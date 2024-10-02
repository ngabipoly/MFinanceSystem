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
                               <strong>ID Types Listing</strong> 
                            </div>
                            <div class="card-body">
                            <a href="#" id="new-id-type" data-toggle="modal" data-target="#id-type-modal" class="btn btn-xs btn-success">Add New</a>
                            
                                <div class="table-responsive mt-2">
                                    <table class="table data-table-simple table-bordered table-striped table-hover table-sm" width="100%">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th><strong><small>ID Type</small></strong></th>
                                                <th><strong><small>Type Category</small></strong></th>
                                                <th><strong><small>Pattern</small></strong></th>
                                                <th><strong><small>Creation Date</small></strong></th>
                                                <th><strong><small>Created By</small></strong></th> 
                                                <th><strong><small>Action</small></strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!$identificationTypes){
                                                # code...
                                            }else{
                                            foreach ($identificationTypes as $idType) { ?>
                                                <tr>
                                                    <td>
                                                        <small>
                                                            <?php echo $idType->IDTypeName; ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <small>
                                                            <?php echo $idType->TypeCategory; ?>
                                                            </small>
                                                    </td>
                                                    <td>
                                                        <small> <?php echo 
                                                        $idType->IdPattern; ?> 
                                                        </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo $idType->CreatedAt; ?> </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo $idType->createdByName; ?> </small>
                                                    </td>
                                                    <td>
                                                        <a href="#" data-toggle="modal" data-target="#id-type-modal" class="btn btn-xs btn-warning edit-id-type"  data-editid="<?php echo $idType->IDTypeID; ?>"  data-category ="<?php echo $idType->TypeCategory; ?>" data-typename="<?php echo $idType->IDTypeName; ?>" data-pattern="<?php echo $idType->IdPattern;?>" data-len = "<?php echo $idType->IdNumberLength;?>" data-desc ="<?php echo $idType->IDTypeDescription;?>"><i class="fas fa-edit"></i></a>
                                                        
                                                        <a href="#" data-toggle="modal" data-target="#delete-id-type-modal" class="btn btn-xs btn-danger delete-id-type" data-typename="<?php echo $idType->IDTypeName; ?>" data-deleteid="<?php echo $idType->IDTypeID; ?>"><i class="fas fa-trash"></i></a>
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
    <div class="modal fade" id="id-type-modal" tabindex="-1" role="dialog" aria-labelledby="id-type-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="id-type-modalLabel"><span id="spn-action"></span> ID Type <span id="spn-id-type-name"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="id-type-form" method="post" class="form-horizontal db-submit" data-initmsg="Saving ID Type Changes..."  action="<?php echo base_url('setup/identification-Types/save'); ?>">
                    <div class="modal-body">  
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="id-type-category">ID Type Category</label>
                                    <select name="id-type-category" class="form-control form-control-sm " id="id-type-category">
                                        <option value="">Select</option>
                                        <option value="NUG">National ID - Ugandan</option>
                                        <option value="INT">International ID</option>
                                        <option value="DPEA">Driving Permit - East Africa</option>
                                    </select>
                                    
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="id-type-name">ID Type Name</label>
                                    <input type="text" class="form-control form-control-sm" id="id-type-name" name="id-type-name">
                                </div>
                            </div>
                        </div>                  
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="id-description">Description</label>
                                        <textarea class="form-control form-control-sm" id="id-description" name="id-description"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="id-number-length">Id Number Length</label>
                                        <input type="number" class="form-control form-control-sm" id="id-number-length" name="id-number-length">
                                    </div> 
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="number-pattern">Number Pattern</label>
                                        <input type="text" class="form-control form-control-sm"   name="number-pattern" id="number-pattern">
                                    </div>    
                                </div>
                            </div>                        
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="type-id" name="type-id">
                            <input type="hidden" name="execMode" id="execMode" value="add">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="save-product">Save Type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Id Type Delete Modal -->
    <div class="modal fade" id="delete-id-type-modal" tabindex="-1" role="dialog" aria-labelledby="delete-id-type-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">                    
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form id="id-type-delete-form" class="form-horizontal db-submit" action="<?php echo base_url('setup/identification-Types/delete'); ?>" method="post" data-initmsg="Deleting Id Type..." >
                        <div class="form-group">
                            <input type="hidden" name="del-id-type-id" id="del-id-type-id">
                            <div class="card-body">
                                <div class="callout callout-danger">
                                    <h5>Warning!</h5>
                                    <p>Are you sure you want to delete Id Type <span id="spn-del-id-type-name"></span>?</p>
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