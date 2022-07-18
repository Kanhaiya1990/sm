<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MainCashBookController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('CashBookModel');
        $this->load->library('session');
        date_default_timezone_set('Asia/Kolkata');
        ini_set('memory_limit', '-1');

        if(isset($this->session->userdata['codeKeyData'])) {
			$this->projectSessionName= $this->session->userdata['codeKeyData']['codeKeyValue'];
		}else{
			$this->load->view('LoginView');
		}
    }

    public function outstandingBills()
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url('index.php/owner/MainCashBookController/mainCashBookDetails');
        
        $config['per_page'] = ($this->input->get('limitRows')) ? $this->input->get('limitRows') : 25;
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
        $mainCashBook="";
        $rowConunts="";
        $mainCashBook = $this->CashBookModel->paginationMainCashBook('main_cashbook_expences',$config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);
        $rowCounts=$this->CashBookModel->countPaginationMainCashBook('main_cashbook_expences',$config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);

        $data['mainCashBook'] = $mainCashBook;
        $config['total_rows'] = $rowCounts;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('mainCashBook/mainCashBookDetailsView',$data);
    }

    public function mainCashBookDetails(){
        $data['mainCashBook']=$this->CashBookModel->allEntries('main_cashbook_expences');
        $this->load->view('mainCashBook/mainCashBookDetailsView',$data);
    }

    public function index()
    {
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

        $contraEntryInflow=$this->CashBookModel->calculateInflowContraEntry($date);
        $contraEntryOutflow=$this->CashBookModel->calculateOutflowContraEntry($date);

        $contraEntryOutflow=$contraEntryOutflow[0]['outflowTotal']; 
        $contraEntryInflow=$contraEntryInflow[0]['inflowTotal']; 

        // echo $contraEntryOutflow.' '.$contraEntryInflow.' '.($contraEntryInflow-$contraEntryOutflow);exit;
        
        $data['diffContraEntry']=$contraEntryInflow-$contraEntryOutflow;

        $data['totalInflow']=$totalInflow[0]['inflowTotal'];  
        $data['totalOutflow']=$totalOutflow[0]['outflowTotal'];  
        $data['totalBankDeposit']=$totalBankDeposit[0]['bankflowTotal'];  

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

        $data['mainCashBook']=$this->CashBookModel->lastEntries('main_cashbook_expences');

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

        //sum of Contra entry Outflow
        $data['expense_notes_contra']=$this->CashBookModel->getContraEntryExpenseNotesDetails('main_cashbook_notes_details');
        if(empty($data['expense_notes_contra'])){
            $data['expense_notes_contra']=array('note2000'=>0,'note500'=>0,'note200'=>0,'note100'=>0,'note50'=>0,'note20'=>0,'note10'=>0,'coins'=>0,'collectedAmt'=>0);
        }else{
             $data['expense_notes_contra']=array('note2000'=>$data['expense_notes_contra'][0]['note2000'],'note500'=>$data['expense_notes_contra'][0]['note500'],'note200'=>$data['expense_notes_contra'][0]['note200'],'note100'=>$data['expense_notes_contra'][0]['note100'],'note50'=>$data['expense_notes_contra'][0]['note50'],'note20'=>$data['expense_notes_contra'][0]['note20'],'note10'=>$data['expense_notes_contra'][0]['note10'],'coins'=>$data['expense_notes_contra'][0]['coins'],'collectedAmt'=>$data['expense_notes_contra'][0]['collectedAmt']);
        }

        //sum of Contra entry Inflow
        $data['income_notes_contra']=$this->CashBookModel->getContraEntryIncomeNotesDetails('main_cashbook_notes_details');
        if(empty($data['income_notes_contra'])){
            $data['income_notes_contra']=array('note2000'=>0,'note500'=>0,'note200'=>0,'note100'=>0,'note50'=>0,'note20'=>0,'note10'=>0,'coins'=>0,'collectedAmt'=>0);
        }else{
             $data['income_notes_contra']=array('note2000'=>$data['income_notes_contra'][0]['note2000'],'note500'=>$data['income_notes_contra'][0]['note500'],'note200'=>$data['income_notes_contra'][0]['note200'],'note100'=>$data['income_notes_contra'][0]['note100'],'note50'=>$data['income_notes_contra'][0]['note50'],'note20'=>$data['income_notes_contra'][0]['note20'],'note10'=>$data['income_notes_contra'][0]['note10'],'coins'=>$data['income_notes_contra'][0]['coins'],'collectedAmt'=>$data['income_notes_contra'][0]['collectedAmt']);
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
        
        $data['bankDep']=$bankDep['BankDepSum'];
        $data['income']=$income['income'];
        $data['expense']=$exp['expense'];
        $data['totalMarketExpense']=$totalMarketExpense;
        $data['emp']=$this->CashBookModel->getEmployeeNames();
        $this->load->view('mainCashBook/mainCashBookView',$data);
    }

    public function insertMainCashBookEntry(){
        $bookId="";
        $userId = $this->session->userdata[$this->projectSessionName]['id'];
        $createdAt=date('Y-m-d H:i:sa');

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

        $collectedDepositTotal=trim($this->input->post('collectedDepositTotal'));
        $openCloseBal=0;
        $openingBalance=0;

        $lastBal=$this->CashBookModel->lastRecordInOutFlow();

        if(!empty($lastBal)){
            $openingBalance=$lastBal['openCloseBalance'];
            $openCloseBal=$lastBal['openCloseBalance'];
        }
        
        $openCloseBal=$openCloseBal-$collectedDepositTotal;

        $mainCashBookRecord=$this->CashBookModel->getLastRecord('main_cashbook_expences');
        $balanceAmt=0;
        if(!empty($mainCashBookRecord)){
            $balanceAmt=$mainCashBookRecord['balance']+$collectedDepositTotal;
        }else{
            $balanceAmt=$balanceAmt+$collectedDepositTotal;
        }

        $userId = $this->session->userdata[$this->projectSessionName]['id'];
        $employeeDetails=$this->CashBookModel->load('employee',$userId);
        if(!empty($employeeDetails)){
            //insert notes details
            $notesId=$this->insertNotesDetails('expense',$total,$userId,$createdAt,$userId,$note2000i,$note1000i,$note500i,$note200i,$note100i,$note50i,$note20i,$note10i,$coini);
            
            //contra entry in expense table
            $insertData=array(
                'employeeId'=>$userId,
                'category'=>'Contra Entry',
                'nature'=>'Contra Entry',
                'amount'=>$collectedDepositTotal,
                'inoutStatus'=>'Outflow',
                'narration'=>'Transfer to Main Cash Book',
                'date'=>$createdAt,
                'openCloseBalance'=>$openCloseBal,
                'notesId'=>$notesId,
                'dayBookId'=>$bookId,
                'updatedBy'=>$userId,
                'isContraEntry'=>1
            );
            $this->CashBookModel->insert('expences',$insertData);

            if($this->db->affected_rows()>0){
                //insert notes details
                $notesId=$this->insertMainCashBookNotesDetails('income',$total,$openingBalance,$openCloseBal,$userId,$createdAt,$userId,$note2000i,$note1000i,$note500i,$note200i,$note100i,$note50i,$note20i,$note10i,$coini);
                //contra entry in expense table
                $insertData=array(
                    'date'=>$createdAt,
                    'employeeId'=>$userId,
                    'amount'=>$collectedDepositTotal,
                    'balance'=>$balanceAmt,
                    'nature'=>'Contra Entry',
                    'category'=>'Contra Entry',
                    'inoutStatus'=>'Inflow',
                    'narration'=>'Transfer from Day Book',
                    'notesId'=>$notesId,
                    'updatedBy'=>$userId
                );
                $this->CashBookModel->insert('main_cashbook_expences',$insertData);
            }
        }
        redirect('manager/CashBookController/IncomeExpense');
    }

    public function insertDayBookEntry(){
        $bookId="";
        $userId = $this->session->userdata[$this->projectSessionName]['id'];
        $createdAt=date('Y-m-d H:i:sa');

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

        $collectedDepositTotal=trim($this->input->post('collectedDepositTotalReturn'));
        $openCloseBal=0;
        $openingBalance=0;

        $lastBal=$this->CashBookModel->lastRecordInOutFlow();

        if(!empty($lastBal)){
            $openingBalance=$lastBal['openCloseBalance'];
            $openCloseBal=$lastBal['openCloseBalance'];
        }
        
        $openCloseBal=$openCloseBal+$collectedDepositTotal;

        $mainCashBookRecord=$this->CashBookModel->getLastRecord('main_cashbook_expences');
        $balanceAmt=0;
        if(!empty($mainCashBookRecord)){
            $balanceAmt=$mainCashBookRecord['balance']-$collectedDepositTotal;
        }else{
            $balanceAmt=$balanceAmt-$collectedDepositTotal;
        }

        $userId = $this->session->userdata[$this->projectSessionName]['id'];
        $employeeDetails=$this->CashBookModel->load('employee',$userId);
        if(!empty($employeeDetails)){
            //insert notes details
            $notesId=$this->insertNotesDetails('income',$total,$userId,$createdAt,$userId,$note2000i,$note1000i,$note500i,$note200i,$note100i,$note50i,$note20i,$note10i,$coini);
            
            //contra entry in expense table
            $insertData=array(
                'employeeId'=>$userId,
                'category'=>'Contra Entry',
                'nature'=>'Contra Entry',
                'amount'=>$collectedDepositTotal,
                'inoutStatus'=>'Inflow',
                'narration'=>'Transfer from Main Cash Book',
                'date'=>$createdAt,
                'openCloseBalance'=>$openCloseBal,
                'notesId'=>$notesId,
                'dayBookId'=>$bookId,
                'updatedBy'=>$userId,
                'isContraEntry'=>1
            );
            $this->CashBookModel->insert('expences',$insertData);

            if($this->db->affected_rows()>0){
                //insert notes details
                $notesId=$this->insertMainCashBookNotesDetails('expense',$total,$openingBalance,$openCloseBal,$userId,$createdAt,$userId,$note2000i,$note1000i,$note500i,$note200i,$note100i,$note50i,$note20i,$note10i,$coini);
                //contra entry in expense table
                $insertData=array(
                    'date'=>$createdAt,
                    'employeeId'=>$userId,
                    'amount'=>$collectedDepositTotal,
                    'balance'=>$balanceAmt,
                    'nature'=>'Contra Entry',
                    'category'=>'Contra Entry',
                    'inoutStatus'=>'Outflow',
                    'narration'=>'Transfer to Day Book',
                    'notesId'=>$notesId,
                    'updatedBy'=>$userId
                );
                $this->CashBookModel->insert('main_cashbook_expences',$insertData);
            }
        }
        redirect('owner/MainCashBookController');
    }

    public function insertBankDepositEntry(){
        $bookId="";
        $userId = $this->session->userdata[$this->projectSessionName]['id'];
        $createdAt=date('Y-m-d H:i:sa');

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

        $collectedDepositTotal=trim($this->input->post('collectedDepositTotalReturn'));
        $openCloseBal=0;
        $openingBalance=0;

        $date=date("Y-m-d");
        // $contraEntryInflow=$this->CashBookModel->calculateInflowContraEntry($date);
        // $contraEntryOutflow=$this->CashBookModel->calculateOutflowContraEntry($date);

        // $contraEntryOutflow=$contraEntryOutflow[0]['outflowTotal']; 
        // $contraEntryInflow=$contraEntryInflow[0]['inflowTotal']; 
        
        // $diffContraEntry=$contraEntryOutflow-$contraEntryInflow;

        $mainCashBookRecord=$this->CashBookModel->getLastRecord('main_cashbook_expences');
        $balanceAmt=0;
        if(!empty($mainCashBookRecord)){
            $balanceAmt=$mainCashBookRecord['balance']-$total;
        }else{
            $balanceAmt=$balanceAmt-$total;
        }
    //    echo $total.' '.$balanceAmt;exit;

        $userId = $this->session->userdata[$this->projectSessionName]['id'];
        $employeeDetails=$this->CashBookModel->load('employee',$userId);
        if(!empty($employeeDetails)){
            //insert notes details
            $notesId=$this->insertMainCashBookNotesDetails('expense',$total,$openingBalance,$openCloseBal,$userId,$createdAt,$userId,$note2000i,$note1000i,$note500i,$note200i,$note100i,$note50i,$note20i,$note10i,$coini);
            //contra entry in expense table
            $insertData=array(
                'date'=>$createdAt,
                'employeeId'=>$userId,
                'amount'=>$total,
                'balance'=>$balanceAmt,
                'nature'=>'Bank Deposit',
                'category'=>'Bank Deposit',
                'inoutStatus'=>'Outflow',
                'narration'=>'Bank Deposit on '.date('Y-m-d H:i:sa'),
                'notesId'=>$notesId,
                'updatedBy'=>$userId,
                'bankDepositApproval'=>1
            );
            $this->CashBookModel->insert('main_cashbook_expences',$insertData);
        }
        redirect('owner/MainCashBookController');
    }

    public function updateBankDepositEntry(){
        $bookId="";
        $userId = $this->session->userdata[$this->projectSessionName]['id'];
        $createdAt=date('Y-m-d H:i:sa');

        $bankDepId = $this->input->post('bankDepId');

        $note2000i=$this->input->post('add2000');
        $note1000i=0;
        $note500i=$this->input->post('add500');
        $note200i=$this->input->post('add200');
        $note100i=$this->input->post('add100');
        $note50i=$this->input->post('add50');
        $note20i=$this->input->post('add20');
        $note10i=$this->input->post('add10');
        $coini=$this->input->post('coin');

        $rem2000=$this->input->post('rem2000');
        $rem1000=0;
        $rem500=$this->input->post('rem500');
        $rem200=$this->input->post('rem200');
        $rem100=$this->input->post('rem100');
        $rem50=$this->input->post('rem50');
        $rem20=$this->input->post('rem20');
        $rem10=$this->input->post('rem10');
        $remcoins=$this->input->post('remcoins');

        $note2000=$this->input->post('add2000');
        if($note2000==''||$note2000==NULL){
            $note2000=0;
            $note2000i=0;
        }else{
            $note2000=2000*(float)$note2000;
        }
        
        $note1000=0;
        if($note1000==''||$note1000==NULL){
            $note1000=0;
            $note1000i=0;
        }else{
            $note1000=1000*(float)$note1000;
        }

        $note500=$this->input->post('add500');
        if($note500==''||$note500==NULL){
            $note500=0;
            $note500i=0;
        }else{
            $note500=500*(float)$note500;
        }

        $note200=$this->input->post('add200');
        if($note200==''||$note200==NULL){
            $note200=0;
            $note200i=0;
        }else{
            $note200=200*(float)$note200;
        }
        
        $note100=$this->input->post('add100');
        if($note100==''||$note100==NULL){
            $note100=0;
            $note100i=0;
        }else{
            $note100=100*(float)$note100;
        }
        
        $note50=$this->input->post('add50');
        if($note50==''||$note50==NULL){
            $note50=0;
            $note50i=0;
        }else{
            $note50=50*(float)$note50;
        }

        $note20=$this->input->post('add20');
        if($note20==''||$note20==NULL){
            $note20=0;
            $note20i=0;
        }else{
            $note20=20*(float)$note20;
        }
        
        $note10=$this->input->post('add10');
        if($note10==''||$note10==NULL){
            $note10=0;
            $note10i=0;
        }else{
            $note10=10*(float)$note10;
        }
        
        $coin=$this->input->post('coin');
        if($coin==''||$coin==NULL){
            $coin=0;
            $coini=0;
        }else{
            $coin=(float)$coin;
        }
    
        $total=$note2000+$note1000+$note500+$note200+$note100+$note50+$note20+$note10+$coin;

        $rem2000Act=$this->input->post('rem2000');
        if($rem2000Act==''||$rem2000Act==NULL){
            $rem2000Act=0;
            $rem2000=0;
        }else{
            $rem2000Act=2000*(float)$rem2000Act;
        }
        
        $rem1000Act=0;
        if($rem1000Act==''||$rem1000Act==NULL){
            $rem1000Act=0;
            $rem1000=0;
        }else{
            $rem1000Act=1000*(float)$rem1000Act;
        }

        $rem500Act=$this->input->post('rem500');
        if($rem500Act==''||$rem500Act==NULL){
            $rem500Act=0;
            $rem500=0;
        }else{
            $rem500Act=500*(float)$rem500Act;
        }

        $rem200Act=$this->input->post('rem200');
        if($rem200Act==''||$rem200Act==NULL){
            $rem200Act=0;
            $rem200=0;
        }else{
            $rem200Act=200*(float)$rem200Act;
        }
        
        $rem100Act=$this->input->post('rem100');
        if($rem100Act==''||$rem100Act==NULL){
            $rem100Act=0;
            $rem100=0;
        }else{
            $rem100Act=100*(float)$rem100Act;
        }
        
        $rem50Act=$this->input->post('rem50');
        if($rem50Act==''||$rem50Act==NULL){
            $rem50Act=0;
            $rem50=0;

        }else{
            $rem50Act=50*(float)$rem50Act;
        }

        $rem20Act=$this->input->post('rem20');
        if($rem20Act==''||$rem20Act==NULL){
            $rem20Act=0;
            $rem20=0;

        }else{
            $rem20Act=20*(float)$rem20Act;
        }
        
        $rem10Act=$this->input->post('rem10');
        if($rem10Act==''||$rem10Act==NULL){
            $rem10Act=0;
            $rem10=0;

        }else{
            $rem10Act=10*(float)$rem10Act;
        }
        
        $remcoinsAct=$this->input->post('remcoins');
        if($remcoinsAct==''||$remcoinsAct==NULL){
            $remcoinsAct=0;
            $remcoins=0;
        }else{
            $remcoinsAct=(float)$remcoinsAct;
        }
    
        $total=$note2000+$note1000+$note500+$note200+$note100+$note50+$note20+$note10+$coin;
        $totalRem=$rem2000Act+$rem1000Act+$rem500Act+$rem200Act+$rem100Act+$rem50Act+$rem20Act+$rem10Act+$remcoinsAct;
        // echo $totalRem-$total;exit;

        $userId=$this->session->userdata[$this->projectSessionName]['id'];

        $collectedDepositTotal=trim($this->input->post('collectedDepositTotalReturn'));
        $openCloseBal=0;
        $openingBalance=0;

        $date=date("Y-m-d");
        $userId = $this->session->userdata[$this->projectSessionName]['id'];
        $employeeDetails=$this->CashBookModel->load('employee',$userId);
        if(!empty($employeeDetails)){
            $updateData=array(
                'amount'=>$total,
                'updatedBy'=>$userId,
                'updatedAt'=>$createdAt,
                'bankDepositApproval'=>0
            );
            $this->CashBookModel->updateBankDeposit('main_cashbook_expences',$updateData,$bankDepId);

            $returnNotes=array(
                'empId'=>$this->session->userdata[$this->projectSessionName]['id'],
                'transactionType'=>'income',
                'note2000'=>($rem2000-$note2000i),
                'note1000'=>($rem1000-$note1000i),
                'note500'=>($rem500-$note500i),
                'note200'=>($rem200-$note200i),
                'note100'=>($rem100-$note100i),
                'note50'=>($rem50-$note50i),
                'note20'=>($rem20-$note20i),
                'note10'=>($rem10-$note10i),
                'coins'=>($remcoins-$coini),
                'collectedAmount'=>($totalRem-$total),
                'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
                'createdAt'=>$createdAt
            );
            $this->CashBookModel->insert('main_cashbook_notes_details',$returnNotes);

            $mainCashBookRecord=$this->CashBookModel->getLastRecord('main_cashbook_expences');
            $balanceAmt=0;
            if(!empty($mainCashBookRecord)){
                $balanceAmt=$mainCashBookRecord['balance']+($totalRem-$total);
            }else{
                $balanceAmt=$balanceAmt+($totalRem-$total);
            }
    
            $insertData=array(
                'date'=>$createdAt,
                'employeeId'=>$userId,
                'amount'=>($totalRem-$total),
                'balance'=>$balanceAmt,
                'nature'=>'Contra Entry',
                'category'=>'Contra Entry',
                'inoutStatus'=>'Inflow',
                'narration'=>'Remaining Bank Deposit Return',
                'notesId'=>($this->db->insert_id()),
                'updatedBy'=>$userId,
                'isBankDepositReturn'=>1
            );
            $this->CashBookModel->insert('main_cashbook_expences',$insertData);
            
        }
        redirect('owner/MainCashBookController');
    }

    //insert notes details for income/expence/bank deposit/cashbook
    public function insertNotesDetails($type,$collectedAmount,$updatedBy,$updatedAt,$empId,$note2000,$note1000,$note500,$note200,$note100,$note50,$note20,$note10,$coin){
        $insertData=array(
            'transactionType'=>$type,
            'collectedAmt'=>$collectedAmount,
            'updatedBy'=>$updatedBy,
            'updatedAt'=>$updatedAt,
            'empId'=>$empId,
            'note2000'=>$note2000,
            'note1000'=>$note1000,
            'note500'=>$note500,
            'note200'=>$note200,
            'note100'=>$note100,
            'note50'=>$note50,
            'note20'=>$note20,
            'note10'=>$note10,
            'coins'=>$coin
        );

        $this->CashBookModel->insert('notesdetails',$insertData);
        return $this->db->insert_id();
    }

    //insert notes details for income/expence/bank deposit/cashbook
    public function insertMainCashBookNotesDetails($type,$collectedAmount,$opening,$closing,$updatedBy,$updatedAt,$empId,$note2000,$note1000,$note500,$note200,$note100,$note50,$note20,$note10,$coin){
        $insertData=array(
            'empId'=>$empId,
            'transactionType'=>$type,
            'note2000'=>$note2000,
            'note1000'=>$note1000,
            'note500'=>$note500,
            'note200'=>$note200,
            'note100'=>$note100,
            'note50'=>$note50,
            'note20'=>$note20,
            'note10'=>$note10,
            'coins'=>$coin,
            'collectedAmount'=>$collectedAmount,
            'createdBy'=>$updatedBy,
            'createdAt'=>$updatedAt
        );

        $this->CashBookModel->insert('main_cashbook_notes_details',$insertData);
        return $this->db->insert_id();
    }

    //update notes details for income/expence/bank deposit/cashbook
    public function updateMainCashBookNotesDetails($bankDepId,$type,$collectedAmount,$opening,$closing,$updatedBy,$updatedAt,$empId,$note2000,$note1000,$note500,$note200,$note100,$note50,$note20,$note10,$coin){
        $insertData=array(
            'empId'=>$empId,
            'transactionType'=>$type,
            'note2000'=>$note2000,
            'note1000'=>$note1000,
            'note500'=>$note500,
            'note200'=>$note200,
            'note100'=>$note100,
            'note50'=>$note50,
            'note20'=>$note20,
            'note10'=>$note10,
            'coins'=>$coin,
            'collectedAmount'=>$collectedAmount,
            'createdBy'=>$updatedBy,
            'createdAt'=>$updatedAt
        );

        $this->CashBookModel->update('main_cashbook_notes_details',$insertData,$bankDepId);
        return $this->db->insert_id();
    }

}