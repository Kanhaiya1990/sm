<?php $this->load->view('/layouts/commanHeader'); ?>
 
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

<style type="text/css">
   .selectStyle select {
   background: transparent;
   width: 250px;
   padding: 4px;
   font-size: 1em;
   border: 1px solid #ddd;
   height: 25px;
}
li{
margin-bottom: 0PX;
padding-bottom: 0PX;
}

.logo_prov1 {
    border-radius: 30px;
    border: 1px solid black;
    background: red;
    color: black;
    padding: 6px;
    width: 50px;
    height: 50px;
}


</style>

<script src="<?php echo base_url('assets/js/pages/ui/tooltips-popovers.js');?>"></script>

    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/>
    <section class="col-md-12 box">
        <div class="container-fluid">
            <div class="block-header">
                
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                              Outstanding Bills
                            </h2>
                        </div>
                        <div class="body">
                          <!-- <div class="row m-t-20">
                                <div class="col-md-12">
                                    <form method="post" role="form" action="">
                                        
                                        <div class="col-md-3">
                                            <b>Company Name </b>
                                        
                                            <select class="form-control" required id="comp" name="cmp">
                                                <option value="<?php echo $cmpName; ?>"><?php echo $cmpName; ?></option>
                                                <?php foreach ($company as $req_item){ ?>
                                                <option value="<?php echo $req_item['name'] ?>"><?php echo $req_item['name'] ?></option>
                                                <?php } ?> 
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                          <button type="submit" class="btn m-t-20 btn-primary">Search</button>
                                        </div>
                                    </form>

                                </div>
                            </div> -->  
                            <br>
                            <div class="top-panel"> 
                              <div class="btn-group pull-right"> 
                                <a class="btn btnStyle btn-lg waves-effect"  href="<?php echo site_url(); ?>/SrController/outstandingBillsExport"> Export to Excel</a>
                                <!-- <button type="button" class="btn btn-primary btn-lg dropdown-toggle" data-toggle="dropdown">Export <span class="caret"></span></button> -->
                                <!-- <ul class="dropdown-menu" role="menu">
                                  <li><a class="dataExport" data-type="excel">XLS</a></li>          
                                </ul> -->
                              </div>
                            </div>
                            <div> 
                              <div class="row cust-tbl">
                                <div class="col-sm-3">
                                  <b>Search Anything</b>
                                  <div class="form-group">
                                    <div class="form-line">
                                       <input type="text" name="searchFor" placeholder="Search..." class="form-control" id="searchKey" onchange="sendRequest();">
                                    </div>
                                  </div>
                                </div>

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
                                <a href="<?php echo site_url('SrController/outstandingBills'); ?>" class="btn btn-sm m-t-15  btn-danger waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </a>
                                </div>
                              </div> 
                              <?php echo $pagination; ?>
                                <table id="outstanding_table" class="table table-bordered cust-tbl">
                                    <thead> 
                                        <tr>
                                            <th>No</th>
                                            <th data-action="sort" data-title="billNo" data-direction="ASC"><span>Bill No</span></th>
                                            <th data-action="sort" data-title="date" data-direction="ASC"><span>Bill Date</span></th>
                                            <th data-action="sort" data-title="retailerName" data-direction="ASC"><span>Retailer</span></th>
                                            <th data-action="sort" data-title="netAmount" data-direction="ASC"><span>Bill</span></th>
                                            <th data-action="sort" data-title="pendingAmt" data-direction="ASC"><span>Pending </span></th>
                                            <th data-action="sort" data-title="salesman" data-direction="ASC"><span>Salesman</span></th>
                                            <th data-action="sort" data-title="routeName" data-direction="ASC"><span>Route</span></th>
                                            <th data-action="sort" data-title="routeName" data-direction="ASC"><span>Days</span></th>
                                            <th data-action="sort" data-title="routeName" data-direction="ASC"><span>Status</span></th>
                                            
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th data-action="sort" data-title="billNo" data-direction="ASC"><span>Bill No</span></th>
                                            <th data-action="sort" data-title="date" data-direction="ASC"><span>Bill Date</span></th>
                                            <th data-action="sort" data-title="retailerName" data-direction="ASC"><span>Retailer</span></th>
                                            <th data-action="sort" data-title="netAmount" data-direction="ASC"><span>Bill </span></th>
                                            <th data-action="sort" data-title="pendingAmt" data-direction="ASC"><span>Pending </span></th>
                                            <th data-action="sort" data-title="salesman" data-direction="ASC"><span>Salesman</span></th>
                                            <th data-action="sort" data-title="routeName" data-direction="ASC"><span>Route</span></th>
                                            <th data-action="sort" data-title="routeName" data-direction="ASC"><span>Days</span></th>
                                            <th data-action="sort" data-title="routeName" data-direction="ASC"><span>Status</span></th>

                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php 
                                        $no=0;
                                            if(!empty($outstanding)){
                                            foreach($outstanding as $data){
                                                  $retailerCode=$this->SrModel->loadRetailer($data['retailerCode']);
                                                  $no++;
                                                  $dt=date_create($data['date']);
                                                  $dt= date_format($dt,'d-M-Y');
                                                  $diff=strtotime(date('Y-m-d'))-strtotime($data['date']);
         
                                        ?>
                                         <?php if($data['isAllocated']==1){ ?>
                                                 <tr style="background-color: #dcd6d5">
                                            <?php }else{ ?>
                                                 <tr>
                                            <?php } ?>
                                            <td><?php echo $no;?></td>
                                            <td><?php echo $data['billNo'];?></td>
                                            <td class="noSpace"><?php echo $dt; ?></td>
											<td class="CellWithComment noSpace"><?php //echo  rtrim($data['salesman'],', '); 
												$retailerName=substr($data['retailerName'], 0, 15);
												echo rtrim($retailerName);?>
												<span class="CellComment"><?php echo $result =substr($data['retailerName'],0); ?></span>
											</td>
                                            <td align="right"><?php echo number_format($data['netAmount']);?></td>
                                            <td align="right"><?php echo number_format($data['pendingAmt']);?></td>
                                 
											<td class="CellWithComment noSpace"><?php //echo  rtrim($data['salesman'],', '); 
												$salesman=substr($data['salesman'], 0, 15);
												echo rtrim($salesman);?>
												<span class="CellComment"><?php echo $result =substr($data['salesman'],0); ?></span>
											</td>
											
											<td class="CellWithComment noSpace"><?php //echo  rtrim($data['salesman'],', '); 
												$routeName=substr($data['routeName'], 0, 15);
												echo rtrim($routeName);?>
												<span class="CellComment"><?php echo $result =substr($data['routeName'],0); ?></span>
											</td>
                                          
                                             <td><?php echo abs(round($diff/86400));?></td>
                                             <td>
                                            <?php
                                                $allocationsHistory=$this->SrModel->getAllocationDetailsByBillHistory('bills',$data['id']);
                                                $officeAllocationsHistory=$this->SrModel->getOfficeAllocationDetailsByBillHistory('bills',$data['id']);
                                                
                                                $alStatus=0;
                                                $ofcStatus=0;
                                                if(!empty($allocationsHistory)){
                                                    $alStatus=1;
                                                }
                                                
                                                if(!empty($officeAllocationsHistory)){
                                                    $ofcStatus=1;
                                                }

                                                if($data['billCurrentStatus']=="Allocated"){
                                                    $data['billCurrentStatus']="Signed";
                                                }

                                                if($data['isAllocated']==1){
                                                    echo '<p style="color:blue">'.$data['billCurrentStatus'].'</p>';
                                                } else if(($data['billCurrentStatus']=="") && ($data['netAmount']==$data['pendingAmt']) && ($data['isDirectDeliveryBill']==0) && ($data['isResendBill']==0) && ($data['isLostBill']==0) && (empty($allocationsHistory)) && (empty($officeAllocationsHistory))){
                                                    echo '<p style="color:red">Unaccounted</p>';
                                                } else if($data['billCurrentStatus']=="Direct Delivery" && $data['netAmount']==$data['pendingAmt'] && $data['isDirectDeliveryBill']=="1"){
                                                    echo '<p style="color:green">Direct Delivery by '.$data['deliveryEmpName'].'</p>';
                                                // } else if($data['netAmount'] == $data['pendingAmt'] && $data['isResendBill']=="0" && $data['isLostBill']=="1"){
                                                //      echo '<p style="color:green">Signed</p>';
                                                // }else if($data['isResendBill']==1){
                                                //     echo '<p style="color:green">Resend</p>';
                                                // } else if($data['isLostBill']==2){
                                                //     echo '<p style="color:green">Lost Bill</p>';
                                                } else if($data['billCurrentStatus'] !==""){
                                                    if($data['billCurrentStatus'] == "Signeds"){
                                                        echo '<p style="color:green">'.$data['billCurrentStatus'].'</p>';
                                                    }else{
                                                        echo '<p style="color:blue">'.$data['billCurrentStatus'].'</p>';
                                                    }
                                                } else{
                                                    echo '<p style="color:green">Signed</p>';
                                                } 
                                                
                                                // $allocations=$this->SrModel->getAllocationDetailsOutstanding('bills',$data['id']);
                                               
                                                // $officeAllocations=$this->SrModel->getOfficeAllocationDetailsByBill('bills',$data['id']);
                                                
                                               
                                                // $status="";
                                                // $allocationNumber="";
                                                // $allocationName="";
                                                // $empName="";

                                                //     if($data['isAllocated']==1){
                                                //         if(!empty($allocations)){
                                                //             echo "<span style='color:blue'>Allocated in : ".$allocations[0]['allocationCode'].'</span>';
                                                //         }

                                                //         if(!empty($officeAllocations)){
                                                //             echo "<span style='color:blue'>Allocated in : ".$officeAllocations[0]['allocationCode'].'</span>';
                                                //         }
                                                //     }else{
                                                //         if($data['pendingAmt']==0){
                                                //             echo "<span style='color:green'> Cleared</span>";
                                                //         }else if($data['isDirectDeliveryBill']==1){
                                                //             echo "<span style='color:green'> Direct Delivery</span>";
                                                //         }else{
                                                //           if(!empty($allocationsHistory) || !empty($officeAllocationsHistory)){
                                                //             if(!empty($allocationsHistory)){
                                                             
                                                //                 $status="";
                                                //                 if($allocationsHistory[0]['isResendBill']){
                                                //                     $status="Resend";
                                                //                 }else if($allocationsHistory[0]['isLostBill']){
                                                //                     $status="Lost Bill";
                                                //                 }else if($allocationsHistory[0]['isLostCheque']){
                                                //                     $status="Lost Cheque";
                                                //                 }else if($allocationsHistory[0]['isBounceCheque']){
                                                //                     $status="Bounce Cheque";
                                                //                 }else if($allocationsHistory[0]['isPendingNeft']){
                                                //                     $status="Pending NEFT";
                                                //                 }else if($allocationsHistory[0]['isBilled']){
                                                //                     $status="Billed";
                                                //                 }else{
                                                //                     $status="Billed";
                                                //                 }
                                                //                 echo "<span style='color:blue'>".$status."</span>";
                                                //                 // echo $status;
                                                //             }

                                                //             if(!empty($officeAllocationsHistory)){
                                                //               echo "<span style='color:green'>Already Allocated in : ".$officeAllocationsHistory[0]['allocationCode'].'</span>';
                                                //             }
                                                //           }else{
                                                //               if($data['pendingAmt']==$data['netAmount']){
                                                //                  echo "<span style='color:red'> Unaccounted</span>";
                                                //               }else{
                                                //                  echo "<span style='color:green'> Accounted</span>";
                                                //               }
                                                              
                                                //           }
                                                //         }
                                                //     } 
                                            ?>
                                                </td>
                                            <td class="noSpace">
                                            <?php if($data['isAllocated']!=1 && $data['pendingAmt'] >0){ ?>

                                            <a id="prDetails" href="javascript:void()" data-id="<?php echo $data['id']; ?>" data-salesman="<?php echo $data['salesman']; ?>" data-billDate="<?php echo $dt; ?>" data-credAdj="<?php echo $data['creditAdjustment']; ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>" data-gst="<?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?>" data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-route="<?php echo $data['routeName']; ?>" data-toggle="modal" data-target="#processModal" ><button class="btn btn-xs process-btn waves-effect waves-float" data-toggle="tooltip" data-placement="bottom" title="Process"><i class="material-icons">touch_app</i></button></a>
                                            <a href="<?php echo site_url('AdHocController/billHistoryInfo/'.$data['id']); ?>" class="btn btn-xs history-btn" data-toggle="tooltip" data-placement="bottom" title="View History"><i class="material-icons">info</i></a>
                                            <a href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs viewBill-btn" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                                                <!-- <button data-toggle="modal" data-id="<?php echo $data['id']; ?>" data-target="#processModal" class="modalLinkProcess btn btn-primary m-t-15 waves-effect">Process</button>
                                           &nbsp;<button data-toggle="modal" data-id="<?php echo $data['id']; ?>" data-target="#fixDebitModal" class="modalLinkFixDebit btn btn-primary m-t-15 waves-effect">Debit</button> -->
                                           <?php  }else{

                                            
                                            // if($data['isAllocated']==1){ 
                                            //     if(!empty($allocations)){
                                            //         $allocationNumber=$allocations[0]['id'];
                                            //         $allocationName=$allocations[0]['allocationCode'];
                                            //         $status="Allocated : ".$allocationName;
                                            //     }

                                            //     if(!empty($officeAllocations)){
                                            //         $allocationNumber=$officeAllocations[0]['id'];
                                            //         $allocationName=$officeAllocations[0]['allocationCode'];
                                            //         $status="Allocated : ".$allocationName;
                                            //     }
                                            // }else{
                                            //     if(!empty($allocationsHistory) || !empty($officeAllocationsHistory)){
                                            //       if(!empty($allocationsHistory)){
                                            //         $status="Past Bill";
                                            //       }
                                            //       if(!empty($officeAllocationsHistory)){
                                            //         $status="Past Bill";
                                            //       }
                                            //     }else{
                                            //         if($data['pendingAmt'] == $data['netAmount']){
                                            //             $status="Unaccounted";
                                            //         }else if($data['deliveryEmpName'] !==""){
                                            //             $status="Direct Delivery";
                                            //         }else if($data['pendingAmt'] <=0){
                                            //             $status= "Bill Cleared";
                                            //         }
                                            //     }
                                            // } 

                                            // echo $status;
                                        ?>
                                        <a href="<?php echo site_url('AdHocController/billHistoryInfo/'.$data['id']); ?>" class="btn btn-xs history-btn" data-toggle="tooltip" data-placement="bottom" title="View History"><i class="material-icons">info</i></a>
                                        <a href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs viewBill-btn" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                                              
                                        <?php

                                            }
                                        ?>
                                                
                                            
                                       </td>
                                        </tr>
                                        <?php 
                                          
                                            }
                                           } 
                                        ?>
                                    
                                    </tbody>
                                </table>
                                <?php echo $pagination; ?>
                            </div>
                        </div>
                    </div>
                </div>
                 
            </div>

        </div>
    </section>
    
    
