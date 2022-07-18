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
                            <h2><a href="<?php echo site_url('AdHocController/debitToEmployeeBills');?>">
                              <button class="btn btn-xs bg-primary margin"><i class="material-icons"> refresh</i>    </button></a>
                            Debit To Employee Bills
                         </h2>
                    </div>
                    <div class="body">
                        <div class="row">                                 
                            <div class="row m-t-20">
                                <div class="col-md-12">
                                    <div class="col-md-2">
                                        <b> Division </b>
                                        <div class="input-group">
                                            <select name="company" class="form-control" id="excelcompany" required>
                                                <option value=''>--select Division--</option>
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
                                        </div> 
                                    </div> 

                                        <div class="col-md-3">
                                            <b>Employee:</b>
                                            <input type="text" class="form-control" id="debitEmpId" name="debitEmpId" placeholder="Select Employee" list="debitEmpIdList" required >
                                            <datalist id="debitEmpIdList">
                                            <?php  foreach($emp as $item){
                                                // $billNo=$item['billNo'];
                                                ?>   
                                                    <option id="<?php echo $item['id'] ?>" value="<?php echo $item['name']; ?>" />
                                            
                                            <?php } ?>
                                            </datalist>
                                        </div>

                                        <div class="col-md-3">
                                            <b>From Bills:</b>
                                            <input type="text" class="form-control" id="from_Bills" name="from_Bills" placeholder="From Bills" list="frmBill" value="<?php if(!empty($from)){ echo $from; } ?>" required >
                                            <datalist id="frmBill">
                                               
                                            </datalist>
                                        </div>

                                        <div class="col-md-3">
                                            <b>To Bills:</b>
                                            <input type="text" class="form-control" id="to_Bills" name="to_Bills" placeholder="To Bills" list="toBill" value="<?php if(!empty($to)){ echo $to; } ?>" required>
                                            <datalist id="toBill">
                                                
                                            </datalist>
                                        </div>
                                      <div class="col-md-1">
                                        <button id="submitBtn" class="btn m-t-20 btn-primary">Search</button>
                                      </div>
                                </div>
                                <div class="col-md-12">

                                    <!-- <button data-toggle="modal" disabled data-target="#percentModel" class="permodalLink btn m-t-20 btn-primary">
                                    Percent (%)</button>

                                    <button data-toggle="modal" disabled data-target="#flatModel" class="flatmodalLink btn m-t-20 btn-primary">
                                    Flat Amount</button>
                                    
                                    <button id="manualBtn" disabled class="btn m-t-20 btn-primary">Manual Debit Note</button>
                                     -->
                                    <table style="font-size: 12px" class="table table-bordered table-striped table-hover" data-page-length='100'>
                                        <thead>
                                            <tr>
                                                <th colspan="8">
                                                <span style="color:blue"> Bills For Debit To Employee </span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr class="gray">
                                                <th> S. No.</th>
                                                <th> Bill No</th>
                                                <th> Bill Date</th>
                                                <th> Retailer</th>
                                                <th> Bill Net Amount</th>
                                                <th> Net Amount</th>
                                                <th> Credit Note</th>
                                                <th> Pending Amount</th>
                                                <th>Amount</th>
                                                <th> Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyData">

                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12">
                                    <button id="manualBtnFinal" class="btn m-t-20 btn-primary" disabled>Save Debit Note</button>
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
    $(document).on('change','#excelcompany',function(){
            var comp = $('#excelcompany').val();
            $('#frmBill').html('');
            $('#toBill').html('');

            $('#from_Bills').val('');
            $('#to_Bills').val('');

            if(comp==""){
                alert("Please select division");
            }else{
                $.ajax({
                    url : "<?php echo site_url('AdHocController/getBillsByComp');?>",
                    method : "POST",
                    data : {comp: comp},
                    success: function(data){
                        $('#frmBill').html(data);
                        $('#toBill').html(data);
                        $('#manualBtnFinal').prop('disabled',true);
                    }
                });
            }
    });
</script>

<script type="text/javascript">
    $(document).on('click','#submitBtn',function(){
        var comp = $('#excelcompany').val();
        var fromBill = $('#from_Bills').val();
        var toBill = $('#to_Bills').val();
        
        var frmBillId = $('#frmBill').find('option[value="'+fromBill+'"]').attr('id');
        var toBillId = $('#toBill').find('option[value="'+toBill+'"]').attr('id');

        var empName=$('#debitEmpId').val();
        var empId = $('#debitEmpIdList').find('option[value="'+empName+'"]').attr('id');

        if(typeof empId === "undefined"){
            alert('Select correct Employee');die();
        }

        if (typeof frmBillId === "undefined") {
            alert("Please select correct billno...");
            die();
        }

        if (typeof toBillId === "undefined") {
            alert("Please select correct billno...");
            die();
        }

        if(comp=="" || frmBillId=="" || toBillId==""){
            alert("Please select data...");
            die();
        }else{
            

            $.ajax({
                url : "<?php echo site_url('AdHocController/getAllBillsForEmployeeDebitNote');?>",
                method : "POST",
                data : {comp: comp,fromBill:frmBillId,toBill:toBillId},
                success: function(data){
                    $('#tbodyData').html(data);
                    $('#manualBtnFinal').prop('disabled',false);
                }
            });
        }
    });
