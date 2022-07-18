<?php $this->load->view('/layouts/commanHeader'); ?>
 
<style type="text/css">
    @media screen and (min-width: 1100px) {
        .modal-dialog {
          width: 1100px; /* New width for default modal */
        }
        .modal-sm {
          width: 400px; /* New width for small modal */
        }
    }

    @media screen and (min-width: 1100px) {
        .modal-lg {
          width: 1100px; /* New width for large modal */
        }
    }

</style>
<script src="<?php echo base_url('assets/js/pages/ui/tooltips-popovers.js');?>"></script>

<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br>
<section class="col-md-12 box">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header flex-div">
                        <h2 style="width: 88%;"><center><?php echo $bills[0]['retailerName'];?></center></h2>
                        <h2 align="right">
                        <a class="btn btn-xs btn-primary btnStyle" href="javascript:window.history.go(-1);"><i class="material-icons">arrow_back</i></a> 
                        </h2>
                            <!--<h2 align="right">
                            <span>
                               <a class="btn btn-xs btn-primary" href="javascript:void();"><i class="material-icons">touch_app</i></a>
                             &nbsp;&nbsp;<a class="btn btn-xs btn-primary" href="javascript:window.history.go(-1);"><i class="material-icons">arrow_back</i></a></span>
                            </h2>-->
                    </div>
                    <div class="body">
                        <div class="row m-t-20 cust-tbl">
                            <div class="container">
                            <!-- <div class="col-md-12"> -->
                                
                                    <div class="col-md-4 mb-0">
                                     Bill No. : <span><?php echo $bills[0]['billNo'];?></span>
                                    </div>
                                    <div class="col-md-4 mb-0">
                                     Bill Date : <span><?php 
                                              $dt=date_create($bills[0]['date']);
                                              $date = date_format($dt,'d-M-Y'); 
                                              echo $date;
                                          ?>
                                          </span>
                                        
                                    </div>

                                    <div class="col-md-4 mb-0">
                                    Bill Amount : <span><?php echo $bills[0]['netAmount'];?></span>
                                    </div>

                                    <div class="col-md-4 mb-0">
                                    Salesman : <span><?php echo $bills[0]['salesman'];?></span>
                                    </div>
                                
                                    <div class="col-md-4 mb-0 cust-tbl">
                                    Retailer Code : <span><?php echo $bills[0]['retailerCode'];?></span>
                                    </div>
                                    
                                    <div class="col-md-4 mb-0">
                                    Route : <span><?php echo $bills[0]['routeName'];?></span>
                                    </div>
                               
                               <!-- <span>-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</span><br> -->
                            <!-- </div> -->

                        <div class="col-md-12">
                         <hr class="w-100 m-t-15">
                        <table class="table table-bordered cust-tbl" data-page-length='100'>
						
                                    <thead>
                                         <tr>
                                            <th>No</th>
                                            <th>Product Name</th>
                                            <th><span class="pull-right">MRP</span></th>
                                            <th><span class="pull-right">Quantity</span></th>
                                            <th><span class="pull-right">Net Amount</span></th>
                                            <th><span class="pull-right">Scheme Discount</span></th>
                                            <th><span class="pull-right">Distributor Discount</span></th>
                                            <th><span class="pull-right">Rate/Pcs</span></th>
                                            <th><span class="pull-right">SR Quantity</span></th>
                                            <th><span class="pull-right">SR Amount</span></th>
                                        </tr>
                                    </thead>
                                   
                                    <tbody>
                                <?php 
                                    $no=0;
                                    $total=0;
                                    $srTotal=0;
                                    if(!empty($billsdetails)){

                                        foreach($billsdetails as $data){
                                            $no++;
                                            $total=$total+$data['netAmount'];
                                            $srTotal=$srTotal+$data['fsReturnAmt'];
                                ?>      
                                            <tr>
                                                <td><?php echo $no;?></td>
												<td class="CellWithComment" style="width: 210px;"><?php //echo  rtrim($data['productName'],', '); 
												$productName=substr($data['productName'], 0, 20);
												echo rtrim($productName);?>
												<span class="CellComment"><?php echo $result =substr($data['productName'],0); ?></span>
											    </td>
                                                <td align="right"><?php echo $data['mrp'];?></td>
                                                <td align="right"><?php echo $data['qty'];?></td>
                                                <td align="right"><?php echo $data['netAmount'];?></td>
                                                <td align="right">
                                                    <?php 
                                                    if($data['grossRate'] !=0){
                                                        $total=($data['schemaDisc']+$data['keyDisc']+$data['rdDisc']+$data['splDisc']);
                                                        if($total !=0){
                                                            $cal=($total/$data['grossRate'])*100;
                                                            // echo round($cal,2).' %';
                                                            echo number_format($cal, 2, '.', ',').'%';
                                                        }
                                                       
                                                    }
                                                   
                                                    ?>
                                                </td>
                                                <td align="right">
                                                    <?php 
                                                    if($data['grossRate'] !=0){
                                                        $total=($data['cddbDisc']);
                                                        if($total !=0){
                                                            $cal=($total/$data['grossRate'])*100;
                                                            // echo round($cal,2).' %';
                                                            echo number_format($cal, 2, '.', ',').'%';
                                                        }
                                                        
                                                    }
                                                    ?>
                                                </td>
                                                <td align="right">
                                                  <?php echo number_format(($data['netAmount']/$data['qty']),2);?>
                                                </td>
                                                <?php if($data['fsReturnQty'] >0){ ?>
                                                  <td align="right"><?php echo ($data['fsReturnQty']+$data['gkReturnQty']);?></td>
                                                <?php }else{ ?>
                                                  <td align="right"><?php echo $data['gkReturnQty'];?></td>
                                                <?php } ?>
                                                
                                                <td align="right"><?php echo $data['fsReturnAmt'];?></td>
                                            </tr>
                                <?php 
                                        }
                                        ?>
                                         <tr>
                                                <th>Total</th>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td align="right"><b><?php echo $srTotal;?></b></td>
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
</section>
    
<?php $this->load->view('/layouts/footerDataTable'); ?>