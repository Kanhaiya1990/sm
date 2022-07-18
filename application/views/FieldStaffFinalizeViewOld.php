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
<!--<section class="content">-->
<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
           <!--  <div class="block-header">
                <h2>Deliveryman Hisaab Master</h2>
            </div> -->
              <!-- Basic Examples -->
            <div class="row clearfix" id="page">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Deliveryman Hisaab
                            </h2>
                          
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a data-action="collapse" aria-controls="page"><i class="ft-minus"></i>-</a></li>
                                        <li><a data-action="expand"  aria-controls="page"><i class="ft-maximize"><-0-></i></a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                              <div class="row">
                               
                              
                                <div class="col-md-12">

                                    <!-- CURRENT SUPPLY-->
                                    <div class="col-md-6 table-responsive">
                                         <?php echo validation_errors(); ?>
                                        <?php echo form_open_multipart('AllocationByManagerController/getCurrentBills') ?>
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-xs-center" colspan="4"><center>Total Amount</center></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-xs-right">
                                                        <label>Total Bill Allocated :</label>
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <label>10000000</label>
                                                    </td>
                                                
                                                    <td class="text-xs-right">
                                                        <label>Credits :</label>
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <label>10000000</label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-xs-right">
                                                        <label>Cash :</label>
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <label>10000000</label>
                                                    </td>
                                                
                                                    <td class="text-xs-right">
                                                        <label>Cheque :</label>
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <label>10000000</label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-xs-right">
                                                        <label>No of Cheques :</label>
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <label>10000000</label>
                                                    </td>
                                                
                                                    <td class="text-xs-right">
                                                        <label>SR/FSR :</label>
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <label>10000000</label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-xs-right">
                                                        <label>Resend :</label>
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <label>10000000</label>
                                                    </td>
                                                
                                                    <td class="text-xs-right">
                                                        <label>Cash as per Accounts :</label>
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <label>10000000</label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-xs-right">
                                                        <label>Cash as per Notes:</label>
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <label>10000000</label>
                                                    </td>
                                               
                                                    <td class="text-xs-right">
                                                        <label>Other Expenses :</label>
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <label>10000000</label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-xs-right">
                                                        <label>Difference :</label>
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <label>10000000</label>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                         <?php echo form_close(); ?>
                                    </div>
                                    <!-- PAST BILLS-->
                                    <div class="col-md-6 table-responsive">
                                        <div class="col-sm-7">
                                        <?php echo validation_errors(); ?>
                                        <?php echo form_open_multipart('AllocationByManagerController/getPastBills') ?>
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-xs-center" colspan="3"><center>Calculations</center></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>2000 &nbsp;&nbsp;&nbsp;&nbsp;X</td>
                                                        <td>
                                                        <input onkeyup="calMoney()" type="text" name="add2000" id="add2000"autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <span id="ad2000"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>1000 &nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                                        <td>
                                                        <input onkeyup="calMoney()" type="text" name="add1000" id="add1000"autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <span id="ad1000"></span>
                                                    </td>
                                                </tr>

                                                 <tr>
                                                    <td>500 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                                        <td>
                                                        <input onkeyup="calMoney()" type="text" name="add500" id="add500"autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <span id="ad500"></span>
                                                    </td>
                                                </tr>
                                                
                                               <tr>
                                                    <td>200 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                                        <td>
                                                        <input onkeyup="calMoney()" type="text" name="add200" id="add200"autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <span id="ad200"></span>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>100 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                                        <td>
                                                        <input onkeyup="calMoney()" type="text" name="add100" id="add100"autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <span id="ad100"></span>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>50 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                                        <td>
                                                        <input onkeyup="calMoney()" type="text" name="add50" id="add50"autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <span id="ad50"></span>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>20 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                                        <td>
                                                        <input onkeyup="calMoney()" type="text" name="add20" id="add20"autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <span id="ad20"></span>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>10 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X </td>
                                                        <td>
                                                        <input onkeyup="calMoney()" type="text" name="add10" id="add10"autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <span id="ad10"></span>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>Coins &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                        <td>
                                                        <input onkeyup="calMoney()" type="text" name="coin" id="coin" autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <span id="coins"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Total &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                        
                                                    <td colspan="2" class="text-xs-right">
                                                        <span id="calTotal"></span>
                                                    </td>
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                         <?php echo form_close(); ?>
                                     </div>
                                     <div class="col-sm-5">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-xs-center" colspan="3"><center>Expences</center></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Parking</td>
                                                        <td>
                                                        <input onkeyup="calc()" type="text" name="park" id="park" autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    
                                                </tr>
                                                <tr>
                                                    <td>CNG </td>
                                                        <td>
                                                        <input id="cng" onkeyup="calc()" type="text" name="cng" id="cng" autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                   
                                                </tr>

                                                 <tr>
                                                    <td>Challan </td>
                                                        <td>
                                                        <input onkeyup="calc()" type="text" name="challan" id="challan" autocomplete="off" class="form-control">
                                                        
                                                    </td>
                                                    
                                                </tr>
                                                
                                               <tr>
                                                    <td>Total : </td>
                                                        
                                                    <td class="text-xs-right">
                                                        <span id="total"></span>
                                                        <!-- <input type="text" name="total" id="total" autocomplete="off" class="form-control" readonly=""> -->
                                                    </td>
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                     </div>
                                    </div>
                                   
                                </div>
                                            <center><a href="<?php echo site_url('');?>">
                                                <button type="button" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name"> Finalize </span>
                                                </button>
                                            </a> </center> 
                     
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Examples --> 
        </div>
