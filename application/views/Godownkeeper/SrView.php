<?php $this->load->view('/layouts/commanHeader'); ?>

        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                    Sales Returns

                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2> Sales Returns</h2><br /><br />
                            <h2 style="color: red;">
                                Retailer Name:  <?php foreach ($billsdetails as $data) 
                                                {
                                                    echo $data['name'];
                                                }?> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
                                Bill No     :  <?php foreach ($billsdetails as $data) 
                                                {
                                                    echo $data['billNo'];
                                                }?> &nbsp &nbsp&nbsp &nbsp &nbsp &nbsp
                                Bill Date     :  <?php foreach ($billsdetails as $data) 
                                                {
                                                    echo $data['Date'];
                                                }?>
                            </h2>
                        </div>
                            
                        <div class="body">
                            <div class="table-responsive">
                                <?php
                                if(!empty($msg)){?>
                                    <div class="alert alert-danger">
                                <?php
                                    echo '<p class="statusMsg" >'.$msg.'</p>';?>
                                </div>
                                    <?php
                                    //echo 'validation_errors()';
                                }
                            ?>
                                 <form method="post" role="form" action="<?php echo site_url('SrController/update');?>"> 
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>ProductName</th>
                                            <th>MRP</th>
                                            <th>Qty</th>
                                            <th>Net Amount</th>
                                            <th>Old SR</th>
                                            <th>SR Qty</th>
                                            <th>returnAmt</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                         <tr>
                                            <th>Sr.No</th>
                                            <th>ProductName</th>
                                            <th>MRP</th>
                                            <th>Qty</th>
                                            <th>Net Amount</th>
                                            <th>Old SR</th>
                                            <th>SR Qty</th>
                                            <th>returnAmt</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                          $no=0;
                                          foreach ($billsdetails as $data) 
                                            {
                                             $no++; 
                                          ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $data['productName']; ?></td>
                                        <td>
                                            <?php echo $data['mrp']; ?> 
                                            <input type="hidden" name="mrp" value="<?php echo $data['mrp']; ?>" readonly>
                                        </td>
                                        <td>
                                            <?php echo $data['qty']; ?>
                                              <input type="hidden" name="qty" value="<?php echo $data['qty']; ?>" readonly> 
                                        </td> 
                                        <td>
                                            <?php echo $data['netAmount']; ?>
                                            <input type="hidden" name="netAmount" value="<?php echo $data['netAmount']; ?>" readonly>      
                                        </td>
                                         <td>
                                             <?php echo $data['returnedQty']; ?>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="returnedQty" value="<?php echo $data['returnedQty']; ?>">
                                            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                                        </td>
                                        <td>
                                            <?php echo $data['returnAmt']; ?>               
                                        </td>
                                   </tr>  
                                     <?php
                                        }
                                      ?>   
                                    </tbody>
                                </table>
                                 <div class="col-md-12">
                                        <center>
                                            <div class="row clearfix">
                                                <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                                    <button type="submite" class="btn btn-primary m-t-15 waves-effect">
                                                        <i class="material-icons">save</i> 
                                                        <span class="icon-name">
                                                        Save
                                                        </span>
                                                    </button>
                                                    <a href="<?php echo site_url('SrController/');?>">
                                                        <button type="button" class="btn btn-primary m-t-15 waves-effect">
                                                            <i class="material-icons">cancel</i> 
                                                            <span class="icon-name"> Cancel</span>
                                                        </button>
                                                    </a>   
                                                </div>
                                            </div>
                                        </center>
                                    </div>  
                                <?php form_close();?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Examples -->  
        </div>
    </section>
<?php $this->load->view('/layouts/footerDataTable'); ?>
