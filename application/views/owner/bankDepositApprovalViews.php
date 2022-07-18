<?php $this->load->view('/layouts/commanHeader'); ?>

<style type="text/css">
    @media screen and (min-width: 1200px) {
        .modal-dialog {
          width: 1200px; /* New width for default modal */
        }
        .modal-sm {
          width: 350px; /* New width for small modal */
        }
    }

    @media screen and (min-width: 1200px) {
        .modal-lg {
          width: 1200px; /* New width for large modal */
        }
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
        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
               
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Cash Reconciliation Details
                            </h2>
                            <h2>
                                <p align="right">
                                     <button class="btn btn-sm bg-primary margin" onClick="window.location.reload();"><i class="material-icons">refresh</i></button>
                                
                                </p> 
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive tableFixHead">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Date</th>
                                            <th>Employee</th>
                                            <th><span class="pull-right">Total Bank Deposit</span></th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                           <th>S. No.</th>
                                            <th>Date</th>
                                            <th>Employee</th>
                                            <th><span class="pull-right">Total Bank Deposit</span></th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                      <?php
                                        $no=0;
                                        if(!empty($bankDeposit)){
                                          foreach ($bankDeposit as $data) 
                                            {
                                                $no++; 
                                                $dt=date_create($data['date']);
                                                $date = date_format($dt,'d-M-Y H:i:sa');
                                            ?>
                                              <tr>
                                                  <td><?php echo $no; ?></td>
                                                  <td><?php echo $date; ?></td>
                                                  <td><?php echo $data['emp_name']; ?></td>
                                                  <td align="right"><?php echo $data['amount']; ?></td>
                                                  <td>
                                                    <button id="acceptbankDetails" data-id="<?php echo $data['id']; ?>" data-date="<?php echo $date; ?>" data-emp="<?php echo $data['emp_name']; ?>" data-amount="<?php echo $data['amount']; ?>" data-openCloseBalance="<?php echo $data['openCloseBalance']; ?>" class="modalLink btn btn-xs btn-primary waves-effect">
                                                    <span class="icon-name"> <i class="material-icons">check</i></span></button>

                                                    <button id="bankDetails" data-id="<?php echo $data['id']; ?>" data-date="<?php echo $data['date']; ?>" data-emp="<?php echo $data['emp_name']; ?>" data-empId="<?php echo $data['emp_id']; ?>" data-amount="<?php echo $data['amount']; ?>" data-openCloseBalance="<?php echo $data['openCloseBalance']; ?>" data-toggle="modal" data-target="#myModal1" class="modalLink btn btn-xs btn-primary waves-effect">
                                <span class="icon-name"> <i class="material-icons">cancel</i></span>
                              </button>
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


<!-- Add Income -->
<div class="container">
  <div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
           <center> <h3 class="modal-title">Cash Reconciliation</h3> </center>
          </div>
          <div class="modal-body">
        <form method="post" role="form" onsubmit="return submitBankDeposit();" action="<?php echo site_url('owner/ExpensesController/rejectBankDeposit');?>"> 
                                <div class="row clearfix">
                                    <div class="col-md-12">

                                      <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                        <tr>
                                          <td>Date</td>
                                          <td>Employee</td>
                                          <td align="right">As per Accounts </td>
                                          <td align="right">Actual Cash Deposit </td>
                                          <td align="right">Short/Excess </td>
                                       </tr>
                                           <tr>
                                            <td><span id="bankDate"></span></td>
                                            <td><span id="bankEmp"></span></td>
                                            <td align="right"><span id="bankCash"></span></td>
                                            <td>
                                              <input type="text" autocomplete="off" placeholder="enter bank deposit" id="finalBankDeposit" onkeypress="return isNumber(event)" onblur="calAmount()" name="finalBankDeposit" class="form-control" required>
                                            </td>
                                            <td align="right"><span id="shortExcessCash">0</span>
                                              <input type="hidden" autocomplete="off" placeholder="enter bank deposit" id="finalShortExcess" name="finalShortExcess" class="form-control" required>
                                            </td>
                                          </tr>
                                      </table>

                                      <table style="font-size: 12px" id="myTable"  class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                        <tr>
                                          <tr>
                                            <td>Company </td>
                                            <td>Employee </td>
                                            <td>Amount </td>
                                            <td>Category </td>
                                            <td>Narration </td>
                                            <td>Action </td>
                                          </tr>
                                          <td>
                                             <input type="text" autocomplete="off" placeholder="select company" list="compNameOutflowList" id="compNameOutflow" name="compNameOutflow[]" class="form-control" required> 
                                    <datalist id="compNameOutflowList">
                                    <?php foreach ($companyDetails as $req_item): ?>
                                        <option id="<?php echo $req_item['id'] ?>" value="<?php echo $req_item['name'] ?>" />
                                      <?php endforeach ?> 
                                    </datalist> 
                                          </td>

                                           <td>
                                                <input type="text" autocomplete="off" placeholder="select employee" list="empNameOutflowList" id="empNameOutflow" name="empNameOutflow[]" class="form-control" required> 
                                          <datalist id="empNameOutflowList">
                                              <?php foreach ($emp as $req_item): ?>
                                                <option id="<?php echo $req_item['id'] ?>" value="<?php echo $req_item['name'] ?>" />
                                              <?php endforeach ?> 
                                          </datalist>
                                          <input type="hidden" id="empOutflowId" name="empOutflowId[]" class="form-control"> 
                                              </td>

                                          <td>
                                             <input type="text" onkeypress="return isNumber(event)"  autocomplete="off" onblur="calShortAmount()" placeholder="amount" id="cashAmtOutflow" name="cashAmtOutflow[]" class="form-control" required> 
                                          </td>

                                         

                                          <td id="expense"  style="display: block">
                                          <input type="text" autocomplete="off" placeholder="select category" list="categoryOutflowList" id="categoryOutflow" name="categoryOutflow[]" class="form-control" required> 
                                              <datalist id="categoryOutflowList">
                                                <?php foreach($cat_expense as $in){ ?>
                                                        <option><?php echo $in['categoryName']; ?></option>
                                                <?php } ?>
                                     
                                              </datalist>   
                                        </td>
                                        <td id="income" style="display: none">
                                             <input type="text" autocomplete="off" placeholder="select category" list="categoryIncomeList" id="categoryOutflow" name="categoryOutflow[]" class="form-control">
                                              <datalist id="categoryIncomeList">
                                                <?php foreach($cat_income as $in){ ?>
                                                <option><?php echo $in['categoryName']; ?></option>
                                                <?php } ?>
                                              </datalist>  
                                        </td>
                                        <td>
                                          <input type="text" autocomplete="off" placeholder="narration" id="narrationOutflow" name="narrationOutflow[]" class="form-control" required>   
                                        </td>
                                         <td>
                                             <button type="button" onclick="addNewRow();" class="btn btn-xs btn-primary waves-effect">
                                      <span class="icon-name"> <i class="material-icons">add</i></span></button>   

                                      <button type="button" onclick="deleterow();" class="btn btn-xs btn-primary waves-effect">
                                      <span class="icon-name"> <i class="material-icons">remove</i></span></button>
                                          </td>
                                        </tr>
                                      </table>
                                  </div>   
                                </div>
                                <br>
                              
                                <div class="row clearfix">
                                <div class="col-md-12">
                                    
                                        <center>                                               
                                                <button type="submit" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name">
                                                    Save
                                                    </span>
                                                </button> 
                                              
                                                    <button data-dismiss="modal" type="button" class="btn btn-primary m-t-15 waves-effect">
                                                        <i class="material-icons">cancel</i> 
                                                        <span class="icon-name">
                                                        cancel
                                                        </span>
                                                    </button>
                                               
                                        </center>

                                    </div>
                                </div>
                                <input type="hidden" autocomplete="off" placeholder="amount" id="bankDepId" name="bankDepId" class="form-control" required>

                                  <input type="hidden" autocomplete="off" placeholder="amount" id="cashierId" name="cashierId" class="form-control" required>

                                <input type="hidden" autocomplete="off" placeholder="amount" id="bankDepositDate" name="bankDepositDate" class="form-control" required>

                                <input type="hidden" autocomplete="off" placeholder="amount" id="bankDepAmount" name="bankDepAmount" class="form-control" required>

                                <input type="hidden" autocomplete="off" placeholder="amount" id="actualAcceptCash" name="actualAcceptCash" class="form-control" required> 

                                <input type="hidden" autocomplete="off" placeholder="amount" id="short_cash" name="short_cash" class="form-control" required> 
                                
                                <input type="hidden" autocomplete="off" placeholder="inoutStatus" id="inoutStatus" name="inoutStatus" class="form-control" required> 
                               </form>
          </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('/layouts/footerDataTable'); ?>

<script type="text/javascript">
  function acceptExpenses(e,id,allocatedId){
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/acceptExpenseByOwner');?>",
                data:{"id" : id,"allocatedId" : allocatedId},
                success: function (data) {
                  if(data.trim()=="Expenses Accepted"){
                    alert(data);
                    location.reload(); 
                  }else{
                    alert(data);
                  }
                  
                }  
            });
        }
    }

      function rejectExpenses(e,id,allocatedId,empId,parking,challan,cng){
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/rejectExpenseByOwner');?>",
                data:{"id" : id,"allocatedId" : allocatedId,"empId":empId,"parking":parking,"challan":challan,"cng":cng},
                success: function (data) {
                  if(data.trim()=="Expenses Rejected"){
                    alert(data);
                    location.reload(); 
                  }else{
                    alert(data);
                  }
                  
                }  
            });
        }
    }
