<?php
namespace App\Controllers;
use App\Models\LoansProductsModel;
use App\Models\AccountTypesModel;
use App\Models\AccountNaturesModel;
use App\Models\IdentificationTypesModel;
use App\Models\TransactionTypesModel;
use App\Models\TransactionMethodsModel;
use App\Models\TransactionMethodProviderModel as ProviderModel;
use CodeIgniter\Config\Services;


helper('App\Helpers\Custom');

class Settings extends BaseController
{   
    protected $user;
    protected $accountTypes;
    protected $logger;
    public function __construct()
    { 
        if(isset($this->session)){
            return redirect()->to('/');
        }

        $this->user = session()->get('userData');
        $this->logger = Services::logger();
        $this->accountTypes= new AccountTypesModel();
    }

    public function loansIndex(){
        try {
            $loanProducts = new LoansProductsModel();
            $data = [
                'user' => $this->user,
                'loans' => $loanProducts->findAll()
            ];
            return view('setup/loanProducts', $data);
        } catch (\Exception $e) {
            //throw $th;
            return "Error: ". $e->getMessage();
        }
    }


    public function saveLoanProduct(){
        try { 
                //validation
                $rules = [
                    'product-name' => 'required',
                    'product-description' => 'required',
                    'interest-rate' => 'required|numeric',
                    'interest-rate-type' => 'required',//length 1
                    'calculation-style' => 'required',
                    'calculation-method' => 'required',
                    'minimum-term' => 'required|numeric', 
                    'maximum-term' => 'required|numeric', 
                    'product-min-amount' => 'required|numeric',   
                    'product-max-amount' => 'required|numeric',  
                    'product-status' => 'required'    
                ] ;

                if (!$this->validate($rules)) {
                    // Validation failed
                    $messages = $this->validator->getErrors();
                    $errorsMessages = nl2br(esc(implode("\n", $messages)));
                    return json_encode([
                        'status' => 'error',
                        'message' => '<strong>Validation failed!</strong>' . "<br/>" . $errorsMessages,
                        'data' => [$this->request->getVar()]
                    ]);
                }

                //save
                $loanProducts = new LoansProductsModel();
                $execMode = $this->request->getPost('execMode');
                $data = [
                    'ProductName' => $this->request->getPost('product-name'),
                    'Description' => $this->request->getPost('product-description'),
                    'InterestRate' => $this->request->getPost('interest-rate'),
                    'InterestRateType' => $this->request->getPost('interest-rate-type'),
                    'MinAmount' => $this->request->getPost('product-min-amount'),
                    'MaxAmount' => $this->request->getPost('product-max-amount'),
                    'MaxTermMonths' => $this->request->getPost('maximum-term'),
                    'MinTermMonths' => $this->request->getPost('minimum-term'),
                    'ProductStatus' => $this->request->getPost('product-status'),
                    'CalculationFrequency' => $this->request->getPost('calculation-style'),
                    'CalculationMethod' => $this->request->getPost('calculation-method'),
                    'CreatedAt' => date('Y-m-d H:i:s'),
                    'CreatedBy' => $this->user['UserId']
                ];

                //if edit mode  
                if($execMode == 'edit'){
                    $data['UpdatedAt'] = date('Y-m-d H:i:s');
                    $data['UpdatedBy'] = $this->user['UserId'];
                    $data['ProductId'] = $this->request->getPost('product-id');
                }

                return saveData('Save Loan Product', $loanProducts, $data);

        } catch (\Exception $e) {
            //throw $th;
            return json_encode([
                'status' => 'error',
                'message' => 'Error saving loan product',
                'data' => ["Error"=>$e->getMessage()]
            ]);
        }
    }   

