
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CashAndChequeController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        // $this->output->enable_profiler(TRUE);
        $this->load->model('CashAndChequeModel');
        $this->load->library('session');
        date_default_timezone_set('Asia/Kolkata');
        ini_set('memory_limit', '-1');

        if(isset($this->session->userdata['codeKeyData'])) {
			$this->projectSessionName= $this->session->userdata['codeKeyData']['codeKeyValue'];
		}else{
			$this->load->view('LoginView');
		}
    }

    public function openAllocations(){
        $data['allocations']=$this->CashAndChequeModel->getAllocations('allocations');
        $this->load->view('openAllocationForAllView',$data);
    }
    public function closedAllocations(){
        $data['allocations']=$this->CashAndChequeModel->getClosedAllocations('allocations');
        $this->load->view('cashier/closedAllocationsView',$data);
    }


    public function index()
    {
        $this->session->set_flashdata('message', '');
        $data['message']=$this->session->flashdata('message');
        
        $data['bills']=$this->CashAndChequeModel->getdataBills('bills');
        $data['banks']=$this->CashAndChequeModel->getdata('bank');
        $data['company']=$this->CashAndChequeModel->getdata('company');
        $this->load->view('cashier/NewEntryView',$data);
    }
    public function DesktopBill()
    {
        $date =  $this->input->post('dt');
        $response=array();
        $DesktopBillResponse=array();
          if($date!="") {
            $data['company']=$this->CashAndChequeModel->getdata('company');
            
            $data['bills']=$this->CashAndChequeModel->getdataRetailerDate('bills',$date);
            foreach($data['bills'] as $row){
                $response[] = $row;
            }

            $this->session->set_userdata("DesktopBill",$response);
            $this->load->view('cashier/DesktopBillView',$data);
        }else if ($date=="") {
            $data['company']=$this->CashAndChequeModel->getdata('company');
            $data['bills']=$this->CashAndChequeModel->getdataRetailer('bills');
            $this->load->view('cashier/DesktopBillView',$data);
         }

    }
    public function BounceCheques()
    {       
        $data['billpayments']=$this->CashAndChequeModel->getdataBounce('billpayments');
        for($j=0;$j<count($data['billpayments']);$j++) {
             $billNo = "";
            $billId= $data['billpayments'][$j]['billId']; // get id in billpayment
            $id = explode(',', $billId);
            $data['bills']=$this->CashAndChequeModel->loadBillNumber('bills',$id);
            for($i=0;$i<count($data['bills']);$i++) {

               $billNo = $data['bills'][$i]['billNo'].", ".$billNo;
               if($i==0) {
                $retailer = $this->CashAndChequeModel->loadRetailsers('retailer',$data['bills'][$i]['retailerCode']);
                $data['billpayments'][$j]['Name'] = $retailer[0]['name'];
               }
            }            
            $data['billpayments'][$j]['billNo'] = $billNo;
        }
        $this->load->view('cashier/BouncedCheques',$data);
    }
     public function CheckRegister()
     {
        $data['billpayments']=$this->CashAndChequeModel->getdata('billpayments');
        for($j=0;$j<count($data['billpayments']);$j++) {
             $billNo = "";
            $billId= $data['billpayments'][$j]['billId']; // get id in billpayment
            $id = explode(',', $billId);
            $data['bills']=$this->CashAndChequeModel->loadBillNumber('bills',$id);
            for($i=0;$i<count($data['bills']);$i++) {

               $billNo = $data['bills'][$i]['billNo'].", ".$billNo;
               if($i==0) {
                $retailer = $this->CashAndChequeModel->loadRetailsers('retailer',$data['bills'][$i]['retailerCode']);
                $data['billpayments'][$j]['Name'] = $retailer[0]['name'];
               }
            }            
            $data['billpayments'][$j]['billNo'] = $billNo;
        }
        $this->load->view('cashier/CheckRegisterView',$data);
    }
    public function ChequeReconcilation()
    {   
         $data['billpayments']=$this->CashAndChequeModel->getdataBanked('billpayments');
        for($j=0;$j<count($data['billpayments']);$j++) {
             $billNo = "";
            $billId= $data['billpayments'][$j]['billId']; // get id in billpayment
            $id = explode(',', $billId);
            $data['bills']=$this->CashAndChequeModel->loadBillNumber('bills',$id);
            for($i=0;$i<count($data['bills']);$i++) {

               $billNo = $data['bills'][$i]['billNo'].", ".$billNo;
               if($i==0) {
                $retailer = $this->CashAndChequeModel->loadRetailsers('retailer',$data['bills'][$i]['retailerCode']);
                $data['billpayments'][$j]['Name'] = $retailer[0]['name'];
               }
            }            
            $data['billpayments'][$j]['billNo'] = $billNo;
        }
        $this->load->view('cashier/ChequeReconcilationView',$data);
    } 

    public function updateStatus() {
        $id = $this->input->post('id');
        $data = array('chequeStatus' => $this->input->post('chequeStatus'),
            'chequeStatusDate' => date("y-m-d")
        );  
        $result = $this->CashAndChequeModel->update('billpayments',$data, $id);
        if($result==1){
                // $data['billpayments']=$this->CashAndChequeModel->getdataStatus('billpayments');
                // $this->load->view('ChequeReconcilationView',$data);
                //echo "Success";?>
                
                <a id="changeStatus" >
                    <button onclick="changeStatus('<?php echo $id;?>')" class="btn btn-primary waves-effect" data-type="basic"><?php echo $data['chequeStatus']; ?>
                </button>
            </a>
            <p id="result_data"></p>
            

        <?php } else {
            echo "Fail";
        }
    }
    public function updateStatusBounceCheques() {
        $id = $this->input->post('id');
        $data = array('chequeStatus' => $this->input->post('chequeStatus'),
            'chequeStatusDate' => date("y-m-d")
        );  
        $result = $this->CashAndChequeModel->update('billpayments',$data, $id);
        if($result==1){
                // $data['billpayments']=$this->CashAndChequeModel->getdataStatus('billpayments');
                // $this->load->view('ChequeReconcilationView',$data);
                //echo "Success";?>
                
                <a id="changeStatus" >
                    <button onclick="changeStatus('<?php echo $id;?>')" class="btn btn-primary waves-effect" data-type="basic"><?php echo $data['chequeStatus']; ?>
                </button>
            </a>
            <p id="result_data"></p>
            

        <?php } else {
            echo "Fail";
        }
    }
    public function updateStatusDesktopBill() {
        $id = $this->input->post('id');
        $data = array('chequeStatus' => $this->input->post('chequeStatus'),
            'chequeStatusDate' => date("y-m-d")
        );  
        $result = $this->CashAndChequeModel->update('billpayments',$data, $id);
        if($result==1){
            ?>           
            <a id="changeStatus" >
                <button onclick="changeStatus('<?php echo $id;?>')" class="btn btn-primary waves-effect" data-type="basic"><?php echo $data['chequeStatus']; ?>
            </button>
        </a>
        <p id="result_data"></p>
        

    <?php } else {
        echo "Fail";
    }
}
public function ChequeDepositSlip(){
   $data['billpayments']=$this->CashAndChequeModel->getdataStatus('billpayments');
   $this->load->view('cashier/ChequeDepositSlipView',$data);
}
public function insert()
{   
    
    $total=0;
    $billId=$this->input->post('billId');
    $paidAmount= $this->input->post('paidAmount');
    $billAmount=$this->input->post('billAmount');

       // $chequeAmt= $this->input->post('chequeBank');
    foreach (explode(',',$billAmount) as $data) 
    {  
       $total=$total+$data; 
   }
   $amt = floatval($total);

   $total= number_format($amt, 2, '.', '');
   $balanceAmount=$total-$paidAmount;
   $paidAmount = floatval($paidAmount);
   
        // if($total>$paidAmount){
   for($i=0;$i<count($billId);$i++) {
       $data = array(
        'billId' => rtrim($billId[$i],','),
        'chequeNo' => $this->input->post('chequeNo'),
        'chequeDate' => $this->input->post('chequeDate'),
        'billAmount' =>  $total,
        'paidAmount' =>  $this->input->post('paidAmount'),
        'balanceAmount' =>  $balanceAmount,
        'chequeBank' =>  $this->input->post('chequeBank'),
        'paymentMode' => "check",
        'date' =>date("y-m-d"),
        'chequeStatus'=>   "received",
        'chequeStatusDate' =>"",
        'allocationId'=>"0"
    );     
            // print_r($data);
            // exit();  
       $result=$this->CashAndChequeModel->insert('billpayments',$data); 
   }       
   if(!$result==0){    

                 // For Company. 
       $cmp=$this->CashAndChequeModel->name_exists('company',$this->input->post('company'));
       if(empty($cmp)){
        $data1 = array(
            'name' => $this->input->post('company')
        );     
        $result1=$this->CashAndChequeModel->insert('company',$data1); 
        if(!$result1==0){  
                        // echo "inserted";
        }else{
            echo "error ";
        }   
    }       

                // For Bank.
    $bnk=$this->CashAndChequeModel->name_exists('bank',$this->input->post('chequeBank'));
    if(empty($bnk)){
        $data2 = array(
            'name' => $this->input->post('chequeBank')
        ); 

        $result2=$this->CashAndChequeModel->insert('bank',$data2); 
        if(!$result2==0){  
                        // echo "inserted";
        }else{
            echo "error ";
        }    
    }
    return redirect('cashier/CashAndChequeController');
}   
else{
    echo "Registration Fails...!";
}
        
}
public function updateStatusCleared($id, $status) {
    $data = array('chequeStatus' => "cleared",
        'chequeStatusDate' => date("y-m-d"));
    $result = $this->CashAndChequeModel->update('billpayments',$data, $id);
    if($result==1)
    {
     redirect("cashier/CashAndChequeController/ChequeReconcilation");
 } else {
    echo "Fail";
}
} 
public function updateStatusReturned($id, $status) {
    $data = array('chequeStatus' => "returned",
        'chequeStatusDate' => date("y-m-d"));
    $result = $this->CashAndChequeModel->update('billpayments',$data, $id);
    if($result==1)
    {
        redirect("cashier/CashAndChequeController/BounceCheques");
    } else {
        echo "Fail";
    }
} 
public function updateStatusBounced() {
    $id = $this->input->post('id');
    $data = array('chequeStatus' => "bounced",
        'statusBouncedReason' => $this->input->post('statusBouncedReason'),
        'chequeStatusDate' => date("y-m-d"));
    $result = $this->CashAndChequeModel->update('billpayments',$data, $id);
    if($result==1)
        {?>
            <a id="changeStatus" >
                <button onclick="changeStatus('<?php echo $id;?>')" class="btn btn-primary waves-effect" data-type="basic">Bounced
                </button>
            </a>
            <p id="result_data"></p>
            <?php 
            //redirect("CashAndChequeController/ChequeReconcilation");
        } else {
            echo "Fail";
        }
    } 
    public function DesktopBillUpdate() {
    
        $oldSession =  $this->session->userdata('DesktopBill');
        foreach($oldSession as $items){
            $data=array('id'=> $items['id'],
            'date'=> $items['date'],
            'billId'=> $items['billId'],
            'paidAmount'=> $items['paidAmount'],
            'billAmount'=> $items['billAmount'],
            'balanceAmount'=> $items['balanceAmount'],
            'paymentMode'=> $items['paymentMode'],
            'chequeNo'=> $items['chequeNo'],
            'chequeBank'=> $items['chequeBank'],
            'chequeDate'=> $items['chequeDate'],
            'paymentMode'=> $items['paymentMode'],
            'chequeStatus'=> $items['chequeStatus'],
            'allocationId'=> $items['allocationId'],
            'chequeStatus'=> "banked",
            'penalty'=> $items['penalty'],
            'chequeStatusDate' => date("y-m-d"));
              $result =$this->CashAndChequeModel->update('billpayments',$data,$items['id']); 
            if(!$result ==0){
                redirect("cashier/CashAndChequeController/DesktopBill");
            } else {
                echo "fails";
            }
        }
    } 
    public function FeatchPanalty()
    {
        $data=$this->CashAndChequeModel->getdataPenalty('penalty');
        echo json_encode($data);
    }
    public function EmailSend()
    {
        $senderId = $this->session->userdata[$this->projectSessionName]['id'];
        $data['employee'] = $this->CashAndChequeModel->load('employee', $senderId);
        $senderEmail=$data['employee'][0]['email'];


        $receiverEmail= "priyankasonawane8071@gmail.com";
        $name="priyanka";

        $subject = "Cheque Destop Slip For ".$name;
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: <'.$senderEmail.'>' . "\r\n";
  
        $oldSession =  $this->session->userdata('DesktopBill');
         $output="";

        $data['billpayments']=$this->session->userdata('DesktopBill');
        for($j=0;$j<count($data['billpayments']);$j++) {
             $billNo = "";
            $billId= $data['billpayments'][$j]['billId']; // get id in billpayment
            $id = explode(',', $billId);
            $data['bills']=$this->CashAndChequeModel->loadBillNumber('bills',$id);
             $no=0;
            for($i=0;$i<count($data['bills']);$i++) {
                 $no=1+$i;
               $billNo = $data['bills'][$i]['billNo'].", ".$billNo; 
               if($i==0) 
               {
                $retailer = $this->CashAndChequeModel->loadRetailsers('retailer',$data['bills'][$i]['retailerCode']);
                $data['billpayments'][$j]['Name'] = $retailer[0]['name'];
                 $id =$data['billpayments'][$j]['id'];
                $name =$data['billpayments'][$j]['Name'];
                $chequeNo = $data['billpayments'][$j]['chequeNo'];
                $chequeBank = $data['billpayments'][$j]['chequeBank'];
                $chequeDate = $data['billpayments'][$j]['chequeDate'];
                $no=0;        

                $output .='
                <tr>
                    <td>'.$id.'</td>
                    <td>'.$name.'</td>
                    <td>'.$chequeNo.'</td>
                    <td>'.$chequeBank.'</td>
                    <td>'.$chequeDate.'</td>                    
                </tr>';
               }
            }            
            $data['billpayments'][$j]['billNo'] = $billNo;
        }

        $message = "<div>
        <h2 style='align:center;'>Smart Distributor</h2>
        <h3>Cheque Destop Slip</h3>
        <html>
        <head></head>
        <body>
        <table style='align:center;' border='1px'>
            <thead  style='align:center;background-color: #44BDA9;'>
                <tr>
                    <th>Sr.No</th>
                    <th>Retailer Name</th>
                    <th>Cheque No</th>
                    <th>Cheque Bank</th>
                    <th>Cheque Date</th>
                </tr>
            </thead>
            <tbody  style='align:center;background-color: whitesmoke;'>
                ".$output."
            </tbody>
        <table>
        </body>
        </html>
        </div>";
            // print_r($message);
            // exit();    
        mail($receiverEmail, $subject, $message, $headers); 
         redirect("cashier/ CashAndChequeController/DesktopBill");
    }
}
        