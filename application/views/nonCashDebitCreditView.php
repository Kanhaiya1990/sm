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
                             Non Cash Debit/Credit
                            </h2>
                       
                        </div>
                        <!-- <form method="post" onsubmit="return checkSerial();" role="form" enctype="multipart/form-data" action="<?php echo site_url('AllocationByManagerController/insertAdhocBill'); ?>">  -->
                            <div class="body">
                                <p id="res"></p>
                                <div class="row clearfix">
                                <div class="demo-masked-input">
                                    <div class="col-md-12"> 
                                        <div class="col-md-3">
                                            <b>Employee Name</b>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">account_box</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="text" list="elist" autocomplete="off" id="empDetail" name="empDetail" class="form-control date" placeholder="Enter employee name">

                                                    <datalist id="elist">
                                                    <?php 
                                                        if(isset($employee)){
                                                            foreach($employee as $dt){?>
                                                                <option id="<?php echo $dt['id'];?>" value="<?php echo $dt['name'];?>" />
                                                    <?php 
                                                            } 
                                                        }
                                                    ?>
                                                    </datalist>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <b>Amount</b>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">account_box</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="text" autocomplete="off" onkeypress="return numbersonly(this, event);" id="amount" name="amount" class="form-control date" placeholder="Enter amount" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-5">
                                            <b>Remark</b>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">account_box</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="text" autocomplete="off" id="remark" name="remark" class="form-control" placeholder="Enter remark" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <input name="cashType" value="cr" type="radio" id="radio_1" />
                                            <label for="radio_1">Credit </label>
                                            <input name="cashType" value="dr" type="radio" id="radio_2" />
                                            <label for="radio_2">Debit </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-4">
                                            <button id="insertRecord" class="btn btn-primary m-t-15 waves-effect">
                                                <i class="material-icons">save</i> 
                                                <span class="icon-name">
                                                 Save
                                                </span>
                                            </button>
                                           <a href="<?php echo site_url('DashbordController');?>">
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
                        <!-- </form> -->
                    </div>
                </div>
            </div>
            <!-- #END# Masked Input -->
        </div>
    </section>

<?php $this->load->view('/layouts/footerDataTable'); ?>

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
    $(document).on('click','#insertRecord',function(){

        $("#insertRecord").attr("disabled", true);

        var empName=$('#empDetail').val();
        var empId = $('#elist').find('option[value="'+empName+'"]').attr('id');
        var amount=$('#amount').val();
        var remark=$('#remark').val();
        var type=$('input[name="cashType"]:checked').val();
        // alert(type);die();

        // alert(empName+' '+empId+' '+amount+' '+remark+' '+type);die();
        
        if(empName==''){
            alert('Please select employee.');
            $("#insertRecord").attr("disabled", false);
            die();
        }else if(empName!=''){
            if (typeof empId === "undefined") {
                alert('Please select correct employee.');
                $("#insertRecord").attr("disabled", false);
                die();
            }
        }

        if(amount=='' || amount==0){
            alert('Please enter amount.');
            $("#insertRecord").attr("disabled", false);
            die();
        }

        if(remark==''){
            alert('Please enter remark.');
            $("#insertRecord").attr("disabled", false);
            die();
        }

        if(typeof type === "undefined"){
            alert('Please select checkbox.');
            $("#insertRecord").attr("disabled", false);
            die();
        }

        $.ajax({
            type: "POST",
            url:"<?php echo site_url('NonAllocationBillsController/insertNonCashEntry');?>",
            data:{empName:empName,empId:empId,amount:amount,remark:remark,type:type},
            success: function (data) {
                if(data.trim()=="Record Inserted"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/NonAllocationBillsController/nonCashDebitCredit";
                }else{
                    alert(data);
                    $("#insertRecord").attr("disabled", false);
                }

            }  
        });
        
        
    });
    
</script>
