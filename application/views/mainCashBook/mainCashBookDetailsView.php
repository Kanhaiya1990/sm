<?php $this->load->view('/layouts/commanHeader'); ?>

<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/>
<section class="col-md-12 box">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    
            <div class="body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                        <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-exportable dataTable" data-page-length='100'>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Nature</th>
                                        <th>Employee</th>
                                        <th>Reference</th>
                                        <th class="text-right">Inflow</th>
                                        <th class="text-right">Outflow</th>
                                        <th class="text-right">Balance</th>

                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Date</th>
                                        <th>Nature</th>
                                        <th>Employee</th>
                                        <th>Reference</th>
                                        <th class="text-right">Inflow</th>
                                        <th class="text-right">Outflow</th>
                                        <th class="text-right">Balance</th>

                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php 
                                        if(!empty($mainCashBook)){ 
                                            foreach($mainCashBook as $data){
                                    ?>
                                          <tr>
                                            <td><?php echo date("d-M-Y h:i a", strtotime($data['date'])); ?></td>
                                            <td><?php echo $data['nature']?></td>
                                            <td><?php echo $data['empName']?></td>
                                            <td><?php echo $data['narration']?></td>
                                            <?php if($data['inoutStatus']=="Inflow"){ ?>
                                                <td class="text-right" style="color:blue"><?php echo number_format($data['amount']); ?></td>
                                                <td></td>
                                            <?php }else{ 
                                                    if($data['nature']=="Bank Deposit"){
                                            ?>
                                                 <td></td>
                                                <td class="text-right" style="color:green"><?php echo number_format($data['amount']); ?></td>
                                            <?php 
                                                }else{

                                                
                                            ?>
                                                <td></td>
                                                <td class="text-right" style="color:red"><?php echo number_format($data['amount']); ?></td>
                                            <?php }
                                            }
                                            ?>
                                            <td class="text-right"><?php echo number_format($data['balance']); ?></td>
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
    </div>
</section>
<?php $this->load->view('/layouts/footerDataTable'); ?>