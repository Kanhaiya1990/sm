<?php $this->load->view('/layouts/commanHeader');
$projectSessionName="";
if (isset($this->session->userdata['codeKeyData'])) {
    $projectSessionName= ($this->session->userdata['codeKeyData']['codeKeyValue']);
  }

?>

<script   src="<?php echo base_url('assets/js/kp_js/jquery-1.12.1.js'); ?>"   integrity="sha256-VuhDpmsr9xiKwvTIHfYWCIQ84US9WqZsLfR4P7qF6O8="   crossorigin="anonymous"></script>
<script src="<?php echo base_url('assets/js/kp_js/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/pages/ui/tooltips-popovers.js');?>"></script> 
<style type="text/css">
    @media screen and (min-width: 1200px) {
        .modal-dialog {
          width: 1200px; /* New width for default modal */
      }
      .modal-sm {
          width: 1200px; /* New width for small modal */
      }
  }

  @media screen and (min-width: 1200px) {
    .modal-lg {
      width: 1200px; /* New width for large modal */
  }
}

.logo_prov {
    border-radius: 30px;
    border: 1px solid black;
    background: red;
    color: black;
    padding: 6px;
    width: 50px;
    height: 50px;
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

<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/>
<section class="col-md-12 box">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                    
                      <h2>
                       <br>
                       <p align="center">Day Book Date: <?php echo date('d-M-Y');?></p>

                       <p align="right">
                    <?php if($this->session->userdata['workRestrict']['status']=="yes"){?>
                        <button class="btn btn-primary btn-xs m-t-15 waves-effect" onclick="planUpgradeMsg()">
                                <span class="icon-name"> <i class="material-icons">playlist_add</i><span>Transfer to Main Cash Book </span></span>
                        </button> 

                        <button  class="btn btn-primary btn-xs m-t-15 waves-effect" onclick="planUpgradeMsg()">
                            <span class="icon-name"> <i class="material-icons">add_circle_outline</i><span>Note Exchange</span> </span>
                        </button>
                        <button class="btn btn-primary btn-xs m-t-15 waves-effect" onclick="planUpgradeMsg()">
                            <span class="icon-name"> <i class="material-icons">add_circle_outline</i><span>Add Income</span> </span>
                        </button>
                        <button class="btn btn-primary btn-xs m-t-15 waves-effect" onclick="planUpgradeMsg()">
                            <span class="icon-name"> <i class="material-icons">add_circle_outline</i><span>Add Expense </span></span>
                        </button>
                        <button class="btn btn-primary btn-xs m-t-15 waves-effect" onclick="planUpgradeMsg()">
                            <span class="icon-name"> <i class="material-icons">playlist_add</i><span>Add Bank Deposit </span></span>
                        </button>
                        <button class="btn btn-xs btn-primary m-t-15 waves-effect" onclick="planUpgradeMsg()">
                            <span class="icon-name"> <i class="material-icons">https</i><span>Close Day Book </span></span>
                        </button>
                    <?php }else{ ?>   
                            <button data-toggle="modal" data-target="#myMainCashBook" class="modalLink btn btn-primary btn-xs m-t-15 waves-effect">
                                    <span class="icon-name"> <i class="material-icons">playlist_add</i><span>Transfer to Main Cash Book </span></span>
                            </button> 

                            <button data-toggle="modal" data-target="#exchangeModel" class="modalLink btn btn-primary btn-xs m-t-15 waves-effect">
                                <span class="icon-name"> <i class="material-icons">add_circle_outline</i><span>Note Exchange</span> </span>
                            </button>
                            <button data-toggle="modal" data-target="#myModal" class="modalLink btn btn-primary btn-xs m-t-15 waves-effect">
                                <span class="icon-name"> <i class="material-icons">add_circle_outline</i><span>Add Income</span> </span>
                            </button>
                            <button data-toggle="modal" data-target="#myModal1" class="modalLink btn btn-primary btn-xs m-t-15 waves-effect">
                                <span class="icon-name"> <i class="material-icons">add_circle_outline</i><span>Add Expense </span></span>
                            </button>
                            <button data-toggle="modal" data-target="#myModal2" class="modalLink btn btn-primary btn-xs m-t-15 waves-effect">
                                <span class="icon-name"> <i class="material-icons">playlist_add</i><span>Add Bank Deposit </span></span>
                            </button>

                            <?php if($lastEntryDayBookCount == 0){ ?>
                                <button onclick="rejecetCloseDayBookSubmit();" class="btn btn-xs btn-primary m-t-15 waves-effect">
                                    <span class="icon-name"> <i class="material-icons">https</i><span>Close Day Book </span></span>
                                </button>
                            <?php } else { 
                                if($ownerExpenseCount > 0 || $ownerExpenseCountTo>0){ ?>
                                    <button onclick="rejecetCloseDayBook();"  class="btn btn-xs btn-primary m-t-15 waves-effect">
                                        <span class="icon-name"> <i class="material-icons">https</i><span>Close Day Book </span></span>
                                    </button>
                                <?php }else{ ?>
                                    <button id="closeDayBookBtn"  data-toggle="modal" data-target="#closeDayModal" class="btn btn-xs btn-primary m-t-15 waves-effect">
                                        <span class="icon-name"> <i class="material-icons">https</i><span>Close Day Book </span></span>
                                    </button>
                        <?php 
                                        } 
                                } 
                            } 
                        ?>
                       
                         


                    </p> <br>
                    <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-basic-example" data-page-length='100'>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th><span class="pull-right">Opening Balance</span></th>
                                <th><span class="pull-right">Income</span></th>
                                <th><span class="pull-right">Expense</span></th>
                                <th><span class="pull-right">Contra Entry</span></th>
                                <th><span class="pull-right">Bank Deposit</span></th>
                                <th><span class="pull-right">Closing Balance</span></th>

                            </tr>
                        </thead>
                        <tbody>
                           <tr>
                            <td><?php echo date('d-M-Y');?></td>
                            <td align="right"><?php echo number_format($closingBal); ?></td>
                            <td align="right"><?php echo number_format(($totalInflow)); ?></td>
                            <td align="right"><?php echo number_format(($totalOutflow)); ?></td>
                            <td align="right"><?php echo number_format(($diffContraEntry)); ?></td>
                            <td align="right"><?php echo number_format($totalBankDeposit); ?></td>
                            <td align="right"><?php echo number_format((($totalInflow+$closingBal)-($totalOutflow+$totalBankDeposit))+($diffContraEntry)); ?></td>

                        </tbody>
                    </table>

                </h2>
            </div>
            <div class="body">

                <div class="table-responsive">
                    <?php 
                    if(!empty($this->session->flashdata('item'))){
                        $msg=$this->session->flashdata('item');
                        echo '<p style="color:red">'.$msg['message'].'</p>';
                    }
                    ?> 
                    <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Allocation No.</th>
                                <th>Nature</th>
                                <th>Employee</th>
                                <th>Reference</th>
                                <th><span class="pull-right">Inflow</span></th>
                                <th><span class="pull-right">Outflow</span></th>
                                <th><span class="pull-right">Balance</span></th>
                            </tr>

                        </thead>
                        <tfoot>
                            <tr>
                                <th>Date</th>
                                <th>Allocation No.</th>
                                <th>Nature</th>
                                <th>Employee</th>
                                <th>Reference</th>
                                <th><span class="pull-right">Inflow</span></th>
                                <th><span class="pull-right">Outflow</span></th>
                                <th><span class="pull-right">Balance</span></th>
                            </tr>
                        </tfoot>
                        <tbody>

                            <tr>
                                <td><?php echo date('d-M-Y'); ?></td>
                                <td></td>
                                <td></td>
                                <td><?php echo 'Opening Balance'; ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td align="right"><?php echo number_format($closingBal); ?></td>
                            </tr>

                            <?php 
                            $no=0;

                            $totalMarketExpense=0;
                            if(!empty($inflowEmp)){
                                foreach($inflowEmp as $dt){
                                    $allocationCode=0;
                                    $allocationParkingExpense=0;
                                    $allocationChallanExpense=0;
                                    $allocationCngExpense=0;
                                    $totalCollection=0;
                                    $expenseOwnerApproval=0;
                                    $bill_no="";
                                    $routeName="";
                                    $ename="";
                                    if($dt['allocationId']>0 && $dt['nature']=="Market Collection"){
                                       $alData=$this->CashBookModel->load('allocations',$dt['allocationId']);
                                       $allocationCode=$alData[0]['allocationCode'];
                                       
                                       if(!empty($alData[0]['fieldStaffCode1'])){
                                            $empData=$this->CashBookModel->load('employee',$alData[0]['fieldStaffCode1']);
                                            $ename=$empData[0]['name'];
                                       }

                                       if(!empty($alData[0]['fieldStaffCode2'])){
                                            $empData=$this->CashBookModel->load('employee',$alData[0]['fieldStaffCode2']);
                                            $ename=$ename.' , '.$empData[0]['name'];
                                       }
                                       
                                       $routeId=$alData[0]['routId'];
                                       $routeData=$this->CashBookModel->load('route',$routeId);
                                       if(!empty($routeData)){
                                            $routeName="Allocation No : ".$allocationCode." , Route : ".$routeData[0]['name'];
                                       }
                                       $expense=$this->CashBookModel->calculateExpense($dt['allocationId']);
                                       $expenseOwnerApproval=$expense[0]['expenseOwnerApproval'];

                                       $allocationParkingExpense=$expense[0]['parking'];
                                       $allocationChallanExpense=$expense[0]['challan'];
                                       $allocationCngExpense=$expense[0]['cng'];
                                       $loadAmount=$this->CashBookModel->loadBalAmt('notesdetails',$dt['allocationId']);
                                      
                                       $totalCollection=$loadAmount[0]['parking']+$loadAmount[0]['challan']+$loadAmount[0]['cng']+$loadAmount[0]['collectedAmt']+$loadAmount[0]['balanceAmt'];
                                   }else{
                                     $ename=$dt['name'];
                                   }

                                   $totalMarketExpense=$totalMarketExpense+$allocationParkingExpense+$allocationChallanExpense+$allocationCngExpense;

                                   $no++;  

                                   if($totalCollection>0){

                                      ?>
                                      <tr>
                                        <?php if($dt['expenseOwnerApproval']==2 || $dt['bankDepositApproval']==2){ ?>
                                            <td><?php echo date("d-M-Y h:i a", strtotime($dt['date'])).'<span class="logo_prov">Rejected</span>';?></td>
                                        <?php }else{ ?>
                                            <td><?php echo date("d-M-Y h:i a", strtotime($dt['date']));?></td>
                                        <?php  } ?>

                                        <td><?php if($allocationCode >0){echo $allocationCode; } ?></td>

                                        <td><?php echo $dt['nature'];?></td>
                                        <td><?php echo $ename;?></td>
                                        <td>
                                            <?php if(!empty($routeName)){
                                                echo $routeName;
                                            }else{ 
                                                echo $dt['narration'];
                                            } ?>
                                        </td>

                                       
                                        <td align="right" style="color:blue;"><?php echo number_format($totalCollection);?></td>

                                        <td></td>

                                        <td align="right"><?php echo number_format($dt['openCloseBalance']);?></td>
                                    </tr>

                                    <?php

                                }else{
                                    if($dt['allocationId']>0){
                                      $alData=$this->CashBookModel->load('allocations',$dt['allocationId']);
                                      $allocationCode=$alData[0]['allocationCode'];

                                      if(!empty($alData[0]['fieldStaffCode1'])){
                                            $empData=$this->CashBookModel->load('employee',$alData[0]['fieldStaffCode1']);
                                            $ename=$empData[0]['name'];
                                       }

                                       if(!empty($alData[0]['fieldStaffCode2'])){
                                            $empData=$this->CashBookModel->load('employee',$alData[0]['fieldStaffCode2']);
                                            $ename=$ename.' , '.$empData[0]['name'];
                                       }
                                    }else{
                                     $ename=$dt['name'];
                                    } 
                                    
                                 ?>     

                                 <?php if($dt['expenseOwnerApproval']==2 || $dt['bankDepositApproval']==2){ ?>
                                    <tr style="background-color:pink;">
                                        <td><?php echo date("d-M-Y h:i a", strtotime($dt['date'])).'<span class="logo_prov">Rejected</span>';?></td>
                                    <?php }else{ ?>
                                        <tr>
                                            <td><?php echo date("d-M-Y h:i a", strtotime($dt['date']));?></td>
                                        <?php  } ?>
                                        <td><?php if($allocationCode >0){echo $allocationCode; } ?></td>

                                        <td><?php echo $dt['nature'];?></td>
                                        <td><?php echo $ename;?></td>
                                        <td><?php echo $dt['narration'];?></td>
                                    <?php if($dt['nature']==="Bank Deposit"){?>
                                           <td></td>
                                           <td align="right" style="color:#4DD608;"><?php echo number_format($dt['amount']);?></td>
                                    <?php } if($dt['inoutStatus']==="Inflow"){ ?>
                                        <?php if($dt['isContraEntry']==1){?>
                                            <td align="right" style="color:#02806e;"><?php echo number_format($dt['amount']);?></td>
                                        <?php }else{?>
                                            <td align="right" style="color:blue;"><?php echo number_format($dt['amount']);?></td>
                                        <?php } ?>

                                        <!-- <td align="right" style="color:blue;"><?php echo number_format($dt['amount']);?></td> -->
                                        <td></td>
                                    <?php } if($dt['nature'] !="Bank Deposit" && $dt['inoutStatus']==="Outflow"){ ?>

                                        <td></td>
                                        <?php if($dt['isContraEntry']==1){?>
                                            <td align="right" style="color: #02806e ;"><?php echo number_format($dt['amount']);?></td>
                                        <?php }else{?>
                                            <td align="right" style="color:red;"><?php echo number_format($dt['amount']);?></td>
                                        <?php } ?>
                                        
                                    <?php } ?>

                                    <td align="right"><?php echo number_format($dt['openCloseBalance']);?></td>
                                </tr>
                                <?php        

                            }
                            ?>


                            <?php 
                        
                }
            }
            ?>


        </tbody>

    </table>
</div><!-- Credit Table-->
<br>

</div>
</div>
</div>

</div>
<!-- #END# Basic Examples -->  

</div>
</section>

<div class="container">
    <div class="modal fade" id="myMainCashBook" role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title text-center">Transfer to Main Cash Book</h3>
                </div>
                <div class="modal-body">
                    <form method="post" role="form" onsubmit="return checkMainCashBookAmount(this);" action="<?php echo site_url('owner/MainCashBookController/insertMainCashBookEntry');?>"> 
                        <div class="row clearfix">
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <p id="err-data" style="color:red"></p>
                                    <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-basic-example" data-page-length='100'>
                                        <thead>
                                            <tr style="background-color: #dfdddd;">
                                                <th>Denomination</th>
                                                <th><span class="pull-right">Available Notes</span></th>
                                                <th><span class="pull-right"> Transfer to Main Cash Book</span></th>
                                                <th><span class="pull-right">Calculated Value</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-right">2000</td>
                                                <td class="text-right"><?php 
                                                    $val2000=(($daybook_notes['note2000']+($income_notes['note2000']-$expense_notes['note2000']))-$bank_deposit_notes['note2000']);

                                                    echo number_format(($daybook_notes['note2000']+($income_notes['note2000']-$expense_notes['note2000']))-$bank_deposit_notes['note2000']); 
                                                ?>
                                                <td align="right"><input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val2000; ?>)" onblur="this.value = minmax(this.value, 0,<?php echo $val2000; ?>)" onkeyup="calMainCashBookMoney();" type="text" name="add2000" id="add2000mainCashBook" autocomplete="off" class="form-control"></td>
                                                <td class="text-right" id="t2000mainCashBook"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">500</td>

                                                <td class="text-right"><?php 
                                                    $val500=(($daybook_notes['note500']+($income_notes['note500']-$expense_notes['note500']))-$bank_deposit_notes['note500']);
                                                echo number_format(($daybook_notes['note500']+($income_notes['note500']-$expense_notes['note500']))-$bank_deposit_notes['note500']); 
                                                ?>

                                                <td align="right">
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val500; ?>)" onblur="this.value = minmax(this.value, 0,<?php echo $val500; ?>)" onkeyup="calMainCashBookMoney();" type="text" name="add500" id="add500mainCashBook" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="t500mainCashBook"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">200</td>
                                                <td class="text-right"><?php 
                                                $val200=(($daybook_notes['note200']+($income_notes['note200']-$expense_notes['note200']))-$bank_deposit_notes['note200']);

                                                echo number_format(($daybook_notes['note200']+($income_notes['note200']-$expense_notes['note200']))-$bank_deposit_notes['note200']); 
                                                ?>

                                                <td align="right">
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val200; ?>)" onblur="this.value = minmax(this.value, 0,<?php echo $val200; ?>)" onkeyup="calMainCashBookMoney();" type="text" name="add200" id="add200mainCashBook" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="t200mainCashBook"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">100</td>
                                                <td class="text-right"><?php
                                                $val100=(($daybook_notes['note100']+($income_notes['note100']-$expense_notes['note100']))-$bank_deposit_notes['note100']);
                                                echo number_format(($daybook_notes['note100']+($income_notes['note100']-$expense_notes['note100']))-$bank_deposit_notes['note100']); 
                                                ?>

                                                <td align="right">
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val100; ?>)" onblur="this.value = minmax(this.value, 0,<?php echo $val100; ?>)" onkeyup="calMainCashBookMoney();;" type="text" name="add100" id="add100mainCashBook" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="t100mainCashBook"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">50</td>
                                                <td class="text-right"><?php 
                                                $val50=(($daybook_notes['note50']+($income_notes['note50']-$expense_notes['note50']))-$bank_deposit_notes['note50']);

                                                echo number_format(($daybook_notes['note50']+($income_notes['note50']-$expense_notes['note50']))-$bank_deposit_notes['note50']); 
                                                ?>

                                                <td align="right">
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val50; ?>)" onblur="this.value = minmax(this.value, 0,<?php echo $val50; ?>)" onkeyup="calMainCashBookMoney();" type="text" name="add50" id="add50mainCashBook" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="t50mainCashBook"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">20</td>
                                                <td class="text-right"><?php 
                                                $val20=(($daybook_notes['note20']+($income_notes['note20'])-$expense_notes['note20'])-$bank_deposit_notes['note20']);
                                                echo number_format(($daybook_notes['note20']+($income_notes['note20'])-$expense_notes['note20'])-$bank_deposit_notes['note20']); 
                                                ?>

                                                <td align="right">
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val20; ?>)" onblur="this.value = minmax(this.value, 0,<?php echo $val20; ?>)" onkeyup="calMainCashBookMoney();" type="text" name="add20" id="add20mainCashBook" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="t20mainCashBook"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">10</td>
                                                <td class="text-right">
                                                <?php 
                                                $val10=(($daybook_notes['note10']+($income_notes['note10']-$expense_notes['note10']))-$bank_deposit_notes['note10']);
                                                echo number_format(($daybook_notes['note10']+($income_notes['note10']-$expense_notes['note10']))-$bank_deposit_notes['note10']); 
                                                ?>

                                                <td align="right">
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val10; ?>)" onblur="this.value = minmax(this.value, 0,<?php echo $val10; ?>)" onkeyup="calMainCashBookMoney();" type="text" name="add10" id="add10mainCashBook" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="t10mainCashBook"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">coins</td>
                                            <td class="text-right"><?php 
                                                $coins=(($daybook_notes['coins']+($income_notes['coins']-$expense_notes['coins']))-$bank_deposit_notes['coins']);
                                                echo number_format(($daybook_notes['coins']+($income_notes['coins']-$expense_notes['coins']))-$bank_deposit_notes['coins']);
                                                ?>

                                                <td align="right">
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $coins; ?>)" onblur="this.value = minmax(this.value, 0,<?php echo $coins; ?>)" onkeyup="calMainCashBookMoney();" type="text" name="coin" id="coinmainCashBook" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="tcoinsmainCashBook"></td>
                                            </tr>
                                            <tr style="background-color: #dfdddd;">
                                                <td class="text-right"><b>Total</b></td>
                                                <td class="text-right"><b>
                                                <?php 
                                                echo number_format(($daybook_notes['collectedAmt']+($income_notes['collectedAmt']-$expense_notes['collectedAmt']))-$bank_deposit_notes['collectedAmt']); 
                                                ?></b></td>
                                                <td class="text-xs-right">
                                                    <span id="calTotalmainCashBook"></span>
                                                    <input type="hidden" value="<?php echo ((($closingBal+$totalInflow)-($totalOutflow+$totalBankDeposit))+($diffContraEntry)); ?>" name="bankDep_amt" id="totalOpeningAmtmainCashBook" autocomplete="off" class="form-control">
                                                    <input type="hidden" name="collectedDepositTotal" id="collectedDepositTotalmainCashBook" autocomplete="off" class="form-control">
                                                </td>
                                                <td class="text-right"><span id="tcalTotalmainCashBook"></span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <center>                                               
                                        <button id="transferToMainCashBookButton" type="submit" class="btn btn-primary m-t-15 waves-effect">
                                            <i class="material-icons">save</i> 
                                            <span class="icon-name">
                                                Save
                                            </span>
                                        </button> 

                                        <button data-dismiss="modal" type="button" id="bnkClose" class="btn btn-primary m-t-15 waves-effect">
                                            <i class="material-icons">cancel</i> 
                                            <span class="icon-name">
                                                cancel
                                            </span>
                                        </button>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Add Income -->
