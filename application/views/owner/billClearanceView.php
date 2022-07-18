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
<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
<section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
    <!-- <section class="content"> -->
        <div class="container-fluid">
            
            <!-- Basic Examples -->
            <div class="row clearfix" id="page">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2><a href="<?php echo site_url('owner/OfficeAllocationController/billClearance'); ?>"><button class="btn btn-xs bg-primary margin"><i class="material-icons">refresh</i></button></a>
                                Residual Bill Clearing</h2>
                        </div>
                        <div class="body">
                            <div class="row">                                 
                                <div class="row m-t-20">                                   
                                    <div class="col-md-12">
                                        <form method="post" role="form" action="<?php echo site_url('owner/OfficeAllocationController/billClearance');?>">
                                            <div class="col-md-12">
                                            <div class="col-md-2">
                                                    <b>Division Name:</b>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                           <i class="material-icons">perm_contact_calendar</i>
                                                        </span>

                                                       <div class="form-line">
                                                             <input type="text"  autocomplete="off" placeholder="select division" list="compNameList" id="compName" name="compName" value="<?php echo $compName; ?>" class="form-control" required> 
                                                            <datalist id="compNameList">
                                                            <?php foreach ($company as $req_item){ ?>
                                                                <option id="<?php echo $req_item['id'] ?>" value="<?php echo $req_item['name'] ?>" />
                                                              <?php } ?> 
                                                            </datalist> 
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="col-md-2">
                                                <b>Threshold Amount:</b>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="material-icons">perm_contact_calendar</i>
                                                        </span>
                                                        <div class="form-line">
                                                            <input type="text" required autocomplete="off"  onkeypress="return numbersonly(this, event);" placeholder=" (Maximum - Rs <?php echo $limit; ?>)" id="amount" name="amount" value="<?php echo $amt; ?>" class="form-control">
                                                        </div>
                                                    </div>
                                            </div>

                                            <?php if(!empty($compName) && !empty($amt) && !empty($bills)){?>
                                            <div class="col-md-5">
                                                <b>Remark:</b>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="material-icons">perm_contact_calendar</i>
                                                        </span>
                                                        <div class="form-line">
                                                            <input type="text" autocomplete="off" value="<?php echo $rem; ?>" placeholder="enter remark" id="remark" name="remark" class="form-control">
                                                        </div>
                                                    </div>
                                            </div>
                                             <div class="col-md-2">
                                                <div class="form-check">
                                                    <input name="selOption" <?php if($opti=='cd'){ echo "checked=checked";}  ?> value="cd" type="radio" id="radio_1" checked />
                                                    <label for="radio_1">CD </label>
                                                <br>
                                                    <input name="selOption" <?php if($opti=='other_adjustment'){ echo "checked=checked";}  ?> value="other_adjustment" type="radio" id="radio_2" />
                                                      <label for="radio_2">Other Adjusment </label>
                                                </div>
                                            </div>
                                        <?php  } ?>
                                        
                                            
                                            <div class="col-md-1">
                                            <?php if(empty($compName) || empty($amt) || empty($bills)){?>
                                                <?php if($this->session->userdata['workRestrict']['status']=="yes"){?>
                                                    <button class="btn btn-primary btn-sm margin m-t-20" onclick="planUpgradeMsg()">Search</button>
                                                <?php }else{ ?>
                                                    <input type="submit" value="Search" class="btn btn-primary btn-sm margin m-t-20">
                                                <?php } ?>
                                            <?php }else{ ?>
                                                <button type="button" id="insert-ins" class="btn btn-primary m-t-15 waves-effect"> 
                                                     <i class="material-icons">save</i> 
                                                  <span class="icon-name"> Save </span>
                                                </button>
                                            <?php } ?> 
                                            </div> 
                                        </div>
                                        </form>
                                    </div>
                                    <div class="col-md-12">
                                           <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover dataTable js-exportable" data-page-length='100'>
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <span> <input class="checkall" type="checkbox" name="selValue" id="basic_checkbox"/>
                                                            <label for="basic_checkbox"></label></span>
                                                           </th>
                                                        <th>S.No.</th>
                                                        <th>Bill No</th>
                                                        <th>Bill Date</th>
                                                        <th>Retailer Name</th>
                                                        <th>Net Amount</th>
                                                        <th>Pending Amount </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tblData">
                                                <?php    $no=0;
                                                    if(!empty($bills)){
                                                    foreach ($bills as $data) 
                                                    {
                                                        $no++; 
                                                    ?>
                                                        <tr>
                                                            <td>
                                                                <input class="checkhour" type="checkbox" name="selValue" value="<?php echo $data['id']; ?>" id="basic_checkbox_<?php echo $data['id']; ?>"/>
                                                                <label for="basic_checkbox_<?php echo $data['id']; ?>"></label>
                                                            </td>
                                                            <td><?php echo $no; ?></td>
                                                           
                                                            <td><?php echo $data['billNo']; ?></td>
                                                            <td><?php
                                                            $dt=date_create($data['date']);
                                                            $date = date_format($dt,'d-M-Y');
                                                            echo $date; ?></td>
                                                            <td><?php echo $data['retailerName']; ?></td>
                                                            <td align="right"><?php echo number_format($data['netAmount']); ?></td>
                                                            <td align="right"><?php echo number_format($data['pendingAmt']); ?></td>
                                                        </tr>
                                            <?php
                                                    }
                                                }else{
                                                    echo "<tr><td colspan='7'>No data available.</td></tr>";
                                                }
                                                    ?>
                                                
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th></th>
                                                        <th>S.No.</th>
                                                        <th>Bill No</th>
                                                        <th>Bill Date</th>
                                                        <th>Retailer Name</th>
                                                        <th>Net Amount</th>
                                                        <th>Pending Amount </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                             
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
   <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    <script type="text/javascript">
    jQuery("#insert-ins").on("click",function(){

        var compName=$('#compName').val();
        var amount=$('#amount').val();
        var remark=$('#remark').val();
        var selOption = $("input[name='selOption']:checked").val();
       
        var selValue = [];
        $.each($("input[name='selValue']:checked"), function(){
                selValue.push($(this).val());
        });

        if(compName =="" || amount=="" || remark==""){
            alert('Please enter all details');die();
        }

        if(selValue.length>0 ){
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('owner/OfficeAllocationController/insertBillClearanceData');?>",
                
                data:{billId:selValue,compName:compName,amount:amount,remark:remark,selOption:selOption},
                success: function (data) {
                    // alert(data);die();
                    $("input[type=checkbox]").each(function(){
                        $(this).attr('checked', false);
                    });

                    // alert('Saved.');
                    window.location.href="<?php echo base_url();?>index.php/owner/OfficeAllocationController/billClearance";
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
            alert('Please select bills.');
        }
});
    
</script>

<script type="text/javascript">
    var clicked = false;
    $(".checkall").on("click", function() {
      $(".checkhour").prop("checked", !clicked);
      clicked = !clicked;
      this.innerHTML = clicked ? 'Deselect' : 'Select';
    });

    // $(".checkall").click(function() {
    //     var isCheckboxChecked = this.checked;
    //     if(isCheckboxChecked==true){
    //         $(".checkhour").attr("checked", true);
    //     }else{
    //         $(".checkhour").attr("checked", false); //uncheck all checkboxes
    //     }
      
    // });
</script>

<script type="text/javascript">
    $(document).on('click','#search',function(){
          var name=$('#name').val();
            var date=$('#date').val();
        $.ajax({
            url : "<?php echo site_url('CashAndChequeController/searchBills');?>",
            method : "POST",
            data : {date: date,name:name},
            success: function(data){
                // alert(data);
                $('#tblData').html(data);
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
    (function(){

      var todo = document.querySelector( '#list' ),
          add = document.querySelector( '#eAdd' ),
          eName = document.querySelector( '#email' );
        
      add.addEventListener('click', function( ev ) {
            var text = eName.value;
            if ( text !== '' ) {
              todo.innerHTML += '<li class="list-group-item list-group-item-action">' + text + '<button  style="float: right;" onclick="Delete(this);"><i class="fa fa-close"></i></button> </li>';
              eName.value = '';
            }
            
        ev.preventDefault();
      }, false);

    })();
      function Delete(currentEl){
      currentEl.parentNode.parentNode.removeChild(currentEl.parentNode);
      }
</script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
 


<script type="text/javascript">
    $(document).on('click','#searchInfo',function(){
        var compName=$('#compName').val();
        var compId = $('#compNameList').find('option[value="'+compName+'"]').attr('id');
        if (typeof compId === "undefined") {
            alert('Please enter correct company');die();
        }
        var amount=$('#amount').val();
        var remark=$('#remark').val();
        var option = $("input[name='selOption']:checked").val();

        if(compName== "" || amount == "" || remark == ""){
            alert("Please enter detail");die();
        }else{
             $.ajax({
                url : "<?php echo site_url('owner/OfficeAllocationController/searchBillsForClearance');?>",
                method : "POST",
                data : {compName: compName,amount:amount,remark:remark,option:option},
                success: function(data){
                    // alert(data);
                    $('#tblData').html(data);
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
