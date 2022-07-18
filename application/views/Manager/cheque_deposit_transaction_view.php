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
                            Old Cheque Deposit Slips
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
                                             <th> Cheque Deposit Transaction No </th>
                                             <th>Total Amount</th>
                                            <th> No. Of Cheques  </th>
                                            <th>Company</th>
                                            <th>Cheque Deposited Till</th>
                                            <th> Download File</th>
                                        </tr>
                                      </thead>
                                        <tbody>
                                            <?php
                                            $no=0;
                                            foreach ($transactions as $data) 
                                            {
                                              $no++; 

                                              $ddate="";
                                              if($data['chequeTillDate']!==""){
                                                $dt=date_create($data['chequeTillDate']);
                                                $ddate = date_format($dt,'d-M-Y');
                                              }
                                              

                                            ?>
                                              <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><a href="<?php echo site_url('CashAndChequeController/getChequesFromTransations/'.$data['transactionId']); ?>"><?php echo $data['transactionId']; ?></a></td>
                                                <td align="right"><?php echo number_format($data['totalChequeSum']); ?></td>
                                                <td><?php echo $data['chequeCount']; ?></td>
                                                
                                                <td><?php echo $data['company']; ?></td>
                                                <td><?php echo $ddate; ?></td>
                                                <td>

                                                  <?php
                                                    if(!empty($data['filePath'])){
                                                  ?>
                                                  <a target="_blank" href="<?php echo base_url().'assets/deliveryslips/'.$data['filePath']; ?>" class="btn btn-xs  btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download PDF"><i class="material-icons">download</i></a>
 
                                                  <?php
                                                    }
                                                  ?>
                                                </td>
                                               <!--  <td>
                                                  <a id="limit_id" href="javascript:void();" data-toggle="modal" data-id="<?php echo $data['transactionId']; ?>" data-target="#updatelimitModal">
                                                    <i class="material-icons" style="color: blue;">visibility</i>
                                                </a> 
                                                </td> -->
                                              </tr>
                                                <?php
                                            }
                                            ?> 
                                        </tbody>
                                    <tfoot>
                                           <tr class="gray">
                                            <th> Sr No.</th>
                                             <th> Cheque Deposit Transaction No </th>
                                             <th>Total Amount</th>
                                            <th> No. Of Cheques  </th>
                                             <th>Company</th>
                                            <th>Cheque Deposited Till</th>
                                            <th> Download File</th>
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
 <?php $this->load->view('/layouts/footerDataTable'); ?>
  
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->

 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script type="text/javascript">
    $(document).on('click','#limit_id',function(){
         var id=$(this).attr('data-id');

         $.ajax({
            url : "<?php echo site_url('CashAndChequeController/getChequesFromTransations');?>",
            method : "POST",
            data : {id: id},
            success: function(data){
              // alert(data);die();
                $('#up_limitid').html(data);
            }
        });
    });

</script>

