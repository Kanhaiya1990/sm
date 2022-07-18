<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ExpensesController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('OfficeAllocationModel');
        $this->load->library('session');
        date_default_timezone_set('Asia/Kolkata');
        ini_set('memory_limit', '-1');
       
        if(isset($this->session->userdata['codeKeyData'])) {
			$this->projectSessionName= $this->session->userdata['codeKeyData']['codeKeyValue'];
		}else{
			$this->load->view('LoginView');
		}

        $designation = ($this->session->userdata[$this->projectSessionName]['designation']);
        $des=explode(',',$designation);
        if (in_array('owner', $des)=== false) { 
            redirect("DashbordController");
        }
    }

    public function expensesDetails(){
        $data['notesdetails']=$this->OfficeAllocationModel->getExpensesDetails('notesdetails');
        $data['expnceDetail']=$this->OfficeAllocationModel->getAllExpensesDetails('expences');
        $data['dayBookDetail']=$this->OfficeAllocationModel->getAllDayBookExpensesDetails('close_daybook_notes');
        $this->load->view('owner/expensesDetailsView',$data);
    }

    public function bankDepositDetails(){
        $data['cat_income']=$this->OfficeAllocationModel->getCategories('categories_income_expenses','income');
        $data['cat_expense']=$this->OfficeAllocationModel->getCategories('categories_income_expenses','expenses');
        
        $data['emp']=$this->OfficeAllocationModel->getdata('employee');
        $data['companyDetails']=$this->OfficeAllocationModel->getdata('company');
        $data['bankDeposit']=$this->OfficeAllocationModel->getBankDepositDetails('expences');
        $this->load->view('owner/bankDepositApprovalViews',$data);
    }

    public function allApprovals(){
        //For Open AdHoc Allocations 
        $data['officeAllocations']=$this->OfficeAllocationModel->getOfficeAllocations('allocations_officeadjustment');

        //For Non-Cash Debit/Credit
        $data['employeeNonCashCredit']=$this->OfficeAllocationModel->getCreditEmpTransaction('emptransactions');
        $data['employeeNonCashDebit']=$this->OfficeAllocationModel->getDebitEmpTransaction('emptransactions');
        $data['getDebitByProcess']=$this->OfficeAllocationModel->getDebitByProcess('billpayments');

        //For Expenses Details for Allocations 
        $data['notesdetails']=$this->OfficeAllocationModel->getExpensesDetails('notesdetails');
        $data['expnceDetail']=$this->OfficeAllocationModel->getAllExpensesDetails('expences');
        $data['dayBookDetail']=$this->OfficeAllocationModel->getAllDayBookExpensesDetails('close_daybook_notes');
        
        // Cash Reconciliation Details
        $data['cat_income']=$this->OfficeAllocationModel->getCategories('categories_income_expenses','income');
        $data['cat_expense']=$this->OfficeAllocationModel->getCategories('categories_income_expenses','expenses');
        
        //employees
        $data['emp']=$this->OfficeAllocationModel->getdata('employee');
        //company
        $data['companyDetails']=$this->OfficeAllocationModel->getdata('company');

        //bank deposits
        $data['mainCashBookbankDeposit']=$this->OfficeAllocationModel->getMainCashbookBankDepositDetails('main_cashbook_expences');

        //bank deposits
        $data['bankDeposit']=$this->OfficeAllocationModel->getBankDepositDetails('expences');

        //Employee Approval Details
        $data['employeeApproval']=$this->OfficeAllocationModel->getEmpApproval('employee');

        //CD Amount Approval
        $data['cdApproval']=$this->OfficeAllocationModel->getCdData('billpayments');

        //Other Adjustment Amount Approval
        $data['otherAdjustmentApproval']=$this->OfficeAllocationModel->getOtherAdjustmentData('billpayments');

        //Office Adjustment Amount Approval
        $data['officeAdjustmentApproval']=$this->OfficeAllocationModel->getOfficeAdjustmentData('billpayments');

        $this->load->view('owner/allClearanceForApprovalView',$data);
    }

    public function acceptBankDeposit(){
        $id=trim($this->input->post('id'));
        $data=array('bankDepositApproval'=>0);
        $this->OfficeAllocationModel->update('expences',$data,$id);
        if($this->db->affected_rows() > 0){
            echo "Bank Deposit accepted";
        }else{
            echo "Error!!!";
        }
    }



    public function acceptMainCashBookBankDeposit(){
        $id=trim($this->input->post('id'));
        $data=array('bankDepositApproval'=>0);
        $this->OfficeAllocationModel->update('main_cashbook_expences',$data,$id);
        if($this->db->affected_rows() > 0){
            echo "Bank Deposit accepted";
        }else{
            echo "Error!!!";
        }
    }

    public function rejectBankDeposit(){
        $updatedBy = ($this->session->userdata[$this->projectSessionName]['id']);
        $updatedAt=date('Y-m-d H:i:sa');

        $id=trim($this->input->post('bankDepId'));
        $bankDepAmount=trim($this->input->post('bankDepAmount'));

        $bankDepositDate=$this->input->post('bankDepositDate');
        $cashierId=$this->input->post('cashierId');

        $finalBankDeposit=trim($this->input->post('finalBankDeposit'));
        $finalShortExcess=trim($this->input->post('finalShortExcess'));
        $inoutStatus=trim($this->input->post('inoutStatus'));
        
        $categoryOutflow=$this->input->post('categoryOutflow');//array
        $narrationOutflow=$this->input->post('narrationOutflow');
        $empNameOutflow=$this->input->post('empNameOutflow');//array
        $empOutflowId=$this->input->post('empOutflowId');//array
        $cashAmtOutflow=$this->input->post('cashAmtOutflow');//array

        $bankDepositDetails=$this->OfficeAllocationModel->load('expences',$id);
        if(!empty($bankDepositDetails)){
            if($bankDepositDetails[0]['isCloseDayBook']==0){

                $closingBalance=$this->OfficeAllocationModel->lastRecordDayBookValue();
                $clBalance=0;
                if(!empty($closingBalance)){
                    $clBalance= $closingBalance['openCloseBalance'];
                    $clBalance=$clBalance+$bankDepAmount;
                }//120

                $editEntry=array('bankDepositApproval'=>2);
                $this->OfficeAllocationModel->update('expences',$editEntry,$id);

                if($this->db->affected_rows() >0){

                    $marketCollection=array('date'=>$updatedAt,'employeeId'=>$bankDepositDetails[0]['employeeId'],'notesId'=>$bankDepositDetails[0]['notesId'],'amount'=>$bankDepositDetails[0]['amount'],'nature'=>'Disallowed Bank Deposit','category'=>'Disallowed Bank Deposit','inoutStatus'=>'Inflow','openCloseBalance'=>$clBalance,'updatedBy'=>$updatedBy);

                    $this->OfficeAllocationModel->insert('expences',$marketCollection); 

                    $bankDepositDetails=$this->OfficeAllocationModel->getLastRecorddata();
                    $openCloseBalance=$bankDepositDetails[0]['openCloseBalance'];
                    $isCloseDayBook=$bankDepositDetails[0]['isCloseDayBook'];
                    $closeDayBookName=$bankDepositDetails[0]['closeDayBookName'];
                    $closeBookDayDate=$bankDepositDetails[0]['closeBookDayDate'];

                    $latestOpenCloseBalance=$openCloseBalance-$finalBankDeposit;

                    $category=array();
                    for($i=0;$i<count($categoryOutflow);$i++){
                        if(!empty($categoryOutflow[$i])){
                           $category[]=$categoryOutflow[$i];
                       }//141
                    }//140
                    $newLine=array(
                        'date'=>$updatedAt,
                        'company'=>$bankDepositDetails[0]['company'],
                        'employeeId'=>$updatedBy,
                        'amount'=>$finalBankDeposit,
                        'nature'=>'Bank Deposit',
                        'category'=>'Bank Deposit',
                        'inoutStatus'=>'Outflow',
                        'narration'=>'',
                        'openCloseBalance'=>$latestOpenCloseBalance,
                        'isCloseDayBook'=>'',
                        'closeDayBookName'=>'',
                        'updatedBy'=>$updatedBy,
                        'bankDepositApproval'=>0
                    );
                    $this->OfficeAllocationModel->insert('expences',$newLine);

                    //update bank deposit amount in close-daybook-table
                    if($closeDayBookName !=""){
                        $closeDayDetails=$this->OfficeAllocationModel->findCloseDaybookDetail('close_daybook_notes',$closeBookDayDate);
                        $updClosaCashBookDetails=array(
                            'totalBankDeposit'=>($closeDayDetails[0]['totalBankDeposit']-$finalShortExcess),
                            'totalExpense'=>($closeDayDetails[0]['totalExpense']+$finalShortExcess)
                        );
                        $this->OfficeAllocationModel->updateCloseDaybookDetail('close_daybook_notes',$updClosaCashBookDetails,$closeBookDayDate);
                    }
                    

                    for($i=0;$i<count($empNameOutflow);$i++){
                    $depositOpenCloseBalance=0;
                    $empOpenCloseBalance=0;

                    $isCloseDayBook=0;
                    $emp_id=$this->OfficeAllocationModel->get_empIdLimit($empNameOutflow[$i]);
                    $empComp=$this->OfficeAllocationModel->getEmpCompany('employee',$emp_id);
                    $cmpName="";
                    if($empComp[0]['companyName']=="" || $empComp[0]['companyName']==NULL){
                        $cmp=$this->OfficeAllocationModel->getdata('company');
                        $cmpName=$cmp[0]['name'];
                    }else{
                        $cmpName=$empComp[0]['companyName'];
                    }

                    $employeeDetails=$this->OfficeAllocationModel->load('employee',$emp_id);
                    $employeeMobile=$employeeDetails[0]['mobile'];
                    $employeeName=$employeeDetails[0]['name'];
                    $transactionDate=date('M d, Y H:i a');
                    
                    $companyDetails=$this->OfficeAllocationModel->getdata('office_details');
                    $officeName=$companyDetails[0]['distributorName'];
                    $distributorCode=$companyDetails[0]['distributorCode'];

                    $insertData=array();
                    $billRemark=array();
                    $empDebit=array();
                    if($inoutStatus=="Inflow"){
                        if($category[$i]=="Employee Credit"){
                            $bankDepositInfo=array();
                            $bankDepositInfo=$this->OfficeAllocationModel->getDetails('expences');
                            $depositOpenCloseBalance=$bankDepositInfo[0]['openCloseBalance'];
                            $closeDayBookName=$bankDepositInfo[0]['closeDayBookName'];
                            $isCloseDayBook=$bankDepositInfo[0]['isCloseDayBook'];
                            $closeBookDayDate=$bankDepositInfo[0]['closeBookDayDate'];
                            $empOpenCloseBalance=$depositOpenCloseBalance-$cashAmtOutflow[$i];


                            $insertData=array('date'=>$updatedAt,'employeeId'=>$emp_id,'amount'=>$cashAmtOutflow[$i],'company'=>$cmpName,'openCloseBalance'=>$empOpenCloseBalance,'nature'=>$category[$i],'category'=>$category[$i],'inoutStatus'=>$inoutStatus,'isCloseDayBook'=>$isCloseDayBook,'closeDayBookName'=>$closeDayBookName,'bankDepositApproval'=>0);
                            $this->OfficeAllocationModel->insert('expences',$insertData);

                            $billRemark=array(
                                'empId'=>$emp_id,'remark'=>$narrationOutflow[$i],'amount'=>$cashAmtOutflow[$i],'updatedAt'=>$updatedAt,'updatedBy'=>$updatedBy
                            );
                            $this->OfficeAllocationModel->insert('bill_remark_history',$billRemark);


                            $empDebit=array(
                                'empId'=>$emp_id,'transactionType'=>'cr','description'=>$narrationOutflow[$i],'amount'=>$cashAmtOutflow[$i],'createdAt'=>$updatedAt,'createdBy'=>$updatedBy
                            );
                            $this->OfficeAllocationModel->insert('emptransactions',$empDebit);

                            $ledger=$this->OfficeAllocationModel->getEmpLedgerByEmp('emptransactions',$emp_id);
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
                                "flow_id"=>"618d05f3fd7c8c278758d435",
                                "sender"=>"SIAInc",
                                "mobiles"=>'91'.$employeeMobile,
                                "name"=>$employeeName,
                                "nature"=>"credited",
                                "amount"=>number_format($cashAmtOutflow[$i]),
                                "distributorname"=>$officeName,
                                "dateandtime"=>date('d M, Y H:i A'),
                                "remarks"=>substr($narrationOutflow[$i], 0, 25),
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

                        }else{
                            $bankDepositInfo=array();
                            $bankDepositInfo=$this->OfficeAllocationModel->getDetails('expences');
                            $depositOpenCloseBalance=$bankDepositInfo[0]['openCloseBalance'];
                            $closeDayBookName=$bankDepositInfo[0]['closeDayBookName'];
                            $isCloseDayBook=$bankDepositInfo[0]['isCloseDayBook'];
                            $closeBookDayDate=$bankDepositInfo[0]['closeBookDayDate'];
                            $empOpenCloseBalance=$depositOpenCloseBalance-$cashAmtOutflow[$i];

                            $insertData=array('date'=>$updatedAt,'employeeId'=>$emp_id,'amount'=>$cashAmtOutflow[$i],'company'=>$cmpName,'openCloseBalance'=>$empOpenCloseBalance,'nature'=>$category[$i],'category'=>$category[$i],'inoutStatus'=>$inoutStatus,'isCloseDayBook'=>$isCloseDayBook,'closeDayBookName'=>$closeDayBookName,'bankDepositApproval'=>0);
                            $this->OfficeAllocationModel->insert('expences',$insertData);
                        }
                    }else if($inoutStatus=="Outflow"){//170
                        if($category[$i]=="Employee Advances"){
                            $bankDepositInfo=array();
                            $bankDepositInfo=$this->OfficeAllocationModel->getDetails('expences');
                            $depositOpenCloseBalance=$bankDepositInfo[0]['openCloseBalance'];
                            $closeDayBookName=$bankDepositInfo[0]['closeDayBookName'];
                            $isCloseDayBook=$bankDepositInfo[0]['isCloseDayBook'];
                            $closeBookDayDate=$bankDepositInfo[0]['closeBookDayDate'];
                            $empOpenCloseBalance=$depositOpenCloseBalance-$cashAmtOutflow[$i];

                            $insertData=array('date'=>$updatedAt,'employeeId'=>$emp_id,'amount'=>$cashAmtOutflow[$i],'company'=>$cmpName,'openCloseBalance'=>$empOpenCloseBalance,'nature'=>$category[$i],'category'=>$category[$i],'inoutStatus'=>$inoutStatus,'isCloseDayBook'=>$isCloseDayBook,'closeDayBookName'=>$closeDayBookName,'bankDepositApproval'=>0);
                            $this->OfficeAllocationModel->insert('expences',$insertData);

                            $billRemark=array(
                                'empId'=>$emp_id,'remark'=>$narrationOutflow[$i],'amount'=>$cashAmtOutflow[$i],'updatedAt'=>$updatedAt,'updatedBy'=>$updatedBy
                            );
                            $this->OfficeAllocationModel->insert('bill_remark_history',$billRemark);

                            $empDebit=array(
                                'empId'=>$emp_id,'transactionType'=>'dr','description'=>$narrationOutflow[$i],'amount'=>$cashAmtOutflow[$i],'createdAt'=>$updatedAt,'createdBy'=>$updatedBy
                            );
                            $this->OfficeAllocationModel->insert('emptransactions',$empDebit);

                            $ledger=$this->OfficeAllocationModel->getEmpLedgerByEmp('emptransactions',$emp_id);
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
                                "flow_id"=>"618d06cbd2afee3aa9720320",
                                "sender"=>"SIAInc",
                                "mobiles"=>'91'.$employeeMobile,
                                "name"=>$employeeName,
                                "amount"=>number_format($cashAmtOutflow[$i]),
                                "distributorname"=>$officeName,
                                "dateandtime"=>date('M d, Y H:i A'),
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

                        }else{//206
                            $bankDepositInfo=array();
                            $bankDepositInfo=$this->OfficeAllocationModel->getDetails('expences');
                            $depositOpenCloseBalance=$bankDepositInfo[0]['openCloseBalance'];
                            $closeDayBookName=$bankDepositInfo[0]['closeDayBookName'];
                            $isCloseDayBook=$bankDepositInfo[0]['isCloseDayBook'];
                            $closeBookDayDate=$bankDepositInfo[0]['closeBookDayDate'];
                            $empOpenCloseBalance=$depositOpenCloseBalance-$cashAmtOutflow[$i];

                            $insertData=array('date'=>$updatedAt,'employeeId'=>$emp_id,'amount'=>$cashAmtOutflow[$i],'company'=>$cmpName,'openCloseBalance'=>$empOpenCloseBalance,'nature'=>$category[$i],'category'=>$category[$i],'inoutStatus'=>$inoutStatus,'isCloseDayBook'=>$isCloseDayBook,'closeDayBookName'=>$closeDayBookName,'bankDepositApproval'=>0);
                            $this->OfficeAllocationModel->insert('expences',$insertData);
                        }//227
                    }//205
                    }//for end 161
                }//125

            }else{//113

                $editEntry=array('bankDepositApproval'=>2);
                $this->OfficeAllocationModel->update('expences',$editEntry,$id);
                if($this->db->affected_rows() >0){
                    $bankDepositDetails=$this->OfficeAllocationModel->load('expences',$id);
                    $openCloseBalance=$bankDepositDetails[0]['openCloseBalance'];
                    $isCloseDayBook=$bankDepositDetails[0]['isCloseDayBook'];
                    $closeDayBookName=$bankDepositDetails[0]['closeDayBookName'];
                    $closeBookDayDate=$bankDepositDetails[0]['closeBookDayDate'];

                    $latestOpenClose=$openCloseBalance+$bankDepAmount;
                    $latestOpenCloseBalance=$openCloseBalance+$finalShortExcess;

                    $latestcloseBookDayDate = date('Y-m-d H:i:sa', strtotime($closeBookDayDate));
                    $latestbankDepositDate = date('Y-m-d H:i:sa', strtotime($bankDepositDate));

                    $marketCollection=array('date'=>$latestbankDepositDate,'employeeId'=>$bankDepositDetails[0]['employeeId'],'notesId'=>$bankDepositDetails[0]['notesId'],'amount'=>$bankDepositDetails[0]['amount'],'nature'=>'Disallowed Bank Deposit','category'=>'Disallowed Bank Deposit','closeBookDayDate'=>$latestcloseBookDayDate,'isCloseDayBook'=>$bankDepositDetails[0]['isCloseDayBook'],'closeDayBookName'=>$bankDepositDetails[0]['closeDayBookName'],'inoutStatus'=>'Inflow','openCloseBalance'=>$latestOpenClose,'updatedBy'=>$updatedBy);

                    $this->OfficeAllocationModel->insert('expences',$marketCollection); 

                    $category=array();
                    for($i=0;$i<count($categoryOutflow);$i++){
                        if(!empty($categoryOutflow[$i])){
                           $category[]=$categoryOutflow[$i];
                        }//266
                    }//265

                    $newLine=array(
                        'date'=>$latestbankDepositDate,
                        'company'=>$bankDepositDetails[0]['company'],
                        'employeeId'=>$updatedBy,
                        'amount'=>$finalBankDeposit,
                        'nature'=>$bankDepositDetails[0]['nature'],
                        'category'=>$bankDepositDetails[0]['category'],
                        'inoutStatus'=>$bankDepositDetails[0]['inoutStatus'],
                        'narration'=>$bankDepositDetails[0]['narration'],
                        'openCloseBalance'=>$latestOpenCloseBalance,
                        'isCloseDayBook'=>$bankDepositDetails[0]['isCloseDayBook'],
                        'closeDayBookName'=>$bankDepositDetails[0]['closeDayBookName'],
                        'closeBookDayDate'=>$latestcloseBookDayDate,
                        'updatedBy'=>$bankDepositDetails[0]['updatedBy'],
                        'bankDepositApproval'=>0
                    );
                    $this->OfficeAllocationModel->insert('expences',$newLine);


                    //update bank deposit amount in close-daybook-table
                    if($closeDayBookName !=""){
                        $closeDayDetails=$this->OfficeAllocationModel->findCloseDaybookDetail('close_daybook_notes',$closeBookDayDate);
                        $updClosaCashBookDetails=array(
                            'totalBankDeposit'=>($closeDayDetails[0]['totalBankDeposit']-$finalShortExcess),
                            'totalExpense'=>($closeDayDetails[0]['totalExpense']+$finalShortExcess)
                        );
                        $this->OfficeAllocationModel->updateCloseDaybookDetail('close_daybook_notes',$updClosaCashBookDetails,$closeBookDayDate);
                    }

                    for($i=0;$i<count($empNameOutflow);$i++){
                    $depositOpenCloseBalance=0;
                    $empOpenCloseBalance=0;

                    $isCloseDayBook=0;
                    $emp_id=$this->OfficeAllocationModel->get_empIdLimit($empNameOutflow[$i]);
                    $empComp=$this->OfficeAllocationModel->getEmpCompany('employee',$emp_id);
                    $cmpName="";
                    if($empComp[0]['companyName']=="" || $empComp[0]['companyName']==NULL){
                        $cmp=$this->OfficeAllocationModel->getdata('company');
                        $cmpName=$cmp[0]['name'];
                    }else{
                        $cmpName=$empComp[0]['companyName'];
                    }

                    $employeeDetails=$this->OfficeAllocationModel->load('employee',$emp_id);
                    $employeeMobile=$employeeDetails[0]['mobile'];
                    $employeeName=$employeeDetails[0]['name'];
                    $transactionDate=date('M d, Y H:i a');
                    
                    $companyDetails=$this->OfficeAllocationModel->getdata('office_details');
                    $officeName=$companyDetails[0]['distributorName'];
                    $distributorCode=$companyDetails[0]['distributorCode'];

                    $insertData=array();
                    $billRemark=array();
                    $empDebit=array();
                    if($inoutStatus=="Inflow"){
                        if($category[$i]=="Employee Credit"){
                            $bankDepositInfo=array();
                            $bankDepositInfo=$this->OfficeAllocationModel->getDetails('expences');
                            $depositOpenCloseBalance=$bankDepositInfo[0]['openCloseBalance'];
                            $closeDayBookName=$bankDepositInfo[0]['closeDayBookName'];
                            $isCloseDayBook=$bankDepositInfo[0]['isCloseDayBook'];
                            $closeBookDayDate=$bankDepositInfo[0]['closeBookDayDate'];
                            $empOpenCloseBalance=$depositOpenCloseBalance-$cashAmtOutflow[$i];

                            $insertData=array('date'=>$latestbankDepositDate,'employeeId'=>$emp_id,'amount'=>$cashAmtOutflow[$i],'company'=>$cmpName,'openCloseBalance'=>$empOpenCloseBalance,'nature'=>$category[$i],'category'=>$category[$i],'inoutStatus'=>$inoutStatus,'isCloseDayBook'=>$isCloseDayBook,'closeDayBookName'=>$closeDayBookName,'closeBookDayDate'=>$latestcloseBookDayDate,'bankDepositApproval'=>0);
                            $this->OfficeAllocationModel->insert('expences',$insertData);

                            $billRemark=array(
                                'empId'=>$emp_id,'remark'=>$narrationOutflow[$i],'amount'=>$cashAmtOutflow[$i],'updatedAt'=>$updatedAt,'updatedBy'=>$updatedBy
                            );
                            $this->OfficeAllocationModel->insert('bill_remark_history',$billRemark);


                            $empDebit=array(
                                'empId'=>$emp_id,'transactionType'=>'cr','description'=>$narrationOutflow[$i],'amount'=>$cashAmtOutflow[$i],'createdAt'=>$updatedAt,'createdBy'=>$updatedBy
                            );
                            $this->OfficeAllocationModel->insert('emptransactions',$empDebit);

                            $ledger=$this->OfficeAllocationModel->getEmpLedgerByEmp('emptransactions',$emp_id);
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
                                "flow_id"=>"618d05f3fd7c8c278758d435",
                                "sender"=>"SIAInc",
                                "mobiles"=>'91'.$employeeMobile,
                                "name"=>$employeeName,
                                "nature"=>"credited",
                                "amount"=>number_format($cashAmtOutflow[$i]),
                                "distributorname"=>$officeName,
                                "dateandtime"=>date('d M, Y H:i A'),
                                "remarks"=>substr($narrationOutflow[$i], 0, 25),
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

                        }else{//298
                            $bankDepositInfo=array();
                            $bankDepositInfo=$this->OfficeAllocationModel->getDetails('expences');
                            $depositOpenCloseBalance=$bankDepositInfo[0]['openCloseBalance'];
                            $closeDayBookName=$bankDepositInfo[0]['closeDayBookName'];
                            $isCloseDayBook=$bankDepositInfo[0]['isCloseDayBook'];
                            $closeBookDayDate=$bankDepositInfo[0]['closeBookDayDate'];
                            $empOpenCloseBalance=$depositOpenCloseBalance-$cashAmtOutflow[$i];

                            $insertData=array('date'=>$latestbankDepositDate,'employeeId'=>$emp_id,'amount'=>$cashAmtOutflow[$i],'company'=>$cmpName,'openCloseBalance'=>$empOpenCloseBalance,'nature'=>$category[$i],'category'=>$category[$i],'inoutStatus'=>$inoutStatus,'isCloseDayBook'=>$isCloseDayBook,'closeDayBookName'=>$closeDayBookName,'closeBookDayDate'=>$latestcloseBookDayDate,'bankDepositApproval'=>0);
                            $this->OfficeAllocationModel->insert('expences',$insertData);
                        }//330
                        }else if($inoutStatus=="Outflow"){//297
                            if($category[$i]=="Employee Advances"){
                                $bankDepositInfo=array();
                                $bankDepositInfo=$this->OfficeAllocationModel->getDetails('expences');
                                $depositOpenCloseBalance=$bankDepositInfo[0]['openCloseBalance'];
                                $closeDayBookName=$bankDepositInfo[0]['closeDayBookName'];
                                $isCloseDayBook=$bankDepositInfo[0]['isCloseDayBook'];
                                $closeBookDayDate=$bankDepositInfo[0]['closeBookDayDate'];
                                $empOpenCloseBalance=$depositOpenCloseBalance-$cashAmtOutflow[$i];

                                $insertData=array('date'=>$latestbankDepositDate,'employeeId'=>$emp_id,'amount'=>$cashAmtOutflow[$i],'company'=>$cmpName,'openCloseBalance'=>$empOpenCloseBalance,'nature'=>$category[$i],'category'=>$category[$i],'inoutStatus'=>$inoutStatus,'isCloseDayBook'=>$isCloseDayBook,'closeDayBookName'=>$closeDayBookName,'closeBookDayDate'=>$latestcloseBookDayDate,'bankDepositApproval'=>0);
                                $this->OfficeAllocationModel->insert('expences',$insertData);

                                $billRemark=array(
                                    'empId'=>$emp_id,'remark'=>$narrationOutflow[$i],'amount'=>$cashAmtOutflow[$i],'updatedAt'=>$updatedAt,'updatedBy'=>$updatedBy
                                );
                                $this->OfficeAllocationModel->insert('bill_remark_history',$billRemark);

                                $empDebit=array(
                                    'empId'=>$emp_id,'transactionType'=>'dr','description'=>$narrationOutflow[$i],'amount'=>$cashAmtOutflow[$i],'createdAt'=>$updatedAt,'createdBy'=>$updatedBy
                                );
                                $this->OfficeAllocationModel->insert('emptransactions',$empDebit);

                                $ledger=$this->OfficeAllocationModel->getEmpLedgerByEmp('emptransactions',$emp_id);
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
                                    "flow_id"=>"618d05f3fd7c8c278758d435",
                                    "sender"=>"SIAInc",
                                    "mobiles"=>'91'.$employeeMobile,
                                    "name"=>$employeeName,
                                    "nature"=>"credited",
                                    "amount"=>number_format($cashAmtOutflow[$i]),
                                    "distributorname"=>$officeName,
                                    "dateandtime"=>date('d M, Y H:i A'),
                                    "remarks"=>substr($narrationOutflow[$i], 0, 25),
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
                            }else{//332
                                $bankDepositInfo=array();
                                $bankDepositInfo=$this->OfficeAllocationModel->getDetails('expences');
                                $depositOpenCloseBalance=$bankDepositInfo[0]['openCloseBalance'];
                                $closeDayBookName=$bankDepositInfo[0]['closeDayBookName'];
                                $isCloseDayBook=$bankDepositInfo[0]['isCloseDayBook'];
                                $closeBookDayDate=$bankDepositInfo[0]['closeBookDayDate'];
                                $empOpenCloseBalance=$depositOpenCloseBalance-$cashAmtOutflow[$i];

                                $insertData=array('date'=>$latestbankDepositDate,'company'=>$cmpName,'employeeId'=>$emp_id,'amount'=>$cashAmtOutflow[$i],'openCloseBalance'=>$empOpenCloseBalance,'nature'=>$category[$i],'category'=>$category[$i],'inoutStatus'=>$inoutStatus,'isCloseDayBook'=>$isCloseDayBook,'closeDayBookName'=>$closeDayBookName,'closeBookDayDate'=>$latestcloseBookDayDate,'bankDepositApproval'=>0);
                                $this->OfficeAllocationModel->insert('expences',$insertData);
                            }//354
                        }//332
                    }//287
                }//285
            }//243
        }
        redirect('owner/ExpensesController/allApprovals');
    }

    public function acceptExpenseByOwner(){
        $notesId=$this->input->post('id');
        $allocationId=$this->input->post('allocatedId');

        $data=array('expenseOwnerApproval'=>0);

        $this->OfficeAllocationModel->update('notesdetails',$data,$notesId);
        if($this->db->affected_rows() > 0){
            echo "Expenses Accepted";
        }else{
            echo "Error while saving data.";
        }
    }

    public function acceptOwExpenseByOwner(){
        $notesId=$this->input->post('id');

        $data=array('expenseOwnerApproval'=>0);

        $this->OfficeAllocationModel->update('expences',$data,$notesId);
        if($this->db->affected_rows() > 0){
            echo "Expenses Accepted";
        }else{
            echo "Error while saving data.";
        }
    }

    //all office expense accept by checkbox
    public function acceptAllOfficeExpenseByOwner(){
        $selValue=$this->input->post('selValue');
        if(!empty($selValue)){
            foreach($selValue as $sel){
                $data=array('expenseOwnerApproval'=>0);
                $this->OfficeAllocationModel->update('expences',$data,$sel);
            }
        }
    }

    //all Allocation expense accept by checkbox
    public function acceptAllAllocationExpenseByOwner(){
        $selValue=$this->input->post('selValue');
        if(!empty($selValue)){
            foreach($selValue as $sel){
                $data=array('expenseOwnerApproval'=>0);
                $this->OfficeAllocationModel->update('notesdetails',$data,$sel);
            }
        }
    }

    public function acceptDayBookExpenseByOwner(){
        $notesId=$this->input->post('id');

        $data=array('expenseApproval'=>1);

        $this->OfficeAllocationModel->update('close_daybook_notes',$data,$notesId);
        if($this->db->affected_rows() > 0){
            echo "Expenses Accepted";
        }else{
            echo "Error while saving data.";
        }
    }

    public function rejectExpenseByOwner(){
        $notesId=$this->input->post('id');
        $allocationId=$this->input->post('allocatedId');
        $empId=$this->input->post('empId');

        $parking=$this->input->post('parking');
        $challan=$this->input->post('challan');
        $cng=$this->input->post('cng');
        $total=$parking+$challan+$cng;

        $updatedAt=date('Y-m-d H:i:sa');
        $updatedBy=$this->session->userdata[$this->projectSessionName]['id'];

        $lastBal=$this->OfficeAllocationModel->lastRecordValue();//get closing balance
        $openCloseBal=$lastBal['openCloseBalance'];
        if($openCloseBal=='' || $openCloseBal==Null){
            $openCloseBal=0.0;
        }

        $totalClosingBalance=$openCloseBal+$total;//final closing balance

        $marketCollection=array('date'=>$updatedAt,'employeeId'=>$empId,'notesId'=>$notesId,'amount'=>$total,'nature'=>'Disallowed Expense Credit','category'=>'Disallowed Expense Credit','inoutStatus'=>'Inflow','openCloseBalance'=>$totalClosingBalance,'updatedBy'=>$updatedBy);
        
        $data=array('expenseOwnerApproval'=>2);
        $this->OfficeAllocationModel->update('notesdetails',$data,$notesId);
        if($this->db->affected_rows() > 0){
            $this->OfficeAllocationModel->insert('expences',$marketCollection);//insert expenses
            echo "Expenses Rejected";
        }else{
            echo "Error while saving data.";
        }
    }

    public function rejectOwExpenseByOwner(){
        $id=$this->input->post('id');
        $empId=$this->input->post('empId');
        $amount=$this->input->post('amount');
        $nature=$this->input->post('nature');
        $noteId=$this->input->post('noteId');

        $insertedNoteId=0;

        $updatedAt=date('Y-m-d H:i:sa');
        $updatedBy=$this->session->userdata[$this->projectSessionName]['id'];

        $lastBal=$this->OfficeAllocationModel->lastRecordValue();//get closing balance
        $openCloseBal=$lastBal['openCloseBalance'];
        if($openCloseBal=='' || $openCloseBal==Null){
            $openCloseBal=0.0;
        }

        $totalClosingBalance=$openCloseBal+$amount;//final closing balance

        $expDetail=$this->OfficeAllocationModel->load('expences',$id);
        $nature=$expDetail[0]['nature'];
        $company=$expDetail[0]['company'];

        $data=array('expenseOwnerApproval'=>2);
        $this->OfficeAllocationModel->update('expences',$data,$id);
        if($this->db->affected_rows() > 0){
            $loadNoteDetails=$this->OfficeAllocationModel->load('notesdetails',$noteId);
            if(!empty($loadNoteDetails)){
                 $noteData=array(
                    'empId'=>$loadNoteDetails[0]['empId'],
                    'transactionType'=>'income',
                    'note2000'=>$loadNoteDetails[0]['note2000'],
                    'note1000'=>$loadNoteDetails[0]['note1000'],
                    'note500'=>$loadNoteDetails[0]['note500'],
                    'note200'=>$loadNoteDetails[0]['note200'],
                    'note100'=>$loadNoteDetails[0]['note100'],
                    'note50'=>$loadNoteDetails[0]['note50'],
                    'note20'=>$loadNoteDetails[0]['note20'],
                    'note10'=>$loadNoteDetails[0]['note10'],
                    'coins'=>$loadNoteDetails[0]['coins'],
                    'collectedAmt'=>$loadNoteDetails[0]['collectedAmt'],
                    'updatedAt'=>$updatedAt,
                    'updatedBy'=>$updatedBy
                );

                $this->OfficeAllocationModel->insert('notesdetails',$noteData);//insert notesdetails for rejected income/expense
                $insertedNoteId=$this->db->insert_id();
            }

            if($nature==="Employee Credit"){
                $marketCollection=array('notesId'=>$insertedNoteId,'company'=>$company,'date'=>$updatedAt,'employeeId'=>$empId,'amount'=>$amount,'nature'=>'Disallowed Expense Reversal','category'=>'Disallowed Expense Reversal','inoutStatus'=>'Inflow','openCloseBalance'=>$totalClosingBalance,'updatedBy'=>$updatedBy);

                $this->OfficeAllocationModel->insert('expences',$marketCollection);//insert expenses
            }else if($nature==="Employee Advances"){
                $marketCollection=array('notesId'=>$insertedNoteId,'company'=>$company,'date'=>$updatedAt,'employeeId'=>$empId,'amount'=>$amount,'nature'=>'Disallowed Advance Reversal','category'=>'Disallowed Advance Reversal','inoutStatus'=>'Inflow','openCloseBalance'=>$totalClosingBalance,'updatedBy'=>$updatedBy);

                $this->OfficeAllocationModel->insert('expences',$marketCollection);//insert expenses
            }else{
                $marketCollection=array('notesId'=>$insertedNoteId,'company'=>$company,'date'=>$updatedAt,'employeeId'=>$empId,'amount'=>$amount,'nature'=>'Disallowed Expense Credit','category'=>'Disallowed Expense Credit','inoutStatus'=>'Inflow','openCloseBalance'=>$totalClosingBalance,'updatedBy'=>$updatedBy);

                $this->OfficeAllocationModel->insert('expences',$marketCollection);//insert expenses
            }
            
            if($this->db->affected_rows() > 0){
                if($nature=="Employee Advances"){
                    $desc=$amount." Expense is disallowed by owner";
                    $empInsert=array('empId'=>$empId,'amount'=>$amount,'transactionType'=>"cr",'description'=>$desc,'createdAt'=>$updatedAt,'createdBy'=>$updatedBy);
                    $this->OfficeAllocationModel->insert('emptransactions',$empInsert);//revert emptransactions
                }
                echo "Expenses Rejected";
            }else{
                echo "Error while saving data.";
            }
        }else{
            echo "Error while saving data.";
        }
    }

    public function nonCashDebitCreditApproval(){
        $data['employee']=$this->OfficeAllocationModel->getEmpTransaction('emptransactions');
        $this->load->view('owner/nonCashDebitCreditApprovalView',$data);
    }

    //accept non cash credit 
    public function transactionAccept(){
        $id=$this->input->post('id');
        $data=array('ownerApprovalStatus'=>1);
        $this->OfficeAllocationModel->update('emptransactions',$data,$id);
        if($this->db->affected_rows()>0){
            echo "Record accepted";
        }else{
            echo "Record not accepted";
        }
    }

    //accept non cash debit 
    public function transactionRejected(){
        $id=$this->input->post('id');
        $data=array('ownerApprovalStatus'=>2);
        $this->OfficeAllocationModel->update('emptransactions',$data,$id);
        if($this->db->affected_rows()>0){
            echo "Record rejected";
        }else{
            echo "Record not rejected";
        }
    }

    //accept non cash credit using checkbox
    public function allTransactionAcceptWithCheckBox(){
        $selValue=$this->input->post('selValue');
        if(!empty($selValue)){
            foreach($selValue as $sel){
                $data=array('ownerApprovalStatus'=>1);
                $this->OfficeAllocationModel->update('emptransactions',$data,$sel);
            }
        }

    }

    //accept process credit using checkbox
    public function allProcessDebitAcceptWithCheckBox(){
        $selValue=$this->input->post('selValue');
        if(!empty($selValue)){
            foreach($selValue as $sel){
                $data=array('ownerApproval'=>1);
                $this->OfficeAllocationModel->update('billpayments',$data,$sel);
            }
        }

    }

    //accept Process button debit
    public function acceptProceeDebitAmount(){
        $billPaymentId=trim($this->input->post('billPaymentId'));
        $data=array('ownerApproval'=>1);
        $this->OfficeAllocationModel->update('billpayments',$data,$billPaymentId);

        $getDebitByProcess=$this->OfficeAllocationModel->getDebitByProcess('billpayments');
        $no=0;
        foreach ($getDebitByProcess as $data) 
        {
         $no++; 
         ?>
         <tr>
            <td>
               <input class="checkForProcessDebitAccept" type="checkbox" name="selProcessDebitAcceptValue" value="<?php echo $data['id']; ?>" id="processdebit-basic_checkbox_<?php echo $data['id']; ?>" />
               <label for="processdebit-basic_checkbox_<?php echo $data['id']; ?>"></label>
           </td>
           <td><?php echo $data['initiatorName']; ?></td>
       <td><?php echo $data['currentbillNo']; ?></td>
       <td><?php echo date("d-M-Y", strtotime($data['currentbillDate'])); ?></td>
       <td><?php echo $data['empName']; ?></td>
           <td><?php echo $data['paidAmount']; ?></td>
           <td><?php echo $data['paymentMode']; ?></td>
           <td><?php echo $data['description']; ?></td>
           <td>
            <a href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['billId']); ?>" class="btn btn-xs  btn-primary" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                                
            <a href="javascript:void();" class="modalLink btn btn-xs btn-primary waves-effect" id="emp_debit_accept_id" data-id="<?php echo $data['id'];?>">
                <i class="material-icons">check</i>
            </a>
            <a href="javascript:void();" class="modalLink btn btn-xs btn-primary waves-effect" id="emp_debit_reject_id"  data-id="<?php echo $data['id'];?>">
                <i class="material-icons">cancel</i>
            </a>
        </td>
    </tr>
    <?php
}
}

    //reject process botton debit
