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
                               Income Master
                            </h2>
                            <h2>
                                <p align="right">
                                  <a data-toggle="modal" data-target="#incomeCategoryModal" href="javascript:void();">
                                    <button type="submit" class="btn btnStyle margin"><i class="material-icons">add</i>  Add  </button></a> 
                                </p> 
                            </h2>
                        </div>
                        <div class="body">
                            <!--<div class="table-responsive"> -->
                                <table class="table table-bordered cust-tbl js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Category Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Category Name</th> 
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php
                                        $no=0;
                                        foreach ($income as $data) 
                                          {
                                           $no++; 
                                    ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td><?php echo $data['categoryName']; ?></td>
                                            <td>
                                              <?php if($data['isStatic'] != 1){ ?>
                                                <a id="limit_id" href="javascript:void();" data-toggle="modal" data-id="<?php echo $data['id']; ?>" data-target="#updatelimitModal" class="btn btn-sm process-btn">
                                                    <b><i class="material-icons">edit</i></b>
                                                </a> 
                                                &nbsp
                                                <a id="deleted" 
                                                    onclick="deleted(<?php echo $data['id'];?>)" href='#' class="btn btn-sm btn-danger">
                                                    <b>
                                                        <i class="material-icons">delete</i> 
                                                    </b>
                                                </a>
                                                <?php } ?>                                               
                                            </td>
                                        </tr>
                                    <?php
                                        }
                                      ?> 
                                    </tbody>
                                </table>
                           <!-- </div> -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Examples -->  
        </div>
    </section>


    <div class="modal fade" id="incomeCategoryModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">Add Income Category</h4>
          </div>
          <div class="modal-body">
         <form method="post" role="form" action="<?php echo site_url('admin/CategoriesController/insertIncomeCategory'); ?>"> 
                        <div class="body">
                            <div class="demo-masked-input">
                                <div class="row clearfix">
                                    <input type="hidden" id='limitId' autocomplete="off" name="limitId" list="ret" value="2" class="form-control date">

                                <div class="col-md-12 cust-tbl">
                                        <b>Category Name</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" id='categoryName' autocomplete="off" name="categoryName" list="ret" class="form-control date" placeholder="Enter category Name" required>
                                            </div>
                                        </div>
                                </div> 
                                    
                                <div id="recStatus1"></div>
                                     <div class="col-md-12">
                                        <div class="row clearfix">
                                            <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                                <button id="insRet" class="btn btnStyle m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name">Save</span>
                                                </button>
                                               
                                                    <button data-dismiss="modal" type="button" class="btn btn-danger m-t-15 waves-effect">
                                                        <i class="material-icons">cancel</i> 
                                                        <span class="icon-name"> Cancel</span>
                                                    </button>
                                               
                                            </div>

                                        </div>
                                    </div>                            
                                </div>
                            </div>
                        </div>
                      </form>
          </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="updatelimitModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">Update Income Category</h4>
          </div>
          <div id="up_limitid" class="modal-body">
       
          </div>
      </div>
    </div>
  </div>

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
        url: "<?php echo site_url('admin/CategoriesController/delete');?>",
        type: "post",
        data: {'id':id},
        success: function (response) {
         
          swal(response, {
            icon: "success",
          });
          var URL = "<?php echo site_url('admin/CategoriesController/incomeCategory');?>";
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


<script type="text/javascript">
    $(document).on('click','#limit_id',function(){
         var id=$(this).attr('data-id');
         $.ajax({
            url : "<?php echo site_url('admin/CategoriesController/loadIncomeCategory');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
                $('#up_limitid').html(data);
            }
        });
    });

 </script>
