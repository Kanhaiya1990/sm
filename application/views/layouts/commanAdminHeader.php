<!DOCTYPE html>
<?php
// $des=array();
// $id=0;
// $nameForDistributor="";
// $projectSessionName="";
// if (isset($this->session->userdata['codeKeyData'])) {
// 	$projectSessionName= ($this->session->userdata['codeKeyData']['codeKeyValue']);
// }

// if ($projectSessionName !="") {
// 	$nameForDistributor=($this->session->userdata[$projectSessionName]['nameForDistributor']);
// 	$email = ($this->session->userdata[$projectSessionName]['email']);
// 	$mobile = ($this->session->userdata[$projectSessionName]['mobile']);
// 	$id = ($this->session->userdata[$projectSessionName]['id']);
// 	$designation = ($this->session->userdata[$projectSessionName]['designation']);
// 	$des=explode(',',$designation);
// 	$des = array_map('trim', $des);
// } else {
// 	redirect("UserAuthentication");
// }

?>

<html>
<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title><?php echo ""; ?></title>
	<link rel="icon" href="<?php echo base_url('assets/uploads/favicon.ico');?>" type="image/x-icon">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url('assets/plugins/bootstrap/css/bootstrap.css');?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/plugins/node-waves/waves.css');?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/plugins/animate-css/animate.css');?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/plugins/morrisjs/morris.css');?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css');?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/style.css');?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/themes/all-themes.css');?>" rel="stylesheet" />
	<link href=" https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css');?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/plugins/dropzone/dropzone.css');?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/plugins/multi-select/css/multi-select.css');?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/plugins/jquery-spinner/css/bootstrap-spinner.css');?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css');?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/plugins/bootstrap-select/css/bootstrap-select.css');?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/plugins/nouislider/nouislider.min.css');?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/plugins/sweetalert/sweetalert.css');?>" rel="stylesheet" />  
    <link rel="stylesheet" href="<?php echo base_url('assets/colorbox-master/example1/colorbox.css');?>">
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/toaster/toastr.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/toaster/toastr.min.js"></script>
    <script type="text/javascript">
        function showSuccess(str){
          toastr.success(str);
      }
      function showError(str){
          toastr.error(str);
      }
  </script>
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
        $.fn.dataTable.ext.errMode = 'none';
		$('#Tbl').DataTable( {
		    stateSave: true,
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

<style type='text/css'>
.the_one {
  display: none;
}

.one_one:hover .the_one {
    display: block;
}
</style>

<style>

  .btn:hover, .btn:focus {
    font-weight: bolder;
    outline: 10px solid blanchedalmond;
    border-style: hidden;
    box-shadow: 0 0 2px 2px red;
  color: black;
  }
</style>
</head>

<body class="theme-red" style="fo">
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

    <nav class="navbar navbar-expand-sm bg-light navbar-light">
    	<div class="container-fluid">
    		<div class="navbar-header">
    			<a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
    			<a href="javascript:void(0);" class="bars"></a>

    			<a class="dropdown" href="<?php echo site_url('DashbordController');?>"  data-toggle="collapse" data-target=".nav-collapse">
    				<div class="dropdown" >
                        <span class="navbar-brand" style="font-size: 14px">Dashboard </span>
    				</div> 
    			</a>

                <a class="dropdown" href="<?php echo site_url('admin/CompanyController/officeDetails');?>"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown" >
                        <span class="navbar-brand" style="font-size: 14px">Office Details </span>
                    </div> 
                </a>

                <a class="dropdown" href="<?php echo site_url('admin/BillTransactionController');?>"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown" >
                        <span class="navbar-brand" style="font-size: 14px">Change Bill Transaction </span>
                    </div> 
                </a>
                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px"> Employee Details 
                            <i class="fa fa-caret-down"></i>
                        </span>
                        <div class="dropdown-content" role="menu">
                            <p><a href="<?php echo site_url('admin/EmployeeController');?>">Employees</a></p>
                            <p><a href="<?php echo site_url('admin/EmployeeController/universalEmployees');?>">Universal Employees</a></p>
                            <p><a href="<?php echo site_url('admin/EmployeeController/employeeeException');?>">Employees Exempt</a></p>
                            <p><a href="<?php echo site_url('admin/EmployeeController/salesmanLinking');?>">Salesman Linking</a></p>
                           
                            <p><a href="<?php echo site_url('admin/SettingsController/loginTime');?>">Login Time Setting</a></p>
                        </div>
                    </div> 
                </a>
                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px"> Penalties
                            <i class="fa fa-caret-down"></i>
                        </span>
                        <div class="dropdown-content" role="menu">
                            <p><a href="<?php echo site_url('admin/PenaltyController');?>">Cheque Bounce Penalties</a></p>
                            <p><a href="<?php echo site_url('admin/PenaltyController/otherPenalty');?>">Other Penalties</a></p>
                        </div>
                    </div> 
                </a>

                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px"> Income / Expense Categories
                            <i class="fa fa-caret-down"></i>
                        </span>
                        <div class="dropdown-content" role="menu">
                            <p><a href="<?php echo site_url('admin/CategoriesController/expensesCategory');?>">Expense Master</a></p>
                            <p><a href="<?php echo site_url('admin/CategoriesController/incomeCategory');?>">Income Master</a></p>
                        </div>
                    </div> 
                </a>

                <a class="dropdown" href="<?php echo site_url('admin/SettingsController/thresholdLimit');?>"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px"> Threshold Limits
                        </span>
                        <!-- <div class="dropdown-content" role="menu">
                            <p><a href="<?php echo site_url('admin/PenaltyController/expenseLimit');?>">Expense Limit</a></p>
                            <p><a href="<?php echo site_url('admin/SettingsController/billClearance');?>">Residual Bill Clearing Limit</a></p>
                            <p><a href="<?php echo site_url('admin/SettingsController/resendLimit');?>">Resend Limit</a></p>

                        </div> -->
                    </div> 
                </a>

                <a class="dropdown" href="<?php echo site_url('admin/SettingsController/highlightingDays');?>"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px"> Notification / Highlight Limit
                        </span>
                    </div> 
                </a>

                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px"> Other Options
                            <i class="fa fa-caret-down"></i>
                        </span>
                        <div class="dropdown-content" role="menu">
                            <p><a href="<?php echo site_url('admin/BankController');?>">Banks for Inward Cheques</a></p>
                            <p><a href="<?php echo site_url('admin/CompanyController');?>">Companies</a></p>
                            <!-- <p><a href="<?php echo site_url('admin/CompanyController/bouncedAdhocCheques');?>">Delete Adhoc Cheques</a></p> -->
                            <!-- <p><a href="<?php echo site_url('admin/RouteController');?>">Routes</a></p> -->
                            
                        </div>
                    </div> 
                </a>

                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px">
                            
                        </span>
                        <div class="dropdown-content">
                            <!-- <p><a href="javascript:void();" id="emp_pro_det_id" data-toggle="modal" data-target="#empProDetails" data-id="<?php echo $id;?>">Profile</a></p>       -->
                            <!-- <p><a href="javascript:void();" data-toggle="modal" data-target="#updateChangePasswordlimitModal">Change Password</a></p> -->
                            <p><a href="<?php echo site_url('UserAuthentication/adminLogout');?>"><i class="material-icons">exit_to_app</i>Logout</a></p>
                        </div>
                    </div> 
                </a>

            </div>
        </div>
    </nav>
    <section>
    	<aside id="leftsidebar" class="sidebar" style="display: none;">
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
    						<li><a href="<?php echo site_url(); ?>/UserAuthentication/logout"><i class="material-icons">input</i>Sign Out</a></li>
    					</ul>
    				</div>
    			</div>
    		</div>
    		<div class="menu" >
    			<ul class="list">
    				<li class="header">MAIN NAVIGATION</li>
    				<li class="active">
    					<a href="<?php echo site_url('DashbordController');?>">
    						<i class="material-icons">home</i>
    						<span>Home</span>
    					</a>
    				</li>
                    
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
                    		
                    		</ul>
                    	</li>
                </ul>
            </div>
            <div class="legal">
            	<div class="copyright">
            	</div>
            	<div class="version">
            	</div>
            </div>
        </aside>
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
    </section>
    <br><br>