    public function deleteLoanProduct(){
        try { 
            //validate
            $rules = [
                'del-product-id' => 'required'
            ];

            if (!$this->validate($rules)) {
                // Validation failed
                $messages = $this->validator->getErrors();
                $errorsMessages = nl2br(esc(implode("\n", $messages)));
                return json_encode([
                    'status' => 'error',
                    'message' => 'Validation failed' . "<br/>" . $errorsMessages,
                    'data' => [$this->request->getVar()]
                ]);
            }

            $loanProducts = new LoansProductsModel();
            $data['ProductId'] = $this->request->getPost('del-product-id');
            $data['DeletedBy'] = $this->user['UserId'];
            $data['ProductStatus'] = 'D';
            $actionPerformed = "Loan Product Deletion";           
            return saveData($actionPerformed, $loanProducts, $data);
            
        } catch (\Exception $e) {
            return json_encode([
                'status' => 'error',
                'message' => 'Error deleting loan product',
                'data' => ["Error"=>$e->getMessage()]
            ]);
        }
    }


    public function retireLoanProduct(){
        try {
            $rules = [
                'retire-product-id' => 'required'
            ];

            if (!$this->validate($rules)) {
                // Validation failed
                $messages = $this->validator->getErrors();
                $errorsMessages = nl2br(esc(implode("\n", $messages)));
                return json_encode([
                    'status' => 'error',
                    'message' => '<strong>Validation Failed!</strong>' . "<br/>" . $errorsMessages,
                    'data' => [$this->request->getVar()]
                ]);
            }

            $loanProducts = new LoansProductsModel();
            $data['ProductId'] = $this->request->getPost('retire-product-id');
            $data['RetiredBy'] = $this->user['UserId'];
            $data['RetiredAt'] = date('Y-m-d H:i:s');
            $data['ProductStatus'] = 'R';

            return saveData('Retire Loan Product', $loanProducts, $data);

        } catch (\Exception $e) {
            //throw $th;
            return json_encode([
                'status' => 'error',
                'message' => 'Error retiring loan product',
                'data' => ["Error"=>$e->getMessage()]
            ]);
        }
    }

    public function accountNaturesIndex(){
        try {
            $accountNature= new AccountNaturesModel();
            $data = [
                'user' => $this->user,
                'natures' => $accountNature->findAll()
            ];
            return view('setup/accountNature', $data);
        } catch (\Exception $e) {
            //throw $th;
            return "Error: ". $e->getMessage();
        }        
    }

    public function saveAccountNature(){
        $action = "Creation of Account Nature";
        try {
            $rules = [
                'nature-name' => 'required',
                'nature-description' => 'required',
                'min-holders' => 'required',
                'max-holders' => 'required'   
            ] ;

            if (!$this->validate($rules)) {
                // Validation failed
                $messages = $this->validator->getErrors();
                $errorsMessages = nl2br(esc(implode("\n", $messages)));
                return json_encode([
                    'status' => 'error',
                    'message' => 'Missing Data!' . "<br/>" . $errorsMessages,
                    'data' => [$this->request->getVar()]
                ]);
            }

            $execMode = $this->request->getPost('execMode');
            $accountNature= new AccountNaturesModel();

            $data = [
                'AccountNatureName' => $this->request->getPost('nature-name'),
                'Description' => $this->request->getPost('nature-description'),
                'MinHolders' => $this->request->getPost('min-holders'),
                'maxHolders' => $this->request->getPost('max-holders')
            ];

            if ($execMode=='edit') {
                $action =  "Updating Account Nature";
                $data['AccountNatureID']= $this->request->getPost('nature-id');
                $data['lastUpdatedBy'] = $this->user['UserId'];
            }
            
            return saveData($action, $accountNature, $data);
        } catch (\Exception $e) {
            return json_encode([
                'status' => 'error',
                'message' => "Error $action",
                'data' => ["Error"=>$e->getMessage()]
            ]);
        }
    }

