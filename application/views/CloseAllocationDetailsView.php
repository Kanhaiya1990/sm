<?php $this->load->view('/layouts/commanHeader'); ?>

<script   src="https://code.jquery.com/jquery-1.12.1.js"   integrity="sha256-VuhDpmsr9xiKwvTIHfYWCIQ84US9WqZsLfR4P7qF6O8="   crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="<?php echo base_url('assets/js/pages/ui/tooltips-popovers.js');?>"></script>
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

.logo_prov {
    border-radius: 30px;
     border: 2px solid black;
    background: red;
    color: black;
    padding: 6px;
    width: 50px;
    height: 50px;
}

</style>
<!--<section class="content">-->
<h1 style="display: none;">Welcome</h1><br/><br/><br/><br><br>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
           
            <div class="row clearfix" id="page">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                              Closed Allocation
                            </h2>
                           <!--  <p align="right">
                                <a href="<?php echo site_url('AllocationByManagerController/closedAllocationTrancationDetails/'.$allocations[0]['id']);?>">
                                  <button class="btn bg-primary margin">Show Details</button></a> 
                            </p> -->
                         
                        </div>
                        <div class="body">
                              <div class="row">
                                <div class="col-md-12">
                                   <div class="col-md-3"> 
                                    <label id="allocation">Allocation : </label>
                                    <?php echo $allocations[0]['allocationCode']?>
                                    
                                </div>
                                    <div class="col-md-3">
                                    <label>Employee</label>
                                    <ul class="list-group" id="list" multiple="multiple">
                                        <?php
                                        $emp1='';
                                         $emp2='';
                                          $emp3='';
                                           $emp4='';
                                            if(!empty($allocations[0]['fieldStaffCode1'])){
                                                $emp1= $this->AllocationByManagerModel->getEmpName('employee',$allocations[0]['fieldStaffCode1']);
                                                $emp1=$emp1[0]['name'];
                                                echo $emp1."<br>";
                                              }   
                                             if(!empty($allocations[0]['fieldStaffCode2'])){
                                                $emp2= $this->AllocationByManagerModel->getEmpName('employee',$allocations[0]['fieldStaffCode2']);
                                                $emp2=$emp2[0]['name'];
                                                  echo $emp2."<br>";
                                                 
                                            }
                                             if(!empty($allocations[0]['fieldStaffCode3'])){
                                                $emp3= $this->AllocationByManagerModel->getEmpName('employee',$allocations[0]['fieldStaffCode3']);
                                                $emp3=$emp3[0]['name'];
                                               
                                                  echo $emp3."<br>";
                                                 
                                            }
                                             if(!empty($allocations[0]['fieldStaffCode4'])){
                                                $emp4= $this->AllocationByManagerModel->getEmpName('employee',$allocations[0]['fieldStaffCode1']);
                                                $emp4=$emp4[0]['name'];
                                                echo $emp4."<br>";
                                                
                                            }
                                            
                                        ?>
                                    </ul>
                                </div>
                                   <div class="col-md-3">
                                    <label> Route</label>
                                    <ul class="list-group" id="rlist" multiple="multiple">
                                        <?php
                                            $rtName=explode(",",rtrim($allocations[0]['routId'],','));
                                        for($i=0;$i<count($rtName);$i++){
                                         $routes=$this->AllocationByManagerModel->getRouteNameById($rtName[$i]);
                                       
                                            if(!empty($routes)){
                                                $routeName=$routes[0]['name'];
                                                echo $routeName."<br>";
                                                
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <div class="col-md-3">
                                      <a href="<?php echo site_url('AllocationByManagerController/closedAllocationSrTrancationDetails/'.$allocations[0]['id']);?>">
                                      <button class="btn bg-primary margin"><i class="material-icons">inventory_2</i>  SR Details</button></a> 

                                      <a href="<?php echo site_url('AllocationByManagerController/closedAllocationTrancationDetails/'.$allocations[0]['id']);?>">
                                      <button class="btn bg-primary margin"><i class="material-icons">local_atm</i>  Other Details</button></a> 
                                </div>
                                </div>

                                <?php
                                    $cashTotal=0;
                                    $chequeTotal=0;
                                    $neftTotal=0;
                                    $totalSr=0;
                                    $totalNetAmount=0;
                                    $totalPendingAmount=0;
                                    $billCount=0;
                                    $resendTotalAmt=0;
                                    $otherAdjTotal=0;
                                    if(!empty($current)){
                                      foreach ($current as $data) 
                                      {
                                        $billCount++;

                                        $totalNetAmount=$totalNetAmount+$data['netAmount'];
                                        $totalPendingAmount=$totalPendingAmount+$data['pendingAmt'];

                                           $allocationID=$allocations[0]['id'];
                                           $billID=$data['id'];
                                           $status="";

                                           $cash=$this->AllocationByManagerModel->loadBillPayments('billpayments',$allocationID,$billID,'Cash');
                                           $cheque=$this->AllocationByManagerModel->loadBillPayments('billpayments',$allocationID,$billID,'Cheque');
                                           $neft=$this->AllocationByManagerModel->loadBillPayments('billpayments',$allocationID,$billID,'NEFT');
                                           $other=$this->AllocationByManagerModel->loadBillPayments('billpayments',$allocationID,$billID,'Other Adjustment');
                                           $sr=$this->AllocationByManagerModel->loadBillSRPayments('allocation_sr_details',$allocationID,$billID);
                                        //   print_r($sr);

                                            $resendBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$data['id'],'isResendBill');

                                            if(!empty($cash)){
                                                $cashTotal=$cashTotal+$cash[0]['paidAmount'];
                                            }

                                            if(!empty($cheque)){
                                                $chequeTotal=$chequeTotal+$cheque[0]['paidAmount'];
                                            }

                                            if(!empty($neft)){
                                                $neftTotal=$neftTotal+$neft[0]['paidAmount'];
                                            }

                                            if(!empty($other)){
                                                $otherAdjTotal=$otherAdjTotal+$other[0]['paidAmount'];
                                            }

                                            if(empty($cash) && empty($cheque) && empty($neft) && ($data['pendingAmt'] !=0)){
                                              $resendTotalAmt=$resendTotalAmt+$data['netAmount'];
                                            }

                                            $srAmount=0;
                                            if((empty($sr)) && ($data['pendingAmt'] ==0)){
                                                $srAmount=$data['SRAmt'];
                                            }else if(!empty($sr)){
                                                if($data['isFsrBill']==1){
                                                  $srAmount=$data['SRAmt'];
                                                }else{
                                                  for($i=0;$i<count($sr);$i++){
                                                      $qty=$sr[$i]['qty'];
                                                      $amount=$sr[$i]['netAmount'];
                                                      $amtPerQty=$amount/$qty;
                                                      $srAmount=$srAmount+($sr[$i]['receivedQuantity']*$amtPerQty);
                                                   }
                                                }
                                             }
                                             $totalSr=$totalSr+$srAmount;
                                        }
                                    }


                                    if(!empty($pass)){
                                        foreach ($pass as $data) 
                                          {
                                            $billCount++;
                                             $totalNetAmount=$totalNetAmount+$data['netAmount'];
                                            $totalPendingAmount=$totalPendingAmount+$data['pendingAmt'];

                                           
                                           $allocationID=$allocations[0]['id'];
                                           $billID=$data['id'];
                                           $status="";

                                           $cash=$this->AllocationByManagerModel->loadBillPayments('billpayments',$allocationID,$billID,'Cash');
                                           $cheque=$this->AllocationByManagerModel->loadBillPayments('billpayments',$allocationID,$billID,'Cheque');
                                           $neft=$this->AllocationByManagerModel->loadBillPayments('billpayments',$allocationID,$billID,'NEFT');
                                           $other=$this->AllocationByManagerModel->loadBillPayments('billpayments',$allocationID,$billID,'Other Adjustment');

                                           $sr=$this->AllocationByManagerModel->loadBillSRPayments('allocation_sr_details',$allocationID,$billID);

                                           if(!empty($cash)){
                                                $cashTotal=$cashTotal+$cash[0]['paidAmount'];
                                            }

                                            if(!empty($cheque)){
                                                $chequeTotal=$chequeTotal+$cheque[0]['paidAmount'];
                                            }

                                            if(!empty($neft)){
                                                $neftTotal=$neftTotal+$neft[0]['paidAmount'];
                                            }

                                            if(!empty($other)){
                                                $otherAdjTotal=$otherAdjTotal+$other[0]['paidAmount'];
                                            }

                                             if(empty($cash) && empty($cheque) && empty($neft) && ($data['pendingAmt'] !=0)){
                                              $resendTotalAmt=$resendTotalAmt+$data['netAmount'];
                                            }

                                              $srAmount=0;
                                              if((empty($sr)) && ($data['pendingAmt'] ==0)){
                                                $srAmount=$data['SRAmt'];
                                              }else if(!empty($sr)){
                                                if($data['isFsrBill']==1){
                                                  $srAmount=$data['SRAmt'];
                                                }else{
                                                  for($i=0;$i<count($sr);$i++){
                                                      $qty=$sr[$i]['qty'];
                                                      $amount=$sr[$i]['netAmount'];
                                                      $amtPerQty=$amount/$qty;
                                                      $srAmount=$srAmount+($sr[$i]['receivedQuantity']*$amtPerQty);
                                                  }
                                                }
                                                 
                                                
                                             }
                                             $totalSr=$totalSr+$srAmount;
                                             // echo $totalSr;
                                         }
                                       }

                                 ?>

                                 <div class="col-md-12 table-responsive">
                                   <table border="2px" style="font-size: 11px;width: 80%">
                                        
                                        
                                         <tr class="gray">
                                            <th><center>Particulars</center></th>
                                            <th><center>Total Value</center></th>
                                            <th><center>Cash</center></th>
                                            <th><center>Cheque</center></th>
                                             <th><center>NEFT</center></th>
                                            <th><center>Other Adj</center></th>
                                            <th><center>SR/FSR</center></th>
                                            <th><center>Resend</center></th>
                                            <th><center>Credit</center></th>
                                           
                                        </tr>
                                        <tr>
                                            <td><center>Amount</center></td>
                                            <td align="center" id="net_amt_chk"><?php echo number_format(($totalNetAmount));?></td>
                                            <td align="center"><?php echo number_format($cashTotal);?></td>
                                            <td align="center"><?php echo number_format($chequeTotal);?></td>
                                            <td align="center"><?php echo number_format($neftTotal);?></td>
                                            <td align="center"><?php echo number_format($otherAdjTotal);?></td>
                                            <td align="center"><?php echo number_format((round($totalSr)));?></td>
                                             <td align="center"><?php echo number_format((round($resendTotalAmt)),2);?></td>
                                            <td align="center">
                                                <?php echo number_format((round($totalPendingAmount)));?>
                                            </td>
                                            
                                        </tr>
                                    </table>
                                 </div>
                                </div>
                                
                            <div class="row">
                                                           
                                <div class="row m-t-20">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="tbl">
                                                <tr class="head">
                                                    <td colspan="12" style="background-color: whitesmoke;"><center><b>Current Supply Bills</b></center></td>
                                                </tr>
                                                <tr class="gray">
                                                    <th>S. No.</th>
                                                    <th>Bill No.</th>
                                                    <th>Bill Date</th>
                                                    <th>Retailer Name</th>
                                                    <th>Net Amount</th>
                                                    
                                                    <th>Cash</th>
                                                    <th>Cheque/NEFT</th>
                                                    <th>Other Adj</th>
                                                    <th>SR</th>


                                                    <th>Remaining Amount</th>
                                                    <th>Status</th>
                                                    <!-- <th>Detail</th> -->
                                                </tr>
                                                <tbody id="result_data">
                                                    <tr>
                            <?php
                                        $no=0;
                                        // $cashTotal=0;
                                         if(!empty($current)){
                                        foreach ($current as $data) 
                                          {
                                           $no++; 
                                           $allocationID=$allocations[0]['id'];
                                           $billID=$data['id'];
                                           $status="";

                                           $cash=$this->AllocationByManagerModel->loadBillPayments('billpayments',$allocationID,$billID,'Cash');
                                           $cheque=$this->AllocationByManagerModel->loadBillPayments('billpayments',$allocationID,$billID,'Cheque');
                                           $neft=$this->AllocationByManagerModel->loadBillPayments('billpayments',$allocationID,$billID,'NEFT');
                                           $other=$this->AllocationByManagerModel->loadBillPayments('billpayments',$allocationID,$billID,'Other Adjustment');

                                           $sr=$this->AllocationByManagerModel->loadBillSRPayments('allocation_sr_details',$allocationID,$billID);

                                            $provBillCount=$this->AllocationByManagerModel->getSumRowCount($data['id']); 
                                            if($data['isFsrBill']==1){
                                              $status="FSR";
                                              $srAmount=$data['SRAmt'];
                                              $cashAmt=0;
                                              $chequeAmt=0;
                                              $neftAmt=0;
                                              $otherAmt=0;
                                            }else{
                                                if(!empty($cash))
                                                $status=$status.','.'Cash';
                                                if(!empty($cheque))
                                                    $status=$status.','.'Cheque';
                                                if(!empty($neft))
                                                    $status=$status.','.'NEFT';
                                                if(!empty($sr)){
                                                    $status=$status.','.'SR';
                                                }

                                                if(!empty($other)){
                                                    $status=$status.','.'Other Adjustment';
                                                }
                                                if(empty($cash) && empty($cheque) && empty($neft) && empty($sr) && ($data['ResendBill']==1) && ($data['pendingAmt']!=0)){
                                                    $status=$status.','.'Resend';
                                                }else if(empty($cash) && empty($cheque) && empty($neft) && empty($sr) && ($data['ResendBill']==0) && ($data['pendingAmt']!=0)){
                                                    $status='Signed';
                                                }
                                               
                                             $srAmount=0;
                                             if(!empty($sr)){
                                                 for($i=0;$i<count($sr);$i++){
                                                    $qty=$sr[$i]['qty'];
                                                    $amount=$sr[$i]['netAmount'];
                                                    $amtPerQty=$amount/$qty;
                                                    $srAmount=$srAmount+($sr[$i]['receivedQuantity']*$amtPerQty);
                                                 }
                                                
                                             }

                                              $cashAmt=0;
                                              $chequeAmt=0;
                                              $neftAmt=0;
                                              $otherAmt=0;

                                              if(!empty($cash)){
                                                  $cashAmt=$cash[0]['paidAmount'];
                                                  $cashTotal=$cashTotal+$cash[0]['paidAmount'];
                                              }

                                              if(!empty($cheque)){
                                                  $chequeAmt=$cheque[0]['paidAmount'];
                                              }

                                              if(!empty($neft)){
                                                  $neftAmt=$neft[0]['paidAmount'];
                                              }

                                              if(!empty($other)){
                                                $otherAmt=$other[0]['paidAmount'];
                                                }
                                            }
                                           
                                          
                                    ?>
                                        <tr>
                                            <td><?php echo $no.' '; 
                                              if($data['creditNoteRenewal']>0){ echo '<span class="logo_prov">CN</span>'; }
                                            if($provBillCount[0]['recBill']>0){ echo '<span class="logo_prov">RB</span>'; }
                                            if($provBillCount[0]['lostBill']>0){ echo '<span class="logo_prov">LB</span>'; }
                                            if($provBillCount[0]['lostCheque']>0){ echo '<span class="logo_prov">LC</span>'; }
                                            if($provBillCount[0]['lostNeft']>0){ echo '<span class="logo_prov">PN</span>'; }
                                              
                                            ?></td>
                                        
                                            <td>
                                                <?php echo $data['billNo']; ?>
                                            </td>
                                             <?php
                                                $dt=date_create($data['date']);
                                                $date = date_format($dt,'d-M-Y');
                                            ?>
                                            <td><?php echo $date; ?></td>
                                            <td><?php echo $data['retailerName']; ?></td>
                                            <td><?php echo number_format($data['netAmount']); ?></td>
                                            <td><?php echo number_format($cashAmt); ?></td>
                                             <td><?php echo number_format(($chequeAmt+$neftAmt)); ?></td>
                                             <td><?php echo number_format(round($otherAmt)); ?></td>
                                            <td><?php echo number_format(round($srAmount)); ?></td>
                                            
                                            <td><?php echo number_format(round((($data['netAmount']+$data['chequePenalty'])-($cashAmt+$chequeAmt+$otherAmt+$neftAmt+round($srAmount))))); ?></td>
                                            <td><?php if($data['netAmount']==round($srAmount)){ echo 'FSR'; }else{ echo trim($status,','); } ?></td>
                                           <!--  <td>
                                              <a href="<?php echo site_url('AllocationByManagerController/closedAllocationTrancationDetails/'.$allocations[0]['id'].'/'.$data['id']);?>">
                                              <button class="btn bg-primary margin">Show Details</button></a> 
                                            </td> -->
                                        </tr>
                                    <?php
                                        }
                                    }
                                      ?> 
                                  </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            
                                            <table class="table table-striped table-bordered">
                                            <tr class="head">
                                                <td colspan="12"  style="background-color: whitesmoke;"><center><b>Past Bills</b></center></td>
                                            </tr>
                                            <tr class="gray">
                                                    <th>S. No.</th>
                                                    <th>Bill No.</th>
                                                    <th>Bill Date</th>
                                                    <th>Retailer Name</th>
                                                    <th>Net Amount</th>
                                                    <th>Pending Amount</th>
                                                    <th>Cash</th>
                                                    <th>Cheque/NEFT</th>
                                                    <th>Other Adj</th>
                                                    <th>SR</th>
                                                    <th>Remaining Amount</th>
                                                    <th>Status</th>
                                                    <!-- <th>Details</th> -->
                                                </tr>
                                            <tbody id="result_past">
                                                 <tr>
                            <?php
                                        $no=0;
                                        if(!empty($pass)){
                                        foreach ($pass as $data) 
                                          {
                                           $no++; 

                                           $newPending=$data['pendingAmt']+$data['fsSrAmt']+$data['fsCashAmt']+$data['fsNeftAmt']+$data['fsChequeAmt']+$data['fsOtherAdjAmt'];

                                           $allocationID=$allocations[0]['id'];
                                           $billID=$data['id'];
                                           $status="";

                                           $cash=$this->AllocationByManagerModel->loadBillPayments('billpayments',$allocationID,$billID,'Cash');
                                           $cheque=$this->AllocationByManagerModel->loadBillPayments('billpayments',$allocationID,$billID,'Cheque');
                                           $neft=$this->AllocationByManagerModel->loadBillPayments('billpayments',$allocationID,$billID,'NEFT');
                                           $other=$this->AllocationByManagerModel->loadBillPayments('billpayments',$allocationID,$billID,'Other Adjustment');
                                           $sr=$this->AllocationByManagerModel->loadBillSRPayments('allocation_sr_details',$allocationID,$billID);

                                           $provBillCount=$this->AllocationByManagerModel->getSumRowCount($data['id']); 
                                      
                                            if(!empty($cash))
                                              $status=$status.','.'Cash';
                                           if(!empty($cheque))
                                              $status=$status.','.'Cheque';
                                           if(!empty($neft))
                                              $status=$status.','.'NEFT';
                                           if(!empty($sr)){
                                              $status=$status.','.'SR';
                                           }

                                           if(!empty($other)){
                                            $status=$status.','.'Other Adjustment';
                                            }
                                           
                                            if(empty($cash) && empty($cheque) && empty($neft) && empty($sr) && ($data['isResendBill']==1) && ($newPending!=0)){
                                                $status=$status.','.'Resend';
                                            }else if(empty($cash) && empty($cheque) && empty($neft) && empty($sr) && ($data['isResendBill']==0) && ($newPending!=0)){
                                                $status='Signed';
                                            }

                                            $srAmount=0;
                                           if(!empty($sr)){
                                               for($i=0;$i<count($sr);$i++){
                                                  $qty=$sr[$i]['qty'];
                                                  $amount=$sr[$i]['netAmount'];
                                                  $amtPerQty=$amount/$qty;
                                                  $srAmount=$srAmount+($sr[$i]['receivedQuantity']*$amtPerQty);
                                               }
                                              
                                           }
                                           

                                           $paidAmountTill=$this->AllocationByManagerModel->getAllocationBillNetAmt('billpayments',$data['id'],$allocations[0]['id']);
                                            if($allocations[0]['allocationCloseAt']==""){
                                                $paidProcessAmountTill=$this->AllocationByManagerModel->getProcessBillNetAmt('billpayments',$data['id'],$allocations[0]['date']);

                                            }else{
                                                $paidProcessAmountTill=$this->AllocationByManagerModel->getProcessBillNetAmt('billpayments',$data['id'],$allocations[0]['allocationCloseAt']);

                                            }

                                           $remAmount=0;
                                           // $remAmount2=0;
                                           if(!empty($paidAmountTill)){
                                               foreach($paidAmountTill as $amt){
                                                 // echo '<br>'.$amt['paidAmount'];
                                                 $remAmount=$amt['paidAmount']+$remAmount;
                                               }
                                           }

                                           if(!empty($paidProcessAmountTill)){
                                             foreach($paidProcessAmountTill as $amt){
                                               // echo '<br>'.$amt['paidAmount'];
                                                 $remAmount=$remAmount+$amt['paidAmount'];
                                             }
                                           }
                                           // print_r($sr);
                                            $cashAmt=0;
                                            $chequeAmt=0;
                                            $neftAmt=0;

                                            $otherAmt=0;

                                            if(!empty($cash)){
                                                $cashAmt=$cash[0]['paidAmount'];
                                                $cashTotal=$cashTotal+$cash[0]['paidAmount'];
                                            }

                                            if(!empty($cheque)){
                                                $chequeAmt=$cheque[0]['paidAmount'];
                                            }

                                            if(!empty($neft)){
                                                $neftAmt=$neft[0]['paidAmount'];
                                            }

                                            if(!empty($other)){
                                                $otherAmt=$other[0]['paidAmount'];
                                            }
                                         
                                    ?>
                                        <tr>
                                             <td><?php echo $no.' '; 
                                              if($data['creditNoteRenewal']>0){ echo '<span class="logo_prov">CN</span>'; }
                                            if($provBillCount[0]['recBill']>0){ echo '<span class="logo_prov">RB</span>'; }
                                            if($provBillCount[0]['lostBill']>0){ echo '<span class="logo_prov">LB</span>'; }
                                            if($provBillCount[0]['lostCheque']>0){ echo '<span class="logo_prov">LC</span>'; }
                                            if($provBillCount[0]['lostNeft']>0){ echo '<span class="logo_prov">PN</span>'; }
                                              
                                            ?></td>
                                        
                                            <td>
                                                <?php echo $data['billNo']; ?>
                                            </td>
                                             <?php
                                                $dt=date_create($data['date']);
                                                $date = date_format($dt,'d-M-Y');
                                            ?>
                                            <td><?php echo $date; ?></td>
                                            <td><?php echo $data['retailerName']; ?></td>
                                            <td align="right"><?php echo number_format($data['netAmount'])?></td>
                                            <td align="right"><?php echo number_format(($newPending-$data['pendingAmt']+$cashAmt+$chequeAmt+$otherAmt+$neftAmt+round($srAmount))); ?></td>
                                            <td align="right"><?php echo number_format($cashAmt); ?></td>
                                             <td align="right"><?php echo number_format(($chequeAmt+$neftAmt)); ?></td>
                                             <td align="right"><?php echo number_format(round($otherAmt)); ?></td>
                                            <td align="right"><?php echo number_format(round($srAmount)); ?></td>
                                            <td align="right"><?php echo number_format($newPending)?></td>
                                           <!-- <td><?php echo round((($data['netAmount']+$data['chequePenalty'])-($cashAmt+$chequeAmt+$otherAmt+$neftAmt+round($srAmount)))); ?></td> -->
                                            <td><?php if($data['netAmount']==round($srAmount)){ echo 'FSR'; }else{ echo trim($status,','); } ?></td>
                                           <!-- <td>
                                              <a href="<?php echo site_url('AllocationByManagerController/closedAllocationTrancationDetails/'.$allocations[0]['id'].'/'.$data['id']);?>">
                                              <button class="btn bg-primary margin">Show Details</button></a> 
                                            </td> -->
                                          
                                        </tr>
                                    <?php
                                        }
                                    }
                                      ?> 
                                  </tr>
                                            </tbody>
                                           
                                           
                                            </table>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                     
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Examples --> 
        </div>
</section>
<?php $this->load->view('/layouts/footerDataTable'); ?>
<script>
function deleted()
{ 
 swal({
  title: "Are you sure?",
  text: "You will not be able to recover this imaginary file!",
  type: "warning",
  showCancelButton: true,
  confirmButtonClass: "btn-danger",
  confirmButtonText: "Yes, delete it!",
  cancelButtonText: "No, cancel plx!",
  closeOnConfirm: false,
  closeOnCancel: false
},
function(isConfirm) {
  if (isConfirm) {
    swal("Deleted!", "Your imaginary file has been deleted.", "success");
  } else {
    swal("Cancelled", "Your imaginary file is safe :)", "error");
  }
});
}
</script>
</section>

 <script type="text/javascript">
    // jQuery("#rtBillNo").focusout(function(){
        function clearPast(){
            // jQuery("#rmv_routeBills").on("click",function(){
                var routeName = $('#rtBillNo').val();
                if(routeName==""){
                    alert("Please enter Route Name");
                }else{
                     $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('AllocationByManagerController/clearAllBills');?>",
                                // url: "<?=base_url()?>/AllocationByManagerController/getCurrentBills",
                        data:{"routeName" : routeName},
                        success: function (data) {
                          $('#result_past').html(data);
                        }  
                    });
                   $('#rtBillNo').val('');   
                }
            // });
        }
