<?php $this->load->view('/layouts/commanHeader'); ?>

<style type="text/css">
    @media screen and (min-width: 1100px) {
        .modal-dialog {
          width: 1100px; /* New width for default modal */
        }
        .modal-sm {
          width: 400px; /* New width for small modal */
        }
    }

    @media screen and (min-width: 1100px) {
        .modal-lg {
          width: 1100px; /* New width for large modal */
        }
    }
</style>
  
    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header flex-div" style="border-bottom: 0;">
                           <center> <h2>
                              Signed Bills
                            </h2></center>

                             <div align="right">
                                <form method="post" role="form" enctype="multipart/form-data" action="<?php echo site_url('manager/SrCheckController/finalSrBillStatus/'.$idAllocated); ?>"> 
                                           
                                        <button class="btn btn-danger m-t-15 waves-effect btn-sm">
                                                <span class="icon-name">Close</span>
                                        </button> 

                                        <button type="button" id="insert-ins" class="btn btn-sm btn-primary btnStyle m-t-15 waves-effect">
                                              <span class="icon-name">Clear Selected</span>
                                        </button>
                                </form>
                                </div> 
                                </div>
                           
                            <div class="row cust-tbl" style="padding: 0 20px;">
                                <div class="col-md-4" style="margin-bottom: 15px;">
                                <span><b>Allocation No : </b><?php echo $BillInfo[0]['allocationCode']; ?></span></div>
                                <div class="col-md-4" style="margin-bottom: 15px;">
                                <span><b>Company : </b><?php echo $BillInfo[0]['company']; ?></span></div>
                                <div class="col-md-4" style="margin-bottom: 15px;">
                                <span><b>Route : </b><?php echo $BillInfo[0]['rname']; ?></span></div>
                                <div class="col-md-4" style="margin-bottom: 15px;">
                                <span><b>Employee : </b><?php echo $BillInfo[0]['ename']; ?></span></div>
                               
                            </div>
                        
                        
                        <div class="body">
                            <div class="table-responsive">
                            <input type="hidden" name="alnum" value="<?php echo $idAllocated; ?>" id="alnum"/>
                                <table id="crTbl" class="table table-bordered cust-tbl js-basic-example dataTable" data-page-length="25">
                                    <thead>
                                        <tr>
                                            <th colspan="14" class="text-center">Signed Bill Check</th>
                                        </tr>
                                    </thead> 
                                    <!--<thead>-->
                                    <!--    <tr>-->
                                    <!--        <th>S. No.</th>-->
                                    <!--        <th>Bill No</th>-->
                                    <!--        <th>Retailer Name</th>-->
                                    <!--        <th>Bill Amount</th>-->
                                    <!--        <th colspan="2" class="text-center">Past SR</th>-->
                                    <!--        <th colspan="3" class="text-center">Today</th>-->
                                    <!--        <th>Pending Amount</th>-->
                                    <!--        <th>Payment Modes</th>-->
                                    <!--        <th><label for="basic_checkbox"><b>Received</b></label></th>-->
                                    <!--        <th><label for="basic_checkbox_forNotReceived"><b>Not Received</b></label></th>-->
                                    <!--        <th>Lost Bill Remark</th>-->
                                    <!--    </tr>-->
                                    <!--</thead>-->
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Bill No</th>
                                            <th class="noSpace">Retailer Name</th>
                                            <th>Bill</th>
                                            <th class="noSpace">Past SR</th>
                                            <th class="noSpace">Past Coll</th>
                                            <th>SR</th>
                                            <th>Coll</th>
                                            <th>Other</th>
                                            <th>Pending</th>
                                            <th>Modes</th> 
                                            <th><label for="basic_checkbox"><b>Received</b></label></th>
                                            <th class="noSpace"><label for="basic_checkbox_forNotReceived"><b>Not Received</b></label></th>
                                            <th class="noSpace">Lost Bill Remark</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <?php 
                                        $no=0;
                                        if(!empty($signed)){
                                            foreach($signed as $data){
                                                $retailerCode=$this->SrModel->loadRetailer($data['retailerCode']);
                                                $dt=date_create($data['date']);
                                                  $dt= date_format($dt,'d-M-Y');
                                                if($data['pendingAmt']>0){
                                                
                                                $no++;
                                                $diff=strtotime(date('Y-m-d'))-strtotime($data['date']);
                                        ?>
                                        <tr>
                                            <td><?php echo $no;?></td>
                                            <td><?php echo $data['billNo'];?></td>
											<td class="CellWithComment"><?php 	
												$retailerName=substr($data['retailerName'], 0, 10);
                                                echo rtrim($retailerName);?>
											    <span class="CellComment"><?php echo $result =substr($data['retailerName'],0); ?></span>
										    </td>
                                            <td align="right"><?php echo number_format($data['netAmount']); ?></td>
                                            <?php if($allocations[0]['gkStatus']==1){ ?>
                                            <td align="right">
                                                <?php 
                                                    $sr= (($data['SRAmt']-$data['fsSrAmt'])); 
                                                    if($sr <= 1){
                                                        echo 0;
                                                    }else{
                                                        echo $sr;
                                                    }
                                                ?>
                                            </td>
                                            <?php }else{ ?>
                                                <td align="right"><?php echo (($data['SRAmt'])); ?></td>
                                            <?php } ?>
                                            
                                            <td align="right"><?php echo number_format($data['receivedAmt']); ?></td>
                                            <td align="right"><?php echo round($data['fsSrAmt']); ?></td>
                                            <td align="right"><?php echo round(($data['fsCashAmt']+$data['fsChequeAmt']+$data['fsNeftAmt'])); ?></td>
                                            <td align="right"><?php echo round(($data['fsOtherAdjAmt'])); ?></td>
                                            <td align="right"><?php echo round(($data['pendingAmt']));?></td>
                                        <?php if($data['fsbillStatus']==='Billed'){ ?>
                                            <td align="left"><?php echo 'Bill'; ?></td>
                                        <?php }else{ 
                                                if(strpos($data['fsbillStatus'], 'Billed') !== false){
                                        ?>
                                                 <td align="left"><?php echo str_replace("Billed","Bill",$data['fsbillStatus']); ?></td>   
                                        <?php   }else{  ?>
                                                    <td align="left"><?php
                                                    $statusData=$data['fsbillStatus'];
                                                    if($data['fsbillStatus']=="F"){
                                                        $statusData="SR";
                                                    }
                                                    echo $statusData.',Bill'; 
                                            
                                                    ?></td>          
                                        <?php   } 
                                            } 
                                        ?>
                                            <td>
                                            <?php if($data['isLostBill'] == 0){ ?>
                                            <!-- <button data-toggle="modal" data-target="#billRemarkModal" data-type="basic" data-id="<?php echo $data["id"]; ?>" data-name="<?php echo $idAllocated; ?>" data-id="<?php echo $data['id']; ?>" data-salesman="<?php echo $data['salesman']; ?>" data-billDate="<?php echo $dt; ?>" data-credAdj="<?php echo $data['creditAdjustment']; ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>" data-gst="<?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?>" data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-route="<?php echo $data['routeName']; ?>" style="font-size : 11px;" id="signedCancel" class="identifyingClass btn btn-xs btn-primary waves-effect"><i class="material-icons">cancel</i> 
                                            </button> -->
                                            <input class="checkhour" type="checkbox" name="selValue" onclick="checkForReceive(<?php echo $data['id']; ?>)" value="<?php echo $idAllocated.':'.$data['id'].':'.$no; ?>" id="basic_checkbox_<?php echo $data['id']; ?>" />
                                            <label for="basic_checkbox_<?php echo $data['id']; ?>"></label>
   
                                            <?php }else if($data['isLostBill'] == 1){ ?>
                                            <i class="material-icons">check</i>
                                            <?php } ?>  
                                            </td>
											
                                            <td>
                                                <?php if($data['isLostBill'] == 0){ ?>
                                                    <!-- <button data-toggle="modal" data-target="#billRemarkModal" data-type="basic" data-id="<?php echo $data["id"]; ?>" data-name="<?php echo $idAllocated; ?>" data-id="<?php echo $data['id']; ?>" data-salesman="<?php echo $data['salesman']; ?>" data-billDate="<?php echo $dt; ?>" data-credAdj="<?php echo $data['creditAdjustment']; ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>" data-gst="<?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?>" data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-route="<?php echo $data['routeName']; ?>" style="font-size : 11px;" id="signedCancel" class="identifyingClass btn btn-xs btn-primary waves-effect"><i class="material-icons">cancel</i> 
                                                    </button> -->
                                                    <input class="customchkClose" type="checkbox" name="selValueForNotReceived" value="<?php echo $idAllocated.':'.$data['id'].':'.$no;; ?>" onclick="checkForNotReceive(<?php echo $data['id']; ?>)" id="basic_checkbox_forNotReceived<?php echo $data['id']; ?>" />
                                                    <label for="basic_checkbox_forNotReceived<?php echo $data['id']; ?>"></label> 
                                               
                                                <?php } else if($data['isLostBill'] == 2){ ?>
                                                    <i class="material-icons">cancel</i> 
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <input class="form-control txtinp" disabled type="text" name="textValue[]" value="<?php echo $data['remark']; ?>" id="textbox__forNotReceived<?php echo $data['id']; ?>" />
                                            </td>
                                        </tr>
                                        <?php 
                                                }
                                            }
                                        } 
                                        ?>
                                    
                                    </tbody>
                                </table>
                            </div>

                            <?php   
                                        if(!empty($otherAdj)){
                            ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example DataTable display nowrap" id="example" data-page-length='100'>
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
                                    <tfoot>
                                        <tr>
                                            <th style="width: 15px;">S. No.</th>
                                            <th>Bill No.</th>
                                            <th>Retailer</th>
                                            <th>Salesman</th>
                                            <th>Employee</th>
                                            <th>Other Adjustment Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
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
                                        <td class="CellWithComment"><?php 
                                            $retailerName=substr($data['retailerName'], 0, 20);
                                            echo $retailerName;?>
											<span class="CellComment"><?php echo $result =substr($data['retailerName'],20); ?></span>
                                        </td>
                                        <td><?php echo $data['salesmanName'];?></td>
                                        <td><?php echo $data['ename'];?></td>
                                        <td class="text-right"><?php echo number_format($data['paidAmount']);?></td>
                                        <td>
                                            <input type="text" id="paidAmount<?php echo $data['id'] ?>" style="width:50%" value="<?php echo $amt; ?>" >
                                            <button onclick="statusOtherAdjustment(this,'<?php echo $data['paidAmount']; ?>','<?php echo $data['id']; ?>','<?php echo $data['bid']; ?>','<?php echo $idAllocated;?>');" class="btn btn-primary waves-effect btn-sm">Save</button>
                                        </td>
                                    </tr>
                                    <?php
                                            }
                                        }

                                    ?>
                                      
                                    </tbody>
                                </table>
                            </div>

                            <?php } ?>
                        </div>
                    </div>
                </div>
                 
            </div>

        </div>
    </section>


