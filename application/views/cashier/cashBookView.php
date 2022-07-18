<?php $this->load->view('/layouts/commanHeader'); ?>

<style type="text/css">
    @media screen and (min-width: 900px) {
        .modal-dialog {
          width: 900px; /* New width for default modal */
        }
        .modal-sm {
          width: 900px; /* New width for small modal */
        }
    }

    @media screen and (min-width: 900px) {
        .modal-lg {
          width: 900px; /* New width for large modal */
        }
    }

</style>

<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>
    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/>
     <section class="col-md-12 box">
        <div class="container-fluid">
            
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">

                      <?php 

                          $route=$this->CashBookModel->load('route',$allocations[0]['routId']);
                          $emp1=$this->CashBookModel->load('employee',$allocations[0]['fieldStaffCode1']);
                      ?>
                        <div class="header">
                           <h2>
                             Cash & Cheque Master
                          </h2><br>
                         
                          <div class="row cust-tbl" style="display: flex;align-items: center;">
                              <div class="col-md-3"<b>Allocation No.:</b> <?php echo $allocations[0]['allocationCode']; ?></div>
                              <div class="col-md-2"<b>Company :</b> <?php echo $allocations[0]['company']; ?></div>
                              <div class="col-md-3" <b>Deliveryman Name :</b> <?php echo $emp1[0]['name']; ?></div>
                              <div class="col-md-3"<b>Route Name :</b> <?php echo $route[0]['name']; ?></div>
                              <div class="col-md-2">
                              <button data-toggle="modal" data-aId="<?php echo $notes[0]['id'];?>" data-id="<?php echo $notes[0]['allocationId'];?>" id="modalLinkBtn" data-target="#myModal" class=" btn btn-primary btnStyle m-t-15 waves-effect">
                              <i class="material-icons">save</i><span class="icon-name">  Save & Confirm </span>
                            </button>
                              </div>
                          </div>
                          
                          <div class="row" style="display: flex;align-items: center;">
                          <p align="right">
                           <!--  <button  onclick="totalCollect('<?php echo $notes[0]['id'];?>','<?php echo $notes[0]['allocationId'];?>');" class="btn btn-primary m-t-15 waves-effect">
                                <span class="icon-name"> Save </span>
                            </button> -->
                            <div class="col-md-8 table-responsive">
                              <table class="table table-bordered cust-tbl display nowrap" style="width: 100%">
                                  <tr class="gray">
                                      <th><center>Particulars</center></th>
                                      <th><center>Total Bills</center></th>
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
                                      <th><center>Total Value</center></th>
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
                         
                           <!--  <button data-toggle="modal" data-aId="<?php echo $notes[0]['id'];?>" data-id="<?php echo $notes[0]['allocationId'];?>" data-target="#myModal" class="modalLink btn btn-primary m-t-15 waves-effect">
                                <span class="icon-name"> Save & Confirm </span>
                            </button>
                           -->   
                        </p>
                      </div>

                        </div>
                        <div class="body">


                            <div class="table-responsive">

                                <div class="col-md-12">
                                  <div class="col-md-7">
                                <table id="recTblIds" class="table table-bordered cust-tbl display nowrap" data-page-length='100'>
                                    <thead>
                                    <tr>
                                        <th colspan="5"><center>Cheque/NEFT Reconciliation</center></th>
                                        <th> <button type="button" id="insert-ins" class="btn btn-sm btn-primary btnStyle m-t-15 waves-effect">
                                              <span class="icon-name">Clear Selected</span>
                                    </button></th>
                                    </tr>
                                    <tr>
                                        
                                        <th>Bill No</th>
                                        <th>Retailer Name</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>
                                          <!-- <input class="checkall"  type="checkbox" name="selValue" id="basic_checkbox"/> -->
                                          <label for="basic_checkbox">Received</label>
                                        </th>
                                        <th>
                                          <!-- <input class="checkallForNotReceived"  type="checkbox" name="selValueForNotReceived" id="basic_checkbox_forNotReceived"/> -->
                                          <label for="basic_checkbox_forNotReceived">Not Received</label>
                                        </th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody id="updateLost">
                              <?php
                                  foreach($bills as $itm){
                                    $billD=$this->CashBookModel->loadChkBillPaymentDetails('billpayments',$itm['id'],$allocations[0]['id'],"NEFT");
                                    if(!empty($billD) && ($billD[0]['paidAmount'] > 0)){
                              ?>
                                      <tr>  
                                          
                                          <td><?php echo $itm['billNo']?></td>
                                         
										  <td class="CellWithComment"><?php 	
										    $retailerName=substr($itm['retailerName'], 0, 15);
                                            echo rtrim($retailerName);?>
											<span class="CellComment"><?php echo $result =substr($itm['retailerName'],0); ?></span>
										  </td>
                                          <td><?php echo $billD[0]['paidAmount']; ?></td>
                                          <td><?php echo 'NEFT';?></td>
                                          
                                          <td>
                                          <?php if($billD[0]['isLostStatus'] == 0){ ?>
                                          <!-- <button style="font-size: 11px" id="neftReceived" onclick="updateChequeNeftRec(this,'<?php echo $itm['id'];?>','<?php echo $itm['fsNeftAmt']?>','NEFT','<?php echo $allocations[0]['id'];?>');" class="btn btn-primary btn-sm waves-effect">
                                                    <span class="icon-name">Received</span>
                                          </button>  -->
                                          <!-- <button style="font-size: 11px" id="neftNotReceived" onclick="updateChequeNeft(this,'<?php echo $itm['id'];?>','<?php echo $itm['fsNeftAmt']?>','NEFT','<?php echo $allocations[0]['id'];?>')" class="btn btn-primary btn-sm waves-effect">
                                              <span class="icon-name">Not Received</span>
                                          </button>  -->
                                              <input class="checkhour" type="checkbox" onclick="checkForReceive(<?php echo $billD[0]['id']; ?>)" name="selValue" value="<?php echo $allocations[0]['id'].':'.$itm['id'].':'.$itm['fsNeftAmt'].':'.'NEFT'; ?>" id="basic_checkbox_neft<?php echo $billD[0]['id']; ?>" />
                                              <label for="basic_checkbox_neft<?php echo $billD[0]['id']; ?>"></label>
                                        
                                        <?php } else if($billD[0]['isLostStatus'] == 2){ ?>
                                            <i class="material-icons">check</i> 
                                        <?php } ?>
                                              
                                          </td>
                                          <td>
                                            <?php if($billD[0]['isLostStatus'] == 0){ ?>
                                            <!-- <button style="font-size: 11px" id="neftReceived" onclick="updateChequeNeftRec(this,'<?php echo $itm['id'];?>','<?php echo $itm['fsNeftAmt']?>','NEFT','<?php echo $allocations[0]['id'];?>');" class="btn btn-primary btn-sm waves-effect">
                                                      <span class="icon-name">Received</span>
                                            </button>  -->
                                            <!-- <button style="font-size: 11px" id="neftNotReceived" onclick="updateChequeNeft(this,'<?php echo $itm['id'];?>','<?php echo $itm['fsNeftAmt']?>','NEFT','<?php echo $allocations[0]['id'];?>')" class="btn btn-primary btn-sm waves-effect">
                                                <span class="icon-name">Not Received</span>
                                            </button>  -->
                                            <input class="customchkClose" onclick="checkForNotReceive(<?php echo $billD[0]['id']; ?>)" type="checkbox" name="selValueForNotReceived" value="<?php echo $allocations[0]['id'].':'.$itm['id'].':'.$itm['fsNeftAmt'].':'.'NEFT'; ?>" id="basic_checkbox_neft_notReceived<?php echo $billD[0]['id']; ?>" />
                                            <label for="basic_checkbox_neft_notReceived<?php echo $billD[0]['id']; ?>"></label>
                                          
                                          <?php } else if($billD[0]['isLostStatus'] == 1){ ?>
                                              <i class="material-icons">cancel</i> 
                                          <?php } ?>
                                              
                                          </td>
                                          <td>
                                          <?php if($billD[0]['isLostStatus'] == 0){ ?>
                                            
                                          <?php } else if($billD[0]['isLostStatus'] == 2){ ?>
                                              <i class="material-icons">check</i> 
                                          <?php } else if($billD[0]['isLostStatus'] == 1){ ?>
                                              <i class="material-icons">cancel</i> 
                                          <?php } ?>
                                          </td>
                                      </tr>
                              <?php       }
                                        // }
                                      } 

                                  foreach($bills as $itm){
                                    $billD=$this->CashBookModel->loadChkBillPaymentDetails('billpayments',$itm['id'],$allocations[0]['id'],"Cheque");
                                      if(!empty($billD) && ($billD[0]['paidAmount'] > 0)){
                              ?>
                                          <tr>
                                            <td><?php echo $itm['billNo']?></td>
                                          <td><?php echo $itm['retailerName']?></td>
                                           <td><?php echo $billD[0]['paidAmount']; ?></td>
                                          <td><?php echo 'Cheque'; ?></td>
                                          

                                        <td>
                                            <?php if($billD[0]['isLostStatus'] == 0){ ?>
                                              <!-- <button style="font-size: 11px" id="chequeReceived" onclick="updateChequeNeftRec(this,'<?php echo $itm['id'];?>','<?php echo $itm['fsChequeAmt']?>','Cheque','<?php echo $allocations[0]['id'];?>');" class="btn btn-primary btn-sm waves-effect">
                                                        <span class="icon-name">Received</span>
                                              </button>  -->
                                              <!-- <button style="font-size: 11px" id="chequeNotReceived" onclick="updateChequeNeft(this,'<?php echo $itm['id'];?>','<?php echo $itm['fsChequeAmt']?>','Cheque','<?php echo $allocations[0]['id'];?>')" class="btn btn-primary btn-sm waves-effect">
                                                  <span class="icon-name">Not Received</span>
                                              </button>  -->
                                              <input class="checkhour" type="checkbox" onclick="checkForReceive(<?php echo $billD[0]['id']; ?>)" name="selValue" value="<?php echo $allocations[0]['id'].':'.$itm['id'].':'.$itm['fsChequeAmt'].':'.'Cheque'; ?>" id="basic_checkbox_neft<?php echo $billD[0]['id']; ?>" />
                                              <label for="basic_checkbox_neft<?php echo $billD[0]['id']; ?>"></label>
                                            
                                            <?php } else if($billD[0]['isLostStatus'] == 2){ ?>
                                                <i class="material-icons">check</i> 
                                            <?php } ?>
                                              
                                          </td>
                                          <td>
                                          <?php if($billD[0]['isLostStatus'] == 0){ ?>
                                              <!-- <button style="font-size: 11px" id="chequeReceived" onclick="updateChequeNeftRec(this,'<?php echo $itm['id'];?>','<?php echo $itm['fsChequeAmt']?>','Cheque','<?php echo $allocations[0]['id'];?>');" class="btn btn-primary btn-sm waves-effect">
                                                        <span class="icon-name">Received</span>
                                              </button>  -->
                                              <!-- <button style="font-size: 11px" id="chequeNotReceived" onclick="updateChequeNeft(this,'<?php echo $itm['id'];?>','<?php echo $itm['fsChequeAmt']?>','Cheque','<?php echo $allocations[0]['id'];?>')" class="btn btn-primary btn-sm waves-effect">
                                                  <span class="icon-name">Not Received</span>
                                              </button>  -->
                                              <input class="customchkClose" type="checkbox" onclick="checkForNotReceive(<?php echo $billD[0]['id']; ?>)" name="selValueForNotReceived" value="<?php echo $allocations[0]['id'].':'.$itm['id'].':'.$itm['fsChequeAmt'].':'.'Cheque'; ?>" id="basic_checkbox_neft_notReceived<?php echo $billD[0]['id']; ?>" />
                                              <label for="basic_checkbox_neft_notReceived<?php echo $billD[0]['id']; ?>"></label>
                                            <?php }else if($billD[0]['isLostStatus'] == 1){ ?>
                                                <i class="material-icons">cancel</i> 
                                            
                                            <?php } ?>
                                              
                                          </td>
                                          <td>
                                          <?php if($billD[0]['isLostStatus'] == 0){ ?>
                                            
                                          <?php } else if($billD[0]['isLostStatus'] == 2){ ?>
                                              <i class="material-icons">check</i> 
                                          <?php } else if($billD[0]['isLostStatus'] == 1){ ?>
                                              <i class="material-icons">cancel</i> 
                                          <?php } ?>
                                          </td>
                                      </tr>
                              <?php       }
                                      }
                              ?>
                                    </tbody>
                                </table>
                            </div>
                                
                            <div class="col-md-5">
                                <table id="recTblId" class="table table-bordered cust-tbl js-basic-example DataTable display nowrap dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th colspan="2"><center>Other Expenses</center></th>
                                            <th colspan="2"><center>Status</center></th>
                                        </tr>
                                    </thead>
                                    <tbody id="updateNotesList">
                                      <?php 
                                      if(!empty($notes)){
                                       
                                          if(($notes[0]['statusParking'] != 1 && $notes[0]['statusParking'] != 2)){

                                       ?>

                                        <tr>
                                            <td><?php echo $firstTitle; ?></td>
                                            <td>
                                              <input style="height:25px;width: 50%" onChange="return expVal()"  onkeypress="return isNumber(event);" type="text" id="prk" name="prk" value="<?php if(!empty($notes)){echo ($notes[0]['parking']*1); } ?>">
                                              </td>
                                            <td>
                                              <?php if(!empty($notes)){ ?>
                                                <button style="font-size: 11px" id="parkingReceive" onclick="updateParkingAllow('parking','<?php echo $notes[0]['id'];?>','<?php echo $notes[0]['allocationId'];?>');" class="btn btnStyle btn-sm waves-effect">
                                                    <span class="icon-name">Allow</span>
                                                </button> 
                                                <button style="font-size: 11px" id="parkingNotReceive" onclick="updateParkingDisAllow('parking','<?php echo $notes[0]['id'];?>','<?php echo $notes[0]['allocationId'];?>');notReceived('parking','<?php echo $notes[0]['parking'];?>');" class="btn btn-danger btn-sm waves-effect">
                                                    <span class="icon-name">Disallow</span>
                                                </button> 
                                                <?php }else{?>
                                                  <button style="font-size: 11px" id="parkingReceive" onclick="removeMe(this);" disabled="" class="btn btn-primary btnStyle btn-sm waves-effect">
                                                    <span class="icon-name">Allow</span>
                                                </button> 
                                                <button style="font-size: 11px" id="parkingNotReceive" onclick="removeMe(this);" disabled="" class="btn btn-danger btn-sm waves-effect">
                                                    <span class="icon-name">Disallow</span>
                                                </button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php    
                                          }else if(($notes[0]['statusParking'] == 1 || $notes[0]['statusParking'] == 2)){
                                    ?>
                                          <tr>
                                          <td><?php echo $firstTitle; ?></td>
                                            <td>
                                              <span><?php if(!empty($notes)){echo ($notes[0]['parking']*1); } ?></span>
                                              
                                              </td>
                                            <td>
                                              
                                            </td>
                                        </tr>

                                    <?php
                                          }
                                        } 

                                        if(!empty($notes)){
                                          if(($notes[0]['statusCng'] != 1 && $notes[0]['statusCng'] != 2)){
                                    ?>
                                        <tr>
                                        <td><?php echo $secondTitle; ?></td>
                                             <td>
                                              <input style="height:25px;width: 50%" onkeypress="return isNumber(event);" onChange="return expVal()" type="text" id="cngValAmt" name="cngValAmt" value="<?php if(!empty($notes)){echo ($notes[0]['cng']*1); } ?>">
                                              </td>
                                             <td>
                                               <?php if(!empty($notes)){ ?>
                                                <button style="font-size: 11px" id="cngReceived" onclick="updateCngAllow('cng','<?php echo $notes[0]['id'];?>','<?php echo $notes[0]['allocationId'];?>');" class="btn btn-primary btnStyle btn-sm waves-effect">
                                                    <span class="icon-name">Allow</span>
                                                </button> 
                                                <button style="font-size: 11px" id="cngNotReceived" onclick="updateCngDisAllow('cng','<?php echo $notes[0]['id'];?>','<?php echo $notes[0]['allocationId'];?>');notReceived('cng','<?php echo $notes[0]['cng'];?>');" class="btn btn-danger btn-sm waves-effect">
                                                    <span class="icon-name">Disallow</span>
                                                </button> 
                                                 <?php }else{?>
                                                     <button style="font-size: 11px" id="cngReceived" onclick="removeMe(this);" disabled="" class="btn btn-primary btnStyle btn-sm waves-effect">
                                                    <span class="icon-name">Allow</span>
                                                  </button> 
                                                  <button style="font-size: 11px" id="cngNotReceived" onclick="removeMe(this);" disabled="" class="btn btn-danger btn-sm waves-effect">
                                                      <span class="icon-name">Disallow</span>
                                                  </button> 
                                                  <?php } ?>
                                            </td>
                                        </tr>
                                    <?php
                                          }else if(($notes[0]['statusCng'] == 1 || $notes[0]['statusCng'] == 2)){
                                    ?>
                                        <tr>
                                        <td><?php echo $secondTitle; ?></td>
                                             <td>
                                              <span><?php if(!empty($notes)){echo ($notes[0]['cng']*1); } ?></span>
                                              </td>
                                             <td>
                                               
                                            </td>
                                        </tr>

                                    <?php        
                                          }
                                        }  
                                      if(!empty($notes)){
                                          if(($notes[0]['statusChallan'] !=1 && $notes[0]['statusChallan'] != 2)){
                                    ?>    
                                        <tr>
                                        <td><?php echo $thirdTitle; ?></td>
                                            <td>
                                              <input style="height:25px;width: 50%" onChange="return expVal()" onkeypress="return isNumber(event);" type="text" id="clnValAmt" name="clnValAmt" value="<?php if(!empty($notes)){echo ($notes[0]['challan']*1); } ?>">
                                              </td>
                                             <td>
                                              <?php if(!empty($notes)){ ?>
                                                <button style="font-size: 11px" id="challanReceive" onclick="updateChallanAllow('challan','<?php echo $notes[0]['id'];?>','<?php echo $notes[0]['allocationId'];?>');" class="btn btn-primary btnStyle btn-sm waves-effect">
                                                    <span class="icon-name">Allow</span>
                                                </button> 
                                                <button style="font-size: 11px" id="challanNotReceive" onclick="updateChallanDisAllow('challan','<?php echo $notes[0]['id'];?>','<?php echo $notes[0]['allocationId'];?>');notReceived('challan','<?php echo $notes[0]['challan'];?>');" class="btn btn-danger btn-sm waves-effect">
                                                    <span class="icon-name">Disallow</span>
                                                </button> 
                                                 <?php }else{?>
                                                    <button style="font-size: 11px" id="challanReceive" onclick="removeMe(this);" disabled="" class="btn btn-primary btnStyle btn-sm waves-effect">
                                                    <span class="icon-name">Allow</span>
                                                </button> 
                                                <button style="font-size: 11px" id="challanNotReceive" onclick="removeMe(this);" disabled="" class="btn btn-danger btn-sm waves-effect">
                                                    <span class="icon-name">Disallow</span>
                                                </button> 
                                                 <?php } ?>
                                            </td>
                                        </tr>
                                  <?php
                                        }else if(($notes[0]['statusChallan'] ==1 || $notes[0]['statusChallan'] == 2)){
                                  ?>
                                          <td><?php echo $thirdTitle; ?></td>
                                            <td>
                                             <span><?php if(!empty($notes)){echo ($notes[0]['challan']*1); } ?></span>
                                              </td>
                                             <td>
                                              
                                            </td>
                                          
                                  <?php

                                        }
                                      }  
                                  ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">

                          <div class="col-md-5">
                                <table class="table table-bordered cust-tbl js-basic-example DataTable display nowrap" id="example" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th colspan="2"><center>Cash and Cheque Reconciliation</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                          <tr>
                                            <td>Number of Cheques/NEFT</td>
                                            <td align="right" id="chequeTotal"><?php if(!empty($chequeTotal)){echo $chequeCount;} ?></td>
                                        </tr>
                                        <tr>
                                            <td>Cash As per Accounts</td>
                                            <td align="right" id="cashTotal"><?php if(!empty($cashTotal)){echo ($cashTotal); }else{ echo "0"; } ?></td>
                                        </tr>
                                        <tr>
                                            <td>Market Expenses</td>
                                            <td align="right" id="expenses">
                                              <?php 
                                              // if(($expenses))
                                              // {
                                                echo $expenses;
                                              // } 
                                              ?></td>
                                        </tr>
                                         <tr>
                                            <td>Cash To be taken</td>
                                            <td align="right" id="toBeTaken">
                                              <?php echo ($cashTotal-$expenses); ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                  </table>

                                  <table class="table table-bordered cust-tbl js-basic-example DataTable" id="example" data-page-length='100'>
                                    
                                    <tbody>
                                        <tr>
                                            <td>Cash Already Taken</td>
                                            <td align="right"><span id="cashTaken"><?php if(!empty($notes)){echo ($notes[0]['collectedAmt']*1); } ?></span></td>
                                            
                                        </tr>
                                        <tr>
                                            <td>Balance Amount</td>
                                            <td align="right"><span id="balAmt">
                                              <?php if(!empty($notes)){echo ($notes[0]['balanceAmt']*1); } ?>
                                            </span></td>
                                            
                                        </tr>
                                      </tbody>
                                    </table>

                                    <table class="table table-bordered cust-tbl js-basic-example DataTable" id="example" data-page-length='100'>
                                    
                                    <tbody>
                                        <tr>
                                            <td>Physical Cash</td>
                                            <td align="right"><span id="phyCash">0</span></td>
                                            
                                        </tr>
                                        
                                        <tr>
                                            <td id="shortCashStatus">Excess/Short</td>
                                            <td align="right" id="shortCash"><span id='shCash' style='color:red;font-weight: 300;'>
                                              <?php
                                                  if(!empty($notes)){
                                                      if($notes[0]['balanceAmt'] !=0){
                                                        echo $notes[0]['balanceAmt']; 
                                                      } else {
                                                         echo ($cashTotal-$expenses);
                                                      }
                                                  }
                                              ?>
                                                
                                              </span></td>
                                            <!-- <td align="right" id="shortCash"><span id='shCash' style='color:red;font-size:11px;'><?php if(!empty($notes)){echo $notes[0]['balanceAmt'];} ?></span></td> -->
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                                
                            <div class="col-md-7">
                                <table class="table table-bordered cust-tbl js-basic-example DataTable" data-page-length='100'>
                                    <thead>
                                    <tr>
                                        <th><center>Denominations</center></th>
                                        <th style="width: 140px;"><center>As per Supplier</center></th>
                                        <th><center>Value</center></th>
                                        <th style="width: 230px;"><center>Received by Accountant</center></th>
                                        <th><center>Value</center></th>
                                    </tr>
                                    </thead>
                                     <tbody>
                                    <tr>
                                        <td align="right">2000</td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['note2000'];} ?></td>
                                         <td align="right">
                                        <?php if(!empty($notes)){echo ($notes[0]['note2000'] *2000);} ?>
                                         </td>
                                        <td><input style="height:25px;width: 40%" onkeypress="return isNumber(event)" onkeyup="calMoney();" type="text" name="add2000" id="add2000"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad2000"></span></td>
                                    </tr>
                                     <!-- <tr>
                                        <td align="right">1000</td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['note1000'];} ?></td>
                                          <td align="right">
                                        <?php if(!empty($notes)){echo ($notes[0]['note1000'] *1000);} ?>
                                         </td>
                                        <td><input style="height:25px;width: 40%" onkeypress="return isNumber(event)" onkeyup="calMoney();" type="text" name="add1000" id="add1000"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad1000"></span></td>
                                    </tr> -->
                                     <tr>
                                        <td align="right">500</td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['note500'];} ?></td>
                                          <td align="right">
                                        <?php if(!empty($notes)){echo ($notes[0]['note500'] *500);} ?>
                                         </td>
                                        <td><input style="height:25px;width: 40%" onkeypress="return isNumber(event)" onkeyup="calMoney();" type="text" name="add500" id="add500"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad500"></span></td>
                                    </tr>
                                    <tr>
                                        <td align="right">200</td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['note200'];} ?></td>
                                          <td align="right">
                                        <?php if(!empty($notes)){echo ($notes[0]['note200'] *200);} ?>
                                         </td>
                                        <td><input style="height:25px;width: 40%" onkeypress="return isNumber(event)" onkeyup="calMoney();" type="text" name="add200" id="add200"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad200"></span></td>
                                    </tr>
                                     <tr>
                                        <td align="right">100</td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['note100'];} ?></td>
                                          <td align="right">
                                        <?php if(!empty($notes)){echo ($notes[0]['note100'] *100);} ?>
                                         </td>
                                        <td><input style="height:25px;width: 40%" onkeypress="return isNumber(event)" onkeyup="calMoney();" type="text" name="add100" id="add100"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad100"></span></td>
                                    </tr>
                                     <tr>
                                        <td align="right">50</td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['note50'];} ?></td>
                                         <td align="right">
                                        <?php if(!empty($notes)){echo ($notes[0]['note50'] *50);} ?>
                                         </td>
                                        <td><input style="height:25px;width: 40%" onkeypress="return isNumber(event)" onkeyup="calMoney();" type="text" name="add50" id="add50"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad50"></span></td>
                                    </tr>
                                     <tr>
                                        <td align="right">20</td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['note20'];} ?></td>
                                         <td align="right">
                                        <?php if(!empty($notes)){echo ($notes[0]['note20'] *20);} ?>
                                         </td>
                                        <td><input style="height:25px;width: 40%" onkeypress="return isNumber(event)" onkeyup="calMoney();" type="text" name="add20" id="add20" autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad20"></span></td>
                                    </tr>
                                    <tr>
                                        <td align="right">10</td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['note10'];} ?></td>
                                          <td align="right">
                                        <?php if(!empty($notes)){echo ($notes[0]['note10'] *10);} ?>
                                         </td>
                                        <td><input style="height:25px;width: 40%" onkeypress="return isNumber(event)" onkeyup="calMoney();" type="text" name="add10" id="add10"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad10"></span></td>
                                    </tr>
                                    <tr>
                                        <td align="center">Coins</td>
                                         <td align="right"><?php if(!empty($notes)){echo ($notes[0]['coins']*1); } ?></td>
                                         <td align="right"><?php if(!empty($notes)){echo ($notes[0]['coins']*1); } ?></td>
                                        <td><input style="height:25px;width: 40%" onkeypress="return isNumber(event)" onkeyup="calMoney();" type="text" name="coin" id="coin"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="coins"></span></td>
                                    </tr>
                                     <tr>
                                        <td colspan="2" align="center">Total Currency</td>
                                        <td align="right"><?php if(!empty($total)){ echo number_format($total);}?></td>
                                        <td align="center">Total Actual</td>
                                        <td align="right"><span id="totalActual">0</span></td>
                                    </tr>

                                    <div id="emp"></div>
                                   
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                            </div><!-- table 1 end-->

                           
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </section>

