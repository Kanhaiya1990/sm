<?php 
$this->load->view('/layouts/commanHeader');

 ?>


  <?php $gstStateCode = $customerData[0]['gstStateCode']; ?>
  <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
  <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <div class="table-responsive">

                              <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                <thead>
                                    <tr style="background-color:white;">
                                      <td colspan="9" align="center"><h4>Recent Plans Transaction</h4></td>
                                      <td><a href="<?php echo $redirectUrl; ?>" class="btn btn-primary m-t-10" target="_blank">Pay Now</a></tr>
                                    <tr>
                                        <th>S. No.</th>
                                        <th>Date</th>
                                        <th>Order Id</th>
                                        <th>Package Name</th>
                                        <th>Duration</th>
                                        <th>Amount</th>
                                        <th>Transaction Id</th>
                                        <th>Order Status</th>
                                        <th>Payment Status</th>
                                        <th>Invoice</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                  <?php
                                    $no=0;
                                      foreach ($distTransactionDetails as $value) {
                                        $no++; 
                                  ?>
                                    <tr>
                                      <td><?php echo $no; ?></td>
                                      <td><?php
                                        $recharge_date = date('d-M-Y',strtotime($value['rechargeDate']));
                                        echo $recharge_date; 
                                      ?></td>
                                      <td><?php echo $value['orderId']; ?></td>
                                      <td><?php echo $value['packageName']; ?></td>
                                      <td><?php echo $value['duration']; ?> Month</td>
                                      <td><?php echo $value['netAmount']; ?></td>
                                      <td><?php echo $value['transactionId']; ?></td>
                                      <td><?php echo $value['orderStatus']; ?></td>
                                      <td><?php echo $value['transactionStatus']; ?></td>
                                      
                                <?php if($value['transactionStatus'] == "SUCCESS" && $value['orderStatus'] == "PAID"){
                                  $fileData="";
                                      $udata=$this->CompanyModel->loadDataById('invoices',$value['id']);
                                      if (!empty($udata)) {
                                        foreach ($udata as $row) {
                                          $fileData = "http://localhost/Smart_New_Integration/superadmin/assets/uploads/pdf/".$row['filepath'];
                                        }
                                      } 
                                    ?>
                                    <td>
                                        <a href="<?php echo $fileData;?>" title="Download File" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"  style="font-size:30px; color:red"></i></a>
                                    </td>
                                <?php }else if(($value['transactionStatus'] != "SUCCESS" || $value['transactionStatus'] != "PENDING") && ($value['orderStatus'] == "ACTIVE")){ ?>
                                    <td>
                                      <a href="<?php echo base_url();?>PackageController/checkNow/?cid=<?php echo $value['cid'];?>&amt=<?php echo $value['netAmount'];?>">Retry Payment</a>
                                    </td>
                                <?php }else{ ?>
                                    <td>
                                        <a href="<?php echo 'http://localhost/Smart_New_Integration/superadmin/index.php/PackageController/paymentStatus/'.$value['orderId'].'/'.$value['orderId']; ?>" target="_blank" title="<?php echo $value['transactionStatus']; ?>">Check Payment Status</a> 
                                    </td>
                                <?php } ?>

                                    </tr>
                                    <?php } ?>
                                </tbody>
                              </table>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</section>