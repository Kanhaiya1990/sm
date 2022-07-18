<?php $this->load->view('/layouts/commanHeader'); ?>

<script>
function goBack() {
  window.history.back();
}
</script>

<style type="text/css">
    .logo_prov {
        border-radius: 30px;
         border: 1px solid black;
        background: red;
        color: black;
        padding: 6px;
        width: 50px;
        height: 50px;
    }
</style>
        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header flex-div">
                            <h2><?php echo $title; ?></h2>
                            <h2>
                               <button onclick="goBack();" class="btn btn-xs btnStyle margin"><i class="material-icons">keyboard_return</i></button>
                            </h2>
                        </div>
                            <br>
                            <p class="cust-tbl">
                                <?php if(!empty($allDetails)){ ?>
                                    <span>
                                        Allocation : 
                                        <label id="allocation"><?php echo $allDetails[0]['allocationCode']?></label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        Date : 
                                        <label id="allocation"><?php $dt=date_create($allDetails[0]['date']);
                                                    $date = date_format($dt,'d-M-Y H:i:sa'); echo $date; ?></label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        Route : 
                                        <label id="allocation"><?php echo $allDetails[0]['rname']?>
                                        </label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        Employees : 
                                        <label id="allocation">
                                        <?php 
                                        $ename=$allDetails[0]['e1name'].','.$allDetails[0]['e2name'].','.$allDetails[0]['e3name'].','.$allDetails[0]['e4name'];
                                        echo trim($ename,',');

                                        ?>
                                        </label>
                                    </span>
                                <?php } ?>
                                </p> 
                                <p align="right">
                                    <button align="right" type="button" id="insert-ins" class="btn btn-xs btnStyle m-t-15 waves-effect"> <i class="material-icons">save</i><span class="icon-name">Save Selected</span></button>
                                </p>

                        <div class="body">
                            <div class="row" id="default_tbl">
                                <table class="table cust-tbl table-bordered">
                                    <thead>
                                        <tr>
                                            <th colspan="9"><center><h6>Total Collection</h6></center></th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th>Bill Count.</th>
                                            <th>Cash</th>
                                            <th>Cheque</th>
                                            <th>NEFT</th>
                                            <th>CD</th>
                                            <th>Office Adjustment</th>
                                            <th>Other Adjustment</th>
                                            <th>Debit</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                         <tr>
                                            <td><?php echo $billCount[0]['countBill']; ?></td>
                                            <td><?php echo $cashTotal; ?></td>
                                            <td><?php echo $chequeTotal; ?></td>
                                            <td><?php echo $neftTotal; ?></td>
                                            <td><?php echo $cdTotal; ?></td>
                                            <td><?php echo $officeAdjAmtTotal; ?></td>
                                            <td><?php echo $otherAdjAmtTotal; ?></td>
                                            <td><?php echo $debitTotal; ?></td>
                                            <td><h6><?php echo $finalTotal; ?></h6></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                             <div class="row" id="runclick" style="display:none;">
                                <table class="table cust-tbl table-bordered">
                                    <thead>
                                        <tr>
                                            <th colspan="9"><center><h6>Total Collection</h6></center></th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th>Bill Count.</th>
                                            <th>Cash</th>
                                            <th>Cheque</th>
                                            <th>NEFT</th>
                                            <th>CD</th>
                                            <th>Office Adjustment</th>
                                            <th>Other Adjustment</th>
                                            <th>Debit</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                         <tr>
                                            <td id="cntchk"><?php echo $billCount[0]['countBill']; ?></td>
                                            <td id="chkCashTotal"><?php echo $cashTotal; ?></td>
                                            <td id="chkChequeTotal"><?php echo $chequeTotal; ?></td>
                                            <td id="chkNeftTotal"><?php echo $neftTotal; ?></td>
                                            <td id="chkCdTotal"><?php echo $cdTotal; ?></td>
                                            <td id="chkOfcAdjTotal"><?php echo $officeAdjAmtTotal; ?></td>
                                            <td id="chkOtherAdjTotal"><?php echo $otherAdjAmtTotal; ?></td>
                                            <td id="chkDebitTotal"><?php echo $debitTotal; ?></td>
                                            <td><h6 id="TotalInvoiceAmt"><?php echo $finalTotal; ?></h6></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="row">
                            <div class="table-responsive">
                                <input type="hidden" id="allocationId" value="<?php echo $allocationId; ?>">
                                <table id="test" class="table table-bordered cust-tbl js-exportable dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th><input class="checkall" type="checkbox" name="selValue" id="basic_checkbox"/>
                                                            <label for="basic_checkbox"></label></th>
                                            <th>Bill No.</th>
                                            <th>Bill Date</th>
                                            <th>Retailer Name</th>
                                            <th>Paid Amount</th>
                                            <th>Payment Mode</th>
                                            <th>Detail/Remark</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th>Bill No.</th>
                                            <th>Bill Date</th>
                                            <th>Retailer Name</th>
                                            <th>Paid Amount</th>
                                            <th>Payment Mode</th>
                                            <th>Detail/Remark</th>
                                        </tr>
                                    </tfoot>
                                    <tbody id="tbl_data">
                                        
                                <?php
                                    $no=0;
                                    if(!empty($collectionDetails)){
                                       
                                        foreach ($collectionDetails as $data) 
                                        {
                                                 $n=0;$no=0; 

                                                 
                                                
                                            ?>
                                                    <tr>
                                                        <td>
                                                            <input class="checkhour" type="checkbox" name="selValue" value="<?php echo $data['paymentId']; ?>" id="basic_checkbox_<?php echo $data['paymentId']; ?>" />
                                                            <label for="basic_checkbox_<?php echo $data['paymentId']; ?>"></label>
                                                        </td>

                                                        
                                                            <td><?php echo $data['billNo']; ?></td>
                                                         
                                                            
                                                        
                                                        <?php if($data['ownerApproval']=="2"){ ?>
                                                         <td><?php
                                                                $dt=date_create($data['billDate']);
                                                                $date = date_format($dt,'d-M-Y');
                                                                echo $date.'<span class="logo_prov">Rejected</span>';
                                                            ?>
                                                              </td>
                                                        <?php }else{ ?>
                                                                <td><?php  
                                                                $dt=date_create($data['billDate']);
                                                                $date = date_format($dt,'d-M-Y');
                                                                echo $date; ?>
                                                                    
                                                                </td>
                                                        <?php } ?>
                                                      
                                                         <td><?php echo $data['retailerName']; ?></td>
                                                         <td class="wagein"><?php echo $data['paidAmount']; ?></td>
                                                        <td class="mode"><?php echo $data['paymentMode']; ?></td>
                                                    <?php 
                                                    if($data['paymentMode']=="Cheque" || $data['paymentMode']=="NEFT"){
                                                        if($data['paymentMode']=="Cheque"){
                                                            $dt=date_create($data['chequeStatusDate']);
                                                            $date = date_format($dt,'d-M-Y'); 
                                                            if($data['chequeStatus']!=""){
                                                    ?>
                                                            <td><?php echo " Status: ".$data['chequeStatus'].",  Cheque No.: ".$data['chequeNo'].",  Date:".$date; ?></td>
                                                    <?php 
                                                            } else { 
                                                                echo "<td>Operation Pending</td>";
                                                            }
                                                        }  
                                                        if($data['paymentMode']=="NEFT"){
                                                            $dt=date_create($data['neftDate']);
                                                            $neftDate = date_format($dt,'d-M-Y');
                                                                if($data['chequeStatus']!=""){
                                                    ?>
                                                            <td><?php echo " Status: ".$data['chequeStatus'].",  NEFT No.: ".$data['neftNo'].",  Date:".$neftDate; ?></td>
                                                    <?php 
                                                            } else { 
                                                                echo "<td>Operation Pending</td>";
                                                            }
                                                        } 
                                                      }else if($data['paymentMode']=="Office Adjustment" || $data['paymentMode']=="Other Adjustment" || $data['paymentMode']=="Debit To Employee" || $data['paymentMode']=="Emp Delivery" || $data['paymentMode']=="CD"){ 

                                                        $remark=$this->OperatorModel->getRemark('bill_remark_history',$data['billId'],$data['paidAmount']);
                                                            if(!empty($remark)){
                                                        ?>
                                                            <td><?php echo $remark[0]['remark']; ?></td>
                                                     <?php 
                                                            }else{
                                                                echo "<td></td>";
                                                            }
                                                        } else { ?>
                                                            <td></td>
                                                     <?php } ?>
                                                       </tr>  
                                                    <?php
                                                }
                                            
                                        }
                                        if(!empty($allocationSr)){
                                            foreach($allocationSr as $sr){
                                    ?>
                                        <tr>
                                            <td></td>
                                            <td><?php echo $sr['billNo']; ?></td>
                                            <td>
                                            <?php
                                                $dt=date_create($sr['billDate']);
                                                $date = date_format($dt,'d-M-Y');
                                                echo $date;
                                            ?> 
                                            </td>
                                            <td><?php echo $sr['retailer']; ?></td>
                                            <td><?php echo round($sr['srTotal']); ?></td>
                                            <td><?php echo $sr['mode']; ?></td>
                                            <td></td>
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
            </div>
            <!-- #END# Basic Examples -->  
        </div>
    </section>
<?php $this->load->view('/layouts/footerDataTable'); ?>

  <script type="text/javascript">
    jQuery("#insert-ins").on("click",function(){
        var selValue = [];
        $.each($("input[name='selValue']:checked"), function(){
                selValue.push($(this).val());
        });


        if(selValue.length>0 ){
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('operator/OperatorController/savePendingCollection');?>",
                data:{selValue:selValue},
                success: function (data) {
                    $("input[type=checkbox]").each(function(){
                        $(this).attr('checked', false);
                    });      
                    window.location.href="<?php echo base_url();?>index.php/operator/OperatorController/pendingCollection";
                }  
            });
        }else{
            alert('Please select Bills.');
        }
});
    
