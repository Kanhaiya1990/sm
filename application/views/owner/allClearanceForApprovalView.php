<?php $this->load->view('/layouts/commanHeader'); ?>

<style type="text/css">
    @media screen and (min-width: 1200px) {
        .modal-dialog {
          width: 1200px; /* New width for default modal */
        }
        .modal-sm {
          width: 350px; /* New width for small modal */
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
        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/>
        
    <?php  if(empty($mainCashBookbankDeposit) && empty($bankDeposit) && empty($employeeApproval) && empty($employeeNonCashCredit) && empty($employeeNonCashDebitDebit) && empty($notesdetails) && empty($expnceDetail) && empty($cdApproval) && empty($officeAdjustmentApproval) && empty($otherAdjustmentApproval) && empty($officeAllocations)){ ?>
     <section class="col-md-12 box">
        <div class="container-fluid">
            
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <h5>No Approvals Pending</h5>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </section>
  <?php } ?>

  <?php  if(!empty($officeAllocations)){ ?>
  <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <div class="table-responsive tableFixHead">
                                <table  style="font-size: 13px" class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                  <thead>
                                    <tr>
                                      <th colspan="5">
                                        <center><h5> Office Allocation Approvals </h5><center>
                                      </th>
                                    </tr>
                                  </thead>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Initiator </th>
                                            <th>Allocation No.</th>
                                            <th>Remark</th>
                                            <th>Title</th>
                                            <th>Owner</th>
                                        </tr>
                                    </thead>
                                   
                                    <tbody>
                                      <?php
                                        $no=0;
                                       


                                    foreach ($officeAllocations as $data) 
                                    {
                                        $no++; 
                                    ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td>
                                              <?php echo $data['emp_name']; ?>
                                            </td>
                                            <td>
                                                  <?php echo $data['allocationCode']; ?>
                                            </td>
                                            <td>
                                              <?php echo $data['remark']; ?>
                                            </td>
                                            <td>
                                              <?php echo $data['title']; ?>
                                            </td>
                                           
                                            <td>
                                              <?php if($data['managerStatus']=="1" && $data['ownerStatus']=="0"){ ?>
                                              <a href="<?php echo base_url().'index.php/owner/OfficeAllocationController/loadOfficeAllocationsBills/'.$data['id'];?>"><center><i class="material-icons">add</i></center></a>
                                            <?php }else if($data['managerStatus']=="1" && $data['ownerStatus']=="1"){ ?>
                                                <center><i class="material-icons">check</i></center>
                                            <?php }else{ ?>
                                              <a href="<?php echo base_url().'index.php/owner/OfficeAllocationController/loadOfficeAllocationsBills/'.$data['id'];?>"><center><i class="material-icons">add</i></center></a>
                                          <?php } ?>
                                              </td>
                                            
                                        </tr>
                                    <?php
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
  <?php } ?>

  <?php  if(!empty($bankDeposit)){ ?>
     <section class="col-md-12 box">
        <div class="container-fluid">
            
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <div class="table-responsive tableFixHead">
                                <table style="font-size: 12px" class="table table-bordered table-striped table-hover" data-page-length='100'>
                                  <thead>
                                    <tr>
                                      <th colspan="6">
                                        <center><h5> Cash Reconciliation Approval</h5><center>
                                      </th>
                                    </tr>
                                  </thead>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Initiator</th>
                                            <th>Date</th>
                                            <th>Employee</th>
                                            <th class="text-right"><span>Total Bank Deposit</span></th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                  
                                    <tbody>
                                      <?php
                                        $no=0;
                                        if(!empty($bankDeposit)){
                                          foreach ($bankDeposit as $data) 
                                            {
                                                $no++; 
                                                $dt=date_create($data['date']);
                                                $date = date_format($dt,'d-M-Y H:i:sa');
                                            ?>
                                              <tr>
                                                  <td><?php echo $no; ?></td>
                                                  <td><?php echo $data['initiator_name']; ?></td>
                                                  <td><?php echo $date; ?></td>
                                                  <td><?php echo $data['emp_name']; ?></td>
                                                  <td class="text-right"><?php echo number_format($data['amount']); ?></td>
                                                  <td>
                                                    <button id="acceptbankDetails" data-id="<?php echo $data['id']; ?>" data-date="<?php echo $date; ?>" data-emp="<?php echo $data['emp_name']; ?>" data-amount="<?php echo $data['amount']; ?>" data-openCloseBalance="<?php echo $data['openCloseBalance']; ?>" class="modalLink btn btn-xs btn-primary waves-effect">
                                                    <span class="icon-name"> <i class="material-icons">check</i></span></button>

                                                    <!-- <button id="bankDetails" data-id="<?php echo $data['id']; ?>" data-date="<?php echo $data['date']; ?>" data-emp="<?php echo $data['emp_name']; ?>" data-empId="<?php echo $data['emp_id']; ?>" data-amount="<?php echo $data['amount']; ?>" data-openCloseBalance="<?php echo $data['openCloseBalance']; ?>" data-toggle="modal" data-target="#myModal1" class="modalLink btn btn-xs btn-primary waves-effect">
                                <span class="icon-name"> <i class="material-icons">cancel</i></span>
                              </button> -->
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
  <?php } ?>


  <?php  if(!empty($mainCashBookbankDeposit)){ ?>
     <section class="col-md-12 box">
        <div class="container-fluid">
            
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <div class="table-responsive tableFixHead">
                                <table style="font-size: 12px" class="table table-bordered table-striped table-hover" data-page-length='100'>
                                  <thead>
                                    <tr>
                                      <th colspan="6">
                                        <center><h5> Main Cash Book Reconciliation Approval</h5><center>
                                      </th>
                                    </tr>
                                  </thead>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Initiator</th>
                                            <th>Date</th>
                                            <th>Employee</th>
                                            <th class="text-right"><span>Total Bank Deposit</span></th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                  
                                    <tbody>
                                      <?php
                                        $no=0;
                                        if(!empty($mainCashBookbankDeposit)){
                                          foreach ($mainCashBookbankDeposit as $data) 
                                            {
                                                $no++; 
                                                $dt=date_create($data['date']);
                                                $date = date_format($dt,'d-M-Y H:i:sa');
                                            ?>
                                              <tr>
                                                  <td><?php echo $no; ?></td>
                                                  <td><?php echo $data['emp_name']; ?></td>
                                                  <td><?php echo $date; ?></td>
                                                  <td><?php echo $data['emp_name']; ?></td>
                                                  <td class="text-right"><?php echo number_format($data['amount']); ?></td>
                                                  <td>
                                                    <button id="acceptMainCashBook" data-id="<?php echo $data['id']; ?>" data-date="<?php echo $date; ?>" data-emp="<?php echo $data['emp_name']; ?>" data-amount="<?php echo $data['amount']; ?>" class="btn btn-xs btn-primary waves-effect">
                                                    <span class="icon-name"> <i class="material-icons">check</i></span></button>

                                                    <button id="rejectMainCashBook" data-id="<?php echo $data['id']; ?>" data-date="<?php echo $data['date']; ?>" data-emp="<?php echo $data['emp_name']; ?>" data-empId="<?php echo $data['emp_id']; ?>" data-amount="<?php echo $data['amount']; ?>" data-toggle="modal" data-target="#myModalMainCashBookBankDeposit" class="btn btn-xs btn-primary waves-effect">
                                                    <span class="icon-name"> <i class="material-icons">cancel</i></span>
                                                  </button>
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
  <?php } ?>


  <?php  if(!empty($employeeApproval)){ ?>
     <section class="col-md-12 box">
        <div class="container-fluid">
           
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <div class="table-responsive">
                                <table style="font-size: 12px" class="table table-bordered table-striped table-hover" data-page-length='100'>
                                  <thead>
                                    <tr>
                                      <th colspan="11">
                                        <center><h5>Employee Addition Approval </h5><center>
                                      </th>
                                    </tr>
                                  </thead>
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Code</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Mobile</th>
                                            <th>Company Name</th>
                                            <th>Role</th>
                                            <th class="text-right">Salary</th>
                                            <th>Salary_Emp</th>
                                            <th>Login_Emp</th>
                                            <th>Action </th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <?php
                                        $no=0;
                                        foreach ($employeeApproval as $data) 
                                          {
                                           $no++; 
                                        ?>
                                        <tr>
                                           <td><?php echo $no; ?></td>
                                            <td><?php echo $data['code']; ?></td>
                                            <td><?php  
                                                $str=$data['name']; 
                                                $exploded=explode(" ",$str);
                                                echo $firstname = $exploded[0];
                                                ?>
                                            </td>
                                             <td><?php  
                                                $str=$data['name']; 
                                                $exploded=explode(" ",$str);
                                                if(!empty($exploded[1])){
                                                     echo $firstname = $exploded[1];
                                                }
                                               
                                                ?>
                                            </td>
                                            <td><?php echo $data['mobile']; ?></td>
                                            <td><?php echo $data['companyName']; ?></td>
                                            <td><?php echo $data['designation']; ?></td>
                                           <td class="text-right"><?php echo number_format($data['salary']); ?></td>
                                            <td><?php if($data['isLoginEmp']==0){ echo "No"; }else{ echo "Yes"; } ?></td>
                                           <td><?php if($data['isLoginEmp']==0){ echo "No"; }else{ echo "Yes"; } ?></td>
                                            <td>


                                            <a href="javascript:void();" class="modalLink btn btn-xs btn-primary waves-effect" data-toggle="modal" data-target="#ownerSalaryModal" id="emp_accept_id" data-id="<?php echo $data['id'];?>">
                                                <i class="material-icons">check</i>
                                            </a>
                                            <a href="javascript:void();" class="modalLink btn btn-xs btn-primary waves-effect" id="emp_reject_id"  data-id="<?php echo $data['id'];?>">
                                                <i class="material-icons">cancel</i>
                                            </a>
                                         
                                                                                           
                                        </td>
                                    
                                        </tr>
                                        <?php
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

  <?php } ?>

   <?php  if(!empty($employeeNonCashCredit)){ ?>
     <section class="col-md-12 box">
        <div class="container-fluid">
           
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        
                        <div class="body">
                            <div class="table-responsive">
                                <table style="font-size: 12px" class="table table-bordered table-striped table-hover" data-page-length='100'>
                                  <thead>
                                    <tr>
                                      <th colspan="7">
                                        <center><h5>Non Cash Credit Approval</h5><center>
                                          <p align="right">
                                            <button type="button" id="insert-credit-chk" class="btn btn-xs btn-primary m-t-15 waves-effect"> <i class="material-icons">save</i> <span class="icon-name">Approve All</span></button>
                                        </p>
                                      </th>
                                    </tr>
                                  </thead>
                                    <thead>
                                        <tr>
                                            <th>
                                              <input class="checkallForCreditAccept" type="checkbox" name="selCreditAcceptValue" id="credit-basic_checkbox"/>
                                              <label for="credit-basic_checkbox"></label>
                                            </th>
                                            <th>Initiator</th>
                                            <th>Employee</th>
                                            <th class="text-right">Amount</th>
                                            <th>Transaction Type</th>
                                            <th>Remark</th>
                                            <th>Action </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no=0;
                                        foreach ($employeeNonCashCredit as $data) 
                                          {
                                           $no++; 
                                        ?>
                                        <tr>
                                           <td>
                                             <input class="checkForCreditAccept" type="checkbox" name="selCreditAcceptValue" value="<?php echo $data['id']; ?>" id="credit-basic_checkbox_<?php echo $data['id']; ?>" />
                                              <label for="credit-basic_checkbox_<?php echo $data['id']; ?>"></label>
                                           </td>
                                           <td><?php echo $data['initiatorName']; ?></td>
                                            <td><?php echo $data['empName']; ?></td>
                                           
                                           
                                            <td class="text-right"><?php echo number_format($data['amount']); ?></td>
                                            <td><?php echo $data['transactionType']; ?></td>
                                            <td><?php echo $data['description']; ?></td>
                                           
                                            <td>
                                            <a href="javascript:void();" class="modalLink btn btn-xs btn-primary waves-effect" id="emp_noncash_accept_id" data-id="<?php echo $data['id'];?>">
                                                <i class="material-icons">check</i>
                                            </a>
                                            <a href="javascript:void();" class="modalLink btn btn-xs btn-primary waves-effect" id="emp_noncash_reject_id"  data-id="<?php echo $data['id'];?>">
                                                <i class="material-icons">cancel</i>
                                            </a>
                                         
                                                                                           
                                        </td>
                                    
                                        </tr>
                                        <?php
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
  <?php } ?>

   <?php  if(!empty($employeeNonCashDebit)){ ?>
     <section class="col-md-12 box">
        <div class="container-fluid">
           
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        
                        <div class="body">
                            <div class="table-responsive">
                                <table style="font-size: 12px" class="table table-bordered table-striped table-hover" data-page-length='100'>
                                  <thead>
                                    <tr>
                                      <th colspan="7">
                                        <center><h5> Non Cash Debit Approval</h5><center>
                                          <p align="right">
                                            <button type="button" id="insert-debit-chk" class="btn btn-xs btn-primary m-t-15 waves-effect"> <i class="material-icons">save</i> <span class="icon-name">Approve All</span></button>
                                        </p>
                                      </th>
                                    </tr>
                                  </thead>
                                    <thead>
                                        <tr>
                                             <th>
                                              <input class="checkallForDebitAccept" type="checkbox" name="selDebitAcceptValue" id="debit-basic_checkbox"/>
                                              <label for="debit-basic_checkbox"></label>
                                            </th>
                                            <th>Initiator</th>
                                            <th>Employee</th>
                                            <th class="text-right">Amount</th>
                                            <th>Transaction Type</th>
                                            <th>Remark</th>
                                            <th>Action </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no=0;
                                        foreach ($employeeNonCashDebit as $data) 
                                        {
                                           $no++; 
                                        ?>
                                        <tr>
                                            <td>
                                             <input class="checkForDebitAccept" type="checkbox" name="selDebitAcceptValue" value="<?php echo $data['id']; ?>" id="debit-basic_checkbox_<?php echo $data['id']; ?>" />
                                              <label for="debit-basic_checkbox_<?php echo $data['id']; ?>"></label>
                                           </td>
                                            <td><?php echo $data['initiatorName']; ?></td>
                                            <td><?php echo $data['empName']; ?></td>
                                            <td class="text-right"><?php echo number_format($data['amount']); ?></td>
                                            <td><?php echo $data['transactionType']; ?></td>
                                            <td><?php echo $data['description']; ?></td>
                                            <td>
                                            <a href="javascript:void();" class="modalLink btn btn-xs btn-primary waves-effect" id="emp_debit_noncash_accept_id" data-id="<?php echo $data['id'];?>">
                                                <i class="material-icons">check</i>
                                            </a>
                                            <a href="javascript:void();" class="modalLink btn btn-xs btn-primary waves-effect" id="emp_debit_noncash_reject_id"  data-id="<?php echo $data['id'];?>">
                                                <i class="material-icons">cancel</i>
                                            </a>
                                            </td>
                                        </tr>
                                        <?php
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
  <?php } ?>


   <?php  if(!empty($getDebitByProcess)){ ?>
     <section class="col-md-12 box">
        <div class="container-fluid">
           
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        
                        <div class="body">
                            <div class="table-responsive">
                                <table style="font-size: 12px" class="table table-bordered table-striped table-hover" data-page-length='100'>
                                  <thead>
                                    <tr>
                                      <th colspan="9">
                                        <center><h5> Bill Debit Approval</h5><center>
                                          <p align="right">
                                            <button type="button" id="insert-process-debit-chk" class="btn btn-xs btn-primary m-t-15 waves-effect"> <i class="material-icons">save</i> <span class="icon-name">Approve All</span></button>
                                        </p>
                                      </th>
                                    </tr>
                                  </thead>
                                    <thead>
                                        <tr>
                                            <th>
                                              <input class="checkallForProcessDebitAccept" type="checkbox" name="selProcessDebitAcceptValue" id="processdebit-basic_checkbox"/>
                                              <label for="processdebit-basic_checkbox"></label>
                                            </th>
                                            <th>Initiator</th>
                                            <th>Bill No</th>
                                            <th>Bill Date</th>
                                            <th>Employee</th>
                                            <th class="text-right">Amount</th>
                                            <th>Transaction Type</th>
                                            <th>Remark</th>
                                            <th>Action </th>
                                        </tr>
                                    </thead>
                                    <tbody id="process-debit-tbl">
                                    <?php
                                        $no=0;
                                        foreach ($getDebitByProcess as $data) 
                                        {
                                           $no++; 
                                        ?>
                                        <tr>
                                            <td>
                                             <input class="checkForProcessDebitAccept" type="checkbox" name="selProcessDebitAcceptValue" value="<?php echo $data['id']; ?>" id="processdebit-basic_checkbox_<?php echo $data['id']; ?>" />
                                              <label for="processdebit-basic_checkbox_<?php echo $data['id']; ?>"></label>
                                           </td>
                                           <td><?php echo $data['initiatorName']; ?></td>
                                           <td><?php echo $data['currentbillNo']; ?></td>
                                           <td><?php echo date("d-M-Y", strtotime($data['currentbillDate'])); ?></td>
                                            <td><?php echo $data['empName']; ?></td>
                                            <td class="text-right"><?php echo number_format($data['paidAmount']); ?></td>
                                            <td><?php echo $data['paymentMode']; ?></td>
                                            <td><?php echo $data['description']; ?></td>
                                            <td>
                                              <a href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['billId']); ?>" class="btn btn-xs  btn-primary" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                                
                                            <a href="javascript:void();" class="modalLink btn btn-xs btn-primary waves-effect" id="emp_processdebit_accept_id" data-id="<?php echo $data['id'];?>">
                                                <i class="material-icons">check</i>
                                            </a>
                                            <a href="javascript:void();" class="modalLink btn btn-xs btn-primary waves-effect" id="emp_processdebit_reject_id"  data-id="<?php echo $data['id'];?>">
                                                <i class="material-icons">cancel</i>
                                            </a>
                                            </td>
                                        </tr>
                                    <?php
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
  <?php } ?>

  <?php  if(!empty($notesdetails)){ ?>
     <section class="col-md-12 box">
        <div class="container-fluid">
            
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        
                        <div class="body">
                            <div class="table-responsive tableFixHead">
                                <table style="font-size: 12px" class="table table-bordered table-striped table-hover" data-page-length='100'>
                                  <thead>
                                    <tr>
                                      <th colspan="12">
                                        <center><h5>Market Expenses Approval</h5><center>
                                          <p align="right">
                                            <button type="button" id="insert-marketExpense-chk" class="btn btn-xs btn-primary m-t-15 waves-effect"> <i class="material-icons">save</i> <span class="icon-name">Approve All</span></button>
                                        </p>
                                      </th>
                                    </tr>
                                  </thead>

                                    <thead>
                                        <tr>
                                            <th>
                                              <input class="checkallForMarketExpense" type="checkbox" name="selCdValue" id="marketExpense-basic_checkbox"/>
                                              <label for="marketExpense-basic_checkbox"></label>
                                            </th>
                                            <th>Initiator</th>
                                            <th>Allocation No.</th>
                                            <th>Date</th>
                                            <th>Employee</th>
                                             <th>Nature</th>
                                             <th>remark</th>
                                            <th class="text-right">Parking</th>
                                            <th class="text-right">Challan</th>
                                            <th class="text-right">CNG</th>
                                            <th class="text-right">Total Expense</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                   
                                    <tbody>
                                      <?php
                                        $no=0;
                                        if(!empty($notesdetails)){
                                        foreach ($notesdetails as $data) 
                                          {
                                              
                                              $no++; 
                                              $totalExpense=$data['parking']+$data['cng']+$data['challan'];
                                              $dt=date_create($data['allocactionDate']);
                                              $date = date_format($dt,'d-M-Y H:i:sa');

                                          ?>
                                            <tr>
                                                <td>
                                                 <input class="checkForMarketExpense" type="checkbox" name="selMarketExpenseValue" value="<?php echo $data['id']; ?>" id="marketExpense-basic_checkbox_<?php echo $data['id']; ?>" />
                                                  <label for="marketExpense-basic_checkbox_<?php echo $data['id']; ?>"></label>
                                               </td>
                                                <td><?php echo $data['initiator_name']; ?></td>
                                                <td><?php echo $data['code']; ?></td>
                                                <td><?php echo $date; ?></td>
                                                <td><?php echo $data['emp_name']; ?></td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right"> <?php echo number_format($data['parking']); ?></td>
                                                <td class="text-right"><?php echo number_format($data['challan']); ?></td>
                                                <td class="text-right"><?php echo number_format($data['cng']); ?></td>
                                                <td class="text-right"><?php echo number_format($totalExpense); ?></td>
                                                <td>
                                                  <button onclick="acceptExpenses(this,'<?php echo $data["id"]?>','<?php echo $data["allocationId"]; ?>');" style="font-size : 12px;" id="signedOk" class="btn btn-xs btn-primary waves-effect"><i class="material-icons">check</i>
                                                 </button> 
                                                 <button onclick="rejectExpenses(this,'<?php echo $data["id"]?>','<?php echo $data["allocationId"]; ?>','<?php echo $data["emp_id"]; ?>','<?php echo $data["parking"]; ?>','<?php echo $data["challan"]; ?>','<?php echo $data["cng"]; ?>');" style="font-size : 12px;" id="signedOk" class="btn btn-xs btn-primary waves-effect"><i class="material-icons">cancel</i> 
                                                 </button>

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
    <?php } ?>

      <?php  if(!empty($expnceDetail)){ ?>
     <section class="col-md-12 box">
        <div class="container-fluid">
            
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        
                        <div class="body">
                            <div class="table-responsive tableFixHead">
                                <table style="font-size: 12px" class="table table-bordered table-striped table-hover" data-page-length='100'>
                                  <thead>
                                    <tr>
                                      <th colspan="12">
                                        <center><h5>Office Expenses Approval</h5><center>
                                          <p align="right">
                                            <button type="button" id="insert-officeExpense-chk" class="btn btn-xs btn-primary m-t-15 waves-effect"> <i class="material-icons">save</i> <span class="icon-name">Approve All</span></button>
                                        </p>
                                      </th>
                                    </tr>
                                  </thead>

                                    <thead>
                                        <tr>
                                            <th>
                                              <input class="checkallForOfficeExpense" type="checkbox" name="selCdValue" id="officeExpense-basic_checkbox"/>
                                              <label for="officeExpense-basic_checkbox"></label>
                                            </th>
                                            <th>Initiator</th>
                                            <th>Date</th>
                                            <th>Employee</th>
                                             <th>Nature</th>
                                             <th>remark</th>
                                            <th class="text-right">Total Expense</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                   
                                    <tbody>
                                      <?php
                                        $no=0;
                                        if(!empty($expnceDetail)){
                                        foreach ($expnceDetail as $data) 
                                          {
                                              $no++; 
                                              $dt=date_create($data['date']);
                                              $date = date_format($dt,'d-M-Y H:i:sa');
                                              
                                          ?>
                                            <tr>
                                                <td>
                                                 <input class="checkForOfficeExpense" type="checkbox" name="selOfficeExpenseValue" value="<?php echo $data['id']; ?>" id="officeExpense-basic_checkbox_<?php echo $data['id']; ?>" />
                                                  <label for="officeExpense-basic_checkbox_<?php echo $data['id']; ?>"></label>
                                               </td>
                                               <td><?php echo $data['initiator_name']; ?></td>
                                                <td><?php echo $date; ?></td>
                                                <td><?php echo $data['emp_name']; ?></td>
                                                 <td><?php echo $data['nature']; ?></td>
                                                <td><?php echo $data['narration']; ?></td>
                                                <td class="text-right"><?php echo number_format($data['amount']); ?></td>
                                                <td>
                                                  <button onclick="acceptOwExpenses(this,'<?php echo $data["id"]?>');" style="font-size : 12px;" id="signedOk" class="btn btn-xs btn-primary waves-effect"><i class="material-icons">check</i>
                                                 </button> 
                                                 <button onclick="rejectOwExpenses(this,'<?php echo $data["id"]?>','<?php echo $data["emp_id"]?>','<?php echo $data["amount"]?>','<?php echo $data["nature"]?>','<?php echo $data["notesId"]?>');" style="font-size : 12px;" id="signedOk" class="btn btn-xs btn-primary waves-effect"><i class="material-icons">cancel</i> 
                                                 </button>

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
    <?php } ?>

    <?php  if(!empty($cdApproval)){ ?>

    <section class="col-md-12 box">
        <div class="container-fluid">
           
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <div class="table-responsive">
                                <table style="font-size: 12px" class="table table-bordered table-striped table-hover" data-page-length='100'>
                                  <thead>
                                    <tr>
                                      <th colspan="10">
                                        <center><h5>Cash Discount Approval</h5><center>
                                        <p align="right">
                                            <button type="button" id="insert-cd-chk" class="btn btn-xs btn-primary m-t-15 waves-effect"> <i class="material-icons">save</i> <span class="icon-name">Approve All</span></button>
                                        </p>
                                      </th>
                                    </tr>
                                  </thead>
                                    <thead>
                                        <tr>
                                            <th>
                                              <input class="checkallForCD" type="checkbox" name="selCdValue" id="cd-basic_checkbox"/>
                                              <label for="cd-basic_checkbox"></label>
                                            </th>
                                            <th>Initiator</th>
                                            <th>Bill No</th>
                                            <th>Bill Date</th>
                                            <th>Retailer</th>
                                            <th class="text-right">Net Amount</th>
                                            <th class="text-right">Pending Amount</th>
                                            <th class="text-right">CD Amount</th>
                                            <th>CD Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody id="cdTableBody">
                                        <?php
                                        $no=0;
                                        foreach ($cdApproval as $data) 
                                          {
                                           $no++; 

                                           $dt=date_create($data['date']);
                                           $billdate = date_format($dt,'d-M-Y');

                                           $dt=date_create($data['cdDate']);
                                           $cdDate = date_format($dt,'d-M-Y H:i:sa');
                                        ?>
                                        <tr>
                                            <td>
                                             <input class="checkCD" type="checkbox" name="selCdValue" value="<?php echo $data['billPaymentId']; ?>" id="cd-basic_checkbox_<?php echo $data['billPaymentId']; ?>" />
                                              <label for="cd-basic_checkbox_<?php echo $data['billPaymentId']; ?>"></label>
                                           </td>
                                           <td><?php echo $data['initiatorName']; ?></td>
                                            <td><?php echo $data['billNo']; ?></td>
                                            <td><?php echo $billdate; ?></td>
                                            <td><?php echo $data['retailerName']; ?></td>
                                            <td class="text-right"><?php echo number_format($data['netAmount']); ?></td>
                                            <td class="text-right"><?php echo number_format($data['pendingAmt']); ?></td>
                                            <td class="text-right"><?php echo number_format($data['cdAmount']); ?></td>
                                            <td><?php echo $cdDate; ?></td>
                                            <td>
                                              <a href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs  btn-primary" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                                
                                              <a href="javascript:void();" onclick="acceptCashDiscount(this,'<?php echo $data["billPaymentId"]; ?>');" class="modalLink btn btn-xs btn-primary waves-effect">
                                                <i class="material-icons">check</i>
                                              </a>
                                              <a href="javascript:void();" onclick="rejectCashDiscount(this,'<?php echo $data["billPaymentId"]; ?>');" class="modalLink btn btn-xs btn-primary waves-effect">
                                                <i class="material-icons">cancel</i>
                                              </a>

                                            </td>
                                    
                                        </tr>
                                        <?php
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

  <?php } ?>

  <?php  if(!empty($officeAdjustmentApproval)){ ?>
    <section class="col-md-12 box">
        <div class="container-fluid">
           
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <div class="table-responsive">
                                <table style="font-size: 12px" class="table table-bordered table-striped table-hover" data-page-length='100'>
                                  <thead>
                                    <tr>
                                      <th colspan="10">
                                        <center><h5>Office Adjustment Approval</h5><center>
                                        <p align="right">
                                            <button type="button" id="insert-officeAdj-chk" class="btn btn-xs btn-primary m-t-15 waves-effect"> <i class="material-icons">save</i> <span class="icon-name">Approve All</span></button>
                                        </p>
                                      </th>
                                    </tr>
                                  </thead>
                                    <thead>
                                        <tr>
                                            <th>
                                              <input class="checkallForOfficeAdj" type="checkbox" name="selOfficeAdjValue" id="officeAdj-basic_checkbox"/>
                                              <label for="officeAdj-basic_checkbox"></label>
                                            </th>
                                            <th>Initiator</th>
                                            <th>Bill No</th>
                                            <th>Bill Date</th>
                                            <th>Retailer</th>
                                            <th class="text-right">Net Amount</th>
                                            <th class="text-right">Pending Amount</th>
                                            <th class="text-right">Office Adjustment Amount</th>
                                            <th>Office Adjustment Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody id="officeTableBody">
                                        <?php
                                        $no=0;
                                        foreach ($officeAdjustmentApproval as $data) 
                                          {
                                           $no++;
                                            
                                           $dt=date_create($data['date']);
                                           $billdate = date_format($dt,'d-M-Y');

                                           $dt=date_create($data['officeAdjDate']);
                                           $otherAdjDate = date_format($dt,'d-M-Y H:i:sa');
                                        ?>
                                        <tr>
                                           <td>
                                             <input class="checkOfficeAdj" type="checkbox" name="selOfficeAdjValue" value="<?php echo $data['billPaymentId']; ?>" id="officeAdj-basic_checkbox_<?php echo $data['billPaymentId']; ?>" />
                                              <label for="officeAdj-basic_checkbox_<?php echo $data['billPaymentId']; ?>"></label>
                                           </td>
                                            <td><?php echo $data['initiatorName']; ?></td>
                                            <td><?php echo $data['billNo']; ?></td>
                                            <td><?php echo $billdate; ?></td>
                                            <td><?php echo $data['retailerName']; ?></td>
                                            <td class="text-right"><?php echo number_format($data['netAmount']); ?></td>
                                            <td class="text-right"><?php echo number_format($data['pendingAmt']); ?></td>
                                            <td class="text-right"><?php echo number_format($data['officeAdjAmount']); ?></td>
                                            <td><?php echo $otherAdjDate; ?></td>
                                            <td>
                                              <a target="_blank" href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs  btn-primary" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                                              <a href="javascript:void();" onclick="acceptOfficeAdjustment(this,'<?php echo $data["billPaymentId"]; ?>');" class="modalLink btn btn-xs btn-primary waves-effect">
                                                <i class="material-icons">check</i>
                                              </a>
                                              <a href="javascript:void();" onclick="rejectOfficeAdjustment(this,'<?php echo $data["billPaymentId"]; ?>');" class="modalLink btn btn-xs btn-primary waves-effect">
                                                <i class="material-icons">cancel</i>
                                              </a>
                                            </td>
                                    
                                        </tr>
                                        <?php
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
  <?php } ?>


  <?php  if(!empty($otherAdjustmentApproval)){ ?>
    <section class="col-md-12 box">
        <div class="container-fluid">
           
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <div class="card">
                        <div class="body">
                          
                            <div class="table-responsive">
                                <table style="font-size: 12px" class="table table-bordered table-striped table-hover" data-page-length='100'>
                                  <thead>
                                    <tr>
                                      <th colspan="10">
                                        <center><h5>Other Adjustment Approval</h5><center>
                                        <p align="right">
                                            <button type="button" id="insert-otherAdj-chk" class="btn btn-xs btn-primary m-t-15 waves-effect"> <i class="material-icons">save</i> <span class="icon-name">Approve All</span></button>
                                        </p>
                                      </th>
                                    </tr>
                                  </thead>
                                    <thead>
                                        <tr>
                                            <th>
                                              <input class="checkallForOtherAdj" type="checkbox" name="selOtherAdjValue" id="otherAdj-basic_checkbox"/>
                                              <label for="otherAdj-basic_checkbox"></label>
                                            </th>
                                            <th>Initiator</th>
                                            <th>Bill No</th>
                                            <th>Bill Date</th>
                                            <th>Retailer</th>
                                            <th class="text-right">Net Amount</th>
                                            <th class="text-right">Pending Amount</th>
                                            <th class="text-right">Other Adjustment Amount</th>
                                            <th>Other Adjustment Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody id="otherTableBody">
                                        <?php
                                        $no=0;
                                        foreach ($otherAdjustmentApproval as $data) 
                                          {
                                           $no++;
                                            
                                           $dt=date_create($data['date']);
                                           $billdate = date_format($dt,'d-M-Y');

                                           $dt=date_create($data['otherAdjDate']);
                                           $otherAdjDate = date_format($dt,'d-M-Y H:i:sa');
                                        ?>
                                        <tr>
                                           <td>
                                             <input class="checkOtherAdj" type="checkbox" name="selOtherAdjValue" value="<?php echo $data['billPaymentId']; ?>" id="otherAdj-basic_checkbox_<?php echo $data['billPaymentId']; ?>" />
                                              <label for="otherAdj-basic_checkbox_<?php echo $data['billPaymentId']; ?>"></label>
                                           </td>
                                            <td><?php echo $data['initiatorName']; ?></td>
                                            <td><?php echo $data['billNo']; ?></td>
                                            <td><?php echo $billdate; ?></td>
                                            <td><?php echo $data['retailerName']; ?></td>
                                            <td class="text-right"><?php echo number_format($data['netAmount']); ?></td>
                                            <td class="text-right"><?php echo number_format($data['pendingAmt']); ?></td>
                                            <td class="text-right"><?php echo number_format($data['otherAdjAmount']); ?></td>
                                            <td><?php echo $otherAdjDate; ?></td>
                                            <td>
                                              <a target="_blank" href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs  btn-primary" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                                              <a href="javascript:void();" onclick="acceptOtherAdjustment(this,'<?php echo $data["billPaymentId"]; ?>');" class="modalLink btn btn-xs btn-primary waves-effect">
                                                <i class="material-icons">check</i>
                                              </a>
                                              <a href="javascript:void();" onclick="rejectOtherAdjustment(this,'<?php echo $data["billPaymentId"]; ?>');" class="modalLink btn btn-xs btn-primary waves-effect">
                                                <i class="material-icons">cancel</i>
                                              </a>
                                            </td>
                                    
                                        </tr>
                                        <?php
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

  <?php } ?>


<!-- Add Income -->
<div class="container">
  <div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
           <center> <h3 class="modal-title">Cash Reconciliation</h3> </center>

          </div>
          <div class="modal-body">
        <form method="post" role="form" onsubmit="return submitBankDeposit();" action="<?php echo site_url('owner/ExpensesController/rejectBankDeposit');?>"> 
                                <div class="row clearfix">
                                    <div class="col-md-12">

                                      <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                        <tr>
                                          <td>Date</td>
                                          <td>Employee</td>
                                          <td class="text-right">As per Accounts </td>
                                          <td class="text-right">Actual Cash Deposit </td>
                                          <td class="text-right">Short/Excess </td>
                                       </tr>
                                           <tr>
                                            <td><span id="bankDate"></span></td>
                                            <td><span id="bankEmp"></span></td>
                                            <td align="right"><span id="bankCash"></span></td>
                                            <td>
                                              <input type="text" autocomplete="off" placeholder="enter bank deposit" id="finalBankDeposit" onkeypress="return isNumber(event)" onblur="calAmount()" name="finalBankDeposit" class="form-control" required>
                                            </td>
                                            <td align="right"><span id="shortExcessCash">0</span>
                                              <input type="hidden" autocomplete="off" placeholder="enter bank deposit" id="finalShortExcess" name="finalShortExcess" class="form-control" required>
                                            </td>
                                          </tr>
                                      </table>

                                      <table style="font-size: 12px" id="myTable"  class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                        <tr>
                                          <tr>
                                            <td>Category </td>
                                            <td>Employee </td>
                                            <!-- <td>Company </td> -->
                                            <td>Amount </td>
                                            <td>Narration </td>
                                            <td>Action </td>
                                          </tr>
                                            <td id="expense"  style="display: block">
                                              <input type="text" autocomplete="off" placeholder="select category" list="categoryOutflowList" id="categoryOutflow" name="categoryOutflow[]" class="form-control" required> 
                                                  <datalist id="categoryOutflowList">
                                                    <?php foreach($cat_expense as $in){ ?>
                                                            <option><?php echo $in['categoryName']; ?></option>
                                                    <?php } ?>
                                         
                                                  </datalist>   
                                            </td>
                                            <td id="income" style="display: none">
                                                 <input type="text" autocomplete="off" placeholder="select category" list="categoryIncomeList" id="categoryOutflow" name="categoryOutflow[]" class="form-control">
                                                  <datalist id="categoryIncomeList">
                                                    <?php foreach($cat_income as $in){ ?>
                                                    <option><?php echo $in['categoryName']; ?></option>
                                                    <?php } ?>
                                                  </datalist>  
                                            </td>

                                            <td>
                                                <input type="text" autocomplete="off" placeholder="select employee" list="empNameOutflowList" id="empNameOutflow" name="empNameOutflow[]" class="form-control" required> 
                                                <datalist id="empNameOutflowList">
                                                    <?php foreach ($emp as $req_item): ?>
                                                      <option id="<?php echo $req_item['id'] ?>" value="<?php echo $req_item['name'] ?>" />
                                                    <?php endforeach ?> 
                                                </datalist>
                                                <input type="hidden" id="empOutflowId" name="empOutflowId[]" class="form-control"> 
                                              </td>
                                           <!--  <td>
                                                <input type="text" autocomplete="off" placeholder="select company" list="compNameOutflowList" id="compNameOutflow" name="compNameOutflow[]" class="form-control" required> 
                                                <datalist id="compNameOutflowList">
                                                <?php foreach ($companyDetails as $req_item): ?>
                                                    <option id="<?php echo $req_item['id'] ?>" value="<?php echo $req_item['name'] ?>" />
                                                  <?php endforeach ?> 
                                                </datalist> 
                                            </td> -->

                                           

                                          <td>
                                             <input type="text" onkeypress="return isNumber(event)"  autocomplete="off" onblur="calShortAmount()" placeholder="amount" id="cashAmtOutflow" name="cashAmtOutflow[]" class="form-control" required> 
                                          </td>

                                         

                                        
                                        <td>
                                          <input type="text" autocomplete="off" placeholder="narration" id="narrationOutflow" name="narrationOutflow[]" class="form-control" required>   
                                        </td>
                                         <td>
                                             <button type="button" onclick="addNewRow();" class="btn btn-xs btn-primary waves-effect">
                                      <span class="icon-name"> <i class="material-icons">add</i></span></button>   

                                      <button type="button" onclick="deleterow();" class="btn btn-xs btn-primary waves-effect">
                                      <span class="icon-name"> <i class="material-icons">remove</i></span></button>
                                          </td>
                                        </tr>
                                      </table>
                                  </div>   
                                </div>
                                <br>
                              
                                
                                <input type="hidden" autocomplete="off" placeholder="amount" id="bankDepId" name="bankDepId" class="form-control" required>

                                  <input type="hidden" autocomplete="off" placeholder="amount" id="cashierId" name="cashierId" class="form-control" required>

                                <input type="hidden" autocomplete="off" placeholder="amount" id="bankDepositDate" name="bankDepositDate" class="form-control" required>

                                <input type="hidden" autocomplete="off" placeholder="amount" id="bankDepAmount" name="bankDepAmount" class="form-control" required>

                                <input type="hidden" autocomplete="off" placeholder="amount" id="actualAcceptCash" name="actualAcceptCash" class="form-control" required> 

                                <input type="hidden" autocomplete="off" placeholder="amount" id="short_cash" name="short_cash" class="form-control" required> 
                                
                                <input type="hidden" autocomplete="off" placeholder="inoutStatus" id="inoutStatus" name="inoutStatus" class="form-control" required> 

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


<div class="modal fade" id="limitModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">Employee Details</h4>
          </div>
          <div class="modal-body">
         
          </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="ownerSalaryModal" role="dialog">
    <div class="modal-dialog">
      <!-- <div class="modal-content"> -->
        <!-- <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Employee Salary Details</h4>
          </div> -->
          <div id="salModalData" class="modal-body">
         
          </div>
      <!-- </div> -->
    </div>
  </div>


  <div class="container">
    <div class="modal fade" id="myModalMainCashBookBankDeposit" role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title text-center">Bank Deposit</h3>
                </div>
                <div class="modal-body" id="mainCashBookBody">
                  
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('/layouts/footerDataTable'); ?>

 <!-- <script type="text/javascript">
    function acceptExpenses(e,id,allocatedId){
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/acceptExpenseByOwner');?>",
                data:{"id" : id,"allocatedId" : allocatedId},
                success: function (data) {
                  if(data.trim()=="Expenses Accepted"){
                    alert(data);
                    location.reload(); 
                  }else{
                    alert(data);
                  }
                  
                }  
            });
        }
    }

      function rejectExpenses(e,id,allocatedId,empId,parking,challan,cng){
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/rejectExpenseByOwner');?>",
                data:{"id" : id,"allocatedId" : allocatedId,"empId":empId,"parking":parking,"challan":challan,"cng":cng},
                success: function (data) {
                  if(data.trim()=="Expenses Rejected"){
                    alert(data);
                    location.reload(); 
                  }else{
                    alert(data);
                  }
                  
                }  
            });
        }
    }
    
</script> -->

<!-- <script type="text/javascript">
  function acceptOwExpenses(e,id){
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/acceptOwExpenseByOwner');?>",
                data:{"id" : id},
                success: function (data) {
                  if(data.trim()=="Expenses Accepted"){
                    alert(data);
                    location.reload(); 
                  }else{
                    alert(data);
                  }
                  
                }  
            });
        }
    }

      function rejectOwExpenses(e,id,emp_id,amount,nature){
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/rejectOwExpenseByOwner');?>",
                data:{"id" : id,"empId":emp_id,"amount":amount,"nature":nature},
                success: function (data) {
                  if(data.trim()=="Expenses Rejected"){
                    alert(data);
                    location.reload(); 
                  }else{
                    alert(data);
                  }
                  
                }  
            });
        }
    }
</script> -->

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
    document.getElementById('calTotal').innerHTML = total.toFixed(2);
     document.getElementById('actualBankDeposit').value = total.toFixed(2);

  }
</script>

<script type="text/javascript">
    $(document).on('change','#empNameOutflow',function(){
          var empName = $("#empNameOutflowList option[value='" + $('#empNameOutflow').val() + "']").attr('id');
          $('#empOutflowId').val(empName);
    });
</script>

<script type="text/javascript">
   $(document).on('click','#bankDetails',function(){
        var id=$(this).attr('data-id');
        var date=$(this).attr('data-date');
        var emp=$(this).attr('data-emp');
        var empId=$(this).attr('data-empId');
        var amount=$(this).attr('data-amount');
        var openCloseBalance=$(this).attr('data-openCloseBalance');

        $('#bankDepId').val(id);
        $('#bankDepAmount').val(amount);
        $('#bankCash').text(amount);
        $('#bankDate').text(date);
        $('#bankEmp').text(emp);
        $('#cashierId').val(empId);
        $('#bankDepositDate').val(date);
        $('#bankDepositAmount').val(amount);

    });
</script>

<script type="text/javascript">
   $(document).on('click','#rejectMainCashBook',function(){
        var id=$(this).attr('data-id');
       
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('owner/ExpensesController/bankDepositView');?>",
            data:{id:id},
            success: function (data) {
              // alert(data);
                  $('#mainCashBookBody').html(data);
            }  
        });

    });
</script>

<script type="text/javascript">
   $(document).on('click','#acceptbankDetails',function(){
        var id=$(this).attr('data-id');
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('owner/ExpensesController/acceptBankDeposit');?>",
            data:{id:id},
            success: function (data) {
                if(data.trim()=="Bank Deposit accepted"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/owner/ExpensesController/allApprovals";
                }else{
                    alert(data);
                }
            }  
        });


        // alert(id+' '+date+' '+emp+' '+amount+' '+openCloseBalance); 
       
    });