<div class="container">
  <div class="modal fade" id="billRemarkModal" role="dialog" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <!-- <center><h4 class="modal-title">Lost Bill Remark</h4></center> -->
            <center><h4 id="title_name" style="color:#050A30">Bill Transactions </h4></center></div>
          <div class="modal-body">
              <div class="body">
                  <div class="demo-masked-input">
                      <div class="row clearfix">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <h5 style="color:#000000">Bill No :  <span style="color:#050A30" id='bill_no'></span></h5>
                                    <input type="hidden" id="currentBillNo" autocomplete="off" name="currentBillNo" class="form-control"> 
                                    <input type="hidden" id="currentBillId" autocomplete="off" name="currentBillId" class="form-control"> 
                                     <input type="hidden" id="currentBillRetailer" autocomplete="off" name="currentBillRetailer" class="form-control">    
                                </div> 
                                
                                 <div class="col-md-3">
                                    <h5 style="color:#000000">Bill Date :  <span style="color:#050A30" id='bill-date'></span></h5>
                                </div> 
                                <span id='bill_retailer'></span>
                                <!--<div class="col-md-6">-->
                                <!--    <b>Retailer : </b>-->
                                <!--        <span id='bill_retailer'></span>-->
                                <!--</div> -->
                                <div class="col-md-3">
                                    <h5 style="color:#000000">Pending Amount : <span style="color:#050A30" id='bill_pendingAmt'></span></h5>
                                    <input type="hidden" id="currentPendingAmt" autocomplete="off" name="currentPendingAmt" class="form-control">   
                                </div>
                            </div>

                            <div class="col-md-12">
                               <!--<div class="col-md-3">-->
                               <!--     <b>Bill Date : </b> <span id='bill-date'></span>-->
                               <!-- </div>-->
                                <div class="col-md-3">
                                    <h5 style="color:#000000">Route:  <span style="color:#050A30" id='bill-route'></span></h5>
                                </div>
                                <div class="col-md-3">
                                    <h5 style="color:#000000">Salesman:  <span style="color:#050A30" id='bill-salesman'></span></h5>
                                </div>
                                <div class="col-md-3">
                                    <h5 style="color:#000000">GST No. : 
                                        <span style="color:#050A30" id='gst'></span></h5>
                                </div>
                                <div class="col-md-3"><span style="display:none" class="logo_prov">CN</span></div>
                            </div>
                        </div>
                        <div class="row"><br><br>
                        <form id="frms" method="post" role="form" action="<?php echo site_url('manager/SrCheckController/ChangeManagerStatusForSigned');?>"> 
                            <div class="col-md-12">
                                <input type="hidden" name="billIdForRemark" id="billIdForRemark" class="form-control date">
                                <input type="hidden" name="allocationIdForRemark" id="allocationIdForRemark" class="form-control date">
                                <div class="col-md-12">
                                    <b style="color:#000000">Remark </b>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                             <i class="material-icons">edit</i>
                                        </span>
                                        <div class="form-line">
                                            <input type="text" required name="billRemark" id="billRemark" class="form-control date" placeholder="Enter Remarks for Lost Bill" value="">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                  <div class="row clearfix">
                                      <div class="col-md-12">
                                          <button id="updateBillRemark" class="btn btn-primary m-t-15 waves-effect">
                                              <i class="material-icons">save</i> 
                                              <span class="icon-name">Save</span>
                                          </button>
                                          <button data-dismiss="modal" type="button" class="btn btn-primary m-t-15 waves-effect">
                                              <i class="material-icons">cancel</i> 
                                              <span class="icon-name"> Cancel</span>
                                          </button>
                                      </div>
                                  </div>
                            </div> 
                        </form> 
                        </div>                          
                    </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
  </div>
