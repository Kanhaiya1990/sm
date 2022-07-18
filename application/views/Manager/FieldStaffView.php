<?php $this->load->view('/layouts/commanHeader'); ?>

<script   src="<?php echo base_url('assets/js/kp_js/jquery-1.12.1.js'); ?>"   integrity="sha256-VuhDpmsr9xiKwvTIHfYWCIQ84US9WqZsLfR4P7qF6O8="   crossorigin="anonymous"></script>
 <script src="<?php echo base_url('assets/js/kp_js/jquery.min.js'); ?>"></script>
 <script src="<?php echo base_url('assets/js/pages/ui/tooltips-popovers.js');?>"></script>
<style type="text/css">
    @media screen and (min-width: 950px) {
        .modal-dialog {
          width: 950px; /* New width for default modal */
        }
        .modal-sm {
          width: 350px; /* New width for small modal */
        }
    }

    @media screen and (min-width: 950px) {
        .modal-lg {
          width: 950px; /* New width for large modal */
        }
    }
F
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
.sticky {
  position: fixed;
  top: 0;
  width: 100%;
}

/*.logo_prov {
    border-radius: 30px;
     border: 1px solid black;
    background: red;
    color: black;
    padding: 6px;
    width: 50px;
    height: 50px;
}
*/
</style>

