<?php $this->load->view('/layouts/commanHeader'); ?>

<script   src="https://code.jquery.com/jquery-1.12.1.js" integrity="sha256-VuhDpmsr9xiKwvTIHfYWCIQ84US9WqZsLfR4P7qF6O8="   crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
      $.fn.dataTable.ext.errMode = 'none';
        $('.js-basic-example').DataTable( {
            dom: 'Bfrtip',
            // align:center,
            buttons: [
            'excel', 'pdf'
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

.MayBeLongColumn {
    word-wrap: break-word !important;
}


</style>



<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
<section  class="col-md-12 box" style="height: auto;overflow-y: scroll;">
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
                             NEFT Register
                         </h2>
                         <p align="right">
                            <a href="<?php echo site_url('CashAndChequeController/NeftRegister');?>">
                              <button class="btn bg-primary margin"><i class="material-icons">refresh</i>    </button></a> 
                        </p>
                         
                     </div>
                     <div class="body">
                        <form method="post" role="form" action="<?php echo site_url('CashAndChequeController/NeftRegister');?>">
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
                            
                                   <table style="font-size: 11px" class="table table-bordered table-striped table-hover dataTable js-exportable" data-page-length='100'>
                                        <!-- <?php print_r($retailer); ?> -->
                                        <thead>
                                        <tr class="gray">
                                          <th> S.No.</th>
                                            <th> Receipt Date</th>
                                             <th> Retailer Name </th>
                                            <th> NEFT No.  </th>
                                            <th> NEFT Date  </th>
                                            <th> NEFT Amount</th>
                                            <th> Company</th> 
                                            <th class="MayBeLongColumn"> Bill No</th>
                                             <th> Bill Date</th>
                                             <th>NEFT Days</th>
                                            <th> Current Status </th>
                                        </tr>
                                      </thead>
                                        <tbody>
                                            <?php
                                            $no=0;
                                            foreach ($billpayments as $data) 
                                            {
                                               $no++; 

                                                $dt=date_create($data['neftDate']);
                                                $neftDate = date_format($dt,'d-M-Y');
                                                $recdate=strtotime($neftDate);

                                                $dt=date_create($data['chequeReceivedDate']);
                                                $neftReceivedDate = date_format($dt,'d-M-Y');

                                                // $dt=date_create($data['neftDate']);
                                                // $neftDate = date_format($dt,'d-m-Y');

                                                // $recdate=strtotime($neftReceivedDate);
                                                // $chqdate=strtotime($neftDate);



                                               ?>
                                               <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $neftReceivedDate; ?></td>
                                                <?php
                                                    $rname="";
                                                    $billDate="";
                                                    $billsID= $data['billId'];
                                                    // echo $billsID."<br>";
                                                    $billsID=explode(',',$billsID);
                                                    // print_r($billsID);
                                                    foreach($billsID as $b){
                                                        $retailer=$this->CashAndChequeModel->getRetailerbyBills('bills',$b);
                                                        $billDate=$this->CashAndChequeModel->getDatebyBills('bills',$b);
                                                        $rname=$rname.$retailer.',';

                                                        $diff=$recdate-strtotime($billDate);
                                                        $diff= abs(round($diff/86400));

                                                        // if($recdate>=$chqdate){
                                                        //     $diff=$recdate-strtotime($billDate);
                                                        //     $diff= abs(round($diff/86400));
                                                        // }else{
                                                        //     $diff=$chqdate-strtotime($billDate);
                                                        //     $diff= abs(round($diff/86400));
                                                        // }
                                                    }
                                                    $rname= trim($rname,',');
                                                ?>
                                                 <td>
                                                            <?php  
                                                                if($data['retailerName'] !=""){
                                                                    $retailerName =$data['retailerName'];
                                                                    $retailerName=trim($retailerName,', ');
                                                                    $retailerName=(explode(",",$retailerName));
                                                                    $retailerName=(array_unique($retailerName));
                                                                    $retailerName=(implode(", ",$retailerName));
                                                                    echo $retailerName; 
                                                                }else{
                                                                    echo $rname; 
                                                                }
                                                            ?>
                                                        </td>
                                                <!--<td><?php echo substr($data['neftNo'], 0, 40); ?></td>-->
                                                <td><?php echo $data['neftNo']; ?></td>
                                                <td><?php echo $neftDate; ?> </td>
                                                <td align="right"><?php echo number_format($data['sumAmount']); ?></td>
                                                <td>
                                                  <?php
                                                      $cmp=$data['compName']; 
                                                      echo trim($cmp,',');
                                                  ?>
                                                </td>
                                                <td class="MayBeLongColumn">
                                                    <?php
                                                    $billNo=$data['billNo'];
                                                    // echo $billNo;
                                                     // echo rtrim($billNo,',');
                                                     $billNo=$data['billNo'];
                                                    $billNo=trim($billNo,', ');
                                                    $billNo=(explode(",",$billNo));
                                                    $billNo=(array_unique($billNo));
                                                    $billNo=(implode(", ",$billNo));
                                                    echo $billNo;
                                                    ?>
                                                </td>  
                                                <td><?php
                                                    $dt=date_create($billDate);
                                                    $data['billDate'] = date_format($dt,'d-M-Y');
                                                    echo $data['billDate']; ?>
                                                </td>
                                                <td><?php 
                                                      echo $diff;
                                                    ?></td>
                                                <td><?php
                                                if($data['isLostStatus']==0){
                                                    echo 'Pending'; 
                                                }
                                                 if($data['isLostStatus']==1){
                                                    echo 'Not Received'; 
                                                }
                                                 if($data['isLostStatus']==2 && $data['chequeStatus']=="New"){
                                                    echo 'Pending for Approval'; 
                                                }
                                                 if($data['isLostStatus']==2 && $data['chequeStatus']=="Received"){
                                                    echo 'Received'; 
                                                }
                                                 if($data['isLostStatus']==2 && $data['chequeStatus']=="Not Received"){
                                                    echo 'Rejected'; 
                                                }
                                                 
                                                 ?>
                                               </td>
                                                
                                                </tr>
                                                <?php
                                            }
                                            ?> 
                                        </tbody>
                                    <tfoot>
                                      <tr>
                                          <th> S.No.</th>
                                          <th> Receipt Date</th>
                                          <th> Retailer Name </th>
                                          <th> NEFT No.  </th>
                                          <th> NEFT Date  </th>
                                          <th> NEFT Amount</th>
                                          <th> Company</th> 
                                          <th class="MayBeLongColumn"> Bill No</th>
                                          <th> Bill Date</th>
                                          <th>NEFT Days</th>
                                          <th> Current Status </th>
                                      </tr>
                                    </tfoot>    
                            </table>
                        </div>
                    </div>
                </div>
                                   <!--  <div class="col-md-5"></div>
                                    <div class="col-md-4">
                                         <button type="button" id="insert-ins" class="btn btn-success margin btn-sm"> Insert </button>
                                    </div>
                                    <div class="col-md-3"></div> -->
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Examples --> 
        <!-- </div> -->
    </section>
   
<?php $this->load->view('/layouts/footerDataTable'); ?>