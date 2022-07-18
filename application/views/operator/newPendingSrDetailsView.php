<?php $this->load->view('/layouts/commanHeader'); ?>

<script>
function goBack() {
  window.history.back();
}
</script>

    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header flex-div">
                            <h2><?php echo $title; ?></h2>
							<button onclick="goBack();" class="btn btn-xs btnStyle margin"><i class="material-icons">keyboard_return</i></button></a>
                        </div>
                        <div class="body">
                            <div class="row">
                                <p align="right">
                                    <button type="button" id="insert-ins" class="btn btn-sm btnStyle m-t-15 waves-effect"> <i class="material-icons">save</i> 
                                              <span class="icon-name">Clear Selected</span>
                                    </button>
                                </p>
                            </div><br>

                            <div class="table-responsive">

                                <input type="hidden" id="dateForSr" value="<?php echo $date; ?>">
                                <table id="test" class="table table-bordered cust-tbl js-exportable dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th><input class="checkall"  type="checkbox" name="selValue" id="basic_checkbox"/>
                                                            <label for="basic_checkbox"></label></th>
                                            <th>No</th>
                                             <!-- <th>SR/FSR Date</th> -->
                                            <th>Allocation Number</th>
                                            <th>Bill No.</th>
                                            <th>Company</th>
                                            <th>Route</th>
                                            <th>Salesman</th>
                                            <th>Retailer Name</th>
                                            <th>Retailer Code</th>
                                            <th>Product Code</th>
                                            <th>Product Name</th>

                                            <th class="text-right">Quantity</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th>No</th>
                                            <!-- <th>SR/FSR Date</th> -->
                                            <th>Allocation Code</th>
                                            <th>Bill No.</th>
                                            <th>Company</th>
                                            <th>Route</th>
                                            <th>Salesman</th>
                                            <th>Retailer Name</th>
                                            <th>Retailer Code</th>
                                            <th>Product Code</th>
                                            <th>Product Name</th>
                                            <th class="text-right">Quantity</th>
                                        </tr>
                                    </tfoot>
                                    <tbody id="tbl_data">
                                        
                                <?php
                                    $no=0;
                                        if(!empty($srDetails)){
                                            $srNo=0;
                                            $flag=false;
                                            $billId="";
                                            foreach ($srDetails as $data) 
                                            {
                                                $n=0;$no=0; 
                                                $allocation_sr_id=$data['sr_id'];
                                                $no++; 

                                                $flag=false;
                                                $fsrStatus=$data['isFsrBill'];
                                                $srNo++; 
                                ?>
                                                    <tr>
                                                        <td>
                                                            <input class="checkhour"  type="checkbox" name="selValue" value="<?php echo $allocation_sr_id.':'.$data['sr_allocationId'].':'.$data['billId'].':'.'SR'.':OPN'; ?>" id="basic_checkbox_<?php echo $data['sr_id']; ?>" />
                                                            <label for="basic_checkbox_<?php echo $data['sr_id']; ?>"></label>
                                                        </td>
                                                        <td><?php echo $srNo; ?></td>
                                                        <!-- <td><?php echo date("d-M-Y", strtotime($data['srCreatedAt'])); ?></td> -->
                                                        <td><?php echo $data['alCode']; ?></td>
                                                         <td><?php echo $data['billNo']; ?></td>
                                                         <td><?php echo $data['compName']; ?></td>
                                                         <td><?php echo $data['routeName']; ?></td>
														 
                                                         <td><?php echo $data['salesman']; ?></td>
                                                         <td><?php echo $data['retailerName']; ?></td>
                                                         <td><?php echo $data['retailerCode']; ?></td>
                                                         <td><?php echo $data['prodCode']; ?></td>
                                                        <td><?php echo $data['prodName']; ?></td>
                                                        <td class="text-right"><?php echo $data['sr_qty']; ?></td>
                                                  
                                                   </tr>  
                                                <?php
                                            }
                                        }

                                        if(!empty($fsrDetails)){
                                            $srNo=0;
                                            $flag=false;
                                            $billId="";
                                            foreach ($fsrDetails as $data) 
                                            {
                                                $n=0;$no=0; 
                                                $allocation_sr_id=$data['sr_id'];
                                                $no++; 

                                                $flag=false;
                                                $fsrStatus=$data['isFsrBill'];

                                                if(($data['isFsrBill']==1)){
                                                    $billId=$data['billId'];

                                                    $srNo++;
                                         ?>
                                                    <tr>
                                                        <td>
                                                            <input class="checkhour"  type="checkbox" name="selValue" value="<?php echo $allocation_sr_id.':'.$data['sr_allocationId'].':'.$data['billId'].':'.'FSR'.':OPN'; ?>" id="basic_checkbox_<?php echo $data['billId']; ?>" />
                                                            <label for="basic_checkbox_<?php echo $data['billId']; ?>"></label>
                                                        </td>
                                                        <td><?php echo $srNo; ?></td>
                                                        <!-- <td><?php echo date("d-M-Y", strtotime($data['srCreatedAt'])); ?></td> -->
                                                        <td><?php echo $data['alCode']; ?></td>
                                                        <td><?php echo $data['billNo']; ?></td>
                                                        <td><?php echo $data['compName']; ?></td>
                                                        <td><?php echo $data['routeName']; ?></td>
                                                        <td><?php echo $data['salesman']; ?></td>
                                                        <td><?php echo $data['retailerName']; ?></td>
                                                        <td><?php echo $data['retailerCode']; ?></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-right">FSR</td>
                                                       
                                                   </tr>  
                                                <?php
                                                }
                                            }
                                        }

                                         if(!empty($officeSrDetails)){
                                             $srNo=0;
                                            $flag=false;
                                            foreach ($officeSrDetails as $data) 
                                            {
                                                $srNo++;
                                                 $n=0;$no=0; 
                                                $allocation_sr_id=$data['sr_id'];
                                                $no++; 

                                    ?>
                                                <tr>
                                                    <td>
                                                        <input class="checkhour"  type="checkbox" name="selValue" value="<?php echo $allocation_sr_id.':'.$data['sr_allocationId'].':'.$data['billId'].':'.'FSR'.':OFC'; ?>" id="basic_checkbox_<?php echo $data['billId']; ?>" />
                                                        <label for="basic_checkbox_<?php echo $data['billId']; ?>"></label>
                                                    </td>
                                                    <td><?php echo $srNo; ?></td>
                                                    <!-- <td><?php echo date("d-M-Y", strtotime($data['srCreatedAt'])); ?></td> -->
                                                    <td><?php echo $data['alCode']; ?></td>
                                                    <td><?php echo $data['billNo']; ?></td>
                                                    <td><?php echo $data['compName']; ?></td>
                                                    <td><?php echo $data['routeName']; ?></td>
                                                    <td><?php echo $data['salesman']; ?></td>
                                                    <td><?php echo $data['retailerName']; ?></td>
                                                    <td><?php echo $data['retailerCode']; ?></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-right">FSR</td>
                                                   
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

<?php $this->load->view('/layouts/footerDataTable'); ?>

<script type="text/javascript">
    jQuery("#insert-ins").on("click",function(){
        var selValue = [];
        $.each($("input[name='selValue']:checked"), function(){
                selValue.push($(this).val());
        });

        if(selValue.length>0 ){
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('operator/OperatorController/savePendingSr');?>",
                data:{selValue:selValue},
                success: function (data) {
                    $("input[type=checkbox]").each(function(){
                        $(this).attr('checked', false);
                    });  
                    alert("Selected Sale Returns are cleared.");
                    var date=$('#dateForSr').val();

                    window.location.href="<?php echo base_url();?>index.php/operator/OperatorController/pendingAllocationSrWithDate/"+date;
                }  
            });
        }else{
            alert('Please select Bills.');
        }
    });