public function rejectProcessDebitAmount(){
    $billPaymentId=trim($this->input->post('billPaymentId'));

    $billPayment=$this->OfficeAllocationModel->load('billpayments',$billPaymentId);
    $billId=$billPayment[0]['billId'];
    $paidAmount=$billPayment[0]['paidAmount'];

    $billInfo=$this->OfficeAllocationModel->load('bills',$billId);
    $pendingAmount=$billInfo[0]['pendingAmt'];
    $debitAmount=$billInfo[0]['debit'];
    $currentPending=($pendingAmount+$paidAmount);
    $currentDebit=($debitAmount-$paidAmount);

        //reject billpayment entry
    $billpaymentData=array('ownerApproval'=>2);
    $this->OfficeAllocationModel->update('billpayments',$billpaymentData,$billPaymentId);
        //add rejected amount to pending
    $billData=array('pendingAmt'=>$currentPending,'debit'=>$currentDebit);
    $this->OfficeAllocationModel->update('bills',$billData,$billId);
        //reject emptransaction entry
    $empTransactionData=array('ownerApprovalStatus'=>2);
    $this->OfficeAllocationModel->updateEmpTransaction('emptransactions',$empTransactionData,$billId,$paidAmount);

    $getDebitByProcess=$this->OfficeAllocationModel->getDebitByProcess('billpayments');
    $no=0;
    foreach ($getDebitByProcess as $data) 
    {
     $no++; 
     ?>
     <tr>
        <td>
           <input class="checkForProcessDebitAccept" type="checkbox" name="selProcessDebitAcceptValue" value="<?php echo $data['id']; ?>" id="processdebit-basic_checkbox_<?php echo $data['id']; ?>" />
           <label for="processdebit-basic_checkbox_<?php echo $data['id']; ?>"></label>
       </td>
       <td><?php echo $data['initiatorName']; ?></td>
       <td><?php echo $data['currentbillNo']; ?></td>
       <td><?php echo date("d-M-Y", strtotime($data['currentbillDate'])); ?></td>
       <td><?php echo $data['empName']; ?></td>
       
       <td><?php echo $data['paidAmount']; ?></td>
       <td><?php echo $data['paymentMode']; ?></td>
       <td><?php echo $data['description']; ?></td>
       <td>
        <a href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['billId']); ?>" class="btn btn-xs  btn-primary" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                                
        <a href="javascript:void();" class="modalLink btn btn-xs btn-primary waves-effect" id="emp_debit_accept_id" data-id="<?php echo $data['id'];?>">
            <i class="material-icons">check</i>
        </a>
        <a href="javascript:void();" class="modalLink btn btn-xs btn-primary waves-effect" id="emp_debit_reject_id"  data-id="<?php echo $data['id'];?>">
            <i class="material-icons">cancel</i>
        </a>
    </td>
</tr>
<?php
}
}

    //accept cash discount
