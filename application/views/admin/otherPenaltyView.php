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
                               Other Penalties
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                              <p id="res"></p>
                                <table class="table" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Penalty For</th>
                                            <th>Percent(%) / Fixed</th>
                                            <th>Value</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <tr id="sr_rep">
                                            <td><?php echo $penalty[0]['title']; ?></td>
                                            <td> 
                                              <div class="demo-radio-button" style="width:100px">
                                                  <input name="percentPenalty" value="0" type="radio" class="with-gap percentPenalty" id="sr_percent" <?php if($penalty[0]["percentOrFixed"]==0){ echo "checked"; } ?>/>
                                                  <label for="sr_percent">Percentage Penalty</label>
                                                  <input name="percentPenalty" value="1" type="radio" id="sr_fixed" class="with-gap percentPenalty" <?php if($penalty[0]["percentOrFixed"]==1){ echo "checked"; } ?>/>
                                                  <label for="sr_fixed">Fixed Amount</label>
                                              </div>
                                            </td>
                                            <td>
                                              <input type="text" id="srValue" class="form-control" value="<?php echo $penalty[0]['multiplier']; ?>" style="width:100px">
                                            </td>
                                             <td>
                                                 <a id="sr_pen_id" href="javascript:void();">
                                                    <button class="btn btn-primary  waves-effect">
                                                        <i class="material-icons">save</i> 
                                                        <span class="icon-name"> Save</span>
                                                    </button>
                                                </a>                                   
                                            </td>
                                        </tr>

                                          <tr id="cash_rep">
                                            <td><?php echo $penalty[1]['title']; ?></td>
                                             <td> 
                                              <div class="demo-radio-button" style="width:100px">
                                                  <input name="fixedPenalty" value="0" type="radio" class="with-gap" id="cash_percent"  <?php if($penalty[1]["percentOrFixed"]==0){ echo "checked"; } ?>/>
                                                  <label for="cash_percent">Percentage Penalty</label>
                                                  <input name="fixedPenalty" value="1" type="radio" id="cash_fixed" class="with-gap"  <?php if($penalty[1]["percentOrFixed"]==1){ echo "checked"; } ?>/>
                                                  <label for="cash_fixed">Fixed Amount</label>
                                              </div>
                                            </td>
                                            <td><input id="cashValue" type="text" class="form-control" value="<?php echo $penalty[1]['multiplier']; ?>" style="width:100px"></td>
                                             <td>
                                                 <a id="cash_pen_id" href="javascript:void();">
                                                  <button class="btn btn-primary  waves-effect">
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
        var value=$('#srValue').val();
        var radioValue = $("input[name='percentPenalty']:checked").val();
         $.ajax({
            url : "<?php echo site_url('admin/PenaltyController/insertSrPenalty');?>",
            method : "POST",
            data : {id: '1',value:value,radioValue:radioValue},
            success: function(data){
              alert('SR Value Updated');
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
    $(document).on('click','#cash_pen_id',function(){
        var value=$('#cashValue').val();
        var radioValue = $("input[name='fixedPenalty']:checked").val();
         $.ajax({
            url : "<?php echo site_url('admin/PenaltyController/insertCashPenalty');?>",
            method : "POST",
            data : {id: '2',value:value,radioValue:radioValue},
            success: function(data){
              alert('Cash Value Updated');
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
 </script>