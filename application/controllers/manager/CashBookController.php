<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
class CashBookController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('CashBookModel');
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
         if (in_array('owner', $des) || in_array('accountant', $des) || in_array('cashier', $des) ) { 

         }else{
            redirect("DashbordController");
         }

    }
    public function index()
    {
        $data['employee']=$this->CashBookModel->getdata('employee');
        $data['bills']=$this->CashBookModel->getdata('bills');
        
        $this->load->view('CurrentDayBookView',$data);
    }

    public function getEmployeeCompany(){
        $empId=$this->input->post('empId');
        $empData=$this->CashBookModel->getEmpCompanydata('employee',$empId);
        $companyName="";
        if(!empty($empData))
            $companyName=$empData[0]['company'];
        echo $companyName;
    }
    public function PastDayBook()
    {
        $data['company']=$this->CashBookModel->getdata('company');
        $this->load->view('PastDayBookView',$data);
    }
    public function PeroidDayBook()
    {
        $data['company']=$this->CashBookModel->getdata('company');
        $this->load->view('PeroidDayBookView',$data);
    }
    public function EVauchers()
    {
        $this->load->view('Manager/EvoucherView');
    }

    public function IncomeExpense(){
        $date=date('Y-m-d');
         $data['companyDetails']=$this->CashBookModel->getInfo('company');
        // echo $date;
        $availBalance=$this->CashBookModel->getAvailableBalance('expences',$date);
        $bankDepositBalance=$this->CashBookModel->getBankDepositBalance('expences',$date,'Bank Deposit');

        $ownerExpenseApproval=$this->CashBookModel->getPendingExpenseCount('notesdetails');
        $rowCount=count($ownerExpenseApproval);

        $data['ownerExpenseCount']=$rowCount;

        $ownerExpenseApprovalTo=$this->CashBookModel->getPendingCashExpenseCount('expences');
        $rowCount=count($ownerExpenseApprovalTo);
        
        $data['ownerExpenseCountTo']=$rowCount;

        $lastEntryDayBook=$this->CashBookModel->getlastEntryDayBook('expences');
        $data['lastEntryDayBookCount']=count($lastEntryDayBook);
        // print_r($lastEntryDayBook);exit;

        // echo $data['ownerExpenseCount'].' '.$data['ownerExpenseCountTo'];exit;

        $totalInflow=$this->CashBookModel->calculateTotalIncome($date);
        $totalOutflow=$this->CashBookModel->calculateTotalOutflow($date);
        $totalBankDeposit=$this->CashBookModel->calculateTotalBankDeposit($date);

        $contraEntryInflow=$this->CashBookModel->calculateInflowContraEntryForDayBook($date);
        $contraEntryOutflow=$this->CashBookModel->calculateOutflowContraEntryForDayBook($date);

        $data['totalInflow']=$totalInflow[0]['inflowTotal'];  
        $data['totalOutflow']=$totalOutflow[0]['outflowTotal'];  
        $data['totalBankDeposit']=$totalBankDeposit[0]['bankflowTotal'];  

        $contraEntryOutflow=$contraEntryOutflow[0]['outflowTotal']; 
        $contraEntryInflow=$contraEntryInflow[0]['inflowTotal']; 

        // echo $contraEntryOutflow.' '.$contraEntryInflow;exit;
        
        $data['diffContraEntry']=$contraEntryInflow-$contraEntryOutflow;

        $data['availBalance']=$availBalance;  
        $data['bankDepositBalance']=$bankDepositBalance;   

        $parkBal=0;$challanBal=0;$cngBal=0;

        $expenceDetail=$this->CashBookModel->getParking('notesdetails',$date);
        if(!empty($expenceDetail)){
            $parkBal=$expenceDetail[0]['parking'];
            $challanBal=$expenceDetail[0]['challan'];
            $cngBal=$expenceDetail[0]['cng'];
        }
         $data['parkBal']=$parkBal;        
         $data['challanBal']=$challanBal;        
         $data['cngBal']=$cngBal;   

        $closingBal=$this->CashBookModel->getClosingBalance('expences');
        $bal=0;
        if(!empty($closingBal)){
            $bal=$closingBal[0]['openCloseBalance'];
        }
        $data['closingBal']=$bal;
        $data['inOut']=$this->CashBookModel->getInflowOutflow('allocations');
        $data['inflowEmp']=$this->CashBookModel->getInflowEmp('expences');

        // $data['inflowEmp']=$this->CashBookModel->getInflowEmpByDate('expences',$date);
        $totalMarketExpense=0;
        if(!empty($data['inflowEmp'])){
            foreach($data['inflowEmp'] as $dt){
                $allocationCode=0;
                $allocationParkingExpense=0;
                $allocationChallanExpense=0;
                $allocationCngExpense=0;
                $totalCollection=0;
                if($dt['allocationId']>0){
                     $alData=$this->CashBookModel->load('allocations',$dt['allocationId']);
                     $allocationCode=$alData[0]['allocationCode'];
                     $expense=$this->CashBookModel->calculateExpense($dt['allocationId']);
                     $allocationParkingExpense=$expense[0]['parking'];
                     $allocationChallanExpense=$expense[0]['challan'];
                     $allocationCngExpense=$expense[0]['cng'];
                     $loadAmount=$this->CashBookModel->loadBalAmt('notesdetails',$dt['allocationId']);
                     $totalCollection=$loadAmount[0]['parking']+$loadAmount[0]['challan']+$loadAmount[0]['cng']+$loadAmount[0]['collectedAmt'];
                }

            $totalMarketExpense=$totalMarketExpense+$allocationParkingExpense+$allocationChallanExpense+$allocationCngExpense;
           }
        }

        $data['inflowCategory']=$this->CashBookModel->getInflowCategory('expences');
        
        $openingBalance=$this->CashBookModel->openingRecordValue();
        $closingBalance=$this->CashBookModel->lastRecordValue();
        $bankDep=$this->CashBookModel->sumBankDeposit();
        $income=$this->CashBookModel->sumIncome();
        $exp=$this->CashBookModel->sumExp();

        $data['cat_income']=$this->CashBookModel->getCategories('categories_income_expenses','income');
        $data['cat_expense']=$this->CashBookModel->getCategories('categories_income_expenses','expenses');

        $data['open']=null;
        $data['close']=null;
        if(!empty($openingBalance)){
            $data['open']= $openingBalance['openCloseBalance'];
        }

        if(!empty($closingBalance)){
            $data['close']=$closingBalance['openCloseBalance'];
        }

        //sum of income notes
        $data['income_notes']=$this->CashBookModel->getIncomeNotesDetails('notesdetails');
        if(empty($data['income_notes'])){
            $data['income_notes']=array('note2000'=>0,'note500'=>0,'note200'=>0,'note100'=>0,'note50'=>0,'note20'=>0,'note10'=>0,'coins'=>0,'collectedAmt'=>0);
        }else{
             $data['income_notes']=array('note2000'=>$data['income_notes'][0]['note2000'],'note500'=>$data['income_notes'][0]['note500'],'note200'=>$data['income_notes'][0]['note200'],'note100'=>$data['income_notes'][0]['note100'],'note50'=>$data['income_notes'][0]['note50'],'note20'=>$data['income_notes'][0]['note20'],'note10'=>$data['income_notes'][0]['note10'],'coins'=>$data['income_notes'][0]['coins'],'collectedAmt'=>$data['income_notes'][0]['collectedAmt']);
        }

        //sum of expense notes
        $data['expense_notes']=$this->CashBookModel->getExpenseNotesDetails('notesdetails');
        if(empty($data['expense_notes'])){
            $data['expense_notes']=array('note2000'=>0,'note500'=>0,'note200'=>0,'note100'=>0,'note50'=>0,'note20'=>0,'note10'=>0,'coins'=>0,'collectedAmt'=>0);
        }else{
             $data['expense_notes']=array('note2000'=>$data['expense_notes'][0]['note2000'],'note500'=>$data['expense_notes'][0]['note500'],'note200'=>$data['expense_notes'][0]['note200'],'note100'=>$data['expense_notes'][0]['note100'],'note50'=>$data['expense_notes'][0]['note50'],'note20'=>$data['expense_notes'][0]['note20'],'note10'=>$data['expense_notes'][0]['note10'],'coins'=>$data['expense_notes'][0]['coins'],'collectedAmt'=>$data['expense_notes'][0]['collectedAmt']);
        }
        

         //sum of bank deposit notes
        $data['bank_deposit_notes']=$this->CashBookModel->getBankDepositNotesDetails('notesdetails');
        if(empty($data['bank_deposit_notes'])){
            $data['bank_deposit_notes']=array('note2000'=>0,'note500'=>0,'note200'=>0,'note100'=>0,'note50'=>0,'note20'=>0,'note10'=>0,'coins'=>0,'collectedAmt'=>0);
        }else{
             $data['bank_deposit_notes']=array('note2000'=>$data['bank_deposit_notes'][0]['note2000'],'note500'=>$data['bank_deposit_notes'][0]['note500'],'note200'=>$data['bank_deposit_notes'][0]['note200'],'note100'=>$data['bank_deposit_notes'][0]['note100'],'note50'=>$data['bank_deposit_notes'][0]['note50'],'note20'=>$data['bank_deposit_notes'][0]['note20'],'note10'=>$data['bank_deposit_notes'][0]['note10'],'coins'=>$data['bank_deposit_notes'][0]['coins'],'collectedAmt'=>$data['bank_deposit_notes'][0]['collectedAmt']);
        }

        //sum of earlier close daybook notes
        $data['daybook_notes']=$this->CashBookModel->getFinalBookNotesDetails('notesdetails');
        if(empty($data['daybook_notes'])){
            $data['daybook_notes']=array('note2000'=>0,'note500'=>0,'note200'=>0,'note100'=>0,'note50'=>0,'note20'=>0,'note10'=>0,'coins'=>0,'collectedAmt'=>0);
        }else{
             $data['daybook_notes']=array('note2000'=>$data['daybook_notes'][0]['note2000'],'note500'=>$data['daybook_notes'][0]['note500'],'note200'=>$data['daybook_notes'][0]['note200'],'note100'=>$data['daybook_notes'][0]['note100'],'note50'=>$data['daybook_notes'][0]['note50'],'note20'=>$data['daybook_notes'][0]['note20'],'note10'=>$data['daybook_notes'][0]['note10'],'coins'=>$data['daybook_notes'][0]['coins'],'collectedAmt'=>$data['daybook_notes'][0]['collectedAmt']);
        }

         $data['final_notes_entry']=$this->CashBookModel->getFinalEntryNotesDetails('notesdetails');
        // print_r($data['income_notes']);
        // echo "<br>";
        // print_r($data['expense_notes']);
        // echo "<br>";
        // print_r($data['bank_deposit_notes']);
        // echo "<br>";
        // print_r($data['daybook_notes']);
        // echo "<br>";
        // print_r($data['final_notes_entry']);
        // exit;


        $data['bankDep']=$bankDep['BankDepSum'];
        $data['income']=$income['income'];
        $data['expense']=$exp['expense'];
        $data['totalMarketExpense']=$totalMarketExpense;
        $data['emp']=$this->CashBookModel->getEmployeeNames();
        $this->load->view('Manager/IncomeExpenseView',$data);
    }
    
    public function pastDayIncomeExpense($daybookName,$opening){
        $data['currentOpening']=$opening;
        $daybookName= urldecode($daybookName);
        $data['daybookName']=$daybookName;
        // echo $daybookName;
        
        $closingBalance=$this->CashBookModel->lastRecordValueByDaybook($daybookName);
        // print_r($closingBalance);exit;
        $data['close']=$closingBalance['openCloseBalance'];

        $data['inflowEmp']=$this->CashBookModel->getInflowEmpByDayBook('expences',$daybookName);

        // $data['inflowEmp']=$this->CashBookModel->getInflowEmpByDate('expences',$date);
        $totalMarketExpense=0;
        if(!empty($data['inflowEmp'])){
            foreach($data['inflowEmp'] as $dt){
                $allocationCode=0;
                $allocationParkingExpense=0;
                $allocationChallanExpense=0;
                $allocationCngExpense=0;
                $totalCollection=0;
                if($dt['allocationId']>0){
                     $alData=$this->CashBookModel->load('allocations',$dt['allocationId']);
                     $allocationCode=$alData[0]['allocationCode'];
                     $expense=$this->CashBookModel->calculateExpense($dt['allocationId']);
                     $allocationParkingExpense=$expense[0]['parking'];
                     $allocationChallanExpense=$expense[0]['challan'];
                     $allocationCngExpense=$expense[0]['cng'];
                     $loadAmount=$this->CashBookModel->loadBalAmt('notesdetails',$dt['allocationId']);
                     $totalCollection=$loadAmount[0]['parking']+$loadAmount[0]['challan']+$loadAmount[0]['cng']+$loadAmount[0]['collectedAmt']+$loadAmount[0]['balanceAmt'];
                }

            $totalMarketExpense=$totalMarketExpense+$allocationParkingExpense+$allocationChallanExpense+$allocationCngExpense;
           }
        }

        $totalInflow=$this->CashBookModel->calculateTotalIncomeByDaybook($daybookName);
        $totalOutflow=$this->CashBookModel->calculateTotalOutflowByDaybook($daybookName);
        $totalBankDeposit=$this->CashBookModel->calculateTotalBankDepositByDaybook($daybookName);

        $data['totalInflow']=$totalInflow[0]['inflowTotal'];  
        $data['totalOutflow']=$totalOutflow[0]['outflowTotal'];  
        $data['totalBankDeposit']=$totalBankDeposit[0]['bankflowTotal'];  
        $data['totalMarketExpense']=$totalMarketExpense;
        // $data['availBalance']=$availBalance;  
        // $data['bankDepositBalance']=$bankDepositBalance; 


        $contraEntryInflow=$this->CashBookModel->calculatePastInflowContraEntryForDayBook($daybookName);
        $contraEntryOutflow=$this->CashBookModel->calculatePastOutflowContraEntryForDayBook($daybookName);
        $contraEntryOutflow=$contraEntryOutflow[0]['outflowTotal']; 
        $contraEntryInflow=$contraEntryInflow[0]['inflowTotal']; 
        $data['diffContraEntry']=$contraEntryInflow-$contraEntryOutflow;
        

        $closingBal=$this->CashBookModel->getClosingBalanceByDayBookName('expences',$daybookName);
        $bal=0;
        if(!empty($closingBal)){
            $bal=$closingBal[0]['openCloseBalance'];
        }
        $data['closingBal']=$bal;
        $data['inflowEmp']=$this->CashBookModel->getInflowEmpByDayBook('expences',$daybookName);
        $this->load->view('Manager/pastDaybookDetailsView',$data);
    }

    public function AddIncome(){
        $data['emp']=$this->CashBookModel->getEmployeeNames();
        $this->load->view('Manager/AddIncomeView',$data);
    }

    public function AddExpense(){
        $data['emp']=$this->CashBookModel->getEmployeeNames();
        $this->load->view('Manager/AddExpenseView',$data);
    }

    public function AddBankDeposit(){
        $this->load->view('Manager/AddBankDepositView');
    }

    public function allocationWiseCashBook($id){
        $data['bills']=$this->CashBookModel->getAllocatedBills('bills',$id);

        $data['allocations']=$this->CashBookModel->load('allocations',$id);

        $data["current"]=null;
        $data["bounced"]=null;
        $data["pass"]=null;
        $data["slip"]=null;
        $count=0;
        $total=0;
        foreach ($data['bills'] as $items) {
            if($items['billType']=='allocatedbillCurrent'){
                $data['current']=$this->CashBookModel->getAllocatedBillsByType('bills',$id,$items['billType']);
                
            }else if($items['billType']=='allocatedbillPass'){
                $data['pass']=$this->CashBookModel->getAllocatedBillsByType('bills',$id,$items['billType']);
                
            }else if($items['billType']=='allocatedbillDS'){
                $data['slip']=$this->CashBookModel->getAllocatedBillsByType('bills',$id,$items['billType']);
            }else if($items['billType']=='allocatedbillBounce'){
                $data['bounced']=$this->CashBookModel->getAllocatedBillsByType('bills',$id,$items['billType']);
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
        $data['notes']=$this->CashBookModel->loadByAllocationId('notesdetails',$id);
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
           
            $data['total']=$total;
            $data['expenses']=$expenses;
        }
        $this->load->view('Manager/CashBookView',$data);
    }

    public function changeStatusRecChequeNeft(){
        $id=$this->input->post('id');
        $amt=$this->input->post('amt');
        $updateData=array('statusLostChequeNeft'=>1);
        $this->CashBookModel->update('bills',$updateData,$id);
        if($this->db->affected_rows()>0){
            echo "yes";
        }else{
            echo "No";
        }
    }

    public function changeStatusLostChequeNeft(){
        $id=$this->input->post('id');
        $amt=$this->input->post('amt');
        $updateData=array('statusLostChequeNeft'=>2);
        $this->CashBookModel->update('bills',$updateData,$id);
        if($this->db->affected_rows()>0){
            echo "yes";
        }else{
            echo "No";
        }
    }

    public function changeStatusAllow(){
        $id=$this->input->post('id');
        $allocationId=$this->input->post('allocationId');
         $category=trim($this->input->post('category'));
         $updatedAt=date('Y-m-d H:i:sa');
        if($category=="parking"){
            $updateData=array('statusParking'=>1,'updatedAt'=>$updatedAt);
            $this->CashBookModel->update('notesdetails',$updateData,$id); 
            if($this->db->affected_rows()>0){
                echo "yes";
            }else{
                echo "No";
            }
        }else if($category=="cng"){
            $updateData=array('statusCng'=>1,'updatedAt'=>$updatedAt);
            $this->CashBookModel->update('notesdetails',$updateData,$id);
            if($this->db->affected_rows()>0){
                echo "yes";
            }else{
                echo "No";
            }
        }else if($category=="challan"){
            $updateData=array('statusChallan'=>1,'updatedAt'=>$updatedAt);
            $this->CashBookModel->update('notesdetails',$updateData,$id);
            if($this->db->affected_rows()>0){
                echo "yes";
            }else{
                echo "No";
            } 
        }
    }

    public function changeStatusDisAllow(){
        $id=$this->input->post('id');
        $allocationId=$this->input->post('allocationId');
        $category=trim($this->input->post('category'));
        $updatedAt=date('Y-m-d H:i:sa');

        if($category=="parking"){
            $updateData=array('statusParking'=>2,'updatedAt'=>$updatedAt);
            $this->CashBookModel->update('notesdetails',$updateData,$id); 
            if($this->db->affected_rows()>0){
                echo "yes";
            }else{
                echo "No";
            }
        }else if($category=="cng"){
            $updateData=array('statusCng'=>2,'updatedAt'=>$updatedAt);
            $this->CashBookModel->update('notesdetails',$updateData,$id);
            if($this->db->affected_rows()>0){
                echo "yes";
            }else{
                echo "No";
            }
        }else if($category=="challan"){
            $updateData=array('statusChallan'=>2,'updatedAt'=>$updatedAt);
            $this->CashBookModel->update('notesdetails',$updateData,$id);
            if($this->db->affected_rows()>0){
                echo "yes";
            }else{
                echo "No";
            } 
        }
    }

    public function updateCashValues(){
        $bookId=date('d_m_Y')."_Daily_Cash_Book";
        $id=$this->input->post('id');
        $data['notes']=$this->CashBookModel->load('notesdetails',$id);
        if($data['notes'][0]['collectedAmt']==0.00 || $data['notes'][0]['collectedAmt']==''){
            $allocatedId=$this->input->post('allocationId');
            $phyCash=$this->input->post('phyCash');
            $phForExpence=$phyCash;
            $expenses=$this->input->post('expenses');
            $cashTotal=$this->input->post('cashTotal');
            $cashTotal=$cashTotal-$expenses;
            $balance=$cashTotal-$phyCash;
            $phyCash=$data['notes'][0]['collectedAmt']+$phyCash;
            $updateData=array('collectedAmt'=>$phyCash,'balanceAmt'=>$balance);
            $this->CashBookModel->update('notesdetails',$updateData,$id);
            if($this->db->affected_rows()>0){
                $lastBal=$this->CashBookModel->lastRecordDayBookValue();
                $openCloseBal=$lastBal['openCloseBalance'];
                if($openCloseBal=='' || $openCloseBal==Null){
                    $openCloseBal=0.0;
                }
                $openCloseBal=$openCloseBal+$phForExpence;
                $empId=$this->session->userdata[$this->projectSessionName]['id'];
                $createdAt=date('Y-m-d H:i:sa');
                $inputData=array('notesId'=>$id,'employeeId'=>$empId,'amount'=>$phForExpence,"nature"=>"Market Collection",'inoutStatus'=>'Inflow','date'=>$createdAt,'openCloseBalance'=>$openCloseBal,'dayBookId'=>$bookId);
                $this->CashBookModel->insert('expences',$inputData);
                if($this->db->affected_rows()>0){
                    echo "Yes.";
                }else{
                    echo "No.";
                }
            }else{
                echo "no";
            }
        }else{
            $allocatedId=$this->input->post('allocationId');
            $phyCash=$this->input->post('phyCash');
            $phForExpence=$phyCash;
            $expenses=$this->input->post('expenses');
            $balance=$data['notes'][0]['balanceAmt']-$phyCash;
            $phyCash=$data['notes'][0]['collectedAmt']+$phyCash;
            $updateData=array('collectedAmt'=>$phyCash,'balanceAmt'=>$balance);
            $this->CashBookModel->update('notesdetails',$updateData,$id);
            if($this->db->affected_rows()>0){
                $lastBal=$this->CashBookModel->lastRecordDayBookValue();
                $openCloseBal=$lastBal['openCloseBalance'];
                if($openCloseBal=='' || $openCloseBal==Null){
                    $openCloseBal=0.0;
                }
                $openCloseBal=$openCloseBal+$phForExpence;
                $empId=$this->session->userdata[$this->projectSessionName]['id'];
                $createdAt=date('Y-m-d H:i:sa');
                $inputData=array('notesId'=>$id,'employeeId'=>$empId,'amount'=>$phForExpence,"nature"=>"Market Collection",'inoutStatus'=>'Inflow','date'=>$createdAt,'openCloseBalance'=>$openCloseBal,'dayBookId'=>$bookId);
                $this->CashBookModel->insert('expences',$inputData);
                if($this->db->affected_rows()>0){
                    echo "Yes.";
                }else{
                    echo "No.";
                }
            }else{
                echo "no";
            }
        }
       
    }

    //insert notes details for income/expence/bank deposit/cashbook
    public function insertNotesDetails($type,$collectedAmount,$updatedBy,$updatedAt,$empId,$note2000,$note1000,$note500,$note200,$note100,$note50,$note20,$note10,$coin){

        $insertData=array('transactionType'=>$type,'collectedAmt'=>$collectedAmount,'updatedBy'=>$updatedBy,'updatedAt'=>$updatedAt,'empId'=>$empId,'note2000'=>$note2000,'note1000'=>$note1000,'note500'=>$note500,'note200'=>$note200,'note100'=>$note100,'note50'=>$note50,'note20'=>$note20,'note10'=>$note10,'coins'=>$coin);

        $this->CashBookModel->insert('notesdetails',$insertData);
        return $this->db->insert_id();
    }

    public function insertIncomeInflow(){
        $bookId="";
        $userId = $this->session->userdata[$this->projectSessionName]['id'];

        $note2000i=$this->input->post('add2000');
        $note1000i=0;
        // $note1000i=$this->input->post('add1000');
        $note500i=$this->input->post('add500');
        $note200i=$this->input->post('add200');
        $note100i=$this->input->post('add100');
        $note50i=$this->input->post('add50');
        $note20i=$this->input->post('add20');
        $note10i=$this->input->post('add10');
        $coini=$this->input->post('coin');


        $note2000=$this->input->post('add2000');
        if($note2000==''||$note2000==NULL){
            $note2000=0;
        }else{
            $note2000=2000*(float)$note2000;
        }
        
        $note1000=$this->input->post('add1000');
        if($note1000==''||$note1000==NULL){
            $note1000=0;
        }else{
            $note1000=1000*(float)$note1000;
        }
    
        
        $note500=$this->input->post('add500');
        if($note500==''||$note500==NULL){
            $note500=0;
        }else{
            $note500=500*(float)$note500;
        }

        $note200=$this->input->post('add200');
        if($note200==''||$note200==NULL){
            $note200=0;
        }else{
            $note200=200*(float)$note200;
        }
        
        $note100=$this->input->post('add100');
        if($note100==''||$note100==NULL){
            $note100=0;
        }else{
            $note100=100*(float)$note100;
        }
        
        $note50=$this->input->post('add50');
        if($note50==''||$note50==NULL){
            $note50=0;
        }else{
            $note50=50*(float)$note50;
        }

        $note20=$this->input->post('add20');
        if($note20==''||$note20==NULL){
            $note20=0;
        }else{
            $note20=20*(float)$note20;
        }
        
        $note10=$this->input->post('add10');
        if($note10==''||$note10==NULL){
            $note10=0;
        }else{
            $note10=10*(float)$note10;
        }
        
        $coin=$this->input->post('coin');
        if($coin==''||$coin==NULL){
            $coin=0;
        }else{
            $coin=(float)$coin;
        }
    
        $total=$note2000+$note1000+$note500+$note200+$note100+$note50+$note20+$note10+$coin;
        

        $openCloseBal=0;
        $lastBal=$this->CashBookModel->lastRecordInOutFlow();

        if(!empty($lastBal)){
            $openCloseBal=$lastBal['openCloseBalance'];
        }
      
        $emp=trim($this->input->post('empName'));
        $empId=trim($this->input->post('empId'));
        $company=trim($this->input->post('compName'));
        $category=trim($this->input->post('category'));
        $cashAmt=trim($this->input->post('cashAmt'));
        $narration=trim($this->input->post('narration'));
        $openCloseBal=$openCloseBal+$cashAmt;
        if($emp != "" && $category != ""){
            
            $employeeDetails=$this->CashBookModel->load('employee',$empId);
            $employeeMobile=$employeeDetails[0]['mobile'];
            $employeeName=$employeeDetails[0]['name'];
            $transactionDate=date('M d, Y H:i a');
            
            $companyDetails=$this->CashBookModel->getdata('office_details');
            $officeName=$companyDetails[0]['distributorName'];
            $distributorCode=$companyDetails[0]['distributorCode'];
            
            if($category=="Employee Credit"){
                $createdAt=date('Y-m-d H:i:sa');

                //insert notes details
                $notesId=$this->insertNotesDetails('income',$total,$userId,$createdAt,$empId,$note2000i,$note1000i,$note500i,$note200i,$note100i,$note50i,$note20i,$note10i,$coini);

                $insertData=array('employeeId'=>$empId,'company'=>$company,'category'=>$category,'nature'=>$category,'amount'=>$cashAmt,'inoutStatus'=>'Inflow','narration'=>$narration,'date'=>$createdAt,'openCloseBalance'=>$openCloseBal,'notesId'=>$notesId,'dayBookId'=>$bookId,'updatedBy'=>$userId);
                $this->CashBookModel->insert('expences',$insertData);
                if($this->db->affected_rows()>0){
                    $insData=array('empId'=>$empId,'transactionType'=>'cr','amount'=>$cashAmt,'description'=>$narration,'createdAt'=>$createdAt,'createdBy'=>$userId);
                    $this->CashBookModel->insert('emptransactions',$insData);

                    $ledger=$this->CashBookModel->getEmpLedgerByEmp('emptransactions',$empId);
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
                    // echo $balance;
                    // $balance=$balance+$cashAmt;
                    // echo $balance;exit;
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
                        "amount"=>number_format($cashAmt),
                        "distributorname"=>$officeName,
                        "dateandtime"=>date('d M, Y H:i A'),
                        "remarks"=>substr($narration, 0, 25),
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
                    // echo $response;

                    //send sms to employee
                    // $this->sendEmployeeCreditSMS($employeeName,$employeeMobile,$collectedAmt,$transactionDate);

                    redirect('manager/CashBookController/IncomeExpense');
                }else{
                    redirect('manager/CashBookController/IncomeExpense');
                }
            }else{
                $createdAt=date('Y-m-d H:i:sa');

                //insert notes details
                $notesId=$this->insertNotesDetails('income',$total,$userId,$createdAt,$empId,$note2000i,$note1000i,$note500i,$note200i,$note100i,$note50i,$note20i,$note10i,$coini);

                $insertData=array('employeeId'=>$empId,'company'=>$company,'category'=>$category,'nature'=>$category,'amount'=>$cashAmt,'inoutStatus'=>'Inflow','narration'=>$narration,'date'=>$createdAt,'openCloseBalance'=>$openCloseBal,'notesId'=>$notesId,'dayBookId'=>$bookId,'updatedBy'=>$userId);
                $this->CashBookModel->insert('expences',$insertData);
                if($this->db->affected_rows()>0){
                    $jsonData=array(
                        "flow_id"=>"618d07fb9af9e25a04535c83",
                        "sender"=>"SIAInc",
                        "mobiles"=>'91'.$employeeMobile,
                        "amount"=>number_format($cashAmt),
                        "name"=>$employeeName,
                        "distributorname"=>$officeName,
                        "dateandtime"=>date('d M, Y H:i A'),
                        "remarks"=>substr($narration, 0, 30)
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
                    
                    redirect('manager/CashBookController/IncomeExpense');
                }else{
                    redirect('manager/CashBookController/IncomeExpense');
                }
            }
        }  
    }

    public function insertIncomeOutflow(){
        $userId = $this->session->userdata[$this->projectSessionName]['id'];
        $bookId="";

        $note2000i=$this->input->post('add2000');
        $note1000i=0;
        $note500i=$this->input->post('add500');
        $note200i=$this->input->post('add200');
        $note100i=$this->input->post('add100');
        $note50i=$this->input->post('add50');
        $note20i=$this->input->post('add20');
        $note10i=$this->input->post('add10');
        $coini=$this->input->post('coin');

        $note2000=$this->input->post('add2000');
        if($note2000==''||$note2000==NULL){
            $note2000=0;
        }else{
            $note2000=2000*(float)$note2000;
        }
        
        $note1000=$this->input->post('add1000');
        if($note1000==''||$note1000==NULL){
            $note1000=0;
        }else{
            $note1000=1000*(float)$note1000;
        }
    
        
        $note500=$this->input->post('add500');
        if($note500==''||$note500==NULL){
            $note500=0;
        }else{
            $note500=500*(float)$note500;
        }

        $note200=$this->input->post('add200');
        if($note200==''||$note200==NULL){
            $note200=0;
        }else{
            $note200=200*(float)$note200;
        }
        
        $note100=$this->input->post('add100');
        if($note100==''||$note100==NULL){
            $note100=0;
        }else{
            $note100=100*(float)$note100;
        }
        
        $note50=$this->input->post('add50');
        if($note50==''||$note50==NULL){
            $note50=0;
        }else{
            $note50=50*(float)$note50;
        }

        $note20=$this->input->post('add20');
        if($note20==''||$note20==NULL){
            $note20=0;
        }else{
            $note20=20*(float)$note20;
        }
        
        $note10=$this->input->post('add10');
        if($note10==''||$note10==NULL){
            $note10=0;
        }else{
            $note10=10*(float)$note10;
        }
        
        $coin=$this->input->post('coin');
        if($coin==''||$coin==NULL){
            $coin=0;
        }else{
            $coin=(float)$coin;
        }
    
        $total=$note2000+$note1000+$note500+$note200+$note100+$note50+$note20+$note10+$coin;

        $emp=trim($this->input->post('empNameOutflow'));
        $empId=trim($this->input->post('empOutflowId'));
        $company=trim($this->input->post('compNameOutflow'));
        $category=trim($this->input->post('categoryOutflow'));
        $cashAmt=trim($this->input->post('cashAmtOutflow'));
        $narration=trim($this->input->post('narrationOutflow'));
        $createdAt=date('Y-m-d H:i:sa');

        $expenseLimit=$this->CashBookModel->get_expenseOfficeLimit('expenses_limit');
         
        $totalExpense=$cashAmt;
        $ownerStatus=0;
        
        $designation = ($this->session->userdata[$this->projectSessionName]['designation']);
        $des=explode(',',$designation);

        if (in_array('owner', $des)) { 
            $ownerStatus=0;
        }else{
            if($totalExpense>=$expenseLimit){
                $ownerStatus=1;
            }
        }
        
        $lastBal=$this->CashBookModel->lastRecordInOutFlow();
        if(!empty($lastBal)){
            $openCloseBal=$lastBal['openCloseBalance'];
        }
        $openCloseBal=$openCloseBal-$cashAmt; 

        if($emp != "" && $category != ""){
            if($category=="Employee Advances"){
                $createdAt=date('Y-m-d H:i:sa');

                 //insert notes details
                $notesId=$this->insertNotesDetails('expense',$total,$userId,$createdAt,$empId,$note2000i,$note1000i,$note500i,$note200i,$note100i,$note50i,$note20i,$note10i,$coini);

                $insertData=array('employeeId'=>$empId,'company'=>$company,'category'=>$category,'nature'=>$category,'amount'=>$cashAmt,'inoutStatus'=>'Outflow','narration'=>$narration,'date'=>$createdAt,'openCloseBalance'=>$openCloseBal,'notesId'=>$notesId,'dayBookId'=>$bookId,'updatedBy'=>$userId,'expenseOwnerApproval'=>$ownerStatus);
                $this->CashBookModel->insert('expences',$insertData);
                if($this->db->affected_rows()>0){
                    $insData=array('empId'=>$empId,'transactionType'=>'dr','amount'=>$cashAmt,'description'=>$narration,'createdAt'=>$createdAt,'createdBy'=>$userId);
                    $this->CashBookModel->insert('emptransactions',$insData);

                    $employeeDetails=$this->CashBookModel->load('employee',$empId);
                    $employeeMobile=$employeeDetails[0]['mobile'];
                    $employeeName=$employeeDetails[0]['name'];
                    $transactionDate=date('M d, Y H:i a');
                    
                    $companyDetails=$this->CashBookModel->getdata('office_details');
                    $officeName=$companyDetails[0]['distributorName'];
                    $distributorCode=$companyDetails[0]['distributorCode'];

                    $ledger=$this->CashBookModel->getEmpLedgerByEmp('emptransactions',$empId);
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
                        "amount"=>number_format($cashAmt),
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
                    
                    //send sms to employee
                    // $this->sendEmployeeDebitSMS($employeeName,$employeeMobile,$collectedAmt,$transactionDate);

                    redirect('manager/CashBookController/IncomeExpense');
                }else{
                    redirect('manager/CashBookController/IncomeExpense');
                }
            }else{
                $createdAt=date('Y-m-d H:i:sa');

                //insert notes details
                $notesId=$this->insertNotesDetails('expense',$total,$userId,$createdAt,$empId,$note2000i,$note1000i,$note500i,$note200i,$note100i,$note50i,$note20i,$note10i,$coini);

                $insertData=array('employeeId'=>$empId,'company'=>$company,'category'=>$category,'nature'=>$category,'amount'=>$cashAmt,'inoutStatus'=>'Outflow','narration'=>$narration,'date'=>$createdAt,'openCloseBalance'=>$openCloseBal,'notesId'=>$notesId,'dayBookId'=>$bookId,'updatedBy'=>$userId,'expenseOwnerApproval'=>$ownerStatus);
                $this->CashBookModel->insert('expences',$insertData);
                if($this->db->affected_rows()>0){
                    redirect('manager/CashBookController/IncomeExpense');
                }else{
                    redirect('manager/CashBookController/IncomeExpense');
                }
            }
        }  
    }

    public function getEmployeeCurrentBalance($empId){

    }


    public function insertNotesExchange(){
        $userId = $this->session->userdata[$this->projectSessionName]['id'];
        $empId = $this->session->userdata[$this->projectSessionName]['id'];
        $amount=0;

        $narration="Notes Exchange";
        $company="General";
        $category="Other Income";
        $createdAt=date('Y-m-d H:i:sa');
        $bookId="";

        $note2000i=$this->input->post('add2000');
        $note1000i=0;
        $note500i=$this->input->post('add500');
        $note200i=$this->input->post('add200');
        $note100i=$this->input->post('add100');
        $note50i=$this->input->post('add50');
        $note20i=$this->input->post('add20');
        $note10i=$this->input->post('add10');
        $coini=$this->input->post('coin');

        $note2000=$this->input->post('add2000');
        if($note2000==''||$note2000==NULL){
            $note2000=0;
        }else{
            $note2000=2000*(float)$note2000;
        }
        
        $note1000=0;
    
        
        $note500=$this->input->post('add500');
        if($note500==''||$note500==NULL){
            $note500=0;
        }else{
            $note500=500*(float)$note500;
        }

        $note200=$this->input->post('add200');
        if($note200==''||$note200==NULL){
            $note200=0;
        }else{
            $note200=200*(float)$note200;
        }
        
        $note100=$this->input->post('add100');
        if($note100==''||$note100==NULL){
            $note100=0;
        }else{
            $note100=100*(float)$note100;
        }
        
        $note50=$this->input->post('add50');
        if($note50==''||$note50==NULL){
            $note50=0;
        }else{
            $note50=50*(float)$note50;
        }

        $note20=$this->input->post('add20');
        if($note20==''||$note20==NULL){
            $note20=0;
        }else{
            $note20=20*(float)$note20;
        }
        
        $note10=$this->input->post('add10');
        if($note10==''||$note10==NULL){
            $note10=0;
        }else{
            $note10=10*(float)$note10;
        }
        
        $coin=$this->input->post('coin');
        if($coin==''||$coin==NULL){
            $coin=0;
        }else{
            $coin=(float)$coin;
        }

        $openCloseBal=0;
        $lastBal=$this->CashBookModel->lastRecordInOutFlow();

        if(!empty($lastBal)){
            $openCloseBal=$lastBal['openCloseBalance'];
        }
    
        $total=(int)$note2000+$note1000+$note500+$note200+$note100+$note50+$note20+$note10+$coin;
        // echo $total.' ';exit;

        if((int)$total != 0){
            echo "Please enter correct data";exit;
        }else{
            //insert notes details
            $notesId=$this->insertNotesDetails('income',$total,$userId,$createdAt,$empId,$note2000i,$note1000i,$note500i,$note200i,$note100i,$note50i,$note20i,$note10i,$coini);

            $insertData=array(
                'employeeId'=>$empId,
                'company'=>$company,
                'category'=>$category,
                'nature'=>$category,
                'amount'=>0,
                'inoutStatus'=>'Inflow',
                'narration'=>$narration,
                'date'=>$createdAt,
                'openCloseBalance'=>$openCloseBal,
                'notesId'=>$notesId,
                'dayBookId'=>$bookId,
                'updatedBy'=>$userId,
                'expenseOwnerApproval'=>0
            );
            $this->CashBookModel->insert('expences',$insertData);
        }

         redirect('manager/CashBookController/IncomeExpense');
    }

    public function sendEmployeeCreditSMS($name,$mobile,$amount,$date){
        $mobile="8446107727";
        $matter="Dear ".$name.", you have deposited Rs ".$amount." cash on ".$date."  which has been credited to you. Your current balance is Rs ";
       
        $url="https://api.msg91.com/api/sendhttp.php?authkey=291106AG8eCyzDe5d626f5c&mobiles=".$mobile."&country=91&message=".$matter."&sender=TESTIN&route=4";
        // echo $url;

        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        //curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        $contents = curl_exec($ch);
        curl_close($ch);
    }

    public function sendEmployeeDebitSMS($name,$mobile,$amount,$date){
        $mobile="8446107727";
        $matter="Dear ".$name.", you have been debited Rs ".$amount." against advance on ".$date.". Your current balance is Rs ";
       
        $url="https://api.msg91.com/api/sendhttp.php?authkey=291106AG8eCyzDe5d626f5c&mobiles=".$mobile."&country=91&message=".$matter."&sender=TESTIN&route=4";
        // echo $url; 

        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        //curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        $contents = curl_exec($ch);
        curl_close($ch);
    }

    public function sendEmployeeDebitDayBookSMS($name,$mobile,$amount,$daybook){
        $mobile="8446107727";
        $matter="Dear ".$name.", you have been debited Rs ".$amount." short cash while closing day book ".$daybook.". Your current balance is Rs ";
       
        $url="https://api.msg91.com/api/sendhttp.php?authkey=291106AG8eCyzDe5d626f5c&mobiles=".$mobile."&country=91&message=".$matter."&sender=TESTIN&route=4";
        // echo $url; 
        
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        //curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        $contents = curl_exec($ch);
        curl_close($ch);
    }

    public function sendDayBookClosedSMS($name,$mobile,$date,$daybook,$income,$expense,$bankDeposit,$closingCash,$totalCashDebit,$shortCash){
        $mobile="8446107727";
        $matter=$daybook." closed by ".$name.". Income - Rs. ".$income.", Expense - Rs. ".$expense.", Bank Deposit - Rs. ".$bankDeposit.", Closing Cash - Rs. ".$closingCash.". Total cash Debits - Rs. ".$totalCashDebit.". Short Cash Rs. ".$shortCash." debited to Cashier ".$name;
       
        $url="https://api.msg91.com/api/sendhttp.php?authkey=291106AG8eCyzDe5d626f5c&mobiles=".$mobile."&country=91&message=".$matter."&sender=TESTIN&route=4";
        // echo $url; 
        
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        //curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        $contents = curl_exec($ch);
        curl_close($ch);
    }
    
   public function addBankDeposits(){
        $bookId=date('d_m_Y')."_Daily_Cash_Book";
       
        $empId=$this->session->userdata[$this->projectSessionName]['id'];

        $note2000i=$this->input->post('add2000');
        // $note1000i=$this->input->post('add1000');
        $note1000i=0;
        $note500i=$this->input->post('add500');
        $note200i=$this->input->post('add200');
        $note100i=$this->input->post('add100');
        $note50i=$this->input->post('add50');
        $note20i=$this->input->post('add20');
        $note10i=$this->input->post('add10');
        $coini=$this->input->post('coin');

        $note2000=$this->input->post('add2000');
        if($note2000==''||$note2000==NULL){
            $note2000=0;
        }else{
            $note2000=2000*(float)$note2000;
        }
        
        $note1000=$this->input->post('add1000');
        if($note1000==''||$note1000==NULL){
            $note1000=0;
        }else{
            $note1000=1000*(float)$note1000;
        }
    
        
        $note500=$this->input->post('add500');
        if($note500==''||$note500==NULL){
            $note500=0;
        }else{
            $note500=500*(float)$note500;
        }

        $note200=$this->input->post('add200');
        if($note200==''||$note200==NULL){
            $note200=0;
        }else{
            $note200=200*(float)$note200;
        }
        
        $note100=$this->input->post('add100');
        if($note100==''||$note100==NULL){
            $note100=0;
        }else{
            $note100=100*(float)$note100;
        }
        
        $note50=$this->input->post('add50');
        if($note50==''||$note50==NULL){
            $note50=0;
        }else{
            $note50=50*(float)$note50;
        }

        $note20=$this->input->post('add20');
        if($note20==''||$note20==NULL){
            $note20=0;
        }else{
            $note20=20*(float)$note20;
        }
        
        $note10=$this->input->post('add10');
        if($note10==''||$note10==NULL){
            $note10=0;
        }else{
            $note10=10*(float)$note10;
        }
        
        $coin=$this->input->post('coin');
        if($coin==''||$coin==NULL){
            $coin=0;
        }else{
            $coin=(float)$coin;
        }
    
        $total=$note2000+$note1000+$note500+$note200+$note100+$note50+$note20+$note10+$coin;
        
        $lastBal=$this->CashBookModel->lastRecordDayBookValue();

        // print_r($lastBal);
        $openCloseBal=$lastBal['openCloseBalance'];
        if($openCloseBal=='' || $openCloseBal==Null){
            $openCloseBal=0.0;
        }
        $openCloseBal=$openCloseBal-$total; 

        $createdAt=date('Y-m-d H:i:sa');

        $ownerStatus=0;
        $userId = ($this->session->userdata[$this->projectSessionName]['id']);
        $designation = ($this->session->userdata[$this->projectSessionName]['designation']);
        $des=explode(',',$designation);

        if (in_array('owner', $des)) { 
            $ownerStatus=0;
        }else{
            $ownerStatus=1;
        }

        //insert notes details
        $notesId=$this->insertNotesDetails('bank_deposit',$total,$userId,$createdAt,$empId,$note2000i,$note1000i,$note500i,$note200i,$note100i,$note50i,$note20i,$note10i,$coini);

        
        $insertData=array('employeeId'=>$empId,'amount'=>$total,'nature'=>'Bank Deposit','inoutStatus'=>'Outflow','date'=>$createdAt,'openCloseBalance'=>$openCloseBal,'notesId'=>$notesId,'dayBookId'=>$bookId,' expenseOwnerApproval '=>$ownerStatus,'bankDepositApproval'=>1,'updatedBy'=>$userId);
        $this->CashBookModel->insert('expences',$insertData);
        if($this->db->affected_rows()>0){
            return redirect('manager/CashBookController/IncomeExpense');
        }else{
            return redirect('manager/CashBookController/IncomeExpense');
        }
    }
   
    public function downloadPeriodDayBook(){
        $this->load->view('Manager/periodDayBookView');
    }

    //Download Period Daybook Collection Excel 
    public function downloadPeriodDayBookExcel(){
        $fromDate=$this->input->post('fromDate');
        $toDate=$this->input->post('toDate');
        $details=$this->CashBookModel->getDayBookDetails('expences',$fromDate,$toDate);
      
        if(empty($details)){
            redirect('manager/CashBookController/downloadPeriodDayBook');
        }else{
            $file="Period_DayBook_Report.xlsx";
            $newFileName= $file;

            $spreadsheet = new Spreadsheet();
            $spreadsheet->setActiveSheetIndex(0);

            foreach (range('A','J') as $col) {
                $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            }

            $spreadsheet->getActiveSheet()->SetCellValue('A1', 'S. No.');
            $spreadsheet->getActiveSheet()->SetCellValue('B1', 'Transaction Date');
            $spreadsheet->getActiveSheet()->SetCellValue('C1', 'Allocation No');
            $spreadsheet->getActiveSheet()->SetCellValue('D1', 'Employee');
            $spreadsheet->getActiveSheet()->SetCellValue('E1', 'Transaction Amount');
            $spreadsheet->getActiveSheet()->SetCellValue('F1', 'Daybook Balance');
            $spreadsheet->getActiveSheet()->SetCellValue('G1', 'Nature');
            $spreadsheet->getActiveSheet()->SetCellValue('H1', 'Inflow/Outflow');
            $spreadsheet->getActiveSheet()->SetCellValue('I1', 'Narration');
            $spreadsheet->getActiveSheet()->SetCellValue('J1', 'Close Daybook Name');
            // $spreadsheet->getActiveSheet()->SetCellValue('K1', 'Close Daybook Date');

            $no=0;
            $num=1;
            $rowCount = 2;
            foreach ($details as $element) {
                $transactionDate=date('d-M-Y H:i:sa',strtotime($element['date']));
                $closeDaybookDate="";
                if($element['closeBookDayDate'] !=""){
                    $closeDaybookDate=date('d-M-Y',strtotime($element['closeBookDayDate']));
                }

                $spreadsheet->getActiveSheet()->SetCellValue('A' . $rowCount, $num);
                $spreadsheet->getActiveSheet()->SetCellValue('B' . $rowCount, $transactionDate);
                $spreadsheet->getActiveSheet()->SetCellValue('C' . $rowCount, $element['allocationCode']);
                $spreadsheet->getActiveSheet()->SetCellValue('D' . $rowCount, $element['empName']);
                $spreadsheet->getActiveSheet()->SetCellValue('E' . $rowCount, $element['amount']);
                $spreadsheet->getActiveSheet()->SetCellValue('F' . $rowCount, $element['openCloseBalance']);
                $spreadsheet->getActiveSheet()->SetCellValue('G' . $rowCount, $element['nature']);
                $spreadsheet->getActiveSheet()->SetCellValue('H' . $rowCount, $element['inoutStatus']);
                $spreadsheet->getActiveSheet()->SetCellValue('I' . $rowCount, $element['narration']);
                $spreadsheet->getActiveSheet()->SetCellValue('J' . $rowCount, $element['closeDayBookName']);
                // $spreadsheet->getActiveSheet()->SetCellValue('K' . $rowCount, $closeDaybookDate);

                $rowCount++;
                $no++;
                $num++;
            }
        
            $writer = new Xlsx($spreadsheet);
            $fileName=$file;
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
            $writer->save('php://output');
        }
    }

    public function pastDay(){
        $data['dayBook']=$this->CashBookModel->getdata('close_daybook_notes');
        $this->load->view('Manager/pastDayCashBooksView',$data);
    }
   
   public function dayWisePastDayBook(){
        $closeDayBookName=trim($this->input->post('closeDayBookName'));
        $pastDayDate=$date;
        // $inOut=$this->CashBookModel->getPastDayInflowOutflow('allocations',$date);
        $inflowEmp=$this->CashBookModel->getPastDayInflowEmp('expences',$closeDayBookName);
        // $inflowCategory=$this->CashBookModel->getPastDayInflowCategory('expences',$date);

        ?>

            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <!--<h2>-->
                            <!--  Cash Book(Income/Expense)-->
                            <!--</h2>-->
                        <h2>
                            <br>
                            <p align="center">Past Day Book Date: <?php echo date("d-m-Y", strtotime($pastDayDate));?></p>
                        </h2>
                        </div>
                        <div class="body">
                            
                            <div class="table-responsive">
                              
                                <table style="font-size: 12px" class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Inflow/Outflow</th>
                                            <th>Nature</th>
                                            <th>Employee</th>
                                            <th>Reference</th>
                                            <th>Amount</th>
                                            <th>Balance</th>
                                        </tr>
                                        
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Date</th>
                                            <th>Inflow/Outflow</th>
                                            <th>Nature</th>
                                            <th>Employee</th>
                                            <th>Reference</th>
                                            <th>Amount</th>
                                            <th>Balance</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        
                                        <?php 
                                        $no=0;
                                           

                                         if(!empty($inflowEmp)){
                                            foreach($inflowEmp as $dt){  ?>
                                        
                                            
                                            <tr>
                                        
                                                <td><?php echo date("d-m-Y", strtotime($dt['date']));?></td>
                                                <td><?php echo $dt['inoutStatus'];?></td>
                                    
                                                <td><?php echo $dt['nature'];?></td>
                                    
                                                <td><?php echo $dt['name'];?></td>
                                                <td></td>
                                                <?php if($dt['inoutStatus']=="Outflow" && $dt['nature']==="Bank Deposit"){?>
                                                    <td style="color:blue;"><?php echo number_format($dt['amount'],2);?></td>
                                                 <?php }else if($dt['inoutStatus']=="Outflow"){ ?>   
                                                    <td style="color:red;"><?php echo number_format($dt['amount'],2);?></td>
                                                <?php }else{ ?> 
                                                     <td style="color:green;"><?php echo number_format($dt['amount'],2);?></td>
                                                <?php } ?>   
                                                <td><?php echo number_format($dt['openCloseBalance'],2);?></td>
                                            </tr>
                                        <?php 
                                                }
                                            }
                                        ?>
                                      
                                    </tbody>
                                </table>
                            </div><!-- Credit Table-->
                        <br>
                            
                       
                        </div>
                    </div>
                </div>
                 
            </div>
        <?php
        // $this->load->view('Manager/dayWisePastDayCashBookView',$data);
    }

    public function debitAmoutToEmp(){
        $bookId=date('d_m_Y')."_Daily_Cash_Book";
        $phyCash=0.00;
        $balance=0.00;
        $id=$this->input->post('aId');
        $data['notes']=$this->CashBookModel->load('notesdetails',$id);
        if($data['notes'][0]['collectedAmt']==0.00 || $data['notes'][0]['collectedAmt']==''){
            $allocatedId=$this->input->post('id');
            $phyCash=$this->input->post('phyCash');
            if($phyCash==""){
                $phyCash=0;
            }
            $phForExpence=$phyCash;
            $expenses=floatval($this->input->post('expenses'));
            $cashTotal=floatval($this->input->post('cashTotal'));
            $cashTotal=$cashTotal-$expenses;
            $balance=$cashTotal-$phyCash;
            $phyCash=$data['notes'][0]['collectedAmt']+$phyCash;
            $updateData=array('collectedAmt'=>$phyCash,'balanceAmt'=>$balance);
            $this->CashBookModel->update('notesdetails',$updateData,$id);
            if($this->db->affected_rows()>0){
                $lastBal=$this->CashBookModel->lastRecordDayBookValue();
                $openCloseBal=$lastBal['openCloseBalance'];
                if($openCloseBal=='' || $openCloseBal==Null){
                    $openCloseBal=0.0;
                }
                $openCloseBal=$openCloseBal+$phForExpence;
                $empId=$this->session->userdata[$this->projectSessionName]['id'];
                $createdAt=date('Y-m-d H:i:sa');
                $inputData=array('notesId'=>$id,'employeeId'=>$empId,'amount'=>$phForExpence,"nature"=>"Market Collection",'inoutStatus'=>'Inflow','date'=>$createdAt,'openCloseBalance'=>$openCloseBal,'dayBookId'=>$bookId);
                $this->CashBookModel->insert('expences',$inputData);
                if($this->db->affected_rows()>0){
                    echo "";
                }else{
                    echo "";
                }
            }else{
                echo "";
            }
        }else{
            $allocatedId=$this->input->post('id');
            $phyCash=$this->input->post('phyCash');
             if($phyCash==""){
                $phyCash=0;
            }
            $phForExpence=$phyCash;
            $expenses=$this->input->post('expenses');
            $balance=$data['notes'][0]['balanceAmt']-$phyCash;
            $phyCash=$data['notes'][0]['collectedAmt']+$phyCash;
            $updateData=array('collectedAmt'=>$phyCash,'balanceAmt'=>$balance);
            $this->CashBookModel->update('notesdetails',$updateData,$id);
            if($this->db->affected_rows()>0){
                $lastBal=$this->CashBookModel->lastRecordDayBookValue();
                $openCloseBal=$lastBal['openCloseBalance'];
                if($openCloseBal=='' || $openCloseBal==Null){
                    $openCloseBal=0.0;
                }
                $openCloseBal=$openCloseBal+$phForExpence;
                $empId=$this->session->userdata[$this->projectSessionName]['id'];
                $createdAt=date('Y-m-d H:i:sa');
                $inputData=array('notesId'=>$id,'employeeId'=>$empId,'amount'=>$phForExpence,"nature"=>"Market Collection",'inoutStatus'=>'Inflow','date'=>$createdAt,'openCloseBalance'=>$openCloseBal,'dayBookId'=>$bookId);
                $this->CashBookModel->insert('expences',$inputData);
                if($this->db->affected_rows()>0){
                    echo "";
                }else{
                    echo "";
                }
            }else{
                echo "";
            }
        }
       

        $remAmount=$balance;
        $cashTaken=$phyCash;
        $emp=$this->CashBookModel->getdata('employee');
        if($cashTaken>0.00){
            if($remAmount<=0){
                $id=$this->input->post('id');
                $upData=array('cashChequeStatus'=>'1','isAllocationComplete'=>'1');
                $this->CashBookModel->update('allocations',$upData,$id);
                if($this->db->affected_rows()>0){
                    $allocatedBills=$this->CashBookModel->getAllocatedBills('bills',$id);
                    if(!empty($allocatedBills)){
                        $totalCash=0.0;
                        $totalCheque=0.0;
                        $totalNeft=0.0;
                        $totalSr=0.0;
                        foreach($allocatedBills as $b){
                            $totalCash=$totalCash+$b['fsCashAmt'];
                            $totalCheque=$totalCheque+$b['fsChequeAmt'];
                            $totalNeft=$totalNeft+$b['fsNeftAmt'];
                            $totalSr=$totalSr+$b['fsSrAmt'];

                            $realSR=0.0;
                            $realReceive=0.0;
                            $realSR=($b['SRAmt'])+($b['fsSrAmt']);
                            $realReceive=($b['receivedAmt'])+($b['fsCashAmt']+$b['fsNeftAmt']+$b['fsChequeAmt']);

                            $upBillData=array('billType'=>'','fsBillStatus'=>'','fsCashAmt'=>'0','fsSrAmt'=>'0','fsNeftAmt'=>'0','fsChequeAmt'=>'0','statusLostChequeNeft'=>'0','SRAmt'=>$realSR,'receivedAmt'=>$realReceive);
                            $this->CashBookModel->update('bills',$upBillData,$b['id']);
                        }
                        $totalCheque=$totalCheque+$totalNeft;
                        //total collected total update for allocations.
                        $upAllocationData=array('totalCashAmt'=>$totalCash,'totalChequeNeftAmt'=>$totalCheque,'totalSRAmt'=>$totalSr);
                        $this->CashBookModel->update('allocations',$upAllocationData,$id);
                    }
                }
            }else{
                $id=$this->input->post('id');
                $data['alocated']=$this->CashBookModel->getfieldStaffsById('allocations',$id);
                $staff1=0;
                $staff2=0;
                $staff3=0;
                $staff4=0;
                if($data['alocated'][0]['fieldStaffCode1']>0){
                    $staff1=$data['alocated'][0]['fieldStaffCode1'];
                }
                if($data['alocated'][0]['fieldStaffCode2']>0){
                    $staff2=$data['alocated'][0]['fieldStaffCode2'];
                }
                if($data['alocated'][0]['fieldStaffCode3']>0){
                    $staff3=$data['alocated'][0]['fieldStaffCode3'];
                }
                if($data['alocated'][0]['fieldStaffCode4']>0){
                    $staff4=$data['alocated'][0]['fieldStaffCode4'];
                }
                $staff=$staff1." ".$staff2." ".$staff3." ".$staff4;
                $staff=explode(' ',$staff);
                $remove = array(0);
                $staff = array_diff($staff, $remove);   
                $countEmp=count($staff);
                $divAmt=$remAmount/$countEmp;

                ?>
                 <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Debit Remaining Amount from Employee</h4>
                    </div>
                    <div class="modal-body">
                    <p id="totAmt">Remaining Amount is : <?php echo $remAmount;?></p>
                    <form onsubmit="return calEmpAmt()" action="<?php echo site_url('manager/CashBookController/closeCashChequeBook');?>" method="post">
                        <input type="hidden" id="totalCalAmt" name="totalCalAmt" value="<?php echo $remAmount; ?>">
                        <span>Add Employee :</span> 
                        <select id="empCm" name="addEmp">
                            <?php foreach($emp as $e){ ?>
                                    <option value="<?php echo $e['id']; ?>"><?php echo $e['name']; ?></option>
                            <?php }  ?>    
                            
                        </select>
                       <!--  <input type="button" value="Add" id="addNewRow"> -->
                       <button type="button" onclick="addNewRow();">Add</button><br><br>
                        <table id="myRowTable" class="table table-striped table-hover js-basic-example DataTable display nowrap">
                          <?php 
                                for($i=0;$i<$countEmp;$i++){
                                    $emp=$this->CashBookModel->getOnlyName('employee',$staff[$i]);
                          ?>

                          <input type="hidden" name="allocationId" value="<?php echo $id; ?>">
                         <!--  <input type="hidden" name="empId[]" value="<?php echo $staff[$i]; ?>"> -->
                          <tr>
                            <td><?php echo $emp;?></td>
                            <td><input id='empAmt' type="text" name="empAmt[]" value="<?php echo $divAmt; ?>"></td>
                             <td><input type="hidden" name="empId[]" value="<?php echo $staff[$i]; ?>"></td>
                            <td><button  style="float: right;" onclick="Delete(this);"><i class="fa fa-close"></i></button></td>
                        </tr>
                          <?php  
                            }        
                          ?>
                        </table>
                        <div id="err" style="color:red"></div>
                        <input type="submit" class="btn btn-primary btn-sm waves-effect" value="Debit">
                    </form>
                    </div>
                <?php
            }
        }
    }

    public function closeCashChequeBook(){
        $userId = $this->session->userdata[$this->projectSessionName]['id'];
        $allocationId=$this->input->post('allocationId');
        $emp=$this->input->post('empId');
        $debAmt=$this->input->post('empAmt');
       
        $createdAt=date('Y-m-d H:i:sa');
        for($i=0;$i<count($emp);$i++){
            if($debAmt[$i]>0){
                $insData=array('empId'=>$emp[$i],'allocationId'=>$allocationId,'transactionType'=>'dr','amount'=>$debAmt[$i],'description'=>"Debit",'createdAt'=>$createdAt);
                $this->CashBookModel->insert('emptransactions',$insData);
                if($this->db->affected_rows()>0){

                    $employeeDetails=$this->CashBookModel->load('employee',$emp[$i]);
                    $employeeMobile=$employeeDetails[0]['mobile'];
                    $employeeName=$employeeDetails[0]['name'];
                    $transactionDate=date('M d, Y H:i a');
                    //send sms to employee
                    $this->sendEmployeeDebitDayBookSMS($employeeName,$employeeMobile,$debAmt[$i],$transactionDate);

                    $upData=array('cashChequeStatus'=>'1','isAllocationComplete'=>'1');
                    $this->CashBookModel->update('allocations',$upData,$allocationId);
                    if($this->db->affected_rows()>0){
                        $allocatedBills=$this->CashBookModel->getAllocatedBills('bills',$allocationId);
                        if(!empty($allocatedBills)){
                            foreach($allocatedBills as $b){
                                $totalCash=$totalCash+$b['fsCashAmt'];
                                $totalCheque=$totalCheque+$b['fsChequeAmt'];
                                $totalNeft=$totalNeft+$b['fsNeftAmt'];
                                $totalSr=$totalSr+$b['fsSrAmt'];

                                $realSR=0.0;
                                $realReceive=0.0;
                                $realSR=($b['SRAmt'])+($b['fsSrAmt']);
                                $realReceive=($b['receivedAmt'])+($b['fsCashAmt']+$b['fsNeftAmt']+$b['fsChequeAmt']);
                                $upBillData=array('billType'=>'','fsBillStatus'=>'','fsCashAmt'=>'0','fsSrAmt'=>'0','fsNeftAmt'=>'0','fsChequeAmt'=>'0','statusLostChequeNeft'=>'0','SRAmt'=>$realSR,'receivedAmt'=>$realReceive);
                                $this->CashBookModel->update('bills',$upBillData,$b['id']);
                            }
                            $totalCheque=$totalCheque+$totalNeft;
                            //total collected total update for allocations.
                            $upAllocationData=array('totalCashAmt'=>$totalCash,'totalChequeNeftAmt'=>$totalCheque,'totalSRAmt'=>$totalSr);
                            $this->CashBookModel->update('allocations',$upAllocationData,$allocationId);
                     } 
                    }
                }
            }
        }

        return redirect('AllocationByManagerController/openAllocations');
    }

    public function closeDayBook(){
        $closeDayBookName='Day Book '.date('d-M-y H:i');

        $checkForPendingExpenses=$this->CashBookModel->checkExpenseData('notesdetails');

        if(!empty($checkForPendingExpenses)){
            echo "Expenses are not approved by owner.";
            exit;
        }

        // $getCloseDayBook=$this->CashBookModel->checkDayBook('close_daybook_notes',$closeDayBookName);
        // if(!empty($getCloseDayBook)){
        //     $closeDayBookName=$closeDayBookName.'_'.count($getCloseDayBook);
        // }
        
        $date=date('Y-m-d H:i:sa');
        $userId = $this->session->userdata[$this->projectSessionName]['id'];

        $closingBal=$this->CashBookModel->getClosingBalance('expences');
        $openingAmount=0;
        if(!empty($closingBal)){
            $openingAmount=$closingBal[0]['openCloseBalance'];
        }

        $short_amt=$this->input->post('short_amt');
        $add2000=$this->input->post('add2000');
        $add1000=$this->input->post('add1000');
        $add500=$this->input->post('add500');
        $add200=$this->input->post('add200');
        $add100=$this->input->post('add100');
        $add50=$this->input->post('add50');
        $add20=$this->input->post('add20');
        $add10=$this->input->post('add10');
        $coins=$this->input->post('coins');
        $collectedTotal=$this->input->post('collectedTotal');

        if($add2000=='' || $add2000==NULL){
            $add2000=0;
        }

        if($add1000=='' || $add1000==NULL){
            $add1000=0;
        }
        if($add500=='' || $add500==NULL){
            $add500=0;
        }
        if($add200=='' || $add200==NULL){
            $add200=0;
        }
        if($add100=='' || $add100==NULL){
            $add100=0;
        }
        if($add50=='' || $add50==NULL){
            $add50=0;
        }
        if($add20=='' || $add20==NULL){
            $add20=0;
        }
        if($add10=='' || $add10==NULL){
            $add10=0;
        }
        if($coins=='' || $coins==NULL){
            $coins=0;
        }

        $totalMarketExpense=0;
        $no=0;
        $data['inflowEmp']=$this->CashBookModel->getInflowEmpByDate('expences',$date);
        if(!empty($data['inflowEmp'])){
            foreach($data['inflowEmp'] as $dt){
                $allocationCode=0;
                $allocationParkingExpense=0;
                $allocationChallanExpense=0;
                $allocationCngExpense=0;
                $totalCollection=0;
                if($dt['allocationId']>0){
                     $alData=$this->CashBookModel->load('allocations',$dt['allocationId']);
                     $allocationCode=$alData[0]['allocationCode'];
                     $expense=$this->CashBookModel->calculateExpense($dt['allocationId']);
                     $allocationParkingExpense=$expense[0]['parking'];
                     $allocationChallanExpense=$expense[0]['challan'];
                     $allocationCngExpense=$expense[0]['cng'];
                     $loadAmount=$this->CashBookModel->loadBalAmt('notesdetails',$dt['allocationId']);
                     $totalCollection=$loadAmount[0]['parking']+$loadAmount[0]['challan']+$loadAmount[0]['cng']+$loadAmount[0]['collectedAmt'];
                }

            $totalMarketExpense=$totalMarketExpense+$allocationParkingExpense+$allocationChallanExpense+$allocationCngExpense;
           }
        }
        $availBalance=$this->CashBookModel->getAvailableBalanceByDate('expences',$date);

        $totalInflow=$this->CashBookModel->calculateTotalIncomeByDate($date);

        $totalOutflow=$this->CashBookModel->calculateTotalOutflowByDate($date);
        $totalBankDeposit=$this->CashBookModel->calculateTotalBankDepositByDate($date);

        $totalInflowBalance=0;
        $totalOutflowBalance=0;
        $totalBankDepositBalance=0;
        $openCloseBalance=0;

        if(!empty($totalInflow[0]['inflowTotal'])){
            $totalInflowBalance=$totalInflow[0]['inflowTotal'];  
            $totalInflowBalance=(floatval($totalInflow[0]['inflowTotal']));  
        }

        if(!empty($totalOutflow[0]['outflowTotal'])){
            $totalOutflowBalance=$totalOutflow[0]['outflowTotal'];
            $totalOutflowBalance=(floatval($totalOutflow[0]['outflowTotal']));   
        }

        if(!empty($totalBankDeposit[0]['bankflowTotal'])){
            $totalBankDepositBalance=$totalBankDeposit[0]['bankflowTotal']; 
        }

        if(!empty($availBalance[0]['openCloseBalance'])){
            $openCloseBalance=$availBalance[0]['openCloseBalance'];
        }

        $shortClosingAmt=($totalInflowBalance-($totalOutflowBalance+$totalBankDepositBalance))-$collectedTotal;

        
        
        $debitData=array();
        if($short_amt>0){
            $openCloseBalance=$openCloseBalance-$short_amt;
            $debitData=array('employeeId'=>$userId,'category'=>'Employee debit','nature'=>'Employee debit','amount'=>$short_amt,'inoutStatus'=>'Outflow','narration'=>'Short amount debited to cashier','date'=>date('Y-m-d H:i:sa'),'openCloseBalance'=>$openCloseBalance,'updatedBy'=>$userId);
            $this->CashBookModel->insert('expences',$debitData);
        }

        $createdAt=date('Y-m-d H:i:sa');

        //insert notes details
        $noteId=$this->insertNotesDetails('close_daybook',$collectedTotal,$userId,$createdAt,$userId,$add2000,$add1000,$add500,$add200,$add100,$add50,$add20,$add10,$coins);

        $details=array('closeDayBookDate'=>$date,'empId'=>$userId,'collectedAmount'=>$collectedTotal,'closeDayBookName'=>$closeDayBookName,'note2000'=>$add2000,'note1000'=>$add1000,'note500'=>$add500,'note200'=>$add200,'note100'=>$add100,'note50'=>$add50,'note20'=>$add20,'note10'=>$add10,'coins'=>$coins,'openingBalance'=>$openingAmount,'closingBalance'=>$openCloseBalance,'totalIncome'=>$totalInflowBalance,'totalExpense'=>$totalOutflowBalance,'totalBankDeposit'=>$totalBankDepositBalance,'totalMarketExpense'=>$totalMarketExpense,'createdAt'=>date('Y-m-d H:i:sa'),'createdBy'=>$userId);

        $this->CashBookModel->insert('close_daybook_notes',$details);
        if($this->db->affected_rows()>0){
            $updateExpenseStatus=array('isCloseDayBook'=>1,'closeBookDayDate'=>$date,'closeDayBookName'=>$closeDayBookName);
            $this->CashBookModel->updateExpenseStatusByDate('expences',$updateExpenseStatus);

            
            $remark="amount of ".$short_amt." short while closing daybook ".$closeDayBookName;
            
            $cashier=$this->session->userdata[$this->projectSessionName]['username'];
            if($short_amt > 0){
                $empDebit=array('empId'=>$userId,'transactionType'=>'dr','description'=>$remark,'amount'=>$short_amt,'createdAt'=>date('Y-m-d H:i:sa'),'createdBy'=>$userId);
                $this->CashBookModel->insert('emptransactions',$empDebit);//insert remark data
                
                $employeeDetails=$this->CashBookModel->load('employee',$userId);
                $employeeMobile=$employeeDetails[0]['mobile'];
                $employeeName=$employeeDetails[0]['name'];
                $transactionDate=date('M d, Y H:i a');
                //send sms to employee
                $this->sendEmployeeDebitDayBookSMS($employeeName,$employeeMobile,$short_amt,$transactionDate);


                //for cashier
                $mobile='8446107727';
                $desc="Amount of ".$short_amt." short while closing daybook '".$closeDayBookName."' debited to you";
                // $this->sendSMS($mobile,$desc);

                //for owner
                $mobile='8446107727';
                $desc="Day book '".$closeDayBookName."' is closed. Amount of ".$short_amt." short while closing daybook '".$closeDayBookName."' debited to".$cashier;
                // $this->sendSMS($mobile,$desc);


                

            }else{
                //if not short value
                // $mobile='9081400400';
                $mobile='8446107727';
                $desc="Day book '".$closeDayBookName."' is closed by cashier ".$cashier;
                // $this->sendSMS($mobile,$desc);
            }

            $employeeDetails=$this->CashBookModel->load('employee',$userId);
            $employeeMobile=$employeeDetails[0]['mobile'];
            $employeeName=$employeeDetails[0]['name'];
            $transactionDate=date('M d, Y H:i a');
            //send sms to employee
            // $this->sendDayBookClosedSMS($employeeName,$employeeMobile,$transactionDate,$closeDayBookName,$totalInflowBalance,$totalOutflowBalance,$totalBankDepositBalance,$diffopenCloseBalance,$totalMarketExpense,$short_amt);

            $companyDetails=$this->CashBookModel->getdata('office_details');
            $officeName=$companyDetails[0]['distributorName'];
            $distributorCode=$companyDetails[0]['distributorCode'];
            
            // $office=$this->CashBookModel->load('office_details','1');
            $shorAmountMsg="";
            
            if($short_amt>0){
                $shorAmountMsg="Short Cash Rs ".number_format($short_amt)." debited to Cashier ".$employeeName." ";

                $ledger=$this->CashBookModel->getEmpLedgerByEmp('emptransactions',$userId);
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
                    "flow_id"=>"618d083facb72303634fd794",
                    "sender"=>"SIAInc",
                    "mobiles"=>'91'.$employeeMobile,
                    "name"=>$employeeName,
                    "amount"=>number_format($short_amt),
                    "distributorname"=>$officeName,
                    "daybookname"=>$closeDayBookName,
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
            }
            
             $allOwners=$this->CashBookModel->getOwners('employee');
             if(!empty($allOwners)){
                 foreach($allOwners as $mbl){
                     $jsonData=array(
                        "flow_id"=>"618d081bee9539792722cda3",
                        "sender"=>"SIAInc",
                        "mobiles"=>'91'.$mbl['mobile'],
                        "distributorname"=>$officeName,
                        "daybookname"=>$closeDayBookName,
                        "name"=>$employeeName,
                        "income"=>number_format($totalInflowBalance),
                        "expense"=>number_format($totalOutflowBalance),
                        "bankdeposit"=>number_format($totalBankDepositBalance),
                        "closingcash"=>number_format($openCloseBalance),
                        "Cashierdebit"=>$shorAmountMsg,
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
             }
                    
            echo "Day Book Closed";
        }else{
            echo "not inserted";
        }

    }

    public function closeDayBookWithSuspenseIncomeTransaction(){
        $closeDayBookName='Day Book '.date('d-M-y H:i');
        $diff=$this->input->post('diff');
        $susIncomeId=$this->CashBookModel->getSuspenseDetail('employee','SUSINC');
        $empIdForSuspenseIncome=0;
        if(!empty($susIncomeId)){
            $empIdForSuspenseIncome=$susIncomeId[0]['id'];
        }
        // print_r($empIdForSuspenseIncome);exit;
        // echo $diff;exit;

        $checkForPendingExpenses=$this->CashBookModel->checkExpenseData('notesdetails');

        if(!empty($checkForPendingExpenses)){
            echo "Expenses are not approved by owner.";
            exit;
        }
        
        $date=date('Y-m-d H:i:sa');
        $userId = $this->session->userdata[$this->projectSessionName]['id'];

        $closingBal=$this->CashBookModel->getClosingBalance('expences');
        $openingAmount=0;
        if(!empty($closingBal)){
            $openingAmount=$closingBal[0]['openCloseBalance'];
        }

        $short_amt=$this->input->post('short_amt');
        $add2000=$this->input->post('add2000');
        $add1000=$this->input->post('add1000');
        $add500=$this->input->post('add500');
        $add200=$this->input->post('add200');
        $add100=$this->input->post('add100');
        $add50=$this->input->post('add50');
        $add20=$this->input->post('add20');
        $add10=$this->input->post('add10');
        $coins=$this->input->post('coins');
        $collectedTotal=$this->input->post('collectedTotal');

        if($add2000=='' || $add2000==NULL){
            $add2000=0;
        }

        if($add1000=='' || $add1000==NULL){
            $add1000=0;
        }
        if($add500=='' || $add500==NULL){
            $add500=0;
        }
        if($add200=='' || $add200==NULL){
            $add200=0;
        }
        if($add100=='' || $add100==NULL){
            $add100=0;
        }
        if($add50=='' || $add50==NULL){
            $add50=0;
        }
        if($add20=='' || $add20==NULL){
            $add20=0;
        }
        if($add10=='' || $add10==NULL){
            $add10=0;
        }
        if($coins=='' || $coins==NULL){
            $coins=0;
        }

        $totalMarketExpense=0;
        $no=0;
        $data['inflowEmp']=$this->CashBookModel->getInflowEmpByDate('expences',$date);
        if(!empty($data['inflowEmp'])){
            foreach($data['inflowEmp'] as $dt){
                $allocationCode=0;
                $allocationParkingExpense=0;
                $allocationChallanExpense=0;
                $allocationCngExpense=0;
                $totalCollection=0;
                if($dt['allocationId']>0){
                     $alData=$this->CashBookModel->load('allocations',$dt['allocationId']);
                     $allocationCode=$alData[0]['allocationCode'];
                     $expense=$this->CashBookModel->calculateExpense($dt['allocationId']);
                     $allocationParkingExpense=$expense[0]['parking'];
                     $allocationChallanExpense=$expense[0]['challan'];
                     $allocationCngExpense=$expense[0]['cng'];
                     $loadAmount=$this->CashBookModel->loadBalAmt('notesdetails',$dt['allocationId']);
                     $totalCollection=$loadAmount[0]['parking']+$loadAmount[0]['challan']+$loadAmount[0]['cng']+$loadAmount[0]['collectedAmt'];
                }

            $totalMarketExpense=$totalMarketExpense+$allocationParkingExpense+$allocationChallanExpense+$allocationCngExpense;
           }
        }
        $availBalance=$this->CashBookModel->getAvailableBalanceByDate('expences',$date);

        $totalInflow=$this->CashBookModel->calculateTotalIncomeByDate($date);

        $totalOutflow=$this->CashBookModel->calculateTotalOutflowByDate($date);
        $totalBankDeposit=$this->CashBookModel->calculateTotalBankDepositByDate($date);

        $totalInflowBalance=0;
        $totalOutflowBalance=0;
        $totalBankDepositBalance=0;
        $openCloseBalance=0;

        if(!empty($totalInflow[0]['inflowTotal'])){
            $totalInflowBalance=$totalInflow[0]['inflowTotal'];  
            $totalInflowBalance=(floatval($totalInflow[0]['inflowTotal']));  
        }

        if(!empty($totalOutflow[0]['outflowTotal'])){
            $totalOutflowBalance=$totalOutflow[0]['outflowTotal'];
            $totalOutflowBalance=(floatval($totalOutflow[0]['outflowTotal']));   
        }

        if(!empty($totalBankDeposit[0]['bankflowTotal'])){
            $totalBankDepositBalance=$totalBankDeposit[0]['bankflowTotal']; 
        }

        if(!empty($availBalance[0]['openCloseBalance'])){
            $openCloseBalance=$availBalance[0]['openCloseBalance'];
        }

        $shortClosingAmt=($totalInflowBalance-($totalOutflowBalance+$totalBankDepositBalance))-$collectedTotal;

        //suspense income transaction
        $description="Excess Cash ".$diff." in day book ".$closeDayBookName." credited to Suspense Income";

        $susIncomeInsert=array('empId'=>$empIdForSuspenseIncome,'transactionType'=>'cr','amount'=>$diff,'description'=>$description,'createdAt'=>date('Y-m-d H:i:sa'),'createdBy'=>$userId);
        $this->CashBookModel->insert('emptransactions',$susIncomeInsert);
        
        $debitData=array();
        if($diff>0){
            $lastBal=$this->CashBookModel->lastRecordDayBookValue();
            $openCloseBalance=$lastBal['openCloseBalance'];
            if($openCloseBalance=='' || $openCloseBalance==Null){
                $openCloseBalance=0.0;
            }
            $openCloseBalance=$openCloseBalance+$diff;
            $debitData=array('employeeId'=>$userId,'category'=>'Employee Credit','nature'=>'Employee Credit','amount'=>$diff,'inoutStatus'=>'Inflow','narration'=>$description,'date'=>date('Y-m-d H:i:sa'),'openCloseBalance'=>$openCloseBalance,'updatedBy'=>$userId);
            $this->CashBookModel->insert('expences',$debitData);
        }

        if($short_amt>0){
            $lastBal=$this->CashBookModel->lastRecordDayBookValue();
            $openCloseBalance=$lastBal['openCloseBalance'];
            if($openCloseBalance=='' || $openCloseBalance==Null){
                $openCloseBalance=0.0;
            }
            $openCloseBalance=$openCloseBalance-$short_amt;
            $debitData=array('employeeId'=>$userId,'category'=>'Employee debit','nature'=>'Employee debit','amount'=>$short_amt,'inoutStatus'=>'Outflow','narration'=>'Short amount debited to cashier','date'=>date('Y-m-d H:i:sa'),'openCloseBalance'=>$openCloseBalance,'updatedBy'=>$userId);
            $this->CashBookModel->insert('expences',$debitData);
        }

        $createdAt=date('Y-m-d H:i:sa');

        //insert notes details
        $noteId=$this->insertNotesDetails('close_daybook',$collectedTotal,$userId,$createdAt,$userId,$add2000,$add1000,$add500,$add200,$add100,$add50,$add20,$add10,$coins);

        $diffopenCloseBalance=$openCloseBalance+$diff;
        $details=array('closeDayBookDate'=>$date,'empId'=>$userId,'collectedAmount'=>$collectedTotal,'closeDayBookName'=>$closeDayBookName,'note2000'=>$add2000,'note1000'=>$add1000,'note500'=>$add500,'note200'=>$add200,'note100'=>$add100,'note50'=>$add50,'note20'=>$add20,'note10'=>$add10,'coins'=>$coins,'openingBalance'=>$openingAmount,'closingBalance'=>$diffopenCloseBalance,'totalIncome'=>$totalInflowBalance,'totalExpense'=>$totalOutflowBalance,'totalBankDeposit'=>$totalBankDepositBalance,'totalMarketExpense'=>$totalMarketExpense,'createdAt'=>date('Y-m-d H:i:sa'),'createdBy'=>$userId);

        $this->CashBookModel->insert('close_daybook_notes',$details);
        if($this->db->affected_rows()>0){
            $updateExpenseStatus=array('isCloseDayBook'=>1,'closeBookDayDate'=>$date,'closeDayBookName'=>$closeDayBookName);
            $this->CashBookModel->updateExpenseStatusByDate('expences',$updateExpenseStatus);

            
            $remark="amount of ".$short_amt." short while closing daybook ".$closeDayBookName;
            
            $cashier=$this->session->userdata[$this->projectSessionName]['username'];
            $employeeName="";
            if($short_amt > 0){
                $empDebit=array('empId'=>$userId,'transactionType'=>'dr','description'=>$remark,'amount'=>$short_amt,'createdAt'=>date('Y-m-d H:i:sa'),'createdBy'=>$userId);
                $this->CashBookModel->insert('emptransactions',$empDebit);//insert remark data
                
                $employeeDetails=$this->CashBookModel->load('employee',$userId);
                $employeeMobile=$employeeDetails[0]['mobile'];
                $employeeName=$employeeDetails[0]['name'];
                $transactionDate=date('M d, Y H:i a');
                //send sms to employee
                $this->sendEmployeeDebitDayBookSMS($employeeName,$employeeMobile,$short_amt,$transactionDate);


                //for cashier
                // $mobile='9081400400';
                $mobile='8446107727';
                $desc="Amount of ".$short_amt." short while closing daybook '".$closeDayBookName."' debited to you";
                // $this->sendSMS($mobile,$desc);

                //for owner
                $mobile='8446107727';
                // $mobile='9081400400';
                $desc="Day book '".$closeDayBookName."' is closed. Amount of ".$short_amt." short while closing daybook '".$closeDayBookName."' debited to".$cashier;
                // $this->sendSMS($mobile,$desc);
            }else{
                //if not short value
                // $mobile='9081400400';
                $mobile='8446107727';
                $desc="Day book '".$closeDayBookName."' is closed by cashier ".$cashier;
                // $this->sendSMS($mobile,$desc);
            }


            $employeeDetails=$this->CashBookModel->load('employee',$userId);
            $employeeMobile=$employeeDetails[0]['mobile'];
            $employeeName=$employeeDetails[0]['name'];
            $transactionDate=date('M d, Y H:i a');
            //send sms to employee
            // $this->sendDayBookClosedSMS($employeeName,$employeeMobile,$transactionDate,$closeDayBookName,$totalInflowBalance,$totalOutflowBalance,$totalBankDepositBalance,$diffopenCloseBalance,$totalMarketExpense,$short_amt);

            $companyDetails=$this->CashBookModel->getdata('office_details');
            $officeName=$companyDetails[0]['distributorName'];
            $distributorCode=$companyDetails[0]['distributorCode'];
            
            // $office=$this->CashBookModel->load('office_details','1');
            $shorAmountMsg="";
            
            if($short_amt>0){
                $shorAmountMsg="Short Cash Rs ".number_format($short_amt)." debited to Cashier ".$employeeName." ";

                $ledger=$this->CashBookModel->getEmpLedgerByEmp('emptransactions',$userId);
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
                    "flow_id"=>"618d083facb72303634fd794",
                    "sender"=>"SIAInc",
                    "mobiles"=>'91'.$employeeMobile,
                    "name"=>$employeeName,
                    "amount"=>number_format($short_amt),
                    "distributorname"=>$officeName,
                    "daybookname"=>$closeDayBookName,
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
            }
            
            $allOwners=$this->CashBookModel->getOwners('employee');
             if(!empty($allOwners)){
                 foreach($allOwners as $mbl){
                     $jsonData=array(
                        "flow_id"=>"618d081bee9539792722cda3",
                        "sender"=>"SIAInc",
                        "mobiles"=>'91'.$mbl['mobile'],
                        "distributorname"=>$officeName,
                        "daybookname"=>$closeDayBookName,
                        "name"=>$employeeName,
                        "income"=>number_format($totalInflowBalance),
                        "expense"=>number_format($totalOutflowBalance),
                        "bankdeposit"=>number_format($totalBankDepositBalance),
                        "closingcash"=>number_format($openCloseBalance),
                        "Cashierdebit"=>$shorAmountMsg,
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
             }
                    
            echo "Day Book Closed";
        }else{
            echo "not inserted";
        }

    }


    public function sendSMS($mobile,$desc){
        $url="https://api.msg91.com/api/sendhttp.php?authkey=291106AG8eCyzDe5d626f5c&mobiles=".$mobile."&country=91&message=".$desc."&sender=TESTIN&route=4";

        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        $contents = curl_exec($ch);
        curl_close($ch);
    }
}
?>