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

</style>
  
    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
            </div>
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                              Distributors
                            </h2>
                             <h2>
                               <p align="right">
                                    <button class="btn btn-sm bg-primary margin" onClick="window.location.reload();"><i class="material-icons">refresh</i></button>
                                    <a href="<?php echo site_url('owner/DistributorController/addDistributor');?>">
                                        <button type="submit" class="btn bg-primary margin"><i class="material-icons">person_add</i>  Add Distributor </button>
                                    </a> 
                                </p> 
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-exportable dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Name</th>
                                            <th>Code</th>
                                            <th>Mobile</th>
                                            <th>Email</th>
                                            <th>Telephone</th>
                                            <th>Address</th>
                                             <th>Url</th>
                                            <th>Database</th>
                                             <th>Status</th>
                                             <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Name</th>
                                            <th>Code</th>
                                            <th>Mobile</th>
                                            <th>Email</th>
                                            <th>Telephone</th>
                                            <th>Address</th>
                                            <th>Url</th>
                                            <th>Database</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php 
                                            $no=0;
                                            if(!empty($distributors_details)){
                                                foreach($distributors_details as $data){
                                                    $no++;
                                            ?>
                                            <tr>
                                                <td><?php echo $no;?></td>
                                                <td><?php echo $data['name'];?></td>
                                                <td><?php echo $data['code'];?></td>
                                                <td><?php echo $data['mobile'];?></td>
                                                <td><?php echo $data['email'];?></td>
                                                <td><?php echo $data['telephone'];?></td>
                                                <td><?php echo $data['address'];?></td>
                                                <td><?php echo $data['baseUrl'];?></td>
                                                <td><?php echo $data['databaseName'];?></td>
                                                <td>
                                                <?php if($data['status']==1){?>
                                                    <a href='<?php echo site_url("owner/DistributorController/updateStatus/".$data['id']."/1");?>'>
                                                        <i class="material-icons" style="color: green;">add_circle</i>
                                                    </a>
                                                <?php } else { ?>
                                                    <a href='<?php echo site_url("owner/DistributorController/updateStatus/".$data['id']."/0");?>'>
                                                        <i class="material-icons" style="color: red;">cancel</i>
                                                    </a>
                                                <?php } ?>
                                                </td>
                                                <td>
                                                    <a href='<?php echo site_url("owner/DistributorController/loadDistributor/".$data['id']);?>'>
                                                        <i class="material-icons" style="color: green;">edit</i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php 
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
        </div>
    </section>

<?php 
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
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <b>Retailer Code : </b>
                                        <span id='retCode'></span>
                                </div> 

                                <div class="col-md-3">
                                    <b>Route : </b>
                                        <span id='retRoute'></span>
                                </div>

                                 <div class="col-md-3">
                                    <b>Salesman : </b><span id='retSales'></span>
                                </div>
                            </div>
                        </div>
                         
                         <br>
                           <div class="row">
                            
                        <div class="col-md-12">
                            <div class="col-md-12">
                            <?php if ((in_array('owner', $des)) || (in_array('cashier', $des))){ ?>
                                <input name="group5" type="radio" id="radio_cash" class="with-gap radio-col-red" checked />
                                <label for="radio_cash">Cash</label>
                            <?php }else{ ?>  
                                <input name="group5" type="radio" id="radio_cash" class="with-gap radio-col-red" disabled />
                                <label for="radio_cash">Cash</label>
                            <?php } ?>   
                           
                            <?php if ((in_array('owner', $des)) || (in_array('cashier', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5" type="radio" id="radio_cheque" class="with-gap radio-col-red"  />
                                <label for="radio_cheque">Cheque</label>
                            <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_cheque" class="with-gap radio-col-red" disabled />
                                <label for="radio_cheque">Cheque</label>
                            <?php } ?> 

                            <?php if ((in_array('owner', $des)) || (in_array('cashier', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5" type="radio" id="radio_neft" class="with-gap radio-col-red"  />
                                <label for="radio_neft">NEFT</label>
                            <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_neft" class="with-gap radio-col-red" disabled />
                                <label for="radio_neft">NEFT</label>
                            <?php } ?> 

                            <?php if ((in_array('owner', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5" type="radio" id="radio_cd" class="with-gap radio-col-red"  />
                                <label for="radio_cd">CD</label>
                            <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_cd" class="with-gap radio-col-red" disabled />
                                <label for="radio_cd">CD</label>
                            <?php } ?> 

                            <?php if ((in_array('owner', $des)) || (in_array('cashier', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5" type="radio" id="radio_debit" class="with-gap radio-col-red" />
                                <label for="radio_debit">Debit</label>
                            <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_debit" class="with-gap radio-col-red" disabled/>
                                <label for="radio_debit">Debit</label>
                            <?php } ?> 

                            <?php if ((in_array('owner', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5" type="radio" id="radio_officeAdj" class="with-gap radio-col-red" />
                                <label for="radio_officeAdj">Office Adjustment</label>
                            <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_officeAdj" class="with-gap radio-col-red" disabled/>
                                <label for="radio_officeAdj">Office Adjustment</label>
                            <?php } ?> 

                            <?php if ((in_array('owner', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5" type="radio" id="radio_otherAdj" class="with-gap radio-col-red" />
                                <label for="radio_otherAdj">Other Adjustment</label>
                            <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_otherAdj" class="with-gap radio-col-red" disabled/>
                                <label for="radio_otherAdj">Other Adjustment</label>
                            <?php } ?> 

                            <?php if ((in_array('owner', $des)) || (in_array('godownkeeper', $des))){ ?>
                                <input name="group5" type="radio" id="radio_sr" class="with-gap radio-col-red"  />
                                <label for="radio_sr">SR/FSR</label>
                            <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_sr" class="with-gap radio-col-red" disabled />
                                <label for="radio_sr">SR/FSR</label>
                            <?php } ?> 
                            
                            <?php if ((in_array('owner', $des)) || (in_array('manager', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5" type="radio" id="radio_allocation" class="with-gap radio-col-red"  />
                                <label for="radio_allocation">Add to Open Allocation</label>
                            <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_allocation" class="with-gap radio-col-red"  disabled/>
                                <label for="radio_allocation">Add to Open Allocation</label>
                            <?php } ?> 

                            <?php if ((in_array('owner', $des)) || (in_array('manager', $des)) || (in_array('senior_manager', $des))){ ?>
                                <input name="group5" type="radio" id="radio_EmpDelivery" class="with-gap radio-col-red"  />
                                <label for="radio_EmpDelivery">Direct Delivery by Employee</label>
                            <?php }else{ ?>
                                <input name="group5" type="radio" id="radio_EmpDelivery" class="with-gap radio-col-red" disabled />
                                <label for="radio_EmpDelivery">Direct Delivery by Employee</label>
                            <?php } ?> 


                            </div>
                             

                        </div>
                    </div>

                    <br>

                    <div id="srDiv" style="display: none" class="row">
                       
                    </div>
                    

                    <div id="chequeDiv" style="display: none" class="row">
                        <div class="col-md-12">
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

                                <div class="col-md-4">
                                    <b>Cheque Number</b>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="text" id="chequeNumber" onkeyup="checkChars(); " onkeypress="return numbersonly(event)" autocomplete="off" name="chequeNumber" class="form-control" placeholder="Cheque Number">   
                                    
                                  </div>
                                  <p id="error-nwl"></p>
                                  </div>
                                </div> 

                            </div>

                            <div class="col-md-12">
                               <div class="col-md-4">
                                    <b>Cheque Date</b>
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                       <i class="material-icons">person</i>
                                     </span>

                                     <div class="form-line">
                                     <input type="date" id="chequeDate" onkeypress="return numbersonly(event)" autocomplete="off" name="chequeDate" class="form-control" placeholder="Cheque date">   
                                    
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
                                     <input type="text" id="chequeBank" onblur="dupChequeEntry();" list="chequeBankList" autocomplete="off" name="chequeBank" class="form-control" placeholder="Cheque date">   
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
                                <div class="col-md-4">
                                    <button id="chequeSaveBtn" type="button" class="btn btn-primary m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-primary m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                             </div>
                    </div>



                    <div id="neftDiv" style="display: none" class="row">
                        <div class="col-md-12">
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
                                    <button id="neftSaveBtn" type="button" class="btn btn-primary m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-primary m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                    </div>

                    <div id="cdDiv" style="display: none" class="row">
                        <div class="col-md-12">
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

                             <div class="col-md-12">
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
                                    <button id="cdSaveBtn" type="button" class="btn btn-primary m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-primary m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                    </div>

                    <div id="debitDiv" style="display: none" class="row">
                        <div class="col-md-12">
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

                             <div class="col-md-12">
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
                                    <button id="debitSaveBtn" type="button" class="btn btn-primary m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-primary m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                    </div>

                    <div id="officeAdjDiv" style="display: none" class="row">
                        <div class="col-md-12">
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
                                     <input type="text" id="officeAdjAmount" onkeypress="return numbersonly(event)" autocomplete="off" name="officeAdjAmount" class="form-control" placeholder="Office Adjustment Amount">   
                                    
                                  </div>
                                  </div>
                                </div> 
                            </div>

                             <div class="col-md-12">
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
                                    <button id="officeAdjSaveBtn" type="button" class="btn btn-primary m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-primary m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                    </div>

                    <div id="otherAdjDiv" style="display: none" class="row">
                        <div class="col-md-12">
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

                             <div class="col-md-12">
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
                                    <button id="otherAdjSaveBtn" type="button" class="btn btn-primary m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-primary m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                    </div>

                    <div id="empDeliveryDiv" style="display: none" class="row">
                        <div class="col-md-12">
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
                                    <button id="deliveryEmpSaveBtn" type="button" class="btn btn-primary m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-primary m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                    </div>

                    <div id="allocationDiv" style="display: none" class="row">
                        <div class="col-md-12">
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
                                    <button id="allocationSaveBtn" type="button" class="btn btn-primary m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-primary m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                    </div>

                    <div id="cashDiv" class="row">
                        <div class="col-md-12">
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
                                <table style="font-size: 13px" class="table table-bordered table-striped table-hover" data-page-length='100'>
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
                                        
                                         
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersonly(event)" onkeyup="calMoney();" type="text" name="add2000" id="add2000"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad2000"></span></td>
                                   
                                        <td align="right">1000</td>
                                        
                                         
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersonly(event)" onkeyup="calMoney();" type="text" name="add1000" id="add1000"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad1000"></span></td>
                                    </tr>
                                     <tr>
                                        <td align="right">500</td>
                                         
                                          
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersonly(event)" onkeyup="calMoney();" type="text" name="add500" id="add500"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad500"></span></td>
                                    
                                        <td align="right">200</td>
                                         
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersonly(event)" onkeyup="calMoney();" type="text" name="add200" id="add200"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad200"></span></td>
                                    </tr>
                                     <tr>
                                        <td align="right">100</td>
                                         
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersonly(event)" onkeyup="calMoney();" type="text" name="add100" id="add100"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad100"></span></td>
                                   
                                        <td align="right">50</td>
                                         
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersonly(event)" onkeyup="calMoney();" type="text" name="add50" id="add50"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad50"></span></td>
                                    </tr>
                                     <tr>
                                        <td align="right">20</td>
                                         
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersonly(event)" onkeyup="calMoney();" type="text" name="add20" id="add20" autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad20"></span></td>
                                   
                                        <td align="right">10</td>
                                         
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersonly(event)" onkeyup="calMoney();" type="text" name="add10" id="add10"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad10"></span></td>
                                    </tr>
                                    <tr>
                                        <td align="right">Coins</td>
                                         
                                        <td><input style="height:25px;width: 80%" onkeypress="return numbersonly(event)" onkeyup="calMoney();" type="text" name="coin" id="coin"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="coins"></span></td>
                                        <td align="right">Total Actual</td>
                                        <td align="right"><span id="totalActual"></span></td>
                                        <td align="center">
                                            <input style="height:25px;width: 80%" type="hidden" name="collectedAmt" id="collectedAmt" class="form-control">
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
                                    <button id="cashSaveBtn" type="button" class="btn btn-primary m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-primary m-t-15 waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </button>
                                </div>
                            </div>
                        <?php } else { ?>
                             <div class="row clearfix">
                                <div class="col-md-5">
                                    <button id="cashSaveBtn" disabled type="button" class="btn btn-primary m-t-15 waves-effect">
                                        <i class="material-icons">save</i> 
                                        <span class="icon-name"> Save</span>
                                    </button>
                               
                                    <button data-dismiss="modal" type="button" class="btn btn-primary m-t-15 waves-effect">
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
    });
</script>

<script>

    $(document).on('click','#prDetails',function(){
        var id=$(this).attr('data-id');
        var billNo=$(this).attr('data-billNo');
        var retailerName=$(this).attr('data-retailerName');
        var pendingAmt=$(this).attr('data-pendingAmt');
        var gst=$(this).attr('data-gst');

        var saleman=$(this).attr('data-salesman');
        var retailerCode=$(this).attr('data-code');
        var retailerRoute=$(this).attr('data-route');
        
        $('#currentPendingAmt').val(pendingAmt);
        $('#currentBillId').val(id);
        $('#currentBillNo').val(billNo);
        $('#currentBillRetailer').val(retailerName);
        $('#bill_no').text(billNo);
        $('#gst').text(gst);
       
        $('#bill_retailer').text(retailerName);
        $('#bill_pendingAmt').text(pendingAmt);

        $('#retCode').text(retailerCode);
        $('#retRoute').text(retailerRoute);
        $('#retSales').text(saleman);
    });


 $(document).ready(function(){
    $('#prDetails').click(function(){
        var id=$(this).attr('data-id');

        // alert(id);die();
            $.ajax({
                url : "<?php echo site_url('BillTransactionController/neftModal');?>",
                method : "POST",
                data : {id: id},
                success: function(data){
                  $('.mods').html(data);
                }
            });
    });
});
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
                url:"<?php echo site_url('BillTransactionController/confirmFSR');?>",
                data:{billId:billId},
                success: function (data) {
                    if(data.trim()=="Record Updated"){
                        alert(data);
                        window.location.href="<?php echo base_url();?>index.php/SrController/unclearedNeft";
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
          url:"<?php echo site_url('BillTransactionController/updateSRCreditAdj');?>",
          data:{billId:billId,productName:productName,mrp:mrp,qty:qty,fsReturnQty:fsReturnQty,netAmount:netAmount,selAmount:selAmount,id:id,returnedQty:returnedQty,returnAmt:returnAmt,currentBillId:currentBillId},
          success: function (data) {
            // alert(data);
            if(data.trim()=="SR Amount is greater than pending amount."){
              alert(data);
            }
              window.location.href="<?php echo base_url();?>index.php/SrController/unclearedNeft";
          }  
      });
  });
</script>

<script type="text/javascript">
    $(document).on('click','#cashSaveBtn',function(){
        var a2000=$('#add2000').val();
        var a1000=$('#add1000').val();
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

        var empName=$('#cashEmp').val();
        var empId = $('#cashEmpList').find('option[value="'+empName+'"]').attr('id');
        var collectedAmt=$('#collectedAmt').val();
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

        if(collectedAmt<0 ){
            alert('Please enter correct cash details.');die();
        }

        if(parseInt(currentPendingAmt) < parseInt(collectedAmt)){
            alert('Cash amount should not be greater than Pending amount');die();
        }
        
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('BillTransactionController/cashCollection');?>",
            data:{empName:empName,empId:empId,collectedAmt:collectedAmt,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId,a2000:a2000,a1000:a1000,a500:a500,a200:a200,a100:a100,a50:a50,a20:a20,a10:a10,coin:coin},
            success: function (data) {
                $('#cashEmp').val("");
                if(data.trim()=="Record updated"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/SrController/unclearedNeft";
                }else{
                    alert(data);
                }
            }  
        });
        
        
    });
    
</script>


<script>
function checkChars()
{
    var chequeNo = document.getElementById('chequeNumber').value;
    // var message = document.getElementById('error-nwl');
    var goodColor = "#66cc66";
    var badColor = "#ff6666";
    
    if(chequeNo.length > 6 || chequeNo.length <6)
    {
        document.getElementById('error-nwl').style.color = badColor;
        document.getElementById('error-nwl').innerHTML = "Please enter only 6 digit!";
        return;
    }else{
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
      
    }else{
      alert('Please enter all details');
      return false;
    }
}
</script>

<script type="text/javascript">
    $(document).on('click','#chequeSaveBtn',function(){
        var empName=$('#chequeEmp').val();
        var empId = $('#chequeEmpList').find('option[value="'+empName+'"]').attr('id');
        var chequeBank=$('#chequeBank').val();
        var bankId = $('#chequeBankList').find('option[value="'+chequeBank+'"]').attr('id');
        var chequeAmount=$('#chequeAmount').val();
        var chequeNumber=$('#chequeNumber').val();
        var chequeDate=$('#chequeDate').val();
        var currentPendingAmt=$('#currentPendingAmt').val();
        var currentBillId=$('#currentBillId').val();
        var currentBillNo=$('#currentBillNo').val();
        var currentBillRetailer=$('#currentBillRetailer').val();
        var billNoText=currentBillNo+' : '+currentBillRetailer;
        
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
            type: "POST",
            url:"<?php echo site_url('BillTransactionController/chequeCollection');?>",
            data:{empName:empName,empId:empId,chequeBank:chequeBank,chequeAmount:chequeAmount,chequeNumber:chequeNumber,chequeDate:chequeDate,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId},
            success: function (data) {
                $('#chequeEmp').val("");
                $('#chequeAmount').val("");
                if(data.trim()=="Record updated"){
                    alert(data);
                   window.location.href="<?php echo base_url();?>index.php/SrController/unclearedNeft";
                }else{
                    alert(data);
                }
            }  
        });
        
        
    });
    
</script>

<script type="text/javascript">
    $(document).on('click','#neftSaveBtn',function(){
        var empName=$('#neftEmp').val();
        var empId = $('#neftEmpList').find('option[value="'+empName+'"]').attr('id');
        var neftAmount=$('#neftAmount').val();
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

        if(neftAmount=='' || neftAmount==0){
            alert('Please enter cheque amount.');die();
        }

        if(parseInt(currentPendingAmt) < parseInt(neftAmount)){
            alert('Cheque amount should not be greater than Pending amount');die();
        }
        
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('BillTransactionController/neftCollection');?>",
            data:{empName:empName,empId:empId,neftAmount:neftAmount,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId},
            success: function (data) {
                $('#neftEmp').val("");
                $('#neftAmount').val("");
                if(data.trim()=="Record updated"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/SrController/unclearedNeft";
                }else{
                    alert(data);
                }
            }  
        });
        
        
    });
    
</script>

<script type="text/javascript">
  $(document).on('click','#allocationSaveBtn',function(){
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
                $('#allocationCode').val("");
                if(data.trim()=="Record inserted"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/SrController/unclearedNeft";
                }else{
                    alert(data);
                }
            }  
        });
        
        
    });
</script>

<script type="text/javascript">
    $(document).on('click','#cdSaveBtn',function(){
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
                $('#cdAmount').val("");
                $('#cdRemark').val("");
                if(data.trim()=="Record updated"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/SrController/unclearedNeft";
                }else{
                    alert(data);
                }
            }  
        });
        
        
    });
    