<!--<section class="content">-->
<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <!-- <div class="block-header">
                <h2>Manager Hissab Master</h2>
            </div> -->
            <!-- Basic Examples -->
             
            <div class="row cust-tbl clearfix" id="page">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2> Hisab Review</h2>
                        </div>
                        <div class="body">
                            <div class="col-md-12">
                                <div class="col-md-5 cust-tbl">
                                    <div class="col-md-6">
                                    <label id="allocation">Allocation : </label>
                                    <?php echo $allocations[0]['allocationCode'];
                                        $allocationID=$allocations[0]['id'];
                                        $allocationCode=$allocations[0]['allocationCode'];
                                       
                                    ?>
                                    <br />
                                    <input id="current_allocation_Id" type="hidden" value="<?php echo $allocationID; ?>">
                                     <input id="current_emp_Id" type="hidden" value="<?php echo $allocations[0]['fieldStaffCode1']; ?>">

                                </div>
                                <div class="col-md-6">
                                    <label>Employees: </label>
                                        <?php
                                        $total=0;
                                        $emp1='';
                                         $emp2='';
                                          $emp3='';
                                           $emp4='';
                                            if(!empty($allocations[0]['fieldStaffCode1'])){
                                                $emp1= $this->FieldStaffModel->getEmpName('employee',$allocations[0]['fieldStaffCode1']);
                                                $emp1=$emp1[0]['name'];
                                                ?>
                                                 <span><?php echo $emp1;?> </span>
                                                 <?php
                                            }
                                             if(!empty($allocations[0]['fieldStaffCode2'])){
                                                $emp2= $this->FieldStaffModel->getEmpName('employee',$allocations[0]['fieldStaffCode2']);
                                                $emp2=$emp2[0]['name'];
                                                ?>
                                                  <span><?php echo $emp2;?> </span>
                                                  <?php
                                            }
                                             if(!empty($allocations[0]['fieldStaffCode3'])){
                                                $emp3= $this->FieldStaffModel->getEmpName('employee',$allocations[0]['fieldStaffCode3']);
                                                $emp3=$emp3[0]['name'];
                                                ?>
                                                 <span><?php echo $emp3;?> </span>
                                                  <?php
                                            }
                                             if(!empty($allocations[0]['fieldStaffCode4'])){
                                                $emp4= $this->FieldStaffModel->getEmpName('employee',$allocations[0]['fieldStaffCode1']);
                                                $emp4=$emp4[0]['name'];
                                                ?>
                                                 <span><?php echo $emp4;?></span>
                                                 <?php
                                            }
                                            
                                        ?>
                                </div>
                                <div class="col-md-6">
                                    <label id="route">Route : </label>
                                    <?php 
                                        $rID=explode(",",rtrim($allocations[0]['routId'],','));
                                        $routeName='';
                                       
                                       for($i=0;$i<count($rID);$i++){
                                        $routes=$this->FieldStaffModel->getRouteNameById($rID[$i]);
                                        $routeName=$routeName.' '.$routes[0]['name'].',';
                                       }
                                        echo rtrim($routeName,',');
                                    ?>
									</div>
                                    <br />
                                </div>
                                <div class="col-md-7">
                                     <table class="table table-bordered cust-tbl" style="font-size: 14px;">
                                        <tr class="gray">
                                            <th><center>Particulars</center></th>
                                            <th><center>Total</center></th>
                                            <th><center>FSR</center></th>
                                            <th><center>Resend</center></th>
                                            <th><center>Delivered</center></th>
                                        </tr>
                                        <tr>
                                            <td><center>No. of Bills</center></td>
                                            <td align="center" id="billcount_id"><?php echo $billCount;?></td>
                                            <td align="center"><?php echo $srBillCount;?></td>
                                            <td align="center" id="resendcount_id"><?php echo $resendCount;?></td>
                                            <td align="center"><?php echo $billedCount;?></td>
                                        </tr>
                                         <tr class="gray">
                                            <th><center>Particulars</center></th>
                                            <th><center>Total</center></th>
                                            <th><center>Cash</center></th>
                                            <th><center>Cheque/NEFT</center></th>
                                            <th><center>Other Adj</center></th>
                                            <th><center>SR/FSR</center></th>
                                            <th><center>Credit</center></th>
                                            <th><center>Resend</center></th>
                                        </tr>
                                        <tr>
                                            <td><center>Amount</center></td>
                                            <td align="center" id="net_amt_chk"><?php echo number_format(($pendingTotal+$cashBillTotal+$chequeNeftTotal),2);?></td>
                                            <td align="center"><?php echo number_format($cashBillTotal,2);?></td>
                                            <td align="center"><?php echo number_format($chequeNeftTotal,2);?></td>
                                            <td align="center"><?php echo number_format($otherAdjTotal,2);?></td>
                                             <td align="center"><?php echo number_format(($srBillTotal-$creditAdjBillTotal),2);?></td>
                                            <td align="center">
                                                <?php echo number_format($pendingTotal-(($srBillTotal-$creditAdjBillTotal)+$resendTotal),2);?>
                                            </td>
                                            <td align="center" id="resend_amt_chk"><?php echo number_format($resendTotal,2);?></td>
                                        </tr>
                                    </table>
                                </div>
                                </div>
                            <div class="row">
                                                          
                                <div class="row m-t-20">
                                    <div class="col-md-12">
                                        <div>

                                            <?php 
                                              if(!empty($this->session->flashdata('item'))){
                                                  $msg=$this->session->flashdata('item');
                                                  echo '<p class="flashMsg flashError" style="color:red">'.$msg['message'].'</p>';
                                              }
                                                  
                                            ?> 
                                            <table class="table cust-tbl table-bordered noLine-css" id="tbl">
                                                <tr class="head">
                                                    <td colspan="12" style="background-color: whitesmoke;"><center><b>Current Supply Bills</b></center></td>
                                                </tr>
												
                                                <tr class="gray">
                                                    <th>No</th>
                                                    <th>Bill No.</th>
                                                    <th>Bill Date</th>
													<th style="width: 250px;">Retailer Name</th>
                                                    <th>Amount</th>
                                                    <th>SR</th>
                                                    <th>Collection</th>
                                                    <th>Pending</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
													
                                                </tr>
                                                <tbody id="result_data">
                                                    <tr>
                                    <?php
                                        $no=0;
                                         if(!empty($current)){
                                        foreach ($current as $data) 
                                          {
                                            $provBillCount=$this->FieldStaffModel->getSumRowCount($data['id']); 

                                            //  $resendBills=$this->FieldStaffModel->getRowCount('allocationsbills',$data['id'],'isResendBill');
                                            // $lostBills=$this->FieldStaffModel->getRowCount('allocationsbills',$data['id'],'isLostBill');
                                            // $lostChequesBills=$this->FieldStaffModel->getRowCount('allocationsbills',$data['id'],'isLostCheque');
                                            // $pendingNeftBills=$this->FieldStaffModel->getRowCount('allocationsbills',$data['id'],'isPendingNeft');
                                            //  $bouncedBill=$this->FieldStaffModel->checkBouncedBill('billpayments',$data['id']);

                                            $total=$total+$data['netAmount'];
                                           $no++; 
                                           if($data['fsbillStatus']=='Resend'){

                                    ?>
                                        <tr style="background-color: #eeeeee;">
                                        <?php } else if($data['fsbillStatus']=='Billed'){?>
                                        <tr style="background-color: #eeeeee;">
                                        <?php } else if(!empty($data['fsbillStatus']) && ($data['fsbillStatus']==='FSR')){?>
                                        <tr style="background-color: #eeeeee;">
                                        <?php } else if(!empty($data['fsbillStatus']) && ($data['fsbillStatus']==='SR')){?>
                                        <tr>
                                        <?php } else if(!empty($data['fsbillStatus'])){?>
                                        <tr style="background-color: #eeeeee;">
                                        <?php } ?>   

                                        <td><?php echo $no.' '; 
                                              if($data['creditNoteRenewal']>0){ echo '<span class="logo_prov">CN</span>'; }
                                              if($provBillCount[0]['recBill']>0){ echo '<span class="logo_prov">RB</span>'; }
                                              if($provBillCount[0]['lostBill']>0){ echo '<span class="logo_prov">LB</span>'; }
                                              if($provBillCount[0]['lostCheque']>0){ echo '<span class="logo_prov">LC</span>'; }
                                              if($provBillCount[0]['lostNeft']>0){ echo '<span class="logo_prov">PN</span>'; }
                                               if($data['chequePenalty']>0){ ?>
                                                 <span class="logo_prov"><?php echo 'B'.intval($data['chequePenalty']); ?></span>
                                            <?php  }
                                              ?></td>
                                        
                                            <td>
                                                <?php echo $data['billNo']; ?>
                                            </td>
                                             <?php

                                                $dt=date_create($data['date']);
                                                $date = date_format($dt,'d-M-Y');
                                            ?>
                                            <td class="noSpace"><?php echo $date; ?></td>
                                            <td class="CellWithComment">
											<?php 
												$result1 =substr($data['retailerName'],0,20);
											echo $result1; ?>
											<?php if(strlen($data['retailerName'])>20){?>
                                            <span class="CellComment"><?php echo $result =substr($data['retailerName'],0); ?></span>
                                          <?php } ?>
											</td>
                                            <td align="right"><?php echo number_format($data['netAmount'],2);?></td>
                                            <td align="right"><?php echo number_format(($data['fsSrAmt']),2);?></td>
                                            <td align="right"><?php echo number_format($data['fsCashAmt']+$data['fsChequeAmt']+$data['fsNeftAmt']+$data['fsOtherAdjAmt'],2);?></td>
                                            <td align="right"><?php echo number_format((($data['pendingAmt'])-($data['fsSrAmt'])),2);?></td>
                                            
                                            <td id="stCr"><?php echo $data['fsbillStatus'];?></td>
                                            <td class="noSpace btns-td">
                                                <?php if(($data['fsbillStatus']=='Resend')){?>
                                            
                                                <button style="font-size : 9px;" class="btn1 waves-effect" data-type="basic" disabled><span>SR</span></button>
                                                <button style="font-size : 9px;" class="btn2 waves-effect" data-type="basic" disabled><span>Cash</span></button>
                                            
                                                <button style="font-size : 9px;" class="btn3 waves-effect" data-type="basic" disabled><span>Cheque</span></button>

                                                <button style="font-size : 9px;" class="btn4 waves-effect" data-type="basic" disabled><span>NEFT</span></button>

                                                <button style="font-size : 9px;" class="btn6 waves-effect" data-type="basic" disabled><span>Other</span></button>

                                            <a>
                                                <button style="font-size : 9px;" onclick="Resend(this,'current','<?php echo $data['id']; ?>');" id="rsn" class="btn5 waves-effect" data-type="basic"><span>Resend</span></button>
                                            </a>

                                            <?php if($data['pendingAmt'] >0) {?>
                                                <button onclick="Bill(this,'current','<?php echo $data['id']; ?>');" id="billCRStatus" style="font-size : 9px;" class="btn6 waves-effect" data-type="basic" disabled><span>Bill</span></button>
                                            <?php }else { ?>
                                                <button disabled id="billCRStatus" style="font-size : 9px;" class="btn6 waves-effect" data-type="basic" disabled><span>Bill</span></button>
                                            <?php } ?>

                                             
                                        
                                             <a>
                                                <button onclick="fsrDS(this,'current','<?php echo $data['id']; ?>','<?php echo $allocationID;?>');" style="font-size : 9px;" id="fsrBtnDS" class="btn7 waves-effect" data-type="basic"><span>FSR</span></button>
                                            </a>  

                                        <?php }else if(($data['fsbillStatus']=='FSR')){?>
                                            
                                                <button style="font-size : 9px;" class="btn1 waves-effect" data-type="basic" disabled><span>SR</span></button>
                                            
                                                <button style="font-size : 9px;" class="btn2 waves-effect" data-type="basic" disabled><span>Cash</span></button>
                                            
                                                <button style="font-size : 9px;" class="btn3 waves-effect" data-type="basic" disabled><span>Cheque</span></button>

                                                <button style="font-size : 9px;" class="btn4 waves-effect" data-type="basic" disabled><span>NEFT</span></button>

                                                <button style="font-size : 9px;" class="btn6 waves-effect" data-type="basic" disabled><span>Other</span></button>
                                            <a>
                                                <button style="font-size : 9px;" onclick="Resend(this,'current','<?php echo $data['id']; ?>');" id="rsn" class="btn5 waves-effect" data-type="basic"><span>Resend</span></button>
                                            </a>

                                            <?php if($data['pendingAmt'] >0) {?>
                                                <button onclick="Bill(this,'current','<?php echo $data['id']; ?>');" id="billCRStatus" style="font-size : 9px;" class="btn6 waves-effect" data-type="basic" disabled><span>Bill</span></button>
                                            <?php }else { ?>
                                                <button disabled id="billCRStatus" style="font-size : 9px;" class="btn6 waves-effect" data-type="basic" disabled><span>Bill</span></button>
                                            <?php } ?>
                                             
                                        
                                             <a>
                                                <button onclick="fsrDS(this,'current','<?php echo $data['id']; ?>','<?php echo $allocationID;?>');" style="font-size : 9px;" id="fsrBtnDS" class="btn7 waves-effect" data-type="basic"><span>FSR</span></button>
                                            </a>  
                                            
                                        <?php }else{?>
                                            
                                             <a href="javascript:void()" class="srM" data-toggle="modal" data-status="<?php echo $data['fsbillStatus']; ?>" data-id="<?php echo $data['id']; ?>" data-allocation="<?php echo $allocationID;?>" data-target="#cpSrModal">
                                                <button style="font-size : 9px;" class="btn1 waves-effect" data-type="basic"><span>SR</span></button>
                                            </a>
                                            
                                            
                                            <a class="cashM" data-toggle="modal" data-status="<?php echo $data['fsbillStatus']; ?>" data-id="<?php echo $data['id']; ?>" data-allocation="<?php echo $allocationID;?>" data-target="#cpCashModal" href="javascript:void()">
                                                <button style="font-size : 9px;" class="btn2 waves-effect" data-type="basic"><span>Cash</span></button>
                                            </a>
                                           
                                            <a class="chequeM" data-toggle="modal" data-status="<?php echo $data['fsbillStatus']; ?>" data-id="<?php echo $data['id']; ?>" data-allocation="<?php echo $allocationID;?>" data-target="#cpChequeModal" href="javascript:void()">
                                                <button style="font-size : 9px;" class="btn3 waves-effect" data-type="basic"><span>Cheque</span></button>
                                            </a>

                                             <a class="neftM" data-toggle="modal" data-status="<?php echo $data['fsbillStatus']; ?>" data-id="<?php echo $data['id']; ?>" data-allocation="<?php echo $allocationID;?>" data-target="#cpNeftModal" href="javascript:void()">
                                                <button style="font-size : 9px;" class="btn4 waves-effect" data-type="basic"><span>NEFT</span></button>
                                            </a>

                                            <a class="otherM" data-toggle="modal" data-status="<?php echo $data['fsbillStatus']; ?>" data-id="<?php echo $data['id']; ?>" data-allocation="<?php echo $allocationID;?>" data-target="#cpOtherModal" href="javascript:void()">
                                                <button style="font-size : 9px;" class="btn6 waves-effect" data-type="basic"><span>Other</span></button>
                                            </a>

                                        <?php if(empty($data['fsbillStatus']) || $data['fsbillStatus']=='Resend'){ ?>
                                             <a>
                                                <button style="font-size : 9px;" onclick="Resend(this,'current','<?php echo $data['id']; ?>');" id="rsn" class="btn5 waves-effect" data-type="basic"><span>Resend</span></button>
                                            </a>
                                        <?php }else{?>
                                            <a>
                                                <button style="font-size : 9px;" onclick="Resend(this,'current','<?php echo $data['id']; ?>');" id="rsn" class="btn5 waves-effect" data-type="basic" disabled><span>Resend</span></button>
                                            </a>
                                        <?php }?>

                                        <?php if($data['pendingAmt'] >0) {?>
                                            <a>
                                                <button onclick="Bill(this,'current','<?php echo $data['id']; ?>');" id="billCRStatus" style="font-size : 9px;" class="btn6 waves-effect" data-type="basic"><span>Bill</span></button>
                                            </a>
                                        <?php }else { ?>
                                            <a>
                                                <button disabled id="billCRStatus" style="font-size : 9px;" class="btn6 waves-effect" data-type="basic"><span>Bill</span></button>
                                            </a>
                                        <?php } ?>
                                            

                                         <?php if(empty($data['fsbillStatus']) || $data['fsbillStatus']=='SR'){ ?>
                                             <a>
                                                <button onclick="fsrDS(this,'current','<?php echo $data['id']; ?>','<?php echo $allocationID;?>');" style="font-size : 9px;" id="fsrBtnDS" class="btn7 waves-effect" data-type="basic"><span>FSR</span></button>
                                            </a>  
                                         <?php }else{?>
                                            <a>
                                                <button onclick="fsrDS(this,'current','<?php echo $data['id']; ?>','<?php echo $allocationID;?>');" style="font-size : 9px;" id="fsrBtnDS" class="btn7 waves-effect" data-type="basic" disabled><span>FSR</span></button>
                                            </a>
                                        <?php }?> 
                                           
                                        <?php } ?>    

                                        </td>
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
                                        <div>
                                            
                                            <table class="table cust-tbl table-bordered noLine-css" id="tbl1">
                                            <tr class="head">
                                                <td colspan="12"  style="background-color: whitesmoke;"><center><b>Past Bills</b></center></td>
                                            </tr>
                                            <tr class="gray">
                                                <th>No</th>
                                                <th>Bill No.</th>
                                                <th>Bill Date</th>
                                                <th style="width: 250px;">Retailer Name</th>
                                                <th>Bill Amount</th>
                                                <th>Past SR</th>
                                                <th>Past Collection</th>
                                                <th>SR</th>
                                                <th>Collection</th>
                                                <th>Pending</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                            <tbody id="result_past">
                                                 <tr>
                            <?php
                                        $no=0;
                                        if(!empty($pass)){
                                        foreach ($pass as $data) 
                                          {
                                            $provBillCount=$this->FieldStaffModel->getSumRowCount($data['id']); 

                                            // $resendBills=$this->FieldStaffModel->getRowCount('allocationsbills',$data['id'],'isResendBill');
                                            // $lostBills=$this->FieldStaffModel->getRowCount('allocationsbills',$data['id'],'isLostBill');
                                            // $lostChequesBills=$this->FieldStaffModel->getRowCount('allocationsbills',$data['id'],'isLostCheque');
                                            // $pendingNeftBills=$this->FieldStaffModel->getRowCount('allocationsbills',$data['id'],'isPendingNeft');
                                            //  $bouncedBill=$this->FieldStaffModel->checkBouncedBill('billpayments',$data['id']);

                                            $total=$total+$data['netAmount'];
                                           $no++; 
                                     if($data['fsbillStatus']=='Resend'){

                                    ?>
                                        <tr style="background-color: #eeeeee;">
                                        <?php } else if($data['fsbillStatus']=='Billed'){?>
                                        <tr style="background-color: #eeeeee;">
                                        <?php } else if(!empty($data['fsbillStatus']) && ($data['fsbillStatus']==='FSR')){?>
                                        <tr style="background-color: #eeeeee;">
                                        <?php } else if(!empty($data['fsbillStatus']) && ($data['fsbillStatus']==='SR')){?>
                                        <tr>
                                        <?php } else if(!empty($data['fsbillStatus'])){?>
                                        <tr style="background-color: #eeeeee;">
                                        <?php } ?>   

                                        <td><?php echo $no.' '; 
                                              if($data['creditNoteRenewal']>0){ echo '<span class="logo_prov">CN</span>'; }
                                              if($provBillCount[0]['recBill']>0){ echo '<span class="logo_prov">RB</span>'; }
                                              if($provBillCount[0]['lostBill']>0){ echo '<span class="logo_prov">LB</span>'; }
                                              if($provBillCount[0]['lostCheque']>0){ echo '<span class="logo_prov">LC</span>'; }
                                              if($provBillCount[0]['lostNeft']>0){ echo '<span class="logo_prov">PN</span>'; }
                                               if($data['chequePenalty']>0){ ?>
                                                 <span class="logo_prov"><?php echo 'B'.intval($data['chequePenalty']); ?></span>
                                            <?php  }
                                              ?></td>
                                        <td><?php echo $data['billNo']; ?></td>
                                             <?php
                                                $dt=date_create($data['date']);
                                                $date = date_format($dt,'d-M-Y');
                                            ?>
                                        <td style="white-space: nowrap;"><?php echo $date;?></td>
                                        <td class="CellWithComment">
											<?php 
												$result1 =substr($data['retailerName'],0,20);
											echo $result1; ?>
											<?php if(strlen($data['retailerName'])>20){?>
                                            <span class="CellComment"><?php echo $result =substr($data['retailerName'],0); ?></span>
                                          <?php } ?>
											</td>
                                        <td align="right"><?php echo number_format($data['netAmount'],2);?></td>
                                          
                                        <td><?php echo $data['SRAmt'];?></td>
                                        <td><?php echo $data['receivedAmt']+$data['cd']+$data['debit']+$data['officeAdjustmentBillAmount']+$data['otherAdjustment'];?></td>
                                        <td align="right"><?php echo number_format(($data['fsSrAmt']-$data['creditNoteRenewal']),2);?></td>
                                        <td align="right"><?php echo number_format($data['fsCashAmt']+$data['fsNeftAmt']+$data['fsChequeAmt']+$data['fsOtherAdjAmt'],2);?></td>
                                        <td align="right"><?php echo number_format((($data['pendingAmt'])-($data['fsSrAmt']-$data['creditNoteRenewal'])),2);?></td>

                                        <td id="stPass"><?php echo $data['fsbillStatus']; ?></td>
                                         <td class="noSpace btns-td"> 
                                        <?php if(($data['fsbillStatus']=='Resend')){  ?>
                                            
                                        <button style="font-size : 9px;"  class="btn1 waves-effect" data-type="basic" disabled><span>SR</span></button>
                                               
                                        <button style="font-size : 9px;" class="btn2 waves-effect" data-type="basic" disabled><span>Cash</span></button>

                                        <button style="font-size : 9px;" class="btn3 waves-effect" data-type="basic" disabled><span>Cheque</span></button>

                                        <button style="font-size : 9px;" class="btn4 waves-effect" data-type="basic" disabled><span>NEFT</span></button>
                                            
                                        <button style="font-size : 9px;" class="btn6 waves-effect" data-type="basic" disabled><span>Other</span></button>
                                            
                                        <?php if($data['pendingAmt'] >0) {?>
                                        <button onclick="Bill(this,'current','<?php echo $data['id']; ?>');" id="billCRStatus" style="font-size : 9px;" class="btn5 waves-effect" data-type="basic" disabled><span>Bill</span></button>
                                        <?php }else { ?>
                                        <button disabled id="billCRStatus" style="font-size : 9px;" class="btn5 waves-effect" data-type="basic" disabled><span>Bill</span></button>
                                        <?php } ?>
                                        <button style="font-size : 9px;" onclick="Bill(this,'pass','<?php echo $data['id']; ?>');" class="btn6 waves-effect" data-type="basic" disabled><span>Bill</span></button>
                                                
                                        <?php }else if($data['fsbillStatus']=='FSR'){  ?>
                                            
                                        <button style="font-size : 9px;"  class="btn1 waves-effect" data-type="basic" disabled><span>SR</span></button>
                                              
                                        <button style="font-size : 9px;" class="btn2 waves-effect" data-type="basic" disabled><span>Cash</span></button>

                                        <button style="font-size : 9px;" class="btn3 waves-effect" data-type="basic" disabled><span>Cheque</span></button>

                                        <button style="font-size : 9px;" class="btn4 waves-effect" data-type="basic" disabled><span>NEFT</span></button>
                                            
                                        <button style="font-size : 9px;" class="btn6 waves-effect" data-type="basic" disabled><span>Other</span></button>
                                            
                                        <?php if($data['pendingAmt'] >0) {?>
                                        <button style="font-size : 9px;" onclick="Bill(this,'pass','<?php echo $data['id']; ?>');" class="btn5 waves-effect" data-type="basic" disabled><span>Bill</span></button>
                                        <?php }else { ?>
                                        <button style="font-size : 9px;" disabled class="btn5 waves-effect" data-type="basic" disabled><span>Bill</span></button>
                                        <?php } ?>

                                        <?php }else{?>
                                            
                                        <a class="srM" data-toggle="modal" data-status="<?php echo $data['fsbillStatus']; ?>" data-target="#cpSrModal" data-id="<?php echo $data['id']; ?>" data-allocation="<?php echo $allocationID;?>" href="javascript:void()">
                                        <button style="font-size : 9px;" class="btn1 waves-effect" data-type="basic"><span>SR</span></button>
                                        </a>
                                             
                                        <a class="cashM" data-toggle="modal" data-status="<?php echo $data['fsbillStatus']; ?>" data-id="<?php echo $data['id']; ?>" data-allocation="<?php echo $allocationID;?>" data-target="#cpCashModal" href="javascript:void()">
                                        <button style="font-size : 9px;" class="btn2 waves-effect" data-type="basic"><span>Cash</span></button>
                                        </a>
                                            
                                        <a class="chequeM" data-toggle="modal" data-status="<?php echo $data['fsbillStatus']; ?>" data-id="<?php echo $data['id']; ?>" data-allocation="<?php echo $allocationID;?>" data-target="#cpChequeModal" href="javascript:void()">
                                        <button style="font-size : 9px;" class="btn3 waves-effect" data-type="basic"><span>Cheque</span></button>
                                        </a>

                                        <a class="neftM" data-toggle="modal" data-status="<?php echo $data['fsbillStatus']; ?>" data-id="<?php echo $data['id']; ?>" data-allocation="<?php echo $allocationID;?>" data-target="#cpNeftModal" href="javascript:void()">
                                        <button style="font-size : 9px;" class="btn4 waves-effect" data-type="basic"><span>NEFT</span></button>
                                        </a>

                                        <a class="otherM" data-toggle="modal" data-status="<?php echo $data['fsbillStatus']; ?>" data-id="<?php echo $data['id']; ?>" data-allocation="<?php echo $allocationID;?>" data-target="#cpOtherModal" href="javascript:void()">
                                        <button style="font-size : 9px;" class="btn6 waves-effect" data-type="basic"><span>Other</span></button>
                                        </a>

                                            <?php if($data['pendingAmt'] >0) {?>
                                                <a>
                                                    <button style="font-size : 9px;" onclick="Bill(this,'pass','<?php echo $data['id']; ?>');" class="btn5 waves-effect" data-type="basic"><span>Bill</span></button>
                                                </a>
                                            <?php }else { ?>
                                                <a>
                                                    <button style="font-size : 9px;" disabled class="btn5 waves-effect" data-type="basic"><span>Bill</span></button>
                                                </a>
                                            <?php } ?>
                                           
                                        <?php } ?>
                                        </td>
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
                                   <!--  
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered" id="tbl2">
                                            <tr class="head">
                                                <td colspan="12"  style="background-color: whitesmoke;"><center><b>Bounced Cheques</b></center></td>
                                            </tr>
                                            <tr class="gray">
                                                <th>S. No.</th>
                                                <th>Cheque No.</th>
                                                <th>Cheque Date</th>
                                                <th>CD</th> 
                                                <th>Retailer Name</th>
                                                <th>Principal Amount</th>
                                                <th>Penalty</th>
                                                <th>Past Collection</th>
                                                <th>Today's Collection</th>
                                                <th>Pending Amount</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                          <tbody id="result_bounced">
                                            </tbody>
                                            </table>
                                        </div>
                                    </div> -->
                                    
                                 
                                   
                                    <div class="col-md-4"></div>
                                    <div class="col-md-6">
                                      
                                    <p id="ins"></p>
                                    <p>

                                   <!--   <a href="<?php echo site_url('manager/FieldStaffController/fieldStaff/'.$allocationID);?>">
                                        <button type="button" id="insert-ins" class="btn btn-primary m-t-15 waves-effect">
                                              <i class="material-icons">save</i> 
                                              <span class="icon-name">Save</span>
                                        </button>
                                     </a>  -->        
                                            
                                        <button type="button" onclick="checkStatus('<?php echo $allocationID;?>','<?php echo $allocationCode;?>');" class="btn btn-primary btnStyle m-t-15 waves-effect">
                                              <i class="material-icons">check</i> 
                                              <span class="icon-name">Save & Confirm</span>
                                        </button>
  

                                    <a href="<?php echo site_url('AllocationByManagerController/openAllocations');?>">
                                        <button type="button" class="btn btn-danger m-t-15 waves-effect">
                                            <i class="material-icons">cancel</i> 
                                            <span class="icon-name"> Cancel </span>
                                        </button>
                                    </a>  
                                        </p>
                                         
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                            </div>
                     
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Examples --> 
        </div>
</section>

<div class="container">
  <div class="modal fade" id="cpSrModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="mods">
          </div>
      </div>
    </div>
  </div>
</div>

<div class="container">
    <div class="modal fade" id="cpCashModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modss">
                    
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="modal fade" id="cpChequeModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modsss">
                    
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="modal fade" id="cpNeftModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modssss">
                    
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="modal fade" id="cpOtherModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="otherModssss">
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('/layouts/footerDataTable'); ?>
<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>

<script>
 $(document).ready(function(){
    $('.srM').click(function(){
        var id=$(this).attr('data-id');
        var status=$(this).attr('data-status');
       
        var allocationId=$(this).attr('data-allocation');
        if(status=='FSR' || status=='Resend'){
            $('#srM').bind('click', false);
            $("#cpSrModal").modal().hide();
        }else{
            $.ajax({
                url : "<?php echo site_url('manager/FieldStaffController/srView');?>",
                method : "POST",
                data : {id: id,allocationId:allocationId},
                success: function(data){
                  $('.mods').html(data);
                }
            });
        }
       
    });
});
</script>

<script>
 $(document).ready(function(){
    $('.cashM').click(function(){
        var id=$(this).attr('data-id');
        var status=$(this).attr('data-status');
        var allocationId=$(this).attr('data-allocation');
        var current_emp_Id=$('#current_emp_Id').val();
        if(status=='FSR' || status=='Resend'){
            $('#srM').bind('click', false);
            $("#cpCashModal").modal().hide();
        }else{
            $.ajax({
                url : "<?php echo site_url('manager/FieldStaffController/cashModal');?>",
                method : "POST",
                data : {id: id,allocationId:allocationId,current_emp_Id:current_emp_Id},
                success: function(data){
                  $('.modss').html(data);
                  $('.cash-amt').trigger('focus');
                }
            });
        }
    });
});
</script>

<script>
 $(document).ready(function(){
    $('.chequeM').click(function(){
        var id=$(this).attr('data-id');
        var status=$(this).attr('data-status');
        var allocationId=$(this).attr('data-allocation');
        var current_emp_Id=$('#current_emp_Id').val();
        if(status=='FSR' || status=='Resend'){
            $('#srM').bind('click', false);
            $("#cpChequeModal").modal().hide();
        }else{
            $.ajax({
                url : "<?php echo site_url('manager/FieldStaffController/chequeModal');?>",
                method : "POST",
                data : {id: id,allocationId:allocationId,current_emp_Id:current_emp_Id},
                success: function(data){
                  $('.modsss').html(data);
                  $('.chk-amt').trigger('focus');
                }
            });
        }
    });
});
</script>

<script>
 $(document).ready(function(){
    $('.neftM').click(function(){
        var id=$(this).attr('data-id');
        var status=$(this).attr('data-status');
        var allocationId=$(this).attr('data-allocation');
        var current_emp_Id=$('#current_emp_Id').val();
        if(status=='FSR' || status=='Resend'){
            $('#srM').bind('click', false);
            $("#cpNeftModal").modal().hide();
        }else{
            $.ajax({
                url : "<?php echo site_url('manager/FieldStaffController/neftModal');?>",
                method : "POST",
                data : {id: id,allocationId:allocationId,current_emp_Id:current_emp_Id},
                success: function(data){
                  $('.modssss').html(data);
                  $('.neft-amt').trigger('focus');
                }
            });
        }
    });
});
</script>

<script>
 $(document).ready(function(){
    $('.otherM').click(function(){
        var id=$(this).attr('data-id');
        var status=$(this).attr('data-status');
        var allocationId=$(this).attr('data-allocation');
        var current_emp_Id=$('#current_emp_Id').val();
        if(status=='FSR' || status=='Resend'){
            $('#srM').bind('click', false);
            $("#cpOtherModal").modal().hide();
        }else{
            $.ajax({
                url : "<?php echo site_url('manager/FieldStaffController/otherAdjModal');?>",
                method : "POST",
                data : {id: id,allocationId:allocationId,current_emp_Id:current_emp_Id},
                success: function(data){
                  $('.otherModssss').html(data);
                  $('.other-amt').trigger('focus');
                }
            });
        }
    });
});
</script>

<script>
    window.onscroll = function() {myFunction()};
    var header = document.getElementById("myHeader");
    var sticky = header.offsetTop;

    function myFunction() {
      if (window.pageYOffset > sticky) {
        header.classList.add("sticky");
      } else {
        header.classList.remove("sticky");
      }
    }
</script>

<script>
    function Bill(t,data,id)
    {
        var current_allocation_Id=$('#current_allocation_Id').val();
        var bill=$(t).closest('tr').find('#stCr');
        var pass=$(t).closest('tr').find('#stPass'); 
        var cheque=$(t).closest('tr').find('#stCheque');
        var slip=$(t).closest('tr').find('#stSlip');
        var backColor='#f1d7ec';
        var clearColor='white';
        var resend="Resend";
        var billed="Billed";
        var cash = "Cash";
        var cheque="Cheque";
        var sr="SR/FSR";
        //For Current Bills
        if(data=='current'){
            if(bill.text()=='' || bill.text()==cash || bill.text()==cheque || bill.text() == sr){
                var billStatus = 'Billed';
                $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('manager/FieldStaffController/changeFsBillStatus');?>",
                    data:{"billStatus" : billStatus,"billId" : id,"current_allocation_Id":current_allocation_Id},
                    success: function (data) {
                        window.parent.location.reload(true);
                    }  
                });
            }else if(bill.text()!='' || bill.text()==billed){
                var billStatus = '';
                $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('manager/FieldStaffController/changeFsBillStatus');?>",
                    data:{"billStatus" : billStatus,"billId" : id,"current_allocation_Id":current_allocation_Id},
                    success: function (data) {
                        window.parent.location.reload(true);
                    }  
                });
            }
        }

         //For Past Bills
        if(data=='pass'){
            if(pass.text()=='' || pass.text()==cash || pass.text()==cheque || pass.text() == sr){
                var billStatus = 'Billed';
                $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('manager/FieldStaffController/changeFsBillStatus');?>",
                    data:{"billStatus" : billStatus,"billId" : id,"current_allocation_Id":current_allocation_Id},
                    success: function (data) {
                        window.parent.location.reload(true);
                    }  
                });
            }else if(pass.text()!='' || pass.text()==billed){
                var billStatus = '';
                $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('manager/FieldStaffController/changeFsBillStatus');?>",
                    data:{"billStatus" : billStatus,"billId" : id,"current_allocation_Id":current_allocation_Id},
                    success: function (data) {
                        window.parent.location.reload(true);
                    }  
                });
            }
        }

         //For Cheque Bills
        if(data=='cheque'){
            if(cheque.text()=='' || cheque.text()==cash || cheque.text()==cheque || cheque.text() == sr){
                var billStatus = 'Billed';
                $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('manager/FieldStaffController/changeFsBillStatus');?>",
                    data:{"billStatus" : billStatus,"billId" : id,"current_allocation_Id":current_allocation_Id},
                    success: function (data) {
                        window.parent.location.reload(true);
                    }  
                });
            }else if(cheque.text()!='' || cheque.text()==billed){
               var billStatus = '';
                $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('manager/FieldStaffController/changeFsBillStatus');?>",
                    data:{"billStatus" : billStatus,"billId" : id,"current_allocation_Id":current_allocation_Id},
                    success: function (data) {
                        window.parent.location.reload(true);
                    }  
                });  
            }
        }

        //For Temporary Bills/DeliverySlips
        if(data=='slip'){
            if(slip.text()=='' || slip.text()==cash || slip.text()==cheque || slip.text() == sr){
                var billStatus = 'Billed';
                $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('manager/FieldStaffController/changeFsBillStatus');?>",
                    data:{"billStatus" : billStatus,"billId" : id,"current_allocation_Id":current_allocation_Id},
                    success: function (data) {
                        window.parent.location.reload(true);
                    }  
                });
            }else if(slip.text()!='' || slip.text()==billed){
               var billStatus = '';
                $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('manager/FieldStaffController/changeFsBillStatus');?>",
                    data:{"billStatus" : billStatus,"billId" : id,"current_allocation_Id":current_allocation_Id},
                    success: function (data) {
                        window.parent.location.reload(true);
                    }  
                });
            }
        }
    }

    function Resend(t,data,id)
    {
        var current_allocation_Id=$('#current_allocation_Id').val();
        // alert(current_allocation_Id);die();
        var pass=$(t).closest('tr').find('#stPass');
        var bill=$(t).closest('tr').find('#stCr');
        var cheque=$(t).closest('tr').find('#stCheque');
        var slip=$(t).closest('tr').find('#stSlip');
        var backColor='#ccdffa';
        var clearColor='white';
        var resend="Resend";
        var billed="Billed";
        var cash = "Cash";
        var cheque="Cheque";
        var sr="SR/FSR";

        if(data=='current'){
            if(bill.text()==''){
                var billStatus = 'Resend';
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('manager/FieldStaffController/changeFsResendStatus');?>",
                        data:{"billStatus" : billStatus,"billId" : id,"current_allocation_Id":current_allocation_Id},
                        success: function (data) {
                            window.parent.location.reload(true);
                        }  
                });
            }else if(bill.text()!='' || bill.text()==resend){
                var billStatus = '';
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('manager/FieldStaffController/changeFsResendStatus');?>",
                        data:{"billStatus" : billStatus,"billId" : id,"current_allocation_Id":current_allocation_Id},
                        success: function (data) {
                            window.parent.location.reload(true);
                        }  
                });
            }
        } 

         if(data=='pass'){
            if(pass.text()==''){
                var billStatus = 'Resend';
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('manager/FieldStaffController/changeFsResendStatus');?>",
                        data:{"billStatus" : billStatus,"billId" : id,"current_allocation_Id":current_allocation_Id},
                        success: function (data) {
                            window.parent.location.reload(true);
                        }  
                });
            }else if(pass.text()!='' || pass.text()==resend){
                var billStatus = '';
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('manager/FieldStaffController/changeFsResendStatus');?>",
                        data:{"billStatus" : billStatus,"billId" : id,"current_allocation_Id":current_allocation_Id},
                        success: function (data) {
                            window.parent.location.reload(true);
                        }  
                });
            }
        } 
       if(data=='cheque'){
            if(cheque.text()==''){
                var billStatus = 'Resend';
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('manager/FieldStaffController/changeFsResendStatus');?>",
                        data:{"billStatus" : billStatus,"billId" : id,"current_allocation_Id":current_allocation_Id},
                        success: function (data) {
                            window.parent.location.reload(true);
                        }  
                });
            }else if(cheque.text()!='' || cheque.text()==resend){
                var billStatus = '';
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('manager/FieldStaffController/changeFsResendStatus');?>",
                        data:{"billStatus" : billStatus,"billId" : id,"current_allocation_Id":current_allocation_Id},
                        success: function (data) {
                            window.parent.location.reload(true);
                        }  
                });
            }
        }  
        if(data=='Slip'){
            if(slip.text()==''){
                var billStatus = 'Resend';
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('manager/FieldStaffController/changeFsResendStatus');?>",
                        data:{"billStatus" : billStatus,"billId" : id,"current_allocation_Id":current_allocation_Id},
                        success: function (data) {
                            window.parent.location.reload(true);
                        }  
                });
            }else if(Slip.text()!='' || Slip.text()==resend){
                var billStatus = '';
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('manager/FieldStaffController/changeFsResendStatus');?>",
                        data:{"billStatus" : billStatus,"billId" : id,"current_allocation_Id":current_allocation_Id},
                        success: function (data) {
                            window.parent.location.reload(true);
                        }  
                });
            }
        }
    }
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
            if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) || (key==45))
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

