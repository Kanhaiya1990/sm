<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CompanyController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('CompanyModel');
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
    public function index()
    {
        $data['company']=$this->CompanyModel->getdata('company');
        $this->load->view('admin/CompanyView',$data);
    }
    public function Add()
    {
        $data['companies']=$this->CompanyModel->getdata('company');

        $this->load->view('admin/AddCompanyView',$data);
    }

    public function officeDetails(){
        $data['bankDetails']=$this->CompanyModel->getdata('office_details');
        $data['empCode']=$this->CompanyModel->getdata('emp_code');
        $data['company']=$this->CompanyModel->getdata('company');
        $this->load->view('admin/officeDetailsView',$data);
    }


    

    public function addOfficeDetailsKia(){
        $id=$this->input->post('id');
        // $distributorName=$this->input->post('distributorName');
        $bankName=$this->input->post('bankName');
        // $address=$this->input->post('address');
        $branch=$this->input->post('branch');
        $accountNo=$this->input->post('accountNo');
        // $panNo=$this->input->post('panNo');

        $updateData=array(
            // 'distributorName'=>$distributorName,
            'bankName'=>$bankName,
            // 'address'=>$address,
            'branch'=>$branch,
            'accountNumber'=>$accountNo,
            // 'panNumber'=>$panNo
        );
       $this->CompanyModel->update('office_details',$updateData,$id); 
    //    echo '<script type="text/javascript">toastr.success("Have Fun")</script>';
       
    }

    public function addContactDetails(){
        $id=$this->input->post('id');
        $smsNumber=$this->input->post('smsNumber');
        $email=$this->input->post('email');

        $adminMobile=$this->input->post('adminMobile');
        $adminEmail=$this->input->post('adminEmail');

        $updateData=array(
            'adminMobile'=>$adminMobile,
            'adminEmail'=>$adminEmail,
            'ownerMobile'=>$smsNumber,
            'ownerEmail'=>$email
        );
       $this->CompanyModel->update('office_details',$updateData,$id); 
    }

  public function insert()
    {
        $data = array(
            'name' => $this->input->post('name'),
            'reportSW' => $this->input->post('swReport')            
        ); 
        $result=$this->CompanyModel->insert('company',$data); 
        if(!$result==0){                
             redirect("admin/CompanyController");
        }   
        else{
            redirect("admin/CompanyController");
        }
    }
    public function load($id) 
    {
        $data['company']=$this->CompanyModel->load('company', $id);
        $this->load->view('admin/AddCompanyView',$data);
    }
    public function update() {
        $id = $this->input->post('id');
        $data = array(
            'name' => $this->input->post('name'),
            'reportSW' => $this->input->post('swReport')
        );  
        $result = $this->CompanyModel->update('company',$data, $id);
        if($result==1){
            redirect("admin/CompanyController");
        } else {
            redirect("admin/CompanyController");
        }
    }
    public function delete()
    {
        $id =$this->input->post('id');
        $data=$this->CompanyModel->delete('company',$id);  
        if ($data==1)
        {
            echo "Your record has been deleted!";                
        }
        else
        {
            echo "Deleted Fail..";
        }
    }  
}
