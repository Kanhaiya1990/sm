<?php $this->load->view('/layouts/commanHeader'); 
    $projectSessionName="";
if (isset($this->session->userdata['codeKeyData'])) {
    $projectSessionName= ($this->session->userdata['codeKeyData']['codeKeyValue']);
}

?>

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
</style>
<!--<section class="content">-->
<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Deliveryman Hisaab Master</h2>
            </div>
              <!-- Basic Examples -->
            <div class="row clearfix" id="page">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                       
                        <div class="body">
                            <form action="<?php echo site_url('fieldStaff/FieldStaffController/insertFinalizeRecord');?>" onsubmit="return chkEmpty();" method="post">
                               
                              <div class="row">
                                    <div class="col-md-12 table-responsive">
                                        <input type="hidden" name="allocationId" value="<?php echo $allocationId;?>">
                                        <input type="hidden" name="allocationCode" value="<?php echo $allocationCode;?>">
                                    <table class="table table-striped table-bordered" border="2px" style="font-size: 12px;width: 80%">
                                        <tr class="gray">
                                            <th><center>Particulars</center></th>
                                            <th><center>Total Bills</center></th>
                                            <th><center>FSR</center></th>
                                            <th><center>Resend</center></th>
                                            <th><center>Delivered</center></th>
                                        </tr>
                                        <tr>
                                            <td><center>No. of Bills</center></td>
                                            <td align="center"><?php echo $billCount;?></td>
                                            <td align="center"><?php echo $srBillCount;?></td>
                                            <td align="center"><?php echo $resendCount;?></td>
                                            <td align="center"><?php echo $billedCount;?></td>
                                        </tr>
                                      <tr class="gray">
                                            <th><center>Particulars</center></th>
                                            <th><center>Total Value</center></th>
                                            <th><center>Cash</center></th>
                                            <th><center>Cheque/NEFT</center></th>
                                            <th><center>Other Adjustment</center></th>
                                            
                                            <th><center>SR/FSR</center></th>
                                            <th><center>Credit</center></th>
                                            <th><center>Resend</center></th>
                                        </tr>
                                        <tr>
                                            <td><center>Amount</center></td>
                                            <td align="center"><?php echo number_format(($pendingTotal+$cashBillTotal+$chequeNeftTotal),2);?></td>
                                            <td align="center"><?php echo number_format($cashBillTotal,2);?></td>
                                            <td align="center"><?php echo number_format($chequeNeftTotal,2);?></td>
                                            <td align="center"><?php echo number_format($otherAdjTotal,2);?></td>
                                            <td align="center"><?php echo number_format($srBillTotal,2);?></td>
                                            <td align="center">
                                                <?php echo number_format($pendingTotal-(($srBillTotal-$creditAdjBillTotal)+$resendTotal),2);?>
                                            </td>
                                            <td align="center"><?php echo number_format($resendTotal,2);?></td>
                                        </tr>
                                    </table>
                                </div>
                                    <div class="col-md-12 table-responsive">
                                        <div class="col-sm-5">
                                            <table class="table table-striped table-bordered" style="font-size: 12px;">
                                            <thead>
                                                <tr>
                                                    <th class="text-xs-center" colspan="3"><center>Total</center></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Cash Collection from Market</td>
                                                    <td id="marketCash"><?php echo number_format($cashBillTotal);?></td>
                                                </tr>
                                                
                                                <tr>
                                                    <td>Market Expense </td>
                                                    <td><span id="total1">0</span></td>
                                                </tr>

                                                <tr>
                                                    <td>Cash to be Submitted</td>
                                                    <td><span id="subCash">0</span></td>
                                                </tr>

                                                <tr>
                                                    <td></td><td></td>
                                                </tr>

                                                <tr>
                                                    <td>Physical cash as per Notes </td>
                                                    <td><span id="calTotal1">0</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span id="status">Short/Excess</span></td>
                                                    <td><span id="diff">0</span></td>
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                        <table class="table table-striped table-bordered" style="font-size: 12px;">
                                            <thead>
                                                <tr>
                                                    <th class="text-xs-center" colspan="4"><center>Expences</center></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><?php echo $firstTitle; ?></td>
                                                        <td>
                                                        <input onkeypress="return isNumber(event)" onkeyup="calc();diff('<?php echo $cashBillTotal;?>');" type="text" name="park" value="0" id="park" autocomplete="off" class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01">
                                                        
                                                    </td>
                                                    
                                                <!-- </tr>
                                                <tr> -->
                                                    <td><?php echo $secondTitle; ?></td>
                                                        <td>
                                                        <input id="cng" onkeypress="return isNumber(event)" onkeyup="calc();diff('<?php echo $cashBillTotal;?>');" type="text" value="0" name="cng" id="cng" autocomplete="off" class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01">
                                                        
                                                    </td>
                                                   
                                                </tr>

                                                 <tr>
                                                    <td><?php echo $thirdTitle; ?></td>
                                                        <td>
                                                        <input onkeypress="return isNumber(event)" onkeyup="calc();diff('<?php echo $cashBillTotal;?>');" type="text" name="challan" id="challan" autocomplete="off" value="0" class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01">
                                                        
                                                    </td>
                                                    
                                               <!--  </tr>
                                                
                                               <tr> -->
                                                    <td>Total </td>
                                                        
                                                    <td class="text-xs-right">
                                                        <span id="total"></span>
                                                    </td>
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                        </div>
                                        <div class="col-sm-7">
                                        <table class="table-striped table-bordered" style="font-size: 12px;">
                                            <thead>
                                                <tr>
                                                    <th class="text-xs-center" colspan="6"><center>Notes</center></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>2000 &nbsp;&nbsp;&nbsp;&nbsp;X</td>
                                                        <td>
                                                        <input onkeypress="return isNumber(event)" onkeyup="calMoney();diff('<?php echo $cashBillTotal;?>');" type="text" name="add2000" id="add2000"autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <span id="ad2000"></span>
                                                    </td>
                                                <!-- </tr>
                                                <tr> -->
                                                    <!-- <td>1000 &nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                                        <td>
                                                        <input onkeypress="return isNumber(event)" onkeyup="calMoney();diff('<?php echo $cashBillTotal;?>');" type="text" name="add1000" id="add1000"autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <span id="ad1000"></span>
                                                    </td> -->

                                                    <td>500 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                                        <td>
                                                        <input onkeypress="return isNumber(event)" onkeyup="calMoney();diff('<?php echo $cashBillTotal;?>');" type="text" name="add500" id="add500"autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <span id="ad500"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    
                                               <!--  </tr>
                                                
                                               <tr> -->
                                                    <td>200 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                                        <td>
                                                        <input onkeypress="return isNumber(event)" onkeyup="calMoney();diff('<?php echo $cashBillTotal;?>');" type="text" name="add200" id="add200"autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <span id="ad200"></span>
                                                    </td>

                                                    <td>100 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                                        <td>
                                                        <input onkeypress="return isNumber(event)" onkeyup="calMoney();diff('<?php echo $cashBillTotal;?>');" type="text" name="add100" id="add100"autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <span id="ad100"></span>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    
                                                <!-- </tr>
                                                 <tr> -->
                                                    <td>50 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                                        <td>
                                                        <input onkeypress="return isNumber(event)" onkeyup="calMoney();diff('<?php echo $cashBillTotal;?>');" type="text" name="add50" id="add50"autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <span id="ad50"></span>
                                                    </td>

                                                    <td>20 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                                        <td>
                                                        <input onkeypress="return isNumber(event)" onkeyup="calMoney();diff('<?php echo $cashBillTotal;?>');" type="text" name="add20" id="add20"autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <span id="ad20"></span>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    
                                                <!-- </tr>
                                                 <tr> -->
                                                    <td>10 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                                        <td>
                                                        <input onkeypress="return isNumber(event)" onkeyup="calMoney();diff('<?php echo $cashBillTotal;?>');" type="text" name="add10" id="add10"autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <span id="ad10"></span>
                                                    </td>

                                                    <td>Coins &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                        <td>
                                                        <input onkeypress="return isNumber(event)" onkeyup="calMoney();diff('<?php echo $cashBillTotal;?>');" type="text" name="coin" id="coin" autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <span id="coins"></span>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    
                                                <!-- </tr>
                                                <tr> -->
                                                    <td>Total &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                        
                                                    <td colspan="2" class="text-xs-right">
                                                        <span id="calTotal"></span>
                                                    </td>
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                     </div>
                                            <left>
                            <?php 
                                $designation = ($this->session->userdata[$projectSessionName]['designation']);
                                $des=explode(',',$designation);

                                if(((in_array('owner', $des) || in_array('senior_manager', $des) || in_array('manager', $des)))){

                            ?>
                                        <button type="submit" id="insert-ins" class="btn btn-primary m-t-15 waves-effect"> <i class="material-icons">save</i><span class="icon-name">Submit Hisab</span></button>
                                   
                                        <a href="<?php echo site_url('AllocationByManagerController/openAllocations'); ?>">
                                           <button type="button" class="btn btn-primary m-t-15 waves-effect"><i class="material-icons">cancel</i><span class="icon-name">Cancel</span></button>
                                        </a>

                                <?php }else{ ?>
                                        <button type="submit" id="insert-ins" class="btn btn-primary m-t-15 waves-effect"> <i class="material-icons">save</i><span class="icon-name">Submit Hisab</span></button>
                                   
                                        <a href="<?php echo site_url('fieldStaff/FieldStaffController/fieldStaff/'.$allocationId); ?>">
                                           <button type="button" class="btn btn-primary m-t-15 waves-effect"><i class="material-icons">cancel</i><span class="icon-name">Cancel</span></button>
                                        </a>
                                <?php } ?>
                                            </left> 
                                    </div>
                                   <div id="insFin"></div>
                                   </div>    
                                    </form>
                     
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Examples --> 
        </div>
