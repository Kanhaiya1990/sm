<?php $this->load->view('/layouts/commanHeader'); 
$projectSessionName="";
if (isset($this->session->userdata['codeKeyData'])) {
    $projectSessionName= ($this->session->userdata['codeKeyData']['codeKeyValue']);
}
?>

<style type="text/css">
    @media screen and (min-width: 1100px) {
        .modal-dialog {
          width: 1100px; /* New width for default modal */
        }
        .modal-sm {
          width: 400px; /* New width for small modal */
        }
    }

    @media screen and (min-width: 1100px) {
        .modal-lg {
          width: 1100px; /* New width for large modal */
        }
    }

    hr.dotted {
      border-top: 3px dotted #bbb;
    }

</style>

<script>
    $(document).ready(function() {
    $.fn.dataTable.ext.errMode = 'none';
    $('#xp').DataTable( {
        dom: 'Bfrtip',
        stateSave: true,
            buttons: [{
                    extend: 'pdf',
                    title: 'Retailerwise Outstanding',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                },{
                    extend: 'excel',
                    title: 'Retailerwise Outstanding',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                }, {
                    extend: 'csv',
                    title: 'Retailerwise Outstanding',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                }
            ]
        
    } );
} );
</script>

<style>
td.details-control {
    background: url('http://www.datatables.net/examples/resources/details_open.png') no-repeat center center;
    cursor: pointer;
}
tr.shown td.details-control {
    background: url('http://www.datatables.net/examples/resources/details_close.png') no-repeat center center;
}
</style>

