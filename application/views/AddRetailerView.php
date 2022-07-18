<?php $this->load->view('/layouts/commanHeader'); ?>
    <!-- <section class="content"> -->
  <h1 style="display: none;">Welcome</h1><br/><br><br>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Add Retailer</h2>
            </div>
            <!-- Masked Input -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div id="frm_AddItem" class="card">
                        <div class="header">
                            <h2>
                               <?php 
                              if(isset($retailer))
                                {
                                  echo 'Update';
                                }
                              else
                                {
                                  echo 'Add';
                                }
                              ?>  Retailer Master
                            </h2>
                        </div>
                        <?php if($this->session->flashdata('msg')): ?>
                          <p align="center" class="bg-primary"><?php echo $this->session->flashdata('msg'); ?></p>
                        <?php endif; ?>
                        <form method="post" role="form" action="<?php
                            if(isset($retailer))
                            {
                              echo site_url('RetailerController/update');
                            }
                            else
                            {
                            echo site_url('RetailerController/insert');
                            }
                        ?>"> 

                        <div class="body">
                            <div class="demo-masked-input">
                                <div class="row clearfix">
                                     <input type="hidden" name="id" value="<?php
                                    if(isset($retailer))
                                      {
                                        echo $retailer[0]['id'];
                                      }
                                    ?>">
    
                                  <div class="col-md-4">
                                        <b>Retailer Name</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" autocomplete="off" name="rtName" list="ret" class="form-control date" placeholder="Enter retailer name" value="<?php if(isset($retailer))
                                                    {
                                                      echo $retailer[0]['name']; 
                                                    }
                                                    ?>">
                                                    <datalist id="ret">
                                                      <?php
                                                          foreach($retNames as $data){
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

                                    <div class="col-md-4">
                                        <b>Route</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" autocomplete="off" name="route" list="routes" class="form-control date" placeholder="Enter route">
                                                     <datalist id="routes">
                                                      <?php
                                                          foreach($route as $data){
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
                                     <div class="col-md-4">
                                      <b>Salesman</b>
                                      <div class="input-group">
                                        <span class="input-group-addon">
                                         <i class="material-icons">check_circle</i>
                                       </span>

                                       <!-- <select id="salesman" name="salesman" class="form-control">
                                        <option>---Select Items---</option>
                                        <?php foreach ($emp as $req_item): ?>
                                          <option value="<?php echo $req_item['id'] ?>"><?php echo $req_item['name'] ?></option>
                                        <?php endforeach ?>    
                                      </select> --> 
                                       <div class="form-line">
                            <input type="text" id="salesman" autocomplete="off" list="emp" name="salesman" class="form-control" placeholder="salesman Name">   
                                        <datalist id="emp">
                                            <?php
                                                foreach($emp as $data){
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
                                            <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                                <button type="submit" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name">
                                                     <?php
                                                        if(isset($product))
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
                                                <!-- <a href="<?php echo site_url('DeliverySlipController/');?>"> -->
                                                    <button onclick="parent.$.colorbox.close(); return false;" type="button" class="btn btn-primary m-t-15 waves-effect">
                                                        <i class="material-icons">cancel</i> 
                                                        <span class="icon-name"> Cancel</span>
                                                    </button>
                                                <!-- </a>    -->
                                            </div>

                                        </div>
                                    </div>                            
                                </div>
                            </div>
                        </div>
                      </form>
                        <!-- <?php form_close();?> -->
                    </div>
                </div>
            </div>
            <!-- #END# Masked Input -->
        </div>
    </section>
    <?php $this->load->view('/layouts/footerDataTable'); ?>
    <script type="text/javascript">
      $(document).ready(function(){
        $(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
    });
</script>
