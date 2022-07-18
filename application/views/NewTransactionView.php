<?php $this->load->view('/layouts/commanHeader'); ?>


 <style type="text/css">
    @media screen and (min-width: 900px) {
        .modal-dialog {
          width: 900px; /* New width for default modal */
        }
        .modal-sm {
          width: 350px; /* New width for small modal */
        }
    }

    @media screen and (min-width: 900px) {
        .modal-lg {
          width: 900px; /* New width for large modal */
        }
    }

</style>

<style>

  .btn:hover, .btn:focus {
    font-weight: bolder;
    outline: 10px solid blanchedalmond;
    border-style: hidden;
    box-shadow: 0 0 2px 2px red;
  color: black;
  }
</style>

<!-- <section class="content"> -->
  <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
  <section class="col-md-12 box" style="height: auto;">
    <div class="container-fluid">
      <!-- <div class="block-header">
        <h2> New Delivery Slip</h2>
      </div> -->
      <!-- Masked Input -->
      <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div id="frm_AddItem" class="card">
            <div class="header">
              <h2>
               New Delivery Slip Master
            </h2>
            <h2>
                                <p align="right">
                                <!-- <a class="iframe" href="<?php echo base_url().'index.php/RetailerController/Add'?>">
                                    <button type="submit" class="btn bg-primary margin"><i class="material-icons">person_add</i>  Add Retailer  </button>
                                  </a> --> 
                                  <button data-toggle="modal" data-target="#prodModal" class="modalLink btn btn-primary m-t-15 waves-effect">
                                <span class="icon-name"> <i class="material-icons">person_add</i>Add Product </span>
                              </button>

                              <button data-toggle="modal" data-target="#retailerModal" class="modalLink btn btn-primary m-t-15 waves-effect">
                                <span class="icon-name"> <i class="material-icons">person_add</i>Add Retailer </span>
                              </button>

                                <!--<a class="iframe" href="<?php echo base_url().'index.php/ProductController/Add'?>">-->
                                <!--    <button type="submit" class="btn bg-primary margin"><i class="material-icons">person_add</i>  Add Product  </button>-->
                                <!--  </a> -->
                                </p>
            </h2>
          </div>
          <?php if($this->session->flashdata('msg')): ?>
            <p align="center" class="bg-primary"><?php echo $this->session->flashdata('msg'); ?></p>
          <?php endif; ?>

          <form method="post" role="form" action="<?php
          if(isset($company))
          {
            echo site_url('CompanyController/update');
          }
          else
          {
            echo site_url('CompanyController/insert');
          }
          ?>"> 
          <div class="body">
            <div class="demo-masked-input">
              <div class="row clearfix">
               <input type="hidden" name="id" value="<?php
               if(isset($company))
               {
                echo $company[0]['id'];
              }
              ?>">
              <div class="col-md-12">
                <div class="col-md-3">
                  <b>Bill No</b>
                  <div class="input-group">
                    <span class="input-group-addon">
                     <i class="material-icons">receipt</i>
                   </span>
                   <div class="form-line">
                    <input id="billNo" type="text" name="billNo" class="form-control date" placeholder="billNo" readonly value="<?php echo 'SL'.(1000+$nextId); ?>">
                  </div>
                </div>
              </div>

              <div class="col-md-3">
              <b>Salesman</b>
              <div class="input-group">
                <span class="input-group-addon">
                 <i class="material-icons">person</i>
               </span>
                <div class="form-line">
                 <input type="text" id="salesman" autocomplete="off" list="emp" name="salesman" value="<?php if(!empty($this->session->userdata['tempSess'])){ echo $this->session->userdata['tempSess']['salesman'];}?>" class="form-control" placeholder="Salesman Name">   
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
            </div> 
           <!--  </div> -->
               <!-- <div class="col-md-12"> -->
              <div class="col-md-3">
                <b>Retailer</b>
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="material-icons">person</i>
                 </span>

                 <div class="form-line">
                 <input type="text" id="retailer" onInput="loadRouteData();" autocomplete="off" value="<?php if(!empty($this->session->userdata['tempSess'])){ echo $this->session->userdata['tempSess']['retailer'];}?>" list="routeN" name="retailer" class="form-control" placeholder="Retailer Name">   
                                        <datalist id="routeN">
                                            <?php
                                                foreach($retailer as $data){
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
              <b>Retailer Route</b>
              <div class="input-group">
                <span class="input-group-addon">
                 <i class="material-icons">person</i>
               </span>
                <div class="form-line">
                 <input type="text" id="route" readonly autocomplete="off" name="route" value="<?php if(!empty($this->session->userdata['tempSess'])){ echo $this->session->userdata['tempSess']['route'];}?>" class="form-control" placeholder="Route Name">   
                                        
                </div>
              </div>
            </div> 
            
        </div>
        <div>
          <div class="col-md-2">
              <b>Products</b>
              <div class="input-group">
                <span class="input-group-addon">
                 <!-- <i class="material-icons">check_circle</i> -->
               </span>
                <div class="form-line">
                <input type="text" id="pname" onblur="loadCustData();" autocomplete="off" list="prdNames" name="productName" class="form-control" placeholder="Product Name" required>   
                <datalist id="prdNames">
                    <?php
                        foreach($product as $data){
                            $name=$data['name'];
                            $id=$data['id'];
                    ?>   
                    <option value="<?php echo $name;?>"/>
                    <?php    
                        }
                    ?>
                </datalist>
                </div>
            </div>
          </div> 
          
            <input id="bxQty" type="hidden" name="boxQty" class="form-control date" placeholder="Pcs/Box in Case" readonly>
            <input id="mrp" type="hidden" readonly name="mrp" class="form-control date" placeholder="Enter MRP">
          
      <div class="col-md-2">
        <b>Quantity</b>
        <div class="input-group">
          <span class="input-group-addon">
           <!-- <i class="material-icons">check_circle</i> -->
         </span>
         <div class="form-line">
          <input id="qty" type="number" name="quantity" class="form-control date" placeholder="Enter Quantity" value="<?php if(isset($company))
          {
            echo $company[0]['name']; 
          }
          ?>">
        </div>
      </div>
    </div>
    <div class="col-md-2">
      <b>Units</b>
      <div class="input-group">
        <span class="input-group-addon">
         <!-- <i class="material-icons">check_circle</i> -->
       </span>
       <select id="unt1" name="units1" class="form-control">
        <option value="1">---Select Unit---</option>
        <option>Case</option>
        <option>Pcs</option>
      </select> 
    </div>
  </div>

  <div class="col-md-2">
    <b>Rate</b>
    <div class="input-group">
      <span class="input-group-addon">
       <!-- <i class="material-icons">check_circle</i> -->
     </span>
     <div class="form-line">
      <input id="rate" type="number" name="rate" class="form-control date" placeholder="Enter Rate" value="<?php if(isset($company))
      {
        echo $company[0]['name']; 
      }
      ?>">
    </div>
  </div>
</div>

<div class="col-md-2">
  <b>Units</b>
  <div class="input-group">
    <span class="input-group-addon">
     <!-- <i class="material-icons">check_circle</i> -->
   </span>
   <select id="unt2" name="units2" class="form-control">
    <option value="1">---Select Unit---</option>
    <option>Case</option>
    <option>Pcs</option>
  </select> 
</div>
</div>

<div class="col-md-2">
  <b>Quantity Available</b>
  <div class="input-group">
    <span class="input-group-addon">
     <!-- <i class="material-icons">check_circle</i> -->
   </span>
   <div class="form-line">
    <input readonly id="avlQty" type="text" name="availQty" class="form-control date" placeholder="Available Quantity" value="<?php if(isset($company))
    {
      echo $company[0]['name']; 
    }
    ?>"> 
  </div>
</div>
</div>

<!-- </div>

<div class="col-md-12"> -->

<!--  <div class="col-md-3">
  <b>Rate per piece</b>
  <div class="input-group">
    <span class="input-group-addon">
     <i class="material-icons">check_circle</i>
   </span>
   <div class ="form-line">-->
    <input readonly id="rpc" type="hidden" name="ratePrPC" class="form-control date" placeholder="Rate per piece" value="<?php if(isset($company))
    {
      echo $company[0]['name']; 
    }
    ?>">
  <!-- </div>
</div>
</div> -->

<!-- <div class="col-md-3">
  <b>Amount</b>
  <div class="input-group">
    <span class="input-group-addon">
     <i class="material-icons">check_circle</i>
   </span>
   <div class="form-line"> -->
    <input readonly id="amt" type="hidden" name="amount" class="form-control date" placeholder="Amount" value="<?php if(isset($company))
    {
      echo $company[0]['name']; 
    }
    ?>">
  <!-- </div>
</div>
</div> -->



<div class="col-md-3">

  <!-- <div class="row clearfix"> -->
    <!-- <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5"> -->
      <button class="add_cart btn btn-primary m-t-15 waves-effect">
        <i class="material-icons">save</i> 
        <span class="icon-name">
         Add
       </span>
     </button>
     
      <button type="button" onClick="clr();" class="btn btn-primary m-t-15 waves-effect">
        <i class="material-icons">cancel</i> 
        <span class="icon-name"> Cancel</span>
      </button>
  <!-- </div> -->
<!-- </div> -->
</div>

</div> 

<div class="col-md-12">
  

<!-- <div class="row clearfix"> -->
  <!-- <div class="body"> -->
    <div id="rc" class="table-responsive">
      <table id="tbl" class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
        <thead>
          <tr>
            <th>Item</th>
            <th>MRP</th>
            <th>Qty</th>
            <th>Rate</th>
            <th>Amount</th>
            <th>Remove</th>
          </tr>
        </thead>
        <tbody id="detail_cart">
            
        </tbody>
      </table>
    </div>
    <div class="row clearfix">
      <!-- <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5"> -->
    <div class="col-sm-offset-5 col-xs-offset-5">
      <button type="button" target="_blank" id="sndBtn" onClick="sendData();" class="btn btn-primary m-t-15 waves-effect">
        <i class="material-icons">print</i> 
        <span class="icon-name">
         Save & Print
       </span>
     </button>
     
     <button type="button" id="cnBtn" class="btn btn-primary m-t-15 waves-effect">
        <i class="material-icons">cancel</i> 
        <span class="icon-name">
         Cancel
       </span>
     </button>
     
   <!--  <a onClick="printData()">-->
   <!--     <button type="button" id="printBtn" class="btn btn-primary m-t-15 waves-effect">-->
   <!--     <i class="material-icons">print</i> -->
   <!--     <span class="icon-name">-->
   <!--      Print-->
   <!--    </span>-->
   <!--  </button>-->
   <!--</a>-->
   
  </div>

</div>
  <!-- </div> -->

<!-- </div> -->
</div>                                 
</div>

</div>
</div>
<?php form_close();?>
</div>
</div>
</div>
<!-- #END# Masked Input -->
</div>
</section>

<div class="container">
  <div class="modal fade" id="prodModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <center><h4 class="modal-title">Add Product</h4></center>
          </div>
          <div class="modal-body">
                      <div class="row clearfix">
                        <div class="body">
                            <div class="demo-masked-input">
                                <div class="col-md-12">
                                  <div class="col-md-4">
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

                                  <div class="col-md-4">
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

                                  <div class="col-md-4">
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
                                  </div>
                                  <div class="col-md-12">
                                    <div class="col-md-4">
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

                                    <div class="col-md-4">
                                        <b>No. of Pcs in a Box</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input onkeypress="return isNumber(event)" autocomplete="off" id="productUnitOne" type="text" name="productUnitOne" class="form-control date" placeholder="No. of Pcs in a Box" required>
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="col-md-4">
                                      <b>No. of box in a case</b>
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
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <b>No. of Cases</b>
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
                                                <button id="insProd" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name">Save</span>
                                                </button>
                                               
                                                <button data-dismiss="modal" type="button" class="btn btn-primary m-t-15 waves-effect">
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
                                <div class="row clearfix">
                                  <div class="col-md-4">
                                        <b>Retailer Code</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" id='retailerCode' autocomplete="off" value="<?php echo $retailerCode; ?>" name="retailerCode" list="ret" class="form-control date" placeholder="Enter retailer code" required>
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
                                                <button id="insRet" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name">Save</span>
                                                </button>
                                               
                                                    <button data-dismiss="modal" type="button" class="btn btn-primary m-t-15 waves-effect">
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


<?php $this->load->view('/layouts/footerDataTable'); ?>
<script type="text/javascript">

  function getParentByTagName(el,tag){
    tag=tag.toLowerCase();
    while(el.nodeName.toLowerCase()!=tag){
        el=el.parentNode;
    }
    return el;
  }

  function killMe(el){
    return el.parentNode.removeChild(el);
  }
  function delRow(){
    killMe(getParentByTagName(this,'tr'));
  }

  function updateForm() {
    var retailerName =document.getElementById('retailer').value;
    var salesmanName = document.getElementById("salesman").value;
    
    if(retailerName!='' && salesmanName!=''){
        
        var bxQty = eval(document.getElementById('bxQty').value);
        var aQty = document.getElementById("avlQty").value;
        var mrp = eval(document.getElementById('mrp').value);
        var qty = eval(document.getElementById('qty').value);
       
        var rate = eval(document.getElementById('rate').value);
        var unit = document.getElementById("unt1");
        var unit2 = document.getElementById("unt2");
        
         
       
        
        var unit = unit.options[unit.selectedIndex].text;
        var unit2 = unit2.options[unit2.selectedIndex].text;
        
        if(unit=='Pcs' && unit2 =='Case'){
            document.getElementById('rpc').value = rate / bxQty;
          var count=rate / bxQty;
          document.getElementById('amt').value = count*qty;
        }else if((unit=='Case' && unit2=='Case') || (unit=='Pcs' && unit2=='Pcs')){
          document.getElementById('amt').value = rate*qty;
        }else if(unit=='Case' && unit2=='Pcs'){
            document.getElementById('rpc').value = rate / bxQty;
            var count=qty*bxQty;
            document.getElementById('amt').value = count*rate;
        }
    
        var pName = document.getElementById("pname").value;
      
       // var pName = product.options[product.selectedIndex].text;
        var bxQty = document.getElementById("bxQty").value;
        var mrp = document.getElementById("mrp").value;
        var qty = document.getElementById("qty").value;
        var unt1 = document.getElementById("unt1").value;
    
        var rate = document.getElementById("rate").value;
        var unt2 = document.getElementById("unt2").value;
        // var rpc = document.getElementById("rpc").value;
        var amt = document.getElementById("amt").value;
        
        
        
        var sessData=pName+","+bxQty+","+mrp+","+qty+","+unt1+","+rate+","+unt2+","+amt;
        
        // var avlQty = document.getElementById("avlQty").value;
        if(pName=='' || qty=='' || rate == ''){
          alert('Please fill all the fields.')
        }else{
           var table=document.getElementById("tbl");
            var row=table.insertRow(-1);
        
            var cell2=row.insertCell(0);
            // var cell3=row.insertCell(1);
            var cell4=row.insertCell(1);
            var cell5=row.insertCell(2);
            // var cell6=row.insertCell(3);
            var cell7=row.insertCell(3);
            // var cell8=row.insertCell(6);
            // var cell9=row.insertCell(7);
            var cell10=row.insertCell(4);
            // var cell11=row.insertCell(9);
            
              cell2.innerHTML=pName;
              // cell3.innerHTML=bxQty; 
              cell4.innerHTML=mrp;  
              cell5.innerHTML=qty+' '+unt1;  
              // cell6.innerHTML=unt1;  
              cell7.innerHTML=rate+'/'+unt2;  
              // cell8.innerHTML=unt2;  
              // cell9.innerHTML=rpc;  
              cell10.innerHTML=amt;  
              // cell11.innerHTML=avlQty;     
              var cell12 = row.insertCell(5);
              var element3 = document.createElement("input");
              element3.type = "button";
              element3.className="del";
              element3.value='-';
              element3.onclick= delRow;
              
              cell12.appendChild(element3); 
              clr();
        }
         
    }else{
        alert('Please Select Retailer/Salesman');
    }
     
}
</script>

<script type="text/javascript">
  function loadCustData() { 
    
   document.getElementById("qty").value='';
   document.getElementById("rate").value='';
   document.getElementById('rpc').value='';
   document.getElementById('amt').value='';
   
   var xhttp = new XMLHttpRequest();
   xhttp.onreadystatechange = function() {
       
    if (this.readyState == 4 && this.status == 200) {
      var data=xhttp.responseText;
      var jsonResponse = JSON.parse(data);
      var cases=jsonResponse['product'][0]['availQty']/jsonResponse['product'][0]['boxQty'];
      var pcs=jsonResponse['product'][0]['availQty']%jsonResponse['product'][0]['boxQty'];
      cases=parseInt(cases);
      document.getElementById("bxQty").value = jsonResponse['product'][0]['boxQty'];
      document.getElementById("mrp").value =jsonResponse['product'][0]['price']; 
      document.getElementById("rate").value =jsonResponse['product'][0]['price']; 
      
      if(jsonResponse['product'][0]['availQty']<0){
          document.getElementById("avlQty").value =jsonResponse['product'][0]['availQty']+" Pcs";
      }else if(pcs>0 && cases>0){
          document.getElementById("avlQty").value =cases+" Cases "+pcs+" Pcs";
      }else if(cases>0){
          document.getElementById("avlQty").value =cases+" Cases ";
      }else if(pcs>0){
          document.getElementById("avlQty").value=pcs+" Pcs";
      }else if(jsonResponse['product'][0]['availQty']==0){
          document.getElementById("avlQty").value=jsonResponse['product'][0]['availQty'];
      }
      
    }
  };
  
  var x = document.getElementById("pname").value;

  xhttp.open("GET", "<?php echo site_url('DeliverySlipController/load/');?>"+x, true);
   
  xhttp.send();
  // clr();
}
</script>

<script type="text/javascript">
function loadRouteData() {  
   var xhttp = new XMLHttpRequest();
   xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var data=xhttp.responseText;
      var jsonResponse = JSON.parse(data);
      document.getElementById("route").value = jsonResponse['retailer'][0]['area'];
    }
  };
  
  var x = document.getElementById("retailer").value;
  xhttp.open("GET", "<?php echo site_url('DeliverySlipController/loadRetailerArea/');?>"+x, true);
  xhttp.send();
}