<?php 
    $projectSessionName="";
    if (isset($this->session->userdata['codeKeyData'])) {
        $projectSessionName= ($this->session->userdata['codeKeyData']['codeKeyValue']);
    }
    $designation = ($this->session->userdata[$projectSessionName]['designation']);
    $des=explode(',',$designation);
?>
  
<div class="container">
  <div class="modal fade" id="processModal" role="dialog" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <center><h4 id="title_name" style="color:#050A30">Bill Transactions </h4></center>
          </div>
          <div class="modal-body">
        
            <div class="body">
                <div class="demo-masked-input">
                    <div class="row clearfix">
                        <div class="row cust-tbl">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <b style="color: #000000;">Bill No : </b> <span style="color: #050A30;" id='bill_no'></span>
                                    <input type="hidden" id="currentBillNo" autocomplete="off" name="currentBillNo" class="form-control"> 
                                    <input type="hidden" id="currentBillId" autocomplete="off" name="currentBillId" class="form-control"> 
                                     <input type="hidden" id="currentBillRetailer" autocomplete="off" name="currentBillRetailer" class="form-control">    
                                </div> 
                                
                                 <div class="col-md-3">
                                   <b style="color: #000000;">Bill Date : </b>  <span style="color: #050A30;" id='bill-date'></span>
                                </div> 
                                <span id='bill_retailer'></span>
                                <!--<div class="col-md-6">-->
                                <!--    <b>Retailer : </b>-->
                                <!--        <span id='bill_retailer'></span>-->
                                <!--</div> -->
                                <div class="col-md-3">
                                    <b style="color: #000000;">Pending Amount : </b><span style="color: #050A30;" id='bill_pendingAmt'></span>
                                    <input type="hidden" id="currentPendingAmt" autocomplete="off" name="currentPendingAmt" class="form-control">   
                                </div>
                            </div>

                            <div class="col-md-12">
                               <!--<div class="col-md-3">-->
                               <!--     <b>Bill Date : </b> <span id='bill-date'></span>-->
                               <!-- </div>-->
                                <div class="col-md-3">
                                    <b style="color: #000000;">Route: </b>  <span style="color: #050A30;" id='bill-route'></span>
                                </div>
                                <div class="col-md-3">
                                    <b style="color: #000000;">Salesman: </b> <span style="color: #050A30;" id='bill-salesman'></span>
                                </div>
                                <div class="col-md-3">
                                    <b style="color: #000000;">GST No. : </b> <span style="color: #050A30;" id='gst'></span>
                                </div>
                                <div class="col-md-3"><span style="display:none;color: #050A30;" class="logo_prov1">CN</span></div>
                            </div>
                        </div> 
                         
                         <br>
                       <div class="row">  
                        <div class="col-md-12">
                            <div class="col-md-12 radio-btns-div">
							<div>
                              <?php if ((in_array('owner', $des)) || (in_array('cashier', $des))){ ?>
                                <input name="group5" type="radio" id="radio_cash" class="with-gap radio-col-red" checked />
                                <label for="radio_cash">Cash</label>
                              <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_cash" class="with-gap radio-col-red" disabled />
                                <label for="radio_cash">Cash</label>
                              <?php } ?> 
						    </div>
							<div>
                              <?php if ((in_array('owner', $des)) || (in_array('cashier', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5" type="radio" id="radio_cheque" class="with-gap radio-col-red"  />
                                <label for="radio_cheque">Cheque</label>
                              <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_cheque" class="with-gap radio-col-red" disabled />
                                <label for="radio_cheque">Cheque</label>
                              <?php } ?> 
                            </div>
							<div>
                              <?php if ((in_array('owner', $des)) || (in_array('cashier', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5" type="radio" id="radio_neft" class="with-gap radio-col-red"  />
                                <label for="radio_neft">NEFT</label>
                              <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_neft" class="with-gap radio-col-red" disabled />
                                <label for="radio_neft">NEFT</label>
                              <?php } ?> 
                            </div>
							<div>
                              <?php if ((in_array('owner', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5" type="radio" id="radio_cd" class="with-gap radio-col-red"  />
                                <label for="radio_cd">CD</label>
                              <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_cd" class="with-gap radio-col-red" disabled />
                                <label for="radio_cd">CD</label>
                              <?php } ?> 
							</div>
						    <div>
                              <?php if ((in_array('owner', $des)) || (in_array('cashier', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5" type="radio" id="radio_debit" class="with-gap radio-col-red" />
                                <label for="radio_debit">Debit to Employee</label>
                              <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_debit" class="with-gap radio-col-red" disabled/>
                                <label for="radio_debit">Debit to Employee</label>
                              <?php } ?> 
							 </div>
							 <div>
                              <?php if ((in_array('owner', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5" type="radio" id="radio_officeAdj" class="with-gap radio-col-red" />
                                <label for="radio_officeAdj">Office Adjustment</label>
                              <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_officeAdj" class="with-gap radio-col-red" disabled/>
                                <label for="radio_officeAdj">Office Adjustment</label>
                              <?php } ?> 
							 </div>
							 <div>
                               <?php if ((in_array('owner', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5" type="radio" id="radio_otherAdj" class="with-gap radio-col-red" />
                                <label for="radio_otherAdj">Other Adjustment</label>
                              <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_otherAdj" class="with-gap radio-col-red" disabled/>
                                <label for="radio_otherAdj">Other Adjustment</label>
                              <?php } ?> 
							  </div>
							  <div>
                              <?php if ((in_array('owner', $des)) || (in_array('godownkeeper', $des))){ ?>
                                <input name="group5" type="radio" id="radio_sr" class="with-gap radio-col-red"  />
                                <label for="radio_sr">SR/FSR</label>
                              <?php }else{ ?>
                                 <input name="group5" type="radio" id="radio_sr" class="with-gap radio-col-red" disabled />
                                <label for="radio_sr">SR/FSR</label>
                              <?php } ?> 
                              </div>
							  <div>
                              <?php if ((in_array('owner', $des)) || (in_array('manager', $des)) || (in_array('senior_manager', $des))){ ?>   
                                <input name="group5" type="radio" id="radio_allocation" class="with-gap radio-col-red"  />
                                <label for="radio_allocation">Add to Open Allocation</label>
                              <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_allocation" class="with-gap radio-col-red" disabled />
                                <label for="radio_allocation">Add to Open Allocation</label>
                              <?php } ?> 
							  </div>
							  <div>
                              <?php if ((in_array('owner', $des)) || (in_array('manager', $des)) || (in_array('senior_manager', $des))){ ?> 
                                <input name="group5" type="radio" id="radio_EmpDelivery" class="with-gap radio-col-red"  />
                                <label for="radio_EmpDelivery">Direct Delivery by Employee</label>
                              <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_EmpDelivery" class="with-gap radio-col-red" disabled />
                                <label for="radio_EmpDelivery">Direct Delivery by Employee</label>
                              <?php } ?> 
							  </div>
							  <div>
                              <?php if ((in_array('owner', $des)) || (in_array('cashier', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5" type="radio" id="radio_debitNote" class="with-gap radio-col-red" />
                                <label for="radio_debitNote">Debit Note</label>
                              <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_debitNote" class="with-gap radio-col-red" disabled/>
                                <label for="radio_debitNote">Debit Note</label>
                              <?php } ?> 
							 </div>
                            </div>
                        </div>
                    </div>

                    <br>

                    <div id="srDiv" style="display: none" class="row">
                       
                    </div>
                    

                    <div id="chequeDiv" style="display: none" class="row">
                        <div class="col-md-12">
                            <div class="col-md-4 cust-tbl">
                                    <b>Employee</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="chequeEmp" tabindex="1" autocomplete="off"  list="chequeEmpList" name="chequeEmp" class="form-control" placeholder="Employee Name">   
                                    <datalist id="chequeEmpList">
                                        <?php
                                            foreach($emp as $data){
                                                $name=$data['name'];
                                        ?>   
                                        <option id="<?php echo $data['id'];?>" value="<?php echo $name;?>"/>
                                        <?php    
                                            }
                                        ?>
                                    </datalist>
                                  </div>
                                  </div>
                                </div> 
                                <div class="col-md-4 cust-tbl">
                                    <b>Amount</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="chequeAmount" tabindex="2" onkeypress="return numbersonly(event)" autocomplete="off" name="chequeAmount" class="form-control" placeholder="Cheque Amount">   
                                    
                                  </div>
                                  </div>
                                </div> 
                               
                               
                            </div>
                            <div class="col-md-12">
                               <div class="col-md-4 cust-tbl">
                                    <b>Cheque Number</b>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="chequeNumber" tabindex="3" onkeyup="checkChars(); " onkeypress="return numbersonly(event)" autocomplete="off" name="chequeNumber" class="form-control" placeholder="Cheque Number">   
                                    
                                  </div>
                                  <p id="error-nwl"></p>
                                  </div>
                                </div> 

                               <div class="col-md-4 cust-tbl">
                                    <b>Cheque Date</b>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="date" id="chequeDate" tabindex="4" onkeypress="return numbersonly(event)" autocomplete="off" name="chequeDate" class="form-control" placeholder="Cheque date">   
                                    
                                  </div>
                                  </div>
                                </div>

                                <div class="col-md-4 cust-tbl">
                                    <b>Bank</b>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="chequeBank" tabindex="5"  list="chequeBankList" autocomplete="off" name="chequeBank" class="form-control" placeholder="Cheque Bank">   
                                      <datalist id="chequeBankList">
                                          <?php
                                              foreach($bank as $data){
                                                  $name=$data['name'];
                                          ?>   
                                          <option id="<?php echo $data['id'];?>" value="<?php echo $name;?>"/>
                                          <?php    
                                              }
                                          ?>
                                      </datalist>
                                  </div>
                                  </div>
                                </div>
                            </div>

                             <div class="col-md-12">
                              <p id="error-chk-all"></p>
                                <div class="col-md-4">
                                    <button id="chequeSaveBtn" type="button" tabindex="6" class="btn btnStyle m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" tabindex="7" class="btn btn-sm btn-danger m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
								
                             </div>
                    </div>



                    <div id="neftDiv" style="display: none" class="row">
                        <div class="col-md-12">
                                <div class="col-md-4 cust-tbl">
                                    <b>Employee</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="neftEmp" autocomplete="off"  list="neftEmpList" name="neftEmp" class="form-control" placeholder="Employee Name">   
                                    <datalist id="neftEmpList">
                                        <?php
                                            foreach($emp as $data){
                                                $name=$data['name'];
                                        ?>   
                                        <option id="<?php echo $data['id'];?>" value="<?php echo $name;?>"/>
                                        <?php    
                                            }
                                        ?>
                                    </datalist>
                                  </div>
                                  </div> 
                                </div> 
                                <div class="col-md-4 cust-tbl">
                                    <b>Amount</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="neftAmount" onkeypress="return numbersonly(event)" autocomplete="off" name="neftAmount" class="form-control" placeholder="NEFT Amount">   
                                    
                                  </div>
                                  </div>
                                </div> 
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4 cust-tbl">
                                    <b>NEFT Number</b>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="neftNumber" autocomplete="off" onkeyup="checkNEFT();" name="neftNumber" class="form-control" placeholder="NEFT Number">   
                                    
                                  </div>
                                  <div id="error-nwl1"></div>
                                </div> 
                                </div> 
                                <div class="col-md-4 cust-tbl">
                                    <b>NEFT Date</b>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="date" id="neftDate" onkeypress="return numbersonly(event)" autocomplete="off" name="neftDate" class="form-control" placeholder="NEFT Date">   
                                    
                                  </div>
                                  </div>
                                </div> 
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <button id="neftSaveBtn" type="button" class="btn btnStyle m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-sm btn-danger m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                    </div>

                    <div id="cdDiv" style="display: none" class="row">
                        <div class="col-md-12">
                            <div class="col-md-4 cust-tbl">
                                    <b>Employee</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="cdEmp" autocomplete="off"  list="cdEmpList" name="cdEmp" class="form-control" placeholder="Employee Name">   
                                    <datalist id="cdEmpList">
                                        <?php
                                            foreach($emp as $data){
                                                $name=$data['name'];
                                        ?>   
                                        <option id="<?php echo $data['id'];?>" value="<?php echo $name;?>"/>
                                        <?php    
                                            }
                                        ?>
                                    </datalist>
                                  </div>
                                  </div>
                                </div> 
                                <div class="col-md-4 cust-tbl">
                                    <b>CD Amount</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="cdAmount" onkeypress="return numbersonly(event)" autocomplete="off" name="cdAmount" class="form-control" placeholder="CD Amount">   
                                    
                                  </div>
                                  </div>
                                </div> 
                            </div>

                             <div class="col-md-12">
                                <div class="col-md-12 cust-tbl">
                                    <b>Remark</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="cdRemark" onpaste="return removeSpaces(this)" autocomplete="off" name="cdRemark" class="form-control" placeholder="Remark">   
                                    
                                  </div>
                                  </div>
                                </div> 
                             </div>

                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <button id="cdSaveBtn" type="button" class="btn btnStyle m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-sm btn-danger m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                    </div>

                    <div id="debitDiv" style="display: none" class="row">
                        <div class="col-md-12">
                            <div class="col-md-4 cust-tbl">
                                    <b>Employee</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="debitEmp" autocomplete="off"  list="debitEmpList" name="debitEmp" class="form-control" placeholder="Employee Name">   
                                    <datalist id="debitEmpList">
                                        <?php
                                            foreach($emp as $data){
                                                $name=$data['name'];
                                        ?>   
                                        <option id="<?php echo $data['id'];?>" value="<?php echo $name;?>"/>
                                        <?php    
                                            }
                                        ?>
                                    </datalist>
                                  </div>
                                  </div>
                                </div> 
                                <div class="col-md-4 cust-tbl">
                                    <b>Debit Amount</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="debitAmount" onkeypress="return numbersonly(event)" autocomplete="off" name="debitAmount" class="form-control" placeholder="Debit Amount">   
                                    
                                  </div>
                                  </div>
                                </div> 
                            </div>

                             <div class="col-md-12">
                                <div class="col-md-12 cust-tbl">
                                    <b>Remark</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="debitRemark" autocomplete="off" name="debitRemark" class="form-control" placeholder="Remark">   
                                    
                                  </div>
                                  </div>
                                </div> 
                             </div>

                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <button id="debitSaveBtn" type="button" class="btn btnStyle m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-sm btn-danger m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                    </div>

                    <div id="debitNote" style="display: none" class="row">
                        <div class="col-md-12">
                            <div class="col-md-4 cust-tbl">
                                    <b>Employee</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="debitNoteEmp" autocomplete="off"  list="debitNoteEmpList" name="debitNoteEmp" class="form-control" placeholder="Employee Name">   
                                    <datalist id="debitNoteEmpList">
                                        <?php
                                            foreach($emp as $data){
                                                $name=$data['name'];
                                        ?>   
                                        <option id="<?php echo $data['id'];?>" value="<?php echo $name;?>"/>
                                        <?php    
                                            }
                                        ?>
                                    </datalist>
                                  </div>
                                  </div>
                                </div> 
                                <div class="col-md-4 cust-tbl">
                                    <b>Debit Note Amount</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="debitNoteAmount" onkeypress="return numbersonly(event)" autocomplete="off" name="debitNoteAmount" class="form-control" placeholder="Debit Amount">   
                                    
                                  </div>
                                  </div>
                                </div> 
                            </div>

                             <div class="col-md-12">
                                <div class="col-md-12 cust-tbl">
                                    <b>Remark</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="debitNoteRemark" autocomplete="off" name="debitNoteRemark" class="form-control" placeholder="Remark">   
                                    
                                  </div>
                                  </div>
                                </div> 
                             </div>

                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <button id="debitNoteSaveBtn" type="button" class="btn btnStyle m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-sm btn-danger m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                    </div>

                    <div id="officeAdjDiv" style="display: none" class="row">
                        <div class="col-md-12">
                            <div class="col-md-4 cust-tbl">
                                    <b>Employee</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="officeAdjEmp" autocomplete="off"  list="officeAdjList" name="officeAdjEmp" class="form-control" placeholder="Employee Name">   
                                    <datalist id="officeAdjList">
                                        <?php
                                            foreach($emp as $data){
                                                $name=$data['name'];
                                        ?>   
                                        <option id="<?php echo $data['id'];?>" value="<?php echo $name;?>"/>
                                        <?php    
                                            }
                                        ?>
                                    </datalist>
                                  </div>
                                  </div>
                                </div> 
                                <div class="col-md-4 cust-tbl">
                                    <b>Office Adjustment Amount</b>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="officeAdjAmount" onkeypress="return numbersonly(event)" autocomplete="off" name="officeAdjAmount" class="form-control" placeholder="Office Adjustment Amount">   
                                    
                                  </div>
                                  </div>
                                </div> 
                            </div>

                             <div class="col-md-12">
                                <div class="col-md-12 cust-tbl">
                                    <b>Remark</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="officeAdjRemark" autocomplete="off" name="officeAdjRemark" class="form-control" placeholder="Remark">   
                                    
                                  </div>
                                  </div>
                                </div> 
                             </div>

                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <button id="officeAdjSaveBtn" type="button" class="btn btnStyle m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-sm btn-danger m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                    </div>

                    <div id="otherAdjDiv" style="display: none" class="row">
                        <div class="col-md-12">
                            <div class="col-md-4 cust-tbl">
                                    <b>Employee</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="otherAdjEmp" autocomplete="off"  list="otherAdjEmpList" name="otherAdjEmp" class="form-control" placeholder="Employee Name">   
                                    <datalist id="otherAdjEmpList">
                                        <?php
                                            foreach($emp as $data){
                                                $name=$data['name'];
                                        ?>   
                                        <option id="<?php echo $data['id'];?>" value="<?php echo $name;?>"/>
                                        <?php    
                                            }
                                        ?>
                                    </datalist>
                                  </div>
                                  </div>
                                </div> 
                                <div class="col-md-4 cust-tbl">
                                    <b>Other Adjustment Amount</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="otherAdjAmount" onkeypress="return numbersonly(event)" autocomplete="off" name="otherAdjAmount" class="form-control" placeholder="Other Adjustment Amount">   
                                    
                                  </div>
                                  </div>
                                </div> 
                            </div>

                             <div class="col-md-12">
                                <div class="col-md-12 cust-tbl">
                                    <b>Remark</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="otherAdjRemark" autocomplete="off" name="otherAdjRemark" class="form-control" placeholder="Remark">   
                                    
                                  </div>
                                  </div>
                                </div> 
                             </div>

                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <button id="otherAdjSaveBtn" type="button" class="btn btnStyle m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-sm btn-danger m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                    </div>

                    <div id="empDeliveryDiv" style="display: none" class="row">
                        <div class="col-md-12">
                            <div class="col-md-4 cust-tbl">
                                    <b>Employee</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="deliveryEmp" autocomplete="off"  list="deliveryEmpList" name="deliveryEmp" class="form-control" placeholder="Employee Name">   
                                    <datalist id="deliveryEmpList">
                                        <?php
                                            foreach($emp as $data){
                                                $name=$data['name'];
                                        ?>   
                                        <option id="<?php echo $data['id'];?>" value="<?php echo $name;?>"/>
                                        <?php    
                                            }
                                        ?>
                                    </datalist>
                                  </div>
                                  </div>
                                </div> 
                               
                            </div>

                             <div class="col-md-12">
                                <div class="col-md-12 cust-tbl">
                                    <b>Remark</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="deliveryRemark" autocomplete="off" name="deliveryRemark" class="form-control" placeholder="Remark">   
                                    
                                  </div>
                                  </div>
                                </div> 
                             </div>

                          
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <button id="deliveryEmpSaveBtn" type="button" class="btn btnStyle m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-sm btn-danger m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                    </div>

                    <div id="allocationDiv" style="display: none" class="row">
                        <div class="col-md-12">
                            <div class="col-md-4 cust-tbl">
                                    <b>Open Allocations</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="allocationCode" autocomplete="off"  list="allocationCodeList" name="allocationCode" class="form-control" placeholder="Select Allocation Number">   
                                    <datalist id="allocationCodeList">
                                        <?php
                                            foreach($currentAllocations as $data){
                                                $name=trim($data['allocationCode']).' : '.trim($data['rname']);
                                        ?>   
                                        <option id="<?php echo $data['id'];?>" value="<?php echo $name;?>"/>
                                        <?php    
                                            }
                                        ?>
                                    </datalist>
                                  </div>
                                  </div>
                                </div> 
                                 
                            </div>


                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <button id="allocationSaveBtn" type="button" class="btn btnStyle m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-sm btn-danger m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                    </div>

                    <div id="cashDiv" class="row">
                        <div class="col-md-12">
                                <div class="col-md-4 cust-tbl">
                                    <b>Employee</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="cashEmp" autocomplete="off"  list="cashEmpList" name="cashEmp" class="form-control" placeholder="Employee Name">   
                                    <datalist id="cashEmpList">
                                        <?php
                                            foreach($emp as $data){
                                                $name=$data['name'];
                                        ?>   
                                        <option id="<?php echo $data['id'];?>" value="<?php echo $name;?>"/>
                                        <?php    
                                            }
                                        ?>
                                    </datalist>
                                  </div>
                                  </div>
                                </div> 

                                <div class="col-md-4 cust-tbl">
                                    <b>Cash Amount</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                      </span>

                                      <div class="form-line">
                                         <input type="text" id="tempCashAmt" onkeypress="return numbersonly(event)" autocomplete="off" name="tempCashAmt" class="form-control" placeholder="Cash Amount">   
                                      </div>
                                  </div>
                                </div>

                            </div>
                    
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <table class="table table-bordered cust-tbl" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th><center>Denominations</center></th>
                                            <th><center>Received Notes</center></th>
                                            <th><center>Value</center></th>
                                            <th><center>Denominations</center></th>
                                            <th><center>Received Notes</center></th>
                                            <th><center>Value</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td align="right">2000</td>
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersWithDash(event)" onkeyup="calMoney();" type="text" name="add2000" id="add2000"autocomplete="off" class="form-control">
                                        </td>
                                        <td align="right"><span id="ad2000"></span></td>
                                   
                                        <!-- <td align="right">1000</td>
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersWithDash(event)" onkeyup="calMoney();" type="text" name="add1000" id="add1000"autocomplete="off" class="form-control">
                                        </td>
                                        <td align="right"><span id="ad1000"></span></td> -->

                                        <td align="right">500</td>
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersWithDash(event)" onkeyup="calMoney();" type="text" name="add500" id="add500"autocomplete="off" class="form-control">
                                        </td>
                                        <td align="right"><span id="ad500"></span></td>
                                    </tr>
                                     <tr>
                                        <td align="right">200</td>
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersWithDash(event)" onkeyup="calMoney();" type="text" name="add200" id="add200"autocomplete="off" class="form-control">
                                        </td>
                                        <td align="right"><span id="ad200"></span></td>

                                        <td align="right">100</td>
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersWithDash(event)" onkeyup="calMoney();" type="text" name="add100" id="add100"autocomplete="off" class="form-control">
                                        </td>
                                        <td align="right"><span id="ad100"></span></td>
                                    </tr>
                                     <tr>
                                        <td align="right">50</td>
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersWithDash(event)" onkeyup="calMoney();" type="text" name="add50" id="add50"autocomplete="off" class="form-control">
                                        </td>
                                        <td align="right"><span id="ad50"></span></td>

                                        <td align="right">20</td>
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersWithDash(event)" onkeyup="calMoney();" type="text" name="add20" id="add20" autocomplete="off" class="form-control">
                                        </td>
                                        <td align="right"><span id="ad20"></span></td>
                                    </tr>
                                     <tr>
                                        <td align="right">10</td>
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersWithDash(event)" onkeyup="calMoney();" type="text" name="add10" id="add10"autocomplete="off" class="form-control">
                                        </td>
                                        <td align="right"><span id="ad10"></span></td>

                                        <td align="right">Coins</td>
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersWithDash(event)" onkeyup="calMoney();" type="text" name="coin" id="coin"autocomplete="off" class="form-control">
                                        </td>
                                        <td align="right"><span id="coins"></span></td>
                                    </tr>
                                    <tr>
                                        
                                        <td align="right">Total Actual</td>
                                        <td align="right"><span id="totalActual"></span></td>
                                        <td align="center">
                                            <input style="height:25px;width: 80%" type="hidden" name="collectedAmt" id="collectedAmt" class="form-control">
                                        </td>

                                        <td align="right">Short/Excess</td>
                                        <td align="right"><span id="shortExcesstotalActual"></span></td>
                                        <td align="center">
                                          <td align="center">
                                              <input style="height:25px;width: 80%" type="hidden" name="shortExcessCollectedAmt" id="shortExcessCollectedAmt" class="form-control">
                                          </td>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                         <div class="col-md-12">
                          <?php if ((in_array('owner', $des)) || (in_array('cashier', $des))){   ?>
                            <div class="row clearfix">
                                <div class="col-md-5">
                                    <button id="cashSaveBtn" type="button" class="btn btnStyle m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-sm btn-danger m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                            <?php } else { ?>
                              <div class="row clearfix">
                                <div class="col-md-5">
                                    <button id="cashSaveBtn" disabled type="button" class="btn btnStyle m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-sm btn-danger m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div><!-- Cash Module -->
                    </div>
                </div>
            </div>
                     
          </div>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('/layouts/footerDataTable'); ?>

<script type="text/javascript">
    $(document).on('click','#radio_cheque',function(){
        $('#srDiv').css("display", "none");
        $('#cashDiv').css("display", "none");
        $('#neftDiv').css("display", "none");
        $('#cdDiv').css("display", "none");
        $('#debitDiv').css("display", "none");
        $('#officeAdjDiv').css("display", "none");
        $('#otherAdjDiv').css("display", "none");
        $('#allocationDiv').css("display", "none");
        $('#empDeliveryDiv').css("display", "none");
        $('#chequeDiv').css("display", "block");
        $('#debitNote').css("display", "none");
    });
   
    $(document).on('click','#radio_cash',function(){
        $('#srDiv').css("display", "none");
        $('#cashDiv').css("display", "block");
        $('#neftDiv').css("display", "none");
        $('#chequeDiv').css("display", "none");
        $('#cdDiv').css("display", "none");
        $('#officeAdjDiv').css("display", "none");
        $('#otherAdjDiv').css("display", "none");
        $('#allocationDiv').css("display", "none");
        $('#debitDiv').css("display", "none");
        $('#empDeliveryDiv').css("display", "none");
        $('#debitNote').css("display", "none");

        $('#add2000').val("");
        // $('#add1000').val("");
        $('#add500').val("");
        $('#add200').val("");
        $('#add100').val("");
        $('#add50').val("");
        $('#add20').val("");
        $('#add10').val("");
        $('#coin').val("");
    });

   

    $('#processModal').on('hidden.bs.modal', function () {
      $(this)
          .find("input:not([type=hidden]),textarea,select")
          .val('')
          .end()
          .find("input[type=checkbox], input[type=radio]")
          .prop("checked", "")
          .end();

        $('#srDiv').css("display", "none");
        $('#cashDiv').css("display", "block");
        $('#neftDiv').css("display", "none");
        $('#chequeDiv').css("display", "none");
        $('#cdDiv').css("display", "none");
        $('#officeAdjDiv').css("display", "none");
        $('#otherAdjDiv').css("display", "none");
        $('#debitDiv').css("display", "none");
        $('#allocationDiv').css("display", "none");
        $('#empDeliveryDiv').css("display", "none");
        $('#debitNote').css("display", "none");
        $('#radio_cash').prop('checked', true);
  });

    $(document).on('click','#radio_neft',function(){
        $('#srDiv').css("display", "none");
        $('#cashDiv').css("display", "none");
        $('#neftDiv').css("display", "block");
        $('#chequeDiv').css("display", "none");
        $('#cdDiv').css("display", "none");
        $('#officeAdjDiv').css("display", "none");
        $('#otherAdjDiv').css("display", "none");
        $('#allocationDiv').css("display", "none");
        $('#debitDiv').css("display", "none");
        $('#empDeliveryDiv').css("display", "none");
        $('#debitNote').css("display", "none");
    });

    $(document).on('click','#radio_cd',function(){
        $('#srDiv').css("display", "none");
        $('#cashDiv').css("display", "none");
        $('#neftDiv').css("display", "none");
        $('#chequeDiv').css("display", "none");
        $('#cdDiv').css("display", "block");
        $('#officeAdjDiv').css("display", "none");
        $('#otherAdjDiv').css("display", "none");
        $('#allocationDiv').css("display", "none");
        $('#debitDiv').css("display", "none");
        $('#empDeliveryDiv').css("display", "none");
        $('#debitNote').css("display", "none");
    });

    $(document).on('click','#radio_debit',function(){
        $('#srDiv').css("display", "none");
        $('#cashDiv').css("display", "none");
        $('#neftDiv').css("display", "none");
        $('#chequeDiv').css("display", "none");
        $('#cdDiv').css("display", "none");
        $('#officeAdjDiv').css("display", "none");
        $('#otherAdjDiv').css("display", "none");
        $('#allocationDiv').css("display", "none");
        $('#debitDiv').css("display", "block");
        $('#empDeliveryDiv').css("display", "none");
        $('#debitNote').css("display", "none");
    });

    $(document).on('click','#radio_officeAdj',function(){
        $('#srDiv').css("display", "none");
        $('#cashDiv').css("display", "none");
        $('#neftDiv').css("display", "none");
        $('#chequeDiv').css("display", "none");
        $('#cdDiv').css("display", "none");
        $('#officeAdjDiv').css("display", "block");
        $('#otherAdjDiv').css("display", "none");
        $('#allocationDiv').css("display", "none");
        $('#debitDiv').css("display", "none");
        $('#empDeliveryDiv').css("display", "none");
        $('#debitNote').css("display", "none");
    });

    $(document).on('click','#radio_otherAdj',function(){
        $('#srDiv').css("display", "none");
        $('#cashDiv').css("display", "none");
        $('#neftDiv').css("display", "none");
        $('#chequeDiv').css("display", "none");
        $('#cdDiv').css("display", "none");
        $('#officeAdjDiv').css("display", "none");
        $('#otherAdjDiv').css("display", "block");
        $('#allocationDiv').css("display", "none");
        $('#debitDiv').css("display", "none");
        $('#empDeliveryDiv').css("display", "none");
        $('#debitNote').css("display", "none");
    });

    $(document).on('click','#radio_sr',function(){
        $('#srDiv').css("display", "block");
        $('#cashDiv').css("display", "none");
        $('#neftDiv').css("display", "none");
        $('#chequeDiv').css("display", "none");
        $('#cdDiv').css("display", "none");
        $('#officeAdjDiv').css("display", "none");
        $('#otherAdjDiv').css("display", "none");
        $('#allocationDiv').css("display", "none");
        $('#debitDiv').css("display", "none");
        $('#empDeliveryDiv').css("display", "none");
        $('#debitNote').css("display", "none");
    });

    $(document).on('click','#radio_allocation',function(){
        $('#srDiv').css("display", "none");
        $('#cashDiv').css("display", "none");
        $('#neftDiv').css("display", "none");
        $('#chequeDiv').css("display", "none");
        $('#cdDiv').css("display", "none");
        $('#officeAdjDiv').css("display", "none");
        $('#otherAdjDiv').css("display", "none");
        $('#allocationDiv').css("display", "block");
        $('#debitDiv').css("display", "none");
        $('#empDeliveryDiv').css("display", "none");
        $('#debitNote').css("display", "none");
    });

     $(document).on('click','#radio_EmpDelivery',function(){
        $('#srDiv').css("display", "none");
        $('#cashDiv').css("display", "none");
        $('#neftDiv').css("display", "none");
        $('#chequeDiv').css("display", "none");
        $('#cdDiv').css("display", "none");
        $('#officeAdjDiv').css("display", "none");
        $('#otherAdjDiv').css("display", "none");
        $('#allocationDiv').css("display", "none");
        $('#debitDiv').css("display", "none");
        $('#empDeliveryDiv').css("display", "block");
        $('#debitNote').css("display", "none");
    });

    $(document).on('click','#radio_debitNote',function(){
        $('#srDiv').css("display", "none");
        $('#cashDiv').css("display", "none");
        $('#neftDiv').css("display", "none");
        $('#chequeDiv').css("display", "none");
        $('#cdDiv').css("display", "none");
        $('#officeAdjDiv').css("display", "none");
        $('#otherAdjDiv').css("display", "none");
        $('#allocationDiv').css("display", "none");
        $('#debitDiv').css("display", "none");
        $('#empDeliveryDiv').css("display", "none");
        $('#debitNote').css("display", "block");
    });
</script>

<script>

    $(document).on('click','#prDetails',function(){
        var id=$(this).attr('data-id');
        var billNo=$(this).attr('data-billNo');
        var retailerName=$(this).attr('data-retailerName');
        var pendingAmt=$(this).attr('data-pendingAmt');
        pendingAmt=parseInt(pendingAmt); 
        
        // var nf = new Intl.NumberFormat();
        // pendingAmt=nf.format(pendingAmt);
        // pendingAmt=pendingAmt.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        var gst=$(this).attr('data-gst');
        var route=$(this).attr('data-route');
        
        var credAdj=$(this).attr('data-credAdj');
        credAdj=parseInt(credAdj);
        // alert(credAdj);
        if(credAdj>0){
            // credAdj=nf.format(credAdj);
            $('.logo_prov').text('CN : '+credAdj);
             $(".logo_prov").show();
        }else{
             $(".logo_prov").hide();
        }

        var billDate=$(this).attr('data-billDate');
        var salesman=$(this).attr('data-salesman');
        
        $('#currentPendingAmt').val(pendingAmt);
        $('#currentBillId').val(id);
        $('#currentBillNo').val(billNo);
        $('#currentBillRetailer').val(retailerName);
        $('#bill_no').text(billNo);
        $('#gst').text(gst);
        $('#routeDetail').text(route);
        $('#title_name').text(retailerName);
        // $('#bill_retailer').text(retailerName);
        $('#bill_pendingAmt').text(pendingAmt);
        $('#bill-date').text(billDate);
         $('#bill-salesman').text(salesman);
         $('#bill-route').text(route);
    });


//  $(document).ready(function(){
//     $('#prDetails').click(function(){
//         var id=$(this).attr('data-id');

//         // alert(id);die();
//             $.ajax({
//                 url : "<?php echo site_url('BillTransactionController/neftModal');?>",
//                 method : "POST",
//                 data : {id: id},
//                 success: function(data){
//                   $('.mods').html(data);
//                 }
//             });
//     });
// });
</script>

<script type="text/javascript">
    $(document).on('click','#searchInfo',function(){
        var billNo=$('#billNo').val();
        var billId = $('#billData').find('option[value="'+billNo+'"]').attr('id');
        if(billNo==''){
            alert('Please enter bill no.');
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('AdHocController/billInfo');?>",
                data:{billNo:billNo,billId:billId},
                success: function (data) {
                    // alert(data);
                    $('#hideInfo').html(data);
                    $('#billNo').val('');
                }  
            });
        }
        
    });
</script>

<script type="text/javascript">
    $(document).on('click','#radio_sr',function(){
        var currentBillId=$('#currentBillId').val();
        var currentBillNo=$('#currentBillNo').val();
        var currentBillRetailer=$('#currentBillRetailer').val();
        var billNoText=currentBillNo+' : '+currentBillRetailer;

        if(currentBillId==''){
            alert('Please enter bill no.');
        }else{ 
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('BillTransactionController/getSrDetails');?>",
                data:{currentBillId:currentBillId},
                success: function (data) {
                    
                    $('#srDiv').html(data);
                }   
            });
        }
        
    });
</script>

<script type="text/javascript">
  $(document).on('click','#fsrBtn',function(e){
    if(!e.detail || e.detail == 1){

       e.preventDefault();
       var el = $(this);
       el.prop('disabled', true);
       setTimeout(function(){el.prop('disabled', false); }, 1000);

        var billId=$(this).attr('data-id');
        var currentBillId=$('#currentBillId').val();
        var currentBillNo=$('#currentBillNo').val();
        var currentBillRetailer=$('#currentBillRetailer').val();
        var billNoText=currentBillNo+' : '+currentBillRetailer;

        if(billId==''){
            alert('Please enter bill no.');
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('BillTransactionController/confirmFSR');?>",
                data:{billId:billId},
                success: function (data) {
                  // alert(data);die();
                    if(data.trim()=="Record Updated"){
                        alert(data);
                        window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
                    }else{
                        alert(data);
                    }
                }  
            });
        }
        }
    });
</script>

<script type="text/javascript">
  $(document).on('click','#srBtn',function(e){
    // alert('heheyye');die();
    if(!e.detail || e.detail == 1){

         e.preventDefault();
         var el = $(this);
         el.prop('disabled', true);
         setTimeout(function(){el.prop('disabled', false); }, 1000);
      // var msg= document.getElementById('all_id').innerHTML;
      var msg=$('#all_id').text();
        if(msg===''||msg===null){
            
        }else{
            die();
        }
      var productName = $("input[name='productName[]']").map(function(){return $(this).val();}).get();
      var mrp = $("input[name='mrp[]']").map(function(){return $(this).val();}).get();
      var qty = $("input[name='qty[]']").map(function(){return $(this).val();}).get();
      var fsReturnQty = $("input[name='fsReturnQty[]']").map(function(){return $(this).val();}).get();
      var netAmount = $("input[name='netAmount[]']").map(function(){return $(this).val();}).get();
      var selAmount = $("input[name='selAmount[]']").map(function(){return $(this).val();}).get();
      var id = $("input[name='id[]']").map(function(){return $(this).val();}).get();
      var returnedQty = $("input[name='returnedQty[]']").map(function(){return $(this).val();}).get();
      var returnAmt = $("input[name='returnAmt[]']").map(function(){return $(this).val();}).get();
      var billId=$('#billId_id').val(); 

      var currentBillId=$('#currentBillId').val();
      var currentBillNo=$('#currentBillNo').val();
      var currentBillRetailer=$('#currentBillRetailer').val();
      var billNoText=currentBillNo+' : '+currentBillRetailer;

      $.ajax({
          type: "POST",
          url:"<?php echo site_url('BillTransactionController/updateSRCreditAdj');?>",
          data:{billId:billId,productName:productName,mrp:mrp,qty:qty,fsReturnQty:fsReturnQty,netAmount:netAmount,selAmount:selAmount,id:id,returnedQty:returnedQty,returnAmt:returnAmt,currentBillId:currentBillId},
          success: function (data) {
            // alert(data);die();
            if(data.trim()=="SR Amount is greater than pending amount." || data.trim()=="Credit Adjustment Bill. Sale Return can not be more than pending amount."){
              alert(data);
            }else{
              window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
            }
          }  
      });
    }
  });
</script>



<script type="text/javascript">
    $(document).on('click','#cashSaveBtn',function(e){
      $("#cashSaveBtn").attr("disabled", true);

        e.preventDefault();
         var el = $(this);
         el.prop('disabled', true);
         setTimeout(function(){el.prop('disabled', false); }, 1000);

      // if(!e.detail || e.detail == 1){
        var a2000=$('#add2000').val().trim();
        // var a1000=$('#add1000').val();
        var a500=$('#add500').val().trim();
        var a200=$('#add200').val().trim();
        var a100=$('#add100').val().trim();
        var a50=$('#add50').val().trim();
        var a20=$('#add20').val().trim();
        var a10=$('#add10').val().trim();
        var coin=$('#coin').val().trim();

        a2000 = a2000.replace(",","");
        a500 = a500.replace(",","");
        a200 = a200.replace(",","");
        a100 = a100.replace(",","");
        a50 = a50.replace(",","");
        a10 = a10.replace(",","");
        coin = coin.replace(",","");


        if(a2000 ==""){
            a2000=0;
        }
        // if(a1000 ==""){
        //     a1000=0;
        // }
        if(a500 ==""){
            a500=0;
        }
        if(a200 ==""){
            a200=0;
        }
        if(a100 ==""){
            a100=0;
        }
        if(a50 ==""){
            a50=0;
        }
        if(a20 ==""){
            a20=0;
        }
        if(a10 ==""){
            a10=0;
        }
        if(coin ==""){
            coin=0;
        }

        var empName=$('#cashEmp').val();
        var empId = $('#cashEmpList').find('option[value="'+empName+'"]').attr('id');
        var collectedAmt=$('#collectedAmt').val();
        var shortExcessCollectedAmt=$('#shortExcessCollectedAmt').val();
        var tempCashAmt=$('#tempCashAmt').val().trim();
        tempCashAmt = tempCashAmt.replace(",","");

        if(tempCashAmt>shortExcessCollectedAmt && (shortExcessCollectedAmt !=0)){
          alert('Cash amount is less than entered amount');die();
        }

        if(collectedAmt>tempCashAmt && (shortExcessCollectedAmt !=0)){
          alert('Cash amount is more than entered amount');die();
        }
        
        var currentPendingAmt=$('#currentPendingAmt').val();
        var currentBillId=$('#currentBillId').val();
        var currentBillNo=$('#currentBillNo').val();
        var currentBillRetailer=$('#currentBillRetailer').val();
        var billNoText=currentBillNo+' : '+currentBillRetailer;
        
        if(empName==''){
            alert('Please select employee.');die();
        }else if(empName!=''){
            if (typeof empId === "undefined") {
                alert('Please select correct employee.');die();
            }
        }

        if(collectedAmt=='' || collectedAmt==0){
            alert('Please enter cash details.');die();
        }

        if(parseInt(currentPendingAmt) < parseInt(collectedAmt)){
            alert('Cash amount should not be greater than Pending amount');die();
        }
        
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('BillTransactionController/cashCollection');?>",
            data:{empName:empName,empId:empId,collectedAmt:collectedAmt,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId,a2000:a2000,a500:a500,a200:a200,a100:a100,a50:a50,a20:a20,a10:a10,coin:coin},
            success: function (data) {
                if(data.trim()=="Record updated"){
                    $('#cashEmp').val("");
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
                }else{
                    alert(data);
                }
            }  
        });
        
        // }
    });
    
</script>

<script>
function checkChars()
{
    var chequeNo = document.getElementById('chequeNumber').value;
    // var message = document.getElementById('error-nwl');
    var goodColor = "#66cc66";
    var badColor = "#ff6666";

    if (chequeNo.length===0) {
      document.getElementById('error-nwl').innerHTML = ""
        return;
    } else if(chequeNo.length > 6 || chequeNo.length <6) {
        document.getElementById('error-nwl').style.color = badColor;
        document.getElementById('error-nwl').innerHTML = "Please enter only 6 digit!";
        return;
    } else {
        document.getElementById('error-nwl').innerHTML = ""
        return;
    }
}

function dupChequeEntry(){
    var date = document.getElementById('chequeDate').value;
    var chequeNo = document.getElementById('chequeNumber').value;
    var bank = document.getElementById('chequeBank').value;
    var billAmount = document.getElementById('chequeAmount').value;
    var message = document.getElementById('error-nwl').innerText;


    if((chequeNo !="" && bank !="" && billAmount !="")){
        $.ajax(
        {
            type:"post",
            url: "<?php echo base_url(); ?>index.php/CashAndChequeController/checkChequeDetails",
            data:{chequeDate:date,chequeNo:chequeNo,payAmount:billAmount},
            success:function(response)
            {
              if(response !=""){
                document.getElementById('error-nwl').innerText = response;
                return false;
              }else{
                document.getElementById('error-nwl').innerText = '';
                return true;
              }
            }
        });
      
    }
}
</script>



<script type="text/javascript">
    $(document).on('click','#chequeSaveBtn',function(e){
      $("#chequeSaveBtn").attr("disabled", true);
      if(!e.detail || e.detail == 1){
        e.preventDefault();
         var el = $(this);
         el.prop('disabled', true);
         setTimeout(function(){el.prop('disabled', false); }, 1000);

        var empName=$('#chequeEmp').val();
        var empId = $('#chequeEmpList').find('option[value="'+empName+'"]').attr('id');
        var chequeBank=$('#chequeBank').val();
        var bankId="";
        if(chequeBank !==""){
           bankId = $('#chequeBankList').find('option[value="'+chequeBank+'"]').attr('id');
        }
        
        var chequeAmount=$('#chequeAmount').val();
        var chequeNumber=$('#chequeNumber').val();
        var chequeDate=$('#chequeDate').val();
        var currentPendingAmt=$('#currentPendingAmt').val();
        var currentBillId=$('#currentBillId').val();
        var currentBillNo=$('#currentBillNo').val();
        var currentBillRetailer=$('#currentBillRetailer').val();
        var billNoText=currentBillNo+' : '+currentBillRetailer;

        // alert(chequeDate);die();

        var chequeAmount = Number(chequeAmount.replace(/,/g, '')); 

        // alert(empName+' n, '+empId+' eid, '+chequeBank+' bnk,  '+bankId+' bid,  '+chequeAmount+' camt, '+chequeNumber+' cnum, '+chequeDate);die();

        if(empName !=="" && chequeAmount !=="" && chequeBank === "" && chequeNumber ==="" && chequeDate ===""){
            if(empName==''){
                alert('Please select employee.');die();
            }else if(empName!=''){
                if (typeof empId === "undefined") {
                    alert('Please select correct employee.');die();
                }
            }
            
            if(chequeAmount=='' || chequeAmount==0){
                alert('Please enter cheque amount.');die();
            }

            if(parseInt(currentPendingAmt) < parseInt(chequeAmount)){
                alert('Cheque amount should not be greater than Pending amount');die();
            }
           
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('BillTransactionController/chequeCollection');?>",
                data:{empName:empName,empId:empId,chequeBank:chequeBank,chequeAmount:chequeAmount,chequeNumber:chequeNumber,chequeDate:chequeDate,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId},
                success: function (data) {
                    if(data.trim()=="Record updated"){
                        $('#chequeEmp').val("");
                        $('#chequeAmount').val("");
                        alert(data);
                        window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
                    }else{
                        alert(data);
                    }
                }  
            });
        }else if(empName !=="" && chequeAmount !=="" && chequeBank !== "" && chequeNumber !=="" && chequeDate !==""){
            var err=$('#error-nwl').text();
            if(err !==""){
              die();
            }
            
            if(empName==''){
                alert('Please select employee.');die();
            }else if(empName!=''){
                if (typeof empId === "undefined") {
                    alert('Please select correct employee.');die();
                }
            }

            if(chequeBank==''){
                alert('Please select bank.');die();
            }else if(chequeBank!=''){
                if (typeof bankId === "undefined") {
                    alert('Please select correct bank.');die();
                }
            }

            if(chequeAmount=='' || chequeAmount==0){
                alert('Please enter cheque amount.');die();
            }

            if(chequeNumber=='' || chequeNumber==0){
                alert('Please enter cheque number.');die();
            }
            if(chequeDate=='' || chequeDate==0){
                alert('Please select cheque date.');die();
            }

            if(parseInt(currentPendingAmt) < parseInt(chequeAmount)){
                alert('Cheque amount should not be greater than Pending amount');die();
            }

            $.ajax({
                type:"post",
                url: "<?php echo base_url(); ?>index.php/CashAndChequeController/chequeDetailsVerify",
                data:{chequeDate:chequeDate,chequeNo:chequeNumber,chequeBank:chequeBank},
                success:function(response)
                {
                  if(response.trim() != ""){
                    if (confirm('Cheque already present. Do you want do club this cheque. ?')) {
                        $.ajax({
                          type: "POST",
                          url:"<?php echo site_url('BillTransactionController/chequeCollection');?>",
                          data:{empName:empName,empId:empId,chequeBank:chequeBank,chequeAmount:chequeAmount,chequeNumber:chequeNumber,chequeDate:chequeDate,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId},
                          success: function (data) {
                              if(data.trim()=="Record updated"){
                                  $('#chequeEmp').val("");
                                  $('#chequeAmount').val("");
                                  alert(data);
                                  window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
                              }else{
                                  alert(data);
                              }
                          }  
                      });
                    } else {
                        // window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
                    }
                  }else{
                    $.ajax({
                          type: "POST",
                          url:"<?php echo site_url('BillTransactionController/chequeCollection');?>",
                          data:{empName:empName,empId:empId,chequeBank:chequeBank,chequeAmount:chequeAmount,chequeNumber:chequeNumber,chequeDate:chequeDate,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId},
                          success: function (data) {
                              if(data.trim()=="Record updated"){
                                  $('#chequeEmp').val("");
                                  $('#chequeAmount').val("");
                                  alert(data);
                                  window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
                              }else{
                                  alert(data);
                              }
                          }  
                      });
                  }
                }
            });
            
            
        }else{
          alert('please enter specific details');die();
        }
      }
    });
    
</script>

<script>
  function checkNEFT() {
      var neftNo = document.getElementById('neftNumber').value;
      var message = document.getElementById('error-nwl1');
      var goodColor = "#66cc66";
      var badColor = "#ff6666";
      if (neftNo.length===0) {
          message.innerHTML = ""
          return;
      } else if(neftNo.length < 6) {
          message.style.color = badColor;
          message.innerHTML = "Please enter atleast 6 alphanumerical!"
          return;
      } else {
          message.innerHTML = ""
          return;
      }
  }
</script>

<script type="text/javascript">
    $(document).on('click','#neftSaveBtn',function(e){
      $("#neftSaveBtn").attr("disabled", true);
      if(!e.detail || e.detail == 1){
         e.preventDefault();
         var el = $(this);
         el.prop('disabled', true);
         setTimeout(function(){el.prop('disabled', false); }, 1000);

        var empName=$('#neftEmp').val();
        var empId = $('#neftEmpList').find('option[value="'+empName+'"]').attr('id');
        var neftAmount=$('#neftAmount').val();
        var neftNumber=$('#neftNumber').val();
        var neftDate=$('#neftDate').val();
        var currentPendingAmt=$('#currentPendingAmt').val();
        var currentBillId=$('#currentBillId').val();
        var currentBillNo=$('#currentBillNo').val();
        var currentBillRetailer=$('#currentBillRetailer').val();
        var billNoText=currentBillNo+' : '+currentBillRetailer;

        var neftAmount = Number(neftAmount.replace(/,/g, '')); 
        
        if(empId !=="" && neftAmount !=="" && neftNumber ==="" && neftDate ===""){
            if(empName==''){
                alert('Please select employee.');die();
            }else if(empName!=''){
                if (typeof empId === "undefined") {
                    alert('Please select correct employee.');die();
                }
            }

            if(neftAmount=='' || neftAmount==0){
                alert('Please enter NEFT amount.');die();
            }

            if(parseInt(currentPendingAmt) < parseInt(neftAmount)){
                alert('Cheque amount should not be greater than Pending amount');die();
            }
            
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('BillTransactionController/neftCollection');?>",
                data:{empName:empName,empId:empId,neftAmount:neftAmount,neftNumber:neftNumber,neftDate:neftDate,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId},
                success: function (data) {
                    if(data.trim()=="Record updated"){
                        $('#neftEmp').val("");
                        $('#neftAmount').val("");
                        alert(data);
                        window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
                    }else{
                        alert(data);
                    }
                }  
            });
        }else if(empId !=="" && neftAmount !=="" && neftNumber !=="" && neftDate !==""){
            var err=$('#error-nwl1').text();
            if(err !==""){
              die();
            }

            if(empName==''){
                alert('Please select employee.');die();
            }else if(empName!=''){
                if (typeof empId === "undefined") {
                    alert('Please select correct employee.');die();
                }
            }

            if(neftAmount=='' || neftAmount==0){
                alert('Please enter cheque amount.');die();
            }

            if(parseInt(currentPendingAmt) < parseInt(neftAmount)){
                alert('Cheque amount should not be greater than Pending amount');die();
            }

            $.ajax({
                    type:"post",
                    url: "<?php echo base_url(); ?>index.php/CashAndChequeController/neftDetailsVerifyWithoutAmount",
                    data:{neftDate:neftDate,neftNo:neftNumber,billAmount:neftAmount},
                    success:function(response)
                    {
                      if(response.trim() != ""){
                        if (confirm('NEFT already present. Do you want club this NEFT. ?')) {
                          $("#neftSaveBtn").attr("disabled", true);
                            $.ajax({
                                type: "POST",
                                url:"<?php echo site_url('BillTransactionController/neftCollection');?>",
                                data:{empName:empName,empId:empId,neftAmount:neftAmount,neftNumber:neftNumber,neftDate:neftDate,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId},
                                success: function (data) {
                                    if(data.trim()=="Record updated"){
                                        $('#neftEmp').val("");
                                        $('#neftAmount').val("");
                                        alert(data);
                                        window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
                                    }else{
                                        alert(data);
                                    }
                                }  
                            });
                        } else {
                            // window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
                        }
                      }else{
                        $.ajax({
                            type: "POST",
                            url:"<?php echo site_url('BillTransactionController/neftCollection');?>",
                            data:{empName:empName,empId:empId,neftAmount:neftAmount,neftNumber:neftNumber,neftDate:neftDate,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId},
                            success: function (data) {
                                if(data.trim()=="Record updated"){
                                    $('#neftEmp').val("");
                                    $('#neftAmount').val("");
                                    alert(data);
                                    window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
                                }else{
                                    alert(data);
                                }
                            }  
                        });
                      }
                    }
                });
            
            
        }else{
             alert('Please enter specific details');die();
        }
      }
    });
</script>

<script type="text/javascript">
  $(document).on('click','#allocationSaveBtn',function(e){
    $("#allocationSaveBtn").attr("disabled", true);
    if(!e.detail || e.detail == 1){
         e.preventDefault();
         var el = $(this);
         el.prop('disabled', true);
         setTimeout(function(){el.prop('disabled', false); }, 1000);

        var allocationCode=$('#allocationCode').val();
        var allocationId = $('#allocationCodeList').find('option[value="'+allocationCode+'"]').attr('id');
        
        var currentPendingAmt=$('#currentPendingAmt').val();
        var currentBillId=$('#currentBillId').val();
        var currentBillNo=$('#currentBillNo').val();
        var currentBillRetailer=$('#currentBillRetailer').val();

        if(allocationCode==''){
            alert('Please select allocation number.');die();
        }else if(allocationCode!=''){
            if (typeof allocationId === "undefined") {
                alert('Please select correct allocation number.');die();
            }
        }
        
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('BillTransactionController/addBillToAllocation');?>",
            data:{allocationCode:allocationCode,allocationId:allocationId,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId,currentPendingAmt:currentPendingAmt},
            success: function (data) {
              // alert(data);die();
                if(data.trim()=="Record inserted"){
                    $('#allocationCode').val("");
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
                }else{
                    alert(data);
                }
            }  
        });
        }
        
    });
</script>

<script type="text/javascript">
    $(document).on('click','#cdSaveBtn',function(e){
      $("#cdSaveBtn").attr("disabled", true);
      if(!e.detail || e.detail == 1){

         e.preventDefault();
         var el = $(this);
         el.prop('disabled', true);
         setTimeout(function(){el.prop('disabled', false); }, 1000);

        var empName=$('#cdEmp').val();
        var empId = $('#cdEmpList').find('option[value="'+empName+'"]').attr('id');
        var cdAmount=$('#cdAmount').val();
        var cdRemark=$('#cdRemark').val();
        var currentPendingAmt=$('#currentPendingAmt').val();
        var currentBillId=$('#currentBillId').val();
        var currentBillNo=$('#currentBillNo').val();
        var currentBillRetailer=$('#currentBillRetailer').val();
        var billNoText=currentBillNo+' : '+currentBillRetailer;

        var cdAmount = Number(cdAmount.replace(/,/g, '')); 
        
        
        if(empName==''){
            // empName='';
            // empId=0;
            alert('Please select employee.');die();
        }else if(empName!=''){
            if (typeof empId === "undefined") {
                alert('Please select correct employee.');die();
            }
        }

        if(cdAmount=='' || cdAmount==0){
            alert('Please enter CD amount.');die();
        }

        if(parseInt(currentPendingAmt) < parseInt(cdAmount)){
            alert('CD amount should not be greater than Pending amount');die();
        }

        if(cdRemark==''){
            alert('Please enter remark.');die();
        }

        $.ajax({
            type: "POST",
            url:"<?php echo site_url('BillTransactionController/cashDiscountCollection');?>",
            data:{empName:empName,empId:empId,cdAmount:cdAmount,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId,cdRemark:cdRemark},
            success: function (data) {
                if(data.trim()=="Record updated"){
                    $('#cdAmount').val("");
                    $('#cdRemark').val("");
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
                }else{
                    alert(data);
                }
            }  
        });
      }
        
    });
    
</script>

<script type="text/javascript">
    $(document).on('click','#debitSaveBtn',function(e){
      $("#debitSaveBtn").attr("disabled", true);
      if(!e.detail || e.detail == 1){
         e.preventDefault();
         var el = $(this);
         el.prop('disabled', true);
         setTimeout(function(){el.prop('disabled', false); }, 1000);

        var empName=$('#debitEmp').val();
        var empId = $('#debitEmpList').find('option[value="'+empName+'"]').attr('id');
        var debitAmount=$('#debitAmount').val();
        var debitRemark=$('#debitRemark').val();
        var currentPendingAmt=$('#currentPendingAmt').val();
        var currentBillId=$('#currentBillId').val();
        var currentBillNo=$('#currentBillNo').val();
        var currentBillRetailer=$('#currentBillRetailer').val();
        var billNoText=currentBillNo+' : '+currentBillRetailer;
        

        var debitAmount = Number(debitAmount.replace(/,/g, '')); 
        
        if(empName==''){
            alert('Please select employee.');die();
        }else if(empName!=''){
            if (typeof empId === "undefined") {
                alert('Please select correct employee.');die();
            }
        }

        if(debitAmount=='' || debitAmount==0){
            alert('Please enter Debit amount.');die();
        }

        if(parseInt(currentPendingAmt) < parseInt(debitAmount)){
            alert('Debit amount should not be greater than Pending amount');die();
        }

        if(debitRemark==''){
            alert('Please enter remark.');die();
        }

        $.ajax({
            type: "POST",
            url:"<?php echo site_url('BillTransactionController/debitToEmployeeCollection');?>",
            data:{empName:empName,empId:empId,debitAmount:debitAmount,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId,debitRemark:debitRemark},
            success: function (data) {
                if(data.trim()=="Record updated"){
                    $('#debitAmount').val("");
                    $('#debitRemark').val("");
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
                }else{
                    alert(data);
                }
            }  
        });
        
        }
    });
    
</script>

<script type="text/javascript">
    $(document).on('click','#officeAdjSaveBtn',function(e){
      $("#officeAdjSaveBtn").attr("disabled", true);
      if(!e.detail || e.detail == 1){
         e.preventDefault();
         var el = $(this);
         el.prop('disabled', true);
         setTimeout(function(){el.prop('disabled', false); }, 1000);

        var empName=$('#officeAdjEmp').val();
        var empId = $('#officeAdjList').find('option[value="'+empName+'"]').attr('id');
        var officeAdjAmount=$('#officeAdjAmount').val();
        var officeAdjRemark=$('#officeAdjRemark').val();
        var currentPendingAmt=$('#currentPendingAmt').val();
        var currentBillId=$('#currentBillId').val();
        var currentBillNo=$('#currentBillNo').val();
        var currentBillRetailer=$('#currentBillRetailer').val();
        var billNoText=currentBillNo+' : '+currentBillRetailer;
        
        var officeAdjAmount = Number(officeAdjAmount.replace(/,/g, ''));
        
        if(empName==''){
            alert('Please select employee.');die();
        }else if(empName!=''){
            if (typeof empId === "undefined") {
                alert('Please select correct employee.');die();
            }
        }

        if(officeAdjAmount==='' || officeAdjAmount < 0 ){
            alert('Please enter Office adjustment amount.');die();
        }
        // die();

        if(parseInt(currentPendingAmt) < parseInt(officeAdjAmount)){
            alert('Office adjustment amount should not be greater than Pending amount');die();
        }

        if(officeAdjRemark==''){
            alert('Please enter remark.');die();
        }

        $.ajax({
            type: "POST",
            url:"<?php echo site_url('BillTransactionController/officeAdjustmentCollection');?>",
            data:{empName:empName,empId:empId,officeAdjAmount:officeAdjAmount,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId,officeAdjRemark:officeAdjRemark},
            success: function (data) {
                if(data.trim()=="Record updated"){
                    $('#officeAdjAmount').val("");
                    $('#officeAdjRemark').val("");
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
                }else{
                    alert(data);
                }

            }  
        });
        }
        
    });
    
</script>

<script type="text/javascript">
    $(document).on('click','#otherAdjSaveBtn',function(e){
      $("#otherAdjSaveBtn").attr("disabled", true);
      if(!e.detail || e.detail == 1){
        e.preventDefault();
         var el = $(this);
         el.prop('disabled', true);
         setTimeout(function(){el.prop('disabled', false); }, 1000);

        var empName=$('#otherAdjEmp').val();
        var empId = $('#otherAdjEmpList').find('option[value="'+empName+'"]').attr('id');
        var otherAdjAmount=$('#otherAdjAmount').val();
        var otherAdjRemark=$('#otherAdjRemark').val();
        var currentPendingAmt=$('#currentPendingAmt').val();
        var currentBillId=$('#currentBillId').val();
        var currentBillNo=$('#currentBillNo').val();
        var currentBillRetailer=$('#currentBillRetailer').val();
        var billNoText=currentBillNo+' : '+currentBillRetailer;

        var otherAdjAmount = Number(otherAdjAmount.replace(/,/g, ''));  
        
        if(empName==''){
            alert('Please select employee.');die();
        }else if(empName!=''){
            if (typeof empId === "undefined") {
                alert('Please select correct employee.');die();
            }
        }

        if(otherAdjAmount=='' || otherAdjAmount==0){
            alert('Please enter Other adjustment amount.');die();
        }

        if(parseInt(currentPendingAmt) < parseInt(otherAdjAmount)){
            alert('Office adjustment amount should not be greater than Pending amount');die();
        }

        if(otherAdjRemark==''){
            alert('Please enter remark.');die();
        }

        $.ajax({
            type: "POST",
            url:"<?php echo site_url('BillTransactionController/otherAdjustmentCollection');?>",
            data:{empName:empName,empId:empId,otherAdjAmount:otherAdjAmount,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId,otherAdjRemark:otherAdjRemark},
            success: function (data) {
                if(data.trim()=="Record updated"){
                    $('#otherAdjAmount').val("");
                    $('#otherAdjRemark').val("");
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
                }else{
                    alert(data);
                }
            }  
        });
        }
        
    });
    
</script>

<script type="text/javascript">
    $(document).on('click','#deliveryEmpSaveBtn',function(e){
       $("#deliveryEmpSaveBtn").attr("disabled", true);
      if(!e.detail || e.detail == 1){
         
         e.preventDefault();
         var el = $(this);
         el.prop('disabled', true);
         setTimeout(function(){el.prop('disabled', false); }, 1000);

        var empName=$('#deliveryEmp').val();
        var empId = $('#deliveryEmpList').find('option[value="'+empName+'"]').attr('id');
        // var deliveryAmount=$('#deliveryAmount').val();
        var deliveryAmount=$('#currentPendingAmt').val();
        var deliveryRemark=$('#deliveryRemark').val();
        var currentPendingAmt=$('#currentPendingAmt').val();
        var currentBillId=$('#currentBillId').val();
        var currentBillNo=$('#currentBillNo').val();
        var currentBillRetailer=$('#currentBillRetailer').val();
        var billNoText=currentBillNo+' : '+currentBillRetailer;
        
        var deliveryAmount = Number(deliveryAmount.replace(/,/g, ''));
        
        if(empName==''){
            alert('Please select employee.');die();
        }else if(empName!=''){
            if (typeof empId === "undefined") {
                alert('Please select correct employee.');die();
            }
        }

        if(deliveryAmount=='' || deliveryAmount==0){
            alert('Please enter amount.');die();
        }

        if(parseInt(currentPendingAmt) < parseInt(deliveryAmount)){
            alert('Amount should not be greater than Pending amount');die();
        }

        if(deliveryRemark==''){
            alert('Please enter remark.');die();
        }
       
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('BillTransactionController/empDeliveryCollection');?>",
            data:{empName:empName,empId:empId,deliveryAmount:deliveryAmount,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId,deliveryRemark:deliveryRemark},
            success: function (data) {
              // alert(data);
                if(data.trim()=="Record updated"){
                    $('#deliveryAmount').val("");
                    $('#deliveryRemark').val("");
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
                }else{
                    alert(data);
                }
            }  
        });
        }
    });
    
