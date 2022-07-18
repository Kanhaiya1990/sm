<?php $this->load->view('/layouts/commanHeader'); ?>

    <h1 style="display: none;">Welcome</h1><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <!-- <div class="block-header">
                <h2>
                    Cash
                </h2>
                
            </div> -->
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h4 style="color: #099dba;">
                            
                           <span style="color: black;">Bill No :</span> 
                            <?php 
                                if(!empty($billDetail)){
                                     echo $billDetail[0]['billNo'];
                                }
                            ?>
                            &nbsp; &nbsp;
                             <span style="color: black;">Retailer Name :</span> 
                            <?php 
                                if(!empty($billDetail)){
                                     echo $billDetail[0]['retailerName'];
                                }
                            ?>
                            &nbsp; &nbsp;
                            </h4>
                        </div>
                            
                        <div class="body">
                        <div class="table-responsive">
                        <div class="body">
                            <div class="demo-masked-input">
                                    <div class="row clearfix">
                                         <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100' id='tbl'>
                                                <thead>
                                                    <tr>
                                                        <th>Sr.No</th>
                                                        <th>Item</th>
                                                        <th>Billed Qty</th>
                                                        <th>GK SR</th>
                                                        <th>Additional</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th>Sr.No</th>
                                                        <th>Item</th>
                                                        <th>Billed Qty</th>
                                                        <th>GK SR</th>
                                                        <th>Additional</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    <?php
                                                    $no=0;
                                       
                                        foreach ($billDetail as $data) 
                                          { 
                                             $no++; 
                                        ?>
                                            <tr>
                                                <td><?php echo $no;?></td>
                                                <td><?php echo $data['productName'];?></td>
                                                <td><?php echo number_format($data['qty']);?></td>
                                                <td><?php echo number_format($data['gkReturnQty']);?></td>
                                                <td><input id='sr'style="width: 70px" type="text" name="additional">
                                                    <div id="srError" style="color: red"></div>
                                                </td>
                                                
                                                <td>
                                                    <button style="font-size: 12px" onclick="updateSRqty(this,'<?php echo $data['id'];?>','<?php echo $data['billId'];?>');" class=" btn-primary waves-effect">
                                                    <i style="font-size: 12px" class="material-icons">save</i> 
                                                    <span class="icon-name">
                                                    Received
                                                    </span>
                                                </button>
                                           
                                                <button style="font-size: 12px" onclick="debitSRqty(this,'<?php echo $data['id'];?>');" class="btn-primary waves-effect">
                                                    <i style="font-size: 12px" class="material-icons">save</i> 
                                                    <span class="icon-name">
                                                    Debit
                                                    </span>
                                                </button>
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
                </div>
            </div>
            <!-- #END# Basic Examples -->  
        </div>
    </div>
    </section>
   <?php $this->load->view('/layouts/footerDataTable'); ?>
<script type="text/javascript">
    function updateSRqty(e,id,billId){
        var srQty=$(e).closest('tr').find('input').val();
        if(srQty){
            document.getElementById('srError').innerHTML="";
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('manager/SrCheckController/ReceivedSR');?>",
                data:{"srQty" : srQty,"id" : id,"billId" : billId},
                success: function (data) {
                    parent.$.fn.colorbox.close();window.parent.location.reload(true)
                    // document.getElementById('srError').innerHTML=data;
                }  
            });

        }else{
           document.getElementById('srError').innerHTML='Please Enter Qty';
        }
    }

    function debitSRqty(e,id){
        var srQty=$(e).closest('tr').find('input').val();
        if(srQty){
            document.getElementById('srError').innerHTML="";
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('manager/SrCheckController/DebitSR');?>",
                data:{"srQty" : srQty,"id" : id},
                success: function (data) {
                     document.getElementById('srError').innerHTML=data;
                }  
            });
        }else{
           document.getElementById('srError').innerHTML='Please Enter Qty';
        }
    }

    function removeMe(t) {
        $(t).closest('tr').remove();
    }
</script>