</script>

<script type="text/javascript">
    function submitStatus(id){
        var allocationId=document.getElementById('allocationId').value;
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('operator/OperatorController/getPendingSr');?>",
            data:{"allocationSrId":id,"allocationId":allocationId},
            success: function (data) {
                $('#tbl_data').html(data);
                // window.location.reload(true);
            }  
        });
    }

     function submitFSRStatus(id){
        var allocationId=document.getElementById('allocationId').value;
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('operator/OperatorController/getPendingFSR');?>",
            data:{"billId":id,"allocationId":allocationId},
            success: function (data) {
                $('#tbl_data').html(data);
                 // window.location.reload(true);
            }  
        });
    }
</script>


<script type="text/javascript">
 var seen = {};
$('$test tr').each(function() {
  var txt = $(this).find("td:not(:first)").text();
  
  if (seen[txt])
    $(this).remove();
  else
    seen[txt] = true;
});
</script>

<script type="text/javascript">
    var clicked = false;
    $(".checkall").on("click", function() {
        var sum = 0;
        $('.chkclass:checked').each(function() {
            sum += parseFloat($(this).closest('tr').find('.wagein').text());
        });
        console.log(sum);
      $(".checkhour").prop("checked", !clicked);
      clicked = !clicked;
      this.innerHTML = clicked ? 'Deselect' : 'Select';
    });
