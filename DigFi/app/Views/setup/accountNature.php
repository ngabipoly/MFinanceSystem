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
                               <strong>Account Nature Management</strong> 
                            </div>
                            <div class="card-body">
                            <a href="#" id="new-nature" data-toggle="modal" data-target="#nature-modal" class="btn btn-xs btn-success">Add New</a>
                            
                                <div class="table-responsive mt-2">
                                    <table class="table data-table-simple table-bordered table-striped table-hover table-sm" width="100%">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th><strong><small>Account Nature</small></strong></th>
                                                <th><strong><small>Description</small></strong></th>
                                                <th><strong><small>Min-Holders</small></strong></th>
                                                <th><strong><small>Max-Holders</small></strong></th>
                                                <th><strong><small>Creation Date</small></strong></th>
                                                <th><strong><small>Created By</small></strong></th> 
                                                <th><strong><small>Action</small></strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!$natures){
                                                # code...
                                            }else{
                                            foreach ($natures as $nature) { ?>
                                                <tr>
                                                    <td>
                                                        <small>
                                                            <?php echo $nature['AccountNatureName']; ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <small>
                                                            <?php echo $nature['Description']; ?>
                                                            </small>
                                                    </td>
                                                    <td>
                                                        <small> <?php 
                                                        echo $nature['MinHolders']; ?> 
                                                        </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo $nature['maxHolders']; ?> </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo $nature['CreatedAt']; ?> </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo $nature['createdBy']; ?> </small>
                                                    </td>
                                                    <td>
                                                        <a href="#" id="edit-nature" data-toggle="modal" data-target="#nature-modal" class="btn btn-xs btn-warning edit-nature" data-editid="<?php echo $nature['AccountNatureID']; ?>" data-editname="<?php echo $nature['AccountNatureName']; ?>" data-editdesc="<?php echo $nature['Description']; ?>" data-editmaxholders="<?php echo $nature['maxHolders']; ?>" data-editminholders="<?php echo $nature['MinHolders']; ?>"  ><i class="fas fa-edit"></i></a>
                                                        <a href="#" id="delete-nature" data-toggle="modal" data-target="#delete-nature-modal" class="btn btn-xs btn-danger delete-nature" data-nature="<?php echo $nature['AccountNatureName']; ?>" data-deleteid="<?php echo $nature['AccountNatureID']; ?>"><i class="fas fa-trash"></i></a>
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

    <!-- Product Modal -->
    <div class="modal fade" id="nature-modal" tabindex="-1" role="dialog" aria-labelledby="nature-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="nature-modalLabel"><span id="spn-action"></span> Account Nature <span id="spn-nature"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="nature-form" method="post" class="form-horizontal db-submit" data-initmsg="Saving Product Changes..."  action="<?php echo base_url('setup/account-nature/save'); ?>">
                    <div class="modal-body">                    
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="nature-name">Nature Name</label>
                                        <input type="text" class="form-control form-control-sm" id="nature-name" name="nature-name">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="nature-description">Description</label>
                                        <textarea class="form-control form-control-sm" id="nature-description" name="nature-description"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="product-type">Min Account Holders</label>
                                        <input type="number" class="form-control form-control-sm"  name="min-holders" id="min-holders">
                                    </div>    
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="max-holders">Max Account Holders</label>
                                        <input type="number" class="form-control form-control-sm" id="max-holders" name="max-holders">
                                    </div> 
                                </div>
                            </div>                        
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="nature-id" name="nature-id">
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
    <div class="modal fade" id="delete-nature-modal" tabindex="-1" role="dialog" aria-labelledby="delete-nature-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">                    
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form id="nature-delete-form" class="form-horizontal db-submit" action="<?php echo base_url('setup/account-nature/delete'); ?>" method="post" data-initmsg="Deleting Account Nature..." >
                        <div class="form-group">
                            <input type="hidden" name="del-nature-id" id="del-nature-id">
                            <div class="card-body">
                                <div class="callout callout-danger">
                                    <h5>Warning!</h5>
                                    <p>Are you sure you want to delete Account Nature <span id="spn-del-nature-name"></span>?</p>
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