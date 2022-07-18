<?php $this->load->view('/layouts/commanHeader'); ?>

    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
           
            <!-- Masked Input -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                              Add New Bills
                            </h2>
                       
                        </div>
                        <!-- <form method="post" onsubmit="return checkSerial();" role="form" enctype="multipart/form-data" action="<?php echo site_url('AllocationByManagerController/insertAdhocBill'); ?>">  -->
                        <div class="body">
                            <p id="res"></p>
                            <div class="row clearfix">
                            <div class="demo-masked-input">
                                
                                  <div class="col-md-12"> 


                                    <div class="col-md-4" id="empName">
                                    <p>
                                       <b>Company </b>
                                    </p>
                                    <select id="cmpName" name="cmpName" class="form-control">
                                      <option>--Select Company---</option>
                                      <?php foreach ($company as $req_item): ?>
                                        <option value="<?php echo $req_item['name'] ?>"><?php echo $req_item['name'] ?></option>
                                      <?php endforeach ?> 
                                    </select>
                                  </div> 

                                    <div class="col-md-4">
                                        <b>Bill Number</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" onblur="checkBillNo(this);" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" id="billNo" name="billNo" class="form-control date" placeholder="Enter bill number" required>
                                            </div>
                                            <p id="billNo_Id"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <b>Retailer Name</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" autocomplete="off" id="retailerName" name="retailerName" class="form-control date" placeholder="Enter retailer name" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-4">
                                            <b>Net Amount</b>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">account_box</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="text" autocomplete="off" id="netAmount" name="netAmount" class="form-control" onkeypress="return numbersonly(this, event);" placeholder="Enter net amount" required>
                                                </div>
                                            </div>
                                        </div>


                                       <!--  <div class="col-md-4">
                                            <b>Category </b>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">account_box</i>
                                                </span>
                                                <div class="form-line">
                                                   <select id="billOption" onchange="return dropboxCheck();" required class="form-control" name="billOption">
                                                        <option>Select Option</option>
                                                        <option>Leave Unallocated</option>
                                                        <option>Add To Open Allocation</option>
                                                        <option>Ad Hoc Delivery by Employee</option>
                                                        <option>Office Adjustment Bill</option>
                                                    </select><br>
                                                </div>
                                            </div>
                                        </div> -->

                                         <div class="col-md-4">
                                            <p>
                                               <b>Category </b>
                                            </p>
                                            <select id="billOption" onchange="return dropboxCheck();" required class="form-control" name="billOption">
                                                <option>Select Option</option>
                                                <option>Leave Unallocated</option>
                                                <option>Add To Open Allocation</option>
                                                <option>Direct Delivery</option>
                                                <option>Office Adjustment Bill</option>
                                            </select>
                                          </div> 

                                    <div id="empblockDiv" class="col-md-4" style="display: none;">
                                        <b>Employee Name</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" list="elist" autocomplete="off" id="empDetail" name="empDetail" class="form-control date" placeholder="Enter employee name">

                                                <datalist id="elist">
                                                <?php 
                                                    if(isset($employee)){
                                                        foreach($employee as $dt){?>
                                                            <option id="<?php echo $dt['id'];?>" value="<?php echo $dt['name'];?>" />
                                                <?php 
                                                        } 
                                                    }
                                                ?>
                                                </datalist>
                                            </div>
                                        </div>
                                    </div>

                                      <div id="blockDiv" class="col-md-4" style="display: none;">
                                        <b>Allocation Number</b>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">account_box</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="text" list="alList" autocomplete="off" id="allocation" name="allocation" class="form-control date" placeholder="Enter employee name">

                                                    <datalist id="alList">
                                                    <?php 
                                                        if(isset($currentAllocations)){
                                                            foreach($currentAllocations as $dt){?>
                                                                 <option><?php echo $dt['allocationCode'].' : '.$dt['rname']; ?></option>
                                                    <?php 
                                                            } 
                                                        }
                                                    ?>
                                                    </datalist>
                                                </div>
                                            </div>
                                      </div>


                                      <div class="col-md-4" id="adjAmtblockDiv" style="display: none;">
                                            <b>Adjustment Amount</b>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">account_box</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="text" autocomplete="off" id="adjustmentAmount" name="adjustmentAmount" class="form-control date" onkeypress="return numbersonly(this, event);" placeholder="Enter adjustment amount">
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="col-md-12" id="remarkblockDiv" style="display: none;">
                                        <div class="col-md-12" >
                                            <b>Remarks</b>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">account_box</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="text" autocomplete="off" id="remark" name="remark" class="form-control date" placeholder="Enter remark name">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12" id="remarkblockDivOffice" style="display: none;">
                                        <div class="col-md-12" >
                                            <b>Remarks</b>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">account_box</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="text" autocomplete="off" id="remarkOffice" name="remarkOffice" class="form-control date" placeholder="Enter remark name">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                  
                                    <div class="col-md-12">
                                        <div class="row clearfix">
                                            <div class="col-md-4">
                                                <button onclick="checkSerial();" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name">
                                                     Save
                                                    </span>
                                                </button>
                                               <a href="<?php echo site_url('DashbordController');?>">
                                                    <button type="button" class="btn btn-primary m-t-15 waves-effect">
                                                        <i class="material-icons">cancel</i> 
                                                        <span class="icon-name"> Cancel</span>
                                                    </button>
                                                </a> 
                                            </div>

                                        </div>
                                    </div> 

                                      <div class="col-md-12">
                                   <table class="table table-bordered table-striped table-hover dataTable js-exportable" data-page-length='100'>
                                        <!-- <?php print_r($retailer); ?> -->
                                    <thead>
                                        <tr class="gray">
                                            <th> Sr No.</th>
                                             <th> Bill No </th>
                                            <th> Bill Date  </th>
                                            <th> Retailer </th>
                                             <th> Bill Amount </th>
                                             <th> SR </th>
                                             <th> Collection </th>
                                              <th> Pending  </th>
                                            <th> Employee </th>
                                            <th> Status </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <?php
                                            $no=0;
                                            foreach ($adhocBills as $data) 
                                            {
                                               $no++; 
                                              

                                              $allocations=$this->AllocationByManagerModel->getAllocationDetailsByBill('bills',$data['id']);

                                              $allocationsHistory=$this->AllocationByManagerModel->getAllocationDetailsByBillHistory('bills',$data['id']);

                                              $officeAllocations=$this->AllocationByManagerModel->getOfficeAllocationDetailsByBill('bills',$data['id']);

                                              $officeAllocationsHistory=$this->AllocationByManagerModel->getOfficeAllocationDetailsByBillHistory('bills',$data['id']);

                                              $dt=date_create($data['date']);
                                              $createdDate = date_format($dt,'d-M-Y');

                                             
                                            ?>

                                            <?php if($data['isAllocated']==1){ ?>
                                                 <tr style="background-color: #dcd6d5">
                                            <?php }else{ ?>
                                                 <tr>
                                            <?php } ?>
                                             
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $data['billNo']; ?></td>
                                                <td><?php echo $createdDate; ?></td>
                                                <td><?php echo $data['retailerName']; ?></td>
                                                <td><?php echo $data['netAmount']; ?></td>
                                                  <td><?php echo $data['SRAmt']; ?></td>
                                                  <td><?php echo $data['receivedAmt']; ?></td>
                                                <td><?php echo $data['pendingAmt']; ?></td>
                                                <td><?php echo $data['salesman'];; ?></td>
                                                <td>
                                                <?php 

                                                    if($data['isAllocated']==1){
                                                        if(!empty($allocations)){
                                                            echo "<span style='color:blue'>Allocated in : ".$allocations[0]['allocationCode'].'</span>';
                                                        }

                                                        if(!empty($officeAllocations)){
                                                            echo "<span style='color:blue'>Allocated in : ".$officeAllocations[0]['allocationCode'].'</span>';
                                                        }
                                                    }else{
                                                        if(!empty($allocationsHistory) || !empty($officeAllocationsHistory)){
                                                          if(!empty($allocationsHistory)){
                                                            echo "<span style='color:green'>Already Allocated in : ".$allocationsHistory[0]['allocationCode'].'</span>';
                                                          }

                                                          if(!empty($officeAllocationsHistory)){
                                                            echo "<span style='color:green'>Already Allocated in : ".$officeAllocationsHistory[0]['allocationCode'].'</span>';
                                                          }
                                                        }else{
                                                            echo "<span style='color:red'>Leave Unallocated</span>";
                                                        }
                                                ?>
                                                       
                                                    <?php } ?>
                                                </td>
                                              </tr>
                                                <?php
                                             
                                            }
                                            ?> 
                                    </tbody>
                                    <tfoot>
                                        <tr class="gray">
                                            <th> Sr No.</th>
                                             <th> Bill No </th>
                                            <th> Bill Date  </th>
                                            <th> Retailer </th>
                                             <th> Bill Amount </th>
                                             <th> SR </th>
                                             <th> Collection </th>
                                              <th> Pending  </th>
                                            <th> Employee </th>
                                            <th> Status </th>
                                        </tr>
                                    </tfoot>    
                            </table>
                        
                    </div>                                
                                </div>
                            </div>
                        </div>
                        <!-- </form> -->
                    </div>
                </div>
            </div>
            <!-- #END# Masked Input -->
        </div>
    </section>

