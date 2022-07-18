<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DeliverySlipController extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('DeliverySlipModel');
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

    public function index(){
        $id=0;
        $nextId=$this->DeliverySlipModel->getNextId('bills');
        if($nextId[0]['id']=="") {
            $nextId[0]['id'] = "1";
        } else {
            $id = $nextId[0]['id'] + 1;
            $nextId[0]['id'] = $id;
        }
        $data['nextId'] =$nextId[0]['id'];

    	$data['product']=$this->DeliverySlipModel->getdata('products');
        $data['retailer']=$this->DeliverySlipModel->getdata('retailer');
        $data['emp']=$this->DeliverySlipModel->getdata('employee');
        $this->load->view('NewTransactionView',$data);
    }

    public function loadRetailerBills($name){
        $name=urldecode($name);
        $data['retailer']=$this->DeliverySlipModel->getRetailerCode($name);
        $code=$data['retailer'][0]['code'];
        $data['bills']=$this->DeliverySlipModel->retailerBillsByCode('bills',$code);
        $this->load->view('RetailerwiseBillsView',$data);
    }

    public function billwiseDetail($billNo){
         $data['bills']=$this->DeliverySlipModel->loadBills('bills',$billNo);
         $id=$data['bills'][0]['id'];
         $data['billDetails']=$this->DeliverySlipModel->loadByBillId('billsdetails',$id);
         // print_r($data['billDetails']);
         // exit;
         $this->load->view('BillDetailsView',$data);
    }
        
        


    public function RetailerwiseDetails()
    {
         $data['bills']=$this->DeliverySlipModel->retailerBills('bills');
        
        // $data['bills']=$this->DeliverySlipModel->getBillData('bills');
         $this->load->view('RetailerwiseDetailView',$data);
    } 

    public function BillwiseDetails()
    {
        $data['retailer']=$this->DeliverySlipModel->RetailerName('retailer');
        $data['bills']=$this->DeliverySlipModel->getBillData('bills');
        $this->load->view('BillwiseDetailsView',$data);
    }
    
    public function OutstandingBill()
    {
        $data['bills']=$this->DeliverySlipModel->getdataOutstandingBill('bills');
        $this->load->view('cashier/OutstandingBillView',$data);
    }

    public function load($id) 
    {
        $data['product']=$this->DeliverySlipModel->load('products', $id);
        echo json_encode($data);
    }

    public function getBillData(){
        // $d=array();
        $d=$this->input->get('data');
        $data = json_decode($d,true);

        if(!empty($data)){
            $total=0;
            $billNo='';
            $retailer='';
           
            $salesman='';
            foreach ($data as $key) {
                $billNo=$key['billNo'];
                $retailer=$key['retailer'];
                $total=$total+$key['amt'];
            }
           
            $code=$this->DeliverySlipModel->getRetailerCode($retailer);//get retailer code and hierarchy
            $retailerCode=$code[0]['code'];
            $hierarchy=$code[0]['hierarchy'];

            $data1 = array
                ('billNo' => $billNo,
                'date' => date('Y-m-d'),
                'retailerHeirarchy' => $hierarchy,
                'retailerCode'=>$retailerCode,
                'netAmount' =>$total,
                'billType' =>'deliveryslip',
                'pendingAmt' =>$total               
            ); 
            //insert data in Bills table 
            $result=$this->DeliverySlipModel->insert('bills',$data1); 
            if(!$result==0){  
                $lastInsertedId=$this->db->insert_id();
                //update product quantity
                foreach($data as $item){
                    $qty=$item['qty'];
                    $prdName=$item['item'];
                    //update AvailableQty in products table.
                    $result1 = $this->DeliverySlipModel->updateByName('products',$prdName,$qty);
                    if($result1==1){
                        // return redirect("DeliverySlipController");
                    } else {
                        $this->session->set_flashdata('msg', 'Record Inserted Successfully...!');
                    }
                }

                //insert productwise billdetail
                foreach($data as $item){
                   
                    $prodName=$item['item'];
                    $mrp=$item['mrp'];
                    $sellingPrice=$item['rate'];
                    $qty=$item['qty'];
                    $netAmt=$item['amt'];
                    $billDetail = array
                    ('billId' => $lastInsertedId,
                    'productName' => $prodName,
                    'mrp' => $mrp,
                    'sellingRate'=>$sellingPrice,
                    'qty' =>$qty,
                    'netAmount' =>$netAmt
                     );
                     $res=$this->DeliverySlipModel->insert('billsdetails',$billDetail);
                     if($res>0){
                        $this->session->set_flashdata('msg', 'Record Inserted Successfully...!');
                     }else{
                       $this->session->set_flashdata('msg', 'Record Inserted Successfully...!');
                     } 
                }

                return redirect("DeliverySlipController");
            } else {
                $this->session->set_flashdata('msg', 'Record Inserted Successfully...!');
            }
            
        }else{
            return redirect("DeliverySlipController");
        }
    }

    public function getRetailersBills(){
        $rname=$this->input->post('name');
        
        if($rname=='' || $rname==null){
            $data['retailer']=$this->DeliverySlipModel->getdata('retailer');
            $data['bills']=$this->DeliverySlipModel->getBillData('bills');
            $this->load->view('BillwiseDetailsView',$data);
        }else{
            $code=$this->DeliverySlipModel->getRetailerCode($rname);
            $retailerCode= $code[0]['code'];

            $data['retailer']=$this->DeliverySlipModel->getdata('retailer');
            $data['bills']=$this->DeliverySlipModel->getRetailerBillData('bills',$retailerCode);
            $this->load->view('BillwiseDetailsView',$data);
        }
    }

    public function getBillWiseDetails() {
        $name= $this->input->post('retailerName');
        $code=$this->DeliverySlipModel->getRetailerCode($name);
            $retailerCode= $code[0]['code'];
        echo $name." ".$code;
        exit;

        $response=array();
        $currentBillIDs=array();
        $newCurrentBills=array();
        //set session

        if((!empty($name))){
            $data['currentBills']=$this->DeliverySlipModel->getRetailerBillData('bills',$code);  
            foreach($data['currentBills'] as $row){
                $response[] = $row;
            }
            
            ?>
            <?php

            $no=0;
            
            foreach($response as $items){
                $id=$items['id'];
                $no++;
                    
            ?>  
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $items['billNo']; ?></td>
                    <td><?php echo $items['date']; ?></td>
                    <td><?php echo $items['retailerCode']; ?></td>
                    <td><?php echo $items['netAmount']; ?></td>
                    <td><?php echo $items['receivedAmt']; ?></td>
                    <td><?php echo $items['pendingAmt']; ?></td>
                </tr>
            <?php
                
                }
                
            ?>  
            <?php
        }
    }

    public function Products(){
        $data['prod']=$this->DeliverySlipModel->getdata('products');
        $this->load->view('ProductsStockView',$data);
    }

    public function SalesmanWiseProducts(){
        // $data['prod']=$this->DeliverySlipModel->getdata('products');
        $data['prod']=$this->DeliverySlipModel->ProductName();
        $data['emp']=$this->DeliverySlipModel->getdata('employee');
        $this->load->view('SalesmansProductStockView',$data);
    }


    public function printPDF()
    {
        $d=$this->input->get('data');
        $data = json_decode($d,true);
        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Delivery Slip');
        $pdf->SetHeaderMargin(30);
        $pdf->SetTopMargin(20);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(true);
        $pdf->SetAuthor('Author');
        $pdf->SetDisplayMode('real', 'default');
        $pdf->Write(5, 'CodeIgniter TCPDF Integration');
        // $html='<p>hi</p>';
        // $pdf->writeHTML($html);
        $pdf->Output('DeliverySlip.pdf', 'I');
    }
}

?>