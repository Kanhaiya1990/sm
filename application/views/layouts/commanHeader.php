<!DOCTYPE html>
<?php

    $id=0;
    $nameForDistributor="";
    $projectSessionName="";
    if (isset($this->session->userdata['codeKeyData'])) {
        $projectSessionName= ($this->session->userdata['codeKeyData']['codeKeyValue']);
    }

    if ($projectSessionName !="") {
        $nameForDistributor=($this->session->userdata[$projectSessionName]['nameForDistributor']);
        $email = ($this->session->userdata[$projectSessionName]['email']);
        $mobile = ($this->session->userdata[$projectSessionName]['mobile']);
        $id = ($this->session->userdata[$projectSessionName]['id']);
        $designation = ($this->session->userdata[$projectSessionName]['designation']);
        $des=explode(',',$designation);
        $des = array_map('trim', $des);
        
    } else {
        redirect("UserAuthentication");
    }

    //for dynamic color for dashboard
    // $color=$this->ExcelModel->load('tbl_settings',2);
    // $colorValue="#F44336";
    // if(!empty($color)){
    //     if($color[0]['name']==""){
    //         $colorValue="#F44336";
    //     }else{
    //         $colorValue=$color[0]['name'];
    //     }
    // }

    $colorValue=($this->session->userdata['colorSession']['colorValue']);

?>

<html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title><?php echo $nameForDistributor; ?></title>
    <link rel="icon" href="<?php echo base_url('assets/uploads/favicon.ico');?>" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets/plugins/bootstrap/css/bootstrap.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/plugins/node-waves/waves.css');?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/plugins/animate-css/animate.css');?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/plugins/morrisjs/morris.css');?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/style.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/custom.css');?>" rel="stylesheet">
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

<style>
    .theme-dashboard .navbar {
        background-color: #F44336; 
    }

    .theme-dashboard .navbar-brand {
        color: #fff; 
    }

    .theme-dashboard .navbar-brand:hover {
        color: #fff; 
    }
    .theme-dashboard .navbar-brand:active {
        color: #fff; 
    }
    .theme-dashboard .navbar-brand:focus {
        color: #fff; 
    }

    .theme-dashboard .nav > li > a {
        color: #fff; 
    }

    .theme-dashboard .nav > li > a:hover {
        background-color: transparent; 
    }

    .theme-dashboard .nav > li > a:focus {
        background-color: transparent; 
    }

    .theme-dashboard .nav .open > a {
        background-color: transparent; 
    }

    .theme-dashboard .nav .open > a:hover {
        background-color: transparent; 
    }

    .theme-dashboard .nav .open > a:focus {
        background-color: transparent;
    }

    .theme-dashboard .bars {
        color: #fff; 
    }

    .theme-dashboard .sidebar .menu .list li.active {
        background-color: transparent; 
    }

    .theme-dashboard .sidebar .menu .list li.active > :first-child i, .theme-dashboard .sidebar .menu .list li.active > :first-child span {
        color: #F44336; 
    }

    .theme-dashboard .sidebar .menu .list .toggled {
        background-color: transparent; 
    }

    .theme-dashboard .sidebar .menu .list .ml-menu {
        background-color: transparent; 
    }

    .theme-dashboard .sidebar .legal {
        background-color: #fff; 
    }
    
    .theme-dashboard .sidebar .legal .copyright a {
        color: #F44336; !important;
    }
</style>

<style>
    
    #mySidenavForAll a {
    position: absolute;
    left: -80px;
    transition: 0.2s;
    padding: 10px;
    width: 90px;
    text-decoration: none;
    font-size: 12px;
    color: white;
    border-radius: 0 5px 5px 0;
    }

    #mySidenavForAll a:hover {
    left: 0;
    }

    #searchButtonSideMenu {
    top: 200px;
    background-color: #f74b42;
    }

    #researchButtonSideMenu {
    top: 250px;
    background-color: #f74b42;
    }

</style>



