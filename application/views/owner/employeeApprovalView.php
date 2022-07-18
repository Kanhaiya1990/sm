<?php $this->load->view('/layouts/commanHeader'); ?>
<style type="text/css">
    @media screen and (min-width: 900px) {
        .modal-dialog {
          width: 900px; /* New width for default modal */
        }
        .modal-sm {
          width: 350px; /* New width for small modal */
        }
    }

    @media screen and (min-width: 900px) {
        .modal-lg {
          width: 900px; /* New width for large modal */
        }
    }

    .logo_prov {
        border-radius: 30px;
         border: 1px solid black;
        background: red;
        color: black;
        padding: 6px;
        width: 50px;
        height: 50px;
    }



</style>
        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
           
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Employee Approval Details
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
                                            <th>Salary</th>
                                            <th>Salary_Emp</th>
                                            <th>Login_Emp</th>
                                            <th>Action </th>
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
                                            <th>Salary</th>
                                            <th>Salary_Emp</th>
                                            <th>Login_Emp</th>
                                            <th>Action </th>
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
                                           <td><?php echo $data['salary']; ?></td>
                                            <td><?php if($data['isLoginEmp']==0){ echo "No"; }else{ echo "Yes"; } ?></td>
                                           <td><?php if($data['isLoginEmp']==0){ echo "No"; }else{ echo "Yes"; } ?></td>
                                            <td>


                                            <a href="javascript:void();" data-toggle="modal" data-target="#ownerSalaryModal" id="emp_accept_id" data-id="<?php echo $data['id'];?>">
                                                <i class="material-icons" style="color: blue;">check</i>
                                            </a>
                                            <a href="javascript:void();" id="emp_reject_id"  data-id="<?php echo $data['id'];?>">
                                                <i class="material-icons" style="color: blue;">cancel</i>
                                            </a>
                                         
                                                                                           
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

  <div class="modal fade" id="ownerSalaryModal" role="dialog">
    <div class="modal-dialog">
          <div id="salModalData" class="modal-body">
         
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
    $(document).on('click','#emp_accept_id',function(){
         var id=$(this).attr('data-id');
         $.ajax({
            url : "<?php echo site_url('owner/OfficeAllocationController/empOwnerApproval');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
                // if(data.trim()==="Record accepted"){
                //     alert(data);
                //     window.location.href="<?php echo base_url();?>index.php/owner/OfficeAllocationController/empApproval";
                // }else{
                //     alert(data);
                // }

                $("#salModalData").html(data);
            }
        });
    });

    $(document).on('click','#emp_reject_id',function(){
         var id=$(this).attr('data-id');
         $.ajax({
            url : "<?php echo site_url('owner/OfficeAllocationController/empReject');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
                if(data==="Record rejected"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/owner/OfficeAllocationController/empApproval";
                }else{
                    alert(data);
                }
            }
        });
    });

 </script>
