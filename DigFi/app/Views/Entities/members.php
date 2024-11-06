<?php echo view('template/partial-header'); ?>
<style>

video, #photo {
            border: 1px solid black;
            width: 230px;
            max-height: 235px;
            max-width: 230px;
            margin-bottom: 10px;
        }
        canvas {
            display: none;
        }
.hidden {
    display: none;
}
</style>

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
                        <li class="breadcrumb-item active">Members</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title"><?php echo $group->GroupName;?></h3>
                        </div>
                        <div class="card-body">
                            <?php if($entityType == "Sacco") { ?>
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
                                                        
                                                        if($member->memberStatus != "Suspended" && $member->Deleted != "1") {
                                                            $action .= '<a href="#" title="Edit Member" data-toggle="modal" data-target="#sacco-member-modal" data-mode="edit" data-member-id="'.$member->SaccoMemberID.'" data-sacco-id="'.$member->SaccoID.'" data-first-name="'.$member->MemberFirstName.'" data-last-name="'.$member->MemberLastName.'" data-email="'.$member->MemberEmail.'" data-phone="'.$member->MemeberPhoneNumber.'" data-status="'.$member->MemberStatus.'" data-id-type="'.$member->MemberIDType.'" data-id-number="'.$member->MemberIDNumber.'"  class="btn btn-xs btn-primary"><i class="fas fa-edit"></i></a>  <a href="#" title="Suspend Member" class="btn btn-xs btn-warning suspend-member member-status-change" data-first-name="'.$member->MemberFirstName.'" data-last-name="'.$member->MemberLastName.'" data-sacco-id="'.$member->SaccoID.'"  data-member-id="'.$member->SaccoMemberID.'"  data-new-status="Suspended" data-toggle="modal" data-target="#member-status-modal"><i class="fas fa-ban"></i></a>';
                                                        }
        
                                                        if($member->MemberStatus != "Active" && $member->Deleted != "1") {
                                                            $action .= ' <a href="#" title="Activate Member" class="btn btn-xs btn-success enable-member member-status-change" data-new-status="Active" data-member-id="'.$member->SaccoMemberID.'" data-sacco-id="'.$member->SaccoID.'" data-first-name="'.$member->MemberFirstName.'" data-last-name="'.$member->MemberLastName.'"data-toggle="modal" data-target="#member-status-modal"><i class="fas fa-check"></i></a>';
                                                        }
        
                                                        if($member->Deleted != "1") {
                                                            $action .= ' <a href="#" title="Delete Member" data-toggle="modal" data-target="#member-status-modal" data-member-id="'.$member->SaccoMemberID.'" data-sacco-id="'.$member->SaccoID.'" data-first-name="'.$member->MemberFirstName.'" data-last-name="'.$member->MemberLastName.' data-new-status="Deleted"  class="btn btn-xs btn-danger delete-member member-status-change" ><i class="fas fa-trash"></i></a>';
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
                                                        echo '<td><small>'.$member->MemberID.'</small></td>';
                                                        echo '<td><small>'.$member->FirstName.'</small></td>';
                                                        echo '<td><small>'.$member->LastName.'</small></td>';
                                                        echo '<td><small>'.$member->Email.'</small></td>';
                                                        echo '<td><small>'.$member->Phone.'</small></td>';
                                                        echo '<td><small>'.$member->MemberStatus.'</small></td>';
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
                            <?php } 
                            if($entityType == 'Group'){ ?>
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
                                                            $action = '';
                                                            if($member->GroupMemberStatus != "Active" && $member->Deleted != "1") {
                                                                $action .= "<a class='btn btn-xs btn-success' title='Activate Member' data-toggle='modal' data-target='#member-status-modal' data-member-id='".$member->GroupMemberID."'><i class='fas fa-check'></i></a>";
                                                            }

                                                            if($member->GroupMemberStatus == "Active" && $member->Deleted != "1") {
                                                                $action .= "<a class='btn btn-xs btn-warning' title='Suspend Member' data-toggle='modal' data-target='#member-status-modal' data-member-id='".$member->GroupMemberID."'><i class='fas fa-ban'></i></a>";
                                                            }

                                                            if($member->Deleted != "1") {
                                                                $action .= " <a class='btn btn-xs btn-primary' title='Edit Member' data-toggle='modal' data-target='#group-member-modal' data-mode='edit' data-member-id='".$member->GroupMemberID."'><i class='fas fa-edit'></i></a> <a class='btn btn-xs btn-danger' title='Delete Member' data-toggle='modal' data-target='#member-status-modal' data-member-id='".$member->GroupMemberID."'><i class='fas fa-trash'></i></a>";
                                                            }

                                                            
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
                           <?php }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
</div>
<!---Group Member Modal-->
<div class="modal fade" id="group-member-modal" tabindex="-1" role="dialog" aria-labelledby="group-member-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="group-member-modal">Add Group Member</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="group-member-form" class="form-horizontal db-submit" action="<?php echo base_url('entities/save-group-member'); ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="group-member-id" value="0">
                    <input type="hidden" name="entity-id" id="entity-id" value="<?php echo $entityId; ?>">
                    <input type="hidden" name="entity-type" id="entity-type" value="G">
                    <input type="hidden" name="exec-mode" id="exec-mode" value="add">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="btn-group btn-group-justified mb-2">
                                <button id="start" class="btn btn-success btn-xs start-camera" title="Start Camera for Live Photo">Live Photo <i class="fas fa-camera"></i></button>
                                <button id="snap" class="btn btn-success btn-xs hidden take-snapshot" title="Take Photo">Take Photo <i class="fas fa-camera"></i> </button>
                                <button type="button" id="photo-upload" class="btn btn-primary btn-xs" title="Upload Photo from">Upload <i class="fas fa-upload"></i></button>
                            </div>
                            <div id="takePhoto" class="hidden">
                                <video id="video"></video>                            
                                <canvas id="canvas"></canvas>  
                            </div>
                                <img id="photo" src="<?php echo base_url('assets/images/member-default.png'); ?>" alt="Captured Photo" /> 
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
                                <div id="photo-uploader" class="col-md-6 upload-photo hidden">
                                    <div class="form-group ">
                                        <div class="custom-file">
                                            <label for="member-photo" class="">Upload Photo</label>
                                            <input type="file" class="form-control form-control-sm custom-file" id="member-photo" name="member-photo" placeholder="MemberPhoto" accept="image/*" capture required />
                                        </div>
                                    </div>                                                                
                                </div>
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

<!--- Suspend Member Modal --->
<div class="modal fade" id="suspend-member-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Suspend Member</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="suspend-member-form" method="post" action="<?php echo base_url('customer/suspend-member');?>">
          <div class="form-group">
            <h4>Why would you like to Suspend this member?</h4>
            <input type="hidden" id="suspend-member-id" name="suspend-member-id" value="0">
            <label for="suspend-member-reason">Reason</label>
            <textarea class="form-control" id="suspend-member-reason" name="suspend-member-reason" rows="3"></textarea>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!--- End Suspend Member Modal --->

<!--- Activate Member Modal --->
<div class="modal fade" id="activate-member-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Activate Member</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="activate-member-form" method="post" action="<?php echo base_url('customer/activate-member');?>">
          <div class="form-group">
            <h4>Why would you like to Activate this member?</h4>
            <input type="hidden" id="activate-member-id" name="activate-member-id" value="0">
            <label for="suspend-member-reason">Reason</label>
            <textarea class="form-control" id="activate-member-reason" name="activate-member-reason" rows="3"></textarea>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!--- End Activate Member Modal --->

<!--- Delete Member Modal --->
<div class="modal fade" id="delete-member-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete Member</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="delete-member-form" method="post" action="<?php echo base_url('customer/delete-member');?>">
          <div class="form-group">
            <h4>Why would you like to Delete this member?</h4>
            <input type="hidden" id="delete-member-id" name="delete-member-id" value="0">
            <label for="suspend-member-reason">Reason</label>
            <textarea class="form-control" id="delete-member-reason" name="delete-member-reason" rows="3"></textarea>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!--- End Delete Member Modal --->


<!-- /.content-wrapper -->
 <?php echo view('template/partial-footer'); ?>