<div class="container">
  <div class="modal fade" id="myModal" role="dialog" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
          <center> <h3 class="modal-title">Add Income</h3>  </center>
      </div>
      <div class="modal-body">
        <form method="post" role="form" onsubmit="return checkIncomeCorrectInputs(this);" action="<?php echo site_url('manager/CashBookController/insertIncomeInflow');?>"> 

            <div class="row clearfix">
                <div class="col-md-12">

                 <div class="col-md-4">
                    <p>
                       <b>Category </b>
                   </p>

                   <input type="text" autocomplete="off" tabindex="1" placeholder="select category" list="categoryList" id="category" name="category" class="form-control" required> 
                   <datalist id="categoryList">
                    <?php foreach($cat_income as $in){ ?>
                       <option id="<?php echo $in['id'] ?>" value="<?php echo $in['categoryName'] ?>" />
                       <?php } ?>
                   </datalist> 
               </div> 

               <div class="col-md-4">
                <p>
                 <b>Employee  </b>
             </p>

             <input type="text" autocomplete="off" tabindex="2" placeholder="select employee" list="empNameList" id="empName" name="empName" class="form-control" required> 
             <datalist id="empNameList">
                <?php foreach ($emp as $req_item): ?>
                    <option id="<?php echo $req_item['id'] ?>" value="<?php echo $req_item['name'] ?>" />
                    <?php endforeach ?> 
                </datalist> 
                <input type="hidden" id="empId" name="empId" class="form-control"> 
            </div> 

            <div class="col-md-4" id="inc-cmp-cmbo">
                <p>
                 <b>Company </b>
             </p>

             <input type="text" autocomplete="off" required tabindex="5" placeholder="select company" list="compNameList" id="compName" name="compName" class="form-control"> 
             <datalist id="compNameList">
                <?php foreach ($companyDetails as $req_item): ?>
                    <option id="<?php echo $req_item['id'] ?>" value="<?php echo $req_item['name'] ?>" />
                    <?php endforeach ?> 
                </datalist> 
            </div>

        </div>

        <div class="col-md-12">

            <div class="col-md-4">                                       
                <p>
                  <b> Amount </b>
              </p>
              <div class="form-line">
                <input type="text" onkeypress="return isNumber(event)" tabindex="3"  autocomplete="off" id="cashAmt" placeholder="amount" name="cashAmt" class="form-control" required>           
            </div>  
            <div style="color:red;" id="sr_qty"></div>          
        </div>
        <div class="col-md-8">                                       
            <p>
              <b> Narration </b>
          </p>
          <div class="form-line">
            <input type="text" autocomplete="off" placeholder="narration" tabindex="4" id="narration" name="narration" class="form-control" required>           
        </div>  
        <div style="color:red;" id="sr_qty"></div>          
    </div>
