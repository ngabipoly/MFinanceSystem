<?php echo view('template\partial-header'); ?>
  <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <?php 
           // $users=json_decode(json_encode($user));
        ?>

        <section class="content">
            <div class="container-fluid">
                <div class="row mt-2">
                    <div class="col-sm-12">
                        <div class="card card-info">
                            <div class="card-header" >
                               <strong>Loan Applications</strong> 
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
                            <a href="<?php echo base_url('loans/apply');?>" id="loan-apply" class="btn btn-xs btn-success">New Application <i class="fas fa-plus-circle"></i></a>
                            
                                <div class="table-responsive mt-2">
                                    <table class="table data-table-simple table-bordered table-striped table-hover table-sm" width="100%">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th><strong><small>Application Number</small></strong></th>
                                                <th><strong><small>Customer Name</small></strong></th>
                                                <th><strong><small>Product</small></strong></th>
                                                <th><strong><small>Loan Amount</small></strong></th> 
                                                <th><strong><small>Period(Months)</small></strong></th>
                                                <th><strong><small>Interest Rate</small></strong></th>
                                                <th><strong><small>Application Date</small></strong></th>
                                                <th><strong><small>Status</small></strong></th>
                                                <th><strong><small>Action</small></strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!$loanApplications){
                                              
                                            }else{
                                            foreach ($loanApplications as $application) { ?>
                                                <tr>
                                                    <td>
                                                        <small>
                                                            <?php echo $application->LoanID; ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <small>
                                                            <?php echo $application->CustomerName; ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo $application->ProductName; ?> </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo number_format($application->PrincipalAmount,2); ?> </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo  $application->TermMonths; ?> </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo $application->InterestRate; ?> </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo $application->ApplicationDate; ?> </small>
                                                    </td>
                                                    <td>
                                                       <small> <?php echo $application->Status; ?> </small>
                                                    </td>
                                                    <td>

                                                        <a href="<?php echo base_url('loans/application/edit/'.$application->LoanID);?>" class="btn btn-xs btn-primary edit-client"  data-editid="<?php echo $application->LoanID; ?>" title="Edit <?php echo $application->LoanID.' - '.$application->CustomerName;?>"><i class="fas fa-edit"></i></a>
                                                        <?php 
                                                            if ($application->Status=='Pending') {                                                             
                                                                echo "<a href='#' data-toggle='modal' data-target='#confirm-delete-modal' class='btn btn-xs btn-danger delete-account' data-deleteid='$application->LoanID' title='Reject $application->CustomerName'><i class='fas fa-times-circle'></i></a> ";
                                                                echo "<a href='#' class='btn btn-xs btn-success approve-account' data-approveid='$application->LoanID' title='Approve $application->CustomerName'><i class='fas fa-check-circle'></i></a>";
                                                            
                                                               }    
                                                            ?>
                                                        

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

    <!-- Reject loan Modal -->
    <div class="modal fade" id="confirm-reject-modal" tabindex="-1" role="dialog" aria-labelledby="confirm-reject-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">                    
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form id="application-reject-form" class="form-horizontal db-submit" action="<?php echo base_url('loans/reject-application'); ?>" method="post" data-initmsg="Recording Rejection..." >
                        <div class="form-group">
                            <input type="hidden" name="reject-application-id" id="reject-application-id">
                            <div class="card-body">
                                <div class="callout callout-danger">
                                    <h5>Warning!</h5>
                                    <p>Are you sure you want to Reject Application <span id="spn-reject-loan-application"></span>?</p>
                                </div>
                                    <button type="button" class="btn btn-sm btn-secondary float-right" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-sm btn-danger float-right mr-2" id="reject-application">Reject Application <i class="fas fa-times-circle"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve loan Modal -->
    <div class="modal fade" id="confirm-approve-modal" tabindex="-1" role="dialog" aria-labelledby="confirm-approve-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">                    
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form id="application-approve-form" class="form-horizontal db-submit" action="<?php echo base_url('loans/approve-application'); ?>" method="post" data-initmsg="Approving Loan..." >
                        <div class="form-group">
                            <input type="hidden" name="approve-application-id" id="approve-application-id">
                            <div class="card-body">
                                <div class="callout callout-danger">
                                    <h5>Warning!</h5>
                                    <p>Are you sure you want to Approve Loan <span id="spn-approve-loan-application"></span>?</p>
                                </div>
                                    <button type="button" class="btn btn-sm btn-secondary float-right" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-sm btn-success float-right mr-2" id="approve-application">Approve Loan <i class="fas fa-check-circle"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


<?php echo view('template\partial-footer'); ?>