public function acceptCashDiscountAmount(){
    $billPaymentId=trim($this->input->post('billPaymentId'));
    $data=array('ownerApproval'=>1);
    $this->OfficeAllocationModel->update('billpayments',$data,$billPaymentId);

    $cdApproval=$this->OfficeAllocationModel->getCdData('billpayments');
    $no=0;
    foreach ($cdApproval as $data) 
    {
        $no++; 

        $dt=date_create($data['date']);
        $billdate = date_format($dt,'d-M-Y');

        $dt=date_create($data['cdDate']);
        $cdDate = date_format($dt,'d-M-Y H:i:sa');
        ?>
        <tr>
            <td>
                <input class="checkCD" type="checkbox" name="selCdValue" value="<?php echo $data['billPaymentId']; ?>" id="cd-basic_checkbox_<?php echo $data['billPaymentId']; ?>" />
                <label for="cd-basic_checkbox_<?php echo $data['billPaymentId']; ?>"></label>
            </td>
            <td><?php echo $data['initiatorName']; ?></td>
            <td><?php echo $data['billNo']; ?></td>
            <td><?php echo $billdate; ?></td>
            <td><?php echo $data['retailerName']; ?></td>
            <td><?php echo $data['netAmount']; ?></td>
            <td><?php echo $data['pendingAmt']; ?></td>
            <td><?php echo $data['cdAmount']; ?></td>
            <td><?php echo $cdDate; ?></td>
            <td>
              <a href="javascript:void();" onclick="acceptCashDiscount(this,'<?php echo $data["billPaymentId"]; ?>');" class="modalLink btn btn-xs btn-primary waves-effect">
                <i class="material-icons">check</i>
            </a>
            <a href="javascript:void();" onclick="rejectCashDiscount(this,'<?php echo $data["billPaymentId"]; ?>');" class="modalLink btn btn-xs btn-primary waves-effect">
                <i class="material-icons">cancel</i>
            </a>
        </td>
        
    </tr>
    <?php
}
}



    //reject cash discount