</script>

<script type="text/javascript">
   $(document).on('click','#acceptMainCashBook',function(){
        var id=$(this).attr('data-id');
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('owner/ExpensesController/acceptMainCashBookBankDeposit');?>",
            data:{id:id},
            success: function (data) {
                if(data.trim()=="Bank Deposit accepted"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/owner/ExpensesController/allApprovals";
                }else{
                    alert(data);
                }
            }  
        });


        alert(id+' '+date+' '+emp+' '+amount+' '+openCloseBalance); 
       
    });
</script>

<script type="text/javascript">
  function checkBankDepositAmount(){
        var clos_bank_amt = Number(document.getElementById("bankDepositAmount").value);
        var collectedTotal = Number(document.getElementById("actualBankDeposit").value);

        if(collectedTotal>clos_bank_amt){
            alert('Amount is more than Bank Deposit amount.');
           return false;
        }else{
            return true;
        }
  }
  
</script>



<script type="text/javascript">
  function addNewRow(){
    var rowCount = $('#myTable tr').length-1;
    $('#myTable').append('<tr><td id="expense" style="display: block"><input type="text" autocomplete="off" placeholder="select category" list="categoryOutflowList" id="categoryOutflow" name="categoryOutflow[]" class="form-control" required><datalist id="categoryOutflowList"><?php foreach($cat_expense as $in){ ?><option><?php echo $in['categoryName']; ?></option><?php } ?></datalist></td><td id="income" style="display: none"><input type="text" autocomplete="off" placeholder="select category" list="categoryIncomeList" id="categoryOutflow" name="categoryOutflow[]" class="form-control"><datalist id="categoryIncomeList"><?php foreach($cat_income as $in){ ?><option><?php echo $in['categoryName']; ?></option><?php } ?> </datalist> </td><td><input type="text" autocomplete="off" placeholder="select employee" list="empNameOutflowList" id="empNameOutflow" name="empNameOutflow[]" class="form-control" required><datalist id="empNameOutflowList"><?php foreach ($emp as $req_item): ?><option id="<?php echo $req_item['id'] ?>" value="<?php echo $req_item['name'] ?>" /><?php endforeach ?></datalist><input type="hidden" id="empOutflowId" name="empOutflowId[]" class="form-control"></td><td><input type="text" onkeypress="return isNumber(event)"  autocomplete="off" value="0" placeholder="amount" onblur="calShortAmount()" id="cashAmtOutflow" name="cashAmtOutflow[]" class="form-control" required></td><td><input type="text" autocomplete="off" placeholder="narration" id="narrationOutflow" name="narrationOutflow[]" class="form-control" required> </td><td></td></tr>');
  }



  function deleterow() {
    var rowCount = $('#myTable tr').length;
    if(rowCount>1){
        rowCount=rowCount-1;
        document.getElementById("myTable").deleteRow(rowCount); 
    }
  }