<?php $this->load->view('/layouts/footerDataTable'); ?>

<script type="text/javascript">
    function dropboxCheck(){
         var billOption=document.getElementById('billOption').value;
         if(billOption==="Add To Open Allocation"){
            document.getElementById('blockDiv').style.display="block";
         }else{
            document.getElementById('blockDiv').style.display="none";
         }

         if(billOption==="Direct Delivery"){
            document.getElementById('empblockDiv').style.display="block";
            document.getElementById('remarkblockDiv').style.display="block";
         }else{
            document.getElementById('empblockDiv').style.display="none";
            document.getElementById('remarkblockDiv').style.display="none";
         }

         if(billOption==="Office Adjustment Bill"){
            document.getElementById('adjAmtblockDiv').style.display="block";
            document.getElementById('remarkblockDivOffice').style.display="block";
         }else{
            document.getElementById('adjAmtblockDiv').style.display="none";
            document.getElementById('remarkblockDivOffice').style.display="none";
         }
    }
</script>

<script>
     function numbersonly(myfield, e){
            var key;
            var keychar;
            if (window.event)
                key = window.event.keyCode;
            else if (e)
                key = e.which;
            else
                return true;

            keychar = String.fromCharCode(key);
            // control keys
            if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
                return true;

            // numbers
            else if ((("0123456789").indexOf(keychar) > -1))
                return true;

            // only one decimal point
            else if ((keychar == "."))
            {
                if (myfield.value.indexOf(keychar) > -1)
                    return false;
            }
            else
                return false;
    }
