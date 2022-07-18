<?php $this->load->view('/layouts/commanHeader'); ?>

<script src="<?php echo base_url('assets/js/pages/ui/tooltips-popovers.js');?>"></script>
<script   src="https://code.jquery.com/jquery-1.12.1.js" integrity="sha256-VuhDpmsr9xiKwvTIHfYWCIQ84US9WqZsLfR4P7qF6O8="   crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
           
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header flex-div">
                            <center><h2>Office Allocation Approval</h2></center><br>
                            <h4>
                                Office Allocation No. :  <span style="color:blue;" id="alDetails"><?php if(!empty($allocationCode)){ echo $allocationCode; }?></span>
                                Title. : <span style="color:blue;" id="alDetails"><?php if(!empty($title)){ echo $title; }?></span>
                                Remark. : <span style="color:blue;" id="alDetails"><?php if(!empty($remark)){ echo $remark; }?></span>
                            </h4>
                        </div>
                       
                        <div class="body">
                            <div class="row">
                            <input type="hidden" name="idOfAllocation" id="idOfAllocation" list="toBill" autocomplete="off" value="<?php if(!empty($allocationId)){ echo $allocationId; }?>" class="form-control">
                                                       
                                <div class="row">
                                     <div class="col-md-12">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered cust-tbl dataTable js-exportable" id="tbl">
                                                <tr class="head">
                                                    <td colspan="13" style="background-color: whitesmoke;"><center><b>Office Allocations Bills</b></center></td>
                                                </tr>
                                                <tr class="head">
                                                    <td colspan="13">
                                    <?php if(!empty($current_allocations)){ ?>                    
                                           <!-- <button type="button" onclick="checkExistClearedInTable();" class="btn btn-success margin btn-sm"> All Cleared </button>   
                                            <button type="button" onclick="checkExistPendingInTable();" class="btn btn-success margin btn-sm"> All Pending </button>  
                                            <button onclick="checkExistFsrInTable();" id="allFsr" type="button" class="btn btn-success margin btn-sm"> All FSR </button> -->

                                    <?php } ?>

                                    <?php if(empty($current_allocations)){ ?>                    
                                        <button type="button" disabled onclick="checkCashInTable();" id="allCash" class="btn btn-primary btnStyle m-t-15 waves-effect"> All Cash </button>
                                        <button type="button" disabled onclick="cancelCheckCashInTable();" id="cancelallCash" class="btn btn-danger m-t-15 waves-effect">Cancel All Cash </button>

                                        <button disabled type="button" id="allClr" onclick="checkClearedInTable();" class="btn btn-primary btnStyle m-t-15 waves-effect"> All Office Adjustment </button>
                                        <button disabled type="button" id="cancelallClr" onclick="cancelCheckClearedInTable();" class="btn btn-danger m-t-15 waves-effect"> Cancel All Office Adjustment </button>      
                                        <button disabled type="button" id="allPnd" onclick="checkPendingInTable();" class="btn btn-primary btnStyle m-t-15 waves-effect"> All Pending </button> 
                                        <button disabled type="button" id="cancelallPnd" onclick="checkCancelPendingInTable();" class="btn btn-danger m-t-15 waves-effect"> Cancel All Pending </button>   
                                        <button disabled onclick="checkFsrInTable();" id="allFsr" type="button" class="btn btn-primary btnStyle m-t-15 waves-effect"> All FSR </button>
                                        <button disabled onclick="cancelCheckFsrInTable();" id="cancelallFsr" type="button" class="btn btn-danger m-t-15 waves-effect">Cancel All FSR </button>
                                           
                                        <?php }else{ ?>
                                        <button onclick="checkExistCashInTable();" type="button" class="btn btn-primary btnStyle m-t-15 waves-effect"> All Cash </button>
                                        <button onclick="cancelCheckExistCashInTable();" type="button" class="btn btn-danger m-t-15 waves-effect">Cancel All Cash </button>
                                    
                                        <button type="button" onclick="checkExistClearedInTable();" class="btn btn-primary btnStyle m-t-15 waves-effect"> All Office Adjustment </button> 
                                        <button type="button" onclick="cancelCheckExistClearedInTable();" class="btn btn-danger m-t-15 waves-effect"> Cancel All Office Adjustment </button>     
                                        <button type="button" onclick="checkExistPendingInTable();" class="btn btn-primary btnStyle m-t-15 waves-effect"> All Pending </button> 
                                        <button type="button" onclick="checkCancelExistPendingInTable();" class="btn btn-danger m-t-15 waves-effect"> Cancel All Pending </button>   
                                        <button onclick="checkExistFsrInTable();" type="button" class="btn btn-primary btnStyle m-t-15 waves-effect"> All FSR </button>
                                        <button onclick="cancelCheckExistFsrInTable();" type="button" class="btn btn-danger m-t-15 waves-effect">Cancel All FSR </button>
                                    <?php } ?> 
                                    </td>
                                        </tr>
                                                <tr class="gray">
                                                    <th>No</th>
                                                    <th style="display: none">No</th>
                                                    <th>Bill No</th>
                                                    <th>Bill Date</th>
                                                    <th class="noSpace">Retailer Name</th>
                                                    <th>Bill</th>
                                                    <th>Coll</th>
                                                    <th>Sr</th>
                                                    <th>Past OA Amount</th>
                                                    <th>Pending</th>
                                                    <th>Current</th>
                                                   <!--  <th>Title</th>
                                                    <th>Remark</th> -->
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                    
                                                </tr>
                                                <tbody id="result_data">
                                                    <?php if(!empty($current_allocations)){ 

                                                        $no=0;
                                                        foreach($current_allocations as $items){
                                                            $no++;
                                                    ?>
                                            <tr id="status-id<?php echo $no; ?>">
                                                <td><?php echo $no; ?></td>
                                                <td style="display: none"><?php echo $items['id']; ?></td>
                                                <td><?php echo $items['billNo']; ?></td>
                                                <td class="noSpace"><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                                              
												<td class="CellWithComment"><?php 
													$retailerName=substr($items['retailerName'], 0, 12);
													echo rtrim($retailerName);?>
													<span class="CellComment"><?php echo $result =substr($items['retailerName'],0); ?></span>
												</td>
                                                <td><?php echo $items['netAmount']; ?></td>
                                                <td><?php echo $items['receivedAmt']; ?></td>
                                                <td><?php echo $items['SRAmt']; ?></td>
                                                <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                                                <td><?php echo $items['pendingAmt']; ?></td>
                                                <td><?php echo $items['a_amount']; ?></td>
                                                 <!-- <td> 
                                                  
                                                    <a href="javascript:void();"  data-trigger="focus" data-container="body" data-toggle="popover" data-placement="left" title="Remark" data-content="<?php echo $items['a_title'] ?>">
                                                        <i class="material-icons">menu</i>
                                                    </a>
                                                </td>
                                                <td>
                                                  
                                                    <a href="javascript:void();"  data-trigger="focus" data-container="body" data-toggle="popover" data-placement="left" title="Remark" data-content="<?php echo $items['a_remark'] ?>">
                                                        <i class="material-icons">menu</i>
                                                    </a>
                                                </td> -->
                                                <td><?php if($items['a_type'] !=="fsr"){ echo $items['a_type']; }else{ echo 'FSR'; } ?></td>
                                                <td class="noSpace">
                                                    <a target="_blank" href="<?php echo site_url('AdHocController/billHistoryInfo/'.$items['id']); ?>" class="btn btn-xs history-btn" data-toggle="tooltip" data-placement="bottom" title="View History"><i class="material-icons">info</i></a>
                                                    <a target="_blank" href="<?php echo site_url('AdHocController/billDetailsInfo/'.$items['id']); ?>" class="btn btn-xs viewBill-btn" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                       
                                                    <!-- <a href="<?php echo site_url('AdHocController/billHistoryInfo/'.$items['id']); ?>" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="bottom" title="View History"><i class="material-icons">info</i></a> -->
                                                    <button id="cashMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cash")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-sm btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                                                    
                                                    <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-sm btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                                    
                        
                                                    <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-sm btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                                                <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                                                    <button  id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-sm btn7 waves-effect" data-type="basic"><span>FSR</span></button>
                                                <?php }else{ ?>
                                                    <button disabled id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-sm btn7 waves-effect" data-type="basic"><span>FSR</span></button>
                                                <?php } ?>
                                                 
                                                    <a>

                                                        <?php if($items['a_type'] ==""){ ?>
                                                        <button onclick="deleteFromTable(this,'<?php echo $items['id'];?>');" class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                                                    <?php }else{ ?>
                                                        <button disabled class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                                                    <?php } ?>
                                                    </a>
                                                </td>
                                            </tr>
                                                    <?php
                                                        }
                                                     } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                    <!-- <div class="col-md-12">
                                        
                                    </div> -->

                                    <div class="col-md-12">
                                         <a href="<?php echo site_url('owner/OfficeAllocationController/openAllocations');?>">
                                            <button type="button" class="btn btn-primary btnStyle m-t-15 waves-effect">
                                                <i class="material-icons">save</i> 
                                                <span class="icon-name"> Save </span>
                                            </button>
                                        </a>  
                                    <?php if(!empty($current_allocations)){ ?> 

                                       
                                        <button id="submitExistData" class="btn btn-primary btnStyle m-t-15 waves-effect">
                                              <i class="material-icons">save</i> 
                                              <span class="icon-name"> Save & Confirm</span>
                                        </button>
                                    <?php } ?>  
                                        
                                    <?php if(!empty($allocationId)){ ?>
                                        <button id="cancelExistData" class="btn btn-sm btn-danger m-t-15 waves-effect">
                                              <i class="material-icons">save</i> 
                                              <span class="icon-name"> Cancel Allocation</span>
                                        </button>
                                    <?php }else{ ?>
                                        <button disabled id="cancelData" class="btn btn-sm btn-danger m-t-15 waves-effect">
                                              <i class="material-icons">save</i> 
                                              <span class="icon-name"> Cancel Allocation</span>
                                        </button>
                                    <?php } ?>   

                                         
                                    </div>
                                </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


