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
                                <?php 
                              if(isset($emprole))
                                {
                                  echo 'Update';
                                }
                              else
                                {
                                  echo 'Add';
                                }
                              ?> Employee Master
                            </h2>
                        </div>
                        <form method="post" role="form" enctype="multipart/form-data" 
                        action="<?php
                            if(isset($emprole))
                            {
                              echo site_url('admin/EmployeeRelationController/update');
                            }
                            else
                            {
                            echo site_url('admin/EmployeeRelationController/insert');
                            }
                        ?>"> 
                        <div class="body">
                            <div class="demo-masked-input">
                                <div class="row clearfix">
                                      <input type="hidden" name="id" value="<?php
                                    if(isset($emprole))
                                      {
                                        echo $emprole[0]['id'];
                                      }
                                    ?>">
                                    <div class="col-md-4">
                                        <b>Employee Name</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <select class="form-control show-tick" name="employeeId">
                                                <?php if(isset($emprole)){ ?>
                                                    <option><?php echo $emprole[0]['eName'] ?> </option>
                                                <?php } ?>
                                                    <option>-----Select Employee -----</option>
                                                    <?php foreach ($employee as $req_item): ?>
                                                    <option value="<?php echo $req_item['id'] ?>"><?php echo $req_item['name'] ?></option>
                                                    <?php endforeach ?> 
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <b>Role </b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">child_care</i>
                                            </span>
                                            <div class="form-line">
                                                
                                                <select class="form-control show-tick" name="roleId[]" multiple="multiple">
                                                    <?php if(isset($emprole)){ ?>
                                                    <option value="<?php echo $emprole[0]['rId'] ?>"><?php echo $emprole[0]['rName'] ?></option>
                                                    <?php } ?>
                                                    <option>-----Select Role -----</option>
                                                    <?php foreach ($role as $req_item): ?>
                                                    <option value="<?php echo $req_item['id'] ?>"><?php echo $req_item['name'] ?></option>
                                                    <?php endforeach ?> 
                                                </select>
                                                                                   
                                            </div>
                                        </div>
                                    </div>                      
                                    <div class="col-md-12">
                                        <div class="row clearfix">
                                            <div class="col-md-4">
                                                <button type="submite" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name">
                                                     <?php
                                                        if(isset($emprole))
                                                         { 
                                                          echo "Update";
                                                          }
                                                        else
                                                          {
                                                            echo "Save";
                                                          }
                                                        ?> 
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
                                    </div>                                 
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
<?php $this->load->view('/layouts/footer'); ?>