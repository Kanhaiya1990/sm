<?php $this->load->view('/layouts/commanHeader'); ?>

<script>
    $(document).ready(function() {
    $.fn.dataTable.ext.errMode = 'none';
    $('#retTbl').DataTable( {
        dom: 'Bfrtip',
        stateSave: true,
            buttons: [{
                    extend: 'pdf',
                    title: 'Blocked Retailers Details',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                },{
                    extend: 'excel',
                    title: 'Blocked Retailers Details',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                }, {
                    extend: 'csv',
                    title: 'Blocked Retailers Details',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                }
            ]
        
    } );
} );
</script>
        <!-- <section class="content"> -->
<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    
                    <div class="card">
                        <div class="header flex-div">
                            <h2>
                               Blocked Retailers
                            </h2>
                            <h2>
                                <p align="right">
                                    <a href="<?php echo site_url('RetailerController/');?>">
                                        <button type="submit" class="btn btnStyle m-t-15 waves-effect"><i class="material-icons">visibility</i>  Show Active Retailers </button>
                                    </a> 
                                </p>
                          </h2>
                            
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table id="retTbl" class="table table-bordered cust-tbl js-basic-example dataTable" data-page-length='10'>
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Retailer Code</th>
                                            <th>Name</th>
                                            <th class="noExport">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Retailer Code</th>
                                            <th>Name</th> 
                                            <th class="noExport">Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php
                                        $no=0;
                                        if(!empty($blockRetailer)){
                                            foreach($blockRetailer as $data) 
                                            {
                                           $no++; 
                                    ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td><?php echo $data['retailerCode']; ?></td>
                                            <td><?php echo $data['name']; ?></td>
                                            
                                            <td>
                                                
                                                <a id="deleted" href="<?php echo base_url().'index.php/RetailerController/activateRetailer/'.$data['id'];  ?>">
                                                    <b>
                                                        <i class="material-icons" style="color: green;">add_circle</i> 
                                                    </b>
                                                </a> 
                                                 <a id="deleted" 
                                                    onclick="deleted(<?php echo $data['id'];?>)" href='#'>
                                                    <b>
                                                        <i class="material-icons" style="color: red;">delete</i> 
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
            <!-- #END# Basic Examples -->  
        </div>
    </section>
    
    
    
    <div class="container">
  <div class="modal fade" id="retailerModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
      
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('/layouts/footerDataTable'); ?>
<script>
 $(document).ready(function(){
    $('.modalLink').click(function(){
        var id=$(this).attr('data-id');
       
        $.ajax({
            url : "<?php echo site_url('RetailerController/editRetailer');?>",
            method : "POST",
            data : {'id': id, },
            success: function(data){
              $('.modal-content').html(data);
            }
        });
    });
});
</script>

<script type="text/javascript">
    jQuery("#insRet").on("click",function(){
        var retailerName = $('#rtName').val();
        var route=$('#routeNames').val();
        var salesman=$('#salesmanNames').val();
        var rtId=$('#rtId').val();
        alert(retailerName+" "+route+" "+salesman+" "+rtId);
        die();
        if(retailerName==""){
            alert("Please enter Retailer Name");
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('RetailerController/update');?>",
                data:{"retailerName" : retailerName,"route":route,"salesman":salesman},
                success: function (data) {
                //   ('#recStatus').innerHTML=data
                    $('#recStatus1').html(data);
                    window.location.href="<?php echo base_url();?>index.php/DeliverySlipController";
                }  
            });
        }
    });
 </script>

<script>
function deleted(id)
{ 
  // alert(id);
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
        url: "<?php echo site_url('RetailerController/delete');?>",
        type: "post",
        data: {'id':id},
        success: function (response) {
         
          swal(response, {
            icon: "success",
          });
          var URL = "<?php echo site_url('RetailerController');?>";
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

