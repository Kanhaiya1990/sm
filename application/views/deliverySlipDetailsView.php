<?php 
    $this->load->view('/layouts/commanHeader'); 

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

    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header flex-div">
                            <h2 class="text-center"> Delivery Slip Details </h2>
                        </div>
                       
                        <div class="body outer">
                          <div class="top-panel">
                              <div class="btn-group pull-right">
                                <button type="button" class="btn btn-primary btnStyle btn-lg dropdown-toggle" data-toggle="dropdown">Export <span class="caret"></span></button>
                                <ul class="dropdown-menu" role="menu">
                                  <!-- <li><a class="dataExport" data-type="csv">CSV</a></li> -->
                                  <li><a class="dataExport" data-type="excel">XLS</a></li>          
                                </ul>
                              </div>
                            </div>
                          <div class="row cust-tbl">
                                <div class="col-sm-3">
                                  <b>Search Anything</b>
                                  <div class="form-group">
                                    <div class="form-line">
                                       <input type="text" name="searchFor" placeholder="Search..." class="form-control" id="searchKey" onchange="sendRequest();">
                                    </div>
                                  </div>
                                </div>

                                <div class="col-sm-3">
                                  <b>Range</b>
                                  <div class="form-group">
                                    <select class="form-control" id="limitRows" onchange="sendRequest();">
                                      <option value="100">100</option>
                                      <option value="500">500</option>
                                      <option value="1000">1000</option>
                                      <option value="2000">2000</option>
                                      <option value="5000">5000</option>
                                      <option value="10000">10000</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-sm-3">
                                  <a href="<?php echo site_url('DeliverySlipController/deliverySlipDetail'); ?>" class="btn btn-sm m-t-15 btn-danger waves-effect">
                                        <i class="material-icons">cancel</i> 
                                        <span class="icon-name"> Cancel</span>
                                    </a>
                                </div>
                            </div>
                            <div class="table-responsive">
                              <?php echo $pagination; ?>
                                <table id="retTbl" class="table table-bordered cust-tbl js-exportable dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th data-action="sort" data-title="compName" data-direction="ASC">Company </th>
                                            <th data-action="sort" data-title="billNo" data-direction="ASC">Bill Number </th>
                                            <th data-action="sort" data-title="date" data-direction="ASC">Date</th>
                                            <th data-action="sort" data-title="retailerName" data-direction="ASC">Retailer</th>
                                            <th class="text-right" data-action="sort" data-title="netAmount" data-direction="ASC">Bill Amount</th>
                                            <th class="text-right" data-action="sort" data-title="pendingAmt" data-direction="ASC">Pending Amount</th>
                                            <th data-action="sort" data-title="salesman" data-direction="ASC">Salesman</th>
                                            <th> Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>S. No.</th>
                                            <th data-action="sort" data-title="compName" data-direction="ASC">Company </th>
                                            <th data-action="sort" data-title="billNo" data-direction="ASC">Bill Number </th>
                                            <th data-action="sort" data-title="date" data-direction="ASC">Date</th>
                                            <th data-action="sort" data-title="retailerName" data-direction="ASC">Retailer</th>
                                            <th class="text-right" data-action="sort" data-title="netAmount" data-direction="ASC">Bill Amount</th>
                                            <th class="text-right" data-action="sort" data-title="pendingAmt" data-direction="ASC">Pending Amount</th>
                                            <th data-action="sort" data-title="salesman" data-direction="ASC">Salesman</th>
                                            <th> Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                  
                                    <?php
                                      $no=0;
                                      if(!empty($pendingForBilling)){
                                          foreach ($pendingForBilling as $data) 
                                          {
                                            $retailerCode=$this->DeliverySlipModel->loadRetailer($data['retailerCode']);


                                          $no++;
                                    ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td><?php echo $data['compName']; ?></td>
                                            <td><?php echo $data['billNo']; ?></td>
                                            <td><?php echo date("d-M-Y", strtotime($data['date'])); ?></td>
                                            <td><?php echo $data['retailerName']; ?></td>
                                            <td class="text-right"><?php echo number_format($data['netAmount']); ?></td>
                                            <td class="text-right"><?php echo number_format($data['pendingAmt']); ?></td>
                                            <td><?php echo $data['salesman']; ?></td>
                                            <td>
                                                <?php
                                                  if($data['deliveryslipOwnerApproval']=="0"){
                                                      echo "<p style='color:red'>Unaccounted</p>";
                                                  }else{
                                                      $allocations=$this->DeliverySlipModel->getCloseAllocationDetailsByBill('bills',$data['id']);
                                                      $officeAllocations=$this->DeliverySlipModel->getCloseOfficeAllocationDetailsByBill('bills',$data['id']);
                                                        
                                                      if(!empty($allocations)){
                                                          echo "<p style='color:blue'>Allocated in : ".$allocations[0]['allocationCode']."</p>";
                                                      }else if(!empty($officeAllocations)){
                                                          echo "<p style='color:blue'>Allocated in : ".$officeAllocations[0]['allocationCode']."</p>";
                                                      }else if($data['netAmount']==$data['pendingAmt']){
                                                          echo "<p style='color:green'>Billed</p>";
                                                      }else if($data['netAmount']>$data['pendingAmt']){
                                                          echo "<p style='color:green'>Accounted</p>";
                                                      }
                                                  }

                                                    
                                                 ?>
                                            </td>
                        <td class="noSpace">
                        <?php if($data['isAllocated']==0){ ?>
                        <a id="prDetailsForAll" href="javascript:void()" data-id="<?php echo $data['id']; ?>" data-salesman="<?php echo $data['salesman']; ?>" data-billDate="<?php echo date("d-M-Y", strtotime($data['date'])); ?>" data-credAdj="<?php echo $data['creditAdjustment']; ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>" data-gst="<?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?>" data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-route="<?php echo $data['routeName']; ?>" data-toggle="modal" data-target="#processModalForAll" ><button class="btn btn-xs process-btn waves-effect waves-float" data-toggle="tooltip" data-placement="bottom" title="Process"><i class="material-icons">touch_app</i></button></a>

                        <!-- <a id="prDetails" href="javascript:void()" data-id="<?php echo $data['id']; ?>" data-salesman="<?php echo $data['salesman']; ?>" data-billDate="<?php echo date("d-M-Y", strtotime($data['date'])); ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>"  data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-toggle="modal" data-target="#processModal"><button class="btn btn-xs btn-primary waves-effect waves-float" data-toggle="tooltip" data-placement="bottom" title="Process"><i class="material-icons">touch_app</i></button></a> -->
                        <?php } ?>
                                              
                        <a href="<?php echo site_url('AdHocController/billHistoryInfo/'.$data['id']); ?>" class="btn btn-xs history-btn" data-toggle="tooltip" data-placement="bottom" title="View History"><i class="material-icons">info</i></a>
                                             
                        <a href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs viewBill-btn" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                                              
                        <a target="_blank"  href="<?php echo site_url('DeliverySlipController/pastDeliverySlipDownloadPDF/'.$data['id']); ?>" class="btn btn-xs  pdf-btn" data-toggle="tooltip" data-placement="bottom" title="Download PDF"><i class="material-icons">download</i></a>
                                            </td>
                                        </tr>
                                    <?php
                                        }
                                      }
                                    ?> 
                                  
                                    </tbody>
                                </table>
                                <?php echo $pagination; ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>



