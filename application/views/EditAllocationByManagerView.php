<?php $this->load->view('/layouts/commanHeader'); ?>

 <script   src="<?php echo base_url('assets/js/kp_js/jquery-1.12.1.js'); ?>"   integrity="sha256-VuhDpmsr9xiKwvTIHfYWCIQ84US9WqZsLfR4P7qF6O8="   crossorigin="anonymous"></script>
 <script src="<?php echo base_url('assets/js/kp_js/jquery.min.js'); ?>"></script>
 <script src="<?php echo base_url('assets/js/pages/ui/tooltips-popovers.js');?>"></script>

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
<!--<section class="content">-->
<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            
              <!-- Basic Examples -->
            <div class="row clearfix" id="page">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Allocation By Manager
                            </h2>
                        </div>
                        <div class="body">
                              <div class="row">
                                <table class="table table-bordered table-striped table-hover" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Bill Count</th>
                                            <th>Bill Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="calTotalAmount">
                                        <tr>
                                            <td id="cntBillFnc">0</td>
                                            <td id="totBillCnt">0</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                              <div class="row">
                                <div class="col-md-2">
                                    Allocation : 
                                    <label id="allocation"><?php echo $allocations[0]['allocationCode']?>
                                    </label><br />
                                  <input type="hidden" id="alID" autocomplete="off" value="<?php echo $alID;?>" class="form-control" ><br>

                                  <label>Division Name:</label>
                                  <select id="cmpName" class="form-control" name="cmpName">
                                                  
                                                      <option value="<?php echo $allocations[0]['company']?>"><?php echo $allocations[0]['company']?></option>
                                  </select><br><br>
                                  <?php if($change_details==""){  ?>
                                   <label>Employee Name:</label>
                                    <input type="text" id="eName" autocomplete="off" list="empList" name="eName[]" class="form-control" placeholder="Enter Emp Name"><br>

                                     <button type="button" id="eAdd" class="btn btn-primary margin btn-sm"> Add </button>
                                                          
                                    <br><br>    

                                   <label> Route:</label>
                                     
                                    <input type="text" id="name" autocomplete="off" list="routeN" name="name" class="form-control" placeholder="Enter Route" required>   
                                    <datalist id="routeN">
                                        <?php
                                            foreach($routeNames as $data){
                                                $name=$data['name'];
                                        ?>   
                                        <option value="<?php echo $name;?>"/>
                                        <?php    
                                            }
                                        ?>
                                    </datalist>
                                    <br>
                                     <button type="button" id="rAdd" class="btn btn-primary margin btn-sm"> Add </button>
                                    <?php } ?>   
                                   
                                </div>
                                <div class="col-md-3">

                                <datalist id="cmpList">
                                    <?php
                                        foreach($company as $data){
                                            $name=$data['name'];
                                    ?>   
                                    <option value="<?php echo $name;?>"/>
                                    <?php    
                                        }
                                    ?>
                                </datalist>
                                                  
                                <datalist id="empList">
                                        <?php
                                            foreach($employeeNames as $data){
                                                $name=$data['name'];
                                        ?>   
                                        <option value="<?php echo $name;?>"/>
                                        <?php    
                                            }
                                        ?>
                                </datalist>
                                        
                                        <br><br>
                                       <label> Reference:</label>
                                     <input type="text" id="reference" name="reference" class="form-control" placeholder="Enter Reference" required> 
                                  
                                    <br>
                                    <label>Selected Emp</label>
                                    <ul class="list-group" id="list" multiple="multiple">
                                        <?php
                                        $emp1='';
                                         $emp2='';
                                          $emp3='';
                                           $emp4='';
                                            if(!empty($allocations[0]['fieldStaffCode1'])){
                                                $emp1= $this->AllocationByManagerModel->getEmpName('employee',$allocations[0]['fieldStaffCode1']);
                                                $emp1=$emp1[0]['name'];
                                                ?>
                                                 <li class="list-group-item list-group-item-action"><?php echo $emp1;?>
                                                 <?php
                                            }
                                             if(!empty($allocations[0]['fieldStaffCode2'])){
                                                $emp2= $this->AllocationByManagerModel->getEmpName('employee',$allocations[0]['fieldStaffCode2']);
                                                $emp2=$emp2[0]['name'];
                                                ?>
                                                  <li class="list-group-item list-group-item-action"><?php echo $emp2;?>
                                                  <?php
                                            }
                                             if(!empty($allocations[0]['fieldStaffCode3'])){
                                                $emp3= $this->AllocationByManagerModel->getEmpName('employee',$allocations[0]['fieldStaffCode3']);
                                                $emp3=$emp3[0]['name'];
                                                ?>
                                                  <li class="list-group-item list-group-item-action"><?php echo $emp3;?>
                                                  <?php
                                            }
                                             if(!empty($allocations[0]['fieldStaffCode4'])){
                                                $emp4= $this->AllocationByManagerModel->getEmpName('employee',$allocations[0]['fieldStaffCode1']);
                                                $emp4=$emp4[0]['name'];
                                                ?>
                                                 <li class="list-group-item list-group-item-action"><?php echo $emp4;?>
                                                 <?php
                                            }
                                            
                                        ?>
                                    </ul>
                                    <br>
                                    <label>Selected Route</label>
                                    <ul class="list-group" id="rlist" multiple="multiple">
                                        <?php
                                            $rtName=explode(",",rtrim($allocations[0]['routId'],','));
                                        for($i=0;$i<count($rtName);$i++){
                                         $routes=$this->AllocationByManagerModel->getRouteNameById($rtName[$i]);
                                       
                                            if(!empty($routes)){
                                                $routeName=$routes[0]['name'];
                                                ?>
                                                <li class="list-group-item list-group-item-action"><?php echo $routeName;?>
                                                <?php
                                            }
                                        }

                                        ?>
                                    </ul>
                                </div>
                            
                                <div class="col-md-7">

                                    <!-- CURRENT SUPPLY-->
                                    <div class="col-md-5 table-responsive">
                                         <?php echo validation_errors(); ?>
                                        <?php echo form_open_multipart('AllocationByManagerController/getCurrentBills') ?>
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-xs-center" colspan="4"><center>Current Supply</center></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-xs-right">
                                                        <label>From :</label>
                                                        <input type="text" name="from" id="from" list="frmBill" autocomplete="off" placeholder="Enter Bill No" class="form-control">
                                                        <datalist id="frmBill">
                                                             <?php foreach($billNos as $cs){
                                                            $billNo=$cs['billNo']." : ".$cs['retailerName'];
                                                        ?>
                                                            <option value="<?php echo $billNo;?>"/>
                                                        <?php } ?>
                                                        </datalist>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td class="text-xs-right">
                                                        <label>To :  </label><br />
                                                        <input type="text" name="to" id="to" list="toBill"autocomplete="off" placeholder="Enter Bill No" class="form-control">
                                                        <datalist id="toBill">
                                                             <?php foreach($billNos as $cs){
                                                            $billNo=$cs['billNo']." : ".$cs['retailerName'];
                                                        ?>
                                                            <option value="<?php echo $billNo;?>"/>
                                                        <?php } ?>
                                                        </datalist>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                     <td class="text-xs-right">
                                                        <button type="button" id="insert-more" class="btn btn-primary margin btn-sm"> Add Current Bills </button><br />                                                      
                                                    </td>
                                                </tr>
                                                <tr>
                                                     <td class="text-xs-right">
                                                        <label>Route Bills :  </label><br />
                                                        <input type="text" name="rtBillNo" id="rtBillNo" list="routeBi" autocomplete="off" placeholder="" class="form-control">
                                                        <datalist id="routeBi">
                                                            <?php
                                                                foreach($routeNames as $data){
                                                                    $name=$data['name'];
                                                            ?>   
                                                            <option value="<?php echo $name;?>"/>
                                                            <?php    
                                                                }
                                                            ?>
                                                        </datalist>
                                                    </td>
                                                </tr>
                                            
                                                <tr>
                                                     <td class="text-xs">
                                                        <button type="button" id="shw_routeBills" class="btn btn-primary margin btn-sm">Show</button>
                                                         <button type="button" onclick="clearPast();" id="rmv_routeBills" class="btn btn-danger margin btn-sm">Cancel</button>
                                                    </td>
                                                </tr>
                                             
                                            </tbody>
                                        </table>
                                         <?php echo form_close(); ?>
                                    </div>
                                    <!-- PAST BILLS-->
                                    <div class="col-md-7 table-responsive">
                                        <!--  -->
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-xs-center" colspan="5"><center>Additional Bills</center></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="2">Current Supply :  </td>
                                                        <td colspan="2">
                                                        <input type="text" name="addBill" id="addBill" list="adddBill"autocomplete="off" placeholder="Enter Bill No" class="form-control">
                                                        <datalist id="adddBill">
                                                        <?php foreach($billNos as $cs){
                                                            $billNo=$cs['billNo']." : ".$cs['retailerName'];
                                                        ?>
                                                            <option value="<?php echo $billNo;?>"/>
                                                        <?php } ?>
                                                        </datalist>
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <button type="button" id="insert-more1" class="btn btn-primary margin btn-sm"> Add </button><br />                                                      
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">Past Bills</td>
                                                    <td colspan="2">

                                                        <input type="text" id="pName" list="pstBill" autocomplete="off" name="pName" class="form-control" placeholder="Enter Bill No">  
                                                        <datalist id="pstBill">
                                                            <?php foreach($pastBillNos as $cs){
                                                            $billNo=$cs['billNo']." : ".$cs['retailerName'];
                                                        ?>
                                                            <option value="<?php echo $billNo;?>"/>
                                                        <?php } ?>
                                                        </datalist>      
                                                      
                                                    </td>
                                                    <td>
                                                         <button id="insert-past" class="btn btn-primary margin btn-sm"> Add </button>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td colspan="2">Delivery Challans</td>
                                                    <td colspan="2">

                                                        <input type="text" id="delBillNo" list="delBill" autocomplete="off" name="delBillNo" class="form-control" placeholder="Enter Bill No">  
                                                        <datalist id="delBill">
                                                             <?php foreach($deliverySlip as $cs){
                                                            $billNo=$cs['billNo']."-".$cs['retailerName'];
                                                        ?>
                                                            <option value="<?php echo $billNo;?>"/>
                                                        <?php } ?>
                                                        </datalist>      
                                                      
                                                    </td>
                                                     <td>
                                                         <button type="button" id="insert-delivery" class="btn btn-primary margin btn-sm"> Add </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                 <td colspan="5">
                                                     <label>Selected Routes</label>
                                                    <ul class="list-group" id="route_list" multiple="multiple"></ul>
                                                 </td>
                                                
                                               
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                        
                                    </div>
                                   
                                </div>
                                </div>
                            <div class="row">                      
                                <div class="row m-t-20">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered" id="tbl">
                                                <tr class="head">
                                                    <td colspan="12" style="background-color: whitesmoke;"><center><b>Current Supply Bills</b></center></td>
                                                </tr>
                                                <tr class="gray">
                                                    <th>S. No.</th>
                                                    <th>Bill No.</th>
                                                    <th>Date</th>
                                                    <th>Retailer Name</th>
                                                    <th>Amount</th>
                                                    <th>Sale Return</th>
                                                    <th>Past Coll.</th>
                                                    <th>CD</th>
                                                    <th>Pending Amount</th>
                                                    <th>Today's Coll.</th>
                                                    <th>Action</th>
                                                </tr>
                                                <tbody id="result_data">
                            <?php
                                        $no=0;
                                         if(!empty($current)){
                                        foreach ($current as $data) 
                                          {
                                            $provBillCount=$this->AllocationByManagerModel->getSumRowCount($data['id']); 

                                           $no++; 
                                    ?>
                                        <tr>
                                            <td><?php echo $no.' '; 
                                              if($data['creditNoteRenewal']>0){ echo '<span class="logo_prov">CN</span>'; }
                                            if($provBillCount[0]['recBill']>0){ echo '<span class="logo_prov">RB</span>'; }
                                            if($provBillCount[0]['lostBill']>0){ echo '<span class="logo_prov">LB</span>'; }
                                            if($provBillCount[0]['lostCheque']>0){ echo '<span class="logo_prov">LC</span>'; }
                                            if($provBillCount[0]['lostNeft']>0){ echo '<span class="logo_prov">PN</span>'; }
                                              
                                            ?></td>
                                        
                                            <td>
                                                <?php echo $data['billNo']; ?>
                                            </td>
                                             <?php
                                                $dt=date_create($data['date']);
                                                $date = date_format($dt,'d-M-Y');
                                            ?>
                                            <td><?php echo $date; ?></td>
                                            <td><?php echo $data['retailerName']; ?></td>
                                            <td class="text-right"><?php echo number_format($data['netAmount']); ?></td>
                                            <td class="text-right"><?php echo number_format($data['SRAmt'])?></td>
                                             <td class="text-right"><?php echo number_format($data['receivedAmt']+$data['debit']+$data['officeAdjustmentBillAmount']+$data['otherAdjustment']);?></td>
                                            
                                            <td class="text-right"><?php echo number_format($data['cd']); ?></td>
                                            <td class="text-right" id="loop"><?php echo number_format($data['pendingAmt']); ?></td>
                                            <td></td>
                                            <td> 
                                              <a>
                                              <button onclick="deleteMe(this,'<?php echo $data['id'];?>','<?php echo $alID; ?>');" class="btn btn-xs btn-primary waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
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
                                    
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            
                                            <table class="table table-striped table-bordered">
                                            <tr class="head">
                                                <td colspan="12"  style="background-color: whitesmoke;"><center><b>Past Bills</b></center></td>
                                            </tr>
                                            <tr class="gray">
                                                <th>S. No.</th>
                                                <th>Bill No.</th>
                                                <th>Date</th>
                                                <th>Retailer Name</th>
                                                <th>Amount</th>
                                                <th>Sale Return</th>
                                                <th>Past Collection</th>
                                                <!-- <th>USR</th> -->
                                                <th>CD</th>
                                                <th>Pending Amount</th>
                                                <th>Today's Collection</th>
                                                <th>Action</th>
                                            </tr>
                                            <tbody id="result_past">
                            <?php
                                        $no=0;
                                        if(!empty($pass)){
                                        foreach ($pass as $data) 
                                          {
                                           $no++;

                                           $provBillCount=$this->AllocationByManagerModel->getSumRowCount($data['id']); 
                                           $bouncedBill=$this->AllocationByManagerModel->checkBouncedBill('billpayments',$data['id']);
                                    ?>
                                        <tr>
                                            <td><?php echo $no.' '; 
                                              if($data['creditNoteRenewal']>0){ echo '<span class="logo_prov">CN</span>'; }
                                              if($provBillCount[0]['recBill']>0){ echo '<span class="logo_prov">RB</span>'; }
                                              if($provBillCount[0]['lostBill']>0){ echo '<span class="logo_prov">LB</span>'; }
                                              if($provBillCount[0]['lostCheque']>0){ echo '<span class="logo_prov">LC</span>'; }
                                              if($provBillCount[0]['lostNeft']>0){ echo '<span class="logo_prov">PN</span>'; }
                                               if($data['chequePenalty']>0){ ?>
                                                 <span class="logo_prov"><?php echo 'B'.intval($data['chequePenalty']); ?></span>
                                            <?php  }
                                              ?></td>
                                        
                                            <td>
                                                <?php echo $data['billNo']; ?>
                                            </td>
                                             <?php
                                                $dt=date_create($data['date']);
                                                $date = date_format($dt,'d-M-Y');
                                            ?>
                                            <td><?php echo $date; ?></td>
                                            <td><?php echo $data['retailerName']; ?></td>
                                            <td class="text-right"><?php echo number_format($data['netAmount']); ?></td>
                                            <td class="text-right"><?php echo number_format($data['SRAmt']); ?></td>
                                            <td class="text-right"><?php echo number_format($data['receivedAmt']+$data['debit']+$data['officeAdjustmentBillAmount']+$data['otherAdjustment']); ?></td>
                                            <!-- <td></td> -->
                                           <td class="text-right"><?php echo number_format($data['cd']); ?></td>
                                            <td class="text-right" id="looop"><?php echo number_format($data['pendingAmt']); ?></td>
                                            <td></td>
                                            <td> 
                                              <a>
                                              <button onclick="deleteMe(this,'<?php echo $data['id'];?>','<?php echo $alID;?>');" class="btn btn-xs btn-primary waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
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
                                    
                                  
                                   <div class="col-md-12">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-8">
                                        
                                        <p id="ins"></p>
                                        <p>
                                        <?php 
                                            if(isset($allocations)){
                                                if($allocations[0]['fsStatus'] === '0'){
                                                   

                                        ?>
                                            <button type="button" id="update-ins" class="btn btn-primary m-t-15 waves-effect">
                                                  <i class="material-icons">sync</i> 
                                                  <span class="icon-name"> Save & Confirm</span>
                                            </button>
                                            <a href="<?php echo site_url('AllocationByManagerController/openAllocations');?>">
                                                    <button type="button" class="btn btn-danger m-t-15 waves-effect">
                                                        <i class="material-icons">cancel</i> 
                                                        <span class="icon-name"> Close </span>
                                                    </button>
                                            </a> 

                                            <?php  if($transactionCount ==0){ ?>
                                                <a href="<?php echo site_url('AllocationByManagerController/cancelTotalAllocation/'.$allocations[0]['id']);?>">
                                                    <button type="button" class="btn btn-danger m-t-15 waves-effect">
                                                        <i class="material-icons">cancel</i> 
                                                        <span class="icon-name"> Cancel Allocation </span>
                                                    </button>
                                                </a> 
                                            <?php   } else { ?>
                                                    <button type="button" id="cancelBillRemark" class="btn btn-danger m-t-15 waves-effect">
                                                        <i class="material-icons">cancel</i> 
                                                        <span class="icon-name"> Cancel Allocation </span>
                                                    </button>
                                            <?php
                                                    }
                                                }
                                            }    
                                            ?>  
                                        </p>
                                        
                                    </div>
                                    <!-- <div class="col-md-2"></div> -->
                                  </div>
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


<script>
function deleted()
{ 
 swal({
  title: "Are you sure?",
  text: "You will not be able to recover this imaginary file!",
  type: "warning",
  showCancelButton: true,
  confirmButtonClass: "btn-danger",
  confirmButtonText: "Yes, delete it!",
  cancelButtonText: "No, cancel plx!",
  closeOnConfirm: false,
  closeOnCancel: false
},
function(isConfirm) {
  if (isConfirm) {
    swal("Deleted!", "Your imaginary file has been deleted.", "success");
  } else {
    swal("Cancelled", "Your imaginary file is safe :)", "error");
  }
});
}
</script>
</section>

 <script type="text/javascript">
        function clearPast(){
                var routeName = $('#rtBillNo').val();
                if(routeName==""){
                    alert("Please enter Route Name");
                }else{
                     $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('AllocationByManagerController/clearAllBills');?>",
                        data:{"routeName" : routeName},
                        success: function (data) {
                          $('#result_past').html(data);
                        },
                        beforeSend: function(){
                            $('.comman-ajax-loader').css("visibility", "visible");
                        },
                        complete: function(){
                            $('.comman-ajax-loader').css("visibility", "hidden");
                        },
                        error: function(jqXHR, exception) {
                            alert("Something Went Wrong, Please Try Again...!");
                        }   
                    });
                   $('#rtBillNo').val('');   
                }
        }
 </script>
 
<script>
    $('a.removebutton').on('click',function() {
    alert("Are you sure? You Want to Delete This Row");
  $(this).closest( 'tr').remove();
  return false;
});
</script>

<script type="text/javascript">
    function removeMe(that,id) {
        var rmId=id;
        $(that).closest('tr').remove();

         $.ajax({
                url: "<?php echo site_url('AllocationByManagerController/removeBillIdFromSession');?>",
                type: "post",
                data:{"rmId" : rmId},
                success: function (response) { 
                  // alert(response);
                },
                beforeSend: function(){
                    $('.comman-ajax-loader').css("visibility", "visible");
                },
                complete: function(){
                    $('.comman-ajax-loader').css("visibility", "hidden");
                },
                error: function(jqXHR, exception) {
                    alert("Something Went Wrong, Please Try Again...!");
                } 
         });
    }

    function deleteMe(that,id,allocationId) {
        var rmId=id;
         var allocationId=allocationId;
         // alert(rmId+' '+allocationId);die();
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('AllocationByManagerController/deleteBillIdFromSession');?>",
                data:{"rmId" : rmId,"allocationId" : allocationId},
                success: function (data) {
                  // alert(data);die();
                  $(that).closest('tr').remove();
                },
                beforeSend: function(){
                    $('.comman-ajax-loader').css("visibility", "visible");
                },
                complete: function(){
                    $('.comman-ajax-loader').css("visibility", "hidden");
                },
                error: function(jqXHR, exception) {
                    alert("Something Went Wrong, Please Try Again...!");
                }   
        });
    }