</script>


<script type="text/javascript">
    $(document).on('click','#debitNoteSaveBtn',function(e){
      $("#debitNoteSaveBtn").attr("disabled", true);
      if(!e.detail || e.detail == 1){
         e.preventDefault();
         var el = $(this);
         el.prop('disabled', true);
         setTimeout(function(){el.prop('disabled', false); }, 1000);

        var empName=$('#debitNoteEmp').val();
        var empId = $('#debitNoteEmpList').find('option[value="'+empName+'"]').attr('id');
        var debitAmount=$('#debitNoteAmount').val();
        var debitRemark=$('#debitNoteRemark').val();
        var currentPendingAmt=$('#currentPendingAmt').val();
        var currentBillId=$('#currentBillId').val();
        var currentBillNo=$('#currentBillNo').val();
        var currentBillRetailer=$('#currentBillRetailer').val();
        var billNoText=currentBillNo+' : '+currentBillRetailer;
        

        var debitAmount = Number(debitAmount.replace(/,/g, '')); 
        
        if(empName==''){
            alert('Please select employee.');die();
        }else if(empName!=''){
            if (typeof empId === "undefined") {
                alert('Please select correct employee.');die();
            }
        }

        if(debitAmount=='' || debitAmount==0){
            alert('Please enter Debit Note amount.');die();
        }

        if(parseInt(currentPendingAmt) < parseInt(debitAmount)){
            alert('Debit Note amount should not be greater than Pending amount');die();
        }

        if(debitRemark==''){
            alert('Please enter remark.');die();
        }

        $.ajax({
            type: "POST",
            url:"<?php echo site_url('BillTransactionController/debitNoteCollection');?>",
            data:{empName:empName,empId:empId,debitAmount:debitAmount,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId,debitRemark:debitRemark},
            success: function (data) {
                if(data.trim()=="Record updated"){
                    $('#debitAmountForAll').val("");
                    $('#debitRemarkForAll').val("");
                    alert(data);
                    // window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
                    window.location.reload(true)
                }else{
                    alert(data);
                }
            }  
        });
        
        }
    });
    
