<?php
$projectSessionName="";
if (isset($this->session->userdata['codeKeyData'])) {
	$projectSessionName= ($this->session->userdata['codeKeyData']['codeKeyValue']);
}

if ($projectSessionName !="") {
	$email = ($this->session->userdata[$projectSessionName]['email']);
	$mobile = ($this->session->userdata[$projectSessionName]['mobile']);
	$id = ($this->session->userdata[$projectSessionName]['id']);
	$designation = ($this->session->userdata[$projectSessionName]['designation']);
	$des=explode(',',$designation);
	$des = array_map('trim', $des);
} else {
	redirect("UserAuthentication");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Smart | Distributor |Accountant</title>
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

    <!-- Colorpicker Css -->
    <link href="<?php echo base_url('assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css');?>" rel="stylesheet" />

    <!-- Dropzone Css -->
    <link href="<?php echo base_url('assets/plugins/dropzone/dropzone.css');?>" rel="stylesheet">

    <!-- Multi Select Css -->
      <link href="<?php echo base_url('assets/plugins/multi-select/css/multi-select.css');?>" rel="stylesheet">

    <!-- Bootstrap Spinner Css -->
    <link href="<?php echo base_url('assets/plugins/jquery-spinner/css/bootstrap-spinner.css');?>" rel="stylesheet">

    <!-- Bootstrap Tagsinput Css -->
    <link href="<?php echo base_url('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css');?>" rel="stylesheet">

    <!-- Bootstrap Select Css -->
    <link href="<?php echo base_url('assets/plugins/bootstrap-select/css/bootstrap-select.css');?>" rel="stylesheet" />

    <!-- noUISlider Css -->
    <link href="<?php echo base_url('assets/plugins/nouislider/nouislider.min.css');?>" rel="stylesheet" />
        <!-- Sweetalert Css -->
    <link href="<?php echo base_url('assets/plugins/sweetalert/sweetalert.css');?>" rel="stylesheet" />  

<style>
.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    padding: 12px 16px;
    z-index: 1;
}

.dropdown:hover .dropdown-content {
    display: block;
}
</style>
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

