<?php $this->load->view('/layouts/commanHeader'); ?>

<h1 style="display: none;">Welcome</h1><br/><br/><br/>
<section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
    <div class="container-fluid">
        <!-- <div class="block-header">
            <h2>
             Add Tagged Bill
         </h2>

     </div> -->
     <!-- Basic Examples -->
     <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                       Add Tagged Bill
                   </h2>
               </div>


               <div class="body">
                <div class="table-responsive">

                    <form method="post" role="form" action="<?php echo site_url('NonAllocationBillsController/update');?>"> 
                        <div class="body">
                            <div class="demo-masked-input">
                                <div class="row clearfix">
                                    <div class="col-md-4">
                                        <p>
                                          <b>Bill No </b>
                                      </p>
                                      <div class="form-line">

                                        <input type="text" id="billNo" onInput="loadCustData();" autocomplete="off" list="bill" name="billNo" class="form-control" placeholder="Enter Bill No" required>   
                                        <datalist id="bill">
                                            <?php
                                            foreach($bills as $data){
                                                $billNo=$data['id'].':'.$data['billNo'];
                                                ?>   
                                                <option value="<?php echo $billNo;?>"/>
                                                    <?php    
                                                }
                                                ?>
                                            </datalist>
                                        </div>
                                    </div>   
                                    <div class="col-md-4">
                                        <p>
                                         <b>Person Name </b>
                                     </p>
                                     <div class="form-line">
                                        <input type="text" id="name" autocomplete="off" list="employee" name="employee" class="form-control" placeholder="Enter Bill No" required>   
                                        <datalist id="employee">
                                            <?php
                                            foreach($employee as $data){
                                                $name=$data['name'];
                                                ?>   
                                                <option value="<?php echo $name;?>"/>
                                                    <?php    
                                                }
                                                ?>
                                            </datalist>
                                        </div>
                                    </div>     

                                    <div class="col-md-4">
                                        <b>Amount</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                               <i class="material-icons">money</i>
                                           </span>
                                           <div class="form-line">
                                            <input type="text" id="amt" name="amt" class="form-control date" placeholder="Enter the Amount" required>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="row clearfix">
                            <div class="col-md-4">
                               
                                 <b>Category </b>
                            
                             <select name="category" class="form-control" id="category" onClick="showDiv1()">
                                <option>--Select Category---</option>
                                <option value="Office Sales">Office Sales</option>
                                <option value="Office Adjustment">Office Adjustment</option>
                            </select>
                        </div>
                        <div class="col-md-4" style="display: none;" id="accounting">
                           
                             <b>Accounting </b>
                         
                         <select name="accounting" id="accounting" class="form-control">
                            <option>--Select Accounting---</option>
                            <option value="cash">Cash</option>
                            <option value="credit">Credit</option>
                        </select>
                    </div> 
                    <div class="col-md-4">
                        <b>Remark</b>
                        <div class="input-group">
                            <span class="input-group-addon">
                               <i class="material-icons">check</i>
                           </span>
                           <div class="form-line">
                            <input type="text" name="remark" class="form-control date" placeholder="Enter the Remark">
                        </div>
                    </div>
                </div>   
            </div>
        </div>
        <div class="col-md-12">
            <center>
                <div class="row clearfix">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary m-t-15 waves-effect">
                            <i class="material-icons">save</i> 
                            <span class="icon-name">
                                Save
                            </span>
                        </button>  
                        <button onclick='parent.$.colorbox.close(); return false;' type="button" class="btn btn-primary m-t-15 waves-effect">
                            <i class="material-icons">cancel</i> 
                            <span class="icon-name">
                                Cancel
                            </span>
                        </button>  
                    </div>
                </div>
            </center>
        </div>  
        <!-- <?php form_close();?> -->
    </div>
  </form>
</div>
</div>
</div>
</div>
<!-- #END# Basic Examples -->  
</div>
</section>
<?php $this->load->view('/layouts/footerDataTable'); ?>
<script type="text/javascript">
    function showDiv1(){
        getSelectValue = document.getElementById("category").value;
        if(getSelectValue == "Office Adjustment"){
          document.getElementById("accounting").style.display="block";
      }else{
          document.getElementById("accounting").style.display="none"; 
      }

  }
</script> 

<script type="text/javascript">
  function loadCustData() {  
     var xhttp = new XMLHttpRequest();
     xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          var data=xhttp.responseText;
          var jsonResponse = JSON.parse(data);
          document.getElementById("amt").value = jsonResponse['bills'][0]['pendingAmt'];
      }
  };
  var billNo =document.getElementById("billNo").value;
  billNo=billNo.split(":");
  // KS%2F18-19%2FPRL-556
  // alert(billNo[0]);
  xhttp.open("GET", "<?php echo site_url('NonAllocationBillsController/loadBills/');?>"+billNo[0], true);
  xhttp.send();
}

</script>