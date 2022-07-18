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
<script   src="https://code.jquery.com/jquery-1.12.1.js" integrity="sha256-VuhDpmsr9xiKwvTIHfYWCIQ84US9WqZsLfR4P7qF6O8="   crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
      $.fn.dataTable.ext.errMode = 'none';
        $('.js-basic-example').DataTable( {
            dom: 'Bfrtip',
            // align:center,
            buttons: [
            'excel', 'pdf'
            ]
        } );
    } );
</script>





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
<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
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
                            Direct Delivery Bills 
                         </h2>
                         <!-- <p align="right">
                            <a href="<?php echo site_url('CashAndChequeController/NeftRegister');?>">
                              <button class="btn bg-primary margin"><i class="material-icons">refresh</i>    </button></a> 
                        </p> -->
                         
                     </div>
                     <div class="body">
                        
                      <div class="row">                                 
                        <div class="row m-t-20">

                            <div class="col-md-12 cust-tbl">
                                    <form method="post" role="form" action="">
                                        
                                         <div class="col-md-3">
                                            <b>Company Name </b>
                                        
                                            <select class="form-control" required id="comp" name="cmp">
                                                <option value="<?php echo $cmpName; ?>"><?php echo $cmpName; ?></option>
                                                <?php foreach ($company as $req_item){ ?>
                                                <option value="<?php echo $req_item['name'] ?>"><?php echo $req_item['name'] ?></option>
                                                <?php } ?> 
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                          <button type="submit" class="btn btnStyle m-t-20"><i class="material-icons">search</i>Search</button>
                                        </div>
                                    </form>

                                </div>
                         
                            <br>
                            <div class="col-md-12 ">
							<div class="table-responsive">
                                   <table class="table table-bordered dataTable js-exportable cust-tbl" data-page-length='100'>
                                        <!-- <?php print_r($retailer); ?> -->
                                    <thead>
                                        <tr class="gray">
                                            <th> No</th>
                                            <th> Bill No </th>
                                            <th> Bill Date  </th>
                                            <th> Retailer </th>
                                            <th> Bill </th>
                                            <th> SR </th>
                                            <th> Collection </th>
                                            <th> Pending  </th> 
                                            <th class="noSpace">Delivery Date</th>
                                            <th class="noSpace">Delivery Days</th> 
                                            <th> Employee </th>
                                            <th> Remark </th>
                                            <th> Action </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <?php
                                            $no=0;
                                            foreach ($adhocBills as $data) 
                                            {
                                               $no++; 

                                                $retailerCode=$this->AllocationByManagerModel->loadRetailer($data['retailerCode']);
                                                
                                                $getDeliveryDate=$this->AllocationByManagerModel->loadDeliveryDates('bill_remark_history',$data['id']);

                                                $directDelDate="";
                                                $diff=0;
                                                $remark="";
                                                if(!empty($getDeliveryDate)){
                                                    $directDelDate=$getDeliveryDate[0]['updatedAt'];
                                                    $remark=$getDeliveryDate[0]['remark'];
                                                    $dt=date_create($directDelDate);
                                                    $directDelDate = date_format($dt,'d-M-Y');
                                                    $diff=strtotime(date('Y-m-d'))-strtotime($getDeliveryDate[0]['updatedAt']);

                                                }

                                              $dt=date_create($data['date']);
                                              $createdDate = date_format($dt,'d-M-Y');

                                            ?>

                                            <?php if($data['isAllocated']==1){ ?>
                                                 <tr style="background-color: #dcd6d5">
                                            <?php }else{ ?>
                                                 <tr>
                                            <?php } ?>
                                             
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $data['billNo']; ?></td>
                                                <td class="noSpace"><?php echo $createdDate; ?></td>
												<td class="CellWithComment noSpace"><?php 
												$retailerName=substr($data['retailerName'], 0, 12);
												echo rtrim($retailerName);?> 
												<span class="CellComment"><?php echo $result =substr($data['retailerName'],0); ?></span> 
											    </td>
                                                <td align="right"><?php echo number_format($data['netAmount']); ?></td>
                                                <td align="right"><?php echo number_format($data['SRAmt']); ?></td>
                                                <td align="right"><?php echo number_format($data['receivedAmt']); ?></td>
                                                <td align="right"><?php echo number_format($data['pendingAmt']); ?></td>
                                                <td class="noSpace"><?php echo $directDelDate; ?></td>
                                                <td><?php echo abs(round($diff/86400));?></td>
                                              
												<td class="CellWithComment noSpace"><?php 
												$deliveryEmpName=substr($data['deliveryEmpName'], 0, 12);
												echo rtrim($deliveryEmpName);?> 
												<span class="CellComment"><?php echo $result =substr($data['deliveryEmpName'],0); ?></span> 
											    </td>
												
                                                <td>  
                                                  <a href="javascript:void();"  data-trigger="focus" data-container="body" data-toggle="popover" data-placement="left" title="Remark" data-content="<?php echo $remark; ?>">
                                        <i class="material-icons">menu</i>
                                    </a>
                                    </td>
                                    <td class="noSpace">
                                    <?php if($data['isAllocated']!=1){ ?>
                                    <!-- <button id="limit_id" data-id="<?php echo $data['id']; ?>" data-toggle="modal" data-target="#officeAdjustmentModal" class="btn bg-primary margin">Process</button> -->
                                    <a id="prDetailsForAll" href="javascript:void()" data-id="<?php echo $data['id']; ?>" data-salesman="<?php echo $data['salesman']; ?>" data-billDate="<?php echo $createdDate; ?>" data-credAdj="<?php echo $data['creditAdjustment']; ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>" data-gst="<?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?>" data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-route="<?php echo $data['routeName']; ?>" data-toggle="modal" data-target="#processModalForAll" ><button class="btn btn-xs process-btn waves-effect waves-float" data-toggle="tooltip" data-placement="bottom" title="Process"><i class="material-icons">touch_app</i></button></a>


                                    <!-- <a id="prDetails" href="javascript:void()" data-id="<?php echo $data['id']; ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>" data-gst="<?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?>" data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-toggle="modal" data-target="#processModal"><button class="btn btn-xs btn-primary waves-effect"><i class="material-icons">touch_app</i></button></a> -->

                                    <a href="<?php echo site_url('AdHocController/billHistoryInfo/'.$data['id']); ?>" class="btn btn-xs history-btn" data-toggle="tooltip" data-placement="bottom" title="View History"><i class="material-icons">info</i></a>
                                    <a href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs  viewBill-btn" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                                                
                                    <?php }else{
                                    $allocations=$this->AllocationByManagerModel->getAllocationDetailsByBill('bills',$data['id']);
                                    $officeAllocations=$this->AllocationByManagerModel->getOfficeAllocationDetailsByBill('bills',$data['id']);
                                                                            
                                    if(!empty($allocations)){
                                    echo "<p style='color:blue'>Allocated in : ".$allocations[0]['allocationCode']."</p>";
                                    }else if(!empty($officeAllocations)){
                                    echo "<p style='color:blue'>Allocated in : ".$officeAllocations[0]['allocationCode']."</p>";
                                    }
                                    }
                                    ?>

                                                </td>
                                              </tr>
                                                <?php
                                            }
                                            ?> 
                                    </tbody>
                                    <tfoot>
                                        <tr class="gray">
                                            <th> No</th>
                                            <th> Bill No </th>
                                            <th> Bill Date  </th>
                                            <th> Retailer </th>
                                            <th> Bill </th>
                                            <th> SR </th>
                                            <th> Collection </th>
                                            <th> Pending  </th> 
                                            <th class="noSpace">Delivery Date</th>
                                            <th class="noSpace">Delivery Days</th> 
                                            <th> Employee </th>
                                            <th> Remark </th>
                                            <th> Action </th>
                                        </tr>
                                    </tfoot>    
                            </table>
                        </div>
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

<?php $this->load->view('/layouts/processButtonView'); ?>

<script type="text/javascript">
    $( "#cmp").click(function() {
      $( "#cmp" ).select();
    });
</script>