<?php $this->load->view('/layouts/commanHeader'); ?>

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
                        <div class="header">
                            <h2>
                               Products Details 
                            </h2>
                            <h2>
                                <p align="right">
                                    <a href="<?php echo site_url('DeliverySlipController/blockedProducts/');?>">
                                        <button type="submit" class="btn bg-primary margin"><i class="material-icons">visibility</i>  Show Blocked Products </button>
                                    </a> 
                                </p>
                            </h2>
                           
                        </div>
                       <!--  <div class="body outer">
                            <div class="table-responsive inner"> -->
                                <div class="body outer">
                                    
                                    <div class="inner table-responsive">
                                <table id="prodTbl" class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100' >
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Product Code</th>
                                            <th>Product Name</th>
                                            <th>Product company</th>
                                            <th>MRP</th>
                                            <th>Pcs</th>
                                            <th>Box/Poly/Packet</th>
                                            <th>Case</th>
                                            <th class="noExport">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                       <tr>
                                          <th>S. No.</th>
                                          <th>Product Code</th>
                                          <th>Product Name</th>
                                          <th>Product company</th>
                                          <th>MRP</th>
                                          <th>Pcs</th>
                                          <th>Box/Poly/Packet</th>
                                          <th>Case</th>
                                          <th class="noExport">Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                  
                                      <?php
                                        $no=0;
                                        foreach ($prod as $data) 
                                          {
                                          $no++;
                                    ?>
                                        <tr>
                                              
                                            <td><?php echo $no; ?></td>
                                             <td class="fixed-side"><?php echo $data['productCode']; ?></td>
                                            <td class="fixed-side"><?php echo $data['name']; ?></td>
                                            <td class="fixed-side"><?php echo $data['company']; ?></td>
                                            <td class="fixed-side"><?php echo $data['mrp']; ?></td>
                                            <td class="fixed-side"><?php echo $data['unitOne']; ?></td>
                                            <td class="fixed-side"><?php echo $data['unitTwo']; ?></td>
                                            <td class="fixed-side"><?php echo $data['unitThree']; ?></td>

                                           <!--  <td class="fixed-side" align="right"><?php echo $data['price']; ?></td>
                                            <?php   
                                                $qty=$data['availQty'];
                                                $blqty=$data['blockQty'];
                                                $caseQty=round($data['availQty']/$data['boxQty']);
                                                $pcsQty=$data['availQty']%$data['boxQty'];
                                                
                                                $blcaseQty=round($data['blockQty']/$data['boxQty']);
                                                $blpcsQty=$data['blockQty']%$data['boxQty']
                                            ?>
                                            
                                            <?php if($qty<0){ ?>
                                                <td class="fixed-side"><?php echo $qty." Pcs"; ?></td>
                                            <?php }else if($caseQty>0 && $pcsQty>0){ ?>
                                                <td class="fixed-side"><?php echo $caseQty." Case, ".$pcsQty." Pcs"; ?></td>
                                            <?php }else if($caseQty>0){ ?>
                                                <td class="fixed-side"><?php echo $caseQty." Case"; ?></td>
                                            <?php }else if($pcsQty>0){ ?>
                                                <td class="fixed-side"><?php echo $pcsQty." Pcs"; ?></td>
                                            <?php }else{ ?>
                                                <td class="fixed-side"><?php echo $caseQty." Case, ".$pcsQty." Pcs"; ?></td>
                                            <?php } ?>
                                            
                                             <?php if($blqty<0){ ?>
                                                <td class="fixed-side"><?php echo $blqty." Pcs"; ?></td>
                                            <?php }else if($blcaseQty>0 && $blpcsQty>0){ ?>
                                                <td class="fixed-side"><?php echo $blcaseQty." Case, ".$blpcsQty." Pcs"; ?></td>
                                            <?php }else if($blcaseQty>0){ ?>
                                                <td class="fixed-side"><?php echo $blcaseQty." Case"; ?></td>
                                            <?php }else if($blpcsQty>0){ ?>
                                                <td class="fixed-side"><?php echo $blpcsQty." Pcs"; ?></td>
                                            <?php }else{ ?>
                                                <td class="fixed-side"><?php echo $blcaseQty." Case, ".$blpcsQty." Pcs"; ?></td>
                                            <?php } ?>
                                           <td>
                                                <button data-toggle="modal" data-name="<?php echo $data['name']; ?>" data-id="<?php echo $data['id']; ?>" data-target="#QtyModal" class="qtyAdd btn bg-primary margin"> <i class="material-icons"></i>Add Qty</button>
                                                
                                           </td> -->
                                           <td>
                                               <a class="prdLink" data-toggle="modal" data-target="#prodModal" data-id="<?php echo $data['id']; ?>" href="#">
                                                    <b>
                                                        <i class="material-icons" style="color: green;">edit</i> 
                                                    </b>
                                                </a> 
                                               <a id="deleted" href="<?php echo base_url().'index.php/ProductController/deactivateProduct/'.$data['id'];  ?>">
                                                    <b>
                                                        <i class="material-icons" style="color: red;">remove_circle</i> 
                                                    </b>
                                                </a>  
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
    </section>

<div class="container">
  <div class="modal fade" id="QtyModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
          
      </div>
    </div>
  </div>
</div>

<div class="container">
  <div class="modal fade" id="prodModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        
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
    $('.prdLink').click(function(){
        var id=$(this).attr('data-id');
       
        $.ajax({
            url : "<?php echo site_url('ProductController/loadProductDetails');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
              $('.modal-content').html(data);
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

