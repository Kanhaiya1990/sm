<?php $this->load->view('/layouts/commanHeader'); ?>

    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
           
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="flex-div header">
                            <center><h2>Closed Office Allocation Bills</h2>
                               
                            </center>
                             <h4>Office Allocation No.  <span style="color:blue;" id="alDetails"><?php if(!empty($allocationCode)){ echo $allocationCode; }?></span></h4>
                        </div>
                       
                        <div class="body">
                            <div class="row">      
                                <div class="row">
                                     <div class="col-md-12">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered js-basic-example dataTable cust-tbl" id="tbl">
                                                <tr class="head">
                                                    <td colspan="13" style="background-color: whitesmoke;"><center><b>Office Allocations Bills</b></center></td>
                                                </tr>
                                                
                                                <tr class="gray">
                                                    <th>S. No.</th>
                                                    <th style="display: none">S. No.</th>
                                                    <th>Bill No.</th>
                                                    <th>Bill Date</th>
                                                    <th>Retailer Name</th>
                                                    <th>Bill Amount</th>
                                                    <!-- <th>Past Coll</th>
                                                    <th>Past Sr</th>
                                                    <th>Past OA Amount</th> -->
                                                    <th>Pending Amount</th>
                                                    <th>Current OA Amount</th>
                                                    <th>Status</th>
                                                    <th>History</th>
                                                </tr>
                                                <tbody id="result_data">
                                                    <?php if(!empty($officeAllocations)){ 

                                                        $no=0;
                                                        foreach($officeAllocations as $items){
                                                            $no++;
                                                    ?>
                                            <tr id="status-id<?php echo $no; ?>">
                                                <td><?php echo $no; ?></td>
                                                <td style="display: none"><?php echo $items['id']; ?></td>
                                                <td><?php echo $items['billNo']; ?></td>
                                                <td><?php echo $items['date']; ?></td>
                                                <td><?php echo $items['retailerName']; ?></td>
                                                <td><?php echo $items['netAmount']; ?></td>
                                                <!-- <td><?php echo $items['receivedAmt']; ?></td>
                                                <td><?php echo $items['SRAmt']; ?></td>
                                                <td><?php echo $items['officeAdjustmentBillAmount']; ?></td> -->
                                                <td><?php echo $items['pendingAmt']; ?></td>
                                                <td><?php echo $items['a_amount']; ?></td>
                                                <td><?php echo $items['a_type']; ?></td>
                                                <td><a href="<?php echo site_url('AdHocController/billHistoryInfo/'.$items['id']); ?>" class="btn btn-xs history-btn" data-toggle="tooltip" data-placement="bottom" title="View History"><i class="material-icons">info</i></a>
                                                  </td>
                                               
                                            </tr>
                                                    <?php
                                                        }
                                                     } ?>
                                                </tbody>
                                            </table>
                                        </div>
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
            url:"<?php echo site_url('manager/OfficeAllocationController/clearAllBills');?>",
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
            url:"<?php echo site_url('manager/OfficeAllocationController/pendingAllBills');?>",
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
            url:"<?php echo site_url('manager/OfficeAllocationController/clearExistAllBills');?>",
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
            url:"<?php echo site_url('manager/OfficeAllocationController/pendingExistingAllBills');?>",
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
            url:"<?php echo site_url('manager/OfficeAllocationController/fsrExistAllBills');?>",
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
            url:"<?php echo site_url('manager/OfficeAllocationController/CompCurrentBills');?>",
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
            url:"<?php echo site_url('manager/OfficeAllocationController/getCurrentBills');?>",
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
            url:"<?php echo site_url('manager/OfficeAllocationController/getCurrentBillsWithAdditions');?>",
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
                url: "<?php echo site_url('manager/OfficeAllocationController/removeBillIdFromSession');?>",
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
                url: "<?php echo site_url('manager/OfficeAllocationController/deleteBillIdFromSession');?>",
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
                url: "<?php echo site_url('manager/OfficeAllocationController/deleteBillIdFromTable');?>",
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
        var rowCount = $('#tbl tr').length;
        // alert(rowCount);die();
        var cmpName = $('#cmpName').val();
        var remark = $('#remark').val();
        var title = $('#title').val();

        if(cmpName === "" || remark === "" || title === "" ){
            alert('Please enter all details.');
        }else{
            if(rowCount<=3){
                alert('Table is empty. Please add some bills.');
            }else{
                $.ajax({
                    url: "<?php echo site_url('manager/OfficeAllocationController/insertAllocationData');?>",
                    type: "post",
                    data:{"cmpName":cmpName,"remark":remark,"title":title},
                    success: function (response) {
                        $('#result_data').html(response);
                        $('#allClr').prop("disabled", false); 
                        $('#allPnd').prop("disabled", false); 
                         $('#allFsr').prop("disabled", false); 
                        $('#cancelData').prop("disabled", false);

                        $('#svData').prop("disabled", false); 
                        $('#submitData').prop("disabled", false);
                        $('#hideDiv').hide();
                        $('#nextStep').prop("disabled", true); 
                        $('#nextStep').hide();
                    }
                });
            }
        }
    });
