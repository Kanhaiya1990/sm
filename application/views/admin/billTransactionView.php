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
    .line{
        width: 112px;
        height: 47px;
        border-bottom: 1px solid black;
        position: absolute;
    }
</style>

<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/>
<section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Change Bill Transaction
                            </h2>
                            <!-- <h2>
                                <p align="left">
                                  <a href="<?php echo site_url('admin/EmployeeRelationController/Add');?>">
                                    <button type="submit" class="btn bg-primary margin"><i class="material-icons">person_add</i>  Add  </button></a> 
                                </p> 
                            </h2> -->
                       
                        </div>
                         <div class="body">
                            
                            <div class="row clearfix">
                            <!-- <div class="demo-masked-input"> -->
                                
                                  <div class="col-md-12"> 
                                    <div class="col-md-4">
                                        <b>Bill Number</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" autocomplete="off" autofocus id="billNo" name="billNo" class="form-control date" placeholder="Enter bill number" list="billData" required>
                                            </div>
                                            <p id="billNo_Id"></p>
                                        </div>
                                    </div>
                                  
                                        <div class="col-md-4">
                                        <?php if($this->session->userdata['workRestrict']['status']=="yes"){?>
                                            <button class="btn btn-xs btn-primary m-t-25 waves-effect" onclick="planUpgradeMsg()">
                                                <i class="material-icons">search</i> 
                                                <span class="icon-name">
                                                 Search
                                                </span>
                                            </button>
                                        <?php }else{ ?>
                                            <button id="searchInfo" class="btn btn-xs btn-primary m-t-25 waves-effect">
                                                <i class="material-icons">search</i> 
                                                <span class="icon-name">
                                                 Search
                                                </span>
                                            </button>
                                        <?php } ?>
                                            
                                           <a href="<?php echo site_url('admin/BillTransactionController');?>">
                                                <button type="button" class="btn btn-xs btn-danger m-t-25 waves-effect">
                                                    <i class="material-icons">cancel</i> 
                                                    <span class="icon-name"> Cancel</span>
                                                </button>
                                            </a> 
                                        </div>

                                        
                                    </div> 
                                    <div id="hideInfo" class="col-md-12"> 
                                    </div>    

                                    <div id="transactionInfo" class="col-md-12"> 
                                         <div class="table-responsive">
                                            <table style="font-size:13px" style="font-size:13px" class="table table-bordered table-striped table-hover" data-page-length='100'>
                                                <thead>
                                                    <tr>
                                                        <th>Transaction Type</th>
                                                        <th class="text-right">As Per Records</th>
                                                        <th>New Entry</th>
                                                        <th>Remark</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                               
                                                <tbody id="billsData">
                                                    <tr>
                                                        <td colspan="4">No data found</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>                               
                                <!-- </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Examples -->  
        </div>
    </section>



<div class="modal" tabindex="-1" role="dialog" id="retailerModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <center>
        
         <h4 style="color:#050A30">Edit transaction for <span id="transaction_title_name"></span> </h4>
         <h4 id="title_name" style="color:#050A30">Bill Transactions </h4>
        </center>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="row">
            <div class="col-md-12">
                <div class="col-md-3">
                    <h5 style="color:#000000">Bill No :  <span style="color:#050A30" id='bill_no'></span></h5>
                    </div> 
                
                 <div class="col-md-3">
                    <h5 style="color:#000000">Bill Date :  <span style="color:#050A30" id='bill-date'></span></h5>
                </div> 
                <span id='bill_retailer'></span>
                <div class="col-md-3">
                    <h5 style="color:#000000">Pending Amount : <span style="color:#050A30" id='bill_pendingAmt'></span></h5>
                    <input type="hidden" id="currentPendingAmt" autocomplete="off" name="currentPendingAmt" class="form-control">   
                </div>
            </div>

            <div class="col-md-12">
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
        <input type="hidden" class="form-control" id="remarkForTransaction" name="remarkForTransaction"><br>
        <input type="hidden" class="form-control" id="userOtp" name="userOtp" onkeypress="return isNumber(event)">
        <input type="hidden" class="form-control" id="userBillId" name="userBillId" onkeypress="return isNumber(event)">
        <input type="hidden" class="form-control" id="oldAmt" name="oldAmt" onkeypress="return isNumber(event)">
        <input type="hidden" class="form-control" id="userAmt" name="userAmt" onkeypress="return isNumber(event)">
        <input type="hidden" class="form-control" id="userType" name="userType" onkeypress="return isNumber(event)"><br>
        
      <div class="modal-body">

        <div class="row">
            <div class="col-md-6" style="color:#050A30">Old Value : <span style="color:#050A30" id="oldValueText"></span></div>
            <div class="col-md-6" style="color:#050A30">New Value : <span style="color:#050A30" id="newValueText"></span></div>
        </div><br>
        <div class="row">
            <div class="col-md-6" style="color:#050A30">CAPTCHA : <span style="color:#050A30" id="userOtpText"></span></div>
            <div class="col-md-6"><input type="text" class="form-control" id="otpText" name="otpText" placeholder="Enter CAPTCHA" onkeypress="return isNumber(event)"></div>
        </div>
        <button type="button" id="otpCheck" class="btn btn-primary" >Verify CAPTCHA</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
    
