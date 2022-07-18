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
                              <div class="row cust-tbl">
                                <div class="col-md-2">
                                    <label>Allocation : </label>
                                    <label id="allocation"><?php echo date("dmy");?>-<?php echo $nextId ?>
                                    </label><br /><br>

                                    <label>Company Name:</label>
                                    <select id="cmpName" class="form-control" name="cmpName">
                                            <option value="<?php echo $compName; ?>"><?php echo $compName; ?></option>
                                    </select><br><br>

                                   <label>Employee Name:</label>
                                    <input type="text" id="eName" autocomplete="off" list="empList" name="eName[]" class="form-control" placeholder="Enter Emp Name"><br>

                                     <button type="button" id="eAdd" class="btn btnStyle margin btn-sm"> Add </button>
                                                          
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
                                     <button type="button" id="rAdd" class="btn btnStyle margin btn-sm"> Add </button>
                                </div>
                                <div class="col-md-3">
                                
                                 <br><br>
                                       <label> Reference:</label>
                                     <input type="text" id="reference" name="reference" class="form-control" placeholder="Enter Reference" required> <br>
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
                                    
                                        
                                       
                                    <br>
                                    <label>Selected Emp</label>
                                    <ul class="list-group" id="list" multiple="multiple"></ul>
                                    <br>
                                    <label>Selected Route</label>
                                    <ul class="list-group" id="rlist" multiple="multiple"></ul>
                                </div>
                            
                                <div class="col-md-7">

                                    <!-- CURRENT SUPPLY-->
                                    <div class="col-md-5 table-responsive">
                                         <?php echo validation_errors(); ?>
                                        <?php echo form_open_multipart('commanTransactions/AllocationByProcessController/getCurrentBills') ?>
                                        <table class="table cust-tbl table-bordered">
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
                                                        <button type="button" id="insert-more" class="btn btnStyle margin btn-sm"> Add Current Bills </button><br />                                                      
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
                                                        <button type="button" id="shw_routeBills" class="btn btnStyle margin btn-sm">Show</button>
                                                         <button type="button" onclick="clearPast();" id="rmv_routeBills" class="btn btn-danger margin btn-sm">Cancel</button>
                                                    </td>
                                                </tr>
                                               
                                            </tbody>
                                        </table>
                                         <?php echo form_close(); ?>
                                    </div>
                                    <!-- PAST BILLS-->
                                    <div class="col-md-7 table-responsive">
                                        <!-- <?php echo validation_errors(); ?>
                                        <?php echo form_open_multipart('commanTransactions/AllocationByProcessController/getPastBills') ?> -->
                                        <table class="table cust-tbl table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-xs-center" colspan="5"><center>Additional Bills</center></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="2">Current Supply :  </td>
                                                    <td colspan="2">
                                                        <input type="text" name="addBill" id="addBill" list="toBill"autocomplete="off" placeholder="Enter Bill No" class="form-control">
                                                        <datalist id="addBill">
                                                            <?php foreach($billNos as $cs){
                                                            $billNo=$cs['billNo']." : ".$cs['retailerName'];
                                                        ?>
                                                            <option value="<?php echo $billNo;?>"/>
                                                        <?php } ?>
                                                        </datalist>
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <button type="button" id="insert-more1" class="btn btnStyle margin btn-sm"> Add </button><br />                                                      
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
                                                         <button type="button" id="insert-past" class="btn btnStyle margin btn-sm"> Add </button>
                                                    </td>
                                                </tr>

                                                <!--  <tr>
                                                    <td colspan="2">Bounced Cheques</td>
                                                    <td colspan="2">
                                                        
                                                        <input type="text" id="chequeNo" list="chBill" autocomplete="off" name="chequeNo" class="form-control" placeholder="Enter Cheque No">  
                                                        <datalist id="chBill">
                                                            
                                                        </datalist>      
                                                      
                                                    </td>
                                                    <td>
                                                         <button type="button" id="insert-bounced" class="btn btn-success margin btn-sm"> Add </button>
                                                    </td>
                                                </tr> -->
                                                
                                                <tr>
                                                    <td colspan="2">Delivery Challans</td>
                                                    <td colspan="2">

                                                        <input type="text" id="delBillNo" list="delBill" autocomplete="off" name="delBillNo" class="form-control" placeholder="Enter Bill No">  
                                                        <datalist id="delBill">
                                                             <?php foreach($deliverySlip as $cs){
                                                            $billNo=$cs['billNo']." : ".$cs['retailerName'];
                                                        ?>
                                                            <option value="<?php echo $billNo;?>"/>
                                                        <?php } ?>
                                                        </datalist>      
                                                      
                                                    </td>
                                                     <td>
                                                         <button type="button" id="insert-delivery" class="btn btnStyle margin btn-sm"> Add </button>
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
                                      
                                          <!-- <?php echo form_close(); ?> -->
                                    </div>
                                    
                                </div>
                                </div>
                            <div class="row">
                                                       
                                <div class="row m-t-20">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table cust-tbl table-bordered" id="tbl">
                                                <tr class="head">
                                                    <td colspan="12" style="background-color: whitesmoke;"><center><b>Current Supply Bills</b></center></td>
                                                </tr>
                                                <tr class="gray">
                                                    <th>No</th>
                                                    <th>Bill No.</th>
                                                    <th>Date</th>
                                                    <th>Retailer Name</th>
                                                    <th>Amount</th>
                                                    <th>Sale Return</th>
                                                    <th>Past Coll.</th>
                                                    <th>CD</th>
                                                    <th>Pending Amount</th>
                                                    <th>Todays Coll.</th>
                                                    <th>Remove</th>
                                                </tr>
                                                <tbody id="result_data">
                            <?php
                                        $no=0;
                                         if(!empty($current)){
                                        foreach ($current as $data) 
                                          {

                                             $resendBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$data['id'],'isResendBill');
                                            $lostBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$data['id'],'isLostBill');
                                            $lostChequesBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$data['id'],'isLostCheque');
                                            $pendingNeftBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$data['id'],'isPendingNeft');

                                            $bouncedBill=$this->AllocationByManagerModel->checkBouncedBill('billpayments',$data['id']);


                                           $no++; 
                                    ?>
                                        <tr>
                                            <td><?php echo $no.' '; 
                if($data['creditNoteRenewal']>0){ echo '<span class="logo_prov">CN</span>'; }
              if($resendBills>0){ echo '<span class="logo_prov">RB</span>'; }
              if($lostBills>0){ echo '<span class="logo_prov">LB</span>'; }
              if($lostChequesBills>0){ echo '<span class="logo_prov">LC</span>'; }
              if($pendingNeftBills>0){ echo '<span class="logo_prov">PN</span>'; }
                
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
                                            <td><?php echo $data['netAmount']?></td>
                                            <td><?php echo $data['SRAmt']?></td>
                                             <td><?php echo $data['receivedAmt']+$data['debit']+$data['officeAdjustmentBillAmount']+$data['otherAdjustment'];?></td>
                                            
                                            <td><?php echo $data['cd']?></td>
                                            <td><?php echo $data['pendingAmt']?></td>
                                            <td>0.00</td>
                                            <td> 
                                              <a>
                            <button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
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
                                            
                                            <table class="table cust-tbl table-bordered">
                                            <tr class="head">
                                                <td colspan="12"  style="background-color: whitesmoke;"><center><b>Past Bills</b></center></td>
                                            </tr>
                                            <tr class="gray">
                                                <th>No</th>
                                                <th>Bill No.</th>
                                                <th>Date</th>
                                                <th>Retailer Name</th>
                                                <th>Amount</th>
                                                <th>Sale Return</th>
                                                <th>Past Coll.</th>
                                                <!-- <th>USR</th> -->
                                                <th>CD</th>
                                                <th>Pending Amount</th>
                                                <th>Todays Coll.</th>
                                                <th>Remove</th>
                                            </tr>
                                            <tbody id="result_past">
                                            </tbody>
                                           
                                           
                                            </table>
                                        </div>
                                    </div>
                                   
                                    <div class="col-md-12">
                                    <div class="col-md-4">
                                        <?php echo validation_errors(); ?>
                                        <?php echo form_open_multipart('commanTransactions/AllocationByProcessController/insertAllocationData') ?>
                                        <p id="ins"></p>
                                        <p>
                                            
                                        <button type="button" id="insert-ins" class="btn btnStyle m-t-15 waves-effect">
                                              <i class="material-icons">save</i> 
                                              <span class="icon-name"> Save</span>
                                        </button>

                                            <a href="<?php echo site_url('AdHocController/adhocBills/');?>">
                                                <button type="button" class="btn btn-danger m-t-15 waves-effect">
                                                    <i class="material-icons">cancel</i> 
                                                    <span class="icon-name"> Close </span>
                                                </button>
                                            </a>  

                                             
                                        </p>
                                         <?php echo form_close(); ?>
                                    </div>
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
                url: "<?php echo site_url('commanTransactions/AllocationByProcessController/removeBillIdFromSession');?>",
                type: "post",
                data:{"rmId" : rmId},
                success: function (response) {
                    // $('#result_data').html(response);    
                }
        });
    }

     function removeRouteBills(that,name){
        
        var name= name;
        // alert(name);

        $(that).closest('tr').remove();
        $.ajax({
                url: "<?php echo site_url('commanTransactions/AllocationByProcessController/removeRouteBillIdFromSession');?>",
                type: "post",
                data:{"name" : name},
                success: function (response) {
                }
        });
    }
