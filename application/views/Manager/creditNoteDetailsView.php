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
</style>
<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
<section class="col-md-12 box">
    <div class="container-fluid">
        <div class="row clearfix" id="page">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2 class="text-center">Bill Journal Entry</h2>
                    </div>
                    <div class="body">
                        <div class="row">                                 
                            <div class="row m-t-20">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-xs-center"><center>Select Option</center></th>
                                                <th class="text-xs-center"><center>Select Allocation / Company</center></th>
                                                <th class="text-xs-center"><center>From Bills</center></th>
                                                <th class="text-xs-center"><center>To Bills</center></th>
                                                <th class="text-xs-center"><center>Maximum Amount</center></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-xs-right">
                                                    <input name="radioType" value="allocation" type="radio" id="radio_1" checked/>
                                                    <label for="radio_1">Allocation</label><br>
                                                    <input name="radioType" value="company" type="radio" id="radio_2" />
                                                    <label for="radio_2">Company</label>
                                                </td>
                                                <td class="text-xs-right" id="allocationDiv">
                                                
                                                    <select name="company" class="form-control" id="allocationsList" required>
                                                        <option value=''>--Select Allocation--</option>
                                                        <?php 
                                                            $no=0;
                                                            foreach($allocations as $item){
                                                            ?>
                                                                <option value='<?php echo $item['id'];?>'><?php echo $item['allocationCode'].' : '.$item['allocationRouteName'];?></option>
                                                            <?php
                                                                $no++;
                                                            } 
                                                            ?>
                                                    </select>
                                                </td>

                                                <td class="text-xs-right" id="cmpDiv" style="display:none;">
                                                    <select name="company" class="form-control" id="excelcompany" required>
                                                        <option value=''>--Select Company--</option>
                                                        <?php 
                                                            $no=0;
                                                            foreach($company as $item){
                                                            ?>
                                                                <option value='<?php echo $item['name'];?>'><?php echo $item['name'];?></option>
                                                            <?php
                                                                $no++;
                                                            } 
                                                            ?>
                                                    </select>
                                                </td>
                                                <td class="text-xs-right">
                                                    <input type="text" class="form-control" id="from_Bills" name="from_Bills" placeholder="From Bills" list="frmBill" value="<?php if(!empty($from)){ echo $from; } ?>" required >
                                                    <datalist id="frmBill">
                                                        
                                                    </datalist>
                                                </td>
                                                <td class="text-xs-right">
                                                    <input type="text" class="form-control" id="to_Bills" name="to_Bills" placeholder="To Bills" list="toBill" value="<?php if(!empty($to)){ echo $to; } ?>" required>
                                                    <datalist id="toBill">
                                                        
                                                    </datalist>
                                                </td>
                                                <td class="text-xs-right">
                                                    <input type="text" class="form-control" id="minAmount" name="minAmount" placeholder="Minimum amount" required >
                                                </td>
                                                <td class="text-xs-right">
                                                    <button id="submitBtn" class="btn btn-primary">Add</button>
                                                </td>
                                                
                                            </tr>
                                        
                                        </tbody>
                                        <thead>
                                            <tr>
                                                <td class="text-xs-center"><b>Remark</b></td>
                                                <td colspan="2">
                                                    <input type="text" class="form-control" id="remarkForAll" name="remarkForAll" placeholder="Remark">
                                                </td>
                                                <td class="text-xs-center"><b>Manual Bills</b></td>
                                                <td class="text-xs-right">
                                                    <input type="text" class="form-control" id="manual_Bills" name="manual_Bills" placeholder="Manual Bills" list="manualBill" value="<?php if(!empty($from)){ echo $from; } ?>" required >
                                                    <datalist id="manualBill">
                                                        
                                                    </datalist>
                                                </td>
                                                <td class="text-xs-right">
                                                    <button id="manualBillCreditJournal" class="btn btn-primary btn-sm waves-effect">
                                                        <span class="icon-name">Add</span>
                                                    </button>
                                                </td>
                                                
                                            </tr>
                                        </thead>
                                    </table>
                                   
                                    
                                </div>

                                <div class="col-md-12 table-responsive">
                                    <table id="tblBillAdd" style="font-size: 12px" class="table table-bordered table-striped table-hover" data-page-length='100'>
                                        <thead>
                                            <tr>
                                                <th colspan="9" class="text-center">
                                                    <span style="color:blue"> Credit Entry Bills </span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr class="gray">
                                                <th> Bill No</th>
                                                <th> Bill Date</th>
                                                <th> Retailer</th>
                                                <th> Net Amount</th>
                                                <th> SR Amount</th>
                                                <th> Collection Amount</th>
                                                <th> Pending Amount</th>
                                                <th>  Amount</th>
                                                <th> Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyData">

                                        </tbody>
                                    </table>

                                    
                                    
                                    <div class="col-md-6">
                                        <table style="font-size: 12px" id="tblAllocationDetail" style="display:none" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Allocation</th>
                                                    <th>Salesman</th>
                                                    <th>Employee</th>
                                                    <th>Route</th>
                                                </tr>
                                            </thead>
                                            <tbody id="alData">
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-4">
                                        <table style="font-size: 12px" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Bill Count.</th>
                                                    <th>Bills Credit Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td id="cntchk"></td>
                                                    <td id="TotalInvoiceAmt"><h6></h6></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="header">
                        <h2 class="text-center">
                            Debit Bills / Employees
                        </h2>
                    </div>
                    <div class="body">
                        <div class="row"> 
                                                              
                            <div class="row m-t-20">
                                <div class="col-md-12 table-responsive">
                                    <div class="col-sm-4 hideDiv" style="display:none">
                                        <input type="text" class="form-control" id="addBillText" name="addBillText" placeholder="Select Bills" list="addBillTextList" required >
                                        <datalist id="addBillTextList"> </datalist>
                                    </div>     

                                    <div class="col-sm-4 hideDiv" style="display:none">
                                        <button id="addbillJournal" class="btn btn-sm btn-primary">Add</button>
                                    </div>

                                    <div class="col-sm-4 hideDiv" style="display:none">
                                        <table style="font-size: 12px" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Pending Amount Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td id="TotalPendingAmt"><h6></h6></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- <div class="col-sm-4 hideDiv" style="display:none"> -->

                                    <table id="billTbl" style="font-size: 12px" class="table table-bordered table-striped table-hover" data-page-length='100'>
                                        <thead>
                                            <tr>
                                                <th colspan="8" class="text-center">
                                                <span  style="color:blue">Debit Bills Entry </span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr class="gray">
                                                <th> Bill No</th>
                                                <th> Bill Date</th>
                                                <th> Retailer</th>
                                                <th> Net Amount</th>
                                                <th> Pending Amount</th>
                                                <th> Amount</th>
                                                <th> Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyForBillJournalData">

                                        </tbody>
                                    </table>

                                   
                                    <div class="col-sm-4 hideEmpDiv" style="display:none">
                                        <input type="text" class="form-control" id="addEmpText" name="addEmpText" placeholder="Select Employee" list="addEmpTextList" required >
                                        <datalist id="addEmpTextList"> 
                                            <?php  foreach($emp as $item){
                                                ?>   
                                                    <option id="<?php echo $item['id'] ?>" value="<?php echo $item['name']; ?>" />
                                            
                                            <?php } ?>
                                        </datalist>
                                    </div>     

                                    <div class="col-sm-4 hideEmpDiv" style="display:none">
                                        <button id="addEmpJournal" class="btn btn-sm btn-primary">Add</button>
                                    </div>
                                        
                                    
                                    <table id="empTbl" style="font-size: 12px" class="table table-bordered table-striped table-hover" data-page-length='100'>
                                        <thead>
                                            <tr>
                                                <th colspan="8" class="text-center">
                                                    <span style="color:blue">Debit Employees </span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr class="gray">
                                                <th> Employee</th>
                                                <th> Current Debit</th>
                                                <th> Amount</th>
                                                <th> Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyForEmployeeJournalData">

                                        </tbody>
                                    </table>
                                
                                    <button id="saveBillJournalBtn" class="btn btn-primary btn-sm m-t-20 waves-effect">
                                        <span class="icon-name">Save</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $this->load->view('/layouts/footerDataTable'); ?>

