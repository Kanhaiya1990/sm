<?php $this->load->view('/layouts/commanHeader'); ?>
  
    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                <h2 style="text-align: center;">
                    Cash Book-Peroid Day Book <?php echo date('d-M-Y');?>
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
               <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                    <div class="col-md-4">
                                      <b>From Date</b>
                                        <div class="input-group">
                                        <span class="input-group-addon">
                                           <i class="material-icons">money</i>
                                        </span>
                                        <div class="form-line">
                                          <input type="date" name="frmDt" class="form-control date" required="">
                                        </div>
                                      </div>
                                    </div> 
                                    <div class="col-md-4">
                                      <b>To Date</b>
                                        <div class="input-group">
                                        <span class="input-group-addon">
                                           <i class="material-icons">money</i>
                                        </span>
                                        <div class="form-line">
                                          <input type="date" name="toDt" class="form-control date" required>
                                        </div>
                                      </div>
                                    </div>     
                                   
                             
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="header">
                            <h2>
                              Receipt
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <td>Sr.No</td>
                                            <th>Name</th>
                                            <th>Allocation</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" style="text-align: right;">Total</th>
                                            <th>0.000</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php
                                        $no=0;
                                        foreach ($company as $data) 
                                          {
                                           $no++; 
                                    ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td><?php echo $data['id']; ?></td>
                                            <td><?php echo $data['name']; ?></td>
                                            <td><?php echo $data['name']; ?></td>
                                        </tr>
                                    <?php
                                        }
                                      ?> 
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                 <div class="col-lg-6">
                    <div class="card">
                        <div class="header">
                            <h2>
                              Expence
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <td>Sr.No</td>
                                            <th>Name</th>
                                            <th>Allocation</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                     <tfoot>
                                        <tr>
                                            <th colspan="3" style="text-align: right;">Total</th>
                                            <th>0.000</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php
                                        $no=0;
                                        foreach ($company as $data) 
                                          {
                                           $no++; 
                                    ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td><?php echo $data['id']; ?></td>
                                            <td><?php echo $data['name']; ?></td>
                                            <td><?php echo $data['name']; ?></td>
                                        </tr>
                                    <?php
                                        }
                                      ?> 
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                 <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                              Receipt
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                    <div class="col-md-4">
                                      <b>Opening Balance</b>
                                        <div class="input-group">
                                        <span class="input-group-addon">
                                           <i class="material-icons">money</i>
                                        </span>
                                        <div class="form-line">
                                          <input type="text" name="openingBalance" class="form-control date" placeholder="Enter the Opening Amount">
                                        </div>
                                      </div>
                                    </div> 
                                    <div class="col-md-4">
                                      <b>Expence</b>
                                        <div class="input-group">
                                        <span class="input-group-addon">
                                           <i class="material-icons">money</i>
                                        </span>
                                        <div class="form-line">
                                          <input type="text" name="expenceBalance" class="form-control date" placeholder="Enter the expence Amount">
                                        </div>
                                      </div>
                                    </div> 
                                    <div class="col-md-12"></div>
                                    <div class="col-md-4">
                                      <b>Receipt</b>
                                        <div class="input-group">
                                        <span class="input-group-addon">
                                           <i class="material-icons">money</i>
                                        </span>
                                        <div class="form-line">
                                          <input type="text" name="receipt" class="form-control date" placeholder="Enter the Receipt">
                                        </div>
                                      </div>
                                    </div> 
                                    <div class="col-md-4">
                                      <b>Closing Balance</b>
                                        <div class="input-group">
                                        <span class="input-group-addon">
                                           <i class="material-icons">money</i>
                                        </span>
                                        <div class="form-line">
                                          <input type="text" name="closingBalance" class="form-control date" placeholder="Enter the expence Amount">
                                        </div>
                                      </div>
                                    </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Examples -->  
        </div>

<?php $this->load->view('/layouts/footerDataTable'); ?>