public function rejectCashDiscountAmount(){
    $billPaymentId=trim($this->input->post('billPaymentId'));

    $billPayment=$this->OfficeAllocationModel->load('billpayments',$billPaymentId);
    $billId=$billPayment[0]['billId'];
    $paidAmount=$billPayment[0]['paidAmount'];

    $billInfo=$this->OfficeAllocationModel->load('bills',$billId);
    $billCdAmount=$billInfo[0]['cd'];
    $pendingAmount=$billInfo[0]['pendingAmt'];

    $currentCD=($billCdAmount-$paidAmount);
    $currentPending=($pendingAmount+$paidAmount);

    $data=array('ownerApproval'=>2);
    $this->OfficeAllocationModel->update('billpayments',$data,$billPaymentId);

    $data=array('cd'=>$currentCD,'pendingAmt'=>$currentPending);
    $this->OfficeAllocationModel->update('bills',$data,$billId);

    $cdApproval=$this->OfficeAllocationModel->getCdData('billpayments');
    $no=0;
    foreach ($cdApproval as $data) 
    {
        $no++; 

        $dt=date_create($data['date']);
        $billdate = date_format($dt,'d-M-Y');

        $dt=date_create($data['cdDate']);
        $cdDate = date_format($dt,'d-M-Y H:i:sa');
        ?>
        <tr>
            <td>
                <input class="checkCD" type="checkbox" name="selCdValue" value="<?php echo $data['billPaymentId']; ?>" id="cd-basic_checkbox_<?php echo $data['billPaymentId']; ?>" />
                <label for="cd-basic_checkbox_<?php echo $data['billPaymentId']; ?>"></label>
            </td>
            <td><?php echo $data['initiatorName']; ?></td>
            <td><?php echo $data['billNo']; ?></td>
            <td><?php echo $billdate; ?></td>
            <td><?php echo $data['retailerName']; ?></td>
            <td><?php echo $data['netAmount']; ?></td>
            <td><?php echo $data['pendingAmt']; ?></td>
            <td><?php echo $data['cdAmount']; ?></td>
            <td><?php echo $cdDate; ?></td>
            <td>
              <a href="javascript:void();" onclick="acceptCashDiscount(this,'<?php echo $data["billPaymentId"]; ?>');" class="modalLink btn btn-xs btn-primary waves-effect">
                <i class="material-icons">check</i>
            </a>
            <a href="javascript:void();" onclick="rejectCashDiscount(this,'<?php echo $data["billPaymentId"]; ?>');" class="modalLink btn btn-xs btn-primary waves-effect">
                <i class="material-icons">cancel</i>
            </a>
        </td>
        
    </tr>
    <?php
}
}


    //accept office adjustment
