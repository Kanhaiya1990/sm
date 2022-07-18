<?php $this->load->view('/layouts/commanHeader'); ?>

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
            <div class="block-header">
                <h2>New Cheque  </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix" id="page">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                             New Cheque 
                         </h2>
                         
                     </div>
                     <div id="infoMessage"><?php echo $message;?></div>
                     <div class="body">
                      <div class="row">                                 
                        <div class="row m-t-20">
                            <div class="col-md-9">
                                <div class="table-responsive">
                                  <!--   <table class="table table-striped table-bordered dataTable" id="tbl"> -->
                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100' id="tbl">
                                        <thead>
                                            <tr class="head">
                                                <td colspan="13" style="background-color: whitesmoke;"><b> New Cheque   </b></td>
                                            </tr>
                                            <tr class="gray">
                                                <th> S No.</th>
                                                <th style="display: none"> ID</th>
                                                <th style="display: none"> Bill Amount</th>
                                                <th> Bill No.</th>
                                                <th> Date</th>
                                                <th> Retailer Name</th>
                                                <th> BillType</th>
                                                <th> Net Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no=0;
                                            foreach ($bills as $data) 
                                            {
                                              $id=$data['id'];  
                                              $no++; 
                                              ?>
                                              <tr>
                                                 <td><?php echo $no; ?></td>
                                                 <td style="display: none"><?php echo $data['id']; ?></td>
                                                 <td style="display: none"><?php echo $data['netAmount']; ?></td>
                                                 <td><?php echo $data['billNo']; ?></td>
                                                 <td><?php
                                                 $dt=date_create($data['date']);
                                                 $data['date'] = date_format($dt,'d-m-Y');
                                                 echo $data['date']; ?></td>
                                                 <td><?php echo $data['Name']; ?></td>
                                                 <td><?php echo $data['billType']; ?></td>
                                                 <td><?php echo $data['netAmount']; ?></td>
                                                 
                                                   <!--  <td> 
                                                        <a id="changeStatus" >
                                                            <button onclick="changeStatus('<?php echo $id;?>')" class="btn btn-primary waves-effect" data-type="basic"><?php echo $data['chequeStatus']; ?>
                                                            </button>
                                                        </a>
                                                       <p id="result_data"></p>
                                                   </td> -->
                                               </tr>
                                               <?php
                                           }
                                           ?> 
                                       </tbody>
                                   </table>
                               </div>
                           </div>
                           <div class="col-md-3 table-responsive">
                               <?php echo validation_errors(); ?>
                               <?php echo form_open('cashier/CashAndChequeController/insert') ?>
                               <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-xs-center" colspan="4"> New Entry</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-xs-right">
                                            <label>Bill No :</label>
                                            <input type="text" name="billno[]" id="billno" class="form-control" required>
                                            <input type="hidden" name="billId[]" id="id" class="form-control">
                                            <input type="hidden" name="billAmount" id="billAmount" class="form-control">

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-xs-right">
                                            <label>Cheque No :</label>
                                            <input type="text" name="chequeNo" placeholder="Enter Cheque No." class="form-control" required>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-xs-right">
                                            <label>Cheque Date :</label>
                                            <input type="date" name="chequeDate" id="theDate" class="form-control" required>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-xs-right">
                                            <label>Bank Name  :</label>
                                            <input type="text" name="chequeBank" placeholder="Enter Bank Name" class="form-control" required>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-xs-right">
                                            <label>Cheque Amount :</label>
                                            <input type="text" name="paidAmount" placeholder="Enter Cheque Amount" class="form-control" required onblur="this.value = 'Php ' + formatNumber(this.value)">

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-xs-right">
                                            <label>Company :</label>
                                            <input type="text" name="company" placeholder="Enter Company Name" class="form-control" required>

                                        </td>
                                    </tr>                                           
                                    <tr>  
                                    </tr>
                                    <tr>
                                        <td class="text-xs-right">
                                            <button type="submite" id="insert-more" class="btn btn-success margin btn-sm"> Add </button>  
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php echo form_close(); ?>
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
            'bank': 'bank'
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
                url: "<?php echo site_url('cashier/CashAndChequeController/updateStatusDesktopBill');?>",
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
<script>
    (function () {
        if (window.addEventListener) {
            window.addEventListener('load', run, false);
        } else if (window.attachEvent) {
            window.attachEvent('onload', run);
        }

        function run() {
            var t = document.getElementById('tbl');
            t.onclick = function (event) {
            event = event || window.event; //IE8
            var target = event.target || event.srcElement;
            while (target && target.nodeName != 'TR') { 
                target = target.parentElement;
            }            
            var cells = target.cells; //cell collection - https://developer.mozilla.org/en-US/docs/Web/API/HTMLTableRowElement
            if (!cells.length || target.parentNode.nodeName == 'THEAD') {
                return;
            }
            var total=0;

            var f = document.getElementById('id');
            f.value =f.value+ cells[1].innerHTML+ ",";
            var f1 = document.getElementById('billno');
            f1.value =f1.value+ cells[3].innerHTML+ ",";
            var f2 = document.getElementById('billAmount');
            f2.value =f2.value+ cells[2].innerHTML+ ",";
        };
    }

})();
</script>
<script>
    var date = new Date();
    var day = date.getDate();
    var month = date.getMonth() + 1;
    var year = date.getFullYear();

    if (month < 10) month = "0" + month;
    if (day < 10) day = "0" + day;
    var today = year + "-" + month + "-" + day;
    document.getElementById('theDate').value = today;
</script>

<!-- <script   src="https://code.jquery.com/jquery-1.12.1.js"   integrity="sha256-VuhDpmsr9xiKwvTIHfYWCIQ84US9WqZsLfR4P7qF6O8="   crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>  -->
<!--  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script> -->

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
   <!--  <?php
    include('layouts/footerDataTable.php');
    ?> -->
