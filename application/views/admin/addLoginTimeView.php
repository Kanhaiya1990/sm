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
                               Login Time Master
                            </h2>
                        </div>
                        <form method="post" role="form" action="<?php echo site_url('admin/SettingsController/updatedLoginTimeLimit'); ?>"> 
                        <div class="body">
                            <div class="demo-masked-input">
                                <div class="row clearfix">
                                     <input type="hidden" name="id" value="<?php
                                    if(isset($loginData))
                                      {
                                        echo $loginData[0]['id'];
                                      }
                                    ?>">
                                  <div class="col-md-4">
                                        <b>From Time</b>
                                        <div class="input-group">
                                            <div class="form-line">
                                                <input type="time" name="fromTime"  autocomplete="off" class="form-control date" placeholder="Enter from time" value="<?php if(isset($loginData))
                                                    {
                                                      echo $loginData[0]['fromTime']; 
                                                    }
                                                    ?>">
                                                     
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <b>From Time</b>
                                        <div class="input-group">
                                            <div class="form-line">
                                                <input type="time" name="toTime"  autocomplete="off" class="form-control date" placeholder="Enter from time" value="<?php if(isset($loginData))
                                                    {
                                                      echo $loginData[0]['toTime']; 
                                                    }
                                                    ?>">
                                                     
                                            </div>
                                        </div>
                                    </div> 
                                    
                                    <div class="col-md-4">
                                                <button type="submite" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name">
                                                     Save
                                                    </span>
                                                </button>
                                            </div> 
                                  
                                   <!--   <div class="col-md-12">
                                        <div class="row clearfix">
                                            
                                        </div>
                                    </div>  -->                           
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