</script>

<script type="text/javascript"></script>

<script>
    (function(){

      var todo = document.querySelector( '#list' ),
          add = document.querySelector( '#eAdd' ),
          eName = document.querySelector( '#eName' );
        
      add.addEventListener('click', function( ev ) {
            var text = eName.value;
            if ( text.trim() != '' ) {
              todo.innerHTML += '<li class="list-group-item list-group-item-action">' + text + '<button  style="float: right;" onclick="Delete(this);"><i class="fa fa-close"></i></button> </li>';
              eName.value = '';
            }
            
        ev.preventDefault();
      }, false);

    })();
      function Delete(currentEl){
      currentEl.parentNode.parentNode.removeChild(currentEl.parentNode);
      }
</script>

<script>
    (function(){

      var todo = document.querySelector( '#rlist' ),
          add = document.querySelector( '#rAdd' ),
          eName = document.querySelector( '#name' );
        
      add.addEventListener('click', function( ev ) {
            var text = eName.value;
            if ( text.trim() != '' ) {
              todo.innerHTML += '<li class="list-group-item list-group-item-action" id="'+text+'">' + text + '<button  style="float: right;" onclick="Delete(this);"><i class="fa fa-close"></i></button> </li>';
              eName.value = '';
        }
        ev.preventDefault();
      }, false);

    })();
      function Delete(currentEl){
      currentEl.parentNode.parentNode.removeChild(currentEl.parentNode);
      }
