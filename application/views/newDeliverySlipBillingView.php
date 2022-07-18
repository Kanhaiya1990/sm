<?php $this->load->view('/layouts/commanHeader'); 

    $projectSessionName="";

    if (isset($this->session->userdata['codeKeyData'])) {
      $projectSessionName= ($this->session->userdata['codeKeyData']['codeKeyValue']);
    }
    $designation = ($this->session->userdata[$projectSessionName]['designation']);
    $des=explode(',',$designation);
?>


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

    hr.dotted {
      border-top: 3px dotted #bbb;
    }

</style>
<script src="<?php echo base_url('assets/js/pages/ui/tooltips-popovers.js');?>"></script>

<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
  <section class="col-md-12 box" style="height: auto;">
    <div class="container-fluid">
      <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div id="frm_AddItem" class="card">
            <div class="header flex-div">
              <h2>New Delivery Slip Master </h2>
              <h2>
                  <p align="right">
                        <button data-toggle="modal" data-target="#prodModal" class="modalLink btn btn-xs btn-primary btnStyle m-t-15 waves-effect">
                            <span class="icon-name"> <i class="material-icons">person_add</i>Add Product </span>
                        </button>

                        <button data-toggle="modal" data-target="#retailerModal" class="modalLink btn btn-xs btn-primary btnStyle m-t-15 waves-effect">
                            <span class="icon-name"> <i class="material-icons">person_add</i>Add Retailer </span>
                        </button>
                  </p>
              </h2>
          </div>
          <?php if($this->session->flashdata('msg')): ?>
            <p align="center" class="bg-primary"><?php echo $this->session->flashdata('msg'); ?></p>
          <?php endif; ?>

          <div class="body">
            <div class="demo-masked-input">
              <div class="row clearfix cust-tbl">
                <div class="col-md-12">
                  <div class="col-md-4">
                      <b>Bill No</b>
                      <div class="input-group">
                          <span class="input-group-addon">
                           <i class="material-icons">receipt</i>
                         </span>
                         <div class="form-line">
                          <input id="billNo" type="text" name="billNo" class="form-control date" placeholder="billNo" readonly value="<?php echo $CodeForDS.(1000+$nextId); ?>">
                        </div>
                      </div>
                  </div>

                  <div class="col-md-4">
                      <b>Salesman</b>
                      <div class="input-group">
                        <span class="input-group-addon">
                         <i class="material-icons">person</i>
                       </span>
                        <div class="form-line">
                            <input type="text" id="salesman" autocomplete="off" list="salesmanNameText" name="salesman" value="<?php echo $salesmanName; ?>" class="form-control" placeholder="Salesman Name">   
                            <datalist id="salesmanNameText">
                                <?php
                                    foreach($emp as $data){
                                        $name=$data['name'];
                                ?>   
                                <option id="<?php echo $data['id'];?>" value="<?php echo $name;?>"/>
                                <?php } ?>
                            </datalist>
                        </div>
                        <p id="salesmanErr" style="color:red"></p>
                      </div>
                </div> 
           
                <div class="col-md-4">
                    <b>Retailer</b>
                    <div class="input-group">
                      <span class="input-group-addon">
                         <i class="material-icons">person</i>
                      </span>
                      <div class="form-line">
                       <input type="text" id="retailer" autocomplete="off" value="<?php echo $retailerName; ?>" list="retailerNameText" name="retailer" class="form-control" placeholder="Retailer Name">   
                          <datalist id="retailerNameText">
                              <?php
                                  foreach($retailer as $data){
                                      $name=$data['retailerCode'].' : '.$data['name'].' : '.$data['area'];
                              ?>   
                              <option id="<?php echo $data['id'];?>" value="<?php echo $name;?>"/>
                              <?php }  ?>
                          </datalist>
                        </div>
                        <p id="retailerErr" style="color:red"></p>
                    </div>
                </div> 
            </div>
            <div class="col-md-12">
                  <div class="col-md-5">
                      <b>Products</b>
                      <div class="input-group">
                        <div class="form-line">
                        <input type="text" id="pname" autocomplete="off" list="productNameText" name="productName" class="form-control" placeholder="Product Name">   
                        <datalist id="productNameText">
                            <?php
                                foreach($product as $data){
                                    $name=$data['productCode'].' : '.$data['name'].' : Rs '.$data['mrp'];
                                    $id=$data['id'];
                            ?>   
                            <option id="<?php echo $data['id'];?>" value="<?php echo $name;?>"/>
                            <?php }  ?>
                        </datalist>
                        </div>
                        <p id="productErr" style="color:red"></p>
                    </div>
                  </div> 
          
                  <input id="mrp" type="hidden" name="mrp">
                  <input id="prodUnitOne" type="hidden" name="prodUnitOne">
                  <input id="prodUnitTwo" type="hidden" name="prodUnitTwo">
                  <input id="prdNameTxt" type="hidden" name="prdNameTxt">
                  <input id="type-unt" type="hidden" name="type-unt">
                  <input id="amt" type="hidden" name="amt">
               
                  <div class="col-md-1">
                      <b>Quantity</b>
                      <div class="input-group">
                        <div class="form-line">
                          <input id="qty" type="text" name="quantity" onkeypress="return isNumber(event)" class="form-control date" placeholder="Quantity">
                        </div>
                        <p id="qtyErr" style="color:red"></p>
                      </div>
                  </div>

                  <!-- <div class="col-md-2">
                      <b>Quantity Unit</b>
                      <div class="input-group">
                        <div class="form-line">
                          <input type="text" id="unt1" list="prdUnit1List" autocomplete="off" list="prdNames" name="units1" class="form-control" placeholder="quantity unit" required>   
                          <datalist id="prdUnit1List">
                            <option id="1" value="Case"/>
                            <option id="3" value="Pcs"/>
                          </datalist>
                        </div>
                        <p id="qtyUnitErr" style="color:red"></p>
                      </div>
                  </div> -->

                  <div id="twoDrp1" class="col-md-2">
                        <b>Quantity Unit</b>
                        <select name="units1" id="unt1" class="form-control">
                            <option value="">Select Unit</option>
                            <option value="Case">Case</option>
                            <option value="Pcs">Pcs</option>
                        </select>
                        <p id="qtyUnitErr" style="color:red"></p>
                  </div> 

                  <div id="twoDrp2" style="display:none" class="col-md-2">
                        <b>Quantity Unit</b>
                        <select name="units1" id="unt11" class="form-control">
                            <option value="">Select Unit</option>
                            <option value="Case">Case</option>
                            <option value="Box">Box/Packet</option>
                            <option value="Pcs">Pcs</option>
                        </select>
                        <p id="qtyUnitErr1" style="color:red"></p>
                  </div> 

                  <div class="col-md-1">
                      <b>Rate</b>
                      <div class="input-group">
                       <div class="form-line">
                        <input id="rate" type="text" autocomplete="off" onkeypress="return isNumber(event)" name="rate" class="form-control date" placeholder="Rate">
                      </div>
                      <p id="rateErr" style="color:red"></p>
                    </div>
                  </div>

                  <!-- <div class="col-md-2">
                      <b>Rate Unit</b>
                      <div class="input-group">
                         <div class="form-line">
                          <input type="text" id="unt2" list="prdUnit2List" autocomplete="off" list="units2" name="units1" class="form-control" placeholder="rate unit" required>   
                          <datalist id="prdUnit2List">
                            <option id="4" value="Case"/>
                            <option id="6" value="Pcs"/>
                          </datalist>
                        </div>
                        <p id="rateUnitErr" style="color:red"></p>
                      </div>
                  </div> -->

                 

                  <div id="threeDrp1" class="col-md-2">
                        <b>Rate Unit</b>
                        <select name="units1" id="unt2" class="form-control">
                            <option value="">Select Unit</option>
                            <option value="Case">Case</option>
                            <option value="Pcs">Pcs</option>
                        </select>
                        <p id="rateUnitErr" style="color:red"></p>
                  </div> 

                  <div id="threeDrp2" style="display:none" class="col-md-2">
                        <b>Rate Unit</b>
                        <select name="units1" id="unt22" class="form-control">
                            <option value="">Select Unit</option>
                            <option value="Case">Case</option>
                            <option value="Box">Box/Packet</option>
                            <option value="Pcs">Pcs</option>
                        </select>
                        <p id="rateUnitErr2" style="color:red"></p>
                  </div> 

                  <div class="col-md-2" style="display:none">
                        <b>Quantity Available</b>
                        <div class="input-group">
                         <div class="form-line">
                          <input readonly id="avlQty" type="text" name="availQty" class="form-control date" placeholder="Available Quantity"> 
                        </div>
                      </div>
                  </div>
                  <div class="col-md-1">
                  <button type="button" id="clearBtn" class="btn btn-xs btn-danger m-t-20 waves-effect">
                          <i class="material-icons">cancel</i> 
                  </button>
                </div>
              </div>

              <!-- <div class="col-md-12">
                  <div class="col-md-3">
                        <button class="add_cart btn btn-primary m-t-15 waves-effect">
                          <i class="material-icons">save</i> 
                          <span class="icon-name" id="addBtn">
                           Add
                         </span>
                       </button>
                       
                        <button type="button" onClick="clr();" class="btn btn-primary m-t-15 waves-effect">
                          <i class="material-icons">cancel</i> 
                          <span class="icon-name"> Cancel</span>
                        </button>
                  </div>
              </div>  -->

              <div class="col-md-12">
                <div id="rc" class="table-responsive">
                    <table id="tbl" class="table table-bordered cust-tbl" data-page-length='100'>
                        <thead>
                          <tr>
                            <th>Item</th>
                            <th class="text-right">MRP</th>
                            <th class="text-right">Qty</th>
                            <th class="text-right">Rate</th>
                            <th class="text-right">Amount</th>
                            <th>Remove</th>
                          </tr>
                        </thead>
                        <tbody id="detail_cart">

              <?php 
                  $no = 0;
                  $total=0;
                  $userId=$this->session->userdata[$projectSessionName]['id'];
                  $cartDetails=$this->DeliverySlipModel->getCartDetailsByUser('deliveryslip_add_to_cart',$userId);
                  foreach ($cartDetails as $items) {
                    $total=$total+$items['amount'];
                      $no++;
              ?>
                      
                      <tr>
                      <td><?php echo $items['productName']; ?></td>
                      <td class="text-right"><?php echo number_format($items['mrp']); ?></td>
                      <td class="text-right"><?php echo $items['quantity'].' '.$items['unitOne']; ?></td>
                      <td class="text-right"><?php echo $items['rate'].'/'.$items['unitTwo']; ?></td>
                      <td class="text-right"><?php echo number_format($items['amount']); ?></td>
                      <!-- <td><button data-id="<?php echo $items['id']; ?>" class="romove_cart btn btn-sm"><i class="fa fa-trash" style="color: red;"></i></button></td> -->
                      <td> 
                          <a href="<?php echo site_url('DeliverySlipController/deleteCart/'.$items['id']); ?>">
                          <button class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">delete</i></button>
                          </a>
                      </td>
                      </tr>
              <?php   
                  }
              ?>

                  <tr>
                        <td>Grand Total :</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right"><?php echo number_format($total); ?></td>
                        <td></td>
                  </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row clearfix">
                  <div class="col-sm-offset-5 col-xs-offset-5">
                        <button type="button" target="_blank" id="sndBtn" class="btn btn-xs btn-primary btnStyle m-t-15 waves-effect">
                          <i class="material-icons">print</i> 
                          <span class="icon-name">
                           Save And Print
                         </span>
                       </button>
                       
                       <button type="button" id="cnBtn" class="btn btn-sm btn-danger m-t-15 waves-effect">
                          <i class="material-icons">cancel</i> 
                          <span class="icon-name">
                           Cancel
                         </span>
                       </button>
                  </div>
                </div>
              </div>  

              <div class="col-md-12">
                  <div class="body outer">
                            <div class="table-responsive">
                                <button data-toggle="modal" data-target="#addToAllocationModal" type="button" class="btn btn-xs btn-primary btnStyle waves-effect">
                                    <i class="material-icons">print</i> 
                                    <span class="icon-name">
                                     Add to Open Allocation
                                   </span>
                                </button>
                                <button data-toggle="modal" data-target="#addToNewAllocationModal" type="button" class="btn btnStyle btn-xs btn-primary waves-effect">
                                    <i class="material-icons">print</i> 
                                    <span class="icon-name">
                                     Create New Allocation
                                   </span>
                                </button>
                                <br><br>
                                <table id="prodTbl" style="font-size:13px" class="table table-bordered cust-tbl" data-page-length='100' >
                                    <thead>
                                        <tr>
                                            <th>
                                              <input class="checkall"  type="checkbox" name="selValue" id="basic_checkbox"/>
                                              <label for="basic_checkbox"></label>
                                            </th>
                                            <th>Company </th>
                                            <th>Bill Number </th>
                                            <th>Date</th>
                                            <th>Retailer</th>
                                            <th class="text-right">Bill Amount</th>
                                            <th class="text-right">Pending Amount</th>
                                            <th>Salesman</th>
                                            <th>Status</th>
                                            <th class="noExport">Action</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                  
                                      <?php
                                        $no=0;
                                        if(!empty($pendingForBilling)){
                                        foreach ($pendingForBilling as $data) 
                                          {
                                            $rCode=$this->DeliverySlipModel->loadRetailer($data['retailerCode']);

                                          $no++;
                                    ?>
                                        <tr>
                                            <td>
                                                <input class="checkhour"  type="checkbox" name="selValue" value="<?php echo $data['id']; ?>" id="basic_checkbox_<?php echo $data['id']; ?>" />
                                                <label for="basic_checkbox_<?php echo $data['id']; ?>"></label>
                                            </td>
                                            <td><?php echo $data['compName']; ?></td>
                                            <td><?php echo $data['billNo']; ?></td>
                                            <td><?php echo date("d-M-Y", strtotime($data['date'])); ?></td>
                                            <td><?php echo $data['retailerName']; ?></td>
                                            <td class="text-right"><?php echo number_format($data['netAmount']); ?></td>
                                            <td class="text-right"><?php echo number_format($data['pendingAmt']); ?></td>
                                            <td><?php echo $data['salesman']; ?></td>
                                            <td><?php echo "<p style='color:red'>Unaccounted</p>"; ?></td>
                                            <td>
                    <a id="prDetailsForAll" href="javascript:void()" data-id="<?php echo $data['id']; ?>" data-salesman="<?php echo $data['salesman']; ?>" data-billDate="<?php echo date("d-M-Y", strtotime($data['date'])); ?>" data-credAdj="<?php echo $data['creditAdjustment']; ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>" data-gst="<?php if(!empty($rCode)){ echo $rCode[0]['gstIn']; } ?>" data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-route="<?php echo $data['routeName']; ?>" data-toggle="modal" data-target="#processModalForAll" ><button class="btn btn-xs process-btn waves-effect waves-float" data-toggle="tooltip" data-placement="bottom" title="Process"><i class="material-icons">touch_app</i></button></a>

                    <!-- <a id="prDetails" href="javascript:void()" data-id="<?php echo $data['id']; ?>" data-salesman="<?php echo $data['salesman']; ?>" data-billDate="<?php echo date("d-M-Y", strtotime($data['date'])); ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>"  data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-toggle="modal" data-target="#processModal" ><button class="btn btn-xs btn-primary waves-effect waves-float" data-toggle="tooltip" data-placement="bottom" title="Process"><i class="material-icons">touch_app</i></button></a> -->
                                    
                    <a href="<?php echo site_url('AdHocController/billHistoryInfo/'.$data['id']); ?>" class="btn btn-xs history-btn" data-toggle="tooltip" data-placement="bottom" title="View History"><i class="material-icons">info</i></a>
                                             
                   <a href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs viewBill-btn" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                                            
                    <a target="_blank" href="<?php echo site_url('DeliverySlipController/downloadPDF/'.$data['id']); ?>" class="btn btn-xs btnStyle" data-toggle="tooltip" data-placement="bottom" title="Download PDF"><i class="material-icons">download</i></a>
 
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
      </div>
    </div>
  </div>
</div>
</section>


<div class="container">
  <div class="modal fade" id="prodModal" role="dialog" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <center><h4 class="modal-title">Add Product</h4></center>
          </div>
          <div class="modal-body">
            <div class="row clearfix cust-tbl">
              <div class="body">
                  <div class="demo-masked-input">
                      <div class="col-md-12">
                        <div class="col-md-3">
                            <b>Company</b>
                            <div class="input-group">
                                <span class="input-group-addon">
                                     <i class="material-icons">check_circle</i>
                                </span>
                                <div class="form-line">
                                  <select id="productCompany" name="productCompany" class="form-control">
                                    <option value="">Select Company</option>
                                    <?php if(!empty($company)){
                                        foreach($company as $data){
                                    ?>
                                      <option value="<?php echo $data['name']; ?>"><?php echo $data['name']; ?></option>
                                    <?php } 
                                      }
                                    ?>
                                  </select>
                                </div>
                            </div>
                        </div> 

                        <div class="col-md-3">
                            <b>Product Code</b>
                            <div class="input-group">
                                <span class="input-group-addon">
                                     <i class="material-icons">check_circle</i>
                                </span>
                                <div class="form-line">
                                    <input autocomplete="off" id="productCode" type="text" name="productCode" class="form-control date" placeholder="Enter product code" required>
                                </div>
                            </div>
                        </div> 

                        <div class="col-md-3">
                              <b>Product Name</b>
                              <div class="input-group">
                                  <span class="input-group-addon">
                                       <i class="material-icons">check_circle</i>
                                  </span>
                                  <div class="form-line">
                                      <input autocomplete="off" id="productName" type="text" list="prodList" name="productName" class="form-control date" placeholder="Enter product name" required>
                                      <datalist id="prodList">
                                            <?php
                                                foreach($product as $data){
                                                    $name=$data['name'];
                                            ?>   
                                            <option value="<?php echo $name;?>"/>
                                            <?php    
                                                }
                                            ?>
                                        </datalist>
                                  </div>
                              </div>
                          </div>

                          <div class="col-md-3">
                              <b>Product MRP</b>
                              <div class="input-group">
                                  <span class="input-group-addon">
                                       <i class="material-icons">check_circle</i>
                                  </span>
                                  <div class="form-line">
                                      <input onkeypress="return isNumber(event)" autocomplete="off" id="productMrp" type="text" name="productMrp" class="form-control date" placeholder="MRP (per Pcs)" required>
                                  </div>
                              </div>
                          </div>  
                        </div>
                        <div class="col-md-12">
                          <div class="col-md-3">
                              <div class="input-group">
                                  <input name="unitFilter" type="radio" id="radio_1" value="u1" class="with-gap radio-col-red" checked />
                                  <label for="radio_1">2 Units</label>
                                  <br>
                                  <input name="unitFilter" type="radio" id="radio_2" value="u2" class="with-gap radio-col-red" />
                                  <label for="radio_2">3 Units</label>
                              </div>
                          </div> 

                          <div class="col-md-3" id="un1">
                              <b>Unit 1 (Pcs)</b>
                              <div class="input-group">
                                  <span class="input-group-addon">
                                       <i class="material-icons">check_circle</i>
                                  </span>
                                  <div class="form-line">
                                      <input onkeypress="return isNumber(event)" autocomplete="off" id="productUnitOne" type="text" name="productUnitOne" class="form-control date" placeholder="No. of Pcs in a Box" required>
                                  </div>
                              </div>
                          </div> 

                          <div class="col-md-3" id="un2" style="display:none;">
                            <b>Unit 2 (Box)</b>
                            <div class="input-group">
                                <span class="input-group-addon">
                                     <i class="material-icons">check_circle</i>
                                </span>
                                <div class="form-line">
                                  <input onkeypress="return isNumber(event)" autocomplete="off" id="productUnitTwo" type="text" name="productUnitTwo" class="form-control date" placeholder="No. of box in a case" required>
                                  <!-- <select id="productUnitTwo" name="productUnitTwo" class="form-control">
                                      <option value="">Select Unit</option>
                                      <option value="box">Box</option>
                                      <option value="poly">Poly</option>
                                      <option value="packet">Packet</option>
                                  </select> -->
                                </div>
                            </div>
                        </div> 

                         <div class="col-md-3" id="un3">
                              <b id="txtunit2">Unit 2 (Case)</b>
                              <div class="input-group">
                                  <span class="input-group-addon">
                                       <i class="material-icons">check_circle</i>
                                  </span>
                                  <div class="form-line">
                                      <input onkeypress="return isNumber(event)" readonly autocomplete="off" value="1" id="productUnitThree" type="text" name="productUnitThree" class="form-control" placeholder="No. of cases" required>
                                  </div>
                              </div>
                          </div>
                      </div>
                      
                      <div id="recStatus"></div>
                           <div class="col-md-12">
                              <div class="row clearfix">
                                  <div class="col-md-4">
                                      <button id="insProd" class="btn btn-primary btnStyle m-t-15 waves-effect">
                                          <i class="material-icons">save</i> 
                                          <span class="icon-name">Save</span>
                                      </button>
                                     
                                      <button data-dismiss="modal" type="button" class="btn btn-danger m-t-15 waves-effect">
                                          <i class="material-icons">cancel</i> 
                                          <span class="icon-name"> Cancel</span>
                                      </button>
                                  </div>
                              </div>
                          </div>                             
                      </div>
                  </div>
              </div>
          </div>
      </div>
    </div>
  </div>
</div>

<div class="container">
  <div class="modal fade" id="retailerModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <center><h4 class="modal-title">Add Retailer</h4></center>
          </div>
          <div class="modal-body">
         <!--<form method="post" role="form" action="<?php echo site_url('RetailerController/insert'); ?>"> -->
                        <div class="body">
                            <div class="demo-masked-input">
                                <div class="row clearfix cust-tbl">
                                  <div class="col-md-4">
                                        <b>Retailer Code</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" id='retailerCode' autocomplete="off" value="<?php  echo ($retailerCode); ?>" name="retailerCode" list="ret" class="form-control date" placeholder="Enter retailer code" required>
                                            </div>
                                        </div>
                                    </div> 
                                  <div class="col-md-4">
                                        <b>Retailer Name</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" id='rtName' autocomplete="off" name="rtName" list="ret" class="form-control date" placeholder="Enter retailer name" required>
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="col-md-4">
                                        <b>Area</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input autocomplete="off" id="area" type="text" name="area" class="form-control date" placeholder="Enter area" required>
                                            </div>
                                        </div>
                                    </div> 

                                   <!--  <div class="col-md-4">
                                        <b>Route</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" id="routeNames" autocomplete="off" name="route" list="routes" class="form-control date" placeholder="Enter route" required>
                                                     <datalist id="routes">
                                                      <?php
                                                          foreach($route as $data){
                                                              $name=$data['name'];
                                                      ?>   
                                                      <option value="<?php echo $name;?>"/>
                                                      <?php    
                                                          }
                                                      ?>
                                                  </datalist>
                                            </div>
                                        </div>
                                    </div>  -->

                                    <!--  <div class="col-md-4">
                                      <b>Salesman</b>
                                      <div class="input-group">
                                        <span class="input-group-addon">
                                         <i class="material-icons">check_circle</i>
                                       </span>
                                       <div class="form-line">
                            <input type="text" id="salesmanNames" autocomplete="off" list="emp" name="salesman" class="form-control" placeholder="salesman Name" required>   
                                        <datalist id="emp">
                                            <?php
                                                foreach($emp as $data){
                                                    $name=$data['name'];
                                            ?>   
                                            <option value="<?php echo $name;?>"/>
                                            <?php    
                                                }
                                            ?>
                                        </datalist>
                                      </div>
                                    </div>
                                  </div>  -->
                                  <div id="recStatus1"></div>
                                     <div class="col-md-12">
                                        <div class="row clearfix">
                                            <div class="col-md-4">
                                                <button id="insRet" class="btn btnStyle btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name">Save</span>
                                                </button>
                                               
                                                    <button data-dismiss="modal" type="button" class="btn btn-danger m-t-15 waves-effect">
                                                        <i class="material-icons">cancel</i> 
                                                        <span class="icon-name"> Cancel</span>
                                                    </button>
                                               
                                            </div>

                                        </div>
                                    </div>                            
                                </div>
                            </div>
                        </div>
                      <!--</form>-->
          </div>
      </div>
    </div>
  </div>
</div>

<div class="container">
  <div class="modal fade" id="addToAllocationModal" role="dialog" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header"><center><h4 class="modal-title">Add Bill to Open Allocation</h4></center></div>
          <div class="modal-body">
              <div class="body">
                  <div class="demo-masked-input">
                      <div class="row clearfix cust-tbl">
                          <div class="col-md-12">
                            <div class="col-md-6">
                                  <b>Open Allocations</b>
                                  <select name="allocations" id="allocations" class="form-control">
                                      <option value="">Select Allocation</option>
                                      <?php 
                                          if(!empty($openAllocations)){
                                              foreach($openAllocations as $item){
                                      ?>
                                            <option value="<?php echo $item['id']; ?>"><?php echo $item['allocationCode'].' : '.$item['routeName']; ?></option>
                                      <?php            
                                              }
                                          }
                                      ?>
                                  </select>
                            </div> 
                          </div>
                          <div class="col-md-12">
                              <div class="row clearfix">
                                  <div class="col-md-4">
                                      <button id="ins_add_allocation" class="btn btnStyle btn-primary m-t-15 waves-effect">
                                          <i class="material-icons">save</i> 
                                          <span class="icon-name">Save</span>
                                      </button>
                                      <button data-dismiss="modal" type="button" class="btn btn-danger m-t-15 waves-effect">
                                          <i class="material-icons">cancel</i> 
                                          <span class="icon-name"> Cancel</span>
                                      </button>
                                  </div>
                              </div>
                          </div>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
  </div>
</div>


<div class="container">
  <div class="modal fade" id="addToNewAllocationModal" role="dialog" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header"><center><h4 class="modal-title">Create New Allocation</h4></center></div>
          <div class="modal-body">
              <div class="body">
                  <div class="demo-masked-input">
                      <div class="row clearfix cust-tbl">
                          <div class="col-md-12">

                            <div class="col-md-6">
                                <b>Employee Name</b>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                     <i class="material-icons">receipt</i>
                                   </span>
                                   <div class="form-line">
                                    <input id="empName" type="text" list="empNameList" name="empName" class="form-control date" placeholder="employee name">
                                  </div>
                                </div>

                                <datalist id="empNameList">
                                    <?php
                                        foreach($emp as $data){
                                            $name=$data['name'];
                                    ?>   
                                    <option id="<?php echo $data['id'] ?>" value="<?php echo $data['name'] ?>" />
                                    <?php    
                                        }
                                    ?>
                                </datalist>
                            </div>

                            <div class="col-md-6">
                                <b>Route Name</b>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                     <i class="material-icons">receipt</i>
                                   </span>
                                   <div class="form-line">
                                    <input id="routeName" type="text" list="routeNameList" name="routeName" class="form-control date" placeholder="route name">
                                  </div>
                                </div>

                                <datalist id="routeNameList">
                                    <?php
                                        foreach($route as $data){
                                            $name=$data['name'];
                                    ?>   
                                    <option id="<?php echo $data['id'] ?>" value="<?php echo $data['name'] ?>" />
                                    <?php    
                                        }
                                    ?>
                                </datalist>
                            </div>
                          </div>

                          <div class="col-md-12">
                              <div class="row clearfix">
                                  <div class="col-md-4">
                                      <button id="ins_new_allocation" class="btn btnStyle btn-primary m-t-15 waves-effect">
                                          <i class="material-icons">save</i> 
                                          <span class="icon-name">Save</span>
                                      </button>
                                      <button data-dismiss="modal" type="button" class="btn btn-danger m-t-15 waves-effect">
                                          <i class="material-icons">cancel</i> 
                                          <span class="icon-name"> Cancel</span>
                                      </button>
                                  </div>
                              </div>
                          </div>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
  </div>
</div>


<?php $this->load->view('/layouts/footerDataTable'); ?>

<?php $this->load->view('/layouts/processButtonView'); ?>

<script type="text/javascript">
  function clr(){
    $("#pname").val("");
    $("#mrp").val("");
    $("#qty").val("");
    $("#rate").val("");
    $("#rpc").val("");
    $("#amt").val("");
    $("#avlQty").val("");
    $("#unt1").val("1").change();
    $("#unt2").val("1").change();
  }
</script>

<!-- change unit -->
<script type="text/javascript">
    $("input[type='radio'],[name='unitFilter']").change(function() {
        if (this.value == 'u1') {
              $('#un2').hide();
              $('#txtunit2').text('Unit 2 (Case)');
        }
        else if (this.value == 'u2') {
              $('#un2').show();
              $('#txtunit2').text('Unit 3 (Case)');
        }
    });
</script>

<!-- Insert Product -->
<script type="text/javascript">
    var filter="u1";
    $('input[type=radio][name=unitFilter]').change(function() {
        if (this.value == 'u1') {
              filter="u1";
        }
        else if (this.value == 'u2') {
              filter="u2";
        }
    });

    $(document).on("click","#insProd",function() {
        var productCompany = $('#productCompany').val();
        var productName = $('#productName').val();
        var productCode = $('#productCode').val();
        var productMrp = $('#productMrp').val();
        var productUnitOne = $('#productUnitOne').val();
        var productUnitThree = $('#productUnitThree').val();

        var productUnitTwo = "";

        if(filter==="u2"){
          productUnitTwo = $('#productUnitTwo').val();
        }else{
          productUnitTwo = '1';
        }

        if(productCompany==="" || productName==="" || productCode==="" || productMrp==="" || productUnitOne==="" || productUnitTwo==="" || productUnitThree===""){
            alert("Please enter all details");
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('ProductController/insert');?>",
                data:{"filter":filter,"productName" : productName,"productCompany":productCompany,"productCode":productCode,"productMrp":productMrp,"productUnitOne":productUnitOne,"productUnitTwo":productUnitTwo,"productUnitThree":productUnitThree},
                success: function (data) {
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/DeliverySlipController";
                }  
            });
        }
    });