<div class="container">
  <div class="modal fade modal-xl" id="myModal" role="dialog">
    <div class="modal-dialog">
      <div id="cashierDataMocal" class="modal-content">
        
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('/layouts/footerDataTable'); ?>


<script>
    function addNewRow(){
        var empName=$('#empCm').val();
        var empId = $('#empNameList').find('option[value="'+empName+'"]').attr('id');
        var chk=false;
         $('table tr').each(function(){
            if($(this).find('td').eq(0).text() == empName){
                chk=true;
            }
        });
        if(!chk){
          $("#myRowTable").append("<tr><td>"+empName+"</td><td><input id='empAmt' type='text' name='empAmt[]'> <td><input id='empAmtComment' type='text' name='empAmtComment[]'></td><td><input type='hidden' id='dbt_empId' name='empId[]' value="+empId+"></td><td><button class='btn btn-xs btn-danger' style='float: right;' onclick='Delete(this);'><i class='fa fa-close'></i></button></td></tr>");
        }
        $('#empCm').val('');
        
    }

    function Delete(t){
        $(t).closest('tr').remove();
    }

    function calEmpAmt() {
      var inputs = document.getElementsByName('empAmt[]');
      var sum = 0;
      for(var i = 0; i<inputs.length; i++){
        sum += parseInt(inputs[i].value);
      }
   
      var totalAmt=$('#totalCalAmt').val();
      alert(sum+' '+totalAmt);
      if(sum==totalAmt){
          $('#err').text("");
          return true;
      }else{
          $('#err').text("Total debit should be equal to employee wise total");
          return false;
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
            if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
                return true;

            else if ((("0123456789").indexOf(keychar) > -1))
                return true;

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
    function notReceived(category,amt){
        if(category=='parking'){
            var amt=document.getElementById('prk').innerText;
            var expenses=document.getElementById('expenses').innerText;
            if(expenses==''){
              expenses=0;
            }
            expenses=parseFloat(expenses);
            expenses=parseFloat(expenses) - parseFloat(amt);
            document.getElementById('expenses').innerHTML=expenses;

            var shortCash=document.getElementById('shortCash').innerText;
            if(shortCash==''){
              shortCash=0;
            }
            shortCash=parseFloat(shortCash);
            shortCash=parseFloat(amt) + parseFloat(shortCash);

           

            if(shortCash >0){
               document.getElementById('shortCash').innerHTML="<span id='shCash' style='color:red;font-size:11px;'>"+shortCash+"</span>";
              document.getElementById('shortCashStatus').innerHTML="<span style='color: red;font-size:11px;'>Short Cash</span>";
            }else if(shortCash <= 0){
               document.getElementById('shortCash').innerHTML="<span id='shCash' style='color: #037921;font-size:11px;'>"+shortCash+"</span>";
              document.getElementById('shortCashStatus').innerHTML="<span style='color: #037921;font-size:11px;'>Excess Cash</span>";
            }
        }else if(category=='challan'){
            var amt=document.getElementById('clnValAmt').innerText;
            var expenses=document.getElementById('expenses').innerText;
            if(expenses==''){
              expenses=0;
            }
            expenses=parseFloat(expenses);
            expenses=parseFloat(expenses) - parseFloat(amt);
            document.getElementById('expenses').innerHTML=expenses;

            var shortCash=document.getElementById('shortCash').innerText;
            if(shortCash==''){
              shortCash=0;
            }
            shortCash=parseFloat(shortCash);
            shortCash=parseFloat(amt) + parseFloat(shortCash);

            if(shortCash >0){
               document.getElementById('shortCash').innerHTML="<span id='shCash' style='color:red;font-size:11px;'>"+shortCash+"</span>";
              document.getElementById('shortCashStatus').innerHTML="<span style='color: red;font-size:11px;'>Short Cash</span>";
            }else if(shortCash <= 0){
               document.getElementById('shortCash').innerHTML="<span id='shCash' style='color: #037921;font-size:11px;'>"+shortCash+"</span>";
              document.getElementById('shortCashStatus').innerHTML="<span style='color: #037921;font-size:11px;'>Excess Cash</span>";
            }
        }else if(category=='cng'){
            var amt=document.getElementById('cngValAmt').innerText;
            var expenses=document.getElementById('expenses').innerText;
            if(expenses==''){
              expenses=0;
            }
            expenses=parseFloat(expenses);
            expenses=parseFloat(expenses) - parseFloat(amt);
            document.getElementById('expenses').innerHTML=expenses;

            var shortCash=document.getElementById('shortCash').innerText;
            if(shortCash==''){
              shortCash=0;
            }
            shortCash=parseFloat(shortCash);
            shortCash=parseFloat(amt) + parseFloat(shortCash);

           

            if(shortCash >0){
               document.getElementById('shortCash').innerHTML="<span id='shCash' style='color:red;font-size:11px;'>"+shortCash+"</span>";
              document.getElementById('shortCashStatus').innerHTML="<span style='color: red;font-size:11px;'>Short Cash</span>";
            }else if(shortCash <= 0){
               document.getElementById('shortCash').innerHTML="<span id='shCash' style='color: #037921;font-size:11px;'>"+shortCash+"</span>";
              document.getElementById('shortCashStatus').innerHTML="<span style='color: #037921;font-size:11px;'>Excess Cash</span>";
            }
        }        
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

    var shortCash=document.getElementById('shCash').innerText;

    if(shortCash==''){
      shortCash=0;
    } 
    
    document.getElementById('totalActual').innerHTML = (total);
    document.getElementById('phyCash').innerHTML = total;
    
    var expenses=document.getElementById('expenses').innerText;
    expenses=parseFloat(expenses);

    var cashtotal=document.getElementById('cashTotal').innerText;
    var takenCashtotal=document.getElementById('balAmt').innerText;

    var cashTaken=document.getElementById('cashTaken').innerText;
    
  
    if(cashtotal==''){
      cashtotal=0;
    }
    var short=0.0;
    var finalshortCash=0.0;
    // if(takenCashtotal!='' && takenCashtotal!='0.00'){
    //   takenCashtotal=parseFloat(takenCashtotal).toFixed(2);
    //   finalshortCash=(parseFloat(takenCashtotal)-parseFloat(total));
    // }else{
      cashtotal=parseFloat(cashtotal);
      short=parseFloat(cashtotal)-(parseFloat(expenses)+parseFloat(cashTaken));
      finalshortCash=(parseFloat(short)-parseFloat(total));
     
    // }

   if(finalshortCash >0){
         document.getElementById('shortCash').innerHTML="<span id='shCash' style='color:red;font-size:11px;'>"+finalshortCash+"</span>";
        document.getElementById('shortCashStatus').innerHTML="<span  style='color: red;font-size:11px;'>Short Cash</span>";
      }else if(finalshortCash <= 0){
         document.getElementById('shortCash').innerHTML="<span id='shCash' style='color: #037921;font-size:11px;'>"+finalshortCash+"</span>";
        document.getElementById('shortCashStatus').innerHTML="<span style='color: #037921;font-size:11px;'>Excess Cash</span>";
      }

      document.getElementById('balAmt').innerText=finalshortCash;
  }
</script>

<script>
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 45 || charCode > 57) ) {
            return false;
        }
        return true;
    }
