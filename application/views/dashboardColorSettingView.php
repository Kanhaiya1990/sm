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
                               Color Code Setting for Dashboard
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                              <p id="res"></p>
                                <table class="table cust-tbl" data-page-length='100'>
                                    <tbody>
                                        <tr id="sr_rep">
                                            <td>Color for Dashboard : </td>
                                            <td>
                                              <input type="color" id="colorValue" class="form-control" value="<?php echo $setting[1]['name']; ?>" style="width:100px">
                                            </td>
                                             <td>
                                                 <a id="sr_pen_id" href="javascript:void();">
                                                    <button class="btn btnStyle  waves-effect">
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
        var colorValue=$('#colorValue').val();
         $.ajax({
            url : "<?php echo site_url('DashbordController/saveDashboard');?>",
            method : "POST",
            data : {id: '2',colorValue:colorValue},
            success: function(data){
                alert('Ribbon Color Changed...!');
                location.reload(); 
            }
        });
    });

    $("input[type='text']").on("click", function () {
       $(this).select();
    });
 </script>