<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdhocBillController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('AllocationByManagerModel');
        $this->load->library('session');
        date_default_timezone_set('Asia/Kolkata');
        ini_set('memory_limit', '-1');

        if(isset($this->session->userdata['codeKeyData'])) {
			$this->projectSessionName= $this->session->userdata['codeKeyData']['codeKeyValue'];
		}else{
			$this->load->view('LoginView');
		}
    }
    
    public function addBillToAllocation(){
        $currentBillNo=trim($this->input->post('currentBillNo'));
        $currentBillRetailer=trim($this->input->post('currentBillRetailer'));
        $currentBillAmount=trim($this->input->post('currentBillAmount'));
        $currentBillCompany=trim($this->input->post('currentBillCompany'));

        $allocationTotalAmount=0;
        $allocationBillCount=0;
        $allocationSalesman="";

        $checkBill=$this->AllocationByManagerModel->loadCurrentBillsByNo('bills',$currentBillNo);
        
        $currentBillId=0;

        if(empty($checkBill)){
              $currentDate=date('Y-m-d');
              $insertData=array('manuallyAddedBill'=>1,'billNo'=>$currentBillNo,'date'=>$currentDate,'retailerName'=>$currentBillRetailer,'compName'=>$currentBillCompany,'netAmount'=>$currentBillAmount,'pendingAmt'=>$currentBillAmount);
              $this->AllocationByManagerModel->insert('bills',$insertData);
              if($this->db->affected_rows()>0){
                  $currentBillId=$this->db->insert_id();
              }else{
                  exit;
              }
        }else{
          exit;
        }

        if($currentBillId==0){
          exit;
        }

        // $currentBillId=trim($this->input->post('currentBillId'));
        $allocationId=trim($this->input->post('allocationId'));
        $currentPendingAmt=$currentBillAmount;
        $currentBillId=$currentBillId;

        $billInfo=$this->AllocationByManagerModel->load('bills',$currentBillId);
        if($billInfo[0]['netAmount']==$currentPendingAmt){
            if(!empty($allocationId)){
                $allocationDetails=$this->AllocationByManagerModel->load('allocations',$allocationId);

                if(!empty($currentBillId)){
                    $billInformation=$this->AllocationByManagerModel->getUnallocatedBillById('bills',$currentBillId);
                    if(!empty($billInformation)){
                        $allocationSalesman=$allocationDetails[0]['allocationSalesman'].','.$billInformation[0]['salesman'];
                        $allocationTotalAmount=$allocationDetails[0]['allocationTotalAmount']+$billInformation[0]['pendingAmt'];
                        $allocationBillCount=1+$allocationDetails[0]['allocationBillCount'];

                        $updateAllocationDetails=array(
                            "allocationTotalAmount"=>$allocationTotalAmount,
                            "allocationBillCount"=>$allocationBillCount,
                            "allocationSalesman"=>$allocationSalesman
                        );
                        $this->AllocationByManagerModel->update('allocations',$updateAllocationDetails,$allocationId);
                    }       
                }

                if(!empty($allocationDetails)){
                    $insAllocationDetail=array('billId'=>$currentBillId,'allocationId'=>$allocationId,'billStatus'=>1);
                    $this->AllocationByManagerModel->insert('allocationsbills',$insAllocationDetail);
                    if($this->db->affected_rows()>0){
                        
                        $updBills=array('billCurrentStatus'=>'Allocated in '.$allocationDetails[0]['allocationCode'],'billType'=>'allocatedbillCurrent','isAllocated'=>1,'isBilled'=>'0','isLostBill'=>'0');
                        $this->AllocationByManagerModel->update('bills',$updBills,$currentBillId);
                        if($this->db->affected_rows()>0){
                            $history=array(
                                'billId'=>$currentBillId,
                                'allocationId'=>$allocationId,
                                'transactionStatus' =>'Allocated',
                                'transactionDate'=>date('Y-m-d H:i:sa'),
                                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                            );
                            $this->AllocationByManagerModel->insert('bill_transaction_history',$history);
                            
                            echo "Record inserted";
                        }else{
                            echo "Record not inserted";
                        }
                    }
                }
            }

        }else{
            if(!empty($allocationId)){
                $allocationDetails=$this->AllocationByManagerModel->load('allocations',$allocationId);

                if(!empty($currentBillId)){
                    $billInformation=$this->AllocationByManagerModel->getUnallocatedBillById('bills',$currentBillId);
                    if(!empty($billInformation)){
                        $allocationSalesman=$allocationDetails[0]['allocationSalesman'].','.$billInformation[0]['salesman'];
                        $allocationTotalAmount=$allocationDetails[0]['allocationTotalAmount']+$billInformation[0]['pendingAmt'];
                        $allocationBillCount=1+$allocationDetails[0]['allocationBillCount'];

                        $updateAllocationDetails=array(
                            "allocationTotalAmount"=>$allocationTotalAmount,
                            "allocationBillCount"=>$allocationBillCount,
                            "allocationSalesman"=>$allocationSalesman
                        );
                        $this->AllocationByManagerModel->update('allocations',$updateAllocationDetails,$allocationId);
                    }       
                }

                if(!empty($allocationDetails)){
                    $insAllocationDetail=array('billId'=>$currentBillId,'allocationId'=>$allocationId,'billStatus'=>2);
                    $this->AllocationByManagerModel->insert('allocationsbills',$insAllocationDetail);
                    if($this->db->affected_rows()>0){
                        
                        $updBills=array('billCurrentStatus'=>'Allocated in'.$allocationDetails[0]['allocationCode'],'billType'=>'allocatedbillPass','isAllocated'=>1,'isBilled'=>'0','isLostBill'=>'0');
                        $this->AllocationByManagerModel->update('bills',$updBills,$currentBillId);
                        if($this->db->affected_rows()>0){
                            $history=array(
                                'billId'=>$currentBillId,
                                'allocationId'=>$allocationId,
                                'transactionStatus' =>'Allocated',
                                'transactionDate'=>date('Y-m-d H:i:sa'),
                                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                            );
                            $this->AllocationByManagerModel->insert('bill_transaction_history',$history);
                            
                            echo "Record inserted";
                        }else{
                            echo "Record not inserted";
                        }
                    }
                }
            }

            
        }
    }

    public function leaveUnAllocatedBill(){
        $currentBillNo=trim($this->input->post('currentBillNo'));
        $currentBillRetailer=trim($this->input->post('currentBillRetailer'));
        $currentBillAmount=trim($this->input->post('currentBillAmount'));
        $currentBillCompany=trim($this->input->post('currentBillCompany'));

        $checkBill=$this->AllocationByManagerModel->loadCurrentBillsByNo('bills',$currentBillNo);
        
        $currentBillId=0;

        if(empty($checkBill)){
              $currentDate=date('Y-m-d');
              $insertData=array('manuallyAddedBill'=>1,'billNo'=>$currentBillNo,'date'=>$currentDate,'retailerName'=>$currentBillRetailer,'compName'=>$currentBillCompany,'netAmount'=>$currentBillAmount,'pendingAmt'=>$currentBillAmount);
              $this->AllocationByManagerModel->insert('bills',$insertData);
              if($this->db->affected_rows()>0){
                  echo "Record inserted";
              }else{
                  echo "Record not inserted";
              }
        }else{
          echo "Bill aleady present";
        }
    }

    public function cashCollection(){
        $currentBillNo=trim($this->input->post('currentBillNo'));
        $currentBillRetailer=trim($this->input->post('currentBillRetailer'));
        $currentBillAmount=trim($this->input->post('currentBillAmount'));
        $currentBillCompany=trim($this->input->post('currentBillCompany'));

        $checkBill=$this->AllocationByManagerModel->loadCurrentBillsByNo('bills',$currentBillNo);
        
        $currentBillId=0;

        if(empty($checkBill)){
              $currentDate=date('Y-m-d');
              $insertData=array('manuallyAddedBill'=>1,'billNo'=>$currentBillNo,'date'=>$currentDate,'retailerName'=>$currentBillRetailer,'compName'=>$currentBillCompany,'netAmount'=>$currentBillAmount,'pendingAmt'=>$currentBillAmount);
              $this->AllocationByManagerModel->insert('bills',$insertData);
              if($this->db->affected_rows()>0){
                  $currentBillId=$this->db->insert_id();
              }else{
                  exit;
              }
        }else{
          exit;
        }

        if($currentBillId==0){
          exit;
        }

        $empName=trim($this->input->post('empName'));
        $empId=trim($this->input->post('empId'));
        $collectedAmt=trim($this->input->post('collectedAmt'));
        $currentPendingAmt=$currentBillAmount;
        $currentBillId=$currentBillId;


        $a2000=trim($this->input->post('a2000'));
        $a1000=trim($this->input->post('a1000'));
        $a500=trim($this->input->post('a500'));
        $a200=trim($this->input->post('a200'));
        $a100=trim($this->input->post('a100'));
        $a50=trim($this->input->post('a50'));
        $a20=trim($this->input->post('a20'));
        $a10=trim($this->input->post('a10'));
        $coin=trim($this->input->post('coin'));

        $updatedAt=date('Y-m-d H:i:sa');
        $updatedBy=$this->session->userdata[$this->projectSessionName]['id'];

        $billInfo=$this->AllocationByManagerModel->load('bills',$currentBillId);//get bill detail
        $netAmount=$billInfo[0]['netAmount'];
        $pendingAmt=$billInfo[0]['pendingAmt'];
        $receivedAmt=$billInfo[0]['receivedAmt'];
        $compName=$billInfo[0]['compName'];
        $billNo=$billInfo[0]['billNo'];
        $bill_no="Bill No. ".$billNo;

        $lastBal=$this->AllocationByManagerModel->lastRecordValue();//get closing balance
        $openCloseBal=$lastBal['openCloseBalance'];
        if($openCloseBal=='' || $openCloseBal==Null){
            $openCloseBal=0.0;
        }

        $totalClosingBalance=$openCloseBal+$collectedAmt;//final closing balance

        $finalPending=$pendingAmt-$collectedAmt;//final pending for current bill
        $finalReceived=$receivedAmt+$collectedAmt;//final received for current bill
        
        $billUpdate=array();
        if($netAmount==$pendingAmt){
            $billUpdate=array(
                'billType'=>'allocatedbillCurrent','isResendBill'=>'0','receivedAmt'=>$finalReceived,'pendingAmt'=>$finalPending
            );
        }else{
            $billUpdate=array(
                'receivedAmt'=>$finalReceived,'isResendBill'=>'0','pendingAmt'=>$finalPending
            );
        }
        

        $this->AllocationByManagerModel->update('bills',$billUpdate,$currentBillId);//update bill amount
        if($this->db->affected_rows()>0){

            $history=array(
                'billId'=>$currentBillId,
                'transactionAmount' =>$collectedAmt,
                'transactionStatus' =>'Cash',
                'transactionMode'=>'dr',
                'transactionDate'=>date('Y-m-d H:i:sa'),
                'empId'=>$empId,
                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
            );
            $this->AllocationByManagerModel->insert('bill_transaction_history',$history);

            $billPayment=array(
                'empId'=>$empId,'billId'=>$currentBillId,'date'=>$updatedAt,'paidAmount'=>$collectedAmt,'billAmount'=>$netAmount,'balanceAmount'=>$finalPending,'paymentMode'=>'Cash','updatedBy'=>$updatedBy
            );
            $this->AllocationByManagerModel->insert('billpayments',$billPayment);//insert billpayment data

            $notesDetails=array('transactionType'=>"Office Collection",
                'billId'=>$currentBillId,'empId'=>$empId,'note2000'=>$a2000,'note1000'=>$a1000,'note500'=>$a500,'note200'=>$a200,'note100'=>$a100,'note50'=>$a50,'note20'=>$a20,'note10'=>$a10,'coins'=>$coin,'collectedAmt'=>$collectedAmt,'updatedAt'=>$updatedAt,'updatedBy'=>$updatedBy
            );
            $this->AllocationByManagerModel->insert('notesdetails',$notesDetails);//insert notes details
            if($this->db->affected_rows()>0){
                $notesId=$this->db->insert_id();
                $marketCollection=array(
                    'company'=>$compName,'billId'=>$currentBillId,'date'=>$updatedAt,'employeeId'=>$empId,'notesId'=>$notesId,'amount'=>$collectedAmt,'nature'=>'Office Collection','narration'=>$bill_no,'inoutStatus'=>'Inflow','openCloseBalance'=>$totalClosingBalance,'updatedBy'=>$updatedBy
                );

                $this->AllocationByManagerModel->insert('expences',$marketCollection);//insert expenses
                if($this->db->affected_rows()>0){
                    echo "Record updated";
                }
            }

        }else{
            echo "Record not updated";
        }

    }

      public function chequeCollection(){
        $currentBillNo=trim($this->input->post('currentBillNo'));
        $currentBillRetailer=trim($this->input->post('currentBillRetailer'));
        $currentBillAmount=trim($this->input->post('currentBillAmount'));
        $currentBillCompany=trim($this->input->post('currentBillCompany'));

        $checkBill=$this->AllocationByManagerModel->loadCurrentBillsByNo('bills',$currentBillNo);
        
        $currentBillId=0;

        if(empty($checkBill)){
              $currentDate=date('Y-m-d');
              $insertData=array('manuallyAddedBill'=>1,'billNo'=>$currentBillNo,'date'=>$currentDate,'retailerName'=>$currentBillRetailer,'compName'=>$currentBillCompany,'netAmount'=>$currentBillAmount,'pendingAmt'=>$currentBillAmount);
              $this->AllocationByManagerModel->insert('bills',$insertData);
              if($this->db->affected_rows()>0){
                  $currentBillId=$this->db->insert_id();
              }else{
                  exit;
              }
        }else{
          exit;
        }

        if($currentBillId==0){
          exit;
        }


        $empName=trim($this->input->post('empName'));
        $empId=trim($this->input->post('empId'));
        $collectedAmt=trim($this->input->post('chequeAmount'));
        $currentPendingAmt=$currentBillAmount;
        $currentBillId=$currentBillId;

        $updatedAt=date('Y-m-d H:i:sa');
        $updatedBy=$this->session->userdata[$this->projectSessionName]['id'];

        $billInfo=$this->AllocationByManagerModel->load('bills',$currentBillId);//get bill detail
        $netAmount=$billInfo[0]['netAmount'];
        $pendingAmt=$billInfo[0]['pendingAmt'];
        $receivedAmt=$billInfo[0]['receivedAmt'];

        $finalPending=$pendingAmt-$collectedAmt;//final pending for current bill
        $finalReceived=$receivedAmt+$collectedAmt;//final received for current bill
        
        $billUpdate=array();
        if($netAmount==$pendingAmt){
            $billUpdate=array(
                'billType'=>'allocatedbillCurrent','isResendBill'=>'0','receivedAmt'=>$finalReceived,'pendingAmt'=>$finalPending
            );
        }else{
            $billUpdate=array(
                'receivedAmt'=>$finalReceived,'isResendBill'=>'0','pendingAmt'=>$finalPending
            );
        }

        $this->AllocationByManagerModel->update('bills',$billUpdate,$currentBillId);//update bill amount
        if($this->db->affected_rows()>0){
            $history=array(
                'billId'=>$currentBillId,
                'transactionAmount' =>$collectedAmt,
                'transactionStatus' =>'Cheque',
                'transactionMode'=>'dr',
                'transactionDate'=>date('Y-m-d H:i:sa'),
                'empId'=>$empId,
                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
            );
            $this->AllocationByManagerModel->insert('bill_transaction_history',$history);

            $billPayment=array(
                'empId'=>$empId,'billId'=>$currentBillId,'date'=>$updatedAt,'paidAmount'=>$collectedAmt,'billAmount'=>$netAmount,'balanceAmount'=>$finalPending,'paymentMode'=>'Cheque','isLostStatus'=>2,'updatedBy'=>$updatedBy
            );
            $this->AllocationByManagerModel->insert('billpayments',$billPayment);//insert billpayment data
            echo "Record updated";
        }else{
            echo "Record not updated";
        }
    }

    public function neftCollection(){
        $currentBillNo=trim($this->input->post('currentBillNo'));
        $currentBillRetailer=trim($this->input->post('currentBillRetailer'));
        $currentBillAmount=trim($this->input->post('currentBillAmount'));
        $currentBillCompany=trim($this->input->post('currentBillCompany'));

        $checkBill=$this->AllocationByManagerModel->loadCurrentBillsByNo('bills',$currentBillNo);
        
        $currentBillId=0;

        if(empty($checkBill)){
              $currentDate=date('Y-m-d');
              $insertData=array('manuallyAddedBill'=>1,'billNo'=>$currentBillNo,'date'=>$currentDate,'retailerName'=>$currentBillRetailer,'compName'=>$currentBillCompany,'netAmount'=>$currentBillAmount,'pendingAmt'=>$currentBillAmount);
              $this->AllocationByManagerModel->insert('bills',$insertData);
              if($this->db->affected_rows()>0){
                  $currentBillId=$this->db->insert_id();
              }else{
                  exit;
              }
        }else{
          exit;
        }

        if($currentBillId==0){
          exit;
        }


        $empName=trim($this->input->post('empName'));
        $empId=trim($this->input->post('empId'));
        $collectedAmt=trim($this->input->post('neftAmount'));
        $currentPendingAmt=$currentBillAmount;
        $currentBillId=$currentBillId;

        $updatedAt=date('Y-m-d H:i:sa');
        $updatedBy=$this->session->userdata[$this->projectSessionName]['id'];

        $billInfo=$this->AllocationByManagerModel->load('bills',$currentBillId);//get bill detail
        $netAmount=$billInfo[0]['netAmount'];
        $pendingAmt=$billInfo[0]['pendingAmt'];
        $receivedAmt=$billInfo[0]['receivedAmt'];

        $finalPending=$pendingAmt-$collectedAmt;//final pending for current bill
        $finalReceived=$receivedAmt+$collectedAmt;//final received for current bill
        
        $billUpdate=array();
        if($netAmount==$pendingAmt){
            $billUpdate=array(
                'billType'=>'allocatedbillCurrent','isResendBill'=>'0','receivedAmt'=>$finalReceived,'pendingAmt'=>$finalPending
            );
        }else{
            $billUpdate=array(
                'receivedAmt'=>$finalReceived,'isResendBill'=>'0','pendingAmt'=>$finalPending
            );
        }

        $this->AllocationByManagerModel->update('bills',$billUpdate,$currentBillId);//update bill amount
        if($this->db->affected_rows()>0){
            $history=array(
                'billId'=>$currentBillId,
                'transactionAmount' =>$collectedAmt,
                'transactionStatus' =>'NEFT',
                'transactionMode'=>'dr',
                'transactionDate'=>date('Y-m-d H:i:sa'),
                'empId'=>$empId,
                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
            );
            $this->AllocationByManagerModel->insert('bill_transaction_history',$history);

            $billPayment=array(
                'empId'=>$empId,'billId'=>$currentBillId,'date'=>$updatedAt,'paidAmount'=>$collectedAmt,'billAmount'=>$netAmount,'balanceAmount'=>$finalPending,'paymentMode'=>'NEFT','isLostStatus'=>2,'updatedBy'=>$updatedBy
            );
            $this->AllocationByManagerModel->insert('billpayments',$billPayment);//insert billpayment data
            echo "Record updated";
        }else{
            echo "Record not updated";
        }
    }

    public function cashDiscountCollection(){
        $currentBillNo=trim($this->input->post('currentBillNo'));
        $currentBillRetailer=trim($this->input->post('currentBillRetailer'));
        $currentBillAmount=trim($this->input->post('currentBillAmount'));
        $currentBillCompany=trim($this->input->post('currentBillCompany'));

        $checkBill=$this->AllocationByManagerModel->loadCurrentBillsByNo('bills',$currentBillNo);
        
        $currentBillId=0;

        if(empty($checkBill)){
              $currentDate=date('Y-m-d');
              $insertData=array('manuallyAddedBill'=>1,'billNo'=>$currentBillNo,'date'=>$currentDate,'retailerName'=>$currentBillRetailer,'compName'=>$currentBillCompany,'netAmount'=>$currentBillAmount,'pendingAmt'=>$currentBillAmount);
              $this->AllocationByManagerModel->insert('bills',$insertData);
              if($this->db->affected_rows()>0){
                  $currentBillId=$this->db->insert_id();
              }else{
                  exit;
              }
        }else{
          exit;
        }

        if($currentBillId==0){
          exit;
        }

        $empName=trim($this->input->post('empName'));
        $empId=trim($this->input->post('empId'));
        $collectedAmt=trim($this->input->post('cdAmount'));
        $remark=trim($this->input->post('cdRemark'));
        $currentPendingAmt=$currentBillAmount;
        $currentBillId=$currentBillId;

        $updatedAt=date('Y-m-d H:i:sa');
        $updatedBy=$this->session->userdata[$this->projectSessionName]['id'];

        $billInfo=$this->AllocationByManagerModel->load('bills',$currentBillId);//get bill detail
        $netAmount=$billInfo[0]['netAmount'];
        $pendingAmt=$billInfo[0]['pendingAmt'];
        $cd=$billInfo[0]['cd'];

        $finalPending=$pendingAmt-$collectedAmt;//final pending for current bill
        $finalCd=$cd+$collectedAmt;//final received for current bill
        
        $billUpdate=array();
        if($netAmount==$pendingAmt){
            $billUpdate=array(
                'billType'=>'allocatedbillCurrent','isResendBill'=>'0','cd'=>$finalCd,'pendingAmt'=>$finalPending
            );
        }else{
            $billUpdate=array(
                'cd'=>$finalCd,'isResendBill'=>'0','pendingAmt'=>$finalPending
            );
        }

        $this->AllocationByManagerModel->update('bills',$billUpdate,$currentBillId);//update bill amount
        if($this->db->affected_rows()>0){
            $history=array(
                'billId'=>$currentBillId,
                'transactionAmount' =>$collectedAmt,
                'transactionStatus' =>'CD',
                'transactionMode'=>'dr',
                'remark'=>$remark,
                'transactionDate'=>date('Y-m-d H:i:sa'),
                'empId'=>$empId,
                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
            );
            $this->AllocationByManagerModel->insert('bill_transaction_history',$history);

            $billRemark=array(
                'billId'=>$currentBillId,'empId'=>$empId,'remark'=>$remark,'amount'=>$collectedAmt,'updatedAt'=>$updatedAt,'updatedBy'=>$updatedBy
            );
            $this->AllocationByManagerModel->insert('bill_remark_history',$billRemark);//insert remark data

            $billPayment=array(
                'empId'=>$empId,'billId'=>$currentBillId,'date'=>$updatedAt,'paidAmount'=>$collectedAmt,'billAmount'=>$netAmount,'balanceAmount'=>$finalPending,'paymentMode'=>'CD','isLostStatus'=>2,'updatedBy'=>$updatedBy
            );
            $this->AllocationByManagerModel->insert('billpayments',$billPayment);
            echo "Record updated";
        }else{
            echo "Record not updated";
        }
    }

    public function debitToEmployeeCollection(){
        $currentBillNo=trim($this->input->post('currentBillNo'));
        $currentBillRetailer=trim($this->input->post('currentBillRetailer'));
        $currentBillAmount=trim($this->input->post('currentBillAmount'));
        $currentBillCompany=trim($this->input->post('currentBillCompany'));

        $checkBill=$this->AllocationByManagerModel->loadCurrentBillsByNo('bills',$currentBillNo);
        
        $currentBillId=0;
        
        $companyDetails=$this->AllocationByManagerModel->getdata('office_details');
        $officeName=$companyDetails[0]['distributorName'];
        $distributorCode=$companyDetails[0]['distributorCode'];

        if(empty($checkBill)){
              $currentDate=date('Y-m-d');
              $insertData=array('manuallyAddedBill'=>1,'billNo'=>$currentBillNo,'date'=>$currentDate,'retailerName'=>$currentBillRetailer,'compName'=>$currentBillCompany,'netAmount'=>$currentBillAmount,'pendingAmt'=>$currentBillAmount);
              $this->AllocationByManagerModel->insert('bills',$insertData);
              if($this->db->affected_rows()>0){
                  $currentBillId=$this->db->insert_id();
              }else{
                  exit;
              }
        }else{
          exit;
        }

        if($currentBillId==0){
          exit;
        }

        $empName=trim($this->input->post('empName'));
        $empId=trim($this->input->post('empId'));
        $collectedAmt=trim($this->input->post('debitAmount'));
        $remark=trim($this->input->post('debitRemark'));
        $currentPendingAmt=$currentBillAmount;
        $currentBillId=$currentBillId;

        $updatedAt=date('Y-m-d H:i:sa');
        $updatedBy=$this->session->userdata[$this->projectSessionName]['id'];

        $billInfo=$this->AllocationByManagerModel->load('bills',$currentBillId);//get bill detail
        $netAmount=$billInfo[0]['netAmount'];
        $pendingAmt=$billInfo[0]['pendingAmt'];
        $debit=$billInfo[0]['debit'];
        $billCompName=$billInfo[0]['compName'];
        $billNo=$billInfo[0]['billNo'];

        $finalPending=$pendingAmt-$collectedAmt;//final pending for current bill
        $finalDebit=$debit+$collectedAmt;//final received for current bill
        
        $billUpdate=array();
        if($netAmount==$pendingAmt){
            $billUpdate=array(
                'billType'=>'allocatedbillCurrent','isResendBill'=>'0','debit'=>$finalDebit,'pendingAmt'=>$finalPending
            );
        }else{
            $billUpdate=array(
                'debit'=>$finalDebit,'isResendBill'=>'0','pendingAmt'=>$finalPending
            );
        }

        $this->AllocationByManagerModel->update('bills',$billUpdate,$currentBillId);//update bill amount
        if($this->db->affected_rows()>0){
            $history=array(
                'billId'=>$currentBillId,
                'transactionAmount' =>$collectedAmt,
                'transactionStatus' =>'Debit To Employee',
                'transactionMode'=>'dr',
                'remark'=>$remark,
                'transactionDate'=>date('Y-m-d H:i:sa'),
                'empId'=>$empId,
                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
            );
            $this->AllocationByManagerModel->insert('bill_transaction_history',$history);

            $billRemark=array(
                'billId'=>$currentBillId,'empId'=>$empId,'remark'=>$remark,'amount'=>$collectedAmt,'updatedAt'=>$updatedAt,'updatedBy'=>$updatedBy
            );
            $this->AllocationByManagerModel->insert('bill_remark_history',$billRemark);//insert remark data

            $empDebit=array(
                'billId'=>$currentBillId,'empId'=>$empId,'transactionType'=>'dr','description'=>$remark,'amount'=>$collectedAmt,'createdAt'=>$updatedAt,'createdBy'=>$updatedBy
            );
             $this->AllocationByManagerModel->insert('emptransactions',$empDebit);//insert remark data

            $billPayment=array(
                'empId'=>$empId,'billId'=>$currentBillId,'date'=>$updatedAt,'paidAmount'=>$collectedAmt,'billAmount'=>$netAmount,'balanceAmount'=>$finalPending,'paymentMode'=>'Debit To Employee','isLostStatus'=>2,'updatedBy'=>$updatedBy
            );
            $this->AllocationByManagerModel->insert('billpayments',$billPayment);
            
            $employeeDetails=$this->AllocationByManagerModel->load('employee',$empId);
            $employeeMobile=$employeeDetails[0]['mobile'];
            $employeeName=$employeeDetails[0]['name'];
            $transactionDate=date('M d, Y H:i a');
            //send sms to employee
            // $this->sendEmployeeDebitSMS($employeeName,$employeeMobile,$collectedAmt,$transactionDate,$billCompName,$billNo,$remark);
            
            $ledger=$this->AllocationByManagerModel->getEmpLedgerByEmp('emptransactions',$empId);
            $balance=0;
            if(!empty($ledger)){
                foreach($ledger as $leg){
                    if($leg['transactionType']=='cr'){
                        $balance=$balance+$leg['amount'];
                    }else if($leg['transactionType']=='dr'){
                         $balance=$balance-$leg['amount'];
                    }
                }
            }
            
            if($balance > 0){
                $balance=number_format(abs($balance));
                $balance =($balance).' Cr';
            }else{
                $balance=number_format(abs($balance));
                $balance= ($balance).' Dr';
            }

            $jsonData=array(
                "flow_id"=>"618d064ba4528b1fda1ceb1d",
                "sender"=>"SIAInc",
                "mobiles"=>'91'.$employeeMobile,
                "name"=>$employeeName,
                "amount"=>number_format($collectedAmt),
                "distributorname"=>$officeName,
                "dateandtime"=>date('d M, Y H:i A'),
                "company"=>$billCompName,
                "billnumber"=>$billNo,
                "balance"=>$balance
            );

            $jsonData=json_encode($jsonData);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.msg91.com/api/v5/flow/",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $jsonData,
                CURLOPT_HTTPHEADER => array("authkey: 291106Atbm2KHoWhat5d99ec46","content-type: application/JSON"),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            
            echo "Record updated";
        }else{
            echo "Record not updated";
        }
    }

    public function officeAdjustmentCollection(){
        $currentBillNo=trim($this->input->post('currentBillNo'));
        $currentBillRetailer=trim($this->input->post('currentBillRetailer'));
        $currentBillAmount=trim($this->input->post('currentBillAmount'));
        $currentBillCompany=trim($this->input->post('currentBillCompany'));

        $checkBill=$this->AllocationByManagerModel->loadCurrentBillsByNo('bills',$currentBillNo);
        
        $currentBillId=0;

        if(empty($checkBill)){
              $currentDate=date('Y-m-d');
              $insertData=array('manuallyAddedBill'=>1,'billNo'=>$currentBillNo,'date'=>$currentDate,'retailerName'=>$currentBillRetailer,'compName'=>$currentBillCompany,'netAmount'=>$currentBillAmount,'pendingAmt'=>$currentBillAmount);
              $this->AllocationByManagerModel->insert('bills',$insertData);
              if($this->db->affected_rows()>0){
                  $currentBillId=$this->db->insert_id();
              }else{
                  exit;
              }
        }else{
          exit;
        }

        if($currentBillId==0){
          exit;
        }

        $empName=trim($this->input->post('empName'));
        $empId=trim($this->input->post('empId'));
        $collectedAmt=trim($this->input->post('officeAdjAmount'));
        if($collectedAmt==""){
            $collectedAmt=0;
        }

        $remark=trim($this->input->post('officeAdjRemark'));
        $currentPendingAmt=$currentBillAmount;
        $currentBillId=$currentBillId;

        $updatedAt=date('Y-m-d H:i:sa');
        $updatedBy=$this->session->userdata[$this->projectSessionName]['id'];

        $billInfo=$this->AllocationByManagerModel->load('bills',$currentBillId);//get bill detail
        $netAmount=$billInfo[0]['netAmount'];
        $pendingAmt=$billInfo[0]['pendingAmt'];
        $officeAdjustment=$billInfo[0]['officeAdjustmentBillAmount'];

        $finalPending=$pendingAmt-$collectedAmt;//final pending for current bill
        $finalOA=$officeAdjustment+$collectedAmt;//final received for current bill
        
        $billUpdate=array(
            'billType'=>'officeAdjustmentBill','isResendBill'=>'0','isOfficeAdjustmentBill'=>1,'officeAdjustmentBillAmount'=>$finalOA,'pendingAmt'=>$finalPending
        );

        $this->AllocationByManagerModel->update('bills',$billUpdate,$currentBillId);//update bill amount
        if($this->db->affected_rows()>0){

            $history=array(
                'billId'=>$currentBillId,
                'transactionAmount' =>$collectedAmt,
                'transactionStatus' =>'Office Adjustment',
                'transactionMode'=>'dr',
                'remark'=>$remark,
                'transactionDate'=>date('Y-m-d H:i:sa'),
                'empId'=>$empId,
                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
            );
            $this->AllocationByManagerModel->insert('bill_transaction_history',$history);

            $billRemark=array(
                'billId'=>$currentBillId,'empId'=>$empId,'remark'=>$remark,'amount'=>$collectedAmt,'updatedAt'=>$updatedAt,'updatedBy'=>$updatedBy
            );
            $this->AllocationByManagerModel->insert('bill_remark_history',$billRemark);//insert remark data

            $billPayment=array(
                'empId'=>$empId,'billId'=>$currentBillId,'date'=>$updatedAt,'paidAmount'=>$collectedAmt,'billAmount'=>$netAmount,'balanceAmount'=>$finalPending,'paymentMode'=>'Office Adjustment','isLostStatus'=>2,'updatedBy'=>$updatedBy
            );
            $this->AllocationByManagerModel->insert('billpayments',$billPayment);
           
            echo "Record updated";
        }else{
            echo "Record not updated";
        }
    }

    public function empDeliveryCollection(){
        $currentBillNo=trim($this->input->post('currentBillNo'));
        $currentBillRetailer=trim($this->input->post('currentBillRetailer'));
        $currentBillAmount=trim($this->input->post('currentBillAmount'));
        $currentBillCompany=trim($this->input->post('currentBillCompany'));

        $checkBill=$this->AllocationByManagerModel->loadCurrentBillsByNo('bills',$currentBillNo);
        
        $currentBillId=0;

        if(empty($checkBill)){
              $currentDate=date('Y-m-d');
              $insertData=array('manuallyAddedBill'=>1,'billNo'=>$currentBillNo,'date'=>$currentDate,'retailerName'=>$currentBillRetailer,'compName'=>$currentBillCompany,'netAmount'=>$currentBillAmount,'pendingAmt'=>$currentBillAmount,'isDirectDeliveryBill'=>1);
              $this->AllocationByManagerModel->insert('bills',$insertData);
              if($this->db->affected_rows()>0){
                  $currentBillId=$this->db->insert_id();
              }else{
                  exit;
              }
        }else{
          exit;
        }

        if($currentBillId==0){
          exit;
        }

        $empName=trim($this->input->post('empName'));
        $empId=trim($this->input->post('empId'));
        $remark=trim($this->input->post('deliveryRemark'));
        $currentPendingAmt=$currentBillAmount;
        $currentBillId=$currentBillId;

        $updatedAt=date('Y-m-d H:i:sa');
        $updatedBy=$this->session->userdata[$this->projectSessionName]['id'];

        $billInfo=$this->AllocationByManagerModel->load('bills',$currentBillId);//get bill detail
        $netAmount=$billInfo[0]['netAmount'];
        $pendingAmt=$billInfo[0]['pendingAmt'];
        $retailerName=$billInfo[0]['retailerName'];
        $billNo=$billInfo[0]['billNo'];

        $billUpdate=array(
            'billCurrentStatus'=>'Direct Delivery','isResendBill'=>'0','billType'=>'adHocDeliveryBill','deliveryEmpName'=>$empName
        );

        $this->AllocationByManagerModel->update('bills',$billUpdate,$currentBillId);//update bill amount
        if($this->db->affected_rows()>0){
            $billRemark=array(
                'billId'=>$currentBillId,'empId'=>$empId,'remark'=>$remark,'updatedAt'=>$updatedAt,'updatedBy'=>$updatedBy,'isDirectDeliveryBill'=>1
            );
            $this->AllocationByManagerModel->insert('bill_remark_history',$billRemark);//insert remark data
            

            $history=array(
                'billId'=>$currentBillId,
                'transactionStatus' =>'Direct Delivery',
                'transactionMode'=>'dr',
                'remark'=>$remark,
                'transactionDate'=>date('Y-m-d H:i:sa'),
                'empId'=>$empId,
                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
            );
            $this->AllocationByManagerModel->insert('bill_transaction_history',$history);

            $companyDetails=$this->AllocationByManagerModel->getdata('office_details');
            $officeName=$companyDetails[0]['distributorName'];
            $distributorCode=$companyDetails[0]['distributorCode'];
    
            $employeeDetails=$this->AllocationByManagerModel->load('employee',$empId);
            $employeeMobile=$employeeDetails[0]['mobile'];
            $employeeName=$employeeDetails[0]['name'];
            $employeeDesignation=$employeeDetails[0]['designation'];
            $transactionDate=date('M d, Y H:i a');
            
            if(!empty($employeeMobile)){
                $jsonData=array(
                    "flow_id"=>"618d066ddcd3551b5e036bb6",
                    "sender"=>"SIAInc",
                    "mobiles"=>'91'.$employeeMobile,
                    "name"=>$employeeName,
                    "distributorname"=>$officeName,
                    "billnumber"=>$billNo,
                    "retailername"=>substr($retailerName,0,25),
                    "amount"=>number_format($pendingAmt),
                    "date"=>date('d M, Y'),
                    "remarks"=>substr($remark,0,20)
                );
    
                $jsonData=json_encode($jsonData);
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.msg91.com/api/v5/flow/",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $jsonData,
                    CURLOPT_HTTPHEADER => array("authkey: 291106Atbm2KHoWhat5d99ec46","content-type: application/JSON"),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
            }
            echo "Record updated";
        }else{
            echo "Record not updated";
        }
    }

    public function otherAdjustmentCollection(){
        $currentBillNo=trim($this->input->post('currentBillNo'));
        $currentBillRetailer=trim($this->input->post('currentBillRetailer'));
        $currentBillAmount=trim($this->input->post('currentBillAmount'));
        $currentBillCompany=trim($this->input->post('currentBillCompany'));

        $checkBill=$this->AllocationByManagerModel->loadCurrentBillsByNo('bills',$currentBillNo);
        
        $currentBillId=0;

        if(empty($checkBill)){
              $currentDate=date('Y-m-d');
              $insertData=array('manuallyAddedBill'=>1,'billNo'=>$currentBillNo,'date'=>$currentDate,'retailerName'=>$currentBillRetailer,'compName'=>$currentBillCompany,'netAmount'=>$currentBillAmount,'pendingAmt'=>$currentBillAmount);
              $this->AllocationByManagerModel->insert('bills',$insertData);
              if($this->db->affected_rows()>0){
                  $currentBillId=$this->db->insert_id();
              }else{
                  exit;
              }
        }else{
          exit;
        }

        if($currentBillId==0){
          exit;
        }

        $empName=trim($this->input->post('empName'));
        $empId=trim($this->input->post('empId'));
        $collectedAmt=trim($this->input->post('otherAdjAmount'));
        
        if($collectedAmt==""){
            $collectedAmt=0;
        }

        $remark=trim($this->input->post('otherAdjRemark'));
        $currentPendingAmt=$currentBillAmount;
        $currentBillId=$currentBillId;

        $updatedAt=date('Y-m-d H:i:sa');
        $updatedBy=$this->session->userdata[$this->projectSessionName]['id'];

        $billInfo=$this->AllocationByManagerModel->load('bills',$currentBillId);//get bill detail
        $netAmount=$billInfo[0]['netAmount'];
        $pendingAmt=$billInfo[0]['pendingAmt'];
        $otherAdjustment=$billInfo[0]['otherAdjustment'];

        $finalPending=$pendingAmt-$collectedAmt;//final pending for current bill
        $finalOA=$otherAdjustment+$collectedAmt;//final received for current bill
        
        $billUpdate=array();
        if($netAmount==$pendingAmt){
            $billUpdate=array(
                'billType'=>'allocatedbillCurrent','isResendBill'=>'0','isOtherAdjustmentBill'=>1,'otherAdjustment'=>$finalOA,'pendingAmt'=>$finalPending
            );
        }else{
            $billUpdate=array(
                'otherAdjustment'=>$finalOA,'isOtherAdjustmentBill'=>1,'isResendBill'=>'0','pendingAmt'=>$finalPending
            );
        }

        $this->AllocationByManagerModel->update('bills',$billUpdate,$currentBillId);//update bill amount
        if($this->db->affected_rows()>0){
            $history=array(
                'billId'=>$currentBillId,
                'transactionAmount' =>$collectedAmt,
                'transactionStatus' =>'Other Adjustment',
                'transactionMode'=>'dr',
                'remark'=>$remark,
                'transactionDate'=>date('Y-m-d H:i:sa'),
                'empId'=>$empId,
                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
            );
            $this->AllocationByManagerModel->insert('bill_transaction_history',$history);

            $billRemark=array(
                'billId'=>$currentBillId,'empId'=>$empId,'remark'=>$remark,'amount'=>$collectedAmt,'updatedAt'=>$updatedAt,'updatedBy'=>$updatedBy
            );
            $this->AllocationByManagerModel->insert('bill_remark_history',$billRemark);//insert remark data

            $billPayment=array(
                'empId'=>$empId,'billId'=>$currentBillId,'date'=>$updatedAt,'paidAmount'=>$collectedAmt,'billAmount'=>$netAmount,'balanceAmount'=>$finalPending,'paymentMode'=>'Other Adjustment','isLostStatus'=>2,'updatedBy'=>$updatedBy
            );
            $this->AllocationByManagerModel->insert('billpayments',$billPayment);
           
           
            echo "Record updated";
        }else{
            echo "Record not updated";
        }
    }

    public function getSrDetails(){
        $billId=trim($this->input->post('currentBillId'));
        $billsInfo=$this->AllocationByManagerModel->load('bills', $billId);
        $billDetails=$this->AllocationByManagerModel->getBillDetailInfo('billsdetails',$billId);

    ?>
    <!-- <form action="<?php echo site_url('BillTransactionController/updateSRCreditAdj');?>" method="post"> -->
    <div class="col-md-12">
         <table id="SrTable" class="table table-bordered dataTable js-exportable cust-tbl" data-page-length='100'>
                <span id="all_id" style="display:none"></span>
                <thead>
                    <tr>
                        <th>S. No</th>
                        <th>Product Name</th>
                        <th>MRP</th>
                        <th>Billed Qty</th>
                        <th>Net Amount</th>
                        <th>Total SR</th>
                        <th>SR Qty</th>
                        <th>Return Amount</th>
                    </tr>
                </thead>
                <tfoot>
                     <tr>
                        <th>S. No</th>
                        <th>Product Name</th>
                        <th>MRP</th>
                        <th>Qty</th>
                        <th>Net Amount</th>
                        <th>Old SR</th>
                        <th>SR Qty</th>
                        <th>Return Amount</th>
                    </tr>
                </tfoot>
                <tbody>


                    <?php
                    if(!empty($billDetails)){
                      $no=0;
                      foreach ($billDetails as $data) 
                        {
                         $no++; 
                         $id_is=$data['id'];
                         $id_qty=$data['qty'];
                         $id_fs_qty=$data['fsReturnQty'];
                      ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $data['productName']; ?></td>
                     <input type="hidden" id="prodName_id" name="productName[]" value="<?php echo $data['productName']; ?>" readonly>
                    <td>
                        <?php echo $data['mrp']; ?> 
                        <input type="hidden" id="mrp_id" name="mrp[]" value="<?php echo $data['mrp']; ?>" readonly>
                    </td>
                    <td>
                        <?php echo $data['qty']; ?>
                          <input type="hidden" id="qty_id<?php echo $no; ?>" name="qty[]" value="<?php echo $data['qty']; ?>" readonly> 
                          <input type="hidden" name="fsReturnQty[]" value="<?php echo $data['fsReturnQty']; ?>" readonly> 
                    </td> 
                    <td>
                        <?php echo $data['netAmount']; ?>
                        <input type="hidden" id="netAmount_id<?php echo $no; ?>" name="netAmount[]" value="<?php echo $data['netAmount']; ?>" readonly>
                        <input type="hidden" id="selAmount_id" name="selAmount[]" value="<?php echo $data['sellingRate']; ?>" readonly>         
                    </td>
                     <td>
                         <?php echo $data['fsReturnQty']; ?>
                         <input type="hidden" id="id_id" name="id[]" value="<?php echo $data['id']; ?>">
                         <input type="hidden" id="billId_id" name="billId" value="<?php echo $data['billId']; ?>">
                    </td>
                    <td>
                    <?php if($data['qty']==$data['fsReturnQty']){?>
                        <input type="text" style="height:25px;width: 50%" onkeypress="return numbersonly(this, event);" id="returnedQty<?php echo $no; ?>" onblur="checkQtyPerItem(this,'<?php echo $no; ?>','<?php echo $id_qty; ?>','<?php echo $id_fs_qty; ?>','<?php echo $billsInfo[0]['netAmount'];?>','<?php echo $billsInfo[0]['pendingAmt'];?>','<?php echo $billsInfo[0]['SRAmt'];?>','<?php echo $billsInfo[0]['receivedAmt'];?>','<?php echo $billsInfo[0]['fsCashAmt'];?>','<?php echo $billsInfo[0]['fsChequeAmt'];?>','<?php echo $billsInfo[0]['fsNeftAmt'];?>','<?php echo $billsInfo[0]['fsSrAmt'];?>')" onfocus="this.select();" autofocus="autofocus" class="form-control" name="returnedQty[]" value="<?php echo '0'; ?>">
                        <span style="color:red" id="data_err<?php echo $no; ?>"></span>
                        
                     <?php }else{ ?>
                         <input id="returnedQty<?php echo $no; ?>" style="height:25px;width: 50%" onkeypress="return numbersonly(this, event);" onblur="checkQtyPerItem(this,'<?php echo $no; ?>','<?php echo $id_qty; ?>','<?php echo $id_fs_qty; ?>','<?php echo $billsInfo[0]['netAmount'];?>','<?php echo $billsInfo[0]['pendingAmt'];?>','<?php echo $billsInfo[0]['SRAmt'];?>','<?php echo $billsInfo[0]['receivedAmt'];?>','<?php echo $billsInfo[0]['fsCashAmt'];?>','<?php echo $billsInfo[0]['fsChequeAmt'];?>','<?php echo $billsInfo[0]['fsNeftAmt'];?>','<?php echo $billsInfo[0]['fsSrAmt'];?>')" onfocus="this.select();" autofocus="autofocus" type="text" class="form-control" name="returnedQty[]" value="<?php echo '0'; ?>">
                         <span style="color:red" id="data_err<?php echo $no; ?>"></span> 
                     <?php }?>
                    </td>
                    <td>
                        <?php echo $data['fsReturnAmt']; ?>  
                         <input type="hidden" id="returnAmt_id<?php echo $no; ?>" name="returnAmt[]" value="<?php echo $data['returnAmt']; ?>">             
                    </td>
               </tr>  

                 <?php
                    }
                }else{ ?>
                    <tr><td>No data available</td></tr>
              <?php  } ?>
                </tbody>
            </table>
        </div>

   
        <div class="col-md-12">
            <div class="col-md-4">
                <button id="srBtn" type="button" class="btn btn-primary btnStyle m-t-15 waves-effect">
                    <i class="material-icons">save</i> 
                    <span class="icon-name">
                     Save SR
                    </span>
                </button>
                <?php if($billsInfo[0]['receivedAmt'] == 0.00){ ?>
                    <button type="button" id="fsrBtn" data-id="<?php echo $billId ?>" class="btn btn-primary btnStyle m-t-15 waves-effect">
                        <i class="material-icons">save</i> 
                        <span class="icon-name">
                         Save FSR
                        </span>
                    </button>
                <?php } ?>
               
                <button type="button" data-dismiss="modal" class="btn btn-danger m-t-15 waves-effect">
                    <i class="material-icons">cancel</i> 
                    <span class="icon-name"> Cancel</span>
                </button>
            </div>
        </div>
    <!-- </form> -->

    <?php
    
    }

    public function confirmFSR(){
        $billId=trim($this->input->post('billId'));
        $billNetAmt=$this->AllocationByManagerModel->load('bills', $billId);
        $fixNetAmt=$billNetAmt[0]['netAmount'];//net amount from bills by billid

        $creditAdj=round($billNetAmt[0]['creditAdjustment']);
        $totalFSR=$creditAdj+$fixNetAmt;
        
        $totalTaxableValue=0;
		$totalTaxAmount=0;
		$totalCessAmount=0;

        if($creditAdj>0){
            $billDetail=$this->AllocationByManagerModel->loadBillDetails('billsdetails', $billId);
            $srTotalFs=0;
            $id=0;
            foreach($billDetail as $bill){
                $id = $bill['id'];
                // $billId = $bill['billId'];
                $name= $bill['productName'];
                $mrp = $bill['mrp'];
                $qty = $bill['qty'];

                $netAmount = $bill['netAmount'];
                $returnedQty = $bill['gkReturnQty'];
                $returnAmt = $bill['fsReturnAmt'];

                //check return qty
                $actReturnQty=$qty-$returnedQty;
                $calAmt=0;
                    
                $calAmt=$netAmount/$qty;

                $ReturnAmount=$returnAmt+($calAmt * $actReturnQty);

                $data['billsdetails']=$this->AllocationByManagerModel->loadBillDetailsID('billsdetails', $id);
                $oldSR=$returnedQty+$actReturnQty;
                $srTotalFs=$srTotalFs+$ReturnAmount;
                
                $data = array(
                    'fsReturnQty' => $oldSR,
                    'fsReturnAmt' =>  $ReturnAmount
                );

                $this->AllocationByManagerModel->update('billsdetails',$data,$id);
                if($this->db->affected_rows()>0){
                    $srData = array(
                        'billId' =>  $billId,
                        'billItemId'=>$id,
                        'quantity'=>$oldSR,
                        'createdAt'=>date('Y-m-d H:i:sa')
                    );
                    $this->AllocationByManagerModel->insert('allocation_sr_details',$srData);
                } 

                $data['billDetail']=$this->AllocationByManagerModel->load('billsdetails',$id);
                $eachQtyRate=$data['billDetail'][0]['netAmount']/$data['billDetail'][0]['qty'];

                //cess amount
                $cessAmount=$data['billDetail'][0]['cessAmount']/$data['billDetail'][0]['qty'];
                $cessAmount=round($qty*$cessAmount,2);

                //tax amount
                $taxAmount=$data['billDetail'][0]['taxAmount']/$data['billDetail'][0]['qty'];
                $taxAmount=round($qty*$taxAmount,2);

                //taxable amount
                $taxableValue=$data['billDetail'][0]['taxableValue']/$data['billDetail'][0]['qty'];
                $taxableValue=round($qty*$taxableValue,2);

                $totalTaxableValue=$totalTaxableValue+$taxableValue;
                $totalTaxAmount=$totalTaxAmount+$taxAmount;
                $totalCessAmount=$totalCessAmount+$cessAmount;
            }
            $dataBills = array('isFsrBill'=>1,'billType'=>'allocatedbillPass','SRAmt' => $fixNetAmt,'creditNoteRenewal'=>$creditAdj,'pendingAmt'=>0);  
            $this->AllocationByManagerModel->update('bills',$dataBills, $billId);
            if($this->db->affected_rows()>0){
                $history=array(
                    'billId'=>$billId,
                    'transactionAmount' =>$fixNetAmt,
                    'transactionStatus' =>'FSR',
                    'transactionMode' =>'dr',
                    'transactionDate'=>date('Y-m-d H:i:sa'),
                    'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                    'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                );
                $this->AllocationByManagerModel->insert('bill_transaction_history',$history);
    
                $billPayment=array(
                    'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                    'billId'=>$billId,
                    'date'=>date('Y-m-d H:i:sa'),
                    'paidAmount'=>$fixNetAmt,
                    'billAmount'=>$fixNetAmt,
                    'balanceAmount'=>0,
                    'paymentMode'=>'FSR',
                    'taxableValue'=>$totalTaxableValue,
					'taxAmount'=>$totalTaxAmount,
					'cessAmount'=>$totalCessAmount,
                    'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                );
                $this->AllocationByManagerModel->insert('billpayments',$billPayment);//insert billpayment data
                echo "Record Updated";
            }else{
                echo "Record not Updated";
            }
        }else{
            $billDetail=$this->AllocationByManagerModel->loadBillDetails('billsdetails', $billId);
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

                $data['billsdetails']=$this->AllocationByManagerModel->loadBillDetailsID('billsdetails', $id);
                $oldSR=$returnedQty+$actReturnQty;
                $srTotalFs=$srTotalFs+$ReturnAmount;
                
                $data = array(
                    'fsReturnQty' => $oldSR,
                    'fsReturnAmt' =>  $ReturnAmount
                );

                $this->AllocationByManagerModel->update('billsdetails',$data,$id);
                if($this->db->affected_rows()>0){
                    $srData = array(
                        'billId' =>  $billId,
                        'billItemId'=>$id,
                        'quantity'=>$oldSR,
                        'createdAt'=>date('Y-m-d H:i:sa')
                    );
                    $this->AllocationByManagerModel->insert('allocation_sr_details',$srData);
                }

                $data['billDetail']=$this->AllocationByManagerModel->load('billsdetails',$id);
                $eachQtyRate=$data['billDetail'][0]['netAmount']/$data['billDetail'][0]['qty'];

                //cess amount
                $cessAmount=$data['billDetail'][0]['cessAmount']/$data['billDetail'][0]['qty'];
                $cessAmount=round($qty*$cessAmount,2);

                //tax amount
                $taxAmount=$data['billDetail'][0]['taxAmount']/$data['billDetail'][0]['qty'];
                $taxAmount=round($qty*$taxAmount,2);

                //taxable amount
                $taxableValue=$data['billDetail'][0]['taxableValue']/$data['billDetail'][0]['qty'];
                $taxableValue=round($qty*$taxableValue,2);

                $totalTaxableValue=$totalTaxableValue+$taxableValue;
                $totalTaxAmount=$totalTaxAmount+$taxAmount;
                $totalCessAmount=$totalCessAmount+$cessAmount;
            }
            $dataBills = array('isFsrBill'=>1,'billType'=>'allocatedbillPass','SRAmt' => $fixNetAmt,'pendingAmt'=>0);  
            $this->AllocationByManagerModel->update('bills',$dataBills, $billId);
            if($this->db->affected_rows()>0){
                $history=array(
                    'billId'=>$billId,
                    'transactionAmount' =>$fixNetAmt,
                    'transactionStatus' =>'FSR',
                    'transactionMode' =>'dr',
                    'transactionDate'=>date('Y-m-d H:i:sa'),
                    'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                    'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                );
                $this->AllocationByManagerModel->insert('bill_transaction_history',$history);
    
                $billPayment=array(
                    'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                    'billId'=>$billId,
                    'date'=>date('Y-m-d H:i:sa'),
                    'paidAmount'=>$fixNetAmt,
                    'billAmount'=>$fixNetAmt,
                    'balanceAmount'=>0,
                    'paymentMode'=>'FSR',
                    'taxableValue'=>$totalTaxableValue,
					'taxAmount'=>$totalTaxAmount,
					'cessAmount'=>$totalCessAmount,
                    'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                );
                $this->AllocationByManagerModel->insert('billpayments',$billPayment);//insert billpayment data

                echo "Record Updated";
            }else{
                echo "Record not Updated";
            }
        }

        
    }

    public function updateSRCreditAdj() {
        $data['msg']='';   
        
        $id = $this->input->post('id');
        $billId = $this->input->post('billId');

        $creditBillCheck=$this->AllocationByManagerModel->load('bills',$billId);
        $creditAdjAmount=$creditBillCheck[0]['creditAdjustment'];
        $netAmountAdj=$creditBillCheck[0]['netAmount'];
        $netAmountAdj=$creditBillCheck[0]['netAmount'];
        $pendingAmounFix = $creditBillCheck[0]['pendingAmt'];


        if($creditAdjAmount > 0){
            $totalTaxableValue=0;
            $totalTaxAmount=0;
            $totalCessAmount=0;

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

            $sumQty=$this->AllocationByManagerModel->getSum('billsdetails',$billId);
            $actualQty=$sumQty[0]['qtySum'];

            $srQty=number_format(array_sum($returnedQty),2);

            $status='SR';
           
            if($srQty<=$actualQty){
                for ($i=0; $i < count($returnedQty); $i++) {
                    if(!empty($returnedQty[$i]) || $returnedQty[$i] != 0.00){
                        if($returnedQty[$i] <= $qty[$i]){
                            $calAmtFix=$netAmount[$i]/$qty[$i];
                            $ReturnAmount=$returnAmt[$i]+($calAmtFix * $returnedQty[$i]);
                            $data['billsdetails']=$this->AllocationByManagerModel->loadBillDetailsID('billsdetails', $id[$i]);
                            
                            $data['bills']=$this->AllocationByManagerModel->load('bills', $billId);
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
                    $creditNoteAmt=round($fsSrBillAmtFix-$onlyPending);
                    
                    if($creditNoteAmt>round($creditAdjAmount)){
                        echo "SR Amount is greater than pending amount.";
                        // $this->session->set_flashdata('item', array('message' => 'SR Amount is greater than pending amount.','class' => 'success'));
                        // redirect('AdHocController/billSearch');
                        exit;
                    }

                    for ($i=0; $i < count($returnedQty); $i++) {
                        if($returnedQty[$i] <= $qty[$i]){
                            $calAmt=$netAmount[$i]/$qty[$i];
                            $ReturnAmount=$returnAmt[$i]+($calAmt * $returnedQty[$i]);
                            $data['billsdetails']=$this->AllocationByManagerModel->loadBillDetailsID('billsdetails', $id[$i]);
                            
                            $data['bills']=$this->AllocationByManagerModel->load('bills', $billId);
                            $fsSrBillAmt=$data['bills'][0]['fsSrAmt'];
                            $oldSR=0+$returnedQty[$i];
                            $srTotalFs=$srTotalFs+$ReturnAmount;

                            $fsSrBillAmt=round($srTotalFs);

                            $oldSR= $oldSR;
                            $ReturnAmount= $ReturnAmount;
                            // if($qty[$i] >= $oldSR){
                            if($returnedQty[$i] > 0){
                                $data = array(
                                    'fsReturnQty' => ($oldSR+$data['billsdetails'][0]['fsReturnQty']),
                                    'fsReturnAmt' => ($ReturnAmount+$data['billsdetails'][0]['fsReturnAmt'])
                                ); 
                                
                                $this->AllocationByManagerModel->update('billsdetails',$data,$id[$i]);

                                $srData = array(
                                    'billId' =>  $billId,
                                    'billItemId'=>$id[$i],
                                    'quantity'=>$oldSR,
                                    'createdAt'=>date('Y-m-d H:i:sa')
                                );
                                $this->AllocationByManagerModel->insert('allocation_sr_details',$srData);

                                $data['billsdetails']=$this->AllocationByManagerModel->loadBillDetailsID('billsdetails', $id[$i]);
                                //cess amount
                                $cessAmount=$data['billsdetails'][0]['cessAmount']/$data['billsdetails'][0]['qty'];
                                $cessAmount=round($returnedQty[$i]*$cessAmount,2);

                                //tax amount
                                $taxAmount=$data['billsdetails'][0]['taxAmount']/$data['billsdetails'][0]['qty'];
                                $taxAmount=round($returnedQty[$i]*$taxAmount,2);

                                //taxable amount
                                $taxableValue=$data['billsdetails'][0]['taxableValue']/$data['billsdetails'][0]['qty'];
                                $taxableValue=round($returnedQty[$i]*$taxableValue,2);

                                $totalTaxableValue=$totalTaxableValue+$taxableValue;
                                $totalTaxAmount=$totalTaxAmount+$taxAmount;
                                $totalCessAmount=$totalCessAmount+$cessAmount;
                            }
                            // }
                        }
                    }

                    $data['bills']=$this->AllocationByManagerModel->loadBillsDetails('bills', $billId);
                    $OlderSrAmt=$data['bills'][0]['SRAmt'];
                    $OlderPendingAmt=$data['bills'][0]['pendingAmt'];

                    $latestSrAmt=0;
                    $latestPendingAmt=0;

                   
                    $fixTotal=$fsSrBillAmt;
                    $fsSrBillAmt=round($fsSrBillAmt);
                    $latestSrAmt=$OlderSrAmt+$fsSrBillAmt;
                    $latestPendingAmt=$OlderPendingAmt-$fsSrBillAmt;

                    if($fsSrBillAmt>0){
                        $data = array('SRAmt' => $latestSrAmt,'pendingAmt'=>$latestPendingAmt,'creditNoteRenewal'=>$creditNoteAmt);  
                        $this->AllocationByManagerModel->update('bills',$data, $billId);

                        $history=array(
                            'billId'=>$billId,
                            'transactionAmount' =>$fsSrBillAmt,
                            'transactionStatus' =>'SR',
                            'transactionMode' =>'dr',
                            'transactionDate'=>date('Y-m-d H:i:sa'),
                            'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                            'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                        );
                        $this->AllocationByManagerModel->insert('bill_transaction_history',$history);
        
                        $billPayment=array(
                            'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                            'billId'=>$billId,
                            'date'=>date('Y-m-d H:i:sa'),
                            'paidAmount'=>$fsSrBillAmt,
                            'billAmount'=>$netAmountAdj,
                            'balanceAmount'=>$latestPendingAmt,
                            'paymentMode'=>'SR',
                            'taxableValue'=>$totalTaxableValue,
					        'taxAmount'=>$totalTaxAmount,
					        'cessAmount'=>$totalCessAmount,
                            'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                        );
                        $this->AllocationByManagerModel->insert('billpayments',$billPayment);//insert billpayment data
                    }
                }else{
                    for ($i=0; $i < count($returnedQty); $i++) {
                        if($returnedQty[$i] <= $qty[$i]){
                            $calAmt=$netAmount[$i]/$qty[$i];
                            $ReturnAmount=$returnAmt[$i]+($calAmt * $returnedQty[$i]);
                            $data['billsdetails']=$this->AllocationByManagerModel->loadBillDetailsID('billsdetails', $id[$i]);
                            
                            $data['bills']=$this->AllocationByManagerModel->load('bills', $billId);
                            $fsSrBillAmt=$data['bills'][0]['fsSrAmt'];
                            $oldSR=0+$returnedQty[$i];
                            $srTotalFs=$srTotalFs+$ReturnAmount;

                            $fsSrBillAmt=$srTotalFs;
                            
                            $oldSR= $oldSR;
                            $ReturnAmount= $ReturnAmount;
                            // if($qty[$i] >= $oldSR){
                                if($returnedQty[$i] > 0){
                                    $data = array(
                                        'fsReturnQty' => ($oldSR+$data['billsdetails'][0]['fsReturnQty']),
                                        'fsReturnAmt' => ($ReturnAmount+$data['billsdetails'][0]['fsReturnAmt'])
                                    ); 
                                    
                                    $this->AllocationByManagerModel->update('billsdetails',$data,$id[$i]);

                                     $srData = array(
                                        'billId' =>  $billId,
                                        'billItemId'=>$id[$i],
                                        'quantity'=>$oldSR,
                                        'createdAt'=>date('Y-m-d H:i:sa')
                                    );
                                    $this->AllocationByManagerModel->insert('allocation_sr_details',$srData);

                                    $data['billsdetails']=$this->AllocationByManagerModel->loadBillDetailsID('billsdetails', $id[$i]);
                                    //cess amount
                                    $cessAmount=$data['billsdetails'][0]['cessAmount']/$data['billsdetails'][0]['qty'];
                                    $cessAmount=round($returnedQty[$i]*$cessAmount,2);

                                    //tax amount
                                    $taxAmount=$data['billsdetails'][0]['taxAmount']/$data['billsdetails'][0]['qty'];
                                    $taxAmount=round($returnedQty[$i]*$taxAmount,2);

                                    //taxable amount
                                    $taxableValue=$data['billsdetails'][0]['taxableValue']/$data['billsdetails'][0]['qty'];
                                    $taxableValue=round($returnedQty[$i]*$taxableValue,2);

                                    $totalTaxableValue=$totalTaxableValue+$taxableValue;
                                    $totalTaxAmount=$totalTaxAmount+$taxAmount;
                                    $totalCessAmount=$totalCessAmount+$cessAmount;
                                }
                            // }
                        }
                    }

                    $data['bills']=$this->AllocationByManagerModel->loadBillsDetails('bills', $billId);
                    $OlderSrAmt=$data['bills'][0]['SRAmt'];
                    $OlderPendingAmt=$data['bills'][0]['pendingAmt'];

                    $latestSrAmt=0;
                    $latestPendingAmt=0;
                   
                    $fixTotal=$fsSrBillAmt;
                    $fsSrBillAmt=round($fsSrBillAmt);
                    $latestSrAmt=$OlderSrAmt+$fsSrBillAmt;
                    $latestPendingAmt=$OlderPendingAmt-$fsSrBillAmt;

                    $creditNoteAmt=0;
                    if(round($fsSrBillAmt)>round($onlyPending)){
                        $creditNoteAmt=(round($fsSrBillAmt)-round($onlyPending));
                    }

                    if($fsSrBillAmt>0){
                        $data = array('SRAmt' => $latestSrAmt,'pendingAmt'=>$latestPendingAmt,'creditNoteRenewal'=>$creditNoteAmt);  
                        $this->AllocationByManagerModel->update('bills',$data, $billId);

                        $history=array(
                            'billId'=>$billId,
                            'transactionAmount' =>$fsSrBillAmt,
                            'transactionStatus' =>'SR',
                            'transactionMode' =>'dr',
                            'transactionDate'=>date('Y-m-d H:i:sa'),
                            'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                            'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                        );
                        $this->AllocationByManagerModel->insert('bill_transaction_history',$history);
        
                        $billPayment=array(
                            'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                            'billId'=>$billId,
                            'date'=>date('Y-m-d H:i:sa'),
                            'paidAmount'=>$fsSrBillAmt,
                            'billAmount'=>$netAmountAdj,
                            'balanceAmount'=>$latestPendingAmt,
                            'paymentMode'=>'SR',
                            'taxableValue'=>$totalTaxableValue,
					        'taxAmount'=>$totalTaxAmount,
					        'cessAmount'=>$totalCessAmount,
                            'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                        );
                        $this->AllocationByManagerModel->insert('billpayments',$billPayment);//insert billpayment data
                    }
                }
            }
        }else{
            $totalTaxableValue=0;
            $totalTaxAmount=0;
            $totalCessAmount=0;

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

            $sumQty=$this->AllocationByManagerModel->getSum('billsdetails',$billId);
            $actualQty=$sumQty[0]['qtySum'];

            $srQty=number_format(array_sum($returnedQty),2);

            $status='SR';
           
            if($srQty<=$actualQty){
                for ($i=0; $i < count($returnedQty); $i++) {
                    if(!empty($returnedQty[$i]) || $returnedQty[$i] != 0.00){
                        if($returnedQty[$i] <= $qty[$i]){
                            $calAmtFix=$netAmount[$i]/$qty[$i];
                            $ReturnAmount=$returnAmt[$i]+($calAmtFix * $returnedQty[$i]);
                            $data['billsdetails']=$this->AllocationByManagerModel->loadBillDetailsID('billsdetails', $id[$i]);
                            
                            $data['bills']=$this->AllocationByManagerModel->load('bills', $billId);
                            // $fsSrBillAmtFix=$data['bills'][0]['fsSrAmt'];
                            $oldSR=0+$returnedQty[$i];
                            $srTotalFsFix=$srTotalFs+$ReturnAmount;
                            $fsSrBillAmtFix=$fsSrBillAmtFix+$srTotalFsFix;
                            $pendingAmounFix=$pendingAmounFix+$data['billsdetails'][0]['fsReturnAmt'];
                        }
                    }
                }

                if(round($fsSrBillAmtFix)>$pendingAmounFix){
                     echo "SR Amount is greater than pending amount.";
                    // $this->session->set_flashdata('item', array('message' => 'SR Amount is greater than pending amount.','class' => 'success'));
                    // redirect('AdHocController/billSearch');
                    exit;
                }
                
                for ($i=0; $i < count($returnedQty); $i++) {
                    if($returnedQty[$i] <= $qty[$i]){
                        $calAmt=$netAmount[$i]/$qty[$i];
                        $ReturnAmount=$returnAmt[$i]+($calAmt * $returnedQty[$i]);
                        $data['billsdetails']=$this->AllocationByManagerModel->loadBillDetailsID('billsdetails', $id[$i]);
                        
                        $data['bills']=$this->AllocationByManagerModel->load('bills', $billId);
                        $fsSrBillAmt=$data['bills'][0]['fsSrAmt'];
                        $oldSR=0+$returnedQty[$i];
                        $srTotalFs=$srTotalFs+$ReturnAmount;

                        $fsSrBillAmt=$srTotalFs;
                        
                        $oldSR= $oldSR;
                        $ReturnAmount= $ReturnAmount;
                        // if($qty[$i] >= $oldSR){
                            if($returnedQty[$i] > 0){
                                 $data = array(
                                    'fsReturnQty' => ($oldSR+$data['billsdetails'][0]['fsReturnQty']),
                                    'fsReturnAmt' => ($ReturnAmount+$data['billsdetails'][0]['fsReturnAmt'])
                                ); 
                                
                                $this->AllocationByManagerModel->update('billsdetails',$data,$id[$i]);

                                 $srData = array(
                                    'billId' =>  $billId,
                                    'billItemId'=>$id[$i],
                                    'quantity'=>$oldSR,
                                    'createdAt'=>date('Y-m-d H:i:sa')
                                );
                                $this->AllocationByManagerModel->insert('allocation_sr_details',$srData);

                                $data['billsdetails']=$this->AllocationByManagerModel->loadBillDetailsID('billsdetails', $id[$i]);
                                //cess amount
                                $cessAmount=$data['billsdetails'][0]['cessAmount']/$data['billsdetails'][0]['qty'];
                                $cessAmount=round($returnedQty[$i]*$cessAmount,2);

                                //tax amount
                                $taxAmount=$data['billsdetails'][0]['taxAmount']/$data['billsdetails'][0]['qty'];
                                $taxAmount=round($returnedQty[$i]*$taxAmount,2);

                                //taxable amount
                                $taxableValue=$data['billsdetails'][0]['taxableValue']/$data['billsdetails'][0]['qty'];
                                $taxableValue=round($returnedQty[$i]*$taxableValue,2);

                                $totalTaxableValue=$totalTaxableValue+$taxableValue;
                                $totalTaxAmount=$totalTaxAmount+$taxAmount;
                                $totalCessAmount=$totalCessAmount+$cessAmount;
                            }
                        // }
                    }
                }

                $data['bills']=$this->AllocationByManagerModel->loadBillsDetails('bills', $billId);
                $OlderSrAmt=$data['bills'][0]['SRAmt'];
                $OlderPendingAmt=$data['bills'][0]['pendingAmt'];

                $latestSrAmt=0;
                $latestPendingAmt=0;
               
                $fixTotal=$fsSrBillAmt;
                $fsSrBillAmt=round($fsSrBillAmt);
                $latestSrAmt=$OlderSrAmt+$fsSrBillAmt;
                $latestPendingAmt=$OlderPendingAmt-$fsSrBillAmt;

                if($fsSrBillAmt>0){
                    $data = array('SRAmt' => $latestSrAmt,'pendingAmt'=>$latestPendingAmt);  
                    $this->AllocationByManagerModel->update('bills',$data, $billId);

                    $history=array(
                        'billId'=>$billId,
                        'transactionAmount' =>$fsSrBillAmt,
                        'transactionStatus' =>'SR',
                        'transactionMode' =>'dr',
                        'transactionDate'=>date('Y-m-d H:i:sa'),
                        'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                        'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                    );
                    $this->AllocationByManagerModel->insert('bill_transaction_history',$history);
    
                    $billPayment=array(
                        'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                        'billId'=>$billId,
                        'date'=>date('Y-m-d H:i:sa'),
                        'paidAmount'=>$fsSrBillAmt,
                        'billAmount'=>$netAmountAdj,
                        'balanceAmount'=>$latestPendingAmt,
                        'paymentMode'=>'SR',
                        'taxableValue'=>$totalTaxableValue,
                        'taxAmount'=>$totalTaxAmount,
                        'cessAmount'=>$totalCessAmount,
                        'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                    );
                    $this->AllocationByManagerModel->insert('billpayments',$billPayment);//insert billpayment data
                }
            }
            
        }
    }

    public function addBillToAllocationBtn(){
        $currentBillId=trim($this->input->post('currentBillId'));
        $allocationId=trim($this->input->post('allocationId'));
        $currentPendingAmt=trim($this->input->post('currentPendingAmt'));

        $allocationTotalAmount=0;
        $allocationBillCount=0;
        $allocationSalesman="";

        $billInfo=$this->AllocationByManagerModel->load('bills',$currentBillId);

        if($billInfo[0]['netAmount']==$currentPendingAmt){
            if(!empty($allocationId)){
                $allocationDetails=$this->AllocationByManagerModel->load('allocations',$allocationId);
                if(!empty($allocationDetails)){

                    if(!empty($currentBillId)){
                        $billInformation=$this->AllocationByManagerModel->getUnallocatedBillById('bills',$currentBillId);
                        if(!empty($billInformation)){
                            $allocationSalesman=$allocationDetails[0]['allocationSalesman'].','.$billInformation[0]['salesman'];
                            $allocationTotalAmount=$allocationDetails[0]['allocationTotalAmount']+$billInformation[0]['pendingAmt'];
                            $allocationBillCount=1+$allocationDetails[0]['allocationBillCount'];

                            $updateAllocationDetails=array(
                                "allocationTotalAmount"=>$allocationTotalAmount,
                                "allocationBillCount"=>$allocationBillCount,
                                "allocationSalesman"=>$allocationSalesman
                            );
                            $this->AllocationByManagerModel->update('allocations',$updateAllocationDetails,$allocationId);
                        }       
                    }

                    $insAllocationDetail=array('billId'=>$currentBillId,'allocationId'=>$allocationId,'billStatus'=>1);
                    $this->AllocationByManagerModel->insert('allocationsbills',$insAllocationDetail);
                    if($this->db->affected_rows()>0){
                        
                        $updBills=array('billCurrentStatus'=>'Allocated in '.$allocationDetails[0]['allocationCode'],'billType'=>'allocatedbillCurrent','isAllocated'=>1,'isBilled'=>'0','isLostBill'=>'0');
                        $this->AllocationByManagerModel->update('bills',$updBills,$currentBillId);
                        if($this->db->affected_rows()>0){
                            $history=array(
                                'billId'=>$currentBillId,
                                'allocationId'=>$allocationId,
                                'transactionStatus' =>'Allocated',
                                'transactionDate'=>date('Y-m-d H:i:sa'),
                                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                            );
                            $this->AllocationByManagerModel->insert('bill_transaction_history',$history);
                            echo "Record inserted";
                        }else{
                            echo "Record not inserted";
                        }
                    }
                }
            }
            
        }else{

            if(!empty($allocationId)){
                $allocationDetails=$this->AllocationByManagerModel->load('allocations',$allocationId);
                if(!empty($allocationDetails)){
                    if(!empty($currentBillId)){
                        $billInformation=$this->AllocationByManagerModel->getUnallocatedBillById('bills',$currentBillId);
                        if(!empty($billInformation)){
                            $allocationSalesman=$allocationDetails[0]['allocationSalesman'].','.$billInformation[0]['salesman'];
                            $allocationTotalAmount=$allocationDetails[0]['allocationTotalAmount']+$billInformation[0]['pendingAmt'];
                            $allocationBillCount=1+$allocationDetails[0]['allocationBillCount'];

                            $updateAllocationDetails=array(
                                "allocationTotalAmount"=>$allocationTotalAmount,
                                "allocationBillCount"=>$allocationBillCount,
                                "allocationSalesman"=>$allocationSalesman
                            );
                            $this->AllocationByManagerModel->update('allocations',$updateAllocationDetails,$allocationId);
                        }       
                    }

                    $insAllocationDetail=array('billId'=>$currentBillId,'allocationId'=>$allocationId,'billStatus'=>2);
                    $this->AllocationByManagerModel->insert('allocationsbills',$insAllocationDetail);
                    if($this->db->affected_rows()>0){
                        
                        $updBills=array('billCurrentStatus'=>'Allocated in '.$allocationDetails[0]['allocationCode'],'billType'=>'allocatedbillPass','isAllocated'=>1,'isBilled'=>'0','isLostBill'=>'0');
                        $this->AllocationByManagerModel->update('bills',$updBills,$currentBillId);
                        if($this->db->affected_rows()>0){
                            $history=array(
                                'billId'=>$currentBillId,
                                'allocationId'=>$allocationId,
                                'transactionStatus' =>'Allocated',
                                'transactionDate'=>date('Y-m-d H:i:sa'),
                                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                            );
                            $this->AllocationByManagerModel->insert('bill_transaction_history',$history);
                            echo "Record inserted";
                        }else{
                            echo "Record not inserted";
                        }
                    }
                }
            }
        }
    }

    public function cashCollectionBtn(){
        $empName=trim($this->input->post('empName'));
        $empId=trim($this->input->post('empId'));
        $collectedAmt=trim($this->input->post('collectedAmt'));
        $currentPendingAmt=trim($this->input->post('currentPendingAmt'));
        $currentBillId=trim($this->input->post('currentBillId'));

        $a2000=trim($this->input->post('a2000'));
        $a1000=trim($this->input->post('a1000'));
        $a500=trim($this->input->post('a500'));
        $a200=trim($this->input->post('a200'));
        $a100=trim($this->input->post('a100'));
        $a50=trim($this->input->post('a50'));
        $a20=trim($this->input->post('a20'));
        $a10=trim($this->input->post('a10'));
        $coin=trim($this->input->post('coin'));

        $updatedAt=date('Y-m-d H:i:sa');
        $updatedBy=$this->session->userdata[$this->projectSessionName]['id'];

        $billInfo=$this->AllocationByManagerModel->load('bills',$currentBillId);//get bill detail
        $netAmount=$billInfo[0]['netAmount'];
        $pendingAmt=$billInfo[0]['pendingAmt'];
        $receivedAmt=$billInfo[0]['receivedAmt'];
        $compName=$billInfo[0]['compName'];
        $billNo=$billInfo[0]['billNo'];
        $bill_no="Bill No. ".$billNo;

        $lastBal=$this->AllocationByManagerModel->lastRecordValue();//get closing balance
        $openCloseBal=$lastBal['openCloseBalance'];
        if($openCloseBal=='' || $openCloseBal==Null){
            $openCloseBal=0.0;
        }

        $totalClosingBalance=$openCloseBal+$collectedAmt;//final closing balance

        $finalPending=$pendingAmt-$collectedAmt;//final pending for current bill
        $finalReceived=$receivedAmt+$collectedAmt;//final received for current bill
        
        $billUpdate=array();
        if($netAmount==$pendingAmt){
            $billUpdate=array(
                'billType'=>'allocatedbillCurrent','isResendBill'=>'0','receivedAmt'=>$finalReceived,'pendingAmt'=>$finalPending
            );
        }else{
            $billUpdate=array(
                'receivedAmt'=>$finalReceived,'isResendBill'=>'0','pendingAmt'=>$finalPending
            );
        }
        

        $this->AllocationByManagerModel->update('bills',$billUpdate,$currentBillId);//update bill amount
        if($this->db->affected_rows()>0){
            $history=array(
                'billId'=>$currentBillId,
                'transactionAmount' =>$collectedAmt,
                'transactionStatus' =>'Cash',
                'transactionMode'=>'dr',
                'transactionDate'=>date('Y-m-d H:i:sa'),
                'empId'=>$empId,
                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
            );
            $this->AllocationByManagerModel->insert('bill_transaction_history',$history);

            $billPayment=array(
                'empId'=>$empId,'billId'=>$currentBillId,'date'=>$updatedAt,'paidAmount'=>$collectedAmt,'billAmount'=>$netAmount,'balanceAmount'=>$finalPending,'paymentMode'=>'Cash','updatedBy'=>$updatedBy
            );
            $this->AllocationByManagerModel->insert('billpayments',$billPayment);//insert billpayment data

            $notesDetails=array(
                'billId'=>$currentBillId,'empId'=>$empId,'note2000'=>$a2000,'note1000'=>$a1000,'note500'=>$a500,'note200'=>$a200,'note100'=>$a100,'note50'=>$a50,'note20'=>$a20,'note10'=>$a10,'coins'=>$coin,'collectedAmt'=>$collectedAmt,'updatedAt'=>$updatedAt,'updatedBy'=>$updatedBy
            );
            $this->AllocationByManagerModel->insert('notesdetails',$notesDetails);//insert notes details
            if($this->db->affected_rows()>0){
                $notesId=$this->db->insert_id();
                $marketCollection=array(
                    'company'=>$compName,'billId'=>$currentBillId,'date'=>$updatedAt,'employeeId'=>$empId,'notesId'=>$notesId,'amount'=>$collectedAmt,'nature'=>'Office Collection','narration'=>$bill_no,'inoutStatus'=>'Inflow','openCloseBalance'=>$totalClosingBalance,'updatedBy'=>$updatedBy
                );

                $this->AllocationByManagerModel->insert('expences',$marketCollection);//insert expenses
                if($this->db->affected_rows()>0){
                    echo "Record updated";
                }
            }

        }else{
            echo "Record not updated";
        }

    }

      public function chequeCollectionBtn(){
        $empName=trim($this->input->post('empName'));
        $empId=trim($this->input->post('empId'));
        $collectedAmt=trim($this->input->post('chequeAmount'));
        $currentPendingAmt=trim($this->input->post('currentPendingAmt'));
        $currentBillId=trim($this->input->post('currentBillId'));

        $updatedAt=date('Y-m-d H:i:sa');
        $updatedBy=$this->session->userdata[$this->projectSessionName]['id'];

        $billInfo=$this->AllocationByManagerModel->load('bills',$currentBillId);//get bill detail
        $netAmount=$billInfo[0]['netAmount'];
        $pendingAmt=$billInfo[0]['pendingAmt'];
        $receivedAmt=$billInfo[0]['receivedAmt'];

        $finalPending=$pendingAmt-$collectedAmt;//final pending for current bill
        $finalReceived=$receivedAmt+$collectedAmt;//final received for current bill
        
        $billUpdate=array();
        if($netAmount==$pendingAmt){
            $billUpdate=array(
                'billType'=>'allocatedbillCurrent','isResendBill'=>'0','receivedAmt'=>$finalReceived,'pendingAmt'=>$finalPending
            );
        }else{
            $billUpdate=array(
                'receivedAmt'=>$finalReceived,'isResendBill'=>'0','pendingAmt'=>$finalPending
            );
        }

        $this->AllocationByManagerModel->update('bills',$billUpdate,$currentBillId);//update bill amount
        if($this->db->affected_rows()>0){
            $history=array(
                'billId'=>$currentBillId,
                'transactionAmount' =>$collectedAmt,
                'transactionStatus' =>'Cheque',
                'transactionMode'=>'dr',
                'transactionDate'=>date('Y-m-d H:i:sa'),
                'empId'=>$empId,
                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
            );
            $this->AllocationByManagerModel->insert('bill_transaction_history',$history);

            $billPayment=array(
                'empId'=>$empId,'billId'=>$currentBillId,'date'=>$updatedAt,'paidAmount'=>$collectedAmt,'billAmount'=>$netAmount,'balanceAmount'=>$finalPending,'paymentMode'=>'Cheque','isLostStatus'=>2,'updatedBy'=>$updatedBy
            );
            $this->AllocationByManagerModel->insert('billpayments',$billPayment);//insert billpayment data
            echo "Record updated";
        }else{
            echo "Record not updated";
        }
    }

    public function neftCollectionBtn(){
        $empName=trim($this->input->post('empName'));
        $empId=trim($this->input->post('empId'));
        $collectedAmt=trim($this->input->post('neftAmount'));
        $currentPendingAmt=trim($this->input->post('currentPendingAmt'));
        $currentBillId=trim($this->input->post('currentBillId'));

        $updatedAt=date('Y-m-d H:i:sa');
        $updatedBy=$this->session->userdata[$this->projectSessionName]['id'];

        $billInfo=$this->AllocationByManagerModel->load('bills',$currentBillId);//get bill detail
        $netAmount=$billInfo[0]['netAmount'];
        $pendingAmt=$billInfo[0]['pendingAmt'];
        $receivedAmt=$billInfo[0]['receivedAmt'];

        $finalPending=$pendingAmt-$collectedAmt;//final pending for current bill
        $finalReceived=$receivedAmt+$collectedAmt;//final received for current bill
        
        $billUpdate=array();
        if($netAmount==$pendingAmt){
            $billUpdate=array(
                'billType'=>'allocatedbillCurrent','isResendBill'=>'0','receivedAmt'=>$finalReceived,'pendingAmt'=>$finalPending
            );
        }else{
            $billUpdate=array(
                'receivedAmt'=>$finalReceived,'isResendBill'=>'0','pendingAmt'=>$finalPending
            );
        }

        $this->AllocationByManagerModel->update('bills',$billUpdate,$currentBillId);//update bill amount
        if($this->db->affected_rows()>0){
            $history=array(
                'billId'=>$currentBillId,
                'transactionAmount' =>$collectedAmt,
                'transactionStatus' =>'NEFT',
                'transactionMode'=>'dr',
                'transactionDate'=>date('Y-m-d H:i:sa'),
                'empId'=>$empId,
                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
            );
            $this->AllocationByManagerModel->insert('bill_transaction_history',$history);

            $billPayment=array(
                'empId'=>$empId,'billId'=>$currentBillId,'date'=>$updatedAt,'paidAmount'=>$collectedAmt,'billAmount'=>$netAmount,'balanceAmount'=>$finalPending,'paymentMode'=>'NEFT','isLostStatus'=>2,'updatedBy'=>$updatedBy
            );
            $this->AllocationByManagerModel->insert('billpayments',$billPayment);//insert billpayment data
            echo "Record updated";
        }else{
            echo "Record not updated";
        }
    }

    public function cashDiscountCollectionBtn(){
        $empName=trim($this->input->post('empName'));
        $empId=trim($this->input->post('empId'));
        $collectedAmt=trim($this->input->post('cdAmount'));
        $remark=trim($this->input->post('cdRemark'));
        $currentPendingAmt=trim($this->input->post('currentPendingAmt'));
        $currentBillId=trim($this->input->post('currentBillId'));

        $updatedAt=date('Y-m-d H:i:sa');
        $updatedBy=$this->session->userdata[$this->projectSessionName]['id'];

        $billInfo=$this->AllocationByManagerModel->load('bills',$currentBillId);//get bill detail
        $netAmount=$billInfo[0]['netAmount'];
        $pendingAmt=$billInfo[0]['pendingAmt'];
        $cd=$billInfo[0]['cd'];

        $finalPending=$pendingAmt-$collectedAmt;//final pending for current bill
        $finalCd=$cd+$collectedAmt;//final received for current bill
        
        $billUpdate=array();
        if($netAmount==$pendingAmt){
            $billUpdate=array(
                'billType'=>'allocatedbillCurrent','isResendBill'=>'0','cd'=>$finalCd,'pendingAmt'=>$finalPending
            );
        }else{
            $billUpdate=array(
                'cd'=>$finalCd,'isResendBill'=>'0','pendingAmt'=>$finalPending
            );
        }

        $this->AllocationByManagerModel->update('bills',$billUpdate,$currentBillId);//update bill amount
        if($this->db->affected_rows()>0){
            $history=array(
                'billId'=>$currentBillId,
                'transactionAmount' =>$collectedAmt,
                'transactionStatus' =>'CD',
                'transactionMode'=>'dr',
                'remark'=>$remark,
                'transactionDate'=>date('Y-m-d H:i:sa'),
                'empId'=>$empId,
                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
            );
            $this->AllocationByManagerModel->insert('bill_transaction_history',$history);

            $billRemark=array(
                'billId'=>$currentBillId,'empId'=>$empId,'remark'=>$remark,'amount'=>$collectedAmt,'updatedAt'=>$updatedAt,'updatedBy'=>$updatedBy
            );
            $this->AllocationByManagerModel->insert('bill_remark_history',$billRemark);//insert remark data

            $billPayment=array(
                'empId'=>$empId,'billId'=>$currentBillId,'date'=>$updatedAt,'paidAmount'=>$collectedAmt,'billAmount'=>$netAmount,'balanceAmount'=>$finalPending,'paymentMode'=>'CD','isLostStatus'=>2,'updatedBy'=>$updatedBy
            );
            $this->AllocationByManagerModel->insert('billpayments',$billPayment);
            echo "Record updated";
        }else{
            echo "Record not updated";
        }
    }

    public function debitToEmployeeCollectionBtn(){
        $empName=trim($this->input->post('empName'));
        $empId=trim($this->input->post('empId'));
        $collectedAmt=trim($this->input->post('debitAmount'));
        $remark=trim($this->input->post('debitRemark'));
        $currentPendingAmt=trim($this->input->post('currentPendingAmt'));
        $currentBillId=trim($this->input->post('currentBillId'));

        $updatedAt=date('Y-m-d H:i:sa');
        $updatedBy=$this->session->userdata[$this->projectSessionName]['id'];

        $billInfo=$this->AllocationByManagerModel->load('bills',$currentBillId);//get bill detail
        $netAmount=$billInfo[0]['netAmount'];
        $pendingAmt=$billInfo[0]['pendingAmt'];
        $debit=$billInfo[0]['debit'];

        $finalPending=$pendingAmt-$collectedAmt;//final pending for current bill
        $finalDebit=$debit+$collectedAmt;//final received for current bill
        
        $billUpdate=array();
        if($netAmount==$pendingAmt){
            $billUpdate=array(
                'billType'=>'allocatedbillCurrent','isResendBill'=>'0','debit'=>$finalDebit,'pendingAmt'=>$finalPending
            );
        }else{
            $billUpdate=array(
                'debit'=>$finalDebit,'isResendBill'=>'0','pendingAmt'=>$finalPending
            );
        }

        $this->AllocationByManagerModel->update('bills',$billUpdate,$currentBillId);//update bill amount
        if($this->db->affected_rows()>0){
            $history=array(
                'billId'=>$currentBillId,
                'transactionAmount' =>$collectedAmt,
                'transactionStatus' =>'Debit To Employee',
                'transactionMode'=>'dr',
                'remark'=>$remark,
                'transactionDate'=>date('Y-m-d H:i:sa'),
                'empId'=>$empId,
                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
            );
            $this->AllocationByManagerModel->insert('bill_transaction_history',$history);

            $billRemark=array(
                'billId'=>$currentBillId,'empId'=>$empId,'remark'=>$remark,'amount'=>$collectedAmt,'updatedAt'=>$updatedAt,'updatedBy'=>$updatedBy
            );
            $this->AllocationByManagerModel->insert('bill_remark_history',$billRemark);//insert remark data

            $empDebit=array(
                'billId'=>$currentBillId,'empId'=>$empId,'transactionType'=>'dr','description'=>$remark,'amount'=>$collectedAmt,'createdAt'=>$updatedAt,'createdBy'=>$updatedBy
            );
             $this->AllocationByManagerModel->insert('emptransactions',$empDebit);//insert remark data

            $billPayment=array(
                'empId'=>$empId,'billId'=>$currentBillId,'date'=>$updatedAt,'paidAmount'=>$collectedAmt,'billAmount'=>$netAmount,'balanceAmount'=>$finalPending,'paymentMode'=>'Debit To Employee','isLostStatus'=>2,'updatedBy'=>$updatedBy
            );
            $this->AllocationByManagerModel->insert('billpayments',$billPayment);
            echo "Record updated";
        }else{
            echo "Record not updated";
        }
    }

    public function officeAdjustmentCollectionBtn(){
        $empName=trim($this->input->post('empName'));
        $empId=trim($this->input->post('empId'));
        $collectedAmt=trim($this->input->post('officeAdjAmount'));
        $remark=trim($this->input->post('officeAdjRemark'));
        $currentPendingAmt=trim($this->input->post('currentPendingAmt'));
        $currentBillId=trim($this->input->post('currentBillId'));

        $updatedAt=date('Y-m-d H:i:sa');
        $updatedBy=$this->session->userdata[$this->projectSessionName]['id'];

        $billInfo=$this->AllocationByManagerModel->load('bills',$currentBillId);//get bill detail
        $netAmount=$billInfo[0]['netAmount'];
        $pendingAmt=$billInfo[0]['pendingAmt'];
        $officeAdjustment=$billInfo[0]['officeAdjustmentBillAmount'];

        $finalPending=$pendingAmt-$collectedAmt;//final pending for current bill
        $finalOA=$officeAdjustment+$collectedAmt;//final received for current bill
        
        $billUpdate=array(
            'billType'=>'officeAdjustmentBill','isResendBill'=>'0','officeAdjustmentBillAmount'=>$finalOA,'pendingAmt'=>$finalPending
        );

        $this->AllocationByManagerModel->update('bills',$billUpdate,$currentBillId);//update bill amount
        if($this->db->affected_rows()>0){
            $history=array(
                'billId'=>$currentBillId,
                'transactionAmount' =>$collectedAmt,
                'transactionStatus' =>'Office Adjustment',
                'transactionMode'=>'dr',
                'remark'=>$remark,
                'transactionDate'=>date('Y-m-d H:i:sa'),
                'empId'=>$empId,
                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
            );
            $this->AllocationByManagerModel->insert('bill_transaction_history',$history);

            $billRemark=array(
                'billId'=>$currentBillId,'empId'=>$empId,'remark'=>$remark,'amount'=>$collectedAmt,'updatedAt'=>$updatedAt,'updatedBy'=>$updatedBy
            );
            $this->AllocationByManagerModel->insert('bill_remark_history',$billRemark);//insert remark data

            $billPayment=array(
                'empId'=>$empId,'billId'=>$currentBillId,'date'=>$updatedAt,'paidAmount'=>$collectedAmt,'billAmount'=>$netAmount,'balanceAmount'=>$finalPending,'paymentMode'=>'Office Adjustment','isLostStatus'=>2,'updatedBy'=>$updatedBy
            );
            $this->AllocationByManagerModel->insert('billpayments',$billPayment);
           
            echo "Record updated";
        }else{
            echo "Record not updated";
        }
    }

    public function empDeliveryCollectionBtn(){
        $empName=trim($this->input->post('empName'));
        $empId=trim($this->input->post('empId'));
        $remark=trim($this->input->post('deliveryRemark'));
        $currentPendingAmt=trim($this->input->post('currentPendingAmt'));
        $currentBillId=trim($this->input->post('currentBillId'));

        $updatedAt=date('Y-m-d H:i:sa');
        $updatedBy=$this->session->userdata[$this->projectSessionName]['id'];

        $billInfo=$this->AllocationByManagerModel->load('bills',$currentBillId);//get bill detail
        $netAmount=$billInfo[0]['netAmount'];
        $pendingAmt=$billInfo[0]['pendingAmt'];

        $billUpdate=array(
            'billCurrentStatus'=>'Direct Delivery','isResendBill'=>'0','billType'=>'adHocDeliveryBill','deliveryEmpName'=>$empName,'isDirectDeliveryBill'=>1
        );

        $this->AllocationByManagerModel->update('bills',$billUpdate,$currentBillId);//update bill amount
        if($this->db->affected_rows()>0){
            $history=array(
                'billId'=>$currentBillId,
                'transactionAmount' =>$collectedAmt,
                'transactionStatus' =>'Direct Delivery',
                'transactionDate'=>date('Y-m-d H:i:sa'),
                'empId'=>$empId,
                'remark'=>$remark,
                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
            );
            $this->AllocationByManagerModel->insert('bill_transaction_history',$history);

            $billRemark=array(
                'billId'=>$currentBillId,'empId'=>$empId,'remark'=>$remark,'updatedAt'=>$updatedAt,'updatedBy'=>$updatedBy
            );
            $this->AllocationByManagerModel->insert('bill_remark_history',$billRemark);//insert remark data
           
            echo "Record updated";
        }else{
            echo "Record not updated";
        }
    }

    public function otherAdjustmentCollectionBtn(){
        $empName=trim($this->input->post('empName'));
        $empId=trim($this->input->post('empId'));
        $collectedAmt=trim($this->input->post('otherAdjAmount'));
        $remark=trim($this->input->post('otherAdjRemark'));
        $currentPendingAmt=trim($this->input->post('currentPendingAmt'));
        $currentBillId=trim($this->input->post('currentBillId'));

        $updatedAt=date('Y-m-d H:i:sa');
        $updatedBy=$this->session->userdata[$this->projectSessionName]['id'];

        $billInfo=$this->AllocationByManagerModel->load('bills',$currentBillId);//get bill detail
        $netAmount=$billInfo[0]['netAmount'];
        $pendingAmt=$billInfo[0]['pendingAmt'];
        $otherAdjustment=$billInfo[0]['otherAdjustment'];

        $finalPending=$pendingAmt-$collectedAmt;//final pending for current bill
        $finalOA=$otherAdjustment+$collectedAmt;//final received for current bill
        
        $billUpdate=array();
        if($netAmount==$pendingAmt){
            $billUpdate=array(
                'billType'=>'allocatedbillCurrent','isResendBill'=>'0','otherAdjustment'=>$finalOA,'pendingAmt'=>$finalPending
            );
        }else{
            $billUpdate=array(
                'otherAdjustment'=>$finalOA,'isResendBill'=>'0','pendingAmt'=>$finalPending
            );
        }

        $this->AllocationByManagerModel->update('bills',$billUpdate,$currentBillId);//update bill amount
        if($this->db->affected_rows()>0){
            $history=array(
                'billId'=>$currentBillId,
                'transactionAmount' =>$collectedAmt,
                'transactionStatus' =>'Other Adjustment',
                'transactionMode'=>'dr',
                'remark'=>$remark,
                'transactionDate'=>date('Y-m-d H:i:sa'),
                'empId'=>$empId,
                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
            );
            $this->AllocationByManagerModel->insert('bill_transaction_history',$history);

            $billRemark=array(
                'billId'=>$currentBillId,'empId'=>$empId,'remark'=>$remark,'amount'=>$collectedAmt,'updatedAt'=>$updatedAt,'updatedBy'=>$updatedBy
            );
            $this->AllocationByManagerModel->insert('bill_remark_history',$billRemark);//insert remark data

            $billPayment=array(
                'empId'=>$empId,'billId'=>$currentBillId,'date'=>$updatedAt,'paidAmount'=>$collectedAmt,'billAmount'=>$netAmount,'balanceAmount'=>$finalPending,'paymentMode'=>'Other Adjustment','isLostStatus'=>2,'updatedBy'=>$updatedBy
            );
            $this->AllocationByManagerModel->insert('billpayments',$billPayment);
           
           
            echo "Record updated";
        }else{
            echo "Record not updated";
        }
    }

    public function getSrDetailsBtn(){
        $billId=trim($this->input->post('currentBillId'));
        $billsInfo=$this->AllocationByManagerModel->load('bills', $billId);
        $billDetails=$this->AllocationByManagerModel->getBillDetailInfo('billsdetails',$billId);

    ?>
    <!-- <form action="<?php echo site_url('BillTransactionController/updateSRCreditAdj');?>" method="post"> -->
    <div class="col-md-12">
         <table style="font-size: 12px" id="SrTable" class="table table-bordered table-striped table-hover" data-page-length='100'>
                <span id="all_id" style="display:none"></span>
                <thead>
                    <tr>
                        <th>S. No</th>
                        <th>Product Name</th>
                        <th>MRP</th>
                        <th>Billed Qty</th>
                        <th>Net Amount</th>
                        <th>Total SR</th>
                        <th>SR Qty</th>
                        <th>Return Amount</th>
                    </tr>
                </thead>
                <tfoot>
                     <tr>
                        <th>S. No</th>
                        <th>Product Name</th>
                        <th>MRP</th>
                        <th>Qty</th>
                        <th>Net Amount</th>
                        <th>Old SR</th>
                        <th>SR Qty</th>
                        <th>Return Amount</th>
                    </tr>
                </tfoot>
                <tbody>


                    <?php
                    if(!empty($billDetails)){
                      $no=0;
                      foreach ($billDetails as $data) 
                        {
                         $no++; 
                         $id_is=$data['id'];
                         $id_qty=$data['qty'];
                         $id_fs_qty=$data['fsReturnQty'];
                      ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $data['productName']; ?></td>
                     <input type="hidden" id="prodName_id" name="productName[]" value="<?php echo $data['productName']; ?>" readonly>
                    <td>
                        <?php echo $data['mrp']; ?> 
                        <input type="hidden" id="mrp_id" name="mrp[]" value="<?php echo $data['mrp']; ?>" readonly>
                    </td>
                    <td>
                        <?php echo $data['qty']; ?>
                          <input type="hidden" id="qty_id<?php echo $no; ?>" name="qty[]" value="<?php echo $data['qty']; ?>" readonly> 
                          <input type="hidden" name="fsReturnQty[]" value="<?php echo $data['fsReturnQty']; ?>" readonly> 
                    </td> 
                    <td>
                        <?php echo $data['netAmount']; ?>
                        <input type="hidden" id="netAmount_id<?php echo $no; ?>" name="netAmount[]" value="<?php echo $data['netAmount']; ?>" readonly>
                        <input type="hidden" id="selAmount_id" name="selAmount[]" value="<?php echo $data['sellingRate']; ?>" readonly>         
                    </td>
                     <td>
                         <?php echo $data['fsReturnQty']; ?>
                         <input type="hidden" id="id_id" name="id[]" value="<?php echo $data['id']; ?>">
                         <input type="hidden" id="billId_id" name="billId" value="<?php echo $data['billId']; ?>">
                    </td>
                    <td>
                    <?php if($data['qty']==$data['fsReturnQty']){?>
                        <input type="text" style="height:25px;width: 50%" onkeypress="return numbersonly(this, event);" id="returnedQty<?php echo $no; ?>" onblur="checkQtyPerItem(this,'<?php echo $no; ?>','<?php echo $id_qty; ?>','<?php echo $id_fs_qty; ?>','<?php echo $billsInfo[0]['netAmount'];?>','<?php echo $billsInfo[0]['pendingAmt'];?>','<?php echo $billsInfo[0]['SRAmt'];?>','<?php echo $billsInfo[0]['receivedAmt'];?>','<?php echo $billsInfo[0]['fsCashAmt'];?>','<?php echo $billsInfo[0]['fsChequeAmt'];?>','<?php echo $billsInfo[0]['fsNeftAmt'];?>','<?php echo $billsInfo[0]['fsSrAmt'];?>')" onfocus="this.select();" autofocus="autofocus" class="form-control" name="returnedQty[]" value="<?php echo '0'; ?>">
                        <span style="color:red" id="data_err<?php echo $no; ?>"></span>
                        
                     <?php }else{ ?>
                         <input id="returnedQty<?php echo $no; ?>" style="height:25px;width: 50%" onkeypress="return numbersonly(this, event);" onblur="checkQtyPerItem(this,'<?php echo $no; ?>','<?php echo $id_qty; ?>','<?php echo $id_fs_qty; ?>','<?php echo $billsInfo[0]['netAmount'];?>','<?php echo $billsInfo[0]['pendingAmt'];?>','<?php echo $billsInfo[0]['SRAmt'];?>','<?php echo $billsInfo[0]['receivedAmt'];?>','<?php echo $billsInfo[0]['fsCashAmt'];?>','<?php echo $billsInfo[0]['fsChequeAmt'];?>','<?php echo $billsInfo[0]['fsNeftAmt'];?>','<?php echo $billsInfo[0]['fsSrAmt'];?>')" onfocus="this.select();" autofocus="autofocus" type="text" class="form-control" name="returnedQty[]" value="<?php echo '0'; ?>">
                         <span style="color:red" id="data_err<?php echo $no; ?>"></span> 
                     <?php }?>
                    </td>
                    <td>
                        <?php echo $data['fsReturnAmt']; ?>  
                         <input type="hidden" id="returnAmt_id<?php echo $no; ?>" name="returnAmt[]" value="<?php echo $data['returnAmt']; ?>">             
                    </td>
               </tr>  

                 <?php
                    }
                }else{ ?>
                    <tr><td>No data available</td></tr>
              <?php  } ?>
                </tbody>
            </table>
        </div>

   
        <div class="col-md-12">
            <div class="col-md-4">
                <button id="srBtn" type="button" class="btn btn-primary m-t-15 waves-effect">
                    <i class="material-icons">save</i> 
                    <span class="icon-name">
                     Save SR
                    </span>
                </button>
                <?php if($billsInfo[0]['receivedAmt'] == 0.00){ ?>
                    <button type="button" id="fsrBtn" data-id="<?php echo $billId ?>" class="btn btn-primary m-t-15 waves-effect">
                        <i class="material-icons">save</i> 
                        <span class="icon-name">
                         Save FSR
                        </span>
                    </button>
                <?php } ?>
               
                <button type="button" data-dismiss="modal" class="btn btn-primary m-t-15 waves-effect">
                    <i class="material-icons">cancel</i> 
                    <span class="icon-name"> Cancel</span>
                </button>
            </div>
        </div>
    <!-- </form> -->

    <?php
    
    }

    public function confirmFSRBtn(){
        $billId=trim($this->input->post('billId'));
        $billNetAmt=$this->AllocationByManagerModel->load('bills', $billId);
        $fixNetAmt=$billNetAmt[0]['netAmount'];//net amount from bills by billid

        $creditAdj=round($billNetAmt[0]['creditAdjustment']);
        $totalFSR=$creditAdj+$fixNetAmt;
        if($creditAdj>0){
            $billDetail=$this->AllocationByManagerModel->loadBillDetails('billsdetails', $billId);
            $srTotalFs=0;
            $id=0;
            foreach($billDetail as $bill){
                $id = $bill['id'];
                // $billId = $bill['billId'];
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

                $data['billsdetails']=$this->AllocationByManagerModel->loadBillDetailsID('billsdetails', $id);
                $oldSR=$returnedQty+$actReturnQty;
                $srTotalFs=$srTotalFs+$ReturnAmount;
                
                $data = array(
                    'fsReturnQty' => $oldSR,
                    'fsReturnAmt' =>  $ReturnAmount
                );

                $this->AllocationByManagerModel->update('billsdetails',$data,$id);
                if($this->db->affected_rows()>0){
                    $srData = array(
                        'billId' =>  $billId,
                        'billItemId'=>$id,
                        'quantity'=>$oldSR,
                        'createdAt'=>date('Y-m-d H:i:sa')
                    );
                    $this->AllocationByManagerModel->insert('allocation_sr_details',$srData);
                } 
            }
            $dataBills = array('isFsrBill'=>1,'billType'=>'allocatedbillPass','SRAmt' => $fixNetAmt,'creditNoteRenewal'=>$creditAdj,'pendingAmt'=>0);  
            $this->AllocationByManagerModel->update('bills',$dataBills, $billId);
            if($this->db->affected_rows()>0){
                echo "Record Updated";
            }else{
                echo "Record not Updated";
            }
        }else{
            $billDetail=$this->AllocationByManagerModel->loadBillDetails('billsdetails', $billId);
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

                $data['billsdetails']=$this->AllocationByManagerModel->loadBillDetailsID('billsdetails', $id);
                $oldSR=$returnedQty+$actReturnQty;
                $srTotalFs=$srTotalFs+$ReturnAmount;
                
                $data = array(
                    'fsReturnQty' => $oldSR,
                    'fsReturnAmt' =>  $ReturnAmount
                );

                $this->AllocationByManagerModel->update('billsdetails',$data,$id);
                if($this->db->affected_rows()>0){
                    $srData = array(
                        'billId' =>  $billId,
                        'billItemId'=>$id,
                        'quantity'=>$oldSR,
                        'createdAt'=>date('Y-m-d H:i:sa')
                    );
                    $this->AllocationByManagerModel->insert('allocation_sr_details',$srData);
                }
            }
            $dataBills = array('isFsrBill'=>1,'billType'=>'allocatedbillPass','SRAmt' => $fixNetAmt,'pendingAmt'=>0);  
            $this->AllocationByManagerModel->update('bills',$dataBills, $billId);
            if($this->db->affected_rows()>0){
                echo "Record Updated";
            }else{
                echo "Record not Updated";
            }
        }

        
    }

    public function updateSRCreditAdjBtn() {
        $data['msg']='';   
        
        $id = $this->input->post('id');
        $billId = $this->input->post('billId');

        $creditBillCheck=$this->AllocationByManagerModel->load('bills',$billId);
        $creditAdjAmount=$creditBillCheck[0]['creditAdjustment'];
        $netAmountAdj=$creditBillCheck[0]['netAmount'];
        $pendingAmounFix = $creditBillCheck[0]['pendingAmt'];


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

            $sumQty=$this->AllocationByManagerModel->getSum('billsdetails',$billId);
            $actualQty=$sumQty[0]['qtySum'];

            $srQty=number_format(array_sum($returnedQty),2);

            $status='SR';
           
            if($srQty<=$actualQty){
                for ($i=0; $i < count($returnedQty); $i++) {
                    if(!empty($returnedQty[$i]) || $returnedQty[$i] != 0.00){
                        if($returnedQty[$i] <= $qty[$i]){
                            $calAmtFix=$netAmount[$i]/$qty[$i];
                            $ReturnAmount=$returnAmt[$i]+($calAmtFix * $returnedQty[$i]);
                            $data['billsdetails']=$this->AllocationByManagerModel->loadBillDetailsID('billsdetails', $id[$i]);
                            
                            $data['bills']=$this->AllocationByManagerModel->load('bills', $billId);
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
                    $creditNoteAmt=round($fsSrBillAmtFix-$onlyPending);
                    
                    if($creditNoteAmt>round($creditAdjAmount)){
                        echo "SR Amount is greater than pending amount.";
                        // $this->session->set_flashdata('item', array('message' => 'SR Amount is greater than pending amount.','class' => 'success'));
                        // redirect('AdHocController/billSearch');
                        exit;
                    }

                    for ($i=0; $i < count($returnedQty); $i++) {
                        if($returnedQty[$i] <= $qty[$i]){
                            $calAmt=$netAmount[$i]/$qty[$i];
                            $ReturnAmount=$returnAmt[$i]+($calAmt * $returnedQty[$i]);
                            $data['billsdetails']=$this->AllocationByManagerModel->loadBillDetailsID('billsdetails', $id[$i]);
                            
                            $data['bills']=$this->AllocationByManagerModel->load('bills', $billId);
                            $fsSrBillAmt=$data['bills'][0]['fsSrAmt'];
                            $oldSR=0+$returnedQty[$i];
                            $srTotalFs=$srTotalFs+$ReturnAmount;

                            $fsSrBillAmt=round($srTotalFs);

                            $oldSR= $oldSR;
                            $ReturnAmount= $ReturnAmount;
                            // if($qty[$i] >= $oldSR){
                            if($returnedQty[$i] > 0){
                                $data = array(
                                    'fsReturnQty' => ($oldSR+$data['billsdetails'][0]['fsReturnQty']),
                                    'fsReturnAmt' => ($ReturnAmount+$data['billsdetails'][0]['fsReturnAmt'])
                                ); 
                                
                                $this->AllocationByManagerModel->update('billsdetails',$data,$id[$i]);

                                $srData = array(
                                    'billId' =>  $billId,
                                    'billItemId'=>$id[$i],
                                    'quantity'=>$oldSR,
                                    'createdAt'=>date('Y-m-d H:i:sa')
                                );
                                $this->AllocationByManagerModel->insert('allocation_sr_details',$srData);
                            }
                            // }
                        }
                    }

                    $data['bills']=$this->AllocationByManagerModel->loadBillsDetails('bills', $billId);
                    $OlderSrAmt=$data['bills'][0]['SRAmt'];
                    $OlderPendingAmt=$data['bills'][0]['pendingAmt'];

                    $latestSrAmt=0;
                    $latestPendingAmt=0;

                   
                    $fixTotal=$fsSrBillAmt;
                    $fsSrBillAmt=round($fsSrBillAmt);
                    $latestSrAmt=$OlderSrAmt+$fsSrBillAmt;
                    $latestPendingAmt=$OlderPendingAmt-$fsSrBillAmt;

                    if($fsSrBillAmt>0){
                        $data = array('SRAmt' => $latestSrAmt,'pendingAmt'=>$latestPendingAmt,'creditNoteRenewal'=>$creditNoteAmt);  
                        $this->AllocationByManagerModel->update('bills',$data, $billId);
                    }
                }else{
                    for ($i=0; $i < count($returnedQty); $i++) {
                        if($returnedQty[$i] <= $qty[$i]){
                            $calAmt=$netAmount[$i]/$qty[$i];
                            $ReturnAmount=$returnAmt[$i]+($calAmt * $returnedQty[$i]);
                            $data['billsdetails']=$this->AllocationByManagerModel->loadBillDetailsID('billsdetails', $id[$i]);
                            
                            $data['bills']=$this->AllocationByManagerModel->load('bills', $billId);
                            $fsSrBillAmt=$data['bills'][0]['fsSrAmt'];
                            $oldSR=0+$returnedQty[$i];
                            $srTotalFs=$srTotalFs+$ReturnAmount;

                            $fsSrBillAmt=$srTotalFs;
                            
                            $oldSR= $oldSR;
                            $ReturnAmount= $ReturnAmount;
                            // if($qty[$i] >= $oldSR){
                                if($returnedQty[$i] > 0){
                                    $data = array(
                                        'fsReturnQty' => ($oldSR+$data['billsdetails'][0]['fsReturnQty']),
                                        'fsReturnAmt' => ($ReturnAmount+$data['billsdetails'][0]['fsReturnAmt'])
                                    ); 
                                    
                                    $this->AllocationByManagerModel->update('billsdetails',$data,$id[$i]);

                                     $srData = array(
                                        'billId' =>  $billId,
                                        'billItemId'=>$id[$i],
                                        'quantity'=>$oldSR,
                                        'createdAt'=>date('Y-m-d H:i:sa')
                                    );
                                    $this->AllocationByManagerModel->insert('allocation_sr_details',$srData);
                                }
                            // }
                        }
                    }

                    $data['bills']=$this->AllocationByManagerModel->loadBillsDetails('bills', $billId);
                    $OlderSrAmt=$data['bills'][0]['SRAmt'];
                    $OlderPendingAmt=$data['bills'][0]['pendingAmt'];

                    $latestSrAmt=0;
                    $latestPendingAmt=0;
                   
                    $fixTotal=$fsSrBillAmt;
                    $fsSrBillAmt=round($fsSrBillAmt);
                    $latestSrAmt=$OlderSrAmt+$fsSrBillAmt;
                    $latestPendingAmt=$OlderPendingAmt-$fsSrBillAmt;

                    $creditNoteAmt=0;
                    if(round($fsSrBillAmt)>round($onlyPending)){
                        $creditNoteAmt=(round($fsSrBillAmt)-round($onlyPending));
                    }

                    if($fsSrBillAmt>0){
                        $data = array('SRAmt' => $latestSrAmt,'pendingAmt'=>$latestPendingAmt,'creditNoteRenewal'=>$creditNoteAmt);  
                        $this->AllocationByManagerModel->update('bills',$data, $billId);
                    }
                }
            }
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

            $sumQty=$this->AllocationByManagerModel->getSum('billsdetails',$billId);
            $actualQty=$sumQty[0]['qtySum'];

            $srQty=number_format(array_sum($returnedQty),2);

            $status='SR';
           
            if($srQty<=$actualQty){
                for ($i=0; $i < count($returnedQty); $i++) {
                    if(!empty($returnedQty[$i]) || $returnedQty[$i] != 0.00){
                        if($returnedQty[$i] <= $qty[$i]){
                            $calAmtFix=$netAmount[$i]/$qty[$i];
                            $ReturnAmount=$returnAmt[$i]+($calAmtFix * $returnedQty[$i]);
                            $data['billsdetails']=$this->AllocationByManagerModel->loadBillDetailsID('billsdetails', $id[$i]);
                            
                            $data['bills']=$this->AllocationByManagerModel->load('bills', $billId);
                            // $fsSrBillAmtFix=$data['bills'][0]['fsSrAmt'];
                            $oldSR=0+$returnedQty[$i];
                            $srTotalFsFix=$srTotalFs+$ReturnAmount;
                            $fsSrBillAmtFix=$fsSrBillAmtFix+$srTotalFsFix;
                            $pendingAmounFix=$pendingAmounFix+$data['billsdetails'][0]['fsReturnAmt'];
                        }
                    }
                }

                if(round($fsSrBillAmtFix)>$pendingAmounFix){
                     echo "SR Amount is greater than pending amount.";
                    // $this->session->set_flashdata('item', array('message' => 'SR Amount is greater than pending amount.','class' => 'success'));
                    // redirect('AdHocController/billSearch');
                    exit;
                }
                
                for ($i=0; $i < count($returnedQty); $i++) {
                    if($returnedQty[$i] <= $qty[$i]){
                        $calAmt=$netAmount[$i]/$qty[$i];
                        $ReturnAmount=$returnAmt[$i]+($calAmt * $returnedQty[$i]);
                        $data['billsdetails']=$this->AllocationByManagerModel->loadBillDetailsID('billsdetails', $id[$i]);
                        
                        $data['bills']=$this->AllocationByManagerModel->load('bills', $billId);
                        $fsSrBillAmt=$data['bills'][0]['fsSrAmt'];
                        $oldSR=0+$returnedQty[$i];
                        $srTotalFs=$srTotalFs+$ReturnAmount;

                        $fsSrBillAmt=$srTotalFs;
                        
                        $oldSR= $oldSR;
                        $ReturnAmount= $ReturnAmount;
                        // if($qty[$i] >= $oldSR){
                            if($returnedQty[$i] > 0){
                                 $data = array(
                                    'fsReturnQty' => ($oldSR+$data['billsdetails'][0]['fsReturnQty']),
                                    'fsReturnAmt' => ($ReturnAmount+$data['billsdetails'][0]['fsReturnAmt'])
                                ); 
                                
                                $this->AllocationByManagerModel->update('billsdetails',$data,$id[$i]);

                                 $srData = array(
                                    'billId' =>  $billId,
                                    'billItemId'=>$id[$i],
                                    'quantity'=>$oldSR,
                                    'createdAt'=>date('Y-m-d H:i:sa')
                                );
                                $this->AllocationByManagerModel->insert('allocation_sr_details',$srData);
                            }
                        // }
                    }
                }

                $data['bills']=$this->AllocationByManagerModel->loadBillsDetails('bills', $billId);
                $OlderSrAmt=$data['bills'][0]['SRAmt'];
                $OlderPendingAmt=$data['bills'][0]['pendingAmt'];

                $latestSrAmt=0;
                $latestPendingAmt=0;
               
                $fixTotal=$fsSrBillAmt;
                $fsSrBillAmt=round($fsSrBillAmt);
                $latestSrAmt=$OlderSrAmt+$fsSrBillAmt;
                $latestPendingAmt=$OlderPendingAmt-$fsSrBillAmt;

                if($fsSrBillAmt>0){
                    $data = array('SRAmt' => $latestSrAmt,'pendingAmt'=>$latestPendingAmt);  
                    $this->AllocationByManagerModel->update('bills',$data, $billId);
                }
            }
            
        }
    }
}

?>