// </script>
 
<script>
    $('a.removebutton').on('click',function() {
    alert("Are you sure? You Want to Delete This Row");
  $(this).closest( 'tr').remove();
  return false;
});
</script>

<script type="text/javascript">
    function removeMe(that,id) {
        var rmId=id;
        $(that).closest('tr').remove();

         $.ajax({
                url: "<?php echo site_url('AllocationByManagerController/removeBillIdFromSession');?>",
                type: "post",
                data:{"rmId" : rmId},
                success: function (response) {
                    // $('#result_data').html(response);    
                }
         });
    }
</script>

<script>
    (function(){

      var todo = document.querySelector( '#list' ),
          add = document.querySelector( '#eAdd' ),
          eName = document.querySelector( '#eName' );
        
      add.addEventListener('click', function( ev ) {
            var text = eName.value;
            if ( text !== '' ) {
              todo.innerHTML += '<li class="list-group-item list-group-item-action">' + text + '<button  style="float: right;" onclick="Delete(this);"><i class="fa fa-close"></i></button> </li>';
              eName.value = '';
            }
            
        ev.preventDefault();
      }, false);

    })();
      function Delete(currentEl){
      currentEl.parentNode.parentNode.removeChild(currentEl.parentNode);
      }
</script>

<script>
    (function(){

      var todo = document.querySelector( '#rlist' ),
          add = document.querySelector( '#rAdd' ),
          eName = document.querySelector( '#name' );
        
      add.addEventListener('click', function( ev ) {
            var text = eName.value;
            if ( text !== '' ) {
              todo.innerHTML += '<li class="list-group-item list-group-item-action" id="'+text+'">' + text + '<button  style="float: right;" onclick="Delete(this);"><i class="fa fa-close"></i></button> </li>';
              eName.value = '';
        }
        ev.preventDefault();
      }, false);

    })();
      function Delete(currentEl){
      currentEl.parentNode.parentNode.removeChild(currentEl.parentNode);
      }