</script>

<script>
function changeStatusForCurrentBills(id)
{ 
    swal({
      title: 'Select Status',
      input: 'select',
      inputOptions: {
        'Cancelled': 'Cancelled',
        'Returned': 'Returned',
        'Delivered': 'Delivered',
        'Fully Settled': 'Fully Settled'
      },
      inputPlaceholder: 'Select Status',
      showCancelButton: true,
      inputValidator: function (value) {
          return new Promise(function (resolve, reject) {
              if (value !== '') {
                resolve();
              } else {
                reject('You need to select a Status');
              }
         });
    }
    }).then(function (result) {
        var from = $('#from').val();
        var to = $('#to').val();
        if (result.value) {
            $.ajax({
                url: "<?php echo site_url('AllocationByManagerController/updateStatusForCurrentBills');?>",
                type: "post",
                data:{"id" : id , "status" : result.value,"from" : from,"to" :to},
                success: function (response) {
                    $('#result_data').html(response);  
                },
                beforeSend: function(){
                    $('.comman-ajax-loader').css("visibility", "visible");
                },
                complete: function(){
                    $('.comman-ajax-loader').css("visibility", "hidden");
                },
                error: function(jqXHR, exception) {
                    alert("Something Went Wrong, Please Try Again...!");
                } 
            });
        }

    });
}

