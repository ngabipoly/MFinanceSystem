   
                                <div class="row">
                                    <div class="col-md-12">
                                        <button id="add-member" class="btn btn-xs btn-primary rounded circle" data-toggle="modal" data-target="#sacco-member-modal">Add Member <i class="fas fa-user-plus"></i></button>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover table-sm table-striped data-table">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th><small>ID Number</small></th>
                                                        <th><small>First Name</small></th>
                                                        <th><small>Last Name</small></th>
                                                        <th><small>Email</small></th>
                                                        <th><small>Phone</small></th>
                                                        <th><small>Status</small></th>
                                                        <th><small>Action</small></th>
                                                    </tr>
                                                </thead>
                                                <?php
                                                    foreach($members as $member) {
                                                        $action = '';
                                                        $suspend = ''; 
                                                        $activate='';
                                                        $delete='';
                                                        $edit='';
                                                        $memberStatus = '';
                                                        
                                                        if($member->MemberStatus != "Suspended" && $member->Deleted != "1") {
                                                            $action .= '<a href="#" title="View Member" data-toggle="modal" data-target="#sacco-member-modal" data-mode="view" data-member-id="'.$member->SaccoMemberID.'" data-entity-id="'.$member->SaccoID.'" data-link="'.base_url("entities/member").'"  class="btn btn-xs btn-success get-sacco-member"><i class="fas fa-eye"></i></a> <a href="#" title="Edit Member" data-toggle="modal" data-target="#sacco-member-modal" data-mode="edit" data-member-id="'.$member->SaccoMemberID.'" data-entity-id="'.$member->SaccoID.'" data-link="'.base_url("entities/member").'"  class="btn btn-xs btn-primary get-sacco-member edit-member"><i class="fas fa-edit"></i></a>  <a href="#" title="Suspend Member" class="btn btn-xs btn-warning suspend-member member-status-change" data-first-name="'.$member->MemberFirstName.'" data-last-name="'.$member->MemberLastName.'" data-sacco-id="'.$member->SaccoID.'"  data-member-id="'.$member->SaccoMemberID.'"  data-new-status="Suspended" data-toggle="modal" data-target="#member-status-modal"><i class="fas fa-ban"></i></a>';
                                                        }
        
                                                        if($member->MemberStatus != "Active" && $member->Deleted != "1") {
                                                            $action .= ' <a href="#" title="Activate Member" class="btn btn-xs btn-success enable-member member-status-change" data-new-status="Active" data-status-verb="Activate" data-member-id="'.$member->SaccoMemberID.'" data-sacco-id="'.$member->SaccoID.'" data-first-name="'.$member->MemberFirstName.'" data-last-name="'.$member->MemberLastName.'"data-toggle="modal" data-target="#member-status-modal"><i class="fas fa-check"></i></a>';
                                                        }
        
                                                        if($member->Deleted != "1") {
                                                            $action .= ' <a href="#" title="Delete Member" data-toggle="modal" data-target="#member-status-modal" data-member-id="'.$member->SaccoMemberID.'" data-sacco-id="'.$member->SaccoID.'" data-first-name="'.$member->MemberFirstName.'" data-last-name="'.$member->MemberLastName.'" data-new-status="Deleted"  class="btn btn-xs btn-danger delete-member member-status-change" ><i class="fas fa-trash"></i></a>';
                                                        }
        
                                                        
                                                        if($member->Deleted == "1") {
                                                            $memberStatus = '<span class="badge badge-danger">Deleted</span>';
                                                        }else if($member->MemberStatus == "Active") {
                                                            $memberStatus = '<span class="badge badge-success">Active</span>';
                                                        }else if($member->MemberStatus == "Inactive") {
                                                            $memberStatus = '<span class="badge badge-info">Inactive</span>';
                                                        }else if($member->MemberStatus == "Suspended") {
                                                            $memberStatus = '<span class="badge badge-warning">Suspended</span>';
                                                        }  

                                                        echo '<tr>';
                                                        echo '<td><small>'.$member->MemberIDNumber.'</small></td>';
                                                        echo '<td><small>'.$member->MemberFirstName.'</small></td>';
                                                        echo '<td><small>'.$member->MemberLastName.'</small></td>';
                                                        echo '<td><small>'.$member->MemberEmail.'</small></td>';
                                                        echo '<td><small>'.$member->MemberPhoneNumber.'</small></td>';
                                                        echo '<td><small>'.$memberStatus.'</small></td>';
                                                        echo '<td><small>'.$action.'</small></td>';
                                                        echo '</tr>';
                                                    }
                                                ?>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

    <!-- Sacco Member Modal-->
    <div class="modal fade" id="sacco-member-modal" tabindex="-1" role="dialog" aria-labelledby="sacco-member-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="sacco-member-modal" ><span id="spn-sacco-exc-mode"></span> Sacco Member</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="sacco-member-form" class="form-horizontal db-submit" action="<?php echo base_url('entities/save-sacco-member'); ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" id="sacco-member-id" name="sacco-member-id" value="0">
                        <input type="hidden" name="entity-id" id="entity-id" value="<?php echo $entityId; ?>">
                        <input type="hidden" name="entity-type" id="entity-type" value="S">
                        <input type="hidden" name="exec-mode" id="exec-mode" value="add">
                        <div class="row">
                            <div class="col-md-4">
                                <h6>Member Photograph</h6>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="btn-group btn-group-justified mb-2">
                                            <button id="start" class="btn btn-success btn-xs start-camera" title="Start Camera for Live Photo">Live Photo <i class="fas fa-camera"></i></button>
                                            <button id="snap" class="btn btn-success btn-xs hidden take-snapshot" title="Take Photo">Take Photo <i class="fas fa-camera"></i> </button>
                                            <button id="stop" class="btn btn-danger btn-xs hidden stop-camera" title="Stop Camera">Stop Camera <i class="fas fa-camera"></i></button>
                                            <button type="button" id="photo-upload" class="btn btn-primary btn-xs" title="Upload Photo from">Upload <i class="fas fa-upload"></i></button>
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
                                                    <input type="file" class="form-control form-control-sm custom-file" id="member-photo" name="member-photo" placeholder="MemberPhoto" accept="image/*" capture/>
                                                </div>
                                            </div>                                                                
                                        </div>
                                    </div>                                    
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h6>Member Details</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="first-name">First Name</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-sm" id="first-name" name="first-name" placeholder="First Name" required="required">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="last-name">Last Name</label>
                                            <div class="input-group">
                                                <input type="text" name="last-name" class="form-control form-control-sm" id="last-name" placeholder="Last Name" required="required">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="member-id-number" class="form-label">ID Number</label>
                                        <div class="input-group mb-2">     
                                            <input type="text" class="form-control form-control-sm" id="member-id-number" name="member-id-number" placeholder="Enter Member ID Number" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="id-type">Id Type</label>
                                            <div class="input-group">
                                                <select name="id-type" id="id-type" class="form-control form-control-sm">
                                                    <option value="">Select Id Type</option>
                                                    <?php
                                                        foreach ($idTypes as $idType) {
                                                           echo "<option value='".$idType['IDTypeID']."'>".$idType['IDTypeName']."</option>";
                                                        } ?>
                                                </select>
                                            </div>                                            
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <label for="gender">Gender</label>
                                        <div class="input-group pl-4" >
                                            <div class="form-check form-check-inline radio radio-inline">
                                                <label for="radioMale" class="form-check-label mr-2"><i class="fas fa-male"></i> Male</label>
                                                <input type="radio" name="gender" id="radioMale" value="Male" class="form-check-input mr-2" checked="checked">
                                                <label for="radioFemale" class="form-check-label mr-2"><i class="fas fa-female"></i> Female</label>
                                                <input type="radio" name="gender" id="radioFemale" value="Female"  class="form-check-input mr-2">
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                        $minDate = date('Y-m-d',strtotime('-100 years'));
                                        $maxDate = date('Y-m-d', strtotime('-18 years'));
                                    ?>
                                    <div class="col-md-6">
                                        <label for="Date of Birth">Date of Birth</label>
                                        <div class="input-group">
                                            <input type="date" class="form-control form-control-sm" placeholder="yyyy-mm-dd" id="date-of-birth" name="date-of-birth" min="<?php echo $minDate; ?>" max="<?php echo $maxDate; ?>" required></label>
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                            </div>
                                        </div>                                        
                                    </div>
                                    <div class="col-md-6">
                                        <label for="member-phone-number">Mobile</label>
                                        <div class="input-group">
                                            <input type="tel" class="form-control form-control-sm" id="member-phone-number" name="member-phone-number" placeholder="Member Telephone Number" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            </div>
                                        </div>                   
                                    </div>     
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="member-address">Physical Address</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-sm" id="member-address" name="member-address" placeholder="Enter Member Address" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                </div>
                                            </div>                                    
                                        </div>
                                    </div>   
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="member-email">Email Address</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-sm" id="member-email"name="member-email" placeholder="Enter Member Email" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fas fa-at"></i></span>
                                                </div>
                                            </div>            
                                        </div>
                                    </div>    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="member-occupation">Occupation</label>
                                            <div class="input-group mb-2">
                                                <input type="text" class="form-control form-control-sm" id="member-occupation" name="member-occupation" placeholder="Enter Member Occupation" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                                                </div>
                                            </div>              
                                        </div>
                                    </div>      
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="member-nok-name">Next of Kin</label>
                                    <div class="input-group">                                    
                                        <input type="text" class="form-control form-control-sm" id="member-nok-name" name="member-nok-name" placeholder="Enter Next of Kin Name" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="member-nok-relationship">Relation</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" id="nok-relationship" name="nok-relationship" placeholder="Enter Next of Kin Relationship" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="member-nok-phone-number">Kin Phone Number</label>
                                    <div class="input-group">
                                        <input type="tel" class="form-control form-control-sm" id="member-nok-phone-number" name="member-nok-phone-number" placeholder="Enter Next of Kin Phone Number" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                    </div>     
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="member-nok-address">Kin Physical Address</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" id="member-nok-address" name="member-nok-address" placeholder="Enter Next of Kin Address" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                                        </div>
                                    </div>      
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button"  class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="save-sacco-member" class="btn btn-sm btn-primary">Save Member</button>
                </div>
            </div>
        </div>
    </div>
    <!--- End Add Member Modal --->
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
                            <input type="hidden" id="sacco-entity" name="entity-type" value="S">
                            <input type="hidden" id="member-id" name="member-id" value="0">
                            <input type="hidden" name="new-status" id="new-status">
                            <input type="hidden" name="entity-id" id="sacco-id">
                            <button type="submit" class="btn btn-primary btn-sm float-center">Yes</button>
                            <button type="button" class="btn btn-secondary btn-sm float-center" data-dismiss="modal">No</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--- End member-status-modal --->