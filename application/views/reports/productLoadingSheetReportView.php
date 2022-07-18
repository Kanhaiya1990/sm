<?php $this->load->view('/layouts/commanHeader'); ?>

<style type="text/css">
    @media screen and (min-width: 1100px) {
        .modal-dialog {
          width: 1100px; /* New width for default modal */
        }
        .modal-sm {
          width: 350px; /* New width for small modal */
        }
    }

    @media screen and (min-width: 1100px) {
        .modal-lg {
          width: 1100px; /* New width for large modal */
        }
    }

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

    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>    
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                              Delivery Slip Loading Sheet
                            </h2>
                        </div>
                      
                        <div class="body">
                        <div class="row clearfix">
                            <!-- <form method="post" role="form" action="<?php echo site_url('reports/ReportController/loadingSheetProductReport');?>">  -->
                               
                                    <div class="col-md-12"> 
                                        <div class="col-md-2">
                                            <b>From Date</b><span style="color:red">  *</span>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">account_box</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="date" autocomplete="off" placeholder="Select Date" autofocus id="fromDate" name="fromDate" class="form-control date" required>
                                                </div>
                                            </div>
                                        </div>
                                    
                                        <div class="col-md-2">
                                            <b>To Date</b><span style="color:red">  *</span>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">account_box</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="date" autocomplete="off" placeholder="Select Date" autofocus id="toDate" name="toDate" class="form-control date" required>
                                                </div>
                                            </div>
                                        </div>
                                       
                                        <div class="col-md-3">
                                            <b>From Bill</b><span style="color:red">  *</span>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">account_box</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="text" autocomplete="off" placeholder="Select Bills" list="fromBillList" autofocus id="fromBill" name="fromBill" class="form-control date">
                                                    <datalist id="fromBillList"></datalist>
                                                    <input type="hidden" autocomplete="off" placeholder="Select Bills" id="fromBillId" name="fromBillId" class="form-control date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <b>To Bill</b><span style="color:red">  *</span>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">account_box</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="text" autocomplete="off" placeholder="Select Bills" list="toBillList" autofocus id="toBill" name="toBill" class="form-control date">
                                                    <datalist id="toBillList"></datalist>
                                                    <input type="hidden" autocomplete="off" id="toBillId" name="toBillId" class="form-control date">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <p id="textData" style="display:none;"></p>
                                        <!-- <p id="textData"></p> -->
                                        <input type="hidden" autocomplete="off" id="selectedIds" name="selectedIds" class="form-control date">


                                        <div class="col-md-2">
                                            <button id="fromToAdd" class="btn btn-sm btn-primary m-t-15 waves-effect">
                                                <i class="material-icons">add</i> 
                                                <span class="icon-name">Add</span>
                                            </button>
                                        </div>

                                        
                                    </div>
                                
                            <!-- </form> -->
                            </div>
                               
                            <div class="row clearfix">
                                <div class="col-md-12"> 
                                    <div class="col-md-3">
                                    <b>Manual Bill</b>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="material-icons">account_box</i>
                                        </span>
                                        <div class="form-line">
                                            <input type="text" autocomplete="off" placeholder="Select Manual Bill" list="manualBillList" autofocus id="manualBill" name="manualBill" class="form-control date">
                                            <datalist id="manualBillList"></datalist>
                                        </div>
                                    </div>
                                    </div>

                                    <div class="col-md-6">
                                        <button id="newBills" class="btn btn-sm btn-primary m-t-15 waves-effect">
                                           <i class="material-icons">add</i>
                                           <span class="icon-name">Add</span>
                                        </button>

                                        <button id="refreshTable" class="btn btn-sm btn-danger m-t-15 waves-effect">
                                           <i class="material-icons">cancel</i>
                                           <span class="icon-name">Cancel</span>
                                        </button>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <button id="exportReport" class="btn btn-sm btn-primary m-t-15 waves-effect">
                                            <i class="material-icons">download</i> 
                                            <span class="icon-name">Download Report</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                               
                            <table id="billWiseTbl" class="table table-bordered table-striped table-hover">
                               <thead>
                                   <tr>
                                       <th>Bill No</th>
                                       <th>Bill Date</th>
                                       <th>Retailer</th>
                                       <th>Salesman</th>
                                       <th>Bill Amount</th>
                                       <th>Pending Amount</th>
                                   </tr>
                               </thead>
                                
                               <tbody id="result_data">
                               </tbody>
                            </table>

                        </div>
                            
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php $this->load->view('/layouts/footerDataTable'); ?>

