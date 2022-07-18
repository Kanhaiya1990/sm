<?php $this->load->view('/layouts/commanHeader'); ?>

<script>
    $(document).ready(function() {
        $('.js-basic-example').DataTable( {
            dom: 'Bfrtip',
            buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print','email'
            ]
        } );
    } );
</script>
<!-- <style type="text/css">
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
</style> -->
  <h1 style="display: none;">Welcome</h1><br/><br/><br/>
  <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
    <div class="container-fluid">
      <!-- <div class="block-header">
        <h2>Cheque Reconciliation</h2>
      </div> -->
      <div class="row clearfix" id="page">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="card">
            <div class="header">
              <h2>
               Cheque Reconciliation
             </h2>
             <div align="right">
              <p align="right">
                  <button align="right" type="button" id="insert-ins" class="btn btn-xs btn-primary m-t-15 waves-effect"> <i class="material-icons">save</i><span class="icon-name">Cleared Selected Cheques</span></button>
              </p>
               <!-- <form method="post" role="form" enctype="multipart/form-data" action="<?php echo site_url('CashAndChequeController/saveChequeReconcilation'); ?>"> 
                                             
                      <button class="btn btn-primary m-t-15 waves-effect btn-sm">
                              <span class="icon-name">Save</span>
                      </button> 
              </form> -->
            </div>
           </div>
           <div class="body">
            <div class="row">                                  
              <div class="row m-t-20">
                <div class="col-md-12">
                  <div class="table-responsive">

                    <div class="row">
                      <div class="col-sm-3">
                        <b>Search Anything</b>
                        <div class="form-group">
                          <div class="form-line">
                             <input type="text" name="searchFor" placeholder="Search..." class="form-control" id="searchKey" onchange="sendRequest();">
                          </div>
                        </div>
                      </div>

                      <div class="col-sm-3">
                        <b>Range</b>
                        <div class="form-group">
                          <select class="form-control" id="limitRows" onchange="sendRequest();">
                            <option value="100">100</option>
                            <option value="500">500</option>
                            <option value="1000">1000</option>
                            <option value="2000">2000</option>
                            <option value="5000">5000</option>
                            <option value="10000">10000</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-sm-2">
                        <a href="<?php echo site_url('CashAndChequeController/ChequeReconcilation'); ?>" class="btn btn-sm m-t-15 btn-primary waves-effect">
                              <i class="material-icons">cancel</i> 
                              <span class="icon-name"> Cancel</span>
                          </a>
                      </div>

                      <div class="col-sm-4" id="default_tbl">
                        <table class="table table-bordered table-striped">
                          <tr>
                            <th align="right">Total Cheques</th>
                            <th align="right">Total Cheque Amount</th>
                            <th align="right">Selected Cheques </th>
                            <th align="right">Selected Cheque Amount</th>
                          <tr>
                          <tr>
                            <td id="curCheques" align="right"><?php echo number_format($totalCheques); ?></td>
                            <td id="curChequeTotal" align="right"><?php echo number_format($totalChequesAmount); ?></td>
                            <td id="fixCnt">0</td>
                            <td id="fixtotal">0</td>
                          </tr>
                        </table>
                      </div>

                      <div class="col-sm-4" id="runclick" style="display:none;">
                        <table class="table table-bordered table-striped">
                          <tr>
                            <th align="right">Total Cheques</th>
                            <th align="right">Total Cheque Amount</th>
                            <th align="right">Selected Cheques </th>
                            <th align="right">Selected Cheque Amount</th>
                          <tr>
                          <tr>
                            <td align="right"><?php echo number_format($totalCheques); ?></td>
                            <td align="right"><?php echo number_format($totalChequesAmount); ?></td>
                            <td id="cntchk"></td>
                            <td id="TotalInvoiceAmt"></td>
                          </tr>
                        </table>
                      </div>
                    </div>

                    <div class="row">
                        
                    </div>

                    <?php echo $pagination; ?>
                    <table style="font-size:12px" class="table table-bordered table-striped table-hover dataTable" data-page-length='100' id="tbl">
                      <thead>

                   <!--  <tr>
                      <th>S. No.</th>
                      <th data-action="sort" data-title="billNo" data-direction="ASC"><span>Bill No</span></th>
                      <th data-action="sort" data-title="date" data-direction="ASC"><span>Bill Date</span></th>
                      <th data-action="sort" data-title="retailerName" data-direction="ASC"><span>Retailer</span></th>
                      <th data-action="sort" data-title="netAmount" data-direction="ASC"><span>Bill Amount</span></th>
                      <th data-action="sort" data-title="pendingAmt" data-direction="ASC"><span>Pending Amount</span></th>
                      <th data-action="sort" data-title="salesman" data-direction="ASC"><span>Salesman</span></th>
                      <th data-action="sort" data-title="routeName" data-direction="ASC"><span>Route</span></th>
                      <th data-action="sort" data-title="routeName" data-direction="ASC"><span>Due Days</span></th>
                      <th data-action="sort" data-title="routeName" data-direction="ASC"><span>Status</span></th>
                      
                      <th>Action</th>
                  </tr> -->

                      <tr class="gray">
                        <th><input class="checkall" type="checkbox" name="selValue" id="basic_checkbox"/><label for="basic_checkbox"></label>Select All</th>
                                            
                        <th>S. No.</th>
                        <th data-action="sort" data-title="retailerName" data-direction="ASC">Retailer Name</th>
                        <th data-action="sort" data-title="chequeNo" data-direction="ASC">Cheque No.</th>
                        <th data-action="sort" data-title="chequeDate" data-direction="ASC">Cheque Date</th>
                        <th data-action="sort" data-title="chequeStatusDate" data-direction="ASC">Deposit Date</th>
                        <th data-action="sort" data-title="sumAmount" data-direction="ASC">Cheque Amount</th>
                        <th data-action="sort" data-title="chequeBank" data-direction="ASC">Bank</th>
                        <th data-action="sort" data-title="compName" data-direction="ASC">Company</th>
                        <th data-action="sort" data-title="billNo" data-direction="ASC">Bill No.</th>
                        <th data-action="sort" data-title="chequeStatus" data-direction="ASC">Current Status</th>
                        <!-- <th>Change Status</th> -->
                        <th>Change Status</th>
                        </tr>
                    </thead>

                      <tbody id="result_data">
                        <?php
                        $no=0;
                        foreach ($billpayments as $data) 
                        {
                         
                          $id=$data['id'];
                          $no++; 

                           $diff=strtotime(date('Y-m-d'))-strtotime($data['chequeStatusDate']);
                          ?>
                           <?php if($diff>3){ ?>
                                 <tr style="background-color: #b2e59e">
                            <?php }else{ ?>
                                 <tr>
                            <?php } ?>
                            <td>
                                <input class="checkhour" type="checkbox" name="selValue" value="<?php echo $id; ?>" id="basic_checkbox_<?php echo $id; ?>" />
                                <label for="basic_checkbox_<?php echo $id; ?>"></label>
                            </td>
                            <td><?php echo $no; ?></td>
                            <?php
                            $rname="";
                            $billsID= $data['billId'];
                            $billsID=explode(',',$billsID);
                            foreach($billsID as $b){
                                if($b > 0){
                                    $retailer=$this->CashAndChequeModel->getRetailerbyBills('bills',$b);
                                    $rname=$rname.$retailer.',';
                                }
                              
                            }
                            $rname= trim($rname,',');
                            ?>
                            <td><?php echo $data['retailerName']; ?></td>
                            <td><?php echo $data['chequeNo']; ?></td>
                            <td><?php
                            $dt=date_create($data['chequeDate']);
                            $data['chequeDate'] = date_format($dt,'d-M-Y');
                            echo $data['chequeDate']; 
                            ?></td>
                            <td><?php
                            $dt=date_create($data['chequeStatusDate']);
                            $data['chequeDate'] = date_format($dt,'d-M-Y');
                            echo $data['chequeDate']; 
                            ?></td>
                            <td align="right">
                                <?php echo number_format($data['sumAmount']); ?>
                                <p class="wagein" style="display:none"><?php echo ($data['sumAmount']); ?></p>
                            </td>
                            <td><?php echo $data['chequeBank']; ?></td>
                            <td><?php echo $data['compName']; ?></td>
                            <td>
                              <?php
                              $billNo=$data['billNo'];
                              $billNo=trim($billNo,',');
                              echo $billNo;
                              ?>
                            </td>   
                            <td><?php echo $data['chequeStatus']; ?></td>

                            <?php
                                if($data['chequeStatus']=='Banked'){
                            ?> 
                                  
                            
                                <?php $id = $data['id'];
                                $status = "cleared";?>
                                <!-- <a href="<?php echo site_url('CashAndChequeController/updateStatusCleared/'.$id.'/'.$status);?>"> -->
                                  
                                  <!-- <button onclick="signedOkStatus(this,'<?php echo $id?>','<?php echo $status; ?>');removeMe(this);" class="btn btn-primary waves-effect" data-type="basic">Cleared
                                  </button> -->
                                
                                <!-- </a>      -->
                              
                              <td> 
                                  <button data-toggle="modal" data-id="<?php echo $data['id'];?>" data-target="#myModal" class="identifyingClass btn btn-primary waves-effect" data-type="basic" id="taginfo">Bounced 
                                  </button>
                              </td>
                          <?php }else{ 
                              if($data['tempStatus']=='1'){

                           ?>
                              <td><i class="material-icons">check_box</i> </td>
                              <td></td>
                         <?php }else if($data['tempStatus']=='2'){ ?>

                              <td></td>
                              <td><i class="material-icons">cancel</i> </td>
                          <?php } 
                              }
                          ?>
                            
                          </tr>  
                          <?php 
                           
                        }

                        ?>                                                   
                      </tbody> 
                      <tfoot>
                       <tr class="gray">
                        <th></th>
                        <th>S. No.</th>
                       <th data-action="sort" data-title="retailerName" data-direction="ASC">Retailer Name</th>
                        <th data-action="sort" data-title="chequeNo" data-direction="ASC">Cheque No.</th>
                        <th data-action="sort" data-title="chequeDate" data-direction="ASC">Cheque Date</th>
                        <th data-action="sort" data-title="chequeStatusDate" data-direction="ASC">Deposit Date</th>
                        <th data-action="sort" data-title="sumAmount" data-direction="ASC">Cheque Amount</th>
                        <th data-action="sort" data-title="chequeBank" data-direction="ASC">Bank</th>
                        <th data-action="sort" data-title="compName" data-direction="ASC">Company</th>
                        <th data-action="sort" data-title="billNo" data-direction="ASC">Bill No.</th>
                        <th data-action="sort" data-title="chequeStatus" data-direction="ASC">Current Status</th>
                        <!-- <th>Change Status </th> -->
                        <th>Change Status</th>
                      </tr>
                    </tfoot>                                                
                  </table>
                  <?php echo $pagination; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</section>

  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Select Reason And Penalty</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
          <div class="modal-body">
         <form id="frms" method="post" role="form" action="<?php echo site_url('CashAndChequeController/updateStatusReturned/');?>"> 
        <input type="hidden" name="billID" id="hiddenValue" value="">
        <div class="col-md-12">
        
          <div class="col-md-6">
                  <b>Reason</b>
                  <div class="input-group"> 
                    <div class="form-line">
                        <select onchange="loadCustData()" id="reason" name="reason">
                            <option value="">--Select Reason--</option>
                            <?php foreach($penalty as $req_item){ ?>
                              <option value="<?php echo $req_item['name']; ?>"><?php echo $req_item['name']; ?> </option>
                        <?php } ?> 
                        </select>
                </div>
              </div>
          </div> 
          <div class="col-md-6"> 
              <b>Penalty</b>
              <div class="input-group">
                <div class="form-line">
                  <input type="text" name="penalty" id="penalty" class="form-control" required="required">
             
                </div>
              </div>
            </div> 
        </div>
        <div class="col-md-12">
          <div class="col-md-2">
              <button id="sbmt" type="submit" class="btn btn-success btn-sm"> Submit </button>
          </div>
          <div class="col-md-2">
             <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
          </div>
        </div>
       </form> 
    </div>
    <div class="modal-footer">
    </div>
  </div>