</script>

<script type="text/javascript">
    function removeMe(t) {
        $(t).closest('tr').remove();
    }
</script>

<script>
  function expVal(){
    var prkValAmt=document.getElementById('prk').value;
    var cngValAmt=document.getElementById('cngValAmt').value;
    var clnValAmt=document.getElementById('clnValAmt').value;
    var total=parseFloat(prkValAmt)+parseFloat(cngValAmt)+parseFloat(clnValAmt);
   
    document.getElementById('expenses').innerHTML=total;
    var short=document.getElementById('shCash').innerHTML;
    var phyCash=document.getElementById('phyCash').innerHTML;
    var expenses=document.getElementById('expenses').innerHTML;
   
    var cashTotal=document.getElementById('cashTotal').innerHTML;

    if(phyCash!=''){
      var someCash=parseFloat(phyCash)+parseFloat(total);
      someCash=parseFloat(cashTotal)-someCash;
      document.getElementById('shortCash').innerHTML=someCash;
     
      if(someCash >0){
         document.getElementById('shortCash').innerHTML="<span id='shCash' style='color:red;font-size:11px;'>"+someCash+"</span>";
        document.getElementById('shortCashStatus').innerHTML="<span  style='color: red;font-size:11px;'>Short Cash</span>";
      }else if(someCash <= 0){
         document.getElementById('shortCash').innerHTML="<span id='shCash' style='color: #037921;font-size:11px;'>"+someCash+"</span>";
        document.getElementById('shortCashStatus').innerHTML="<span style='color: #037921;font-size:11px;'>Excess Cash</span>";
      }
    }

    document.getElementById('toBeTaken').innerHTML=parseFloat(cashTotal)-parseFloat(expenses);

  }

