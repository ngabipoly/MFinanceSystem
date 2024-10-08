<?php
namespace App\Controllers;
use App\Models\ClientModel as CustomerModel;
use App\Models\UserModel;
use App\Models\IdentificationTypesModel as IDTypesModel;
use App\Models\CustomerAccountModel as AccountModel;
use App\Models\AccountHolderModel;
use App\Models\AccountNaturesModel as AccountCategoryModel;
use App\Models\AccountTypesModel;

class CustomerManager extends BaseController
{
    protected $user;
    public function __construct()
    { 
        if(isset($this->session)){
            return redirect()->to('/');
        }
        $this->user = session()->get('userData');

    }
    public function customerIndex()
    {
        $customers = new CustomerModel();
        $data = [
            'user'=>$this->user,
            'customers' => $customers->customerList(),
            'page'=>'Customer Listing'
        ];
        return view('customer/customer_manager', $data);
    }

    public function customerRegistration(){
        $idTypes = new IDTypesModel(); 
        $mode = ($this->request->uri->getSegment(3))?$this->request->uri->getSegment(3):'create';
        $data=[
            'idTypes'=> $idTypes->findAll(),
            'user'=>$this->user,
            'page'=>'Register Customer',
            'mode' => $mode
        ];

        if($mode=='edit'){
            $customer = new CustomerModel();
            $data['customer'] = $customer->asObject()->find($this->request->uri->getSegment(4));
        }
        return view('customer/customer_register', $data);
    }

