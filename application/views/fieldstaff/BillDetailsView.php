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
                            
                        <div class="body">
                            <div class="table-responsive">
                                 
                                 <form method="post" role="form" action="<?php echo site_url('FieldStaff/FieldStaffController/updateSR');?>"> 
                                    
                                    <div style="color:red;" id="sr_qty"></div>
                                <table id="SrTable" class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Product Name</th>
                                            <th>MRP</th>
                                            <th>Billed Qty</th>
                                            <th>Net Amount</th>
                                            <th>Old SR</th>
                                            <th>SR Qty</th>
                                            <th>Return Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                         <tr>
                                            <th>Sr.No</th>
                                            <th>Product Name</th>
                                            <th>MRP</th>
                                            <th>Qty</th>
                                            <th>Net Amount</th>
                                            <th>Old SR</th>
                                            <th>SR Qty</th>
                                             <th>Return Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                          $no=0;
                                          foreach ($bills as $data) 
                                            {
                                             $no++; 
                                          ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $data['productName']; ?></td>
                                         <input type="hidden" name="productName[]" value="<?php echo $data['productName']; ?>" readonly>
                                        <td>
                                            <?php echo $data['mrp']; ?> 
                                            <input type="hidden" name="mrp[]" value="<?php echo $data['mrp']; ?>" readonly>
                                        </td>
                                        <td>
                                            <?php echo $data['qty']; ?>
                                              <input type="hidden" name="qty[]" value="<?php echo $data['qty']; ?>" readonly> 
                                        </td> 
                                        <td>
                                            <?php echo $data['netAmount']; ?>
                                            <input type="hidden" name="netAmount[]" value="<?php echo $data['netAmount']; ?>" readonly>
                                            <input type="hidden" name="selAmount[]" value="<?php echo $data['sellingRate']; ?>" readonly>         
                                        </td>
                                         <td>
                                             <?php echo $data['returnedQty']; ?>
                                             <input type="hidden" name="id[]" value="<?php echo $data['id']; ?>">
                                             <input type="hidden" name="billId" value="<?php echo $data['billId']; ?>">
                                        </td>
                                        <td>
                                        <?php if($data['qty']==$data['returnedQty']){?>
                                            <input type="text" id="returnedQty" class="form-control" name="returnedQty[]" value="<?php echo number_format($data['fsReturnQty']);?>">
                                            
                                         <?php }else{?>
                                             <input onblur="checkQty();" type="text" class="form-control" name="returnedQty[]" value="<?php echo number_format($data['fsReturnQty']);?>"> 
                                         <?php }?>
                                        </td>
                                        <td>
                                            <?php echo $data['fsReturnAmt']; ?>  
                                             <input type="hidden" name="returnAmt[]" value="<?php echo $data['returnAmt']; ?>">             
                                        </td>
                                        <td>
                                            <a>
                                                <button style="font-size : 9px;" class="btn-primary waves-effect"><span>OK</span></button>
                                            </a>
                                             <a>
                                                <button style="font-size : 9px;" class="btn-primary waves-effect"><span>Edit</span></button>
                                            </a>
                                        </td>
                                        
                                   </tr>  
                                     <?php
                                        }
                                      ?>   
                                    </tbody>
                                </table>
                                 
                                <!-- <?php form_close();?> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Examples -->  
        </div>
    </section>

   <!--  <script>
        function getFSR(){
           var table = document.getElementById('SrTable');
            for (var i = 1; i < table.rows.length-1; i++) {
              if (table.rows[i].cells.length) {
                var billedQty = (table.rows[i].cells[3].textContent.trim());
                var oldSR = (table.rows[i].cells[5].textContent.trim());
                billedQty=billedQty-oldSR;
                var srQty = (table.rows[i].cells[6].textContent.trim());
                table.rows[i].cells[6].innerHTML='<input id="returnedQty" type="text" class="form-control" name="returnedQty[]" value="'+billedQty+'">';
              }
            }
        }
    </script> -->
    <!-- <script type="text/javascript">
        function checkQty(){
            var tbl=document.getElementById('SrTable');
            var msg=document.getElementById('sr_qty');
            msg.innerHTML="";
            var currentQty=0;
            for(var i=1;i<tbl.rows.length-1;i++){
                if(tbl.rows[i].cells.length){
                    var billQty=(tbl.rows[i].cells[3].textContent.trim());
                    var oldSR=(tbl.rows[i].cells[5].textContent.trim());
                    currentQty=parseInt(billQty)-parseInt(oldSR);
                    var srQty=(tbl.rows[i].cells[6].children[0].value);
                    if(typeof srQty != 'undefined' && srQty){
                        if(srQty>currentQty){
                            msg.innerHTML="Sale Return Quantity can not be greater than Billed  Quantity";
                            tbl.rows[i].cells[6].children[0].value="";
                            msg.innerHTML="";
                        }else{
                            msg.innerHTML="";
                        }
                    }
                }
            }
        }
    </script>
    -->

 <?php $this->load->view('/layouts/footerDataTable'); ?>