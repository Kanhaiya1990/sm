<?php 
$projectSessionName="";
if (isset($this->session->userdata['codeKeyData'])) {
	$projectSessionName= ($this->session->userdata['codeKeyData']['codeKeyValue']);
}
    $designation = ($this->session->userdata[$projectSessionName]['designation']);
    $des=explode(',',$designation);
?>
  
<div class="container">
  <div class="modal fade" id="processModalForAll" role="dialog" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <center>
            <h4 class="modal-title">Bill Transactions </h4>
          </center>
          </div>
          <div class="modal-body">
            <div class="body">
                <div class="demo-masked-input">
                    <div class="row clearfix">
                        <div class="row cust-tbl">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <b style="color: #000000;"> Bill No :</b>  <span style="color: #050A30;" id='bill_noForAll'></span>
                                    <input type="hidden" id="currentBillNoForAll" autocomplete="off" name="currentBillNoForAll" class="form-control"> 
                                    <input type="hidden" id="currentBillIdForAll" autocomplete="off" name="currentBillIdForAll" class="form-control"> 
                                     <input type="hidden" id="currentBillRetailerForAll" autocomplete="off" name="currentBillRetailerForAll" class="form-control">    
                                </div> 
                                
                                 <div class="col-md-3">
                                    <b style="color: #000000;">Bill Date :</b>  <span style="color: #050A30;" id='bill-dateForAll'></span>
                                </div> 
                                <span id='bill_retailerForAll'></span>
                               
                                <div class="col-md-3">
                                    <b style="color: #000000;">Pending Amount :</b> <span style="color: #050A30;" id='bill_pendingAmtForAll'></span>
                                    <input type="hidden" id="currentPendingAmtForAll" autocomplete="off" name="currentPendingAmtForAll" class="form-control">   
                                </div>
                            </div>

                            <div class="col-md-12 cust-tbl">
                              
                                <div class="col-md-3">
                                    <b style="color: #000000;">Route :</b> <span style="color: #050A30;" id='bill-routeForAll'></span>
                                </div>
                                <div class="col-md-3">
                                    <b style="color: #000000;">Salesman : </b> <span style="color: #050A30;" id='bill-salesmanForAll'></span>
                                </div>
                                <div class="col-md-3">
                                    <b style="color: #000000;">GST No. : </b> <span style="color: #050A30;" id='gstForAll'></span>
                                </div>
                                <div class="col-md-2">
                                  <span style="display:none;color: #050A30;" class="logo_prov"></span>
                                </div>
                            </div>
                        </div>
                         
                        <br>
                        <div class="row">   
                        <div class="col-md-12">
                            <div class="col-md-12 radio-btns-div">
                              <!-- <div>
                                <input name="group5" type="radio" id="radio_cash" class="with-gap radio-col-red" checked="" value="">
                                <label for="radio_cash">Cash</label>
                              </div> -->
                              <div>
                              <?php if ((in_array('owner', $des)) || (in_array('cashier', $des))){ ?>
                                <input name="group5ForAll" type="radio" id="radio_cashForAll" class="with-gap radio-col-red" checked />
                                <label for="radio_cashForAll">Cash</label>
                              <?php }else{ ?>
                                <input name="group5ForAll" type="radio" id="radio_cashForAll" class="with-gap radio-col-red" disabled />
                                <label for="radio_cashForAll">Cash</label>
                              <?php } ?> 
                            </div>
                            <div>
                              <?php if ((in_array('owner', $des)) || (in_array('cashier', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5ForAll" type="radio" id="radio_chequeForAll" class="with-gap radio-col-red"  />
                                <label for="radio_chequeForAll">Cheque</label>
                              <?php }else{ ?>
                                <input name="group5ForAll" type="radio" id="radio_chequeForAll" class="with-gap radio-col-red" disabled />
                                <label for="radio_chequeForAll">Cheque</label>
                              <?php } ?> 
                            </div>
                            <div>
                              <?php if ((in_array('owner', $des)) || (in_array('cashier', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5ForAll" type="radio" id="radio_neftForAll" class="with-gap radio-col-red"  />
                                <label for="radio_neftForAll">NEFT</label>
                              <?php }else{ ?>
                                <input name="group5ForAll" type="radio" id="radio_neftForAll" class="with-gap radio-col-red" disabled />
                                <label for="radio_neftForAll">NEFT</label>
                              <?php } ?>
                            </div> 
                            <div>
                              <?php if ((in_array('owner', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5ForAll" type="radio" id="radio_cdForAll" class="with-gap radio-col-red"  />
                                <label for="radio_cdForAll">CD</label>
                              <?php }else{ ?>
                                <input name="group5ForAll" type="radio" id="radio_cdForAll" class="with-gap radio-col-red" disabled />
                                <label for="radio_cdForAll">CD</label>
                              <?php } ?> 
                            </div>
                            <div>
                              <?php if ((in_array('owner', $des)) || (in_array('cashier', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5ForAll" type="radio" id="radio_debitForAll" class="with-gap radio-col-red" />
                                <label for="radio_debitForAll">Debit to Employee</label>
                              <?php }else{ ?>
                                <input name="group5ForAll" type="radio" id="radio_debitForAll" class="with-gap radio-col-red" disabled/>
                                <label for="radio_debitForAll">Debit to Employee</label>
                              <?php } ?> 
                            </div>
                            <div>
                              <?php if ((in_array('owner', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5ForAll" type="radio" id="radio_officeAdjForAll" class="with-gap radio-col-red" />
                                <label for="radio_officeAdjForAll">Office Adjustment</label>
                              <?php }else{ ?>
                                <input name="group5ForAll" type="radio" id="radio_officeAdjForAll" class="with-gap radio-col-red" disabled/>
                                <label for="radio_officeAdjForAll">Office Adjustment</label>
                              <?php } ?> 
                            </div>
                            <div>
                               <?php if ((in_array('owner', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5ForAll" type="radio" id="radio_otherAdjForAll" class="with-gap radio-col-red" />
                                <label for="radio_otherAdjForAll">Other Adjustment</label>
                              <?php }else{ ?>
                                <input name="group5ForAll" type="radio" id="radio_otherAdjForAll" class="with-gap radio-col-red" disabled/>
                                <label for="radio_otherAdjForAll">Other Adjustment</label>
                              <?php } ?> 
                            </div>
                            <div>
                              <?php if ((in_array('owner', $des)) || (in_array('godownkeeper', $des))){ ?>
                                <input name="group5ForAll" type="radio" id="radio_srForAll" class="with-gap radio-col-red"  />
                                <label for="radio_srForAll">SR/FSR</label>
                              <?php }else{ ?>
                                 <input name="group5ForAll" type="radio" id="radio_srForAll" class="with-gap radio-col-red" disabled />
                                <label for="radio_srForAll">SR/FSR</label>
                              <?php } ?> 
                              </div>
                              <div>
                              <?php if ((in_array('owner', $des)) || (in_array('manager', $des)) || (in_array('senior_manager', $des))){ ?>   
                                <input name="group5ForAll" type="radio" id="radio_allocationForAll" class="with-gap radio-col-red"  />
                                <label for="radio_allocationForAll">Add to Open Allocation</label>
                              <?php }else{ ?>
                                <input name="group5ForAll" type="radio" id="radio_allocationForAll" class="with-gap radio-col-red" disabled />
                                <label for="radio_allocationForAll">Add to Open Allocation</label>
                              <?php } ?> 
                            </div>
                            <div>
                              <?php if ((in_array('owner', $des)) || (in_array('manager', $des)) || (in_array('senior_manager', $des))){ ?> 
                                <input name="group5ForAll" type="radio" id="radio_EmpDeliveryForAll" class="with-gap radio-col-red"  />
                                <label for="radio_EmpDeliveryForAll">Direct Delivery by Employee</label>
                              <?php }else{ ?>
                                <input name="group5ForAll" type="radio" id="radio_EmpDeliveryForAll" class="with-gap radio-col-red" disabled />
                                <label for="radio_EmpDeliveryForAll">Direct Delivery by Employee</label>
                              <?php } ?> 
                              </div>
                              <div>
                              <?php if ((in_array('owner', $des)) || (in_array('cashier', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5ForAll" type="radio" id="radio_debitNoteForAll" class="with-gap radio-col-red" />
                                <label for="radio_debitNoteForAll">Debit Note</label>
                              <?php }else{ ?>
                                <input name="group5ForAll" type="radio" id="radio_debitNoteForAll" class="with-gap radio-col-red" disabled/>
                                <label for="radio_debitNoteForAll">Debit Note</label>
                              <?php } ?> 
                            </div>
                            </div>
                        </div>
                    </div>

                    <br>

                    <div id="srDivForAll" style="display: none" class="row cust-tbl">   
                    </div>
                    
                    <div id="chequeDivForAll" style="display: none" class="row cust-tbl">
                        <div class="col-md-12">
                            <div class="col-md-4">
                                    <b>Employee</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="chequeEmpForAll" tabindex="1" autocomplete="off"  list="chequeEmpListForAll" name="chequeEmpForAll" class="form-control" placeholder="Employee Name">   
                                    <datalist id="chequeEmpListForAll">
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
                                <div class="col-md-4">
                                    <b>Amount</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="chequeAmountForAll" tabindex="2" onkeypress="return numbersonly(event)" autocomplete="off" name="chequeAmountForAll" class="form-control" placeholder="Cheque Amount">   
                                    
                                  </div>
                                  </div>
                                </div> 
                               
                               
                            </div>
                            <div class="col-md-12">
                               <div class="col-md-4">
                                    <b>Cheque Number</b>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="chequeNumberForAll" tabindex="3" onkeyup="checkChars(); " onkeypress="return numbersonly(event)" autocomplete="off" name="chequeNumberForAll" class="form-control" placeholder="Cheque Number">   
                                    
                                  </div>
                                  <p id="error-nwlForAll"></p>
                                  </div>
                                </div> 

                               <div class="col-md-4">
                                    <b>Cheque Date</b>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="date" id="chequeDateForAll" tabindex="4" onkeypress="return numbersonly(event)" autocomplete="off" name="chequeDateForAll" class="form-control" placeholder="Cheque date">   
                                    
                                  </div>
                                  </div>
                                </div>

                                <div class="col-md-4">
                                    <b>Bank</b>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="chequeBankForAll" tabindex="5"  list="chequeBankListForAll" autocomplete="off" name="chequeBankForAll" class="form-control" placeholder="Cheque Bank">   
                                      <datalist id="chequeBankListForAll">
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
                              <p id="error-chk-allForAll"></p>
                                <div class="col-md-4">
                                  <button id="chequeSaveBtnForAll" type="button" tabindex="6"  class="btn btnStyle m-t-15 waves-effect">
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



                    <div id="neftDivForAll" style="display: none" class="row cust-tbl">
                        <div class="col-md-12">
                                <div class="col-md-4">
                                    <b>Employee</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="neftEmpForAll" autocomplete="off"  list="neftEmpListForAll" name="neftEmpForAll" class="form-control" placeholder="Employee Name">   
                                    <datalist id="neftEmpListForAll">
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
                                <div class="col-md-4">
                                    <b>Amount</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="neftAmountForAll" onkeypress="return numbersonly(event)" autocomplete="off" name="neftAmountForAll" class="form-control" placeholder="NEFT Amount">   
                                    
                                  </div>
                                  </div>
                                </div> 
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <b>NEFT Number</b>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="neftNumberForAll" autocomplete="off" onkeyup="checkNEFT();" name="neftNumberForAll" class="form-control" placeholder="NEFT Number">   
                                    
                                  </div>
                                  <div id="error-nwl1ForAll"></div>
                                </div> 
                                </div> 
                                <div class="col-md-4">
                                    <b>NEFT Date</b>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="date" id="neftDateForAll" onkeypress="return numbersonly(event)" autocomplete="off" name="neftDateForAll" class="form-control" placeholder="NEFT Date">   
                                    
                                  </div>
                                  </div>
                                </div> 
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <button id="neftSaveBtnForAll" type="button" class="btn btnStyle m-t-15 waves-effect">
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

                    <div id="cdDivForAll" style="display: none" class="row cust-tbl">
                        <div class="col-md-12">
                            <div class="col-md-4">
                                    <b>Employee</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="cdEmpForAll" autocomplete="off"  list="cdEmpListForAll" name="cdEmpForAll" class="form-control" placeholder="Employee Name">   
                                    <datalist id="cdEmpListForAll">
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
                                <div class="col-md-4">
                                    <b>CD Amount</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="cdAmountForAll" onkeypress="return numbersonly(event)" autocomplete="off" name="cdAmountForAll" class="form-control" placeholder="CD Amount">   
                                    
                                  </div>
                                  </div>
                                </div> 
                            </div>

                             <div class="col-md-12">
                                <div class="col-md-12">
                                    <b>Remark</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="cdRemarkForAll" onpaste="return removeSpaces(this)" autocomplete="off" name="cdRemarkForAll" class="form-control" placeholder="Remark">   
                                    
                                  </div>
                                  </div>
                                </div> 
                             </div>

                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <button id="cdSaveBtnForAll" type="button" class="btn btnStyle m-t-15 waves-effect">
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

                    <div id="debitDivForAll" style="display: none" class="row cust-tbl">
                        <div class="col-md-12">
                            <div class="col-md-4">
                                    <b>Employee</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="debitEmpForAll" autocomplete="off"  list="debitEmpListForAll" name="debitEmpForAll" class="form-control" placeholder="Employee Name">   
                                    <datalist id="debitEmpListForAll">
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
                                <div class="col-md-4">
                                    <b>Debit Amount</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="debitAmountForAll" onkeypress="return numbersonly(event)" autocomplete="off" name="debitAmountForAll" class="form-control" placeholder="Debit Amount">   
                                    
                                  </div>
                                  </div>
                                </div> 
                            </div>

                             <div class="col-md-12">
                                <div class="col-md-12">
                                    <b>Remark</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="debitRemarkForAll" autocomplete="off" name="debitRemarkForAll" class="form-control" placeholder="Remark">   
                                    
                                  </div>
                                  </div>
                                </div> 
                             </div>

                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <button id="debitSaveBtnForAll" type="button" class="btn btnStyle m-t-15 waves-effect">
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

                    <div id="debitNoteForAll" style="display: none" class="row cust-tbl">
                        <div class="col-md-12">
                            <div class="col-md-4">
                                    <b>Employee</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="debitNoteEmpForAll" autocomplete="off"  list="debitNoteEmpListForAll" name="debitNoteEmpForAll" class="form-control" placeholder="Employee Name">   
                                    <datalist id="debitNoteEmpListForAll">
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
                                <div class="col-md-4">
                                    <b>Debit Note Amount</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="debitNoteAmountForAll" onkeypress="return numbersonly(event)" autocomplete="off" name="debitNoteAmountForAll" class="form-control" placeholder="Debit Amount">   
                                    
                                  </div>
                                  </div>
                                </div> 
                            </div>

                             <div class="col-md-12">
                                <div class="col-md-12">
                                    <b>Remark</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="debitNoteRemarkForAll" autocomplete="off" name="debitNoteRemarkForAll" class="form-control" placeholder="Remark">   
                                    
                                  </div>
                                  </div>
                                </div> 
                             </div>

                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <button id="debitNoteSaveBtnForAll" type="button" class="btn btnStyle m-t-15 waves-effect">
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

                    <div id="officeAdjDivForAll" style="display: none" class="row cust-tbl">
                        <div class="col-md-12">
                            <div class="col-md-4">
                                    <b>Employee</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="officeAdjEmpForAll" autocomplete="off"  list="officeAdjListForAll" name="officeAdjEmpForAll" class="form-control" placeholder="Employee Name">   
                                    <datalist id="officeAdjListForAll">
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
                                <div class="col-md-4">
                                    <b>Office Adjustment Amount</b>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="officeAdjAmountForAll" onkeypress="return numbersonly(event)" autocomplete="off" name="officeAdjAmountForAll" class="form-control" placeholder="Office Adjustment Amount">   
                                    
                                  </div>
                                  </div>
                                </div> 
                            </div>

                             <div class="col-md-12">
                                <div class="col-md-12">
                                    <b>Remark</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="officeAdjRemarkForAll" autocomplete="off" name="officeAdjRemarkForAll" class="form-control" placeholder="Remark">   
                                    
                                  </div>
                                  </div>
                                </div> 
                             </div>

                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <button id="officeAdjSaveBtnForAll" type="button" class="btn btnStyle m-t-15 waves-effect">
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

                    <div id="otherAdjDivForAll" style="display: none" class="row cust-tbl">
                        <div class="col-md-12">
                            <div class="col-md-4">
                                    <b>Employee</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="otherAdjEmpForAll" autocomplete="off"  list="otherAdjEmpListForAll" name="otherAdjEmpForAll" class="form-control" placeholder="Employee Name">   
                                    <datalist id="otherAdjEmpListForAll">
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
                                <div class="col-md-4">
                                    <b>Other Adjustment Amount</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="otherAdjAmountForAll" onkeypress="return numbersonly(event)" autocomplete="off" name="otherAdjAmountForAll" class="form-control" placeholder="Other Adjustment Amount">   
                                    
                                  </div>
                                  </div>
                                </div> 
                            </div>

                             <div class="col-md-12">
                                <div class="col-md-12">
                                    <b>Remark</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="otherAdjRemarkForAll" autocomplete="off" name="otherAdjRemarkForAll" class="form-control" placeholder="Remark">   
                                    
                                  </div>
                                  </div>
                                </div> 
                             </div>

                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <button id="otherAdjSaveBtnForAll" type="button" class="btn btnStyle m-t-15 waves-effect">
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

                    <div id="empDeliveryDivForAll" style="display: none" class="row cust-tbl">
                        <div class="col-md-12">
                            <div class="col-md-4">
                                    <b>Employee</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="deliveryEmpForAll" autocomplete="off"  list="deliveryEmpListForAll" name="deliveryEmpForAll" class="form-control" placeholder="Employee Name">   
                                    <datalist id="deliveryEmpListForAll">
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
                                <div class="col-md-12">
                                    <b>Remark</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="deliveryRemarkForAll" autocomplete="off" name="deliveryRemarkForAll" class="form-control" placeholder="Remark">   
                                    
                                  </div>
                                  </div>
                                </div> 
                             </div>

                          
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <button id="deliveryEmpSaveBtnForAll" type="button" class="btn btnStyle m-t-15 waves-effect">
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

                    <div id="allocationDivForAll" style="display: none" class="row cust-tbl">
                        <div class="col-md-12">
                            <div class="col-md-4">
                                    <b>Open Allocations</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="allocationCodeForAll" autocomplete="off"  list="allocationCodeListForAll" name="allocationCodeForAll" class="form-control" placeholder="Select Allocation Number">   
                                    <datalist id="allocationCodeListForAll">
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
                                    <button id="allocationSaveBtnForAll" type="button" class="btn btnStyle m-t-15 waves-effect">
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

                    <div id="cashDivForAll" class="row cust-tbl">
                        <div class="col-md-12">
                                <div class="col-md-4">
                                    <b>Employee</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="cashEmpForAll" autocomplete="off"  list="cashEmpListForAll" name="cashEmpForAll" class="form-control" placeholder="Employee Name">   
                                    <datalist id="cashEmpListForAll">
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

                                <div class="col-md-4">
                                    <b>Cash Amount</b><span style="color:red">  *</span>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                      </span>

                                      <div class="form-line">
                                         <input type="text" id="tempCashAmtForAll" onkeypress="return numbersonly(event)" autocomplete="off" name="tempCashAmtForAll" class="form-control" placeholder="Cash Amount">   
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
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersWithDash(event)" onkeyup="calMoney();" type="text" name="add2000ForAll" id="add2000ForAll"autocomplete="off" class="form-control">
                                        </td>
                                        <td align="right"><span id="ad2000ForAll"></span></td>
                                   
                                        <!-- <td align="right">1000</td>
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersWithDash(event)" onkeyup="calMoney();" type="text" name="add1000" id="add1000"autocomplete="off" class="form-control">
                                        </td>
                                        <td align="right"><span id="ad1000"></span></td> -->

                                        <td align="right">500</td>
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersWithDash(event)" onkeyup="calMoney();" type="text" name="add500ForAll" id="add500ForAll"autocomplete="off" class="form-control">
                                        </td>
                                        <td align="right"><span id="ad500ForAll"></span></td>
                                    </tr>
                                     <tr>
                                        <td align="right">200</td>
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersWithDash(event)" onkeyup="calMoney();" type="text" name="add200ForAll" id="add200ForAll"autocomplete="off" class="form-control">
                                        </td>
                                        <td align="right"><span id="ad200ForAll"></span></td>

                                        <td align="right">100</td>
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersWithDash(event)" onkeyup="calMoney();" type="text" name="add100ForAll" id="add100ForAll"autocomplete="off" class="form-control">
                                        </td>
                                        <td align="right"><span id="ad100ForAll"></span></td>
                                    </tr>
                                     <tr>
                                        <td align="right">50</td>
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersWithDash(event)" onkeyup="calMoney();" type="text" name="add50ForAll" id="add50ForAll"autocomplete="off" class="form-control">
                                        </td>
                                        <td align="right"><span id="ad50ForAll"></span></td>

                                        <td align="right">20</td>
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersWithDash(event)" onkeyup="calMoney();" type="text" name="add20ForAll" id="add20ForAll" autocomplete="off" class="form-control">
                                        </td>
                                        <td align="right"><span id="ad20ForAll"></span></td>
                                    </tr>
                                     <tr>
                                        <td align="right">10</td>
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersWithDash(event)" onkeyup="calMoney();" type="text" name="add10ForAll" id="add10ForAll"autocomplete="off" class="form-control">
                                        </td>
                                        <td align="right"><span id="ad10ForAll"></span></td>

                                        <td align="right">Coins</td>
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersWithDash(event)" onkeyup="calMoney();" type="text" name="coinForAll" id="coinForAll"autocomplete="off" class="form-control">
                                        </td>
                                        <td align="right"><span id="coinsForAll"></span></td>
                                    </tr>
                                    <tr>
                                        
                                        <td align="right">Total Actual</td>
                                        <td align="right"><span id="totalActualForAll"></span></td>
                                        <td align="center">
                                            <input style="height:25px;width: 80%" type="hidden" name="collectedAmtForAll" id="collectedAmtForAll" class="form-control">
                                        </td>

                                        <td align="right">Short/Excess</td>
                                        <td align="right"><span id="shortExcesstotalActualForAll"></span></td>
                                        <td align="center">
                                          <td align="center">
                                              <input style="height:25px;width: 80%" type="hidden" name="shortExcessCollectedAmtForAll" id="shortExcessCollectedAmtForAll" class="form-control">
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
                                    <button id="cashSaveBtnForAll" type="button" class="btn btnStyle m-t-15 waves-effect">
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
                                    <button id="cashSaveBtnForAll" disabled type="button" class="btn btnStyle m-t-15 waves-effect">
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

<script type="text/javascript">
    $(document).on('click','#radio_chequeForAll',function(){
        $('#srDivForAll').css("display", "none");
        $('#cashDivForAll').css("display", "none");
        $('#neftDivForAll').css("display", "none");
        $('#cdDivForAll').css("display", "none");
        $('#debitDivForAll').css("display", "none");
        $('#officeAdjDivForAll').css("display", "none");
        $('#otherAdjDivForAll').css("display", "none");
        $('#allocationDivForAll').css("display", "none");
        $('#empDeliveryDivForAll').css("display", "none");
        $('#chequeDivForAll').css("display", "block");
        $('#debitNoteForAll').css("display", "none");
    });
   
    $(document).on('click','#radio_cashForAll',function(){
        $('#srDivForAll').css("display", "none");
        $('#cashDivForAll').css("display", "block");
        $('#neftDivForAll').css("display", "none");
        $('#chequeDivForAll').css("display", "none");
        $('#cdDivForAll').css("display", "none");
        $('#officeAdjDivForAll').css("display", "none");
        $('#otherAdjDivForAll').css("display", "none");
        $('#allocationDivForAll').css("display", "none");
        $('#debitDivForAll').css("display", "none");
        $('#empDeliveryDivForAll').css("display", "none");
        $('#debitNoteForAll').css("display", "none");

        $('#add2000ForAll').val("");
        // $('#add1000').val("");
        $('#add500ForAll').val("");
        $('#add200ForAll').val("");
        $('#add100ForAll').val("");
        $('#add50ForAll').val("");
        $('#add20ForAll').val("");
        $('#add10ForAll').val("");
        $('#coinForAll').val("");
    });

    $('#processModalForAll').on('hidden.bs.modal', function () {
      $(this)
          .find("input:not([type=hidden]),textarea,select")
          .val('')
          .end()
          .find("input[type=checkbox], input[type=radio]")
          .prop("checked", "")
          .end();

        $('#srDivForAll').css("display", "none");
        $('#cashDivForAll').css("display", "block");
        $('#neftDivForAll').css("display", "none");
        $('#chequeDivForAll').css("display", "none");
        $('#cdDivForAll').css("display", "none");
        $('#officeAdjDivForAll').css("display", "none");
        $('#otherAdjDivForAll').css("display", "none");
        $('#debitDivForAll').css("display", "none");
        $('#allocationDivForAll').css("display", "none");
        $('#empDeliveryDivForAll').css("display", "none");
        $('#radio_cashForAll').prop('checked', true);
        $('#debitNoteForAll').css("display", "none");
  });

    $(document).on('click','#radio_neftForAll',function(){
        $('#srDivForAll').css("display", "none");
        $('#cashDivForAll').css("display", "none");
        $('#neftDivForAll').css("display", "block");
        $('#chequeDivForAll').css("display", "none");
        $('#cdDivForAll').css("display", "none");
        $('#officeAdjDivForAll').css("display", "none");
        $('#otherAdjDivForAll').css("display", "none");
        $('#allocationDivForAll').css("display", "none");
        $('#debitDivForAll').css("display", "none");
        $('#empDeliveryDivForAll').css("display", "none");
        $('#debitNoteForAll').css("display", "none");
    });

    $(document).on('click','#radio_cdForAll',function(){
        $('#srDivForAll').css("display", "none");
        $('#cashDivForAll').css("display", "none");
        $('#neftDivForAll').css("display", "none");
        $('#chequeDivForAll').css("display", "none");
        $('#cdDivForAll').css("display", "block");
        $('#officeAdjDivForAll').css("display", "none");
        $('#otherAdjDivForAll').css("display", "none");
        $('#allocationDivForAll').css("display", "none");
        $('#debitDivForAll').css("display", "none");
        $('#empDeliveryDivForAll').css("display", "none");
        $('#debitNoteForAll').css("display", "none");
    });

    
    $(document).on('click','#radio_debitForAll',function(){
        $('#srDivForAll').css("display", "none");
        $('#cashDivForAll').css("display", "none");
        $('#neftDivForAll').css("display", "none");
        $('#chequeDivForAll').css("display", "none");
        $('#cdDivForAll').css("display", "none");
        $('#officeAdjDivForAll').css("display", "none");
        $('#otherAdjDivForAll').css("display", "none");
        $('#allocationDivForAll').css("display", "none");
        $('#debitDivForAll').css("display", "block");
        $('#empDeliveryDivForAll').css("display", "none");
        $('#debitNoteForAll').css("display", "none");
    });

    $(document).on('click','#radio_debitNoteForAll',function(){
        $('#srDivForAll').css("display", "none");
        $('#cashDivForAll').css("display", "none");
        $('#neftDivForAll').css("display", "none");
        $('#chequeDivForAll').css("display", "none");
        $('#cdDivForAll').css("display", "none");
        $('#officeAdjDivForAll').css("display", "none");
        $('#otherAdjDivForAll').css("display", "none");
        $('#allocationDivForAll').css("display", "none");
        $('#debitDivForAll').css("display", "none");
        $('#empDeliveryDivForAll').css("display", "none");
        $('#debitNoteForAll').css("display", "block");
    });

    $(document).on('click','#radio_officeAdjForAll',function(){
        $('#srDivForAll').css("display", "none");
        $('#cashDivForAll').css("display", "none");
        $('#neftDivForAll').css("display", "none");
        $('#chequeDivForAll').css("display", "none");
        $('#cdDivForAll').css("display", "none");
        $('#officeAdjDivForAll').css("display", "block");
        $('#otherAdjDivForAll').css("display", "none");
        $('#allocationDivForAll').css("display", "none");
        $('#debitDivForAll').css("display", "none");
        $('#empDeliveryDivForAll').css("display", "none");
        $('#debitNoteForAll').css("display", "none");

    });

    $(document).on('click','#radio_otherAdjForAll',function(){
        $('#srDivForAll').css("display", "none");
        $('#cashDivForAll').css("display", "none");
        $('#neftDivForAll').css("display", "none");
        $('#chequeDivForAll').css("display", "none");
        $('#cdDivForAll').css("display", "none");
        $('#officeAdjDivForAll').css("display", "none");
        $('#otherAdjDivForAll').css("display", "block");
        $('#allocationDivForAll').css("display", "none");
        $('#debitDivForAll').css("display", "none");
        $('#empDeliveryDivForAll').css("display", "none");
        $('#debitNoteForAll').css("display", "none");

    });

    $(document).on('click','#radio_srForAll',function(){
        $('#srDivForAll').css("display", "block");
        $('#cashDivForAll').css("display", "none");
        $('#neftDivForAll').css("display", "none");
        $('#chequeDivForAll').css("display", "none");
        $('#cdDivForAll').css("display", "none");
        $('#officeAdjDivForAll').css("display", "none");
        $('#otherAdjDivForAll').css("display", "none");
        $('#allocationDivForAll').css("display", "none");
        $('#debitDivForAll').css("display", "none");
        $('#empDeliveryDivForAll').css("display", "none");
        $('#debitNoteForAll').css("display", "none");

    });

    $(document).on('click','#radio_allocationForAll',function(){
        $('#srDivForAll').css("display", "none");
        $('#cashDivForAll').css("display", "none");
        $('#neftDivForAll').css("display", "none");
        $('#chequeDivForAll').css("display", "none");
        $('#cdDivForAll').css("display", "none");
        $('#officeAdjDivForAll').css("display", "none");
        $('#otherAdjDivForAll').css("display", "none");
        $('#allocationDivForAll').css("display", "block");
        $('#debitDivForAll').css("display", "none");
        $('#empDeliveryDivForAll').css("display", "none");
        $('#debitNoteForAll').css("display", "none");

    });

     $(document).on('click','#radio_EmpDeliveryForAll',function(){
        $('#srDivForAll').css("display", "none");
        $('#cashDivForAll').css("display", "none");
        $('#neftDivForAll').css("display", "none");
        $('#chequeDivForAll').css("display", "none");
        $('#cdDivForAll').css("display", "none");
        $('#officeAdjDivForAll').css("display", "none");
        $('#otherAdjDivForAll').css("display", "none");
        $('#allocationDivForAll').css("display", "none");
        $('#debitDivForAll').css("display", "none");
        $('#empDeliveryDivForAll').css("display", "block");
        $('#debitNoteForAll').css("display", "none");

    });
</script>

<script>
    $(document).on('click','#prDetailsForAll',function(){
        var id=$(this).attr('data-id');
        var billNo=$(this).attr('data-billNo');
        var retailerName=$(this).attr('data-retailerName');
        var pendingAmt=$(this).attr('data-pendingAmt');
        pendingAmt=parseInt(pendingAmt); 
       
        var gst=$(this).attr('data-gst');
        var route=$(this).attr('data-route');
        
        var credAdj=$(this).attr('data-credAdj');
        credAdj=parseInt(credAdj);
       
        if(credAdj>0){
           
            $('.logo_prov').text('CN:'+credAdj);
             $(".logo_prov").show();
        }else{
             $(".logo_prov").hide();
        }

        var billDate=$(this).attr('data-billDate');
        var salesman=$(this).attr('data-salesman');
        
        $('#currentPendingAmtForAll').val(pendingAmt);
        $('#currentBillIdForAll').val(id);
        $('#currentBillNoForAll').val(billNo);
        $('#currentBillRetailerForAll').val(retailerName);
        $('#bill_noForAll').text(billNo);
        $('#gstForAll').text(gst);
        $('#routeDetailForAll').text(route);
        $('#title_nameForAll').text(retailerName);
        // $('#bill_retailer').text(retailerName);
        $('#bill_pendingAmtForAll').text(pendingAmt);
        $('#bill-dateForAll').text(billDate);
         $('#bill-salesmanForAll').text(salesman);
         $('#bill-routeForAll').text(route);
    });
</script>


<script type="text/javascript">
    $(document).on('click','#radio_srForAll',function(){
        var currentBillId=$('#currentBillIdForAll').val();
        var currentBillNo=$('#currentBillNoForAll').val();
        var currentBillRetailer=$('#currentBillRetailerForAll').val();
        var billNoText=currentBillNo+' : '+currentBillRetailer;

        if(currentBillId==''){
            alert('Please enter bill no.');
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('BillTransactionController/getSrDetails');?>",
                data:{currentBillId:currentBillId},
                success: function (data) {
                    $('#srDivForAll').html(data);
                }  
            });
        }
    });
</script>

<script type="text/javascript">
  $(document).on('click','#fsrBtn',function(e){
    $("#fsrBtn").attr("disabled", true);
    if(!e.detail || e.detail == 1){
        e.preventDefault();
        var el = $(this);
        el.prop('disabled', true);
        setTimeout(function(){el.prop('disabled', false); }, 1000);

        var billId=$(this).attr('data-id');
        var currentBillId=$('#currentBillIdForAll').val();
        var currentBillNo=$('#currentBillNoForAll').val();
        var currentBillRetailer=$('#currentBillRetailerForAll').val();
        var billNoText=currentBillNo+' : '+currentBillRetailer;

        if(billId==''){
            alert('Please enter bill no.');
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('BillTransactionController/confirmFSR');?>",
                data:{billId:billId},
                success: function (data) {
                    if(data.trim()=="Record Updated"){
                        alert(data);
                        window.location.reload()
                        // window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
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
    $("#srBtn").attr("disabled", true);
    if(!e.detail || e.detail == 1){
         e.preventDefault();
         var el = $(this);
         el.prop('disabled', true);
         setTimeout(function(){el.prop('disabled', false); }, 1000);

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

      var currentBillId=$('#currentBillIdForAll').val();
      var currentBillNo=$('#currentBillNoForAll').val();
      var currentBillRetailer=$('#currentBillRetailerForAll').val();
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
                window.location.reload()
            //   window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
            }
          }  
      });
    }
  });
</script>



<script type="text/javascript">
    $(document).on('click','#cashSaveBtnForAll',function(e){
      $("#cashSaveBtnForAll").attr("disabled", true);

        e.preventDefault();
         var el = $(this);
         el.prop('disabled', true);
         setTimeout(function(){el.prop('disabled', false); }, 1000);

      // if(!e.detail || e.detail == 1){
        var a2000=$('#add2000ForAll').val().trim();
        // var a1000=$('#add1000').val();
        var a500=$('#add500ForAll').val().trim();
        var a200=$('#add200ForAll').val().trim();
        var a100=$('#add100ForAll').val().trim();
        var a50=$('#add50ForAll').val().trim();
        var a20=$('#add20ForAll').val().trim();
        var a10=$('#add10ForAll').val().trim();
        var coin=$('#coinForAll').val().trim();

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

        var empName=$('#cashEmpForAll').val();
        var empId = $('#cashEmpListForAll').find('option[value="'+empName+'"]').attr('id');
        var collectedAmt=$('#collectedAmtForAll').val();
        var shortExcessCollectedAmt=$('#shortExcessCollectedAmtForAll').val();
        var tempCashAmt=$('#tempCashAmtForAll').val().trim();
        tempCashAmt = tempCashAmt.replace(",","");

        if(tempCashAmt>shortExcessCollectedAmt && (shortExcessCollectedAmt !=0)){
          alert('Cash amount is less than entered amount');die();
        }

        if(collectedAmt>tempCashAmt && (shortExcessCollectedAmt !=0)){
          alert('Cash amount is more than entered amount');die();
        }
        
        var currentPendingAmt=$('#currentPendingAmtForAll').val();
        var currentBillId=$('#currentBillIdForAll').val();
        var currentBillNo=$('#currentBillNoForAll').val();
        var currentBillRetailer=$('#currentBillRetailerForAll').val();
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
                    $('#cashEmpForAll').val("");
                    alert(data);
                    window.location.reload()
                    // window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
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
    var chequeNo = document.getElementById('chequeNumberForAll').value;
    // var message = document.getElementById('error-nwl');
    var goodColor = "#66cc66";
    var badColor = "#ff6666";

    if (chequeNo.length===0) {
      document.getElementById('error-nwlForAll').innerHTML = ""
        return;
    } else if(chequeNo.length > 6 || chequeNo.length <6) {
        document.getElementById('error-nwlForAll').style.color = badColor;
        document.getElementById('error-nwlForAll').innerHTML = "Please enter only 6 digit!";
        return;
    } else {
        document.getElementById('error-nwlForAll').innerHTML = ""
        return;
    }
}

function dupChequeEntry(){
    var date = document.getElementById('chequeDateForAll').value;
    var chequeNo = document.getElementById('chequeNumberForAll').value;
    var bank = document.getElementById('chequeBankForAll').value;
    var billAmount = document.getElementById('chequeAmountForAll').value;
    var message = document.getElementById('error-nwlForAll').innerText;


    if((chequeNo !="" && bank !="" && billAmount !="")){
        $.ajax(
        {
            type:"post",
            url: "<?php echo base_url(); ?>index.php/CashAndChequeController/checkChequeDetails",
            data:{chequeDate:date,chequeNo:chequeNo,payAmount:billAmount},
            success:function(response)
            {
              if(response !=""){
                document.getElementById('error-nwlForAll').innerText = response;
                return false;
              }else{
                document.getElementById('error-nwlForAll').innerText = '';
                return true;
              }
            }
        });
      
    }
}
</script>



<script type="text/javascript">
    $(document).on('click','#chequeSaveBtnForAll',function(e){
      $("#chequeSaveBtnForAll").attr("disabled", true);
      if(!e.detail || e.detail == 1){
        e.preventDefault();
         var el = $(this);
         el.prop('disabled', true);
         setTimeout(function(){el.prop('disabled', false); }, 1000);

        var empName=$('#chequeEmpForAll').val();
        var empId = $('#chequeEmpListForAll').find('option[value="'+empName+'"]').attr('id');
        var chequeBank=$('#chequeBankForAll').val();
        var bankId="";
        if(chequeBank !==""){
           bankId = $('#chequeBankListForAll').find('option[value="'+chequeBank+'"]').attr('id');
        }
        
        var chequeAmount=$('#chequeAmountForAll').val();
        var chequeNumber=$('#chequeNumberForAll').val();
        var chequeDate=$('#chequeDateForAll').val();
        var currentPendingAmt=$('#currentPendingAmtForAll').val();
        var currentBillId=$('#currentBillIdForAll').val();
        var currentBillNo=$('#currentBillNoForAll').val();
        var currentBillRetailer=$('#currentBillRetailerForAll').val();
        var billNoText=currentBillNo+' : '+currentBillRetailer;

        var chequeAmount = Number(chequeAmount.replace(/,/g, '')); 

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
                        $('#chequeEmpForAll').val("");
                        $('#chequeAmountForAll').val("");
                        alert(data);
                        window.location.reload()
                        // window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
                    }else{
                        alert(data);
                    }
                }  
            });
        }else if(empName !=="" && chequeAmount !=="" && chequeBank !== "" && chequeNumber !=="" && chequeDate !==""){
            var err=$('#error-nwlForAll').text();
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
                                  $('#chequeEmpForAll').val("");
                                  $('#chequeAmountForAll').val("");
                                  alert(data);
                                  window.location.reload()
                                //   window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
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
                                  $('#chequeEmpForAll').val("");
                                  $('#chequeAmountForAll').val("");
                                  alert(data);
                                  window.location.reload()
                                //   window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
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
      var neftNo = document.getElementById('neftNumberForAll').value;
      var message = document.getElementById('error-nwl1ForAll');
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
    $(document).on('click','#neftSaveBtnForAll',function(e){
      $("#neftSaveBtnForAll").attr("disabled", true);
      if(!e.detail || e.detail == 1){
         e.preventDefault();
         var el = $(this);
         el.prop('disabled', true);
         setTimeout(function(){el.prop('disabled', false); }, 1000);

        var empName=$('#neftEmpForAll').val();
        var empId = $('#neftEmpListForAll').find('option[value="'+empName+'"]').attr('id');
        var neftAmount=$('#neftAmountForAll').val();
        var neftNumber=$('#neftNumberForAll').val();
        var neftDate=$('#neftDateForAll').val();
        var currentPendingAmt=$('#currentPendingAmtForAll').val();
        var currentBillId=$('#currentBillIdForAll').val();
        var currentBillNo=$('#currentBillNoForAll').val();
        var currentBillRetailer=$('#currentBillRetailerForAll').val();
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
                        $('#neftEmpForAll').val("");
                        $('#neftAmountForAll').val("");
                        alert(data);
                        $('#processModalForAll').modal('toggle');
                        window.location.reload()
                        // window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
                    }else{
                        alert(data);
                    }
                }  
            });
        }else if(empId !=="" && neftAmount !=="" && neftNumber !=="" && neftDate !==""){
            var err=$('#error-nwl1ForAll').text();
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
                          $("#neftSaveBtnForAll").attr("disabled", true);
                            $.ajax({
                                type: "POST",
                                url:"<?php echo site_url('BillTransactionController/neftCollection');?>",
                                data:{empName:empName,empId:empId,neftAmount:neftAmount,neftNumber:neftNumber,neftDate:neftDate,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId},
                                success: function (data) {
                                    if(data.trim()=="Record updated"){
                                        $('#neftEmpForAll').val("");
                                        $('#neftAmountForAll').val("");
                                        alert(data);
                                        $('#processModalForAll').modal('toggle');
                                        window.location.reload()
                                        // window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
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
                                    $('#neftEmpForAll').val("");
                                    $('#neftAmountForAll').val("");
                                    alert(data);
                                    $('#processModalForAll').modal('toggle');
                                    window.location.reload()
                                    // window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
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
  $(document).on('click','#allocationSaveBtnForAll',function(e){
    $("#allocationSaveBtnForAll").attr("disabled", true);
    if(!e.detail || e.detail == 1){
         e.preventDefault();
         var el = $(this);
         el.prop('disabled', true);
         setTimeout(function(){el.prop('disabled', false); }, 1000);

        var allocationCode=$('#allocationCodeForAll').val();
        var allocationId = $('#allocationCodeListForAll').find('option[value="'+allocationCode+'"]').attr('id');
        
        var currentPendingAmt=$('#currentPendingAmtForAll').val();
        var currentBillId=$('#currentBillIdForAll').val();
        var currentBillNo=$('#currentBillNoForAll').val();
        var currentBillRetailer=$('#currentBillRetailerForAll').val();

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
                    $('#allocationCodeForAll').val("");
                    alert(data);
                    // window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
                    window.location.reload()
                }else{
                    alert(data);
                }
            }  
        });
        }
        
    });
</script>

<script type="text/javascript">
    $(document).on('click','#cdSaveBtnForAll',function(e){
      $("#cdSaveBtnForAll").attr("disabled", true);
      if(!e.detail || e.detail == 1){

         e.preventDefault();
         var el = $(this);
         el.prop('disabled', true);
         setTimeout(function(){el.prop('disabled', false); }, 1000);

        var empName=$('#cdEmpForAll').val();
        var empId = $('#cdEmpListForAll').find('option[value="'+empName+'"]').attr('id');
        var cdAmount=$('#cdAmountForAll').val();
        var cdRemark=$('#cdRemarkForAll').val();
        var currentPendingAmt=$('#currentPendingAmtForAll').val();
        var currentBillId=$('#currentBillIdForAll').val();
        var currentBillNo=$('#currentBillNoForAll').val();
        var currentBillRetailer=$('#currentBillRetailerForAll').val();
        var billNoText=currentBillNo+' : '+currentBillRetailer;

        var cdAmount = Number(cdAmount.replace(/,/g, '')); 
        
        
        if(empName==''){
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
                    $('#cdAmountForAll').val("");
                    $('#cdRemarkForAll').val("");
                    alert(data);
                    // window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
                    window.location.reload()
                }else{
                    alert(data);
                }
            }  
        });
      }
        
    });
    
</script>

<script type="text/javascript">
    $(document).on('click','#debitSaveBtnForAll',function(e){
      $("#debitSaveBtnForAll").attr("disabled", true);
      if(!e.detail || e.detail == 1){
         e.preventDefault();
         var el = $(this);
         el.prop('disabled', true);
         setTimeout(function(){el.prop('disabled', false); }, 1000);

        var empName=$('#debitEmpForAll').val();
        var empId = $('#debitEmpListForAll').find('option[value="'+empName+'"]').attr('id');
        var debitAmount=$('#debitAmountForAll').val();
        var debitRemark=$('#debitRemarkForAll').val();
        var currentPendingAmt=$('#currentPendingAmtForAll').val();
        var currentBillId=$('#currentBillIdForAll').val();
        var currentBillNo=$('#currentBillNoForAll').val();
        var currentBillRetailer=$('#currentBillRetailerForAll').val();
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
                    $('#debitAmountForAll').val("");
                    $('#debitRemarkForAll').val("");
                    alert(data);
                    // window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
                    window.location.reload()
                }else{
                    alert(data);
                }
            }  
        });
        
        }
    });
    
</script>


<script type="text/javascript">
    $(document).on('click','#debitNoteSaveBtnForAll',function(e){
      $("#debitNoteSaveBtnForAll").attr("disabled", true);
      if(!e.detail || e.detail == 1){
         e.preventDefault();
         var el = $(this);
         el.prop('disabled', true);
         setTimeout(function(){el.prop('disabled', false); }, 1000);

        var empName=$('#debitNoteEmpForAll').val();
        var empId = $('#debitNoteEmpListForAll').find('option[value="'+empName+'"]').attr('id');
        var debitAmount=$('#debitNoteAmountForAll').val();
        var debitRemark=$('#debitNoteRemarkForAll').val();
        var currentPendingAmt=$('#currentPendingAmtForAll').val();
        var currentBillId=$('#currentBillIdForAll').val();
        var currentBillNo=$('#currentBillNoForAll').val();
        var currentBillRetailer=$('#currentBillRetailerForAll').val();
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
                    window.location.reload()
                }else{
                    alert(data);
                }
            }  
        });
        
        }
    });
    
