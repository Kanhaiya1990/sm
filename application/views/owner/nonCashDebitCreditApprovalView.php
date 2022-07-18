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
                               Non Cash Debit/Credit Approval
                            </h2>
                            
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Employee</th>
                                            <th>Amount</th>
                                            <th>Transaction Type</th>
                                            <th>Remark</th>
                                            <th>Action </th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                         <tr>
                                            <th>Sr.No</th>
                                            <th>Employee</th>
                                            <th>Amount</th>
                                            <th>Transaction Type</th>
                                            <th>Remark</th>
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
                                            <td><?php echo $data['empName']; ?></td>
                                           
                                           
                                            <td><?php echo $data['amount']; ?></td>
                                            <td><?php echo $data['transactionType']; ?></td>
                                            <td><?php echo $data['description']; ?></td>
                                           
                                            <td>
                                            <a href="javascript:void();" id="emp_noncash_accept_id" data-id="<?php echo $data['id'];?>">
                                                <i class="material-icons" style="color: blue;">check</i>
                                            </a>
                                            <a href="javascript:void();" id="emp_noncash_reject_id"  data-id="<?php echo $data['id'];?>">
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



<?php $this->load->view('/layouts/footerDataTable'); ?>



<script type="text/javascript">
    $(document).on('click','#emp_noncash_accept_id',function(){
         var id=$(this).attr('data-id');
         $.ajax({
            url : "<?php echo site_url('owner/ExpensesController/transactionAccept');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
                if(data.trim()==="Record accepted"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/owner/ExpensesController/nonCashDebitCreditApproval";
                }else{
                    alert(data);
                }
            }
        });
    });

    $(document).on('click','#emp_noncash_reject_id',function(){
         var id=$(this).attr('data-id');
         $.ajax({
            url : "<?php echo site_url('owner/ExpensesController/transactionRejected');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
                if(data==="Record rejected"){
                    alert(data);
                    window.location.href="<?php echo base_url();?>index.php/owner/ExpensesController/nonCashDebitCreditApproval";
                }else{
                    alert(data);
                }
            }
        });
    });

 </script>
