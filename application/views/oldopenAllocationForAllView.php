<?php $this->load->view('/layouts/commanHeader'); ?>

<!--<meta http-equiv="refresh" content="300"/>-->


<?php
  $projectSessionName="";

    if (isset($this->session->userdata['codeKeyData'])) {
      $projectSessionName= ($this->session->userdata['codeKeyData']['codeKeyValue']);
    }
    $designation = ($this->session->userdata[$projectSessionName]['designation']);
    $des=explode(',',$designation);

?>
<style>

  .btn:hover, .btn:focus {
    font-weight: bolder;
    outline: 10px solid blanchedalmond;
    border-style: hidden;
    box-shadow: 0 0 2px 2px red;
  color: black;
  }
</style>

        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/>
     <section class="col-md-12 box">
        <div class="container-fluid">
            <div class="block-header">
               
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Open Allocations Master
                            </h2>
                            <?php if(((in_array('owner', $des) || in_array('operator', $des) || in_array('cashier', $des) || in_array('accountant', $des) || in_array('senior_manager', $des) || in_array('manager', $des)))) { ?>
                            <h2>
                                <p align="right">
                                     <button class="btn btn-sm bg-primary margin" onClick="window.location.reload();"><i class="material-icons">refresh</i></button>
                                  <a href="<?php echo site_url('AllocationByManagerController/Add');?>">
                                    <button class="btn bg-primary margin"><i class="material-icons">person_add</i>  Add New Allocation </button></a>
                                  <a href="<?php echo site_url('manager/OfficeAllocationController/addOfficeAllocationsBills');?>">
                                    <button class="btn bg-primary margin"><i class="material-icons">person_add</i>  Add Office Allocation </button></a>  
                                </p> 
                            </h2>

                          <?php } ?>
                        </div>
                        <div class="body">
                            <div class="table-responsive tableFixHead">
                                <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Allocation No.</th>
                                            <th>Route</th>
                                            <th>Employees</th>
                                            <th>Salesman</th>
                                            <th>Allocation Amount</th>
                                            <th>No of Bills</th>
                                            <th>Deliveryman</th>
                                            <th>Manager Review</th>
                                            <th>SR Check</th>
                                            <th>Bills Check</th>
                                            <th>Cash & Cheques</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                           <th>S. No.</th>
                                            <th>Allocation No.</th>
                                            <th>Route</th>
                                            <th>Employees</th>
                                            <th>Salesman</th>
                                            <th>Allocation Amount</th>
                                            <th>No of Bills</th>
                                            <th>Deliveryman</th>
                                            <th>Manager Review</th>
                                            <th>SR Check</th>
                                            <th>Bills Check</th>
                                            <th>Cash & Cheques</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                      <?php
                                        $no=0;
                                        // print_r($allocations);
                                        foreach ($allocations as $data) 
                                          {
                                            // print_r($data);exit;
                                               $no++;

                                               // $totalBillsInAllocation= $this->AllocationByManagerModel->getBillCount('allocationsbills',$data['id']);
                                               // $totalAmountInAllocation= $this->AllocationByManagerModel->getAllocationAmount('allocationsbills',$data['id']);
                                               // $totalAllocationAmt=0;
                                               // if(!empty($totalAmountInAllocation)){
                                               //    $totalAllocationAmt=$totalAmountInAllocation[0]['sumNetAmount'];
                                               // }


                                               // $salesmanAllocation= $this->AllocationByManagerModel->getAllocationSalesman('allocationsbills',$data['id']);
                                               //  // print_r($salesmanAllocation);exit;
                                               // $salesmanName='';
                                               // if(!empty($salesmanAllocation)){
                                               //    $sname=array_unique($salesmanAllocation,SORT_REGULAR);
                                               //    // print_r($sname);exit;
                                               //    foreach($sname as $name){
                                               //        $salesmanName=$name['salesman'].', '.$salesmanName;
                                               //    }
                                               // }
                                               $start = strtotime(date('Y-m-d'));
                                                $end = strtotime($data['date']);
                                               $diff = ceil(abs($end - $start) / 86400);
                                               // $diff=strtotime(date('Y-m-d'))-strtotime($data['date']);
                                               // $routeName='';
                                               // $rtName=explode(",",rtrim($data['routeCode'],','));
                                               // for($i=0;$i<count($rtName);$i++){
                                               //      $routes=$this->AllocationByManagerModel->getRouteName($rtName[$i]);
                                               //      if(!empty($routes)){
                                               //          $routeName=$routeName.' '.$routes[0]['name'].', ';
                                               //      }
                                               // }

                                               //  $employee="";
                                               //  if(($data['fieldStaffCode1'] !='0')){
                                               //      $emp=$this->AllocationByManagerModel->getEmployeeNamesByID($data['fieldStaffCode1']);
                                               //      $employee= $employee.$emp.', ';
                                               //  }
                                               //  if(($data['fieldStaffCode2'] !='0')){
                                               //      $emp=$this->AllocationByManagerModel->getEmployeeNamesByID($data['fieldStaffCode2']);
                                               //      $employee=$employee.$emp.', ';
                                               //  }
                                                // echo $emp;
                                                // exit;

                                               // print_r($data);exit;

                                               // echo $diff.' '.$allocationDays;
                                           ?>
                                          <?php if($diff>$allocationDays){ ?>
                                                 <tr style="background-color: #b2e59e">
                                            <?php }else{ ?>
                                                 <tr>
                                            <?php } ?>
                                            <td><?php echo $no; ?></td>

                                            <td>
                                            <?php  if($data['fsStatus']==0){ ?>
                                                <a href="<?php echo base_url().'index.php/AllocationByManagerController/CompleteAllocation/'.$data['id']; ?>"><?php echo $data['allocationCode']; ?></a>
                                            <?php }else{ ?>
                                                <a href="<?php echo base_url().'index.php/AllocationByManagerController/allocationDetailsCheck/'.$data['id']; ?>"><?php echo $data['allocationCode']; ?></a>
                                            <?php } ?>  
                                                
                                            </td>
                                            
                                            <td>
                                            <?php 
                                                if($data['fsStatus']==0){ ?>
                                                  <a href="javascript:void();" data-routeallocationid="<?php echo $data['id']; ?>" data-name="<?php echo rtrim($data['allocationRouteName'],', '); ?>" data-toggle="modal" data-target="#routeLinkModal" class="routeLink"><?php echo rtrim($data['allocationRouteName'],', '); ?></a>
                                            <?php 
                                                }else{
                                                  echo rtrim($data['allocationRouteName'],', ');
                                                } 
                                            ?>
                                            </td> 
                                            <td>
                                              <?php 
                                                if($data['fsStatus']==0){ ?>
                                                  <a href="javascript:void();" data-empallocationid="<?php echo $data['id']; ?>" data-name="<?php echo rtrim($data['allocationEmployeeName'],', '); ?>"  data-toggle="modal" data-target="#empLinkModal" class="empLink"><?php echo rtrim($data['allocationEmployeeName'],', '); ?></a>
                                            <?php 
                                                }else{
                                                  echo rtrim($data['allocationEmployeeName'],', ');
                                                } 
                                            ?>
                                            </td>
                                            <td><?php echo rtrim($data['allocationSalesman'],', '); ?></td>
                                            <td align="right"><?php echo number_format($data['allocationTotalAmount']); ?></td>
                                            <td><?php echo $data['allocationBillCount']  ; ?></td>

                                <?php if(((in_array('owner', $des)) || (in_array('manager', $des)) || (in_array('senior_manager', $des)) || (in_array('cashier', $des))) && (($data['fsStatus']==0) || ($data['fsStatus']==2))) { 
                                        if(($data['fsStatus']==0) && ($data['isMobileAllocation']==1) && ($data['isApproved']==0)){  ?>
                                            <td>    <a href="<?php echo base_url().'index.php/AllocationByManagerController/approvedMobileAllocation/'.$data['id']?>"><i class="btn btn-sm btn-primary material-icons">check</i></a>
                                              &nbsp;&nbsp;&nbsp;<a href="<?php echo base_url().'index.php/AllocationByManagerController/cancelTotalAllocation/'.$data['id']?>"><i class="btn btn-sm btn-primary material-icons">cancel</i></a>
                                            </td>
                              <?php }else{
                                                if(((in_array('owner', $des)) || (in_array('manager', $des)) || (in_array('senior_manager', $des))) && (($data['fsStatus']==0) || ($data['fsStatus']==2))){
                              ?>
                                            <td>
                                            <?php if($this->session->userdata['workRestrict']['status']=="yes"){?>
                                              <button class="btn btn-xs btn-primary waves-effect waves-float" onclick="planUpgradeMsg()">
                                              <center><a href="javascript:void();"><i class="material-icons">add</i></center>
                                              </button>
                                            <?php }else{ ?> 
                                                <center><a href="<?php echo base_url().'index.php/fieldStaff/FieldStaffController/fieldStaff/'.$data['id']; ?>"><i class="material-icons">add</i></a></center>
                                            <?php } ?>

                                            </td>
                                  <?php      }else{
                                  ?>
                                            <td></td>
                                  <?php
                                            }
                                          } 

                                        }else if(((in_array('deliveryman', $des))) && (($data['fsStatus']==0) || ($data['fsStatus']==2))) { 
                                          if(($data['fsStatus']==0) && ($data['isMobileAllocation']==1) && ($data['isApproved']==1)){  ?>
                                  ?>
                                          <td>
                                          <?php if($this->session->userdata['workRestrict']['status']=="yes"){?>
                                            <center><a href="javascript:void();"><i class="material-icons">add</i></a></center>
                                          <?php }else{ ?>
                                            <center><a href="<?php echo base_url().'index.php/fieldStaff/FieldStaffController/fieldStaff/'.$data['id']; ?>"><i class="material-icons">add</i></a></center>
                                          <?php } ?>

                                          </td>
                                  <?php
                                          }else if(($data['fsStatus']==0) && ($data['isMobileAllocation']==0) && ($data['isApproved']==0)){
                                  ?>
                                          <td>
                                          <?php if($this->session->userdata['workRestrict']['status']=="yes"){?>
                                            <center><a href="javascript:void();"><i class="material-icons">add</i></a></center>
                                          <?php }else{ ?>
                                            <center><a href="<?php echo base_url().'index.php/fieldStaff/FieldStaffController/fieldStaff/'.$data['id']; ?>"><i class="material-icons">add</i></a></center>
                                            <?php } ?>
                                          </td>
                                  <?php          
                                          }else{
                                  ?>
                                          <td></td
                                  <?php          
                                          }
                                        }else if($data['fsStatus']==1){  ?>
                                            <td>
                                              <center><i class="material-icons">check</i></center>
                                            </td> 
                                  <?php }else{ ?> 
                                            <td></td>
                                  <?php } ?>

                                  <?php if($data['fsStatus']==1 && ((in_array('owner', $des) || in_array('senior_manager', $des) || in_array('manager', $des))) && $data['managerHisaabStatus']==0 && $data['gkStatus']==0){?>
                                            <td>        
                                                <center><a href="<?php echo base_url().'index.php/manager/FieldStaffController/fieldStaff/'.$data['id']; ?>"><i class="material-icons">add</i></a></center>
                                            </td>
                                  <?php }else if($data['managerHisaabStatus']==1 && $data['fsStatus']==1){  ?>
                                            <td><center><i class="material-icons">check</i></center></td> 
                                  <?php }else{ ?> 
                                            <td></td>
                                  <?php } ?>

                                  <?php if($data['fsStatus']==1 && $data['managerHisaabStatus']==1 && $data['gkStatus']==1){ ?>
                                          <td><center><i class="material-icons">check</i></center></td>
                                  <?php }else if((in_array('godownkeeper', $des) || in_array('owner', $des)) && $data['fsStatus']==1 && $data['managerHisaabStatus']==1 && $data['gkStatus']==0){?>
                                          <td>
                                            <center><a href="<?php echo base_url().'index.php/godownkeeper/GodownKeeperController/GodownkeeperBills/'.$data['id']?>"><i class="material-icons">add</i></a></center>
                                          </td>
                                  <?php }else{ ?>
                                          <td></td>
                                <?php }
                                        if((in_array('godownkeeper', $des) || in_array('owner', $des)  || (in_array('manager', $des))  || (in_array('senior_manager', $des))) && $data['fsStatus']==1 && $data['managerHisaabStatus']==1 && $data['gkStatus']==1 && $data['sr_bill_Status']==0){
                                  ?>
                                        <td><a href="<?php echo base_url().'index.php/manager/SrCheckController/LoadSrCheckDetails/'.$data['id'];?>"><center><i class="material-icons">add</i></center></a></td>

                                  <?php }else if($data['fsStatus']==1 && $data['gkStatus']==1 && $data['sr_bill_Status']==1){ ?>
                                        
                                             <td><center><i class="material-icons">check</i></center></td>
                                  <?php }else{  ?>
                                            <td></td>
                                  <?php } ?>
                                    

                                  <?php if(((in_array('cashier', $des)) || (in_array('owner', $des))) && $data['fsStatus']==1 && $data['managerHisaabStatus']==1 && $data['gkStatus']==1 && $data['sr_bill_Status']==1 && $data['cashChequeStatus']==0){
                                  ?>
                                        <td><a href="<?php echo base_url().'index.php/cashier/CashBookController/allocationWiseCashBook/'.$data['id'];?>"><center><i class="material-icons">add</i></center></a></td>

                                  <?php }else if($data['fsStatus']==1 && $data['gkStatus']==1 && $data['sr_bill_Status']==1 && $data['cashChequeStatus']==1){ ?>
                                        
                                             <td><center><i class="material-icons">check</i></center></td>
                                  <?php }else{  ?>
                                            <td></td>
                                  <?php } ?>
                                          
                                           
                                        </tr>
                                    <?php
                                        }


                                    foreach ($officeAllocations as $data) 
                                    {
                                        $no++; 
                                    ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td>
                                                  <?php echo $data['allocationCode']; ?>
                                            </td>
                                            <td></td>
                                            <td><?php echo $data['title']; ?></td>
                                            <td></td>
                                            <td>
                                            <?php if($data['managerStatus']=="0"){ ?>
                                              <a href="<?php echo base_url().'index.php/manager/OfficeAllocationController/loadOfficeAllocationsBills/'.$data['id'];?>"><center><i class="material-icons">add</i></center></a>
                                            <?php }else if($data['managerStatus']=="1"){ ?>
                                                <span>Pending for owner approval</span>
                                            <?php }else{ ?>
                                                <center><i class="material-icons">check</i></center>
                                            <?php } ?>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            
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
            <!-- #END# Basic Examples -->  
        </div>
    </section>


<div class="modal" id="empLinkModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><center>Edit Employee Details</center></h5>
      </div>
      <div class="modal-body">
        <div class="col-md-12">
          <div class="col-md-5">
            <input type="hidden" id="allocationIdForEmp" autocomplete="off" name="allocationIdForEmp">   
            <label>Employee Name:</label>
              <input type="text" id="eName" autocomplete="off" list="empList" name="eName[]" class="form-control" placeholder="Enter Emp Name"><br>
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
              <button type="button" id="eAdd" class="btn btn-sm btn-primary">Add</button>
           </div>
           
           <div class="col-md-7">
               <label>Selected Emp</label>
                <ul class="list-group" id="list" multiple="multiple"></ul>
           </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="empSubmit" class="btn btn-sm btn-primary">Save changes</button>
        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<div class="modal" id="routeLinkModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><center>Edit Route Details</center></h5>
      </div>
      <div class="modal-body">
        <div class="col-md-12">
          <div class="col-md-5">
              <input type="hidden" id="allocationIdForRoute" autocomplete="off" name="allocationIdForRoute">   
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
              <button type="button" id="rAdd" class="btn btn-sm btn-primary ">Add</button>
          </div>

          <div class="col-md-7">
              <label>Selected Route</label>
              <ul class="list-group" id="rlist" multiple="multiple"></ul>
          </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" id="routeSubmit" class="btn btn-sm btn-primary">Save changes</button>
        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('/layouts/footerDataTable'); ?>


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



<script type="text/javascript">
    $(document).on("click",".empLink",function() {
          $('#list').empty();
          var ename= $(this).data('name');

          var allocationId= $(this).data('empallocationid');
          $('#allocationIdForEmp').val(allocationId);

          var todo = $('#list').text();
          var ename = ename.split(" ,");

          for(i=0;i<ename.length;i++){
              $('#list').append('<li class="list-group-item list-group-item-action">' + ename[i] + '<button  style="float: right;" onclick="Delete(this);"><i class="fa fa-close"></i></button> </li>');
          } 
    });
</script>

<script type="text/javascript">
    $(document).on("click",".routeLink",function() {
          $('#rlist').empty();
          var routeName= $(this).data('name');

          var allocationId= $(this).data('routeallocationid');
          $('#allocationIdForRoute').val(allocationId);

          var todo = $('#rlist').text();
          var routeName = routeName.split(" ,");

          for(i=0;i<routeName.length;i++){
              $('#rlist').append('<li class="list-group-item list-group-item-action">' + routeName[i] + '<button  style="float: right;" onclick="Delete(this);"><i class="fa fa-close"></i></button> </li>');
          } 
    });
</script>

<script type="text/javascript">
    $(document).on("click","#empSubmit",function() {
          var empNamesArr = new Array();
          $("#list li").each(function()
          {
               empNamesArr.push($(this).text());
          });

          var allocationId=$('#allocationIdForEmp').val();

          if(empNamesArr.length > 0){
              $.ajax({
                  type: "POST",
                  url:"<?php echo site_url('AllocationByManagerController/updateAllocationEmployees');?>",
                  data:{"allocationId":allocationId,"emp":empNamesArr},
                  success: function (data) {
                      if(data.trim()=="Record Updated...!"){
                          alert(data);
                          window.location.href="<?php echo base_url();?>index.php/AllocationByManagerController/openAllocations";
                      }else{
                          alert(data);
                      }
                  }  
              });
          }else{
              alert('Employees not added');
          }
    });
</script>

<script type="text/javascript">
    $(document).on("click","#routeSubmit",function() {
          var routeNamesArr = new Array();
          $("#rlist li").each(function()
          {
               routeNamesArr.push($(this).text());
          });

          if(routeNamesArr.length > 0){
              var allocationId=$('#allocationIdForRoute').val();
              $.ajax({
                  type: "POST",
                  url:"<?php echo site_url('AllocationByManagerController/updateAllocationRoutes');?>",
                  data:{"allocationId":allocationId,"route":routeNamesArr},
                  success: function (data) {
                      if(data.trim()=="Record Updated...!"){
                        alert(data);
                        window.location.href="<?php echo base_url();?>index.php/AllocationByManagerController/openAllocations";
                      }else{
                        alert(data);
                      }
                  }  
              });
          }else{
              alert('Routes not added');
          }
    });
</script>
