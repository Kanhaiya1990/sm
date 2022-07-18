<?php $this->load->view('/layouts/commanHeader'); ?>
 
    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                <h2 style="text-align: center;">
                   E-Vouchers 
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                              E-Vouchers
                            </h2>
                             <h2>
                                <!-- <p align="right">
                                  <a href="<?php echo site_url('cashier/CashBookController/PdfEVauchers');?>">
                                    <button type="submit" class="btn bg-primary margin"><i class="material-icons">picture_as_pdf</i>  PDf  </button></a> &nbsp
                                    <a href="<?php echo site_url('cashier/CashBookController/PdfDebitVauchers');?>">
                                    <button type="submit" class="btn bg-primary margin"><i class="material-icons">picture_as_pdf</i>  Debit Vauchers PDf  </button></a> 
                                </p>  -->
                               
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Date</th>
                                            <th>Voucher Number</th>
                                            <th>Nature</th>
                                            <th>Category</th>
                                            <th>Account</th>
                                            <th>Amount</th>
                                            <th>PDF</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Date</th>
                                            <th>Voucher Number</th>
                                            <th>Nature</th>
                                            <th>Category</th>
                                            <th>Account</th>
                                            <th>Amount</th>
                                            <th>PDF</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    
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