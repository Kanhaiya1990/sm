<?php $this->load->view('/layouts/commanHeader'); ?>

<script   src="https://code.jquery.com/jquery-1.12.1.js" integrity="sha256-VuhDpmsr9xiKwvTIHfYWCIQ84US9WqZsLfR4P7qF6O8="   crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
               
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                          <h2>
                              <button class="btn btn-xs bg-primary margin" onclick="history.back()"><i class="material-icons"> arrow_back</i>    </button>
                              <a href="<?php echo site_url('admin/EmployeeController/employeeLedgerByEmp/'.$empId);?>">
                              <button class="btn btn-xs bg-primary margin"><i class="material-icons"> refresh</i>    </button></a>

                              <?php 
                                $dt=date_create(date($fromdate));
                                $fdate = date_format($dt,'d-M-Y');

                                $dt=date_create(date($todate));
                                $tdate = date_format($dt,'d-M-Y');
                              ?>
                              Ledger For <?php echo urldecode($emp[0]['name']); ?>  :- <?php echo $fdate; ?> to <?php echo $tdate; ?></h2>
                        </div>

                        <div class="body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form method="post" role="form" action="<?php echo site_url('admin/EmployeeController/employeeLedgerByEmp/'.$empId);?>">
                                        
                                        <label>From Date:</label>
                                        <input type="date" name="from_date" value="<?php if(!empty($fromdate)){ echo $fromdate; }else{ echo date('Y-m-d'); } ?>" required >
                                        <label>To Date:</label>
                                        <input type="date" name="to_date" value="<?php if(!empty($todate)){ echo $todate; }else{ echo date('Y-m-d'); }?>" required>
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        
                                    </form>

                                </div>
                            </div><br>
                            <div class="table-responsive">
                                <table id="tbl-emp" style="font-size: 12px" class="table table-bordered table-striped table-hover dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No</th>
                                            <th>Employee Name</th>
                                            <th>Date</th>
                                            <th>Credit</th>
                                            <th>Debit</th>
                                            <th>Current Balance</th>
                                            <th style="display: none;">Current Balance</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>S. No</th>
                                            <th>Employee Name</th>
                                            <th>Date</th>
                                            <th>Credit</th>
                                            <th>Debit</th>
                                            <th>Current Balance</th>
                                            <th style="display: none;">Current Balance</th>
                                            <th>Details</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    
                                        <tr>
                                            <?php $no=1; ?>
                                            <td><?php echo $no; ?></td>
                                            <td>Opening Balance</td>
                                            <td><?php 
                                                $dt=date_create(date($fromdate));
                                                $date = date_format($dt,'d-M-Y');
                                                echo $date;
                                                ?>
                                            </td>
                                            <td></td>
                                            <td></td>
                                              <td>
                                            <?php 
                                             $bal=0;
                                                if(!empty($opening)){
                                                   
                                                    foreach ($opening as $data) 
                                                    {
                                                        if($data['transactionType']=='cr'){
                                                            $bal=$bal+$data['amount'];
                                                        }else if($data['transactionType']=='dr'){
                                                             $bal=$bal-$data['amount'];
                                                        }
      
                                                    }
                                                    if($bal<0){
                                                                echo '<span style="color:red">'.str_replace('-','',intval($bal)).' dr</span>'; 
                                                    }else if($bal>0){
                                                            echo '<span style="color:blue">'.intval($bal).' cr</span>'; 
                                                    }else{
                                                            echo 0;
                                                    }
                                                }else{
                                                    echo 0;
                                                }
                                            ?>
                                            </td>

                                                <td style="display: none;">
                                            <?php 
                                             $bal=0;
                                                if(!empty($opening)){
                                                   
                                                    foreach ($opening as $data) 
                                                    {
                                                        if($data['transactionType']=='cr'){
                                                            $bal=$bal+$data['amount'];
                                                        }else if($data['transactionType']=='dr'){
                                                             $bal=$bal-$data['amount'];
                                                        }
      
                                                    }
                                                    if($bal<0){
                                                                echo intval($bal); 
                                                    }else if($bal>0){
                                                            echo intval($bal); 
                                                    }else{
                                                            echo 0;
                                                    }
                                                }else{
                                                    echo 0;
                                                }
                                            ?>
                                            </td>
                                            <td></td>
                                        </tr>
                                         
                                    
                                      

                                        <?php
                                            
                                              $crAmount=0;
                                              $drAmount=0;
                                              $balance=0;
                                              foreach ($empLedger as $data) 
                                                {
                                                  $id=$data['id'];
                                                 $no++; 
                                          ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        
                                       
                                        <td><?php echo $data['empName']; ?></td>
                                        <td><?php 
                                                $dt=date_create($data['createdAt']);
                                                $date = date_format($dt,'d-M-Y H:i:sa');
                                                echo $date;
                                        ?>
                                        </td>
                                        <td style="color:blue">
                                        <?php 
                                            if($data['transactionType']=='cr'){
                                                echo $data['amount']+$bal; 
                                            } 
                                         ?>
                                         </td>
                                        <td style="color:red">
                                        <?php
                                            if($data['transactionType']=='dr'){
                                                echo $data['amount']; 
                                            } 
                                         ?>
                                                
                                        </td>
                                        <td>
                                            <?php 

                                            if($data['transactionType']=='cr'){

                                                $balance=$balance+$data['amount'];
                                            }else if($data['transactionType']=='dr'){
                                                 $balance=$balance-$data['amount'];
                                            }
                                            
                                            if($balance<0){
                                                if($no==2){ 
                                                    echo '<span style="color:red">'.str_replace('-','',intval($balance+$bal)).' dr</span>'; 
                                                }else{
                                                    echo '<span style="color:red">'.str_replace('-','',intval($balance)).' dr</span>'; 
                                                }
                                                
                                            }else if($balance>0){
                                                echo '<span style="color:blue">'.intval($balance).' cr</span>'; 
                                            }else{
                                                echo 0;
                                            }
                                            
                                            ?>
                                                
                                        </td>
                                        <td style="display: none;">
                                            <?php 

                                            if($data['transactionType']=='cr'){

                                                $balance=$balance+$data['amount'];
                                            }else if($data['transactionType']=='dr'){
                                                 $balance=$balance-$data['amount'];
                                            }
                                            
                                            if($balance<0){
                                                if($no==2){ 
                                                    echo intval($balance+$bal); 
                                                }else{
                                                    echo intval($balance); 
                                                }
                                                
                                            }else if($balance>0){
                                                echo intval($balance); 
                                            }else{
                                                echo 0;
                                            }
                                            
                                            ?>
                                                
                                        </td>
                                        <td><?php if($data['billNo'] ==''){ 
                                                echo $data['description'];
                                             }else{ 
                                                echo 'Debited amount for bill no '.$data['billNo'].'. '.$data['description']; 
                                            }  ?>
                                                    
                                        </td>
                                       
                                   </tr>  
                                     <?php
                                        }
                                      ?>  

                                      <tr>
                                            <td><?php echo $no+1; ?></td>
                                            <td>Closing Balance</td>
                                            <td>
                                            <?php 
                                                if(empty($tdate)){
                                                    $dt=date_create(date('Y-m-d'));
                                                    $date = date_format($dt,'d-M-Y');
                                                    echo $date;
                                                }else{
                                                    $dt=date_create($tdate);
                                                    $date = date_format($dt,'d-M-Y');
                                                    echo $date;
                                                }
                                               
                                            ?>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td>
                                            <?php
                                           
                                                
                                             $bal=0;
                                                if(!empty($closing)){
                                                   
                                                    foreach ($closing as $data) 
                                                    {
                                                        if($data['transactionType']=='cr'){
                                                            $bal=$bal+$data['amount'];
                                                        }else if($data['transactionType']=='dr'){
                                                             $bal=$bal-$data['amount'];
                                                        }
      
                                                    }
                                                    if($bal<0){
                                                                echo '<span style="color:red">'.str_replace('-','',intval($bal)).' dr</span>'; 
                                                    }else if($bal>0){
                                                            echo '<span style="color:blue">'.intval($bal).' cr</span>'; 
                                                    }else{
                                                            echo 0;
                                                    }
                                                }else{
                                                    echo 0;
                                                }
                                          
                                            ?>
                                            </td>

                                             <td style="display: none;">
                                            <?php
                                           
                                                
                                             $bal=0;
                                                if(!empty($closing)){
                                                   
                                                    foreach ($closing as $data) 
                                                    {
                                                        if($data['transactionType']=='cr'){
                                                            $bal=$bal+$data['amount'];
                                                        }else if($data['transactionType']=='dr'){
                                                             $bal=$bal-$data['amount'];
                                                        }
      
                                                    }
                                                    if($bal<0){
                                                                echo intval($bal); 
                                                    }else if($bal>0){
                                                            echo intval($bal); 
                                                    }else{
                                                            echo 0;
                                                    }
                                                }else{
                                                    echo 0;
                                                }
                                          
                                            ?>
                                            </td>
                                            <td></td>
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
<script type="text/javascript">
  $(document).ready(function() {
      $.fn.dataTable.ext.errMode = 'none';
      $('#tbl-emp').DataTable( {
          stateSave: true,
        dom: 'Bfrtip',
        buttons: [
          {
            extend: 'pdf',
            exportOptions: {
              columns: [ 2, 3 ]
            }
          },
          {
            extend: 'excel',
            exportOptions: {
              columns: [ 0,1,2,3,4,6,7 ]
            }
          }
        ]
      });
  });
</script>