</script>

<script>
  function cngVal(parkVal){
    var val=document.getElementById('cngValAmt').value;
    if(val < parkVal){
      var total=parkVal-val;
      var expenses=document.getElementById('expenses').innerText;
      if(expenses==''){
        expenses=0;
      }
      expenses=parseFloat(expenses);
     
      expenses=parseFloat(expenses)-parseFloat(total);
      document.getElementById('expenses').innerHTML=expenses;
    }else{
      var total=val-parkVal;
      var expenses=document.getElementById('expenses').innerText;
      if(expenses==''){
        expenses=0;
      }
      expenses=parseFloat(expenses);
      
      expenses=parseFloat(expenses) + parseFloat(total);
      document.getElementById('expenses').innerHTML=expenses;
    }
  }
</script>

 <script type="text/javascript">
  function updateChequeNeft(e,id,amt,mode,allocationId){
      if(mode=="NEFT"){
          $("#neftNotReceived").attr("disabled", true);
      }else{
          $("#chequeNotReceived").attr("disabled", true);
      }
      // alert('hey');
      $.ajax({
          type: "POST",
          url:"<?php echo site_url('cashier/CashBookController/changeStatusLostChequeNeft');?>",
          data:{"id" : id,"amt" : amt,"mode":mode,"allocationId":allocationId},
          success: function (data) {
            // alert(data);
            $('#updateLost').html(data);
          }  
      });
  }

  function updateChequeNeftRec(e,id,amt,mode,allocationId){
      if(mode=="NEFT"){
          $("#neftReceived").attr("disabled", true);
      }else{
          $("#chequeReceived").attr("disabled", true);
      }
      
      $.ajax({
          type: "POST",
          url:"<?php echo site_url('cashier/CashBookController/changeStatusRecChequeNeft');?>",
          data:{"id" : id,"amt" : amt,"mode":mode,"allocationId":allocationId},
          success: function (data) {
            $('#updateLost').html(data);
          }  
      });
  }

  function updateParkingAllow(category,id,allocationId){
      var amount=document.getElementById('prk').value;
      $.ajax({
          type: "POST",
          url:"<?php echo site_url('cashier/CashBookController/changeStatusAllow');?>",
          data:{"id" : id,"amount":amount,"allocationId" : allocationId,"category" : category},
          success: function (data) {
            $('#updateNotesList').html(data);
            location.reload(true); 
          }  
      });
  }

  function updateCngAllow(category,id,allocationId){
      var amount=document.getElementById('cngValAmt').value;
      $.ajax({
          type: "POST",
          url:"<?php echo site_url('cashier/CashBookController/changeStatusAllow');?>",
          data:{"id" : id,"amount":amount,"allocationId" : allocationId,"category" : category},
          success: function (data) {
            $('#updateNotesList').html(data);
            location.reload(true); 
          }  
      });
  }

  function updateChallanAllow(category,id,allocationId){
      var amount=document.getElementById('clnValAmt').value;
      $.ajax({
          type: "POST",
          url:"<?php echo site_url('cashier/CashBookController/changeStatusAllow');?>",
          data:{"id" : id,"amount":amount,"allocationId" : allocationId,"category" : category},
          success: function (data) {
            $('#updateNotesList').html(data);
            location.reload(true); 
          }  
      });
  }

  function updateParkingDisAllow(category,id,allocationId){
     var amount=document.getElementById('prk').value;
     $.ajax({
          type: "POST",
          url:"<?php echo site_url('cashier/CashBookController/changeStatusDisAllow');?>",
          data:{"id" : id,"amount":amount,"allocationId" : allocationId,"category" : category},
          success: function (data) {
            $('#updateNotesList').html(data);
            location.reload(true); 
          }  
      });
  }

  function updateCngDisAllow(category,id,allocationId){
     var amount=document.getElementById('cngValAmt').value;
     $.ajax({
          type: "POST",
          url:"<?php echo site_url('cashier/CashBookController/changeStatusDisAllow');?>",
          data:{"id" : id,"amount":amount,"allocationId" : allocationId,"category" : category},
          success: function (data) {
            $('#updateNotesList').html(data);
            location.reload(true); 
          }  
      });
  }

  function updateChallanDisAllow(category,id,allocationId){
     var amount=document.getElementById('clnValAmt').value;
     $.ajax({
          type: "POST",
          url:"<?php echo site_url('cashier/CashBookController/changeStatusDisAllow');?>",
          data:{"id" : id,"amount":amount,"allocationId" : allocationId,"category" : category},
          success: function (data) {
            $('#updateNotesList').html(data);
            location.reload(true); 
          }  
      });
  }

  function totalCollect(id,allocationId){
    var phyCash=document.getElementById('phyCash').innerText;
    var expenses=document.getElementById('expenses').innerText;
    var cashTotal=document.getElementById('cashTotal').innerText;

    $.ajax({
          type: "POST",
          url:"<?php echo site_url('cashier/CashBookController/updateCashValues');?>",
          data:{"id" : id,"allocationId" : allocationId,"phyCash" : phyCash,"expenses":expenses,"cashTotal" :cashTotal},
          success: function (data) {
              window.history.go(-1);
          }  
      });
  }
 </script>