</section>

<?php $this->load->view('/layouts/footerDataTable'); ?>

<script type="text/javascript">
    function chkEmpty(){

        var marketCash = document.getElementById('marketCash').innerHTML;
        var calTotal =document.getElementById('calTotal').innerHTML;
        

        marketCash=parseInt(marketCash);
        shortAmt=parseFloat(shortAmt);

        if(marketCash==0 && calTotal ===""){
            return true;
        }else if(marketCash>0 && calTotal ===""){
            alert('Please enter notes details.');
            return false;
        }else if(marketCash>0 && calTotal !==""){
            var shortAmt =document.getElementById('s_diff').innerHTML;
            if(shortAmt>0){
                var msj= confirm('Amount is less market collection. Short amount will be debited to you.');
                if (msj == false) { 
                  return false;
                } else {
                    return true;
                }
            }else{
                return true;
            }
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


    document.getElementById('subCash').innerHTML = parseInt(document.getElementById('marketCash').innerHTML)-cal;
    // var cal1=document.getElementById('total1').innerHTML;
    // var cal2=document.getElementById('calTotal1').innerHTML;
    // var tot=parseFloat(cal1)+parseFloat(cal2);
    // var finalDiff=cash-tot;
   
    // if(finalDiff>0.0){
    //     document.getElementById('diff').innerHTML=finalDiff.toFixed(2)+"    <span style='color: green;'>Excess</span>";
    // }else{
    //     document.getElementById('diff').innerHTML=finalDiff.toFixed(2)+"    <span style='color: red;'>Short</span>";
    // }
    
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
            document.getElementById('diff').innerHTML="<p id='s_diff' style='color:red;font-size:15px;'>"+finalDiff.toFixed(2)+"</span>";;
            document.getElementById('status').innerHTML="<p style='color: red;font-size:15px;'>Short</span>";
        }else{
            document.getElementById('diff').innerHTML="<p id='s_diff' style='color:#037921;font-size:15px;'>"+finalDiff.toFixed(2)+"</p>";
            document.getElementById('status').innerHTML="<p style='color:#037921;font-size:15px;'>Excess</p>";
        }
    }
