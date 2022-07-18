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
    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Signed Bills
                            </h2>
                        </div>
                        <div class="body">

                            <?php 
                                if(!empty($srData) || !empty($fsr) || !empty($signed)){
                            ?>
                            
                            <div align="right">
                                   <!--  <button class="btn btn-primary m-t-15 waves-effect btn-sm">
                                        <span class="icon-name">
                                        Approve All
                                        </span>
                                    </button>

                                    <button class="btn btn-primary m-t-15 waves-effect btn-sm">
                                        <span class="icon-name">
                                        Disapprove All
                                        </span>
                                    </button> -->
                                    <button id="svId" class="btn btn-primary m-t-15 waves-effect btn-sm">
                                        <span class="icon-name">
                                        Save
                                        </span>
                                    </button> 
                                    <input id="allocatedHid" type="hidden" value="<?php echo $idAllocated; ?>">
                             
                                </div>
                                <div id="res"></div>
                            <div class="table-responsive">
                                <div id="err"></div>
                                <table class="table table-bordered table-striped table-hover js-basic-example DataTable display nowrap" id="example" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <!-- <th></th> -->
                                            <th>S. No.</th>
                                            <th>Bill No.</th>
                                            <th>Retailer</th>
                                            <!-- <th>Salesman</th>
                                            <th style="display: none;">id</th>
                                            <th style="display: none;">billId</th>
                                            <th>Item</th>
                                            <th>Net Amount</th>
                                            <th>Billed Qty</th>
                                            <th>FS SR</th>
                                            <th>GK SR</th> -->
                                           <!--  <th>Edit</th>
                                            <th>Approve/Disapprove</th>
                                            <th>SR Check</th> -->
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <!-- <th></th> -->
                                            <th>S. No.</th>
                                            <th>Bill No.</th>
                                            <th>Retailer</th>
                                            <!-- <th>Salesman</th>
                                            <th style="display: none;">id</th>
                                            <th style="display: none;">billId</th>
                                            <th>Item</th>
                                            <th>Net Amount</th>
                                            <th>Billed Qty</th>
                                            <th>FS SR</th>
                                            <th>GK SR</th> -->
                                           <!--  <th>Edit</th>
                                            <th>Approve/Disapprove</th>
                                            <th>SR Check</th> -->
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                   <?php
                                        $no=0;
                                        $i = 1;
                                        $last_key = '';
                                        if(!empty($signed)){
                                            foreach($signed as $s){
                                                $no++;
                                        ?>
                                       <!--  <tr>
                                            <td></td>
                                            <td><?php echo $no;?></td>
                                            <td><?php echo $s['billNo'];?></td>
                                            <td><?php echo $s['retailerName'];?></td>
                                            <td></td>
                                            <td></td>
                                            <td><?php echo $s['netAmount'];?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <button onclick="signedOkStatus(this,'<?php echo $s["id"]?>','<?php echo $idAllocated; ?>');removeMe(this);" style="font-size : 12px;" id="signedOk" class="btn-primary waves-effect"><span class="icon-name">Yes</span>
                                                 </button> 
                                                 <button onclick="signedStatus(this,'<?php echo $s["id"]?>','<?php echo $idAllocated; ?>');removeMe(this);" style="font-size : 12px;" id="signedOk" class="btn-primary waves-effect"><span class="icon-name">No</span>
                                                 </button>
                                            </td>
                                        </tr> -->
                                        <?php 
                                               }
                                            }
                                       ?>

                                         <!-- SR Page -->
                                       <?php
                                        if(!empty($srData)){
                                        foreach ($srData as $data) {
                                            $val=explode('.',$data['creditAdjustment']);
                                            $i++;
                                            $current_key = $data['billNo'] . $data['retailerName'];
                                            if ($current_key !== $last_key) {
                                                $no++;
                                                if($val[1]==00 && $val[0] !=0){
                                            ?>
                                            <tr style='background-color:white;'>
                                                <!-- <td><i class='material-icons' style='color:red;font-size:15px'>local_parking</i></td> -->
                                            <?php }else{  ?>
                                                    <!-- <td></td> -->
                                            <?php  } ?>
                                            <td><?php echo $no;?></td>
                                            <td><?php echo $data['billNo'];?></td>
                                            <td><?php echo $data['retailerName'];?></td>
                                            <td><?php echo $data['salesman'];?></td>
                                            <?php
                                                } else {
                                                 if($val[1]==00 && $val[0] !=0){
                                            ?>
                                                    <td></td>
                                            <?php  }else{ ?>
                                                    <td></td>
                                            <?php  } ?>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            <?php
                                                 }
                                                $last_key = $current_key;
                                            ?>
                                                <td style="display: none;"><?php echo $data['id'];?></td>
                                                <td style="display: none;"><?php echo $data['billId'];?></td>
                                                <td><?php echo $data['productName'];?></td>
                                                <td><?php echo number_format($data['netAmount'],2);?></td>
                                                <td><?php echo number_format($data['qty']);?></td>
                                                <td><?php echo number_format($data['fsReturnQty']);?></td>
                                                <td><?php echo number_format($data['gkReturnQty']);?></td>
                                    
                                    <?php if($data['managerSrStatus']!=1){?>
                                            <!-- <td id="okedit">
                                            <button id="ok" onclick="managerStatus(this,'<?php echo $data["id"]?>','<?php echo $idAllocated; ?>');" style="font-size : 12px;" type="" class="btn-primary waves-effect"> <span class="icon-name">Ok</span></button> 
                                           
                                                <a id="srEdit-id" data-toggle="modal" data-target="#srCheckEditModal" data-allocated="<?php echo $idAllocated;?>" data-id="<?php echo $data['id']; ?>">
                                                <button id="edit" style="font-size : 12px;" class="btn-primary waves-effect" data-type="basic"><span>Edit</span></button>
                                            </a> 
                                        </td> -->
                                    <?php }else{?>
                                        <!-- <td></td> -->
                                    <?php } ?>

                                     <?php if($data['managerSrStatus']==1){ ?>
                                        <!--  <td>
                                            <a id="yes" href="<?php echo base_url().'index.php/manager/SrCheckController/ApprovedSR/'.$data['id'].'/'.$data['billId'].'/'.$idAllocated;?>">
                                                <button style="font-size : 12px;" type="" class="btn-primary waves-effect">
                                                    <span class="icon-name">Yes </span></button> 
                                            </a>
                                            <a id="disap-id" data-toggle="modal" data-target="#disapprovModal" data-id="<?php echo $data['id']; ?>" data-allocation="<?php echo $idAllocated; ?>" href="#">
                                                <button style="font-size : 12px;" type="" class="btn-primary waves-effect">
                                                    <span class="icon-name">No</span></button> 
                                            </a>
                                         </td> -->
                                     <?php }else{ ?>
                                        <!-- <td>
                                            <button style="font-size : 12px;" id="yesBtn" type="" class="btn-primary waves-effect" disabled><span class="icon-name"> Yes</span></button> 
                                            <button style="font-size : 12px;" type="" class="btn-primary waves-effect" disabled>
                                                    <span class="icon-name">No</span> </button> 
                                        </td> -->
                                    <?php } ?>
                                           </tr>
                                     <?php
                                                }
                                            }
                                            ?>

                                        <!-- FSR Page -->
                                            <?php
                                        if(!empty($fsr)){
                                        foreach ($fsr as $data) 
                                          { 
                                             $no++; 
                                    ?>
                                            <?php 
                                                $val=explode('.',$data['creditAdjustment']);
                                                      if($val[1]==00 && $val[0] !=0){
                                            ?>
                                                 <tr style='background-color:white;'>
                                                 <!-- <td><i class='material-icons' style='color:red;font-size:15px'>local_parking</i></td> -->
                                            <?php  }else{ ?>
                                            <!-- <tr> -->

                                                <!-- <td></td> -->
                                            <?php } ?>
                                                <td><?php echo $no;?></td>
                                                <td>
                                                    <?php echo $data['billNo'];?>
                                                </td>
                                                <td><?php 
                                                $retailerName=substr($data['retailerName'], 0, 20);
                                                echo $retailerName;?></td>
                                                <td style="display: none;"></td>
                                                <td style="display: none;"></td>
                                                <td> 
                                                    FSR
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <!-- <td></td>
                                                <td>
                                                <a href="<?php echo base_url().'index.php/manager/SrCheckController/ApprovedFSR/'.$data['id'].'/'.$idAllocated;?>">
                                                    <button style="font-size : 12px;" id="yesBtn" class="btn-primary waves-effect">
                                                    
                                                    <span class="icon-name">
                                                    Yes
                                                    </span>
                                                     </button> 
                                                </a>
                                              <a id="fsr-disap-id" data-toggle="modal" data-target="#fsr_disapprovModal" data-id="<?php echo $data['id']; ?>" data-allocation="<?php echo $idAllocated; ?>" href="#">
                                                <button style="font-size : 12px;" type="" class="btn-primary waves-effect">
                                                    <span class="icon-name">No</span></button> 
                                             </a>
                                                </td> -->
                                           </tr>
                                     <?php 
                                            }
                                           } 
                                     ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                                }else{
                                     if(empty($signed) && empty($srData) && empty($fsr)){
                            ?>
                                <p align="right">
                                    <span id="final-allocated-id" style="display: none"><?php echo $idAllocated; ?></span>
                                     <button id="save-sign-resend-id" class="btn btn-primary m-t-15 waves-effect btn-sm"><span class="icon-name"> Save</span></button>
                                </p>
                               
                            <?php   } 
                                }   
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </section>
    