</script>
 
<!-- Insert Retailer -->
<script type="text/javascript">
    $(document).on("click","#insRet",function() {
        var retailerName = $('#rtName').val();
        var area=$('#area').val();
        var retailerCode=$('#retailerCode').val();

        if(retailerName ==="" || area==="" || retailerCode===""){
            alert("Please enter all details");
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('RetailerController/insert');?>",
                data:{"retailerName" : retailerName,"area":area,"retailerCode":retailerCode},
                success: function (data) {
                  alert(data);
                    window.location.href="<?php echo base_url();?>index.php/DeliverySlipController";
                }  
            });
        }
    });
</script>

<!-- Clear all data  -->
<script type="text/javascript">
 $(document).on("click","#cnBtn",function() {
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('DeliverySlipController/clearAllData');?>",
            data:{},
            success: function (data) {
                window.location.href="<?php echo base_url();?>index.php/DeliverySlipController";
            }  
        });
    });
</script>

<script type="text/javascript">
  $(document).on("blur","#salesman",function() {
      var salesmanName=$('#salesman').val();
      var salesmanId = $('#salesmanNameText').find('option[value="'+salesmanName+'"]').attr('id');

      if (typeof salesmanId === "undefined") {
        $('#salesmanErr').text('salesman not found');
      }else{
        $('#salesmanErr').text('');
      }
  });
