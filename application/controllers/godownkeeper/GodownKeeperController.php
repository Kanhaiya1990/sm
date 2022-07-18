<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GodownKeeperController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('GodownKeeperModel');
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

    public function db_backup()
	{
	    $this->load->helper('url');
	    $this->load->helper('file');
	    $this->load->helper('download');
	    $this->load->library('zip');
	    $this->load->dbutil();
	    $db_format=array('format'=>'zip','filename'=>'my_db_backup.sql');
	    $backup=$this->dbutil->backup($db_format);
	    $dbname='backup-on-'.date('Y-m-d H-i').'.zip';
	    $save='assets/db_backup/'.$dbname;
	    write_file($save,$backup);
	    force_download($dbname,$backup);
	}

    public function OpenAllocation(){
		//For Allocations
		$data['allocations']=$this->GodownKeeperModel->getAllocationDetails('allocations');
    	$this->load->view('openAllocationForAllView',$data);
    }

    public function CompleteAllocation($id){
		$compName=$this->input->post("cmpName");
		$data['alID']=$id;
		$data['employee']=$this->GodownKeeperModel->getdataEmployee('employee');
		$data['company']=$this->GodownKeeperModel->getNames('company');
		$data['routeNames']=$this->GodownKeeperModel->getRouteNames();
		$data['employeeNames']=$this->GodownKeeperModel->getEmployeeNames();
		
		if(!empty($compName)){
			$data['billNos']=$this->GodownKeeperModel->getBillNosByCompany($compName);
			$data['pastBillNos']=$this->GodownKeeperModel->getPastBillsByComp($compName);
			$data['bounceReturnCheques']=$this->GodownKeeperModel->bouncedReturnCheques('billpayments',$compName);
			$data['deliverySlip']=$this->GodownKeeperModel->deliverySlipBillNo($compName);
		}else{
			$compName='Nestle';
			$data['cmpName']='Nestle';
			$data['billNos']=$this->GodownKeeperModel->getBillNosByCompany($compName);
			$data['pastBillNos']=$this->GodownKeeperModel->getPastBillsByComp($compName);
			$data['bounceReturnCheques']=$this->GodownKeeperModel->bouncedReturnCheques('billpayments',$compName);
			$data['deliverySlip']=$this->GodownKeeperModel->deliverySlipBillNo($compName);
		}

		$data["current"]=array();
		$data["ds"]=array();
		$data["pass"]=array();
		$data["slip"]=array();
		$data['bounced']=array();
		$data['bills']=$this->GodownKeeperModel->getAllocatedBills('bills',$id);
		$data['allocations']=$this->GodownKeeperModel->load('allocations',$id);
		
		foreach ($data['bills'] as $items) {
			if($items['billType']=='allocatedbillCurrent'){
				$data['current']=$this->GodownKeeperModel->getAllocatedBillsByType('bills',$id,$items['billType'],'1');
			}else if(($items['billType']==='allocatedbillPass') || ($items['billType']==='adHocDeliveryBill') || ($items['billType']==='officeAdjustmentBill')){
				$data['pass']=$this->GodownKeeperModel->getAllocatedPastBillsByType('bills',$id);
			}else if($items['billType']=='allocatedbillDS'){
				$data['slip']=$this->GodownKeeperModel->getAllocatedBillsByType('bills',$id,$items['billType'],'1');
			}else if($items['billType']=='allocatedbillBounce'){
				$ar=$this->GodownKeeperModel->loadPayBills('allocationsbills',$id);
				
				
				for($i=0;$i<count($ar);$i++){
					$a[]=$this->GodownKeeperModel->getChequeBillsByIDs('billpayments',$ar[$i]['billId']);
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
			$response2=array();
			foreach($data['slip'] as $row){
				$response2[] = $result;
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
			$response3=array();
			foreach($data['bounced'] as $row){
				$response3[] = $result;
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
		$this->load->view('/Godownkeeper/EditAllocationView',$data);
	}

	public function confirmOtherAdjustment(){
		$paidAmount=$this->input->post('paidAmount');
		$billPaymentId=$this->input->post('billPaymentId');
		$billId=$this->input->post('billId');
		$allocationId=$this->input->post('allocationId');

		$billPayment=$this->GodownKeeperModel->load('billpayments',$billPaymentId);
		$bills=$this->GodownKeeperModel->load('bills',$billId);
		if(!empty($billPayment) && !empty($bills)){
			$originalPaidAmt=$billPayment[0]['paidAmount'];

			if($paidAmount == 0){
				$newPending=$bills[0]['pendingAmt']+$originalPaidAmt;
				$newStatus=$bills[0]['fsbillStatus'];

				$status=str_replace('Other Adjustment', '', $newStatus);
				$status=trim($status,',');
				//update bill data
				$updateBill=array(
					'pendingAmt'=>$newPending,
					'fsOtherAdjAmt'=>0,
					'fsbillStatus'=>$status
				);
				$this->GodownKeeperModel->update('bills',$updateBill,$billId);

				//update bill details data
				$updateBillPayment=array(
					'paidAmount'=>0,
					'balanceAmount'=>$newPending,
					'otherAdjustmentApproval'=>1
				);
				$this->GodownKeeperModel->update('billpayments',$updateBillPayment,$billPaymentId);

				//update bill history data
				$updateBillHistory=array(
					'transactionAmount'=>0
				);
				$this->GodownKeeperModel->updateHistory('bill_transaction_history',$updateBillHistory,$billId,$allocationId,'Other Adjustment');

			}else if($originalPaidAmt == $paidAmount){
				$newOtherAdjAmt=$bills[0]['otherAdjustment']+$paidAmount;

				//update bill data
				$updateBill=array(
					'otherAdjustment'=>$newOtherAdjAmt
				);
				$this->GodownKeeperModel->update('bills',$updateBill,$billId);

				//update bill details data
				$updateBillPayment=array(
					'otherAdjustmentApproval'=>1
				);
				$this->GodownKeeperModel->update('billpayments',$updateBillPayment,$billPaymentId);
			}else if($originalPaidAmt > $paidAmount){
				$newPending=$bills[0]['pendingAmt']+($originalPaidAmt-$paidAmount);
				$newOtherAdjAmt=$bills[0]['otherAdjustment']+$paidAmount;
				$newFsOtherAdjAmt=$paidAmount;

				//update bill data
				$updateBill=array(
					'pendingAmt'=>$newPending,
					'otherAdjustment'=>$newOtherAdjAmt,
					'fsOtherAdjAmt'=>$newFsOtherAdjAmt
				);
				$this->GodownKeeperModel->update('bills',$updateBill,$billId);

				//update bill details data
				$updateBillPayment=array(
					'paidAmount'=>$paidAmount,
					'balanceAmount'=>$newPending,
					'otherAdjustmentApproval'=>1
				);
				$this->GodownKeeperModel->update('billpayments',$updateBillPayment,$billPaymentId);

				//update bill history data
				$updateBillHistory=array(
					'transactionAmount'=>$newFsOtherAdjAmt
				);
				$this->GodownKeeperModel->updateHistory('bill_transaction_history',$updateBillHistory,$billId,$allocationId,'Other Adjustment');
			}
		}
		redirect('godownkeeper/GodownKeeperController/GodownkeeperBills/'.$allocationId);
	}

	public function GodownkeeperBills($id){
		$data['allocationID']=$id;

		$data['BillInfo']=$this->GodownKeeperModel->getAllocatedBillInfo('allocations',$id);
		$data['sr']=$this->GodownKeeperModel->getAllocatedBillsType('billsdetails',$id);

		$data['fsr']=$this->GodownKeeperModel->getAllocatedBillsFSR('billsdetails',$id);
		$data['newFsr']=$this->GodownKeeperModel->getNewBillAllocatedBillsFSR('bills',$id);

		$otherCheck=$this->GodownKeeperModel->load('tbl_settings',3);
			
		$data['otherAdj']=array();
		if(!empty($otherCheck)){
			if($otherCheck[0]['propertyValue']=="no"){
				$data['otherAdj']=$this->GodownKeeperModel->getOtherAdjAllocatedBills('allocations',$id);
			}
		}
		
		// print_r($data['otherAdj']);exit;
		// if(!empty($data['otherAdj'])){

		// }else{

		// }

		// getNewBillAllocatedBillsFSR
		// print_r($data['newFsr']);exit;
// 
		$data['chkfsr']=$this->GodownKeeperModel->chkAllocatedBillsFSR('billsdetails',$id);
		$data['srData']=array();
		$no=0;

		$res=array();
		if(!empty($data['fsr'])){
			for($i=0;$i<count($data['fsr']);$i++){
				
				for($j=0;$j<count($data['sr']);$j++){
					$no++;
					if(!in_array($data['sr'][$j]['billNo'],$data['fsr'][$i])){
						$res[]=$data['sr'][$j];
						array_push($data['srData'], $res);
					}
				}
			}
		}else{
			$data['srData']=$data['sr'];
		}
		
		if(!empty($res)){
			// $data['srData']=$res
			$data['srData']=array_unique($res, SORT_REGULAR);
		}

		// echo count($data['srData']);
		// print_r($data['sr']);exit;
		// print_r($data);exit;

		if(!empty($data['otherAdj']) || !empty($data['sr']) || !empty($data['fsr']) || !empty($data['srData']) || !empty($data['newFsr'])){
			$this->load->view('Godownkeeper/GodownkeeperView',$data);
		}else{
			$updateBillDetails=array('gkStatus' => 1);
			$this->GodownKeeperModel->update('allocations',$updateBillDetails,$id);

			$signedBills=$this->GodownKeeperModel->getSignedBills('bills',$id);
			if(empty($signedBills)){
				$data=array('sr_bill_Status'=>1);
				$this->GodownKeeperModel->update('allocations',$data,$id);
			}
			$data['allocations']=$this->GodownKeeperModel->getAllocationDetails('allocations');
    		redirect('AllocationByManagerController/openAllocations');
		}
	}


	public function saveGodownkeeperAllocation(){
		$id=trim($this->input->post('allocatedId'));
		$data['allocationID']=$id;
		// echo $id;
		$data['BillInfo']=$this->GodownKeeperModel->getAllocatedBillInfo('allocations',$id);
		$data['sr']=$this->GodownKeeperModel->getAllocatedBillsType('billsdetails',$id);
		$data['fsr']=$this->GodownKeeperModel->getAllocatedBillsFSR('billsdetails',$id);

		$data['chksr']=$this->GodownKeeperModel->chkAllocatedBillsType('billsdetails',$id);
		$data['chkfsr']=$this->GodownKeeperModel->chkAllocatedBillsFSR('billsdetails',$id);

		$data['srData']=array();
		$no=0;

		if(!empty($data['fsr'])){
			for($i=0;$i<count($data['fsr']);$i++){
				$res=array();
				for($j=0;$j<count($data['sr']);$j++){
					$no++;
					if(!in_array($data['sr'][$j]['billNo'],$data['fsr'][$i])){
						$res[]=$data['sr'][$j];
						array_push($data['srData'], $res);
					}
				}
			}
		}else{
			$data['srData']=$data['sr'];
		}
		
		if(!empty($res)){
			$data['srData']=$res;
		}

		if(!empty($data['chksr']) || !empty($data['chkfsr'])){
			redirect('godownkeeper/GodownKeeperController/GodownkeeperBills/'.$id);
			// $this->load->view('Godownkeeper/GodownkeeperView',$data);
		}else{
			$updateBillDetails=array('gkStatus' => 1);
			$this->GodownKeeperModel->update('allocations',$updateBillDetails,$id);
			$data['allocations']=$this->GodownKeeperModel->getAllocationDetails('allocations');
    		redirect('AllocationByManagerController/openAllocations');
		}

		// $allocationId=trim($this->input->post('allocatedId'));
		// echo $allocationId;exit;

		// $updateBillDetails=array('gkStatus' => 1);
		// $this->GodownKeeperModel->update('allocations',$updateBillDetails,$allocationId);
		// $data['allocations']=$this->GodownKeeperModel->getAllocationDetails('allocations');
  //   	$this->load->view('Godownkeeper/OpenAllocationsView',$data);
	}

	public function BillDetails(){
       	$id=trim($this->input->post('id'));
		$allocationId=trim($this->input->post('allocationId'));
        $bills=$this->GodownKeeperModel->billsByCode('billsdetails',$id);

        ?>

        <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                             <div class="header">
                            <center><h2> Bill Details</h2></center><br />
                            <h2 style="color: red;">
                                Bill No     :   <?php 
                                                    if(!empty($bills)){
                                                       echo $bills[0]['BillNo'];
                                                    }
                                                ?> &nbsp &nbsp&nbsp &nbsp &nbsp &nbsp
                                Retailer Name:  <?php 
                                                    if(!empty($bills)){
                                                       echo $bills[0]['RetailerName'];
                                                    }
                                                ?> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
                            </h2>
                        </div>
                        <div class="body">
                        	<div>
                                    <button type="button" onclick="updateCurrentSrValue('<?php echo $bills[0]['billId']; ?>','<?php echo $allocationId; ?>')" class="btn btn-primary m-t-15 waves-effect">
                                        <i class="material-icons">save</i><span class="icon-name">Accept All</span>
                                    </button>
                                
                                <button data-dismiss="modal" type="button" class="btn btn-danger m-t-15 waves-effect">
                                            <i class="material-icons">cancel</i><span class="icon-name">cancel</span>
                                        </button>
                                </div>
                            <div class="table-responsive">
                                 
                                    <div id="res"></div>
                                <table id="SrTable" class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                             <th>S. No.</th>
                                            <th style="display: none;"></th>
                                            <th>Bill No.</th>
                                            <th>Retailer</th>
                                            <th>Item</th>
                                            <th>MRP</th>
                                            <th>Billed Qty</th>
                                            <th>SR</th>
                                            <th>SR Received</th>
                                             <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                         <tr>
                                            <th>S. No.</th>
                                            <th style="display: none;"></th>
                                            <th>Bill No.</th>
                                            <th>Retailer</th>
                                            <th>Item</th>
                                            <th>MRP</th>
                                            <th>Billed Qty</th>
                                            <th>SR</th>
                                            <th>SR Received</th>
                                             <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        $no=0;
                                        if(!empty($bills)){
                                        foreach ($bills as $data) 
                                          { 
                                             $no++; 
                                             $srQty=$this->GodownKeeperModel->getTotalSrQtyById($data['id']);
                                    ?>
                                             <tr>
                                                <td><?php echo $no;?></td>
                                               <td id="billId" style="display: none;"><?php echo $data['id'];?></td>
                                                <td id="billNo">
                                                    <?php echo $data['BillNo'];?>
                                                </td>
                                               <td><?php 
                                                $retailerName=substr($data['RetailerName'], 0, 30);
                                                echo $retailerName;?></td>
                                               
                                                <td><?php echo $data['productName'];?></td>
                                                <td align="right"><?php echo $data['mrp'];?></td>
                                                <td align="right"><?php echo number_format($data['qty']);?></td>
                                                <td align="right"><?php echo number_format($data['fsReturnQty']);?></td>
                                                <td id="srQty">
                                                	<?php if($data['gkStatus']==1){ ?>
                                                		<?php echo (int)$data['fsReturnQty']; ?>
                                                	<?php }else{ ?>	
                                                		<input style="width: 50%"  type="text" onblur="checkFsrQtyPerItem(this,'<?php echo $no; ?>','<?php echo $data['qty']; ?>','<?php echo $data['fsReturnQty']; ?>')" onfocus="this.select();" name="fsReturnQty[]" value="<?php echo (int)$data['fsReturnQty']; ?>">
                                                    <p style="color:red" id="data_errr<?php echo $no; ?>"></p>

                                                    <input style="width: 50%" type="hidden" name="checkReturnQty[]" value="<?php echo (int)$data['fsReturnQty']; ?>">
                                                	<?php } ?>

                                                    
                                                </td>
                                                <td>
                                                	<?php if($data['gkStatus']==1){ 
                                                		echo '&#10004;';
                                                	 }else{ ?>	
	                                                    <button id="btn_errr<?php echo $no; ?>" onclick="updateSRqtyForFsr(this);" class="btn-primary waves-effect">
	                                                        
	                                                        <span class="icon-name">
	                                                        OK
	                                                        </span>
	                                                    </button>
                                                <?php } ?>
                                                </td>
                                           </tr>
                                     <?php
                                            }
                                        }?>
                                    </tbody>
                                </table>
                                 
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php 
     
        // if(!empty($data['bills'])){
        // 	$this->load->view('Godownkeeper/BillDetailsView',$data);
        // }else{
        // 	echo "<script type='text/javascript'> parent.$.fn.colorbox.close();window.parent.location.reload(true);
        // 	   </script>";
        	
        // }
    }
	
	public function loadBills($id){
        // $name=urldecode($name);
        $data['bills']=$this->GodownKeeperModel->retailerBillsByCode('billsdetails',$id);
        echo json_encode( $data['bills']);
    }

    public function updateSr(){
    	$allocationId=trim($this->input->post('allocationId'));

    	$billNo=trim($this->input->post('billNo'));
    	$billDetailId=trim($this->input->post('billId'));
    	$srQty=trim($this->input->post('srQty'));
    	$fsSrAmt=trim($this->input->post('fsSrAmt'));

		$billsInfo=$this->GodownKeeperModel->getBillInfo('bills',$billNo);
    	$billDetailInfo=$this->GodownKeeperModel->load('billsdetails',$billDetailId);
    	$prodCode="";
        $prodName="";
        $prodMrp="";
        if(!empty($billDetailInfo)){
            $prodCode=$billDetailInfo[0]['productCode'];
			$prodName=$billDetailInfo[0]['productName'];
            $prodMrp=$billDetailInfo[0]['mrp'];
        }

    	$currBillId=$billsInfo[0]['id'];
		$creditAdjustment=$billsInfo[0]['creditAdjustment'];
		$pendingAmtNow=$billsInfo[0]['pendingAmt'];
		$billNetAmount=$billsInfo[0]['netAmount'];

		$finalQtySrTotal=0;
		if($creditAdjustment>0){
			$totalTaxableValue=0;
            $totalTaxAmount=0;
            $totalCessAmount=0;

			$pendingAmt=$billsInfo[0]['pendingAmt'];
			$sumOfCals=$billsInfo[0]['fsCashAmt']+$billsInfo[0]['fsSrAmt']+$billsInfo[0]['fsNeftAmt']+$billsInfo[0]['fsChequeAmt'];
			$totalPending=$pendingAmt-$sumOfCals;//total pending till current allocation.

			$eachQtyRate=$billDetailInfo[0]['netAmount']/$billDetailInfo[0]['qty'];
			$returnAmt=($srQty*$eachQtyRate);

			$finalQtySrTotal=($srQty*$eachQtyRate);

			$fsReturnQty=$billDetailInfo[0]['fsReturnQty'];
			
			$billSRAmt=$billsInfo[0]['fsSrAmt'];
			if($srQty==$fsReturnQty){
				$shortQty=$fsReturnQty-$srQty;

				$shortReturnAmt=($shortQty*$eachQtyRate);
				$shortReturnAmt= ($shortReturnAmt);
				$updatedPendingAmt=$pendingAmt+$shortReturnAmt;//updated pending amt

				$updatedSrAmt=$billSRAmt-$shortReturnAmt;//updated SR amt
				$updatedSrAmt=($updatedSrAmt);

				if($updatedSrAmt>$pendingAmtNow){
                    $updatedSrAmt=floor($updatedSrAmt);
                }else{
                    $updatedSrAmt=round($updatedSrAmt);
                }

				$data['billsdetails']=$this->GodownKeeperModel->load('billsdetails', $billDetailId);
				//cess amount
				$cessAmount=$data['billsdetails'][0]['cessAmount']/$data['billsdetails'][0]['qty'];
				$cessAmount=round($srQty*$cessAmount,2);

				//tax amount
				$taxAmount=$data['billsdetails'][0]['taxAmount']/$data['billsdetails'][0]['qty'];
				$taxAmount=round($srQty*$taxAmount,2);

				//taxable amount
				$taxableValue=$data['billsdetails'][0]['taxableValue']/$data['billsdetails'][0]['qty'];
				$taxableValue=round($srQty*$taxableValue,2);

				$totalTaxableValue=$totalTaxableValue+$taxableValue;
				$totalTaxAmount=$totalTaxAmount+$taxAmount;
				$totalCessAmount=$totalCessAmount+$cessAmount;

				$billDetailUpdate=array('fsReturnQty' => 0,'gkReturnQty' => $srQty,'fsReturnAmt' => $returnAmt,'gkStatus' => 1);
				
				$this->GodownKeeperModel->update('billsdetails',$billDetailUpdate,$billDetailId);

				$pendBillInfo=$this->GodownKeeperModel->load('bills',$currBillId);
				$currentPending=$pendBillInfo[0]['pendingAmt'];
				$currentNetAmount=$pendBillInfo[0]['netAmount'];
				$currentSrAmt=$pendBillInfo[0]['SRAmt'];

				$totalPendingAmount=($currentPending-$returnAmt);
				if($totalPendingAmount>$pendingAmtNow){
                    $totalPendingAmount=floor($totalPendingAmount);
                }else{
                    $totalPendingAmount=round($totalPendingAmount);
                }

				$totalSrAmount=($currentSrAmt+$returnAmt);
				if($totalSrAmount>$pendingAmtNow){
                    $totalSrAmount=floor($totalSrAmount);
                }else{
                    $totalSrAmount=round($totalSrAmount);
                }

				if($totalPendingAmount>0){
					$srUpdate=array('pendingAmt' => $totalPendingAmount,'SRAmt'=>$totalSrAmount,'fsSrAmt'=>$updatedSrAmt,'fsbillStatus' => 'SR');
					$this->GodownKeeperModel->update('bills',$srUpdate,$currBillId);//update latest sr amount

					$history=array(
						'billId'=>$currBillId,
						'allocationId'=>$allocationId,
						'transactionAmount' =>round($finalQtySrTotal),
						'transactionStatus' =>'SR',
						'transactionMode' =>'dr',
						'transactionDate'=>date('Y-m-d H:i:sa'),
						'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
						'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
					);
					$this->GodownKeeperModel->insert('bill_transaction_history',$history);
	
	
					$billPayment=array(
						'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
						'billId'=>$currBillId,
						'allocationId'=>$allocationId,
						'date'=>date('Y-m-d H:i:sa'),
						'paidAmount'=>round($finalQtySrTotal),
						'billAmount'=>$billNetAmount,
						'balanceAmount'=>$totalPendingAmount,
						'paymentMode'=>'SR',
						'taxableValue'=>$totalTaxableValue,
						'taxAmount'=>$totalTaxAmount,
					    'cessAmount'=>$totalCessAmount,
						'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
					);
					$this->GodownKeeperModel->insert('billpayments',$billPayment);//insert billpayment data
				}else{
					$credRenival=$creditAdjustment-$currentPending;
					$srUpdate=array('pendingAmt' =>0,'SRAmt'=>$totalSrAmount,'creditNoteRenewal'=>$credRenival,'fsSrAmt'=>$currentPending,'fsbillStatus' => 'SR');
					$this->GodownKeeperModel->update('bills',$srUpdate,$currBillId);//update latest sr amount

					$history=array(
						'billId'=>$currBillId,
						'allocationId'=>$allocationId,
						'transactionAmount' =>$currentPending,
						'transactionStatus' =>'SR',
						'transactionMode' =>'dr',
						'transactionDate'=>date('Y-m-d H:i:sa'),
						'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
						'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
					);
					$this->GodownKeeperModel->insert('bill_transaction_history',$history);
	
	
					$billPayment=array(
						'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
						'billId'=>$currBillId,
						'allocationId'=>$allocationId,
						'date'=>date('Y-m-d H:i:sa'),
						'paidAmount'=>$currentPending,
						'billAmount'=>$billNetAmount,
						'balanceAmount'=>0,
						'paymentMode'=>'SR',
						'taxableValue'=>$totalTaxableValue,
						'taxAmount'=>$totalTaxAmount,
					    'cessAmount'=>$totalCessAmount,
						'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
					);
					$this->GodownKeeperModel->insert('billpayments',$billPayment);//insert billpayment data
				}
				
				$checkSrDetail=$this->GodownKeeperModel->loadSrDetails('allocation_sr_details',$currBillId,$allocationId,$billDetailId);
				if(!empty($checkSrDetail)){
					$srData = array(
						'allocationId' => $allocationId,
						'billId' =>  $currBillId,
						'billItemId'=>$billDetailId,
						'quantity'=>$srQty,
						'createdAt'=>date('Y-m-d H:i:sa')
					);
					$this->GodownKeeperModel->updateSrDetail('allocation_sr_details',$srData,$allocationId,$currBillId,$billDetailId);

					$isDeliverySlip=$this->GodownKeeperModel->load('bills', $currBillId);
					if(!empty($isDeliverySlip)){
						if($isDeliverySlip[0]['isDeliverySlipBill']==1){
							//for product return with code for delivery slip products
							$prodDet=$this->GodownKeeperModel->getDetailByCodeWithDetails('products',$prodName,$prodCode,(int)$prodMrp);
							if(!empty($prodDet)){
								$insertData=array(
									'billingId'=>$currBillId,
									'operationStatus'=>'add',
									'quantityInPcs'=>$srQty,
									'productId'=>$prodDet[0]['id'],
									'productName'=>$prodDet[0]['name'],
									'productCode'=>$prodDet[0]['productCode'],
									'quantity'=>$srQty,
									'quantityUnit'=>'pcs',
									'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
									'createdAt'=>date('Y-m-d H:i:sa')
								);
								// print_r($insertData);exit;
								$this->GodownKeeperModel->insert('deliveryslip_pending_for_billing',$insertData);
							}
						}
					}

				}else{
					$srData = array(
						'allocationId' => $allocationId,
						'billId' =>  $currBillId,
						'billItemId'=>$billDetailId,
						'quantity'=>$srQty,
						'createdAt'=>date('Y-m-d H:i:sa')
					);
					$this->GodownKeeperModel->insert('allocation_sr_details',$srData);

					$isDeliverySlip=$this->GodownKeeperModel->load('bills', $currBillId);
					if(!empty($isDeliverySlip)){
						if($isDeliverySlip[0]['isDeliverySlipBill']==1){
							//for product return with code for delivery slip products
							$prodDet=$this->GodownKeeperModel->getDetailByCodeWithDetails('products',$prodName,$prodCode,(int)$prodMrp);
							if(!empty($prodDet)){
								$insertData=array(
									'billingId'=>$currBillId,
									'operationStatus'=>'add',
									'quantityInPcs'=>$srQty,
									'productId'=>$prodDet[0]['id'],
									'productName'=>$prodDet[0]['name'],
									'productCode'=>$prodDet[0]['productCode'],
									'quantity'=>$srQty,
									'quantityUnit'=>'pcs',
									'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
									'createdAt'=>date('Y-m-d H:i:sa')
								);
								// print_r($insertData);exit;
								$this->GodownKeeperModel->insert('deliveryslip_pending_for_billing',$insertData);
							}
						}
					}
				}
			}else if($srQty<$fsReturnQty){
				
				$shortQty=$fsReturnQty-$srQty;
				
				$shortReturnAmt=($shortQty*$eachQtyRate);
				$finalQtySrTotal=($shortQty*$eachQtyRate);

				$shortReturnAmt= ($shortReturnAmt);
				$updatedPendingAmt=$pendingAmt+$shortReturnAmt;//updated pending amt

				$updatedSrAmt=$billSRAmt-$shortReturnAmt;//updated SR amt

				$updatedSrAmt=($updatedSrAmt);
				if($updatedSrAmt>$pendingAmtNow){
                    $updatedSrAmt=floor($updatedSrAmt);
                }else{
                    $updatedSrAmt=round($updatedSrAmt);
                }

				$data['billsdetails']=$this->GodownKeeperModel->load('billsdetails', $billDetailId);
				//cess amount
				$cessAmount=$data['billsdetails'][0]['cessAmount']/$data['billsdetails'][0]['qty'];
				$cessAmount=round($srQty*$cessAmount,2);

				//tax amount
				$taxAmount=$data['billsdetails'][0]['taxAmount']/$data['billsdetails'][0]['qty'];
				$taxAmount=round($srQty*$taxAmount,2);

				//taxable amount
				$taxableValue=$data['billsdetails'][0]['taxableValue']/$data['billsdetails'][0]['qty'];
				$taxableValue=round($srQty*$taxableValue,2);

				$totalTaxableValue=$totalTaxableValue+$taxableValue;
				$totalTaxAmount=$totalTaxAmount+$taxAmount;
				$totalCessAmount=$totalCessAmount+$cessAmount;

				if($pendingAmt>$sumOfCals){
					
					$billDetailUpdate=array('fsReturnQty' => 0,'gkReturnQty' => $srQty,'fsReturnAmt' => $returnAmt,'gkStatus' => 1);

					$this->GodownKeeperModel->update('billsdetails',$billDetailUpdate,$billDetailId);

					$pendBillInfo=$this->GodownKeeperModel->load('bills',$currBillId);
					$currentPending=$pendBillInfo[0]['pendingAmt'];
					$currentSrAmt=$pendBillInfo[0]['SRAmt'];

					$totalPendingAmount=($currentPending-$returnAmt);
					if($totalPendingAmount>$pendingAmtNow){
	                    $totalPendingAmount=floor($totalPendingAmount);
	                }else{
	                    $totalPendingAmount=round($totalPendingAmount);
	                }

					$totalSrAmount=($currentSrAmt+$returnAmt);
					if($totalSrAmount>$pendingAmtNow){
	                    $totalSrAmount=floor($totalSrAmount);
	                }else{
	                    $totalSrAmount=round($totalSrAmount);
	                }

					
					$srUpdate=array('pendingAmt' => $totalPendingAmount,'SRAmt'=>$totalSrAmount,'fsSrAmt'=>$updatedSrAmt);
					$this->GodownKeeperModel->update('bills',$srUpdate,$currBillId);//update latest sr amount

					$history=array(
						'billId'=>$currBillId,
						'allocationId'=>$allocationId,
						'transactionAmount' =>round($finalQtySrTotal),
						'transactionStatus' =>'SR',
						'transactionMode' =>'dr',
						'transactionDate'=>date('Y-m-d H:i:sa'),
						'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
						'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
					);
					$this->GodownKeeperModel->insert('bill_transaction_history',$history);
	
	
					$billPayment=array(
						'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
						'billId'=>$currBillId,
						'allocationId'=>$allocationId,
						'date'=>date('Y-m-d H:i:sa'),
						'paidAmount'=>round($finalQtySrTotal),
						'billAmount'=>$billNetAmount,
						'balanceAmount'=>$totalPendingAmount,
						'paymentMode'=>'SR',
						'taxableValue'=>$totalTaxableValue,
						'taxAmount'=>$totalTaxAmount,
					    'cessAmount'=>$totalCessAmount,
						'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
					);
					$this->GodownKeeperModel->insert('billpayments',$billPayment);//insert billpayment data

					$updatedBillInfo=$this->GodownKeeperModel->load('bills',$currBillId);
					$currentSrTotal=$updatedBillInfo[0]['fsSrAmt'];

					$currentSrTotal= round($currentSrTotal);
					if($currentSrTotal<=0){
						$oldStatus=$updatedBillInfo[0]['fsbillStatus'];
						$fsstatus=str_replace('SR', '', $oldStatus);
						$status=trim($fsstatus,',');
						$data_status = array('fsbillStatus' => $status);  
						$this->GodownKeeperModel->update('bills',$data_status,$currBillId);
					}

					$checkSrDetail=$this->GodownKeeperModel->loadSrDetails('allocation_sr_details',$currBillId,$allocationId,$billDetailId);
					if(!empty($checkSrDetail)){
						$srData = array(
							'allocationId' => $allocationId,
							'billId' =>  $currBillId,
							'billItemId'=>$billDetailId,
							'quantity'=>$srQty,
							'createdAt'=>date('Y-m-d H:i:sa')
						);
						$this->GodownKeeperModel->updateSrDetail('allocation_sr_details',$srData,$allocationId,$currBillId,$billDetailId);

						$isDeliverySlip=$this->GodownKeeperModel->load('bills', $currBillId);
						if(!empty($isDeliverySlip)){
							if($isDeliverySlip[0]['isDeliverySlipBill']==1){
								//for product return with code for delivery slip products
								$prodDet=$this->GodownKeeperModel->getDetailByCodeWithDetails('products',$prodName,$prodCode,(int)$prodMrp);
								if(!empty($prodDet)){
									$insertData=array(
										'billingId'=>$currBillId,
										'operationStatus'=>'add',
										'quantityInPcs'=>$srQty,
										'productId'=>$prodDet[0]['id'],
										'productName'=>$prodDet[0]['name'],
										'productCode'=>$prodDet[0]['productCode'],
										'quantity'=>$srQty,
										'quantityUnit'=>'pcs',
										'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
										'createdAt'=>date('Y-m-d H:i:sa')
									);
									// print_r($insertData);exit;
									$this->GodownKeeperModel->insert('deliveryslip_pending_for_billing',$insertData);
								}
							}
						}
					}else{
						$srData = array(
							'allocationId' => $allocationId,
							'billId' =>  $currBillId,
							'billItemId'=>$billDetailId,
							'quantity'=>$srQty,
							'createdAt'=>date('Y-m-d H:i:sa')
						);
						$this->GodownKeeperModel->insert('allocation_sr_details',$srData);

						$isDeliverySlip=$this->GodownKeeperModel->load('bills', $currBillId);
						if(!empty($isDeliverySlip)){
							if($isDeliverySlip[0]['isDeliverySlipBill']==1){
								//for product return with code for delivery slip products
								$prodDet=$this->GodownKeeperModel->getDetailByCodeWithDetails('products',$prodName,$prodCode,(int)$prodMrp);
								if(!empty($prodDet)){
									$insertData=array(
										'billingId'=>$currBillId,
										'operationStatus'=>'add',
										'quantityInPcs'=>$srQty,
										'productId'=>$prodDet[0]['id'],
										'productName'=>$prodDet[0]['name'],
										'productCode'=>$prodDet[0]['productCode'],
										'quantity'=>$srQty,
										'quantityUnit'=>'pcs',
										'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
										'createdAt'=>date('Y-m-d H:i:sa')
									);
									// print_r($insertData);exit;
									$this->GodownKeeperModel->insert('deliveryslip_pending_for_billing',$insertData);
								}
							}
						}
					}
				}else{
					
					$allocation=$this->GodownKeeperModel->load('allocations',$allocationId);

					$empId=$allocation[0]['fieldStaffCode1'];
					$link="https://yontechsoftwares.com/share/smartdistributor/index.php/AllocationByManagerController/smsSrTrancationDetails/".$allocationId;

					$desc="Amount debited for short qty : ".$shortQty." for Bill No : ".$billNo." of Allocation No. ".$allocationId ;
					
					$debitData=array('empId'=>$empId,'allocationId'=>$allocationId,'amount' => $shortReturnAmt,'description'=>$desc,'createdAt'=>date('Y-m-d H:i:sa'));

					$descr="Amount debited for short qty : ".$shortQty." for Bill No : ".$billNo." of Allocation No. ".$allocationId.'SR accepted please check at '.$link ;

					$mobile="8446107727";
					$url="https://api.msg91.com/api/sendhttp.php?authkey=291106AG8eCyzDe5d626f5c&mobiles=".$mobile."&country=91&message=".$descr."&sender=TESTIN&route=4";

					$billDetailUpdate=array('fsReturnQty' => 0,'gkReturnQty' => $srQty,'fsReturnAmt' => $returnAmt,'gkStatus' => 1);

					$updatedSrAmt=($updatedSrAmt);
					if($updatedSrAmt>$pendingAmtNow){
	                    $updatedSrAmt=floor($updatedSrAmt);
	                }else{
	                    $updatedSrAmt=round($updatedSrAmt);
	                }
					
					$this->GodownKeeperModel->update('billsdetails',$billDetailUpdate,$billDetailId);

					$pendBillInfo=$this->GodownKeeperModel->load('bills',$currBillId);
					$currentPending=$pendBillInfo[0]['pendingAmt'];
					$currentSrAmt=$pendBillInfo[0]['SRAmt'];

					$totalPendingAmount=($currentPending-$returnAmt);
					if($totalPendingAmount>$pendingAmtNow){
	                    $totalPendingAmount=floor($totalPendingAmount);
	                }else{
	                    $totalPendingAmount=round($totalPendingAmount);
	                }

					$totalSrAmount=($currentSrAmt+$returnAmt);
					if($totalSrAmount>$pendingAmtNow){
	                    $totalSrAmount=floor($totalSrAmount);
	                }else{
	                    $totalSrAmount=round($totalSrAmount);
	                }
					
					$srUpdate=array('pendingAmt' => $totalPendingAmount,'SRAmt'=>$totalSrAmount,'fsSrAmt'=>$updatedSrAmt);
					$this->GodownKeeperModel->update('bills',$srUpdate,$currBillId);//update latest sr amount

					$history=array(
						'billId'=>$currBillId,
						'allocationId'=>$allocationId,
						'transactionAmount' =>round($finalQtySrTotal),
						'transactionStatus' =>'SR',
						'transactionMode' =>'dr',
						'transactionDate'=>date('Y-m-d H:i:sa'),
						'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
						'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
					);
					$this->GodownKeeperModel->insert('bill_transaction_history',$history);
	
	
					$billPayment=array(
						'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
						'billId'=>$currBillId,
						'allocationId'=>$allocationId,
						'date'=>date('Y-m-d H:i:sa'),
						'paidAmount'=>round($finalQtySrTotal),
						'billAmount'=>$billNetAmount,
						'balanceAmount'=>$totalPendingAmount,
						'paymentMode'=>'SR',
						'taxableValue'=>$totalTaxableValue,
						'taxAmount'=>$totalTaxAmount,
					    'cessAmount'=>$totalCessAmount,
						'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
					);
					$this->GodownKeeperModel->insert('billpayments',$billPayment);//insert billpayment data

					$updatedBillInfo=$this->GodownKeeperModel->load('bills',$currBillId);
					$currentSrTotal=$updatedBillInfo[0]['fsSrAmt'];
					$currentSrTotal= round($currentSrTotal);
					if($currentSrTotal<=0){
						$oldStatus=$updatedBillInfo[0]['fsbillStatus'];
						$fsstatus=str_replace('SR', '', $oldStatus);
						$status=trim($fsstatus,',');
						$data_status = array('fsbillStatus' => $status);  
						$this->GodownKeeperModel->update('bills',$data_status,$currBillId);
					}

					$checkSrDetail=$this->GodownKeeperModel->loadSrDetails('allocation_sr_details',$currBillId,$allocationId,$billDetailId);
					if(!empty($checkSrDetail)){
						$srData = array(
							'allocationId' => $allocationId,
							'billId' =>  $currBillId,
							'billItemId'=>$billDetailId,
							'quantity'=>$srQty,
							'createdAt'=>date('Y-m-d H:i:sa')
						);
						$this->GodownKeeperModel->updateSrDetail('allocation_sr_details',$srData,$allocationId,$currBillId,$billDetailId);

						$isDeliverySlip=$this->GodownKeeperModel->load('bills', $currBillId);
						if(!empty($isDeliverySlip)){
							if($isDeliverySlip[0]['isDeliverySlipBill']==1){
								//for product return with code for delivery slip products
								$prodDet=$this->GodownKeeperModel->getDetailByCodeWithDetails('products',$prodName,$prodCode,(int)$prodMrp);
								if(!empty($prodDet)){
									$insertData=array(
										'billingId'=>$currBillId,
										'operationStatus'=>'add',
										'quantityInPcs'=>$srQty,
										'productId'=>$prodDet[0]['id'],
										'productName'=>$prodDet[0]['name'],
										'productCode'=>$prodDet[0]['productCode'],
										'quantity'=>$srQty,
										'quantityUnit'=>'pcs',
										'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
										'createdAt'=>date('Y-m-d H:i:sa')
									);
									// print_r($insertData);exit;
									$this->GodownKeeperModel->insert('deliveryslip_pending_for_billing',$insertData);
								}
							}
						}
					}else{
						$srData = array(
							'allocationId' => $allocationId,
							'billId' =>  $currBillId,
							'billItemId'=>$billDetailId,
							'quantity'=>$srQty,
							'createdAt'=>date('Y-m-d H:i:sa')
						);
						$this->GodownKeeperModel->insert('allocation_sr_details',$srData);

						$isDeliverySlip=$this->GodownKeeperModel->load('bills', $currBillId);
						if(!empty($isDeliverySlip)){
							if($isDeliverySlip[0]['isDeliverySlipBill']==1){
								//for product return with code for delivery slip products
								$prodDet=$this->GodownKeeperModel->getDetailByCodeWithDetails('products',$prodName,$prodCode,(int)$prodMrp);
								if(!empty($prodDet)){
									$insertData=array(
										'billingId'=>$currBillId,
										'operationStatus'=>'add',
										'quantityInPcs'=>$srQty,
										'productId'=>$prodDet[0]['id'],
										'productName'=>$prodDet[0]['name'],
										'productCode'=>$prodDet[0]['productCode'],
										'quantity'=>$srQty,
										'quantityUnit'=>'pcs',
										'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
										'createdAt'=>date('Y-m-d H:i:sa')
									);
									// print_r($insertData);exit;
									$this->GodownKeeperModel->insert('deliveryslip_pending_for_billing',$insertData);
								}
							}
						}
					}
				}
			}else if($srQty>$fsReturnQty){
				$extraQty=$srQty-$fsReturnQty;
				$extraReturnAmt=($extraQty*$eachQtyRate);

				$finalQtySrTotal=($extraQty*$eachQtyRate);

				$updatedPendingAmt=$pendingAmt-$extraReturnAmt;//updated pending amt
				$updatedSrAmt=$billSRAmt+$extraReturnAmt;//updated SR amt

				$updatedSrAmt=($updatedSrAmt);
				if($updatedSrAmt>$pendingAmtNow){
                    $updatedSrAmt=floor($updatedSrAmt);
                }else{
                    $updatedSrAmt=round($updatedSrAmt);
                }

				$data['billsdetails']=$this->GodownKeeperModel->load('billsdetails', $billDetailId);
				//cess amount
				$cessAmount=$data['billsdetails'][0]['cessAmount']/$data['billsdetails'][0]['qty'];
				$cessAmount=round($srQty*$cessAmount,2);

				//tax amount
				$taxAmount=$data['billsdetails'][0]['taxAmount']/$data['billsdetails'][0]['qty'];
				$taxAmount=round($srQty*$taxAmount,2);

				//taxable amount
				$taxableValue=$data['billsdetails'][0]['taxableValue']/$data['billsdetails'][0]['qty'];
				$taxableValue=round($srQty*$taxableValue,2);

				$totalTaxableValue=$totalTaxableValue+$taxableValue;
				$totalTaxAmount=$totalTaxAmount+$taxAmount;
				$totalCessAmount=$totalCessAmount+$cessAmount;

				if($pendingAmt>$sumOfCals){
					$billDetailUpdate=array('fsReturnQty' => 0,'gkReturnQty' => $srQty,'fsReturnAmt' => $updatedSrAmt,'gkStatus' => 1);
					
					$this->GodownKeeperModel->update('billsdetails',$billDetailUpdate,$billDetailId);

					$pendBillInfo=$this->GodownKeeperModel->load('bills',$currBillId);
					$currentPending=$pendBillInfo[0]['pendingAmt'];
					$currentSrAmt=$pendBillInfo[0]['SRAmt'];

					$totalPendingAmount=($currentPending-$updatedSrAmt);
					if($totalPendingAmount>$pendingAmtNow){
	                    $totalPendingAmount=floor($totalPendingAmount);
	                }else{
	                    $totalPendingAmount=round($totalPendingAmount);
	                }

					$totalSrAmount=($currentSrAmt+$updatedSrAmt);
					if($totalSrAmount>$pendingAmtNow){
	                    $totalSrAmount=floor($totalSrAmount);
	                }else{
	                    $totalSrAmount=round($totalSrAmount);
	                }
					
					$srUpdate=array('pendingAmt' => $totalPendingAmount,'SRAmt'=>$totalSrAmount,'fsSrAmt'=>$updatedSrAmt);
					$this->GodownKeeperModel->update('bills',$srUpdate,$currBillId);//update latest sr amount

					$history=array(
						'billId'=>$currBillId,
						'allocationId'=>$allocationId,
						'transactionAmount' =>round($finalQtySrTotal),
						'transactionStatus' =>'SR',
						'transactionMode' =>'dr',
						'transactionDate'=>date('Y-m-d H:i:sa'),
						'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
						'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
					);
					$this->GodownKeeperModel->insert('bill_transaction_history',$history);
	
					$billPayment=array(
						'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
						'billId'=>$currBillId,
						'allocationId'=>$allocationId,
						'date'=>date('Y-m-d H:i:sa'),
						'paidAmount'=>round($finalQtySrTotal),
						'billAmount'=>$billNetAmount,
						'balanceAmount'=>$totalPendingAmount,
						'paymentMode'=>'SR',
						'taxableValue'=>$totalTaxableValue,
						'taxAmount'=>$totalTaxAmount,
					    'cessAmount'=>$totalCessAmount,
						'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
					);
					$this->GodownKeeperModel->insert('billpayments',$billPayment);//insert billpayment data

					$checkSrDetail=$this->GodownKeeperModel->loadSrDetails('allocation_sr_details',$currBillId,$allocationId,$billDetailId);
					if(!empty($checkSrDetail)){
						$srData = array(
							'allocationId' => $allocationId,
							'billId' =>  $currBillId,
							'billItemId'=>$billDetailId,
							'quantity'=>$srQty,
							'createdAt'=>date('Y-m-d H:i:sa')
						);
						$this->GodownKeeperModel->updateSrDetail('allocation_sr_details',$srData,$allocationId,$currBillId,$billDetailId);

						$isDeliverySlip=$this->GodownKeeperModel->load('bills', $currBillId);
						if(!empty($isDeliverySlip)){
							if($isDeliverySlip[0]['isDeliverySlipBill']==1){
								//for product return with code for delivery slip products
								$prodDet=$this->GodownKeeperModel->getDetailByCodeWithDetails('products',$prodName,$prodCode,(int)$prodMrp);
								if(!empty($prodDet)){
									$insertData=array(
										'billingId'=>$currBillId,
										'operationStatus'=>'add',
										'quantityInPcs'=>$srQty,
										'productId'=>$prodDet[0]['id'],
										'productName'=>$prodDet[0]['name'],
										'productCode'=>$prodDet[0]['productCode'],
										'quantity'=>$srQty,
										'quantityUnit'=>'pcs',
										'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
										'createdAt'=>date('Y-m-d H:i:sa')
									);
									// print_r($insertData);exit;
									$this->GodownKeeperModel->insert('deliveryslip_pending_for_billing',$insertData);
								}
							}
						}
					}else{
						$srData = array(
							'allocationId' => $allocationId,
							'billId' =>  $currBillId,
							'billItemId'=>$billDetailId,
							'quantity'=>$srQty,
							'createdAt'=>date('Y-m-d H:i:sa')
						);
						$this->GodownKeeperModel->insert('allocation_sr_details',$srData);

						$isDeliverySlip=$this->GodownKeeperModel->load('bills', $currBillId);
						if(!empty($isDeliverySlip)){
							if($isDeliverySlip[0]['isDeliverySlipBill']==1){
								//for product return with code for delivery slip products
								$prodDet=$this->GodownKeeperModel->getDetailByCodeWithDetails('products',$prodName,$prodCode,(int)$prodMrp);
								if(!empty($prodDet)){
									$insertData=array(
										'billingId'=>$currBillId,
										'operationStatus'=>'add',
										'quantityInPcs'=>$srQty,
										'productId'=>$prodDet[0]['id'],
										'productName'=>$prodDet[0]['name'],
										'productCode'=>$prodDet[0]['productCode'],
										'quantity'=>$srQty,
										'quantityUnit'=>'pcs',
										'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
										'createdAt'=>date('Y-m-d H:i:sa')
									);
									// print_r($insertData);exit;
									$this->GodownKeeperModel->insert('deliveryslip_pending_for_billing',$insertData);
								}
							}
						}
					}
				}
			}
		}else{
			$totalTaxableValue=0;
            $totalTaxAmount=0;
            $totalCessAmount=0;

			$pendingAmt=$billsInfo[0]['pendingAmt'];
			$sumOfCals=$billsInfo[0]['fsCashAmt']+$billsInfo[0]['fsSrAmt']+$billsInfo[0]['fsNeftAmt']+$billsInfo[0]['fsChequeAmt'];
			$totalPending=$pendingAmt-$sumOfCals;//total pending till current allocation.

			$eachQtyRate=$billDetailInfo[0]['netAmount']/$billDetailInfo[0]['qty'];
			$returnAmt=($srQty*$eachQtyRate);

			$finalQtySrTotal=($srQty*$eachQtyRate);

			$fsReturnQty=$billDetailInfo[0]['fsReturnQty'];
				
			$billSRAmt=$billsInfo[0]['fsSrAmt'];
			if($srQty==$fsReturnQty){
				$shortQty=$fsReturnQty-$srQty;

				$shortReturnAmt=($shortQty*$eachQtyRate);
				$shortReturnAmt= ($shortReturnAmt);
				$updatedPendingAmt=$pendingAmt+$shortReturnAmt;//updated pending amt

				$updatedSrAmt=$billSRAmt-$shortReturnAmt;//updated SR amt
				$updatedSrAmt=($updatedSrAmt);
				if($updatedSrAmt>$pendingAmtNow){
                    $updatedSrAmt=floor($updatedSrAmt);
                }else{
                    $updatedSrAmt=round($updatedSrAmt);
                }

				$data['billsdetails']=$this->GodownKeeperModel->load('billsdetails', $billDetailId);
				//cess amount
				$cessAmount=$data['billsdetails'][0]['cessAmount']/$data['billsdetails'][0]['qty'];
				$cessAmount=round($srQty*$cessAmount,2);

				//tax amount
				$taxAmount=$data['billsdetails'][0]['taxAmount']/$data['billsdetails'][0]['qty'];
				$taxAmount=round($srQty*$taxAmount,2);

				//taxable amount
				$taxableValue=$data['billsdetails'][0]['taxableValue']/$data['billsdetails'][0]['qty'];
				$taxableValue=round($srQty*$taxableValue,2);

				$totalTaxableValue=$totalTaxableValue+$taxableValue;
				$totalTaxAmount=$totalTaxAmount+$taxAmount;
				$totalCessAmount=$totalCessAmount+$cessAmount;

				$billDetailUpdate=array('fsReturnQty' => 0,'gkReturnQty' => $srQty,'fsReturnAmt' => $returnAmt,'gkStatus' => 1);
				
				$this->GodownKeeperModel->update('billsdetails',$billDetailUpdate,$billDetailId);

				$pendBillInfo=$this->GodownKeeperModel->load('bills',$currBillId);
				$currentPending=$pendBillInfo[0]['pendingAmt'];
				$currentSrAmt=$pendBillInfo[0]['SRAmt'];

				$totalPendingAmount=($currentPending-$returnAmt);
				if($totalPendingAmount>$pendingAmtNow){
                    $totalPendingAmount=floor($totalPendingAmount);
                }else{
                    $totalPendingAmount=round($totalPendingAmount);
                }

				$totalSrAmount=($currentSrAmt+$returnAmt);
				if($totalSrAmount>$pendingAmtNow){
                    $totalSrAmount=floor($totalSrAmount);
                }else{
                    $totalSrAmount=round($totalSrAmount);
                }
				
				$srUpdate=array('pendingAmt' => $totalPendingAmount,'SRAmt'=>$totalSrAmount,'fsSrAmt'=>$updatedSrAmt,'fsbillStatus' => 'SR');
				$this->GodownKeeperModel->update('bills',$srUpdate,$currBillId);//update latest sr amount

				$history=array(
					'billId'=>$currBillId,
					'allocationId'=>$allocationId,
					'transactionAmount' =>round($finalQtySrTotal),
					'transactionStatus' =>'SR',
					'transactionMode' =>'dr',
					'transactionDate'=>date('Y-m-d H:i:sa'),
					'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
					'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
				);
				$this->GodownKeeperModel->insert('bill_transaction_history',$history);


				$billPayment=array(
					'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
					'billId'=>$currBillId,
					'allocationId'=>$allocationId,
					'date'=>date('Y-m-d H:i:sa'),
					'paidAmount'=>round($finalQtySrTotal),
					'billAmount'=>$billNetAmount,
					'balanceAmount'=>$totalPendingAmount,
					'paymentMode'=>'SR',
					'taxableValue'=>$totalTaxableValue,
					'taxAmount'=>$totalTaxAmount,
					'cessAmount'=>$totalCessAmount,
					'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
				);
				$this->GodownKeeperModel->insert('billpayments',$billPayment);//insert billpayment data
				
				$checkSrDetail=$this->GodownKeeperModel->loadSrDetails('allocation_sr_details',$currBillId,$allocationId,$billDetailId);
				if(!empty($checkSrDetail)){
					$srData = array(
						'allocationId' => $allocationId,
						'billId' =>  $currBillId,
						'billItemId'=>$billDetailId,
						'quantity'=>$srQty,
						'createdAt'=>date('Y-m-d H:i:sa')
					);
					$this->GodownKeeperModel->updateSrDetail('allocation_sr_details',$srData,$allocationId,$currBillId,$billDetailId);

					$isDeliverySlip=$this->GodownKeeperModel->load('bills', $currBillId);
					if(!empty($isDeliverySlip)){
						if($isDeliverySlip[0]['isDeliverySlipBill']==1){
							//for product return with code for delivery slip products
							$prodDet=$this->GodownKeeperModel->getDetailByCodeWithDetails('products',$prodName,$prodCode,(int)$prodMrp);
							if(!empty($prodDet)){
								$insertData=array(
									'billingId'=>$currBillId,
									'operationStatus'=>'add',
									'quantityInPcs'=>$srQty,
									'productId'=>$prodDet[0]['id'],
									'productName'=>$prodDet[0]['name'],
									'productCode'=>$prodDet[0]['productCode'],
									'quantity'=>$srQty,
									'quantityUnit'=>'pcs',
									'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
									'createdAt'=>date('Y-m-d H:i:sa')
								);
								// print_r($insertData);exit;
								$this->GodownKeeperModel->insert('deliveryslip_pending_for_billing',$insertData);
							}
						}
					}
				}else{
					$srData = array(
						'allocationId' => $allocationId,
						'billId' =>  $currBillId,
						'billItemId'=>$billDetailId,
						'quantity'=>$srQty,
						'createdAt'=>date('Y-m-d H:i:sa')
					);
					$this->GodownKeeperModel->insert('allocation_sr_details',$srData);

					$isDeliverySlip=$this->GodownKeeperModel->load('bills', $currBillId);
					if(!empty($isDeliverySlip)){
						if($isDeliverySlip[0]['isDeliverySlipBill']==1){
							//for product return with code for delivery slip products
							$prodDet=$this->GodownKeeperModel->getDetailByCodeWithDetails('products',$prodName,$prodCode,(int)$prodMrp);
							if(!empty($prodDet)){
								$insertData=array(
									'billingId'=>$currBillId,
									'operationStatus'=>'add',
									'quantityInPcs'=>$srQty,
									'productId'=>$prodDet[0]['id'],
									'productName'=>$prodDet[0]['name'],
									'productCode'=>$prodDet[0]['productCode'],
									'quantity'=>$srQty,
									'quantityUnit'=>'pcs',
									'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
									'createdAt'=>date('Y-m-d H:i:sa')
								);
								// print_r($insertData);exit;
								$this->GodownKeeperModel->insert('deliveryslip_pending_for_billing',$insertData);
							}
						}
					}
				}
			}else if($srQty<$fsReturnQty){
				
				$shortQty=$fsReturnQty-$srQty;
				
				$shortReturnAmt=($shortQty*$eachQtyRate);
				$finalQtySrTotal=($shortQty*$eachQtyRate);

				$shortReturnAmt= ($shortReturnAmt);
				$updatedPendingAmt=$pendingAmt+$shortReturnAmt;//updated pending amt

				$updatedSrAmt=$billSRAmt-$shortReturnAmt;//updated SR amt
				$updatedSrAmt=($updatedSrAmt);
				if($updatedSrAmt>$pendingAmtNow){
                    $updatedSrAmt=floor($updatedSrAmt);
                }else{
                    $updatedSrAmt=round($updatedSrAmt);
                }

				$data['billsdetails']=$this->GodownKeeperModel->load('billsdetails', $billDetailId);
				//cess amount
				$cessAmount=$data['billsdetails'][0]['cessAmount']/$data['billsdetails'][0]['qty'];
				$cessAmount=round($srQty*$cessAmount,2);

				//tax amount
				$taxAmount=$data['billsdetails'][0]['taxAmount']/$data['billsdetails'][0]['qty'];
				$taxAmount=round($srQty*$taxAmount,2);

				//taxable amount
				$taxableValue=$data['billsdetails'][0]['taxableValue']/$data['billsdetails'][0]['qty'];
				$taxableValue=round($srQty*$taxableValue,2);

				$totalTaxableValue=$totalTaxableValue+$taxableValue;
				$totalTaxAmount=$totalTaxAmount+$taxAmount;
				$totalCessAmount=$totalCessAmount+$cessAmount;

				if($pendingAmt>$sumOfCals){
					
					$billDetailUpdate=array('fsReturnQty' => 0,'gkReturnQty' => $srQty,'fsReturnAmt' => $returnAmt,'gkStatus' => 1);

					$this->GodownKeeperModel->update('billsdetails',$billDetailUpdate,$billDetailId);

					$pendBillInfo=$this->GodownKeeperModel->load('bills',$currBillId);
					$currentPending=$pendBillInfo[0]['pendingAmt'];
					$currentSrAmt=$pendBillInfo[0]['SRAmt'];

					$totalPendingAmount=($currentPending-$returnAmt);
					if($totalPendingAmount>$pendingAmtNow){
	                    $totalPendingAmount=floor($totalPendingAmount);
	                }else{
	                    $totalPendingAmount=round($totalPendingAmount);
	                }

					$totalSrAmount=($currentSrAmt+$returnAmt);
					if($totalSrAmount>$pendingAmtNow){
	                    $totalSrAmount=floor($totalSrAmount);
	                }else{
	                    $totalSrAmount=round($totalSrAmount);
	                }
					
					$srUpdate=array('pendingAmt' => $totalPendingAmount,'SRAmt'=>$totalSrAmount,'fsSrAmt'=>$updatedSrAmt);
					$this->GodownKeeperModel->update('bills',$srUpdate,$currBillId);//update latest sr amount

					$history=array(
						'billId'=>$currBillId,
						'allocationId'=>$allocationId,
						'transactionAmount' =>round($finalQtySrTotal),
						'transactionStatus' =>'SR',
						'transactionMode' =>'dr',
						'transactionDate'=>date('Y-m-d H:i:sa'),
						'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
						'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
					);
					$this->GodownKeeperModel->insert('bill_transaction_history',$history);
	
	
					$billPayment=array(
						'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
						'billId'=>$currBillId,
						'allocationId'=>$allocationId,
						'date'=>date('Y-m-d H:i:sa'),
						'paidAmount'=>round($finalQtySrTotal),
						'billAmount'=>$billNetAmount,
						'balanceAmount'=>$totalPendingAmount,
						'paymentMode'=>'SR',
						'taxableValue'=>$totalTaxableValue,
						'taxAmount'=>$totalTaxAmount,
					    'cessAmount'=>$totalCessAmount,
						'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
					);
					$this->GodownKeeperModel->insert('billpayments',$billPayment);//insert billpayment data

					$updatedBillInfo=$this->GodownKeeperModel->load('bills',$currBillId);
					$currentSrTotal=$updatedBillInfo[0]['fsSrAmt'];

					$currentSrTotal= round($currentSrTotal);
					if($currentSrTotal<=0){
						$oldStatus=$updatedBillInfo[0]['fsbillStatus'];
						$fsstatus=str_replace('SR', '', $oldStatus);
						$status=trim($fsstatus,',');
						$data_status = array('fsbillStatus' => $status);  
						$this->GodownKeeperModel->update('bills',$data_status,$currBillId);
					}

					$checkSrDetail=$this->GodownKeeperModel->loadSrDetails('allocation_sr_details',$currBillId,$allocationId,$billDetailId);
					if(!empty($checkSrDetail)){
						$srData = array(
							'allocationId' => $allocationId,
							'billId' =>  $currBillId,
							'billItemId'=>$billDetailId,
							'quantity'=>$srQty,
							'createdAt'=>date('Y-m-d H:i:sa')
						);
						$this->GodownKeeperModel->updateSrDetail('allocation_sr_details',$srData,$allocationId,$currBillId,$billDetailId);

						$isDeliverySlip=$this->GodownKeeperModel->load('bills', $currBillId);
						if(!empty($isDeliverySlip)){
							if($isDeliverySlip[0]['isDeliverySlipBill']==1){
								//for product return with code for delivery slip products
								$prodDet=$this->GodownKeeperModel->getDetailByCodeWithDetails('products',$prodName,$prodCode,(int)$prodMrp);
								if(!empty($prodDet)){
									$insertData=array(
										'billingId'=>$currBillId,
										'operationStatus'=>'add',
										'quantityInPcs'=>$srQty,
										'productId'=>$prodDet[0]['id'],
										'productName'=>$prodDet[0]['name'],
										'productCode'=>$prodDet[0]['productCode'],
										'quantity'=>$srQty,
										'quantityUnit'=>'pcs',
										'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
										'createdAt'=>date('Y-m-d H:i:sa')
									);
									// print_r($insertData);exit;
									$this->GodownKeeperModel->insert('deliveryslip_pending_for_billing',$insertData);
								}
							}
						}
					}else{
						$srData = array(
							'allocationId' => $allocationId,
							'billId' =>  $currBillId,
							'billItemId'=>$billDetailId,
							'quantity'=>$srQty,
							'createdAt'=>date('Y-m-d H:i:sa')
						);
						$this->GodownKeeperModel->insert('allocation_sr_details',$srData);

						$isDeliverySlip=$this->GodownKeeperModel->load('bills', $currBillId);
						if(!empty($isDeliverySlip)){
							if($isDeliverySlip[0]['isDeliverySlipBill']==1){
								//for product return with code for delivery slip products
								$prodDet=$this->GodownKeeperModel->getDetailByCodeWithDetails('products',$prodName,$prodCode,(int)$prodMrp);
								if(!empty($prodDet)){
									$insertData=array(
										'billingId'=>$currBillId,
										'operationStatus'=>'add',
										'quantityInPcs'=>$srQty,
										'productId'=>$prodDet[0]['id'],
										'productName'=>$prodDet[0]['name'],
										'productCode'=>$prodDet[0]['productCode'],
										'quantity'=>$srQty,
										'quantityUnit'=>'pcs',
										'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
										'createdAt'=>date('Y-m-d H:i:sa')
									);
									// print_r($insertData);exit;
									$this->GodownKeeperModel->insert('deliveryslip_pending_for_billing',$insertData);
								}
							}
						}
					}
				}else{
					
					$allocation=$this->GodownKeeperModel->load('allocations',$allocationId);

					$empId=$allocation[0]['fieldStaffCode1'];
					$link="https://yontechsoftwares.com/share/smartdistributor/index.php/AllocationByManagerController/smsSrTrancationDetails/".$allocationId;

					$desc="Amount debited for short qty : ".$shortQty." for Bill No : ".$billNo." of Allocation No. ".$allocationId ;
					
					$debitData=array('empId'=>$empId,'allocationId'=>$allocationId,'amount' => $shortReturnAmt,'description'=>$desc,'createdAt'=>date('Y-m-d H:i:sa'));

					$descr="Amount debited for short qty : ".$shortQty." for Bill No : ".$billNo." of Allocation No. ".$allocationId.'SR accepted please check at '.$link ;

					$mobile="8446107727";
					$url="https://api.msg91.com/api/sendhttp.php?authkey=291106AG8eCyzDe5d626f5c&mobiles=".$mobile."&country=91&message=".$descr."&sender=TESTIN&route=4";

					$billDetailUpdate=array('fsReturnQty' => 0,'gkReturnQty' => $srQty,'fsReturnAmt' => $returnAmt,'gkStatus' => 1);

					$updatedSrAmt=($updatedSrAmt);
					if($updatedSrAmt>$pendingAmtNow){
	                    $updatedSrAmt=floor($updatedSrAmt);
	                }else{
	                    $updatedSrAmt=round($updatedSrAmt);
	                }
					
					$this->GodownKeeperModel->update('billsdetails',$billDetailUpdate,$billDetailId);

					$pendBillInfo=$this->GodownKeeperModel->load('bills',$currBillId);
					$currentPending=$pendBillInfo[0]['pendingAmt'];
					$currentSrAmt=$pendBillInfo[0]['SRAmt'];

					$totalPendingAmount=($currentPending-$returnAmt);
					if($totalPendingAmount>$pendingAmtNow){
	                    $totalPendingAmount=floor($totalPendingAmount);
	                }else{
	                    $totalPendingAmount=round($totalPendingAmount);
	                }

					$totalSrAmount=($currentSrAmt+$returnAmt);
					if($totalSrAmount>$pendingAmtNow){
	                    $totalSrAmount=floor($totalSrAmount);
	                }else{
	                    $totalSrAmount=round($totalSrAmount);
	                }
					
					$srUpdate=array('pendingAmt' => $totalPendingAmount,'SRAmt'=>$totalSrAmount,'fsSrAmt'=>$updatedSrAmt);
					$this->GodownKeeperModel->update('bills',$srUpdate,$currBillId);//update latest sr amount

					$history=array(
						'billId'=>$currBillId,
						'allocationId'=>$allocationId,
						'transactionAmount' =>round($finalQtySrTotal),
						'transactionStatus' =>'SR',
						'transactionMode' =>'dr',
						'transactionDate'=>date('Y-m-d H:i:sa'),
						'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
						'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
					);
					$this->GodownKeeperModel->insert('bill_transaction_history',$history);
	
	
					$billPayment=array(
						'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
						'billId'=>$currBillId,
						'allocationId'=>$allocationId,
						'date'=>date('Y-m-d H:i:sa'),
						'paidAmount'=>round($finalQtySrTotal),
						'billAmount'=>$billNetAmount,
						'balanceAmount'=>$totalPendingAmount,
						'paymentMode'=>'SR',
						'taxableValue'=>$totalTaxableValue,
						'taxAmount'=>$totalTaxAmount,
					    'cessAmount'=>$totalCessAmount,
						'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
					);
					$this->GodownKeeperModel->insert('billpayments',$billPayment);//insert billpayment data

					$updatedBillInfo=$this->GodownKeeperModel->load('bills',$currBillId);
					$currentSrTotal=$updatedBillInfo[0]['fsSrAmt'];
					$currentSrTotal= round($currentSrTotal);
					if($currentSrTotal<=0){
						$oldStatus=$updatedBillInfo[0]['fsbillStatus'];
						$fsstatus=str_replace('SR', '', $oldStatus);
						$status=trim($fsstatus,',');
						$data_status = array('fsbillStatus' => $status);  
						$this->GodownKeeperModel->update('bills',$data_status,$currBillId);
					}

					$checkSrDetail=$this->GodownKeeperModel->loadSrDetails('allocation_sr_details',$currBillId,$allocationId,$billDetailId);
					if(!empty($checkSrDetail)){
						$srData = array(
							'allocationId' => $allocationId,
							'billId' =>  $currBillId,
							'billItemId'=>$billDetailId,
							'quantity'=>$srQty,
							'createdAt'=>date('Y-m-d H:i:sa')
						);
						$this->GodownKeeperModel->updateSrDetail('allocation_sr_details',$srData,$allocationId,$currBillId,$billDetailId);

						$isDeliverySlip=$this->GodownKeeperModel->load('bills', $currBillId);
						if(!empty($isDeliverySlip)){
							if($isDeliverySlip[0]['isDeliverySlipBill']==1){
								//for product return with code for delivery slip products
								$prodDet=$this->GodownKeeperModel->getDetailByCodeWithDetails('products',$prodName,$prodCode,(int)$prodMrp);
								if(!empty($prodDet)){
									$insertData=array(
										'billingId'=>$currBillId,
										'operationStatus'=>'add',
										'quantityInPcs'=>$srQty,
										'productId'=>$prodDet[0]['id'],
										'productName'=>$prodDet[0]['name'],
										'productCode'=>$prodDet[0]['productCode'],
										'quantity'=>$srQty,
										'quantityUnit'=>'pcs',
										'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
										'createdAt'=>date('Y-m-d H:i:sa')
									);
									// print_r($insertData);exit;
									$this->GodownKeeperModel->insert('deliveryslip_pending_for_billing',$insertData);
								}
							}
						}
					}else{
						$srData = array(
							'allocationId' => $allocationId,
							'billId' =>  $currBillId,
							'billItemId'=>$billDetailId,
							'quantity'=>$srQty,
							'createdAt'=>date('Y-m-d H:i:sa')
						);
						$this->GodownKeeperModel->insert('allocation_sr_details',$srData);

						$isDeliverySlip=$this->GodownKeeperModel->load('bills', $currBillId);
						if(!empty($isDeliverySlip)){
							if($isDeliverySlip[0]['isDeliverySlipBill']==1){
								//for product return with code for delivery slip products
								$prodDet=$this->GodownKeeperModel->getDetailByCodeWithDetails('products',$prodName,$prodCode,(int)$prodMrp);
								if(!empty($prodDet)){
									$insertData=array(
										'billingId'=>$currBillId,
										'operationStatus'=>'add',
										'quantityInPcs'=>$srQty,
										'productId'=>$prodDet[0]['id'],
										'productName'=>$prodDet[0]['name'],
										'productCode'=>$prodDet[0]['productCode'],
										'quantity'=>$srQty,
										'quantityUnit'=>'pcs',
										'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
										'createdAt'=>date('Y-m-d H:i:sa')
									);
									// print_r($insertData);exit;
									$this->GodownKeeperModel->insert('deliveryslip_pending_for_billing',$insertData);
								}
							}
						}
					}
				}
			}else if($srQty>$fsReturnQty){
				$extraQty=$srQty-$fsReturnQty;
				$extraReturnAmt=($extraQty*$eachQtyRate);

				$finalQtySrTotal=($extraQty*$eachQtyRate);

				$updatedPendingAmt=$pendingAmt-$extraReturnAmt;//updated pending amt
				$updatedSrAmt=$billSRAmt+$extraReturnAmt;//updated SR amt

				$updatedSrAmt=($updatedSrAmt);
				if($updatedSrAmt>$pendingAmtNow){
                    $updatedSrAmt=floor($updatedSrAmt);
                }else{
                    $updatedSrAmt=round($updatedSrAmt);
                }

				$data['billsdetails']=$this->GodownKeeperModel->load('billsdetails', $billDetailId);
				//cess amount
				$cessAmount=$data['billsdetails'][0]['cessAmount']/$data['billsdetails'][0]['qty'];
				$cessAmount=round($srQty*$cessAmount,2);

				//tax amount
				$taxAmount=$data['billsdetails'][0]['taxAmount']/$data['billsdetails'][0]['qty'];
				$taxAmount=round($srQty*$taxAmount,2);

				//taxable amount
				$taxableValue=$data['billsdetails'][0]['taxableValue']/$data['billsdetails'][0]['qty'];
				$taxableValue=round($srQty*$taxableValue,2);

				$totalTaxableValue=$totalTaxableValue+$taxableValue;
				$totalTaxAmount=$totalTaxAmount+$taxAmount;
				$totalCessAmount=$totalCessAmount+$cessAmount;

				if($pendingAmt>$sumOfCals){
					$billDetailUpdate=array('fsReturnQty' => 0,'gkReturnQty' => $srQty,'fsReturnAmt' => $updatedSrAmt,'gkStatus' => 1);
					
					$this->GodownKeeperModel->update('billsdetails',$billDetailUpdate,$billDetailId);

					$pendBillInfo=$this->GodownKeeperModel->load('bills',$currBillId);
					$currentPending=$pendBillInfo[0]['pendingAmt'];
					$currentSrAmt=$pendBillInfo[0]['SRAmt'];

					$totalPendingAmount=($currentPending-$updatedSrAmt);
					if($totalPendingAmount>$pendingAmtNow){
	                    $totalPendingAmount=floor($totalPendingAmount);
	                }else{
	                    $totalPendingAmount=round($totalPendingAmount);
	                }

					$totalSrAmount=($currentSrAmt+$updatedSrAmt);
					if($totalSrAmount>$pendingAmtNow){
	                    $totalSrAmount=floor($totalSrAmount);
	                }else{
	                    $totalSrAmount=round($totalSrAmount);
	                }
					
					$srUpdate=array('pendingAmt' => $totalPendingAmount,'SRAmt'=>$totalSrAmount,'fsSrAmt'=>$updatedSrAmt);
					$this->GodownKeeperModel->update('bills',$srUpdate,$currBillId);//update latest sr amount

					$history=array(
						'billId'=>$currBillId,
						'allocationId'=>$allocationId,
						'transactionAmount' =>round($finalQtySrTotal),
						'transactionStatus' =>'SR',
						'transactionMode' =>'dr',
						'transactionDate'=>date('Y-m-d H:i:sa'),
						'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
						'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
					);
					$this->GodownKeeperModel->insert('bill_transaction_history',$history);
	
	
					$billPayment=array(
						'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
						'billId'=>$currBillId,
						'allocationId'=>$allocationId,
						'date'=>date('Y-m-d H:i:sa'),
						'paidAmount'=>round($finalQtySrTotal),
						'billAmount'=>$billNetAmount,
						'balanceAmount'=>$totalPendingAmount,
						'paymentMode'=>'SR',
						'taxableValue'=>$totalTaxableValue,
						'taxAmount'=>$totalTaxAmount,
					    'cessAmount'=>$totalCessAmount,
						'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
					);
					$this->GodownKeeperModel->insert('billpayments',$billPayment);//insert billpayment data

					$checkSrDetail=$this->GodownKeeperModel->loadSrDetails('allocation_sr_details',$currBillId,$allocationId,$billDetailId);
					if(!empty($checkSrDetail)){
						$srData = array(
							'allocationId' => $allocationId,
							'billId' =>  $currBillId,
							'billItemId'=>$billDetailId,
							'quantity'=>$srQty,
							'createdAt'=>date('Y-m-d H:i:sa')
						);
						$this->GodownKeeperModel->updateSrDetail('allocation_sr_details',$srData,$allocationId,$currBillId,$billDetailId);

						$isDeliverySlip=$this->GodownKeeperModel->load('bills', $currBillId);
						if(!empty($isDeliverySlip)){
							if($isDeliverySlip[0]['isDeliverySlipBill']==1){
								//for product return with code for delivery slip products
								$prodDet=$this->GodownKeeperModel->getDetailByCodeWithDetails('products',$prodName,$prodCode,(int)$prodMrp);
								if(!empty($prodDet)){
									$insertData=array(
										'billingId'=>$currBillId,
										'operationStatus'=>'add',
										'quantityInPcs'=>$srQty,
										'productId'=>$prodDet[0]['id'],
										'productName'=>$prodDet[0]['name'],
										'productCode'=>$prodDet[0]['productCode'],
										'quantity'=>$srQty,
										'quantityUnit'=>'pcs',
										'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
										'createdAt'=>date('Y-m-d H:i:sa')
									);
									// print_r($insertData);exit;
									$this->GodownKeeperModel->insert('deliveryslip_pending_for_billing',$insertData);
								}
							}
						}
					}else{
						$srData = array(
							'allocationId' => $allocationId,
							'billId' =>  $currBillId,
							'billItemId'=>$billDetailId,
							'quantity'=>$srQty,
							'createdAt'=>date('Y-m-d H:i:sa')
						);
						$this->GodownKeeperModel->insert('allocation_sr_details',$srData);

						$isDeliverySlip=$this->GodownKeeperModel->load('bills', $currBillId);
							if(!empty($isDeliverySlip)){
								if($isDeliverySlip[0]['isDeliverySlipBill']==1){
								//for product return with code for delivery slip products
								$prodDet=$this->GodownKeeperModel->getDetailByCodeWithDetails('products',$prodName,$prodCode,(int)$prodMrp);
								if(!empty($prodDet)){
									$insertData=array(
										'billingId'=>$currBillId,
										'operationStatus'=>'add',
										'quantityInPcs'=>$srQty,
										'productId'=>$prodDet[0]['id'],
										'productName'=>$prodDet[0]['name'],
										'productCode'=>$prodDet[0]['productCode'],
										'quantity'=>$srQty,
										'quantityUnit'=>'pcs',
										'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
										'createdAt'=>date('Y-m-d H:i:sa')
									);
									// print_r($insertData);exit;
									$this->GodownKeeperModel->insert('deliveryslip_pending_for_billing',$insertData);
								}
							}
						}
					}
				}
			}
		}

        $bills=$this->GodownKeeperModel->billsByCode('billsdetails',$currBillId);

    ?>

        <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                             <div class="header">
                            <center><h2> Bill Details</h2></center><br />
                            <h2 style="color: red;">
                                Bill No     :   <?php 
                                                    if(!empty($bills)){
                                                       echo $bills[0]['BillNo'];
                                                    }
                                                ?> &nbsp &nbsp&nbsp &nbsp &nbsp &nbsp
                                Retailer Name:  <?php 
                                                    if(!empty($bills)){
                                                       echo $bills[0]['RetailerName'];
                                                    }
                                                ?> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
                            </h2>
                        </div>
                        <div class="body">
                        	<div>
                                    <button type="button" onclick="updateCurrentSrValue('<?php if(!empty($bills)){ echo $bills[0]['billId']; } ?>','<?php echo $allocationId; ?>')" class="btn btn-primary m-t-15 waves-effect">
                                        <i class="material-icons">save</i><span class="icon-name">Accept All</span>
                                    </button>
                                
                                <button data-dismiss="modal" type="button" class="btn btn-danger m-t-15 waves-effect">
                                            <i class="material-icons">cancel</i><span class="icon-name">cancel</span>
                                        </button>
                                </div>
                            <div class="table-responsive">
                                 
                                    <div id="res"></div>
                                <table id="SrTable" class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                             <th>S. No.</th>
                                            <th style="display: none;"></th>
                                            <th>Bill No.</th>
                                            <th>Retailer</th>
                                            <th>Item</th>
                                            <th>MRP</th>
                                            <th>Billed Qty</th>
                                            <th>SR</th>
                                            <th>SR Received</th>
                                             <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                         <tr>
                                            <th>S. No.</th>
                                            <th style="display: none;"></th>
                                            <th>Bill No.</th>
                                            <th>Retailer</th>
                                            <th>Item</th>
                                            <th>MRP</th>
                                            <th>Billed Qty</th>
                                            <th>SR</th>
                                            <th>SR Received</th>
                                             <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        $no=0;
                                        if(!empty($bills)){
                                        foreach ($bills as $data) 
                                          { 
                                             $no++; 
                                             $srQty=$this->GodownKeeperModel->getTotalSrQtyById($data['id']);
                                    ?>
                                             <tr>
                                                <td><?php echo $no;?></td>
                                               <td id="billId" style="display: none;"><?php echo $data['id'];?></td>
                                                <td id="billNo">
                                                    <?php echo $data['BillNo'];?>
                                                </td>
                                               <td><?php 
                                                $retailerName=substr($data['RetailerName'], 0, 30);
                                                echo $retailerName;?></td>
                                               
                                                <td><?php echo $data['productName'];?></td>
                                                <td align="right"><?php echo $data['mrp'];?></td>
                                                <td align="right"><?php echo number_format($data['qty']);?></td>
                                                <td align="right"><?php echo number_format($data['fsReturnQty']);?></td>
                                                <td id="srQty">
                                                	<?php if($data['gkStatus']==1){ ?>
                                                		<?php echo (int)$data['fsReturnQty']; ?>
                                                	<?php }else{ ?>	
                                                		<input style="width: 50%" type="text" onblur="checkFsrQtyPerItem(this,'<?php echo $no; ?>','<?php echo $data['qty']; ?>','<?php echo $data['fsReturnQty']; ?>')" onfocus="this.select();" name="fsReturnQty" value="<?php echo (int)$data['fsReturnQty']; ?>">
                                                    <p style="color:red" id="data_errr<?php echo $no; ?>"></p>
                                                	<?php } ?>

                                                    
                                                </td>
                                                <td>
                                                	<?php if($data['gkStatus']==1){ 
                                                		echo '&#10004;';
                                                	 }else{ ?>	
	                                                    <button id="btn_errr<?php echo $no; ?>" onclick="updateSRqtyForFsr(this);" class="btn-primary waves-effect">
	                                                        
	                                                        <span class="icon-name">
	                                                        OK
	                                                        </span>
	                                                    </button>
                                                <?php } ?>
                                                </td>
                                           </tr>
                                     <?php
                                            }
                                        }?>
                                    </tbody>
                                </table>
                                 
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php 

    }

    public function updatePendingAmtWithSrQuantity(){
    	$billId=$this->input->post('billId');
    	$getBill=$this->GodownKeeperModel->load('bills',$billId);
    	$allocationId=$this->input->post('allocationId');
		// echo $allocationId;
    	$billDetail=$this->GodownKeeperModel->billsByCode('billsdetails',$billId);

    	$prodCode="";
        $prodName="";
        $prodMrp="";
        if(!empty($billDetail)){
            $prodCode=$billDetail[0]['productCode'];
			$prodName=$billDetail[0]['productName'];
			$prodMrp=$billDetail[0]['mrp'];
        }
    	$totalFsValue=0;

    	$creditAdjAmount=$getBill[0]['creditAdjustment'];
    	$billNetAmount=$getBill[0]['netAmount'];
		$currentnetAmount=$pendBillInfo[0]['netAmount'];
		if($creditAdjAmount>0){
			if(!empty($billDetail)){
	    		foreach($billDetail as $item){
	    			if($item['gkStatus'] !=1){
		    			$eachQtyRate=$item['netAmount']/$item['qty'];
		    			$returnAmt=round($item['fsReturnQty']*$eachQtyRate,2);// each items amount calculation
		    			$totalFsValue=$totalFsValue+$returnAmt;//addition of total sr amount for bill
		    			$billDetailUpdate=array('fsReturnQty' => 0,'gkReturnQty' => $item['fsReturnQty'],'fsReturnAmt' => $returnAmt,'gkStatus' => 1);
		    			$this->GodownKeeperModel->update('billsdetails',$billDetailUpdate,$item['id']);
		    			if($this->db->affected_rows()>0){
				            $checkSrDetail=$this->GodownKeeperModel->loadSrDetails('allocation_sr_details',$billId,$allocationId,$item['id']);
				            if(empty($checkSrDetail)){
				            	$srData = array(
					           		'allocationId' => $allocationId,
					              	'billId' =>  $billId,
					              	'billItemId'=>$item['id'],
					              	'quantity'=>$item['fsReturnQty'],
					              	'createdAt'=>date('Y-m-d H:i:sa')
					            );
					            $this->GodownKeeperModel->insert('allocation_sr_details',$srData);

								$isDeliverySlip=$this->GodownKeeperModel->load('bills', $billId);
								if(!empty($isDeliverySlip)){
									if($isDeliverySlip[0]['isDeliverySlipBill']==1){
										//for product return with code for delivery slip products
										$prodDet=$this->GodownKeeperModel->getDetailByCodeWithDetails('products',$prodName,$prodCode,(int)$prodMrp);
										if(!empty($prodDet)){
											$insertData=array(
												'billingId'=>$billId,
												'operationStatus'=>'add',
												'quantityInPcs'=>$item['fsReturnQty'],
												'productId'=>$prodDet[0]['id'],
												'productName'=>$prodDet[0]['name'],
												'productCode'=>$prodDet[0]['productCode'],
												'quantity'=>$item['fsReturnQty'],
												'quantityUnit'=>'pcs',
												'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
												'createdAt'=>date('Y-m-d H:i:sa')
											);
											// print_r($insertData);exit;
											$this->GodownKeeperModel->insert('deliveryslip_pending_for_billing',$insertData);
										}
									}
								}
				            }else{
				            	$srData = array(
					           		'allocationId' => $allocationId,
					              	'billId' =>  $billId,
					              	'billItemId'=>$item['id'],
					              	'quantity'=>$item['fsReturnQty'],
					              	'createdAt'=>date('Y-m-d H:i:sa')
					            );
					            $this->GodownKeeperModel->updateSrDetail('allocation_sr_details',$srData,$allocationId,$billId,$item['id']);

								$isDeliverySlip=$this->GodownKeeperModel->load('bills', $billId);
								if(!empty($isDeliverySlip)){
									if($isDeliverySlip[0]['isDeliverySlipBill']==1){
										//for product return with code for delivery slip products
										$prodDet=$this->GodownKeeperModel->getDetailByCodeWithDetails('products',$prodName,$prodCode,(int)$prodMrp);
										if(!empty($prodDet)){
											$insertData=array(
												'billingId'=>$billId,
												'operationStatus'=>'add',
												'quantityInPcs'=>$item['fsReturnQty'],
												'productId'=>$prodDet[0]['id'],
												'productName'=>$prodDet[0]['name'],
												'productCode'=>$prodDet[0]['productCode'],
												'quantity'=>$item['fsReturnQty'],
												'quantityUnit'=>'pcs',
												'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
												'createdAt'=>date('Y-m-d H:i:sa')
											);
											// print_r($insertData);exit;
											$this->GodownKeeperModel->insert('deliveryslip_pending_for_billing',$insertData);
										}
									}
								}
				            }
			            }
			        }
	    		}

	    		$totalFsValue=round($totalFsValue);
	    		if($totalFsValue>0){
		    		$pendBillInfo=$this->GodownKeeperModel->load('bills',$billId);
		    		$currentnetAmount=$pendBillInfo[0]['netAmount'];
		    		$currentPending=$pendBillInfo[0]['pendingAmt'];
		    		$currentSrAmt=$pendBillInfo[0]['SRAmt'];
		    		$totalPendingAmount=$currentPending-$totalFsValue;
		    		$totalSrAmount=$currentSrAmt+$totalFsValue;
		    		
		    		if($totalFsValue>$billNetAmount){
		    			$creditNoteRenewal=$totalFsValue-$currentnetAmount;
		    			$nowSrAmt=$totalFsValue-$billNetAmount;
		    			$srUpdate=array('pendingAmt' => 0,'SRAmt'=>$billNetAmount,'creditNoteRenewal'=>$creditNoteRenewal,'fsSrAmt'=>$billNetAmount);
			    		$this->GodownKeeperModel->update('bills',$srUpdate,$billId);//update latest sr amount

						$history=array(
							'billId'=>$billId,
							'allocationId'=>$allocationId,
							'transactionAmount' =>$billNetAmount,
							'transactionStatus' =>'SR',
							'transactionMode' =>'dr',
							'transactionDate'=>date('Y-m-d H:i:sa'),
							'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
							'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
						);
						$this->GodownKeeperModel->insert('bill_transaction_history',$history);
		
		
						$billPayment=array(
							'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
							'billId'=>$billId,
							'allocationId'=>$allocationId,
							'date'=>date('Y-m-d H:i:sa'),
							'paidAmount'=>$billNetAmount,
							'billAmount'=>$currentnetAmount,
							'balanceAmount'=>0,
							'paymentMode'=>'SR',
							'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
						);
						$this->GodownKeeperModel->insert('billpayments',$billPayment);//insert billpayment data

						$updatedBillInfo=$this->GodownKeeperModel->load('bills',$billId);
						$currentSrTotal=$updatedBillInfo[0]['fsSrAmt'];

						$currentSrTotal= round($currentSrTotal);
						if($currentSrTotal<=0){
							$oldStatus=$updatedBillInfo[0]['fsbillStatus'];
			       			$fsstatus=str_replace('SR', '', $oldStatus);
							$status=trim($fsstatus,',');
			    	   		$data_status = array('fsbillStatus' => $status);  
			    			$this->GodownKeeperModel->update('bills',$data_status,$billId);//update latest sr status
						}
		    		}else{
		    			$srUpdate=array('pendingAmt' => $totalPendingAmount,'SRAmt'=>$totalSrAmount,'fsSrAmt'=>$totalFsValue);
			    		$this->GodownKeeperModel->update('bills',$srUpdate,$billId);//update latest sr amount

						$history=array(
							'billId'=>$billId,
							'allocationId'=>$allocationId,
							'transactionAmount' =>$totalFsValue,
							'transactionStatus' =>'SR',
							'transactionMode' =>'dr',
							'transactionDate'=>date('Y-m-d H:i:sa'),
							'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
							'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
						);
						$this->GodownKeeperModel->insert('bill_transaction_history',$history);
		
		
						$billPayment=array(
							'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
							'billId'=>$billId,
							'allocationId'=>$allocationId,
							'date'=>date('Y-m-d H:i:sa'),
							'paidAmount'=>$totalFsValue,
							'billAmount'=>$currentnetAmount,
							'balanceAmount'=>$totalPendingAmount,
							'paymentMode'=>'SR',
							'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
						);
						$this->GodownKeeperModel->insert('billpayments',$billPayment);//insert billpayment data

						$updatedBillInfo=$this->GodownKeeperModel->load('bills',$billId);
						$currentSrTotal=$updatedBillInfo[0]['fsSrAmt'];

						$currentSrTotal= round($currentSrTotal);
						if($currentSrTotal<=0){
							$oldStatus=$updatedBillInfo[0]['fsbillStatus'];
			       			$fsstatus=str_replace('SR', '', $oldStatus);
							$status=trim($fsstatus,',');
			    	   		$data_status = array('fsbillStatus' => $status);  
			    			$this->GodownKeeperModel->update('bills',$data_status,$billId);//update latest sr status
						}
		    		}
				}
	    	}
		}else{

			// print_r($billDetail);exit;
			if(!empty($billDetail)){
	    		foreach($billDetail as $item){
	    			if($item['gkStatus'] !=1){
		    			$eachQtyRate=$item['netAmount']/$item['qty'];
		    			$returnAmt=round($item['fsReturnQty']*$eachQtyRate,2);// each items amount calculation
		    			$totalFsValue=$totalFsValue+$returnAmt;//addition of total sr amount for bill
						// echo $totalFsValue;
		    			$billDetailUpdate=array('fsReturnQty' => 0,'gkReturnQty' => $item['fsReturnQty'],'fsReturnAmt' => $returnAmt,'gkStatus' => 1);
		    			$this->GodownKeeperModel->update('billsdetails',$billDetailUpdate,$item['id']);
		    			if($this->db->affected_rows()>0){
							
				            $checkSrDetail=$this->GodownKeeperModel->loadSrDetails('allocation_sr_details',$billId,$allocationId,$item['id']);
							// print_r($checkSrDetail);
				            if(empty($checkSrDetail)){
				            	$srData = array(
					           		'allocationId' => $allocationId,
					              	'billId' =>  $billId,
					              	'billItemId'=>$item['id'],
					              	'quantity'=>$item['fsReturnQty'],
					              	'createdAt'=>date('Y-m-d H:i:sa')
					            );
								// print_r($srData);exit;
					            $this->GodownKeeperModel->insert('allocation_sr_details',$srData);
								// echo "hie";exit;
								$isDeliverySlip=$this->GodownKeeperModel->load('bills', $billId);
								if(!empty($isDeliverySlip)){
									if($isDeliverySlip[0]['isDeliverySlipBill']==1){
										//for product return with code for delivery slip products
										$prodDet=$this->GodownKeeperModel->getDetailByCodeWithDetails('products',$prodName,$prodCode,(int)$prodMrp);
										if(!empty($prodDet)){
											$insertData=array(
												'billingId'=>$billId,
												'operationStatus'=>'add',
												'quantityInPcs'=>$item['fsReturnQty'],
												'productId'=>$prodDet[0]['id'],
												'productName'=>$prodDet[0]['name'],
												'productCode'=>$prodDet[0]['productCode'],
												'quantity'=>$item['fsReturnQty'],
												'quantityUnit'=>'pcs',
												'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
												'createdAt'=>date('Y-m-d H:i:sa')
											);
											$this->GodownKeeperModel->insert('deliveryslip_pending_for_billing',$insertData);
										}
									}
								}
								
				            }else{
				            	$srData = array(
					           		'allocationId' => $allocationId,
					              	'billId' =>  $billId,
					              	'billItemId'=>$item['id'],
					              	'quantity'=>$item['fsReturnQty'],
					              	'createdAt'=>date('Y-m-d H:i:sa')
					            );
					            $this->GodownKeeperModel->updateSrDetail('allocation_sr_details',$srData,$allocationId,$billId,$item['id']);
// echo "hhhhh";exit;
								$isDeliverySlip=$this->GodownKeeperModel->load('bills', $billId);
								if(!empty($isDeliverySlip)){
									if($isDeliverySlip[0]['isDeliverySlipBill']==1){
										//for product return with code for delivery slip products
										$prodDet=$this->GodownKeeperModel->getDetailByCodeWithDetails('products',$prodName,$prodCode,(int)$prodMrp);
										if(!empty($prodDet)){
											$insertData=array(
												'billingId'=>$billId,
												'operationStatus'=>'add',
												'quantityInPcs'=>$item['fsReturnQty'],
												'productId'=>$prodDet[0]['id'],
												'productName'=>$prodDet[0]['name'],
												'productCode'=>$prodDet[0]['productCode'],
												'quantity'=>$item['fsReturnQty'],
												'quantityUnit'=>'pcs',
												'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
												'createdAt'=>date('Y-m-d H:i:sa')
											);
											$this->GodownKeeperModel->insert('deliveryslip_pending_for_billing',$insertData);
										}
									}
								}
				            }
			            }
			        }
	    		}

	    		$totalFsValue=round($totalFsValue);
	    		
	    		if($totalFsValue>0){
		    		$pendBillInfo=$this->GodownKeeperModel->load('bills',$billId);
		    		$currentPending=$pendBillInfo[0]['pendingAmt'];
		    		$currentSrAmt=$pendBillInfo[0]['SRAmt'];
		    		$totalPendingAmount=$currentPending-$totalFsValue;
		    		$totalSrAmount=$currentSrAmt+$totalFsValue;
		    		
		    		$srUpdate=array('pendingAmt' => $totalPendingAmount,'SRAmt'=>$totalSrAmount,'fsSrAmt'=>$totalFsValue);
		    		$this->GodownKeeperModel->update('bills',$srUpdate,$billId);//update latest sr amount

					$history=array(
						'billId'=>$billId,
						'allocationId'=>$allocationId,
						'transactionAmount' =>$totalFsValue,
						'transactionStatus' =>'SR',
						'transactionMode' =>'dr',
						'transactionDate'=>date('Y-m-d H:i:sa'),
						'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
						'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
					);
					$this->GodownKeeperModel->insert('bill_transaction_history',$history);
	
	
					$billPayment=array(
						'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
						'billId'=>$billId,
						'allocationId'=>$allocationId,
						'date'=>date('Y-m-d H:i:sa'),
						'paidAmount'=>$totalFsValue,
						'billAmount'=>$currentnetAmount,
						'balanceAmount'=>$totalPendingAmount,
						'paymentMode'=>'SR',
						'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
					);
					$this->GodownKeeperModel->insert('billpayments',$billPayment);//insert billpayment data

					$updatedBillInfo=$this->GodownKeeperModel->load('bills',$billId);
					$currentSrTotal=$updatedBillInfo[0]['fsSrAmt'];

					$currentSrTotal= round($currentSrTotal);
					if($currentSrTotal<=0){
						$oldStatus=$updatedBillInfo[0]['fsbillStatus'];
		       			$fsstatus=str_replace('SR', '', $oldStatus);
						$status=trim($fsstatus,',');
		    	   		$data_status = array('fsbillStatus' => $status);  
		    			$this->GodownKeeperModel->update('bills',$data_status,$billId);//update latest sr status
					}
				}
	    	}
		}
    }

    public function updateFSR(){
    	echo "thid";exit;
    	$billNo=$this->input->post('billNo');
    	$billId=$this->input->post('billId');
    	$srQty=$this->input->post('srQty');
    	$data['billDetail']=$this->GodownKeeperModel->load('billsdetails',$billId);
    	$eachQtyRate=$data['billDetail'][0]['netAmount']/$data['billDetail'][0]['qty'];


    	$returnAmt=round($srQty*$eachQtyRate,2);
    	$fsReturnQty=$data['billDetail'][0]['fsReturnQty'];
    	
    	//If Deliveryman SR qty is less
    	if(!($srQty>$fsReturnQty)){
    		$shortQty=$fsReturnQty-$srQty;
    		$billDetail=array('fsReturnQty' => 0,'fsReturnAmt' => $returnAmt,'gkStatus' => 1,'fsGkShortQty' => $shortQty);
	    	$res=$this->GodownKeeperModel->update('billsdetails',$billDetail,$billId);
	    	if($res>0){
	    		  $data['bills']=$this->GodownKeeperModel->billsByCode('billsdetails',$id);
        		  $this->load->view('Godownkeeper/BillDetailsView',$data);
	    	}else{
	    		echo "fail";
	    	}
	    	//If Deliveryman SR qty is equal
    	}else if($srQty==$fsReturnQty){
    		$billDetail=array('fsReturnQty' => 0,'fsReturnAmt' => $returnAmt,'gkStatus' => 1);
	    	$res=$this->GodownKeeperModel->update('billsdetails',$billDetail,$billId);
	    	if($res>0){
	    		$data['bills']=$this->GodownKeeperModel->billsByCode('billsdetails',$id);
        		$this->load->view('Godownkeeper/BillDetailsView',$data);
	    	}else{
	    		echo "fail";
	    	}
    	}
    }

    public function updateAllFsr(){
    	$bill_id=trim($this->input->post('billId'));
		$allocationId=trim($this->input->post('allocationId'));
    	$billInfo=$this->GodownKeeperModel->load('bills',$bill_id);
    	$billDetails=$this->GodownKeeperModel->retailerBillsByCode('billsdetails',$bill_id);
    	$totalFsValue=0;

		$totalTaxableValue=0;
		$totalTaxAmount=0;
		$totalCessAmount=0;
    	foreach($billDetails as $detail){
    		if($detail['gkStatus'] != 1){
		    	$billId=$detail['id'];
		    	$prodCode=$detail['productCode'];
				$prodName=$detail['productName'];
				$prodMrp=$detail['mrp'];
		    	$srQty=$detail['qty'];
		    	
				$data['billDetail']=$this->GodownKeeperModel->load('billsdetails',$billId);
		    	$eachQtyRate=$data['billDetail'][0]['netAmount']/$data['billDetail'][0]['qty'];

				//cess amount
				$cessAmount=$data['billDetail'][0]['cessAmount']/$data['billDetail'][0]['qty'];
				$cessAmount=round($srQty*$cessAmount,2);

				//tax amount
				$taxAmount=$data['billDetail'][0]['taxAmount']/$data['billDetail'][0]['qty'];
				$taxAmount=round($srQty*$taxAmount,2);

				//taxable amount
				$taxableValue=$data['billDetail'][0]['taxableValue']/$data['billDetail'][0]['qty'];
				$taxableValue=round($srQty*$taxableValue,2);

				$totalTaxableValue=$totalTaxableValue+$taxableValue;
				$totalTaxAmount=$totalTaxAmount+$taxAmount;
				$totalCessAmount=$totalCessAmount+$cessAmount;

		    	$returnAmt=round($srQty*$eachQtyRate,2);
		    	$totalFsValue=$totalFsValue+$returnAmt;
		    	$fsReturnQty=$data['billDetail'][0]['fsReturnQty'];
		    	
		    	//If Deliveryman SR qty is less
		    	if($srQty<$fsReturnQty){
		    		$shortQty=$fsReturnQty-$srQty;
		    		$billDetail=array('fsReturnQty' => 0,'gkReturnQty' => $srQty,'fsReturnAmt' => $returnAmt,'gkStatus' => 1,'fsGkShortQty' => $shortQty);
			    	$this->GodownKeeperModel->update('billsdetails',$billDetail,$billId);

					$isDeliverySlip=$this->GodownKeeperModel->load('bills', $bill_id);
					if(!empty($isDeliverySlip)){
						if($isDeliverySlip[0]['isDeliverySlipBill']==1){
							//for product return with code for delivery slip products
							$prodDet=$this->GodownKeeperModel->getDetailByCodeWithDetails('products',$prodName,$prodCode,(int)$prodMrp);
							if(!empty($prodDet)){
								$insertData=array(
									'billingId'=>$bill_id,
									'operationStatus'=>'add',
									'quantityInPcs'=>$srQty,
									'productId'=>$prodDet[0]['id'],
									'productName'=>$prodDet[0]['name'],
									'productCode'=>$prodDet[0]['productCode'],
									'quantity'=>$srQty,
									'quantityUnit'=>'pcs',
									'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
									'createdAt'=>date('Y-m-d H:i:sa')
								);
								// print_r($insertData);exit;
								$this->GodownKeeperModel->insert('deliveryslip_pending_for_billing',$insertData);
							}
						}
					}

			    	//If Deliveryman SR qty is equal
		    	}else if($srQty==$fsReturnQty){
		    		$shortQty=$fsReturnQty-$srQty;
		    		$billDetail=array('fsReturnQty' => 0,'gkReturnQty' => $srQty,'fsReturnAmt' => $returnAmt,'gkStatus' => 1,'fsGkShortQty' => $shortQty);
			    	$this->GodownKeeperModel->update('billsdetails',$billDetail,$billId);

					$isDeliverySlip=$this->GodownKeeperModel->load('bills', $bill_id);
					if(!empty($isDeliverySlip)){
						if($isDeliverySlip[0]['isDeliverySlipBill']==1){
							//for product return with code for delivery slip products
							$prodDet=$this->GodownKeeperModel->getDetailByCodeWithDetails('products',$prodName,$prodCode,(int)$prodMrp);
							if(!empty($prodDet)){
								$insertData=array(
									'billingId'=>$bill_id,
									'operationStatus'=>'add',
									'quantityInPcs'=>$srQty,
									'productId'=>$prodDet[0]['id'],
									'productName'=>$prodDet[0]['name'],
									'productCode'=>$prodDet[0]['productCode'],
									'quantity'=>$srQty,
									'quantityUnit'=>'pcs',
									'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
									'createdAt'=>date('Y-m-d H:i:sa')
								);
								// print_r($insertData);exit;
								$this->GodownKeeperModel->insert('deliveryslip_pending_for_billing',$insertData);
							}
						}
					}
		    	}
		    }
    	}

    	$totalSrAmt=$billInfo[0]['SRAmt'];
    	$pendingAmt=$billInfo[0]['pendingAmt'];
    	$creditAdjustmentAmt=$billInfo[0]['creditAdjustment'];

    	$totalFsValue=round($totalFsValue);

    	if($creditAdjustmentAmt>0){

			if($totalFsValue>0){
	    		$pendBillInfo=$this->GodownKeeperModel->load('bills',$bill_id);
	    		$currentNetAmt=$pendBillInfo[0]['netAmount'];
	    		$currentPending=$pendBillInfo[0]['pendingAmt'];
	    		$currentSrAmt=$pendBillInfo[0]['SRAmt'];
	    		$totalPendingAmount=$currentPending-$totalFsValue;
	    		$totalSrAmount=$currentSrAmt+$totalFsValue;
	    		
	    		$srUpdate=array('isFsrBill'=>1,'pendingAmt' => 0,'SRAmt'=>$currentNetAmt,'creditNoteRenewal'=>$creditAdjustmentAmt,'fsSrAmt'=>$currentNetAmt);
	    		$this->GodownKeeperModel->update('bills',$srUpdate,$bill_id);//update latest sr amount

				$history=array(
					'billId'=>$bill_id,
					'allocationId'=>$allocationId,
					'transactionAmount' =>$currentNetAmt,
					'transactionStatus' =>'FSR',
					'transactionMode' =>'dr',
					'transactionDate'=>date('Y-m-d H:i:sa'),
					'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
					'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
				);
				$this->GodownKeeperModel->insert('bill_transaction_history',$history);


				$billPayment=array(
					'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
					'billId'=>$bill_id,
					'allocationId'=>$allocationId,
					'date'=>date('Y-m-d H:i:sa'),
					'paidAmount'=>$currentNetAmt,
					'billAmount'=>$currentNetAmt,
					'balanceAmount'=>0,
					'paymentMode'=>'FSR',
					'taxableValue'=>$totalTaxableValue,
					'taxAmount'=>$totalTaxAmount,
					'cessAmount'=>$totalCessAmount,
					'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
				);
				$this->GodownKeeperModel->insert('billpayments',$billPayment);//insert billpayment data

			}
    	}else{
    		if($totalFsValue>0){
	    		$pendBillInfo=$this->GodownKeeperModel->load('bills',$bill_id);
				$currentNetAmt=$pendBillInfo[0]['netAmount'];
	    		$currentPending=$pendBillInfo[0]['pendingAmt'];
	    		$currentSrAmt=$pendBillInfo[0]['SRAmt'];
	    		$totalPendingAmount=$currentPending-$totalFsValue;
	    		$totalSrAmount=$currentSrAmt+$totalFsValue;
	    		
	    		$srUpdate=array('isFsrBill'=>1,'pendingAmt' => 0,'SRAmt'=>$totalSrAmount,'fsSrAmt'=>$totalFsValue);
	    		$this->GodownKeeperModel->update('bills',$srUpdate,$bill_id);//update latest sr amount

				$updatedBillInfo=$this->GodownKeeperModel->load('bills',$bill_id);
				$currentSrTotal=$updatedBillInfo[0]['fsSrAmt'];

				$currentSrTotal= round($currentSrTotal);
				if($currentSrTotal<=0){
					$oldStatus=$updatedBillInfo[0]['fsbillStatus'];
	       			$fsstatus=str_replace('SR', '', $oldStatus);
					$status=trim($fsstatus,',');
	    	   		$data_status = array('fsbillStatus' => $status);  
	    			$this->GodownKeeperModel->update('bills',$data_status,$bill_id);//update latest sr status
				}

				$history=array(
					'billId'=>$bill_id,
					'allocationId'=>$allocationId,
					'transactionAmount' =>$totalFsValue,
					'transactionStatus' =>'FSR',
					'transactionMode' =>'dr',
					'transactionDate'=>date('Y-m-d H:i:sa'),
					'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
					'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
				);
				$this->GodownKeeperModel->insert('bill_transaction_history',$history);


				$billPayment=array(
					'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
					'billId'=>$bill_id,
					'allocationId'=>$allocationId,
					'date'=>date('Y-m-d H:i:sa'),
					'paidAmount'=>$totalFsValue,
					'billAmount'=>$currentNetAmt,
					'balanceAmount'=>0,
					'paymentMode'=>'FSR',
					'taxableValue'=>$totalTaxableValue,
					'taxAmount'=>$totalTaxAmount,
					'cessAmount'=>$totalCessAmount,
					'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
				);
				$this->GodownKeeperModel->insert('billpayments',$billPayment);//insert billpayment data

			}
    	}
    }


    public function cancelFrsByGodownKeeper(){
    	$bill_id=trim($this->input->post('billId'));
    	$allocationId=trim($this->input->post('allocationId'));
    	
    	$bills=$this->GodownKeeperModel->load('bills',$bill_id);
    	// print_r($bills);
    	$billDetails=$this->GodownKeeperModel->retailerBillsByCode('billsdetails',$bill_id);
    	if(!empty($billDetails)){
    		$fsReturnQty=0;
	    	$gkReturnQty=0;
	    	$fsReturnAmt=0;
	    	$updateData=array(
	    		'fsReturnQty'=>0,
	    		'gkReturnQty'=>0,
	    		'fsReturnAmt'=>0
	    	);

	    	$updateBillData=array(
	    		'isFsrBill'=>0,
	    		'creditNoteRenewal'=>0,
	    		'fsbillStatus'=>"Billed",
	    		'fsSrAmt'=>0
	    	);
    		$this->GodownKeeperModel->update('bills',$updateBillData,$bill_id);
    		$this->GodownKeeperModel->updateByBillId('billsdetails',$updateData,$bill_id);
    		$this->GodownKeeperModel->deleteSrDetails('allocation_sr_details',$bill_id,$allocationId);
    	}
    }

    public function cancelNewBillFrsByGodownKeeper(){
    	$bill_id=trim($this->input->post('billId'));
    	$allocationId=trim($this->input->post('allocationId'));
    	
    	$bills=$this->GodownKeeperModel->load('bills',$bill_id);
    	if(!empty($bills)){
    		$fsReturnQty=0;
	    	$gkReturnQty=0;
	    	$fsReturnAmt=0;

	    	$updateBillData=array(
	    		'isFsrBill'=>0,
	    		'creditNoteRenewal'=>0,
	    		'fsbillStatus'=>"Billed",
	    		'fsSrAmt'=>0
	    	);
    		$this->GodownKeeperModel->update('bills',$updateBillData,$bill_id);
    	}
    }

    public function updateNewBillFsr(){
    	$bill_id=trim($this->input->post('billId'));
		$allocationId=trim($this->input->post('allocationId'));
    	$billInfo=$this->GodownKeeperModel->load('bills',$bill_id);
    	$totalFsValue=0;

		$totalTaxableValue=0;
		$totalTaxAmount=0;
		$totalCessAmount=0;
    	if(!empty($billInfo)){
    		$totalSrAmt=$billInfo[0]['SRAmt'];
	    	$pendingAmt=$billInfo[0]['pendingAmt'];
	    	$netAmount=$billInfo[0]['netAmount'];
	    	$creditAdjustmentAmt=$billInfo[0]['creditAdjustment'];

			$billDetails=$this->GodownKeeperModel->retailerBillsByCode('billsdetails',$bill_id);
			if(!empty($billDetails)){
				foreach($billDetails as $detail){
					$billId=$detail['id'];
					$prodCode=$detail['productCode'];
					$srQty=$detail['qty'];
					$data['billDetail']=$this->GodownKeeperModel->load('billsdetails',$billId);
					$eachQtyRate=$data['billDetail'][0]['netAmount']/$data['billDetail'][0]['qty'];

					//cess amount
					$cessAmount=$data['billDetail'][0]['cessAmount']/$data['billDetail'][0]['qty'];
					$cessAmount=round($srQty*$cessAmount,2);

					//tax amount
					$taxAmount=$data['billDetail'][0]['taxAmount']/$data['billDetail'][0]['qty'];
					$taxAmount=round($srQty*$taxAmount,2);

					//taxable amount
					$taxableValue=$data['billDetail'][0]['taxableValue']/$data['billDetail'][0]['qty'];
					$taxableValue=round($srQty*$taxableValue,2);

					$totalTaxableValue=$totalTaxableValue+$taxableValue;
					$totalTaxAmount=$totalTaxAmount+$taxAmount;
					$totalCessAmount=$totalCessAmount+$cessAmount;
				}
			}

	    	if($creditAdjustmentAmt>0){
		    	$srUpdate=array('isFsrBill'=>1,'pendingAmt' => 0,'SRAmt'=>$netAmount,'creditNoteRenewal'=>$creditAdjustmentAmt,'fsSrAmt'=>$netAmount);
		    	$this->GodownKeeperModel->update('bills',$srUpdate,$bill_id);//update latest sr amount

				$history=array(
					'billId'=>$bill_id,
					'allocationId'=>$allocationId,
					'transactionAmount' =>$netAmount,
					'transactionStatus' =>'FSR',
					'transactionMode' =>'dr',
					'transactionDate'=>date('Y-m-d H:i:sa'),
					'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
					'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
				);
				$this->GodownKeeperModel->insert('bill_transaction_history',$history);


				$billPayment=array(
					'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
					'billId'=>$bill_id,
					'allocationId'=>$allocationId,
					'date'=>date('Y-m-d H:i:sa'),
					'paidAmount'=>$netAmount,
					'billAmount'=>$netAmount,
					'balanceAmount'=>0,
					'paymentMode'=>'FSR',
					'taxableValue'=>$totalTaxableValue,
					'taxAmount'=>$totalTaxAmount,
					'cessAmount'=>$totalCessAmount,
					'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
				);
				$this->GodownKeeperModel->insert('billpayments',$billPayment);//insert billpayment data
				
	    	}else{
	    		$srUpdate=array('isFsrBill'=>1,'pendingAmt' =>0,'SRAmt'=>$netAmount,'fsSrAmt'=>$netAmount);
	    		$this->GodownKeeperModel->update('bills',$srUpdate,$bill_id);//update latest sr amount

				$history=array(
					'billId'=>$bill_id,
					'allocationId'=>$allocationId,
					'transactionAmount' =>$netAmount,
					'transactionStatus' =>'FSR',
					'transactionMode' =>'dr',
					'transactionDate'=>date('Y-m-d H:i:sa'),
					'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
					'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
				);
				$this->GodownKeeperModel->insert('bill_transaction_history',$history);


				$billPayment=array(
					'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
					'billId'=>$bill_id,
					'allocationId'=>$allocationId,
					'date'=>date('Y-m-d H:i:sa'),
					'paidAmount'=>$netAmount,
					'billAmount'=>$netAmount,
					'balanceAmount'=>0,
					'paymentMode'=>'FSR',
					'taxableValue'=>$totalTaxableValue,
					'taxAmount'=>$totalTaxAmount,
					'cessAmount'=>$totalCessAmount,
					'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
				);
				$this->GodownKeeperModel->insert('billpayments',$billPayment);//insert billpayment data
	    	}
    	}
    }
}