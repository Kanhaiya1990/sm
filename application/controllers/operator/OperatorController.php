<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OperatorController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('OperatorModel');
        $this->load->library('session');
        date_default_timezone_set('Asia/Kolkata');
        ini_set('memory_limit', '-1');

        if(isset($this->session->userdata['codeKeyData'])) {
			$this->projectSessionName= $this->session->userdata['codeKeyData']['codeKeyValue'];
		}else{
			$this->load->view('LoginView');
		}
    }

    public function pendingSr(){
        $data['company']=$this->OperatorModel->getdata('company');
        $cmp=trim($this->input->post('cmp'));
        $cmpName="";

        if($cmp=="" || $cmp=="General"){
            $data['cmpName']=$cmp;
            $data['pendingSr']=$this->OperatorModel->getDetailsWithDate('allocation_sr_details');
            $data['officePendingSr']=$this->OperatorModel->getOfficeDetailsWithDate('allocations_officebills');
            $this->load->view('operator/newPendingSrView',$data);
        }else{
            $data['cmpName']=$cmp;
            $data['pendingSr']=$this->OperatorModel->getDetailsWithDateCompany('allocation_sr_details',$cmp);
            $data['officePendingSr']=$this->OperatorModel->getOfficeDetailsWithDateCompany('allocations_officebills',$cmp);
            $this->load->view('operator/newPendingSrView',$data);
        }
    }

    public function pendingAllocationSrWithDate($date){
        $data['title']="Pending SR/FSR for ".date("d-M-Y", strtotime($date));
        $data['date']=$date;
        $data['fsrDetails']=$this->OperatorModel->getAllocationFsrDetailsDate('bills',$date);
       
        $data['srDetails']=$this->OperatorModel->getAllocationSrDetailsDate('allocation_sr_details',$date);
        $data['officeSrDetails']=$this->OperatorModel->getOfficeAllocationSrDetailsDate('allocations_officebills',$date);
        // print_r($data['officeSrDetails']);exit;

        if(!empty($data['srDetails']) || !empty($data['fsrDetails']) || !empty($data['officeSrDetails'])){
            $this->load->view('operator/newPendingSrDetailsView',$data);
        }else{
            redirect('operator/OperatorController/pendingSr');
        }
        
    }

    public function OldpendingSr(){
        $data['company']=$this->OperatorModel->getdata('company');
        $cmp=trim($this->input->post('cmp'));
        $cmpName="";

        if($cmp=="" || $cmp=="General"){
            $data['cmpName']=$cmp;
            $data['pendingSr']=$this->OperatorModel->getDetails('allocation_sr_details');

            if(!empty($data['pendingSr'])){
                $no=0;
                foreach($data['pendingSr'] as $dt){
                    $salesmanName='';
                    $retailerName='';
                    $retailerCode='';
                    $routeName='';
                    $salesmanAllocation= $this->OperatorModel->getAllocationSalesman('allocationsbills',$dt['allocation_Id']);
                    // print_r($salesmanAllocation);exit;
                    if(!empty($salesmanAllocation)){
                      $sname=array_unique($salesmanAllocation,SORT_REGULAR);
                      // print_r($sname);exit;
                      foreach($sname as $name){
                          $salesmanName=$name['salesman'].', '.$salesmanName;
                          // $retailerName=$name['retailerName'].', '.$retailerName;
                          // $retailerCode=$name['retailerCode'].', '.$retailerCode;
                      }
                    }
                   
                   $rtName=explode(",",rtrim($dt['rcode'],','));
                   for($i=0;$i<count($rtName);$i++){
                        $routes=$this->OperatorModel->getRouteName($rtName[$i]);
                        if(!empty($routes)){
                            $routeName=$routeName.' '.$routes[0]['name'].', ';
                        }
                   }
                   $data['pendingSr'][$no]['salesman']=rtrim($salesmanName,', ');
                   $data['pendingSr'][$no]['routeName']=rtrim($routeName,', ');
                   // $data['pendingSr'][$no]['retailerCode']=rtrim($retailerCode,', ');
                   // $data['pendingSr'][$no]['retailerName']=rtrim($retailerName,', ');
                   $no++;
                }
            }

            $officeSr=$this->OperatorModel->getOfficeSr('allocation_sr_details');
            $data['countOffice']=count($officeSr);
           
            $this->load->view('operator/pendingSrView',$data);
        }else{
            $data['cmpName']=$cmp;
            $data['pendingSr']=$this->OperatorModel->getDetailsWithCompany('allocation_sr_details',$cmp);

            if(!empty($data['pendingSr'])){
                $no=0;
                foreach($data['pendingSr'] as $dt){
                    $salesmanName='';
                    $retailerName='';
                    $retailerCode='';
                    $routeName='';
                    $salesmanAllocation= $this->OperatorModel->getAllocationSalesman('allocationsbills',$dt['allocation_Id']);
                    // print_r($salesmanAllocation);exit;
                    if(!empty($salesmanAllocation)){
                      $sname=array_unique($salesmanAllocation,SORT_REGULAR);
                      // print_r($sname);exit;
                      foreach($sname as $name){
                          $salesmanName=$name['salesman'].', '.$salesmanName;
                          // $retailerName=$name['retailerName'].', '.$retailerName;
                          // $retailerCode=$name['retailerCode'].', '.$retailerCode;
                      }
                    }
                   
                   $rtName=explode(",",rtrim($dt['rcode'],','));
                   for($i=0;$i<count($rtName);$i++){
                        $routes=$this->OperatorModel->getRouteName($rtName[$i]);
                        if(!empty($routes)){
                            $routeName=$routeName.' '.$routes[0]['name'].', ';
                        }
                   }
                   $data['pendingSr'][$no]['salesman']=rtrim($salesmanName,', ');
                   $data['pendingSr'][$no]['routeName']=rtrim($routeName,', ');
                   // $data['pendingSr'][$no]['retailerCode']=rtrim($retailerCode,', ');
                   // $data['pendingSr'][$no]['retailerName']=rtrim($retailerName,', ');
                   $no++;
                }
            }

            $officeSr=$this->OperatorModel->getOfficeSr('allocation_sr_details');
            $data['countOffice']=count($officeSr);
            // echo $data['countOffice'];exit;
            $this->load->view('operator/pendingSrView',$data);
        }
        
    }

    public function pendingCollection(){
        $data['company']=$this->OperatorModel->getdata('company');
        $cmp=trim($this->input->post('cmp'));
        $cmpName="";
        if($cmp=="" || $cmp=="General"){
            $data['cmpName']=$cmp;
            $data['pendingOfficeCollection']=$this->OperatorModel->getPendingOfficeCollectionDetails('allocations_officeadjustment');
            $data['srAllocationData']=$this->OperatorModel->getPendingSrDetails('allocation_sr_details');

            $data['pendingCollection']=$this->OperatorModel->getPendingCollectionDetails('billpayments');
            $officeSr=$this->OperatorModel->getPendingCollectionWithoutAllocation('billpayments');
            $data['countOffice']=count($officeSr);
           
            $this->load->view('operator/pendingCollectionView',$data);
        }else{
            $data['cmpName']=$cmp;
            $data['pendingOfficeCollection']=$this->OperatorModel->getCompanyPendingOfficeCollectionDetails('allocations_officeadjustment',$cmp);
            $data['srAllocationData']=$this->OperatorModel->getCompanyPendingSrDetails('allocation_sr_details',$cmp);
            $data['pendingCollection']=$this->OperatorModel->getPendingCollectionDetailsWithCompany('billpayments',$cmp);
            $officeSr=$this->OperatorModel->getPendingCollectionWithoutAllocation('billpayments');
            $data['countOffice']=count($officeSr);
            // echo $data['countOffice'];exit;
            $this->load->view('operator/pendingCollectionView',$data);
        }
        
    }

    public function srPrint(){
        $data['pendingGstSr']=$this->OperatorModel->getGstBillSrDetails('allocation_sr_details');
        $this->load->view('operator/gstSrReportView',$data);
    }

    public function fsrPrint(){
        // $bills=$this->OperatorModel->getBillsData('bills');
        // $data['fsr']=array();
        // if(!empty($bills)){
        //     foreach($bills as $bill){
        //         $srSum=$this->OperatorModel->getQtySumByBill($bill['id']);
        //         $srAcceptSum=$this->OperatorModel->srAcceptedByBill($bill['id']);
        //         if($srSum==$srAcceptSum){
        //             array_push($data['fsr'],$bill);
        //         }
        //     }
        // }
        $data['fsr']=$this->OperatorModel->getFsrBillsData('bills');
        $this->load->view('operator/fsrBillsView',$data);
    }

    

    public function pendingAllocationSr($id){
        $data['allocationId']=$id;
        $data['title']="Pending SR Details";
        $data['billCount']=$this->OperatorModel->getCountForBills($id);
        $data['allDetails']=$this->OperatorModel->getAllocationDetails('allocations',$id);
        // print_r($data['allDetails']);exit;
        $data['srDetails']=$this->OperatorModel->getAllocationSrDetails('allocation_sr_details',$id);
        $this->load->view('operator/pendingSrDetailsView',$data);
    }



    public function pendingOfficeSr(){
        $data['allocationId']=0;
        $data['title']="Office SR Details";
        $data['allDetails']=array();
        $data['billCount']=$this->OperatorModel->getCountForBills(0);
        $data['srDetails']=$this->OperatorModel->getOfficeSr('allocation_sr_details');
        $this->load->view('operator/pendingSrDetailsView',$data);
    }

    public function pendingOfficeCollection(){
        $data['allocationId']=0;
        $data['title']="Pending Office Collection Details";
        $data['page']="Office Collection";
        $data['allDetails']=array();
        $data['billCount']=$this->OperatorModel->getCountPendingOfficeCollection('billpayments');
        $data['collectionDetails']=$this->OperatorModel->getPendingOfficeCollection('billpayments');

        $cash=0;
        $cheque=0;
        $neft=0;
        $cd=0;
        $debit=0;
        $officeAdjAmt=0;
        $otherAdjAmt=0;
        foreach($data['collectionDetails'] as $dt){
            if($dt['paymentMode']=="Cash"){
                $cash=$cash+$dt['paidAmount'];
            }

            if($dt['paymentMode']=="Cheque"){
                $cheque=$cheque+$dt['paidAmount'];
            }

            if($dt['paymentMode']=="NEFT"){
                $neft=$neft+$dt['paidAmount'];
            }

            if($dt['paymentMode']=="CD"){
                $cd=$cd+$dt['paidAmount'];
            }

            if($dt['paymentMode']=="Debit To Employee"){
                $debit=$debit+$dt['paidAmount'];
            }

            if($dt['paymentMode']=="Office Adjustment"){
                $officeAdjAmt=$officeAdjAmt+$dt['paidAmount'];
            }

            if($dt['paymentMode']=="Other Adjustment"){
                $otherAdjAmt=$otherAdjAmt+$dt['paidAmount'];
            }
        }

        $data['finalTotal']=$cash+$cheque+$neft+$debit+$cd+$officeAdjAmt+$otherAdjAmt;
        $data['cashTotal']=$cash;
        $data['chequeTotal']=$cheque;
        $data['neftTotal']=$neft;
        $data['debitTotal']=$debit;
        $data['cdTotal']=$cd;
        $data['officeAdjAmtTotal']=$officeAdjAmt;
        $data['otherAdjAmtTotal']=$otherAdjAmt;
        $this->load->view('operator/pendingCollectionDetailView',$data);
    }

    public function pendingOfficeCollectionByAllocation($id){
        $data['allocationId']=$id;
        $data['title']="Pending Allocation Collection Details";
        $data['page']="Allocation Collection";
        $data['billCount']=$this->OperatorModel->getCountPendingOfficeCollectionByAllocation('billpayments',$id);
        // print_r($data['billCount']);exit;
        $data['allDetails']=$this->OperatorModel->getAllocationDetails('allocations',$id);
        $data['collectionDetails']=$this->OperatorModel->getPendingOfficeCollectionByAllocation('billpayments',$id);
        // print_r($data['allDetails']);exit;
        
        $cash=0;
        $cheque=0;
        $neft=0;
        $cd=0;
        $debit=0;
        $officeAdjAmt=0;
        $otherAdjAmt=0;
        foreach($data['collectionDetails'] as $dt){
            if($dt['paymentMode']=="Cash"){
                $cash=$cash+$dt['paidAmount'];
            }

            if($dt['paymentMode']=="Cheque"){
                $cheque=$cheque+$dt['paidAmount'];
            }

            if($dt['paymentMode']=="NEFT"){
                $neft=$neft+$dt['paidAmount'];
            }

            if($dt['paymentMode']=="CD"){
                $cd=$cd+$dt['paidAmount'];
            }

            if($dt['paymentMode']=="Debit To Employee"){
                $debit=$debit+$dt['paidAmount'];
            }

            if($dt['paymentMode']=="Office Adjustment"){
                $officeAdjAmt=$officeAdjAmt+$dt['paidAmount'];
            }

            if($dt['paymentMode']=="Other Adjustment"){
                $otherAdjAmt=$otherAdjAmt+$dt['paidAmount'];
            }
        }

        $data['finalTotal']=$cash+$cheque+$neft+$debit+$cd+$officeAdjAmt+$otherAdjAmt;
        $data['cashTotal']=$cash;
        $data['chequeTotal']=$cheque;
        $data['neftTotal']=$neft;
        $data['debitTotal']=$debit;
        $data['cdTotal']=$cd;
        $data['officeAdjAmtTotal']=$officeAdjAmt;
        $data['otherAdjAmtTotal']=$otherAdjAmt;

        $srDetails=$this->OperatorModel->getSrInfo('allocation_sr_details',$id);
        $srArr=array();
        foreach($srDetails as $item){
            $billId=$item['billId'];
            $billInfo=$this->OperatorModel->load('bills',$billId);
            $billSr=$this->OperatorModel->getSrInfoDetails('allocation_sr_details',$id,$billId);
            $total=0;
            if(!empty($billSr)){
                foreach($billSr as $sr){
                    $actualSr= $sr['ac_qty'];
                    $rateSr= $sr['netAmount']/$sr['qty'];
                    $total=$total+ ($rateSr*$actualSr);
                }
                $srArr[]=array('billNo'=>$billInfo[0]['billNo'],'retailer'=>$billInfo[0]['retailerName'],'billDate'=>$billInfo[0]['date'],'mode'=>'SR','srTotal'=>$total);
            }
        }
        $data['allocationSr']=$srArr;
        $this->load->view('operator/pendingCollectionDetailView',$data);
    }

    public function pendingOfficeAdjustmentCollectionByAllocation($id){
        $data['allocationId']=$id;
        $data['title']="Pending Office Allocation Collection Details";
        $data['page']="Office Allocation Collection";
        $data['billCount']=$this->OperatorModel->getCountOfficeAdjustmentCollectionDetails('allocations_officebills',$id);
        $data['allDetails']=$this->OperatorModel->load('allocations_officeadjustment',$id);
        $data['collectionDetails']=$this->OperatorModel->getOfficeAdjustmentCollectionDetails('allocations_officebills',$id);

        // print_r($data['collectionDetails']);exit;

        $fsr=0;
        $clear=0;
        foreach($data['collectionDetails'] as $dt){
            if($dt['transactionType']=="cleared"){
                $clear=$clear+$dt['amount'];
            }

            if($dt['transactionType']=="fsr"){
                $fsr=$fsr+$dt['amount'];
            }
        }

        $data['finalTotal']=$fsr+$clear;
        $data['fsr']=$fsr;
        $data['clear']=$clear;

        $this->load->view('operator/pendingOfficeCollectionDetailView',$data);
    }

    // public function pendingOfficeSr(){
    //     $data['title']="Office SR Details";
    //     $data['srDetails']=$this->OperatorModel->getOfficeSr('allocation_sr_details');
    //     $this->load->view('operator/pendingSrDetailsView',$data);
    // }

    public function getPendingSr(){
        $allocationSrId=$this->input->post('allocationSrId');
        $allocationId=$this->input->post('allocationId');

        $data=array(
            'operatorStatus'=>1
        );
        $this->OperatorModel->update('allocation_sr_details',$data,$allocationSrId);

        $srDetails=$this->OperatorModel->getAllocationSrDetails('allocation_sr_details',$allocationId);
       
        $no=0;
        if(!empty($srDetails)){
            foreach ($srDetails as $data) 
            {
                $allocation_sr_id=$data['sr_id'];
                $no++; 
        ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $data['billNo']; ?></td>
                    <td><?php echo $data['prodName']; ?></td>
                    <td><?php echo $data['sr_qty']; ?></td>
                    <td><button onclick="submitStatus('<?php echo $allocation_sr_id; ?>');" class="btn btn-xs bg-primary margin"><i class="material-icons">check</i></button></td>
               </tr>  
        <?php
            }
        }
    }

     public function getPendingFSR(){
        $billId=$this->input->post('billId');
        $allocationId=$this->input->post('allocationId');

        $data=array(
            'operatorStatus'=>1
        );
        $this->OperatorModel->updatePendingFSR('allocation_sr_details',$data,$billId,$allocationId);

        $srDetails=$this->OperatorModel->getAllocationSrDetails('allocation_sr_details',$allocationId);
       
        $no=0;
        if(!empty($srDetails)){
            foreach ($srDetails as $data) 
            {
                $allocation_sr_id=$data['sr_id'];
                $no++; 
        ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $data['billNo']; ?></td>
                    <td><?php echo $data['prodName']; ?></td>
                    <td><?php echo $data['sr_qty']; ?></td>
                    <td><button onclick="submitStatus('<?php echo $allocation_sr_id; ?>');" class="btn btn-xs bg-primary margin"><i class="material-icons">check</i></button></td>
               </tr>  
        <?php
            }
        }else{
            echo "<tr><td colspan='5'>No data available in table</td></tr>";
        }
    }


     public function getGSTPendingSr(){
        $allocationSrId=$this->input->post('allocationSrId');

        $data=array(
            'isGstBill'=>1
        );
        $this->OperatorModel->update('allocation_sr_details',$data,$allocationSrId);
        $pendingGstSr=$this->OperatorModel->getGstBillSrDetails('allocation_sr_details');
                                
        $no=0;
        if(!empty($pendingGstSr)){

            foreach ($pendingGstSr as $data) 
            {

                $allocation_sr_id=$data['sr_id'];
                $no++; 

                $sumQty=$this->OperatorModel->getQtySumByBill($data['billId']);
                $sumSRQty=$this->OperatorModel->getSrQtySumByBill($data['billId'],$data['sr_allocationId']);
                if($sumQty!=$sumSRQty){
                    if($data['sr_qty'] >0){
   
    ?>
        <tr>
            <td><?php echo $no; ?></td>
            <td><?php echo $data['billNo']; ?></td>
            <td><?php echo $data['prodName']; ?></td>
            <td><?php echo $data['sr_qty']; ?></td>
           
            <td><button onclick="submitStatus('<?php echo $allocation_sr_id; ?>');" class="btn btn-xs bg-primary margin"><i class="material-icons">check</i></button></td>
            
       </tr>  
    <?php          }
                }
            }
        }

    }

    public function savePendingSr(){
        $selValue=$this->input->post('selValue');
        if(!empty($selValue)){
            foreach($selValue as $sel){
                $splitArr=explode(':',$sel);
                if(count($splitArr)==5){
                    $srId=$splitArr[0];
                    $allocationId=$splitArr[1];
                    $billId=$splitArr[2];
                    $type=$splitArr[3];

                    $allocationType=$splitArr[4];

                    if($allocationType==="OPN"){
                        if($type=="FSR"){
                            $data=array(
                                'operatorStatus'=>1
                            );
                            $this->OperatorModel->updatePendingFSR('allocation_sr_details',$data,$billId,$allocationId);
                        }else{
                            $data=array(
                                'operatorStatus'=>1
                            );
                            $this->OperatorModel->update('allocation_sr_details',$data,$srId);
                        }
                    }else if($allocationType==="OFC"){
                        $data=array(
                            'operatorApproval'=>1
                        );
                        $this->OperatorModel->update('allocations_officebills',$data,$srId);
                    }
                }
            }
        }
        
    }

    //SR amount approval
    public function saveSrApproval($id){
        $updateData=array('allocationAmountStatus'=>1);
        $this->OperatorModel->updateAllocationSr('allocation_sr_details',$updateData,$id);
        return redirect("operator/OperatorController/pendingCollection");
    }

    //save operator approval
    public function savePendingCollection(){
        $selValue=$this->input->post('selValue');
        if(!empty($selValue)){
            foreach($selValue as $sel){
                $pendingPaymentId=$sel;
                $data=array(
                    'operatorApproval'=>1
                );
                $this->OperatorModel->update('billpayments',$data,$pendingPaymentId);
            }
        }
    }

    //save operator approval for office allocation
    public function savePendingOfficeCollection(){
        $selValue=$this->input->post('selValue');
        if(!empty($selValue)){
            foreach($selValue as $sel){
                $pendingPaymentId=$sel;
                $data=array(
                    'operatorApproval'=>1
                );
                $this->OperatorModel->update('allocations_officebills',$data,$pendingPaymentId);
            }
        }
    }

     public function savePendingGstSr(){
        $selValue=$this->input->post('selValue');
        if(!empty($selValue)){
            foreach($selValue as $sel){
                $splitArr=explode(':',$sel);
                if(count($splitArr)==4){
                    $srId=$splitArr[0];
                    $allocationId=$splitArr[1];
                    $billId=$splitArr[2];
                    $type=$splitArr[3];

                    if($type=="FSR"){
                        $data=array(
                            'isGstBill'=>1
                        );
                        $this->OperatorModel->updatePendingFSR('allocation_sr_details',$data,$billId,$allocationId);
                    }else{
                        $data=array(
                            'isGstBill'=>1
                        );
                        $this->OperatorModel->update('allocation_sr_details',$data,$srId);
                    }
                }
                
                
            }
        }
        
    }

    
}
?>
