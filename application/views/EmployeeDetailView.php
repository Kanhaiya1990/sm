<?php $this->load->view('/layouts/commanHeader'); ?>

        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                     Employee Details
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Employee Details Master
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>EmpCode</th>
                                            <th>Employee Name</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Address</th>
                                            <th>Joining Date</th>
                                            <th>Designation</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Sr.No</th>
                                           <th>Employee Name</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Address</th>
                                            <th>Joining Date</th>
                                            <th>Designation</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                              $no=0;
                                              foreach ($emp as $data) 
                                                {
                                                  $id=$data['id'];
                                                 $no++; 
                                          ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td>
                                            <!-- <a href="<?php echo base_url().'index.php/cashier/CashBookController/load/'.$data['id']; ?>"> -->
                                                <?php echo $data['code']; ?>
                                            <!-- </a> -->
                                        </td>
                                        <!-- <td><?php
                                            $dt=date_create($data['date']);
                                            $data['date'] = date_format($dt,'d-m-Y');
                                            echo $data['date']; ?>
                                        </td> -->
                                        <td><?php echo $data['name']; ?></td>
                                        <td><?php echo $data['email']; ?></td>
                                        <td><?php echo $data['mobile']; ?></td>
                                        <td><?php echo $data['localAddress']; ?></td>
                                        <td><?php
                                            $dt=date_create($data['joiningDate']);
                                            $data['date'] = date_format($dt,'d-m-Y');
                                            echo $data['date']; ?>
                                        </td>
                                        <td><?php echo $data['designation']; ?></td>
                                       
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