</script>

<!-- <script>
    $(document).ready(function(){
        $("#eName").on('input', function() {
            var val = $('#eName').val();
            if(val != ""){
                $('#list').append('<option onClick="removeList('+val+')" class="list-group-item" value="'+val+'">' + val + '</option>');
                $('#eName').val('').focus();
            }
        })

        $('#list').on('click', function() {
            $("#list option").remove();
        });
    });
</script> -->

<script>
function changeStatusForCurrentBills(id)
{ 
    swal({
      title: 'Select Status',
      input: 'select',
      inputOptions: {
        'Cancelled': 'Cancelled',
        'Returned': 'Returned',
        'Delivered': 'Delivered',
        'Fully Settled': 'Fully Settled'
      },
      inputPlaceholder: 'Select Status',
      showCancelButton: true,
      inputValidator: function (value) {
          return new Promise(function (resolve, reject) {
              if (value !== '') {
                resolve();
              } else {
                reject('You need to select a Status');
              }
         });
    }
    }).then(function (result) {
        var from = $('#from').val();
        var to = $('#to').val();
        if (result.value) {
            // swal({
            //   type: 'success',
            //   html: 'You selected: ' + result.value+" id "+id
            // });
            $.ajax({
                url: "<?php echo site_url('AllocationByManagerController/updateStatusForCurrentBills');?>",
                type: "post",
                data:{"id" : id , "status" : result.value,"from" : from,"to" :to},
                success: function (response) {
                    $('#result_data').html(response);  
                }
            });
        }

    });
}

