<?php $this->load->view('/layouts/commanHeader'); ?>

    <h1 style="display: none;">Welcome</h1><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                    Cash Discount
                </h2>
                
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Cash Discount
                            </h2>
                        </div>
                            
                        <div class="body">
                        <div class="table-responsive">
                        <div class="body">
                            <div class="demo-masked-input">
                                <form method="post" role="form" action="<?php echo site_url('NonAllocationBillsController/CashDiscountupdate');?>">
                                <div class="row clearfix">
                                     <input type="hidden" name="id" value="<?php
                                    if(isset($bills))
                                      {
                                        echo $bills[0]['id'];
                                      }
                                    ?>">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">                                       
                                        <p>
                                          <b>Cash Discount Amount </b>
                                        </p>
                                        <div class="form-line">
                                            <input type="text" name="cashAmt" class="form-control">           
                                        </div>           
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row clearfix">
                                        <center>                                               
                                                <button type="submite" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name">
                                                    Save
                                                    </span>
                                                </button>
                                                <a class="iframe" href="<?php echo base_url().'index.php/NonAllocationBillsController/loadTaggedBill/'.$bills[0]['id'];?>">
                                                    <button type="button" class="btn btn-primary m-t-15 waves-effect">
                                                        <i class="material-icons">cancel</i> 
                                                        <span class="icon-name">
                                                        cancel
                                                        </span>
                                                    </button>
                                                </a>      
                                        </center>

                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Examples -->  
        </div>
    </section>
    <?php $this->load->view('/layouts/footerDataTable'); ?>