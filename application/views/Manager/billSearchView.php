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
           
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                              Bill Search
                            </h2>
                            
                        </div>
                      
                        <div class="body">
                            
                            <div class="row clearfix">
                            <!-- <div class="demo-masked-input"> -->
                                
                                  <div class="col-md-12 cust-tbl"> 
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
                                            <button id="searchInfo" class="btn btnStyle m-t-15 waves-effect">
                                                <i class="material-icons">search</i> 
                                                <span class="icon-name">
                                                 Search
                                                </span>
                                            </button>
                                           <a href="<?php echo site_url('AdHocController/billSearch');?>">
                                                <button type="button" class="btn btn-danger m-t-15 waves-effect">
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

<?php $this->load->view('/layouts/footerDataTable'); ?>

<?php $this->load->view('/layouts/processButtonView'); ?>

<script type="text/javascript">
    $(document).on('click','#searchInfo',function(){
        var billNo=$('#billNo').val();
        var charCount=$('#billNo').val().length;

        if(charCount<4){
            alert('Please enter first 4 characters');die();
        }else{
            if(billNo===''){
                $('#hideInfo').html('');
                $('#billNo').focus();
            }else{
                $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('AdHocController/findBillsData');?>",
                    data:{billNo:billNo},
                    success: function (data) {
                        // alert(data);
                        $('#hideInfo').html(data);
                        $('#billNo').val('');
                        $('#billNo').focus();
                    }  
                });
            }
        }
       
        
    });
</script>

<script type="text/javascript">
    $(document).on('keypress','#billNo',function(e){
        if (e.keyCode == 13) {
            var billNo=$('#billNo').val();
            var charCount=$('#billNo').val().length;
            if(charCount<4){
                alert('Please enter first 4 characters');die();
            }else{
                if(billNo===''){
                    $('#hideInfo').html('');
                    $('#billNo').focus();
                }else{
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('AdHocController/findBillsData');?>",
                        data:{billNo:billNo},
                        success: function (data) {
                            // alert(data);
                            $('#hideInfo').html(data);
                            // $('#billNo').val('');
                            // $('#billNo').focus();
                        }  
                    });
                }
            }
        }
    });
</script>