<div class="container">
  <div class="modal fade" id="getDeliveryDetailModal" role="dialog">
    <div class="modal-dialog">
      <div id="prd-data" class="modal-content">

        
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('/layouts/footerDataTable'); ?>

<?php $this->load->view('/layouts/processButtonView'); ?>

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
    $('.prdQtyLink').click(function(){
        var id=$(this).attr('data-id');
        $.ajax({
            url : "<?php echo site_url('DeliverySlipController/getDeliverySlipDetails');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
              // alert(data);
              $('#prd-data').html(data);
            }
        });
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
                    // $('#recStatus').html(data);
                    window.location.href="<?php echo base_url();?>index.php/DeliverySlipController/Products";
                }  
            });
        }
    });
 </script>

 <script type="text/javascript">
    $(document).on("click","#updProd",function() {
        var prodId = $('#prodIdU').val();
        var productCompany = $('#productCompanyU').val();
        var productName = $('#productNameU').val();
        var productCode = $('#productCodeU').val();
        var productMrp = $('#productMrpU').val();
        var productUnitOne = $('#productUnitOneU').val();
        var productUnitTwo = $('#productUnitTwoU').val();
        var productUnitThree = $('#productUnitThreeU').val();

       
        if(productCompany==="" || productName==="" || productCode==="" || productMrp==="" || productUnitOne==="" || productUnitTwo==="" || productUnitThree===""){
            alert("Please enter all details");
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('ProductController/update');?>",
                data:{"prodId":prodId,"productName" : productName,"productCompany":productCompany,"productCode":productCode,"productMrp":productMrp,"productUnitOne":productUnitOne,"productUnitTwo":productUnitTwo,"productUnitThree":productUnitThree},
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



<script type="text/javascript">
    var sendRequest = function(){
      // var curOrderField = "BillNo";
      // var curOrderDirection = "ASC";
      var searchKey = $('#searchKey').val();
      var limitRows = $('#limitRows').val();
      window.location.href = '<?=base_url('index.php/DeliverySlipController/deliverySlipDetail')?>?query='+searchKey+'&limitRows='+limitRows+'&orderField='+curOrderField+'&orderDirection='+curOrderDirection;
    }

    var getNamedParameter = function (key) {
            if (key == undefined) return false;
            var url = window.location.href;
            var path_arr = url.split('?');
            if (path_arr.length === 1) {
                return null;
            }
            path_arr = path_arr[1].split('&');
            path_arr = remove_value(path_arr, "");
            var value = undefined;
            for (var i = 0; i < path_arr.length; i++) {
                var keyValue = path_arr[i].split('=');
                if (keyValue[0] == key) {
                    value = keyValue[1];
                    break;
                }
            }
            return value;
        };

        var remove_value = function (value, remove) {
            if (value.indexOf(remove) > -1) {
                value.splice(value.indexOf(remove), 1);
                remove_value(value, remove);
            }
            return value;
        };

        var curOrderField, curOrderDirection;
        $('[data-action="sort"]').on('click', function(e){
          curOrderField = $(this).data('title');
          curOrderDirection = $(this).data('direction');
          // curOrderField = "BillNo";
          // curOrderDirection = "ASC";
          sendRequest();
        });

        $('#searchKey').val(decodeURIComponent(getNamedParameter('query')||""));
        $('#limitRows option[value="'+getNamedParameter('limitRows')+'"]').attr('selected', true);

        var curOrderField = getNamedParameter('orderField')||"";
        var curOrderDirection = getNamedParameter('orderDirection')||"";
        var currentSort = $('[data-action="sort"][data-title="'+getNamedParameter('orderField')+'"]');
        if(curOrderDirection=="ASC"){
          currentSort.attr('data-direction', "DESC").find('i.glyphicon').removeClass('glyphicon-triangle-bottom').addClass('glyphicon-triangle-top'); 
        }else{
          currentSort.attr('data-direction', "ASC").find('i.glyphicon').removeClass('glyphicon-triangle-top').addClass('glyphicon-triangle-bottom');  
        }

  </script>

  <script type="text/javascript">
    $( document ).ready(function() {
      $(".dataExport").click(function() {
        var exportType = $(this).data('type');  
        $('#retTbl').tableExport({
          type : exportType, 
          select: true,  
          escape : 'false',
          ignoreColumn: [10]
        });   
      });
    });

  </script>