public function acceptOfficeAdjustmentAmount(){
    $billPaymentId=trim($this->input->post('billPaymentId'));
    $data=array('ownerApproval'=>1);
    $this->OfficeAllocationModel->update('billpayments',$data,$billPaymentId);

    $officeAdjustmentApproval=$this->OfficeAllocationModel->getOfficeAdjustmentData('billpayments');
    $no=0;
    foreach ($officeAdjustmentApproval as $data) 
    {
     $no++;

     $dt=date_create($data['date']);
     $billdate = date_format($dt,'d-M-Y');

     $dt=date_create($data['officeAdjDate']);
     $otherAdjDate = date_format($dt,'d-M-Y H:i:sa');
     ?>
     <tr>
        <td>
            <input class="checkOfficeAdj" type="checkbox" name="selOfficeAdjValue" value="<?php echo $data['billPaymentId']; ?>" id="officeAdj-basic_checkbox_<?php echo $data['billPaymentId']; ?>" />
            <label for="officeAdj-basic_checkbox_<?php echo $data['billPaymentId']; ?>"></label>
        </td>
        <td><?php echo $data['initiatorName']; ?></td>
        <td><?php echo $data['billNo']; ?></td>
        <td><?php echo $billdate; ?></td>
        <td><?php echo $data['retailerName']; ?></td>
        <td class="text-right"><?php echo number_format($data['netAmount']); ?></td>
        <td class="text-right"><?php echo number_format($data['pendingAmt']); ?></td>
        <td class="text-right"><?php echo number_format($data['officeAdjAmount']); ?></td>
        <td><?php echo $otherAdjDate; ?></td>
        <td>
          <a href="javascript:void();" onclick="acceptOfficeAdjustment(this,'<?php echo $data["billPaymentId"]; ?>');" class="modalLink btn btn-xs btn-primary waves-effect">
            <i class="material-icons">check</i>
        </a>
        <a href="javascript:void();" onclick="rejectOfficeAdjustment(this,'<?php echo $data["billPaymentId"]; ?>');" class="modalLink btn btn-xs btn-primary waves-effect">
            <i class="material-icons">cancel</i>
        </a>
    </td>

</tr>
<?php
}
}

    //reject office adjustment