</section>
<?php $this->load->view('/layouts/footerDataTable'); ?>
<script type="text/javascript">
  function calc() {
    var park = document.getElementById('park').value;
    var challan =document.getElementById('challan').value;
    var cng = document.getElementById('cng').value;

    if(park == ""){
        park = 0;
    }
    if(challan == ""){
        challan = 0;
    }
    if(cng == ""){
        cng = 0;
    }
          
    var cal=0;
    var cal =cal+ parseInt(park)+parseInt(challan)+parseInt(cng);
    document.getElementById('total').innerHTML = cal;
  }
</script>

<script type="text/javascript">
  function calMoney() {
    var a2000 = document.getElementById('add2000').value;
    var a1000 = document.getElementById('add1000').value;
    var a500 = document.getElementById('add500').value;
    var a200 = document.getElementById('add200').value;
    var a100 = document.getElementById('add100').value;
    var a50 = document.getElementById('add50').value;
    var a20 = document.getElementById('add20').value;
    var a10 = document.getElementById('add10').value;
    var coin = document.getElementById('coin').value;

    if(a2000 ==""){
        a2000=0;
    }
    if(a1000 ==""){
        a1000=0;
    }
    if(a500 ==""){
        a500=0;
    }
    if(a200 ==""){
        a200=0;
    }
    if(a100 ==""){
        a100=0;
    }
    if(a50 ==""){
        a50=0;
    }
    if(a20 ==""){
        a20=0;
    }
    if(a10 ==""){
        a10=0;
    }
    if(coin ==""){
        coin=0;
    }

    var c1=0;
    c1=2000*a2000;
    var c2=0;
    c2=1000*a1000;
    var c3=0;
    c3=500*a500;
    var c4=0;
    c4=200*a200;
    var c5=0;
    c5=100*a100;
    var c6=0;
    c6=50*a50;
    var c7=0;
    c7=20*a20;
    var c8=0;
    c8=10*a10;
    var c9=0;
    c9=coin;

    document.getElementById('ad2000').innerHTML = c1;
    document.getElementById('ad1000').innerHTML = c2;
    document.getElementById('ad500').innerHTML = c3;
    document.getElementById('ad200').innerHTML = c4;
    document.getElementById('ad100').innerHTML = c5;
    document.getElementById('ad50').innerHTML = c6;
    document.getElementById('ad20').innerHTML = c7;
    document.getElementById('ad10').innerHTML = c8;
    document.getElementById('coins').innerHTML = c9;
    var total=0;
    total=total+c1+c2+c3+c4+c5+c6+c7+c8;
    total=parseFloat(total)+parseFloat(c9);
    document.getElementById('calTotal').innerHTML = total;


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

