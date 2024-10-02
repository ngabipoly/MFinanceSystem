<?php
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\RoleRightsModel;
use App\Models\MenuModel;

helper('App\Helpers\Custom');

class UserManager extends BaseController
{
    protected $user;
   
    public function __construct()
    { 
        if(isset($this->session)){
            return redirect()->to('/');
        }
        $this->user = session()->get('userData');

    }

    public function index()
    {
        $users = new UserModel();
        $roles = new RoleModel();
        $data = [
            'users' => $users->findAll(),
            'roles' => $roles->findAll(),
            'user' => $this->user
        ];
        return view('user_manager', $data);
    }

    public function saveUser()
    {
        try {
            // Validation rules
            $rules = [
                'first-name' => 'required|alpha',
                'last-name' => 'required|alpha',
                'user-email' => 'required|valid_email',
                'user-phone' => 'required|numeric',
                'user-username' => 'required',
                'user-role' => 'required',
                'user-status' => 'required'
            ];
    
            if (!$this->validate($rules)) {
                // Validation failed
                $messages = $this->validator->getErrors();
                $errorsMessages = nl2br(esc(implode("\n", $messages)));
                return json_encode([
                    'status' => 'error',
                    'message' => 'Validation failed' . "\n" . $errorsMessages,
                    'data' => []
                ]);
            }
    
            $defaultPassword = generatePassword();
            $user_hash = password_hash($defaultPassword, PASSWORD_BCRYPT);
            $users = new UserModel();
    
            $exec_mode = $this->request->getPost('exec-mode');
    
            // Prepare user data
            $data = [
                'FirstName' => $this->request->getPost('first-name'),
                'LastName' => $this->request->getPost('last-name'),
                'MiddleName' => $this->request->getPost('middle-name'),
                'Email' => $this->request->getPost('user-email'),
                'PhoneNumber' => $this->request->getPost('user-phone'),
                'RoleId' => $this->request->getPost('user-role'),
                'AccountStatus' => $this->request->getPost('user-status'),
                'CreatedBy' => $this->user['UserId'],
                'Username' => $this->request->getPost('user-username'),
                'Password' => $user_hash
            ];
    
            // File upload handling
            $file = $this->request->getFile('user-photo');
            if ($file && $file->isValid()) {
                $fileName = $file->getRandomName();
                $file->move(ROOTPATH . 'public/assets/images/users/', $fileName);
                $data['UserPhoto'] = $fileName;
            } else {
                if ($file->getError() != UPLOAD_ERR_NO_FILE) { // Ignore if no file is uploaded
                    writeLog("File upload failed: {$file->getError()} - {$file->getErrorString()}");
                }
            }
    
            if ($exec_mode == 'edit') {
                $data['UserId'] = $this->request->getPost('user-id');
                $data['UpdatedBy'] = $this->user['UserId'];
                $update_user = $users->update($data['UserId'], $data);
    
                if (!$update_user) {
                    writeLog('User Update Failed! : ' . join(' ', "UserID: $data[UserId]", "FirstName: $data[FirstName]", "LastName: $data[LastName]", "Status: $data[AccountStatus]", "Errors: " . json_encode($users->errors())));
                    return json_encode([
                        'status' => 'error',
                        'message' => 'Failed to update user',
                        'data' => $users->errors()
                    ]);
                }
    
                writeLog('User Update Success! : ' . join(' ', "UserID: $data[UserId]", "FirstName: $data[FirstName]", "LastName: $data[LastName]", "Status: $data[AccountStatus]"));
                return json_encode([
                    'status' => 'success',
                    'message' => 'User updated successfully',
                    'data' => $data
                ]);
            }
    
            // Insert new user
            $add_user = $users->insert($data);
            if (!$add_user) {
                writeLog("User Creation Failed! FirstName: $data[FirstName] LastName: $data[LastName] Status: $data[AccountStatus] Errors: " . json_encode($users->errors()));
                return json_encode([
                    'status' => 'error',
                    'message' => 'Failed to add user',
                    'data' => $users->errors()
                ]);
            }
    
            // Get last inserted ID and assign a UserCode based on it
            $insertedId = $users->insertID();
            $nextUserCode = $insertedId + USER_CODE_BASE;
    
            // Update the UserCode in the database
            $update_user_code = [
                'UserId' => $insertedId,
                'UserCode' => $nextUserCode
            ];
            $users->update($insertedId, $update_user_code);
    
            writeLog("User Creation Success! : FirstName: $data[FirstName] LastName: $data[LastName] UserCode: $nextUserCode Status: $data[AccountStatus]");
    
            return json_encode([
                'status' => 'success',
                'message' => "User created successfully with User Code: $nextUserCode",
                'data' => $data
            ]);
    
        } catch (\Exception $e) {
            return json_encode([
                'status' => 'error',
                'message' => 'An error occurred',
                'data' => $e->getMessage()
            ]);
        }
    }
    


