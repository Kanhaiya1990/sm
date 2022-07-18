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

    /*.logo_prov {
        border-radius: 30px;
         border: 1px solid black;
        background: red;
        color: black;
        padding: 6px;
        width: 50px;
        height: 50px;
    }*/

</style>

    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>    
    <section class="col-md-12 box">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                              Period Daybook Report
                            </h2>
                        </div>
                      
                        <div class="body">
                            <form method="post" role="form" action="<?php echo site_url('manager/CashBookController/downloadPeriodDayBookExcel');?>"> 
                                <div class="row clearfix">

                                    <div class="col-md-12 cust-tbl"> 
                                        <div class="col-md-3">
                                            <b>From Date</b><span style="color:red"> *</span>
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
                                            <b>To Date</b><span style="color:red"> *</span>
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
                                                <button class="btn btn-primary btnStyle m-t-15 waves-effect">
                                                    <i class="material-icons">search</i> 
                                                    <span class="icon-name">
                                                    Download
                                                    </span>
                                                </button>
                                            <a href="<?php echo site_url('manager/CashBookController/downloadPeriodDayBook');?>">
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
            </div>
        </div>
    </section>

<?php $this->load->view('/layouts/footerDataTable'); ?>