<?php $this->load->view('/layouts/commanHeader'); ?>

<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/>
<section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            
            <!-- Masked Input -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               <?php 
                              if(isset($company))
                                {
                                  echo 'Update';
                                }
                              else
                                {
                                  echo 'Add';
                                }
                              ?>  Distributor Master
                            </h2>
                        </div>
                        <form method="post" role="form" action="<?php
                            if(isset($company))
                            {
                              echo site_url('owner/DistributorController/update');
                            }
                            else
                            {
                              echo site_url('owner/DistributorController/insert');
                            }
                        ?>"> 
                        <div class="body">
                            <div class="demo-masked-input">
                                <div class="row clearfix">
                                     <input type="hidden" name="id" value="<?php
                                    if(isset($company))
                                      {
                                        echo $company[0]['id'];
                                      }
                                    ?>">
                                  <div class="col-sm-4">
                                        <b>Distribitor Name</b>
                                        <div class="input-group">
                                            <div class="form-line">
                                                <input type="text" required name="name" autocomplete="off" class="form-control date" placeholder="Enter Distributor name" value="<?php if(isset($company))
                                                    {
                                                      echo $company[0]['name']; 
                                                    }
                                                    ?>">
                                                    
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="col-sm-4">
                                        <b>Mobile</b>
                                        <div class="input-group">
                                            <div class="form-line">
                                                <input type="text" required name="mobile" onkeypress="return numbersonly(event)" onblur="mobileCheck(this);" autocomplete="off" class="form-control date" placeholder="Enter Mobile name" value="<?php if(isset($company))
                                                    {
                                                      echo $company[0]['mobile']; 
                                                    }
                                                    ?>">
                                                    
                                            </div>
                                            <span style="color:red" id="mblSal"></span>
                                        </div>
                                    </div>  

                                    <div class="col-sm-4">
                                        <b>Telephone</b>
                                        <div class="input-group">
                                            <div class="form-line">
                                                <input type="text" name="telephone" onkeypress="return numbersonly(event)" autocomplete="off" class="form-control date" placeholder="Enter Telephone name" value="<?php if(isset($company))
                                                    {
                                                      echo $company[0]['telephone']; 
                                                    }
                                                    ?>">
                                                    
                                            </div>
                                        </div>
                                    </div>  

                                    <div class="col-sm-4">
                                        <b>Email</b>
                                        <div class="input-group">
                                            <div class="form-line">
                                                <input type="email" required name="email" style="text-transform:lowercase"  autocomplete="off" class="form-control date" placeholder="Enter Email" value="<?php if(isset($company))
                                                    {
                                                      echo $company[0]['email']; 
                                                    }
                                                    ?>">
                                                    
                                            </div>
                                        </div>
                                    </div>  

                                    <div class="col-sm-4">
                                        <b>Base URL for Distributor</b>
                                        <div class="input-group">
                                            <div class="form-line">
                                                <input type="text" required name="baseUrl" autocomplete="off" class="form-control date" placeholder="Enter URL name" value="<?php if(isset($company))
                                                    {
                                                      echo $company[0]['baseUrl']; 
                                                    }
                                                    ?>">
                                                    
                                            </div>
                                        </div>
                                    </div>  

                                    <div class="col-sm-4">
                                        <b>Database Name for Distributor</b>
                                        <div class="input-group">
                                            <div class="form-line">
                                                <input type="text" required name="databaseName" autocomplete="off" class="form-control date" placeholder="Enter Database name" value="<?php if(isset($company))
                                                    {
                                                      echo $company[0]['databaseName']; 
                                                    }
                                                    ?>">
                                                    
                                            </div>
                                        </div>
                                    </div>  

                                    <div class="col-sm-8">
                                        <b>Address</b>
                                        <div class="input-group">
                                            <div class="form-line">
                                                <input type="text" required name="address" autocomplete="off" class="form-control date" placeholder="Enter Address" value="<?php if(isset($company))
                                                    {
                                                      echo $company[0]['address']; 
                                                    }
                                                    ?>">
                                                    
                                            </div>
                                        </div>
                                    </div>  

                                    <div class="col-sm-4">
                                        <b>City / District</b>
                                        <div class="input-group">
                                            <div class="form-line">
                                                <input type="text" required name="city" autocomplete="off" class="form-control date" placeholder="Enter City / District name" value="<?php if(isset($company))
                                                    {
                                                      echo $company[0]['city']; 
                                                    }
                                                    ?>">
                                                    
                                            </div>
                                        </div>
                                    </div>  

                                    <div class="col-sm-4">
                                        <b>State / Province</b>
                                        <div class="input-group">
                                            <div class="form-line">
                                                <input type="text" required name="state" autocomplete="off" class="form-control date" placeholder="Enter State / Province name" value="<?php if(isset($company))
                                                    {
                                                      echo $company[0]['state']; 
                                                    }
                                                    ?>">
                                                    
                                            </div>
                                        </div>
                                    </div>  


                                    <div class="col-sm-4">
                                        <b>Zip / Postal Code</b>
                                        <div class="input-group">
                                            <div class="form-line">
                                                <input type="text" required name="pincode" onkeypress="return numbersonly(event)" autocomplete="off" class="form-control date" placeholder="Enter Zip / Postal code" value="<?php if(isset($company))
                                                    {
                                                      echo $company[0]['pincode']; 
                                                    }
                                                    ?>">
                                                    
                                            </div>
                                        </div>
                                    </div>  

                                    <div class="col-sm-4">
                                        <b>Country</b>
                                        <div class="input-group">
                                            <div class="form-line">
                                                <input type="text" required name="country" autocomplete="off" class="form-control date" placeholder="Enter Country name" value="India">
                                                    
                                            </div>
                                        </div>
                                    </div>  
                                  
                                     <div class="col-sm-12">
                                        <div class="row clearfix">
                                            <div class="col-sm-4">
                                                <button type="submit" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name">
                                                     <?php
                                                        if(isset($company))
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
                                                <a href="<?php echo site_url('owner/DistributorController');?>">
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
            <!-- #END# Masked Input -->
        </div>
    </section>
    
<?php $this->load->view('/layouts/footer'); ?>

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
    function mobileCheck(pass){
        var mobile =pass.value;
        var IndNum = /^[0]?[789]\d{9}$/;
        if(mobile.length <10 || mobile.length >10){
            document.getElementById('mbl').innerText='Enter 10 digit mobile number';
        }else{
             if(IndNum.test(mobile)){
                document.getElementById('mbl').innerText='';
            } else{
                document.getElementById('mbl').innerText='please enter valid mobile number';
            }
        }
    }
</script>