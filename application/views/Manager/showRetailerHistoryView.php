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

<script>
    $(document).ready(function() {
    $.fn.dataTable.ext.errMode = 'none';
    $('#xp').DataTable( {
        dom: 'Bfrtip',
        stateSave: true,
            buttons: [{
                    extend: 'pdf',
                    title: 'Retailerwise Outstanding',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                },{
                    extend: 'excel',
                    title: 'Retailerwise Outstanding',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                }, {
                    extend: 'csv',
                    title: 'Retailerwise Outstanding',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                }
            ]
        
    } );
} );
</script>

<style>
td.details-control {
    background: url('http://www.datatables.net/examples/resources/details_open.png') no-repeat center center;
    cursor: pointer;
}
tr.shown td.details-control {
    background: url('http://www.datatables.net/examples/resources/details_close.png') no-repeat center center;
}
</style>

    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>    
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
           
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                             Retailer History
                            </h2>
                       
                        </div>
                      
                        <div class="body">
                            
                            <div class="row clearfix">
                            <!-- <div class="demo-masked-input"> -->
                                
                                  <div class="col-md-12"> 
                                    <div class="col-md-6">
                                        <b>Retailer</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text"  autocomplete="off" id="retailer" name="retailer" class="form-control date" placeholder="Enter Retailer Name" list="retailerData" required>
                                                <datalist id="retailerData">
                                                <?php
                                                    foreach($retailer as $data){
                                                        $name=$data['retailerName'].' : '.$data['routeName'].' : '.$data['compName'].' : '.$data['retailerCode'];
                                                ?>   
                                                
                                                <option value="<?php echo $name;?>"/>
                                                <?php    
                                                    }
                                                ?>
                                            </datalist>
                                            </div>
                                            <p id="billNo_Id"></p>
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-3">
                                        <b>Company</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text"  autocomplete="off" id="company" name="company" class="form-control date" placeholder="Enter company name" list="compData" required>
                                                <datalist id="compData">
                                                <?php
                                                    foreach($company as $data){
                                                        $name=$data['name'];
                                                ?>   
                                                
                                                <option id="<?php echo $data['id'];?>" value="<?php echo $name;?>"/>
                                                <?php    
                                                    }
                                                ?>
                                            </datalist>
                                            </div>
                                            <p id="billNo_Id"></p>
                                        </div>
                                    </div> -->

                                    <div class="col-md-3">
                                        <b>From Date</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="date" autocomplete="off" id="fromDate" name="fromDate" value="<?php echo date('Y-m-d',strtotime("-1 year")) ; ?>" class="form-control date" placeholder="Enter date" list="compData" required>
                                            </div>
                                            <p id="billNo_Id"></p>
                                        </div>
                                    </div>

                                     <div class="col-md-3">
                                        <b>To Date</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="date" autocomplete="off" id="toDate" name="toDate" value="<?php echo date('Y-m-d'); ?>" class="form-control date" placeholder="Enter date" list="compData" required>
                                            </div>
                                            <p id="billNo_Id"></p>
                                        </div>
                                    </div>
                                  
                                        <div class="col-md-3">
                                            <button id="searchInfo" class="btn btn-xs btn-primary m-t-15 waves-effect">
                                                <i class="material-icons">search</i> 
                                                <span class="icon-name">
                                                 Search
                                                </span>
                                            </button>
                                           <a href="<?php echo site_url('AdHocController/retailerHistory');?>">
                                                <button type="button" class="btn btn-xs btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">cancel</i> 
                                                    <span class="icon-name"> Cancel</span>
                                                </button>
                                            </a> 
                                        </div>

                                        
                                    </div> 
                                    <div id="hideInfo" class="col-md-12"> 
                                    </div>                                
                                <!-- </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
  <div class="modal fade" id="billprocessModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <center><h4 class="modal-title">Bill History </h4></center>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
           <!-- <a href="<?php echo site_url('AdHocController/retailerHistory');?>">
                <button type="button" class="btn btn-primary m-t-15 waves-effect">
                    <i class="material-icons">cancel</i> 
                </button>
            </a>  -->
          </div>
          <div class="modal-body">
        
            <div class="body">
                <div class="demo-masked-input">
                    <div class="row clearfix">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <b>Bill No : </b> <span id='bill_no'></span>
                                    <input type="hidden" id="currentBillNo" autocomplete="off" name="currentBillNo" class="form-control"> 
                                    <input type="hidden" id="currentBillId" autocomplete="off" name="currentBillId" class="form-control"> 
                                     <input type="hidden" id="currentBillRetailer" autocomplete="off" name="currentBillRetailer" class="form-control">    
                                </div> 

                                <div class="col-md-3">
                                    <b>Retailer : </b>
                                        <span id='bill_retailer'></span>
                                </div> 

                                <div class="col-md-3">
                                    <b>GST No. : </b>
                                        <span id='gst'></span>
                                </div>

                                 <div class="col-md-3">
                                    <b>Pending Amount : </b><span id='bill_pendingAmt'></span>
                                    <input type="hidden" id="currentPendingAmt" autocomplete="off" name="currentPendingAmt" class="form-control">   
                                </div>
                            </div>

                            <div id="hideBillHistoryInfo" class="col-md-12"> 
                            </div>
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



<script type="text/javascript">
    $(document).on('keypress','#retailer',function(e){
        var keyCode = (event.keyCode ? event.keyCode : event.which);   
        if (keyCode == 13) {
            var retailer=$('#retailer').val();
            var fromDate=$('#fromDate').val();
            var toDate=$('#toDate').val();
            // alert(retailer);
            if(retailer=='' || fromDate=='' || toDate==''){
                alert('Please enter all details.');
            }else{
                $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('AdHocController/retailerHistoryInfo');?>",
                    data:{retailer:retailer,fromDate:fromDate,toDate:toDate},
                    success: function (data) {
                        $('#hideInfo').html(data);
                    }  
                });
            }
        }
        
    });
</script>

<script type="text/javascript">
    $(document).on('click','#searchInfo',function(){
        var retailer=$('#retailer').val();
        var fromDate=$('#fromDate').val();
        var toDate=$('#toDate').val();
        // alert(retailer);
        if(retailer=='' || fromDate=='' || toDate==''){
            alert('Please enter all details.');
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('AdHocController/retailerHistoryInfo');?>",
                data:{retailer:retailer,fromDate:fromDate,toDate:toDate},
                success: function (data) {
                    $('#hideInfo').html(data);
                }  
            });
        }
        
    });
</script>

<script type="text/javascript">
    $(document).on('click','#billHistory',function(){
        var id=$(this).attr('data-id');
        var billNo=$(this).attr('data-billNo');
        var retailerName=$(this).attr('data-retailerName');
        var pendingAmt=$(this).attr('data-pendingAmt');
        var gst=$(this).attr('data-gst');
        
        $('#currentPendingAmt').val(pendingAmt);
        $('#currentBillId').val(id);
        $('#currentBillNo').val(billNo);
        $('#currentBillRetailer').val(retailerName);
        $('#bill_no').text(billNo);
        $('#gst').text(gst);
       
        $('#bill_retailer').text(retailerName);
        $('#bill_pendingAmt').text(pendingAmt);

        $.ajax({
            type: "POST",
            url:"<?php echo site_url('AdHocController/retailerbillInfo');?>",
            data:{billNo:billNo,billId:id},
            success: function (data) {
                $('#hideBillHistoryInfo').html(data);
            }  
        });
       


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