<script src="<?php echo base_url('assets/js/pages/ui/tooltips-popovers.js');?>"></script>
<script   src="https://code.jquery.com/jquery-1.12.1.js" integrity="sha256-VuhDpmsr9xiKwvTIHfYWCIQ84US9WqZsLfR4P7qF6O8="   crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>    
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
           
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                             Retailer History
                            </h2>
                       
                        </div>
                      
                        <div class="body">
                            
                            <div class="row clearfix">
                            <!-- <div class="demo-masked-input"> -->
                                <form method="post" role="form" action="<?php echo base_url('index.php/AdHocController/allRetailerHistory'); ?>"> 
                                  <div class="col-md-12"> 

                                    <?php
                                        $retName="";
                                        if(!empty($bills)){
                                            $retName=$bills[0]['retailerName'].' : '.$bills[0]['routeName'].' : '.$bills[0]['compName'].' : '.$bills[0]['retailerCode'];
                                        }
                                    ?>
                                    <div class="col-md-6">
                                        <b>Retailer</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" value="<?php echo $retName; ?>" autocomplete="off" id="retailer" name="retailer" class="form-control date" placeholder="Enter Retailer Name" list="retailerData" required>
                                                <datalist id="retailerData">
                                                <?php
                                                    foreach($retailer as $data){
                                                        $name=$data['retailerName'].' : '.$data['routeName'].' : '.$data['compName'].' : '.$data['retailerCode'];
                                                ?>   
                                                
                                                <option value="<?php echo $name;?>"/>
                                                <?php    
                                                    }
                                                ?>
                                            </datalist>
                                            </div>
                                            <p id="billNo_Id"></p>
                                        </div>
                                    </div>

                                    

                                    <div class="col-md-2">
                                        <b>From Date</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="date" autocomplete="off" id="fromDate" name="fromDate" value="<?php echo date('Y-m-d',strtotime("-1 year")) ; ?>" class="form-control date" placeholder="Enter date" list="compData" required>
                                            </div>
                                            <p id="billNo_Id"></p>
                                        </div>
                                    </div>

                                     <div class="col-md-2">
                                        <b>To Date</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="date" autocomplete="off" id="toDate" name="toDate" value="<?php echo date('Y-m-d'); ?>" class="form-control date" placeholder="Enter date" list="compData" required>
                                            </div>
                                            <p id="billNo_Id"></p>
                                        </div>
                                    </div>
                                  
                                        <div class="col-md-2">
                                             <button type="submit" class="btn btn-xs btn-primary m-t-25 waves-effect">
                                                <i class="material-icons">search</i> 
                                                <span class="icon-name">
                                                 Search
                                                </span>
                                            </button>
                                           <!--  <button id="searchInfo" class="btn btn-xs btn-primary m-t-15 waves-effect">
                                                <i class="material-icons">search</i> 
                                                <span class="icon-name">
                                                 Search
                                                </span>
                                            </button> -->
                                           <a href="<?php echo site_url('AdHocController/allRetailerHistory');?>">
                                                <button type="button" class="btn btn-xs btn-danger m-t-25 waves-effect">
                                                    <i class="material-icons">cancel</i> 
                                                    <span class="icon-name"> Cancel</span>
                                                </button>
                                            </a> 
                                        </div>

                                        
                                    </div> 

                                </form>
                                    <div id="hideInfo" class="col-md-12"> 

     <table style="font-size: 12px;" class="table table-bordered table-striped table-hover" data-page-length='100'>
        <thead>
            <tr colspan="8">
                 <th>
                  <span style="color:blue"> Retailer Information  </span>
              </th>
            </tr>
        </thead>

        <thead>
            <tr class="gray">
               
                 <th colspan="3"> Retailer Name  </th>
                 <th colspan="3">Retailer Code</th>
                <th colspan="3"> Route Name  </th>
                <th colspan="5">Retailer GST No.</th>
                

            </tr>
        </thead>
            <tbody>
            <?php
               
                if(!empty($bills)){
                    $retailerCode=$this->AllocationByManagerModel->loadRetailer($bills[0]['retailerCode']);
            ?>
                   
                    
            <tr>
                <td colspan="3"><?php echo $bills[0]['retailerName']; ?></td>
                <td colspan="3"><?php echo $bills[0]['retailerCode']; ?></td>
                <td colspan="3"><?php echo $bills[0]['routeName']; ?></td>
                 <td colspan="5"><?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?></td>
            </tr>

            <?php
                    
                }else{
            ?>
                    <tr><td colspan="14">No data available.</td></tr>
           <?php     
                } 
                
            ?>

        </tbody>
        </table>

        <hr class="dotted">

        <div class="row">
            <?php echo $pagination; ?> 

            <div class="col-sm-3">
              <b>Range</b>
              <div class="form-group">
                <select class="form-control" id="limitRows" onchange="sendRequest();">
                  <option value="25">25</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                  <option value="500">500</option>
                  <option value="1000">1000</option>
                  <option value="2000">2000</option>
                  <option value="5000">5000</option>
                  <option value="10000">10000</option>
                </select>
              </div>
            </div>

            <div class="col-sm-3">
              <b>Search Anything</b>
              <div class="form-group">
                <div class="form-line">
                   <input type="text" name="searchFor" placeholder="Search..." class="form-control" id="searchKey" onchange="sendRequest();">
                </div>
              </div>
            </div>
            <div class="col-sm-3">
              <a href="<?php echo site_url('AdHocController/allRetailerHistory'); ?>" class="btn btn-xs m-t-25 btn-danger waves-effect">
                    <i class="material-icons">cancel</i> 
                    <span class="icon-name"> Cancel</span>
                </a>
                &nbsp;&nbsp;
                <a class="btn btn-xs m-t-25 btn-primary"  href="<?php echo site_url(); ?>/AdHocController/retailerBillsExport"> 
                    <i class="material-icons">download</i> 
                    <span class="icon-name"> Export</span></a>
            
            </div>
        </div>

        
      <!--  <div class="top-panel">
          <div class="btn-group pull-right">
            
          </div>
        </div>   -->                    
     <table style="font-size: 12px;" class="table table-bordered table-striped table-hover" data-page-length='25'>
        <thead>
            <tr class="gray">
                <th data-action="sort" data-direction="ASC" data-title="billNo"> Bill No  </th>
                <th data-action="sort" data-direction="ASC"  data-title="date">Bill Date</th>
                <th data-action="sort" data-direction="ASC" data-title="salesman"> Salesman </th>
                <th data-action="sort" data-direction="ASC" data-title="salesman"> Employee </th>
                <th class="text-right" data-action="sort" data-direction="ASC"  data-title="netAmount"> Net Amount </th>
                <th class="text-right" data-action="sort" data-direction="ASC" data-title="SRamt"> SR  </th>
                <th class="text-right" data-action="sort" data-direction="ASC" data-title="cd"> CD  </th>
                <th class="text-right" data-action="sort" data-direction="ASC" data-title="receivedAmt"> Collection </th>
                <th class="text-right" data-action="sort" data-direction="ASC" data-title="officeAdjustmentBillAmount"> Office Adj  </th>
                <th class="text-right" data-action="sort" data-direction="ASC" data-title="otherAdjustment"> Other Adj  </th>
                <th class="text-right" data-action="sort" data-direction="ASC" data-title="debit"> Debit </th>
                <th class="text-right" data-action="sort" data-direction="ASC" data-title="pendingAmt"> Remaining  </th>
                <th class="text-right" data-action="sort" data-direction="ASC" data-title="chequePenalty"> Cheque Penalty  </th>
                <th> Action  </th>
                <th> Status  </th>
            </tr>
        </thead>
        <tbody>
            <?php
              
                if(!empty($bills)){
                    foreach ($bills as $data) 
                    {
                       
                       $allocations=$this->AllocationByManagerModel->getAllocationDetailsByBill('bills',$data['id']);
                        $allocationsHistory=$this->AllocationByManagerModel->getAllocationDetailsByBillHistory('bills',$data['id']);
                        $officeAllocations=$this->AllocationByManagerModel->getOfficeAllocationDetailsByBill('bills',$data['id']);
                        $officeAllocationsHistory=$this->AllocationByManagerModel->getOfficeAllocationDetailsByBillHistory('bills',$data['id']);
                       
                        $billHistory=$this->AllocationByManagerModel->getBillpaymentHistory('billpayments',$data['id']);
                        $billSrHistory=$this->AllocationByManagerModel->getSrBillpaymentHistory('allocation_sr_details',$data['id']);
                       // print_r($billHistory);
                        $clearDate="";
                        $eName="";
                        if(!empty($billHistory)){
                            $clearDate=$billHistory[0]['date'];
                            $eName=$billHistory[0]['empName'];
                        }else if(!empty($billSrHistory)){
                            $clearDate=$billSrHistory[0]['createdAt'];
                            $eName=$billSrHistory[0]['empName'];
                        }

                        $status="";
                        $allocationNumber="";
                        $allocationName="";
                        $empName="";

                      $dt=date_create($data['date']);
                      $createdDate = date_format($dt,'d-M-Y');
                   
                    if($data['isAllocated']==1){ ?>
                         <tr style="background-color: #dcd6d5">
                    <?php }else{ ?>
                         <tr>
                    <?php } ?>
                        
                        <td><?php echo $data['billNo']; ?></td>
                        <td><?php echo $createdDate; ?></td>
                        <td><?php echo $data['salesman']; ?></td>
                         <td><?php echo $eName; ?></td>
                        <td class="text-right"><?php echo number_format($data['netAmount']); ?></td>
                        <td class="text-right"><?php echo number_format($data['SRAmt']); ?></td>
                         <td class="text-right"><?php echo number_format($data['cd']); ?></td>
                         <td class="text-right"><?php echo number_format($data['receivedAmt']); ?></td>
                        <td class="text-right"><?php echo number_format($data['officeAdjustmentBillAmount']); ?></td>
                        <td class="text-right"><?php echo number_format($data['otherAdjustment']); ?></td>
                        <td class="text-right"><?php echo number_format($data['debit']); ?></td>
                        <td class="text-right"><?php if($data['deliveryStatus']=="cancelled"){ echo "0"; }else{ echo number_format($data['pendingAmt']); } ?></td>
                        <td class="text-right"><?php echo number_format($data['chequePenalty']); ?></td>

                        <td>
                        <?php 
                        if($data['deliveryStatus']=="cancelled"){
                            echo "Cancelled";
                        } else{

                        

                            if($data['isAllocated']==1){
                                if(!empty($allocations)){
                                    echo "<span style='color:blue'>Allocated in : ".$allocations[0]['allocationCode'].'</span>';
                                }

                                if(!empty($officeAllocations)){
                                    echo "<span style='color:blue'>Allocated in : ".$officeAllocations[0]['allocationCode'].'</span>';
                                }
                            }else{
                                if($data['pendingAmt']==0){
                                    echo "<span style='color:green'> Cleared on ". date('d-M-Y H:i:sa', strtotime($clearDate))."</span>";
                                }else if($data['isDirectDeliveryBill']==1){
                                    echo "<span style='color:green'> Direct Delivery</span>";
                                }else{
                                  if(!empty($allocationsHistory) || !empty($officeAllocationsHistory)){
                                    if(!empty($allocationsHistory)){
                                      echo "<span style='color:blue'>Earlier Allocated </span>";
                                    }

                                    if(!empty($officeAllocationsHistory)){
                                      echo "<span style='color:blue'>Already Allocated in : ".$officeAllocationsHistory[0]['allocationCode'].'</span>';
                                    }
                                  }else{
                                      if($data['pendingAmt']==$data['netAmount']){
                                         echo "<span style='color:red'> Unaccounted</span>";
                                      }else{
                                         echo "<span style='color:blue'> Accounted</span>";
                                      }
                                      
                                  }
                                }
                            } 
                          }
                        ?>
                        </td>
                        <!-- <td>
                        <?php 
                                
                            $allocations=$this->AllocationByManagerModel->getAllocationDetailsByBill('bills',$data['id']);
                            $allocationsHistory=$this->AllocationByManagerModel->getAllocationDetailsByBillHistory('bills',$data['id']);
                            $officeAllocations=$this->AllocationByManagerModel->getOfficeAllocationDetailsByBill('bills',$data['id']);
                            $officeAllocationsHistory=$this->AllocationByManagerModel->getOfficeAllocationDetailsByBillHistory('bills',$data['id']);
                            // print_r($allocations);exit;
                            $status="";
                            $allocationNumber="";
                            $allocationName="";
                            $empName="";
                            if($data['isAllocated']==1){ 
                                if(!empty($allocations)){
                                    $allocationNumber=$allocations[0]['id'];
                                    $allocationName=$allocations[0]['allocationCode'];
                                    $empName=trim($allocations[0]['ename1']).','.trim($allocations[0]['ename2']).','.trim($allocations[0]['ename3']).','.trim($allocations[0]['ename4']);
                                    $status="Allocated : ".$allocationName;
                                }

                                if(!empty($officeAllocations)){
                                    $allocationNumber=$allocations[0]['id'];
                                    $allocationName=$allocations[0]['allocationCode'];
                                    $status="Allocated : ".$allocationName;
                                }
                            }else{
                                if(!empty($allocationsHistory) || !empty($officeAllocationsHistory)){
                                  if(!empty($allocationsHistory)){
                                    $status="Past Bill";
                                  }
                                  if(!empty($officeAllocationsHistory)){
                                    $status="Past Bill";
                                  }
                                }else{
                                    if($data['pendingAmt'] == $data['netAmount']){
                                        $status="Unaccounted";
                                    }else if($data['deliveryEmpName'] !==""){
                                        $status="Direct Delivery";
                                    }else if($data['pendingAmt'] <=0){
                                        $status= "Bill Cleared";
                                    }
                                }
                            } 

                            echo $status;

                         ?>
                     </td> -->
                        <!-- <td>
                            <a id="billHistory" href="javascript:void()" data-id="<?php echo $data['id']; ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>" data-gst="<?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?>" data-code="<?php echo $data['retailerCode']; ?>" data-salesman="<?php echo $data['salesman']; ?>" data-route="<?php echo $data['routeName']; ?>" data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-toggle="modal" data-target="#billprocessModal"><button class="btn btn-xs bg-primary margin"><i class="material-icons">visibility</i> </button></a>
                            <a id="billHistoryProcess" href="javascript:void()" data-id="<?php echo $data['id']; ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>" data-gst="<?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?>" data-code="<?php echo $data['retailerCode']; ?>" data-salesman="<?php echo $data['salesman']; ?>" data-route="<?php echo $data['routeName']; ?>" data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-toggle="modal" data-target="#retailerprocessModal"><button class="btn btn-xs bg-primary margin">Process</button></a>
                        </td> -->
                              <td>
                        <?php
                        if($data['isAllocated']!=1 && $data['pendingAmt'] >0 && $data['deliveryStatus'] !=="cancelled"){

                            $designation = ($this->session->userdata[$projectSessionName]['designation']);
                            $des=explode(',',$designation);
                            $des = array_map('trim', $des);

                            if ((in_array('operator', $des))) { 
                    ?>
                                &nbsp;&nbsp;<a href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs  btn-primary" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                                               
                    <?php
                            }else{
                    ?>
                    <a id="prDetailsForAll" href="javascript:void()" data-id="<?php echo $data['id']; ?>" data-salesman="<?php echo $data['salesman']; ?>" data-billDate="<?php echo $createdDate; ?>" data-credAdj="<?php echo $data['creditAdjustment']; ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>" data-gst="<?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?>" data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-route="<?php echo $data['routeName']; ?>" data-toggle="modal" data-target="#processModalForAll" ><button class="btn btn-xs btn-primary waves-effect waves-float" data-toggle="tooltip" data-placement="bottom" title="Process"><i class="material-icons">touch_app</i></button></a>

                            <!-- <a id="prDetails" href="javascript:void()" data-id="<?php echo $data['id']; ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>" data-gst="<?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?>" data-code="<?php echo $data['retailerCode']; ?>" data-salesman="<?php echo $data['salesman']; ?>" data-route="<?php echo $data['routeName']; ?>" data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-toggle="modal" data-target="#processModal"><button class="btn btn-xs  btn-primary"><i class="material-icons">touch_app</i></button></a> -->
                    &nbsp;&nbsp;<a target="_blank" href="<?php echo site_url('AdHocController/billHistoryInfo/'.$data['id']); ?>" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="View History"><i class="material-icons">info</i></a>
                    &nbsp;&nbsp;<a target="_blank" href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs  btn-primary" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                                               
                      <?php }

                        }else{
                            
                    ?>
                        &nbsp;&nbsp;<a target="_blank" href="<?php echo site_url('AdHocController/billHistoryInfo/'.$data['id']); ?>" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="View History"><i class="material-icons">info</i></a>
                        &nbsp;&nbsp;<a target="_blank" href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs  btn-primary" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                   
                    <?php
                        }

                        ?>
                        </td>
                        
                      </tr>

                    <?php
                    }
                 }else{

                    ?>
                    <tr><td colspan="14">No data available.</td></tr>
           <?php     } 
                ?>

        </tbody>
