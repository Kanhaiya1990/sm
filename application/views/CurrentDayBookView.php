<?php $this->load->view('/layouts/commanHeader'); ?>

   <!--  <section class="content"> -->
    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Current Day Book</h2>
            </div>
            <!-- Masked Input -->
            <div class="row clearfix">
                <div class="col-xs-6">
                    <div class="card">
                        <div class="header">
                            <h2> Add Income</h2>
                        </div>
                        <form method="post" role="form" action="<?php echo site_url('CashBookController/insertIncome');?>"> 
                        <div class="body">
                            <div class="demo-masked-input">
                                <div class="row clearfix">
                                  <div class="col-md-4">
                                    <p>
                                      <b>Nature</b>
                                    </p>
                                     <select name="nature" class="form-control" id="test" onClick="showDiv()">
                                          <option>---Select Nature---</option>
                                          <option value="cd_sale">Cd Sale</option>
                                          <option value="advance_Sale">Advance Sale</option>
                                          <option value="other">Other</option>
                                        </select>
                                  </div>     
                                  <div class="col-md-4" style="display: none;" id="billNo">
                                    <p>
                                     <b>Bill No </b>
                                    </p>
                                    <select name="billNo" class="form-control">
                                        <option>--Select Bills No---</option>
                                        <?php foreach ($bills as $req_item): ?>
                                          <option value="<?php echo $req_item['id'] ?>"><?php echo $req_item['billNo'] ?></option>
                                        <?php endforeach ?> 
                                    </select>
                                  </div> 
                                  <div class="col-md-4" style="display: none;" id="empName">
                                    <p>
                                       <b>Employee Name </b>
                                    </p>
                                    <select  name="userId" class="form-control">
                                      <option>--Select Employee---</option>
                                      <?php foreach ($employee as $req_item): ?>
                                        <option value="<?php echo $req_item['id'] ?>"><?php echo $req_item['name'] ?></option>
                                      <?php endforeach ?> 
                                    </select>
                                  </div>      
                                  <div class="col-md-4" style="display: none;" id="text">
                                    <b>Text</b>
                                      <div class="input-group">
                                      <span class="input-group-addon">
                                         <i class="material-icons">format_color_text</i>
                                      </span>
                                      <div class="form-line">
                                        <input type="text" name="text" class="form-control date" placeholder="Enter the text"required>
                                      </div>
                                    </div>
                                  </div> 
                                  <div class="col-md-4">
                                    <b>Amount</b>
                                      <div class="input-group">
                                      <span class="input-group-addon">
                                         <i class="material-icons">money</i>
                                      </span>
                                      <div class="form-line">
                                        <input type="text" name="amt" class="form-control date" placeholder="Enter the Amount"required>
                                      </div>
                                    </div>
                                  </div> 
                                  <div class="col-md-12">
                                    <div class="col-md-8"></div>
                                    <div class="col-md-4">
                                      <div class="row clearfix">
                                        <p style="text-align: right;">
                                          <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                            <button type="submite" class="btn btn-primary m-t-15 waves-effect">
                                              <i class="material-icons">check_box</i> 
                                              <span class="icon-name">
                                               Ok
                                             </button>
                                          </div>
                                         </p>
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
            <div class="col-xs-6">
                    <div class="card">
                        <div class="header">
                            <h2> Add Expence</h2>
                        </div>
                        <form method="post" role="form" action="<?php echo site_url('CashBookController/insertExpence');?>"> 
                        <div class="body">
                            <div class="demo-masked-input">
                                <div class="row clearfix">
                                  <div class="col-md-4">
                                    <p>
                                      <b>Nature</b>
                                    </p>
                                     <select  name="nature" class="form-control" id="test1" onClick="showDiv1()">
                                          <option>---Select Nature---</option>
                                          <option value="advance">Advance</option>
                                          <option value="expence">Expence</option>
                                        </select>
                                  </div>     
                                  <div class="col-md-4" style="display: none;" id="ename">
                                    <p>
                                       <b>Employee Name </b>
                                    </p>
                                    <select  name="userId" class="form-control">
                                      <option>--Select Employee---</option>
                                      <?php foreach ($employee as $req_item): ?>
                                        <option value="<?php echo $req_item['id'] ?>"><?php echo $req_item['name'] ?></option>
                                      <?php endforeach ?> 
                                    </select>
                                  </div> 
                                  <div class="col-md-4" style="display: none;" id="category">
                                  <p>
                                    <b>Category</b>
                                  </p>
                                  <select  name="category" class="form-control">
                                    <option>---Select Category---</option>
                                    <option value=""> Car Maintenance</option>
                                    <option value=""> Challan</option>
                                    <option value=""> Converyance</option>
                                    <option value=""> Courier Charges</option>
                                    <option value=""> Fright Outwards</option>
                                    <option value=""> Fuel Expenses</option>
                                    <option value=""> General Expenses</option>
                                    <option value=""> Internet Expenses</option>
                                    <option value=""> Materail Handling Inwards</option>
                                    <option value=""> Materail Handling Outwards</option>
                                    <option value=""> Miscellaneous</option>
                                    <option value=""> Mobile Phone Expenses</option>
                                    <option value=""> Other Expenses</option>
                                    <option value=""> Staff Welfare Expenses</option>
                                    <option value=""> Stationary Expenses</option>
                                    <option value=""> Telephone Charges</option>
                                    <option value=""> Vehicle Repairs</option>
                                    <option value=""> Water Expenses</option>
                                    <option value=""> Market Adjustment</option>
                                  </select>
                                </div>
                                <div class="col-md-4">
                                  <b>Amount</b>
                                    <div class="input-group">
                                    <span class="input-group-addon">
                                       <i class="material-icons">money</i>
                                    </span>
                                    <div class="form-line">
                                      <input type="text" name="amt" class="form-control date" placeholder="Enter the Amount"required>
                                    </div>
                                  </div>
                                </div> 
       
                                <div class="col-md-10">
                                   <p style="display: none">Category</p>
                                  <input type="checkbox" id="remember_me" class="filled-in">
                                  <label for="remember_me">Create Vauchers</label>
                                </div>          
                                 
                                  <div class="col-md-12">
                                    <div class="col-md-8"></div>
                                    <div class="col-md-4">
                                      <div class="row clearfix">
                                        <p style="text-align: right;">
                                          <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                            <button type="submite" class="btn btn-primary m-t-15 waves-effect">
                                              <i class="material-icons">check_box</i> 
                                              <span class="icon-name">
                                               Ok
                                             </button>
                                          </div>
                                         </p>
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
        </div>
    </section>
    <?php $this->load->view('/layouts/footerDataTable'); ?>
    <script type="text/javascript">
      function showDiv(){
        getSelectValue = document.getElementById("test").value;
        if(getSelectValue == "cd_sale"){
          document.getElementById("billNo").style.display="block";
        }else{
          document.getElementById("billNo").style.display="none";
         
        }
        if(getSelectValue == "advance_Sale"){
          document.getElementById("empName").style.display="block";
        }else{
          document.getElementById("empName").style.display="none";
        }
        if(getSelectValue == "other"){
          document.getElementById("text").style.display="block";
        }else{
          document.getElementById("text").style.display="none";
        }
      }
    </script> 
     <script type="text/javascript">
      function showDiv1(){
        getSelectValue = document.getElementById("test1").value;
        if(getSelectValue == "advance"){
          document.getElementById("ename").style.display="block";
        }else{
          document.getElementById("ename").style.display="none";
         
        }
        if(getSelectValue == "expence"){
          document.getElementById("category").style.display="block";
        }else{
          document.getElementById("category").style.display="none";
        }
      }
    </script> 
