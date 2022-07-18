<?php $this->load->view('/layouts/commanHeader'); ?>

<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
<section class="col-md-12 box" style="height: auto;">
    <div class="container-fluid">
        <!-- Masked Input -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                         Upload Bills Transactions
                        </h2>
                    </div>
                    <div class="row clearfix">
                        <div class="body">
                        <form method="post" role="form"  enctype="multipart/form-data"  action="<?php echo site_url('owner/OfficeAllocationController/billsTransactionDataUploading');?>"> 
                            <div class="col-md-12">
                                <div class="col-md-5">
                                    <b id="billsTitle"> Bills Transaction File </b>
                                    <div class="input-group">
                                        <span class="input-group-addon"></span>
                                         <input type="file" id="billFile" name="billFile" required class="form-control"  accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                      <button type="submit" class="btn bg-primary m-d-15 margin">Import</button>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $this->load->view('/layouts/footerDataTable'); ?>