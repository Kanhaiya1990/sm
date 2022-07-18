<?php $this->load->view('/layouts/commanHeader'); ?>

<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>
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
                             Cash & Cheque Master
                          </h2>
                        </div>
                        <div class="body">
                          <div align="right">
                            <a href="">
                            <button  onclick="totalCollect('<?php echo $notes[0]['id'];?>','<?php echo $notes[0]['allocationId'];?>');" class="btn btn-primary m-t-15 waves-effect">
                                <span class="icon-name"> Save </span>
                            </button>
                          </a> 
                          <!-- <a href=""> -->
                            <!-- onclick="cashChequeStatus('<?php echo $notes[0]['id'];?>','<?php echo $notes[0]['allocationId'];?>');"  -->
                            <button data-toggle="modal" data-aId="<?php echo $notes[0]['id'];?>" data-id="<?php echo $notes[0]['allocationId'];?>" data-target="#myModal" class="modalLink btn btn-primary m-t-15 waves-effect">
                                <span class="icon-name"> Save & Confirm </span>
                            </button>
                          <!-- </a>  -->
                        </div>
                            <div class="table-responsive">
                                <div class="col-md-12">
                                  <div class="col-md-7">
                                <table style="font-size: 11px" class="table table-bordered table-striped table-hover display nowrap" data-page-length='100'>
                                    <thead>
                                    <tr>
                                        <th colspan="4"><center>Cheque/NEFT Reconciliation</center></th>
                                    </tr>
                                    <tr>
                                        <th>Retailer Name</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                        foreach($bills as $itm){
                                          if(($itm['fsChequeAmt'] !=0.00 || $itm['fsNeftAmt'] !=0.00) && ($itm['statusLostChequeNeft'] !=1 && $itm['statusLostChequeNeft'] !=2)){
                                      ?>  
                                      <tr>  
                                        <td><?php echo $itm['retailerName']?></td>
                                        
                                        <?php if($itm['fsbillStatus'] !='NEFT'){?>
                                          <td><?php echo $itm['fsChequeAmt']?></td>
                                          <td><?php echo 'Cheque';?></td>
                                          <td>
                                          <button style="font-size: 11px" id="chequeReceived" onclick="removeMe(this);updateChequeNeftRec(this,'<?php echo $itm['id'];?>','<?php echo $itm['fsChequeAmt']?>');" class="btn btn-primary btn-sm waves-effect">
                                                    <span class="icon-name">Received</span>
                                          </button> 
                                          <button style="font-size: 11px" onclick="removeMe(this);updateChequeNeft(this,'<?php echo $itm['id'];?>','<?php echo $itm['fsChequeAmt']?>')" id="chequeNotReceived" class="btn btn-primary btn-sm waves-effect">
                                              <span class="icon-name">Not Received</span>
                                          </button> 
                                          <!-- <div id="res"></div> -->
                                        </td>
                                        <?php }else{?>
                                           <td><?php echo $itm['fsNeftAmt']?></td>
                                          <td><?php echo $itm['fsbillStatus']?></td>
                                          <td>
                                          <button style="font-size: 11px" id="chequeReceived" onclick="removeMe(this);updateChequeNeftRec(this,'<?php echo $itm['id'];?>','<?php echo $itm['fsChequeAmt']?>');" class="btn btn-primary btn-sm waves-effect">
                                                    <span class="icon-name">Received</span>
                                          </button> 
                                          <button style="font-size: 11px" onclick="removeMe(this);updateChequeNeft(this,'<?php echo $itm['id'];?>','<?php echo $itm['fsNeftAmt']?>')" id="chequeNotReceived" class="btn btn-primary btn-sm waves-effect">
                                              <span class="icon-name">Not Received</span>
                                          </button> 
                                          <!-- <div id="res"></div> -->
                                        </td>
                                        <?php }?>
                                        <!-- <td>
                                          <button style="font-size: 11px" id="chequeReceived" onclick="removeMe(this);" class="btn btn-primary btn-sm waves-effect">
                                                    <span class="icon-name">Received</span>
                                          </button> 
                                          <button style="font-size: 11px" onclick="updateChequeNeft(this,'<?php echo $itm[0]['id'];?>','<?php echo $itm[0]['']?>')" id="chequeNotReceived" class="btn btn-primary btn-sm waves-effect">
                                              <span class="icon-name">Not Received</span>
                                          </button> 
                                          <div id="res"></div>
                                        </td> -->
                                      </tr>  
                                      <?php
                                          }
                                        }
                                      ?>
                                    </tbody>
                                </table>
                            </div>
                                
                            <div class="col-md-5">
                                <table style="font-size: 11px" class="table table-bordered table-striped table-hover js-basic-example DataTable display nowrap" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th colspan="2"><center>Other Expenses</center></th>
                                            <th colspan="2"><center>Status</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php 
                                      if(!empty($notes)){
                                       
                                          if(($notes[0]['statusParking'] != 1 && $notes[0]['statusParking'] != 2)){

                                       ?>

                                        <tr>
                                            <td>Parking</td>
                                            <td>
                                              <input style="height:25px;width: 50%" onChange="return expVal()"  onkeypress="return isNumber(event);" type="text" id="prk" name="prk" value="<?php if(!empty($notes)){echo $notes[0]['parking'];} ?>">
                                              </td>
                                            <td>
                                              <?php if(!empty($notes)){ ?>
                                                <button style="font-size: 11px" id="parkingReceive" onclick="received('parking','<?php echo $notes[0]['parking'];?>');removeMe(this);updateAllow('parking','<?php echo $notes[0]['id'];?>','<?php echo $notes[0]['allocationId'];?>');" class="btn btn-primary btn-sm waves-effect">
                                                    <span class="icon-name">Allow</span>
                                                </button> 
                                                <button style="font-size: 11px" id="parkingNotReceive" onclick="notReceived('parking','<?php echo $notes[0]['parking'];?>');removeMe(this);updateDisAllow('parking','<?php echo $notes[0]['id'];?>','<?php echo $notes[0]['allocationId'];?>');" class="btn btn-primary btn-sm waves-effect">
                                                    <span class="icon-name">Disallow</span>
                                                </button> 
                                                <?php }else{?>
                                                  <button style="font-size: 11px" id="parkingReceive" onclick="removeMe(this);" disabled="" class="btn btn-primary btn-sm waves-effect">
                                                    <span class="icon-name">Allow</span>
                                                </button> 
                                                <button style="font-size: 11px" id="parkingNotReceive" onclick="removeMe(this);" disabled="" class="btn btn-primary btn-sm waves-effect">
                                                    <span class="icon-name">Disallow</span>
                                                </button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php    
                                          }
                                        } 

                                        if(!empty($notes)){
                                          if(($notes[0]['statusCng'] != 1 && $notes[0]['statusCng'] != 2)){
                                    ?>
                                        <tr>
                                            <td>CNG</td>
                                             <td>
                                              <input style="height:25px;width: 50%"  onkeypress="return isNumber(event);" onChange="return expVal()" type="text" id="cngValAmt" name="cngValAmt" value="<?php if(!empty($notes)){echo $notes[0]['cng'];} ?>">
                                              </td>
                                             <td>
                                               <?php if(!empty($notes)){ ?>
                                                <button style="font-size: 11px" id="cngReceived" onclick="received('cng','<?php echo $notes[0]['cng'];?>');removeMe(this);updateAllow('cng','<?php echo $notes[0]['id'];?>','<?php echo $notes[0]['allocationId'];?>');" class="btn btn-primary btn-sm waves-effect">
                                                    <span class="icon-name">Allow</span>
                                                </button> 
                                                <button style="font-size: 11px" id="cngNotReceived" onclick="notReceived('cng','<?php echo $notes[0]['cng'];?>');removeMe(this);updateDisAllow('cng','<?php echo $notes[0]['id'];?>','<?php echo $notes[0]['allocationId'];?>');" class="btn btn-primary btn-sm waves-effect">
                                                    <span class="icon-name">Disallow</span>
                                                </button> 
                                                 <?php }else{?>
                                                     <button style="font-size: 11px" id="cngReceived" onclick="removeMe(this);" disabled="" class="btn btn-primary btn-sm waves-effect">
                                                    <span class="icon-name">Allow</span>
                                                  </button> 
                                                  <button style="font-size: 11px" id="cngNotReceived" onclick="removeMe(this);" disabled="" class="btn btn-primary btn-sm waves-effect">
                                                      <span class="icon-name">Disallow</span>
                                                  </button> 
                                                  <?php } ?>
                                            </td>
                                        </tr>
                                    <?php
                                          }
                                        }  
                                      if(!empty($notes)){
                                          if(($notes[0]['statusChallan'] !=1 && $notes[0]['statusChallan'] != 2)){
                                    ?>    
                                        <tr>
                                            <td>Challan</td>
                                            <td>
                                              <input style="height:25px;width: 50%" onChange="return expVal()" onkeypress="return isNumber(event);" type="text" id="clnValAmt" name="clnValAmt" value="<?php if(!empty($notes)){echo $notes[0]['challan'];} ?>">
                                              </td>
                                             <td>
                                              <?php if(!empty($notes)){ ?>
                                                <button style="font-size: 11px" id="challanReceive" onclick="received('challan','<?php echo $notes[0]['challan'];?>');removeMe(this);updateAllow('challan','<?php echo $notes[0]['id'];?>','<?php echo $notes[0]['allocationId'];?>');" class="btn btn-primary btn-sm waves-effect">
                                                    <span class="icon-name">Allow</span>
                                                </button> 
                                                <button style="font-size: 11px" id="challanNotReceive" onclick="notReceived('challan','<?php echo $notes[0]['challan'];?>');removeMe(this);updateDisAllow('challan','<?php echo $notes[0]['id'];?>','<?php echo $notes[0]['allocationId'];?>');" class="btn btn-primary btn-sm waves-effect">
                                                    <span class="icon-name">Disallow</span>
                                                </button> 
                                                 <?php }else{?>
                                                    <button style="font-size: 11px" id="challanReceive" onclick="removeMe(this);" disabled="" class="btn btn-primary btn-sm waves-effect">
                                                    <span class="icon-name">Allow</span>
                                                </button> 
                                                <button style="font-size: 11px" id="challanNotReceive" onclick="removeMe(this);" disabled="" class="btn btn-primary btn-sm waves-effect">
                                                    <span class="icon-name">Disallow</span>
                                                </button> 
                                                 <?php } ?>
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

                          <div class="col-md-6">
                                <table style="font-size: 11px" class="table table-bordered table-striped table-hover js-basic-example DataTable display nowrap" id="example" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th colspan="2"><center>Cash and Cheque Reconciliation</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                          <tr>
                                            <td>Number of Cheques/NEFT</td>
                                            <td align="right" id="chequeTotal"><?php if(!empty($chequeTotal)){echo $chequeCount;} ?></td>
                                        </tr>
                                        <tr>
                                            <td>Cash as per Accounts</td>
                                            <td align="right" id="cashTotal"><?php if(!empty($cashTotal)){echo $cashTotal;} ?></td>
                                        </tr>
                                        <tr>
                                            <td>Other Expenses</td>
                                            <td align="right" id="expenses">
                                              <?php if(!empty($expenses))
                                              {
                                                echo $expenses;
                                              } ?></td>
                                            
                                        </tr>
                                        <tr>
                                            <td>Physical Cash</td>
                                            <td align="right"><span id="phyCash"></span></td>
                                            
                                        </tr>
                                        <tr>
                                            <td>Cash Already Taken</td>
                                            <td align="right"><span id="cashTaken"><?php if(!empty($notes)){echo $notes[0]['collectedAmt'];} ?></span></td>
                                            
                                        </tr>
                                        <tr>
                                            <td>Balance Amount</td>
                                            <td align="right"><span id="balAmt">
                                              <?php if(!empty($notes)){echo $notes[0]['balanceAmt'];} ?>
                                            </span></td>
                                            
                                        </tr>
                                        
                                        <tr>
                                            <td id="shortCashStatus">Short Cash</td>
                                            <td align="right" id="shortCash"><?php if(!empty($notes)){echo $notes[0]['balanceAmt'];} ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                                
                            <div class="col-md-6">
                                <table style="font-size: 11px" class="table table-bordered table-striped table-hover js-basic-example DataTable" data-page-length='100'>
                                    <thead>
                                    <tr>
                                        <th><center>Denominations</center></th>
                                        <th><center>As per Supplier</center></th>
                                        <th><center>Value</center></th>
                                        <th><center>Received by Accountant</center></th>
                                        <th><center>Value</center></th>
                                    </tr>
                                    </thead>
                                     <tbody>
                                    <tr>
                                        <td align="right">2000</td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['note2000'];} ?></td>
                                         <td align="right">
                                        <?php if(!empty($notes)){echo ($notes[0]['note2000'] *2000);} ?>
                                         </td>
                                        <td><input style="height:25px;width: 40%" onkeypress="return isNumber(event)" onkeyup="calMoney();" type="text" name="add2000" id="add2000"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad2000"></span></td>
                                    </tr>
                                     <tr>
                                        <td align="right">1000</td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['note1000'];} ?></td>
                                          <td align="right">
                                        <?php if(!empty($notes)){echo ($notes[0]['note1000'] *1000);} ?>
                                         </td>
                                        <td><input style="height:25px;width: 40%" onkeypress="return isNumber(event)" onkeyup="calMoney();" type="text" name="add1000" id="add1000"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad1000"></span></td>
                                    </tr>
                                     <tr>
                                        <td align="right">500</td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['note500'];} ?></td>
                                          <td align="right">
                                        <?php if(!empty($notes)){echo ($notes[0]['note500'] *500);} ?>
                                         </td>
                                        <td><input style="height:25px;width: 40%" onkeypress="return isNumber(event)" onkeyup="calMoney();" type="text" name="add500" id="add500"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad500"></span></td>
                                    </tr>
                                    <tr>
                                        <td align="right">200</td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['note200'];} ?></td>
                                          <td align="right">
                                        <?php if(!empty($notes)){echo ($notes[0]['note200'] *200);} ?>
                                         </td>
                                        <td><input style="height:25px;width: 40%" onkeypress="return isNumber(event)" onkeyup="calMoney();" type="text" name="add200" id="add200"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad200"></span></td>
                                    </tr>
                                     <tr>
                                        <td align="right">100</td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['note100'];} ?></td>
                                          <td align="right">
                                        <?php if(!empty($notes)){echo ($notes[0]['note100'] *100);} ?>
                                         </td>
                                        <td><input style="height:25px;width: 40%" onkeypress="return isNumber(event)" onkeyup="calMoney();" type="text" name="add100" id="add100"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad100"></span></td>
                                    </tr>
                                     <tr>
                                        <td align="right">50</td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['note50'];} ?></td>
                                         <td align="right">
                                        <?php if(!empty($notes)){echo ($notes[0]['note50'] *50);} ?>
                                         </td>
                                        <td><input style="height:25px;width: 40%" onkeypress="return isNumber(event)" onkeyup="calMoney();" type="text" name="add50" id="add50"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad50"></span></td>
                                    </tr>
                                     <tr>
                                        <td align="right">20</td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['note20'];} ?></td>
                                         <td align="right">
                                        <?php if(!empty($notes)){echo ($notes[0]['note20'] *20);} ?>
                                         </td>
                                        <td><input style="height:25px;width: 40%" onkeypress="return isNumber(event)" onkeyup="calMoney();" type="text" name="add20" id="add20" autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad20"></span></td>
                                    </tr>
                                    <tr>
                                        <td align="right">10</td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['note10'];} ?></td>
                                          <td align="right">
                                        <?php if(!empty($notes)){echo ($notes[0]['note10'] *10);} ?>
                                         </td>
                                        <td><input style="height:25px;width: 40%" onkeypress="return isNumber(event)" onkeyup="calMoney();" type="text" name="add10" id="add10"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="ad10"></span></td>
                                    </tr>
                                    <tr>
                                        <td align="center">Coins</td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['coins'];} ?></td>
                                         <td align="right"><?php if(!empty($notes)){echo $notes[0]['coins'];} ?></td>
                                        <td><input style="height:25px;width: 40%" onkeypress="return isNumber(event)" onkeyup="calMoney();" type="text" name="coin" id="coin"autocomplete="off" class="form-control">
                                        </td>
                                         <td align="right"><span id="coins"></span></td>
                                    </tr>
                                     <tr>
                                        <td colspan="2" align="center">Total Currency</td>
                                        <td align="right"><?php if(!empty($total)){ echo number_format($total,2);}?></td>
                                        <td align="center">Total Actual</td>
                                        <td align="right"><span id="totalActual"></span></td>
                                    </tr>

                                    <div id="emp"></div>
                                   
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                            </div><!-- table 1 end-->

                           
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </section>

