<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DigiFi: </title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url()?>/assets/plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url()?>/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url()?>/assets/css/adminlte.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>/assets/css/adminlte.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?php echo base_url()?>/assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">

  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="<?php echo base_url()?>/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url()?>/assets/plugins/daterangepicker/daterangepicker.css">

  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url()?>/assets/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Toastr -->
  <link rel="stylesheet" href="<?php echo base_url()?>/assets/plugins/toastr/toastr.min.css">
  <style>
    /* Flex container for search filter and length menu */
    .custom-controls-wrapper {
        display: flex;
        justify-content: space-between; /* Space between search and length menu */
        flex-wrap: wrap; /* Wrap elements on smaller screens */
        margin-bottom: 15px; /* Add spacing below the controls */
    }

    /* Styling for search filter and length menu */
    .custom-controls-wrapper .dataTables_filter,
    .custom-controls-wrapper .data-table-exportable_length {
        display: inline-block;
        margin: 0 10px; /* Adjust spacing between elements */
        width: auto; /* Adjust width as needed */
    }

    /* Responsiveness for mobile */
    @media (max-width: 768px) {
        .custom-controls-wrapper {
            flex-direction: column;
            align-items: center; /* Center elements on small screens */
        }

        .custom-controls-wrapper .dataTables_filter,
        .custom-controls-wrapper .data-table-exportable_length {
            width: 100%; /* Full width on small screens */
            margin: 10px 0; /* Margin for spacing */
        }
    }
</style>

</head>
<body class="hold-transition sidebar-mini layout-fixed" data-panel-auto-height-mode="height">

<div class="wrapper">