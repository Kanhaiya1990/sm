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
    <link href="<?php echo base_url('assets/css/custom.css');?>" rel="stylesheet">
    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?php echo base_url('assets/css/themes/all-themes.css');?>" rel="stylesheet" />
    <!--<link href=" https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />-->
    <link href="<?php echo base_url('assets/css/font-awesome.min.css');?>" rel="stylesheet" />
    
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
   <!--  <div class="page-loader-wrapper">
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
    </div> -->
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
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="<?php echo site_url('DashbordController');?>">Smart Distributor</a>
            </div>
          

            <div class="navbar-right">
                <a class="navbar-brand m-t-15" href="<?php echo site_url('UserAuthentication/adminLogout'); ?>">Logout</a>
            </div>
          
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <!-- <div class="user-info">
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
                            <li><a href="<?php echo site_url('UserAuthentication/changePassword');?>"><i class="material-icons">edit</i>Change Password</a></li>
                            
                            <li role="seperator" class="divider"></li>
                            <li><a href="<?php echo site_url(); ?>/UserAuthentication/logout"><i class="material-icons">input</i>Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </div> -->
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li class="header">MAIN NAVIGATION</li>
                     <li class="active">
                        <a href="<?php echo site_url('DashbordController');?>">
                            <i class="material-icons">home</i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">sort</i>
                            <span> Office Details </span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="<?php echo site_url('admin/CompanyController/bankDetailsKia');?>">
                                    <i class="material-icons">home</i>
                                    <span>Office Details</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                   
                
                     <li>
                        <a href="<?php echo site_url('admin/BillTransactionController');?>">
                            <i class="material-icons">home</i>
                            <span>Change Bill Transaction</span>
                        </a>
                    </li>
                  
                  
                     <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">sort</i>
                            <span> Employee Details </span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="<?php echo site_url('admin/EmployeeController');?>"><i class="material-icons">group</i><span>Employees</span></a>
                            </li>
                           <!--  <li>
                                <a href="<?php echo site_url('admin/EmployeeRelationController');?>"><i class="material-icons">group</i><span>Assign Employee Role</span></a>
                            </li> -->
                             <li>
                                <a href="<?php echo site_url('admin/EmployeeController/empCodeSetting');?>"><i class="material-icons">group</i><span>Employee Code</span></a>
                            </li>
                        </ul>
                    </li>

                     <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">sort</i>
                            <span>Penalties </span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="<?php echo site_url('admin/PenaltyController');?>"><i class="material-icons">check_circle</i><span>Cheque Bounce Penalties</span></a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('admin/PenaltyController/otherPenalty');?>"><i class="material-icons">check_circle</i><span>Other Penalties</span></a>
                            </li>
                        </ul>
                    </li>

                   
                   <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">sort</i>
                            <span>Income / Expense Categories </span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="<?php echo site_url('admin/CategoriesController/expensesCategory');?>"><i class="material-icons">check_circle</i><span>Expense Master</span></a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('admin/CategoriesController/incomeCategory');?>"><i class="material-icons">check_circle</i><span>Income Master</span></a>
                            </li>
                        </ul>
                    </li>


                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">sort</i>
                            <span>Threshold Limits </span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="<?php echo site_url('admin/PenaltyController/expenseLimit');?>">
                                    <i class="material-icons">sync</i>
                                    <span>Expense Limit</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('admin/SettingsController/billClearance');?>"><i class="material-icons">check_circle</i><span>Residual Bill Clearing Limit</span></a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('admin/SettingsController/resendLimit');?>"><i class="material-icons">check_circle</i><span>Resend Limit</span></a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">sort</i>
                            <span>Notification / Highlight Limit </span>
                        </a>
                        <ul class="ml-menu">
                          <!--   <li>
                                <a href="<?php echo site_url('admin/SettingsController/billsHighlightingDays');?>">
                                    <i class="material-icons">sync</i>
                                    <span>Bills</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('admin/SettingsController/retailersHighlightingDays');?>">
                                    <i class="material-icons">sync</i>
                                    <span>Retailers</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('admin/SettingsController/allocationsHighlightingDays');?>">
                                    <i class="material-icons">sync</i>
                                    <span>Allocations</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('admin/SettingsController/chequesHighlightingDays');?>">
                                    <i class="material-icons">sync</i>
                                    <span>Cheques</span>
                                </a>
                            </li> -->
                            <li>
                                <a href="<?php echo site_url('admin/SettingsController/highlightingDays');?>">
                                    <i class="material-icons">sync</i>
                                    <span>Highlighting Days</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                     <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">sort</i>
                            <span>Other Options </span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <!-- <a href="<?php echo site_url('admin/BillTransactionController/billSrManagement');?>">
                                    <i class="material-icons">sync</i>
                                    <span>Bills Serial No</span>
                                </a> -->
                                <a href="<?php echo site_url('admin/BankController');?>">
                                    <i class="material-icons">check_circle</i>
                                    <span>Banks for Inward Cheques</span>
                                </a>
                                <a href="<?php echo site_url('admin/CompanyController');?>">
                                    <i class="material-icons">check_circle</i>
                                    <span>Company</span>
                                </a>
                                <a href="<?php echo site_url('admin/CompanyController/bouncedAdhocCheques');?>">
                                    <i class="material-icons">sync</i>
                                    <span>Delete Adhoc Cheques</span>
                                </a>
                                <a href="<?php echo site_url('admin/RouteController');?>">
                                    <i class="material-icons">sync</i>
                                    <span>Routes</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                     
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