</script>

<script type="text/javascript">
    function calAmount(){
        var bankDepAmount=document.getElementById("bankDepAmount").value;
        var finalBankDeposit=document.getElementById("finalBankDeposit").value;
        var total=parseInt(finalBankDeposit);
        var diff=parseInt(bankDepAmount)-parseInt(total);
        document.getElementById("actualAcceptCash").value=parseInt(total);
        if(diff>0){
            document.getElementById("inoutStatus").value="Outflow";
        }else{
            document.getElementById("inoutStatus").value="Inflow";
        }

        if(parseInt(bankDepAmount)==parseInt(finalBankDeposit)){
              alert('Close Popup and accept bank deposit.');
              location.reload(); 
              die();
        }
        
        if(diff>0){
            document.getElementById("shortExcessCash").innerHTML="<span style='color:red'>"+parseInt(diff)+"</span>";
            document.getElementById("short_cash").value=Math.abs(diff);
            document.getElementById("finalShortExcess").value=Math.abs(diff)
            
        }else{
            document.getElementById("shortExcessCash").innerHTML="<span style='color:blue'>"+parseInt(diff)+"</span>";
            document.getElementById("short_cash").value=Math.abs(diff)
            document.getElementById("finalShortExcess").value=Math.abs(diff)
        }

        if(diff>0){
            document.getElementById('expense').style.display="block";
            document.getElementById('income').style.display="none";
        }else{
            document.getElementById('expense').style.display="none";
            document.getElementById('income').style.display="block";
        }
    }
