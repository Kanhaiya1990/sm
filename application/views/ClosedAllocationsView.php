<?php $this->load->view('/layouts/commanHeader'); ?>
        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
          <!--   <div class="block-header">
                <h2>
                    Closed Allocations Master
                </h2>
            </div> -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Closed Allocations Master
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table id="tbl" style="font-size: 12px"  class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Allocation</th>
                                            <th>Date</th>
                                            <th>Route</th>
                                            <th>Salesman</th>
                                            <th>Deliveryman</th>
                                            <th>Reference</th>
                                            <th>Total Cash Amt</th>
                                            <th>Total Cheque/NEFT Amt</th>
                                            <th>Total SR Amt</th>
                                            <th>Total Other Adj Amt</th>
                                           
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Allocation ID</th>
                                            <th>Date</th>
                                            <th>Route</th>
                                            <th>Salesman</th>
                                            <th>Deliveryman</th>
                                            <th>Reference</th>
                                            <th>Total Cash Amt</th>
                                            <th>Total Cheque/NEFT Amt</th>
                                            <th>Total SR Amt</th>
                                            <th>Total Other Adj Amt</th>
                                          
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

                                                $otherTotal=$this->AllocationByManagerModel->loadAllocationBillPayments('billpayments',$allocationId,'Other Adjustment');
                                                $totalValue=0;
                                                if(!empty($otherTotal)){
                                                    foreach($otherTotal as $tot){
                                                        $totalValue=$totalValue+$tot['paidAmount'];
                                                    }
                                                }
                                                // $allocationCollection[0]['totalOtherAdjAmt']=$totalValue;
                                             
                                           ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            
                                            <td>
                                                <a href="<?php echo base_url().'index.php/AllocationByManagerController/CloseCompleteAllocation/'.$data['id']; ?>"><?php echo $data['allocationCode']; ?></a>
                                            </td>
                                            <?php
                                                $dt=date_create($data['date']);
                                                $date = date_format($dt,'d-M-Y');
                                            ?>
                                             <td><?php echo $date; ?></td>
                                            
                                            <td><?php echo rtrim($routeName,', '); ?></td>
                                            <td></td> 
                                            <td><?php echo rtrim($employee,', '); ?></td>
                                            <td><?php echo $data['reference']; ?></td> 
                                            <td align="right"><?php echo number_format($data['totalCashAmt']); ?></td>
                                            <td align="right"><?php echo number_format($data['totalChequeNeftAmt']); ?></td>
                                            <td align="right"><?php echo number_format($data['totalSRAmt']); ?></td>
                                            <td><?php echo $totalValue; ?></td>
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
        </div>
    </section>
<?php $this->load->view('/layouts/footerDataTable'); ?>
