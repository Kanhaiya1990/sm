<?php $this->load->view('/layouts/commanHeader'); ?>
<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
<section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            
            <!-- Masked Input -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Change Password 
                            </h2>
                        </div>
                        <form method="post" role="form" onsubmit="return checkPass();" action="<?php echo site_url('UserAuthentication/updatePassword'); ?>"> 
                        <div class="body">
                            <div class="demo-masked-input">
                               
                                <div class="row clearfix">
                                    
                                  <div class="col-md-6">
                                        <b>New Password</b>
                                        <div class="input-group">
                                            
                                            <div class="form-line">
                                                <input type="password" id="newPass" name="newPass" autocomplete="off" class="form-control date" placeholder="Enter new password" value="">
                                                     
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="col-md-6">
                                        <b>Confirm Password</b>
                                        <div class="input-group">
                                            
                                            <div class="form-line">
                                                <input type="password" id="confPass" name="confPass" autocomplete="off"  class="form-control date" placeholder="Enter confirm password" value="">
                                                     
                                            </div>
                                        </div>
                                    </div>  
                                  
                                     <div class="col-md-12">
                                         <span id="res" style="color:red"></span>
                                <?php if($this->session->flashdata('msg')){ 
                                ?>     
                                <span id="suc" style><?php echo $this->session->flashdata('msg'); ?></span>   
                                  <?php } ?>
                                        <div class="row clearfix">
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name">Save</span>
                                                </button>
                                                <a href="<?php echo site_url('admin/CompanyController/');?>">
                                                    <button type="button" class="btn btn-primary m-t-15 waves-effect">
                                                        <i class="material-icons">cancel</i> 
                                                        <span class="icon-name"> Cancel</span>
                                                    </button>
                                                </a>   
                                            </div>

                                        </div>
                                    </div>                            
                                </div>

                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
           
        </div>
    </section>

<?php $this->load->view('/layouts/footerDataTable'); ?>

<script type="text/javascript">

    function checkPass(){
        var newPass=document.getElementById('newPass').value;
        var confPass=document.getElementById('confPass').value;
        if(newPass===confPass){
            return true;
        }else{
            document.getElementById('res').innerHTML='Both password should be same';
            return false;
        }
    }

    $(document).on('click','#save_res',function(){
        var newPass=$('#newPass').val();
        var confPass=$('#confPass').val();

        $.ajax({
            url : "<?php echo site_url('UserAuthentication/updatePassword');?>",
            method : "POST",
            data : {newPass:newPass,confPass:confPass},
            success: function(data){
               $('#res').innerHTML(data);
            }
        });
    });
 </script>
