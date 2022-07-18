<?php $this->load->view('/layouts/commanHeader'); ?>

<script>
    $(document).ready(function() {
        $('.js-basic-example').DataTable( {
            dom: 'Bfrtip',
            buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print','email'
            ]
        } );
    } );
</script>

<script   src="https://code.jquery.com/jquery-1.12.1.js"   integrity="sha256-VuhDpmsr9xiKwvTIHfYWCIQ84US9WqZsLfR4P7qF6O8="   crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
                <h2>Bounced Cheque Register</h2>
            </div> -->
            <!-- Basic Examples -->
            <div class="row clearfix" id="page">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Bounced Cheque Register
                           </h2>
                           
                       </div>
                       <div class="body">
                          <div class="row">                                 
                            <div class="row m-t-20">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table style="font-size:12px" class="table table-bordered table-striped table-hover dataTable js-exportable" data-page-length='100'>
                                            <thead>
                                            <tr class="gray">
                                                <th> Return Cheque</th>
                                                <th> Bounce Date</th>
                                                <th> Retailer Name</th>
                                                <th> Bill No.</th>
                                                 <th> Bill Date</th>
                                                 <th> Cheque No.</th>
                                                 <th> Cheque Date</th>
                                                  <th> Cheque Amount</th>
                                                <th> Company</th>
                                                
                                                <th> Reason </th>
                                                <th class="text-right"> Penalty</th>
                                                
                                                <th class="text-right"> Pending Amount</th>   
                                                <th> Current Status </th>
                                            </tr>
                                          </thead>
                                            <tbody>
                                            <?php
                                                $no=0;
                                                foreach ($billpayments as $data1) 
                                                {

                                                  $billData=$this->CashAndChequeModel->getdataBounceForUniqueBounceCheque('billpayments',$data1['billId']);
                                                  if(!empty($billData)){
                                                    foreach($billData as $data){
                                                  $rname="";
                                                  $billDate="";

                                                  // $billInfo=$this->CashAndChequeModel->getChequeDetail('billpayments',$data['chequeNo'],$data['chequeDate'],$data['chequeBank']);
                                                  // $countPenalty= count($billInfo);
                                                  $id=$data['id'];
                                                  
                                                  $billInfo=$this->CashAndChequeModel->load('bills',$data['billId']);

                                                  $pendAmt=0;
                                                  if(!empty($billInfo)){
                                                    $pendAmt=$billInfo[0]['pendingAmt'];

                                                    $retailer=$billInfo[0]['retailerName'];
                                                    $billDate=$billInfo[0]['date'];
                                                    $rname=$rname.$retailer.',';
                                                  }  
                                                  $rname= trim($rname,',');

                                                  $start = strtotime(date('Y-m-d'));
                                                  $end = strtotime($billInfo[0]['date']);
                                                  $hiDateDiff = ceil(abs($end - $start) / 86400);

                                                  if($pendAmt>0){
                                                  
                                                      if($billInfo[0]['isAllocated']==1){
                                            ?>
                                                        <tr style="background-color: #dcd6d5">
                                            <?php 
                                                      }else if($hiDateDiff > $highlightValue){ ?>
                                                          <tr style="background-color: #c8e090">
                                            <?php     }else{ ?>
                                                          <tr>
                                            <?php     } 
                                                        
                                                      if($data['chequeStatus']=='Bounced'){
                                            ?>   
                                                    <td>
                                            <?php 
                                                          if($billInfo[0]['isAllocated']==0){ 
                                                          $id = $data['id'];
                                            ?>
                                                            <a href="<?php echo site_url('CashAndChequeController/updateStatusBounced/'.$id);?>">
                                                              <button class="btn btn-primary waves-effect btn-sm" data-type="basic">
                                                                <i class="material-icons">check</i>
                                                              </button></a>  
                                            <?php         } ?>
                                                    
                                                    </td>
                                            <?php
                                                    
                                                  }else{
                                            ?>
                                                    <td></td>
                                            <?php
                                                  }
                                            
                                                  
                                                  // $billsID= $data['billId'];
                                                  // $billsID=explode(',',$billsID);
                                                  // foreach($billsID as $b){
                                                  //   $bill_info=array();
                                                  //   if($b>0){
                                                  //     $retailer=$this->CashAndChequeModel->getRetailerbyBills('bills',$b);
                                                  //     $billDate=$this->CashAndChequeModel->getDatebyBills('bills',$b);
                                                  //     $rname=$rname.$retailer.',';
                                                  //   }
                                                      
                                                  // }
                                                  // $rname= trim($rname,',');
                                              ?>
                                                  <td>
                                                    <?php
                                                    $dt=date_create($data['chequeStatusDate']);
                                                    $data['chequeDate'] = date_format($dt,'d-M-Y');
                                                    echo $data['chequeDate']; ?>
                                                  </td> 
                                                        <td>
                                                        <?php
                                                           echo $data['retailerName'];
                                                        ?>
                                                          
                                                        </td>
                                                        <td>
                                                    <?php
                                                    echo $data['billNo'];
                                                    ?>
                                                  </td> 
                                                  <td><?php
                                                  $dt=date_create($billDate);
                                                  $data['billDate'] = date_format($dt,'d-M-Y');
                                                  echo $data['billDate']; ?>
                                                </td>
                                                    <td><?php echo $data['chequeNo']; ?></td>
                                                    <td><?php
                                                    $dt=date_create($data['chequeDate']);
                                                    $data['chequeDate'] = date_format($dt,'d-M-Y');
                                                    echo $data['chequeDate']; ?>
                                                  </td> 
                                                  <td align="right"><?php echo number_format($data['sumAmount']); ?></td>  
                                                  <td><?php echo $data['compName'];?></td>
                                                   
                                                <td><?php echo $data['statusBouncedReason'];?></td>
                                                <td class="text-right"><?php echo number_format($data['penaltyAmount']); ?></td>
                                              
                                                <?php 
                                                
                                                  // $pending=floatval($data['sumAmount'])+floatval($data['penaltyAmount']);
                                                  $pending=floatval($data['latestPendingAmt']);
                                                ?>
                                                <td class="text-right"><?php echo number_format($pending); ?></td>
                                                <td><?php
                                                  if($billInfo[0]['isAllocated']!=1){
                                                      echo $data['chequeStatus'];
                                                  }else{
                                                      $allocations=$this->CashAndChequeModel->getAllocationDetailsByBill('bills',$data['billId']);
                                                      $officeAllocations=$this->CashAndChequeModel->getOfficeAllocationDetailsByBill('bills',$data['billId']);
                                                        
                                                      if(!empty($allocations)){
                                                        echo "<p style='color:blue'>Allocated in : ".$allocations[0]['allocationCode']."</p>";
                                                      }else if(!empty($officeAllocations)){
                                                           echo "<p style='color:blue'>Allocated in : ".$officeAllocations[0]['allocationCode']."</p>";
                                                      }
                                                  }

                                                 ?>
                                            </td> 
                                              <?php 

                                                  
                                              ?>
                                              </tr>
                                               <?php
                                               $no++; 
                                                }
                                              }
                                             }
                                           }
                                           ?> 
                                       </tbody>
                                        <tfoot>
                                            <tr class="gray">
                                                <th> Return Cheque </th>
                                                <th> Bounce Date </th>
                                                <th> Retailer Name</th>
                                                <th> Bill No.</th>
                                                 <th> Bill Date</th>
                                                 <th> Cheque No.</th>
                                                 <th> Cheque Date</th>
                                                  <th> Cheque Amount</th>
                                                <th> Company</th>
                                                <th> Reason </th>
                                                <th class="text-right"> Penalty</th>
                                               
                                                <th class="text-right"> Pending Amount</th>   
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
        </div>
    </section>


    <div class="container">
  <div class="modal fade" id="clearBouncedCheque" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">Clear Bounced Cheque</h4>
          </div>
          <div class="modal-body">
         <form method="post" role="form" action="<?php echo site_url('CashAndChequeController/updateBouncedAndClearCheque'); ?>"> 
                        <div class="body">

                          <input type="hidden" id="chequeId" name="chequeId" list="ret" class="form-control date" value="">
                            <div class="demo-masked-input">
                                <div class="row clearfix">
                                  <div class="col-md-12">
                                    <div class="col-md-12">
                                      Are you sure? Wants to clear this cheque.
                                    </div>
                                  </div> 
                                  <div id="recStatus1"></div>
                                     <div class="col-md-12">
                                        <!-- <div class="row clearfix"> -->
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name">Save</span>
                                                </button>
                                               
                                                    <button data-dismiss="modal" type="button" class="btn btn-primary m-t-15 waves-effect">
                                                        <i class="material-icons">cancel</i> 
                                                        <span class="icon-name"> Cancel</span>
                                                    </button>
                                               
                                            <!-- </div> -->

                                        </div>
                                    </div>                            
                                </div>
                            </div>
                        </div>
                </form>
          </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('/layouts/footerDataTable'); ?>
<script type="text/javascript">
  function setId(id){
    document.getElementById('chequeId').value=id;
    // alert(id);
  }
</script>

    <script>
        function changeStatus(id)
        { 
            swal({
              title: 'Select Status',
              input: 'select',
              inputOptions: {
                'received':'Returned'
            },
            inputPlaceholder: 'Select Status',
            showCancelButton: true,
            inputValidator: function (value) {
              return new Promise(function (resolve, reject) {
                  if (value !== '') {
                    resolve();
                } else {
                    reject('You need to select a Status');
                }
            });
          }
      }).then(function (result) {
        if (result.value) {
            // swal({
            //   type: 'success',
            //   html: 'You selected: ' + result.value+" id "+id
            // });
            $.ajax({
                url: "<?php echo site_url('CashAndChequeController/updateStatusBounceCheques');?>",
                type: "post",
                data:{"id" : id , "chequeStatus" : result.value},
                success: function (response) {
                    $('#changeStatus').html(response);  
                }
            });
        }
    });
  }
</script>