<script>
    function checkEmptyStatusInTable1(){
        var tbl=document.getElementById('tbl');
        var billNo="";
        for(var i=2;i<tbl.rows.length;i++){
            if(tbl.rows[i].cells.length){
                var status=(tbl.rows[i].cells[8].textContent.trim());
                var billNo=(tbl.rows[i].cells[1].textContent.trim());
                if(!status){
                    return billNo;
                }
            }
        }
    }

    function checkEmptyStatusInTable2(){
        var tbl=document.getElementById('tbl1');
        for(var i=2;i<tbl.rows.length;i++){
            if(tbl.rows[i].cells.length){
                var status=(tbl.rows[i].cells[10].textContent.trim());
                var billNo=(tbl.rows[i].cells[1].textContent.trim());
                if(!status){
                   return billNo;
                }
            }
        }
    }

    function checkEmptyStatusInTable3(){
        var tbl=document.getElementById('tbl2');
        for(var i=2;i<tbl.rows.length;i++){
            if(tbl.rows[i].cells.length){
                var status=(tbl.rows[i].cells[10].textContent.trim());
                var billNo=(tbl.rows[i].cells[1].textContent.trim());
                if(!status){
                    return billNo;
                }
            }

        }
    }

    function checkStatus(id,code){
        var t1=this.checkEmptyStatusInTable1();
        var t2=this.checkEmptyStatusInTable2();

        var billCounts=document.getElementById('billcount_id').innerHTML;
        var resendCounts=document.getElementById('resendcount_id').innerHTML;
        var netAmt=document.getElementById('net_amt_chk').innerHTML;
        var resendAmt=document.getElementById('resend_amt_chk').innerHTML;

        netAmt=netAmt.replace(/\,/g,'');
        netAmt=Number(netAmt);

        resendAmt=resendAmt.replace(/\,/g,'');
        resendAmt=Number(resendAmt); 

        
        // if(resendCounts>=2){
        //     alert('Do not finalize hisab, You cant resend '+ resendCounts +' bills in an allocation.');
        //     return true;
        // }else{

            if(t1){
                alert('Do not finalize hisab, Bill No. '+ t1 +' is not accounted.');
                return true;
            }else if(t2){
                alert('Do not finalize hisab, Bill No. '+ t2 +' is not accounted.');
                return true;
            }else{
                 $.ajax({
                    url : "<?php echo site_url('manager/FieldStaffController/managerHissabUpdate');?>",
                    method : "POST",
                    data : {id:id},
                    success: function(data){
                        // alert(data);
                        window.location.href = "<?php echo site_url('AllocationByManagerController/openAllocations');?>";
                    }
                });
            }
        // }
    }
