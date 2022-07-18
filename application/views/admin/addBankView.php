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
                              if(isset($bank))
                                {
                                  echo 'Update';
                                }
                              else
                                {
                                  echo 'Add';
                                }
                              ?>  Bank Master
                            </h2>
                        </div>
                        <form method="post" role="form" action="<?php
                            if(isset($bank))
                            {
                              echo site_url('admin/BankController/update');
                            }
                            else
                            {
                            echo site_url('admin/BankController/insert');
                            }
                        ?>"> 
                        <div class="body">
                            <div class="demo-masked-input">
                                <div class="row clearfix">
                                     <input type="hidden" name="id" value="<?php
                                    if(isset($bank))
                                      {
                                        echo $bank[0]['id'];
                                      }
                                    ?>">
                                  <div class="col-md-6">
                                        <b>Bank Name</b>
                                        <div class="input-group">
                                            
                                            <div class="form-line">
                                                <input type="text" name="name" list="nm" autocomplete="off" class="form-control date" placeholder="Enter your bank name" value="<?php if(isset($bank))
                                                    {
                                                      echo $bank[0]['name']; 
                                                    }
                                                    ?>">
                                                     <datalist id="nm">
                                                            <?php 
                                                                foreach($bank as $data){
                                                                $name=$data['name'];
                                                            ?> 
                                                            <option value="<?php echo $name;?>"/>
                                                            <?php 
                                                                }
                                                            ?> 
                                                </datalist>
                                            </div>
                                        </div>
                                    </div>  
                                    
                                  
                                     <div class="col-md-12">
                                        <div class="row clearfix">
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name">
                                                     <?php
                                                        if(isset($bank))
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
                                                <a href="<?php echo site_url('admin/BankController/');?>">
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
                        </form>
                    </div>
                </div>
            </div>
            <!-- #END# Masked Input -->
        </div>
    </section>
    
<?php $this->load->view('/layouts/footer'); ?>