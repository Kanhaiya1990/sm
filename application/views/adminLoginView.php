<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title> KIAS </title>
    <!-- Favicon-->
    <link rel="icon" href="<?php echo base_url('assets/uploads/favicon.ico');?>" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="<?php echo base_url('assets/plugins/bootstrap/css/bootstrap.css');?>" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?php echo base_url('assets/plugins/node-waves/waves.css');?>" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="<?php echo base_url('assets/plugins/animate-css/animate.css');?>" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="<?php echo base_url('assets/css/style.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/custom.css');?>" rel="stylesheet">
     <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/login-sliders/css/demo.css');?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/login-sliders/css/style2.css');?>" />
    <script type="text/javascript" src="js/modernizr.custom.86080.js"></script>
</head>

<body class="login-page" style="background-color: #607D8B">
       <!--  <ul class="cb-slideshow">
            <li><span>Image 01</span><div><h3>KIA SALES</h3></div></li>
            <li><span>Image 02</span><div><h3>NESTLE</h3></div></li>
            <li><span>Image 03</span><div><h3>ITC</h3></div></li>
            <li><span>Image 04</span><div><h3>PARLE</h3></div></li>
           
        </ul> -->
        <div class="container">
            <header>
                <img width="25%" height="20%" src="<?php echo base_url('assets/uploads/KiAS Logo 1024x512.png'); ?>" alt="Italian Trulli">
               
                <!-- <h1><span>Smart Distributor</span></h1>
                <h2>Welcome To Smart Distributor</h2> -->
            </header>
        </div>

    <div class="login-box">
       <!--  <div class="logo">
            <a href="javascript:void(0);"><b>Smart Distributor</b></a>
            <small> Welcome To Smart Distributor</small>
        </div> -->
        <div class="card">
            <div class="body">
                 
                 <?php echo form_open('UserAuthentication/adminLogin'); ?> 
                    <div class="msg">Sign in to start your session</div>
                        <?php
                            if (isset($message_display)) {
                            echo "<div class='alert alert-success'>";
                            //echo $message_display;
                            echo "<h3><b> $message_display </b></h3>";
                            echo "</div>";
                            }
                        ?>
                 
                    <?php
                    if(!empty($logout_message)){?>
                        <div class="alert alert-success">
                    <?php
                        echo '<p class="statusMsg" >'.$logout_message.'</p>';?>
                    </div>
                        <?php
                    }
                    elseif(!empty($error_message)) {?>
                    <div class="alert alert-danger">
                    <?php
                        echo '<p class="statusMsg">'.$error_message.'</p>';?>
                    </div>
                        <?php
                        //echo 'validation_errors()';
                    }
                    ?>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" autocomplete="off" class="form-control" name="email" placeholder="Username" required autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" autocomplete="off" class="form-control" name="password" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-8 p-t-5">
                            Forgot Password ? <a data-toggle="modal" data-target="#updatelimitModal" href="">Click here</a>
                        </div>
                        <div class="col-xs-4 p-t-5">
                            <a href="<?php echo base_url()."index.php/UserAuthentication/user_login_process"; ?>">Home</a>
                            <!-- <button type="submit" class="btn btn-block bg-primary waves-effect" >SIGN IN</button> -->
                        </div>
                         <div class="col-xs-12 p-t-5">
                            <button type="submit" class="btn btn-block btnStyle waves-effect" >SIGN IN</button>
                        </div>
                    </div>
                   <!--  <div class="row m-t-15 m-b--20">
                        <div class="col-xs-6">
                            <a href="<?php echo base_url('assets/pages/examples/sign-up.html');?>">Register Now!</a>
                        </div>
                        <div class="col-xs-6 align-right">
                            <a href="<?php echo base_url('assets/pages/examples/forgot-password.html');?>">Forgot Password?</a>
                        </div>
                    </div> -->
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>



    <div class="modal fade" id="updatelimitModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">Forgot/Change Password?</h4>
          </div>
          <div id="up_limitid" class="modal-body">
            <!-- <form method="post" action="<?php echo site_url('UserAuthentication/createPassword'); ?>" role="form"> -->
                <div class="row clearfix">
                <div class="col-md-12">
                    <div class=" input-group">
                            
                            <?php  
                                $admin=$this->LoginDatabase->getdata('admin_login');
                                $mobile="";
                                if(!empty($admin)){
                                     $mobile=$admin[0]['adminMobile'];
                                }
                            ?>

                             <input type="hidden" autocomplete="off" onkeypress="return numbersonly(this, event);" class="form-control" value="<?php echo $mobile; ?>" id="mobile" name="mobile" required>
                            <!-- <div class="col-md-4 form-line">
                                <input type="hidden" autocomplete="off" onkeypress="return numbersonly(this, event);" class="form-control" value="<?php echo $mobile; ?>" id="mobile" name="mobile" placeholder="Enter registered mobile number" required>

                            </div> -->
                            <p style="color:red" id="mbl"></p>
                    </div>
                    
                </div>

                <div id="divForPass" style="display: none;" class="col-md-12">
                    <div class=" input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">person</i> 
                            </span>
                            <div class="col-md-4 form-line">
                                <input type="text" onkeypress="return numbersonly(this, event);" class="form-control" id="otp" name="otp" placeholder="Enter otp" required>
                            </div>
                            <p style="color:red" id="otpCheck"></p>
                     </div>

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
                        <div id="otpDiv" style="display: block" class="col-xs-4">
                            <button  id="sbmt_id" class="btn btn-block bg-primary waves-effect" >Send OTP</button>
                        </div>
                        <div id="passDiv" style="display: none" class="col-xs-4">
                            <button id="save_id_pass" class="btn btn-block bg-primary waves-effect" >Save password</button>
                        </div>

                        <div class="col-xs-4">
                            <button data-dismiss="modal" class="btn btn-block bg-primary waves-effect" >Cancel</button>
                        </div>
                </div>
                </div>
            <!-- </form> -->
          </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    $(document).on('hidden.bs.modal','#updatelimitModal',function(){
        $(this).find("input:not([type=hidden]),textarea,select").val('').end().find("input[type=checkbox], input[type=radio]").prop("checked", "").end();

        $("#divForPass").css("display", "none");
        $("#otpDiv").css("display", "block");
        $("#passDiv").css("display", "none");
        $("#mobile").prop("disabled", false);

    });
  </script>

   <!--  <center><a href="<?php echo site_url('DashbordController');?>"><button type="button" class="btn btn-success"><span class="icon-name"> Next </span>  <i class="material-icons">navigate_next</i> </button></a></center> -->
    <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>

    <!-- Bootstrap Core Js -->
    <script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.js');?>"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/node-waves/waves.js');?>"></script>

    <!-- Validation Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/jquery-validation/jquery.validate.js');?>"></script>

    <!-- Custom Js -->
    <script src="<?php echo base_url('assets/js/admin.js');?>"></script>
    <script src="<?php echo base_url('assets/js/pages/examples/sign-in.js');?>"></script>
    <script src="<?php echo base_url('assets/login-sliders/js/modernizr.custom.86080.js');?>"></script>