<div class="container">
  <div class="modal fade" id="srCheckEditModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="mods">
              
          </div>
      </div>
    </div>
  </div>
</div>

<div class="container">
  <div class="modal fade" id="disapprovModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="dis-mods">
              
          </div>
        
      </div>
    </div>
  </div>
</div>

<div class="container">
  <div class="modal fade" id="fsr_disapprovModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="fsr-dis-mods">
              
          </div>
        
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('/layouts/footerDataTable'); ?>
<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>
<script>
 $(document).ready(function(){
    $('#srEdit-id').click(function(){
        var id=$(this).attr('data-id');
        var allocationId=$(this).attr('data-allocated');
        $.ajax({
            url : "<?php echo site_url('manager/SrCheckController/SrCheckEdit');?>",
            method : "POST",
            data : {id: id,allocationId:allocationId},
            success: function(data){
              $('.mods').html(data);
            }
        });
    });
});
</script>

<script>
 $(document).ready(function(){
    $('#disap-id').click(function(){
        var id=$(this).attr('data-id');
        var allocation=$(this).attr('data-allocation');
        $.ajax({
            url : "<?php echo site_url('manager/SrCheckController/Disapproved');?>",
            method : "POST",
            data : {id: id,allocatedId:allocation},
            success: function(data){
              $('.dis-mods').html(data);
            }
        });
    });
});
</script>

