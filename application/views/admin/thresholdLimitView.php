<?php $this->load->view('/layouts/commanHeader'); ?>

<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
<section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                       
                        <div class="body">
                            <div class="table-responsive">
                              <p id="res"></p>
                                <table class="table" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th> Expense Limit</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="sr_rep">
                                            <td><?php echo $expenseLimit[0]['title']; ?></td>
                                            
                                            <td>
                                              <input type="text" id="text1" class="form-control" value="<?php echo $expenseLimit[0]['expenseLimit']; ?>" style="width:70px;height:25px">
                                            </td>
                                            
                                             <td>
                                                 <a id="btn-one" href="javascript:void();">
                                                    <button class="btn btn-xs btn-primary waves-effect">
                                                        <i class="material-icons">save</i> 
                                                        <span class="icon-name"> Save</span>
                                                    </button>
                                                </a>                                   
                                            </td>
                                        </tr>
                                        <tr id="sr_rep">
                                            <td><?php echo $expenseLimit[1]['title']; ?></td>
                                           
                                            <td>

                                              <input type="text" id="text2" class="form-control" value="<?php echo $expenseLimit[1]['expenseLimit']; ?>" style="width:70px;height:25px">
                                            </td>
                                            
                                             <td>
                                                 <a id="btn-two" href="javascript:void();">
                                                    <button class="btn btn-xs btn-primary waves-effect">
                                                        <i class="material-icons">save</i> 
                                                        <span class="icon-name"> Save</span>
                                                    </button>
                                                </a>                                   
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                      
                        <div class="body">
                            <div class="table-responsive">
                              <p id="res"></p>
                                <table class="table" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Residual Bill Clearing Limit </th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="sr_rep">
                                            <td><?php echo $expenseLimit[3]['title']; ?></td>
                                            <td>
                                              <input type="text" id="text3" class="form-control" value="<?php echo $expenseLimit[3]['expenseLimit']; ?>" style="width:70px;height:25px">
                                            </td>

                                             <td>
                                                 <a id="btn-three" href="javascript:void();">
                                                    <button class="btn btn-xs btn-primary waves-effect">
                                                        <i class="material-icons">save</i> 
                                                        <span class="icon-name"> Save</span>
                                                    </button>
                                                </a>                                   
                                            </td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <div class="card">
                        <div class="body">
                            <div class="table-responsive">
                              <p id="res"></p>
                                <table class="table" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Resend Limit</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="sr_rep">
                                            <td><?php echo $resendData[0]['title']; ?></td>
                                            <td>
                                              <input type="text" id="text4" class="form-control" value="<?php echo $resendData[0]['resendLimit']; ?>" style="width:100px">
                                            </td>
                                            <td>Percent</td>
                                             <td>
                                                 <a id="btn-four" href="javascript:void();">
                                                    <button class="btn btn-xs btn-primary waves-effect">
                                                        <i class="material-icons">save</i> 
                                                        <span class="icon-name"> Save</span>
                                                    </button>
                                                </a>                                   
                                            </td>
                                        </tr>
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
        url: "<?php echo site_url('admin/PenaltyController/delete');?>",
        type: "post",
        data: {'id':id},
        success: function (response) {
         
          swal(response, {
            icon: "success",
          });
          var URL = "<?php echo site_url('admin/PenaltyController');?>";
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

<?php $this->load->view('/layouts/footerDataTable'); ?>

<script type="text/javascript">
    $(document).on('click','#btn-one',function(){
        var amount=$('#text1').val();
         $.ajax({
            url : "<?php echo site_url('admin/EmployeeRelationController/insertExpenseLimit');?>",
            method : "POST",
            data : {id: '1',amount:amount},
            success: function(data){
              alert('Amount Updated');
                location.reload(); 
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
    });

    $(document).on('click','#btn-two',function(){
        var amount=$('#text2').val();
         $.ajax({
            url : "<?php echo site_url('admin/EmployeeRelationController/insertExpenseLimit');?>",
            method : "POST",
            data : {id: '2',amount:amount},
            success: function(data){
              alert('Amount Updated');
                location.reload(); 
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
    });

    $("input[type='text']").on("click", function () {
       $(this).select();
    });
 </script>


<script type="text/javascript">
    $(document).on('click','#btn-three',function(){
        var amount=$('#text3').val();
         $.ajax({
            url : "<?php echo site_url('admin/SettingsController/insertBillClearenceLimit');?>",
            method : "POST",
            data : {id: '4',amount:amount},
            success: function(data){
              alert('Amount Updated');
                location.reload(); 
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
    });

    $("input[type='text']").on("click", function () {
       $(this).select();
    });
 </script>

 <script type="text/javascript">
    $(document).on('click','#btn-four',function(){
        var recend_percent=$('#text4').val();
         $.ajax({
            url : "<?php echo site_url('admin/SettingsController/updateResendLimit');?>",
            method : "POST",
            data : {id: '1',recend_percent:recend_percent},
            success: function(data){
              alert('Resend Percentage Updated');
                location.reload(); 
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
    });
   

    $("input[type='text']").on("click", function () {
       $(this).select();
    });
 </script>