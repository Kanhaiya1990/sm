<?php $this->load->view('/layouts/commanHeader'); ?>
        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
           <!--  <div class="block-header">
                <h2>
                     Allocation Transactions Details
                </h2>
            </div> -->
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                 Bill Received Details
                            </h2>
                            <p align="right">
                                <a href="<?php echo site_url('AllocationByManagerController/CloseCompleteAllocation/'.$allocationId);?>">
                                    <button class="btn bg-primary margin">Summary</button></a> 

                                <a href="<?php echo site_url('AllocationByManagerController/closedAllocationSrTrancationDetails/'.$allocationId);?>">
                                      <button class="btn bg-primary margin"> SR Details</button></a>  
                            </p>
                        </div>
                        <div class="body">
                          <div class="row">
                                <div class="col-md-12">
                                   <div class="col-md-4"> 
                                    <label id="allocation">Allocation : </label>
                                    <?php echo $allocations[0]['allocationCode']?>
                                    
                                </div>
                                    <div class="col-md-4">
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
                                   <div class="col-md-4">
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
                              
                                </div>
                                </div>
                            <div class="table-responsive">
                                <table id="crTbl" class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='25'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Bill No</th>
                                            <th>Retailer Name</th>
                                            <th>Bill Amount</th>
                                            
                                            <th>Pending Amount</th>
                                            <th>Received Bill</th>
                                            <th>Bill Type</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Bill No</th>
                                            <th>Retailer Name</th>
                                            <th>Bill Amount</th>
                                            
                                            <th>Pending Amount</th>
                                            <th>Received Bill</th>
                                            <th>Bill Type</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php 
                                        $no=0;
                                            if(!empty($signed)){
                                            foreach($signed as $data){
                                                 if($data['isFsrBill']==0){

                                                  $paidProcessAmountTill=array();
                                                 
                                                $no++;
                                                $diff=strtotime(date('Y-m-d'))-strtotime($data['date']);
                                                $paidAmountTill=$this->AllocationByManagerModel->getAllocationBillNetAmt('billpayments',$data['id'],$allocations[0]['id']);
                                                if($allocations[0]['allocationCloseAt']==""){
                                                  $paidProcessAmountTill=$this->AllocationByManagerModel->getProcessBillNetAmt('billpayments',$data['id'],$allocations[0]['date']);

                                                }else{
                                                  $paidProcessAmountTill=$this->AllocationByManagerModel->getProcessBillNetAmt('billpayments',$data['id'],$allocations[0]['allocationCloseAt']);

                                                }
                                              //  echo $allocations[0]['allocationCloseAt'];
                                              //   print_r($paidProcessAmountTill);
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



                                                 
                                        ?>
                                        <tr>
                                            <td><?php echo $no;?></td>
                                            <td><?php echo $data['billNo'];?></td>
                                            <td><?php echo $data['retailerName'];?></td>
                                            
                                            <td align="right"><?php echo round(($data['netAmount'])); ?></td>
                                            <td align="right"><?php echo round(($data['netAmount']-($remAmount)));?></td>
                                            <td>
                                                <?php if((($data['netAmount']-($remAmount)) > 0) && ($data['lostBill'] == 0)){ ?>
                                                <i class="material-icons">check</i>
                                             <?php }else if($data['lostBill'] == 1){ ?>
                                               <i class="material-icons">cancel</i> 
                                            <?php } ?>
                                                
                                            </td>
                                            <td>
                                              <?php 
                                              if($data['lostBill'] == 1){
                                                echo "Lost Bill";
                                              }else if($data['resendBill'] == 1){
                                               echo "Resend";
                                              } else if((($data['netAmount']-($remAmount)) > 0) && ($data['resendBill'] == 0)){ 
                                                echo "Signed";
                                              }else{
                                                echo "Cleared";
                                              }
                                             
                                              ?>
                                            </td>
                                        </tr>
                                        <?php 
                                            }
                                          }
                                        } 
                                        ?>
                                    
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

              <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">

                      <?php 

                          $route=$this->AllocationByManagerModel->load('route',$allocations[0]['routId']);
                          $emp1=$this->AllocationByManagerModel->load('employee',$allocations[0]['fieldStaffCode1']);
                      ?>
                        <div class="header">
                           <h2>
                             Cashier Transaction Details
                          </h2><br>
                          <p>
                          <tr>
                              <td><span style="color:blue">Allocation No.:</span> <?php echo $allocations[0]['allocationCode']; ?></td>
                              <td><span style="color:blue">Company :</span> <?php echo $allocations[0]['company']; ?></td>
                              <td> <span style="color:blue">Fieldstaff Name :</span> <?php echo $emp1[0]['name']; ?></td>
                              <td><span style="color:blue">Route Name :</span> <?php echo $route[0]['name']; ?></td>
                          </tr>
                          </p>
                        

                        </div>
                        <div class="body">


                            <div class="table-responsive">

                                <div class="col-md-12">
                                  <div class="col-md-7">
                                <table id="recTblIds" style="font-size: 11px" class="table table-bordered table-striped table-hover display nowrap" data-page-length='100'>
                                    <thead>
                                    <tr>
                                        <th colspan="4"><center>Cheque/NEFT Reconciliation</center></th>
                                    </tr>
                                    <tr>
                                        <th>Retailer Name</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody id="updateLost">
                              <?php
                              $noOfCheques=0;
                                  foreach($bills as $itm){
                                    $billD=$this->AllocationByManagerModel->loadChkBillPaymentDetails('billpayments',$itm['id'],$allocations[0]['id'],"NEFT");
                                    if(!empty($billD)){
                                       if($billD[0]['paidAmount']>0){
                                        $noOfCheques++;
                              ?>
                                      <tr>  
                                           <td><?php echo $itm['retailerName']?></td>
                                          <td><?php echo $billD[0]['paidAmount']?></td>
                                          <td><?php echo 'NEFT';?></td>
                                          <td>
                                            
                                        <?php if($billD[0]['isLostStatus'] == 1){ ?>
                                           <i class="material-icons">cancel</i> 
                                        <?php } else if($billD[0]['isLostStatus'] == 2){ ?>
                                            <i class="material-icons">check</i> 
                                        <?php } ?>
                                        </td>
                                      </tr>
                              <?php       }
                                        }
                                      } 

                                  foreach($bills as $itm){
                                    $billD=$this->AllocationByManagerModel->loadChkBillPaymentDetails('billpayments',$itm['id'],$allocations[0]['id'],"Cheque");
                                      if(!empty($billD)){
                                        if($billD[0]['paidAmount']>0){
                                        $noOfCheques++
                              ?>
                                          <tr>
                                          <td><?php echo $itm['retailerName']?></td>
                                           <td><?php echo $billD[0]['paidAmount']; ?></td>
                                          <td><?php echo 'Cheque'; ?></td>
                                          <td>
                                            
                                        <?php  if($billD[0]['isLostStatus'] == 1){ ?>
                                            <i class="material-icons">cancel</i> 
                                        <?php } else if($billD[0]['isLostStatus'] == 2){ ?>
                                            <i class="material-icons">check</i> 
                                        <?php } ?>
                                        </td>
                                      </tr>
                              <?php       }
                                        }
                                      }
                              ?>
                                    </tbody>
                                </table>
                            </div>
                                
                            <div class="col-md-5">
                                <table id="recTblId" style="font-size: 11px" class="table table-bordered table-striped table-hover js-basic-example DataTable display nowrap" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th colspan="2"><center>Other Expenses</center></th>
                                            <th colspan="2"><center>Status</center></th>
                                        </tr>
                                    </thead>
                                    <tbody id="updateNotesList">
                                      <?php 
                                      if(!empty($notes)){
                                       if(($notes[0]['statusParking'] == 1)){
                                    ?>
                                          <tr>
                                          <td><?php echo $firstTitle; ?></td>
                                            <td>
                                              <span><?php if(!empty($notes)){echo $notes[0]['parking'];} ?></span>
                                              
                                              </td>
                                            <td>
                                                <i class="material-icons">check</i> 
                                            </td>
                                        </tr>

                                    <?php
                                          }

                                          if(($notes[0]['statusParking'] == 2)){
                                    ?>
                                          <tr>
                                          <td><?php echo $firstTitle; ?></td>
                                            <td>
                                              <span><?php if(!empty($notes)){echo $notes[0]['parking'];} ?></span>
                                              
                                              </td>
                                            <td>
                                                <i class="material-icons">cancel</i> 
                                            </td>
                                        </tr>

                                    <?php
                                          }
                                        } 

                                        if(!empty($notes)){
                                            if(($notes[0]['statusCng'] == 1)){
                                    ?>
                                        <tr>
                                            <td><?php echo $secondTitle; ?></td>
                                             <td>
                                              <span><?php if(!empty($notes)){echo $notes[0]['cng'];} ?></span>
                                              </td>
                                             <td>
                                                <i class="material-icons">check</i>
                                            </td>
                                        </tr>
                                    <?php        
                                          }

                                           if(($notes[0]['statusCng'] == 2)){
                                    ?>
                                        <tr>
                                            <td><?php echo $secondTitle; ?></td>
                                             <td>
                                              <span><?php if(!empty($notes)){echo $notes[0]['cng'];} ?></span>
                                              </td>
                                             <td>
                                                <i class="material-icons">cancel</i>
                                            </td>
                                        </tr>
                                    <?php        
                                          }

                                        }  


                                      if(!empty($notes)){
                                        if(($notes[0]['statusChallan'] ==1)){
                                  ?>
                                           <td><?php echo $thirdTitle; ?></td>
                                            <td>
                                             <span><?php if(!empty($notes)){echo $notes[0]['challan'];} ?></span>
                                              </td>
                                             <td>
                                                 <i class="material-icons">check</i> 
                                            </td>
                                  <?php
                                        }

                                        if(($notes[0]['statusChallan'] == 2)){
                                  ?>
                                          <td><?php echo $thirdTitle; ?></td>
                                            <td>
                                             <span><?php if(!empty($notes)){echo $notes[0]['challan'];} ?></span>
                                              </td>
                                             <td>
                                                 <i class="material-icons">cancel</i> 
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

                          <div class="col-md-6">
                                <table style="font-size: 11px" class="table table-bordered table-striped table-hover js-basic-example DataTable display nowrap" id="example" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th colspan="2"><center>Cash and Cheque Reconciliation</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php 
                                             $totalDebit=0;
                                             $totalCredit=0;
                                            if(!empty($empDebit)){
                                                // $totalDebit=0;

                                                foreach($empDebit as $deb){
                                                  if($deb['transactionType']=="dr"){
                                                    $totalDebit=$totalDebit+$deb['amount'];
                                                  }

                                                  if($deb['transactionType']=="cr"){
                                                    $totalCredit=$totalCredit+$deb['amount'];
                                                  }
                                                  
                                                }
                                           } 
                                      ?>

                                          <tr>
                                            <td>Number of Cheques/NEFT</td>
                                            <td align="right" id="chequeTotal"><?php echo $noOfCheques; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Cash As per Accounts</td>
                                            <td align="right">
                                            <?php
                                              if(!empty($total)){ 
                                                if(!empty($notes)){
                                                  echo number_format(($total+$expenses+($totalDebit-$totalCredit)),2);
                                                }else{
                                                  echo number_format(($total+$expenses),2);
                                                }
                                                
                                              }
                                            ?>
                                               
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Market Expenses</td>
                                            <td align="right" id="expenses">
                                              <?php 
                                              if(($expenses))
                                              {
                                                echo $expenses;
                                              } 
                                              ?></td>
                                        </tr>
                                         <tr>
                                            <td>Cash To be taken</td>
                                            <td align="right" id="toBeTaken">
                                               <?php
                                              if(!empty($total)){ 
                                                if(!empty($notes)){
                                                  echo number_format(($total+($totalDebit-$totalCredit)),2);
                                                }else{
                                                  echo number_format(($total),2);
                                                }
                                                
                                              }
                                            ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"></td>
                                        </tr>
                                        
                                        
                                       <!--  <tr>
                                            <td>Cash Already Taken</td>
                                            <td align="right"><span id="cashTaken"><?php if(!empty($notes)){echo $notes[0]['collectedAmt'];} ?></span></td>
                                            
                                        </tr>
                                        <tr>
                                            <td>Balance Amount</td>
                                            <td align="right"><span id="balAmt">
                                              <?php if(!empty($notes)){
                                                if(!empty($empDebit)){
                                                  echo ($notes[0]['balanceAmt']-$empDebit[0]['amount']);
                                                }else{
                                                  echo $notes[0]['balanceAmt'];
                                                }
                                                
                                              } ?>
                                            </span></td>
                                            
                                        </tr> 

                                        <tr>
                                            <td colspan="2"></td>
                                        </tr>-->

                                        <tr>
                                            <td>Physical Cash</td>
                                            <td align="right"><span id="phyCash"><?php if(!empty($notes)){echo $notes[0]['collectedAmt'];} ?></span></td>
                                            
                                        </tr>
                                        
                          <?php 
                            if(!empty($empDebit)){
                                      $totalDebit=0;
                                      $totalCredit=0;
                                      if(!empty($empDebit)){
                                            // $totalDebit=0;

                                            foreach($empDebit as $deb){
                                              if($deb['transactionType']=="dr"){
                                                $totalDebit=$totalDebit+$deb['amount'];
                                              }

                                              if($deb['transactionType']=="cr"){
                                                $totalCredit=$totalCredit+$deb['amount'];
                                              }
                                              
                                            }
                                       } 
                          ?>
                                        <tr>
                                            <td id="shortCashStatus">
                                              <?php if($totalDebit>0){
                                                echo "Short";
                                              }else if($totalCredit > 0){
                                                echo "Excess";
                                              } 

                                              ?>
                                              </td>
                                            <td align="right" id="shortCash">
                                              <?php if($totalDebit>0){
                                                echo $totalDebit;
                                              }else if($totalCredit > 0){
                                                echo $totalCredit;
                                              } 
                                              ?>
                                            </td>
                                        </tr>

                          <?php } ?>

                                    </tbody>
                                </table>
                            </div>
                                
                            <div class="col-md-6">
                                <table style="font-size: 11px" class="table table-bordered table-striped table-hover js-basic-example DataTable" data-page-length='100'>
                                    <thead>
                                    <tr>
                                        <th><center>Denominations</center></th>
                                        <th><center>As per Supplier</center></th>
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
                                       
                                    </tr>
                                     <tr>
                                        <td align="right">1000</td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['note1000'];} ?></td>
                                          <td align="right">
                                        <?php if(!empty($notes)){echo ($notes[0]['note1000'] *1000);} ?>
                                         </td>
                                        
                                    </tr>
                                     <tr>
                                        <td align="right">500</td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['note500'];} ?></td>
                                          <td align="right">
                                        <?php if(!empty($notes)){echo ($notes[0]['note500'] *500);} ?>
                                         </td>
                                        
                                    </tr>
                                    <tr>
                                        <td align="right">200</td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['note200'];} ?></td>
                                          <td align="right">
                                        <?php if(!empty($notes)){echo ($notes[0]['note200'] *200);} ?>
                                         </td>
                                       
                                    </tr>
                                     <tr>
                                        <td align="right">100</td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['note100'];} ?></td>
                                          <td align="right">
                                        <?php if(!empty($notes)){echo ($notes[0]['note100'] *100);} ?>
                                         </td>
                                        
                                    </tr>
                                     <tr>
                                        <td align="right">50</td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['note50'];} ?></td>
                                         <td align="right">
                                        <?php if(!empty($notes)){echo ($notes[0]['note50'] *50);} ?>
                                         </td>
                                        
                                    </tr>
                                     <tr>
                                        <td align="right">20</td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['note20'];} ?></td>
                                         <td align="right">
                                        <?php if(!empty($notes)){echo ($notes[0]['note20'] *20);} ?>
                                         </td>
                                       
                                    </tr>
                                    <tr>
                                        <td align="right">10</td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['note10'];} ?></td>
                                          <td align="right">
                                        <?php if(!empty($notes)){echo ($notes[0]['note10'] *10);} ?>
                                         </td>
                                        
                                    </tr>
                                    <tr>
                                        <td align="right">Coins</td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['coins'];} ?></td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['coins'];} ?></td>
                                       
                                    </tr>
                                     <tr>
                                        <td colspan="2" align="center">Total Currency</td>
                                        <td align="right"><?php if(!empty($total)){ echo number_format($total,2);}?></td>
                                       
                                    </tr>

                                    <div id="emp"></div>
                                   
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>

                       
                        <div class="col-md-12">
                          <table id="crTbl" class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='25'>
                                     <thead>
                                        <tr>
                                            <th colspan="2"><center>Debited/Credited Amount Detail</center></th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th>Employee Name</th>
                                            <th>Transaction Type</th>
                                            <th>Comment</th>
                                            <th> Amount</th>
                                        </tr>
                                    </thead>
                                   
                                    <tbody>

                        <?php 
                          if(!empty($empDebit)){ 
                            foreach($empDebit as $deb){
                              $emp=$this->AllocationByManagerModel->load('employee',$deb['empId']);
                            //   print_r($emp);
                        ?>
                                      <tr>
                                        <td><?php echo $emp[0]['name']; ?></td>
                                        <td><?php echo $deb['transactionType']; ?></td>
                                        <td><?php echo $deb['description']; ?></td>
                                        <td><?php echo $deb['amount']; ?></td>
                                      </tr>
                        <?php } ?>
                                    </tbody>
                                  </table>
                        </div>
                      <?php } ?>
                            </div><!-- table 1 end-->

                           
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
<?php $this->load->view('/layouts/footerDataTable'); ?>
