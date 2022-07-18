<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class AdHocController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('AllocationByManagerModel');
        $this->load->library('session');
        date_default_timezone_set('Asia/Kolkata');
        ini_set('memory_limit', '-1');

        if(isset($this->session->userdata['codeKeyData'])) {
			$this->projectSessionName= $this->session->userdata['codeKeyData']['codeKeyValue'];
		}else{
			$this->load->view('LoginView');
		}
    }

    public function freeItemBills(){
        $data['bills']=$this->AllocationByManagerModel->getdata('billsdetails_freeitems');
        $this->load->view('freeItemBillsView',$data);
    }


    public function retailerBillsExport()
    {
        $code="";
        if (isset($this->session->userdata['historyRetailer']) && (empty($retailerPost))) {
            $code=($this->session->userdata['historyRetailer']['code']);
        }
        $retailerBills=$this->AllocationByManagerModel->allRetailerBillsByCode('bills',$code);
        $extension = "xlsx";
        if(!empty($extension)){
          $extension = $extension;
        } else {
          $extension = 'xlsx';
        }
        $this->load->helper('download');  
        $data = array();
        $data['title'] = 'Export Excel Sheet | Coders Mag';
        
        $fileName = 'Retailers Bills -'.time(); 
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'Sr No.');
        $sheet->setCellValue('B1', 'Bill No');
        $sheet->setCellValue('C1', 'Bill Date');
        $sheet->setCellValue('D1', 'Retailer');
        $sheet->setCellValue('E1', 'Bill Amount');
        $sheet->setCellValue('F1', 'Pending Amount');
        $sheet->setCellValue('G1', 'Salesman');
        $sheet->setCellValue('H1', 'Route');
        $sheet->setCellValue('I1', 'Company');
     
        $rowCount = 2;
        $no=0;
        foreach ($retailerBills as $element) {
            $no++;
            $dt=date_create($element['date']);
            $dt= date_format($dt,'d-M-Y');
            $sheet->setCellValue('A' . $rowCount, $no++);
            $sheet->setCellValue('B' . $rowCount, $element['billNo']);
            $sheet->setCellValue('C' . $rowCount, $dt);
            $sheet->setCellValue('D' . $rowCount, $element['retailerName']);
            $sheet->setCellValue('E' . $rowCount, $element['netAmount']);
            $sheet->setCellValue('F' . $rowCount, $element['pendingAmt']);
            $sheet->setCellValue('G' . $rowCount, $element['salesman']);
            $sheet->setCellValue('H' . $rowCount, $element['routeName']);
            $sheet->setCellValue('I' . $rowCount, $element['compName']);
            $rowCount++;
        }
     
        if($extension == 'csv'){          
          $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
          $fileName = $fileName.'.csv';
        } elseif($extension == 'xlsx') {
          $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
          $fileName = $fileName.'.xlsx';
        } else {
          $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
          $fileName = $fileName.'.xls';
        }
     
        $this->output->set_header('Content-Type: application/vnd.ms-excel');
        $this->output->set_header("Content-type: application/csv");
        $this->output->set_header('Cache-Control: max-age=0');
        $writer->save(ROOT_UPLOAD_PATH.$fileName); 
        //redirect(HTTP_UPLOAD_PATH.$fileName); 
        $filepath = file_get_contents(ROOT_UPLOAD_PATH.$fileName);
        force_download($fileName, $filepath);
    }

    public function billSearch(){
        $data['bank']=$this->AllocationByManagerModel->getdata('bank');
        $data['emp']=$this->AllocationByManagerModel->getdata('employee');
        $data['currentAllocations']=$this->AllocationByManagerModel->getCurrentOpenAllocations('allocations');
        $data['bills']=$this->AllocationByManagerModel->getBillsData('bills');
        $this->load->view('Manager/billSearchView',$data);
    }

    public function findBillsData(){
        $billNo=trim($this->input->post('billNo'));
        $billsData=$this->AllocationByManagerModel->findBills('bills',$billNo);
        if(!empty($billsData)){
            if(count($billsData)>1){
?>
       <!--  <table class="table table-bordered cust-tbl js-exportable dataTable" data-page-length='100'> -->
        <table style="font-size: 14px" class="table table-bordered js-basic-example dataTable cust-tbl" data-page-length="100" id="DataTables_Table_0">
        <thead>
            <tr>
                <th>S. No.</th>
                <th>Bill No</th>
                <th>Bill Date</th>
                <th>Retailer</th>
                <th>Bill Amount</th>
                <th>Pending Amount</th>
                <th>Salesman</th>
                <th>Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>S. No.</th>
                <th>Bill No</th>
                <th>Bill Date</th>
                <th>Retailer</th>
                <th>Bill Amount</th>
                <th>Pending Amount</th>
                <th>Salesman</th>
                <th>Action</th>
            </tr>
        </tfoot>
        <tbody>
            <?php 
            $no=0;
                if(!empty($billsData)){
                foreach($billsData as $data){
                      $no++;
                      $dt=date_create($data['date']);
                      $dt= date_format($dt,'d-M-Y');

                    
            ?>
             <?php if($data['isAllocated']==1){ ?>
                     <tr style="background-color: #dcd6d5">
                <?php }else{ ?>
                     <tr>
                <?php } ?>
                <td><?php echo $no;?></td>
                <td><?php echo $data['billNo'];?></td>
                <td><?php echo $dt; ?></td>
                <td><?php echo $data['retailerName'];?></td>
                <td><?php echo $data['netAmount'];?></td>
                <td><?php echo $data['pendingAmt'];?></td>
                <td><?php echo $data['salesman'];?></td>
                 
                <td>
                     <?php if($data['isAllocated']!=1){ ?>

                      <a href="<?php echo site_url('AdHocController/billHistoryInfo/'.$data['id']); ?>" class="btn btn-xs history-btn" data-toggle="tooltip" data-placement="bottom" title="View History"><i class="material-icons">info</i></a>
                   <?php  }else{
                        $allocations=$this->AllocationByManagerModel->getAllocationDetailsByBill('bills',$data['id']);
                        $officeAllocations=$this->AllocationByManagerModel->getOfficeAllocationDetailsByBill('bills',$data['id']);
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
            ?>
        
        </tbody>
    </table>
<?php
            }else{
                $billId=$billsData[0]['id'];

                $presentBill=$this->AllocationByManagerModel->load('bills',$billId);

                if(!empty($presentBill)){
                    $bills=$this->AllocationByManagerModel->getBillAllocationHistoryByBill('billpayments',$billId);
                    $billOfficeAdj=$this->AllocationByManagerModel->getBillOfficeAdjHistoryByBill('allocations_officebills',$billId);
                    $billSr=$this->AllocationByManagerModel->getBillAllocationSrByBill('allocation_sr_details',$billId);
                    $this->billAllocationInfo($billId,$bills,$billOfficeAdj,$billSr);
                }else{
                    echo "<span style='color:red'>Please select bill no.</span>";
                }
            }
        }else{
            echo "Bill not found";
        }
    }

    public function loadbillSearchBills(){
        $bills=$this->AllocationByManagerModel->getBillsData('bills');
        foreach($bills as $data){
            $name=$data['billNo'].' : '.$data['retailerName'];
        ?>   
        <option id="<?php echo $data['id'];?>" value="<?php echo $name;?>"/>
    <?php    
        }
    }

    public function billDetailsInfo($id){
        $data['bills']=$this->AllocationByManagerModel->load('bills',$id);
        $data['billsdetails']=$this->AllocationByManagerModel->getBillDetailInfo('billsdetails',$id);
        $this->load->view('commanBillView',$data);
    }

    public function billLedgerInfo($id){
        $data['bills']=$this->AllocationByManagerModel->load('bills',$id);
        $data['billHistory']=$this->AllocationByManagerModel->getBillHistoryInfo('bill_transaction_history',$id);
        $this->load->view('billLedgerView',$data);
    }

    public function checkValuesByBillSr(){
        $billNo=trim($this->input->post('billNo'));
        $cmpName=trim($this->input->post('comp'));

        $srNo=$this->AllocationByManagerModel->loadSrBillDetails('bill_serial_manage',$cmpName);
        $cmpSrNp=$srNo[0]['serialStartWith'];

        if(strpos($billNo, $cmpSrNp) !== false){
            echo "";
        }else{
            echo "Please use serial number '".$cmpSrNp."' for ".$cmpName.".";
        }

    }

    public function billInfo(){
        $billNo=trim($this->input->post('billNo'));
        $billId=trim($this->input->post('billId'));
        $presentBill=$this->AllocationByManagerModel->load('bills',$billId);
        if(!empty($presentBill)){
            $bills=$this->AllocationByManagerModel->getBillAllocationHistoryByBill('billpayments',$billId);
            $billOfficeAdj=$this->AllocationByManagerModel->getBillOfficeAdjHistoryByBill('allocations_officebills',$billId);
            $billSr=$this->AllocationByManagerModel->getBillAllocationSrByBill('allocation_sr_details',$billId);
            $this->billAllocationInfo($billId,$bills,$billOfficeAdj,$billSr);
        }else{
            echo "<span style='color:red'>Please select bill no.</span>";
        }
    }

    public function billInfoForAdmin(){
        $billNo=trim($this->input->post('billNo'));
        // $billId=trim($this->input->post('billId'));
        $billsData=$this->AllocationByManagerModel->findBills('bills',$billNo);
        if(!empty($billsData)){
            $billId=$billsData[0]['id'];
            $presentBill=$this->AllocationByManagerModel->load('bills',$billId);

            if(!empty($presentBill)){

                
                $bills=$this->AllocationByManagerModel->getBillAllocationHistoryByBill('billpayments',$billId);
                $billOfficeAdj=$this->AllocationByManagerModel->getBillOfficeAdjHistoryByBill('allocations_officebills',$billId);
                $billSr=$this->AllocationByManagerModel->getBillAllocationSrByBill('allocation_sr_details',$billId);
                $this->billAllocationInfo($billId,$bills,$billOfficeAdj,$billSr);
            }else{
                echo "<span style='color:red'>Please select bill no.</span>";
            }
        }else{
                echo "<span style='color:red'>Please select bill no.</span>";
        }
    }

    public function billHistoryInfo($id){
        $data['billData']=$this->AllocationByManagerModel->load('bills',$id);
        $data['billHistory']=$this->AllocationByManagerModel->getBillHistoryInfo('bill_transaction_history',$id);
   
        $data['currentAllocations']=$this->AllocationByManagerModel->getCurrentOpenAllocations('allocations');
        $data['company']=$this->AllocationByManagerModel->getdata('company');
        $data['bank']=$this->AllocationByManagerModel->getdata('bank');
        $data['emp']=$this->AllocationByManagerModel->getdata('employee');

        $billId=trim($id);

        $presentBill=$this->AllocationByManagerModel->load('bills',$billId);

        if(!empty($presentBill)){
            $data['billId']=$billId;
            $data['bills']=$this->AllocationByManagerModel->getBillAllocationHistoryByBill('billpayments',$billId);
            $data['billOfficeAdj']=$this->AllocationByManagerModel->getBillOfficeAdjHistoryByBill('allocations_officebills',$billId);

            // $data['bills']=array_merge($data['bills'],$data['billOfficeAdj']);

            $data['billSr']=$this->AllocationByManagerModel->getBillAllocationSrByBill('allocation_sr_details',$billId);
            $data['billInfo']=$this->AllocationByManagerModel->load('bills',$billId);
            $data['retailerCode']=$this->AllocationByManagerModel->loadRetailer($data['billInfo'][0]['retailerCode']);
            $data['resendBill']=$this->AllocationByManagerModel->getResendBill('allocationsbills',$billId);
            $data['signedBill']=$this->AllocationByManagerModel->getSignedBill('allocationsbills',$billId);
            // $this->billAllocationInfo($billId,$bills,$billOfficeAdj,$billSr);
            $this->load->view('Manager/billHistorySearchView',$data);
        }else{
            echo "<span style='color:red'>Please select bill no.</span>";
        }
    }

    public function retailerbillInfo(){
        $billNo=trim($this->input->post('billNo'));
        $billId=trim($this->input->post('billId'));

        $presentBill=$this->AllocationByManagerModel->load('bills',$billId);

        if(!empty($presentBill)){
            $bills=$this->AllocationByManagerModel->getBillAllocationHistoryByBill('billpayments',$billId);
            $billOfficeAdj=$this->AllocationByManagerModel->getBillOfficeAdjHistoryByBill('allocations_officebills',$billId);

            $billSr=$this->AllocationByManagerModel->getBillAllocationSrByBill('allocation_sr_details',$billId);
            $this->retailerBillHistory($billId,$bills,$billOfficeAdj,$billSr);
        }else{
            echo "<span style='color:red'>Please select bill no.</span>";
        }
    }


    public function retailerHistory(){
        $data['company']=$this->AllocationByManagerModel->getdata('company');
        $data['retailer']=$this->AllocationByManagerModel->getRetailerDetails('bills');
        $this->load->view('Manager/showRetailerHistoryView',$data);
    }

    public function allRetailerHistory()
    {
        $data['currentAllocations']=$this->AllocationByManagerModel->getCurrentOpenAllocations('allocations');
        $data['company']=$this->AllocationByManagerModel->getdata('company');
        $data['bank']=$this->AllocationByManagerModel->getdata('bank');
        $data['emp']=$this->AllocationByManagerModel->getdata('employee');

        $this->load->library('pagination');

        $config['base_url'] = base_url('index.php/AdHocController/allRetailerHistory');
        
        $config['per_page'] = ($this->input->get('limitRows')) ? $this->input->get('limitRows') : 50;
        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;
        // $config['get'] = "?retailerGet=" .trim($this->input->post('retailer')); 

        $data['company']=$this->AllocationByManagerModel->getdata('company');
        $data['retailer']=$this->AllocationByManagerModel->getRetailerDetails('bills');

        $retailerPost=trim($this->input->post('retailer'));
        $fromDate=trim($this->input->post('fromDate'));
        $toDate=trim($this->input->post('toDate'));

        $retailerGet=trim($this->input->get('retailer'));
        $retailerName="";
        $routeName="";
        $company="";
        $code="";


        if (isset($this->session->userdata['historyRetailer']) && (empty($retailerPost))) {
            $retailerName=($this->session->userdata['historyRetailer']['retailerName']);
            $routeName=($this->session->userdata['historyRetailer']['routeName']);
            $company=($this->session->userdata['historyRetailer']['company']);
            $code=($this->session->userdata['historyRetailer']['code']);
            $fromDate=($this->session->userdata['historyRetailer']['fromDate']);
            $toDate=($this->session->userdata['historyRetailer']['toDate']);
        }else{
            if(!empty($retailerPost)){
                $retailer=explode(' : ', $retailerPost); 
                if(count($retailer)>1){
                    $retailerName=$retailer[0];
                    $routeName=$retailer[1];
                    $company=$retailer[2];
                    $code=$retailer[3];

                    $session_data = array(
                        'retailerName' => $retailerName,
                        'routeName' => $routeName,
                        'company' => $company,
                        'code' => $code,
                        'fromDate' => $fromDate,
                        'toDate' => $toDate
                    );
                    $this->session->set_userdata('historyRetailer', $session_data);
                }
            }
        }

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
        $bills = $this->AllocationByManagerModel->paginationRetailerBills('bills',$config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection'],$code,$fromDate,$toDate);
        $rowCounts=$this->AllocationByManagerModel->countRetailerBills('bills',$config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection'],$code,$fromDate,$toDate);

        $data['bills'] = $bills;
        $config['total_rows'] = $rowCounts;
        $data['retailerName']=$retailerName;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('Manager/allRetailersHistoryView',$data);
    }

    public function OldallRetailerHistory(){
        $data['company']=$this->AllocationByManagerModel->getdata('company');
        $data['retailer']=$this->AllocationByManagerModel->getRetailerDetails('bills');

        $retailer=trim($this->input->post('retailer'));
        $fromDate=trim($this->input->post('fromDate'));
        $toDate=trim($this->input->post('toDate'));

        $retailer=explode(' : ', $retailer);
        $retailerName="";
        $routeName="";
        $company="";
        $code="";
        if(count($retailer)>1){
            $retailerName=$retailer[0];
            $routeName=$retailer[1];
            $company=$retailer[2];
            $code=$retailer[3];
            $bills=$this->AllocationByManagerModel->getBillsByRetailerCode('bills',$code,$company,$fromDate,$toDate);
            $data['bills']=$bills;
            $data['retailerName']=$retailerName;
            $this->load->view('Manager/allRetailersHistoryView',$data);
            // $this->retailerHistoryInformation($bills,$retailerName);
        }else{
            $this->load->view('Manager/allRetailersHistoryView',$data);
        }
        
    }

    public function retailerHistoryInfo(){
        $retailer=trim($this->input->post('retailer'));
        $fromDate=trim($this->input->post('fromDate'));
        $toDate=trim($this->input->post('toDate'));

        $retailer=explode(' : ', $retailer);
        $retailerName="";
        $routeName="";
        $company="";
        $code="";
        if(count($retailer)>1){
            $retailerName=$retailer[0];
            $routeName=$retailer[1];
            $company=$retailer[2];
            $code=$retailer[3];
            $bills=$this->AllocationByManagerModel->getBillsByRetailerCode('bills',$code,$company,$fromDate,$toDate);
            $this->retailerHistoryInformation($bills,$retailerName);
        }else{
            echo "<span style='color:red'>Please select retailer.</span>";
        }
    }

    public function retailerHistoryInfoByBillSearch($retailerName,$code,$routeName,$company,$fromDate,$toDate){
        // $retailerName=trim($this->input->post('retailer'));
        // $code=trim($this->input->post('retailerCode'));
        // $routeName=trim($this->input->post('routeName'));
        // $company=trim($this->input->post('compName'));
        $data['company']=$this->AllocationByManagerModel->getdata('company');
        $data['retailer']=$this->AllocationByManagerModel->getRetailerDetails('bills');
        // $fromDate=trim($this->input->post('fromDate'));
        // $toDate=trim($this->input->post('toDate'));
        
        $bills=$this->AllocationByManagerModel->getBillsByRetailerCode('bills',$code,$company,$fromDate,$toDate);
        $data['bills']=$bills;
        $data['retailerName']=$retailerName;
        $this->load->view('Manager/showRetailerHistoryForBillSearchView',$data);
    }

    public function adhocBills(){
        $data['currentAllocations']=$this->AllocationByManagerModel->getCurrentOpenAllocations('allocations');
        $data['adhocBills']=$this->AllocationByManagerModel->getNotAllocatedAdHocBillsByType('bills');
        // print_r($data['adhocBills']);exit;
        $data['company']=$this->AllocationByManagerModel->getdata('company');
        $data['emp']=$this->AllocationByManagerModel->getdata('employee');
        $data['employee']=$this->AllocationByManagerModel->getdata('employee');
        $this->load->view('Manager/adhocBillsDupView',$data);
    }

    public function debitNoteBills(){
        $data['company']=$this->AllocationByManagerModel->getdata('company');
        $cmp="Havells";
        
        $data['cmpName']=$cmp;
       
        $data['bills']=$this->AllocationByManagerModel->getDebitNoteBills('bills');
        $this->load->view('Manager/debitNoteBillsView',$data);
    }

    public function debitToEmployeeBills(){
        $data['company']=$this->AllocationByManagerModel->getdata('company');
        $data['emp']=$this->AllocationByManagerModel->getAllEmployees('employee');
        $cmp="Havells";
        
        $data['cmpName']=$cmp;
       
        $data['bills']=$this->AllocationByManagerModel->getDebitNoteBills('bills');
        $this->load->view('Manager/debitToEmployeeView',$data);
    }

    public function creditNoteBills(){
        $data['company']=$this->AllocationByManagerModel->getdata('company');
        $data['allocations']=$this->AllocationByManagerModel->getOpenAllocationsForBillJournal('allocations');
        $data['emp']=$this->AllocationByManagerModel->getAllEmployees('employee');
        $cmp="Havells";
        
        $data['cmpName']=$cmp;
       
        $data['bills']=$this->AllocationByManagerModel->getDebitNoteBills('bills');
        $this->load->view('Manager/creditNoteDetailsView',$data);
    }

    public function getBillsByComp(){
        $comp=$this->input->post('comp');
        $bills=$this->AllocationByManagerModel->getDebitNoteBillsWithCompany('bills',$comp);

        // $allocationId=$this->input->post('allocationId');
        // $bills=$this->AllocationByManagerModel->getAllocationBills('allocations',$allocationId);
        foreach($bills as $item){
        	$billNo=$item['billNo'];
            ?>   
                 <option id="<?php echo $item['id'] ?>" value="<?php echo $item['billNo'].' : '.$item['retailerName'] ?>" />
            <?php    
	    }
    }

    public function getBillsByCompForCreditNote(){
        // $comp=$this->input->post('comp');
        // $bills=$this->AllocationByManagerModel->getDebitNoteBillsWithCompany('bills',$comp);

        $allocationId=$this->input->post('allocationId');
        $bills=$this->AllocationByManagerModel->getAllocationBills('allocations',$allocationId);
        foreach($bills as $item){
        	$billNo=$item['billNo'];
            ?>   
                 <option id="<?php echo $item['id'] ?>" value="<?php echo $item['billNo'].' : '.$item['retailerName'] ?>" />
            <?php    
	    }
    }

    public function getDetailForAllocation(){
        // $comp=$this->input->post('comp');
        // $bills=$this->AllocationByManagerModel->getDebitNoteBillsWithCompany('bills',$comp);

        $allocationId=$this->input->post('allocationId');
        $allocations=$this->AllocationByManagerModel->load('allocations',$allocationId);
        foreach($allocations as $item){
            ?>   
                 <tr>
                     <td><?php echo $item['allocationCode']; ?></td>
                     <td><?php echo $item['allocationSalesman']; ?></td>
                     <td><?php echo $item['allocationEmployeeName']; ?></td>
                     <td><?php echo $item['allocationRouteName']; ?></td>
                 </tr>
            <?php    
	    }
    }

//deleted 
    public function getAllBillsForDebitNote(){
        $comp=$this->input->post('comp');
        $fromBill=$this->input->post('fromBill');
        $toBill=$this->input->post('toBill');

        $bills=$this->AllocationByManagerModel->getBillsForDebit('bills',$comp,$fromBill,$toBill);
        if(!empty($bills)){
            $no=0;
            foreach($bills as $bill){
                $no++;
        ?>
            <tr>
                <td><?php echo $no; ?></td>
                <td><?php echo $bill['billNo']; ?></td>
                <td><?php echo date('d-M-Y',strtotime($bill['date'])); ?></td>
                <td><?php echo $bill['retailerName']; ?></td>
                <td class="text-right"><?php echo number_format($bill['billNetAmount']); ?></td>
                <td class="text-right"><?php echo number_format($bill['netAmount']); ?></td>
                <td class="text-right"><?php echo number_format($bill['creditAdjustment']); ?></td>
                <td class="text-right"><?php echo number_format($bill['pendingAmt']); ?></td>
                <td>
                    <input type="hidden" class="form-control" style="width:80px;" id="idFordebitAmt[]" name="idFordebitAmt[]" value="<?php echo $bill['id']; ?>" placeholder="Amount">
                    <input type="hidden" class="form-control" style="width:80px;" id="debitAmt[]" name="debitAmt[]" onkeypress="return numbersonly(event)" placeholder="Amount">
                    <input type="hidden" class="form-control" id="debitAmtRemark[]" name="debitAmtRemark[]" placeholder="Remark">
                </td>
                <td> 
                    <a>
                    <button onclick="removeMe(this,'<?php echo $bill['id']; ?>');" class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                    </a>
                </td>

            </tr>
        <?php
            }
        }
    }

    public function getAllBillsForEmployeeDebitNote(){
        $comp=$this->input->post('comp');
        $fromBill=$this->input->post('fromBill');
        $toBill=$this->input->post('toBill');

        $bills=$this->AllocationByManagerModel->getBillsForDebit('bills',$comp,$fromBill,$toBill);
        if(!empty($bills)){
            $no=0;
            foreach($bills as $bill){
                $no++;
        ?>
            <tr>
                <td><?php echo $no; ?></td>
                <td><?php echo $bill['billNo']; ?></td>
                <td><?php echo date('d-M-Y',strtotime($bill['date'])); ?></td>
                <td><?php echo $bill['retailerName']; ?></td>
                <td class="text-right"><?php echo number_format($bill['billNetAmount']); ?></td>
                <td class="text-right"><?php echo number_format($bill['netAmount']); ?></td>
                <td class="text-right"><?php echo number_format($bill['creditAdjustment']); ?></td>
                <td class="text-right"><?php echo number_format($bill['pendingAmt']); ?></td>
                <td>
                    <input type="hidden" class="form-control" style="width:80px;" id="idFordebitAmt[]" name="idFordebitAmt[]" value="<?php echo $bill['id']; ?>" placeholder="Amount">
                    <input type="text" class="form-control" style="width:80px;" id="debitAmt[]" name="debitAmt[]" onkeypress="return numbersonly(event)" placeholder="Amount">
                    <input type="text" class="form-control" id="debitAmtRemark[]" name="debitAmtRemark[]" placeholder="Remark">
                </td>
                <td> 
                    <a>
                    <button onclick="removeMe(this,'<?php echo $bill['id']; ?>');" class="btn btn-xs btn-primary waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                    </a>
                </td>

            </tr>
        <?php
            }
        }
    }

    public function getAllBillsForBillJournalDebitNote(){
       
        $fromBill=$this->input->post('fromBill');
        $toBill=$this->input->post('toBill');
        $minAmount=$this->input->post('minAmount');

        // $comp=$this->input->post('comp');
        // $bills=$this->AllocationByManagerModel->getBillsForBillJournalDebit('bills',$comp,$fromBill,$toBill,$minAmount);

        $allocationId=$this->input->post('allocationId');
        $bills=$this->AllocationByManagerModel->getAllocationsBillsForBillJournalDebit('bills',$allocationId,$fromBill,$toBill,$minAmount);
        // print_r($bills);
        if(!empty($bills)){
            $no=0;
            foreach($bills as $bill){
                $no++;
        ?>
            <tr>
                <td><?php echo $no; ?></td>
                <td><?php echo $bill['billNo']; ?></td>
                <td><?php echo date('d-M-Y',strtotime($bill['date'])); ?></td>
                <td><?php echo $bill['retailerName']; ?></td>
                <td class="text-right"><?php echo number_format($bill['billNetAmount']); ?></td>
                <td class="text-right"><?php echo number_format($bill['netAmount']); ?></td>
                <td class="text-right"><?php echo number_format($bill['creditAdjustment']); ?></td>
                <td class="text-right"><?php echo number_format($bill['pendingAmt']); ?></td>
                <td>
                    <input type="hidden" class="form-control" style="width:80px;" id="idFordebitAmt[]" name="idFordebitAmt[]" value="<?php echo $bill['id']; ?>" placeholder="Amount">
                    <input type="hidden" class="form-control" style="width:80px;" id="debitAmt[]" name="debitAmt[]" onkeypress="return numbersonly(event)" placeholder="Amount">
                    <input type="hidden" class="form-control" id="debitAmtRemark[]" name="debitAmtRemark[]" placeholder="Remark">
                </td>
               
                <td> 
                    <a>
                    <button onclick="removeMe(this,'<?php echo $bill['id']; ?>');" class="btn btn-xs btn-primary waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                    </a>
                </td>

            </tr>
        <?php
            }
        }else{
            echo "<tr colspan='10'>Bills not available</tr>";
        }
    }

    public function getAllBillsForBillJournalDebitNoteByCompany(){
       
        $fromBill=$this->input->post('fromBill');
        $toBill=$this->input->post('toBill');
        $minAmount=$this->input->post('minAmount');

        $comp=$this->input->post('comp');
        $bills=$this->AllocationByManagerModel->getBillsForBillJournalDebit('bills',$comp,$fromBill,$toBill,$minAmount);

        $emp=$this->AllocationByManagerModel->getAllEmployees('employee');
        if(!empty($bills)){
            $no=0;
            foreach($bills as $bill){
                $no++;
        ?>
            <tr>
                <td><?php echo $bill['billNo']; ?></td>
                <td><?php echo date('d-M-Y',strtotime($bill['date'])); ?></td>
                <td><?php echo $bill['retailerName']; ?></td>
                <td class="text-right"><?php echo number_format($bill['netAmount']); ?></td>
                <td class="text-right"><?php echo number_format($bill['SRAmt']); ?></td>
                <td class="text-right"><?php echo number_format($bill['receivedAmt']); ?></td>
                <td class="wagein" style="display:none"><?php echo $bill['pendingAmt']; ?></td>
                <td class="text-right"><?php echo number_format($bill['pendingAmt']); ?></td>
                <td class="text-right" style="display:none">
                    <input type="text" class="form-control" id="debitIdForSelectedBill[]" name="debitIdForSelectedBill[]" value="<?php echo $bill['id']; ?>" required>
                </td> 
                <td class="text-right">
                    <input type="text" class="form-control" onkeypress="return numbersonly(event)" onblur="checkAmountPerItem(this,<?php echo $bill['id']; ?>,<?php echo $bill['pendingAmt']; ?>)" id="debitIdForSelectedBillAmt[]" name="debitIdForSelectedBillAmt[]" value="<?php echo (int)$bill['pendingAmt']; ?>" required>
                    <span style="color:red" id="data_err<?php echo $bill['id']; ?>"></span>
                </td> 
                <td> 
                    <a>
                    <button onclick="removeMe(this,'<?php echo $bill['id']; ?>');" class="btn btn-xs btn-primary waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                    </a>
                </td>
            </tr>
        <?php
            }
        }
    }

    public function getAllBillsForBillJournalDebitNoteByAllocation(){
       
        $fromBill=$this->input->post('fromBill');
        $toBill=$this->input->post('toBill');
        $minAmount=$this->input->post('minAmount');
        // echo $fromBill;

        $allocationId=$this->input->post('allocationId');
        $bills=$this->AllocationByManagerModel->getAllocationsBillsForBillJournalDebit('bills',$allocationId,$fromBill,$toBill,$minAmount);
        // echo $this->db->last_query();
        // print_r($bills);
        $emp=$this->AllocationByManagerModel->getAllEmployees('employee');

        if(!empty($bills)){
            $no=0;
            foreach($bills as $bill){
                $no++;
        ?>
            <tr>
                <td><?php echo $bill['billNo']; ?></td>
                <td><?php echo date('d-M-Y',strtotime($bill['date'])); ?></td>
                <td><?php echo $bill['retailerName']; ?></td>
                <td class="text-right"><?php echo number_format($bill['netAmount']); ?></td>
                <td class="text-right"><?php echo number_format($bill['SRAmt']); ?></td>
                <td class="text-right"><?php echo number_format($bill['receivedAmt']); ?></td>
                <td class="wagein" style="display:none"><?php echo $bill['pendingAmt']; ?></td>
                <td class="text-right"><?php echo number_format($bill['pendingAmt']); ?></td>
                <td class="text-right" style="display:none">
                    <input type="text" class="form-control" id="debitIdForSelectedBill[]" name="debitIdForSelectedBill[]" value="<?php echo $bill['id']; ?>" required>
                </td> 
                <td class="text-right">
                    <input type="text" class="form-control" onkeypress="return numbersonly(event)" onblur="checkAmountPerItem(this,<?php echo $bill['id']; ?>,<?php echo $bill['pendingAmt']; ?>)" id="debitIdForSelectedBillAmt[]" name="debitIdForSelectedBillAmt[]" value="<?php echo (int)$bill['pendingAmt']; ?>" required>
                    <span style="color:red" id="data_err<?php echo $bill['id']; ?>"></span>
                </td> 
            
                <td> 
                    <a>
                    <button onclick="removeMe(this,'<?php echo $bill['id']; ?>');" class="btn btn-xs btn-primary waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                    </a>
                </td>

            </tr>
        <?php
            }
        }
    }

    public function getAllBillsForManualJournalCreditNote(){
        // $comp=$this->input->post('comp');
        $billNo=$this->input->post('manualBillId');

        $bills=$this->AllocationByManagerModel->load('bills',$billNo);
        if(!empty($bills)){
            $no=0;
            foreach($bills as $bill){
                $no++;
        ?>
            <tr>
                <td><?php echo $bill['billNo']; ?></td>
                <td><?php echo date('d-M-Y',strtotime($bill['date'])); ?></td>
                <td><?php echo $bill['retailerName']; ?></td>
                <td class="text-right"><?php echo number_format($bill['netAmount']); ?></td>
                <td class="text-right"><?php echo number_format($bill['SRAmt']); ?></td>
                <td class="text-right"><?php echo number_format($bill['receivedAmt']); ?></td>
                <td class="wagein" style="display:none"><?php echo $bill['pendingAmt']; ?></td>
                <td class="text-right"><?php echo number_format($bill['pendingAmt']); ?></td>
                <td class="text-right" style="display:none">
                    <input type="text" class="form-control" id="debitIdForSelectedBill[]" name="debitIdForSelectedBill[]" value="<?php echo $bill['id']; ?>" required>
                </td> 
                <td class="text-right">
                    <input type="text" class="form-control" onkeypress="return numbersonly(event)" onblur="checkAmountPerItem(this,<?php echo $bill['id']; ?>,<?php echo $bill['pendingAmt']; ?>)" id="debitIdForSelectedBillAmt[]" name="debitIdForSelectedBillAmt[]" value="<?php echo (int)$bill['pendingAmt']; ?>" required>
                    <span style="color:red" id="data_err<?php echo $bill['id']; ?>"></span>
                </td> 
                <td> 
                    <a>
                    <button onclick="removeMe(this,'<?php echo $bill['id']; ?>');" class="btn btn-xs btn-primary waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                    </a>
                </td>
            </tr>
        <?php
            }
        }
    }

    public function addRowForEmpJournal(){
        $balance=0;
        $empId=$this->input->post('empId');

        $empData=$this->AllocationByManagerModel->load('employee',$empId);

        $ledger=$this->AllocationByManagerModel->getEmpLedgerByEmp('emptransactions',$empId);
        if(!empty($ledger)){
            foreach($ledger as $leg){
                if($leg['transactionType']=='cr'){
                    $balance=$balance+$leg['amount'];
                }else if($leg['transactionType']=='dr'){
                        $balance=$balance-$leg['amount'];
                }
            }
        }

        if(!empty($empData)){
            foreach($empData as $emp){
    ?>
            <tr>
                <td><?php echo $emp['name']; ?></td>
                <td>
                    <?php 
                        if($balance<0){ ?><?php  echo '<span style="color:red">'.str_replace('-','',intval($balance)).' dr</span>'; 
                        }else if($balance>0){ 
                            echo '<span style="color:blue">'.intval($balance).' cr</span>'; 
                        }else{
                            if($emp['isSalaryEmp']==1){ 
                    ?>
                            <span style="color:blue">0</span>
                    <?php   
                        }else{ 
                    ?>
                            <span style="color:blue">0</span>
                    <?php
                            }
                        }
                    ?>
                </td>
                <td>
                    <input type="hidden" class="form-control" style="width:80px;" id="idForAddEmpdebitAmt[]" name="idForAddEmpdebitAmt[]" value="<?php echo $emp['id']; ?>" placeholder="Amount">
                    <input type="text" class="form-control" oninput="checkPerItem(this);" style="width:80px;" id="addEmpdebitAmt[]" name="addEmpdebitAmt[]" onkeypress="return numbersonly(event)" placeholder="Amount">
                </td>
                <td> 
                    <a>
                    <button onclick="removeMe(this,'<?php echo $emp['id']; ?>');" class="btn btn-xs btn-primary waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                    </a>
                </td>
            </tr>
        <?php
            }
        }

        // echo $balance;
    }

    public function addRowForBillJournal(){
        $billId=$this->input->post('billId');
        $bills=$this->AllocationByManagerModel->load('bills',$billId);

        if(!empty($bills)){
            foreach($bills as $bill){
    ?>
            <tr>
                <td><?php echo $bill['billNo']; ?></td>
                <td><?php echo date('d-M-Y',strtotime($bill['date'])); ?></td>
                <td><?php echo $bill['retailerName']; ?></td>
                <td class="text-right"><?php echo number_format($bill['netAmount']); ?></td>
                <td class="wagein" style="display:none"><?php echo $bill['pendingAmt']; ?></td>
                <td class="text-right"><?php echo number_format($bill['pendingAmt']); ?></td>
                <td>
                    <input type="hidden" class="form-control" style="width:80px;" id="idForAddBilldebitAmt[]" name="idForAddBilldebitAmt[]" value="<?php echo $bill['id']; ?>" placeholder="Amount">
                    <input type="text" class="form-control" oninput="checkPerItem(this)" style="width:80px;" id="addBilldebitAmt[]" name="addBilldebitAmt[]" onkeypress="return numbersonly(event)" placeholder="Amount">
                    
                </td>
                <td> 
                    <a>
                    <button onclick="removeMe(this,'<?php echo $bill['id']; ?>');" class="btn btn-xs btn-primary waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                    </a>
                </td>
            </tr>
        <?php
            }
        }
    }

    public function finalDebitTransactionSubmit(){
        $allocationId=$this->input->post('allocationId');
        $comp=$this->input->post('comp');
        $remark=$this->input->post('remark');

        $billId=$this->input->post('selectedId');
        $pending=$this->input->post('selectPendingBillAmt');

        $debitBillId=$this->input->post('selectBillId');
        $debitBillAmt=$this->input->post('selectBillAmt');

        $debitEmpId=$this->input->post('selectEmpId');
        $debitEmpAmt=$this->input->post('selectEmpAmt');

        $journal_entry=$this->AllocationByManagerModel->getdata('journal_entry');

        $journalId="";
        if(!empty($journal_entry)){
            $journalId='JEN-'.date('dmy').'-'.(count($journal_entry)+1);
        }else{
            $journalId='JEN-'.date('dmy').'-1';
        }

        $journalData=array(
            'journalEntryCode'=>$journalId,
            'journalEntryDate'=>date('Y-m-d H:i:sa')
        );
        $this->AllocationByManagerModel->insert('journal_entry',$journalData);

        $remark=$journalId.' - '.$remark;
        
        if(!empty($billId)){
            $no=0;
            foreach($billId as $id){
                $bills=$this->AllocationByManagerModel->load('bills',$id);
                if(!empty($bills)){
                    if($bills[0]['isAllocated']==1){
                        $netAmount=$bills[0]['netAmount'];
                        $pendingAmt=$bills[0]['pendingAmt'];
                        $creditNoteJournalAmt=$bills[0]['creditNoteJournalAmt'];
                    
                        $newPending=$pendingAmt-$pending[$no];
                        $newCreditNoteJournalAmt=$creditNoteJournalAmt+$pending[$no];

                        $dataForUpdate=array(
                            'fsbillStatus'=>'Billed',
                            'pendingAmt'=>$newPending,
                            'creditNoteJournalAmt'=>$newCreditNoteJournalAmt
                        );
                        $this->AllocationByManagerModel->update('bills',$dataForUpdate,$id);
                    }else{
                        $netAmount=$bills[0]['netAmount'];
                        $pendingAmt=$bills[0]['pendingAmt'];
                        $creditNoteJournalAmt=$bills[0]['creditNoteJournalAmt'];
                    
                        $newPending=$pendingAmt-$pending[$no];
                        $newCreditNoteJournalAmt=$creditNoteJournalAmt+$pending[$no];

                        $dataForUpdate=array(
                            'pendingAmt'=>$newPending,
                            'creditNoteJournalAmt'=>$newCreditNoteJournalAmt
                        );
                        $this->AllocationByManagerModel->update('bills',$dataForUpdate,$id);
                    }
                    

                    $history=array(
                        'billId'=>$id,
                        'transactionAmount' =>$pending[$no],
                        'transactionStatus' =>'Credit Journal Entry',
                        'transactionMode' =>'dr',
                        'remark'=>$remark,
                        'allocationId'=>$allocationId,
                        'journalId'=>$journalId,
                        'transactionDate'=>date('Y-m-d H:i:sa'),
                        'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                        'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                    );
                    $this->AllocationByManagerModel->insert('bill_transaction_history',$history);

                    $billPayment=array(
                        'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                        'billId'=>$id,
                        'allocationId'=>$allocationId,
                        'journalId'=>$journalId,
                        'compName'=>$comp,
                        'date'=>date('Y-m-d H:i:sa'),
                        'paidAmount'=>$pending[$no],
                        'billAmount'=>$netAmount,
                        'balanceAmount'=>$newPending,
                        'paymentMode'=>'Credit Journal Entry',
                        'tallyRemark'=>$remark,
                        'isLostStatus'=>2,
                        'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                    );
                    $this->AllocationByManagerModel->insert('billpayments',$billPayment);
                }
                $no++;
            }

            if(!empty($debitBillId)){
                $no1=0;
                foreach($debitBillId as $id){
                    $bills=$this->AllocationByManagerModel->load('bills',$id);
                    if(!empty($bills)){
                        $netAmount=$bills[0]['netAmount'];
                        $pendingAmt=$bills[0]['pendingAmt'];
                        $debitNoteJournalAmt=$bills[0]['debitNoteJournalAmt'];
                    
                        $newPending=$pendingAmt+$debitBillAmt[$no1];
                        $newDebitNoteJournalAmt=$debitNoteJournalAmt+$debitBillAmt[$no1];
    
                        $dataForUpdate=array(
                            'pendingAmt'=>$newPending,
                            'debitNoteJournalAmt'=>$newDebitNoteJournalAmt
                        );
                        $this->AllocationByManagerModel->update('bills',$dataForUpdate,$id);
    
                        $history=array(
                            'billId'=>$id,
                            'transactionAmount' =>'-'.$debitBillAmt[$no1],
                            'transactionStatus' =>'Debit Journal Entry',
                            'transactionMode' =>'cr',
                            'remark'=>$remark,
                            'allocationId'=>$allocationId,
                            'journalId'=>$journalId,
                            'transactionDate'=>date('Y-m-d H:i:sa'),
                            'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                            'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                        );
                        $this->AllocationByManagerModel->insert('bill_transaction_history',$history);
    
                        $billPayment=array(
                            'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                            'billId'=>$id,
                            'allocationId'=>$allocationId,
                            'journalId'=>$journalId,
                            'compName'=>$comp,
                            'date'=>date('Y-m-d H:i:sa'),
                            'paidAmount'=>'-'.$debitBillAmt[$no1],
                            'billAmount'=>$netAmount,
                            'balanceAmount'=>$newPending,
                            'paymentMode'=>'Debit Journal Entry',
                            'tallyRemark'=>$remark,
                            'isLostStatus'=>2,
                            'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                        );
                        $this->AllocationByManagerModel->insert('billpayments',$billPayment);
                    }
                    $no1++;
                }
            }

            if(!empty($debitEmpId)){
                $no2=0;
                foreach($debitEmpId as $id){
                    $empData=$this->AllocationByManagerModel->load('employee',2);
                    if(!empty($empData)){

                        $empDebit=array(
                            // 'billId'=>$currentBillId,
                            'empId'=>$debitEmpId[$no2],
                            'transactionType'=>'dr',
                            'description'=>$remark,
                            'journalId'=>$journalId,
                            'amount'=>$debitEmpAmt[$no2],
                            'createdAt'=>date('Y-m-d H:i:sa'),
                            'createdBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                        );
                         $this->AllocationByManagerModel->insert('emptransactions',$empDebit);//insert remark data
                    }
                    $no2++;
                }
            }
        }
    }

    
    public function getBillsForCreditNote(){
        $billId=$this->input->post('billId');

        $bills=$this->AllocationByManagerModel->load('bills',$billId);
        if(!empty($bills)){
            $no=0;
            foreach($bills as $bill){
                $no++;
        ?>
            <tr>
                <td><?php echo $no; ?></td>
                <td><?php echo $bill['billNo']; ?></td>
                <td><?php echo date('d-M-Y',strtotime($bill['date'])); ?></td>
                <td><?php echo $bill['retailerName']; ?></td>
                <td class="text-right"><?php echo number_format($bill['billNetAmount']); ?></td>
                <td class="text-right"><?php echo number_format($bill['netAmount']); ?></td>
                <td class="text-right"><?php echo number_format($bill['creditAdjustment']); ?></td>
                <td class="text-right"><?php echo number_format($bill['pendingAmt']); ?></td>
                <td>
                    <input type="hidden" class="form-control" style="width:80px;" id="idForBillJournal[]" name="idForBillJournal[]" value="<?php echo $bill['id']; ?>" placeholder="Amount">
                    <input type="text" class="form-control" style="width:80px;" id="amountForBillJournal[]" name="amountForBillJournal[]" onkeypress="return numbersonly(event)" placeholder="Amount">
                </td>
                <td> 
                    <a>
                    <button onclick="removeMe(this,'<?php echo $bill['id']; ?>');" class="btn btn-xs btn-primary waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                    </a>
                </td>
            </tr>
        <?php
            }
        }else{
            echo "<tr colspan='10'>Bills not available</tr>";
        }
    }

    public function saveDebitNoteCollectionForFlatAmount(){
        $amount=$this->input->post('percentAmt');
        $remark=$this->input->post('percentRemark');
        $selValue=$this->input->post('selValue');
        if(!empty($selValue)){
            foreach($selValue as $sel){
                $pendingPaymentId=$sel;
                $bills=$this->AllocationByManagerModel->load('bills',$sel);
                if(!empty($bills)){
                    $pending=$bills[0]['pendingAmt'];
                    $debitNoteAmount=$bills[0]['debitNoteAmount'];
                    $newPending=$pending+(int)$amount;
                    $newDebitNoteAmount=$debitNoteAmount+(int)$amount;
                    $paidAmount='-'.$amount;

                    $billUpdate=array(
                        'pendingAmt' =>$newPending,
                        'debitNoteAmount' =>$newDebitNoteAmount
                    );
                    $this->AllocationByManagerModel->update('bills',$billUpdate,$bills[0]['id']);
                    
                    $history=array(
                        'billId'=>$bills[0]['id'],
                        'transactionAmount' =>$amount,
                        'transactionStatus' =>'Debit Note',
                        'transactionMode' =>'cr',
                        'remark'=>$remark,
                        'transactionDate'=>date('Y-m-d H:i:sa'),
                        'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                        'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                    );
                    $this->AllocationByManagerModel->insert('bill_transaction_history',$history);

                    $billPayment=array(
                        'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                        'billNo'=>$bills[0]['billNo'],
                        'compName'=>$bills[0]['compName'],
                        'billId'=>$bills[0]['id'],
                        'date'=>date('Y-m-d H:i:sa'),
                        'paidAmount'=>$paidAmount,
                        'billAmount'=>$bills[0]['netAmount'],
                        'balanceAmount'=>$newPending,
                        'tallyRemark'=>$remark,
                        'paymentMode'=>'Debit Note',
                        'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                    );
                    $this->AllocationByManagerModel->insert('billpayments',$billPayment);//insert billpayment data

                }   

            }
        }
    }

    public function saveDebitNoteCollectionForPercentAmount(){
        $percent=$this->input->post('percentAmt');
        $remark=$this->input->post('percentRemark');
        $selValue=$this->input->post('selValue');
        if(!empty($selValue)){
            foreach($selValue as $sel){
                $pendingPaymentId=$sel;
                $bills=$this->AllocationByManagerModel->load('bills',$sel);
                if(!empty($bills)){
                    $amount=round(($bills[0]['billNetAmount']/100)*$percent);
                    // echo $amount;exit;
                    $pending=$bills[0]['pendingAmt'];
                    $debitNoteAmount=$bills[0]['debitNoteAmount'];
                    $newPending=$pending+(int)$amount;
                    $newDebitNoteAmount=$debitNoteAmount+(int)$amount;
                    $paidAmount='-'.$amount;

                    $billUpdate=array(
                        'pendingAmt' =>$newPending,
                        'debitNoteAmount' =>$newDebitNoteAmount
                    );
                    $this->AllocationByManagerModel->update('bills',$billUpdate,$bills[0]['id']);
                    
                    $history=array(
                        'billId'=>$bills[0]['id'],
                        'transactionAmount' =>$amount,
                        'transactionStatus' =>'Debit Note',
                        'transactionMode' =>'cr',
                        'transactionDate'=>date('Y-m-d H:i:sa'),
                        'remark'=>$remark,
                        'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                        'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                    );
                    $this->AllocationByManagerModel->insert('bill_transaction_history',$history);

                    $billPayment=array(
                        'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                        'billNo'=>$bills[0]['billNo'],
                        'compName'=>$bills[0]['compName'],
                        'billId'=>$bills[0]['id'],
                        'date'=>date('Y-m-d H:i:sa'),
                        'paidAmount'=>$paidAmount,
                        'tallyRemark'=>$remark,
                        'billAmount'=>$bills[0]['netAmount'],
                        'balanceAmount'=>$newPending,
                        'paymentMode'=>'Debit Note',
                        'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                    );
                    $this->AllocationByManagerModel->insert('billpayments',$billPayment);//insert billpayment data

                }   

            }
        }
    }

    public function saveDebitNoteCollectionManualAmount(){
        $amount=$this->input->post('selDebitAmt');
        $selValue=$this->input->post('selValue');
        
        $selDebitAmtRemark=$this->input->post('selDebitAmtRemark');

        if(!empty($selValue)){
            $no=0;
            foreach($selValue as $sel){
               
                if($amount[$no] !=""){
                    $pendingPaymentId=$sel;
                    $bills=$this->AllocationByManagerModel->load('bills',$sel);
                    if(!empty($bills)){
                        $pending=$bills[0]['pendingAmt'];
                        $debitNoteAmount=$bills[0]['debitNoteAmount'];
                        $newPending=$pending+(int)$amount[$no];
                        $newDebitNoteAmount=$debitNoteAmount+(int)$amount[$no];
                        $paidAmount='-'.$amount[$no];

                        $billUpdate=array(
                            'pendingAmt' =>$newPending,
                            'debitNoteAmount' =>$newDebitNoteAmount
                        );
                        $this->AllocationByManagerModel->update('bills',$billUpdate,$bills[0]['id']);
                        
                        $history=array(
                            'billId'=>$bills[0]['id'],
                            'transactionAmount' =>$amount[$no],
                            'transactionStatus' =>'Debit Note',
                            'transactionMode' =>'cr',
                            'remark'=>$selDebitAmtRemark[$no],
                            'transactionDate'=>date('Y-m-d H:i:sa'),
                            'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                            'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                        );
                        $this->AllocationByManagerModel->insert('bill_transaction_history',$history);

                        $billPayment=array(
                            'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                            'billNo'=>$bills[0]['billNo'],
                            'compName'=>$bills[0]['compName'],
                            'billId'=>$bills[0]['id'],
                            'date'=>date('Y-m-d H:i:sa'),
                            'paidAmount'=>$paidAmount,
                            'tallyRemark'=>$selDebitAmtRemark[$no],
                            'billAmount'=>$bills[0]['netAmount'],
                            'balanceAmount'=>$newPending,
                            'paymentMode'=>'Debit Note',
                            'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                        );
                        $this->AllocationByManagerModel->insert('billpayments',$billPayment);//insert billpayment data

                    } 
                }
                $no++;
            }
        }
    }

    public function saveEmployeeDebitNoteCollectionManualAmount(){
        $amount=$this->input->post('selDebitAmt');
        $selValue=$this->input->post('selValue');
        
        $selDebitAmtRemark=$this->input->post('selDebitAmtRemark');
        $empId=$this->input->post('empId');

        if(!empty($selValue)){
            $no=0;
            foreach($selValue as $sel){
               
                if($amount[$no] !=""){
                    $pendingPaymentId=$sel;
                    $bills=$this->AllocationByManagerModel->load('bills',$sel);
                    if(!empty($bills)){
                        $pending=$bills[0]['pendingAmt'];
                        $debit=$bills[0]['debit'];
                        $newPending=$pending-(int)$amount[$no];
                        $newDebitAmount=$debit+(int)$amount[$no];
                        $paidAmount=$amount[$no];

                        $billUpdate=array(
                            'pendingAmt' =>$newPending,
                            'debit' =>$newDebitAmount
                        );
                        $this->AllocationByManagerModel->update('bills',$billUpdate,$bills[0]['id']);
                        
                        $history=array(
                            'billId'=>$bills[0]['id'],
                            'transactionAmount' =>$amount[$no],
                            'transactionStatus' =>'Debit To Employee',
                            'transactionMode' =>'dr',
                            'remark'=>$selDebitAmtRemark[$no],
                            'transactionDate'=>date('Y-m-d H:i:sa'),
                            'empId'=>$empId[$no],
                            'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                        );
                        $this->AllocationByManagerModel->insert('bill_transaction_history',$history);

                        $billPayment=array(
                            'empId'=>$empId[$no],
                            'billNo'=>$bills[0]['billNo'],
                            'compName'=>$bills[0]['compName'],
                            'billId'=>$bills[0]['id'],
                            'date'=>date('Y-m-d H:i:sa'),
                            'paidAmount'=>$paidAmount,
                            'tallyRemark'=>$selDebitAmtRemark[$no],
                            'billAmount'=>$bills[0]['netAmount'],
                            'balanceAmount'=>$newPending,
                            'paymentMode'=>'Debit To Employee',
                            'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                        );
                        $this->AllocationByManagerModel->insert('billpayments',$billPayment);//insert billpayment data

                        $billRemark=array(
                            'billId'=>$bills[0]['id'],
                            'empId'=>$empId[$no],
                            'remark'=>$selDebitAmtRemark[$no],
                            'amount'=>$paidAmount,
                            'updatedAt'=>date('Y-m-d H:i:sa'),
                            'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                        );
                        $this->AllocationByManagerModel->insert('bill_remark_history',$billRemark);//insert remark data
    
                        $empDebit=array(
                            'billId'=>$bills[0]['id'],
                            'empId'=>$empId[$no],
                            'transactionType'=>'dr',
                            'description'=>$selDebitAmtRemark[$no],
                            'amount'=>$paidAmount,
                            'createdAt'=>date('Y-m-d H:i:sa'),
                            'createdBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                        );
                         $this->AllocationByManagerModel->insert('emptransactions',$empDebit);//insert remark data
    
                         $lastBal=$this->AllocationByManagerModel->lastRecordDayBookValue();
                         $openCloseBal=$lastBal['openCloseBalance'];
                         if($openCloseBal=='' || $openCloseBal==Null){
                             $openCloseBal=0.0;
                         }
                         $openCloseBal=$openCloseBal+$paidAmount;

                         $billNarration="Bill No.  : ".$bills[0]['billNo']." market collection";
                         $expenseData=array(
                             'company'=>$bills[0]['compName'],
                             'employeeId'=>$empId[$no],
                             'narration'=>$billNarration,
                             'amount'=>$paidAmount,
                             "nature"=>"Market Collection",
                             'inoutStatus'=>'Inflow',
                             'date'=>date('Y-m-d H:i:sa'),
                             'openCloseBalance'=>$openCloseBal,
                             'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                        );
                         $this->AllocationByManagerModel->insert('expences',$expenseData);
     
                         $lastBal=$this->AllocationByManagerModel->lastRecordDayBookValue();
                         $openCloseBal=$lastBal['openCloseBalance'];
                         if($openCloseBal=='' || $openCloseBal==Null){
                             $openCloseBal=0.0;
                         }
                         $openCloseBal=$openCloseBal-$paidAmount;

                         $billNarration="Bill No.  : ".$bills[0]['billNo']." debit entry for employee debit";
                         $expenseData=array(
                             'company'=>$bills[0]['compName'],
                             'employeeId'=>$empId[$no],
                             'narration'=>$billNarration,
                             'amount'=>$paidAmount,
                             "nature"=>"Employee Advances",
                             'inoutStatus'=>'Outflow',
                             'date'=>date('Y-m-d H:i:sa'),
                             'openCloseBalance'=>$openCloseBal,
                             'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                        );
                         $this->AllocationByManagerModel->insert('expences',$expenseData);
                    } 
                }
                $no++;
            }
        }
    }

    public function debitNoteBillsHistory(){
        $data['currentAllocations']=$this->AllocationByManagerModel->getCurrentOpenAllocations('allocations');
        $data['company']=$this->AllocationByManagerModel->getdata('company');
        $data['emp']=$this->AllocationByManagerModel->getdata('employee');
        $cmp=$this->input->post('cmp');
        $data['cmpName']=$cmp;
        $from_date=$this->input->post('from_date');
        $to_date=$this->input->post('to_date');


        if(($cmp=='General' || $cmp=="") && $from_date=='' && $to_date==''){
            $time = strtotime("-1 year", time());
            $from_date = date("Y-m-d", $time);
            $to_date=date('Y-m-d');
            $data['from']=$from_date;
            $data['to']=$to_date;
            $data['officeAdjustmentBills']=$this->AllocationByManagerModel->getDebitBillsByType('bills',$from_date,$to_date);
            $this->load->view('Manager/debitNoteBillsListView',$data);

        }else if($cmp=='General' && $from_date !='' && $to_date!=''){
            $time = strtotime("-1 year", time());
            $from_date = date("Y-m-d", $time);
            $to_date=date('Y-m-d');
            $data['from']=$from_date;
            $data['to']=$to_date;
            $data['officeAdjustmentBills']=$this->AllocationByManagerModel->getDebitBillsByType('bills',$from_date,$to_date);
            $this->load->view('Manager/debitNoteBillsListView',$data);
        }else {
            $from_date=$this->input->post('from_date');
            $to_date=$this->input->post('to_date');
            $data['from']=$from_date;
            $data['to']=$to_date;
            $data['officeAdjustmentBills']=$this->AllocationByManagerModel->getDebitBillsByTypeWithComp('bills',$from_date,$to_date,$cmp);
            $this->load->view('Manager/debitNoteBillsListView',$data);
        }
    }

    public function officeAdjustmentBills(){
        $data['currentAllocations']=$this->AllocationByManagerModel->getCurrentOpenAllocations('allocations');
        $data['company']=$this->AllocationByManagerModel->getdata('company');
        $data['emp']=$this->AllocationByManagerModel->getdata('employee');
        $cmp=$this->input->post('cmp');
        $data['cmpName']=$cmp;
        $from_date=$this->input->post('from_date');
        $to_date=$this->input->post('to_date');


        if(($cmp=='General' || $cmp=="") && $from_date=='' && $to_date==''){
            $time = strtotime("-1 year", time());
            $from_date = date("Y-m-d", $time);
            $to_date=date('Y-m-d');
            $data['from']=$from_date;
            $data['to']=$to_date;
            $data['officeAdjustmentBills']=$this->AllocationByManagerModel->getOfficeBillsByType('bills','officeAdjustmentBill',$from_date,$to_date);
            $this->load->view('Manager/officeAdjustmentBillsView',$data);

        }else if($cmp=='General' && $from_date !='' && $to_date!=''){
            $time = strtotime("-1 year", time());
            $from_date = date("Y-m-d", $time);
            $to_date=date('Y-m-d');
            $data['from']=$from_date;
            $data['to']=$to_date;
            $data['officeAdjustmentBills']=$this->AllocationByManagerModel->getOfficeBillsByType('bills','officeAdjustmentBill',$from_date,$to_date);
            $this->load->view('Manager/officeAdjustmentBillsView',$data);
        }else {
            $from_date=$this->input->post('from_date');
            $to_date=$this->input->post('to_date');
            $data['from']=$from_date;
            $data['to']=$to_date;
            $data['officeAdjustmentBills']=$this->AllocationByManagerModel->getOfficeBillsByTypeWithComp('bills','officeAdjustmentBill',$from_date,$to_date,$cmp);
            $this->load->view('Manager/officeAdjustmentBillsView',$data);
        }
    }

    public function otherAdjustmentBills(){
        $data['currentAllocations']=$this->AllocationByManagerModel->getCurrentOpenAllocations('allocations');
        $data['emp']=$this->AllocationByManagerModel->getdata('employee');
        $data['company']=$this->AllocationByManagerModel->getdata('company');
        $cmp=$this->input->post('cmp');
        $data['cmpName']=$cmp;
        $from_date=$this->input->post('from_date');
        $to_date=$this->input->post('to_date');

        if(($cmp=='General' || $cmp=="") && $from_date=='' && $to_date==''){
            $time = strtotime("-1 year", time());
            $from_date = date("Y-m-d", $time);
            $to_date=date('Y-m-d');
            $data['from']=$from_date;
            $data['to']=$to_date;
            $data['officeAdjustmentBills']=$this->AllocationByManagerModel->getOtherBillsByType('bills','officeAdjustmentBill',$from_date,$to_date);
            $this->load->view('Manager/otherAdjustmentBillsView',$data);
        }else if($cmp=='General' && $from_date !='' && $to_date!=''){
            $time = strtotime("-1 year", time());
            $from_date = date("Y-m-d", $time);
            $to_date=date('Y-m-d');
            $data['from']=$from_date;
            $data['to']=$to_date;
            $data['officeAdjustmentBills']=$this->AllocationByManagerModel->getOtherBillsByType('bills','officeAdjustmentBill',$from_date,$to_date);
            $this->load->view('Manager/otherAdjustmentBillsView',$data);
        }else{
            $from_date=$this->input->post('from_date');
            $to_date=$this->input->post('to_date');
            $data['from']=$from_date;
            $data['to']=$to_date;
            $data['officeAdjustmentBills']=$this->AllocationByManagerModel->getOtherBillsByTypeWithComp('bills','officeAdjustmentBill',$from_date,$to_date,$cmp);
            $this->load->view('Manager/otherAdjustmentBillsView',$data);
        }
    }

    public function cashDiscountHistoryBills(){
        $data['currentAllocations']=$this->AllocationByManagerModel->getCurrentOpenAllocations('allocations');
        $data['emp']=$this->AllocationByManagerModel->getdata('employee');
        $data['company']=$this->AllocationByManagerModel->getdata('company');
        $cmp=$this->input->post('cmp');
        $data['cmpName']=$cmp;
        $from_date=$this->input->post('from_date');
        $to_date=$this->input->post('to_date');

        if(($cmp=='General' || $cmp=="") && $from_date=='' && $to_date==''){
            $time = strtotime("-1 year", time());
            $from_date = date("Y-m-d", $time);
            $to_date=date('Y-m-d');
            $data['from']=$from_date;
            $data['to']=$to_date;
            $data['officeAdjustmentBills']=$this->AllocationByManagerModel->getCashDiscountBillsByType('bills','officeAdjustmentBill',$from_date,$to_date);
            $this->load->view('Manager/cashDiscountBillsView',$data);
        }else if($cmp=='General' && $from_date !='' && $to_date!=''){
            $time = strtotime("-1 year", time());
            $from_date = date("Y-m-d", $time);
            $to_date=date('Y-m-d');
            $data['from']=$from_date;
            $data['to']=$to_date;
            $data['officeAdjustmentBills']=$this->AllocationByManagerModel->getCashDiscountBillsByType('bills','officeAdjustmentBill',$from_date,$to_date);
            $this->load->view('Manager/cashDiscountBillsView',$data);
        }else{
            $from_date=$this->input->post('from_date');
            $to_date=$this->input->post('to_date');
            $data['from']=$from_date;
            $data['to']=$to_date;
            $data['officeAdjustmentBills']=$this->AllocationByManagerModel->getCashDiscountBillsByTypeWithComp('bills','officeAdjustmentBill',$from_date,$to_date,$cmp);
            $this->load->view('Manager/cashDiscountBillsView',$data);
        }
    }

    public function adhocDeliveryBills(){
        $data['currentAllocations']=$this->AllocationByManagerModel->getCurrentOpenAllocations('allocations');
        $cmp=trim($this->input->post('cmp'));
        $data['company']=$this->AllocationByManagerModel->getdata('company');
        $data['emp']=$this->AllocationByManagerModel->getdata('employee');
        $data["cmpName"]=$cmp;
        if($cmp=="" || $cmp=="General"){
            $data['adhocBills']=$this->AllocationByManagerModel->getAdHocDeliveryBillsByType('bills','adHocDeliveryBill');
            $this->load->view('Manager/adhocBillsByEmployeeView',$data);
        }else{
            $data['adhocBills']=$this->AllocationByManagerModel->getAdHocDeliveryBillsByTypeWithCompany('bills','adHocDeliveryBill',$cmp);
            $this->load->view('Manager/adhocBillsByEmployeeView',$data);
        }
        
    }

    public function unalocatedAdHocBills(){
        $data['adhocBills']=$this->AllocationByManagerModel->getNotAllocatedAdHocBillsByType('bills');
        $this->load->view('Manager/showAdHocBillsView',$data);
    }

    public function insertAdhocBill(){
        $billNo=trim($this->input->post('billNo'));
        $netAmount=trim($this->input->post('netAmount'));
        $retailerName=trim($this->input->post('retailerName'));
        $cmpName=trim($this->input->post('cmpName'));
        $billOption=trim($this->input->post('billOption'));
        $allocationId=trim($this->input->post('allocationType'));
        $remark=trim($this->input->post('remark'));
        $remarkOffice=trim($this->input->post('remarkOffice'));
        $empId=trim($this->input->post('empId'));
        $empName=trim($this->input->post('empDetail'));
        $adjustmentAmount=trim($this->input->post('adjustmentAmount'));
        
        $srNo=$this->AllocationByManagerModel->loadSrBillDetails('bill_serial_manage',$cmpName);
        $cmpSrNp=$srNo[0]['serialStartWith'];

        $currentDate=date('Y-m-d');
        $updatedBy=$this->session->userdata[$this->projectSessionName]['id'];
        
        if(strpos($billNo, $cmpSrNp) !== false){
            if(($billOption==="Direct Delivery") && ($empId !=="") && ($remark !=="")){
                $insertData=array('billNo'=>$billNo,'deliveryEmpName'=>$empName,'date'=>$currentDate,'retailerName'=>$retailerName,'compName'=>$cmpName,'remark'=>$remark,'netAmount'=>$netAmount,'pendingAmt'=>$netAmount,'billType'=>'adHocDeliveryBill');
                $this->AllocationByManagerModel->insert('bills',$insertData);
                if($this->db->affected_rows()>0){
                    $lastBillId=$this->db->insert_id();
                    $billRemark=array(
                        'billId'=>$lastBillId,'empId'=>$empId,'remark'=>$remark,'updatedAt'=>date('Y-m-d H:i:sa'),'updatedBy'=>$updatedBy
                    );
                    $this->AllocationByManagerModel->insert('bill_remark_history',$billRemark);//insert remark data

                    echo "Record inserted";
                }else{
                    echo "Record not inserted";
                }
                
            }else if(($billOption==="Office Adjustment Bill") && ($adjustmentAmount !=="") && ($remarkOffice !=="")){
                if($adjustmentAmount>$netAmount){
                    echo "Office ajustment amount is greater than net amount";
                }else{
                    $pendingAmount=0;
                    if($adjustmentAmount>0){
                        $pendingAmount=$netAmount-$adjustmentAmount;
                    }else{
                        $pendingAmount=$netAmount;
                    }
                    $insertData=array('billNo'=>$billNo,'date'=>$currentDate,'retailerName'=>$retailerName,'compName'=>$cmpName,'remark'=>$remarkOffice,'netAmount'=>$netAmount,'officeAdjustmentBillAmount'=>$adjustmentAmount,'pendingAmt'=>$pendingAmount,'billType'=>'officeAdjustmentBill');
                    $this->AllocationByManagerModel->insert('bills',$insertData);
                    if($this->db->affected_rows()>0){
                        $lastBillId=$this->db->insert_id();
                        $billRemark=array(
                            'billId'=>$lastBillId,'empId'=>$updatedBy,'amount'=>$adjustmentAmount,'remark'=>$remarkOffice,'updatedAt'=>date('Y-m-d H:i:sa'),'updatedBy'=>$updatedBy
                        );
                        $this->AllocationByManagerModel->insert('bill_remark_history',$billRemark);//insert remark data
                        echo "Record inserted";
                    }else{
                        echo "Record not inserted";
                    }
                }
                
            } else if($billOption==="Leave Unallocated"){
                $insertData=array('billNo'=>$billNo,'date'=>$currentDate,'retailerName'=>$retailerName,'compName'=>$cmpName,'netAmount'=>$netAmount,'pendingAmt'=>$netAmount);
                $this->AllocationByManagerModel->insert('bills',$insertData);
                if($this->db->affected_rows()>0){
                    echo "Record inserted";
                }else{
                    echo "Record not inserted";
                }
            }else if(($billOption==="Add To Open Allocation") && ($allocationId != "")){
                $alId=explode(' : ',$allocationId);
                $allocationDetails=array();
                if(!empty($alId[0])){
                    $allocationDetails=$this->AllocationByManagerModel->checkAllocationCode('allocations',$alId[0]);
                }

                if(!empty($allocationDetails)){
                    $billAllocationId=$allocationDetails[0]['id'];

                    $insertData=array('billNo'=>$billNo,'date'=>$currentDate,'retailerName'=>$retailerName,'compName'=>$cmpName,'netAmount'=>$netAmount,'pendingAmt'=>$netAmount);
                    $this->AllocationByManagerModel->insert('bills',$insertData);
                    if($this->db->affected_rows()>0){
                        $lastInsertedId=$this->db->insert_id(); 
                        $insAllocationDetail=array('billId'=>$lastInsertedId,'allocationId'=>$billAllocationId,'billStatus'=>1);
                        $this->AllocationByManagerModel->insert('allocationsbills',$insAllocationDetail);
                        if($this->db->affected_rows()>0){
                            $updBills=array('billType'=>'allocatedbillCurrent','isAllocated'=>1,'isBilled'=>'0','isLostBill'=>'0');
                            $this->AllocationByManagerModel->update('bills',$updBills,$lastInsertedId);
                            if($this->db->affected_rows()>0){
                                echo "Record inserted";
                            }else{
                                echo "Record not inserted";
                            }
                        }
                    }
                }else{
                    echo "Please enter correct allocation number";
                }
            }else{
                echo "Please enter all details";
            }
        }else{
            echo "Please use serial number '".$cmpSrNp."' for ".$cmpName.".";
        }
    }

    public function officeAdjustmentForm(){
        $id=trim($this->input->post('id'));
        $billDetail=$this->AllocationByManagerModel->load('bills',$id);
?>

        <div class="col-md-12">
            <p><span style="color:blue"> Bill Amount : </span> <?php if(!empty($billDetail)){ echo $billDetail[0]['netAmount']; } ?> &nbsp;&nbsp;
            <span style="color:blue"> Pending Amount : </span> <?php if(!empty($billDetail)){ echo $billDetail[0]['pendingAmt']; } ?> &nbsp;&nbsp;
            <span style="color:blue"> Bill Date : </span> <?php if(!empty($billDetail)){ echo $billDetail[0]['date']; } ?> </p>
        </div>
        <br>
        <div class="col-md-12">
          <input type="hidden" autocomplete="off" id="offcId" name="offcId" value="<?php echo $id; ?>">
          <input type="hidden" autocomplete="off" id="pendAmt" name="pendAmt" value="<?php if(!empty($billDetail)){ echo $billDetail[0]['pendingAmt']; } ?>">

          <input type="hidden" autocomplete="off" id="billAmt" name="billAmt" value="<?php if(!empty($billDetail)){ echo $billDetail[0]['netAmount']; } ?>">

          <input type="hidden" autocomplete="off" id="officeAdjBill" name="officeAdjBill" value="<?php if(!empty($billDetail)){ echo $billDetail[0]['officeAdjustmentBillAmount']; } ?>">
            <div class="col-md-6">
                <b> Amount</b>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">account_box</i>
                    </span>
                    <div class="form-line">
                        <input type="text" onblur="amountCheck(this);" autocomplete="off" id="collectAmount" name="adjustmentAmount" class="form-control date" value="" onkeypress="return numbersonly(this, event);" placeholder="adjustment amount">
                    </div>
                    <p id="eror" style="color:red"></p>
                </div>
            </div>

            <div class="col-md-6">
                <button id="submitCollect" class="btn btn-primary btn-sm margin m-t-20">Collect</button>
                <button type="button" data-dismiss="modal" class="btn btn-danger btn-sm margin m-t-20">Close</button>
            </div>
        </div>

<?php
    } 


    public function adHociBillByEmpAdjustmentForm(){
        $id=trim($this->input->post('id'));
        $billDetail=$this->AllocationByManagerModel->load('bills',$id);
?>

        <div class="col-md-12">
            <p><span style="color:blue"> Bill Amount : </span> <?php if(!empty($billDetail)){ echo $billDetail[0]['netAmount']; } ?> &nbsp;&nbsp;
            <span style="color:blue"> Pending Amount : </span> <?php if(!empty($billDetail)){ echo $billDetail[0]['pendingAmt']; } ?> &nbsp;&nbsp;
            <span style="color:blue"> Bill Date : </span> <?php if(!empty($billDetail)){ echo $billDetail[0]['date']; } ?> </p>
        </div>
        <br><br>
        <div class="col-md-12">
          <input type="hidden" autocomplete="off" id="offcId" name="offcId" value="<?php echo $id; ?>">
          <input type="hidden" autocomplete="off" id="pendAmt" name="pendAmt" value="<?php if(!empty($billDetail)){ echo $billDetail[0]['pendingAmt']; } ?>">

          <input type="hidden" autocomplete="off" id="billAmt" name="billAmt" value="<?php if(!empty($billDetail)){ echo $billDetail[0]['netAmount']; } ?>">

          <input type="hidden" autocomplete="off" id="officeAdjBill" name="officeAdjBill" value="<?php if(!empty($billDetail)){ echo $billDetail[0]['officeAdjustmentBillAmount']; } ?>">
            <div class="col-md-12">
                <b> Amount</b>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">account_box</i>
                    </span>
                    <div class="form-line">
                        <input type="text" onblur="amountCheck(this);" autocomplete="off" id="collectAmount" name="adjustmentAmount" class="form-control date" value="" onkeypress="return numbersonly(this, event);" placeholder="amount">
                    </div>
                    <p id="eror" style="color:red"></p>
                </div>
            </div>

            <div class="col-md-12">
                <button id="button" class="btn btn-primary btn-sm margin m-t-20">Cash</button>
                <button id="button" class="btn btn-primary btn-sm margin m-t-20">Cheque</button>
                <button id="button" class="btn btn-primary btn-sm margin m-t-20">NEFT</button>
                <button id="button" class="btn btn-primary btn-sm margin m-t-20">SR</button>
                <button id="button" class="btn btn-primary btn-sm margin m-t-20">FSR</button>
                <button id="button" class="btn btn-primary btn-sm margin m-t-20">CD</button>
                <button id="button" class="btn btn-primary btn-sm margin m-t-20">Debit</button>
                <button id="button" class="btn btn-primary btn-sm margin m-t-20">Bill</button>
                <button type="button" data-dismiss="modal" class="btn btn-danger btn-sm margin m-t-20">Close</button>
            </div>
        </div>

<?php
    } 


    public function insertOfficeAdjustmentAmount(){
        $id=trim($this->input->post('id'));
        $pendAmt=trim($this->input->post('pendAmt'));
        $billAmt=trim($this->input->post('billAmt'));
        $collectAmount=trim($this->input->post('collectAmount'));
        $officeAdjBill=trim($this->input->post('officeAdjBill'));
        

        $totalPending=$pendAmt-$collectAmount;
        $totalOfficeAdjustment=$officeAdjBill+$collectAmount;

        $data=array(
            'pendingAmt'=>$totalPending,'officeAdjustmentBillAmount'=>$totalOfficeAdjustment
        );

        $this->AllocationByManagerModel->update('bills',$data,$id);
        if($this->db->affected_rows()>0){
            echo "Record updated";
        }else{
            echo "Unable update record";
        }
    }


    public function billAllocationInfo($billId,$bills,$billOfficeAdj,$billSr){
        $billInfo=$this->AllocationByManagerModel->load('bills',$billId);
        $retailerCode=$this->AllocationByManagerModel->loadRetailer($billInfo[0]['retailerCode']);
        $resendBill=$this->AllocationByManagerModel->getResendBill('allocationsbills',$billId);
        $signedBill=$this->AllocationByManagerModel->getSignedBill('allocationsbills',$billId);

        $billData=$this->AllocationByManagerModel->load('bills',$billId);
        $billHistory=$this->AllocationByManagerModel->getBillHistoryInfo('bill_transaction_history',$billId);
               

    ?>
    
     <table class="table table-bordered cust-tbl js-exportable"  data-page-length='100'>
        <thead>
            <tr colspan="8">
                 <th colspan="6">
                  <span style="color:blue"> Bill Information  </span>
              </th>
            </tr>
        </thead>
        <thead>
            <tr class="gray">
                <th colspan="3"> Retailer Name  </th>
                <th colspan="3">Retailer Code</th>
                <th colspan="3"> Route Name  </th>
                <th colspan="5">Retailer GST No.</th>
            </tr>
        </thead>
            <tbody>
            <?php
                if(!empty($billInfo)){
                    foreach ($billInfo as $data) 
                    {
                        $urlSite="/".$data['retailerName'].'/'.$data['retailerCode'].'/'.$data['routeName'].'/'.$data['compName'].'/'.date('Y-m-d',strtotime("-1 year")).'/'.date('Y-m-d');
                        // echo $urlSite;
            ?>
                    <tr>
                        <td colspan="3">
                            <a href="<?php echo base_url('index.php/AdHocController/retailerHistoryInfoByBillSearch'.$urlSite); ?>"><?php echo $data['retailerName']; ?></a>
                            <!-- <a id="searchRetailerInfo" href="javascript:void();"><?php echo $data['retailerName']; ?></a> -->
                            <!-- <input type="text" id="tbRetailerName" value="<?php echo $data['retailerName']; ?>">
                            <input type="text" id="tbRetailerCode" value="<?php echo $data['retailerCode']; ?>">
                            <input type="text" id="tbRouteName" value="<?php echo $data['routeName']; ?>">
                            <input type="text" id="tbCompName" value="<?php echo $data['compName']; ?>">
                            <input type="hidden" id="tbFromDate" value="<?php echo date('Y-m-d'); ?>">
                            <input type="hidden" id="tbToDate" value="<?php echo date('Y-m-d'); ?>"> -->
                        </td>
                        <td colspan="3"><?php echo $data['retailerCode']; ?></td>
                        <td colspan="3"><?php echo $data['routeName']; ?></td>
                        <td colspan="5"><?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?></td>
                    </tr>

            <?php
                    }
                } 
            ?>
        </tbody>
        <thead>
            <tr class="gray">
                 <th> Bill No  </th>
                 <th>Bill Date</th>
                 <th> Salesman </th>
                 <th> Amount </th>
                 <th> SR  </th>
                 <th> CD  </th>
                 <th> Collection </th>
                 <th> Office  </th>
                 <th> Other  </th>
                 <th> Debit </th>
                 <th> Remaining  </th>
                 <th>Penalty  </th>
                 <th> Status </th>
                 <th> Action  </th>
            </tr>
        </thead>
        <tbody>
            <?php
                if(!empty($billInfo)){
                    foreach ($billInfo as $data) 
                    {
                        $dt=date_create($data['date']);
                        $createdDate = date_format($dt,'d-M-Y');
                   
                        if($data['isAllocated']==1){ ?>
                             <tr style="background-color: #dcd6d5">
                        <?php }else{ ?>
                             <tr>
                        <?php } ?>

                        <td><?php echo $data['billNo']; ?></td>
                        <td class="noSpace"><?php echo $createdDate; ?></td>
						<td class="CellWithComment"><?php 
						    $salesman=substr($data['salesman'], 0, 15);
                            echo rtrim($salesman);?>
							<span class="CellComment"><?php echo $result =substr($data['salesman'],0); ?></span>
						</td>
                        <td><?php echo $data['netAmount']; ?></td>
                        <?php if($data['isAllocated'] == 1){ ?>
                            <td><?php echo ($data['SRAmt']+$data['fsSrAmt']-$data['fsSrAmt']); ?></td>
                        <?php } else { ?>
                            <td><?php echo ($data['SRAmt']); ?></td>
                        <?php } ?>
                        
                         <td><?php echo $data['cd']; ?></td>
                         <td><?php echo ($data['receivedAmt']+$data['fsCashAmt']+$data['fsChequeAmt']+$data['fsNeftAmt']); ?></td>
                        <td><?php echo $data['officeAdjustmentBillAmount']; ?></td>
                        <td><?php echo $data['otherAdjustment']; ?></td>
                        <td><?php echo $data['debit']; ?></td>
                        <td><?php echo $data['pendingAmt']; ?></td>
                        <td><?php echo $data['chequePenalty']; ?></td>
                        <td>
                        <?php
                        if($data['deliveryStatus']=="cancelled"){
                            echo "Cancelled";
                        } else{     
                            $allocations=$this->AllocationByManagerModel->getAllocationDetailsByBill('bills',$data['id']);
                            $allocationsHistory=$this->AllocationByManagerModel->getAllocationDetailsByBillHistory('bills',$data['id']);
                            $officeAllocations=$this->AllocationByManagerModel->getOfficeAllocationDetailsByBill('bills',$data['id']);
                            $officeAllocationsHistory=$this->AllocationByManagerModel->getOfficeAllocationDetailsByBillHistory('bills',$data['id']);
                            // print_r($allocations);exit;
                            $status="";
                            $allocationNumber="";
                            $allocationName="";
                            $empName="";
                            if($data['deliveryStatus']=="cancelled"){
                                echo "Cancelled";
                            }else{
                                // if($data['isAllocated']==1){ 
                                //     if(!empty($allocations)){
                                //         $allocationNumber=$allocations[0]['id'];
                                //         $allocationName=$allocations[0]['allocationCode'];
                                //         $empName=trim($allocations[0]['ename1']).','.trim($allocations[0]['ename2']).','.trim($allocations[0]['ename3']).','.trim($allocations[0]['ename4']);
                                //         $status="Allocated : ".$allocationName;
                                //     }
    
                                //     if(!empty($officeAllocations)){
                                //         $allocationNumber=$allocations[0]['id'];
                                //         $allocationName=$allocations[0]['allocationCode'];
                                //         $status="Allocated : ".$allocationName;
                                //     }
                                // }else{
                                //     if(!empty($allocationsHistory) || !empty($officeAllocationsHistory)){
                                //       if(!empty($allocationsHistory)){
                                //         $status="Past Bill";
                                //       }
                                //       if(!empty($officeAllocationsHistory)){
                                //         $status="Past Bill";
                                //       }
                                //     }else{
                                //         if($data['pendingAmt'] == $data['netAmount']){
                                //             $status="Unaccounted";
                                //         }else if($data['deliveryEmpName'] !==""){
                                //             $status="Direct Delivery";
                                //         }else if($data['pendingAmt'] <=0){
                                //             $status= "Bill Cleared";
                                //         }
                                //     }
                                // } 
                                if($data['isAllocated']==1){
                                    if(!empty($allocations)){
                                        echo "<span style='color:blue'>Allocated in : ".$allocations[0]['allocationCode'].'</span>';
                                    }

                                    if(!empty($officeAllocations)){
                                        echo "<span style='color:blue'>Allocated in : ".$officeAllocations[0]['allocationCode'].'</span>';
                                    }
                                }else{ 
                                    if($data['pendingAmt']==0){
                                        echo "<span style='color:green'> Cleared</span>";
                                    }else if($data['isDirectDeliveryBill']==1){
                                        echo "<span style='color:green'> Direct Delivery</span>";
                                    }else{
                                      if(!empty($allocationsHistory) || !empty($officeAllocationsHistory)){
                                        if(!empty($allocationsHistory)){
                                          echo "<span style='color:green'>Earlier Allocated </span>";
                                        }

                                        if(!empty($officeAllocationsHistory)){
                                          echo "<span style='color:green'>Already Allocated in : ".$officeAllocationsHistory[0]['allocationCode'].'</span>';
                                        }
                                      }else{
                                          if($data['pendingAmt']==$data['netAmount']){
                                             echo "<span style='color:red'> Unaccounted</span>";
                                          }else{
                                             echo "<span style='color:green'> Accounted</span>";
                                          }
                                          
                                      }
                                    }
                                } 
                                // echo $status;
                            }
                        }
                         ?>
                        
                            
                        </td>
                        <td class="noSpace">
                        <?php
                        if($data['isAllocated']!=1 && $data['pendingAmt'] >0 && $data['deliveryStatus'] !=="cancelled"){

                            $designation = ($this->session->userdata[$this->projectSessionName]['designation']);
                            $des=explode(',',$designation);
                            $des = array_map('trim', $des);

                    if ((in_array('operator', $des))) { 
                    ?>
                    <a href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs viewBill-btn" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                                               
                    <?php
                    }else{
                    ?>
                     <a id="prDetailsForAll" href="javascript:void()" data-id="<?php echo $data['id']; ?>" data-salesman="<?php echo $data['salesman']; ?>" data-billDate="<?php echo $createdDate; ?>" data-credAdj="<?php echo $data['creditAdjustment']; ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>" data-gst="<?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?>" data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-route="<?php echo $data['routeName']; ?>" data-toggle="modal" data-target="#processModalForAll" ><button class="btn btn-xs process-btn waves-effect waves-float" data-toggle="tooltip" data-placement="bottom" title="Process"><i class="material-icons">touch_app</i></button></a>

                    <!-- <a id="prDetails" href="javascript:void()" data-id="<?php echo $data['id']; ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>" data-gst="<?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?>" data-code="<?php echo $data['retailerCode']; ?>" data-salesman="<?php echo $data['salesman']; ?>" data-route="<?php echo $data['routeName']; ?>" data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-toggle="modal" data-target="#processModal"><button class="btn btn-xs  btn-primary"><i class="material-icons">touch_app</i></button></a> -->
                    <a href="<?php echo site_url('AdHocController/billHistoryInfo/'.$data['id']); ?>" class="btn btn-xs history-btn" data-toggle="tooltip" data-placement="bottom" title="View History"><i class="material-icons">info</i></a>
                    <a href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs viewBill-btn" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                                               
                      <?php }

                        }else{
                            
                    ?>
                    <a href="<?php echo site_url('AdHocController/billHistoryInfo/'.$data['id']); ?>" class="btn btn-xs history-btn" data-toggle="tooltip" data-placement="bottom" title="View History"><i class="material-icons">info</i></a>
                    <a href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs viewBill-btn" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                   
                    <?php
                        }

                        ?>
                        </td>
                        
                      </tr>

                    <?php
                    }
                } 
                ?>

        </tbody>
</table>

<table class="table table-bordered cust-tbl dataTable js-exportable" data-page-length='100'>
        <thead>
            <tr  align="center">
                  <th colspan="7">
                  <span style="color:blue"> Bill Payment History  </span>
              </th>
            </tr>
        </thead>
        <thead>
                <tr>
                <th>S. No.</th>
                
                <th>Employee</th>
                <th>Date</th>
                <th>Allocation</th>
                <th>Transaction Type</th>
                <th><span class="pull-right">CR</span></th>
                <th><span class="pull-right">DR</span></th>
                <th><span class="pull-right">Balance</span></th>
                <th>Remarks</th>
            </tr>
        </thead>
        
        <tbody>
        <?php 
            $no=0;
            $total=0;
            $srTotal=0;
            if(!empty($billData)){
                $billTotal=0;
                foreach($billData as $data){
                    $no++;
                    $billTotal=$data['netAmount'];
        ?>      
                    <tr>
                        <td><?php echo $no;?></td>
                        <td class="text-left"><?php echo $data['salesman'];?></td>
                        <td><?php echo date('d-M-Y',strtotime($data['date']));?></td>
                        <td class="text-right"></td>
                        <td class="text-left">New Bill</td>
                        
                        <td class="text-right"></td>
                        <td class="text-right""><?php echo number_format($data['billNetAmount']);?></td>
                        <td class="text-right"><?php echo number_format($data['billNetAmount']);?></td>
                        <td class="text-right"></td>
                    </tr>

        <?php       
                    if($data['creditAdjustment'] >0 ){ 
                        $no++;
        ?>
                    <tr>
                        <td><?php echo $no;?></td>
                        <td class="text-left"><?php echo $data['salesman'];?></td>
                        <td><?php echo date('d-M-Y',strtotime($data['date']));?></td>
                        <td class="text-right"></td>
                        <td class="text-right">Credit Adjustment</td>
                        
                        <td class="text-right"><?php echo number_format($data['creditAdjustment']);?></td>
                        <td class="text-right"></td>
                        <td class="text-right"><?php echo number_format($data['netAmount']);?></td>
                        <td class="text-right"></td>
                    </tr>
        <?php 
                    }
                }

                if(!empty($billHistory)){
                    foreach($billHistory as $item){
                        
                            
                        
                        if($item['transactionStatus'] !=""){ 
                        $allocationDetail="";
                        $allocationCode="";
                        if($item['allocationId'] >0){
                            $allocationInfo=$this->AllocationByManagerModel->load('allocations',$item['allocationId']);
                            if(!empty($allocationInfo)){
                                $allocationDetail='Added in allocation No : '.$allocationInfo[0]['allocationCode'];
                                $allocationCode=$allocationInfo[0]['allocationCode'];
                            }
                        }
                        $officeAllocationData="";
                        $officeAllocationCode="";
                        $officeAllocationRemark="";
                        if($item['officeAllocationId'] >0){
                            $allocationInfoData=$this->AllocationByManagerModel->load('allocations_officeadjustment',$item['officeAllocationId']);
                            // echo "je";print_r($allocationInfoData);
                            if(!empty($allocationInfoData)){
                                $officeAllocationData='Added in allocation No : '.$allocationInfoData[0]['allocationCode'];
                                $officeAllocationCode=$allocationInfoData[0]['allocationCode'];
                                $officeAllocationRemark=$allocationInfoData[0]['remark'];;
                            }
                        }
                        
                        $no++;
                        if($item['transactionMode']=='dr'){
                            $billTotal=$billTotal-abs($item['transactionAmount']);
                        }else{
                            $billTotal=$billTotal+abs($item['transactionAmount']);
                        }
                        // $billTotal=$billTotal-$item['transactionAmount'];

                        // if($item['transactionAmount'] != 0){
        ?>
                    <tr>
                        <td><?php echo $no;?></td>
                        <td>
                        <?php 
                            if($item['empName1'] != ""){
                                echo $item['empName1'].' ';
                            }else{
                                echo $item['empName2'];
                            }
                        ?>
                        </td>
                        <td><?php echo date('d-M-Y',strtotime($item['transactionDate']));?></td>
                        <td class="text-left">
                        <?php 
                        $alId=$item['allocationId'];
                        if($item['allocationId']>0){
                            $url= base_url()."index.php/AllocationByManagerController/CloseCompleteAllocation/".$alId;
                            if($item['allocationId'] >0){
                                echo "<a href='".$url."' target='_blank'>".$allocationCode."</a>";
                            }else{
                                echo '';
                            }
                        }

                        if($item['officeAllocationId']>0){
                            $url= base_url()."index.php/owner/OfficeAllocationController/closedAllocationInfo/".$item['officeAllocationId'];
                            if($item['officeAllocationId'] >0){
                                echo "<a href='".$url."' target='_blank'>".$officeAllocationCode."</a>";
                            }else{
                                echo '';
                            }
                        }
                        
                        ?>
                        
                        </td>
                        <td class="text-left">
                        <?php 
                            if($item['transactionStatus']=="Allocated" || $item['transactionStatus']=="Added to Allocation" ||  $item['transactionStatus']=="Create new allocation"){
                                if($allocationDetail != ""){
                                    echo $allocationDetail;
                                }else if($officeAllocationData !=""){
                                    echo $officeAllocationData;
                                    
                                }else{
                                    echo $item['transactionStatus'];
                                }
                            }else{
                                echo $item['transactionStatus'];
                            }
                        ?>
                        </td>
                        <td class="text-right">
                            <?php 
                            if($item['transactionMode']=='dr'){
                                if($item['transactionStatus']=="Allocated" || $item['transactionStatus']=="Signed" || $item['transactionStatus']=="Resend" || $item['transactionStatus']=="Lost Bill" || $item['transactionStatus']=="Lost Cheque"){
                                    echo '';
                                }else{
                                    echo number_format(abs($item['transactionAmount']));
                                }
                            }
                        ?>
                        
                        </td>
                        <td class="text-right">
                            <?php 
                            if($item['transactionMode']=='cr'){
                                if($item['transactionStatus']=="Allocated" || $item['transactionStatus']=="Signed" || $item['transactionStatus']=="Resend" || $item['transactionStatus']=="Lost Bill"){
                                    echo '';
                                }else{
                                    echo number_format(abs($item['transactionAmount']));
                                }
                            }
                        ?>
                        </td>
                        <td class="text-right"><?php echo number_format($billTotal);?></td>
                        <td class="text-left">
                        <?php
                            if($item['transactionStatus']=='CD'){
                                $cdRemark=$this->AllocationByManagerModel->getCdRemark('bill_remark_history',$item['transactionAmount'],$item['billId']);
                                // print_r($cdRemark);
                                if(!empty($cdRemark)){
                                    echo $cdRemark[0]['remark'];
                                }
                            }

                            if($item['transactionStatus']=='Office Adjustment'){
                                $officeRemark=$this->AllocationByManagerModel->getCdRemark('bill_remark_history',$item['transactionAmount'],$item['billId']);
                                // print_r($cdRemark);
                                if(!empty($officeRemark)){
                                    echo $officeRemark[0]['remark'];
                                }
                            }

                            if($item['transactionStatus']=='Other Adjustment'){
                                $otherRemark=$this->AllocationByManagerModel->getCdRemark('bill_remark_history',$item['transactionAmount'],$item['billId']);
                                // print_r($cdRemark);
                                if(!empty($otherRemark)){
                                    echo $otherRemark[0]['remark'];
                                }
                            }

                            if($item['transactionStatus']=='Emp Delivery'){
                                $officeRemark=$this->AllocationByManagerModel->getCdRemark('bill_remark_history',$item['transactionAmount'],$item['billId']);
                                // print_r($cdRemark);
                                if(!empty($officeRemark)){
                                    echo $officeRemark[0]['remark'];
                                }
                            }

                            if($item['transactionStatus']=="Cheque" || $item['transactionStatus']=="Cheque Bounce" || $item['transactionStatus']=="Cheque Bounce Penalty" || $item['transactionStatus']=="Lost Cheque"){
                                $bills=$this->AllocationByManagerModel->getBillHistoryByBillId('billpayments',$item['transactionAmount'],$item['transactionStatus'],$item['billId'],$item['allocationId'],$item['officeAllocationId']);
                                if(!empty($bills)){
                                    foreach($bills as $data){
                                        $chequeDate=date_create($data['chequeDate']);
                                        $chequeCreatedDate = date_format($chequeDate,'d-M-Y');

                                        $neftDate=date_create($data['neftDate']);
                                        $neftCreatedDate = date_format($neftDate,'d-M-Y');

                                        if($data['paymentMode']=='Cheque'){
        
                                            if($data['isLostStatus']==1){
                                                echo "Not Received";
                                            }else if($data['isLostStatus']==2 && $data['chequeStatus']=='New'){
                                                echo "Received, but not banked";
                                            }else if($data['isLostStatus']==2 && $data['chequeStatus']=='Banked'){
                                                echo "Banked, But Not Cleared";
                                            }else if($data['chequeStatus']=='Bounced' || $data['chequeStatus']=='Bounced&Returned'){
                                                echo "Bounced";
                                            }else if($data['isLostStatus']==2 && $data['chequeStatus']=='Cleared'){
                                                echo "Cleared";
                                            }
                                        }

                                        
    
                                        if($data['isLostStatus']==2 && $data['chequeStatus']==''){
                                            echo "Received but not saved ";
                                        }else if($data['isLostStatus']==2 && $data['chequeStatus']=='New'){
                                            echo ", Cheque No. ".$data['chequeNo'].' dated '.$chequeCreatedDate.' Bank '.$data['chequeBank'];
                                        }else if($data['isLostStatus']==2 && $data['chequeStatus']=='Banked'){
                                            echo ", Cheque No. ".$data['chequeNo'].' dated '.$chequeCreatedDate.' Bank '.$data['chequeBank'];
                                        }else if($data['isLostStatus']==2 && $data['chequeStatus']=='Bounced'){
                                            echo ", Cheque No. ".$data['chequeNo'].' dated '.$chequeCreatedDate.' Bank '.$data['chequeBank'];
                                        }else if($data['isLostStatus']==2 && $data['chequeStatus']=='Cleared'){
                                            echo ", Cheque No. ".$data['chequeNo'].' dated '.$chequeCreatedDate.' Bank '.$data['chequeBank'];
                                        }
                                        // $data['bills']=$this->AllocationByManagerModel->load('bills',$id);

                                        
                                    }
                                    
                                }
                                echo ' '.$item['remark'];
                                
                            }else if($item['transactionStatus']=="NEFT" || $item['transactionStatus']=="Pending NEFT" || $item['transactionStatus']=="NEFT not Received"){
                                $bills=$this->AllocationByManagerModel->getBillHistoryByBillId('billpayments',$item['transactionAmount'],$item['transactionStatus'],$item['billId'],$item['allocationId'],$item['officeAllocationId']);
                                if(!empty($bills)){
                                    foreach($bills as $data){
                                        $chequeDate=date_create($data['chequeDate']);
                                        $chequeCreatedDate = date_format($chequeDate,'d-M-Y');

                                        $neftDate=date_create($data['neftDate']);
                                        $neftCreatedDate = date_format($neftDate,'d-M-Y');

                                        if($data['paymentMode']=='NEFT'){
                                            if($data['isLostStatus']==1){
                                                echo "Not Received";
                                            }else if($data['isLostStatus']==2 && $data['chequeStatus']==''){
                                                echo "Received,But not saved";
                                            }else if($data['isLostStatus']==2 && $data['chequeStatus']=='New'){
                                                echo "Received,But not cleared";
                                            }else if($data['isLostStatus']==2 && $data['chequeStatus']=='Received'){
                                                echo "Cleared";
                                            }else if($data['isLostStatus']==2 && $data['chequeStatus']=='Not Received'){
                                                echo "Rejected";
                                            }
                                        }
    
                                        if($data['isLostStatus']==2 && $data['chequeStatus']=='New'){
                                            echo ", NEFT No. ".$data['neftNo'].' dated '.$neftCreatedDate;
                                        }else if($data['isLostStatus']==2 && $data['chequeStatus']=='Received'){
                                            echo ", NEFT No. ".$data['neftNo'].' dated '.$neftCreatedDate;
                                        }else if($data['isLostStatus']==2 && $data['chequeStatus']=='Not Received'){
                                            echo ", NEFT No. ".$data['neftNo'].' dated '.$neftCreatedDate;
                                        }
                                    }
                                }
                            }else{
                                    if($item['officeAllocationId'] >0){
                                        echo $officeAllocationRemark;
                                    }else{
                                        echo $item['remark'];
                                    }
                            }
                        ?>
                        </td>
                    </tr>
        <?php
                        }
                    // }
                // }
            }

                }
            } 
        ?>
        
        </tbody>
    </table>



<?php
      if(!empty($billSr)){ ?>
       <table style="font-size: 12px" class="table table-bordered table-striped table-hover dataTable js-exportable" data-page-length='100'>

        <thead>
            <tr colspan="6" align="center">
                <th>
                  <span style="color:blue"> Bill SR/FSR Details  </span>
              </th>
            </tr>
        </thead>
        <thead>
            <tr class="gray">
               <th> Sr No  </th>
                 <th> Allocation Code  </th>
                 <th> Employee Name  </th>
                 <th>Date</th>
                 <th>Item Name</th>
                <th> Quantity  </th>
                <th> SR Quantity </th>
                <th>SR Amount</th>

            </tr>
        </thead>
        <tbody>
            <?php
               
                
                    $no=0;
                    foreach ($billSr as $data) 
                    {
                       $no++;

                      $dt=date_create($data['createdDate']);
                      $createdDate = date_format($dt,'d-M-Y');
                   
                   ?>
                        <tr>
                   
                        <td><?php echo $no; ?></td>
                        <td><?php 
                            $idForAllocation=$data['allocationId'];
                                    $codeForAllocation=$data['allocationCode'];
                                    $url= site_url("AllocationByManagerController/CloseCompleteAllocation/".$idForAllocation);
                                    
                                    echo "<a target='_blank' href='".$url."'>".$codeForAllocation."</a>";
                         ?></td>
                       <td><?php echo trim($data['ename1'].','.$data['ename2'].','.$data['ename3'].','.$data['ename4'].','.$data['ename5'],","); ?></td>
                        <td><?php echo $createdDate; ?></td>
                        <td><?php echo $data['productName']; ?></td>
                        <td><?php echo $data['qty']; ?></td>
                        <td><?php echo $data['sr_qty']; ?></td>
                        <td><?php echo number_format(($data['netAmount']/$data['qty']*$data['sr_qty']),2); ?></td>
                        
                      </tr>

                    <?php
                    }
                
                ?>

            </tbody>
        </table>
    <?php } 
    
    }


    public function loadBillsHistory($id){
        $id=urldecode($id);
        $data['bills']=$this->AllocationByManagerModel->load('bills',$id);
        echo json_encode( $data['bills']);
    }

    public function retailerHistoryInformation($bills,$retailerName){
        
    ?>
	 <table class="table table-bordered cust-tbl dataTable js-exportable" data-page-length='100' id="xp">
        <thead>
            <tr colspan="8">
                 <th>
                  <span style="color:blue"> Bill Information  </span>
              </th>
            </tr>
        </thead>

        <thead>
            <tr class="gray">
               
                 <th colspan="3"> Retailer Name  </th>
                 <th colspan="3">Retailer Code</th>
                <th colspan="3"> Route Name  </th>
                <th colspan="5">Retailer GST No.</th>
                

            </tr>
        </thead>
            <tbody>
            <?php
               
                if(!empty($bills)){
                    $retailerCode=$this->AllocationByManagerModel->loadRetailer($bills[0]['retailerCode']);
            ?>
                   
                    
            <tr>
                <td colspan="3"><?php echo $bills[0]['retailerName']; ?></td>
                <td colspan="3"><?php echo $bills[0]['retailerCode']; ?></td>
                <td colspan="3"><?php echo $bills[0]['routeName']; ?></td>
                 <td colspan="5"><?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?></td>
            </tr>

            <?php
                    
                }else{
            ?>
                    <tr><td colspan="14">No data available.</td></tr>
           <?php     
                } 
                
            ?>

        </tbody>
        </table>
		
	<div id="hideInfo" class="col-md-12"> 
    <div class="table-responsive"> 
     <table class="table table-bordered cust-tbl dataTable js-exportable" data-page-length='100' id="xp">
        <thead>
            <tr class="gray">
                <th> Bill No  </th>
                <th> Bill Date</th>
                <th> Salesman </th>
                <th> Amount </th>
                <th> SR  </th>
                <th> CD  </th>
                <th> Collection </th>
                <th> Office </th>
                <th> Other </th>
                <th> Debit </th>
                <th> Remaining  </th>
                <th> Penalty  </th>
				<th> Status  </th>
                <th> Action  </th>  
            </tr> 
        </thead>
        <tbody>
            <?php
              
                if(!empty($bills)){
                    foreach ($bills as $data) 
                    {
                      $dt=date_create($data['date']);
                      $createdDate = date_format($dt,'d-M-Y');
                   
                    if($data['isAllocated']==1){ ?>
                         <tr style="background-color: #dcd6d5">
                    <?php }else{ ?>
                         <tr>
                    <?php } ?>
                        
                        <td><?php echo $data['billNo']; ?></td>
                        <td class="noSpace"><?php echo $createdDate; ?></td>
						<td class="CellWithComment"><?php //echo  rtrim($data['salesman'],', '); 
						    $salesman=substr($data['salesman'], 0, 10);
                            echo rtrim($salesman);?>
							<span class="CellComment"><?php echo $result =substr($data['salesman'],0); ?></span>
						</td>
                        <td><?php echo $data['netAmount']; ?></td>
                        <td><?php echo $data['SRAmt']; ?></td>
                        <td><?php echo $data['cd']; ?></td>
                        <td><?php echo $data['receivedAmt']; ?></td>
                        <td><?php echo $data['officeAdjustmentBillAmount']; ?></td>
                        <td><?php echo $data['otherAdjustment']; ?></td>
                        <td><?php echo $data['debit']; ?></td>
                        <td><?php echo $data['pendingAmt']; ?></td>
                        <td><?php echo $data['chequePenalty']; ?></td>
                        <td>
                        <?php 
                                
                            $allocations=$this->AllocationByManagerModel->getAllocationDetailsByBill('bills',$data['id']);
                            $allocationsHistory=$this->AllocationByManagerModel->getAllocationDetailsByBillHistory('bills',$data['id']);
                            $officeAllocations=$this->AllocationByManagerModel->getOfficeAllocationDetailsByBill('bills',$data['id']);
                            $officeAllocationsHistory=$this->AllocationByManagerModel->getOfficeAllocationDetailsByBillHistory('bills',$data['id']);
                            // print_r($allocations);exit;
                            $status="";
                            $allocationNumber="";
                            $allocationName="";
                            $empName="";
                            if($data['isAllocated']==1){ 
                                if(!empty($allocations)){
                                    $allocationNumber=$allocations[0]['id'];
                                    $allocationName=$allocations[0]['allocationCode'];
                                    $empName=trim($allocations[0]['ename1']).','.trim($allocations[0]['ename2']).','.trim($allocations[0]['ename3']).','.trim($allocations[0]['ename4']);
                                    $status="Allocated : ".$allocationName;
                                }

                                if(!empty($officeAllocations)){
                                    $allocationNumber=$allocations[0]['id'];
                                    $allocationName=$allocations[0]['allocationCode'];
                                    $status="Allocated : ".$allocationName;
                                }
                            }else{
                                if(!empty($allocationsHistory) || !empty($officeAllocationsHistory)){
                                  if(!empty($allocationsHistory)){
                                    $status="Past Bill";
                                  }
                                  if(!empty($officeAllocationsHistory)){
                                    $status="Past Bill";
                                  }
                                }else{
                                    if($data['pendingAmt'] == $data['netAmount']){
                                        $status="Unaccounted";
                                    }else if($data['deliveryEmpName'] !==""){
                                        $status="Direct Delivery";
                                    }else if($data['pendingAmt'] <=0){
                                        $status= "Bill Cleared";
                                    }
                                }
                            } 

                            echo $status;

                         ?>
                     </td>
                        <!-- <td>
                        <a id="billHistory" href="javascript:void()" data-id="<?php echo $data['id']; ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>" data-gst="<?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?>" data-code="<?php echo $data['retailerCode']; ?>" data-salesman="<?php echo $data['salesman']; ?>" data-route="<?php echo $data['routeName']; ?>" data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-toggle="modal" data-target="#billprocessModal"><button class="btn btn-xs bg-primary margin"><i class="material-icons">visibility</i> </button></a>
                        <a id="billHistoryProcess" href="javascript:void()" data-id="<?php echo $data['id']; ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>" data-gst="<?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?>" data-code="<?php echo $data['retailerCode']; ?>" data-salesman="<?php echo $data['salesman']; ?>" data-route="<?php echo $data['routeName']; ?>" data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-toggle="modal" data-target="#retailerprocessModal"><button class="btn btn-xs bg-primary margin">Process</button></a>
                        </td> -->
                        <td class="noSpace">
                        <?php 
                            $designation = ($this->session->userdata[$this->projectSessionName]['designation']);
                            $des=explode(',',$designation);
                            $des = array_map('trim', $des);
                            if($data['isAllocated']!=1 && $data['pendingAmt'] >0){ 
                            if ((in_array('operator', $des))) { 
                                ?>
                            <a href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs viewBill-btn" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>


                            <?php  }else{  ?>
                            <a id="prDetails" href="javascript:void()" data-id="<?php echo $data['id']; ?>" data-salesman="<?php echo $data['salesman']; ?>"  data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>" data-gst="<?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?>" data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-toggle="modal" data-target="#processModal"><button class="btn btn-xs process-btn waves-effect"><i class="material-icons">touch_app</i> </button></a>
                            <!-- <a id="billHistory" href="javascript:void()" data-id="<?php echo $data['id']; ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>" data-gst="<?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?>" data-code="<?php echo $data['retailerCode']; ?>" data-salesman="<?php echo $data['salesman']; ?>" data-route="<?php echo $data['routeName']; ?>" data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-toggle="modal" data-target="#billprocessModal"><button class="btn btn-xs btn-primary waves-effect"><i class="material-icons">info</i> </button></a> -->
                            <a href="<?php echo site_url('AdHocController/billHistoryInfo/'.$data['id']); ?>" class="btn btn-xs history-btn" data-toggle="tooltip" data-placement="bottom" title="View History"><i class="material-icons">info</i></a>
                            <a href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs viewBill-btn" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                                                  
                            <?php   
                                }
                        }else{ 

                                if ((in_array('operator', $des))) { 
                                ?>
                                &nbsp;<a href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs viewBill-btn" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>


                                <?php    }else{ 

                            ?>
                                <a href="<?php echo site_url('AdHocController/billHistoryInfo/'.$data['id']); ?>" class="btn btn-xs history-btn" data-toggle="tooltip" data-placement="bottom" title="View History"><i class="material-icons">info</i></a>
                                <a href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs viewBill-btn" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                        <?php 
                            }
                                    
                                }
                          ?>             
                        </td>
                        
                      </tr>

                    <?php
                    }
                 }else{

                    ?>
                    <tr><td colspan="14">No data available.</td></tr>
           <?php     } 
                ?>

        </tbody>
</table>
</div>
</div>

<?php
    }


    public function retailerBillHistory($billId,$bills,$billOfficeAdj,$billSr){
        $billInfo=$this->AllocationByManagerModel->load('bills',$billId);
        $retailerCode=$this->AllocationByManagerModel->loadRetailer($billInfo[0]['retailerCode']);
        $resendBill=$this->AllocationByManagerModel->getResendBill('allocationsbills',$billId);
        $signedBill=$this->AllocationByManagerModel->getSignedBill('allocationsbills',$billId);
        
    ?>
    
     <table class="table table-bordered cust-tbl dataTable js-exportable" data-page-length='100'>
        <thead>
            <tr colspan="8">
                 <th>
                  <span style="color:blue"> Bill Information  </span>
              </th>
            </tr>
        </thead>
        <thead>
            <tr class="gray">
                <th colspan="3"> Retailer Name  </th>
                <th colspan="3">Retailer Code</th>
                <th colspan="3"> Route Name  </th>
                <th colspan="5">Retailer GST No.</th>
            </tr>
        </thead>
            <tbody>
            <?php
                if(!empty($billInfo)){
                    foreach ($billInfo as $data) 
                    {
            ?>
                    <tr>
                        <td colspan="3"><?php echo $data['retailerName']; ?></td>
                        <td colspan="3"><?php echo $data['retailerCode']; ?></td>
                        <td colspan="3"><?php echo $data['routeName']; ?></td>
                        <td colspan="5"><?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?></td>
                    </tr>

            <?php
                    }
                } 
            ?>
        </tbody>
        <thead>
            <tr class="gray">
                 <th> Bill No  </th>
                 <th>Bill Date</th>
                 <th> Salesman </th>
                 <th> Net Amount </th>
                 <th> SR  </th>
                 <th> CD  </th>
                 <th> Collection </th>
                 <th> Office Adj  </th>
                 <th> Other Adj  </th>
                 <th> Debit </th>
                 <th> Remaining  </th>
                 <th> Cheque Penalty  </th>
                 <th>Action</th>
                
            </tr>
        </thead>
        <tbody>
            <?php
                if(!empty($billInfo)){
                    foreach ($billInfo as $data) 
                    {
                        $dt=date_create($data['date']);
                        $createdDate = date_format($dt,'d-M-Y');
                   
                        if($data['isAllocated']==1){ ?>
                             <tr style="background-color: #dcd6d5">
                        <?php }else{ ?>
                             <tr>
                        <?php } ?>

                        <td><?php echo $data['billNo']; ?></td>
                        <td><?php echo $createdDate; ?></td>
                        <td><?php echo $data['salesman']; ?></td>
                        <td><?php echo $data['netAmount']; ?></td>
                        <td><?php echo ($data['SRAmt']+$data['fsSrAmt']); ?></td>
                        <td><?php echo $data['cd']; ?></td>
                        <td><?php echo ($data['receivedAmt']+$data['fsCashAmt']+$data['fsChequeAmt']+$data['fsNeftAmt']); ?></td>
                        <td><?php echo $data['officeAdjustmentBillAmount']; ?></td>
                        <td><?php echo $data['otherAdjustment']; ?></td>
                        <td><?php echo $data['debit']; ?></td>
                        <td><?php echo $data['pendingAmt']; ?></td>
                        <td><?php echo $data['chequePenalty']; ?></td>
                        <td class="noSpace">
                         <a href="<?php echo site_url('AdHocController/billHistoryInfo/'.$data['id']); ?>" class="btn btn-xs history-btn" data-toggle="tooltip" data-placement="bottom" title="View History"><i class="material-icons">info</i></a>
                         <a href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs viewBill-btn" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>                          
                        </td>
                        
                      </tr>

                    <?php
                    }
                } 
                ?>

        </tbody>
</table>

<?php if((!empty($resendBill)) || (!empty($signedBill))){ ?>


<table style="font-size: 12px" class="table table-bordered table-striped table-hover dataTable js-exportable" data-page-length='100'>
        <thead>
            <tr colspan="8">
                 <th>
                  <span style="color:blue"> Bill Resend/Signed Information  </span>
              </th>
            </tr>
        </thead>
        <thead>
            <tr class="gray">
                <th colspan="4">Allocation No </th>
                <th colspan="4">Date</th>
                 <th colspan="4"> Status  </th>
            </tr>
        <thead>
            <tbody>
            <?php
                if(!empty($resendBill)){
                    $dt=date_create($resendBill[0]['alDate']);
                      $createdDate = date_format($dt,'d-M-Y');
            ?>
                    <tr>
                        <td colspan="4"><?php echo $resendBill[0]['alCode']; ?></td>
                        <td colspan="4"><?php echo $createdDate; ?></td>
                        <td colspan="4"><?php echo 'Resend'; ?></td>
                    </tr>
            <?php
                    
                } 

                 if(!empty($signedBill)){
                    $dt=date_create($signedBill[0]['alDate']);
                      $createdDate = date_format($dt,'d-M-Y');
                    
            ?>
                    <tr>
                        <td colspan="4"><?php echo $signedBill[0]['alCode']; ?></td>
                        <td colspan="4"><?php echo $createdDate; ?></td>
                        <td colspan="4"><?php echo 'Signed'; ?></td>
                    </tr>
            <?php
                    
                }  
            ?>
        </tbody>
</table>
<?php } ?>


<?php if((!empty($bills)) || (!empty($billOfficeAdj))){ ?>

        <table style="font-size: 12px" class="table table-bordered table-striped table-hover dataTable js-exportable" data-page-length='100'>
        <thead>
            <tr colspan="7" align="center">
                  <th>
                  <span style="color:blue"> Bill Transaction Details  </span>
              </th>
            </tr>
        </thead>

        <thead>
            <tr class="gray">
                <th> S. No.</th>
                 <th> Allocation No. </th>
                 <th>Employee</th>
                <th>  Date  </th>
                <th> Receivable Amount </th>
                 <th> Transaction</th>
                 <th>Amount</th>
                 <th>Remaining Amount</th>
                  <th> Status  </th>
                   <th> Details  </th>
               
            </tr>
        </thead>
        <tbody>
                <?php
                $no=0;
                 if(!empty($bills)){
                    foreach ($bills as $data) 
                    {
                       $no++; 

                      $dt=date_create($data['date']);
                      $createdDate = date_format($dt,'d-M-Y');
                      if($data['paidAmount']>0){
                    ?>
                      <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php 
                                if(empty($data['allocationCode'])){ 
                                    echo '<center>-</center>'; 
                                }else {
                                    echo $data['allocationCode']; 
                                }
                            ?>
                            
                        </td>
                        <td><?php echo $data['name']; ?></td>
                        <td><?php echo $createdDate; ?></td>
                        <td><?php echo ($data['balanceAmount']+$data['paidAmount']); ?></td>
                        <td><?php echo $data['paymentMode']; ?></td>
                        <td><?php echo $data['paidAmount']; ?></td>
                        <td><?php echo $data['balanceAmount']; ?></td>
                        <td>
                        <?php 
                            if($data['paymentMode']=='Cash'){
                                echo '-';
                            }else{
                                if($data['paymentMode']=='Cheque'){
                                    
                                    if($data['isLostStatus']==1){
                                        echo "Not Received";
                                    }

                                    if($data['isLostStatus']==2 && $data['chequeStatus']==''){
                                        echo "Received";
                                    }

                                    if($data['isLostStatus']==2 && $data['chequeStatus']=='New'){
                                        echo "Received, but not banked";
                                    }

                                    if($data['isLostStatus']==2 && $data['chequeStatus']=='Banked'){
                                        echo "Banked, But Not Cleared";
                                    }

                                    if($data['chequeStatus']=='Bounced' || $data['chequeStatus']=='Bounced&Returned'){
                                        echo "Bounced";
                                    }

                                    if($data['isLostStatus']==2 && $data['chequeStatus']=='Cleared'){
                                        echo "Cleared";
                                    }
                                }


                                if($data['paymentMode']=='NEFT'){
                                    if($data['isLostStatus']==1){
                                        echo "Not Received";
                                    }

                                    if($data['isLostStatus']==2 && $data['chequeStatus']==''){
                                        echo "Received,But not cleared";
                                    }

                                    if($data['isLostStatus']==2 && $data['chequeStatus']=='New'){
                                        echo "Received,But not cleared";
                                    }
                                    if($data['isLostStatus']==2 && $data['chequeStatus']=='Received'){
                                        echo "Cleared";
                                    }

                                    if($data['isLostStatus']==2 && $data['chequeStatus']=='Not Received'){
                                        echo "Rejected";
                                    }
                                }

                            }

                            ?>
                             
                        </td>
                        <td>
                            <?php

                                if($data['paymentMode']=='CD'){
                                    $cdRemark=$this->AllocationByManagerModel->getCdRemark('bill_remark_history',$data['paidAmount'],$data['billId']);
                                    // print_r($cdRemark);
                                    if(!empty($cdRemark)){
                                        echo $cdRemark[0]['remark'];
                                    }
                                }

                                if($data['paymentMode']=='Office Adjustment'){
                                    $officeRemark=$this->AllocationByManagerModel->getCdRemark('bill_remark_history',$data['paidAmount'],$data['billId']);
                                    // print_r($cdRemark);
                                    if(!empty($officeRemark)){
                                        echo $officeRemark[0]['remark'];
                                    }
                                }

                                if($data['paymentMode']=='Other Adjustment'){
                                    $otherRemark=$this->AllocationByManagerModel->getCdRemark('bill_remark_history',$data['paidAmount'],$data['billId']);
                                    // print_r($cdRemark);
                                    if(!empty($otherRemark)){
                                        echo $otherRemark[0]['remark'];
                                    }
                                }

                                if($data['paymentMode']=='Emp Delivery'){
                                    $officeRemark=$this->AllocationByManagerModel->getCdRemark('bill_remark_history',$data['paidAmount'],$data['billId']);
                                    // print_r($cdRemark);
                                    if(!empty($officeRemark)){
                                        echo $officeRemark[0]['remark'];
                                    }
                                }

                                $chequeDate=date_create($data['chequeDate']);
                                $chequeCreatedDate = date_format($chequeDate,'d-M-Y');

                                $neftDate=date_create($data['neftDate']);
                                $neftCreatedDate = date_format($neftDate,'d-M-Y');

                                if($data['paymentMode']=='Cheque'){
                                    if($data['isLostStatus']==1){
                                        echo "-";
                                    }

                                    if($data['isLostStatus']==2 && $data['chequeStatus']==''){
                                        echo "Received but not saved ";
                                    }

                                    if($data['isLostStatus']==2 && $data['chequeStatus']=='New'){
                                        echo "Cheque No. ".$data['chequeNo'].' dated '.$chequeCreatedDate.' Bank '.$data['chequeBank'];
                                    }

                                    if($data['isLostStatus']==2 && $data['chequeStatus']=='Banked'){
                                        echo "Cheque No. ".$data['chequeNo'].' dated '.$chequeCreatedDate.' Bank '.$data['chequeBank'];
                                    }

                                    if($data['isLostStatus']==2 && $data['chequeStatus']=='Bounced'){
                                        echo "Cheque No. ".$data['chequeNo'].' dated '.$chequeCreatedDate.' Bank '.$data['chequeBank'];
                                    }

                                    if($data['isLostStatus']==2 && $data['chequeStatus']=='Cleared'){
                                        echo "Cheque No. ".$data['chequeNo'].' dated '.$chequeCreatedDate.' Bank '.$data['chequeBank'];
                                    }
                                }

                                if($data['paymentMode']=='NEFT'){
                                    if($data['isLostStatus']==1){
                                        echo "-";
                                    } 

                                    if($data['isLostStatus']==2 && $data['chequeStatus']==''){
                                        echo "Received but not saved ";
                                    }

                                    if($data['isLostStatus']==2 && $data['chequeStatus']=='New'){
                                        echo "NEFT No. ".$data['neftNo'].' dated '.$neftCreatedDate;
                                    }

                                    if($data['isLostStatus']==2 && $data['chequeStatus']=='Received'){
                                        echo "NEFT No. ".$data['neftNo'].' dated '.$neftCreatedDate;
                                    }

                                    if($data['isLostStatus']==2 && $data['chequeStatus']=='Not Received'){
                                        echo "NEFT No. ".$data['neftNo'].' dated '.$neftCreatedDate;
                                    }
                                }

                            ?>
                        </td>
                        
                      </tr>

                        <?php
                        }
                    }
                }


                if(!empty($billOfficeAdj)){
                    foreach ($billOfficeAdj as $data) 
                    {
                       $no++; 

                      $dt=date_create($data['updatedAt']);
                      $createdDate = date_format($dt,'d-M-Y');
                      if($data['amount']>0){
                    ?>
                      <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php echo $data['allocationCode']; ?></td>
                        <td><?php echo $data['name']; ?></td>
                        <td><?php echo $createdDate; ?></td>
                        <td><?php echo $data['amount']; ?></td>
                        <td>Office Adjustment</td>
                        <td>
                        <?php echo $data['transactionType']; ?>
                        </td>
                        
                      </tr>

                        <?php
                        }
                    }
                }
                ?> 
        </tbody>
       </table>

      <?php 
         }

      if(!empty($billSr)){ ?>
       <table style="font-size: 12px" class="table table-bordered table-striped table-hover dataTable js-exportable" data-page-length='100'>

        <thead>
            <tr colspan="6" align="center">
                <th>
                  <span style="color:blue"> Bill SR/FSR Details  </span>
              </th>
            </tr>
        </thead>
        <thead>
            <tr class="gray">
               <th> Sr No  </th>
                 <th> Allocation Code  </th>
                 <th>Date</th>
                 <th>Item Name</th>
                <th> Quantity  </th>
                <th> SR Quantity </th>

            </tr>
        </thead>
        <tbody>
            <?php
               
                
                    $no=0;
                    foreach ($billSr as $data) 
                    {
                       $no++;

                      $dt=date_create($data['createdDate']);
                      $createdDate = date_format($dt,'d-M-Y');
                   
                   ?>
                        <tr>
                   
                        <td><?php echo $no; ?></td>
                        <td><?php echo $data['allocationCode']; ?></td>
                        <td><?php echo $createdDate; ?></td>
                        <td><?php echo $data['productName']; ?></td>
                        <td><?php echo $data['qty']; ?></td>
                        <td><?php echo $data['sr_qty']; ?></td>
                        
                      </tr>

                    <?php
                    }
                
                ?>

            </tbody>
        </table>
    <?php } 
    
    }

}

?>