</script>

<script type="text/javascript">
    function checkSerial(){
        var billNo=document.getElementById('billNo').value;
        var retailerName=document.getElementById('retailerName').value;
        var netAmount=document.getElementById('netAmount').value;
        var cmpName=document.getElementById('cmpName').value;
        var billOption=document.getElementById('billOption').value;
        var empDetail=document.getElementById('empDetail').value;
        var empId = $('#elist').find('option[value="'+empDetail+'"]').attr('id');
        
        var remark=document.getElementById('remark').value;
        var remarkOffice=document.getElementById('remarkOffice').value;

        var adjustmentAmount=document.getElementById('adjustmentAmount').value;


        var allocationType=document.getElementById('allocation').value;
        var ids=document.getElementById('billNo_Id').innerText;

            if(billOption==="Add To Open Allocation" && allocationType==="Select Open Allocation"){
                alert('plz select allocation number');
            }else{
                if(billNo !=="" && netAmount !== "" && retailerName !=="" && cmpName !== "" && billOption!=="Select Option"){
                    $.ajax({
                        type: "POST",
                        url:"<?php echo site_url('AdHocController/insertAdhocBill');?>",
                        data:{"empId":empId,"empDetail":empDetail,"remark":remark,"remarkOffice":remarkOffice,"billNo" : billNo,"retailerName" : retailerName,'adjustmentAmount':adjustmentAmount,'netAmount':netAmount,'cmpName':cmpName,"billOption":billOption,"allocationType":allocationType},
                        success: function (data) {
                            if(data==="Record inserted"){
                                alert(data);
                                window.location.href="<?php echo base_url();?>index.php/AdHocController/adhocBills";
                            }else{
                                alert(data);
                            }
                        }  
                    });
                }else{
                    alert("Please enter all details.");
                }
            }
    }
</script>
<script type="text/javascript">
    function checkBillNo(no)
    {
        var nos=no.value;
        $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('AllocationByManagerController/checkValuesByBillno');?>",
                    data:{"billNo" : nos},
                    success: function (data) {
                        $('#billNo_Id').html('<span style="color: red;">'+data+'</span>');
                    }  
                });
    }
</script>
