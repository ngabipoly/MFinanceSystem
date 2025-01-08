<div class="row">
                                    <div class="col-md-12">
                                        <button id="add-group-member" class="btn btn-xs btn-primary rounded circle" data-toggle="modal" data-target="#group-member-modal">Add Member <i class="fas fa-user-plus"></i></button>

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover table-sm table-striped data-table">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th><small>Member ID</small></th>
                                                        <th><small>Member Name</small></th>
                                                        <th><small>Member Status</small></th>
                                                        <th><small>Telephone</small></th>
                                                        <th><small>DEMNet Client</small></th>
                                                        <th><small>Action</small></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        foreach($members as $member) {
                                                            $names = explode(" ", $member->MemberName);
                                                            $firstName = $names[0];
                                                            $lastName = $names[1];
                                                            $action = "<div class='btn-group'> <a class='btn btn-xs btn-primary get-group-member' title='View Member' data-toggle='modal' data-target='#group-member-modal' data-mode='view' data-link='".base_url('entities/member')."' data-entity-type='G' data-entity-id='".$member->GroupID."'  data-member-id='".$member->GroupMemberID."'><i class='fas fa-eye'></i></a>";
                                                            if($member->GroupMemberStatus != "Active" && $member->Deleted != "1") {
                                                                $action .= "<a class='btn btn-xs btn-success member-status-change' title='Activate Member' data-new-status='Active'
                                                                data-group-id='".$member->GroupID."'
                                                                data-first-name='".$firstName."' data-last-name='".$lastName."' data-toggle='modal' data-target='#member-status-modal' data-member-id='".$member->GroupMemberID."'><i class='fas fa-check'></i></a>";
                                                            }

                                                            if($member->GroupMemberStatus == "Active" && $member->Deleted != "1") {
                                                                $action .= "<a class='btn btn-xs btn-warning member-status-change' title='Suspend Member' data-new-status='Suspended' data-member-id='".$member->GroupMemberID."' data-group-id='".$member->GroupID."'
                                                                data-first-name='".$firstName."' data-last-name='".$lastName."' data-toggle='modal' data-target='#member-status-modal' data-member-id='".$member->GroupMemberID."'><i class='fas fa-ban'></i></a>";
                                                            }

                                                            if($member->Deleted != "1") {
                                                                $action .= " <a class='btn btn-xs btn-primary get-group-member edit-member' title='Edit Member' data-toggle='modal' data-target='#group-member-modal' data-mode='edit' data-link='".base_url('entities/member')."' data-entity-type='G'  data-group-id='".$member->GroupID."'  data-member-id='".$member->GroupMemberID."' data-member-id='".$member->GroupMemberID."'><i class='fas fa-edit'></i></a> 
                                                                <a class='btn btn-xs btn-danger member-status-change' data-new-status='Deleted' data-group-id='".$member->GroupID."'  
                                                                data-first-name='".$firstName."' data-last-name='".$lastName."'  title='Delete Member' data-toggle='modal' data-target='#member-status-modal' data-member-id='".$member->GroupMemberID."'><i class='fas fa-trash'></i></a>";
                                                            }
                                                            
                                                            $action .= "</div>";
                                                            
                                                            if($member->Deleted == "1") {
                                                                $memberStatus = '<span class="badge badge-danger">Deleted</span>';
                                                            }else if($member->GroupMemberStatus == "Active") {
                                                                $memberStatus = '<span class="badge badge-success">Active</span>';
                                                            }else if($member->GroupMemberStatus == "Inactive") {
                                                                $memberStatus = '<span class="badge badge-info">Inactive</span>';
                                                            }else if($member->GroupMemberStatus == "Suspended") {
                                                                $memberStatus = '<span class="badge badge-warning">Suspended</span>';
                                                            }  

                                                            echo '<tr>';
                                                            echo '<td><small>'.$member->MemberID.'</small></td>';
                                                            echo '<td><small>'.$member->MemberName.'</small></td>';
                                                            echo '<td><small>'.$memberStatus.'</small></td>';
                                                            echo '<td><small>'.$member->MemberPhoneNumber.'</small></td>';
                                                            echo '<td><small>'.$member->DemNetID.'</small></td>';
                                                            echo '<td><small>'.$action.'</small></td>';
                                                            echo '</tr>';
                                                        }
                                                    ?>


                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>

