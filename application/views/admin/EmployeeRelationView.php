<?php $this->load->view('/layouts/commanHeader'); ?>
<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
<section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
           
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Employee Assign Role
                            </h2>
                            <h2>
                                <p align="right">
                                  <a href="<?php echo site_url('admin/EmployeeRelationController/Add');?>">
                                    <button type="submit" class="btn bg-primary margin"><i class="material-icons">person_add</i>  Add  </button></a> 
                                </p> 
                            </h2>
                       
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th> Sr.No</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Action </th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                         <tr>
                                            <th> Sr.No</th>                                            
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Action </th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        $no=0;
                                        foreach ($emprole as $data) 
                                          {
                                           $no++; 
                                        ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td><?php echo $data['eName']; ?></td>
                                            <td><?php echo $data['email']; ?></td>
                                            <td><?php echo $data['rName']; ?></td> 
                                           
                                            <td>
                                                <a href="<?php echo base_url().'index.php/admin/EmployeeRelationController/load/'.$data['employeeId']; ?>">
                                                <i class="material-icons" style="color: green;">edit</i>
                                                </a>
                                            </td>
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