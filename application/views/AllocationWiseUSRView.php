<?php $this->load->view('/layouts/commanHeader'); ?>

        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                    Allocation Wise USR Master
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Allocation Wise USR Master
                            </h2>
                           
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Allocation No.</th>
                                            <th>Route</th>
                                             <th>Employee Names</th>
                                           
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                           <th>S. No.</th>
                                            <th>Allocation No.</th>
                                            <th>Route</th>
                                             <th>Employee Names</th>
                                            
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                      <?php
                                        $no=0;
                                        foreach ($allocations as $data) 
                                          {
                                               $no++; 
                                               $routeName='';
                                               $rtName=explode(",",rtrim($data['routeCode'],','));
                                               for($i=0;$i<count($rtName);$i++){
                                                    $routes=$this->SrModel->getRouteName($rtName[$i]);
                                                    if(!empty($routes)){
                                                        $routeName=$routeName.' '.$routes[0]['name'].', ';
                                                    }
                                               }

                                                $employee="";
                                                if(($data['fieldStaffCode1'] !='0')){
                                                    $emp=$this->SrModel->getEmployeeNamesByID($data['fieldStaffCode1']);
                                                    $employee= $employee.$emp.', ';
                                                }
                                                if(($data['fieldStaffCode2'] !='0')){
                                                    $emp=$this->SrModel->getEmployeeNamesByID($data['fieldStaffCode2']);
                                                    $employee=$employee.$emp.', ';
                                                }
                                                // echo $emp;
                                                // exit;
                                           ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td>
                                                <a href="<?php echo base_url().'index.php/SrController/AllocationWiseUSR/'.$data['id']; ?>"><?php echo $data['allocationCode']; ?></a>
                                            </td>
                                            
                                            <td><?php echo rtrim($routeName,', '); ?></td> 
                                            <td><?php echo rtrim($employee,', '); ?></td>
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