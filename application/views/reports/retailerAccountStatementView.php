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
                               Retailer Account Statement Report
                            </h2>                       
                        </div>
                         <div class="body">
                            
                            <div class="row clearfix">
                            <!-- <div class="demo-masked-input"> -->
                                
                                  <div class="col-md-12 mb-0 cust-tbl"> 
                                    <div class="col-md-3">
                                            <b>From Date</b><span style="color:red">  *</span>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">account_box</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="date" autocomplete="off" autofocus id="fromDate" name="fromDate" class="form-control date" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <b>To Date</b><span style="color:red">  *</span>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">account_box</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="date" autocomplete="off" autofocus id="toDate" name="toDate" class="form-control date"  required>
                                                </div>
                                            </div>
                                        </div>

                                    <div class="col-md-3">
                                            <b>Retailer</b>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">account_box</i>
                                                </span>
                                                <div class="form-line">
                                                <input type="text" autocomplete="off" autofocus id="retailer" value="" name="retailer" class="form-control date" placeholder="Enter retailer" list="retailerList" required>
                                                </div>
                                                <datalist id="retailerList">
                                                    <?php
                                                        foreach($retailer as $data){
                                                            $name=$data['name'];
                                                    ?>   
                                                    <option value="<?php echo $name;?>"/>
                                                    <?php    
                                                        }
                                                    ?>
                                                </datalist>
                                            </div>
                                        </div>
                                    
                                  
                                        <div class="col-md-3">
                                            <button id="searchInfo" class="btn btn-xs btn-primary btnStyle m-t-25 waves-effect">
                                                <i class="material-icons">search</i> 
                                                <span class="icon-name">
                                                 Search
                                                </span>
                                            </button>
                                           <a href="<?php echo site_url('admin/BillTransactionController');?>">
                                                <button type="button" class="btn btn-sm btn-danger m-t-25 waves-effect">
                                                    <i class="material-icons">cancel</i> 
                                                    <span class="icon-name"> Cancel</span>
                                                </button>
                                            </a> 
                                        </div>
                                    </div>
                                    
                                    <div class="row clearfix">
                                    <div id="hideInfo" class="col-md-12"> 
                                        
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
    
<?php $this->load->view('/layouts/footerDataTable'); ?>

<script type="text/javascript">
    $(document).on('click','#searchInfo',function(){
        var fromDate=$('#fromDate').val();
        var toDate=$('#toDate').val();
        var retailer=$('#retailer').val();
      // alert(retailer);
        // var billId = $('#billData').find('option[value="'+billNo+'"]').attr('id');
        if(fromDate==='' || toDate==='' || retailer===''){
            $('#hideInfo').html('');
            $('#fromDate').focus();
            $('#toDate').focus();
            $('#retailer').focus();
        }else{
            $.ajax({
              type: "POST",
              url:"<?php echo site_url('reports/ReportController/retailorWiseBillReport');?>",
                data:{fromDate:fromDate,toDate:toDate,retailer:retailer},
                success: function (data) {
                    // alert(data);
                $('#hideInfo').html(data);
                $('#fromDate').val('');
                $('#toDate').val('');
                $('#retailer').val('');
                $('#fromDate').focus();
                $('#toDate').focus();
                $('#retailer').focus();
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
</script>




