<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\AccountModel;
use App\Models\TransactionModel;
use App\Models\TransactionTypesModel;
use App\Models\TransactionMethodsModel;
use App\Models\TransactionProviderModel;
use App\Models\TransactionStatusModel;
use App\Models\TransactionMethodProviderModel;
use App\Models\LoansProductsModel;
use App\Models\ClientModel as CustomerModel;
use App\Models\LoanModel;
use App\Models\OrganizationModel;
use App\Models\AccountBalanceModel;

helper('App\Helpers\Custom');

class FinanceController extends BaseController{

    public function __construct(){
        $this->user = session()->get('userData');
    }


    public function loanApplications(){
        $loanModel = new LoanModel();
        $data = [
            'title' => 'Loan Applications',
            'page'=>'Loan Applications',
            'user' => $this->user,
            'loanApplications' => $loanModel->getLoans()
        ];
        return view('finance/loan-applications', $data);
    }

    public function applyLoan(){
        $mode = ($this->request->uri->getSegment(3))?$this->request->uri->getSegment(3):'create';
        $customer = new CustomerModel();
        $loanProducts = new LoansProductsModel();
        $data = [
            'mode' => $mode,
            'title' => 'Apply Loan',
            'page'=>'Apply Loan',
            'user' => $this->user,
            'customers' => $customer->asObject()->customerList(),
            'loanProducts' => $loanProducts->findAll()
        ];
        return view('finance/apply-loan', $data);
    }

function saveLoanApplication(){
    $rules=[
        'customer-id' => 'required|numeric',
        'loan-product' => 'required|numeric',
        'loan-amount' => 'required|numeric',
        'loan-duration' => 'required|numeric',
        'loan-purpose' => 'required|max_length[255]'
    ];
    if(!$this->validate($rules)){
        
        $validation = \Config\Services::validation();
        log_message('debug', 'Validation errors: ' . json_encode($validation->getErrors()));
        return redirect()->back()->withInput()->with('errors', $validation->getErrors());
    }

    $loanModel = new LoanModel();

    $data = [
        'ClientID' => $this->request->getPost('customer-id'),
        'ProductID' => $this->request->getPost('loan-product'),
        'PrincipalAmount' => $this->request->getPost('loan-amount'),
        'TermMonths' => $this->request->getPost('loan-duration'),
        'Purpose' => $this->request->getPost('loan-purpose')
    ];

    $save = $loanModel->insert($data);

    if(!$save){
        return redirect()->back()->withInput()->with('errors', $loanModel->errors());
    }    
    return redirect()->to('/loans/loan-applications')->with('success', 'Loan Application Created Successfully');    
}

    public function editLoan(){
        

    }
    
    public function manageOrganization(){
        try {
            $transType = new TransactionTypesModel();  
            $transMethods = new TransactionMethodsModel();          

            $data = [
                'title' => 'Manage Organization',
                'page'=>'Manage Organization',
                'user' => $this->user,
                'CreditTransactions' => $transType->where('TypeCategory', 'CRD')->findAll(),
                'DebitTransactions' => $transType->where('TypeCategory', 'DBT')->findAll(),
                'TransactionMethods' => $transMethods->findAll()
            ];
            return view('finance/manage-organization', $data);
        } catch (\Exception $e) {
            return "Error: ". $e->getMessage();
        }
    }

