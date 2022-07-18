<?php $this->load->view('/layouts/commanHeader'); ?>
<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>
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
<script>
  $(document).ready(function() {
        $.fn.dataTable.ext.errMode = 'none';
    $('#Tbldata').DataTable( {
        stateSave: false,
      dom: 'Bfrtip',
      buttons: [
      'copyHtml5',
      'excelHtml5',
      'csvHtml5',
      'pdfHtml5'
      ]
    } );
  } );
</script>


        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
        <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
           
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Accountant Income Expense Report
                            </h2><br/>
                        </div>
                        <div class="body">
                          <div class="row">
                            <div class="col-md-12">
                                    <form method="post" role="form" action="<?php echo site_url('accountant/AccountantController/incomeExpense');?>">
                                          <label>From Date:</label>
                                          <input type="date" value="<?php echo $startDate?>" name="from_date" required >
                                          <label>To Date:</label>
                                          <input type="date" value="<?php echo $endDate?>" name="to_date" required>
                                          <button type="submit" class="btn btn-primary">Search</button>
                                          <a href="<?php echo site_url('accountant/AccountantController/incomeExpense');?>">
                                              <button type="button" class="btn btn-primary">Cancel</button>
                                          </a> 
                                    </form>
                                </div>
                            </div>
                            <div class="table-responsive">
                                
                                <table id="Tbldata" style="font-size: 12px;" class="table table-bordered table-striped table-hover js-exportable dataTable" data-page-length='25'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Date</th>
                                            <th>View</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                         <tr>
                                            <th>S. No.</th>
                                            <th>Date</th>
                                            <th>View</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        $no=0;
                                        foreach ($dayBook as $data) 
                                        {
                                          $date=date("Y-m-d", strtotime($data['date']));
                                          $ownerExpenseApproval=$this->CashBookModel->getPendingExpenseCountByDate('notesdetails',$date);
                                          $ownerExpenseApprovalTo=$this->CashBookModel->getPendingCashExpenseCountByDate('expences',$date);
                                           $no++; 
                                            $date=date("Y-m-d", strtotime($data['date']));
                                        ?>
                                        <tr>
                                          <td><?php echo $no; ?></td>
                                          <td><?php echo date("D, d-M-Y", strtotime($data['date'])); ?></td>
                                          <td>
                                            <?php 
                                              if((!empty($ownerExpenseApproval)) || (!empty($ownerExpenseApprovalTo))){
                                                echo "Expenses are not approved by owner.";
                                              }else{ ?>
                                                <a href="<?php echo site_url('accountant/AccountantController/incomeExpenseDetails/'.$date); ?>">
                                                  <i class="material-icons" style="color: blue;">remove_red_eye</i>
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
            },
            beforeSend: function(){
                $('.comman-ajax-loader').css("visibility", "visible");
            },
            complete: function(){
                $('.comman-ajax-loader').css("visibility", "hidden");
            },
            error: function(jqXHR, exception) {
                alert("Something Went Wrong, Please Try Again...!");
            }   
        });
        

    });
});
</script>


<script>
function deleted(id)
{ 
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

<script type="text/javascript">
    function rejecetCloseDayBook(){
        alert('Expenses are not approved by owner.');
    }
</script>