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
<style type="text/css">
.selectStyle select {
   background: transparent;
   width: 100px;
   padding: 4px;
   font-size: 1em;
   border: 1px solid #ddd;
   height: 25px;
}
li{
    margin-bottom: 0PX;
    padding-bottom: 0PX;
}

#myBtn {
  display: none;
  position: fixed;
  bottom: 20px;
  right: 30px;
  z-index: 99;
  font-size: 18px;
  border: none;
  outline: none;
  background-color: red;
  color: white;
  cursor: pointer;
  padding: 15px;
  border-radius: 4px;
}

#myBtn:hover {
  background-color: #f59782 ;
}
</style>
<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
<section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
    <!-- <section class="content"> -->
        <div class="container-fluid">
            
            <!-- Basic Examples -->
            <div class="row clearfix" id="page">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Cheque Deposit Slip 
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row">                                 
                                <div class="row m-t-20">                                   
                                    <div class="table-responsive cust-tbl">
                                        <div class="col-md-12">
                                            <form method="post" role="form" action="<?php echo site_url('CashAndChequeController/DesktopBill');?>">
                                            <div class="col-md-3">
                                                    <b>Company Name:</b>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                           <i class="material-icons">perm_contact_calendar</i>
                                                        </span>
                                                       <div class="form-line">
                                                            <select class="form-control" id="name" name="name">
                                                                <?php if(!empty($this->session->userdata('DepositCompany'))){ ?>
                                                                <option value="<?php echo $this->session->userdata['DepositCompany']['depositCompany']; ?>"><?php echo $this->session->userdata['DepositCompany']['depositCompany']; ?></option>
                                                                <?php } ?>
                                                                <option value="General">--Select All Companies--</option>
                                                              
                                                                <?php foreach ($company as $req_item): ?>
                                                                    <option value="<?php echo $req_item['name'] ?>"><?php echo $req_item['name'] ?>                                                            
                                                                </option>
                                                            <?php endforeach ?> 
                                                            </select>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="col-md-2">

                                                <?php if(!empty($this->session->userdata('DepositDate'))){ ?>
                                                     <b> Depositable cheques till :</b>
                                                <?php }else{ ?>
                                                    <b> Show Cheques till Date:</b>
                                                <?php } ?>
                                                    
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="material-icons">perm_contact_calendar</i>
                                                        </span>
                                                        <div class="form-line">
                                                            <input type="date" id="date" value="<?php if(!empty($this->session->userdata('DepositDate'))){ echo $this->session->userdata['DepositDate']['depositDate']; }else{ echo date("Y-m-d"); }?>" name="dt" class="form-control">
                                                        </div>
                                                    </div>
                                            </div> 
                                            <div class="col-md-1">
                                                <input type="submit" value="Search" class="btn btn-primary btnStyle btn-sm margin m-t-20">
                                            </div> 
                                        </form>
                                        <div class="col-md-6" id="default_tbl">
                                            <table class="table table-bordered">
                                                
                                                <thead>
                                                    <tr>
                                                        <th>Bill Count.</th>
                                                        <th>Cheque Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                     <tr>
                                                        <td>
                                                        <?php
                                                        $total=0;
                                                        if((!empty($bills)) || (!empty($officeBills))){
                                                            echo (count($bills)+count($officeBills));
                                                            foreach ($bills as $key) {
                                                                $total=$total+$key['chAmount'];
                                                            }

                                                            foreach ($officeBills as $key) {
                                                                $total=$total+$key['chAmount'];
                                                            }
                                                        }
                                                        ?>
                                                        </td>
                                                        <td><h6>
                                                        <?php 
                                                            if((!empty($bills)) || (!empty($officeBills))){
                                                                echo $total; 
                                                            }
                                                        ?>
                                                        </h6></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                         <div class="col-md-6" id="runclick" style="display:none;">
                                            <table style="font-size: 12px" class="table table-bordered">
                                                
                                                <thead>
                                                    <tr>
                                                        <th>Bill Count.</th>
                                                        <th>Cheque Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                     <tr>
                                                        <td id="cntchk"></td>
                                                        <td id="TotalInvoiceAmt"><h6></h6></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <table data-page-length='200' class="table table-bordered dataTable js-exportable cust-tbl">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <span> <input class="checkall" type="checkbox" name="selValue" id="basic_checkbox"/>
                                                        <label for="basic_checkbox"></label></span>
                                                       </th>
                                                    <th>S.No.</th>
                                                    <th>Retailer Name</th>
                                                    <th>Cheque No</th>
                                                    <th>Cheque Date </th>
                                                    <th>Cheque Amount</th>
                                                    <th>Bank</th>
                                                    <th>Company </th>
                                                </tr>
                                            </thead>
                                                <tbody id="tblData">
                                                    <?php
                                                    $no=0;
                                                    if((!empty($bills)) || (!empty($officeBills))){

                                                    
                                                    foreach ($bills as $data) 
                                                    {
                                                       $no++; 
                                                       ?>
                                                       <tr>
                                                        <td>
                                                            <input class="checkhour" type="checkbox" name="selValue" value="<?php echo $data['id']; ?>" id="basic_checkbox_<?php echo $data['id']; ?>" />
                                                            <label for="basic_checkbox_<?php echo $data['id']; ?>"></label>
                                                        </td>
                                                        <td><?php echo $no; ?></td>
                                                        <?php
                                                            $rname="";
                                                            $billsID= $data['billId'];
                                                            // echo $billsID."<br>";
                                                            $billsID=explode(',',$billsID);
                                                            // print_r($billsID);
                                                            foreach($billsID as $b){
                                                                if($b>0){
                                                                     $retailer=$this->CashAndChequeModel->getRetailerbyBills('bills',$b);
                                                                    $rname=$rname.$retailer.',';
                                                                }
                                                               
                                                            }
                                                            $rname= trim($rname,',');
                                                        ?>
                                                         <td>
                                                            <?php  
                                                                if($data['retailerName'] !=""){
                                                                    echo $data['retailerName']; 
                                                                }else{
                                                                    echo $rname; 
                                                                }
                                                            ?>
                                                        </td>
                                                         <td><?php echo $data['chequeNo']; ?></td>
                                                         <td><?php
                                                        $dt=date_create($data['chequeDate']);
                                                        $data['chequeDate'] = date_format($dt,'d-M-Y');
                                                        echo $data['chequeDate']; ?></td>
                                                        <td class="wagein" align="right"><?php echo ($data['chAmount']); ?></td>
                                                        <td><?php echo $data['chequeBank']; ?></td>
                                                        <td>
                                                        <?php
                                                            $cmp=$data['compName']; 
                                                            echo trim($cmp,',');
                                                        ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                foreach ($officeBills as $data) 
                                                    {
                                                       $no++; 
                                                       ?>
                                                       <tr>
                                                        <td>
                                                            <input class="checkhour" checked type="checkbox" name="selValue" value="<?php echo $data['id']; ?>" id="basic_checkbox_<?php echo $data['id']; ?>" />
                                                            <label for="basic_checkbox_<?php echo $data['id']; ?>"></label>
                                                        </td>
                                                        <td><?php echo $no; ?></td>
                                                        
                                                        <td><?php echo $data['retailerName']; ?></td>
                                                         <td><?php echo $data['chequeNo']; ?></td>
                                                         <td><?php
                                                        $dt=date_create($data['chequeDate']);
                                                        $data['chequeDate'] = date_format($dt,'d-M-Y');
                                                        echo $data['chequeDate']; ?></td>
                                                        <td class="wagein" align="right"><?php echo ($data['paidAmount']); ?></td>
                                                        <td><?php echo $data['chequeBank']; ?></td>
                                                        <td>
                                                        <?php
                                                            $cmp=$data['compName']; 
                                                            echo trim($cmp,',');
                                                        ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }else{
                                                ?>
                                               <tr><td colspan="8">No cheque is found for current search criteria</td></tr>
                                                <?php
                                            }
                                                ?> 
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th></th>
                                                        <th>S.No.</th>
                                                        <th>Retailer Name</th>
                                                        <th>Cheque No</th>
                                                        <th>Cheque Date </th>
                                                        <th>Cheque Amount</th>
                                                        <th>Bank</th>
                                                        <th>Company </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                             <button type="button" id="insert-ins" class="btn btn-primary btnStyle m-t-15 waves-effect"> 
                                                     <i class="material-icons">save</i> <i class="material-icons">email</i> 
                                              <span class="icon-name"> Save and Download</span>
                                        </button> 
                                        </div>
                                    </div><!--end -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </section>

    
<?php $this->load->view('/layouts/footerDataTable'); ?>

<script type="text/javascript">
    $("#insert-ins").one('click', function() {
        var compName=$("#name option:selected").val()
        var date=$('#date').val();
    // jQuery("#insert-ins").one("click",function(){
        // e.stopImmediatePropagation();
        var selValue = [];
        $.each($("input[name='selValue']:checked"), function(){
                selValue.push($(this).val());
        });

        // alert(selValue);die();
       
        if(selValue.length>0 ){
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('CashAndChequeController/depositSlipUpdate');?>",
                data:{company:compName,date:date,selValue:selValue},
                success: function (data) {
                    // alert(data);die();
                    // $("input[type=checkbox]").each(function(){
                    //     $(this).attr('checked', false);
                    // });

                    alert('Saved & Downloaded.');
                    var res=data.trim();
                    // alert(res);
                    var path="<?php echo base_url();?>assets/deliveryslips/"+res;
                    window.open(path, "_blank");
                    window.location.href="<?php echo base_url();?>index.php/CashAndChequeController/DesktopBill";
                }  
            });
        }else{
            alert('Please select cheque.');
        }
});
    