<script type="text/javascript">
    $(document).on('change','#toDate',function(){
        var fromDate=$('#fromDate').val();
        var toDate=$('#toDate').val();

        if(fromDate == "" || toDate == ""){
            toastr.info('Please select correct dates.', 'Alert!');
            die();
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('AdHocController/loadDeliverySlipBills');?>",
                data:{fromDate:fromDate,toDate:toDate},
                success: function (data) {
                    $('#manualBillList').html(data);
                    $('#fromBillList').html(data);
                    $('#toBillList').html(data);
                },
                beforeSend: function(){
                    $('.comman-ajax-loader').css("visibility", "visible");
                },
                complete: function(){
                    $('.comman-ajax-loader').css("visibility", "hidden");
                },
                error: function(jqXHR, exception) {
                    alert("Write error Message Here");
                }  
            });
        }

    });

    $(document).on('click','#newBills',function(){
        var manualBill=$('#manualBill').val();
        var billId = $('#manualBillList').find('option[value="'+manualBill+'"]').attr('id');
        
        if (typeof billId === "undefined") {
            toastr.info('Please select correct bill.', 'Alert!');
            die();
        }
       
        $('#textData').append(billId+',');
        $('#selectedIds').val($('#textData').text());
     
        if(billId ==""){
            $('#manualBill').val('');
            $('#manualBill').focus();
            alert('Please enter bill');die();
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('reports/ReportController/addManualBill');?>",
                data:{billId:billId},
                success: function (data) {
                    $('#result_data').append(data);
                    $('#manualBill').val('');
                    $('#manualBill').focus();
                    removeDuplicateRows($('#billWiseTbl'));
                },
                beforeSend: function(){
                    $('.comman-ajax-loader').css("visibility", "visible");
                },
                complete: function(){
                    $('.comman-ajax-loader').css("visibility", "hidden");
                },
                error: function(jqXHR, exception) {
                    alert("Write error Message Here");
                }  
            });
        }
        
    });

    $(document).on('click','#fromToAdd',function(){
        var fromBill=$('#fromBill').val();
        var toBill=$('#toBill').val();
        
        var fromBillId = $('#fromBillList').find('option[value="'+fromBill+'"]').attr('id');
        if (typeof fromBillId === "undefined") {
            toastr.info('Please select correct bill.', 'Alert!');
            die();
        }

        var toBillId = $('#toBillList').find('option[value="'+toBill+'"]').attr('id');
        if (typeof toBillId === "undefined") {
            toastr.info('Please select correct bill.', 'Alert!');
            die();
        }
       
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('reports/ReportController/addFromToBill');?>",
            data:{fromBillId:fromBillId,toBillId:toBillId},
            success: function (data) {
                $('#result_data').append(data);
                $('#fromBill').val('');
                $('#toBill').val('');
                $('#fromBill').focus();
                removeDuplicateRows($('#billWiseTbl'));
            },
            beforeSend: function(){
                $('.comman-ajax-loader').css("visibility", "visible");
            },
            complete: function(){
                $('.comman-ajax-loader').css("visibility", "hidden");
            },
            error: function(jqXHR, exception) {
                alert("Write error Message Here");
            }  
        });
        
        
    });

    $(document).on('blur','#fromBill',function(){
        var bill=$('#fromBill').val();
        if(bill==""){
            die();
        }
        var billId = $('#fromBillList').find('option[value="'+bill+'"]').attr('id');
        
        if (typeof billId === "undefined") {
            toastr.info('Please select correct bill.', 'Alert!');
            die();
        }
         
        $('#fromBillId').val(billId);
    });

    $(document).on('blur','#toBill',function(){
        var bill=$('#toBill').val();
        if(bill==""){
            die();
        }
        var billId = $('#toBillList').find('option[value="'+bill+'"]').attr('id');
        
        if (typeof billId === "undefined") {
            toastr.info('Please select correct bill.', 'Alert!');
            die();
        }
         
        $('#toBillId').val(billId);
    });

    $(document).on('blur','#manualBill',function(){
        var bill=$('#manualBill').val();
        if(bill==""){
            die();
        }
        var billId = $('#manualBillList').find('option[value="'+bill+'"]').attr('id');
        
        if (typeof billId === "undefined") {
            toastr.info('Please select correct bill.', 'Alert!');
            die();
        }
    });

    $(document).on('click','#refreshTable',function(){
        $('#manualBill').val('');
        $('#result_data').html('');
        $('#selectedIds').val('');
        $('#textData').html('');
    });

</script>

<script>
    function removeMe(that,id) {
        var rmId=id;
        $(that).closest('tr').remove();

        var allId=$('#selectedIds').val();
        var text=$('#textData').text();

        $("#textData").text($("#textData").text().replace(rmId+",", ""));
        $("#selectedIds").val($("#selectedIds").val().replace(rmId+",", ""));
    }
</script>

<script>
    function removeDuplicateRows($table){
        function getVisibleRowText($row){
            return $row.find('td:visible').text().toLowerCase();
        }
        
        $table.find('tr').each(function(index, row){
            var $row = $(row);
        
            $row.nextAll('tr').each(function(index, next){
                var $next = $(next);
                console.log(getVisibleRowText($row), getVisibleRowText($next))
                if(getVisibleRowText($next) == getVisibleRowText($row))
                    $next.remove();
            })
        });
    }
</script>


<script>
     $(document).on('click','#exportReport',function(){
        var fromDate=$('#fromDate').val();
        var toDate=$('#toDate').val();

        var billIds = $("input[name='selectedBillId[]']")
              .map(function(){return $(this).val();}).get();

        if(billIds == ""){
            alert('please add bills');die();
        }else{

            var fromDate = encodeURIComponent(fromDate);
            var toDate = encodeURIComponent(toDate);
            var billIds = encodeURIComponent(billIds);

            window.location.href="<?php echo base_url();?>index.php/reports/ReportController/downloadLoadingSheetProductReport/"+fromDate+"/"+toDate+"/"+billIds;
              
            // $.ajax({
            //     type: "POST",
            //     url:"<?php echo site_url('reports/ReportController/downloadLoadingSheetProductReport');?>",
            //     data:{fromDate:fromDate,toDate:toDate,billIds:billIds},
            //     success: function (data) {
            //     //    alert(data);

            //     },
            //     beforeSend: function(){
            //         $('.comman-ajax-loader').css("visibility", "visible");
            //     },
            //     complete: function(){
            //         $('.comman-ajax-loader').css("visibility", "hidden");
            //     },
            //     error: function(jqXHR, exception) {
            //         alert("Write error Message Here");
            //     }  
            // });
        }
    });
</script>