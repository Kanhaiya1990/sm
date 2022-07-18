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
		$data['bills']=$this->SrModel->getdata1('bills');
		$this->load->view('BillSrView',$data);
	}

    public function deliverySlipSaleReturn()
    {
        $data['bills']=$this->SrModel->getSalesReturnDS('bills');
        $this->load->view('SalesReturnDelSlipview',$data);
    }

    public function load($id) 
    {
        $data['billsdetails']=$this->SrModel->loadBillDetails('billsdetails', $id);
        $data['msg'] = "";
        $this->load->view('cashier/SrView',$data);
    }
   
    public function update() {
        $data['msg']='';
        $id = $this->input->post('id');
        $billId = $this->input->post('billId');
        $name=$this->input->post('productName');
        $mrp = $this->input->post('mrp');
        $qty = $this->input->post('qty');
        $netAmount = $this->input->post('netAmount');
        $sellingRate = $this->input->post('selAmount');
        $returnedQty = $this->input->post('returnedQty');
        $returnAmt = $this->input->post('returnAmt');
        for ($i=0; $i < count($returnedQty); $i++) { 
            if($returnedQty[$i]!='' || $returnedQty[$i]!=null){
           
                if($returnAmt[$i]=='' || $returnAmt[$i]==null){
                    $returnAmt[$i]=0;
                }
                $RetuenAmount=$returnAmt[$i]+($sellingRate[$i] * $returnedQty[$i]);
                $data['billsdetails']=$this->SrModel->loadBillDetailsID('billsdetails', $id[$i]);
                if($data['billsdetails'][0]['returnedQty']=='' || $data['billsdetails'][0]['returnedQty']==null){
                    $oldSR=0+$returnedQty[$i];
                }else{
                    $oldSR=$data['billsdetails'][0]['returnedQty']+ $returnedQty[$i];
                }
                if($qty[$i] >= $oldSR){
                    $data = array
                            ('returnedQty' => $oldSR,
                            'returnAmt' =>  $RetuenAmount
                            ); 
                    $result = $this->SrModel->update('billsdetails',$data,  $id[$i]);
                    if($result==1){
                        if($returnedQty[$i]!='' || $returnedQty[$i]!=null){
                            $resSRP=$this->SrModel->updateSRPamt('bills',($sellingRate[$i] * $returnedQty[$i]),$billId);
                            if($resSRP==1){
                                $resSRP=$this->SrModel->updateAvailQty('products',$returnedQty[$i],$name[$i]);
                                if($resSRP==1){
                                     $data['msg']="SR qty accepted";
                                }else{
                                    echo "Fail";
                                }
                            }else{
                                echo "Fail";
                            }
                        }
                    } else {
                        echo "Fail";
                    }
                    $data['billsdetails']=$this->SrModel->loadBillDetails('billsdetails', $billId);
                }else{
                    $data['billsdetails']=$this->SrModel->loadBillDetails('billsdetails', $billId);
                    $data['msg']="Sale Return Quantity can not be greater than Billed Quantity";
                     // $data['msg']="Sorry ! for Product '".$name[$i]."', SR Quantity is greater...";
                    // $this->load->view('SrView',$data);
                } 
            }
        }
        $this->load->view('cashier/SrView',$data);
    }

    public function USRBillsDetails()
    {
        $data['bills']=$this->SrModel->getdataBillsDetails('billsdetails');
        $this->load->view('USRBillsDetailsView',$data);
    }

    public function USRItemDetails()
    {
        $data['billsdetails']=$this->SrModel->getdataBillsDetails('billsdetails');
        $this->load->view('USRItemDetailsView',$data);
    }

    public function loadUSRItemDetails($id) 
    {
        $data['billsdetails']=$this->SrModel->loadBillDetails('billsdetails', $id);
        $data['employee']=$this->SrModel->EmployeeName('employee');
        // print_r($data['employee']);
        // exit();
        $this->load->view('USRItemDetailsView',$data);
    }

    public function updateUSRItem() {

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

        if($qty > $oldSR){
            $data = array
                    ('returnedQty' => $oldSR,
                    'returnAmt' =>  $RetuenAmount
                    ); 

            $result = $this->SrModel->update('billsdetails',$data, $id);
            if($result==1){
                 ?>
                <script type="text/javascript">
                  parent.showSuccess("Record Updated successfully");
                  parent.$.colorbox.close();
                </script>
                <?php
                //return redirect("SrController");
            } else {
                echo "Fail";
            }
        }else{
            $data['billsdetails']=$this->SrModel->loadBillDetails('billsdetails', $id);
            $data['msg']="Sorry ! Return quetity can not be ruturn that quenity...";
            $this->load->view('SrView',$data);
        } 
    }
    
}