</script>

<script>
    
    function fsrDS(t,data,id,allocationId)
    {
        var pass=$(t).closest('tr').find('#stPass');
        var bill=$(t).closest('tr').find('#stCr');
        var cheque=$(t).closest('tr').find('#stCheque');
        var slip=$(t).closest('tr').find('#stSlip');
        var fsr="FSR";

        if(data=='current'){
            if((bill.text()=='') || (bill.text()=='SR')){
                var billStatus = 'FSR';
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('manager/FieldStaffController/confirmFSR');?>",
                        data:{"billStatus" : billStatus,"billId" : id,"allocationId":allocationId},
                        success: function (data) {
                            window.parent.location.reload(true);
                        }  
                });
            }else if(bill.text()!='' || bill.text()==FSR){
                var billStatus = '';
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('manager/FieldStaffController/cancelFSR');?>",
                        data:{"billStatus" : billStatus,"billId" : id,"allocationId":allocationId},
                        success: function (data) {
                            window.parent.location.reload(true);
                        }  
                });
            }
        } 

         if(data=='pass'){
            if((pass.text()=='') || (pass.text()=='SR')){
                var billStatus = 'FSR';
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('manager/FieldStaffController/confirmFSR');?>",
                        data:{"billStatus" : billStatus,"billId" : id},
                        success: function (data) {
                            window.parent.location.reload(true);
                        }  
                });
            }else if(pass.text()!='' || pass.text()==FSR){
                var billStatus = '';
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('manager/FieldStaffController/cancelFSR');?>",
                        data:{"billStatus" : billStatus,"billId" : id},
                        success: function (data) {
                            window.parent.location.reload(true);
                        }  
                });
            }
        } 
       if(data=='cheque'){
            if((cheque.text()=='') || (cheque.text()=='SR')){
                var billStatus = 'FSR';
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('manager/FieldStaffController/confirmFSR');?>",
                        data:{"billStatus" : billStatus,"billId" : id},
                        success: function (data) {
                            window.parent.location.reload(true);
                        }  
                });
            }else if(cheque.text()!='' || cheque.text()==FSR){
                var billStatus = '';
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('manager/FieldStaffController/cancelFSR');?>",
                        data:{"billStatus" : billStatus,"billId" : id},
                        success: function (data) {
                            window.parent.location.reload(true);
                        }  
                });
            }
        }  
        if(data=='Slip'){
            if((slip.text()=='') || (slip.text()=='SR')){
                var billStatus = 'FSR';
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('manager/FieldStaffController/confirmFSR');?>",
                        data:{"billStatus" : billStatus,"billId" : id},
                        success: function (data) {
                            window.parent.location.reload(true);
                        }  
                });
            }else if(Slip.text()!='' || Slip.text()==resend){
                var billStatus = '';
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('manager/FieldStaffController/cancelFSR');?>",
                        data:{"billStatus" : billStatus,"billId" : id},
                        success: function (data) {
                            window.parent.location.reload(true);
                        }  
                });
            }
        }
    }
