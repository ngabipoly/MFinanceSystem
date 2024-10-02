<?php
namespace App\Controllers;
use App\Models\AccountModel;
use App\Models\UserModel;
use App\Models\AccountNatureModel;
use App\Models\AccountTypeModel;
use App\Models\AccountHolderModel;

class AccountManagement extends BaseController
{
    public function index()
    {
        $users = new UserModel();
        $data = [
            'users' => $users->findAll()
        ];
        return view('account_manager', $data);
    }

    public function saveAccount()
    {
        try {
            $exec_mode = $this->request->getPost('exec-mode');
            $data['AccountName'] = $this->request->getPost('account-name');
            $data['AccountNature'] = $this->request->getPost('account-nature');
            $data['AccountNumber'] = $this->request->getPost('account-number');
            $data['AccountType'] = $this->request->getPost('account-type');
            $data['AccountStatus'] = $this->request->getPost('account-status');
            $data['CreatedBy'] = $this->request->getPost('added-by');

            $account = new AccountModel();
            if($exec_mode == 'edit') {
                $data['AccountId'] = $this->request->getPost('account-id');
                $update_account = $account->update($data['AccountId'], $data);
                if(!$update_account) {
                    $add_status = [
                        'status'=> 'error', 
                        'message' => 'Failed to update account', 
                        'data' => $account->errors()
                    ];
                    return json_encode($add_status);
                }

                $add_status = [
                    'status'=> 'success', 
                    'message' => 'Account updated successfully', 
                    'data' => $data
                ];
                return json_encode($add_status);
            }

            $add_account = $account->insert($data);
            if(!$add_account) {
                $add_status = [
                    'status'=> 'error',
                    'message' => 'Failed to add account',
                    'data' => $account->errors()
                ];
                return json_encode($add_status);
            }

            $add_status = [
                'status'=> 'success',
                'message' => 'Account added successfully',
                'data' => $data
            ];
            return json_encode($add_status);
        } catch (\Exception $e) {
            $add_status = [
                'status'=> 'error',
                'message' => $e->getMessage(),
                'data' => []
            ];
            return json_encode($add_status);
        }
    }

    public function deleteAccount()
    {
        try {
            $account = new AccountModel();
            $data['AccountId'] = $this->request->getPost('account-id');
            $data['DeletedBy'] = $this->request->getPost('deleted-by');
            $delete_account = $account->delete($data['AccountId']);
            if(!$delete_account) {
                $add_status = [
                    'status'=> 'error',
                    'message' => 'Failed to delete account',
                    'data' => $account->errors()
                ];
                return json_encode($add_status);
            }
            $add_status = [
                'status'=> 'success', 
                'message' => 'Account deleted successfully', 
                'data' => $data
            ];
            return json_encode($add_status);
        } catch (\Exception $e) {
            $add_status = [
                'status'=> 'error',
                'message' => $e->getMessage(),
                'data' => []
            ];
            return json_encode($add_status);
        }
    }

    public function getAccountTypes()
    {
        try {
            $account_types = new AccountTypeModel();
            $data = [
                'account_types' => $account_types->findAll()
            ];
            return $data;
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function getAccountNatures()
    {
        try {
            $account_natures = new AccountNatureModel();
            $data = [
                'account_natures' => $account_natures->findAll()
            ];
            return $data;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getAccountHolders()
    {
        try {
            $account_code = $this->request->getPost('account-code');
            $account_holders = new AccountHolderModel();
            $get_data = $account_holders->
                                        where('AccountID', $account_code)->findAll();
            if(!$get_data) {
                $data = [
                    'status' => 'error',
                    'message' => 'Failed to get account holders',
                    'data' => [],
                    'errors' => $account_holders->errors()
                ];
                return $data;
            }
            $data = [
                'status' => 'success',
                'message' => 'Account holders retrieved successfully',
                'account_holders' => $get_data
            ];
            return $data;
        } catch (\Exception $e) {
            $data = [
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => [],
            ];
            return $data;
        }
    }

    public function addAccountHolder()
    {
        try {
            $exec_mode = $this->request->getPost('exec-mode');
            $data['AccountID'] = $this->request->getPost('account-id');
            $data['userID'] = $this->request->getPost('client-id');
            $data['CreatedBy'] = $this->request->getPost('user-id');
            $account_holders = new AccountHolderModel();
            
            if ($exec_mode == 'edit') {
                $update_account_holder = $account_holders->update($data['AccountHolderId'], $data);
                if(!$update_account_holder) {
                    $update_status = [
                        'status'=> 'error',
                        'message' => 'Failed to update account holder',
                        'data' => $account_holders->errors()
                    ];
                    return json_encode($update_status);
                }

                $update_status = [
                    'status'=> 'success',
                    'message' => 'Account holder updated successfully',
                    'data' => $data
                ];
                return json_encode($update_status);
            }
            $add_account_holder = $account_holders->insert($data);
            if(!$add_account_holder) {
                $add_status = [
                    'status'=> 'error',
                    'message' => 'Failed to add account holder',
                    'data' => $account_holders->errors()
                ];
                return json_encode($add_status);
            }
            $add_status = [
                'status'=> 'success',
                'message' => 'Account holder added successfully',
                'data' => $data
            ];
            return json_encode($add_status);
        } catch (\Exception $e) {
            $add_status = [
                'status'=> 'error',
                'message' => $e->getMessage(),
                'data' => []
            ];
            return json_encode($add_status);
        }
    }

    public function deleteAccountHolder()
    {
        try {
            $account_holders = new AccountHolderModel();
            $data['AccountHolderId'] = $this->request->getPost('account-holder-id');
            $data['DeletedBy'] = $this->request->getPost('deleted-by');
            $delete_account_holder = $account_holders->delete($data['AccountHolderId']);
            if(!$delete_account_holder) {
                $delete_status = [
                    'status'=> 'error',
                    'message' => 'Failed to delete account holder',
                    'data' => $account_holders->errors()
                ];
                return json_encode($delete_status);
            }
            $delete_status = [
                'status'=> 'success',
                'message' => 'Account holder deleted successfully',
                'data' => $data
            ];
            return json_encode($delete_status);
        } catch (\Exception $e) {
            $delete_status = [
                'status'=> 'error',
                'message' => $e->getMessage(),
                'data' => []
            ];
            return json_encode($delete_status);
        }

    }

}
