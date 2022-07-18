<?php $this->load->view('/layouts/commanHeader'); ?>

         <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
        <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                    Tagged Bill Master
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Tagged Bill
                            </h2>
                            <h2>
                                <p align="right">

                                  <a class="iframe" href="<?php echo site_url('NonAllocationBillsController/Add');?>">
                                    <button type="submit" class="btn bg-primary margin"><i class="material-icons">add</i>  Add  </button></a> 
                                </p> 
                            </h2>
                        </div>
                        
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                         <tr>
                                            <td>Sr.No</td>
                                            <th>Bill No</th>
                                            <th>Date</th>
                                            <th>Retailer Name</th>                                            
                                            <th>Person</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                         <tr>
                                            <td>Sr.No</td>
                                            <th>Bill No</th>
                                            <th>Date</th>
                                            <th>Retailer Name</th>                                            
                                            <th>Person</th>
                                            <th>Amount</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                         <?php
                                          $no=0;
                                          foreach ($bills as $data) 
                                            {
                                                $no++; 
                                            ?>   
                                            <tr>
                                                <td><?php echo $no; ?></td> 
                                                <td>
                                                    <a  class="iframe" href="<?php echo base_url().'index.php/NonAllocationBillsController/loadTaggedBill/'.$data['id']; ?>">
                                                      <?php echo rtrim($data['billNo']); ?>
                                                    </a>
                                                </td>
                                               
                                                <td><?php echo $data['date']; ?></td>
                                                <td><?php echo $data['retailerName']; ?></td>
                                                <td><?php echo $data['empname'];?></td>
                                                <td><?php echo $data['pendingAmt']; ?></td>
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