</script>

<script type="text/javascript">
    function checkCashBillAmt(netAmt,pendAmt,prevSR,prevRecAmt,cheque,neft,sr,chequePenalty,cd,debit,offceAdj,otherAdj,debitNote,fsOtherAdj,debitJournal){
        var netAmount=parseFloat(netAmt)+parseFloat(chequePenalty)+parseFloat(debitNote)+parseFloat(debitJournal);
        var pendingAmount=parseFloat(pendAmt);
        var amount=parseFloat(document.getElementById('amt').value);
        var total=(parseFloat(prevSR)+parseFloat(prevRecAmt)+parseFloat(amount)+parseFloat(sr)+parseFloat(cheque)+parseFloat(neft))+parseFloat(cd)+parseFloat(debit)+parseFloat(offceAdj)+parseFloat(otherAdj)+parseFloat(fsOtherAdj);
        if(total>netAmount){
            var msg=document.getElementById('cashRes');
            msg.innerHTML="Sorry!.. Total collected amount( "+total+" ) will greater than net amount.";
            return false;die();
        }else{
            return true;
        }
    }

    function checkChequeBillAmt(netAmt,pendAmt,prevSR,prevRecAmt,cash,neft,sr,chequePenalty,cd,debit,offceAdj,otherAdj,debitNote,fsOtherAdj,debitJournal){
        var netAmount=parseFloat(netAmt)+parseFloat(chequePenalty)+parseFloat(debitNote)+parseFloat(debitJournal);
        var pendingAmount=parseFloat(pendAmt);
        var amount=parseFloat(document.getElementById('amt').value);
        var total=parseFloat(prevSR)+parseFloat(prevRecAmt)+parseFloat(amount)+parseFloat(sr)+parseFloat(cash)+parseFloat(neft)+parseFloat(cd)+parseFloat(debit)+parseFloat(offceAdj)+parseFloat(otherAdj)+parseFloat(fsOtherAdj);
        if(total>netAmount){
            var msg=document.getElementById('chequeRes');
            msg.innerHTML="Sorry!.. Total collected amount( "+total+" ) will greater than net amount.";
            return false;die();
        }else{
            return true;
        }
    }

    function checkNeftBillAmt(netAmt,pendAmt,prevSR,prevRecAmt,cash,cheque,sr,chequePenalty,cd,debit,offceAdj,otherAdj,debitNote,fsOtherAdj,debitJournal){
        var netAmount=parseFloat(netAmt)+parseFloat(chequePenalty)+parseFloat(debitNote)+parseFloat(debitJournal);
        var pendingAmount=parseFloat(pendAmt);
        var amount=parseFloat(document.getElementById('amt').value);
        var total=parseFloat(prevSR)+parseFloat(prevRecAmt)+parseFloat(amount)+parseFloat(sr)+parseFloat(cash)+parseFloat(cheque)+parseFloat(cd)+parseFloat(debit)+parseFloat(offceAdj)+parseFloat(otherAdj)+parseFloat(fsOtherAdj);
        if(total>netAmount){
            var msg=document.getElementById('neftRes');
            msg.innerHTML="Sorry!.. Total collected amount( "+total+" ) will greater than net amount.";
            return false;die();
        }else{
            return true;
        }
    }

    function checkOtherAdjBillAmt(netAmt,pendAmt,prevSR,prevRecAmt,cash,cheque,sr,chequePenalty,cd,debit,offceAdj,otherAdj,debitNote,neft,debitJournal){
        var netAmount=parseFloat(netAmt)+parseFloat(chequePenalty)+parseFloat(debitNote)+parseFloat(debitJournal);
        var pendingAmount=parseFloat(pendAmt);
        var amount=parseFloat(document.getElementById('amt').value);
        var total=parseFloat(prevSR)+parseFloat(prevRecAmt)+parseFloat(amount)+parseFloat(sr)+parseFloat(cash)+parseFloat(cheque)+parseFloat(neft)+parseFloat(cd)+parseFloat(debit)+parseFloat(offceAdj)+parseFloat(otherAdj)+parseFloat(neft);
        if(total>netAmount){
            var msg=document.getElementById('otherRes');
            msg.innerHTML="Sorry!.. Total collected amount( "+total+" ) will greater than net amount.";
            return false;die();
        }else{
            return true;
        }
    }