</script>





<script type="text/javascript">
  function acceptOwExpenses(e,id){
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/acceptOwExpenseByOwner');?>",
                data:{"id" : id},
                success: function (data) {
                  if(data.trim()=="Expenses Accepted"){
                    alert(data);
                    location.reload(); 
                  }else{
                    alert(data);
                  }
                  
                }  
            });
        }
    }

      function rejectOwExpenses(e,id,emp_id,amount,nature){
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/ExpensesController/rejectOwExpenseByOwner');?>",
                data:{"id" : id,"empId":emp_id,"amount":amount,"nature":nature},
                success: function (data) {
                  if(data.trim()=="Expenses Rejected"){
                    alert(data);
                    location.reload(); 
                  }else{
                    alert(data);
                  }
                  
                }  
            });
        }
    }
</script>

<script type="text/javascript">
  function calMoney() {
    var a2000 = document.getElementById('add2000').value;
    var a1000 = document.getElementById('add1000').value;
    var a500 = document.getElementById('add500').value;
    var a200 = document.getElementById('add200').value;
    var a100 = document.getElementById('add100').value;
    var a50 = document.getElementById('add50').value;
    var a20 = document.getElementById('add20').value;
    var a10 = document.getElementById('add10').value;
    var coin = document.getElementById('coin').value;

    if(a2000 ==""){
        a2000=0;
    }
    if(a1000 ==""){
        a1000=0;
    }
    if(a500 ==""){
        a500=0;
    }
    if(a200 ==""){
        a200=0;
    }
    if(a100 ==""){
        a100=0;
    }
    if(a50 ==""){
        a50=0;
    }
    if(a20 ==""){
        a20=0;
    }
    if(a10 ==""){
        a10=0;
    }
    if(coin ==""){
        coin=0;
    }

    var c1=0;
    c1=2000*a2000;
    var c2=0;
    c2=1000*a1000;
    var c3=0;
    c3=500*a500;
    var c4=0;
    c4=200*a200;
    var c5=0;
    c5=100*a100;
    var c6=0;
    c6=50*a50;
    var c7=0;
    c7=20*a20;
    var c8=0;
    c8=10*a10;
    var c9=0;
    c9=coin;

    document.getElementById('ad2000').innerHTML = c1;
    document.getElementById('ad1000').innerHTML = c2;
    document.getElementById('ad500').innerHTML = c3;
    document.getElementById('ad200').innerHTML = c4;
    document.getElementById('ad100').innerHTML = c5;
    document.getElementById('ad50').innerHTML = c6;
    document.getElementById('ad20').innerHTML = c7;
    document.getElementById('ad10').innerHTML = c8;
    document.getElementById('coins').innerHTML = c9;
    var total=0;
    total=total+c1+c2+c3+c4+c5+c6+c7+c8;
    total=parseFloat(total)+parseFloat(c9);
    document.getElementById('calTotal').innerHTML = total.toFixed(2);
     document.getElementById('actualBankDeposit').value = total.toFixed(2);

  }