</table>
<?php echo $pagination; ?>

                                    </div>                                
                                <!-- </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
  <div class="modal fade" id="billprocessModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <center><h4 class="modal-title">Bill History </h4></center>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
           <!-- <a href="<?php echo site_url('AdHocController/retailerHistory');?>">
                <button type="button" class="btn btn-primary m-t-15 waves-effect">
                    <i class="material-icons">cancel</i> 
                </button>
            </a>  -->
          </div>
          <div class="modal-body">
        
            <div class="body">
                <div class="demo-masked-input">
                    <div class="row clearfix">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <b>Bill No : </b> <span id='bill_no'></span>
                                    <input type="hidden" id="currentBillNo" autocomplete="off" name="currentBillNo" class="form-control"> 
                                    <input type="hidden" id="currentBillId" autocomplete="off" name="currentBillId" class="form-control"> 
                                     <input type="hidden" id="currentBillRetailer" autocomplete="off" name="currentBillRetailer" class="form-control">    
                                </div> 

                                <div class="col-md-3">
                                    <b>Retailer : </b>
                                        <span id='bill_retailer'></span>
                                </div> 

                                <div class="col-md-3">
                                    <b>GST No. : </b>
                                        <span id='gst'></span>
                                </div>

                                 <div class="col-md-3">
                                    <b>Pending Amount : </b><span id='bill_pendingAmt'></span>
                                    <input type="hidden" id="currentPendingAmt" autocomplete="off" name="currentPendingAmt" class="form-control">   
                                </div>
                            </div>

                            <div id="hideBillHistoryInfo" class="col-md-12"> 

                            </div>
                        </div>
                 
                    </div>
                </div>
            </div>
                     
          </div>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('/layouts/footerDataTable'); ?>