</script>

<script type="text/javascript">
  function clr(){
   //  var prod=document.getElementById("pname");
   // prod.selectedIndex = 0;
   //  var unt1=document.getElementById("unt1");
   //  unt1.selectedIndex = 0;
   //  var unt2=document.getElementById("unt2");
   // unt2.selectedIndex = 0;
   
    document.getElementById("pname").value = "";
    // document.getElementById("unt1").value = "--Select Unit--";
    // document.getElementById("unt1").selectedIndex = 1;
    // document.getElementById("unt2").selectedIndex = 1;
    // document.getElementById("unt2").value = "--Select Unit--";
    document.getElementById('bxQty').value='';
    document.getElementById('mrp').value='';
    document.getElementById('qty').value='';
    document.getElementById('rate').value='';
    document.getElementById('rpc').value='';
    document.getElementById('amt').value='';
    document.getElementById('avlQty').value='';
    $("#unt1").val("1").change();
    $("#unt2").val("1").change();
  }
</script>

<script type="text/javascript">
  var tableArr = [];
  
 
  // tableArr.push({bill:bl});
  function sendData(){
    var bl = document.getElementById("billNo").value;
    var retailer = document.getElementById("retailer").value;
    // var rName = retailer.options[retailer.selectedIndex].text;
      // alert(retailer);
    var salesman = document.getElementById("salesman").value;
    // var sName = salesman.options[salesman.selectedIndex].text;
    var route = document.getElementById("route").value;
    // alert(route);
    if(retailer == '' || salesman == ''){
        alert('please select retailer/salesman');
    }else{
       var table = document.getElementById( "tbl" );
      for ( var i = 1; i < table.rows.length; i++ ) {
          tableArr.push({
              billNo: bl,
              retailer: retailer,
              salesman: salesman,
              route:route,
              item: table.rows[i].cells[0].innerHTML,
              // box_pcs: table.rows[i].cells[1].innerHTML,
              mrp: table.rows[i].cells[1].innerHTML,
              qty: table.rows[i].cells[2].innerHTML,
            //   unit1: table.rows[i].cells[4].innerHTML,
              rate: table.rows[i].cells[3].innerHTML,
            //   unit2: table.rows[i].cells[6].innerHTML,
              // rpc: table.rows[i].cells[7].innerHTML,
              amt: table.rows[i].cells[4].innerHTML
          });
      }
      var data = JSON.stringify(tableArr);
      // var data=tableArr.toString();
   
      console.log(data);
      window.location.href = "<?php echo site_url('DeliverySlipController/getBillData?data=');?>"+data.toString();
    }
    
  };
  
