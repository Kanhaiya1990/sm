<?php $this->load->view('/layouts/commanHeader'); 
    $projectSessionName="";

    if (isset($this->session->userdata['codeKeyData'])) {
        $projectSessionName= ($this->session->userdata['codeKeyData']['codeKeyValue']);
    }
?>

    <h1 style="display: none;">Welcome</h1><br/><br/><br/>
    <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
           
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Add Tagged Bill
                            </h2>
                        </div>
                            
                        <div class="body">
                        <div class="table-responsive">
                        <div class="body">
                            <div class="demo-masked-input">

                                <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                         <tr>
                                            <th>SrNo</th>
                                            <th>BillNo</th>
                                            <th>Date</th>
                                            <th>RetName</th>                                            
                                            <th>Emp</th>
                                            <th>CD</th>
                                            <th>NetAmt</th>
                                            <th>SRAmt</th>
                                            <th>RecvAmt</th>
                                            <th>PendingAmt</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                         <?php
                                          $no=0;
                                          foreach ($bills as $data) 
                                            {
                                                $no++; 
                                            ?>        
                                            <tr>
                                                <td><?php echo $no; ?></td> 
                                                <td>
                                                    <?php echo rtrim($data['billNo']); ?>
                                                </td>
                                               
                                                <td><?php echo $data['date']; ?></td>
                                                <td><?php echo $data['retailerName']; ?></td>
                                                <td><?php echo $data['empname'];?></td>
                                                <td><?php echo $data['cashDiscount'];?></td>
                                                <td><?php echo $data['netAmount'];?></td>
                                                <td><?php echo $data['SRAmt'];?></td>
                                                <td><?php echo $data['receivedAmt'];?></td>
                                                <td><?php echo $data['pendingAmt']; ?></td>
                                            </tr>
                                        
                                         <?php
                                        }
                                      ?> 
                                    </tbody>
                                </table>
                            </div>
                                <!-- <div class="row clearfix">
                                     <input type="hidden" name="id" value="<?php
                                    if(isset($bills))
                                      {
                                        echo $bills[0]['id'];
                                      }
                                    ?>">
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <p>
                                              <b>Bill No </b>
                                            </p>
                                            <div class="form-line">
                                                <input type="text" name="billNo" class="form-control" value="<?php if(isset($bills))
                                                        {
                                                          echo $bills[0]['billNo']; 
                                                        }
                                                        ?>" readonly>           
                                            </div>
                                        </div>  
                                        <div class="col-md-3">
                                            <p>
                                              <b>Retailer Name </b>
                                            </p>
                                            <div class="form-line">
                                                <input type="text" name="name" class="form-control" value="<?php if(isset($bills))
                                                        {
                                                          echo $bills[0]['name']; 
                                                        }
                                                        ?>" readonly>           
                                            </div>
                                        </div>  
                                         <div class="col-md-3">
                                            <p>
                                              <b>Person Name </b>
                                            </p>
                                            <div class="form-line">
                                                <input type="text" name="name" class="form-control" value="<?php if(isset($bills))
                                                        {
                                                          echo $bills[0]['empname']; 
                                                        }
                                                        ?>" readonly>           
                                            </div>
                                        </div>  
                                         <div class="col-md-3">
                                            <p>
                                              <b>Amount </b>
                                            </p>
                                            <div class="form-line">
                                                <input type="text" name="pendingAmt" class="form-control" value="<?php if(isset($bills))
                                                        {
                                                          echo $bills[0]['pendingAmt']; 
                                                        }
                                                        ?>" readonly>           
                                            </div>   
                                        </div>  

                                    </div>
                                    
                                    <div class="col-md-3"></div>
                                </div> -->
                            </div>
                            <div class="col-md-12">
                                    <div class="row clearfix">
                                        <div class="col-md-12">
                                            <?php
                                            if(isset($bills))
                                              {
                                                $id=$bills[0]['id'];
                                              }
                                            ?>

                                            <?php
                                                $designation = ($this->session->userdata[$projectSessionName]['designation']);
                                                if($designation=='cashier'){


                                            ?>
                                            <a  class="iframe" href="<?php echo base_url().'index.php/NonAllocationBillsController/Cash/'.$id?>">
                                                <button type="submite" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">money</i> 
                                                    <span class="icon-name">
                                                    Cash
                                                    </span>
                                                </button>
                                            </a>
                                            <?php
                                                }else{?>
                                                    <a  class="iframe" href="<?php echo base_url().'index.php/NonAllocationBillsController/Cash/'.$id?>">
                                                <button type="submite" class="btn btn-primary m-t-15 waves-effect" disabled>
                                                    <i class="material-icons">money</i> 
                                                    <span class="icon-name">
                                                    Cash
                                                    </span>
                                                </button>
                                            </a>

                                            <?php    
                                                }
                                            ?>
                                             &nbsp &nbsp &nbsp  &nbsp &nbsp &nbsp &nbsp &nbsp
                                            <a  class="iframe" href="<?php echo base_url().'index.php/NonAllocationBillsController/Cheque/'.$id?>">
                                                <button type="submit" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">rate_review</i> 
                                                    <span class="icon-name">
                                                    Cheque
                                                    </span>
                                                </button>
                                            </a>
                                            &nbsp &nbsp &nbsp  &nbsp &nbsp &nbsp &nbsp &nbsp  
                                         <a  class="iframe" href="<?php echo base_url().'index.php/NonAllocationBillsController/SrFSR/'.$id?>">
                                            <button type="submit" class="btn btn-primary m-t-15 waves-effect">
                                                <i class="material-icons">crop_free</i> 
                                                <span class="icon-name">
                                                SR/SFR
                                                </span>
                                            </button>
                                        </a>
                                             &nbsp &nbsp &nbsp  &nbsp &nbsp &nbsp &nbsp &nbsp 
                                            <a  class="iframe" href="<?php echo base_url().'index.php/NonAllocationBillsController/CashDiscount/'.$id?>">
                                                <button type="submit" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">group_work</i> 
                                                    <span class="icon-name">
                                                    CD
                                                    </span>
                                                </button>
                                            </a>
                                            
                                            &nbsp &nbsp &nbsp  &nbsp &nbsp &nbsp &nbsp &nbsp 
                                             <a  class="iframe" href="<?php echo base_url().'index.php/NonAllocationBillsController/Debit/'.$id?>">
                                             <button type="submite" class="btn btn-primary m-t-15 waves-effect">
                                                <i class="material-icons">credit_card</i> 
                                                <span class="icon-name">
                                                Debit
                                                </span>
                                            </button>
                                        </a>
                                        
                                             &nbsp &nbsp &nbsp  &nbsp &nbsp &nbsp &nbsp &nbsp
                                            <button onclick='parent.$.colorbox.close(); return false;' type="button" class="btn btn-primary m-t-15 waves-effect">
                                                <i class="material-icons">cancel</i> 
                                                <span class="icon-name">
                                                Cancel
                                                </span> 
                                            </button> 
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
    <script type="text/javascript">
        function showDiv1(){
            getSelectValue = document.getElementById("test1").value;
            if(getSelectValue == "Office Adjustment"){
              document.getElementById("accounting").style.display="block";
            }else{
              document.getElementById("accounting").style.display="none"; 
            }
        
      }
    </script> 