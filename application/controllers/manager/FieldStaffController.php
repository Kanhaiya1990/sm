<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FieldStaffController extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('FieldStaffModel');
		$this->load->library('session');
        date_default_timezone_set('Asia/Kolkata');
        ini_set('memory_limit', '-1');

		if(isset($this->session->userdata['codeKeyData'])) {
			$this->projectSessionName= $this->session->userdata['codeKeyData']['codeKeyValue'];
			$this->baseUrl=$this->session->userdata['codeKeyData']['yourBaseUrl'];

            if($this->baseUrl=="http://localhost/smartdistributor/" || $this->baseUrl=="https://siainc.in/kiasales/" || $this->baseUrl=="https://siainc.in/staging_kiasales/"){

            }else{
                $this->load->helper('url');
                $url_parts = parse_url(current_url());
                $siteUrl=explode('/',$url_parts['path']);//current url path
        
                $baseUrl=explode('/',$this->baseUrl);//base url path
                
                $siteDistributorName=trim($siteUrl[2]);
                $baseDistributorName=trim($baseUrl[4]);
                
                if($siteDistributorName !="" && $baseDistributorName !=""){
                    if($siteDistributorName==$baseDistributorName){
                    //   
                    }else{
                    redirect($this->baseUrl.'index.php/UserAuthentication/randomlogout');
                    }
                }else{
                redirect($this->baseUrl.'index.php/UserAuthentication/randomlogout');
                }
            }
		}else{
			$this->load->view('LoginView');
		}
    }

	public function fieldStaffHisaab($id,$code){
		$data['allocationId']=$id;
		$data['allocationCode']=$code;

		// $id=$this->input->post('allcationID');
		$data['employee']=$this->FieldStaffModel->getdataEmployee('employee');
		$data['routeNames']=$this->FieldStaffModel->getRouteNames();
		$data['employeeNames']=$this->FieldStaffModel->getEmployeeNames();
		$data['billNos']=$this->FieldStaffModel->getBillNos();
		$data['bounceReturnCheques']=$this->FieldStaffModel->bouncedReturnCheques('billpayments');
		$data['deliverySlip']=$this->FieldStaffModel->deliverySlipBillNo();
		$data['pastBillNos']=$this->FieldStaffModel->getPastBills();
		
		$data['bills']=$this->FieldStaffModel->getAllocatedBills('bills',$id);
		$data['allocations']=$this->FieldStaffModel->load('allocations',$id);

		$data["current"]=array();
		$data["bounced"]=array();
		$data["pass"]=array();
		$data["slip"]=array();
		$count=0;
		$total=0;
		
		foreach ($data['bills'] as $items) {
			if($items['billType']=='allocatedbillCurrent'){
				$data['current']=$this->FieldStaffModel->getAllocatedBillsByType('bills',$id,'1');
			}else if(($items['billType']==='allocatedbillPass') || ($items['billType']==='adHocDeliveryBill') || ($items['billType']==='officeAdjustmentBill')){
				$data['pass']=$this->FieldStaffModel->getAllocatedPastBillsByType('bills',$id);
			}else if($items['billType']=='allocatedbillDS'){
				$data['slip']=$this->FieldStaffModel->getAllocatedBillsByType('bills',$id,'1');
			}else if($items['billType']=='allocatedbillBounce'){
				$data['bounced']=$this->FieldStaffModel->getAllocatedBillsByType('bills',$id,'1');
			}
		}

		//Total Allocated Bills
		$count=$count+count($data['current'])+count($data['pass'])+count($data['slip'])+count($data['bounced']);

		// For SR Bills Calculation
		$srBill=0;
		$netAmountTotal=0;
		$srBillTotal=0;

		// For Cash Bills Calculation
		$cashBill=0;
		$cashBillTotal=0;
		$creditTotal=0;
		

		// For Billed Status Calculation 
		$billedCount=0;
		$billedTotal=0;

		// For Resend Status Calculation
		$resendCount=0;
		$resendTotal=0;
		$chequeNeftTotal=0;

		$otherAdjTotal=0;
		//Total Allocated bills Amount Total : 
		for($i=0;$i<count($data['current']);$i++){
			$total=$total+$data['current'][$i]['pendingAmt'];
			$netAmountTotal=$netAmountTotal+$data['current'][$i]['netAmount'];

			if($data['current'][$i]['fsSrAmt'] != '0.00'){ 
				$srBillTotal=$srBillTotal+$data['current'][$i]['fsSrAmt'];
			}

			if($data['current'][$i]['fsCashAmt'] != '0.00'){
				$billedCount=$billedCount+1;
				$cashBillTotal=$cashBillTotal+$data['current'][$i]['fsCashAmt'];
			}

			if($data['current'][$i]['fsChequeAmt'] != '0.00'){
				$billedCount=$billedCount+1;
				$chequeNeftTotal=$chequeNeftTotal+$data['current'][$i]['fsChequeAmt'];
			}

			if($data['current'][$i]['fsOtherAdjAmt'] != '0.00'){
				$billedCount=$billedCount+1;
				$otherAdjTotal=$otherAdjTotal+$data['current'][$i]['fsOtherAdjAmt'];
			}

			if($data['current'][$i]['fsNeftAmt'] != '0.00'){
				$billedCount=$billedCount+1;
				$chequeNeftTotal=$chequeNeftTotal+$data['current'][$i]['fsNeftAmt'];
			}

			if($data['current'][$i]['pendingAmt'] != '0.00'){
				$creditTotal=$creditTotal+$data['current'][$i]['pendingAmt'];
			}

			if($data['current'][$i]['fsbillStatus'] == 'FSR'){
				$srBill=$srBill+1;
				$billedTotal=$billedTotal+$data['current'][$i]['pendingAmt'];
			}

			if($data['current'][$i]['fsbillStatus']=='Resend'){
				$resendCount=$resendCount+1;
				$resendTotal=$resendTotal+$data['current'][$i]['pendingAmt'];
			}
		}

		for($i=0;$i<count($data['pass']);$i++){
			$total=$total+$data['pass'][$i]['pendingAmt'];
			$netAmountTotal=$netAmountTotal+$data['pass'][$i]['netAmount'];

			if($data['pass'][$i]['fsSrAmt'] != '0.00'){
				$srBillTotal=$srBillTotal+$data['pass'][$i]['fsSrAmt'];
			}

			if($data['pass'][$i]['fsCashAmt'] != '0.00'){
				$billedCount=$billedCount+1;
				$cashBillTotal=$cashBillTotal+$data['pass'][$i]['fsCashAmt'];
			}

			if($data['pass'][$i]['fsChequeAmt'] != '0.00'){
				$billedCount=$billedCount+1;
				$chequeNeftTotal=$chequeNeftTotal+$data['pass'][$i]['fsChequeAmt'];
			}

			if($data['pass'][$i]['fsNeftAmt'] != '0.00'){
				$billedCount=$billedCount+1;
				$chequeNeftTotal=$chequeNeftTotal+$data['pass'][$i]['fsNeftAmt'];
			}

			if($data['pass'][$i]['fsOtherAdjAmt'] != '0.00'){
				$billedCount=$billedCount+1;
				$otherAdjTotal=$otherAdjTotal+$data['pass'][$i]['fsOtherAdjAmt'];
			}

			if($data['pass'][$i]['pendingAmt'] != '0.00'){
				$creditTotal=$creditTotal+$data['pass'][$i]['pendingAmt'];
			}

			if($data['pass'][$i]['fsbillStatus'] == 'Resend'){
				$resendCount=$resendCount+1;
				$resendTotal=$resendTotal+$data['pass'][$i]['pendingAmt'];
			}

			if($data['pass'][$i]['fsbillStatus'] == 'FSR'){
				$srBill=$srBill+1;
				$billedTotal=$billedTotal+$data['pass'][$i]['pendingAmt'];
			}
		}

		for($i=0;$i<count($data['slip']);$i++){
			$total=$total+$data['slip'][$i]['pendingAmt'];
			$netAmountTotal=$netAmountTotal+$data['slip'][$i]['netAmount'];

			if($data['slip'][$i]['fsSrAmt'] != '0.00'){
				$srBillTotal=$srBillTotal+$data['slip'][$i]['fsSrAmt'];
			}

			if($data['slip'][$i]['fsCashAmt'] != '0.00'){
				$billedCount=$billedCount+1;
				$cashBillTotal=$cashBillTotal+$data['slip'][$i]['fsCashAmt'];
			}

			if($data['slip'][$i]['fsChequeAmt'] != '0.00'){
				$billedCount=$billedCount+1;
				$chequeNeftTotal=$chequeNeftTotal+$data['slip'][$i]['fsChequeAmt'];
			}

			if($data['slip'][$i]['fsNeftAmt'] != '0.00'){
				$billedCount=$billedCount+1;
				$chequeNeftTotal=$chequeNeftTotal+$data['slip'][$i]['fsNeftAmt'];
			}

			if($data['slip'][$i]['fsOtherAdjAmt'] != '0.00'){
				$billedCount=$billedCount+1;
				$otherAdjTotal=$otherAdjTotal+$data['slip'][$i]['fsOtherAdjAmt'];
			}


			if($data['slip'][$i]['pendingAmt'] != '0.00'){
				$creditTotal=$creditTotal+$data['slip'][$i]['pendingAmt'];
			}

			if($data['slip'][$i]['fsbillStatus'] == 'FSR'){
				$srBill=$srBill+1;
				$billedTotal=$billedTotal+$data['slip'][$i]['pendingAmt'];
			}

			if($data['slip'][$i]['fsbillStatus']== 'Resend'){
				$resendCount=$resendCount+1;
				$resendTotal=$resendTotal+$data['slip'][$i]['pendingAmt'];
			}


		}

		for($i=0;$i<count($data['bounced']);$i++){
			$total=$total+$data['bounced'][$i]['pendingAmt'];
			$netAmountTotal=$netAmountTotal+$data['bounced'][$i]['netAmount'];

			if($data['bounced'][$i]['fsSrAmt'] != '0.00'){
				$srBillTotal=$srBillTotal+$data['bounced'][$i]['fsSrAmt'];
			}

			if($data['bounced'][$i]['fsCashAmt'] != '0.00'){
				$billedCount=$billedCount+1;
				$cashBillTotal=$cashBillTotal+$data['bounced'][$i]['fsCashAmt'];
			}

			if($data['bounced'][$i]['fsChequeAmt'] != '0.00'){
				$billedCount=$billedCount+1;
				$chequeNeftTotal=$chequeNeftTotal+$data['bounced'][$i]['fsChequeAmt'];
			}

			if($data['bounced'][$i]['fsNeftAmt'] != '0.00'){
				$billedCount=$billedCount+1;
				$chequeNeftTotal=$chequeNeftTotal+$data['bounced'][$i]['fsNeftAmt'];
			}

			if($data['bounced'][$i]['fsOtherAdjAmt'] != '0.00'){
				$billedCount=$billedCount+1;
				$otherAdjTotal=$otherAdjTotal+$data['bounced'][$i]['fsOtherAdjAmt'];
			}


			if($data['bounced'][$i]['pendingAmt'] != '0.00'){
				$creditTotal=$creditTotal+$data['bounced'][$i]['pendingAmt'];
			}

			if($data['bounced'][$i]['fsbillStatus'] == 'FSR'){
				$srBill=$srBill+1;
				$billedTotal=$billedTotal+$data['bounced'][$i]['pendingAmt'];
			}

			if($data['bounced'][$i]['fsbillStatus'] == 'Resend'){
				$resendCount=$resendCount+1;
				$resendTotal=$resendTotal+$data['bounced'][$i]['pendingAmt'];
			}
		}

		$data['cashBill']=$cashBill;
		$data['cashBillTotal']=$cashBillTotal;
		$data['srBillTotal']=$srBillTotal;
		$data['srBillCount']=$srBill;
		$data['billCount']=$count;
		$data['billTotal']=$total;
		$data['billedCount']=$billedCount;
		$data['billedTotal']=$billedTotal;
		$data['resendCount']=$resendCount;
		$data['resendTotal']=$resendTotal;
		$data['creditTotal']=$creditTotal;
		$data['chequeNeftTotal']=$chequeNeftTotal;
		$data['netAmountTotal']=$netAmountTotal;
		$data['pendingTotal']=$total;

		$data['otherAdjTotal']=$otherAdjTotal;

		$data['notesDetails']=$this->FieldStaffModel->noteDetailsByAllocationId('notesdetails',$id);
    	$this->load->view('Manager/FieldStaffFinalizeView',$data);
    }

    public function updateFinalizeRecord(){
    	$allocationId=$this->input->post('allocationId');
    	$allocationCode=$this->input->post('allocationCode');

    	$parking=$this->input->post('park');
    	$challan=$this->input->post('challan');
    	$cng=$this->input->post('cng');

    	$note2000=$this->input->post('add2000');
    	$note1000=$this->input->post('add1000');
    	$note500=$this->input->post('add500');
    	$note200=$this->input->post('add200');
    	$note100=$this->input->post('add100');
    	$note50=$this->input->post('add50');
    	$note20=$this->input->post('add20');
    	$note10=$this->input->post('add10');
    	$coin=$this->input->post('coin');
    	
    	$updateNoteDetails=array('allocationId'=>$allocationId,'allocationCode' => $allocationCode,'note2000' =>$note2000,'note1000' => $note1000, 'note500' => $note500,'note200' => $note200,'note100' => $note100,'note50' => $note50,'note20' => $note20,'note10' => $note10,'coins' => $coin,'parking'=>$parking,'challan' => $challan,'cng' => $cng);

    	$this->FieldStaffModel->updateFinelizeRecordByManager('notesdetails',$updateNoteDetails,$allocationId);
    	if($this->db->affected_rows()>0){
    		$update=array('managerHisaabStatus' => 1);
    		$this->FieldStaffModel->update('allocations',$update,$allocationId);
    		if($this->db->affected_rows()>0){
    			redirect('AllocationByManagerController/openAllocations');
    		}else{
    			redirect('AllocationByManagerController/openAllocations');
    		}
    	}else{
    		$update=array('managerHisaabStatus' => 1);
    		echo $allocationId;
    		$this->FieldStaffModel->update('allocations',$update,$allocationId);
    		if($this->db->affected_rows()>0){
    			redirect('AllocationByManagerController/openAllocations');
    		}else{
    			redirect('AllocationByManagerController/openAllocations');
    		}
    	}
    }


    public function fieldStaff($id){
    	$data['employee']=$this->FieldStaffModel->getdataEmployee('employee');
		$data['routeNames']=$this->FieldStaffModel->getRouteNames();
		$data['employeeNames']=$this->FieldStaffModel->getEmployeeNames();
		$data['billNos']=$this->FieldStaffModel->getBillNos();
		$data['bounceReturnCheques']=$this->FieldStaffModel->bouncedReturnCheques('billpayments');
		$data['deliverySlip']=$this->FieldStaffModel->deliverySlipBillNo();
		$data['pastBillNos']=$this->FieldStaffModel->getPastBills();
		$data["current"]=array();
		$data["bounced"]=array();
		$data["pass"]=array();
		$data["slip"]=array();
		$data['bills']=$this->FieldStaffModel->getAllocatedBills('bills',$id);
		$data['allocations']=$this->FieldStaffModel->load('allocations',$id);

		$count=0.0;//for total allocated bills

		$total=0;
		foreach ($data['bills'] as $items) {
			if($items['billType']=='allocatedbillCurrent'){
				$data['current']=$this->FieldStaffModel->getAllocatedBillsByType('bills',$id,'1');
			}else if(($items['billType']==='allocatedbillPass') || ($items['billType']==='adHocDeliveryBill') || ($items['billType']==='officeAdjustmentBill')){
				$data['pass']=$this->FieldStaffModel->getAllocatedPastBillsByType('bills',$id);
			}else if($items['billType']=='allocatedbillDS'){
				$data['slip']=$this->FieldStaffModel->getAllocatedBillsByType('bills',$id,'1');
			}else if($items['billType']=='allocatedbillBounce'){
				$data['bounced']=$this->FieldStaffModel->getAllocatedBillsByType('bills',$id,'1');
			}
		}
		
		//Total Allocated Bills
		$count=$count+(count($data['current']))+(count($data['pass']))+(count($data['slip']))+(count($data['bounced']));
	
		$netAmountTotal=0;
		$srBill=0;
		$srBillTotal=0;
		$cashBill=0;
		$cashBillTotal=0;
		$billedCount=0;
		$billedTotal=0;
		$resendCount=0;
		$resendTotal=0;
		$creditTotal=0;
		$chequeNeftTotal=0;
		$creditAdjBillTotal=0;

		$otherAdjTotal=0;
		//Total Allocated bills Amount Total : 
		for($i=0;$i<count($data['current']);$i++){
			$total=$total+$data['current'][$i]['pendingAmt'];
			$netAmountTotal=$netAmountTotal+$data['current'][$i]['netAmount'];

			if(($data['current'][$i]['fsbillStatus'] == 'Billed') || ($data['current'][$i]['fsSrAmt'] != '0.00') || ($data['current'][$i]['fsCashAmt'] != '0.00') || ($data['current'][$i]['fsChequeAmt'] != '0.00') || ($data['current'][$i]['fsNeftAmt'] != '0.00')){
				$billedCount=$billedCount+1;
			}

			if($data['current'][$i]['fsSrAmt'] != '0.00'){
				$srBillTotal=$srBillTotal+$data['current'][$i]['fsSrAmt'];
				$creditAdjBillTotal=$creditAdjBillTotal+$data['current'][$i]['creditNoteRenewal'];
			}

			if($data['current'][$i]['fsCashAmt'] != '0.00'){
				// $billedCount=$billedCount+1;
				$cashBillTotal=$cashBillTotal+$data['current'][$i]['fsCashAmt'];
			}

			if($data['current'][$i]['fsChequeAmt'] != '0.00'){
				// $billedCount=$billedCount+1;
				$chequeNeftTotal=$chequeNeftTotal+$data['current'][$i]['fsChequeAmt'];
			}

			if($data['current'][$i]['fsNeftAmt'] != '0.00'){
				// $billedCount=$billedCount+1;
				$chequeNeftTotal=$chequeNeftTotal+$data['current'][$i]['fsNeftAmt'];
			}

			if($data['current'][$i]['fsOtherAdjAmt'] != '0.00'){
				// $billedCount=$billedCount+1;
				$otherAdjTotal=$otherAdjTotal+$data['current'][$i]['fsOtherAdjAmt'];
			}

			if($data['current'][$i]['fsbillStatus'] =="FSR"){
				$srBill=$srBill+1;
				$billedCount=$billedCount-1;
				$billedTotal=$billedTotal+$data['current'][$i]['pendingAmt'];
			}

			if($data['current'][$i]['fsbillStatus'] =="Resend"){
				$resendCount=$resendCount+1;
				$resendTotal=$resendTotal+$data['current'][$i]['pendingAmt'];
			}

			if($data['current'][$i]['pendingAmt'] != '0.00'){
				$creditTotal=$creditTotal+$data['current'][$i]['pendingAmt'];
			}

		}
		
		for($i=0;$i<count($data['pass']);$i++){
			$total=$total+$data['pass'][$i]['pendingAmt'];
			$netAmountTotal=$netAmountTotal+$data['pass'][$i]['netAmount'];

			if(($data['pass'][$i]['fsbillStatus'] == 'Billed') || ($data['pass'][$i]['fsSrAmt'] != '0.00') || ($data['pass'][$i]['fsCashAmt'] != '0.00') || ($data['pass'][$i]['fsChequeAmt'] != '0.00') || ($data['pass'][$i]['fsNeftAmt'] != '0.00')){
				$billedCount=$billedCount+1;
			}

			if($data['pass'][$i]['fsSrAmt'] != '0.00'){
				$srBillTotal=$srBillTotal+$data['pass'][$i]['fsSrAmt'];
				$creditAdjBillTotal=$creditAdjBillTotal+$data['pass'][$i]['creditNoteRenewal'];
			}

			if($data['pass'][$i]['fsCashAmt'] != '0.00'){
				// $billedCount=$billedCount+1;
				$cashBillTotal=$cashBillTotal+$data['pass'][$i]['fsCashAmt'];
			}

			if($data['pass'][$i]['fsChequeAmt'] != '0.00'){
				// $billedCount=$billedCount+1;
				$chequeNeftTotal=$chequeNeftTotal+$data['pass'][$i]['fsChequeAmt'];
			}

			if($data['pass'][$i]['fsNeftAmt'] != '0.00'){
				// $billedCount=$billedCount+1;
				$chequeNeftTotal=$chequeNeftTotal+$data['pass'][$i]['fsNeftAmt'];
			}

			if($data['pass'][$i]['fsOtherAdjAmt'] != '0.00'){
				// $billedCount=$billedCount+1;
				$otherAdjTotal=$otherAdjTotal+$data['pass'][$i]['fsOtherAdjAmt'];
			}

			if($data['pass'][$i]['fsbillStatus'] =="FSR"){
				$srBill=$srBill+1;
				$billedCount=$billedCount-1;
				$billedTotal=$billedTotal+$data['pass'][$i]['pendingAmt'];
			}

			if($data['pass'][$i]['fsbillStatus'] =="Resend"){
				$resendCount=$resendCount+1;
				$resendTotal=$resendTotal+$data['pass'][$i]['pendingAmt'];
			}

			if($data['pass'][$i]['pendingAmt'] != '0.00'){
				$creditTotal=$creditTotal+$data['pass'][$i]['pendingAmt'];
			}
		}

		for($i=0;$i<count($data['slip']);$i++){
			$total=$total+$data['slip'][$i]['pendingAmt'];
			$netAmountTotal=$netAmountTotal+$data['slip'][$i]['netAmount'];

			if(($data['slip'][$i]['fsSrAmt'] != '0.00') || ($data['slip'][$i]['fsCashAmt'] != '0.00') || ($data['slip'][$i]['fsChequeAmt'] != '0.00') || ($data['slip'][$i]['fsNeftAmt'] != '0.00')){
				$billedCount=$billedCount+1;
			}

			if($data['slip'][$i]['fsSrAmt'] != '0.00'){
				$srBillTotal=$srBillTotal+$data['slip'][$i]['fsSrAmt'];
				$creditAdjBillTotal=$creditAdjBillTotal+$data['slip'][$i]['creditNoteRenewal'];
			}

			if($data['slip'][$i]['fsCashAmt'] != '0.00'){
				// $billedCount=$billedCount+1;
				$cashBillTotal=$cashBillTotal+$data['slip'][$i]['fsCashAmt'];
			}

			if($data['slip'][$i]['fsChequeAmt'] != '0.00'){
				// $billedCount=$billedCount+1;
				$chequeNeftTotal=$chequeNeftTotal+$data['slip'][$i]['fsChequeAmt'];
			}

			if($data['slip'][$i]['fsNeftAmt'] != '0.00'){
				// $billedCount=$billedCount+1;
				$chequeNeftTotal=$chequeNeftTotal+$data['slip'][$i]['fsNeftAmt'];
			}

			if($data['slip'][$i]['fsOtherAdjAmt'] != '0.00'){
				// $billedCount=$billedCount+1;
				$otherAdjTotal=$otherAdjTotal+$data['slip'][$i]['fsOtherAdjAmt'];
			}

			if($data['slip'][$i]['fsbillStatus'] =="FSR"){
				$srBill=$srBill+1;
				$billedTotal=$billedTotal+$data['slip'][$i]['pendingAmt'];
			}

			if($data['slip'][$i]['fsbillStatus'] =="Resend"){
				$resendCount=$resendCount+1;
				$resendTotal=$resendTotal+$data['slip'][$i]['pendingAmt'];
			}

			if($data['slip'][$i]['pendingAmt'] != '0.00'){
				$creditTotal=$creditTotal+$data['slip'][$i]['pendingAmt'];
			}
		}

		for($i=0;$i<count($data['bounced']);$i++){
			$total=$total+$data['bounced'][$i]['pendingAmt'];
			$netAmountTotal=$netAmountTotal+$data['bounced'][$i]['netAmount'];

			if(($data['bounced'][$i]['fsSrAmt'] != '0.00') || ($data['bounced'][$i]['fsCashAmt'] != '0.00') || ($data['bounced'][$i]['fsChequeAmt'] != '0.00') || ($data['bounced'][$i]['fsNeftAmt'] != '0.00')){
				$billedCount=$billedCount+1;
			}

			if($data['bounced'][$i]['fsSrAmt'] != '0.00'){
				$srBillTotal=$srBillTotal+$data['bounced'][$i]['fsSrAmt'];
				$creditAdjBillTotal=$creditAdjBillTotal+$data['bounced'][$i]['creditNoteRenewal'];
			}

			if($data['bounced'][$i]['fsCashAmt'] != '0.00'){
				// $billedCount=$billedCount+1;
				$cashBillTotal=$cashBillTotal+$data['bounced'][$i]['fsCashAmt'];
			}

			if($data['bounced'][$i]['fsChequeAmt'] != '0.00'){
				// $billedCount=$billedCount+1;
				$chequeNeftTotal=$chequeNeftTotal+$data['bounced'][$i]['fsChequeAmt'];
			}

			if($data['bounced'][$i]['fsNeftAmt'] != '0.00'){
				// $billedCount=$billedCount+1;
				$chequeNeftTotal=$chequeNeftTotal+$data['bounced'][$i]['fsNeftAmt'];
			}

			if($data['bounced'][$i]['fsOtherAdjAmt'] != '0.00'){
				// $billedCount=$billedCount+1;
				$otherAdjTotal=$otherAdjTotal+$data['bounced'][$i]['fsOtherAdjAmt'];
			}
			
		}
		
		$data['cashBill']=$cashBill;
		$data['cashBillTotal']=$cashBillTotal;
		$data['srBillTotal']=$srBillTotal;
		$data['srBillCount']=$srBill;
		$data['billCount']=$count;
		$data['billTotal']=$total;
		$data['billedCount']=$billedCount;
		$data['billedTotal']=$billedTotal;
		$data['resendCount']=$resendCount;
		$data['resendTotal']=$resendTotal;
		$data['chequeNeftTotal']=$chequeNeftTotal;
		$data['netAmountTotal']=$netAmountTotal;
		$data['pendingTotal']=$total;
		$data['creditAdjBillTotal']=$creditAdjBillTotal;

		$data['otherAdjTotal']=$otherAdjTotal;
		
    	$this->load->view('Manager/FieldStaffView',$data);
    }


    public function updateSrByBillDetailsId(){
    	$billdetailId=trim($this->input->post('billdetailId'));
    	$billId=trim($this->input->post('billId'));
    	$returnedQty=trim($this->input->post('returnedQty'));
    	$returnedAmt=trim($this->input->post('returnedAmt'));
    	$allocationID = $this->input->post('allocationID');

      	//bill_details 
    	$billsdetails=$this->FieldStaffModel->loadBillDetailsID('billsdetails',$billdetailId);
      	$billNetAmt=$billsdetails[0]['netAmount'];	
      	$billQty=$billsdetails[0]['qty'];
      	$eachItemRate=$billNetAmt/$billQty;

      	if($returnedQty >$billQty){
      		echo "SR Quantity is greater than bill Quantity";
      	}else{
	      	$oldReturnQty=$billsdetails[0]['fsReturnQty'];
	      	$oldReturnAmt=$billsdetails[0]['fsReturnAmt'];
	      	
	      	$calAmt=$eachItemRate * $returnedQty;
	      	$totalReturnAmt=$oldReturnAmt+$calAmt;
	        $totalSrQty=$oldReturnQty+$returnedQty;
	       
	       	$data = array('fsReturnQty' => $totalSrQty,'fsReturnAmt' =>  $totalReturnAmt); 
			$this->FieldStaffModel->update('billsdetails',$data,$billdetailId);
			if($this->db->affected_rows()>0){
				$data['bills']=$this->FieldStaffModel->loadBillsDetails('bills', $billId);
	    	   	$status='';	

		        $oldStatus=$data['bills'][0]['fsbillStatus'];
		        if($oldStatus=='FSR'){
		            $status='FSR';
		        }else{
		        	$status='SR';
		        }

		        if($oldStatus=='FSR'){
		            $status='FSR';
		        }else{
		            if(strpos($oldStatus, $status) === false){
	    				$oldStatus=trim($oldStatus,',');
	    				$status=$oldStatus.','.$status;
	    				$status=trim($status,',');
	    	    	}else{
	    				$status=$oldStatus;
	    	    	    $status=trim($status,',');
	    	    	}
		        }

		        // $pastSR=0.0;
		        // $pastSR=$totalReturnAmt+$data['bills'][0]['pastSR'];

		        // $totalReturnAmt=$totalReturnAmt+$data['bills'][0]['fsSrAmt'];
		   		$data = array('fsbillStatus' => $status,'fsSrAmt' => $totalReturnAmt);  
	            $this->FieldStaffModel->update('bills',$data, $billId);
	            if($this->db->affected_rows() > 0){
	            	echo "Record Saved";
	            } else {
	            	echo "Unable to save record";
	            }
			}
		}
      	
    }

    public function saveUpdatedSrByBillDetailsId(){
    	$billdetailId=trim($this->input->post('billdetailId'));
    	$billId=trim($this->input->post('billId'));
    	$returnedQty=trim($this->input->post('returnedQty'));

    	$returnedAmt=trim($this->input->post('returnedAmt'));
    	$allocationID = $this->input->post('allocationID');

      	//bill_details 
    	$billsdetails=$this->FieldStaffModel->loadBillDetailsID('billsdetails',$billdetailId);
      	$billNetAmt=$billsdetails[0]['netAmount'];	
      	$billQty=$billsdetails[0]['qty'];
      	$eachItemRate=$billNetAmt/$billQty;
      
      	if($returnedQty >$billQty){
      		echo "SR Quantity is greater than bill Quantity";
      	}else{
	      	$oldReturnQty=$billsdetails[0]['fsReturnQty'];
	      	$oldReturnAmt=$billsdetails[0]['fsReturnAmt'];	      	

	      	$calAmt=$eachItemRate * $returnedQty;
	      	$totalReturnAmt=$calAmt;
	        $totalSrQty=$returnedQty;
	      	
	       
	       	$data = array('fsReturnQty' => $totalSrQty,'fsReturnAmt' =>  $totalReturnAmt); 
			$this->FieldStaffModel->update('billsdetails',$data,$billdetailId);
			if($this->db->affected_rows()>0){
				$data['bills']=$this->FieldStaffModel->loadBillsDetails('bills', $billId);
	    	   	$status='';	

		        $oldStatus=$data['bills'][0]['fsbillStatus'];
		        if($oldStatus=='FSR'){
		            $status='FSR';
		        }else{
		        	$status='SR';
		        }

		        if($oldStatus=='FSR'){
		            $status='FSR';
		        }else{
		            if(strpos($oldStatus, $status) === false){
	    				$oldStatus=trim($oldStatus,',');
	    				$status=$oldStatus.','.$status;
	    				$status=trim($status,',');
	    	    	}else{
	    				$status=$oldStatus;
	    	    	    $status=trim($status,',');
	    	    	}
		        }

		        $totalReturnAmt=$totalReturnAmt+($data['bills'][0]['fsSrAmt']-$oldReturnAmt);
	     
		        // $pastSR=0.0;
		        // $pastSR=$totalReturnAmt+$data['bills'][0]['pastSR'];

	        	$dataBills = array('fsbillStatus' => $status,'fsSrAmt' => $totalReturnAmt);  

	            $this->FieldStaffModel->update('bills',$dataBills, $billId);
	            if($this->db->affected_rows() > 0){
	            	echo "Record Updated";
	            } else {
	            	echo "Unable to save record";
	            }
			}
		}
      	
    }

     public function updateSR() {
	     $data['msg']='';	
		$allocationID = $this->input->post('allocationID');

		$pendingAmounFix = $this->input->post('pendAmt');

	    $id = $this->input->post('id');
	    $billId = $this->input->post('billId');

	    $name=$this->input->post('productName');
	    $mrp = $this->input->post('mrp');
	    $qty = $this->input->post('qty');

		$status="";

	    $netAmount = $this->input->post('netAmount');
	    $sellingRate = $this->input->post('selAmount');
	    $returnedQty = $this->input->post('returnedQty');
	    $returnAmt = $this->input->post('returnAmt');
	  	$srTotalFs=0;
	  	$fsSrBillAmt=0;

	  	$srTotalFsFix=0;
	  	$fsSrBillAmtFix=0;
	  	$calAmtFix=0;

	  	$calAmt=0;


	  	$sumQty=$this->FieldStaffModel->getSum('billsdetails',$billId);
	  	$actualQty=$sumQty[0]['qtySum'];

	  	//fieldstaff SR qty
	  	$srQty=number_format(array_sum($returnedQty),2);

	  	$status='SR';
       
	  	if($srQty<=$actualQty){

	  		///////////////////////////

	  		for ($i=0; $i < count($returnedQty); $i++) {
	        	if(!empty($returnedQty[$i]) || $returnedQty[$i] != 0.00){
	        		if($returnedQty[$i] <= $qty[$i]){
	        			$calAmtFix=$netAmount[$i]/$qty[$i];
		        		$ReturnAmount=$returnAmt[$i]+($calAmtFix * $returnedQty[$i]);
		                $data['billsdetails']=$this->FieldStaffModel->loadBillDetailsID('billsdetails', $id[$i]);
		                
		                $data['bills']=$this->FieldStaffModel->load('bills', $billId);
		                $fsSrBillAmtFix=$data['bills'][0]['fsSrAmt'];
		                $oldSR=0+$returnedQty[$i];
		                $srTotalFsFix=$srTotalFs+$ReturnAmount;
		                $fsSrBillAmtFix=$fsSrBillAmtFix+$srTotalFsFix;
	        		}
	        	}
	   		}
	   		
	   		if($fsSrBillAmtFix>$pendingAmounFix){
	   			$this->session->set_flashdata('item', array('message' => 'SR Amount is greater than pending amount.','class' => 'success'));
	   			redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
	   			exit;
	   		}
	  		/////////////////////////
    	  	
	        for ($i=0; $i < count($returnedQty); $i++) {
	        		if($returnedQty[$i] <= $qty[$i]){
	        			$calAmt=$netAmount[$i]/$qty[$i];
		        		$ReturnAmount=$returnAmt[$i]+($calAmt * $returnedQty[$i]);
		                $data['billsdetails']=$this->FieldStaffModel->loadBillDetailsID('billsdetails', $id[$i]);
		                
		                $data['bills']=$this->FieldStaffModel->load('bills', $billId);
		                $fsSrBillAmt=$data['bills'][0]['fsSrAmt'];
		                $oldSR=0+$returnedQty[$i];
		                $srTotalFs=$srTotalFs+$ReturnAmount;

		                $fsSrBillAmt=$srTotalFs;
		                
		                $oldSR= $oldSR;
		                $ReturnAmount= $ReturnAmount;
		                
		                if($qty[$i] >= $oldSR){
		                   	$data = array(
		                   		'fsReturnQty' => $oldSR,
		                      	'fsReturnAmt' => $ReturnAmount
		                    ); 
		                    
		                    $this->FieldStaffModel->update('billsdetails',$data,$id[$i]);
		                    if($this->db->affected_rows() > 0){
		                    	
			                } else {
			                    echo "Fail";
			                }
			            	$data['billsdetails']=$this->FieldStaffModel->loadBillDetails('billsdetails', $billId);
			            }else{
			                $data['billsdetails']=$this->FieldStaffModel->loadBillDetails('billsdetails', $billId);
			            } 
	        		}else{
	        			echo "<script> $(window).load(function(){
					             $('#cpSrModal').modal('show');
					         }); </script>";
	        		}
	   		}
    		

    	   		$data['bills']=$this->FieldStaffModel->loadBillsDetails('bills', $billId);
    	   		
    	        $oldStatus=$data['bills'][0]['fsbillStatus'];
    	       
	            if(strpos($oldStatus, $status) === FALSE){
    				$oldStatus=trim($oldStatus,',');
    				$status=$oldStatus.','.$status;
    	    	}else{
    	    		$status=$oldStatus;
    	    	}

    			$status=trim($status,',');

    			$fixTotal=$fsSrBillAmt;

    				$fsSrBillAmt=(int)($fsSrBillAmt);

    				if($fsSrBillAmt>0){
    					$data = array('fsbillStatus' => $status,'fsSrAmt' => $fsSrBillAmt);  
		                $this->FieldStaffModel->update('bills',$data, $billId);
		                if($this->db->affected_rows() > 0){
		                	echo "";
		                } else {
		                    echo "";
		                }
    				}else{
    					$fsstatus=str_replace('SR', '', $status);
	    				$status=trim($fsstatus,',');
		    	   		$data = array('fsbillStatus' => $status,'fsSrAmt' => 0);  
		                $this->FieldStaffModel->update('bills',$data, $billId);
		                if($this->db->affected_rows() > 0){
		                	echo "";
		                } else {
		                    echo "";
		                }

    				}
	    	   		
    		
	  }
	
        return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
	}


	public function updateSRCreditAdj() {
	    $data['msg']='';	
		$allocationID = $this->input->post('allocationID');
		$pendingAmounFix = $this->input->post('pendAmt');

	    $id = $this->input->post('id');
	    $billId = $this->input->post('billId');

	    $creditBillCheck=$this->FieldStaffModel->load('bills',$billId);
	    $creditAdjAmount=$creditBillCheck[0]['creditAdjustment'];
	    $netAmountAdj=$creditBillCheck[0]['netAmount'];
	    $pendingAmtNow=$creditBillCheck[0]['pendingAmt'];

	    if($creditAdjAmount > 0){
	    	$name=$this->input->post('productName');
		    $mrp = $this->input->post('mrp');
		    $qty = $this->input->post('qty');

			$status="";

		    $netAmount = $this->input->post('netAmount');
		    $sellingRate = $this->input->post('selAmount');
		    $returnedQty = $this->input->post('returnedQty');
		    $returnAmt = $this->input->post('returnAmt');
		  	$srTotalFs=0;
		  	$fsSrBillAmt=0;

		  	$srTotalFsFix=0;
		  	$fsSrBillAmtFix=0;
		  	$calAmtFix=0;

		  	$calAmt=0;

		  	$sumQty=$this->FieldStaffModel->getSum('billsdetails',$billId);
		  	$actualQty=$sumQty[0]['qtySum'];

		  	// $srQty=number_format(array_sum($returnedQty),2);
		  	$srQty=(array_sum($returnedQty));
		  	$status='SR';
	        $srQty=(int)$srQty;
            $actualQty=(int)$actualQty;
		  	if($srQty<=$actualQty){
		  		for ($i=0; $i < count($returnedQty); $i++) {
		        	if(!empty($returnedQty[$i]) || $returnedQty[$i] != 0.00){
		        		if(($returnedQty[$i] <= $qty[$i]) && ($qty[$i] >0)){
		        			$calAmtFix=$netAmount[$i]/$qty[$i];
			        		$ReturnAmount=$returnAmt[$i]+($calAmtFix * $returnedQty[$i]);
			                $data['billsdetails']=$this->FieldStaffModel->loadBillDetailsID('billsdetails', $id[$i]);
			                
			                $data['bills']=$this->FieldStaffModel->load('bills', $billId);
			                // $fsSrBillAmtFix=$data['bills'][0]['fsSrAmt'];
			                $fsSrBillAmtFix=0;
			                $oldSR=0+$returnedQty[$i];
			                $srTotalFsFix=$srTotalFs+$ReturnAmount;
			                $fsSrBillAmtFix=$fsSrBillAmtFix+$srTotalFsFix;
			                $pendingAmounFix=$pendingAmounFix+$data['billsdetails'][0]['fsReturnAmt'];
		        		}
		        	}
		   		}
		   		$onlyPending=$pendingAmounFix;
		   		$pendingWithAdjustment=$pendingAmounFix+$creditAdjAmount;

	    	  	if($pendingAmounFix<=$fsSrBillAmtFix){
	    	  		$creditNoteAmt=($fsSrBillAmtFix-$onlyPending);

	    	  		$creditNoteAmt=($creditNoteAmt);
                	$creditAdjAmount=($creditAdjAmount);

                	$fsSrBillAmtFix=($fsSrBillAmtFix);
                	$pendingAmtNow=($pendingAmtNow);
	    	  		
	    	  		if((int)$creditNoteAmt>(int)($creditAdjAmount)){
			   			$this->session->set_flashdata('item', array('message' => 'SR Amount is greater than pending amount.','class' => 'success'));
			   			redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
			   			exit;
			   		}

			   		if((int)($fsSrBillAmtFix)>(int)($pendingAmtNow)){
			   			$this->session->set_flashdata('item', array('message' => 'Credit Adjustment Bill. Sale Return can not be more than pending amount.','class' => 'success'));
			   			redirect('fieldStaff/FieldStaffController/fieldStaff/'.$allocationID);
			   			exit;
			   		}

	    	  		for ($i=0; $i < count($returnedQty); $i++) {
		        		if(($returnedQty[$i] <= $qty[$i]) && ($qty[$i] >0)){
		        			$calAmt=$netAmount[$i]/$qty[$i];
			        		$ReturnAmount=$returnAmt[$i]+($calAmt * $returnedQty[$i]);
			                $data['billsdetails']=$this->FieldStaffModel->loadBillDetailsID('billsdetails', $id[$i]);
			                
			                $data['bills']=$this->FieldStaffModel->load('bills', $billId);
			                $fsSrBillAmt=$data['bills'][0]['fsSrAmt'];
			                $oldSR=0+$returnedQty[$i];
			                $srTotalFs=$srTotalFs+$ReturnAmount;

			                $fsSrBillAmt=($srTotalFs);

			                $oldSR= $oldSR;
			                $ReturnAmount= $ReturnAmount;

			                if($qty[$i] >= $oldSR){
			                   	$data = array(
			                   		'fsReturnQty' => $oldSR,
			                      	'fsReturnAmt' => $ReturnAmount
			                    ); 
			                    
			                    $this->FieldStaffModel->update('billsdetails',$data,$id[$i]);
			                    
				            	$data['billsdetails']=$this->FieldStaffModel->loadBillDetails('billsdetails', $billId);
				            }else{
				                $data['billsdetails']=$this->FieldStaffModel->loadBillDetails('billsdetails', $billId);
				            } 
		        		}
			   		}

	    	   		$data['bills']=$this->FieldStaffModel->loadBillsDetails('bills', $billId);
	    	   		
	    	        $oldStatus=$data['bills'][0]['fsbillStatus'];
	    	       
		            if(strpos($oldStatus, $status) === FALSE){
	    				$oldStatus=trim($oldStatus,',');
	    				$status=$oldStatus.','.$status;
	    	    	}else{
	    	    		$status=$oldStatus;
	    	    	}

	    			$status=trim($status,',');
	    			$fixTotal=$fsSrBillAmt;
					$fsSrBillAmt=($fsSrBillAmt);

					if($fsSrBillAmt>$pendingAmtNow){
	                	$fsSrBillAmt=floor($fsSrBillAmt);
	                }else{
	                	$fsSrBillAmt=round($fsSrBillAmt);
	                }

					if($fsSrBillAmt>0){
						$data = array('fsbillStatus' => $status,'fsSrAmt' => $fsSrBillAmt,'creditNoteRenewal'=>(int)$creditNoteAmt);  
		                $this->FieldStaffModel->update('bills',$data, $billId);
					}else{
						$fsstatus=str_replace('SR', '', $status);
	    				$status=trim($fsstatus,',');
		    	   		$data = array('fsbillStatus' => $status,'fsSrAmt' => 0,'creditNoteRenewal'=>0);  
		                $this->FieldStaffModel->update('bills',$data, $billId);
					}
	    	  	}else{

	    	  		$creditNoteAmt=($creditNoteAmt);
                	$creditAdjAmount=($creditAdjAmount);

                	$fsSrBillAmtFix=($fsSrBillAmtFix);
                	$pendingAmtNow=($pendingAmtNow);

	    	  		if((int)$creditNoteAmt>(int)($creditAdjAmount)){
			   			$this->session->set_flashdata('item', array('message' => 'SR Amount is greater than pending amount.','class' => 'success'));
			   			redirect('fieldStaff/FieldStaffController/fieldStaff/'.$allocationID);
			   			exit;
			   		}

			   		if((int)($fsSrBillAmtFix)>(int)($pendingAmtNow)){
			   			$this->session->set_flashdata('item', array('message' => 'Credit Adjustment Bill. Sale Return can not be more than pending amount.','class' => 'success'));
			   			redirect('fieldStaff/FieldStaffController/fieldStaff/'.$allocationID);
			   			exit;
			   		}
			   		
	    	  		for ($i=0; $i < count($returnedQty); $i++) {
		        		if(($returnedQty[$i] <= $qty[$i]) && ($qty[$i] >0)){
		        			$calAmt=$netAmount[$i]/$qty[$i];
			        		$ReturnAmount=$returnAmt[$i]+($calAmt * $returnedQty[$i]);
			                $data['billsdetails']=$this->FieldStaffModel->loadBillDetailsID('billsdetails', $id[$i]);
			                
			                $data['bills']=$this->FieldStaffModel->load('bills', $billId);
			                $fsSrBillAmt=$data['bills'][0]['fsSrAmt'];
			                $oldSR=0+$returnedQty[$i];
			                $srTotalFs=$srTotalFs+$ReturnAmount;

			                $fsSrBillAmt=$srTotalFs;
			                
			                $oldSR= $oldSR;
			                $ReturnAmount= $ReturnAmount;

			                if($qty[$i] >= $oldSR){
			                   	$data = array(
			                   		'fsReturnQty' => $oldSR,
			                      	'fsReturnAmt' => $ReturnAmount
			                    ); 
			                    
			                    $this->FieldStaffModel->update('billsdetails',$data,$id[$i]);
			                   
				            	$data['billsdetails']=$this->FieldStaffModel->loadBillDetails('billsdetails', $billId);
				            }else{
				                $data['billsdetails']=$this->FieldStaffModel->loadBillDetails('billsdetails', $billId);
				            } 
		        		}
			   		}

	    	   		$data['bills']=$this->FieldStaffModel->loadBillsDetails('bills', $billId);
	    	   		
	    	        $oldStatus=$data['bills'][0]['fsbillStatus'];
	    	       
		            if(strpos($oldStatus, $status) === FALSE){
	    				$oldStatus=trim($oldStatus,',');
	    				$status=$oldStatus.','.$status;
	    	    	}else{
	    	    		$status=$oldStatus;
	    	    	}

	    			$status=trim($status,',');
	    			$fixTotal=$fsSrBillAmt;
					$fsSrBillAmt=($fsSrBillAmt);
					$creditNoteAmt=0;
					if(($fsSrBillAmt)>($onlyPending)){
						$creditNoteAmt=(($fsSrBillAmt)-($onlyPending));
					}

					if($fsSrBillAmt>$pendingAmtNow){
	                	$fsSrBillAmt=floor($fsSrBillAmt);
	                }else{
	                	$fsSrBillAmt=round($fsSrBillAmt);
	                }

					if($fsSrBillAmt>0){
						$data = array('fsbillStatus' => $status,'fsSrAmt' => $fsSrBillAmt,'creditNoteRenewal'=>(int)$creditNoteAmt);  
		                $this->FieldStaffModel->update('bills',$data, $billId);
					}else{
						$fsstatus=str_replace('SR', '', $status);
	    				$status=trim($fsstatus,',');
		    	   		$data = array('fsbillStatus' => $status,'fsSrAmt' => 0,'creditNoteRenewal'=>0);  
		                $this->FieldStaffModel->update('bills',$data, $billId);
					}
	    	  	}
		    }
		
	        return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
	    }else{
	    	$name=$this->input->post('productName');
		    $mrp = $this->input->post('mrp');
		    $qty = $this->input->post('qty');

			$status="";

		    $netAmount = $this->input->post('netAmount');
		    $sellingRate = $this->input->post('selAmount');
		    $returnedQty = $this->input->post('returnedQty');
		    $returnAmt = $this->input->post('returnAmt');
		  	$srTotalFs=0;
		  	$fsSrBillAmt=0;

		  	$srTotalFsFix=0;
		  	$fsSrBillAmtFix=0;
		  	$calAmtFix=0;

		  	$calAmt=0;

		  	$sumQty=$this->FieldStaffModel->getSum('billsdetails',$billId);
		  	$actualQty=$sumQty[0]['qtySum'];

		  	// $srQty=number_format(array_sum($returnedQty),2);
		  	$srQty=(array_sum($returnedQty));
		  	$status='SR';
	       	$srQty=(int)$srQty;
            $actualQty=(int)$actualQty;
		  	if($srQty<=$actualQty){
		  		for ($i=0; $i < count($returnedQty); $i++) {
		        	if(!empty($returnedQty[$i]) || $returnedQty[$i] != 0.00){
		        		if(($returnedQty[$i] <= $qty[$i]) && ($qty[$i] >0)){
		        			$calAmtFix=$netAmount[$i]/$qty[$i];
			        		$ReturnAmount=$returnAmt[$i]+($calAmtFix * $returnedQty[$i]);
			                $data['billsdetails']=$this->FieldStaffModel->loadBillDetailsID('billsdetails', $id[$i]);
			                
			                $data['bills']=$this->FieldStaffModel->load('bills', $billId);
			                // $fsSrBillAmtFix=$data['bills'][0]['fsSrAmt'];
			                $oldSR=0+$returnedQty[$i];
			                $srTotalFsFix=$srTotalFs+$ReturnAmount;
			                $fsSrBillAmtFix=$fsSrBillAmtFix+$srTotalFsFix;
			                $pendingAmounFix=$pendingAmounFix+$data['billsdetails'][0]['fsReturnAmt'];
		        		}
		        	}
		   		}

		   		// $creditNoteAmt=(int)($creditNoteAmt);
       //          $creditAdjAmount=(int)($creditAdjAmount);

                $fsSrBillAmtFix=($fsSrBillAmtFix);
                $pendingAmtNow=($pendingAmtNow);

		   		if((int)($fsSrBillAmtFix)>(int)$pendingAmtNow){
		   			$this->session->set_flashdata('item', array('message' => 'SR Amount is greater than pending amount.','class' => 'success'));
		   			redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
		   			exit;
		   		}

		   		if((int)($fsSrBillAmtFix)>(int)($pendingAmtNow)){
		   			$this->session->set_flashdata('item', array('message' => 'Credit Adjustment Bill. Sale Return can not be more than pending amount.','class' => 'success'));
		   			redirect('fieldStaff/FieldStaffController/fieldStaff/'.$allocationID);
		   			exit;
		   		}
	    	  	
		        for ($i=0; $i < count($returnedQty); $i++) {
	        		if(($returnedQty[$i] <= $qty[$i]) && ($qty[$i] >0)){
	        			$calAmt=$netAmount[$i]/$qty[$i];
		        		$ReturnAmount=$returnAmt[$i]+($calAmt * $returnedQty[$i]);
		                $data['billsdetails']=$this->FieldStaffModel->loadBillDetailsID('billsdetails', $id[$i]);
		                
		                $data['bills']=$this->FieldStaffModel->load('bills', $billId);
		                $fsSrBillAmt=$data['bills'][0]['fsSrAmt'];
		                $oldSR=0+$returnedQty[$i];
		                $srTotalFs=$srTotalFs+$ReturnAmount;

		                $fsSrBillAmt=$srTotalFs;
		                
		                $oldSR= $oldSR;
		                $ReturnAmount= $ReturnAmount;

		                if($qty[$i] >= $oldSR){
		                   	$data = array(
		                   		'fsReturnQty' => $oldSR,
		                      	'fsReturnAmt' => $ReturnAmount
		                    ); 
		                    
		                    $this->FieldStaffModel->update('billsdetails',$data,$id[$i]);
			            	$data['billsdetails']=$this->FieldStaffModel->loadBillDetails('billsdetails', $billId);
			            }else{
			                $data['billsdetails']=$this->FieldStaffModel->loadBillDetails('billsdetails', $billId);
			            } 
	        		}
		   		}

    	   		$data['bills']=$this->FieldStaffModel->loadBillsDetails('bills', $billId);
    	   		
    	        $oldStatus=$data['bills'][0]['fsbillStatus'];
    	       
	            if(strpos($oldStatus, $status) === FALSE){
    				$oldStatus=trim($oldStatus,',');
    				$status=$oldStatus.','.$status;
    	    	}else{
    	    		$status=$oldStatus;
    	    	}

    			$status=trim($status,',');
    			$fixTotal=$fsSrBillAmt;
				$fsSrBillAmt=($fsSrBillAmt);

				if($fsSrBillAmt>$pendingAmtNow){
                	$fsSrBillAmt=floor($fsSrBillAmt);
                }else{
                	$fsSrBillAmt=round($fsSrBillAmt);
                }

				if($fsSrBillAmt>0){
					$data = array('fsbillStatus' => $status,'fsSrAmt' => $fsSrBillAmt);  
	                $this->FieldStaffModel->update('bills',$data, $billId);
				}else{
					$fsstatus=str_replace('SR', '', $status);
    				$status=trim($fsstatus,',');
	    	   		$data = array('fsbillStatus' => $status,'fsSrAmt' => 0);  
	                $this->FieldStaffModel->update('bills',$data, $billId);
				}
		    }
		
	        return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
	    }


	   
	}

 	public function cashUpdate() {
 	    $allocationID = $this->input->post('allocationID');
 	    $empID = $this->input->post('empID');
        $id = $this->input->post('id');
        $cashAmt = $this->input->post('cashAmt');
        
        $data['bills']=$this->FieldStaffModel->loadBillsDetails('bills', $id);
      	$pendingAmt= $data['bills'][0]['pendingAmt'];
      	$netAmt= $data['bills'][0]['netAmount']+$data['bills'][0]['chequePenalty']+$data['bills'][0]['debitNoteAmount']+$data['bills'][0]['debitNoteJournalAmt'];
    
    		
		$status='Cash';
		$oldStatus="";

        $oldStatus=$data['bills'][0]['fsbillStatus'];
        
        if(strpos($oldStatus, $status) === FALSE){
        	if($netAmt==$pendingAmt){
        		$oldStatus=str_replace('Billed', '', $oldStatus);
        	}
			$oldStatus=trim($oldStatus,',');
			$status=$oldStatus.','.$status;
    	}else{
    		if($netAmt==$pendingAmt){
        		$oldStatus=str_replace('Billed', '', $oldStatus);
        	}
    		$status=$oldStatus;
    	}
		$status=trim($status,',');
		
        if($netAmt >= $cashAmt){
            $data['billPay']=$this->FieldStaffModel->getBillPaymentDetailsById('billpayments',$id,$allocationID,'Cash');
			if(!empty($data['billPay'])){
				$pamt=0;
				if($cashAmt > $data['billPay'][0]['paidAmount']){
					$pamt=$pendingAmt-($cashAmt-$data['billPay'][0]['paidAmount']);
				}else{
					$pamt=$pendingAmt+($data['billPay'][0]['paidAmount']-$cashAmt);
				}

				if($cashAmt>0){
					$data1=array('fsbillStatus'=>$status,'fsCashAmt'=>$cashAmt,'isResendBill'=>'0','pendingAmt'=>$pamt);

					$this->FieldStaffModel->update('bills',$data1,$id);
					if($this->db->affected_rows()>0){
						$billPay=array(
		            		'billId' => $id,
		            		'allocationId' => $allocationID,
		            		'empId'=>$empID,
		            		'paidAmount' => $cashAmt,
		            		'billAmount' => $netAmt,
		            		'balanceAmount' => $pamt,
		            		'paymentMode' => 'Cash',
		            		'date' => date('Y-m-d H:i:sa')
				        );
						$this->FieldStaffModel->update('billpayments',$billPay,$data['billPay'][0]['id']);
		            	if($this->db->affected_rows()>0){
							$history=array(
								'billId'=>$id,
								'allocationId'=>$allocationID,
								'transactionAmount'=>$cashAmt,
								'transactionStatus' =>'Cash',
								'transactionMode'=>'dr',
								'transactionDate'=>date('Y-m-d H:i:sa'),
								'empId'=>$empID,
								'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
							);
							$this->FieldStaffModel->updateHistory('bill_transaction_history',$history,$id,$allocationID,'Cash');

		            	    return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
		            	}else{
		            		return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
		            	}
					}else{
						return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
					}
				}else{
					$status=str_replace('Cash', '', $oldStatus);
					$status=trim($status,',');
					$data1=array('fsbillStatus'=>$status,'fsCashAmt'=>$cashAmt,'isResendBill'=>'0','pendingAmt'=>$pamt);

					$this->FieldStaffModel->update('bills',$data1,$id);
					if($this->db->affected_rows()>0){
						$billPay=array(
		            		'billId' => $id,
		            		'allocationId' => $allocationID,
		            		'empId'=>$empID,
		            		'paidAmount' => $cashAmt,
		            		'billAmount' => $netAmt,
		            		'balanceAmount' => $pamt,
		            		'paymentMode' => 'Cash',
		            		'date' => date('Y-m-d H:i:sa')
				        );
						$this->FieldStaffModel->update('billpayments',$billPay,$data['billPay'][0]['id']);
		            	if($this->db->affected_rows()>0){
							$history=array(
								'billId'=>$id,
								'allocationId'=>$allocationID,
								'transactionAmount'=>$cashAmt,
								'transactionStatus' =>'Cash',
								'transactionMode'=>'dr',
								'transactionDate'=>date('Y-m-d H:i:sa'),
								'empId'=>$empID,
								'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
							);
							$this->FieldStaffModel->updateHistory('bill_transaction_history',$history,$id,$allocationID,'Cash');

		            	    return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
		            	}else{
		            		return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
		            	}
					}else{
						return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
					}
				}

				
			}else{
				$pamt=$pendingAmt-$cashAmt;

				if($cashAmt>0){
					$status='Cash';
					if(strpos($oldStatus,$status)===FALSE){
			    		$oldStatus=trim($oldStatus,',');
			    		$status=$oldStatus.','.$status;
			    	}else{
			    		$status=$oldStatus;
			    	}
				}else{
					$status=str_replace('Cash', '',$oldStatus);
				}
				$status=trim($status,',');

				$data1=array('fsbillStatus'=>$status,'fsCashAmt'=>$cashAmt,'isResendBill'=>'0','pendingAmt'=>$pamt);
				$this->FieldStaffModel->update('bills',$data1,$id);
				if($this->db->affected_rows()>0){
					$billPay=array(
	            		'billId' => $id,
	            		'allocationId' => $allocationID,
	            		'empId'=>$empID,
	            		'paidAmount' => $cashAmt,
	            		'billAmount' => $netAmt,
	            		'balanceAmount' => $pamt,
	            		'paymentMode' => 'Cash',
	            		'date' => date('Y-m-d H:i:sa')
			        );
	            	$this->FieldStaffModel->insert('billpayments',$billPay);
	            	if($this->db->affected_rows()>0){
						$history=array(
							'billId'=>$id,
							'allocationId'=>$allocationID,
							'transactionAmount'=>$cashAmt,
							'transactionStatus' =>'Cash',
							'transactionMode'=>'dr',
							'transactionDate'=>date('Y-m-d H:i:sa'),
							'empId'=>$empID,
							'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
						);
						$this->FieldStaffModel->insert('bill_transaction_history',$history);

	            	    return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
	            	}else{
	            		return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
	            	}
	            }else{
	            	
	            }
			}
        }else{
            echo "Sorry!.. Cash amount is greater than pending amount.";
        }
        return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
    }

	public function otherAdjUpdate() {
		$allocationID = $this->input->post('allocationID');
		$empID = $this->input->post('empID');
	   $id = $this->input->post('id');
	   $cashAmt = $this->input->post('cashAmt');
	   
	   $data['bills']=$this->FieldStaffModel->loadBillsDetails('bills', $id);
		 $pendingAmt= $data['bills'][0]['pendingAmt'];
		 $netAmt= $data['bills'][0]['netAmount']+$data['bills'][0]['chequePenalty']+$data['bills'][0]['debitNoteAmount']+$data['bills'][0]['debitNoteJournalAmt'];
		 
   
		   
	   $status='Other Adjustment';
	   $oldStatus="";

	   $oldStatus=$data['bills'][0]['fsbillStatus'];
	   
	   if(strpos($oldStatus, $status) === FALSE){
		   if($netAmt==$pendingAmt){
			   $oldStatus=str_replace('Billed', '', $oldStatus);
		   }
		   $oldStatus=trim($oldStatus,',');
		   $status=$oldStatus.','.$status;
	   }else{
		   if($netAmt==$pendingAmt){
			   $oldStatus=str_replace('Billed', '', $oldStatus);
		   }
		   $status=$oldStatus;
	   }
	   $status=trim($status,',');
	   
	   if($netAmt >= $cashAmt){
		   $data['billPay']=$this->FieldStaffModel->getBillPaymentDetailsById('billpayments',$id,$allocationID,'Other Adjustment');
		   if(!empty($data['billPay'])){
			   $pamt=0;
			   if($cashAmt > $data['billPay'][0]['paidAmount']){
				   $pamt=$pendingAmt-($cashAmt-$data['billPay'][0]['paidAmount']);
			   }else{
				   $pamt=$pendingAmt+($data['billPay'][0]['paidAmount']-$cashAmt);
			   }

			   if($cashAmt>0){
				   $data1=array('fsbillStatus'=>$status,'fsOtherAdjAmt'=>$cashAmt,'isResendBill'=>'0','pendingAmt'=>$pamt);

				   $this->FieldStaffModel->update('bills',$data1,$id);
				   if($this->db->affected_rows()>0){
					   $billPay=array(
						   'billId' => $id,
						   'allocationId' => $allocationID,
						   'empId'=>$empID,
						   'paidAmount' => $cashAmt,
						   'billAmount' => $netAmt,
						   'balanceAmount' => $pamt,
						   'paymentMode' => 'Other Adjustment',
						   'date' => date('Y-m-d H:i:sa')
					   );
					   $this->FieldStaffModel->update('billpayments',$billPay,$data['billPay'][0]['id']);
					   if($this->db->affected_rows()>0){
						   $history=array(
							   'billId'=>$id,
							   'allocationId'=>$allocationID,
							   'transactionAmount'=>$cashAmt,
							   'transactionStatus' =>'Other Adjustment',
							   'transactionMode'=>'dr',
							   'transactionDate'=>date('Y-m-d H:i:sa'),
							   'empId'=>$empID,
							   'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
						   );
						   $this->FieldStaffModel->updateHistory('bill_transaction_history',$history,$id,$allocationID,'Other Adjustment');

						   return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
					   }else{
						   return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
					   }
				   }else{
					   return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
				   }
			   }else{
				   $status=str_replace('Other Adjustment', '', $oldStatus);
				   $status=trim($status,',');
				   $data1=array('fsbillStatus'=>$status,'fsOtherAdjAmt'=>$cashAmt,'isResendBill'=>'0','pendingAmt'=>$pamt);

				   $this->FieldStaffModel->update('bills',$data1,$id);
				   if($this->db->affected_rows()>0){
					   $billPay=array(
						   'billId' => $id,
						   'allocationId' => $allocationID,
						   'empId'=>$empID,
						   'paidAmount' => $cashAmt,
						   'billAmount' => $netAmt,
						   'balanceAmount' => $pamt,
						   'paymentMode' => 'Other Adjustment',
						   'date' => date('Y-m-d H:i:sa')
					   );
					   $this->FieldStaffModel->update('billpayments',$billPay,$data['billPay'][0]['id']);
					   if($this->db->affected_rows()>0){
						   $history=array(
							   'billId'=>$id,
							   'allocationId'=>$allocationID,
							   'transactionAmount'=>$cashAmt,
							   'transactionStatus' =>'Other Adjustment',
							   'transactionMode'=>'dr',
							   'transactionDate'=>date('Y-m-d H:i:sa'),
							   'empId'=>$empID,
							   'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
						   );
						   $this->FieldStaffModel->updateHistory('bill_transaction_history',$history,$id,$allocationID,'Other Adjustment');

						   return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
					   }else{
						   return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
					   }
				   }else{
					   return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
				   }
			   }

			   
		   }else{
			   $pamt=$pendingAmt-$cashAmt;

			   if($cashAmt>0){
				   $status='Other Adjustment';
				   if(strpos($oldStatus,$status)===FALSE){
					   $oldStatus=trim($oldStatus,',');
					   $status=$oldStatus.','.$status;
				   }else{
					   $status=$oldStatus;
				   }
			   }else{
				   $status=str_replace('Other Adjustment', '',$oldStatus);
			   }
			   $status=trim($status,',');

			   $data1=array('fsbillStatus'=>$status,'fsOtherAmt'=>$cashAmt,'isResendBill'=>'0','pendingAmt'=>$pamt);
			   $this->FieldStaffModel->update('bills',$data1,$id);
			   if($this->db->affected_rows()>0){
				   $billPay=array(
					   'billId' => $id,
					   'allocationId' => $allocationID,
					   'empId'=>$empID,
					   'paidAmount' => $cashAmt,
					   'billAmount' => $netAmt,
					   'balanceAmount' => $pamt,
					   'paymentMode' => 'Other Adjustment',
					   'date' => date('Y-m-d H:i:sa')
				   );
				   $this->FieldStaffModel->insert('billpayments',$billPay);
				   if($this->db->affected_rows()>0){
					   $history=array(
						   'billId'=>$id,
						   'allocationId'=>$allocationID,
						   'transactionAmount'=>$cashAmt,
						   'transactionStatus' =>'Other Adjustment',
						   'transactionMode'=>'dr',
						   'transactionDate'=>date('Y-m-d H:i:sa'),
						   'empId'=>$empID,
						   'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
					   );
					   $this->FieldStaffModel->insert('bill_transaction_history',$history);

					   return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
				   }else{
					   return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
				   }
			   }else{
				   
			   }
		   }
	   }else{
		   echo "Sorry!.. Other Adjustment amount is greater than pending amount.";
	   }
	   return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
   }
    
     public function chequeUpdate(){
        $allocationID = $this->input->post('allocationID');
    	$id=$this->input->post('id');
    	$empID = $this->input->post('empID');
    	$chkAmt=$this->input->post('cashAmt');
        	$data['bills']=$this->FieldStaffModel->loadBillsDetails('bills', $id);
        	$pendingAmt= $data['bills'][0]['pendingAmt'];
          	$netAmt= $data['bills'][0]['netAmount']+$data['bills'][0]['chequePenalty']+$data['bills'][0]['debitNoteAmount']+$data['bills'][0]['debitNoteJournalAmt'];
    
        	//For Deliveryman Status
        	$status='Cheque';
        	$oldStatus=$data['bills'][0]['fsbillStatus'];
    
        	if(strpos($oldStatus,$status)===FALSE){
        		if($netAmt==$pendingAmt){
	        		$oldStatus=str_replace('Billed', '', $oldStatus);
	        	}
        		$oldStatus=trim($oldStatus,',');
        		$status=$oldStatus.','.$status;
        	}else{
        		if($netAmt==$pendingAmt){
	        		$oldStatus=str_replace('Billed', '', $oldStatus);
	        	}
        		$status=$oldStatus;
        	}
    
        	$status=trim($status,',');
    		if($netAmt >= $chkAmt){
    			$data['billPay']=$this->FieldStaffModel->getBillPaymentDetailsById('billpayments',$id,$allocationID,'Cheque');
    			if(!empty($data['billPay'])){
    				$pamt=0;
    				if($chkAmt > $data['billPay'][0]['paidAmount']){
    					$pamt=$pendingAmt-($chkAmt-$data['billPay'][0]['paidAmount']);
    				}else{
    					$pamt=$pendingAmt+($data['billPay'][0]['paidAmount']-$chkAmt);
    				}

    				if($chkAmt>0){
    					$data1=array('fsbillStatus'=>$status,'fsChequeAmt'=>$chkAmt,'isResendBill'=>'0','pendingAmt'=>$pamt);
	    				$this->FieldStaffModel->update('bills',$data1,$id);
	    				if($this->db->affected_rows()>0){
	    					$billPay=array(
	    	            		'billId' => $id,
	    	            		'allocationId' => $allocationID,
	    	            		'empId'=>$empID,
	    	            		'paidAmount' => $chkAmt,
	    	            		'billAmount' => $netAmt,
	    	            		'balanceAmount' => $pamt,
	    	            		'paymentMode' => 'Cheque',
	    	            		'date' => date('Y-m-d H:i:sa')
	    			        );
	    					$this->FieldStaffModel->update('billpayments',$billPay,$data['billPay'][0]['id']);
	    	            	if($this->db->affected_rows()>0){
								$history=array(
									'billId'=>$id,
									'allocationId'=>$allocationID,
									'transactionAmount'=>$chkAmt,
									'transactionStatus' =>'Cheque',
									'transactionMode'=>'dr',
									'transactionDate'=>date('Y-m-d H:i:sa'),
									'empId'=>$empID,
									'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
								);
								$this->FieldStaffModel->updateHistory('bill_transaction_history',$history,$id,$allocationID,'Cheque');

	    	            	    return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
	    	            	}else{
	    	            		return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
	    	            	}
	    				}else{
	    					return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
	    				}
    				}else{
    					$status=str_replace('Cheque', '', $oldStatus);
    					$status=trim($status,',');
    					$data1=array('fsbillStatus'=>$status,'fsChequeAmt'=>$chkAmt,'isResendBill'=>'0','pendingAmt'=>$pamt);
	    				$this->FieldStaffModel->update('bills',$data1,$id);
	    				if($this->db->affected_rows()>0){
	    					$billPay=array(
	    	            		'billId' => $id,
	    	            		'allocationId' => $allocationID,
	    	            		'empId'=>$empID,
	    	            		'paidAmount' => $chkAmt,
	    	            		'billAmount' => $netAmt,
	    	            		'balanceAmount' => $pamt,
	    	            		'paymentMode' => 'Cheque',
	    	            		'date' => date('Y-m-d H:i:sa')
	    			        );
	    					$this->FieldStaffModel->update('billpayments',$billPay,$data['billPay'][0]['id']);
	    	            	if($this->db->affected_rows()>0){
								$history=array(
									'billId'=>$id,
									'allocationId'=>$allocationID,
									'transactionAmount'=>$chkAmt,
									'transactionStatus' =>'Cheque',
									'transactionMode'=>'dr',
									'transactionDate'=>date('Y-m-d H:i:sa'),
									'empId'=>$empID,
									'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
								);
								$this->FieldStaffModel->updateHistory('bill_transaction_history',$history,$id,$allocationID,'Cheque');


	    	            	    return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
	    	            	}else{
	    	            		return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
	    	            	}
	    				}else{
	    					return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
	    				}
    				}
    			}else{
    				$pamt=$pendingAmt-$chkAmt;

    				if($chkAmt>0){
						$status='Cheque';
						if(strpos($oldStatus,$status)===FALSE){
				    		$oldStatus=trim($oldStatus,',');
				    		$status=$oldStatus.','.$status;
				    	}else{
				    		$status=$oldStatus;
				    	}
					}else{
						$status=str_replace('Cheque', '',$oldStatus);
					}
					$status=trim($status,',');

    				$data1=array('fsbillStatus'=>$status,'fsChequeAmt'=>$chkAmt,'isResendBill'=>'0','pendingAmt'=>$pamt);
    				$this->FieldStaffModel->update('bills',$data1,$id);
    				if($this->db->affected_rows()>0){
    					$billPay=array(
    	            		'billId' => $id,
    	            		'allocationId' => $allocationID,
    	            		'empId'=>$empID,
    	            		'paidAmount' => $chkAmt,
    	            		'billAmount' => $netAmt,
    	            		'balanceAmount' => $pamt,
    	            		'paymentMode' => 'Cheque',
    	            		'date' => date('Y-m-d H:i:sa')
    			        );
    	            	$this->FieldStaffModel->insert('billpayments',$billPay);
    	            	if($this->db->affected_rows()>0){
							$history=array(
								'billId'=>$id,
								'allocationId'=>$allocationID,
								'transactionAmount'=>$chkAmt,
								'transactionStatus' =>'Cheque',
								'transactionMode'=>'dr',
								'transactionDate'=>date('Y-m-d H:i:sa'),
								'empId'=>$empID,
								'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
							);
							$this->FieldStaffModel->insert('bill_transaction_history',$history);

    	            	    return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
    	            	}else{
    	            		return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
    	            	}
    	            }else{
    	            	return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
    	            }
    			}
    		}else{
    			echo "Sorry!.. Cheque amount is greater than pending amount.";
    		}
    	// }
    	return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
	}
   
     public function neftUpdate(){
        $allocationID = $this->input->post('allocationID');
    	$id=$this->input->post('id');
    	$empID = $this->input->post('empID');
    	$neftAmt=$this->input->post('cashAmt');

    	$data['bills']=$this->FieldStaffModel->loadBillsDetails('bills', $id);
    	$pendingAmt= $data['bills'][0]['pendingAmt'];
      	$netAmt= $data['bills'][0]['netAmount']+$data['bills'][0]['chequePenalty']+$data['bills'][0]['debitNoteAmount']+$data['bills'][0]['debitNoteJournalAmt'];

    	$status='NEFT';
    	$oldStatus=$data['bills'][0]['fsbillStatus'];

    	if(strpos($oldStatus,$status)===FALSE){
    		if($netAmt==$pendingAmt){
        		$oldStatus=str_replace('Billed', '', $oldStatus);
        	}
    		$oldStatus=trim($oldStatus,',');
    		$status=$oldStatus.','.$status;
    	}else{
    		if($netAmt==$pendingAmt){
        		$oldStatus=str_replace('Billed', '', $oldStatus);
        	}
    		$status=$oldStatus;
    	}

    	$status=trim($status,',');

		if($netAmt >= $neftAmt){
			$data['billPay']=$this->FieldStaffModel->getBillPaymentDetailsById('billpayments',$id,$allocationID,'NEFT');
			if(!empty($data['billPay'])){
				$pamt=0;
				if($neftAmt > $data['billPay'][0]['paidAmount']){
					$pamt=$pendingAmt-($neftAmt-$data['billPay'][0]['paidAmount']);
				}else{
					$pamt=$pendingAmt+($data['billPay'][0]['paidAmount']-$neftAmt);
				}

				if($neftAmt>0){
					$data1=array('fsbillStatus'=>$status,'fsNeftAmt'=>$neftAmt,'isResendBill'=>'0','pendingAmt'=>$pamt);
					$this->FieldStaffModel->update('bills',$data1,$id);
					if($this->db->affected_rows()>0){
						$billPay=array(
		            		'billId' => $id,
		            		'allocationId' => $allocationID,
		            		'empId'=>$empID,
		            		'paidAmount' => $neftAmt,
		            		'billAmount' => $netAmt,
		            		'balanceAmount' => $pamt,
		            		'paymentMode' => 'NEFT',
		            		'date' => date('Y-m-d H:i:sa')
				        );
						$this->FieldStaffModel->update('billpayments',$billPay,$data['billPay'][0]['id']);
		            	if($this->db->affected_rows()>0){
							$history=array(
								'billId'=>$id,
								'allocationId'=>$allocationID,
								'transactionAmount'=>$neftAmt,
								'transactionStatus' =>'NEFT',
								'transactionMode'=>'dr',
								'transactionDate'=>date('Y-m-d H:i:sa'),
								'empId'=>$empID,
								'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
							);
							$this->FieldStaffModel->updateHistory('bill_transaction_history',$history,$id,$allocationID,'NEFT');

		            	    return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
		            	}else{
		            		return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
		            	}
					}else{
						return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
					}
				}else{
					$status=str_replace('NEFT','',$oldStatus);
					$status=trim($status,',');
					$data1=array('fsbillStatus'=>$status,'fsNeftAmt'=>$neftAmt,'isResendBill'=>'0','pendingAmt'=>$pamt);
					$this->FieldStaffModel->update('bills',$data1,$id);
					if($this->db->affected_rows()>0){
						$billPay=array(
		            		'billId' => $id,
		            		'allocationId' => $allocationID,
		            		'empId'=>$empID,
		            		'paidAmount' => $neftAmt,
		            		'billAmount' => $netAmt,
		            		'balanceAmount' => $pamt,
		            		'paymentMode' => 'NEFT',
		            		'date' => date('Y-m-d H:i:sa')
				        );
						$this->FieldStaffModel->update('billpayments',$billPay,$data['billPay'][0]['id']);
		            	if($this->db->affected_rows()>0){
							$history=array(
								'billId'=>$id,
								'allocationId'=>$allocationID,
								'transactionAmount'=>$neftAmt,
								'transactionStatus' =>'NEFT',
								'transactionMode'=>'dr',
								'transactionDate'=>date('Y-m-d H:i:sa'),
								'empId'=>$empID,
								'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
							);
							$this->FieldStaffModel->updateHistory('bill_transaction_history',$history,$id,$allocationID,'NEFT');


		            	    return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
		            	}else{
		            		return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
		            	}
					}else{
						return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
					}
				}

				
			}else{
				$pamt=$pendingAmt-$neftAmt;

				if($neftAmt>0){
					$status='NEFT';
					if(strpos($oldStatus,$status)===FALSE){
			    		$oldStatus=trim($oldStatus,',');
			    		$status=$oldStatus.','.$status;
			    	}else{
			    		$status=$oldStatus;
			    	}
				}else{
					$status=str_replace('NEFT', '',$oldStatus);
				}
				$status=trim($status,',');

				$data1=array('fsbillStatus'=>$status,'fsNeftAmt'=>$neftAmt,'isResendBill'=>'0','pendingAmt'=>$pamt);
				$this->FieldStaffModel->update('bills',$data1,$id);
				if($this->db->affected_rows()>0){
					$billPay=array(
	            		'billId' => $id,
	            		'allocationId' => $allocationID,
	            		'empId'=>$empID,
	            		'paidAmount' => $neftAmt,
	            		'billAmount' => $netAmt,
	            		'balanceAmount' => $pamt,
	            		'paymentMode' => 'NEFT',
	            		'date' => date('Y-m-d H:i:sa')
			        );
	            	$this->FieldStaffModel->insert('billpayments',$billPay);
	            	if($this->db->affected_rows()>0){
						$history=array(
							'billId'=>$id,
							'allocationId'=>$allocationID,
							'transactionAmount'=>$neftAmt,
							'transactionStatus' =>'NEFT',
							'transactionMode'=>'dr',
							'transactionDate'=>date('Y-m-d H:i:sa'),
							'empId'=>$empID,
							'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
						);
						$this->FieldStaffModel->insert('bill_transaction_history',$history);

	            	    return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
	            	}else{
	            		return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
	            	}
	            }else{
	            	return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
	            }
			}
		}else{
			echo "Fail";
		}
    	return redirect('manager/FieldStaffController/fieldStaff/'.$allocationID);
	}

    //Updating status As Billed 
    public function changeFsBillStatus(){
    	$current_allocation_Id=$this->input->post('current_allocation_Id');
    	$status=$this->input->post('billStatus');
    	$id=$this->input->post('billId');
    	$data['bills']=$this->FieldStaffModel->load('bills',$id);
    	$oldStatus="";
    	
    	if(empty($status)){
    		$oldStatus=$data['bills'][0]['fsbillStatus'];
    		$oldStatus=trim($oldStatus,',');
			$status = str_replace('Billed', '', $oldStatus);
    	}else{
    		$oldStatus=$data['bills'][0]['fsbillStatus'];
    		if(strpos($oldStatus, $status) === FALSE){
    			$oldStatus=trim($oldStatus,',');
    			$status=$oldStatus.','.$status;
    		}
    	}
    	$status=trim($status,',');
    	
    	// $data=array('fsbillStatus'=>$status);
    	// $res=$this->FieldStaffModel->update('bills',$data,$id);
    	// if($res!=0){
    	// 	// echo "<script type='text/javascript'> parent.$.fn.colorbox.close(); window.parent.location.reload(true); </script>";
    	// }else{
    	// 	echo "fail";
    	// }

    	if(!empty($status)){
    		$data=array('billCurrentStatus'=>'Signed','fsbillStatus'=>$status,'isResendBill'=>'0','isBilled'=>1);
	    	$this->FieldStaffModel->update('bills',$data,$id);

			$checkHistory=$this->FieldStaffModel->checkHistory('bill_transaction_history',$id,$current_allocation_Id,'Signed');
			if(!empty($checkHistory)){
				$history=array(
					'billId'=>$id,
					'allocationId' => $current_allocation_Id,
					'transactionStatus' =>'Signed',
					'remark' =>'Signed for pending amount '.$data['bills'][0]['pendingAmt'],
					'transactionDate'=>date('Y-m-d H:i:sa'),
					'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
				);
				$this->FieldStaffModel->updateHistory('bill_transaction_history',$history,$id,$current_allocation_Id,'Signed');
			}else{
				$history=array(
					'billId'=>$id,
					'allocationId' => $current_allocation_Id,
					'transactionStatus' =>'Signed',
					'remark' =>'Signed for pending amount '.$data['bills'][0]['pendingAmt'],
					'transactionDate'=>date('Y-m-d H:i:sa'),
					'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
				);
				$this->FieldStaffModel->insert('bill_transaction_history',$history);
			}
    	}else{
    		$data=array('billCurrentStatus'=>'','fsbillStatus'=>$status,'isResendBill'=>'0','isBilled'=>0);
	    	$this->FieldStaffModel->update('bills',$data,$id);

			$checkHistory=$this->FieldStaffModel->checkHistory('bill_transaction_history',$id,$current_allocation_Id,'Signed');
			if(!empty($checkHistory)){
				$history=array(
					'billId'=>$id,
					'allocationId' => $current_allocation_Id,
					'transactionStatus' =>'',
					'transactionDate'=>date('Y-m-d H:i:sa'),
					'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
				);
				$this->FieldStaffModel->updateHistory('bill_transaction_history',$history,$id,$current_allocation_Id,'Signed');
			}else{
				$history=array(
					'billId'=>$id,
					'allocationId' => $current_allocation_Id,
					'transactionStatus' =>'',
					'transactionDate'=>date('Y-m-d H:i:sa'),
					'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
				);
				$this->FieldStaffModel->insert('bill_transaction_history',$history);
			}
    	}
    }


     //Updating Status as Resend
    public function changeFsResendStatus(){
    	$current_allocation_Id=$this->input->post('current_allocation_Id');
    	$status=$this->input->post('billStatus');
    	$id=$this->input->post('billId');
    	$data['bills']=$this->FieldStaffModel->load('bills',$id);
    	$oldStatus="";
    	
    	$allocationStatus="";
    	$allocationData=$this->FieldStaffModel->load('allocations',$current_allocation_Id);
    	if(!empty($allocationData)){
    	    $allocationStatus="Allocated in ".$allocationData[0]['allocationCode'];
    	}
    	
    	if(empty($status)){
    		$oldStatus=$data['bills'][0]['fsbillStatus'];
    		$oldStatus=trim($oldStatus,',');
			$oldStatus = str_replace('Resend', '', $oldStatus);
    	}else{
    		
    		$oldStatus=$data['bills'][0]['fsbillStatus'];
    		if(strpos($oldStatus, $status) === FALSE){
    			$oldStatus=trim($oldStatus,',');
    			$oldStatus=$oldStatus.','.$status;
    		}
    	}
    	$oldStatus=trim($oldStatus,',');
    	if(!empty($oldStatus)){
    		$data=array('billCurrentStatus'=>'Resend','fsbillStatus'=>$oldStatus,'isResendBill'=>'1');
	    	$res=$this->FieldStaffModel->update('bills',$data,$id);
	    	
	    	$updateStatus=array('isResendBill'=>1);
	    	$this->FieldStaffModel->updateAllocationStatus('allocationsbills',$updateStatus,$current_allocation_Id,$id);
	    	
			$checkHistory=$this->FieldStaffModel->checkHistory('bill_transaction_history',$id,$current_allocation_Id,'Resend');
			if(!empty($checkHistory)){
				$history=array(
					'billId'=>$id,
					'allocationId' => $current_allocation_Id,
					'transactionStatus' =>'Resend',
					'remark' =>'Resend for pending amount '.$data['bills'][0]['pendingAmt'],
					'transactionDate'=>date('Y-m-d H:i:sa'),
					'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
				);
				$this->FieldStaffModel->updateHistory('bill_transaction_history',$history,$id,$current_allocation_Id,'Resend');
			}else{
				$history=array(
					'billId'=>$id,
					'allocationId' => $current_allocation_Id,
					'transactionStatus' =>'Resend',
					'remark' =>'Resend for pending amount '.$data['bills'][0]['pendingAmt'],
					'transactionDate'=>date('Y-m-d H:i:sa'),
					'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
				);
				$this->FieldStaffModel->insert('bill_transaction_history',$history);
			}
    	}else{
    		$data=array('billCurrentStatus'=>$allocationStatus,'fsbillStatus'=>$oldStatus,'isResendBill'=>'0');
	    	$res=$this->FieldStaffModel->update('bills',$data,$id);
	    	
	    	$updateStatus=array('isResendBill'=>0);
	    	$this->FieldStaffModel->updateAllocationStatus('allocationsbills',$updateStatus,$current_allocation_Id,$id);
	    	
			$checkHistory=$this->FieldStaffModel->checkHistory('bill_transaction_history',$id,$current_allocation_Id,'Resend');
			if(!empty($checkHistory)){
				$history=array(
					'billId'=>$id,
					'allocationId' => $current_allocation_Id,
					'transactionStatus' =>$allocationStatus,
					'transactionDate'=>date('Y-m-d H:i:sa'),
					'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
				);
				$this->FieldStaffModel->updateHistory('bill_transaction_history',$history,$id,$current_allocation_Id,'Resend');
			}else{
				$history=array(
					'billId'=>$id,
					'allocationId' => $current_allocation_Id,
					'transactionStatus' =>$allocationStatus,
					'transactionDate'=>date('Y-m-d H:i:sa'),
					'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
				);
				$this->FieldStaffModel->insert('bill_transaction_history',$history);
			}
    	}
    	
    }
    




    public function srView(){
        $id=trim($this->input->post('id'));
        $allocationID=trim($this->input->post('allocationId'));
        $billsInfo=$this->FieldStaffModel->load('bills', $id);

        $billsdetails=$this->FieldStaffModel->loadBillDetails('billsdetails', $id);
	    $data['bID']=$id;
	    $data['msg'] = "";
        ?>
            
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <!-- <h2> Sales Returns</h2><br /><br /> -->
                            <h4 style="color: #099dba;">
                            <span style="color: black;">Retailer :</span>
                            <?php 
                                if(!empty($billsdetails)){
                                     echo $billsdetails[0]['name'];
                                }
                            ?>
                             &nbsp; &nbsp;
                           <span style="color: black;">Bill No :</span> 
                            <?php 
                                if(!empty($billsdetails)){
                                     echo $billsdetails[0]['billNo'];
                                }
                            ?>
                            &nbsp; &nbsp;
                          <span style="color: black;">Date :</span>
                            <?php
                                if(!empty($billsdetails)){
                                    $dt=date_create($billsdetails[0]['Date']);
                                    $date = date_format($dt,'d-m-Y');
                                    echo $date;
                                }
                            ?>
                            &nbsp;&nbsp;<br><br>
                       <span style="color: black;">Bill Amount :</span>
                            <?php
                                if(!empty($billsdetails)){
                                   echo number_format($billsdetails[0]['netAmt'],2);
                                }
                            ?>
                            &nbsp;&nbsp;
                        <span style="color: black;">Pending Amount :</span>
                            <?php
                                if(!empty($billsdetails)){
                                    echo number_format(($billsdetails[0]['pendingAmt']-($billsdetails[0]['fsSrAmt']-$billsdetails[0]['creditNoteRenewal'])),2);
                                }
                            ?>
                            </h4>
                        </div>
                            
                        <div class="body">
                          <form onsubmit="return msgIsEmpty();" action="<?php echo site_url('manager/FieldStaffController/updateSRCreditAdj');?>" method="post">
                            <div class="table-responsive">
                               <p id="msg"></p>
                               
                                        <div>
                                            <button type="submit" <?php if(empty($billsdetails)){ echo "disabled"; }?> class="btn btn-xs btn-primary m-t-15 waves-effect">
                                                <i class="material-icons">save</i><span class="icon-name">SR</span>
                                            </button>
                                        <a href="<?php echo site_url('manager/FieldStaffController/fieldStaff/'.$allocationID); ?>">
                                            <button type="button" class="btn btn-xs btn-danger m-t-15 waves-effect" data-dismiss="modal">
                                                <i class="material-icons">cancel</i><span class="icon-name">Close</span>
                                            </button>
                                        </a>
                                        </div>

                                        <input type="hidden" name="pendAmt" id="sr_pendAmt" value="<?php if(!empty($billsdetails)){ echo ($billsdetails[0]['pendingAmt']-$billsdetails[0]['fsSrAmt']); } ?>" readonly>
                                          
                                    <div style="color:red;" id="sr_qty"></div>
                                <table style="font-size: 12px" id="SrTable" class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                	<span id="all_id" style="display:none"></span>
                                    <thead>
                                       <tr>
	                                    <th>Sr.No</th>
	                                    <th>Product Name</th>
	                                    <th class="text-right">MRP</th>
	                                    <th class="text-right">Billed Qty</th>
	                                    <th class="text-right">Net Amount</th>
	                                    <th class="text-right">Past SR</th>
	                                    <th class="text-right">Past SR Amount</th>
	                                    <th class="text-right">Current SR</th>
	                                    <th class="text-right">SR Qty</th>
	                                    <th class="text-right">Current SR Amount</th>
	                                </tr>
                                    </thead>
                                    <tfoot>
                                         <tr>
		                                    <th>Sr.No</th>
		                                    <th>Product Name</th>
		                                    <th class="text-right">MRP</th>
		                                    <th class="text-right">Billed Qty</th>
		                                    <th class="text-right">Net Amount</th>
		                                    <th class="text-right">Past SR</th>
		                                    <th class="text-right">Past SR Amount</th>
		                                    <th class="text-right">Current SR</th>
		                                    <th class="text-right">SR Qty</th>
		                                    <th class="text-right">Current SR Amount</th>
		                                </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        if(!empty($billsdetails)){
                                          $no=0;
                                          foreach ($billsdetails as $data) 
                                            {
                                             $no++; 
                                             $id_is=$data['id'];
                                             $id_qty=$data['qty'];
                                             $id_fs_qty=$data['gkReturnQty'];
                                          ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <input type="hidden" name="allocationID" id="sr_allocationID" value="<?php echo $allocationID; ?>" readonly>
                                        <td><?php echo $data['productName']; ?></td>
                                         <input type="hidden" id="prodName_id" name="productName[]" value="<?php echo $data['productName']; ?>" readonly>
                                        <td class="text-right">
                                            <?php echo number_format($data['mrp']); ?> 
                                            <input type="hidden" id="mrp_id" name="mrp[]" value="<?php echo $data['mrp']; ?>" readonly>
                                        </td>
                                        <td class="text-right">
                                            <?php echo number_format($data['qty']); ?>
                                              <input type="hidden" id="qty_id<?php echo $no; ?>" name="qty[]" value="<?php echo $data['qty']; ?>" readonly> 
                                              <input type="hidden" name="fsReturnQty[]" value="<?php echo $data['fsReturnQty']; ?>" readonly> 
                                        </td> 
                                        <td class="text-right">
                                            <?php echo number_format($data['netAmount']); ?>
                                            <input type="hidden" id="netAmount_id<?php echo $no; ?>" name="netAmount[]" value="<?php echo $data['netAmount']; ?>" readonly>
                                            <input type="hidden" id="selAmount_id" name="selAmount[]" value="<?php echo $data['sellingRate']; ?>" readonly>         
                                        </td>
                                        <td class="text-right">
                                             <?php echo number_format($data['gkReturnQty']); ?>
                                        </td>
                                         <td class="text-right">
		                                    <?php if($data['gkReturnQty'] >0){ echo number_format($data['fsReturnAmt']); }else{ echo "0"; } ?>
		                                </td>
                                         <td class="text-right">
                                            <?php echo number_format($data['fsReturnQty']); ?>
                                             <input type="hidden" id="id_id" name="id[]" value="<?php echo $data['id']; ?>">
                                             <input type="hidden" id="billId_id" name="billId" value="<?php echo $data['billId']; ?>">
                                        </td>
                                        <td class="text-right">
                                        <?php if($data['qty']==$data['fsReturnQty']){?>
                                            <input type="text" onkeypress="return numbersonly(this, event);" id="returnedQty<?php echo $no; ?>" onblur="checkQtyPerItem(this,'<?php echo $no; ?>','<?php echo $id_qty; ?>','<?php echo $id_fs_qty; ?>','<?php echo $billsInfo[0]['netAmount'];?>','<?php echo $billsInfo[0]['pendingAmt'];?>','<?php echo $billsInfo[0]['SRAmt'];?>','<?php echo $billsInfo[0]['receivedAmt'];?>','<?php echo $billsInfo[0]['fsCashAmt'];?>','<?php echo $billsInfo[0]['fsChequeAmt'];?>','<?php echo $billsInfo[0]['fsNeftAmt'];?>','<?php echo $billsInfo[0]['fsSrAmt'];?>')" onfocus="this.select();" autofocus="autofocus" class="form-control" name="returnedQty[]" value="<?php echo $data['fsReturnQty']; ?>">
                                            <span style="color:red" id="data_err<?php echo $no; ?>"></span>
                                            
                                         <?php }else{ ?>
                                             <input id="returnedQty<?php echo $no; ?>" onkeypress="return numbersonly(this, event);" onblur="checkQtyPerItem(this,'<?php echo $no; ?>','<?php echo $id_qty; ?>','<?php echo $id_fs_qty; ?>','<?php echo $billsInfo[0]['netAmount'];?>','<?php echo $billsInfo[0]['pendingAmt'];?>','<?php echo $billsInfo[0]['SRAmt'];?>','<?php echo $billsInfo[0]['receivedAmt'];?>','<?php echo $billsInfo[0]['fsCashAmt'];?>','<?php echo $billsInfo[0]['fsChequeAmt'];?>','<?php echo $billsInfo[0]['fsNeftAmt'];?>','<?php echo $billsInfo[0]['fsSrAmt'];?>')" onfocus="this.select();" autofocus="autofocus" type="text" class="form-control" name="returnedQty[]" value="<?php echo $data['fsReturnQty']; ?>">
                                             <span style="color:red" id="data_err<?php echo $no; ?>"></span> 
                                         <?php }?>
                                        </td>
                                        <td class="text-right">
                                            <?php echo number_format($data['fsReturnAmt']); ?>  
                                             <input type="hidden" id="returnAmt_id<?php echo $no; ?>" name="returnAmt[]" value="<?php echo $data['returnAmt']; ?>">             
                                        </td>
                                     
                                   </tr>  
                                     <?php
                                        }
                                    }else{ ?>
                                    	<tr><td>No data available</td></tr>
                                    <?php
                                      }  
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        	</form>
                        </div>
                    </div>
                </div>
            
    
        <?php
    }
    
    public function cashModal(){
        $id=trim($this->input->post('id'));
        $allocationID=trim($this->input->post('allocationId'));
        $current_emp_Id=trim($this->input->post('current_emp_Id'));
        $bills=$this->FieldStaffModel->loadBillsDetails('bills', $id);
        
        ?>
            
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h4 style="color: #099dba;">
                            <span style="color: black;">Retailer :</span>
                            <?php 
                                if(!empty($bills)){
                                     echo $bills[0]['name'];
                                }
                            ?>
                             &nbsp; &nbsp;
                           <span style="color: black;">Bill No :</span> 
                            <?php 
                                if(!empty($bills)){
                                     echo $bills[0]['billNo'];
                                }
                            ?>
                            &nbsp; &nbsp;
                          <span style="color: black;">Date :</span>
                            <?php
                                if(!empty($bills)){
                                    $dt=date_create($bills[0]['date']);
                                    $date = date_format($dt,'d-m-Y');
                                    echo $date;
                                }
                            ?>
                            &nbsp;&nbsp;<br><br>
                       <span style="color: black;">Bill Amount :</span>
                            <?php
                                if(!empty($bills)){
                                    echo number_format($bills[0]['netAmount'],2);
                                }
                            ?>
                            &nbsp;&nbsp;
                        <span style="color: black;">Pending Amount :</span>
                            <?php
                                if(!empty($bills)){
                                    echo number_format(($bills[0]['pendingAmt']-$bills[0]['fsSrAmt']),2);
                                }
                            ?>
                            </h4>
                        </div>
                            
                        <div class="body">
                        <div class="table-responsive">
                        <div class="body">
                            <div class="demo-masked-input">
                                <form method="post" role="form" action="<?php echo site_url('manager/FieldStaffController/Cashupdate');?>" onsubmit="return checkCashBillAmt('<?php echo $bills[0]['netAmount']; ?>','<?php echo $bills[0]['pendingAmt']; ?>','<?php echo $bills[0]['SRAmt']; ?>','<?php echo $bills[0]['receivedAmt']; ?>','<?php echo $bills[0]['fsChequeAmt']; ?>','<?php echo $bills[0]['fsNeftAmt']; ?>','<?php echo $bills[0]['fsSrAmt']; ?>','<?php echo $bills[0]['chequePenalty']; ?>','<?php echo $bills[0]['cd']; ?>','<?php echo $bills[0]['debit']; ?>','<?php echo $bills[0]['officeAdjustmentBillAmount']; ?>','<?php echo $bills[0]['otherAdjustment']; ?>','<?php echo $bills[0]['debitNoteAmount']; ?>','<?php echo $bills[0]['fsOtherAdjAmt']; ?>','<?php echo $bills[0]['debitNoteJournalAmt']; ?>');"> 
                                    <div class="row clearfix">
                                         <input type="hidden" name="id" value="<?php
                                        if(isset($bills))
                                          {
                                            if(!empty($bills[0]['id'])){
                                                 echo $bills[0]['id'];
                                            }
                                          }
                                        ?>">
                                        <input type="hidden" name="allocationID" value="<?php echo $allocationID; ?>">
                                        <input type="hidden" name="empID" value="<?php echo $current_emp_Id; ?>">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-6">                                       
                                            <p>
                                              <b>Cash Amount </b>
                                            </p>
                                            <div class="form-line">
                                                <input onkeypress="return numbersonly(this, event);" onfocus="this.select();" autofocus="autofocus" type="text" id="amt" value="<?php if(isset($bills)){ echo $bills[0]['fsCashAmt']; }?>" name="cashAmt" class="cash-amt form-control">           
                                            </div>  <br>
                                            <div style="color:red;" id="cashRes"></div>         
                                        </div>
                                        <div class="col-md-3"></div>
                                    </div>

                                <div class="col-md-12">
                                    <div class="row clearfix">
                                        <center>                                               
                                                <button type="submit" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name">
                                                    Save
                                                    </span>
                                                </button> 
                                                 <button data-dismiss="modal" type="button" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">cancel</i><span class="icon-name">cancel</span>
                                                </button>
                                        </center>

                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
    }
    
   public function chequeModal(){
       $id=trim($this->input->post('id'));
       $allocationID=trim($this->input->post('allocationId'));
       $current_emp_Id=trim($this->input->post('current_emp_Id'));
       $bills=$this->FieldStaffModel->loadBillsDetails('bills', $id);
       ?>
            
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                      
                            <div class="header">
                          
                            <h4 style="color: #099dba;">
                            <span style="color: black;">Retailer :</span>
                            <?php 
                                if(!empty($bills)){
                                     echo $bills[0]['name'];
                                }
                            ?>
                             &nbsp; &nbsp;
                           <span style="color: black;">Bill No :</span> 
                            <?php 
                                if(!empty($bills)){
                                     echo $bills[0]['billNo'];
                                }
                            ?>
                            &nbsp; &nbsp;
                          <span style="color: black;">Date :</span>
                            <?php
                                if(!empty($bills)){
                                    $dt=date_create($bills[0]['date']);
                                    $date = date_format($dt,'d-m-Y');
                                    echo $date;
                                }
                            ?>
                            &nbsp;&nbsp;<br><br>
                       <span style="color: black;">Bill Amount :</span>
                            <?php
                                if(!empty($bills)){
                                    echo number_format($bills[0]['netAmount'],2);
                                }
                            ?>
                            &nbsp;&nbsp;
                        <span style="color: black;">Pending Amount :</span>
                            <?php
                                if(!empty($bills)){
                                    echo number_format(($bills[0]['pendingAmt']-$bills[0]['fsSrAmt']),2);
                                }
                            ?>
                            </h4>
                        </div>
                        <div class="body">
                        <div class="table-responsive">
                        <div class="body">
                            <div class="demo-masked-input">
                                <form method="post" role="form" action="<?php echo site_url('manager/FieldStaffController/ChequeUpdate');?>" onsubmit="return checkChequeBillAmt('<?php echo $bills[0]['netAmount']; ?>','<?php echo $bills[0]['pendingAmt']; ?>','<?php echo $bills[0]['SRAmt']; ?>','<?php echo $bills[0]['receivedAmt']; ?>','<?php echo $bills[0]['fsCashAmt']; ?>','<?php echo $bills[0]['fsNeftAmt']; ?>','<?php echo $bills[0]['fsSrAmt']; ?>','<?php echo $bills[0]['chequePenalty']; ?>','<?php echo $bills[0]['cd']; ?>','<?php echo $bills[0]['debit']; ?>','<?php echo $bills[0]['officeAdjustmentBillAmount']; ?>','<?php echo $bills[0]['otherAdjustment']; ?>','<?php echo $bills[0]['debitNoteAmount']; ?>','<?php echo $bills[0]['fsOtherAdjAmt']; ?>','<?php echo $bills[0]['debitNoteJournalAmt']; ?>');"> 
                                <div class="row clearfix">
                                     <input type="hidden" name="id" value="<?php
                                    if(isset($bills))
                                      {
                                        if(!empty($bills[0]['id'])){
                                            echo $bills[0]['id'];
                                        }
                                      }
                                    ?>">
                                    <input type="hidden" name="allocationID" value="<?php echo $allocationID; ?>">
                                    <input type="hidden" name="empID" value="<?php echo $current_emp_Id; ?>">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">                                       
                                        <p>
                                          <b>Cheque Amount </b>
                                        </p>
                                       <div class="form-line">
                                                <input onkeypress="return numbersonly(this, event);" onfocus="this.select();" type="text" autofocus="autofocus" id="amt" value="<?php if(isset($bills)){ echo $bills[0]['fsChequeAmt']; }?>" name="cashAmt" class="chk-amt form-control">           
                                            </div>  <br>
                                            <div style="color:red;" id="chequeRes"></div>           
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>

                                <div class="col-md-12">
                                    <div class="row clearfix">
                                        <center>                                               
                                                <button type="submit" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name">
                                                    Save
                                                    </span>
                                                </button> 
                                                <button data-dismiss="modal" type="button" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">cancel</i><span class="icon-name">cancel</span>
                                                </button>
                                                
                                        </center>

                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
       <?php
   }
   
   public function neftModal(){
       $allocationID=trim($this->input->post('allocationId'));
       $id=trim($this->input->post('id'));
       $current_emp_Id=trim($this->input->post('current_emp_Id'));
       $bills=$this->FieldStaffModel->loadBillsDetails('bills',$id);
       
    ?>
       
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h4 style="color: #099dba;">
                    <span style="color: black;">Retailer :</span>
                    <?php 
                        if(!empty($bills)){
                             echo $bills[0]['name'];
                        }
                    ?>
                     &nbsp; &nbsp;
                   <span style="color: black;">Bill No :</span> 
                    <?php 
                        if(!empty($bills)){
                             echo $bills[0]['billNo'];
                        }
                    ?>
                    &nbsp; &nbsp;
                  <span style="color: black;">Date :</span>
                    <?php
                        if(!empty($bills)){
                            $dt=date_create($bills[0]['date']);
                            $date = date_format($dt,'d-m-Y');
                            echo $date;
                        }
                    ?>
                    &nbsp;&nbsp;<br><br>
               <span style="color: black;">Bill Amount :</span>
                    <?php
                        if(!empty($bills)){
                            echo number_format($bills[0]['netAmount'],2);
                        }
                    ?>
                    &nbsp;&nbsp;
                <span style="color: black;">Pending Amount :</span>
                    <?php
                        if(!empty($bills)){
                            echo number_format(($bills[0]['pendingAmt']-$bills[0]['fsSrAmt']),2);
                        }
                    ?>
                    </h4>
                </div>
                    
                <div class="body">
                <div class="table-responsive">
                <div class="body">
                    <div class="demo-masked-input">
                        <form method="post" role="form" action="<?php echo site_url('manager/FieldStaffController/NEFTUpdate');?>" onsubmit="return checkNeftBillAmt('<?php echo $bills[0]['netAmount']; ?>','<?php echo $bills[0]['pendingAmt']; ?>','<?php echo $bills[0]['SRAmt']; ?>','<?php echo $bills[0]['receivedAmt']; ?>','<?php echo $bills[0]['fsCashAmt']; ?>','<?php echo $bills[0]['fsChequeAmt']; ?>','<?php echo $bills[0]['fsSrAmt']; ?>','<?php echo $bills[0]['chequePenalty']; ?>','<?php echo $bills[0]['cd']; ?>','<?php echo $bills[0]['debit']; ?>','<?php echo $bills[0]['officeAdjustmentBillAmount']; ?>','<?php echo $bills[0]['otherAdjustment']; ?>','<?php echo $bills[0]['debitNoteAmount']; ?>','<?php echo $bills[0]['fsOtherAdjAmt']; ?>','<?php echo $bills[0]['debitNoteJournalAmt']; ?>');"> 
                        <div class="row clearfix">
                             <input type="hidden" name="id" value="<?php
                            if(isset($bills))
                              {
                                if(!empty($bills[0]['id'])){
                                    echo $bills[0]['id'];
                                }
                              }
                            ?>">
                            <input type="hidden" name="allocationID" value="<?php echo $allocationID; ?>">
                            <input type="hidden" name="empID" value="<?php echo $current_emp_Id; ?>">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">                                       
                                <p>
                                  <b>NEFT Amount </b>
                                </p>
                                 <div class="form-line">
                                        <input onkeypress="return numbersonly(this, event);" onfocus="this.select();" autofocus="autofocus" type="text" id="amt" name="cashAmt" value="<?php if(isset($bills)){ echo $bills[0]['fsNeftAmt']; }?>" class="neft-amt form-control">           
                                    </div>  <br>
                                    <div style="color:red;" id="neftRes"></div>          
                            </div>
                            <div class="col-md-3"></div>
                        </div>

                        <div class="col-md-12">
                            <div class="row clearfix">
                                <center>                                               
                                        <button type="submit" class="btn btn-primary m-t-15 waves-effect">
                                            <i class="material-icons">save</i> 
                                            <span class="icon-name">
                                            Save
                                            </span>
                                        </button> 
                                       <button data-dismiss="modal" type="button" class="btn btn-primary m-t-15 waves-effect">
                                                <i class="material-icons">cancel</i> 
                                                <span class="icon-name">
                                                cancel
                                                </span>
                                        </button>
                                </center>

                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                    </div>
                </div>
            </div>
        </div>
   
    <?php
   }

   public function otherAdjModal(){
	$allocationID=trim($this->input->post('allocationId'));
	$id=trim($this->input->post('id'));
	$current_emp_Id=trim($this->input->post('current_emp_Id'));
	$bills=$this->FieldStaffModel->loadBillsDetails('bills',$id);
	
 ?>
	
	 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		 <div class="card">
			 <div class="header">
				 <h4 style="color: #099dba;">
				 <span style="color: black;">Retailer :</span>
				 <?php 
					 if(!empty($bills)){
						  echo $bills[0]['name'];
					 }
				 ?>
				  &nbsp; &nbsp;
				<span style="color: black;">Bill No :</span> 
				 <?php 
					 if(!empty($bills)){
						  echo $bills[0]['billNo'];
					 }
				 ?>
				 &nbsp; &nbsp;
			   <span style="color: black;">Date :</span>
				 <?php
					 if(!empty($bills)){
						 $dt=date_create($bills[0]['date']);
						 $date = date_format($dt,'d-m-Y');
						 echo $date;
					 }
				 ?>
				 &nbsp;&nbsp;<br><br>
			<span style="color: black;">Bill Amount :</span>
				 <?php
					 if(!empty($bills)){
						 echo number_format($bills[0]['netAmount'],2);
					 }
				 ?>
				 &nbsp;&nbsp;
			 <span style="color: black;">Pending Amount :</span>
				 <?php
					 if(!empty($bills)){
						 echo number_format(($bills[0]['pendingAmt']-$bills[0]['fsSrAmt']),2);
					 }
				 ?>
				 </h4>
			 </div>
				 
			 <div class="body">
			 <div class="table-responsive">
			 <div class="body">
				 <div class="demo-masked-input">
					 <form method="post" role="form" action="<?php echo site_url('manager/FieldStaffController/otherAdjUpdate');?>" onsubmit="return checkOtherAdjBillAmt('<?php echo $bills[0]['netAmount']; ?>','<?php echo $bills[0]['pendingAmt']; ?>','<?php echo $bills[0]['SRAmt']; ?>','<?php echo $bills[0]['receivedAmt']; ?>','<?php echo $bills[0]['fsCashAmt']; ?>','<?php echo $bills[0]['fsChequeAmt']; ?>','<?php echo $bills[0]['fsSrAmt']; ?>','<?php echo $bills[0]['chequePenalty']; ?>','<?php echo $bills[0]['cd']; ?>','<?php echo $bills[0]['debit']; ?>','<?php echo $bills[0]['officeAdjustmentBillAmount']; ?>','<?php echo $bills[0]['otherAdjustment']; ?>','<?php echo $bills[0]['debitNoteAmount']; ?>','<?php echo $bills[0]['fsNeftAmt']; ?>','<?php echo $bills[0]['debitNoteJournalAmt']; ?>');"> 
					 <div class="row clearfix">
						  <input type="hidden" name="id" value="<?php
						 if(isset($bills))
						   {
							 if(!empty($bills[0]['id'])){
								 echo $bills[0]['id'];
							 }
						   }
						 ?>">
						 <input type="hidden" name="allocationID" value="<?php echo $allocationID; ?>">
						 <input type="hidden" name="empID" value="<?php echo $current_emp_Id; ?>">
						 <div class="col-md-3"></div>
						 <div class="col-md-6">                                       
							 <p>
							   <b>Other Adjustment Amount </b>
							 </p>
							  <div class="form-line">
									 <input onkeypress="return numbersonly(this, event);" onfocus="this.select();" autofocus="autofocus" type="text" id="amt" name="cashAmt" value="<?php if(isset($bills)){ echo $bills[0]['fsOtherAdjAmt']; }?>" class="other-amt form-control">           
								 </div>  <br>
								 <div style="color:red;" id="otherRes"></div>          
						 </div>
						 <div class="col-md-3"></div>
					 </div>

					 <div class="col-md-12">
						 <div class="row clearfix">
							 <center>                                               
									 <button type="submit" class="btn btn-primary m-t-15 waves-effect">
										 <i class="material-icons">save</i> 
										 <span class="icon-name">
										 Save
										 </span>
									 </button> 
									<button data-dismiss="modal" type="button" class="btn btn-primary m-t-15 waves-effect">
											 <i class="material-icons">cancel</i> 
											 <span class="icon-name">
											 cancel
											 </span>
									 </button>
							 </center>

						 </div>
					 </div>
					 <?php echo form_close(); ?>
				 </div>
				 </div>
			 </div>
		 </div>
	 </div>

 <?php
}

  	public function confirmFSR(){
  		$allocationId=trim($this->input->post('allocationId'));
   		$billStatus=trim($this->input->post('billStatus'));
   		$billId=trim($this->input->post('billId'));
   		$billNetAmt=$this->FieldStaffModel->load('bills', $billId);
   		$fixNetAmt=$billNetAmt[0]['netAmount'];//net amount from bills by billid

   		$creditAdj=(int)($billNetAmt[0]['creditAdjustment']);
   		$totalFSR=$creditAdj+$fixNetAmt;
   		// echo $creditAdj;exit;

   		if($creditAdj>0){
   			$billDetail=$this->FieldStaffModel->loadBillDetails('billsdetails', $billId);
	   		$srTotalFs=0;
	   		$id=0;
	   		foreach($billDetail as $bill){
			    $id = $bill['id'];
			    $billId = $bill['billId'];
			    $name= $bill['productName'];
			    $mrp = $bill['mrp'];
			    $qty = $bill['qty'];

			    $netAmount = $bill['netAmount'];
			    $returnedQty = $bill['fsReturnQty'];
			    $returnAmt = $bill['fsReturnAmt'];

			    //check return qty
			    $actReturnQty=$qty-$returnedQty;
			  	$calAmt=0;
		        	
	    		$calAmt=$netAmount/$qty;

	    		$ReturnAmount=$returnAmt+($calAmt * $actReturnQty);

	            $data['billsdetails']=$this->FieldStaffModel->loadBillDetailsID('billsdetails', $id);
	            $oldSR=$returnedQty+$actReturnQty;
	            $srTotalFs=$srTotalFs+$ReturnAmount;
	            
	           	$data = array(
	           		'fsReturnQty' => $oldSR,
	              	'fsReturnAmt' =>  $ReturnAmount
	            );

	            $this->FieldStaffModel->update('billsdetails',$data,$id);
	            if($this->db->affected_rows()>0){
	            	
		            $checkSrDetail=$this->FieldStaffModel->loadSrDetails('allocation_sr_details',$billId,$allocationId,$id);
		            if(empty($checkSrDetail)){
		            	$srData = array(
			           		'allocationId' => $allocationId,
			              	'billId' =>  $billId,
			              	'billItemId'=>$id,
			              	'quantity'=>$oldSR,
			              	'createdAt'=>date('Y-m-d H:i:sa')
			            );
			            $this->FieldStaffModel->insert('allocation_sr_details',$srData);
		            }else{
		            	$srData = array(
			           		'allocationId' => $allocationId,
			              	'billId' =>  $billId,
			              	'billItemId'=>$id,
			              	'quantity'=>$oldSR,
			              	'createdAt'=>date('Y-m-d H:i:sa')
			            );
			            $this->FieldStaffModel->updateSrDetail('allocation_sr_details',$srData,$allocationId,$billId,$id);
		            }
	            } else {
	                echo "Fail";
	            }
	   		}
	   		// $fixNetAmt=round($fixNetAmt);
	   		$dataBills = array('isFsrBill'=>1,'isResendBill'=>'0','fsbillStatus' => $billStatus,'fsSrAmt' => $fixNetAmt,'creditNoteRenewal'=>$creditAdj);  
	        $this->FieldStaffModel->update('bills',$dataBills, $billId);
   		}else{
   			$billDetail=$this->FieldStaffModel->loadBillDetails('billsdetails', $billId);
	   		$srTotalFs=0;
	   		$id=0;
	   		foreach($billDetail as $bill){
			    $id = $bill['id'];
			    $billId = $bill['billId'];
			    $name= $bill['productName'];
			    $mrp = $bill['mrp'];
			    $qty = $bill['qty'];

			    $netAmount = $bill['netAmount'];
			    $returnedQty = $bill['fsReturnQty'];
			    $returnAmt = $bill['fsReturnAmt'];

			    //check return qty
			    $actReturnQty=$qty-$returnedQty;
			  	$calAmt=0;
		        	
	    		$calAmt=$netAmount/$qty;

	    		$ReturnAmount=$returnAmt+($calAmt * $actReturnQty);

	            $data['billsdetails']=$this->FieldStaffModel->loadBillDetailsID('billsdetails', $id);
	            $oldSR=$returnedQty+$actReturnQty;
	            $srTotalFs=$srTotalFs+$ReturnAmount;
	            
	           	$data = array(
	           		'fsReturnQty' => $oldSR,
	              	'fsReturnAmt' =>  $ReturnAmount
	            );

	            $this->FieldStaffModel->update('billsdetails',$data,$id);
	            if($this->db->affected_rows()>0){
	            	
		            $checkSrDetail=$this->FieldStaffModel->loadSrDetails('allocation_sr_details',$billId,$allocationId,$id);
		            if(empty($checkSrDetail)){
		            	$srData = array(
			           		'allocationId' => $allocationId,
			              	'billId' =>  $billId,
			              	'billItemId'=>$id,
			              	'quantity'=>$oldSR,
			              	'createdAt'=>date('Y-m-d H:i:sa')
			            );
			            $this->FieldStaffModel->insert('allocation_sr_details',$srData);
		            }else{
		            	$srData = array(
			           		'allocationId' => $allocationId,
			              	'billId' =>  $billId,
			              	'billItemId'=>$id,
			              	'quantity'=>$oldSR,
			              	'createdAt'=>date('Y-m-d H:i:sa')
			            );
			            $this->FieldStaffModel->updateSrDetail('allocation_sr_details',$srData,$allocationId,$billId,$id);
		            }
	            } else {
	                echo "Fail";
	            }
	   		}
	   		// $fixNetAmt=round($fixNetAmt);
	   		$dataBills = array('isFsrBill'=>1,'isResendBill'=>'0','fsbillStatus' => $billStatus,'fsSrAmt' => $fixNetAmt);  
	        $this->FieldStaffModel->update('bills',$dataBills, $billId);
   		}

   		
    }

    public function cancelFSR(){
    	$allocationId=trim($this->input->post('allocationId'));
   		$billStatus=trim($this->input->post('billStatus'));
   		$billId=trim($this->input->post('billId'));

   		$billDetail=$this->FieldStaffModel->loadBillDetails('billsdetails', $billId);
   		$id=0;
   		foreach($billDetail as $bill){
   			$id = $bill['id'];
           	$data = array(
           		'fsReturnQty' => 0,
              	'fsReturnAmt' => 0
            );
            $this->FieldStaffModel->update('billsdetails',$data,$id);
            if($this->db->affected_rows()>0){
            	$checkSrDetail=$this->FieldStaffModel->loadSrDetails('allocation_sr_details',$billId,$allocationId,$id);
	            if(!empty($checkSrDetail)){
	            	$srData = array(
		           		'allocationId' => $allocationId,
		              	'billId' =>  $billId,
		              	'billItemId'=>$id,
		              	'quantity'=>0,
		              	'createdAt'=>date('Y-m-d H:i:sa')
		            );
			        $this->FieldStaffModel->updateSrDetail('allocation_sr_details',$srData,$allocationId,$billId,$id);
			    }
            } else {
                echo "Fail";
            }
   		}
   		$dataBills = array('isFsrBill'=>0,'isResendBill'=>'0','fsbillStatus' => '','fsSrAmt' => 0,'creditNoteRenewal'=>0);  
        $this->FieldStaffModel->update('bills',$dataBills, $billId);
    }

    public function managerHissabUpdate(){
    	$allocationId=trim($this->input->post('id'));
    	$update=array('managerHisaabStatus' => 1);
		$this->FieldStaffModel->update('allocations',$update,$allocationId);

		$this->load->model('FieldStaffModel');
		$checkSrDetails=$this->FieldStaffModel->getAllocationBills('allocationsbills',$allocationId);
    	$srFlag=0;
    	if(!empty($checkSrDetails)){
    		foreach($checkSrDetails as $itm){
    			if (strpos($itm['fsbillStatus'], 'SR')!== false) {
    				$srFlag=1;
    				break;
    			}
    		}
    	}

		// $data['otherAdj']=$this->FieldStaffModel->getOtherAdjAllocatedBills('allocations',$allocationId);

		$otherCheck=$this->FieldStaffModel->load('tbl_settings',3);
			
		$data['otherAdj']=array();
		if(!empty($otherCheck)){
			if($otherCheck[0]['propertyValue']=="no"){
				$data['otherAdj']=$this->FieldStaffModel->getOtherAdjAllocatedBills('allocations',$allocationId);
			}
		}

		if(!empty($data['otherAdj'])){

		}else{
			if($srFlag == 0){
				$update=array('gkStatus' => 1);
				$this->FieldStaffModel->update('allocations',$update,$allocationId);
	
				$signedBills=$this->FieldStaffModel->getSignedBills('bills',$allocationId);
				if(empty($signedBills)){
					$data=array('sr_bill_Status'=>1);
					$this->FieldStaffModel->update('allocations',$data,$allocationId);
				}
			}
		}
		

		redirect('AllocationByManagerController/openAllocations');

		// $data['sr']=$this->GodownKeeperModel->getAllocatedBillsType('billsdetails',$allocationId);
		// $data['fsr']=$this->GodownKeeperModel->getAllocatedBillsFSR('billsdetails',$allocationId);
		// $data['srData']=array();
		// $no=0;

		// if(!empty($data['fsr'])){
		// 	for($i=0;$i<count($data['fsr']);$i++){
		// 		$res=array();
		// 		for($j=0;$j<count($data['sr']);$j++){
		// 			$no++;
		// 			if(!in_array($data['sr'][$j]['billNo'],$data['fsr'][$i])){
		// 				$res[]=$data['sr'][$j];
		// 				array_push($data['srData'], $res);
		// 			}
		// 		}
		// 	}
		// }else{
		// 	$data['srData']=$data['sr'];
		// }
		
		// if(!empty($res)){
		// 	$data['srData']=$res;
		// }

		// if(!empty($data['sr']) || !empty($data['fsr']) || !empty($data['srData'])){
		// 	// $this->load->view('Godownkeeper/GodownkeeperView',$data);
		// }else{
		// 	$updateBillDetails=array('gkStatus' => 1);
		// 	$this->GodownKeeperModel->update('allocations',$updateBillDetails,$allocationId);
		// 	$data['allocations']=$this->GodownKeeperModel->getAllocationDetails('allocations');
  //   		redirect('AllocationByManagerController/openAllocations');
		// }
    }
    
}
?>