<script>
 $(document).ready(function(){
    $('#fsr-disap-id').click(function(){
        var id=$(this).attr('data-id');
        var allocation=$(this).attr('data-allocation');
        $.ajax({
            url : "<?php echo site_url('manager/SrCheckController/Disapproved_FSR');?>",
            method : "POST",
            data : {id: id,allocatedId:allocation},
            success: function(data){
              $('.fsr-dis-mods').html(data);
            }
        });
    });
});
</script>


<script type="text/javascript">
    function updateSRqty(e,id,billId){
        var srQty=$(e).closest('tr').find('input').val();
        if(srQty){
            document.getElementById('srError').innerHTML="";
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('manager/SrCheckController/ReceivedSR');?>",
                data:{"srQty" : srQty,"id" : id,"billId" : billId},
                success: function (data) {
                    parent.$.fn.colorbox.close();window.parent.location.reload(true);
                }  
            });

        }else{
           document.getElementById('srError').innerHTML='Please Enter Qty';
        }
    }

    function debitSRqty(e,id,allocatedId){
        var srQty=$(e).closest('tr').find('input').val();
        if(srQty){
            document.getElementById('srError').innerHTML="";
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('manager/SrCheckController/DebitSR');?>",
                data:{"srQty" : srQty,"id" : id,"allocatedId":allocatedId},
                success: function (data) {
                     document.getElementById('srError').innerHTML=data;
                }  
            });
        }else{
           document.getElementById('srError').innerHTML='Please Enter Qty';
        }
    }

    function removeMe(t) {
        $(t).closest('tr').remove();
    }
</script>

