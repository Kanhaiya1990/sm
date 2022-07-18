<?php $this->load->view('/layouts/commanHeader'); ?>

 <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
<section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
           
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                        <input type="hidden" id="companyCount" class="form-control" value="<?php echo $companyCount; ?>" style="width:70px;height:25px">
                                            
                            <div class="table-responsive">
                              <p id="res"></p>
                                <table style="font-size: 14px" class="table" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Bills</th>
                                            <th></th>
                                            <th></th>
                                             <th></th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <tr id="sr_rep">
                                            <td><span><?php echo $days[1]['name']; ?></span></td>
                                            <td>  <input type="text" id="text2" onkeypress="return isNumberWithoutDash(event)" class="form-control" value="<?php echo $days[1]['days']; ?>" style="width:70px;height:25px">
                                            </td>
                                            <td> days</td>
                                             <td>
                                                 <a id="btn-2" href="javascript:void();">
                                                    <button class="btn btn-xs btn-primary waves-effect">
                                                        <i class="material-icons">save</i> 
                                                        <span class="icon-name"> Save</span>
                                                    </button>
                                                </a>                                   
                                            </td>
                                        </tr>
                                        <tr id="sr_rep">
                                            <td><?php echo $days[2]['name']; ?></td>
                                              <td><input type="text" id="text3" onkeypress="return isNumberWithoutDash(event)" class="form-control" value="<?php echo $days[2]['days']; ?>" style="width:70px;height:25px">
                                            </td>
                                            <td>days</td>
                                             <td>
                                                 <a id="btn-3" href="javascript:void();">
                                                    <button class="btn btn-xs btn-primary waves-effect">
                                                        <i class="material-icons">save</i> 
                                                        <span class="icon-name"> Save</span>
                                                    </button>
                                                </a>                                   
                                            </td>
                                        </tr>
                                    
                                        <tr id="sr_rep">
                                            <td><span><?php echo $days[0]['name']; ?></span></td>
                                            <td><input type="text" class="form-control" onkeypress="return isNumberWithoutDash(event)" id="billdays" name="billdays" value="<?php echo $companyDaysForBills[0]; ?>" style="width:70px;height:25px">
                                            </td>
                                            <td>days for all divisions</td>
                                             
                                             <td>
                                            <button id="billDaysCompanyID" class="btn btn-xs btn-primary waves-effect">
                                                <i class="material-icons">save</i> 
                                                <span class="icon-name"> Save</span>
                                            </button>
                                        </td>
                                        </tr>
                                    <tr>
                                        
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        
                            <div class="table-responsive">
                              <p id="res"></p>
                                <table class="table" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Company specific Retailers</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                <?php
                                    if(!empty($companyName)){
                                        $no=0;
                                        foreach($companyName as $cmp){
                                    ?>
                                        <tr id="sr_rep">
                                            <td><span><?php echo $days[3]['name']; ?></span></td>
                                            <td><span><?php echo $cmp['name']; ?></span></td>
                                            <td><input type="text" class="form-control" name="retailerDays[]" onkeypress="return isNumberWithoutDash(event)" value="<?php if(!empty($companyDaysForRetailers)){ echo $companyDaysForRetailers[$no];}  ?>" onkeypress="return numbersonly(this, event);" style="width:70px;height:25px">
                                            </td>
                                            <td></td>
                                        </tr>
                                    <?php
                                                $no++;
                                            }
                                        }
                                    ?>

                                        <tr id="sr_rep">
                                            <td><span><?php echo $days[3]['name']; ?></span></td>
                                            <td><span>Deliveryslip</span></td>
                                            <td><input type="text" class="form-control" name="retailerDays[]" onkeypress="return numbersonly(this, event);" value="<?php if(!empty($companyDaysForRetailers)){ echo $companyDaysForRetailers[$no];}  ?>" style="width:70px;height:25px">
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                        <td>
                                            <a id="retailerDaysCompanyID" href="javascript:void();">
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
                       

                            <div class="table-responsive">
                              <p id="res"></p>
                                <table class="table" data-page-length='100'>
                                     <thead>
                                        <tr>
                                            <th>Allocations</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="sr_rep">
                                            <td><?php echo $days[9]['name']; ?></td>
                                            <td>
                                              <input type="text" id="text5" class="form-control" onkeypress="return isNumberWithoutDash(event)" value="<?php echo $days[9]['days']; ?>" style="width:70px;height:25px">
                                            </td>
                                            <td><span> days</span></td>
                                             <td>
                                                 <a id="btn-5" data-id="" href="javascript:void();">
                                                    <button class="btn btn-xs btn-primary waves-effect">
                                                        <i class="material-icons">save</i> 
                                                        <span class="icon-name"> Save</span>
                                                    </button>
                                                </a>                                   
                                            </td>
                                        </tr>
                                        <tr id="sr_rep">
                                            <td><?php echo $days[4]['name']; ?></td>
                                            <td>
                                              <input type="text" id="text6" class="form-control" onkeypress="return isNumberWithoutDash(event)" value="<?php echo $days[4]['days']; ?>" style="width:70px;height:25px">
                                            </td>
                                            <td><span> days</span></td>
                                             <td>
                                                 <a id="btn-6" href="javascript:void();">
                                                    <button class="btn btn-xs btn-primary waves-effect">
                                                        <i class="material-icons">save</i> 
                                                        <span class="icon-name"> Save</span>
                                                    </button>
                                                </a>                                   
                                            </td>
                                        </tr>
                                        <tr id="sr_rep">
                                            <td><?php echo $days[10]['name']; ?></td>
                                            <td>
                                              <input type="text" id="godownText" class="form-control" onkeypress="return isNumberWithoutDash(event)" value="<?php echo $days[10]['days']; ?>" style="width:70px;height:25px">
                                            </td>
                                            <td><span> days</span></td>
                                             <td>
                                                 <a id="btn-godownText" href="javascript:void();">
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

                            <div class="table-responsive">
                              <p id="res"></p>
                                <table class="table" data-page-length='100'>
                                     <thead>
                                        <tr>
                                            <th>Cheques</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                   <tbody>
                                        <tr id="sr_rep">
                                            <td><?php echo $days[5]['name']; ?></td>
                                            <td>
                                              <input type="text" id="text7" class="form-control" onkeypress="return isNumberWithoutDash(event)" value="<?php echo $days[5]['days']; ?>" style="width:70px;height:25px">
                                            </td>
                                            <td><span> days</span></td>
                                             <td>
                                                 <a id="btn-7" data-id="" href="javascript:void();">
                                                    <button class="btn btn-xs btn-primary waves-effect">
                                                        <i class="material-icons">save</i> 
                                                        <span class="icon-name"> Save</span>
                                                    </button>
                                                </a>                                   
                                            </td>
                                        </tr>
                                        <tr id="sr_rep">
                                            <td><?php echo $days[6]['name']; ?></td>
                                            <td>
                                              <input type="text" id="text8" class="form-control" onkeypress="return isNumberWithoutDash(event)" value="<?php echo $days[6]['days']; ?>" style="width:70px;height:25px">
                                            </td>
                                            <td><span> days</span></td>
                                             <td>
                                                 <a id="btn-8" href="javascript:void();">
                                                    <button class="btn btn-xs btn-primary waves-effect">
                                                        <i class="material-icons">save</i> 
                                                        <span class="icon-name"> Save</span>
                                                    </button>
                                                </a>                                   
                                            </td>
                                        </tr>

                                         <tr id="sr_rep">
                                            <td><?php echo $days[7]['name']; ?></td>
                                            <td>
                                              <input type="text" id="text9" class="form-control" onkeypress="return isNumberWithoutDash(event)" value="<?php echo $days[7]['days']; ?>" style="width:70px;height:25px">

                                            </td>
                                            <td><span> days</span></td>
                                             <td>
                                                 <a id="btn-9" href="javascript:void();">
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
                    </div><!--card-->
                </div>
            </div>
            <!-- #END# Basic Examples -->  
        </div>
    </section>
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