    public function deleteAccountNature(){
        try { 
            //validate
            $rules = [
                'del-nature-id' => 'required'
            ];

            if (!$this->validate($rules)) {
                // Validation failed
                $messages = $this->validator->getErrors();
                $errorsMessages = nl2br(esc(implode("\n", $messages)));
                return json_encode([
                    'status' => 'error',
                    'message' => 'Validation failed' . "<br/>" . $errorsMessages,
                    'data' => [$this->request->getVar()]
                ]);
            }

            $accountNature = new AccountNaturesModel();
            $data['AccountNatureID'] = $this->request->getPost('del-nature-id');
            $data['deletedBy'] = $this->user['UserId'];
            $data['deletedAt'] = date('Y-m-d H:i:s');
            $actionPerformed = "Account Nature Deletion";           
            return saveData($actionPerformed, $accountNature, $data);
            
        } catch (\Exception $e) {
            return json_encode([
                'status' => 'error',
                'message' => 'Error deleting account nature',
                'data' => ["Error"=>$e->getMessage()]
            ]);
        }        
    }

    public function accountTypesIndex(){
        try {
            $accountTypes= new AccountTypesModel();
            $accountNature = new AccountNaturesModel();
            $data = [
                'user' => $this->user,
                'accountTypes' => $accountTypes->accountProductList(),
                'natures' => $accountNature->findAll()
            ];
            return view('setup/accountTypes', $data);
        } catch (\Exception $e) {
            //throw $th;
            return "Error: ". $e->getMessage()." on line ". $e->getLine();
        }          
    }
    public function saveAccountType(){
        $action = "Creating Account Product";
        try {
            $rules = [
                'product-nature' => 'required',
                'product-name' => 'required',
                'product-description' => 'required',
                'opening-balance' => 'required|numeric',
                'minimum-balance' => 'required|numeric'   
            ] ;

            if (!$this->validate($rules)) {
                // Validation failed
                $messages = $this->validator->getErrors();
                $errorsMessages = nl2br(esc(implode("\n", $messages)));
                return json_encode([
                    'status' => 'error',
                    'message' => 'Product Data incomplete!' . "<br/>" . $errorsMessages,
                    'data' => [$this->request->getVar()]
                ]);
            }

            $execMode = $this->request->getPost('execMode');
            $accountTypes= new AccountTypesModel();

            $data = [
                'AccountNature' => $this->request->getPost('product-nature'),
                'AccountTypeName' => $this->request->getPost('product-name'),
                'Description' => $this->request->getPost('product-description'),
                'OpeningBal' => $this->request->getPost('opening-balance'),
                'MinBal' => $this->request->getPost('minimum-balance')
            ];

            if ($execMode=='edit') {
                $action =  "Updating Product Details";
                $data['AccountTypeID']= $this->request->getPost('ac-product-id');
                $data['lastUpdatedBy'] = $this->user['UserId'];
            }
            
            return saveData($action, $accountTypes, $data);
        } catch (\Exception $e) {
            return json_encode([
                'status' => 'error',
                'message' => "Error $action",
                'data' => ["Error"=>$e->getMessage()]
            ]);
        }

    }


    public function deleteAccountType() : string {
        try {
            $rules  = ['del-account-product-id'=>'required'];

            if (!$this->validate($rules)) {
                // Validation failed
                $messages = $this->validator->getErrors();
                $errorsMessages = nl2br(esc(implode("\n", $messages)));
                return json_encode([
                    'status' => 'error',
                    'message' => 'Product Data incomplete!' . "<br/>" . $errorsMessages,
                    'data' => [$this->request->getVar()]
                ]);
            }

            $data = [
                'AccountTypeID'=> $this->request->getPost('del-account-product-id'),
                'deletedAt' => date("Y-m-d H:i:s"),
                'deletedBy' => $this->user['UserId']
            ];
            return saveData('Account Product Deletion', $this->accountTypes, $data);       
            
        } catch (\Exception $e) {
            return json_encode([
                'status' => 'error',
                'message' => "Error $action",
                'data' => ["Error"=>$e->getMessage()]
            ]);
        }
    }

