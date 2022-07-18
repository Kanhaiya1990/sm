<?php $this->load->view('/layouts/commanHeader'); ?>

 <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
<section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
           
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Expense Limit Details</h2>
                        </div>
                        <div class="body">
                        <!-- <input type="hidden" id="companyCount" class="form-control" value="<?php echo $companyCount; ?>" style="width:70px;height:25px"> -->
                                            
                            <div class="table-responsive">
                              <p id="res"></p>
                                <table style="font-size: 14px" class="table" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Expense Setting For </th>
                                            <th>Amount</th>
                                             <th></th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <tr id="sr_rep">
                                            <td><span><?php echo 'Owner Approval for '.$days[0]['title']; ?></span></td>
                                            <td>  <input type="text" id="text1" onkeypress="return isNumberWithoutDash(event)" class="form-control" value="<?php echo $days[0]['expenseLimit']; ?>" style="width:70px;height:25px">
                                            </td>
                                             <td>
                                                 <a id="btn-1" href="javascript:void();">
                                                    <button class="btn btn-xs btn-primary waves-effect">
                                                        <i class="material-icons">save</i> 
                                                        <span class="icon-name"> Save</span>
                                                    </button>
                                                </a>                                   
                                            </td>
                                        </tr>
                                        <tr id="sr_rep">
                                            <td><?php echo 'Owner Approval for '.$days[1]['title']; ?></td>
                                              <td><input type="text" id="text2" onkeypress="return isNumberWithoutDash(event)" class="form-control" value="<?php echo $days[1]['expenseLimit']; ?>" style="width:70px;height:25px">
                                            </td>
                                             <td>
                                                 <a id="btn-2" href="javascript:void();">
                                                    <button class="btn btn-xs btn-primary waves-effect">
                                                        <i class="material-icons">save</i> 
                                                        <span class="icon-name"> Save</span>
                                                    </button>
                                                </a>                                   
                                            </td>
                                        </tr>
                                    
                                        <!-- <tr id="sr_rep">
                                            <td><span><?php echo $days[2]['title']; ?></span></td>
                                            <td><input type="text" id="text3" class="form-control" onkeypress="return isNumberWithoutDash(event)"  value="<?php echo $days[2]['expenseLimit']; ?>" style="width:70px;height:25px">
                                            </td>
                                            <td>
                                                 <a id="btn-3" href="javascript:void();">
                                                    <button class="btn btn-xs btn-primary waves-effect">
                                                        <i class="material-icons">save</i> 
                                                        <span class="icon-name"> Save</span>
                                                    </button>
                                                </a>                                   
                                            </td>
                                        </tr> -->
                                        
                                        <!-- <tr id="sr_rep">
                                            <td><span><?php echo $days[3]['title']; ?></span></td>
                                            <td><input type="text" id="text4" class="form-control" onkeypress="return isNumberWithoutDash(event)" value="<?php echo $days[3]['expenseLimit']; ?>" style="width:70px;height:25px">
                                            </td>
                                            <td>
                                                 <a id="btn-4" href="javascript:void();">
                                                    <button class="btn btn-xs btn-primary waves-effect">
                                                        <i class="material-icons">save</i> 
                                                        <span class="icon-name"> Save</span>
                                                    </button>
                                                </a>                                   
                                            </td>
                                        </tr> -->
                                    <tr>
                                        
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!--card-->
                </div>
            </div>
            <!-- #END# Basic Examples -->  
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
    $(document).on('click','#btn-1',function(){
        var amount=$('#text1').val();

        if(amount !=""){
            $.ajax({
                url : "<?php echo site_url('admin/SettingsController/updatedExpenseLimit');?>",
                method : "POST",
                data : {id: '1',amount:amount},
                success: function(data){
                    toastr.success('Record updated', 'Success!');
                },
                beforeSend: function(){
                    $('.comman-ajax-loader').css("visibility", "visible");
                },
                complete: function(){
                    $('.comman-ajax-loader').css("visibility", "hidden");
                },
                error: function(jqXHR, exception) {
                    alert("Something Went Wrong, Please Try Again...!");
                }   
            });
        }else{
            toastr.error('Please enter value', 'Alert!');
        }
    });

    $(document).on('click','#btn-2',function(){
        var amount=$('#text2').val();
        if(amount !=""){
            $.ajax({
                url : "<?php echo site_url('admin/SettingsController/updatedExpenseLimit');?>",
                method : "POST",
                data : {id: '2',amount:amount},
                success: function(data){
                    toastr.success('Record updated', 'Success!');
                },
                beforeSend: function(){
                    $('.comman-ajax-loader').css("visibility", "visible");
                },
                complete: function(){
                    $('.comman-ajax-loader').css("visibility", "hidden");
                },
                error: function(jqXHR, exception) {
                    alert("Something Went Wrong, Please Try Again...!");
                }   
            });

        }else{
            toastr.error('Please enter value', 'Alert!');
        }
    });

    $(document).on('click','#btn-3',function(){
        var amount=$('#text3').val();
        if(amount !=""){
            $.ajax({
                url : "<?php echo site_url('admin/SettingsController/updatedExpenseLimit');?>",
                method : "POST",
                data : {id: '3',amount:amount},
                success: function(data){
                    toastr.success('Record updated', 'Success!');
                },
                beforeSend: function(){
                    $('.comman-ajax-loader').css("visibility", "visible");
                },
                complete: function(){
                    $('.comman-ajax-loader').css("visibility", "hidden");
                },
                error: function(jqXHR, exception) {
                    alert("Something Went Wrong, Please Try Again...!");
                }   
            });

        }else{
            toastr.error('Please enter value', 'Alert!');
        }
    });

</script>


<script type="text/javascript">
     $(document).on('click','#btn-4',function(){
        var amount=$('#text4').val();
        if(amount !=""){
            $.ajax({
                url : "<?php echo site_url('admin/SettingsController/updatedExpenseLimit');?>",
                method : "POST",
                data : {id: '4',amount:amount},
                success: function(data){
                    toastr.success('Record updated', 'Success!');
                },
                beforeSend: function(){
                    $('.comman-ajax-loader').css("visibility", "visible");
                },
                complete: function(){
                    $('.comman-ajax-loader').css("visibility", "hidden");
                },
                error: function(jqXHR, exception) {
                    alert("Something Went Wrong, Please Try Again...!");
                }   
            });

        }else{
            toastr.error('Please enter value', 'Alert!');
        }
    });

</script>

<script>
    function isNumberWithoutDash(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if ((charCode < 48 || charCode > 57) ) {
            return false;
        }
        return true;
    }
</script>
