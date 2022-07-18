<?php $this->load->view('/layouts/commanHeader'); ?>

        <h1 style="display: none;">Welcome</h1>&nbsp;
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                    Bill Details
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                             <div class="header">
                            <h2> Bill Details</h2><br />
                            <h2 style="color: red;">
                                Bill No     :   <?php 
                                                    if(!empty($bills)){
                                                       echo $bills[0]['BillNo'];
                                                    }
                                                ?> &nbsp &nbsp&nbsp &nbsp &nbsp &nbsp
                                Retailer Name:  <?php 
                                                    if(!empty($bills)){
                                                       echo $bills[0]['RetailerName'];
                                                    }
                                                ?> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                 
                                    <div id="res"></div>
                                <table id="SrTable" class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                             <th>S. No.</th>
                                            <th style="display: none;"></th>
                                            <th>Bill No.</th>
                                            <th>Retailer</th>
                                            <th>Item</th>
                                            <th>MRP</th>
                                            <th>Billed Qty</th>
                                            <th>SR</th>
                                            <th>SR Received</th>
                                             <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                         <tr>
                                            <th>S. No.</th>
                                            <th style="display: none;"></th>
                                            <th>Bill No.</th>
                                            <th>Retailer</th>
                                            <th>Item</th>
                                            <th>MRP</th>
                                            <th>Billed Qty</th>
                                            <th>SR</th>
                                            <th>SR Received</th>
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
                                             $srQty=$this->GodownKeeperModel->getTotalSrQtyById($data['id']);
                                    ?>
                                             <tr>
                                                <td><?php echo $no;?></td>
                                               <td id="billId" style="display: none;"><?php echo $data['id'];?></td>
                                                <td id="billNo">
                                                    <?php echo $data['BillNo'];?>
                                                </td>
                                               <td><?php 
                                                $retailerName=substr($data['RetailerName'], 0, 30);
                                                echo $retailerName;?></td>
                                               
                                                <td><?php echo $data['productName'];?></td>
                                                <td align="right"><?php echo $data['mrp'];?></td>
                                                <td align="right"><?php echo $data['qty'];?></td>
                                                <td align="right"><?php echo $data['fsReturnQty'];?></td>
                                                <td id="srQty">
                                                    <input style="width: 50%" type="text" name="fsReturnQty" value="<?php echo number_format($data['fsReturnQty']); ?>">
                                                </td>
                                                <td>
                                                    <button onclick="updateSRqty(this);removeMe(this);" class="btn-primary waves-effect">
                                                        <i style="font-size : 11px;" class="material-icons">save</i> 
                                                        <span class="icon-name">
                                                        OK
                                                        </span>
                                                    </button>
                                                </td>
                                           </tr>
                                     <?php
                                            }
                                        }?>
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
<script type="text/javascript">
    function updateSRqty(e){
         var billNo=$(e).closest('tr').find('#billNo').text().trim();
         var billId=$(e).closest('tr').find('#billId').text().trim();
         var srQty=$(e).closest('tr').find('input').val();
         if((billNo!='' || billNo!=null) && (srQty) && (billId!='' || billId!=null)){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('godownkeeper/GodownKeeperController/updateSr');?>",
                data:{"billNo" : billNo,"billId" : billId,"srQty":srQty},
                success: function (data) {
                    // document.getElementById('res').innerHTML=data;
                    window.location.reload(true);
                }  
            });
         }else{
             document.getElementById('res').innerHTML='';
         }
    }
</script>
<script type="text/javascript">
    function removeMe(t) {
        $(t).closest('tr').remove();
    }
</script>

