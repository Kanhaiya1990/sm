<?php 
    $this->load->view('/layouts/commanHeader');
    $projectSessionName="";
    $id=0;
    if (isset($this->session->userdata['codeKeyData'])) {
        $projectSessionName= ($this->session->userdata['codeKeyData']['codeKeyValue']);
    }

    if ($projectSessionName !="") {
        $email = ($this->session->userdata[$projectSessionName]['email']);
        $mobile = ($this->session->userdata[$projectSessionName]['mobile']);
        $id = ($this->session->userdata[$projectSessionName]['id']);
        $designation = ($this->session->userdata[$projectSessionName]['designation']);
        $des=explode(',',$designation);
        $des = array_map('trim', $des);
    } else {
        redirect("UserAuthentication");
    }

?>

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

.header-auto-scroll{
    padding-top: 60px;
    height: auto;
    overflow-y: scroll;
}
</style>
     <section class="col-md-12 box header-auto-scroll">
        <div class="container-fluid">
          <div class="row clearfix">

          <!--  <div class="col-md-2"> 
                    <button data-toggle="modal" data-target="#pendbillUploadModal" class="btn bg-primary margin"><i class="fa fa-upload"></i>Pend Bills Import</button>
          </div> -->
          <?php if (in_array('owner', $des)) {  ?>
            <!--  <div class="col-md-2"> 
               <a href="<?php echo site_url('manager/EmployeeController/employeeData');?>"> 
                  <button class="btn bg-primary margin"><i class="fa fa-upload"></i>Employee Import</button>
                </a>
              </div>
             <div class="col-md-2"> 
                <button data-toggle="modal" data-target="#cancelBillUploadModal" class="btn bg-primary margin"><i class="fa fa-upload"></i>Cancel Bills Import</button>
              </div> -->
          <?php } ?>
               <!-- <a href="<?php echo site_url('owner/OfficeAllocationController/billsTransactionData');?>">
                <div class="col-xs-2">
                    <div class="info-box bg-indigo hover-zoom-effect hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">upload</i>
                        </div>
                        <div class="content">
                            <div class="text">Bills Transactions Import</div>
                        </div>
                    </div>
                </div>
              </a> -->

               <!-- <a href="<?php echo site_url('admin/BillTransactionController/changeNeftChequeTransactions');?>">
                <div class="col-xs-2">
                    <div class="info-box bg-indigo hover-zoom-effect hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">edit</i>
                        </div>
                        <div class="content">
                            <div class="text">Change NEFT/Cheque Entries</div>
                        </div>
                    </div>
                </div>
              </a> -->

          <?php if (in_array('owner', $des) || in_array('cashier', $des) || in_array('senior_manager', $des) || in_array('manager', $des) || in_array('operator', $des)) {  ?>
              <a href="<?php echo site_url('CompanyDataUploadingController');?>">
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box bg-indigo hover-zoom-effect hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">upload</i>
                        </div>
                        <div class="content">
                            <div class="text">Latest Bills Import</div>
                        </div>
                    </div>
                </div>
              </a>
          <?php } ?>

          <?php if (in_array('owner', $des) || in_array('cashier', $des) || in_array('senior_manager', $des) || in_array('manager', $des) || in_array('operator', $des) || in_array('godownkeeper', $des)) {  ?>
            <a href="<?php echo site_url('AdHocController/billSearch');?>">
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box bg-cyan hover-zoom-effect hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">search</i>
                        </div>
                        <div class="content">
                            <div class="text">Search Bills</div>
                        </div>
                    </div>
                </div>
              </a>
          <?php } ?>

           <?php if (in_array('owner', $des) || in_array('cashier', $des) || in_array('senior_manager', $des) || in_array('manager', $des) || in_array('operator', $des) || in_array('godownkeeper', $des)) {  ?>
            <a href="<?php echo site_url('AdHocController/allRetailerHistory');?>">
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box bg-teal hover-zoom-effect hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">list</i>
                        </div>
                        <div class="content">
                            <div class="text">Retailer History</div>
                        </div>
                    </div>
                </div>
              </a>
          <?php } ?>

            <?php if (in_array('owner', $des) || in_array('cashier', $des) ||  in_array('senior_manager', $des)) {  ?>
          

              <a href="<?php echo site_url('NonAllocationBillsController/nonCashDebitCredit');?>">
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box bg-brown hover-zoom-effect hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">euro_symbol</i>
                        </div>
                        <div class="content">
                            <div class="text">Non Cash Credit/Debit</div>
                        </div>
                    </div>
                </div>
              </a>
          <?php } ?>

            <?php if (in_array('owner', $des) || in_array('cashier', $des) || in_array('senior_manager', $des) || in_array('manager', $des)) {  ?>
          
             <a href="<?php echo site_url('manager/EmployeeController/employeeClearance');?>">
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box bg-blue hover-zoom-effect hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">account_box</i>
                        </div>
                        <div class="content">
                            <div class="text">Employee Clearance</div>
                        </div>
                    </div>
                </div>
              </a>

              <a href="<?php echo site_url('BillTransactionController/retailerwiseDetails');?>">
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box bg-blue-grey hover-zoom-effect hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">edit</i>
                        </div>
                        <div class="content">
                            <div class="text">Retailer Wise Outstanding</div>
                        </div>
                    </div>
                </div>
              </a>

            <?php } ?>
          </div>

          <div class="row clearfix">
            <?php if (in_array('owner', $des)) {  ?>
                <a href="<?php echo site_url('admin/BillTransactionController');?>">
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box bg-pink hover-zoom-effect hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">edit</i>
                        </div>
                        <div class="content">
                            <div class="text">Change Bill Transaction</div>
                        </div>
                    </div>
                </div>
                </a>
            <?php } ?>

            <a href="<?php echo site_url('BillTransactionController/cancelledBills');?>">
              <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                  <div class="info-box bg-red hover-zoom-effect hover-expand-effect">
                      <div class="icon">
                          <i class="material-icons">cancel</i>
                      </div>
                      <div class="content">
                          <div class="text">Cancelled Bills</div>
                      </div>
                  </div>
              </div>
            </a>

            <a href="<?php echo site_url('AllocationByManagerController/nonAllocatedBillsDetails');?>">
              <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                  <div class="info-box bg-green hover-zoom-effect hover-expand-effect">
                      <div class="icon">
                          <i class="material-icons">build</i>
                      </div>
                      <div class="content">
                          <div class="text">Unaccounted Bills</div>
                      </div>
                  </div>
              </div>
            </a>

            <?php if (in_array('owner', $des)) {  ?>
                <a href="<?php echo site_url('TallyExportController');?>">
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box bg-pink hover-zoom-effect hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">edit</i>
                        </div>
                        <div class="content">
                            <div class="text">Tally Export</div>
                        </div>
                    </div>
                </div>
                </a>

            <?php } ?>
            <?php if (in_array('owner', $des) || in_array('cashier', $des) || in_array('senior_manager', $des) || in_array('operator', $des)) {  ?>
                <a href="<?php echo site_url('AdHocController/debitNoteBills');?>">
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box bg-red hover-zoom-effect hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">edit</i>
                        </div>
                        <div class="content">
                            <div class="text">Multiple Debit Notes</div>
                        </div>
                    </div>
                </div>
                </a>

                <a href="<?php echo site_url('AdHocController/creditNoteBills');?>">
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box bg-grey hover-zoom-effect hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">edit</i>
                        </div>
                        <div class="content">
                            <div class="text">Bill Journal Entry</div>
                        </div>
                    </div>
                </div>
                </a>

                <!-- <a href="<?php echo site_url('AdHocController/debitToEmployeeBills');?>">
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box bg-blue hover-zoom-effect hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">edit</i>
                        </div>
                        <div class="content">
                            <div class="text">Multiple Employee Debit Entry</div>
                        </div>
                    </div>
                </div>
                </a> -->
            <?php } ?>
          </div>
          <?php if (in_array('owner', $des) || in_array('senior_manager', $des)) {  ?>
           

          <div class="row clearfix">
               <!-- Task Info -->
                 <!-- <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="header">
                            <h4>Division wise Outstanding</h4>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table style="font-size:12px" class="table table-hover dashboard-task-infos">
                                    <thead>
                                        <tr>
                                            <th>Division</th>
                                            <th class="text-right">Unaccounted</th>
                                            <th class="text-right">Pending Supply</th>
                                            <th class="text-right">Signed Bills</th>
                                            <th class="text-right">Cheque in hand</th>
                                            <th class="text-right">Banked Cheques</th>
                                            <th class="text-right">Total Outstanding</th>
                                        </tr>
                                    </thead>
                                    <tbody id='compOutstanding'>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>  -->
                <!-- #END# Task Info -->

                <!-- Task Info -->
                 <!-- <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="header">
                            <h4>Division wise Sale</h4>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table style="font-size:12px" class="table table-hover dashboard-task-infos">
                                    <thead>
                                        <tr>
                                            <th>Division</th>
                                            <th class="text-right">Daily</th>
                                            <th class="text-right">Monthly</th>
                                            <th class="text-right">Same Month Last Year</th>
                                            <th class="text-right">Growth</th>
                                            <th class="text-right">Last Month</th>
                                            <th class="text-right">YTD</th>
                                            <th class="text-right">Growth</th>
                                        </tr>
                                    </thead>
                                    <tbody id="divisionSale">
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>  -->
                <!-- #END# Task Info -->
          </div>

          <div class="row clearfix">
              <!-- Task Info -->
              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <!-- <div class="card">
                        <div class="header">
                            <h4>Concerning Bills</h4>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table style="font-size:12px" class="table table-hover dashboard-task-infos">
                                    <thead>
                                        <tr>
                                            <th>Particular</th>
                                            <th class="text-right">Count</th>
                                            <th class="text-right">Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-left">Pending Allocations</td>
                                            <td class="text-right">
                                                <a class="badge badge-secondary" href="<?php echo site_url('SrController/lostBills'); ?>">
                                                <?php echo number_format($pendingAllocationCount); ?>
                                                </a>
                                            </td>
                                            <td class="text-right"> <?php echo number_format($pendingAllocationTotal); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Lost Bill</td>
                                            <td class="text-right">
                                                <a class="badge badge-secondary" href="<?php echo site_url('SrController/lostBills'); ?>">
                                                <?php echo number_format($lostBillsCount); ?>
                                                </a>
                                            </td>
                                            <td class="text-right"> <?php echo number_format($lostBills); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Lost Cheque</td>
                                            <td class="text-right">
                                                <a class="badge badge-secondary" href="<?php echo site_url('SrController/lostCheques'); ?>">
                                                <?php echo number_format($lostChequesCount); ?>
                                                </a>
                                            </td>
                                            <td class="text-right"> <?php echo number_format($lostCheques); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Pending NEFT</td>
                                            <td class="text-right">
                                                <a class="badge badge-secondary" href="<?php echo site_url('SrController/unclearedNeft'); ?>">
                                                <?php echo number_format($lostNeftCount); ?>
                                                </a>
                                            </td>
                                            <td class="text-right"> <?php echo number_format($lostNeft); ?></td>
                                        </tr>
                                       
                                        <tr>
                                            <td class="text-left">Resend</td>
                                            <td class="text-right">
                                                <a class="badge badge-secondary" href="<?php echo site_url('SrController/resendBills'); ?>">
                                                <?php echo number_format($resendBillsCount); ?>
                                                </a>
                                            </td>
                                            <td class="text-right"> <?php echo number_format($resendBills); ?></td>
                                        </tr>

                                        <tr>
                                            <td class="text-left">Uncleared Cheques in Bank</td>
                                            <td class="text-right">
                                                <a class="badge badge-secondary" href="<?php echo site_url('CashAndChequeController/DesktopBill'); ?>">
                                               0
                                                </a>
                                            </td>
                                            <td class="text-right"> 0</td>
                                        </tr>
                                      
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>-->
                </div> 
                <!-- #END# Task Info -->
            </div>
            <!-- <div class="row clearfix">
                 <div id="chartContainer" style="height: 500px; width: 100%;"></div>
            </div> -->
        <?php } ?>

           
        
    </section>


     <div class="modal fade" id="newBillUploadModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
            <center><h4 class="modal-title">Upload New Data for Company Bills</h4></center>
          </div>
          <div class="modal-body">
           <form method="post" role="form"  enctype="multipart/form-data"  action="<?php echo site_url('DataUploadingController/importAllFiles');?>"> 
                             
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <b> Company </b>
                                         <div class="input-group">
                                         <select name="company" class="form-control" id="excelcompany" required>
                                            <option value=''>--select Company--</option>
                                               <?php 
                                                $no=0;
                                                foreach($company as $item){
                                                ?>
                                                    <option value='<?php echo $item['name'];?>'><?php echo $item['name'];?></option>
                                                <?php
                                                    $no++;
                                                  } 
                                                ?>
                                        </select>
                                    </div>  
                                    </div>
                                    <div class="col-md-3">
                                        <b id="billsTitle"> Bills </b>
                                        <div class="input-group">
                                            <span class="input-group-addon"></span>
                                             <input type="file" name="billFile" class="form-control" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <b id="billDetailsTitle"> Bills Details </b>
                                        <div class="input-group">
                                            <span class="input-group-addon"></span>
                                             <input type="file" name="billDetailFile" class="form-control" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <b id="retailersTitle"> Retailer Details </b>
                                        <div class="input-group">
                                            <span class="input-group-addon"></span>
                                             <input type="file" name="retailerDetailFile" class="form-control" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                        </div>
                                    </div>
                               </div>    
                          <div class="col-md-12">
                            <div class="col-md-12">
                              <div class="input-group">
                                  <button type="submit" class="btn bg-primary margin">Import</button>
                                  <button type="button" class="btn bg-primary margin" data-dismiss="modal">Close</button>
                              </div>
                            </div>
                          </div>
                     
              </form>
          </div>
          <div class="modal-footer">
          
          </div>
      </div>
    </div>
  </div>


