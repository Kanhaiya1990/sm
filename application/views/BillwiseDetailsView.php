<?php $this->load->view('/layouts/commanHeader'); ?>

<script>
    $(document).ready(function() {
    $.fn.dataTable.ext.errMode = 'none';
    $('#billWiseTbl').DataTable( {
        dom: 'Bfrtip',
        stateSave: true,
            buttons: [{
                    extend: 'pdf',
                    title: 'Bill wise Details',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                },{
                    extend: 'excel',
                    title: 'Bill wise Details',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                }, {
                    extend: 'csv',
                    title: 'Bill wise Details',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                }
            ]
        
    } );
} );
</script>

<!-- <section class="content"> -->
    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
           
          <!-- Basic Examples -->
          <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                         BillwiseDetails
                     </h2>
                 </div>
                 <div class="body">
                    <div class="table-responsive">
                        <div class="col-md-12">



                            <!-- <div class="col-md-5">
                                         <?php echo validation_errors(); ?>
                                        <?php echo form_open_multipart('AllocationByManagerController/getCurrentBills') ?>
                                        <table class="table table-striped">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                    <b>Retailer Name:</b>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                         <i class="material-icons">perm_contact_calendar</i>
                                                     </span>
                                                     <div class="form-line">
                                                        <select class="form-control" id="retailerName" name="name" required>
                                                            <?php foreach ($retailer as $req_item): ?>
                                                                <option value="<?php echo $req_item['id'] ?>"><?php echo $req_item['name'] ?>                                                            
                                                            </option>
                                                        <?php endforeach ?> 
                                                    </select>
                                                    </div>
                                                    </div>
                                                       
                                                    </td>
                                               
                                                     <td>
                                                        <button type="button" id="selBills" class="btn btn-primary btn-lg"> <i class="material-icons">call_made</i> Go </button><br />                                                      
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                         <?php echo form_close(); ?>
                                    </div> -->
                            
                             <form method="post" role="form" action="<?php echo site_url('DeliverySlipController/getRetailersBills');?>"> 

                                <div class="col-md-3">
                                    <b>Retailer Name:</b>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                         <i class="material-icons">perm_contact_calendar</i>
                                     </span>
                                     <div class="form-line">
                                     <input type="text" id="retailerName" autocomplete="off" list="routeN" name="name" class="form-control" placeholder="Retailer Name">   
                                        <datalist id="routeN">
                                            <?php
                                                foreach($retailer as $data){
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
                    <!-- </div> -->
                        <!-- <div class="col-md-4">
                            <button type="button" id="sbClick" class="btn btn-primary btn-lg"> <i class="material-icons">call_made</i> Go </button> 
                        </div>  -->
                     </form>  
                    
                    <!-- <br /><br /><br /><br /><br >
                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp -->
                     <div class="col-md-12">
                    <table id="billWiseTbl" class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                            <tr>
                                <th>S. No.</th>
                                <th>Bill No</th>
                                <th>Bill Date</th>
                                <th>Retailer</th>
                                <th>Salesman</th>
                                <th>Bill Amount</th>
                                <th>SR</th>
                                <th>Cash</th>
                                <th>Pending Amount</th>
                            </tr>
                        </thead>
                        <tfoot>
                             <tr>
                                <th>S. No.</th>
                                <th>Bill No</th>
                                <th>Bill Date</th>
                                <th>Retailer</th>
                                <th>Salesman</th>
                                <th>Bill Amount</th>
                                <th>SR</th>
                                <th>Cash</th>
                                <th>Pending Amount</th>
                            </tr>
                        </tfoot>
                        <tbody id="result_data">
                           
                            <?php
                                        $no=0;
                                        foreach ($bills as $data) 
                                          {
                                           $no++; 

                                    ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                        
                                            <td>
                                                <a target="_new" href="<?php echo base_url().'index.php/DeliverySlipController/ShowPDF/'.$data['id']; ?>">
                                                <?php echo $data['billNo']; ?>
                                                  </a>  
                                            </td>
                                             <?php
                                                $dt=date_create($data['date']);
                                                $date = date_format($dt,'d-m-Y');
                                            ?>
                                            <td><?php echo $date; ?></td>
                                            <td><?php echo $data['retailerName']; ?></td>
                                            <td><?php echo $data['salesman']; ?></td>
                                            <td align="right"><?php echo number_format($data['netAmount'],2); ?></td>
                                            <td align="right"><?php echo number_format($data['SRAmt'],2); ?></td>
                                            <td align="right"><?php echo number_format($data['receivedAmt'],2); ?></td>
                                            <td align="right"><?php echo number_format($data['pendingAmt'],2); ?></td>
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
<!-- #END# Basic Examples -->
</div>
</section>
<?php $this->load->view('/layouts/footerDataTable'); ?>
<script>
    $(document).ready(function(){
        $("#retaiName").change(function () {
            alert("Changed!");
        });
    });

    // jQuery('#retailerName').on('input',function(){
    //     var retailerName = $('#retailerName').val();
    //     alert(retailerName);
    //     $.ajax({
    //         type: "POST",
    //         url:"<?php echo site_url('DeliverySlipController/getBillWiseDetails');?>",
    //         data:{"retailerName" : retailerName},
    //         success: function (data) {
    //           $('#result_data').html(data);
    //         }  
    //     });
    // });
</script>

