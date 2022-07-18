<?php $this->load->view('/layouts/commanHeader'); ?>


        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                    Salesmans Stock
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Salesmans Stock Master
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                         <th>Sr.No</th>
                                         <th>Salesman</th>
                                <?php
                                        $no=0;
                                        foreach ($prod as $data) 
                                        {
                                ?>
                                            <th><?php echo $data['name']; ?></th>
                                <?php
                                        }
                                ?> 
                                     </tr>
                                 </thead>
                                 <tfoot>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>Salesman</th>
                                <?php
                                        $no=0;
                                        foreach ($prod as $data) 
                                        {
                                ?>
                                            <th><?php echo $data['name']; ?></th>
                                <?php
                                        }
                                ?> 
                                    </tr>
                                </tfoot>
                                    <tbody>
                                   <tr>
                                      <?php
                                        $no=0;
                                        foreach ($emp as $data) 
                                          {
                                          $no++;
                                    ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td><?php echo $data['name']; ?></td>
                                            
                                        </tr>
                                    <?php
                                        }
                                      ?> 
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
