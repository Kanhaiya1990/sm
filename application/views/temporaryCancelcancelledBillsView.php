<?php $this->load->view('/layouts/commanHeader'); ?>

<h1 style="display: none;">Welcome</h1><br><br><br><br>
 <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                            <h2>Suggested for Cancellation</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table id="SrTable" class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                <thead>
                                    <tr>
                                        <th>S. No.</th>
                                        <th>Bill No.</th>
                                        <th>Bill Date</th>
                                        <th>Retailer</th>
                                        <th>Net Amount</th>
                                        <th>Pending Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>S. No.</th>
                                        <th>Bill No.</th>
                                        <th>Bill Date</th>
                                        <th>Retailer</th>
                                        <th>Net Amount</th>
                                        <th>Pending Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                <?php
                                    $no=0;
                                    if(!empty($bills)){
                                        foreach ($bills as $data) 
                                        { 
                                            $no++;
                                            $dt=date_create($data['date']);
                                            $createdDate = date_format($dt,'d-M-Y'); 
                                ?>
                                            <tr>
                                                <td><?php echo $no;?></td>
                                                <td><?php echo $data['billNo'];?></td>
                                                <td><?php echo $createdDate;?></td>
                                                <td><?php echo $data['retailerName'];?></td>
                                                <td align="right"><?php echo number_format($data['netAmount']);?></td>
                                                <td align="right"><?php echo number_format($data['pendingAmt']);?></td>
                                                <td>
                                                    <button onclick="cancelBills(<?php echo $data['id']; ?>);" class="btn-primary waves-effect"><i style="font-size : 11px;" class="material-icons">save</i> <span class="icon-name">OK</span></button>
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
</section>

<?php $this->load->view('/layouts/footerDataTable'); ?>
<script type="text/javascript">
function cancelBills(id){
    var msj= confirm('Are you sure you want to cancel bill.');
    if (msj == false) { 
      die();
    } else {
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('BillTransactionController/updatedTempCancelBillStatus');?>",
            data:{"id":id},
            success: function (data) {
                window.location.href="<?php echo base_url();?>index.php/BillTransactionController/tempCancelledBills";
            }  
        });
    }
}
</script>