<div class="container">
  <div class="modal fade" id="clrModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="mods">

          </div>
      </div>
    </div>
  </div>
</div>

<div class="container">
  <div class="modal fade" id="cashModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="cash-mods">

          </div>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('/layouts/footerDataTable'); ?>
<script type="text/javascript">
    function dropboxCheck(){
         var billOption=document.getElementById('billOption').value;
         if(billOption==="Add To Open Allocation"){
            document.getElementById('blockDiv').style.display="block";
         }else{
            document.getElementById('blockDiv').style.display="none";
         }

         if(billOption==="Ad Hoc Delivery by Employee"){
            document.getElementById('empblockDiv').style.display="block";
            document.getElementById('remarkblockDiv').style.display="block";
         }else{
            document.getElementById('empblockDiv').style.display="none";
            document.getElementById('remarkblockDiv').style.display="none";
         }

         if(billOption==="Office Adjustment Bill"){
            document.getElementById('adjAmtblockDiv').style.display="block";
            document.getElementById('remarkblockDivOffice').style.display="block";
         }else{
            document.getElementById('adjAmtblockDiv').style.display="none";
            document.getElementById('remarkblockDivOffice').style.display="none";
         }
    }
</script>

<script type="text/javascript">
    function checkClearedInTable(){
        var tbl=document.getElementById('tbl');
        var billNo="";
        var rowNo="";
        var billArr=[];
        var rowArr=[];

        for(var i=3;i<tbl.rows.length;i++){
            if(tbl.rows[i].cells.length){
                var rowNo=(tbl.rows[i].cells[0].textContent.trim());
                var status=(tbl.rows[i].cells[11].textContent.trim());
                var billNo=(tbl.rows[i].cells[1].textContent.trim());
                if(!status){
                    billArr.push(billNo);
                    rowArr.push(rowNo);
                }
            }
        }
        if(billArr.length<=0){
            alert('No data in table.Please check');
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('owner/OfficeAllocationController/clearAllBills');?>",
                data:{"billArray" : billArr,"rowArray":rowArr},
                success: function (data) {
                    for (var i = 0; i < rowArr.length; i++) {
                      $('#status-id'+rowArr[i]).replaceWith(data);
                    } 
                    var textLookup={};

                    $('#tbl tbody tr:gt(0)').each(function(){
                       var $row = $(this),
                           targetText = $row.find('td:first').text().trim();
                           if(textLookup[targetText]){
                              $row.remove()
                           }
                           textLookup[targetText]=true; 
                    });
                  // alert(data);
                }  
            });
        }
    }

    function checkPendingInTable(){
        var tbl=document.getElementById('tbl');
        var billNo="";
        var rowNo="";
        var billArr=[];
        var rowArr=[];
        for(var i=3;i<tbl.rows.length;i++){
            if(tbl.rows[i].cells.length){
                var rowNo=(tbl.rows[i].cells[0].textContent.trim());
                var status=(tbl.rows[i].cells[11].textContent.trim());
                var billNo=(tbl.rows[i].cells[1].textContent.trim());
                if(!status){
                    billArr.push(billNo);
                    rowArr.push(rowNo);
                }
            }
        }
        if(billArr.length<=0){
            alert('No data in table.Please check');
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('owner/OfficeAllocationController/pendingAllBills');?>",
                data:{"billArray" : billArr,"rowArray":rowArr},
                success: function (data) {
                    for (var i = 0; i < rowArr.length; i++) {
                      $('#status-id'+rowArr[i]).replaceWith(data);
                    } 
                   

                    var textLookup={};

                    $('#tbl tbody tr:gt(0)').each(function(){
                       var $row = $(this),
                           targetText = $row.find('td:first').text().trim();
                           if(textLookup[targetText]){
                              $row.remove()
                           }
                           textLookup[targetText]=true; 
                    });
                }  
            });
        }
    }

    function checkFsrInTable(){
        var tbl=document.getElementById('tbl');
        var billNo="";
        var rowNo="";
        var billArr=[];
        var rowArr=[];
        for(var i=3;i<tbl.rows.length;i++){
            if(tbl.rows[i].cells.length){
                var rowNo=(tbl.rows[i].cells[0].textContent.trim());
                var status=(tbl.rows[i].cells[11].textContent.trim());
                var billNo=(tbl.rows[i].cells[1].textContent.trim());
                if(!status){
                    billArr.push(billNo);
                    rowArr.push(rowNo);
                }
            }
        }
        if(billArr.length<=0){
            alert('No data in table.Please check');
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('manager/OfficeAllocationController/fsrAllBills');?>",
                data:{"billArray" : billArr,"rowArray":rowArr},
                success: function (data) {
                    for (var i = 0; i < rowArr.length; i++) {
                      $('#status-id'+rowArr[i]).replaceWith(data);
                    } 
                   

                    var textLookup={};

                    $('#tbl tbody tr:gt(0)').each(function(){
                       var $row = $(this),
                           targetText = $row.find('td:first').text().trim();
                           if(textLookup[targetText]){
                              $row.remove()
                           }
                           textLookup[targetText]=true; 
                    });
                }  
            });
        }
    }
