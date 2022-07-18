<?php $this->load->view('/layouts/commanHeader'); ?>

<script   src="<?php echo base_url('assets/js/kp_js/jquery-1.12.1.js'); ?>"   integrity="sha256-VuhDpmsr9xiKwvTIHfYWCIQ84US9WqZsLfR4P7qF6O8="   crossorigin="anonymous"></script>
<script src="<?php echo base_url('assets/js/kp_js/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/pages/ui/tooltips-popovers.js');?>"></script> 
<style type="text/css">
    @media screen and (min-width: 600px) {
        .modal-dialog {
          width: 600px; /* New width for default modal */
      }
      .modal-sm {
          width: 600px; /* New width for small modal */
      }
  }

  @media screen and (min-width: 600px) {
    .modal-lg {
      width: 600px; /* New width for large modal */
  }
}

.logo_prov {
    border-radius: 30px;
    border: 1px solid black;
    background: red;
    color: black;
    padding: 6px;
    width: 50px;
    height: 50px;
}
</style>
<style>

  .btn:hover, .btn:focus {
    font-weight: bolder;
    outline: 10px solid blanchedalmond;
    border-style: hidden;
    box-shadow: 0 0 2px 2px red;
  color: black;
  }
</style>

<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/>
<section class="col-md-12 box">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <p align="right">
                            <!-- <button data-toggle="modal" data-target="#myModal1" class="modalLink btn btn-primary btn-xs m-t-15 waves-effect">
                                <span class="icon-name"> <i class="material-icons">playlist_add</i><span>Transfer to Main Cash Book </span></span>
                            </button> -->
                            <?php if($this->session->userdata['workRestrict']['status']=="yes"){?>
                                <button class="btn btn-primary btn-xs m-t-15 waves-effect" onclick="planUpgradeMsg()">
                                    <span class="icon-name"> <i class="material-icons">playlist_add</i><span>Transfer to Day Book</span></span>
                                </button>

                                <button class="btn btn-primary btn-xs m-t-15 waves-effect" onclick="planUpgradeMsg()">
                                    <span class="icon-name"> <i class="material-icons">playlist_add</i><span>Bank Deposit </span></span>
                                </button>
                            <?php } else { ?>
                                <button data-toggle="modal" data-target="#myModal2" class="modalLink btn btn-primary btn-xs m-t-15 waves-effect">
                                    <span class="icon-name"> <i class="material-icons">playlist_add</i><span>Transfer to Day Book</span></span>
                                </button>

                                <button data-toggle="modal" data-target="#myModal3" class="modalLink btn btn-primary btn-xs m-t-15 waves-effect">
                                    <span class="icon-name"> <i class="material-icons">playlist_add</i><span>Bank Deposit </span></span>
                                </button>
                            <?php } ?>
                            
                        </p> <br>
                       
                    </div>
            <div class="body">
                <div class="row">
                    <div class="col-lg-4">
                    <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-basic-example" data-page-length='100'>
                            <thead>
                                <tr style="background-color: #dfdddd;">
                                    <th>Denomination</th>
                                    <th><span class="pull-right">Available Notes</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-right">2000</td>
                                    <td class="text-right"><?php 
                                        echo number_format(($income_notes_contra['note2000']-$expense_notes_contra['note2000'])); 
                                    ?></td>
                                </tr>
                                <tr>
                                    <td class="text-right">500</td>
                                    <td class="text-right"><?php 
                                        echo number_format(($income_notes_contra['note500']-$expense_notes_contra['note500'])); 
                                    ?></td>
                                </tr>
                                <tr>
                                    <td class="text-right">200</td>
                                    <td class="text-right"><?php 
                                        echo number_format(($income_notes_contra['note200']-$expense_notes_contra['note200'])); 
                                    ?></td>
                                </tr>
                                <tr>
                                    <td class="text-right">100</td>
                                    <td class="text-right"><?php 
                                        echo number_format(($income_notes_contra['note100']-$expense_notes_contra['note100'])); 
                                    ?></td>
                                </tr>
                                <tr>
                                    <td class="text-right">50</td>
                                    <td class="text-right"><?php 
                                        echo number_format(($income_notes_contra['note50']-$expense_notes_contra['note50'])); 
                                    ?></td>
                                </tr>
                                <tr>
                                    <td class="text-right">20</td>
                                    <td class="text-right"><?php 
                                        echo number_format(($income_notes_contra['note20']-$expense_notes_contra['note20'])); 
                                    ?></td>
                                </tr>
                                <tr>
                                    <td class="text-right">10</td>
                                    <td class="text-right"><?php 
                                        echo number_format(($income_notes_contra['note10']-$expense_notes_contra['note10'])); 
                                    ?></td>
                                </tr>
                                <tr>
                                    <td class="text-right">Coins</td>
                                    <td class="text-right"><?php 
                                        echo number_format(($income_notes_contra['coins']-$expense_notes_contra['coins'])); 
                                    ?></td>
                                </tr>
                                <tr>
                                    <th class="text-right">Total</th>
                                    <th class="text-right"><?php 
                                        echo number_format($diffContraEntry); 
                                    ?></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-lg-8">
                        <div class="table-responsive">
                            <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                <thead>
                                    <tr>
                                        <th>Last 10 transaction</th>
                                        <th class="text-right" colspan="6"><a href="<?php echo site_url('owner/MainCashBookController/mainCashBookDetails'); ?>">View All</a></th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Nature</th>
                                        <th>Employee</th>
                                        <th>Reference</th>
                                        <th class="text-right">Inflow</th>
                                        <th class="text-right">Outflow</th>
                                        <th class="text-right">Balance</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Date</th>
                                        <th>Nature</th>
                                        <th>Employee</th>
                                        <th>Reference</th>
                                        <th class="text-right">Inflow</th>
                                        <th class="text-right">Outflow</th>
                                        <th class="text-right">Balance</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php 
                                        $total=abs($diffContraEntry);
                                        if(!empty($mainCashBook)){ 
                                            foreach($mainCashBook as $data){
                                    ?>
                                          <tr>
                                            <td><?php echo date("d-M-Y h:i a", strtotime($data['date'])); ?></td>
                                            <td><?php echo $data['nature']?></td>
                                            <td><?php echo $data['empName']?></td>
                                            <td><?php echo $data['narration']?></td>
                                            <?php if($data['inoutStatus']=="Inflow"){ ?>
                                                <td class="text-right" style="color:blue"><?php echo number_format($data['amount']); ?></td>
                                                <td></td>
                                            <?php }else{ 
                                                    if($data['nature']=="Bank Deposit"){
                                            ?>
                                                 <td></td>
                                                <td class="text-right" style="color:green"><?php echo number_format($data['amount']); ?></td>
                                            <?php 
                                                }else{

                                                
                                            ?>
                                                <td></td>
                                                <td class="text-right" style="color:red"><?php echo number_format($data['amount']); ?></td>
                                            <?php }
                                            }
                                            ?>
                                            <td class="text-right"><?php echo number_format($data['balance']); ?></td>

                                        </tr>
                                    <?php
                                        }
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
</section>



<div class="container">
    <div class="modal fade" id="myModal1" role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title text-center">Transfer to Main Cash Book</h3>
                </div>
                <div class="modal-body">
                    <form method="post" role="form" onsubmit="return checkMainCashBookAmount(this);" action="<?php echo site_url('owner/MainCashBookController/insertMainCashBookEntry');?>"> 
                        <div class="row clearfix">
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-basic-example" data-page-length='100'>
                                        <thead>
                                            <tr style="background-color: #dfdddd;">
                                                <th>Denomination</th>
                                                <th><span class="pull-right">Available Notes</span></th>
                                                <th><span class="pull-right"> Transfer to Main Cash Book</span></th>
                                                <th><span class="pull-right">Calculated Value</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-right">2000</td>
                                                <td class="text-right"><?php 
                                                    $val2000=(($daybook_notes['note2000']+($income_notes['note2000']-$expense_notes['note2000']))-$bank_deposit_notes['note2000']);

                                                    echo number_format(($daybook_notes['note2000']+($income_notes['note2000']-$expense_notes['note2000']))-$bank_deposit_notes['note2000']); 
                                                ?>
                                                <td><input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" onblur="this.value = minmax(this.value, 0,<?php echo $val2000; ?>)" onkeyup="calMoney();" type="text" name="add2000" id="add2000" autocomplete="off" class="form-control"></td>
                                                <td class="text-right" id="t2000"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">500</td>

                                                <td class="text-right"><?php 
                                                    $val500=(($daybook_notes['note500']+($income_notes['note500']-$expense_notes['note500']))-$bank_deposit_notes['note500']);
                                                echo number_format(($daybook_notes['note500']+($income_notes['note500']-$expense_notes['note500']))-$bank_deposit_notes['note500']); 
                                                ?>

                                                <td>
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" onblur="this.value = minmax(this.value, 0,<?php echo $val500; ?>)" onkeyup="calMoney();" type="text" name="add500" id="add500" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="t500"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">200</td>
                                                <td class="text-right"><?php 
                                                $val200=(($daybook_notes['note200']+($income_notes['note200']-$expense_notes['note200']))-$bank_deposit_notes['note200']);

                                                echo number_format(($daybook_notes['note200']+($income_notes['note200']-$expense_notes['note200']))-$bank_deposit_notes['note200']); 
                                                ?>

                                                <td>
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" onblur="this.value = minmax(this.value, 0,<?php echo $val200; ?>)" onkeyup="calMoney();" type="text" name="add200" id="add200" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="t200"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">100</td>
                                                <td class="text-right"><?php
                                                $val100=(($daybook_notes['note100']+($income_notes['note100']-$expense_notes['note100']))-$bank_deposit_notes['note100']);
                                                echo number_format(($daybook_notes['note100']+($income_notes['note100']-$expense_notes['note100']))-$bank_deposit_notes['note100']); 
                                                ?>

                                                <td>
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" onblur="this.value = minmax(this.value, 0,<?php echo $val100; ?>)" onkeyup="calMoney();;" type="text" name="add100" id="add100" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="t100"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">50</td>
                                                <td class="text-right"><?php 
                                                $val50=(($daybook_notes['note50']+($income_notes['note50']-$expense_notes['note50']))-$bank_deposit_notes['note50']);

                                                echo number_format(($daybook_notes['note50']+($income_notes['note50']-$expense_notes['note50']))-$bank_deposit_notes['note50']); 
                                                ?>

                                                <td>
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" onblur="this.value = minmax(this.value, 0,<?php echo $val50; ?>)" onkeyup="calMoney();" type="text" name="add50" id="add50" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="t50"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">20</td>
                                                <td class="text-right"><?php 
                                                $val20=(($daybook_notes['note20']+($income_notes['note20'])-$expense_notes['note20'])-$bank_deposit_notes['note20']);
                                                echo number_format(($daybook_notes['note20']+($income_notes['note20'])-$expense_notes['note20'])-$bank_deposit_notes['note20']); 
                                                ?>

                                                <td>
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" onblur="this.value = minmax(this.value, 0,<?php echo $val20; ?>)" onkeyup="calMoney();" type="text" name="add20" id="add20" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="t20"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">10</td>
                                                <td class="text-right">
                                                <?php 
                                                $val10=(($daybook_notes['note10']+($income_notes['note10']-$expense_notes['note10']))-$bank_deposit_notes['note10']);
                                                echo number_format(($daybook_notes['note10']+($income_notes['note10']-$expense_notes['note10']))-$bank_deposit_notes['note10']); 
                                                ?>

                                                <td>
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" onblur="this.value = minmax(this.value, 0,<?php echo $val10; ?>)" onkeyup="calMoney();" type="text" name="add10" id="add10" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="t10"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">coins</td>
                                            <td class="text-right"><?php 
                                                $coins=(($daybook_notes['coins']+($income_notes['coins']-$expense_notes['coins']))-$bank_deposit_notes['coins']);
                                                echo number_format(($daybook_notes['coins']+($income_notes['coins']-$expense_notes['coins']))-$bank_deposit_notes['coins']);
                                                ?>

                                                <td>
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" onblur="this.value = minmax(this.value, 0,<?php echo $coins; ?>)" onkeyup="calMoney();" type="text" name="coin" id="coin" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="tcoins"></td>
                                            </tr>
                                            <tr style="background-color: #dfdddd;">
                                                <td class="text-right"><b>Total</b></td>
                                                <td class="text-right"><b>
                                                <?php 
                                                echo number_format(($daybook_notes['collectedAmt']+($income_notes['collectedAmt']-$expense_notes['collectedAmt']))-$bank_deposit_notes['collectedAmt']); 
                                                ?></b></td>
                                                <td class="text-xs-right">
                                                    <span id="bancalTotal"></span>
                                                    <input type="hidden" value="<?php echo (($closingBal+$totalInflow)-($totalOutflow+$totalBankDeposit)); ?>" name="bankDep_amt" id="totalOpeningAmt" autocomplete="off" class="form-control">
                                                    <input type="hidden" name="collectedDepositTotal" id="collectedDepositTotal" autocomplete="off" class="form-control">
                                                </td>
                                                <td class="text-right"><span id="tcalTotal"></span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <center>                                               
                                        <button id="transferToMainCashBookButton" type="submit" class="btn btn-primary m-t-15 waves-effect">
                                            <i class="material-icons">save</i> 
                                            <span class="icon-name">
                                                Save
                                            </span>
                                        </button> 

                                        <button data-dismiss="modal" type="button" id="bnkClose" class="btn btn-primary m-t-15 waves-effect">
                                            <i class="material-icons">cancel</i> 
                                            <span class="icon-name">
                                                cancel
                                            </span>
                                        </button>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="modal fade" id="myModal2" role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title text-center">Transfer to Day Book</h3>
                </div>
                <div class="modal-body">
                    <form method="post" onsubmit="return checkDayBookAmount(this);" role="form" action="<?php echo site_url('owner/MainCashBookController/insertDayBookEntry');?>"> 
                        <div class="row clearfix">
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <p id="err-data" style="color:red"></p>
                                    <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-basic-example" data-page-length='100'>
                                        <thead>
                                            <tr style="background-color: #dfdddd;">
                                                <th>Denomination</th>
                                                <th><span class="pull-right">Available Notes</span></th>
                                                <th><span class="pull-right"> Transfer to Day Book</span></th>
                                                <th><span class="pull-right">Calculated Value</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-right">2000</td>
                                                <td class="text-right"><?php 
                                                $val2000=(($income_notes_contra['note2000']-$expense_notes_contra['note2000']));
                                                    echo number_format(($income_notes_contra['note2000']-$expense_notes_contra['note2000'])); 
                                                ?>
                                                </td>
                                                <td><input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val2000; ?>)"  onblur="this.value = minmax(this.value, 0,<?php echo $val2000; ?>)" onkeyup="calDayMoney();" type="text" name="add2000" id="add2000out" autocomplete="off" class="form-control"></td>
                                                <td class="text-right" id="t2000out"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">500</td>
                                                <td class="text-right"><?php 
                                                $val500=(($income_notes_contra['note500']-$expense_notes_contra['note500'])); 
                                                    echo number_format(($income_notes_contra['note500']-$expense_notes_contra['note500'])); 
                                                ?>
                                                </td>
                                                <td>
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val500; ?>)"  onblur="this.value = minmax(this.value, 0,<?php echo $val500; ?>)" onkeyup="calDayMoney();" type="text" name="add500" id="add500out" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="t500out"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">200</td>
                                                <td class="text-right"><?php 
                                                $val200=(($income_notes_contra['note200']-$expense_notes_contra['note200']));
                                                echo number_format(($income_notes_contra['note200']-$expense_notes_contra['note200'])); 
                                                ?>
                                                </td>
                                                <td>
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val200; ?>)" onblur="this.value = minmax(this.value, 0,<?php echo $val200; ?>)" onkeyup="calDayMoney();" type="text" name="add200" id="add200out" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="t200out"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">100</td>
                                                <td class="text-right"><?php
                                                $val100=(($income_notes_contra['note100']-$expense_notes_contra['note100'])); 
                                                echo number_format(($income_notes_contra['note100']-$expense_notes_contra['note100'])); 
                                                ?>
                                                </td>
                                                <td>
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val100; ?>)" onblur="this.value = minmax(this.value, 0,<?php echo $val100; ?>)" onkeyup="calDayMoney();;" type="text" name="add100" id="add100out" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="t100out"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">50</td>
                                                <td class="text-right"><?php 
                                                $val50=(($income_notes_contra['note50']-$expense_notes_contra['note50'])); 
                                                echo number_format(($income_notes_contra['note50']-$expense_notes_contra['note50'])); 
                                                ?>
                                                </td>
                                                <td>
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val50; ?>)" onblur="this.value = minmax(this.value, 0,<?php echo $val50; ?>)" onkeyup="calDayMoney();" type="text" name="add50" id="add50out" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="t50out"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">20</td>
                                                <td class="text-right"><?php 
                                                $val20=(($income_notes_contra['note20']-$expense_notes_contra['note20'])); 
                                                echo number_format(($income_notes_contra['note20']-$expense_notes_contra['note20'])); 
                                                ?>
                                                </td>
                                                <td>
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val20; ?>)" onblur="this.value = minmax(this.value, 0,<?php echo $val20; ?>)" onkeyup="calDayMoney();" type="text" name="add20" id="add20out" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="t20out"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">10</td>
                                                <td class="text-right">
                                                <?php 
                                                $val10=(($income_notes_contra['note10']-$expense_notes_contra['note10'])); 
                                                echo number_format(($income_notes_contra['note10']-$expense_notes_contra['note10'])); 
                                                ?>
                                                </td>
                                                <td>
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val10; ?>)" onblur="this.value = minmax(this.value, 0,<?php echo $val10; ?>)" onkeyup="calDayMoney();" type="text" name="add10" id="add10out" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="t10out"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">coins</td>
                                                <td class="text-right"><?php 
                                                $valCoins=(($income_notes_contra['coins']-$expense_notes_contra['coins'])); 
                                                echo number_format(($income_notes_contra['coins']-$expense_notes_contra['coins'])); 
                                                ?>
                                                </td>
                                                <td>
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $valCoins; ?>)" onblur="this.value = minmax(this.value, 0,<?php echo $valCoins; ?>)" onkeyup="calDayMoney();" type="text" name="coin" id="coinout" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="tcoinsout"></td>
                                            </tr>
                                            <tr style="background-color: #dfdddd;">
                                                <td class="text-right"><b>Total</b></td>
                                                <td class="text-right"><b>
                                                <?php 
                                                    echo number_format(abs($diffContraEntry)); 
                                                ?></b></td>
                                                <td class="text-xs-right">
                                                    <span id="bookcalTotal"></span>
                                                    <input type="hidden" value="<?php echo (abs($diffContraEntry)); ?>" name="bankDep_amt" id="cashBookAmtRet_out" autocomplete="off" class="form-control">
                                                    <input type="hidden" name="collectedDepositTotalReturn" id="collectedDepositTotal_out" autocomplete="off" class="form-control">
                                                </td>
                                                <td class="text-right"><span id="tcalTotal_out"></span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <center>                                               
                                        <button id="transferToDayButton" type="submit" class="btn btn-primary m-t-15 waves-effect">
                                            <i class="material-icons">save</i> 
                                            <span class="icon-name">
                                                Save
                                            </span>
                                        </button> 

                                        <button data-dismiss="modal" type="button" id="bnkClose" class="btn btn-primary m-t-15 waves-effect">
                                            <i class="material-icons">cancel</i> 
                                            <span class="icon-name">
                                                cancel
                                            </span>
                                        </button>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="modal fade" id="myModal3" role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title text-center">Bank Deposit</h3>
                </div>
                <div class="modal-body">
                    <form method="post" onsubmit="return checkBankDepostAmount(this);" role="form" action="<?php echo site_url('owner/MainCashBookController/insertBankDepositEntry');?>"> 
                        <div class="row clearfix">
                            <div class="col-md-12">
                                <div class="col-md-12">
                                <p id="err-data1" style="color:red"></p>
                                    <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-basic-example" data-page-length='100'>
                                        <thead>
                                            <tr style="background-color: #dfdddd;">
                                                <th>Denomination</th>
                                                <th><span class="pull-right">Available Notes</span></th>
                                                <th><span class="pull-right"> Bank Deposit</span></th>
                                                <th><span class="pull-right">Calculated Value</span></th>
                                                <th><span class="pull-right">Remaining After Bank Deposit</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-right">2000</td>
                                                <td class="text-right" id="rem2000"><?php 
                                                $val2000=(($income_notes_contra['note2000']-$expense_notes_contra['note2000']));
                                                    echo number_format(($income_notes_contra['note2000']-$expense_notes_contra['note2000'])); 
                                                ?>
                                                </td>
                                                <td><input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val2000; ?>)" onblur="this.value = minmax(this.value, 0,<?php echo $val2000; ?>)" onkeyup="calDepositMoney();" type="text" name="add2000" id="add2000outBank" autocomplete="off" class="form-control"></td>
                                                <td class="text-right" id="t2000outBank"></td>
                                                <td class="text-right" id="t2000outBankRem"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">500</td>
                                                <td class="text-right" id="rem500"><?php 
                                                $val500=(($income_notes_contra['note500']-$expense_notes_contra['note500'])); 
                                                    echo number_format(($income_notes_contra['note500']-$expense_notes_contra['note500'])); 
                                                ?>
                                                </td>
                                                <td>
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val500; ?>)" onblur="this.value = minmax(this.value, 0,<?php echo $val500; ?>)" onkeyup="calDepositMoney();" type="text" name="add500" id="add500outBank" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="t500outBank"></td>
                                                <td class="text-right" id="t500outBankRem"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">200</td>
                                                <td class="text-right" id="rem200"><?php 
                                                $val200=(($income_notes_contra['note200']-$expense_notes_contra['note200']));
                                                echo number_format(($income_notes_contra['note200']-$expense_notes_contra['note200'])); 
                                                ?>
                                                </td>
                                                <td>
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val200; ?>)" onblur="this.value = minmax(this.value, 0,<?php echo $val200; ?>)" onkeyup="calDepositMoney();" type="text" name="add200" id="add200outBank" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="t200outBank"></td>
                                                <td class="text-right" id="t200outBankRem"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">100</td>
                                                <td class="text-right" id="rem100"><?php
                                                $val100=(($income_notes_contra['note100']-$expense_notes_contra['note100'])); 
                                                echo number_format(($income_notes_contra['note100']-$expense_notes_contra['note100'])); 
                                                ?>
                                                </td>
                                                <td>
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="" onblur="this.value = minmax(this.value, 0,<?php echo $val100; ?>)" onblur="this.value = minmax(this.value, 0,<?php echo $val100; ?>)" onkeyup="calDepositMoney();;" type="text" name="add100" id="add100outBank" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="t100outBank"></td>
                                                <td class="text-right" id="t100outBankRem"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">50</td>
                                                <td class="text-right" id="rem50"><?php 
                                                $val50=(($income_notes_contra['note50']-$expense_notes_contra['note50'])); 
                                                echo number_format(($income_notes_contra['note50']-$expense_notes_contra['note50'])); 
                                                ?>
                                                </td>
                                                <td>
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val50; ?>)" onblur="this.value = minmax(this.value, 0,<?php echo $val50; ?>)" onkeyup="calDepositMoney();" type="text" name="add50" id="add50outBank" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="t50outBank"></td>
                                                <td class="text-right" id="t50outBankRem"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">20</td>
                                                <td class="text-right" id="rem20"><?php 
                                                $val20=(($income_notes_contra['note20']-$expense_notes_contra['note20'])); 
                                                echo number_format(($income_notes_contra['note20']-$expense_notes_contra['note20'])); 
                                                ?>
                                                </td>
                                                <td>
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val20; ?>)" onblur="this.value = minmax(this.value, 0,<?php echo $val20; ?>)" onkeyup="calDepositMoney();" type="text" name="add20" id="add20outBank" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="t20outBank"></td>
                                                <td class="text-right" id="t20outBankRem"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">10</td>
                                                <td class="text-right" id="rem10">
                                                <?php 
                                                $val10=(($income_notes_contra['note10']-$expense_notes_contra['note10'])); 
                                                echo number_format(($income_notes_contra['note10']-$expense_notes_contra['note10'])); 
                                                ?>
                                                </td>
                                                <td>
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val10; ?>)" onblur="this.value = minmax(this.value, 0,<?php echo $val10; ?>)" onkeyup="calDepositMoney();" type="text" name="add10" id="add10outBank" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="t10outBank"></td>
                                                <td class="text-right" id="t10outBankRem"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">coins</td>
                                                <td class="text-right"><?php 
                                                $valCoins=(($income_notes_contra['coins']-$expense_notes_contra['coins'])); 
                                                echo number_format(($income_notes_contra['coins']-$expense_notes_contra['coins'])); 
                                                ?>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="remcoins" id="remcoins" value="<?php echo $valCoins; ?>">
                                                    <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $valCoins; ?>)" onblur="this.value = minmax(this.value, 0,<?php echo $valCoins; ?>)" onkeyup="calDepositMoney();" type="text" name="coin" id="coinoutBank" autocomplete="off" class="form-control">

                                                </td>
                                                <td class="text-right" id="tcoinsoutBank"></td>
                                                <td class="text-right" id="tcoinsoutBankRem"></td>
                                            </tr>
                                            <tr style="background-color: #dfdddd;">
                                                <td class="text-right"><b>Total</b></td>
                                                <td class="text-right"><b>
                                                <?php 
                                                    echo number_format(abs($diffContraEntry)); 
                                                ?></b></td>
                                                <td class="text-xs-right">
                                                    <span id="calTotal"></span>
                                                    <input type="hidden" value="<?php echo (abs($diffContraEntry)); ?>" name="bankDep_amt" id="cashBookAmtRet_outBank" autocomplete="off" class="form-control">
                                                    <input type="hidden" name="collectedDepositTotalReturn" id="collectedDepositTotal_outBank" autocomplete="off" class="form-control">
                                                </td>
                                                <td class="text-right"><span id="tcalTotal_outBank"></span></td>
                                                <td class="text-right"><span id="tcalTotal_outBankRem"></span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <center>                                               
                                        <button id="bankDepositButton" type="submit" class="btn btn-primary m-t-15 waves-effect">
                                            <i class="material-icons">save</i> 
                                            <span class="icon-name">
                                                Save
                                            </span>
                                        </button> 

                                        <button data-dismiss="modal" type="button" id="bnkClose" class="btn btn-primary m-t-15 waves-effect">
                                            <i class="material-icons">cancel</i> 
                                            <span class="icon-name">
                                                cancel
                                            </span>
                                        </button>
                                    </center>
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
    $(document).on('hidden.bs.modal','#myModal',function(){
      $(this).find("input:not([type=hidden]),textarea,select").val('').end().find("input[type=checkbox], input[type=radio]").prop("checked", "").end();
  });

    $('#myModal1').on('hidden.bs.modal', function () {
      $(this).find("input:not([type=hidden]),textarea,select").val('').end().find("input[type=checkbox], input[type=radio]").prop("checked", "").end();
  });

    $('#myModal2').on('hidden.bs.modal', function () {
      $(this).find("input:not([type=hidden]),textarea,select").val('').end().find("input[type=checkbox], input[type=radio]").prop("checked", "").end();

      $('#add2000').val('');
      $('#add1000').val('');
      $('#add500').val('');
      $('#add200').val('');
      $('#add100').val('');
      $('#add50').val('');
      $('#add20').val('');
      $('#add10').val('');
      $('#coin').val('');

      $('#ad2000').text('');
      $('#ad1000').text('');
      $('#ad500').text('');
      $('#ad200').text('');
      $('#ad100').text('');
      $('#ad50').text('');
      $('#ad20').text('');
      $('#ad10').text('');
      $('#coins').text('');
  });

