<?php $this->load->view('/layouts/commanHeader'); ?>

<!-- <section class="content"> -->
    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                  Retailerwise Outstanding
              </h2>
          </div>
          <!-- Basic Examples -->
          <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                         Retailerwise Outstanding
                     </h2>
                 </div>
                 <div class="body">
                    <div class="table-responsive">
                        <div class="col-md-12">
                           
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                        <thead>
                        <tr>
                            <th>Sr.No</th>
                                <th>Retailer Name</th>
                                <th>Beat</th>
                                <th>Salesman</th>
                                <th>No of Bills</th>
                                <th>Balance Due</th>

                        </tr>
                        </thead>
                        <tfoot>
                             <tr>
                                <th>Sr.No</th>
                                <th>Retailer Name</th>
                                <th>Beat</th>
                                <th>Salesman</th>
                                <th>No of Bills</th>
                                <th>Balance Due</th>

                            </tr>
                        </tfoot>
                        <tbody>
                            <!-- <tr> -->
                            <?php
                                        $no=0;
                                        foreach ($bills as $data) 
                                          {
                                           $no++; 

                                           $retailerName=$this->DeliverySlipModel->getRetailerName($data['retailerCode']);
                                           $retailerName=$retailerName[0]['name'];
                                    ?>
                                        <tr>

                                            <td><?php echo $no; ?></td>
                                            <td>
                                              <a href="<?php echo base_url().'index.php/DeliverySlipController/loadRetailerBills/'.$retailerName; ?>">
                                                <?php echo $retailerName; ?>
                                            </a>

                                               <!-- <?php echo $retailerName; ?> -->
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td><?php echo $data['billCount'];?></td>
                                            <td><?php echo $data['pendingAmt'];?></td>
                                          
                                        </tr>
                                    <?php
                                        }
                                      ?> 
                                  <!-- </tr> -->
                        </tbody>
                    </table>
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