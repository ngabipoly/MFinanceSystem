<?php echo view('template\partial-header'); ?>
  <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <?php 
           // $users=json_decode(json_encode($user));
           $sex = ['F'=>'Female','M'=>'Male'];
           $maritalStatus = ['S'=>'Single', 'M'=>'Married', 'D'=>'Divorced', 'WF'=>'Widow', 'WM'=>'Widower']
        ?>

        <section class="content">
            <div class="container-fluid">
                <div class="row mt-2">
                    <div class="col-sm-12">
                        <div class="card card-info">
                            <div class="card-header" >
                               <strong>Customer Management</strong> 
                            </div>
                            <div class="card-body">
                            <?php
                                if (session()->getFlashdata('success')) { ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Success!</strong> <?php echo session()->getFlashdata('success'); ?>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                <?php }
                            ?>
                            <a href="<?php echo base_url('customer/customer-registration');?>" id="new-customer" class="btn btn-xs btn-success">Add Customer <i class="fas fa-user-plus"></i></a>
                            
                                <div class="table-responsive mt-2">
                                    <table class="table data-table-simple table-bordered table-striped table-hover table-sm" width="100%">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th><strong><small>Customer ID</small></strong></th>
                                                <th><strong><small>Names</small></strong></th>
                                                <th><strong><small>Type</small></strong></th>
                                                <th><strong><small>Gender</small></strong></th> 
                                                <th><strong><small>Age</small></strong></th> 
                                                <th><strong><small>Marital Status</small></strong></th>
                                                <th><strong><small>Occupation</small></strong></th>
                                                <th><strong><small>Action</small></strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!$customers){
                                                # code...
                                            }else{
                                            foreach ($customers as $customer) { ?>
                                                <tr>
                                                    <td>
                                                        <small>
                                                            <?php echo $customer->IDNumber; ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <small>
                                                            <?php echo $customer->Names; ?>
                                                            </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo $customer->IdType; ?> </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo $sex[$customer->Gender]; ?> </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo $customer->Age; ?> </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo $maritalStatus[$customer->MaritalStatus]; ?> </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo $customer->Occupation; ?> </small>
                                                    </td>
                                                    <td>

                                                        <a href="<?php echo base_url('customer/customer-registration/edit/'.$customer->ClientID);?>" class="btn btn-xs btn-primary edit-client" data-url="<?php echo base_url('customer/customer-registration');?>"  data-editid="<?php echo $customer->ClientID; ?>" title="Edit <?php echo $customer->Names;?>"><i class="fas fa-edit"></i></a>
                                                        <?php 
                                                            if ($customer->Deleted==0) {?>                                                               
                                                            <a href="#" data-toggle="modal" data-target="#confirm-delete-modal" class="btn btn-xs btn-danger delete-client" data-deleteid="<?php echo $customer->ClientID; ?>" title="Delete <?php echo $customer->Names;?>?"><i class="fas fa-trash"></i></a>                                                         
                                                             <?php  } ?>
                                                        

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
    </div>

    <!-- Confirm Customer Delete Modal -->
    <div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-labelledby="confirm-delete-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">                    
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form id="customer-delete-form" class="form-horizontal db-submit" action="<?php echo base_url('customer/delete-customer'); ?>" method="post" data-initmsg="Deleting Customer..." >
                        <div class="form-group">
                            <input type="hidden" name="delete-customer-id" id="delete-customer-id">
                            <div class="card-body">
                                <div class="callout callout-danger">
                                    <h5>Warning!</h5>
                                    <p>Are you sure you want to delete Customer <span id="spn-delete-customer-name"></span>?</p>
                                </div>
                                    <button type="button" class="btn btn-sm btn-secondary float-right" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-sm btn-danger float-right mr-2" id="delete-customer">Delete</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php echo view('template\partial-footer'); ?>