<?php $this->load->view('/layouts/commanHeader'); ?>

<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
<section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                            Delivery Slip Setting
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                              <p id="res"></p>
                                <table class="table" data-page-length='100'>
                                    <tbody>
                                        <tr id="sr_rep">
                                            <td> 
                                              <div class="demo-radio-button">
                                                  <input name="percentPenalty" value="yes" type="radio" class="with-gap percentPenalty" id="sr_percent" <?php if($setting[0]["propertyValue"]=="yes"){ echo "checked"; } ?>/>
                                                  <label for="sr_percent">Show Retailer Code And Address</label>
                                                  <br><input name="percentPenalty" value="no" type="radio" id="sr_fixed" class="with-gap percentPenalty" <?php if($setting[0]["propertyValue"]=="no"){ echo "checked"; } ?>/>
                                                  <label for="sr_fixed">Hide Retailer Code And Address</label>
                                              </div>
                                            </td>
                                             <td>
                                                 <a id="sr_pen_id" href="javascript:void();">
                                                    <button class="btn btn-sm btn-primary  waves-effect">
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
                        <div class="header">
                            <h2>
                            Other Adjustment Approval Setting
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                              <p id="res"></p>
                                <table class="table" data-page-length='100'>
                                    <tbody>
                                        <tr id="sr_rep">
                                            <td> 
                                              <div class="demo-radio-button">
                                                  <input name="otherAdj" value="yes" type="radio" class="with-gap otherAdj" id="sr_percent1" <?php if($setting[2]["propertyValue"]=="yes"){ echo "checked"; } ?>/>
                                                  <label for="sr_percent1">Show Other Adjustment to Manager</label>
                                                  <br><input name="otherAdj" value="no" type="radio" id="sr_fixed1" class="with-gap otherAdj" <?php if($setting[2]["propertyValue"]=="no"){ echo "checked"; } ?>/>
                                                  <label for="sr_fixed1">Show Other Adjustment to Godownkeeper</label>
                                              </div>
                                            </td>
                                             <td>
                                                 <a id="other_adj_id" href="javascript:void();">
                                                    <button class="btn btn-sm btn-primary  waves-effect">
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
    $(document).on('click','#sr_pen_id',function(){
        var radioValue = $("input[name='percentPenalty']:checked").val();
         $.ajax({
            url : "<?php echo site_url('admin/SettingsController/saveSettingForDeliveryslip');?>",
            method : "POST",
            data : {id: '1',radioValue:radioValue},
            success: function(data){
                alert('Value Updated');
                location.reload(true); 
            }
        });
    });

    $(document).on('click','#other_adj_id',function(){
        var radioValue = $("input[name='otherAdj']:checked").val();
         $.ajax({
            url : "<?php echo site_url('admin/SettingsController/saveSettingForDeliveryslip');?>",
            method : "POST",
            data : {id: '3',radioValue:radioValue},
            success: function(data){
                alert('Value Updated');
                location.reload(true); 
            }
        });
    });

    $("input[type='text']").on("click", function () {
       $(this).select();
    });
 </script>