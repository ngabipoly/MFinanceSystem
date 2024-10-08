<?php echo view('template\partial-header'); ?>
  <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <?php 
           // $users=json_decode(json_encode($user));
        ?>

        <section class="content">
            <div class="container-fluid">
                <div class="row mt-2">
                    <div class="col-sm-12">
                        <div class="card card-info">
                            <div class="card-header" >
                               <strong>Account Management</strong> 
                            </div>
                            <div class="card-body">
                            <?php
                                if (session()->getFlashdata('success')) { ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Success!</strong> <?php echo session()->getFlashdata('success'); ?>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                <?php }
                            ?>
                            <a href="<?php echo base_url('customer/create-edit-account');?>" id="new-account" class="btn btn-xs btn-success">New Account <i class="fas fa-plus-circle"></i></a>
                            
                                <div class="table-responsive mt-2">
                                    <table class="table data-table-simple table-bordered table-striped table-hover table-sm" width="100%">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th><strong><small>Account Number</small></strong></th>
                                                <th><strong><small>Account Name</small></strong></th>
                                                <th><strong><small>Account Type</small></strong></th>
                                                <th><strong><small>Category</small></strong></th> 
                                                <th><strong><small>Account Status</small></strong></th>
                                                <th><strong><small>Action</small></strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!$accounts){
                                              
                                            }else{
                                            foreach ($accounts as $account) { ?>
                                                <tr>
                                                    <td>
                                                        <small>
                                                            <?php echo $account->AccountNumber; ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <small>
                                                            <?php echo $account->AccountName; ?>
                                                            </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo $account->AccountTypeName; ?> </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo $account->AccountNatureName; ?> </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo $account->AccountStatus; ?> </small>
                                                    </td>
                                                    <td>

                                                        <a href="<?php echo base_url('customer/accounts/edit/'.$account->AccountID);?>" class="btn btn-xs btn-primary edit-client"  data-editid="<?php echo $account->AccountID; ?>" title="Edit <?php echo $account->AccountName;?>"><i class="fas fa-edit"></i></a>
                                                        <?php 
                                                            if ($account->Deleted==0) {                                                               
                                                            echo "<a href='#' data-toggle='modal' data-target='#confirm-delete-modal' class='btn btn-xs btn-danger delete-account' data-deleteid='$account->AccountID' title='Delete $account->AccountName'><i class='fas fa-trash'></i></a> ";
                                                               }    
                                                            ?>
                                                        

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

    <!-- Confirm Customer Delete Modal -->
    <div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-labelledby="confirm-delete-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">                    
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form id="account-delete-form" class="form-horizontal db-submit" action="<?php echo base_url('customer/delete-account'); ?>" method="post" data-initmsg="Deleting account..." >
                        <div class="form-group">
                            <input type="hidden" name="delete-account-id" id="delete-account-id">
                            <div class="card-body">
                                <div class="callout callout-danger">
                                    <h5>Warning!</h5>
                                    <p>Are you sure you want to delete Account <span id="spn-delete-account-name"></span>?</p>
                                </div>
                                    <button type="button" class="btn btn-sm btn-secondary float-right" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-sm btn-danger float-right mr-2" id="delete-account">Delete</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php echo view('template\partial-footer'); ?>