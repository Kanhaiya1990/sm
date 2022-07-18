<?php $this->load->view('/layouts/commanHeader'); ?>

<script>
    $(document).ready(function() {
    $.fn.dataTable.ext.errMode = 'none';
    $('#prodTbl').DataTable( {
        dom: 'Bfrtip',
        stateSave: true,
            buttons: [{
                    extend: 'pdf',
                    title: 'Historical SR Details',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                },{
                    extend: 'excel',
                    title: 'Historical SR Details',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                }, {
                    extend: 'csv',
                    title: 'Historical SR Details',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                }
            ]
        
    } );
} );
</script>
        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <!--<div class="block-header">-->
            <!--    <h2>-->
            <!--         Historical Sr Bills-->
            <!--    </h2>-->
            <!--</div>-->
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Historical Sr Bills
                            </h2>
                        </div>
                        <br>
                        <form method="post" action="<?php echo site_url('SrController/HistoricalSr'); ?>">
                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <b>Company Name</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" name="compName" autocomplete="off" list="cmpName" class="form-control date" placeholder="Enter Company Name">
                                                <datalist id="cmpName">
                                                    <?php
                                                        foreach($comp as $data){
                                                            $name=$data['name'];
                                                    ?>   
                                                    <option value="<?php echo $name;?>"/>
                                                    <?php    
                                                        }
                                                    ?>
                                                </datalist>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <b>Retailer Name</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" autocomplete="off" name="retailerName" class="form-control date" placeholder="Enter Retailer Name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <b>Type of Bill</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" autocomplete="off" name="billType" class="form-control date" placeholder="Enter Bill Type">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary m-t-15 waves-effect">
                                            <i class="material-icons">visibility</i> 
                                            <span class="icon-name">Show</span>
                                        </button>
                                    </div>
                                </div>
                                </div>
                            </form>
                        <div class="body">
                            
                            <div class="table-responsive">
                                <table id="prodTbl" class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Allocation ID</th>
                                            <th>Bill No.</th>
                                            <th>Retailer</th>
                                            <th>Route</th>
                                            <th>Item</th>
                                            <th>Company</th>
                                            <th>Net Amount</th>
                                            <th>SR Qty</th>
                                            <th>SR Amount</th>
                                            <th>Bill Type</th>
                                             
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Allocation ID</th>
                                            <th>Bill No.</th>
                                            <th>Retailer</th>
                                            <th>Route</th>
                                            <th>Item</th>
                                            <th>Company</th>
                                            <th>Net Amount</th>
                                            <th>SR Qty</th>
                                            <th>SR Amount</th>
                                           <th>Bill Type</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        
                                        <?php
                                              $no=0;
                                              if(!empty($srBills)){
                                              foreach ($srBills as $data) 
                                                {
                                                  $id=$data['id'];
                                                 $no++; 
                                          ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $data['AllocationCode']; ?></td>
                                        <td>
                                                <?php echo rtrim($data['billNo']); ?>
                                        </td>
                                        <td><?php
                                            echo $data['retailerName']; ?>
                                        </td>
                                        <td><?php echo $data['routeName']; ?></td>
                                        <td><?php echo $data['compName']; ?></td>
                                        <td><?php echo $data['productName']; ?></td>
                                        <td><?php echo $data['netAmount']; ?></td>
                                        <td><?php echo $data['gkReturnQty']; ?></td>
                                        <td><?php echo $data['fsReturnAmt']; ?></td>
                                        <td><?php echo $data['billType']; ?></td>
                                   </tr>  
                                     <?php
                                            }
                                        }
                                         if(!empty($fsrBills)){
                                            foreach($fsrBills as $data){
                                      ?>   
                                      <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $data['AllocationCode']; ?></td>
                                        <td>
                                                <?php echo rtrim($data['billNo']); ?>
                                        </td>
                                        <td><?php
                                            echo $data['retailerName']; ?>
                                        </td>
                                        <td><?php echo $data['routeName']; ?></td>
                                        <td><?php echo $data['compName']; ?></td>
                                        <td><?php echo 'FSR'; ?></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><?php echo $data['billType']; ?></td>
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