</script>

<script>
  function numbersonly(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if ((charCode < 48 || charCode > 57) ) {
          return false;
      }
      return true;
  }

  function numbersWithDash(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode !=45 && (charCode < 48 || charCode > 57) ) {
          return false;
      }
      return true;
  }
</script>

<script type="text/javascript">
  function calMoney() {
    var a2000 = document.getElementById('add2000').value;
    // var a1000 = document.getElementById('add1000').value;
    var a500 = document.getElementById('add500').value;
    var a200 = document.getElementById('add200').value;
    var a100 = document.getElementById('add100').value;
    var a50 = document.getElementById('add50').value;
    var a20 = document.getElementById('add20').value;
    var a10 = document.getElementById('add10').value;
    var coin = document.getElementById('coin').value;

    a2000 = a2000.replace(",", "");
    a500 = a500.replace(",", "");
    a200 = a200.replace(",", "");
    a100 = a100.replace(",", "");
    a50 = a50.replace(",", "");
    a20 = a20.replace(",", "");
    a10 = a10.replace(",", "");
    coin = coin.replace(",", "");

    if(a2000 ==""){
        a2000=0;
    }
    // if(a1000 ==""){
    //     a1000=0;
    // }
    if(a500 ==""){
        a500=0;
    }
    if(a200 ==""){
        a200=0;
    }
    if(a100 ==""){
        a100=0;
    }
    if(a50 ==""){
        a50=0;
    }
    if(a20 ==""){
        a20=0;
    }
    if(a10 ==""){
        a10=0;
    }
    if(coin ==""){
        coin=0;
    }

    var c1=0;
    c1=2000*a2000;
    // var c2=0;
    // c2=1000*a1000;
    var c3=0;
    c3=500*a500;
    var c4=0;
    c4=200*a200;
    var c5=0;
    c5=100*a100;
    var c6=0;
    c6=50*a50;
    var c7=0;
    c7=20*a20;
    var c8=0;
    c8=10*a10;
    var c9=0;
    c9=coin;

    document.getElementById('ad2000').innerHTML = c1;
    // document.getElementById('ad1000').innerHTML = c2;
    document.getElementById('ad500').innerHTML = c3;
    document.getElementById('ad200').innerHTML = c4;
    document.getElementById('ad100').innerHTML = c5;
    document.getElementById('ad50').innerHTML = c6;
    document.getElementById('ad20').innerHTML = c7;
    document.getElementById('ad10').innerHTML = c8;
    document.getElementById('coins').innerHTML = c9;
    var total=0;
    total=total+c1+c3+c4+c5+c6+c7+c8;
    total=parseFloat(total)+parseFloat(c9);

    document.getElementById('totalActual').innerHTML = total;
    
    document.getElementById('collectedAmt').value= total;
    // document.getElementById('shortExcessCollectedAmt').value=total;

    var tempCashAmt=document.getElementById('tempCashAmt').value; 
    tempCashAmt = tempCashAmt.replace(",", "");
    // alert(tempCashAmt);

    var final_short=parseFloat(total)-parseFloat(tempCashAmt);
    if(tempCashAmt<total){
      document.getElementById('shortExcessCollectedAmt').value= (final_short);
      document.getElementById('shortExcesstotalActual').innerHTML = '<span style="color:green">'+(total-tempCashAmt)+'</span>';
      
    }else{
      document.getElementById('shortExcessCollectedAmt').value= (final_short);
      document.getElementById('shortExcesstotalActual').innerHTML = '<span style="color:red">'+(total-tempCashAmt)+'</span>';
      
    }
    
  }
