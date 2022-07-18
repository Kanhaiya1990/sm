<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CashfreePaymentGatewayController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('PaymentGatewayModel');
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

    public function manageAccountDetails(){
        $officeDetails=$this->PaymentGatewayModel->getdata('office_details');
        $distributorCode=$officeDetails[0]['distributorCode'];
       
        $distributorInfo=$this->PaymentGatewayModel->getUserByCode('distributors_details',$distributorCode);
        $userId=$distributorInfo[0]['id'];
        $username=$distributorInfo[0]['email'];
        $password=$distributorInfo[0]['password'];
        $baseUrl=$distributorInfo[0]['adminBaseUrl'];

        $session_data = array(
            'id' =>$userId,
            'username' =>$username
        );
        // Add user data in session
        $this->session->set_userdata('paymentSession', $session_data);

        // $data['adminBaseUrl']=base_url()."assets/uploads/pdf";
        $data['adminBaseUrl']=$baseUrl."assets/uploads/pdf";
        $data['redirectUrl']=$baseUrl."index.php/UserAuthentication/distributorsLoginForUser/".urlencode($username)."/".urlencode($password);

        $data['packageLists']=$this->PaymentGatewayModel->loadData('packages');

        $data['custPackageLists']=$this->PaymentGatewayModel->load('packages',$distributorInfo[0]['package']);
        $data['customerData']=$this->PaymentGatewayModel->getUserById('distributors_details',$userId);
        
        $invData1=$this->PaymentGatewayModel->getLastId('invoices',$userId);
        $id = $invData1[0]['id'];
        $data['invoiceData']=$this->PaymentGatewayModel->loadDataById('invoices',$id);
        $data['distTransactionDetails']=$this->PaymentGatewayModel->getLastFive('transaction',$userId);

        $data['lastEntry']=$this->PaymentGatewayModel->getLastEntry('transaction',$userId);

        $siaOfficeDetails=$this->PaymentGatewayModel->getTableData('office_details');
        $data['siaGstStateCode']=$siaOfficeDetails[0]['gstStateCode'];  //for checking GST code
        $data['officeGstStateCode']= $data['customerData'][0]['gstStateCode']; //for checking GST code

        $gstPercent=0;
        $sacCode=0;
        $convenienceFeeSacCode=0;
        
        if($siaOfficeDetails[0]['gstPercent'] !=''){
            $gstPercent=$siaOfficeDetails[0]['gstPercent'];
        }

        if($siaOfficeDetails[0]['sacCode'] !=''){
            $sacCode=$siaOfficeDetails[0]['sacCode'];
        }

        if($siaOfficeDetails[0]['convenienceFeeSacCode'] !=''){
            $convenienceFeeSacCode=$siaOfficeDetails[0]['convenienceFeeSacCode'];
        }

        $data['gstPercent']=$gstPercent;
        $data['sacCode']=$sacCode;
        $data['convenienceFeeSacCode']=$convenienceFeeSacCode;

        $this->load->view('paymentGateway/managePaymentAccountView',$data);
    }


    public function showPackageModal(){
        $officeDetails=$this->PaymentGatewayModel->getdata('office_details');
        $distributorCode=$officeDetails[0]['distributorCode'];

        $distributorInfo=$this->PaymentGatewayModel->getUserByCode('distributors_details',$distributorCode);
        $validTill=$distributorInfo[0]['validTill'];

        $projectSessionName="";
        $id=0;
        if (isset($this->session->userdata['codeKeyData'])) {
            $projectSessionName= ($this->session->userdata['codeKeyData']['codeKeyValue']);
        }

        if ($projectSessionName !="") {
            $designation = ($this->session->userdata[$projectSessionName]['designation']);
            $des=explode(',',$designation);
            $des = array_map('trim', $des);
           
            if (in_array('owner', $des) || in_array('senior_manager', $des)) { 
                $now = time(); // or your date as well
                $your_date = strtotime($validTill);
                $datediff = $your_date - $now;
                $days= round($datediff / (60 * 60 * 24));

                // if($days <= 10){
                //     echo "yes";
                // }else{
                //     echo "no";
                // }

                $currentDay=date('Y-m-d');//current day
                if($validTill < $currentDay){
                    echo "yes";
                }else{
                    echo "no";
                }
            }
        } 
    }
}