<?php $this->load->view('/layouts/commanHeader'); ?>

        <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                    Role Master
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Role
                            </h2>
                            <h2>
                                <p align="right">
                                  <a href="<?php echo site_url('RoleController/Add');?>">
                                    <button type="submit" class="btn bg-primary margin"><i class="material-icons">person_add</i>  Add  </button></a> 
                                </p> 
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <td>Sr.No</td>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <td>Sr.No</td>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php
                                        $no=0;
                                        foreach ($role as $data) 
                                          {
                                           $no++; 
                                    ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td><?php echo $data['name']; ?></td>
                                            <td>
                                                <a href="<?php echo base_url().'index.php/RoleController/load/'.$data['id']; ?>">
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
        url: "<?php echo site_url('RoleController/delete');?>",
        type: "post",
        data: {'id':id},
        success: function (response) {
         
          swal(response, {
            icon: "success",
          });
          var URL = "<?php echo site_url('RoleController');?>";
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

