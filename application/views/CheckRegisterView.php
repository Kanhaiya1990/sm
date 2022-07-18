<?php $this->load->view('/layouts/commanHeader'); ?>

<script   src="https://code.jquery.com/jquery-1.12.1.js" integrity="sha256-VuhDpmsr9xiKwvTIHfYWCIQ84US9WqZsLfR4P7qF6O8="   crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#chk-reg-tbl').DataTable( {
            dom: 'Bfrtip',
            buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print','email'
            ]
        } );
    } );
</script>
<style type="text/css">
.selectStyle select {
   background: transparent;
   width: 250px;
   padding: 4px;
   font-size: 1em;
   border: 1px solid #ddd;
   height: 25px;
}
li{
    margin-bottom: 0PX;
    padding-bottom: 0PX;
}
</style>
<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
<section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
    <!-- <section class="content"> -->
        <div class="container-fluid">
            <!-- <div class="block-header">
                <h2>Cheque Register</h2>
            </div> -->
            <!-- Basic Examples -->
            <div class="row clearfix" id="page">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                             Cheque Register 
                         </h2>

                        <p align="right">
                            <a href="<?php echo site_url('CashAndChequeController/ChequeRegister');?>">
                              <button class="btn btn-sm bg-primary margin"><i class="material-icons">refresh</i>    </button></a> 
                        </p>
                         
                     </div>
                     <div class="body">
                        <form method="post" role="form" action="<?php echo site_url('CashAndChequeController/ChequeRegister');?>">
                            <input name="selectedDateType" value="receive" type="radio" id="radio_1" checked />
                            <label for="radio_1">Receipt Date </label>
                            <input name="selectedDateType" value="cheque" type="radio" id="radio_2" />
                            <label for="radio_2">Cheque Date </label>
                            <label>From Date:</label>
                            <input type="date" name="from_date" required >
                            <label>To Date:</label>
                            <input type="date" name="to_date" required>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </form>
                      <div class="row">                                 
                        <div class="row m-t-20">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                   <table id="chk-reg-tbl" style="font-size: 11px" class="table table-bordered table-striped table-hover dataTable js-exportable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th> S.No.</th>
                                            <th> Receipt Date </th>
                                            <th> Retailer Name </th>
                                            <th> Cheque No.  </th>
                                            <th> Cheque Date  </th>
                                            <th> Cheque Amount</th>
                                            <th>  Bank </th>
                                            <th> Company</th> 
                                            <th> Bill No</th>
                                            <th> Bill Date</th>
                                            <th> Cheque Days</th>
                                            <th> Current Status </th>
                                        </tr>
                                      </thead>
                                       <tfoot>
                                            <tr>
                                                <th> S.No.</th>
                                                <th> Receipt Date </th>
                                                <th> Retailer Name </th>
                                                <th> Cheque No.  </th>
                                                <th> Cheque Date  </th>
                                                <th> Cheque Amount</th>
                                                <th>  Bank </th>
                                                <th> Company</th> 
                                                <th> Bill No</th>
                                                <th> Bill Date</th>
                                                <th> Cheque Days</th>
                                                <th> Current Status </th>
                                            </tr>
                                        </tfoot>    
                                        <tbody>
                                            <?php
                                            $no=0;
                                            foreach ($billpayments as $data) 
                                            {
                                               $no++; 

                                               $billInfo=$this->CashAndChequeModel->load('bills',$data['billId']);
                                               $pendAmt=0;
                                               if(!empty($billInfo)){
                                                 $pendAmt=$billInfo[0]['pendingAmt'];
                                               }  

                                               ?>
                                               <tr>
                                                <?php
                                                    $rname="";
                                                    $billDate="";
                                                    $diff="";
                                                    $billsID= $data['billId'];
                                                    // echo $billsID."<br>";
                                                    $billsID=explode(',',$billsID);
                                                    
                                                    $chequeReceivedDate="";
                                                    if (trim($data['chequeReceivedDate']) == '' || substr($data['chequeReceivedDate'],0,10) == '0000-00-00') {
                                                        $chequeReceivedDate= '';
                                                    }else{
                                                        $dt=date_create($data['chequeReceivedDate']);
                                                        $chequeReceivedDate = date_format($dt,'d-M-Y');
                                                    }

                                                    $chequeDate="";
                                                    if (trim($data['chequeDate']) == '' || substr($data['chequeDate'],0,10) == '0000-00-00') {
                                                        $chequeDate= '';
                                                    }else{
                                                        $dt=date_create($data['chequeDate']);
                                                        $chequeDate = date_format($dt,'d-M-Y');
                                                    }
                                                    

                                                    $recdate=strtotime($chequeReceivedDate);
                                                    $chqdate=strtotime($chequeDate);
                                                    
                                                    foreach($billsID as $b){
                                                      
                                                      if($b>0){
                                                        $retailer=$this->CashAndChequeModel->getRetailerbyBills('bills',$b);
                                                        $rname=$rname.$retailer.',';
                                                        $billDate=$this->CashAndChequeModel->getDatebyBills('bills',$b);

                                                        if($recdate>=$chqdate){
                                                          // echo $chequeReceivedDate;
                                                            $diff=$recdate-strtotime($billDate);
                                                            $diff= abs(round($diff/86400));
                                                        }else{
                                                           // echo $chequeDate;
                                                            $diff=$chqdate-strtotime($billDate);
                                                            $diff= abs(round($diff/86400));
                                                        }

                                                      }
                                                    }
                                                    $rname= trim($rname,',');

                                                ?>
                                                <td><?php echo $no; ?></td>
                                                <td><?php  echo $chequeReceivedDate; ?></td>
                                                 <td>
                                                            <?php  
                                                                if($data['retailerName'] !=""){
                                                                    echo $data['retailerName']; 
                                                                }else{
                                                                    echo $rname; 
                                                                }
                                                            ?>
                                                        </td>
                                                <td><?php echo $data['chequeNo']; ?></td>
                                                <td><?php echo $chequeDate; ?> </td>
                                                <td align="right"><?php echo number_format($data['sumAmount']); ?></td>
                                                <td><?php echo strtoupper($data['chequeBank']); ?></td>
                                                <td>
                                                    <?php
                                                        $cmp=$data['compName']; 
                                                        echo trim($cmp,',');
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        $billNo=$data['billNo'];
                                                         echo rtrim($billNo,',');
                                                    ?>
                                                </td>  
                                                <td><?php
                                                    $dt=date_create($billDate);
                                                    $data['billDate'] = date_format($dt,'d-M-Y');
                                                    echo $data['billDate']; ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                        if($data['billId']==0){

                                                        }else{
                                                           echo $diff;
                                                        }
                                                    ?>
                                                </td>
                                                <td><?php echo $data['chequeStatus']; ?></td>
                                                
                                            </tr>
                                        <?php
                                            }

                                            foreach ($billpaymentsAdHoc as $data) 
                                            {
                                               $no++; 
                                               ?>
                                               <tr>
                                            <?php
                                                $rname="";
                                                $billDate="";
                                                $billsID= $data['billId'];
                                                // echo $billsID."<br>";
                                                $billsID=explode(',',$billsID);
                                                // print_r($billsID);
                                                $rname= trim($rname,',');

                                                $chequeReceivedDate="";
                                                if (trim($data['chequeReceivedDate']) == '' || substr($data['chequeReceivedDate'],0,10) == '0000-00-00') {
                                                    $chequeReceivedDate= '';
                                                }else{
                                                    $dt=date_create($data['chequeReceivedDate']);
                                                    $chequeReceivedDate = date_format($dt,'d-M-Y');
                                                }

                                                $chequeDate="";
                                                if (trim($data['chequeDate']) == '' || substr($data['chequeDate'],0,10) == '0000-00-00') {
                                                    $chequeDate= '';
                                                }else{
                                                    $dt=date_create($data['chequeDate']);
                                                    $chequeDate = date_format($dt,'d-M-Y');
                                                }

                                                $billdt=date_create($data['date']);
                                                $billDate = date_format($billdt,'d-M-Y');

                                                $recdate=strtotime($chequeReceivedDate);
                                                $chqdate=strtotime($chequeDate);


                                                if($recdate>=$chqdate){
                                                    $diff=$recdate-strtotime($billDate);
                                                    $diff= abs(round($diff/86400));
                                                }else{
                                                    $diff=$chqdate-strtotime($billDate);
                                                    $diff= abs(round($diff/86400));
                                                }

                                                $no++;
                                            ?>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $chequeReceivedDate; ?> </td>
                                                <td><?php echo $data['retailerName']; ?></td>
                                                <td><?php echo $data['chequeNo']; ?></td>
                                                <td><?php echo $chequeDate; ?>  </td>
                                                <td><?php echo $data['paidAmount']; ?></td>
                                                <td><?php echo strtoupper($data['chequeBank']); ?></td>
                                                <td>
                                                    <?php
                                                        $cmp=$data['compName']; 
                                                        echo trim($cmp,',');
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $billNo=$data['billNo'];
                                                     echo rtrim($billNo,',');
                                                    ?>
                                                </td>  
                                                <td><?php echo $billDate; ?></td>
                                                <td><?php echo $diff;?></td>
                                                <td><?php echo $data['chequeStatus']; ?></td>
                                                
                                                </tr>
                                        <?php
                                            }

                                          foreach ($bouncedBillPayments as $data) 
                                            {
                                               $no++; 

                                               $billInfo=$this->CashAndChequeModel->load('bills',$data['billId']);
                                               $pendAmt=0;
                                               if(!empty($billInfo)){
                                                 $pendAmt=$billInfo[0]['pendingAmt'];
                                               }  

                                               ?>
                                               <tr>
                                                <?php
                                                    $rname="";
                                                    $billDate="";
                                                    $diff="";
                                                    $billsID= $data['billId'];
                                                    // echo $billsID."<br>";
                                                    $billsID=explode(',',$billsID);
                                                    
                                                    $chequeReceivedDate="";
                                                    if (trim($data['chequeReceivedDate']) == '' || substr($data['chequeReceivedDate'],0,10) == '0000-00-00') {
                                                        $chequeReceivedDate= '';
                                                    }else{
                                                        $dt=date_create($data['chequeReceivedDate']);
                                                        $chequeReceivedDate = date_format($dt,'d-M-Y');
                                                    }

                                                    $chequeDate="";
                                                    if (trim($data['chequeDate']) == '' || substr($data['chequeDate'],0,10) == '0000-00-00') {
                                                        $chequeDate= '';
                                                    }else{
                                                        $dt=date_create($data['chequeDate']);
                                                        $chequeDate = date_format($dt,'d-M-Y');
                                                    }
                                                    

                                                    $recdate=strtotime($chequeReceivedDate);
                                                    $chqdate=strtotime($chequeDate);
                                                    
                                                    foreach($billsID as $b){
                                                      
                                                      if($b>0){
                                                        $retailer=$this->CashAndChequeModel->getRetailerbyBills('bills',$b);
                                                        $rname=$rname.$retailer.',';
                                                        $billDate=$this->CashAndChequeModel->getDatebyBills('bills',$b);

                                                        if($recdate>=$chqdate){
                                                          // echo $chequeReceivedDate;
                                                            $diff=$recdate-strtotime($billDate);
                                                            $diff= abs(round($diff/86400));
                                                        }else{
                                                           // echo $chequeDate;
                                                            $diff=$chqdate-strtotime($billDate);
                                                            $diff= abs(round($diff/86400));
                                                        }

                                                      }
                                                    }
                                                    $rname= trim($rname,',');

                                                ?>
                                                <td><?php echo $no; ?></td>
                                                <td><?php  echo $chequeReceivedDate; ?></td>
                                                 <td>
                                                            <?php  
                                                                if($data['retailerName'] !=""){
                                                                    echo $data['retailerName']; 
                                                                }else{
                                                                    echo $rname; 
                                                                }
                                                            ?>
                                                        </td>
                                                <td><?php echo $data['chequeNo']; ?></td>
                                                <td><?php echo $chequeDate; ?> </td>
                                                <td align="right"><?php echo number_format($data['sumAmount']); ?></td>
                                                <td><?php echo strtoupper($data['chequeBank']); ?></td>
                                                <td>
                                                    <?php
                                                        $cmp=$data['compName']; 
                                                        echo trim($cmp,',');
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        $billNo=$data['billNo'];
                                                         echo rtrim($billNo,',');
                                                    ?>
                                                </td>  
                                                <td><?php
                                                    $dt=date_create($billDate);
                                                    $data['billDate'] = date_format($dt,'d-M-Y');
                                                    echo $data['billDate']; ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                        if($data['billId']==0){

                                                        }else{
                                                           echo $diff;
                                                        }
                                                    ?>
                                                </td>
                                                <td><?php echo $data['chequeStatus']; ?></td>
                                                
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
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Basic Examples --> 
    </div>
</section>

<?php $this->load->view('/layouts/footerDataTable'); ?>   