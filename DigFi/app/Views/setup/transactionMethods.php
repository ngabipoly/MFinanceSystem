<?php echo view('template\partial-header'); ?>
  <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <?php 
            $users=json_decode(json_encode($user));
        ?>

        <section class="content">
            <div class="container-fluid">
                <div class="row mt-2">
                    <div class="col-sm-12">
                        <div class="card card-info">
                            <div class="card-header" >
                               <strong>Transaction Methods Setup</strong> 
                            </div>
                            <div class="card-body">
                            <a href="#" id="new-method" data-toggle="modal" data-target="#transaction-method-modal" class="btn btn-xs btn-success">Add New <i class="fas fa-plus-circle"></i></a>
                            
                                <div class="table-responsive mt-2">
                                    <table class="table data-table-simple table-bordered table-striped table-hover table-sm" width="100%">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th><strong><small>Transaction Method</small></strong></th>
                                                <th><strong><small>Description</small></strong></th>
                                                <th><strong><small>Creation Date</small></strong></th>
                                                <th><strong><small>Created By</small></strong></th> 
                                                <th><strong><small>Action</small></strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!$transactionMethods){
                                                # code...
                                            }else{
                                            foreach ($transactionMethods as $method) { ?>
                                                <tr>
                                                    <td>
                                                        <small>
                                                            <?php echo $method->TransactionMethodName; ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <small>
                                                            <?php echo $method->MethodDescription; ?>
                                                            </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo $method->CreatedAt; ?> </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo $method->createdByName; ?> </small>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            if ($method->ProviderListApplicable==1) {?>
                                                                <a href="#" data-toggle="modal"  data-target="#payment-provider-modal" class="btn btn-xs btn-success provider-list" data-methodid = "<?php echo $method->TransactionMethodID; ?>" data-methodname="<?php echo $method->TransactionMethodName; ?>" ><i class="fas fa-list"></i></a>
                                                        <?php
                                                            }  ?>


                                                        <a href="#" data-toggle="modal" data-target="#transaction-method-modal" class="btn btn-xs btn-warning edit-trans-method"  data-editid="<?php echo $method->TransactionMethodID; ?>"  data-providerlist ="<?php echo $method->ProviderListApplicable; ?>" data-methodname="<?php echo $method->TransactionMethodName; ?>"  data-desc ="<?php echo $method->MethodDescription;?>"><i class="fas fa-edit"></i></a>
                                                        <?php 
                                                            if ($method->Deleted==0) {?>                                                               
                                                            <a href="#" data-toggle="modal" data-target="#delete-transaction-method-modal" class="btn btn-xs btn-danger delete-trans-method" data-methodname="<?php echo $method->TransactionMethodName; ?>" data-deleteid="<?php echo $method->TransactionMethodID; ?>"><i class="fas fa-trash"></i></a>                                                         
                                                             <?php  } ?>
                                                        

                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                        } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>                                           
    </div>

    <!-- Transaction Method Modal -->
    <div class="modal fade" id="transaction-method-modal" tabindex="-1" role="dialog" aria-labelledby="transaction-method-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="transaction-method-modalLabel"><span id="spn-action"></span> Transaction Method <span id="spn-trans-method-name"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="transaction-method-form" method="post" class="form-horizontal db-submit" data-initmsg="Saving Transaction method Changes..."  action="<?php echo base_url('setup/transaction-methods/save'); ?>">
                    <div class="modal-body">  
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="select-provider-listing">Provider Listing Applicable</label>
                                    <select name="select-provider-listing" class="form-control form-control-sm " id="select-provider-listing">
                                        <option value="">Select</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                    
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="transaction-method-name">Transaction Method Name</label>
                                    <input type="text" class="form-control form-control-sm" id="transaction-method-name" name="transaction-method-name">
                                </div>
                            </div>
                        </div>                  
                            <div class="row">  
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="transaction-method-description">Description</label>
                                        <textarea class="form-control form-control-sm" id="transaction-method-description" name="transaction-method-description"></textarea>
                                    </div>
                                </div>  
                            </div>                      
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="method-id" name="method-id">
                            <input type="hidden" name="execMode" id="execMode" value="create">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="save-product">Save Type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Transaction Type Delete Modal -->
    <div class="modal fade" id="delete-transaction-method-modal" tabindex="-1" role="dialog" aria-labelledby="delete-transaction-method-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">                    
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form id="id-type-delete-form" class="form-horizontal db-submit" action="<?php echo base_url('setup/transaction-methods/delete'); ?>" method="post" data-initmsg="Deleting Transaction method..." >
                        <div class="form-group">
                            <input type="hidden" name="del-transaction-method-id" id="del-transaction-method-id">
                            <div class="card-body">
                                <div class="callout callout-danger">
                                    <h5>Warning!</h5>
                                    <p>Are you sure you want to delete Transaction Method <span id="spn-del-transaction-method-name"></span>?</p>
                                </div>
                                    <button type="button" class="btn btn-sm btn-secondary float-right" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-sm btn-danger float-right mr-2" id="delete-nature">Delete</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Type Retire Modal -->
    <div class="modal fade" id="product-retire-modal" tabindex="-1" role="dialog" aria-labelledby="product-retire-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form id="product-retire-form" class="form-horizontal db-submit" action="<?php echo base_url('setup/loans/retire'); ?>" data-initmsg="Saving Product Changes..." method="post">
                            <input type="hidden" name="retire-product-id" id="retire-product-id">
                            <div class="card-body">
                                <div class="callout callout-danger">
                                    <h5>Warning!</h5>
                                        <p>Are you sure you want to retire Loan Product <span id="spn-retire-product-name"></span>?</p>
                                        <button type="button" class="btn btn-secondary float-right mt-4 mr-2" data-dismiss="modal">Close</button> 
                                        <button type="submit" class="btn btn-danger float-right mt-4 mr-2" id="retire-product">Retire</button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--- Payment Provider List -->
    <div class="modal fade" id="payment-provider-modal" tabindex="-1" role="dialog" aria-labelledby="payment-provider-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info p-1">
                    <h5 class="modal-title" id="payment-provider-modalLabel"><span id="spn-provider-method-name"></span> Provider List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-2">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <form class="form-horizontal db-submit" action="<?php echo base_url('setup/transaction-method-provider/save'); ?>" method="post" >
                                <div class="row ml-2">
                                    <input type="hidden" name="provider-method-id" id="provider-method-id">
                                    <input type="hidden" name="pExecMode" id="pExecMode" value="create">
                                    <input type="hidden" name="provider-id" id="provider-id">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-prepend">
                                            <label for="provider-name" class="input-group-text">Provider Name: </label>
                                        </span>
                                        <input type="text" class="form-control form-control-sm" name="provider-name" id="payment-provider-name">
                                        <span class="input-group-append">
                                            <button type="submit" class="btn btn-success btn-flat btn-sm">Save Provider</button>
                                        </span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <div class="col-md-4">
                                <div class="input-group input-group-sm" id="status-filter">
                                    <span class="input-group-prepend">
                                        <label for="filter-status" class="input-group-text">Filter By Status: </label> 
                                    </span>                                   
                                    <select id="filter-status" class="form-control form-control-sm">
                                        <option value="">All Statuses</option>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                        <option value="Deleted">Deleted</option>
                                    </select>
                                </div>
                            </div>
                    </div>
                    <hr width="95%"/>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table data-table-simple table-bordered table-striped table-hover table-sm" id="method-provider-table" width="100%">
                                <thead class="thead-dark">
                                    <tr>
                                        <th><small>Provider Name</small></th>
                                        <th><small>Provider Status</small></th>
                                        <th><small>Created By</small></th>
                                        <th><small>Created On</small></th>
                                        <th><small>Action</small></th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php echo view('template\partial-footer'); ?>