</script>

<script>
function changeStatusForPastBills(id)
{ 
    swal({
      title: 'Select Status',
      input: 'select',
      inputOptions: {
        'Cancelled': 'Cancelled',
        'Returned': 'Returned',
        'Delivered': 'Delivered',
        'Fully Settled': 'Fully Settled'
      },
      inputPlaceholder: 'Select Status',
      showCancelButton: true,
      inputValidator: function (value) {
          return new Promise(function (resolve, reject) {
              if (value !== '') {
                resolve();
              } else {
                reject('You need to select a Status');
              }
         });
    }
    }).then(function (result) {
        var pName = $('#pName').val();
        if (result.value) {
            $.ajax({
                url: "<?php echo site_url('AllocationByManagerController/updateStatusForPastBills');?>",
                type: "post",
                data:{"id" : id , "status" : result.value,"pName" : pName},
                success: function (response) {
                    $('#result_past').html(response);  
                },
                beforeSend: function(){
                    $('.comman-ajax-loader').css("visibility", "visible");
                },
                complete: function(){
                    $('.comman-ajax-loader').css("visibility", "hidden");
                },
                error: function(jqXHR, exception) {
                    alert("Something Went Wrong, Please Try Again...!");
                } 
            });
        }
    });
}

</script>

<script type="text/javascript">
    $(document).on('change','#cmpName',function(){
        var cmpName = $('#cmpName').val();
        if(cmpName==""){
            alert("Please enter cmpName");
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('AllocationByManagerController/CompCurrentBills');?>",
                data:{"cmpName" : cmpName},
                success: function (data) {
                  $('#frmBill').html(data);
                  $('#toBill').html(data);
                  $('#addBill').html(data);
                },
                beforeSend: function(){
                    $('.comman-ajax-loader').css("visibility", "visible");
                },
                complete: function(){
                    $('.comman-ajax-loader').css("visibility", "hidden");
                },
                error: function(jqXHR, exception) {
                    alert("Something Went Wrong, Please Try Again...!");
                }   
            });
        }
});
</script>
<script type="text/javascript">
    $(document).on('change','#cmpName',function(){
        var cmpName = $('#cmpName').val();
        if(cmpName==""){
            alert("Please enter cmpName");
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('AllocationByManagerController/CompPastBills');?>",
                data:{"cmpName" : cmpName},
                success: function (data) {
                  $('#pstBill').html(data);
                },
                beforeSend: function(){
                    $('.comman-ajax-loader').css("visibility", "visible");
                },
                complete: function(){
                    $('.comman-ajax-loader').css("visibility", "hidden");
                },
                error: function(jqXHR, exception) {
                    alert("Something Went Wrong, Please Try Again...!");
                }   
            });
        }
});
</script>
<script type="text/javascript">
    $(document).on('change','#cmpName',function(){
        var cmpName = $('#cmpName').val();
        if(cmpName==""){
            alert("Please enter cmpName");
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('AllocationByManagerController/CompChequeBills');?>",
                data:{"cmpName" : cmpName},
                success: function (data) {
                  $('#chbill').html(data);
                },
                beforeSend: function(){
                    $('.comman-ajax-loader').css("visibility", "visible");
                },
                complete: function(){
                    $('.comman-ajax-loader').css("visibility", "hidden");
                },
                error: function(jqXHR, exception) {
                    alert("Something Went Wrong, Please Try Again...!");
                }   
            });
        }
});
</script>

