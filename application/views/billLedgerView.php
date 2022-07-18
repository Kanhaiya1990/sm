<?php $this->load->view('/layouts/commanHeader'); ?>
 
<style type="text/css">
    @media screen and (min-width: 1100px) {
        .modal-dialog {
          width: 1100px; /* New width for default modal */
        }
        .modal-sm {
          width: 400px; /* New width for small modal */
        }
    }

    @media screen and (min-width: 1100px) {
        .modal-lg {
          width: 1100px; /* New width for large modal */
        }
    }

</style>
<script src="<?php echo base_url('assets/js/pages/ui/tooltips-popovers.js');?>"></script>

<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br>
<section class="col-md-12 box">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                            <a class="btn btn-xs btn-primary" href="javascript:window.history.go(-1);"><i class="material-icons">arrow_back</i></a>
                          <h4 style="color:#050A30"><center><?php if(!empty($bills)){ echo $bills[0]['retailerName']; } ?></center>
                           
                          </h4>
                            <!--<h2 align="right">
                            <span>
                               <a class="btn btn-xs btn-primary" href="javascript:void();"><i class="material-icons">touch_app</i></a>
                             &nbsp;&nbsp;<a class="btn btn-xs btn-primary" href="javascript:window.history.go(-1);"><i class="material-icons">arrow_back</i></a></span>
                            </h2>-->
                    </div>
                    <div class="body">
                        <div class="row m-t-20">
                            <div class="container">
                            <!-- <div class="col-md-12"> -->
                                
                                    <div class="col-md-4">
                                        <h5 style="color:#000000">Bill No. : <span style="color:#050A30"><?php if(!empty($bills)){ echo $bills[0]['billNo']; } ?></span></h5>
                                        <h5 style="color:#000000">Bill Date : 
                                          <span style="color:#050A30"><?php 
                                          if(!empty($bills)){
                                              $dt=date_create($bills[0]['date']);
                                              $date = date_format($dt,'d-M-Y'); 
                                              echo $date;
                                          }
                                          ?>
                                          </span>
                                        </h5>
                                    </div>

                                    <div class="col-md-4">
                                        <h5 style="color:#000000">Bill Amount : <span style="color:#050A30"><?php if(!empty($bills)){ echo $bills[0]['netAmount']; } ?></span>
                                        <h5 style="color:#000000">Salesman : <span style="color:#050A30"><?php if(!empty($bills)){ echo $bills[0]['salesman']; } ?></span>
                                    </div>
                                

                                    <div class="col-md-4">
                                        <h5 style="color:#000000">Retailer Code : <span style="color:#050A30"><?php if(!empty($bills)){ echo $bills[0]['retailerCode']; } ?></span>
                                        <h5 style="color:#000000">Route : <span style="color:#050A30"><?php if(!empty($bills)){ echo $bills[0]['routeName']; } ?></span>
                                    </div>
                                    <hr />
                                <!-- </div> -->
                            <div class="col-md-12">
                                <table style="font-size: 12px" class="table table-bordered table-striped table-hover" data-page-length='100'>
                                    <thead>
                                         <tr>
                                            <th>S. No.</th>
                                           
                                            <th>Employee</th>
                                            <th>Date</th>
                                            <th>Allocation</th>
                                            <th>Transaction Type</th>
                                            <th><span class="pull-right">CR</span></th>
                                            <th><span class="pull-right">DR</span></th>
                                            <th><span class="pull-right">Balance</span></th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                   
                                    <tbody>
                                    <?php 
                                        $no=0;
                                        $total=0;
                                        $srTotal=0;
                                        if(!empty($bills)){
                                            $billTotal=0;
                                            foreach($bills as $data){
                                                $no++;
                                                $billTotal=$data['netAmount'];
                                    ?>      
                                                <tr>
                                                    <td><?php echo $no;?></td>
                                                    <td class="text-left"><?php echo $data['salesman'];?></td>
                                                    <td><?php echo date('d-M-Y',strtotime($data['date']));?></td>
                                                    <td class="text-right"></td>
                                                    <td class="text-left">New Bill</td>
                                                    
                                                    <td class="text-right"></td>
                                                    <td class="text-right""><?php echo number_format($data['billNetAmount']);?></td>
                                                    <td class="text-right"><?php echo number_format($data['billNetAmount']);?></td>
                                                    <td class="text-right"></td>
                                                </tr>

                                    <?php       
                                                if($data['creditAdjustment'] >0 ){ 
                                                    $no++;
                                    ?>
                                                <tr>
                                                    <td><?php echo $no;?></td>
                                                    <td class="text-left"><?php echo $data['salesman'];?></td>
                                                    <td><?php echo date('d-M-Y',strtotime($data['date']));?></td>
                                                    <td class="text-right"></td>
                                                    <td class="text-right">Credit Adjustment</td>
                                                    
                                                    <td class="text-right"><?php echo number_format($data['creditAdjustment']);?></td>
                                                    <td class="text-right"></td>
                                                    <td class="text-right"><?php echo number_format($data['netAmount']);?></td>
                                                    <td class="text-right"></td>
                                                </tr>
                                    <?php 
                                                }
                                            }

                                            if(!empty($billHistory)){
                                                foreach($billHistory as $item){
                                                    if($item['transactionStatus'] !=""){ 
                                                    $allocationDetail="";
                                                    $allocationCode="";
                                                    if($item['allocationId'] >0){
                                                        $allocationInfo=$this->AllocationByManagerModel->load('allocations',$item['allocationId']);
                                                        if(!empty($allocationInfo)){
                                                            $allocationDetail='Added in allocation No : '.$allocationInfo[0]['allocationCode'];
                                                            $allocationCode=$allocationInfo[0]['allocationCode'];
                                                        }
                                                    }
                                                    $officeAllocationData="";
                                                    $officeAllocationCode="";
                                                    $officeAllocationRemark="";
                                                    if($item['officeAllocationId'] >0){
                                                        $allocationInfoData=$this->AllocationByManagerModel->load('allocations_officeadjustment',$item['officeAllocationId']);
                                                        // echo "je";print_r($allocationInfoData);
                                                        if(!empty($allocationInfoData)){
                                                            $officeAllocationData='Added in allocation No : '.$allocationInfoData[0]['allocationCode'];
                                                            $officeAllocationCode=$allocationInfoData[0]['allocationCode'];
                                                            $officeAllocationRemark=$allocationInfoData[0]['remark'];;
                                                        }
                                                    }
                                                    
                                                    $no++;
                                                    if($item['transactionMode']=='dr'){
                                                        $billTotal=$billTotal-abs($item['transactionAmount']);
                                                    }else{
                                                        $billTotal=$billTotal+abs($item['transactionAmount']);
                                                    }
                                                    // $billTotal=$billTotal-$item['transactionAmount'];
                                    ?>
                                                <tr>
                                                    <td><?php echo $no;?></td>
                                                    <td>
                                                    <?php 
                                                        if($item['empName1'] != ""){
                                                            echo $item['empName1'].' ';
                                                        }else{
                                                            echo $item['empName2'];
                                                        }
                                                    ?>
                                                    </td>
                                                    <td><?php echo date('d-M-Y',strtotime($item['transactionDate']));?></td>
                                                    <td class="text-left">
                                                    <?php 
                                                    $alId=$item['allocationId'];
                                                    if($item['allocationId']>0){
                                                        $url= base_url()."index.php/AllocationByManagerController/CloseCompleteAllocation/".$alId;
                                                        if($item['allocationId'] >0){
                                                            echo "<a href='".$url."' target='_blank'>".$allocationCode."</a>";
                                                        }else{
                                                            echo '';
                                                        }
                                                    }

                                                    if($item['officeAllocationId']>0){
                                                        $url= base_url()."index.php/OfficeAllocationController/closedAllocationInfo/".$item['officeAllocationId'];
                                                        if($item['officeAllocationId'] >0){
                                                            echo "<a href='".$url."' target='_blank'>".$officeAllocationCode."</a>";
                                                        }else{
                                                            echo '';
                                                        }
                                                    }
                                                    
                                                    ?>
                                                    
                                                   </td>
                                                   <td class="text-left">
                                                   <?php 
                                                        if($item['transactionStatus']=="Allocated" || $item['transactionStatus']=="Added to Allocation" ||  $item['transactionStatus']=="Create new allocation"){
                                                            if($allocationDetail != ""){
                                                                echo $allocationDetail;
                                                            }else if($officeAllocationData !=""){
                                                                echo $officeAllocationData;
                                                             
                                                            }else{
                                                                echo $item['transactionStatus'];
                                                            }
                                                        }else{
                                                            echo $item['transactionStatus'];
                                                        }
                                                    ?>
                                                   </td>
                                                    <td class="text-right">
                                                     <?php 
                                                        if($item['transactionMode']=='dr'){
                                                            if($item['transactionStatus']=="Allocated" || $item['transactionStatus']=="Signed" || $item['transactionStatus']=="Resend" || $item['transactionStatus']=="Lost Bill" || $item['transactionStatus']=="Lost Cheque"){
                                                                echo '';
                                                            }else{
                                                                echo number_format(abs($item['transactionAmount']));
                                                            }
                                                        }
                                                    ?>
                                                    
                                                    </td>
                                                    <td class="text-right">
                                                     <?php 
                                                        if($item['transactionMode']=='cr'){
                                                            if($item['transactionStatus']=="Allocated" || $item['transactionStatus']=="Signed" || $item['transactionStatus']=="Resend" || $item['transactionStatus']=="Lost Bill"){
                                                                echo '';
                                                            }else{
                                                                echo number_format(abs($item['transactionAmount']));
                                                            }
                                                        }
                                                    ?>
                                                    </td>
                                                    <td class="text-right"><?php echo number_format($billTotal);?></td>
                                                    <td class="text-left">
                                                    <?php
                                                        if($item['transactionStatus']=='CD'){
                                                            $cdRemark=$this->AllocationByManagerModel->getCdRemark('bill_remark_history',$item['transactionAmount'],$item['billId']);
                                                            // print_r($cdRemark);
                                                            if(!empty($cdRemark)){
                                                                echo $cdRemark[0]['remark'];
                                                            }
                                                        }
                        
                                                        if($item['transactionStatus']=='Office Adjustment'){
                                                            $officeRemark=$this->AllocationByManagerModel->getCdRemark('bill_remark_history',$item['transactionAmount'],$item['billId']);
                                                            // print_r($cdRemark);
                                                            if(!empty($officeRemark)){
                                                                echo $officeRemark[0]['remark'];
                                                            }
                                                        }
                        
                                                        if($item['transactionStatus']=='Other Adjustment'){
                                                            $otherRemark=$this->AllocationByManagerModel->getCdRemark('bill_remark_history',$item['transactionAmount'],$item['billId']);
                                                            // print_r($cdRemark);
                                                            if(!empty($otherRemark)){
                                                                echo $otherRemark[0]['remark'];
                                                            }
                                                        }
                        
                                                        if($item['transactionStatus']=='Emp Delivery'){
                                                            $officeRemark=$this->AllocationByManagerModel->getCdRemark('bill_remark_history',$item['transactionAmount'],$item['billId']);
                                                            // print_r($cdRemark);
                                                            if(!empty($officeRemark)){
                                                                echo $officeRemark[0]['remark'];
                                                            }
                                                        }

                                                        
                        
                        
                                                        

                                                        if($item['transactionStatus']=="Cheque" || $item['transactionStatus']=="Cheque Bounce" || $item['transactionStatus']=="Cheque Bounce Penalty" || $item['transactionStatus']=="Lost Cheque"){
                                                            $bills=$this->AllocationByManagerModel->getBillHistoryByBillId('billpayments',$item['transactionAmount'],$item['transactionStatus'],$item['billId'],$item['allocationId'],$item['officeAllocationId']);
                                                            if(!empty($bills)){
                                                                foreach($bills as $data){
                                                                    $chequeDate=date_create($data['chequeDate']);
                                                                    $chequeCreatedDate = date_format($chequeDate,'d-M-Y');

                                                                    $neftDate=date_create($data['neftDate']);
                                                                    $neftCreatedDate = date_format($neftDate,'d-M-Y');

                                                                    if($data['paymentMode']=='Cheque'){
                                    
                                                                        if($data['isLostStatus']==1){
                                                                            echo "Not Received";
                                                                        }else if($data['isLostStatus']==2 && $data['chequeStatus']=='New'){
                                                                            echo "Received, but not banked";
                                                                        }else if($data['isLostStatus']==2 && $data['chequeStatus']=='Banked'){
                                                                            echo "Banked, But Not Cleared";
                                                                        }else if($data['chequeStatus']=='Bounced' || $data['chequeStatus']=='Bounced&Returned'){
                                                                            echo "Bounced";
                                                                        }else if($data['isLostStatus']==2 && $data['chequeStatus']=='Cleared'){
                                                                            echo "Cleared";
                                                                        }
                                                                    }

                                                                   
                                
                                                                    if($data['isLostStatus']==2 && $data['chequeStatus']==''){
                                                                        echo "Received but not saved ";
                                                                    }else if($data['isLostStatus']==2 && $data['chequeStatus']=='New'){
                                                                        echo ", Cheque No. ".$data['chequeNo'].' dated '.$chequeCreatedDate.' Bank '.$data['chequeBank'];
                                                                    }else if($data['isLostStatus']==2 && $data['chequeStatus']=='Banked'){
                                                                        echo ", Cheque No. ".$data['chequeNo'].' dated '.$chequeCreatedDate.' Bank '.$data['chequeBank'];
                                                                    }else if($data['isLostStatus']==2 && $data['chequeStatus']=='Bounced'){
                                                                        echo ", Cheque No. ".$data['chequeNo'].' dated '.$chequeCreatedDate.' Bank '.$data['chequeBank'];
                                                                    }else if($data['isLostStatus']==2 && $data['chequeStatus']=='Cleared'){
                                                                        echo ", Cheque No. ".$data['chequeNo'].' dated '.$chequeCreatedDate.' Bank '.$data['chequeBank'];
                                                                    }
                                                                    // $data['bills']=$this->AllocationByManagerModel->load('bills',$id);

                                                                    
                                                                }
                                                                
                                                            }
                                                            echo ' '.$item['remark'];
                                                            
                                                        }else if($item['transactionStatus']=="NEFT" || $item['transactionStatus']=="Pending NEFT" || $item['transactionStatus']=="NEFT not Received"){
                                                            $bills=$this->AllocationByManagerModel->getBillHistoryByBillId('billpayments',$item['transactionAmount'],$item['transactionStatus'],$item['billId'],$item['allocationId'],$item['officeAllocationId']);
                                                            if(!empty($bills)){
                                                                foreach($bills as $data){
                                                                    $chequeDate=date_create($data['chequeDate']);
                                                                    $chequeCreatedDate = date_format($chequeDate,'d-M-Y');

                                                                    $neftDate=date_create($data['neftDate']);
                                                                    $neftCreatedDate = date_format($neftDate,'d-M-Y');

                                                                    if($data['paymentMode']=='NEFT'){
                                                                        if($data['isLostStatus']==1){
                                                                            echo "Not Received";
                                                                        }else if($data['isLostStatus']==2 && $data['chequeStatus']==''){
                                                                            echo "Received,But not saved";
                                                                        }else if($data['isLostStatus']==2 && $data['chequeStatus']=='New'){
                                                                            echo "Received,But not cleared";
                                                                        }else if($data['isLostStatus']==2 && $data['chequeStatus']=='Received'){
                                                                            echo "Cleared";
                                                                        }else if($data['isLostStatus']==2 && $data['chequeStatus']=='Not Received'){
                                                                            echo "Rejected";
                                                                        }
                                                                    }
                                
                                                                    if($data['isLostStatus']==2 && $data['chequeStatus']=='New'){
                                                                        echo ", NEFT No. ".$data['neftNo'].' dated '.$neftCreatedDate;
                                                                    }else if($data['isLostStatus']==2 && $data['chequeStatus']=='Received'){
                                                                        echo ", NEFT No. ".$data['neftNo'].' dated '.$neftCreatedDate;
                                                                    }else if($data['isLostStatus']==2 && $data['chequeStatus']=='Not Received'){
                                                                        echo ", NEFT No. ".$data['neftNo'].' dated '.$neftCreatedDate;
                                                                    }
                                                                }
                                                            }
                                                        }else{
                                                                if($item['officeAllocationId'] >0){
                                                                    echo $officeAllocationRemark;
                                                                }else{
                                                                    echo $item['remark'];
                                                                }
                                                        }
                                                    ?>
                                                    </td>
                                                </tr>
                                    <?php
                                                }
                                            }

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
        </div>
    </div>
</section>
    
<?php $this->load->view('/layouts/footerDataTable'); ?>