</script>


<script type="text/javascript">
    function checkExistClearedInTable(){
         var allocation=document.getElementById('idOfAllocation').value;
        var tbl=document.getElementById('tbl');
        var billNo="";
        var rowNo="";
        var billArr=[];
        var rowArr=[];

        for(var i=3;i<tbl.rows.length;i++){
            if(tbl.rows[i].cells.length){
                var rowNo=(tbl.rows[i].cells[0].textContent.trim());
                var status=(tbl.rows[i].cells[11].textContent.trim());
                var billNo=(tbl.rows[i].cells[1].textContent.trim());
                if(!status){
                    billArr.push(billNo);
                    rowArr.push(rowNo);
                }
            }
        }
        if(billArr.length<=0){
            alert('No data in table.Please check');
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('owner/OfficeAllocationController/clearExistAllBills');?>",
                data:{"billArray" : billArr,"rowArray":rowArr,"alId":allocation},
                success: function (data) {
                    // alert(data);
                    for (var i = 0; i < rowArr.length; i++) {
                      $('#status-id'+rowArr[i]).replaceWith(data);
                    } 
                    var textLookup={};

                    $('#tbl tbody tr:gt(0)').each(function(){
                       var $row = $(this),
                           targetText = $row.find('td:first').text().trim();
                           if(textLookup[targetText]){
                              $row.remove()
                           }
                           textLookup[targetText]=true; 
                    });
                  // alert(data);
                }  
            });
        }
    }

    function checkExistPendingInTable(){
        var allocation=document.getElementById('idOfAllocation').value;
        var tbl=document.getElementById('tbl');
        var billNo="";
        var rowNo="";
        var billArr=[];
        var rowArr=[];
        for(var i=3;i<tbl.rows.length;i++){
            if(tbl.rows[i].cells.length){
                var rowNo=(tbl.rows[i].cells[0].textContent.trim());
                var status=(tbl.rows[i].cells[11].textContent.trim());
                var billNo=(tbl.rows[i].cells[1].textContent.trim());
                if(!status){
                    billArr.push(billNo);
                    rowArr.push(rowNo);
                }
            }
        }
        if(billArr.length<=0){
            alert('No data in table.Please check');
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('owner/OfficeAllocationController/pendingExistingAllBills');?>",
                data:{"billArray" : billArr,"rowArray":rowArr,"alId":allocation},
                success: function (data) {
                    for (var i = 0; i < rowArr.length; i++) {
                      $('#status-id'+rowArr[i]).replaceWith(data);
                    } 
                   

                    var textLookup={};

                    $('#tbl tbody tr:gt(0)').each(function(){
                       var $row = $(this),
                           targetText = $row.find('td:first').text().trim();
                           if(textLookup[targetText]){
                              $row.remove()
                           }
                           textLookup[targetText]=true; 
                    });
                }  
            });
        }
    }

    function checkExistFsrInTable(){
        var allocation=document.getElementById('idOfAllocation').value;
        var tbl=document.getElementById('tbl');
        var billNo="";
        var rowNo="";
        var billArr=[];
        var rowArr=[];

        for(var i=3;i<tbl.rows.length;i++){
            if(tbl.rows[i].cells.length){
                var rowNo=(tbl.rows[i].cells[0].textContent.trim());
                var status=(tbl.rows[i].cells[11].textContent.trim());
                var billNo=(tbl.rows[i].cells[1].textContent.trim());
                if(!status){
                    billArr.push(billNo);
                    rowArr.push(rowNo);
                }
            }
        }
        if(billArr.length<=0){
            alert('No data in table.Please check');
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('owner/OfficeAllocationController/fsrExistAllBills');?>",
                data:{"billArray" : billArr,"rowArray":rowArr,"alId":allocation},
                success: function (data) {
                    // alert(data);
                    for (var i = 0; i < rowArr.length; i++) {
                      $('#status-id'+rowArr[i]).replaceWith(data);
                    } 
                    var textLookup={};

                    $('#tbl tbody tr:gt(0)').each(function(){
                       var $row = $(this),
                           targetText = $row.find('td:first').text().trim();
                           if(textLookup[targetText]){
                              $row.remove()
                           }
                           textLookup[targetText]=true; 
                    });
                  // alert(data);
                }  
            });
        }
    }