</script>

<script type="text/javascript">
   function calShortAmount(){
        var bankDepAmount=document.getElementById("bankDepAmount").value;
        var finalBankDeposit=document.getElementById("finalBankDeposit").value;
        var finalShortExcess=document.getElementById("finalShortExcess").value;
        if(finalShortExcess==""){
            alert('please enter bank deposit');
        }else{
            var cashAmtOutflow=document.getElementsByName("cashAmtOutflow[]");
            var total=0;
            for (var i = 0, iLen = cashAmtOutflow.length; i < iLen; i++) {
              total=parseInt(total)+parseInt(cashAmtOutflow[i].value);
            }
        }
       
    }

    function submitBankDeposit(){
        var bankDepAmount=document.getElementById("bankDepAmount").value;
        var finalBankDeposit=document.getElementById("finalBankDeposit").value;
        var finalShortExcess=document.getElementById("finalShortExcess").value;
        if(finalShortExcess==""){
            alert('please enter bank deposit');
        }else{
            var cashAmtOutflow=document.getElementsByName("cashAmtOutflow[]");
            var total=0;
            for (var i = 0, iLen = cashAmtOutflow.length; i < iLen; i++) {
              total=parseInt(total)+parseInt(cashAmtOutflow[i].value);
            }

            var short=finalShortExcess-total;
            short=Math.abs(short);
            if(short>0 || short<0){
                alert("please enter amount with equal to short amount :"+finalShortExcess);
                return false;
            }else{
                return true;
            }
            // return true;
        }
    }
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $("input:text").focus(function() { $(this).select(); } );
    });
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
    $(document).on('click','#emp_accept_id',function(){
         var id=$(this).attr('data-id');
         $.ajax({
            url : "<?php echo site_url('owner/OfficeAllocationController/empOwnerApproval');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
                // if(data.trim()==="Record accepted"){
                //     alert(data);
                //     window.location.href="<?php echo base_url();?>index.php/owner/OfficeAllocationController/empApproval";
                // }else{
                //     alert(data);
                // }

                $("#salModalData").html(data);
            }
        });
    });

    $(document).on('click','#emp_reject_id',function(){
         var id=$(this).attr('data-id');
         $.ajax({
            url : "<?php echo site_url('owner/OfficeAllocationController/empReject');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
                if(data==="Record rejected"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/owner/ExpensesController/allApprovals";
                }else{
                    alert(data);
                }
            }
        });
    });

 </script>

 <!-- For Process button Debit Transaction -->