</script>

<script>
function changeStatusForPastBills(id)
{ 
    swal({
      title: 'Select Status',
      input: 'select',
      inputOptions: {
        'Cancelled': 'Cancelled',
        'Returned': 'Returned',
        'Delivered': 'Delivered',
        'Fully Settled': 'Fully Settled'
      },
      inputPlaceholder: 'Select Status',
      showCancelButton: true,
      inputValidator: function (value) {
          return new Promise(function (resolve, reject) {
              if (value !== '') {
                resolve();
              } else {
                reject('You need to select a Status');
              }
         });
    }
    }).then(function (result) {
        var pName = $('#pName').val();
        if (result.value) {
            // swal({
            //   type: 'success',
            //   html: 'You selected: ' + result.value+" id "+id
            // });
            $.ajax({
                url: "<?php echo site_url('AllocationByManagerController/updateStatusForPastBills');?>",
                type: "post",
                data:{"id" : id , "status" : result.value,"pName" : pName},
                success: function (response) {
                    $('#result_past').html(response);  
                }
            });
        }
    });
}

</script>

<script type="text/javascript">
    jQuery("#cmpName").on("change",function(){
        var cmpName = $('#cmpName').val();
        if(cmpName==""){
            alert("Please enter cmpName");
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('AllocationByManagerController/CompCurrentBills');?>",
            // url: "<?=base_url()?>/AllocationByManagerController/getCurrentBills",
                data:{"cmpName" : cmpName},
                success: function (data) {
                  $('#frmBill').html(data);
                  $('#toBill').html(data);
                  $('#addBill').html(data);
                  // $('#pstBill').html(data);
                }  
            });
                // $('#cmpName').val('');
        }
});
</script>
<script type="text/javascript">
    jQuery("#cmpName").on("change",function(){
        var cmpName = $('#cmpName').val();
        if(cmpName==""){
            alert("Please enter cmpName");
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('AllocationByManagerController/CompPastBills');?>",
            // url: "<?=base_url()?>/AllocationByManagerController/getCurrentBills",
                data:{"cmpName" : cmpName},
                success: function (data) {
                  $('#pstBill').html(data);
                }  
            });
        }
});
</script>
<script type="text/javascript">
    jQuery("#cmpName").on("change",function(){
        var cmpName = $('#cmpName').val();
        if(cmpName==""){
            alert("Please enter cmpName");
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('AllocationByManagerController/CompChequeBills');?>",
            // url: "<?=base_url()?>/AllocationByManagerController/getCurrentBills",
                data:{"cmpName" : cmpName},
                success: function (data) {
                  $('#chbill').html(data);
                  // $('#result_data').html(data);
                }  
            });
        }
});
</script>

