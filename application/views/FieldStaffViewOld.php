<?php $this->load->view('/layouts/commanHeader'); ?>

 <script   src="https://code.jquery.com/jquery-1.12.1.js"   integrity="sha256-VuhDpmsr9xiKwvTIHfYWCIQ84US9WqZsLfR4P7qF6O8="   crossorigin="anonymous"></script>
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
<!--<section class="content">-->
<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Deliveryman Master</h2>
            </div>
              <!-- Basic Examples -->
            <div class="row clearfix" id="page">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Deliveryman
                            </h2>
                         
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a data-action="collapse" aria-controls="page"><i class="ft-minus"></i>-</a></li>
                                        <li><a data-action="expand"  aria-controls="page"><i class="ft-maximize"><-0-></i></a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                              <div class="row">
                                <div class="col-md-2">
                                    <label id="allocation">Allocation : </label>
                                    <?php echo $allocations[0]['allocationCode']?>
                                    <br />
                                </div>
                                <div class="col-sm-2">
                                    <label>Employees: </label>
                                    <!-- <ul class="list-group" id="list" multiple="multiple"> -->
                                        <?php
                                        $total=0;
                                        $emp1='';
                                         $emp2='';
                                          $emp3='';
                                           $emp4='';
                                            if(!empty($allocations[0]['fieldStaffCode1'])){
                                                $emp1= $this->AllocationByManagerModel->getEmpName('employee',$allocations[0]['fieldStaffCode1']);
                                                $emp1=$emp1[0]['name'];
                                                ?>
                                                 <span><?php echo $emp1;?> </span>
                                                 <?php
                                            }
                                             if(!empty($allocations[0]['fieldStaffCode2'])){
                                                $emp2= $this->AllocationByManagerModel->getEmpName('employee',$allocations[0]['fieldStaffCode2']);
                                                $emp2=$emp2[0]['name'];
                                                ?>
                                                  <span><?php echo $emp2;?> </span>
                                                  <?php
                                            }
                                             if(!empty($allocations[0]['fieldStaffCode3'])){
                                                $emp3= $this->AllocationByManagerModel->getEmpName('employee',$allocations[0]['fieldStaffCode3']);
                                                $emp3=$emp3[0]['name'];
                                                ?>
                                                 <span><?php echo $emp3;?> </span>
                                                  <?php
                                            }
                                             if(!empty($allocations[0]['fieldStaffCode4'])){
                                                $emp4= $this->AllocationByManagerModel->getEmpName('employee',$allocations[0]['fieldStaffCode1']);
                                                $emp4=$emp4[0]['name'];
                                                ?>
                                                 <span><?php echo $emp4;?></span>
                                                 <?php
                                            }
                                            
                                        ?>
                                    <!-- </ul> -->
                                   
                                </div>
                                <div class="col-md-2">
                                    <label id="route">Route : </label>
                                    <?php 
                                        // $rid= $bills[0]['route'];
                                        $rID=explode(",",rtrim($bills[0]['route'],','));
                                        $routeName='';
                                       for($i=0;$i<count($rID);$i++){
                                        $routes=$this->AllocationByManagerModel->getRouteNameById($rID[$i]);
                                        $routeName=$routeName.' '.$routes[0]['name'].',';
                                       }
                                        // $rname=$this->AllocationByManagerModel->getRouteNameById($rid);
                                        echo rtrim($routeName,',');
                                    ?>
                                    <br />
                                </div>
                            <!-- </div>
                            <div class="row"> -->
                               
                                </div>
                            <div class="row">
                             <!--    <div class="col-md-3">
                                    <p class="gray-head" style="background-color: gray;border: 1px; text-align: center;color: black"><b>Reconcile</b></p>
                                    <p>
                                        <a href="#" class="btn btn-sm btn-info">Credit Bills</a>
                                        <a href="#" class="btn btn-sm btn-success pull-right">Cash &Cheque</a>
                                    </p>
                                </div> -->                                  
                                <div class="row m-t-20">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered" id="tbl">
                                                <tr class="head">
                                                    <td colspan="12" style="background-color: whitesmoke;"><center><b>Current Supply Bills</b></center></td>
                                                </tr>
                                                <tr class="gray">
                                                    <th>S No.</th>
                                                    <th>Bill No.</th>
                                                    <th>Bill Date</th>
                                                    <th>Retailer Name</th>
                                                    <th>Bill Amt</th>
                                                    <th>Sale Return</th>
                                                    <th>Past Coll.</th>
                                                    <!-- <th>USR</th>
                                                    <th>CD</th> -->
                                                    <!-- <th>Status</th> -->
                                                    <th>Pending Amt</th>
                                                    <!-- <th>Today's Coll.</th> -->
                                                    <th>Action</th>
                                                </tr>
                                                <tbody id="result_data">
                                                    <tr>
                            <?php
                                        $no=0;
                                         if(!empty($current)){
                                        foreach ($current as $data) 
                                          {
                                            $total=$total+$data['netAmount'];
                                           $no++; 
                                           $retailerName=$this->AllocationByManagerModel->getRetailerName($data['retailerCode']);
                                           $retailerName=$retailerName[0]['name'];
                                    ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                        
                                            <td>
                                                <?php echo $data['billNo']; ?>
                                            </td>
                                             <?php

                                                $dt=date_create($data['date']);
                                                $date = date_format($dt,'d-m-Y');
                                            ?>
                                            <td><?php echo $date; ?></td>
                                            <td><?php echo $retailerName; ?></td>
                                            <td><?php echo $data['netAmount']?></td>
                                            <td><?php echo $data['SRAmt']?></td>
                                            <!-- <td><?php echo $data['receivedAmt']?></td> -->
                                             <td><?php echo $data['receivedAmt']?></td>
                                            <!-- <td></td> -->
                                            <!-- <td><?php echo $data['cashDiscount']?></td> -->
                                            <td><?php echo $data['pendingAmt']?></td>
                                            <!-- <td><?php echo $data['receivedAmt']?></td> -->
                                            <td> 
                                            <a class="iframe" href="<?php echo base_url().'index.php/SrController/load/'.$data['id'];  ?>">
                                                <button class="btn btn-primary waves-effect" data-type="basic"><i class="material-icons">save</i><span>SR/FSR</span></button>
                                            </a>
                                            <a>
                                                <button class="btn btn-primary waves-effect" data-type="basic"><i class="material-icons">save</i><span>Bill</span></button>
                                            </a>
                                            <a>
                                                <button class="btn btn-primary waves-effect" data-type="basic"><i class="material-icons">save</i><span>Cash</span></button>
                                            </a>
                                            <a>
                                                <button class="btn btn-primary waves-effect" data-type="basic"><i class="material-icons">save</i><span>Cheque</span></button>
                                            </a>
                                            <a>
                                                <button class="btn btn-primary waves-effect" data-type="basic"><i class="material-icons">save</i><span>Resend</span></button>
                                            </a>
                                        </td>
                                        </tr>
                                    <?php
                                        }
                                    }
                                      ?> 
                                  </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            
                                            <table class="table table-striped table-bordered">
                                            <!--Past Bills-->
                                            <tr class="head">
                                                <td colspan="12"  style="background-color: whitesmoke;"><center><b>Past Bills</b></center></td>
                                            </tr>
                                            <tr class="gray">
                                                <th>S No.</th>
                                                <th>Bill No.</th>
                                                <th>Bill Date</th>
                                                <th>Retailer Name</th>
                                                <th>Bill Amt</th>
                                                <th>Sale Return</th>
                                                <th>Past Collection</th>
                                                <!-- <th>USR</th> -->
                                                <!-- <th>CD</th> -->
                                                <th>Pending Amt</th>
                                                <!-- <th>Status</th> -->
                                                <!-- <th>Today's Collection</th> -->
                                                <th>Action</th>
                                            </tr>
                                            <tbody id="result_past">
                                                 <tr>
                            <?php
                                        $no=0;
                                        if(!empty($pass)){
                                        foreach ($pass as $data) 
                                          {
                                            $total=$total+$data['netAmount'];
                                           $no++; 
                                            $retailerName=$this->AllocationByManagerModel->getRetailerName($data['retailerCode']);
                                           $retailerName=$retailerName[0]['name'];
                                    ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                        
                                            <td>
                                                <?php echo $data['billNo']; ?>
                                            </td>
                                             <?php
                                                $dt=date_create($data['date']);
                                                $date = date_format($dt,'d-m-Y');
                                            ?>
                                            <td><?php echo $date; ?></td>
                                            <td><?php echo $retailerName; ?></td>
                                            <td><?php echo $data['netAmount']?></td>
                                            <td><?php echo $data['SRAmt']?></td>
                                             <td><?php echo $data['receivedAmt']?></td>
                                            <!-- <td></td> -->
                                           <!-- <td><?php echo $data['cashDiscount']?></td> -->
                                            <td><?php echo $data['pendingAmt']?></td>
                                            <!-- <td><?php echo $data['receivedAmt']?></td> -->
                                            <td> 
                                            <a class="iframe" href="<?php echo base_url().'index.php/SrController/load/'.$data['id'];  ?>">
                                                <button class="btn btn-primary waves-effect" data-type="basic"><i class="material-icons">save</i><span>SR/FSR</span></button>
                                            </a>
                                            <a>
                                                <button class="btn btn-primary waves-effect" data-type="basic"><i class="material-icons">save</i><span>Bill</span></button>
                                            </a>
                                            <a>
                                                <button class="btn btn-primary waves-effect" data-type="basic"><i class="material-icons">save</i><span>Cash</span></button>
                                            </a>
                                            <a>
                                                <button class="btn btn-primary waves-effect" data-type="basic"><i class="material-icons">save</i><span>Cheque</span></button>
                                            </a>
                                            <a>
                                                <button class="btn btn-primary waves-effect" data-type="basic"><i class="material-icons">save</i><span>Resend</span></button>
                                            </a>
                                        </td>
                                        </tr>
                                    <?php
                                        }
                                    }
                                      ?> 
                                  </tr>
                                            </tbody>
                                           
                                           
                                            </table>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                            <tr class="head">
                                                <td colspan="10"  style="background-color: whitesmoke;"><center><b>Bounced Cheques</b></center></td>
                                            </tr>
                                            <tr class="gray">
                                                <th>S No.</th>
                                                <th>Cheque No.</th>
                                                <th>Cheque Date</th>
                                                <!-- <th>USR</th>
                                                <th>CD</th> -->
                                                <th>Retailer Name</th>
                                                <th>Principal Amt</th>
                                                <th>Penalty</th>
                                                <th>Past Collection</th>
                                                <th>Pending Amt</th>
                                                <!-- <th>Status</th> -->
                                                <th>Today's Collection</th>
                                                <th>Remove</th>
                                            </tr>
                                          <tbody id="result_bounced">
                                            </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                     <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                            <!--Delivery Challan -->
                                            <tr class="head">
                                            <td colspan="11"  style="background-color: whitesmoke;"><center><b>DeliverySlip Bills</b></center></td>
                                            </tr>
                                            <tr class="gray">
                                                <th>S No.</th>
                                                <th>Bill No.</th>
                                                <th>Bill Date</th>
                                                <th>Retailer Name</th>
                                                <th>Bill Amt</th>
                                                <th>Sale Return</th>
                                                <th>Past Collection</th>
                                                <!-- <th>USR</th> -->
                                                <!-- <th>CD</th> -->
                                                <th>Pending Amt</th>
                                                <!-- <th>Status</th> -->
                                                <!-- <th>Today's Collection</th> -->
                                                <th>Remove</th>
                                            </tr>
                                            <tbody id="result_delivery">
                                                <tr>
                            <?php
                                        $no=0;
                                        if(!empty($slip)){
                                        foreach ($slip as $data) 
                                          {
                                            $total=$total+$data['netAmount'];
                                           $no++; 
                                            $retailerName=$this->AllocationByManagerModel->getRetailerNameById($data['retailerId']);
                                           $retailerName=$retailerName[0]['name'];
                                    ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                        
                                            <td>
                                                <?php echo $data['billNo']; ?>
                                            </td>
                                             <?php
                                                $dt=date_create($data['date']);
                                                $date = date_format($dt,'d-m-Y');
                                            ?>
                                            <td><?php echo $date; ?></td>
                                            <td><?php echo $retailerName; ?></td>
                                            <td><?php echo $data['netAmount']?></td>
                                            <td><?php echo $data['SRAmt']?></td>
                                             <td><?php echo $data['receivedAmt']?></td>
                                            <!-- <td></td> -->
                                           
                                            <td><?php echo $data['pendingAmt']?></td>
                                            <!-- <td><?php echo $data['receivedAmt']?></td> -->
                                            <td> 
                                            <a class="iframe" href="<?php echo base_url().'index.php/SrController/load/'.$data['id'];  ?>">
                                                <button class="btn btn-primary waves-effect" data-type="basic"><i class="material-icons">save</i><span>SR/FSR</span></button>
                                            </a>
                                            <a>
                                                <button class="btn btn-primary waves-effect" data-type="basic"><i class="material-icons">save</i><span>Bill</span></button>
                                            </a>
                                            <a>
                                                <button class="btn btn-primary waves-effect" data-type="basic"><i class="material-icons">save</i><span>Cash</span></button>
                                            </a>
                                            <a>
                                                <button class="btn btn-primary waves-effect" data-type="basic"><i class="material-icons">save</i><span>Cheque</span></button>
                                            </a>
                                            <a>
                                                <button class="btn btn-primary waves-effect" data-type="basic"><i class="material-icons">save</i><span>Resend</span></button>
                                            </a>
                                        </td>
                                        </tr>
                                    <?php
                                        }
                                    }
                                      ?> 
                                  </tr>

                                            </tbody>
                                            </table>
                                        </div>
                                    </div>
                                   
                                    <div class="col-md-4"></div>
                                    <div class="col-md-6">
                                        <?php echo validation_errors(); ?>
                                        <?php echo form_open_multipart('AllocationByManagerController/fieldStaffHisaab') ?>
                                        <p id="ins"></p>
                                        <p>
                                            
                                    <a href="<?php echo site_url('AllocationByManagerController/fieldStaffHisaab');?>">
                                        <button type="button" id="insert-ins" class="btn btn-primary m-t-15 waves-effect">
                                              <i class="material-icons">sync</i> 
                                              <span class="icon-name"> Finalise & Sync</span>
                                        </button>
                                     </a>     

                                            <a href="<?php echo site_url('AllocationByManagerController/');?>">
                                                <button type="button" class="btn btn-danger m-t-15 waves-effect">
                                                    <i class="material-icons">cancel</i> 
                                                    <span class="icon-name"> Cancel </span>
                                                </button>
                                            </a>  
                                        </p>
                                         <?php echo form_close(); ?>
                                    </div>
                                    <div class="col-md-2"></div>
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
      <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>

       <!-- Bootstrap Core Js -->
    <script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.js');?>"></script>

   <!-- Select Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/bootstrap-select/js/bootstrap-select.js');?>"></script>

     <!-- Slimscroll Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/jquery-slimscroll/jquery.slimscroll.js');?>"></script>

    <!-- Bootstrap Colorpicker Js -->
    <script src="<?php echo base_url('assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js');?>"></script>

    <!-- Dropzone Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/dropzone/dropzone.js');?>"></script>

    <!-- Input Mask Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/jquery-inputmask/jquery.inputmask.bundle.js');?>"></script>

    <!-- Multi Select Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/multi-select/js/jquery.multi-select.js');?>"></script>

    <!-- Jquery Spinner Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/jquery-spinner/js/jquery.spinner.js');?>"></script>

    <!-- Bootstrap Tags Input Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js');?>"></script>

    <!-- noUISlider Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/nouislider/nouislider.js');?>"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/node-waves/waves.js');?>"></script>

    <!-- Custom Js -->
    <script src="<?php echo base_url('assets/js/admin.js');?>"></script>
    <script src="<?php echo base_url('assets/js/pages/forms/advanced-form-elements.js');?>"></script>

    <!-- Demo Js -->
    <script src="<?php echo base_url('assets/js/demo.js');?>"></script>
    <!-- SweetAlert Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/sweetalert/sweetalert.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/pages/ui/dialogs.js');?>"></script>
    <!-- Bootstrap Notify Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/bootstrap-notify/bootstrap-notify.js');?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7"></script>

