<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\AccountModel;
use App\Models\TransactionModel;
use App\Models\TransactionTypeModel;
use App\Models\TransactionMethodModel;
use App\Models\TransactionProviderModel;
use App\Models\TransactionStatusModel;
use App\Models\TransactionMethodProviderModel;
use App\Models\LoansProductsModel;
use App\Models\ClientModel as CustomerModel;
use App\Models\LoanModel;

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
    

}