<?php $this->load->view('/layouts/processButtonView'); ?>

<script type="text/javascript">
    // $(document).on('keypress','#retailer',function(e){
    //     var keyCode = (event.keyCode ? event.keyCode : event.which);   
    //     if (keyCode == 13111) {
    //         var retailer=$('#retailer').val();
    //         var fromDate=$('#fromDate').val();
    //         var toDate=$('#toDate').val();
    //         // alert(retailer);
    //         if(retailer=='' || fromDate=='' || toDate==''){
    //             alert('Please enter all details.');
    //         }else{
    //             $.ajax({
    //                 type: "POST",
    //                 url:"<?php echo site_url('AdHocController/retailerHistoryInfo');?>",
    //                 data:{retailer:retailer,fromDate:fromDate,toDate:toDate},
    //                 success: function (data) {
    //                     $('#hideInfo').html(data);
    //                 }  
    //             });
    //         }
    //     }
        
    // });
</script>

<script type="text/javascript">
    // $(document).on('click','#searchInfo',function(){
    //     var retailer=$('#retailer').val();
    //     var fromDate=$('#fromDate').val();
    //     var toDate=$('#toDate').val();
    //     // alert(retailer);
    //     if(retailer=='' || fromDate=='' || toDate==''){
    //         alert('Please enter all details.');
    //     }else{
    //         $.ajax({
    //             type: "POST",
    //             url:"<?php echo site_url('AdHocController/retailerHistoryInfo');?>",
    //             data:{retailer:retailer,fromDate:fromDate,toDate:toDate},
    //             success: function (data) {
    //                 $('#hideInfo').html(data);
    //             }  
    //         });
    //     }
        
    // });
