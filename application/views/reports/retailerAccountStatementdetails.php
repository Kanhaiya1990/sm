

<table class="table table-bordered js-exportable dataTable cust-tbl" data-page-length='100'>
   <thead>
     <tr>  
                <th colspan="3">Bill No.  </th>
                <th colspan="3">Date</th>
                <th colspan="3">Retailer Name  </th>
                <th colspan="3">Retailer Code</th>
                <th colspan="3">Route Name  </th>
                <th colspan="3">NetAmount  </th>
                <th colspan="3">Received </th>
                <th colspan="3">Pending  </th>
                
     </tr>
   </thead>
 <tbody>
            <?php
                if(!empty($billInfo)){
                    foreach ($billInfo as $data) 
                    {
            ?>
                     <tr>
                        <td colspan="3" >
                        <a style="color:#555;" href="javascript:void()" class="bills" data-toggle="modal"  data-id="<?php echo $data['id']; ?>" data-target="#billModal"><?php echo $data['billNo']; ?></a>
                        </td>
                        <td colspan="3"><?php echo $data['date']; ?></td>
                        <td colspan="3"><?php echo $data['retailerName']; ?></td>
                        <td colspan="3"><?php echo $data['retailerCode']; ?></td>
                        <td colspan="3"><?php echo $data['routeName']; ?></td>
                        <td colspan="3"><?php echo $data['netAmount']; ?></td>
                        <td colspan="3"><?php echo $data['debit']; ?></td>
                        <td colspan="3"><?php echo $data['pendingAmt']; ?></td>
                    </tr>

            <?php
                    }
                } 
            ?>
        </tbody>
 </table>

 <div class="container">
  <div class="modal fade" id="billModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="text-right" style="margin: 10px;">
          <div class="modal-header"><h3>Bill Details List</h3>
          </div>
        </div>
          <div class="billdetails" id="billdetails">

          </div>
      </div>
    </div>
  </div>
</div>

<script>
 $(document).ready(function(){
    $('.bills').click(function(){
        var id=$(this).attr('data-id');
        //alert(id);
            $.ajax({
                url : "<?php echo site_url('reports/ReportController/showBillDetails');?>",
                method : "POST",
                data : {id: id},
                success: function(data){
                  $('.billdetails').html(data);

                }
            });
       // }
       
    });
});
</script>