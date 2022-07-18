<?php $this->load->view('/layouts/commanHeader'); ?>
<!-- <section class="content"> -->

<style type="text/css">
    @media screen and (min-width: 1100px) {
        .modal-dialog {
          width: 1100px; /* New width for default modal */
        }
        .modal-sm {
          width: 350px; /* New width for small modal */
        }
    }

    @media screen and (min-width: 1100px) {
        .modal-lg {
          width: 1100px; /* New width for large modal */
        }
    }

</style>


<script>
    $(document).ready(function() {
    $.fn.dataTable.ext.errMode = 'none';
    $('#outTbl').DataTable( {
        dom: 'Bfrtip',
        stateSave: true,
            buttons: [{
                    extend: 'pdf',
                    title: 'Outstanding Bills',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                },{
                    extend: 'excel',
                    title: 'Outstanding Bills',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                }, {
                    extend: 'csv',
                    title: 'Outstanding Bills',
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
            
          <!-- Basic Examples -->
          <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                         Outstanding Bills
                     </h2>
                 </div>
                 <div class="body">
                    <div class="table-responsive">
                        <div class="col-md-12">
                            <?php if($responce = $this->session->flashdata('emptyTxt')){ ?>
                                  <div class="box-header">
                                       <div class="alert alert-danger"><?php echo $responce;?></div>
                                  </div>
                            <?php } ?>
                    <table id="outTbl" class="table table-bordered table-striped table-hover dataTable js-exportable" data-page-length='100'>
                        <thead>
                        <tr>
                            <th style="width:5px">Sr.No</th>
                            <th>Bill No</th>
                            <th>Date</th>
                            <th>Retailer Name</th>
                            <th>Net Amount</th>
                            <th>SR</th>
                            <th>Received</th>
                            <th>Pending</th>
                            <th class="noExport">Action</th>
                        </tr>
                        </thead>
                        <tfoot>
                         <tr>
                            <th style="width:5px">Sr.No</th>
                            <th>Bill No</th>
                            <th>Date</th>
                            <th>Retailer Name</th>
                            <th>Net Amount</th>
                            <th>SR</th>
                            <th>Received</th>
                            <th>Pending</th>
                            <th class="noExport">Action</th>
                        </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            $no=0;
                            foreach ($bills as $data) 
                            {
                               $no++; 
                                // $retailerName=$this->DeliverySlipModel->getRetailerName($data['retailerId']);
                                // $retailerName=$retailerName[0]['name'];
                            ?>
                             <tr>
                                <td style="width:5px"><?php echo $no; ?></td>
                                <td>
                                    <?php echo $data['billNo']; ?>
                                </td>

                                <?php
                                    $dt=date_create($data['date']);
                                    $date = date_format($dt,'d-m-Y');
                                ?>
                                
                                <td><?php echo $date; ?></td>
                                <td><?php echo $data['retailerName']; ?></td>
                                <td align="right"><?php echo number_format($data['netAmount'],2); ?></td>
                                <td align="right"><?php echo number_format($data['SRAmt'],2); ?></td>
                                <td align="right"><?php echo number_format($data['receivedAmt'],2); ?></td>
                                <td align="right"><?php echo number_format($data['pendingAmt'],2); ?></td>
                                <td>
                                  <!--  <a class="iframe" href="<?php echo base_url().'index.php/SrController/load/'.$data['id'];  ?>">
                                    <button class="btn btn-primary btn-sm" data-type="basic"><i class="material-icons">add</i><span>Sales Return</span></button>
                                   </a>  -->


                                    <button data-toggle="modal" data-target="#myModal" data-id="<?php echo $data['id']; ?>" class="modalLink btn btn-primary btn-sm" data-type="basic"><i class="material-icons">add</i><span>Sales Return</span></button>
                                   
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
</div>
<!-- #END# Basic Examples -->
</div>
</section>


<div class="container">
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          
            <h4 class="modal-title">Sales Return</h4>
          </div>
          <div class="modal-body">
          </div>
      </div>
    </div>
  </div>
</div>

<script>
 $(document).ready(function(){
    $('.modalLink').click(function(){
       
        var id=$(this).attr('data-id');
        
       
        $.ajax({
            url : "<?php echo site_url('SrController/loadSrBill');?>",
            method : "POST",
            data : {id: id, },
            success: function(data){
                
              $('.modal-content').html(data);
            }
        });
    });
});
</script>

    <script>
        function getFSR(){
           var table = document.getElementById('SrTable');
            for (var i = 1; i < table.rows.length-1; i++) {
              if (table.rows[i].cells.length) {
                var billedQty = (table.rows[i].cells[3].textContent.trim());
                var oldSR = (table.rows[i].cells[5].textContent.trim());
                billedQty=billedQty-oldSR;
                var srQty = (table.rows[i].cells[6].textContent.trim());
                // table.rows[i].cells[6].innerHTML='<input type="text" class="form-control" name="returnedQty[]" value="'+billedQty+'">';
                table.rows[i].cells[6].innerHTML='<input type="text" class="form-control" name="returnedQty[]" value="'+billedQty+'">';
              }
            }
        }
    </script>
<?php $this->load->view('/layouts/footerDataTable'); ?>