<div class="container">
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        
      </div>
    </div>
  </div>
</div>

<!-- <script type="text/javascript">
    $(document).ready(function () {
        $("#addNewRow").click(function () {
            $("#myRowTable").append("<tr><td>row</td><td><input type='text'></td></tr>");
        });
    });   
</script> -->
<?php $this->load->view('/layouts/footerDataTable'); ?>
<script>
    function addNewRow(){
        var empName=$('#empCm').find('option:selected').text();
        var empId=$('#empCm').find('option:selected').val();
        // $("#myRowTable").append("<input type='hidden' name='empId[]' value='"+empId+"'>");
        var chk=false;
         $('table tr').each(function(){
            if($(this).find('td').eq(0).text() == empName){
                chk=true;
            }
        });
        if(!chk){
          $("#myRowTable").append("<tr><td>"+empName+"</td><td><input id='empAmt' type='text' name='empAmt[]'><td><input type='hidden' name='empId[]' value="+empId+"></td><td><button style='float: right;' onclick='Delete(this);'><i class='fa fa-close'></i></button></td></tr>");
        }
        
    }

    function Delete(t){
         $(t).closest('tr').remove();
    }

    function calEmpAmt() {
      var tot=0.0;
      var arr =$('input[name="empAmt[]"]').map(function () {
                      tot=parseFloat(tot)+parseFloat(this.value);
                      return this.value;
              }).get();
      var totalAmt=$('#totalCalAmt').val();
      if(tot==totalAmt){
          $('#err').text("");
          return true;
      }else{
         $('#err').text("Total debit should be equal to employee wise total");
         return false;
      }
    }
