<?php $this->load->view('/layouts/commanHeader'); ?>

<script>
    $(document).ready(function() {
    $.fn.dataTable.ext.errMode = 'none';
    $('#xp').DataTable( {
        dom: 'Bfrtip',
        stateSave: true,
            buttons: [{
                    extend: 'pdf',
                    title: 'Retailerwise Outstanding',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                },{
                    extend: 'excel',
                    title: 'Retailerwise Outstanding',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                }, {
                    extend: 'csv',
                    title: 'Retailerwise Outstanding',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                }
            ]
        
    } );
} );
</script>

<style>
td.details-control {
    background: url('http://www.datatables.net/examples/resources/details_open.png') no-repeat center center;
    cursor: pointer;
}
tr.shown td.details-control {
    background: url('http://www.datatables.net/examples/resources/details_close.png') no-repeat center center;
}
</style>
    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            
          <!-- Basic Examples -->
          <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                        Retailerwise Outstanding
                     </h2>
                 </div>
                 <div class="body">
                    <div class="table-responsive">
                        <div class="col-md-12">
   
                    <table class="table table-bordered dataTable js-exportable cust-tbl" data-page-length='100' id="xp">
                        <thead>
                         <tr data-key-1="Value 1" data-key-2="Value 2">
                            <th></th>
                            <th>No</th>
                            <th>Retailer Name</th>
                            <th>Retailer Code</th>
                            <th>Salesman</th>
                            <th>Nos</th>
                            <th class="text-right">Balance</th>
                            <!--<th class="text-right">Balance Due</th>-->
                        </tr>
                        </thead>
                        <tfoot>
                        <tr data-key-1="Value 1" data-key-2="Value 2">
                            <th></th>
                            <th>No</th>
                            <th>Retailer Name</th>
                            <th>Retailer Code</th>
                            <th>Salesman</th>
                            <th>Nos</th>
							<!--<th>No of Bills</th>-->
							<th class="text-right">Balance</th>
                            <!--<th class="text-right">Balance Due</th>-->
                        </tr>
                        </tfoot>
                        <tbody>
                             <?php    
                              $no=0;
                              foreach ($bills as $data) 
                                {
                                 $no++; 
                                  $routeName="";
                                  $retailerName="";
                                ?>
                              <tr>                                   
                                <td class="details-control"></td>
                                <td><?php echo $no; ?></td>
                                <td id="retailerName">                                 
                                    <?php echo $data['retailerName']; ?>
                                </td>
                                <td id="retailerCode">                                 
                                    <?php echo $data['retailerCode']; ?>
                                </td>
                                <td><?php echo $data['salesman']; ?></td>
                                <!-- <td><?php echo $data['routeName'];?></td> -->
                                <!--<td></td>-->
                                <td><?php echo $data['billCount'];?></td>
                                <td class="text-right"><?php echo number_format($data['pendingAmt']);?></td>                     
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
<!-- <script>
  $(document).ready(function() {
    var table = $('#example').DataTable();
     
    $('#example tbody').on('click', 'tr', function () {
        var data = table.row( this ).data();
        alert(data[2]);
    } );
} );
</script> -->
<script>
$(document).ready(function () {
    $.fn.dataTable.ext.errMode = 'none';
    var table = $('#xp').DataTable({});
    
    // Add event listener for opening and closing details
    // $('#example').on('click', 'td.details-control', function () {
       $('#xp tbody').on('click', 'tr', function () {
        var data = table.row( this ).data();
        var rName=encodeURIComponent(data[3]);
        // alert(rName);
         rName = rName.replace("(", "%28");
        rName = rName.replace(")", "%29");
         // rName = rName.replace("&", "");
        // alert(rName);
        var tr = $(this).closest('tr');
        var row = table.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
             format(row.child,rName);
            tr.addClass('shown');
        }
    });

    function format(callback,rName) {
      
        $.ajax({
          url:"<?php echo site_url('BillTransactionController/loadRetailerBills/');?>"+rName,
       
        dataType: "json",
        complete: function (response) {
            var data = JSON.parse(response.responseText);
          
                var thead = '',  tbody = '';
                 thead += '<tr><th>  Bill No  </th><th> Date </th><th> Retailer Name </th><th> Salesman Name </th><th> NetAmount </th><th> SR</th><th>Received </th><th> Pending </th><th> Status</th></tr>';
               
                $.each(data, function (i, d) {
                    // alert(d);
                    tbody += '<tr><td>' + d.billNo + '</td><td>' + d.date + '</td><td>' + d.retailerName + '</td><td>' + d.salesman + '</td><td align="right">' + d.netAmount + '</td><td align="right">' + d.SRAmt + '</td><td align="right">' + d.receivedAmt + '</td><td align="right">' + d.pendingAmt + '</td><td align="left">' + d.status + '</td></tr>';
                });
            console.log('<table class="table table-bordered dataTable js-exportable cust-tbl display nowrap">' + thead + tbody + '</table>');
            callback($('<table class="table table-bordered dataTable js-exportable cust-tbl display nowrap">' + thead + tbody + '</table>')).show();
        },
        error: function () {
            $('#output').html('Bummer: there was an error!');
        }
    });
    }
});
</script>