</script>

<script type="text/javascript">
    // $(document).on('click','#manualBtn',function(){
    //     $('#percentAmt').prop('type','hidden');
    //     $('#flatAmt').prop('type','hidden');
    //     $("input[name='debitAmt[]']").prop('type','text');
    //     $("input[name='debitAmtRemark[]']").prop('type','text');
    //     $('#manualBtnFinal').prop('disabled',false);
    // });
</script>

<script type="text/javascript">
    function removeMe(that,id) {
        var rmId=id;
        $(that).closest('tr').remove();
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
    $(document).on('click','#flatBtnFinal',function(){
        var selValue = [];
        $.each($("input[name='idFordebitAmt[]']"), function(){
                selValue.push($(this).val());
        });
        
        var percentAmt = $('#addflatNo').val();
        var percentRemark = $('#addflatRemark').val();

        if(percentAmt == ""){
            alert('Amount should not be blank.');
            $('#addflatNo').focus();
            die();
        }

        if(percentRemark == ""){
            alert('Please enter remark.');
            $('#addflatRemark').focus();
            die();
        }

        // if(percentAmt > 100){
        //     alert('Percentage should not be greater than 100.');
        //     $('#addflatNo').focus();
        //     die();
        // }
        
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('AdHocController/saveDebitNoteCollectionForFlatAmount');?>",
            data:{selValue:selValue,percentAmt:percentAmt,percentRemark:percentRemark},
            success: function (data) {
                if(data.trim()==""){
                    window.location.href="<?php echo base_url();?>index.php/AdHocController/debitToEmployeeBills";
                }else{
                    alert(data);
                }
            }  
        });
    });
</script>

<script type="text/javascript">
    $(document).on('click','#percentBtnFinal',function(){
        var selValue = [];
        $.each($("input[name='idFordebitAmt[]']"), function(){
                selValue.push($(this).val());
        });
        
        var percentAmt = $('#addPercentNo').val();
        var percentRemark = $('#addPercentRemark').val();

        if(percentAmt == ""){
            alert('Percent should not be blank.');
            $('#addPercentNo').focus();
            die();
        }

        if(percentRemark == ""){
            alert('Please enter remark.');
            $('#addPercentRemark').focus();
            die();
        }

        if(percentAmt > 100){
            alert('Percentage should not be greater than 100.');
            $('#addPercentNo').focus();
            die();
        }
        
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('AdHocController/saveDebitNoteCollectionForPercentAmount');?>",
            data:{selValue:selValue,percentAmt:percentAmt,percentRemark:percentRemark},
            success: function (data) {
                if(data.trim()==""){
                    window.location.href="<?php echo base_url();?>index.php/AdHocController/debitToEmployeeBills";
                }else{
                    alert(data);
                }
            }  
        });
    });
</script>

<script type="text/javascript">
    $(document).on('click','#manualBtnFinal',function(){
        var selValue = [];
        $.each($("input[name='idFordebitAmt[]']"), function(){
                selValue.push($(this).val());
        });

        var empName=$('#debitEmpId').val();
        var empId = $('#debitEmpIdList').find('option[value="'+empName+'"]').attr('id');

        if(empId ==""){
            alert('Select correct Employee');die();
        }
        
        var len=selValue.length;
        
        
        var selDebitAmt = [];
        $.each($("input[name='debitAmt[]']"), function(){
            selDebitAmt.push($(this).val());
        });
        var amtLen = selDebitAmt.filter(function(v){return v!==''});
        
        var selDebitAmtRemark = [];
        $.each($("input[name='debitAmtRemark[]']"), function(){
            selDebitAmtRemark.push($(this).val());
        });
        var remLen = selDebitAmtRemark.filter(function(v){return v!==''});
        
        
        if(len !=amtLen.length){
            alert('Please enter Debit Amount for selected bills');die();
        }

        if(len !=remLen.length){
            alert('Please enter Remark for selected bills');die();
        }
        
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('AdHocController/saveEmployeeDebitNoteCollectionManualAmount');?>",
            data:{selValue:selValue,selDebitAmt:selDebitAmt,selDebitAmtRemark:selDebitAmtRemark,empId:empId},
            success: function (data) {
                if(data.trim()==""){
                    window.location.href="<?php echo base_url();?>index.php/AdHocController/debitToEmployeeBills";
                }else{
                    alert(data);
                }
            }  
        });
    });
</script>