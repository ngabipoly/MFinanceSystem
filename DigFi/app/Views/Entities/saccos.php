<?php echo view('template/partial-header'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><?php echo $title; ?></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Entity</a></li>
                        <li class="breadcrumb-item active">Saccos</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Saccos</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm" id="add-sacco-btn" data-toggle="modal" data-target="#sacco-modal"><i class="fas fa-plus"></i> Add Sacco</button>
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="sacco-table" class="table table-bordered table-striped table-hover table-sm data-table">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th><small><strong>Sacco ID</strong></small></th>
                                                    <th><small><strong>Sacco Name</strong></small></th>
                                                    <th><small><strong>Sacco Phone</strong></small></th>
                                                    <th><small><strong>Sacco Email</strong></small></th>
                                                    <th><small><strong>Sacco Status</strong></small></th>
                                                    <th><small><strong>Actions</strong></small></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach ($saccos as $sacco) {
                                                        $action = '<div class="btn-group"> <a class="btn btn-sm btn-primary view-sacco" data-sacco-id="'. $sacco->SaccoID .'" data-link="'.base_url('entities/get-sacco').'" title="View Sacco Details" data-toggle="modal" data-target="#sacco-modal"><i class="fas fa-eye"></i></a>';
                                                        if ($sacco->SaccoStatus != "Active" && $sacco->Deleted != "1") {
                                                            $action.= '<a class="btn btn-sm btn-success sacco-status" data-sacco-id="' . $sacco->SaccoID . '"  data-sacco="'.$sacco->SaccoName.'" title="Activate Sacco" data-old-status="'.$sacco->SaccoStatus.'" data-new-status="Active" data-action="Activate" data-toggle="modal" data-target="#sacco-status-modal"><i class="fas fa-check"></i></a>';
                                                        }
                                                        if ($sacco->SaccoStatus == "Active" && $sacco->Deleted != "1") {
                                                            $action.= '<a class="btn btn-sm bg-olive members" data-entity-type="S" data-entity-id="'.$sacco->SaccoID.'" title="Manage Sacco Members"><i class="fas fa-users"></i></a> <a class="btn btn-sm btn-warning sacco-status" data-sacco="'.$sacco->SaccoName.'" data-sacco-id="' . $sacco->SaccoID . '" title="Suspend Sacco"  data-old-status="'.$sacco->SaccoStatus.'" data-new-status="Suspended" data-toggle="modal" data-action="Suspend" data-target="#sacco-status-modal"><i class="fas fa-ban"></i></a>';
                                                        }
                                                        if ($sacco->Deleted == "0") {
                                                            $action.= '<a class ="btn btn-sm btn-info edit-sacco" data-sacco-id="' . $sacco->SaccoID . '"  data-link="'.base_url('entities/get-sacco').'" title="Edit Sacco" data-toggle="modal" data-target="#sacco-modal"><i class="fas fa-edit"></i></a> <a class="btn btn-sm btn-danger sacco-status" data-action="Delete" data-sacco="'.$sacco->SaccoName.'" data-sacco-id="' . $sacco->SaccoID . '" title="Delete Sacco"  data-old-status="'.$sacco->SaccoStatus.'" data-new-status="Deleted" data-toggle="modal" data-target="#sacco-status-modal"><i class="fas fa-trash"></i></a>';
                                                        }
                                                        $action.= '</div>';
                                                        echo '<tr>
                                                                <td><small>' . $sacco->SaccoID . '</small></td> 
                                                                <td><small>' . $sacco->SaccoName . '</small></td>
                                                                <td><small>' . $sacco->Phone . '</small></td>
                                                                <td><small>' . $sacco->Email . '</small></td>
                                                                <td><small>' . $sacco->SaccoStatus . '</small></td>
                                                                <td><small>' . $action . '</small></td>
                                                            </tr>';
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
            </div>
        </div>
        <!-- saccos modal -->
        <div class="modal fade" id="sacco-modal" tabindex="-1" role="dialog" aria-labelledby="sacco-modalLabel" aria-hidden="true" data-initmsg="Adding Sacco details...">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary pb-2">
                        <h5 class="modal-title" id="sacco-modalLabel"><span id="sacco-action"></span>  Sacco</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="sacco-form" class="form-sm form-horizontal db-submit" method="post"  action="<?php echo base_url('entities/save-sacco'); ?>">
                            <input type="hidden" id="sacco-id" name="sacco-id" value="0">
                            <input type="hidden" name="exec-mode" id="exec-mode" value="add">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sacco-name">Registered Name</label>
                                        <input type="text" class="form-control form-control-sm" id="sacco-name" name="sacco-name" placeholder="Enter Sacco Name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sacco-phone">Phone Contact</label>
                                        <input type="text" class="form-control form-control-sm" id="sacco-phone" name="sacco-phone" placeholder="Enter Sacco Phone">
                                    </div>
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sacco-email">Email</label>
                                        <input type="text" class="form-control form-control-sm" id="sacco-email" name="sacco-email" placeholder="Enter Sacco Email">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sacco-district">District</label>
                                        <select class="form-control form-control-sm district select2" id="sacco-district" name="sacco-district">
                                            <option value="">Select District</option>
                                                <?php foreach($districts as $district) { ?>
                                                    <option value="<?php echo $district->DistrictID; ?>" data-subregion-id="<?php echo $district->SubRegionID; ?>" data-group-region-id="<?php echo $district->RegionID; ?>" data-subregion-name="<?php echo $district->SubRegionName; ?>" data-group-region-name="<?php echo $district->RegionName; ?>"><?php echo $district->DistrictName; ?></option>
                                                <?php } ?>
                                        </select>
                                    </div>
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sacco-region">Region</label>
                                        <input type="text" class="form-control form-control-sm region" id="sacco-region" name="sacco-region" placeholder="Enter Sacco Region" readonly="readonly">
                                    </div>
                                </div>  
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sacco-sub-region">Sub Region</label>
                                        <input type="text" class="form-control form-control-sm sub-region" id="sacco-sub-region" name="sacco-sub-region" placeholder="Enter Sacco Sub Region" readonly="readonly">
                                    </div>
                                </div>                                                              
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sacco-address">Address</label>
                                        <textarea type="text" class="form-control form-control-sm" id="sacco-address" name="sacco-address" placeholder="Enter Sacco Address"></textarea>
                                    </div>
                                </div>  
                                <div class="col-md-6">                           
                                    <div class="form-group">
                                        <label for="sacco-description">Description</label>
                                        <textarea class="form-control form-control-sm" id="sacco-description" name="sacco-description" placeholder="Enter Sacco Description"></textarea>
                                    </div>
                                </div>                                                              
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="save-sacco" class="btn btn-sm btn-primary" form="sacco-form">Save</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sacco Status Model -->
        <div class="modal fade" id="sacco-status-modal" tabindex="-1" role="dialog" aria-labelledby="sacco-status-modalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="sacco-status-modalLabel">Changing Sacco Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="sacco-status-form" class="form-horizontal db-submit" action="<?php echo base_url('entities/change-sacco-status'); ?>" method="post">
                            <div id="sacco-status-form">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                        <i class="fas fa-exclamation-triangle text-danger fa-3x"></i> Are you sure you want to <strong> <span id="spn-action"></span> <span id="spn-sacco-name"></span> </strong>?
                                        <input type="hidden" name="sacco-status" id="sacco-status">
                                        <input type="hidden" name="saccos-id" id="saccos-id">
                                        <input type="hidden" name="sacco-new-status" id="sacco-new-status">
                                        <input type="hidden" name="exec-mode" id="exec-mode">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">No, Dismiss This <i class="fas fa-times"></i></button>
                        <button type="button" class="btn btn-primary" id="sacco-status-btn">Yes, I am! <i class="fas fa-check"></i></button> </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php echo view('template/partial-footer'); ?>