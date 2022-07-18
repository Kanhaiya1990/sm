<?php $this->load->view('/layouts/commanHeader'); ?>

<style type="text/css">
    @media screen and (min-width: 1100px) {
        .modal-dialog {
          width: 1100px; /* New width for default modal */
        }
        .modal-sm {
          width: 350px; /* New width for small modal */
        }
    }

    @media screen and (min-width: 1100px) {
        .modal-lg {
          width: 1100px; /* New width for large modal */
        }
    }

</style>

        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
        <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
           
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Past Day Book
                            </h2><br/>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered js-basic-example dataTable cust-tbl" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Date</th>
                                            <th class="text-right">Opening Balance</th>
                                            <th class="text-right">Inflow</th>
                                            <th class="text-right">Outflow</th>
                                            <th class="text-right">Bank Deposit</th>
                                            <th class="text-right">Contra Entry</th>
                                            <th class="text-right">Short/Excess</th>
                                            
                                            <th class="text-right">Closing Cash</th>
                                            <th>View</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                         <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Date</th>
                                            <th class="text-right">Opening Balance</th>
                                            <th class="text-right">Inflow</th>
                                            <th class="text-right">Outflow</th>
                                            <th class="text-right">Bank Deposit</th>
                                            <th class="text-right">Contra Entry</th>
                                            <th class="text-right">Short/Excess</th>
                                            
                                            <th class="text-right">Closing Cash</th>
                                            <th>View</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        $no=0;
                                        foreach ($dayBook as $data) 
                                          {


                                            $no++; 
                                            $checkCashReconsile=$this->CashBookModel->checkCashReconsilation('expences',$data['closeDayBookName']);
                                           
                                            $contraEntryInflow=$this->CashBookModel->calculatePastInflowContraEntryForDayBook($data['closeDayBookName']);
                                            $contraEntryOutflow=$this->CashBookModel->calculatePastOutflowContraEntryForDayBook($data['closeDayBookName']);
                                            $contraEntryOutflow=$contraEntryOutflow[0]['outflowTotal']; 
                                            $contraEntryInflow=$contraEntryInflow[0]['inflowTotal']; 
                                            $diffContraEntry=$contraEntryInflow-$contraEntryOutflow;
                                // $openingBalance=$this->CashBookModel->openingRecordValueByDate($data['date']);
                                // $closingBalance=$this->CashBookModel->closingBalValueByDate($data['date']);
                                // $bankDep=$this->CashBookModel->sumBankDepositByDate($data['date']);
                                // $income=$this->CashBookModel->sumIncomeByDate($data['date']);
                                // $exp=$this->CashBookModel->sumExpenseByDate($data['date']);
                                        ?>
                                        <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $data['closeDayBookName']; ?></td>
                                        <td class="noSpace"><?php echo date("d-M-Y", strtotime($data['closeDayBookDate'])); ?></td>
                                        <td align="right"><?php echo number_format($data['openingBalance']); ?></td>
                                        <td align="right"><?php echo number_format($data['totalIncome']);?></td>
                                        <td align="right"><?php echo number_format($data['totalExpense']);?></td>
                                        <td align="right"><?php echo number_format($data['totalBankDeposit']);?></td>
                                        <td align="right">
                                        <?php
                                              echo '<span style="color:red">'.abs($diffContraEntry).'</span>'; 
                                         ?>
                                       </td>
                                        <td align="right">
                                        <?php
                                        $cal=($data['openingBalance']+$data['totalIncome']-($data['totalExpense']+$data['totalBankDeposit']))-$data['collectedAmount']+$diffContraEntry;
                                        if($cal > 0){
                                          echo '<span style="color:red">'.number_format(abs($cal)).'</span>'; 
                                        }else{
                                          echo '<span style="color:blue">'.number_format(abs($cal)).'</span>'; 
                                        }
                                         
                                         ?>
                                       </td>
                                       
                                        <td align="right"><?php echo number_format($data['collectedAmount']); ?></td>

                                        <td>
                                            <?php if(empty($checkCashReconsile)){?>
                                            <a href="<?php echo site_url('manager/CashBookController/pastDayIncomeExpense/'.$data['closeDayBookName'].'/'.$data['openingBalance']); ?>">
                                            <button class="btn btn-xs btnStyle margin"><i class="material-icons">visibility</i></button>
                                                </a> 
                                            <?php } ?>
                                        </td>
                                     
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
            <!-- #END# Basic Examples -->  
        </div>
    </section>

<div class="container">
  <div class="modal fade" id="PastDayDataModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="mods">
          
              
          </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('/layouts/footerDataTable'); ?>

<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>
<script>
 $(document).ready(function(){
    $('#srEdit-id').click(function(){
        var closeDayBookName=$(this).attr('data-closeDayBookName');

        $.ajax({
            url : "<?php echo site_url('manager/CashBookController/dayWisePastDayBook');?>",
            method : "POST",
            data : {closeDayBookName: closeDayBookName},
            success: function(data){
              $('.mods').html(data);
            }
        });
        

    });
});
</script>


    <script>
function deleted(id)
{ 
  // alert(id);
swal({
  title: "Are you sure to delete?",
  text: "Once deleted, you will not be able to recover this!",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
    $.ajax({
        url: "<?php echo site_url('manager/EmployeeController/delete');?>",
        type: "post",
        data: {'id':id},
        success: function (response) {
         
          swal(response, {
            icon: "success",
          });
          var URL = "<?php echo site_url('manager/EmployeeController');?>";
          setTimeout(function(){ window.location = URL; }, 1000);
        },
        error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus, errorThrown);
        }
    });
    
  } else {
    swal("Your record is safe!");
  }
});
}
</script>