  public function recordOrganizationIncome(){
        
        try {
            log_message('debug', 'Entering recordOrganizationIncome Method');
            $organization = new OrganizationModel();
            //get organization details
            $orgDetails = $organization->first();
            $orgAccountNo = $orgDetails->accountId;
            $prefix = filter_var($this->request->getPost('transaction-prefix'), FILTER_SANITIZE_SPECIAL_CHARS);
            $transNum =  $prefix . '/' . date('mdHis'). substr(uniqid(), -4) . '/' . date('y');   
            $sourceType = filter_var($this->request->getPost('income-source'), FILTER_SANITIZE_SPECIAL_CHARS);
            $amount = filter_var($this->request->getPost('amount'), FILTER_SANITIZE_SPECIAL_CHARS);
            $currency = filter_var($this->request->getPost('currency'), FILTER_SANITIZE_SPECIAL_CHARS);
            $transactionDate = filter_var($this->request->getPost('transaction-date'), FILTER_SANITIZE_SPECIAL_CHARS);
            $transactionSource = filter_var($this->request->getPost('source'), FILTER_SANITIZE_SPECIAL_CHARS);
            $narration = filter_var($this->request->getPost('narration'), FILTER_SANITIZE_SPECIAL_CHARS);
            $transactionMethod = filter_var($this->request->getPost('transaction-method'), FILTER_SANITIZE_SPECIAL_CHARS);

            log_message('debug', 'Transaction Details: ' . json_encode([
                'TransactionNumber'=>$transNum,
                'AccountID'=>$orgAccountNo,
                'TransactionTypeID'=>$sourceType,
                'TransactionAmount'=>$amount,
                'TransactionCurrencyID'=>$currency,
                'TransactionDate'=>(new \DateTime())->format('Y-m-d H:i:s'),
                'TransactionMethodID'=>$transactionMethod,
                'TransActorName'=>$transactionSource,
                'TransactionDescription'=>$narration,
                'createdBy'=>$this->user['UserId'],
                'createdAt'=>(new \DateTime())->format('Y-m-d H:i:s')
            ]));

            $transData = [
                'TransactionNumber'=>$transNum,
                'AccountID'=>$orgAccountNo,
                'TransactionProviderID' => '',
                'TransactionTypeID'=>$sourceType,
                'TransactionAmount'=>$amount,
                'TransactionCurrencyID'=>$currency,
                'TransactionDate'=>(new \DateTime())->format('Y-m-d H:i:s'),
                'TransactionMethodID'=>$transactionMethod,
                'TransActorName'=>$transactionSource,
                'TransactionDescription'=>$narration,
                'createdBy'=>$this->user['UserId'],
                'createdAt'=>(new \DateTime())->format('Y-m-d H:i:s')
            ];

            $type = $this->request->getPost('transaction-category');

            log_message('debug', 'Calling saveTransaction Method');
            return $this->saveTransaction($transData, $type, 'O', $orgDetails->AppID);
        } catch (\Exception $e) {
            log_message('error', 'Error: '. $e->getMessage());
            $returnData = [
                'status' => 'error',
                'message' => "Error: ". $e->getMessage()
            ];
            return json_encode($returnData);
        }
    }

    public function showTransactionReport($transactionID){
        $transaction = new TransactionModel();
    }

    /**
     * Saves a transaction
     * @param array $saveData The data to be saved
     * @param string $type The type of transaction, either 'CRD' or 'DBT'
     * @param string $entityType The type of entity performing the transaction, either 'ORG' or 'USR'
     * @param int $entityId The ID of the entity performing the transaction
     * @return string A JSON string containing the result of the transaction
     * @throws \Exception
     */
    public function saveTransaction(array $saveData, string $type, string $entityType, int $entityId) {
        log_message('debug', 'Entering saveTransaction Method');
        $db = \Config\Database::connect();
        $db->transBegin(); // ✅ Use transBegin instead of transStart()
    
        try {
            log_message('debug', 'Starting Transaction');
            $transaction = new TransactionModel($db);
    
            // Check if transaction exists
            $existingTransaction = $transaction->where('TransactionNumber', $saveData['TransactionNumber'])->first();
            log_message('debug', 'Existing Transaction: ' . json_encode($existingTransaction));
    
            if ($existingTransaction != null) {
                log_message('debug', 'Transaction already exists');
                return json_encode(['status' => 'error', 'message' => 'Transaction already exists']);
            }
    
            // Prepare Transaction Data
            $transData = [
                'AccountID' => $saveData['AccountID'],
                'TransactionNumber' => $saveData['TransactionNumber'],
                'TransactionDate' => $saveData['TransactionDate'],
                'TransactionTypeID' => $saveData['TransactionTypeID'],
                'TransactionMethodID' => $saveData['TransactionMethodID'],
                'TransactionProviderID' => $saveData['TransactionProviderID'],
                'TransactionAmount' => $saveData['TransactionAmount'],
                'TransactionDescription' => $saveData['TransactionDescription'],
                'TransactorPhone' => $saveData['TransactorPhone'] ?? null,
                'TransActorName' => $saveData['TransActorName'],
                'TransactorId' => $saveData['TransactorId'] ?? null,
                'createdBy' => $saveData['createdBy'],
                'createdAt' => $saveData['createdAt']
            ];
    
            // Save transaction
            log_message('debug', 'Saving Transaction');
            $saveResponse = json_decode(saveData('Organization Income', $transaction, $transData), true);
    
            if ($saveResponse['status'] == 'error') {
                log_message('error', 'Transaction Save Failed');
                $db->transRollback();
                return json_encode(['status' => 'error', 'message' => $saveResponse['message']]);
            }
    
            log_message('debug', 'Transaction Saved Successfully');
    
            // Update Account Balance
            $account = new AccountBalanceModel($db);
            $amount = ($type == 'CRD') ? $saveData['TransactionAmount'] : -$saveData['TransactionAmount'];
    
            // Check if account entry exists
            $accountEntry = $account->where('AccountID', $saveData['AccountID'])->first();
    
            if (!$accountEntry) {
                log_message('debug', 'Account Entry Not Found, Creating New Entry');
                $accountData = [
                    'AccountID' => $saveData['AccountID'],
                    'balance' => $amount,
                    'EntityType' => $entityType,
                    'EntityID' => $entityId,
                    'LastUpdated' => date('Y-m-d H:i:s')
                ];
    
                $saveAccount = $account->insert($accountData);
                if (!$saveAccount) {
                    log_message('error', 'Failed to create account entry: ' . json_encode($account->errors()));
                    $db->transRollback();
                    return json_encode(['status' => 'error', 'message' => 'Failed to create account entry!']);
                }
            } else {
                log_message('debug', 'Account Entry Found, Updating Balance');
    
                $updateAccountBal = $account->where('AccountID', $saveData['AccountID'])
                    ->set('balance', "balance + {$amount}", false)
                    ->set('LastUpdated', date('Y-m-d H:i:s'))
                    ->update();
    
                if (!$updateAccountBal) {
                    log_message('error', 'Failed to update account balance: ' . json_encode($account->errors()));
                    $db->transRollback();
                    return json_encode(['status' => 'error', 'message' => 'Failed to update account balance!']);
                }
            }
    
            // ✅ Explicitly commit the transaction
            $db->transCommit();
            log_message('debug', 'Transaction Completed Successfully');
    
            return json_encode([
                'status' => 'success',
                'message' => 'Transaction Recorded Successfully: ' . $saveData['TransactionNumber'],
                'redirect' => isset($saveResponse['data']['ID']) ? base_url('finance/transaction-report/' . $saveResponse['data']['ID']) : null
            ]);
    
        } catch (\Exception $e) {
            log_message('error', 'Transaction Failed: ' . $e->getMessage());
            $db->transRollback();
            return json_encode(['status' => 'error', 'message' => 'Transaction failed: ' . $e->getMessage()]);
        }
    }