<!---Group Member Modal-->
<div class="modal fade" id="group-member-modal" tabindex="-1" role="dialog" aria-labelledby="group-member-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="group-member-modal" ><span id="spn-group-exc-mode"></span> Group Member</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="group-member-form" class="form-horizontal db-submit" action="<?php echo base_url('entities/save-group-member'); ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="group-member-id" name="group-member-id" value="0">
                    <input type="hidden" name="entity-id" id="entity-id" value="<?php echo $entityId; ?>">
                    <input type="hidden" name="entity-type" id="entity-type" value="G">
                    <input type="hidden" name="exec-mode" id="exec-mode" value="add">
                    <div class="row">
                        <div class="col-md-4">
                            <h4>Member Photograph</h4>
                            <div class="row">
                                <div class="col-md12">
                                    <div class="btn-group btn-group-justified mb-2">
                                        <button id="start" class="btn btn-success btn-xs start-camera" title="Start Camera for Live Photo">Live Photo <i class="fas fa-camera"></i></button>
                                        <button id="snap" class="btn btn-success btn-xs hidden take-snapshot" title="Take Photo">Take Photo <i class="fas fa-camera"></i> </button>
                                        <button type="button" id="photo-upload" class="btn btn-primary btn-xs" title="Upload Photo from">Upload <i class="fas fa-upload"></i></button>
                                    </div>                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="takePhoto" class="hidden">
                                        <video id="video"></video>                            
                                        <canvas id="canvas"></canvas>  
                                    </div>
                                        <img id="photo" src="<?php echo base_url('assets/images/member-default.png'); ?>" alt="Captured Photo" />                                     
                                </div>
                            </div>
                            <div class="row">
                                <div id="photo-uploader" class="col-md-12 upload-photo hidden">
                                    <div class="form-group ">
                                        <div class="custom-file">
                                            <label for="member-photo" class="custom-file-label">Upload Photo</label>
                                            <input type="file" class="form-control form-control-sm custom-file" id="member-photo" name="member-photo" placeholder="MemberPhoto" accept="image/*" capture required />
                                        </div>
                                    </div>                                                                
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id-number">ID Number</label>
                                        <input type="text" class="form-control form-control-sm" id="id-number" name="id-number" placeholder="Member ID">
                                    </div>                                
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="group-member-first-name">First Name</label>
                                        <input type="text" class="form-control form-control-sm" id="group-member-first-name" name="group-member-first-name" placeholder="Member Name">
                                    </div> 
                                </div>                                 
                            </div>
                            <div class="row">                                    
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="group-member-last-name">Last Name</label>
                                        <input type="text" class="form-control form-control-sm" id="group-member-last-name" name="group-member-last-name" placeholder="Member Name">
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="group-member-gender">Gender</label>
                                        <select class="form-control form-control-sm" id="group-member-gender" name="group-member-gender">  
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="group-member-dob">Date of Birth</label>
                                        <input type="date" class="form-control form-control-sm" id="group-member-dob" name="group-member-dob">
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="group-member-address">Address</label>
                                        <input type="text" class="form-control form-control-sm" id="group-member-address" name="group-member-address" placeholder="Member Address">
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="group-member-status">Member Status</label>
                                        <select class="form-control form-control-sm" id="group-member-status" name="group-member-status">  
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                            <option value="Suspended">Suspended</option>
                                        </select>
                                    </div>   
                                </div> 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="group-member-telephone">Telephone</label>
                                        <input type="text" class="form-control form-control-sm" id="group-member-telephone" placeholder="Telephone" name="group-member-telephone">
                                    </div>                        
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="group-member-email">Email</label>
                                        <input type="text" class="form-control form-control-sm" id="group-member-email" placeholder="Email" name="group-member-email">
                                    </div> 
                                </div>  
                            </div>
                        </div>  
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm" id="save-group-member">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- End Modal -->

    <!--- member-status-modal --->
    <div class="modal fade" id="member-status-modal" tabindex="-1" role="dialog" aria-labelledby="member-status-modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="member-status-modalLabel">Member Status Change</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-3"><i class="fas fa-exclamation-triangle fa-3x text-danger"></i></div>
                        <div class="col-sm-9 text-danger" id="member-status-message">
                             <p>Are you sure you want to <strong> <span id="chg-sacco-member-status"></span> <span id="chg-member-name"></span></strong> ?</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <form id="member-status-form" class="db-submit" data-initmsg="Updating Member Status..." method="post" action="<?php echo base_url('entities/change-sacco-member-status');?>">
                            <input type="hidden" id="entity-type" name="entity-type" value="G">
                            <input type="hidden" id="member-id" name="member-id" value="0">
                            <input type="hidden" name="new-status" id="new-status">
                            <input type="hidden" name="entity-id" id="group-id">
                            <button type="submit" class="btn btn-primary btn-sm float-center">Yes</button>
                            <button type="button" class="btn btn-secondary btn-sm float-center" data-dismiss="modal">No</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--- End member-status-modal --->