<script type="text/javascript">
  $(document).on('click','#modalLinkBtn',function(e){
        $('#cashierDataMocal').html('');
      // $("#modalLinkBtn").attr("disabled", true);
      if (confirm('Do you want to submit this Allocation. ?')) {
         $("#modalLinkBtn").attr("disabled", true);
         e.preventDefault();
         var el = $(this);
         el.prop('disabled', true);
         setTimeout(function(){el.prop('disabled', false); }, 1000);

     

        var fst_tbl = document.getElementById("recTblIds");
        var fst_tbl_exp = document.getElementById("recTblId");
        // alert(fst_tbl.rows.length);

        for (var i = 2; i < fst_tbl.rows.length; i++) {
           if(fst_tbl.rows[i].cells[6].innerText == "check" || fst_tbl.rows[i].cells[6].innerText == "cancel"){
            // alert(fst_tbl.rows[i].cells[4].innerText);
           }else{
              // alert(fst_tbl.rows[i].cells[4].innerText);
              alert('Please submit all NEFT/Cheques first.');
              location.reload(true);
              die();
           }
        }
        // die();

        for (var i = 1; i < fst_tbl_exp.rows.length; i++) {
          if(typeof fst_tbl_exp.rows[i].cells[1].children[0].value=='undefined'){

          }else{
            if(((fst_tbl_exp.rows[i].cells[1].children[0].value == "0.00") || (fst_tbl_exp.rows[i].cells[1].children[0].value == "0")) && (fst_tbl_exp.rows[i].cells[2].innerText != "")){

            }else{
              // alert(fst_tbl_exp.rows[i].cells[1].children[0].value);
              alert('Please submit all Expenses first.');
              location.reload(true);
              die();
            }
          }
        }

        // die();
        
        var a2000 = document.getElementById('add2000').value;
        // var a1000 = document.getElementById('add1000').value;
        var a500 = document.getElementById('add500').value;
        var a200 = document.getElementById('add200').value;
        var a100 = document.getElementById('add100').value;
        var a50 = document.getElementById('add50').value;
        var a20 = document.getElementById('add20').value;
        var a10 = document.getElementById('add10').value;
        var coin = document.getElementById('coin').value;

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

        var cashTotalCount=a2000+a500+a200+a100+a50+a20+a10+coin;



        var marketCash = document.getElementById('toBeTaken').innerHTML;
        var calTotal =document.getElementById('totalActual').innerHTML;
        var shortAmt=document.getElementById('shCash').innerHTML;
        shortAmt=parseInt(shortAmt);
        marketCash=parseInt(marketCash);
        calTotal=parseInt(calTotal);

        cashTotalCount=parseInt(cashTotalCount);


        // if(cashTotalCount==0 && shortAmt !=0){
        //   alert('Please submit notes details first.');
        //   location.reload(true);
        //   die();
        // }
       
        var cashTaken=document.getElementById('cashTaken').innerHTML;
        var phyCash=document.getElementById('phyCash').innerText;
        var expenses=document.getElementById('expenses').innerText;
        var cashTotal=document.getElementById('cashTotal').innerText;
        var id=$(this).attr('data-id');
        var aId=$(this).attr('data-aId');

        // alert(cashTotal+' '+phyCash+' '+cashTaken+' '+shortAmt);die();



        if(shortAmt<0){
          // alert('suus');die();
          if (confirm('There is Suspence Income.Do you want to close this Allocation. ?')) {
             $("#modalLinkBtn").attr("disabled", true);
            $.ajax({
                url : "<?php echo site_url('cashier/CashBookController/suspenseIncomeTransaction');?>",
                method : "POST",
                data : {allocationId:id,noteId:aId,short: shortAmt,cashTaken:cashTaken,phyCash:phyCash,expenses:expenses,cashTotal:cashTotal,a2000:a2000,a500:a500,a200:a200,a100:a100,a50:a50,a20:a20,a10:a10,coin:coin},
                success: function(data){
                  if(!$.trim(data)){
                    alert('Allocation Closed');
                    $('#myModal').modal('hide');
                    var redirect_to="<?php echo site_url('AllocationByManagerController/openAllocations');?>";
                    window.location.href = redirect_to;
                  }else{
                      if(data.trim()=="Please allow/disallow parking expense first"){
                        alert(data);
                        location.reload(true);
                      }else if(data.trim()=="Please allow/disallow challan expense first"){
                        alert(data);
                        location.reload(true);
                      }else if(data.trim()=="Please allow/disallow CNG expense first"){
                        alert(data);
                        location.reload(true);
                      }else{
                        $('#cashierDataMocal').html(data);
                      }
                  }
                }
            });
          }else{
            die();
          }
        }else{
          $("#modalLinkBtn").attr("disabled", true);
          $.ajax({
              url : "<?php echo site_url('cashier/CashBookController/debitAmoutToEmp');?>",
              method : "POST",
              data : {allocationId:id,noteId:aId,short: shortAmt,cashTaken:cashTaken,phyCash:phyCash,expenses:expenses,cashTotal:cashTotal,a2000:a2000,a500:a500,a200:a200,a100:a100,a50:a50,a20:a20,a10:a10,coin:coin},
              success: function(data){
                // alert(data);
                if(!$.trim(data)){
                  // alert(data);
                  alert('Allocation Closed');
                  $('#myModal').modal('hide');
                  var redirect_to="<?php echo site_url('AllocationByManagerController/openAllocations');?>";
                  window.location.href = redirect_to;
                }else{
                  //  $('#cashierDataMocal').html(data);

                      if(data.trim()=="Please allow/disallow parking expense first"){
                        alert(data);
                        location.reload(true);
                      }else if(data.trim()=="Please allow/disallow challan expense first"){
                        alert(data);
                        location.reload(true);
                      }else if(data.trim()=="Please allow/disallow CNG expense first"){
                        alert(data);
                        location.reload(true);
                      }else{
                        $('#cashierDataMocal').html(data);
                      }
                }
              }
          });
        }
      }else{
        location.reload(true);
        // die();
      }
        
    });
