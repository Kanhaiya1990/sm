<?php $this->load->view('/layouts/commanHeader'); ?>
 
    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                              Retailers Account Statement
                            </h2>
                             <h2>
                               
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Retailer Name</th>
                                            <th>Outstanding</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Retailer Name</th>
                                            <th>Outstanding</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        
                                      <?php
                                        $no=0;
                                        foreach ($bills as $data) 
                                          {
                                          $no++;
                                    ?>
                                        <tr> 
                                            <td><?php echo $no; ?></td>
                                            <td class="fixed-side"><a href="<?php echo site_url('DeliverySlipController/retailerWiseAccountStatement/').$data['name'];?>"><?php echo $data['name']; ?></a></td>
                                            <td class="fixed-side"><?php echo $data['pendingAmt']; ?></td>
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