</script>

<script type="text/javascript">
    // var clicked = false;
    // $(".checkall").on("click", function() {
    //   $(".checkhour").prop("checked", !clicked);
    //   clicked = !clicked;
    //   this.innerHTML = clicked ? 'Deselect' : 'Select';
    // });
</script>

<script type="text/javascript">
    var clicked = false;
    $(".checkall").on("click", function() {
        var sum = 0;
        $('.chkclass:checked').each(function() {
            sum += parseFloat($(this).closest('tr').find('.wagein').text());
        });
        // console.log(sum);
      $(".checkhour").prop("checked", !clicked);
      clicked = !clicked;
      this.innerHTML = clicked ? 'Deselect' : 'Select';
    });
</script>

<script type="text/javascript">
    $('.checkhour').change(function () {
        var total = 0;
        var cashTotal=0;
        // iterate through each td based on class and add the values
        $(".wagein").each(function () {
            //Check if the checkbox is checked
            if ($(this).closest('tr').find('.checkhour').is(':checked')) {
                var value = $(this).text();
                // add only if the value is number
                if (!isNaN(value) && value.length != 0) {
                    total += parseFloat(value);
                }
            }
        });
        // alert(total);
        var cnt=$('input[name="selValue"]:checked').length;


        if(total>0){
            $('#default_tbl').hide();
            $('#runclick').show();
            $('#TotalInvoiceAmt').text(total);
            $('#cntchk').text(cnt);
        }else{
            $('#default_tbl').show();
            $('#runclick').hide();
            $('#TotalInvoiceAmt').text(total);
            $('#cntchk').text(cnt);
        }
       
    });