</script>

<script type="text/javascript">
    $(document).on('click','#srM',function(){
        var id=$(this).attr('data-id');
        var row_no=$(this).attr('data-no');
        $.ajax({
            url: "<?php echo site_url('manager/OfficeAllocationController/clearedOfficeAllocationBill');?>",
            type: "post",
            data:{"billId" : id,"rowNo":row_no},
            success: function (response) {
                $('.mods').html(response);

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
            url: "<?php echo site_url('manager/OfficeAllocationController/existingClearedOfficeAllocationBill');?>",
            type: "post",
            data:{"billId" : id,"rowNo":row_no,"alId":allocation},
            success: function (response) {
                $('.mods').html(response);

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
            url: "<?php echo site_url('manager/OfficeAllocationController/updatedPendingAmount');?>",
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
            url: "<?php echo site_url('manager/OfficeAllocationController/updatedExistPendingAmount');?>",
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
            url: "<?php echo site_url('manager/OfficeAllocationController/updatedFsrAmount');?>",
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
            url: "<?php echo site_url('manager/OfficeAllocationController/updatedExistFsrAmount');?>",
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
                url: "<?php echo site_url('manager/OfficeAllocationController/updatedClearedAmount');?>",
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
                url: "<?php echo site_url('manager/OfficeAllocationController/updatedExistingClearedAmount');?>",
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
                                window.location.href="<?php echo base_url();?>index.php/AllocationByManagerController/openAllocations";
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
                    url:"<?php echo site_url('manager/OfficeAllocationController/insertOfficeAllocationData');?>",
                    data:{"company":company},
                    success: function (data) {
                        if(data==="Record inserted"){
                            alert(data);
                            window.location.href="<?php echo base_url();?>index.php/AllocationByManagerController/openAllocations";
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
                    url:"<?php echo site_url('manager/OfficeAllocationController/closeCurrentAllocation');?>",
                    data:{"company":company},
                    success: function (data) {
                        if(data==="Record saved"){
                            alert(data);
                            window.location.href="<?php echo base_url();?>index.php/AllocationByManagerController/openAllocations";
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
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('manager/OfficeAllocationController/closeExistCurrentAllocation');?>",
                data:{"alId":allocation},
                success: function (data) {
                    if(data==="Record saved"){
                        alert(data);
                        window.location.href="<?php echo base_url();?>index.php/AllocationByManagerController/openAllocations";
                    }else{
                        alert(data);
                    }
                }  
            });
        }
        
    });
    
</script>

<script type="text/javascript">
    $(document).on('click','#cancelData',function(){
        $.ajax({
        type: "POST",
        url:"<?php echo site_url('manager/OfficeAllocationController/cancelCurrentFullAllocation');?>",
            data:{},
            success: function (data) {
                if(data==="Allocation Deleted."){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/AllocationByManagerController/openAllocations";
                }else{
                    alert(data);
                }
            }  
        });
    });
</script>


<script type="text/javascript">
    $(document).on('click','#cancelExistData',function(){
        var allocation=$('#idOfAllocation').val();
        // alert(allocation);
        $.ajax({
        type: "POST",
        url:"<?php echo site_url('manager/OfficeAllocationController/cancelExistFullAllocation');?>",
            data:{'alId':allocation},
            success: function (data) {
                if(data==="Allocation Deleted."){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/AllocationByManagerController/openAllocations";
                }else{
                    alert(data);
                }
                
            }  
        });
    });
</script>
