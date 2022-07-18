<!-- Jquery Core Js -->
<?php 

$projectSessionName="";
if (isset($this->session->userdata['codeKeyData'])) {
    $projectSessionName= ($this->session->userdata['codeKeyData']['codeKeyValue']);
}
?>
    <div class="modal fade" id="empProDetails" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <div class="col-xs-4"> -->
                            <p align="right">   <button type="button" data-dismiss="modal" class="btn btn-block bg-primary waves-effect" >Cancel</button></p>
                            <!-- </div> -->
            <h4 class="modal-title"><center>Employee Details</center></h4>
          </div>
          <div id="detail_emp" class="modal-body">
         
          </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="updateChangePasswordlimitModal1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">Change Password?</h4>
          </div>
          <div id="up_limitid" class="modal-body">
            <!-- <form method="post" action="<?php echo site_url('UserAuthentication/createPassword'); ?>" role="form"> -->
                <div class="row clearfix">
                <div class="col-md-12">
                    
                     <div class=" input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">person</i> 
                            </span>
                            <div class="col-md-4 form-line">
                                <input type="text" onkeypress="return numbersonly(this, event);" class="form-control" id="mobileChange" readonly value="<?php echo ($this->session->userdata[$projectSessionName]['mobile']); ?>" name="mobile" placeholder="Enter registered mobile number" required>
                            </div>
                            
                        </div>
                         <p id='mbl' style="color:blue"></p>
                    </div>
                    <div class="row">
                            <div class="col-xs-4">
                                <button id="sbmt_change_id" class="btn btn-block bg-primary waves-effect" >Send OTP</button>
                                <!-- <button data-dismiss="modal" class="btn btn-block bg-primary waves-effect" >Cancel</button> -->
                            </div>
                            <div class="col-xs-4">
                               <button type="button" data-dismiss="modal" class="btn btn-block bg-primary waves-effect" >Cancel</button>
                            </div>
                        </div>
                </div>
            <!-- </form> -->
          </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="updateChangePasswordlimitModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">Forgot/Change Password?</h4>
          </div>
          <div id="up_limitid" class="modal-body">
            <!-- <form method="post" action="<?php echo site_url('UserAuthentication/createPassword'); ?>" role="form"> -->
                <div class="row clearfix">
                <!-- <div class="col-md-12">
                    <div class="  input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">person</i> 
                            </span>
                            <?php $mbl= ($this->session->userdata[$projectSessionName]['mobile']); ?>
                            <div class="col-md-4 form-line">
                                 <input type="hidden" onkeypress="return numbersonly(<?php echo $mbl; ?>, event);" class="form-control" id="mobileChange" value="<?php echo $mbl; ?>" name="mobile" placeholder="Enter registered mobile number" required>

                            </div>
                            <p style="color:red" id="mblData"></p>
                    </div>
                    
                </div> -->
                <?php $mbl= ($this->session->userdata[$projectSessionName]['mobile']); ?>
                <input type="hidden" onkeypress="return numbersonly(<?php echo $mbl; ?>, event);" class="form-control" id="mobileChange" value="<?php echo $mbl; ?>" name="mobile" placeholder="Enter registered mobile number" required>


                <div id="divForPass" class="col-md-12">
                    <!-- <div class=" input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">person</i> 
                            </span>
                            <div class="col-md-4 form-line">
                                <input type="text" onkeypress="return numbersonly(this, event);" class="form-control" id="otp" name="otp" placeholder="Enter otp" required>
                            </div>
                            <p style="color:red" id="otpCheck"></p>
                     </div> -->

                     <div class=" input-group">
                            
                                 <span class="input-group-addon">
                                    <i class="material-icons">person</i> 
                                </span>
                          
                            <div class="col-md-3 form-line">
                                <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="Enter password" required>
                            </div>
                            <p style="color:red" id="newpassCheck"></p>
                    </div>

                    <div class=" input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">person</i> 
                            </span>
                            <div class="col-md-4 form-line">
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Enter confirm password" required>
                            </div>
                            <p style="color:red" id="confpassCheck"></p>
                            <p style="color:red" id="passCheck"></p>
                            <p style="color:red" id="res"></p>
                            
                    </div>
                    <p id='mbl' style="color:blue"></p>
                </div>
                <div class="row">
                        <!-- <div id="otpDiv" style="display: block" class="col-xs-4">
                            <button  id="sbmt_id_data" class="btn btn-block bg-primary waves-effect" >Send OTP</button>
                        </div> -->
                        <div id="passDiv" class="col-xs-4">
                            <button id="save_id_pass_data" class="btn btn-block bg-primary waves-effect" >Save password</button>
                        </div>

                        <div class="col-xs-4">
                            <button data-dismiss="modal" class="btn btn-block bg-danger waves-effect" >Cancel</button>
                        </div>
                </div>
                </div>
            <!-- </form> -->
          </div>
      </div>
    </div>
  </div>

