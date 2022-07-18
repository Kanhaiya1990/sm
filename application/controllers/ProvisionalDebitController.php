<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProvisionalDebitController extends CI_Controller {
	
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
        $this->load->view('AllocationWiseSRView',$data);
    }

    public function changeLostBillStatus(){
        $billId=trim($this->input->post('billId'));
        $updateData=array('isLostBill'=>1);
        $this->SrModel->update('bills',$updateData,$billId);
    }

    public function changeLostChequeStatus(){
        $billPaymentId=trim($this->input->post('billPaymentId'));
        $updateData=array('isLostStatus'=>0);
        $this->SrModel->update('billpayments',$updateData,$billPaymentId);
    }

    public function lostBills()
    {
        $data['lost']=$this->SrModel->loadLostBills('bills');
        $data['emp']=$this->SrModel->getdata('employee');
        $this->load->view('Manager/LostBillsView',$data);
    }

    public function lostCheques()
    {
        $data['lost']=$this->SrModel->lostCheque('bills');
        // print_r($data['lost']);exit;
        $data['emp']=$this->SrModel->getdata('employee');
        $this->load->view('Manager/lostChequeView',$data);
    }

    public function unclearedNeft()
    {
        $data['lost']=$this->SrModel->lostNeft('bills');
        // print_r($data['lost']);exit;
        $data['emp']=$this->SrModel->getdata('employee');
        $this->load->view('Manager/lostNeftView',$data);
    }

    public function resendBills(){
        $data['resend']=$this->SrModel->loadResendBills('bills');
        $data['emp']=$this->SrModel->getdata('employee');
        $this->load->view('Manager/ResendBillsView',$data);
    }

    public function billusr()
    {
        $data['srBills']=$this->SrModel->loadBillUSrDetail('billsdetails');
        $data['fsrBills']=$this->SrModel->loadBillUfsrDetail('billsdetails');
        $this->load->view('BillwiseUsrView',$data);
    }

    public function updatedLostBills(){
        $lost=$this->SrModel->lostCheque('bills');
        // print_r($data['lost']);exit;
        $emp=$this->SrModel->getdata('employee');
       
        $no=0;
            if(!empty($lost)){
            foreach($lost as $data){

              $retailerCode=$this->SrModel->loadRetailer($data['retailerCode']);

                $no++;
                 $diff=strtotime(date('Y-m-d'))-strtotime($data['entryDate']);
        ?>
        <?php if($data['isAllocated']==1){ ?>
                 <tr style="background-color: #dcd6d5">
            <?php }else{ ?>
                 <tr>
            <?php } ?>
            <td><?php echo $no;?></td>
            <td><?php echo $data['billNo'];?></td>
             <td><?php $dt=date_create($data['date']);echo date_format($dt,'d-M-Y');?></td>
            <td><?php echo $data['retailerName'];?></td>
           <td><?php echo $data['netAmount'];?></td>
           <td><?php echo $data['pendingAmt'];?></td>
            <td><?php echo $data['salesman'];?></td>
            <td><?php echo $data['ename'];?></td>
            <td><?php echo $data['rname'];?></td>
             <td><?php
             $date=date_create($data['entryDate']);
             echo date_format($date,"d-M-Y");
              ?></td>
            <td><?php echo abs(round($diff/86400));?></td>
           <td>
            <?php if($data['isAllocated']!=1){ ?>
            <a id="prDetails" href="javascript:void()" data-id="<?php echo $data['id']; ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>" data-gst="<?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?>" data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-toggle="modal" data-target="#processModal"><button class="btn btn-xs btn-primary m-t-15 waves-effect"><i class="material-icons">edit</i>Process</button></a>
            
            <button onclick="lostBillStatus(this,'<?php echo $data["id"]?>');removeMe(this);" class="btn btn-xs btn-primary m-t-15 waves-effect"> <i class="material-icons">check</i></button>
         
         
          <?php }else{
          
            $allocations=$this->SrModel->getAllocationDetailsByBill('bills',$data['id']);

                      $officeAllocations=$this->SrModel->getOfficeAllocationDetailsByBill('bills',$data['id']);
                      
                     if(!empty($allocations)){
                      echo "<p style='color:blue'>Allocated in : ".$allocations[0]['allocationCode']."</p>";
                        }else if(!empty($officeAllocations)){
                           echo "<p style='color:blue'>Allocated in : ".$officeAllocations[0]['allocationCode']."</p>";
                        }
                  }
                ?>
          </td>
        </tr>
        <?php 
            }
           } 
      
    }

}
?>