</div>
</div>

<!-- <script type="text/javascript">
    $(function () {
        $("#sbmt").click(function () {
            var penalty = $('#penalty').val();
            alert(penalty);
            die();
            if(penalty==null || penalty=="Select Penalty"){
                alert('Please select Penalty');
            }
        })
    });
</script> -->
<?php $this->load->view('/layouts/footerDataTable'); ?>
<script type="text/javascript">
  function loadCustData() {  
     var xhttp = new XMLHttpRequest();
     xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          var data=xhttp.responseText;
          var jsonResponse = JSON.parse(data);
          document.getElementById("penalty").value = jsonResponse['penAmt'][0]['amount'];
      }
    };
    var billNo = encodeURI(document.getElementById("reason").value);
    xhttp.open("GET", "<?php echo site_url('CashAndChequeController/getPenaltyAmtByText/');?>"+billNo, true);
    xhttp.send();
  }

</script>

<script type="text/javascript">

$(document).on('click','.identifyingClass',function(){

    // $(function () {
    //     $(".identifyingClass").click(function () {
            var my_id_value = $(this).data('id');
            // alert(my_id_value);
            $("#hiddenValue").val(my_id_value);
        // })
    });
</script>

<script type="text/javascript">

    function removeMe(t) {
        $(t).closest('tr').remove();
    }

    function signedOkStatus(e,id,status){
        if(id){
             $.ajax({
                type: "POST",
                url:"<?php echo site_url('CashAndChequeController/updateStatusCleared');?>",
                data:{"id" : id,"status" : status},
                success: function (data) {
                  alert(data);die();
                  // $('#result_data').html(data);
                  // window.parent.location.reload(true);
                    // document.getElementById('err').innerHTML=data;
                }  
            });
        }

        $(e).closest('tr').find('#okedit').text('');
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<!-- <script>

  function changeStatus(id)
  { 
    var inputOptionsPromise = new Promise(function(resolve) {
      setTimeout(function() {
        // $.getJSON("http://localhost/smartdistributorKP/index.php/CashAndChequeController/FeatchPanalty", function(data) {
          $.getJSON("<?php echo site_url('CashAndChequeController/FeatchPanalty');?>", function(data) {
            var d=JSON.stringify(data);
            var penalty=new Array();
            for (var i = 0; i < data.length; i++){
              penalty.push(data[i]['amount']); 
            }
            resolve(penalty);
          });
        }, 2000)
    })
    swal({
      title: 'Select Penalty',
      input: 'select',
      inputOptions:inputOptionsPromise,
      inputPlaceholder: 'Select Penalty',
      html:
      '<textarea id="swal-input2" name="reason" placeholder="enter reason"/>',
      
      showCancelButton: true,
      inputValidator: function (resValue) {
        return new Promise(function (resolve, reject) {
          var reason=document.getElementById('swal-input2').value;
          if (resValue != '' || reason!='') {
            resolve()
          } else {
            reject('You need to select a Penalty');
          }
        });
      }
    }).then(function (result) {
      alert(result.value);
      die();
      if (result.value) {
        $.ajax({
         url: "<?php echo site_url('CashAndChequeController/updateStatusBounced');?>",
         type: "post",
         data:{"id" : id , "statusBouncedReason" : result.value},
         success: function (response) {
          $('#result_data').html(response);  
        }
      });
      }
    });
  }
</script>  -->

<script type="text/javascript">
    var clicked = false;
    $(document).on('change','.checkall',function(){
    // $(".checkall").on("click", function() {
        var sum = 0;
        $('.chkclass:checked').each(function() {
            sum += parseFloat($(this).closest('tr').find('.wagein').text());
        });
        
      $(".checkhour").prop("checked", !clicked);
      clicked = !clicked;
      this.innerHTML = clicked ? 'Deselect' : 'Select';

      if(clicked){
            var cnt= $('#curCheques').text();
            var cntTotal= $('#curChequeTotal').text();
            $('#default_tbl').hide();
            $('#runclick').show();
            $('#TotalInvoiceAmt').text(cntTotal.toLocaleString('en'));
            $('#cntchk').text(cnt);
        }else{
            $('#default_tbl').show();
            $('#runclick').hide();
            $('#TotalInvoiceAmt').text(0);
            $('#cntchk').text(0);
        }
    });
</script>

<script type="text/javascript">
$(document).on('change','.checkhour',function(){
    // $('.checkhour').change(function () {
        var total = 0;
        // var cashTotal=0;
        $(".wagein").each(function () {
            if ($(this).closest('tr').find('.checkhour').is(':checked')) {
                
                var value = parseInt($(this).text());
                // alert(value);
                if (!isNaN(value) && value.length != 0) {
                    total += parseFloat(value);
                }
            }
        });
        var cnt=$('input[name="selValue"]:checked').length;
        
        if(total>0){
            $('#default_tbl').hide();
            $('#runclick').show();
            $('#TotalInvoiceAmt').text(total.toLocaleString('en'));
            $('#cntchk').text(cnt);
        }else{
            $('#default_tbl').show();
            $('#runclick').hide();
            $('#TotalInvoiceAmt').text(total.toLocaleString('en'));
            $('#cntchk').text(cnt);
        }
    });
</script>


  <script type="text/javascript">
    jQuery("#insert-ins").on("click",function(){

        var cnt= $('#cntchk').text();
        var cntTotal= $('#TotalInvoiceAmt').text();

        var selValue = [];
        $.each($("input[name='selValue']:checked"), function(){
                selValue.push($(this).val());
        });
        if(selValue.length>0 ){
            var msj= confirm('Total selected cheques '+cnt+', Total cheque value '+cntTotal.toLocaleString('en')+'. Are you sure want to submit.');
            if (msj == false) { 
              return false;
            } else {
                $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('CashAndChequeController/updateStatusClearedWithCheckBox');?>",
                    data:{selValue:selValue},
                    success: function (data) {
                        alert('All Selected Cheques marked as cleared');
                        $("input[type=checkbox]").each(function(){
                            $(this).attr('checked', false);
                        });      
                        window.location.href="<?php echo base_url();?>index.php/CashAndChequeController/ChequeReconcilation";
                    }  
                });
            }
        }else{
            alert('Please select Bills.');
        }
});
    