</script>

<script>
    function received(category,amt){
        
    }

    function notReceived(category,amt){
        var expenses=document.getElementById('expenses').innerText;
        if(expenses==''){
          expenses=0;
        }
        expenses=parseFloat(expenses).toFixed(2);
        expenses=parseFloat(expenses) - parseFloat(amt);
        document.getElementById('expenses').innerHTML=expenses;

        var shortCash=document.getElementById('shortCash').innerText;
        if(shortCash==''){
          shortCash=0;
        }
        shortCash=parseFloat(shortCash).toFixed(2);
        shortCash=parseFloat(amt) + parseFloat(shortCash);

       

        if(shortCash >0){
           document.getElementById('shortCash').innerHTML="<span id='shCash' style='color:red;font-size:11px;'>"+shortCash.toFixed(2)+"</span>";
          document.getElementById('shortCashStatus').innerHTML="<span style='color: red;font-size:11px;'>Short Cash</span>";
        }else if(shortCash <= 0){
           document.getElementById('shortCash').innerHTML="<span id='shCash' style='color: #037921;font-size:11px;'>"+shortCash.toFixed(2)+"</span>";
          document.getElementById('shortCashStatus').innerHTML="<span style='color: #037921;font-size:11px;'>Excess Cash</span>";
        }
    }  

    function chequeReceived(amt){
      
    }

    function chequeNotReceived(amt){
      alert(amt);
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

    var shortCash=document.getElementById('shortCash').innerText;
    
    if(shortCash==''){
      shortCash=0;
    } 
    
    document.getElementById('totalActual').innerHTML = total.toFixed(2);
    document.getElementById('phyCash').innerHTML = total.toFixed(2);
    
    var expenses=document.getElementById('expenses').innerText;
    
    if(expenses==''){
      expenses=0;
    }
    expenses=parseFloat(expenses).toFixed(2);

    var cashtotal=document.getElementById('cashTotal').innerText;
   
    var takenCashtotal=document.getElementById('balAmt').innerText;
    
  
    if(cashtotal==''){
      cashtotal=0;
    }
    var short=0.0;
    var finalshortCash=0.0;
    if(takenCashtotal!='' && takenCashtotal!='0.00'){
      takenCashtotal=parseFloat(takenCashtotal).toFixed(2);
      finalshortCash=(parseFloat(takenCashtotal)-parseFloat(total));
    }else{
      cashtotal=parseFloat(cashtotal).toFixed(2);
      short=parseFloat(cashtotal)-parseFloat(expenses);
      finalshortCash=(parseFloat(short)-parseFloat(total));
    }

     

   if(finalshortCash >0){
         document.getElementById('shortCash').innerHTML="<span id='shCash' style='color:red;font-size:11px;'>"+finalshortCash.toFixed(2)+"</span>";
        document.getElementById('shortCashStatus').innerHTML="<span  style='color: red;font-size:11px;'>Short Cash</span>";
      }else if(finalshortCash <= 0){
         document.getElementById('shortCash').innerHTML="<span id='shCash' style='color: #037921;font-size:11px;'>"+finalshortCash.toFixed(2)+"</span>";
        document.getElementById('shortCashStatus').innerHTML="<span style='color: #037921;font-size:11px;'>Excess Cash</span>";
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
    function removeMe(t) {
        $(t).closest('tr').remove();
    }
</script>

<script>
  function expVal(){
    var prkValAmt=document.getElementById('prk').value;
    var cngValAmt=document.getElementById('cngValAmt').value;
    var clnValAmt=document.getElementById('clnValAmt').value;
    var total=parseFloat(prkValAmt)+parseFloat(cngValAmt)+parseFloat(clnValAmt);
   
    document.getElementById('expenses').innerHTML=total;

    var short=document.getElementById('shCash').innerHTML;
    var phyCash=document.getElementById('phyCash').innerHTML;
    var expenses=document.getElementById('expenses').innerHTML;
    var cashTotal=document.getElementById('cashTotal').innerHTML;
    
    if(phyCash!=''){
      var someCash=parseFloat(phyCash)+parseFloat(total);
      someCash=parseFloat(cashTotal)-someCash;
      document.getElementById('shortCash').innerHTML=someCash;

      if(someCash >0){
         document.getElementById('shortCash').innerHTML="<span id='shCash' style='color:red;font-size:11px;'>"+someCash.toFixed(2)+"</span>";
        document.getElementById('shortCashStatus').innerHTML="<span  style='color: red;font-size:11px;'>Short Cash</span>";
      }else if(someCash <= 0){
         document.getElementById('shortCash').innerHTML="<span id='shCash' style='color: #037921;font-size:11px;'>"+someCash.toFixed(2)+"</span>";
        document.getElementById('shortCashStatus').innerHTML="<span style='color: #037921;font-size:11px;'>Excess Cash</span>";
      }
    }
  }

</script>

<script>
  function cngVal(parkVal){
    // var val="new";
    var val=document.getElementById('cngValAmt').value;
    if(val < parkVal){
      var total=parkVal-val;
      var expenses=document.getElementById('expenses').innerText;
      if(expenses==''){
        expenses=0;
      }
      expenses=parseFloat(expenses).toFixed(2);
     
      expenses=parseFloat(expenses)-parseFloat(total);
      document.getElementById('expenses').innerHTML=expenses;
    }else{
      var total=val-parkVal;
      var expenses=document.getElementById('expenses').innerText;
      if(expenses==''){
        expenses=0;
      }
      expenses=parseFloat(expenses).toFixed(2);
      
      expenses=parseFloat(expenses) + parseFloat(total);
      document.getElementById('expenses').innerHTML=expenses;
    }
  }
</script>

 <script type="text/javascript">
  function updateChequeNeft(e,id,amt){
      $.ajax({
          type: "POST",
          url:"<?php echo site_url('manager/CashBookController/changeStatusLostChequeNeft');?>",
          data:{"id" : id,"amt" : amt},
          success: function (data) {
            $('#res').html(data);
          }  
      });
  }

  function updateChequeNeftRec(e,id,amt){
      $.ajax({
          type: "POST",
          url:"<?php echo site_url('manager/CashBookController/changeStatusRecChequeNeft');?>",
          data:{"id" : id,"amt" : amt},
          success: function (data) {
            $('#res').html(data);
          }  
      });
  }

  function updateAllow(category,id,allocationId){
      
     $.ajax({
          type: "POST",
          url:"<?php echo site_url('manager/CashBookController/changeStatusAllow');?>",
          data:{"id" : id,"allocationId" : allocationId,"category" : category},
          success: function (data) {
            $('#res').html(data);
          }  
      });
  }

  function updateDisAllow(category,id,allocationId){
     $.ajax({
          type: "POST",
          url:"<?php echo site_url('manager/CashBookController/changeStatusDisAllow');?>",
          data:{"id" : id,"allocationId" : allocationId,"category" : category},
          success: function (data) {
            $('#res').html(data);
          }  
      });
  }

  function totalCollect(id,allocationId){
    var phyCash=document.getElementById('phyCash').innerText;
    var expenses=document.getElementById('expenses').innerText;
    var cashTotal=document.getElementById('cashTotal').innerText;
    
    $.ajax({
          type: "POST",
          url:"<?php echo site_url('manager/CashBookController/updateCashValues');?>",
          data:{"id" : id,"allocationId" : allocationId,"phyCash" : phyCash,"expenses":expenses,"cashTotal" :cashTotal},
          success: function (data) {
            $('#emp').html(data);
          }  
      });
  }

  // function cashChequeStatus(id,allocationId){
  //   var short=document.getElementById('shortCash').innerHTML;
  //   alert(short);
  //   die();
  //   if(parseFloat(short)>0.00){
  //     $.ajax({
  //         type: "POST",
  //         url:"<?php echo site_url('manager/CashBookController/debitAmoutToEmp');?>",
  //         data:{"id" : id,"allocationId" : allocationId,"short" : short},
  //         success: function (data) {
  //            window.parent.location.reload(true);
  //         }  
  //     });
  //   }else if(parseFloat(short)==0.00){
  //       $.ajax({
  //         type: "POST",
  //         url:"<?php echo site_url('manager/CashBookController/cashChequeStatus');?>",
  //         data:{"id" : id,"allocationId" : allocationId},
  //         success: function (data) {
  //            window.parent.location.reload(true);
  //         }  
  //     });
  //   }
  
  // }
  
 </script>

 

 <script>
 $(document).ready(function(){
    $('.modalLink').click(function(){
        var short=document.getElementById('shortCash').innerHTML;
        var cashTaken=document.getElementById('cashTaken').innerHTML;

        var phyCash=document.getElementById('phyCash').innerText;
        var expenses=document.getElementById('expenses').innerText;
        var cashTotal=document.getElementById('cashTotal').innerText;
    

        var id=$(this).attr('data-id');
        var aId=$(this).attr('data-aId');
        $.ajax({
            url : "<?php echo site_url('manager/CashBookController/debitAmoutToEmp');?>",
            method : "POST",
            data : {id:id,aId:aId,short: short,cashTaken:cashTaken,phyCash:phyCash,expenses:expenses,cashTotal:cashTotal},
            success: function(data){
              if(!$.trim(data)){
                $('#myModal').modal('hide');
                var redirect_to="<?php echo site_url('AllocationByManagerController/openAllocations');?>";
                window.location.href = redirect_to;
              }else{
                 $('.modal-content').html(data);
              }
             
            }
        });
    });
});
</script>



