<?php $this->load->view('/layouts/commanHeader'); ?>
  
    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                              Day Wise Past Day Book
                            </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <!--<h2>-->
                            <!--  Cash Book(Income/Expense)-->
                            <!--</h2>-->
                        <h2>
                            <br>
                            <p align="center">Past Day Book Date: <?php echo date("d-m-Y", strtotime($pastDayDate));?></p>
                        </h2>
                        </div>
                        <div class="body">
                            
                            <div class="table-responsive">
                              
                                <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Inflow/Outflow</th>
                                            <th>Nature</th>
                                            <th>Employee</th>
                                            <th>Reference</th>
                                            <th>Amount</th>
                                            <th>Balance</th>
                                        </tr>
                                        
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Date</th>
                                            <th>Inflow/Outflow</th>
                                            <th>Nature</th>
                                            <th>Employee</th>
                                            <th>Reference</th>
                                            <th>Amount</th>
                                            <th>Balance</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        
                                        <?php 
                                        $no=0;
                                            if(!empty($inOut)){
                                                foreach($inOut as $data){
                                                $no++;
                                        ?>
                                        
                                        <?php if($data['statusParking']==1){?>
                                            <tr style="background-color:  #f97069;">
                                                <td><?php echo date("d-m-Y", strtotime($data['updatedAt']));?></td>
                                                <td><?php echo "Outflow";?></td>
                                                <td>Parking</td>
                                                <td><?php echo $data['name'];?></td>
                                                <td></td>
                                                <td><?php echo $data['parking'];?></td>
                                                <td></td>
                                            </tr>
                                        <?php } ?>

                                         <?php if($data['statusCng']==1){?>
                                             <tr style="background-color:#f97069;">
                                               <td><?php echo date("d-m-Y", strtotime($data['updatedAt']));?></td>
                                                <td><?php echo "Outflow";?></td>
                                                <td>CNG</td>
                                                <td><?php echo $data['name'];?></td>
                                                <td></td>
                                                <td><?php echo $data['cng'];?></td>
                                                <td></td>
                                            </tr>
                                        <?php } ?>
                                        <?php if($data['statusChallan']==1){?>
                                             <tr style="background-color:#f97069;">
                                                <td><?php echo date("d-m-Y", strtotime($data['updatedAt']));?></td>
                                                <td><?php echo "Outflow";?></td>
                                                <td>Challan</td>
                                                <td><?php echo $data['name'];?></td>
                                                <td></td>
                                                <td><?php echo $data['challan'];?></td>
                                                <td></td>
                                            </tr>
                                        <?php 
                                                } 
                                            }
                                           } 

                                         if(!empty($inflowEmp)){
                                            foreach($inflowEmp as $dt){
                                                if($dt['inoutStatus']=="Outflow"){
                                        ?>
                                            <tr style="background-color:#f97069;">
                                        <?php }else{?>
                                            <tr style="background-color: #57f587;">
                                        <?php } ?>
                                                <td><?php echo date("d-m-Y", strtotime($dt['date']));?></td>
                                                <td><?php echo $dt['inoutStatus'];?></td>
                                    
                                                <td><?php echo $dt['nature'];?></td>
                                    
                                                <td><?php echo $dt['name'];?></td>
                                                <td></td>
                                                <td><?php echo $dt['amount'];?></td>
                                                <td><?php echo $dt['openCloseBalance'];?></td>
                                            </tr>
                                        <?php 
                                                }
                                            }
                                        ?>
                                        
                                        <?php if(!empty($inflowCategory)){
                                            foreach($inflowCategory as $dt){
                                       if($dt['inoutStatus']=="Outflow"){
                                        ?>
                                            <tr style="background-color:#f97069;">
                                        <?php }else{?>
                                            <tr style="background-color: #57f587;">
                                        <?php } ?>
                                                <td><?php echo date("d-m-Y", strtotime($dt['date']));?></td>
                                                <td><?php echo $dt['inoutStatus'];?></td>
                                    
                                                <td><?php echo $dt['nature'];?></td>
                                         <td></td>
                                                <td><?php echo $dt['category'];?></td>
                                                <td><?php echo $dt['amount'];?></td>
                                                <td><?php echo $dt['openCloseBalance'];?></td>
                                            </tr>
                                        <?php 
                                                }
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div><!-- Credit Table-->
                        <br>
                            
                       
                        </div>
                    </div>
                </div>
                 
            </div>
            <!-- #END# Basic Examples -->  

        </div>
    </section>
<?php $this->load->view('/layouts/footerDataTable'); ?>
