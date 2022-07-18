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

                            <table class="table table-bordered table-striped table-hover dataTable">
                                <tbody>
                                    <tr>
                                      <td colspan="6" align="center"><h4>Distributor Details</h4></td>
                                    </tr>
                                    <tr align="center">
                                      <td><b>Package Name</b></td>
                                      <td><?php echo $customerData[0]['packageName']?></td>
                                      <td><b>Valid Till</b></td>
                                      <td>
                                  <?php 

                                      if($customerData[0]['validTill'] == 0000-00-00){
                                          echo "0000-00-00";
                                      }else{
                                          echo $valid_date = date('d - M - Y',strtotime($customerData[0]['validTill']));
                                      }
                                      
                                  ?>
                                      </td>
                                  <?php
                                      $startTimeStamp = strtotime(date('Y-m-d'));
                                      $endTimeStamp = strtotime($customerData[0]['validTill']);
                                      if($startTimeStamp < $endTimeStamp){
                                  ?>
                                          <td><b>Remaining Days</b></td>
                                          <td>
                                  <?php
                                          $timeDiff = abs($startTimeStamp - $endTimeStamp);
                                          $numberDays = $timeDiff/86400;  // 86400 seconds in one day
                                          if($customerData[0]['validTill'] == 0000-00-00){
                                          echo $numberDays = "0";
                                          }else if($startTimeStamp < $endTimeStamp){
                                          echo $numberDays = intval($numberDays);
                                        }else{
                                          echo $numberDays = 0;
                                        }
                                  ?>
                                          </td>
                                  <?php }else{ ?>
                                            <td><b>Valid Expired Days</b></td>
                                            <td>
                                  <?php
                                            $timeDiff = abs($startTimeStamp - $endTimeStamp);
                                            $numberDays = $timeDiff/86400;  // 86400 seconds in one day
                                            if($customerData[0]['validTill'] == 0000-00-00){
                                                echo $numberDays = "0";
                                            }else if($startTimeStamp > $endTimeStamp){
                                                echo "-".$numberDays = intval($numberDays);
                                            }else{
                                                echo $numberDays = 0;
                                            }
                                            ?>
                                    <?php } ?>
                                      </tr>
                                  </tbody>
                                </table>
                                <br/>

                                <table class="table table-bordered table-striped table-hover dataTable">
                                  <form id="form_validation" method="POST" action="<?php echo site_url('paymentGateways/PackageController/checkout');  ?>">
                                  <tbody style="background-color:white">
                                    <?php if($custPackageLists[0]['id'] !=0 || $customerData[0]['package']==0){

                                        $orderAmount1 = $custPackageLists[0]['packageAmount'];
                                        $id = $custPackageLists[0]['id'];
                                        $gstStateCheck = $siaGstStateCode; 
                                        $gstStateCode1 = $officeGstStateCode;
                                        // $gstStateCode1 = substr($gstStateCode, 0, 2);
                                      ?>
                                    <tr>
                                      <td colspan="8" align="center"><h3>Increase Validity</h3></td>
                                    </tr>
                                    <input type="hidden" name="pid" id="pid" value="<?php echo $custPackageLists[0]['id']?>" />
                                    <tr style="font-weight: bold;" align="left">
                                      <td>Package Name</td>
                                      <td>Rate</td>
                                      <td>Duration</td>
                                      <td>Taxable Amount</td>
                                      <td>GST</td>
                                      <td>Total</td>
                                      <td></td>
                                    </tr>

                                  <tr>
                                      <td><?php echo $custPackageLists[0]['packageName']?>
                                      <input type="hidden" name="pkgName" id="pkgName" value="<?php echo $custPackageLists[0]['packageName']?>" />
                                      </td>
                                      <td><?php echo $custPackageLists[0]['packageAmount']?></td>

                                      <td>
                                        <select class="form-control show-tick" name="duration" id="duration" required="required">
                                          <option value="">-- Please select Duration--</option>

                                  <?php 
                                        $oneMonth=$packageLists[0]['duration']." Month";
                                        $threeMonth=$packageLists[0]['duration']*3 ." Month";

                                        $oneMonthDuration=$packageLists[0]['duration'];
                                        $threeMonthDuration=$packageLists[0]['duration']*3;
                                  ?>

                                          <option value="<?php echo $oneMonthDuration; ?>"><?php echo $oneMonth;?></option>
                                          <option value="<?php echo $threeMonthDuration;?>"><?php echo $threeMonth;?></option>
                                        </select>
                                      </td>

                                      <td>
                                      <input type="hidden" name="ofcGstValue" id="ofcGstValue" value="<?php echo $gstPercent; ?>" readonly />
                                      <input type="hidden" name="ofcSacValue" id="ofcSacValue" value="<?php echo $sacCode; ?>" readonly />
                                      <input type="hidden" name="convenienceFeeSacCode" id="convenienceFeeSacCode" value="<?php echo $convenienceFeeSacCode; ?>" readonly />

                                        <input type="hidden" name="gstStateCheck" id="gstStateCheck" value="<?php echo $gstStateCheck?>" />
                                      <input type="hidden" name="gstStateCode1" id="gstStateCode1" value="<?php echo $gstStateCode1?>" />
                                      <input type="hidden" name="orderAmount" id="orderAmount" value="<?php echo $orderAmount1?>" />
                                      <input type="hidden" name="pkgAmount" id="pkgAmount" readonly/>
                                      <input type="hidden" name="selectedDuration" id="selectedDuration" readonly/>
                                      <span id="txtPkgAmt"></span>
                                      </td>
                                      <input type="hidden" name="totalDays" value="<?php echo $numberDays?>" readonly>
                                      
                                        <input type="hidden" name="cgst" id="cgstVal" readonly />
                                        <input type="hidden" name="sgst" id="sgstVal" readonly/>
                                        <input type="hidden" name="igst" id="igstVal" readonly/>
                                        <!--<input type="hidden" name="payment_methods" id="payment_methods" value='<?php echo 'cc'; ?>'/>-->

                                       <td> <span id="txtTaxVal"></span>
                                          <input type="hidden" name="taxAmt" id="taxVal" readonly/>
                                        </td>

                                      <td> <span id="txtTotalVal"></span>
                                          <input type="hidden" name="totalAmount" id="totalVal" readonly/>
                                      </td>

                                      <td>
                                        <?php 
                                            if(!empty($lastEntry)){
                                              if($lastEntry[0]['transactionStatus']=='SUCCESS' && $lastEntry[0]['orderStatus']=='PAID'){
                                        ?>
                                                <button type="submit" id="payNow" class="btn btn-primary waves-effect" onclick="return Validate()" >Pay Now</button> 
                                        <?php     
                                              }else{
                                                echo "<span style='color:red'>Last transaction pending. Please retry payment for pending order below.</span>";
                                              }
                                            }else{
                                        ?>
                                            <button type="submit" id="payNow" class="btn btn-primary waves-effect" onclick="return Validate()" >Pay Now</button> 
                                        <?php    
                                            }
                                        ?>
                                        
                                      </td>
                                  </tr>

                                  <tr class="text-left">
                                  <td colspan="7">Certain payment modes may attract convenience charge. Please check exact amount while making the payment.</td>
                                  </tr>

                                  <?php } ?>
                                  </tbody>
                                  </form>
                                </table>
                                
                                <br/>

                              <table class="table table-bordered table-striped table-hover dataTable">
                                <thead>
                                    <tr style="background-color:white;">
                                      <td colspan="9" align="center"><h4>Recent Plans Transaction</h4></td>
                                      <td>
                                        <!-- <a href="<?php echo $redirectUrl; ?>" class="btn btn-primary m-t-10" target="_blank">View All</a> -->
                                      </td>
                                     </tr>
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
                                  // echo $value['id'];
                                      $udata=$this->PaymentGatewayModel->loadFileById('invoices',$value['cid']);
                                      // print_r($udata);exit;
                                      if (!empty($udata)) {
                                        foreach ($udata as $row) {
                                          $fileData =$adminBaseUrl.'/'.$row['filepath'];
                                        }
                                      } 
                                      // echo 'file '.$fileData;
                                    ?>
                                    <td>
                                        <a href="<?php echo $fileData;?>" title="Download File" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"  style="font-size:30px; color:red"></i></a>
                                    </td>
                                <?php }else if(($value['transactionStatus'] != "SUCCESS" || $value['transactionStatus'] != "PENDING") && ($value['orderStatus'] == "ACTIVE")){ ?>
                                    <td>
                                      <a href="<?php echo base_url();?>index.php/paymentGateways/PackageController/checkNow/?cid=<?php echo $value['cid'];?>&amt=<?php echo $value['netAmount'];?>">Retry Payment</a>
                                    </td>
                                <?php }else{ ?>
                                    <td>
                                        <a href="<?php echo base_url().'index.php/paymentGateways/PackageController/paymentStatus/'.$value['orderId'].'/'.$value['orderId']; ?>" title="<?php echo $value['transactionStatus']; ?>">Check Payment Status</a> 
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

<?php $this->load->view('/layouts/footerDataTable'); ?>

<script type="text/javascript">
  $(document).ready(function(){
  $('#duration').change(function(){
    // alert($('#duration').val());
    var abc_total = parseInt($('#orderAmount').val());
    var gstStateCheck = parseInt($('#gstStateCheck').val());
    var gstStateCode1 = parseInt($('#gstStateCode1').val());
    //alert(gstStateCode1);
    var selected_price = parseInt($(this).val());
    //alert(gstStateCheck);
   var total = (abc_total * selected_price);
   // alert(total);
    $('#total_html').html(total);
    $('#pkgAmount').val(total);
    $('#txtPkgAmt').html(total);
    

    //SIA gst value
   var gst=$('#ofcGstValue').val();
  // alert(gst);
   if(gstStateCheck == gstStateCode1){
            var cgst=gst/2;
            var cgstAmt = total * (cgst/100);
            var sgst=gst/2;
            var sgstAmt = total * (sgst/100);
            var igst=0;
            var igstAmt = total * (igst/100);

            taxAmt = cgstAmt + sgstAmt + igstAmt;
            var totalAmount = total + taxAmt ; 
         }else{
            var cgst=0;
            var cgstAmt = total * (cgst/100);
            var sgst=0;
            var sgstAmt = total * (sgst/100);
            var igst=gst;
            var igstAmt = total * (igst/100);

            var taxAmt = cgstAmt + sgstAmt + igstAmt;
            var totalAmount = total + taxAmt ;  
         }

          $('#cgst').html(cgstAmt);
          $('#sgst').html(sgstAmt);
          $('#igst').html(igstAmt);
          $('#taxAmt').html(taxAmt);
          $('#totalAmount').html(totalAmount);

          $('#cgstVal').val(cgstAmt);
          $('#sgstVal').val(sgstAmt);
          $('#igstVal').val(igstAmt);
          $('#taxVal').val(taxAmt);
          $('#txtTaxVal').html(taxAmt);
          
          $('#totalVal').val(totalAmount);
          $('#txtTotalVal').html(totalAmount);
          $('#selectedDuration').val($('#duration').val());
          
          // $('#duration').val($('#duration').val());
          
  });
});
</script>
<script type="text/javascript">

  $(document).on('click','#pkg-did',function(){
        var id=$(this).attr('data-id');
         alert(id);
        $.ajax({
            url: "<?php echo site_url('paymentGateways/PackageController/checkout'); ?>",
            method: "post",
            data: {'id': id,},
            success: function(data){
                 alert(data);
               // $('#update-package').html(data);
            }
        });
    });
</script>

<script type="text/javascript">
    function Validate() {
        var ddlFruits = document.getElementById("duration");
        if (ddlFruits.value == "") {
            //If the "Please Select" option is selected display error.
            alert("Please select Duration!");
            return false;
        }
        return true;
    }
</script>
  