<div class="modal fade" id="cancelBillUploadModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
            <center><h4 class="modal-title">Upload New Data for Company Bills</h4></center>
          </div>
          <div class="modal-body">
           <form method="post" role="form"  enctype="multipart/form-data"  action="<?php echo site_url('DataUploadingController/cancelBillsImport');?>"> 
                             
                    <div class="col-md-12">
                        <div class="col-md-3">
                            <b> Company </b>
                             <div class="input-group">
                             <select name="company" class="form-control" id="cancelExcelCompany" required>
                                <option value=''>--select Company--</option>
                                   <?php 
                                    $no=0;
                                    foreach($company as $item){
                                    ?>
                                        <option value='<?php echo $item['name'];?>'><?php echo $item['name'];?></option>
                                    <?php
                                        $no++;
                                      } 
                                    ?>
                            </select>
                        </div>  
                        </div>
                     
                        <div class="col-md-3">
                            <b> Files </b>
                            <div class="input-group">
                                <span class="input-group-addon"></span>
                                 <input type="file" name="cancelBillFile" class="form-control" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                            </div>
                        </div>
                    </div>    
                    <div class="col-md-12">
                      <div class="col-md-12">
                        <div class="input-group">
                            <button type="submit" class="btn bg-primary margin">Import</button>
                            <button type="button" class="btn bg-primary margin" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
              </form>
          </div>
          <div class="modal-footer">
          
          </div>
      </div>
    </div>
  </div>


     <div class="modal fade" id="billUploadModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
            <center><h4 class="modal-title">Upload Company Bills</h4></center>
          </div>
          <div class="modal-body">
           <form method="post" role="form"  enctype="multipart/form-data"  action="<?php echo site_url('ExcelController/billsImport');?>"> 
                             
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <b> Company </b>
                                         <div class="input-group">
                                         <select name="company" class="form-control" id="company">
                                            <option>--select Company--</option>
                                               <?php 
                                                $no=0;
                                                foreach($company as $item){
                                                ?>
                                                    <option value='<?php echo $item['name'];?>'><?php echo $item['name'];?></option>
                                                <?php
                                                    $no++;
                                                  } 
                                                ?>
                                        </select>
                                    </div>  
                                    </div>
                                    <div class="col-md-6">
                                        <b> Excel </b>
                                         <div class="input-group">
                                            <span class="input-group-addon"></span>
                                             <input type="file" name="file" class="form-control" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">                               
                                          </div>
                                    </div>
                                    
                                 <div class="col-md-12">
                                        <div class="input-group">
                                    <button type="submit" class="btn bg-primary margin">Import</button>
                                    </div>
                                        </div>
                                 </div>
                        </form>
          </div>
          <div class="modal-footer">
          
          </div>
      </div>
    </div>
  </div>

 <div class="modal fade" id="pendbillUploadModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
            <center><h4 class="modal-title">Upload Pend Amt Bills</h4></center>
          </div>
          <div class="modal-body">
           <form method="post" role="form"  enctype="multipart/form-data"  action="<?php echo site_url('DataUploadingController/billPendDataUploadExcel');?>"> 
                             
                                <div class="col-md-12">
                                    
                                    <div class="col-md-6">
                                        <b> Excel </b>
                                         <div class="input-group">
                                            <span class="input-group-addon"></span>
                                             <input type="file" name="pendfile" class="form-control" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">                               
                                          </div>
                                    </div>
                                    
                                 <div class="col-md-12">
                                        <div class="input-group">
                                    <button type="submit" class="btn bg-primary margin">Import</button>
                                    </div>
                                        </div>
                                 </div>
                        </form>
          </div>
          <div class="modal-footer">
          
          </div>
      </div>
    </div>
  </div>

     <div class="modal fade" id="billDetailUploadModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
            <center><h4 class="modal-title">Upload Bill Detail</h4></center>
          </div>
          <div class="modal-body">
                                       <form method="post" role="form"  enctype="multipart/form-data"  action="<?php echo site_url('ExcelController/billDetailsImport');?>"> 
                            
                                <div class="col-md-12">
                                     <div class="col-md-6">
                                        <b> Company </b>
                                         <div class="input-group">
                                         <select name="company" class="form-control" id="company">
                                            <option>--select Company--</option>
                                               <?php 
                                                $no=0;
                                                foreach($company as $item){
                                                ?>
                                                    <option value='<?php echo $item['name'];?>'><?php echo $item['name'];?></option>
                                                <?php
                                                    $no++;
                                                  } 
                                                ?>
                                        </select>
                                    </div>  
                                    </div>
                                    <div class="col-md-6">
                                        <b> Excel </b>
                                         <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-excel-o"></i></span>
                                             <input type="file" name="file" class="form-control" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                         </div>
                                    </div>
                                </div>
                                <div>
                                    <button type="submit" class="btn bg-primary margin"><i class="fa fa-upload"></i> &nbsp Import</button>
                                </div>
                            </form>
                            
          </div>
          <div class="modal-footer">
          
          </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="outletUploadModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
            <center><h4 class="modal-title">Upload Outlet Summery</h4></center>
          </div>
          <div class="modal-body">
                                       <form method="post" role="form"  enctype="multipart/form-data"  action="<?php echo site_url('ExcelController/routeRetailerImport');?>"> 
                            
                                <div class="col-md-12">
                                     <div class="col-md-6">
                                        <b> Company </b>
                                         <div class="input-group">
                                         <select name="company" class="form-control" id="company">
                                            <option>--select Company--</option>
                                               <?php 
                                                $no=0;
                                                foreach($company as $item){
                                                ?>
                                                    <option value='<?php echo $item['name'];?>'><?php echo $item['name'];?></option>
                                                <?php
                                                    $no++;
                                                  } 
                                                ?>
                                        </select>
                                    </div>  
                                    </div>
                                    <div class="col-md-6">
                                        <b> Excel </b>
                                         <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-excel-o"></i></span>
                                             <input type="file" name="file" class="form-control" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                         </div>
                                    </div>
                                </div>
                                <div>
                                    <button type="submit" class="btn bg-primary margin"><i class="fa fa-upload"></i> &nbsp Import</button>
                                </div>
                            </form>
                            
          </div>
          <div class="modal-footer">
          
          </div>
      </div>
    </div>
  </div>