</script>

<script>
    function checkQtyPerItem(qty,no,billQty,fsQty,netAmount,pendingAmt,prevSR,prevReceived,cash,cheque,neft,sr){
        var totalQty=parseInt(fsQty)+parseInt(qty.value);
        // var totalQty=parseInt(qty.value);
        var qty_id=document.getElementById('qty_id'+no).value;
        var netAmount_id=document.getElementById('netAmount_id'+no).value;
        var avg=parseFloat(netAmount_id)/parseInt(qty_id);
        var currentQty=parseInt(document.getElementById('returnedQty'+no).value);
        var currentSrAmt=avg*currentQty;
        var totalCollection=parseFloat(prevSR)+parseFloat(prevReceived)+parseFloat(cash)+parseFloat(cheque)+parseFloat(neft)+parseFloat(sr)+parseFloat(currentSrAmt);
        
        // if(parseFloat(totalCollection)>parseFloat(netAmount)){
        //     var msg= "SR amount will greater than bill amount";
        //     document.getElementById('data_err'+no).innerHTML=msg;
        //     document.getElementById('all_id').innerHTML=msg;
        // }else{
            var msg="";
            if((totalQty>billQty) || (totalQty<0)){
                msg="Quantity returned can not be more than quantity billed";
                document.getElementById('data_err'+no).innerHTML=msg;
                document.getElementById('all_id').innerHTML=msg;

                if(qty==="" || qty==0){
                  document.getElementById('return_total_Amt_id'+no).innerHTML='0.00';
                }else{
                   document.getElementById('return_total_Amt_id'+no).innerHTML=currentSrAmt;
                }
                // 

            }else{
                var msg=""
                document.getElementById('data_err'+no).innerHTML=msg;
                document.getElementById('all_id').innerHTML=msg;
                if(qty==="" || qty==0){
                  document.getElementById('return_total_Amt_id'+no).innerHTML='0.00';
                }else{
                   document.getElementById('return_total_Amt_id'+no).innerHTML=currentSrAmt.toFixed(2);;
                }
            }
        // }
        
    }



    function msgIsEmpty(){
        var msg=document.getElementById('all_id').innerHTML;
        if(msg===''||msg===null){
            return true;
        }else{
            return false;
        }
    }