public function rejectOfficeAdjustmentAmount(){
    $billPaymentId=trim($this->input->post('billPaymentId'));

    $billPayment=$this->OfficeAllocationModel->load('billpayments',$billPaymentId);
    $billId=$billPayment[0]['billId'];
    $paidAmount=$billPayment[0]['paidAmount'];

    $billInfo=$this->OfficeAllocationModel->load('bills',$billId);
    $billOfficeAdjAmount=$billInfo[0]['officeAdjustmentBillAmount'];
    $pendingAmount=$billInfo[0]['pendingAmt'];

    $currentOfficeAdj=($billOfficeAdjAmount-$paidAmount);
    $currentPending=($pendingAmount+$paidAmount);

    $data=array('ownerApproval'=>2);
    $this->OfficeAllocationModel->update('billpayments',$data,$billPaymentId);

    $data=array('officeAdjustmentBillAmount'=>$currentOfficeAdj,'pendingAmt'=>$currentPending);
    $this->OfficeAllocationModel->update('bills',$data,$billId);

    $officeAdjustmentApproval=$this->OfficeAllocationModel->getOfficeAdjustmentData('billpayments');
    $no=0;
    foreach ($officeAdjustmentApproval as $data) 
    {
     $no++;

     $dt=date_create($data['date']);
     $billdate = date_format($dt,'d-M-Y');

     $dt=date_create($data['officeAdjDate']);
     $otherAdjDate = date_format($dt,'d-M-Y H:i:sa');
     ?>
     <tr>
         <td>
            <input class="checkOfficeAdj" type="checkbox" name="selOfficeAdjValue" value="<?php echo $data['billPaymentId']; ?>" id="officeAdj-basic_checkbox_<?php echo $data['billPaymentId']; ?>" />
            <label for="officeAdj-basic_checkbox_<?php echo $data['billPaymentId']; ?>"></label>
        </td>
        <td><?php echo $data['initiatorName']; ?></td>
        <td><?php echo $data['billNo']; ?></td>
        <td><?php echo $billdate; ?></td>
        <td><?php echo $data['retailerName']; ?></td>
        <td class="text-right"><?php echo number_format($data['netAmount']); ?></td>
        <td class="text-right"><?php echo number_format($data['pendingAmt']); ?></td>
        <td class="text-right"><?php echo number_format($data['officeAdjAmount']); ?></td>
        <td><?php echo $otherAdjDate; ?></td>
        <td>
            <a target="_blank" href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs  btn-primary" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                                             
            <a href="javascript:void();" onclick="acceptOfficeAdjustment(this,'<?php echo $data["billPaymentId"]; ?>');" class="modalLink btn btn-xs btn-primary waves-effect">
            <i class="material-icons">check</i>
            </a>
            
            <a href="javascript:void();" onclick="rejectOfficeAdjustment(this,'<?php echo $data["billPaymentId"]; ?>');" class="modalLink btn btn-xs btn-primary waves-effect">
            <i class="material-icons">cancel</i>
            </a>
        </td>

</tr>
<?php
}
}

    //accept other adjustment