<script type="text/javascript">
    $(document).on('change','#cmpName',function(){
        var cmpName = $('#cmpName').val();
        if(cmpName==""){
            alert("Please enter cmpName");
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('AllocationByManagerController/CompDeliveryBills');?>",
                data:{"cmpName" : cmpName},
                success: function (data) {
                  $('#delBill').html(data);
                },
                beforeSend: function(){
                    $('.comman-ajax-loader').css("visibility", "visible");
                },
                complete: function(){
                    $('.comman-ajax-loader').css("visibility", "hidden");
                },
                error: function(jqXHR, exception) {
                    alert("Something Went Wrong, Please Try Again...!");
                }   
            });
        }
});
</script>

<script type="text/javascript">
    $(document).on('click','#insert-more',function(){
        var from = $('#from').val();
        var to = $('#to').val();
        if($.trim(from) == "" || $.trim(to) ==""){
            alert("Please enter From/To BillNo");
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('AllocationByManagerController/getCurrentBills');?>",
                data:{"from" : from , "to" : to},
                success: function (data) {
                  $('#result_data').html(data);
                },
                beforeSend: function(){
                    $('.comman-ajax-loader').css("visibility", "visible");
                },
                complete: function(){
                    $('.comman-ajax-loader').css("visibility", "hidden");
                },
                error: function(jqXHR, exception) {
                    alert("Something Went Wrong, Please Try Again...!");
                }  
            });
                $('#from').val('');
                $('#to').val('');
                calfunction();
        }
});
</script>