<button onclick="topFunction()"class="btn btn-xs" id="myBtn" title="Go to top"> <i class="material-icons"><span class="material-icons-outlined">
arrow_upward
</span></i></button>

<div style="display:none" id="divMsg">
        <img src="<?php echo base_url('/assets/uploads/loading.gif');?>" alt="Please wait.." />
</div>






    <script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>

    <!-- Bootstrap Core Js -->
    <script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.j');?>s"></script>

    <!-- Select Plugin Js -->
    <script src="<?php echo base_url('assets/pages/maps/jvectormap.html');?>"></script>

    <!-- Select Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/bootstrap-select/js/bootstrap-select.js');?>"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/jquery-slimscroll/jquery.slimscroll.js');?>"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/node-waves/waves.js');?>"></script>

    <!-- Sparkline Chart Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/jquery-sparkline/jquery.sparkline.js');?>"></script>

    <!-- Jquery DataTable Plugin Js -->
 
    <script src="<?php echo base_url('assets/plugins/jquery-datatable/jquery.dataTables.js');?>"></script>
    <script src="<?php echo base_url('assets/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js');?>"></script>
    <script src="<?php echo base_url('assets/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js');?>"></script>
    <script src="<?php echo base_url('assets/plugins/jquery-datatable/extensions/export/buttons.flash.min.js');?>"></script>