</script>

<script type="text/javascript">
  $(document).on("blur","#retailer",function() {
      var retailerName=$('#retailer').val();
      var retailerId = $('#retailerNameText').find('option[value="'+retailerName+'"]').attr('id');

      if (typeof retailerId === "undefined") {
        $('#retailerErr').text('retailer not found');
      } else {
        $('#retailerErr').text('');
      }
  });
</script>

<script type="text/javascript">
  $(document).on("blur","#pname",function() {
        var productName=$('#pname').val();
        var productId = $('#productNameText').find('option[value="'+productName+'"]').attr('id');
        var retailerName=$('#retailer').val();
        var retailerId = $('#retailerNameText').find('option[value="'+retailerName+'"]').attr('id');

        if (typeof productId === "undefined" && productName!=="") {
          $('#productErr').text('product not found');
          $('#pname').val('');
          $('#pname').focus();
          die();
        } else {
          $('#productErr').text('');
        }

        if (typeof retailerId === "undefined") {
          $('#retailerErr').text('retailer not found');
        } else {
          $('#retailerErr').text('');
        }

        $.ajax({
            type: "POST",
            url:"<?php echo site_url('DeliverySlipController/checkProduct');?>",
            data:{productName:productName,productId:productId},
            success: function (data) {
              // alert(data);die();
              if((data.trim()==="no")){
                $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('DeliverySlipController/getProductDetailById');?>",
                    data:{productId:productId,retailerId:retailerId},
                    success: function (data) {
                        var returnedData = JSON.parse(data);
                        // alert(returnedData);die();

                        $('#avlQty').val(returnedData.totalQty);
                        $('#prdNameTxt').val(returnedData.prodName);
                        $('#mrp').val(returnedData.productMrp);
                        $('#rate').val(returnedData.retailerRate);
                        $('#prodUnitOne').val(returnedData.unitOne);
                        $('#prodUnitTwo').val(returnedData.unitTwo);
                        $('#type-unt').val(returnedData.unitFilter);

                        var unitFilter=(returnedData.unitFilter);
                        if(unitFilter==='u1'){
                            $('#twoDrp1').show();
                            $('#threeDrp1').show();

                            $('#twoDrp2').hide();
                            $('#threeDrp2').hide();
                        }else{
                            $('#twoDrp1').hide();
                            $('#threeDrp1').hide();

                            $('#twoDrp2').show();
                            $('#threeDrp2').show();
                        }
                    }  
                });
              }else{
                if(productName !==""){
                    // alert("Product already present");
                    $('#productErr').text('Product already present');
                    $('#pname').val('');
                    $('#pname').focus();
                    die();
                }
              }
            }  
        });
  });