</script>

<script type="text/javascript">
    $('#closeDayModal').on('hidden.bs.modal', function () {
        $(this).find("input,textarea,select").val('').end();
    });
</script>

<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>

<script type="text/javascript">
  function checkMainCashBookAmount(){
      var bankAmount = Number(document.getElementById("collectedDepositTotal").value);
      if(Number(bankAmount)>0){
        var availableAmount = Number(document.getElementById("totalOpeningAmt").value);
        if(availableAmount<bankAmount){
          alert('Amount is more than closing amount.');
          return false;
        }

        if (confirm('Do you want to transfer amount to Main cash Book. ?')) {
            $("#transferToMainCashBookButton").attr("disabled", true);
            return true;
        }else{
            $("#transferToMainCashBookButton").attr("disabled", true);
            return false;
        }
      }else{
          alert('Please enter notes details.');
          return false;
      }
  } 

  function checkDayBookAmount(){
      var bankAmount = Number(document.getElementById("collectedDepositTotal_out").value);
      if(Number(bankAmount)>0){
        var availableAmount = Number(document.getElementById("cashBookAmtRet_out").value);
        if(availableAmount<bankAmount){
          alert('Amount is more than closing amount.');
          return false;
        }

        if (confirm('Do you want to transfer amount to Day Book. ?')) {
            $("#transferToDayButton").attr("disabled", true);
            return true;
        }else{
            $("#transferToDayButton").attr("disabled", true);
            return false;
        }
      }else{
          alert('Please enter notes details.');
          return false;
      }
  } 

  function checkBankDepostAmount(){
      var bankAmount = Number(document.getElementById("collectedDepositTotal_outBank").value);
      if(Number(bankAmount)>0){
        var availableAmount = Number(document.getElementById("cashBookAmtRet_outBank").value);
        if(availableAmount<bankAmount){
          alert('Amount is more than closing amount.');
          return false;
        }

        if (confirm('Do you want to submit this Bank Deposit. ?')) {
            $("#bankDepositButton").attr("disabled", true);
            return true;
        }else{
            $("#bankDepositButton").attr("disabled", true);
            return false;
        }
      }else{
          alert('Please enter notes details.');
          return false;
      }
  } 
