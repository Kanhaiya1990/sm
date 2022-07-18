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
</style>
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2> Signed Bills </h2>
            </div>
              <!-- Basic Examples -->
            <div class="row clearfix" id="page">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Signed Bills 
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
                                <div class="col-md-2">
                                    <div class="card" style="color: red" > 
                                        <h1 style="text-align: center;padding: 10px;">
                                             Bills
                                        </h1> 
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    Allocation : <label>060717-1</label><br />
                                    Reference:<label> Gobal Market</label>
                                </div>
                                <div class="col-sm-2">
                                     <table class="table table-striped table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td><b>Employee </b></td>
                                                <tr>
                                                    <td>xyz</td>
                                                </tr>
                                                <tr>
                                                    <td>abc</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                </div><br />
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td><b>Current Supply Bills </b></td>
                                                    <td><b>Past Bills</b></td>
                                                    <td><b>Bounced cheque</b></td>
                                                    <td><b>Delivery challans</b></td>
                                                    <td><b>Display bills</b></td>
                                                    <td><b>Resend bills</b></td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>3</td>
                                                    <td>3</td>
                                                    <td>2</td>
                                                    <td>3</td>
                                                    <td>3</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <tr class="head">
                                                    <td colspan="10"><label>Current Supply Bills</label></td>
                                                    <td colspan="2" class="text-xs-right">
                                                        <a href="#" id="confirm" onclick="confirm()" class="btn btn-primary btn-sm">Confirm</a>
                                                    </td>
                                                </tr>
                                                <tr class="gray">
                                                    <th>S No.</th>
                                                    <th>Bill No.</th>
                                                    <th>Bill Date</th>
                                                    <th>Retailer Name</th>
                                                    <th>Bill Amount</th>
                                                    <th>Sale Return</th>
                                                    <th>Past Collection</th>
                                                    <th>Pending Amount</th>
                                                    <th>Status</th>
                                                    <th>Today's Collection</th>
                                                    <th colspan="2">Select All <br />
                                                        Received / Not Received
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>NS1812345324</td>
                                                    <td>2-Aug-17</td>
                                                    <td>Baba Sales</td>
                                                    <td>10,345</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>10,345</td>
                                                    <td> 
                                                        <a id="changeStatus" onclick="changeStatus()" href='#'>
                                                            <button class="btn btn-primary waves-effect" data-type="basic">Change Status
                                                            </button>
                                                        </a>
                                                    </td>
                                                    <td>-</td>
                                                    <td >
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" id="remember_me" class="filled-in" checked="">
                                                                <label for="remember_me"></label>
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="odd">
                                                    <td>2</td>
                                                    <td>NS1812345324</td>
                                                    <td>22-Aug-17</td>
                                                    <td>Krishna Sales</td>
                                                    <td>2,345</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>2,345</td>
                                                     <td> 
                                                        <a id="changeStatus" onclick="changeStatus()" href='#'>
                                                            <button class="btn btn-primary waves-effect" data-type="basic">Change Status
                                                            </button>
                                                        </a>
                                                    </td>
                                                    <td>-</td>
                                                   <td >
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" id="remember_me1" class="filled-in" checked="">
                                                                <label for="remember_me1"></label>
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <br />
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                            <!--Past Bills-->
                                                <tr class="head">
                                                    <td colspan="11"><label>Past Bills</label></td>
                                                    <td colspan="2" class="text-xs-right">
                                                        <a href="#" id="confirm" onclick="confirm()" class="btn btn-primary btn-sm">Confirm</a>
                                                    </td>
                                                </tr>

                                                <tr class="gray">
                                                    <th>S No.</th>
                                                    <th>Bill No.</th>
                                                    <th>Bill Date</th>
                                                    <th>Retailer Name</th>
                                                    <th>Bill Amount</th>
                                                    <th>Sale Return</th>
                                                    <th>Past Collection</th>
                                                    <th>Pending Amount</th>
                                                    <th>Status</th>
                                                    <th>Today's Collection</th>
                                                    <th>No. of times sent</th>
                                                    <th>Select All</th>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>NS1812345324</td>
                                                    <td>2-Aug-17</td>
                                                    <td>Baba Sales</td>
                                                    <td>10,345</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>10,345</td>
                                                     <td> 
                                                        <a id="changeStatus" onclick="changeStatus()" href='#'>
                                                            <button class="btn btn-primary waves-effect" data-type="basic">Change Status
                                                            </button>
                                                        </a>
                                                    </td>
                                                    <td>-</td>
                                                    <td>2</td>
                                                     <td >
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" id="remember_me2" class="filled-in" checked="">
                                                                <label for="remember_me2"></label>
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <br />
                                        <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <!--Bounced Cheques -->
                                            <tr class="head">
                                                <td colspan="11"><label>Bounced Cheques</label></td>
                                                <td colspan="2" class="text-xs-right">
                                                     <a href="#" id="confirm" onclick="confirm()" class="btn btn-primary btn-sm">Confirm</a>
                                                </td>
                                            </tr>
                                            <tr class="gray">
                                                <th>S No.</th>
                                                <th>Cheque No.</th>
                                                <th>Cheque Date</th>
                                                <th>Retailer Name</th>
                                                <th>Principal Amount</th>
                                                <th>Penalty</th>
                                                <th>Past Collection</th>
                                                <th>Pending Amount</th>
                                                <th>Status</th>
                                                <th>Today's Collection</th>
                                                <th>No. of times sent</th>
                                                <th>Select All </th>
                                            </tr>

                                            <tr>
                                                <td>1</td>
                                                <td>123456</td>
                                                <td>2-Jul-17</td>
                                                <td>Baba Sales</td>
                                                <td>12,345</td>
                                                <td>250</td>
                                                <td>1123</td>
                                                <td>11,472</td>
                                                <td> 
                                                    <a id="changeStatus" onclick="changeStatus()" href='#'>
                                                        <button class="btn btn-primary waves-effect" data-type="basic">Change Status
                                                        </button>
                                                    </a>
                                                </td>
                                                <td>-</td>
                                                <td>2</td>
                                               <td>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" id="remember_me3" class="filled-in" checked="">
                                                            <label for="remember_me3"></label>
                                                        </label>
                                                    </div>
                                                </td>

                                            </tr>
                                            <tr class="odd">
                                                <td>2</td>
                                                <td>3344567</td>
                                                <td>22-Aug-17</td>
                                                <td>Krishna Sales</td>
                                                <td>2,345</td>
                                                <td>100</td>
                                                <td>1000</td>
                                                <td>1,445</td>
                                                <td> 
                                                    <a id="changeStatus" onclick="changeStatus()" href='#'>
                                                        <button class="btn btn-primary waves-effect" data-type="basic">Change Status
                                                        </button>
                                                    </a>
                                                </td>
                                                <td>-</td>
                                                <td>1</td>
                                                <td>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" id="remember_me4" class="filled-in" checked="">
                                                            <label for="remember_me4"></label>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>

                                        </table>
                                        </div>
                                        <br />
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                            <!--Past Bills-->
                                                <tr class="head">
                                                    <td colspan="11"><label>Delivery Challan</label></td>
                                                    <td colspan="2" class="text-xs-right">
                                                        <a href="#" id="confirm" onclick="confirm()" class="btn btn-primary btn-sm">Confirm</a>
                                                    </td>
                                                </tr>

                                                <tr class="gray">
                                                    <th>S No.</th>
                                                    <th>Bill No.</th>
                                                    <th>Bill Date</th>
                                                    <th>Retailer Name</th>
                                                    <th>Bill Amount</th>
                                                    <th>Sale Return</th>
                                                    <th>Past Collection</th>
                                                    <th>Pending Amount</th>
                                                    <th>Status</th>
                                                    <th>Today's Collection</th>
                                                    <th>No. of times sent</th>
                                                    <th>Select All</th>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>NS1812345324</td>
                                                    <td>2-Aug-17</td>
                                                    <td>Baba Sales</td>
                                                    <td>10,345</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>10,345</td>
                                                     <td> 
                                                        <a id="changeStatus" onclick="changeStatus()" href='#'>
                                                            <button class="btn btn-primary waves-effect" data-type="basic">Change Status
                                                            </button>
                                                        </a>
                                                    </td>
                                                    <td>-</td>
                                                    <td>2</td>
                                                     <td >
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" id="remember_me5" class="filled-in" checked="">
                                                                <label for="remember_me5"></label>
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <br />
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                            <!--Unsanctioned Credit-->
                                                <tr class="head">
                                                    <td colspan="11"><label>Unsanctioned Credit</label></td>
                                                    <td colspan="2" class="text-xs-right">
                                                         <a href="#" id="confirm" onclick="confirm()" class="btn btn-primary btn-sm">Confirm</a>
                                                    </td>
                                                </tr>
                                                <tr class="gray">
                                                    <th>S No.</th>
                                                    <th>Bill No.</th>
                                                    <th>Bill Date</th>
                                                    <th>Retailer Name</th>
                                                    <th>Bill Amount</th>
                                                    <th>Sale Return</th>
                                                    <th>Past Collection</th>
                                                    <th>Pending Amount</th>
                                                    <th>Today's Collection</th>
                                                    <th>List</th>
                                                    <th>Penalty</th>
                                                    <th>Select All <br />
                                                         Debit /Skip
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>SL0123</td>
                                                    <td>2-Aug-17</td>
                                                    <td>Baba Sales</td>
                                                    <td>10,000</td>
                                                    <td>-</td>
                                                    <td>2000</td>
                                                    <td>8,000</td>
                                                    <td >-</td>
                                                    <td>Cash only</td>
                                                    <td>
                                                        <input id="Text1" type="text" style="width:80px" class="form-control" />
                                                    </td>
                                                    <td>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" id="remember_me6" class="filled-in" checked="">
                                                                <label for="remember_me6"></label>
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="odd">
                                                    <td>2</td>
                                                    <td>NS1812345324</td>
                                                    <td>22-Aug-17</td>
                                                    <td>Krishna Sales</td>
                                                    <td>5,000</td>
                                                    <td>500</td>
                                                    <td>1000</td>
                                                    <td>4,500</td>
                                                    <td >-</td>
                                                    <td>Cash only</td>
                                                    <td><input id="Text2" type="text" style="width:60px" class="form-control"/></td>
                                                    <td>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" id="remember_me7" class="filled-in" checked="">
                                                                <label for="remember_me7"></label>
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <br />
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                            <!--Display Bills-->
                                                <tr class="head">
                                                    <td colspan="11"><label>Display Bills</label></td>
                                                    <td colspan="2" class="text-xs-right">
                                                        <a href="#" id="confirm" onclick="confirm()" class="btn btn-primary btn-sm">Confirm</a>
                                                    </td>
                                                </tr>
                                                <tr class="gray">
                                                    <th>S No.</th>
                                                    <th>Bill No.</th>
                                                    <th>Bill Date</th>
                                                    <th>Retailer Name</th>
                                                    <th>Bill Amount</th>
                                                    <th>Sale Return</th>
                                                    <th>Past Collection</th>
                                                    <th>Pending Amount</th>
                                                    <th>Today's Collection</th>
                                                    <th>Penalty</th>
                                                    <th>Select All</th>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>SL0123</td>
                                                    <td>2-Aug-17</td>
                                                    <td>Baba Sales</td>
                                                    <td>10,000</td>
                                                    <td>-</td>
                                                    <td>2000</td>
                                                    <td>8,000</td>
                                                    <td colspan="2">-</td>
                                                    <td><input id="Text4" type="text" style="width:60px" class="form-control" /></td>
                                                    <td>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" id="remember_me8" class="filled-in" checked="">
                                                                <label for="remember_me8"></label>
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <br />
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <!--FSR Bills-->
                                                <tr class="head">
                                                    <td colspan="11"><label>FSR Bills</label></td>
                                                    <td colspan="2" class="text-xs-right">
                                                        <a href="#" id="confirm" onclick="confirm()" class="btn btn-primary btn-sm">Confirm</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>S No.</th>
                                                    <th>Bill No.</th>
                                                    <th>Bill Date</th>
                                                    <th>Retailer Name</th>
                                                    <th>Bill Amount</th>
                                                    <th>Sale Return</th>
                                                    <th>Past Collection</th>
                                                    <th>Pending Amount</th>
                                                    <th>Today's Collection</th>
                                                    <th>Reason</th>
                                                    <th>Penalty</th>
                                                    <th> Select All</th>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>SL0123</td>
                                                    <td>2-Aug-17</td>
                                                    <td>Baba Sales</td>
                                                    <td>10,000</td>
                                                    <td>-</td>
                                                    <td>2000</td>
                                                    <td>8,000</td>
                                                    <td >-</td>
                                                    <td><input  class="form-control" id="Text9" type="text" style="width:80px" value="Shop Close"/></td>
                                                    <td><input class="form-control"  id="Text6" type="text" style="width:60px"/></td>
                                                    <td>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" id="remember_me8" class="filled-in" checked="">
                                                                <label for="remember_me8"></label>
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                <td>2</td>
                                                <td>SL0124</td>
                                                <td>22-Aug-17</td>
                                                <td>Krishna Sales</td>
                                                <td>5,000</td>
                                                <td>500</td>
                                                <td>1000</td>
                                                <td>4,500</td>
                                                <td >-</td>
                                                <td><input  class="form-control" id="Text9" type="text" style="width:80px" value="Shop Close"/></td>
                                                <td><input class="form-control"  id="Text6" type="text" style="width:60px"/></td>
                                               <td>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" id="remember_me9" class="filled-in" checked="">
                                                            <label for="remember_me9"></label>
                                                        </label>
                                                    </div>
                                                </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <br />
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                            <!--Resend Bills-->
                                                <tr class="head">
                                                <td colspan="11"><label>Resend Bills</label></td>
                                                </tr>
                                                <tr class="gray">
                                                <th>S No.</th>
                                                <th>Bill No.</th>
                                                <th>Bill Date</th>
                                                <th>Retailer Name</th>
                                                <th>Bill Amount</th>
                                                <th>Sale Return</th>
                                                <th>Past Collection</th>
                                                <th>Pending Amount</th>
                                                <th>Today's Collection</th>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>SL0123</td>
                                                    <td>2-Aug-17</td>
                                                    <td>Baba Sales</td>
                                                    <td>10,000</td>
                                                    <td>-</td>
                                                    <td>2000</td>
                                                    <td>8,000</td>
                                                    <td>-</td>
                                                </tr>
                                                <tr class="odd">
                                                    <td>2</td>
                                                    <td>SL0124</td>
                                                    <td>22-Aug-17</td>
                                                    <td>Krishna Sales</td>
                                                    <td>5,000</td>
                                                    <td>500</td>
                                                    <td>1000</td>
                                                    <td>4,500</td>
                                                    <td>-</td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>SL0134</td>
                                                    <td>12-Aug-17</td>
                                                    <td>Sai Sales</td>
                                                    <td>15,345</td>
                                                    <td>-</td>
                                                    <td>5,000</td>
                                                    <td>10,345</td>
                                                    <td>-</td>
                                                </tr>
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
function deleted()
{ 
 swal({
  title: "Are you sure?",
  text: "You will not be able to recover this imaginary file!",
  type: "warning",
  showCancelButton: true,
  confirmButtonClass: "btn-danger",
  confirmButtonText: "Yes!",
  cancelButtonText: "No !",
  closeOnConfirm: false,
  closeOnCancel: false
},
function(isConfirm) {
  if (isConfirm) {
    swal("Deleted!", "Your imaginary file has been deleted.", "success");
  } else {
    swal("Cancelled", "Your imaginary file is safe", "error");
  }
});
}
</script>
</section>
<script>
function changeStatus()
{ 
 swal({
  title: "An input!",
  text: "Write something interesting:",
  type: "input",
  showCancelButton: true,
  closeOnConfirm: false,
  inputPlaceholder: "Write something"
}, function (inputValue) {
  if (inputValue === false) return false;
  if (inputValue === "") {
    swal.showInputError("You need to write something!");
    return false
  }
  swal("Nice!", "You wrote: " + inputValue, "success");
});
}
function confirm(){
    swal({
  title: "Are you sure?",
  text: "You will not be able to recover this imaginary file!",
  type: "warning",
  showCancelButton: true,
  confirmButtonClass: "btn-danger",
  confirmButtonText: "Yes, Reset it!",
  cancelButtonText: "No, cancel!",
  closeOnConfirm: false,
  closeOnCancel: false
},
function(isConfirm) {
  if (isConfirm) {
    swal("Deleted!", "Your imaginary file has been Reset.", "success");
  } else {
    swal("Cancelled", "Your imaginary file is safe!", "error");
  }
});
}
</script>
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