</script>

<script type="text/javascript">
  $(document).on("change","#unt1",function() {


      var quantityUnit=$('#unt1').val();
      if (quantityUnit == "") {
        $('#qtyUnitErr').text('quantity unit not found');
      } else {
        $('#qtyUnitErr').text('');
      }
  });
</script>

<script type="text/javascript">
  $(document).on("change","#unt11",function() {


      var quantityUnit=$('#unt11').val();
      if (quantityUnit == "") {
        $('#qtyUnitErr1').text('quantity unit not found');
      } else {
        $('#qtyUnitErr1').text('');
      }
  });
</script>


<script type="text/javascript">
  $(document).on("change","#unt2",function() {
      var rateUnit=$('#unt2').val();
      if (rateUnit == "") {
        $('#rateUnitErr').text('quantity unit not found');
      } else {
        $('#rateUnitErr').text('');
      }
  });
</script>

<script type="text/javascript">
  $(document).on("change","#unt22",function() {
      var rateUnit=$('#unt22').val();
      if (rateUnit == "") {
        $('#rateUnitErr2').text('quantity unit not found');
      } else {
        $('#rateUnitErr2').text('');
      }
  });
</script>


<!-- only numbers -->
<script type="text/javascript">
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if ((charCode < 46 || charCode > 57) ) {
            return false;
        }
        return true;
    }