</script>

<script type="text/javascript">
    $('.checkhour').change(function () {
        var total = 0;
        var cashTotal=0;
        var chequeTotal=0;
        var neftTotal=0;
        var cdTotal=0;
        var officeAdjTotal=0;
        var otherAdjTotal=0;
        var debitTotal=0;
        // iterate through each td based on class and add the values
        $(".wagein").each(function () {
            //Check if the checkbox is checked
            if ($(this).closest('tr').find('.checkhour').is(':checked')) {
                var value = $(this).text();
                
                // add only if the value is number
                if (!isNaN(value) && value.length != 0) {
                    total += parseFloat(value);
                }
            }
        });

        $(".mode").each(function () {
            //Check if the checkbox is checked
            if ($(this).closest('tr').find('.checkhour').is(':checked')) {
                var value = $(this).text();
                if(value=="Cash"){
                    var cashAmt=parseFloat($(this).closest('tr').find('.wagein').text());
                    cashTotal=cashTotal+cashAmt;
                }

                if(value=="Cheque"){
                    var cashAmt=parseFloat($(this).closest('tr').find('.wagein').text());
                    chequeTotal=chequeTotal+cashAmt;
                }

                if(value=="NEFT"){
                    var cashAmt=parseFloat($(this).closest('tr').find('.wagein').text());
                    neftTotal=neftTotal+cashAmt;
                }

                if(value=="CD"){
                    var cashAmt=parseFloat($(this).closest('tr').find('.wagein').text());
                    cdTotal=cdTotal+cashAmt;
                }

                if(value=="Debit"){
                    var cashAmt=parseFloat($(this).closest('tr').find('.wagein').text());
                    debitTotal=debitTotal+cashAmt;
                }

                if(value=="Other Adjustment"){
                    var cashAmt=parseFloat($(this).closest('tr').find('.wagein').text());
                    otherAdjTotal=otherAdjTotal+cashAmt;
                }

                if(value=="Office Adjustment"){
                    var cashAmt=parseFloat($(this).closest('tr').find('.wagein').text());
                    officeAdjTotal=officeAdjTotal+cashAmt;
                }
                // add only if the value is number
                if (!isNaN(value) && value.length != 0) {
                    total += parseFloat(value);
                }
            }
        });
        var cnt=$('input[name="selValue"]:checked').length;
        if(total>0){
            $('#default_tbl').hide();
            $('#runclick').show();
            $('#TotalInvoiceAmt').text(total);
            $('#cntchk').text(cnt);

            $('#chkCashTotal').text(cashTotal);
            $('#chkChequeTotal').text(chequeTotal);
            $('#chkNeftTotal').text(neftTotal);
            $('#chkOfcAdjTotal').text(officeAdjTotal);
            $('#chkOtherAdjTotal').text(otherAdjTotal);
            $('#chkCdTotal').text(cdTotal);
            $('#chkDebitTotal').text(debitTotal);
        }else{
            $('#default_tbl').show();
            $('#runclick').hide();
            $('#TotalInvoiceAmt').text('');
            $('#cntchk').text('');

             $('#chkCashTotal').text('');
            $('#chkChequeTotal').text('');
            $('#chkNeftTotal').text('');
            $('#chkOfcAdjTotal').text('');
            $('#chkOtherAdjTotal').text('');
            $('#chkCdTotal').text('');
            $('#chkDebitTotal').text('');
        }
       
    });
</script>
<script type="text/javascript">
    $('.checkall').change(function () {
        var total = 0;
        // iterate through each td based on class and add the values
        $(".wagein").each(function () {
            //Check if the checkbox is checked
            if ($(this).closest('tr').find('.checkall').is(':checked')) {
                var value = $(this).text();
                // add only if the value is number
                if (!isNaN(value) && value.length != 0) {
                    total += parseFloat(value);
                }
            }
        });
        
        var cnt=$('input[name="selValue"]:checked').length;
        if(total>0){
            $('#default_tbl').hide();
            $('#runclick').show();
            $('#TotalInvoiceAmt').text(total.toFixed(2));
            $('#cntchk').text(cnt);
        }else{
            $('#default_tbl').show();
            $('#runclick').hide();
            $('#TotalInvoiceAmt').text('');
            $('#cntchk').text('');
        }
       
    });
</script>