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

    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
           
            <!-- Masked Input -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                              Add New Bills
                            </h2>
                       
                        </div>
                        
                        <div class="body">
                            <p id="res"></p>
                            <div class="row clearfix">
                            <div class="demo-masked-input">
                                <div class="col-md-12 mb-0 cust-tbl"> 
                                    <div class="col-md-3" id="empName">
                                  
                                           <b>Company </b>
                                        
                                        <select id="cmpName" name="cmpName" tabindex="1" class="form-control">
                                          <option>--Select Company---</option>
                                          <?php foreach ($company as $req_item): ?>
                                            <option value="<?php echo $req_item['name'] ?>"><?php echo $req_item['name'] ?></option>
                                          <?php endforeach ?> 
                                        </select>
                                    </div> 

                                    <div class="col-md-2">
                                        <b>Bill Number</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" onblur="checkBillNo(this);" tabindex="2" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" id="billNo" name="billNo" class="form-control date" placeholder="Enter bill number" required>
                                            </div>
                                        </div>
                                        <p id="billNo_Id"></p>
                                        <p id="billSr_Id"></p>
                                    </div>

                                    <div class="col-md-3">
                                        <b>Retailer Name</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" autocomplete="off" tabindex="3" id="retailerName" name="retailerName" class="form-control date" placeholder="Enter retailer name" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <b>Net Amount</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" autocomplete="off" tabindex="4" id="netAmount" name="netAmount" class="form-control" onkeypress="return numbersonly(this, event);" placeholder="Enter net amount" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2 m-t-15 text-right noPadding">
                                    <a id="prDetails" href="javascript:void()" tabindex="5" data-toggle="modal" data-target="#processModal"><button class="btn btn-xs btnStyle btn-primary waves-effect"><i class="material-icons">save</i> Process</button></a>

                                      <!--   <button onclick="sumSerial();" class="btn btn-primary m-t-15 waves-effect">
                                            <i class="material-icons">save</i> 
                                            <span class="icon-name">
                                             Process
                                            </span>
                                        </button> -->
                                       <a tabindex="6" href="<?php echo site_url('DashbordController');?>">
                                            <button type="button" class="btn btn-danger btn-sm waves-effect">
                                                <i class="material-icons">cancel</i> 
                                                <span class="icon-name"> Cancel</span>
                                            </button>
                                        </a> 
                                    </div>
                                </div> 

                                <div class="col-md-12">
                                <div class="table-responsive">
                                <table class="table table-bordered dataTable js-exportable cust-tbl" data-page-length='100'>
                                    <thead>
                                        <tr class="gray">
                                            <th>No</th>
                                            <th> Bill No </th>
                                            <th> Bill Date  </th>
                                            <th> Retailer </th>
                                            <th> Bill Amount </th>
                                            <th> SR </th>
                                            <th> Collection </th>
                                            <th> Pending  </th>
                                            <th> Employee </th>
                                            <th> Status </th>
                                            <th> Action </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <?php
                                            $no=0;
                                            foreach ($adhocBills as $data) 
                                            {
                                               $no++; 
                                              
                                              $retailerCode=$this->AllocationByManagerModel->loadRetailer($data['retailerCode']);
                                              $allocations=$this->AllocationByManagerModel->getAllocationDetailsByBill('bills',$data['id']);
                                              $allocationsHistory=$this->AllocationByManagerModel->getAllocationDetailsByBillHistory('bills',$data['id']);
                                              $officeAllocations=$this->AllocationByManagerModel->getOfficeAllocationDetailsByBill('bills',$data['id']);
                                              $officeAllocationsHistory=$this->AllocationByManagerModel->getOfficeAllocationDetailsByBillHistory('bills',$data['id']);

                                              $billPayment=$this->AllocationByManagerModel->getBillPayment('bills',$data['id']);

                                              $empName="";
                                              if(!empty($billPayment)){
                                                $empName=$billPayment[0]['empName'];
                                              }
                                              // print_r($billPayment);

                                              $dt=date_create($data['date']);
                                              $createdDate = date_format($dt,'d-M-Y');
                                              $edt=date_format($dt,'d-M-Y');
                                             
                                            ?>

                                          
                                             <tr <?php if($data['isAllocated']==1){ ?> style="background-color: #dcd6d5" <?php } ?> >
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $data['billNo']; ?></td>
                                                <td><?php echo $createdDate; ?></td>
                                                <td><?php echo $data['retailerName']; ?></td>
                                                <td align="right"><?php echo number_format($data['netAmount']); ?></td>
                                                 <td align="right"><?php echo number_format($data['SRAmt']); ?></td>
                                                 <td align="right"><?php echo number_format($data['receivedAmt']); ?></td>
                                                <td align="right"><?php echo number_format($data['pendingAmt']); ?></td>
                                            <?php 
                                                    if($data['isDirectDeliveryBill']==1){
                                            ?>
                                                        <td><?php echo $data['deliveryEmpName']; ?></td>
                                            <?php
                                                    }else{
                                            ?>
                                                      <td><?php echo $empName; ?></td>
                                            <?php
                                                    }
                                            ?>

                                                
                                                <td>
                                                <?php 

                                                    if($data['isAllocated']==1){
                                                        if(!empty($allocations)){
                                                            echo "<span style='color:blue'>Allocated in : ".$allocations[0]['allocationCode'].'</span>';
                                                        }

                                                        if(!empty($officeAllocations)){
                                                            echo "<span style='color:blue'>Allocated in : ".$officeAllocations[0]['allocationCode'].'</span>';
                                                        }
                                                    }else{
                                                        if($data['pendingAmt']==0){
                                                            echo "<span style='color:green'> Cleared</span>";
                                                        }else if($data['isDirectDeliveryBill']==1){
                                                            echo "<span style='color:green'> Direct Delivery</span>";
                                                        }else{
                                                          if(!empty($allocationsHistory) || !empty($officeAllocationsHistory)){
                                                            if(!empty($allocationsHistory)){
                                                              echo "<span style='color:green'>Earlier Allocated </span>";
                                                            }

                                                            if(!empty($officeAllocationsHistory)){
                                                              echo "<span style='color:green'>Already Allocated in : ".$officeAllocationsHistory[0]['allocationCode'].'</span>';
                                                            }
                                                          }else{
                                                              if($data['pendingAmt']==$data['netAmount']){
                                                                 echo "<span style='color:red'> Unaccounted</span>";
                                                              }else{
                                                                 echo "<span style='color:green'> Accounted</span>";
                                                              }
                                                              
                                                          }
                                                        }

                                                        
                                                ?>
                                                       
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                  <?php if($data['isAllocated']==0){
                                                      if($data['pendingAmt']==0){

                                                      }else{

                                                   ?>

                                    <a id="prDetailsForAll" href="javascript:void()" data-id="<?php echo $data['id']; ?>" data-salesman="<?php echo $data['salesman']; ?>" data-billDate="<?php echo $edt; ?>" data-credAdj="<?php echo $data['creditAdjustment']; ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>" data-gst="<?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?>" data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-route="<?php echo $data['routeName']; ?>" data-toggle="modal" data-target="#processModalForAll" ><button class="btn btn-xs text-white process-btn" data-toggle="tooltip" data-placement="bottom" title="Process"><i class="material-icons">touch_app</i></button></a>

                                    <!-- <a id="prBtnDetails" href="javascript:void()" data-id="<?php echo $data['id']; ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>" data-gst="<?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?>" data-code="<?php echo $data['retailerCode']; ?>" data-salesman="<?php echo $data['salesman']; ?>" data-route="<?php echo $data['routeName']; ?>" data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-toggle="modal" data-target="#processBtnModal"><button class="btn btn-xs btn-primary margin"><i class="material-icons">touch_app</i></button></a> -->

                                    <?php } ?>
                                                    
                                    <a href="<?php echo site_url('AdHocController/billHistoryInfo/'.$data['id']); ?>" class="btn btn-xs  history-btn" data-toggle="tooltip" data-placement="bottom" title="View History"><i class="material-icons">info</i></a>
                                    <a href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs viewBill-btn" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                                               
                                    <?php if(empty($allocationsHistory) && ($data['pendingAmt'] > 0)){ ?>

                                    <a id="newTrans" href='<?php echo base_url("index.php/commanTransactions/AllocationByProcessController/newProcessAllocation/".$data['id']."/".$data['compName']) ?>'><button class="btn btn-xs add-btn margin"><i class="material-icons">add</i></button></a>

                                                  <?php } 
                                                    }
                                                  ?>
                                                  
                                                </td>
                                              </tr>
                                                <?php
                                             
                                            }
                                            ?> 
                                    </tbody>
                                    <tfoot>
                                        <tr class="gray">
                                            <th> No</th>
                                            <th> Bill No </th>
                                            <th> Bill Date  </th>
                                            <th> Retailer </th>
                                            <th> Bill Amount </th>
                                            <th> SR </th>
                                            <th> Collection </th>
                                            <th> Pending  </th>
                                            <th> Employee </th>
                                            <th> Status </th>
                                            <th> Action </th>
                                        </tr>
                                    </tfoot>    
                            </table>
                        </div>
                    </div>                                
                                </div>
                            </div>
                        </div>
                        <!-- </form> -->
                    </div>
                </div>
            </div>
            <!-- #END# Masked Input -->
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
  <div class="modal fade" id="processModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <center><h4 class="modal-title">Bill Transactions </h4></center>
          </div>
          <div class="modal-body">
        
            <div class="body">
                <div class="demo-masked-input">
                    <div class="row clearfix">
                        <div class="row">
                            <div class="col-md-12 cust-tbl">
                                <div class="col-md-3">
                                    <b>Bill No : </b> <span id='bill_no'></span>
                                    <input type="hidden" id="currentBillNo" autocomplete="off" name="currentBillNo" class="form-control"> 
                                    <input type="hidden" id="currentBillId" autocomplete="off" name="currentBillId" class="form-control"> 
                                    <input type="hidden" id="currentBillRetailer" autocomplete="off" name="currentBillRetailer" class="form-control">
                                    <input type="hidden" id="currentBillAmount" autocomplete="off" name="currentBillAmount" class="form-control"> 
                                    <input type="hidden" id="currentBillCompany" autocomplete="off" name="currentBillCompany" class="form-control">     
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
                        </div>
                         
                         <br>
                           <div class="row">
                            
                        <div class="col-md-12">
                            <div class="col-md-12 radio-btns-div">
                            <div class="col-md-2">
                            <?php if ((in_array('owner', $des)) || (in_array('cashier', $des))){ ?>
                                <input name="group5" type="radio" id="radio_cash" class="with-gap radio-col-red" checked />
                                <label for="radio_cash">Cash</label>
                            <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_cash" class="with-gap radio-col-red" disabled />
                                <label for="radio_cash">Cash</label>
                            <?php } ?>
                            </div>
                            <div class="col-md-2">
                            <?php if ((in_array('owner', $des)) || (in_array('cashier', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5" type="radio" id="radio_cheque" class="with-gap radio-col-red"  />
                                <label for="radio_cheque">Cheque</label>
                            <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_cheque" class="with-gap radio-col-red"  disabled/>
                                <label for="radio_cheque">Cheque</label>
                            <?php } ?>
                            </div>
                            <div class="col-md-2">
                            <?php if ((in_array('owner', $des)) || (in_array('cashier', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5" type="radio" id="radio_neft" class="with-gap radio-col-red"  />
                                <label for="radio_neft">NEFT</label>
                            <?php }else{ ?>
                                 <input name="group5" type="radio" id="radio_neft" class="with-gap radio-col-red"  disabled/>
                                <label for="radio_neft">NEFT</label>
                            <?php } ?>
                            </div>  
                            <div class="col-md-2">
                            <?php if ((in_array('owner', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5" type="radio" id="radio_cd" class="with-gap radio-col-red"  />
                                  <label for="radio_cd">CD</label>
                            <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_cd" class="with-gap radio-col-red" disabled />
                                  <label for="radio_cd">CD</label>
                            <?php } ?> 
                            </div>
                            <div class="col-md-2">
                            <?php if ((in_array('owner', $des)) || (in_array('cashier', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5" type="radio" id="radio_debit" class="with-gap radio-col-red" />
                                <label for="radio_debit">Debit</label>
                            <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_debit" class="with-gap radio-col-red" disabled/>
                                <label for="radio_debit">Debit</label>
                             <?php } ?> 
                            </div>
                            <div class="col-md-2">
                            <?php if ((in_array('owner', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5" type="radio" id="radio_officeAdj" class="with-gap radio-col-red" />
                                <label for="radio_officeAdj">Office Adjustment</label>
                            <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_officeAdj" class="with-gap radio-col-red" disabled/>
                                <label for="radio_officeAdj">Office Adjustment</label>
                             <?php } ?> 
                            </div>
                            <div class="col-md-2">
                            <?php if ((in_array('owner', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5" type="radio" id="radio_otherAdj" class="with-gap radio-col-red" />
                                <label for="radio_otherAdj">Other Adjustment</label>
                            <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_otherAdj" class="with-gap radio-col-red" disabled/>
                                <label for="radio_otherAdj">Other Adjustment</label>
                             <?php } ?> 
                            </div>
                            <div class="col-md-2">
                            <?php if ((in_array('owner', $des)) || (in_array('godownkeeper', $des))){ ?>
                                <input name="group5" type="radio" id="radio_sr" class="with-gap radio-col-red"  />
                                <label for="radio_sr">SR/FSR</label>
                            <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_sr" class="with-gap radio-col-red"  disabled/>
                                <label for="radio_sr">SR/FSR</label>
                             <?php } ?>
                             </div> 
                            <div class="col-md-2">
                            <?php if ((in_array('owner', $des)) || (in_array('manager', $des)) || (in_array('senior_manager', $des))){ ?> 
                                <input name="group5" type="radio" id="radio_allocation" class="with-gap radio-col-red"  />
                                <label for="radio_allocation">Add to Open Allocation</label>
                            <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_allocation" class="with-gap radio-col-red"  disabled/>
                                <label for="radio_allocation">Add to Open Allocation</label>
                            <?php } ?> 
                            </div>
                            <div class="col-md-2">
                            <?php if ((in_array('owner', $des)) || (in_array('manager', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5" type="radio" id="radio_EmpDelivery" class="with-gap radio-col-red"  />
                                <label for="radio_EmpDelivery">Direct Delivery by Employee</label>
                            <?php }else{ ?>
                                 <input name="group5" type="radio" id="radio_EmpDelivery" class="with-gap radio-col-red" disabled />
                                <label for="radio_EmpDelivery">Direct Delivery by Employee</label>
                            <?php } ?>
                            </div> 

                            <div class="col-md-2">
                            <?php if ((in_array('owner', $des)) || (in_array('manager', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5" type="radio" id="radio_Leave" class="with-gap radio-col-red"  />
                                <label for="radio_Leave">Leave Unallocated</label>
                            <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_Leave" class="with-gap radio-col-red" disabled />
                                <label for="radio_Leave">Leave Unallocated</label>
                            <?php } ?> 
                            </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div id="srDiv" style="display: none" class="row"></div>
                    
                    <div id="chequeDiv" style="display: none" class="row">
                        <div class="col-md-12 cust-tbl">
                            <div class="col-md-4">
                                    <b>Employee</b>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="chequeEmp" autocomplete="off"  list="chequeEmpList" name="chequeEmp" class="form-control" placeholder="Employee Name">   
                                    <datalist id="chequeEmpList">
                                        <?php
                                            foreach($employee as $data){
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
                                <div class="col-md-4">
                                    <b>Amount</b>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="chequeAmount" onkeypress="return numbersonly(event)" autocomplete="off" name="chequeAmount" class="form-control" placeholder="Cheque Amount">   
                                    
                                  </div>
                                  </div>
                                </div> 

                            </div>
                             <div class="col-md-12">
                                <div class="col-md-4">
                                    <button id="chequeSaveBtn" type="button" class="btn btn-primary btnStyle m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-danger m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                             </div>
                    </div>



                    <div id="neftDiv" style="display: none" class="row">
                        <div class="col-md-12 cust-tbl">
                                <div class="col-md-4">
                                    <b>Employee</b>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="neftEmp" autocomplete="off"  list="neftEmpList" name="neftEmp" class="form-control" placeholder="Employee Name">   
                                    <datalist id="neftEmpList">
                                        <?php
                                            foreach($employee as $data){
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
                                <div class="col-md-4">
                                    <b>Amount</b>
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
                                <div class="col-md-4">
                                    <button id="neftSaveBtn" type="button" class="btn btn-primary btnStyle m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-danger m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                    </div>

                    <div id="cdDiv" style="display: none" class="row">
                        <div class="col-md-12 cust-tbl">
                            <div class="col-md-4">
                                    <b>Employee</b>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="cdEmp" autocomplete="off"  list="cdEmpList" name="cdEmp" class="form-control" placeholder="Employee Name">   
                                    <datalist id="cdEmpList">
                                        <?php
                                            foreach($employee as $data){
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
                                <div class="col-md-4">
                                    <b>CD Amount</b>
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

                             <div class="col-md-12 cust-tbl">
                                <div class="col-md-12">
                                    <b>Remark</b>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="cdRemark" autocomplete="off" name="cdRemark" class="form-control" placeholder="Remark">   
                                    
                                  </div>
                                  </div>
                                </div> 
                             </div>

                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <button id="cdSaveBtn" type="button" class="btn btn-primary btnStyle m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-danger m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                    </div>

                    <div id="debitDiv" style="display: none" class="row">
                        <div class="col-md-12 cust-tbl">
                            <div class="col-md-4">
                                    <b>Employee</b>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="debitEmp" autocomplete="off"  list="debitEmpList" name="debitEmp" class="form-control" placeholder="Employee Name">   
                                    <datalist id="debitEmpList">
                                        <?php
                                            foreach($employee as $data){
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
                                <div class="col-md-4">
                                    <b>Debit Amount</b>
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

                             <div class="col-md-12 cust-tbl">
                                <div class="col-md-12">
                                    <b>Remark</b>
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
                                    <button id="debitSaveBtn" type="button" class="btn btn-primary btnStyle m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-danger m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                    </div>

                    <div id="officeAdjDiv" style="display: none" class="row">
                        <div class="col-md-12 cust-tbl">
                            <div class="col-md-4">
                                    <b>Employee</b>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="officeAdjEmp" autocomplete="off"  list="officeAdjList" name="officeAdjEmp" class="form-control" placeholder="Employee Name">   
                                    <datalist id="officeAdjList">
                                        <?php
                                            foreach($employee as $data){
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
                                <div class="col-md-4">
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

                             <div class="col-md-12 cust-tbl">
                                <div class="col-md-12">
                                    <b>Remark</b>
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
                                    <button id="officeAdjSaveBtn" type="button" class="btn btn-primary btnStyle m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-danger m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                    </div>

                    <div id="otherAdjDiv" style="display: none" class="row">
                        <div class="col-md-12 cust-tbl">
                            <div class="col-md-4">
                                    <b>Employee</b>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="otherAdjEmp" autocomplete="off"  list="otherAdjEmpList" name="otherAdjEmp" class="form-control" placeholder="Employee Name">   
                                    <datalist id="otherAdjEmpList">
                                        <?php
                                            foreach($employee as $data){
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
                                <div class="col-md-4">
                                    <b>Other Adjustment Amount</b>
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

                             <div class="col-md-12 cust-tbl">
                                <div class="col-md-12">
                                    <b>Remark</b>
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
                                    <button id="otherAdjSaveBtn" type="button" class="btn btn-primary btnStyle m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-danger m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                    </div>

                    <div id="empDeliveryDiv" style="display: none" class="row">
                        <div class="col-md-12 cust-tbl">
                            <div class="col-md-4">
                                    <b>Employee</b>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="deliveryEmp" autocomplete="off"  list="deliveryEmpList" name="deliveryEmp" class="form-control" placeholder="Employee Name">   
                                    <datalist id="deliveryEmpList">
                                        <?php
                                            foreach($employee as $data){
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

                             <div class="col-md-12 cust-tbl">
                                <div class="col-md-12">
                                    <b>Remark</b>
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
                                    <button id="deliveryEmpSaveBtn" type="button" class="btn btn-primary btnStyle m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-danger m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                    </div>

                    <div id="allocationDiv" style="display: none" class="row">
                        <div class="col-md-12 cust-tbl">
                            <div class="col-md-4">
                                    <b>Open Allocations</b>
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
                                    <button id="allocationSaveBtn" type="button" class="btn btn-primary btnStyle m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-danger m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                    </div>

                    <div id="leaveDiv" style="display: none" class="row">
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <button id="leaveSaveBtn" type="button" class="btn btn-primary btnStyle m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-danger m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                    </div>

                    <div id="newAllocationDiv" style="display: none" class="row">
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <button id="newAllocationBtn" type="button" class="btn btn-primary btnStyle m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-danger m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                    </div>

                    <div id="cashDiv" class="row">
                        <div class="col-md-12 cust-tbl">
                                <div class="col-md-4">
                                    <b>Employee</b>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="cashEmp" autocomplete="off"  list="cashEmpList" name="cashEmp" class="form-control" placeholder="Employee Name">   
                                    <datalist id="cashEmpList">
                                        <?php
                                            foreach($employee as $data){
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

                                  <div class="col-md-4">
                                    <b>Cash Amount</b>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="cashAmtTemp" onkeypress="return numbersonly(event)" autocomplete="off" name="cashAmtTemp" class="form-control" placeholder="">   
                                    
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
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersonly(event)" onkeyup="calMoneyPrc();" type="text" name="add2000" id="add2000"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad2000"></span></td>
                                        

                                        <td align="right">500</td>
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersonly(event)" onkeyup="calMoneyPrc();" type="text" name="add500" id="add500"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad500"></span></td>

                                        
                                    </tr>
                                     <tr>
                                        <td align="right">200</td>
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersonly(event)" onkeyup="calMoneyPrc();" type="text" name="add200" id="add200"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad200"></span></td>

                                         <td align="right">100</td>
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersonly(event)" onkeyup="calMoneyPrc();" type="text" name="add100" id="add100"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad100"></span></td>
                                    </tr>
                                     <tr>
                                        <td align="right">50</td>
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersonly(event)" onkeyup="calMoneyPrc();" type="text" name="add50" id="add50"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad50"></span></td>

                                         <td align="right">20</td>
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersonly(event)" onkeyup="calMoneyPrc();" type="text" name="add20" id="add20" autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad20"></span></td>
                                    </tr>
                                     <tr>
                                        <td align="right">10</td>
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersonly(event)" onkeyup="calMoneyPrc();" type="text" name="add10" id="add10"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad10"></span></td>

                                         <td align="right">Coins</td>
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersonly(event)" onkeyup="calMoneyPrc();" type="text" name="coin" id="coin"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="coins"></span></td>
                                    </tr>
                                    <tr>
                                        
                                        <td align="right">Total Actual</td>
                                        <td align="right"><span id="totalActual"></span></td>
                                        <td align="center">
                                            <input style="height:25px;width: 80%" type="hidden" name="collectedAmt" id="collectedAmt" class="form-control">
                                        </td>

                                        <td></td>
                                        <td class="text-xs-right">
                                            <span id="income_exp_short_status"></span>
                                        </td>
                                        <td class="text-xs-right">
                                            <span id="income_exp_short_amt"></span>
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
                                    
                                    <button id="cashSaveBtn" type="button" class="btn btn-primary btnStyle m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-danger m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>

                          <?php } else { ?>
                               <div class="row clearfix">
                                <div class="col-md-5">
                                    
                                    <button id="cashSaveBtn" disabled type="button" class="btn btn-primary btnStyle m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" disabled type="button" class="btn btn-danger m-t-15 waves-effect">
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

<?php $this->load->view('/layouts/processButtonView'); ?>


<script type="text/javascript">
    $(document).on('click','#radio_newAllocation',function(){
        $('#srDiv').css("display", "none");
        $('#cashDiv').css("display", "none");
        $('#neftDiv').css("display", "none");
        $('#cdDiv').css("display", "none");
        $('#debitDiv').css("display", "none");
        $('#officeAdjDiv').css("display", "none");
        $('#otherAdjDiv').css("display", "none");
        $('#allocationDiv').css("display", "none");
        $('#empDeliveryDiv').css("display", "none");
        $('#leaveDiv').css("display", "none");
        $('#chequeDiv').css("display", "none");
        $('#newAllocationDiv').css("display", "block");
    });

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
        $('#leaveDiv').css("display", "none");
        $('#chequeDiv').css("display", "block");
        $('#newAllocationDiv').css("display", "none");
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
        $('#leaveDiv').css("display", "none");
        $('#empDeliveryDiv').css("display", "none");
        $('#newAllocationDiv').css("display", "none");

        $('#add2000').val("");
        $('#add1000').val("");
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
        $('#leaveDiv').css("display", "none");
        $('#radio_cash').prop('checked', true);
        $('#newAllocationDiv').css("display", "none");
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
        $('#leaveDiv').css("display", "none");
        $('#empDeliveryDiv').css("display", "none");
        $('#newAllocationDiv').css("display", "none");
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
        $('#leaveDiv').css("display", "none");
        $('#empDeliveryDiv').css("display", "none");
        $('#newAllocationDiv').css("display", "none");
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
        $('#leaveDiv').css("display", "none");
        $('#debitDiv').css("display", "block");
        $('#empDeliveryDiv').css("display", "none");
        $('#newAllocationDiv').css("display", "none");
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
        $('#leaveDiv').css("display", "none");
        $('#empDeliveryDiv').css("display", "none");
        $('#newAllocationDiv').css("display", "none");
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
        $('#leaveDiv').css("display", "none");
        $('#empDeliveryDiv').css("display", "none");
        $('#newAllocationDiv').css("display", "none");
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
        $('#leaveDiv').css("display", "none");
        $('#empDeliveryDiv').css("display", "none");
        $('#newAllocationDiv').css("display", "none");
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
        $('#leaveDiv').css("display", "none");
        $('#empDeliveryDiv').css("display", "none");
        $('#newAllocationDiv').css("display", "none");
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
        $('#leaveDiv').css("display", "none");
        $('#empDeliveryDiv').css("display", "block");
        $('#newAllocationDiv').css("display", "none");
    });

    $(document).on('click','#radio_Leave',function(){
        $('#srDiv').css("display", "none");
        $('#cashDiv').css("display", "none");
        $('#neftDiv').css("display", "none");
        $('#chequeDiv').css("display", "none");
        $('#cdDiv').css("display", "none");
        $('#officeAdjDiv').css("display", "none");
        $('#otherAdjDiv').css("display", "none");
        $('#allocationDiv').css("display", "none");
        $('#debitDiv').css("display", "none");
        $('#leaveDiv').css("display", "block");
        $('#empDeliveryDiv').css("display", "none");
        $('#newAllocationDiv').css("display", "none");
    });
</script>
 <script>
//      function numbersonly(e){
//         evt = (evt) ? evt : window.event;
//         var charCode = (evt.which) ? evt.which : evt.keyCode;
//         if (charCode > 31 && (charCode < 45 || charCode > 57) ) {
//             return false;
//         }
//         return true;
//     }
 </script>

<script>
    function numbersonly(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode !=45 && (charCode < 48 || charCode > 57) ) {
            return false;
        }
        return true;
    }
</script>



<script type="text/javascript">
    function checkBillNo(no)
    {
        var nos=no.value;

        if(nos.trim()===""){
          document.getElementById('billNo').value="";
          alert("Enter bill number");die();
        }
        $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('AllocationByManagerController/checkValuesByBillno');?>",
                    data:{"billNo" : nos},
                    success: function (data) {
                      if(data.trim()==""){
                         $("#prDetails").prop("disabled", false);
                         $('#billNo_Id').html('<span style="color: red;">'+data+'</span>');
                      }else{
                        $("#prDetails").prop("disabled", true);
                          $('#billNo_Id').html('<span style="color: red;">'+data+'</span>');
                      }

                        
                    }  
                });
    }
</script>

<script type="text/javascript">
    function checkBillSr(no)
    {
        var nos=no.value;
        var cmpName=document.getElementById('cmpName').value;

        if(nos.trim()===""){
            document.getElementById('billNo').value="";
            alert("Please enter bill no.");die();
        }
        if(cmpName.trim()==="--Select Company---"){
            document.getElementById('billNo').value="";
            alert("Please select company first.");die();
        }
        
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('AdHocController/checkValuesByBillSr');?>",
            data:{"billNo" : nos,"comp":cmpName},
            success: function (data) {
                $('#billSr_Id').html('<span style="color: red;">'+data+'</span>');
            }  
        });
    }
</script>
<script>
    $(document).on('click','#prDetails',function(){
        // var netAmount=document.getElementById('netAmount').value;
        var billNo=$('#billNo').val();
        var retailerName=$('#retailerName').val();
        var netAmount=$('#netAmount').val();
        var cmpName=$('#cmpName').val();

        if(billNo=="" || retailerName=="" || netAmount==""){
            alert('Please enter all details');
            $('#processModal').modal('toggle');
            die();
        }else{
          $('#currentBillNo').val(billNo);
          $('#currentBillRetailer').val(retailerName);
          $('#currentBillAmount').val(netAmount);
          $('#currentBillCompany').val(cmpName);
          $('#currentPendingAmt').val(netAmount);
          $('#cashAmtTemp').val(netAmount);

          $('#bill_no').text(billNo);
          $('#bill_retailer').text(retailerName);
          $('#bill_pendingAmt').text(netAmount);
        }
        
        
    });

    $('#netAmount').keypress(function (e) {
       var key = e.which;
       if(key == 13)  // the enter key code
        {
          var billNo=$('#billNo').val();
          var retailerName=$('#retailerName').val();
          var netAmount=$('#netAmount').val();
          var cmpName=$('#cmpName').val();

          if(billNo=="" || retailerName=="" || netAmount==""){
              alert('Please enter all details');
              die();
          }else{
              $('#processModal').modal('toggle');

            $('#currentBillNo').val(billNo);
            $('#currentBillRetailer').val(retailerName);
            $('#currentBillAmount').val(netAmount);
            $('#currentBillCompany').val(cmpName);
           
            $('#bill_no').text(billNo);
            $('#bill_retailer').text(retailerName);
            $('#bill_pendingAmt').text(netAmount);
          }
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
            alert('No data available.');die();
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('AdhocBillController/getSrDetails');?>",
                data:{currentBillId:currentBillId},
                success: function (data) {
                    $('#srDiv').html(data);
                }  
            });
        }
        
    });
</script>

<script type="text/javascript">
  $(document).on('click','#fsrBtn',function(){
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
                url:"<?php echo site_url('AdhocBillController/confirmFSR');?>",
                data:{billId:billId},
                success: function (data) {
                    if(data.trim()=="Record Updated"){
                        alert(data);
                        window.location.href="<?php echo base_url();?>index.php/AdHocController/adhocBills";
                    }else{
                        alert(data);
                    }
                }  
            });
        }
        
    });
</script>

<script type="text/javascript">
  $(document).on('click','#srBtn',function(){

      // var msg=document.getElementById('all_id').innerHTML;
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
          url:"<?php echo site_url('AdhocBillController/updateSRCreditAdj');?>",
          data:{billId:billId,productName:productName,mrp:mrp,qty:qty,fsReturnQty:fsReturnQty,netAmount:netAmount,selAmount:selAmount,id:id,returnedQty:returnedQty,returnAmt:returnAmt,currentBillId:currentBillId},
          success: function (data) {
            // alert(data);
            if(data.trim()=="SR Amount is greater than pending amount."){
              alert(data);
            }
              window.location.href="<?php echo base_url();?>index.php/AdHocController/adhocBills";
          }  
      });
  });
</script>

<script type="text/javascript">
    $(document).on('click','#cashSaveBtn',function(){
        $("#cashSaveBtn").attr("disabled", true);

        var a2000=$('#add2000').val();
        var a1000=0;
        var a500=$('#add500').val();
        var a200=$('#add200').val();
        var a100=$('#add100').val();
        var a50=$('#add50').val();
        var a20=$('#add20').val();
        var a10=$('#add10').val();
        var coin=$('#coin').val();

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

        var currentBillAmount=$('#currentBillAmount').val();
        var currentBillCompany=$('#currentBillCompany').val();
        var cashAmtTemp=$('#cashAmtTemp').val();

        var empName=$('#cashEmp').val();
        var empId = $('#cashEmpList').find('option[value="'+empName+'"]').attr('id');
        var collectedAmt=$('#collectedAmt').val();
        var currentPendingAmt=$('#currentPendingAmt').val();
        var currentBillId=$('#currentBillId').val();
        var currentBillNo=$('#currentBillNo').val();
        var currentBillRetailer=$('#currentBillRetailer').val();
        var billNoText=currentBillNo+' : '+currentBillRetailer;


        // alert(collectedAmt+' '+currentPendingAmt+' '+cashAmtTemp);die();

        if(empName==''){
            
            alert('Please select employee.');
            $("#cashSaveBtn").attr("disabled", false);
            die();
        }else if(empName!=''){
            if (typeof empId === "undefined") {
                $("#cashSaveBtn").attr("disabled", false);
                alert('Please select correct employee.');
                die();
            }
        }

        if(collectedAmt=='' || collectedAmt==0){
            $("#cashSaveBtn").attr("disabled", false);
            alert('Please enter cash details.');die();
        }

        if(collectedAmt<0 ){
            $("#cashSaveBtn").attr("disabled", false);
            alert('Please enter correct cash details.');die();
        }

        if(parseInt(cashAmtTemp) < parseInt(collectedAmt)){
            $("#cashSaveBtn").attr("disabled", false);
            alert('Cash amount should not be greater than Amount to be Collected');die();
        }

        if(parseInt(cashAmtTemp) > parseInt(collectedAmt)){
            $("#cashSaveBtn").attr("disabled", false);
            alert('Cash amount should not be less than Amount to be Collected');die();
        }
        $("#cashSaveBtn").attr("disabled", true);
        // alert(currentBillAmount+' '+collectedAmt+' nnn');die();
        
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('AdhocBillController/cashCollection');?>",
            data:{currentBillNo:currentBillNo,currentBillRetailer:currentBillRetailer,currentBillAmount:currentBillAmount,currentBillCompany:currentBillCompany,empName:empName,empId:empId,collectedAmt:collectedAmt,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId,a2000:a2000,a1000:a1000,a500:a500,a200:a200,a100:a100,a50:a50,a20:a20,a10:a10,coin:coin},
            success: function (data) {
                $('#cashEmp').val("");
                if(data.trim()=="Record updated"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/AdHocController/adhocBills";
                }else{
                    alert(data);
                }
            }  
        });
        
        
    });
    
</script>


<script type="text/javascript">
    $(document).on('click','#chequeSaveBtn',function(){
        $("#chequeSaveBtn").attr("disabled", true);

        var currentBillAmount=$('#currentBillAmount').val();
        var currentBillCompany=$('#currentBillCompany').val();

        var empName=$('#chequeEmp').val();
        var empId = $('#chequeEmpList').find('option[value="'+empName+'"]').attr('id');
        var chequeAmount=$('#chequeAmount').val();
        var currentPendingAmt=$('#currentPendingAmt').val();
        var currentBillId=$('#currentBillId').val();
        var currentBillNo=$('#currentBillNo').val();
        var currentBillRetailer=$('#currentBillRetailer').val();
        var billNoText=currentBillNo+' : '+currentBillRetailer;
        
        
        if(empName==''){
            $("#chequeSaveBtn").attr("disabled", false);
            alert('Please select employee.');die();
        }else if(empName!=''){
            if (typeof empId === "undefined") {
                $("#chequeSaveBtn").attr("disabled", false);
                alert('Please select correct employee.');die();
            }
        }

        if(chequeAmount=='' || chequeAmount==0){
            $("#chequeSaveBtn").attr("disabled", false);
            alert('Please enter cheque amount.');die();
        }

        if(parseInt(currentPendingAmt) < parseInt(chequeAmount)){
            $("#chequeSaveBtn").attr("disabled", false);
            alert('Cheque amount should not be greater than Pending amount');die();
        }

        $("#chequeSaveBtn").attr("disabled", true);

        
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('AdhocBillController/chequeCollection');?>",
            data:{currentBillNo:currentBillNo,currentBillRetailer:currentBillRetailer,currentBillAmount:currentBillAmount,currentBillCompany:currentBillCompany,empName:empName,empId:empId,chequeAmount:chequeAmount,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId},
            success: function (data) {
                $('#chequeEmp').val("");
                $('#chequeAmount').val("");
                if(data.trim()=="Record updated"){
                    alert(data);
                   window.location.href="<?php echo base_url();?>index.php/AdHocController/adhocBills";
                }else{
                    alert(data);
                }
            }  
        });
        
        
    });
    
</script>

<script type="text/javascript">
    $(document).on('click','#neftSaveBtn',function(){
        $("#neftSaveBtn").attr("disabled", true);

        var currentBillAmount=$('#currentBillAmount').val();
        var currentBillCompany=$('#currentBillCompany').val();

        var empName=$('#neftEmp').val();
        var empId = $('#neftEmpList').find('option[value="'+empName+'"]').attr('id');
        var neftAmount=$('#neftAmount').val();
        var currentPendingAmt=$('#currentPendingAmt').val();
        var currentBillId=$('#currentBillId').val();
        var currentBillNo=$('#currentBillNo').val();
        var currentBillRetailer=$('#currentBillRetailer').val();
        var billNoText=currentBillNo+' : '+currentBillRetailer;
        
        
        if(empName==''){
            $("#neftSaveBtn").attr("disabled", false);
            alert('Please select employee.');die();
        }else if(empName!=''){
            if (typeof empId === "undefined") {
                $("#neftSaveBtn").attr("disabled", false);
                alert('Please select correct employee.');die();
            }
        }

        if(neftAmount=='' || neftAmount==0){
            $("#neftSaveBtn").attr("disabled", false);
            alert('Please enter cheque amount.');die();
        }

        if(parseInt(currentPendingAmt) < parseInt(neftAmount)){
            $("#neftSaveBtn").attr("disabled", false);
            alert('Cheque amount should not be greater than Pending amount');die();
        }

        $("#neftSaveBtn").attr("disabled", true);
        
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('AdhocBillController/neftCollection');?>",
            data:{currentBillNo:currentBillNo,currentBillRetailer:currentBillRetailer,currentBillAmount:currentBillAmount,currentBillCompany:currentBillCompany,empName:empName,empId:empId,neftAmount:neftAmount,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId},
            success: function (data) {
                $('#neftEmp').val("");
                $('#neftAmount').val("");
                if(data.trim()=="Record updated"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/AdHocController/adhocBills";
                }else{
                    alert(data);
                }
            }  
        });
        
        
    });
    
</script>

<script type="text/javascript">
  $(document).on('click','#allocationSaveBtn',function(){
        $("#allocationSaveBtn").attr("disabled", true);


        var allocationCode=$('#allocationCode').val();
        var allocationId = $('#allocationCodeList').find('option[value="'+allocationCode+'"]').attr('id');
        
        var currentBillAmount=$('#currentBillAmount').val();
        var currentBillCompany=$('#currentBillCompany').val();

        var currentPendingAmt=$('#currentPendingAmt').val();
        var currentBillId=$('#currentBillId').val();
        var currentBillNo=$('#currentBillNo').val();
        var currentBillRetailer=$('#currentBillRetailer').val();

        if(allocationCode==''){
            $("#allocationSaveBtn").attr("disabled", false);
            alert('Please select allocation number.');die();
        }else if(allocationCode!=''){
            if (typeof allocationId === "undefined") {
                $("#allocationSaveBtn").attr("disabled", false);
                alert('Please select correct allocation number.');die();
            }
        }

        $("#allocationSaveBtn").attr("disabled", true);
        
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('AdhocBillController/addBillToAllocation');?>",
            data:{currentBillNo:currentBillNo,currentBillRetailer:currentBillRetailer,currentBillAmount:currentBillAmount,currentBillCompany:currentBillCompany,allocationCode:allocationCode,allocationId:allocationId,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId,currentPendingAmt:currentPendingAmt},
            success: function (data) {
                $('#allocationCode').val("");
                if(data.trim()=="Record inserted"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/AdHocController/adhocBills";
                }else{
                    alert(data);
                }
            }  
        });
        
        
    });
</script>

<script type="text/javascript">
    $(document).on('click','#cdSaveBtn',function(){
        $("#cdSaveBtn").attr("disabled", true);

        var currentBillAmount=$('#currentBillAmount').val();
        var currentBillCompany=$('#currentBillCompany').val();

        var empName=$('#cdEmp').val();
        var empId = $('#cdEmpList').find('option[value="'+empName+'"]').attr('id');
        var cdAmount=$('#cdAmount').val();
        var cdRemark=$('#cdRemark').val();
        var currentPendingAmt=$('#currentPendingAmt').val();
        var currentBillId=$('#currentBillId').val();
        var currentBillNo=$('#currentBillNo').val();
        var currentBillRetailer=$('#currentBillRetailer').val();
        var billNoText=currentBillNo+' : '+currentBillRetailer;


        
        
        if(empName==''){
            // empName='';
            // empId=0;
            $("#cdSaveBtn").attr("disabled", false);
            alert('Please select employee.');die();
        }else if(empName!=''){
            if (typeof empId === "undefined") {
                $("#cdSaveBtn").attr("disabled", false);
                alert('Please select correct employee.');die();
            }
        }

        if(cdAmount=='' || cdAmount==0){
            $("#cdSaveBtn").attr("disabled", false);
            alert('Please enter CD amount.');die();
        }

        if(parseInt(currentPendingAmt) < parseInt(cdAmount)){
            $("#cdSaveBtn").attr("disabled", false);
            alert('CD amount should not be greater than Pending amount');die();
        }

        if(cdRemark==''){
            $("#cdSaveBtn").attr("disabled", false);
            alert('Please enter remark.');die();
        }

        $("#cdSaveBtn").attr("disabled", true);

        $.ajax({
            type: "POST",
            url:"<?php echo site_url('AdhocBillController/cashDiscountCollection');?>",
            data:{currentBillNo:currentBillNo,currentBillRetailer:currentBillRetailer,currentBillAmount:currentBillAmount,currentBillCompany:currentBillCompany,empName:empName,empId:empId,cdAmount:cdAmount,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId,cdRemark:cdRemark},
            success: function (data) {
                $('#cdAmount').val("");
                $('#cdRemark').val("");
                if(data.trim()=="Record updated"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/AdHocController/adhocBills";
                }else{
                    alert(data);
                }
            }  
        });
        
        
    });
    
</script>

<script type="text/javascript">
    $(document).on('click','#debitSaveBtn',function(){
        $("#debitSaveBtn").attr("disabled", true);

        var currentBillAmount=$('#currentBillAmount').val();
        var currentBillCompany=$('#currentBillCompany').val(); 

        var empName=$('#debitEmp').val();
        var empId = $('#debitEmpList').find('option[value="'+empName+'"]').attr('id');
        var debitAmount=$('#debitAmount').val();
        var debitRemark=$('#debitRemark').val();
        var currentPendingAmt=$('#currentPendingAmt').val();
        var currentBillId=$('#currentBillId').val();
        var currentBillNo=$('#currentBillNo').val();
        var currentBillRetailer=$('#currentBillRetailer').val();
        var billNoText=currentBillNo+' : '+currentBillRetailer;
        
        
        if(empName==''){
            $("#debitSaveBtn").attr("disabled", false);
            alert('Please select employee.');die();
        }else if(empName!=''){
            if (typeof empId === "undefined") {
                $("#debitSaveBtn").attr("disabled", false);
                alert('Please select correct employee.');die();
            }
        }

        if(debitAmount=='' || debitAmount==0){
            $("#debitSaveBtn").attr("disabled", false);
            alert('Please enter Debit amount.');die();
        }

        if(parseInt(currentPendingAmt) < parseInt(debitAmount)){
            $("#debitSaveBtn").attr("disabled", false);
            alert('Debit amount should not be greater than Pending amount');die();
        }

        if(debitRemark==''){
            $("#debitSaveBtn").attr("disabled", false);
            alert('Please enter remark.');die();
        }

        $("#debitSaveBtn").attr("disabled", true);

        $.ajax({
            type: "POST",
            url:"<?php echo site_url('AdhocBillController/debitToEmployeeCollection');?>",
            data:{currentBillNo:currentBillNo,currentBillRetailer:currentBillRetailer,currentBillAmount:currentBillAmount,currentBillCompany:currentBillCompany,empName:empName,empId:empId,debitAmount:debitAmount,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId,debitRemark:debitRemark},
            success: function (data) {
                $('#debitAmount').val("");
                $('#debitRemark').val("");
                if(data.trim()=="Record updated"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/AdHocController/adhocBills";
                }else{
                    alert(data);
                }
            }  
        });
        
        
    });
    
</script>

<script type="text/javascript">
    $(document).on('click','#officeAdjSaveBtn',function(){
        $("#officeAdjSaveBtn").attr("disabled", true);

        var currentBillAmount=$('#currentBillAmount').val();
        var currentBillCompany=$('#currentBillCompany').val();

        var empName=$('#officeAdjEmp').val();
        var empId = $('#officeAdjList').find('option[value="'+empName+'"]').attr('id');
        var officeAdjAmount=$('#officeAdjAmount').val();
        var officeAdjRemark=$('#officeAdjRemark').val();
        var currentPendingAmt=$('#currentPendingAmt').val();
        var currentBillId=$('#currentBillId').val();
        var currentBillNo=$('#currentBillNo').val();
        var currentBillRetailer=$('#currentBillRetailer').val();
        var billNoText=currentBillNo+' : '+currentBillRetailer;
        
        
        if(empName==''){
            $("#officeAdjSaveBtn").attr("disabled", false);
            alert('Please select employee.');die();
        }else if(empName!=''){
            if (typeof empId === "undefined") {
                $("#officeAdjSaveBtn").attr("disabled", false);
                alert('Please select correct employee.');die();
            }
        }

        // if(officeAdjAmount==''){
        //     $("#officeAdjSaveBtn").attr("disabled", false);
        //     alert('Please enter Office adjustment.');die();
        // }

        if(parseInt(currentPendingAmt) < parseInt(officeAdjAmount)){
            $("#officeAdjSaveBtn").attr("disabled", false);
            alert('Office adjustment amount should not be greater than Pending amount');die();
        }

        if(officeAdjRemark==''){
            $("#officeAdjSaveBtn").attr("disabled", false);
            alert('Please enter remark.');die();
        }

        $("#officeAdjSaveBtn").attr("disabled", true);

        $.ajax({
            type: "POST",
            url:"<?php echo site_url('AdhocBillController/officeAdjustmentCollection');?>",
            data:{currentBillNo:currentBillNo,currentBillRetailer:currentBillRetailer,currentBillAmount:currentBillAmount,currentBillCompany:currentBillCompany,empName:empName,empId:empId,officeAdjAmount:officeAdjAmount,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId,officeAdjRemark:officeAdjRemark},
            success: function (data) {
                $('#officeAdjAmount').val("");
                $('#officeAdjRemark').val("");
                if(data.trim()=="Record updated"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/AdHocController/adhocBills";
                }else{
                    alert(data);
                }

            }  
        });
        
        
    });
    
</script>

<script type="text/javascript">
    $(document).on('click','#otherAdjSaveBtn',function(){
        $("#otherAdjSaveBtn").attr("disabled", true);

        var currentBillAmount=$('#currentBillAmount').val();
        var currentBillCompany=$('#currentBillCompany').val();

        var empName=$('#otherAdjEmp').val();
        var empId = $('#otherAdjEmpList').find('option[value="'+empName+'"]').attr('id');
        var otherAdjAmount=$('#otherAdjAmount').val();
        var otherAdjRemark=$('#otherAdjRemark').val();
        var currentPendingAmt=$('#currentPendingAmt').val();
        var currentBillId=$('#currentBillId').val();
        var currentBillNo=$('#currentBillNo').val();
        var currentBillRetailer=$('#currentBillRetailer').val();
        var billNoText=currentBillNo+' : '+currentBillRetailer;
        
        
        if(empName==''){
            $("#otherAdjSaveBtn").attr("disabled", false);
            alert('Please select employee.');die();
        }else if(empName!=''){
            if (typeof empId === "undefined") {
                $("#otherAdjSaveBtn").attr("disabled", false);
                alert('Please select correct employee.');die();
            }
        }

        // if(otherAdjAmount==''){
        //     $("#otherAdjSaveBtn").attr("disabled", false);
        //     alert('Please enter Office adjustment.');die();
        // }

        if(parseInt(currentPendingAmt) < parseInt(otherAdjAmount)){
            $("#otherAdjSaveBtn").attr("disabled", false);
            alert('Office adjustment amount should not be greater than Pending amount');die();
        }

        if(otherAdjRemark==''){
            $("#otherAdjSaveBtn").attr("disabled", false);
            alert('Please enter remark.');die();
        }

        $("#otherAdjSaveBtn").attr("disabled", true);

        $.ajax({
            type: "POST",
            url:"<?php echo site_url('AdhocBillController/otherAdjustmentCollection');?>",
            data:{currentBillNo:currentBillNo,currentBillRetailer:currentBillRetailer,currentBillAmount:currentBillAmount,currentBillCompany:currentBillCompany,empName:empName,empId:empId,otherAdjAmount:otherAdjAmount,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId,otherAdjRemark:otherAdjRemark},
            success: function (data) {
                $('#otherAdjAmount').val("");
                $('#otherAdjRemark').val("");
                if(data.trim()=="Record updated"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/AdHocController/adhocBills";
                }else{
                    alert(data);
                }
            }  
        });
        
        
    });
    
</script>

<script type="text/javascript">
    $(document).on('click','#deliveryEmpSaveBtn',function(){
        $("#deliveryEmpSaveBtn").attr("disabled", true);

        var currentBillAmount=$('#currentBillAmount').val();
        var currentBillCompany=$('#currentBillCompany').val();

        var empName=$('#deliveryEmp').val();
        var empId = $('#deliveryEmpList').find('option[value="'+empName+'"]').attr('id');
        var deliveryAmount=$('#deliveryAmount').val();
        var deliveryRemark=$('#deliveryRemark').val();
        var currentPendingAmt=$('#currentPendingAmt').val();
        var currentBillId=$('#currentBillId').val();
        var currentBillNo=$('#currentBillNo').val();
        var currentBillRetailer=$('#currentBillRetailer').val();
        var billNoText=currentBillNo+' : '+currentBillRetailer;
        
        
        if(empName==''){
            $("#deliveryEmpSaveBtn").attr("disabled", false);
            alert('Please select employee.');die();
        }else if(empName!=''){
            if (typeof empId === "undefined") {
                $("#deliveryEmpSaveBtn").attr("disabled", false);
                alert('Please select correct employee.');die();
            }
        }

        if(deliveryAmount=='' || deliveryAmount==0){
            $("#deliveryEmpSaveBtn").attr("disabled", false);
            alert('Please enter amount.');die();
        }

        if(parseInt(currentPendingAmt) < parseInt(deliveryAmount)){
            $("#deliveryEmpSaveBtn").attr("disabled", false);
            alert('Amount should not be greater than Pending amount');die();
        }

        if(deliveryRemark==''){
            $("#deliveryEmpSaveBtn").attr("disabled", false);
            alert('Please enter remark.');die();
        }

        $("#deliveryEmpSaveBtn").attr("disabled", true);
       
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('AdhocBillController/empDeliveryCollection');?>",
            data:{currentBillNo:currentBillNo,currentBillRetailer:currentBillRetailer,currentBillAmount:currentBillAmount,currentBillCompany:currentBillCompany,empName:empName,empId:empId,deliveryAmount:deliveryAmount,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId,deliveryRemark:deliveryRemark},
            success: function (data) {
                $('#deliveryAmount').val("");
                $('#deliveryRemark').val("");
                if(data.trim()=="Record updated"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/AdHocController/adhocBills";
                }else{
                    alert(data);
                }
            }  
        });
        
    });


</script>

<script type="text/javascript">
    $(document).on('click','#leaveSaveBtn',function(){
        $("#leaveSaveBtn").attr("disabled", true);

        var currentBillAmount=$('#currentBillAmount').val();
        var currentBillCompany=$('#currentBillCompany').val();
        var currentBillNo=$('#currentBillNo').val();
        var currentBillRetailer=$('#currentBillRetailer').val();

        if(currentBillAmount=='' || currentBillAmount==0){
            $("#leaveSaveBtn").attr("disabled", false);
            alert('Please enter amount.');die();
        }

        $("#leaveSaveBtn").attr("disabled", true);
       
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('AdhocBillController/leaveUnAllocatedBill');?>",
            data:{currentBillNo:currentBillNo,currentBillRetailer:currentBillRetailer,currentBillAmount:currentBillAmount,currentBillCompany:currentBillCompany},
            success: function (data) {
                if(data.trim()=="Record inserted"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/AdHocController/adhocBills";
                }else{
                    alert(data);
                }
            }  
        });
        
    });