    //identification types setup
    public function identificationTypesIndex(): string{
        $this->logger->info('Fetching all identification types');
        $identificationTypes = new IdentificationTypesModel();
        $this->logger->info('Fetching identification types from database');
        $data = [
            'user' => $this->user,
            'identificationTypes' => $identificationTypes->idTypeList()
        ];
        $this->logger->info('Returning identification types index view with data');
        return view('setup/identificationTypes', $data);
    }


    public function saveIdentificationType(){
        $action = "Creating Identification Type";
        try {
            $this->logger->info("Saving Identification Type: " . json_encode($this->request->getVar()));

            $rules = [
                'id-type-name' => 'required',
                'number-pattern' => 'required',
                'id-number-length' => 'required|numeric',
                'id-type-category' => 'required',
                'id-description' => 'required'
            ] ;

            $data = [
                'IDTypeName' => $this->request->getPost('id-type-name'),
                'IdPattern' => $this->request->getPost('number-pattern'),
                'IdNumberLength' => $this->request->getPost('id-number-length'),
                'IDTypeDescription' => $this->request->getPost('id-description'),
                'TypeCategory' => $this->request->getPost('id-type-category'),
            ];

            $execMode = $this->request->getPost('execMode');
            $identificationTypes= new IdentificationTypesModel();


            if ($execMode=='create') {
                $data['CreatedBy'] = $this->user['UserId'];
            }

            if ($execMode=='edit') {
                $rules['type-id'] = 'required';
                $action =  "Updating Identification Type Details";
                $data['IDTypeID']= $this->request->getPost('type-id');
                $data['lastUpdatedBy'] = $this->user['UserId'];
            }

            if (!$this->validate($rules)) {
                // Validation failed
                $messages = $this->validator->getErrors();
                $errorsMessages = nl2br(esc(implode("\n", $messages)));
                $this->logger->error("Validation failed: " . $errorsMessages);
                return json_encode([
                    'status' => 'error',
                    'message' => 'ID Type Data incomplete!' . "<br/>" . $errorsMessages,
                    'data' => [$this->request->getVar()]
                ]);
            }

            $this->logger->info("Saving Identification Type: " . json_encode($data));
            return saveData($action, $identificationTypes, $data);
        } catch (\Exception $e) {
            $this->logger->error("Error $action: " . $e->getMessage());
            return json_encode([
                'status' => 'error',
                'message' => "Error $action",
                'data' => ["Error"=>$e->getMessage()]
            ]);
        }
    }


    public function deleteIdentificationType() : string {
        try {
            $rules  = ['del-id-type-id'=>'required'];

            $this->logger->info("Deleting Identification Type: " . json_encode($this->request->getVar()));
            if (!$this->validate($rules)) {
                // Validation failed
                $messages = $this->validator->getErrors();
                $errorsMessages = nl2br(esc(implode("\n", $messages)));
                $this->logger->error("Validation failed: " . $errorsMessages);
                return json_encode([
                    'status' => 'error',
                    'message' => 'ID Type Data incomplete!' . "<br/>" . $errorsMessages,
                    'data' => [$this->request->getVar()]
                ]);
            }
            $idType = $this->request->getPost('del-id-type-id');
            $identificationTypes= new IdentificationTypesModel();
            $data = [
                'IDTypeID' => $idType,
                'Deleted' => 1,
                'DeletedAt' => date('Y-m-d H:i:s'),
                'DeletedBy' => $this->user['UserId']    
            ];
            $this->logger->info("Deleting Identification Type: " . json_encode($data));
            return saveData('Identification Type Deletion', $identificationTypes, $data);
        } catch (\Exception $e) {
            $this->logger->error("Error Deleting Identification Type: " . $e->getMessage());
            return json_encode([
                'status' => 'error',
                'message' => "Error Deleting Identification Type",
                'data' => ["Error"=>$e->getMessage()]
            ]);
        }
    }

