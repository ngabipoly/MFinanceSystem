<?php echo view('template\partial-header'); ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Organization Finance</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Finance</a></li>
                        <li class="breadcrumb-item active">Manage Organization</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-primary button-link" data-target="#manage-income-modal" data-toggle="modal">
                                    Record Income <i class="fas fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-primary button-link" data-link="<?php echo base_url('finance/adjustments'); ?>" data-target="#adjustments-modal" data-toggle="modal">
                                    Adjustments <i class="fas fa-exchange-alt fa-rotate-90"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-primary button-link" data-link="<?php echo base_url('finance/sacco-applications'); ?>">
                                    Loan Applications <i class="fas fa-money-bill"></i>
                                </button> 
                                <button type="button" class="btn btn-sm btn-primary button-link" data-target="#main-account-statement-modal" data-toggle="modal">
                                    Account Statement <i class="fas fa-file-invoice-dollar"></i>
                                </button>                   
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="finance-content">

                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
</div>
<!--- Manage Organization Income modal-->
<div class="modal fade" id="manage-income-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="exampleModalLabel">Record Income</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="manage-income-modal-content">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="" class="form-horizontal form-label-left db-submit" method="post">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-3 col-sm-3 ">Source Type</label>
                                    <div class="col-md-9 col-sm-9 ">
                                        <select name="income-source" id="income-source" class="form-control form-control-sm">
                                            <option value="">Select</option>
                                            <?php
                                                if($CreditTransactions){
                                                    foreach($CreditTransactions as $CreditTransaction){
                                                        echo '<option value="'.$CreditTransaction['TransactionTypeID'].'" data-trans-suffix="'.$CreditTransaction['Suffix'].'">'.$CreditTransaction['TransactionTypeName'].'</option>';
                                                    }                                                     
                                                }
                                                ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-3 col-sm-3 ">Transaction Method</label>
                                    <div class="col-md-9 col-sm-9 ">
                                        <select name="transaction-method" id="transaction-method" class="form-control form-control-sm">
                                            <option value="">Select</option>
                                            <?php
                                                if($TransactionMethods){
                                                    foreach($TransactionMethods as $TransactionMethod){
                                                        echo '<option value="'.$TransactionMethod['TransactionMethodID'].'">'.$TransactionMethod['TransactionMethodName'].'</option>';
                                                    }                                                     
                                                }
                                                ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-form-label col-md-3 col-sm-3 ">Source</label>
                                    <div class="col-md-9 col-sm-9 ">
                                        <input type="text" name="source" id="source" class="form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-3 col-sm-3 ">Amount</label>
                                    <div class="col-md-9 col-sm-9 ">
                                        <input type="number" name="amount" id="amount" class="form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-3 col-sm-3 ">Narration</label>
                                    <div class="col-md-9 col-sm-9 ">
                                        <textarea type="text" name="narration" id="narration" class="form-control form-control-sm">

                                        </textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm" id="save-income">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!--- /.Manage Organization Income modal-->
<!---Adjustments modal-->
<div class="modal fade" id="adjustments-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="exampleModalLabel">Adjustments</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="adjustments-modal-content">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="" class="form-horizontal form-label-left db-submit" method="post">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4 col-sm-3 ">Adjustment Type</label>
                                    <div class="col-md-8 col-sm-9 ">
                                        <select name="adjustment-type" id="adjustment-type" class="form-control form-control-sm">
                                            <option value="">Select</option>
                                            <option value="CRN">Credit Note</option>
                                            <option value="DBN">Debit Note</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4 col-sm-3 ">Transaction Number</label>
                                    <div class="col-md-8 col-sm-9 ">
                                        <input type="text" name="transaction-number" id="transaction-number" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4 col-sm-3 ">Amount</label>
                                    <div class="col-md-8 col-sm-9 ">
                                        <input type="number" name="amount" id="amount" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4 col-sm-3 ">Reason</label>
                                    <div class="col-md-8 col-sm-9 ">
                                        <textarea type="text" name="reason" id="reason" class="form-control form-control-sm"></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm" id="save-adjustment">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!--/. Adjustments modal-->
<!---Main account statement modal-->
<div class="modal fade" id="main-account-statement-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="exampleModalLabel">Main Account Statement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="main-account-statement-modal-content">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="" class="form-horizontal form-label-left db-submit" method="post">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-3 col-sm-3 ">Start Date</label>
                                    <div class="col-md-9 col-sm-9 ">
                                        <input type="date" name="start-date" id="start-date" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-3 col-sm-3 ">End Date</label>
                                    <div class="col-md-9 col-sm-9 ">
                                        <input type="date" name="end-date" id="end-date" class="form-control form-control-sm">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm" id="save-main-account-statement">Load Statement</button>
            </div>
        </div>
    </div>
</div>
<!--/. Main account statement modal-->
<?php echo view('template\partial-footer'); ?>