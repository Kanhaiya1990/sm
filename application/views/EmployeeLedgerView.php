<?php $this->load->view('/layouts/commanHeader'); ?>

<style type="text/css">
    @media screen and (min-width: 900px) {
        .modal-dialog {
          width: 900px; /* New width for default modal */
        }
        .modal-sm {
          width: 350px; /* New width for small modal */
        }
    }

    @media screen and (min-width: 900px) {
        .modal-lg {
          width: 900px; /* New width for large modal */
        }
    }

</style>

        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
               
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header flex-div">
                            <h2>
                                Employee Ledger
                            </h2>
                            <h2>
                                <p align="right">
                                    
                                <a href="<?php echo site_url('manager/EmployeeController/exportDataToExcel');?>">
                                <button class="btn btn-xs btnStyle bg-primary margin"><i class="material-icons">download</i> Download Advance Master</button>
                                </a>

                                <button data-toggle="modal" data-target="#SalaryAdvanceModal" class="btn btn-xs btnStyle bg-primary margin"><i class="material-icons">upload</i> Upload Advance Master</button>
                                </p>
                            </h2>
                        </div>
                        <?php 
                             if($this->session->flashdata('true')){
                           ?>
                             <div class="alert alert-success"> 
                               <?php  echo $this->session->flashdata('true'); ?>
                             </div>
                          <?php    
                          } else if($this->session->flashdata('err')){
                          ?>
                           <div class = "alert alert-danger">
                             <?php echo $this->session->flashdata('err'); ?>
                           </div>
                          <?php } ?>
                        <div class="body">
                            <!--<div class="table-responsive"> -->
                                <table id="tbl-employee" class="table table-bordered cust-tbl js-exportable dataTable" data-page-length='25'>
                                    <thead>
                                        <tr>
                                            <th>S. No</th>
                                            <th>Employee Code</th>
                                            <th>Employee Name</th>
                                            <th>Mobile</th>
                                            <th>Current Balance</th>
                                            <th style="display: none;">Current Balance</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                       <tr>
                                            <th>S. No</th>
                                            <th>Employee Code</th>
                                            <th>Employee Name</th>
                                            <th>Mobile</th>
                                            <th>Current Balance</th>
                                            <th style="display: none;">Current Balance</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                              $no=0;
                                              // print_r($empLedger);
                                              foreach ($empLedger as $data) 
                                                {
                                                
                                          ?>
                                    <tr>
                                        <td><?php echo $no+1; ?></td>
                                        <td><?php echo $data['code']; ?></td>
                                        <td><?php echo $data['name']; ?></td>
                                        <td><?php echo $data['mobile']; ?></td>
                                        <td>
                                            <?php 
                                                if($balance[$no]<0){ ?>
                                                  <a class="btn btn-xs btn-dark waves-effect" href="<?php echo site_url('manager/EmployeeController/employeeLedgerByEmp/'.$data['id']); ?>">
                                                  <?php  echo '<span style="color:red">'.str_replace('-','',intval($balance[$no])).' dr</span></a>'; 
                                                }else if($balance[$no]>0){ ?>
                                                   <a class="btn btn-xs btn-dark waves-effect" href="<?php echo site_url('manager/EmployeeController/employeeLedgerByEmp/'.$data['id']); ?>">
                                                <?php    echo '<span style="color:blue">'.intval($balance[$no]).' cr</span></a>'; 
                                                }else{
                                                  if($data['isSalaryEmp']==1){ ?>
                                                    <a class="btn btn-xs btn-dark waves-effect" href="<?php echo site_url('manager/EmployeeController/employeeLedgerByEmp/'.$data['id']); ?>"><span style="color:blue">0</span></a>
                                          <?php   }else{ ?>
                                                       <a class="btn btn-xs btn-dark waves-effect" href="<?php echo site_url('manager/EmployeeController/employeeLedgerByEmp/'.$data['id']); ?>"><span style="color:blue">0</span></a>
                                          <?php
                                                  }
                                                }
                                          ?>
                                            
                                        </td>
                                        <td style="display: none;">
                                          <?php 
                                                if($balance[$no]<0){ 
                                                  echo intval($balance[$no]); 
                                                }else if($balance[$no]>0){ 
                                                 echo intval($balance[$no]); 
                                                }else{
                                                  if($data['isSalaryEmp']==1){ 
                                                    echo 0; 
                                                  }else{
                                                    echo 0; 
                                                  }
                                                }
                                            ?>
                                        </td>
                                       
                                   </tr>  
                                     <?php
                                      $no++; 
                                        }
                                      ?>   
                                    </tbody>
                                </table>
                           <!-- </div> -->
                        </div>
                    </div>
                </div>
            </div> 
            <!-- #END# Basic Examples -->  
        </div>
    </section>
 
      <div class="modal fade" id="SalaryAdvanceModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
            <center><h4 class="modal-title">Upload Salary/Advance File</h4></center>
          </div>  
          <div class="modal-body">
           <form method="post" role="form"  enctype="multipart/form-data"  action="<?php echo site_url('manager/EmployeeController/employeeAdvanceDataUploading');?>"> 
                    <div class="col-md-12">

                        <div class="col-md-4">
                            <b> Deduction amount  </b>
                            <div class="input-group">
                              <div class="form-line">
                                <input onkeypress="return numbersonly(event)" autocomplete="off" type="text" name="advanceAmount" placeholder="Deduction amount" class="form-control" required >                               
                              </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <b> Salary/Advance File </b>
                            <div class="input-group">
                              <div class="form-line">
                               <input type="file" name="file" class="form-control" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">                               
                             </div>
                            </div>
                        </div>
                          
                        <div class="col-md-4">
                            <div class="input-group">
                                <button type="submit" class="btn btn-sm btn-primary m-t-20 margin">Upload</button>
                                <a href="<?php echo site_url('manager/EmployeeController/employeeLedger');?>">
                                  <button type="button" class="btn btn-sm btn-danger m-t-20 margin">Cancel</button>
                                </a>
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
  
<?php $this->load->view('/layouts/footerDataTable'); ?>

<script type="text/javascript">
  // $(document).ready(function() {
  //     $.fn.dataTable.ext.errMode = 'none';
  //     $('#tbl-employee').DataTable( {
  //         stateSave: false,
  //       dom: 'Bfrtip',
  //       buttons: [
  //         {
  //           extend: 'pdf',
  //           exportOptions: {
  //             columns: [ 0,1,2,3,5 ]
  //           }
  //         },
  //         {
  //           extend: 'excel',
  //           exportOptions: {
  //             columns: [ 0,1,2,3,5 ]
  //           }
  //         }
  //       ]
  //     });
  // });
</script>

<script type="text/javascript">
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
    $( window ).on( "load", function() {
        $("#tbl-employee").DataTable().page('last').draw('page');
    });
</script>