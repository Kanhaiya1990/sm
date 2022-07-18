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
                               Pending SR/FSR Detail
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row m-t-20">
                            <div class="col-md-12 cust-tbl">
                                    <form method="post" role="form" action="">
                                        
                                        <label>Company:</label>
                                        <input type="text" list="compList" autocomplete="off" placeholder="select company" id="cmp" name="cmp" value="<?php  echo $cmpName; ?>">
                                         <datalist id="compList">
                                        <?php
                                            foreach($company as $data){
                                                $name=$data['name'];
                                        ?>   
                                        <option value="<?php echo $name;?>"/>
                                        <?php    
                                            }
                                        ?>
                                    </datalist>
                                        <button type="submit" class="btn btnStyle">Filter</button>
                                    </form>

                                </div>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-exportable dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Allocation No.</th>
                                            <th>Company</th>
                                            <th>Route</th>
                                            
                                            <th>Salesman</th>
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
                                            <th>Route</th>
                                            
                                            <th>Salesman</th>
                                            <th>Time</th>
                                            <th>Employee</th>
                                            <th>Details</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        
                                <?php 
                                    $no=0;
                                    if(!empty($countOffice)){ $no++; ?>
                                        <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo "Office SR"; ?></td>
                                        <td>All </td>
                                        <td></td>
                                        <td></td>
                                      
                                        <td><?php echo "Process Button SR"; ?></td>
                                        <td></td>
                                        <td>
                                            <a href="<?php echo site_url('operator/OperatorController/pendingOfficeSr'); ?>"><button class="btn btn-xs bg-primary margin"><i class="material-icons">visibility</i></button></a>
                                        </td>
                                   </tr>  
                                <?php } 
                                   
                                        if(!empty($pendingSr)){
                                            foreach ($pendingSr as $data) 
                                            {
                                                $allocation_Id=$data['allocation_Id'];
                                                $no++; 


                                ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $data['code']; ?></td>
                                        <td><?php echo $data['allocation_Company']; ?></td>
                                        <td><?php echo $data['routeName']; ?></td>
                                        
                                        <td><?php echo implode(',',array_unique(explode(',', $data['salesman']))); ?></td>
                                         
                                        <td><?php
                                                $dt=date_create($data['endDate']);
                                                $date = date_format($dt,'d-M-Y H:i:sa');
                                                echo $date;
                                            ?>
                                        </td>
                                        <td><?php
                                            $emp=$data['empName1'].', '.$data['empName2'];
                                            echo rtrim($emp,', '); ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo site_url('operator/OperatorController/pendingAllocationSr/'.$allocation_Id); ?>"><button class="btn btn-xs bg-primary margin"><i class="material-icons">visibility</i></button></a>
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

<script type="text/javascript">
    
$( "#cmp").click(function() {
  $( "#cmp" ).select();
});
</script>