</div>
</div>
<br>
<div class="row clearfix">
<div class="col-md-12">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <table class="table table-bordered table-striped table-hover js-basic-example" style="font-size: 13px;">
            <thead>
                <tr>
                    <th class="text-xs-center" colspan="6"><center>Notes</center></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2000 &nbsp;&nbsp;&nbsp;&nbsp;X</td>
                    <td>
                        <input onkeypress="return isNumber(event)" tabindex="6" onkeyup="calIncomeMoney();" type="text" name="add2000" id="add2000i" autocomplete="off" class="form-control">

                    </td>
                    <td width="10%" class="text-xs-right">
                        <span id="ad2000i"></span>
                        <p id="2000im"></p>
                    </td>

                    <td>500 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                    <td>
                        <input onkeypress="return isNumber(event)" tabindex="7" onkeyup="calIncomeMoney();" type="text" name="add500" id="add500i" autocomplete="off" class="form-control">

                    </td>
                    <td width="10%" class="text-xs-right">
                        <span id="ad500i"></span>
                        <p id="500im"></p>
                    </td>
                </tr>
                <tr>
                    <td>200 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                    <td>
                        <input onkeypress="return isNumber(event)" tabindex="8" onkeyup="calIncomeMoney();" type="text" name="add200" id="add200i" autocomplete="off" class="form-control">

                    </td>
                    <td width="10%" class="text-xs-right">
                        <span id="ad200i"></span>
                        <p id="200im"></p>
                    </td>

                    <td>100 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                    <td>
                        <input onkeypress="return isNumber(event)" tabindex="9" onkeyup="calIncomeMoney();;" type="text" name="add100" id="add100i" autocomplete="off" class="form-control">

                    </td>
                    <td width="10%" class="text-xs-right">
                        <span id="ad100i"></span>
                        <p id="100im"></p>
                    </td>
                </tr>
                <tr>
                    <td>50 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                    <td>
                        <input onkeypress="return isNumber(event)" tabindex="10" onkeyup="calIncomeMoney();" type="text" name="add50" id="add50i" autocomplete="off" class="form-control">

                    </td>
                    <td width="10%" class="text-xs-right">
                        <span id="ad50i"></span>
                        <p id="50im"></p>
                    </td>

                    <td>20 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                    <td>
                        <input onkeypress="return isNumber(event)" tabindex="11" onkeyup="calIncomeMoney();" type="text" name="add20" id="add20i" autocomplete="off" class="form-control">

                    </td>
                    <td width="10%" class="text-xs-right">
                        <span id="ad20i"></span>
                        <p id="20im"></p>
                    </td>
                </tr>
                <tr>
                    <td>10 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                    <td>
                        <input onkeypress="return isNumber(event)" tabindex="12" onkeyup="calIncomeMoney();" type="text" name="add10" id="add10i" autocomplete="off" class="form-control">

                    </td>
                    <td width="10%" class="text-xs-right">
                        <span id="ad10i"></span>
                        <p id="10im"></p>
                    </td>

                    <td>Coins &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td>
                        <input onkeypress="return isNumber(event)" tabindex="13" onkeyup="calIncomeMoney();" type="text" name="coin" id="coini" autocomplete="off" class="form-control">

                    </td>
                    <td width="10%" class="text-xs-right">
                        <span id="coinsi"></span>
                        <p id="coinsim"></p>
                    </td>
                </tr>
                <tr>
                    <td>Total &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

                    <td colspan="2" class="text-xs-right">
                        <span id="calTotali"></span>

                        <input type="hidden" name="collectedIncomeTotal" id="collectedIncomeTotal" autocomplete="off" class="form-control">
                    </td>

                    <td colspan="2" class="text-xs-right">
                        <span id="income_exp_short_status"></span>
                    </td>
                    <td class="text-xs-right">
                        <span id="income_exp_short_amt"></span>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
    <div class="col-md-1"></div>
</div>

<div class="col-md-12">

    <center>                                               
        <button id="incomeDisableButton" type="submit" class="btn btn-primary m-t-15 waves-effect">
            <i class="material-icons">save</i> 
            <span class="icon-name">
                Save
            </span>
        </button> 

        <button type="button" data-dismiss="modal" id="inClose"  class="btn btn-primary m-t-15 waves-effect">
            <i class="material-icons">cancel</i> 
            <span class="icon-name">
                cancel
            </span>
        </button>

    </center>

</div>
</div>


</form>
</div>
</div>
</div>
</div>
</div>



<!-- Add Income -->
<div class="container">
  <div class="modal fade" id="myModal1" role="dialog" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
          <center> <h3 class="modal-title">Add Expense</h3> </center>
      </div>
      <div class="modal-body">
        <form method="post" role="form" onsubmit="return checkExpenseCorrectInputs(this);" action="<?php echo site_url('manager/CashBookController/insertIncomeOutflow');?>"> 
            <div class="row clearfix">
                <div class="col-md-12">
                    <input type="hidden" value="<?php echo (($closingBal+$totalInflow)-($totalOutflow+$totalBankDeposit)+($diffContraEntry)); ?>" name="expenceData_amt" id="expenceData_amt" autocomplete="off" class="form-control">

                    <div class="col-md-4">
                        <p>
                           <b>Category </b>
                       </p>


                       <input type="text" autocomplete="off" tabindex="1" placeholder="select category" list="categoryOutflowList" id="categoryOutflow" name="categoryOutflow" class="form-control" required> 
                       <datalist id="categoryOutflowList">
                        <?php foreach($cat_expense as $in){ ?>
                            <option id="<?php echo $in['id'] ?>" value="<?php echo $in['categoryName'] ?>" />
                            <?php } ?>

                        </datalist> 
                    </div> 

                    <div class="col-md-4">
                        <p>
                         <b>Employee Name </b>
                     </p>

                     <input type="text" autocomplete="off" tabindex="2" placeholder="select employee" list="empNameOutflowList" id="empNameOutflow" name="empNameOutflow" class="form-control" required> 
                     <datalist id="empNameOutflowList">
                      <?php foreach ($emp as $req_item): ?>
                        <option id="<?php echo $req_item['id'] ?>" value="<?php echo $req_item['name'] ?>" />
                        <?php endforeach ?> 
                    </datalist>
                    <input type="hidden" id="empOutflowId" name="empOutflowId" class="form-control"> 
                </div>

                <div class="col-md-4" id="exp-cmp-cmbo">
                    <p>
                     <b>Company </b>
                 </p>

                 <input type="text" required autocomplete="off" tabindex="5" placeholder="select company" list="compNameOutflowList" id="compNameOutflow" name="compNameOutflow" class="form-control"> 
                 <datalist id="compNameOutflowList">
                    <?php foreach ($companyDetails as $req_item): ?>
                        <option id="<?php echo $req_item['id'] ?>" value="<?php echo $req_item['name'] ?>" />
                        <?php endforeach ?> 
                    </datalist> 
                </div>



            </div>   


            <div class="col-md-12">

                <div class="col-md-4">                                       
                    <p>
                      <b> Amount </b>
                  </p>
                  <div class="form-line">
                    <input type="text" onkeypress="return isNumber(event)" tabindex="3" autocomplete="off" placeholder="amount" id="cashAmtOutflow" name="cashAmtOutflow" class="form-control" required>           

                </div>  
                <div style="color:red;" id="sr_qty"></div>          
            </div>

            <div class="col-md-8">                                       
                <p>
                  <b> Narration </b>
              </p>
              <div class="form-line">
                <input type="text" autocomplete="off" placeholder="narration" tabindex="4" id="narrationOutflow" name="narrationOutflow" class="form-control" required>           
            </div>  
            <div style="color:red;" id="sr_qty"></div>          
        </div>
    </div>
</div>
<br>

<div class="col-md-12">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <table class="table table-bordered table-striped table-hover js-basic-example" style="font-size: 13px;">
            <thead>
                <tr>
                    <th class="text-xs-center" colspan="6"><center>Notes</center></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2000 &nbsp;&nbsp;&nbsp;&nbsp;X</td>
                    <td>
                        <input onkeypress="return isNumber(event)" tabindex="6" onkeyup="calExpenseMoney();" type="text" name="add2000" id="add2000e" autocomplete="off" class="form-control">

                    </td>
                    <td width="10%" class="text-xs-right">
                        <span id="ad2000e"></span><span id="ad2000m"></span>
                    </td>

                    <td>500 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                    <td>
                        <input onkeypress="return isNumber(event)" tabindex="7" onkeyup="calExpenseMoney();" type="text" name="add500" id="add500e" autocomplete="off" class="form-control">

                    </td>
                    <td width="10%" class="text-xs-right">
                        <span id="ad500e"></span>
                    </td>
                </tr>
                <tr>
                    <td>200 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                    <td>
                        <input onkeypress="return isNumber(event)" tabindex="8" onkeyup="calExpenseMoney();" type="text" name="add200" id="add200e" autocomplete="off" class="form-control">

                    </td>
                    <td width="10%" class="text-xs-right">
                        <span id="ad200e"></span>
                    </td>

                    <td>100 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                    <td>
                        <input onkeypress="return isNumber(event)" tabindex="9" onkeyup="calExpenseMoney();;" type="text" name="add100" id="add100e" autocomplete="off" class="form-control">

                    </td>
                    <td width="10%" class="text-xs-right">
                        <span id="ad100e"></span>
                    </td>
                </tr>
                <tr>
                    <td>50 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                    <td>
                        <input onkeypress="return isNumber(event)" tabindex="10" onkeyup="calExpenseMoney();" type="text" name="add50" id="add50e" autocomplete="off" class="form-control">

                    </td>
                    <td width="10%" class="text-xs-right">
                        <span id="ad50e"></span>
                    </td>

                    <td>20 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                    <td>
                        <input onkeypress="return isNumber(event)" tabindex="11" onkeyup="calExpenseMoney();" type="text" name="add20" id="add20e" autocomplete="off" class="form-control">

                    </td>
                    <td width="10%" class="text-xs-right">
                        <span id="ad20e"></span>
                    </td>
                </tr>
                <tr>
                    <td>10 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                    <td>
                        <input onkeypress="return isNumber(event)" tabindex="12" onkeyup="calExpenseMoney();" type="text" name="add10" id="add10e" autocomplete="off" class="form-control">

                    </td>
                    <td width="10%" class="text-xs-right">
                        <span id="ad10e"></span>
                    </td>

                    <td>Coins &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td>
                        <input onkeypress="return isNumber(event)" tabindex="13" onkeyup="calExpenseMoney();" type="text" name="coin" id="coine" autocomplete="off" class="form-control">

                    </td>
                    <td width="10%" class="text-xs-right">
                        <span id="coinse"></span>
                    </td>
                </tr>
                <tr>
                    

                    <td>Total &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

                    <td colspan="2" class="text-xs-right">
                        <span id="calTotale"></span>

                        <input type="hidden" name="collectedExpenseTotal" id="collectedExpenseTotal" autocomplete="off" class="form-control">

                    </td>
                    <td colspan="2" class="text-xs-right">
                        <span id="expense_exp_short_status"></span>
                    </td>
                    <td class="text-xs-right">
                        <span id="expense_exp_short_amt"></span>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

</div>

<div class="row clearfix">
    <div class="col-md-12">

        <center>                                               
            <button id="expenseDisableButton" type="submit" class="btn btn-primary m-t-15 waves-effect">
                <i class="material-icons">save</i> 
                <span class="icon-name">
                    Save
                </span>
            </button> 

            <button data-dismiss="modal" type="button" id="expClose" class="btn btn-primary m-t-15 waves-effect">
                <i class="material-icons">cancel</i> 
                <span class="icon-name">
                    cancel
                </span>
            </button>

        </center>

    </div>
