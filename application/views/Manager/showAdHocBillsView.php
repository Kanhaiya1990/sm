<?php $this->load->view('/layouts/commanHeader'); ?>

<script src="<?php echo base_url('assets/js/pages/ui/tooltips-popovers.js');?>"></script>
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
</style>
<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
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
                            Non Allocated Ad Hoc Bills
                         </h2>
                         <!-- <p align="right">
                            <a href="<?php echo site_url('CashAndChequeController/NeftRegister');?>">
                              <button class="btn bg-primary margin"><i class="material-icons">refresh</i>    </button></a> 
                        </p> -->
                         
                     </div>
                     <div class="body">
                        
                      <div class="row">                                 
                        <div class="row m-t-20">
                            <div class="col-md-12">
                                   <table class="table table-bordered table-striped table-hover dataTable js-exportable" data-page-length='100'>
                                        <!-- <?php print_r($retailer); ?> -->
                                    <thead>
                                        <tr class="gray">
                                            <th> Sr No.</th>
                                             <th> Bill No </th>
                                            <th> Bill Date  </th>
                                            <th> Retailer </th>
                                             <th> Bill Amount </th>
                                             <th> SR </th>
                                             <th> Collection </th>
                                              <th> Pending  </th>
                                            <th> Employee </th>
                                            <th> Status </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <?php
                                            $no=0;
                                            foreach ($adhocBills as $data) 
                                            {
                                               $no++; 
                                              

                                              $allocations=$this->AllocationByManagerModel->getAllocationDetailsByBill('bills',$data['id']);

                                              $allocationsHistory=$this->AllocationByManagerModel->getAllocationDetailsByBillHistory('bills',$data['id']);

                                              $officeAllocations=$this->AllocationByManagerModel->getOfficeAllocationDetailsByBill('bills',$data['id']);

                                              $officeAllocationsHistory=$this->AllocationByManagerModel->getOfficeAllocationDetailsByBillHistory('bills',$data['id']);

                                              $dt=date_create($data['date']);
                                              $createdDate = date_format($dt,'d-M-Y');

                                             
                                            ?>

                                            <?php if($data['isAllocated']==1){ ?>
                                                 <tr style="background-color: #dcd6d5">
                                            <?php }else{ ?>
                                                 <tr>
                                            <?php } ?>
                                             
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $data['billNo']; ?></td>
                                                <td><?php echo $createdDate; ?></td>
                                                <td><?php echo $data['retailerName']; ?></td>
                                                <td><?php echo $data['netAmount']; ?></td>
                                                  <td><?php echo $data['SRAmt']; ?></td>
                                                  <td><?php echo $data['receivedAmt']; ?></td>
                                                <td><?php echo $data['pendingAmt']; ?></td>
                                                <td><?php echo $data['salesman'];; ?></td>
                                                <td>
                                                <?php 

                                                    if($data['isAllocated']==1){
                                                        if(!empty($allocations)){
                                                            echo "<span style='color:blue'>Allocated in : ".$allocations[0]['allocationCode'].'</span>';
                                                        }

                                                        if(!empty($officeAllocations)){
                                                            echo "<span style='color:blue'>Allocated in : ".$officeAllocations[0]['allocationCode'].'</span>';
                                                        }
                                                    }else{
                                                        if(!empty($allocationsHistory) || !empty($officeAllocationsHistory)){
                                                          if(!empty($allocationsHistory)){
                                                            echo "<span style='color:green'>Already Allocated in : ".$allocationsHistory[0]['allocationCode'].'</span>';
                                                          }

                                                          if(!empty($officeAllocationsHistory)){
                                                            echo "<span style='color:green'>Already Allocated in : ".$officeAllocationsHistory[0]['allocationCode'].'</span>';
                                                          }
                                                        }else{
                                                            echo "<span style='color:red'>Non Allocated</span>";
                                                        }
                                                ?>
                                                       
                                                    <?php } ?>
                                                </td>
                                              </tr>
                                                <?php
                                             
                                            }
                                            ?> 
                                    </tbody>
                                    <tfoot>
                                        <tr class="gray">
                                            <th> Sr No.</th>
                                             <th> Bill No </th>
                                            <th> Bill Date  </th>
                                            <th> Retailer </th>
                                              <th> Bill Amount </th>
                                             <th> SR </th>
                                             <th> Collection </th>
                                              <th> Pending  </th>
                                            <th> Employee </th>
                                        </tr>
                                    </tfoot>    
                            </table>
                        
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

  <div class="modal fade" id="updatelimitModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
            <center><h4 class="modal-title">Cheque List For Cheque Deposit Slip</h4></center>
          </div>
          <div class="modal-body">
            <div class="body">
                      <div class="row">                                 
                        <div class="row m-t-20">
                            <div class="col-md-12">
                                <table class="table table-bordered table-striped table-hover dataTable" data-page-length='100'>
                                        <thead>
                                        <tr class="gray">
                                            <th> Sr No.</th>
                                            <th>Party</th>
                                             <th> Cheque No </th>
                                            <th> Amount  </th>
                                            <th> Cheque Date </th>
                                        </tr>
                                      </thead>

                                      <tbody id="up_limitid">

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

   <div class="modal fade" id="officeAdjustmentModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
            <center><h4 class="modal-title">Select Transaction</h4></center>
          </div>
          <div class="modal-body">
            <div class="body">
                <div class="row">                                 
                    <div id="dataForId" class="row m-t-20">
                        
                    </div>
                </div>
             </div>
       
          </div>
      </div>
    </div>
  </div>
 
  <?php $this->load->view('/layouts/footerDataTable'); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->

 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script type="text/javascript">
     $(document).on('click','#limit_id',function(){
         var id=$(this).attr('data-id');
         $('#offcId').val(id);
         $.ajax({
            url : "<?php echo site_url('AdHocController/adHociBillByEmpAdjustmentForm');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
              // alert(data);die();
                $('#dataForId').html(data);
              
            }
        });
    });

</script>