</script>

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
    var cal =parseFloat(cal)+ parseFloat(park)+parseFloat(challan)+parseFloat(cng);
    if(cal=='')
        cal=0;
    document.getElementById('total').innerHTML = cal.toFixed(2);
    document.getElementById('total1').innerHTML = cal.toFixed(2);

}
</script>

<script type="text/javascript">
    function diff(cash){
        var cal1=document.getElementById('total1').innerHTML;
        var cal2=document.getElementById('calTotal1').innerHTML;
        if(cal1=='')
            cal1=0;
        if(cal2=='')
            cal2=0;
        var tot=parseFloat(cal1)+parseFloat(cal2);
        var finalDiff=cash-tot;

        if(finalDiff>0.0){
            document.getElementById('diff').innerHTML="<span style='color:red;font-size:15px;'>"+finalDiff.toFixed(2)+"</span>";
            document.getElementById('status').innerHTML="<span style='color: red;font-size:15px;'>Short</span>";
        }else{
            document.getElementById('diff').innerHTML="<span style='color:#037921;font-size:15px;'>"+finalDiff.toFixed(2)+"</span>";
            document.getElementById('status').innerHTML="<span style='color:#037921;font-size:15px;'>Excess</span>";
        }
    }
</script>

<!-- for Transfer to Main Cash Book -->
<script type="text/javascript">
  function calMoney() {
    var a2000 = document.getElementById('add2000').value;
    // var a1000 = document.getElementById('add1000').value;
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

    // if(a1000 ==""){
    //     a1000=0;
    // }
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
    // var c2=0;
    // c2=1000*a1000;
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

    document.getElementById('t2000').innerHTML = c1;
    // document.getElementById('t1000').innerHTML = c2;
    document.getElementById('t500').innerHTML = c3;
    document.getElementById('t200').innerHTML = c4;
    document.getElementById('t100').innerHTML = c5;
    document.getElementById('t50').innerHTML = c6;
    document.getElementById('t20').innerHTML = c7;
    document.getElementById('t10').innerHTML = c8;
    document.getElementById('tcoins').innerHTML = c9;
    var total=0;
    total=total+c1+c3+c4+c5+c6+c7+c8;
    // total=total+c1+c2+c3+c4+c5+c6+c7+c8;
   
    total=parseFloat(total)+parseFloat(c9);
    document.getElementById('calTotal').innerHTML = total.toFixed(2);
    document.getElementById('tcalTotal').innerHTML = total.toFixed(2);
    document.getElementById('collectedDepositTotal').value = total.toFixed(2);

}
</script>