<script type="text/javascript">
    jQuery("#cmpName").on("change",function(){
        var cmpName = $('#cmpName').val();
        if(cmpName==""){
            alert("Please enter cmpName");
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('AllocationByManagerController/CompDeliveryBills');?>",
            // url: "<?=base_url()?>/AllocationByManagerController/getCurrentBills",
                data:{"cmpName" : cmpName},
                success: function (data) {
                  $('#delBill').html(data);
                }  
            });
        }
});
</script>

<script type="text/javascript">
    jQuery("#insert-more").on("click",function(){
        var from = $('#from').val();
        var to = $('#to').val();
        if(from == "" || to ==""){
            alert("Please enter From/To BillNo");
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('AllocationByManagerController/getCurrentBills');?>",
            // url: "<?=base_url()?>/AllocationByManagerController/getCurrentBills",
                data:{"from" : from , "to" : to},
                success: function (data) {
                  $('#result_data').html(data);
                } 
            });
                $('#from').val('');
                $('#to').val('');
        }
});
</script>

<script type="text/javascript">
    jQuery("#insert-more1").on("click",function(){
        var addBill = $('#addBill').val();
        if(addBill==""){
            alert("Please enter BillNo");
        }else{
             $.ajax({
            type: "POST",
            url:"<?php echo site_url('AllocationByManagerController/getCurrentBillsWithAdditions');?>",
            // url: "<?=base_url()?>/AllocationByManagerController/getCurrentBills",
                data:{"addBill" : addBill},
                success: function (data) {
                  $('#result_data').html(data);
                }  
            });
                $('#addBill').val('');
        }
});
</script>

