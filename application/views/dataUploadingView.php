<?php $this->load->view('/layouts/commanHeader'); ?>

<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
<section class="col-md-12 box" style="height: auto;">
    <div class="container-fluid">
            
    <div style="display:none" class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait data is uploading...</p>
        </div>

        <div id="progress_wrapper" class="d-none">
          <label id="progress_status"></label>
          <div class="progress mb-3">
            <div id="progress" class="progress-bar" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>
        <div id="alert_wrapper"></div>
    </div>
  
        <!-- Masked Input -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                         Upload Bills Data
                        </h2>
                    </div>
                    <div class="row clearfix">
                        <div class="body">
                        <form id="uploadForm" enctype="multipart/form-data"> 
                            <!-- <form method="post" role="form"  enctype="multipart/form-data"  action="<?php echo site_url('CompanyDataUploadingController/uploadFilesForImport');?>">  -->
                            <div class="col-md-12 cust-tbl">
                                <div class="col-md-3">
                                    <b> Company </b>
                                     <div class="input-group">
                                     <select name="company" class="form-control" id="excelcompany" required>
                                        <option value=''>--select Company--</option>
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
                                    <b id="billsTitle"> Bills </b>
                                    <div class="input-group">
                                        <span class="input-group-addon"></span>
                                         <input type="file" id="billFile" name="billFile" required class="form-control"  accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                    </div>
                                </div>

                                <div id="bdetail" class="col-md-3">
                                    <b id="billDetailsTitle"> Bills Details </b>
                                    <div class="input-group">
                                        <span class="input-group-addon"></span>
                                         <input type="file" id="billDetailFile" name="billDetailFile" class="form-control" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                    </div>
                                </div>

                                <div class="col-md-3" id="rdetail">
                                    <b id="retailersTitle"> Retailer Details </b>
                                    <div class="input-group">
                                        <span class="input-group-addon"></span>
                                         <input type="file" id="retailerDetailFile" name="retailerDetailFile" class="form-control" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                    </div>
                                </div>

                                      
                                
                            </div>
                            <div class="col-md-12">
                           
                                <div class="col-md-3">
                                    <div class="progress">
                                        <div class="progress-bar"></div>
                                    </div>
                                    <div id="uploadStatus"></div>
                                    <p id="res" style="color:red"></p>
                                    <input type="hidden" name="dateForUpload" id="dateForUpload" class="form-control">
                                    <div class="input-group">
                                      <button type="submit" class="btn btnStyle m-t-15 waves-effect margin">Import</button>&nbsp;
                                      <button type="button" class="btn btn-danger m-t-15 waves-effect margin" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                                <!-- Progress bar -->
                                
                            </div>
                            </form>

                            <button style="display:none;" data-toggle="modal" data-target="#addRetailerModal" id="routeRefresh" class="btn btnStyle margin">Refresh Routes & Salesman</button>
                                
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container">
  <div class="modal fade" id="addRetailerModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <center><h4 class="modal-title" id="stsAddSub">Outlet Summary Upload</h4></center>
          </div>
          <div class="modal-body">
            <form method="post" role="form" enctype="multipart/form-data" action="<?php echo site_url('BillTransactionController/uploadRetailersDataElectricals'); ?>"> 
                      <div class="row clearfix">
                          <div class="body">

                            <div class="demo-masked-input">
                                  <input id="addProdQtyId" type="hidden" name="addProdQtyId">
                                  <div class="col-md-12">
                                    <div class="col-md-6">
                                        <b> Outlet Summary Details </b>
                                        <div class="input-group">
                                            <span class="input-group-addon"></span>
                                            <input type="file" id="billFile3" name="billFile3" class="form-control" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                        </div>
                                    </div>
                                </div>
                                  
                                 <div class="col-md-12">
                                    <div class="row clearfix">
                                        <div class="col-md-8">
                                            <button type="submit" class="btn btnStyle m-t-15 waves-effect">Import</button>
                                           
                                            <button data-dismiss="modal" type="button" class="btn btn-danger m-t-15 waves-effect">
                                               
                                                <span class="icon-name"> Cancel</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>                             
                            </div>
                        </div>
                    </div>
                  </form>
          </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('/layouts/footerDataTable'); ?>