<?php $this->load->view('/layouts/footerDataTable'); ?>

<?php $this->load->view('/layouts/processButtonView'); ?>

<script type="text/javascript">
    $(document).on('click','#showId',function(){
        var billNo=$('#billNo').val();
         $.ajax({
            url : "<?php echo site_url('admin/BillTransactionController/getBillTransaction');?>",
            method : "POST",
            data : {billNo:billNo},
            success: function(data){
                alert(data);
                $('#tblData').html(data);
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
    $(document).on('click','#editEntryId',function(){
        var id=$(this).attr('data-id');
        var billId=$(this).attr('data-billId');
        var paymentMode=$(this).attr('data-paymentMode');
        // alert(id+' '+billId+' '+paymentMode);

        // $.ajax({
        //     url : "<?php echo site_url('admin/BillTransactionController/getBillTransaction');?>",
        //     method : "POST",
        //     data : {billNo:billNo},
        //     success: function(data){
        //         alert(data);
        //         $('#tblData').html(data);
        //     }
        // });
    });
 </script>

  <script type="text/javascript">
//     $(document).on('click','#searchInfo',function(){
//         var billNo=$('#billNo').val();
//         var billId = $('#billData').find('option[value="'+billNo+'"]').attr('id');
//         if(billNo===''){
//             $('#hideInfo').html('');
//             $('#billNo').focus();
//         }else{
//             $.ajax({
//                 type: "POST",
//                 url:"<?php echo site_url('AdHocController/billInfo');?>",
//                 data:{billNo:billNo,billId:billId},
//                 success: function (data) {
//                     // alert(data);
//                     $('#hideInfo').html(data);
//                     $('#billNo').val('');
//                     $('#billNo').focus();
//                 }  
//             });
//         }
//     });
// </script>

<script type="text/javascript">
//     $(document).on('click','#searchInfo',function(){
//         var billNo=$('#billNo').val();
//         var billId = $('#billData').find('option[value="'+billNo+'"]').attr('id');
//         if(billNo===''){
//             $('#hideInfo').html('');
//             $('#billNo').focus();
//         }else{
//             $.ajax({
//                 type: "POST",
//                 url:"<?php echo site_url('admin/BillTransactionController/getAllTransactionView');?>",
//                 data:{billNo:billNo,billId:billId},
//                 success: function (data) {
//                     $('#billsData').html(data);
//                 }  
//             });
//         }
//     });
// </script>

<script type="text/javascript">
    $(document).on('keypress','#billNo',function(e){
        if (e.keyCode == 13) {
            var billNo=$('#billNo').val();
            var charCount=$('#billNo').val().length;
            if(charCount<5){
                alert('Please enter first 5 characters');die();
            }
            // var billId = $('#billData').find('option[value="'+billNo+'"]').attr('id');
            if(billNo===''){
                $('#hideInfo').html('');
                $('#billNo').focus();
            }else{
                $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('AdHocController/billInfoForAdmin');?>",
                    data:{billNo:billNo},
                    success: function (data) {
                        // alert(data);
                        $('#hideInfo').html(data);
                        $('#billNo').val('');
                        $('#billNo').focus();
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
    $(document).on('click','#searchInfo',function(){
        var billNo=$('#billNo').val();
        var charCount=$('#billNo').val().length;
        if(charCount<5){
            alert('Please enter first 5 characters');die();
        }
        // var billId = $('#billData').find('option[value="'+billNo+'"]').attr('id');
        if(billNo===''){
            $('#hideInfo').html('');
            $('#billNo').focus();
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('AdHocController/billInfoForAdmin');?>",
                data:{billNo:billNo},
                success: function (data) {
                    // alert(data);
                    $('#hideInfo').html(data);
                    $('#billNo').val('');
                    $('#billNo').focus();
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
    $(document).on('keypress','#billNo',function(e){
        if (e.keyCode == 13) {
            var billNo=$('#billNo').val();
            var charCount=$('#billNo').val().length;
            if(charCount<5){
                alert('Please enter first 5 characters');die();
            }
            // alert(billNo);die();
            // var billId = $('#billData').find('option[value="'+billNo+'"]').attr('id');
            if(billNo===''){
                $('#hideInfo').html('');
                $('#billNo').focus();
            }else{
                $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('admin/BillTransactionController/getAllTransactionView');?>",
                    data:{billNo:billNo},
                    success: function (data) {
                        // alert(data);
                        $('#billsData').html(data);
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
    $(document).on('click','#searchInfo',function(){
        var billNo=$('#billNo').val();
        var charCount=$('#billNo').val().length;
        if(charCount<5){
            alert('Please enter first 5 characters');die();
        }
        // alert(billNo);die();
        // var billId = $('#billData').find('option[value="'+billNo+'"]').attr('id');
        if(billNo===''){
            $('#hideInfo').html('');
            $('#billNo').focus();
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('admin/BillTransactionController/getAllTransactionView');?>",
                data:{billNo:billNo},
                success: function (data) {
                    // alert(data);
                    $('#billsData').html(data);
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
<script>
     function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if ((charCode < 48 || charCode > 57) ) {
            return false;
        }
        return true;
    }
    // function isNumber(evt) {
    //     evt = (evt) ? evt : window.event;
    //     var charCode = (evt.which) ? evt.which : evt.keyCode;
    //     if (charCode !=45 && (charCode < 48 || charCode > 57) ) {
    //         return false;
    //     }
    //     return true;
    // }
</script>

<script type="text/javascript">
    //reduce cash
    $(document).on('click','#cashAmtId',function(){
        var cashAmt=$('#cashAmtType').val();
        var remark=$('#cashRemark').val();
        if(remark==""){
            alert('Please enter remark');die();
        }

        var billId=$('#bill-id').val();

        var amt_cash=$('#amt_cash').val();
        if(amt_cash <=0 || amt_cash==""){
            alert('No cash transaction done earlier');die();
        }
        
        // alert('previous : '+cashAmt+' now: '+amt_cash);die()
        if(parseInt(cashAmt)>parseInt(amt_cash)){
            alert('Amount is greater than transaction amount');die();
        }
        
        var type="Cash";
        if(cashAmt===''){
            $('#cashAmtType').focus();
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('admin/BillTransactionController/receiveAmt');?>",
                data:{billId:billId,amt:cashAmt,type:type,oldTransaction:amt_cash,remark:remark},
                success: function (data) {
                    $('#otpText').val("");
                    $('#userAmt').val("");
                    $('#oldAmt').val("");
                    $('#userType').val("");
                    $('#userBillId').val("");
                    $('#userOtp').val("");

                    const obj = JSON.parse(data);
                    var pendingAmt=(Number(obj.pendingAmt).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    var oldValue=(Number(amt_cash).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    var newValue=(Number(obj.amt).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));

                    $('#retailerModal').modal('toggle');
                    $('#userOtp').val(obj.otp);
                    $('#userOtpText').text(obj.otp);
                    
                    $('#oldValueText').text(oldValue);
                    $('#newValueText').text(newValue);
                    
                    $('#userAmt').val(obj.amt);
                    $('#oldAmt').val(amt_cash);
                    $('#userType').val(obj.amtType);
                    $('#userBillId').val(billId);

                    $('#remarkForTransaction').val(obj.remark);

                    $('#transaction_title_name').text(obj.amtType);
                    $('#bill_no').text(obj.billNo);
                    $('#gst').text(obj.gst);
                    $('#title_name').text(obj.retailerName);
                    $('#bill_pendingAmt').text(pendingAmt);
                    $('#bill-date').text(obj.billDate);
                    $('#bill-salesman').text(obj.salesman);
                    $('#bill-route').text(obj.route);
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

    $(document).on('click','#chequeAmtId',function(){
        var chequeAmt=$('#chequeAmtType').val();
        var billId=$('#bill-id').val();
        var remark=$('#chequeRemark').val();
        if(remark==""){
            alert('Please enter remark');die();
        }

        var amt_cheque=$('#amt_cheque').val();
        // alert('old '+amt_cheque+' new '+chequeAmt);die();
        if(amt_cheque <=0 || amt_cheque==""){
            alert('No Cheque transaction done earlier');die();
        }

        if(parseInt(chequeAmt)>parseInt(amt_cheque)){
            alert('Amount is greater than transaction amount');die();
        }
        
        var type="Cheque";
        if(chequeAmt===''){
            $('#chequeAmtType').focus();
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('admin/BillTransactionController/receiveAmt');?>",
                data:{billId:billId,amt:chequeAmt,type:type,oldTransaction:amt_cheque,remark:remark},
                success: function (data) {
                    $('#otpText').val("");
                    $('#userAmt').val("");
                    $('#oldAmt').val("");
                    $('#userType').val("");
                    $('#userBillId').val("");
                    $('#userOtp').val("");

                    const obj = JSON.parse(data);
                    var pendingAmt=0;
                    if(obj.pendingAmt>0){
                        pendingAmt=(Number(obj.pendingAmt).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    }
                    var oldValue=(Number(amt_cheque).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    var newValue=(Number(obj.amt).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));

                    $('#retailerModal').modal('toggle');
                    $('#userOtp').val(obj.otp);
                    $('#userOtpText').text(obj.otp);
                    
                    $('#oldValueText').text(oldValue);
                    $('#newValueText').text(newValue);
                    
                    $('#userAmt').val(obj.amt);
                    $('#oldAmt').val(amt_cheque);
                    $('#userType').val(obj.amtType);
                    $('#userBillId').val(billId);

                    $('#remarkForTransaction').val(obj.remark);

                    $('#transaction_title_name').text(obj.amtType);
                    $('#bill_no').text(obj.billNo);
                    $('#gst').text(obj.gst);
                    $('#title_name').text(obj.retailerName);
                    $('#bill_pendingAmt').text(pendingAmt);
                    $('#bill-date').text(obj.billDate);
                    $('#bill-salesman').text(obj.salesman);
                    $('#bill-route').text(obj.route);
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

    $(document).on('click','#neftAmtId',function(){
        var amount=$('#neftAmtType').val();
        var billId=$('#bill-id').val();
        var remark=$('#neftRemark').val();
        if(remark==""){
            alert('Please enter remark');die();
        }

        var amt_neft=$('#amt_neft').val();
        if(amt_neft <=0 || amt_neft==""){
            alert('No NEFT transaction done earlier');die();
        }
// alert(amt_neft+' '+amount);die();
        if(parseInt(amount)>parseInt(amt_neft)){
            alert('Amount is greater than transaction amount');die();
        }
        
        var type="NEFT";
        if(amount===''){
            $('#neftAmtType').focus();
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('admin/BillTransactionController/receiveAmt');?>",
                data:{billId:billId,amt:amount,type:type,oldTransaction:amt_neft},
                success: function (data) {
                    $('#otpText').val("");
                    $('#userAmt').val("");
                    $('#oldAmt').val("");
                    $('#userType').val("");
                    $('#userBillId').val("");
                    $('#userOtp').val("");

                    const obj = JSON.parse(data);
                    var pendingAmt=0;
                    if(obj.pendingAmt>0){
                        pendingAmt=(Number(obj.pendingAmt).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    }
                    var oldValue=(Number(amt_neft).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    var newValue=(Number(obj.amt).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));

                    $('#retailerModal').modal('toggle');
                    $('#userOtp').val(obj.otp);
                    $('#userOtpText').text(obj.otp);
                    
                    $('#oldValueText').text(oldValue);
                    $('#newValueText').text(newValue);
                    
                    $('#userAmt').val(obj.amt);
                    $('#oldAmt').val(amt_neft);
                    $('#userType').val(obj.amtType);
                    $('#userBillId').val(billId);

                    $('#remarkForTransaction').val(obj.remark);

                    $('#transaction_title_name').text(obj.amtType);
                    $('#bill_no').text(obj.billNo);
                    $('#gst').text(obj.gst);
                    $('#title_name').text(obj.retailerName);
                    $('#bill_pendingAmt').text(pendingAmt);
                    $('#bill-date').text(obj.billDate);
                    $('#bill-salesman').text(obj.salesman);
                    $('#bill-route').text(obj.route);
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

    $(document).on('click','#srAmtId',function(){
        var amount=$('#srAmtType').val();
        var billId=$('#bill-id').val();
        var remark=$('#srRemark').val();
        if(remark==""){
            alert('Please enter remark');die();
        }

        var amt_sr=$('#amt_sr').val();
        if(parseInt(amt_sr) <=0 || amt_sr==""){
            alert('No SR transaction done earlier');die();
        }

        if(parseInt(amount)>parseInt(amt_sr)){
            alert('Amount is greater than transaction amount');die();
        }
        
        var type="SR";
        if(amount===''){
            $('#srAmtType').focus();
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('admin/BillTransactionController/receiveAmt');?>",
                data:{billId:billId,amt:amount,type:type,oldTransaction:amt_sr,remark:remark},
                success: function (data) {
                    $('#otpText').val("");
                    $('#userAmt').val("");
                    $('#oldAmt').val("");
                    $('#userType').val("");
                    $('#userBillId').val("");
                    $('#userOtp').val("");

                    const obj = JSON.parse(data);
                    var pendingAmt=0;
                    if(obj.pendingAmt>0){
                        pendingAmt=(Number(obj.pendingAmt).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    }
                    var oldValue=(Number(amt_sr).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    var newValue=(Number(obj.amt).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));

                    $('#retailerModal').modal('toggle');
                    $('#userOtp').val(obj.otp);
                    $('#userOtpText').text(obj.otp);
                    
                    $('#oldValueText').text(oldValue);
                    $('#newValueText').text(newValue);
                    
                    $('#userAmt').val(obj.amt);
                    $('#oldAmt').val(amt_sr);
                    $('#userType').val(obj.amtType);
                    $('#userBillId').val(billId);

                    $('#remarkForTransaction').val(obj.remark);

                    $('#transaction_title_name').text(obj.amtType);
                    $('#bill_no').text(obj.billNo);
                    $('#gst').text(obj.gst);
                    $('#title_name').text(obj.retailerName);
                    $('#bill_pendingAmt').text(pendingAmt);
                    $('#bill-date').text(obj.billDate);
                    $('#bill-salesman').text(obj.salesman);
                    $('#bill-route').text(obj.route);
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

    $(document).on('click','#ofcAdjAmtId',function(){
        var amount=$('#officeAdjAmtType').val();
        var billId=$('#bill-id').val();
        var remark=$('#ofcAdjRemark').val();
        if(remark==""){
            alert('Please enter remark');die();
        }
        
        var amt_ofcAdj=$('#amt_ofcAdj').val();
        if(amt_ofcAdj <=0 || amt_ofcAdj==""){
            alert('No Office Adjustment transaction done earlier');die();
        }

        if(parseInt(amount)>parseInt(amt_ofcAdj)){
            alert('Amount is greater than transaction amount');die();
        }

        var type="Office Adjustment";
        if(amount===''){
            $('#officeAdjAmtType').focus();
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('admin/BillTransactionController/receiveAmt');?>",
                data:{billId:billId,amt:amount,type:type,oldTransaction:amt_ofcAdj,remark:remark},
                success: function (data) {
                    $('#otpText').val("");
                    $('#userAmt').val("");
                    $('#oldAmt').val("");
                    $('#userType').val("");
                    $('#userBillId').val("");
                    $('#userOtp').val("");

                    const obj = JSON.parse(data);
                    var pendingAmt=0;
                    if(obj.pendingAmt>0){
                        pendingAmt=(Number(obj.pendingAmt).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    }
                    var oldValue=(Number(amt_ofcAdj).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    var newValue=(Number(obj.amt).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));

                    $('#retailerModal').modal('toggle');
                    $('#userOtp').val(obj.otp);
                    $('#userOtpText').text(obj.otp);
                    
                    $('#oldValueText').text(oldValue);
                    $('#newValueText').text(newValue);
                    
                    $('#userAmt').val(obj.amt);
                    $('#oldAmt').val(amt_ofcAdj);
                    $('#userType').val(obj.amtType);
                    $('#userBillId').val(billId);

                    $('#remarkForTransaction').val(obj.remark);

                    $('#transaction_title_name').text(obj.amtType);
                    $('#bill_no').text(obj.billNo);
                    $('#gst').text(obj.gst);
                    $('#title_name').text(obj.retailerName);
                    $('#bill_pendingAmt').text(pendingAmt);
                    $('#bill-date').text(obj.billDate);
                    $('#bill-salesman').text(obj.salesman);
                    $('#bill-route').text(obj.route);
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

    $(document).on('click','#otrAdjAmtId',function(){
        var amount=$('#otherAdjAmtType').val();
        var billId=$('#bill-id').val();
        var remark=$('#otherAdjRemark').val();
        if(remark==""){
            alert('Please enter remark');die();
        }

        var amt_otherAdj=$('#amt_otherAdj').val();
        if(amt_otherAdj <=0 || amt_otherAdj==""){
            alert('No Other Adjustment transaction done earlier');die();
        }

        if(parseInt(amount)>parseInt(amt_otherAdj)){
            alert('Amount is greater than transaction amount');die();
        }
        
        var type="Other Adjustment";
        if(amount===''){
            $('#otherAdjAmtType').focus();
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('admin/BillTransactionController/receiveAmt');?>",
                data:{billId:billId,amt:amount,type:type,oldTransaction:amt_otherAdj,remark:remark},
                success: function (data) {
                    $('#otpText').val("");
                    $('#userAmt').val("");
                    $('#oldAmt').val("");
                    $('#userType').val("");
                    $('#userBillId').val("");
                    $('#userOtp').val("");

                    const obj = JSON.parse(data);
                    var pendingAmt=0;
                    if(obj.pendingAmt>0){
                        pendingAmt=(Number(obj.pendingAmt).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    }
                    var oldValue=(Number(amt_otherAdj).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    var newValue=(Number(obj.amt).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));

                    $('#retailerModal').modal('toggle');
                    $('#userOtp').val(obj.otp);
                    $('#userOtpText').text(obj.otp);
                    
                    $('#oldValueText').text(oldValue);
                    $('#newValueText').text(newValue);
                    
                    $('#userAmt').val(obj.amt);
                    $('#oldAmt').val(amt_otherAdj);
                    $('#userType').val(obj.amtType);
                    $('#userBillId').val(billId);

                    $('#remarkForTransaction').val(obj.remark);

                    $('#transaction_title_name').text(obj.amtType);
                    $('#bill_no').text(obj.billNo);
                    $('#gst').text(obj.gst);
                    $('#title_name').text(obj.retailerName);
                    $('#bill_pendingAmt').text(pendingAmt);
                    $('#bill-date').text(obj.billDate);
                    $('#bill-salesman').text(obj.salesman);
                    $('#bill-route').text(obj.route);
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

    $(document).on('click','#cdAmtId',function(){
        var amount=$('#cdAmtType').val();
        var billId=$('#bill-id').val();
        var remark=$('#cdRemark').val();
        if(remark==""){
            alert('Please enter remark');die();
        }

        var amt_cd=$('#amt_cd').val();
        if(amt_cd <=0 || amt_cd==""){
            alert('No CD transaction done earlier');die();
        }

        if(parseInt(amount)>parseInt(amt_cd)){
            alert('Amount is greater than transaction amount');die();
        }
        
        var type="CD";
        if(amount===''){
            $('#cdAmtType').focus();
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('admin/BillTransactionController/receiveAmt');?>",
                data:{billId:billId,amt:amount,type:type,oldTransaction:amt_cd,remark:remark},
                success: function (data) {
                    $('#otpText').val("");
                    $('#userAmt').val("");
                    $('#oldAmt').val("");
                    $('#userType').val("");
                    $('#userBillId').val("");
                    $('#userOtp').val("");

                    const obj = JSON.parse(data);
                    var pendingAmt=0;
                    if(obj.pendingAmt>0){
                        pendingAmt=(Number(obj.pendingAmt).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    }
                    var oldValue=(Number(amt_cd).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    var newValue=(Number(obj.amt).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));

                    $('#retailerModal').modal('toggle');
                    $('#userOtp').val(obj.otp);
                    $('#userOtpText').text(obj.otp);
                    
                    $('#oldValueText').text(oldValue);
                    $('#newValueText').text(newValue);
                    
                    $('#userAmt').val(obj.amt);
                    $('#oldAmt').val(amt_cd);
                    $('#userType').val(obj.amtType);
                    $('#userBillId').val(billId);

                    $('#remarkForTransaction').val(obj.remark);

                    $('#transaction_title_name').text(obj.amtType);
                    $('#bill_no').text(obj.billNo);
                    $('#gst').text(obj.gst);
                    $('#title_name').text(obj.retailerName);
                    $('#bill_pendingAmt').text(pendingAmt);
                    $('#bill-date').text(obj.billDate);
                    $('#bill-salesman').text(obj.salesman);
                    $('#bill-route').text(obj.route);
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

    $(document).on('click','#debitAmtId',function(){
        var amount=$('#debitAmtType').val();
        var billId=$('#bill-id').val();
        var remark=$('#debitRemark').val();
        if(remark==""){
            alert('Please enter remark');die();
        }

        var amt_debit=$('#amt_debit').val();
        if(amt_debit <=0 || amt_debit==""){
            alert('No Debit transaction done earlier');die();
        }
        
        if(parseInt(amount)>parseInt(amt_debit)){
            alert('Amount is greater than transaction amount');die();
        }

        var type="Debit To Employee";
        if(amount===''){
            $('#debitAmtType').focus();
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('admin/BillTransactionController/receiveAmt');?>",
                data:{billId:billId,amt:amount,type:type,oldTransaction:amt_debit,remark:remark},
                success: function (data) {
                    $('#otpText').val("");
                    $('#userAmt').val("");
                    $('#oldAmt').val("");
                    $('#userType').val("");
                    $('#userBillId').val("");
                    $('#userOtp').val("");
                    
                    const obj = JSON.parse(data);
                    var pendingAmt=0;
                    if(obj.pendingAmt>0){
                        pendingAmt=(Number(obj.pendingAmt).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    }
                    var oldValue=(Number(amt_debit).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    var newValue=(Number(obj.amt).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));

                    $('#retailerModal').modal('toggle');
                    $('#userOtp').val(obj.otp);
                    $('#userOtpText').text(obj.otp);
                    
                    $('#oldValueText').text(oldValue);
                    $('#newValueText').text(newValue);
                    
                    $('#userAmt').val(obj.amt);
                    $('#oldAmt').val(amt_debit);
                    $('#userType').val(obj.amtType);
                    $('#userBillId').val(billId);

                    $('#remarkForTransaction').val(obj.remark);

                    $('#transaction_title_name').text(obj.amtType);
                    $('#bill_no').text(obj.billNo);
                    $('#gst').text(obj.gst);
                    $('#title_name').text(obj.retailerName);
                    $('#bill_pendingAmt').text(pendingAmt);
                    $('#bill-date').text(obj.billDate);
                    $('#bill-salesman').text(obj.salesman);
                    $('#bill-route').text(obj.route);
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
    $(document).on('click','#otpCheck',function(){
        var billNo=$('#billNo').val();
        var billId = $('#billData').find('option[value="'+billNo+'"]').attr('id');

        var otp=$('#userOtp').val();
        var amount=$('#userAmt').val();
        var oldAmount=$('#oldAmt').val();
        var amountType=$('#userType').val();
        var billId=$('#userBillId').val();
        var enteredOtp=$('#otpText').val();
        var finalRemark=$('#remarkForTransaction').val();

        // alert(finalRemark);die();

        if(enteredOtp===""){
            alert("Please enter CAPTCHA");die();
        }

        if(otp !== enteredOtp){
            alert("Please enter correct CAPTCHA");die();
        }else{
           $.ajax({
                type: "POST",
                url:"<?php echo site_url('admin/BillTransactionController/updateBillAmount');?>",
                data:{amount:amount,amountType:amountType,billId:billId,oldAmount:oldAmount,finalRemark:finalRemark},
                success: function (data) {
                    if(data.trim()=="Record updated"){
                        alert(data);
                        $.ajax({
                            type: "POST",
                            url:"<?php echo site_url('AdHocController/billInfo');?>",
                            data:{billNo:billNo,billId:billId},
                            success: function (data) {
                                // alert(data);
                                $('#hideInfo').html(data);
                                $('#billNo').val('');
                                $('#billNo').focus();
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

                        $.ajax({
                            type: "POST",
                            url:"<?php echo site_url('admin/BillTransactionController/getAllTransactionView');?>",
                            data:{billNo:billNo,billId:billId},
                            success: function (data) {
                                $('#billsData').html(data);
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
                        $('#retailerModal').modal('toggle');
                        // $('#retailerModal').toggle();
                        // location.reload(); 
                    }else{
                        alert(data);
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
    });
</script>