<script type="text/javascript">
    $(document).on('keyup','#to',function(e){
        var key=e.which;
        if(key==13){
            var from = $('#from').val();
            var to = $('#to').val();
            if($.trim(from) == "" || $.trim(to) ==""){
                alert("Please enter From/To BillNo");
                $('#to').val('');
                $('#from').focus();
            }else{
                $.ajax({
                type: "POST",
                url:"<?php echo site_url('AllocationByManagerController/getCurrentBills');?>",
                    data:{"from" : from , "to" : to},
                    success: function (data) {
                      $('#result_data').html(data);
                    },
                    beforeSend: function(){
                        $('.comman-ajax-loader').css("visibility", "visible");
                    },
                    complete: function(){
                        $('.comman-ajax-loader').css("visibility", "hidden");
                    },
                    error: function(jqXHR, exception) {
                        alert("Something Went Wrong, Please Try Again...!");
                    }  
                });
                $('#from').val('');
                $('#to').val('');
                calfunction();
            }
        }
    });
</script>

<script type="text/javascript">
    $(document).on('keyup','#addBill',function(e){
        var key=e.which;
        if(key==13){
            var addBill = $('#addBill').val();
            if($.trim(addBill)==""){
                alert("Please enter BillNo");
            }else{
                 $.ajax({
                type: "POST",
                url:"<?php echo site_url('AllocationByManagerController/getCurrentBillsWithAdditions');?>",
                    data:{"addBill" : addBill},
                    success: function (data) {
                      $('#result_data').html(data);
                    },
                    beforeSend: function(){
                        $('.comman-ajax-loader').css("visibility", "visible");
                    },
                    complete: function(){
                        $('.comman-ajax-loader').css("visibility", "hidden");
                    },
                    error: function(jqXHR, exception) {
                        alert("Something Went Wrong, Please Try Again...!");
                    }   
                });
                    $('#addBill').val('');
                    calfunction();
            }
        }
});
</script>

<script type="text/javascript">
    $(document).on('click','#insert-more1',function(){
        var addBill = $('#addBill').val();
        if($.trim(addBill)==""){
            alert("Please enter BillNo");
        }else{
             $.ajax({
            type: "POST",
            url:"<?php echo site_url('AllocationByManagerController/getCurrentBillsWithAdditions');?>",
                data:{"addBill" : addBill},
                success: function (data) {
                  $('#result_data').html(data);
                },
                beforeSend: function(){
                    $('.comman-ajax-loader').css("visibility", "visible");
                },
                complete: function(){
                    $('.comman-ajax-loader').css("visibility", "hidden");
                },
                error: function(jqXHR, exception) {
                    alert("Something Went Wrong, Please Try Again...!");
                }   
            });
                $('#addBill').val('');
                calfunction();
        }
});
</script>

<script type="text/javascript">
  $(document).on('click','#insert-past',function(){
        var pName = $('#pName').val();
        var routeName = $('#name').val();
        if($.trim(pName)==""){
            alert("Enter Past BillNo");
        }else{
             $.ajax({
            type: "POST",
            url:"<?php echo site_url('AllocationByManagerController/getPastBills');?>",
                data:{"pName" : pName,"routeName" : routeName},
                success: function (data) {
                  $('#result_past').html(data);
                },
                beforeSend: function(){
                    $('.comman-ajax-loader').css("visibility", "visible");
                },
                complete: function(){
                    $('.comman-ajax-loader').css("visibility", "hidden");
                },
                error: function(jqXHR, exception) {
                    alert("Something Went Wrong, Please Try Again...!");
                } 
            });
                $('#pName').val('');
                calfunction();
        }
});
</script>

