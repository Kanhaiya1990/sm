<?php $this->load->view('/layouts/commanHeader'); ?>

<script>
function goBack() {
  window.history.back();
}
</script>
        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               <button onclick="goBack();" class="btn btn-xs bg-primary margin"><i class="material-icons">keyboard_return</i></button></a> <?php echo $title; ?>
                            </h2>
                        </div>
                        <div class="body">
                            <div>
                                <?php if(!empty($allocationDetails)){ ?>
                                    Allocation : <b><?php echo $allocationDetails[0]['allocationCode']; ?> </b>
                                    &nbsp;&nbsp;
                                    Route : <b><?php echo $allocationDetails[0]['rname']; ?> </b>
                                    &nbsp;&nbsp;
                                    Company : <b><?php echo $allocationDetails[0]['company']; ?> </b>
                                    &nbsp;&nbsp;
                                    Employee : <b><?php echo $allocationDetails[0]['ename']; ?> </b>
                                <?php } ?>
                            </div><br>
                            <div class="table-responsive">
                                <input type="hidden" id="allocationId" value="<?php echo $allocationId; ?>">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Bill No.</th>
                                            <th>Retailer Name</th>
                                            <th>Retailer Code</th>
                                            <th>Company</th>
                                            <th>Item</th>
                                            <th>Quantity</th>
                                            <!-- <th>Action</th> -->
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Bill No.</th>
                                            <th>Retailer Name</th>
                                            <th>Retailer Code</th>
                                            <th>Company</th>
                                            <th>Item</th>
                                            <th>Quantity</th>
                                            <!-- <th>Action</th> -->
                                        </tr>
                                    </tfoot>
                                    <tbody id="tbl_data">
                                        
                                <?php
                                    $no=0;
                                        if(!empty($srDetails)){
                                            foreach ($srDetails as $data) 
                                            {
                                                $allocation_sr_id=$data['sr_id'];
                                                $no++; 
                                ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $data['billNo']; ?></td>
                                        <td><?php echo $data['retailerName']; ?></td>
                                        <td><?php echo $data['retailerCode']; ?></td>
                                        <td><?php echo $data['compName']; ?></td>
                                        <td><?php echo $data['prodName']; ?></td>
                                        <td><?php echo $data['sr_qty']; ?></td>
                                        
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