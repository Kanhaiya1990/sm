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

.logo_prov {
    border-radius: 30px;
     border: 1px solid black;
    background: red;
    color: black;
    padding: 6px;
    width: 50px;
    height: 50px;
}


</style>

        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
           
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Universal Employee Details
                            </h2>
                           
                        </div>
                        <div class="body">

                          <?php
                                $designation = ($this->session->userdata[$projectSessionName]['designation']);
                                $des=explode(',',$designation);
                                
                           ?>
                            <div class="table-responsive">
                                <table style="font-size: 12px" class="table table-bordered table-striped table-hover dataTable js-exportable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Employee Code</th>
                                            <th>Login ID</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Mobile</th>
                                            <th>Role</th>
                                            <th>Change Password</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                         <tr>
                                            <th>Sr.No</th>
                                            <th>Employee Code</th>
                                            <th>Login ID</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Mobile</th>
                                            <th>Role</th>
                                            <th>Change Password</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        $no=0;
                                        $srNo=0;
                                        foreach ($employee as $data) 
                                          {
                                           $srNo++;
                                        ?>
                                        <tr>
                                           <td><?php echo $srNo.' '; 
                                            // if($data['isDeleted']>0){ echo '<span class="logo_prov">DL</span>'; };
                                            // if($data['isSalaryEmp']==0){ echo '<span class="logo_prov">NS</span>'; };
                                            // if($data['isLoginEmp']==0){ echo '<span class="logo_prov">NL</span>'; };
                                            ?></td>
                                            <td><?php echo $data['code']; ?></td>
                                            <td><?php echo $data['email']; ?></td>
                                            <td><?php  
                                                $str=$data['name']; 
                                                $exploded=explode(" ",$str);
                                                if(!empty($exploded[0])){
                                                     echo $firstname = $exploded[0];
                                                }
                                                ?>
                                            </td>
                                             <td><?php  
                                                $str=$data['name']; 
                                                $exploded=explode(" ",$str);
                                                if(!empty($exploded[1])){
                                                     echo $firstname = $exploded[1];
                                                }

                                                if(!empty($exploded[2])){
                                                     echo " ".$firstname = $exploded[2];
                                                }
                                               
                                                ?>
                                            </td>
                                            <td><?php echo $data['mobile']; ?></td>
                                            <td><?php echo $data['designation']; ?></td>
                                            <td>
                                                <a id="empDetailId" data-id="<?php echo $data['id']; ?>" data-toggle="modal" data-target="#updatePasswordModal" href="">
                                                  <i class="btn btn-xs btn-primary material-icons">edit</i>
                                                </a>
                                            </td>
                                    
                                        </tr>
                                        <?php
                                        $no++; 
                                            }
                                        ?> 
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

    <div class="modal fade" id="updatePasswordModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Change Password?</h4>
          </div>
          <div id="up_limitid" class="modal-body">
                <div class="row clearfix">
                  <input autocomplete="off"  type="hidden" id="userId" name="userId" class="form-control date" placeholder="Enter userId">        
                    
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
                    <div class="row">
                            <div id="passDiv" class="col-xs-4">
                                <button id="save_id_pass" class="btn btn-block bg-primary waves-effect" >Save password</button>
                            </div>

                            <div class="col-xs-4">
                                <button data-dismiss="modal" class="btn btn-block bg-primary waves-effect" >Cancel</button>
                            </div>
                    </div>
                </div>
          </div>
      </div>
    </div>
  </div>

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
        url: "<?php echo site_url('admin/EmployeeController/delete');?>",
        type: "post",
        data: {'id':id},
        success: function (response) {
         
          swal(response, {
            icon: "success",
          });
          var URL = "<?php echo site_url('admin/EmployeeController');?>";
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


<script type="text/javascript">
    $(document).on('click','#emp_det_id',function(){
         var id=$(this).attr('data-id');
         $.ajax({
            url : "<?php echo site_url('admin/EmployeeController/empDetails');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
                $('.modal-body').html(data);
            }
        });
    });

 </script>

 <script type="text/javascript">
    $(document).on('click','#empDetailId',function(){
         var id=$(this).attr('data-id');
         $('#userId').val(id);
    });
 </script>

<script type="text/javascript">
    $(document).on('click','#save_id_pass',function(){
        var userId=$('#userId').val();
        var newPassword=$('#newPassword').val();
        var confirmPassword=$('#confirmPassword').val();

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
            url: "<?php echo base_url(); ?>index.php/UserAuthentication/updateUniversalEmployeePassword",
            data:{userId:userId,newPassword:newPassword},
            success:function(response)
            {
                if(response.trim()=="Password changed Successfully."){
                    alert(response);
                     window.location.href="<?php echo base_url();?>index.php/UserAuthentication/user_login_process";
                }else{
                    $('#res').html('<span style="color: blue;">'+response+'</span>');
                }
            }
        });
    });
</script>