</script>

<script type="text/javascript">
    $(document).on('change','#empNameOutflow',function(){
          var empName = $("#empNameOutflowList option[value='" + $('#empNameOutflow').val() + "']").attr('id');
          $('#empOutflowId').val(empName);
    });
</script>

<script type="text/javascript">
   $(document).on('click','#bankDetails',function(){
        var id=$(this).attr('data-id');
        var date=$(this).attr('data-date');
        var emp=$(this).attr('data-emp');
        var empId=$(this).attr('data-empId');
        var amount=$(this).attr('data-amount');
        var openCloseBalance=$(this).attr('data-openCloseBalance');

        $('#bankDepId').val(id);
        $('#bankDepAmount').val(amount);
        $('#bankCash').text(amount);
        $('#bankDate').text(date);
        $('#bankEmp').text(emp);
        $('#cashierId').val(empId);
        $('#bankDepositDate').val(date);
        $('#bankDepositAmount').val(amount);

    });
</script>

<script type="text/javascript">
   $(document).on('click','#acceptbankDetails',function(){
        var id=$(this).attr('data-id');

        $.ajax({
            type: "POST",
            url:"<?php echo site_url('owner/ExpensesController/acceptBankDeposit');?>",
            data:{id:id},
            success: function (data) {
                if(data.trim()=="Bank Deposit accepted"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/owner/ExpensesController/bankDepositDetails";
                }else{
                    alert(data);
                }
            }  
        });


        alert(id+' '+date+' '+emp+' '+amount+' '+openCloseBalance); 
       
    });
