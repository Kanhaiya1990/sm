<?php $this->load->view('/layouts/commanHeader'); ?>

<style type="text/css">
    .logo_prov {
        border-radius: 30px;
         border: 1px solid black;
        background: red;
        color: black;
        padding: 6px;
        width: 50px;
        height: 50px;
    }
</style>
        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Pending Collection Detail
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row m-t-20">
                            <div class="col-md-12 cust-tbl">
                                    <form method="post" role="form" action="">
                                        
                                        <label>Company:</label>
                                        <input type="text" list="compList" autocomplete="off" placeholder="select company" id="cmp" name="cmp" value="<?php  echo $cmpName; ?>" class="b-bottom" style="border: 0">
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
                                        <button type="submit" class="btn btnStyle h-35">Search</button>
                                    </form>

                                </div>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="table table-bordered cust-tbl js-exportable dataTable" data-page-length='100'>
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
                                    if(!empty($countOffice)){ $no++; ?>
                                        <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo "Office Collection"; ?></td>
                                        <td>All </td>
                                        <td><?php echo "Process Button Collection"; ?></td>
                                        <td></td>
                                        <td>
                                            <a href="<?php echo site_url('operator/OperatorController/pendingOfficeCollection'); ?>"><button class="btn btn-xs btnStyle margin"><i class="material-icons">visibility</i></button></a>
                                        </td>
                                   </tr>  
                                <?php } 
                                   
                                        if(!empty($pendingCollection)){
                                            foreach ($pendingCollection as $data) 
                                            {
                                                $allocation_Id=$data['allocation_Id'];
                                                $no++; 
                                ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $data['code'].' : '.$data['routeName'].' : '.$data['empName']; ?></td>
                                        <td><?php echo $data['allocation_Company']; ?></td>
                                        <td><?php
                                                $dt=date_create($data['endDate']);
                                                $date = date_format($dt,'d-M-Y H:i:sa');
                                                echo $date;
                                            ?>
                                        </td>
                                        <td><?php echo $data['empName']; ?></td>
                                        <td>
                                            <a href="<?php echo site_url('operator/OperatorController/pendingOfficeCollectionByAllocation/'.$allocation_Id); ?>"><button class="btn btn-xs btnStyle margin"><i class="material-icons">visibility</i></button></a>
                                        </td>
                                   </tr>  
                                <?php
                                            }
                                        }


                                        if(!empty($pendingOfficeCollection)){
                                            foreach ($pendingOfficeCollection as $data) 
                                            {
                                                $allocation_Id=$data['id'];
                                                $no++; 
                                ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $data['allocationCode']; ?></td>
                                        <td><?php echo $data['company']; ?></td>
                                        <td><?php
                                                $dt=date_create($data['createdAt']);
                                                $date = date_format($dt,'d-M-Y H:i:sa');
                                                echo $date;
                                            ?>
                                        </td>
                                        <td><?php echo $data['empName']; ?></td>
                                        <td>
                                            <a href="<?php echo site_url('operator/OperatorController/pendingOfficeAdjustmentCollectionByAllocation/'.$allocation_Id); ?>"><button class="btn btn-xs bg-primary margin"><i class="material-icons">visibility</i></button></a>
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

   <!--  <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Pending Allocation SR Detail
                            </h2>
                        </div>
                        <div class="body">
                            
                            <div class="table-responsive">
                                <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-exportable dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Allocation No.</th>
                                            <th>Company</th>
                                            <th>Time</th>
                                            <th>Employee</th>
                                            <th>Allocation SR Total</th>
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
                                            <th>Allocation SR Total</th>
                                            <th>Details</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        
                                <?php 
                                    $no=0;
                                    if(!empty($srAllocationData)){
                                        foreach ($srAllocationData as $data) 
                                        {
                                            $allocation_Id=$data['allocation_Id'];

                                            $getAllocationSr=$this->OperatorModel->getSrBills('allocation_sr_details',$allocation_Id);
                                            $total=0;
                                            if(!empty($getAllocationSr)){
                                                foreach($getAllocationSr as $sr){
                                                    $actualSr= $sr['ac_qty'];
                                                    $rateSr= $sr['netAmount']/$sr['qty'];
                                                    $total=$total+ ($rateSr*$actualSr);
                                                }
                                            }
                                            $no++; 
                                ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $data['code']; ?></td>
                                        <td><?php echo $data['allocation_Company']; ?></td>
                                        <td><?php
                                                $dt=date_create($data['startDate']);
                                                $date = date_format($dt,'d-M-Y H:i:sa');
                                                echo $date;
                                            ?>
                                        </td>
                                        <td><?php echo $data['empName']; ?></td>
                                        <td><?php echo number_format($total); ?></td>
                                        <td>
                                            <a href="<?php echo site_url('operator/OperatorController/saveSrApproval/'.$allocation_Id); ?>"><button class="btn btn-xs bg-primary margin"><i class="material-icons">check</i></button></a>
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
        </div>
    </section> -->
<?php $this->load->view('/layouts/footerDataTable'); ?>

<script type="text/javascript">
    
$( "#cmp").click(function() {
  $( "#cmp" ).select();
});
</script>