</script>



<script>
    (function(){

      var todo = document.querySelector( '#list' ),
          add = document.querySelector( '#eAdd' ),
          eName = document.querySelector( '#eName' );
        
      add.addEventListener('click', function( ev ) {
            var text = eName.value;
            if ( text !== '' ) {
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
            if ( text !== '' ) {
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
    (function(){
          var todo = document.querySelector('#route_list'),
          add = document.querySelector('#shw_routeBills'),
          eName = document.querySelector('#rtBillNo');
            
          add.addEventListener('click', function( ev ) {
            var text = eName.value;

            if ( text !== '' ) {
              todo.innerHTML += '<li class="list-group-item list-group-item-action">' + text + '<button style="float: right;" onclick="DeleteT(this)"><i class="fa fa-close"></i></button> </li>';

              eName.value = '';
            }
                
            ev.preventDefault();
          }, false);

    })();

    function DeleteT(currentEl){
         // $(currentEl).closest('tr').remove();
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
                url: "<?php echo site_url('commanTransactions/AllocationByProcessController/updateStatusForCurrentBills');?>",
                type: "post",
                data:{"id" : id , "status" : result.value,"from" : from,"to" :to},
                success: function (response) {
                    $('#result_data').html(response);  
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
                url: "<?php echo site_url('commanTransactions/AllocationByProcessController/updateStatusForPastBills');?>",
                type: "post",
                data:{"id" : id , "status" : result.value,"pName" : pName},
                success: function (response) {
                    $('#result_past').html(response);  
                }
            });
        }
    });
}

</script>



<script type="text/javascript">
    $(document).on('click','#insert-more',function(){
        var from = $('#from').val();
        var to = $('#to').val();
        if(from == "" || to ==""){
            alert("Please enter From/To BillNo");
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('commanTransactions/AllocationByProcessController/getCurrentBills');?>",
                data:{"from" : from , "to" : to},
                success: function (data) {
                  $('#result_data').html(data);
                } 
            });
            $('#from').val('');
            $('#to').val('');
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
            url:"<?php echo site_url('commanTransactions/AllocationByProcessController/CompCurrentBills');?>",
                data:{"cmpName" : cmpName},
                success: function (data) {
                  $('#frmBill').html(data);
                  $('#toBill').html(data);
                  $('#addBill').html(data);
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
            url:"<?php echo site_url('commanTransactions/AllocationByProcessController/CompPastBills');?>",
                data:{"cmpName" : cmpName},
                success: function (data) {
                  $('#pstBill').html(data);
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
            url:"<?php echo site_url('commanTransactions/AllocationByProcessController/CompChequeBills');?>",
                data:{"cmpName" : cmpName},
                success: function (data) {
                  $('#chBill').html(data);
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
            url:"<?php echo site_url('commanTransactions/AllocationByProcessController/CompDeliveryBills');?>",
                data:{"cmpName" : cmpName},
                success: function (data) {
                  $('#delBill').html(data);
                }  
            });
        }
});
</script>

<script type="text/javascript">
    $(document).on('click','#insert-more1',function(){
        
        var addBill = $('#addBill').val();
        if(addBill==""){
            alert("Please enter BillNo");
        }else{
             $.ajax({
            type: "POST",
            url:"<?php echo site_url('commanTransactions/AllocationByProcessController/getCurrentBillsWithAdditions');?>",
                data:{"addBill" : addBill},
                success: function (data) {
                    
                  $('#result_data').html(data);
                }  
            });
                $('#addBill').val('');
        }
});
</script>

<script type="text/javascript">
    $(document).on('click','#insert-past',function(){

        var pName = $('#pName').val();
        var routeName = $('#name').val();
        if(pName==""){
            alert("Enter Past BillNo");
        }else{
             $.ajax({
            type: "POST",
            url:"<?php echo site_url('commanTransactions/AllocationByProcessController/getPastBills');?>",
                data:{"pName" : pName,"routeName" : routeName},
                success: function (data) {
                    // alert(data);
                  $('#result_past').html(data);
                }  
            });
                $('#pName').val('');
        }
});
</script>

<script type="text/javascript">
    $(document).on('click','#insert-delivery',function(){
        var delBill = $('#delBillNo').val();
        var routeName = $('#name').val();
        
        if(delBill==""){
            alert("Please enter DeliverySlip BillNo");
        }else{
            $.ajax({
            type: "POST",
            url:"<?php echo site_url('commanTransactions/AllocationByProcessController/getDeliverySlipBills');?>",
                data:{"delBill" : delBill,"routeName" : routeName},
                success: function (data) {
                  $('#result_data').html(data);
                }  
            });
            $('#delBillNo').val('');
        }
});
</script>

<script type="text/javascript">
    $(document).on('click','#insert-bounced',function(){
        var chequeNo = $('#chequeNo').val();
        var routeName = $('#name').val();
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('commanTransactions/AllocationByProcessController/getBouncedBills');?>",
        data:{"chequeNo" : chequeNo,"routeName" : routeName},
        success: function (data) {
          $('#result_bounced').html(data);
        }  
    });
        $('#chequeNo').val('');
        
});
</script>

 <script type="text/javascript">
    $(document).on("click","#shw_routeBills",function() {
        var routeNamesArr = new Array();
         $("#route_list li").each(function()
        {
             routeNamesArr.push($(this).text());
        });
  
        if(routeNamesArr.length<=0){
            alert("Please enter Route Name");
        }else{
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('commanTransactions/AllocationByProcessController/loadPastBills');?>",
                data:{"routeName" : routeNamesArr},
                success: function (data) {
                  $('#result_past').html(data);
                }  
            });
           $('#rtBillNo').val('');   
        }
    });
 </script>

 <script type="text/javascript">
        function clearPast(){
                var routeName = $('#rtBillNo').val();
                if(routeName==""){
                    alert("Please enter Route Name");
                }else{
                     $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('commanTransactions/AllocationByProcessController/clearAllBills');?>",
                        data:{"routeName" : routeName},
                        success: function (data) {
                          $('#result_past').html(data);
                        }  
                    });
                   $('#rtBillNo').val('');   
                }
        }
 </script>


