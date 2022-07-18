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
	}


	public function updateAllocationEmployees(){
		$allocationId=$this->input->post('allocationId');
		$emp=$this->input->post('emp');
		$emp=array_unique($emp);

		$emp=implode(",",$emp);

		$empNameData=$emp;
		
		$emp=explode(",",$emp);
		$empCount=count($emp);

		$empName="";
		$fieldStaffCode1=0;
		$fieldStaffCode2=0;
		$fieldStaffCode3=0;
		$fieldStaffCode4=0;
		for($i=0;$i<$empCount;$i++){
			$empData['empData']=$this->AllocationByManagerModel->getEmpId('employee',$emp[$i]);
			if(!empty($empData['empData'])){
				if($i==0){
					$fieldStaffCode1=$empData['empData'][0]['id'];
					if($empName==""){
						$empName=$emp[$i];
					}else{
						$empName=$empName.','.$emp[$i];
					}
					
				}else if($i==1){
					$fieldStaffCode2=$empData['empData'][0]['id'];
					if($empName==""){
						$empName=$emp[$i];
					}else{
						$empName=$empName.','.$emp[$i];
					}
				}else if($i==2){
					$fieldStaffCode3=$empData['empData'][0]['id'];
					if($empName==""){
						$empName=$emp[$i];
					}else{
						$empName=$empName.','.$emp[$i];
					}
				}else if($i==3){
					$fieldStaffCode4=$empData['empData'][0]['id'];
					if($empName==""){
						$empName=$emp[$i];
					}else{
						$empName=$empName.','.$emp[$i];
					}
				}
			}
		}

		if($empName !=""){
			$updateAllocationData=array(
				"fieldStaffCode1"=>$fieldStaffCode1,
				"fieldStaffCode2"=>$fieldStaffCode2,
				"fieldStaffCode3"=>$fieldStaffCode3,
				"fieldStaffCode4"=>$fieldStaffCode4,
				"allocationEmployeeName"=>$empName
			);
			$this->AllocationByManagerModel->update('allocations',$updateAllocationData,$allocationId);
			if($this->db->affected_rows()>0){
				echo "Record Updated...!";
			}else{
				echo "Unable to update record...!";
			}
		}else{
			echo "Please select correct employeee...!";
		}
		
	}

	public function updateAllocationRoutes(){
		$allocationId=$this->input->post('allocationId');
		$route=$this->input->post('route');
		$route=array_unique($route);
		
		$route=implode(",", $route);
		$routeNameData=$route;
		$route=explode(",",$route);
		$rtCount=count($route);


		$routeName="";
		$rID="";
		$rCODE="";
		$routeId='';
		$routCode='';
		$allocationRouteName="";
		for($i=0;$i<$rtCount;$i++){
			$routeData['routeData']=$this->AllocationByManagerModel->getRouteCodeByName('route',$route[$i]);
			if(!empty($routeData['routeData'])){
				$rID=$routeData['routeData'][0]['id'];
				$rCODE=$routeData['routeData'][0]['code'];
				$routeId=$routeId."".$rID.",";
				$routCode=$routCode."".$rCODE.",";
				$allocationRouteName=$allocationRouteName.','.$route[$i];
				if($routeName==""){
					$routeName=$route[$i];
				}else{
					$routeName=$routeName.','.$route[$i];
				}
			}
			
			
		}

		$routeId = rtrim($routeId,',');
		$routCode = rtrim($routCode,',');

		// echo $routeId.' re 	 '.$routCode.' rr '.$routeName.' ';

		if($routeId !=""){
			$updateAllocationData=array(
				"routId"=>$routeId,
				"routeCode"=>$routCode,
				"allocationRouteName"=>$routeName
			);
			$this->AllocationByManagerModel->update('allocations',$updateAllocationData,$allocationId);
			if($this->db->affected_rows()>0){
				echo "Record Updated...!";
			}else{
				echo "Unable to update record...!";
			}
		}else{
			echo "Please select Correct Route";
		}
		
	}

	public function removeRouteBillAddedByRoutes(){
		$routeName=$this->input->post('routeName');
		// echo "hey".$routeName;exit;

		$newPastArray=array();
		$routeBills = $this->session->userdata('routeBills');
		if(!empty($routeBills)){
			// print_r($routeBills[0]);
			foreach ($routeBills AS $key => $value) {
				if ($routeName == $value['routeName']){
					unset($routeBills[$key]);
				}
			}

			foreach($routeBills as $newItem){
				$newPastArray[]=$newItem;
			}
			$this->session->set_userdata('routeBills', $newPastArray);

			// $newRouteBills = $this->session->userdata('routeBills');
			$newLoads = $this->session->userdata('routeBills');
			// print_r($newLoads);exit;
			if(!empty($newLoads)){
				$no=0;
				foreach ($newLoads as $items) {
					$resendBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isResendBill');
					$lostBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isLostBill');
					$lostChequesBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isLostCheque');
					$pendingNeftBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isPendingNeft');
					$bouncedBill=$this->AllocationByManagerModel->checkBouncedBill('billpayments',$items['id']);

						$id=$items['route'];
						// echo $items['id'];
						$bill_id=$items['id'];
						// $pastBillIDs[$no]=$bill_id;
						$no++;
						 // print_r($pastBillIDs);
						?>
						<tr>
							<td><?php echo $no.' ';
								if($items['creditNoteRenewal']>0){ echo '<span class="logo_prov">CN</span>'; }
								if($resendBills>0){ echo '<span class="logo_prov">RS</span>'; }
								if($lostBills>0){ echo '<span class="logo_prov">LB</span>'; }
								if($lostChequesBills>0){ echo '<span class="logo_prov">LC</span>'; }
								if($pendingNeftBills>0){ echo '<span class="logo_prov">PN</span>'; }
								
								if($items['chequePenalty']>0){ ?>
				         				<span class="logo_prov"><?php echo 'B'.intval($items['chequePenalty']); ?></span>
				    			<?php  }
							 ?>
							</td>
							<td><?php echo $items['billNo']; ?></td>
							<td><?php echo $items['date']; ?></td>
							<td><?php echo $items['retailerName']; ?></td>
							<td class="text-right"><?php echo number_format($items['netAmount']); ?></td>
							<td class="text-right"><?php echo number_format($items['SRAmt']); ?></td>
							<td class="text-right"><?php echo number_format($items['receivedAmt']); ?></td>
							<!-- <td></td> -->
							<td class="text-right"><?php echo number_format($items['cd']);?></td>
				            <td class="text-right" id="looop"><?php echo number_format($items['pendingAmt']); ?></td>
				          <td class="text-right">0</td>
				            <td>
				            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-xs btn-primary waves-effect" data-type="basic">
				            		<i class="material-icons">cancel</i></button>
				            </td>
		            </tr>
		            <?php

		        }
			}
		}
	}
	


	public function removeR(){
	// $rtId=$this->session->userdata('routeBills');
			$billId=$this->session->userdata('currentBillIDs');
			$pastbillId=$this->session->userdata('routeBills');
			$bouncedBills=$this->session->userdata('bouncedBills');
			$deliveryBills=$this->session->userdata('deliveryBills');
	}

	public function calculateAllocationAmount(){
		// print($this->session->userdata['currentBillIDs']);
		$total=0;
		$count=0;

		if (isset($this->session->userdata['currentBillIDs'])){
			$currentBills=$this->session->userdata('currentBillIDs');
			if(!empty($currentBills)){
				foreach($currentBills as $item){
					$total=$total+$item['pendingAmt'];
				}
				$count=$count+count($currentBills);
			}
		}

		if (isset($this->session->userdata['routeBills'])){
			$pastBills=$this->session->userdata('routeBills');
			if(!empty($pastBills)){
				foreach($pastBills as $item){
					$total=$total+$item['pendingAmt'];
				}
				$count=$count+count($pastBills);
			}
		}

		if (isset($this->session->userdata['deliveryBills'])){
			$deliveryBills=$this->session->userdata('deliveryBills');
			if(!empty($deliveryBills)){
				foreach($deliveryBills as $item){
					$total=$total+$item['pendingAmt'];
				}
				$count=$count+count($deliveryBills);
			}
		}
		
	?>
		<tr>
			<td><?php echo $count; ?></td>
			<td><?php echo $total; ?></td>
		</tr>

	<?php
		
	}

	public function approvedMobileAllocation($id){
		$checkData=$this->AllocationByManagerModel->load('allocations',$id);

		$userid = ($this->session->userdata[$this->projectSessionName]['id']);
		if(!empty($checkData)){
			$updateData=array(
				'isApproved'=>1,
				'approvedBy'=>$userid
			);
			$this->AllocationByManagerModel->update('allocations',$updateData,$id);
		}
		redirect('AllocationByManagerController/openAllocations');
	}

	public function nonAllocatedBillsDetails(){
		$highlightValue=3;
        $days=$this->AllocationByManagerModel->load('highlighting_days',1);
        // print_r();
        if(!empty($days)){
            $highlightValue=$days[0]['days'];
        }
        $data['highlightValue']=$highlightValue;

		$data['currentAllocations']=$this->AllocationByManagerModel->getCurrentOpenAllocations('allocations');
        $data['emp']=$this->AllocationByManagerModel->getdata('employee');
		$data['company']=$this->AllocationByManagerModel->getdata('company');
		$cmp=$this->input->post('cmp');
		$data['cmpName']=$cmp;
		if($cmp=="" || $cmp=="General"){
			$data['bills']=$this->AllocationByManagerModel->getNonAllocatedBills('bills');
			$this->load->view('Manager/NonAllocatedBillsView',$data);
		}else{
			$data['bills']=$this->AllocationByManagerModel->getNonAllocatedBillsWithCompany('bills',$cmp);
			$this->load->view('Manager/NonAllocatedBillsView',$data);
		}
	}

	public function closedAllocationTrancationDetails($allocationId){
		$data['allocationId']=$allocationId;
		$data['allocations']=$this->AllocationByManagerModel->load('allocations',$allocationId);
		$data['signed']=$this->AllocationByManagerModel->getAllocationSignedBills('bills',$allocationId);
		$data['payment']=$this->AllocationByManagerModel->getAllocationBillPaymentsDetails('bills',$allocationId);


		//dynamic names
		$d1=$this->AllocationByManagerModel->load('categories_income_expenses',12);
		$d2=$this->AllocationByManagerModel->load('categories_income_expenses',13);
		$d3=$this->AllocationByManagerModel->load('categories_income_expenses',14);
		$firstTitle="Parking";
		$secondTitle="CNG";
		$thirdTitle="Challan";
		if(!empty($d1)){
			$firstTitle=$d1[0]['categoryName'];
		}

		if(!empty($d2)){
			$secondTitle=$d2[0]['categoryName'];
		}

		if(!empty($d3)){
			$thirdTitle=$d3[0]['categoryName'];
		}

		$data['firstTitle']=$firstTitle;
		$data['secondTitle']=$secondTitle;
		$data['thirdTitle']=$thirdTitle;

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
            
        }
        $data['expenses']=$expenses;
		////////

		$this->load->view('closedAllocationTransactionView',$data);
	}

	public function smsSrTrancationDetails($allocationId){
		$data['allocationId']=$allocationId;
		$data['allocations']=$this->AllocationByManagerModel->load('allocations',$allocationId);
		$data['sr']=$this->AllocationByManagerModel->getAllocationSrBills('bills',$allocationId);
		$this->load->view('smsSrView',$data);
	}

	public function closedAllocationSrTrancationDetails($allocationId){
		$data['allocationId']=$allocationId;
		$data['allocations']=$this->AllocationByManagerModel->load('allocations',$allocationId);
		$data['sr']=$this->AllocationByManagerModel->getAllocationSrBills('bills',$allocationId);
		$data['fsr']=$this->AllocationByManagerModel->getAllocationFsrBills('bills',$allocationId);
		$this->load->view('closedAllocationSrTransactionView',$data);
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

		$data['employeeNames']=$this->AllocationByManagerModel->getEmployeeNames();
		$data['routeNames']=$this->AllocationByManagerModel->getRouteNames();

		$openAllocationsDays=$this->AllocationByManagerModel->load('highlighting_days',10);
		$allocationDays=0;
		if(!empty($openAllocationsDays)){
			$allocationDays=$openAllocationsDays[0]['days'];
		}

		$data['allocationDays']=$allocationDays;
		
		$data['allocations']=$this->AllocationByManagerModel->getAllocations('allocations');
		// print_r($data['allocations']);exit;

		// if(!empty($allocations)){
		// 	$no=0;
  //           foreach ($allocations as $item){
  //           	$totalBillsInAllocation= $this->AllocationByManagerModel->getBillCount('allocationsbills',$item['id']);
  //               $totalAmountInAllocation= $this->AllocationByManagerModel->getAllocationAmount('allocationsbills',$item['id']);
  //               $totalAllocationAmt=0;
  //               if(!empty($totalAmountInAllocation)){
  //                  $totalAllocationAmt=$totalAmountInAllocation[0]['sumNetAmount'];
  //               }

  //               $salesmanAllocation= $this->AllocationByManagerModel->getAllocationSalesman('allocationsbills',$item['id']);
  //               // print_r($salesmanAllocation);exit;
  //               $salesmanName='';
  //               if(!empty($salesmanAllocation)){
  //                  $sname=array_unique($salesmanAllocation,SORT_REGULAR);
  //                  foreach($sname as $name){
  //                      $salesmanName=$name['salesman'].', '.$salesmanName;
  //                  }
  //               }

  //               
		// 		$diff = ceil(abs($end - $start) / 86400);

  //               $routeName='';
  //               $rtName=explode(",",rtrim($item['routeCode'],','));
  //               for($i=0;$i<count($rtName);$i++){
  //                   $routes=$this->AllocationByManagerModel->getRouteName($rtName[$i]);
  //                   if(!empty($routes)){
  //                       $routeName=$routeName.' '.$routes[0]['name'].', ';
  //                   }
  //               }

  //               $employee="";
  //               if(($item['fieldStaffCode1'] !='0')){
  //                   $emp=$this->AllocationByManagerModel->getEmployeeNamesByID($item['fieldStaffCode1']);
  //                   $employee= $employee.$emp.', ';
  //               }
  //               if(($item['fieldStaffCode2'] !='0')){
  //                   $emp=$this->AllocationByManagerModel->getEmployeeNamesByID($item['fieldStaffCode2']);
  //                   $employee=$employee.$emp.', ';
  //               }

  //               $item['totalAllocationAmt']=$totalAllocationAmt;
  //               $item['totalBillsInAllocation']=$totalBillsInAllocation;
  //               $item['salesmanName']=$salesmanName;
  //               $item['routeName']=$routeName;
  //               $item['employee']=$employee;
  //               $item['diff']=$diff;
  //               $data['allocations'][$no]=$item;
  //               $no++;
  //           } 
		// }

		$data['officeAllocations']=$this->AllocationByManagerModel->getAllocations('allocations_officeadjustment');
		$this->load->view('openAllocationForAllView',$data);
	}

	public function closedAllocations()
    {

		$openAllocationsDays=$this->AllocationByManagerModel->load('highlighting_days',5);
		$fieldstaffAllocationDays=0;
		if(!empty($openAllocationsDays)){
			$fieldstaffAllocationDays=$openAllocationsDays[0]['days'];
		}

		$data['fieldstaffallocationDays']=$fieldstaffAllocationDays;

		$openAllocationsDays=$this->AllocationByManagerModel->load('highlighting_days',11);
		$godownkeeperAllocationDays=0;
		if(!empty($openAllocationsDays)){
			$godownkeeperAllocationDays=$openAllocationsDays[0]['days'];
		}

		$data['godownkeeperAllocationDays']=$godownkeeperAllocationDays;
		

        $this->load->library('pagination');

        $config['base_url'] = base_url('index.php/AllocationByManagerController/closedAllocations');
        $allocations=$this->AllocationByManagerModel->getClosedAllocations('allocations');

        $config['per_page'] = ($this->input->get('limitRows')) ? $this->input->get('limitRows') : 100;
        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;

         // integrate bootstrap pagination
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
       
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = 'Prev';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="'.$config['base_url'].'?per_page=0">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $data['page'] = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;
        $data['searchFor'] = ($this->input->get('query')) ? $this->input->get('query') : NULL;
        $data['orderField'] = ($this->input->get('orderField')) ? $this->input->get('orderField') : '';
        $data['orderDirection'] = ($this->input->get('orderDirection')) ? $this->input->get('orderDirection') : '';
        $outstanding="";
        $rowConunts="";
        $closedAllocations = $this->AllocationByManagerModel->paginationOutstandingBills('allocations',$config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);
        $data['allocations'] = $closedAllocations;
        // print_r($data['allocations']);exit;
        $config['total_rows'] = count($allocations);
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('paginationClosedAllocationView',$data);
    }

	public function closedJournals()
    {
        $this->load->library('pagination');

        $config['base_url'] = base_url('index.php/AllocationByManagerController/closedJournals');

        $config['per_page'] = ($this->input->get('limitRows')) ? $this->input->get('limitRows') : 100;
        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;

         // integrate bootstrap pagination
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
       
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = 'Prev';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="'.$config['base_url'].'?per_page=0">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $data['page'] = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;
        $data['searchFor'] = ($this->input->get('query')) ? $this->input->get('query') : NULL;
        $data['orderField'] = ($this->input->get('orderField')) ? $this->input->get('orderField') : '';
        $data['orderDirection'] = ($this->input->get('orderDirection')) ? $this->input->get('orderDirection') : '';
        $journals="";
        $rowConunts="";
		$journals = $this->AllocationByManagerModel->paginationJournalBills('journal_entry',$config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);
        $rowCounts=$this->AllocationByManagerModel->countPaginationJournalBills('journal_entry',$config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);
        
        $data['journals'] = $journals;
        $config['total_rows'] = $rowCounts;

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('paginationJournalView',$data);
    }

	public function journalDetails($code){
		// echo $code;exit;
		$data['code']=$code;
		$data['journalEntry']=$this->AllocationByManagerModel->getJournalDetails('journal_entry',$code);
		$data['journalEntryEmp']=$this->AllocationByManagerModel->getJournalDetailsForEmp('journal_entry',$code);
		// echo $this->db->last_query();
		// print_r($data['journalEntry']);exit;
		$this->load->view('journalDetailsView',$data);
	}

	// public function closedAllocations(){
	// 	$this->session->unset_userdata('bouncedBills');
	// 	$this->session->unset_userdata('deliveryBills');
	// 	$this->session->unset_userdata('currentBillIDs');
	// 	$this->session->unset_userdata('routeBills');
	// 	$this->session->unset_userdata('Emp');
	// 	$data['allocations']=$this->AllocationByManagerModel->getClosedAllocations('allocations');
		

	// 	$this->load->view('ClosedAllocationsView',$data);
	// }

	public function CompCurrentBills(){
		$compName=$this->input->post("cmpName");
		$data['billNos']=$this->AllocationByManagerModel->getOpenAllocationBillsByCompany($compName);
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
		$data['deliverySlip']=$this->AllocationByManagerModel->getDeliverySlipBillsByCompany($compName);
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
		$updateBillInfo=array();
		if(!empty($details)){
			$allocationdetails=$this->AllocationByManagerModel->load('allocations',$id);
			$allocationCode=$allocationdetails[0]['allocationCode'];
			foreach($details as $data){
				$bills=$this->AllocationByManagerModel->load('bills',$data['billId']);

				$cancelPaidBill=0.0;
				$newPendingAmt=0.0;
				if(!empty($bills)){
					$cancelPaidBill=$cancelPaidBill+$bills[0]['fsCashAmt']+$bills[0]['fsChequeAmt']+$bills[0]['fsNeftAmt'];
					$newPendingAmt=$bills[0]['pendingAmt']+$cancelPaidBill;
				}

				if(!empty($bills)){
					$this->AllocationByManagerModel->deleteAllocationsBills('allocationsbills',$data['billId'],$id);
					if($this->db->affected_rows()>0){
						$this->AllocationByManagerModel->deleteAllocationsBills('billpayments',$data['billId'],$id);
						if(($bills[0]['billType']=='allocatedbillCurrent') && ($bills[0]['netAmount'] == $bills[0]['pendingAmt'])){
							$billStatus=$bills[0]['billHistoryStatus'];	
							$rtData=array(
								'billCurrentStatus'=>'Allocation Cancelled : '.$allocationCode,'billType'=>$billStatus,'isLostBill'=>'0','pendingAmt'=>$newPendingAmt,'fsbillStatus'=>'','fsChequeAmt'=>'0','fsNeftAmt'=>'0','fsCashAmt'=>'0','fsSrAmt'=>'0','isResendBill'=>'0','isAllocated'=>'0'	
							);

							$this->AllocationByManagerModel->update('bills',$rtData,$data['billId']);

							$history=array(
								'billId'=>$data['billId'],
								'allocationId' => $id,
								'transactionStatus' =>'Allocation Cancelled : '.$allocationCode,
								'transactionDate'=>date('Y-m-d H:i:sa'),
								'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
							);
							$this->AllocationByManagerModel->insert('bill_transaction_history',$history);
						
						}else{
							$rtData=array(
								'isLostBill'=>'0',
								'fsbillStatus'=>'',
								'pendingAmt'=>$newPendingAmt,
								'fsChequeAmt'=>'0',
								'fsNeftAmt'=>'0',
								'fsCashAmt'=>'0',
								'fsSrAmt'=>'0',
								'isResendBill'=>'0',
								'isAllocated'=>'0'	
							);
							$this->AllocationByManagerModel->update('bills',$rtData,$data['billId']);
							$history=array(
								'billId'=>$data['billId'],
								'allocationId' => $id,
								'transactionStatus' =>'Allocation Cancelled : '.$allocationCode,
								'transactionDate'=>date('Y-m-d H:i:sa'),
								'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
							);
							$this->AllocationByManagerModel->insert('bill_transaction_history',$history);
						}
					}
				}
			}
			$this->AllocationByManagerModel->delete('allocations',$id);
		}
		redirect('AllocationByManagerController/openAllocations');
	}

	// public function cancelTotalAllocation($id){
	// 	$data['allocationId']=$id;
	// 	$details=$this->AllocationByManagerModel->getAllocationDetails('allocations',$id);
	// 	// print_r($details);exit;
	// 	$updateBillInfo=array();
	// 	if(!empty($details)){
	// 		foreach($details as $data){




	// 			$bills=$this->AllocationByManagerModel->load('bills',$data['billId']);
	// 			if($bills[0]['billType']==='allocatedbillPass'){
	// 				$updateBillInfo=array('billType'=>'allocatedbillPass','route'=>'0','isAllocated'=>0);
	// 			}else if($bills[0]['billType']==='allocatedbillCurrent'){
	// 				$updateBillInfo=array('billType'=>'','route'=>'0','isAllocated'=>0);
	// 			}
	// 			// print_r($updateBillInfo);exit;
	// 			// $updateBillInfo=array('billType'=>'','route'=>'0');
	// 			//change bill status
	// 			$this->AllocationByManagerModel->update('bills',$updateBillInfo,$data['billId']);
	// 			//delete allocation details
	// 			$this->AllocationByManagerModel->delete('allocationsbills',$data['id']);
	// 			//delete allocation
	// 			$this->AllocationByManagerModel->delete('allocations',$data['allocationId']);
	// 		}
	// 	}
	// 	redirect('AllocationByManagerController/openAllocations');
	// }

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
			$data['billNos']=$this->AllocationByManagerModel->getOpenAllocationBillsByCompany($compName);
			$data['pastBillNos']=$this->AllocationByManagerModel->getPastBillsByComp($compName);
			$data['bounceReturnCheques']=$this->AllocationByManagerModel->bouncedReturnCheques('billpayments',$compName);
			$data['deliverySlip']=$this->AllocationByManagerModel->getDeliverySlipBillsByCompany($compName);
		}else{
			$compName='Nestle';
			$data['cmpName']='Nestle';
			$data['billNos']=$this->AllocationByManagerModel->getOpenAllocationBillsByCompany($compName);
			$data['pastBillNos']=$this->AllocationByManagerModel->getPastBillsByComp($compName);
			$data['bounceReturnCheques']=$this->AllocationByManagerModel->bouncedReturnCheques('billpayments',$compName);
			$data['deliverySlip']=$this->AllocationByManagerModel->getDeliverySlipBillsByCompany($compName);
		}

		$this->load->view('AddAllocationByManagerView',$data);
	}

	public function CompleteAllocation($id){
		$data['change_details']="yes";
		// echo $data['details'];exit;
		$session_data = array('sessionAllocationId' => $id);
			
		$this->session->set_userdata('EditCurrentId', $session_data);

		$this->session->unset_userdata('bouncedBills');
		$this->session->unset_userdata('deliveryBills');
		$this->session->unset_userdata('currentBillIDs');
		$this->session->unset_userdata('routeBills');
		$this->session->unset_userdata('Emp');

		$billTransactions=$this->AllocationByManagerModel->checkAllocationTransaction('allocations',$id);
		$data['transactionCount']=0;
		if(!empty($billTransactions)){
			$data['transactionCount']=count($billTransactions);
		}

		$data['allocations']=$this->AllocationByManagerModel->load('allocations',$id);
		$compName=$data['allocations'][0]['company'];
		$data['alID']=$id;
		$data['employee']=$this->AllocationByManagerModel->getdataEmployee('employee');
		$data['company']=$this->AllocationByManagerModel->getNames('company');
		$data['routeNames']=$this->AllocationByManagerModel->getRouteNames();
		$data['employeeNames']=$this->AllocationByManagerModel->getEmployeeNames();
		
		$data['billNos']=$this->AllocationByManagerModel->getOpenAllocationBillsByCompany($compName);
		$data['pastBillNos']=$this->AllocationByManagerModel->getPastBillsByComp($compName);
		$data['deliverySlip']=$this->AllocationByManagerModel->getDeliverySlipBillsByCompany($compName);

		$data["current"]=array();
		$data["ds"]=array();
		$data["pass"]=array();
		$data["slip"]=array();
		$data['bills']=$this->AllocationByManagerModel->getAllocatedBills('bills',$id);

		
		foreach ($data['bills'] as $items) {
			if($items['billType']==='allocatedbillCurrent'){
				$data['current']=$this->AllocationByManagerModel->getAllocatedBillsByType('bills',$id,'1');
			}else if(($items['billType']==='allocatedbillPass') || ($items['billType']==='adHocDeliveryBill') || ($items['billType']==='officeAdjustmentBill')){
				$data['pass']=$this->AllocationByManagerModel->getAllocatedPastBillsByType('bills',$id);
			}else if($items['billType']==='allocatedbillDS'){
				$data['slip']=$this->AllocationByManagerModel->getAllocatedBillsByType('bills',$id,'1');
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
		
		$data['billNos']=$this->AllocationByManagerModel->getOpenAllocationBillsByCompany($compName);
		$data['pastBillNos']=$this->AllocationByManagerModel->getPastBillsByComp($compName);
		$data['bounceReturnCheques']=$this->AllocationByManagerModel->bouncedReturnCheques('billpayments',$compName);
		$data['deliverySlip']=$this->AllocationByManagerModel->getDeliverySlipBillsByCompany($compName);

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
			$data['billNos']=$this->AllocationByManagerModel->getOpenAllocationBillsByCompany($compName);
			$data['pastBillNos']=$this->AllocationByManagerModel->getPastBillsByCompAllocation($compName);
			// $data['bounceReturnCheques']=$this->AllocationByManagerModel->bouncedReturnCheques('billpayments',$compName);
			$data['deliverySlip']=$this->AllocationByManagerModel->getDeliverySlipBillsByCompany($compName);
		}else{
			$compName='Nestle';
			$data['cmpName']='Nestle';
			$data['billNos']=$this->AllocationByManagerModel->getOpenAllocationBillsByCompany($compName);
			$data['pastBillNos']=$this->AllocationByManagerModel->getPastBillsByCompAllocation($compName);
			// $data['bounceReturnCheques']=$this->AllocationByManagerModel->bouncedReturnCheques('billpayments',$compName);
			$data['deliverySlip']=$this->AllocationByManagerModel->getDeliverySlipBillsByCompany($compName);
		}

		$data["current"]=array();
		$data["ds"]=array();
		$data["pass"]=array();
		$data["slip"]=array();
		// $data['bounced']=array();
		$data['bills']=$this->AllocationByManagerModel->getAllocatedBills('bills',$id);
		$data['allocations']=$this->AllocationByManagerModel->load('allocations',$id);
		
		foreach ($data['bills'] as $items) {
			
			$data['current']=$this->AllocationByManagerModel->getAllocatedBillsByType('bills',$id,'1');
		

			if(($items['billType']==='allocatedbillPass') || ($items['billType']==='adHocDeliveryBill') || ($items['billType']==='officeAdjustmentBill')){
				$data['pass']=$this->AllocationByManagerModel->getAllocatedPastBillsByType('bills',$id,$items['billType']);
			}else if($items['billType']=='allocatedbillDS'){
				$data['slip']=$this->AllocationByManagerModel->getAllocatedBillsByType('bills',$id,'1');
			}
			// else if($items['billType']=='allocatedbillBounce'){
			// 	$ar=$this->AllocationByManagerModel->loadPayBills('allocationsbills',$id);
			// 	for($i=0;$i<count($ar);$i++){
			// 		$a[]=$this->AllocationByManagerModel->getChequeBillsByIDs('billpayments',$ar[$i]['billId']);
			// 	}
			// 	$data['bounced']=$a;
			// }
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

		// if(!empty($data['bounced'])){
		// 	//For Bounced Cheques
		// 	$response3=array();
		// 	foreach($data['bounced'] as $row){
		// 		$response3[] = $row;
		// 	}

		// 	$routeBills="";
		// 	foreach ($response3 as $items) {
		// 		$routeBills=$items;
		// 		$oldSession =  $this->session->userdata('bouncedBills');
		// 		if(empty($oldSession)){
		// 			$routes[]=$routeBills;
		// 			$this->session->set_userdata('bouncedBills', $routes);
		// 		}else{
		// 			if(!in_array($items,$oldSession)){
		// 				array_push($oldSession, $routeBills);
		// 				$this->session->set_userdata('bouncedBills', $oldSession);
		// 			}
		// 		}
		// 	}
		// }
		
		$this->load->view('CloseAllocationDetailsView',$data);
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

		// echo $fromNo.' '.$toNo;exit;

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
			$getFromNo=$this->AllocationByManagerModel->loadBillByNumber('bills', $fromNo);
			$compName="";
			if(!empty($getFromNo)){
				$fromNo=$getFromNo[0]['id'];
				$compName=$getFromNo[0]['compName'];
			}
			$getToNo=$this->AllocationByManagerModel->loadBillByNumber('bills', $toNo);
			if(!empty($getToNo)){
				$toNo=$getToNo[0]['id'];
			}

		
			$data['currentBills']=$this->AllocationByManagerModel->loadCurrentBillsWithCompany('bills', $fromNo, $toNo,$compName);	
			// print_r($data['currentBills']);exit;
			// echo $this->db->last_query();exit;
			foreach($data['currentBills'] as $row){
				if($row['deliveryStatus'] !=="cancelled"){
					$response[] = $row;
				}
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
                 <span class="logo_prov"><?php echo 'B'.intval($items['chequePenalty']); ?></span>
            <?php  }

		    			?></td>
						<td><?php echo $items['billNo']; ?></td>
						<td><?php echo $items['date']; ?></td>
						<td><?php echo $items['retailerName']; ?></td>
						<td class="text-right"><?php echo number_format($items['netAmount']); ?></td>
						<td class="text-right"><?php echo number_format($items['SRAmt']); ?></td> 
						<td class="text-right"><?php echo number_format($items['receivedAmt']); ?></td>
			            <td class="text-right"><?php echo number_format($items['cd']); ?></td>
			            <td class="text-right" id="loop"><?php echo number_format($items['pendingAmt']); ?></td>
			            <td></td>
			            <td> 
			            	<a>
			            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-xs btn-primary waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
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
                 <span class="logo_prov"><?php echo 'B'.intval($items['chequePenalty']); ?></span>
            <?php  }

	    			?></td>
					<td><?php echo $items['billNo']; ?></td>
					<td><?php echo $items['date']; ?></td>
					<td><?php echo $items['retailerName']; ?></td>
					<td class="text-right"><?php echo number_format($items['netAmount']); ?></td>
					<td class="text-right"><?php echo number_format($items['SRAmt']); ?></td> 
					<td class="text-right"><?php echo number_format($items['receivedAmt']); ?></td>
		            <td class="text-right"><?php echo number_format($items['cd']); ?></td>
		            <td class="text-right" id="loop"><?php echo number_format($items['pendingAmt']); ?></td>
		            <td></td>
		            <td> <a>
		            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-xs btn-primary waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
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
                 <span class="logo_prov"><?php echo 'B'.intval($items['chequePenalty']); ?></span>
            <?php  }
		    			?></td>
						<td><?php echo $items['billNo']; ?></td>
						<td><?php echo $items['date']; ?></td>

						<td><?php echo $items['retailerName']; ?></td>
						<td class="text-right"><?php echo number_format($items['netAmount']); ?></td>
						<td class="text-right"><?php echo number_format($items['SRAmt']); ?></td> 
						<td class="text-right"><?php echo number_format($items['receivedAmt']); ?></td>
			            <td class="text-right"><?php echo number_format($items['cd']); ?></td>
			            <td class="text-right" id="loop"><?php echo number_format($items['pendingAmt']); ?></td>
			            <td></td>
			            <!-- <td><?php echo $items['creditAdjustment']; ?></td> -->
			            <td> <a>
			            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-xs btn-primary waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
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
                 <span class="logo_prov"><?php echo 'B'.intval($items['chequePenalty']); ?></span>
            <?php  }
	    			?></td>
					<td><?php echo $items['billNo']; ?></td>
					<td><?php echo $items['date']; ?></td>
					<td><?php echo $items['retailerName']; ?></td>
					<td class="text-right"><?php echo number_format($items['netAmount']); ?></td>
					<td class="text-right"><?php echo number_format($items['SRAmt']); ?></td> 
					<td class="text-right"><?php echo number_format($items['receivedAmt']); ?></td>
		            <td class="text-right"><?php echo number_format($items['cd']); ?></td>
		            <td class="text-right" id="loop"><?php echo number_format($items['pendingAmt']); ?></td>
		            <td></td>
		            <!-- <td><?php echo $items['creditAdjustment']; ?></td> -->
		            <td> <a>
		            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-xs btn-primary waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
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
					if($items['chequePenalty']>0){ ?>
                 <span class="logo_prov"><?php echo 'B'.intval($items['chequePenalty']); ?></span>
            <?php  }
    			?></td>
				<td><?php echo $items['billNo']; ?></td>
				<td><?php echo $items['date']; ?></td>

				<td><?php echo $items['retailerName']; ?></td>
				<td class="text-right"><?php echo number_format($items['netAmount']); ?></td>
				<td class="text-right"><?php echo number_format($items['SRAmt']); ?></td>
				<td class="text-right"><?php echo number_format($items['receivedAmt']+$items['debit']+$items['officeAdjustmentBillAmount']+$items['otherAdjustment']);?></td>
		            <!-- <td></td> -->
		            <td class="text-right"><?php echo number_format($items['cd']); ?></td>
		            <td class="text-right" id="looop"><?php echo number_format($items['pendingAmt']); ?></td>
		           <td></td>
		            <td>
		            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-xs btn-primary waves-effect" data-type="basic">
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
							if($items['chequePenalty']>0){ ?>
                 <span class="logo_prov"><?php echo 'B'.intval($items['chequePenalty']); ?></span>
            <?php  }
		    			?></td>
		    			<td><?php echo $items['billNo']; ?></td>
		    			<td><?php echo $items['date']; ?></td>

		    			<td><?php echo $items['retailerName']; ?></td>
		    			<td class="text-right"><?php echo number_format($items['netAmount']); ?></td>
		    			<td class="text-right"><?php echo number_format($items['SRAmt']); ?></td>
		    			<td class="text-right"> <?php echo number_format($items['receivedAmt']+$items['debit']+$items['officeAdjustmentBillAmount']+$items['otherAdjustment']);?></td>
		            <!-- <td></td> -->
		            <td class="text-right"><?php echo number_format($items['cd']); ?></td>
		            <td class="text-right" id="looop"><?php echo number_format($items['pendingAmt']); ?></td>
		            <td><?php echo $items['creditAdjustment']; ?></td>
		            <td></td>
		            <td>
		            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-xs btn-primary waves-effect" data-type="basic">
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
				            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-xs btn-primary waves-effect" data-type="basic">
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
				            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-xs btn-primary waves-effect" data-type="basic">
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
							<td class="text-right"><?php echo number_format($items['netAmount']); ?></td>
							<td class="text-right"><?php echo number_format($items['SRAmt']); ?></td>
							<td class="text-right"><?php echo number_format($items['receivedAmt']); ?></td>
			           		<td class="text-right"><?php echo number_format($items['cd']); ?></td>
			           		<td class="text-right" id="loop"><?php echo number_format($items['pendingAmt']); ?></td>
			          		<td></td>
				            <td>
				            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-xs btn-primary waves-effect" data-type="basic">
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
		            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-xs btn-primary waves-effect" data-type="basic">
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
			            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-xs btn-primary waves-effect" data-type="basic">
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
				

				$date=date('Y-m-d');
				if(!empty($routeName)){
					// echo "he";
					foreach($routeName as $rname){
						$routeData=$this->AllocationByManagerModel->getRouteCodeByName('route',$rname);
						$routeID=$routeData[0]['id'];
						$routeCode=$routeData[0]['code'];

						$data['pastBills']=array();
						if($routeCode ==""){
							$data['pastBills']=$this->AllocationByManagerModel->loadPastBillsByRoute('bills',$rname);
						}else{
							$data['pastBills']=$this->AllocationByManagerModel->loadPastBillsByRouteCode('bills',$routeCode);
						}
					
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
					// print_r($newLoads);exit;
					if(!empty($newLoads)){
						foreach ($newLoads as $items) {
							$resendBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isResendBill');
							$lostBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isLostBill');
							$lostChequesBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isLostCheque');
							$pendingNeftBills=$this->AllocationByManagerModel->getRowCount('allocationsbills',$items['id'],'isPendingNeft');
							$bouncedBill=$this->AllocationByManagerModel->checkBouncedBill('billpayments',$items['id']);

						$id=$items['route'];
						// echo $items['id'];
						$bill_id=$items['id'];
						// $pastBillIDs[$no]=$bill_id;
						$no++;
						 // print_r($pastBillIDs);
						?>
						<tr>
							<td><?php echo $no.' ';
								if($items['creditNoteRenewal']>0){ echo '<span class="logo_prov">CN</span>'; }
								if($resendBills>0){ echo '<span class="logo_prov">RS</span>'; }
								if($lostBills>0){ echo '<span class="logo_prov">LB</span>'; }
								if($lostChequesBills>0){ echo '<span class="logo_prov">LC</span>'; }
								if($pendingNeftBills>0){ echo '<span class="logo_prov">PN</span>'; }
								
								if($items['chequePenalty']>0){ ?>
                 <span class="logo_prov"><?php echo 'B'.intval($items['chequePenalty']); ?></span>
            <?php  }
							 ?></td>
							<td><?php echo $items['billNo']; ?></td>
							<td><?php echo $items['date']; ?></td>
							<td><?php echo $items['retailerName']; ?></td>
							<td class="text-right"><?php echo number_format($items['netAmount']); ?></td>
							<td class="text-right"><?php echo number_format($items['SRAmt']); ?></td>
							<td class="text-right"><?php echo number_format($items['receivedAmt']); ?></td>
							<!-- <td></td> -->
							<td class="text-right"><?php echo number_format($items['cd']);?></td>
        		            <td class="text-right" id="looop"><?php echo number_format($items['pendingAmt']); ?></td>
        		          <td></td>
        		            <td>
        		            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-xs btn-primary waves-effect" data-type="basic">
        		            		<i class="material-icons">cancel</i></button>
        		            </td>
		            </tr>
		            <?php

		        }
		        // print_r($pastBillIDs);
		       
					}
					
		    }
		   
		    // $this->session->set_userdata("routeBills",$pastBillIDs);
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
		            	<button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-xs btn-primary waves-effect" data-type="basic">
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

		public function removeRouteBillIdFromSession(){
			$name=$this->input->post('name');

			$newCurrentArray=array();
			$currentBills = $this->session->userdata('currentBillIDs');
			foreach ($currentBills AS $key => $value) {
				if ($id == $value['routeName']){
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
				if ($id == $value['routeName']){
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

			$cancelPaidBill=0.0;
			$newPendingAmt=0.0;
			if(!empty($billData)){
				$cancelPaidBill=$cancelPaidBill+$billData[0]['fsCashAmt']+$billData[0]['fsChequeAmt']+$billData[0]['fsNeftAmt'];
				$newPendingAmt=$billData[0]['pendingAmt']+$cancelPaidBill;
			}

			if(!empty($checkData)){
				$this->AllocationByManagerModel->deleteAllocationsBills('allocationsbills',$id,$allocationId);
				if($this->db->affected_rows()>0){
					$this->AllocationByManagerModel->deleteAllocationsBills('billpayments',$id,$allocationId);
					if($billData[0]['billType']=='allocatedbillCurrent'){
						$rtData=array
						(
							'isLostBill'=>'0','pendingAmt'=>$newPendingAmt,'fsbillStatus'=>'','fsChequeAmt'=>'0','fsNeftAmt'=>'0','fsCashAmt'=>'0','fsSrAmt'=>'0','billType'=>'','isResendBill'=>'0','isAllocated'=>'0'	
						);

						$this->AllocationByManagerModel->update('bills',$rtData,$id);

						$history=array(
							'billId'=>$id,
							'allocationId'=>$allocationId,
							'transactionStatus' =>'Removed from allocation',
							'transactionDate'=>date('Y-m-d H:i:sa'),
							'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
						);
						$this->AllocationByManagerModel->insert('bill_transaction_history',$history);
					}else{
						$rtData=array
						(
							'isLostBill'=>'0','fsbillStatus'=>'','pendingAmt'=>$newPendingAmt,'fsChequeAmt'=>'0','fsNeftAmt'=>'0','fsCashAmt'=>'0','fsSrAmt'=>'0','isResendBill'=>'0','isAllocated'=>'0'	
						);
						$this->AllocationByManagerModel->update('bills',$rtData,$id);

						$history=array(
							'billId'=>$id,
							'allocationId'=>$allocationId,
							'transactionStatus' =>'Removed from allocation',
							'transactionDate'=>date('Y-m-d H:i:sa'),
							'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
						);
						$this->AllocationByManagerModel->insert('bill_transaction_history',$history);
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
			$id=0;
			$totalAllocaation=0;
			$allocationCode="";

			$allocationCount=$this->AllocationByManagerModel->getNextId('allocations');
			if(!empty($allocationCount)){
				$totalAllocaation=count($allocationCount);
				$nextId =$allocationCount[0]['id']+1;
				$allocationCode= date("dmy").'-'.$nextId;
			}else{
				$nextId =$totalAllocaation+1;
				$allocationCode= date("dmy").'-'.$nextId;
			}

			$company=$this->input->post('company');
			$emp=$this->input->post('emp');
			$rtName=$this->input->post('rtName');

			$allocationTotalAmount=0;
			$allocationBillCount=0;
			$allocationEmployeeName="";
			$allocationSalesman="";
			$allocationRouteName="";

			$billId=$this->session->userdata('currentBillIDs');
			$pastbillId=$this->session->userdata('routeBills');
			$bouncedBills=$this->session->userdata('bouncedBills');
			$deliveryBills=$this->session->userdata('deliveryBills');

			if(!empty($billId)){
				foreach($billId as $items){
					$billInformation=$this->AllocationByManagerModel->getUnallocatedBillById('bills',$items['id']);
					if(!empty($billInformation)){
						$allocationSalesman=$allocationSalesman.','.$billInformation[0]['salesman'];
						$allocationTotalAmount=$allocationTotalAmount+$billInformation[0]['pendingAmt'];
						$allocationBillCount++;
					}		
				}
			}

			if(!empty($pastbillId)){
				foreach($pastbillId as $items){
					$billInformation=$this->AllocationByManagerModel->getUnallocatedBillById('bills',$items['id']);
					if(!empty($billInformation)){
						$allocationSalesman=$allocationSalesman.','.$billInformation[0]['salesman'];
						$allocationTotalAmount=$allocationTotalAmount+$billInformation[0]['pendingAmt'];
						$allocationBillCount++;
					}		
				}
			}

			if(!empty($deliveryBills)){
				foreach($deliveryBills as $items){
					$billInformation=$this->AllocationByManagerModel->getUnallocatedBillById('bills',$items['id']);
					if(!empty($billInformation)){
						$allocationSalesman=$allocationSalesman.','.$billInformation[0]['salesman'];
						$allocationTotalAmount=$allocationTotalAmount+$billInformation[0]['pendingAmt'];
						$allocationBillCount++;
					}		
				}
			}

			$emp=array_unique($emp);
			$this->session->set_userdata("Emp",$emp);
			$emp=implode(",", $this->session->userdata('Emp'));
			$emp=explode(",",$emp);
			$empCount=count($emp);

			$rtName=array_unique($rtName);
			$this->session->set_userdata("rtName",$rtName);
			$rtName=implode(",", $this->session->userdata('rtName'));
			$rtName=explode(",",$rtName);
			$rtCount=count($rtName);
	
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
					$allocationEmployeeName=$allocationEmployeeName.','.$emp[$i];
				}else if($i==1){
					$fieldStaffCode2=$empData['empData'][0]['id'];
					$allocationEmployeeName=$allocationEmployeeName.','.$emp[$i];
				}else if($i==2){
					$fieldStaffCode3=$empData['empData'][0]['id'];
					$allocationEmployeeName=$allocationEmployeeName.','.$emp[$i];
				}else if($i==3){
					$fieldStaffCode4=$empData['empData'][0]['id'];
					$allocationEmployeeName=$allocationEmployeeName.','.$emp[$i];
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
				$allocationRouteName=$allocationRouteName.','.$rtName[$i];
			}

			$routeId = rtrim($routeId,',');
			$routCode = rtrim($routCode,',');

			$salesman = explode(",", $allocationSalesman);
			$salesman=array_unique($salesman,SORT_REGULAR);
			$newSalesman="";
	        foreach($salesman as $name){
	            $newSalesman=$newSalesman.','.$name;
	        }
	        $allocationSalesman=$newSalesman;

	        $allocationEmployeeName=trim($allocationEmployeeName,',');
	        $allocationSalesman=trim($allocationSalesman,',');
	        $allocationRouteName=trim($allocationRouteName,',');

			$allocationData=array(
				'date' => date("Y-m-d H:i:sa"),
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
				'allocationCreatedBy'=>$this->session->userdata[$this->projectSessionName]['id'],
				'allocationCode' => $allocationCode,
				'allocationTotalAmount'=>$allocationTotalAmount,
				'allocationBillCount'=>$allocationBillCount,
				'allocationEmployeeName'=>$allocationEmployeeName,
				'allocationSalesman'=>$allocationSalesman,
				'allocationRouteName'=>$allocationRouteName
			);

			if(empty($checkAllocationCode)){
				if($this->AllocationByManagerModel->insertTableData('allocations',$allocationData)){ 
					$lastInsertedId=$this->db->insert_id(); 
					$allocationDetailInfo=$this->AllocationByManagerModel->load('allocations',$lastInsertedId);
					$latestAllocationCode="";
					if(!empty($allocationDetailInfo)){
					    $latestAllocationCode=$allocationDetailInfo[0]['allocationCode'];
					}

					if(!empty($billId)){
						foreach($billId as $items){
							$billInformation=$this->AllocationByManagerModel->getUnallocatedBillById('bills',$items['id']);
							if(!empty($billInformation)){
								$billAllocationData=array(
									'billId' => $items['id'],
									'allocationId' => $lastInsertedId,
									'billStatus'=>'1'
								);

								if($this->AllocationByManagerModel->insertTableData('allocationsbills',$billAllocationData)){
									$billstatus=$billInformation[0]['billType'];
									$rtData=array(
										'billCurrentStatus'=>'Allocated in '.$latestAllocationCode,
										'route' => $routeId,
										'billHistoryStatus'=>$billstatus,
										'billType' =>'allocatedbillCurrent',
										'isBilled'=>'0',
										'isLostBill'=>'0',
										'isAllocated'=>'1',
										'deliveryslipOwnerApproval'=>'1'	
									);
									$this->AllocationByManagerModel->update('bills',$rtData,$items['id']);
									if($this->db->affected_rows()>0){
										$history=array(
											'billId'=>$items['id'],
											'allocationId' => $lastInsertedId,
											'transactionStatus' =>'Allocated',
											'transactionDate'=>date('Y-m-d H:i:sa'),
											'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
										);
										$this->AllocationByManagerModel->insert('bill_transaction_history',$history);
									}
								} 
							}
						}
					}

					if(!empty($pastbillId)){
						foreach($pastbillId as $items){
							$billUpdateInfo=$this->AllocationByManagerModel->getUnallocatedBillById('bills',$items['id']);
							if(!empty($billUpdateInfo)){
								$billAllocationData=array(
									'billId' => $items['id'],
									'allocationId' => $lastInsertedId,
									'billStatus'=>'2'
								);
 
								if($this->AllocationByManagerModel->insertTableData('allocationsbills',$billAllocationData)){
									if(($billUpdateInfo[0]['billType'] === "officeAdjustmentBill") || ($billUpdateInfo[0]['billType'] === "adHocDeliveryBill")){
										$billstatus=$billUpdateInfo[0]['billType'];
										$rtData=array(
											'billCurrentStatus'=>'Allocated in '.$latestAllocationCode,
											'route' => $routeId,
											'billHistoryStatus'=>$billstatus,
											'isBilled'=>'0',
											'isLostBill'=>'0',
											'isAllocated'=>'1'		
										);
										$this->AllocationByManagerModel->update('bills',$rtData,$items['id']);
										if($this->db->affected_rows()>0){
											$history=array(
												'billId'=>$items['id'],
												'allocationId' => $lastInsertedId,
												'transactionStatus' =>'Allocated',
												'transactionDate'=>date('Y-m-d H:i:sa'),
												'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
											);
											$this->AllocationByManagerModel->insert('bill_transaction_history',$history);
										}
									}else{
										$billstatus=$billUpdateInfo[0]['billType'];
										$rtData=array(
											'billCurrentStatus'=>'Allocated in '.$latestAllocationCode,
											'route' => $routeId,
											'billHistoryStatus'=>$billstatus,
											'billType' =>'allocatedbillPass',
											'isBilled'=>'0',
											'isLostBill'=>'0',
											'isAllocated'=>'1'		
										);
										$this->AllocationByManagerModel->update('bills',$rtData,$items['id']);
										if($this->db->affected_rows()>0){
											$history=array(
												'billId'=>$items['id'],
												'allocationId' => $lastInsertedId,
												'transactionStatus' =>'Allocated',
												'transactionDate'=>date('Y-m-d H:i:sa'),
												'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
											);
											$this->AllocationByManagerModel->insert('bill_transaction_history',$history);
										}
									}
								}
							}
						}
					}

					if(!empty($deliveryBills)){
						foreach($deliveryBills as $items){
							$billInformation=$this->AllocationByManagerModel->getUnallocatedBillById('bills',$items['id']);
							if(!empty($billInformation)){
								$deliveryBillsData=array(
									'billId' => $items['id'],
									'allocationId' => $lastInsertedId,
									'billStatus'=>'3'
								);
								
								if($this->AllocationByManagerModel->insert('allocationsbills',$deliveryBillsData)){
									$billstatus=$billInformation[0]['billType'];
									$slipData=array(
										'billCurrentStatus'=>'Allocated in '.$latestAllocationCode,
										'route' => $routeId,
										'billHistoryStatus'=>$billstatus,
										'billType' =>'allocatedbillDS',
										'isBilled'=>'0',
										'isLostBill'=>'0',
										'isAllocated'=>'1'	
									);
									$this->AllocationByManagerModel->update('bills',$slipData,$items['id']);
									if($this->db->affected_rows()>0){
										$history=array(
											'billId'=>$items['id'],
											'allocationId' => $lastInsertedId,
											'transactionStatus' =>'Allocated',
											'transactionDate'=>date('Y-m-d H:i:sa'),
											'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
										);
										$this->AllocationByManagerModel->insert('bill_transaction_history',$history);
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
					
					echo "Allocation Number : ".$allocationCode.' saved successfully';
				} else {
					echo "Fail";
				}
			}
		}

		public function SaveConfirm(){
			$billId=$this->session->userdata('currentBillIDs');
			$pastbillId=$this->session->userdata('routeBills');
			$bouncedBills=$this->session->userdata('bouncedBills');
			$deliveryBills=$this->session->userdata('deliveryBills');

			$allocationTotalAmount=0;
			$allocationBillCount=0;
			$allocationEmployeeName="";
			$allocationSalesman="";
			$allocationRouteName="";

			if(!empty($billId)){
				foreach($billId as $items){
					$allocationSalesman=$allocationSalesman.','.$items['salesman'];
					$allocationTotalAmount=$allocationTotalAmount+$items['pendingAmt'];
					$allocationBillCount++;
				}
			}

			if(!empty($pastbillId)){
				foreach($pastbillId as $items){
					$allocationSalesman=$allocationSalesman.','.$items['salesman'];
					$allocationTotalAmount=$allocationTotalAmount+$items['pendingAmt'];
					$allocationBillCount++;
				}
			}

			if(!empty($deliveryBills)){
				foreach($deliveryBills as $items){
					$allocationSalesman=$allocationSalesman.','.$items['salesman'];
					$allocationTotalAmount=$allocationTotalAmount+$items['pendingAmt'];
					$allocationBillCount++;
				}
			}

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

			$id=$this->input->post('allocationCode');
			$data['alloca']=$this->AllocationByManagerModel->loadAllocated('allocations',$id);
		
			
			$id=explode("-",$id);
			$data['allocations']=$this->AllocationByManagerModel->loadAllocatedBills('allocationsbills',$data['alloca'][0]['id']);

			$salesman = explode(",", $allocationSalesman);
			$salesman=array_unique($salesman,SORT_REGULAR);
			$newSalesman="";
	        foreach($salesman as $name){
	            $newSalesman=$newSalesman.','.$name;
	        }
	        $allocationSalesman=$newSalesman;
	        $allocationSalesman=trim($allocationSalesman,',');

			if(!empty($data['allocations'])){
				$updateallocationData=array(
					'allocationTotalAmount'=>$allocationTotalAmount,
					'allocationBillCount'=>$allocationBillCount,
					'allocationSalesman'=>$allocationSalesman
				);

				$this->AllocationByManagerModel->update('allocations',$updateallocationData,$data['alloca'][0]['id']); 
				if(!empty($billId)){
					for ($i=0; $i < count($billId); $i++) { 
						$chekAlocatedId=$this->AllocationByManagerModel->getDuplicatedAllocatedRecord('allocationsbills',$billId[$i]['id'],$data['alloca'][0]['id']);
						
						if(empty($chekAlocatedId)){
							$billUpdateInfo=$this->AllocationByManagerModel->load('bills',$billId[$i]['id']);
							
							$billStatus=$billUpdateInfo[0]['billHistoryStatus'];

							$billAllocationData=array(
								'billId' => $billId[$i]['id'],
								'allocationId' => $data['alloca'][0]['id'],
								'billStatus'=>'1'
							);

							$res=$this->AllocationByManagerModel->insert('allocationsbills',$billAllocationData); 
							
							if(!$res==0){
								$rtData=array(
									'billCurrentStatus'=>'Allocated in '.$data['alloca'][0]['allocationCode'],
									'billHistoryStatus'=>$billStatus,
									'billType' =>'allocatedbillCurrent',
									'isBilled'=>'0',
									'isLostBill'=>'0',
									'isAllocated'=>'1',
									'deliveryslipOwnerApproval'=>'1'	
								);
								$rs=$this->AllocationByManagerModel->update('bills',$rtData,$billId[$i]['id']);
									
								if(!$rs==0){
									$history=array(
										'billId'=>$billId[$i]['id'],
										'allocationId' => $data['alloca'][0]['id'],
										'transactionStatus' =>'Allocated',
										'transactionDate'=>date('Y-m-d H:i:sa'),
										'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
									);
									$this->AllocationByManagerModel->insert('bill_transaction_history',$history);
								}
							} 
						}
					}
				}

				if(!empty($pastbillId)){
					for ($i=0; $i < count($pastbillId); $i++) {
						$chekAlocatedId=$this->AllocationByManagerModel->getDuplicatedAllocatedRecord('allocationsbills',$pastbillId[$i]['id'],$data['alloca'][0]['id']);

						if(empty($chekAlocatedId)){
							$billAllocationData=array(
								'billId' => $pastbillId[$i]['id'],
								'allocationId' => $data['alloca'][0]['id'],
								'billStatus'=>'2'
							);

							$billUpdateInfo=$this->AllocationByManagerModel->load('bills',$pastbillId[$i]['id']);

							if(($billUpdateInfo[0]['billType'] === "officeAdjustmentBill") || ($billUpdateInfo[0]['billType'] === "adHocDeliveryBill")){
								$this->AllocationByManagerModel->insert('allocationsbills',$billAllocationData); 
									
								if($this->db->affected_rows()>0){
									$billStatus=$billUpdateInfo[0]['billHistoryStatus'];
									$rtData=array(
										'billCurrentStatus'=>'Allocated in '.$data['alloca'][0]['allocationCode'],
										'billHistoryStatus'=>$billStatus,
										'isBilled'=>'0',
										'isLostBill'=>'0',
										'isAllocated'=>'1'		
									);
									$this->AllocationByManagerModel->update('bills',$rtData,$pastbillId[$i]['id']);
										
									if($this->db->affected_rows()>0){
										$history=array(
											'billId'=>$pastbillId[$i]['id'],
											'allocationId' => $data['alloca'][0]['id'],
											'transactionStatus' =>'Allocated',
											'transactionDate'=>date('Y-m-d H:i:sa'),
											'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
										);
										$this->AllocationByManagerModel->insert('bill_transaction_history',$history);
									}
								} 
							}else{
								$this->AllocationByManagerModel->insert('allocationsbills',$billAllocationData); 
								if($this->db->affected_rows()>0){
									$billStatus=$billUpdateInfo[0]['billHistoryStatus'];
									$rtData=array(
										'billCurrentStatus'=>'Allocated in '.$data['alloca'][0]['allocationCode'],
										'billType' =>'allocatedbillPass',
										'billHistoryStatus'=>$billStatus,
										'isBilled'=>'0',
										'isLostBill'=>'0',
										'isAllocated'=>'1'		
									);
									$this->AllocationByManagerModel->update('bills',$rtData,$pastbillId[$i]['id']);
									if($this->db->affected_rows()>0){
										$history=array(
											'billId'=>$pastbillId[$i]['id'],
											'allocationId' => $data['alloca'][0]['id'],
											'transactionStatus' =>'Allocated',
											'transactionDate'=>date('Y-m-d H:i:sa'),
											'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
										);
										$this->AllocationByManagerModel->insert('bill_transaction_history',$history);
									}
								}
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