<script type="text/javascript">
    $(document).on('click','#emp_processdebit_accept_id',function(){
         var id=$(this).attr('data-id');
         $.ajax({
            url : "<?php echo site_url('owner/ExpensesController/acceptProceeDebitAmount');?>",
            method : "POST",
            data : {billPaymentId: id},
            success: function(data){
                document.getElementById("process-debit-tbl").innerHTML=data;
            }
        });
    });

    $(document).on('click','#emp_processdebit_reject_id',function(){
         var id=$(this).attr('data-id');
         $.ajax({
            url : "<?php echo site_url('owner/ExpensesController/rejectProcessDebitAmount');?>",
            method : "POST",
            data : {billPaymentId: id},
            success: function(data){
                document.getElementById("process-debit-tbl").innerHTML=data;
            }
        });
    });
 </script>
<!-- End For Process button debit -->

<!-- For Non-Cash-Debit-Credit -->

<script type="text/javascript">
  //accept credit
    $(document).on('click','#emp_noncash_accept_id',function(){
         var id=$(this).attr('data-id');
         $.ajax({
            url : "<?php echo site_url('owner/ExpensesController/transactionAccept');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
                if(data.trim()==="Record accepted"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/owner/ExpensesController/allApprovals";
                }else{
                    alert(data);
                }
            }
        });
    });

    //accept debit
     $(document).on('click','#emp_debit_noncash_accept_id',function(){
         var id=$(this).attr('data-id');
         $.ajax({
            url : "<?php echo site_url('owner/ExpensesController/transactionAccept');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
                if(data.trim()==="Record accepted"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/owner/ExpensesController/allApprovals";
                }else{
                    alert(data);
                }
            }
        });
    });

     //reject credit
    $(document).on('click','#emp_noncash_reject_id',function(){
         var id=$(this).attr('data-id');
         $.ajax({
            url : "<?php echo site_url('owner/ExpensesController/transactionRejected');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
                if(data==="Record rejected"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/owner/ExpensesController/allApprovals";
                }else{
                    alert(data);
                }
            }
        });
    });

    //reject debit
    $(document).on('click','#emp_debit_noncash_reject_id',function(){
         var id=$(this).attr('data-id');
         $.ajax({
            url : "<?php echo site_url('owner/ExpensesController/transactionRejected');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
                if(data==="Record rejected"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/owner/ExpensesController/allApprovals";
                }else{
                    alert(data);
                }
            }
        });
    });

 </script>