</div>
</form>
</div>
</div>
</div>
</div>
</div>

<!-- Add Bank Deposit -->
<div class="container">
  <div class="modal fade" id="myModal2" role="dialog" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
          <center><h3 class="modal-title">Add Bank Deposit</h3></center>
      </div>
      <div class="modal-body">
       <form method="post" role="form" onsubmit="return checkBankDepositAmount(this);" action="<?php echo site_url('manager/CashBookController/addBankDeposits');?>"> 
        <div class="row clearfix">
          <div class="col-md-12">
            <div class="col-md-4">
                <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-basic-example" data-page-length='100'>
                    <thead>
                        <tr>
                         <th>Title</th>
                         <th>Value</th>
                     </tr>
                 </thead>
                 <tbody>
                   <tr>
                    <td >Date</td>
                    <td align="right"><?php echo date('d-M-Y');?></td>
                </tr>
                <tr>
                    <td>Opening Balance</td>
                    <td  align="right"><?php echo number_format($closingBal,2); ?></td>
                </tr>
                <tr>
                    <td>Income</td>
                    <td align="right"><?php echo number_format(($totalInflow),2); ?></td>
                </tr>
                <tr>
                    <td>Expense</td>
                    <td align="right"><?php echo number_format(($totalOutflow),2); ?></td>
                </tr>
                <tr>
                    <td>Bank Deposit</td>
                    <td align="right"><?php echo number_format($totalBankDeposit,2); ?></td>
                </tr>
                <tr>
                    <td>Closing Balance</td>
                    <td align="right"><?php echo number_format((($closingBal+$totalInflow)-($totalOutflow+$totalBankDeposit)+($diffContraEntry)),2); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-8">
        <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-basic-example" data-page-length='100'>
            <thead>
                <tr style="background-color: #dfdddd;">
                    <th>Denomination</th>
                    <th><span class="pull-right">Opening Cash</span></th>
                    <th><span class="pull-right">Net Collection</span></th>
                    <th><span class="pull-right">Old Bank Deposit</span></th>
                    <th><span class="pull-right">Available Notes</span></th>
                    <th><span class="pull-right">Bank Deposit</span></th>
                    <th><span class="pull-right">Bank Deposit Value</span></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td align="right">2000</td>
                    <td align="right"><?php echo number_format($daybook_notes['note2000']); ?></td>
                    <td align="right"><?php echo number_format($income_notes['note2000']-$expense_notes['note2000']); ?></td>
                    <td align="right"><?php echo number_format($bank_deposit_notes['note2000']); ?></td>
                    <td align="right"><?php 
                    // echo number_format($final_notes_entry['note2000']); 
                    echo number_format(($daybook_notes['note2000']+($income_notes['note2000']-$expense_notes['note2000']))-$bank_deposit_notes['note2000']); 
                    ?></td>
                    <td><input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" onkeyup="calMoney();" type="text" name="add2000" id="add2000" autocomplete="off" class="form-control"></td>
                    <td align="right" id="t2000"></td>
                </tr>
                <tr>
                    <td align="right">500</td>
                    <td align="right"><?php echo number_format($daybook_notes['note500']); ?></td>
                    <td align="right"><?php echo number_format($income_notes['note500']-$expense_notes['note500']); ?></td>
                    <td align="right"><?php echo number_format($bank_deposit_notes['note500']); ?></td>
                    <td align="right"><?php 
                    // echo number_format($final_notes_entry['note500']); 
                    echo number_format(($daybook_notes['note500']+($income_notes['note500']-$expense_notes['note500']))-$bank_deposit_notes['note500']); 
                    ?></td>
                    <td>
                        <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" onkeyup="calMoney();" type="text" name="add500" id="add500" autocomplete="off" class="form-control">

                    </td>
                    <td align="right" id="t500"></td>
                </tr>
                <tr>
                    <td align="right">200</td>
                    <td align="right"><?php echo number_format($daybook_notes['note200']); ?></td>
                    <td align="right"><?php echo number_format($income_notes['note200']-$expense_notes['note200']); ?></td>
                    <td align="right"><?php echo number_format($bank_deposit_notes['note200']); ?></td>
                    <td align="right"><?php 
                    // echo number_format($final_notes_entry['note200']); 

                    echo number_format(($daybook_notes['note200']+($income_notes['note200']-$expense_notes['note200']))-$bank_deposit_notes['note200']); 
                    ?></td>
                    <td>
                        <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" onkeyup="calMoney();" type="text" name="add200" id="add200" autocomplete="off" class="form-control">

                    </td>
                    <td align="right" id="t200"></td>
                </tr>
                <tr>
                    <td align="right">100</td>
                    <td align="right"><?php echo number_format($daybook_notes['note100']); ?></td>
                    <td align="right"><?php echo number_format($income_notes['note100']-$expense_notes['note100']); ?></td>
                    <td align="right"><?php echo number_format($bank_deposit_notes['note100']); ?></td>
                    <td align="right"><?php
                    // echo number_format($final_notes_entry['note100']); 
                     echo number_format(($daybook_notes['note100']+($income_notes['note100']-$expense_notes['note100']))-$bank_deposit_notes['note100']); 
                     ?></td>
                    <td>
                        <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" onkeyup="calMoney();;" type="text" name="add100" id="add100" autocomplete="off" class="form-control">

                    </td>
                    <td align="right" id="t100"></td>
                </tr>
                <tr>
                    <td align="right">50</td>
                    <td align="right"><?php echo number_format($daybook_notes['note50']); ?></td>
                    <td align="right"><?php echo number_format($income_notes['note50']-$expense_notes['note50']); ?></td>
                    <td align="right"><?php echo number_format($bank_deposit_notes['note50']); ?></td>
                    <td align="right"><?php 
                    // echo number_format($final_notes_entry['note50']); 

                    echo number_format(($daybook_notes['note50']+($income_notes['note50']-$expense_notes['note50']))-$bank_deposit_notes['note50']); 
                    ?></td>
                    <td>
                        <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" onkeyup="calMoney();" type="text" name="add50" id="add50" autocomplete="off" class="form-control">

                    </td>
                    <td align="right" id="t50"></td>
                </tr>
                <tr>
                    <td align="right">20</td>
                    <td align="right"><?php echo number_format($daybook_notes['note20']); ?></td>
                    <td align="right"><?php echo number_format($income_notes['note20']-$expense_notes['note20']); ?></td>
                    <td align="right"><?php echo number_format($bank_deposit_notes['note20']); ?></td>
                    <td align="right"><?php 
                    // echo number_format($final_notes_entry['note20']); 

                    echo number_format(($daybook_notes['note20']+($income_notes['note20'])-$expense_notes['note20'])-$bank_deposit_notes['note20']); 
                    ?></td>
                    <td>
                        <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" onkeyup="calMoney();" type="text" name="add20" id="add20" autocomplete="off" class="form-control">

                    </td>
                    <td align="right" id="t20"></td>
                </tr>
                <tr>
                    <td align="right">10</td>
                    <td align="right"><?php echo number_format($daybook_notes['note10']); ?></td>
                    <td align="right"><?php echo number_format($income_notes['note10']-$expense_notes['note10']); ?></td>
                    <td align="right"><?php echo number_format($bank_deposit_notes['note10']); ?></td>
                    <td align="right">
                      <?php 
                      // echo number_format($final_notes_entry['note10']); 
                      echo number_format(($daybook_notes['note10']+($income_notes['note10']-$expense_notes['note10']))-$bank_deposit_notes['note10']); 
                      ?></td>
                    <td>
                        <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" onkeyup="calMoney();" type="text" name="add10" id="add10" autocomplete="off" class="form-control">

                    </td>
                    <td align="right" id="t10"></td>
                </tr>
                <tr>
                    <td align="right">coins</td>
                    <td align="right"><?php echo number_format($daybook_notes['coins']); ?></td>
                    <td align="right"><?php echo number_format($income_notes['coins']-$expense_notes['coins']); ?></td>
                    <td align="right"><?php echo number_format($bank_deposit_notes['coins']); ?></td>
                    <td align="right"><?php 
                      // echo number_format($daybook_notes['coins']); 
                      
                      echo number_format(($daybook_notes['coins']+($income_notes['coins']-$expense_notes['coins']))-$bank_deposit_notes['coins']); 
                    ?></td>
                    <td>
                        <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" onkeyup="calMoney();" type="text" name="coin" id="coin" autocomplete="off" class="form-control">

                    </td>
                    <td align="right" id="tcoins"></td>
                </tr>
                <tr style="background-color: #dfdddd;">
                    <td align="right"><b>Total</b></td>
                    <td align="right"><b><?php echo number_format($daybook_notes['collectedAmt']); ?></b></td>
                    <td align="right"><b><?php echo number_format($income_notes['collectedAmt']-$expense_notes['collectedAmt']); ?></b></td>
                    <td align="right"><b><?php echo number_format($bank_deposit_notes['collectedAmt']); ?></b></td>
                    <td align="right"><b>
                      <?php 
                      echo number_format(($daybook_notes['collectedAmt']+($income_notes['collectedAmt']-$expense_notes['collectedAmt']))-$bank_deposit_notes['collectedAmt']); 
                      ?></b></td>
                    <td class="text-xs-right">
                        <span id="calTotal"></span>
                        <input type="hidden" value="<?php echo (($closingBal+$totalInflow)-($totalOutflow+$totalBankDeposit)+($diffContraEntry)); ?>" name="bankDep_amt" id="bankDep_amt" autocomplete="off" class="form-control">
                        <input type="hidden" name="collectedDepositTotal" id="collectedDepositTotal" autocomplete="off" class="form-control">
                    </td>
                    <td align="right"><span id="tcalTotal"></span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>

<div class="row clearfix">
    <div class="col-md-12">
        <center>                                               
            <button id="bankDepositDisableButton" type="submit" class="btn btn-primary m-t-15 waves-effect">
                <i class="material-icons">save</i> 
                <span class="icon-name">
                    Save
                </span>
            </button> 

            <button data-dismiss="modal" type="button" id="bnkClose" class="btn btn-primary m-t-15 waves-effect">
                <i class="material-icons">cancel</i> 
                <span class="icon-name">
                    cancel
                </span>
            </button>
        </center>
    </div>
</div>
</form>
</div>
</div>
</div>
</div>
</div>

