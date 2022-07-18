<?php $this->load->view('/layouts/commanHeader');
    $projectSessionName="";
    if (isset($this->session->userdata['codeKeyData'])) {
        $projectSessionName= ($this->session->userdata['codeKeyData']['codeKeyValue']);
    }

?>

 

    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
          
            <!-- Masked Input -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                <?php 
                              if(isset($employee))
                                {
                                  echo 'Update';
                                }
                              else
                                {
                                  echo 'Add';
                                }
                              ?> Employee 
                            </h2>
                     
                        </div>
                         <?php if(empty($employee)){ ?>
                        <div class="body col-md-12">
                                <p align="right">
                                    <input id="isLoginDiv" checked value="login" type="checkbox"/>
                                    <label for="isLoginDiv" style="font-size:16px"><b>Login Required  </b></label>
                                </p>
                        </div>
                       
                                <?php } ?>

                         <div class="body">
                           
                        <?php
                             if(!empty($employee)){ 
                                if($employee[0]['isLoginEmp'] ==0){
                        ?>        

                        <form id="nonLoginFrm" <?php if(isset($employee)) { if($employee[0]['isLoginEmp'] ==1){ ?>style="display:none" <?php } } ?> method="post" role="form" enctype="multipart/form-data"  action="<?php if(isset($employee)) { echo site_url('admin/EmployeeController/updateNonSalaryEmployee'); }?>"> 
                            <div class="body">
                                <div class="demo-masked-input">
                                    <div class="row clearfix">
                                        <div class="col-md-12">

                                        <input type="hidden" name="nonLoginId" value="<?php if(isset($employee)) { echo $employee[0]['id']; } ?>">

                                        <div class="col-md-4">
                                            <b>First Name</b>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                     <i class="material-icons">check_circle</i>
                                                </span>
                                                <div class="form-line">
                                                    <input autocomplete="off" required type="text" value="<?php if(isset($employee))
                                                    {
                                                       $str=$employee[0]['name']; 
                                                        $exploded=explode(" ",$str);
                                                        echo $firstname = $exploded[0];
                                                    }
                                                    ?>" name="firstName" class="form-control date" placeholder="Enter first name">
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
                                                    <input autocomplete="off" required type="text" value="<?php if(isset($employee))
                                                    {
                                                       $str=$employee[0]['name']; 
                                                        $exploded=explode(" ",$str);
                                                        echo $firstname = $exploded[0];
                                                    }
                                                    ?>" name="lastName" class="form-control date" placeholder="Enter last name">
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
                                                    <input autocomplete="off" value="<?php if(isset($employee))
                                                            {
                                                              echo $employee[0]['mobile']; 
                                                            }
                                                            ?>" onkeypress="return numbersonly(event)" onblur="mobileCheck(this);" type="text" name="mobile" class="form-control date" placeholder="Enter mobile">
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
                                </div>
                            </div>
                        </div>
                                </form>

                    <?php }
                        }else{
                     ?>


                        <form id="nonLoginFrm" style="display:none" method="post" role="form" enctype="multipart/form-data"  action="<?php echo site_url('admin/EmployeeController/addNonSalaryEmployee');?>"> 
                            <div class="body">
                                <div class="demo-masked-input">
                                    <div class="row clearfix">
                                        <div class="col-md-12">
                                        <div class="col-md-4">
                                            <b>First Name</b>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                     <i class="material-icons">check_circle</i>
                                                </span>
                                                <div class="form-line">
                                                    <input autocomplete="off" required type="text" value="<?php if(isset($employee))
                                                    {
                                                       $str=$employee[0]['name']; 
                                                        $exploded=explode(" ",$str);
                                                        echo $firstname = $exploded[0];
                                                    }
                                                    ?>" name="firstName" class="form-control date" placeholder="Enter first name">
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
                                                    <input autocomplete="off" required type="text" value="<?php if(isset($employee))
                                                    {
                                                       $str=$employee[0]['name']; 
                                                        $exploded=explode(" ",$str);
                                                        echo $firstname = $exploded[0];
                                                    }
                                                    ?>" name="lastName" class="form-control date" placeholder="Enter last name">
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
                                                    <input autocomplete="off" value="<?php if(isset($employee))
                                                            {
                                                              echo $employee[0]['mobile']; 
                                                            }
                                                            ?>" onkeypress="return numbersonly(event)" onblur="mobileCheck(this);" type="text" name="mobile" class="form-control date" placeholder="Enter mobile">
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
                                </div>
                            </div>
                        </div>
                                </form>

                     <?php } ?>



                           <!--  <div class="demo-masked-input">
                                <div class="row clearfix"> -->
                                    <?php if(isset($employee)){ ?>
                                        <div class="col-md-9">
                                <form id="frmuser" <?php if($employee[0]['isLoginEmp'] ==0){ ?>style="display:none" <?php } ?> method="post" onsubmit="return onEmpUserNameSubmit();" role="form" action="<?php echo site_url('admin/EmployeeController/updateEmployeeUsername'); ?>">

                                        <input type="hidden" id="updateEmpId" name="updateEmpId" value="<?php if(isset($employee)) { echo $employee[0]['id']; } ?>">

                                    
                                        <div class="col-md-3">
                                            <b> Login ID</b>
                                            <div class="input-group">
                                                <div class="form-line">
                                                    <input type="text" tabindex="1" onblur="checkUpdatedUserName(this);" id="updatelogId" name="updatelogId" class="form-control" required value="<?php if(isset($employee)){ echo $employee[0]['email']; }else{ echo $userEmail; } ?>">
                                                </div>
                                                <span style="color:red" id="usrUpdate"></span>
                                            </div>
                                        </div>

                                    <div class="col-md-3">
                                        <b>First Name</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" tabindex="2" id="updateFirstName" name="updateFirstName" class="form-control date" placeholder="employee first name" required value="<?php if(isset($employee))
                                                    {
                                                       $str=$employee[0]['name']; 
                                                        $exploded=explode(" ",$str);
                                                        echo $firstname = $exploded[0];
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div>
                                     
                                     <div class="col-md-3">
                                        <b> Last Name</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" tabindex="3" id="updatelastName" name="updatelastName" class="form-control date" placeholder="employee last name" required value="<?php if(isset($employee))
                                                    {
                                                        $str=$employee[0]['name']; 
                                                        $exploded=explode(" ",$str);
                                                        echo $lastname = $exploded[1];
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <a id="enabledUsrBtn" class="btn btn-primary m-t-15 waves-effect"> 
                                            <span class="icon-name"> Edit </span>
                                        </a>

                                        <input type="submit" value="Update" class="btn btn-primary m-t-15 waves-effect"> 
                                            
                                    </div>
                               
                                </form>
                                 </div>
                               
                                <div class="col-md-3">
                                    <form id="frmmobile" <?php if($employee[0]['isLoginEmp'] ==0){ ?>style="display:none" <?php } ?> method="post" onsubmit="return onEmpMobileSubmit();" role="form" action="<?php echo site_url('admin/EmployeeController/updateEmployeeMobile'); ?>">
                                        <input type="hidden" id="updateMobileEmpId" name="updateMobileEmpId" value="<?php if(isset($employee)) { echo $employee[0]['id']; } ?>">
                                            <div class="col-md-7">
                                                <b>Mobile</b>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="material-icons">settings_cell</i>
                                                    </span>
                                                    <div class="form-line">
                                                        <input type="text" tabindex="4"  required onblur="updateMobileCheck(this);" id="updatemobile" name="updatemobile" class="form-control time12" placeholder="employee Mobile number" onkeypress="return numbersonly(this, event);" value="<?php if(isset($employee))
                                                            {
                                                              echo $employee[0]['mobile']; 
                                                            }
                                                            ?>">
                                                    </div>
                                                      <span style="color:red" id="updmbl"></span>
                                                </div>
                                            </div> 
                                            <div class="col-md-3">
                                                 <a id="enabledUsrMobile" class="btn btn-primary m-t-15 waves-effect"> 
                                                    <span class="icon-name"> Edit </span>
                                                </a>

                                                 <input type="submit" value="Update" class="btn btn-primary m-t-15 waves-effect"> 
                                                
                                            </div>
                                        </form>
                                         </div>
                                    <?php } ?>
                                <!-- </div>
                            </div> -->
                        </div>


                        <form id="frm" <?php if(isset($employee)){if($employee[0]['isLoginEmp'] ==0){ ?>style="display:none" <?php } } ?> method="post" onsubmit="return onEmpSubmit();" role="form" enctype="multipart/form-data" 
                        action="<?php
                            if(isset($employee))
                            {
                              echo site_url('admin/EmployeeController/update');
                            }
                            else
                            {
                            echo site_url('admin/EmployeeController/insert');
                            }
                        ?>"> 

                        <div class="body">
                            <div class="demo-masked-input">
                                
                                <div class="row clearfix">

                                    <div class="col-md-12">
                                        <div class="col-md-2">
                                            <input type="hidden" name="code" readonly class="form-control" value="<?php if(isset($employee))
                                                    {
                                                      echo $employee[0]['code']; 
                                                    }else{ echo $empCount; }?>">
                                            <b>Employee Image</b><br>
                                        <img src="<?php if(isset($employee)){ echo base_url().'/assets/uploads/'.$employee[0]['profileImage'];  } ?>" id='emp-img-upload' class="border" style="height:90px; max-height: 90px; max-width:90px; width: 90px;" /><br>
                                        <div class="input-group">
                                            <div class="form-line">
                                                <input type="file" onchange="readEmp(this);" name="profileImage" class="form-control time12" placeholder="upload employee Image" value="<?php if(isset($employee))
                                                    {
                                                      echo $employee[0]['profileImage']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                             <b>Id Proof Image</b><br>
                                            <img src="<?php if(isset($employee)){ echo base_url().'/assets/uploads/'.$employee[0]['idProofImage'];  } ?>"  id='id-img-upload' class="border" style="height:90px; max-height: 90px; max-width:90px; width: 90px;" />
                                                <br>
                                       
                                        <div class="input-group">
                                           
                                            <div class="form-line">
                                                <input type="file" onchange="readId(this);" id="idProofImage" name="idProofImage" class="form-control time12 btn-file" placeholder="Id Proof Image" value="<?php if(isset($employee))
                                                    {
                                                      echo $employee[0]['idProofImage']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                       
                                       <b>Address Proof Image</b><br>
                                       <img src="<?php if(isset($employee)){ echo base_url().'/assets/uploads/'.$employee[0]['addrProofImage'];  } ?>"  id='address-img-upload' class="border" style="height:90px; max-height: 90px; max-width:90px; width: 90px;" /><br>
                                        <div class="input-group">
                                            <div class="form-line">
                                                <input type="file" onchange="readAddress(this);" name="addrProofImage" class="form-control time12" placeholder="Enter Address Proof Image" value="<?php if(isset($employee))
                                                    {
                                                      echo $employee[0]['addrProofImage']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>

                                        
                                </div>
                                <div class="col-md-10">
                                            <input type="hidden" name="id" value="<?php
                                    if(isset($employee))
                                      {
                                        echo $employee[0]['id'];
                                      }
                                    ?>">

                                    
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <b> Code</b>
                                            <div class="input-group">
                                            <span><?php if(isset($employee)){ echo $employee[0]['code'];   }else{  $pre_role_code=$this->EmployeeModel->getdata('emp_code'); echo $pre_role_code[0]['name'].''.$empCount; }?></span>
                                            </div>
                                        </div>
                                <?php if(empty($employee)){ ?>
                                    <div class="col-md-3">
                                            <b> Login ID</b>
                                            <div class="input-group">
                                           
                                            <div class="form-line">
                                                <input type="text" tabindex="1" autofocus="on" onblur="checkUserName(this);" id="logId" name="logId" class="form-control" required value="<?php if(isset($employee)){ echo $employee[0]['email']; }else{ echo $userEmail; } ?>">
                                            </div>
                                            <span style="color:red" id="usr"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <b>First Name</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" tabindex="2" onblur="createName();" id="FirstName" name="FirstName" class="form-control date" placeholder="employee first name" required value="<?php if(isset($employee))
                                                    {
                                                       $str=$employee[0]['name']; 
                                                        $exploded=explode(" ",$str);
                                                        echo $firstname = $exploded[0];
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div>
                                     
                                     <div class="col-md-3">
                                        <b> Last Name</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" tabindex="3" onblur="createName();" id="lastName" name="lastName" class="form-control date" placeholder="employee last name" required value="<?php if(isset($employee))
                                                    {
                                                        $str=$employee[0]['name']; 
                                                        $exploded=explode(" ",$str);
                                                        echo $lastname = $exploded[1];
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div>

                                <?php } ?>

                                   <!--  <div class="col-md-2">
                                        
                                        <input name="salaried"  <?php if(empty($employee)){ echo "checked"; }?> onclick="ShowHideDiv(this)" value="salaried" type="checkbox" <?php if(isset($employee)){
                                                        if($employee[0]['isSalaryEmp']==1)
                                                      echo 'checked'; 
                                                    } ?> id="radio_1"/>
                                        <label for="radio_1"> Salaried </label><br>
                                        <input onclick="ShowHidePassDiv(this)" <?php if(empty($employee)){ echo "checked"; }?> name="login" <?php if(isset($employee))
                                                    {
                                                        if($employee[0]['isLoginEmp']==1)
                                                      echo 'checked'; 
                                                    } ?>
                                                     value="login" type="checkbox" id="radio_2" />
                                        <label for="radio_2">Login Required  </label>
                                    </div> -->
                                    
                                </div>
                                 <div class="col-md-12">
                                <?php if(empty($employee)){ ?>
                                    <div class="col-md-3">
                                        <b>Mobile</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">settings_cell</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" tabindex="4"  required onblur="mobileCheck(this);" name="mobile" class="form-control time12" placeholder="employee Mobile number" onkeypress="return numbersonly(this, event);" value="<?php if(isset($employee))
                                                    {
                                                      echo $employee[0]['mobile']; 
                                                    }
                                                    ?>">
                                            </div>
                                              <span style="color:red" id="mbl"></span>
                                        </div>
                                    </div> 
                                <?php } ?>

                                    <div class="col-md-3">
                                         <b>Id Proof Name </b>
                                    
                                        <select id="idProofName" tabindex="5" name="idProofName" required class="form-control">
                                                <?php if(isset($employee)){ ?>
                                                    <option value="<?php echo $employee[0]['idProofName']; ?>"><?php echo $employee[0]['idProofName']; ?></option>
                                                <?php } ?> 
                                                <option value="">Select ID Proof</option>  
                                                <?php foreach($proof as $itm){ ?>
                                                    <option value="<?php echo $itm['name']; ?>"><?php echo $itm['name']; ?></option>
                                                <?php } ?>
                                                </select>
                                  </div> 
                                   
                                    <div class="col-md-3">
                                  
                                       <b>Address Proof Name </b>
                                   
                                    <select id="addrProofName" tabindex="6" name="addrProofName" required class="form-control">
                                                <?php if(isset($employee)){ ?>
                                                    <option value="<?php echo $employee[0]['addrProofName']; ?>"><?php echo $employee[0]['addrProofName']; ?></option>
                                                <?php } ?> 
                                                <option value="">Select Address Proof</option>  
                                                <?php foreach($proof as $itm){ ?>
                                                    <option value="<?php echo $itm['name']; ?>"><?php echo $itm['name']; ?></option>
                                                <?php } ?>
                                                </select>
                                  </div> 


                                    <div class="col-md-3">
                                   
                                       <b>Company Name </b>
                                    
                                    <select class="form-control" tabindex="7" required id="comp" name="companyId">
                                                <?php if(isset($employee)){ ?>
                                                    <option value="<?php echo $employee[0]['compId']; ?>"><?php echo $employee[0]['companyName']; ?></option>
                                                <?php } ?>
                                                    <option value="">Select Company</option>
                                                    <?php foreach ($company as $req_item){ ?>
                                                    <option value="<?php echo $req_item['id'] ?>"><?php echo $req_item['name'] ?></option>
                                                    <?php } ?> 
                                                </select>
                                  </div> 
                                    
                                </div>
                                 
                                <div class="col-md-12"> 
                                   <?php 
                                        $des=($this->session->userdata[$projectSessionName]['designation']);
                                        $des=explode(',',$des);
                                        if ((in_array('owner', $des))) { ?>
                                            <div class="col-md-3"  id="dvPassport">
                                                <b>Salary(per month)</b>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="material-icons">money</i>
                                                    </span>
                                                    <div class="form-line">
                                                        <input type="text" tabindex="8" onkeypress="return numbersonly(this, event);" name="salary" class="form-control time12" placeholder="Salary(per month)" value="<?php if(isset($employee))
                                                            {
                                                              echo $employee[0]['salary']; 
                                                            }
                                                            ?>">
                                                    </div>
                                                </div>
                                            </div> 
                                    <?php }else{  ?> 
                                        <div class="col-md-3" id="dvPassport" style="display: none">
                                                <b>Salary(per month)</b>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="material-icons">money</i>
                                                    </span>
                                                    <div class="form-line">
                                                        <input type="text" tabindex="8" onkeypress="return numbersonly(this, event);" name="salary" class="form-control time12" placeholder="Salary(per month)" value="<?php if(isset($employee))
                                                            {
                                                              echo $employee[0]['salary']; 
                                                            }else{ echo 0; }
                                                            ?>">
                                                    </div>
                                                </div>
                                            </div> 
                                    <?php } ?>

                                    <div class="col-md-6">
                                        <b>Other Perks/ Remarks</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">check</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" tabindex="9" name="remark" class="form-control time12" placeholder="Other Perks/ Remarks" value="<?php if(isset($employee))
                                                    {
                                                      echo $employee[0]['remark']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div> 

                                    <?php $des=($this->session->userdata[$projectSessionName]['designation']);
                                        $des=explode(',',$des);
                                        ?>
                                      

                                    <div class="col-md-3" id="divRole">
                                       <b>Role</b>
                                    
                                        <select class="form-control" tabindex="10" name="designation[]" multiple="true">
                                            
                                            <option value="">-----Select Role -----</option>
                                            <?php foreach ($role as $req_item){  

                                                
                                            ?>
                                            <option value="<?php echo $req_item['name']; ?>"><?php echo $req_item['name']; ?></option>
                                            <?php  }
                                            ?> 
                                        </select>
                                    </div>
                                    

                                    <?php $des=($this->session->userdata[$projectSessionName]['designation']);
                                        $des=explode(',',$des);
                                        if ((in_array('senior_manager', $des)) && (empty($employee))) { ?>
                                      

                                    <div class="col-md-3">
                                       <b>Role</b>
                                    
                                        <select class="form-control" tabindex="10" name="designation[]" multiple="true">
                                            
                                            <option value="">-----Select Role -----</option>
                                            <?php foreach ($role as $req_item){  

                                                $designation = ($this->session->userdata[$projectSessionName]['designation']);
                                                $desArr=explode(',',$designation);
                                                if ((in_array('owner', $desArr))) {
                                                    if($req_item['name']!="admin" && $req_item['name']!="owner"){
                                            ?>
                                            <option value="<?php echo $req_item['name']; ?>"><?php echo $req_item['name']; ?></option>
                                            <?php  }
                                                }else if ((in_array('senior_manager', $desArr))) {
                                                    if($req_item['name']!="admin" && $req_item['name']!="owner" && $req_item['name']!="senior_manager"){
                                                 ?>
                                                    <option value="<?php echo $req_item['name']; ?>"><?php echo $req_item['name']; ?></option>
                                                <?php 
                                                    }

                                                }

                                            } ?> 
                                        </select>
                                    </div>
                                    <?php } ?> 
                                    

                                    </div>

                                   <div class="col-md-12">
                                      
                                </div>
                                    <div class="col-md-12">
                                            <div class="col-md-4">
                                                <?php if(isset($employee)){ ?>
                                                     <a id="enabledBtn" class="btn btn-primary m-t-15 waves-effect"> 
                                                            <i class="material-icons">edit</i> 
                                                            <span class="icon-name"> Edit </span>
                                                        </a>
                                                  <?php } ?>
                                                <button type="submit" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name"> Save </span>
                                                </button>
                                                
                                              
                                          
                                               <a href="<?php echo site_url('admin/EmployeeController/');?>">
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
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>


<?php $this->load->view('/layouts/footerDataTable'); ?>

<script type="text/javascript">
    <?php if(isset($employee)){ ?>
        $(document).ready(function(){
            $("#frm *").prop("disabled", true);
            $("#enabledBtn").prop("disabled", false);
        });
    <?php } ?>
</script> 

<script type="text/javascript">
    $(document).on('click','#enabledBtn',function(){
        // $(this).prop('disabled', true);
        $(this).hide();
        $("#frm *").prop("disabled", false);
    });
</script>

<script type="text/javascript">
    <?php if(isset($employee)){ ?>
        $(document).ready(function(){
            $("#frmuser *").prop("disabled", true);
            $("#enabledUsrBtn").prop("disabled", false);
        });
    <?php } ?>
</script> 

<script type="text/javascript">
    $(document).on('click','#enabledUsrBtn',function(){
        // $(this).prop('disabled', true);
        $(this).hide();
        $("#frmuser *").prop("disabled", false);
    });
</script>

<script type="text/javascript">
    <?php if(isset($employee)){ ?>
        $(document).ready(function(){
            $("#frmmobile *").prop("disabled", true);
            $("#enabledUsrMobile").prop("disabled", false);
        });
    <?php } ?>
</script> 

<script type="text/javascript">
    $(document).on('click','#enabledUsrMobile',function(){
        // $(this).prop('disabled', true);
        $(this).hide();
        $("#frmmobile *").prop("disabled", false);
    });
</script>

<script>
    function FillBilling(f) {
  if(f.billingtoo.checked == true) {
    f.permanantAddress.value = f.localAddress.value;
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
    function checkEmail(email)
    {
        var email =email.value;
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    }

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

<script language="Javascript" type="text/javascript">

    function onEmpSubmit(){
        var usr=document.getElementById('usr').innerText;
        var mbl=document.getElementById('mbl').innerText;
        if(mbl.trim()=="" && mbl.trim()==""){
            return true;
        }else{
            return false;
        }
    }  

    function onEmpUserNameSubmit(){
        var usr=document.getElementById('usrUpdate').innerText;
        if(usr.trim()==""){
            return true;
        }else{
            return false;
        }
    } 

    function onEmpMobileSubmit(){
        var usr=document.getElementById('updmbl').innerText;
        if(usr.trim()==""){
            return true;
        }else{
            return false;
        }
    }   

    function onlyAlphabets(e, t) {
        try {
            if (window.event) {
                var charCode = window.event.keyCode;
            }
            else if (e) {
                var charCode = e.which;
            }
            else { return true; }
            if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || (charCode ==32))
                return true;
            else
                return false;
        }
        catch (err) {
            alert(err.Description);
        }
    }
</script>

 <script type="text/javascript">
    function ShowHideDiv(chkPassport) {
        var dvPassport = document.getElementById("dvPassport");
        dvPassport.style.display = chkPassport.checked ? "block" : "none";
    }

     function ShowHidePassDiv(chkPassport) {
        var dvPassport = document.getElementById("divRole");
        dvPassport.style.display = chkPassport.checked ? "block" : "none";
    }
</script>


<script type="text/javascript">
    function readEmp(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#emp-img-upload')
                    .attr('src', e.target.result)
                    .width(150)
                    .height(200);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function readId(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#id-img-upload')
                    .attr('src', e.target.result)
                    .width(150)
                    .height(200);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function readAddress(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#address-img-upload')
                    .attr('src', e.target.result)
                    .width(150)
                    .height(200);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<script type="text/javascript">

    function checkUserName(userName){
        var userName =userName.value;
        $.ajax(
        {
            type:"post",
            url: "<?php echo base_url(); ?>index.php/admin/EmployeeController/checkValuesByUserName",
            data:{logId:userName},
            success:function(response)
            {
                // if(response.trim() !="" ){
                //     alert(response);
                // }
                $('#usr').html('<span style="color: red;">'+response+'</span>');
            }
        });
    }

     function checkUpdatedUserName(userName){
        var userName =userName.value;
        $.ajax(
        {
            type:"post",
            url: "<?php echo base_url(); ?>index.php/admin/EmployeeController/checkValuesByUserName",
            data:{logId:userName},
            success:function(response)
            {
                // if(response.trim() !="" ){
                //     alert(response);
                // }
                $('#usrUpdate').html(response);
            }
        });
    }

    function mobileCheck(pass){
        var mobile =pass.value;
        var IndNum = /^[0]?[789]\d{9}$/;
        if(mobile.length <10 || mobile.length >10){
            document.getElementById('mbl').innerText='Enter 10 digit mobile number';
        }else{
             if(IndNum.test(mobile)){
                $.ajax(
                {
                    type:"post",
                    url: "<?php echo base_url(); ?>index.php/admin/EmployeeController/checkValuesByMobile",
                    data:{mobile:mobile},
                    success:function(response)
                    {
                        $('#mbl').html('<span style="color: red;">'+response+'</span>');
                    }
                });
            } else{
                document.getElementById('mbl').innerText='please enter valid mobile number';
            }
        }
    }

    function updateMobileCheck(pass){
        var mobile =pass.value;
        var IndNum = /^[0]?[789]\d{9}$/;
        if(mobile.length <10 || mobile.length >10){
            document.getElementById('updmbl').innerText='Enter 10 digit mobile number';
        }else{
             if(IndNum.test(mobile)){
                $.ajax(
                {
                    type:"post",
                    url: "<?php echo base_url(); ?>index.php/admin/EmployeeController/checkValuesByMobile",
                    data:{mobile:mobile},
                    success:function(response)
                    {
                        $('#updmbl').html(response);
                    }
                });
            } else{
                document.getElementById('updmbl').innerText='please enter valid mobile number';
            }
        }
    }
    
    function createName(){
        var fname=document.getElementById('FirstName').value;
        fname=fname.trim().replace(/\s/g, ".");
        var lname=document.getElementById('lastName').value;
        lname=lname.trim().replace(/\s/g, ".");

        var finalName=fname+'.'+lname;
        document.getElementById('logId').value=finalName;
    }
</script>


<script type="text/javascript">
    $(document).on('click','#updateMobileNo',function(){
        var mobile=$('#updatemobile').val();
        var empId=$('#updateEmpId').val();
        var msg=$('#updmbl').html().trim();

        if(msg==''){
            $('#updmbl').html('');
            return true;
        }else if(msg!=''){
            alert('Please enter correct mobile.');die();
        }

        $.ajax({
            type: "POST",
            url:"<?php echo base_url(); ?>index.php/admin/EmployeeController/updateEmployeeMobile",
            data:{empId:empId,mobile:mobile},
            success: function (data) {
                alert(data);
                if(data.trim()=="Record updated"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/admin/EmployeeController";
                }else{
                    alert(data);
                }

            }  
        });
        
        
    });
    
</script>

<script type="text/javascript">
    $(document).on('click','#updateUserName',function(){
    // function updateUserNameById(){
        var username=$('#updatelogId').val();
        var empId=$('#updateEmpId').val();
        var msg=$('#usrUpdate').html();

        if(msg==''){
            $('#usrUpdate').html('');
            return true;
        }else if(msg!=''){
            alert('Please enter correct mobile.');die();
        }

        $.ajax({
            type: "POST",
            url:"<?php echo base_url(); ?>index.php/admin/EmployeeController/updateEmployeeUsername",
            data:{empId:empId,username:username},
            success: function (data) {
                alert(data);
                if(data.trim()=="Record updated"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/admin/EmployeeController";
                }else{
                    alert(data);
                }

            }  
        });
    // }
        
        
    });
    
</script>

<script type="text/javascript">
    $("#isLoginDiv").click(function() {
        if($(this).is(":checked")) {
            $("#frm").show();
            $("#nonLoginFrm").hide();
            
        } else {
            $("#frm").hide();
            $("#nonLoginFrm").show();
        }
    });
</script>