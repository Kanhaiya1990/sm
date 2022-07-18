<?php $this->load->view('/layouts/commanHeader'); ?>

<script>
function goBack() {
  window.history.back();
}
</script>
        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               <button onclick="goBack();" class="btn btn-xs bg-primary margin"><i class="material-icons">keyboard_return</i></button></a> GST SR Report
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row">
                                <p align="right">
                                    <button type="button" id="insert-ins" class="btn btn-primary m-t-15 waves-effect"> <i class="material-icons">save</i> 
                                              <span class="icon-name">Save Selected</span>
                                    </button>
                                </p>
                            </div><br> 
                            <div class="table-responsive">
                                <!-- <input type="hidden" id="allocationId" value="<?php echo $allocationId; ?>"> -->
                                <table class="table table-bordered table-striped table-hover js-exportable dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th><input class="checkall" type="checkbox" name="selValue" id="basic_checkbox"/>
                                                            <label for="basic_checkbox"></label></th>
                                            <th>Bill No.</th>
                                            <th>Retailer Name</th>
                                            <th>Retailer Code</th>
                                             <th>Company</th>
                                            <th>Item</th>
                                            <th>Quantity</th>
                                            <!-- <th>Action</th> -->
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Select</th>
                                            <th>Bill No.</th>
                                            <th>Retailer Name</th>
                                            <th>Retailer Code</th>
                                             <th>Company</th>
                                            <th>Item</th>
                                            <th>Quantity</th>
                                            <!-- <th>Action</th> -->
                                        </tr>
                                    </tfoot>
                                    <tbody id="tbl_data">
                                <?php
                                    $no=0;
                                        if(!empty($pendingGstSr)){

                                            foreach ($pendingGstSr as $data) 
                                            {
                                                // print_r($data);
                                                $allocation_sr_id=$data['sr_id'];
                                                $no++; 

                                                $sumQty=$this->OperatorModel->getQtySumByBill($data['billId']);
                                                $sumSRQty=$this->OperatorModel->getSrQtySumByBill($data['billId'],$data['sr_allocationId']);

                                                // echo $sumQty.' '.$sumSRQty;
                                                if($sumQty!=$sumSRQty){
                                                    if($data['sr_qty'] >0){
                               
                                ?>
                                    <tr>
                                         <td>
                                                            <input class="checkhour" type="checkbox" name="selValue" value="<?php echo $allocation_sr_id.':'.$data['sr_allocationId'].':'.$data['billId'].':'.'SR'; ?>" id="basic_checkbox_<?php echo $data['sr_id']; ?>" />
                                                            <label for="basic_checkbox_<?php echo $data['sr_id']; ?>"></label>
                                                        </td>
                                        <td><?php echo $data['billNo']; ?></td>
                                        <td><?php echo $data['retailerName']; ?></td>
                                        <td><?php echo $data['retailerCode']; ?></td>
                                        <td><?php echo $data['compName']; ?></td>
                                        <td><?php echo $data['prodName']; ?></td>
                                        <td><?php echo $data['sr_qty']; ?></td>
                                        
                                   </tr>  
                                <?php          }
                                            }
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
<script type="text/javascript">
    jQuery("#insert-ins").on("click",function(){
        var selValue = [];
        $.each($("input[name='selValue']:checked"), function(){
                selValue.push($(this).val());
        });

        if(selValue.length>0 ){
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('operator/OperatorController/savePendingGstSr');?>",
                data:{selValue:selValue},
                success: function (data) {
                    $("input[type=checkbox]").each(function(){
                        $(this).attr('checked', false);
                    });      
                    window.location.href="<?php echo base_url();?>index.php/operator/OperatorController/srPrint";
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
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('operator/OperatorController/getGSTPendingSr');?>",
            data:{"allocationSrId":id},
            success: function (data) {
                // alert(data);
                $('#tbl_data').html(data);
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

<script type="text/javascript">
 var seen = {};
$('table tr').each(function() {
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
      $(".checkhour").prop("checked", !clicked);
      clicked = !clicked;
      this.innerHTML = clicked ? 'Deselect' : 'Select';
    });
</script>
