<?php $this->load->view('/layouts/commanHeader'); ?>

<style type="text/css">
    @media screen and (min-width: 1000px) {
        .modal-dialog {
          width: 1000px; /* New width for default modal */
        }
        .modal-sm {
          width: 350px; /* New width for small modal */
        }
    }

    @media screen and (min-width: 1000px) {
        .modal-lg {
          width: 1000px; /* New width for large modal */
        }
    }

</style>

<script>
    $(document).ready(function() {
    $.fn.dataTable.ext.errMode = 'none';
    $('#prodTbl').DataTable( {
        dom: 'Bfrtip',
        stateSave: true,
            buttons: [{
                    extend: 'pdf',
                    title: 'Products Details',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                },{
                    extend: 'excel',
                    title: 'Products Details',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                }, {
                    extend: 'csv',
                    title: 'Products Details',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                }
            ]
        
    } );
} );
</script>
    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;">
        <div class="container-fluid">
           
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header flex-div">
                            <h2>
                               Products Details 
                            </h2>
                            <p align="right">
                                 <button data-toggle="modal" id="prod-modal-ref" data-target="#newProdModal" class="modalLink btnStyle btn btn-primary m-t-15 waves-effect">
                                    <span class="icon-name"> <i class="material-icons">person_add</i>Add Product </span>
                                  </button>
                            
                                  <a href="<?php echo site_url('DeliverySlipController/blockedProducts/');?>">
                                  <button type="submit" class="modalLink btn btnStyle btn-primary m-t-15 waves-effect"><i class="material-icons">visibility</i>  Show Inactive Products </button>
                                  </a> 
                                  <button data-toggle="modal" data-target="#ImportProductsModal" class="btn btnStyle btn-primary m-t-15 waves-effect"><i class="material-icons">upload</i> Upload Products</button>
                            </p>
                            
                           
                        </div>
                       <!--  <div class="body outer">
                            <div class="table-responsive inner"> -->
                                <div class="body outer">
                                    
                                <div class="table-responsive">
                                <table id="prodTbl" class="table table-bordered cust-tbl js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Company</th>
                                            <th class="noSpace">Product Code</th>
                                            <th>Product Name</th>
                                            <th>Configuration</th>
                                            <th>MRP</th>
                                            <th class="noSpace">Pcs In Box</th>
                                            <th class="noSpace">Box In Case</th>
                                            <th class="noSpace">Quantity Available</th>
                                            <th class="noSpace">Edit Stock</th>
                                            <th class="noExport">Action</th> 
                                        </tr>
                                    </thead>
                                    <tfoot>
                                       <tr>
                                            <th>No</th>
                                            <th>Company</th>
                                            <th class="noSpace">Product Code</th>
                                            <th>Product Name</th>
                                            <th>Configuration</th>
                                            <th>MRP</th>
                                            <th class="noSpace">Pcs In Box</th>
                                            <th class="noSpace">Box In Case</th>
                                            <th class="noSpace">Quantity Available</th>
                                            <th class="noSpace">Edit Stock</th>
                                            <th class="noExport">Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                  
                                  <?php
                                    $no=0;
                                      if(!empty($prod)) {
                                        foreach ($prod as $data) {
                                          $no++;

                                          $sumOfAdd=0;
                                          $sumOfReduce=0;
                                          $sumOfReplace=0;
                                          $fin=$this->DeliverySlipModel->getTotalQtySum('deliveryslip_pending_for_billing',$data['id']);
                                          $add=$this->DeliverySlipModel->getSumOfAddQty('deliveryslip_pending_for_billing',$data['id']);
                                          $reduce=$this->DeliverySlipModel->getSumOfReduceQty('deliveryslip_pending_for_billing',$data['id']);
                                          $replace=$this->DeliverySlipModel->getSumOfReplaceQty('deliveryslip_pending_for_billing',$data['id']);

                                          $finalQtyTotal=0;
                                          if(!empty($fin)){
                                              $finalQtyTotal=$fin[0]['totatQuantity'];
                                              // echo $add[0]['totatAddQuantity'].' ';
                                          }
                                          if(!empty($add)){
                                              $sumOfAdd=$add[0]['totatAddQuantity'];
                                              // echo $add[0]['totatAddQuantity'].' ';
                                          }

                                          if(!empty($reduce)){
                                              $sumOfReduce=$reduce[0]['totatReduceQuantity'];
                                              // echo $reduce[0]['totatReduceQuantity'].' ';
                                          }

                                          if(!empty($replace)){
                                              $sumOfReplace=$replace[0]['totatReplaceQuantity'];
                                              // echo $replace[0]['totatReplaceQuantity'].' ';
                                          }
                                    ?>    

                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td><?php echo $data['company']; ?></td>
                                            <td><?php echo $data['productCode']; ?></td>
                                            <td><?php echo $data['name']; ?></td>
                                            <td>
                                              <?php 
                                                if($data['unitFilter']=="u1"){
                                                    echo "Unit 2"; 
                                                }else{
                                                    echo "Unit 3"; 
                                                }
                                                
                                              ?>
                                            </td>
                                            <td><?php echo $data['mrp']; ?></td>
                                            <td><?php echo $data['unitOne']; ?></td>
                                            <td><?php echo $data['unitTwo']; ?></td>
                                            <td>
                                            <?php
                                                // if($sumOfReplace > 0){
                                                //     $dataQty=($sumOfReduce-$sumOfAdd);
                                                //     // echo $dataQty;
                                                //     $final=($sumOfReplace+$dataQty);
                                                //     echo $final.' Pcs'; 
                                                // }else{
                                                //     echo ($sumOfAdd-$sumOfReduce).' Pcs'; 
                                                // } 
                                            echo $finalQtyTotal.' Pcs';
                                            ?> 
                                            </td>
                                            <td>
                                              <a class="prdQtyLink" data-toggle="modal" data-target="#addProdQtyModal" data-id="<?php echo $data['id']; ?>" data-prodName="<?php echo $data['name']; ?>"
                                                data-prodCode="<?php echo $data['productCode']; ?>" data-company="<?php echo $data['company']; ?>" data-prodFilter="<?php echo $data['unitFilter']; ?>" data-mrp="<?php echo $data['mrp']; ?>" href="#">
                                                    <b>
                                                    <i class="material-icons" style="color: orange;">add_circle</i> 
                                                    </b>
                                                </a> 
                                            </td>
                                            <td>
                                               <a class="prdLink" data-toggle="modal" data-target="#prodModal" data-unitFilter="<?php echo $data['unitFilter']; ?>" data-id="<?php echo $data['id']; ?>" href="#">
                                                    <b>
                                                        <i class="material-icons" style="color: green;">edit</i> 
                                                    </b>
                                                </a> 
                                               <a href="<?php echo base_url().'index.php/ProductController/deactivateProduct/'.$data['id'];  ?>">
                                                    <b>
                                                        <i class="material-icons" style="color: red;">remove_circle</i> 
                                                    </b>
                                                </a>  
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

<div class="container">
  <div class="modal fade" id="addProdQtyModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <center><h4 class="modal-title" id="stsAddSub">Add Product Quantity</h4></center>
              <div class="col-md-12 m-t-25 cust-tbl noPadding">
                  <div class="col-md-3">
                     Product Code : <span id="prdCodeText" style="color:black"></span>
                  </div>
                  <div class="col-md-3">
                     Product Name :<span id="prdNameText" style="color:black"></span>
                  </div>
                  <div class="col-md-3">
                    Product Company : <span id="prdCompanyText" style="color:black"></span>
                  </div>
                  <div class="col-md-3">
                    Product MRP : <span id="prdMrpText" style="color:black"></span>
                  </div>
              </div>
                          
          </div>
          <div class="modal-body">
            <form method="post" role="form" action="<?php echo site_url('ProductController/changeQuantityForProduct'); ?>"> 
                      <div class="row clearfix">
                          <div class="body">

                            <div class="demo-masked-input">
                                  <input id="addProdQtyId" type="hidden" name="addProdQtyId">
                                  <div class="col-md-12 cust-tbl">
                                    <div class="col-md-4">
                                        <b>Product Quantity</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input onkeypress="return isNumber(event)" autocomplete="off" id="addProdQty" type="text" name="addProdQty" class="form-control" placeholder="Product quantity" required>
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="col-md-4">
                                        <b>Select Category</b>
                                        <div class="input-group">
                                          
                                              <input type="radio" name="addReduce" checked id="add_1" value="add" class="with-gap radio-col-red" />
                                              <label for="add_1">Add</label>
                                              <br>
                                              <input type="radio" name="addReduce" id="add_2" value="reduce" class="with-gap radio-col-red" />
                                              <label for="add_2">Reduce</label>
                                              <br>
                                              <input type="radio" name="addReduce" id="add_3" value="replace" class="with-gap radio-col-red" />
                                              <label for="add_3">Replace</label>
                                        </div>
                                    </div> 

                                    <div class="col-md-4">
                                        <b>Select Unit</b><br>
                                        <div class="input-group">
                                              <input type="radio" name="unit_category" checked id="unit_cat_3" value="case" class="with-gap radio-col-red" />
                                              <label for="unit_cat_3">Case</label>
                                              <br>
                                              <input type="radio" name="unit_category" id="unit_cat_2" value="box" class="with-gap radio-col-red" />
                                              <label for="unit_cat_2">Box</label>
                                              <br>
                                              <input type="radio" name="unit_category" id="unit_cat_1" value="pcs" class="with-gap radio-col-red" />
                                              <label for="unit_cat_1">Pcs</label>
                                        </div>
                                    </div> 
                                </div>
                                  
                                 <div class="col-md-12">
                                    <div class="row clearfix">
                                        <div class="col-md-4">
                                            <button id="insProdQty" class="btn btnStyle btn-primary m-t-15 waves-effect">
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
                  </form>
          </div>
      </div>
    </div>
  </div>
</div>


<div class="container">
  <div class="modal fade" id="newProdModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <center><h4 class="modal-title">Add Product</h4></center>
          </div>
          <div class="modal-body">
                      <div class="row clearfix">
                        <div class="body">
                            <div class="demo-masked-input">
                                <div class="col-md-12 cust-tbl">
                                  <div class="col-md-3">
                                      <b>Company</b>
                                      <div class="input-group">
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
                                          <div class="form-line">
                                              <input autocomplete="off" id="productCode" type="text" name="productCode" class="form-control date" placeholder="Enter product code" required>
                                          </div>
                                      </div>
                                  </div> 

                                  <div class="col-md-3">
                                        <b>Product Name</b>
                                        <div class="input-group">
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
                                            <div class="form-line">
                                                <input onkeypress="return isNumber(event)" autocomplete="off" id="productMrp" type="text" name="productMrp" class="form-control date" placeholder="MRP (per Pcs)" required>
                                            </div>
                                        </div>
                                    </div>  
                                  </div>
                                  <div class="col-md-12 cust-tbl">
                                    <div class="col-md-3">
                                        <!-- <b>No. of Pcs in a Box</b> -->
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
                                            <div class="form-line">
                                                <input onkeypress="return isNumber(event)" autocomplete="off" id="productUnitOne" type="text" name="productUnitOne" class="form-control date" placeholder="No. of Pcs in a Box" required>
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="col-md-3" id="un2" style="display:none;">
                                        <b>Unit 2 (Box)</b>
                                        <div class="input-group">
                                            <div class="form-line">
                                              <input onkeypress="return isNumber(event)" autocomplete="off" id="productUnitTwo" type="text" name="productUnitTwo" class="form-control date" placeholder="No. of box in a case" required>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-3" id="un22" style="display:none;">
                                        <b>Select Unit</b>
                                        <div class="input-group">
                                              <div class="form-line">
                                                <select id="productUnitTwo" name="productUnitTwo" class="form-control">
                                                    <option value=""> Select Unit</option>
                                                    <option value="box">Box</option>
                                                    <option value="poly">Poly</option>
                                                    <option value="packet">Packet</option>
                                                </select>
                                              </div>
                                        </div>
                                  </div>  -->

                                  <div class="col-md-3" id="un3">
                                        <b id="txtunit2">Unit 2 (Case)</b>
                                        <div class="input-group">
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
                                                <button id="insProd" class="btn btnStyle btn-primary m-t-15 waves-effect">
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
  <div class="modal fade" id="QtyModal" role="dialog">
    <div class="modal-dialog">
      <div  class="modal-content">
          
      </div>
    </div>
  </div>
</div>

<div class="container">
  <div class="modal fade" id="prodModal" role="dialog">
    <div class="modal-dialog">
      <div id="prd-data" class="modal-content">
        
      </div>
    </div>
  </div>
</div>

   <div class="modal fade" id="ImportProductsModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
            <center><h4 class="modal-title">Upload Products File</h4></center>
          </div>
          <div class="modal-body">
           <form method="post" role="form"  enctype="multipart/form-data"  action="<?php echo site_url('DeliverySlipController/productsDataUploading');?>"> 
                    <div class="col-md-12 cust-tbl">
                        <div class="col-md-4">
                            <b> Products File </b>
                            <div class="input-group">
                              <div class="form-line">
                               <input type="file" name="file" class="form-control" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">                               
                             </div>
                            </div>
                        </div>
                          
                        <div class="col-md-4">
                            <div class="input-group">
                                <button type="submit" class="btn btn-sm btnStyle btn-primary m-t-20 margin">Upload</button>
                                <a href="<?php echo site_url('DeliverySlipController/Products');?>">
                                  <button type="button" class="btn btn-sm btn-danger m-t-20 margin">Cancel</button>
                                </a>
                            </div>
                        </div>
                    </div>
              </form>
          </div>
          <div class="modal-footer">
          
          </div>
      </div>
    </div>
  </div>
  
<?php $this->load->view('/layouts/footerDataTable'); ?>
<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>
<script>
 $(document).ready(function(){
    $('.qtyAdd').click(function(){
        var id=$(this).attr('data-id');
        var name=$(this).attr('data-name');
        $.ajax({
            url : "<?php echo site_url('ProductController/addQty');?>",
            method : "POST",
            data : {id: id,name:name},
            success: function(data){
              $('.modal-content').html(data);
            }
        });
    });
});
</script>

<script>
 $(document).ready(function(){
    var filter="u1";
    $('input[type=radio][name=unitFilterUpd]').change(function() {
        if (this.value == 'u1') {
              filter="u1";
        }
        else if (this.value == 'u2') {
              filter="u2";
        }
    });

    $('.prdLink').click(function(){
        var id=$(this).attr('data-id');
        var unitFilter=$(this).attr('data-unitFilter');
        
        $.ajax({
            url : "<?php echo site_url('ProductController/loadProductDetails');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
              $('#prd-data').html(data);

              if(unitFilter==='u2'){
                  $('#updDrop').show();
              }else{
                $('#updDrop').hide();
              }
            }
        });
    });
});
</script>

<script>
 $(document).ready(function(){
    $('.prdQtyLink').click(function(){
        var id=$(this).attr('data-id');
        var prodName=$(this).attr('data-prodName');
        var prodCode=$(this).attr('data-prodCode');
        var prodFilter=$(this).attr('data-prodFilter');
        var company=$(this).attr('data-company');
        var mrp=$(this).attr('data-mrp');
        
        $('#addProdQtyId').val(id);
        $('#prdCodeText').text(prodCode);
        $('#prdNameText').text(prodName);
        $('#prdCompanyText').text(company);
        $('#prdMrpText').text(mrp+' Rs.');

        if(prodFilter==="u1"){
           $("#unit_cat_2").prop("disabled", true);
        }else{
          $("#unit_cat_2").prop("disabled", false);
        }
    });
});
</script>




<script Language="JavaScript">
    function validateR(){
      if (document.getElementById("qtyOption").value == "Select Option") {
         alert("Please Select Quantity Option");
         return false;
      }
         return true;
    }
</script>


<script>
function deleted(id)
{ 
    swal({
      title: "Are you sure to delete?",
      text: "Once deleted, you will not be able to recover this!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
            url: "<?php echo site_url('ProductController/delete');?>",
            type: "post",
            data: {'id':id},
            success: function (response) {
             
              swal(response, {
                icon: "success",
              });
              var URL = "<?php echo site_url('DeliverySlipController/Products');?>";
              setTimeout(function(){ window.location = URL; }, 1000);
            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus, errorThrown);
            }
        });
        
      } else {
        swal("Your record is safe!");
      }
    });
}
</script>

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
                    // $('#recStatus').html(data);
                    window.location.href="<?php echo base_url();?>index.php/DeliverySlipController/Products";
                }  
            });
        }
    });
 </script>

 <script type="text/javascript">
    var filter="u1";
    $('input[type=radio][name=unitFilterUpd]').change(function() {
        if (this.value == 'u1') {
              filter="u1";
        }
        else if (this.value == 'u2') {
              filter="u2";
        }
    });

    $(document).on("click","#updProd",function() {
        var prodId = $('#prodIdU').val();
        var productCompany = $('#productCompanyU').val();
        var productName = $('#productNameU').val();
        var productCode = $('#productCodeU').val();
        var productMrp = $('#productMrpU').val();
        var productUnitOne = $('#productUnitOneU').val();
        // var productUnitTwo = $('#productUnitTwoU').val();
        var productUnitThree = $('#productUnitThreeU').val();

        var productUnitTwo = "";

        if(filter==="u2"){
          productUnitTwo = $('#productUnitTwoU').val();
        }else{
          productUnitTwo = '1';
        }
       
        if(productCompany==="" || productName==="" || productCode==="" || productMrp==="" || productUnitOne==="" || productUnitTwo==="" || productUnitThree===""){
            alert("Please enter all details");
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('ProductController/update');?>",
                data:{"filter":filter,"prodId":prodId,"productName" : productName,"productCompany":productCompany,"productCode":productCode,"productMrp":productMrp,"productUnitOne":productUnitOne,"productUnitTwo":productUnitTwo,"productUnitThree":productUnitThree},
                success: function (data) {
                  alert(data);
                    // $('#recStatus').html(data);
                    window.location.href="<?php echo base_url();?>index.php/DeliverySlipController/Products";
                }  
            });
        }
    });