</div>


<?php $this->load->view('/layouts/footerDataTable'); ?>
<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>

<script type="text/javascript">
    jQuery("#svId").on("click",function(){
        var rowCount = $('#crTbl tr').length;
        var textData=$('#crTbl tr');
       
        if(rowCount<=3){
            var allocatedID=$('#allocatedHid').val();
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('manager/SrCheckController/saveSrCheck');?>",
                data:{"allocationId":allocatedID},
                success: function (data) {
                    parent.history.back();
                }  
            });
        }else{
            alert('Please complete process');
        }
       
       
    });

    function removeMe(t) {
        $(t).closest('tr').remove();
    }
</script>
<script type="text/javascript">
    function signedStatus(e,id,allocatedId){
        
        // if(id){
        //      $.ajax({
        //         type: "POST",
        //         url:"<?php echo site_url('manager/SrCheckController/ChangeManagerStatusForSigned');?>",
        //         data:{"id" : id,"allocatedId" : allocatedId},
        //         success: function (data) {
        //             location.reload(); 
        //         }  
        //     });
        // }

        // $(e).closest('tr').find('#okedit').text('');
    }

    function signedOkStatus(e,id,allocatedId){
        $("#signedOk").attr("disabled", true);
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('manager/SrCheckController/ChangeManagerStatusForSignedOk');?>",
                data:{"id" : id,"allocatedId" : allocatedId},
                success: function (data) {
                    // document.getElementById('err').innerHTML=data;
                    location.reload(); 
                    // parent.history.back();
                    // window.location.href="<?php echo base_url();?>index.php/manager/SrCheckController/LoadSrCheckDetails/"+allocatedId;
                }  
            });
        }

        $(e).closest('tr').find('#okedit').text('');
    }

    // function checkTableDetails(){
    //     if ( $(this).closest("tbody").find("tr").length === 0 ) {
    //         alert("no rows left");

    //     }
    // }
