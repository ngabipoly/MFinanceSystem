<?php echo view('template/partial-header'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row mt-2">
                <div class="col-sm-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <strong>Customer Registration</strong>
                        </div>
                        <div class="card-body">
                            <form id="customer-registration-form" method="post" action="<?php echo base_url('customer/customer-save');?>" class="form-horizontal" enctype="multipart/form-data">
                                <input type="hidden" name="customer-id" id="customer-id" value="<?php echo old('customer-id')||($mode=="edit") ? $customer->ClientID : ''; ?>">
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
                                    <div class="col-md-6">
                                        <div class="card card-primary">
                                            <div class="card-header">
                                                <strong>Bio Data</strong>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="input-group input-group-sm">
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input" name="customer-photo" id="customer-photo" accept="image/*">
                                                                    <label for="customer-photo" class="custom-file-label">Customer Photo</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div id="customer-photo-preview">
                                                            <img src="<?php echo ($mode=='edit') ? CLIENT_PHOTO_PATH.$customer->CustomerPhoto: UPLOADS_PATH.'noimage.jpg'; ?>" id="customer-photo-preview-img">
                                                        </div>
                                                    </div>                                                    
                                                </div>

                                                <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">                                                            
                                                                <div class="input-group input-group-sm">
                                                                    <div class="custom-file">
                                                                        <input type="file" class="custom-file-input" name="id-front-face" id="id-front-face" accept="image/*">
                                                                        <label for="id-front-face" class="custom-file-label" >ID Front Face</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div id="id-front-face-preview">
                                                                <img src="<?php echo ($mode=='edit')? CLIENT_PHOTO_PATH.$customer->IDFrontFace: UPLOADS_PATH.'noimage.jpg'; ?>" id="id-front-face-preview-img">
                                                            </div>
                                                        </div>
                                                </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="input-group input-group-sm">
                                                                    <div class="custom-file">
                                                                        <input type="file" class="custom-file-input" name="id-back-face" id="id-back-face" accept="image/*">
                                                                        <label for="id-back-face" class="custom-file-label" >ID Back Face</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div id="id-back-face-preview">
                                                                <img src="<?php echo ($mode=='edit')? CLIENT_PHOTO_PATH.$customer->IDBackFace : UPLOADS_PATH.'noimage.jpg'; ?>" id="id-back-face-preview-img">
                                                            </div>
                                                        </div>                                                    
                                                    </div>                                                
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="input-group input-group-sm">
                                                                    <div class="custom-file">
                                                                        <input type="file" class="custom-file-input" name="customer-signature" id="customer-signature" accept="image/*">
                                                                        <label for="customer-signature" class="custom-file-label" >Customer Signature</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div id="customer-signature-preview">
                                                                <img src="<?php echo ($mode=='edit')?  SIGNATURE_PATH.$customer->CustomerSignature: UPLOADS_PATH.'noimage.jpg'; ?>" id="customer-signature-preview-img">
                                                            </div>
                                                        </div>                                                    
                                                    </div>                                                

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="id-type">ID Type</label>
                                                        <div class="input-group input-group-sm">
                                                            <select name="id-type" id="id-type" class="form-control form-control-sm">
                                                                <option value="">Select ID Type</option>
                                                                <?php foreach ($idTypes as $idType) { ?>
                                                                    <option value="<?php echo $idType['IDTypeID']; ?>" <?php echo ($idType['IDTypeID'] == old('id-type')|| ($mode == 'edit' && $customer->IdType == $idType['IDTypeID']))? 'selected': ''?> ><?php echo $idType['IDTypeName']; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="id-number">ID Number</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" name="id-number" value="<?php echo old('id-number') || ($mode == 'edit' ) ?  $customer->IDNumber: ''; ?>" id="id-number" class="form-control form-control-sm">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i class="fas fa-keyboard"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label  for="first-name">First Name</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" name="first-name" id="first-name" value="<?php echo old('first-name') || ($mode == 'edit') ? $customer->FirstName : ''; ?>" class="form-control form-control-sm">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i class="fas fa-keyboard"></i>
                                                                </span>                                                
                                                            </div>
                                                        </div>                                                          
                                                    </div>
                                                    <div class="col-md-6">
                                                            <label  for="middle-name">Middle Name</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" name="middle-name" id="middle-name" value="<?php echo old('middle-name')|| ($mode === 'edit') ? $customer->MiddleName : ''; ?>" class="form-control form-control-sm">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i class="fas fa-keyboard"></i>
                                                                </span>                                                
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label  for="last-name">Last Name</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" name="last-name" id="last-name" value="<?php echo old('last-name')|| ($mode == 'edit') ? $customer->LastName : ''; ?>" class="form-control form-control-sm">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i class="fas fa-keyboard"></i>
                                                                </span>                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="date-of-birth" >Date of Birth</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="date" name="date-of-birth" id="date-of-birth" value="<?php echo old('date-of-birth') || ($mode == 'edit') ? $customer->DateOfBirth: ''; ?>" class="form-control form-control-sm">            
                                                        </div>                                        
                                                    </div>                                                     
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label  for="gender">Gender</label>
                                                        <div class="input-group input-group-sm">
                                                            <select name="gender" id="gender" class="form-control form-control-sm">
                                                                <option value="">Select One</option>
                                                                <option value="M" <?php echo (old('gender') == 'M' || ($mode == 'edit' && $customer->Gender == 'M')) ? 'selected' : ''; ?>
                                                                >Male</option>
                                                                <option value="F" <?php echo (old('gender') == 'F' || ($mode == 'edit' && $customer->Gender == 'F')) ? 'selected' : ''; ?>
                                                                >Female</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label  for="marital-status">Marital Status</label>
                                                        <div class="input-group input-group-sm">
                                                            <select name="marital-status" id="marital-status" class="form-control form-control-sm">
                                                                <option value="">Select One</option>
                                                                <option value="S" <?php echo (old('marital-status') == 'S' || ($mode == 'edit' && $customer->MaritalStatus == 'S')) ? 'selected' : ''; ?>>Single</option>
                                                                <option value="M" <?php echo (old('marital-status') == 'M' || ($mode == 'edit' && $customer->MaritalStatus == 'M')) ? 'selected' : ''; ?>>Married</option>
                                                            </select>
                                                        </div>
                                                    </div>                                                        
                                                </div>  
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label  for="next-of-kin">Next of Kin</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" name="next-of-kin" id="next-of-kin" value="<?php echo old('next-of-kin') || $mode == 'edit' ? $customer->NextOfKin : '' ?>" class="form-control form-control-sm">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i class="fas fa-keyboard"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label  for="next-of-kin-type">Next of Kin Type</label>  
                                                        <div class="input-group input-group-sm">
                                                            <select name="next-of-kin-type" id="next-of-kin-type"  class="form-control form-control-sm">
                                                                <option value="">Select One</option>
                                                                <option value="Parent" <?php echo (old('next-of-kin-type') == 'Parent'||($mode == 'edit' && $customer->NextOfKinRelationship == 'Parent'))? 'selected': ''?>>Parent</option>
                                                                <option value="Child" <?php echo (old('next-of-kin-type') == 'Child' || ($mode == 'edit' && $customer->NextOfKinRelationship == 'Child'))? 'selected': ''?>>Child</option>
                                                                <option value="Sibling" <?php echo (old('next-of-kin-type') == 'Sibling' || ($mode == 'edit' && $customer->NextOfKinRelationship == 'Sibling'))? 'selected': ''?>>Sibling</option>
                                                                <option value="Uncle" <?php echo (old('next-of-kin-type') == 'Uncle' || ($mode == 'edit' && $customer->NextOfKinRelationship == 'Uncle'))? 'selected': ''?>>Uncle</option>
                                                                <option value="Aunt" <?php echo (old('next-of-kin-type') == 'Aunt' || ($mode == 'edit' && $customer->NextOfKinRelationship == 'Aunt'))? 'selected': ''?>>Aunt</option>
                                                                <option value="Spouse" <?php echo (old('next-of-kin-type') == 'Spouse' || ($mode == 'edit' && $customer->NextOfKinRelationship == 'Spouse'))? 'selected': ''?>>Spouse</option>
                                                            </select>
                                                        </div>
                                                    </div>  
												</div>	                                                                                              
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card card-info">
                                            <div class="card-header">
                                                <strong>Additional Details</strong>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="customer-email">Email</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="customer-email" name="customer-email" id="customer-email" value="<?php echo old('customer-email')|| ($mode == 'edit') ? $customer->Email:'' ?>" class="form-control form-control-sm">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i class="fas fa-keyboard"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="phone-number">Customer Phone Number</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" name="phone-number" id="phone-number" value="<?php echo old('phone-number') || ($mode == 'edit') ? $customer->PhoneNumber : '' ?>" class="form-control form-control-sm">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i class="fas fa-phone"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="address">Customer Address</label>
                                                        <div class="input-group input-group-sm">
                                                            <textarea name="address" id="address" class="form-control form-control-sm"><?php echo old('address') ?? ($mode == 'edit') ? $customer->Address : '' ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="next-of-kin-contact">Next of Kin Phone Number</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" name="next-of-kin-contact" id="next-of-kin-contact" value="<?php echo old('next-of-kin-contact') || ($mode == 'edit') ? $customer->NextOfKinPhoneNumber : '' ?>" class="form-control form-control-sm">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i class="fas fa-phone"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="next-of-kin-address">Next of Kin Address</label>
                                                        <div class="input-group input-group-sm">
                                                            <textarea name="next-of-kin-address" id="next-of-kin-address" class="form-control form-control-sm"><?php echo old('next-of-kin-address')|| ($mode == 'edit') ? $customer->NextOfKinAddress : '' ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="Occupation">Occupation</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" name="occupation" id="occupation" value="<?php echo old('occupation')|| ($mode == 'edit') ? $customer->Occupation : ''?>" class="form-control form-control-sm">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i class="fas fa-keyboard"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="annual-income">Annual Income</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" name="annual-income" id="annual-income" value="<?php echo old('annual-income')|| ($mode == 'edit') ? $customer->AnnualIncome : '' ?>" class="form-control form-control-sm">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i class="fas fa-keyboard"></i>
                                                                </span>
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
</div>

<?php echo view('template/partial-footer'); ?>
