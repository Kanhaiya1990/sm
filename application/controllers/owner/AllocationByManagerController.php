<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AllocationByManagerController extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('AllocationByManagerModel');
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
		
		$designation = ($this->session->userdata[$this->projectSessionName]['designation']);
        $des=explode(',',$designation);
		if (in_array('owner', $des)=== false) { 
			redirect("DashbordController");
		}


	}

	public function nonAllocatedBillsDetails(){
		$data['bills']=$this->AllocationByManagerModel->getNonAllocatedBills('bills');
		$this->load->view('Manager/NonAllocatedBillsView',$data);
	}

	public function closedAllocationTrancationDetails($allocationId){
		$data['allocationId']=$allocationId;
		$data['allocations']=$this->AllocationByManagerModel->load('allocations',$allocationId);
		$data['signed']=$this->AllocationByManagerModel->getAllocationSignedBills('bills',$allocationId);

		$data['payment']=$this->AllocationByManagerModel->getAllocationBillPaymentsDetails('bills',$allocationId);


		/////////
		$id=$allocationId;
		 $data['bills']=$this->AllocationByManagerModel->getAllocatedBills('bills',$id);
		 $data['empDebit']=$this->AllocationByManagerModel->loadByAllocationId('emptransactions',$id);
        $data['allocations']=$this->AllocationByManagerModel->load('allocations',$id);

        $data["current"]=array();
        $data["bounced"]=array();
        $data["pass"]=array();
        $data["slip"]=array();
        $count=0;
        $total=0;
        foreach ($data['bills'] as $items) {
            if($items['billType']=='allocatedbillCurrent'){
                $data['current']=$this->AllocationByManagerModel->getAllocatedBillsByType('bills',$id,$items['billType'],'1');
                
            }else if(($items['billType']==='allocatedbillPass') || ($items['billType']==='adHocDeliveryBill') || ($items['billType']==='officeAdjustmentBill')){
                $data['pass']=$this->AllocationByManagerModel->getAllocatedPastBillsByType('bills',$id);
                
            }else if($items['billType']=='allocatedbillDS'){
                $data['slip']=$this->AllocationByManagerModel->getAllocatedBillsByType('bills',$id,$items['billType'],'1');
            }else if($items['billType']=='allocatedbillBounce'){
                $data['bounced']=$this->AllocationByManagerModel->getAllocatedBillsByType('bills',$id,$items['billType'],'1');
            }
        }

        //Total Allocated Bills
        $count=$count+count($data['current'])+count($data['pass'])+count($data['slip'])+count($data['bounced']);
       
        $cashBillTotal=0;
        $chequeNeftTotal=0; 
        $chequeNeftCount=0;

        //Total Allocated bills Amount Total : 
        for($i=0;$i<count($data['current']);$i++){
            if($data['current'][$i]['fsCashAmt'] != '0.00'){
                $cashBillTotal=$cashBillTotal+$data['current'][$i]['fsCashAmt'];
            }

            if($data['current'][$i]['fsChequeAmt'] != '0.00'){
                $chequeNeftTotal=$chequeNeftTotal+$data['current'][$i]['fsChequeAmt'];
                $chequeNeftCount++;
            }

            if($data['current'][$i]['fsNeftAmt'] != '0.00'){
                $chequeNeftCount++;
            }
        }

        for($i=0;$i<count($data['pass']);$i++){
            if($data['pass'][$i]['fsCashAmt'] != '0.00'){
                $cashBillTotal=$cashBillTotal+$data['pass'][$i]['fsCashAmt'];
            }

            if($data['pass'][$i]['fsChequeAmt'] != '0.00'){
                $chequeNeftTotal=$chequeNeftTotal+$data['pass'][$i]['fsChequeAmt'];
                $chequeNeftCount++;
            }
             if($data['pass'][$i]['fsNeftAmt'] != '0.00'){
                $chequeNeftCount++;
            }
        }

        for($i=0;$i<count($data['slip']);$i++){
            if($data['slip'][$i]['fsCashAmt'] != '0.00'){
                $cashBillTotal=$cashBillTotal+$data['slip'][$i]['fsCashAmt'];
            }

            if($data['slip'][$i]['fsChequeAmt'] != '0.00'){
                $chequeNeftTotal=$chequeNeftTotal+$data['slip'][$i]['fsChequeAmt'];
                $chequeNeftCount++;
            }
            if($data['slip'][$i]['fsNeftAmt'] != '0.00'){
                $chequeNeftCount++;
            }
        }

        for($i=0;$i<count($data['bounced']);$i++){
            if($data['bounced'][$i]['fsCashAmt'] != '0.00'){
                $cashBillTotal=$cashBillTotal+$data['bounced'][$i]['fsCashAmt'];
            }

            if($data['bounced'][$i]['fsChequeAmt'] != '0.00'){
                $chequeNeftTotal=$chequeNeftTotal+$data['bounced'][$i]['fsChequeAmt'];
                $chequeNeftCount++;
            }
            if($data['bounced'][$i]['fsNeftAmt'] != '0.00'){
                $chequeNeftCount++;
            }
        }

        $data['cashTotal']=$cashBillTotal;
        $data['chequeTotal']=$chequeNeftTotal;
        $data['chequeCount']=$chequeNeftCount;
        $data['notes']=$this->AllocationByManagerModel->loadByAllocationId('notesdetails',$id);
       $expenses=0.0;
        if(!empty($data['notes'])){
            $n2000=$data['notes'][0]['note2000']*2000;
            $n1000=$data['notes'][0]['note1000']*1000;        
            $n500=$data['notes'][0]['note500']*500;
            $n200=$data['notes'][0]['note200']*200;
            $n100=$data['notes'][0]['note100']*100;
            $n50=$data['notes'][0]['note50']*50;
            $n20=$data['notes'][0]['note20']*20;
            $n10=$data['notes'][0]['note10']*10;
            $coin=$data['notes'][0]['coins'];
            $total=$n2000+$n1000+$n500+$n200+$n100+$n50+$n20+$n10+$coin;
            $expenses=$data['notes'][0]['cng']+$data['notes'][0]['challan']+$data['notes'][0]['parking'];
            if($data['notes'][0]['statusParking']==2){
                 $expenses=$expenses-$data['notes'][0]['parking'];
            }
            if($data['notes'][0]['statusCng']==2){
                $expenses=$expenses-$data['notes'][0]['cng'];
            }
            if($data['notes'][0]['statusChallan']==2){
                $expenses=$expenses-$data['notes'][0]['challan'];
            }
           // echo $expenses;exit;
            $data['total']=$total;
            $data['expenses']=$expenses;
        }
		////////

		$this->load->view('owner/closedAllocationTransactionView',$data);
	}

	public function closedAllocationSrTrancationDetails($allocationId){
		$data['allocationId']=$allocationId;
		$data['allocations']=$this->AllocationByManagerModel->load('allocations',$allocationId);
		$data['sr']=$this->AllocationByManagerModel->getAllocationSrBills('bills',$allocationId);
		$this->load->view('owner/closedAllocationSrTransactionView',$data);
	}

	public function checkValuesByBillno(){
        $billNo=trim($this->input->post('billNo'));
        $exists = $this->AllocationByManagerModel->loadCurrentBillsByNo('bills',$billNo);
        if(empty($exists)) {
            echo "";
        } else {
            echo "Bill Number already present.";
        }
    }

	
	public function index(){
		$data['allocations']=$this->AllocationByManagerModel->getAllocations('allocations');
		$data['officeAllocations']=$this->AllocationByManagerModel->getAllocations('allocations_officeadjustment');
		$this->load->view('openAllocationForAllView',$data);
	}

	public function openAllocations(){
		$this->session->unset_userdata('officeAllocationInfo');
		$this->session->unset_userdata('bouncedBills');
		$this->session->unset_userdata('deliveryBills');
		$this->session->unset_userdata('currentBillIDs');
		$this->session->unset_userdata('routeBills');
		$this->session->unset_userdata('Emp');
		$data['allocations']=$this->AllocationByManagerModel->getAllocations('allocations');
		$data['officeAllocations']=$this->AllocationByManagerModel->getAllocations('allocations_officeadjustment');

		$this->load->view('openAllocationForAllView',$data);
	}

	public function closedAllocations(){
		$this->session->unset_userdata('bouncedBills');
		$this->session->unset_userdata('deliveryBills');
		$this->session->unset_userdata('currentBillIDs');
		$this->session->unset_userdata('routeBills');
		$this->session->unset_userdata('Emp');
		$data['allocations']=$this->AllocationByManagerModel->getClosedAllocations('allocations');
		$this->load->view('owner/ClosedAllocationsView',$data);
	}

	public function CompCurrentBills(){
		$compName=$this->input->post("cmpName");
		$data['billNos']=$this->AllocationByManagerModel->getBillNosByCompany($compName);
        foreach($data['billNos'] as $item){
        	$billNo=$item['billNo']." : ".$item['retailerName'];
   		?>   
          <option value="<?php echo $billNo;?>"/>
	    <?php    
	    }
	}

	

	public function CompPastBills(){
		$compName=$this->input->post("cmpName");
		// $data['pastBillNos']=$this->AllocationByManagerModel->getPastBillsByComp($compName);
			$data['pastBillNos']=$this->AllocationByManagerModel->getPastBillsList($compName);
        foreach($data['pastBillNos'] as $item){
	        $billNo=$item['billNo']." : ".$item['retailerName'];
	   		?>   
	        <option value="<?php echo $billNo;?>"/>
		    <?php    
	    }
	}

	public function CompChequeBills(){
		$compName=$this->input->post("cmpName");
		$data['bounceReturnCheques']=$this->AllocationByManagerModel->bouncedReturnCheques('billpayments',$compName);
		// $chkNo="";

        foreach($data['bounceReturnCheques'] as $item){
	        $chkNo=$item['chequeNo'];
	        ?>
	        <option value="<?php echo $chkNo; ?>"/>
	        <?php
	    }
	}

	public function CompDeliveryBills(){
		$compName=$this->input->post("cmpName");
		$data['deliverySlip']=$this->AllocationByManagerModel->deliverySlipBillNo($compName);
         foreach($data['deliverySlip'] as $item){
	        $billNo=$item['billNo']."-".$item['retailerName'];
	        ?>   
	        <option value="<?php echo $billNo;?>"/>
	        <?php    
        }
	}

	public function cancelTotalAllocation($id){
		$data['allocationId']=$id;
		$details=$this->AllocationByManagerModel->getAllocationDetails('allocations',$id);
		// print_r($details);exit;
		$updateBillInfo=array();
		if(!empty($details)){
			foreach($details as $data){
				$bills=$this->AllocationByManagerModel->load('bills',$data['billId']);
				if($bills[0]['billType']==='allocatedbillPass'){
					$updateBillInfo=array('billType'=>'allocatedbillPass','route'=>'0','isAllocated'=>0);
				}else if($bills[0]['billType']==='allocatedbillCurrent'){
					$updateBillInfo=array('billType'=>'','route'=>'0','isAllocated'=>0);
				}
				// print_r($updateBillInfo);exit;
				// $updateBillInfo=array('billType'=>'','route'=>'0');
				//change bill status
				$this->AllocationByManagerModel->update('bills',$updateBillInfo,$data['billId']);
				//delete allocation details
				$this->AllocationByManagerModel->delete('allocationsbills',$data['id']);
				//delete allocation
				$this->AllocationByManagerModel->delete('allocations',$data['allocationId']);
			}
		}
		redirect('AllocationByManagerController/openAllocations');
	}

	public function Add()
	{


		$compName=$this->input->post("cmpName");
		$this->session->unset_userdata('bouncedBills');
		$this->session->unset_userdata('deliveryBills');
		$this->session->unset_userdata('currentBillIDs');
		$this->session->unset_userdata('routeBills');
		$this->session->unset_userdata('Emp');
		$id=0;
		$allocationCount=$this->AllocationByManagerModel->getNextId('allocations');
		$totalAllocaation=count($allocationCount);

		$data['nextId'] =$allocationCount[0]['id']+1;
		$data['currentId'] =$totalAllocaation;
		$data['employee']=$this->AllocationByManagerModel->getdataEmployee('employee');
		$data['company']=$this->AllocationByManagerModel->getNames('company');
		$data['routeNames']=$this->AllocationByManagerModel->getRouteNames();
		// $data['bouncedCheques']=$this->AllocationByManagerModel->loadBounceBills('bills');
		$data['employeeNames']=$this->AllocationByManagerModel->getEmployeeNames();
		
		if(!empty($compName)){
			$data['billNos']=$this->AllocationByManagerModel->getBillNosByCompany($compName);
			$data['pastBillNos']=$this->AllocationByManagerModel->getPastBillsByComp($compName);
			$data['bounceReturnCheques']=$this->AllocationByManagerModel->bouncedReturnCheques('billpayments',$compName);
			$data['deliverySlip']=$this->AllocationByManagerModel->deliverySlipBillNo();
		}else{
			$compName='Nestle';
			$data['cmpName']='Nestle';
			$data['billNos']=$this->AllocationByManagerModel->getBillNosByCompany($compName);
			$data['pastBillNos']=$this->AllocationByManagerModel->getPastBillsByComp($compName);
			$data['bounceReturnCheques']=$this->AllocationByManagerModel->bouncedReturnCheques('billpayments',$compName);
			$data['deliverySlip']=$this->AllocationByManagerModel->deliverySlipBillNo();
		}

		$this->load->view('AddAllocationByManagerView',$data);
	}

	public function CompleteAllocation($id){
		$session_data = array('sessionAllocationId' => $id);
			
		$this->session->set_userdata('EditCurrentId', $session_data);

		// print_r($this->session->userdata('EditCurrentId'));

		$this->session->unset_userdata('bouncedBills');
		$this->session->unset_userdata('deliveryBills');
		$this->session->unset_userdata('currentBillIDs');
		$this->session->unset_userdata('routeBills');
		$this->session->unset_userdata('Emp');
		$data['allocations']=$this->AllocationByManagerModel->load('allocations',$id);
		$compName=$data['allocations'][0]['company'];
		$data['alID']=$id;
		$data['employee']=$this->AllocationByManagerModel->getdataEmployee('employee');
		$data['company']=$this->AllocationByManagerModel->getNames('company');
		$data['routeNames']=$this->AllocationByManagerModel->getRouteNames();
		$data['employeeNames']=$this->AllocationByManagerModel->getEmployeeNames();
		
		$data['billNos']=$this->AllocationByManagerModel->getBillNosByCompany($compName);
		$data['pastBillNos']=$this->AllocationByManagerModel->getPastBillsByComp($compName);
		$data['bounceReturnCheques']=$this->AllocationByManagerModel->bouncedReturnCheques('billpayments',$compName);
		$data['deliverySlip']=$this->AllocationByManagerModel->deliverySlipBillNo($compName);

		$data["current"]=array();
		$data["ds"]=array();
		$data["pass"]=array();
		$data["slip"]=array();
		$data['bounced']=array();
		$data['bills']=$this->AllocationByManagerModel->getAllocatedBills('bills',$id);

		// echo count($data['bills']);
		// print_r($data['bills']);exit;
		
		foreach ($data['bills'] as $items) {
			if($items['billType']==='allocatedbillCurrent'){
				$data['current']=$this->AllocationByManagerModel->getAllocatedBillsByType('bills',$id,'1');
			}else if(($items['billType']==='allocatedbillPass') || ($items['billType']==='adHocDeliveryBill') || ($items['billType']==='officeAdjustmentBill')){
				$data['pass']=$this->AllocationByManagerModel->getAllocatedPastBillsByType('bills',$id);
			}else if($items['billType']==='allocatedbillDS'){
				$data['slip']=$this->AllocationByManagerModel->getAllocatedBillsByType('bills',$id,'1');
			}else if($items['billType']==='allocatedbillBounce'){
				$ar=$this->AllocationByManagerModel->loadPayBills('allocationsbills',$id);
				for($i=0;$i<count($ar);$i++){
					$a[]=$this->AllocationByManagerModel->getChequeBillsByIDs('billpayments',$ar[$i]['billId']);
				}
				$data['bounced']=$a;
			}
		}

		// print_r($data['pass']);exit;
		if(!empty($data['current'])){
			$response=array();
			foreach($data['current'] as $row){
				$response[] = $row;
			}

			$routeBills='';
			foreach ($response as $items) {
				$routeBills=$items;
				$oldSession =  $this->session->userdata('currentBillIDs');
				if(empty($oldSession)){
					$routes[]=$routeBills;
					$this->session->set_userdata('currentBillIDs', $routes);
				}else{
					if(!in_array($items,$oldSession)){
						array_push($oldSession, $routeBills);
						$this->session->set_userdata('currentBillIDs', $oldSession);
					}
				}
			}
		}

		

		if(!empty($data['pass'])){
			//For Past Bills
			$response1=array();
			foreach($data['pass'] as $row){
				$response1[] = $row;
			}

			$routeBills='';
			foreach ($response1 as $items) {
				$routes=array();
				$routeBills=$items;
				$oldSession =  $this->session->userdata('routeBills');
				if(empty($oldSession)){
					$routes[]=$routeBills;
					$this->session->set_userdata('routeBills', $routes);
				}else{
					if(!in_array($items,$oldSession)){
						array_push($oldSession, $routeBills);
						$this->session->set_userdata('routeBills', $oldSession);
					}
				}
			}
		}
		
		// print_r($this->session->userdata('routeBills'));exit;



		if(!empty($data['slip'])){
			//For Delivery Slip
			$response2=array();
			foreach($data['slip'] as $row){
				$response2[] = $row;
			}
			$routeBills="";
			foreach ($response2 as $items) {
				$routeBills=$items;
				$oldSession =  $this->session->userdata('deliveryBills');
				if(empty($oldSession)){
					$routes[]=$routeBills;
					$this->session->set_userdata('deliveryBills', $routes);
				}else{
					if(!in_array($items,$oldSession)){
						array_push($oldSession, $routeBills);
						$this->session->set_userdata('deliveryBills', $oldSession);
					}
				}
			}
		}

		$this->load->view('EditAllocationByManagerView',$data);
	}

	public function allocationDetailsCheck($id){
		$data['allocations']=$this->AllocationByManagerModel->load('allocations',$id);
		$compName=$data['allocations'][0]['company'];
		$data['alID']=$id;
		$data['employee']=$this->AllocationByManagerModel->getdataEmployee('employee');
		$data['company']=$this->AllocationByManagerModel->getNames('company');
		$data['routeNames']=$this->AllocationByManagerModel->getRouteNames();
		$data['employeeNames']=$this->AllocationByManagerModel->getEmployeeNames();
		
		$data['billNos']=$this->AllocationByManagerModel->getBillNosByCompany($compName);
		$data['pastBillNos']=$this->AllocationByManagerModel->getPastBillsByComp($compName);
		$data['bounceReturnCheques']=$this->AllocationByManagerModel->bouncedReturnCheques('billpayments',$compName);
		$data['deliverySlip']=$this->AllocationByManagerModel->deliverySlipBillNo($compName);

		$data["current"]=array();
		$data["ds"]=array();
		$data["pass"]=array();
		$data["slip"]=array();
		$data['bounced']=array();
		$data['bills']=$this->AllocationByManagerModel->getAllocatedBills('bills',$id);
		
		foreach ($data['bills'] as $items) {
			if($items['billType']=='allocatedbillCurrent'){
				$data['current']=$this->AllocationByManagerModel->getAllocatedBillsByType('bills',$id,'1');
			}else if(($items['billType']==='allocatedbillPass') || ($items['billType']==='adHocDeliveryBill') || ($items['billType']==='officeAdjustmentBill')){
				$data['pass']=$this->AllocationByManagerModel->getAllocatedPastBillsByType('bills',$id);
			}else if($items['billType']=='allocatedbillDS'){
				$data['slip']=$this->AllocationByManagerModel->getAllocatedBillsByType('bills',$id,'1');
			}else if($items['billType']=='allocatedbillBounce'){
				$ar=$this->AllocationByManagerModel->loadPayBills('allocationsbills',$id);
				for($i=0;$i<count($ar);$i++){
					$a[]=$this->AllocationByManagerModel->getChequeBillsByIDs('billpayments',$ar[$i]['billId']);
				}
				$data['bounced']=$a;
			}
		}

		if(!empty($data['current'])){
			$response=array();
			foreach($data['current'] as $row){
				$response[] = $row;
			}

			$routeBills='';
			foreach ($response as $items) {
				$routeBills=$items;
				$oldSession =  $this->session->userdata('currentBillIDs');
				if(empty($oldSession)){
					$routes[]=$routeBills;
					$this->session->set_userdata('currentBillIDs', $routes);
				}else{
					if(!in_array($items,$oldSession)){
						array_push($oldSession, $routeBills);
						$this->session->set_userdata('currentBillIDs', $oldSession);
					}
				}
			}
		}

		if(!empty($data['pass'])){
			//For Past Bills
			$response1=array();
			foreach($data['pass'] as $row){
				$response1[] = $row;
			}

			$routeBills='';
			foreach ($response1 as $items) {
				$routeBills=$items;
				$oldSession =  $this->session->userdata('routeBills');
				if(empty($oldSession)){
					$routes[]=$routeBills;
					$this->session->set_userdata('routeBills', $routes);
				}else{
					if(!in_array($items,$oldSession)){
						array_push($oldSession, $routeBills);
						$this->session->set_userdata('routeBills', $oldSession);
					}
				}
			}
		}

		if(!empty($data['slip'])){
			//For Delivery Slip
			$response2=array();
			foreach($data['slip'] as $row){
				$response2[] = $row;
			}
			$routeBills="";
			foreach ($response2 as $items) {
				$routeBills=$items;
				$oldSession =  $this->session->userdata('deliveryBills');
				if(empty($oldSession)){
					$routes[]=$routeBills;
					$this->session->set_userdata('deliveryBills', $routes);
				}else{
					if(!in_array($items,$oldSession)){
						array_push($oldSession, $routeBills);
						$this->session->set_userdata('deliveryBills', $oldSession);
					}
				}
			}
		}

		if(!empty($data['bounced'])){
			//For Bounced Cheques
			$response3=array();
			foreach($data['bounced'] as $row){
				$response3[] = $row;
			}

			$routeBills="";
			foreach ($response3 as $items) {
				$routeBills=$items;
				$oldSession =  $this->session->userdata('bouncedBills');
				if(empty($oldSession)){
					$routes[]=$routeBills;
					$this->session->set_userdata('bouncedBills', $routes);
				}else{
					if(!in_array($items,$oldSession)){
						array_push($oldSession, $routeBills);
						$this->session->set_userdata('bouncedBills', $oldSession);
					}
				}
			}
		}

		$this->load->view('Manager/allocationDetailsView',$data);
	}

	public function CloseCompleteAllocation($id){
		$compName=$this->input->post("cmpName");
		$data['alID']=$id;
		$data['employee']=$this->AllocationByManagerModel->getdataEmployee('employee');
		$data['company']=$this->AllocationByManagerModel->getNames('company');
		$data['routeNames']=$this->AllocationByManagerModel->getRouteNames();
		$data['employeeNames']=$this->AllocationByManagerModel->getEmployeeNames();
		if(!empty($compName)){
			$data['billNos']=$this->AllocationByManagerModel->getBillNosByCompany($compName);
			$data['pastBillNos']=$this->AllocationByManagerModel->getPastBillsByComp($compName);
			$data['bounceReturnCheques']=$this->AllocationByManagerModel->bouncedReturnCheques('billpayments',$compName);
			$data['deliverySlip']=$this->AllocationByManagerModel->deliverySlipBillNo($compName);
		}else{
			$compName='Nestle';
			$data['cmpName']='Nestle';
			$data['billNos']=$this->AllocationByManagerModel->getBillNosByCompany($compName);
			$data['pastBillNos']=$this->AllocationByManagerModel->getPastBillsByComp($compName);
			$data['bounceReturnCheques']=$this->AllocationByManagerModel->bouncedReturnCheques('billpayments',$compName);
			$data['deliverySlip']=$this->AllocationByManagerModel->deliverySlipBillNo($compName);
		}

		$data["current"]=array();
		$data["ds"]=array();
		$data["pass"]=array();
		$data["slip"]=array();
		$data['bounced']=array();
		$data['bills']=$this->AllocationByManagerModel->getAllocatedBills('bills',$id);
		
		$data['allocations']=$this->AllocationByManagerModel->load('allocations',$id);
		
		foreach ($data['bills'] as $items) {
			
			$data['current']=$this->AllocationByManagerModel->getAllocatedBillsByType('bills',$id,'1');
		

			if(($items['billType']==='allocatedbillPass') || ($items['billType']==='adHocDeliveryBill') || ($items['billType']==='officeAdjustmentBill')){
				$data['pass']=$this->AllocationByManagerModel->getAllocatedPastBillsByType('bills',$id,$items['billType']);
			}else if($items['billType']=='allocatedbillDS'){
				$data['slip']=$this->AllocationByManagerModel->getAllocatedBillsByType('bills',$id,'1');
			}else if($items['billType']=='allocatedbillBounce'){
				$ar=$this->AllocationByManagerModel->loadPayBills('allocationsbills',$id);
				for($i=0;$i<count($ar);$i++){
					$a[]=$this->AllocationByManagerModel->getChequeBillsByIDs('billpayments',$ar[$i]['billId']);
				}
				$data['bounced']=$a;
			}
		}


		if(!empty($data['current'])){
			$response=array();
			//For CurrentSupply
			foreach($data['current'] as $row){
				$response[] = $row;
			}

			$routeBills='';
			foreach ($response as $items) {
				$routeBills=$items;
				$oldSession =  $this->session->userdata('currentBillIDs');
				if(empty($oldSession)){
					$routes[]=$routeBills;
					$this->session->set_userdata('currentBillIDs', $routes);
				}else{
					if(!in_array($items,$oldSession)){
						array_push($oldSession, $routeBills);
						$this->session->set_userdata('currentBillIDs', $oldSession);
					}
				}
			}
		}

		if(!empty($data['pass'])){
			//For Past Bills
			$response1=array();
			foreach($data['pass'] as $row){
				$response1[] = $row;
			}

			$routeBills='';
			foreach ($response1 as $items) {
				$routeBills=$items;
				$oldSession =  $this->session->userdata('routeBills');
				if(empty($oldSession)){
					$routes[]=$routeBills;
					$this->session->set_userdata('routeBills', $routes);
				}else{
					if(!in_array($items,$oldSession)){
						array_push($oldSession, $routeBills);
						$this->session->set_userdata('routeBills', $oldSession);
					}
				}
			}
		}

		if(!empty($data['slip'])){
			//For Delivery Slip
			$response2=array();
			foreach($data['slip'] as $row){
				$response2[] = $row;
			}
			$routeBills="";
			foreach ($response2 as $items) {
				$routeBills=$items;
				$oldSession =  $this->session->userdata('deliveryBills');
				if(empty($oldSession)){
					$routes[]=$routeBills;
					$this->session->set_userdata('deliveryBills', $routes);
				}else{
					if(!in_array($items,$oldSession)){
						array_push($oldSession, $routeBills);
						$this->session->set_userdata('deliveryBills', $oldSession);
					}
				}
				
			}
		}

		if(!empty($data['bounced'])){
			//For Bounced Cheques
			$response3=array();
			foreach($data['bounced'] as $row){
				$response3[] = $row;
			}

			$routeBills="";
			foreach ($response3 as $items) {
				$routeBills=$items;
				$oldSession =  $this->session->userdata('bouncedBills');
				if(empty($oldSession)){
					$routes[]=$routeBills;
					$this->session->set_userdata('bouncedBills', $routes);
				}else{
					if(!in_array($items,$oldSession)){
						array_push($oldSession, $routeBills);
						$this->session->set_userdata('bouncedBills', $oldSession);
					}
				}
			}
		}
		
		$this->load->view('owner/CloseAllocationDetailsView',$data);
	}

	public function allocationInsert(){	
		$data = array
		('date' => date('Y-m-d H:i:sa'),
			'reference' => $this->input->post('reference'),
			'totalCashAmt' => $this->input->post('totalCashAmt'),
			'totalChequeAmt' => $this->input->post('totalChequeAmt'),
			'totalSRAmt' => $this->input->post('totalSRAmt'),
			'fieldStaffCode1' =>$this->input->post('fieldStaffCode1'),                         
			'fieldStaffCode2' => $this->input->post('fieldStaffCode2'),                
			'fieldStaffCode3' => $this->input->post('fieldStaffCode3'),
			'fieldStaffCode4' => $this->input->post('fieldStaffCode4'),
			'routId' =>$this->input->post('routId'),
			'routeCode' =>$this->input->post('routeCode'),                         
			'allocationCode' => $this->input->post('allocationCode')                
		); 
		$result=$this->AllocationByManagerModel->insert('allocations',$data); 
		if(!$result==0){                
			return redirect("AllocationByManagerController");
		} else {
			echo "Fail";
		}

	}
	
	public function getCurrentBills() {
		
		$billId=$this->session->userdata('currentBillIDs');
		$pastbillId=$this->session->userdata('routeBills');
		$bouncedBills=$this->session->userdata('bouncedBills');
		$deliveryBills=$this->session->userdata('deliveryBills');

		$fromNo= $this->input->post('from');
		$toNo= $this->input->post('to');
		$addBill=$this->input->post('addBill');
		$fromNo=explode(" : ",$fromNo);
		$toNo=explode(" : ",$toNo);

		$fromNo=$fromNo[0];
		$toNo=$toNo[0];

		if(!empty($billId)){
			for($i=0;$i<count($billId);$i++) {
				if(trim($billId[$i]['billNo'])==trim($fromNo) || trim($billId[$i]['billNo'])==trim($toNo)) {
					$fromNo = "";
					$toNo="";			
				}
			}
		}
		if(!empty($pastbillId)){
			for($i=0;$i<count($pastbillId);$i++) {
				if(trim($pastbillId[$i]['billNo'])==trim($fromNo) || trim($billId[$i]['billNo'])==trim($toNo)) {
					$fromNo = "";
					$toNo="";			
				}
			}
		}

		// if(!empty($pastbillId)){
		// 	for($i=0;$i<count($bouncedBills);$i++) {
		// 		if(trim($bouncedBills[$i]['billNo'])==trim($fromNo) || trim($billId[$i]['billNo'])==trim($toNo)) {
		// 			$fromNo = "";
		// 			$toNo="";			
		// 		}
		// 	}
		// }
		if(!empty($deliveryBills)){
			for($i=0;$i<count($deliveryBills);$i++) {
				if(trim($deliveryBills[$i]['billNo'])==trim($fromNo) || trim($billId[$i]['billNo'])==trim($toNo)) {
					$fromNo = "";	
					$toNo="";		
				}
			}
		}

		
		$response=array();
		$currentBillIDs=array();
		$newCurrentBills=array();

		if((!empty($from)) || (!empty($toNo))){
			$data['currentBills']=$this->AllocationByManagerModel->loadCurrentBills('bills', $fromNo, $toNo);	
			foreach($data['currentBills'] as $row){
				$response[] = $row;
			}
			
			?>
			<?php

			$no=0;

			$routeBills="";
			foreach ($response as $items) {
				$routeBills=$items;
				$oldSession = $this->session->userdata('currentBillIDs');
				if(empty($oldSession)){
					$routes[]=$routeBills;
					$this->session->set_userdata('currentBillIDs', $routes);
				}else{
					if(!in_array($items,$oldSession)){
						array_push($oldSession, $routeBills);
						$this->session->set_userdata('currentBillIDs', $oldSession);
					}
				}
			}

			$newCurrentBills = $this->session->userdata('currentBillIDs');
			if(!empty($newCurrentBills)){


				foreach($newCurrentBills as $items){

					$resendBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isResendBill');
					$lostBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isLostBill');
					$lostChequesBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isLostCheque');
					$pendingNeftBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isPendingNeft');
					

					$id=$items['id'];
					$pastBillIDs[$no]=$id;
					$no++;

					?>	
					<tr>
						<td><?php echo $no.' '; 
		    				if($items['creditNoteRenewal']>0){ echo '<span class="logo_prov">CN</span>'; }
							if($resendBills>0){ echo '<span class="logo_prov">RS</span>'; }
							if($lostBills>0){ echo '<span class="logo_prov">LB</span>'; }
							if($lostChequesBills>0){ echo '<span class="logo_prov">LC</span>'; }
							if($pendingNeftBills>0){ echo '<span class="logo_prov">PN</span>'; }
							if($items['chequePenalty']>0){ ?>
                 <a href="javascript:void();"  data-trigger="focus" data-container="body" data-toggle="popover" data-placement="right" title="Penalty" data-content="<?php echo $items['chequePenalty']; ?>" class="logo_prov">
                                        CP
                                    </a>
            <?php  }

		    			?></td>
						<td><?php echo $items['billNo']; ?></td>
						<td><?php echo $items['date']; ?></td>
						<td><?php echo $items['retailerName']; ?></td>
						<td><?php echo $items['netAmount']; ?></td>
						<td><?php echo $items['SRAmt']; ?></td> 
						<td><?php echo $items['receivedAmt']; ?></td>
			            <td><?php echo $items['cd']; ?></td>
			            <td><?php echo $items['pendingAmt']; ?></td>
			            <td></td>
			            <td> 
			            	<a>
			            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-primary waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
			            	</a>
			        	</td>
			        </tr>
			        <?php
		    	}
			}
			
		    ?>	
		    <?php
		}else{
			$no=0;
			$newCurrentBills = $this->session->userdata('currentBillIDs');
			
			foreach($newCurrentBills as $items){

				$resendBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isResendBill');
				$lostBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isLostBill');
				$lostChequesBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isLostCheque');
				$pendingNeftBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isPendingNeft');

				$id=$items['id'];
				$pastBillIDs[$no]=$id;
				$no++;

				?>	
				<tr>
					<td><?php echo $no.' '; 
	    				if($items['creditNoteRenewal']>0){ echo '<span class="logo_prov">CN</span>'; }
						if($resendBills>0){ echo '<span class="logo_prov">RS</span>'; }
						if($lostBills>0){ echo '<span class="logo_prov">LB</span>'; }
						if($lostChequesBills>0){ echo '<span class="logo_prov">LC</span>'; }
						if($pendingNeftBills>0){ echo '<span class="logo_prov">PN</span>'; }
						if($items['chequePenalty']>0){ ?>
                 <a href="javascript:void();"  data-trigger="focus" data-container="body" data-toggle="popover" data-placement="right" title="Penalty" data-content="<?php echo $items['chequePenalty']; ?>" class="logo_prov">
                                        CP
                                    </a>
            <?php  }

	    			?></td>
					<td><?php echo $items['billNo']; ?></td>
					<td><?php echo $items['date']; ?></td>
					<td><?php echo $items['retailerName']; ?></td>
					<td><?php echo $items['netAmount']; ?></td>
					<td><?php echo $items['SRAmt']; ?></td> 
					<td><?php echo $items['receivedAmt']; ?></td>
		            <td><?php echo $items['cd']; ?></td>
		            <td><?php echo $items['pendingAmt']; ?></td>
		            <td></td>
		            <td> <a>
		            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-primary waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
		            </a></td>
		        </tr>
		        <?php
		    }
		}
	}

	public function getCurrentBillsWithAdditions() {
		$addBill=$this->input->post('addBill');

		$addBill=explode(" : ",$addBill);
		$addBill=$addBill[0];

		$response=array();
		$currentBillIDs=array();
		
		$billId=$this->session->userdata('currentBillIDs');
		$pastbillId=$this->session->userdata('routeBills');
		$bouncedBills=$this->session->userdata('bouncedBills');
		$deliveryBills=$this->session->userdata('deliveryBills');

		if(!empty($billId)){
			for($i=0;$i<count($billId);$i++) {
				if(trim($billId[$i]['billNo'])==trim($addBill)) {
					$addBill = "";
				}
			}
		}
		
		if(!empty($pastbillId)){
			for($i=0;$i<count($pastbillId);$i++) {
				if(trim($pastbillId[$i]['billNo'])==trim($addBill)) {
					$addBill = "";
				}
			}
		}
		// if(!empty($bouncedBills)){
		// 	for($i=0;$i<count($bouncedBills);$i++) {
		// 		if(trim($bouncedBills[$i]['billNo'])==trim($addBill)) {
		// 			$addBill = "";
		// 		}
		// 	}
		// }
		if(!empty($deliveryBills)){
			for($i=0;$i<count($deliveryBills);$i++) {
				if(trim($deliveryBills[$i]['billNo'])==trim($addBill)) {
					$addBill = "";	
				}
			}
		}

   
		if((!empty($addBill))){
			$data['currentBills']=$this->AllocationByManagerModel->loadCurrentBillsByNo('bills', $addBill);
			foreach($data['currentBills'] as $row){	
				$response[] = $row;
			}

		?>

		<?php
			$no=0;

			$routeBills="";
			foreach ($response as $items) {
				$routeBills=$items;
				$oldSession =$this->session->userdata('currentBillIDs');
				if(empty($oldSession)){
					$routes[]=$routeBills;
					$this->session->set_userdata('currentBillIDs', $routes);
				}else{
					if(!in_array($items,$oldSession)){
						array_push($oldSession, $routeBills);
						$this->session->set_userdata('currentBillIDs', $oldSession);
					}
				}
			}

			$newCurrentBills = $this->session->userdata('currentBillIDs');
			if(!empty($newCurrentBills)){
				foreach($newCurrentBills as $items){

					$resendBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isResendBill');
					$lostBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isLostBill');
					$lostChequesBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isLostCheque');
					$pendingNeftBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isPendingNeft');

					$id=$items['id'];
					$pastBillIDs[$no]=$id;
					$no++;

					?>	
					<tr>
						<td><?php echo $no.' '; 
		    				if($items['creditNoteRenewal']>0){ echo '<span class="logo_prov">CN</span>'; }
							if($resendBills>0){ echo '<span class="logo_prov">RS</span>'; }
							if($lostBills>0){ echo '<span class="logo_prov">LB</span>'; }
							if($lostChequesBills>0){ echo '<span class="logo_prov">LC</span>'; }
							if($pendingNeftBills>0){ echo '<span class="logo_prov">PN</span>'; }
							if($items['chequePenalty']>0){ ?>
                 <a href="javascript:void();"  data-trigger="focus" data-container="body" data-toggle="popover" data-placement="right" title="Penalty" data-content="<?php echo $items['chequePenalty']; ?>" class="logo_prov">
                                        CP
                                    </a>
            <?php  }
		    			?></td>
						<td><?php echo $items['billNo']; ?></td>
						<td><?php echo $items['date']; ?></td>

						<td><?php echo $items['retailerName']; ?></td>
						<td><?php echo $items['netAmount']; ?></td>
						<td><?php echo $items['SRAmt']; ?></td> 
						<td><?php echo $items['receivedAmt']; ?></td>
			            <td><?php echo $items['cd']; ?></td>
			            <td><?php echo $items['pendingAmt']; ?></td>
			            <td>0.00</td>
			            <!-- <td><?php echo $items['creditAdjustment']; ?></td> -->
			            <td> <a>
			            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-primary waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
			            </a></td>
				    </tr>
				    <?php
		    	}
			}
			
		    ?>	
		    <?php
		}else{
		    $no=0;
			$newCurrentBills = $this->session->userdata('currentBillIDs');
			
			foreach($newCurrentBills as $items){

				$resendBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isResendBill');
				$lostBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isLostBill');
				$lostChequesBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isLostCheque');
				$pendingNeftBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isPendingNeft');

				$id=$items['id'];
				$pastBillIDs[$no]=$id;
				$no++;

				?>	
				<tr>
					<td><?php echo $no.' '; 
	    				if($items['creditNoteRenewal']>0){ echo '<span class="logo_prov">CN</span>'; }
						if($resendBills>0){ echo '<span class="logo_prov">RS</span>'; }
						if($lostBills>0){ echo '<span class="logo_prov">LB</span>'; }
						if($lostChequesBills>0){ echo '<span class="logo_prov">LC</span>'; }
						if($pendingNeftBills>0){ echo '<span class="logo_prov">PN</span>'; }
						if($items['chequePenalty']>0){ ?>
                 <a href="javascript:void();"  data-trigger="focus" data-container="body" data-toggle="popover" data-placement="right" title="Penalty" data-content="<?php echo $items['chequePenalty']; ?>" class="logo_prov">
                                        CP
                                    </a>
            <?php  }
	    			?></td>
					<td><?php echo $items['billNo']; ?></td>
					<td><?php echo $items['date']; ?></td>
					<td><?php echo $items['retailerName']; ?></td>
					<td><?php echo $items['netAmount']; ?></td>
					<td><?php echo $items['SRAmt']; ?></td> 
					<td><?php echo $items['receivedAmt']; ?></td>
		            <td><?php echo $items['cd']; ?></td>
		            <td><?php echo $items['pendingAmt']; ?></td>
		            <td>0.00</td>
		            <!-- <td><?php echo $items['creditAdjustment']; ?></td> -->
		            <td> <a>
		            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-primary waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
		            </a></td>
		        </tr>
		        <?php
		    }
		}
	}