<div class="container">
  <div class="modal fade" id="closeDayModal" role="dialog" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
          <center><h3 class="modal-title">Close Day Book</h3></center>
      </div>
      <div class="modal-body">
        <div class="col-md-12">
            <div class="col-md-3">
                <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-basic-example" data-page-length='100'>
                    <thead>
                        <tr>
                         <th>Title</th>
                         <th>Value</th>
                     </tr>
                 </thead>
                 <tbody>
                   <tr>
                    <td >Date</td>
                    <td align="right"><?php echo date('d-M-Y');?></td>
                </tr>
                <tr>
                    <td>Opening Balance</td>
                    <td  align="right"><?php echo number_format($closingBal,2); ?></td>
                </tr>
                <tr>
                    <td>Income</td>
                    <td align="right"><?php echo number_format(($totalInflow),2); ?></td>
                </tr>
                <tr>
                    <td>Expense</td>
                    <td align="right"><?php echo number_format(($totalOutflow),2); ?></td>
                </tr>
                <tr>
                    <td>Bank Deposit</td>
                    <td align="right"><?php echo number_format($totalBankDeposit,2); ?></td>
                </tr>
                <tr>
                    <td>Closing Balance</td>
                    <td align="right"><?php echo number_format((($closingBal+$totalInflow)-($totalOutflow+$totalBankDeposit)+($diffContraEntry)),2); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-basic-example" data-page-length='100'>
            <thead>
                <tr style="background-color: #dfdddd;">
                    <th>Donomination</th>
                    <th><span class="pull-right">Opening Cash</span></th>
                    <th><span class="pull-right">Day Collection</span></th>
                    <th><span class="pull-right">Bank Deposit</span></th>
                    <th><span class="pull-right">Evening Cash Count</span></th>
                    <th><span class="pull-right">Closing Cash</span></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td align="right">2000</td>
                    <td align="right"><?php echo number_format($daybook_notes['note2000']); ?></td>
                    <td align="right"><?php echo number_format($income_notes['note2000']-$expense_notes['note2000']); ?></td>
                    <td align="right"><?php echo number_format($bank_deposit_notes['note2000']); ?></td>
                    <td align="right"><?php echo number_format(($daybook_notes['note2000']+($income_notes['note2000']-$expense_notes['note2000']))-$bank_deposit_notes['note2000']); ?></td>
                    <td align="right">
                        <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" onkeyup="calsMoney();" type="text" name="addd2000" id="addd2000" autocomplete="off" class="form-control">

                    </td>
                </tr>
                <tr>
                    <td align="right">500</td>
                    <td align="right"><?php echo number_format($daybook_notes['note500']); ?></td>
                    <td align="right"><?php echo number_format($income_notes['note500']-$expense_notes['note500']); ?></td>
                    <td align="right"><?php echo number_format($bank_deposit_notes['note500']); ?></td>
                    <td align="right"><?php echo number_format(($daybook_notes['note500']+($income_notes['note500']-$expense_notes['note500']))-$bank_deposit_notes['note500']); ?></td>
                    <td>
                        <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" onkeyup="calsMoney();" type="text" name="addd500" id="addd500" autocomplete="off" class="form-control">

                    </td>
                </tr>
                <tr>
                    <td align="right">200</td>
                    <td align="right"><?php echo number_format($daybook_notes['note200']); ?></td>
                    <td align="right"><?php echo number_format($income_notes['note200']-$expense_notes['note200']); ?></td>
                    <td align="right"><?php echo number_format($bank_deposit_notes['note200']); ?></td>
                    <td align="right"><?php echo number_format(($daybook_notes['note200']+($income_notes['note200']-$expense_notes['note200']))-$bank_deposit_notes['note200']); ?></td>
                    <td>
                        <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" onkeyup="calsMoney();" type="text" name="addd200" id="addd200" autocomplete="off" class="form-control">

                    </td>
                </tr>
                <tr>
                    <td align="right">100</td>
                    <td align="right"><?php echo number_format($daybook_notes['note100']); ?></td>
                    <td align="right"><?php echo number_format($income_notes['note100']-$expense_notes['note100']); ?></td>
                    <td align="right"><?php echo number_format($bank_deposit_notes['note100']); ?></td>
                    <td align="right"><?php echo number_format(($daybook_notes['note100']+($income_notes['note100']-$expense_notes['note100']))-$bank_deposit_notes['note100']); ?></td>
                    <td>
                        <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" onkeyup="calsMoney();;" type="text" name="addd100" id="addd100" autocomplete="off" class="form-control">

                    </td>
                </tr>
                <tr>
                    <td align="right">50</td>
                    <td align="right"><?php echo number_format($daybook_notes['note50']); ?></td>
                    <td align="right"><?php echo number_format($income_notes['note50']-$expense_notes['note50']); ?></td>
                    <td align="right"><?php echo number_format($bank_deposit_notes['note50']); ?></td>
                    <td align="right"><?php echo number_format(($daybook_notes['note50']+($income_notes['note50']-$expense_notes['note50']))-$bank_deposit_notes['note50']); ?></td>
                    <td>
                        <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" onkeyup="calsMoney();" type="text" name="addd50" id="addd50" autocomplete="off" class="form-control">

                    </td>
                </tr>
                <tr>
                    <td align="right">20</td>
                    <td align="right"><?php echo number_format($daybook_notes['note20']); ?></td>
                    <td align="right"><?php echo number_format($income_notes['note20']-$expense_notes['note20']); ?></td>
                    <td align="right"><?php echo number_format($bank_deposit_notes['note20']); ?></td>
                    <td align="right"><?php echo number_format(($daybook_notes['note20']+($income_notes['note20']-$expense_notes['note20']))-$bank_deposit_notes['note20']); ?></td>
                    <td>
                        <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" onkeyup="calsMoney();" type="text" name="addd20" id="addd20" autocomplete="off" class="form-control">

                    </td>
                </tr>
                <tr>
                    <td align="right">10</td>
                    <td align="right"><?php echo number_format($daybook_notes['note10']); ?></td>
                    <td align="right"><?php echo number_format($income_notes['note10']-$expense_notes['note10']); ?></td>
                    <td align="right"><?php echo number_format($bank_deposit_notes['note10']); ?></td>
                    <td align="right"><?php echo number_format(($daybook_notes['note10']+($income_notes['note10']-$expense_notes['note10']))-$bank_deposit_notes['note10']); ?></td>
                    <td>
                        <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" onkeyup="calsMoney();" type="text" name="addd10" id="addd10" autocomplete="off" class="form-control">

                    </td>
                </tr>
                <tr>
                    <td align="right">coins</td>
                    <td align="right"><?php echo number_format($daybook_notes['coins']); ?></td>
                    <td align="right"><?php echo number_format($income_notes['coins']-$expense_notes['coins']); ?></td>
                    <td align="right"><?php echo number_format($bank_deposit_notes['coins']); ?></td>
                    <td align="right"><?php echo number_format(($daybook_notes['coins']+($income_notes['coins']-$expense_notes['coins']))-$bank_deposit_notes['coins']); ?></td>
                    <td>
                        <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" onkeyup="calsMoney();" type="text" name="coin_s" id="coin_s" autocomplete="off" class="form-control">

                    </td>
                </tr>
                <tr style="background-color: #dfdddd;">
                    <td align="right"><b>Total</b></td>
                    <td align="right"><b><?php echo number_format($daybook_notes['collectedAmt']); ?></b></td>
                    <td align="right"><b><?php echo number_format($income_notes['collectedAmt']-$expense_notes['collectedAmt']); ?></b></td>
                    <td align="right"><b><?php echo number_format($bank_deposit_notes['collectedAmt']); ?></b></td>
                    <td align="right"><b><?php 
                    echo number_format(($daybook_notes['collectedAmt']+($income_notes['collectedAmt']-$expense_notes['collectedAmt']))-$bank_deposit_notes['collectedAmt']); 
                    ?></b></td>
                    <td class="text-xs-right">
                        <span id="calsTotal"></span>
                        <input onkeypress="return isNumber(event)" onkeyup="calsMoney();" type="hidden" name="collectedTotal" id="collectedTotal" autocomplete="off" class="form-control">

                        <input onkeypress="return isNumber(event)" onkeyup="calsMoney();" type="hidden" value="<?php echo (($closingBal+$totalInflow)-($totalOutflow+$totalBankDeposit)+($diffContraEntry)); ?>" name="clos_bank_amt" id="clos_bank_amt" autocomplete="off" class="form-control">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>


    <div class="col-md-3">
        <table style="font-size: 11px" class="table table-bordered table-striped table-hover js-basic-example DataTable display nowrap" id="example" data-page-length='100'>
            <thead>
                <tr>
                    <th colspan="2"><center>Day Book Details</center></th>
                </tr>
            </thead>
            <tbody>
              <tr>
                <td>Cashier Name</td>
                <td align="right" id="chashierNames"><?php echo $this->session->userdata[$projectSessionName]['username']; ?></td>
            </tr>
            <tr>
                <td>Cash as per Accounts</td>
                <td align="right"><?php echo number_format((($closingBal+$totalInflow)-($totalOutflow+$totalBankDeposit)+($diffContraEntry)),2); ?></td>
            </tr>

            <tr>
                <td>Physical Cash</td>
                <td align="right" id="phycalsTotal"></td>
            </tr>

            <tr>
                <td>Excess</td>
                <td align="right" id="ExcessCash" style="color:blue"></td>
            </tr>

            <tr>
                <td>Short</td>
                <td align="right" id="ShortCash" style="color:red"></td>
                <input type="hidden" name="short_amt" id="short_amt" autocomplete="off" class="form-control">
            </tr>
        </tbody>
    </table>
</div>