</script>

<script type="text/javascript">
   $(document).on('change','#cmpName',function(){
        var cmpName = $('#cmpName').val();
        if(cmpName==""){
            alert("Please enter cmpName");
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('owner/OfficeAllocationController/CompCurrentBills');?>",
                data:{"cmpName" : cmpName},
                success: function (data) {
                  $('#frmBill').html(data);
                  $('#toBill').html(data);
                  $('#addBill').html(data);

                }  
            });
        }
});
</script>

<script type="text/javascript">
    $(document).on('click','#insert-more',function(){
        var from = $('#from').val();
        var to = $('#to').val();
        if(from == "" || to ==""){
            alert("Please enter From/To BillNo");
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('owner/OfficeAllocationController/getCurrentBills');?>",
                data:{"from" : from , "to" : to},
                success: function (data) {
                  $('#result_data').html(data);
                } 
            });
            $('#from').val('');
            $('#to').val('');
        }
    });
</script>

<script type="text/javascript">
    $(document).on('click','#insert-more1',function(){
        var addBill = $('#addExtraBill').val();
        if(addBill==""){
            alert("Please enter BillNo");
        }else{
             $.ajax({
            type: "POST",
            url:"<?php echo site_url('owner/OfficeAllocationController/getCurrentBillsWithAdditions');?>",
                data:{"addBill" : addBill},
                success: function (data) {
                  $('#result_data').html(data);
                }  
            });
                $('#addExtraBill').val('');
        }
});
</script>

<script type="text/javascript">
    function removeMe(that,id) {
        var rmId=id;
        $(that).closest('tr').remove();
        $.ajax({
                url: "<?php echo site_url('owner/OfficeAllocationController/removeBillIdFromSession');?>",
                type: "post",
                data:{"rmId" : rmId},
                success: function (response) {
                    // $('#result_data').html(response);    
                }
        });
    }


    function deleteMe(that,id) {
        var rmId=id;
        $(that).closest('tr').remove();
        $.ajax({
                url: "<?php echo site_url('owner/OfficeAllocationController/deleteBillIdFromSession');?>",
                type: "post",
                data:{"rmId" : rmId},
                success: function (response) {
                    // alert(response);
                    // $('#result_data').html(response);    
                }
        });
    }

    function deleteFromTable(that,id) {
        var rmId=id;
        var allocationId=$('#idOfAllocation').val();

        $(that).closest('tr').remove();
        $.ajax({
                url: "<?php echo site_url('owner/OfficeAllocationController/deleteBillIdFromTable');?>",
                type: "post",
                data:{"rmId" : rmId,"allocationId":allocationId},
                success: function (response) {
                    // alert(response);
                    // $('#result_data').html(response);    
                }
        });
    }
