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

        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>

        <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
           
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Unaccounted Bills 
                            </h2><br/>
                        </div>
                        <div class="body">
                            <div class="row m-t-20">
                                <div class="col-md-12">
                                    <form method="post" role="form" action="">
                                        
                                        <div class="col-md-3">
                                            <b>Division Name </b>
                                        
                                            <select class="form-control" required id="comp" name="cmp">
                                                <option value="<?php echo $cmpName; ?>"><?php echo $cmpName; ?></option>
                                                <?php foreach ($company as $req_item){ ?>
                                                <option value="<?php echo $req_item['name'] ?>"><?php echo $req_item['name'] ?></option>
                                                <?php } ?> 
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                        <button type="submit" class="btn m-t-20 btn-primary">Search</button>
                                    </div>
                                    </form>

                                </div>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-exportable dataTable" data-page-length='25'>
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Bill No </th>
                                            <th>Bill Date</th>
                                            <th>Retailer Name</th>
                                            <th>Salesman Name</th>
                                            <th>Route</th>
                                            <th>Net Amount</th>
                                            <th>Pending Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                         <tr>
                                            <th>Sr.No</th>
                                            <th>Bill No </th>
                                            <th>Bill Date</th>
                                            <th>Retailer Name</th>
                                            <th>Salesman Name</th>
                                            <th>Route</th>
                                            <th>Net Amount</th>
                                            <th>Pending Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        $no=0;
                                        foreach ($bills as $data) 
                                          {
                                           $no++; 
                                            $start = strtotime(date('Y-m-d'));
                                            $end = strtotime($data['date']);
                                            $hiDateDiff = ceil(abs($end - $start) / 86400);

                                           $dt=date_create($data['date']);
                                              $createdDate = date_format($dt,'d-M-Y');
                                        ?>
                                        <?php if($data['isAllocated']==1){ ?>
                                                <tr style="background-color: #dcd6d5">
                                        <?php }else if($hiDateDiff >= $highlightValue){ ?>
                                                <tr style="background-color: #c8e090">
                                        <?php }else{ ?>
                                            <tr>
                                        <?php } ?>
                                            <td><?php echo $no; ?></td>
                                            <td><?php echo $data['billNo']; ?></td>
                                            <td><?php echo $createdDate; ?></td>
                                            <td><?php echo $data['retailerName']; ?></td>
                                            <td><?php echo $data['salesman']; ?></td>
                                            <td><?php echo $data['routeName']; ?></td>
                                            <td align="right"><?php echo number_format($data['netAmount']); ?></td>
                                            <td align="right"><?php echo number_format($data['pendingAmt']); ?></td>
                                            <td>
                                            <?php if($data['isAllocated']!=1){ ?>
                                                <?php if($this->session->userdata['workRestrict']['status']=="yes"){?>
                                                    <button class="btn btn-xs btn-primary waves-effect waves-float" onclick="planUpgradeMsg()"><i class="material-icons">touch_app</i></button>
                                                <?php }else{ ?>
                                                    <a id="prDetailsForAll" href="javascript:void()" data-id="<?php echo $data['id']; ?>" data-salesman="<?php echo $data['salesman']; ?>" data-billDate="<?php echo $createdDate; ?>" data-credAdj="<?php echo $data['creditAdjustment']; ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>" data-gst="<?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?>" data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-route="<?php echo $data['routeName']; ?>" data-toggle="modal" data-target="#processModalForAll" ><button class="btn btn-xs btn-primary waves-effect waves-float" data-toggle="tooltip" data-placement="bottom" title="Process"><i class="material-icons">touch_app</i></button></a>
                                                <?php } ?>
                                            <?php } ?>
                                                <!-- <a id="prDetails" href="javascript:void()" data-id="<?php echo $data['id']; ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>" data-gst="<?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?>" data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-toggle="modal" data-target="#processModal"><button class="btn btn-xs btn-primary waves-effect"><i class="material-icons">touch_app</i></button></a> -->
                                                &nbsp;&nbsp;<a href="<?php echo site_url('AdHocController/billHistoryInfo/'.$data['id']); ?>" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="View History"><i class="material-icons">info</i></a>
                                                &nbsp;&nbsp;<a href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs  btn-primary" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>

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
