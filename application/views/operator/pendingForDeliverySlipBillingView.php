<?php $this->load->view('/layouts/commanHeader'); ?>

<script>
function goBack() {
  window.history.back();
}
</script>

<style type="text/css">
    .logo_prov {
        border-radius: 30px;
         border: 1px solid black;
        background: red;
        color: black;
        padding: 6px;
        width: 50px;
        height: 50px;
    }
</style>
        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header flex-div">
                            <h2>Pending Billing</h2>
                            <p align="right">
                               <button align="right" type="button" id="insert-ins" class="btn btn-xs btnStyle m-t-15 waves-effect"> <i class="material-icons">save</i><span class="icon-name">Save Selected</span></button>
                               <button onclick="goBack();" class="btn btn-xs m-t-15 btnStyle margin"><i class="material-icons">keyboard_return</i></button>
                            </p>
                            
                        </div>
                        
                        <div class="body">
                            <div class="row">
                            <div class="table-responsive">
                                <table id="test" class="table table-bordered cust-tbl js-exportable dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th><input class="checkall" type="checkbox" name="selValue" id="basic_checkbox"/><label for="basic_checkbox"></label></th>
                                            <th>Product Code</th>
                                            <th>Product Name</th>
                                            <th>MRP</th>
                                            <!-- <th>Cases</th> -->
                                            <!-- <th>Add In Company Software</th> -->
                                            <th>Reduce In Company Software</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th>Product Code</th>
                                            <th>Product Name</th>
                                            <th>MRP</th>
                                            <!-- <th>Cases</th> -->
                                            <!-- <th>Add In Company Software</th> -->
                                            <th>Reduce In Company Software</th>
                                        </tr>
                                    </tfoot>
                                    <tbody id="tbl_data">
                                        <?php
                                            $no=0;
                                            if(!empty($pendingForBilling)){
                                                foreach ($pendingForBilling as $data) 
                                                {
                                                         $n=0;$no=0; 
                                                         if($data['totatReducePendingBilling']<0){
                                        ?>
                                                    <tr>
                                                        <td>
                                                            <input class="checkhour" type="checkbox" name="selValue" value="<?php echo $data['productId']; ?>" id="basic_checkbox_<?php echo $data['productId']; ?>" />
                                                            <label for="basic_checkbox_<?php echo $data['productId']; ?>"></label>
                                                        </td>
                                                        <td><?php echo $data['productCode']; ?></td>
                                                        <td><?php echo $data['productName']; ?></td>
                                                        <td><?php echo $data['pmrp']; ?></td>
                                                        <!-- <td><?php echo ($data['totatReducePendingBilling']); ?></td> -->
                                                        <!-- <td><?php echo '0'; ?></td> -->
                                                        <td><?php echo ($data['totatReducePendingBilling']). ' Pcs'; ?></td>
                                                    </tr>  
                                        <?php
                                                    }
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
            <!-- #END# Basic Examples -->  
        </div>
    </section>

<?php $this->load->view('/layouts/footerDataTable'); ?>

  <script type="text/javascript">
    jQuery("#insert-ins").on("click",function(){
        var selValue = [];
        $.each($("input[name='selValue']:checked"), function(){
                selValue.push($(this).val());
        });
        // alert(selValue);die();

        if(selValue.length>0 ){
            var msj= confirm('Are you sure wants to submit.');
            if (msj == false) { 
              return false;
            } else {
                $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('DeliverySlipController/savePendingBilling');?>",
                    data:{selValue:selValue},
                    success: function (data) {
                        // alert(data);die();
                        $("input[type=checkbox]").each(function(){
                            $(this).attr('checked', false);
                        });      
                        window.location.href="<?php echo base_url();?>index.php/DeliverySlipController/pendingForBilling";
                    }  
                });
            }
        }else{
            alert('Please select Bills.');
        }
});
    
</script>



<script type="text/javascript">
 var seen = {};
$('$test tr').each(function() {
  var txt = $(this).find("td:not(:first)").text();
  
  if (seen[txt])
    $(this).remove();
  else
    seen[txt] = true;
});
</script>

<script type="text/javascript">
    var clicked = false;
    $(".checkall").on("click", function() {
        var sum = 0;
        $('.chkclass:checked').each(function() {
            sum += parseFloat($(this).closest('tr').find('.wagein').text());
        });
        console.log(sum);
      $(".checkhour").prop("checked", !clicked);
      clicked = !clicked;
      this.innerHTML = clicked ? 'Deselect' : 'Select';
    });
</script>
