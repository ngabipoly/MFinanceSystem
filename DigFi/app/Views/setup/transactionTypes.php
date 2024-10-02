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
                               <strong>Transaction Types Listing</strong> 
                            </div>
                            <div class="card-body">
                            <a href="#" id="new-trans-type" data-toggle="modal" data-target="#transaction-type-modal" class="btn btn-xs btn-success">Add New <i class="fas fa-plus-circle"></i></a>
                            
                                <div class="table-responsive mt-2">
                                    <table class="table data-table-simple table-bordered table-striped table-hover table-sm" width="100%">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th><strong><small>Transaction Type</small></strong></th>
                                                <th><strong><small>Type Category</small></strong></th>
                                                <th><strong><small>Suffix</small></strong></th>
                                                <th><strong><small>Creation Date</small></strong></th>
                                                <th><strong><small>Created By</small></strong></th> 
                                                <th><strong><small>Action</small></strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!$transactionTypes){
                                                # code...
                                            }else{
                                            foreach ($transactionTypes as $transactionType) { ?>
                                                <tr>
                                                    <td>
                                                        <small>
                                                            <?php echo $transactionType->TransactionTypeName; ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <small>
                                                            <?php echo $transactionType->TypeCategory; ?>
                                                            </small>
                                                    </td>
                                                    <td>
                                                        <small> 
                                                            <?php echo $transactionType->Suffix; ?> 
                                                        </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo $transactionType->CreatedAt; ?> </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo $transactionType->createdByName; ?> </small>
                                                    </td>
                                                    <td>
                                                        <a href="#" data-toggle="modal" data-target="#transaction-type-modal" class="btn btn-xs btn-warning edit-trans-type"  data-editid="<?php echo $transactionType->TransactionTypeID; ?>"  data-category ="<?php echo $transactionType->TypeCategory; ?>" data-typename="<?php echo $transactionType->TransactionTypeName; ?>" data-suffix="<?php echo $transactionType->Suffix;?>"  data-desc ="<?php echo $transactionType->TypeDescription;?>"><i class="fas fa-edit"></i></a>
                                                        
                                                        <a href="#" data-toggle="modal" data-target="#delete-transaction-type-modal" class="btn btn-xs btn-danger delete-trans-type" data-typename="<?php echo $transactionType->TransactionTypeName; ?>" data-deleteid="<?php echo $transactionType->TransactionTypeID; ?>"><i class="fas fa-trash"></i></a>
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

    <!-- Transaction Type Modal -->
    <div class="modal fade" id="transaction-type-modal" tabindex="-1" role="dialog" aria-labelledby="transaction-type-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="transaction-type-modalLabel"><span id="spn-action"></span> Transaction Type <span id="spn-trans-type-name"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="transaction-type-form" method="post" class="form-horizontal db-submit" data-initmsg="Saving Transaction Type Changes..."  action="<?php echo base_url('setup/transaction-types/save'); ?>">
                    <div class="modal-body">  
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="transaction-type-category">Transaction Type Category</label>
                                    <select name="transaction-type-category" class="form-control form-control-sm " id="transaction-type-category">
                                        <option value="">Select</option>
                                        <option value="CRD">Credit</option>
                                        <option value="DBT">Debit</option>
                                    </select>
                                    
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="id-type-name">Transaction Type Name</label>
                                    <input type="text" class="form-control form-control-sm" id="transaction-type-name" name="transaction-type-name">
                                </div>
                            </div>
                        </div>                  
                            <div class="row">                          
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="number-suffix">Transaction Number Suffix</label>
                                        <input type="text" class="form-control form-control-sm" id="number-suffix" name="number-suffix">
                                    </div> 
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="transaction-description">Description</label>
                                        <textarea class="form-control form-control-sm" id="transaction-description" name="transaction-description"></textarea>
                                    </div>
                                </div>  
                            </div>                      
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="type-id" name="type-id">
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
    <div class="modal fade" id="delete-transaction-type-modal" tabindex="-1" role="dialog" aria-labelledby="delete-transaction-type-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">                    
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form id="id-type-delete-form" class="form-horizontal db-submit" action="<?php echo base_url('setup/transaction-types/delete'); ?>" method="post" data-initmsg="Deleting Transaction Type..." >
                        <div class="form-group">
                            <input type="hidden" name="del-transaction-type-id" id="del-transaction-type-id">
                            <div class="card-body">
                                <div class="callout callout-danger">
                                    <h5>Warning!</h5>
                                    <p>Are you sure you want to delete Transaction Type <span id="spn-del-transaction-type-name"></span>?</p>
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
<?php echo view('template\partial-footer'); ?>