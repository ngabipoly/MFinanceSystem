<?php 
namespace App\Controllers;
use App\Models\UserModel;
use App\Models\MenuModel;
use App\Models\RoleModel;
use App\Models\RoleRightsModel;
use App\Models\FailedLogin;
use CodeIgniter\Session\Handlers\FileHandler;


class AuthController extends BaseController
{
    protected $session;

    public function __construct()
    {
        // Load the session service
        $this->session = \Config\Services::session();
    }
        public function index()
        {
            $users = new UserModel();
            $data = [
                'users' => $users->findAll()
            ];
            return view('auth', $data);
        }

    public function login()
    {
        try {
        // Define validation rules
            $rules = [
                'user-code'    => 'required',
                'pass-phrase' => 'required|min_length[8]|max_length[255]',
                'verification-code' => 'required|min_length[8]|max_length[255]',
            ];

            // Validate the input data
            if (!$this->validate($rules)) {
                // If validation fails, redirect back with input and errors
                //return redirect()->back()->withInput()->with('validation', $this->validator);
                $messages = $this->validator->getErrors();
                $errorsMessages = nl2br(esc(implode("\n", $messages))) ;

                $data = [
                    'status'=> 'error',
                    'message' => $errorsMessages,
                    'data' => []
                ];

                return json_encode($data);
            }

            $userCode = $this->request->getPost('user-code');
            $password = $this->request->getPost('pass-phrase');
            $veriCode = $this->request->getPost('verification-code');
            // Check if the account is locked
            if ($this->isAccountLocked($userCode)) {

                $data = [
                    'status'=> 'error',
                    'message' => 'Your account is locked. Please contact your administrator.',
                    'data' => []
                ];

                return json_encode($data);
            }

            $login = $this->authenticate($userCode, $password, $veriCode );

            if($login == 'Invalid_user'){
                $data = [
                    'status'=> 'error',
                    'message' => 'Invalid User Name or Password.',
                    'data' => ['user_code' => $userCode]
                ];
                return json_encode($data);                
            }

            if($login == 'Invalid_password'){
               // $this->logFailedLoginAttempt($userCode);
                $data = [
                    'status'=> 'error',
                    'message' => 'Invalid User Name or Password.',
                    'data' => ['Invalid_password']
                ];
                return json_encode($data);
            }

            $accessDetails = $this->getAccessItems($login['RoleID'], $login['UserID']);

            // Set user data in session
                $userData = [
                    'UserId'    => $login['UserID'],
                    'Email'      => $login['Email'],
                    'logged_in'  => true,
                    'RoleId'       => $login['RoleID'],
                    'FirstName' => $login['FirstName'], 
                    'LastName' => $login['LastName'],
                    'UserPhoto' => $login['UserPhoto'],
                    'RoleName' => $accessDetails['roleName'],
                    'menus' => $accessDetails['menus'],
                    'lists' => $accessDetails['lists'],
                ];

                $this->session->start();
                $this->session->set('isLoggedIn', true);
                $this->session->set('userData',$userData);

                $reset = ($login['ResetRequired']) ? 'Y' : 'N';
                $data = [
                    'status'=> 'success',
                    'message' => 'Login successful.',
                    'forcePwdChange' => $reset
                ];
                return json_encode($data);        
        } catch (\Exception $e) {
                // Get the last executed query
            $db = \Config\Database::connect();  // Get the database connection
            $lastQuery = $db->getLastQuery();   // Get the last query
            $messages = $e->getMessage();
            $lineNumber = $e->getLine();
            
            $data = [
                'status'=> 'error',
                'message' => "$messages on Line $lineNumber",
                'data' => [$this->request->getPost('user-code'),$lastQuery]
            ];

            return json_encode($data);
        }
    }

