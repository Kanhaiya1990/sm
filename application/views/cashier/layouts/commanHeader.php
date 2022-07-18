<?php
if (isset($this->session->userdata['logged_in'])) {
	$email = ($this->session->userdata['logged_in']['email']);
	$designation = ($this->session->userdata['logged_in']['designation']);
    $des=explode(',',$designation);
    // print_r($des);
     
} else {
	redirect("UserAuthentication/user_login_process");
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title>Smart | Distributor |Comman</title>
	<link rel="icon" href="<?php echo base_url('assets/favicon.ico');?>" type="image/x-icon">
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
    					<!-- <i class="navbar-brand material-icons" style="font-size: 30px">home</i>  -->
    				</div> 
    			</a>
                <?php if ((in_array('owner', $des))) { ?>
                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px">Approvals 
                            <i class="fa fa-caret-down"></i>
                        </span>
                        <div class="dropdown-content" role="menu">
                            <p><a href="<?php echo site_url('owner/ExpensesController/bankDepositDetails');?>">Cash Reconciliation</a></p>  
                            <p><a href="<?php echo site_url('manager/EmployeeController');?>">New Employee Addition</a></p>
                            <p><a href="<?php echo site_url('AllocationByManagerController/closedAllocations');?>">Cash Expenses</a></p>
                            <p><a href="<?php echo site_url('AllocationByManagerController/closedAllocations');?>">Office Adjustment Bill</a></p>
                            <p><a href="<?php echo site_url('AllocationByManagerController/closedAllocations');?>">Office Adjustment Allocations</a></p>
                            
                        </div>
                    </div> 
                </a>
            <?php } ?>
            <?php if((!in_array('accountant', $des))){ 
                        if (!in_array('operator', $des)){
                ?>
    			<a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
    				<div class="dropdown">
    					<span class="navbar-brand" style="font-size: 14px">Allocations 
    						<i class="fa fa-caret-down"></i>
    					</span>
    					<div class="dropdown-content">
    						<p><a href="<?php echo site_url('AllocationByManagerController/openAllocations');?>">Open Allocations</a></p>  
    						<p><a href="<?php echo site_url('AllocationByManagerController/closedAllocations');?>">Closed Allocations</a></p>
                            
    					</div>
    				</div> 
    			</a>
            <?php } 
                }
            ?>

            <?php if ((!in_array('accountant', $des))) { 
                        if (!in_array('operator', $des)){
                ?>
                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px">Outstanding Details
                            <i class="fa fa-caret-down"></i>
                        </span>
                        <div class="dropdown-content">
                            <p><a href="<?php echo site_url('SrController/resendBills');?>">Outstanding Bills</a></p> 
                            <p><a href="<?php echo site_url('SrController/resendBills');?>">Resend Bills</a></p> 
                            <p><a href="<?php echo site_url('SrController/lostBills');?>">Lost Bills</a></p>
                            <p><a href="<?php echo site_url('SrController/lostCheques');?>">Lost Cheques</a></p>
                            <p><a href="<?php echo site_url('SrController/unclearedNeft');?>">Pending NEFT</a></p>
                            <p><a href="<?php echo site_url('owner/OfficeAllocationController/billClearance');?>">Residual Bill Clearing</a></p>
                        </div>
                    </div> 
                </a>
            <?php } 
                }
            ?>

            <?php if ((!in_array('accountant', $des))) { 
                if (!in_array('operator', $des)){
                ?>
                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px">Retailer Mng
                            <i class="fa fa-caret-down"></i>
                        </span>
                        <div class="dropdown-content">
                            <p><a href="<?php echo site_url('SrController/resendBills');?>">Retailer Credit Days</a></p> 
                            <p><a href="<?php echo site_url('SrController/resendBills');?>">Retailer Details</a></p> 
                            <p><a href="<?php echo site_url('SrController/lostBills');?>">Multiple visit retailers</a></p>
                            <p><a href="<?php echo site_url('SrController/lostCheques');?>">Multiple bill retailers</a></p>
                            <p><a href="<?php echo site_url('SrController/unclearedNeft');?>">Outlet Summary</a></p>
                        </div>
                    </div> 
                </a>
            <?php } 
                }
            ?>
            <?php if ((!in_array('accountant', $des))) { 
                if (!in_array('operator', $des)){
                ?>
                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px">Bill Mng
                            <i class="fa fa-caret-down"></i>
                        </span>
                        <div class="dropdown-content">
                           <p><a href="<?php echo site_url('AdHocController/adhocBills');?>">Add New Bills</a></p> 
                            <p><a href="<?php echo site_url('AdHocController/adhocDeliveryBills');?>">Direct Delivery Bills</a></p>  
                            <p><a href="<?php echo site_url('AdHocController/officeAdjustmentBills');?>">Office Adjustment Bills</a></p>
                            <p><a href="<?php echo site_url('AdHocController/officeAdjustmentBills');?>">Other Adjustment Bills</a></p>
                            <p><a href="<?php echo site_url('AdHocController/officeAdjustmentBills');?>">CD Bills</a></p>
                            <p><a href="<?php echo site_url('AdHocController/officeAdjustmentBills');?>">Never Allocated Bills</a></p>
                            <p><a href="<?php echo site_url('AdHocController/officeAdjustmentBills');?>">Billwise Retailer Sale</a></p>
                        </div>
                    </div> 
                </a>
            <?php } 
                }
            ?>    
                <?php if (in_array('accountant', $des)) { 
                    if (!in_array('operator', $des)){
                ?>
    			<a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
    				<div class="dropdown">
    					<span class="navbar-brand" style="font-size: 14px">Cheque/NEFT
    						<i class="fa fa-caret-down"></i>
    					</span>
    					<div class="dropdown-content">
    						<p><a href="<?php echo site_url('CashAndChequeController/');?>">New Entry</a></p>    
    						<p><a href="<?php echo site_url('CashAndChequeController/DesktopBill');?>">Cheque Deposit Slip</a></p>
                            <p><a href="<?php echo site_url('CashAndChequeController/ChequeReconcilation');?>">Cheque Reconciliation</a></p> 
                              <p><a href="<?php echo site_url('CashAndChequeController/neftReconcilation');?>">NEFT Reconciliation</a></p> 
    						<p><a href="<?php echo site_url('CashAndChequeController/BounceCheques');?>">Bounced Cheques</a></p>  
    						<p><a href="<?php echo site_url('CashAndChequeController/ChequeRegister');?>">Cheque Register</a></p> 
                            <p><a href="<?php echo site_url('CashAndChequeController/NeftRegister');?>">NEFT Register</a></p> 

                            <p><a href="<?php echo site_url('CashAndChequeController/chequeDepositSlipTransactions');?>">Past Cheque Deposits</a></p>
                          
    					</div>
    				</div> 
    			</a>
                <?php } 
                    }
                ?>
                <?php if (in_array('accountant', $des)) { 
                        if (!in_array('operator', $des)){
                ?>
                      
    			<a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
    				<div class="dropdown">
    					<span class="navbar-brand" style="font-size: 14px">Cash Book
    						<i class="fa fa-caret-down"></i>
    					</span>
    					<div class="dropdown-content">
                            
                            <p><a href="<?php echo site_url('manager/CashBookController/IncomeExpense');?>"> Day Book
                            </a></p>  
                             <p><a href="<?php echo site_url('manager/CashBookController/pastDay');?>">Past Day Book
                            </a></p>
                            <p><a href="<?php echo site_url('manager/CashBookController/pastDay');?>">Period Day Book
                            </a></p>
    						<p><a href="<?php echo site_url('accountant/AccountantController/incomeExpense');?>">Income Expense Report</a></p> 
                            <p><a href="<?php echo site_url('accountant/AccountantController/periodIncomeExpense');?>">Period Income Expense Report</a></p> 
    					</div>
    				</div> 
    			</a>
                <?php } 
            }
                ?>
                <?php if (!in_array('accountant', $des)) { 
                    if (!in_array('operator', $des)){
                ?>
    			<a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
    				<div class="dropdown">
    					<span class="navbar-brand" style="font-size: 14px">Delivery Slip
    						<i class="fa fa-caret-down"></i>
    					</span>
    					<div class="dropdown-content">
    						<p><a href="<?php echo site_url('DeliverySlipController');?>">New Transaction</a></p> 
                            <p><a href="<?php echo site_url('DeliverySlipController');?>">Slip Details</a></p>
                            <p><a href="<?php echo site_url('DeliverySlipController');?>">Product Master</a></p>
                            <p><a href="<?php echo site_url('DeliverySlipController');?>">Retailer Master</a></p>  
                            <p><a href="<?php echo site_url('DeliverySlipController');?>">Stock Status</a></p>  
                            <p><a href="<?php echo site_url('DeliverySlipController');?>">Pending Billing in System</a></p>     
    						<p>
                                <a href="<?php echo site_url('DeliverySlipController/OutstandingBill');?>">Outstanding Bill</a>
                            </p> 
                            <p><a href="<?php echo site_url('DeliverySlipController/RetailerwiseDetails');?>">Retailer Outstanding </a></p>
                           
    						<p><a href="<?php echo site_url('DeliverySlipController/Products');?>">View Products</a></p>
    						<p><a href="<?php echo site_url('RetailerController/');?>">View Retailers</a></p>
    						<p><a href="<?php echo site_url('DeliverySlipController/BillwiseDetails');?>">Billwise Details</a></p>
    					
                            <div class="one_one">Reports
                              <div class="the_one">
                                   <p><a href="<?php echo site_url('DeliverySlipController/salesmanSaleDetail');?>">Salesman Stock Report</a></p>  
    				    	       <p><a href="<?php echo site_url('DeliverySlipController/retailerAccountDetail');?>">Retailer Account Statement</a></p>    
                                </div>
                            </div>
                            
    					</div>
    				</div> 
    			</a>
                <?php } 
                    }
                ?>
                <?php if (!in_array('accountant', $des)) { 
                        if (!in_array('operator', $des)){
                ?>
    				 <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px">Sales Return
                            <i class="fa fa-caret-down"></i>
                        </span>
                        <div class="dropdown-content">
                            <p><a href="<?php echo site_url('manager/SrCheckController/pendingSr');?>">Allocation Wise SR </a></p> 
                            <p><a href="<?php echo site_url('manager/SrCheckController/pendingSr');?>">Historical SR </a></p>     
                            <p><a href="<?php echo site_url('manager/SrCheckController/pendingSr');?>">Current SR </a></p>
                        </div>
                    </div> 
                </a>
                <?php } 
                    }
                ?>

                <?php if (!in_array('accountant', $des)) { 
                        if (in_array('operator', $des)){
                ?>
                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px">SR/FSR Details 
                            <i class="fa fa-caret-down"></i>
                        </span>
                        <div class="dropdown-content">
                            <p><a href="<?php echo site_url('operator/OperatorController/pendingSr');?>">Pending SR </a></p>
                            <p><a href="<?php echo site_url('operator/OperatorController/srPrint');?>">SR Print </a></p>
                             
                        </div>
                    </div> 
                </a>
<?php } 
                    }
                ?>


    			<?php if (!in_array('accountant', $des)) { 

                    if (!in_array('operator', $des)){
                ?>
    			<a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
    				<div class="dropdown">
    					<span class="navbar-brand" style="font-size: 14px">Employee Mng
    						<i class="fa fa-caret-down"></i>
    					</span>
    					<div class="dropdown-content">
    						<p><a href="<?php echo site_url('manager/EmployeeController/employeeLedger');?>">Employee Ledger</a></p>      
    						<p><a href="<?php echo site_url('manager/EmployeeController');?>">Employee Details</a></p>
                            <p><a href="<?php echo site_url('manager/EmployeeController');?>">Employee Clearance</a></p>  
    					</div>
    				</div> 
    			</a>
                <?php } 
                    }
                ?>
    			<!-- <a class="dropdown" href="<?php echo site_url('ReportsController');?>"  data-toggle="collapse" data-target=".nav-collapse">
    				<div class="dropdown">
    					<span class="navbar-brand" style="font-size: 14px">Reports 
    					</span>
    				</div> 
    			</a> -->
                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px">Reports
                            <i class="fa fa-caret-down"></i>
                        </span>
                        <div class="dropdown-content">
                            <p><a href="<?php echo site_url('manager/EmployeeController/employeeLedger');?>">Collection Report</a></p>      
                            <p><a href="<?php echo site_url('manager/EmployeeController');?>">Bill wise Product wise Report</a></p>
                            <p><a href="<?php echo site_url('manager/EmployeeController');?>">Delivery Slip Retailer Account Statement</a></p>  
                            <p><a href="<?php echo site_url('manager/EmployeeController');?>">Sales Return Report</a></p> 
                            <p><a href="<?php echo site_url('manager/EmployeeController');?>">Bounced Cheque Report</a></p>  
                            <p><a href="<?php echo site_url('manager/EmployeeController');?>">Employee Debit Report</a></p>  
                            <p><a href="<?php echo site_url('manager/EmployeeController');?>">Audit Report</a></p>   
                        </div>
                    </div> 
                </a>

                <a class="dropdown" href="<?php echo site_url('UserAuthentication/logout');?>"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown" >
                        <span class="navbar-brand" style="font-size: 14px">Logout </span>
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