<script type="text/javascript">
    function removeMe(t) {
        $(t).closest('tr').remove();
    }

    function managerStatus(e,id,allocatedId){
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('manager/SrCheckController/ChangeManagerStatus');?>",
                data:{"id" : id,"allocatedId" : allocatedId},
                success: function (data) {
                    window.parent.location.reload(true);
                }  
            });
        }

        $(e).closest('tr').find('#okedit').text('');
    }

    function signedStatus(e,id,allocatedId){
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('manager/SrCheckController/ChangeManagerStatusForSigned');?>",
                data:{"id" : id,"allocatedId" : allocatedId},
                success: function (data) {
                    document.getElementById('err').innerHTML=data;
                }  
            });
        }

        $(e).closest('tr').find('#okedit').text('');
    }

    function signedOkStatus(e,id,allocatedId){
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('manager/SrCheckController/ChangeManagerStatusForSignedOk');?>",
                data:{"id" : id,"allocatedId" : allocatedId},
                success: function (data) {
                    document.getElementById('err').innerHTML=data;
                }  
            });
        }

        $(e).closest('tr').find('#okedit').text('');
    }
</script>
<script type="text/javascript">
    function approveAll(allocatedId){
        var table = document.getElementById('example');
        for (var i = 1; i < table.rows.length-1; i++) {
          if (table.rows[i].cells.length) {
            var id = (table.rows[i].cells[4].textContent.trim());
            var billId = (table.rows[i].cells[5].textContent.trim());
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('manager/SrCheckController/ApprovedSR/');?>"+id+'/'+billId+'/'+allocatedId,
                data:{},
                success: function (data) {
                    window.parent.location.reload(true);
                }  
            });
          }
        }
    }
</script>

<script type="text/javascript">
    function checkCash(pendCash){
        var cash=parseFloat(document.getElementById('cashAmt').value);
        var msg=document.getElementById('sr_qty');
            var pendCash=parseFloat(pendCash);
            if(cash>pendCash){
                msg.innerHTML="Sorry!.. Cash amount is greater than pending amount.";
            }else{
                msg.innerHTML="";
            }
        }
    </script>
<script>
    function allfsrempList(){
        var todo = document.querySelector('#fsrlist');
        var eName = document.getElementById('fsreName').value;
       
        if (eName !== ''){
          todo.innerHTML += '<li class="list-group-item list-group-item-action">' + eName + '<button  style="float: right;" onclick="Delete(this);"><i class="fa fa-close"></i></button> </li>';
          document.getElementById('fsreName').value = "";
        }
    }

    function allsrempList(){
        var todo = document.querySelector('#emp_list');
        var eName = document.getElementById('addeName').value;
        if (eName !== ''){
          todo.innerHTML += '<li class="list-group-item list-group-item-action">' + eName + '<button  style="float: right;" onclick="Delete(this);"><i class="fa fa-close"></i></button> </li>';
          document.getElementById('addeName').value = "";
        }
    }

    function Delete(currentEl){
         currentEl.parentNode.parentNode.removeChild(currentEl.parentNode);
    }

</script>

<script type="text/javascript">
    jQuery("#insert_usr").on("click",function(){
        var emp = new Array();
        var id = $('#bId').val(); 
        var allocatedID=$('#allocatedID').val();
        $("#list li").each(function()
        {
            emp.push($(this).text());
        });
       
        if(emp.length>0){
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('manager/SrCheckController/insertUfsr');?>",
                data:{"empName":emp,"id":id,"allocationId":allocatedID},
                success: function (data) {
                     document.getElementById('res').innerHTML=data;
                }  
            });
        }else{
            alert('Please select Employee.');
        }
    });
</script>

<script type="text/javascript">
    jQuery("#save-sign-resend-id").on("click",function(){
        var allocatedID=$('#final-allocated-id').text();
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('manager/SrCheckController/updateSrBillStatus');?>",
            data:{"allocationId":allocatedID},
            success: function (data) {
                parent.history.back();
                return false;
            }  
        });
    });
</script>

<script type="text/javascript">
    jQuery("#svId").on("click",function(){
        var allocatedID=$('#allocatedHid').val();
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('manager/SrCheckController/saveSrCheck');?>",
            data:{"allocationId":allocatedID},
            success: function (data) {
                parent.history.back();
            }  
        });
    });
</script>

<script type="text/javascript">
    jQuery("#insert_usr").on("click",function(){
        var emp = new Array();
        var id = $('#bId').val(); 
        var allocatedID=$('#allocatedID').val();
        $("#list li").each(function()
        {
            emp.push($(this).text());
        });

        if(emp.length>0){
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('manager/SrCheckController/insertUSR');?>",
                data:{"empName":emp,"id":id,"allocationId":allocatedID},
                success: function (data) {
                    parent.$.fn.colorbox.close();
                    window.parent.location.reload(true);
                }  
            });
        }else{
            alert('Please select Employee.');
        }
    });
</script>

