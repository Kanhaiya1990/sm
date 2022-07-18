<?php $this->load->view('/layouts/commanHeader'); ?>

        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
           <!--  <div class="block-header">
                <h2>
                     Allocation SR Transactions Details
                </h2>
            </div> -->
           
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                 Bill SR Details
                            </h2>
                            <p align="right">
                                <a href="<?php echo site_url('owner/AllocationByManagerController/CloseCompleteAllocation/'.$allocationId);?>">
                                    <button class="btn bg-primary margin">Summary</button></a> 

                                <a href="<?php echo site_url('owner/AllocationByManagerController/closedAllocationTrancationDetails/'.$allocationId);?>">
                                    <button class="btn bg-primary margin"> Other Details</button></a> 
                            </p>
                        </div>
                        <div class="body">
                            <div class="row">
                                <div class="col-md-12">
                                   <div class="col-md-4"> 
                                    <label id="allocation">Allocation : </label>
                                    <?php echo $allocations[0]['allocationCode']?>
                                    
                                </div>
                                    <div class="col-md-4">
                                    <label>Employee</label>
                                    <ul class="list-group" id="list" multiple="multiple">
                                        <?php
                                        $emp1='';
                                         $emp2='';
                                          $emp3='';
                                           $emp4='';
                                            if(!empty($allocations[0]['fieldStaffCode1'])){
                                                $emp1= $this->AllocationByManagerModel->getEmpName('employee',$allocations[0]['fieldStaffCode1']);
                                                $emp1=$emp1[0]['name'];
                                                echo $emp1."<br>";
                                              }   
                                             if(!empty($allocations[0]['fieldStaffCode2'])){
                                                $emp2= $this->AllocationByManagerModel->getEmpName('employee',$allocations[0]['fieldStaffCode2']);
                                                $emp2=$emp2[0]['name'];
                                                  echo $emp2."<br>";
                                                 
                                            }
                                             if(!empty($allocations[0]['fieldStaffCode3'])){
                                                $emp3= $this->AllocationByManagerModel->getEmpName('employee',$allocations[0]['fieldStaffCode3']);
                                                $emp3=$emp3[0]['name'];
                                               
                                                  echo $emp3."<br>";
                                                 
                                            }
                                             if(!empty($allocations[0]['fieldStaffCode4'])){
                                                $emp4= $this->AllocationByManagerModel->getEmpName('employee',$allocations[0]['fieldStaffCode1']);
                                                $emp4=$emp4[0]['name'];
                                                echo $emp4."<br>";
                                                
                                            }
                                            
                                        ?>
                                    </ul>
                                </div>
                                   <div class="col-md-4">
                                    <label> Route</label>
                                    <ul class="list-group" id="rlist" multiple="multiple">
                                        <?php
                                            $rtName=explode(",",rtrim($allocations[0]['routId'],','));
                                        for($i=0;$i<count($rtName);$i++){
                                         $routes=$this->AllocationByManagerModel->getRouteNameById($rtName[$i]);
                                       
                                            if(!empty($routes)){
                                                $routeName=$routes[0]['name'];
                                                echo $routeName."<br>";
                                                
                                            }
                                        }

                                        ?>
                                    </ul>
                                </div>
                                
                                </div>
                                </div>
                            <div class="table-responsive">
                                <table id="crTbl" class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='25'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Bill No</th>
                                            <th>Retailer Name</th>
                                            <th>Item Name</th>
                                            <th>Item Quantity</th>
                                            <th>SR Quantity</th>
                                            <th>Remaining Quantity</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Bill No</th>
                                            <th>Retailer Name</th>
                                            <th>Item Name</th>
                                            <th>Item Quantity</th>
                                            <th>SR Quantity</th>
                                            <th>Remaining Quantity</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php 
                                        $no=0;
                                            if(!empty($sr)){
                                            foreach($sr as $data){
                                                
                                                $no++;
                                        ?>
                                        <tr>
                                            <td><?php echo $no;?></td>
                                            <td><?php echo $data['b_billNo'];?></td>
                                            <td><?php echo $data['b_retailer'];?></td>
                                             <td><?php echo $data['productName'];?></td>
                                             <td><?php echo number_format($data['qty']);?></td>
                                             <td><?php echo $data['srQuantity'];?></td>
                                             <td><?php echo ($data['qty']-$data['srQuantity']);?></td>
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
