<?php $this->load->view('/layouts/commanHeader'); ?>

<style type="text/css">
    @media screen and (min-width: 950px) {
        .modal-dialog {
          width: 950px; /* New width for default modal */
        }
        .modal-sm {
          width: 350px; /* New width for small modal */
        }
    }

    @media screen and (min-width: 950px) {
        .modal-lg {
          width: 950px; /* New width for large modal */
        }
    }

</style>


<style type="text/css">
    .selectStyle select {
       background: transparent;
       width: 250px;
       padding: 4px;
       font-size: 1em;
       border: 1px solid #ddd;
       height: 25px;
    }
    li{
    margin-bottom: 0PX;
    padding-bottom: 0PX;
    }
    .sticky {
      position: fixed;
      top: 0;
      width: 100%;
    }
</style>

    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                           <center> <h2>
                               Sale Return Check 
                            </h2></center>
                            <br>
                             <div class="cust-tbl">
                                <span><b>Allocation No : </b><?php echo $BillInfo[0]['allocationCode']; ?></span>&nbsp;&nbsp;
                                <span><b>Company : </b><?php echo $BillInfo[0]['company']; ?></span>&nbsp;&nbsp;
                                <span><b>Route : </b><?php echo $BillInfo[0]['rname']; ?></span>&nbsp;&nbsp;
                                <span><b>Employee : </b><?php echo $BillInfo[0]['ename'].' '.$BillInfo[0]['ename2'].' '.$BillInfo[0]['ename3'].' '.$BillInfo[0]['ename4']; ?></span>&nbsp;&nbsp;
                                <div align="right">
                                <!-- <a href="<?php echo site_url('godownkeeper/GodownKeeperController/db_backup'); ?>">Backup</a> -->

                        <!-- <form method="post" role="form" enctype="multipart/form-data" action="<?php echo site_url('godownkeeper/GodownKeeperController/saveGodownkeeperAllocation'); ?>"> 
                                   
                                    <input id="allocatedHid" name="allocatedId" type="hidden" value="<?php echo $allocationID; ?>">

                                    <a href="<?php echo site_url("AllocationByManagerController/openAllocations"); ?>" class="btn btn-primary m-t-15 waves-effect btn-sm">Back</a>       
                                    <button id="svId" class="btn btn-primary m-t-15 waves-effect btn-sm">
                                        <span class="icon-name">Save</span>
                                    </button> 
                        </form> -->
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <div id="res"></div>
                            <span id="allocationId" style="display:none"><?php echo $allocationID; ?></span>
                            <span id="all_id" style="display:none"></span>
                            <div class="table-responsive">
                                <?php if(!empty($sr) || !empty($fsr) || !empty($newFsr)){ ?>
                                <table class="table table-bordered cust-tbl js-basic-example DataTable display nowrap" id="example" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th colspan="11" class="text-center">SR/FSR Approval</th>
                                        </tr>
                                    </thead>    
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th style="display: none;"></th>
                                            <th>Bill No.</th>
                                            <th>Retailer</th>
                                            <th>Salesman</th>
                                            <th>Item</th>
                                            <th>MRP</th>
                                            <th>Billed Qty</th>
                                            <th>SR</th>
                                            <th class="noSpace">SR Received</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                   
                                    <tbody>
                                  
                                    <?php
                                        $no=0;
                                        if(!empty($sr)){
                                        foreach ($sr as $data) 
                                          { 
                                             $no++; 
                                             $srQty=$this->GodownKeeperModel->getTotalSrQtyById($data['id']);
                                    ?>
                                            <tr>
                                                <td style="width: 15px;"><?php echo $no;?></td>
                                               <td id="billId" style="display: none;"><?php echo $data['id'];?></td>
                                                <td id="billNo">
                                                    <?php echo $data['billNo'];?>
                                                </td>
                                                <td class="CellWithComment"><?php 	
												$retailerName=substr($data['retailerName'], 0, 15);
                                                echo rtrim($retailerName);?>
											    <span class="CellComment"><?php echo $result =substr($data['retailerName'],0); ?></span>
										        </td>
												<td class="CellWithComment"><?php 	
												$salesmanName=substr($data['salesmanName'], 0, 15);
                                                echo rtrim($salesmanName);?>
											    <span class="CellComment"><?php echo $result =substr($data['salesmanName'],0); ?></span>
										        </td>
												<td class="CellWithComment"><?php 	
												$productName=substr($data['productName'], 0, 15);
                                                echo rtrim($productName);?>
											    <span class="CellComment"><?php echo $result =substr($data['productName'],0); ?></span>
										        </td>
                                                <td class="text-right"><?php echo number_format($data['mrp']);?></td>
                                                <td class="text-right"><?php echo number_format($data['qty']);?></td>
                                                <td align="right" id="fsSrAmt"><?php echo number_format($data['fsReturnQty']);?></td>
                                                <td id="srQty" style="width:50px">
                                                    <?php if($data['gkStatus'] !=1 || $data['fsReturnQty'] >0){ ?>
                                                    <input style="width: 100%" onblur="checkQtyPerItem(this,'<?php echo $no; ?>','<?php echo $data['qty']; ?>','<?php echo $data['fsReturnQty']; ?>')" onfocus="this.select();" type="text" name="fsReturnQty" value="<?php echo (int)$data['fsReturnQty']; ?>">
                                                <?php }else{ ?>
                                                     <?php echo (int)$data['fsReturnQty'];?>
                                                <?php } ?>
                                                    <p style="color:red" id="data_err<?php echo $no; ?>"></p>
                                                
                                                </td>
                                                <td>
                                                    <?php if($data['gkStatus'] !=1 || $data['fsReturnQty'] >0){ ?>
                                                    <button id="btn_err<?php echo $no; ?>" onclick="updateSRqty(this,<?php echo $no; ?>);" class="btn btn-sm btnStyle primary waves-effect" style="padding: 3px 10px;">
                                                        OK 
                                                    </button>
                                                <?php }else{ ?>
                                                        <i class="material-icons">check</i>
                                                <?php } ?>
                                                </td>
                                           </tr>
                                     <?php
                                            }
                                        }
                                        if(!empty($fsr)){
                                        foreach ($fsr as $data) 
                                          { 
                                            // print_r($data);
                                             $no++; 
                                             $chkFSR=$this->GodownKeeperModel->detailByGkBill('billsdetails',$data['id']);
                                            
                                    ?>
                                            <tr>
                                                <td><?php echo $no;?></td>
                                                <td id="billId" style="display: none;"><?php echo $data['id'];?></td>
                                                <td>
                                                    <?php echo $data['billNo'];?>
                                                </td>
												<td class="CellWithComment"><?php 	
												$retailerName=substr($data['retailerName'], 0, 15);
                                                echo rtrim($retailerName);?>
											    <span class="CellComment"><?php echo $result =substr($data['retailerName'],0); ?></span>
										        </td>
                                                <td class="CellWithComment"><?php 	
												$salesmanName=substr($data['salesmanName'], 0, 15);
                                                echo rtrim($salesmanName);?>
											    <span class="CellComment"><?php echo $result =substr($data['salesmanName'],0); ?></span>
										        </td>
                                                <td> 
                                                <?php if($data['creditAdjustment'] >0){ ?>
                                                    <button onclick="creditAdjustmentMsg();" class="btn btn-sm btnStyle waves-effect" style="padding: 2px 13px;">
                                                        FSR
                                                    </button>
                                                <?php } else { ?>
                                                    <button id="fsr-id" onclick="showModal(this,'<?php echo $data['id']; ?>','<?php echo $allocationID; ?>');" data-toggle="modal" data-target="#retailerModal" class="btn btn-sm btnStyle waves-effect" style="padding: 2px 13px;">
                                                        FSR
                                                    </button>
                                                <?php } ?>

                                                </td>
                                                <td class="text-right"><?php echo number_format($data['netAmount']);?></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                   <!--  <button onclick="updateSRqty(this);removeMe(this);" class="btn-primary waves-effect"> -->
                                                    <?php if(!empty($chkFSR)){ 

                                                    ?>
                                                     <button data-id="<?php echo $data['id']; ?>" data-allocationId="<?php echo $allocationID; ?>" id="fsr_btn_clk" class="btn btn-sm btnStyle waves-effect" style="padding: 3px 10px;">
                                                        OK
                                                    </button>

                                                    <button data-id="<?php echo $data['id']; ?>" id="fsr_btn_cncl_clk" class="btn btn-sm btn-danger waves-effect" style="padding: 3px 10px;">
                                                        Cancel
                                                    </button>
                                                <?php }else{ ?>
                                                        <i class="material-icons">check</i>
                                                <?php } ?>
                                                </td>
                                           </tr>
                                     <?php 
                                            }
                                           } 

                                           if(!empty($newFsr)){
                                        foreach ($newFsr as $data) 
                                          { 
                                            // print_r($data);
                                             $no++; 
                                             $chkFSR=$this->GodownKeeperModel->detailByGkBill('billsdetails',$data['id']);
                                                if(empty($chkFSR)){

                                                
                                    ?>
                                            <tr>
                                                <td><?php echo $no;?></td>
                                                <td id="billId" style="display: none;"><?php echo $data['id'];?></td>
                                                <td>
                                                    <?php echo $data['billNo'];?>
                                                </td>
                                                <td><?php 
                                                $retailerName=substr($data['retailerName'], 0, 20);
                                                echo $retailerName;?></td>
                                                <td><?php echo $data['salesmanName'];?></td>
                                                <td> 
                                                    FSR
                                                </td>
                                                <td class="text-right"><?php echo number_format($data['netAmount']);?></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                   <!--  <button onclick="updateSRqty(this);removeMe(this);" class="btn-primary waves-effect"> -->
                                                    <?php if($data['pendingAmt'] >0){ 

                                                    ?>
                                                     <button data-id="<?php echo $data['id']; ?>" data-allocationId="<?php echo $allocationID; ?>" id="newbill_fsr_btn_clk" class="btn btn-sm btnStyle waves-effect">
                                                        OK
                                                    </button>

                                                    <button data-id="<?php echo $data['id']; ?>" id="newbill_fsr_btn_cncl_clk" class="btn btn-sm btn-danger waves-effect">
                                                        Cancel
                                                    </button>
                                                <?php }else{ ?>
                                                        <i class="material-icons">check</i>
                                                <?php } ?>
                                                </td>
                                           </tr>
                                     <?php 
                                            }
                                           } 
                                       }
                                     ?>
                                    </tbody>
                                </table>
                                <?php } ?>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered cust-tbl table-hover js-basic-example DataTable display nowrap" id="example" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th colspan="7" class="text-center">Other Adjustment Approval</th>
                                        </tr>
                                    </thead>    
                                    <thead>
                                        <tr>
                                            <th style="width: 15px;">S. No.</th>
                                            <th>Bill No.</th>
                                            <th>Retailer</th>
                                            <th>Salesman</th>
                                            <th>Employee</th>
                                            <th>Other Adjustment Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                    <?php   
                                        if(!empty($otherAdj)){
                                            $n=0;
                                            foreach ($otherAdj as $data) 
                                            { 
                                                $n++;
                                                $amt=(int)$data['paidAmount'];
                                    ?> 
                                    <tr>
                                        <td><?php echo $n;?></td>
                                        <td><?php echo $data['billNo'];?></td>
                                        <td><?php 
                                            $retailerName=substr($data['retailerName'], 0, 20);
                                            echo $retailerName;?>
                                        </td>
                                        <td><?php echo $data['salesmanName'];?></td>
                                        <td><?php echo $data['ename'];?></td>
                                        <td class="text-right"><?php echo number_format($data['paidAmount']);?></td>
                                        <td>
                                            <input type="text" id="paidAmount<?php echo $data['id'] ?>" style="width:50%" value="<?php echo $amt; ?>" >
                                            <button onclick="statusOtherAdjustment(this,'<?php echo $data['paidAmount']; ?>','<?php echo $data['id']; ?>','<?php echo $data['bid']; ?>','<?php echo $allocationID;?>');" class="btn btnStyle waves-effect btn-sm">Save</button>
                                        </td>
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
    </section>

<div class="container">
  <div class="modal fade" id="retailerModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="mods">
              
          </div>
        
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('/layouts/footerDataTable'); ?>

<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>

<script type="text/javascript">
    function showModal(id,billId,allocationId){
        var id=billId;
        $.ajax({
            url : "<?php echo site_url('godownkeeper/GodownKeeperController/BillDetails');?>",
            method : "POST",
            data : {id: id,allocationId:allocationId},
            success: function(data){
              $('.mods').html(data);
            }
        });
    }
</script>
<!-- <script>
 $(document).ready(function(){
    $('#fsr-id').click(function(){
        var id=$(this).attr('data-id');
        alert(id);
        $.ajax({
            url : "<?php echo site_url('godownkeeper/GodownKeeperController/BillDetails');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
                alert(data);
              $('.mods').html(data);
            }
        });
        

    });
});
</script> -->

<!-- <script>
  $(document).on('click','#svId',function(){
        var rowCount = $('#example tr').length;
        var textData=$('#example tr');
        var allocatedID=$('#allocatedHid').val();
        if(rowCount<=3){
            $.ajax({
                url:"<?php echo site_url('godownkeeper/GodownKeeperController/saveGodownkeeperAllocation');?>",
                type: "POST",
                data:{"allocationId":allocatedID},
                success: function (data) {
                    window.parent.location.reload(true);
                }  
            });
        }else{
            alert('Please complete process');
        }
       
       
    });
  </script> -->

<script type="text/javascript">
    function updateSRqty(e,id){
        var btn = $("#btn_err"+id).attr("disabled", true);
        // var btn = document.getElementById("#btn_err"+id);
        // btn.disabled = true;
        // alert(id);die();
        var allocationId=document.getElementById('allocationId').innerHTML;
        var msg=document.getElementById('all_id').innerHTML;
        if(msg===''||msg===null){
            var billNo=$(e).closest('tr').find('#billNo').text().trim();
             var billId=$(e).closest('tr').find('#billId').text().trim();
             var fsSrAmt=$(e).closest('tr').find('#fsSrAmt').text().trim();
             var srQty=$(e).closest('tr').find('input').val();
             var srQty=parseInt(Math.abs(srQty));
             
             if((billNo!='' || billNo!=null) && (srQty>=0) && (billId!='' || billId!=null)){
                 $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('godownkeeper/GodownKeeperController/updateSr');?>",
                    data:{"allocationId":allocationId,"billNo" : billNo,"billId" : billId,"srQty":srQty,"fsSrAmt":fsSrAmt},
                    success: function (data) {
                        // alert(data);
                      window.parent.location.reload(true);
                        // document.getElementById('res').innerHTML=data;
                        // location.reload(); 
                        // $('.mods').html(data);
                    }  
                });
             }else{
                 document.getElementById('res').innerHTML='';
             }
        }else{
            return false;
        }
         
    }
    
    function updateSRqtyForFsr(e){
        var allocationId=document.getElementById('allocationId').innerHTML;
        var msg=document.getElementById('all_id').innerHTML;
        if(msg===''||msg===null){
            var billNo=$(e).closest('tr').find('#billNo').text().trim();
             var billId=$(e).closest('tr').find('#billId').text().trim();
             var fsSrAmt=$(e).closest('tr').find('#fsSrAmt').text().trim();

             var srQty=$(e).closest('tr').find('input').val();
             var srQty=parseInt(Math.abs(srQty));

             if((billNo!='' || billNo!=null) && (srQty>=0) && (billId!='' || billId!=null)){
                 $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('godownkeeper/GodownKeeperController/updateSr');?>",
                    data:{"allocationId":allocationId,"billNo" : billNo,"billId" : billId,"srQty":srQty,"fsSrAmt":fsSrAmt},
                    success: function (data) {
                      // window.parent.location.reload(true);
                        // document.getElementById('res').innerHTML=data;
                        // location.reload(); 
                        $('.mods').html(data);
                    }  
                });
             }else{
                 document.getElementById('res').innerHTML='';
             }
        }else{
            return false;
        }
         
    }
    
    