<script type="text/javascript">
    jQuery("#insert-past").on("click",function(){
        var pName = $('#pName').val();
        var routeName = $('#name').val();
        if(pName==""){
            alert("Enter Past BillNo");
        }else{
             $.ajax({
            type: "POST",
            url:"<?php echo site_url('AllocationByManagerController/getPastBills');?>",
            // url: "<?=base_url()?>/AllocationByManagerController/getCurrentBills",
                data:{"pName" : pName,"routeName" : routeName},
                success: function (data) {
                  $('#result_past').html(data);
                }  
            });
                $('#pName').val('');
        }
});
</script>

<script type="text/javascript">
    jQuery("#insert-delivery").on("click",function(){
        var delBill = $('#delBillNo').val();
        var routeName = $('#name').val();
        if(delBill==""){
            alert("Please enter DeliverySlip BillNo");
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('AllocationByManagerController/getDeliverySlipBills');?>",
            // url: "<?=base_url()?>/AllocationByManagerController/getCurrentBills",
                data:{"delBill" : delBill,"routeName" : routeName},
                success: function (data) {
                  $('#result_delivery').html(data);
                }  
            });
                $('#delBillNo').val('');
        }
});
</script>

<script type="text/javascript">
    jQuery("#insert-bounced").on("click",function(){
        var chequeNo = $('#chequeNo').val();
        var routeName = $('#name').val();
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('AllocationByManagerController/getBouncedBills');?>",
            // url: "<?=base_url()?>/AllocationByManagerController/getCurrentBills",
        data:{"chequeNo" : chequeNo,"routeName" : routeName},
        success: function (data) {
          $('#result_bounced').html(data);
        }  
    });
        $('#chequeNo').val('');
        
});
</script>