</script>

<script type="text/javascript">
    $(document).on("click","#insProdQty",function() {
        var prodQty = $('#addProdQty').val();
        var prodId = $('#addProdQtyId').val();
       
        if(prodQty===""){
            alert("Please enter all details");
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('ProductController/insertProdQuantity');?>",
                data:{"prodId":prodId,"prodQty" : prodQty},
                success: function (data) {
                  alert(data);
                    // $('#recStatus').html(data);
                    window.location.href="<?php echo base_url();?>index.php/DeliverySlipController/Products";
                }  
            });
        }
    });
 </script>

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

 <script type="text/javascript">
     $("input[type='radio'],[name='unitFilterUpd']").change(function() {
        if (this.value == 'u1') {
              $('#updDrop').hide();
              $('#txtunitU2').text('Unit 2 (Case)');
        }
        else if (this.value == 'u2') {
              $('#updDrop').show();
              $('#txtunitU2').text('Unit 3 (Case)');
        }
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


<script type="text/javascript">
     $("input[type='radio'],[name='addReduce']").change(function() {
        if (this.value === 'add') {
              $('#stsAddSub').text('Add Product Quantity');
        }
        else if (this.value === 'reduce') {
              $('#stsAddSub').text('Reduce Product Quantity');
        }
    });
 </script>