<script type="text/javascript">
    $(document).on("click","#insert-ins",function() {
        // $("#insert-ins").attr("disabled", true);
        var emp = new Array();
        var rtName = new Array();
        var allocationCode=$('#allocation').text();
        var reference=$('#reference').val();
        var company=$('#cmpName').val();
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
                url:"<?php echo site_url('commanTransactions/AllocationByProcessController/insertAllocationData');?>",
                data:{"emp":emp,"allocationCode" : allocationCode,"company":company,"reference" : reference,"routeName" : routeName,"rtName":rtName},
                success: function (data) {
                    // alert(data);die();
                    alert('Record Saved...!');
                    window.location.href="<?php echo base_url();?>index.php/AllocationByManagerController/openAllocations";
                }  
            });
        }else{
            alert('Please select Employee/Route');
        }
});
    
</script>

<script type="text/javascript">
    $(document).on("click","#update-ins",function() {
        // $("#update-ins").attr("disabled", true);
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
                url:"<?php echo site_url('commanTransactions/AllocationByProcessController/SaveConfirm');?>",
                data:{"emp":emp,"allocationCode" : allocationCode,"reference" : reference,"routeName" : routeName,"rtName":rtName},
                success: function (data) {
                  $('#ins').html(data);
                }  
            });
        }else{
            alert('Please select Employee/Route');
        }
});
    
</script>


<script>
    $(document).on("click","#test",function() {
        var empName = $('#eName').val();
        alert(empName);
        if(empName==""){
            alert("Please enter Route Name");
        }else{
            $.ajax({
                 type:'post',
                 url:'<?php echo site_url('mypage/check_code/'); ?>',
                 data:{ref_code: ref_code},
                 success:function(msg){
                     if(msg.indexOf('value does exist') > -1)
                         $('#msg').html('<span style="color: green;">'+msg+"</span>");    
                     else $('#msg').html('<sapn style="color:red;">Value does not exist</span>');
                 }
            });
        }
    });
</script>

