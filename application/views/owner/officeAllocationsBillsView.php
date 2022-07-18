<?php $this->load->view('/layouts/commanHeader'); ?>

<!--<meta http-equiv="refresh" content="300"/>-->
<!-- <style>
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
  </style> -->
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
                               Closed Office Allocations
                            </h2>
                            <h2>
                                <p align="right">
                                     <button class="btn btn-sm bg-primary margin" onClick="window.location.reload();"><i class="material-icons">refresh</i></button>
                                 <!--  <a href="<?php echo site_url('owner/OfficeAllocationController/addOfficeAllocationsBills');?>">
                                    <button type="submit" class="btn bg-primary margin"><i class="material-icons">person_add</i>  Add  </button></a>  -->
                                </p> 
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive tableFixHead">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Allocation No.</th>
                                            <th>Division</th>
                                            <th>Title</th>
                                            <th>Allocation Date</th>
                                            <th>Created By</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                           <th>S. No.</th>
                                            <th>Allocation No.</th>
                                            <th>Division</th>
                                            <th>Title</th>
                                            <th>Allocation Date</th>
                                            <th>Created By</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                      <?php
                                        $no=0;
                                        foreach ($officeAllocations as $data) 
                                          {
                                               $no++; 
                                               $emp=$this->OfficeAllocationModel->load('employee',$data['createdBy']);
                                              
                                           ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td>
                                              <a href="<?php echo base_url().'index.php/owner/OfficeAllocationController/closedAllocationInfo/'.$data['id'];?>"><?php echo $data['allocationCode']; ?></a>
                                              </td>
                                            <td><?php echo $data['company']; ?></td>
                                            <td><?php echo $data['title']; ?></td>
                                            <td><?php echo $data['createdAt']; ?></td>
                                            <td><?php  if(!empty($emp)){ echo $emp[0]['name']; }; ?></td>
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
