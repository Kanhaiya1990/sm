<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SettingsController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('SettingsModel');
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

    public function bouncedAdhocCheques(){
        $data['days']=$this->SettingsModel->getInfo('highlighting_days');
        
        $this->load->view('admin/bouncedAdhocChequesView',$data);
    }

    public function dynamicNames(){
        $data['dynamicNames']=$this->SettingsModel->getDynamicNames('dynamic_names','expense');
        
        $this->load->view('settingForDynamicNamesView',$data);
    }

    public function settingForDeliveryslip(){
        $data['setting']= $this->SettingsModel->getInfo('tbl_settings');
        $this->load->view('settingForDeliveryslipView',$data);
    }

    public function saveSettingForDeliveryslip(){
        $id=$this->input->post('id');
        $value=$this->input->post('radioValue');

        $updateValue=array('propertyValue'=>$value);
        $this->SettingsModel->update('tbl_settings',$updateValue,$id);
    }

    public function saveSettingForDynamicNames(){
        $fname=$this->input->post('fname');
        $sname=$this->input->post('sname');
        $tname=$this->input->post('tname');

        $updateValue=array('name'=>$fname);
        $this->SettingsModel->update('dynamic_names',$updateValue,1);

        $updateValue=array('name'=>$sname);
        $this->SettingsModel->update('dynamic_names',$updateValue,2);

        $updateValue=array('name'=>$tname);
        $this->SettingsModel->update('dynamic_names',$updateValue,3);
    }

    public function billsHighlightingDays(){
        $data['days']=$this->SettingsModel->getInfo('highlighting_days');
        
        $this->load->view('admin/billsHighlightLimitView',$data);
    }

    public function retailersHighlightingDays(){
        $data['days']=$this->SettingsModel->getInfo('highlighting_days');
        
        $this->load->view('admin/retailerHighlightLimitView',$data);
    }

    public function allocationsHighlightingDays(){
        $data['days']=$this->SettingsModel->getInfo('highlighting_days');
        
        $this->load->view('admin/allocationsHighlightLimitView',$data);
    }

    public function chequesHighlightingDays(){
        $data['days']=$this->SettingsModel->getInfo('highlighting_days');
        
        $this->load->view('admin/chequesHighlightLimitView',$data);
    }

    public function expenseLimitSetting(){
        $data['days']=$this->SettingsModel->getInfo('expenses_limit');
        $this->load->view('admin/expenseLimitSettingView',$data);
    }

    public function highlightingDays(){
        $data['days']=$this->SettingsModel->getInfo('highlighting_days');
        $days=$this->SettingsModel->getInfo('highlighting_days');
        $specificCompany=$this->SettingsModel->getInfo('company');
        $companyCount=count($specificCompany)+1;

        $companyDaysForBills="";
        $companyDaysForRetailers="";
        $companyName="";
        if(!empty($specificCompany)){
            if($days[0]['id']==1){
                 $companyDaysForBills=trim($days[0]['days']);
                 $companyDaysForBills= explode(",",$companyDaysForBills);
            }

            if($days[3]['id']==4){
                 $companyDaysForRetailers=trim($days[3]['days']);
                 $companyDaysForRetailers= explode(",",$companyDaysForRetailers);
            }

            // $companyName=trim($specificCompany[0]['name']);
            // $companyName= explode(",",$companyName);
        }

        $data['companyCount']=$companyCount;
        $data['companyName']=$specificCompany;
        $data['companyDaysForBills']=$companyDaysForBills;
        $data['companyDaysForRetailers']=$companyDaysForRetailers;
        // echo count($data['companyDaysForRetailers']);exit;
        $this->load->view('admin/allLimitsView',$data);
    }

    public function thresholdLimit(){
        $data['expenseLimit']=$this->SettingsModel->getInfo('expenses_limit');
        $data['resendData']=$this->SettingsModel->getInfo('resend_limit');
        $this->load->view('admin/thresholdLimitView',$data);
    }

    public function updatedDaysLimit(){
        $limitId=trim($this->input->post('id'));
        $days=trim($this->input->post('days'));
        $data=array('days'=>$days);
        $this->SettingsModel->update('highlighting_days',$data,$limitId);
        // redirect('admin/EmployeeRelationController/cashierExpensesLimit');
    }

    public function updatedExpenseLimit(){
        $limitId=trim($this->input->post('id'));
        $amount=trim($this->input->post('amount'));
        $data=array(
            'expenseLimit'=>$amount,
            'updatedBy'=>$this->session->userdata[$this->projectSessionName]['id'],
            'updatedAt'=>date('Y-m-d H:i:sa')
        );
        $this->SettingsModel->update('expenses_limit',$data,$limitId);
    }

    public function updatedCompanyDaysLimit(){
        $limitId=trim($this->input->post('id'));
        $days=trim($this->input->post('days'));
        $days = trim($days,",");
        $data=array('days'=>$days);
        $this->SettingsModel->update('highlighting_days',$data,$limitId);
        // redirect('admin/EmployeeRelationController/cashierExpensesLimit');
    }

     public function insertBillClearenceLimit(){
        $limitId=trim($this->input->post('id'));
        $amount=trim($this->input->post('amount'));
        $data=array('expenseLimit'=>$amount);
        $this->SettingsModel->update('expenses_limit',$data,$limitId);
        // redirect('admin/EmployeeRelationController/cashierExpensesLimit');
    }

    //// For Resend Limit
    public function resendLimit()
    {
        $data['resendData']=$this->SettingsModel->getInfo('resend_limit');
        $this->load->view('admin/resendBillLimitView',$data);
    }

    public function updateResendLimit(){
        $limitId=trim($this->input->post('id'));
        $recend_percent=trim($this->input->post('recend_percent'));
        $data=array('resendLimit'=>$recend_percent);
        $this->SettingsModel->update('resend_limit',$data,$limitId);
        // redirect('admin/EmployeeRelationController/cashierExpensesLimit');
    }

    public function loginTime()
    {
        $data['loginData']=$this->SettingsModel->getInfo('login_limit');
        $this->load->view('admin/addLoginTimeView',$data);
    }

    public function updatedLoginTimeLimit(){
        $userId = $this->session->userdata[$this->projectSessionName]['id'];
        $id=trim($this->input->post('id'));
        $fromTime=trim($this->input->post('fromTime'));
        $toTime=trim($this->input->post('toTime'));
        $data=array(
            'fromTime'=>$fromTime,
            'toTime'=>$toTime,
            'createdBy'=>$userId
        );
        $this->SettingsModel->update('login_limit',$data,$id);
        redirect('DashbordController');
    }
    

}
?>