</script>
<script type="text/javascript">
    // $(document).on('click','#signedCancel',function(){
    //   var billId = $(this).data('modalbillId');
    //   var allocationId = $(this).data('modalallocationId');
    //   alert(billId+' '+allocationId);

    //   $('#billIdForRemark').val(billId);
    //   $('#allocationIdForRemark').val(allocationId);
    // });
</script>

<script type="text/javascript">
    $(document).on('click','.identifyingClass',function(){
            $("#billIdForRemark").val('');
            $("#allocationIdForRemark").val('');
            $("#billRemark").val('');

            var billId = $(this).data('id');
            // alert(billId);
            var allocationId = $(this).data('name');
            $("#billIdForRemark").val(billId);
            $("#allocationIdForRemark").val(allocationId);

        // })
    });
</script>

<script type="text/javascript">

// $(document).on('click','#updateBillRemark',function(){
//      $("#updateBillRemark").attr("disabled", true);
// });   

$(document).on('click','#signedCancel',function(){
    var id=$(this).attr('data-id');
    var billNo=$(this).attr('data-billNo');
    var retailerName=$(this).attr('data-retailerName');
    var pendingAmt=$(this).attr('data-pendingAmt');
    pendingAmt=parseInt(pendingAmt); 
    
    // var nf = new Intl.NumberFormat();
    // pendingAmt=nf.format(pendingAmt);
    // pendingAmt=pendingAmt.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
    var gst=$(this).attr('data-gst');
    var route=$(this).attr('data-route');
    
    var credAdj=$(this).attr('data-credAdj');
    credAdj=parseInt(credAdj);
    // alert(credAdj);
    if(credAdj>0){
        // credAdj=nf.format(credAdj);
        $('.logo_prov').text('CN : '+credAdj);
         $(".logo_prov").show();
    }else{
         $(".logo_prov").hide();
    }

    var billDate=$(this).attr('data-billDate');
    var salesman=$(this).attr('data-salesman');
    
    $('#currentPendingAmt').val(pendingAmt);
    $('#currentBillId').val(id);
    $('#currentBillNo').val(billNo);
    $('#currentBillRetailer').val(retailerName);
    $('#bill_no').text(billNo);
    $('#gst').text(gst);
    $('#routeDetail').text(route);
    $('#title_name').text(retailerName);
    // $('#bill_retailer').text(retailerName);
    $('#bill_pendingAmt').text(pendingAmt);
    $('#bill-date').text(billDate);
     $('#bill-salesman').text(salesman);
     $('#bill-route').text(route);
});
</script>