</div>


                    <div class="row clearfix">
                        <div class="col-md-12">

                            <center>                                               
                                <button id="closeDayBookDisableButton" onclick="submitDayBook(this);" type="button" class="btn btn-primary m-t-15 waves-effect">
                                    <i class="material-icons">save</i> 
                                    <span class="icon-name">
                                        Save
                                    </span>
                                </button> 

                                <button data-dismiss="modal" type="button" id="dayBClose" class="btn btn-primary m-t-15 waves-effect">
                                    <i class="material-icons">cancel</i> 
                                    <span class="icon-name">
                                        cancel
                                    </span>
                                </button>
                            </center>

                        </div>
                    </div>
                    <!-- </form> -->
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Exchange notes Income -->
<div class="container">
  <div class="modal fade" id="exchangeModel" role="dialog" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
          <center> <h3 class="modal-title">Notes Exchange</h3> </center>
      </div>
       <div class="modal-body">
       <form method="post" role="form" onsubmit="return checkNoteExchangeInputs(this);" action="<?php echo site_url('manager/CashBookController/insertNotesExchange');?>"> 
        <div class="row clearfix">
          <div class="col-md-12">
           <!--  <div class="col-md-4">
                <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-basic-example" data-page-length='100'>
                    <thead>
                        <tr>
                         <th>Title</th>
                         <th>Value</th>
                     </tr>
                 </thead>
                 <tbody>
                   <tr>
                    <td >Date</td>
                    <td align="right"><?php echo date('d-M-Y');?></td>
                </tr>
                <tr>
                    <td>Opening Balance</td>
                    <td  align="right"><?php echo number_format($closingBal,2); ?></td>
                </tr>
                <tr>
                    <td>Income</td>
                    <td align="right"><?php echo number_format(($totalInflow+($totalMarketExpense)),2); ?></td>
                </tr>
                <tr>
                    <td>Expence</td>
                    <td align="right"><?php echo number_format(($totalOutflow+($totalMarketExpense)),2); ?></td>
                </tr>
                <tr>
                    <td>Bank Deposit</td>
                    <td align="right"><?php echo number_format($totalBankDeposit,2); ?></td>
                </tr>
                <tr>
                    <td>Closing Balance</td>
                    <td align="right"><?php echo number_format((($closingBal+$totalInflow)-($totalOutflow+$totalBankDeposit)),2); ?></td>
                </tr>
            </tbody>
        </table>
    </div> -->
    <div class="col-md-12">
        <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-basic-example" data-page-length='100'>
            <thead>
                <tr style="background-color: #dfdddd;">
                    <th>Denomination</th>
                    <!-- <th><span class="pull-right">Opening Cash</span></th> -->
                    <!-- <th><span class="pull-right">Net Collection</span></th> -->
                    <!-- <th><span class="pull-right">Old Bank Deposit</span></th> -->
                    <th><span class="pull-right">Available Notes</span></th>
                    <th><span class="pull-right">Exchange Notes</span></th>
                    <th><span class="pull-right">Exchange Notes Value</span></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td align="right">2000</td>
                    <!-- <td align="right"><?php echo number_format($daybook_notes['note2000']); ?></td> -->
                    <!-- <td align="right"><?php echo number_format($income_notes['note2000']-$expense_notes['note2000']); ?></td> -->
                    <!-- <td align="right"><?php echo number_format($bank_deposit_notes['note2000']); ?></td> -->
                    <td align="right"><?php 
                    // echo number_format($final_notes_entry['note2000']); 
                    echo number_format(($daybook_notes['note2000']+($income_notes['note2000']-$expense_notes['note2000']))-$bank_deposit_notes['note2000']); 
                    ?></td>
                    <td><input onkeypress="return isNumber(event)" style="height: 25px;width:100px" onkeyup="calNoteExchangeMoney();" type="text" name="add2000" id="add2000exch" autocomplete="off" class="form-control"></td>
                    <td align="right" id="t2000exch"></td>
                </tr>
                <tr>
                    <td align="right">500</td>
                    <!-- <td align="right"><?php echo number_format($daybook_notes['note500']); ?></td> -->
                    <!-- <td align="right"><?php echo number_format($income_notes['note500']-$expense_notes['note500']); ?></td> -->
                    <!-- <td align="right"><?php echo number_format($bank_deposit_notes['note500']); ?></td> -->
                    <td align="right"><?php 
                    // echo number_format($final_notes_entry['note500']); 
                    echo number_format(($daybook_notes['note500']+($income_notes['note500']-$expense_notes['note500']))-$bank_deposit_notes['note500']); 
                    ?></td>
                    <td>
                        <input onkeypress="return isNumber(event)" style="height: 25px;width:100px" onkeyup="calNoteExchangeMoney();" type="text" name="add500" id="add500exch" autocomplete="off" class="form-control">

                    </td>
                    <td align="right" id="t500exch"></td>
                </tr>
                <tr>
                    <td align="right">200</td>
                    <!-- <td align="right"><?php echo number_format($daybook_notes['note200']); ?></td> -->
                    <!-- <td align="right"><?php echo number_format($income_notes['note200']-$expense_notes['note200']); ?></td> -->
                    <!-- <td align="right"><?php echo number_format($bank_deposit_notes['note200']); ?></td> -->
                    <td align="right"><?php 
                    // echo number_format($final_notes_entry['note200']); 

                    echo number_format(($daybook_notes['note200']+($income_notes['note200']-$expense_notes['note200']))-$bank_deposit_notes['note200']); 
                    ?></td>
                    <td>
                        <input onkeypress="return isNumber(event)" style="height: 25px;width:100px" onkeyup="calNoteExchangeMoney();" type="text" name="add200" id="add200exch" autocomplete="off" class="form-control">

                    </td>
                    <td align="right" id="t200exch"></td>
                </tr>
                <tr>
                    <td align="right">100</td>
                    <!-- <td align="right"><?php echo number_format($daybook_notes['note100']); ?></td> -->
                    <!-- <td align="right"><?php echo number_format($income_notes['note100']-$expense_notes['note100']); ?></td> -->
                    <!-- <td align="right"><?php echo number_format($bank_deposit_notes['note100']); ?></td> -->
                    <td align="right"><?php
                    // echo number_format($final_notes_entry['note100']); 
                     echo number_format(($daybook_notes['note100']+($income_notes['note100']-$expense_notes['note100']))-$bank_deposit_notes['note100']); 
                     ?></td>
                    <td>
                        <input onkeypress="return isNumber(event)" style="height: 25px;width:100px" onkeyup="calNoteExchangeMoney();;" type="text" name="add100" id="add100exch" autocomplete="off" class="form-control">

                    </td>
                    <td align="right" id="t100exch"></td>
                </tr>
                <tr>
                    <td align="right">50</td>
                    <!-- <td align="right"><?php echo number_format($daybook_notes['note50']); ?></td> -->
                    <!-- <td align="right"><?php echo number_format($income_notes['note50']-$expense_notes['note50']); ?></td> -->
                    <!-- <td align="right"><?php echo number_format($bank_deposit_notes['note50']); ?></td> -->
                    <td align="right"><?php 
                    // echo number_format($final_notes_entry['note50']); 

                    echo number_format(($daybook_notes['note50']+($income_notes['note50']-$expense_notes['note50']))-$bank_deposit_notes['note50']); 
                    ?></td>
                    <td>
                        <input onkeypress="return isNumber(event)" style="height: 25px;width:100px" onkeyup="calNoteExchangeMoney();" type="text" name="add50" id="add50exch" autocomplete="off" class="form-control">

                    </td>
                    <td align="right" id="t50exch"></td>
                </tr>
                <tr>
                    <td align="right">20</td>
                    <!-- <td align="right"><?php echo number_format($daybook_notes['note20']); ?></td> -->
                    <!-- <td align="right"><?php echo number_format($income_notes['note20']-$expense_notes['note20']); ?></td> -->
                    <!-- <td align="right"><?php echo number_format($bank_deposit_notes['note20']); ?></td> -->
                    <td align="right"><?php 
                    // echo number_format($final_notes_entry['note20']); 

                    echo number_format(($daybook_notes['note20']+($income_notes['note20'])-$expense_notes['note20'])-$bank_deposit_notes['note20']); 
                    ?></td>
                    <td>
                        <input onkeypress="return isNumber(event)" style="height: 25px;width:100px" onkeyup="calNoteExchangeMoney();" type="text" name="add20" id="add20exch" autocomplete="off" class="form-control">

                    </td>
                    <td align="right" id="t20exch"></td>
                </tr>
                <tr>
                    <td align="right">10</td>
                    <!-- <td align="right"><?php echo number_format($daybook_notes['note10']); ?></td> -->
                    <!-- <td align="right"><?php echo number_format($income_notes['note10']-$expense_notes['note10']); ?></td> -->
                    <!-- <td align="right"><?php echo number_format($bank_deposit_notes['note10']); ?></td> -->
                    <td align="right">
                      <?php 
                      // echo number_format($final_notes_entry['note10']); 
                      echo number_format(($daybook_notes['note10']+($income_notes['note10']-$expense_notes['note10']))-$bank_deposit_notes['note10']); 
                      ?></td>
                    <td>
                        <input onkeypress="return isNumber(event)" style="height: 25px;width:100px" onkeyup="calNoteExchangeMoney();" type="text" name="add10" id="add10exch" autocomplete="off" class="form-control">

                    </td>
                    <td align="right" id="t10exch"></td>
                </tr>
                <tr>
                    <td align="right">coins</td>
                    <!-- <td align="right"><?php echo number_format($daybook_notes['coins']); ?></td> -->
                    <!-- <td align="right"><?php echo number_format($income_notes['coins']-$expense_notes['coins']); ?></td> -->
                    <!-- <td align="right"><?php echo number_format($bank_deposit_notes['coins']); ?></td> -->
                    <td align="right"><?php 
                      // echo number_format($daybook_notes['coins']); 
                      
                      echo number_format(($daybook_notes['coins']+($income_notes['coins']-$expense_notes['coins']))-$bank_deposit_notes['coins']); 
                    ?></td>
                    <td>
                        <input onkeypress="return isNumber(event)" style="height: 25px;width:100px" onkeyup="calNoteExchangeMoney();" type="text" name="coin" id="coinexch" autocomplete="off" class="form-control">

                    </td>
                    <td align="right" id="tcoinsexch"></td>
                </tr>
                <tr style="background-color: #dfdddd;">
                    <td align="right"><b>Total</b></td>
                    <!-- <td align="right"><b><?php echo number_format($daybook_notes['collectedAmt']); ?></b></td> -->
                    <!-- <td align="right"><b><?php echo number_format($income_notes['collectedAmt']-$expense_notes['collectedAmt']); ?></b></td> -->
                    <!-- <td align="right"><b><?php echo number_format($bank_deposit_notes['collectedAmt']); ?></b></td> -->
                    <td align="right"><b>
                      <?php 
                      // echo number_format(($daybook_notes['collectedAmt']+($income_notes['collectedAmt']-$expense_notes['collectedAmt']))-$bank_deposit_notes['collectedAmt']); 
                      ?></b></td>
                    <td class="text-xs-right">
                        <span id="calTotalexch"></span>
                        <input type="hidden" name="collectedExpenseTotal" id="collectedExpenseTotalexch" autocomplete="off" class="form-control">
                    </td>
                    <td class="text-xs-right">
                        <span id="expense_exp_short_status_exch"></span>
                        <span id="expense_exp_short_amt_exch"></span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>

<div class="row clearfix">
    <div class="col-md-12">
        <center>                                               
            <button id="exchDisButton" type="submit" class="btn btn-primary m-t-15 waves-effect">
                <i class="material-icons">save</i> 
                <span class="icon-name">
                    Save
                </span>
            </button> 

            <button data-dismiss="modal" type="button" id="noteExClose" class="btn btn-primary m-t-15 waves-effect">
                <i class="material-icons">cancel</i> 
                <span class="icon-name">
                    cancel
                </span>
            </button>
        </center>
    </div>
</div>
</form>
</div>
</div>
</div>
</div>
</div>

<?php $this->load->view('/layouts/footerDataTable'); ?>



<script type="text/javascript">
    $(document).on('hidden.bs.modal','#myModal',function(){
      $(this).find("input:not([type=hidden]),textarea,select").val('').end().find("input[type=checkbox], input[type=radio]").prop("checked", "").end();
  });

    $('#myModal1').on('hidden.bs.modal', function () {
      $(this).find("input:not([type=hidden]),textarea,select").val('').end().find("input[type=checkbox], input[type=radio]").prop("checked", "").end();
  });

    $('#myModal2').on('hidden.bs.modal', function () {
      $(this).find("input:not([type=hidden]),textarea,select").val('').end().find("input[type=checkbox], input[type=radio]").prop("checked", "").end();

      $('#add2000').val('');
      $('#add1000').val('');
      $('#add500').val('');
      $('#add200').val('');
      $('#add100').val('');
      $('#add50').val('');
      $('#add20').val('');
      $('#add10').val('');
      $('#coin').val('');

      $('#ad2000').text('');
      $('#ad1000').text('');
      $('#ad500').text('');
      $('#ad200').text('');
      $('#ad100').text('');
      $('#ad50').text('');
      $('#ad20').text('');
      $('#ad10').text('');
      $('#coins').text('');
  });

</script>

<script type="text/javascript">
    $('#closeDayModal').on('hidden.bs.modal', function () {
        $(this).find("input,textarea,select").val('').end();
    });
</script>

<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>