<script>
    (function(){

      var todo = document.querySelector( '#lastRTlist' ),
          add = document.querySelector( '#shw_routeBills' ),
          eName = document.querySelector( '#rtBillNo' );
        
      add.addEventListener('click', function( ev ) {
            var text = eName.value;
            if ( text !== '' ) {
              todo.innerHTML += '<li class="list-group-item list-group-item-action">' + text + '<button  style="float: right;" onclick="Delete(this);"><i class="fa fa-close"></i></button> </li>';
              eName.value = '';
            }
            
        ev.preventDefault();
      }, false);

    })();
      function Delete(currentEl){
      currentEl.parentNode.parentNode.removeChild(currentEl.parentNode);
      }
</script>

 <script type="text/javascript">
    // jQuery("#rtBillNo").focusout(function(){
    jQuery("#shw_routeBills").on("click",function(){
        var routeName = $('#rtBillNo').val();
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('AllocationByManagerController/loadPastBills');?>",
            // url: "<?=base_url()?>/AllocationByManagerController/getCurrentBills",
        data:{"routeName" : routeName},
        success: function (data) {
          $('#result_past').html(data);
        }  
    });
});
// </script>

<script type="text/javascript">
    jQuery("#insert-ins").on("click",function(){
        var emp = new Array();
        var rtName = new Array();
        var allocationCode=$('#allocation').text();
        var reference=$('#reference').val();
        var routeName=$('#name').val();
        
        $("#list li").each(function()
        {
             emp.push($(this).text());
        });
        $("#rlist li").each(function()
        {
             rtName.push($(this).text());
        });
        
        if(emp.length>0 && rtName.length>0){
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('AllocationByManagerController/insertAllocationData');?>",
                // url: "<?=base_url()?>/AllocationByManagerController/getCurrentBills",
                data:{"emp":emp,"allocationCode" : allocationCode,"reference" : reference,"routeName" : routeName,"rtName":rtName},
                success: function (data) {
                  $('#ins').html(data);
                }  
            });
        }else{
            alert('Please select Employee/Route');
        }
});
    