<script>
    $(document).ready(function() {
        $.fn.dataTable.ext.errMode = 'none';
        $('#Tbl').DataTable( {
            stateSave: false,
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

<style type="text/css">
.MayBeLongColumn {
    word-wrap: break-word !important;
}
</style>

<style type='text/css'>
.the_one {
  display: none;
}

.one_one:hover .the_one {
    display: block;
}
</style>

<style type="text/css">
    #myBtn {
      display: none;
      position: fixed;
      bottom: 10px;
      right: 10px;
      z-index: 99;
      font-size: 15px;
      border: none;
      outline: none;
      background-color:  #f74f2b ;
      color: white;
      cursor: pointer;
      padding: 10px;
      border-radius: 4px;
    }
    .ide{   
        /*float:left;*/
        background-color: #f74b42   ;
        color: white;
    }
    .txtItem{   
        color: white;
    }

  /*  @media (max-width: 480px) {*/
  /*.bt {*/
  /*  display: none;*/
  /*}*/
/*}*/

    #myBtn:hover {
      background-color: #f59782 ;

    }

     .zoom {
          zoom: 97%;
        }
</style>

</head>
<!-- <body class="theme-red zoom" oncontextmenu="return false;"> -->
<body class="theme-dashboard zoom" onload="noBack();" 
	onpageshow="if (event.persisted) noBack();" onunload="">




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

   <nav class="navbar navbar-expand-xs bg-light navbar-light">
        <div id="mySidenavForAll" class="sidenav">
            <a href="<?php echo base_url('index.php/AdHocController/billSearch'); ?>" target="_blank" id="searchButtonSideMenu">Search Bill</a>
            <a href="<?php echo base_url('index.php/AdHocController/allRetailerHistory'); ?>" target="_blank" id="researchButtonSideMenu">Retailer History</a>
        </div>
  
         <a  href="<?php echo site_url('DashbordController');?>"  data-toggle="collapse" data-target=".nav-collapse" class="mob-only">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px"><i class="material-icons">dashboard</i> 
                            <!--<i class="fa fa-caret-down"></i>-->
                        </span>
                        <!--<div class="dropdown-content" role="menu">-->
                        <!--    <p><a href="<?php echo site_url('owner/ExpensesController/allApprovals');?>">All Approvals</a></p>-->
                        <!--</div>-->
                    </div> 
                </a>
            <button class="bt navbar-toggler collapsed btn btn-xs ide menubarBtn"  type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon theme-red "><i class="material-icons">menu</i></span></button>
            <div class="container-fluid">
                        <div class="navbar-collapse collapse" id="navbarText" aria-expanded="false" style="height: 1px;">
                            <a href="javascript:void(0);" class="navbar-toggle collapsed d-none" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                            <a href="javascript:void(0);" class="bars" style="display: none;"></a>
                            <a class="dropdown" href="<?php echo site_url('DashbordController');?>" data-toggle="collapse" data-target=".nav-collapse" style="
                display: none;
            ">

            <a  href="<?php echo site_url('DashbordController');?>"  data-toggle="collapse" data-target=".nav-collapse" class="desk-only">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px"><i class="material-icons">dashboard</i> 
                            <!--<i class="fa fa-caret-down"></i>-->
                        </span>
                        <!--<div class="dropdown-content" role="menu">-->
                        <!--    <p><a href="<?php echo site_url('owner/ExpensesController/allApprovals');?>">All Approvals</a></p>-->
                        <!--</div>-->
                    </div> 
                </a>
            <?php if ((in_array('owner', $des))) { ?>
                <a  href="<?php echo site_url('owner/ExpensesController/allApprovals');?>"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px">Approvals 
                            <!--<i class="fa fa-caret-down"></i>-->
                        </span>
                        <!--<div class="dropdown-content" role="menu">-->
                        <!--    <p><a href="<?php echo site_url('owner/ExpensesController/allApprovals');?>">All Approvals</a></p>-->
                        <!--</div>-->
                    </div> 
                </a>
            <?php } ?>
            <?php if((in_array('owner', $des)) || (in_array('operator', $des)) || (in_array('senior_manager', $des)) || (in_array('manager', $des)) || (in_array('cashier', $des)) || (in_array('godownkeeper', $des)) || (in_array('deliveryman', $des)) || (in_array('salesman', $des))){
                ?>
                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px">Allocations 
                            <i class="fa fa-caret-down"></i>
                        </span>
                        <div class="dropdown-content">

                        
                        <?php if (in_array('manager', $des) || in_array('senior_manager', $des) || in_array('owner', $des)){ ?>
                            <p><a href="<?php echo site_url('AllocationByManagerController/openAllocations');?>">Open Allocations</a></p> 
                            <p><a href="<?php echo site_url('AllocationByManagerController/closedAllocations');?>">Closed Allocations</a></p>
                            <p><a href="<?php echo site_url('AllocationByManagerController/closedJournals');?>">Past Journal Entry</a></p> 
                            <?php if((in_array('owner', $des))){ ?>
                                
                                <p><a href="<?php echo site_url('owner/OfficeAllocationController/closedAllocations');?>">Closed Office Allocations</a></p> 
                                
                            <?php } ?>
                            
                        <?php } else if ((in_array('deliveryman', $des)) || (in_array('salesman', $des))){ ?>
                            
                            <p><a href="<?php echo site_url('AllocationByManagerController/openAllocations');?>">Open Allocations</a></p>
                            <p><a href="<?php echo site_url('AllocationByManagerController/closedAllocations');?>">Closed Allocations</a></p>
                        <?php } else if (in_array('godownkeeper', $des)){ ?>
                            <p><a href="<?php echo site_url('AllocationByManagerController/openAllocations');?>">Open Allocations</a></p> 
                            <p><a href="<?php echo site_url('AllocationByManagerController/closedAllocations');?>">Closed Allocations</a></p>
                        <?php } else if (in_array('cashier', $des)){ ?>
                            <p><a href="<?php echo site_url('AllocationByManagerController/openAllocations');?>">Open Allocations</a></p> 
                            <p><a href="<?php echo site_url('AllocationByManagerController/closedAllocations');?>">Closed Allocations</a></p>
                        

                        <?php }else if(in_array('operator', $des)){?>
                        <p><a href="<?php echo site_url('AllocationByManagerController/openAllocations');?>">Open Allocations</a></p> 
                       <?php } ?>
                        </div>
                        
                    </div> 
                </a>
            <?php  
                }
            ?>

            <?php if ((in_array('owner', $des)) || (in_array('senior_manager', $des)) || (in_array('manager', $des)) || (in_array('cashier', $des)) || (in_array('deliveryman', $des)) || (in_array('salesman', $des))) { 
                ?>
                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px">Outstanding Details
                            <i class="fa fa-caret-down"></i>
                        </span>
                        <div class="dropdown-content">
                            <?php 
                            
                            if((in_array('deliveryman', $des)) || (in_array('salesman', $des))){  
                        ?>
                            <p><a href="<?php echo site_url('SrController/salesmanOutstandingBills');?>">Outstanding Bills</a></p>
                            
                        <?php        
                            }else{
                        ?>

                            <p><a href="<?php echo site_url('SrController/outstandingBills');?>">Outstanding Bills</a></p>
                        <?php } 

                        ?> 

                            <p><a href="<?php echo site_url('SrController/resendBills');?>">Resend Bills</a></p> 
                            <p><a href="<?php echo site_url('SrController/lostBills');?>">Lost Bills</a></p>
                            <p><a href="<?php echo site_url('SrController/lostCheques');?>">Lost Cheques</a></p>
                            <p><a href="<?php echo site_url('SrController/unclearedNeft');?>">Pending NEFT</a></p>
                        <?php if((in_array('owner', $des)) || (in_array('senior_manager', $des))){ ?>
                            <p><a href="<?php echo site_url('owner/OfficeAllocationController/billClearance');?>">Residual Bill Clearing</a></p>
                        <?php } ?>

                        <p><a href="<?php echo site_url('BillTransactionController/retailerwiseDetails');?>">Retailer Wise Outstanding</a></p>
                        </div>
                    </div> 
                </a>
            <?php } ?>

         
            <?php if ((in_array('owner', $des)) || (in_array('senior_manager', $des)) || (in_array('manager', $des)) || (in_array('cashier', $des))){
                ?>
                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px">Bills
                            <i class="fa fa-caret-down"></i>
                        </span>
                        <div class="dropdown-content">
                           <p><a href="<?php echo site_url('AdHocController/adhocBills');?>">Add New Bills</a></p> 
                           <!--<p><a href="<?php echo site_url('SrController/allBills');?>">All Bills</a></p> -->
                    <?php if((in_array('owner', $des))){ ?>
                        <p><a href="<?php echo site_url('admin/BillTransactionController');?>">Change Bill Transaction </span>
                            </a></p>

                    <?php } ?>

                    <?php if((in_array('owner', $des)) || (in_array('senior_manager', $des)) || (in_array('manager', $des)) || (in_array('cashier', $des))){ ?>
                            <p><a href="<?php echo site_url('AdHocController/adhocDeliveryBills');?>">Direct Delivery Bills</a></p> 
                        <?php if((in_array('owner', $des))){ ?>
                            <p><a href="<?php echo site_url('BillTransactionController/cancelledBills');?>">Cancelled Bills</a></p>
                            <p><a href="<?php echo site_url('BillTransactionController/tempCancelledBills');?>">Suggested for Cancellation</a></p>
                            
                            <p><a href="<?php echo site_url('AdHocController/officeAdjustmentBills');?>">Office Adjustment Bills</a></p>

                        <?php } ?>
                        <?php if((in_array('owner', $des)) || (in_array('senior_manager', $des))){ ?>
                            <p><a href="<?php echo site_url('AdHocController/debitNoteBillsHistory');?>">Debit Note Bills</a></p>
                            <p><a href="<?php echo site_url('AdHocController/otherAdjustmentBills');?>">Other Adjustment Bills</a></p>
                            <p><a href="<?php echo site_url('AdHocController/cashDiscountHistoryBills');?>">CD Bills</a></p>
                            <p><a href="<?php echo site_url('AdHocController/freeItemBills');?>">Free Item Bills</a></p>
                        <?php } ?>
                            <p><a href="<?php echo site_url('AllocationByManagerController/nonAllocatedBillsDetails');?>">Unaccounted Bills</a></p>
                            <!--<p><a href="<?php echo site_url('AdHocController/officeAdjustmentBills');?>">Billwise Retailer Sale</a></p>-->
                    <?php } ?>

                            <!-- <p><a href="<?php echo site_url('AdHocController/billSearch');?>">Bill History</a></p> 
                            <p><a href="<?php echo site_url('AdHocController/allRetailerHistory');?>">Retailer History</a></p>  -->

                        </div>
                    </div> 
                </a>
            <?php } 
            ?>    
                <?php 
                
                if (in_array('owner', $des) || in_array('accountant', $des) || in_array('cashier', $des) || in_array('manager', $des) || in_array('senior_manager', $des)) { 
                   
                ?>
                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px">Cheque/NEFT
                            <i class="fa fa-caret-down"></i>
                        </span>
                        <div class="dropdown-content">
                            <p><a href="<?php echo site_url('CashAndChequeController/');?>">New Entry</a></p>    
                            <p><a href="<?php echo site_url('CashAndChequeController/DesktopBill');?>">Cheque Deposit Slip</a></p>
                            <?php if (!in_array('manager', $des)){ ?>
                            <p><a href="<?php echo site_url('CashAndChequeController/ChequeReconcilation');?>">Cheque Reconciliation</a></p> 
                              <p><a href="<?php echo site_url('CashAndChequeController/neftReconcilation');?>">NEFT Reconciliation</a></p> 
                          <?php } ?>
                            <p><a href="<?php echo site_url('CashAndChequeController/BounceCheques');?>">Bounced Cheques</a></p>  
                            <p><a href="<?php echo site_url('CashAndChequeController/ChequeRegister');?>">Cheque Register</a></p> 
                            <p><a href="<?php echo site_url('CashAndChequeController/NeftRegister');?>">NEFT Register</a></p> 
                        <?php if ((in_array('owner', $des)) || (in_array('cashier', $des)) || (in_array('accountant', $des))){ ?>
                            <p><a href="<?php echo site_url('CashAndChequeController/chequeDepositSlipTransactions');?>">Past Cheque Deposits</a></p>
                        <?php } ?>
                        </div>
                    </div> 
                </a>
                <?php 
                    }
                ?>
                <?php 
                        
                            if (in_array('owner', $des) || in_array('accountant', $des) || in_array('cashier', $des) ) { 
                ?>
                      
                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px">Cash Book
                            <i class="fa fa-caret-down"></i>
                        </span>
                        <div class="dropdown-content">

                       
                        <?php if (in_array('owner', $des) || in_array('cashier', $des) ) { ?>
                            <p><a href="<?php echo site_url('manager/CashBookController/IncomeExpense');?>">Day Book
                            </a></p>
                        <?php } ?>  

                        <?php if (in_array('owner', $des)) { ?>
                            <p><a href="<?php echo site_url('owner/MainCashBookController');?>">Main Cash Book</a></p>
                        <?php } ?>
                        
                             <p><a href="<?php echo site_url('manager/CashBookController/pastDay');?>">Past Day Book</a></p>
                             <p><a href="<?php echo site_url('manager/CashBookController/downloadPeriodDayBook');?>">Period Wise Day Book Download</a></p>
                            <!--<p><a href="<?php echo site_url('manager/CashBookController/pastDay');?>">Period Day Book-->
                            </a></p>
                            <p><a href="<?php echo site_url('accountant/AccountantController/incomeExpense');?>">Cash Inflow/Outflow Report</a></p> 
                            <p><a href="<?php echo site_url('accountant/AccountantController/periodIncomeExpense');?>">Period Income Expense Report</a></p> 
                        </div>
                    </div> 
                </a>
                <?php } 
                ?>
                <?php if (in_array('owner', $des) || in_array('manager', $des) || in_array('senior_manager', $des)){
                ?>
                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px">Delivery Slip
                            <i class="fa fa-caret-down"></i>
                        </span>
                        <div class="dropdown-content">
                            <p><a href="<?php echo site_url('DeliverySlipController');?>">New Transaction</a></p> 
                            <p><a href="<?php echo site_url('DeliverySlipController/deliverySlipDetail');?>">Outstanding Delivery Slips</a></p>
                            <p><a href="<?php echo site_url('DeliverySlipController/Products');?>">Product Master</a></p>
                            <p><a href="<?php echo site_url('RetailerController/');?>">Retailer Master</a></p>
                            
                            <p><a href="<?php echo site_url('DeliverySlipController/RetailerwiseDetails');?>">Retailer Outstanding </a></p>
                            <p><a href="<?php echo site_url('reports/ReportController/stockMovementReport');?>">Stock Movement Report</a></p>
                           
                            <!--<p><a href="<?php echo site_url('DeliverySlipController/BillwiseDetails');?>">Billwise Details</a></p>-->
                        
                            <!--<div class="one_one">Reports-->
                            <!--  <div class="the_one">-->
                            <!--       <p><a href="<?php echo site_url('DeliverySlipController/salesmanSaleDetail');?>">Salesman Stock Report</a></p>  -->
                            <!--       <p><a href="<?php echo site_url('DeliverySlipController/retailerAccountDetail');?>">Retailer Account Statement</a></p>    -->
                            <!--    </div>-->
                            <!--</div>-->
                            
                        </div>
                    </div> 
                </a>
                <?php 
                }
                ?>
               

                <?php if (in_array('owner', $des) || in_array('senior_manager', $des) || in_array('operator', $des) || in_array('godownkeeper', $des) || in_array('deliveryman', $des) || in_array('salesman', $des)){
                            
                ?>
                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px">System Entries
                            <i class="fa fa-caret-down"></i>
                        </span>
                        <div class="dropdown-content">
                        <?php if (in_array('owner', $des) || in_array('senior_manager', $des) || in_array('godownkeeper', $des) || in_array('deliveryman', $des) || in_array('salesman',$des)){ ?>
                            <p><a href="<?php echo site_url('manager/SrCheckController/pendingSr');?>">Allocation Wise SR </a></p> 
                        <?php } ?>
                        <?php if (in_array('operator', $des) || in_array('owner', $des) || in_array('senior_manager', $des)){ ?>
                            <p><a href="<?php echo site_url('operator/OperatorController/pendingSr');?>">Pending SR </a></p>

                        <?php } ?>

                        <?php if (in_array('operator', $des) || in_array('owner', $des) || in_array('senior_manager', $des)){ ?>
                            <p><a href="<?php echo site_url('operator/OperatorController/srPrint');?>">SR Print </a></p>
                            <p><a href="<?php echo site_url('operator/OperatorController/fsrPrint');?>">FSR Bills </a></p>
                        <?php } ?>   

                        <?php if (in_array('operator', $des) || in_array('owner', $des) || in_array('senior_manager', $des)){ ?>
                            <p><a href="<?php echo site_url('operator/OperatorController/pendingCollection');?>">Pending Collection </a></p>
                            
                        <?php } ?>
                        
                        <?php if (in_array('operator', $des) || in_array('owner', $des) || in_array('senior_manager', $des)){ ?>
                            <p><a href="<?php echo site_url('DeliverySlipController/pendingForBilling');?>">Pending Billing </a></p>
                        <?php } ?>  

                         
                        </div>
                    </div> 
                </a>

                <?php  } ?>


                <?php if (in_array('owner', $des) || in_array('senior_manager', $des)) { 

                    
                ?>
                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px">Employees
                            <i class="fa fa-caret-down"></i>
                        </span>
                        <div class="dropdown-content">
                            <p><a href="<?php echo site_url('manager/EmployeeController/employeeLedger');?>">Employee Ledger</a></p>      
                            <p><a href="<?php echo site_url('manager/EmployeeController');?>">Employee Master</a></p>
                            <p><a href="<?php echo site_url('manager/EmployeeController/employeeClearance');?>">Employee Clearance</a></p> 
                        <?php if ((in_array('owner', $des))) { ?>
                            <!--<p><a href="<?php echo site_url('owner/DistributorController');?>">Distributor Details</a></p> -->
                            <!--<p><a href="<?php echo site_url('admin/EmployeeController/salesmanLinking');?>">Salesman Linking</a></p>-->
                        <?php } ?> 
                        </div>
                    </div> 
                </a>
                <?php } 
            
                ?>

<?php if((in_array('owner', $des)) || (in_array('senior_manager', $des)) || (in_array('manager', $des)) || (in_array('cashier', $des)) || (in_array('godownkeeper', $des)) || (in_array('salesman', $des))){ ?>

                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px">Reports
                            <i class="fa fa-caret-down"></i>
                        </span>
                        <div class="dropdown-content">
                        <?php if((in_array('owner', $des)) || (in_array('senior_manager', $des)) || (in_array('manager', $des)) || (in_array('cashier', $des)) || (in_array('godownkeeper', $des)) || (in_array('salesman', $des))){ ?>
                            <p><a href="<?php echo site_url('reports/ReportController/billWiseRetailerReportView');?>">Billwise Retailer sale</a></p>
                            <p><a href="<?php echo site_url('reports/ReportController/billWiseCollectionReportView');?>">Billwise Collection Report</a></p>
                            <p><a href="<?php echo site_url('reports/ReportController/datewiseReportView');?>">Datewise Collection Report</a></p>
                            <p><a href="<?php echo site_url('reports/ReportController/allocationWiseCollectionReportView');?>">Allocation Wise Collection Report</a></p>
							<p><a href="<?php echo site_url('reports/ReportController/frequentsrRetailersReportView');?>">Frequent SR Retailers Report</a></p>
                            <p><a href="<?php echo site_url('reports/ReportController/frequentsrSalesmanReportView');?>">Frequent SR Salesman Report</a></p>
							<p><a href="<?php echo site_url('reports/ReportController/multipleVisitorRetailerReportView');?>">Multiple Visitor Retailer Report</a></p>
							<p><a href="<?php echo site_url('reports/ReportController/OverdueBillsReports');?>">Overdue Bills Report</a></p> 
							<p><a href="<?php echo site_url('reports/ReportController/RetailerAccountStatementReport');?>">Retailer Account Statement Report</a></p>
                        <?php } ?>
                        <?php if (in_array('owner', $des) || in_array('manager', $des) || in_array('senior_manager', $des)){?>
                            <p><a href="<?php echo site_url('reports/ReportController/deliveryslipSalesReportView');?>">Deliveryslip Sale Report</a></p>
                        <?php } ?>
                        </div>
                    </div> 
                </a>
                <?php } ?>
                
                <?php if ((in_array('owner', $des)) || (in_array('senior_manager', $des))) { ?>
                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px">Settings
                            <i class="fa fa-caret-down"></i>
                        </span>
                        <div class="dropdown-content">
                            <p><a href="<?php echo site_url('admin/EmployeeController/salesmanLinking');?>">Salesman Linking</a></p>
                            <p><a href="<?php echo site_url('admin/CategoriesController/expensesCategory');?>">Expense Master</a></p>
                            <p><a href="<?php echo site_url('admin/CategoriesController/incomeCategory');?>">Income Master</a></p>

                            <p><a href="<?php echo site_url('admin/CompanyController/officeDetails');?>">Office Details</a></p>
                            <p><a href="<?php echo site_url('admin/EmployeeController/employeeeException');?>">Employees Exempt</a></p>

                            <p><a href="<?php echo site_url('admin/PenaltyController');?>">Cheque Bounce Penalties</a></p>
                           
                            <p><a href="<?php echo site_url('admin/SettingsController/highlightingDays');?>">Notification / Highlight Limit</a></p>

                            <p><a href="<?php echo site_url('admin/BankController');?>">Banks for Inward Cheques</a></p>
                           
                            <p><a href="<?php echo site_url('admin/SettingsController//settingForDeliveryslip');?>">Deliveryslip Setting</a></p>
                            <p><a href="<?php echo site_url('DashbordController/dashboardColorCode');?>">Dashboard Color Setting</a></p>
                            <!-- <p><a href="<?php echo site_url('admin/SettingsController/dynamicNames');?>">Dynamic Names</a></p> -->
                        </div>
                    </div> 
                </a>
                <?php } 
            
                ?>
                <!-- <a class="dropdown" href="<?php echo site_url('ReportsController');?>"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">
                        <span class="navbar-brand" style="font-size: 14px">Reports 
                        </span>
                    </div> 
                </a> -->
                <!--<a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">-->
                <!--    <div class="dropdown">-->
                <!--        <span class="navbar-brand" style="font-size: 14px">Reports-->
                <!--            <i class="fa fa-caret-down"></i>-->
                <!--        </span>-->
                        
                <!--    </div> -->
                <!--</a>-->

                <a class="dropdown" href="#"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown">

                        <span class="navbar-brand" style="font-size: 14px">
                            <?php echo $email; ?>
                        </span>
                        <div class="dropdown-content">
                            <p><a href="javascript:void();" id="emp_pro_det_id" data-toggle="modal" data-target="#empProDetails" data-id="<?php echo $id;?>"><i class="material-icons">account_box</i><span>Profile</span></a></p>      
                            <p><a href="javascript:void();" data-toggle="modal" data-target="#updateChangePasswordlimitModal"><i class="material-icons">vpn_key</i>Password</a></p>
                            <p><a href="<?php echo site_url('admin/CompanyController/manageAccountDetails');?>"><i class="material-icons">manage_accounts</i>Accounts</a></p>
                            <p><a href="<?php echo site_url('UserAuthentication/logout');?>"><i class="material-icons">exit_to_app</i>Logout</a></p>
                        </div>
                    </div> 
                </a>

               <!--  <a class="dropdown" href="<?php echo site_url('UserAuthentication/logout');?>"  data-toggle="collapse" data-target=".nav-collapse">
                    <div class="dropdown" >
                        <span class="navbar-brand" style="font-size: 11px"><i class="material-icons">assignment_ind</i> </span>
                    </div> 
                </a> -->
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