<!-- 
    <script src=" https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script> -->
   
    <script src="<?php echo base_url('assets/plugins/jquery-datatable/extensions/export/jszip.min.js');?>"></script>
    <script src="<?php echo base_url('assets/plugins/jquery-datatable/extensions/export/pdfmake.min.js');?>"></script>
    <script src="<?php echo base_url('assets/plugins/jquery-datatable/extensions/export/vfs_fonts.js');?>"></script>
    <script src="<?php echo base_url('assets/plugins/jquery-datatable/extensions/export/buttons.html5.min.js');?>"></script>
    <script src="<?php echo base_url('assets/plugins/jquery-datatable/extensions/export/buttons.print.min.js');?>"></script>

    <!-- Custom Js -->
    <script src="<?php echo base_url('assets/js/admin.js');?>"></script>
    <script src="<?php echo base_url('assets/js/pages/tables/jquery-datatable.js');?>"></script>

    <!-- Demo Js -->
    <script src="<?php echo base_url('assets/js/demo.js');?>"></script>
     <!-- SweetAlert Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/bootstrap-notify/bootstrap-notify.js');?>"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> <script src="<?php echo base_url('assets/colorbox-master/jquery.colorbox-min.js');?>"></script>
   <script type="text/javascript">
      $(document).ready(function(){
        $(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
    });
   </script>


    <script src="<?php echo base_url('assets/tableExport/tableExport.js');?>"></script>
    <script src="<?php echo base_url('assets/tableExport/jquery.base64.js');?>" type="text/javascript"></script>

   <script type="text/javascript">
    $(document).on('click','#emp_pro_det_id',function(){
         var id=$(this).attr('data-id');
         $.ajax({
            url : "<?php echo site_url('admin/EmployeeController/empDetails');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
                $('#detail_emp').html(data);
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
    $(document).on('click','#sbmt_change_id',function(){
        var mobile=$('#mobileChange').val();
        alert('hey');
            var IndNum = /^[0]?[789]\d{9}$/;
            if(mobile.length <10 || mobile.length >10){
                document.getElementById('mbl').innerText='Enter 10 digit mobile number';
            }else{
                 if(IndNum.test(mobile)){
                    $.ajax(
                    {
                        type:"post",
                        url: "<?php echo base_url(); ?>index.php/UserAuthentication/createPassword",
                        data:{mobile:mobile},
                        success:function(response)
                        {
                            $('#mbl').html('<span style="color: blue;">'+response+'</span>');
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
                } else{
                    document.getElementById('mbl').innerText='Please enter valid mobile number';
                }
            }
        });
</script>

<script type="text/javascript">
    $(document).on('click','#sbmt_id_data',function(){
        var mobile=$('#mobileChange').val();
alert(mobile);
            var IndNum = /^[0]?[789]\d{9}$/;
            if(mobile.length <10 || mobile.length >10){
                document.getElementById('mbl').innerText='Enter 10 digit mobile number';
            }else{
                $("#divForPass").css("display", "block");
                $("#otpDiv").css("display", "none");
                $("#passDiv").css("display", "block");
                 if(IndNum.test(mobile)){
                    $.ajax(
                    {
                        type:"post",
                        url: "<?php echo base_url(); ?>index.php/UserAuthentication/createPassword",
                        data:{mobile:mobile},
                        success:function(response)
                        {
                            $('#mblData').html('<span style="color: blue;">'+response+'</span>');
                            // $("#divForPass").prop("display", "");
                            
                            
                            $("#mobileChange").prop("disabled", true);
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
                } else{
                    document.getElementById('mblData').innerText='Please enter valid mobile number';
                }
            }
        });
</script>

<script type="text/javascript">
    $(document).on('click','#save_id_pass_data',function(){
        var mobile=$('#mobileChange').val();
        // var otp=$('#otp').val();
        // var mobile="";
        // var otp="";
        var newPassword=$('#newPassword').val();
        var confirmPassword=$('#confirmPassword').val();

        // if(otp.trim()===""){
        //     $('#otpCheck').html('<span style="color: red;">Please enter otp</span>'); die();
        // }else{
        //     $('#otpCheck').html('');
        // }

        if(newPassword.trim()===""){
            $('#newpassCheck').html('<span style="color: red;">Please enter password</span>'); die();
        }else{
            $('#newpassCheck').html('');
        }

        if(confirmPassword.trim()===""){
            $('#confpassCheck').html('<span style="color: red;">Please enter confirm password</span>'); die();
        }else{
            $('#confpassCheck').html('');
        }

        if(confirmPassword.trim() != newPassword.trim()){
            $('#passCheck').html('<span style="color: red;">Please enter correct password</span>'); die();
        }else{
            $('#passCheck').html('');
        }
        
        $.ajax(
        {
            type:"post",
            url: "<?php echo base_url(); ?>index.php/UserAuthentication/saveNewPassword",
            data:{mobile:mobile,newPassword:newPassword},
            success:function(response)
            {
                if(response.trim()=="Password changed Successfully."){
                    alert(response);
                     window.location.href="<?php echo base_url();?>index.php/UserAuthentication/user_login_process";
                    // $('#res').html('<span style="color: blue;">'+response+'</span>');
                    // $('#mbl').html("");
                    // $('#mobile').val("");
                    // $('#otp').val("");
                    // $('#newPassword').val("");
                    // $('#confirmPassword').val("");
                }else{
                    $('#res').html('<span style="color: blue;">'+response+'</span>');
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
        
    });
</script>



<script>
    var mybutton = document.getElementById("myBtn");
    window.onscroll = function() {scrollFunction()};
    function scrollFunction() {
      if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        mybutton.style.display = "block";
      } else {
        mybutton.style.display = "none";
      }
    }

    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
      document.body.scrollTop = 0;
      document.documentElement.scrollTop = 0;
    }
</script>

<script type="text/javascript">
//   $(document).keyup(function(e) {    
//     if (e.keyCode == 27) { //escape key
//         //reload the page if you still need to
//         window.location.reload();
//     }
// });
</script>

<script type="text/javascript">
    $(document).ajaxStart(function(){
        $('#loading').show();
     }).ajaxStop(function(){
        $('#loading').hide();
     });
</script>


<script>
  function planUpgradeMsg() {
    alert('Please renew your subscription to continue.');
  }
</script>