<script type="text/javascript">
  $(document).on("change","#excelcompany",function() {
        var company=$('#excelcompany').val();
        if(company===""){
          $('#billsTitle').html('Bills');
          $('#billDetailsTitle').html('Bills Details');
          $('#retailersTitle').html('Retailers Details');
          $("#bdetail").css("display", "block");
        }else if(company==="Nestle"){
          $('#billsTitle').html('Sales Report Billwise<span style="color:red"> *</span>');
          $('#billDetailsTitle').html('Master Sales Report<span style="color:red"> *</span>');
          $('#retailersTitle').html('Retailer Master Report');
          $("#bdetail").css("display", "block");
        }else if(company==="Parle"){
            $('#billsTitle').html('Parle bill wise customer wise<span style="color:red"> *</span>');
            $('#billDetailsTitle').html('Parle Bill wise Product wise Report<span style="color:red"> *</span>');
            $('#retailersTitle').html('Retailers Details');
            $("#bdetail").css("display", "block");
        }else if(company==="ITC"){
           $("#bdetail").css("display", "none");
            // $('#billsTitle').html('Invoice Report Using F8<span style="color:red"> *</span>');
            // $('#billDetailsTitle').html('Invoice Report<span style="color:red"> *</span>');
            // $('#retailersTitle').html('Retailers Details');
        }else if(company==="Jockey"){
            $('#billsTitle').html('Reports File<span style="color:red"> *</span>');
            $('#billDetailsTitle').html('sfa new with all parties<span style="color:red"> *</span>');
            $('#retailersTitle').html('sfa new with all parties');
            $("#billDetailsTitle").css("display", "none");
            $("#bdetail").css("display", "none");
            $("#routeRefresh").css("display", "block");
        }else if(company==="Marico"){
            $('#billsTitle').html('Bill Wise-Sales Value Report <span style="color:red"> *</span>');
            $('#billDetailsTitle').html('Bill Wise Item Wise Sales Report<span style="color:red"> *</span>');
            $('#retailersTitle').html('Retailer Master');
            $("#bdetail").css("display", "block");
        }else if(company==="Havells"){
            $('#billsTitle').html('Bills Report <span style="color:red"> *</span>');
            // $('#billDetailsTitle').html('Bill Wise Item Wise Sales Report<span style="color:red"> *</span>');
            // $('#retailersTitle').html('Outlet Summary <span style="color:red"> *</span>');
            $("#bdetail").css("display", "none");
            $("#rdetail").css("display", "none");
            $("#routeRefresh").css("display", "block");

            // routeRefresh
        }
  });
</script>

<script type="text/javascript">
    $(document).on("change","#excelcompany",function() {
        $('#res').html('');
        $('#dateForUpload').val('');
        var company=$('#excelcompany').val();
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('CompanyDataUploadingController/checkDatesForCompany');?>",
            
            data:{"company" : company},
            success: function (data) {
                // alert(data);
                var data=$.parseJSON(data);
                $('#res').html(data.message);
                $('#dateForUpload').val(data.date);
            }  
        });
    });
</script>

<script>
$(document).ready(function(){
    // File upload via Ajax
    $("#uploadForm").on('submit', function(e){
        e.preventDefault();
         $('.page-loader-wrapper').show();
        $.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = ((evt.loaded / evt.total) * 100);
                        $(".progress-bar").width(percentComplete + '%');
                        $(".progress-bar").html(percentComplete+'%');
                    }
                }, false);
                return xhr;
            },
            type: 'POST',
            // url:"<?php echo site_url('DataUploadingController/importAllFiles');?>",
            url:"<?php echo site_url('CompanyDataUploadingController/uploadFilesForImport');?>",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
                $(".progress-bar").width('0%');
            },
            error:function(resp){
                alert(resp);
            },
            success: function(resp){
                alert(resp);
                window.location.reload(); 
            }
        });
    });
    
    // File type validation
    $("#billFile").change(function(){
        var allowedTypes = ['text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        var file = this.files[0];
        var fileType = file.type;
        if(!allowedTypes.includes(fileType)){
            alert('Please select a valid file (XLSX).');
            $("#billFile").val('');
            return false;
        }
    });

     $("#billDetailFile").change(function(){
        var allowedTypes = ['text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        var file = this.files[0];
        var fileType = file.type;
        if(!allowedTypes.includes(fileType)){
            alert('Please select a valid file (XLSX).');
            $("#billDetailFile").val('');
            return false;
        }
    });

     $("#retailerDetailFile").change(function(){
        var allowedTypes = ['text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        var file = this.files[0];
        var fileType = file.type;
        if(!allowedTypes.includes(fileType)){
            alert('Please select a valid file (XLSX).');
            $("#retailerDetailFile").val('');
            return false;
        }
    });

     // File upload via Ajax
    // $("#uploadForm").on('submit', function(e){
    //     e.preventDefault();
    //     $.ajax({
    //         type: 'POST',
    //         url:"<?php echo site_url('DataUploadingController/importAllFiles');?>",
    //         data: new FormData(this),
    //         contentType: false,
    //         cache: false,
    //         processData:false,
    //         success: function(resp){
    //             alert(resp);die();
    //             // window.location.reload(); 
    //         },
    //         error:function(){
    //         }
    //     });
    // });
});
</script>

<!-- <script type="text/javascript">
    $(document).on('click','#insRetailer',function(){
        $.ajax({
            url: "<?php echo site_url('BillTransactionController/refreshRoutesAndSalesman');?>",
            type: "post",
            data:{},
            success: function (response) {
                alert(response);die();
                // $('#status-id'+rowNo).replaceWith(response);
                window.location.href="<?php echo base_url();?>index.php/CompanyDataUploadingController";
            }
        });
    });
</script> -->