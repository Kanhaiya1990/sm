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
     border: 1px solid black;
    background: red;
    color: black;
    padding: 6px;
    width: 50px;
    height: 50px;
}
</style>

 <script>
    $(document).ready(function() {
    $.fn.dataTable.ext.errMode = 'none';
    $('#xpdata').DataTable( {
        dom: 'Bfrtip',
        stateSave: false,
            buttons: [{
                    extend: 'pdf',
                    title: 'Employee Clearance',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                },{
                    extend: 'excel',
                    title: 'Employee Clearance',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                  
                }, {
                    extend: 'csv',
                    title: 'Employee Clearance',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                }
            ]
        
    } );
} );
</script>


    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>    
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
           
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                             Employee Clearance
                            </h2>
                       
                        </div>
                      
                        <div class="body">
                            
                            <div class="row clearfix">
                            <!-- <div class="demo-masked-input"> -->
                                
                                  <div class="col-md-12"> 
                                    <form id="formidForSubmit" action="<?php echo site_url('manager/EmployeeController/employeeClearance');?>" method="post">
                                    <div class="col-md-6">
                                        <b>Employee</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text"  autocomplete="off" id="employee" name="employee" class="form-control date" placeholder="Enter employee Name" list="empData" required>
                                                <datalist id="empData">
                                                <?php
                                                    foreach($employee as $data){
                                                        $name=$data['name'];
                                                ?>   
                                                
                                                <option id="<?php echo $data['id']; ?>" value="<?php echo $name;?>"/>
                                                <?php    
                                                    }
                                                ?>
                                            </datalist>
                                            <input type="hidden" id="eid" name="eid">
                                               
                                            </div>
                                            <p id="billNo_Id"></p>
                                        </div>
                                    </div>
                                  
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-primary m-t-15 waves-effect">
                                                <i class="material-icons">search</i> 
                                                <span class="icon-name">
                                                 Search
                                                </span>
                                            </button>
                                           <a href="<?php echo site_url('manager/EmployeeController/employeeClearance');?>">
                                                <button type="button" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">cancel</i> 
                                                    <span class="icon-name"> Cancel</span>
                                                </button>
                                            </a> 
                                        </div>

                                        
                                    </div> 
                                     <div class="col-md-12">

                                        <div class="table-responsive"> 
                                            <table id="xpdata" style="font-size: 11px" class="table table-bordered table-striped table-hover js-exportable datatable" data-page-length='100'>
                                                    <thead>
                                                        <tr>
                                                            <!-- <th>S No.</th> -->
                                                            <th>S. No.</th>
                                                            <th> Bill No  </th>
                                                            <th>Bill Date</th>
                                                            <th> Retailer </th>
                                                            <th> Salesman </th>
                                                            <th> Net Amount </th>
                                                            <th> SR  </th>
                                                            <th> CD  </th>
                                                            <th> Collection </th>
                                                            <th> Office Adj  </th>
                                                            <th> Other Adj  </th>
                                                            <th> Debit </th>
                                                            <th> Remaining  </th>
                                                            <th> Cheque Penalty  </th>
                                                            <th> Status  </th>
                                                            <th class="noExport">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <!-- <th>S No.</th> -->
                                                            <th>S. No.</th>
                                                            <th> Bill No  </th>
                                                            <th>Bill Date</th>
                                                            <th> Retailer </th>
                                                            <th> Salesman </th>
                                                            <th> Net Amount </th>
                                                            <th> SR  </th>
                                                            <th> CD  </th>
                                                            <th> Collection </th>
                                                            <th> Office Adj  </th>
                                                            <th> Other Adj  </th>
                                                            <th> Debit </th>
                                                            <th> Remaining  </th>
                                                            <th> Cheque Penalty  </th>
                                                            <th> Status  </th>
                                                            <th class="noExport">Action</th>
                                                        </tr>
                                                    </tfoot>
                                                    <tbody id="hideInfo">
                                                        <?php
                                                        if(!empty($bills)){
                                                            $no=0;
            foreach ($bills as $data) 
            {
                $provBillCount=$this->EmployeeModel->getSumRowCount($data['id']); 

                // $resendBills=$this->EmployeeModel->getRowCount('allocationsbills',$data['id'],'isResendBill');
                // $lostBills=$this->EmployeeModel->getRowCount('allocationsbills',$data['id'],'isLostBill');
                // $lostChequesBills=$this->EmployeeModel->getRowCount('allocationsbills',$data['id'],'isLostCheque');
                // $pendingNeftBills=$this->EmployeeModel->getRowCount('allocationsbills',$data['id'],'isPendingNeft');

                $bouncedBill=$this->EmployeeModel->checkBouncedBill('billpayments',$data['id']);

                $dt=date_create($data['date']);
                $createdDate = date_format($dt,'d-M-Y');
                $style="";
                if($data['isAllocated']==1){ 
                    $style="background-color: #dcd6d5";
                }
                $no++;

            ?>
            <tr style="<?php echo $style; ?>">
                <!-- <td><?php echo $no; ?></td> -->
                <td>
            <?php 
                echo $no.'  ';
              if($data['creditNoteRenewal']>0){ echo '<span class="logo_prov">CN</span>'; }
              if($provBillCount[0]['recBill']>0){ echo '<span class="logo_prov">RB</span>'; }
              if($provBillCount[0]['lostBill']>0){ echo '<span class="logo_prov">LB</span>'; }
              if($provBillCount[0]['lostCheque']>0){ echo '<span class="logo_prov">LC</span>'; }
              if($provBillCount[0]['lostNeft']>0){ echo '<span class="logo_prov">PN</span>'; }

                // if($data['creditNoteRenewal']>0){ echo '<span class="logo_prov">CN</span>'; }
                // if($resendBills>0){ echo '<span class="logo_prov">RB</span>'; }
                // if($lostBills>0){ echo '<span class="logo_prov">LB</span>'; }
                // if($lostChequesBills>0){ echo '<span class="logo_prov">LC</span>'; }
                // if($pendingNeftBills>0){ echo '<span class="logo_prov">PN</span>'; }
        
            ?>
                </td>
                <td><?php echo $data['billNo']; ?></td>
                <td><?php echo $createdDate; ?></td>
                <td><?php echo $data['retailerName']; ?></td>
                <td><?php echo $data['salesman']; ?></td>
                <td><?php echo $data['netAmount']; ?></td>
                <td><?php echo $data['SRAmt']; ?></td>
                <td><?php echo $data['cd']; ?></td>
                <td><?php echo $data['receivedAmt']; ?></td>
                <td><?php echo $data['officeAdjustmentBillAmount']; ?></td>
                <td><?php echo $data['otherAdjustment']; ?></td>
                <td><?php echo $data['debit']; ?></td>
                <td><?php echo $data['pendingAmt']; ?></td>
                <td><?php echo $data['chequePenalty']; ?></td>
                   <td>
                <?php 

                $allocations=$this->EmployeeModel->getAllocationDetailsByBill('bills',$data['id']);
                $allocationsHistory=$this->EmployeeModel->getAllocationDetailsByBillHistory('bills',$data['id']);
                $officeAllocations=$this->EmployeeModel->getOfficeAllocationDetailsByBill('bills',$data['id']);
                $officeAllocationsHistory=$this->EmployeeModel->getOfficeAllocationDetailsByBillHistory('bills',$data['id']);
                // print_r($allocations);exit;
                $status="";
                $allocationNumber="";
                $allocationName="";
                $empName="";

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
                    } 
            ?>
                </td>
                <td class="noExport">
                    <?php if($data['isAllocated']!=1){ ?>
                        <?php if($this->session->userdata['workRestrict']['status']=="yes"){?>
                            <button class="btn btn-xs btn-primary waves-effect waves-float" onclick="planUpgradeMsg()"><i class="material-icons">touch_app</i></button>
                        <?php }else{ ?>    
                            <a id="prDetailsForAll" href="javascript:void()" data-id="<?php echo $data['id']; ?>" data-salesman="<?php echo $data['salesman']; ?>" data-billDate="<?php echo $date; ?>" data-credAdj="<?php echo $data['creditAdjustment']; ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>" data-gst="<?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?>" data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-route="<?php echo $data['routeName']; ?>" data-toggle="modal" data-target="#processModalForAll" ><button class="btn btn-xs btn-primary waves-effect waves-float" data-toggle="tooltip" data-placement="bottom" title="Process"><i class="material-icons">touch_app</i></button></a>
                        <?php } ?>
                      <!-- <a id="prDetails" href="javascript:void()" data-id="<?php echo $data['id']; ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>" data-gst="<?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?>" data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-toggle="modal" data-target="#processModal"><button class="btn btn-xs btn-primary waves-effect"><i class="material-icons">touch_app</i></button></a> -->
                      &nbsp;&nbsp;<a target="_blank" href="<?php echo site_url('AdHocController/billHistoryInfo/'.$data['id']); ?>" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="View History"><i class="material-icons">info</i></a>
                     &nbsp;&nbsp;<a target="_blank" href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs  btn-primary" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                                       
                  <?php }else{ ?>
                     <a target="_blank" href="<?php echo site_url('AdHocController/billHistoryInfo/'.$data['id']); ?>" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="View History"><i class="material-icons">info</i></a>
                     &nbsp;&nbsp;<a target="_blank" href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs  btn-primary" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                        

                  <?php  }
                   ?>
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
                                                                 
                                <!-- </div> -->
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
  <div class="modal fade" id="billprocessModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <center><h4 class="modal-title">Bill History </h4></center>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
           <!-- <a href="<?php echo site_url('AdHocController/retailerHistory');?>">
                <button type="button" class="btn btn-primary m-t-15 waves-effect">
                    <i class="material-icons">cancel</i> 
                </button>
            </a>  -->
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

                            <div id="hideBillHistoryInfo" class="col-md-12"> 
                            </div>
                        </div>
                 
                    </div>
                </div>
            </div>
                     
          </div>
      </div>
    </div>
  </div>
</div>

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
                        </div>
                         
                         <br>
                           <div class="row">
                            
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <input name="group5" type="radio" id="radio_cash" class="with-gap radio-col-red" checked />
                                <label for="radio_cash">Cash</label>
                           
                                <input name="group5" type="radio" id="radio_cheque" class="with-gap radio-col-red"  />
                                <label for="radio_cheque">Cheque</label>
                            
                                <input name="group5" type="radio" id="radio_neft" class="with-gap radio-col-red"  />
                                <label for="radio_neft">NEFT</label>
                            
                                <input name="group5" type="radio" id="radio_cd" class="with-gap radio-col-red"  />
                                <label for="radio_cd">CD</label>

                                <input name="group5" type="radio" id="radio_debit" class="with-gap radio-col-red" />
                                <label for="radio_debit">Debit</label>

                                <input name="group5" type="radio" id="radio_officeAdj" class="with-gap radio-col-red" />
                                <label for="radio_officeAdj">Office Adjustment</label>

                                <input name="group5" type="radio" id="radio_otherAdj" class="with-gap radio-col-red" />
                                <label for="radio_otherAdj">Other Adjustment</label>

                                <input name="group5" type="radio" id="radio_sr" class="with-gap radio-col-red"  />
                                <label for="radio_sr">SR/FSR</label>
                                
                                <input name="group5" type="radio" id="radio_allocation" class="with-gap radio-col-red"  />
                                <label for="radio_allocation">Add to Open Allocation</label>

                                <input name="group5" type="radio" id="radio_EmpDelivery" class="with-gap radio-col-red"  />
                                <label for="radio_EmpDelivery">Direct Delivery by Employee</label>
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
     $(document).on('blur','#employee',function(){
        var empName=$('#employee').val();
        var empId = $('#empData').find('option[value="'+empName+'"]').attr('id');
        $('#eid').val(empId);
    });

    $(document).on('submit','#formidForSubmit',function(){
        // alert('heye');
        // if (e.keyCode == 13) {
            // alert('heye');
            var empName=$('#employee').val();
            var empId = $('#empData').find('option[value="'+empName+'"]').attr('id');
            $('#eid').val(empId);

            var empName=$('#employee').val();
            var empId=$('#eid').val();
            // var empId = $('#empData').find('option[value="'+empName+'"]').attr('id');
            if(empName==''){
                alert('Please select employee.');die();
            }else if(empName!=''){
                if (typeof empId === "undefined") {
                    alert('Please select correct employee.');die();
                }
            }

            $('#eid').val(empId);
            
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('manager/EmployeeController/employeeClearance');?>",
                // url:"<?php echo site_url('manager/EmployeeController/retailerHistoryInfo');?>",
                data:{employee:empName,eid:empId},
                success: function (data) {
                    // $('#hideInfo').empty().append(data);
                    // $('#hideInfo').html(data);
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
        // }
    });
</script>

<script type="text/javascript">
    $(document).on('click','#billHistory',function(){
        var id=$(this).attr('data-id');
        var billNo=$(this).attr('data-billNo');
        var retailerName=$(this).attr('data-retailerName');
        var pendingAmt=$(this).attr('data-pendingAmt');
        var gst=$(this).attr('data-gst');
        
        $('#currentPendingAmt').val(pendingAmt);
        $('#currentBillId').val(id);
        $('#currentBillNo').val(billNo);
        $('#currentBillRetailer').val(retailerName);
        $('#bill_no').text(billNo);
        $('#gst').text(gst);
       
        $('#bill_retailer').text(retailerName);
        $('#bill_pendingAmt').text(pendingAmt);

        $.ajax({
            type: "POST",
            url:"<?php echo site_url('AdHocController/retailerbillInfo');?>",
            data:{billNo:billNo,billId:id},
            success: function (data) {
                $('#hideBillHistoryInfo').html(data);
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
            if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
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
        
        $('#currentPendingAmt').val(pendingAmt);
        $('#currentBillId').val(id);
        $('#currentBillNo').val(billNo);
        $('#currentBillRetailer').val(retailerName);
        $('#bill_no').text(billNo);
        $('#gst').text(gst);
       
        $('#bill_retailer').text(retailerName);
        $('#bill_pendingAmt').text(pendingAmt);
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
                        $('#processModal').modal('toggle');
                        var empName=$('#employee').val();
                        var empId = $('#empData').find('option[value="'+empName+'"]').attr('id');
                        if(empName==''){
                            alert('Please select employee.');die();
                        }else if(empName!=''){
                            if (typeof empId === "undefined") {
                                alert('Please select correct employee.');die();
                            }
                        }
                        
                        $.ajax({
                            type: "POST",
                            url:"<?php echo site_url('manager/EmployeeController/retailerHistoryInfo');?>",
                            data:{empName:empName,empId:empId},
                            success: function (data) {
                                $('#hideInfo').html(data);
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
                    }else{
                        alert(data);
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
            // alert(data);
                    $('#processModal').modal('toggle');
                    var empName=$('#employee').val();
                    var empId = $('#empData').find('option[value="'+empName+'"]').attr('id');
                    if(empName==''){
                        alert('Please select employee.');die();
                    }else if(empName!=''){
                        if (typeof empId === "undefined") {
                            alert('Please select correct employee.');die();
                        }
                    }
                    
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('manager/EmployeeController/retailerHistoryInfo');?>",
                        data:{empName:empName,empId:empId},
                        success: function (data) {
                            $('#hideInfo').html(data);
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
                    $('#processModal').modal('toggle');
                    var empName=$('#employee').val();
                    var empId = $('#empData').find('option[value="'+empName+'"]').attr('id');
                    if(empName==''){
                        alert('Please select employee.');die();
                    }else if(empName!=''){
                        if (typeof empId === "undefined") {
                            alert('Please select correct employee.');die();
                        }
                    }
                    
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('manager/EmployeeController/retailerHistoryInfo');?>",
                        data:{empName:empName,empId:empId},
                        success: function (data) {
                            $('#hideInfo').html(data);
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
                }else{
                    alert(data);
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
    $(document).on('click','#chequeSaveBtn',function(){
        var empName=$('#chequeEmp').val();
        var empId = $('#chequeEmpList').find('option[value="'+empName+'"]').attr('id');
        var chequeAmount=$('#chequeAmount').val();
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

        if(chequeAmount=='' || chequeAmount==0){
            alert('Please enter cheque amount.');die();
        }

        if(parseInt(currentPendingAmt) < parseInt(chequeAmount)){
            alert('Cheque amount should not be greater than Pending amount');die();
        }
        
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('BillTransactionController/chequeCollection');?>",
            data:{empName:empName,empId:empId,chequeAmount:chequeAmount,currentPendingAmt:currentPendingAmt,currentBillId:currentBillId},
            success: function (data) {
                $('#chequeEmp').val("");
                $('#chequeAmount').val("");
                if(data.trim()=="Record updated"){
                    alert(data);
                    $('#processModal').modal('toggle');
                    var empName=$('#employee').val();
                    var empId = $('#empData').find('option[value="'+empName+'"]').attr('id');
                    if(empName==''){
                        alert('Please select employee.');die();
                    }else if(empName!=''){
                        if (typeof empId === "undefined") {
                            alert('Please select correct employee.');die();
                        }
                    }
                    
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('manager/EmployeeController/retailerHistoryInfo');?>",
                        data:{empName:empName,empId:empId},
                        success: function (data) {
                            $('#hideInfo').html(data);
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
                }else{
                    alert(data);
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
                    $('#processModal').modal('toggle');
                    var empName=$('#employee').val();
                    var empId = $('#empData').find('option[value="'+empName+'"]').attr('id');
                    if(empName==''){
                        alert('Please select employee.');die();
                    }else if(empName!=''){
                        if (typeof empId === "undefined") {
                            alert('Please select correct employee.');die();
                        }
                    }
                    
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('manager/EmployeeController/retailerHistoryInfo');?>",
                        data:{empName:empName,empId:empId},
                        success: function (data) {
                            $('#hideInfo').html(data);
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
                }else{
                    alert(data);
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
                    $('#processModal').modal('toggle');
                    var empName=$('#employee').val();
                    var empId = $('#empData').find('option[value="'+empName+'"]').attr('id');
                    if(empName==''){
                        alert('Please select employee.');die();
                    }else if(empName!=''){
                        if (typeof empId === "undefined") {
                            alert('Please select correct employee.');die();
                        }
                    }
                    
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('manager/EmployeeController/retailerHistoryInfo');?>",
                        data:{empName:empName,empId:empId},
                        success: function (data) {
                            $('#hideInfo').html(data);
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
                }else{
                    alert(data);
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
                    $('#processModal').modal('toggle');
                    var empName=$('#employee').val();
                    var empId = $('#empData').find('option[value="'+empName+'"]').attr('id');
                    if(empName==''){
                        alert('Please select employee.');die();
                    }else if(empName!=''){
                        if (typeof empId === "undefined") {
                            alert('Please select correct employee.');die();
                        }
                    }
                    
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('manager/EmployeeController/retailerHistoryInfo');?>",
                        data:{empName:empName,empId:empId},
                        success: function (data) {
                            $('#hideInfo').html(data);
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
                }else{
                    alert(data);
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
                    $('#processModal').modal('toggle');
                    var empName=$('#employee').val();
                    var empId = $('#empData').find('option[value="'+empName+'"]').attr('id');
                    if(empName==''){
                        alert('Please select employee.');die();
                    }else if(empName!=''){
                        if (typeof empId === "undefined") {
                            alert('Please select correct employee.');die();
                        }
                    }
                    
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('manager/EmployeeController/retailerHistoryInfo');?>",
                        data:{empName:empName,empId:empId},
                        success: function (data) {
                            $('#hideInfo').html(data);
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
                }else{
                    alert(data);
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
                    $('#processModal').modal('toggle');
                    var empName=$('#employee').val();
                    var empId = $('#empData').find('option[value="'+empName+'"]').attr('id');
                    if(empName==''){
                        alert('Please select employee.');die();
                    }else if(empName!=''){
                        if (typeof empId === "undefined") {
                            alert('Please select correct employee.');die();
                        }
                    }
                    
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('manager/EmployeeController/retailerHistoryInfo');?>",
                        data:{empName:empName,empId:empId},
                        success: function (data) {
                            $('#hideInfo').html(data);
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
                }else{
                    alert(data);
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
                    $('#processModal').modal('toggle');
                    var empName=$('#employee').val();
                    var empId = $('#empData').find('option[value="'+empName+'"]').attr('id');
                    if(empName==''){
                        alert('Please select employee.');die();
                    }else if(empName!=''){
                        if (typeof empId === "undefined") {
                            alert('Please select correct employee.');die();
                        }
                    }
                    
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('manager/EmployeeController/retailerHistoryInfo');?>",
                        data:{empName:empName,empId:empId},
                        success: function (data) {
                            $('#hideInfo').html(data);
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
                }else{
                    alert(data);
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
                    $('#processModal').modal('toggle');
                    var empName=$('#employee').val();
                    var empId = $('#empData').find('option[value="'+empName+'"]').attr('id');
                    if(empName==''){
                        alert('Please select employee.');die();
                    }else if(empName!=''){
                        if (typeof empId === "undefined") {
                            alert('Please select correct employee.');die();
                        }
                    }
                    
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('manager/EmployeeController/retailerHistoryInfo');?>",
                        data:{empName:empName,empId:empId},
                        success: function (data) {
                            $("#hideInfo").empty();
                            $("#hideInfo").append(data);
                            // $('#hideInfo').html(data);
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
                }else{
                    alert(data);
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
            if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
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
            if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
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
     $(document).on('click','#limit_id',function(){
         var id=$(this).attr('data-id');
         $('#offcId').val(id);
         $.ajax({
            url : "<?php echo site_url('AdHocController/adHociBillByEmpAdjustmentForm');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
              // alert(data);die();
                $('#dataForId').html(data);
              
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