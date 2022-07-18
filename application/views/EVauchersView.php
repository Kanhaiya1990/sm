<?php $this->load->view('/layouts/commanHeader'); ?>
  
    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                <h2 style="text-align: center;">
                    Cash Book-E-Vauchers 
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                              E-Vauchers
                            </h2>
                             <h2>
                                <p align="right">
                                  <a href="<?php echo site_url('cashier/CashBookController/PdfEVauchers');?>">
                                    <button type="submit" class="btn bg-primary margin"><i class="material-icons">picture_as_pdf</i>  PDf  </button></a> &nbsp
                                    <a href="<?php echo site_url('cashier/CashBookController/PdfDebitVauchers');?>">
                                    <button type="submit" class="btn bg-primary margin"><i class="material-icons">picture_as_pdf</i>  Debit Vauchers PDf  </button></a> 
                                </p> 
                                </p> 
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
                 
            </div>
            <!-- #END# Basic Examples -->  

        </div>
    </section>
<?php $this->load->view('/layouts/footerDataTable'); ?>