    function createAdminUser() {
        $users = new UserModel();
        $data['FirstName'] = 'Admin';
        $data['LastName'] = 'User';
        $data['MiddleName'] = 'Admin';
        $data['Email'] = 'ngabipoly@gmail.com';
        $data['PhoneNumber'] = '256752608744';
        $data['RoleId'] = 1;
        $data['AccountStatus'] = 1;
        $data['CreatedBy'] = 1;
        $data['Username'] = 'admin';
        $data['SecretHash'] = password_hash('admin202408', PASSWORD_BCRYPT);
        $data['UserCode'] = 1001;
        $add_user = $users->insert($data);
        if(!$add_user) {
            $add_status = [
                    'status'=> 'error', 
                    'message' => 'Failed to add user', 
                    'data' => $users->errors()
                ];
            return json_encode($add_status);
        }
        $add_status = [
            'status'=> 'success', 
            'message' => 'User added successfully', 
            'data' => $data
        ];
        return json_encode($add_status);
    }

    /**password reset request
     * Generate random token
     * save token in database with user id and set reset required field to 1
     * send link to email with radomly generated password token 
     */

     function requestPasswordReset(){
        try {
            $users = new UserModel();
            $email = $this->request->getPost('user-email');
            $data['UserId'] = $this->request->getPost('user-id');
            $data['PasswordResetToken'] = generatePassword(8);
            $data['PasswordResetRequired'] = 1;
            $update_user = $users->update($data['UserId'], $data);

            if(!$update_user) {
                $add_status = [
                    'status'=> 'error', 
                    'message' => 'Failed to update user', 
                    'data' => $users->errors()
                ];
                return json_encode($add_status);
            }

            //send email
            $email = \Config\Services::email();
            $email->setTo($email);
            $email->setFrom('pRqkT@example.com', 'DigFi Alerts');
            $email->setSubject('Password Reset Request');
            $email->setMessage('
                <h1>Password Reset Request</h1>
                <p>Your Password has been reset, use Token:</p>
                <p>'.$data['PasswordResetToken'].'</p>
            ');
            $mail_send = $email->send();

            if(!$mail_send) {
                $add_status = [
                    'status'=> 'error', 
                    'message' => 'Failed to send email', 
                    'data' => $email->printDebugger()
                ];
                return json_encode($add_status);
            }

            $add_status = [
                'status'=> 'success', 
                'message' => 'Password reset request sent successfully', 
                'data' => $data
            ];
            return json_encode($add_status);
        } catch (\Exception $e) {
            $add_status = [
                'status'=> 'error', 
                'message' => 'Failed to add user', 
                'data' => $e->getMessage()
            ];
            return json_encode($add_status);
        }
     }

    function changePassword(){
        $db = \Config\Database::connect();  // Get the database connection
        $updateData=[];
   
        try {        
            $rules = [
            'user-code'    => 'required',
            'old-pass-phrase' => 'required|min_length[8]|max_length[255]',
            'new-pass-phrase' => 'required|min_length[8]|max_length[255]',
            'confirm-pass-phrase' => 'required|min_length[8]|max_length[255]',
            'verification-code' => 'required|min_length[8]|max_length[255]',
            ];

            if($this->checkNotSame($this->request->getPost('new-pass-phrase'), $this->request->getPost('confirm-pass-phrase'))) {
                $data = [
                    'status'=> 'error',
                    'message' => 'New password and confirm password do not match',
                    'data' => []
                ];
                return json_encode($data);
            }

            if(!$this->checkNotSame($this->request->getPost('new-pass-phrase'), $this->request->getPost('old-pass-phrase'))) {
                $data = [
                    'status'=> 'error',
                    'message' => 'Old password and new password cannot be same',
                    'data' => []
                ];
                return json_encode($data);
            }

                // Validate the input data
                if (!$this->validate($rules)) {
                    // If validation fails, redirect back with input and errors
                    $messages = $this->validator->getErrors();
                    $errorsMessages = nl2br(esc(implode("\n", $messages))) ;

                    $data = [
                        'status'=> 'error',
                        'message' => $errorsMessages,
                        'data' => []
                    ]; 
                    return json_encode($data);                   
                }

                $users = new UserModel();
                $mandated = $this->request->getPost('mandated') ?? false;
                $userCode = $this->request->getPost('user-code');
                $password = $this->request->getPost('old-pass-phrase');
                $updateData['SecretHash'] = password_hash($this->request->getPost('new-pass-phrase'),  PASSWORD_BCRYPT);
                if ($mandated) {
                    $updateData['ResetRequired'] = 0;
                }                
                $user = $users->where('UserCode', $userCode)->first();
                $updateData['UserId'] = $user['UserID'];


                if (!password_verify($password, $user['SecretHash'])) {
                    $data = [
                        'status'=>'error',
                        'message'=> 'Invalid User name/Old password provided',
                        'data' => []
                    ];
                    return json_encode($data);
                }

                $updateUser = $users->save($updateData);
                //view generated update statemen                

                if(!$updateUser) {
                    $updateStatus = [
                        'status'=> 'error', 
                        'message' => 'Failed to update user', 
                        'data' => ['error'=>$users->errors(), 'sql'=> $this->db->getLastQuery() ]
                    ];
                    return json_encode($updateStatus);
                }

                $updateStatus = [
                    'status'=> 'success',
                    'message' => 'Success! you can now login with New Password', 
                    'data' => $updateData
                ];

                if($mandated){
                    //end session
                    $session = session();
                    $session->destroy();
                }
                return json_encode($updateStatus);
        } catch (\Exception $e) {
            $update_status = [
                'status'=> 'error', 
                'message' => 'Error updating user', 
                'data' => "Error: ".$e->getMessage()." Line: ".$e->getLine(),
                'sql'=> $db->getLastQuery() 
            ];
            return json_encode($update_status);
        }
    }

    function checkNotSame($newPassphrase, $oldPassphrase)
        {
            if ($newPassphrase === $oldPassphrase) {
                return false;
            }
            return true;
        }

    function mandatoryPassChange(){
        $data['page'] = 'Mandate Password Change';
        return view('forms/pass-change', $data);
    }

    #List user roles
    public function listUserRoles()
    {
        $roles = new RoleModel();
        $data = [
            'roles' => $roles->findAll(),
            'user' => $this->user
        ];
        return view('forms/user_role_management', $data);
    }

    #add a new user role

    public function saveUserRole()
    {
        try {
            $data = [];
            $roles = new RoleModel();
            $exec_mode = $this->request->getPost('exec-mode');
            $dateRange = $this->request->getPost('role-daterange');
            $dates = explode(':', $dateRange);
            $data['StartDate'] = $dates[0];
            $data['EndDate'] = $dates[1];
            $data['RoleName'] = $this->request->getPost('user-role-name');
            $data['Description'] = $this->request->getPost('user-role-description');
            $data['CreatedBy'] = $this->request->getPost('added-by');

            if($exec_mode == 'edit') {
                $data['RoleId'] = $this->request->getPost('role-id');
                $update_role = $roles->save($data);
                if(!$update_role) {
                    $update_status = [
                        'status'=> 'error', 
                        'message' => 'Failed to update user role', 
                        'data' => $roles->errors()
                    ];
                    return json_encode($update_status);
                }
            }

            $add_role = $roles->save($data);
            if(!$add_role) {
                $add_status = [
                    'status'=> 'error',
                    'message' => 'Failed to add user role',
                    'data' => $roles->errors()
                ];
                return json_encode($add_status);
            }

            $add_status = [
                'status'=> 'success',
                'message' => 'User role added successfully',
                'data' => $data
            ];
            return json_encode($add_status);

        } catch (\Exception $e) {
            $add_status = [
                'status'=> 'error',
                'message' => 'Failed to add user role. ' . $e->getMessage(),
                'data' => []
            ];
            return json_encode($add_status);
        }
    }

    public function deleteRole()
    {
        try{
            $roles = new RoleModel();
            $data['RoleId'] = $this->request->getPost('user-role-id');
            $data['DeletedBy'] = $this->request->getPost('deleted-by');
            $delete_role = $roles->delete($data['RoleId']);
            if(!$delete_role) {
                $delete_status = [
                    'status'=> 'error',
                    'message' => 'Failed to delete user role',
                    'data' => $roles->errors()
                ];
                return json_encode($delete_status);
            }
            $delete_status = [
                'status'=> 'success',
                'message' => 'User role deleted successfully',
                'data' => $data
            ];
            return json_encode($delete_status);
        } catch (\Exception $e) {
            $delete_status = [
                'status'=> 'error',
                'message' => 'Failed to delete user role. ' . $e->getMessage(),
                'data' => []
            ];
            return json_encode($delete_status);
        }
    } 

    function loadRightsMenus(){
        $data = [];
        $entity_type = $this->request->getGet('entity_type');
        $entity_id = $this->request->getGet('entity_id');
        $given_menus = $this->getAssigned($entity_type,$entity_id);
        $assigned_ids = array_map(function($assign){
               return $assign['MenuID'];
        },$given_menus);
        $data['assigned'] = $this->getRoleMenus($assigned_ids);
        $data['unassigned'] = $this->getRoleMenus($assigned_ids,'U');
        return view('partials/rights-assignment',$data);
    }
    function getAssigned(string $type, int $id) {
        $model = new RoleRightsModel();
        $where_data = [
            'RoleID' => $id,
            'AssignmentType' => $type,
            'DeletedBy' => null // DeletedBy must be NULL
        ];
        
        return $model
                    ->where($where_data)
                    ->groupStart()
                        ->where('DeletionDate', null)
                        ->orWhere('DeletionDate >', date('Y-m-d'))
                    ->groupEnd()
                    ->findAll();
    }
    

    //gets role menus (by default gets all assigned menus)
    function getRoleMenus(array $menu_ids,string $op_type='A'){
        $model = new MenuModel();
        if($op_type=='U'){
            return (!empty($menu_ids)) ? $model->whereNotIn('MenuID',$menu_ids)->findAll():$model->findAll();
        }
        return (!empty($menu_ids))? $model->whereIn('MenuID',$menu_ids)->findAll():null;        
    }

    
    public function saveRights(){
        try {        
            $entity_id = $this->request->getPost('entity-id');
            $entity_type = 'G';
            $rights_revoke = $this->request->getPost('revoke-list');
            $right_grant = $this->request->getPost('assign-list');
            $user = $this->user['UserId'];
            $assign = [];
            $revoke = [];
            $message = '';
    
            $give_rights = explode(':',$right_grant);
            $revoke_rights =explode(':',$rights_revoke);
    
            if(!empty($right_grant)){
                $assign = $this->assignRights($entity_type,$entity_id, $user, $give_rights);
                $message .= 'Assignment '.$assign['message'];
            }
    
            if(!empty($rights_revoke)){
                $revoke = $this->revokeRights($entity_id,$entity_type, $user, $revoke_rights);
                $message .= '\n Revocation:'.$revoke['message'];
            }
                
            return json_encode(['status'=>'info','message'=>$message]);     
        } catch (\Exception $e) {
            return json_encode([
                'status'=>'error',
                'message'=>$e->getMessage()
            ]);
        }
   
    }
    public function revokeRights(int $entity_id, string $entity_type, int $user, array $selected_menus) {
        try {
            $model = new RoleRightsModel();
            
            // Data to update
            $data = [
                'EndDate' => date('Y-m-d H:i:s'),
                'DeletedBy' => $user,
                'DeletionDate' => date('Y-m-d H:i:s')
            ];
    
            // Update records where 'RoleMenuID' is in the selected menus
            $revoke = $model->set($data)
                            ->whereIn('MenuID', $selected_menus)
                            ->where('RoleID', $entity_id)
                            ->where('AssignmentType', $entity_type)
                            ->where('DeletionDate', null)
                            ->where('DeletedBy', null)
                            ->update();
    
            if ($revoke === false) {
                return [
                    'status' => 'error',
                    'message' => 'Failed to revoke rights',
                    'data' => $model->errors()
                ];
            }
    
            // Get the number of affected rows
            $affectedRows = $model->affectedRows();
    
            return [
                'status' => 'success',
                'message' => 'Success!'.$affectedRows.' Rights Revoked',
            ];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
    

    public function assignRights(string $entity_type, int $entity_id,int $user, array $granted){
        try {
            $model = new RoleRightsModel();
            $data = [];
            foreach ($granted as $grant_id) {
                $row =[
                    'RoleID'=>$entity_id, 
                    'MenuID'=>$grant_id,
                    'AssignmentType'=>$entity_type,
                    'CreatedBy'=>$user,
                    'StartDate'=>date('Y-m-d H:i:s')
                ];
                array_push($data,$row);
            }
            writeLog('Rigths to assign: '.json_encode($data));
            $assign = $model->insertBatch($data);
            if(!$assign){
                return [
                    'status'=>'error',
                    'message'=>'Rights Assignment Failed!',
                    'data' => $model->errors()
                ];
            }
             return ['status'=>'success','message'=>'Rights Assignment Complete'];
        } catch (\Exception $e) {
            return ['status'=>'error','message'=>$e->getMessage()];
        }
    }

}