</script>

<script type="text/javascript">
    $(document).on('click','#debitSaveBtn',function(){
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
                $('#debitAmount').val("");
                $('#debitRemark').val("");
                if(data.trim()=="Record updated"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/SrController/unclearedNeft";
                }else{
                    alert(data);
                }
            }  
        });
        
        
    });
    
</script>

<script type="text/javascript">
    $(document).on('click','#officeAdjSaveBtn',function(){
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
            alert('Please select employee.');die();
        }else if(empName!=''){
            if (typeof empId === "undefined") {
                alert('Please select correct employee.');die();
            }
        }

        if(officeAdjAmount=='' || officeAdjAmount==0){
            alert('Please enter Office adjustment.');die();
        }

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
                $('#officeAdjAmount').val("");
                $('#officeAdjRemark').val("");
                if(data.trim()=="Record updated"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/SrController/unclearedNeft";
                }else{
                    alert(data);
                }

            }  
        });
        
        
    });
    
</script>

<script type="text/javascript">
    $(document).on('click','#otherAdjSaveBtn',function(){
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
            alert('Please select employee.');die();
        }else if(empName!=''){
            if (typeof empId === "undefined") {
                alert('Please select correct employee.');die();
            }
        }

        if(otherAdjAmount=='' || otherAdjAmount==0){
            alert('Please enter Office adjustment.');die();
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
                $('#otherAdjAmount').val("");
                $('#otherAdjRemark').val("");
                if(data.trim()=="Record updated"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/SrController/unclearedNeft";
                }else{
                    alert(data);
                }
            }  
        });
        
        
    });
    
