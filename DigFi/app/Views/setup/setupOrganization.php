<?php echo view('template\partial-header'); ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Organization Setup</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Setup</a></li>
                        <li class="breadcrumb-item active">Organization</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Organization Setup</h3>
                        </div>
                        <div class="card-body">
                            <?php
                                //check if flash message is set
                                if(session()->getFlashdata('success')) { ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <strong>Success!</strong> <?php echo session()->getFlashdata('success'); ?>
                                    </div>
                                <?php }
                                elseif(session()->getFlashdata('error')){ ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <strong>Error!</strong> <?php echo session()->getFlashdata('error'); ?>
                                    </div>  <?php
                                }
                                ?>

                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                        $execMode = 'add';
                                        //check if organization has been setup
                                        if($organizationSettings != null) {
                                            $execMode = 'edit';
                                            ?>
                                            <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#setup-organization-modal">Edit Organization</a>
                                            <?php
                                        }else{
                                            ?>
                                            <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#setup-organization-modal">Setup Organization</a>
                                            <?php
                                        }
                                        ?>
                                    
                            </div>  
                            <div class="col-md-6 offset-md-2">
                                <div class="card card-primary mt-4">
                                    <div class="card-header">
                                        <h4 class="card-title">Organization Information</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <?php
                                                //check if organization has been setup
                                                if($organizationSettings != null) {
                                                    ?>
                                                    <!-- organization Information -->
                                                    <dt class="col-sm-4">Organization Name: </dt>
                                                    <dd class="col-sm-8 col-lg-6"><?php echo $organizationSettings->OrgName; ?></dd>
                                                    <hr width="98%"/>
                                                    <dt class="col-sm-4">Organization Logo:</dt>
                                                    <dd class="col-sm-8"><img src="<?php echo base_url("assets/images/logo/" . $organizationSettings->orgLogo); ?>" alt="Organization Logo" width="100" height="100"></dd>
                                                    <hr width="98%"/>
                                                    <dt class="col-sm-4">Organization Address</dt>
                                                    <dd class="col-sm-8"><?php echo $organizationSettings->orgAddress; ?></dd>
                                                    <hr width="98%"/>
                                                    <dt class="col-sm-4">Organization Phone</dt>
                                                    <dd class="col-sm-8"><?php echo $organizationSettings->orgPhoneNumber; ?></dd>
                                                    <hr width="98%"/>
                                                    <dt class="col-sm-4">Organization Email</dt>
                                                    <dd class="col-sm-8"><?php echo $organizationSettings->orgEmail; ?></dd>
                                                    <hr width="98%"/>
                                                    <dt class="col-sm-4">Organization Website</dt>
                                                    <dd class="col-sm-8"><a href="<?php echo $organizationSettings->orgWebsite; ?>" target="_blank"><?php echo $organizationSettings->orgWebsite; ?></a></dd>
                                                    <?php
                                                } else { ?>
                                                    <!-- alert -->
                                                    <div class="row">
                                                        <div class="col-md-12 justify-content-center align-items-center">
                                                            <div class="alert alert-danger mt-3 mr-3 ml-3">
                                                                <h5><i class="icon fas fa-exclamation-circle"></i> Alert!</h5>
                                                                Organization has not been setup yet. Please setup organization first.
                                                            </div>
                                                        </div>                                        
                                                    </div>
                                                    <?php                                    
                                                }
                                            ?>                 
                                        </div>
                                    </div>
                                </div>                                
                            </div> 

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.content -->
 <!-- Setup Organization Modal -->
    <div class="modal fade" id="setup-organization-modal">
        <div class="modal-dialog">
            <div class="modal-content ">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Setup Organization</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo base_url('setup/organization/save'); ?>" class="form-horizontal" method="post" id="organization-settings-form" data-initmsg="Saving Organization..." enctype="multipart/form-data">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="hidden" name="organization-id" id="organization-id" value="<?php echo $organizationSettings ? $organizationSettings->AppID : ''; ?>">
                                <input type="hidden" name="exec-mode" id="exec-mode" value="<?php echo $execMode; ?>">
                                <input type="hidden" name="account-id" id="account-id" value="<?php echo $organizationSettings ? $organizationSettings->accountId : ''; ?>">
                                <label for="organization-logo">Organization Logo</label>
                                <div class="input-group">
                                        <input type="file" class="form-control form-control-sm" id="organization-logo" name="organization-logo">
                                        <label class="custom-file-label" for="organization-logo">Choose file</label>
                                    
                                </div>
                            </div>
                        </div>                                    
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="organization-name" class="control-label">Organization Name</label>
                                <input type="text" class="form-control form-control-sm" id="organization-name" name="organization-name" value="<?php echo htmlspecialchars(old('organization-name', $organizationSettings ? $organizationSettings->OrgName : ''), ENT_QUOTES, 'UTF-8'); ?>">

                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="organization-phone">Organization Phone</label>
                                <input type="text" class="form-control form-control-sm" id="organization-phone" name="organization-phone" value="<?php echo htmlspecialchars(old('organization-phone', $organizationSettings ? $organizationSettings->orgPhoneNumber : ''), ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="organization-email">Organization Email</label>
                                <input type="text" class="form-control form-control-sm" id="organization-email" name="organization-email" value="<?php echo htmlspecialchars(old('organization-email', $organizationSettings ? $organizationSettings->orgEmail : ''), ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="organization-url">Organization Website</label>
                                <input type="url" name="organization-url" class="form-control form-control-sm" id="organization-url" value="<?php echo htmlspecialchars(old('organization-url', $organizationSettings ? $organizationSettings->orgWebsite : ''), ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="organization-address">Organization Address</label>
                                <textarea class="form-control form-control-sm" id="organization-address" name="organization-address" required><?php echo htmlspecialchars(old('organization-address', $organizationSettings ? $organizationSettings->orgAddress : ''), ENT_QUOTES, 'UTF-8'); ?>
                                </textarea>
                            </div>
                        </div>  
                </div>
                <div class="modal-footer justify-content-between">
                <button type="submit" class="btn btn-primary btn-sm float-right">Save</button>
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
                </div>                                  
            </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
<?php echo view('template\partial-footer'); ?>