</script>




<script type="text/javascript">
    $(document).on('click','#nextStep',function(){
        var cmpName = $('#cmpName').val();
        $.ajax({
            url: "<?php echo site_url('owner/OfficeAllocationController/insertAllocationData');?>",
            type: "post",
            data:{"cmpName":cmpName},
            success: function (response) {
                $('#result_data').html(response);
                // alert(response);
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).on('click','#srM',function(){
        var id=$(this).attr('data-id');
        var row_no=$(this).attr('data-no');
        $.ajax({
            url: "<?php echo site_url('owner/OfficeAllocationController/clearedOfficeAllocationBill');?>",
            type: "post",
            data:{"billId" : id,"rowNo":row_no},
            success: function (response) {
                $('.mods').html(response);

            }
        });
    });
</script>

<script type="text/javascript">
    $(document).on('click','#cashM',function(){
        var id=$(this).attr('data-id');
        var row_no=$(this).attr('data-no');
        $.ajax({
            url: "<?php echo site_url('owner/OfficeAllocationController/cashOfficeAllocationBill');?>",
            type: "post",
            data:{"billId" : id,"rowNo":row_no},
            success: function (response) {
                $('.cash-mods').html(response);

            }
        });
    });
</script>

<script type="text/javascript">
    $(document).on('click','#srMupdate',function(){
        var id=$(this).attr('data-id');
        var row_no=$(this).attr('data-no');
        var allocation=$('#idOfAllocation').val();
        $.ajax({
            url: "<?php echo site_url('owner/OfficeAllocationController/existingClearedOfficeAllocationBill');?>",
            type: "post",
            data:{"billId" : id,"rowNo":row_no,"alId":allocation},
            success: function (response) {
                $('.mods').html(response);

            }
        });
    });
</script>

<script type="text/javascript">
    $(document).on('click','#cashMupdate',function(){
        var id=$(this).attr('data-id');
        var row_no=$(this).attr('data-no');
        var allocation=$('#idOfAllocation').val();
        $.ajax({
            url: "<?php echo site_url('owner/OfficeAllocationController/existingCashOfficeAllocationBill');?>",
            type: "post",
            data:{"billId" : id,"rowNo":row_no,"alId":allocation},
            success: function (response) {
                $('.cash-mods').html(response);

            }
        });
    });
</script>


<script type="text/javascript">
    $(document).on('click','#pendingM',function(){
        var id=$(this).attr('data-id');
        var row_no=$(this).attr('data-no');
        // alert(row_no+' '+id);
        $.ajax({
            url: "<?php echo site_url('owner/OfficeAllocationController/updatedPendingAmount');?>",
            type: "post",
            data:{"billId" : id,"rowNo":row_no},
            success: function (response) {
                // alert(response);
                $('#status-id'+row_no).replaceWith(response);
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).on('click','#pendingMupdate',function(){
        var id=$(this).attr('data-id');
        var row_no=$(this).attr('data-no');
        var allocation=$('#idOfAllocation').val();
        // alert(row_no+' '+id);
        $.ajax({
            url: "<?php echo site_url('owner/OfficeAllocationController/updatedExistPendingAmount');?>",
            type: "post",
            data:{"billId" : id,"rowNo":row_no,"alId":allocation},
            success: function (response) {
                // alert(response);
                $('#status-id'+row_no).replaceWith(response);
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).on('click','#fsrM',function(){
        var id=$(this).attr('data-id');
        var row_no=$(this).attr('data-no');
        // alert(row_no+' '+id);
        $.ajax({
            url: "<?php echo site_url('owner/OfficeAllocationController/updatedFsrAmount');?>",
            type: "post",
            data:{"billId" : id,"rowNo":row_no},
            success: function (response) {
                // alert(response);
                $('#status-id'+row_no).replaceWith(response);
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).on('click','#fsrMupdate',function(){
        var id=$(this).attr('data-id');
        var row_no=$(this).attr('data-no');
         var allocation=$('#idOfAllocation').val();
        // alert(row_no+' '+id);
        $.ajax({
            url: "<?php echo site_url('owner/OfficeAllocationController/updatedExistFsrAmount');?>",
            type: "post",
            data:{"billId" : id,"rowNo":row_no,"alId":allocation},
            success: function (response) {
                // alert(response);
                $('#status-id'+row_no).replaceWith(response);
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).on('click','#clearSubmit',function(){
        var billId = $('#billId').val();
        var amount = $('#amt').val();
        var pendingAmount = $('#penAmt').val();

        if(parseFloat(amount)>parseFloat(pendingAmount)){
            alert('amount is greater');die();
        }else{
            var rowNo = $('#rowNo').val();
            $.ajax({
                url: "<?php echo site_url('owner/OfficeAllocationController/updatedClearedAmount');?>",
                type: "post",
                data:{"billId" : billId,"amount" : amount,"rowNo":rowNo},
                success: function (response) {
                    $('#status-id'+rowNo).replaceWith(response);
                }
            });
        }
    });
</script>

<script type="text/javascript">
    $(document).on('click','#cashSubmit',function(){
        var billId = $('#billId').val();
        var amount = $('#amt').val();
        var pendingAmount = $('#penAmt').val();

        if(parseFloat(amount)>parseFloat(pendingAmount)){
            alert('amount is greater');die();
        }else{
            var rowNo = $('#rowNo').val();
            $.ajax({
                url: "<?php echo site_url('owner/OfficeAllocationController/updatedCashAmount');?>",
                type: "post",
                data:{"billId" : billId,"amount" : amount,"rowNo":rowNo},
                success: function (response) {
                    $('#status-id'+rowNo).replaceWith(response);
                }
            });
        }
    });
</script>

<script type="text/javascript">
    $(document).on('click','#clearExistingSubmit',function(){
        var billId = $('#billId').val();
        var amount = $('#amt').val();
        var pendingAmount = $('#penAmt').val();
        var allocationId = $('#letAlId').val();

        if(parseFloat(amount)>parseFloat(pendingAmount)){
            alert('amount is greater');die();
        }else{
            var rowNo = $('#rowNo').val();
            $.ajax({
                url: "<?php echo site_url('owner/OfficeAllocationController/updatedExistingClearedAmount');?>",
                type: "post",
                data:{"billId" : billId,"amount" : amount,"rowNo":rowNo,"alId":allocationId},
                success: function (response) {
                    $('#status-id'+rowNo).replaceWith(response);
                }
            });
        }
    });
</script>

<script type="text/javascript">
    $(document).on('click','#cashExistingSubmit',function(){
        var billId = $('#billId').val();
        var amount = $('#amt').val();
        var pendingAmount = $('#penAmt').val();
        var allocationId = $('#letAlId').val();

        if(parseFloat(amount)>parseFloat(pendingAmount)){
            alert('amount is greater');die();
        }else{
            var rowNo = $('#rowNo').val();
            $.ajax({
                url: "<?php echo site_url('owner/OfficeAllocationController/updatedExistingCashAmount');?>",
                type: "post",
                data:{"billId" : billId,"amount" : amount,"rowNo":rowNo,"alId":allocationId},
                success: function (response) {
                    $('#status-id'+rowNo).replaceWith(response);
                }
            });
        }
    });
</script>



<script>
     function numbersonly(myfield, e){
            var key;
            var keychar;
            if (window.event)
                key = window.event.keyCode;
            else if (e)
                key = e.which;
            else
                return true;

            keychar = String.fromCharCode(key);
            // control keys
            if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
                return true;

            // numbers
            else if ((("0123456789").indexOf(keychar) > -1))
                return true;

            // only one decimal point
            else if ((keychar == "."))
            {
                if (myfield.value.indexOf(keychar) > -1)
                    return false;
            }
            else
                return false;
    }
</script>

<script type="text/javascript">
    function checkSerial(){
        var billNo=document.getElementById('billNo').value;
        var retailerName=document.getElementById('retailerName').value;
        var netAmount=document.getElementById('netAmount').value;
        var cmpName=document.getElementById('cmpName').value;
        var billOption=document.getElementById('billOption').value;
        var empDetail=document.getElementById('empDetail').value;

        var remark=document.getElementById('remark').value;
        var remarkOffice=document.getElementById('remarkOffice').value;

        var adjustmentAmount=document.getElementById('adjustmentAmount').value;


        var allocationType=document.getElementById('allocation').value;
        var ids=document.getElementById('billNo_Id').innerText;

            if(billOption==="Add To Open Allocation" && allocationType==="Select Open Allocation"){
                alert('plz select allocation number');
            }else{
                if(billNo !=="" && netAmount !== "" && retailerName !=="" && cmpName !== "" && billOption!=="Select Option"){
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('AdHocController/insertAdhocBill');?>",
                        data:{"empId":empDetail,"remark":remark,"remarkOffice":remarkOffice,"billNo" : billNo,"retailerName" : retailerName,'adjustmentAmount':adjustmentAmount,'netAmount':netAmount,'cmpName':cmpName,"billOption":billOption,"allocationType":allocationType},
                        success: function (data) {
                            if(data==="Record inserted"){
                                alert(data);
                                window.location.href="<?php echo base_url();?>index.php/owner/ExpensesController/allApprovals";
                            }else{
                                alert(data);
                            }
                        }  
                    });
                }else{
                    alert("Please enter all details.");
                }
            }
    }
</script>
<script type="text/javascript">
    function checkBillNo(no)
    {
        var nos=no.value;
        $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('AllocationByManagerController/checkValuesByBillno');?>",
                    data:{"billNo" : nos},
                    success: function (data) {
                        $('#billNo_Id').html('<span style="color: red;">'+data+'</span>');
                    }  
                });
    }
</script>


<script type="text/javascript">
    $(document).on("click","#insert-ins",function() {
        var tbl=document.getElementById('tbl');
        var billNo="";
        var rowNo="";
        var billArr=[];
        for(var i=3;i<tbl.rows.length;i++){
            if(tbl.rows[i].cells.length){
                var rowNo=(tbl.rows[i].cells[0].textContent.trim());
                var status=(tbl.rows[i].cells[11].textContent.trim());
                var billNo=(tbl.rows[i].cells[1].textContent.trim());
                if(!status){
                    billArr.push(billNo);
                }
            }
        }

        var company=$('#cmpName').val();
        if(company !=="--Select Company---"){
            if(billArr.length!=0){
                alert('Please make bills operations.');
            }else{
                $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('owner/OfficeAllocationController/insertOfficeAllocationData');?>",
                    data:{"company":company},
                    success: function (data) {
                        if(data==="Record inserted"){
                            alert(data);
                            window.location.href="<?php echo base_url();?>index.php/owner/ExpensesController/allApprovals";
                        }else{
                            alert(data);
                        }
                    }  
                });
            }
        }else{
            alert('Please select company');
        }
    });
    
</script>


<script type="text/javascript">
    $(document).on("click","#submitData",function() {
        var tbl=document.getElementById('tbl');
        var billNo="";
        var rowNo="";
        var billArr=[];
        for(var i=3;i<tbl.rows.length;i++){
            if(tbl.rows[i].cells.length){
                var rowNo=(tbl.rows[i].cells[0].textContent.trim());
                var status=(tbl.rows[i].cells[11].textContent.trim());
                var billNo=(tbl.rows[i].cells[1].textContent.trim());
                if(!status){
                    billArr.push(billNo);
                }
            }
        }

        var company=$('#cmpName').val();
        if(company !=="--Select Company---"){
            if(billArr.length!=0){
                alert('Please make bills operations.');
            }else{
                $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('owner/OfficeAllocationController/closeCurrentAllocation');?>",
                    data:{"company":company},
                    success: function (data) {
                        if(data==="Record saved"){
                            alert(data);
                            window.location.href="<?php echo base_url();?>index.php/owner/ExpensesController/allApprovals";
                        }else{
                            alert(data);
                        }
                    }  
                });
            }
        }else{
            alert('Please select company');
        }
    });
    
</script>

<script type="text/javascript">
    $(document).on("click","#submitExistData",function() {
        $("#submitExistData").attr("disabled", true);
        var allocation=document.getElementById('idOfAllocation').value;

        var tbl=document.getElementById('tbl');
        var billNo="";
        var rowNo="";
        var billArr=[];
        for(var i=3;i<tbl.rows.length;i++){
            if(tbl.rows[i].cells.length){
                var rowNo=(tbl.rows[i].cells[0].textContent.trim());
                var status=(tbl.rows[i].cells[11].textContent.trim());
                var billNo=(tbl.rows[i].cells[1].textContent.trim());
                if(!status){
                    billArr.push(billNo);
                }
            }
        }

        
        if(billArr.length!=0){
            alert('Please make bills operations.');
            $("#submitExistData").attr("disabled", false);
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/OfficeAllocationController/closeOwnerCurrentAllocation');?>",
                data:{"alId":allocation},
                success: function (data) {
                    if(data==="Record saved"){
                        alert(data);
                        window.location.href="<?php echo base_url();?>index.php/owner/ExpensesController/allApprovals";
                    }else{
                        alert(data);
                        $("#submitExistData").attr("disabled", false);
                    }
                }  
            });
        }
        
    });
    
</script>

<script type="text/javascript">
    $(document).on('click','#cancelData',function(){
        $("#cancelData").attr("disabled", true);
        $.ajax({
        type: "POST",
        url:"<?php echo site_url('owner/OfficeAllocationController/cancelCurrentFullAllocation');?>",
            data:{},
            success: function (data) {
                if(data==="Allocation Deleted."){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/owner/ExpensesController/allApprovals";
                }else{
                    alert(data);
                    $("#cancelData").attr("disabled", false);
                }
            }  
        });
    });
</script>


<script type="text/javascript">
    $(document).on('click','#cancelExistData',function(){
        $("#cancelExistData").attr("disabled", true);
        var allocation=$('#idOfAllocation').val();
        // alert(allocation);
        $.ajax({
        type: "POST",
        url:"<?php echo site_url('owner/OfficeAllocationController/cancelExistFullAllocation');?>",
            data:{'alId':allocation},
            success: function (data) {
                if(data==="Allocation Deleted."){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/owner/ExpensesController/allApprovals";
                }else{
                    alert(data);
                    $("#cancelExistData").attr("disabled", false);
                }
                
            }  
        });
    });
</script>

<script>
    function checkExistCashInTable(){
        var allocation=document.getElementById('idOfAllocation').value;
        var tbl=document.getElementById('tbl');
        var billNo="";
        var rowNo="";
        var billArr=[];
        var rowArr=[];

        for(var i=3;i<tbl.rows.length;i++){
            if(tbl.rows[i].cells.length){
                var rowNo=(tbl.rows[i].cells[0].textContent.trim());
                var status=(tbl.rows[i].cells[11].textContent.trim());
                var billNo=(tbl.rows[i].cells[1].textContent.trim());
                if(!status){
                    billArr.push(billNo);
                    rowArr.push(rowNo);
                }
            }
        }
        if(billArr.length<=0){
            alert('No data in table.Please check');
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('owner/OfficeAllocationController/cashExistAllBills');?>",
                data:{"billArray" : billArr,"rowArray":rowArr,"alId":allocation},
                success: function (data) {
                    // alert(data);
                    for (var i = 0; i < rowArr.length; i++) {
                      $('#status-id'+rowArr[i]).replaceWith(data);
                    } 
                    var textLookup={};

                    $('#tbl tbody tr:gt(0)').each(function(){
                       var $row = $(this),
                           targetText = $row.find('td:first').text().trim();
                           if(textLookup[targetText]){
                              $row.remove()
                           }
                           textLookup[targetText]=true; 
                    });
                  // alert(data);
                }  
            });
        }
    }

    function cancelCheckExistCashInTable(){
        var allocation=document.getElementById('idOfAllocation').value;
        var tbl=document.getElementById('tbl');
        var billNo="";
        var rowNo="";
        var billArr=[];
        var rowArr=[];

        for(var i=3;i<tbl.rows.length;i++){
            if(tbl.rows[i].cells.length){
                var rowNo=(tbl.rows[i].cells[0].textContent.trim());
                var status=(tbl.rows[i].cells[11].textContent.trim());
                var billNo=(tbl.rows[i].cells[1].textContent.trim());
                if(status){
                    billArr.push(billNo);
                    rowArr.push(rowNo);
                }
            }
        }
        if(billArr.length<=0){
            alert('sNo data in table.Please check');
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('owner/OfficeAllocationController/cancelCashExistAllBills');?>",
                data:{"billArray" : billArr,"rowArray":rowArr,"alId":allocation},
                success: function (data) {
                    // alert(data);
                    for (var i = 0; i < rowArr.length; i++) {
                      $('#status-id'+rowArr[i]).replaceWith(data);
                    } 
                    var textLookup={};

                    $('#tbl tbody tr:gt(0)').each(function(){
                       var $row = $(this),
                           targetText = $row.find('td:first').text().trim();
                           if(textLookup[targetText]){
                              $row.remove()
                           }
                           textLookup[targetText]=true; 
                    });
                  // alert(data);
                }  
            });
        }
    }

    function cancelCheckExistClearedInTable(){
         var allocation=document.getElementById('idOfAllocation').value;
        var tbl=document.getElementById('tbl');
        var billNo="";
        var rowNo="";
        var billArr=[];
        var rowArr=[];

        for(var i=3;i<tbl.rows.length;i++){
            if(tbl.rows[i].cells.length){
                var rowNo=(tbl.rows[i].cells[0].textContent.trim());
                var status=(tbl.rows[i].cells[11].textContent.trim());
                var billNo=(tbl.rows[i].cells[1].textContent.trim());
                if(status){
                    billArr.push(billNo);
                    rowArr.push(rowNo);
                }
            }
        }
        if(billArr.length<=0){
            alert('No data in table.Please check');
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('owner/OfficeAllocationController/cancelClearExistAllBills');?>",
                data:{"billArray" : billArr,"rowArray":rowArr,"alId":allocation},
                success: function (data) {
                    // alert(data);
                    for (var i = 0; i < rowArr.length; i++) {
                      $('#status-id'+rowArr[i]).replaceWith(data);
                    } 
                    var textLookup={};

                    $('#tbl tbody tr:gt(0)').each(function(){
                       var $row = $(this),
                           targetText = $row.find('td:first').text().trim();
                           if(textLookup[targetText]){
                              $row.remove()
                           }
                           textLookup[targetText]=true; 
                    });
                  // alert(data);
                }  
            });
        }
    }

    function checkCancelExistPendingInTable(){
        var allocation=document.getElementById('idOfAllocation').value;
        var tbl=document.getElementById('tbl');
        var billNo="";
        var rowNo="";
        var billArr=[];
        var rowArr=[];
        for(var i=3;i<tbl.rows.length;i++){
            if(tbl.rows[i].cells.length){
                var rowNo=(tbl.rows[i].cells[0].textContent.trim());
                var status=(tbl.rows[i].cells[11].textContent.trim());
                var billNo=(tbl.rows[i].cells[1].textContent.trim());
                if(status){
                    billArr.push(billNo);
                    rowArr.push(rowNo);
                }
            }
        }
        if(billArr.length<=0){
            alert('No data in table.Please check');
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('owner/OfficeAllocationController/cancelPendingExistingAllBills');?>",
                data:{"billArray" : billArr,"rowArray":rowArr,"alId":allocation},
                success: function (data) {
                    for (var i = 0; i < rowArr.length; i++) {
                      $('#status-id'+rowArr[i]).replaceWith(data);
                    } 
                   

                    var textLookup={};

                    $('#tbl tbody tr:gt(0)').each(function(){
                       var $row = $(this),
                           targetText = $row.find('td:first').text().trim();
                           if(textLookup[targetText]){
                              $row.remove()
                           }
                           textLookup[targetText]=true; 
                    });
                }  
            });
        }
    }

    function cancelCheckExistFsrInTable(){
        var allocation=document.getElementById('idOfAllocation').value;
        var tbl=document.getElementById('tbl');
        var billNo="";
        var rowNo="";
        var billArr=[];
        var rowArr=[];

        for(var i=3;i<tbl.rows.length;i++){
            if(tbl.rows[i].cells.length){
                var rowNo=(tbl.rows[i].cells[0].textContent.trim());
                var status=(tbl.rows[i].cells[11].textContent.trim());
                var billNo=(tbl.rows[i].cells[1].textContent.trim());
                if(status){
                    billArr.push(billNo);
                    rowArr.push(rowNo);
                }
            }
        }
        if(billArr.length<=0){
            alert('No data in table.Please check');
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('owner/OfficeAllocationController/cancelFsrExistAllBills');?>",
                data:{"billArray" : billArr,"rowArray":rowArr,"alId":allocation},
                success: function (data) {
                    // alert(data);
                    for (var i = 0; i < rowArr.length; i++) {
                      $('#status-id'+rowArr[i]).replaceWith(data);
                    } 
                    var textLookup={};

                    $('#tbl tbody tr:gt(0)').each(function(){
                       var $row = $(this),
                           targetText = $row.find('td:first').text().trim();
                           if(textLookup[targetText]){
                              $row.remove()
                           }
                           textLookup[targetText]=true; 
                    });
                  // alert(data);
                }  
            });
        }
    }
</script>