</script>

<script type="text/javascript">
  $(document).on("click","#clearBtn",function() {
      $('#prdNameTxt').val();
      $('#pname').val("");  
      $('#mrp').val("");
      $('#qty').val("");
      $('#rate').val("");

      $("#prodUnitOne").val("");
      $("#prodUnitTwo").val("");
      $("#avlQty").val("");

      
  });
</script>

<script type="text/javascript">
    $(document).on("change","#unt2",function() {
      
      var quantityUnit=$('#unt1').val();
      var rateUnit=$('#unt2').val();
      // alert("c "+quantityUnit+' r '+rateUnit);die();
      if(quantityUnit==""){
        $('#rateUnitErr').text('rate unit not found');
        $("#pname").focus();
        die();
      }

      if(rateUnit==""){
        $('#rateUnitErr').text('rate unit not found');
        $("#pname").focus();
        die();
      }

      var billNo=$('#billNo').val();
      var salesmanName=$('#salesman').val();
      var salesmanId = $('#salesmanNameText').find('option[value="'+salesmanName+'"]').attr('id');
      var productName=$('#pname').val();
      var productId = $('#productNameText').find('option[value="'+productName+'"]').attr('id');
      var retailerName=$('#retailer').val();
      var retailerId = $('#retailerNameText').find('option[value="'+retailerName+'"]').attr('id');

      var latestProdName=$('#prdNameTxt').val();

      var unit=$('#unt1').val();
      var unit2=$('#unt2').val();

      var mrp = $('#mrp').val();
      var qty = $('#qty').val();
      var rate = $('#rate').val();
      var prodUnitOne = $('#prodUnitOne').val();
      var bxQty = $('#prodUnitTwo').val();

      if(qty==0 || qty===""){
        $('#qtyErr').text('Please enter quantity');
        $("#pname").focus();
        die();
      }else{
          $('#qtyErr').text('');
      }

      if(rate==0 || rate===""){
        $('#rateErr').text('Please enter rate');
        $("#pname").focus();
        die();
      }else{
        $('#rateErr').text('');
      }

      if((unit==='Pcs') && (unit2==='Case')){
          var count=(rate/(bxQty*prodUnitOne));
          var res=count*qty;
          $('#amt').val(res);
      }else if((unit=='Pcs') && (unit2 =='Box')){
          var count=(rate/(prodUnitOne));
          $('#amt').val(count*qty);
      } else if((unit=='Pcs') && (unit2=='Pcs')){
         $('#amt').val(rate*qty);
      }

      if((unit=='Case') && (unit2=='Pcs')){
          var count=(qty*prodUnitOne*bxQty);
          $('#amt').val(count*rate);
      }else if((unit=='Case') && (unit2=='Box')){
          var count=qty*bxQty;
          $('#amt').val(count*rate);
      }else if((unit=='Case') && (unit2=='Case')){
          $('#amt').val(rate*qty);
      }

      if((unit=='Box') && (unit2=='Pcs')){
          var count=qty*prodUnitOne;
          $('#amt').val(count*rate);
      }else if((unit=='Box') && (unit2=='Case')){
          var count=(rate/(bxQty));
          var res=count*qty;
          $('#amt').val(res);
      }else if((unit=='Box') && (unit2=='Box')){
          $('#amt').val(rate*qty);
      }

      var amt = $('#amt').val();

      if(retailerName!='' && salesmanName !=''){
          if(productName!='' || qty!='' || rate!=''){
              $.ajax({
                url : "<?php echo site_url('DeliverySlipController/addToCart');?>",
                // url : "<?php echo site_url('DeliverySlipController/add_to_cart');?>",
                method : "POST",
                data : {billNo:billNo,salesmanId:salesmanId,productId:productId,retailerId:retailerId,pName:latestProdName,bxQty:bxQty,mrp:mrp,qty:qty,unt1:unit,rate:rate,unt2:unit2,amt:amt,salesman:salesmanName,retailer:retailerName},
                success: function(data){
                  $('#rateErr').text('');
                  $('#qtyErr').text('');
                  // alert(data);
                  // $('#detail_cart').html(data);
                  location.reload();
                }
              });
          }else{
              alert('Please fill all the fields...!');
          }
      }else{
          alert('Please select Retailer/Salesman');
      }

      
      // $('#detail_cart').load("<?php echo site_url('DeliverySlipController/load_cart');?>");      
      // $('#detail_cart').load("<?php echo site_url('DeliverySlipController/loadCart');?>");
            
      $(document).on('click','.romove_cart',function(){
          // var row_id=$(this).attr("id"); 
          var row_id =$(this).attr("data-id");
          alert(row_id);die();
        
          $.ajax({
            url : "<?php echo site_url('DeliverySlipController/deleteCart');?>",
            // url : "<?php echo site_url('DeliverySlipController/delete_cart');?>",
            method : "POST",
            data : {cart_id : row_id},
            success :function(data){
              // $('#detail_cart').html(data);
            }
          });
      });

      $('#prdNameTxt').val();
      $('#pname').val("");  
      $('#mrp').val("");
      $('#qty').val("");
      $('#rate').val("");

      $("#prodUnitOne").val("");
      $("#prodUnitTwo").val("");
      $("#avlQty").val("");
      $("#pname").focus();

      $("#unt1").val("").change();
      $("#unt2").val("").change();
      $("#unt11").val("").change();
      $("#unt22").val("").change();

      
      
  });
