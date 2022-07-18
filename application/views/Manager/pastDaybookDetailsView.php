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
          width: 350px; /* New width for small modal */
        }
    }

    @media screen and (min-width: 1100px) {
        .modal-lg {
          width: 1100px; /* New width for large modal */
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

    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br>
    <section class="col-md-12 box">
        <div class="container-fluid">
            <!--<div class="block-header">-->
            <!--    <h2>-->
            <!--                  Cash Book(Income/Expense)-->
            <!--                </h2>-->
            <!--</div>-->
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                              Past Day Book Details
                            </h2>
                             <h2>
                                 <br>
                                 <p align="center">Day Book Name: <?php echo $daybookName;?></p>
                                
                                 <br>
                                <table style="font-size: 12px" class="table table-bordered table-striped table-hover" data-page-length='100'>
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
                                            <td align="right"><?php echo number_format($currentOpening); ?></td>
                                            <td align="right"><?php echo number_format(($totalInflow)); ?></td>
                                            <td align="right"><?php echo number_format(($totalOutflow)); ?></td>
                                            <td align="right"><?php echo number_format(abs($diffContraEntry)); ?></td>
                                            <td align="right"><?php echo number_format($totalBankDeposit); ?></td>
                                            <td align="right"><?php echo number_format($close); ?></td>
                                            
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
                                <table style="font-size: 12px" class="table header-fixed table-bordered table-striped table-hover dataTable js-exportable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Allocation No.</th>
                                            <th>Nature</th>
                                            <th>Employee</th>
                                            <th>Reference</th>
                                            <th class="text-right">Inflow</th>
                                            <th class="text-right">Outflow</th>
                                            <th class="text-right">Balance</th>
                                        </tr>
                                        
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Date</th>
                                            <th>Allocation No.</th>
                                            <th>Nature</th>
                                            <th>Employee</th>
                                            <th>Reference</th>
                                            <th class="text-right">Inflow</th>
                                            <th class="text-right">Outflow</th>
                                            <th class="text-right">Balance</th>
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
                                            <td class="text-right"><?php echo number_format($currentOpening); ?></td>
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
                                                    <td><?php echo '<span class="logo_prov">Rejected</span>'.date("d-M-Y h:i a", strtotime($dt['date']));?></td>
                                                <?php }else{ ?>
                                                    <td><?php echo date("d-M-Y h:i a", strtotime($dt['date']));?></td>
                                                <?php  } ?>
                                                <!-- <td><?php echo date("d-M-Y h:i a", strtotime($dt['date']));?></td> -->
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
                                                <td class="text-right" style="color:blue;"><?php echo number_format($totalCollection);?></td>

                                                <td class="text-right"></td>
                                                
                                                <td class="text-right"><?php echo number_format($dt['openCloseBalance']);?></td>
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
                                           <tr>
                                               <?php if($dt['expenseOwnerApproval']==2 || $dt['bankDepositApproval']==2){ ?>
                                                    <td><?php echo '<span class="logo_prov">Rejected</span>'.date("d-M-Y h:i a", strtotime($dt['date']));?></td>
                                                <?php }else{ ?>
                                                    <td><?php echo date("d-M-Y h:i a", strtotime($dt['date']));?></td>
                                                <?php  } ?>
                                                <!-- <td><?php echo date("d-M-Y h:i a", strtotime($dt['date']));?></td> -->
                                                <td><?php if($allocationCode >0){echo $allocationCode; } ?></td>
                                                
                                                <td><?php echo $dt['nature'];?></td>
                                                <td><?php echo $ename;?></td>
                                                <td><?php echo $dt['narration'];?></td>
                                                <?php if($dt['nature']==="Bank Deposit"){?>
                                                     <td  class="text-right"></td>
                                                    <td class="text-right" style="color:#4DD608;"><?php echo number_format($dt['amount']);?></td>
                                                 <?php } if($dt['inoutStatus']==="Inflow"){ ?>
                                                    <td class="text-right" style="color:blue;"><?php echo number_format($dt['amount']);?></td>
                                                    <td class="text-right"></td>
                                                 <?php } if($dt['nature'] !="Bank Deposit" && $dt['inoutStatus']==="Outflow"){ ?>

                                                    <td class="text-right"></td>
                                                    <td class="text-right" style="color:red;"><?php echo number_format($dt['amount']);?></td>
                                                 <?php } ?>
                                                
                                                <td class="text-right"><?php echo number_format($dt['openCloseBalance']);?></td>
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

<!-- Add Income -->
<div class="container">
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
           <center> <h3 class="modal-title">Add Income</h3>  </center>
          </div>
          <div class="modal-body">
        <form method="post" role="form" action="<?php echo site_url('manager/CashBookController/insertIncomeInflow');?>"> 
                               
                                <div class="row clearfix">
                                    <div class="col-md-12">
                                  
                                    <div class="col-md-4">
                                    <p>
                                       <b>Company </b>
                                    </p>
                                    
                                    <input type="text" autocomplete="off" placeholder="select company" list="compNameList" id="compName" name="compName" class="form-control" required> 
                                    <datalist id="compNameList">
                                    <?php foreach ($companyDetails as $req_item): ?>
                                        <option id="<?php echo $req_item['id'] ?>" value="<?php echo $req_item['name'] ?>" />
                                      <?php endforeach ?> 
                                    </datalist> 
                                  </div>

                                     <div class="col-md-4" id="category">
                                    <p>
                                     <b>Category </b>
                                    </p>
                                   
                                  <input type="text" autocomplete="off" placeholder="select category" list="categoryList" id="category" name="category" class="form-control" required> 
                                      <datalist id="categoryList">
                                        <?php foreach($cat_income as $in){ ?>

                                            <option><?php echo $in['categoryName']; ?></option>
                                        <?php } ?>
                                      </datalist> 
                                  </div> 
                                  <div class="col-md-4">
                                    <p>
                                       <b>Employee  </b>
                                    </p>
                                   
                                    <input type="text" autocomplete="off" placeholder="select employee" list="empNameList" id="empName" name="empName" class="form-control" required> 
                                    <datalist id="empNameList">
                                    <?php foreach ($emp as $req_item): ?>
                                        <option id="<?php echo $req_item['id'] ?>" value="<?php echo $req_item['name'] ?>" />
                                      <?php endforeach ?> 
                                    </datalist> 
                                    <input type="hidden" id="empId" name="empId" class="form-control"> 
                                  </div> 
                              </div>
                                   
                                   <div class="col-md-12">
                                   
                                    <div class="col-md-4">                                       
                                        <p>
                                          <b> Amount </b>
                                        </p>
                                         <div class="form-line">
                                                <input type="text" autocomplete="off" id="cashAmt" placeholder="amount" name="cashAmt" class="form-control" required>           
                                            </div>  
                                            <div style="color:red;" id="sr_qty"></div>          
                                    </div>
                                    <div class="col-md-8">                                       
                                        <p>
                                          <b> Narration </b>
                                        </p>
                                         <div class="form-line">
                                                <input type="text" autocomplete="off" placeholder="narration" id="narration" name="narration" class="form-control" required>           
                                            </div>  
                                            <div style="color:red;" id="sr_qty"></div>          
                                    </div>
                                </div>
                               
                             <div class="row clearfix">
                                <div class="col-md-12">
                                   
                                        <center>                                               
                                                <button type="submit" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name">
                                                    Save
                                                    </span>
                                                </button> 
                                                
                                                    <button data-dismiss="modal" type="button" class="btn btn-primary m-t-15 waves-effect">
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
  <div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
           <center> <h3 class="modal-title">Add Expense</h3> </center>
          </div>
          <div class="modal-body">
        <form method="post" role="form" action="<?php echo site_url('manager/CashBookController/insertIncomeOutflow');?>"> 
                                <div class="row clearfix">
                                    <div class="col-md-12">
                                    <div class="col-md-4">
                                    <p>
                                       <b>Company </b>
                                    </p>
                                    
                                    <input type="text" autocomplete="off" placeholder="select company" list="compNameOutflowList" id="compNameOutflow" name="compNameOutflow" class="form-control" required> 
                                    <datalist id="compNameOutflowList">
                                    <?php foreach ($companyDetails as $req_item): ?>
                                        <option id="<?php echo $req_item['id'] ?>" value="<?php echo $req_item['name'] ?>" />
                                      <?php endforeach ?> 
                                    </datalist> 
                                  </div>

                                    <div class="col-md-4">
                                    <p>
                                     <b>Category </b>
                                    </p>
                                  

                                  <input type="text" autocomplete="off" placeholder="select category" list="categoryOutflowList" id="categoryOutflow" name="categoryOutflow" class="form-control" required> 
                                          <datalist id="categoryOutflowList">
                                            <?php foreach($cat_expense as $in){ ?>
                                                    <option><?php echo $in['categoryName']; ?></option>
                                            <?php } ?>
                                 
                                          </datalist> 
                                  </div> 
                                  <div class="col-md-4">
                                    <p>
                                       <b>Employee Name </b>
                                    </p>
                                    
                                    <input type="text" autocomplete="off" placeholder="select employee" list="empNameOutflowList" id="empNameOutflow" name="empNameOutflow" class="form-control" required> 
                                      <datalist id="empNameOutflowList">
                                          <?php foreach ($emp as $req_item): ?>
                                            <option id="<?php echo $req_item['id'] ?>" value="<?php echo $req_item['name'] ?>" />
                                          <?php endforeach ?> 
                                      </datalist>
                                      <input type="hidden" id="empOutflowId" name="empOutflowId" class="form-control"> 
                                  </div>
                                  </div>   
                                   

                                   <div class="col-md-12">
                                    
                                    <div class="col-md-4">                                       
                                        <p>
                                          <b> Amount </b>
                                        </p>
                                         <div class="form-line">
                                                <input type="text" autocomplete="off" placeholder="amount" id="cashAmtOutflow" name="cashAmtOutflow" class="form-control" required>           
                                            </div>  
                                            <div style="color:red;" id="sr_qty"></div>          
                                    </div>
                                    
                                    <div class="col-md-8">                                       
                                        <p>
                                          <b> Narration </b>
                                        </p>
                                         <div class="form-line">
                                                <input type="text" autocomplete="off" placeholder="narration" id="narrationOutflow" name="narrationOutflow" class="form-control" required>           
                                            </div>  
                                            <div style="color:red;" id="sr_qty"></div>          
                                    </div>
                                </div>
                                </div>
                                <br>
                              
                                <div class="row clearfix">
                                <div class="col-md-12">
                                    
                                        <center>                                               
                                                <button type="submit" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name">
                                                    Save
                                                    </span>
                                                </button> 
                                              
                                                    <button data-dismiss="modal" type="button" class="btn btn-primary m-t-15 waves-effect">
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
  <div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <center><h3 class="modal-title">Add Bank Deposit</h3></center>
          </div>
          <div class="modal-body">
         <form method="post" role="form" onsubmit="return checkBankDepositAmount();" action="<?php echo site_url('manager/CashBookController/addBankDeposits');?>"> 
                                <div class="row clearfix">
                                  <div class="col-md-12">
                                    <table style="font-size: 12px" class="table table-bordered table-striped table-hover" data-page-length='100'>
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Opening Balance</th>
                                                <th>Income</th>
                                                <th>Expence</th>
                                                <th>Bank Deposit</th>
                                                <th>Closing Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                             <tr>
                                                <td><?php echo date('d-m-Y');?></td>
                                                <td  align="right"><?php echo number_format($closingBal,2); ?></td>
                                                <td align="right"><?php echo number_format(($totalInflow+($totalMarketExpense)),2); ?></td>
                                                <td align="right"><?php echo number_format(($totalOutflow+($totalMarketExpense)),2); ?></td>
                                                <td align="right"><?php echo number_format($totalBankDeposit,2); ?></td>
                                                <td align="right"><?php echo number_format($close,2); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                  <div class="col-md-12">
                                    <table class="table-striped table-bordered" style="font-size: 13px;">
                                            <thead>
                                                <tr>
                                                    <th class="text-xs-center" colspan="6"><center>Notes</center></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>2000 &nbsp;&nbsp;&nbsp;&nbsp;X</td>
                                                        <td>
                                                        <input onkeypress="return isNumber(event)" onkeyup="calMoney();" type="text" name="add2000" id="add2000"autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td width="10%" class="text-xs-right">
                                                        <span id="ad2000"></span>
                                                    </td>
                                                
                                                    <td>1000 &nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                                        <td>
                                                        <input onkeypress="return isNumber(event)" onkeyup="calMoney();" type="text" name="add1000" id="add1000"autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td width="10%" class="text-xs-right">
                                                        <span id="ad1000"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>500 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                                        <td>
                                                        <input onkeypress="return isNumber(event)" onkeyup="calMoney();" type="text" name="add500" id="add500"autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td width="10%" class="text-xs-right">
                                                        <span id="ad500"></span>
                                                    </td>
                                              
                                                    <td>200 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                                        <td>
                                                        <input onkeypress="return isNumber(event)" onkeyup="calMoney();" type="text" name="add200" id="add200"autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td width="10%" class="text-xs-right">
                                                        <span id="ad200"></span>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>100 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                                        <td>
                                                        <input onkeypress="return isNumber(event)" onkeyup="calMoney();;" type="text" name="add100" id="add100"autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td width="10%" class="text-xs-right">
                                                        <span id="ad100"></span>
                                                    </td>
                                               
                                                    <td>50 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                                        <td>
                                                        <input onkeypress="return isNumber(event)" onkeyup="calMoney();" type="text" name="add50" id="add50"autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td width="10%" class="text-xs-right">
                                                        <span id="ad50"></span>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>20 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                                        <td>
                                                        <input onkeypress="return isNumber(event)" onkeyup="calMoney();" type="text" name="add20" id="add20"autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td width="10%" class="text-xs-right">
                                                        <span id="ad20"></span>
                                                    </td>
                                                
                                                    <td>10 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                                        <td>
                                                        <input onkeypress="return isNumber(event)" onkeyup="calMoney();" type="text" name="add10" id="add10"autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td width="10%" class="text-xs-right">
                                                        <span id="ad10"></span>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>Coins &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                        <td>
                                                        <input onkeypress="return isNumber(event)" onkeyup="calMoney();" type="text" name="coin" id="coin" autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td width="10%" class="text-xs-right">
                                                        <span id="coins"></span>
                                                    </td>
                                                
                                                    <td>Total &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                        
                                                    <td colspan="2" class="text-xs-right">
                                                        <span id="calTotal"></span>
                                                    </td>
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="row clearfix">
                                <div class="col-md-12">
                                    
                                        <center>                                               
                                                <button type="submit" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name">
                                                    Save
                                                    </span>
                                                </button> 
                                                
                                                    <button data-dismiss="modal" type="button" class="btn btn-primary m-t-15 waves-effect">
                                                        <i class="material-icons">cancel</i> 
                                                        <span class="icon-name">
                                                        cancel
                                                        </span>
                                                    </button>
                                                <!-- </a>      -->
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
  <div class="modal fade" id="closeDayModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <center><h3 class="modal-title">Close Day Book</h3></center>
          </div>
          <div class="modal-body">
            <div class="col-md-12">
            <table style="font-size: 12px" class="table table-bordered table-striped table-hover" data-page-length='100'>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th align="right">Opening Balance</th>
                        <th align="right">Income</th>
                        <th align="right">Expence</th>
                        <th align="right">Bank Deposit</th>
                        <th align="right">Closing Balance</th>
                    </tr>
                </thead>
                <tbody>
                     <tr>
                        <td><?php echo date('d-m-Y');?></td>
                        <td  align="right"><?php echo number_format($closingBal,2); ?></td>
                        <td align="right"><?php echo number_format(($totalInflow+($totalMarketExpense)),2); ?></td>
                        <td align="right"><?php echo number_format(($totalOutflow+($totalMarketExpense)),2); ?></td>
                        <td align="right"><?php echo number_format($totalBankDeposit,2); ?></td>
                        <td align="right"><?php echo number_format($close,2); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
         <!-- <form method="post" role="form" onsubmit="return checkRemAmount();" action="<?php echo site_url('manager/CashBookController/closeDayBook');?>">  -->
                    <div class="row clearfix">
                     
                        <div class="col-md-12">
                            <div class="col-md-8">
                        <table class="table-striped table-bordered" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th class="text-xs-center" colspan="12"><center>Notes</center></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>2000 &nbsp;&nbsp;&nbsp;&nbsp;X</td>
                                            <td>
                                            <input onkeypress="return isNumber(event)" onkeyup="calsMoney();" type="text" name="addd2000" id="addd2000"autocomplete="off" class="form-control">
                                            
                                        </td>
                                        <td width="10%" class="text-xs-right">
                                            <span id="aad2000"></span>
                                        </td>
                                  
                                        <td>1000 &nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                            <td>
                                            <input onkeypress="return isNumber(event)" onkeyup="calsMoney();" type="text" name="addd1000" id="addd1000"autocomplete="off" class="form-control">
                                            
                                        </td>
                                        <td width="10%" class="text-xs-right">
                                            <span id="aad1000"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>500 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                            <td>
                                            <input onkeypress="return isNumber(event)" onkeyup="calsMoney();" type="text" name="addd500" id="addd500"autocomplete="off" class="form-control">
                                            
                                        </td>
                                        <td width="10%" class="text-xs-right">
                                            <span id="aad500"></span>
                                        </td>
                                  
                                        <td>200 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                            <td>
                                            <input onkeypress="return isNumber(event)" onkeyup="calsMoney();" type="text" name="addd200" id="addd200"autocomplete="off" class="form-control">
                                            
                                        </td>
                                        <td width="10%" class="text-xs-right">
                                            <span id="aad200"></span>
                                        </td>
                                    </tr>
                                     <tr>
                                        <td>100 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                            <td>
                                            <input onkeypress="return isNumber(event)" onkeyup="calsMoney();;" type="text" name="addd100" id="addd100"autocomplete="off" class="form-control">
                                            
                                        </td>
                                        <td width="10%" class="text-xs-right">
                                            <span id="aad100"></span>
                                        </td>
                                   
                                        <td>50 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                            <td>
                                            <input onkeypress="return isNumber(event)" onkeyup="calsMoney();" type="text" name="addd50" id="addd50"autocomplete="off" class="form-control">
                                            
                                        </td>
                                        <td width="10%" class="text-xs-right">
                                            <span id="aad50"></span>
                                        </td>
                                    </tr>
                                     <tr>
                                        <td>20 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                            <td>
                                            <input onkeypress="return isNumber(event)" onkeyup="calsMoney();" type="text" name="addd20" id="addd20"autocomplete="off" class="form-control">
                                            
                                        </td>
                                        <td width="10%" class="text-xs-right">
                                            <span id="aad20"></span>
                                        </td>
                                   
                                        <td>10 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                            <td>
                                            <input onkeypress="return isNumber(event)" onkeyup="calsMoney();" type="text" name="addd10" id="addd10"autocomplete="off" class="form-control">
                                            
                                        </td>
                                        <td width="10%" class="text-xs-right">
                                            <span id="aad10"></span>
                                        </td>
                                    </tr>
                                     <tr>
                                        <td>Coins &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                            <td>
                                            <input onkeypress="return isNumber(event)" onkeyup="calsMoney();" type="text" name="coin_s" id="coin_s" autocomplete="off" class="form-control">
                                            
                                        </td>
                                        <td width="10%" class="text-xs-right">
                                            <span id="coinss"></span>
                                        </td>
                                    
                                        <td>Total &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                            
                                        <td colspan="2" class="text-xs-right">
                                            <span id="calsTotal"></span>
                                            <input onkeypress="return isNumber(event)" onkeyup="calsMoney();" type="hidden" name="collectedTotal" id="collectedTotal" autocomplete="off" class="form-control">

                                            <input onkeypress="return isNumber(event)" onkeyup="calsMoney();" type="hidden" value="<?php echo $close; ?>" name="clos_bank_amt" id="clos_bank_amt" autocomplete="off" class="form-control">
                                        </td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-4">
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
                                            <td>Closing Balance</td>
                                            <td align="right" id="closingCashInfo"><?php echo number_format($close,2); ?></td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Excess</td>
                                            <td align="right" id="ExcessCash"></td>
                                        </tr>

                                        <tr>
                                            <td>Short</td>
                                            <td align="right" id="ShortCash"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                    <div class="col-md-12">
                        
                            <center>                                               
                                    <button onclick="submitDayBook();" type="button" class="btn btn-primary m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name">
                                        Save
                                        </span>
                                    </button> 
                                    
                                        <button data-dismiss="modal" type="button" class="btn btn-primary m-t-15 waves-effect">
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

<?php $this->load->view('/layouts/footerDataTable'); ?>


   <script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>
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
          var bankAmount = Number(document.getElementById("calTotal").innerHTML);
          var availableAmount = Number(document.getElementById("availableAmount").innerHTML);
          if(availableAmount<bankAmount){
            alert('Amount is more than remaining amount.');
            return false;
          }else{
            return true;
          }
      }

      function checkRemAmount(){
          var clos_bank_amt = Number(document.getElementById("clos_bank_amt").value);
          var collectedTotal = Number(document.getElementById("collectedTotal").value);
          if(collectedTotal>clos_bank_amt){
            alert('Amount is more than remaining amount.');
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

<script type="text/javascript">
    
    $('#myModal').on('hidden.bs.modal', function () {
      $(this)
          .find("input:not([type=hidden]),textarea,select")
          .val('')
          .end()
          .find("input[type=checkbox], input[type=radio]")
          .prop("checked", "")
          .end();
    });

    $('#myModal1').on('hidden.bs.modal', function () {
      $(this)
          .find("input:not([type=hidden]),textarea,select")
          .val('')
          .end()
          .find("input[type=checkbox], input[type=radio]")
          .prop("checked", "")
          .end();
    });

$(document).on('show.bs.modal','#myModal2', function () {
    // $('#myModal2').on('hidden.bs.modal', function () {
      $(this)
          .find("input:not([type=hidden]),textarea,select")
          .val('')
          .end()
          .find("input[type=checkbox], input[type=radio]")
          .prop("checked", "")
          .end();

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

    $('#closeDayModal').on('hidden.bs.modal', function () {
      $(this)
          .find("input:not([type=hidden]),textarea,select")
          .val('')
          .end()
          .find("input[type=checkbox], input[type=radio]")
          .prop("checked", "")
          .end();
    });


</script>

<script type="text/javascript">
  function calMoney() {
    var a2000 = document.getElementById('add2000').value;
    var a1000 = document.getElementById('add1000').value;
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

    document.getElementById('ad2000').innerHTML = c1;
    document.getElementById('ad1000').innerHTML = c2;
    document.getElementById('ad500').innerHTML = c3;
    document.getElementById('ad200').innerHTML = c4;
    document.getElementById('ad100').innerHTML = c5;
    document.getElementById('ad50').innerHTML = c6;
    document.getElementById('ad20').innerHTML = c7;
    document.getElementById('ad10').innerHTML = c8;
    document.getElementById('coins').innerHTML = c9;
    var total=0;
    total=total+c1+c2+c3+c4+c5+c6+c7+c8;
    total=parseFloat(total)+parseFloat(c9);
    document.getElementById('calTotal').innerHTML = total.toFixed(2);;
    document.getElementById('calTotal1').innerHTML = total.toFixed(2);;

  }
</script>

<script type="text/javascript">
  function calsMoney() {
    var a2000 = document.getElementById('addd2000').value;
    var a1000 = document.getElementById('addd1000').value;
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

    document.getElementById('aad2000').innerHTML = c1;
    document.getElementById('aad1000').innerHTML = c2;
    document.getElementById('aad500').innerHTML = c3;
    document.getElementById('aad200').innerHTML = c4;
    document.getElementById('aad100').innerHTML = c5;
    document.getElementById('aad50').innerHTML = c6;
    document.getElementById('aad20').innerHTML = c7;
    document.getElementById('aad10').innerHTML = c8;
    document.getElementById('coinss').innerHTML = c9;



    var total=0;

    total=total+c1+c2+c3+c4+c5+c6+c7+c8;
    total=parseFloat(total)+parseFloat(c9);
    document.getElementById('calsTotal').innerHTML = total.toFixed(2);
    document.getElementById('collectedTotal').value = total.toFixed(2);

    var clos_bank_amt = Number(document.getElementById("clos_bank_amt").value);
    var collectedTotal = Number(document.getElementById("collectedTotal").value);
    var excessShort=clos_bank_amt-collectedTotal;
    if(excessShort>0){
        document.getElementById('ShortCash').innerText=excessShort.toFixed(2);
        document.getElementById('ExcessCash').innerText="";
    }else{
        document.getElementById('ExcessCash').innerText=excessShort.toFixed(2);
        document.getElementById('ShortCash').innerText="";
    }
   
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
    function submitDayBook(){
        var add2000 = document.getElementById('addd2000').value;
        var add1000 = document.getElementById('addd1000').value;
        var add500 = document.getElementById('addd500').value;
        var add200 = document.getElementById('addd200').value;
        var add100 = document.getElementById('addd100').value;
        var add50 = document.getElementById('addd50').value;
        var add20 = document.getElementById('addd20').value;
        var add10 = document.getElementById('addd10').value;
        var coin = document.getElementById('coins').value;

        var clos_bank_amt = Number(document.getElementById("clos_bank_amt").value);
        var collectedTotal = Number(document.getElementById("collectedTotal").value);

        if(collectedTotal>clos_bank_amt){
            alert('Amount is more than remaining amount.');
            die();
        }

        $.ajax({
            type: "POST",
            url:"<?php echo site_url('manager/CashBookController/closeDayBook');?>",
            data:{"add2000" : add2000,"add1000" : add1000,"add500" : add500,"add200" : add200,"add100" : add100,"add50" : add50,"add20" : add20,"add10" : add10,"coins":coin,"collectedTotal":collectedTotal},
            success: function (data) {
                alert(data);
                 location.reload(); 
            }  
        });
    }
</script>