</script>

<script type="text/javascript">
    function updateCurrentSrValue(id,allocationId){

        var changedValues= new Array();
        $("input[name='fsReturnQty[]']").each(function(){
            changedValues.push($(this).val());
        }); 

        var oldSrValues= new Array();
        $("input[name='checkReturnQty[]']").each(function(){
            oldSrValues.push($(this).val());
        });

        if(changedValues.sort().toString() == oldSrValues.sort().toString()){
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('godownkeeper/GodownKeeperController/updatePendingAmtWithSrQuantity');?>",
                data:{"billId" : id,"allocationId":allocationId},
                success: function (data) {
                    // alert(data);die();
                    window.parent.location.reload(true);
                }  
            });
        }else{  
            alert("Can't be change quantity to accept all.");
        }
    }
</script>

<script type="text/javascript">
    function removeMe(t) {
        $(t).closest('tr').remove();
    }
</script>



<script type="text/javascript">
    $(document).on('click','#fsr_btn_clk',function(){
        // $("#fsr_btn_clk"+id).attr("disabled", true);
        var billId=$(this).attr('data-id');
        var allocationId=$(this).attr('data-allocationId');
        // alert(allocationId);die();
         $.ajax({
            url : "<?php echo site_url('godownkeeper/GodownKeeperController/updateAllFsr');?>",
            method : "POST",
            data : {billId: billId,allocationId:allocationId},
            success: function(data){
                // alert(data);die();
              window.parent.location.reload(true);
            }
        });
    });
 </script>

 <script type="text/javascript">
    $(document).on('click','#newbill_fsr_btn_clk',function(){
        // $("#newbill_fsr_btn_clk"+id).attr("disabled", true);
        var billId=$(this).attr('data-id');
        var allocationId=$(this).attr('data-allocationId');
         $.ajax({
            url : "<?php echo site_url('godownkeeper/GodownKeeperController/updateNewBillFsr');?>",
            method : "POST",
            data : {billId: billId,allocationId:allocationId},
            success: function(data){
                // alert(data);die();
              window.parent.location.reload(true);
            }
        });
    });
 </script>

 <script type="text/javascript">
    $(document).on('click','#fsr_btn_cncl_clk',function(){
        $("#fsr_btn_cncl_clk"+id).attr("disabled", true);
        var billId=$(this).attr('data-id');
        var allocationId=$('#allocatedHid').val();
        $.ajax({
            url : "<?php echo site_url('godownkeeper/GodownKeeperController/cancelFrsByGodownKeeper');?>",
            method : "POST",
            data : {billId: billId,allocationId:allocationId},
            success: function(data){
                // alert(data);die();
              window.parent.location.reload(true);
            }
        });
    });
 </script>

 <script type="text/javascript">
    $(document).on('click','#newbill_fsr_btn_cncl_clk',function(){
        $("#newbill_fsr_btn_cncl_clk"+id).attr("disabled", true);
        var billId=$(this).attr('data-id');
        var allocationId=$('#allocatedHid').val();
        $.ajax({
            url : "<?php echo site_url('godownkeeper/GodownKeeperController/cancelNewBillFrsByGodownKeeper');?>",
            method : "POST",
            data : {billId: billId,allocationId:allocationId},
            success: function(data){
                // alert(data);die();
              window.parent.location.reload(true);
            }
        });
    });
 </script>

 <!-- <script type="text/javascript">
    $(document).on('click','#svId',function(){
        var rowCount = $('#example tr').length;
        var textData=$('#example tr');
        var allocatedID=$('#allocatedHid').val();
         $.ajax({
            url : "<?php echo site_url('godownkeeper/GodownKeeperController/saveGodownkeeperAllocation');?>",
            method : "POST",
            data : {allocatedID: allocatedID},
            success: function(data){
                alert(data);
              window.parent.location.reload(true);
            }
        });
    });
 </script> -->

 <script type="text/javascript">
     function checkQtyPerItem(qty,no,billQty,fsReturnQty){
        var totalQty=parseInt(Math.abs(qty.value));
        var diff=parseInt(billQty)-totalQty;
        var msg="";
        if(totalQty>billQty || (diff<0) || totalQty>fsReturnQty){
            msg="Qty greater than billed Qty/Deliveryman Qty.";
            document.getElementById('data_err'+no).innerHTML=msg;
            document.getElementById('all_id').innerHTML=msg;
            document.getElementById('btn_err'+no).disabled=true;
        }else{
            var msg=""
            document.getElementById('data_err'+no).innerHTML=msg;
            document.getElementById('all_id').innerHTML=msg;
            document.getElementById('btn_err'+no).disabled=false;
        }
    }

    function checkFsrQtyPerItem(qty,no,billQty,fsReturnQty){
        var totalQty=parseInt(Math.abs(qty.value));
        var diff=parseInt(billQty)-totalQty;
        var msg="";
        if(totalQty>billQty || (diff<0)){
            msg="Qty greater than billed qty.";
            document.getElementById('data_errr'+no).innerHTML=msg;
            document.getElementById('all_id').innerHTML=msg;
            document.getElementById('btn_errr'+no).disabled=true;
        }else{
            var msg=""
            document.getElementById('data_errr'+no).innerHTML=msg;
            document.getElementById('all_id').innerHTML=msg;
            document.getElementById('btn_errr'+no).disabled=false;
        }
    }
 </script>

 <script>
function creditAdjustmentMsg() {
  alert("Credit Adjustment bill. Can't be altered");
}
</script>

<script type = "text/javascript" >  
    window.history.pushState(null, "", window.location.href);
    window.onpopstate = function () {
        window.history.pushState(null, "", window.location.href);
    };
</script> 

<script>
    function statusOtherAdjustment(t,oldPayment,billPaymentId,billId,allocationId){
        var paidAmount=$('#paidAmount'+billPaymentId).val();

        if(paidAmount > oldPayment){
            alert('Amount is greater than given amount');
            window.parent.location.reload(true);
            die();
        }
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('godownkeeper/GodownKeeperController/confirmOtherAdjustment');?>",
            data:{"paidAmount" : paidAmount,"billPaymentId" : billPaymentId,"billId":billId,"allocationId":allocationId},
            success: function (data) {
                window.parent.location.reload(true);
            }  
        });
    } 
</script>