</script>

<script type="text/javascript">
    $(document).on('click','#officeAdjSaveBtnForAll',function(e){
      $("#officeAdjSaveBtnForAll").attr("disabled", true);
      if(!e.detail || e.detail == 1){
         e.preventDefault();
         var el = $(this);
         el.prop('disabled', true);
         setTimeout(function(){el.prop('disabled', false); }, 1000);

        var empName=$('#officeAdjEmpForAll').val();
        var empId = $('#officeAdjListForAll').find('option[value="'+empName+'"]').attr('id');
        var officeAdjAmount=$('#officeAdjAmountForAll').val();
        var officeAdjRemark=$('#officeAdjRemarkForAll').val();
        var currentPendingAmt=$('#currentPendingAmtForAll').val();
        var currentBillId=$('#currentBillIdForAll').val();
        var currentBillNo=$('#currentBillNoForAll').val();
        var currentBillRetailer=$('#currentBillRetailerForAll').val();
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
                    $('#officeAdjAmountForAll').val("");
                    $('#officeAdjRemarkForAll').val("");
                    alert(data);
                    // window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
                    window.location.reload()
                }else{
                    alert(data);
                }

            }  
        });
        }
        
    });
    
</script>

<script type="text/javascript">
    $(document).on('click','#otherAdjSaveBtnForAll',function(e){
      $("#otherAdjSaveBtnForAll").attr("disabled", true);
      if(!e.detail || e.detail == 1){
        e.preventDefault();
         var el = $(this);
         el.prop('disabled', true);
         setTimeout(function(){el.prop('disabled', false); }, 1000);

        var empName=$('#otherAdjEmpForAll').val();
        var empId = $('#otherAdjEmpListForAll').find('option[value="'+empName+'"]').attr('id');
        var otherAdjAmount=$('#otherAdjAmountForAll').val();
        var otherAdjRemark=$('#otherAdjRemarkForAll').val();
        var currentPendingAmt=$('#currentPendingAmtForAll').val();
        var currentBillId=$('#currentBillIdForAll').val();
        var currentBillNo=$('#currentBillNoForAll').val();
        var currentBillRetailer=$('#currentBillRetailerForAll').val();
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
                    $('#otherAdjAmountForAll').val("");
                    $('#otherAdjRemarkForAll').val("");
                    alert(data);
                    // window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
                    window.location.reload()
                }else{
                    alert(data);
                }
            }  
        });
        }
        
    });
    