<script type="text/javascript">
    $(document).on('click','#radio_1',function(){
        $('#allocationDiv').css('display','block');
        $('#tblAllocationDetail').css('display','block');
        $('#cmpDiv').css('display','none');
        
    });
</script>

<script type="text/javascript">
    $(document).on('click','#radio_2',function(){
        $('#allocationDiv').css('display','none');
        $('#tblAllocationDetail').css('display','none');
        $('#cmpDiv').css('display','block');
    });
</script>

<script type="text/javascript">
    $(document).on('change','#allocationsList',function(){
        var allocationId = $('#allocationsList').val();
        $('#frmBill').html('');
        $('#toBill').html('');
        $('#manualBill').html('');
        $('#addBillTextList').html('');

        $('#alData').html('');

        $('#from_Bills').val('');
        $('#to_Bills').val('');
        $('#manual_Bills').val('');

        if(allocationId==""){
            alert("Please select allocation");
        }else{
            $.ajax({
                url : "<?php echo site_url('AdHocController/getBillsByCompForCreditNote');?>",
                method : "POST",
                data : {allocationId: allocationId},
                success: function(data){
                    $('#frmBill').html(data);
                    $('#toBill').html(data);
                    $('#manualBill').html(data);
                    $('#addBillTextList').html(data);
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
    });

    $(document).on('change','#allocationsList',function(){
        var allocationId = $('#allocationsList').val();
        $('#alData').html('');
      

        if(allocationId==""){
            alert("Please select allocation");
        }else{
            $.ajax({
                url : "<?php echo site_url('AdHocController/getDetailForAllocation');?>",
                method : "POST",
                data : {allocationId: allocationId},
                success: function(data){
                    $('#alData').html(data);
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
    });
</script>

<script type="text/javascript">
    $(document).on('change','#excelcompany',function(){
        var comp = $('#excelcompany').val();
        $('#frmBill').html('');
        $('#toBill').html('');
        $('#manualBill').html('');
        $('#addBillTextList').html('');

        $('#from_Bills').val('');
        $('#to_Bills').val('');
        $('#manual_Bills').val('');
        
        if(comp==""){
            alert("Please select company");
        }else{
            $.ajax({
                url : "<?php echo site_url('AdHocController/getBillsByComp');?>",
                method : "POST",
                data : {comp: comp},
                success: function(data){
                    $('#frmBill').html(data);
                    $('#toBill').html(data);
                    $('#manualBill').html(data);
                    $('#addBillTextList').html(data);
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
    });
</script>

<script type="text/javascript">
    $(document).on('click','#submitBtn',function(){
        var allocationId = $('#allocationsList').val();
        var comp = $('#excelcompany').val();
        var fromBill = $('#from_Bills').val();
        var toBill = $('#to_Bills').val();

        var radioType=$('input[name="radioType"]:checked').val();

        var minAmount = $('#minAmount').val();
        if(minAmount==""){
            alert("Please enter minimum amount...");
            die();
        }

        var frmBillId = $('#frmBill').find('option[value="'+fromBill+'"]').attr('id');
        var toBillId = $('#toBill').find('option[value="'+toBill+'"]').attr('id');

        if (typeof frmBillId === "undefined") {
            alert("Please select correct billno...");
            die();
        }

        if (typeof toBillId === "undefined") {
            alert("Please select correct billno...");
            die();
        }

        if(radioType == "allocation"){
            if (allocationId=== "") {
                alert("Please select allocation...");
                die();
            }
            
            if(allocationId=="" || frmBillId=="" || toBillId==""){
                alert("Please select data...");
                die();
            }else{
                
                $.ajax({
                    url : "<?php echo site_url('AdHocController/getAllBillsForBillJournalDebitNoteByAllocation');?>",
                    method : "POST",
                    data : {allocationId: allocationId,fromBill:frmBillId,toBill:toBillId,minAmount:minAmount},
                    success: function(data){
                        // alert(data);
                        $('#tbodyData').append(data);
                        // $('#billJournal').prop('disabled',false);
                        // $('#retailerJournal').prop('disabled',false);
                        $('#from_Bills').val('');
                        $('#to_Bills').val('');

                        $('input[name=radioType]').attr("disabled",true);
                        $('#allocationsList').attr("disabled",true);
                        $('#excelcompany').attr("disabled",true);

                        var seen = {};
                        $('#tblBillAdd tbody tr').each(function() {
                            var txt = $(this).text();
                            if (seen[txt]){
                                $(this).remove();
                            }else{
                                seen[txt] = true;
                            }
                        });

                        var total = 0;
                        var cnt=0;
                        
                        $(".wagein").each(function () {
                            var value = $(this).text();
                            if (!isNaN(value) && value.length != 0) {
                                total += parseFloat(value);
                                cnt++;
                            }
                        });

                        if(total>0){
                            $('#TotalInvoiceAmt').text(total);
                            $('#cntchk').text(cnt);
                        }else{
                            $('#TotalInvoiceAmt').text(total);
                            $('#cntchk').text(cnt);
                        }
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
        }else{
            if (comp=== "") {
                alert("Please select company...");
                die();
            }
            
            if(comp=="" || frmBillId=="" || toBillId==""){
                alert("Please select data...");
                die();
            }else{
                
                $.ajax({
                    url : "<?php echo site_url('AdHocController/getAllBillsForBillJournalDebitNoteByCompany');?>",
                    method : "POST",
                    data : {comp: comp,fromBill:frmBillId,toBill:toBillId,minAmount:minAmount},
                    success: function(data){
                        $('#tbodyData').append(data);
                        // $('#billJournal').prop('disabled',false);
                        // $('#retailerJournal').prop('disabled',false);
                        $('#from_Bills').val('');
                        $('#to_Bills').val('');

                        $('input[name=radioType]').attr("disabled",true);
                        $('#allocationsList').attr("disabled",true);
                        $('#excelcompany').attr("disabled",true);

                        var seen = {};
                        $('#tblBillAdd tbody tr').each(function() {
                            var txt = $(this).text();
                            if (seen[txt]){
                                $(this).remove();
                            }else{
                                seen[txt] = true;
                            }
                        });

                        var total = 0;
                        var cnt=0;
                        
                        $(".wagein").each(function () {
                            var value = $(this).text();
                            if (!isNaN(value) && value.length != 0) {
                                total += parseFloat(value);
                                cnt++;
                            }
                        });

                        if(total>0){
                            $('#TotalInvoiceAmt').text(total);
                            $('#cntchk').text(cnt);
                        }else{
                            $('#TotalInvoiceAmt').text(total);
                            $('#cntchk').text(cnt);
                        }
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
        }
    });
</script>

<script type="text/javascript">
    $(document).on('click','#manualBillCreditJournal',function(){
        var comp = $('#excelcompany').val();
        var allocationId = $('#allocationsList').val();
        var manual = $('#manual_Bills').val();
        var manualBillId = $('#manualBill').find('option[value="'+manual+'"]').attr('id');

        var radioType=$('input[name="radioType"]:checked').val();

        if (typeof manualBillId === "undefined") {
            alert("Please select correct billno...");
            die();
        }
        
        if(radioType == "allocation"){
            if (allocationId=== "") {
                alert("Please select allocation...");
                die();
            }

            if(manualBillId==""){
                alert("Please select data...");
                die();
            }else{
                $.ajax({
                    url : "<?php echo site_url('AdHocController/getAllBillsForManualJournalCreditNote');?>",
                    method : "POST",
                    data : {allocationId:allocationId,manualBillId:manualBillId},
                    success: function(data){
                        $('#tbodyData').append(data);
                        // $('#billJournal').prop('disabled',false);
                        // $('#retailerJournal').prop('disabled',false);
                        $('#manual_Bills').val('');

                        $('input[name=radioType]').attr("disabled",true);
                        $('#allocationsList').attr("disabled",true);
                        $('#excelcompany').attr("disabled",true);

                        var seen = {};
                        $('#tblBillAdd tbody tr').each(function() {
                            var txt = $(this).text();
                            if (seen[txt]){
                                $(this).remove();
                            }else{
                                seen[txt] = true;
                            }
                        });

                        var total = 0;
                        var cnt=0;
                        
                        $(".wagein").each(function () {
                            var value = $(this).text();
                            if (!isNaN(value) && value.length != 0) {
                                total += parseFloat(value);
                                cnt++;
                            }
                        });

                        if(total>0){
                            $('#TotalInvoiceAmt').text(total);
                            $('#cntchk').text(cnt);
                        }else{
                            $('#TotalInvoiceAmt').text(total);
                            $('#cntchk').text(cnt);
                        }
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
        }else{
            if (comp=== "") {
                alert("Please select company...");
                die();
            }
            
            if(comp=="" || manualBillId==""){
                alert("Please select data...");
                die();
            }else{
                $.ajax({
                    url : "<?php echo site_url('AdHocController/getAllBillsForManualJournalCreditNote');?>",
                    method : "POST",
                    data : {allocationId:allocationId,manualBillId:manualBillId},
                    success: function(data){
                        $('#tbodyData').append(data);
                        // $('#billJournal').prop('disabled',false);
                        // $('#retailerJournal').prop('disabled',false);
                        $('#manual_Bills').val('');

                        $('input[name=radioType]').attr("disabled",true);
                        $('#allocationsList').attr("disabled",true);
                        $('#excelcompany').attr("disabled",true);

                        var seen = {};
                        $('#tblBillAdd tbody tr').each(function() {
                            var txt = $(this).text();
                            if (seen[txt]){
                                $(this).remove();
                            }else{
                                seen[txt] = true;
                            }
                        });

                        var total = 0;
                        var cnt=0;
                        
                        $(".wagein").each(function () {
                            var value = $(this).text();
                            if (!isNaN(value) && value.length != 0) {
                                total += parseFloat(value);
                                cnt++;
                            }
                        });

                        if(total>0){
                            $('#TotalInvoiceAmt').text(total);
                            $('#cntchk').text(cnt);
                        }else{
                            $('#TotalInvoiceAmt').text(total);
                            $('#cntchk').text(cnt);
                        }
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
        }
        
    });
</script>


<script type="text/javascript">
    function removeMe(that,id) {
        var rmId=id;
        $(that).closest('tr').remove();

        var total = 0;
        var cnt=0;
        
        $(".wagein").each(function () {
            var value = $(this).text();
            if (!isNaN(value) && value.length != 0) {
                total += parseFloat(value);
                cnt++;
            }
        });

        if(total>0){
            $('#TotalInvoiceAmt').text(total);
            $('#cntchk').text(cnt);
        }else{
            $('#TotalInvoiceAmt').text(total);
            $('#cntchk').text(cnt);
        }
    }

    function numbersonly(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if ((charCode < 48 || charCode > 57) ) {
          return false;
      }
      return true;
    }
</script>

<script type="text/javascript">
    $(document).on('click','#submitBtn',function(){
        $('.hideDiv').css('display','block');
        $('.hideEmpDiv').css('display','block');
        // submitBtn
    });

    $(document).on('click','#manualBillCreditJournal',function(){
        $('.hideDiv').css('display','block');
        $('.hideEmpDiv').css('display','block');
        // submitBtn
    });

    // $(document).on('click','#retailerJournal',function(){
       
    // });
</script>    

<script>
    $(document).on('click','#addbillJournal',function(){
        var bill = $('#addBillText').val();
        var billId = $('#addBillTextList').find('option[value="'+bill+'"]').attr('id');
        // alert(billId);

        $.ajax({
            url : "<?php echo site_url('AdHocController/addRowForBillJournal');?>",
            method : "POST",
            data : {billId:billId},
            success: function(data){
                $('#tbodyForBillJournalData').append(data);
                $('#addBillText').val('');
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
    });
</script>

<script>
    $(document).on('click','#addEmpJournal',function(){
        var emp = $('#addEmpText').val();
        var empId = $('#addEmpTextList').find('option[value="'+emp+'"]').attr('id');
       
        $.ajax({
            url : "<?php echo site_url('AdHocController/addRowForEmpJournal');?>",
            method : "POST",
            data : {empId:empId},
            success: function(data){
                $('#tbodyForEmployeeJournalData').append(data);
                $('#addEmpText').val('');
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
    });
</script>


<script type="text/javascript">
    $(document).on('click','#saveBillJournalBtn',function(){
        $('#saveBillJournalBtn').attr("disabled",true);

        var total= parseInt($('#TotalInvoiceAmt').text());

        var allocationId = 0;
        var comp = 0;

        var radioType=$('input[name="radioType"]:checked').val();
        if(radioType == "allocation"){
             allocationId = $('#allocationsList').val();
        }else{
            comp = $('#excelcompany').val();
        }

        var remark= $('#remarkForAll').val();
        if(remark == ""){
            $('#saveBillJournalBtn').attr("disabled",false);
            alert('Please enter remark');die();
        }
        


        var selectedId = [];
        $.each($("input[name='debitIdForSelectedBill[]']"), function(){
            selectedId.push($(this).val());
        });

        var selectPendingBillAmt = [];
        var finalPendingBillTotal=0;
        var finalAmtTotal=0;
        $.each($("input[name='debitIdForSelectedBillAmt[]']"), function(){
            
            if($(this).val() !=""){
                finalPendingBillTotal=parseInt(finalPendingBillTotal)+parseInt($(this).val());
                finalAmtTotal=parseInt(finalAmtTotal)+parseInt($(this).val());
                selectPendingBillAmt.push($(this).val());
            }else{
                selectPendingBillAmt.push(0);
            }
        });

        var selectBillId = [];
        $.each($("input[name='idForAddBilldebitAmt[]']"), function(){
            selectBillId.push($(this).val());
        });

        var selectBillAmt = [];
        var finalBillTotal=0;
        $.each($("input[name='addBilldebitAmt[]']"), function(){
            
            if($(this).val() !=""){
                finalBillTotal=parseInt(finalBillTotal)+parseInt($(this).val());
                selectBillAmt.push($(this).val());
            }else{
                selectBillAmt.push(0);
            }
        });

        var selectEmpId = [];
        $.each($("input[name='idForAddEmpdebitAmt[]']"), function(){
            selectEmpId.push($(this).val());
        });

        var selectEmpAmt = [];
        var finalEmpTotal=0;
        $.each($("input[name='addEmpdebitAmt[]']"), function(){
            
            if($(this).val() !=""){
                finalEmpTotal=parseInt(finalEmpTotal)+parseInt($(this).val());
                selectEmpAmt.push($(this).val());
            }else{
                selectEmpAmt.push(0);
            }
        });

        var finalTotal=parseInt(finalBillTotal)+parseInt(finalEmpTotal);

        if(selectBillId.length == 0 && selectEmpId.length == 0){
            $('#saveBillJournalBtn').attr("disabled",false);
            alert('Please add Bill/Employee for debit');die();
        }
     
        if(finalPendingBillTotal !== finalTotal){
            $('#saveBillJournalBtn').attr("disabled",false);
            alert('Amount not match with bills total amount');die();
        }


        $.ajax({
            url : "<?php echo site_url('AdHocController/finalDebitTransactionSubmit');?>",
            method : "POST",
            data : {allocationId:allocationId,comp:comp,remark:remark,selectedId:selectedId,selectPendingBillAmt:selectPendingBillAmt,selectBillId:selectBillId,selectBillAmt:selectBillAmt,selectEmpId:selectEmpId,selectEmpAmt:selectEmpAmt},
            success: function(data){
                // alert(data);die();

                window.parent.location.reload(true);
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
       
    });
</script>

<script>
    function checkAmountPerItem(current,no,pending){
        var current=parseInt(current.value);
        var pending=parseInt(pending);
        var msg="";

        var total=0;
        $.each($("input[name='debitIdForSelectedBillAmt[]']"), function(){
            if($(this).val() !=""){
                total=parseInt(total)+parseInt($(this).val());
            }
        });

        if((current>pending)){
            msg="Amount can not be more than pending amount";
            document.getElementById('data_err'+no).innerHTML=msg;
            $('#saveBillJournalBtn').prop('disabled',true);
        }else{
            var msg=""
            document.getElementById('data_err'+no).innerHTML=msg;
            $('#saveBillJournalBtn').prop('disabled',false);

            if(total>0){
                $('#TotalInvoiceAmt').text(total);
            }else{
                $('#TotalInvoiceAmt').text(total);
            }
        }
    }

    function checkPerItem(current,no,pending){
        var current=parseInt(current.value);
        var pending=parseInt(pending);
        var msg="";

        var total=0;
        $.each($("input[name='debitIdForSelectedBillAmt[]']"), function(){
            if($(this).val() !=""){
                total=parseInt(total)+parseInt($(this).val());
            }
        });

        var finalBillTotal=0;
        $.each($("input[name='addBilldebitAmt[]']"), function(){
            if($(this).val() !=""){
                finalBillTotal=parseInt(finalBillTotal)+parseInt($(this).val());
            }
        });

        var finalEmpTotal=0;
        $.each($("input[name='addEmpdebitAmt[]']"), function(){
            if($(this).val() !=""){
                finalEmpTotal=parseInt(finalEmpTotal)+parseInt($(this).val());
            }
        });

        var totalRem=parseInt(finalBillTotal)+parseInt(finalEmpTotal);

        if(total>0){
            $('#TotalPendingAmt').text(total-totalRem);
        }else{
            $('#TotalPendingAmt').text(total-totalRem);
        }
    }
</script>