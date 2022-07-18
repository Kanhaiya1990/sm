<?php $this->load->view('/layouts/commanHeader'); ?>
<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>
<style type="text/css">
    @media screen and (min-width: 1100px) {
        .modal-dialog {
          width: 1100px; /* New width for default modal */
        }
        .modal-sm {
          width: 350px; /* New width for small modal */
        }
    }

    @media screen and (min-width: 1100px) {
        .modal-lg {
          width: 1100px; /* New width for large modal */
        }
    }

</style>
<script>
  $(document).ready(function() {
        $.fn.dataTable.ext.errMode = 'none';
    $('#Tbldata').DataTable( {
        stateSave: false,
      dom: 'Bfrtip',
      buttons: [
      'copyHtml5',
      'excelHtml5',
      'csvHtml5',
      'pdfHtml5'
      ]
    } );
  } );
</script>

        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
        <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
           
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <center><h2>
                               Frequent SR Salesman Report
                            </h2></center><br/>
                        </div>
                        <div class="body">
                         
                            <div class="table-responsive">
                                
                                <table id="Tbldata" style="font-size: 12px;" class="table table-bordered cust-tbl js-exportable dataTable" data-page-length='25'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Salesman Name</th>
                                            <th>Count</th>
                                           
                                        </tr>
                                    </thead>
                                    <tfoot>
                                         <tr>
                                            <th>S. No.</th>
                                            <th>Salesman Name</th>
                                            <th>Count</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php
                                    $no=0;
                                    foreach ($salesmanSR as $data) 
                                    {  
                                    $no++;
                                    $salesman_name = $data['salesman'];
                                    $this->db->select('DISTINCT(allocation_sr_details.billId) as biId, bills.id as bid, bills.salesman');
                                    $this->db->join('bills','bills.id=allocation_sr_details.billId','left outer');
                                    $this->db->where('bills.salesman', $salesman_name);
                                    $query=$this->db->get('allocation_sr_details');
                                    //$aa = $this->db->last_query(); print_r($aa); exit();
                                    $salesmanCount = $query->num_rows();
                                   //  echo $retailerCount;
                                   // return $query->num_rows();
                                        
                                        ?>
                                        <tr>
                                          <td><?php echo $no; ?></td>
                                          <td><?php echo $data['salesman']; ?></td>
                                           
                                          <td><?php //foreach ($count4 as $key => $value) { 
                                       // echo $value->count_rows;

                                            echo $salesmanCount;
                                        
                                   //  } ?></td>
                                     <?php
                                            } 
                                        ?> 
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



