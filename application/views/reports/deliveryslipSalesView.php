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
                              Deliveryslip Sale Report
                            </h2>
                        </div>
                      
                        <div class="body">
                            <form method="post" role="form" action="<?php echo site_url('reports/ReportController/deliveryslipSalesReport');?>"> 
                                <div class="row clearfix">

                                    <div class="col-md-12 cust-tbl"> 

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
                                            <b>Salesman</b>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">account_box</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="text" autocomplete="off" autofocus id="salesman" value="All" name="salesman" class="form-control date" placeholder="Enter salesman" list="salesmanList">
                                                </div>
                                                <datalist id="salesmanList">
                                                    <?php
                                                        foreach($salesman as $data){
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
                                            <b>Retailer</b>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">account_box</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="text" autocomplete="off" autofocus id="retailer" value="All" name="retailer" class="form-control date" placeholder="Enter retailer" list="retailerList">
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

                                        

                                        <!-- <div class="col-md-2">
                                            <div class="input-group">
                                                <input type="radio" id="radio_1" name="category" value="summary" class="with-gap radio-col-red" checked/>
                                                <label for="radio_1">Summary</label>
                                                <br>
                                                <input type="radio" id="radio_2" name="category" value="detail" class="with-gap radio-col-red"/>
                                                <label for="radio_2">Detail</label>
                                            </div>
                                        </div> -->
                                    
                                       
                                    </div>

                                    <div class="col-md-12">
                                        <div class="col-md-4">
                                                <button id="searchInfo" class="btn btnStyle m-t-15 waves-effect">
                                                    <i class="material-icons">search</i> 
                                                    <span class="icon-name">Search</span>
                                                </button>
                                                <a href="<?php echo site_url('AdHocController/billSearch');?>">
                                                    <button type="button" class="btn btn-sm btn-danger m-t-15 waves-effect">
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
                    }  
                });
            }
        }
       
        
    });
</script>