<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AccountantController extends CI_Controller {

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
    }

    public function incomeExpense(){
        $fromDate=$this->input->post('from_date');
        $toDate=$this->input->post('to_date');

        $data['startDate']=$fromDate;
        $data['endDate']=$toDate;

        if($fromDate=="" && $toDate==""){ 
            $ownerExpenseApproval=$this->CashBookModel->getPendingExpenseCount('notesdetails');
            $rowCount=count($ownerExpenseApproval);
            $data['ownerExpenseCount']=$rowCount;

            $ownerExpenseApprovalTo=$this->CashBookModel->getPendingCashExpenseCount('expences');
            $rowCount=count($ownerExpenseApprovalTo);
            $data['ownerExpenseCountTo']=$rowCount;

            $data['dayBook']=$this->CashBookModel->getDaybookDates('expences');
            $this->load->view('accountant/accountantIncomeExpenseView',$data);
        }else{
            $ownerExpenseApproval=$this->CashBookModel->getPendingExpenseCount('notesdetails');
            $rowCount=count($ownerExpenseApproval);
            $data['ownerExpenseCount']=$rowCount;

            $ownerExpenseApprovalTo=$this->CashBookModel->getPendingCashExpenseCount('expences');
            $rowCount=count($ownerExpenseApprovalTo);
            $data['ownerExpenseCountTo']=$rowCount;

            $data['dayBook']=$this->CashBookModel->getDaybookDatesWithDates('expences',$fromDate,$toDate);
            $this->load->view('accountant/accountantIncomeExpenseView',$data);
        }
    }


    public function incomeExpenseDetails($daybook_date){
        $accountDates=urldecode($daybook_date);
        $data['dates']=$accountDates;

        $data['inflowEmp']=$this->CashBookModel->getInflowEmpByDayBookDate('expences',$accountDates);

        $totalMarketExpense=0;
        $parkingExpense=0;
        $challanExpense=0;
        $cngExpense=0;
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
                     $parkingExpense=$parkingExpense+$expense[0]['parking'];

                     $allocationChallanExpense=$expense[0]['challan'];
                     $challanExpense=$challanExpense+$expense[0]['challan'];

                     $allocationCngExpense=$expense[0]['cng'];
                     $cngExpense=$cngExpense+$expense[0]['cng'];
                     $loadAmount=$this->CashBookModel->loadBalAmt('notesdetails',$dt['allocationId']);
                     $totalCollection=$loadAmount[0]['parking']+$loadAmount[0]['challan']+$loadAmount[0]['cng']+$loadAmount[0]['collectedAmt'];
                }

            $totalMarketExpense=$totalMarketExpense+$allocationParkingExpense+$allocationChallanExpense+$allocationCngExpense;
           }
        }

        $totalInflow=$this->CashBookModel->calculateTotalIncomeByDaybookDate($accountDates);
        $totalOutflow=$this->CashBookModel->calculateTotalOutflowByDaybookDate($accountDates);
      
         // print_r($totalOutflow);exit;
        $data['parkingExpense']=$parkingExpense;
        $data['challanExpense']=$challanExpense;
        $data['cngExpense']=$cngExpense;

        $data['totalInflow']=$totalInflow[0]['inflowTotal'];  
        $data['totalOutflow']=$totalOutflow[0]['outflowTotal'];  
        $data['totalMarketExpense']=$totalMarketExpense;
        
        //Income/Expense categories from table
        $company=$this->CashBookModel->getInfo('company');

        $data['company']=$company;
        $data['incomeCategory']=$this->CashBookModel->getByType('categories_income_expenses','income');

        //manual entries
        $type1=array('id'=>11111,'categoryName'=>'Market Collection','type'=>'income','isStatic'=>1);
        array_push($data['incomeCategory'],$type1);

        $type1=array('id'=>22222,'categoryName'=>'Office Collection','type'=>'income','isStatic'=>1);
        array_push($data['incomeCategory'],$type1);
        
        $type2=array('id'=>33333,'categoryName'=>'Disallowed Advance Reversal','type'=>'income','isStatic'=>1);
        array_push($data['incomeCategory'],$type2);
       
        $type3=array('id'=>44444,'categoryName'=>'Disallowed Expense Reversal','type'=>'income','isStatic'=>1);
        array_push($data['incomeCategory'],$type3);


        $data['expenseCategory']=$this->CashBookModel->getByType('categories_income_expenses','expenses');
        $type2=array('id'=>111,'categoryName'=>'Employee debit','type'=>'outflow','isStatic'=>1);
        array_push($data['expenseCategory'],$type2);

        $data['cmpIncome']=array();
        $data['cmpExpense']=array();
        $data['incomeByType']=array();
        $data['expenseByType']=array();
        foreach($company as $cmp){
            //get income by company
            $cmpIncome=$this->CashBookModel->calculateTotalIncomeByCompanyDate($accountDates,$cmp['name']);
            if(empty($cmpIncome)){
                $cmpIncome=0;
            }
            $data['cmpIncome'][]=array('company'=>$cmp['name'],'income'=>$cmpIncome);

            //get expense by company
            $cmpExepnse=$this->CashBookModel->calculateTotalOutflowByCompanyDate($accountDates,$cmp['name']);
            if(empty($cmpExepnse)){
                $cmpExepnse=0;
            }
            $data['cmpExpense'][]=array('company'=>$cmp['name'],'expense'=>$cmpExepnse);

            

            //get income for company by type
            foreach($data['incomeCategory'] as $cat){
                $cmpTypeIncome=$this->CashBookModel->calculateTotalIncomeByCompanyTypeDate($accountDates,$cmp['name'],$cat['categoryName']);
                if(empty($cmpTypeIncome)){
                    $cmpTypeIncome="";
                }
                $data['incomeByType'][]=array('company'=>$cmp['name'],'type'=>$cat['categoryName'],'income'=>$cmpTypeIncome);
            }

            //get expense for company by type
            foreach($data['expenseCategory'] as $cat){
                $cmpTypeExpense=$this->CashBookModel->calculateTotalOutflowByCompanyTypeDate($accountDates,$cmp['name'],$cat['categoryName']);
                if(empty($cmpTypeExpense)){
                    $cmpTypeExpense="";
                }
                $data['expenseByType'][]=array('company'=>$cmp['name'],'type'=>$cat['categoryName'],'expense'=>$cmpTypeExpense);
            }
        }

        $this->load->view('accountant/incomeExpenseDetailsView',$data);
    }

    public function periodIncomeExpense(){
        $fromDate=$this->input->post('from_date');
        $toDate=$this->input->post('to_date');
        if($fromDate=="" && $toDate==""){ 
            $fromDate=date('Y-m-01');
            $toDate=date('Y-m-d');
            $ownerExpenseApproval=$this->CashBookModel->getPendingExpenseCountBetweenDate('notesdetails',$fromDate,$toDate);
            $ownerExpenseApprovalTo=$this->CashBookModel->getPendingCashExpenseCountBetweenDate('expences',$fromDate,$toDate);
            if((!empty($ownerExpenseApproval)) || (!empty($ownerExpenseApprovalTo))){ 
              echo "Expenses are not approved by owner.";exit;
            }else{ 
                $this->periodIncomeExpenseDetails($fromDate,$toDate);
            }
        }else{
            $fromDate=$this->input->post('from_date');
            $toDate=$this->input->post('to_date');
            $ownerExpenseApproval=$this->CashBookModel->getPendingExpenseCountBetweenDate('notesdetails',$fromDate,$toDate);
            $ownerExpenseApprovalTo=$this->CashBookModel->getPendingCashExpenseCountBetweenDate('expences',$fromDate,$toDate);
            if((!empty($ownerExpenseApproval)) || (!empty($ownerExpenseApprovalTo))){ 
              echo "Expenses are not approved by owner.";exit;
            }else{ 
                $this->periodIncomeExpenseDetails($fromDate,$toDate);
            }
        }
    }

    public function oldperiodIncomeExpense(){
        $fromDate=$this->input->post('from_date');
        $toDate=$this->input->post('to_date');
        if($fromDate=="" && $toDate==""){ 
            $fromDate=date('Y-m-01');
            $toDate=date('Y-m-d');
            $this->periodIncomeExpenseDetails($fromDate,$toDate);
        }else{
            $fromDate=$this->input->post('from_date');
            $toDate=$this->input->post('to_date');
            $this->periodIncomeExpenseDetails($fromDate,$toDate);
        }
    }

    public function periodIncomeExpenseDetails($startDate,$endDate){
        $startDate=urldecode($startDate);
        $data['startDate']=$startDate;

        $endDate=urldecode($endDate);
        $data['endDate']=$endDate;

        $data['inflowEmp']=$this->CashBookModel->getPeriodInflowEmpByDayBookDate('expences',$startDate,$endDate);
        $totalMarketExpense=0;
        $parkingExpense=0;
        $challanExpense=0;
        $cngExpense=0;
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
                     $parkingExpense=$parkingExpense+$expense[0]['parking'];

                     $allocationChallanExpense=$expense[0]['challan'];
                     $challanExpense=$challanExpense+$expense[0]['challan'];

                     $allocationCngExpense=$expense[0]['cng'];
                     $cngExpense=$cngExpense+$expense[0]['cng'];
                     $loadAmount=$this->CashBookModel->loadBalAmt('notesdetails',$dt['allocationId']);
                     $totalCollection=$loadAmount[0]['parking']+$loadAmount[0]['challan']+$loadAmount[0]['cng']+$loadAmount[0]['collectedAmt'];
                }

            $totalMarketExpense=$totalMarketExpense+$allocationParkingExpense+$allocationChallanExpense+$allocationCngExpense;
           }
        }

        $totalInflow=$this->CashBookModel->calculateTotalPeriodIncomeByDaybookDate($startDate,$endDate);
        $totalOutflow=$this->CashBookModel->calculateTotalPeriodOutflowByDaybookDate($startDate,$endDate);
      
        $data['parkingExpense']=$parkingExpense;
        $data['challanExpense']=$challanExpense;
        $data['cngExpense']=$cngExpense;

        $data['totalInflow']=$totalInflow[0]['inflowTotal'];  
        $data['totalOutflow']=$totalOutflow[0]['outflowTotal'];  
        $data['totalMarketExpense']=$totalMarketExpense;
        
        //Income/Expense categories from table
        $company=$this->CashBookModel->getInfo('company');

        $data['company']=$company;
        $data['incomeCategory']=$this->CashBookModel->getByType('categories_income_expenses','income');
        $type1=array('id'=>11111,'categoryName'=>'Market Collection','type'=>'income','isStatic'=>1);
        array_push($data['incomeCategory'],$type1);
        $type2=array('id'=>22222,'categoryName'=>'Disallowed Advance Reversal','type'=>'income','isStatic'=>1);
        array_push($data['incomeCategory'],$type2);
        $type3=array('id'=>33333,'categoryName'=>'Disallowed Expense Reversal','type'=>'income','isStatic'=>1);
        array_push($data['incomeCategory'],$type3);


        $data['expenseCategory']=$this->CashBookModel->getByType('categories_income_expenses','expenses');
        $type2=array('id'=>111,'categoryName'=>'Employee debit','type'=>'outflow','isStatic'=>1);
        array_push($data['expenseCategory'],$type2);

        $data['cmpIncome']=array();
        $data['cmpExpense']=array();
        $data['incomeByType']=array();
        $data['expenseByType']=array();
        foreach($company as $cmp){
            //get income by company
            $cmpIncome=$this->CashBookModel->calculateTotalPeriodIncomeByCompanyDate($startDate,$endDate,$cmp['name']);
            if(empty($cmpIncome)){
                $cmpIncome=0;
            }
            $data['cmpIncome'][]=array('company'=>$cmp['name'],'income'=>$cmpIncome);

            //get expense by company
            $cmpExepnse=$this->CashBookModel->calculateTotalPeriodOutflowByCompanyDate($startDate,$endDate,$cmp['name']);
            if(empty($cmpExepnse)){
                $cmpExepnse=0;
            }
            $data['cmpExpense'][]=array('company'=>$cmp['name'],'expense'=>$cmpExepnse);

            //get income for company by type
            foreach($data['incomeCategory'] as $cat){
                $cmpTypeIncome=$this->CashBookModel->calculateTotalPeriodIncomeByCompanyTypeDate($startDate,$endDate,$cmp['name'],$cat['categoryName']);
                if(empty($cmpTypeIncome)){
                    $cmpTypeIncome="";
                }
                $data['incomeByType'][]=array('company'=>$cmp['name'],'type'=>$cat['categoryName'],'income'=>$cmpTypeIncome);
            }

            //get expense for company by type
            foreach($data['expenseCategory'] as $cat){
                $cmpTypeExpense=$this->CashBookModel->calculateTotalPeriodOutflowByCompanyTypeDate($startDate,$endDate,$cmp['name'],$cat['categoryName']);
                if(empty($cmpTypeExpense)){
                    $cmpTypeExpense="";
                }
                $data['expenseByType'][]=array('company'=>$cmp['name'],'type'=>$cat['categoryName'],'expense'=>$cmpTypeExpense);
            }
        }

        

        $this->load->view('accountant/periodIncomeExpenseDetailsView',$data);
    }
}

?>