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
            <!-- <div class="row clearfix"> -->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                              Stock Movement Report
                            </h2>
                        </div>
                      
                        <div class="body">
                            <form method="post" role="form" action="<?php echo site_url('reports/ReportController/deliveryslipProductReport');?>"> 
                                <div class="row clearfix">

                                    <div class="col-md-12"> 

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
                                                    <input type="date" autocomplete="off" autofocus id="toDate" name="toDate" class="form-control date" required>
                                                </div>
                                            </div>
                                        </div>
<!-- 
                                        <div class="col-md-3">
                                            <b>Product Name</b>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">account_box</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="text" autocomplete="off" autofocus id="productName" value="All" name="productName" class="form-control date" placeholder="Enter Product" list="productNameList">
                                                </div>
                                                <datalist id="productNameList">
                                                    <?php
                                                        foreach($products as $data){
                                                            $name=$data['name'];
                                                    ?>   
                                                    <option value="<?php echo $name;?>"/>
                                                    <?php    
                                                        }
                                                    ?>
                                                </datalist>
                                            </div>
                                        </div> -->
                                        <!-- <div class="col-md-3">
                                            <input type="checkbox" name="prodName" value="prodName" class="form-check-input m-t-15  child3" id="exampleCheck10">
                                            <label class="form-check-label  m-t-25" for="exampleCheck10">With Product Name</label>&nbsp;&nbsp;
                                        </div> -->

                                        <div class="col-md-3">
                                                <button id="searchInfo" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">download</i> 
                                                    <span class="icon-name">Download</span>
                                                </button>
                                                <a href="<?php echo site_url('AdHocController/billSearch');?>">
                                                    <button type="button" class="btn btn-primary m-t-15 waves-effect">
                                                        <i class="material-icons">cancel</i> 
                                                        <span class="icon-name"> Cancel</span>
                                                    </button>
                                                </a> 
                                            </div>
                                        </div>    
                               
                                    </div>

                            </form>
                        </div>
                    </div>
                </div>
            <!-- </div> -->
        </div>
    </section>

<?php $this->load->view('/layouts/footerDataTable'); ?>

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
        }
       
        
    });
</script>