<!-- End For Non-Cash-Debit-Credit -->

<!--Start for Expenses Details for Allocations  -->
<script type="text/javascript">
  function acceptExpenses(e,id,allocatedId){
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/acceptExpenseByOwner');?>",
                data:{"id" : id,"allocatedId" : allocatedId},
                success: function (data) {
                  location.reload(); 
                }  
            });
        }
    }

      function rejectExpenses(e,id,allocatedId,empId,parking,challan,cng){
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/rejectExpenseByOwner');?>",
                data:{"id" : id,"allocatedId" : allocatedId,"empId":empId,"parking":parking,"challan":challan,"cng":cng},
                success: function (data) {
                  location.reload(); 
                }  
            });
        }
    }
</script>


<script type="text/javascript">
  function acceptOwExpenses(e,id){
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/acceptOwExpenseByOwner');?>",
                data:{"id" : id},
                success: function (data) {
                  location.reload(); 
                }  
            });
        }
    }

      function rejectOwExpenses(e,id,emp_id,amount,nature,noteId){
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/rejectOwExpenseByOwner');?>",
                data:{"id" : id,"empId":emp_id,"amount":amount,"nature":nature,"noteId":noteId},
                success: function (data) {
                  // alert(data);die();
                  location.reload(); 
                }  
            });
        }
    }

    function acceptDayBookExpenses(e,id){
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/acceptDayBookExpenseByOwner');?>",
                data:{"id" : id},
                success: function (data) {
                  location.reload(); 
                }  
            });
        }
    }

    // For Cash Discount
    function acceptCashDiscount(e,id){
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/acceptCashDiscountAmount');?>",
                data:{"billPaymentId" : id},
                success: function (data) {
                  document.getElementById("cdTableBody").innerHTML=data;
                  // alert(data);
                  // location.reload(); 
                }  
            });
        }
    }

    function rejectCashDiscount(e,id){
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/rejectCashDiscountAmount');?>",
                data:{"billPaymentId" : id},
                success: function (data) {
                  document.getElementById("cdTableBody").innerHTML=data;
                }  
            });
        }
    }

    // For Office Adjustment
    function acceptOfficeAdjustment(e,id){
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/acceptOfficeAdjustmentAmount');?>",
                data:{"billPaymentId" : id},
                success: function (data) {
                  document.getElementById("officeTableBody").innerHTML=data;
                  // alert(data);
                  // location.reload(); 
                }  
            });
        }
    }

    function rejectOfficeAdjustment(e,id){
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/rejectOfficeAdjustmentAmount');?>",
                data:{"billPaymentId" : id},
                success: function (data) {
                  document.getElementById("officeTableBody").innerHTML=data;
                }  
            });
        }
    }

    // For Other Adjustment
    function acceptOtherAdjustment(e,id){
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/acceptOtherAdjustmentAmount');?>",
                data:{"billPaymentId" : id},
                success: function (data) {
                  document.getElementById("otherTableBody").innerHTML=data;
                  // alert(data);
                  // location.reload(); 
                }  
            });
        }
    }

    function rejectOtherAdjustment(e,id){
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/rejectOtherAdjustmentAmount');?>",
                data:{"billPaymentId" : id},
                success: function (data) {
                  document.getElementById("otherTableBody").innerHTML=data;
                }  
            });
        }
    }

    
