<?php $this->load->view('/layouts/commanHeader'); 
  error_reporting(0);
?>
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
                            <center><h2>
                               Frequent SR Retailers Report
                            </h2></center><br/>

                        </div>

                        <div class="body">
                                               <br>
                        <div class="row">   
                        <div class="col-md-12">
                            <div class="col-md-6 radio-btns-div">
                              <!-- <div>
                                <input name="group5" type="radio" id="radio_cash" class="with-gap radio-col-red" checked="" value="">
                                <label for="radio_cash">Cash</label>
                              </div> -->
                              <div>
                                <?php $oneMonth = date('Y-m-d',strtotime("-1 month"));?>
                                <input type="radio" id="radio_cashForAll" class="with-gap radio-col-red" value="<?php echo $oneMonth;?>" name='course' />
                                <label for="radio_cashForAll">1 Month</label>
                              </div>
                            <div>
                                <?php $threeMonth = date('Y-m-d',strtotime("-3 month"));?>
                                <input type="radio" name='course' id="radio_chequeForAll" value="<?php echo $threeMonth;?>"class="with-gap radio-col-red"  />
                                <label for="radio_chequeForAll">3 Month</label>
                            </div>

                            <div>
                                <?php $sixMonth = date('Y-m-d',strtotime("-6 month"));?>
                                <input type="radio" name='course' value="<?php echo $sixMonth;?>" id="radio_chequeForAll1" class="with-gap radio-col-red"  />
                                <label for="radio_chequeForAll1">6 Month</label>
                            </div>

                            <div>
                                <?php $oneYear = date('Y-m-d',strtotime("-1 year"));?>
                                <input type="radio" name='course' id="radio_chequeForAll2" class="with-gap radio-col-red" value="<?php echo $oneYear;?>" />
                                <label for="radio_chequeForAll2">1 Year</label>
                            </div>
                            
                            </div>

                        </div>
                    </div>
                     <div id=d></div>
                         
                         <div class="table-responsive test" id="test">
                                
                                <table id="Tbldata" style="font-size: 12px;" class="table table-bordered cust-tbl js-exportable dataTable" data-page-length='25'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Retailer Code</th>
                                            <th>Retailer Name</th>
                                            <th>Salesman</th>
                                            <th>Route</th>
                                            <th>No Of Bills</th>
                                            <th>No Of SR</th>
                                            <th>Bills Amount</th>
                                            <th>SR Amount</th>
                                            <th>% SR</th>
                                           
                                        </tr>
                                    </thead>
                                    <tfoot>
                                         <tr>
                                            <th>S. No.</th>
                                            <th>Retailer Code</th>
                                            <th>Retailer Name</th>
                                            <th>Salesman</th>
                                            <th>Route</th>
                                            <th>No Of Bills</th>
                                            <th>No Of SR</th>
                                            <th>Bills Amount</th>
                                            <th>SR Amount</th>
                                            <th>% SR</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php
                                    $no=0;
                                    foreach ($retailerSR as $data) 
                                    {  
                                    $no++;
                                    $retailerName = $data['retailerName'];
                                   // echo $newString = str_replace(' ',/' , $retailerName);
                                     $aa = str_replace("\\","",$retailerName);
                                    //echo $name = preg_replace('/\s+/', ' ', $retailerName);
                                    $biId = $data['biId'];
                                    //$sum += $data['SRAmt'];
                                //$this->db->select('DISTINCT(allocation_sr_details.billId) as biId, bills.id as bid, bills.retailerName');
                                    $this->db->select('allocation_sr_details.billId as biId, bills.id as bid, bills.retailerName');
                                    $this->db->join('bills','bills.id=allocation_sr_details.billId','left outer');
                                    $this->db->where('bills.retailerName', $retailerName);
                                    $query=$this->db->get('allocation_sr_details');
                                    //$aa = $this->db->last_query(); print_r($aa); exit();
                                    $retailerCount = $query->num_rows();
                            

                                    $this->db->select('DISTINCT(allocation_sr_details.billId) as biId, bills.id as bid, bills.retailerName');
                                    $this->db->join('bills','bills.id=allocation_sr_details.billId','left outer');
                                    $this->db->where('bills.retailerName', $retailerName);
                                    $query=$this->db->get('allocation_sr_details');
                                    //$aa = $this->db->last_query(); print_r($aa); exit();
                                    $srCount = $query->num_rows();

                                    $amount = $this->db->query("SELECT SUM(netAmount) as ttlAmt FROM bills where retailerName like '$aa' ")->result_array();

                                    $sumSR = $this->db->query("SELECT SUM(SRAmt) as srttl FROM bills where retailerName like '$aa' ")->result_array();
                                    
                                    $perSR = ($sumSR[0]['srttl'] * 100) /$amount[0]['ttlAmt'];
        
                                        ?>
                                        <tr>
                                          <td><?php echo $no; ?></td>
                                          <td><?php echo $data['retailerCode']; ?></td>
                                          <td class="noSpace CellWithComment"><?php 
                                          $retailer = substr($data['retailerName'],0,15);
                                          echo rtrim($retailer,', '); ?>
                                          <span class="CellComment"><?php echo $result =substr($data['retailerName'],0); ?></span></td>
                                          <td class="noSpace CellWithComment"><?php $salesman = substr($data['salesman'],0,15); echo rtrim($salesman,', '); ?>
                                           <span class="CellComment"><?php echo $result =substr($data['salesman'],0); ?></span>
                                          </td>
                                          <td class="noSpace CellWithComment"><?php $routeName = substr($data['routeName'],0,15); echo rtrim($routeName,', '); ?>
                                          <span class="CellComment"><?php echo $result =substr($data['routeName'],0); ?></span>
                                          </td>
                                          <td><?php echo $srCount; ?></td>
                                          <td><?php //foreach ($count4 as $key => $value) { 
                                       // echo $value->count_rows;
                                            echo $retailerCount;
                                   //  } ?></td>
                                          <td><?php echo $amount[0]['ttlAmt']; ?></td>
                                          <td><?php echo $sumSR[0]['srttl']; ?></td>
                                          <td><?php //echo $perSR;
                                          echo number_format((float)$perSR, 2, '.', ''); ?></td>
                                     <?php
                                            } 
                                        ?> 
                                        </tr>
                                       
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

<?php $this->load->view('/layouts/footerDataTable'); ?>

 <script>
$(document).ready(function() {
// Jquery code here ///
$("input[type='radio']").change(function() {
var value = $("input[name=course]:checked").val();
//alert(value);
//$('#d').load('loadck.php?class='+class1);
 $.ajax({
            type: "POST",
            url:"<?php echo site_url('reports/ReportController/showDetails');?>",
                data:{"value" : value},
                success: function (data) {
                    // alert(data);
                  $('#d').html(data);
                  $('#test').css("display", "none");
                }  
            });
});
///
});
</script>