</script>

<script type="text/javascript">
    $(document).on('click','#billHistory',function(){
        var id=$(this).attr('data-id');
        var billNo=$(this).attr('data-billNo');
        var retailerName=$(this).attr('data-retailerName');
        var pendingAmt=$(this).attr('data-pendingAmt');
        var gst=$(this).attr('data-gst');
        
        $('#currentPendingAmt').val(pendingAmt);
        $('#currentBillId').val(id);
        $('#currentBillNo').val(billNo);
        $('#currentBillRetailer').val(retailerName);
        $('#bill_no').text(billNo);
        $('#gst').text(gst);
       
        $('#bill_retailer').text(retailerName);
        $('#bill_pendingAmt').text(pendingAmt);

        $.ajax({
            type: "POST",
            url:"<?php echo site_url('AdHocController/retailerbillInfo');?>",
            data:{billNo:billNo,billId:id},
            success: function (data) {
                $('#hideBillHistoryInfo').html(data);
            },
            beforeSend: function(){
                    $('.comman-ajax-loader').css("visibility", "visible");
                },
                complete: function(){
                    $('.comman-ajax-loader').css("visibility", "hidden");
                },
                error: function(jqXHR, exception) {
                    alert("Something Went Wrong, Please Try Again...!");
                }    
        });
       


    });