</script>

<script type="text/javascript">
    $(document).on('click','#deliveryEmpSaveBtnForAll',function(e){
       $("#deliveryEmpSaveBtnForAll").attr("disabled", true);
      if(!e.detail || e.detail == 1){
         
         e.preventDefault();
         var el = $(this);
         el.prop('disabled', true);
         setTimeout(function(){el.prop('disabled', false); }, 1000);

        var empName=$('#deliveryEmpForAll').val();
        var empId = $('#deliveryEmpListForAll').find('option[value="'+empName+'"]').attr('id'); 
        var deliveryAmount=$('#currentPendingAmtForAll').val();
        var deliveryRemark=$('#deliveryRemarkForAll').val();
        var currentPendingAmt=$('#currentPendingAmtForAll').val();
        var currentBillId=$('#currentBillIdForAll').val();
        var currentBillNo=$('#currentBillNoForAll').val();
        var currentBillRetailer=$('#currentBillRetailerForAll').val();
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
                if(data.trim()=="Record updated"){
                    $('#deliveryAmountForAll').val("");
                    $('#deliveryRemarkForAll').val("");
                    alert(data);
                    window.location.reload()
                    // window.location.href="<?php echo base_url();?>index.php/SrController/outstandingBills";
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
    var a2000 = document.getElementById('add2000ForAll').value;
    // var a1000 = document.getElementById('add1000').value;
    var a500 = document.getElementById('add500ForAll').value;
    var a200 = document.getElementById('add200ForAll').value;
    var a100 = document.getElementById('add100ForAll').value;
    var a50 = document.getElementById('add50ForAll').value;
    var a20 = document.getElementById('add20ForAll').value;
    var a10 = document.getElementById('add10ForAll').value;
    var coin = document.getElementById('coinForAll').value;

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

    document.getElementById('ad2000ForAll').innerHTML = c1;
    // document.getElementById('ad1000').innerHTML = c2;
    document.getElementById('ad500ForAll').innerHTML = c3;
    document.getElementById('ad200ForAll').innerHTML = c4;
    document.getElementById('ad100ForAll').innerHTML = c5;
    document.getElementById('ad50ForAll').innerHTML = c6;
    document.getElementById('ad20ForAll').innerHTML = c7;
    document.getElementById('ad10ForAll').innerHTML = c8;
    document.getElementById('coinsForAll').innerHTML = c9;
    var total=0;
    total=total+c1+c3+c4+c5+c6+c7+c8;
    total=parseFloat(total)+parseFloat(c9);

    document.getElementById('totalActualForAll').innerHTML = total;
    
    document.getElementById('collectedAmtForAll').value= total;

    var tempCashAmt=document.getElementById('tempCashAmtForAll').value; 
    tempCashAmt = tempCashAmt.replace(",", "");

    var final_short=parseFloat(total)-parseFloat(tempCashAmt);
    if(tempCashAmt<total){
      document.getElementById('shortExcessCollectedAmtForAll').value= (final_short);
      document.getElementById('shortExcesstotalActualForAll').innerHTML = '<span style="color:green">'+(total-tempCashAmt)+'</span>';
      
    }else{
      document.getElementById('shortExcessCollectedAmtForAll').value= (final_short);
      document.getElementById('shortExcesstotalActualForAll').innerHTML = '<span style="color:red">'+(total-tempCashAmt)+'</span>';
    }
    
  }
</script>

<script>
    function checkQtyPerItem(qty,no,billQty,fsQty,netAmount,pendingAmt,prevSR,prevReceived,cash,cheque,neft,sr){
        var totalQty=parseInt(fsQty)+parseInt(qty.value);
        
        var qty_id=document.getElementById('qty_id'+no).value;
        var netAmount_id=document.getElementById('netAmount_id'+no).value;
        var avg=parseFloat(netAmount_id)/parseInt(qty_id);
        var currentQty=parseInt(document.getElementById('returnedQty'+no).value);
        var currentSrAmt=avg*currentQty;
        var totalCollection=parseFloat(prevSR)+parseFloat(prevReceived)+parseFloat(cash)+parseFloat(cheque)+parseFloat(neft)+parseFloat(sr)+parseFloat(currentSrAmt);
        
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

<script type="text/javascript">
  function removeSpaces(string) {
    return string.split(' ').join(' ');
  }
</script>