</script>

<script type="text/javascript">
    $(document).on('change','#empCm',function(){
        var empId = $("#empNameList option[value='" + $('#empCm').val() + "']").attr('id');
        $('#empIdFor').val(empId);
    });
</script>

<script type="text/javascript">
    $(document).on('click','#btn_dbt',function(){
        $("#btn_dbt").attr("disabled", true);
        var inputs = document.getElementsByName('empAmt[]');
        var sum = 0;
        for(var i = 0; i<inputs.length; i++){
          sum += parseInt(inputs[i].value);
        }
     
        var totalAmt=$('#totalCalAmt').val();
        if(sum==totalAmt){
            $('#err').text("");
        }else{
            $('#err').text("Total debit should be equal to employee wise total");
            die();
        }

        var add2000=$('#add2000').val();
        // var add1000=$('#add1000').val();
        var add500=$('#add500').val();
        var add200=$('#add200').val();
        var add100=$('#add100').val();
        var add50=$('#add50').val();
        var add20=$('#add20').val();
        var add10=$('#add10').val();
        var coin=$('#coin').val();
        // alert(coin);die();

        var cashTotal=$('#dbt_cashTotal').val();
        var notesdetailId=$('#dbt_notesdetailId').val();
        var allocationId=$('#dbt_allocationId').val();
        var totalCalAmt=$('#totalCalAmt').val();

        var empId = $("input[name='empId[]']")
              .map(function(){return $(this).val();}).get();
        var empAmt = $("input[name='empAmt[]']")
              .map(function(){return $(this).val();}).get();

        var empAmtComment = $("input[name='empAmtComment[]']")
              .map(function(){return $(this).val();}).get();

        $.ajax({
            type: "POST",
            url:"<?php echo site_url('cashier/CashBookController/closeCashChequeBook');?>",
            data:{"empId" : empId,"empAmt" : empAmt,"empAmtComment":empAmtComment,"cashTotal" : cashTotal,"notesdetailId":notesdetailId,"allocationId" :allocationId,"totalCalAmt" :totalCalAmt,"add2000":add2000,"add500":add500,"add200":add200,"add100":add100,"add50":add50,"add20":add20,"add10":add10,"coin":coin},
            success: function (data) {
                alert(data);
                window.location.href="<?php echo base_url();?>index.php/AllocationByManagerController/openAllocations";
                // window.history.go(-1);
                
            }  
        });
    });
