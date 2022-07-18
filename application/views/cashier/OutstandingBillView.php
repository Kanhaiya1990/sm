<?php $this->load->view('/layouts/commanHeader'); ?>

<!-- <section class="content"> -->
    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                  Outstanding Bill
              </h2>
          </div>
          <!-- Basic Examples -->
          <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                         Outstanding Bill
                     </h2>
                 </div>
                 <div class="body">
                    <div class="table-responsive">
                        <div class="col-md-12">
                           
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                        <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Bill No</th>
                            <th>Date</th>
                            <th>Retailer Name</th>
                            <th>Net Amount</th>
                            <th>SRAmt</th>
                            <th>Received Amt</th>
                            <th>Pending Amt</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tfoot>
                         <tr>
                            <th>Sr.No</th>
                            <th>Bill No</th>
                            <th>Date</th>
                            <th>Retailer Name</th>
                            <th>Net Amount</th>
                            <th>SRAmt</th>
                            <th>Received Amt</th>
                            <th>Pending Amt</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                        <tbody>
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
                                    <?php echo $data['billNo']; ?>
                                </td>

                                <?php
                                    $dt=date_create($data['date']);
                                    $date = date_format($dt,'d-m-Y');
                                ?>
                                
                                <td><?php echo $date; ?></td>
                                <td><?php echo $retailerName; ?></td>
                                <td><?php echo $data['netAmount']; ?></td>
                                <td><?php echo $data['SRAmt']; ?></td>
                                <td><?php echo $data['receivedAmt']; ?></td>
                                <td><?php echo $data['pendingAmt']; ?></td>
                                <td>
                                   <a href="<?php echo base_url().'index.php/cashier/SrController/load/'.$data['id'];  ?>">
                                    <button class="btn btn-primary btn-sm" data-type="basic"><i class="material-icons">add</i>Sales Return</button>
                                   </a> 
                                   <a href="<?php echo base_url().'index.php/cashier/CashBookController/load/'.$data['id']; ?>">
                                    <button class="btn btn-primary btn-sm" data-type="basic"><i class="material-icons">add</i>Cash Receipt</button>
                                   </a> 

                                </td>
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
</div>
<!-- #END# Basic Examples -->
</div>
</section>
<?php $this->load->view('/layouts/footerDataTable'); ?>