<?php $this->load->view('/layouts/commanHeader'); ?>

<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
<section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <div class="table-responsive">
                              <p id="res"></p>
                              
                                <div class="header">
                                    <h2 class="text-center">Office Details Master</h2>
                                </div>
                                <table class="table table-bordered cust-tbl" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <td><strong>Distributor Name</strong></td> <td><?php echo $bankDetails[0]['distributorName']; ?></td>
                                            <td><strong>Primary Contact No.</strong></td><td><?php echo $bankDetails[0]['ownerMobile']; ?></td>
                                            <td><strong>Distributor Code</strong></td><td><?php echo $bankDetails[0]['distributorCode']; ?></td>
                                        </tr>

                                        <tr>
                                             <td><strong>GST Number</strong></td><td><?php echo $bankDetails[0]['gstNumber']; ?></td>
                                             <td><strong>Primary Contact Email</strong></td> <td><?php echo $bankDetails[0]['ownerEmail']; ?></td>
                                             <td><strong>Employee Code Prefix</strong></td><td><?php echo $empCode[0]['name']; ?></td>
                                        </tr>

                                        <tr>
                                             <td><strong>PAN Number</td> <td><?php echo $bankDetails[0]['panNumber']; ?></td>
                                             <td></td>
                                             <td></td>
                                             <td></td>
                                             <td></td>
                                        </tr>
                                    </thead>
                                </table>

                                <div class="header">
                                    <h2 class="text-center">Division Details</h2>
                                </div>
                                <table class="table table-bordered cust-tbl" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <td><strong>Divisions </strong></td><td><strong>Software</strong></td>
                                        </tr>
                                        <?php 
                                            if(!empty($company)){
                                                foreach($company as $item){
                                        ?>
                                                <tr>
                                                    <td><?php echo $item['name']; ?></td>
                                                    <td><?php echo $item['reportSW']; ?></td>
                                                </tr>
                                        <?php
                                                }
                                            }
                                        ?>
                                    </thead>
                                </table>

                                <div class="header">
                                    <h2 class="text-center">Change Bank Details</h2>
                                </div>
                                <table class="table table-bordered cust-tbl" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Bank Name</th>
                                            <th>Account Number</th>
                                            <th>Branch</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="sr_rep">
                                           
                                            <td>
                                              <input type="text" id="bankName" class="form-control" value="<?php echo $bankDetails[0]['bankName']; ?>" >
                                            </td>
                                            <td>
                                              <input type="text" id="accountNo" class="form-control" value="<?php echo $bankDetails[0]['accountNumber']; ?>" >
                                            </td>
                                            <td>
                                              <input type="text" id="branch" class="form-control" value="<?php echo $bankDetails[0]['branch']; ?>" >
                                            </td>

                                            <td>
                                                 <a id="sr_pen_id" href="javascript:void();">
                                                   <button class="btn btn-sm btnStyle waves-effect">
                                                        <i class="material-icons">save</i> 
                                                        <span class="icon-name"> Save</span>
                                                    </button>
                                                </a>                                   
                                            </td>
                                        </tr>
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

    <?php $this->load->view('/layouts/footerDataTable'); ?>

<script>

function deleted(id)
{ 
  // alert(id);
swal({
  title: "Are you sure to delete?",
  text: "Once deleted, you will not be able to recover this!",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
    $.ajax({
        url: "<?php echo site_url('admin/PenaltyController/delete');?>",
        type: "post",
        data: {'id':id},
        success: function (response) {
         
          swal(response, {
            icon: "success",
          });
          var URL = "<?php echo site_url('admin/PenaltyController');?>";
          setTimeout(function(){ window.location = URL; }, 1000);
        },
        error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus, errorThrown);
        }
    });
    
  } else {
    swal("Your record is safe!");
  }
});
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
    $(document).on('click','#sr_pen_id',function(){
        // var distributorName=$('#distributorName').val();
        var bankName=$('#bankName').val();
        // var address=$('#address').val();
        var branch=$('#branch').val();
        var accountNo=$('#accountNo').val();
        // var panNo=$('#panNumber').val();
         $.ajax({
            url : "<?php echo site_url('admin/CompanyController/addOfficeDetailsKia');?>",
            method : "POST",
            data : {id: '1',branch:branch,bankName:bankName,accountNo:accountNo},
            success: function(data){
                alert('Details Updated');
                location.reload(); 
            }
        });
    });

    $(document).on('click','#number_pen_id',function(){
        var smsNumber=$('#smsNumber').val();
        var email=$('#email').val();

        var adminMobile=$('#adminMobile').val();
        var adminEmail=$('#adminEmail').val();

        // var eml=$('#eml').text();
        // var mbl=$('#mbl').text();

        // var eml1=$('#eml1').text();
        // var mbl2=$('#mbl1').text();

        // if((eml !== "") || (mbl !== "") || (eml1 !== "") || (mbl1 !== "")){
            // die();
        // }else{
            $.ajax({
                url : "<?php echo site_url('admin/CompanyController/addContactDetails');?>",
                method : "POST",
                data : {id: '1',adminEmail:adminEmail,adminMobile:adminMobile,smsNumber:smsNumber,email:email},
                success: function(data){
                  alert('Details Updated');
                    location.reload(); 
                }
            });
        // }
      
       
         
    });

 </script>

 <script type="text/javascript">
    $(document).on('click','#market_pen_id',function(){
        var name=$('#name').val();
         $.ajax({
            url : "<?php echo site_url('admin/EmployeeController/updateEmpCode');?>",
            method : "POST",
            data : {id: '1',name:name},
            success: function(data){
              alert('Code Updated');
                location.reload(); 
            }
        });
    });

    $("input[type='text']").on("click", function () {
       $(this).select();
    });
 </script>

 <script>
    function checkEmail(email)
    {
        var email =email.value;
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    
        if(!re.test(email))
        {
           document.getElementById('eml').innerText='Please enter valid email id';
        }else{
            email="";
            document.getElementById('eml').innerText='';
            
        }
    }

    function checkEmail1(email)
    {
        var email =email.value;
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    
        if(!re.test(email))
        {
           document.getElementById('eml1').innerText='Please enter valid email id';
        }else{
            email="";
            document.getElementById('eml1').innerText='';
            
        }
    }
</script>

<script>
function mobileCheck(pass){
        var mobile =pass.value;
        var IndNum = /^[0]?[789]\d{9}$/;
        if(mobile.length <10 || mobile.length >10){
            document.getElementById('mbl').innerText='Enter 10 digit mobile number';
        }else{
             if(IndNum.test(mobile)){
                document.getElementById('mbl').innerText="";
            } else{
                document.getElementById('mbl').innerText='please enter valid mobile number';
            }
        }
    }
</script>

<script>
function mobileCheck1(pass){
        var mobile =pass.value;
        var IndNum = /^[0]?[789]\d{9}$/;
        if(mobile.length <10 || mobile.length >10){
            document.getElementById('mbl1').innerText='Enter 10 digit mobile number';
        }else{
             if(IndNum.test(mobile)){
                document.getElementById('mbl1').innerText="";
            } else{
                document.getElementById('mbl1').innerText='please enter valid mobile number';
            }
        }
    }
</script>