</script>

<script>
 $(document).ready(function(){
    $('.modalLinkProcess').click(function(){
        var id=$(this).attr('data-id');
        $.ajax({
            url : "<?php echo site_url('SrController/processResend');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
              $('#processIds').html(data);
            }
        });
    });
});
</script>



<script>
 $(document).ready(function(){
    $('#modalLinkCash').click(function(){
        var id=$(this).attr('data-cashId');
        $.ajax({
            url : "<?php echo site_url('SrController/processCash');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
                alert(data);
              $('#cashContent').html(data);
            }
        });
    });
});
    

 </script>

<script>
 $(document).ready(function(){
    $('#modalLinkCheque').click(function(){
        var id=$(this).attr('data-chequeId');
        $.ajax({
            url : "<?php echo site_url('SrController/processCheque');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
              $('#chequeContent').html(data);
            }
        });
    });
});
</script>

<script>
 $(document).ready(function(){
    $('#modalLinkCD').click(function(){
        var id=$(this).attr('data-cdId');
        $.ajax({
            url : "<?php echo site_url('SrController/processCD');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
              $('#dcContent').html(data);
            }
        });
    });
});
</script>


<script>
 $(document).ready(function(){
    $('#modalLinkDebit').click(function(){
        var id=$(this).attr('data-debitId');
        $.ajax({
            url : "<?php echo site_url('SrController/processDebit');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
              $('#debitContent').html(data);
            }
        });
    });
});
</script>

