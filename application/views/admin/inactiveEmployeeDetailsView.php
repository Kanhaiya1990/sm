<?php $this->load->view('/layouts/commanHeader'); ?>

        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
           
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Employee Details
                            </h2>
                            <h2>
                            
                               
                                <p align="right">
                                  <a href="<?php echo site_url('admin/EmployeeController');?>">
                                    <button type="submit" class="btn btn-xs bg-primary margin"><i class="material-icons">airplanemode_active</i>  Active  </button>
                                  </a> 
                                  <a href="<?php echo site_url('admin/EmployeeController/inactiveEmployee');?>">
                                    <button type="submit" class="btn btn-xs bg-primary margin"><i class="material-icons">airplanemode_inactive</i>  Inactive  </button>
                                  </a> 
                                  
                                </p> 
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Code</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Mobile</th>
                                            <th>Company Name</th>
                                            <th>Role</th>
                                            <th>Status </th>
                                            <!-- <th>Action</th> -->
                                            
                                        </tr>
                                    </thead>
                                    <tfoot>
                                         <tr>
                                            <th>Sr.No</th>
                                            <th>Code</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Mobile</th>
                                            <th>Company Name</th>
                                            <th>Role</th>
                                             <th>Status </th>
                                            <!-- <th>Action</th> -->
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        $no=0;
                                        foreach ($employee as $data) 
                                          {
                                           $no++; 
                                        ?>
                                        <tr>
                                           <td><?php echo $no; ?></td>
                                            <td><?php echo $data['code']; ?></td>
                                            <td><?php  
                                                $str=$data['name']; 
                                                $exploded=explode(" ",$str);
                                                echo $firstname = $exploded[0];
                                                ?>
                                            </td>
                                             <td><?php  
                                                $str=$data['name']; 
                                                $exploded=explode(" ",$str);
                                                if(!empty($exploded[1])){
                                                     echo $firstname = $exploded[1];
                                                }
                                               
                                                ?>
                                            </td>
                                            <td><?php echo $data['mobile']; ?></td>
                                            <td><?php echo $data['companyName']; ?></td>
                                            <td><?php echo $data['designation']; ?></td>
                         
                                            <td><?php 
                                                $id = $data['id'];
                                                $status1 = $data['status'];                    
                                                if($data['status']==1) { 
                                            ?>
                                                <a href='<?php echo site_url("admin/EmployeeController/updateStatus/".$id."/".$status1);?>'><i class="material-icons" style="color: green;">remove_circle</i></a>
                                            <?php }else{  ?>
                                                <a href='<?php echo site_url("admin/EmployeeController/updateStatus/".$id."/".$status1);?>'><i class="material-icons" style="color: red;">add_circle</i></a>
                                            <?php } ?>  
                                               <a href='<?php echo site_url("admin/EmployeeController/updateDeleteStatus/".$id);?>'><i class="material-icons" style="color: red;">delete</i></a>   
                                            </td>
                                            
                                    
                                        </tr>
                                        <?php
                                            }
                                        ?> 
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

<div class="modal fade" id="limitModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">Employee Details</h4>
          </div>
          <div class="modal-body">
         
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
        url: "<?php echo site_url('admin/EmployeeController/delete');?>",
        type: "post",
        data: {'id':id},
        success: function (response) {
         
          swal(response, {
            icon: "success",
          });
          var URL = "<?php echo site_url('admin/EmployeeController');?>";
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
    $(document).on('click','#emp_det_id',function(){
         var id=$(this).attr('data-id');
         $.ajax({
            url : "<?php echo site_url('admin/EmployeeController/empDetails');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
                $('.modal-body').html(data);
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