<script type="text/javascript">
    $(document).on('keyup','#pName',function(e){
        var key=e.which;
        if(key==13){
            var pName = $('#pName').val();
            var routeName = $('#name').val();
            if($.trim(pName)==""){
                alert("Enter Past BillNo");
            }else{
                 $.ajax({
                type: "POST",
                url:"<?php echo site_url('AllocationByManagerController/getPastBills');?>",
                    data:{"pName" : pName,"routeName" : routeName},
                    success: function (data) {
                        // alert(data);
                      $('#result_past').html(data);
                    },
                    beforeSend: function(){
                        $('.comman-ajax-loader').css("visibility", "visible");
                    },
                    complete: function(){
                        $('.comman-ajax-loader').css("visibility", "hidden");
                    },
                    error: function(jqXHR, exception) {
                        alert("Something Went Wrong, Please Try Again...!");
                    }   
                });
                    $('#pName').val('');
                    calfunction();
            }
        }
});
</script>

<script type="text/javascript">
     $(document).on('click','#insert-delivery',function(){
        var delBill = $('#delBillNo').val();
        // var routeName = $('#name').val(); alert(delBill+' '+routeName);die();
        var routeName = "";
        if($.trim(delBill)==""){
            alert("Please enter DeliverySlip BillNo");
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('AllocationByManagerController/getDeliverySlipBills');?>",
                data:{"delBill" : delBill,"routeName" : routeName},
                success: function (data) {
                  $('#result_data').html(data);
                },
                beforeSend: function(){
                    $('.comman-ajax-loader').css("visibility", "visible");
                },
                complete: function(){
                    $('.comman-ajax-loader').css("visibility", "hidden");
                },
                error: function(jqXHR, exception) {
                    alert("Something Went Wrong, Please Try Again...!");
                }   
            });
                $('#delBillNo').val('');
                calfunction();
        }
});
</script>

<script type="text/javascript">
    $(document).on('keyup','#delBillNo',function(e){
        var key=e.which;
        if(key==13){
            var delBill = $('#delBillNo').val();
            var routeName = $('#name').val();
            
            if($.trim(delBill)==""){
                alert("Please enter DeliverySlip BillNo");
            }else{
                $.ajax({
                type: "POST",
                url:"<?php echo site_url('AllocationByManagerController/getDeliverySlipBills');?>",
                    data:{"delBill" : delBill,"routeName" : routeName},
                    success: function (data) {
                      $('#result_data').html(data);
                    },
                    beforeSend: function(){
                        $('.comman-ajax-loader').css("visibility", "visible");
                    },
                    complete: function(){
                        $('.comman-ajax-loader').css("visibility", "hidden");
                    },
                    error: function(jqXHR, exception) {
                        alert("Something Went Wrong, Please Try Again...!");
                    }   
                });
                $('#delBillNo').val('');
                calfunction();
            }
        }
});
</script>

<script>
    var myVar;    
    $(document).ready(function(){
        myVar = setInterval("calfunction()", 1000);
    });
</script>

<script type="text/javascript">
    function calfunction(){
        var TotalValue = 0;
        var cnt = 0;

        var TotalValue1 = 0;
        var cnt1 = 0;

        $("tr #loop").each(function(index,value){
          var textValue= $(this).text();
          textValue=textValue.replace(',','');
          currentRow = parseFloat(textValue);
          TotalValue += currentRow;
          cnt =cnt+1;
        });

        $("tr #looop").each(function(index,value){
          var textValue= $(this).text();
          textValue=textValue.replace(',','');
          currentRow = parseFloat(textValue);
          TotalValue1 += currentRow;
          cnt1 =cnt1+1;
        });

        var totalCount=cnt+cnt1;
        var finalTotal=TotalValue+TotalValue1;
        
        totalCount=(Number(totalCount).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        finalTotal=(Number(finalTotal).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));

        $("#cntBillFnc").text(totalCount+''); 
        $("#totBillCnt").text(finalTotal+''); 
    }
</script>

<script>
    (function(){
          var todo = document.querySelector('#route_list'),
          add = document.querySelector('#shw_routeBills'),
          eName = document.querySelector('#rtBillNo');
            
          eName.addEventListener('keyup', function( ev ) {
            var text = eName.value;
            if(text.trim() !=""){
                var quote_str =  "'" + text + "'";
                if(ev.key==="Enter"){
                    if ( text !== '' ) {
                    todo.innerHTML += '<li class="list-group-item list-group-item-action">' + text + '<button style="float: right;" onclick="DeleteT(this,'+quote_str+')"><i class="fa fa-close"></i></button> </li>';
                    eName.value = '';
                    }
                }
            }
            
                
            ev.preventDefault();
          }, false);

    })();

    (function(){

      var todo = document.querySelector( '#lastRTlist' ),
          add = document.querySelector( '#shw_routeBills' ),
          eName = document.querySelector( '#rtBillNo' );
        
      add.addEventListener('click', function( ev ) {
            var text = eName.value;
            if ( text.trim() != '' ) {
              todo.innerHTML += '<li class="list-group-item list-group-item-action">' + text + '<button  style="float: right;" onclick="Delete(this);"><i class="fa fa-close"></i></button> </li>';
              alert(todo.innerHTML);
              eName.value = '';
            }
            
        ev.preventDefault();
      }, false);

    })();
      function Delete(currentEl){
      currentEl.parentNode.parentNode.removeChild(currentEl.parentNode);
      }