<script>
 $(document).ready(function(){

    $('input').on('paste', function() {
      $(this).val($(this).val().replace(/[^a-z0-9]/gi, ''));
    });

    $('.modalLinkFixDebit').click(function(){
        var id=$(this).attr('data-id');
        $.ajax({
            url : "<?php echo site_url('SrController/fixDebit');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
              $('#fixDebId').html(data);
            }
        });
    });
});
</script>

<script type="text/javascript">
    var sendRequest = function(){
      // var curOrderField = "BillNo";
      // var curOrderDirection = "ASC";
      var searchKey = $('#searchKey').val();
      var limitRows = $('#limitRows').val();
      window.location.href = '<?=base_url('index.php/SrController/outstandingBills')?>?query='+searchKey+'&limitRows='+limitRows+'&orderField='+curOrderField+'&orderDirection='+curOrderDirection;
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

  <script type="text/javascript">
    $( document ).ready(function() {
      $(".dataExport").click(function() {
        var exportType = $(this).data('type');  
        $('#outstanding_table').tableExport({
          type : exportType, 
          select: true,  
          escape : 'false',
          ignoreColumn: [7]
        });   
      });
    });

  </script>
<script type="text/javascript">
  function removeSpaces(string) {
    return string.split(' ').join(' ');
  }
</script>