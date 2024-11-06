<?php echo view('template/partial-header'); ?>
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">Manage Group Entities</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm" id="add-group-entity-btn" data-toggle="modal" data-target="#group-modal"><i class="fas fa-plus"></i> Add Group</button>
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <hr>
                                    <table id="group-entities-table" class="table table-bordered table-striped table-hover table-sm inverted">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th><small>#</small></th>
                                                <th><small>Group Name</small></th>
                                                <th><small>Region</small></th>
                                                <th><small>Sub-Region</small></th>
                                                <th><small>District</small></th>
                                                <th><small>Status</small></th>
                                                <th><small>Action</small></th>
                                            </tr>  
                                        </thead>
                                        <tbody>
                                        <?php 
                                        if($groups) {
                                            foreach($groups as $group) {
                                                $action = '';
                                                $suspend = '';
                                                $activate='';
                                                $delete='';
                                                $edit='';
                                                $groupStatus = '';
                                                
                                                if($group->GroupStatus != "Suspended" && $group->Deleted != "1") {
                                                    $action .= '<a href="#" title="Add Members" class="btn btn-xs bg-olive  add-group-members" data-group-id="'.$group->GroupID.'" data-toggle="modal" data-target="#group-members-modal"><i class="fas fa-user-plus"></i></a> <a href="#" title="Edit Group" data-toggle="modal" data-target="#group-modal" data-mode="edit" data-group-id="'.$group->GroupID.'" data-group-name="'.$group->GroupName.'" data-group-status="'.$group->GroupStatus.'" data-group-region="'.$group->RegionID.'" data-group-subregion="'.$group->SubRegionID.'" data-group-district="'.$group->DistrictID.'" data-description="'.$group->GroupDescription.'" class="btn btn-xs btn-primary"><i class="fas fa-edit"></i></a>  <a href="#" title="Suspend Group" class="btn btn-xs btn-warning disable-group group-status-change" data-group-name="'.$group->GroupName.'" data-group-id="'.$group->GroupID.'" data-group-new-status="Suspended" data-toggle="modal" data-target="#group-status-modal"><i class="fas fa-ban"></i></a>';
                                                }

                                                if($group->GroupStatus != "Active" && $group->Deleted != "1") {
                                                    $action .= ' <a href="#" title="Activate Group" class="btn btn-xs btn-success enable-group group-status-change" data-group-new-status="Active" data-group-name="'.$group->GroupName.'" data-toggle="modal" data-target="#group-status-modal" data-group-id="'.$group->GroupID.'"><i class="fas fa-check"></i></a>';
                                                }

                                                if($group->Deleted != "1") {
                                                    $action .= ' <a href="#" title="Delete Group" data-toggle="modal" data-target="#group-status-modal" data-group-name="'.$group->GroupName.'" data-group-new-status="Deleted"  class="btn btn-xs btn-danger delete-group group-status-change" data-group-id="'.$group->GroupID.'"><i class="fas fa-trash"></i></a>';
                                                }

                                                
                                                if($group->Deleted == "1") {
                                                    $groupStatus = '<span class="badge badge-danger">Deleted</span>';
                                                }else if($group->GroupStatus == "Active") {
                                                    $groupStatus = '<span class="badge badge-success">Active</span>';
                                                }else if($group->GroupStatus == "Inactive") {
                                                    $groupStatus = '<span class="badge badge-info">Inactive</span>';
                                                }else if($group->GroupStatus == "Suspended") {
                                                    $groupStatus = '<span class="badge badge-warning">Suspended</span>';
                                                }  
                                                echo '<tr>';
                                                echo '<td><small>'.$group->GroupID.'</small></td>';
                                                echo '<td><small>'.$group->GroupName.'</small></th>';
                                                echo '<td><small>'.$group->RegionName.'</small></td>';
                                                echo '<td><small>'.$group->SubRegionName.'</small></td>';
                                                echo '<td><small>'.$group->DistrictName.'</small></td>';
                                                echo '<td>'.$groupStatus.'</td>';
                                                echo '<td><small>'.$action.'</small></td>';
                                                echo '</tr>';
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
        </div>
    </section>
</div>

<!---Group Addition Modal--->
<div class="modal fade" id="group-modal" tabindex="-1" role="dialog" aria-labelledby="group-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="group-modalLabel"><span id="spn-group-exc-mode"></span> Group</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="group-addition-form" class="form-horizontal db-submit" action="<?php echo base_url('entities/create-group'); ?>" method="post">
                    <div id="group-addition-form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="group-name">Group Name</label>
                                    <input type="text" class="form-control form-control-sm" id="group-name" name="group-name" placeholder="Group Name" required="required">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="group-district">District</label>
                                    <select class="form-control form-control-sm select2" id="group-district" name="group-district" required="required">
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
                                    <label for="group-region">Region</label>
                                    <input type="text" name="group-region" id="group-region" class="form-control form-control-sm" readonly="readonly">
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sub-region">Sub Region</label>
                                    <input type="text" name="sub-region" id="sub-region" class="form-control form-control-sm" readonly="readonly">
                                </div>
                            </div>                       
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="group-description">Group description</label>
                                    <textarea class="form-control form-control-sm" id="group-description" name="group-description"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="group-add-btn">Add</button>
            </div>
        </div>
    </div>
</div>
<!-- Group Status Model -->
<div class="modal fade" id="group-status-modal" tabindex="-1" role="dialog" aria-labelledby="group-status-modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="group-status-modalLabel">Group Status Change</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="group-status-form" class="form-horizontal db-submit" action="<?php echo base_url('entities/group-status-change'); ?>" method="post">
                    <div id="group-status-form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                   <i class="fas fa-exclamation-triangle text-danger fa-3x"></i> Are you sure you want to <strong> <span id="spn-action"></span> <span id="spn-group-name"></span> </strong>?
                                   <input type="hidden" name="group-status" id="group-status">
                                   <input type="hidden" name="group-id" id="group-id">
                                   <input type="hidden" name="group-new-status" id="group-new-status">
                                   <input type="hidden" name="exec-mode" id="exec-mode">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">No, Dismiss This <i class="fas fa-times"></i></button>
                <button type="button" class="btn btn-primary" id="group-status-btn">Yes, I do! <i class="fas fa-check"></i></button> </button>
            </div>
        </div>
    </div>
</div>

<?php echo view('template\partial-footer'); ?>