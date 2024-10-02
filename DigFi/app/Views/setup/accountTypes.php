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
                               <strong>Account Product Listing</strong> 
                            </div>
                            <div class="card-body">
                            <a href="#" id="new-ac-product" data-toggle="modal" data-target="#ac-product-modal" class="btn btn-xs btn-success">Add New</a>
                            
                                <div class="table-responsive mt-2">
                                    <table class="table data-table-simple table-bordered table-striped table-hover table-sm" width="100%">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th><strong><small>Account Product</small></strong></th>
                                                <th><strong><small>Minimum Balance</small></strong></th>
                                                <th><strong><small>Opening Balance</small></strong></th>
                                                <th><strong><small>Account Nature</small></strong></th>
                                                <th><strong><small>Creation Date</small></strong></th>
                                                <th><strong><small>Created By</small></strong></th> 
                                                <th><strong><small>Action</small></strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!$accountTypes){
                                                # code...
                                            }else{
                                            foreach ($accountTypes as $accountType) { ?>
                                                <tr>
                                                    <td>
                                                        <small>
                                                            <?php echo $accountType->AccountTypeName; ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <small>
                                                            <?php echo number_format($accountType->MinBal); ?>
                                                            </small>
                                                    </td>
                                                    <td>
                                                        <small> <?php 
                                                        echo number_format($accountType->OpeningBal); ?> 
                                                        </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo $accountType->AccountNatureName; ?> </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo $accountType->CreatedAt; ?> </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo $accountType->createdBy; ?> </small>
                                                    </td>
                                                    <td>
                                                        <a href="#" data-toggle="modal" data-target="#ac-product-modal" class="btn btn-xs btn-warning edit-account-product"  data-editid="<?php echo $accountType->AccountTypeID; ?>" data-productname="<?php echo $accountType->AccountTypeName; ?>" data-minbal="<?php echo $accountType->MinBal;?>" data-openbal="<?php echo $accountType->OpeningBal;?>" data-nature = "<?php echo $accountType->AccountNature;?>" data-desc ="<?php echo $accountType->Description;?>"><i class="fas fa-edit"></i></a>
                                                        
                                                        <a href="#" data-toggle="modal" data-target="#delete-ac-product-modal" class="btn btn-xs btn-danger delete-account-product" data-accounttype="<?php echo $accountType->AccountTypeName; ?>" data-deleteid="<?php echo $accountType->AccountTypeID; ?>"><i class="fas fa-trash"></i></a>
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

    <!-- Account Product Modal -->
    <div class="modal fade" id="ac-product-modal" tabindex="-1" role="dialog" aria-labelledby="ac-product-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="ac-product-modalLabel"><span id="spn-action"></span> Account Product <span id="spn-account"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="procuct-form" method="post" class="form-horizontal db-submit" data-initmsg="Saving Product Changes..."  action="<?php echo base_url('setup/account-type/save'); ?>">
                    <div class="modal-body">  
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="product-nature">Product Nature</label>
                                    <select name="product-nature" class="form-control form-control-sm select2" id="product-nature">
                                        <?php
                                            if ($natures) {
                                                foreach ($natures as $nature) { 
                                                    echo "<option value = '".$nature['AccountNatureID']."'> ".$nature['AccountNatureName']."</option>";     
                                                 }
                                            } ?>
                                    </select>
                                    
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="product-name">Product Name</label>
                                    <input type="text" class="form-control form-control-sm" id="product-name" name="product-name">
                                </div>
                            </div>
                        </div>                  
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="product-description">Description</label>
                                        <textarea class="form-control form-control-sm" id="product-description" name="product-description"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="opening-balance">Opening Balance</label>
                                        <input type="number" class="form-control form-control-sm" id="opening-balance" name="opening-balance">
                                    </div> 
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="product-type">Min Balance</label>
                                        <input type="number" class="form-control form-control-sm"   name="minimum-balance" id="minimum-balance">
                                    </div>    
                                </div>
                            </div>                        
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="ac-product-id" name="ac-product-id">
                            <input type="hidden" name="execMode" id="execMode" value="add">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="save-product">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Product Delete Modal -->
    <div class="modal fade" id="delete-ac-product-modal" tabindex="-1" role="dialog" aria-labelledby="delete-ac-product-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">                    
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form id="ac-product-delete-form" class="form-horizontal db-submit" action="<?php echo base_url('setup/account-type/delete'); ?>" method="post" data-initmsg="Deleting Account Product..." >
                        <div class="form-group">
                            <input type="hidden" name="del-account-product-id" id="del-account-product-id">
                            <div class="card-body">
                                <div class="callout callout-danger">
                                    <h5>Warning!</h5>
                                    <p>Are you sure you want to delete Account Product <span id="spn-del-account-product-name"></span>?</p>
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

    <!-- Product Retire Modal -->
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