<script type="text/javascript">
    $(document).on('click','#sbmt_id',function(){
        var mobile=$('#mobile').val();

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
                        url: "<?php echo base_url(); ?>index.php/UserAuthentication/createAdminPassword",
                        data:{mobile:mobile},
                        success:function(response)
                        {
                            $('#mbl').html('<span style="color: blue;">'+response+'</span>');
                            // $("#divForPass").prop("display", "");
                            
                            
                            $("#mobile").prop("disabled", true);
                        }
                    });
                } else{
                    document.getElementById('mbl').innerText='Please enter valid mobile number';
                }
            }
        });
</script>

<script type="text/javascript">
    $(document).on('click','#save_id_pass',function(){
        var mobile=$('#mobile').val();
        var otp=$('#otp').val();
        var newPassword=$('#newPassword').val();
        var confirmPassword=$('#confirmPassword').val();

        if(otp.trim()===""){
            $('#otpCheck').html('<span style="color: red;">Please enter otp</span>'); die();
        }else{
            $('#otpCheck').html('');
        }

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
            url: "<?php echo base_url(); ?>index.php/UserAuthentication/saveCreatedAdminPassword",
            data:{mobile:mobile,otp:otp,newPassword:newPassword},
            success:function(response)
            {
                // alert(response);
                if(response.trim()=="Password changed Successfully."){
                    alert(response);
                     window.location.href="<?php echo base_url();?>index.php/UserAuthentication/adminLogin";
                    // $('#res').html('<span style="color: blue;">'+response+'</span>');
                    // $('#mbl').html("");
                    // $('#mobile').val("");
                    // $('#otp').val("");
                    // $('#newPassword').val("");
                    // $('#confirmPassword').val("");
                }else{
                    $('#res').html('<span style="color: blue;">'+response+'</span>');
                }
                
            }
        });
        
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
</body>



 

</html>