public function acceptOtherAdjustmentAmount(){
    $billPaymentId=trim($this->input->post('billPaymentId'));
    $data=array('ownerApproval'=>1);
    $this->OfficeAllocationModel->update('billpayments',$data,$billPaymentId);

    $otherAdjustmentApproval=$this->OfficeAllocationModel->getOtherAdjustmentData('billpayments');
    $no=0;
    foreach ($otherAdjustmentApproval as $data) 
    {
        $no++;

        $dt=date_create($data['date']);
        $billdate = date_format($dt,'d-M-Y');

        $dt=date_create($data['otherAdjDate']);
        $otherAdjDate = date_format($dt,'d-M-Y H:i:sa');
        ?>
        <tr>
         <td>
            <input class="checkOtherAdj" type="checkbox" name="selOtherAdjValue" value="<?php echo $data['billPaymentId']; ?>" id="otherAdj-basic_checkbox_<?php echo $data['billPaymentId']; ?>" />
            <label for="otherAdj-basic_checkbox_<?php echo $data['billPaymentId']; ?>"></label>
        </td>
        <td><?php echo $data['initiatorName']; ?></td>
        <td><?php echo $data['billNo']; ?></td>
        <td><?php echo $billdate; ?></td>
        <td><?php echo $data['retailerName']; ?></td>
        <td class="text-right"><?php echo $data['netAmount']; ?></td>
        <td class="text-right"><?php echo $data['pendingAmt']; ?></td>
        <td class="text-right"><?php echo $data['otherAdjAmount']; ?></td>
        <td><?php echo $otherAdjDate; ?></td>
        <td>
          <a href="javascript:void();" onclick="acceptOtherAdjustment(this,'<?php echo $data["billPaymentId"]; ?>');" class="modalLink btn btn-xs btn-primary waves-effect">
            <i class="material-icons">check</i>
        </a>
        <a href="javascript:void();" onclick="rejectOtherAdjustment(this,'<?php echo $data["billPaymentId"]; ?>');" class="modalLink btn btn-xs btn-primary waves-effect">
            <i class="material-icons">cancel</i>
        </a>
    </td>
</tr>
<?php
}
}

    //reject other adjustment
public function rejectOtherAdjustmentAmount(){
    $billPaymentId=trim($this->input->post('billPaymentId'));

    $billPayment=$this->OfficeAllocationModel->load('billpayments',$billPaymentId);
    $billId=$billPayment[0]['billId'];
    $paidAmount=$billPayment[0]['paidAmount'];

    $billInfo=$this->OfficeAllocationModel->load('bills',$billId);
    $billOtherAdjAmount=$billInfo[0]['otherAdjustment'];
    $pendingAmount=$billInfo[0]['pendingAmt'];

    $currentOtherAdj=($billOtherAdjAmount-$paidAmount);
    $currentPending=($pendingAmount+$paidAmount);

    $data=array('ownerApproval'=>2);
    $this->OfficeAllocationModel->update('billpayments',$data,$billPaymentId);

    $data=array('otherAdjustment'=>$currentOtherAdj,'pendingAmt'=>$currentPending);
    $this->OfficeAllocationModel->update('bills',$data,$billId);

    $otherAdjustmentApproval=$this->OfficeAllocationModel->getOtherAdjustmentData('billpayments');
    $no=0;
    foreach ($otherAdjustmentApproval as $data) 
    {
        $no++;

        $dt=date_create($data['date']);
        $billdate = date_format($dt,'d-M-Y');

        $dt=date_create($data['otherAdjDate']);
        $otherAdjDate = date_format($dt,'d-M-Y H:i:sa');
        ?>
        <tr>
         <td>
            <input class="checkOtherAdj" type="checkbox" name="selOtherAdjValue" value="<?php echo $data['billPaymentId']; ?>" id="otherAdj-basic_checkbox_<?php echo $data['billPaymentId']; ?>" />
            <label for="otherAdj-basic_checkbox_<?php echo $data['billPaymentId']; ?>"></label>
        </td>
        <td><?php echo $data['initiatorName']; ?></td>
        <td><?php echo $data['billNo']; ?></td>
        <td><?php echo $billdate; ?></td>
        <td><?php echo $data['retailerName']; ?></td>
        <td class="text-right"><?php echo number_format($data['netAmount']); ?></td>
        <td class="text-right"><?php echo number_format($data['pendingAmt']); ?></td>
        <td class="text-right"><?php echo number_format($data['otherAdjAmount']); ?></td>
        <td><?php echo $otherAdjDate; ?></td>
        <td>
          <a href="javascript:void();" onclick="acceptOtherAdjustment(this,'<?php echo $data["billPaymentId"]; ?>');" class="modalLink btn btn-xs btn-primary waves-effect">
            <i class="material-icons">check</i>
        </a>
        <a href="javascript:void();" onclick="rejectOtherAdjustment(this,'<?php echo $data["billPaymentId"]; ?>');" class="modalLink btn btn-xs btn-primary waves-effect">
            <i class="material-icons">cancel</i>
        </a>
    </td>
</tr>
<?php
}
}

    //CheckBox Other Adjustment
public function acceptOtherAdjustmentAmountWithCheckbox(){
    $selValue=$this->input->post('selValue');
    if(!empty($selValue)){
        foreach($selValue as $sel){
            $pendingPaymentId=$sel;
            $data=array('ownerApproval'=>1);
            $this->OfficeAllocationModel->update('billpayments',$data,$pendingPaymentId);
        }
    }
}

    //CheckBox Office Adjustment
public function acceptOfficeAdjustmentAmountWithCheckbox(){
    $selValue=$this->input->post('selValue');
    if(!empty($selValue)){
        foreach($selValue as $sel){
            $pendingPaymentId=$sel;
            $data=array('ownerApproval'=>1);
            $this->OfficeAllocationModel->update('billpayments',$data,$pendingPaymentId);
        }
    }
}

    //CheckBox Cash Discount