</script>

<script type="text/javascript">
    $(document).on('click','#deliveryEmpSaveBtn',function(){
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
                $('#deliveryAmount').val("");
                $('#deliveryRemark').val("");
                if(data.trim()=="Record updated"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/SrController/unclearedNeft";
                }else{
                    alert(data);
                }
            }  
        });
        
    });
    
</script>

<script>
     function numbersonly(e){
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 45 || charCode > 57) ) {
            return false;
        }
        return true;
    }
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

    document.getElementById('totalActual').innerHTML = total;
    document.getElementById('collectedAmt').value= total;
    
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


<script type="text/javascript">
    $(document).on('click','#neft_id',function(){
         var id=$(this).attr('data-id');
         var bill=$(this).attr('data-bill');
         var retailer=$(this).attr('data-retailer');
         var amount=$(this).attr('data-amount');
         
         $.ajax({
            url : "<?php echo site_url('SrController/updateNeftForm');?>",
            method : "POST",
            data : {id: id,bill:bill,retailer:retailer,amount:amount},
            success: function(data){
                $('#up_limitid').html(data);
            }
        });
    });

 </script>

 <script type="text/javascript">
   
   function chkMsg(){

      var date = document.getElementById('neftDate').value;
      var neftNum = document.getElementById('neftNum').value;

      if((neftNum !="" && date !="")){
            return true;
      }else{
            alert('Please enter all details');
            return false;
      }
  }
 </script>

<script type="text/javascript">
  function removeMe(t) {
        $(t).closest('tr').remove();
  }


  function lostChequeStatus(e,id){
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('SrController/changeLostChequeStatus');?>",
                data:{"billPaymentId" : id},
                success: function (data) {
                    // document.getElementById('err').innerHTML=data;
                }  
            });
        }
    }
</script>

<script type="text/javascript">
    $( "#cmp").click(function() {
      $( "#cmp" ).select();
    });
</script>