<!-- for Transfer to Day Book -->
<script type="text/javascript">
  function calDayMoney() {
    var a2000 = document.getElementById('add2000out').value;
    // var a1000 = document.getElementById('add1000').value;
    var a500 = document.getElementById('add500out').value;
    var a200 = document.getElementById('add200out').value;
    var a100 = document.getElementById('add100out').value;
    var a50 = document.getElementById('add50out').value;
    var a20 = document.getElementById('add20out').value;
    var a10 = document.getElementById('add10out').value;
    var coin = document.getElementById('coinout').value;

    if(a2000 ==""){
        a2000=0;
    }
    // if(a1000 ==""){
    //     a1000=0;
    // }
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
    // var c2=0;
    // c2=1000*a1000;
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

    document.getElementById('t2000out').innerHTML = c1;
    // document.getElementById('t1000').innerHTML = c2;
    document.getElementById('t500out').innerHTML = c3;
    document.getElementById('t200out').innerHTML = c4;
    document.getElementById('t100out').innerHTML = c5;
    document.getElementById('t50out').innerHTML = c6;
    document.getElementById('t20out').innerHTML = c7;
    document.getElementById('t10out').innerHTML = c8;
    document.getElementById('tcoinsout').innerHTML = c9;
    var total=0;
    total=total+c1+c3+c4+c5+c6+c7+c8;
    // total=total+c1+c2+c3+c4+c5+c6+c7+c8;
   
    total=parseFloat(total)+parseFloat(c9);
    // document.getElementById('bookcalTotal').innerHTML = total.toFixed(2);
    document.getElementById('tcalTotal_out').innerHTML = total.toFixed(2);
    document.getElementById('collectedDepositTotal_out').value = total.toFixed(2);

}
</script>

