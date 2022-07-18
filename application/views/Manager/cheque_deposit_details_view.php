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

<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
<section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            
            <div class="row clearfix" id="page">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                      <div class="header">
                            <h2>
                            Cheque Deposit Slip :  <?php echo $title; ?>
                         </h2>
                      </div>
                     <div class="body">
                        
                      <div class="row">                                 
                        <div class="row m-t-20">
                            <div class="col-md-12">
                              <div class="table-responsive">
                                   <table id="#chk-reg-tbl" style="font-size: 12px" class="table table-bordered table-striped table-hover js-exportable dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th> Sr No.</th>
                                            <th>Party</th>
                                            <th> Cheque No </th>
                                            <th> Amount </th>
                                            <th>Cheque Bank</th>
                                            <th> Cheque Date </th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th> Sr No.</th>
                                            <th>Party</th>
                                             <th> Cheque No </th>
                                            <th> Amount  </th>
                                            <th>Cheque Bank</th>
                                            <th> Cheque Date </th>
                                        </tr>
                                    </tfoot>   
                                    <tbody>
                                      <?php
                                            $no=0;
                                                foreach ($chequeDetails as $data) 
                                                {
                                                   $no++; 
                                                    $rname="";
                                                    if($data['billId']>0){
                                                        $retailer=$this->CashAndChequeModel->getRetailerbyBills('bills',$data['billId']);
                                                        $rname=$rname.$retailer.',';
                                                    }
                                                    $rname= trim($rname,',');

                                                   $dt=date_create($data['chequeDate']);
                                                     $chequeDate = date_format($dt,'d-M-Y');

                                                ?>
                                                  <tr>
                                                    <td><?php echo $no; ?></td>
                                                    <td><?php if(!empty($rname)){ echo $rname; }else{ echo $data['retailerName']; } ?></td>
                                                    <td><?php echo $data['chequeNo']; ?></td>
                                                    
                                                    <td align="right"><?php echo number_format($data['paidAmount']); ?></td>
                                                    <td><?php echo $data['chequeBank']; ?></td>
                                                    <td><?php echo $chequeDate; ?></td>
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
           
    </section>

 <?php $this->load->view('/layouts/footerDataTable'); ?>