</script>

<script type = "text/javascript" >  
    window.history.pushState(null, "", window.location.href);
    window.onpopstate = function () {
        window.history.pushState(null, "", window.location.href);
    };
</script> 



<script type="text/javascript">
    jQuery("#insert-ins").on("click",function(){
        var selValue = [];
        $.each($("input[name='selValue']:checked"), function(){
                selValue.push($(this).val());
        });

        var notselValue = [];
        $.each($("input[name='selValueForNotReceived']:checked"), function(){
            notselValue.push($(this).val());
        });

        // alert(selValue+' not :'+notselValue);die();

        if((selValue.length>0) || (notselValue.length>0)){
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('cashier/CashBookController/changeStatusRecChequeNeftWithCheckBox');?>",
                data:{selValue:selValue, notselValue:notselValue},
                success: function (data) {
                    $("input[type=checkbox]").each(function(){
                        $(this).attr('checked', false);
                    });   
                    
                    location.reload(true);    
                    // window.location.href="<?php echo base_url();?>index.php/operator/OperatorController/pendingSr";
                }  
            });
        }else{
            alert('Please select Bills.');
        }
});

$.ajax({
          type: "POST",
          url:"<?php echo site_url('cashier/CashBookController/changeStatusRecChequeNeft');?>",
          data:{"id" : id,"amt" : amt,"mode":mode,"allocationId":allocationId},
          success: function (data) {
            $('#updateLost').html(data);
          }  
      });
    
</script>

<script type="text/javascript">
    var clicked = false;
    $(".checkall").on("click", function() {
      $(".checkhour").prop("checked", !clicked);
      clicked = !clicked;
      this.innerHTML = clicked ? 'Deselect' : 'Select';
    });
</script>

<script type="text/javascript">
    var clicked = false;
    $(".checkallForNotReceived").on("click", function() {
      $(".checkhourForNotReceived").prop("checked", !clicked);
      clicked = !clicked;
      this.innerHTML = clicked ? 'Deselect' : 'Select';
    });
</script>

<script>
    function checkForNotReceive(id){
        var checker = document.getElementById('basic_checkbox_neft_notReceived'+id);
        var sendbtn = document.getElementById('basic_checkbox_neft'+id);
        
        checker.onchange = function() {
            sendbtn.disabled = !!this.checked;
        };
    }

    function checkForReceive(id){
        var sendbtn = document.getElementById('basic_checkbox_neft_notReceived'+id);
        var checker = document.getElementById('basic_checkbox_neft'+id);
        
        checker.onchange = function() {
            sendbtn.disabled = !!this.checked;
        };
    }
</script>