</script>

<script type="text/javascript">
    $(document).on('click','#newAllocationBtn',function(){
        $("#newAllocationBtn").attr("disabled", true);

        var currentBillAmount=$('#currentBillAmount').val();
        var currentBillCompany=$('#currentBillCompany').val();
        var currentBillNo=$('#currentBillNo').val();
        var currentBillRetailer=$('#currentBillRetailer').val();

        if(currentBillAmount=='' || currentBillAmount==0){
            $("#newAllocationBtn").attr("disabled", false);
            alert('Please enter amount.');die();
        }
       
        $("#newAllocationBtn").attr("disabled", true);
        
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('AdhocBillController/leaveUnAllocatedBill');?>",
            data:{currentBillNo:currentBillNo,currentBillRetailer:currentBillRetailer,currentBillAmount:currentBillAmount,currentBillCompany:currentBillCompany},
            success: function (data) {
                if(data.trim()=="Record inserted"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/AdHocController/adhocBills";
                }else{
                    alert(data);
                }
            }  
        });
        
    });


</script>

<script type="text/javascript">
  function calMoneyPrc() {
    var act_amount=document.getElementById('currentPendingAmt').value;
    // cashAmtTemp
    var cashAmtTemp=document.getElementById('cashAmtTemp').value;
    var a2000 = document.getElementById('add2000').value;
    var a1000 = 0;
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
    // document.getElementById('ad1000').innerHTML = c2;
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

    document.getElementById('totalActual').innerHTML = total;
    document.getElementById('collectedAmt').value= total;

    var final_amt=cashAmtTemp-total;
    if(final_amt>0){
        document.getElementById('income_exp_short_amt').innerHTML = "<span style='color:red'>-"+final_amt.toFixed(2)+"</span>";
        document.getElementById('income_exp_short_status').innerHTML = "<span style='color:red'>Short</span>";
    }else{
         document.getElementById('income_exp_short_amt').innerHTML = "<span style='color:blue'>"+final_amt.toFixed(2)+"</span>";
         document.getElementById('income_exp_short_status').innerHTML = "<span style='color:blue'>Excess</span>";
    }
    
  }
</script>

<script>
    $(document).on('click','#newAllocationDetails',function(){
        var id=$(this).attr('data-id');
        var compName=$(this).attr('data-compName');
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('commanTransactions/AllocationByProcessController/getSrDetailsBtn');?>",
            data:{billId:id,compName:compName},
            success: function (data) {
                $('#srDiv').html(data);
            }  
        });
    });

</script>