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
  <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
  <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
    <div class="container-fluid">
      <!-- <div class="block-header">
        <h2>Cheque Reconciliation</h2>
      </div> -->
      <div class="row clearfix" id="page">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="card">
            <div class="header flex-div">
              <h2>
               Cheque Reconciliation
             </h2>
              <p align="right">
                  <button align="right" type="button" id="insert-ins" class="btn btn-xs btn-primary btnStyle m-t-15 waves-effect"> <i class="material-icons">save</i><span class="icon-name">Clear All</span></button>
              </p>
               <!-- <form method="post" role="form" enctype="multipart/form-data" action="<?php// echo site_url('CashAndChequeController/saveChequeReconcilation'); ?>"> 
                                             
                      <button class="btn btn-primary m-t-15 waves-effect btn-sm">
                              <span class="icon-name">Save</span>
                      </button> 
              </form> -->
           </div>
           <div class="body">
            <div class="row">                                  
              <div class="row m-t-20">
                <div class="col-md-12">
                  <div class="table-responsive">
                    <table style="font-size:12px" class="table table-bordered table-striped table-hover dataTable js-exportable" data-page-length='100' id="tbl">
                      <thead>
                        <tr class="gray">
                        <th><input class="checkall" type="checkbox" name="selValue" id="basic_checkbox"/><label for="basic_checkbox"></label>Select All</th>
                                            
                        <th>No</th>
                        <th>Retailer Name</th>
                        <th>Cheque No.</th>
                        <th>Cheque Date</th>
                        <th>Deposit Date</th>
                        <th>Cheque Amount</th>
                        <th>Bank</th>
                        <th>Company</th>
                        <th>Bill No.</th>
                        <th>Current Status</th>
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
                              $retailer=$this->CashAndChequeModel->getRetailerbyBills('bills',$b);
                              $rname=$rname.$retailer.',';
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
                            <td align="right"><?php echo number_format($data['sumAmount']); ?></td>
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
                                  <button data-toggle="modal" data-id="<?php echo $data['id'];?>" data-target="#myModal" class="identifyingClass btn btnStyle waves-effect" data-type="basic" id="taginfo">Bounced 
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

                        foreach ($billpaymentsAdHoc as $data) 
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
                            <td align="right"><?php echo $data['sumAmount']; ?></td>
                            <td><?php echo $data['chequeBank']; ?></td>
                            <td><?php echo $data['compName']; ?></td>
                            <td>
                              <?php
                              echo $data['billNo'];
                              ?>
                            </td>   
                            <td><?php echo $data['chequeStatus']; ?></td>

                            <?php
                                if($data['chequeStatus']=='Banked'){
                            ?> 
                                  
                            
                                <?php $id = $data['id'];
                                $status = "cleared";?>
                                <!-- <a href="<?php echo site_url('CashAndChequeController/updateStatusCleared/'.$id.'/'.$status);?>"> -->
                                
                                <!--   <button onclick="signedOkStatus(this,'<?php echo $id?>','<?php echo $status; ?>');removeMe(this);" class="btn btn-primary waves-effect" data-type="basic">Cleared
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
                        <th>No</th>
                        <th>Retailer Name</th>
                        <th>Cheque No.</th>
                        <th>Cheque Date</th>
                        <th>Deposit Date</th>
                        <th>Cheque Amount</th>
                        <th>Bank</th>
                        <th>Company</th>
                        <th>Bill No.</th>
                        <th>Current Status </th>
                        <!-- <th>Change Status </th> -->
                        <th>Change Status</th>
                      </tr>
                    </tfoot>                                                
                  </table>
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
    $(".checkall").on("click", function() {
        var sum = 0;
        $('.chkclass:checked').each(function() {
            sum += parseFloat($(this).closest('tr').find('.wagein').text());
        });
        console.log(sum);
      $(".checkhour").prop("checked", !clicked);
      clicked = !clicked;
      this.innerHTML = clicked ? 'Deselect' : 'Select';
    });
</script>


  <script type="text/javascript">
    jQuery("#insert-ins").on("click",function(){
        var selValue = [];
        $.each($("input[name='selValue']:checked"), function(){
                selValue.push($(this).val());
        });
        if(selValue.length>0 ){
            var msj= confirm('Are you sure wants to submit.');
            if (msj == false) { 
              return false;
            } else {
                $.ajax({
                    type: "POST",
                    url:"<?php echo site_url('CashAndChequeController/updateStatusClearedWithCheckBox');?>",
                    data:{selValue:selValue},
                    success: function (data) {
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