</script>

<script type="text/javascript">
    jQuery("#update-ins").on("click",function(){
        var emp = new Array();
        var rtName = new Array();
        var allocationCode=$('#allocation').text();
        var reference=$('#reference').val();
        var routeName=$('#name').val();
        
        $("#list li").each(function()
        {
             // emp.push(this.value);
             emp.push($(this).text());
        });
        $("#rlist li").each(function()
        {
             // emp.push(this.value);
             rtName.push($(this).text());
        });
        
        if(emp.length>0 && rtName.length>0){
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('AllocationByManagerController/SaveConfirm');?>",
                // url: "<?=base_url()?>/AllocationByManagerController/getCurrentBills",
                data:{"emp":emp,"allocationCode" : allocationCode,"reference" : reference,"routeName" : routeName,"rtName":rtName},
                success: function (data) {
                  $('#ins').html(data);
                }  
            });
        }else{
            alert('Please select Employee/Route');
        }
});
    
</script>


<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
      <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>

       <!-- Bootstrap Core Js -->
    <script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.js');?>"></script>

   <!-- Select Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/bootstrap-select/js/bootstrap-select.js');?>"></script>

     <!-- Slimscroll Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/jquery-slimscroll/jquery.slimscroll.js');?>"></script>

    <!-- Bootstrap Colorpicker Js -->
    <script src="<?php echo base_url('assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js');?>"></script>

    <!-- Dropzone Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/dropzone/dropzone.js');?>"></script>

    <!-- Input Mask Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/jquery-inputmask/jquery.inputmask.bundle.js');?>"></script>

    <!-- Multi Select Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/multi-select/js/jquery.multi-select.js');?>"></script>

    <!-- Jquery Spinner Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/jquery-spinner/js/jquery.spinner.js');?>"></script>

    <!-- Bootstrap Tags Input Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js');?>"></script>

    <!-- noUISlider Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/nouislider/nouislider.js');?>"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/node-waves/waves.js');?>"></script>

    <!-- Custom Js -->
    <script src="<?php echo base_url('assets/js/admin.js');?>"></script>
    <script src="<?php echo base_url('assets/js/pages/forms/advanced-form-elements.js');?>"></script>

    <!-- Demo Js -->
    <script src="<?php echo base_url('assets/js/demo.js');?>"></script>
    <!-- SweetAlert Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/sweetalert/sweetalert.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/pages/ui/dialogs.js');?>"></script>
    <!-- Bootstrap Notify Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/bootstrap-notify/bootstrap-notify.js');?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7"></script>