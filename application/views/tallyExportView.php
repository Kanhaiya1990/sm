<?php $this->load->view('/layouts/commanHeader'); ?>

<style type="text/css">
    hr.dotted {
      border-top: 3px dotted #bbb;
    }
</style>

<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/>
<section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                        Export Masters for Tally
                        </h2>
                    </div>
                    <div class="body">
                        <div class="row">
                            <form method="post" role="form" action="<?php echo site_url('TallyExportController/downloadMaster');?>">     
                                <div class="col-md-12">
                                    <div class="col-md-7">
                                        <b>Company </b><span style="color:red">*</span><br><br>

                                        <input type="checkbox" name="allcompName" value="allComp" class="compName form-check-input" id="example123456">
                                        <label class="form-check-label" for="example123456">All Companies</label>&nbsp;&nbsp;

                                        <?php foreach($company as $itm){ ?>
                                            <input type="checkbox" name="compName[]" value="<?php echo $itm['name']; ?>" class="compName form-check-input child" id="example<?php echo $itm['id']; ?>">
                                            <label class="form-check-label" for="example<?php echo $itm['id']; ?>"><?php echo $itm['name']; ?></label>&nbsp;&nbsp;
                                        <?php } ?>

                                        <input type="checkbox" name="compName[]" value="deliveryslip" class="compName form-check-input child" id="example1234">
                                        <label class="form-check-label" for="example1234">Delivery Slip</label>&nbsp;&nbsp;
                                    </div>
                                   
                                    <div class="col-md-3">
                                        <input type="submit" value="Export Master File" class="btn btn-primary btn-sm m-t-35 waves-effect"></input>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="header">
                        <h2>
                        Export Invoices for Tally
                        </h2>
                    </div>
                    <div class="body">
                        <div class="row">
                            <form method="post" role="form" action="<?php echo site_url('TallyExportController/downloadInvoice');?>">     
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <b>Company </b><span style="color:red">*</span><br><br>

                                        <input type="checkbox" name="invallcompName" value="allComp" class="invcompName form-check-input" id="invexample123456">
                                        <label class="form-check-label" for="invexample123456">All Companies</label>&nbsp;&nbsp;

                                        <?php foreach($company as $itm){ ?>
                                            <input type="checkbox" name="compName[]" value="<?php echo $itm['name']; ?>" class="invcompName form-check-input invchild" id="invexample<?php echo $itm['id']; ?>">
                                            <label class="form-check-label" for="invexample<?php echo $itm['id']; ?>"><?php echo $itm['name']; ?></label>&nbsp;&nbsp;
                                        <?php } ?>

                                        <input type="checkbox" name="compName[]" value="deliveryslip" class="invcompName form-check-input invchild" id="invexample1234">
                                        <label class="form-check-label" for="invexample1234">Delivery Slip</label>&nbsp;&nbsp;
                                    </div>

                                    <div class="col-md-2">
                                        <b>From Date </b><span style="color:red">*</span><br><br>
                                        <input autocomplete="off" required type="date" id="invfromDate" name="invfromDate" class="form-control date" value="">
                                    </div>

                                    <div class="col-md-2">
                                        <b>To Date </b><span style="color:red">*</span><br><br>
                                        <input autocomplete="off" required type="date" id="invtoDate" name="invtoDate" class="form-control date" value="">
                                    </div>
                                   
                                    <div class="col-md-2">
                                        <input type="submit" value="Invoice Export File" class="btn btn-primary btn-sm m-t-40 waves-effect"></input>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="header">
                        <h2>
                        Export Transaction Details for Tally
                        </h2>
                    </div>
                    <div class="body">
                        <div class="row">
                            <form method="post" role="form" action="<?php echo site_url('TallyExportController/submitTally');?>"> 
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <b>Company </b><span style="color:red">*</span><br><br>

                                            <input type="checkbox" name="allcompData" value="allComp" class="form-check-input" id="example1234567">
                                            <label class="form-check-label" for="example1234567">All Companies</label>&nbsp;&nbsp;

                                            <?php foreach($company as $itm){ ?>
                                                <input type="checkbox" name="compName[]" value="<?php echo $itm['name']; ?>" class="form-check-input child1" id="example1<?php echo $itm['id']; ?>">
                                                <label class="form-check-label" for="example1<?php echo $itm['id']; ?>"><?php echo $itm['name']; ?></label>&nbsp;&nbsp;
                                            <?php } ?>

                                            <input type="checkbox" name="compName[]" value="deliveryslip" class="form-check-input child1" id="example12341">
                                            <label class="form-check-label" for="example12341">Delivery Slip</label>&nbsp;&nbsp;
                                        </div>
                                        <div class="col-md-3">
                                            <b>From Date </b><span style="color:red">*</span><br><br>
                                            <input autocomplete="off" required type="date" id="fromDate" name="fromDate" class="form-control date" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <b>To Date </b><span style="color:red">*</span><br><br>
                                            <input autocomplete="off" required type="date" id="toDate" name="toDate" class="form-control date" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        
                                        <div class="col-md-3">
                                        <b>Select Options </b><span style="color:red">*</span><br><br>
                                        <br>
                                            <input type="checkbox" name="all" value="all" class="form-check-input" id="exampleCheck1">
                                            <label class="form-check-label" for="exampleCheck1">All</label>&nbsp;&nbsp;
                                                <br>
                                            <!-- <input type="checkbox" name="invoice" value="invoice" class="form-check-input child3" id="exampleCheck2">
                                            <label class="form-check-label" for="exampleCheck2">Invoices</label>&nbsp;&nbsp;
                                            <br> -->
                                            <input type="checkbox" name="cash_receipt" value="cash_receipt" class="form-check-input child3" id="exampleCheck3">
                                            <label class="form-check-label" for="exampleCheck3">Cash Receipt</label>&nbsp;&nbsp;
                                           
                                        </div>
                                        
                                        <div class="col-md-3">
                                        <br><br><br>
                                            <input type="checkbox" name="cash_discount" value="cash_discount" class="form-check-input child3" id="exampleCheck7">
                                            <label class="form-check-label" for="exampleCheck7">Cash Discount</label>&nbsp;&nbsp;
                                            <br>
                                            <input type="checkbox" name="neft_receipt" value="neft_receipt" class="form-check-input child3" id="exampleCheck5">
                                            <label class="form-check-label" for="exampleCheck5">NEFT Receipt</label>&nbsp;&nbsp;
                                            <br>
                                            <input type="checkbox" name="sales_return" value="sales_return" class="form-check-input child3" id="exampleCheck6">
                                            <label class="form-check-label" for="exampleCheck6">Sales Return</label>&nbsp;&nbsp;
                                        </div>
                                        <div class="col-md-3">
                                        <br><br><br>
                                        <input type="checkbox" name="cheque_receipt" value="cheque_receipt" class="form-check-input child3" id="exampleCheck4">
                                            <label class="form-check-label" for="exampleCheck4">Cheque Receipt</label>&nbsp;&nbsp;
                                            
                                            <br>
                                            <input type="checkbox" name="cheque_discount" value="cheque_bounce" class="form-check-input child3" id="exampleCheck8">
                                            <label class="form-check-label" for="exampleCheck8">Cheque Bounce</label>&nbsp;&nbsp;
                                            <br>
                                            <input type="checkbox" name="cheque_bounce_penalty" value="cheque_bounce_penalty" class="form-check-input child3" id="exampleCheck9">
                                            <label class="form-check-label" for="exampleCheck9">Cheque Bounce Penalty</label>&nbsp;&nbsp;
                                        </div>
                                        <div class="col-md-3">
                                        <br><br><br>
                                            <input type="checkbox" name="office_adjustment" value="office_adjustment" class="form-check-input child3" id="exampleCheck10">
                                            <label class="form-check-label" for="exampleCheck10">Office Adjustment</label>&nbsp;&nbsp;
                                            <br>
                                            <input type="checkbox" name="other_adjustment" value="other_adjustment" class="form-check-input child3" id="exampleCheck11">
                                            <label class="form-check-label" for="exampleCheck11">Other Adjustment</label>&nbsp;&nbsp;
                                            <br>
                                            <input type="checkbox" name="debit_to_employee" value="debit_to_employee" class="form-check-input child3" id="exampleCheck12">
                                            <label class="form-check-label" for="exampleCheck12">Debit To Employee</label>&nbsp;&nbsp;
                                        </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <input type="submit" value="Export Files" class="btn btn-primary btn-sm m-t-45 waves-effect"></input>
                                    </div>
                                </div> 
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $this->load->view('/layouts/footerDataTable'); ?>