</script>

<script type="text/javascript">
  function checkBankDepositAmount(){
        var clos_bank_amt = Number(document.getElementById("bankDepositAmount").value);
        var collectedTotal = Number(document.getElementById("actualBankDeposit").value);

        if(collectedTotal>clos_bank_amt){
            alert('Amount is more than Bank Deposit amount.');
           return false;
        }else{
            return true;
        }
  }
  
</script>

<script type="text/javascript">
  function addNewRow(){
    var rowCount = $('#myTable tr').length-1;
    $('#myTable').append('<tr><td><input type="text" autocomplete="off" placeholder="select company" list="compNameOutflowList" id="compNameOutflow" name="compNameOutflow[]" class="form-control" required><datalist id="compNameOutflowList"><?php foreach ($companyDetails as $req_item): ?><option id="<?php echo $req_item['id'] ?>" value="<?php echo $req_item['name'] ?>" /><?php endforeach ?> </datalist> </td><td><input type="text" autocomplete="off" placeholder="select employee" list="empNameOutflowList" id="empNameOutflow" name="empNameOutflow[]" class="form-control" required><datalist id="empNameOutflowList"><?php foreach ($emp as $req_item): ?><option id="<?php echo $req_item['id'] ?>" value="<?php echo $req_item['name'] ?>" /><?php endforeach ?></datalist><input type="hidden" id="empOutflowId" name="empOutflowId[]" class="form-control"></td><td><input type="text" onkeypress="return isNumber(event)"  autocomplete="off" value="0" placeholder="amount" onblur="calShortAmount()" id="cashAmtOutflow" name="cashAmtOutflow[]" class="form-control" required></td><td id="expense"  style="display: block"><input type="text" autocomplete="off" placeholder="select category" list="categoryOutflowList" id="categoryOutflow" name="categoryOutflow[]" class="form-control" required><datalist id="categoryOutflowList"><?php foreach($cat_expense as $in){ ?><option><?php echo $in['categoryName']; ?></option><?php } ?></datalist></td><td id="income" style="display: none"><input type="text" autocomplete="off" placeholder="select category" list="categoryIncomeList" id="categoryOutflow" name="categoryOutflow[]" class="form-control"><datalist id="categoryIncomeList"><?php foreach($cat_income as $in){ ?><option><?php echo $in['categoryName']; ?></option><?php } ?> </datalist> </td><td><input type="text" autocomplete="off" placeholder="narration" id="narrationOutflow" name="narrationOutflow[]" class="form-control" required> </td><td></td></tr>');
  }



  function deleterow() {
    var rowCount = $('#myTable tr').length;
    if(rowCount>1){
        rowCount=rowCount-1;
        document.getElementById("myTable").deleteRow(rowCount); 
    }
  }