<script type = "text/javascript" >  
    window.history.pushState(null, "", window.location.href);
    window.onpopstate = function () {
        window.history.pushState(null, "", window.location.href);
    };
</script> 

<script type="text/javascript">
    jQuery("#insert-ins").on("click",function(){
        $("#insert-ins").attr("disabled", true);
       
        var allocationNumber=$("#alnum").val();

        var selValue = [];
        $.each($("input[name='selValue']:checked"), function(){
                selValue.push($(this).val());
        });

        var notselValue = [];
        $.each($("input[name='selValueForNotReceived']:checked"), function(){
            notselValue.push($(this).val());
        });

        var textValue = [];
        $.each($("input[name='textValue[]']"), function(){
            if($(this).val() !=''){
                textValue.push($(this).val());
            }
        });

        var textValueForRemark="";
        if(notselValue.length>0){
            for(let i=0;i<notselValue.length;i++){
                if(textValue[i] !=""){
                    textValueForRemark=textValue[i];
                }
            }
        }

        if((typeof textValueForRemark == 'undefined')){
            alert('Enter remark for lost bill');
            $("#insert-ins").attr("disabled", false);
            die();
        }

        if((selValue.length>0) || (notselValue.length>0)){
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('manager/SrCheckController/ChangeManagerStatusForSignedOkWithCheckBox');?>",
                data:{selValue:selValue, notselValue:notselValue, textValue:textValue},
                success: function (data) {
                    // alert(data);die();
                    $("input[type=checkbox]").each(function(){
                        $(this).attr('checked', false);
                    });   
                   
                    window.location.href="<?php echo base_url();?>index.php/manager/SrCheckController/finalSrBillStatus/"+allocationNumber;
                    // location.reload(true);    
                }  
            });
        }else{
            alert('Please select Bills.');
            $("#insert-ins").attr("disabled", false);
        }
});
    