</script>
<!--End for Expenses Details for Allocations  -->

<!-- For Other Adjustment Checkbox submit -->
<script type="text/javascript">
    var clicked = false;
    $(".checkallForOtherAdj").on("click", function() {
      $(".checkOtherAdj").prop("checked", !clicked);
      clicked = !clicked;
      this.innerHTML = clicked ? 'Deselect' : 'Select';
    });
</script>

 <script type="text/javascript">
    $("#insert-otherAdj-chk").on("click", function() {
    // jQuery("#insert-ins").on("click",function(){
        var selValue = [];
        $.each($("input[name='selOtherAdjValue']:checked"), function(){
                selValue.push($(this).val());
        });

        // alert(selValue);die();

        if(selValue.length>0){
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/acceptOtherAdjustmentAmountWithCheckbox');?>",
                data:{selValue:selValue},
                success: function (data) {
                    $("input[type=checkbox]").each(function(){
                        $(this).attr('checked', false);
                    });      
                    window.location.href="<?php echo base_url();?>index.php/owner/ExpensesController/allApprovals";
                }  
            });
        }else{
            alert('Please select Bills.');
        }
});
    
</script>
<!--End For Other Adjustment Checkbox submit -->


<!-- Start For Office Adjustment Checkbox submit -->
<script type="text/javascript">
    var clicked = false;
    $(".checkallForOfficeAdj").on("click", function() {
      $(".checkOfficeAdj").prop("checked", !clicked);
      clicked = !clicked;
      this.innerHTML = clicked ? 'Deselect' : 'Select';
    });
</script>

<script type="text/javascript">
    $("#insert-officeAdj-chk").on("click", function() {
        var selValue = [];
        $.each($("input[name='selOfficeAdjValue']:checked"), function(){
                selValue.push($(this).val());
        });

        if(selValue.length>0){
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/acceptOfficeAdjustmentAmountWithCheckbox');?>",
                data:{selValue:selValue},
                success: function (data) {
                    $("input[type=checkbox]").each(function(){
                        $(this).attr('checked', false);
                    });      
                    window.location.href="<?php echo base_url();?>index.php/owner/ExpensesController/allApprovals";
                }  
            });
        }else{
            alert('Please select Bills.');
        }
});
    
</script>
<!-- End For Office Adjustment Checkbox submit -->

<!-- Start For Cash Discount Checkbox submit -->
<script type="text/javascript">
    var clicked = false;
    $(".checkallForCD").on("click", function() {
      $(".checkCD").prop("checked", !clicked);
      clicked = !clicked;
      this.innerHTML = clicked ? 'Deselect' : 'Select';
    });
</script>

<script type="text/javascript">
    $("#insert-cd-chk").on("click", function() {
        var selValue = [];
        $.each($("input[name='selCdValue']:checked"), function(){
                selValue.push($(this).val());
        });

        if(selValue.length>0){
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/acceptCdAmountWithCheckbox');?>",
                data:{selValue:selValue},
                success: function (data) {
                    $("input[type=checkbox]").each(function(){
                        $(this).attr('checked', false);
                    });      
                    window.location.href="<?php echo base_url();?>index.php/owner/ExpensesController/allApprovals";
                }  
            });
        }else{
            alert('Please select Bills.');
        }
});
    
</script>
<!-- End For Cash Discount Checkbox submit -->


<!-- Start For Credit Accept Checkbox submit -->
<script type="text/javascript">
    var clicked = false;
    $(".checkallForCreditAccept").on("click", function() {
      $(".checkForCreditAccept").prop("checked", !clicked);
      clicked = !clicked;
      this.innerHTML = clicked ? 'Deselect' : 'Select';
    });
</script>
<script type="text/javascript">
    $("#insert-credit-chk").on("click", function() {
        var selValue = [];
        $.each($("input[name='selCreditAcceptValue']:checked"), function(){
                selValue.push($(this).val());
        });

        if(selValue.length>0){
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/allTransactionAcceptWithCheckBox');?>",
                data:{selValue:selValue},
                success: function (data) {
                    $("input[type=checkbox]").each(function(){
                        $(this).attr('checked', false);
                    });      
                    window.location.href="<?php echo base_url();?>index.php/owner/ExpensesController/allApprovals";
                }  
            });
        }else{
            alert('Please select Bills.');
        }
});
</script>
<!-- End For Credit Accept Checkbox submit -->