<!-- for Bank Deposit -->
<script type="text/javascript">
  function calDepositMoney() {
    var a2000 = document.getElementById('add2000outBank').value;
    var a500 = document.getElementById('add500outBank').value;
    var a200 = document.getElementById('add200outBank').value;
    var a100 = document.getElementById('add100outBank').value;
    var a50 = document.getElementById('add50outBank').value;
    var a20 = document.getElementById('add20outBank').value;
    var a10 = document.getElementById('add10outBank').value;
    var coin = document.getElementById('coinoutBank').value;

    var rem2000 = document.getElementById('rem2000').innerHTML.replace(/,/g, '');
    var rem500 = document.getElementById('rem500').innerHTML.replace(/,/g, '');
    var rem200 = document.getElementById('rem200').innerHTML.replace(/,/g, '');
    var rem100 = document.getElementById('rem100').innerHTML.replace(/,/g, '');
    var rem50 = document.getElementById('rem50').innerHTML.replace(/,/g, '');
    var rem20 = document.getElementById('rem20').innerHTML.replace(/,/g, '');
    var rem10 = document.getElementById('rem10').innerHTML.replace(/,/g, '');
    var remcoin = (document.getElementById('remcoins').value);
    
    var originalAmt = document.getElementById('cashBookAmtRet_outBank').value;
   

    if(a2000 ==""){
        a2000=0;
    }
    // if(a1000 ==""){
    //     a1000=0;
    // }
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
    // var c2=0;
    // c2=1000*a1000;
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

    document.getElementById('t2000outBank').innerHTML = c1;
    // document.getElementById('t1000').innerHTML = c2;
    document.getElementById('t500outBank').innerHTML = c3;
    document.getElementById('t200outBank').innerHTML = c4;
    document.getElementById('t100outBank').innerHTML = c5;
    document.getElementById('t50outBank').innerHTML = c6;
    document.getElementById('t20outBank').innerHTML = c7;
    document.getElementById('t10outBank').innerHTML = c8;
    document.getElementById('tcoinsoutBank').innerHTML = c9;

    document.getElementById('t2000outBankRem').innerHTML = (rem2000-a2000);
    // document.getElementById('t1000').innerHTML = c2;
    document.getElementById('t500outBankRem').innerHTML = (rem500-a500);
    document.getElementById('t200outBankRem').innerHTML = (rem200-a200);
    document.getElementById('t100outBankRem').innerHTML = (rem100-a100);
   
    document.getElementById('t50outBankRem').innerHTML = (rem50-a50);
    document.getElementById('t20outBankRem').innerHTML = (rem20-a20);
    document.getElementById('t10outBankRem').innerHTML = (rem10-a10);
    document.getElementById('tcoinsoutBankRem').innerHTML = (remcoin-coin);

    var total=0;
    total=total+c1+c3+c4+c5+c6+c7+c8;
   
    total=parseFloat(total)+parseFloat(c9);

    var collected=total;
    collected=(Number(collected).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));

    document.getElementById('tcalTotal_outBank').innerHTML = collected;
    
    document.getElementById('collectedDepositTotal_outBank').value = total.toFixed(2);
    
    var remaining=originalAmt-total;
    remaining=(Number(remaining).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
    document.getElementById('tcalTotal_outBankRem').innerHTML =remaining;

}
</script>

