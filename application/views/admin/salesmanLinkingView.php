<?php $this->load->view('/layouts/commanHeader'); ?>

<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/>
<section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Salesman Linking</h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <?php if($responce = $this->session->flashdata('Successfully')): ?>
                                      <div class="box-header">
                                         <div class="alert alert-success"><?php echo $responce;?></div>
                                      </div>
                                  <?php endif;?>
                                  <br>
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S No.</th>
                                            <th>Salesman Code</th>
                                            <th>Salesman Name</th>
                                            <th>Company</th>
                                            <th>Designation</th>
                                            <th>Assigned Salesman</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                  <?php 
                                      if(!empty($employee)){
                                        $no=0;
                                        foreach ($employee as $data) {
                                            $status="";
                                            $salesmanExist=$this->EmployeeModel->getLinkedSalesman('salesman_linking',$data['salesmanCode'],$data['salesman']);  
                                            if(!empty($salesmanExist)){
                                                $empId=$salesmanExist[0]['employeeId'];
                                                if($empId !=0){
                                                  $employeeDetails=$this->EmployeeModel->load('employee',$empId);
                                                  $status= $employeeDetails[0]['name']; 
                                                }
                                            }
                                            $no++;
                                  ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td><?php echo $data['salesmanCode']; ?></td>
                                            <td><?php echo $data['salesman']; ?></td>
                                            <td><?php echo $data['compName']; ?></td>
                                            <td><?php echo 'Salesman'; ?></td>
                                            <td>
                                              <?php echo $status; ?>
                                            </td>
                                            <td>
                                                <a id="salesman-id" data-code="<?php echo $data['salesmanCode']; ?>" data-name="<?php echo $data['salesman']; ?>" data-toggle="modal" data-target="#modal-lg">
                                                    <i class="btn btn-xs btn-primary material-icons">edit</i>
                                                </a>
                                            <?php if($status !=""){ ?>
                                                <a href="<?php echo site_url('admin/EmployeeController/cancelSalesmanLinking/'.$data['salesmanCode']); ?>">
                                                    <i class="btn btn-xs btn-danger material-icons">cancel</i>
                                                </a>
                                            <?php } ?>
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
            <!-- #END# Basic Examples -->  
        </div>
    </section>



<div class="modal fade" id="modal-lg">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Salesman Linking for <span id="salesmanLinkName"></span></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="<?php echo site_url('admin/EmployeeController/insertSalesmanLinking'); ?>" method="post">
                <div class="card-body">
                  <input type="hidden" id="emp-code" readonly name="salesmancode" value="" class="form-control">
                  <input type="hidden" id="emp-name" readonly name="salesmanname" value="" class="form-control">
                      <div class="form-group">
                        <label>Select Salesman</label>
                        <!-- <select id="salesmanSelectName" name="empId" class="form-control" required>
                          <option value="">Select salesman</option>
                          <?php if(!empty($salesman)){
                              foreach($salesman as $data){
                          ?>
                              <option value="<?php echo $data['id'];?>"><?php echo $data['name'];?></option>
                          <?php 
                                }
                              } 
                          ?>
                         
                        </select> -->
                        <div class="form-line">
                        <input type="text" name="empId" id="salesmanSelectName" list="salesmanList" autocomplete="off" class="form-control date" placeholder="Enter salesman name">
                          <datalist id="salesmanList">
                          <?php if(!empty($salesman)){
                              foreach($salesman as $data){
                          ?>
                              <option id="<?php echo $data['id'] ?>" value="<?php echo $data['name'] ?>" />
                        
                          <?php 
                              }
                            }
                          ?> 
                          </datalist>
                        </div>
                          <input type="hidden" id="empSelectedId" name="empSelectedId" class="form-control"> 
                        <div id="errorMsg" style="color:red"></div>
                      </div>
                      
                </div>
                <div class="card-footer">
                  <button id="salesSubmit" type="submit" class="btn btn-primary">Submit</button>
                  <button id="closeModalId" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
              </form>
      </div>
      <!-- <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>
<?php $this->load->view('/layouts/footerDataTable'); ?>

 <script type="text/javascript">
//   $('#salesman-id').on('hidden.bs.modal', function (e) {           
//       location.reload();
//       $('#salesman-id').show();
//   })
// </script>

<script type="text/javascript">
    $(document).on('click','#salesman-id',function(){
        // $("#salesman-id").val(null).trigger("change");
        var code=$(this).attr('data-code');
        var name=$(this).attr('data-name');
        $('#emp-code').val(code);
         $('#emp-name').val(name);
         $('#salesmanLinkName').text(name);
    });

    $(document).on('click','#closeModalId',function(){
        location.reload();
    });
</script>

<script type="text/javascript">
    $(document).on('input','#salesmanSelectName',function(){
        $("#salesSubmit").attr("disabled", false);
        $("#errorMsg").text('');

        var empName=$('#salesmanSelectName').val();
        var empId = $('#salesmanList').find('option[value="'+empName+'"]').attr('id');
        // alert(empName+' '+empId);
        $('#empSelectedId').val(empId);
        
        if (typeof empId === "undefined") {
            $("#errorMsg").text('Please select correct employee.');
            $("#salesSubmit").attr("disabled", true);
        }
        
        $.ajax({
            type: "POST",
            url:"<?php echo base_url(); ?>index.php/admin/EmployeeController/checkSalesmanLinking",
            data:{empId:empId},
            success: function (data) {
                if(data.trim()=="Salesman already linked"){
                  $("#errorMsg").text(data);
                  // $("#salesSubmit").text(data);
                  $("#salesSubmit").attr("disabled", true);
                    // alert(data);
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