</script>

<script>
    (function(){
          var todo = document.querySelector( '#route_list' ),
          add = document.querySelector( '#rtBillNo' ),
          eName = document.querySelector( '#rtBillNo' );
            
          add.addEventListener('change', function( ev ) {
            var text = eName.value;
            if ( text.trim() != '' ) {
              todo.innerHTML += '<li class="list-group-item list-group-item-action">' + text + '<button  style="float: right;" onclick="Delete(this);"><i class="fa fa-close"></i></button> </li>';
              eName.value = '';
            }
                
            ev.preventDefault();
          }, false);

    })();

    function Delete(currentEl){
      currentEl.parentNode.parentNode.removeChild(currentEl.parentNode);
    }
</script>

 <script type="text/javascript">
    $(document).on("click","#shw_routeBills",function() {
        var routeNamesArr = new Array();
         $("#route_list li").each(function()
        {   
            if($.trim($(this).text()) !=""){
                routeNamesArr.push($(this).text());
            }
        });
  
        if(routeNamesArr.length<=0){
            alert("Please enter Route Name");
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('AllocationByManagerController/loadPastBills');?>",
                data:{"routeName" : routeNamesArr},
                success: function (data) {
                  // alert(data);
                  $('#result_past').html(data);
                },
                beforeSend: function(){
                    $('.comman-ajax-loader').css("visibility", "visible");
                },
                complete: function(){
                    $('.comman-ajax-loader').css("visibility", "hidden");
                },
                error: function(jqXHR, exception) {
                    alert("Something Went Wrong, Please Try Again...!");
                }   
            });
           $('#rtBillNo').val('');   
        }
    });
 </script>

  <script type="text/javascript">
    $(document).on("keyup","#rtBillNo",function(e) {
        var key = e.which;
        if(key == 13){
            // alert('hey');
            var routeNamesArr = new Array();
            $("#route_list li").each(function()
            {
                if($.trim($(this).text()) !=""){
                    routeNamesArr.push($(this).text());
                }
                 
            });
            
            if(routeNamesArr.length<=0){
                alert("Please enter Route Name");
            }else{
                $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('AllocationByManagerController/loadPastBills');?>",
                    data:{"routeName" : routeNamesArr},
                    success: function (data) {
                      $('#result_past').html(data);
                    },
                    beforeSend: function(){
                        $('.comman-ajax-loader').css("visibility", "visible");
                    },
                    complete: function(){
                        $('.comman-ajax-loader').css("visibility", "hidden");
                    },
                    error: function(jqXHR, exception) {
                        alert("Something Went Wrong, Please Try Again...!");
                    }   
                });
               $('#rtBillNo').val('');  
               calfunction(); 
            }
        }
    });
 </script>

<script type="text/javascript">
  $(document).on("click","#insert-ins",function() {
      if (confirm('Do you want to save this Allocation. ?')) {
           var emp = new Array();
          var rtName = new Array();
          var allocationCode=$('#allocation').text();
          var reference=$('#reference').val();
          var routeName=$('#name').val();
          
          $("#list li").each(function()
          {
               emp.push($(this).text());
          });
          $("#rlist li").each(function()
          {
               rtName.push($(this).text());
          });
          
          if(emp.length>0 && rtName.length>0){
              $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('AllocationByManagerController/insertAllocationData');?>",
                    data:{"emp":emp,"allocationCode" : allocationCode,"reference" : reference,"routeName" : routeName,"rtName":rtName},
                    success: function (data) {
                        $('#ins').html(data);
                    },
                    beforeSend: function(){
                        $('.comman-ajax-loader').css("visibility", "visible");
                    },
                    complete: function(){
                        $('.comman-ajax-loader').css("visibility", "hidden");
                    },
                    error: function(jqXHR, exception) {
                        alert("Something Went Wrong, Please Try Again...!");
                    }   
              });
          }else{
              alert('Please select Employee/Route');
          }
      }else{
        return false;
      }
       
});
    
</script>

<script type="text/javascript">
  $(document).on("click","#update-ins",function() {
      if (confirm('Do you want to save this Allocation. ?')) {
          var emp = new Array();
          var rtName = new Array();
          var allocationCode=$('#allocation').text();
          var reference=$('#reference').val();
          var routeName=$('#name').val();
          
          $("#list li").each(function()
          {
               emp.push($(this).text());
          });
          $("#rlist li").each(function()
          {
               rtName.push($(this).text());
          });

          
          if(emp.length>0 && rtName.length>0){
              $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('AllocationByManagerController/SaveConfirm');?>",
                    data:{"emp":emp,"allocationCode" : allocationCode,"reference" : reference,"routeName" : routeName,"rtName":rtName},
                    success: function (data) {
                        // alert(data);die();
                        alert('Record Updated...!');
                        window.location.href="<?php echo base_url();?>index.php/AllocationByManagerController/openAllocations";
                    },
                    beforeSend: function(){
                        $('.comman-ajax-loader').css("visibility", "visible");
                    },
                    complete: function(){
                        $('.comman-ajax-loader').css("visibility", "hidden");
                    },
                    error: function(jqXHR, exception) {
                        alert("Something Went Wrong, Please Try Again...!");
                    }   
              });
          }else{
              alert('Please select Employee/Route');
          }
      }else{
          return false;
      }
});
    

$(document).on("click","#cancelBillRemark",function() {
     alert('Please clear all status for bills');
});

</script>


