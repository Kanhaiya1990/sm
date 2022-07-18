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
                               Employee Details
                            </h2>
                            <h2>
                            
                                <p align="right">
                                  <!-- <a href="<?php echo site_url('manager/EmployeeController/getNonSalaryEmployee');?>">
                                    <button type="submit" class="btn btn-xs bg-primary margin"><i class="material-icons">visibility</i>  Non Salary Employee  </button>
                                  </a>  -->

                            

                                <a href="<?php echo site_url('manager/EmployeeController');?>">
                                    <button type="submit" class="btn btn-xs btnStyle bg-primary margin"><i class="material-icons">how_to_reg</i>  Active  </button>
                                  </a> 

                                  <a href="<?php echo site_url('manager/EmployeeController/inactiveEmployee');?>">
                                    <button type="submit" class="btn btn-xs btnStyle bg-primary margin"><i class="material-icons">person_add_disabled</i>  Inactive  </button>
                                  </a> 
                                  <a href="<?php echo site_url('manager/EmployeeController/add');?>">
                                    <button type="submit" class="btn btn-xs btnStyle bg-primary margin"><i class="material-icons">person_add</i>  Add Employee </button></a> 
                                    <!-- <button data-toggle="modal" data-target="#nonSalaryModal" type="button" class="btn btn-xs bg-primary margin"><i class="material-icons">person_add</i>  Add Non Salary Employee </button> -->
                                </p> 
                            </h2>
                        </div>
                        <div class="body">

                          <?php
                                $designation = ($this->session->userdata[$projectSessionName]['designation']);
                                $des=explode(',',$designation);
                                
                           ?>
                            <div class="table-responsive">
                              <?php 
                                    if(!empty($this->session->flashdata('item'))){
                                        $msg=$this->session->flashdata('item');
                                        echo '<p class="flashMsg flashError" style="color:red">'.$msg['message'].'</p>';
                                    }
                              ?>
                                <table class="table table-bordered cust-tbl dataTable js-exportable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th class="noSpace">Employee Code</th>
                                            <th>FirstName</th>
                                            <th>LastName</th>
                                            <th>Mobile</th>
                                            <th>Company</th>
                                            <th>Role</th>
                                          <?php if ((in_array('owner', $des))) {  ?>   
                                            <th>Salary</th> 
                                          <?php } ?>
                                            <th>Balance</th>
                                            <th class="noSpace">ID Proof</th>
                                            <th class="noSpace">Address Proof</th>
                                            <th>Type</th>
                                            <th>Status </th>
                                            <th class="noSpace">Disable Login </th>
                                            <th>Edit</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                         <tr>          
                                            <th>No</th>
                                            <th class="noSpace">Employee Code</th>
                                            <th>FirstName</th>
                                            <th>LastName</th>
                                            <th>Mobile</th>
                                            <th>Company</th>
                                            <th>Role</th>
                                           <?php if ((in_array('owner', $des))) {  ?>   
                                            <th>Salary</th> 
                                          <?php } ?>
                                            <th>Balance</th>
                                            <th class="noSpace">ID Proof</th>
                                            <th class="noSpace">Address Proof</th>
                                            <th>Type</th>
                                            <th>Status </th>
                                            <th class="noSpace">Disable Login </th>
                                            <th>Edit</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        $no=0;
                                        $srNo=0;
                                        foreach ($employee as $data) 
                                          {
                                           
                                           if($data['code'] != "SUSINC"){
                                            $srNo++;
                                           

                                           
                                        ?>
                                        <tr>
                                           <td><?php echo $srNo.' '; 
                                            // if($data['isDeleted']>0){ echo '<span class="logo_prov">DL</span>'; };
                                            // if($data['isSalaryEmp']==0){ echo '<span class="logo_prov">NS</span>'; };
                                            // if($data['isLoginEmp']==0){ echo '<span class="logo_prov">NL</span>'; };
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

                                                if(!empty($exploded[2])){
                                                     echo " ".$firstname = $exploded[2];
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
                                                  if($data['isSalaryEmp']==1){ ?>
                                                    <a class="btn btn-xs btn-dark waves-effect" href="<?php echo site_url('manager/EmployeeController/employeeLedgerByEmp/'.$data['id']); ?>">
                                                <?php       echo '<span style="color:blue">0</span></a>'; 
                                                  }else{
                                                ?>
                                                  <a class="btn btn-xs btn-dark waves-effect" href="<?php echo site_url('manager/EmployeeController/employeeLedgerByEmp/'.$data['id']); ?>"><span style="color:blue">0</span></a>
                                                <?php    
                                                  }
                                                }
                                            ?>
                                            </td>
                                            <td>
                                              <?php if(!empty($data['idProofImage'])){ ?>
                                              <a download href="<?php echo base_url().'assets/uploads/'.$data['idProofImage']; ?>" ><i class="material-icons" style="color: blue;">perm_identity</i> </a>
                                            <?php } ?>
                                            </td>
                                            <td>
                                              <?php if(!empty($data['idProofImage'])){ ?>
                                              <a download href="<?php echo base_url().'assets/uploads/'.$data['addrProofImage']; ?>" ><i class="material-icons" style="color: blue;">contact_mail</i> </a>
                                               <?php } ?>
                                            </td>
                                            <td>
                                            <?php 
                                                if(($data['isLoginEmp']==1) && ($data['status']==1)){ ?>
                                                    <i class="material-icons" style="color: green;">monetization_on</i>
                                              <?php  } else{ ?>
                                                    <i class="material-icons" style="color: green;">money_off</i>
                                                <?php }
                                            ?>
                                            </td>
                                           
                                            <td><?php 
                                                $id = $data['id'];
                                                $status1 = $data['status'];                    
                                                if($data['status']==1 && $data['isDeleted']==0) { 
                                            ?>
                                                <a href='<?php echo site_url("manager/EmployeeController/updateStatus/".$id."/".$status1);?>'><i class="material-icons" style="color: green;">toggle_on</i></a>
                                            <?php }else if($data['status']==0 && $data['isDeleted']==0){  ?>
                                                <a href='<?php echo site_url("manager/EmployeeController/updateStatus/".$id."/".$status1);?>'><i class="material-icons" style="color: red;">toggle_off</i></a></a>
                                            <?php } ?>     
                                            </td>
                                            <td>
                                              <?php if($data['status']==2){?>
                                              <a href='<?php echo site_url("manager/EmployeeController/updateStatus/".$id."/0");?>'>
                                                <i class="material-icons" style="color: green;">add_circle</i>
                                            </a>
                                              <?php } else{ ?>
                                             <a href='<?php echo site_url("manager/EmployeeController/updateStatus/".$id."/2");?>'>
                                                <i class="material-icons" style="color: red;">cancel</i>
                                            </a>
                                            <?php } ?>
                                            </td>
                                            <td>
                                            
                                            <?php if($data['isDeleted']==0){ ?>
                                            <a href="<?php echo base_url().'index.php/manager/EmployeeController/load/'.$data['id']; ?>">
                                                <i class="material-icons" style="color: green;">create</i>
                                            </a>
                                          <?php } 

                                          ?>
                                         
                                                                                            
                                        </td>
                                    
                                        </tr>
                                        <?php
                                        
                                            }
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
        url: "<?php echo site_url('manager/EmployeeController/delete');?>",
        type: "post",
        data: {'id':id},
        success: function (response) {
         
          swal(response, {
            icon: "success",
          });
          var URL = "<?php echo site_url('manager/EmployeeController');?>";
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
            url : "<?php echo site_url('manager/EmployeeController/empDetails');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
                $('.modal-body').html(data);
            }
        });
    });

 </script>