<!-- With - for negative values -->
<script>
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode !=45 && (charCode < 48 || charCode > 57) ) {
            return false;
        }
        return true;
    }
</script>

<script>
    function isNumberWithoutDash(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if ((charCode < 48 || charCode > 57) ) {
            return false;
        }
        return true;
    }
</script>


<script type="text/javascript">
  $('body').on('hidden.bs.modal', '.modal', function () {
    $(this).removeData('bs.modal');
  });
</script>


<script type="text/javascript">
    $(document).ready(function() {
        $("input:text").focus(function() { $(this).select(); } );
    });
</script>

<script type="text/javascript">
  $(document).on("click","#inClose",function() {
      location.reload();
  });

  $(document).on("click","#expClose",function() {
      location.reload();
  });

  $(document).on("click","#dayBClose",function() {
      location.reload();
  });

  $(document).on("click","#bnkClose",function() {
      location.reload();
  });

  $(document).on("click","#noteExClose",function() {
      location.reload();
  });
</script>

<script type="text/javascript">
    function minmax(value, min, max) 
    {

        // alert(value+' '+min+' '+max);
        if(parseInt(value) ==0){
            $('#err-data').html('');
            return value; 
        }else if(parseInt(value) < min || isNaN(parseInt(value))) {
            $('#err-data').html('');
            $('#err-data1').html('');
            return value; 
        } else if ((parseInt(value) > min && parseInt(value) < max)){ 
            $('#err-data').html('');
            $('#err-data1').html('');
            return value; 
        } else if (parseInt(value) == max) {
            $('#err-data').html('');
            $('#err-data1').html('');
            return value;
        } else {
            $('#err-data1').html('Notes can not more than available notes');
            $('#err-data').html('Notes can not more than available notes');
            return '';
        }
    }
</script>