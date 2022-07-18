<?php $this->load->view('/layouts/commanHeader'); ?>

<style type="text/css">
    @media screen and (min-width: 1000px) {
        .modal-dialog {
          width: 1000px; /* New width for default modal */
        }
        .modal-sm {
          width: 350px; /* New width for small modal */
        }
    }

    @media screen and (min-width: 1000px) {
        .modal-lg {
          width: 1000px; /* New width for large modal */
        }
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
                               Allocation Wise SR Master
                            </h2>
                           
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Allocation No.</th>
                                            <th>Route</th>
                                             <th>Employee Names</th>
                                           
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                           <th>S. No.</th>
                                            <th>Allocation No.</th>
                                            <th>Route</th>
                                             <th>Employee Names</th>
                                            
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                  
                                      <?php
                                        $no=0;
                                        foreach ($allocations as $data) 
                                          {
                                               $no++; 
                                               $routeName='';
                                               $rtName=explode(",",rtrim($data['routeCode'],','));
                                               for($i=0;$i<count($rtName);$i++){
                                                    $routes=$this->SrModel->getRouteName($rtName[$i]);
                                                    if(!empty($routes)){
                                                        $routeName=$routeName.' '.$routes[0]['name'].', ';
                                                    }
                                               }

                                                $employee="";
                                                if(($data['fieldStaffCode1'] !='0')){
                                                    $emp=$this->SrModel->getEmployeeNamesByID($data['fieldStaffCode1']);
                                                    $employee= $employee.$emp.', ';
                                                }
                                                if(($data['fieldStaffCode2'] !='0')){
                                                    $emp=$this->SrModel->getEmployeeNamesByID($data['fieldStaffCode2']);
                                                    $employee=$employee.$emp.', ';
                                                }
                                                // echo $emp;
                                                // exit;
                                           ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td>
                                                <a id="srEdit-id" href="javascript:void();" data-toggle="modal" data-target="#AllocatedBillModal" data-id="<?php echo $data['id']; ?>">
                                                <?php echo $data['allocationCode']; ?>
                                            </a> 
                                            </td>
                                           <!--  <td>
                                                <a href="<?php echo base_url().'index.php/SrController/AllocationWiseSR/'.$data['id']; ?>"><?php echo $data['allocationCode']; ?></a>
                                            </td> -->
                                            
                                            <td><?php echo rtrim($routeName,', '); ?></td> 
                                            <td><?php echo rtrim($employee,', '); ?></td>
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

<div class="container">
  <div class="modal fade" id="AllocatedBillModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="mods">
              
          </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('/layouts/footerDataTable'); ?>
<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>
<script>
 $(document).ready(function(){
    $('#srEdit-id').click(function(){
        var id=$(this).attr('data-id');

        $.ajax({
            url : "<?php echo site_url('SrController/AllocationWiseSR');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
              $('.mods').html(data);
            }
        });
        

    });
});
</script>

