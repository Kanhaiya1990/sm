<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BankController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('BankModel');
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
        $data['bank']=$this->BankModel->getdata('bank');
        $this->load->view('admin/banksView',$data);
    }

    public function Add()
    {
        // $data['bank']=$this->BankModel->getdata('bank');

        $this->load->view('admin/addBankView');
    }

  public function insert()
    {
        $data = array('name' => $this->input->post('name')); 
        $result=$this->BankModel->insert('bank',$data); 
        redirect("admin/BankController");
    }

    public function load($id) 
    {
        $data['bank']=$this->BankModel->load('bank', $id);
        $this->load->view('admin/addBankView',$data);
    }

    public function update() {
        $id = $this->input->post('id');
        $data = array('name' => $this->input->post('name'));
        $result = $this->BankModel->update('bank',$data, $id);
        redirect("admin/BankController");
    }

    public function delete()
    {
        $id =$this->input->post('id');
        $data=$this->BankModel->delete('bank',$id);  
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