<body class="theme-red">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
    <!-- Top Bar -->

    <nav class="navbar" >
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>

                <a class="dropdown" href="<?php echo site_url('DashbordController');?>"  data-toggle="collapse" data-target=".nav-collapse">
                 <div class="dropdown">
                    <span class="navbar-brand">Dashbord 
                    </span>
                  </div> 
                </a>
    
                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                 <div class="dropdown">
                    <span class="navbar-brand">Cheque
                      <i class="fa fa-caret-down"></i>
                    </span>
                    <div class="dropdown-content">
                      <p><a href="<?php echo site_url('CashAndChequeController');?>">New Entry</a></p>         
                      <p><a href="<?php echo site_url('MakeBillwiseController');?>">Cheque Deposit slip</a></p> <p><a href="<?php echo site_url('MakeBillwiseController');?>">Cheque Reconciliation</a></p> 
                      <p><a href="<?php echo site_url('MakeBillwiseController');?>">Bounced Cheques</a></p>  
                      <p><a href="<?php echo site_url('MakeBillwiseController/DesktopBill');?>">Cheque Register</a></p> 
                    </div>
                  </div> 
                </a>
                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                 <div class="dropdown">
                    <span class="navbar-brand">Cash Book
                      <i class="fa fa-caret-down"></i>
                    </span>
                    <div class="dropdown-content">
                      <p><a href="<?php echo site_url('CashAndChequeController');?>">E-Vauchers</a></p>  
                      <p><a href="<?php echo site_url('CashAndChequeController');?>">Accounting Entries</a></p> 
                    </div>
                  </div> 
                </a>
                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                 <div class="dropdown">
                    <span class="navbar-brand">Sales return
                      <i class="fa fa-caret-down"></i>
                    </span>
                    <div class="dropdown-content">
                        <p><a href="<?php echo site_url('');?>">Sales Return Details</a></p>      
                        <p><a href="<?php echo site_url('');?>">USR Item Details</a></p>
                        <p><a href="<?php echo site_url('');?>">USR Bills</a></p>  
                    </div>
                  </div> 
                </a>
                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                 <div class="dropdown">
                    <span class="navbar-brand">Non Allocation Bill
                      <i class="fa fa-caret-down"></i>
                    </span>
                    <div class="dropdown-content">
                        <p><a href="<?php echo site_url('');?>">Tagged Bill</a></p>
                    </div>
                  </div> 
                </a>
                <a class="dropdown" href="<?php echo site_url('ReportsController');?>"  data-toggle="collapse" data-target=".nav-collapse">
                 <div class="dropdown">
                    <span class="navbar-brand">Reports 
                    </span>
                  </div> 
                </a>
                 <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                 <div class="dropdown">
                    <span class="navbar-brand">Accounting Entries
                      <i class="fa fa-caret-down"></i>
                    </span>
                    <div class="dropdown-content">
                        <p><a href="<?php echo site_url('CashAndChequeController');?>">Sale Return Entries</a></p>      
                        <p><a href="<?php echo site_url('CashAndChequeController');?>">Bounced Check</a></p> <p><a href="<?php echo site_url('CashAndChequeController');?>">Cash Receipts </a></p> 
                        <p><a href="<?php echo site_url('CashAndChequeController');?>">Cash Expenses</a></p>  
                    </div>
                  </div> 
                </a>
                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                 <div class="dropdown">
                    <span class="navbar-brand">Employee
                      <i class="fa fa-caret-down"></i>
                    </span>
                    <div class="dropdown-content">
                        <p><a href="<?php echo site_url('EmployeeController');?>">Ledger</a></p>      
                        <p><a href="<?php echo site_url('EmployeeController');?>">Salary slip</a></p>
                        <p><a href="<?php echo site_url('EmployeeController');?>">Employee Details</a></p>  
                    </div>
                  </div> 
                </a>
               
            <!-- 
                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                 <div class="dropdown">
                    <span class="navbar-brand">Billwise 
                      <i class="fa fa-caret-down"></i>
                    </span>
                    <div class="dropdown-content">
                      <p><a href="<?php echo site_url('ReviewBillwiseController');?>">Review billwise</a></p>                   
                      <p><a href="<?php echo site_url('MakeBillwiseController');?>">Make billwise</a></p>  
                    </div>
                  </div> 
                </a> -->
              <!--   <a class="navbar-brand" href="<?php echo site_url('ReviewBillwiseController');?>">Review billwise</a>
                <a class="navbar-brand" href="<?php echo site_url('MakeBillwiseController');?>">Make billwise</a> -->
     
               
               <!--  <a class="navbar-brand" href="<?php echo site_url('UsrController');?>"> USR</a>
                 <a class="dropdown" href="#">
                 <div class="dropdown">
                    <span class="navbar-brand">Bill 
                      <i class="fa fa-caret-down"></i>
                    </span>
                    <div class="dropdown-content"> 
                        <p>
                            <a href="<?php echo site_url('OutstandingBillController');?>">Outstanding bill</a>
                        </p>
                        <p>
                            <a href="<?php echo site_url('LostBillController');?>"> lost Bill</a>
                        </p> 
                        <p>
                            <a href="<?php echo site_url('LostBillController');?>"> lost cheques</a>
                        </p> 
                    </div>
                  </div> 
                </a> -->
               <!--  <a class="navbar-brand" href="<?php echo site_url('PenaltyController');?>"> Penalty</a>
                <a class="navbar-brand" href="<?php echo site_url('RouteController');?>">Route</a>
                <a class="navbar-brand" href="<?php echo site_url('EmployeeRelationController');?>">Relation</a> -->
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <!-- Call Search -->             
                    <li><a href="<?php echo site_url('UserAuthentication/logout');?>" class="navbar-brand" data-close="true"><i class="material-icons">vpn_key</i></a></li> 
                   <li><a href="javascript:void(0);" class="js-search" data-close="true"><i class="material-icons">search</i></a></li> 
                    <!-- #END# Call Search -->
                    <!-- Notifications -->