</script>

<script type="text/javascript">
    function calAmount(){
        var bankDepAmount=document.getElementById("bankDepAmount").value;
        var finalBankDeposit=document.getElementById("finalBankDeposit").value;
        var total=parseInt(finalBankDeposit);
        var diff=parseInt(bankDepAmount)-parseInt(total);
        document.getElementById("actualAcceptCash").value=parseInt(total);
        if(diff>0){
            document.getElementById("inoutStatus").value="Outflow";
        }else{
            document.getElementById("inoutStatus").value="Inflow";
        }

        if(parseInt(bankDepAmount)==parseInt(finalBankDeposit)){
              alert('Close Popup and accept bank deposit.');
              location.reload(); 
              die();
        }
        
        if(diff>0){
            document.getElementById("shortExcessCash").innerHTML="<span style='color:red'>"+parseInt(diff)+"</span>";
            document.getElementById("short_cash").value=parseInt(diff);
            document.getElementById("finalShortExcess").value=parseInt(diff);
            
        }else{
            document.getElementById("shortExcessCash").innerHTML="<span style='color:blue'>"+parseInt(diff)+"</span>";
            document.getElementById("short_cash").value=parseInt(diff);
            document.getElementById("finalShortExcess").value=parseInt(diff);
        }

        if(diff>0){
            document.getElementById('expense').style.display="block";
            document.getElementById('income').style.display="none";
        }else{
            document.getElementById('expense').style.display="none";
            document.getElementById('income').style.display="block";
        }
    }
</script>

<script type="text/javascript">
   function calShortAmount(){
        var bankDepAmount=document.getElementById("bankDepAmount").value;
        var finalBankDeposit=document.getElementById("finalBankDeposit").value;
        var finalShortExcess=document.getElementById("finalShortExcess").value;
        if(finalShortExcess==""){
            alert('please enter bank deposit');
        }else{
            var cashAmtOutflow=document.getElementsByName("cashAmtOutflow[]");
            var total=0;
            for (var i = 0, iLen = cashAmtOutflow.length; i < iLen; i++) {
              total=parseInt(total)+parseInt(cashAmtOutflow[i].value);
            }
        }
       
    }

    function submitBankDeposit(){
        var bankDepAmount=document.getElementById("bankDepAmount").value;
        var finalBankDeposit=document.getElementById("finalBankDeposit").value;
        var finalShortExcess=document.getElementById("finalShortExcess").value;
        if(finalShortExcess==""){
            alert('please enter bank deposit');
        }else{
            var cashAmtOutflow=document.getElementsByName("cashAmtOutflow[]");
            var total=0;
            for (var i = 0, iLen = cashAmtOutflow.length; i < iLen; i++) {
              total=parseInt(total)+parseInt(cashAmtOutflow[i].value);
            }

            var short=finalShortExcess-total;
            if(short>0 || short<0){
                alert("plsease enter amount with equal to short amount :"+finalShortExcess);
                return false;
            }else{
                return true;
            }
        }
    }
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $("input:text").focus(function() { $(this).select(); } );
    });
</script>

<script>
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 45 || charCode > 57) ) {
            return false;
        }
        return true;
    }
</script>