    public function transactionTypesIndex(): string
    {
        $trans = new TransactionTypesModel();
        $data = [
            'user' => $this->user,
            'page'=>"Transaction Types",
            'transactionTypes' => $trans->transactionTypeList()
        ];
        return view('setup/transactionTypes', $data);
    }

    public function saveTransactionType(): string
    {
        try {
            $this->logger->info("Saving Transaction Type: " . json_encode($this->request->getVar()));
            $data = [
                'TransactionTypeName' => $this->request->getVar('transaction-type-name'),
                'TypeDescription' => $this->request->getVar('transaction-description'),
                'TypeCategory' => $this->request->getVar('transaction-type-category'),
                'Suffix' => $this->request->getVar('number-suffix'),
                'CreatedBy' => $this->user['UserId'],
            ];

            $rules = [
                'transaction-type-name' => 'required',
                'transaction-description' => 'required',
                'transaction-type-category' => 'required',
                'number-suffix' => 'required',
            ];

            $execMode = $this->request->getVar('execMode');

            if ($execMode=='create') {
                $data['CreatedBy'] = $this->user['UserId'];
            }

            if ($execMode=='edit') {
                $rules['type-id'] = 'required';
                $action =  "Updating Transaction Type Details";
                $data['TransactionTypeID']= $this->request->getPost('type-id');
                $data['lastUpdatedBy'] = $this->user['UserId'];
            }

            if (!$this->validate($rules)) {
                // Validation failed
                $messages = $this->validator->getErrors();
                $errorsMessages = nl2br(esc(implode("\n", $messages)));
                $this->logger->error("Validation failed: " . $errorsMessages);
                return json_encode([
                    'status' => 'error',
                    'message' => 'Transaction Type Data incomplete!' . "<br/>" . $errorsMessages,
                    'data' => [$this->request->getVar()]
                ]);
            }

            $transactionTypes = new TransactionTypesModel();

            return saveData('Transaction Type Creation', $transactionTypes, $data);         
        } catch (\Exception $e) {
            $this->logger->error("Error Saving Transaction Type: " . $e->getMessage());
            return json_encode([
                'status' => 'error',
                'message' => "Error Saving Transaction Type",
                'data' => ["Error"=>$e->getMessage()]
            ]);
        }

    }


    public function deleteTransactionType() : string {
        try {
            $rules  = ['del-transaction-type-id'=>'required'];            
            $this->logger->info("Deleting Transaction Type: " . json_encode($this->request->getVar()));
            if (!$this->validate($rules)) {
                // Validation failed
                $messages = $this->validator->getErrors();
                $errorsMessages = nl2br(esc(implode("\n", $messages)));
                $this->logger->error("Validation failed: " . $errorsMessages);
                return json_encode([
                    'status' => 'error',
                    'message' => 'Transaction Type Data incomplete!' . "<br/>" . $errorsMessages,
                    'data' => [$this->request->getVar()]
                ]);
            }
            $transType = $this->request->getPost('del-transaction-type-id');
            $transactionTypes= new TransactionTypesModel();
            $data = [
                'TransactionTypeID' => $transType,
                'Deleted' => 1,
                'DeletedAt' => date('Y-m-d H:i:s'),
                'DeletedBy' => $this->user['UserId']    
            ];
            $this->logger->info("Deleting Transaction Type: " . json_encode($data));
            return saveData('Transaction Type Deletion', $transactionTypes, $data);
        } catch (\Exception $e) {
            $this->logger->error("Error Deleting Transaction Type: " . $e->getMessage());
            return json_encode([
                'status' => 'error',
                'message' => "Error Deleting Transaction Type",
                'data' => ["Error"=>$e->getMessage()]
            ]);
        }
    }

    //Transaction Methods Setup