</script>


<script type="text/javascript">
    var sendRequest = function(){
      // var curOrderField = "BillNo";
      // var curOrderDirection = "ASC";
      var searchKey = $('#searchKey').val();
      var limitRows = $('#limitRows').val();
      window.location.href = '<?=base_url('index.php/CashAndChequeController/ChequeReconcilation')?>?query='+searchKey+'&limitRows='+limitRows+'&orderField='+curOrderField+'&orderDirection='+curOrderDirection;
    }

    var getNamedParameter = function (key) {
            if (key == undefined) return false;
            var url = window.location.href;
            var path_arr = url.split('?');
            if (path_arr.length === 1) {
                return null;
            }
            path_arr = path_arr[1].split('&');
            path_arr = remove_value(path_arr, "");
            var value = undefined;
            for (var i = 0; i < path_arr.length; i++) {
                var keyValue = path_arr[i].split('=');
                if (keyValue[0] == key) {
                    value = keyValue[1];
                    break;
                }
            }
            return value;
        };

        var remove_value = function (value, remove) {
            if (value.indexOf(remove) > -1) {
                value.splice(value.indexOf(remove), 1);
                remove_value(value, remove);
            }
            return value;
        };

        var curOrderField, curOrderDirection;
        $('[data-action="sort"]').on('click', function(e){
          curOrderField = $(this).data('title');
          curOrderDirection = $(this).data('direction');
          // curOrderField = "BillNo";
          // curOrderDirection = "ASC";
          sendRequest();
        });

        $('#searchKey').val(decodeURIComponent(getNamedParameter('query')||""));
        $('#limitRows option[value="'+getNamedParameter('limitRows')+'"]').attr('selected', true);

        var curOrderField = getNamedParameter('orderField')||"";
        var curOrderDirection = getNamedParameter('orderDirection')||"";
        var currentSort = $('[data-action="sort"][data-title="'+getNamedParameter('orderField')+'"]');
        if(curOrderDirection=="ASC"){
          currentSort.attr('data-direction', "DESC").find('i.glyphicon').removeClass('glyphicon-triangle-bottom').addClass('glyphicon-triangle-top'); 
        }else{
          currentSort.attr('data-direction', "ASC").find('i.glyphicon').removeClass('glyphicon-triangle-top').addClass('glyphicon-triangle-bottom');  
        }

  </script>

  <script type="text/javascript">
    $( document ).ready(function() {
      $(".dataExport").click(function() {
        var exportType = $(this).data('type');  
        $('#outstanding_table').tableExport({
          type : exportType, 
          select: true,  
          escape : 'false',
          ignoreColumn: [7]
        });   
      });
    });

  </script>
<script type="text/javascript">
  function removeSpaces(string) {
    return string.split(' ').join(' ');
  }
</script>