</script>


<script type="text/javascript">
    jQuery("#insert-ins-fsr").on("click",function(){
        var selValue = [];
        $.each($("input[name='selValue']:checked"), function(){
                selValue.push($(this).val());
        });
        // alert(selValue);die();

        if(selValue.length>0 ){
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('operator/OperatorController/savePendingSr');?>",
                data:{selValue:selValue},
                success: function (data) {
                    $("input[type=checkbox]").each(function(){
                        $(this).attr('checked', false);
                    });  
                    alert("Selected Sale Returns are cleared.");
                    var date=$('#dateForSr').val();
                    window.location.href="<?php echo base_url();?>index.php/operator/OperatorController/pendingAllocationSrWithDate/"+date;
                }  
            });
        }else{
            alert('Please select Bills.');
        }
    });
</script>

<script type="text/javascript">
    function submitStatus(id){
        var allocationId=document.getElementById('allocationId').value;
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('operator/OperatorController/getPendingSr');?>",
            data:{"allocationSrId":id,"allocationId":allocationId},
            success: function (data) {
                $('#tbl_data').html(data);
                // window.location.reload(true);
            }  
        });
    }

     function submitFSRStatus(id){
        var allocationId=document.getElementById('allocationId').value;
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('operator/OperatorController/getPendingFSR');?>",
            data:{"billId":id,"allocationId":allocationId},
            success: function (data) {
                $('#tbl_data').html(data);
                 // window.location.reload(true);
            }  
        });
    }
</script>


<script type="text/javascript">
 var seen = {};
$('table tr').each(function() {
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
      $(".checkhour").prop("checked", !clicked);
      clicked = !clicked;
      this.innerHTML = clicked ? 'Deselect' : 'Select';
    });
</script>