public function getPastBills(){
	$response=array();
	$pastBillIDs=array();
	$newRouteBills=array();
    	//bill no
	$billNo=$this->input->post('pName');
	$billNo=explode(" : ",$billNo);
	$billNo=$billNo[0];
	$routeName=$this->input->post('routeName');

	$billId=$this->session->userdata('currentBillIDs');
	$pastbillId=$this->session->userdata('routeBills');

	// print_r($pastbillId);exit;
	$bouncedBills=$this->session->userdata('bouncedBills');
	$deliveryBills=$this->session->userdata('deliveryBills');

	if(!empty($billId)){
		for($i=0;$i<count($billId);$i++) {
			if(trim($billId[$i]['billNo'])==trim($billNo)) {
				$billNo = "";			
			}
		}
	}

	if(!empty($passbillId)){
		for($i=0;$i<count($pastbillId);$i++) {
			if(trim($pastbillId[$i]['billNo'])==trim($billNo)) {
				$billNo = "";			
			}
		}
	}	

	// if(!empty($bouncedBills)){
	// 	for($i=0;$i<count($bouncedBills);$i++) {
	// 		if(trim($bouncedBills[$i]['billNo'])==trim($billNo)) {
	// 			$billNo = "";			
	// 		}
	// 	}
	// }

	if(!empty($deliveryBills)){
		for($i=0;$i<count($deliveryBills);$i++) {
			if(trim($deliveryBills[$i]['billNo'])==trim($billNo)) {
				$billNo = "";			
			}
		}
	}

	// print_r($this->session->userdata('routeBills'));exit;

	$date=date('Y-m-d');
	if(!empty($billNo)){
		$data['pastBills']=$this->AllocationByManagerModel->getPastBilldata('bills',$date,$billNo);

		foreach($data['pastBills'] as $row){
			$response[] = $row;
		}
		
		?>
		<?php

		$no=0;

		$routeBills="";
		$oldSession=array();
		foreach ($response as $items) {
			$routeBills=$items;
			$oldSession =  $this->session->userdata('routeBills');
			if(empty($oldSession)){
				$routes[]=$routeBills;
				$this->session->set_userdata('routeBills', $routes);
			}else{
				if(!in_array($items,$oldSession)){
					array_push($oldSession, $routeBills);
					$this->session->set_userdata('routeBills', $oldSession);
				}
					
			}
		}

		$newRouteBills = $this->session->userdata('routeBills');

		if(!empty($newRouteBills)){
			foreach($newRouteBills as $items){

				$resendBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isResendBill');
				$lostBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isLostBill');
				$lostChequesBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isLostCheque');
				$pendingNeftBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isPendingNeft');
				$bouncedBill=$this->AllocationByManagerModel->checkBouncedBill('billpayments',$items['id']);


			$id=$items['id'];
			$pastBillIDs[$no]=$id;
			$no++;

			?>
			<tr>
				<td><?php echo $no.' '; 
    				if($items['creditNoteRenewal']>0){ echo '<span class="logo_prov">CN</span>'; }
					if($resendBills>0){ echo '<span class="logo_prov">RS</span>'; }
					if($lostBills>0){ echo '<span class="logo_prov">LB</span>'; }
					if($lostChequesBills>0){ echo '<span class="logo_prov">LC</span>'; }
					if($pendingNeftBills>0){ echo '<span class="logo_prov">PN</span>'; }
					if($bouncedBill>0){ echo '<span class="logo_prov">BC</span>'; }
					if($items['chequePenalty']>0){ ?>
                 <a href="javascript:void();"  data-trigger="focus" data-container="body" data-toggle="popover" data-placement="right" title="Penalty" data-content="<?php echo $items['chequePenalty']; ?>" class="logo_prov">
                                        CP
                                    </a>
            <?php  }
    			?></td>
				<td><?php echo $items['billNo']; ?></td>
				<td><?php echo $items['date']; ?></td>

				<td><?php echo $items['retailerName']; ?></td>
				<td><?php echo $items['netAmount']; ?></td>
				<td><?php echo $items['SRAmt']; ?></td>
				<td><?php echo $items['receivedAmt']; ?></td>
		            <!-- <td></td> -->
		            <td><?php echo $items['cd']; ?></td>
		            <td><?php echo $items['pendingAmt']; ?></td>
		           <td></td>
		            <td>
		            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-primary waves-effect" data-type="basic">
		            		<i class="material-icons">cancel</i></button>
		            	</td>
		            </tr>
		            <?php
		        }

		        $this->session->set_userdata("pastBillIDs",$pastBillIDs);
		        ?>	
		        <?php
		}
		
		    }else{
		    	$no=0;
		    	$newRouteBills = $this->session->userdata('routeBills');
		    	if(!empty($newRouteBills)){
		    	foreach($newRouteBills as $items){

		    		$resendBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isResendBill');
				$lostBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isLostBill');
				$lostChequesBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isLostCheque');
				$pendingNeftBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isPendingNeft');
				$bouncedBill=$this->AllocationByManagerModel->checkBouncedBill('billpayments',$items['id']);


		    		$id=$items['id'];
		    		$pastBillIDs[$no]=$id;
		    		$no++;

		    		?>
		    		<tr>
		    			<td><?php echo $no.' '; 
		    				if($items['creditNoteRenewal']>0){ echo '<span class="logo_prov">CN</span>'; }
							if($resendBills>0){ echo '<span class="logo_prov">RS</span>'; }
							if($lostBills>0){ echo '<span class="logo_prov">LB</span>'; }
							if($lostChequesBills>0){ echo '<span class="logo_prov">LC</span>'; }
							if($pendingNeftBills>0){ echo '<span class="logo_prov">PN</span>'; }
							if($bouncedBill>0){ echo '<span class="logo_prov">BC</span>'; }
							if($items['chequePenalty']>0){ ?>
                 <a href="javascript:void();"  data-trigger="focus" data-container="body" data-toggle="popover" data-placement="right" title="Penalty" data-content="<?php echo $items['chequePenalty']; ?>" class="logo_prov">
                                        CP
                                    </a>
            <?php  }
		    			?></td>
		    			<td><?php echo $items['billNo']; ?></td>
		    			<td><?php echo $items['date']; ?></td>

		    			<td><?php echo $items['retailerName']; ?></td>
		    			<td><?php echo $items['netAmount']; ?></td>
		    			<td><?php echo $items['SRAmt']; ?></td>
		    			<td><?php echo $items['receivedAmt']; ?></td>
		            <!-- <td></td> -->
		            <td><?php echo $items['cd']; ?></td>
		            <td><?php echo $items['pendingAmt']; ?></td>
		            <td><?php echo $items['creditAdjustment']; ?></td>
		            <td></td>
		            <td>
		            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-primary waves-effect" data-type="basic">
		            		<i class="material-icons">cancel</i></button>
		            	</td>
		            </tr>
		            <?php
		        }
		    }
		    }
		}

	public function getBouncedChequeBills(){
		$response=array();
		$pastBillIDs=array();
		$newRouteBills=array();
	    	//bill no
		$billNo=$this->input->post('pName');
		$billNo=explode(" : ",$billNo);
		$billNo=$billNo[0];
		$routeName=$this->input->post('routeName');

		$billId=$this->session->userdata('currentBillIDs');
		$pastbillId=$this->session->userdata('routeBills');
		$bouncedBills=$this->session->userdata('bouncedBills');
		$deliveryBills=$this->session->userdata('deliveryBills');

		if(!empty($billId)){
			for($i=0;$i<count($billId);$i++) {
				if(trim($billId[$i]['billNo'])==trim($billNo)) {
					$billNo = "";			
				}
			}
		}

		if(!empty($passbillId)){
			for($i=0;$i<count($pastbillId);$i++) {
				if(trim($pastbillId[$i]['billNo'])==trim($billNo)) {
					$billNo = "";			
				}
			}
		}	

		if(!empty($bouncedBills)){
			for($i=0;$i<count($bouncedBills);$i++) {
				if(trim($bouncedBills[$i]['billNo'])==trim($billNo)) {
					$billNo = "";			
				}
			}
		}

		// if(!empty($deliveryBills)){
		// 	for($i=0;$i<count($deliveryBills);$i++) {
		// 		if(trim($deliveryBills[$i]['billNo'])==trim($billNo)) {
		// 			$billNo = "";			
		// 		}
		// 	}
		// }

		$date=date('Y-m-d');
		if(!empty($billNo)){
			
			$data['pastBills']=$this->AllocationByManagerModel->getPastBilldata('bills',$date,$billNo);
			foreach($data['pastBills'] as $row){
				$response[] = $row;
			}
			
			?>
			<?php

			$no=0;

			$routeBills="";
			$oldSession=array();
			foreach ($response as $items) {
				$routeBills=$items;
				$oldSession =  $this->session->userdata('bouncedBills');
				if(empty($oldSession)){
					$routes[]=$routeBills;
					$this->session->set_userdata('bouncedBills', $routes);
				}else{
					if(!in_array($items,$oldSession)){
						array_push($oldSession, $routeBills);
						$this->session->set_userdata('bouncedBills', $oldSession);
					}
						
				}
			}

			$newRouteBills = $this->session->userdata('bouncedBills');
			// print_r($newRouteBills);exit;
			if(!empty($newRouteBills)){
				foreach($newRouteBills as $items){
						$id=$items['id'];
						$pastBillIDs[$no]=$id;
						$no++;

						?>
						<tr>
							<td><?php echo $no; ?></td>
							<td><?php echo $items['billNo']; ?></td>
							<td><?php echo $items['date']; ?></td>

							<td><?php echo $items['retailerName']; ?></td>
							<td><?php echo $items['netAmount']; ?></td>
							<td><?php echo $items['SRAmt']; ?></td>
							<td><?php echo $items['receivedAmt']; ?></td>
				            <td></td>
				            <td><?php echo $items['cd']; ?></td>
				            <td><?php echo $items['pendingAmt']; ?></td>
				            <td><?php echo $items['creditAdjustment']; ?></td>
				            <td>
				            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-primary waves-effect" data-type="basic">
				            		<i class="material-icons">cancel</i></button>
				            	</td>
				            </tr>
				            <?php
				        }

				        $this->session->set_userdata("pastBillIDs",$pastBillIDs);
				        ?>	
				        <?php
					}
			
			    }else{
			    	$no=0;
			    	$newRouteBills = $this->session->userdata('bouncedBills');
			    	if(!empty($newRouteBills)){
			    	foreach($newRouteBills as $items){
			    		$id=$items['id'];
			    		$pastBillIDs[$no]=$id;
			    		$no++;

			    		?>
			    		<tr>
			    			<td><?php echo $no; ?></td>
			    			<td><?php echo $items['billNo']; ?></td>
			    			<td><?php echo $items['date']; ?></td>

			    			<td><?php echo $items['retailerName']; ?></td>
			    			<td><?php echo $items['netAmount']; ?></td>
			    			<td><?php echo $items['SRAmt']; ?></td>
			    			<td><?php echo $items['receivedAmt']; ?></td>
				            <td></td>
				            <td><?php echo $items['cd']; ?></td>
				            <td><?php echo $items['pendingAmt']; ?></td>
				            <td><?php echo $items['creditAdjustment']; ?></td>
				            <td>
				            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-primary waves-effect" data-type="basic">
				            		<i class="material-icons">cancel</i></button>
			            	</td>
			            </tr>
			            <?php
			        }
			    }
			}
		}

		public function getDeliverySlipBills(){
			$response=array();
			$delBillIDs=array();
			$newRouteBills=array();

			$billNo=$this->input->post('delBill');
			$billNo=explode("-",$billNo);
			$billNo=$billNo[0];
            
			$routeName=$this->input->post('routeName');

			$date=date('Y-m-d');
			if(!empty($billNo)){
				$data['pastBills']=$this->AllocationByManagerModel->getDeliverySlipBilldata('bills',$date,$billNo);
				foreach($data['pastBills'] as $row){
					$response[] = $row;
				}

				?>
				<?php
				$no=0;
				$routeBills="";
				
				foreach ($response as $items) {
					$routeBills=$items;
					$oldSession =  $this->session->userdata('currentBillIDs');
					if(empty($oldSession)){
						$routes[]=$routeBills;
						$this->session->set_userdata('currentBillIDs', $routes);
					}else{
						if(!in_array($items,$oldSession)){
							array_push($oldSession, $routeBills);
							$this->session->set_userdata('currentBillIDs', $oldSession);
						}
					}
				}

				$newRouteBills =  $this->session->userdata('currentBillIDs');
				if(!empty($newRouteBills)){
					foreach($newRouteBills as $items){
						$id=$items['id'];
						$delBillIDs[$no]=$id;
						$no++;

						?>
						<tr>
							<td><?php echo $no; ?></td>
							<td><?php echo $items['billNo']; ?></td>
							<td><?php echo $items['date']; ?></td>

							<td><?php echo $items['retailerName']; ?></td>
							<td><?php echo $items['netAmount']; ?></td>
							<td><?php echo $items['SRAmt']; ?></td>
							<td><?php echo $items['receivedAmt']; ?></td>
			           		<td><?php echo $items['cd']; ?></td>
			           		<td><?php echo $items['pendingAmt']; ?></td>
			          
				            <td>
				            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-primary waves-effect" data-type="basic">
				            		<i class="material-icons">cancel</i></button>
				            </td>
			            </tr>
			            <?php
			        }
		        }

		        $this->session->set_userdata("delBillIDs",$delBillIDs);
		        ?>	
		        <?php
		    }
		}

		public function getBouncedBills(){
			$response=array();
			$bouncedBills=array();
			$newRouteBills=array();
	//bill no
			$chequeNo=$this->input->post('chequeNo');
			if(!empty($chequeNo)){
				$data['pastBills']=$this->AllocationByManagerModel->loadDataByChequeNo('billpayments',$chequeNo);
				// print_r($data['pastBills']);exit;
				$routeName=$this->input->post('routeName');

				$date=date('Y-m-d');
				foreach($data['pastBills'] as $row){
					$response[] = $row;
				}

				?>
				<?php


				$no=0;

				$routeBills="";
				foreach ($response as $items) {
					$routeBills=$items;
					$oldSession =  $this->session->userdata('bouncedBills');
					if(empty($oldSession)){
						$routes[]=$routeBills;
						$this->session->set_userdata('bouncedBills', $routes);
					}else{
						if(!in_array($items,$oldSession)){
							array_push($oldSession, $routeBills);
							$this->session->set_userdata('bouncedBills', $oldSession);
						}
				
					}

				}

				$newRouteBills = $this->session->userdata('bouncedBills');
				foreach($newRouteBills as $items){
					$id=$items['id'];
					$bouncedBills[$no]=$id;
					$no++;

					?>
					<tr>
						<td><?php echo $no; ?></td>
						<td><?php echo $items['chNo']; ?></td>
						<td><?php echo date('d-m-Y',strtotime($items['chDate'])); ?></td>
						<td><?php echo $items['retailerName']; ?></td>
						<td><?php echo $items['paidCheque']; ?></td>
						<td><?php echo $items['chPenalty']; ?></td>
						<td><?php echo $items['cd']; ?></td>

		            <td><?php echo ($items['receivedAmt']-$items['paidCheque']); ?></td>
		            <td><?php echo ($items['paidCheque']+$items['chPenalty']); ?></td>
		            <td>
		            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-primary waves-effect" data-type="basic">
		            		<i class="material-icons">cancel</i></button>
		            	</td>
		            </tr>
		            <?php
		        }

		        $this->session->set_userdata("bouncedBillIDs",$bouncedBills);
		        ?>	
		        <?php
		    }
		}

		public function updateStatusForPastBills(){
			$id=$this->input->post('id');
			$status=$this->input->post('status'); 
			$pastBill=$this->input->post('pName');
			$response=array();
			$pastBillIDs=array();

			$data = array
			('id' => $id,
				'deliveryStatus' => $status,
			);  
			$result = $this->AllocationByManagerModel->update('bills',$data, $id);
			if($result==1){
			} else {
				echo "Fail";
			}

			if(!empty($pastBill)){
				$data['pastBills']=$this->AllocationByManagerModel->loadPastBills('bills', $pastBill);	
				foreach($data['pastBills'] as $row){
					$response[] = $row;
				}

				?>
				<?php

				$no=0;

				foreach ($response as $items) {
					$id=$items['id'];
					$pastBillIDs[$no]=$id;
					$no++;
					$status=$items['deliveryStatus'];

					?>
					<tr>
						<td><?php echo $items['id']; ?></td>
						<td><?php echo $items['billNo']; ?></td>
						<td><?php echo $items['date']; ?></td>
						<td><?php echo $items['retailerHeirarchy']; ?></td>
						<td><?php echo $items['netAmount']; ?></td>
						<td><?php echo $items['pendingAmt']; ?></td>
						<td><?php echo $items['receivedAmt']; ?></td>
			            <td><?php echo $items['pendingAmt']; ?></td>
			            <td><?php echo $items['creditAdjustment']; ?></td>
			            <td>
			            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-primary waves-effect" data-type="basic">
			            		<i class="material-icons">cancel</i></button>
			            	</td>
			            </tr>
			            <?php
			        }
			        $this->session->set_userdata("pastBillIDs",$pastBillIDs);
			        ?>	
			        <?php
			    }else{
			    }
			}

		//Get past bills by Route
			public function loadPastBills(){
				$response=array();
				$pastBillIDs=array();
				$routeBills=array();
				$routeName=$this->input->post('routeName');
				// echo $routeName;exit;

				$date=date('Y-m-d');
				if(!empty($routeName)){
					// echo "he";
					foreach($routeName as $rname){
						$routeData=$this->AllocationByManagerModel->getRouteCodeByName('route',$rname);
						$routeID=$routeData[0]['id'];
						$data['pastBills']=$this->AllocationByManagerModel->loadPastBillsByRoute('bills',$rname);
						// print_r($data['pastBills']);

						// print_r($data['pastBills']);exit;
						foreach($data['pastBills'] as $row){
							$response[] = $row;
						}
					}

					// print_r($response);
                     
					$no=0;

					$pbills="";
					if(!empty($response)){
    					foreach ($response as $items) {
    						$pbills=$items;
    						$oldSession =  $this->session->userdata('routeBills');
    						if(empty($oldSession)){
    							$routes[]=$pbills;
    							$this->session->set_userdata('routeBills', $routes);
    						}else{
    							if(!in_array($items,$oldSession)){
    								array_push($oldSession, $pbills);
    								$this->session->set_userdata('routeBills', $oldSession);
    							}
    						}
    					}
					}
					$newLoads = $this->session->userdata('routeBills');
					if(!empty($newLoads)){
						foreach ($newLoads as $items) {
							$resendBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isResendBill');
							$lostBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isLostBill');
							$lostChequesBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isLostCheque');
							$pendingNeftBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isPendingNeft');
							$bouncedBill=$this->AllocationByManagerModel->checkBouncedBill('billpayments',$items['id']);

						$id=$items['route'];
						$pastBillIDs[$no]=$id;
						$no++;

						?>
						<tr>
							<td><?php echo $no.' ';
								if($items['creditNoteRenewal']>0){ echo '<span class="logo_prov">CN</span>'; }
								if($resendBills>0){ echo '<span class="logo_prov">RS</span>'; }
								if($lostBills>0){ echo '<span class="logo_prov">LB</span>'; }
								if($lostChequesBills>0){ echo '<span class="logo_prov">LC</span>'; }
								if($pendingNeftBills>0){ echo '<span class="logo_prov">PN</span>'; }
								if($bouncedBill>0){ echo '<span class="logo_prov">BC</span>'; }
								if($items['chequePenalty']>0){ ?>
                 <a href="javascript:void();"  data-trigger="focus" data-container="body" data-toggle="popover" data-placement="right" title="Penalty" data-content="<?php echo $items['chequePenalty']; ?>" class="logo_prov">
                                        CP
                                    </a>
            <?php  }
							 ?></td>
							<td><?php echo $items['billNo']; ?></td>
							<td><?php echo $items['date']; ?></td>
							<td><?php echo $items['retailerName']; ?></td>
							<td><?php echo $items['netAmount']; ?></td>
							<td><?php echo $items['SRAmt']; ?></td>
							<td><?php echo $items['receivedAmt']; ?></td>
							<!-- <td></td> -->
							<td><?php echo $items['cd'];?></td>
        		            <td><?php echo $items['pendingAmt']; ?></td>
        		          <td>0.00</td>
        		            <td>
        		            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-primary waves-effect" data-type="basic">
        		            		<i class="material-icons">cancel</i></button>
        		            </td>
		            </tr>
		            <?php
		        }
					}
					
		    }
		    $this->session->set_userdata("temppastBillIDs",$pastBillIDs);
		    ?>	
		    <?php
		}

		public function clearAllBills(){
			$rname=$this->input->post('routeName');
			$code=$this->AllocationByManagerModel->getRouteID($rname);
			$tempIds=$this->session->userdata('temppastBillIDs');
			if(!empty($tempIds)){
				foreach($tempIds as $itm){
					$id=$itm;
					$currentBills = $this->session->userdata('currentBillIDs');
					if(!empty($currentBills)){
						foreach ($currentBills AS $key => $value) {
							if ($code == $value['route']){
								unset($currentBills[$key]);
							}
						}
						$this->session->set_userdata('currentBillIDs', $currentBills);
					}
					
					$routeBills = $this->session->userdata('routeBills');
					if(!empty($routeBills)){
						foreach ($routeBills AS $key => $value) {
							if ($code == $value['route']){
								unset($routeBills[$key]);
							}
						}
						$this->session->set_userdata('routeBills', $routeBills);
					}
				}

				$newLoads =  $this->session->userdata('routeBills');
				$no=0;
					foreach ($newLoads as $items) {
						$id=$items['route'];
						$no++;

						?>
						<tr>
							<td><?php echo $no; ?></td>
							<td><?php echo $items['billNo']; ?></td>
							<td><?php echo $items['date']; ?></td>
							<td><?php echo $items['retailerName']; ?></td>
							<td><?php echo $items['netAmount']; ?></td>
							<td><?php echo $items['SRAmt']; ?></td>
							<td></td>
							<td></td>
							<td><?php echo $items['cd'];?></td>
		            <td><?php echo $items['pendingAmt']; ?></td>
		            <td></td>
		            <td>
		            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-primary waves-effect" data-type="basic">
		            		<i class="material-icons">cancel</i></button>
		            	</td>
		            </tr>
		            <?php
		        }
			}
			
		}

		public function GetRoute(){
			$keyword=$this->input->post('name');
			$routeNames['RouteNames']=$this->AllocationByManagerModel->GetRow('route',$keyword);  
			$this->load->view('AddAllocationByManagerView',$routeNames);  
		}
		public function GetEmployee(){
			$keyword=$this->input->post('keyword');
			$data=$this->AllocationByManagerModel->GetRow('employee',$keyword);        
			echo json_encode($data);
		}
		public function GetBillNo(){
			$keyword=$this->input->post('keyword');
			$data=$this->AllocationByManagerModel->GetRowBill('bills',$keyword);        
			echo json_encode($data);
		}
		public function GetCheckNo(){
			$keyword=$this->input->post('keyword');
			$data=$this->AllocationByManagerModel->GetCheckNo('billpayments',$keyword);        
			echo json_encode($data);
		}

		public function removeBillIdFromSession(){
			$id=$this->input->post('rmId');

			$currentAllocationBill = $this->session->userdata('EditCurrentId');
			$allocationId=$currentAllocationBill['sessionAllocationId'];


			$checkData=$this->AllocationByManagerModel->checkInfo('allocationsbills',$id,$allocationId);
			$billData=$this->AllocationByManagerModel->load('bills',$id);
			if(!empty($checkData)){
				$this->AllocationByManagerModel->deleteAllocationsBills('allocationsbills',$id,$allocationId);
				if($this->db->affected_rows()>0){
					if($billData[0]['billType']=='allocatedbillCurrent'){
						$rtData=array
						(
							'isLostBill'=>'0','billType'=>'','isResendBill'=>'0','isAllocated'=>'0'	
						);
						$this->AllocationByManagerModel->update('bills',$rtData,$id);
					}else{
						$rtData=array
						(
							'isLostBill'=>'0','isResendBill'=>'0','isAllocated'=>'0'	
						);
						$this->AllocationByManagerModel->update('bills',$rtData,$id);
					}
					
				}
			}

			// $id=$this->input->post('rmId');

			$newCurrentArray=array();
			$currentBills = $this->session->userdata('currentBillIDs');
			foreach ($currentBills AS $key => $value) {
				if ($id == $value['id']){
					unset($currentBills[$key]);
				}
			}

			foreach($currentBills as $newItem){
				$newCurrentArray[]=$newItem;
			}
			$this->session->set_userdata('currentBillIDs', $newCurrentArray);

			$newPastArray=array();
			$routeBills = $this->session->userdata('routeBills');
			foreach ($routeBills AS $key => $value) {
				if ($id == $value['id']){
					unset($routeBills[$key]);
				}
			}

			foreach($routeBills as $newItem){
				$newPastArray[]=$newItem;
			}
			$this->session->set_userdata('routeBills', $newPastArray);
		}

		public function deleteBillIdFromSession(){
			$id=$this->input->post('rmId');
			$allocationId=$this->input->post('allocationId');


			$checkData=$this->AllocationByManagerModel->checkInfo('allocationsbills',$id,$allocationId);
			$billData=$this->AllocationByManagerModel->load('bills',$id);
			if(!empty($checkData)){
				$this->AllocationByManagerModel->deleteAllocationsBills('allocationsbills',$id,$allocationId);
				if($this->db->affected_rows()>0){
					if($billData[0]['billType']=='allocatedbillCurrent'){
						$rtData=array
						(
							'isLostBill'=>'0','billType'=>'','isResendBill'=>'0','isAllocated'=>'0'	
						);
						$this->AllocationByManagerModel->update('bills',$rtData,$id);
					}else{
						$rtData=array
						(
							'isLostBill'=>'0','isResendBill'=>'0','isAllocated'=>'0'	
						);
						$this->AllocationByManagerModel->update('bills',$rtData,$id);
					}
					
				}
			}

			$newCurrentArray=array();
			$currentBills = $this->session->userdata('currentBillIDs');
			foreach ($currentBills AS $key => $value) {
				if ($id == $value['id']){
					unset($currentBills[$key]);
				}
			}

			foreach($currentBills as $newItem){
				$newCurrentArray[]=$newItem;
			}
			$this->session->set_userdata('currentBillIDs', $newCurrentArray);

			$newPastArray=array();
			$routeBills = $this->session->userdata('routeBills');
			foreach ($routeBills AS $key => $value) {
				if ($id == $value['id']){
					unset($routeBills[$key]);
				}
			}

			foreach($routeBills as $newItem){
				$newPastArray[]=$newItem;
			}
			$this->session->set_userdata('routeBills', $newPastArray);


		}

		public function insertAllocationData(){
			$billId=$this->session->userdata('currentBillIDs');
			$pastbillId=$this->session->userdata('routeBills');
			$bouncedBills=$this->session->userdata('bouncedBills');
			$deliveryBills=$this->session->userdata('deliveryBills');

			$company=$this->input->post('company');

			$emp=$this->input->post('emp');
			$emp=array_unique($emp);
			$this->session->set_userdata("Emp",$emp);
			$emp=implode(",", $this->session->userdata('Emp'));
			$emp=explode(",",$emp);
			$empCount=count($emp);

			$rtName=$this->input->post('rtName');
			$rtName=array_unique($rtName);
			$this->session->set_userdata("rtName",$rtName);
			$rtName=implode(",", $this->session->userdata('rtName'));
			$rtName=explode(",",$rtName);
			$rtCount=count($rtName);
	
			$allocationCode=$this->input->post('allocationCode');
			$checkAllocationCode=$this->AllocationByManagerModel->checkAllocationCode('allocations',$allocationCode);	
					
			$reference=$this->input->post('reference');
			$routeName=$this->input->post('routeName');

			$totalCashAmt=0;
			$totalChequeAmt=0;
			$totalSRAmt=0;

			$fieldStaffCode1=0;
			$fieldStaffCode2=0;
			$fieldStaffCode3=0;
			$fieldStaffCode4=0;

			for($i=0;$i<$empCount;$i++){
				$empData['empData']=$this->AllocationByManagerModel->getEmpId('employee',$emp[$i]);
				if($i==0){
					$fieldStaffCode1=$empData['empData'][0]['id'];
				}else if($i==1){
					$fieldStaffCode2=$empData['empData'][0]['id'];
				}else if($i==2){
					$fieldStaffCode3=$empData['empData'][0]['id'];
				}else if($i==3){
					$fieldStaffCode4=$empData['empData'][0]['id'];
				}
			}

			$routeId='';
			$routCode='';
			for($i=0;$i<$rtCount;$i++){
				$routeData['routeData']=$this->AllocationByManagerModel->getRouteCodeByName('route',$rtName[$i]);
				$rID=$routeData['routeData'][0]['id'];
				$rCODE=$routeData['routeData'][0]['code'];
				$routeId=$routeId."".$rID.",";
				$routCode=$routCode."".$rCODE.",";
			}

			$routeId = rtrim($routeId,',');
			$routCode = rtrim($routCode,',');

			$allocationData=array
			('date' => date("Y-m-d H:i:sa"),
				'reference' => $reference,
				'company'=>$company,
				'totalCashAmt' => $totalCashAmt,
				'totalChequeNeftAmt' => $totalChequeAmt,
				'totalSRAmt' => $totalSRAmt,
				'fieldStaffCode1' => $fieldStaffCode1,
				'fieldStaffCode2' => $fieldStaffCode2,
				'fieldStaffCode3' => $fieldStaffCode3,
				'fieldStaffCode4' => $fieldStaffCode4,
				'routId' => $routeId,
				'routeCode' => $routCode,
				'allocationCode' => $allocationCode
			);

			if(empty($checkAllocationCode)){
				$this->AllocationByManagerModel->insert('allocations',$allocationData); 

				if($this->db->affected_rows()>0){ 
					$lastInsertedId=$this->db->insert_id(); 

					if(!empty($billId)){
						foreach($billId as $items){
							$billAllocationData=array
							('billId' => $items['id'],
								'allocationId' => $lastInsertedId,
								'billStatus'=>'1'
							);

							$this->AllocationByManagerModel->insert('allocationsbills',$billAllocationData); 
							if($this->db->affected_rows()>0){
								$rtData=array
								('route' => $routeId,
									'billType' =>'allocatedbillCurrent','isLostBill'=>'0','isResendBill'=>'0','isAllocated'=>'1'	
								);
								$this->AllocationByManagerModel->update('bills',$rtData,$items['id']);
								if($this->db->affected_rows()>0){
									// echo "success";
								}else{
									echo "fails";
								}
							} else {
								echo "fails";
							}
						}
					}

					if(!empty($pastbillId)){
						foreach($pastbillId as $items){
							$billUpdateInfo=$this->AllocationByManagerModel->load('bills',$items['id']);
							
							$billAllocationData=array
							('billId' => $items['id'],
								'allocationId' => $lastInsertedId,
								'billStatus'=>'2'
							);

							$this->AllocationByManagerModel->insert('allocationsbills',$billAllocationData); 
							if($this->db->affected_rows()>0){

								

								if(($billUpdateInfo[0]['billType'] === "officeAdjustmentBill") || ($billUpdateInfo[0]['billType'] === "adHocDeliveryBill")){
									$rtData=array
									('route' => $routeId,'isLostBill'=>'0','isResendBill'=>'0','isAllocated'=>'1'		
									);
									$this->AllocationByManagerModel->update('bills',$rtData,$items['id']);
									if($this->db->affected_rows()>0){
										// echo "success";
									}else{
										echo "fails";
									}
								}else{
									$rtData=array
									('route' => $routeId,'billType' =>'allocatedbillPass','isLostBill'=>'0','isResendBill'=>'0','isAllocated'=>'1'		
									);
									$this->AllocationByManagerModel->update('bills',$rtData,$items['id']);
									if($this->db->affected_rows()>0){
										// echo "success";
									}else{
										echo "fails";
									}
								}
								
							} else {
								echo "fails";
							}
						}
					}

					if(!empty($deliveryBills)){
						foreach($deliveryBills as $items){
							$deliveryBillsData=array
							('billId' => $items['id'],
								'allocationId' => $lastInsertedId,
								'billStatus'=>'3'
							);

							$this->AllocationByManagerModel->insert('allocationsbills',$deliveryBillsData); 
							if($this->db->affected_rows()>0){
								$slipData=array
								('route' => $routeId,
									'billType' =>'allocatedbillDS',
									'isAllocated'=>'1'	
								);
								$this->AllocationByManagerModel->update('bills',$slipData,$items['id']);
								if($this->db->affected_rows()>0){
									// echo "success";
								}else{
									echo "fails";
								}
							} else {
								echo "fails";
							}
						}
					}
					 
					
	   	    
					$this->session->unset_userdata('bouncedBills');
					$this->session->unset_userdata('deliveryBills');
					$this->session->unset_userdata('currentBillIDs');
					$this->session->unset_userdata('routeBills');
					$this->session->unset_userdata('Emp');
					$this->session->unset_userdata('EditCurrentId');
					echo '<script>alert("Record saved successfully.");</script>';
					return redirect('AllocationByManagerController/openAllocations');
				} else {
					echo "Fail";
				}
			}
		}

		public function SaveConfirm(){
	// $rtId=$this->session->userdata('routeBills');
			$billId=$this->session->userdata('currentBillIDs');
			$pastbillId=$this->session->userdata('routeBills');
			$bouncedBills=$this->session->userdata('bouncedBills');
			$deliveryBills=$this->session->userdata('deliveryBills');

			// print_r($billId);
			// print_r($pastbillId);
			// exit;

	// Employees
			$emp=$this->input->post('emp');
			$emp=array_unique($emp);
			$this->session->set_userdata("Emp",$emp);
			$emp=implode(",", $this->session->userdata('Emp'));
			$emp=explode(",",$emp);
			$empCount=count($emp);

	//routes
			$rtName=$this->input->post('rtName');
			$rtName=array_unique($rtName);
			$this->session->set_userdata("rtName",$rtName);
			$rtName=implode(",", $this->session->userdata('rtName'));
			$rtName=explode(",",$rtName);
			$rtCount=count($rtName);

	//Get AllocationCode, Reference,RouteId,RouteName,RouteCode
			$allocationCode=$this->input->post('allocationCode');
			$reference=$this->input->post('reference');
			$routeName=$this->input->post('routeName');

			$totalCashAmt=0;
			$totalChequeAmt=0;
			$totalSRAmt=0;

			$fieldStaffCode1=0;
			$fieldStaffCode2=0;
			$fieldStaffCode3=0;
			$fieldStaffCode4=0;

			for($i=0;$i<$empCount;$i++){
				$empData['empData']=$this->AllocationByManagerModel->getEmpId('employee',$emp[$i]);
				if($i==0){
					$fieldStaffCode1=$empData['empData'][0]['id'];
				}else if($i==1){
					$fieldStaffCode2=$empData['empData'][0]['id'];
				}else if($i==2){
					$fieldStaffCode3=$empData['empData'][0]['id'];
				}else if($i==3){
					$fieldStaffCode4=$empData['empData'][0]['id'];
				}
			}

	///get selected routeId
			$routeId='';
			$routCode='';
			for($i=0;$i<$rtCount;$i++){
				$routeData['routeData']=$this->AllocationByManagerModel->getRouteCodeByName('route',$rtName[$i]);
				$rID=$routeData['routeData'][0]['id'];
				$rCODE=$routeData['routeData'][0]['code'];
				$routeId=$routeId."".$rID.",";
				$routCode=$routCode."".$rCODE.",";
			}

			$routeId = rtrim($routeId,',');
			$routCode = rtrim($routCode,',');

			$id=$this->input->post('allocationCode');
			$data['alloca']=$this->AllocationByManagerModel->loadAllocated('allocations',$id);
			$id=explode("-",$id);
			$data['allocations']=$this->AllocationByManagerModel->loadAllocatedBills('allocationsbills',$data['alloca'][0]['id']);
			
			if(!empty($data['allocations'])){
				for ($j=0; $j < count($data['allocations']); $j++) {
					for ($i=0; $i < count($billId); $i++) { 
						if(!in_array($billId[$i]['id'],$data['allocations'][$j])){
							$chekAlocatedId=$this->AllocationByManagerModel->getDuplicatedAllocatedRecord('allocationsbills',$billId[$i]['id'],$data['alloca'][0]['id']);
							if(empty($chekAlocatedId)){
								$billAllocationData=array
								('billId' => $billId[$i]['id'],
									'allocationId' => $data['alloca'][0]['id'],
									'billStatus'=>'1'
								);

								$res=$this->AllocationByManagerModel->insert('allocationsbills',$billAllocationData); 
								if(!$res==0){
									$rtData=array
									('route' => $routeId,
										'billType' =>'allocatedbillCurrent','isLostBill'=>'0','isResendBill'=>'0','isAllocated'=>'1'	
									);
									$rs=$this->AllocationByManagerModel->update('bills',$rtData,$billId[$i]['id']);
									if(!$rs==0){
										echo "success";
									}else{
										echo "fails";
									}
								} else {
									echo "fails";
								}
							}
						}
					}
				}

				for ($j=0; $j < count($data['allocations']); $j++) {
					for ($i=0; $i < count($pastbillId); $i++) { 
						if(!in_array($pastbillId[$i]['id'],$data['allocations'][$j])){
							$chekAlocatedId=$this->AllocationByManagerModel->getDuplicatedAllocatedRecord('allocationsbills',$pastbillId[$i]['id'],$data['alloca'][0]['id']);

							// print_r($chekAlocatedId);exit;
							if(empty($chekAlocatedId)){
								$billAllocationData=array
								('billId' => $pastbillId[$i]['id'],
									'allocationId' => $data['alloca'][0]['id'],
									'billStatus'=>'2'
								);

								$billUpdateInfo=$this->AllocationByManagerModel->load('bills',$pastbillId[$i]['id']);

								if(($billUpdateInfo[0]['billType'] === "officeAdjustmentBill") || ($billUpdateInfo[0]['billType'] === "adHocDeliveryBill")){
									$this->AllocationByManagerModel->insert('allocationsbills',$billAllocationData); 
									if($this->db->affected_rows()>0){
										$rtData=array
										('route' => $routeId,'isLostBill'=>'0','isResendBill'=>'0','isAllocated'=>'1'		
										);
										$this->AllocationByManagerModel->update('bills',$rtData,$pastbillId[$i]['id']);
										if($this->db->affected_rows()>0){
											echo "success";
										}else{
											echo "fails";
										}
									} else {
										echo "fails";
									}
								}else{
									$this->AllocationByManagerModel->insert('allocationsbills',$billAllocationData); 
									if($this->db->affected_rows()>0){
										$rtData=array
										('route' => $routeId,'isLostBill'=>'0','isResendBill'=>'0','isAllocated'=>'1'		
										);
										$this->AllocationByManagerModel->update('bills',$rtData,$pastbillId[$i]['id']);
										if($this->db->affected_rows()>0){
											echo "success";
										}else{
											echo "fails";
										}
									} else {
										echo "fails";
									}
								}
							}
						}
					}
				}
			}else{
				$allocationData=array
				('date' => date("Y-m-d"),
					'reference' => $reference,
					'totalCashAmt' => $totalCashAmt,
					'totalChequeNeftAmt' => $totalChequeAmt,
					'totalSRAmt' => $totalSRAmt,
					'fieldStaffCode1' => $fieldStaffCode1,
					'fieldStaffCode2' => $fieldStaffCode2,
					'fieldStaffCode3' => $fieldStaffCode3,
					'fieldStaffCode4' => $fieldStaffCode4,
					'routId' => $routeId,
					'routeCode' => $routCode,
					'allocationCode' => $allocationCode
				);

				$result=$this->AllocationByManagerModel->insert('allocations',$allocationData); 

				if(!$result==0){  
    	//last inserted id
					$lastInsertedId=$this->db->insert_id(); 

    	//insert each bill to allocationsbills table
					if(!empty($billId)){
						foreach($billId as $items){
        		// $ids=$billId[$i]['id'];
							$billAllocationData=array
							('billId' => $items['id'],
								'allocationId' => $lastInsertedId,
								'billStatus'=>'1'
							);

							$res=$this->AllocationByManagerModel->insert('allocationsbills',$billAllocationData); 
							if(!$res==0){
								$rtData=array
								('route' => $routeId,
									'billType' =>'allocatedbillCurrent'	
								);
								$rs=$this->AllocationByManagerModel->update('bills',$rtData,$items['id']);
								if(!$rs==0){
									echo "success";
								}else{
									echo "fails";
								}
							} else {
								echo "fails";
							}
						}
					}
				}
			}
			$this->session->unset_userdata('bouncedBills');
			$this->session->unset_userdata('deliveryBills');
			$this->session->unset_userdata('currentBillIDs');
			$this->session->unset_userdata('routeBills');
			$this->session->unset_userdata('Emp');
			$this->session->unset_userdata('EditCurrentId');
		}
	}
?>