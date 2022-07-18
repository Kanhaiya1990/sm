<?php $this->load->view('/layouts/commanHeader'); ?>

        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                     Bill Details
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Bill Details Master
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Bill No</th>
                                            <th>Product</th>
                                            <th>MRP</th>
                                            <th>Bill Qty</th>
                                            <th>Selling Rate</th>
                                            <th>NetAmt</th>
                                            <th>ReturnQty</th>
                                            <th>ReturnAmt</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                             <th>Sr.No</th>
                                            <th>Bill No</th>
                                            <th>Product</th>
                                            <th>MRP</th>
                                            <th>Bill Qty</th>
                                            <th>Selling Rate</th>
                                            <th>NetAmt</th>
                                            <th>ReturnQty</th>
                                            <th>ReturnAmt</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                              $no=0;
                                              foreach ($billDetails as $data) 
                                                {
                                                  $id=$data['id'];
                                                 $no++; 
                                                 $bill=$this->DeliverySlipModel->load('bills',$data['billId']);
                                                 $billNo=$bill[0]['billNo'];
                                          ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $billNo; ?></td>
                                        <td><?php echo $data['productName']; ?></td>
                                        <td><?php echo $data['mrp']; ?></td>
                                        <td><?php echo $data['qty']; ?></td>
                                        <td><?php echo $data['sellingRate']; ?></td>
                                        <td><?php echo $data['netAmount']; ?></td>
                                        <td><?php echo $data['returnedQty']; ?></td>
                                        <td><?php echo $data['returnAmt']; ?></td>
                                   </tr>  
                                     <?php
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
