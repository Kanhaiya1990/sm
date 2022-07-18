<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NonAllocationBillsController extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('NonAllocationBillModel');
        $this->load->library('session');
        date_default_timezone_set('Asia/Kolkata');
        ini_set('memory_limit', '-1');

        if(isset($this->session->userdata['codeKeyData'])) {
			$this->projectSessionName= $this->session->userdata['codeKeyData']['codeKeyValue'];
		}else{
			$this->load->view('LoginView');
		}
    }

    public function index()
    {
    	$data['bills']=$this->NonAllocationBillModel->getdataTagged('bills');
        // print_r($data['bills']);
        // exit;
    	$this->load->view('TaggedBillView',$data);
    }
    
    public function Add()
    {
    	$data['employee']=$this->NonAllocationBillModel->getdata('employee');
    	$data['bills']=$this->NonAllocationBillModel->getBillsTB('bills');
        
    	$this->load->view('AddTaggedBillView',$data);
    }

    public function loadTaggedBill($id) 
    {
    	$data['bills']=$this->NonAllocationBillModel->loadBillsDetails('bills', $id);
    	$this->load->view('NextTaggedBillView',$data);
    }

    public function Cash($id)
    {
        $data['bills']=$this->NonAllocationBillModel->loadBillsDetails('bills', $id);
        $this->load->view('CashView',$data);
    }

    public function Cashupdate() {
        $id = $this->input->post('id');
        $cashAmt = $this->input->post('cashAmt');
        $data['bills']=$this->NonAllocationBillModel->loadBillsDetails('bills', $id);
        $pendingAmt= $data['bills'][0]['pendingAmt'];
        $pamt=$pendingAmt-$cashAmt;
        $receivedAmt=$data['bills'][0]['receivedAmt']+ $cashAmt;
            if($pendingAmt > $cashAmt){
                $data = array
                ('pendingAmt' => $pamt,
                 'receivedAmt' =>  $receivedAmt        
                 );  
              
                $result = $this->NonAllocationBillModel->update('bills',$data, $id);
                if($result==1){
                        return redirect('NonAllocationBillsController/loadTaggedBill/'.$id);
                } else {
                    echo "Fail";
                }
            }else{
                 echo "Sorry!.. Cash amount greater than pending amount.";
            }
    }

    public function cashDebitUpdate($id,$emp){
        $emp=urldecode($emp);
        $data['bills']=$this->NonAllocationBillModel->loadBillsDetails('bills', $id);
        $pendingAmt= $data['bills'][0]['pendingAmt'];
    }

    public function srDebitUpdate($id,$emp){
        $emp=urldecode($emp);
        $data['bills']=$this->NonAllocationBillModel->loadBillsDetails('bills', $id);
        $pendingAmt= $data['bills'][0]['pendingAmt'];
    }

    public function CashDiscountupdate() {
        $id = $this->input->post('id');
        $cashAmt = $this->input->post('cashAmt');
        $data['bills']=$this->NonAllocationBillModel->loadBillsDetails('bills', $id);
        $pendingAmt= $data['bills'][0]['pendingAmt'];
        $pamt=$pendingAmt-$cashAmt;
        $cashDiscount=$data['bills'][0]['cashDiscount']+ $cashAmt;
        $data = array
            ('cashDiscount' => $cashDiscount,
            'pendingAmt' => $pamt      
        );  

        $result = $this->NonAllocationBillModel->update('bills',$data, $id);
        if($result==1){
             return redirect('NonAllocationBillsController/loadTaggedBill/'.$id);
          }else{
             echo "Fail";
        }

    }

    public function Cheque($id)
    {
        $data['bills']=$this->NonAllocationBillModel->loadBillsDetails('bills', $id);
        $this->load->view('ChequeView',$data);
    }

    public function SrFSR($id)
    {
        $data['bills']=$this->NonAllocationBillModel->loadBillsDetails('bills', $id);
        $data['billsdetails']=$this->NonAllocationBillModel->billDetails('billsdetails', $id);
        $data['msg'] = "";
        $this->load->view('SrFSRview',$data);
    }

    public function Debit($id)
    {
        $data['employee']=$this->NonAllocationBillModel->getdata('employee');
        $data['bills']=$this->NonAllocationBillModel->loadBillsDetails('bills', $id);
        $this->load->view('DebitView',$data);
    }

    public function CashDiscount($id)
    {
        $data['bills']=$this->NonAllocationBillModel->loadBillsDetails('bills', $id);
        $this->load->view('CashDiscountView',$data);
    }

    //
    public function loadBills($billNo){
        // $billNo=urldecode($billNo);
        // echo $billNo;
        // exit;
        $data['bills']=$this->NonAllocationBillModel->loadByBillNo('bills',$billNo);
        echo json_encode($data);
    }


    public function SRupdate() {
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
                $data['bills']=$this->NonAllocationBillModel->loadBillsDetails('bills', $id[$i]);
                $data['billsdetails']=$this->NonAllocationBillModel->loadBillDetailsID('billsdetails', $id[$i]);
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
                    $result = $this->NonAllocationBillModel->update('billsdetails',$data,  $id[$i]);
                    if($result==1){
                        if($returnedQty[$i]!='' || $returnedQty[$i]!=null){
                            $resSRP=$this->NonAllocationBillModel->updateSRPamt('bills',($sellingRate[$i] * $returnedQty[$i]),$billId);
                            if($resSRP==1){
                                $resSRP=$this->NonAllocationBillModel->updateAvailQty('products',$returnedQty[$i],$name[$i]);
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
                    $data['billsdetails']=$this->NonAllocationBillModel->billDetails('billsdetails', $billId);
                }else{
                    $data['billsdetails']=$this->NonAllocationBillModel->billDetails('billsdetails', $billId);
                    $data['msg']="Sale Return Quantity can not be greater than Billed Quantity";
                } 
            }
        }
        return redirect('NonAllocationBillsController/loadTaggedBill/'.$billId);
    }

    //
    public function update(){
        $billNo=$this->input->post('billNo');
        $billNo=explode(":",$billNo);
        $billNo=$billNo[0];
        // echo $billNo;
        // exit;
        $amt=$this->input->post('amt');
        $emp=$this->input->post('employee');
        $category=$this->input->post('category');
        $accounting=$this->input->post('accounting');
        $remark=$this->input->post('remark');

        $data['emp']=$this->NonAllocationBillModel->getEmployee($emp);
        $data['bill']=$this->NonAllocationBillModel->loadByBillNo('bills',$billNo);

        $pendingAmt=0;
        if(($category=='Office Sales') || ($category=='Office Adjustment' && $accounting=='credit')){
            $pendingAmt=$amt;
        }else if($category=='Office Adjustment' && $accounting=='cash'){
            $pendingAmt=0;
        }
        
        if(!empty($data['bill'])){  //if billID present
            $empId=$data['emp'][0]['id'];
            $billId=$data['bill'][0]['id'];
            //update BillType Status & pending Amt
            $billData = array
            ('billType' => 'taggedbill',
                'pendingAmt' => $pendingAmt                       
            ); 
            $res=$this->NonAllocationBillModel->updateBillType('bills',$billData,$billNo); 
            if(!$res==0){ 
                //insert Entry into Allocations table
                $dataAllocations = array
                ('date' => date('Y-m-d H:i:sa'),
                    'fieldStaffCode1' => $empId                        
                ); 
                $result=$this->NonAllocationBillModel->insert('allocations',$dataAllocations); 
                if(!$result==0){
                    //insert Entry into AllocationsBills table                
                    $lastId=$this->db->insert_id();
                    $dataAllocationBills=array('billId' => $billId,
                        'allocationId' => $lastId,
                        'status' => '1'
                    );
                    $result1=$this->NonAllocationBillModel->insert('allocationsbills',$dataAllocationBills); 
                    if(!$result1==0){
                        // echo "success oc";
                    }else{
                        echo "fail";
                    }
                } else {
                    echo "Fail";
                }
                 echo "<script type='text/javascript'> parent.$.fn.colorbox.close(); </script>";
            } else {
                echo "Fail";
            }
        }else{
            $insertBill = array('billNo' => $billNo,
                'billType' => 'taggedbill',
                'pendingAmt' => $amt
            );
            $result=$this->NonAllocationBillModel->insert('bills',$insertBill);
            if($result>0){
                echo "<script type='text/javascript'> parent.$.fn.colorbox.close(); </script>";
            }else{
                echo 'error';
            }
        }

        $data['bills']=$this->NonAllocationBillModel->getdataTagged('bills');
        // $this->load->view('TaggedBillView',$data);
         return redirect('NonAllocationBillsController/','refresh');
    }

    public function nonCashDebitCredit(){
        $data['employee']=$this->NonAllocationBillModel->getEmloyee('employee');
        $this->load->view('nonCashDebitCreditView',$data);
    }

    public function insertNonCashEntry(){
        $userid = ($this->session->userdata[$this->projectSessionName]['id']);
        $empName=$this->input->post('empName');
        $empId=$this->input->post('empId');
        $amount=$this->input->post('amount');
        $remark=$this->input->post('remark');
        $type=$this->input->post('type');

        $designation = ($this->session->userdata[$this->projectSessionName]['designation']);
        $des=explode(',',$designation);

        $status=0;
        if ((in_array('owner', $des))) {
            $status=1;
        }

        $data=array(
            'empId'=>$empId,
            'amount'=>$amount,
            'transactionType'=>$type,
            'description'=>$remark,
            'nonCashStatus'=>1,
            'ownerApprovalStatus'=>$status,
            'createdAt'=>date('Y-m-d H:i:sa'),
            'createdBy'=>$userid
        );

        $this->NonAllocationBillModel->insert('emptransactions',$data);
        if($this->db->affected_rows()>0){
            
            $employeeDetails=$this->NonAllocationBillModel->load('employee',$empId);
            $employeeMobile=$employeeDetails[0]['mobile'];
            $employeeName=$employeeDetails[0]['name'];
            $transactionDate=date('M d, Y H:i a');
            
            $companyDetails=$this->NonAllocationBillModel->getdata('office_details');
            $officeName=$companyDetails[0]['distributorName'];
            $distributorCode=$companyDetails[0]['distributorCode'];

            $ledger=$this->NonAllocationBillModel->getEmpLedgerByEmp('emptransactions',$empId);
            $balance=0;
            if(!empty($ledger)){
                foreach($ledger as $leg){
                    if($leg['transactionType']=='cr'){
                        $balance=$balance+$leg['amount'];
                    }else if($leg['transactionType']=='dr'){
                         $balance=$balance-$leg['amount'];
                    }
                }
            }
            // echo $balance;
            // $balance=$balance+$cashAmt;
            // echo $balance;exit;
            if($balance > 0){
                $balance=number_format(abs($balance));
                $balance =($balance).' Cr';
            }else{
                $balance=number_format(abs($balance));
                $balance= ($balance).' Dr';
            }
                    
            if($type=="cr" || $type=="Cr" || $type=="CR"){
                $jsonData=array(
                    "flow_id"=>"618d05f3fd7c8c278758d435",
                    "sender"=>"SIAInc",
                    "mobiles"=>'91'.$employeeMobile,
                    "name"=>$employeeName,
                    "nature"=>"credited",
                    "amount"=>number_format($amount),
                    "distributorname"=>$officeName,
                    "dateandtime"=>date('d M, Y H:i A'),
                    "remarks"=>substr($remark, 0, 25),
                    "balance"=>$balance
                );

                $jsonData=json_encode($jsonData);
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.msg91.com/api/v5/flow/",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $jsonData,
                    CURLOPT_HTTPHEADER => array("authkey: 291106Atbm2KHoWhat5d99ec46","content-type: application/JSON"),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
            }else{
                $jsonData=array(
                    "flow_id"=>"618d05f3fd7c8c278758d435",
                    "sender"=>"SIAInc",
                    "mobiles"=>'91'.$employeeMobile,
                    "name"=>$employeeName,
                    "nature"=>"debited",
                    "amount"=>number_format($amount),
                    "distributorname"=>$officeName,
                    "dateandtime"=>date('d M, Y H:i A'),
                    "remarks"=>substr($remark, 0, 25),
                    "balance"=>$balance
                );

                $jsonData=json_encode($jsonData);
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.msg91.com/api/v5/flow/",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $jsonData,
                    CURLOPT_HTTPHEADER => array("authkey: 291106Atbm2KHoWhat5d99ec46","content-type: application/JSON"),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
            }    
            
            echo "Record Inserted";
        }else{
            echo "Record not inserted";
        }


    }
}
?>    