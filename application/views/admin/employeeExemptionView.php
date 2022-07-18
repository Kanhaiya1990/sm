<?php $this->load->view('/layouts/commanHeader'); ?>

<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
<section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Employees Exempt from Manager Hisab Review</h2>
                             <div align="right">
                              <p align="right">
                                  <button align="right" type="button" id="insert-ins" class="btn btn-xs btn-primary m-t-15 waves-effect"> <i class="material-icons">save</i><span class="icon-name">Clear All</span></button>
                                  <button align="right" type="button" id="cancel-ins" class="btn btn-xs btn-primary m-t-15 waves-effect"> <i class="material-icons">save</i><span class="icon-name">Cancel All</span></button>
                              </p>
                            </div>
                        <div class="body">
                            <div class="table-responsive">
                              <p id="res"></p>
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th><input class="checkall" type="checkbox" name="selValue" id="basic_checkbox"/><label for="basic_checkbox"></label>Select All</th>
                                            <!-- <th><input class="cancelall" type="checkbox" name="cancelValue" id="cancelbasic_checkbox"/><label for="cancelbasic_checkbox"></label>Cancel All</th> -->
                                            
                                            <th>S No.</th>
                                            <th>Employee Code</th>
                                            <th>Employee Name</th>
                                            <th>Mobile</th>
                                            <th>Designation</th>
                                            <th>Status</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                  <?php 
                                      if(!empty($employee)){
                                        $no=0;
                                        foreach ($employee as $data) {
                                          $id=$data['id'];
                                          $status=$data['empExemption'];

                                            $no++;
                                  ?>
                                        <tr>
                                            <td>
                                                <input class="checkhour" type="checkbox" name="selValue" value="<?php echo $id; ?>" id="basic_checkbox_<?php echo $id; ?>" />
                                                <label for="basic_checkbox_<?php echo $id; ?>"></label>
                                            </td>
                                             <!-- <td>
                                                <input class="cancelOne" type="checkbox" name="cancelValue" value="<?php echo $id; ?>" id="cancelbasic_checkbox_<?php echo $id; ?>" />
                                                <label for="cancelbasic_checkbox_<?php echo $id; ?>"></label>
                                            </td> -->
                                            <td><?php echo $no; ?></td>
                                            <td><?php echo $data['code']; ?></td>
                                            <td><?php echo $data['name']; ?></td>
                                            <td><?php echo $data['mobile']; ?></td>
                                            <td><?php echo $data['designation']; ?></td>
                                            <td>
                                            <?php
                                                  if($status==0){
                                                    echo "Non Exempt";
                                                  }else{
                                                    echo "Exempted";
                                                  }
                                            ?>
                                            </td>

                                           
                                            <!-- <td>
                                                <a href='<?php echo site_url("admin/EmployeeController/updateEmployeeExcemptionStatus/".$id."/".$status);?>'>
                                                <i class="btn btn-xs btn-primary material-icons">edit</i>
                                            </a>
                                            </td> -->
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
<?php $this->load->view('/layouts/footerDataTable'); ?>


<script type="text/javascript">
    var clicked = false;
    $(".checkall").on("click", function() {
      $(".checkhour").prop("checked", !clicked);
      clicked = !clicked;
      this.innerHTML = clicked ? 'Deselect' : 'Select';
    });
</script>

  <script type="text/javascript">
    jQuery("#insert-ins").on("click",function(){
        var selValue = [];
        $.each($("input[name='selValue']:checked"), function(){
                selValue.push($(this).val());
        });

        if(selValue.length>0 ){
            var msj= confirm('Are you sure wants to submit.');
            if (msj == false) { 
              return false;
            } else {
                $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('admin/EmployeeController/updateSelectedEmployeeExcemptionStatus');?>",
                    data:{selValue:selValue},
                    success: function (data) {
                        $("input[type=checkbox]").each(function(){
                            $(this).attr('checked', false);
                        });      
                        window.location.href="<?php echo base_url();?>index.php/admin/EmployeeController/employeeeException";
                    },
                    beforeSend: function(){
                        $('.comman-ajax-loader').css("visibility", "visible");
                    },
                    complete: function(){
                        $('.comman-ajax-loader').css("visibility", "hidden");
                    },
                    error: function(jqXHR, exception) {
                        toastr.error('Something Went Wrong, Please Try Again...!', 'Alert!');
                        // alert("Something Went Wrong, Please Try Again...!");
                    }     
                });
            }
        }else{
            toastr.info('Please select employees.', 'Alert!');
            // alert('Please select employees.');
        }
});
    
</script>

<script type="text/javascript">
    var clicked = false;
    $(".cancelall").on("click", function() {
      $(".cancelOne").prop("checked", !clicked);
      clicked = !clicked;
      this.innerHTML = clicked ? 'Deselect' : 'Select';
    });
</script>

  <script type="text/javascript">
    jQuery("#cancel-ins").on("click",function(){
        var selValue = [];
        $.each($("input[name='selValue']:checked"), function(){
                selValue.push($(this).val());
        });

        if(selValue.length>0 ){
            var msj= confirm('Are you sure wants to submit.');
            if (msj == false) { 
              return false;
            } else {
                $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('admin/EmployeeController/cancelSelectedEmployeeExcemptionStatus');?>",
                    data:{selValue:selValue},
                    success: function (data) {
                        $("input[type=checkbox]").each(function(){
                            $(this).attr('checked', false);
                        });      
                        window.location.href="<?php echo base_url();?>index.php/admin/EmployeeController/employeeeException";
                    },
                    beforeSend: function(){
                        $('.comman-ajax-loader').css("visibility", "visible");
                    },
                    complete: function(){
                        $('.comman-ajax-loader').css("visibility", "hidden");
                    },
                    error: function(jqXHR, exception) {
                        toastr.error('Something Went Wrong, Please Try Again...!', 'Error!');
                        alert("");
                    }     
                });
            }
        }else{
            toastr.info('Please select employees.', 'Alert!');
            // alert('Please select employees.');
        }
});
    
</script>