<script type="text/javascript">
    $(document).on('blur','#empName',function(){
        var category=$('#category').val();
        var empName=$('#empName').val();
        var empId = $('#empNameList').find('option[value="'+empName+'"]').attr('id');
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('manager/CashBookController/getEmployeeCompany');?>",
            data:{"empId" : empId},
            success: function (data) {
                if(category==="Employee Credit"){
                    $('#compName').val(data);
                    $('#compName').prop('readonly',true);
                }else{
                    $('#compName').removeAttr('readonly',false);
                }
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

    $(document).on('blur','#empNameOutflow',function(){
        var category=$('#categoryOutflow').val();
        var empName=$('#empNameOutflow').val();
        var empId = $('#empNameOutflowList').find('option[value="'+empName+'"]').attr('id');
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('manager/CashBookController/getEmployeeCompany');?>",
            data:{"empId" : empId},
            success: function (data) {
                $('#compNameOutflow').val(data);
                if(category==="Employee Advances"){
                    $('#compNameOutflow').val(data);
                    $('#compNameOutflow').prop('readonly',true);
                }else{
                    $('#compNameOutflow').removeAttr('readonly',false);
                }
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

<script type="text/javascript">
    function checkNEFTAmt(pendCash){
        var cash=parseFloat(document.getElementById('cashAmt').value);
        var msg=document.getElementById('sr_qty');
            // alert(cash+' '+pendCash);
            var pendCash=parseFloat(pendCash);
            if(cash>pendCash){
                msg.innerHTML="Sorry!.. NEFT amount is greater than pending amount.";
            }else{
                msg.innerHTML="";
            }
        }
        
        function showCombos(){
            var getSelectValue = document.getElementById("nature").value;
            if(getSelectValue == "Other Income"){
              document.getElementById("category").style.display="block";
          }else{
              document.getElementById("category").style.display="none";

          }
          if(getSelectValue == "Employee Credit"){
              document.getElementById("empName").style.display="block";
          }else{
              document.getElementById("empName").style.display="none";
          }
      }
  </script>

  <script type="text/javascript">
    function showCombos1(){
        var getSelectValue = document.getElementById("nature1").value;

        if(getSelectValue == "Other Expenses"){
          document.getElementById("category1").style.display="block";
      }else{
          document.getElementById("category1").style.display="none";

      }
      if(getSelectValue == "Employee Advances"){
          document.getElementById("empName1").style.display="block";
      }else{
          document.getElementById("empName1").style.display="none";
      }
  }
</script>

<script type="text/javascript">
  function checkBankDepositAmount(){
      var bankAmount = Number(document.getElementById("collectedDepositTotal").value);
      if(Number(bankAmount)>0){
          var availableAmount = Number(document.getElementById("bankDep_amt").value);
        if(availableAmount<bankAmount){
          alert('Amount is more than closing amount.');
          return false;
        }

        if (confirm('Do you want to submit this Bank Deposit. ?')) {
          return true;
          $("#bankDepositDisableButton").attr("disabled", true);
        }else{
          return false;
          $("#bankDepositDisableButton").attr("disabled", true);
        }
      }else{
          alert('Please enter notes details.');
          return false;
      }
  } 

function checkRemAmount(){
  var clos_bank_amt = Number(document.getElementById("clos_bank_amt").value);
  var collectedTotal = Number(document.getElementById("collectedTotal").value);
  if(collectedTotal>clos_bank_amt){
    alert('Amount is more than closing amount.');
    return false;
}else{
    return true;
}
}

function checkExpenseAmount(){
  var clos_bank_amt = Number(document.getElementById("expenceData_amt").value);
  var collectedTotal = Number(document.getElementById("cashAmtOutflow").value);
  if(collectedTotal>clos_bank_amt){
    alert('Amount is more than closing amount.');
    return false;
}else{
    return true;
}
}
</script>

<script type="text/javascript">
  function calc() {
    var park = document.getElementById('park').value;
    var challan =document.getElementById('challan').value;
    var cng = document.getElementById('cng').value;

    if(park == ""){
        park = 0;
    }
    if(challan == ""){
        challan = 0;
    }
    if(cng == ""){
        cng = 0;
    }

    var cal=0;
    var cal =parseFloat(cal)+ parseFloat(park)+parseFloat(challan)+parseFloat(cng);
    if(cal=='')
        cal=0;
    document.getElementById('total').innerHTML = cal.toFixed(2);
    document.getElementById('total1').innerHTML = cal.toFixed(2);

}
</script>

<script type="text/javascript">
    function diff(cash){
        var cal1=document.getElementById('total1').innerHTML;
        var cal2=document.getElementById('calTotal1').innerHTML;
        if(cal1=='')
            cal1=0;
        if(cal2=='')
            cal2=0;
        var tot=parseFloat(cal1)+parseFloat(cal2);
        var finalDiff=cash-tot;

        if(finalDiff>0.0){
            document.getElementById('diff').innerHTML="<span style='color:red;font-size:15px;'>"+finalDiff.toFixed(2)+"</span>";
            document.getElementById('status').innerHTML="<span style='color: red;font-size:15px;'>Short</span>";
        }else{
            document.getElementById('diff').innerHTML="<span style='color:#037921;font-size:15px;'>"+finalDiff.toFixed(2)+"</span>";
            document.getElementById('status').innerHTML="<span style='color:#037921;font-size:15px;'>Excess</span>";
        }
    }
</script>

<!-- for Bank Deposit -->
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

    document.getElementById('t2000').innerHTML = c1;
    // document.getElementById('t1000').innerHTML = c2;
    document.getElementById('t500').innerHTML = c3;
    document.getElementById('t200').innerHTML = c4;
    document.getElementById('t100').innerHTML = c5;
    document.getElementById('t50').innerHTML = c6;
    document.getElementById('t20').innerHTML = c7;
    document.getElementById('t10').innerHTML = c8;
    document.getElementById('tcoins').innerHTML = c9;
    var total=0;
    total=total+c1+c3+c4+c5+c6+c7+c8;
    // total=total+c1+c2+c3+c4+c5+c6+c7+c8;
    total=parseFloat(total)+parseFloat(c9);
    document.getElementById('calTotal').innerHTML = total.toFixed(2);
    document.getElementById('tcalTotal').innerHTML = total.toFixed(2);
    document.getElementById('collectedDepositTotal').value = total.toFixed(2);

}
</script>


<!-- for Close daybook -->
<script type="text/javascript">
  function calsMoney() {

    var a2000 = document.getElementById('addd2000').value;
    var a500 = document.getElementById('addd500').value;
    var a200 = document.getElementById('addd200').value;
    var a100 = document.getElementById('addd100').value;
    var a50 = document.getElementById('addd50').value;
    var a20 = document.getElementById('addd20').value;
    var a10 = document.getElementById('addd10').value;
    var coin = document.getElementById('coin_s').value;

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

    // document.getElementById('aad2000').innerHTML = c1;
    // document.getElementById('aad1000').innerHTML = c2;
    // document.getElementById('aad500').innerHTML = c3;
    // document.getElementById('aad200').innerHTML = c4;
    // document.getElementById('aad100').innerHTML = c5;
    // document.getElementById('aad50').innerHTML = c6;
    // document.getElementById('aad20').innerHTML = c7;
    // document.getElementById('aad10').innerHTML = c8;
    // document.getElementById('coinss').innerHTML = c9;



    var total=0;
    total=total+c1+c3+c4+c5+c6+c7+c8;
    // total=total+c1+c2+c3+c4+c5+c6+c7+c8;
    total=parseFloat(total)+parseFloat(c9);
    document.getElementById('calsTotal').innerHTML = total.toFixed(2);
    document.getElementById('phycalsTotal').innerHTML = total.toFixed(2);
    document.getElementById('collectedTotal').value = total.toFixed(2);

    var clos_bank_amt = Number(document.getElementById("clos_bank_amt").value);
    var collectedTotal = Number(document.getElementById("collectedTotal").value);


    var excessShort=clos_bank_amt-collectedTotal;

    // alert(clos_bank_amt+' - '+collectedTotal+' = '+excessShort);die();
    if(excessShort>0){
        document.getElementById('ShortCash').innerText=excessShort.toFixed(2).replace('+','-');
        document.getElementById('ExcessCash').innerText="";
        document.getElementById('short_amt').value=excessShort.toFixed(2);
    }else{
        document.getElementById('ExcessCash').innerText=excessShort.toFixed(2).replace('-','+');
        document.getElementById('ShortCash').innerText="";
    }

}
</script>

<!-- for income money -->
<script type="text/javascript">
  function calIncomeMoney() {
    var act_amount=document.getElementById('cashAmt').value;
    if(act_amount===""){
        $("input[type=text]").val('');
        alert('Please enter amount first.');die();
    }

    var a2000 = document.getElementById('add2000i').value;
    var a1000 = 0;
    // var a1000 = document.getElementById('add1000i').value;
    var a500 = document.getElementById('add500i').value;
    var a200 = document.getElementById('add200i').value;
    var a100 = document.getElementById('add100i').value;
    var a50 = document.getElementById('add50i').value;
    var a20 = document.getElementById('add20i').value;
    var a10 = document.getElementById('add10i').value;
    var coin = document.getElementById('coini').value;

    if(a2000 ==""){
        a2000=0;
    }
    if(a1000 ==""){
        a1000=0;
    }
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
    var c2=0;
    c2=1000*a1000;
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
    
    document.getElementById('ad2000i').innerHTML = c1;
    // document.getElementById('ad1000i').innerHTML = c2;
    document.getElementById('ad500i').innerHTML = c3;
    document.getElementById('ad200i').innerHTML = c4;
    document.getElementById('ad100i').innerHTML = c5;
    document.getElementById('ad50i').innerHTML = c6;
    document.getElementById('ad20i').innerHTML = c7;
    document.getElementById('ad10i').innerHTML = c8;
    document.getElementById('coinsi').innerHTML = c9;
    var total=0;

    total=total+c1+c2+c3+c4+c5+c6+c7+c8;
    total=parseFloat(total)+parseFloat(c9);
    document.getElementById('calTotali').innerHTML = total.toFixed(2);
    document.getElementById('collectedIncomeTotal').value = total.toFixed(2);
    
    var final_amt=act_amount-total;
    if(final_amt>0){
        document.getElementById('income_exp_short_amt').innerHTML = "<span style='color:red'>-"+final_amt.toFixed(2)+"</span>";
        document.getElementById('income_exp_short_status').innerHTML = "<span style='color:red'>Short</span>";
    }else{
         document.getElementById('income_exp_short_amt').innerHTML = "<span style='color:blue'>"+final_amt.toFixed(2)+"</span>";
         document.getElementById('income_exp_short_status').innerHTML = "<span style='color:blue'>Excess</span>";
    }
    

}
</script>

<!-- for Expense money -->
<script type="text/javascript">
  function calExpenseMoney() {

    var act_amount=document.getElementById('cashAmtOutflow').value;
    if(act_amount===""){
        $("input[type=text]").val('');
        alert('Please enter amount first.');die();
    }

    var a2000 = document.getElementById('add2000e').value;
    // var a1000 = document.getElementById('add1000e').value;
    var a500 = document.getElementById('add500e').value;
    var a200 = document.getElementById('add200e').value;
    var a100 = document.getElementById('add100e').value;
    var a50 = document.getElementById('add50e').value;
    var a20 = document.getElementById('add20e').value;
    var a10 = document.getElementById('add10e').value;
    var coin = document.getElementById('coine').value;

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

    document.getElementById('ad2000e').innerHTML = c1;
    // document.getElementById('ad1000e').innerHTML = c2;
    document.getElementById('ad500e').innerHTML = c3;
    document.getElementById('ad200e').innerHTML = c4;
    document.getElementById('ad100e').innerHTML = c5;
    document.getElementById('ad50e').innerHTML = c6;
    document.getElementById('ad20e').innerHTML = c7;
    document.getElementById('ad10e').innerHTML = c8;
    document.getElementById('coinse').innerHTML = c9;
    var total=0;
    total=total+c1+c3+c4+c5+c6+c7+c8;
    total=parseFloat(total)+parseFloat(c9);
    document.getElementById('calTotale').innerHTML = total.toFixed(2);
    document.getElementById('collectedExpenseTotal').value = total.toFixed(2);

    if(act_amount ==""){
        act_amount=0;
        var final_amt=act_amount-total;
        document.getElementById('expense_exp_short_amt').innerHTML = final_amt.toFixed(2);
    }else{
        var final_amt=act_amount-total;
        if(final_amt>0){
            document.getElementById('expense_exp_short_amt').innerHTML = "<span style='color:red'>"+final_amt.toFixed(2)+"</span>";
            document.getElementById('expense_exp_short_status').innerHTML = "<span style='color:red'>Short</span>";
        }else{
             document.getElementById('expense_exp_short_amt').innerHTML = "<span style='color:blue'>"+final_amt.toFixed(2)+"</span>";
             document.getElementById('expense_exp_short_status').innerHTML = "<span style='color:blue'>Excess</span>";
        }
    }
}
</script>

<!-- for Expense money -->
<script type="text/javascript">
  function calNoteExchangeMoney() {
    var a2000 = document.getElementById('add2000exch').value;
    // var a1000 = document.getElementById('add1000e').value;
    var a500 = document.getElementById('add500exch').value;
    var a200 = document.getElementById('add200exch').value;
    var a100 = document.getElementById('add100exch').value;
    var a50 = document.getElementById('add50exch').value;
    var a20 = document.getElementById('add20exch').value;
    var a10 = document.getElementById('add10exch').value;
    var coin = document.getElementById('coinexch').value;

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

    document.getElementById('t2000exch').innerHTML = c1;
    // document.getElementById('ad1000e').innerHTML = c2;
    document.getElementById('t500exch').innerHTML = c3;
    document.getElementById('t200exch').innerHTML = c4;
    document.getElementById('t100exch').innerHTML = c5;
    document.getElementById('t50exch').innerHTML = c6;
    document.getElementById('t20exch').innerHTML = c7;
    document.getElementById('t10exch').innerHTML = c8;
    document.getElementById('tcoinsexch').innerHTML = c9;
    var total=0;
    total=total+c1+c3+c4+c5+c6+c7+c8;
    total=parseFloat(total)+parseFloat(c9);
    document.getElementById('calTotalexch').innerHTML = total.toFixed(2);
    document.getElementById('collectedExpenseTotalexch').value = total.toFixed(2);

    if(total ==""){
        total=0;
        var final_amt=total;
        document.getElementById('expense_exp_short_amt_exch').innerHTML = final_amt.toFixed(2);
    }else{
        var final_amt=total;
        if(final_amt>0){
            document.getElementById('calTotalexch').innerHTML = "<span style='color:red'>"+total.toFixed(2)+"</span>";
            document.getElementById('expense_exp_short_amt_exch').innerHTML = "<span style='color:red'>"+final_amt.toFixed(2)+"</span>";
            document.getElementById('expense_exp_short_status_exch').innerHTML = "<span style='color:red'>Difference</span>";
        }else{
            document.getElementById('calTotalexch').innerHTML = "<span style='color:red'>"+total.toFixed(2)+"</span>";
            document.getElementById('expense_exp_short_amt_exch').innerHTML = "<span style='color:blue'>"+final_amt.toFixed(2)+"</span>";
            document.getElementById('expense_exp_short_status_exch').innerHTML = "<span style='color:blue'>Difference</span>";
        }
    }
}
</script>


<!-- With - for negative values -->
<script>
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode !=45 && (charCode < 48 || charCode > 57) ) {
            return false;
        }
        return true;
    }
</script>

<script>
    function isNumberWithoutDash(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if ((charCode < 48 || charCode > 57) ) {
            return false;
        }
        return true;
    }
</script>


<script type="text/javascript">
    $(function(){
        $('#empName').change(function(){
            var empName = $("#empNameList option[value='" + $('#empName').val() + "']").attr('id');
            $('#empId').val(empName);
        });
    });
</script>

<script type="text/javascript">
    $(function(){
        $('#empNameOutflow').change(function(){
            var empName = $("#empNameOutflowList option[value='" + $('#empNameOutflow').val() + "']").attr('id');
            $('#empOutflowId').val(empName);
        });
    });
</script>

<script type="text/javascript">
    function submitDayBook(e){
       
      
      if (confirm('Do you want to submit this Close Day Book. ?')) {
          $("#closeDayBookDisableButton").attr("disabled", true);

          var short_amt = document.getElementById('short_amt').value;
          var add2000 = document.getElementById('addd2000').value;
          var add1000 = 0;
          var add500 = document.getElementById('addd500').value;
          var add200 = document.getElementById('addd200').value;
          var add100 = document.getElementById('addd100').value;
          var add50 = document.getElementById('addd50').value;
          var add20 = document.getElementById('addd20').value;
          var add10 = document.getElementById('addd10').value;
          var coin = document.getElementById('coin_s').value;
         
          var clos_bank_amt = Number(document.getElementById("clos_bank_amt").value);
          var collectedTotal = Number(document.getElementById("collectedTotal").value);

          if(collectedTotal>clos_bank_amt){
              var diff=collectedTotal-clos_bank_amt;

              $.ajax({
                  type: "POST",
                  url:"<?php echo site_url('manager/CashBookController/closeDayBookWithSuspenseIncomeTransaction');?>",
                  data:{"diff" : diff,"add2000" : add2000,"add1000" : add1000,"add500" : add500,"add200" : add200,"add100" : add100,"add50" : add50,"add20" : add20,"add10" : add10,"coins":coin,"collectedTotal":collectedTotal},
                  success: function (data) {
                      alert(data);
                      location.reload(); 
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

              // alert('Amount is more than remaining amount.'+diff);
              // die();
          }else{
              var shortAmount=clos_bank_amt-collectedTotal;
              
              if(shortAmount>0){
                  var msj= confirm('Amount is short and it will be debited to you.');
                  if (msj == false) { 
                    die();
                } else {
                    $.ajax({
                      type: "POST",
                      url:"<?php echo site_url('manager/CashBookController/closeDayBook');?>",
                      data:{"add2000" : add2000,"add1000" : add1000,"add500" : add500,"add200" : add200,"add100" : add100,"add50" : add50,"add20" : add20,"add10" : add10,"coins":coin,"collectedTotal":collectedTotal,"short_amt":short_amt},
                      success: function (data) {
                          alert(data);
                          location.reload(); 
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
                }
              }else{
                  $.ajax({
                      type: "POST",
                      url:"<?php echo site_url('manager/CashBookController/closeDayBook');?>",
                      data:{"add2000" : add2000,"add1000" : add1000,"add500" : add500,"add200" : add200,"add100" : add100,"add50" : add50,"add20" : add20,"add10" : add10,"coins":coin,"collectedTotal":collectedTotal},
                      success: function (data) {
                          alert(data);
                          location.reload(); 
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
              }
          }
      }else{
        $("#closeDayBookDisableButton").attr("disabled", false);
      }
        
    }
</script>

<script type="text/javascript">
  $('body').on('hidden.bs.modal', '.modal', function () {
    $(this).removeData('bs.modal');
  });
</script>

<script type="text/javascript">
    function rejecetCloseDayBook(){
        alert('Expenses are not approved by owner.');
    }
</script>

<script type="text/javascript">
    function rejecetCloseDayBookSubmit(){
        alert('No entry in Day book for submit.');
    }
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $("input:text").focus(function() { $(this).select(); } );
    });
</script>

<script type="text/javascript">
    function checkIncomeCorrectInputs(e){
        var res=true;
        var cashAmt=parseInt($('#cashAmt').val());
        var collectedExpenseTotal=parseInt($('#collectedIncomeTotal').val());
        
        if(collectedExpenseTotal != cashAmt){
            alert('Amount not match with notes amount.');
            return false;
        }else{
            var compName=$('#compName').val();
            var compId = $('#compNameList').find('option[value="'+compName+'"]').attr('id');

            var category=$('#category').val();
            var categoryId = $('#categoryList').find('option[value="'+category+'"]').attr('id');

            var empName=$('#empName').val();
            var empId = $('#empNameList').find('option[value="'+empName+'"]').attr('id');
            
            if(category==="Employee Credit"){
                if (typeof categoryId === "undefined") {
                    alert('Please select correct category.');
                    return false;
                }else if (typeof empId === "undefined") {
                    alert('Please select correct employee.');
                    return false;
                }
            }else{
                if (typeof compId === "undefined") {
                    alert('Please select correct company.');
                    return false;
                }else if (typeof categoryId === "undefined") {
                    alert('Please select correct category.');
                    return false;
                }else if (typeof empId === "undefined") {
                    alert('Please select correct employee.');
                    return false;
                }
            }
        }
        if (confirm('Do you want to submit this Income. ?')) {
            $("#incomeDisableButton").attr("disabled", true);
            return res;
        }else{
          $("#incomeDisableButton").attr("disabled", false);
          return false;
        }
    }
</script>

<script type="text/javascript">
    function checkExpenseCorrectInputs(e){

        var res=true;
        var clos_bank_amt=parseInt($('#expenceData_amt').val());
        var collectedTotal=parseInt($('#cashAmtOutflow').val());
        var collectedExpenseTotal=parseInt($('#collectedExpenseTotal').val());

        if(collectedExpenseTotal != collectedTotal){
            alert('Amount not match with notes amount.');
            return false;
        }else{
            if(collectedTotal>clos_bank_amt){
                alert('Amount is more than closing amount.');
                return false;
            }

            var compName=$('#compNameOutflow').val();
            var compId = $('#compNameOutflowList').find('option[value="'+compName+'"]').attr('id');

            var category=$('#categoryOutflow').val();
            var categoryId = $('#categoryOutflowList').find('option[value="'+category+'"]').attr('id');

            var empName=$('#empNameOutflow').val();
            var empId = $('#empNameOutflowList').find('option[value="'+empName+'"]').attr('id');
            
            if (category==="Employee Advances") {
                if (typeof categoryId === "undefined") {
                    alert('Please select correct category.');
                    return false;
                }else if (typeof empId === "undefined") {
                    alert('Please select correct employee.');
                    return false;
                }
            } else {
                if (typeof compId === "undefined") {
                    alert('Please select correct company.');
                    return false;
                }else if (typeof categoryId === "undefined") {
                    alert('Please select correct category.');
                    return false;
                }else if (typeof empId === "undefined") {
                    alert('Please select correct employee.');
                    return false;
                }
            }
        }
        if (confirm('Do you want to submit this Expense. ?')) {
            $("#expenseDisableButton").attr("disabled", true);
            return res;
        }else{
          $("#expenseDisableButton").attr("disabled", false);
            return false;
        } 
    }
</script>

<script type="text/javascript">
  function checkNoteExchangeInputs(e){

      var res=true;
      var collectedExpenseTotal=parseInt($('#collectedExpenseTotalexch').val());
      if(collectedExpenseTotal !=0){
          alert('Incorrect notes details.');
          return false;
      }
      
      if (confirm('Do you want to submit this notes exchange. ?')) {
          $("#exchDisButton").attr("disabled", true);
          return true;
      }else{
          $("#exchDisButton").attr("disabled", false);
          return false;
      }
  }
</script>

<script type="text/javascript">
  $(document).on("click","#inClose",function() {
      location.reload();
  });

  $(document).on("click","#expClose",function() {
      location.reload();
  });

  $(document).on("click","#dayBClose",function() {
      location.reload();
  });

  $(document).on("click","#bnkClose",function() {
      location.reload();
  });

  $(document).on("click","#noteExClose",function() {
      location.reload();
  });
</script>

<script type="text/javascript">
  function checkMainCashBookAmount(){
      var bankAmount = Number(document.getElementById("collectedDepositTotalmainCashBook").value);
      if(Number(bankAmount)>0){
        var availableAmount = Number(document.getElementById("totalOpeningAmtmainCashBook").value);
        
        
        if(availableAmount<bankAmount){
          alert('Amount is more than closing amount.');
          return false;
        }

        if (confirm('Do you want to transfer amount to Main cash Book. ?')) {
            $("#transferToMainCashBookButton").attr("disabled", true);
            return true;
        }else{
            $("#transferToMainCashBookButton").attr("disabled", true);
            return false;
        }
      }else{
          alert('Please enter notes details.');
          return false;
      }
  } 
  </script>

  
<!-- for Transfer to Main Cash Book -->
<script type="text/javascript">
  function calMainCashBookMoney() {
    var a2000 = document.getElementById('add2000mainCashBook').value;
    // var a1000 = document.getElementById('add1000').value;
    var a500 = document.getElementById('add500mainCashBook').value;
    var a200 = document.getElementById('add200mainCashBook').value;
    var a100 = document.getElementById('add100mainCashBook').value;
    var a50 = document.getElementById('add50mainCashBook').value;
    var a20 = document.getElementById('add20mainCashBook').value;
    var a10 = document.getElementById('add10mainCashBook').value;
    var coin = document.getElementById('coinmainCashBook').value;

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

    document.getElementById('t2000mainCashBook').innerHTML = c1;
    // document.getElementById('t1000').innerHTML = c2;
    document.getElementById('t500mainCashBook').innerHTML = c3;
    document.getElementById('t200mainCashBook').innerHTML = c4;
    document.getElementById('t100mainCashBook').innerHTML = c5;
    document.getElementById('t50mainCashBook').innerHTML = c6;
    document.getElementById('t20mainCashBook').innerHTML = c7;
    document.getElementById('t10mainCashBook').innerHTML = c8;
    document.getElementById('tcoinsmainCashBook').innerHTML = c9;
    var total=0;
    total=total+c1+c3+c4+c5+c6+c7+c8;
    // total=total+c1+c2+c3+c4+c5+c6+c7+c8;
   
    total=parseFloat(total)+parseFloat(c9);
    document.getElementById('calTotalmainCashBook').innerHTML = total.toFixed(2);
    document.getElementById('tcalTotalmainCashBook').innerHTML = total.toFixed(2);
    document.getElementById('collectedDepositTotalmainCashBook').value = total.toFixed(2);

}
</script>

<script type="text/javascript">
    function minmax(value, min, max) 
    {   
        if(parseInt(value) ==0){
            $('#err-data').html('');
            return value; 
        }else if(parseInt(value) < min || isNaN(parseInt(value))) {
            $('#err-data').html('');
            return value; 
        } else if ((parseInt(value) > min && parseInt(value) < max)){ 
            $('#err-data').html('');
            return value; 
        } else if (parseInt(value) == max) {
            $('#err-data').html('');
            return value;
        } else {
            $('#err-data').html('Notes can not more than available notes');
            return '';
        }
    }
</script>