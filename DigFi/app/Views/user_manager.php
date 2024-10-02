<?php echo view('template\partial-header'); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
<?php 
$users=json_decode(json_encode($users));
?>
    <section class="content">
        <div class="container-fluid">
            <div class="row mt-2">
                <div class="col-sm-12">
                    <div class="card card-warning">
                        <div class="card-header" >
                           <strong> Management</strong> 
                        </div>
                        <div class="card-body">
                        <a href="#" id="new-user" data-toggle="modal" data-target="#user-modal" class="btn btn-xs btn-success">Add User</a>
                        
                            <div class="table-responsive mt-2">
                                <table class="table data-table-simple table-bordered table-striped table-hover table-sm" width="100%">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th><strong><small>User Photo</small></strong></th>
                                            <th><strong><small>First Name</small></strong></th>
                                            <th><strong><small>Last Name</small></strong></th>
                                            <th><strong><small>Email</small></strong></th>
                                            <th><strong><small>Role</small></strong></th>
                                            <th><strong><small>Creation Date</small></strong></th>
                                            <th><strong><small>Status</small></strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if (!$users){
                                                # code...
                                            }else{ 
                                            foreach ($users as $user) {?>
                                                <tr>
                                                    <td>
                                                        <img src="<?php echo base_url('assets/images/user.png'); ?>" class="img-circle elevation-2" width="35" height="35" alt="User Image">
                                                    </td>
                                                    <td>
                                                        <?php echo $user->FirstName; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo  $user->LastName; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $user->Email; ?>
                                                    </td>
                                                    <td>
                                                        <?php //echo $user->RoleName; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $user->CreatedAt; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $user->AccountStatus; ?>
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

    <?php echo view('template\partial-footer'); ?>
    
    <div class="modal fade" id="user-modal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="user-mgr-h">New User</h4>
              <button type="button"  class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php echo base_url('user-manager/save');?>" id="frm-user-mgt"  method="post" class="form-horizontal" enctype="multipart/form-data" data-initmsg="Saving User...">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="uid" id="uid">
                <div class="modal-body">
                    <div class="form-group row">
                       <label for="pf-number" class="col-sm-3 col-form-label">UserPhoto</label>
                       <div class="col-sm-6 custom-file">
                             <input type="file" class="form-control custom-file-input form-control-sm" name="user-photo" id="user-photo" placeholder="User Photo" accept="image/*" capture required />  
                             <label class="custom-file-label" for="user-photo">Choose Photo</label> 
                       </div> 
                    </div>
                    <div class="form-group row">
                       <label for="first-name" class="col-sm-3 col-form-label">First Name</label>
                       <div class="col-sm-6">
                            <input type="text" class="form-control form-control-sm required" name="first-name" id="first-name" placeholder="First Name" required>
                       </div> 
                    </div>
                    
                    <div class="form-group row">
                       <label for="middle-name" class="col-sm-3 col-form-label">Middle Name</label>
                       <div class="col-sm-6">
                            <input type="text" class="form-control form-control-sm" name="middle-name" id="middle-name" placeholder="Middle Name">
                       </div>
                    </div>

                    <div class="form-group row">
                       <label for="last-name" class="col-sm-3 col-form-label">Last Name</label>
                       <div class="col-sm-6">
                            <input type="text" class="form-control form-control-sm" name="last-name" id="last-name" placeholder="Last Name" required>
                       </div> 
                    </div>    

                    <div class="form-group row">
                        <label for="user-phone" class="col-sm-3 col-form-label">User Mobile</label>
                        <div class="col-sm-6">
                            <input type="tel" class="form-control form-control-sm required" id="user-phone" name="user-phone" placeholder="User Mobile" required>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="user-username" class="col-sm-3 col-form-label">Username</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control form-control-sm required" id="user-username" name="user-username" placeholder="User Username" required>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="user-email" class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-6">
                            <input type="email" class="form-control form-control-sm required" id="user-email" name="user-email" placeholder="User Email" required>
                        </div>
                    </div>                                   

                    <div class="form-group row">
                        <label for="user-role" class="col-sm-3 col-form-label">Role</label>
                        <div class="col-sm-6">
                            <select class="form-control form-control-sm required select2" name="user-role" id="user-role" required>
                                
                                <?php 
                                    if ($roles) {
                                        foreach ($roles as $role) { ?>
                                            <option value="<?php echo $role['RoleID'];?>"><?php echo $role['RoleName'] ;?></option>
                                     <?php   }
                                    }
                                ?>
                            </select>
                        </div>                        
                    </div>
                    <div class="form-group row">
                        <label for="user-status" class="col-sm-3 col-form-label">User Status</label>
                        <div class="col-sm-6">
                            <select name="user-status" id="user-status" class="form-control form-control-sm required">
                                <option value="" selected>Set User Status</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Active">Active</option>
                                <option value="Suspended">Suspended</option>
                                <option value="Deactivated">Deactive</option>
                            </select>
                        </div>
                    </div>
                </div>  
                <div id="rtn-errors"></div>              
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm float-left" data-dismiss="modal">Close</button>
                    <button type="button" id="reset-pwd" class="btn btn-sm btn-danger">Reset Password</button>
                    <button type="submit" class="btn btn-primary btn-sm float-right">Save changes</button>
                    <button type="button" id="special-rights" class="btn btn-dark btn-sm float-right">Special Rights</button>
                    <button type="button" id="unlock" class="btn btn-success btn-sm float-right">Unlock</button>
                </div>            
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