    public function saveCustomer()
    {
        try {
            $exec_mode = $this->request->getPost('exec-mode');
            $rules = [
                'first-name' => 'required|alpha_dash|max_length[50]',
                'last-name' => 'required|alpha_dash|max_length[50]',
                'middle-name' => 'permit_empty|alpha_dash|max_length[50]',
                'customer-email' => 'required|valid_email|max_length[100]',
                'phone-number' => 'required|numeric|min_length[9] |max_length[12]',
                'id-number' => 'required',
                'id-type' => 'required|numeric',
                'occupation' => 'required',
                'gender' => 'required',
                'marital-status' => 'required',
                'date-of-birth' => 'required|valid_date',
                'address' => 'required',
                'next-of-kin' => 'required',
                'next-of-kin-address' => 'required',
                'next-of-kin-contact' => 'required|numeric|min_length[9] |max_length[12]',
                'next-of-kin-type' => 'required',
                'annual-income' => 'required|numeric',
            ];

            
            $image = \Config\Services::image();
            //get uploaded file
            $customer_photo = $this->request->getFile('customer-photo');
            $id_face = $this->request->getFile('id-front-face');
            $id_back = $this->request->getFile('id-back-face');
            $randomFaceName = '';
            $randomBackName = '';
            $randomPhotoName = '';
            $randomSignatureName = '';
            $customer_signature = $this->request->getFile('customer-signature');
            $exec_mode = $this->request->getPost('exec-mode');
            $data=[
                    'FirstName' => $this->request->getPost('first-name'),
                    'LastName' => $this->request->getPost('last-name'),
                    'MiddleName' => $this->request->getPost('middle-name'),
                    'Email' => $this->request->getPost('customer-email'),
                    'PhoneNumber' => $this->request->getPost('phone-number'),
                    'CreatedBy' => $this->request->getPost('added-by'),
                    'IDNumber' => $this->request->getPost('id-number'),
                    'IdType' => $this->request->getPost('id-type'),
                    'Occupation' => $this->request->getPost('occupation'),
                    'Gender' => $this->request->getPost('gender'),
                    'MaritalStatus' => $this->request->getPost('marital-status'),
                    'DateOfBirth' => $this->request->getPost('date-of-birth'),
                    'Address' => $this->request->getPost('address'),
                    'NextOfKin' => $this->request->getPost('next-of-kin'),
                    'NextOfKinAddress' => $this->request->getPost('next-of-kin-address'),
                    'NextOfKinPhoneNumber' => $this->request->getPost('next-of-kin-contact'),
                    'NextOfKinRelationship' => $this->request->getPost('next-of-kin-type'),
                    'AnnualIncome' => $this->request->getPost('annual-income')
            ];
            if($customer_photo->isValid() && !$customer_photo->hasMoved()) {
                $randomPhotoName = $customer_photo->getRandomName();
                $image->withFile($customer_photo)
                ->resize(1024, 768, true, 'auto')
                ->save(CLIENT_PHOTO_PATH.$randomPhotoName,75);
            }

            if($id_face->isValid() && !$id_face->hasMoved()) {
                $randomFaceName = $id_face->getRandomName();
                $image->withFile($id_face)
                ->resize(1024, 768, true, 'auto')
                ->save(ID_FRONT_PHOTO_PATH.$randomFaceName,75);
            }

            if($id_back->isValid() && !$id_back->hasMoved()) {
                $randomBackName = $id_back->getRandomName();
                $image->withFile($id_back)
                ->resize(1024, 768, true, 'auto')
                ->save(ID_BACK_PHOTO_PATH.$randomBackName,75);
            }

            if($customer_signature->isValid() && !$customer_signature->hasMoved()) {
                $randomSignatureName = $customer_signature->getRandomName();
                $image->withFile($customer_signature)
                ->resize(1024, 768, true, 'auto')
                ->save(SIGNATURE_PATH.$randomSignatureName,75);
            }
            

            if($exec_mode == 'create') {
                $rules['customer-photo'] = 'uploaded[customer-photo]|is_image[customer-photo]|mime_in[customer-photo,image/jpg,image/jpeg,image/gif,image/png]|max_size[customer-photo,2048]';
                $rules['id-front-face'] = 'uploaded[id-front-face]|is_image[id-front-face]|mime_in[id-front-face,image/jpg,image/jpeg,image/gif,image/png]|max_size[id-front-face,2048]';
                $rules['id-back-face'] = 'uploaded[id-back-face]|is_image[id-back-face]|mime_in[id-back-face,image/jpg,image/jpeg,image/gif,image/png]|max_size[id-back-face,2048]';
                $rules['customer-signature'] = 'uploaded[customer-signature]|is_image[customer-signature]|mime_in[customer-signature,image/jpg,image/jpeg,image/gif,image/png]|max_size[customer-signature,2048]';
                $data['DateCreated'] = date('Y-m-d H:i:s');
                $data['CreatedBy'] = $this->user['UserId'];
                $data['CustomerPhoto'] = $randomPhotoName;
                $data['IDFrontFace'] = $randomFaceName;
                $data['IDBackFace'] = $randomBackName;
                $data['CustomerSignature'] = $randomSignatureName;
            }
           
            if (!$this->validate($rules)) {
                $validation = \Config\Services::validation();
                $data['validation'] = $validation;
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }            


            $customer = new CustomerModel();
            if($exec_mode == 'edit') {
                $data['CustomerId'] = $this->request->getPost('customer-id');
                $update_customer = $customer->update($data['CustomerId'], $data);
                if(!$update_customer) {
                    return redirect()->back()->withInput()->with('errors', $customer->errors());                    
                }
                return redirect()->to(base_url('customer/index'))->with('success', 'Customer updated successfully');
            }

            $add_customer = $customer->insert($data);
            if(!$add_customer) {
                return redirect()->back()->withInput()->with('errors', $customer->errors());
            }

            return redirect()->to(base_url('customer/index'))->with('success', 'Customer added successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('errors', $e->getMessage());
        }
    }

    public function deleteCustomer()
    {
        try {
                $customer = new CustomerModel();
                $customerId = $this->request->getPost('delete-customer-id');
                
                // Update the deletion fields
                $data = [
                    'DeletedAt' => date('Y-m-d H:i:s'),
                    'DeletedBy' => $this->request->getPost('deleted-by'),
                    'Deleted' => 1
                ];
            
                // Perform the update to mark the customer as deleted
                $delete_customer=$customer->update($customerId, $data);
            
                // Handle errors if deletion fails
                if (!$delete_customer) {
                    $deleteStatus = [
                        'status'=>'error',
                        'message' => $customer->errors()
                    ];
                    return json_encode($deleteStatus);
                }
            
                // If successful, redirect to the customer index page
                $deleteStatus = [
                    'status'=>'success',
                    'message' => 'Customer deleted successfully'
                ];
                return json_encode($deleteStatus);
            } catch (\Exception $e) {
                $deleteStatus = [
                    'status'=>'error',
                    'message' => $e->getMessage()
                ];
                return json_encode($deleteStatus);
            }           
    } 
    
    public function accountIndex():string{
        $account = new AccountModel();
        $data['accounts'] = $account->getAccounts();
        return view('customer/account_list', $data);
    }

    public function getCustomerList():string{
        try {
            $customer = new CustomerModel();
            $data = [
                'data'=> $customer->findAll(),
                'status' => 'success'
            ] ;

            return json_encode($data);
        } catch (\Exception $e) {
            $data=[
                'error'=> $e->getMessage(),
                'status' => 'error'
                ] ;
            return json_encode($data);
        }
    }

    public function createEditAccount()
    {
        log_message('debug', 'Entering createEditAccount function. Request URI is: ' . $this->request->uri->getPath());
        $accountCategory = new AccountCategoryModel();
        $types = new AccountTypesModel();
        $totalSegments = $this->request->uri->getTotalSegments();
        $mode = ($totalSegments >= 3) ? $this->request->uri->getSegment(3) : 'create';
        $accountId = ($totalSegments >= 4) ? $this->request->uri->getSegment(4) : 0;
        $data = [
            'page' => 'Accounts',
            'categories'=>$accountCategory->findAll(),
            'accountTypes' => $types->findAll(),
            'mode' => $mode
        ];
    
        log_message('debug', 'Total URI segments: ' . $totalSegments);
        log_message('debug', 'Mode: ' . $mode);
        log_message('debug', 'Account ID: ' . $accountId);
    
        // Check if mode is valid
        if (!in_array($mode, ['create', 'edit'])) {
            log_message('error', 'Invalid operation mode: ' . $mode);
            throw new \Exception("Invalid operation mode");
        }

        if ($mode === 'edit') {
            $data['accountId'] = $accountId;
            $account = new AccountModel();  
            $holderModel = new AccountHolderModel();      
            $data['account'] = $account->asObject()->find($accountId);
            $data['holders'] = $holderModel->getAccountHolders($accountId);
            log_message('debug', 'Edit Account data: ' . json_encode($data['account']));
                    // If in edit mode and account not found, redirect with error
            if ($mode === 'edit' && !$data['account']) {
                log_message('error', 'Account not found.');
                return redirect()->back()->with('error', 'Account Details not found.');
            }
        }
    
        
        log_message('debug', 'Exiting createEditAccount function. Data: ' . json_encode($data));
        return view('customer/account_form', $data);
    }

    

    public function saveAccount() {
        try {
            log_message('debug', 'Entering saveAccount function.');
            $account = new AccountModel();
            $rules  = [
                'account-name' => 'required|alpha_numeric_punct|min_length[8]|max_length[50]',
                'account-type' => 'required|numeric',
                'account-category' => 'required|numeric',
                'account-status' => 'required',
                'holders' => 'required'
            ];
    
            $data = [
                'AccountName' => $this->request->getPost('account-name'),
                'AccountType' => $this->request->getPost('account-type'),
                'AccountNature' => $this->request->getPost('account-category'),
                'AccountStatus' => $this->request->getPost('account-status'),
                'DateCreated' => date('Y-m-d H:i:s'),
                'CreatedBy' => $this->user['UserId']    
            ];
    
            $maxHolders = $this->request->getPost('max-holders');
            $minHolders = $this->request->getPost('min-holders');
            $holders = $this->request->getPost('holders');
            log_message('debug', 'RawHolders: ' . $holders);
            $holdersArray = explode(':', $holders);  // Changed to 'holdersArray' to avoid confusion with other $data
    
            log_message('debug', 'Data: ' . json_encode($data));
            log_message('debug', 'Holders: ' . json_encode($holdersArray));
    
            if (!$this->validate($rules)) {
                $validation = \Config\Services::validation();
                log_message('debug', 'Validation errors: ' . json_encode($validation->getErrors()));
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }
    
            // Start transaction
            $db = \Config\Database::connect();
            $db->transStart();
    
            $exec_mode = $this->request->getPost('exec-mode');
            if($exec_mode == 'edit') {
                $accountId = $this->request->getPost('account-id');
                log_message('debug', 'Updating account with ID: ' . $accountId);
                $update_account =  $account->update($accountId, $data);
    
                if(!$update_account) {
                    $db->transRollback();
                    log_message('debug', 'Update account failed. Errors: ' . json_encode($account->errors()));
                    return redirect()->back()->withInput()->with('errors', $account->errors());                    
                }
    
                // Complete the transaction after all operations
                $db->transComplete();
                if($db->transStatus() === false) {
                    log_message('debug', 'Transaction failed.');
                    return redirect()->back()->withInput()->with('errors', $account->errors());
                }
    
                log_message('debug', 'Exiting saveAccount function. Update successful.');
                return redirect()->to(base_url('customer/account-list'))->with('success', 'Account updated successfully');
            }
    
            // Add new account
            log_message('debug', 'Inserting new account.');
            $add_account = $account->insert($data);
            if(!$add_account) {
                $db->transRollback();
                log_message('debug', 'Insert account failed. Errors: ' . json_encode($account->errors()));
                return redirect()->back()->withInput()->with('errors', $account->errors());
            }
    
            $accountId = $account->getInsertID();

            //Create account number
            if($exec_mode == 'create') {                
                 $acData = [
                     'AccountNumber' => $this->generateAccountNumber($accountId)
                 ];
             
                 log_message('debug', 'Generating account number for ID: ' . $accountId);
                 $genAccountNum = $account->update($accountId, $acData);
             
                 if(!$genAccountNum) {
                     $db->transRollback();
                     log_message('debug', 'Generating account number failed. Errors: ' . json_encode($account->errors()));
                     return redirect()->back()->withInput()->with('errors', $account->errors());
                 }
            }
    
            // Account holders insertion using the correct model
            log_message('debug', 'Inserting account holders.');
            $accountHolder = new AccountHolderModel();  // Use appropriate model for account holders
    
            foreach ($holdersArray as $holderId) {
                $holderData = [
                    'AccountID' => $accountId,
                    'CustomerID' => $holderId,
                    'CreatedAt' => date('Y-m-d H:i:s'),
                    'createdBy' => $this->user['UserId']
                ];
                $add_account_holder = $accountHolder->insert($holderData);  // Use correct model for inserting holders
    
                if(!$add_account_holder) {
                    $db->transRollback();
                    log_message('debug', 'Insert account holder failed. Errors: ' . json_encode($accountHolder->errors()));
                    return redirect()->back()->withInput()->with('errors', $accountHolder->errors());
                }
            }
    
            // Complete the transaction after all operations
            $db->transComplete();
            if($db->transStatus() === false) {
                log_message('debug', 'Transaction failed.');
                return redirect()->back()->withInput()->with('errors', $account->errors());
            }
    
            log_message('debug', 'Exiting saveAccount function. Insert successful.');
            return redirect()->to(base_url('customer/accounts'))->with('success', 'Account added successfully');            
    
        } catch (\Exception $e) {
            log_message('debug', 'Exception in saveAccount function: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('errors', $e->getMessage());
        }
    }    


    public function changeAccountStatus() {
        log_message('debug', 'Entering changeAccountStatus function. Request URI is: ' . $this->request->uri->getPath());
        $account = new AccountModel();
        $accountId = $this->request->getPost('account-id');
        $accountStatus = $this->request->getPost('account-status');
        $data = [
            'AccountStatus' => $accountStatus
        ];
        log_message('debug', 'Account ID: ' . $accountId . ', Account Status: ' . $accountStatus);
        $update_account = $account->update($accountId, $data);
        if(!$update_account) {
            log_message('debug', 'Update account failed. Errors: ' . json_encode($account->errors()));
            return redirect()->back()->withInput()->with('errors', $account->errors());                    
        }
        log_message('debug', 'Exiting changeAccountStatus function. Update successful.');
        return redirect()->to(base_url('customer/account-list'))->with('success', 'Account status updated successfully');
    }

    public function generateAccountNumber($customerId)
    {
        $accountNumber = 'ACC-' . str_pad($customerId, CUSTOMER_ACCOUNT_LENGTH, ACCOUNT_PAD_CHAR, STR_PAD_LEFT);    
        if ($accountNumber) {
            return $accountNumber;
        }
        return false;
    }  
    
}