<!--                     <li class="dropdown ">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" >
                            <i class="material-icons">notifications</i>
                            <span class="label-count">7</span>
                        </a> -->
                       <!--  <ul class="dropdown-menu">
                            <li class="header">NOTIFICATIONS</li>
                            <li class="body">
                                <ul class="menu">
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-light-green">
                                                <i class="material-icons">person_add</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>12 new members joined</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 14 mins ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-cyan">
                                                <i class="material-icons">add_shopping_cart</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>4 sales made</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 22 mins ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-red">
                                                <i class="material-icons">delete_forever</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4><b>Nancy Doe</b> deleted account</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 3 hours ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-orange">
                                                <i class="material-icons">mode_edit</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4><b>Nancy</b> changed name</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 2 hours ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-blue-grey">
                                                <i class="material-icons">comment</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4><b>John</b> commented your post</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 4 hours ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-light-green">
                                                <i class="material-icons">cached</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4><b>John</b> updated status</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 3 hours ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-purple">
                                                <i class="material-icons">settings</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>Settings updated</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> Yesterday
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="javascript:void(0);">View All Notifications</a>
                            </li>
                        </ul>
                    </li> -->
                    <!-- #END# Notifications -->
                    <!-- Tasks -->
                  <!--   <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons">flag</i>
                            <span class="label-count">9</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">TASKS</li>
                            <li class="body">
                                <ul class="menu tasks">
                                    <li>
                                        <a href="javascript:void(0);">
                                            <h4>
                                                Footer display issue
                                                <small>32%</small>
                                            </h4>
                                            <div class="progress">
                                                <div class="progress-bar bg-pink" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 32%">
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <h4>
                                                Make new buttons
                                                <small>45%</small>
                                            </h4>
                                            <div class="progress">
                                                <div class="progress-bar bg-cyan" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <h4>
                                                Create new dashboard
                                                <small>54%</small>
                                            </h4>
                                            <div class="progress">
                                                <div class="progress-bar bg-teal" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 54%">
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <h4>
                                                Solve transition issue
                                                <small>65%</small>
                                            </h4>
                                            <div class="progress">
                                                <div class="progress-bar bg-orange" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 65%">
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <h4>
                                                Answer GitHub questions
                                                <small>92%</small>
                                            </h4>
                                            <div class="progress">
                                                <div class="progress-bar bg-purple" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 92%">
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="javascript:void(0);">View All Tasks</a>
                            </li>
                        </ul>
                    </li> -->
                    <!-- #END# Tasks -->
                    <!-- <li class="pull-right"><a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="material-icons">more_vert</i></a></li> -->
                </ul>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar" style="display: none;">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <img src="<?php echo base_url('assets/images/user.png');?>" width="48" height="48" alt="User" />
                </div>
                <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">John Doe</div>
                    <div class="email">john.doe@example.com</div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="<?php echo site_url('DashbordController');?>"><i class="material-icons">person</i>Profile</a></li>
                            <li role="seperator" class="divider"></li>
                            <!--<li><a href="<?php echo site_url('DashbordController');?>"><i class="material-icons">group</i>Followers</a></li>-->
                            <!--<li><a href="<?php echo site_url('DashbordController');?>"><i class="material-icons">shopping_cart</i>Sales</a></li>-->
                            <!--<li><a href="<?php echo site_url('DashbordController');?>"><i class="material-icons">favorite</i>Likes</a></li>-->
                            <!--<li role="seperator" class="divider"></li>-->
                            <li><a href="<?php echo site_url(); ?>/UserAuthentication/logout"><i class="material-icons">input</i>Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu" >
                <ul class="list">
                    <li class="header">MAIN NAVIGATION</li>
                    <li class="active">
                        <a href="<?php echo site_url('DashbordController');?>">
                            <i class="material-icons">home</i>
                            <span>Home</span>
                        </a>
                    </li>
                    <!--  <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">sort</i>
                            <span> Forms </span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="<?php echo site_url('AllocationByManagerController');?>">Allocation By Manager </a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('SignedBillsController');?>">Signed Bills </a>
                            </li>
                        </ul>
                    </li> -->
                    <li>
                        <a href="<?php echo site_url('AllocationByManagerController');?>">
                            <i class="material-icons">vpn_key</i>
                            <span>Allocation By Manager</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo site_url('EmployeeController');?>">
                            <i class="material-icons">group</i>
                            <span>Employee</span>
                        </a>
                    </li>
                    <li>
                      <a href="<?php echo site_url('ReviewBillwiseController');?>">
                            <i class="material-icons">visibility</i>
                         <span>Review billwise</span>
                        </a>
                    </li>
                    <li>
                      <a href="<?php echo site_url('MakeBillwiseController');?>">
                            <i class="material-icons">transit_enterexit</i>
                         <span>Make billwise</span>
                        </a>
                    </li>
                    <li>
                      <a href="<?php echo site_url('CashAndChequeController');?>">
                            <i class="material-icons">money</i>
                         <span>Cheque details</span>
                        </a>
                    </li>
                    <li>
                      <a href="<?php echo site_url('SalesReturnController');?>">
                            <i class="material-icons">keyboard_return</i>
                         <span>Sales return</span>
                        </a>
                    </li>
                    <li>
                      <a href="<?php echo site_url('UsrController');?>">
                            <i class="material-icons">gamepad</i>
                         <span>USR</span>
                        </a>
                    </li>
                    <li>
                      <a href="<?php echo site_url('OutstandingBillController');?>">
                            <i class="material-icons">view_list</i>
                         <span>outstanding bill</span>
                        </a>
                    </li>
                    <li>
                      <a href="<?php echo site_url('LostBillController');?>">
                            <i class="material-icons">check_box</i>
                         <span>lost bills/lost cheques</span>
                        </a>
                    </li>
                    <li>
                      <a href="<?php echo site_url('LostBillController');?>">
                            <i class="material-icons">assessment</i>
                         <span>Reports</span>
                        </a>
                    </li>                   
                     <li>
                        <a href="<?php echo site_url('PenaltyController');?>">
                            <i class="material-icons">check_circle</i>
                            <span>Penalty</span>
                        </a>
                    </li>
                     <li>
                        <a href="<?php echo site_url('RouteController');?>">
                            <i class="material-icons">sync</i>
                            <span>Route</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">sort</i>
                            <span> Relation </span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="<?php echo site_url('EmployeeRelationController');?>">Employee Relation</a>
                            </li>
                            <!--<li>-->
                            <!--    <a href="<?php echo site_url('PermissionRelationController');?>">Permission Relation</a>-->
                            <!--</li>-->
                        </ul>
                    </li>
                   <!--  <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">check_circle</i>
                            <span> Cheque Penalty </span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="<?php echo site_url('EmployeeRelationController');?>"> Show Cheque Penalty</a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('PermissionRelationController');?>">Cheque Penalty </a>
                            </li>
                        </ul>
                    </li>  -->
                  <!--   <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">wc</i>
                            <span> Employee Penalty </span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="<?php echo site_url('EmployeeRelationController');?>"> Show Employee Penalty</a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('PermissionRelationController');?>">Cheque Penalty </a>
                            </li>
                        </ul>
                    </li> -->
                   <!--  <li>
                        <a href="<?php echo base_url('assets/pages/typography.html');?>">
                            <i class="material-icons">text_fields</i>
                            <span>Typography</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('assets/pages/helper-classes.html');?>">
                            <i class="material-icons">layers</i>
                            <span>Helper Classes</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">widgets</i>
                            <span>Widgets</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="javascript:void(0);" class="menu-toggle">
                                    <span>Cards</span>
                                </a>
                                <ul class="ml-menu">
                                    <li>
                                        <a href="<?php echo base_url('assets/pages/widgets/cards/basic.html');?>">Basic</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url('assets/pages/widgets/cards/colored.html');?>">Colored</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url('assets/pages/widgets/cards/no-header.html');?>">No Header</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="menu-toggle">
                                    <span>Infobox</span>
                                </a>
                                <ul class="ml-menu">
                                    <li>
                                        <a href="<?php echo base_url('assets/pages/widgets/infobox/infobox-1.html');?>">Infobox-1</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url('assets/pages/widgets/infobox/infobox-2.html');?>">Infobox-2</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url('assets/pages/widgets/infobox/infobox-3.html');?>">Infobox-3</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url('assets/pages/widgets/infobox/infobox-4.html');?>">Infobox-4</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url('assets/pages/widgets/infobox/infobox-5.html');?>">Infobox-5</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">swap_calls</i>
                            <span>User Interface (UI)</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="<?php echo base_url('assets/pages/ui/alerts.html');?>">Alerts</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/ui/animations.html');?>">Animations</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/ui/badges.html');?>">Badges</a>
                            </li>

                            <li>
                                <a href="<?php echo base_url('assets/pages/ui/breadcrumbs.html');?>">Breadcrumbs</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/ui/breadcrumbs.html');?>">Buttons</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/ui/collapse.html');?>">Collapse</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/ui/colors.html');?>">Colors</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/ui/dialogs.html');?>">Dialogs</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/ui/icons.html');?>">Icons</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/ui/labels.html');?>">Labels</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/ui/list-group.html');?>">List Group</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/ui/media-object.html');?>">Media Object</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/ui/modals.html');?>">Modals</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/ui/notifications.html');?>">Notifications</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/ui/pagination.html');?>">Pagination</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/ui/preloaders.html');?>">Preloaders</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/papages/ui/progressbars.html');?>">Progress Bars</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/ui/range-sliders.html');?>">Range Sliders</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/ui/sortable-nestable.html');?>">Sortable & Nestable</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/ui/tabs.html');?>">Tabs</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/ui/thumbnails.html');?>">Thumbnails</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/ui/tooltips-popovers.html');?>">Tooltips & Popovers</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/ui/waves.html');?>">Waves</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">assignment</i>
                            <span>Forms</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="<?php echo base_url('assets/pages/forms/basic-form-elements.html');?>">Basic Form Elements</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/forms/advanced-form-elements.html');?>">Advanced Form Elements</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/forms/form-examples.html');?>">Form Examples</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/forms/form-validation.html');?>">Form Validation</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/forms/form-wizard.html');?>">Form Wizard</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/forms/editors.html');?>">Editors</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">view_list</i>
                            <span>Tables</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="<?php echo base_url('assets/pages/tables/normal-tables.html');?>">Normal Tables</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/tables/jquery-datatable.html');?>">Jquery Datatables</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/tables/editable-table.html');?>">Editable Tables</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">perm_media</i>
                            <span>Medias</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="<?php echo base_url('assets/pages/medias/image-gallery.html');?>">Image Gallery</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/medias/carousel.html');?>">Carousel</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">pie_chart</i>
                            <span>Charts</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="<?php echo base_url('assets/pages/charts/morris.html');?>">Morris</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/charts/flot.html');?>">Flot</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/charts/chartjs.html');?>">ChartJS</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/charts/sparkline.html');?>">Sparkline</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/charts/jquery-knob.html');?>">Jquery Knob</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">content_copy</i>
                            <span>Example Pages</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="<?php echo base_url('assets/pages/examples/sign-in.html');?>">Sign In</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/examples/sign-up.html');?>">Sign Up</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/examples/forgot-password.html');?>">Forgot Password</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/examples/blank.html');?>">Blank Page</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/examples/404.html');?>">404 - Not Found</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/examples/500.html');?>">500 - Server Error</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">map</i>
                            <span>Maps</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="<?php echo base_url('assets/pages/maps/google.html');?>">Google Map</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/maps/yandex.html');?>">YandexMap</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('assets/pages/maps/jvectormap.html');?>">jVectorMap</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">trending_down</i>
                            <span>Multi Level Menu</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="javascript:void(0);">
                                    <span>Menu Item</span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">
                                    <span>Menu Item - 2</span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="menu-toggle">
                                    <span>Level - 2</span>
                                </a>
                                <ul class="ml-menu">
                                    <li>
                                        <a href="javascript:void(0);">
                                            <span>Menu Item</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="menu-toggle">
                                            <span>Level - 3</span>
                                        </a>
                                        <ul class="ml-menu">
                                            <li>
                                                <a href="javascript:void(0);">
                                                    <span>Level - 4</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="<?php echo base_url('assets/pages/changelogs.html');?>">
                            <i class="material-icons">update</i>
                            <span>Changelogs</span>
                        </a>
                    </li>
                    <li class="header">LABELS</li>
                    <li>
                        <a href="javascript:void(0);">
                            <i class="material-icons col-red">donut_large</i>
                            <span>Important</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);">
                            <i class="material-icons col-amber">donut_large</i>
                            <span>Warning</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);">
                            <i class="material-icons col-light-blue">donut_large</i>
                            <span>Information</span>
                        </a>
                    </li> -->
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                  <!--  2018 - 2019  -->
                </div>
                <div class="version">
                    <!-- <b>Version: </b> 1.0.5 -->
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
        <!-- Right Sidebar -->
        <aside id="rightsidebar" class="right-sidebar">
            <ul class="nav nav-tabs tab-nav-right" role="tablist">
                <li role="presentation" class="active"><a href="#skins" data-toggle="tab">SKINS</a></li>
                <li role="presentation"><a href="#settings" data-toggle="tab">SETTINGS</a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active in active" id="skins">
                    <ul class="demo-choose-skin">
                        <li data-theme="red" class="active">
                            <div class="red"></div>
                            <span>Red</span>
                        </li>
                        <li data-theme="pink">
                            <div class="pink"></div>
                            <span>Pink</span>
                        </li>
                        <li data-theme="purple">
                            <div class="purple"></div>
                            <span>Purple</span>
                        </li>
                        <li data-theme="deep-purple">
                            <div class="deep-purple"></div>
                            <span>Deep Purple</span>
                        </li>
                        <li data-theme="indigo">
                            <div class="indigo"></div>
                            <span>Indigo</span>
                        </li>
                        <li data-theme="blue">
                            <div class="blue"></div>
                            <span>Blue</span>
                        </li>
                        <li data-theme="light-blue">
                            <div class="light-blue"></div>
                            <span>Light Blue</span>
                        </li>
                        <li data-theme="cyan">
                            <div class="cyan"></div>
                            <span>Cyan</span>
                        </li>
                        <li data-theme="teal">
                            <div class="teal"></div>
                            <span>Teal</span>
                        </li>
                        <li data-theme="green">
                            <div class="green"></div>
                            <span>Green</span>
                        </li>
                        <li data-theme="light-green">
                            <div class="light-green"></div>
                            <span>Light Green</span>
                        </li>
                        <li data-theme="lime">
                            <div class="lime"></div>
                            <span>Lime</span>
                        </li>
                        <li data-theme="yellow">
                            <div class="yellow"></div>
                            <span>Yellow</span>
                        </li>
                        <li data-theme="amber">
                            <div class="amber"></div>
                            <span>Amber</span>
                        </li>
                        <li data-theme="orange">
                            <div class="orange"></div>
                            <span>Orange</span>
                        </li>
                        <li data-theme="deep-orange">
                            <div class="deep-orange"></div>
                            <span>Deep Orange</span>
                        </li>
                        <li data-theme="brown">
                            <div class="brown"></div>
                            <span>Brown</span>
                        </li>
                        <li data-theme="grey">
                            <div class="grey"></div>
                            <span>Grey</span>
                        </li>
                        <li data-theme="blue-grey">
                            <div class="blue-grey"></div>
                            <span>Blue Grey</span>
                        </li>
                        <li data-theme="black">
                            <div class="black"></div>
                            <span>Black</span>
                        </li>
                    </ul>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="settings">
                    <div class="demo-settings">
                        <p>GENERAL SETTINGS</p>
                        <ul class="setting-list">
                            <li>
                                <span>Report Panel Usage</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                            <li>
                                <span>Email Redirect</span>
                                <div class="switch">
                                    <label><input type="checkbox"><span class="lever"></span></label>
                                </div>
                            </li>
                        </ul>
                        <p>SYSTEM SETTINGS</p>
                        <ul class="setting-list">
                            <li>
                                <span>Notifications</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                            <li>
                                <span>Auto Updates</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                        </ul>
                        <p>ACCOUNT SETTINGS</p>
                        <ul class="setting-list">
                            <li>
                                <span>Offline</span>
                                <div class="switch">
                                    <label><input type="checkbox"><span class="lever"></span></label>
                                </div>
                            </li>
                            <li>
                                <span>Location Permission</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </aside>
        <!-- #END# Right Sidebar -->
    </section>