    public function  transactionMethodsIndex(): string
    {
        $trans = new TransactionMethodsModel();
        $data = [
            'user' => $this->user,
            'page'=>"P Methods",
            'transactionMethods' => $trans->transactionMethodsList()
        ];
        return view('setup/transactionMethods', $data);
    }

    public function saveTransactionMethod(): string
    {
        $action = "Creating Payment Method";
        try {
            $this->logger->info("Saving Payment Method: " . json_encode($this->request->getVar()));
            $data = [
                'TransactionMethodName' => $this->request->getPost('transaction-method-name'),
                'MethodDescription' => $this->request->getPost('transaction-method-description'),
                'ProviderListApplicable' => $this->request->getPost('select-provider-listing'),
                'CreatedBy' => $this->user['UserId'],
            ];
            $rules = [
                'transaction-method-name' => 'required',
                'transaction-method-description' => 'required',
                'select-provider-listing' => 'required'
            ];

            $execMode = $this->request->getVar('execMode');
            if ($execMode=='create') {
                $data['CreatedAt'] = date('Y-m-d H:i:s');
                $data['CreatedBy'] = $this->user['UserId'];
            }

            if ($execMode=='edit') {
                $rules['method-id'] = 'required';
                $action =  "Updating Payment Method Details";
                $data['TransactionMethodID']= $this->request->getPost('method-id');
                $data['UpdatedAt'] = date('Y-m-d H:i:s');
                $data['lastUpdatedBy'] = $this->user['UserId'];
            }

            if (!$this->validate($rules)) {
                // Validation failed
                $messages = $this->validator->getErrors();
                $errorsMessages = nl2br(esc(implode("\n", $messages)));
                $this->logger->error("Validation failed: " . $errorsMessages);
                return json_encode([
                    'status' => 'error',
                    'message' => 'Method Data incomplete!' . "<br/>" . $errorsMessages,
                    'data' => [$this->request->getVar()]
                ]);
            }

            $transactionMethods= new TransactionMethodsModel();
            return saveData($action, $transactionMethods, $data);
        } catch (\Exception $e) {
            $this->logger->error("Error Saving Transaction Method: " . $e->getMessage());
            return json_encode([
                'status' => 'error',
                'message' => "Error Saving Transaction Method",
                'data' => ["Error"=>$e->getMessage()]
            ]);
        }
    }

    public function deleteTransactionMethod(): string{
        try {
            $rules  = ['del-transaction-method-id'=>'required'];            
            $this->logger->info("Deleting Transaction Method: " . json_encode($this->request->getVar()));
            if (!$this->validate($rules)) {
                // Validation failed
                $messages = $this->validator->getErrors();
                $errorsMessages = nl2br(esc(implode("\n", $messages)));
                $this->logger->error("Validation failed: " . $errorsMessages);
                return json_encode([
                    'status' => 'error',
                    'message' => 'Method Data incomplete!' . "<br/>" . $errorsMessages,
                    'data' => [$this->request->getVar()]
                ]);
            }
            $transMethod = $this->request->getPost('del-transaction-method-id');
            $transactionMethods= new TransactionMethodsModel();
            $data = [
                'TransactionMethodID' => $transMethod,
                'Deleted' => 1,
                'DeletedAt' => date('Y-m-d H:i:s'),
                'DeletedBy' => $this->user['UserId']
            ];
            return saveData('Transaction Method Deletion', $transactionMethods, $data);
        } catch (\Exception $e) {
            $this->logger->error("Error Deleting Transaction Method: " . $e->getMessage());
            return json_encode([
                'status' => 'error',
                'message' => "Error Deleting Transaction Method",
                'data' => ["Error"=>$e->getMessage()]
            ]);
        }
    }