</script>

<script type="text/javascript">
    $(document).on("change","#unt22",function() {
      
      var quantityUnit=$('#unt11').val();
      var rateUnit=$('#unt22').val();
      // alert("c "+quantityUnit+' r '+rateUnit);die();
      if(quantityUnit==""){
        $('#rateUnitErr').text('rate unit not found');
        $("#pname").focus();
        die();
      }

      if(rateUnit==""){
        $('#rateUnitErr').text('rate unit not found');
        $("#pname").focus();
        die();
      }

      var billNo=$('#billNo').val();
      var salesmanName=$('#salesman').val();
      var salesmanId = $('#salesmanNameText').find('option[value="'+salesmanName+'"]').attr('id');
      var productName=$('#pname').val();
      var productId = $('#productNameText').find('option[value="'+productName+'"]').attr('id');
      var retailerName=$('#retailer').val();
      var retailerId = $('#retailerNameText').find('option[value="'+retailerName+'"]').attr('id');

      var latestProdName=$('#prdNameTxt').val();
      
      var unit=$('#unt11').val();
      var unit2=$('#unt22').val();

      var mrp = $('#mrp').val();
      var qty = $('#qty').val();
      var rate = $('#rate').val();
      var prodUnitOne = $('#prodUnitOne').val();
      var bxQty = $('#prodUnitTwo').val();

      if(qty==0 || qty===""){
        $('#qtyErr').text('Please enter quantity');
        $("#pname").focus();
        die();
      }else{
          $('#qtyErr').text('');
      }

      if(rate==0 || rate===""){
        $('#rateErr').text('Please enter rate');
        $("#pname").focus();
        die();
      }else{
        $('#rateErr').text('');
      }

      if((unit==='Pcs') && (unit2==='Case')){
          var count=(rate/(bxQty*prodUnitOne));
          var res=count*qty;
          $('#amt').val(res);
      }else if((unit=='Pcs') && (unit2 =='Box')){
          var count=(rate/(prodUnitOne));
          $('#amt').val(count*qty);
      } else if((unit=='Pcs') && (unit2=='Pcs')){
         $('#amt').val(rate*qty);
      }

      if((unit=='Case') && (unit2=='Pcs')){
          var count=(qty*prodUnitOne*bxQty);
          $('#amt').val(count*rate);
      }else if((unit=='Case') && (unit2=='Box')){
          var count=qty*bxQty;
          $('#amt').val(count*rate);
      }else if((unit=='Case') && (unit2=='Case')){
          $('#amt').val(rate*qty);
      }

      if((unit=='Box') && (unit2=='Pcs')){
          var count=qty*prodUnitOne;
          $('#amt').val(count*rate);
      }else if((unit=='Box') && (unit2=='Case')){
          var count=(rate/(bxQty));
          var res=count*qty;
          $('#amt').val(res);
      }else if((unit=='Box') && (unit2=='Box')){
          $('#amt').val(rate*qty);
      }

      var amt = $('#amt').val();

      if(retailerName!='' && salesmanName !=''){
          if(productName!='' || qty!='' || rate!=''){
              $.ajax({
                url : "<?php echo site_url('DeliverySlipController/addToCart');?>",
                // url : "<?php echo site_url('DeliverySlipController/add_to_cart');?>",
                method : "POST",
                data : {billNo:billNo,salesmanId:salesmanId,productId:productId,retailerId:retailerId,pName:latestProdName,bxQty:bxQty,mrp:mrp,qty:qty,unt1:unit,rate:rate,unt2:unit2,amt:amt,salesman:salesmanName,retailer:retailerName},
                success: function(data){
                  $('#rateErr').text('');
                  $('#qtyErr').text('');
                  // alert(data);
                  // $('#detail_cart').html(data);
                  location.reload();
                }
              });
          }else{
              alert('Please fill all the fields...!');
          }
      }else{
          alert('Please select Retailer/Salesman');
      }

      
      // $('#detail_cart').load("<?php echo site_url('DeliverySlipController/load_cart');?>");      
      // $('#detail_cart').load("<?php echo site_url('DeliverySlipController/loadCart');?>");
            
      $(document).on('click','.romove_cart',function(){
          // var row_id=$(this).attr("id"); 
          var row_id =$(this).attr("data-id");
          alert(row_id);die();
        
          $.ajax({
            url : "<?php echo site_url('DeliverySlipController/deleteCart');?>",
            // url : "<?php echo site_url('DeliverySlipController/delete_cart');?>",
            method : "POST",
            data : {cart_id : row_id},
            success :function(data){
              // $('#detail_cart').html(data);
            }
          });
      });

      $('#prdNameTxt').val();
      $('#pname').val("");  
      $('#mrp').val("");
      $('#qty').val("");
      $('#rate').val("");

      $("#prodUnitOne").val("");
      $("#prodUnitTwo").val("");
      $("#avlQty").val("");
      $("#pname").focus();

      $("#unt1").val("").change();
      $("#unt2").val("").change();
      $("#unt11").val("").change();
      $("#unt22").val("").change();

        

      

  });