</script>

<script type="text/javascript">

    function insertData(id,code){
        var n2000=document.getElementById('add2000').value;
        // var n1000=document.getElementById('add1000').value;
        var n500=document.getElementById('add500').value;
        var n200=document.getElementById('add200').value;
        var n100=document.getElementById('add100').value;
        var n50=document.getElementById('add50').value;
        var n20=document.getElementById('add20').value;
        var n10=document.getElementById('add10').value;
        var coin=document.getElementById('coin').value;

        var parking=document.getElementById('park').value;
        var challan=document.getElementById('challan').value;
        var cng=document.getElementById('cng').value;

        $.ajax({
            type: "POST",
            url:"<?php echo site_url('fieldStaff/FieldStaffController/insertFinalizeRecord');?>",
            data:{"allocationId" : id,"allocationCode" : code,"n2000" : n2000,"n500" : n500,"n200" : n200,"n100" : n100,"n50" : n50,"n20" : n20,"n10" : n10,"coin" : coin,"parking" : parking,"challan" : challan,"cng" : cng},
            success: function (data) {
                document.getElementById('insFin').innerHTML=data;
                // window.parent.location.reload(true);
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

  function calMoney() {
    var a2000 = document.getElementById('add2000').value;
    // var a1000 = document.getElementById('add1000').value;
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
    // if(a1000 ==""){
    //     a1000=0;
    // }
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
    // var c2=0;
    // c2=1000*a1000;
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
    // document.getElementById('ad1000').innerHTML = c2;
    document.getElementById('ad500').innerHTML = c3;
    document.getElementById('ad200').innerHTML = c4;
    document.getElementById('ad100').innerHTML = c5;
    document.getElementById('ad50').innerHTML = c6;
    document.getElementById('ad20').innerHTML = c7;
    document.getElementById('ad10').innerHTML = c8;
    document.getElementById('coins').innerHTML = c9;
    var total=0;
    total=total+c1+c3+c4+c5+c6+c7+c8;
    total=parseFloat(total)+parseFloat(c9);
    document.getElementById('calTotal').innerHTML = total.toFixed(2);
    document.getElementById('calTotal1').innerHTML = total.toFixed(2);

    var parking=document.getElementById('park').value;
        var challan=document.getElementById('challan').value;
        var cng=document.getElementById('cng').value;
       var expTotal=parseInt(parking)+parseInt(challan)+parseInt(cng);
       // alert(expTotal); 
    // document.getElementById('calTotal1').innerHTML  marketCash
     document.getElementById('subCash').innerHTML =parseInt(document.getElementById('marketCash').innerHTML)-(expTotal) ;
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

<script type = "text/javascript" >  
    window.history.pushState(null, "", window.location.href);
    window.onpopstate = function () {
        window.history.pushState(null, "", window.location.href);
    };
</script> 