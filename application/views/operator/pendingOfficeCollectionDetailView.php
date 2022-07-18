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
                        <div class="header">
                            <h2>
                               <button onclick="goBack();" class="btn btn-xs bg-primary margin"><i class="material-icons">keyboard_return</i></button></a> <?php echo $title; ?>
                            </h2>
                            <br>
                           <p>
                                <?php if(!empty($allDetails)){ ?>
                                    <span>
                                        Allocation : 
                                        <label id="allocation"><?php echo $allDetails[0]['allocationCode']?></label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        Date : 
                                        <label id="allocation"><?php $dt=date_create($allDetails[0]['createdAt']);
                                                    $date = date_format($dt,'d-M-Y H:i:sa'); echo $date; ?></label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                       
                                    </span>
                                <?php } ?>
                                </p> 
                                <p align="right">
                                    <button align="right" type="button" id="insert-ins" class="btn btn-xs btn-primary m-t-15 waves-effect"> <i class="material-icons">save</i><span class="icon-name">Save Selected</span></button>
                                </p>

                            
                        </div>


                        
                        <div class="body">
                            <div class="row" id="withchkres">
                                <table style="font-size: 12px" class="table  table-bordered">
                                    <thead>
                                        <tr>
                                            <th colspan="9"><center><h6>Total Collection</h6></center></th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th>Bill Count.</th>
                                            <th>FSR</th>
                                            <th>Clear</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                         <tr>
                                            <td><?php echo $billCount[0]['countBill']; ?></td>
                                            <td><?php echo $fsr; ?></td>
                                            <td><?php echo $clear; ?></td>
                                            <td><h6><?php echo $finalTotal; ?></h6></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row" id="chkres" style="display: none;">
                                <table style="font-size: 12px" class="table  table-bordered">
                                    <thead>
                                        <tr>
                                            <th colspan="9"><center><h6>Total Collection</h6></center></th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th>Bill Count.</th>
                                            <th>FSR</th>
                                            <th>Clear</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                         <tr>
                                            <td id="cntchk"><?php echo $billCount[0]['countBill']; ?></td>
                                            <td><?php echo $fsr; ?></td>
                                            <td><?php echo $clear; ?></td>
                                            <td id="TotalInvoiceAmt"><h6><?php echo $finalTotal; ?></h6></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="row">
                            <div class="table-responsive">
                                <input type="hidden" id="allocationId" value="<?php echo $allocationId; ?>">
                                <table style="font-size: 12px" id="test" class="table table-bordered table-striped table-hover js-exportable dataTable" data-page-length='100'>
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
                                                            <input class="checkhour" type="checkbox" name="selValue" value="<?php echo $data['id']; ?>" id="basic_checkbox_<?php echo $data['id']; ?>" />
                                                            <label for="basic_checkbox_<?php echo $data['id']; ?>"></label>
                                                        </td>
                                                         <td><?php echo $data['billNo']; ?></td>
                                                         <?php if($data['operatorApproval']=="2"){ ?>
                                                            <td><?php
                                                                $dt=date_create($data['date']);
                                                                $date = date_format($dt,'d-M-Y');
                                                                echo $date.'<span class="logo_prov">Rejected</span>';
                                                            ?>
                                                              </td>
                                                        <?php }else{ ?>
                                                                <td><?php  
                                                                $dt=date_create($data['date']);
                                                                $date = date_format($dt,'d-M-Y');
                                                                echo $date; ?>
                                                                    
                                                                </td>
                                                        <?php } ?>
                                                         <td><?php echo $data['retailerName']; ?></td>
                                                         <td class="wagein"><?php echo $data['amount']; ?></td>
                                                   
                                                         <td><?php echo $data['transactionType']; ?></td>
                                                         <td><?php echo $data['remark']; ?></td>
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
                url:"<?php echo site_url('operator/OperatorController/savePendingOfficeCollection');?>",
                data:{selValue:selValue},
                success: function (data) {
                    $("input[type=checkbox]").each(function(){
                        $(this).attr('checked', false);
                    });      
                    window.location.href="<?php echo base_url();?>index.php/operator/OperatorController/pendingCollection";
                },
                beforeSend: function(){
                    $('.comman-ajax-loader').css("visibility", "visible");
                },
                complete: function(){
                    $('.comman-ajax-loader').css("visibility", "hidden");
                },
                error: function(jqXHR, exception) {
                    alert("Something Went Wrong, Please Try Again...!");
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
            },
            beforeSend: function(){
                $('.comman-ajax-loader').css("visibility", "visible");
            },
            complete: function(){
                $('.comman-ajax-loader').css("visibility", "hidden");
            },
            error: function(jqXHR, exception) {
                alert("Something Went Wrong, Please Try Again...!");
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
            },
            beforeSend: function(){
                $('.comman-ajax-loader').css("visibility", "visible");
            },
            complete: function(){
                $('.comman-ajax-loader').css("visibility", "hidden");
            },
            error: function(jqXHR, exception) {
                alert("Something Went Wrong, Please Try Again...!");
            }     
        });
    }
</script>


<!-- <script type="text/javascript">
 var seen = {};
$('$test tr').each(function() {
  var txt = $(this).find("td:not(:first)").text();
  
  if (seen[txt])
    $(this).remove();
  else
    seen[txt] = true;
});
</script> -->

<script type="text/javascript">
    var clicked = false;
    $(".checkall").on("click", function() {
          $(".checkhour").prop("checked", !clicked);
          clicked = !clicked;
          this.innerHTML = clicked ? 'Deselect' : 'Select';
    });
</script>

<script type="text/javascript">
    $('.checkhour').change(function () {
        var total = 0;
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
        var cnt=$('input[name="selValue"]:checked').length;
        if(total>0){
            $('#withchkres').hide();
            $('#chkres').show();
            $('#TotalInvoiceAmt').text(total.toFixed(2));
            $('#cntchk').text(cnt);
        }else{
            $('#withchkres').show();
            $('#chkres').hide();
            $('#TotalInvoiceAmt').text('');
            $('#cntchk').text('');
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
            $('#withchkres').hide();
            $('#chkres').show();
            $('#TotalInvoiceAmt').text(total.toFixed(2));
            $('#cntchk').text(cnt);
        }else{
            $('#withchkres').show();
            $('#chkres').hide();
            $('#TotalInvoiceAmt').text('');
            $('#cntchk').text('');
        }
       
    });
</script>