</script>

<script>
     function numbersonly(myfield, e){
            var key;
            var keychar;
            if (window.event)
                key = window.event.keyCode;
            else if (e)
                key = e.which;
            else
                return true;

            keychar = String.fromCharCode(key);
            // control keys
            if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
                return true;

            // numbers
            else if ((("0123456789").indexOf(keychar) > -1))
                return true;

            // only one decimal point
            else if ((keychar == "."))
            {
                if (myfield.value.indexOf(keychar) > -1)
                    return false;
            }
            else
                return false;
    }
</script>
<script type="text/javascript">
    var sendRequest = function(){
      // var curOrderField = "BillNo";
      // var curOrderDirection = "ASC";
      var searchKey = $('#searchKey').val();
      var limitRows = $('#limitRows').val();
      window.location.href = '<?=base_url('index.php/AdHocController/allRetailerHistory')?>?query='+searchKey+'&limitRows='+limitRows+'&orderField='+curOrderField+'&orderDirection='+curOrderDirection;
    }

    var getNamedParameter = function (key) {
            if (key == undefined) return false;
            var url = window.location.href;
            var path_arr = url.split('?');
            if (path_arr.length === 1) {
                return null;
            }
            path_arr = path_arr[1].split('&');
            path_arr = remove_value(path_arr, "");
            var value = undefined;
            for (var i = 0; i < path_arr.length; i++) {
                var keyValue = path_arr[i].split('=');
                if (keyValue[0] == key) {
                    value = keyValue[1];
                    break;
                }
            }
            return value;
        };

        var remove_value = function (value, remove) {
            if (value.indexOf(remove) > -1) {
                value.splice(value.indexOf(remove), 1);
                remove_value(value, remove);
            }
            return value;
        };

        var curOrderField, curOrderDirection;
        $('[data-action="sort"]').on('click', function(e){
          curOrderField = $(this).data('title');
          curOrderDirection = $(this).data('direction');
          // curOrderField = "BillNo";
          // curOrderDirection = "ASC";
          sendRequest();
        });

        $('#searchKey').val(decodeURIComponent(getNamedParameter('query')||""));
        $('#limitRows option[value="'+getNamedParameter('limitRows')+'"]').attr('selected', true);

        var curOrderField = getNamedParameter('orderField')||"";
        var curOrderDirection = getNamedParameter('orderDirection')||"";
        var currentSort = $('[data-action="sort"][data-title="'+getNamedParameter('orderField')+'"]');
        if(curOrderDirection=="ASC"){
          currentSort.attr('data-direction', "DESC").find('i.glyphicon').removeClass('glyphicon-triangle-bottom').addClass('glyphicon-triangle-top'); 
        }else{
          currentSort.attr('data-direction', "ASC").find('i.glyphicon').removeClass('glyphicon-triangle-top').addClass('glyphicon-triangle-bottom');  
        }

  </script>