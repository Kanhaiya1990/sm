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
                               Allocation Wise SR/FSR Detail
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-exportable dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Allocation No.</th>
                                            <th>Company</th>
                                            <th>Time</th>
                                            <th>Employee</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Allocation No.</th>
                                            <th>Company</th>
                                            <th>Time</th>
                                            <th>Employee</th>
                                            <th>Details</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        
                                <?php 
                                    $no=0;
                                        if(!empty($pendingSr)){
                                            foreach ($pendingSr as $data) 
                                            {
                                                $allocation_Id=$data['allocation_Id'];
                                                $no++; 
                                ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $data['code'].' : '.$data['routeName'].' : '.$data['empName']; ?></td>
                                        <td><?php echo $data['compName']; ?></td>
                                        <td><?php
                                                $dt=date_create($data['endDate']);
                                                $date = date_format($dt,'d-M-Y H:i:sa');
                                                echo $date;
                                            ?>
                                        </td>
                                        <td><?php echo $data['empName']; ?></td>
                                        <td>
                                            <a href="<?php echo site_url('manager/SrCheckController/pendingAllocationSr/'.$allocation_Id); ?>"><button class="btn btn-xs bg-primary margin"><i class="material-icons">visibility</i></button></a>
                                        </td>
                                   </tr>  
                                <?php
                                            }
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
