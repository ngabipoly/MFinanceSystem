<?php
    echo view('template/partial-header');
?>
<!-- Invoice page based on the adminlte template -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Transaction Report
            <small>Invoice</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Finance</a></li>
            <li class="active">Transaction Report</li>
        </ol>
    </section>

    <section class="invoice">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> Company Name
                    <small class="pull-right">Date: <?php echo date('d/m/Y'); ?></small>
                </h2>
            </div>
        </div>

        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                From
                <address>
                    <strong><?php echo $organization->OrgName; ?></strong><br>
                    <?php echo $organization->orgAddress; ?><br>
                    Phone: <?php echo $organization->orgPhoneNumber; ?><br>
                    Email: <?php echo $organization->orgEmail; ?><br>
                    Website: <?php echo $organization->orgWebsite; ?>
                </address>
            </div>
            <div class="col-sm-4 invoice-col">
                To
                <address>
                    <strong><?php echo $report->TransActorName;?></strong><br>
                    5678 Second St<br>
                    City, State, ZIP<br>
                    Phone: (555) 987-6543<br>
                    Email: customer@example.com
                </address>
            </div>
            <div class="col-sm-4 invoice-col">
                <b>Invoice <?php echo $report->TransactionNumber; ?></b><br>
                <br>
                <b>Transaction Date:</b> <?php echo $report->TransactionDate; ?> <br>
                <b>Account:</b> 968-34567
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                    <tbody>
                        <?php foreach($report as $item){?>
                        <tr>
                            <td><?php echo $item->TransactionTypeName;?></td>
                            <td><?php echo $item->TransactionDescription;?></td>
                            <td><?php echo $item->TransactionAmount;?></td></td>
                        </tr><?php
                        } 
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6">
                <p class="lead">Payment Methods:</p>
                <img src="../../dist/img/credit/visa.png" alt="Visa">
                <img src="../../dist/img/credit/mastercard.png" alt="Mastercard">
                <img src="../../dist/img/credit/american-express.png" alt="American Express">
                <img src="../../dist/img/credit/paypal2.png" alt="Paypal">

                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                    Payment instructions or additional information can be placed here.
                </p>
            </div>
            <div class="col-xs-6">
                <p class="lead">Amount Due 2/22/2024</p>

                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th style="width:50%">Subtotal:</th>
                            <td>$364.50</td>
                        </tr>
                        <tr>
                            <th>Tax (9.3%)</th>
                            <td>$33.92</td>
                        </tr>
                        <tr>
                            <th>Shipping:</th>
                            <td>$5.80</td>
                        </tr>
                        <tr>
                            <th>Total:</th>
                            <td>$404.22</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="row no-print">
            <div class="col-xs-12">
                <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Print</button>
                <button class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment</button>
                <button class="btn btn-primary pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> Generate PDF</button>
            </div>
        </div>
    </section>
</div>
 <?php
    echo view('template/partial-footer');
?>