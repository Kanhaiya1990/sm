<?php $this->load->view('/layouts/commanHeader'); ?>

        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
        <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                    Employee Master
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Employee
                            </h2><br/>
                            <h2>
                                
                         <!--        <?php echo validation_errors(); ?>
                                <?php echo form_open('EmployeeController/index') ?>
                                <div class="demo-radio-button">
                                    <input name="status" type="radio" id="radio_1"  value="1"/>
                                    <label for="radio_1">Active</label>

                                    <input name="status" type="radio" id="radio_2" value="0"/>
                                    <label for="radio_2">Deactive</label> 

                                     <button type="submit" class="btn bg-primary margin"><i class="fa fa-share"></i> Go</button>
                                 </div>
                                   <?php echo form_close(); ?> --> 
                                <p align="left">
                                 <a href="<?php echo site_url('manager/EmployeeController/index');?>">
                                    <button type="submit" class="btn btnStyle bg-primary margin"><i class="material-icons">airplanemode_active</i> Active </button></a> 

                                    <a href="<?php echo site_url('manager/EmployeeController/Deactive');?>">
                                    <button type="submit" class="btn btnStyle bg-primary margin"><i class="material-icons">airplanemode_inactive</i>  Inactive  </button></a> 
                                </p> 
                                <p align="right">
                                  <a href="<?php echo site_url('manager/EmployeeController/Add');?>">
                                    <button type="submit" class="btn btnStyle bg-primary margin"><i class="material-icons">person_add</i>  Add  </button></a> 
                                </p> 
                            </h2>
                         <!--    <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another action</a></li>
                                        <li><a href="javascript:void(0);">Something else here</a></li>
                                    </ul>
                                </li>
                            </ul> -->
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Code</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Mobile</th>
                                            <th>Company NAme</th>
                                            <th>Role</th>
                                            <th>Salary</th>
                                            <th>Status </th>
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
                                            <th>Company NAme</th>
                                            <th>Role</th>
                                             <th>Salary</th>
                                            <th>Status </th>
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
                                                echo $firstname = $exploded[1];
                                                ?>
                                            </td>
                                            <td><?php echo $data['mobile']; ?></td>
                                            <td><?php echo $data['companyName']; ?></td>
                                            <td><?php echo $data['designation']; ?></td>
                                            <td><?php echo $data['salary']; ?></td>
                         
                                             <td><?php 
                                                $id = $data['id'];
                                                $status1 = $data['status'];                    
                                                if($data['status']==1) {
                                                  echo "<a href=https://yontechsoftwares.com/share/smartdistributor/index.php/manager/EmployeeController/updateStatus/".$id."/".$status1.">Active</a>";
                                                } else {
                                                  echo "<a href=https://yontechsoftwares.com/share/smartdistributor/index.php/manager/EmployeeController/updateStatus/".$id."/".$status1.">Inactive</a>";
                                                }
                                              ?>     
                                            </td>
                                            <td>
                                            <a href="<?php echo base_url().'index.php/manager/EmployeeController/load/'.$data['id']; ?>">
                                                <i class="material-icons" style="color: green;">edit</i>
                                            </a>
                                            &nbsp
                                             <a id="deleted" 
                                                    onclick="deleted(<?php echo $data['id'];?>)" href='#'>
                                                    <b>
                                                        <i class="material-icons" style="color: red;">delete</i> 
                                                    </b>
                                                </a>                                                  
                                        </td>
                                       <!--  <td>  <div class="demo-switch">
                                                <div class="switch">
                                                    <label>Dactive<input type="checkbox" checked><span class="lever"></span>Active</label>
                                                </div>
                                            </div>
                                        </td> -->
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
        url: "<?php echo site_url('manager/EmployeeController/delete');?>",
        type: "post",
        data: {'id':id},
        success: function (response) {
         
          swal(response, {
            icon: "success",
          });
          var URL = "<?php echo site_url('manager/EmployeeController');?>";
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

