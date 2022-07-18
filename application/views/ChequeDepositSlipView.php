<?php $this->load->view('/layouts/commanHeader'); ?>

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
<!-- <section class="content"> -->
      <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Cheque Deposit Slip</h2>
            </div>
              <!-- Basic Examples -->
            <div class="row clearfix" id="page">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Cheque Deposit Slip
                            </h2>
                        </div>
                        <div class="body">
                              <div class="row">                                  
                                <div class="row m-t-20">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered" id="tbl">
                                                <tr class="head">
                                                    <td colspan="13" style="background-color: whitesmoke;"><b> Cheque Deposit Slip</b></td>
                                                </tr>
                                                <tr class="gray">
                                                    <th>Sr.No</th>
                                                    <th>Bill No</th>
                                                    <th>Date</th>
                                                    <th>Paid Amount</th>
                                                    <th>Bill Amount</th>
                                                    <th>Balance Amount</th>
                                                    <th>Payment Mode</th>
                                                    <th>Cheque No</th>
                                                    <th>Cheque Bank</th>
                                                    <th>Cheque Date</th>
                                                    <th>Cheque Status </th>
                                                    <th>Cheque Status Date</th>
                                                    <th>Allocation Id</th>
                                                </tr>
                                                <tfoot>
                                                   <tr class="gray">
                                                    <th>Sr.No</th>
                                                    <th>Bill No</th>
                                                    <th>Date</th>
                                                    <th>Paid Amount</th>
                                                    <th>Bill Amount</th>
                                                    <th>Balance Amount</th>
                                                    <th>Payment Mode</th>
                                                    <th>Cheque No</th>
                                                    <th>Cheque Bank</th>
                                                    <th>Cheque Date</th>
                                                    <th>Cheque Status </th>
                                                    <th>Cheque Status Date</th>
                                                    <th>Allocation Id</th>
                                                </tr>
                                                </tfoot>
                                                <tbody id="result_data1">
                                                    <?php
                                                        $no=0;
                                                        foreach ($billpayments as $data) 
                                                          {
                                                            $id=$data['id'];
                                                           $no++; 
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $no; ?></td>
                                                        <td><?php echo rtrim($data['billId'],','); ?></td>
                                                        <td><?php
                                                            $dt=date_create($data['date']);
                                                            $data['date'] = date_format($dt,'d-M-Y');
                                                            echo $data['date']; ?>
                                                        </td>
                                                        <td><?php echo $data['paidAmount']; ?></td>
                                                        <td><?php echo $data['billAmount']; ?></td>
                                                        <td><?php echo $data['balanceAmount']; ?></td>
                                                        <td><?php echo $data['paymentMode']; ?></td>
                                                        <td><?php echo $data['chequeNo']; ?></td>
                                                        <td><?php echo $data['chequeBank']; ?></td>
                                                        <td><?php echo $data['chequeDate']; ?></td>
                                                        <td> 
                                                            <a id="changeStatus" >
                                                                <button onclick="changeStatus('<?php echo $id;?>')" class="btn btn-primary waves-effect" data-type="basic"><?php echo $data['chequeStatus']; ?>
                                                                </button>
                                                            </a>
                                                            <p id="result_data"></p>
                                                        </td>
                                                        <td><?php echo $data['chequeStatusDate']; ?></td>
                                                        <td><?php echo $data['allocationId']; ?></td>
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
            <!-- #END# Basic Examples --> 
        </div>
</section>
<?php $this->load->view('/layouts/footerDataTable'); ?>
<script>
function changeStatus(id)
{ 
    swal({
      title: 'Select Status',
      input: 'select',
      inputOptions: {
        'cleared': 'cleared',
        'bounced':'bounced'
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
                url: "<?php echo site_url('CashAndChequeController/updateStatus');?>",
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

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
      <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>

       <!-- Bootstrap Core Js -->
    <script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.js');?>"></script>

   <!-- Select Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/bootstrap-select/js/bootstrap-select.js');?>"></script>

     <!-- Slimscroll Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/jquery-slimscroll/jquery.slimscroll.js');?>"></script>

    <!-- Bootstrap Colorpicker Js -->
    <script src="<?php echo base_url('assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js');?>"></script>

    <!-- Dropzone Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/dropzone/dropzone.js');?>"></script>

    <!-- Input Mask Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/jquery-inputmask/jquery.inputmask.bundle.js');?>"></script>

    <!-- Multi Select Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/multi-select/js/jquery.multi-select.js');?>"></script>

    <!-- Jquery Spinner Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/jquery-spinner/js/jquery.spinner.js');?>"></script>

    <!-- Bootstrap Tags Input Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js');?>"></script>

    <!-- noUISlider Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/nouislider/nouislider.js');?>"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/node-waves/waves.js');?>"></script>

    <!-- Custom Js -->
    <script src="<?php echo base_url('assets/js/admin.js');?>"></script>
    <script src="<?php echo base_url('assets/js/pages/forms/advanced-form-elements.js');?>"></script>

    <!-- Demo Js -->
    <script src="<?php echo base_url('assets/js/demo.js');?>"></script>
    <!-- SweetAlert Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/sweetalert/sweetalert.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/pages/ui/dialogs.js');?>"></script>
    <!-- Bootstrap Notify Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/bootstrap-notify/bootstrap-notify.js');?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7"></script>

