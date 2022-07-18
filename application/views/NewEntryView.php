<?php $this->load->view('/layouts/commanHeader'); ?>

<style type="text/css">
.selectStyle select {
 background-color: darkgray;
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
.highlight{
    background-color: #333;
    color: #fff;    
}

.button-add {
    cursor:pointer;
}

</style>

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

<style>

  .btn:hover, .btn:focus {
    font-weight: bolder;
    outline: 10px solid blanchedalmond;
    border-style: hidden;
    box-shadow: 0 0 2px 2px red;
  color: black;
  }
</style>

<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
<section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
    <!-- <section class="content"> -->
        <div class="container-fluid">
            
            <div class="row clearfix" id="page">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                             New Cheque 
                         </h2>
                         <p align="right">

                         <?php if($this->session->userdata['workRestrict']['status']=="yes"){?>
                            <button class="modalLink btn btn-primary m-t-15 waves-effect" onclick="planUpgradeMsg()">
                                <span class="icon-name"> <i class="material-icons">add</i> Ad Hoc Cheques </span>
                              </button>
                         <?php }else{ ?>
                            <button data-toggle="modal" data-target="#myModal" class="modalLink btn btn-primary m-t-15 waves-effect">
                                <span class="icon-name"> <i class="material-icons">add</i> Ad Hoc Cheques </span>
                              </button>
                         <?php } ?>
                            
                          </p>
                         
                     </div>
                     <div id="infoMessage"><?php echo $message;?></div>
                     <div class="body">
                      <div class="row">                                 
                        <div class="row m-t-20">
                            <div class="col-md-9">
                                <div class="table-responsive">
                            <?php if($this->session->flashdata('msg')){ $msg=$this->session->flashdata('msg'); ?>
                                  <div class="alert alert-success">      
                                    <?php echo $msg['message'];?>
                                  </div>
                            <?php } ?> 

                            <?php if($this->session->flashdata('msgerr')){ $msg=$this->session->flashdata('msgerr'); ?>
                                  <div class="alert alert-danger">      
                                    <?php echo $msg['message'];?>
                                  </div>
                            <?php } ?> 
                                  <!--   <table class="table table-striped table-bordered dataTable" data-page-length='100' id="tbl"> -->
                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100' id="tbl">
                                        <thead>
                                            
                                            <tr class="gray">
                                                <th> S.No.</th>
                                                <th style="display: none"> ID</th>
                                                <th style="display: none"> Bill Amount</th>
                                                <th> Bill No.</th>
                                                <th> Date</th>
                                                <th> Retailer Name</th>
                                                <th> Payment Mode</th>
                                                <th> Cheque/NEFT Amount</th>
                                                <th style="display:none;"><?php echo $data['compName']; ?></th>
                                                 <th></th>
                                                 <th>Split</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no=0;
                                            foreach ($bills as $data) 
                                            {
                                              $id=$data['id'];  
                                              $no++; 

                                                $start = strtotime(date('Y-m-d'));
                                                $end = strtotime($data['date']);
                                                $hiDateDiff = ceil(abs($end - $start) / 86400);

                                              ?>
                                               <?php if($hiDateDiff > $highlightValue){ ?>
                                                        <tr style="background-color: #c8e090">
                                                <?php }else{ ?>
                                                        <tr>
                                                <?php } ?>
                                                 <td><?php echo $no; ?></td>
                                                 <td id="bpayId" style="display: none"><?php echo $data['bpayId']; ?></td>
                                                 <td style="display: none"><?php echo $data['netAmount']; ?></td>
                                                 <td><?php echo $data['billNo']; ?></td>
                                                 <td><?php
                                                 $dt=date_create($data['date']);
                                                 $data['date'] = date_format($dt,'d-M-Y');
                                                 echo $data['date']; ?></td>
                                                 <td><?php echo $data['retailerName']; ?></td>
                                                 <td id="payMode"><?php echo $data['payMode']; ?></td>
                                                 <td align="right"><?php echo $data['paidAmt']; ?></td>
                                                 <td style="display:none;"><?php echo $data['compName']; ?></td>
                                                 <td></td>
                                                 <td>
                                                   <a id="limit_id" href="javascript:void();" data-toggle="modal" data-id="<?php echo $data['bpayId']; ?>" data-target="#updatelimitModal">
                                                    <i class="material-icons" style="color: blue;">call_split</i>
                                                 </td>
                                               </tr>
                                               <?php
                                           }
                                           ?> 
                                       </tbody>
                                      
                                   </table>
                               </div>
                           </div>
                           <div class="col-md-3 table-responsive">

                            
                               <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-xs-center" colspan="4"> New Entry</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-xs-right">                                    
                                            <label>Bill No :</label>
                                            <input type="text" name="billno1[]" id="billno"  class="form-control" required>
                                            <input type="hidden" name="billId1[]" id="id" class="form-control">
                                            <input type="hidden" name="billPaymentId1[]" id="billPaymentId" class="form-control">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-xs-right">
                                            <label> Amount :</label>
                                            <input type="hidden" name="billAmt1[]" id="billAmount" class="form-control">
                                            <input type="text" onkeypress="return numbersonly(this, event);" name="billAmount1" id="billAmount2" class="form-control">
                                        </td>
                                    </tr> 
                                    <tr>
                                        <td class="text-xs-right">
                                            <label>Retailer :</label>
                                            <input type="text" name="retailer1" id="retailer" placeholder="Enter Retailer Name" class="form-control" required>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-xs-right">
                                            <label>Cheque No :</label>
                                            <input type="text" onkeypress="return numbersonly(this, event);" autocomplete="off" name="chequeNo1" id="chequeNo" placeholder="Enter Cheque No." onkeyup="checkChars(); return false;" class="form-control">
                                            <div id="error-nwl"></div>
                                            <div style="color:red" id="dupchkerr"></div>
                                        </td>
                                    </tr>
                                     <tr>
                                        <td class="text-xs-right">
                                            <label>NEFT No :</label>
                                            <input type="text" autocomplete="off" name="neftNo1" id="neftNo" placeholder="Enter NEFT No." onkeyup="checkNEFT(); return false;" class="form-control">
                                            <div id="error-nwl1"></div>
                                            <div style="color:red" id="dupeneftkerr"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-xs-right">
                                            <label>Cheque/NEFT Date :</label>
                                            <input type="date" name="chequeDate1" onblur="dupNEFT();" id="theDate" class="form-control" required>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-xs-right">
                                            <label>Bank Name  :</label>
                                            <input type="text" onblur="dupCheque();" autocomplete="off" name="chequeBank1" id="chequeBank" placeholder="Enter Bank Name" list="bnk" class="form-control">
                                            <datalist id="bnk">
                                                <?php
                                                    foreach($banks as $data){
                                                        // $billNo=$data['billNo'];
                                                    $banks=$data['name'];
                                                ?>   
                                                <option value="<?php echo $banks;?>"/>
                                                <?php    
                                                    }
                                                ?>
                                            </datalist>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-xs-right">
                                            <label>Division :</label>
                                            <input type="text" autocomplete="off" name="company1" id="company" placeholder="Enter Division" list="comp" class="form-control" required>
                                            <datalist id="comp">
                                                <?php
                                                    foreach($company as $data){
                                                        // $billNo=$data['billNo'];
                                                    $comp=$data['name'];
                                                ?>   
                                                <option value="<?php echo $comp;?>"/>
                                                <?php    
                                                    }
                                                ?>
                                            </datalist>
                                            <input type="hidden" autocomplete="off" name="mode1" id="mode"   class="form-control">
                                            <input type="hidden" autocomplete="off" name="allIDs1" id="allIDs" class="form-control">
                                            <input type="hidden" autocomplete="off" name="billModes1" id="billModes" class="form-control">
                                        </td>
                                    </tr>                                      
                                   
                                    <tr>
                                        <td class="text-xs-right">
                                        <?php if($this->session->userdata['workRestrict']['status']=="yes"){?>
                                            <button type="button" class="btn btn-primary waves-effect" onclick="planUpgradeMsg()"> Add </button>  
                                        <?php }else{ ?>
                                            <button type="button" id="sbDataInsert" class="btn btn-primary waves-effect"> Add </button>  
                                        <?php } ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                       
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
</div>
<!-- #END# Basic Examples --> 
<!-- </div> -->
</section>



<!-- Add Income -->
<div class="container">
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
           <center> <h3 class="modal-title">Ad Hoc Cheque</h3>  </center>
          </div>
          <div class="modal-body">
            <div class="row clearfix">
         <div class="col-md-12 table-responsive">

                            <!-- <form method="post" role="form" action="<?php echo site_url('CashAndChequeController/updateAddHocNewEntry'); ?>">  -->
                               
                               <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-xs-center" colspan="4"> New Ad Hoc Cheque Entry</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-xs-right">                                    
                                            <label>Bill No :</label>
                                            <input type="text" autocomplete="off" name="billno[]" id="billnos"  class="form-control" required>
                                            <input type="hidden" autocomplete="off" name="billId[]" id="id" class="form-control">
                                        </td>
                                        <td class="text-xs-right">
                                            <label> Amount :</label>
                                            <input type="hidden" name="billAmt[]" id="billAmount" class="form-control">
                                            <input type="text" onkeypress="return numbersonly(this, event);" autocomplete="off" name="billAmount" id="billAmounts" class="form-control" required>
                                        </td>
                                    </tr>
                                  
                                    <tr>
                                        <td class="text-xs-right">
                                            <label>Retailer :</label>
                                            <input type="text" autocomplete="off" name="retailer" id="retailers" placeholder="Enter Retailer Name" class="form-control" required>

                                        </td>
                                        <td class="text-xs-right">
                                            <label>Cheque No :</label>
                                            <input type="text" onkeypress="return numbersonly(this, event);" autocomplete="off" name="chequeNo" id="chequeNos" placeholder="Enter Cheque No." onkeyup="checkChars(); return false;" class="form-control" required>
                                            <div id="error-nwls">
                                                <div style="color:red;" id="chkNoErr"></div> 
                                        </td>
                                    </tr>
                                   
                                     <tr>
                                       
                                        <td class="text-xs-right">
                                            <label>Cheque :</label>
                                            <input type="date" name="chequeDate" id="theDates" class="form-control" required>

                                        </td>

                                        <td class="text-xs-right">
                                            <label>Bank Name  :</label>
                                            <input type="text" onblur="dupChequeEntry();" autocomplete="off" name="chequeBank" id="chequeBanks" placeholder="Enter Bank Name" list="bnk" class="form-control" required>
                                            <datalist id="bnk">
                                                            <?php
                                                                foreach($banks as $data){
                                                                    // $billNo=$data['billNo'];
                                                                $banks=$data['name'];
                                                            ?>   
                                                            <option value="<?php echo $banks;?>"/>
                                                            <?php    
                                                                }
                                                            ?>
                                                        </datalist>
                                        </td>
                                    </tr>
                                   
                                    <tr>
                                        

                                         <td class="text-xs-right">
                                            <label>Company :</label>
                                            <input type="text" autocomplete="off" name="company" id="companys" placeholder="Enter Company" list="comp" class="form-control" required>
                                            <datalist id="comp">
                                                <?php
                                                    foreach($company as $data){
                                                        // $billNo=$data['billNo'];
                                                    $comp=$data['name'];
                                                ?>   
                                                <option value="<?php echo $comp;?>"/>
                                                <?php    
                                                    }
                                                ?>
                                            </datalist>
                                            <input type="hidden" autocomplete="off" name="mode" id="modes"   class="form-control">
                                            <input type="hidden" autocomplete="off" name="allIDs" id="allIDss" class="form-control">
                                            <input type="hidden" autocomplete="off" name="billModes" id="billModess" class="form-control">
                                            
                                            
                                        </td>
                                    </tr>
                                                                    
                                    <tr style="display: none;"> 
                                        <td>
                                            <input type="checkbox" checked disabled class="filled-in" id="basic_checkbox_1" value="yes" name="newCheque" />
                                            <label for="basic_checkbox_1">New Cheque?</label>
                                        </td> 
                                    </tr>
                                    <tr>
                                        <td class="text-xs-right">
                                            <button type="button" onclick="submitAdHocCheques();" id="insert-more" class="btn btn-success margin btn-sm"> Add </button>

                                            <button data-dismiss="modal" type="button" class="btn btn-danger  waves-effect">
                                            <span class="icon-name">cancel</span>
                                        </button>
                                            <!-- <button type="button" onclick="clearText();" class="btn btn-success margin btn-sm"> Clear </button>  --> 
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                       <!-- </form> -->
                        </div>
                   </td></tr></div>     
          </div>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="updatelimitModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
            <center><h4 class="modal-title">Split Cheque's/NEFT's</h4></center>
          </div>
          <div id="up_limitid" class="modal-body">
            
                                
                                
       
          </div>
      </div>
    </div>
  </div>

<?php $this->load->view('/layouts/footerDataTable'); ?>
  <script type="text/javascript">
    $(document).on('click','#limit_id',function(){
         var id=$(this).attr('data-id');
         // alert(id);die();
         $.ajax({
            url : "<?php echo site_url('CashAndChequeController/splitCheques');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
              // alert(data);die();
                $('#up_limitid').html(data);
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
    });

</script>

<script type="text/javascript">
  function addNewRow(){
    var rowCount = $('#myTable tr').length-1;
    $('#myTable').append('<tr><td><input type="text" id="amount2" name="amount[]" autocomplete="off" placeholder="amount" class="form-control"></td><td><input type="text" name="chequeNo[]" autocomplete="off" placeholder="chequeNo" class="form-control"></td><td><input type="date" name="date[]" autocomplete="off" placeholder="cheque date" class="form-control"></td><td><input type="text" name="bank[]" autocomplete="off" list="bnkList" placeholder="bank name" class="form-control"></td><tr>');
  }

  function deleterow() {
    var rowCount = $('#myTable tr').length;
    if(rowCount>2){
        rowCount=rowCount-1;
        document.getElementById("myTable").deleteRow(rowCount); 
    }
  }
</script>

<script type="text/javascript">
  function addNewNeftRow(){
    var rowCount = $('#myTable tr').length-1;
    $('#myTable').append('<tr><td><input type="text" onkeypress="return numbersonly(this, event);" id="neftAmt1" name="amount[]" autocomplete="off" placeholder="amount" class="form-control" required></td><td><input type="text" id="neftNo1" name="chequeNo[]" autocomplete="off" placeholder="neft no" class="form-control" required></td><td><input type="date" id="neftDate1" name="date[]" onblur="dupNeftEntry1();" autocomplete="off" placeholder="cheque date" class="form-control" required><div style="color:red;" id="neftNoErr1"></div></td></tr>');
  }

  function deleterow() {
    var rowCount = $('#myTable tr').length;
    if(rowCount>2){
        rowCount=rowCount-1;
        document.getElementById("myTable").deleteRow(rowCount); 
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
            if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) || (key==45))
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
  function checkAmount(){
      var billpaymentAmount = parseInt(document.getElementById('billpaymentAmount').value);

      var names = document.getElementsByName('amount[]');
      var total=0;
      for (var i = 0, iLen = names.length; i < iLen; i++) {
        total=total+parseInt(names[i].value);
        // alert(names[i].value);
      }

      // var amount = document.getElementById('chAmt1').value;
      // var amount1 = document.getElementById('chAmt2').value;

      // var total=parseInt(amount)+parseInt(amount1);
      
      if(billpaymentAmount === total){
        return true;
      }else{
        alert('Collected amount should be equal to Cheque amount.');
        return false;
      }
  }

  function checkNeftAmount(){
      var billpaymentAmount = parseInt(document.getElementById('billpaymentAmount').value);

      var names = document.getElementsByName('amount[]');
      var total=0;
      for (var i = 0, iLen = names.length; i < iLen; i++) {
        total=total+parseInt(names[i].value);
        // alert(names[i].value);
      }

      // var amount = document.getElementById('chAmt1').value;
      // var amount1 = document.getElementById('chAmt2').value;

      // var total=parseInt(amount)+parseInt(amount1);
      
      if(billpaymentAmount === total){
        return true;
      }else{
        alert('Collected amount should be equal to NEFT amount.');
        return false;
      }
  }
</script>


<script>
    function changeStatus(id)
    { 
        swal({
          title: 'Select Status',
          input: 'select',
          inputOptions: {
            'bank': 'bank'
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
    if (result.value) {
            // swal({
            //   type: 'success',
            //   html: 'You selected: ' + result.value+" id "+id
            // });
            $.ajax({
                url: "<?php echo site_url('CashAndChequeController/updateStatusDesktopBill');?>",
                type: "post",
                data:{"id" : id , "chequeStatus" : result.value},
                success: function (response) {
                    $('#changeStatus').html(response);  
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
(function () {
    if (window.addEventListener) {
        window.addEventListener('load', run, false);
    } else if (window.attachEvent) {
        window.attachEvent('onload', run);
    }

    var total=0;
    var click=0;
    function run() {
        var t = document.getElementById('tbl');
        t.onclick = function (event) {
            event = event || window.event; //IE8
            var target = event.target || event.srcElement;
            while (target && target.nodeName != 'TR') { 
                target = target.parentElement;
            }            
            var cells = target.cells; 
            if (!cells.length || target.parentNode.nodeName == 'THEAD') {
                return;
            }
            tick= '&#x2714';

            var mode = document.getElementById('mode');
            if(mode.value===''){
                 mode.value=mode.value+cells[6].innerHTML;
            }
           
            if(cells[6].innerHTML === mode.value){
                var billAmt = document.getElementById('billAmount');
                var f = document.getElementById('id');
                var f1 = document.getElementById('billno');
                var f2 = document.getElementById('billAmount2');
                var f3 = document.getElementById('retailer');
                var f4 = document.getElementById('company');
                var billPaymentId = document.getElementById('billPaymentId');

                var allIDs = document.getElementById('allIDs');
                 var billModes = document.getElementById('billModes');

                if(cells[9].innerHTML!="✔"){
                    billAmt.value=billAmt.value + cells[7].innerHTML+",";
                    f.value =f.value + cells[1].innerHTML+ ",";
                    f1.value =f1.value + cells[3].innerHTML+",";
                    f3.value = f3.value+cells[5].innerHTML+",";
                    billPaymentId.value=billPaymentId.value+cells[1].innerHTML+",";
                    str=f4.value+cells[8].innerHTML+",";
                    str = str.replace(/[ ]/g,"").split(",");
                    var result = [];
                    for(var i =0; i < str.length ; i++){
                        if(result.indexOf(str[i]) == -1) 
                            result.push(str[i]);
                    }
                    f4.value=result;
                    cells[9].innerHTML =tick;
                    f2.value = parseInt(cells[7].innerHTML);
                    total=total+parseInt(f2.value);
                    var nf = new Intl.NumberFormat();
                    f2.value=nf.format(total);

                    allIDs.value=allIDs.value+''+cells[1].innerHTML+',';
                    billModes.value=cells[6].innerHTML;
                }else{
                    var remBillId =cells[1].innerHTML+',';
                    // f1.value =  f1.value.replace(remBillId,'');
                    var remBill =cells[3].innerHTML+',';
                    f1.value =  f1.value.replace(remBill,'');
                    var remRetailer =cells[5].innerHTML+',';
                    f3.value =  f3.value.replace(remRetailer,'');
                    cells[9].innerHTML ="";
                    f2.value = parseInt(cells[7].innerHTML);
                    total=total-parseInt(f2.value);
                    var nf = new Intl.NumberFormat();
                    f2.value=nf.format(total);

                    var remAllId =cells[1].innerHTML+',';
                    allIDs.value=allIDs.value.replace(remAllId,'');

                    var bpayId =cells[1].innerHTML+',';
                    billPaymentId.value =  billPaymentId.value.replace(bpayId,'');

                    var billMode =cells[6].innerHTML;
                    billModes.value=billModes.value.replace(billMode,'');
                }
            }

            if(f1.value===""){
                mode.value="";
            }

            if(mode.value==="Cheque"){
                document.getElementById('neftNo').disabled=true;
                document.getElementById('chequeNo').disabled=false;
                document.getElementById('chequeBank').disabled=false;
                document.getElementById('theDate').disabled=false;
            } 
            if(mode.value==="NEFT"){
                document.getElementById('neftNo').disabled=false;
                document.getElementById('chequeNo').disabled=true;
                document.getElementById('chequeBank').disabled=true;
                document.getElementById('theDate').disabled=false;
            }
            
        };

    }

})();
</script>

<script type="text/javascript">

function dupCheque(){
  var date = document.getElementById('theDate').value;
    var chequeNo = document.getElementById('chequeNo').value;
    var neftNo = document.getElementById('neftNo').value;
    var bank = document.getElementById('chequeBank').value;
    var billAmount = document.getElementById('billAmount2').value;


    if((chequeNo !="" && neftNo == "" && bank !="") || (chequeNo =="" && neftNo != "")){
        $.ajax(
        {
            type:"post",
            url: "<?php echo base_url(); ?>index.php/CashAndChequeController/checkChequeDetails",
            data:{chequeDate:date,chequeNo:chequeNo,payAmount:billAmount},
            success:function(response)
            {
                if(response !=""){
                  document.getElementById('dupchkerr').innerHTML= response;
                  return false;
                }else{
                  document.getElementById('dupchkerr').innerHTML = '';
                  return true;
                }
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
      alert('Please enter all details');
      return false;
    }
}

function dupNEFT(){
  var date = document.getElementById('theDate').value;
    var chequeNo = document.getElementById('chequeNo').value;
    var neftNo = document.getElementById('neftNo').value;
    var bank = document.getElementById('chequeBank').value;
    var billAmount = document.getElementById('billAmount2').value;


    if((chequeNo !="" && neftNo == "" && bank !="") || (chequeNo =="" && neftNo != "")){
        $.ajax(
        {
            type:"post",
            url: "<?php echo base_url(); ?>index.php/CashAndChequeController/checkNeftDetails",
            data:{chequeDate:date,neftDate:date,neftNo:neftNo,chequeNo:chequeNo,payAmount:billAmount},
            success:function(response)
            {
                if(response !=""){
                  document.getElementById('dupeneftkerr').innerHTML= response;
                  return false;
                }else{
                  document.getElementById('dupeneftkerr').innerHTML = '';
                  return true;
                }
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
}

  function checkEmptyString(){
    // alert('heyyyy');die();
    var date = document.getElementById('theDate').value;
    var chequeNo = document.getElementById('chequeNo').value;
    var neftNo = document.getElementById('neftNo').value;
    var bank = document.getElementById('chequeBank').value;
    var billAmount = document.getElementById('billAmount2').value;

    var dupeneftkerr = document.getElementById('dupeneftkerr').innerHTML;
    var dupchkerr = document.getElementById('dupchkerr').innerHTML;

    if((dupeneftkerr !="") || (dupchkerr !="")){
      return false;
    }

    if((chequeNo !="" && neftNo == "" && bank !="") || (chequeNo =="" && neftNo != "")){

        if((chequeNo !="" && neftNo == "" && bank !="") || (chequeNo =="" && neftNo != "")){
          $.ajax(
          {
              type:"post",
              url: "<?php echo base_url(); ?>index.php/CashAndChequeController/checkChequeDetails",
              data:{chequeDate:date,chequeNo:chequeNo,payAmount:billAmount},
              success:function(response)
              {
                  if(response !=""){
                    alert(response);
                    // document.getElementById('dupchkerr').innerHTML= response;
                    return false;
                  }else{
                    document.getElementById('dupchkerr').innerHTML = '';
                    return true;
                  }
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
      // return true;
    }else{
      alert('Please enter all details');
      return false;
    }
    
  }
</script>

<script>
    var date = new Date();
    var day = date.getDate();
    var month = date.getMonth() + 1;
    var year = date.getFullYear();

    if (month < 10) month = "0" + month;
    if (day < 10) day = "0" + day;
    var today = year + "-" + month + "-" + day;
    document.getElementById('theDate').value = today;
</script>
<script type="text/javascript">
    function clearText(){
        document.getElementById('billno').value="";
        document.getElementById('billAmount2').value="";
        document.getElementById('company').value="";
        document.getElementById('chequeNo').value="";
        document.getElementById('chequeBank').value="";
        document.getElementById('paidAmount').value="";
    }
</script>

<script>
    $("#paidAmount").on('keyup', function(){
        var n = parseInt($(this).val().replace(/\D/g,''),10);
        $(this).val(n.toLocaleString());
    });
</script>

<script>
function checkChars()
{
    var chequeNo = document.getElementById('chequeNo').value;
    var chequeNos = document.getElementById('chequeNos').value;
    // alert(chequeNos);die();
    var message = document.getElementById('error-nwl');
    var messages = document.getElementById('error-nwls');
    var goodColor = "#66cc66";
    var badColor = "#ff6666";
    
    if(chequeNo.length > 6 || chequeNos.length > 6)
    {
        message.style.color = badColor;
        messages.style.color = badColor;
        message.innerHTML = "Please enter only 6 digit!";
        messages.innerHTML = "Please enter only 6 digit!";
        return;
    }else{
        message.innerHTML = ""
        messages.innerHTML = ""
        return;
    }
}
</script>

<script>
function checkNEFT()
{
    var neftNo = document.getElementById('neftNo').value;
    // alert()
    var message = document.getElementById('error-nwl1');
    var goodColor = "#66cc66";
    var badColor = "#ff6666";
    
    if(neftNo.length < 6)
    {
        message.style.color = badColor;
        message.innerHTML = "Please enter atleast 6 alphanumerical!"
        return;
    }else{
        message.innerHTML = ""
        return;
    }
}
</script>


<script type="text/javascript">
function dupChequeEntry(){
    var date = document.getElementById('theDates').value;
    var chequeNo = document.getElementById('chequeNos').value;
    var bank = document.getElementById('chequeBanks').value;
    var billAmount = document.getElementById('billAmounts').value;
    var message = document.getElementById('chkNoErr').innerText;

    if((chequeNo !="" && bank !="" && billAmount !="")){
        $.ajax(
        {
            type:"post",
            url: "<?php echo base_url(); ?>index.php/CashAndChequeController/checkChequeDetails",
            data:{chequeDate:date,chequeNo:chequeNo,payAmount:billAmount},
            success:function(response)
            {
              if(response !=""){
                document.getElementById('chkNoErr').innerText = response;
                return false;
              }else{
                document.getElementById('chkNoErr').innerText = '';
                return true;
              }
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
      alert('Please enter all details');
      return false;
    }
}


function dupAdHocChequeEntry1(){
    var date = document.getElementById('chDate1').value;
    var chequeNo = document.getElementById('chNo1').value;
    var bank = document.getElementById('chBank1').value;
    var billAmount = document.getElementById('chAmt1').value;
    var message = document.getElementById('chkNoErr1').innerText;

        $.ajax(
        {
            type:"post",
            url: "<?php echo base_url(); ?>index.php/CashAndChequeController/checkChequeDetails",
            data:{chequeDate:date,chequeNo:chequeNo,payAmount:billAmount},
            success:function(response)
            {
              if(response !=""){
                document.getElementById('chkNoErr1').innerText = response;
                return false;
              }else{
                document.getElementById('chkNoErr1').innerText = '';
                return true;
              }
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



function dupAdHocChequeEntry2(){
    var date = document.getElementById('chDate2').value;
    var chequeNo = document.getElementById('chNo2').value;
    var bank = document.getElementById('chBank2').value;
    var billAmount = document.getElementById('chAmt2').value;
    var message = document.getElementById('chkNoErr2').innerText;

    
        $.ajax(
        {
            type:"post",
            url: "<?php echo base_url(); ?>index.php/CashAndChequeController/checkChequeDetails",
            data:{chequeDate:date,chequeNo:chequeNo,payAmount:billAmount},
            success:function(response)
            {
              if(response !=""){
                document.getElementById('chkNoErr2').innerText = response;
                return false;
              }else{
                document.getElementById('chkNoErr2').innerText = '';
                return true;
              }
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

function dupNeftEntry1(){
    var date = document.getElementById('neftDate1').value;
    var number = document.getElementById('neftNo1').value;
    var billAmount = document.getElementById('neftAmt1').value;
    var message = document.getElementById('neftNoErr1').innerText;

        $.ajax(
        {
            type:"post",
            url: "<?php echo base_url(); ?>index.php/CashAndChequeController/checkNeftDetails",
            data:{neftDate:date,neftNo:number,payAmount:billAmount},
            success:function(response)
            {
              if(response !=""){
                document.getElementById('neftNoErr1').innerHTML = response;
                return false;
              }else{
                document.getElementById('neftNoErr1').innerHTML = '';
                return true;
              }
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

function dupNeftEntry2(){
    var date = document.getElementById('neftDate2').value;
    var number = document.getElementById('neftNo2').value;
    var billAmount = document.getElementById('neftAmt2').value;
    var message = document.getElementById('neftNoErr2').innerText;

        $.ajax(
        {
            type:"post",
            url: "<?php echo base_url(); ?>index.php/CashAndChequeController/checkNeftDetails",
            data:{neftDate:date,neftNo:number,payAmount:billAmount},
            success:function(response)
            {
              if(response !=""){
                document.getElementById('neftNoErr2').innerHTML = response;
                return false;
              }else{
                document.getElementById('neftNoErr2').innerHTML = '';
                return true;
              }
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


function chkMsg(){
  var message = document.getElementById('chkNoErr').innerText;
  if(message.trim() !== ""){
    // alert(message);
      return false;
  }else{
    message='';
      return true;
  }
}


</script>

<script type="text/javascript">
  function submitAdHocCheques(){
      var billnos = document.getElementById('billnos').value;
      var billAmounts = document.getElementById('billAmounts').value;
      var retailers = document.getElementById('retailers').value;
      var chequeNos = document.getElementById('chequeNos').value;
      var theDates = document.getElementById('theDates').value;
      var chequeBanks = document.getElementById('chequeBanks').value;
      var companys = document.getElementById('companys').value;
      var modes = document.getElementById('modes').value;
      var allIDss = document.getElementById('allIDss').value;
      var billModess = document.getElementById('billModess').value;

      // alert(billnos+' '+billAmounts+' '+retailers+' '+chequeNos+' '+theDates+' '+chequeBanks+' '+companys+' ');die();

      if(billnos =="" || billAmounts =="" || retailers =="" || chequeNos =="" || theDates =="" || chequeBanks =="" || companys ==""){
          alert('Please enter all the details.');
      }else{
          $.ajax({
            url : "<?php echo site_url('CashAndChequeController/updateAddHocNewEntry');?>",
            method : "POST",
            data : {billModess:billModess,allIDss:allIDss,billnos: billnos,billAmounts:billAmounts,retailers:retailers,chequeNos:chequeNos,theDates:theDates,chequeBanks:chequeBanks,companys:companys},
            success: function(data){
                if(data==="Record inserted"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/CashAndChequeController";
                }else{
                    alert(data);
                }
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

  }
</script>  

<script type="text/javascript">
    $(document).on('click','#sbDataInsert',function(){
        var billId = $("input[name='billPaymentId1[]']").map(function(){return $(this).val();}).get();
        var billNo = $("input[name='billno1[]']").map(function(){return $(this).val();}).get();
        var billAmount = $("input[name='billAmount1']").val();
        var chequeNo = $("input[name='chequeNo1']").val();
        var neftNo = $("input[name='neftNo1']").val();
        var retailer = $("input[name='retailer1']").val();

        var chequeDate = $("input[name='chequeDate1']").val();
        var chequeBank = $("input[name='chequeBank1']").val();
        var company = $("input[name='company1']").val();
        var mode = $("input[name='mode1']").val();

        // alert(chequeDate);die();
        
        if(mode===""){
            alert('Please select any Cheque/NEFT. ');die();
        }

        if(mode==="Cheque"){
            if((billId=="") || (billNo == "") || (billAmount == "") || (chequeNo == "") || (chequeDate == "") || (chequeBank == "") || (company == "")){
                alert('Please enter all details. ');die();
            }else{
                $.ajax({
                    type:"post",
                    url: "<?php echo base_url(); ?>index.php/CashAndChequeController/chequeDetailsVerify",
                    data:{chequeDate:chequeDate,chequeNo:chequeNo,chequeBank:chequeBank},
                    success:function(response)
                    {
                      if(response.trim() != ""){
                        if (confirm('Cheque already present. Do you want do club this cheque. ?')) {
                          $("#sbDataInsert").attr("disabled", true);
                            $.ajax({
                              url : "<?php echo site_url('CashAndChequeController/addChequeInDepositSlip');?>",
                              method : "POST",
                              data : {
                                billId:billId,
                                billNo:billNo,
                                billAmount: billAmount,
                                chequeNo:chequeNo,
                                neftNo:neftNo,
                                chequeDate:chequeDate,
                                chequeBank:chequeBank,
                                company:company,
                                mode:mode,
                                retailer:retailer
                              },
                              success: function(data){
                                  if(data==="Record updated"){
                                      alert(data);
                                      window.location.href="<?php echo base_url();?>index.php/CashAndChequeController";
                                  }else{
                                      alert(data);
                                  }
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
                        } else {
                            window.location.href="<?php echo base_url();?>index.php/CashAndChequeController";
                        }
                      }else{
                        $("#sbDataInsert").attr("disabled", true);
                        $.ajax({
                              url : "<?php echo site_url('CashAndChequeController/addChequeInDepositSlip');?>",
                              method : "POST",
                              data : {
                                billId:billId,
                                billNo:billNo,
                                billAmount: billAmount,
                                chequeNo:chequeNo,
                                neftNo:neftNo,
                                chequeDate:chequeDate,
                                chequeBank:chequeBank,
                                company:company,
                                mode:mode,
                                retailer:retailer
                              },
                              success: function(data){
                                  if(data==="Record updated"){
                                      alert(data);
                                      window.location.href="<?php echo base_url();?>index.php/CashAndChequeController";
                                  }else{
                                      alert(data);
                                  }
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
        }else if(mode==="NEFT"){
          if((billId=="") || (billNo == "") || (billAmount == "") || (neftNo == "") || (chequeDate == "") || (company == "")){
                alert('Please enter all details. ');die();
            }else{
                $.ajax({
                    type:"post",
                    url: "<?php echo base_url(); ?>index.php/CashAndChequeController/neftDetailsVerifyWithoutAmount",
                    data:{neftDate:chequeDate,neftNo:neftNo,billAmount:billAmount},
                    success:function(response)
                    {
                      if(response.trim() != ""){
                        if (confirm('NEFT already present. Do you want club this NEFT. ?')) {
                          $("#sbDataInsert").attr("disabled", true);
                            $.ajax({
                              url : "<?php echo site_url('CashAndChequeController/addNeftInNeftRegister');?>",
                              method : "POST",
                              data : {
                                billId:billId,
                                billNo:billNo,
                                billAmount: billAmount,
                                chequeNo:chequeNo,
                                neftNo:neftNo,
                                chequeDate:chequeDate,
                                chequeBank:chequeBank,
                                company:company,
                                mode:mode,
                                retailer:retailer
                              },
                              success: function(data){
                                  if(data==="Record updated"){
                                      alert(data);
                                      window.location.href="<?php echo base_url();?>index.php/CashAndChequeController";
                                  }else{
                                      alert(data);
                                  }
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
                        } else {
                            window.location.href="<?php echo base_url();?>index.php/CashAndChequeController";
                        }
                      }else{
                        $("#sbDataInsert").attr("disabled", true);
                        $.ajax({
                              url : "<?php echo site_url('CashAndChequeController/addNeftInNeftRegister');?>",
                              method : "POST",
                              data : {
                                billId:billId,
                                billNo:billNo,
                                billAmount: billAmount,
                                chequeNo:chequeNo,
                                neftNo:neftNo,
                                chequeDate:chequeDate,
                                chequeBank:chequeBank,
                                company:company,
                                mode:mode,
                                retailer:retailer
                              },
                              success: function(data){
                                  if(data==="Record updated"){
                                      alert(data);
                                      window.location.href="<?php echo base_url();?>index.php/CashAndChequeController";
                                  }else{
                                      alert(data);
                                  }
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
        }
        
    });
</script>