<?php $this->load->view('/layouts/footerDataTable'); ?>

<script type="text/javascript">
//   window.onload = function () {
      
//     var dataArray = <?php echo json_encode($salesmanArray); ?>;
//     var unaccountObj = [];
//     var pendingObj = [];
//     var signedObj = [];

//     for (let i = 0; i < dataArray.length; i++) {
//         unaccountObj.push({label: dataArray[i]["salesman"], y:Number(dataArray[i]["unaccounted"])});
//         pendingObj.push({label: dataArray[i]["salesman"], y:Number(dataArray[i]["pendingSupply"])});
//         signedObj.push({label: dataArray[i]["salesman"], y:Number(dataArray[i]["signed"])});
//     } 

//     var chart = new CanvasJS.Chart("chartContainer",
//     {
//       title:{
//         text: "Salesman Outstanding.", 
//         fontSize: 20,
//         fontFamily: "tahoma",
//         // fontWeight: "bolder",
//         padding: 10
//       },
//       axisX: {
//         valueFormatString: "",
//         interval: 1,
//         intervalType: "month"
//       },
//       data:[
//         {
//             type: "stackedBar",
//             legendText: "Unaccounted",
//             showInLegend: "true",
//             dataPoints: unaccountObj
//         },
//         {
//             type: "stackedBar",
//             legendText: "Pending Supply",
//             showInLegend: "true",
//             dataPoints:pendingObj
//         },
//         {
//             type: "stackedBar",
//             legendText: "signed",
//             showInLegend: "true",
//             dataPoints:signedObj
//         }
//       ]
//     });

// chart.render();
// }
</script>

<script type="text/javascript">
    // $(document).ready(function() { 
    //     $(window).bind("load", function() {
    //         $.ajax({
    //             type: "POST",
    //             url:"<?php echo site_url('DashbordController/divisionWiseSale');?>",
    //                 data:{},
    //                 success: function (data) {
    //                     $('#divisionSale').html(data);
    //                 }  
    //         });
    //     });
    // });

</script>

<script type="text/javascript">
    // $(document).ready(function() { 
    //     $(window).bind("load", function() {
    //         $.ajax({
    //             type: "POST",
    //             url:"<?php echo site_url('DashbordController/companyWiseOutstanding');?>",
    //                 data:{},
    //                 success: function (data) {
    //                     $('#compOutstanding').html(data);
    //                 }  
    //         });
    //     });
    // });

</script>




 <!-- <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script></head> -->