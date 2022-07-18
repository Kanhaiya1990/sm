<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashbordController extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('ExcelModel');
		$this->load->helper('url');
		$this->load->library('session');
		date_default_timezone_set('Asia/Kolkata');
		ini_set('memory_limit', '-1');


		if(isset($this->session->userdata['codeKeyData'])) {
			$this->projectSessionName= $this->session->userdata['codeKeyData']['codeKeyValue'];
		}else{
			$this->load->view('LoginView');
		}
	}

	public function dashboardColorCode(){
		$data['setting']=$this->ExcelModel->getdata('tbl_settings');
		$this->load->view('dashboardColorSettingView',$data);
	}

	public function saveDashboard(){
		$id=$this->input->post('id');
		$colorCode=$this->input->post('colorValue');

		$arr=array(
			'name'=>$colorCode
		);
		$this->ExcelModel->update('tbl_settings',$arr,$id);
		
		$colorSession = array(
			'colorValue' => $colorCode
		);
		$this->session->set_userdata('colorSession', $colorSession);

	}
	
	public function index() {
		// $id = $this->uri->segment(2);
		// echo $id;exit;

		//Dynamic name for Distributor
		$getCode=$this->ExcelModel->getdata('office_details');
		$data['nameForDistributor']="";
		if(!empty($getCode)){
			$data['nameForDistributor']=$getCode[0]['distributorName'];
		}

		$designation = $this->session->userdata[$this->projectSessionName]['designation']; 
		// echo $designation;exit;
		$data['company']=$this->ExcelModel->getAllCompanies('company');
		if($designation=="admin"){  
			$this->load->view('dashboard/DashbordView');
		}else{
			$this->load->model('BillTransactionModel');
			
			$dayCount=0;
			$getDays=$this->BillTransactionModel->load('highlighting_days',1);
			if(!empty($getDays)){
				$dayCount=$getDays[0]['days'];
			}
			
			//current date
			$currentDate=date('Y-m-d');
			//previous 3 days date
			$customDate =date('Y-m-d',strtotime('-'.$dayCount.' day', strtotime($currentDate)));
			

			//lost Bills
			$lostBills=0;
			$lostBillsCount=0;
			$nestleBills=$this->BillTransactionModel->loadLostBillsWithComp('bills',$customDate);
			if(!empty($nestleBills)){
				foreach($nestleBills as $bill){
					$lostBills=$lostBills+$bill['pendingAmt'];
					$lostBillsCount++;
				}
			}
			$data['lostBills']=$lostBills;
			$data['lostBillsCount']=$lostBillsCount;

			//lost Cheque
			$lostCheques=0;
			$lostChequesCount=0;
			$nestleBills=$this->BillTransactionModel->lostChequeWithComp('bills',$customDate);
			if(!empty($nestleBills)){
				foreach($nestleBills as $bill){
					$lostCheques=$lostCheques+$bill['pendingAmt'];
					$lostChequesCount++;
				}
			}
			$data['lostCheques']=$lostCheques;
			$data['lostChequesCount']=$lostChequesCount;


			//lost NEFT
			$lostNeft=0;
			$lostNeftCount=0;
			$nestleBills=$this->BillTransactionModel->lostNeftWithComp('bills',$customDate);
			if(!empty($nestleBills)){
				foreach($nestleBills as $bill){
					$lostNeft=$lostNeft+$bill['pendingAmt'];
					$lostNeftCount++;
				}
			}
			$data['lostNeft']=$lostNeft;
			$data['lostNeftCount']=$lostNeftCount;

			//Resend Bills
			$resendBills=0;
			$resendBillsCount=0;
			$nestleBills=$this->BillTransactionModel->loadResendBillsWithComp('bills','Nestle',$customDate);
			if(!empty($nestleBills)){
				foreach($nestleBills as $bill){
					$resendBills=$resendBills+$bill['pendingAmt'];
					$resendBillsCount++;
				}
			}
			$data['resendBills']=$resendBills;
			$data['resendBillsCount']=$resendBillsCount;

			$pendingAllocationCount=0;
			$pendingAllocationTotal=0;
			$pendingAllocations=$this->BillTransactionModel->getPendingAllocations('allocations',$customDate);
			if(!empty($pendingAllocations)){
				foreach($pendingAllocations as $itm){
					$pendingAllocationCount++;
					$pendingAllocationTotal=$pendingAllocationTotal+$itm['allocationTotalAmount'];
				}
			}

			$pendingAllocations=$this->BillTransactionModel->getPendingOfficeAllocations('allocations_officeadjustment',$customDate);
			$pendingAllocationCount=$pendingAllocationCount+count($pendingAllocations);
			
			$pendingAllocations=$this->BillTransactionModel->getPendingAllocationsAmount('allocations_officeadjustment',$customDate);
			if(!empty($pendingAllocations)){
				foreach($pendingAllocations as $itm){
					$pendingAllocationTotal=$pendingAllocationTotal+$itm['pendingAmt'];
				}
			}

			$data['pendingAllocationCount']=$pendingAllocationCount;
			$data['pendingAllocationTotal']=$pendingAllocationTotal;

			$this->load->view('dashboard/DashbordView1',$data);
		} 
	}

	public function divisionWiseSale() {
		$this->load->model('BillTransactionModel');

		$dayCount=0;
		$getDays=$this->BillTransactionModel->load('highlighting_days',1);
		if(!empty($getDays)){
			$dayCount=$getDays[0]['days'];
		}
	
		//current date
		$currentDate=date('Y-m-d');
		//current month
		$currentMonth=date('Y-m-01');
		//previous month
		$previousMonth = strtotime('-1 months', strtotime($currentMonth));

		

		$threeMonth = strtotime('-3 months', strtotime($currentDate));

		//previous month
		$previousMonth = strtotime('-1 months', strtotime($currentMonth));
		$monthStart= date("Y-m-01",$previousMonth);//since last month with same month
		// First day of the month.
		$lastMonthfirstDay= date('Y-m-01', strtotime($monthStart));
		// Last day of the month.
		$lastMonthLastDay= date('Y-m-t', strtotime($monthStart));

		
		$monthCount=date('m');
		$yearStart="";
		if($monthCount>3){//current year start from april
			$yearStart=date('Y-04-01');
		}else{
			$yearStart = date('Y-04-01',strtotime('-12 months', strtotime(date('Y-04-01'))));
		}
		
		//Previos year start from april
		$lastYearStart = date('Y-04-01',strtotime('-12 months', strtotime($yearStart)));
		$lastYearSameMonth = date('Y-m-01',strtotime('-12 months', strtotime($currentMonth)));//last year 01 Day for current month
		$lastYearSameDay = date('Y-m-d',strtotime('-12 months', strtotime($currentDate)));//last year current Day

		$companyData=$this->BillTransactionModel->getdata('company');
		$compSaleArr=array();
		$compSrArr=array();

		$deliverySlipDailySale=0;
		$deliverySlipMonthlySale=0;
		$deliverySlipYearlySale=0;
		$deliverySlipLastMonthSale=0;
		$deliverySlipLastYearSameMonthSale=0;
		$deliverySlipLastYearSameDateSale=0;

		$deliverySlipDailySaleSr=0;
		$deliverySlipMonthlySaleSr=0;
		$deliverySlipYearlySaleSr=0;
		$deliverySlipLastMonthSaleSr=0;
		$deliverySlipLastYearSameMonthSaleSr=0;
		$deliverySlipLastYearSameDateSaleSr=0;

		if(!empty($companyData)){
			foreach($companyData as $data){
				if($data['name'] !="General"){
					$dailySaleSr=0;
					$monthlySaleSr=0;
					$yearlySaleSr=0;
					$lastMonthSaleSr=0;
					$lastYearSameMonthSaleSr=0;
					$lastYearSameDateSaleSr=0;

					// 1. Daily SR
					$billsSaleDailySr=$this->BillTransactionModel->getSrAllBillsByDate('allocation_sr_details',$data['name'],$currentDate,$currentDate);	
					if(!empty($billsSaleDailySr)){
						foreach($billsSaleDailySr as $bill){
							if($bill['isDeliverySlipBill']>0){
								$avg=($bill['billItemNetAmount']/$bill['BillItemQuantity']);
								$deliverySlipDailySaleSr=$deliverySlipDailySaleSr+($avg*$bill['quantity']);
							}else{
								$avg=($bill['billItemNetAmount']/$bill['BillItemQuantity']);
								$dailySaleSr=$dailySaleSr+($avg*$bill['quantity']);
							}
						}
					}

					// 2. Monthly SR
					$billsSaleSr=$this->BillTransactionModel->getSrAllBillsByDate('allocation_sr_details',$data['name'],$currentMonth,$currentDate);	
					if(!empty($billsSaleSr)){
						foreach($billsSaleSr as $bill){
							if($bill['isDeliverySlipBill']>0){
								$avg=($bill['billItemNetAmount']/$bill['BillItemQuantity']);
								$deliverySlipMonthlySaleSr=$deliverySlipMonthlySaleSr+($avg*$bill['quantity']);
							}else{
								$avg=($bill['billItemNetAmount']/$bill['BillItemQuantity']);
								$monthlySaleSr=$monthlySaleSr+($avg*$bill['quantity']);
							}
						}
					}

					// 3. Same month last year SR
					$billsSaleSr=$this->BillTransactionModel->getSrAllBillsByDate('allocation_sr_details',$data['name'],$lastYearSameMonth,$lastYearSameDay);	
					if(!empty($billsSaleSr)){
						foreach($billsSaleSr as $bill){
							if($bill['isDeliverySlipBill']>0){
								$avg=($bill['billItemNetAmount']/$bill['BillItemQuantity']);
								$deliverySlipLastYearSameMonthSaleSr=$deliverySlipLastYearSameMonthSaleSr+($avg*$bill['quantity']);
							}else{
								$avg=($bill['billItemNetAmount']/$bill['BillItemQuantity']);
								$lastYearSameMonthSaleSr=$lastYearSameMonthSaleSr+($avg*$bill['quantity']);
							}
						}
					}

					// 5. Last Month SR
					$billsSaleSr=$this->BillTransactionModel->getSrAllBillsByDate('allocation_sr_details',$data['name'],$lastMonthfirstDay,$lastMonthLastDay);	
					if(!empty($billsSaleSr)){
						foreach($billsSaleSr as $bill){
							if($bill['isDeliverySlipBill']>0){
								$avg=($bill['billItemNetAmount']/$bill['BillItemQuantity']);
								$deliverySlipLastMonthSaleSr=$deliverySlipLastMonthSaleSr+($avg*$bill['quantity']);
							}else{
								$avg=($bill['billItemNetAmount']/$bill['BillItemQuantity']);
								$lastMonthSaleSr=$lastMonthSaleSr+($avg*$bill['quantity']);
							}
						}
					}

					// 6. Current Yearly SR from 1 April till date
					$billsSaleSr=$this->BillTransactionModel->getSrAllBillsByDate('allocation_sr_details',$data['name'],$yearStart,$currentDate);	
					if(!empty($billsSaleSr)){
						foreach($billsSaleSr as $bill){
							if($bill['isDeliverySlipBill']>0){
								$avg=($bill['billItemNetAmount']/$bill['BillItemQuantity']);
								$deliverySlipYearlySaleSr=$deliverySlipYearlySaleSr+($avg*$bill['quantity']);
							}else{
								$avg=($bill['billItemNetAmount']/$bill['BillItemQuantity']);
								$yearlySaleSr=$yearlySaleSr+($avg*$bill['quantity']);
							}
						}
					}
					
					// 7. Last Yearly SR from 1 April 
					$billsSaleSr=$this->BillTransactionModel->getSrAllBillsByDate('allocation_sr_details',$data['name'],$lastYearStart,$lastYearSameDay);	
					if(!empty($billsSaleSr)){
						foreach($billsSaleSr as $bill){
							if($bill['isDeliverySlipBill']>0){
								$avg=($bill['billItemNetAmount']/$bill['BillItemQuantity']);
								$deliverySlipLastYearSameDateSaleSr=$deliverySlipLastYearSameDateSaleSr+($avg*$bill['quantity']);
							}else{
								$avg=($bill['billItemNetAmount']/$bill['BillItemQuantity']);
								$lastYearSameDateSaleSr=$lastYearSameDateSaleSr+($avg*$bill['quantity']);
							}
						}
					}

					

					// Sale details
					$nestleDailySale=0;
					$nestleMonthlySale=0;
					$nestleYearlySale=0;
					$nestleLastMonthSale=0;
					$nestleLastYearSameMonthSale=0;
					$nestleLastYearSameDateSale=0;

					// 1. Daily Sale
					$nestleBillsSaleDaily=$this->BillTransactionModel->getAllBillsByDate('bills',$data['name'],$currentDate,$currentDate);	
					if(!empty($nestleBillsSaleDaily)){
						foreach($nestleBillsSaleDaily as $bill){
							if($bill['isDeliverySlipBill']>0){
								$deliverySlipDailySale=$deliverySlipDailySale+$bill['billNetAmount'];
							}else{
								$nestleDailySale=$nestleDailySale+$bill['billNetAmount'];
							}	
						}
					}

					

					// 2. Monthly Sale
					$nestleBillsSale=$this->BillTransactionModel->getAllBillsByDate('bills',$data['name'],$currentMonth,$currentDate);	
					if(!empty($nestleBillsSale)){
						foreach($nestleBillsSale as $bill){
							if($bill['isDeliverySlipBill']>0){
								$deliverySlipMonthlySale=$deliverySlipMonthlySale+$bill['billNetAmount'];
							}else{
								$nestleMonthlySale=$nestleMonthlySale+$bill['billNetAmount'];
							}
						}
					}

						

					// 3. Same month last year Sale
					$nestleBillsSale=$this->BillTransactionModel->getAllBillsByDate('bills',$data['name'],$lastYearSameMonth,$lastYearSameDay);	
					if(!empty($nestleBillsSale)){
						foreach($nestleBillsSale as $bill){
							if($bill['isDeliverySlipBill']>0){
								$deliverySlipLastYearSameMonthSale=$deliverySlipLastYearSameMonthSale+$bill['billNetAmount'];
							}else{
								$nestleLastYearSameMonthSale=$nestleLastYearSameMonthSale+$bill['billNetAmount'];
							}
						}
					}

					// 5. Last Month Sale
					$nestleBillsSale=$this->BillTransactionModel->getAllBillsByDate('bills',$data['name'],$lastMonthfirstDay,$lastMonthLastDay);	
					// print_r();exit;
					if(!empty($nestleBillsSale)){
						foreach($nestleBillsSale as $bill){
							if($bill['isDeliverySlipBill']>0){
								$deliverySlipLastMonthSale=$deliverySlipLastMonthSale+$bill['billNetAmount'];
							}else{
								$nestleLastMonthSale=$nestleLastMonthSale+$bill['billNetAmount'];
							}	
						}
					}

					// 6. Current Yearly Sale from 1 April till date
					$nestleBillsSale=$this->BillTransactionModel->getAllBillsByDate('bills',$data['name'],$yearStart,$currentDate);	
					if(!empty($nestleBillsSale)){
						foreach($nestleBillsSale as $bill){
							if($bill['isDeliverySlipBill']>0){
								$deliverySlipYearlySale=$deliverySlipYearlySale+$bill['billNetAmount'];
							}else{
								$nestleYearlySale=$nestleYearlySale+$bill['billNetAmount'];
							}
							
						}
					}
					
					// 7. Last Yearly Sale from 1 April 
					$nestleBillsSale=$this->BillTransactionModel->getAllBillsByDate('bills',$data['name'],$lastYearStart,$lastYearSameDay);	
					if(!empty($nestleBillsSale)){
						foreach($nestleBillsSale as $bill){

							if($bill['isDeliverySlipBill']>0){
								$deliverySlipLastYearSameDateSale=$deliverySlipLastYearSameDateSale+$bill['billNetAmount'];
							}else{
								$nestleLastYearSameDateSale=$nestleLastYearSameDateSale+$bill['billNetAmount'];
							}
							
						}
					}

					$data['nestleDailySale']=$nestleDailySale;
					$data['nestleCurrentMonthSale']=$nestleMonthlySale;
					$data['nestleCurrentYearSale']=$nestleYearlySale;
					$data['nestleLastMonthSale']=$nestleLastMonthSale;
					$data['nestleLastYearSameMonthSale']=$nestleLastYearSameMonthSale;
					$data['nestleLastYearSameDateSale']=$nestleLastYearSameDateSale;

					$data['deliverySlipDailySale']=$deliverySlipDailySale;
					$data['deliverySlipMonthlySale']=$deliverySlipMonthlySale;
					$data['deliverySlipYearlySale']=$deliverySlipYearlySale;
					$data['deliverySlipLastMonthSale']=$deliverySlipLastMonthSale;
					$data['deliverySlipLastYearSameMonthSale']=$deliverySlipLastYearSameMonthSale;
					$data['deliverySlipLastYearSameDateSale']=$deliverySlipLastYearSameDateSale;

					$data['dailySaleSr']=$dailySaleSr;
					$data['monthlySaleSr']=$monthlySaleSr;
					$data['yearlySaleSr']=$yearlySaleSr;
					$data['lastMonthSaleSr']=$lastMonthSaleSr;
					$data['lastYearSameMonthSaleSr']=$lastYearSameMonthSaleSr;
					$data['lastYearSameDateSaleSr']=$lastYearSameDateSaleSr;

					$data['deliverySlipDailySaleSr']=$deliverySlipDailySaleSr;
					$data['deliverySlipMonthlySaleSr']=$deliverySlipMonthlySaleSr;
					$data['deliverySlipYearlySaleSr']=$deliverySlipYearlySaleSr;
					$data['deliverySlipLastMonthSaleSr']=$deliverySlipLastMonthSaleSr;
					$data['deliverySlipLastYearSameMonthSaleSr']=$deliverySlipLastYearSameMonthSaleSr;
					$data['deliverySlipLastYearSameDateSaleSr']=$deliverySlipLastYearSameDateSaleSr;

					$monthGrowth="";
					$yearGrowth="";
					if(($data['nestleCurrentMonthSale']==0) || ($data['nestleLastYearSameMonthSale']==0)){
						$data['nestleMonthGrowth']="-";
						$monthGrowth="-";
					}else{
						$data['nestleMonthGrowth']=(($data['nestleCurrentMonthSale']/$data['nestleLastYearSameMonthSale'])-1)*100;
						$monthGrowth=(($data['nestleCurrentMonthSale']/$data['nestleLastYearSameMonthSale'])-1)*100;
					}

					if(($data['nestleCurrentYearSale']==0) || ($data['nestleLastYearSameDateSale']==0)){
						$data['nestleYearGrowth']="-";
						$yearGrowth="-";
					}else{
						$data['nestleYearGrowth']=(($data['nestleCurrentYearSale']/$data['nestleLastYearSameDateSale'])-1)*100;
						$yearGrowth=(($data['nestleCurrentYearSale']/$data['nestleLastYearSameDateSale'])-1)*100;
					}

					$dataItems=array(
						'compName'=>$data['name'],
						'dailySale'=>$nestleDailySale,
						'currentMonthSale'=>$nestleMonthlySale,
						'currentYearSale'=>$nestleYearlySale,
						'lastMonthSale'=>$nestleLastMonthSale,
						'lastYearSameMonthSale'=>$nestleLastYearSameMonthSale,
						'lastYearSameDateSale'=>$nestleLastYearSameDateSale,
						'monthGrowth'=>$monthGrowth,
						'yearGrowth'=>$yearGrowth,
						'dailySaleSr'=>$dailySaleSr,
						'monthlySaleSr'=>$monthlySaleSr,
						'yearlySaleSr'=>$yearlySaleSr,
						'lastMonthSaleSr'=>$lastMonthSaleSr,
						'lastYearSameMonthSaleSr'=>$lastYearSameMonthSaleSr,
						'lastYearSameDateSaleSr'=>$lastYearSameDateSaleSr
					);
					array_push($compSaleArr,$dataItems);
				}

				
			}

			$monthGrowth="";
			$yearGrowth="";
			if(($deliverySlipMonthlySale==0) || ($deliverySlipLastYearSameMonthSale==0)){
				$data['nestleMonthGrowth']="-";
				$monthGrowth="-";
			}else{
				$data['nestleMonthGrowth']=(($deliverySlipMonthlySale/$deliverySlipLastYearSameMonthSale)-1)*100;
				$monthGrowth=(($deliverySlipMonthlySale/$deliverySlipLastYearSameMonthSale)-1)*100;
			}

			if(($deliverySlipYearlySale==0) || ($deliverySlipLastYearSameDateSale==0)){
				$data['nestleYearGrowth']="-";
				$yearGrowth="-";
			}else{
				$data['nestleYearGrowth']=(($deliverySlipYearlySale/$deliverySlipLastYearSameDateSale)-1)*100;
				$yearGrowth=(($deliverySlipYearlySale/$deliverySlipLastYearSameDateSale)-1)*100;
			}

			$dataItems=array(
				'compName'=>'DeliverySlip',
				'dailySale'=>$deliverySlipDailySale,
				'currentMonthSale'=>$deliverySlipMonthlySale,
				'currentYearSale'=>$deliverySlipYearlySale,
				'lastMonthSale'=>$deliverySlipLastMonthSale,
				'lastYearSameMonthSale'=>$deliverySlipLastYearSameMonthSale,
				'lastYearSameDateSale'=>$deliverySlipLastYearSameDateSale,
				'monthGrowth'=>$monthGrowth,
				'yearGrowth'=>$yearGrowth,
				'dailySaleSr'=>$deliverySlipDailySaleSr,
				'monthlySaleSr'=>$deliverySlipMonthlySaleSr,
				'yearlySaleSr'=>$deliverySlipYearlySaleSr,
				'lastMonthSaleSr'=>$deliverySlipLastMonthSaleSr,
				'lastYearSameMonthSaleSr'=>$deliverySlipLastYearSameMonthSaleSr,
				'lastYearSameDateSaleSr'=>$deliverySlipLastYearSameDateSaleSr
			);
			array_push($compSaleArr,$dataItems);
		}

		$totaldailySale=0;
		$totalcurrentMonthSale=0;
		$totallastYearSameMonthSale=0;
		$totallastMonthSale=0;
		$totalcurrentYearSale=0;

		$totaldailySr=0;
		$totalcurrentMonthSr=0;
		$totallastYearSameMonthSr=0;
		$totallastMonthSr=0;
		$totalcurrentYearSr=0;

		foreach($compSaleArr as $data){
			
			$totaldailySale=$totaldailySale+$data['dailySale'];
			$totalcurrentMonthSale=$totalcurrentMonthSale+$data['currentMonthSale'];
			$totallastYearSameMonthSale=$totallastYearSameMonthSale+$data['lastYearSameMonthSale'];
			$totallastMonthSale=$totallastMonthSale+$data['lastMonthSale'];
			$totalcurrentYearSale=$totalcurrentYearSale+$data['currentYearSale'];

			$totaldailySr=$totaldailySr+$data['dailySaleSr'];
			$totalcurrentMonthSr=$totalcurrentMonthSr+$data['monthlySaleSr'];
			$totallastYearSameMonthSr=$totallastYearSameMonthSr+$data['lastYearSameMonthSaleSr'];
			$totallastMonthSr=$totallastMonthSr+$data['lastMonthSaleSr'];
			$totalcurrentYearSr=$totalcurrentYearSr+$data['lastYearSameDateSaleSr'];

		?>
			<tr>
				<td><?php echo $data['compName']; ?></td>
				<td class="text-right"><?php echo number_format($data['dailySale']-$data['dailySaleSr']); ?></td>
				<td class="text-right"><?php echo number_format($data['currentMonthSale']-$data['monthlySaleSr']); ?></td>
				<td class="text-right"><?php echo number_format($data['lastYearSameMonthSale']-$data['lastYearSameMonthSaleSr']); ?></td>
				<td class="text-right"><?php echo round($data['monthGrowth'],1).' %'; ?></td>
				<td class="text-right"><?php echo number_format($data['lastMonthSale']-$data['lastMonthSaleSr']); ?></td>
				<td class="text-right"><?php echo number_format($data['currentYearSale']-$data['lastYearSameDateSaleSr']); ?></td>
				<td class="text-right"><?php echo round($data['yearGrowth'],1).' %'; ?></td>
			</tr>
			<?php
	 }
 ?>
	 
		 <tr>
			 <th>Total</th>
			 <th class="text-right"><?php echo number_format($totaldailySale-$totaldailySr); ?></th>
			 <th class="text-right"><?php echo number_format($totalcurrentMonthSale-$totalcurrentMonthSr); ?></th>
			 <th class="text-right"><?php echo number_format($totallastYearSameMonthSale-$totallastYearSameMonthSr); ?></th>
			 <th class="text-right">-</th>
			 <th class="text-right"><?php echo number_format($totallastMonthSale-$totallastMonthSr); ?></th>
			 <th class="text-right"><?php echo number_format($totalcurrentYearSale-$totalcurrentYearSr); ?></th>
			 <th class="text-right">-</th>
		 </tr>
  <?php  
		
	}

	public function companyWiseOutstanding(){
		$this->load->model('BillTransactionModel');
		$dayCount=0;
		$getDays=$this->BillTransactionModel->load('highlighting_days',1);
		if(!empty($getDays)){
			$dayCount=$getDays[0]['days'];
		}
// 		echo $dayCount;exit;
		$dayCount=3;
		//current date
		$currentDate=date('Y-m-d');
		//previous 3 days date
		$customDate =date('Y-m-d',strtotime('-'.$dayCount.' day', strtotime($currentDate)));
		$uaccountedCount=0;
		$companyData=$this->BillTransactionModel->getdata('company');
		$compOutstandingData=array();
		if(!empty($companyData)){
			foreach($companyData as $comp){
				if($comp['name'] !="General"){
					$totalNestleUnaccounted=0;
					$totalNestlePendingSupply=0;
					$totalNestleSigned=0;
					$totalNestleCheque=0;
					$totalNestleChequeBanked=0;
					$totalNestleOutstanding=0;
					$unNestle=0;
					$nestleUnBills=$this->BillTransactionModel->getUnAllBills('bills',$comp['name'],$customDate);
					if(!empty($nestleUnBills)){
						foreach($nestleUnBills as $bill){
							if(($bill['isAllocated']!=1) && ($bill['billType']=="") && ($bill['netAmount'] == $bill['pendingAmt'])){
								$unNestle=$unNestle+$bill['pendingAmt'];
							}
							
						}
					}
					
					$nestleBills=$this->BillTransactionModel->getAllBills('bills',$comp['name'],$customDate);
					if(!empty($nestleBills)){
						foreach($nestleBills as $bill){
							if(($bill['isAllocated']==0) && ($bill['billType']=="") && ($bill['netAmount'] == $bill['pendingAmt'])){
							    
								$totalNestleUnaccounted=$totalNestleUnaccounted+$bill['pendingAmt'];
								$uaccountedCount++;
							}

							if(($bill['isAllocated']==1) && ($bill['billType']=="" || $bill['billType']=="allocatedbillCurrent" || $bill['billType']=="allocatedbillPass")){
								$totalNestlePendingSupply=$totalNestlePendingSupply+$bill['pendingAmt']+$bill['fsCashAmt']+$bill['fsSrAmt']+$bill['fsNeftAmt']+$bill['fsChequeAmt'];
							}

							if(($bill['isAllocated']!=1 || $bill['isAllocated']==1) && (($bill['netAmount'] != $bill['pendingAmt'])) || ($bill['billType'] =="adHocDeliveryBill") ){
								
								$totalNestleSigned=$totalNestleSigned+$bill['pendingAmt']+$bill['fsCashAmt']+$bill['fsSrAmt']+$bill['fsNeftAmt']+$bill['fsChequeAmt'];
								
							}
							$totalNestleOutstanding=$totalNestleOutstanding+$bill['pendingAmt']+$bill['fsCashAmt']+$bill['fsSrAmt']+$bill['fsNeftAmt']+$bill['fsChequeAmt'];;
						}
					}

					$nestleNewChequeBillDetails=$this->BillTransactionModel->getStatusAllBillDetails('billpayments',$comp['name']);
					if(!empty($nestleNewChequeBillDetails)){
						foreach($nestleNewChequeBillDetails as $bill){
							$totalNestleCheque=$totalNestleCheque+$bill['chAmount'];
						}
					}

					$nestleBankedChequeBillDetails=$this->BillTransactionModel->getAllBillDetails('billpayments',$comp['name'],'Banked');
					if(!empty($nestleBankedChequeBillDetails)){
						foreach($nestleBankedChequeBillDetails as $bill){
							$totalNestleChequeBanked=$totalNestleChequeBanked+$bill['chAmount'];
						}
					}
					$dataArr=array(
						'compName'=>$comp['name'],
						'uaccountedCount'=>$uaccountedCount,
						'totalUnaccounted'=>$totalNestleUnaccounted,
						'totalPendingSupply'=>$totalNestlePendingSupply+$unNestle,
						'totalSigned'=>$totalNestleSigned,
						'totalOutstanding'=>$totalNestleOutstanding,
						'totalCheque'=>$totalNestleCheque,
						'totalChequeBanked'=>$totalNestleChequeBanked,
					);
					
					array_push($compOutstandingData,$dataArr);
				}
			}
		}

		$totalUnaccounted=0;
		$totalPendingSupply=0;
		$totalSigned=0;
		$totalChequeHand=0;
		$totalChequeBank=0;
		$totalOutstandingAmt=0;
		
		if(!empty($compOutstandingData)){
			foreach($compOutstandingData as $data){
				if($data['compName'] !="General"){
					$totalUnaccounted=$totalUnaccounted+$data['totalUnaccounted'];
					$totalPendingSupply=$totalPendingSupply+$data['totalPendingSupply'];
					$totalSigned=$totalSigned+$data['totalSigned'];
					$totalChequeHand=$totalChequeHand+$data['totalCheque'];
					$totalChequeBank=$totalChequeBank+$data['totalChequeBanked'];
					$totalOutstandingAmt=$totalOutstandingAmt+$data['totalUnaccounted']+$data['totalPendingSupply']+$data['totalSigned']+$data['totalCheque']+$data['totalChequeBanked'];

	?>
			<tr>
				<td><?php echo $data['compName']; ?></td>
				<td class="text-right"><?php echo number_format($data['totalUnaccounted']); ?></td>
				<td class="text-right"><?php echo number_format($data['totalPendingSupply']); ?></td>
				<td class="text-right"><?php echo number_format($data['totalSigned']); ?></td>
				<td class="text-right"><?php echo number_format($data['totalCheque']); ?></td>
				<td class="text-right"><?php echo number_format($data['totalChequeBanked']); ?></td>
				<td class="text-right"><?php echo number_format($data['totalUnaccounted']+$data['totalPendingSupply']+$data['totalSigned']+$data['totalCheque']+$data['totalChequeBanked']); ?></td>
			</tr>  
	<?php
			}
		  }
		} 
	?>
		<tr>
			<th>Total</th>
			<th class="text-right"><?php echo number_format($totalUnaccounted); ?></th>
			<th class="text-right"><?php echo number_format($totalPendingSupply); ?></th>
			<th class="text-right"><?php echo number_format($totalSigned); ?></th>
			<th class="text-right"><?php echo number_format($totalChequeHand); ?></th>
			<th class="text-right"><?php echo number_format($totalChequeBank); ?></th>
			<th class="text-right"><?php echo number_format($totalOutstandingAmt); ?></th>
		</tr>
<?php
	}

	public function admin() {
		$this->load->view('dashboard/DashbordView');
	}

	public function indexBackup() {
		

		$designation = $this->session->userdata[$distributorCode]['designation']; 
		$data['company']=$this->ExcelModel->getAllCompanies('company');
		if($designation=="admin"){  
			$this->load->view('dashboard/DashbordView');
		}else{
			$this->load->model('BillTransactionModel');

			//salesman data
			$getSalesmans=$this->ExcelModel->getAllSalesmans('bills');
			$salesmanArray=array();
			if(!empty($getSalesmans)){
				foreach($getSalesmans as $item){
					$data=$this->ExcelModel->getDataUsingSalesman('bills',$item['salesmanCode'],$item['salesman']);
					if(!empty($data)){
						$unaccounted=0;
						$pendingSupply=0;
						$signed=0;
						foreach($data as $itm){
							if($itm['isAllocated']==1){
								$pendingSupply=$pendingSupply+$itm['pendingAmt'];
							}

							if(($itm['isAllocated']!=1) && ($itm['netAmount'] != $itm['pendingAmt'])){
								$signed=$signed+$itm['pendingAmt'];
							}

							if(($itm['isAllocated']!=1) && ($itm['netAmount'] == $itm['pendingAmt'])){
								$unaccounted=$unaccounted+$itm['pendingAmt'];
							}
						}
						$salesmanData=array(
							'salesman'=>$item['salesman'],
							'unaccounted'=>$unaccounted,
							'pendingSupply'=>$pendingSupply,
							'signed'=>$signed
						);

						array_push($salesmanArray,$salesmanData);
					}
				}
			}

			//bills not having salesman data
			$emptydata=$this->ExcelModel->getDataUsingSalesman('bills','','');
			if(!empty($emptydata)){
				$unaccounted=0;
				$pendingSupply=0;
				$signed=0;
				foreach($emptydata as $itm){
					if($itm['isAllocated']==1){
						$pendingSupply=$pendingSupply+$itm['pendingAmt'];
					}

					if(($itm['isAllocated']!=1) && ($itm['netAmount'] != $itm['pendingAmt'])){
						$signed=$signed+$itm['pendingAmt'];
					}

					if(($itm['isAllocated']!=1) && ($itm['netAmount'] == $itm['pendingAmt'])){
						$unaccounted=$unaccounted+$itm['pendingAmt'];
					}
				}
				$salesmanData=array(
					'salesman'=>'non-salesman-data',
					'unaccounted'=>$unaccounted,
					'pendingSupply'=>$pendingSupply,
					'signed'=>$signed
				);

				array_push($salesmanArray,$salesmanData);
			}

			$data['salesmanArray']=$salesmanArray;
			
			$dayCount=0;
			$getDays=$this->BillTransactionModel->load('highlighting_days',1);
			if(!empty($getDays)){
			    $dayCount=$getDays[0]['days'];
			}
		
			//current date
			$currentDate=date('Y-m-d');
			//current month
			$currentMonth=date('Y-m-01');

			$threeMonth = strtotime('-3 months', strtotime($currentDate));

			//previous month
			$previousMonth = strtotime('-1 months', strtotime($currentMonth));
			$monthStart= date("Y-m-01",$previousMonth);//since last month with same month
			// First day of the month.
			$lastMonthfirstDay= date('Y-m-01', strtotime($monthStart));
			// Last day of the month.
			$lastMonthLastDay= date('Y-m-t', strtotime($monthStart));

			//current year start from april
			$yearStart=date('Y-04-01');

			//Previos year start from april
			$lastYearStart = date('Y-04-01',strtotime('-12 months', strtotime($yearStart)));
			$lastYearSameMonth = date('Y-m-01',strtotime('-12 months', strtotime($currentMonth)));//last year 01 Day for current month
			$lastYearSameDay = date('Y-m-d',strtotime('-12 months', strtotime($currentDate)));//last year current Day


			$companyData=$this->BillTransactionModel->getdata('company');
			$compSaleArr=array();
			if(!empty($companyData)){
				foreach($companyData as $data){
					if($data['name'] !="General"){
						// Sale details
						$nestleDailySale=0;
						$nestleMonthlySale=0;
						$nestleYearlySale=0;
						$nestleLastMonthSale=0;
						$nestleLastYearSameMonthSale=0;
						$nestleLastYearSameDateSale=0;

						// 1. Daily Sale
						$nestleBillsSaleDaily=$this->BillTransactionModel->getAllBillsByDate('bills',$data['name'],$currentDate,$currentDate);	
						if(!empty($nestleBillsSaleDaily)){
							foreach($nestleBillsSaleDaily as $bill){

								$nestleDailySale=$nestleDailySale+$bill['billNetAmount'];
							}
						}

						// 2. Monthly Sale
						$nestleBillsSale=$this->BillTransactionModel->getAllBillsByDate('bills',$data['name'],$currentMonth,$currentDate);	
						if(!empty($nestleBillsSale)){
							foreach($nestleBillsSale as $bill){
								$nestleMonthlySale=$nestleMonthlySale+$bill['billNetAmount'];
							}
						}

						// 3. Same month last year Sale
						$nestleBillsSale=$this->BillTransactionModel->getAllBillsByDate('bills',$data['name'],$lastYearSameMonth,$lastYearSameDay);	
						if(!empty($nestleBillsSale)){
							foreach($nestleBillsSale as $bill){
								$nestleLastYearSameMonthSale=$nestleLastYearSameMonthSale+$bill['billNetAmount'];
							}
						}

						// 5. Last Month Sale
						$nestleBillsSale=$this->BillTransactionModel->getAllBillsByDate('bills',$data['name'],$lastMonthfirstDay,$lastMonthLastDay);	
						if(!empty($nestleBillsSale)){
							foreach($nestleBillsSale as $bill){
								$nestleLastMonthSale=$nestleLastMonthSale+$bill['billNetAmount'];
							}
						}

						// 6. Current Yearly Sale from 1 April till date
						$nestleBillsSale=$this->BillTransactionModel->getAllBillsByDate('bills',$data['name'],$yearStart,$currentDate);	
						if(!empty($nestleBillsSale)){
							foreach($nestleBillsSale as $bill){

								$nestleYearlySale=$nestleYearlySale+$bill['billNetAmount'];
							}
						}
						
						// 7. Last Yearly Sale from 1 April 
						$nestleBillsSale=$this->BillTransactionModel->getAllBillsByDate('bills',$data['name'],$lastYearStart,$lastYearSameDay);	
						if(!empty($nestleBillsSale)){
							foreach($nestleBillsSale as $bill){
								$nestleLastYearSameDateSale=$nestleLastYearSameDateSale+$bill['billNetAmount'];
							}
						}

						$data['nestleDailySale']=$nestleDailySale;
						$data['nestleCurrentMonthSale']=$nestleMonthlySale;
						$data['nestleCurrentYearSale']=$nestleYearlySale;
						$data['nestleLastMonthSale']=$nestleLastMonthSale;
						$data['nestleLastYearSameMonthSale']=$nestleLastYearSameMonthSale;
						$data['nestleLastYearSameDateSale']=$nestleLastYearSameDateSale;

						$monthGrowth="";
						$yearGrowth="";
						if(($data['nestleCurrentMonthSale']==0) || ($data['nestleLastYearSameMonthSale']==0)){
							$data['nestleMonthGrowth']="-";
							$monthGrowth="-";
						}else{
							$data['nestleMonthGrowth']=(($data['nestleCurrentMonthSale']/$data['nestleLastYearSameMonthSale'])-1)*100;
							$monthGrowth=(($data['nestleCurrentMonthSale']/$data['nestleLastYearSameMonthSale'])-1)*100;
						}

						if(($data['nestleCurrentYearSale']==0) || ($data['nestleLastYearSameDateSale']==0)){
							$data['nestleYearGrowth']="-";
							$yearGrowth="-";
						}else{
							$data['nestleYearGrowth']=(($data['nestleCurrentYearSale']/$data['nestleLastYearSameDateSale'])-1)*100;
							$yearGrowth=(($data['nestleCurrentYearSale']/$data['nestleLastYearSameDateSale'])-1)*100;
						}

						$dataItems=array(
							'compName'=>$data['name'],
							'dailySale'=>$nestleDailySale,
							'currentMonthSale'=>$nestleMonthlySale,
							'currentYearSale'=>$nestleYearlySale,
							'lastMonthSale'=>$nestleLastMonthSale,
							'lastYearSameMonthSale'=>$nestleLastYearSameMonthSale,
							'lastYearSameDateSale'=>$nestleLastYearSameDateSale,
							'monthGrowth'=>$monthGrowth,
							'yearGrowth'=>$yearGrowth
						);
						array_push($compSaleArr,$dataItems);
					}
				}
			}

			//comp Sale
			$data['compSaleArr']=$compSaleArr;
		
			//current date
			$currentDate=date('Y-m-d');
			//previous 3 days date
			$customDate =date('Y-m-d',strtotime('-'.$dayCount.' day', strtotime($currentDate)));

			$uaccountedCount=0;
			
			$compOutstandingData=array();
			if(!empty($companyData)){
				foreach($companyData as $comp){
					if($data['name'] !="General"){
						$totalNestleUnaccounted=0;
						$totalNestlePendingSupply=0;
						$totalNestleSigned=0;
						$totalNestleCheque=0;
						$totalNestleChequeBanked=0;
						$totalNestleOutstanding=0;
						$unNestle=0;
						$nestleUnBills=$this->BillTransactionModel->getUnAllBills('bills',$comp['name'],$customDate);
						if(!empty($nestleUnBills)){
							foreach($nestleUnBills as $bill){
								if(($bill['isAllocated']!=1) && ($bill['billType']=="") && ($bill['netAmount'] == $bill['pendingAmt'])){
									$unNestle=$unNestle+$bill['pendingAmt'];
								}
								
							}
						}
						
						$nestleBills=$this->BillTransactionModel->getAllBills('bills',$comp['name'],$customDate);
						if(!empty($nestleBills)){
							foreach($nestleBills as $bill){
								if(($bill['isAllocated']!=1) && ($bill['billType']=="") && ($bill['netAmount'] == $bill['pendingAmt'])){
									$totalNestleUnaccounted=$totalNestleUnaccounted+$bill['pendingAmt'];
									$uaccountedCount++;
								}

								if(($bill['isAllocated']==1) && ($bill['billType']=="" || $bill['billType']=="allocatedbillCurrent" || $bill['billType']=="allocatedbillPass")){
									$totalNestlePendingSupply=$totalNestlePendingSupply+$bill['pendingAmt']+$bill['fsCashAmt']+$bill['fsSrAmt']+$bill['fsNeftAmt']+$bill['fsChequeAmt'];
								}

								if(($bill['isAllocated']!=1 || $bill['isAllocated']==1) && (($bill['netAmount'] != $bill['pendingAmt'])) || ($bill['billType'] =="adHocDeliveryBill") ){
									
									$totalNestleSigned=$totalNestleSigned+$bill['pendingAmt']+$bill['fsCashAmt']+$bill['fsSrAmt']+$bill['fsNeftAmt']+$bill['fsChequeAmt'];
									
								}
								$totalNestleOutstanding=$totalNestleOutstanding+$bill['pendingAmt']+$bill['fsCashAmt']+$bill['fsSrAmt']+$bill['fsNeftAmt']+$bill['fsChequeAmt'];;
							}
						}

						$nestleNewChequeBillDetails=$this->BillTransactionModel->getStatusAllBillDetails('billpayments',$comp['name']);
						if(!empty($nestleNewChequeBillDetails)){
							foreach($nestleNewChequeBillDetails as $bill){
								$totalNestleCheque=$totalNestleCheque+$bill['chAmount'];
							}
						}

						$nestleBankedChequeBillDetails=$this->BillTransactionModel->getAllBillDetails('billpayments',$comp['name'],'Banked');
						if(!empty($nestleBankedChequeBillDetails)){
							foreach($nestleBankedChequeBillDetails as $bill){
								$totalNestleChequeBanked=$totalNestleChequeBanked+$bill['chAmount'];
							}
						}
						$dataArr=array(
							'compName'=>$comp['name'],
							'uaccountedCount'=>$uaccountedCount,
							'totalUnaccounted'=>$totalNestleUnaccounted,
							'totalPendingSupply'=>$totalNestlePendingSupply+$unNestle,
							'totalSigned'=>$totalNestleSigned,
							'totalOutstanding'=>$totalNestleOutstanding,
							'totalCheque'=>$totalNestleCheque,
							'totalChequeBanked'=>$totalNestleChequeBanked,
						);
						
						array_push($compOutstandingData,$dataArr);
					}
				}
			}
			$data['compOutstandingData']=$compOutstandingData;
			$data['uaccountedCount']=$uaccountedCount;

			//lost Bills
			$lostBills=0;
			$lostBillsCount=0;
			$nestleBills=$this->BillTransactionModel->loadLostBillsWithComp('bills',$customDate);
			if(!empty($nestleBills)){
				foreach($nestleBills as $bill){
					$lostBills=$lostBills+$bill['pendingAmt'];
					$lostBillsCount++;
				}
			}
			$data['lostBills']=$lostBills;
			$data['lostBillsCount']=$lostBillsCount;

			//lost Cheque
			$lostCheques=0;
			$lostChequesCount=0;
			$nestleBills=$this->BillTransactionModel->lostChequeWithComp('bills',$customDate);
			if(!empty($nestleBills)){
				foreach($nestleBills as $bill){
					$lostCheques=$lostCheques+$bill['pendingAmt'];
					$lostChequesCount++;
				}
			}
			$data['lostCheques']=$lostCheques;
			$data['lostChequesCount']=$lostChequesCount;


			//lost NEFT
			$lostNeft=0;
			$lostNeftCount=0;
			$nestleBills=$this->BillTransactionModel->lostNeftWithComp('bills',$customDate);
			if(!empty($nestleBills)){
				foreach($nestleBills as $bill){
					$lostNeft=$lostNeft+$bill['pendingAmt'];
					$lostNeftCount++;
				}
			}
			$data['lostNeft']=$lostNeft;
			$data['lostNeftCount']=$lostNeftCount;

			//Resend Bills
			$resendBills=0;
			$resendBillsCount=0;
			$nestleBills=$this->BillTransactionModel->loadResendBillsWithComp('bills','Nestle',$customDate);
			if(!empty($nestleBills)){
				foreach($nestleBills as $bill){
					$resendBills=$resendBills+$bill['pendingAmt'];
					$resendBillsCount++;
				}
			}
			$data['resendBills']=$resendBills;
			$data['resendBillsCount']=$resendBillsCount;

			$pendingAllocationCount=0;
			$pendingAllocationTotal=0;
			$pendingAllocations=$this->BillTransactionModel->getPendingAllocations('allocations',$customDate);
			if(!empty($pendingAllocations)){
				foreach($pendingAllocations as $itm){
					$pendingAllocationCount++;
					$pendingAllocationTotal=$pendingAllocationTotal+$itm['allocationTotalAmount'];
				}
			}

			$pendingAllocations=$this->BillTransactionModel->getPendingOfficeAllocations('allocations_officeadjustment',$customDate);
			$pendingAllocationCount=$pendingAllocationCount+count($pendingAllocations);
			
			$pendingAllocations=$this->BillTransactionModel->getPendingAllocationsAmount('allocations_officeadjustment',$customDate);
			if(!empty($pendingAllocations)){
				foreach($pendingAllocations as $itm){
					$pendingAllocationTotal=$pendingAllocationTotal+$itm['pendingAmt'];
				}
			}

			$data['pendingAllocationCount']=$pendingAllocationCount;
			$data['pendingAllocationTotal']=$pendingAllocationTotal;

			$this->load->view('dashboard/DashbordView1',$data);
		} 
	}


}