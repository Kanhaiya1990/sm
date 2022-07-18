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
                            <h2 align="center">
                                <?php if(!empty($bills)){ ?>
                                    Retailers Account Statement
                                    <br><br>
                                    <?php echo $bills[0]['retailerName']; ?>
                                <?php }else{ ?>
                                    Retailers Account Statement
                                <?php } ?>
                              
                            </h2>
                             
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Particular</th>
                                            <th>Ref No.</th>
                                            <th>Dr/Cr</th>
                                            <th>Amount</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Date</th>
                                            <th>Particular</th>
                                            <th>Ref No.</th>
                                            <th>Dr/Cr</th>
                                            <th>Amount</th>
                                            <th>Balance</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td>Opening Balance</td>
                                            <td></td>
                                            <td>Dr</td>
                                            <td>0</td>
                                            <td>0</td>
                                        </tr>
                                        <?php
                                            $no=0;
                                            foreach ($bills as $data) 
                                              {
                                              $no++;
                                        ?>
                                            <tr> 
                                                <td><?php echo date('d-m-Y',strtotime($data['date'])); ?></td>
                                                <td>Sale</td>
                                                <td><?php echo $data['billNo']; ?></td>
                                                <td>Dr</td>
                                                <td><?php echo $data['netAmount']; ?></td>
                                                <td><?php echo $data['pendingAmt']; ?></td>
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