<script>
    $(".compName").change(function(){
        var text = $("#cmpText").val();
        $(".compName:checked").each(function(){ 
            if ($(this).prop("checked"))
                text += $(this).val();
        });
        $("#cmpText").val(text+',');
    });
</script>

<script type = "text/javascript" >  
    window.history.pushState(null, "", window.location.href);
    window.onpopstate = function () {
        window.history.pushState(null, "", window.location.href);
    };
</script> 
<script>
$(document).ready(function() {
    $("#example123456").click(function() {
        $(".child").prop("checked", this.checked);
    });

    $('.child').click(function() {
        if ($('.child:checked').length == $('.child').length) {
            $('#example123456').prop('checked', true);
        } else {
            $('#example123456').prop('checked', false);
        }
    });
});

$(document).ready(function() {
    $("#invexample123456").click(function() {
        $(".invchild").prop("checked", this.checked);
    });

    $('.invchild').click(function() {
        if ($('.invchild:checked').length == $('.invchild').length) {
            $('#invexample123456').prop('checked', true);
        } else {
            $('#invexample123456').prop('checked', false);
        }
    });
});

$(document).ready(function() {
    $("#example1234567").click(function() {
        $(".child1").prop("checked", this.checked);
    });

    $('.child1').click(function() {
        if ($('.child1:checked').length == $('.child1').length) {
            $('#example1234567').prop('checked', true);
        } else {
            $('#example1234567').prop('checked', false);
        }
    });
});





$(document).ready(function() {
    $("#exampleCheck1").click(function() {
        $(".child3").prop("checked", this.checked);
    });

    $('.child3').click(function() {
        if ($('.child3:checked').length == $('.child3').length) {
            $('#exampleCheck1').prop('checked', true);
        } else {
            $('#exampleCheck1').prop('checked', false);
        }
    });
});
</script>

