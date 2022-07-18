<?php $this->load->view('/layouts/commanHeader'); ?>

<!--<meta http-equiv="refresh" content="300"/>-->
        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
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
                            <p align="right">
                                <button class="btn btn-sm bg-primary margin" onClick="window.location.reload();"><i class="material-icons">refresh</i></button>
                            </p>
                           
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Allocation No.</th>
                                            <th>Route</th>
                                             <th>Employees</th>
                                           <!-- <th>Deliveryman</th>
                                            <th>SR Check</th>
                                            <th>Bills Check</th> -->
                                            <th>Cash & Cheques</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                           <th>S. No.</th>
                                            <th>Allocation No.</th>
                                            <th>Route</th>
                                             <th>Employees</th>
                                            <!-- <th>Deliveryman</th>
                                            <th>SR Check</th>
                                            <th>Bills Check</th> -->
                                            <th>Cash & Cheques</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                   <tr>
                                      <?php
                                        $no=0;
                                        foreach ($allocations as $data) 
                                          {
                                               $no++; 
                                               $routeName='';
                                               $rtName=explode(",",rtrim($data['routeCode'],','));
                                               for($i=0;$i<count($rtName);$i++){
                                                    $routes=$this->CashAndChequeModel->getRouteName($rtName[$i]);
                                                    if(!empty($routes)){
                                                        $routeName=$routeName.' '.$routes[0]['name'].', ';
                                                    }
                                               }

                                                $employee="";
                                                if(($data['fieldStaffCode1'] !='0')){
                                                    $emp=$this->CashAndChequeModel->getEmployeeNamesByID($data['fieldStaffCode1']);
                                                    $employee= $employee.$emp.', ';
                                                }
                                                if(($data['fieldStaffCode2'] !='0')){
                                                    $emp=$this->CashAndChequeModel->getEmployeeNamesByID($data['fieldStaffCode2']);
                                                    $employee=$employee.$emp.', ';
                                                }
                                                // echo $emp;
                                                // exit;
                                           ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td>
                                                <!-- <a href="<?php echo base_url().'index.php/AllocationByManagerController/CompleteAllocation/'.$data['id']; ?>"> -->
                                                  <?php echo $data['allocationCode']; ?>
                                                    
                                                  <!-- </a> -->
                                            </td>
                                            
                                            <td><?php echo rtrim($routeName,', '); ?></td> 
                                            <td><?php echo rtrim($employee,', '); ?></td>

<!--                                         <?php if($data['fsStatus']==0){?>
                                                 <td></td>
                                        <?php }else{ ?>
                                                 <td><center><i class="material-icons">check</i></center></td> 
                                        <?php }  ?> -->
                                       <!--         
                                        <?php if($data['gkStatus']==1){ ?>
                                          <td><center><i class="material-icons">check</i></center></td>
                                       <?php }else{?>
                                          <td></td>
                                       <?php }

                                         if($data['fsStatus']==1 && $data['gkStatus']==1 && $data['sr_bill_Status']==0){
                                      ?> -->
                                        <!-- <td></td> -->

                                     <!--  <?php }else if($data['fsStatus']==1 && $data['gkStatus']==1 && $data['sr_bill_Status']==1){?>
                                        
                                             <td><center><i class="material-icons">check</i></center></td>
                                      <?php }else{ ?>
                                            <td></td>
                                      <?php }  ?>
 -->
                                      <?php
                                            if($data['fsStatus']==1 && $data['gkStatus']==1 && $data['sr_bill_Status']==1){
                                      ?>
                                        <td><a href="<?php echo base_url().'index.php/cashier/CashBookController/allocationWiseCashBook/'.$data['id'];?>"><center><i class="material-icons">add</i></center></a></td>
                                      <?php }else{?>
                                            <td></td>
                                          <?php } ?>
                                            
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