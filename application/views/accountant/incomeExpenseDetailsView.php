<?php $this->load->view('/layouts/commanHeader'); ?> 
<style type="text/css">
    @media screen and (min-width: 1100px) {
        .modal-dialog {
          width: 1100px; /* New width for default modal */
        }
        .modal-sm {
          width: 350px; /* New width for small modal */
        }
    }

    @media screen and (min-width: 1100px) {
        .modal-lg {
          width: 1100px; /* New width for large modal */
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

    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br>
    <section class="col-md-12 box">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                             <h2>
                                 <p align="center">Income-Expenses Details For : <?php echo date("D,d-M-Y", strtotime($dates));?></p>
                             </h2>
                         </div>
                    <div class="body">
                            
                    <div class="table-responsive">
                      <div class="col-md-12">
                                <!-- <table style="font-size: 12px;" class="table table-bordered table-striped table-hover" data-page-length='100'>
                                    <thead>
                                        <tr style="background-color: #adc7ec">
                                            <th colspan="<?php echo count($company)+2; ?>"><center><b>Income</b></center></th>
                                        </tr>
                                    </thead>

                                    <thead>

                                      <tr style="background-color: #adc7ec">
                                            <th><b>Nature</b></th>
                                            <th><b>Total</b></th>
                                          <?php foreach($company as $cm){  ?>
                                            <th class="float-right"><?php echo $cm['name']?></th>
                                          <?php } ?>
                                        </tr>
                                        
                                         <tr style="background-color: #adc7ec">
                                            <th><b>Net Income</b></th>
                                        <?php
                                            $no=0;
                                            $totalNetIncome=0; 
                                            foreach($company as $cm){  if(in_array($cm['name'],$cmpExpense[$no])){ 

                                                  $totalNetIncome=$totalNetIncome +($cmpIncome[$no]['income']-$cmpExpense[$no]['expense']);
                                               } 
                                              $no++;
                                            } 
                                        ?>
                                            <th><?php echo $totalNetIncome; ?></th>
                                          <?php
                                            $no=0; 
                                            foreach($company as $cm){  ?>
                                              
                                            <th class="float-right">
                                              <?php if(in_array($cm['name'],$cmpExpense[$no])){ 

                                                  echo ($cmpIncome[$no]['income']-$cmpExpense[$no]['expense']);
                                               } ?> 
                                            </th>
                                          <?php $no++; } ?>

                                        </tr>
                                        

                                    </thead>

                                  </table> -->

                                <table style="font-size: 12px;" class="table table-bordered table-striped table-hover" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th colspan="<?php echo count($company)+2; ?>"><center><b>Income</b></center></th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <!--  <tr style="background-color: #adc7ec">
                                            <th><b>Net Income</b></th>
                                        <?php
                                            $no=0;
                                            $totalNetIncome=0; 
                                            foreach($company as $cm){  if(in_array($cm['name'],$cmpExpense[$no])){ 

                                                  $totalNetIncome=$totalNetIncome +($cmpIncome[$no]['income']-$cmpExpense[$no]['expense']);
                                               } 
                                              $no++;
                                            } 
                                        ?>
                                            <th><?php echo $totalNetIncome; ?></th>
                                          <?php
                                            $no=0; 
                                            foreach($company as $cm){  ?>
                                              
                                            <th class="float-right">
                                              <?php if(in_array($cm['name'],$cmpExpense[$no])){ 

                                                  echo ($cmpIncome[$no]['income']-$cmpExpense[$no]['expense']);
                                               } ?> 
                                            </th>
                                          <?php $no++; } ?>

                                        </tr> -->
                                        <tr>
                                            <th><b>Nature</b></th>
                                            <th class="text-right"><b>Total</b></th>
                                          <?php foreach($company as $cm){  ?>
                                            <!-- <th></th> -->
                                            <th class="text-right"><?php echo $cm['name']?></th>
                                          <?php } ?>
                                        </tr>

                                    </thead>
                                    
                                    <tbody>

                                      <?php foreach($incomeCategory as $in){ ?>
                                        <tr>
                                            <td><?php echo $in['categoryName']; ?></td>
                                            
                                           <td class="text-right">
                                              <?php 
                                                $t=0;
                                                foreach($incomeByType as $itm){  
                                                    if(($in['categoryName']==$itm['type'])){ 
                                                        $value=(int)($itm['income']);
                                                        $t=(int)($t)+(int)($value);
                                                    } 
                                                }
                                                echo number_format($t);
                                               ?> 
                                            </td>
                                           
                                           <?php  
                                           $no=0;
                                            foreach($company as $cmp){
                                              foreach($incomeByType as $itm){  
                                             
                                                    if($itm['company']==$cmp['name'] && ($in['categoryName']==$itm['type'])){
                                                  ?>
                                                      <td align="right"><?php echo number_format((int)$itm['income']); ?></td>
                                                  <?php }
                                                }
                                            } ?>
                                        </tr>
                                      <?php } ?>
                                    </tbody>
                                    <tfoot>
                                      <tr>
                                        <th>Total</th>
                                        <th class="text-right">
                                          <?php
                                            $no=0; 
                                            $total=0;
                                            foreach($company as $cm){ 
                                              
                                           ?>
                                           
                                              <?php if(in_array($cm['name'],$cmpExpense[$no])){ 
                                                  $total=$total+$cmpIncome[$no]['income'];
                                                  
                                               } ?> 
                                          
                                          <?php $no++; } 
                                            echo number_format($total);
                                          ?>
                                        </th>
                                        <?php
                                            $no=0; 
                                           
                                            foreach($company as $cm){ 
                                               $total=0;
                                           ?>
                                            <th class="text-right">
                                              <?php if(in_array($cm['name'],$cmpExpense[$no])){ 
                                                  $total=$total+$cmpIncome[$no]['income'];
                                                  echo number_format($total);
                                               } ?> 
                                            </th>
                                          <?php $no++; } ?>
                                      </tr>
                                    </tfoot>
                                </table>
                            </div>
 <div class="col-md-12"></div> <div class="col-md-12"></div>
                            <div class="col-md-12">
                                <table style="font-size: 12px;" class="table table-bordered table-striped table-hover" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th colspan="<?php echo count($company)+2; ?>"><center><b>Expense</b></center></th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th><b>Nature</b></th>
                                            <th class="text-right"><b>Total</b></th>
                                          <?php foreach($company as $cm){  ?>
                                            <th class="text-right"><?php echo $cm['name']?></th>
                                          <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php foreach($expenseCategory as $in){ ?>
                                        <tr>
                                            <td><?php echo $in['categoryName']; ?></td>

                                            <td align="right">
                                              <?php 
                                                $t=0;
                                                foreach($expenseByType as $itm){  
                                                    if(($in['categoryName']==$itm['type'])){ 

                                                        $value=(int)($itm['expense']);
                                                        $t=(int)($t)+(int)($value);
                                                    } 
                                                }
                                                echo number_format($t);
                                               ?> 
                                            </td>
                                           <?php  
                                           $no=0;
                                            foreach($company as $cmp){
                                              foreach($expenseByType as $itm){  
                                             
                                                    if($itm['company']==$cmp['name'] && $in['categoryName']==$itm['type']){
                                                  ?>
                                                      <td class="text-right"><?php echo number_format((int)$itm['expense']); ?></td>
                                                  <?php }
                                                }
                                              
                                            } ?>
                                        </tr>
                                      <?php } ?>
                                    </tbody>

                                     <tfoot>
                                      <tr>
                                        <th>Total</th>
                                        <th class="text-right">
                                           <?php
                                            $no=0; 
                                             $total=0;
                                            foreach($company as $cm){ 
                                             
                                           ?>
                                            
                                              <?php if(in_array($cm['name'],$cmpExpense[$no])){ 
                                                  $total=$total+$cmpExpense[$no]['expense'];
                                                  
                                               } ?> 
                                            
                                          <?php $no++; } 
                                            echo number_format($total);
                                          ?>
                                        </th>
                                        <?php
                                            $no=0; 
                                            
                                            foreach($company as $cm){ 
                                              $total=0;
                                           ?>
                                            <th class="text-right">
                                              <?php if(in_array($cm['name'],$cmpExpense[$no])){ 
                                                  $total=$total+$cmpExpense[$no]['expense'];
                                                  echo number_format($total);
                                               } ?> 
                                            </th>
                                          <?php $no++; } ?>
                                      </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                            </div><!-- Credit Table-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php $this->load->view('/layouts/footerDataTable'); ?>

   <script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>
<script type="text/javascript">
        function checkNEFTAmt(pendCash){
            var cash=parseFloat(document.getElementById('cashAmt').value);
            var msg=document.getElementById('sr_qty');
            // alert(cash+' '+pendCash);
            var pendCash=parseFloat(pendCash);
            if(cash>pendCash){
                msg.innerHTML="Sorry!.. NEFT amount is greater than pending amount.";
            }else{
                msg.innerHTML="";
            }
        }
        
        function showCombos(){
            var getSelectValue = document.getElementById("nature").value;
            if(getSelectValue == "Other Income"){
              document.getElementById("category").style.display="block";
            }else{
              document.getElementById("category").style.display="none";
             
            }
            if(getSelectValue == "Employee Credit"){
              document.getElementById("empName").style.display="block";
            }else{
              document.getElementById("empName").style.display="none";
            }
        }
    </script>

     <script type="text/javascript">
        function showCombos1(){
            var getSelectValue = document.getElementById("nature1").value;
            
            if(getSelectValue == "Other Expenses"){
              document.getElementById("category1").style.display="block";
            }else{
              document.getElementById("category1").style.display="none";
             
            }
            if(getSelectValue == "Employee Advances"){
              document.getElementById("empName1").style.display="block";
            }else{
              document.getElementById("empName1").style.display="none";
            }
        }
    </script>

    <script type="text/javascript">
      function checkBankDepositAmount(){
          var bankAmount = Number(document.getElementById("calTotal").innerHTML);
          var availableAmount = Number(document.getElementById("availableAmount").innerHTML);
          if(availableAmount<bankAmount){
            alert('Amount is more than remaining amount.');
            return false;
          }else{
            return true;
          }
      }

      function checkRemAmount(){
          var clos_bank_amt = Number(document.getElementById("clos_bank_amt").value);
          var collectedTotal = Number(document.getElementById("collectedTotal").value);
          if(collectedTotal>clos_bank_amt){
            alert('Amount is more than remaining amount.');
            return false;
          }else{
            return true;
          }
      }
    </script>


    <script type="text/javascript">
      function calc() {
        var park = document.getElementById('park').value;
        var challan =document.getElementById('challan').value;
        var cng = document.getElementById('cng').value;
       
        if(park == ""){
            park = 0;
        }
        if(challan == ""){
            challan = 0;
        }
        if(cng == ""){
            cng = 0;
        }
              
        var cal=0;
        var cal =parseFloat(cal)+ parseFloat(park)+parseFloat(challan)+parseFloat(cng);
        if(cal=='')
            cal=0;
        document.getElementById('total').innerHTML = cal.toFixed(2);
        document.getElementById('total1').innerHTML = cal.toFixed(2);
      
      }
    </script>

<script type="text/javascript">
    function diff(cash){
        var cal1=document.getElementById('total1').innerHTML;
        var cal2=document.getElementById('calTotal1').innerHTML;
        if(cal1=='')
            cal1=0;
        if(cal2=='')
            cal2=0;
        var tot=parseFloat(cal1)+parseFloat(cal2);
        var finalDiff=cash-tot;
       
        if(finalDiff>0.0){
            document.getElementById('diff').innerHTML="<span style='color:red;font-size:15px;'>"+finalDiff.toFixed(2)+"</span>";
            document.getElementById('status').innerHTML="<span style='color: red;font-size:15px;'>Short</span>";
        }else{
            document.getElementById('diff').innerHTML="<span style='color:#037921;font-size:15px;'>"+finalDiff.toFixed(2)+"</span>";
            document.getElementById('status').innerHTML="<span style='color:#037921;font-size:15px;'>Excess</span>";
        }
    }
</script>

<script type="text/javascript">
    
    $('#myModal').on('hidden.bs.modal', function () {
      $(this)
          .find("input:not([type=hidden]),textarea,select")
          .val('')
          .end()
          .find("input[type=checkbox], input[type=radio]")
          .prop("checked", "")
          .end();
    });

    $('#myModal1').on('hidden.bs.modal', function () {
      $(this)
          .find("input:not([type=hidden]),textarea,select")
          .val('')
          .end()
          .find("input[type=checkbox], input[type=radio]")
          .prop("checked", "")
          .end();
    });

$(document).on('show.bs.modal','#myModal2', function () {
    // $('#myModal2').on('hidden.bs.modal', function () {
      $(this)
          .find("input:not([type=hidden]),textarea,select")
          .val('')
          .end()
          .find("input[type=checkbox], input[type=radio]")
          .prop("checked", "")
          .end();

         $('#add2000').val('');
         $('#add1000').val('');
         $('#add500').val('');
         $('#add200').val('');
         $('#add100').val('');
         $('#add50').val('');
         $('#add20').val('');
         $('#add10').val('');
         $('#coin').val('');

         $('#ad2000').text('');
         $('#ad1000').text('');
         $('#ad500').text('');
         $('#ad200').text('');
         $('#ad100').text('');
         $('#ad50').text('');
         $('#ad20').text('');
         $('#ad10').text('');
         $('#coins').text('');
    });

    $('#closeDayModal').on('hidden.bs.modal', function () {
      $(this)
          .find("input:not([type=hidden]),textarea,select")
          .val('')
          .end()
          .find("input[type=checkbox], input[type=radio]")
          .prop("checked", "")
          .end();
    });


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
    document.getElementById('calTotal').innerHTML = total.toFixed(2);;
    document.getElementById('calTotal1').innerHTML = total.toFixed(2);;

  }
</script>

<script type="text/javascript">
  function calsMoney() {
    var a2000 = document.getElementById('addd2000').value;
    var a1000 = document.getElementById('addd1000').value;
    var a500 = document.getElementById('addd500').value;
    var a200 = document.getElementById('addd200').value;
    var a100 = document.getElementById('addd100').value;
    var a50 = document.getElementById('addd50').value;
    var a20 = document.getElementById('addd20').value;
    var a10 = document.getElementById('addd10').value;
    var coin = document.getElementById('coin_s').value;

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

    document.getElementById('aad2000').innerHTML = c1;
    document.getElementById('aad1000').innerHTML = c2;
    document.getElementById('aad500').innerHTML = c3;
    document.getElementById('aad200').innerHTML = c4;
    document.getElementById('aad100').innerHTML = c5;
    document.getElementById('aad50').innerHTML = c6;
    document.getElementById('aad20').innerHTML = c7;
    document.getElementById('aad10').innerHTML = c8;
    document.getElementById('coinss').innerHTML = c9;



    var total=0;

    total=total+c1+c2+c3+c4+c5+c6+c7+c8;
    total=parseFloat(total)+parseFloat(c9);
    document.getElementById('calsTotal').innerHTML = total.toFixed(2);
    document.getElementById('collectedTotal').value = total.toFixed(2);

    var clos_bank_amt = Number(document.getElementById("clos_bank_amt").value);
    var collectedTotal = Number(document.getElementById("collectedTotal").value);
    var excessShort=clos_bank_amt-collectedTotal;
    if(excessShort>0){
        document.getElementById('ShortCash').innerText=excessShort.toFixed(2);
        document.getElementById('ExcessCash').innerText="";
    }else{
        document.getElementById('ExcessCash').innerText=excessShort.toFixed(2);
        document.getElementById('ShortCash').innerText="";
    }
   
  }
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


<script type="text/javascript">
    $(function(){
        $('#empName').change(function(){
            var empName = $("#empNameList option[value='" + $('#empName').val() + "']").attr('id');
            $('#empId').val(empName);
        });
    });
</script>

<script type="text/javascript">
    $(function(){
        $('#empNameOutflow').change(function(){
            var empName = $("#empNameOutflowList option[value='" + $('#empNameOutflow').val() + "']").attr('id');
            $('#empOutflowId').val(empName);
        });
    });
</script>

<script type="text/javascript">
    function submitDayBook(){
        var add2000 = document.getElementById('addd2000').value;
        var add1000 = document.getElementById('addd1000').value;
        var add500 = document.getElementById('addd500').value;
        var add200 = document.getElementById('addd200').value;
        var add100 = document.getElementById('addd100').value;
        var add50 = document.getElementById('addd50').value;
        var add20 = document.getElementById('addd20').value;
        var add10 = document.getElementById('addd10').value;
        var coin = document.getElementById('coins').value;

        var clos_bank_amt = Number(document.getElementById("clos_bank_amt").value);
        var collectedTotal = Number(document.getElementById("collectedTotal").value);

        if(collectedTotal>clos_bank_amt){
            alert('Amount is more than remaining amount.');
            die();
        }

        $.ajax({
            type: "POST",
            url:"<?php echo site_url('manager/CashBookController/closeDayBook');?>",
            data:{"add2000" : add2000,"add1000" : add1000,"add500" : add500,"add200" : add200,"add100" : add100,"add50" : add50,"add20" : add20,"add10" : add10,"coins":coin,"collectedTotal":collectedTotal},
            success: function (data) {
                alert(data);
                 location.reload(); 
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
