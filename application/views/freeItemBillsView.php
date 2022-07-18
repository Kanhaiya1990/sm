<?php $this->load->view('/layouts/commanHeader'); ?>

        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                 Free Quantity for Bills
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered dataTable js-exportable cust-tbl" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Bill No.</th>
                                            <th>Item Code</th>
                                            <th>Item</th>
                                            <th>Qty</th>
                                            <th>Free Qty</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                       <tr>
                                            <th>S. No.</th>
                                            <th>Bill No.</th>
                                            <th>Item Code</th>
                                            <th>Item</th>
                                            <th>Qty</th>
                                            <th>Free Qty</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        
                                        <?php
                                              $no=0;
                                              if(!empty($bills)){
                                              foreach ($bills as $data) 
                                                {
                                                  $id=$data['id'];
                                                 $no++; 
                                          ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $data['billNo']; ?></td>
                                        <td><?php echo $data['productCode']; ?></td>
                                        <td><?php echo $data['productName']; ?></td>
                                        <td><?php echo $data['quantity']; ?></td>
                                        <td><?php echo $data['freeQuantity']; ?></td>
                                   </tr>  
                                     <?php
                                            }
                                        }
                                     ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Examples -->  
        </div>
    </section>
<?php $this->load->view('/layouts/footerDataTable'); ?>
