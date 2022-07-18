<?php $this->load->view('/layouts/commanHeader'); ?>

        <section class="content">
        <div class="container-fluid">
           
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                              Bills Limit
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                              <p id="res"></p>
                                <table style="font-size: 14px" class="table" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Days Limit For</th>
                                            <th>Days or Bills</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="sr_rep">
                                            <td><?php echo $days[0]['name']; ?></td>
                                            <td>
                                              <input type="text" id="unallocatedDays" class="form-control" value="<?php echo $days[0]['days']; ?>" style="width:100px">
                                            </td>
                                             <td>
                                                 <a id="unallocated_pen_id" data-id="" href="javascript:void();">
                                                    <button class="btn btn-primary waves-effect">
                                                        <i class="material-icons">save</i> 
                                                        <span class="icon-name"> Save</span>
                                                    </button>
                                                </a>                                   
                                            </td>
                                        </tr>
                                        <tr id="sr_rep">
                                            <td><?php echo $days[1]['name']; ?></td>
                                            <td>
                                              <input type="text" id="resendDays" class="form-control" value="<?php echo $days[1]['days']; ?>" style="width:100px">
                                            </td>
                                             <td>
                                                 <a id="resend_pen_id" href="javascript:void();">
                                                    <button class="btn btn-primary waves-effect">
                                                        <i class="material-icons">save</i> 
                                                        <span class="icon-name"> Save</span>
                                                    </button>
                                                </a>                                   
                                            </td>
                                        </tr>
                                        <tr id="sr_rep">
                                            <td><?php echo $days[2]['name']; ?></td>
                                            <td>
                                              <input type="text" id="pendingNeftDays" class="form-control" value="<?php echo $days[2]['days']; ?>" style="width:100px">
                                            </td>
                                             <td>
                                                 <a id="neft_pen_id" href="javascript:void();">
                                                    <button class="btn btn-primary waves-effect">
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
    $(document).on('click','#unallocated_pen_id',function(){
        var days=$('#unallocatedDays').val();
         $.ajax({
            url : "<?php echo site_url('admin/SettingsController/updatedDaysLimit');?>",
            method : "POST",
            data : {id: '1',days:days},
            success: function(data){
              alert('Record Updated');
                location.reload(); 
            }
        });
    });

    $(document).on('click','#resend_pen_id',function(){
        var days=$('#resendDays').val();
         $.ajax({
            url : "<?php echo site_url('admin/SettingsController/updatedDaysLimit');?>",
            method : "POST",
            data : {id: '2',days:days},
            success: function(data){
              alert('Record Updated');
                location.reload(); 
            }
        });
    });

    $(document).on('click','#neft_pen_id',function(){
        var days=$('#pendingNeftDays').val();
         $.ajax({
            url : "<?php echo site_url('admin/SettingsController/updatedDaysLimit');?>",
            method : "POST",
            data : {id: '3',days:days},
            success: function(data){
              alert('Record Updated');
                location.reload(); 
            }
        });
    });

    $("input[type='text']").on("click", function () {
       $(this).select();
    });
 </script>