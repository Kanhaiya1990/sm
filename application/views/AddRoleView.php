<?php $this->load->view('/layouts/commanHeader'); ?>

    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Add Role</h2>
            </div>
            <!-- Masked Input -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               <?php 
                              if(isset($role))
                                {
                                  echo 'Update';
                                }
                              else
                                {
                                  echo 'Add';
                                }
                              ?>  Role Master
                            </h2>
                        </div>
                        <form method="post" role="form" action="<?php
                            if(isset($role))
                            {
                              echo site_url('RoleController/update');
                            }
                            else
                            {
                            echo site_url('RoleController/insert');
                            }
                        ?>"> 
                        <div class="body">
                            <div class="demo-masked-input">
                                <div class="row clearfix">
                                     <input type="hidden" name="id" value="<?php
                                    if(isset($role))
                                      {
                                        echo $role[0]['id'];
                                      }
                                    ?>">
                                    <div class="col-md-6">
                                        <b>Role Name</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">child_care</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" name="name" class="form-control date" placeholder="Enter your Role" value="<?php if(isset($role))
                                                    {
                                                      echo $role[0]['name']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div>  
                                     <div class="col-md-12"></div><br /><br />
                                     <div class="col-md-12">
                                        <div class="row clearfix">
                                            <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                                <button type="submite" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name">
                                                     <?php
                                                        if(isset($role))
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
                                                <a href="<?php echo site_url('RoleController/');?>">
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
<?php $this->load->view('/layouts/footerDataTable'); ?>