</script>

<script type="text/javascript">
  var tableArr = [];
  function printData(){
    var bl = document.getElementById("billNo").value;
    var retailer = document.getElementById("retailer").value;
    // var rName = retailer.options[retailer.selectedIndex].text;
    
    var salesman = document.getElementById("salesman").value;
    // var sName = salesman.options[salesman.selectedIndex].text;
    var route = document.getElementById("route").value;
     var table = document.getElementById( "tbl" );
      for ( var i = 1; i < table.rows.length; i++ ) {
          tableArr.push({
              billNo: bl,
              retailer: retailer,
              salesman: salesman,
              route:route,
              item: table.rows[i].cells[0].innerHTML,
              // box_pcs: table.rows[i].cells[1].innerHTML,
              mrp: table.rows[i].cells[1].innerHTML,
              qty: table.rows[i].cells[2].innerHTML,
              // unit1: table.rows[i].cells[3].innerHTML,
              rate: table.rows[i].cells[3].innerHTML,
              // unit2: table.rows[i].cells[6].innerHTML,
              // rpc: table.rows[i].cells[7].innerHTML,
              amt: table.rows[i].cells[4].innerHTML
          });
      }
      var data = JSON.stringify(tableArr);
      // var data=tableArr.toString();
      // alert(data);
     
      window.location.href = "<?php echo site_url('DeliverySlipController/printPDF?data=');?>"+data.toString();
  };
  
