<?php echo view('template/partial-header'); ?>
<style>
/* General styling for video and photo containers */
video, #photo {
    border: 1px solid black;
    width: 100%;
    max-width: 230px;
    height: auto;
    max-height: 235px;
    margin-bottom: 10px;
}

/* Hide canvas elements */
canvas {
    display: none;
}

/* Hide elements with .hidden class */
.hidden {
    display: none;
}

/* Responsive styling */
@media (max-width: 768px) {
    video, #photo {
        max-width: 180px;
        max-height: 185px;
    }
}

@media (max-width: 480px) {
    video, #photo {
        max-width: 140px;
        max-height: 145px;
    }
}
</style>

<?php
    $entityName = ($entityType== "Sacco")? $sacco->SaccoName: $group->GroupName; ?>
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
                        <li class="breadcrumb-item"><a href="#"><?php echo $entityName;?></a></li>
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
                            <h3 class="card-title"><?php echo $entityName;?> Member List</h3>
                        </div>
                        <div class="card-body">
                            <?php if($entityType == "Sacco") { 
                              echo view('Entities/sacco-members');   
                             } 

                            if($entityType == 'Group'){ 
                                 echo view('Entities/group-Members');
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
 <?php echo view('template/partial-footer'); ?>