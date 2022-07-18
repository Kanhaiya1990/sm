<?php $this->load->view('/layouts/commanHeader'); ?>
<!--<meta http-equiv="refresh" content="300"/>-->
<!-- <style>
  .tableFixHead {
  overflow-y: auto;
  height: 400px;
}

.tableFixHead table {
  border-collapse: collapse;
  width: 100%;
}

.tableFixHead th,
.tableFixHead td {
  padding: 8px 16px;
}

.tableFixHead th {
  position: sticky;
  top: 0;
  background: #eee;
}
  </style> -->
        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
               
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Expenses Details for Allocations
                            </h2>
                            <h2>
                                <p align="right">
                                     <button class="btn btn-sm bg-primary margin" onClick="window.location.reload();"><i class="material-icons">refresh</i></button>
                                
                                </p> 
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive tableFixHead">
                                <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Allocation No.</th>
                                            <th>Date</th>
                                            <th>Employee</th>
                                             <th>Nature</th>
                                             <th>remark</th>
                                            <th>Parking</th>
                                            <th>Challan</th>
                                            <th>CNG</th>
                                            <th>Total Expense</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Allocation No.</th>
                                            <th>Date</th>
                                            <th>Employee</th>
                                             <th>Nature</th>
                                             <th>remark</th>
                                            <th>Parking</th>
                                            <th>Challan</th>
                                            <th>CNG</th>
                                            <th>Total Expense</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
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
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $data['code']; ?></td>
                                                <td><?php echo $date; ?></td>
                                                <td><?php echo $data['emp_name']; ?></td>
                                                <td></td>
                                                <td></td>
                                                <td><?php echo $data['parking']; ?></td>
                                                <td><?php echo $data['challan']; ?></td>
                                                <td><?php echo $data['cng']; ?></td>
                                                <td><?php echo $totalExpense; ?></td>
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

                                        $no=0;
                                        if(!empty($expnceDetail)){
                                        foreach ($expnceDetail as $data) 
                                          {
                                              $no++; 
                                              $dt=date_create($data['date']);
                                              $date = date_format($dt,'d-M-Y H:i:sa');
                                              
                                          ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td></td>
                                                <td><?php echo $date; ?></td>
                                                <td><?php echo $data['emp_name']; ?></td>
                                                 <td><?php echo $data['nature']; ?></td>
                                                <td><?php echo $data['narration']; ?></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><?php echo $data['amount']; ?></td>
                                                <td>
                                                  <button onclick="acceptOwExpenses(this,'<?php echo $data["id"]?>');" style="font-size : 12px;" id="signedOk" class="btn btn-xs btn-primary waves-effect"><i class="material-icons">check</i>
                                                 </button> 
                                                 <button onclick="rejectOwExpenses(this,'<?php echo $data["id"]?>','<?php echo $data["emp_id"]?>','<?php echo $data["amount"]?>','<?php echo $data["nature"]?>');" style="font-size : 12px;" id="signedOk" class="btn btn-xs btn-primary waves-effect"><i class="material-icons">cancel</i> 
                                                 </button>

                                                </td>
                                            </tr>
                                      <?php
                                            }
                                          }


                                          $no=0;
                                        if(!empty($dayBookDetail)){
                                        foreach ($dayBookDetail as $data) 
                                          {
                                            $expenseLimit=$this->OfficeAllocationModel->get_expenseDayBookLimit('expenses_limit');
                                              $no++; 
                                              $dt=date_create($data['closeDayBookDate']);
                                              $date = date_format($dt,'d-M-Y H:i:sa');
                                              if($data['totalExpense']>=$expenseLimit){
                                          ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $data['closeDayBookName']; ?></td>
                                                <td><?php echo $date; ?></td>
                                                <td><?php echo $data['emp_name']; ?></td>
                                                 <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><?php echo $data['totalExpense']; ?></td>
                                                <td>
                                                  <button onclick="acceptDayBookExpenses(this,'<?php echo $data["id"]?>');" style="font-size : 12px;" id="signedOk" class="btn btn-xs btn-primary waves-effect"><i class="material-icons">check</i>
                                                 </button> 
                                                 

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
            <!-- #END# Basic Examples -->  
        </div>
    </section>
<?php $this->load->view('/layouts/footerDataTable'); ?>

<script type="text/javascript">
  function acceptExpenses(e,id,allocatedId){
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/acceptExpenseByOwner');?>",
                data:{"id" : id,"allocatedId" : allocatedId},
                success: function (data) {
                  // if(data.trim()=="Expenses Accepted"){
                  //   alert(data);
                  //   location.reload(); 
                  // }else{
                  //   alert(data);
                  // }
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
                  // if(data.trim()=="Expenses Rejected"){
                  //   alert(data);
                  //   location.reload(); 
                  // }else{
                  //   alert(data);
                  // }
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
                  // if(data.trim()=="Expenses Accepted"){
                  //   alert(data);
                  //   location.reload(); 
                  // }else{
                  //   alert(data);
                  // }
                  location.reload(); 
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
                  // if(data.trim()=="Expenses Rejected"){
                  //   alert(data);
                  //   location.reload(); 
                  // }else{
                  //   alert(data);
                  // }
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
                  // if(data.trim()=="Expenses Rejected"){
                  //   alert(data);
                  //   location.reload(); 
                  // }else{
                  //   alert(data);
                  // }
                  location.reload(); 
                }  
            });
        }
    }
</script>