function signedOkStatus(e,id,allocatedId){
       
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('manager/SrCheckController/ChangeManagerStatusForSignedOk');?>",
                data:{"id" : id,"allocatedId" : allocatedId},
                success: function (data) {
                    // document.getElementById('err').innerHTML=data;
                    // location.reload(); 
                    // parent.history.back();
                    // window.location.href="<?php echo base_url();?>index.php/manager/SrCheckController/LoadSrCheckDetails/"+allocatedId;
                }  
            });
        }

        $(e).closest('tr').find('#okedit').text('');
    }
</script>

<script type="text/javascript">
    var clicked = false;
    $(".checkall").on("click", function() {
      $(".checkhour").prop("checked", !clicked);
      clicked = !clicked;
      this.innerHTML = clicked ? 'Deselect' : 'Select';
      $(".txtinp").prop("disabled", true);
    //   $(".checkhourForNotReceived").prop("disabled", true);
    });
</script>

<script type="text/javascript">
    var clicked = false;
    $(".checkallForNotReceived").on("click", function() {
      $(".checkhourForNotReceived").prop("checked", !clicked);
      clicked = !clicked;
      this.innerHTML = clicked ? 'Deselect' : 'Select';

      $(".txtinp").prop("disabled", false);
    //   $(".checkhour").prop("disabled", true);
    });
</script>

<script>
    function checkForNotReceive(id){
        var checker = document.getElementById('basic_checkbox_forNotReceived'+id);
        var sendbtn = document.getElementById('basic_checkbox_'+id);
        var textbox = document.getElementById('textbox__forNotReceived'+id);
        
        checker.onchange = function() {
            sendbtn.disabled = !!this.checked;
            textbox.disabled = !this.checked;
            document.getElementById('textbox__forNotReceived'+id).value='';
        };
    }

    function checkForReceive(id){
        var sendbtn = document.getElementById('basic_checkbox_forNotReceived'+id);
        var checker = document.getElementById('basic_checkbox_'+id);
        var textbox = document.getElementById('textbox__forNotReceived'+id);
        
        checker.onchange = function() {
            sendbtn.disabled = !!this.checked;
            textbox.disabled = !!this.checked;
            document.getElementById('textbox__forNotReceived'+id).value='';
        };
    }
</script>

<script>
    function statusOtherAdjustment(t,oldPayment,billPaymentId,billId,allocationId){
        var paidAmount=$('#paidAmount'+billPaymentId).val();
        var allocationNumber=$("#alnum").val();
       

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
                window.location.href="<?php echo base_url();?>index.php/manager/SrCheckController/finalSrBillStatus/"+allocationNumber;
                // window.parent.location.reload(true);
            }  
        });
    } 
</script>