<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BillTransactionController extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('BillTransactionModel');
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

	public function index(){
		$data['company']=$this->BillTransactionModel->getdata('company');
        $data['bank']=$this->BillTransactionModel->getdata('bank');
        $data['emp']=$this->BillTransactionModel->getdataActive('employee');
		$data['bills']=$this->BillTransactionModel->getBills('bills');
		$this->load->view('admin/billTransactionView',$data);
	}

	public function changeNeftChequeTransactions(){
		// $data['bills']=$this->BillTransactionModel->getBills('bills');
		$this->load->view('admin/uploadBillTransactionView');
	}
     
    public function neftChequeTransactionDataUploading(){
        $fileName=$_FILES['billFile']['name'];
        $fileType=$_FILES['billFile']['type'];
        $fileTempName=$_FILES['billFile']['tmp_name'];

        $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        if(isset($fileName) && in_array($fileType, $file_mimes)) {
            $arr_file = explode('.', $fileName); 
            $extension = end($arr_file);

            if('csv' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else if ('xlsx'){
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            }

            $reader->setReadDataOnly(true);
            $objPHPExcel = $reader->load($fileTempName);
            
            $worksheet = $objPHPExcel->getSheet(0);
            $highestRow = $worksheet->getHighestRow(); 
            $highestColumn = $worksheet->getHighestColumn(); 

            $billNumber="";
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
            $cnt=0;
            for ($row = 2; $row <= $highestRow; ++$row) {
                $cnt++;
                
                $transactionId = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
				$billId = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                $billNo = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                $retailerName = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
				$paymentMode = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
				$receiveDate = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
				$date = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
				$number = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
				$bank = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());

				$chequeNeftdate="";
				if(!empty($date) && $date !=='Bill Date'){
                    $billDate =str_replace("/","-",$date);
                    $date = ($billDate - 25569) * 86400;
                    $chequeNeftdate=date('Y-m-d', $date);//convert date from excel data
                }

				$chequeNeftReceiveDate="";
				if(!empty($receiveDate) && $receiveDate !=='Bill Date'){
                    $billDate =str_replace("/","-",$receiveDate);
                    $date = ($billDate - 25569) * 86400;
                    $chequeNeftReceiveDate=date('Y-m-d', $date);//convert date from excel data
                }

				$status="";
				if($paymentMode==="Cheque"){
					$status="New";
					$readData=array(
						'id'=>$transactionId,
						'billId'=>$billId,
						'billNo'=>$billNo,
						'retailerName'=>$retailerName,
						'paymentMode'=>$paymentMode,
						'chequeNo'=>$number,
						'chequeBank'=>$bank,
						'chequeStatus'=>$status,
						'chequeDate'=>$chequeNeftdate,
						'chequeReceivedDate'=>$chequeNeftReceiveDate,
						'chequeStatusDate'=>$chequeNeftReceiveDate
					);
					$this->BillTransactionModel->update('billpayments',$readData,$transactionId);

				}else if($paymentMode==="NEFT"){
					$status="Received";
					$readData=array(
						'id'=>$transactionId,
						'billId'=>$billId,
						'billNo'=>$billNo,
						'retailerName'=>$retailerName,
						'paymentMode'=>$paymentMode,
						'neftNo'=>$number,
						'chequeStatus'=>$status,
						'neftDate'=>$chequeNeftdate,
						'chequeReceivedDate'=>$chequeNeftReceiveDate,
						'chequeStatusDate'=>$chequeNeftReceiveDate
					);
					$this->BillTransactionModel->update('billpayments',$readData,$transactionId);
				}

				
				

                // if($transactionId !==""){
                // 	$billTransactionExist=$this->BillTransactionModel->load('billpayments',$transactionId);
	            //     if(!empty($billTransactionExist)){
	            //         $data = array(
	            //           'billNo'=>$billNo,
	            //           'retailerName'=>$retailerName
	            //         );
	            //         $this->BillTransactionModel->update('billpayments',$data,$transactionId);
	            //     }
                // }
            }
        }
        redirect('admin/BillTransactionController/changeNeftChequeTransactions');
    }

	public function billSrManagement(){
		$data['companySerial']=$this->BillTransactionModel->getCompanySerialNo('bill_serial_manage');
		$this->load->view('admin/billNoSrManageView',$data);
	}

	public function getAllTransactionView(){
		$billNo=trim($this->input->post('billNo'));
		// echo $billNo;
		if($billNo !==""){
			$billsData=$this->BillTransactionModel->findBills('bills',$billNo);
			if(!empty($billsData)){
				$billId=$billsData[0]['id'];
				// $billId=trim($this->input->post('billId'));
				$checkDatetail=$this->BillTransactionModel->load('bills',$billId);
				if(!empty($checkDatetail)){
					$cash=$this->BillTransactionModel->getSumByType('billpayments',$billId,'Cash');
					$cheque=$this->BillTransactionModel->getSumByType('billpayments',$billId,'Cheque');
					$neft=$this->BillTransactionModel->getSumByType('billpayments',$billId,'NEFT');
					$cd=$this->BillTransactionModel->getSumByType('billpayments',$billId,'CD');
					$sr=$checkDatetail[0]['SRAmt'];
					$ofcAdj=$this->BillTransactionModel->getSumByType('billpayments',$billId,'Office Adjustment');
					$otherAdj=$this->BillTransactionModel->getSumByType('billpayments',$billId,'Other Adjustment');
					$debit=$this->BillTransactionModel->getSumByType('billpayments',$billId,'Debit To Employee');

					// $checkChequeStatus=$this->BillTransactionModel->checkChequeStatus('billpayments',$billNo);

					$checkNeftStatus=$this->BillTransactionModel->checkNeftStatus('billpayments',$billId);

					$checkChequeStatus=$this->BillTransactionModel->checkChequeStatus('billpayments',$billId);

					$cashAmt=$this->BillTransactionModel->getSumByOfficeType('allocations_officebills',$billId,'cash');
					$officeAmt=$this->BillTransactionModel->getSumByOfficeType('allocations_officebills',$billId,'cleared');;
					// $fsrAmt=$this->BillTransactionModel->getSumByOfficeType('allocations_officebills',$billId,'fsr');;
					
					$totalCash=0;
					$totalFsr=0;
					$totalQA=0;
					if(!empty($cashAmt)){
						$totalCash=$cashAmt[0]['amt'];
					}

					if(!empty($officeAmt)){
						$totalQA=$officeAmt[0]['amt'];
					}

		?>	
					<input type="hidden" id="bill-id" name="bill-id" value="<?php echo $billId; ?>" onkeypress="return isNumber(event)">
					<tr>
						<td>Cash</td>
						<td class="text-right"><?php echo number_format($cash[0]['amt']+$totalCash); ?></td>
						<td><input type="text" id="cashAmtType" name="cashAmtType" onkeypress="return isNumber(event)">
						
						<input type="hidden" id="amt_cash" name="amt_cash" value="<?php echo ($cash[0]['amt']+$totalCash); ?>"></td>
						<td><input type="text" id="cashRemark" name="cashRemark"></td>
						<td><button id="cashAmtId" class="btn btn-xs btn-primary" width="50%">Save</button></td>
					<tr>
					<tr>
						<td>Cheque</td>
						<td class="text-right"><?php echo number_format($cheque[0]['amt']); ?></td>
						<td><input type="text" id="chequeAmtType" name="chequeAmtType" onkeypress="return isNumber(event)">
						<input type="hidden" id="amt_cheque" name="amt_cheque" value="<?php echo $cheque[0]['amt']; ?>"></td>
						<td><input type="text" id="chequeRemark" name="chequeRemark"></td>
						
					<?php if(empty($checkChequeStatus)){ ?>
						<td><button id="chequeAmtId" class="btn btn-xs btn-primary" >Save</button></td>
					<?php }else{
						if(!empty($checkChequeStatus)){
							if($checkChequeStatus[0]['chequeStatus']=="Cleared"){
					?>
								<td><button id="chequeAmtId" class="btn btn-xs btn-primary" >Save</button></td>
					<?php	}else{
								echo "<td>Please clear all Cheques</td>";
							}
						}else{
							echo "<td>Please clear all Cheques</td>";
						}
						
					} ?>
					<tr>
					<tr>
						<td>NEFT</td>
						<td class="text-right"><?php echo number_format($neft[0]['amt']); ?></td>
						<td><input type="text" id="neftAmtType" name="neftAmtType" onkeypress="return isNumber(event)">
						<input type="hidden" id="amt_neft" name="amt_neft" value="<?php echo $neft[0]['amt']; ?>"></td>
						<td><input type="text" id="neftRemark" name="neftRemark"></td>

					<?php if(empty($checkNeftStatus)){ ?>
						<td><button id="neftAmtId" class="btn btn-xs btn-primary" >Save</button></td>
					<?php }else{
						if(!empty($checkNeftStatus)){
							if($checkNeftStatus[0]['chequeStatus']=="Received"){
					?>
								<td><button id="neftAmtId" class="btn btn-xs btn-primary" >Save</button></td>
					<?php	}else{
								echo "<td>Please clear all NEFT's</td>";
							}
						}else{
							echo "<td>Please clear all NEFT's</td>";
						}
						
					} ?>
					<tr>
					<tr>
						<td>SR</td>
						<td class="text-right"><?php echo number_format($sr); ?></td>
						<td><input type="text" id="srAmtType" name="srAmtType" onkeypress="return isNumber(event)">
						<input type="hidden" id="amt_sr" name="amt_sr" value="<?php echo ($sr); ?>"></td>
						<td><input type="text" id="srRemark" name="srRemark"></td>
						<td><button id="srAmtId" class="btn btn-xs btn-primary" >Save</button></td>
					<tr>
					<tr>
						<td>Office Adjustment</td>
						<td class="text-right"><?php echo number_format($ofcAdj[0]['amt']); ?></td>
						<td><input type="text" id="officeAdjAmtType" name="officeAdjAmtType" onkeypress="return isNumber(event)">
						<input type="hidden" id="amt_ofcAdj" name="amt_ofcAdj" value="<?php echo ($ofcAdj[0]['amt']); ?>"></td>
						<td><input type="text" id="ofcAdjRemark" name="ofcAdjRemark"></td>
						<td><button id="ofcAdjAmtId" class="btn btn-xs btn-primary" >Save</button></td>
					<tr>
					<tr>
						<td>Other Adjustment</td>
						<td class="text-right"><?php echo number_format($otherAdj[0]['amt']); ?></td>
						<td><input type="text" id="otherAdjAmtType" name="otherAdjAmtType" onkeypress="return isNumber(event)">
						<input type="hidden" id="amt_otherAdj" name="amt_otherAdj" value="<?php echo ($otherAdj[0]['amt']); ?>"></td>
						<td><input type="text" id="otherAdjRemark" name="otherAdjRemark"></td>
						<td><button id="otrAdjAmtId" class="btn btn-xs btn-primary" >Save</button></td>
					<tr>
					<tr>
						<td>CD</td>
						<td class="text-right"><?php echo number_format($cd[0]['amt']); ?></td>
						<td><input type="text" id="cdAmtType" name="cdAmtType" onkeypress="return isNumber(event)">
						<input type="hidden" id="amt_cd" name="amt_cd" value="<?php echo ($cd[0]['amt']); ?>"></td>
						<td><input type="text" id="cdRemark" name="cdRemark"></td>
						<td><button id="cdAmtId" class="btn btn-xs btn-primary" >Save</button></td>
					<tr>
					<tr>
						<td>Debit</td>
						<td class="text-right"><?php echo number_format($debit[0]['amt']); ?></td>
						<td><input type="text" id="debitAmtType" name="debitAmtType" onkeypress="return isNumber(event)">
						<input type="hidden" id="amt_debit" name="amt_debit" value="<?php echo ($debit[0]['amt']); ?>"></td>
						<td><input type="text" id="debitRemark" name="debitRemark"></td>
						<td><button id="debitAmtId" class="btn btn-xs btn-primary" >Save</button></td>
					<tr>
		<?php
				}
			}
		}
	}

	public function receiveAmt(){
		$userId = $this->session->userdata[$this->projectSessionName]['id'];
		$amt=trim($this->input->post('amt'));
		$type=trim($this->input->post('type'));
		$remark=trim($this->input->post('remark'));
		$otp=$this->generateOtp();
		// $this->sendMsg($amt,$type,$otp);
		$billId=trim($this->input->post('billId'));
		$billsData=$this->BillTransactionModel->load('bills',$billId);

		$billNo=$billsData[0]['billNo'];
		$billDate=date("d-M-Y", strtotime($billsData[0]['date']));
		$retailerName=$billsData[0]['retailerName'];
		$route=$billsData[0]['routeName'];
		$salesman=$billsData[0]['salesman'];
		$pendingAmt=$billsData[0]['pendingAmt'];
		
		$retailerGst="";
		if($billsData[0]['isDeliverySlipBill']==1){
			$retailerGst="";
		}else{
			$retailerCode=$this->BillTransactionModel->loadRetailer($billsData[0]['retailerCode']);
			if(!empty($retailerCode)){
				$retailerGst=$retailerCode[0]['gstIn'];
			}
			
		}
		
		$res=array(
			'otp'=>$otp,
			'amt'=>$amt,
			'amtType'=>$type,
			'remark'=>$remark,
			'billNo'=>$billNo,
			'billDate'=>$billDate,
			'retailerName'=>$retailerName,
			'route'=>$route,
			'salesman'=>$salesman,
			'pendingAmt'=>$pendingAmt,
			'gst'=>$retailerGst
		);
		echo json_encode($res);
	}

	public function generateOtp(){
		$_len=6;
	    $_numerics   = '123456789';                           
	    $_container = $_numerics;   
	    $otp = '';  
	    for($i = 0; $i < $_len; $i++) {          
	        $_rand = rand(0, strlen($_container) - 1);                 
	        $otp .= substr($_container, $_rand, 1);              
	    }
	    return $otp;       
	}

	public function sendMsg($amt,$type,$otp){
		$mobile="8446107727";
		$desc="OTP for accept request to submit ".$type." amount Rs.".$amt." is : ".$otp;
        $url="https://api.msg91.com/api/sendhttp.php?authkey=291106AG8eCyzDe5d626f5c&mobiles=".$mobile."&country=91&message=".$desc."&sender=TESTIN&route=4";
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        $contents = curl_exec($ch);
        curl_close($ch);
	}

	public function updateBillAmount(){
		$updatedAt=date('Y-m-d H:i:sa');
		$userId = trim($this->session->userdata[$this->projectSessionName]['id']);

		$empInfo=$this->BillTransactionModel->load('employee',$userId);
		$employeeName="";
		if(!empty($empInfo)){
			$employeeName=$empInfo[0]['name'];
		}

		
		$amount=trim($this->input->post('amount'));
		$oldAmount=trim($this->input->post('oldAmount'));
		$amountType=trim($this->input->post('amountType'));
		$billId=trim($this->input->post('billId'));

		$remark=trim($this->input->post('finalRemark'));
		$billInfo=$this->BillTransactionModel->load('bills',$billId);
		$latestTransactionAmount=$oldAmount-$amount;
		$remark= "Transaction edited by owner ".$employeeName.' for amount '.abs($amount).'. Remark : '.$remark;

		// echo $latestTransactionAmount;exit;

// 		echo $amountType.' '.

		if(!empty($billInfo)){
			$netAmount=$billInfo[0]['netAmount'];
			$pendingAmt=$billInfo[0]['pendingAmt'];
			$receiveAmt=$billInfo[0]['receivedAmt'];
			$srAmount=$billInfo[0]['SRAmt'];
			$officeAdjustmentBillAmount=$billInfo[0]['officeAdjustmentBillAmount'];
			$otherAdjustment=$billInfo[0]['otherAdjustment'];
			$debit=$billInfo[0]['debit'];
			$cd=$billInfo[0]['cd'];

			if($amountType=="Cash"){
				if($receiveAmt>0){
					$updatedCashAmt=$receiveAmt-$latestTransactionAmount;
					$pendingAmt=$pendingAmt+$latestTransactionAmount;
					// echo $receiveAmt.' '.$updatedCashAmt.' '.$pendingAmt;exit;

					$updateBills=array('receivedAmt'=>($updatedCashAmt),'pendingAmt'=>$pendingAmt);
					$amount=(-$latestTransactionAmount);
					$updateBillDetails=array(
						'billId'=>$billId,
						'empId'=>$userId,
						'date'=>$updatedAt,
						'paidAmount'=>$amount,
						'billAmount'=>$netAmount,
						'balanceAmount'=>$pendingAmt,
						'paymentMode'=>$amountType,
						'updatedBy'=>$userId
					);
					
					$this->BillTransactionModel->update('bills',$updateBills,$billId);
					if($this->db->affected_rows() > 0){
						$this->BillTransactionModel->insert('billpayments',$updateBillDetails);
						if($this->db->affected_rows() > 0){
							$history=array(
                                'billId'=>$billId,
                                'transactionStatus' =>'Cash',
								'transactionAmount' =>$amount,
								'transactionMode'=>'cr',
								'remark'=>$remark,
                                'transactionDate'=>date('Y-m-d H:i:sa'),
								'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                            );
                            $this->BillTransactionModel->insert('bill_transaction_history',$history);
							echo "Record updated";
						}else{
							echo "Record not updated";
						}
					}
				}else{
					echo "No cash transaction done earlier";
				}
				
			}else if($amountType=="Cheque"){
				if($receiveAmt>0){
					$updatedCashAmt=$receiveAmt-$latestTransactionAmount;
					$pendingAmt=$pendingAmt+$latestTransactionAmount;

					// echo ($receiveAmt).' '.$updatedCashAmt.' '.$pendingAmt;exit;

					$updateBills=array('receivedAmt'=>($updatedCashAmt),'pendingAmt'=>$pendingAmt);
					$amount=(-$latestTransactionAmount);
					$updateBillDetails=array(
						'billId'=>$billId,
						'empId'=>$userId,
						'date'=>$updatedAt,
						'paidAmount'=>$amount,
						'billAmount'=>$netAmount,
						'tallyStatus'=>'Cheque_Change',
						'balanceAmount'=>$pendingAmt,
						'paymentMode'=>$amountType,
						'updatedBy'=>$userId
					);
					
					$this->BillTransactionModel->update('bills',$updateBills,$billId);
					if($this->db->affected_rows() > 0){
						$this->BillTransactionModel->insert('billpayments',$updateBillDetails);
						if($this->db->affected_rows() > 0){
							$history=array(
                                'billId'=>$billId,
                                'transactionStatus' =>'Cheque',
								'transactionAmount' =>$amount,
								'transactionMode'=>'cr',
								'remark'=>$remark,
                                'transactionDate'=>date('Y-m-d H:i:sa'),
								'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                            );
                            $this->BillTransactionModel->insert('bill_transaction_history',$history);
							echo "Record updated";
						}else{
							echo "Record not updated";
						}
					}
				}else{
					echo "No cash transaction done earlier";
				}
			}else if($amountType=="NEFT"){
				if($receiveAmt>0){
					$updatedCashAmt=$receiveAmt-$latestTransactionAmount;
					$pendingAmt=$pendingAmt+$latestTransactionAmount;
					// echo ($receiveAmt).' '.$updatedCashAmt.' '.$pendingAmt;exit;

					$updateBills=array('receivedAmt'=>($updatedCashAmt),'pendingAmt'=>$pendingAmt);
					$amount=(-$latestTransactionAmount);
					$updateBillDetails=array(
						'billId'=>$billId,
						'empId'=>$userId,
						'date'=>$updatedAt,
						'paidAmount'=>$amount,
						'billAmount'=>$netAmount,
						'tallyStatus'=>'NEFT_Change',
						'balanceAmount'=>$pendingAmt,
						'paymentMode'=>$amountType,
						'updatedBy'=>$userId
					);
					
					$this->BillTransactionModel->update('bills',$updateBills,$billId);
					if($this->db->affected_rows() > 0){
						$this->BillTransactionModel->insert('billpayments',$updateBillDetails);
						if($this->db->affected_rows() > 0){
							$history=array(
                                'billId'=>$billId,
                                'transactionStatus' =>'NEFT',
								'transactionMode'=>'cr',
								'transactionAmount' =>$amount,
								'remark'=>$remark,
                                'transactionDate'=>date('Y-m-d H:i:sa'),
								'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                            );
                            $this->BillTransactionModel->insert('bill_transaction_history',$history);
							echo "Record updated";
						}else{
							echo "Record not updated";
						}
					}
				}else{
					echo "No cash transaction done earlier";
				}
			}else if($amountType=="SR"){
				if($srAmount>0){
					$updatedSrAmt=$srAmount-$latestTransactionAmount;
					$pendingAmt=$pendingAmt+$latestTransactionAmount;
					$updateBills=array('SRAmt'=>($updatedSrAmt),'pendingAmt'=>$pendingAmt);
					$amount=(-$latestTransactionAmount);
					$updateBillDetails=array(
						'billId'=>$billId,
						'empId'=>$userId,
						'date'=>$updatedAt,
						'paidAmount'=>$amount,
						'billAmount'=>$netAmount,
						'balanceAmount'=>$pendingAmt,
						'paymentMode'=>$amountType,
						'updatedBy'=>$userId
					);
					
					$this->BillTransactionModel->update('bills',$updateBills,$billId);
					if($this->db->affected_rows() > 0){
						$this->BillTransactionModel->insert('billpayments',$updateBillDetails);
						if($this->db->affected_rows() > 0){
							$history=array(
                                'billId'=>$billId,
                                'transactionStatus' =>'SR',
								'transactionAmount' =>$amount,
								'transactionMode'=>'cr',
								'remark'=>$remark,
                                'transactionDate'=>date('Y-m-d H:i:sa'),
								'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                            );
                            $this->BillTransactionModel->insert('bill_transaction_history',$history);
							echo "Record updated";
						}else{
							echo "Record not updated";
						}
					}
				}else{
					echo "No transaction done for SR earlier";
				}
			}else if($amountType=="Office Adjustment"){
				if($officeAdjustmentBillAmount>0){
					$updatedOfficeAdj=$officeAdjustmentBillAmount-$latestTransactionAmount;
					$pendingAmt=$pendingAmt+$latestTransactionAmount;
					$updateBills=array('officeAdjustmentBillAmount'=>($updatedOfficeAdj),'pendingAmt'=>$pendingAmt);
					$amount=(-$latestTransactionAmount);
					$updateBillDetails=array(
						'billId'=>$billId,
						'empId'=>$userId,
						'date'=>$updatedAt,
						'paidAmount'=>$amount,
						'billAmount'=>$netAmount,
						'balanceAmount'=>$pendingAmt,
						'paymentMode'=>$amountType,
						'updatedBy'=>$userId
					);
					
					$this->BillTransactionModel->update('bills',$updateBills,$billId);
					if($this->db->affected_rows() > 0){
						$this->BillTransactionModel->insert('billpayments',$updateBillDetails);
						if($this->db->affected_rows() > 0){
							$history=array(
                                'billId'=>$billId,
                                'transactionStatus' =>'Office Adjustment',
								'transactionAmount' =>$amount,
								'transactionMode'=>'cr',
								'remark'=>$remark,
                                'transactionDate'=>date('Y-m-d H:i:sa'),
								'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                            );
                            $this->BillTransactionModel->insert('bill_transaction_history',$history);
							echo "Record updated";
						}else{
							echo "Record not updated";
						}
					}
				}else{
					echo "No transaction done for Office Adjustmnt earlier";
				}
			}else if($amountType=="Other Adjustment"){
				if($otherAdjustment>0){
					$updatedOtherAdj=$otherAdjustment-$latestTransactionAmount;
					$pendingAmt=$pendingAmt+$latestTransactionAmount;
					$updateBills=array('otherAdjustment'=>($updatedOtherAdj),'pendingAmt'=>$pendingAmt);
					$amount=(-$latestTransactionAmount);

					$updateBillDetails=array(
						'billId'=>$billId,
						'empId'=>$userId,
						'date'=>$updatedAt,
						'paidAmount'=>$amount,
						'billAmount'=>$netAmount,
						'balanceAmount'=>$pendingAmt,
						'paymentMode'=>$amountType,
						'updatedBy'=>$userId
					);
					
					$this->BillTransactionModel->update('bills',$updateBills,$billId);
					if($this->db->affected_rows() > 0){
						$this->BillTransactionModel->insert('billpayments',$updateBillDetails);
						if($this->db->affected_rows() > 0){
							$history=array(
                                'billId'=>$billId,
                                'transactionStatus' =>'Other Adjustment',
								'transactionAmount' =>$amount,
								'transactionMode'=>'cr',
								'remark'=>$remark,
                                'transactionDate'=>date('Y-m-d H:i:sa'),
								'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                            );
                            $this->BillTransactionModel->insert('bill_transaction_history',$history);
							echo "Record updated";
						}else{
							echo "Record not updated";
						}
					}
				}else{
					echo "No transaction done for Other Adjustmnt earlier";
				}
			}else if($amountType=="CD"){
				if($cd>0){
					$updatedCd=$cd-$latestTransactionAmount;
					$pendingAmt=$pendingAmt+$latestTransactionAmount;
					$updateBills=array('cd'=>($updatedCd),'pendingAmt'=>$pendingAmt);
					$amount=(-$latestTransactionAmount);

					$updateBillDetails=array(
						'billId'=>$billId,
						'empId'=>$userId,
						'date'=>$updatedAt,
						'paidAmount'=>$amount,
						'billAmount'=>$netAmount,
						'balanceAmount'=>$pendingAmt,
						'paymentMode'=>$amountType,
						'updatedBy'=>$userId
					);
					
					$this->BillTransactionModel->update('bills',$updateBills,$billId);
					if($this->db->affected_rows() > 0){
						$this->BillTransactionModel->insert('billpayments',$updateBillDetails);
						if($this->db->affected_rows() > 0){
							$history=array(
                                'billId'=>$billId,
                                'transactionStatus' =>'CD',
								'transactionAmount' =>$amount,
								'transactionMode'=>'cr',
								'remark'=>$remark,
                                'transactionDate'=>date('Y-m-d H:i:sa'),
								'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                            );
                            $this->BillTransactionModel->insert('bill_transaction_history',$history);
							echo "Record updated";
						}else{
							echo "Record not updated";
						}
					}
				}else{
					echo "No transaction done for Cash Discount earlier";
				}
			}else if($amountType=="Debit To Employee"){
				if($debit>0){
					$updatedDebit=$debit-$latestTransactionAmount;
					$pendingAmt=$pendingAmt+$latestTransactionAmount;
					$updateBills=array('debit'=>($updatedDebit),'pendingAmt'=>$pendingAmt);
					$amount=(-$latestTransactionAmount);

					$updateBillDetails=array(
						'billId'=>$billId,
						'empId'=>$userId,
						'date'=>$updatedAt,
						'paidAmount'=>$amount,
						'billAmount'=>$netAmount,
						'balanceAmount'=>$pendingAmt,
						'paymentMode'=>$amountType,
						'updatedBy'=>$userId
					);
					
					$this->BillTransactionModel->update('bills',$updateBills,$billId);
					if($this->db->affected_rows() > 0){
						$this->BillTransactionModel->insert('billpayments',$updateBillDetails);
						if($this->db->affected_rows() > 0){
							$history=array(
                                'billId'=>$billId,
                                'transactionStatus' =>'Debit To Employee',
								'transactionAmount' =>$amount,
								'transactionMode'=>'cr',
								'remark'=>$remark,
                                'transactionDate'=>date('Y-m-d H:i:sa'),
								'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                            );
                            $this->BillTransactionModel->insert('bill_transaction_history',$history);
							echo "Record updated";
						}else{
							echo "Record not updated";
						}
					}
				}else{
					echo "No transaction done for Debit earlier";
				}
			}
		}
	}

	public function updateBillsSerial(){
		$srId=trim($this->input->post('srId'));
		$compId=trim($this->input->post('compId'));
		$serialValue=trim($this->input->post('serialValue'));
		if(empty($srId) && !empty($compId)){
			$insData=array('serialStartWith'=>$serialValue,'companyId'=>$compId);
			$this->BillTransactionModel->insert('bill_serial_manage',$insData);
		}else{
			$upData=array('serialStartWith'=>$serialValue,'companyId'=>$compId);
			$this->BillTransactionModel->update('bill_serial_manage',$upData,$srId);
		}
	}

	public function getBillTransaction(){
		$billNo=trim($this->input->post('billNo'));
		if(!empty($billNo)){
			$billDetail=$this->BillTransactionModel->getBillDetails('bills',$billNo);
			$billId=$billDetail[0]['id'];
			
			$billTransaction=$this->BillTransactionModel->getBillTransaction('billpayments',$billId);
			$id=0;

			if(!empty($billTransaction)){
				foreach ($billTransaction as $data) {
					$id++;
					print_r($billTransaction);
		?>		<tr>

					<td><?php echo $id; ?></td>
					<td><?php echo $billNo; ?></td>
					<td><?php echo $data['billAmount']; ?></td>
					<td><?php echo $data['paymentMode']; ?></td>
					<td>
						<input name="paidAmount" value="<?php echo $data['paidAmount']; ?>" type="text"/>
					</td>
					<td><?php echo date('d-m-Y',strtotime($data['date'])); ?></td>
					<td>
						<button  id="editEntryId" data-id="<?php echo $data['id']; ?>" data-billId="<?php echo $data['billId']; ?>" data-paymentMode="<?php echo $data['paymentMode']; ?>" class="btn btn-xs btn-primary"><i class="material-icons">edit</i></button>
						<button id="deleteEntryId" data-id="<?php echo $data['id']; ?>" class="btn btn-xs btn-danger"><i class="material-icons">delete</i></button>
					</td>
				</tr>
		<?php	
				}
			}else{
				echo '<tr><td colspan="6">';
				echo 'No record found.';
				echo '</td></tr>';
			}
		}else{
			echo '<tr><td colspan="6">';
			echo 'No record found.';
			echo '</td></tr>';	
		}
	}
}

?>