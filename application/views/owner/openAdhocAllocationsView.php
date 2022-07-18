<?php $this->load->view('/layouts/commanHeader'); ?>

<!--<meta http-equiv="refresh" content="300"/>-->
<style>
  .tableFixHead {
  overflow-y: auto;
  height: 400px;
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
                               Open AdHoc Allocations 
                            </h2>
                            <!-- <h2>
                                <p align="right">
                                     <button class="btn btn-sm bg-primary margin" onClick="window.location.reload();"><i class="material-icons">refresh</i></button>
                                  <a href="<?php echo site_url('AllocationByManagerController/Add');?>">
                                    <button class="btn bg-primary margin"><i class="material-icons">person_add</i>  Add New Allocation </button></a>
                                  <a href="<?php echo site_url('manager/OfficeAllocationController/addOfficeAllocationsBills');?>">
                                    <button class="btn bg-primary margin"><i class="material-icons">person_add</i>  Add Office Allocation </button></a>  
                                </p> 
                            </h2> -->
                        </div>
                        <div class="body">
                            <div class="table-responsive tableFixHead">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Allocation No.</th>
                                            <th>Title</th>
                                            <th>Owner</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Allocation No.</th>
                                            <th>Title</th>
                                            <th>Owner</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                      <?php
                                        $no=0;
                                       


                                    foreach ($officeAllocations as $data) 
                                    {
                                        $no++; 
                                    ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td>
                                                  <?php echo $data['allocationCode']; ?>
                                            </td>
                                            <td>
                                              <?php echo $data['title']; ?>
                                            </td>
                                           
                                            <td>
                                              <?php if($data['managerStatus']=="1" && $data['ownerStatus']=="0"){ ?>
                                              <a href="<?php echo base_url().'index.php/owner/OfficeAllocationController/loadOfficeAllocationsBills/'.$data['id'];?>"><center><i class="material-icons">add</i></center></a>
                                            <?php }else if($data['managerStatus']=="1" && $data['ownerStatus']=="1"){ ?>
                                                <center><i class="material-icons">check</i></center>
                                            <?php }else{ ?>

                                          <?php } ?>
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