public function acceptCdAmountWithCheckbox(){
    $selValue=$this->input->post('selValue');
    if(!empty($selValue)){
        foreach($selValue as $sel){
            $pendingPaymentId=$sel;
            $data=array('ownerApproval'=>1);
            $this->OfficeAllocationModel->update('billpayments',$data,$pendingPaymentId);
        }
    }
}


    public function bankDepositView(){
        $id=$this->input->post('id');
        $bankdeposit=$this->OfficeAllocationModel->load('main_cashbook_expences',$id);
        if(!empty($bankdeposit)){
            $noteDetails=$this->OfficeAllocationModel->load('main_cashbook_notes_details',$bankdeposit[0]['notesId']);
            // print_r($noteDetails);exit;
        
    ?>
        
        <form method="post" onsubmit="return checkBankDepostAmount(this);" role="form" action="<?php echo site_url('owner/MainCashbookController/updateBankDepositEntry');?>"> 
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="col-md-12">
                    <input type="hidden" name="bankDepId" value="<?php echo $id; ?>">
                    <p id="err-dataBank" style="color:red"></p>
                                    
                        <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-basic-example" data-page-length='100'>
                            <thead>
                                <tr style="background-color: #dfdddd;">
                                    <th>Denomination</th>
                                    <th><span class="pull-right">Original Bank Deposit</span></th>
                                    <th><span class="pull-right"> New Bank Deposit</span></th>
                                    <th><span class="pull-right">Calculated Value</span></th>
                                    <th><span class="pull-right"> Notes transferred back to Main Cash book </span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-right">2000</td>
                                    <td class="text-right" id="rem2000"><?php 
                                    $val2000=$noteDetails[0]['note2000'];
                                        echo number_format($noteDetails[0]['note2000']); 
                                    ?>
                                    </td>
                                    <td>
                                    <input type="hidden" name="rem2000" value="<?php echo $val2000; ?>">
                                        <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val2000; ?>)"  onblur="this.value = minmax(this.value, 0,<?php echo $val2000; ?>)" onkeyup="calDepositMoney();" type="text" name="add2000" id="add2000outBank" autocomplete="off" class="form-control"></td>
                                    <td class="text-right" id="t2000outBank"></td>
                                    <td class="text-right" id="t2000outBankRem"></td>
                                </tr>
                                <tr>
                                    <td class="text-right">500</td>
                                    <td class="text-right" id="rem500"><?php 
                                    $val500=$noteDetails[0]['note500']; 
                                        echo number_format($noteDetails[0]['note500']); 
                                    ?>
                                    </td>
                                    <td>
                                    <input type="hidden" name="rem500" value="<?php echo $val500; ?>">
                                        <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val500; ?>)"  onblur="this.value = minmax(this.value, 0,<?php echo $val500; ?>)" onkeyup="calDepositMoney();" type="text" name="add500" id="add500outBank" autocomplete="off" class="form-control">

                                    </td>
                                    <td class="text-right" id="t500outBank"></td>
                                    <td class="text-right" id="t500outBankRem"></td>
                                </tr>
                                <tr>
                                    <td class="text-right">200</td>
                                    <td class="text-right" id="rem200"><?php 
                                    $val200=$noteDetails[0]['note200'];
                                    echo number_format($noteDetails[0]['note200']); 
                                    ?>
                                    </td>
                                    <td>
                                    <input type="hidden" name="rem200" value="<?php echo $val200; ?>">
                                        <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val200; ?>)"  onblur="this.value = minmax(this.value, 0,<?php echo $val200; ?>)" onkeyup="calDepositMoney();" type="text" name="add200" id="add200outBank" autocomplete="off" class="form-control">

                                    </td>
                                    <td class="text-right" id="t200outBank"></td>
                                    <td class="text-right" id="t200outBankRem"></td>
                                </tr>
                                <tr>
                                    <td class="text-right">100</td>
                                    <td class="text-right" id="rem100"><?php
                                    $val100=$noteDetails[0]['note100']; 
                                    echo number_format($noteDetails[0]['note100']); 
                                    ?>
                                    </td>
                                    <td>
                                    <input type="hidden" name="rem100" value="<?php echo $val100; ?>">
                                        <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val100; ?>)"  onblur="this.value = minmax(this.value, 0,<?php echo $val100; ?>)" onkeyup="calDepositMoney();;" type="text" name="add100" id="add100outBank" autocomplete="off" class="form-control">

                                    </td>
                                    <td class="text-right" id="t100outBank"></td>
                                    <td class="text-right" id="t100outBankRem"></td>
                                </tr>
                                <tr>
                                    <td class="text-right">50</td>
                                    <td class="text-right" id="rem50"><?php 
                                    $val50=$noteDetails[0]['note50']; 
                                    echo number_format($noteDetails[0]['note50']); 
                                    ?>
                                    </td>
                                    <td>
                                    <input type="hidden" name="rem50" value="<?php echo $val50; ?>">
                                        <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val50; ?>)"  onblur="this.value = minmax(this.value, 0,<?php echo $val50; ?>)" onkeyup="calDepositMoney();" type="text" name="add50" id="add50outBank" autocomplete="off" class="form-control">

                                    </td>
                                    <td class="text-right" id="t50outBank"></td>
                                    <td class="text-right" id="t50outBankRem"></td>
                                </tr>
                                <tr>
                                    <td class="text-right">20</td>
                                    <td class="text-right" id="rem20"><?php 
                                    $val20=$noteDetails[0]['note20']; 
                                    echo number_format($noteDetails[0]['note20']); 
                                    ?>
                                    </td>
                                    <td>
                                    <input type="hidden" name="rem20" value="<?php echo $val20; ?>">
                                        <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val20; ?>)"  onblur="this.value = minmax(this.value, 0,<?php echo $val20; ?>)" onkeyup="calDepositMoney();" type="text" name="add20" id="add20outBank" autocomplete="off" class="form-control">

                                    </td>
                                    <td class="text-right" id="t20outBank"></td>
                                    <td class="text-right" id="t20outBankRem"></td>
                                </tr>
                                <tr>
                                    <td class="text-right">10</td>
                                    <td class="text-right" id="rem10">
                                    <?php 
                                    $val10=$noteDetails[0]['note10']; 
                                    echo number_format($noteDetails[0]['note10']); 
                                    ?>
                                    </td>
                                    <td>
                                    <input type="hidden" name="rem10" value="<?php echo $val10; ?>">
                                        
                                        <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $val10; ?>)"  onblur="this.value = minmax(this.value, 0,<?php echo $val10; ?>)" onkeyup="calDepositMoney();" type="text" name="add10" id="add10outBank" autocomplete="off" class="form-control">

                                    </td>
                                    <td class="text-right" id="t10outBank"></td>
                                    <td class="text-right" id="t10outBankRem"></td>
                                </tr>
                                <tr>
                                    <td class="text-right">coins</td>
                                    <td class="text-right"><?php 
                                    $valCoins=$noteDetails[0]['coins']; 
                                    echo number_format($noteDetails[0]['coins']); 
                                    ?>
                                    </td>
                                    <td>
                                        <input type="hidden" name="remcoins" id="remcoins" value="<?php echo $valCoins; ?>">
                                        <input onkeypress="return isNumberWithoutDash(event)" style="height: 25px;width:100px" oninput="this.value = minmax(this.value, 0,<?php echo $valCoins; ?>)"  onblur="this.value = minmax(this.value, 0,<?php echo $valCoins; ?>)" onkeyup="calDepositMoney();" type="text" name="coin" id="coinoutBank" autocomplete="off" class="form-control">

                                    </td>
                                    <td class="text-right" id="tcoinsoutBank"></td>
                                    <td class="text-right" id="tcoinsoutBankRem"></td>
                                </tr>
                                <tr style="background-color: #dfdddd;">
                                    <td class="text-right"><b>Total</b></td>
                                    <td class="text-right"><b>
                                    <?php 
                                        echo number_format(abs($noteDetails[0]['collectedAmount'])); 
                                    ?></b></td>
                                    <td class="text-xs-right">
                                        <span id="calTotal"></span>
                                        <input type="hidden" value="<?php echo (abs($noteDetails[0]['collectedAmount'])); ?>" name="bankDep_amt" id="cashBookAmtRet_outBank" autocomplete="off" class="form-control">
                                        <input type="hidden" name="collectedDepositTotalReturn" id="collectedDepositTotal_outBank" autocomplete="off" class="form-control">
                                    </td>
                                    <td class="text-right"><span id="tcalTotal_outBank"></span></td>
                                    <td class="text-right"><span id="tcalTotal_outBankRem"></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-md-12">
                        <center>                                               
                            <button id="bankDepositButton" type="submit" class="btn btn-primary m-t-15 waves-effect">
                                <i class="material-icons">save</i> 
                                <span class="icon-name">
                                    Save
                                </span>
                            </button> 

                            <button data-dismiss="modal" type="button" id="bnkClose" class="btn btn-primary m-t-15 waves-effect">
                                <i class="material-icons">cancel</i> 
                                <span class="icon-name">
                                    cancel
                                </span>
                            </button>
                        </center>
                    </div>
                </div>
            </div>
        </form>

    <?php
        }
    }
}
?>