</script>
<script type="text/javascript">
function createPDF() {
        var sTable = document.getElementById('tbl').innerHTML;

        var style = "<style>";
        style = style + "table {width: 100%;font: 17px Calibri;}";
        style = style + "table, th, td {border: solid 1px #DDD; border-collapse: collapse;";
        style = style + "padding: 2px 3px;text-align: center;}";
        style = style + "</style>";

        // CREATE A WINDOW OBJECT.
        // var win = window.open('', '_blank', 'height=700,width=700');
        var win =window.open('', '_blank', 'toolbar=0,location=0,menubar=0');
        win.document.write('<html><head>');
        win.document.write('<title>Profile</title>');   // <title> FOR PDF HEADER.
        win.document.write(style);          // ADD STYLE INSIDE THE HEAD TAG.
        win.document.write('</head>');
        win.document.write('<body>');
        win.document.write(sTable);         // THE TABLE CONTENTS INSIDE THE BODY TAG.
        win.document.write('</body></html>');

        win.document.close();   // CLOSE THE CURRENT WINDOW.

        win.print();    // PRINT THE CONTENTS.
    }
</script>
 <script type="text/javascript">
      $(document).ready(function(){
        $(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
    });
</script>

<script type="text/javascript">
    $(document).on("click","#insProd",function() {
        var productCompany = $('#productCompany').val();
        var productName = $('#productName').val();
        var productCode = $('#productCode').val();
        var productMrp = $('#productMrp').val();
        var productUnitOne = $('#productUnitOne').val();
        var productUnitTwo = $('#productUnitTwo').val();
        var productUnitThree = $('#productUnitThree').val();

        if(productCompany==="" || productName==="" || productCode==="" || productMrp==="" || productUnitOne==="" || productUnitTwo==="" || productUnitThree===""){
            alert("Please enter all details");
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('ProductController/insert');?>",
                data:{"productName" : productName,"productCompany":productCompany,"productCode":productCode,"productMrp":productMrp,"productUnitOne":productUnitOne,"productUnitTwo":productUnitTwo,"productUnitThree":productUnitThree},
                success: function (data) {
                  alert(data);
                    $('#recStatus').html(data);
                    window.location.href="<?php echo base_url();?>index.php/DeliverySlipController";
                }  
            });
        }
    });
 </script>
 
 
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
                //   ('#recStatus').innerHTML=data
                  alert(data);
                    // $('#recStatus1').html(data);
                    window.location.href="<?php echo base_url();?>index.php/DeliverySlipController";
                }  
            });
        }
    });
 </script>
 
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
          $(document).ready(function(){
            $('.add_cart').click(function(){
                var retailer=$('#retailer').val();
                var salesman=$('#salesman').val();
                var routeName=$('#route').val();
                  
                var bxQty = $('#bxQty').val();
                var aQty = $('#avlQty').val();
                var mrp = $('#mrp').val();
                var qty = $('#qty').val();
                var rate = $('#rate').val();
                var unit = $('#unt1').val();
                var unit2 = $('#unt2').val();

                alert(bxQty+' -bx,'+aQty+' -aqty,');
              
                
                if(unit=='Pcs' && unit2 =='Case'){
                    $('#rpc').val(rate / bxQty);
                    var count=rate / bxQty;
                    $('#amt').val(count*qty);
                }else if((unit=='Case' && unit2=='Case') || (unit=='Pcs' && unit2=='Pcs')){
                    $('#amt').val(rate*qty);
                }else if(unit=='Case' && unit2=='Pcs'){
                    $('#rpc').val(rate / bxQty);
                    var count=qty*bxQty;
                    $('#amt').val(count*rate);
                }
            
                var pName = $('#pname').val();
                var bxQty = $('#bxQty').val();
                var mrp = $('#mrp').val();
                var qty = $('#qty').val();
                var unt1 = $('#unt1').val();
                var rate = $('#rate').val();
                var unt2 = $('#unt2').val();
                var amt = $('#amt').val();
                // alert(pName+" "+bxQty+" "+mrp+" "+qty+" "+unt1+" "+rate+" "+unt2+" "+amt);
              
              if(retailer!='' && salesman !='' && routeName !=''){
                  if(pName!='' || qty!='' || rate!=''){
                      $.ajax({
                        url : "<?php echo site_url('DeliverySlipController/add_to_cart');?>",
                        method : "POST",
                        data : {pName:pName,bxQty:bxQty,mrp:mrp,qty:qty,unt1:unt1,rate:rate,unt2:unt2,amt:amt,salesman:salesman,retailer:retailer,route:routeName},
                        success: function(data){
                          $('#detail_cart').html(data);
                        }
                      });
                  }else{
                      alert('Please fill all the fields...!');
                  }
              }else{
                  alert('Please select Retailer/Salesman');
              }
              
            $('#pname').val("");  
            $('#bxQty').val("");
            $('#avlQty').val("");
            $('#mrp').val("");
            $('#qty').val("");
            $('#rate').val("");
            $("#unt1").val("1").change();
            $("#unt2").val("1").change();
            });

            
            $('#detail_cart').load("<?php echo site_url('DeliverySlipController/load_cart');?>");

            
            $(document).on('click','.romove_cart',function(){
              var row_id=$(this).attr("id"); 
            
              $.ajax({
                url : "<?php echo site_url('DeliverySlipController/delete_cart');?>",
                method : "POST",
                data : {row_id : row_id},
                success :function(data){
                  $('#detail_cart').html(data);
                }
              });
            });
          });
</script>

<script>
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 45 || charCode > 57) ) {
            return false;
        }
        return true;
    }
</script>