</script>

<script>
    function getFSR(){
       var table = document.getElementById('SrTable');
        for (var i = 1; i < table.rows.length-1; i++) {
          if (table.rows[i].cells.length) {
            var billedQty = (table.rows[i].cells[3].textContent.trim());
            var oldSR = (table.rows[i].cells[5].textContent.trim());
            billedQty=billedQty-oldSR;
            var srQty = (table.rows[i].cells[6].textContent.trim());
            table.rows[i].cells[6].innerHTML='<input id="returnedQty" type="text" class="form-control" name="returnedQty[]" value="'+billedQty+'">';
          }
        }
    }
</script>



<script type="text/javascript">
    $(document).on('click','#sr_ok_id',function(){
        window.parent.location.reload(true);
    });
</script>

<script>
   function editable_btn(id){
        document.getElementsByClassName("editable_id"+id).style.display = "block"; 
   }
</script>

<script>
    $(document).on('click','#sr_save_id',function(){
        var allocation_Id=$('#sr_allocationID').val();
        var bill_id=$('#billId_id').val();
        
        alert(allocation_Id+' '+bill_id);



    });
</script>

<script type="text/javascript">
  
  $(document).on('click','#save_btn_id',function(){
          var noId=$(this).attr('data-noId');
          var allocationID=$(this).attr("data-allocationId"); 
          var billdetailId=$(this).attr("data-billdetailId"); 
          var billId=$(this).attr("data-billId"); 
          var returnedQty=$('#returnedQty'+noId).val();
          var returnedAmt=$('#returnAmt_id'+noId).val();

          $.ajax({
            url : "<?php echo site_url('manager/FieldStaffController/updateSrByBillDetailsId');?>",
            method : "POST",
            data : {billdetailId : billdetailId,billId:billId,returnedQty:returnedQty,returnedAmt:returnedAmt,allocationID:allocationID},
            success :function(data){
                $('#msg').html('<span style="color: red;">'+data+'</span>');
            }
          });
    });
</script>

<script type="text/javascript">
  
  $(document).on('click','#update_btn_id',function(){
    alert('hi');
          var noId=$(this).attr('data-noId');
          var allocationID=$(this).attr("data-allocationId"); 
          var billdetailId=$(this).attr("data-billdetailId"); 
          var billId=$(this).attr("data-billId"); 
          var returnedQty=$('#returnedQty'+noId).val();
          var returnedAmt=$('#returnAmt_id'+noId).val();

          $.ajax({
            url : "<?php echo site_url('manager/FieldStaffController/saveUpdatedSrByBillDetailsId');?>",
            method : "POST",
            data : {billdetailId : billdetailId,billId:billId,returnedQty:returnedQty,returnedAmt:returnedAmt,allocationID:allocationID},
            success :function(data){
                $('#msg').html('<span style="color: red;">'+data+'</span>');
            }
          });
    });
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
            }else{
                var msg=""
                document.getElementById('data_err'+no).innerHTML=msg;
                document.getElementById('all_id').innerHTML=msg;
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

<script type = "text/javascript" >  
    window.history.pushState(null, "", window.location.href);
    window.onpopstate = function () {
        window.history.pushState(null, "", window.location.href);
    };
</script> 