</script>
<script type="text/javascript">
    $('.checkall').change(function () {
        var total = 0;

        // iterate through each td based on class and add the values
        $(".wagein").each(function () {
            //Check if the checkbox is checked
            if ($(this).closest('tr').find('.checkall').is(':checked')) {
                var value = $(this).text();
                // add only if the value is number
                if (!isNaN(value) && value.length != 0) {
                    total += parseFloat(value);
                }
            }
        });
        
        var cnt=$('input[name="selValue"]:checked').length;
        if(total>0){
            $('#default_tbl').hide();
            $('#runclick').show();
            $('#TotalInvoiceAmt').text(total.toFixed(2));
            $('#cntchk').text(cnt);
        }else{
            $('#default_tbl').show();
            $('#runclick').hide();
            $('#TotalInvoiceAmt').text(total.toFixed(2));
            $('#cntchk').text(cnt);
        }
       
    });
</script>

 <script type="text/javascript">
//     $(document).on('click','#search',function(){
//           var name=$('#name').val();
//             var date=$('#date').val();
//         $.ajax({
//             url : "<?php echo site_url('CashAndChequeController/searchBills');?>",
//             method : "POST",
//             data : {date: date,name:name},
//             success: function(data){
//                 // alert(data);
//                 $('#tblData').html(data);
//             }
//         });
//     });
// </script>

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
                }
                });
            }
        });
      }
    </script>
    
     <script>
//     (function(){

//       var todo = document.querySelector( '#list' ),
//           add = document.querySelector( '#eAdd' ),
//           eName = document.querySelector( '#email' );
        
//       add.addEventListener('click', function( ev ) {
//             var text = eName.value;
//             if ( text !== '' ) {
//               todo.innerHTML += '<li class="list-group-item list-group-item-action">' + text + '<button  style="float: right;" onclick="Delete(this);"><i class="fa fa-close"></i></button> </li>';
//               eName.value = '';
//             }
            
//         ev.preventDefault();
//       }, false);

//     })();
//       function Delete(currentEl){
//       currentEl.parentNode.parentNode.removeChild(currentEl.parentNode);
//       }
// </script>
