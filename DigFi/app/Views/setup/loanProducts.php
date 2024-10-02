<?php echo view('template\partial-header'); ?>
  <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <?php 
            $users=json_decode(json_encode($user));
            $productStatus = [
                'A'=> 'Active',
                'I'=>'Inactive',
                'R'=>'Retired'
            ];
        ?>

        <section class="content">
            <div class="container-fluid">
                <div class="row mt-2">
                    <div class="col-sm-12">
                        <div class="card card-info">
                            <div class="card-header" >
                               <strong>Loan Product Management</strong> 
                            </div>
                            <div class="card-body">
                            <a href="#" id="new-product" data-toggle="modal" data-target="#product-modal" class="btn btn-xs btn-success">Add Product</a>
                            
                                <div class="table-responsive mt-2">
                                    <table class="table data-table-simple table-bordered table-striped table-hover table-sm" width="100%">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th><strong><small>Product Name</small></strong></th>
                                                <th><strong><small>Product Description</small></strong></th>
                                                <th><strong><small>Product Status</small></strong></th>
                                                <th><strong><small>Creation Date</small></strong></th>
                                                <th><strong><small>Term</small></strong></th> 
                                                <th><strong><small>Action</small></strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!$loans){
                                                # code...
                                            }else{
                                            foreach ($loans as $loan) { ?>
                                                <tr>
                                                    <td>
                                                        <small>
                                                            <?php echo $loan['ProductName']; ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <small>
                                                            <?php echo $loan['Description']; ?>
                                                            </small>
                                                    </td>
                                                    <td>
                                                        <small> <?php 
                                                        $statusCode = $loan['ProductStatus'];
                                                        $status = $productStatus["$statusCode"] ?? $statusCode;
                                                        echo $status; ?> 
                                                        </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo $loan['CreatedAt']; ?> </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo $loan['MinTermMonths']." - ".$loan['MaxTermMonths']." Months"; ?> </small>
                                                    </td>
                                                    <td>
                                                        <a href="#" id="edit-product" data-toggle="modal" data-target="#product-modal" class="btn btn-xs btn-warning edit-loan-product" data-editid="<?php echo $loan['ProductID']; ?>" data-editname="<?php echo $loan['ProductName']; ?>" data-editdesc="<?php echo $loan['Description']; ?>" data-editstatus="<?php echo $loan['ProductStatus']; ?>" data-editminterm="<?php echo $loan['MinTermMonths']; ?>" data-editmaxterm="<?php echo $loan['MaxTermMonths']; ?>" data-editminamount="<?php echo $loan['MinAmount']; ?>" data-editmaxamount="<?php echo $loan['MaxAmount']; ?>" data-editinttype="<?php echo $loan['InterestRateType']; ?>" data-editintrate="<?php echo $loan['InterestRate']; ?>" data-editcalcstyle="<?php echo $loan['CalculationFrequency']; ?>" data-editcalcmethod="<?php echo $loan['CalculationMethod']; ?>" ><i class="fas fa-edit"></i></a>
                                                        <a href="#" id="delete-product" data-toggle="modal" data-target="#product-delete-modal" class="btn btn-xs btn-danger delete-loan-product" data-product="<?php echo $loan['ProductName']; ?>" data-deleteid="<?php echo $loan['ProductID']; ?>"><i class="fas fa-trash"></i></a>
                                                        <a href="#" id="retire-product" data-toggle="modal" data-target="#product-retire-modal" class="btn btn-xs btn-primary retire-loan-product" data-retname="<?php echo $loan['ProductName']; ?>" data-retireid="<?php echo $loan['ProductID']; ?> "><i class="fas fa-ban"></i></a>
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
    <div class="modal fade" id="product-modal" tabindex="-1" role="dialog" aria-labelledby="product-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="product-modalLabel"><span id="spn-action"></span> Loan Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="product-form" method="post" class="form-horizontal db-submit" data-initmsg="Saving Product Changes..."  action="<?php echo base_url('setup/loans/save'); ?>">
                    <div class="modal-body">                    
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="product-name">Product Name</label>
                                        <input type="text" class="form-control form-control-sm" id="product-name" name="product-name">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="product-description">Product Description</label>
                                        <textarea class="form-control form-control-sm" id="product-description" name="product-description"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="product-type">Interest Rate Type</label>
                                        <select class="form-control form-control-sm" id="interest-rate-type" name="interest-rate-type">
                                            <option value="F">Flat</option>
                                            <option value="P">Percentage</option>
                                        </select>
                                    </div>    
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="interest-rate">Interest Rate</label>
                                        <input type="number" class="form-control form-control-sm" id="interest-rate" name="interest-rate">
                                    </div>                                                           
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="calculation-style">Calculation Frequency</label>
                                        <select class="form-control form-control-sm" id="calculation-style" name="calculation-style">
                                            <option value="D">Daily</option>
                                            <option value="W">Weekly</option>
                                            <option value="M">Monthly</option>
                                        </select>
                                    </div>     
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="calc-method"> Calculation Method</label>
                                        <select class="form-control form-control-sm" id="calculation-method" name="calculation-method">
                                            <option value="C">Compound</option>
                                            <option value="S">Simple</option>
                                            <option value="D">Decrease</option>
                                        </select>
                                    </div>                                
                                </div>                           
                            </div>     

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="minimum-term">Min Term(Months)</label>
                                        <input type="number" class="form-control form-control-sm" id="minimum-term" name="minimum-term">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="maximum-term">Max Term(Months)</label>
                                        <input type="number" class="form-control form-control-sm" id="maximum-term" name="maximum-term">
                                    </div>     
                                </div>                           
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="product-min-amount">Minimum Amount</label>
                                        <input type="number" class="form-control form-control-sm" id="product-min-amount" name="product-min-amount">
                                    </div>                                  
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="product-max-amount">Maximum Amount</label>
                                        <input type="number" class="form-control form-control-sm" id="product-max-amount" name="product-max-amount">
                                    </div>                                
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="product-status">Product Status</label>
                                        <select class="form-control form-control-sm" id="product-status" name="product-status">
                                            <option value="A">Active</option>
                                            <option value="I">Inactive</option>
                                        </select>
                                    </div>                                   
                                </div>                         
                            </div>
                        
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="product-id" name="product-id">
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
    <div class="modal fade" id="product-delete-modal" tabindex="-1" role="dialog" aria-labelledby="product-delete-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">                    
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form id="product-delete-form" class="form-horizontal db-submit" action="<?php echo base_url('setup/loans/delete'); ?>" method="post" data-initmsg="Deleting Loan Product..." >
                        <div class="form-group">
                            <input type="hidden" name="del-product-id" id="del-product-id">
                            <div class="card-body">
                                <div class="callout callout-danger">
                                    <h5>Warning!</h5>
                                    <p>Are you sure you want to delete Loan Product <span id="spn-del-product-name"></span>?</p>
                                </div>
                                    <button type="button" class="btn btn-sm btn-secondary float-right" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-sm btn-danger float-right mr-2" id="delete-product">Delete</button>
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