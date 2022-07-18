<?php
if (isset($this->session->userdata['logged_in'])) {
$email = ($this->session->userdata['logged_in']['email']);
} else {
  redirect("UserAuthentication/user_login_process");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Smart | Distributor</title>
    <!-- Favicon-->
    <link rel="icon" href="<?php echo base_url('assets/favicon.ico');?>" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="<?php echo base_url('assets/plugins/bootstrap/css/bootstrap.css');?>" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?php echo base_url('assets/plugins/node-waves/waves.css');?>" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="<?php echo base_url('assets/plugins/animate-css/animate.css');?>" rel="stylesheet" />
    
    <!-- Morris Chart Css-->
    <link href="<?php echo base_url('assets/plugins/morrisjs/morris.css');?>" rel="stylesheet" />

    <!-- JQuery DataTable Css -->
    <link href="<?php echo base_url('assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css');?>" rel="stylesheet">
    <!-- Custom Css -->
    <link href="<?php echo base_url('assets/css/style.css');?>" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?php echo base_url('assets/css/themes/all-themes.css');?>" rel="stylesheet" />
    <link href=" https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
      <!-- Bootstrap Select Css -->
    <link href="<?php echo base_url('assets/plugins/bootstrap-select/css/bootstrap-select.css');?>" rel="stylesheet" />
      <!-- Multi Select Css -->
    <link href="<?php echo base_url('assets/plugins/multi-select/css/multi-select.css');?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/4.0.2/bootstrap-material-design.css" rel="stylesheet">
   
        <script>
        $(document).ready(function() {
            $('#Tbl').DataTable( {
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            } );
        } );
    </script>
</head>