<!-- Start For Debit Accept Checkbox submit -->
<script type="text/javascript">
    var clicked = false;
    $(".checkallForDebitAccept").on("click", function() {
      $(".checkForDebitAccept").prop("checked", !clicked);
      clicked = !clicked;
      this.innerHTML = clicked ? 'Deselect' : 'Select';
    });
</script>
<script type="text/javascript">
    $("#insert-debit-chk").on("click", function() {
        var selValue = [];
        $.each($("input[name='selDebitAcceptValue']:checked"), function(){
                selValue.push($(this).val());
        });

        if(selValue.length>0){
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/allTransactionAcceptWithCheckBox');?>",
                data:{selValue:selValue},
                success: function (data) {
                    $("input[type=checkbox]").each(function(){
                        $(this).attr('checked', false);
                    });      
                    window.location.href="<?php echo base_url();?>index.php/owner/ExpensesController/allApprovals";
                }  
            });
        }else{
            alert('Please select Bills.');
        }
});
</script>
<!-- End For Debit Accept Checkbox submit -->

<!-- Start For Process Debit Accept Checkbox submit -->
<script type="text/javascript">
    var clicked = false;
    $(".checkallForProcessDebitAccept").on("click", function() {
      $(".checkForProcessDebitAccept").prop("checked", !clicked);
      clicked = !clicked;
      this.innerHTML = clicked ? 'Deselect' : 'Select';
    });
</script>
<script type="text/javascript">
    $("#insert-process-debit-chk").on("click", function() {
        var selValue = [];
        $.each($("input[name='selProcessDebitAcceptValue']:checked"), function(){
                selValue.push($(this).val());
        });

        if(selValue.length>0){
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/allProcessDebitAcceptWithCheckBox');?>",
                data:{selValue:selValue},
                success: function (data) {
                    $("input[type=checkbox]").each(function(){
                        $(this).attr('checked', false);
                    });      
                    window.location.href="<?php echo base_url();?>index.php/owner/ExpensesController/allApprovals";
                }  
            });
        }else{
            alert('Please select Bills.');
        }
});
</script>
<!-- End For Debit Accept Checkbox submit -->

<!-- Start For Office Expense Accept Checkbox submit -->
<script type="text/javascript">
    var clicked = false;
    $(".checkallForOfficeExpense").on("click", function() {
      $(".checkForOfficeExpense").prop("checked", !clicked);
      clicked = !clicked;
      this.innerHTML = clicked ? 'Deselect' : 'Select';
    });
</script>
<script type="text/javascript">
    $("#insert-officeExpense-chk").on("click", function() {
        var selValue = [];
        $.each($("input[name='selOfficeExpenseValue']:checked"), function(){
                selValue.push($(this).val());
        });

        if(selValue.length>0){
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/acceptAllOfficeExpenseByOwner');?>",
                data:{selValue:selValue},
                success: function (data) {
                    $("input[type=checkbox]").each(function(){
                        $(this).attr('checked', false);
                    });      
                    window.location.href="<?php echo base_url();?>index.php/owner/ExpensesController/allApprovals";
                }  
            });
        }else{
            alert('Please select Bills.');
        }
});
</script>
<!-- End For Office Expense Checkbox submit -->

<!-- Start For Market Expense Accept Checkbox submit -->
<script type="text/javascript">
    var clicked = false;
    $(".checkallForMarketExpense").on("click", function() {
      $(".checkForMarketExpense").prop("checked", !clicked);
      clicked = !clicked;
      this.innerHTML = clicked ? 'Deselect' : 'Select';
    });
</script>
<script type="text/javascript">
    $("#insert-marketExpense-chk").on("click", function() {
        var selValue = [];
        $.each($("input[name='selMarketExpenseValue']:checked"), function(){
                selValue.push($(this).val());
        });

        if(selValue.length>0){
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/acceptAllAllocationExpenseByOwner');?>",
                data:{selValue:selValue},
                success: function (data) {
                    $("input[type=checkbox]").each(function(){
                        $(this).attr('checked', false);
                    });      
                    window.location.href="<?php echo base_url();?>index.php/owner/ExpensesController/allApprovals";
                }  
            });
        }else{
            alert('Please select Bills.');
        }
});
</script>

<script>
  function checkBankDepostAmount(){
      var bankAmount = Number(document.getElementById("collectedDepositTotal_outBank").value);
      if(Number(bankAmount)>0){
        var availableAmount = Number(document.getElementById("cashBookAmtRet_outBank").value);
        if(availableAmount<bankAmount){
          alert('Amount is more than closing amount.');
          return false;
        }

        if (confirm('Do you want to submit this Bank Deposit. ?')) {
            $("#bankDepositButton").attr("disabled", true);
            return true;
        }else{
            $("#bankDepositButton").attr("disabled", true);
            return false;
        }
      }else{
          alert('Please enter notes details.');
          return false;
      }
  } 
</script>


<!-- for Bank Deposit -->
<script type="text/javascript">
  function calDepositMoney() {
    var a2000 = document.getElementById('add2000outBank').value;
    var a500 = document.getElementById('add500outBank').value;
    var a200 = document.getElementById('add200outBank').value;
    var a100 = document.getElementById('add100outBank').value;
    var a50 = document.getElementById('add50outBank').value;
    var a20 = document.getElementById('add20outBank').value;
    var a10 = document.getElementById('add10outBank').value;
    var coin = document.getElementById('coinoutBank').value;

    var rem2000 = document.getElementById('rem2000').innerHTML ;
    var rem500 = document.getElementById('rem500').innerHTML ;
    var rem200 = document.getElementById('rem200').innerHTML ;
    var rem100 = document.getElementById('rem100').innerHTML ;
    var rem50 = document.getElementById('rem50').innerHTML ;
    var rem20 = document.getElementById('rem20').innerHTML ;
    var rem10 = document.getElementById('rem10').innerHTML ;
    var remcoin = document.getElementById('remcoins').value ;

    var originalAmt = document.getElementById('cashBookAmtRet_outBank').value;
    // alert(originalAmt);

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

    document.getElementById('t2000outBank').innerHTML = c1;
    // document.getElementById('t1000').innerHTML = c2;
    document.getElementById('t500outBank').innerHTML = c3;
    document.getElementById('t200outBank').innerHTML = c4;
    document.getElementById('t100outBank').innerHTML = c5;
    document.getElementById('t50outBank').innerHTML = c6;
    document.getElementById('t20outBank').innerHTML = c7;
    document.getElementById('t10outBank').innerHTML = c8;
    document.getElementById('tcoinsoutBank').innerHTML = c9;

    document.getElementById('t2000outBankRem').innerHTML = (rem2000-a2000);
    // document.getElementById('t1000').innerHTML = c2;
    document.getElementById('t500outBankRem').innerHTML = (rem500-a500);
    document.getElementById('t200outBankRem').innerHTML = (rem200-a200);
    document.getElementById('t100outBankRem').innerHTML = (rem100-a100);
    document.getElementById('t50outBankRem').innerHTML = (rem50-a50);
    document.getElementById('t20outBankRem').innerHTML = (rem20-a20);
    document.getElementById('t10outBankRem').innerHTML = (rem10-a10);
    document.getElementById('tcoinsoutBankRem').innerHTML = (remcoin-coin);

    var total=0;
    total=total+c1+c3+c4+c5+c6+c7+c8;
    // total=total+c1+c2+c3+c4+c5+c6+c7+c8;
   
    total=parseFloat(total)+parseFloat(c9);
    // document.getElementById('calTotal_out').innerHTML = total.toFixed(2);
    document.getElementById('tcalTotal_outBank').innerHTML = total.toFixed(2);
    document.getElementById('collectedDepositTotal_outBank').value = total.toFixed(2);

}
</script>

<!-- <script type="text/javascript">
    function minmax(value, min, max) 
    {
        if(parseInt(value) < min || isNaN(parseInt(value))) 
            return value; 
        if((parseInt(value) > min && parseInt(value) < max)) 
            return value; 
        else if(parseInt(value) == max) 
            return value; 
        else 
            return 0;
    }
</script> -->

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
    function minmax(value, min, max) 
    {
        if(parseInt(value) ==0){
            $('#err-dataBank').html('');
            return value; 
        }else if(parseInt(value) < min || isNaN(parseInt(value))) {
            $('#err-dataBank').html('');
            return value; 
        } else if ((parseInt(value) > min && parseInt(value) < max)){ 
            $('#err-dataBank').html('');
            return value; 
        } else if (parseInt(value) == max) {
            $('#err-dataBank').html('');
            return value;
        } else {
            $('#err-dataBank').html('Notes can not more than available notes');
            return '';
        }
    }
</script>


<!-- End For Market Expense Checkbox submit -->