</script>

<script type="text/javascript">
    $(document).on("click","#sndBtn",function() {
      $("#sndBtn").attr("disabled", true);
        var billNo=$('#billNo').val();
        var salesmanName=$('#salesman').val();
        var salesmanId = $('#salesmanNameText').find('option[value="'+salesmanName+'"]').attr('id');

        var retailerName=$('#retailer').val();
        var retailerId = $('#retailerNameText').find('option[value="'+retailerName+'"]').attr('id');

        $.ajax({
          url : "<?php echo site_url('DeliverySlipController/insertDeliverySlipData');?>",
          method : "POST",
          data : {billNo:billNo,salesmanId:salesmanId,retailerId:retailerId},
          success: function(data){
            // alert(data);die();
            var res=data.trim();
            var path="<?php echo base_url();?>index.php/DeliverySlipController/downloadPDF/"+res;
           
            window.open(path, "_blank");
            
            window.location.href="<?php echo base_url();?>index.php/DeliverySlipController";
          }
        });
    });
</script>

<script type="text/javascript">
    var clicked = false;
    $(".checkall").on("click", function() {
      $(".checkhour").prop("checked", !clicked);
      clicked = !clicked;
      this.innerHTML = clicked ? 'Deselect' : 'Select';
    });
</script>

<script type="text/javascript">
    $("#ins_add_allocation").on("click",function(){
      $("#ins_add_allocation").attr("disabled", true);
        var billId = [];
        $.each($("input[name='selValue']:checked"), function(){
                billId.push($(this).val());
        });

        var allocationId=$("#allocations").val();

        if((billId.length>0) && (allocationId !="")){
          
            $.ajax({
            url : "<?php echo site_url('BillTransactionController/addDeliverySlipBillToAllocation');?>",
            method : "POST",
            data : {allocationId:allocationId,billId:billId},
            success: function(data){
              alert(data);
              window.location.href="<?php echo base_url();?>index.php/DeliverySlipController";
            }
          });
        }else{
            alert('Please select Allocation/Bill.');
            $("#ins_add_allocation").attr("disabled", false);
        }
    });
