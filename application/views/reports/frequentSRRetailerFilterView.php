<script>
  $(document).ready(function() {
        $.fn.dataTable.ext.errMode = 'none';
    $('#Tbldat').DataTable( {
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
<table id="Tbldat" style="font-size: 12px;" class="table table-bordered cust-tbl js-exportable dataTable" data-page-length='25'>
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
                                    foreach ($retailerSRs as $data) 
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
                                
