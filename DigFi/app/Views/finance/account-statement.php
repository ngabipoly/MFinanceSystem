<?php 
    echo view('template\partial-header');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Account Statement</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="invoice p-3 mb-3">
                        <!-- title row -->
                        <div class="row">
                            <div class="col-12">
                                <h4>
                                    <i class="fas fa-globe"></i> <strong><?php echo $organization->OrgName; ?></strong>
                                    <small class="float-right">Date: <?php echo date('Y-m-d'); ?></small>
                                </h4>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- info row -->
                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                                <address>
                                    <?php echo $organization->orgAddress; ?><br>
                                    Phone: <?php echo $organization->orgPhoneNumber; ?><br>
                                    Email: <?php echo $organization->orgEmail; ?><br>
                                    Website: <?php echo $organization->orgWebsite; ?>
                                </address>
                            </div>
                            <!-- /.col -->
                             <?php
                              if($orgType != 'Organization'){ ?>
                                    <div class="col-sm-4 invoice-col">
                                        <address>
                                            <strong><?php echo $entityData['name']; ?> </strong><br>
                                            <?php echo $entityData['address']; ?><br>
                                            <?php echo 'Phone: ' . $entityData['phone']; ?><br>
                                            <?php echo 'Phone: ' . $entityData['email']; ?>
                                        </address>
                                    </div>
                            <?php } ?>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col">
                                <b>Account Statement</b><br>
                                <?php if($orgType != 'Organization'){ ?><br>
                                <b>Account ID:</b> <?php echo $entityData['account_id']; ?><br> <?php } ?>
                                <?php echo (isset($periodStart) && isset($periodEnd)) ? "<b>Period:</b>  $periodStart  to $periodEnd ": ''; ?><br>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- Table row -->
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered table-striped table-hover table-sm">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th><small>Date</small></th>
                                            <th><small>Transaction #</small></th>
                                            <th><small>Type</small></th>
                                            <th><small>Description</small></th>
                                            <th><small>Amount</small></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($transactions)) {
                                            foreach ($transactions as $transaction) {
                                                echo '<tr>';
                                                echo '<td><small>' . htmlspecialchars($transaction->TransactionDate) . '</small></td>';
                                                echo '<td><small>' . htmlspecialchars($transaction->TransactionNumber) . '</small></td>';
                                                echo '<td><small>' . htmlspecialchars($transaction->TransactionTypeName) . '</small></td>';
                                                echo '<td><small>' . htmlspecialchars($transaction->TransactionDescription) . '</small></td>';
                                                echo '<td><small>' . htmlspecialchars(number_format($transaction->TransactionAmount, 2)). '</small></td>';
                                                echo '</tr>';
                                            }
                                            $totoalAmount = array_sum(array_column($transactions, 'TransactionAmount'));
                                            echo '<tr><td colspan="4"><small><b>Total:</b></small></td><td><small><b>' . number_format($totoalAmount, 2) . '</b></small></td></tr>';
                                        } else {
                                            echo '<tr><td colspan="3">No transactions available.</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- this row will not appear when printing -->
                        <div class="row no-print">
                            <div class="col-12">
                                <button onclick="window.print();" class="btn btn-default"><i class="fas fa-print"></i> Print</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.invoice -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php echo view('template\partial-footer'); ?>
