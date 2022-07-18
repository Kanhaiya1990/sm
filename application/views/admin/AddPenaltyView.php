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
                              if(isset($penalty))
                                {
                                  echo 'Update';
                                }
                              else
                                {
                                  echo 'Add';
                                }
                              ?> Cheque Penalty 
                            </h2>
                        </div>
                        <form method="post" role="form" action="<?php
                            if(isset($penalty))
                            {
                              echo site_url('admin/PenaltyController/update');
                            }
                            else
                            {
                            echo site_url('admin/PenaltyController/insert');
                            }
                        ?>"> 
                        <div class="body">
                            <div class="demo-masked-input">
                                <div class="row clearfix">
                                     <input type="hidden" name="id" value="<?php
                                    if(isset($penalty))
                                      {
                                        echo $penalty[0]['id'];
                                      }
                                    ?>">
                               
                                    <div class="col-md-6 cust-tbl">
                                        <b>Penalty Reason</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" name="name" class="form-control date" placeholder="Enter penalty reason" value="<?php if(isset($penalty))
                                                    {
                                                      echo $penalty[0]['name']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="col-md-6 cust-tbl">
                                        <b>Amount</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">money</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" name="amount" class="form-control date" placeholder="Enter penalty Amount" value="<?php if(isset($penalty))
                                                    {
                                                      echo $penalty[0]['amount']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>  
                                    </div>  
                                       
                                     <div class="col-md-12">
                                        <div class="row clearfix">
                                            <div class="col-md-4">
                                                <button type="submite" class="btn btnStyle m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name">
                                                     <?php
                                                        if(isset($penalty))
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
                                                <a href="<?php echo site_url('admin/PenaltyController/');?>">
                                                    <button type="button" class="btn btn-danger m-t-15 waves-effect">
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
                        <?php form_close();?>
                    </div>
                </div>
            </div>
            <!-- #END# Masked Input -->
        </div>
    </section>
    
<?php $this->load->view('/layouts/footerDataTable'); ?>