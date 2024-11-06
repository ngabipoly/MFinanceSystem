<?php echo view('template/partial-header'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row mt-2">
                <div class="col-sm-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <strong><span class="text-capitalize"><?php echo  $mode ?></span> Application</strong>
                        </div>
                        <div class="card-body">
                            <form id="loan-application-form" method="post" action="<?php echo base_url('loans/save-loan');?>" class="form-horizontal">
                                <input type="hidden" name="loan-id" id="loan-id" value="<?php echo old('loan-id')||($mode=="edit") ? $loan->LoanID : ''; ?>">
                                <input type="hidden" name="exec-mode" id="exec-mode" value="<?php echo $mode; ?>">
                                <div class="row">
                                    <?php 
                                        echo csrf_field(); 
                                    ?>
                                <?php if (session()->has('errors')){ ?>
                                    <div class="alert col-md-8  alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-exclamation-triangle"></i> Error Saving Loan Details! </h5>
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
                                    <div class="col-md-6">
                                        <div class="row mb-2">
                                            <div class="col-md-12">
                                                <a href="#" id="btn-get-customer" data-toggle="modal" data-target="#customer-modal"   class="btn btn-sm btn-primary">Select Customer <i class="fas fa-user-tie"></i></a>
                                            </div>
                                        </div>
                                        <div id="customer-details">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <img src="" id="customer-photo" alt="" style="width: 100%; height: 120px;">
                                                </div>
                                                <div class="col-md-9">
                                                        <h3 id="customer-name"></h3>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="customer-idnumber">NIN:</label>
                                                                <span id="customer-idnumber" class="mr-2"></span>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="customer-dob">Birth Date:</label>
                                                                <span id="customer-dob" class="mr-2"></span> 
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="customer-gender">Gender:</label>
                                                                <span id="customer-gender" class="mr-2"></span>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="customer-age">Age</label>
                                                                <span id="customer-age" class="mr-2"></span>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="customer-phone">Telephone</label>
                                                                <span id="customer-phone" class="mr-2"></span>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <label for="customer-occupation">Occupation</label>
                                                                <span id="customer-occupation" class="mr-2"></span>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-10">
                                                                <label for="customer-address">Address</label>
                                                                <Address id="customer-address"></Address>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <img src="" alt="" style="width: 100%; height: 100px;" id="customer-idfront">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <img src="" alt="" style="width: 100%; height: 100px;" id="customer-idback">
                                                            </div>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">    
                                        <input type="hidden" name="customer-id" id="customer-id" value="<?php echo old('customer-id')||($mode=="edit") ? old('customer-id') : ''; ?>">                                                                                                                       
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="loan-product">Loan Product</label>
                                                        <div class="input-group input-group-sm">
                                                            <select name="loan-product" id="loan-product" class="form-control form-control-sm">
                                                                <option value="">Select Account Type</option>
                                                                <?php foreach ($loanProducts as $loanProduct) { ?>
                                                                    <option value="<?php echo $loanProduct['ProductID']; ?>" <?php echo ($loanProduct['ProductName'] == old('loan-product')|| ($mode == 'edit' && $loan->ProductID == $loanProduct['ProductID']))? 'selected': ''?>  data-min-amt="<?php echo $loanProduct['MinAmount']; ?>" data-max-amt="<?php echo $loanProduct['MaxAmount']; ?>" data-interest-rate="<?php echo $loanProduct['InterestRate']; ?>" data-MinTermMonths="<?php echo $loanProduct['MinTermMonths']; ?>" data-MaxTermMonths="<?php echo $loanProduct['MaxTermMonths']; ?>"><?php echo $loanProduct['ProductName'].' - '.$loanProduct['InterestRate'].'%'; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="loan-category">Loan Range</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" name="min-amt" id="min-amount" class="form-control form-control-sm"> - 
                                                            <input type="number" name="max-amt" id="max-amount" class="form-control form-control-sm">
                                                            <input type="hidden" name="interest-rate" id="interest-rate">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="loan-amount">Loan Amount</label>
                                                        <div class="input-group input-group-sm">                                                    
                                                            <input type="text" name="loan-amount" value="<?php echo old('loan-amount') ? old('loan-amount') : ($mode == 'edit' ? $loan->PrincipalAmount : 0);  ?>" id="loan-amount" class="form-control form-control-sm">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i class="fas fa-number"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="loan-duration">Loan Term</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" name="loan-duration" value="<?php echo old('loan-duration') ? old('loan-duration') : ($mode == 'edit' ? $loan->Term : 0);  ?>" id="loan-duration" class="form-control form-control-sm">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i class="fas fa-number"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="repayment-schedule"> Repayment Schedule</label>
                                                        <div class="input-group input-group-sm">  
                                                            <?php
                                                            $schedule = ''; 
                                                            if(old('repayment-schedule')){
                                                                $schedule = old('repayment-schedule');
                                                            }elseif($mode == 'edit'){
                                                                $schedule = $loan->schedule;
                                                            }   
                                                            ?>                                                 
                                                            <select name="repayment-schedule" id="repayment-schedule" class="form-control form-control-sm">
                                                                <option value="M" <?php echo ($schedule == 'M')? 'selected': ''?> >Monthly</option>
                                                                <option value="W" <?php echo ($schedule == 'W')? 'selected': ''?> >Weekly</option>
                                                                <option value="D" <?php echo ($schedule == 'D')? 'selected': ''?> >Daily</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>  
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="loan-purpose">Loan Purpose</label>
                                                        <div class="input-group input-group-sm">
                                                            <textarea name="loan-purpose" id="loan-purpose" class="form-control form-control-sm" rows="3"><?php echo old('loan-purpose') ?? ($mode == 'edit' ? $loan->Purpose : '') ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>                                                                                      
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <button type="submit" id="save-application"  class="btn btn-sm btn-primary float-right">Save Application</button>
                            <button type="reset" id="cancel-application" class="btn btn-sm btn-danger">Reset</button>
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
                <div class="modal-header bg-primary">
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
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if($customers){
                                    $photoPath = CLIENT_PHOTO_PATH;
                                    $IdFront = ID_FRONT_PHOTO_PATH;
                                    $IdBack = ID_BACK_PHOTO_PATH;
                                    $sex = ['F'=>'Female','M'=>'Male'];
                                    foreach($customers as $customer){
                                        $photo = $photoPath.$customer->CustomerPhoto;
                                        $idFace = $IdFront.$customer->IDFrontFace;
                                        $idBack = $IdBack.$customer->IDBackFace;
                                        $gender = $sex[$customer->Gender];
                                        echo "<tr>
                                            <td><small><a href='#' class='select-customer' data-customer-id='$customer->ClientID' data-customer-names='$customer->Names' data-customer-idnumber='$customer->IDNumber' data-customer-occupation='$customer->Occupation' data-customer-phonenumber='$customer->PhoneNumber' data-customer-dob='$customer->DateOfBirth' data-customer-age='$customer->Age' data-customer-gender='$gender' data-customer-address='$customer->Address' data-customer-photo='$photo' data-customer-idfront='$idFace' data-customer-idback='$idBack' >$customer->Names</a></small></td>
                                            <td><small>$customer->IDNumber</small></td>
                                            <td><small>$customer->Occupation</small></td>
                                            <td><small>$customer->PhoneNumber</small></td>
                                            </tr>";
                                    }
                                }
                                ?>
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
