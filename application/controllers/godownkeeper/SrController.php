<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SrController extends CI_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('SrModel');
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
        $data['allocations']=$this->SrModel->getAllAllocations('allocations');
        $this->load->view('Godownkeeper/AllocationWiseSRView',$data);
    }

     public function usr()
    {
        $data['allocations']=$this->SrModel->getAllUsr('allocations');
        $this->load->view('Godownkeeper/AllocationWiseUSRView',$data);
    }

      public function AllocationWiseSR($id){
            $data['srBills']=$this->SrModel->loadSrDetail('billsdetails',$id);
            $data['fsrBills']=$this->SrModel->loadFsrDetail('billsdetails',$id);
            $this->load->view('Godownkeeper/BillSrView',$data);
      }

      public function AllocationWiseUSR($id){
            $data['srBills']=$this->SrModel->loadUSrDetail('billsdetails',$id);
            $data['fsrBills']=$this->SrModel->loadUfsrDetail('billsdetails',$id);
           
            $this->load->view('Godownkeeper/BillUsrView',$data);
      }


    public function load($id) 
    {
        $data['billsdetails']=$this->SrModel->loadBillDetails('billsdetails', $id);
        $data['msg'] = "";
        $this->load->view('Godownkeeper/SrView',$data);
    }
      public function update() {
        $id = $this->input->post('id');
        $mrp = $this->input->post('mrp');
        $qty = $this->input->post('qty');
        $netAmount = $this->input->post('netAmount');
        $returnedQty = $this->input->post('returnedQty');
        $Quentity=$qty-$returnedQty;
        $RetuenAmount=$mrp* $returnedQty;
        $NetAmount =$mrp* $Quentity;
        $data['billsdetails']=$this->SrModel->loadBillDetails('billsdetails', $id);
        $oldSR=$data['billsdetails'][0]['returnedQty']+ $this->input->post('returnedQty');
        if($qty > $returnedQty){
            $data = array
                    ('returnedQty' => $oldSR,
                    'returnAmt' =>  $RetuenAmount
                    ); 

            $result = $this->SrModel->update('billsdetails',$data, $id);
            if($result==1){
                return redirect("Godownkeeper/SrController");
            } else {
                echo "Fail";
            }
        }else{
            $data['billsdetails']=$this->SrModel->loadBillDetails('billsdetails', $id);
            $data['msg']="Sorry ! Return quetity can not be ruturn that quenity...";
            $this->load->view('Godownkeeper/SrView',$data);
        } 
    }
    public function USRItemDetails()
    {
        $data['billsdetails']=$this->SrModel->getdata('billsdetails');
        $this->load->view('Godownkeeper/USRItemDetailsView',$data);
    }
}
