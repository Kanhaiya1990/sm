<?php $this->load->view('/layouts/commanHeader'); ?>

<!--<meta http-equiv="refresh" content="300"/>-->
<style>
  .tableFixHead {
  overflow-y: auto;
  height: 500px;
}

.tableFixHead table {
  border-collapse: collapse;
  width: 100%;
}

.tableFixHead th,
.tableFixHead td {
  padding: 8px 16px;
}

.tableFixHead th {
  position: sticky;
  top: 0;
  background: #eee;
}
  </style>
        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/>
     <section class="col-md-12 box">
        <div class="container-fluid">
            <div class="block-header">
               
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Open Allocations Master
                            </h2>
                            <h2>
                                <p align="right">
                                     <button class="btn btn-sm bg-primary margin" onClick="window.location.reload();"><i class="material-icons">refresh</i></button>
                                  <a href="<?php echo site_url('AllocationByManagerController/Add');?>">
                                    <button class="btn bg-primary margin"><i class="material-icons">person_add</i>  Add New Allocation </button></a>
                                  <a href="<?php echo site_url('manager/OfficeAllocationController/addOfficeAllocationsBills');?>">
                                    <button class="btn bg-primary margin"><i class="material-icons">person_add</i>  Add Office Allocation </button></a>  
                                </p> 
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive tableFixHead">
                                <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Allocation No.</th>
                                            <th>Route</th>
                                             <th>Employees</th>
                                             <th>Deliveryman</th>
                                            <th>Manager Review</th>
                                            <th>SR Check</th>
                                            <th>Bills Check</th>
                                            <th>Cash & Cheques</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                           <th>S. No.</th>
                                            <th>Allocation No.</th>
                                            <th>Route</th>
                                              <th>Employees</th>
                                              <th>Deliveryman</th>
                                            <th>Manager Review</th>
                                            <th>SR Check</th>
                                            <th>Bills Check</th>
                                            <th>Cash & Cheques</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                      <?php
                                        $no=0;
                                        foreach ($allocations as $data) 
                                          {
                                               $no++; 

                                                $diff=strtotime(date('Y-m-d'))-strtotime($data['date']);
                                               $routeName='';
                                               $rtName=explode(",",rtrim($data['routeCode'],','));
                                               for($i=0;$i<count($rtName);$i++){
                                                    $routes=$this->AllocationByManagerModel->getRouteName($rtName[$i]);
                                                    if(!empty($routes)){
                                                        $routeName=$routeName.' '.$routes[0]['name'].', ';
                                                    }
                                               }

                                                $employee="";
                                                if(($data['fieldStaffCode1'] !='0')){
                                                    $emp=$this->AllocationByManagerModel->getEmployeeNamesByID($data['fieldStaffCode1']);
                                                    $employee= $employee.$emp.', ';
                                                }
                                                if(($data['fieldStaffCode2'] !='0')){
                                                    $emp=$this->AllocationByManagerModel->getEmployeeNamesByID($data['fieldStaffCode2']);
                                                    $employee=$employee.$emp.', ';
                                                }
                                                // echo $emp;
                                                // exit;
                                           ?>
                                          <?php if($diff>3){ ?>
                                                 <tr style="background-color: #b2e59e">
                                            <?php }else{ ?>
                                                 <tr>
                                            <?php } ?>
                                            <td><?php echo $no; ?></td>
                                            <td>
                                            <?php if($data['fsStatus']==0){ ?>
                                                <a href="<?php echo base_url().'index.php/AllocationByManagerController/CompleteAllocation/'.$data['id']; ?>"><?php echo $data['allocationCode']; ?></a>
                                            <?php }else{ ?>
                                                <a href="<?php echo base_url().'index.php/AllocationByManagerController/allocationDetailsCheck/'.$data['id']; ?>"><?php echo $data['allocationCode']; ?></a>
                                            <?php } ?>  
                                                
                                            </td>
                                            
                                            <td><?php echo rtrim($routeName,', '); ?></td> 
                                            <td><?php echo rtrim($employee,', '); ?></td>

                                  <?php if($data['fsStatus']==0){?>
                                            <td></td>
                                  <?php }else if($data['fsStatus']==1){  ?>
                                            <td><center><i class="material-icons">check</i></center></td> 
                                  <?php }else{ ?> 
                                            <td></td>
                                  <?php } ?>

                                  <?php if($data['fsStatus']==1 && $data['managerHisaabStatus']==0 && $data['gkStatus']==0){?>
                                            <td>        
                                                <center><a href="<?php echo base_url().'index.php/manager/FieldStaffController/fieldStaff/'.$data['id']; ?>"><i class="material-icons">add</i></a></center>
                                            </td>
                                  <?php }else if($data['managerHisaabStatus']==1 && $data['fsStatus']==1){  ?>
                                            <td><center><i class="material-icons">check</i></center></td> 
                                  <?php }else{ ?> 
                                            <td></td>
                                  <?php } ?>

                                  <?php if($data['fsStatus']==1 && $data['managerHisaabStatus']==1 && $data['gkStatus']==1){ ?>
                                          <td><center><i class="material-icons">check</i></center></td>
                                  <?php }else if($data['fsStatus']==1 && $data['managerHisaabStatus']==1 && $data['gkStatus']==0){?>
                                          <td></td>
                                  <?php }else{ ?>
                                          <td></td>
                                <?php }
                                        if($data['fsStatus']==1 && $data['managerHisaabStatus']==1 && $data['gkStatus']==1 && $data['sr_bill_Status']==0){
                                  ?>
                                        <td><a href="<?php echo base_url().'index.php/manager/SrCheckController/LoadSrCheckDetails/'.$data['id'];?>"><center><i class="material-icons">add</i></center></a></td>

                                  <?php }else if($data['fsStatus']==1 && $data['gkStatus']==1 && $data['sr_bill_Status']==1){ ?>
                                        
                                             <td><center><i class="material-icons">check</i></center></td>
                                  <?php }else{  ?>
                                            <td></td>
                                  <?php } ?>
                                        
                                            <td></td>
                                          
                                            
                                        </tr>
                                    <?php
                                        }


                                    foreach ($officeAllocations as $data) 
                                    {
                                        $no++; 
                                    ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td>
                                                  <?php echo $data['allocationCode']; ?>
                                            </td>
                                            <td></td>
                                            <td><?php echo $data['title']; ?></td>
                                            <td></td>
                                            <td>
                                            <?php if($data['managerStatus']=="0"){ ?>
                                              <a href="<?php echo base_url().'index.php/manager/OfficeAllocationController/loadOfficeAllocationsBills/'.$data['id'];?>"><center><i class="material-icons">add</i></center></a>
                                            <?php }else if($data['managerStatus']=="1"){ ?>
                                                <span>Pending for owner approval</span>
                                            <?php }else{ ?>
                                                <center><i class="material-icons">check</i></center>
                                            <?php } ?>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                          
                                            
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