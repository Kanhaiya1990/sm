<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DeliverySlipController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('DeliverySlipModel');
        $this->load->library('cart');
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

    public function deliverySlipDetail()
    {
        $this->load->library('pagination');

        $config['base_url'] = base_url('index.php/DeliverySlipController/deliverySlipDetail');
        
        $config['per_page'] = ($this->input->get('limitRows')) ? $this->input->get('limitRows') : 100;
        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;

        $data['currentAllocations']=$this->DeliverySlipModel->getCurrentOpenAllocations('allocations');
        $data['company']=$this->DeliverySlipModel->getdata('company');
        $data['bank']=$this->DeliverySlipModel->getdata('bank');
        $data['emp']=$this->DeliverySlipModel->getdata('employee');

         // integrate bootstrap pagination
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
       
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = 'Prev';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="'.$config['base_url'].'?per_page=0">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $data['page'] = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;
        $data['searchFor'] = ($this->input->get('query')) ? $this->input->get('query') : NULL;
        $data['orderField'] = ($this->input->get('orderField')) ? $this->input->get('orderField') : '';
        $data['orderDirection'] = ($this->input->get('orderDirection')) ? $this->input->get('orderDirection') : '';
        $outstanding="";
        $rowConunts="";

        $outstanding = $this->DeliverySlipModel->paginationDeliveryBills('bills',$config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);
        $rowCounts=$this->DeliverySlipModel->countPaginationDeliveryBills('bills',$config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);

        $data['pendingForBilling'] = $outstanding;
        $config['total_rows'] = $rowCounts;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('deliverySlipDetailsView',$data);
    }

    public function pendingForBilling(){
        $loginId = $this->session->userdata[$this->projectSessionName]['id'];
        $userDetail=$this->DeliverySlipModel->load('employee',$loginId);
        if($userDetail[0]['companyId']==0 || $userDetail[0]['companyId']==1){
            $data['pendingForBilling']=$this->DeliverySlipModel->getPendingForBilling('deliveryslip_pending_for_billing');
            $this->load->view('operator/pendingForDeliverySlipBillingView',$data);
        }else{
            $company=$this->DeliverySlipModel->load('company',$userDetail[0]['companyId']);
            $compName=$company[0]['name'];
            $data['pendingForBilling']=$this->DeliverySlipModel->getPendingForBillingWithCompany('deliveryslip_pending_for_billing',$compName);
            $this->load->view('operator/pendingForDeliverySlipBillingView',$data);
        }
    }

    // public function deliverySlipDetail(){
    //     $data['currentAllocations']=$this->DeliverySlipModel->getCurrentOpenAllocations('allocations');
    //     $data['company']=$this->DeliverySlipModel->getdata('company');
    //     $data['bank']=$this->DeliverySlipModel->getdata('bank');
    //     $data['emp']=$this->DeliverySlipModel->getEmployee('employee');
    //     $data['pendingForBilling']=$this->DeliverySlipModel->getDetailBilling('bills');
    //     $this->load->view('deliverySlipDetailsView',$data);
    // }

    public function getProductDetailById(){
        $productId=$this->input->post('productId');
        $productDetail=$this->DeliverySlipModel->load('products',$productId);
        $sumOfAdd=0;
        $sumOfReduce=0;
        $unitFilter=$productDetail[0]['unitFilter'];
        $prodName=$productDetail[0]['name'];
        $mrp=$productDetail[0]['mrp'];
        $unitOne=$productDetail[0]['unitOne'];
        $unitTwo=$productDetail[0]['unitTwo'];

        $add=$this->DeliverySlipModel->getSumOfAddQty('deliveryslip_pending_for_billing',$productId);
        $reduce=$this->DeliverySlipModel->getSumOfReduceQty('deliveryslip_pending_for_billing',$productId);
        if(!empty($add)){
            $sumOfAdd=$add[0]['totatAddQuantity'];
        }

        if(!empty($reduce)){
            $sumOfReduce=$reduce[0]['totatReduceQuantity'];
        }

        $totalQty=($sumOfAdd-$sumOfReduce);

        $retailerId=$this->input->post('retailerId');
        $retailerDetail=$this->DeliverySlipModel->getRetailerRateById('deliveryslip_pending_for_billing',$retailerId,$productId);
        $rate=0;
        $retailerUnitTwo="";
        if(!empty($retailerDetail)){
            $rate=$retailerDetail[0]['rate'];
            $retailerUnitTwo=$retailerDetail[0]['rateUnit'];
        }
        echo json_encode(['unitFilter'=>$unitFilter,'totalQty'=>$totalQty,'prodName'=>$prodName,'productMrp'=>$mrp,'unitOne'=>$unitOne,'unitTwo'=>$unitTwo,'retailerRate'=>$rate,'retailerUnitTwo'=>$retailerUnitTwo]);
        // echo $prodRate.' '.$rate;
    }

    public function getRetailerRateDetailById(){
        $retailerId=$this->input->post('retailerId');
        $retailerDetail=$this->DeliverySlipModel->getRetailerRateById('deliveryslip_pending_for_billing',$retailerId);
        print_r($retailerDetail);
    }

    public function getDeliverySlipDetails(){
        $id=$this->input->post('id');
        $getBillDetail=$this->DeliverySlipModel->getDeliveryBillDetail('deliveryslip_pending_for_billing',$id);
?>
    <div class="modal-header">
          <center><h4 class="modal-title">Delivery Slip Details</h4></center>
      </div>
        <div class="body outer">
        <div class="table-responsive">
            <div class="col-md-12">
            <table id="prodTbl" class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100' >
                <thead>
                    <tr>
                        <th>S. No.</th>
                        <th>Product Code </th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Quantity Unit</th>
                        <th>Rate</th>
                        <th>Rate Unit</th>
                    </tr>
                </thead>
                
                <tbody>
              
                  <?php
                    $no=0;
                    if(!empty($getBillDetail)){
                    foreach ($getBillDetail as $data) 
                      {
                      $no++;
                ?>
                    <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php echo $data['productCode']; ?></td>
                        <td><?php echo $data['productName']; ?></td>
                        <td><?php echo $data['quantity']; ?></td>
                        <td><?php echo $data['quantityUnit']; ?></td>
                        <td><?php echo $data['rate']; ?></td>
                        <td><?php echo $data['rateUnit']; ?></td>
                    </tr>
                <?php
                    }
                  }
                  ?> 
              
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php

    }

    //save operator operation for pending for billing
    public function savePendingBilling(){
        $userId=$this->session->userdata[$this->projectSessionName]['id'];
        $date=date('Y-m-d H:i:sa');
        $selValue=$this->input->post('selValue');

        // print_r($selValue);exit;
        if(!empty($selValue)){
            foreach($selValue as $sel){
                if($sel !== "on"){
                    $productId=$sel;
                    $prod=$this->DeliverySlipModel->load('products',$productId);
                    $fin=$this->DeliverySlipModel->getTotalQtySum('deliveryslip_pending_for_billing',$productId);
                    $finalQtyTotal=0;
                    if(!empty($fin)){
                          $finalQtyTotal=abs($fin[0]['totatQuantity']);
                    }    
                    $data=array(
                        'operationStatus'=>"operator operation",
                        'productId'=>$prod[0]['id'],
                        'productName'=>$prod[0]['name'],
                        'productCode'=>$prod[0]['productCode'],
                        'quantity'=>$finalQtyTotal,
                        'quantityUnit'=>'pcs',
                        'quantityInPcs'=>$finalQtyTotal,
                        'createdBy'=>$userId,
                        'createdAt'=>$date
                    );
                    $this->DeliverySlipModel->insert('deliveryslip_pending_for_billing',$data);
                }
                
            }
        }
    }

    public function index(){
        $userId=$this->session->userdata[$this->projectSessionName]['id'];
        $cartDetail=$this->DeliverySlipModel->getCartDetailsByUser('deliveryslip_add_to_cart',$userId);
        $retailerName="";
        $salesmanName="";
        if(!empty($cartDetail)){
            $retailerName=$cartDetail[0]['retailerCode'].' : '.$cartDetail[0]['retailerName'].' : '.$cartDetail[0]['retailerArea'];
            $salesmanName=$cartDetail[0]['empName'];
        }
        // print_r($cartDetail);exit;
        $data['retailerName']=$retailerName;
        $data['salesmanName']=$salesmanName;

        $data['currentAllocations']=$this->DeliverySlipModel->getCurrentOpenAllocations('allocations');
        $data['bank']=$this->DeliverySlipModel->getdata('bank');
        $getRetailerInfo=$this->DeliverySlipModel->getdata('retailer_kia');

        $getCode=$this->DeliverySlipModel->getdata('emp_code');
        $codeForRetailer="";
        $data['CodeForDS']="";
        $data['retailerCode']="";
        if(!empty($getCode)){
            $codeForRetailer=$getCode[0]['name'];
            $data['CodeForDS']=$getCode[0]['name'].'DS';
        }
        
        if(!empty($getRetailerInfo)){
            $data['retailerCode']=$codeForRetailer."100".count($getRetailerInfo);
        }else{
            $data['retailerCode']=$codeForRetailer."1001";
        }

        // echo $data['retailerCode'];exit;
       
        $id=0;
        $nextId=$this->DeliverySlipModel->getDeliveryData('bills');
        if(empty($nextId)) {
            $id = "1";
        } else {
            $id = count($nextId)+1;
        }
        $data['nextId'] =$id;

        $data['company']=$this->DeliverySlipModel->getdata('company');
        $data['retailer']=$this->DeliverySlipModel->RetailerName('retailer_kia');
        $data['retNames']=$this->DeliverySlipModel->RetailerName('retailer_kia');
        $data['emp']=$this->DeliverySlipModel->getEmployee('employee');
        $data['route']=$this->DeliverySlipModel->getRoute('route');
        $data['product']=$this->DeliverySlipModel->getActiveProductName('products');
        $data['openAllocations']=$this->DeliverySlipModel->getOpenAllocations('allocations');
        // print_r($data['openAllocations']);exit;
        $data['pendingForBilling']=$this->DeliverySlipModel->getPendingDetailBilling('bills');
        // print_r($data);exit;
        $this->load->view('newDeliverySlipBillingView',$data);
    }

    public function loadRetailerBills($name){
        $name=urldecode($name);
        // $data['retailer']=$this->DeliverySlipModel->getRetailerCode($name);
        // $code=$data['retailer'][0]['code'];
        $data['bills']=$this->DeliverySlipModel->retailerBillsByCode('bills',$name);
        echo json_encode( $data['bills']);
        // $this->load->view('RetailerwiseBillsView',$data);
    }

    public function billwiseDetail($billNo){
         $data['bills']=$this->DeliverySlipModel->loadBills('bills',$billNo);
         $id=$data['bills'][0]['id'];
         $data['billDetails']=$this->DeliverySlipModel->loadByBillId('billsdetails',$id);
         // print_r($data['billDetails']);
         // exit;
         $this->load->view('BillDetailsView',$data);
    }
        
    public function salesmanSaleDetail(){
        $data['sales']=$this->DeliverySlipModel->getSalesmanStockSale('bills');
        $data['prod']=$this->DeliverySlipModel->getdata('products');
        $data['emp']=$this->DeliverySlipModel->getdata('employee');
        // print_r($data['sales']);
        // exit;
        $this->load->view('Manager/salesmanStockSaleReportView',$data);
    }        
    
    public function checkSalesmanStock(){
        $fromDate=$this->input->post('from_date');
        $toDate=$this->input->post('to_date');
        $data['prod']=$this->DeliverySlipModel->getdata('products');
        $data['emp']=$this->DeliverySlipModel->getdata('employee');
        if(!empty($fromDate) && !empty($toDate)){
            $data['sales']=$this->DeliverySlipModel->getSalesmanStockSaleByDate('bills',$fromDate,$toDate);
            $this->load->view('Manager/salesmanStockSaleReportView',$data);
        }else{
            $data['sales']=$this->DeliverySlipModel->getSalesmanStockSale('bills');
            $this->load->view('Manager/salesmanStockSaleReportView',$data);
        }
    }
        


    public function RetailerwiseDetails()
    {
         $data['bills']=$this->DeliverySlipModel->retailerBills('bills');
        // print_r($data['bills']);
        // exit;
        // $data['bills']=$this->DeliverySlipModel->getBillData('bills');
         $this->load->view('RetailerwiseDetailView',$data);
    } 

    public function BillwiseDetails()
    {
        $data['retailer']=$this->DeliverySlipModel->getdata('retailer_kia');
        $data['bills']=$this->DeliverySlipModel->getBillData('bills');
        $this->load->view('BillwiseDetailsView',$data);
    }
    
    public function OutstandingBill()
    {
        $data['bills']=$this->DeliverySlipModel->getdataOutstandingBill('bills');
        $this->load->view('OutstandingBillView',$data);
    }


    public function load($id) 
    {
        $id=urldecode($id);
        $data['prod']=$this->DeliverySlipModel->getIDs('products',$id);
        $data['product']=$this->DeliverySlipModel->load('products', $data['prod'][0]['id']);
        echo json_encode($data);
    }

    public function loadRoute($name) 
    {
        $name=urldecode($name);
        $data['route']=$this->DeliverySlipModel->getRetailerArea($name);
        echo json_encode($data);
    }

    public function loadRetailerArea($name) 
    {
        $name=urldecode($name);
        $data['retailer']=$this->DeliverySlipModel->getRetailerArea($name);
        echo json_encode($data);
    }

    public function getBillData(){
        // $d=array();
        $d=$this->input->get('data');
        $data = json_decode($d,true); 


        $emp=$this->DeliverySlipModel->getEmpCode($data[0]['salesman']);
        $empId=$emp[0]['id'];
        
        
        $empID= $emp[0]['id'];
       
        if(!empty($data)){
            $total=0;
            $billNo='';
            $retailer='';
            $routeName='';
            $salesman='';
            foreach ($data as $key) {
                $billNo=$key['billNo'];
                $retailer=$key['retailer'];
                $total=$total+$key['amt'];
                $salesman=$key['salesman'];
                $routeName=$key['route'];
            }
          
            $code=$this->DeliverySlipModel->getKiaRetailerCode($retailer);//get retailer code and hierarchy
            
            $retailerId=$code[0]['id'];

            $data1 = array
                ('billNumber' => $billNo,
                'billDate' => date('Y-m-d H:i:sa'),
                'retailerId'=>$retailerId,
                'salesman'=>$salesman,
                'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
                'createdAt'=>date('Y-m-d H:i:sa')           
            ); 
            //insert data in Bills table 
            $this->DeliverySlipModel->insert('deliveryslip_billing',$data1); 
            if($this->db->affected_rows()>0){  
                $lastInsertedId=$this->db->insert_id();
                
                //update product quantity
                foreach($data as $item){
                    $qty=$item['qty'];
                    $qty = explode(' ', $qty);
                    $prdName=$item['item'];
                    //update AvailableQty in products table.
                    
                    $prdDetails=$this->DeliverySlipModel->prodDetailsByName('products',$prdName);//get product by name
                   
                    $boxQtyCal= $prdDetails[0]['boxQty'];//box qty for product
                    $qtyToUpdate=0;
                    if($qty[1]=='Case'){
                        $qtyToUpdate=$qty[0]*$boxQtyCal;//convert qty from cases to pcs
                    }else{
                        $qtyToUpdate=$qty[0];//qty in pcs
                    }
                   
                    $this->DeliverySlipModel->updateByName('products',$prdName,$qtyToUpdate);
                    if($this->db->affected_rows()>0){
                        $this->session->set_flashdata('msg', 'Record Inserted Successfully...!');
                    } else {
                        $this->session->set_flashdata('msg', 'Record Not Inserted...!');
                    }
                }

                //insert productwise billdetail
                foreach($data as $item){
                    $prodName=$item['item'];
                    $mrp=$item['mrp'];
                    $sellingPrice=$item['rate'];
                    $sellingPrice=explode('/',$sellingPrice);
                    $qty=$item['qty'];
                    $qty=explode(' ',$qty);
                    $netAmt=$item['amt'];
                    $billDetail = array
                    ('billId' => $lastInsertedId,
                    'productName' => $prodName,
                    'mrp' => $mrp,
                    'sellingRate'=>$sellingPrice[0],
                    'sellingUnit'=>$sellingPrice[1],
                    'qty' =>$qty[0],
                    'qtyUnit'=>$qty[1],
                    'netAmount' =>$netAmt,
                    'empId' => $empID
                     );
                     $this->DeliverySlipModel->insert('billsdetails',$billDetail);
                     if($this->db->affected_rows()>0){
                        $this->session->set_flashdata('msg', 'Record Inserted Successfully...!');
                     }else{
                        $this->session->set_flashdata('msg', 'Record Not Inserted...!');
                     } 
                }
                
                //remove cart data
                $this->cart->destroy();
                 $this->session->unset_userdata('tempSess');
                $this->printPDF($data);
                return redirect("DeliverySlipController");
                
            } else {
                $this->session->set_flashdata('msg', 'Record Not Inserted...!');
            }
           
        }else{
            return redirect("DeliverySlipController");
        }
    }

    public function insertDeliverySlipBill(){
        // $d=array();
        $d=$this->input->get('data');
        $data = json_decode($d,true); 


        $emp=$this->DeliverySlipModel->getEmpCode($data[0]['salesman']);
        $empId=$emp[0]['id'];
        
        
        $empID= $emp[0]['id'];
       
        if(!empty($data)){
            $total=0;
            $billNo='';
            $retailer='';
            $routeName='';
            $salesman='';
            foreach ($data as $key) {
                $billNo=$key['billNo'];
                $retailer=$key['retailer'];
                $total=$total+$key['amt'];
                $salesman=$key['salesman'];
                $routeName=$key['route'];
            }
          
            $code=$this->DeliverySlipModel->getKiaRetailerCode($retailer);//get retailer code and hierarchy
            
            $retailerId=$code[0]['id'];

            $data1 = array
                ('billNumber' => $billNo,
                'billDate' => date('Y-m-d H:i:sa'),
                'retailerId'=>$retailerId,
                'salesman'=>$salesman,
                'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
                'createdAt'=>date('Y-m-d H:i:sa')           
            ); 
            //insert data in Bills table 
            $this->DeliverySlipModel->insert('deliveryslip_billing',$data1); 
            if($this->db->affected_rows()>0){  
                $lastInsertedId=$this->db->insert_id();

                //insert productwise billdetail
                foreach($data as $item){
                    $prodName=$item['item'];
                    $productDetails=$code=$this->DeliverySlipModel->prodDetailsByName('products',$prodName);
                    $mrp=$item['mrp'];
                    $sellingPrice=$item['rate'];
                    $sellingPrice=explode('/',$sellingPrice);
                    $qty=$item['qty'];
                    $qty=explode(' ',$qty);
                    $netAmt=$item['amt'];
                    $billDetail = array
                    (
                        'billingId' => $lastInsertedId,
                        'productId'=>$productDetails[0]['id'],
                        'productName' => $prodName,
                        'productCode'=>$productDetails[0]['productCode'],
                        'quantity' =>$qty[0],
                        'quantityUnit'=>$qty[1],
                        'rate'=>$sellingPrice[0],
                        'rateUnit'=>$sellingPrice[1],
                        'cases'=>$qty[0],
                        'reduceInCompanySoftware' =>$qty[0],
                        'createdBy' => $this->session->userdata[$this->projectSessionName]['id'],
                        'createdAt'=>date('Y-m-d H:i:sa')
                    );
                     $this->DeliverySlipModel->insert('deliveryslip_pending_for_billing',$billDetail);
                     if($this->db->affected_rows()>0){
                        $this->session->set_flashdata('msg', 'Record Inserted Successfully...!');
                     }else{
                        $this->session->set_flashdata('msg', 'Record Not Inserted...!');
                     } 
                }
                
                //remove cart data
                $this->cart->destroy();
                 $this->session->unset_userdata('tempSess');
                $this->printPDF($data);
                return redirect("DeliverySlipController");
                
            } else {
                $this->session->set_flashdata('msg', 'Record Not Inserted...!');
            }
           
        }else{
            return redirect("DeliverySlipController");
        }
    }

    public function getRetailersBills(){
        $rname=$this->input->post('name');
        if($rname=='' || $rname==null){
            $data['retailer']=$this->DeliverySlipModel->getdata('retailer_kia');
            $data['bills']=$this->DeliverySlipModel->getBillData('bills');
            $this->load->view('BillwiseDetailsView',$data);
        }else{
            $code=$this->DeliverySlipModel->getKiaRetailerCode($rname);
            if(!empty($code)){
                $retailerName= $code[0]['name'];
                $data['retailer']=$this->DeliverySlipModel->getdata('retailer_kia');
                $data['bills']=$this->DeliverySlipModel->getKiaRetailerBillData('bills',$retailerName);
                $this->load->view('BillwiseDetailsView',$data);
            }else{
                $data['retailer']=$this->DeliverySlipModel->getdata('retailer_kia');
                $data['bills']=$this->DeliverySlipModel->getBillData('bills');
                $this->load->view('BillwiseDetailsView',$data); 
            }
        }
    }

    public function getBillWiseDetails() {
        $name= $this->input->post('retailerName');
        $code=$this->DeliverySlipModel->getRetailerCode($name);
            $retailerCode= $code[0]['code'];
        // echo $name." ".$code;
        // exit;

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
        $data['company']=$this->DeliverySlipModel->getdata('company');
          $data['prod']=$this->DeliverySlipModel->getActiveProducts('products');
          // $data['deactiveProd']=$this->DeliverySlipModel->getDeactiveProducts('products');
           
          // $data['employee']=$this->DeliverySlipModel->getdata('employee');
          // $items=array();
          // $record=array();
          // $i=0;
          // foreach ($data['employee'] as $item) {
          //    $items=$this->DeliverySlipModel->itemDetails($item['id']);
          //    $record[$i]=$items;
          //    $i++;
          // }
          // $data['items']=$record;
        
          $this->load->view('productMasterView',$data);
    }
    
    public function blockedProducts(){
          $data['prod']=$this->DeliverySlipModel->getDeactiveProducts('products');
          $this->load->view('blockedProductView',$data);
    }
    
    public function productsDataUploading(){
        $fileName=$_FILES['file']['name'];
        $fileType=$_FILES['file']['type'];
        $fileTempName=$_FILES['file']['tmp_name'];

        //upload file
        $config['upload_path'] = 'assets/uploads/';                             
        $config['file_name'] = $fileName;
        $config['overwrite'] = true;
        $config['allowed_types'] = 'xls|xlsx|csv';

        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('file')){
            $this->upload->display_errors();
        }
        $media =  $fileName;
        $path = 'assets/uploads/'. $media;

        $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        if(isset($fileName) && in_array($fileType, $file_mimes)) {
            $arr_file = explode('.', $fileName); //get file
            $extension = end($arr_file); //get file extension

            // select spreadsheet reader depends on file extension
            if('csv' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else if ('xlsx'){
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            }

            $reader->setReadDataOnly(true);
            $objPHPExcel = $reader->load($fileTempName);//Get filename
            
            $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
            $highestRow = $worksheet->getHighestRow(); // e.g. 12
            $highestColumn = $worksheet->getHighestColumn(); // e.g M'

            $billNumber="";
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
            
            $cnt=0;
            $excelTotalAmt=0;
            
            for ($row = 2; $row <= $highestRow; ++$row) {
                $cnt++;
                $productName = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                $productCode = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                $company= trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                $mrp = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                $unitFilter = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                $unitOne = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                $unitTwo = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                
                $checkProduct=$this->DeliverySlipModel->checkProductDataForUpload('products',$productCode,$productName,$mrp);//insert remark data
                if(empty($checkProduct)){
                     $prodData=array(
                        'name'=>$productName,
                        'productCode'=>$productCode,
                        'company'=>$company,
                        'mrp'=>$mrp,
                        'unitFilter'=>$unitFilter,
                        'unitOne'=>$unitOne,
                        'unitTwo'=>$unitTwo,
                        'unitThree'=>1,
                        'isActive'=>1,
                    );
                    $this->DeliverySlipModel->insert('products',$prodData);//insert remark data
                }
            }
        }
        redirect('DeliverySlipController/Products');
    }
    
    

    public function SalesmanWiseProducts(){
        // $data['prod']=$this->DeliverySlipModel->getdata('products');
        $data['prod']=$this->DeliverySlipModel->ProductName();
        $data['emp']=$this->DeliverySlipModel->getdata('employee');
        $this->load->view('SalesmansProductStockView',$data);
    }

    public function ShowPDF($id)
    {
        $data['bills']=$this->DeliverySlipModel->load('bills', $id);
        $data['billNo']=$data['bills'][0]['billNo'];
        $data['salesman']=$data['bills'][0]['salesman'];
        $data['retailerName']=$data['bills'][0]['retailerName'];
        $data['routeName']=$data['bills'][0]['routeName'];
        $data['date']=date("d-m-Y", strtotime($data['bills'][0]['date']));
        $data['orderDetails']=$this->DeliverySlipModel->loadByBillId('billsdetails', $id);
        $this->load->library('Pdf');
        $this->load->view('BillwisePDFview', $data);
    }

    public function printPDF($details,$retailerName,$retailerCode,$salesmanName,$salesmanCode)
    {
        $retailerArea="";
        $getRetailer=$this->DeliverySlipModel->checkRetailerExist('retailer_kia', trim($retailerCode));
        if(!empty($getRetailer)){
            $retailerArea=$getRetailer[0]['area'];
        }

        $orderDetails=array();
        $no=0;
        foreach($details as $item){
            $orderDetails[$no]=$item;
            $no++;
        }
        
        $this->load->library('Pdf');
        // $pageLayout = array(105, 148);
        $pdf = new Pdf('L', 'mm', 'A6', true, 'UTF-8', false);
        $pdf->SetTitle('Delivery Slip');
        $pdf->SetHeaderMargin(30);
        $pdf->SetTopMargin(20);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(true);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAuthor('Author');
        $pdf->SetDisplayMode('real', 'default');
        $pdf->AddPage();

        $details = "";
        $total=0;
        
        for($i=0;$i<count($orderDetails);$i++) {

            $details = $details."<tr><td>".($i+1)."</td>
                <td>".$orderDetails[$i]['prodName']."</td>
                <td>".$orderDetails[$i]['mrp']."</td>
                <td>".$orderDetails[$i]['qty']."</td>
                <td>".$orderDetails[$i]['price']."</td>
                <td>".$orderDetails[$i]['amt']."</td></tr>";
                $total=$total+$orderDetails[$i]['amt'];
        }    

        $html = '
       <table>
            <br /><br /><br />
            <tr>
                <td align="left"><b>Date : </b>'.date('d-m-Y').'</td>
                <td align="right"><b>Bill No : </b>'.$bills[0]['billNo'].'</td>
            </tr>
            <tr>
                <td align="left"><b>Retailer Name :</b>'.$bills[0]['retailerName'].'</td>
                <td align="right"><b>Retailer Code :</b>'.$bills[0]['retailerCode'].'</td>
             </tr>
             <tr>
                <td align="left"><b>Salesman Name: </b>'.$bills[0]['salesman'].'</td>
                <td align="right"><b>Salesman Code: </b>'.$bills[0]['salesmanCode'].'</td>
             </tr>  
             <tr>
                <td align="left"><b>Retailer Area: </b>'.$retailerArea.'</td>
                <td align="right"></td>
             </tr> 
        </table>
        <br><br>
        <table border="1px" rowspan="2" colspan="2" style="text-align:center;font-size:10px">
            <tr>
                <td><b>Sr.No</b></td>
                <td style="width:100px"><b>Name</b></td>
                <td><b>MRP</b></td>    
                <td><b>Quantity</b></td>
                <td><b>Rate</b></td>
                <td><b>Amount</b></td>
            </tr>
                '.$details.'
        </table>
        <br /> 
        <table width="100%" style="text-align:right;">
            <div style="min-height:427px;">
             <span><b>Grand Total :</b> '.$total.'</span>
            </div>  
        </table>';

         // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        $filename="delivery-slip-".$bills[0]['billNo'].".pdf";
        $filePath=base_url()."/assets/uploads/pdf/".$filename;
        $pdf->Output($filePath, 'FI');
    }

    
    public function downloadPDF($id)
    {
        $bills=$prodDetails=$this->DeliverySlipModel->load('bills', $id);
        $orderDetails=$prodDetails=$this->DeliverySlipModel->loadByBillId('billsdetails', $id);

        $setting= $this->DeliverySlipModel->load('tbl_settings',1);
        $printDataValue="";
        if(!empty($setting)){
            if($setting[0]['propertyValue']=="yes"){
                $printDataValue="yes";
            }
        }

        $retailerArea="";
        $retailerCode="";
        $retailerAddress="";
        $getRetailer=$this->DeliverySlipModel->checkRetailerExist('retailer_kia', trim($bills[0]['retailerCode']));
        if(!empty($getRetailer)){
            $retailerArea=$getRetailer[0]['area'];
            $retailerCode=$getRetailer[0]['retailerCode'];
            $retailerAddress=$getRetailer[0]['billingAddress'];
        }
       
        $this->load->library('Pdf');
        // $pageLayout = array(105, 148);
        $pdf = new Pdf('P', 'mm', 'A6', true, 'UTF-8', false);
        $pdf->SetTitle($bills[0]['billNo']);
        $pdf->SetHeaderMargin(30);
        $pdf->SetTopMargin(20);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(true);
        $pdf->SetAuthor('Author');
        $pdf->SetDisplayMode('real', 'default');
        $pdf->AddPage();

        $details = "";
        $total=0;
        $caseTotal=0;
        $boxTotal=0;
        $pcsTotal=0;
        for($i=0;$i<count($orderDetails);$i++) {
   
   
            if($orderDetails[$i]['qtyUnit']=="Case"){
                $caseTotal=$caseTotal+$orderDetails[$i]['sellingQuantity'];
            }
            if($orderDetails[$i]['qtyUnit']=="Box"){
                $boxTotal=$boxTotal+$orderDetails[$i]['sellingQuantity'];
            }
            if($orderDetails[$i]['qtyUnit']=="Pcs"){
                $pcsTotal=$pcsTotal+$orderDetails[$i]['qty'];
            }
            $details = $details."<tr><td>".($i+1)."</td>
                <td>".$orderDetails[$i]['productName']."</td>
                <td>".number_format($orderDetails[$i]['mrp'])."</td>
                <td>".$orderDetails[$i]['sellingQuantity'].' '.$orderDetails[$i]['qtyUnit']."</td>
                <td>".number_format($orderDetails[$i]['sellingRate']).'/'.$orderDetails[$i]['sellingUnit']."</td>
                <td>".number_format($orderDetails[$i]['netAmount'])."</td></tr>";
                $total=$total+$orderDetails[$i]['netAmount'];
        }    

        $html = '
        <br /><br /><br />
        <table style="text-align:center;font-size:16px">
            <tr>
                <td align="left"><b>Date: </b>'.date('d-M-Y').'</td>
                
                <td align="left"><b>Bill No: </b>'.$bills[0]['billNo'].'</td>
            </tr>
            <tr>
                <td align="left"><b>Retailer: </b>'.$bills[0]['retailerName'].'</td>
                
                <td align="left"><b>Salesman: </b>'.$bills[0]['salesman'].'</td>
             </tr>
            <tr>
                <td align="left"><b>Retailer Code: </b>'.$retailerCode.'</td>
                <td align="left"><b>Retailer Area: </b>'.$retailerArea.'</td>
             </tr>';
                
             if($printDataValue !=""){
                $html .= '<tr>
                        <td colspan="2" align="left"><b>Retailer Address: </b>'.$retailerAddress.'</td>
                    </tr> ';
             }
               
             $html .=' </table>
        <br>
        <br>

        <table border="1px" style="padding:3px;text-align:center;font-size:16px">
            <tr>
                <td style="width:30px"><b>No</b></td>
                <td style="width:210px"><b>Name</b></td>
                <td style="width:45px"><b>MRP</b></td>
                <td style="width:80px"><b>Qty</b></td>
                <td style="width:95px"><b>Rate</b></td>
                <td style="width:90px"><b>Amount</b></td>
            </tr>
                '.$details.'
        </table>
        <br /> 
        <table width="100%" style="text-align:left;font-size:16px">
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            
            <tr>
                <td><b>Total Case</b></td>
                <td>'.$caseTotal.' Case</td>
                <td><b>Total Box</b></td>
                <td>'.$boxTotal.' Box </td>
                <td><b>Total Pcs</b></td>
                <td>'.$pcsTotal.' Pcs</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                
                <td></td>
                <td></td>
                <td></td>
                
                <td colspan="3" align="right"><b>Grand Total: </b><b>'.number_format($total).' </b></td>
            </tr>
           
        </table>';

         // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        $filename=$bills[0]['billNo'].".pdf";
        $pdf->Output($filename, 'I');
    }

    public function pastDeliverySlipDownloadPDF($id)
    {
        $bills=$prodDetails=$this->DeliverySlipModel->load('bills', $id);
        $orderDetails=$prodDetails=$this->DeliverySlipModel->loadByBillId('billsdetails', $id);

        $setting= $this->DeliverySlipModel->load('tbl_settings',1);
        $printDataValue="";
        if(!empty($setting)){
            if($setting[0]['propertyValue']=="yes"){
                $printDataValue="yes";
            }
        }

        

        $retailerArea="";
        $retailerCode="";
        $retailerAddress="";
        $getRetailer=$this->DeliverySlipModel->checkRetailerExist('retailer_kia', trim($bills[0]['retailerCode']));
        if(!empty($getRetailer)){
            $retailerArea=$getRetailer[0]['area'];
            $retailerCode=$getRetailer[0]['retailerCode'];
            $retailerAddress=$getRetailer[0]['billingAddress'];
        }
       
        $this->load->library('Pdf');
        // $pageLayout = array(105, 148);
        $pdf = new Pdf('P', 'mm', 'A6', true, 'UTF-8', false);
        $pdf->SetTitle($bills[0]['billNo']);
        $pdf->SetHeaderMargin(30);
        $pdf->SetTopMargin(20);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(true);
        $pdf->SetAuthor('Author');
        $pdf->SetDisplayMode('real', 'default');
        $pdf->AddPage();

        $details = "";
        $total=0;
        $caseTotal=0;
        $boxTotal=0;
        $pcsTotal=0;
        for($i=0;$i<count($orderDetails);$i++) {
   
   
            if($orderDetails[$i]['qtyUnit']=="Case"){
                $caseTotal=$caseTotal+$orderDetails[$i]['sellingQuantity'];
            }
            if($orderDetails[$i]['qtyUnit']=="Box"){
                $boxTotal=$boxTotal+$orderDetails[$i]['sellingQuantity'];
            }
            if($orderDetails[$i]['qtyUnit']=="Pcs"){
                $pcsTotal=$pcsTotal+$orderDetails[$i]['qty'];
            }
            $details = $details."<tr><td>".($i+1)."</td>
                <td>".$orderDetails[$i]['productName']."</td>
                <td>".number_format($orderDetails[$i]['mrp'])."</td>
                <td>".$orderDetails[$i]['sellingQuantity'].' '.$orderDetails[$i]['qtyUnit']."</td>
                <td>".number_format($orderDetails[$i]['sellingRate']).'/'.$orderDetails[$i]['sellingUnit']."</td>
                <td>".number_format($orderDetails[$i]['netAmount'])."</td></tr>";
                $total=$total+$orderDetails[$i]['netAmount'];
        }    

        $html = '
        <br /><br /><br />
        <table style="text-align:center;font-size:16px">
            <tr>
                <td align="left"><b>Date: </b>'.date('d-M-Y',strtotime($bills[0]['date'])).'</td>
                
                <td align="left"><b>Bill No: </b>'.$bills[0]['billNo'].'</td>
            </tr>
            <tr>
                <td align="left"><b>Retailer: </b>'.$bills[0]['retailerName'].'</td>
                
                <td align="left"><b>Salesman: </b>'.$bills[0]['salesman'].'</td>
             </tr>
            <tr>
                <td align="left"><b>Retailer Code: </b>'.$retailerCode.'</td>
                <td align="left"><b>Retailer Area: </b>'.$retailerArea.'</td>
             </tr>';   

             if($printDataValue !=""){
                $html .= '<tr>
                        <td colspan="2" align="left"><b>Retailer Address: </b>'.$retailerAddress.'</td>
                    </tr> ';
             }
               
             $html .=' </table>
        <br>
        <br>

        <table border="1px" style="padding:3px;text-align:center;font-size:16px">
            <tr>
                <td style="width:30px"><b>No</b></td>
                <td style="width:210px"><b>Name</b></td>
                <td style="width:45px"><b>MRP</b></td>
                <td style="width:80px"><b>Qty</b></td>
                <td style="width:95px"><b>Rate</b></td>
                <td style="width:90px"><b>Amount</b></td>
            </tr>
                '.$details.'
        </table>
        <br /> 
        <table width="100%" style="text-align:left;font-size:16px">
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            
            <tr>
                <td><b>Total Case</b></td>
                <td>'.$caseTotal.' Case</td>
                <td><b>Total Box</b></td>
                <td>'.$boxTotal.' Box </td>
                <td><b>Total Pcs</b></td>
                <td>'.$pcsTotal.' Pcs</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                
                <td></td>
                <td></td>
                <td></td>
                
                <td colspan="3" align="right"><b>Grand Total: </b><b>'.number_format($total).' </b></td>
            </tr>
           
        </table>';

         // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        $filename=$bills[0]['billNo'].".pdf";
        $pdf->Output($filename, 'I');
    }

    public function checkProduct(){
        $res="";
        $productId=trim($this->input->post('productId'));
        $productName=trim($this->input->post('productName'));
        $userId = $this->session->userdata[$this->projectSessionName]['id'];
        if (!empty($productName)) {
            $prodDetails=$this->DeliverySlipModel->checkProductExist('deliveryslip_add_to_cart', $productId,$userId);
            if(!empty($prodDetails)){
                $res="yes";
            }else{
                $res="no";
            }
            // $name=$prodDetails[0]['name'];
            // $bag = $this->cart->contents();
            // if (!empty($bag)) {
            //     foreach ($bag as $item) {
            //         if ($item['prodName'] == $name) {
            //             $res="yes";
            //             // break;
            //         } else {
            //             $res="no";
            //         }
            //     }
            // }else{
            //     $res="no";
            // }
        }
        echo $res;
    }

    public function deleteCartData(){
        $this->db->truncate('deliveryslip_add_to_cart');
    }

    public function loadCart(){ 
        echo $this->showDetails();
    }

    public function deleteCart($id){ 
        // $id=$this->input->post('cart_id');
        $this->DeliverySlipModel->delete('deliveryslip_add_to_cart',$id); 
        redirect('DeliverySlipController');
        // echo $this->showDetails();
    }

    public function addToCart(){ 
        $salesman=$this->input->post('salesman');
        $retailer=$this->input->post('retailer');

        $billNo=$this->input->post('billNo');
        $salesmanId=$this->input->post('salesmanId');
        $productId=$this->input->post('productId');
        $retailerId=$this->input->post('retailerId');
        $userId=$this->session->userdata[$this->projectSessionName]['id'];
        
        $prodDetails=$this->DeliverySlipModel->load('products', $productId);
        $productMrp=$prodDetails[0]['mrp'];
        $productName=$prodDetails[0]['name']; 

        $rate=$this->input->post('rate');
        $unt2=$this->input->post('unt2');
        $qty= $this->input->post('qty');
        $unt1=$this->input->post('unt1');
        $prodName=$this->input->post('pName');
        $pName='test';
        $mrp=$this->input->post('mrp');
        $amt=$this->input->post('amt');


        
        $data = array(
            'userId' => $userId,
            'salesmanId' => $salesmanId,
            'retailerId' => $retailerId,
            'productId' => $productId,
            'billNo'=>$billNo,
            'productName' =>$productName,
            'mrp'=>$mrp,
            'quantity' =>$qty,
            'rate' =>$rate,
            'amount' =>round($amt),
            'unitOne'=>$unt1,
            'unitTwo' => $unt2
        );
        $this->DeliverySlipModel->insert('deliveryslip_add_to_cart', $data);
        echo $this->showDetails(); 
    }

    public function showDetails(){
        $cartDetails=$this->DeliverySlipModel->getdata('deliveryslip_add_to_cart');
        $output = '';
        $no = 0;
        $total=0;
        foreach ($cartDetails as $items) {
            $path= site_url('DeliverySlipController/deleteCart/'.$items['id']);
            $no++;
            $total=$total+$items['amount'];
            $output .='
            <tr>
            <td>'.$items['productName'].'</td>
            <td>'.$items['mrp'].'</td>
            <td>'.$items['quantity'].' '.$items['unitOne'].'</td>
            <td>'.$items['rate'].'/'.$items['unitTwo'].'</td>
            <td>'.$items['amount'].'</td>
            <td> 
              <a href="'.$path.'">
              <button class="btn btn-xs btn-primary waves-effect" data-type="basic"><i class="material-icons">delete</i></button>
              </a>
            </td>
            </tr>
            
            ';
        }
        $output=$output.'
            <tr>
                  <td>Grand Total :</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td>'.$total.'</td>
                  <td></td>
            </tr>
        ';
        
        return $output;
    }

  
       //add to cart
    function add_to_cart(){ 
        $salesman=$this->input->post('salesman');
        $retailer=$this->input->post('retailer');
        
        $session_data = array(
            'salesman' => $salesman,
            'retailer' => $retailer
        );

        if($this->session->userdata('tempSess')){
            // Update user data in session
            $detailsData = $this->session->userdata('tempSess');
            $detailsData['salesman']= $salesman;
            $detailsData['retailer']= $retailer;
            $this->session->set_userdata('tempSess', $detailsData);  
        }else{
            // Add user data in session
            $this->session->set_userdata('tempSess', $session_data);
        }
        

        $billNo=$this->input->post('billNo');
        $salesmanId=$this->input->post('salesmanId');
        $productId=$this->input->post('productId');
        $retailerId=$this->input->post('retailerId');
        
        $prodDetails=$this->DeliverySlipModel->load('products', $productId);
        $prodMrp=$prodDetails[0]['mrp']; 

        $rate=$this->input->post('rate');
        $unt2=$this->input->post('unt2');
        $qty= $this->input->post('qty');
        $unt1=$this->input->post('unt1');
        $prodName=$this->input->post('pName');
        $pName='test';
        $mrp=$this->input->post('mrp');
        $amt=$this->input->post('amt');

        $insert_new = TRUE;
        $bag = $this->cart->contents();
        foreach ($bag as $item) {
            // check product id in session, if exist update the quantity
            if ( $item['prodName'] === $prodName ) { // Set value to your variable
                $data = array(
                    'rowid' => $item['rowid'],
                    'name' =>$pName,
                    'qty' =>$qty,
                    'price' =>$rate,
                    'mrp' =>$prodMrp,
                    'amt'=>$amt,
                    'unt1'=>$unt1,
                    'unt2' => $unt2,
                    'prodName' => $prodName,
                    'billNo' => $billNo,
                    'salesmanId' => $salesmanId,
                    'productId' => $productId,
                    'retailerId' => $retailerId
                );
                $this->cart->update($data);
                $insert_new = FALSE;
            }else{
                $insert_new = TRUE;
            }
        }

        if ($insert_new) {
            $id=count($this->cart->contents());
            $id=$id+1;
            $data = array(
                'id'=>$id,
                'name' =>$pName,
                'qty' =>$qty,
                'price' =>$rate,
                'mrp' =>$prodMrp,
                'amt'=>$amt,
                'unt1'=>$unt1,
                'unt2' => $unt2,
                'prodName' => $prodName,
                'billNo' => $billNo,
                'salesmanId' => $salesmanId,
                'productId' => $productId,
                'retailerId' => $retailerId
            );
            $this->cart->insert($data);
        }
        
        echo $this->show_cart(); 
    }

    function show_cart(){ 
        $output = '';
        $no = 0;
        $total=0;
        foreach ($this->cart->contents() as $items) {
            $no++;
            $total=$total+$items['amt'];
            $output .='
            <tr>
            <td>'.$items['prodName'].'</td>
            <td>'.$items['mrp'].'</td>
            <td>'.$items['qty'].' '.$items['unt1'].'</td>
            <td>'.$items['price'].'/'.$items['unt2'].'</td>
            <td>'.$items['amt'].'</td>
            <td><button id="'.$items['rowid'].'" class="romove_cart btn btn-sm"><i class="fa fa-trash" style="color: red;"></i></button></td>
            </tr>
            
            ';
        }
        $output=$output.'
            <tr>
                  <td>Grand Total :</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td>'.$total.'</td>
                  <td></td>
            </tr>
        ';
        
        return $output;
    }

    function load_cart(){ 
        echo $this->show_cart();
    }

    function delete_cart(){ 
        $data = array(
            'rowid' => $this->input->post('row_id'), 
            'qty' => 0, 
        );
        $this->cart->update($data);
        echo $this->show_cart();
    }
    
    public function clearAllData(){
        $userId=$this->session->userdata[$this->projectSessionName]['id'];
        $this->DeliverySlipModel->deleteCartRecordsByUser('deliveryslip_add_to_cart',$userId);
        $this->session->unset_userdata('tempSess');
        redirect('DeliverySlipController');
    }
    
    public function retailerAccountDetail(){
         $data['bills']=$this->DeliverySlipModel->retailerAccountBills('retailer_kia');
        $this->load->view('Manager/retailerAccountStatementView',$data);
    }
    
    public function retailerWiseAccountStatement($name){
        $name=urldecode($name);
        $data['bills']=$this->DeliverySlipModel->retailerBillsByCode('bills',$name);
        $this->load->view('Manager/retailerAccountStatementDetailView',$data);
    }

    public function insertDeliverySlipData(){
        $latestid=0;
        $nextId=$this->DeliverySlipModel->getDeliveryData('bills');
        if(empty($nextId)) {
            $latestid = "1";
        } else {
            $latestid = count($nextId)+1;
        }
        
        $getCode=$this->DeliverySlipModel->getdata('emp_code');
        $codeForRetailer="";
        $codeForDS="";
        $data['retailerCode']="";
        if(!empty($getCode)){
            $codeForRetailer=$getCode[0]['name'];
            $codeForDS=$getCode[0]['name'].'DS';
        }
        $billNo="";
     
        $billNo= $codeForDS.date('y').(1000+$latestid);
        // echo $billNo;exit;
        // $billNo=$this->input->post('billNo');
        $salesmanId=$this->input->post('salesmanId');
        $retailerId=$this->input->post('retailerId');

        

        $billDate=date('Y-m-d');
        $userId=$this->session->userdata[$this->projectSessionName]['id'];

        $salesmanData=$this->DeliverySlipModel->load('employee',$salesmanId);
        $retailerData=$this->DeliverySlipModel->load('retailer_kia',$retailerId);

        $retailerName="";
        $retailerCode="";
        $salesmanName="";
        $salesmanCode="";
        if(!empty($salesmanData)){
            $salesmanName=$salesmanData[0]['name'];
            $salesmanCode=$salesmanData[0]['code'];
        }

        if(!empty($retailerData)){
            $retailerName=$retailerData[0]['name'];
            $retailerCode=$retailerData[0]['retailerCode'];
        }

        $billAmountTotal=0;
        $companyName="";
        // $userId=$this->session->userdata[$this->projectSessionName]['id'];
        $itemsData=$this->DeliverySlipModel->getCartDetailsByUser('deliveryslip_add_to_cart',$userId);
        // $itemsData=$this->cart->contents();
        if(!empty($itemsData)){
            foreach($itemsData as $item){
                $billAmountTotal=$billAmountTotal+$item['amount'];
                $prodData=$this->DeliverySlipModel->load('products',$item['productId']);
                if(!empty($prodData)){
                    if(strpos($companyName, $prodData[0]['company']) == false){
                        $companyName=$companyName.','.$prodData[0]['company'];
                    }
                }
            }
        }

        $deliverySlipData=array(
            'billNo'=>$billNo,
            'date'=>$billDate,
            'salesman'=>$salesmanName,
            'salesmanId'=>$salesmanId,
            'salesmanCode'=>$salesmanCode,
            'retailerName'=>$retailerName,
            'retailerId'=>$retailerId,
            'retailerCode'=>$retailerCode,
            'routeCode'=>'OFC',
            'routeName'=>'Office Allocated',
            'billNetAmount'=>$billAmountTotal,
            'netAmount'=>$billAmountTotal,
            'pendingAmt'=>$billAmountTotal,
            'compName'=>trim($companyName,','),
            'isDeliverySlipBill'=>1,
            'createdBy'=>$userId,
            'deliveryStatus'=>"delivered",
            'manuallyAddedBill'=>1
        );
// print_r($deliverySlipData);exit;
        $this->DeliverySlipModel->insert('bills',$deliverySlipData);
        if($this->db->affected_rows()>0){
            $insert_id = $this->db->insert_id();
            // $itemsData=$this->cart->contents();
            $itemsData=$this->DeliverySlipModel->getCartDetailsByUser('deliveryslip_add_to_cart',$userId);
            if(!empty($itemsData)){
                foreach($itemsData as $item){
                    $productDetail=$this->DeliverySlipModel->load('products',$item['productId']);
                    $retailerDetail=$this->DeliverySlipModel->load('retailer_kia',$item['retailerId']);
                    $pcs=$productDetail[0]['unitOne'];
                    $box=$productDetail[0]['unitTwo'];
                    $case=$productDetail[0]['unitThree'];

                    $retailerName=$retailerDetail[0]['name'];
                    $retailerCode=$retailerDetail[0]['retailerCode'];

                    $productName=$productDetail[0]['name'];
                    $productCode=$productDetail[0]['productCode'];
                    
                    $totalPcs=0;
                    $totalMrp=0;
                   
                    if($item['unitOne']==="Box"){
                        $totalPcs=$pcs*$item['quantity']*$case;
                    }else if($item['unitOne']==="Case"){
                        $totalPcs=$pcs*$box*$item['quantity'];
                    }else{
                        $totalPcs=$item['quantity'];
                    }

                    $insData=array(
                        'billingId'=>$insert_id,
                        'operationStatus'=>'reduce',
                        'retailerId'=>$item['retailerId'],
                        'retailerCode'=>$retailerCode,
                        'productId'=>$item['productId'],
                        'productName'=>$productName,
                        'productCode'=>$productCode,
                        'quantity'=>$item['quantity'],
                        'quantityUnit'=>$item['unitOne'],
                        'rate'=>$item['rate'],
                        'rateUnit'=>$item['unitTwo'],
                        'netAmount'=>$item['amount'],
                        'reduceInCompanySoftware'=>$item['quantity'],
                        'quantityInPcs'=>(-$totalPcs),
                        'createdBy'=>$userId,
                        'createdAt'=>date('Y-m-d H:i:sa')
                    );



                    $billDetailData=array(
                        'billId'=>$insert_id,
                        'productName'=>$productName,
                        'productCode'=>$productCode,
                        'qty'=>$totalPcs,
                        'sellingQuantity'=>$item['quantity'],
                        'qtyUnit'=>$item['unitOne'],
                        'mrp'=>$item['mrp'],
                        'sellingRate'=>$item['rate'],
                        'sellingUnit'=>$item['unitTwo'],
                        'netAmount'=>$item['amount']
                    );
                    
                    
                    $this->DeliverySlipModel->insert('deliveryslip_pending_for_billing',$insData);
                    $this->DeliverySlipModel->insert('billsdetails',$billDetailData);
                    $this->DeliverySlipModel->delete('deliveryslip_add_to_cart',$item['id']);
                }
            } 
            echo $insert_id;
            // $this->cart->destroy();
            // $this->session->unset_userdata('tempSess');
            // $this->printPDF($itemsData,$retailerName,$retailerCode,$salesmanName,$salesmanCode); 
            // echo "Record Inserted";
        }else{
            echo "Record not Inserted";
        }
    }

     

}
?>