    public function getProviderList(): string{
        try {
            $this->logger->info("Getting Provider List: " . json_encode($this->request->getVar()));
            $provider = new ProviderModel();
            $methodId = $this->request->getPost('methodId');
            $providers = $provider->providerList($methodId);
            $this->logger->info("Provider List Retrieved: " . json_encode($providers));
            return json_encode([
                'status' => 'success',
                'message' => "Provider List Retrieved!",
                'data' => $providers
            ]);
        } catch (\Exception $e) {
            $this->logger->error("Error Getting Provider List: " . $e->getMessage());
            return json_encode([
                'status' => 'error',
                'message' => "Error Getting Provider List",
                'data' => ["Error"=>$e->getMessage()]
            ]);
        }
    }

    public function saveProvider(): string{
        $action = "Saving Provider Details";
        try {
            $this->logger->info("Saving Provider: " . json_encode($this->request->getVar()));
            $provider = new ProviderModel();
            $data = [
                'ProviderName' => $this->request->getPost('provider-name'),
                'MethodID' => $this->request->getPost('provider-method-id'),
            ];

            $rules = [
                'provider-name' => 'required',
                'provider-method-id' => 'required'
            ];

            $execMode = $this->request->getVar('pExecMode');

            if ($execMode=='create') {
                $data['CreatedAt'] = date('Y-m-d H:i:s');
                $data['CreatedBy'] = $this->user['UserId'];
            }

            if ($execMode=='edit') {
                $rules['provider-id'] = 'required';
                $action =  "Updating Provider Details";
                $data['ProviderID'] = $this->request->getPost('provider-id');
                $data['UpdatedAt'] = date('Y-m-d H:i:s');
                $data['lastUpdatedBy'] = $this->user['UserId'];
            }

            if (!$this->validate($rules)) {
                // Validation failed
                $messages = $this->validator->getErrors();
                $errorsMessages = nl2br(esc(implode("\n", $messages)));
                $this->logger->error("Validation failed: " . $errorsMessages);
                return json_encode([
                    'status' => 'error',
                    'message' => 'Provider Data incomplete!' . "<br/>" . $errorsMessages,
                    'data' => [$this->request->getVar()]
                ]);
            }
            $this->logger->info("Saving Provider Data: " . json_encode($data));
            return saveData($action, $provider, $data);
        } catch (\Exception $e) {
            $this->logger->error("Error Saving Provider: " . $e->getMessage());
            return json_encode([
                'status' => 'error',
                'message' => "Error Saving Provider",
                'data' => ["Error"=>$e->getMessage()]
            ]);
        }
    }

    public function changeProviderStatus(): string{
        try {
            $provider = new ProviderModel();
            $data = [
                'ProviderID' => $this->request->getPost('provider-id'),
                'ProviderStatus' => $this->request->getPost('provider-status'),
                'UpdatedAt' => date('Y-m-d H:i:s'),
                'lastUpdatedBy' => $this->user['UserId']
            ];

            $status = $this->request->getPost('provider-status');

            if ($status == 'Deleted') {
                $data['DeletedAt'] = date('Y-m-d H:i:s');
                $data['DeletedBy'] = $this->user['UserId'];
            }  else{
                $data['DeletedAt'] = NULL;
                $data['DeletedBy'] = NULL;
            }

            $rules = [
                'provider-id' => 'required',
                'provider-status' => 'required'
            ];

            if (!$this->validate($rules)) {
                // Validation failed
                $messages = $this->validator->getErrors();
                $errorsMessages = nl2br(esc(implode("\n", $messages)));
                $this->logger->error("Validation failed: " . $errorsMessages);
                return json_encode([
                    'status' => 'error',
                    'message' => 'Provider Data incomplete!' . "<br/>" . $errorsMessages,
                    'data' => [$this->request->getVar()]
                ]);
            }

            return saveData("Provider Status Change to $status", $provider, $data);
        } catch (\Exception $e) {
            $this->logger->error("Error Changing Provider Status: " . $e->getMessage());
            return json_encode([
                'status' => 'error',
                'message' => "Error Changing Provider Status",
                'data' => ["Error"=>$e->getMessage()]
            ]);
        }
    }
}
