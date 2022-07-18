<?php $this->load->view('/layouts/commanHeader'); ?>

    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Add Employee </h2>
            </div>
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
                              ?> Employee Master
                            </h2>
                       <!--      <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another action</a></li>
                                        <li><a href="javascript:void(0);">Something else here</a></li>
                                    </ul>
                                </li>
                            </ul> -->
                        </div>
                        <form method="post" role="form" enctype="multipart/form-data" 
                        action="<?php
                            if(isset($employee))
                            {
                              echo site_url('manager/EmployeeController/update');
                            }
                            else
                            {
                            echo site_url('manager/EmployeeController/insert');
                            }
                        ?>"> 
                        <div class="body">
                            <div class="demo-masked-input">
                                <div class="row clearfix">
                                      <input type="hidden" name="id" value="<?php
                                    if(isset($employee))
                                      {
                                        echo $employee[0]['id'];
                                      }
                                    ?>">
                                    <div class="col-md-4">
                                        <b>Employee first Name</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" name="FirstName" class="form-control date" placeholder="Enter your employee name" required value="<?php if(isset($employee))
                                                    {
                                                       $str=$employee[0]['name']; 
                                                        $exploded=explode(" ",$str);
                                                        echo $firstname = $exploded[0];
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <b>Employee Last Name</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" name="lastName" class="form-control date" placeholder="Enter your employee name" required value="<?php if(isset($employee))
                                                    {
                                                        $str=$employee[0]['name']; 
                                                        $exploded=explode(" ",$str);
                                                        echo $lastname = $exploded[1];
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <b>Code </b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                               <!--  <input type="text" name="code" class="form-control date" placeholder="Enter your code" value="<?php if(isset($employee))
                                                    {
                                                      echo $employee[0]['code']; 
                                                    }
                                                    ?>"> -->
                                                 <input type="text" name="code"  class="form-control date" value="<?php print rand(111111, 999999)?>">
                                            </div>
                                        </div>
                                    </div>
                                      <div class="col-md-4">
                                        <b>Employee Email</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">email</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="email" name="email" class="form-control time24" placeholder="Enetr your email"  value="<?php if(isset($employee))
                                                    {
                                                      echo $employee[0]['email']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div> 
                                   <!--   <div class="col-md-4">
                                        <b>Password</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">vpn_key</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="password" name="password" class="form-control time12" placeholder="Enter your Password" value="<?php if(isset($employee))
                                                    {
                                                      echo $employee[0]['password']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div>  -->
                                     <div class="col-md-4">
                                         <b>Local Address</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" name="localAddress" class="form-control date" placeholder="Enter your localAddress" required value="<?php if(isset($employee))
                                                    {
                                                      echo $employee[0]['localAddress']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <div class="checkbox">
                                            <label>
                                               <!--  <input type="checkbox" name="addCopy" id="remember_me" class="filled-in"  onclick="FillBilling(this.form)"> -->
                                                <input type="checkbox" name="billingtoo" id="remember_me"  onclick="FillBilling(this.form)">
                                                <label for="remember_me"></label>
                                            </label>                                          
                                        </div>
                                            </span>
                                              <p>Copy the local address to Permanent Address</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <b>Permanent Address</b>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <textarea rows="2" name="permanantAddress" class="form-control no-resize" placeholder="Enetr your Permanent Address." required>
                                                    <?php if(isset($employee))
                                                    {
                                                      echo $employee[0]['permanantAddress']; 
                                                    }
                                                ?>
                                                </textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <b>Mobile</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">settings_cell</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" name="mobile" class="form-control time12" placeholder="Enter your Mobile number"  pattern="[7-9]{1}[0-9]{9}" value="<?php if(isset($employee))
                                                    {
                                                      echo $employee[0]['mobile']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <b>Father Name</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" name="fatherName" class="form-control time12" placeholder="Enter your Father Name" required value="<?php if(isset($employee))
                                                    {
                                                      echo $employee[0]['fatherName']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <b>Id Proof Name</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" name="idProofName" class="form-control time12" placeholder="Id Proof Name" required value="<?php if(isset($employee))
                                                    {
                                                      echo $employee[0]['idProofName']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div>   
                                    <div class="col-md-4">
                                        <b>Id Proof No</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">format_list_numbered</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" name="idProofNo" class="form-control time12" placeholder="Id Proof No" required value="<?php if(isset($employee))
                                                    {
                                                      echo $employee[0]['idProofNo']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div>   
                                    <div class="col-md-4">
                                        <b>Id Proof Image</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">insert_photo</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="file" name="idProofImage" class="form-control time12" placeholder="Id Proof Image" required value="<?php if(isset($employee))
                                                    {
                                                      echo $employee[0]['idProofImage']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div>    
                                      <div class="col-md-4">
                                        <b>Address Proof Name</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">map</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" name="addrProofName" class="form-control time12" placeholder="Enter Address Proof Name"  required value="<?php if(isset($employee))
                                                    {
                                                      echo $employee[0]['addrProofName']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div>     
                                      <div class="col-md-4">
                                        <b>Address Proof No</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">format_list_numbered</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" name="addrProofNo" class="form-control time12" placeholder="Enter Address Proof No" required value="<?php if(isset($employee))
                                                    {
                                                      echo $employee[0]['addrProofNo']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div>    
                                    <div class="col-md-4">
                                        <b>Address Proof Image</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">insert_photo</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="file" name="addrProofImage" class="form-control time12" placeholder="Enter Address Proof Image" required value="<?php if(isset($employee))
                                                    {
                                                      echo $employee[0]['addrProofImage']; 
                                                    }
                                                    ?>"required>
                                            </div>
                                        </div>
                                    </div>    
     
                                    <div class="col-md-4">
                                        <b>Employee Image</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">insert_photo</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="file" name="profileImage" class="form-control time12" placeholder="upload employee Image" required value="<?php if(isset($employee))
                                                    {
                                                      echo $employee[0]['profileImage']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div>   
                                   <!--  <div class="col-md-4">
                                        <b>Date of Joining</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">perm_contact_calendar</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="date" name="joiningDate" class="form-control time12" placeholder="Date of Joining" value="<?php if(isset($employee))
                                                    {
                                                      echo $employee[0]['joiningDate']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div>      -->
                                    <div class="col-md-4">
                                        <b>Salary(per month)</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">money</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" name="salary" class="form-control time12" placeholder="Salary(per month)" required value="<?php if(isset($employee))
                                                    {
                                                      echo $employee[0]['salary']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div>    
                                    <div class="col-md-4">
                                        <b>Other Perks/ Remarks</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">check</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" name="remark" class="form-control time12" placeholder="Other Perks/ Remarks" value="<?php if(isset($employee))
                                                    {
                                                      echo $employee[0]['remark']; 
                                                    }
                                                    ?>">
                                            </div>
                                        </div>
                                    </div>  

                                    <div class="col-md-4">
                                        <b>Designation</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">check</i>
                                            </span>
                                            <div class="form-line">
                    
                                           <?php
                                             if(isset($employee)){                               
                                               ?>
                                                <select class="form-control show-tick" name="designation">
                                                    <option><?php echo $employee[0]['designation'] ?> </option>
                                                    <option value="admin">Admin</option>
                                                    <option value="manager">Manager</option>
                                                    <option value="accountant">Accountant</option>
                                                    <option value="owner">Owner</option>
                                                    <option value="deliveryman">Delivery Man</option>
                                                    <option value="godown_keeper">Godown Keeper</option>
                                                    <option value="cashier">Cashier</option>
                                                    <option value="salesman">Salesman</option>
                                               </select>
                                                <?php

                                                 } else {
                                                ?>
                                                <select class="form-control show-tick" name="designation">
                                                    <option>-----Select Designation -----</option>
                                                    <option value="admin">Admin</option>
                                                    <option value="manager">Manager</option>
                                                    <option value="accountant">Accountant</option>
                                                    <option value="owner">Owner</option>
                                                    <option value="deliveryman">Delivery Man</option>
                                                    <option value="godown_keeper">Godown Keeper</option>
                                                    <option value="cashier">Cashier</option>
                                                    <option value="salesman">Salesman</option>
                                               </select> 
                                                <?php
                                              }
                                                  ?>        
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-5">
                                        <b>Company Name</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                            <div class="form-line">
                                                <select class="form-control show-tick" name="companyId">
                                                    <option>-----Select Company -----</option>
                                                    <?php foreach ($company as $req_item): ?>
                                                    <option value="<?php echo $req_item['id'] ?>"><?php echo $req_item['name'] ?></option>
                                                    <?php endforeach ?> 
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                   <div class="col-md-5">
                                        <b>Status</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">check</i>
                                            </span>
                                            <div class="form-line">
                    
                                           <?php
                                             if(isset($employee)){                               
                                               ?>
                                                <select class="form-control show-tick" name="status">
                                                    <option>
                                                        <?php  
                                                        if($employee[0]['status']==1){
                                                            echo "Active";
                                                        } else{
                                                             echo "Deactive";
                                                        }?>
                                                     </option>
                                                    <option value="1">Active</option>
                                                    <option value="0">Deactive</option>
                                               </select>
                                                <?php

                                                 } else {
                                                ?>
                                                <select class="form-control show-tick" name="status">
                                                    <option value="1">Active</option>
                                                    <option value="0">Deactive</option>
                                               </select>
                                                <?php
                                              }
                                                  ?>        
                                            </div>
                                        </div>
                                    </div>    
                                    <!--  <div class="col-md-8">
                                        <b>Police Verification</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">sentiment_very_satisfied</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" class="form-control time12" placeholder="Police Verification">
                                            </div>
                                        </div>
                                    </div>  -->
                                  <!--  <div class="col-md-8"></div> -->
                                    <div class="col-md-12">
                                        <div class="row clearfix">
                                            <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                                <button type="submite" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name">
                                                     <?php
                                                        if(isset($employee))
                                                         { 
                                                          echo "Update";
                                                          }
                                                        else
                                                          {
                                                            echo "Save";
                                                          }
                                                        ?> 
                                                    </span>
                                                </button>
                                               <a href="<?php echo site_url('manager/EmployeeController/');?>">
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
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
            <!-- #END# Masked Input -->
        </div>
    </section>
    <?php $this->load->view('/layouts/footerDataTable'); ?>
<script>
    function FillBilling(f) {
  if(f.billingtoo.checked == true) {
    f.permanantAddress.value = f.localAddress.value;
  }
}
</script>

