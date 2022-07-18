<?php $this->load->view('/layouts/commanHeader'); ?>

<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
<section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            
            <!-- Masked Input -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                             Edit Roles to Employee
                            </h2>
                        </div>
                        <form method="post" role="form" enctype="multipart/form-data" 
                        action="<?php echo site_url('admin/EmployeeRelationController/update'); ?>"> 
                        <div class="body">
                            <div class="demo-masked-input">
                                <div class="row clearfix">
                                      <input type="hidden" name="id" value="<?php if(isset($emprole)){  echo $emprole[0]['id'];} ?>">

                                      <input type="hidden" name="employeeId" value="<?php if(isset($emprole)){  echo $emprole[0]['employeeId'];} ?>">


                                        <h3 style="text-align: center;"><b>Employee Details</b></h3>
                                        <div class="col-md-12">
                                            <div class="col-md-4"><b>Name : <?php echo $emprole[0]['eName'];?></b><br /><br /></div>
                                            <div class="col-md-4"><b>Email : <?php echo $emprole[0]['email'];?></b><br /><br /></div>
                                            <div class="col-md-4"><b>Roles :</b> 
                                                  &nbsp;  <span class="btn label label-success">  <?php  echo $emprole[0]['rName'];?></span>
                                            </div>
                                        </div>
                                      
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <h3>Update Roles </h3>
                                <p>(You  assign  these new roles to employee - Please check the box for add role )</p>
                                <hr>
                                <div class="demo-checkbox">
                                       <?php foreach ($role as $req_item){ ?>
                                        <input type="checkbox" id="remember_me<?php echo $req_item['id'] ?>" class="filled-in" name="roleId[]" value="<?php echo $req_item['id']?>">
                                        <label for="remember_me<?php echo $req_item['id'] ?>"><?php echo $req_item['name'] ?></label><br>
                                        <?php
                                          }
                                        ?> 
                                        <button type="submite" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name"><?php if(isset($emprole)){ echo "Update"; }else{ echo "Save"; }?> 
                                                    </span>
                                                </button>
                                               <a href="<?php echo site_url('admin/EmployeeRelationController/');?>">
                                                    <button type="button" class="btn btn-primary m-t-15 waves-effect">
                                                        <i class="material-icons">cancel</i> 
                                                        <span class="icon-name"> Cancel</span>
                                                    </button>
                                                </a>    
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h3>Remove Roles </h3>
                                 <p>(Remove assigned roles to employee - Please check the box to remove role )</p>
                                <hr>
                                <div class="demo-checkbox">
                                       <?php foreach ($emprole as $req_item){ ?>
                                        <input type="hidden" class="filled-in" name="empId" value="<?php echo $req_item['employeeId']?>">
                                        <input type="checkbox" id="delete_me<?php echo $req_item['roleId'] ?>" class="filled-in" name="rmroleId[]" value="<?php echo $req_item['roleId']?>">
                                        <label for="delete_me<?php echo $req_item['roleId'] ?>"><?php echo $req_item['rName'] ?></label><br>
                                        <?php
                                          }
                                        ?>   
                                        <a href="javascript:void();" id="remove_btn_id" class="btn btn-danger m-t-15 waves-effect">
                                                <i class="material-icons">delete</i> 
                                                <span class="icon-name"> Remove</span>
                                        </a> 
                                </div>
                            </div>

                        </div>
                                    </div>             
                                  <!--   <div class="col-md-12">
                                        <div class="row clearfix">
                                            <div class="col-md-4">
                                                <button type="submite" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name"><?php if(isset($emprole)){ echo "Update"; }else{ echo "Save"; }?> 
                                                    </span>
                                                </button>
                                               <a href="<?php echo site_url('admin/EmployeeRelationController/');?>">
                                                    <button type="button" class="btn btn-primary m-t-15 waves-effect">
                                                        <i class="material-icons">cancel</i> 
                                                        <span class="icon-name"> Cancel</span>
                                                    </button>
                                                </a> 
                                            </div>

                                        </div>
                                    </div>         -->                         
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
            <!-- #END# Masked Input -->
        </div>
    </section>
<?php $this->load->view('/layouts/footerDataTable'); ?>

<script type="text/javascript">
    $(document).on('click','#remove_btn_id',function(){
        var empId = $("input[name='empId']").val();
        var roleId = $("input[name='rmroleId[]']:checked").val();
         $.ajax({
            url : "<?php echo site_url('admin/EmployeeRelationController/removeRole');?>",
            method : "POST",
            data : {id: '2',empId:empId,roleId:roleId},
            success: function(data){
              alert('Role Deleted');
              parent.history.back();
            }
        });
    });
 </script>