    public function accountStatement(){
        try {
            $periodStart = filter_var($this->request->getPost('start-date'), FILTER_SANITIZE_STRING);
            $periodEnd = filter_var($this->request->getPost('end-date'), FILTER_SANITIZE_STRING);
            $orgType = filter_var($this->request->getPost('orgType'), FILTER_SANITIZE_STRING);
            $entityId = filter_var($this->request->getPost('entityId'), FILTER_SANITIZE_STRING);
            $entityModel = null;
            $entityData = null;
            $orgTypeName = null;
            log_message('info', 'Generating Account Statement');

            if (empty($periodStart) || empty($periodEnd)) {
                throw new \Exception('Please select a period');
            }

            switch ($orgType) {
                case 'C':
                    $entityModel = new ClientModel();
                    $entityData = $entityModel->where('ClientID', $entityId)->first();
                    $orgType = 'Client';
                    break;
                case 'S':
                    $entityModel = new SaccoModel();
                    $entityData = $entityModel->where('SaccoID', $entityId)->first();
                    $orgType = 'Sacco';
                    break;
                case 'G':
                    $entityModel = new GroupModel();
                    $entityData = $entityModel->where('GroupID', $entityId)->first();
                    $orgType = 'Group';
                    break;
                case 'O':
                    $entityModel = new OrganizationModel();
                    $entityData = $entityModel->first();
                    $orgType = 'Organization';
                    break;
                default:
                    throw new \Exception('Invalid organization type');
            }

            if ($entityData == null) {
                log_message('error', "No Data found for Selected $orgType, Period: $periodStart - $periodEnd");
                throw new \Exception("No Data found for Selected $orgType, Period: $periodStart - $periodEnd ");
            }

            $transModel = new TransactionModel();
            $organizationModel = new OrganizationModel();
            
            $data = [
                'title' => 'Account Statement',
                'page' => 'Account Statement',
                'user' => $this->user,
                'entityData' => $entityData,
                'transactions' => $transModel->getTransactions($periodStart, $periodEnd, null, $entityId),
                'periodStart' => $periodStart,
                'periodEnd' => $periodEnd,
                'orgType' => $orgType,
                'organization' => $organizationModel->first()
            ];

            return view('finance/account-statement', $data);
        } catch (\Exception $e) {
            return json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    

    public function viewTransactionReport(){
        try {
            $organization = new OrganizationModel();
            $transaction = new TransactionModel();
            $orgDetail = $organization->first();
            $transID = filter_var($this->request->uri->getSegment(3), FILTER_SANITIZE_NUMBER_INT);
            $report = $transaction->getTransaction(null, null, $transID);

            if ($report == null) {
                return view('errors/html/404');
            }

            $data = [
                'report' => $report,
                'title' => 'Transaction Report',
                'page' => 'Transaction Report',
                'user' => $this->user,
                'organization' => $orgDetail
            ];
            return view('finance/transaction-report', $data);
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return "Error: ". $e->getMessage();
        }
    }   

}