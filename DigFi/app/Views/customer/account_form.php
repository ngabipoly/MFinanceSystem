<?php echo view('template/partial-header'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row mt-2">
                <div class="col-sm-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <strong><span class="text-capitalize"><?php echo  $mode ?></span> Account</strong>
                        </div>
                        <div class="card-body">
                            <form id="account-registration-form" method="post" action="<?php echo base_url('customer/save-account');?>" class="form-horizontal">
                                <input type="hidden" name="account-id" id="account-id" value="<?php echo old('account-id')||($mode=="edit") ? $customer->ClientID : ''; ?>">
                                <input type="hidden" name="exec-mode" id="exec-mode" value="<?php echo $mode; ?>">
                                <div class="row">
                                    <?php 
                                        echo csrf_field(); 
                                    ?>
                                <?php if (session()->has('errors')){ ?>
                                    <div class="alert col-md-8  alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-ban"></i> Alert!</h5>
                                        <div class="errors">
                                            <ul>
                                                <?php 
                                                $errors = session()->get('errors');
                                                //if object or array
                                                if(is_object($errors) || is_array($errors)){
                                                    foreach ( $errors as $error){ ?>
                                                        <li><?php echo esc($error) ?></li>
                                                    <?php 
                                                    }
                                                 }else{ ?>
                                                       <li><?php echo $errors; ?></li>
                                                  <?php 
                                                   }                                                
                                                    ?>

                                            </ul>
                                        </div>
                                    </div>                    
                                <?php } ?>
                                    <div class="col-md-12">
                                        <div class="card card-primary">
                                            <div class="card-header">
                                                <strong>Account Details</strong>
                                            </div>
                                            <div class="card-body">                                                                                 
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="account-type">Account Type</label>
                                                        <div class="input-group input-group-sm">
                                                            <select name="account-type" id="account-type" class="form-control form-control-sm">
                                                                <option value="">Select Account Type</option>
                                                                <?php foreach ($accountTypes as $accountType) { ?>
                                                                    <option value="<?php echo $accountType['AccountTypeID']; ?>" <?php echo ($accountType['AccountTypeID'] == old('account-type')|| ($mode == 'edit' && $account->IdType == $accountType['AccountTypeID']))? 'selected': ''?> ><?php echo $accountType['AccountTypeName']; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="account-category">Account Category</label>
                                                        <div class="input-group input-group-sm">
                                                            <select name="account-category" id="account-category" class="form-control form-control-sm">
                                                                <option value="">Select Account Category</option>
                                                                <?php foreach ($categories as $category) { ?>
                                                                    <option value="<?php echo $category['AccountNatureID']; ?>" <?php echo ($category['AccountNatureID'] == old('account-category')|| ($mode == 'edit' && $customer->IdType == $category['AccountNatureID']))? 'selected': ''?> title="<?php echo $category['Description']; ?>" ><?php echo $category['AccountNatureName']; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="account-name">Account Name</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" name="account-name" value="<?php echo old('account-name') || ($mode == 'edit' ) ?  $account->IDNumber: ''; ?>" id="account-name" class="form-control form-control-sm">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i class="fas fa-keyboard"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br/>
                                                <div class="row">   
                                                    <div class="col-md-12">
                                                        <div class="card">
                                                            <div class="card-header bg-lightblue">
                                                                <strong>Account Holder Details</strong>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <a href="<?php echo base_url('customer/list-customers'); ?>" id="add-holder" class="btn btn-xs btn-primary" data-target="#customer-modal" data-toggle="modal"><i class="fa fa-plus"></i> Add Holder</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>                                                     
                                                </div>                                                                                                
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <button type="submit" id="save-customer"  class="btn btn-sm btn-primary float-right">Save Customer Details</button>
                            <button type="reset" id="cancel-registration" class="btn btn-sm btn-danger">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- customer-modal -->
    <div class="modal fade" id="customer-modal" tabindex="-1" role="dialog" aria-labelledby="customer-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customer-modalLabel">Attach Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="customer-list">
                        <table class="table data-table-simple table-bordered table-striped table-hover table-sm" id="customer-table" width="100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th><small>Customer Names</small></th>
                                    <th><small>ID Number</small></th>
                                    <th><small>Occupation</small></th>
                                    <th><small>Phone Number</small></th>
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
<!-- /.content-wrapper -->
 

<?php echo view('template/partial-footer'); ?>
