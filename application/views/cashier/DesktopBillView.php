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
                <h2>Cheque Destop Slip </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix" id="page">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Cheque Destop Slip 
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row">                                 
                                <div class="row m-t-20">                                   
                                        <div class="table-responsive">
                                            <div class="col-md-12">
                                                <form method="post" role="form" action="<?php echo site_url('cashier/CashAndChequeController/DesktopBill');?>"> 
                                                <div class="col-md-4">
                                                    <b> Date:</b>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="material-icons">perm_contact_calendar</i>
                                                        </span>
                                                        <div class="form-line">
                                                            <input type="date" name="dt" class="form-control date" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <b>Company Name:</b>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                           <i class="material-icons">perm_contact_calendar</i>
                                                        </span>
                                                       <div class="form-line">
                                                            <select class="form-control" name="name" required>
                                                                <?php foreach ($company as $req_item): ?>
                                                                    <option value="<?php echo $req_item['id'] ?>"><?php echo $req_item['name'] ?>                                                            
                                                                </option>
                                                            <?php endforeach ?> 
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class="col-md-4">
                                                    <button type="submit" class="btn btn-primary btn-lg"> <i class="material-icons">call_made</i> Go </button> 
                                                </div> 
                                             </form>
                                             <br /><br /><br /><br /><br >
                                             &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                          <!--   </div>
                                        </div> -->
                                   
                                    <!-- <div class="table-responsive">
                                        <div class="col-md-12"> -->
                                            <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                                <thead>
                                                    <tr>
                                                        <td>Sr.No</td>
                                                        <th>Retailer </th>
                                                        <th>Cheque No</th>
                                                        <th>Cheque Date</th>
                                                        <th>Bank</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <td>Sr.No</td>
                                                        <th>Retailer </th>
                                                        <th>Cheque No</th>
                                                        <th>Cheque Date</th>
                                                        <th>Bank</th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    <?php
                                                    $no=0;
                                                    foreach ($bills as $data) 
                                                    {
                                                       $no++; 
                                                       ?>
                                                       <tr>
                                                        <td><?php echo $no; ?></td>
                                                        <td><?php echo $data['Name']; ?></td>
                                                        <td><?php echo $data['chequeNo']; ?></td>
                                                        <!-- <td><?php echo $data['chequeDate']; ?></td> -->
                                                        <td><?php
                                                        $dt=date_create($data['chequeDate']);
                                                        $data['chequeDate'] = date_format($dt,'d-m-Y');
                                                        echo $data['chequeDate']; ?></td>
                                                        <td><?php echo $data['chequeBank']; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?> 
                                                </tbody>
                                            </table>
                                            <center>
                                                <a href="<?php echo site_url('cashier/CashAndChequeController/EmailSend');?>">
                                                    <button type="button" class="btn btn-danger btn-lg"><i class="material-icons">email</i>  Email </button> 
                                                </a> 
                                                <a href="<?php echo site_url('cashier/CashAndChequeController/DesktopBillUpdate');?>">
                                                    <button type="button" class="btn btn-danger btn-lg"><i class="material-icons">save</i>  Save </button>  
                                                </a> 
                                            </center>
                                        </div>
                                    </div><!--end -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
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
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
<!-- <?php
include('layouts/footerDataTable.php');
?> 
 -->