<script type="text/javascript">
    $(document).on('click','#btn-1',function(){
        var days=$('#text1').val();

        if(days !=""){
            $.ajax({
                url : "<?php echo site_url('admin/SettingsController/updatedDaysLimit');?>",
                method : "POST",
                data : {id: '1',days:days},
                success: function(data){
                //   alert('Record Updated');
                    // location.reload(); 
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
        var days=$('#text2').val();
        if(days !=""){
            $.ajax({
                url : "<?php echo site_url('admin/SettingsController/updatedDaysLimit');?>",
                method : "POST",
                data : {id: '2',days:days},
                success: function(data){
                //   alert('Record Updated');
                    // location.reload(); 
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
        var days=$('#text3').val();
        if(days !=""){
            $.ajax({
                url : "<?php echo site_url('admin/SettingsController/updatedDaysLimit');?>",
                method : "POST",
                data : {id: '3',days:days},
                success: function(data){
                //   alert('Record Updated');
                    // location.reload(); 
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
        var days=$('#text4').val();
        if(days !=""){
            $.ajax({
                url : "<?php echo site_url('admin/SettingsController/updatedDaysLimit');?>",
                method : "POST",
                data : {id: '4',days:days},
                success: function(data){
                //   alert('Record Updated');
                    // location.reload(); 
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
    $(document).on('click','#btn-5',function(){
        var days=$('#text5').val();
        if(days !=""){
            $.ajax({
                url : "<?php echo site_url('admin/SettingsController/updatedDaysLimit');?>",
                method : "POST",
                data : {id: '10',days:days},
                success: function(data){
                //   alert('Record Updated');
                    // location.reload(); 
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

    $(document).on('click','#btn-6',function(){
        var days=$('#text6').val();
        if(days !=""){
            $.ajax({
                url : "<?php echo site_url('admin/SettingsController/updatedDaysLimit');?>",
                method : "POST",
                data : {id: '5',days:days},
                success: function(data){
                //   alert('Record Updated');
                    // location.reload(); 
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

    $(document).on('click','#btn-godownText',function(){
        var days=$('#godownText').val();
        if(days !=""){
            $.ajax({
                url : "<?php echo site_url('admin/SettingsController/updatedDaysLimit');?>",
                method : "POST",
                data : {id: '11',days:days},
                success: function(data){
                //   alert('Record Updated');
                    // location.reload(); 
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
    $(document).on('click','#btn-7',function(){
        var days=$('#text7').val();
        if(days !=""){
            $.ajax({
                url : "<?php echo site_url('admin/SettingsController/updatedDaysLimit');?>",
                method : "POST",
                data : {id: '6',days:days},
                success: function(data){
                //   alert('Record Updated');
                    // location.reload(); 
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

    $(document).on('click','#btn-8',function(){
        var days=$('#text8').val();
        if(days !=""){
            $.ajax({
                url : "<?php echo site_url('admin/SettingsController/updatedDaysLimit');?>",
                method : "POST",
                data : {id: '7',days:days},
                success: function(data){
                //   alert('Record Updated');
                    // location.reload(); 
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

    $(document).on('click','#btn-9',function(){
        var days=$('#text9').val();
        if(days !=""){
            $.ajax({
                url : "<?php echo site_url('admin/SettingsController/updatedDaysLimit');?>",
                method : "POST",
                data : {id: '8',days:days},
                success: function(data){
                //   alert('Record Updated');
                    // location.reload(); 
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

    $("input[type='text']").on("click", function () {
       $(this).select();
    });
 </script>

 <script type="text/javascript">
    $(document).on('click','#billDaysCompanyID',function(){
        var days= $("#billdays").val();
        if(days !=""){
        // alert(days);die();
            $.ajax({
                url : "<?php echo site_url('admin/SettingsController/updatedCompanyDaysLimit');?>",
                method : "POST",
                data : {id: '1',days:days},
                success: function(data){
                //   alert('Record Updated');
                    // location.reload(); 
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
    $(document).on('click','#retailerDaysCompanyID',function(){
        var days= "";
        var companyCount=$('#companyCount').val();
        $("input[name='retailerDays[]']").each(function(){
            days=days+","+$(this).val();
        }); 
      
        var array = days.split(",");
        var newArray = [];
        for (var i = 0; i < array.length; i++) {
            if (array[i] !== "" && array[i] !== null) {
                newArray.push(array[i]);
            }
        }
        if(companyCount != newArray.length){
            toastr.error('Please enter all details', 'Alert!');
            // alert('Please enter all details');
            die();
        }

        $.ajax({
            url : "<?php echo site_url('admin/SettingsController/updatedCompanyDaysLimit');?>",
            method : "POST",
            data : {id: '4',days:days},
            success: function(data){
            //   alert('Record Updated');
                // location.reload(); 
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
