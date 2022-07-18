<?php $this->load->view('/layouts/commanHeader'); ?>

<script   src="https://code.jquery.com/jquery-1.12.1.js" integrity="sha256-VuhDpmsr9xiKwvTIHfYWCIQ84US9WqZsLfR4P7qF6O8="   crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<style type="text/css">
.selectStyle select {
   background: transparent;
   width: 250px;
   padding: 4px;
   font-size: 1em;
   border: 1px solid #ddd;
   height: 25px;
}
li{
    margin-bottom: 0PX;
    padding-bottom: 0PX;
}
</style>

<script>
    $(document).ready(function() {
    $.fn.dataTable.ext.errMode = 'none';
    $('#prodTbl').DataTable( {
        dom: 'Bfrtip',
        stateSave: true,
            buttons: [{
                    extend: 'pdf',
                    title: 'Products Details',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                },{
                    extend: 'excel',
                    title: 'Products Details',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                }, {
                    extend: 'csv',
                    title: 'Products Details',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                }
            ]
        
    } );
} );
</script>

<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
<section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
    <!-- <section class="content"> -->
        <div class="container-fluid">
            <!-- <div class="block-header">
                <h2>Cheque Register</h2>
            </div> -->
            <!-- Basic Examples -->
            <div class="row clearfix" id="page">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                             Salesman wise Stock wise sale
                         </h2>
                         
                     </div>
                     <div class="body">
                        <form method="post" role="form" action="<?php echo site_url('DeliverySlipController/checkSalesmanStock');?>">
                            <label>From Date:</label>
                            <input type="date" name="from_date" value="<?php echo date('Y-m-01'); ?>">
                            <label>To Date:</label>
                            <input type="date" name="to_date" value="<?php echo date('Y-m-d'); ?>">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                      <div class="row">                                 
                        <div class="row m-t-20">
                            <div class="col-md-12">
                               <!--<?php echo count($sales);  ?>-->
                               <!--     <?php print_r($sales);?>-->
                                   <table id="prodTbl" class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                        <thead>
                                            <tr class="gray">
                                                <th>Product Name</th>
                                                <th>Available Stock</th>
                                                <?php
                                                    foreach ($emp as $data) 
                                                    {
                                                ?>
                                                <th><?php echo $data['name'] ?></th>
                                                <?php
                                                    }
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                                <?php
                                                    $no=0;
                                                    foreach ($prod as $data) 
                                                    {
                                                ?>
                                                <tr>
                                                   <td><?php echo $data['name'] ?></td> 
                                                   <td><?php echo $data['availQty'] ?></td> 
                                                   <?php
                                                    foreach($emp as $data1){
                                                    foreach($sales as $data2){
                                                            if($data['name']==$data2['pname'] && $data1['name']==$data2['ename']){
                                                    ?>
                                                        <td><?php echo $data2['prodQty']; ?></td>
                                                    <?php
                                                            }
                                                        }
                                                            ?>
                                                            <td></td>
                                                            <?php
                                                        }
                                                   ?>
                                                </tr>
                                                <?php
                                                $no++;
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
                </div>
            </div>
            <!-- #END# Basic Examples --> 
        </div>
    </section>
  <?php $this->load->view('/layouts/footerDataTable'); ?>
   <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
