<?php $this->load->view('/layouts/commanHeader'); ?>

        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Current Sr Bills
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Allocation ID</th>
                                            <th>Bill No.</th>
                                            <th>Retailer</th>
                                            <th>Route</th>
                                            <th>Item</th>
                                            <th>Net Amount</th>
                                            <th>SR Qty</th>
                                            <th>SR Amount</th>
                                             <th>Print</th>
                                              <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Allocation ID</th>
                                            <th>Bill No.</th>
                                            <th>Retailer</th>
                                            <th>Route</th>
                                            <th>Item</th>
                                            <th>Net Amount</th>
                                            <th>SR Qty</th>
                                            <th>SR Amount</th>
                                            <th>Print</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        
                                        <?php
                                              $no=0;
                                              if(!empty($srBills)){
                                              foreach ($srBills as $data) 
                                                {
                                                  $id=$data['id'];
                                                 $no++; 
                                          ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $data['AllocationCode']; ?></td>
                                        <td>
                                                <?php echo rtrim($data['billNo']); ?>
                                        </td>
                                        <td><?php
                                            echo $data['retailerName']; ?>
                                        </td>
                                        <td><?php echo $data['routeName']; ?></td>
                                        <td><?php echo $data['productName']; ?></td>
                                        <td><?php echo $data['netAmount']; ?></td>
                                        <td><?php echo $data['gkReturnQty']; ?></td>
                                        <td><?php echo $data['fsReturnAmt']; ?></td>
                                        <?php 
                                            $gst=$this->SrModel->getGstNo('retailer',$data['retailerName']);

                                           if(!empty($gst)){
                                            if($gst[0]['gstIn'] !=''){
                                        ?>
                                        <td><button class="btn bg-primary margin">GST</button></td>
                                        <?php
                                            }else{
                                        ?>
                                        <td></td>
                                        <?php 
                                                } 
                                            }else{
                                        ?>
                                        <td></td>
                                        <?php 
                                            }
                                        ?>
                                        <td><button class="btn bg-primary margin">OK</button></td>
                                   </tr>  
                                     <?php
                                            }
                                        }
                                         if(!empty($fsrBills)){
                                            foreach($fsrBills as $data){
                                      ?>   
                                      <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $data['AllocationCode']; ?></td>
                                        <td>
                                                <?php echo rtrim($data['billNo']); ?>
                                        </td>
                                        <td><?php
                                            echo $data['retailerName']; ?>
                                        </td>
                                        <td><?php echo $data['routeName']; ?></td>
                                        <td><?php echo 'FSR'; ?></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <?php 
                                            $gst=$this->SrModel->getGstNo('retailer',$data['retailerName']);
                                           if(!empty($gst)){
                                            if($gst[0]['gstIn'] !=''){
                                        ?>
                                        <td><button class="btn bg-primary margin">GST</button></td>
                                        <?php
                                            }else{
                                        ?>
                                        <td></td>
                                        <?php 
                                                } 
                                            }else{
                                        ?>
                                        <td></td>
                                        <?php 
                                            }
                                        ?>
                                        <td><button class="btn bg-primary margin">OK</button></td>
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