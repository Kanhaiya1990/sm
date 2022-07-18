<?php $this->load->view('/layouts/commanHeader'); ?>

          <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <!-- <section class="content"> -->
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                    Outlet Summery Master
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Upload Outlet Summery
                            </h2>
                        
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                            <form method="post" role="form"  enctype="multipart/form-data"  action="<?php echo site_url('ExcelController/routeRetailerImport');?>"> 
                             <!-- <?= form_open_multipart(base_url('index.php/ExcelController/excelImport2'))?> -->
                                <div class="col-md-12">
                                     <div class="col-md-4">
                                        <b> Company </b>
                                         <div class="input-group">
                                         <select name="company" class="form-control" id="company">
                                            <option>--select Company--</option>
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
                                    <div class="col-md-4">
                                        <b> Excel </b>
                                         <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-excel-o"></i></span>
                                             <input type="file" name="file" class="form-control" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">                               
                                          </div>
                                    </div>
                                </div>
                                <div>
                                    <button type="submit" class="btn bg-primary margin"><i class="fa fa-upload"></i> &nbsp Import</button>
                                </div>
                            </form>
                            <!-- <?= form_close()?>     -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Examples -->  
        </div>
    </section>
<?php $this->load->view('/layouts/footerDataTable'); ?>
