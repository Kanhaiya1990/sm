<?php $this->load->view('/layouts/commanHeader'); ?>

<style type="text/css">
    @media screen and (min-width: 1100px) {
        .modal-dialog {
          width: 1100px; /* New width for default modal */
        }
        .modal-sm {
          width: 400px; /* New width for small modal */
        }
    }

    @media screen and (min-width: 1100px) {
        .modal-lg {
          width: 1100px; /* New width for large modal */
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
<script src="<?php echo base_url('assets/js/pages/ui/tooltips-popovers.js');?>"></script>
<script   src="https://code.jquery.com/jquery-1.12.1.js" integrity="sha256-VuhDpmsr9xiKwvTIHfYWCIQ84US9WqZsLfR4P7qF6O8="   crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
               
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                              Journal Details for : <?php echo $code; ?>
                            </h2>
                             <h2>
                               
                            </h2>
                        </div>
                        <div class="body">
                         
                            <div class="table-responsive">
                                <table style="font-size: 11px" class="table table-bordered table-striped table-hover js-exportable dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Bill No</th>
                                            <th>Retailer </th>
                                            <th>Allocation</th>
                                            <th>Bill Amount</th>
                                            <th>Pending Amount</th>
                                            <th>Debit/ Credit Amount</th>
                                            <th> Mode </th>
                                            <th> Date </th>
                                            <th>Employee </th>
                                            <th>Remark</th>
                                            <!-- <th>Action</th> -->
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Bill No</th>
                                            <th>Retailer </th>
                                            <th>Allocation</th>
                                            <th>Bill Amount</th>
                                            <th>Pending Amount</th>
                                            
                                            <th>Debit / Credit Amount</th>
                                            <th> Mode </th>
                                            <th> Date </th>
                                            <th>Employee </th>
                                            <th>Remark</th>
                                            <!-- <th>Action</th> -->
                                        </tr>
                                    </tfoot>
                                    <tbody id="hideInfo">
                                        <?php 
                                        $no=0;
                                            if(!empty($journalEntry)){
                                            foreach($journalEntry as $data){
                                                $no++;

                                                $dt=date_create($data['date']);
                                                $date = date_format($dt,'d-M-Y H:i A');
                                        ?>
                                       
                                        <tr>
                                            <td><?php echo $no;?></td>
                                            <td><?php echo $data['billNo'];?></td>
                                            <td><?php echo $data['retailerName'];?></td>
                                            <td><?php echo $data['allocationCode'];?></td>
                                            <td align="right"><?php echo number_format($data['billAmount']);?></td>
                                            <td align="right"><?php echo number_format($data['balanceAmount']);?></td>
                                            
                                            <td align="right"><?php echo number_format(abs($data['paidAmount']));?></td>
                                            <td><?php echo $data['paymentMode'];?></td>
                                            <td><?php echo $date;?></td>
                                            <td><?php echo $data['empName'];?></td>
                                            <td><?php echo $data['tallyRemark'];?></td>
                                        </tr>
                                        <?php 
                                            }
                                           } 
                                        ?>
                                    
                                    </tbody>
                                </table>

                                <table style="font-size: 11px" class="table table-bordered table-striped table-hover js-exportable dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Employee</th>
                                            <th>Debit Amount </th>
                                            <th>Remark </th>
                                            <th>Transaction Date </th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Employee</th>
                                            <th>Debit Amount </th>
                                            <th>Remark </th>
                                            <th>Transaction Date </th>
                                        </tr>
                                    </tfoot>
                                    <tbody id="hideInfo">
                                        <?php 
                                        $no=0;
                                            if(!empty($journalEntryEmp)){
                                            foreach($journalEntryEmp as $data){
                                                $no++;

                                                $dt=date_create($data['createdAt']);
                                                $date = date_format($dt,'d-M-Y H:i A');
                                        ?>
                                       
                                        <tr>
                                            <td><?php echo $no;?></td>
                                            <td><?php echo $data['empName'];?></td>
                                            <td><?php echo $data['amount'];?></td>
                                            <td><?php echo $data['description'];?></td>
                                            <td><?php echo $date;?></td>
                                        </tr>
                                        <?php 
                                            }
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

<?php $this->load->view('/layouts/processButtonView'); ?>


<script type="text/javascript">
    $( "#cmp").click(function() {
      $( "#cmp" ).select();
    });
</script>