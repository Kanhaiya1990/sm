<?php $this->load->view('/layouts/commanHeader'); ?>

        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                    Cash Receipt
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2> Cash Receipt</h2><br /><br />
                            <h2 style="color: red;">
                               <!--  Retailer Name: -->  <!-- <?php foreach ($billsdetails as $data) 
                                                {
                                                    echo $data['name'];
                                                }?> -->
                                                <?php 
                                                    if(!empty($billsdetails)){
                                                         echo $billsdetails[0]['name'];
                                                    }
                                                ?>
                                                 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
                                <!-- Bill No     : -->  <!-- <?php foreach ($billsdetails as $data) 
                                                {
                                                    echo $data['billNo'];
                                                }?> --> 
                                                <?php 
                                                    if(!empty($billsdetails)){
                                                         echo $billsdetails[0]['billNo'];
                                                    }
                                                ?>
                                                &nbsp &nbsp&nbsp &nbsp &nbsp &nbsp
                                <!-- Bill Date     : -->  <!-- <?php foreach ($billsdetails as $data) 
                                                {
                                                    echo $data['Date'];
                                                }?> -->
                                                <?php
                                                    if(!empty($billsdetails)){
                                                        $dt=date_create($billsdetails[0]['date']);
                                                        $date = date_format($dt,'d-m-Y');
                                                        echo $date;
                                                    }
                                                ?>
                            </h2>
                        </div>
                            
                        <div class="body">
                            <div class="table-responsive">
                                 <?php
                                if(!empty($msg)){?>
                                    <div class="alert alert-danger">
                                <?php
                                    echo '<p class="statusMsg" >'.$msg.'</p>';
                                ?>
                                </div>
                                    <?php
                                }
                            ?> 
                        
                                 <form method="post" role="form" action="<?php echo site_url('cashier/CashBookController/InsertCashEntry');?>"> 
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Bill No</th>
                                            <th>Net Amt</th>
                                            <th>Received Amt</th>
                                            <th>Pending Amt</th>
                                            <th>Enter Cash Amt</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                         <tr>
                                            <th>Sr.No</th>
                                            <th>Bill No</th>
                                            <th>Net Amt</th>
                                            <th>Received Amt</th>
                                            <th>Pending Amt</th>
                                            <th>Enter Cash Amt</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                          $no=0;
                                          foreach ($billsdetails as $data) 
                                            {

                                             $no++; 
                                          ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td>
                                            <?php echo $data['billNo']; ?> 
                                            <input type="hidden" name="billNo" value="<?php echo $data['billNo']; ?>" readonly>
                                        </td>
                                        <td>
                                            <?php echo $data['netAmount']; ?>
                                              <input type="hidden" name="netAmount" value="<?php echo $data['netAmount']; ?>" readonly> 
                                        </td> 
                                        <td>
                                            <?php echo $data['receivedAmt']; ?>
                                            <input type="hidden" name="receivedAmt" value="<?php echo $data['receivedAmt']; ?>" readonly>
                                            <!-- <input type="hidden" name="selAmount[]" value="<?php echo $data['sellingRate']; ?>" readonly>  -->        
                                        </td>
                                       <td>
                                            <?php echo $data['pendingAmt']; ?>
                                            <input type="hidden" name="pendingAmt" value="<?php echo $data['pendingAmt']; ?>" readonly>
                                       </td>
                                        <td>
                                            <input type="text" class="form-control" name="cashAmt">
                                            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                                            
                                        </td>
                                        
                                   </tr>  
                                     <?php
                                        }
                                      ?>   
                                    </tbody>
                                </table>
                                 <div class="col-md-12">
                                        <center>
                                            <div class="row clearfix">
                                                <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                                    <button type="submit" class="btn btn-primary m-t-15 waves-effect">
                                                        <i class="material-icons">save</i> 
                                                        <span class="icon-name">
                                                        Save
                                                        </span>
                                                    </button>
                                                    <a href="<?php echo site_url('cashier/DeliverySlipController/OutstandingBill');?>">
                                                        <button type="button" class="btn btn-primary m-t-15 waves-effect">
                                                            <i class="material-icons">cancel</i> 
                                                            <span class="icon-name"> Cancel</span>
                                                        </button>
                                                    </a>   
                                                </div>
                                            </div>
                                        </center>
                                    </div>  
                                <?php form_close();?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Examples -->  
        </div>
    </section>

<?php $this->load->view('/layouts/footerDataTable'); ?>