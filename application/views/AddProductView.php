<?php $this->load->view('/layouts/commanHeader'); ?>

    <!-- <section class="content"> -->
  <h1 style="display: none;">Welcome</h1><br/><br><br>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Add Product</h2>
            </div>
            <!-- Masked Input -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div id="frm_AddItem" class="card">
                        <div class="header">
                            <h2>
                               <?php 
                              if(isset($product))
                                {
                                  echo 'Update';
                                }
                              else
                                {
                                  echo 'Add';
                                }
                              ?>  Product Master
                            </h2>
                        </div>
                        <form method="post" role="form" action="<?php
                            if(isset($product))
                            {
                              echo site_url('ProductController/update');
                            }
                            else
                            {
                            echo site_url('ProductController/insert');
                            }
                        ?>"> 
                        <div class="body">
                            <div class="demo-masked-input">
                                <div class="row clearfix">
                                     <input type="hidden" name="id" value="<?php
                                    if(isset($product))
                                      {
                                        echo $product[0]['id'];
                                      }
                                    ?>">
    
                                  <div class="col-md-3">
                                        <b>Product Name</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input autocomplete="off" type="text" name="prodName" class="form-control date" placeholder="Enter product name" value="<?php if(isset($product))
                                                    {
                                                      echo $product[0]['name']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div> 

                                     <div class="col-md-3">
                                        <b>Pcs/Box in Case</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input autocomplete="off" type="text" name="noBox" class="form-control date" placeholder="No of Pcs/Box" value="<?php if(isset($product))
                                                    {
                                                      echo $product[0]['boxQty']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="col-md-3">
                                        <b>MRP</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input autocomplete="off" type="text" name="price" class="form-control date" placeholder="Enter Price" value="<?php if(isset($product))
                                                    {
                                                      echo $product[0]['price']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-3">
                                        <b>Quantity</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input autocomplete="off" type="text" name="qty" class="form-control date" placeholder="Enter Quantity" value="<?php if(isset($product))
                                                    {
                                                      echo $product[0]['availQty']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div> -->
                                  
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
                       <!--  <?php form_close();?> -->
                    </div>
                </div>
            </div>
            <!-- #END# Masked Input -->
        </div>
    </section>
<?php $this->load->view('/layouts/footerDataTable'); ?>