    private function authenticate(string $userCode, string $password, string $verifyCode)
    {
        $user = new UserModel();        
        $user = $user->where('UserCode', $userCode)->first();
        $data = [
            'status'=> 'error',
            'message' => 'Invalid User Name or Password.'
        ];
        if (!$user) {
           return 'Invalid_user';
        }
        if (!password_verify($password, $user['SecretHash'])) {
            return 'Invalid_password';
        }
/** 
        if ($user['verifyCode'] != $verifyCode) {
            return 1;
        }
            */
        return $user;
    }

    function getAccessItems(int $roleId, int $userId): array {

        $role = new RoleModel();
        $rights = new RoleRightsModel();
        $menu = new MenuModel();
        $currentDate = date('Y-m-d');
        
        $roleName = $role->select('RoleName')->where(['RoleID'=>$roleId])->first();
        $assigned = $rights->select('RoleMenuID')
                            ->where('RoleID', $roleId)
                            ->where('AssignmentType', 'G')
                            ->where('StartDate <=', $currentDate)
                            ->where('EndDate >=', $currentDate)
                            ->findAll();

        $specialUserRights = $rights->select('RoleMenuID')
                                    ->where('RoleID', $roleId)
                                    ->where('AssignmentType', 'G')
                                    ->where('StartDate <=', $currentDate)
                                    ->where('EndDate >=', $currentDate)
                                    ->findAll();

        $ids = []; 
        
        foreach ($assigned as $menu_id) {
            array_push($ids,$menu_id['MenuId']);
        }

        foreach ($specialUserRights as $menu_id) {
            array_push($ids,$menu_id['MenuId']);
        }

        //$menus = $menu->where(['type'=>'Link'])->orderBy('MenuGroup')->find($ids);
        $menus = $menu->where(['MenuType'=>'Link'])->orderBy('MenuGroup')->findAll();
        //$lists = $menu->where(['type'=>'List'])->orderBy('MenuGroup')->find($ids);
        $lists = $menu->where(['MenuType'=>'List'])->orderBy('MenuGroup')->findAll();
        
        return ['roleName' => $roleName, 'menus' => $menus, 'lists' => $lists];
    }

    private function logFailedLoginAttempt(string $user_code)
    {
        try {
            // Store the failed login attempt in the database
            $user = new UserModel();
            $failedLogin = new FailedLoginModel();
            $data = [
                'user_code' => $user_code,
                'IPAddress' => $this->getIpAddress(),
            ];

            $failedLog= $failedLogin->insert($data);

            if (!$failedLog) {
                $data = [
                    'status'=> 'error',
                    'message' => 'Failed to log failed login attempt with user code: ' . $user_code.' and IP address: '.$this->getIpAddress(),
                    //failure reason
                    'data' => [ 'reason' => $failedLogin->errors()]
                ];
                $data= json_encode($data);
                //add data to log file
                $this->logger->error($data);
            }

            $user = $user->where('UserCode', $user_code)->first();
            #log last executed query
            log_message('info', 'Query: ' . $query->getQuery());
            if ($user) {
                $user->FailedLoginAttempts++;
                $user->save();
            }

            return true;
        } catch (\Exception $e) {
            $data = [
                'status'=> 'error',
                'message' => 'Failed to log failed login attempt with user code: ' . $user_code.' and IP address: '.$this->getIpAddress(),
                //failure reason
                'data' => [ 'reason' => $e->getMessage()]
            ];
            $data= json_encode($data);
            //add data to log file
            $this->logger->error($data);
            return false;
        }
    }

    private function isAccountLocked(string $user_code)
    {
        $user = new UserModel();
        $user = $user->where('UserCode', $user_code)->first();
        if ($user && $user['FailedLoginAttempts'] >= 5) {
            return true;
        }
        return false;
    }

    
    /**
     * Retrieves the IP address of the user making the request.
     *
     * Checks for the presence of HTTP_CLIENT_IP, HTTP_X_FORWARDED_FOR, and REMOTE_ADDR
     * server variables to determine the user's IP address.
     *
     * @return string The IP address of the user.
     */
    private function getIpAddress(){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

}