</script>

<script type="text/javascript">
    $("#ins_new_allocation").on("click",function(){
      $("#ins_new_allocation").attr("disabled", true);
        var billId = [];
        $.each($("input[name='selValue']:checked"), function(){
                billId.push($(this).val());
        });
        // alert(billId);die();
        var empName=$('#empName').val();
        var empId = $('#empNameList').find('option[value="'+empName+'"]').attr('id');
        if (typeof empId === "undefined") {
            alert('Please enter correct employee');die();
        }

        var routeName=$('#routeName').val();
        var routeId = $('#routeNameList').find('option[value="'+routeName+'"]').attr('id');
        if (typeof routeId === "undefined") {
            alert('Please enter correct company');
            $("#ins_new_allocation").attr("disabled", false);
            die();
        }

        if((billId.length>0)){
            $.ajax({
            url : "<?php echo site_url('BillTransactionController/createNewAllocation');?>",
            method : "POST",
            data : {empId:empId,routeId:routeId,billId:billId},
            success: function(data){
              alert(data);
              window.location.href="<?php echo base_url();?>index.php/DeliverySlipController";
            }
          });
        }else{
            alert('Please select Allocation/Bill.');
            $("#ins_new_allocation").attr("disabled", false);
        }
    });
</script>

<script type="text/javascript">
    $(document).on('click'.'#clearBtn',function()){
      $('#prdNameTxt').val();
      $('#pname').val("");  
      $('#mrp').val("");
      $('#qty').val("");
      $('#rate').val("");

      $('#unt1').prop('selectedIndex',0);
      $('#unt2').prop('selectedIndex',0);
      $('#unt11').prop('selectedIndex',0);
      $('#unt22').prop('selectedIndex',0);

      $("#prodUnitOne").val("");
      $("#prodUnitTwo").val("");
      $("#avlQty").val("");
      $("#pname").focus();
    }
});

</script>


<script>
 $(document).ready(function(){

    $('input').on('paste', function() {
      $(this).val($(this).val().replace(/[^a-z0-9]/gi, ''));
    });

    $('.modalLinkFixDebit').click(function(){
        var id=$(this).attr('data-id');
        $.ajax({
            url : "<?php echo site_url('SrController/fixDebit');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
              $('#fixDebId').html(data);
            }
        });
    });
});
</script>