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
                              if(isset($role))
                                {
                                  echo 'Update';
                                }
                              else
                                {
                                  echo 'Add';
                                }
                              ?>  Route Master
                            </h2>
                        </div>
                        <form method="post" role="form" action="<?php
                            if(isset($route))
                            {
                              echo site_url('admin/RouteController/update');
                            }
                            else
                            {
                            echo site_url('admin/RouteController/insert');
                            }
                        ?>"> 
                        <div class="body">
                            <div class="demo-masked-input">
                                <div class="row clearfix">
                                     <input type="hidden" name="id" value="<?php
                                    if(isset($route))
                                      {
                                        echo $route[0]['id'];
                                      }
                                    ?>">
                                    <div class="col-md-6">
                                        <b>Route Name</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" name="name" class="form-control date" placeholder="Enter your route name" id="nm" value="<?php if(isset($route))
                                                    {
                                                      echo $route[0]['name']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                       
                                    </div> 
                                    <div class="col-md-6">
                                        <b>Code</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">money</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" name="code" class="form-control date" placeholder="Enter your code" value="<?php if(isset($route))
                                                    {
                                                      echo $route[0]['code']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>  
                                    </div>  
                                     <div class="col-md-12">
                                        <div class="row clearfix">
                                            <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                                <button type="submite" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name">
                                                     <?php
                                                        if(isset($route))
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
                                                <a href="<?php echo site_url('admin/RouteController/');?>">
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
   <script type="text/javascript">
            $(document).ready(function(){
                $('#nm').autocomplete({
                    source: "<?php echo base_url();?>admin/RouteController/search/?"
                });
            });
        </script>

    <div class="col-md-4">
        <?php
        $input_data = array(
        'name'  => 'name',
        'id' => 'nm',
        'class' => 'form-control'
        );
        echo form_input($input_data)?>
    </div>
</script>

<?php $this->load->view('/layouts/footerDataTable'); ?>