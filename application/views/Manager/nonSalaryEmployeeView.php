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

<style type="text/css">
    @media screen and (min-width: 800px) {
        .modal-dialog {
          width: 800px; /* New width for default modal */
        }
        .modal-sm {
          width: 800px; /* New width for small modal */
        }
    }

    @media screen and (min-width: 800px) {
        .modal-lg {
          width: 800px; /* New width for large modal */
        }
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
                               Non Salary Employee Details
                            </h2>
                            <h2>
                            
                                <p align="right">
                                   <a href="<?php echo site_url('manager/EmployeeController');?>">
                                    <button type="submit" class="btn btn-xs bg-primary margin"><i class="material-icons">visibility</i> Salary Employee  </button>
                                  </a> 
                                

                                <a href="<?php echo site_url('manager/EmployeeController');?>">
                                    <button type="button" class="btn btn-xs bg-primary margin"><i class="material-icons">airplanemode_active</i>  Active  </button>
                                  </a> 

                                  <a href="<?php echo site_url('manager/EmployeeController/inactiveEmployee');?>">
                                    <button type="button" class="btn btn-xs bg-primary margin"><i class="material-icons">airplanemode_inactive</i>  Inactive  </button>
                                  </a> 
                                  
                                    <button data-toggle="modal" data-target="#nonSalaryModal" type="button" class="btn btn-xs bg-primary margin"><i class="material-icons">person_add</i>  Add  </button>
                                </p> 
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
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Mobile</th>
                                            <th>Company</th>
                                            <th>Role</th>
                                          <?php if ((in_array('owner', $des))) {  ?>   
                                            <th>Salary</th> 
                                          <?php } ?>
                                            <th>Current Balance</th>
                                            <th>ID Proof</th>
                                            <th>Address Proof</th>
                                            <th>Status </th>
                                            <th>Edit</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                         <tr>
                                            <th>Sr.No</th>
                                            <th>Employee Code</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Mobile</th>
                                            <th>Company</th>
                                            <th>Role</th>
                                           <?php if ((in_array('owner', $des))) {  ?>   
                                            <th>Salary</th> 
                                          <?php } ?>
                                            <th>Current Balance</th>
                                            <th>ID Proof</th>
                                            <th>Address Proof</th>
                                            <th>Status </th>
                                            <th>Edit</th>
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
                                            if($data['isDeleted']>0){ echo '<span class="logo_prov">DL</span>'; };
                                            if($data['isSalaryEmp']==0){ echo '<span class="logo_prov">NS</span>'; };
                                            ?></td>
                                            <td><?php echo $data['code']; ?></td>
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
                                               
                                                ?>
                                            </td>
                                            <td><?php echo $data['mobile']; ?></td>
                                            <td><?php echo $data['companyName']; ?></td>
                                            <td><?php echo $data['designation']; ?></td>
                                        <?php if ((in_array('owner', $des))) {  ?>   
                                            <td><?php echo $data['salary']; ?></td>
                                         <?php } ?>
                                             <td>
                                              <?php 
                                                if($balance[$no]<0){ ?>
                                                  <a class="btn btn-xs btn-dark waves-effect" href="<?php echo site_url('manager/EmployeeController/employeeLedgerByEmp/'.$data['id']); ?>">
                                                  <?php  echo '<span style="color:red">'.str_replace('-','',intval($balance[$no])).' dr</span></a>'; 
                                                }else if($balance[$no]>0){ ?>
                                                   <a class="btn btn-xs btn-dark waves-effect" href="<?php echo site_url('manager/EmployeeController/employeeLedgerByEmp/'.$data['id']); ?>">
                                                <?php    echo '<span style="color:blue">'.intval($balance[$no]).' cr</span></a>'; 
                                                }else{
                                                  if($data['isSalaryEmp']==1){
                                                    echo 0;
                                                  }else{
                                                    echo 0;
                                                  }
                                                }
                                            ?>
                                            </td>
                                            <td>
                                              <?php if(!empty($data['idProofImage'])){ ?>
                                              <a download href="<?php echo base_url().'assets/uploads/'.$data['idProofImage']; ?>" ><i class="material-icons" style="color: blue;">get_app</i> </a>
                                            <?php } ?>
                                            </td>
                                            <td>
                                              <?php if(!empty($data['idProofImage'])){ ?>
                                              <a download href="<?php echo base_url().'assets/uploads/'.$data['addrProofImage']; ?>" ><i class="material-icons" style="color: blue;">get_app</i> </a>
                                               <?php } ?>
                                            </td>
                                           
                                            <td><?php 
                                                $id = $data['id'];
                                                $status1 = $data['status'];                    
                                                if($data['status']==1 && $data['isDeleted']==0) { 
                                            ?>
                                                <a href='<?php echo site_url("manager/EmployeeController/updateStatus/".$id."/".$status1);?>'><i class="material-icons" style="color: green;">add_circle</i></a>
                                            <?php }else if($data['status']==0 && $data['isDeleted']==0){  ?>
                                                <a href='<?php echo site_url("manager/EmployeeController/updateStatus/".$id."/".$status1);?>'><i class="material-icons" style="color: red;">remove_circle</i></a></a>
                                            <?php } ?>     
                                            </td>
                                            <td>
                                            
                                            <?php if($data['isDeleted']==0){ ?>
                                            <a href="<?php echo base_url().'index.php/manager/EmployeeController/load/'.$data['id']; ?>">
                                                <i class="material-icons" style="color: green;">visibility</i>
                                            </a>
                                          <?php } ?>
                                         
                                                                                            
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

    <div class="modal fade" id="SalaryAdvanceModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
            <center><h4 class="modal-title">Upload Salary/Advance File</h4></center>
          </div>
          <div class="modal-body">
           <form method="post" role="form"  enctype="multipart/form-data"  action="<?php echo site_url('manager/EmployeeController/uploadEmpSalaryAdvanceDetail');?>"> 
                      <div class="col-md-12">
                          <div class="col-md-6">
                              <b> Salary/Advance File </b>
                                <div class="input-group">
                                  <span class="input-group-addon"></span>
                                   <input type="file" name="file" class="form-control" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">                               
                                </div>
                          </div>
                          
                       <div class="col-md-6">
                              <div class="input-group">
                          <button type="submit" class="btn bg-primary m-t-25 margin">Upload</button>
                          </div>
                              </div>
                       </div>
              </form>
          </div>
          <div class="modal-footer">
          
          </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="nonSalaryModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
            <center><h4 class="modal-title">Add Non-Salary Employee</h4></center>
          </div>
          <div class="modal-body">
                <form method="post" role="form" onsubmit="return onEmpSubmit();"  enctype="multipart/form-data"  action="<?php echo site_url('manager/EmployeeController/addNonSalaryEmployee');?>"> 
                            
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <b>First Name</b>
                            <div class="input-group">
                                <span class="input-group-addon">
                                     <i class="material-icons">check_circle</i>
                                </span>
                                <div class="form-line">
                                    <input autocomplete="off" required type="text" name="firstName" class="form-control date" placeholder="Enter first name">
                                </div>
                            </div>
                        </div> 

                        <div class="col-md-4">
                            <b>Last Name</b>
                            <div class="input-group">
                                <span class="input-group-addon">
                                     <i class="material-icons">check_circle</i>
                                </span>
                                <div class="form-line">
                                    <input autocomplete="off" required type="text" name="lastName" class="form-control date" placeholder="Enter last name">
                                </div>
                            </div>
                        </div> 

                        <div class="col-md-4">
                            <b>Mobile</b>
                            <div class="input-group">
                                <span class="input-group-addon">
                                     <i class="material-icons">check_circle</i>
                                </span>
                                <div class="form-line">
                                    <input autocomplete="off" onkeypress="return numbersonly(event)" onblur="mobileCheck(this);" type="text" name="mobile" class="form-control date" placeholder="Enter mobile">
                                </div>
                                 <span style="color:red" id="mblSal"></span>
                            </div>
                        </div>
                       
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-xs bg-primary margin"><i class="material-icons">person_add</i> Save</button>
                        </div>
                    </div>
                </form>
          </div>
          <div class="modal-footer">
          
          </div>
      </div>
    </div>
  </div>


<div class="modal fade" id="limitModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">Employee Details</h4>
          </div>
          <div class="modal-body">
         
          </div>
      </div>
    </div>
  </div>
<?php $this->load->view('/layouts/footerDataTable'); ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
     function mobileCheck(pass){
        var mobile =pass.value;
        var IndNum = /^[0]?[789]\d{9}$/;
        if(mobile.length <10 || mobile.length >10){
            document.getElementById('mblSal').innerText='Enter 10 digit mobile number';
        }else{
             if(IndNum.test(mobile)){
                $.ajax(
                {
                    type:"post",
                    url: "<?php echo base_url(); ?>index.php/manager/EmployeeController/checkValuesByMobile",
                    data:{mobile:mobile},
                    success:function(response)
                    {
                        $('#mblSal').html('<span style="color: red;">'+response+'</span>');
                    }
                });
            } else{
                document.getElementById('mblSal').innerText='please enter valid mobile number';
            }
        }
    }

    function onEmpSubmit(){
        var mbl=document.getElementById('mblSal').innerText;
        if(mbl.trim()==""){
          document.getElementById('mblSal').innerText="";
            return true;
        }else{
            return false;
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
