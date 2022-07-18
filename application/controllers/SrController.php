<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
class SrController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('SrModel');
        $this->load->library('session');
        $this->load->library('pagination');
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

    public function outstandingBills()
    {
        $this->load->library('pagination');

        $config['base_url'] = base_url('index.php/SrController/outstandingBills');
        
        $config['per_page'] = ($this->input->get('limitRows')) ? $this->input->get('limitRows') : 25;
        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;

        $data['currentAllocations']=$this->SrModel->getCurrentOpenAllocations('allocations');
        $data['company']=$this->SrModel->getdata('company');
        $data['bank']=$this->SrModel->getdata('bank');
        $cmp=$this->input->post('cmp');
        $data['cmpName']=$cmp;
        $data['emp']=$this->SrModel->getdataActive('employee');

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
        $outstanding = $this->SrModel->paginationOutstandingBills('bills',$config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);
        $rowCounts=$this->SrModel->countPaginationOutstandingBills('bills',$config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);
        // $rowCounts=count($rowCounts);

        $data['outstanding'] = $outstanding;
        $config['total_rows'] = $rowCounts;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('Manager/paginationOutstanding',$data);
    }


    public function salesmanOutstandingBills()
    {
        $userId = ($this->session->userdata[$this->projectSessionName]['id']);

        $this->load->library('pagination');

        $config['base_url'] = base_url('index.php/SrController/salesmanOutstandingBills');
        
        $config['per_page'] = ($this->input->get('limitRows')) ? $this->input->get('limitRows') : 100;
        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;

        $data['currentAllocations']=$this->SrModel->getCurrentOpenAllocations('allocations');
        $data['company']=$this->SrModel->getdata('company');
        $data['bank']=$this->SrModel->getdata('bank');
        $cmp=$this->input->post('cmp');
        $data['cmpName']=$cmp;
        $data['emp']=$this->SrModel->getdata('employee');

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

        $provisioanloutstanding = $this->SrModel->salesmanProvisionalOutstandingBills('bills',$userId,$config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);
        $provisionalrowCounts=$this->SrModel->countSalesmanProvisionalOutstandingBills('bills',$userId,$config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);
        $outstanding = $this->SrModel->salesmanPaginationOutstandingBills('bills',$userId,$config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);
        $rowCounts=$this->SrModel->countSalesmanPaginationOutstandingBills('bills',$userId,$config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);

        $outstanding=array_merge($outstanding,$provisioanloutstanding);
        $rowCounts=$rowCounts+$provisionalrowCounts;

        $data['outstanding'] = $outstanding;
        $config['total_rows'] = $rowCounts;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('Manager/paginationOutstanding',$data);
    }

    public function allBills()
    {
        $this->load->library('pagination');

        $config['base_url'] = base_url('index.php/SrController/allBills');
        
        $config['per_page'] = ($this->input->get('limitRows')) ? $this->input->get('limitRows') : 28770;
        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;

        $data['currentAllocations']=$this->SrModel->getCurrentOpenAllocations('allocations');
        $data['company']=$this->SrModel->getdata('company');
        $data['bank']=$this->SrModel->getdata('bank');
        $cmp=$this->input->post('cmp');
        $data['cmpName']=$cmp;
        $data['emp']=$this->SrModel->getdata('employee');

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
        $outstanding = $this->SrModel->paginationAllBills('bills',$config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);
        $rowCounts=$this->SrModel->countPaginationAllBills('bills',$config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);

        $data['outstanding'] = $outstanding;
        $config['total_rows'] = $rowCounts;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('allBillsTransactionsView',$data);
    }

    public function index()
    {
        $data['allocations']=$this->SrModel->getAllAllocations('allocations');
        $this->load->view('AllocationWiseSRView',$data);
    }

    public function usr()
    {
        $data['srBills']=$this->SrModel->loadUSrDetail('billsdetails');
        $data['fsrBills']=$this->SrModel->loadUfsrDetail('billsdetails');
        $this->load->view('BillUsrView',$data);
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
        $userId = ($this->session->userdata[$this->projectSessionName]['id']);
        $designation = ($this->session->userdata[$this->projectSessionName]['designation']);
        $des=explode(',',$designation);
        if ((in_array('owner', $des)) || (in_array('senior_manager', $des)) || (in_array('manager', $des))) {
            $data['currentAllocations']=$this->SrModel->getCurrentOpenAllocations('allocations');
            $data['company']=$this->SrModel->getdata('company');
            $data['bank']=$this->SrModel->getdata('bank');
            $cmp=$this->input->post('cmp');
            $data['cmpName']=$cmp;

            if($cmp=="" || $cmp=="General"){
                $data['lost']=$this->SrModel->loadLostBills('bills');
                $data['emp']=$this->SrModel->getdataActive('employee');
                $this->load->view('Manager/LostBillsView',$data);
            }else{
                $data['lost']=$this->SrModel->loadLostBillsWithComp('bills',$cmp);
                $data['emp']=$this->SrModel->getdataActive('employee');
                $this->load->view('Manager/LostBillsView',$data);
            }   
        }else{
            $data['currentAllocations']=$this->SrModel->getCurrentOpenAllocations('allocations');
            $data['company']=$this->SrModel->getdata('company');
            $data['bank']=$this->SrModel->getdata('bank');
            $cmp=$this->input->post('cmp');
            $data['cmpName']=$cmp;

            if($cmp=="" || $cmp=="General"){
                $data['lost']=$this->SrModel->salesmanloadLostBills('bills',$userId);
                $data['emp']=$this->SrModel->getdata('employee');
                $this->load->view('Manager/LostBillsView',$data);
            }else{
                $data['lost']=$this->SrModel->salesmanloadLostBillsWithComp('bills',$cmp,$userId);
                $data['emp']=$this->SrModel->getdata('employee');
                $this->load->view('Manager/LostBillsView',$data);
            }   
        }
        
    }

    public function outstandingBillsExport()
    {
        $outstanding=$this->SrModel->loadOutstandingBills('bills');
        $extension = "xlsx";
        if(!empty($extension)){
          $extension = $extension;
        } else {
          $extension = 'xlsx';
        }
        $this->load->helper('download');  
        $data = array();
        $data['title'] = 'Export Excel Sheet | Coders Mag';
        
        $fileName = 'Outstanding-Bills-'.time(); 
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
        $sheet->setCellValue('J1', 'Status');
     
        $rowCount = 2;
        $no=0;
        foreach ($outstanding as $element) {
            // $allocations=$this->SrModel->getAllocationDetailsOutstanding('bills',$element['id']);
            // $allocationsHistory=$this->SrModel->getAllocationDetailsByBillHistory('bills',$element['id']);
            // $officeAllocations=$this->SrModel->getOfficeAllocationDetailsByBill('bills',$element['id']);
            // $officeAllocationsHistory=$this->SrModel->getOfficeAllocationDetailsByBillHistory('bills',$element['id']);
            
            // $alStatus=0;
            // $ofcStatus=0;
            // if(!empty($allocationsHistory)){
            //     $alStatus=1;
            // }
            
            // if(!empty($officeAllocationsHistory)){
            //     $ofcStatus=1;
            // }
            
            $status="";
            $allocationNumber="";
            $allocationName="";
            $empName="";
            $status="";

            // if($element['isAllocated']==1){
            //     if(!empty($allocations)){
            //         $status= "Allocated in : ".$allocations[0]['allocationCode'];
            //     }

            //     if(!empty($officeAllocations)){
            //         $status= "Allocated in : ".$officeAllocations[0]['allocationCode'];
            //     }
            // }else{
            //     if($element['pendingAmt']==0){
            //         $status= "Cleared";
            //     }else if($element['isDirectDeliveryBill']==1){
            //         $status= "Direct Delivery";
            //     }else{
            //         if(!empty($allocationsHistory) || !empty($officeAllocationsHistory)){
            //             if(!empty($allocationsHistory)){
            //                 $status="";
            //                 if($allocationsHistory[0]['isResendBill']){
            //                     $status="Resend";
            //                 }else if($allocationsHistory[0]['isLostBill']){
            //                     $status="Lost Bill";
            //                 }else if($allocationsHistory[0]['isLostCheque']){
            //                     $status="Lost Cheque";
            //                 }else if($allocationsHistory[0]['isBounceCheque']){
            //                     $status="Bounce Cheque";
            //                 }else if($allocationsHistory[0]['isPendingNeft']){
            //                     $status="Pending NEFT";
            //                 }else if($allocationsHistory[0]['isBilled']){
            //                     $status="Billed";
            //                 }else{
            //                     $status="Billed";
            //                 }
            //                 $status= $status;
            //                 // echo $status;
            //             }

            //             if(!empty($officeAllocationsHistory)){
            //                 $status ="Already Allocated in : ".$officeAllocationsHistory[0]['allocationCode'];
            //             }
            //         }else{
            //             if($element['pendingAmt']==$element['netAmount']){
            //                 $status= "Unaccounted";
            //             }else{
            //                 $status= "Accounted";
            //             }
            //         }
            //     }
            // } 

            $allocationsHistory=$this->SrModel->getAllocationDetailsByBillHistory('bills',$element['id']);
            $officeAllocationsHistory=$this->SrModel->getOfficeAllocationDetailsByBillHistory('bills',$element['id']);
            
            $alStatus=0;
            $ofcStatus=0;
            if(!empty($allocationsHistory)){
                $alStatus=1;
            }
            
            if(!empty($officeAllocationsHistory)){
                $ofcStatus=1;
            }
            if($element['isAllocated']==1){
                $status='Allocated';
            } else if($element['billCurrentStatus']=="" && $element['netAmount']==$element['pendingAmt'] && $element['isDirectDeliveryBill']=="0" && $element['isResendBill']=="0" && $element['isLostBill']=="0" && (empty($allocationsHistory)) && (empty($officeAllocationsHistory))){
                $status='Unaccounted';
            } else if($element['billCurrentStatus']=="" && $element['netAmount']==$element['pendingAmt'] && $element['isDirectDeliveryBill']=="1"){
                $status='Direct Delivery';
            } else if($element['netAmount'] !== $element['pendingAmt']){
                $status='Accounted';
            } else if($element['netAmount'] == $element['pendingAmt'] && $element['isResendBill']=="0" && $element['isLostBill']=="1"){
                $status='Signed';
            }else if($element['isResendBill']==1){
                $status='Resend';
            } else if($element['isLostBill']==2){
                $status='Lost Bill';
            } else if($element['billCurrentStatus'] !==""){
                $status=$element['billCurrentStatus'];
            } else{
                $status='Signed';
            } 

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
            $sheet->setCellValue('J' . $rowCount, $status);
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
        // $writer->save(ROOT_UPLOAD_PATH.$fileName); 
        //redirect(HTTP_UPLOAD_PATH.$fileName); 
        // $filepath = file_get_contents(ROOT_UPLOAD_PATH.$fileName);
        // $filepath =$fileName;
        // force_download($fileName, $filepath);
        // $writer->save('php://output');
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }

    public function allBillsExport()
    {
        $outstanding=$this->SrModel->loadOutstandingBills('bills');
        $extension = "xlsx";
        if(!empty($extension)){
          $extension = $extension;
        } else {
          $extension = 'xlsx';
        }
        $this->load->helper('download');  
        $data = array();
        $data['title'] = 'Export Excel Sheet | Coders Mag';
        
        $fileName = 'All Bills -'.time(); 
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'Sr No.');
        $sheet->setCellValue('B1', 'Bill No');
        $sheet->setCellValue('C1', 'Bill Date');
        $sheet->setCellValue('D1', 'Retailer');
        $sheet->setCellValue('E1', 'Bill Amount');
        $sheet->setCellValue('F1', 'Cash');
        $sheet->setCellValue('G1', 'Cheque');
        $sheet->setCellValue('H1', 'NEFT');
        $sheet->setCellValue('I1', 'SR');
        $sheet->setCellValue('J1', 'CD');
        $sheet->setCellValue('K1', 'Office Adj');
        $sheet->setCellValue('L1', 'Other Adj');
        $sheet->setCellValue('M1', 'Debit');
        $sheet->setCellValue('N1', 'Pending Amount');
        $sheet->setCellValue('O1', 'Salesman');
        $sheet->setCellValue('P1', 'Route');
        $sheet->setCellValue('Q1', 'Company');
     
        $rowCount = 2;
        $no=0;
        foreach ($outstanding as $element) {

            $cash=$this->SrModel->getSumByType('billpayments',$element['id'],'Cash');
            $cheque=$this->SrModel->getSumByType('billpayments',$element['id'],'Cheque');
            $neft=$this->SrModel->getSumByType('billpayments',$element['id'],'NEFT');
            // $cd=$this->SrModel->getSumByType('billpayments',$element['id'],'CD');
            // $ofcAdj=$this->SrModel->getSumByType('billpayments',$element['id'],'Office Adjustment');
            // $otherAdj=$this->SrModel->getSumByType('billpayments',$element['id'],'Other Adjustment');
            // $debit=$this->SrModel->getSumByType('billpayments',$element['id'],'Debit To Employee');

            $no++;
            $dt=date_create($element['date']);
            $dt= date_format($dt,'d-M-Y');
            $sheet->setCellValue('A' . $rowCount, $no++);
            $sheet->setCellValue('B' . $rowCount, $element['billNo']);
            $sheet->setCellValue('C' . $rowCount, $dt);
            $sheet->setCellValue('D' . $rowCount, $element['retailerName']);
            $sheet->setCellValue('E' . $rowCount, $element['netAmount']);
            $sheet->setCellValue('F' . $rowCount, $cash[0]['amt']);
            $sheet->setCellValue('G' . $rowCount, $cheque[0]['amt']);
            $sheet->setCellValue('H' . $rowCount, $neft[0]['amt']);
            $sheet->setCellValue('I' . $rowCount, $element['SRAmt']);
            $sheet->setCellValue('J' . $rowCount, $element['cd']);
            $sheet->setCellValue('K' . $rowCount, $element['officeAdjustmentBillAmount']);
            $sheet->setCellValue('L' . $rowCount, $element['otherAdjustment']);
            $sheet->setCellValue('M' . $rowCount, $element['debit']);
            $sheet->setCellValue('N' . $rowCount, $element['pendingAmt']);
            $sheet->setCellValue('O' . $rowCount, $element['salesman']);
            $sheet->setCellValue('P' . $rowCount, $element['routeName']);
            $sheet->setCellValue('Q' . $rowCount, $element['compName']);
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
        // $writer->save(ROOT_UPLOAD_PATH.$fileName); 
        //redirect(HTTP_UPLOAD_PATH.$fileName); 
        // $filepath = file_get_contents(ROOT_UPLOAD_PATH.$fileName);
        // $filepath = $fileName;
        // force_download($fileName, $filepath);
        // $writer->save('php://output');
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }

    public function lostCheques()
    {
        $userId = ($this->session->userdata[$this->projectSessionName]['id']);
        $designation = ($this->session->userdata[$this->projectSessionName]['designation']);
        $des=explode(',',$designation);
        if ((in_array('owner', $des)) || (in_array('senior_manager', $des)) || (in_array('manager', $des))) {
            $data['currentAllocations']=$this->SrModel->getCurrentOpenAllocations('allocations');
            $data['company']=$this->SrModel->getdata('company');
            $data['bank']=$this->SrModel->getdata('bank');
            $cmp=$this->input->post('cmp');
            $data['cmpName']=$cmp;

            if($cmp=="" || $cmp=="General"){
                $data['lost']=$this->SrModel->lostCheque('bills');
                $data['emp']=$this->SrModel->getdata('employee');
                $this->load->view('Manager/lostChequeView',$data);
            }else{
                $data['lost']=$this->SrModel->lostChequeWithComp('bills',$cmp);
                $data['emp']=$this->SrModel->getdata('employee');
                $this->load->view('Manager/lostChequeView',$data);
            } 
        }else{
            $data['currentAllocations']=$this->SrModel->getCurrentOpenAllocations('allocations');
            $data['company']=$this->SrModel->getdata('company');
            $data['bank']=$this->SrModel->getdata('bank');
            $cmp=$this->input->post('cmp');
            $data['cmpName']=$cmp;

            if($cmp=="" || $cmp=="General"){
                $data['lost']=$this->SrModel->lostCheque('bills');
                $data['emp']=$this->SrModel->getdata('employee');
                $this->load->view('Manager/lostChequeView',$data);
            }else{
                $data['lost']=$this->SrModel->lostChequeWithComp('bills',$cmp);
                $data['emp']=$this->SrModel->getdata('employee');
                $this->load->view('Manager/lostChequeView',$data);
            } 
        }
    }

    public function unclearedNeft()
    {
        $highlightValue=3;
        $days=$this->SrModel->getdata('highlighting_days');
        if(!empty($days)){
            if($days[1]['id']==3){
                $highlightValue=$days[1]['days'];
            }
        }
        $data['highlightValue']=$highlightValue;

        $userId = ($this->session->userdata[$this->projectSessionName]['id']);
        $designation = ($this->session->userdata[$this->projectSessionName]['designation']);
        $des=explode(',',$designation);
        if ((in_array('owner', $des)) || (in_array('senior_manager', $des)) || (in_array('manager', $des))) {
            $data['currentAllocations']=$this->SrModel->getCurrentOpenAllocations('allocations');
            $data['company']=$this->SrModel->getdata('company');
            $data['bank']=$this->SrModel->getdata('bank');
            $cmp=$this->input->post('cmp');
            $data['cmpName']=$cmp;

            if($cmp=="" || $cmp=="General"){
                $data['lost']=$this->SrModel->lostNeft('bills');
                $data['emp']=$this->SrModel->getdata('employee');
                $this->load->view('Manager/lostNeftView',$data);
            }else{
                $data['lost']=$this->SrModel->lostNeftWithComp('bills',$cmp);
                $data['emp']=$this->SrModel->getdata('employee');
                $this->load->view('Manager/lostNeftView',$data);
            }
        }else{
            $data['currentAllocations']=$this->SrModel->getCurrentOpenAllocations('allocations');
            $data['company']=$this->SrModel->getdata('company');
            $data['bank']=$this->SrModel->getdata('bank');
            $cmp=$this->input->post('cmp');
            $data['cmpName']=$cmp;

            if($cmp=="" || $cmp=="General"){
                $data['lost']=$this->SrModel->lostNeft('bills');
                $data['emp']=$this->SrModel->getdata('employee');
                $this->load->view('Manager/lostNeftView',$data);
            }else{
                $data['lost']=$this->SrModel->lostNeftWithComp('bills',$cmp);
                $data['emp']=$this->SrModel->getdata('employee');
                $this->load->view('Manager/lostNeftView',$data);
            }
        }
    }

    public function resendBills(){
        $userId = ($this->session->userdata[$this->projectSessionName]['id']);

        $highlightValue=3;
        $days=$this->SrModel->getdata('highlighting_days');
        if(!empty($days)){
            if($days[1]['id']==2){
                $highlightValue=$days[1]['days'];
            }
        }
        $data['highlightValue']=$highlightValue;

        $designation = ($this->session->userdata[$this->projectSessionName]['designation']);
        $des=explode(',',$designation);
        if ((in_array('owner', $des)) || (in_array('senior_manager', $des)) || (in_array('manager', $des))) {
            $data['currentAllocations']=$this->SrModel->getCurrentOpenAllocations('allocations');
            $data['company']=$this->SrModel->getdata('company');
            $data['bank']=$this->SrModel->getdata('bank');
            $cmp=$this->input->post('cmp');
            $data['cmpName']=$cmp;
            if($cmp=="" || $cmp=="General"){
                $data['resend']=$this->SrModel->loadResendBills('bills');
                $data['emp']=$this->SrModel->getdata('employee');
                $this->load->view('Manager/ResendBillsView',$data);
            }else{
                $data['resend']=$this->SrModel->loadResendBillsWithComp('bills',$cmp);
                $data['emp']=$this->SrModel->getdata('employee');
                $this->load->view('Manager/ResendBillsView',$data);
            }
        }else{

            $data['currentAllocations']=$this->SrModel->getCurrentOpenAllocations('allocations');
            $data['company']=$this->SrModel->getdata('company');
            $data['bank']=$this->SrModel->getdata('bank');
            $cmp=$this->input->post('cmp');
            $data['cmpName']=$cmp;
            if($cmp=="" || $cmp=="General"){
                $data['resend']=$this->SrModel->loadResendBillsWithUser('bills',$userId);
                // print_r($data['resend']);exit;
                $data['emp']=$this->SrModel->getdata('employee');
                $this->load->view('Manager/ResendBillsView',$data);
            }else{
                $data['resend']=$this->SrModel->loadResendBillsWithCompWithUser('bills',$cmp,$userId);
                $data['emp']=$this->SrModel->getdata('employee');
                $this->load->view('Manager/ResendBillsView',$data);
            }
        }
    }

    public function billusr()
    {
        $data['srBills']=$this->SrModel->loadBillUSrDetail('billsdetails');
        $data['fsrBills']=$this->SrModel->loadBillUfsrDetail('billsdetails');
        $this->load->view('BillwiseUsrView',$data);
    }

  

      public function AllocationWiseSR(){
            $id=trim($this->input->post('id'));
            $srBills=$this->SrModel->loadSrDetail('billsdetails',$id);
            $fsrBills=$this->SrModel->loadFsrDetail('billsdetails',$id);
        ?>

           <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                 Allocation Wise SR Bills Items
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Allocation ID</th>
                                            <th>Bill No.</th>
                                            <th>Retailer</th>
                                            <th>Route</th>
                                            <th>Item</th>
                                            <th>Net Amount</th>
                                            <th>SR Qty</th>
                                            <th>SR Amount</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Allocation ID</th>
                                            <th>Bill No.</th>
                                            <th>Retailer</th>
                                            <th>Route</th>
                                            <th>Item</th>
                                            <th>Net Amount</th>
                                            <th>SR Qty</th>
                                            <th>SR Amount</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        
                                        <?php
                                              $no=0;
                                              if(!empty($srBills)){
                                              foreach ($srBills as $data) 
                                                {
                                                  $id=$data['id'];
                                                 $no++; 
                                          ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $data['AllocationCode']; ?></td>
                                        <td>
                                                <?php echo rtrim($data['billNo']); ?>
                                        </td>
                                        <td><?php
                                            echo $data['retailerName']; ?>
                                        </td>
                                        <td><?php echo $data['routeName']; ?></td>
                                        <td><?php echo $data['productName']; ?></td>
                                        <td><?php echo $data['netAmount']; ?></td>
                                        <td><?php echo $data['gkReturnQty']; ?></td>
                                        <td><?php echo $data['fsReturnAmt']; ?></td>
                                       
                                   </tr>  
                                     <?php
                                            }
                                        }
                                         if(!empty($fsrBills)){
                                            foreach($fsrBills as $data){
                                      ?>   
                                      <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $data['AllocationCode']; ?></td>
                                        <td>
                                                <?php echo rtrim($data['billNo']); ?>
                                        </td>
                                        <td><?php
                                            echo $data['retailerName']; ?>
                                        </td>
                                        <td><?php echo $data['routeName']; ?></td>
                                        <td><?php echo 'FSR'; ?></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                       
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
                </div>
            </div>
        
        <?php     

            // print_r($data['fsrBills']);
            // exit;
            // $this->load->view('BillSrView',$data);
      }

      public function HistoricalSr(){
            $cmpName=trim($this->input->post('compName'));
            $retName=trim($this->input->post('retailerName'));
            $billType=trim($this->input->post('billType'));
            if($cmpName != '' || $retName != '' || $billType != '' ){
                $data['srBills']=$this->SrModel->loadHistoricalSrDetailWithCRB('billsdetails',$cmpName,$retName,$billType);
                $data['fsrBills']=$this->SrModel->loadHistoricalFsrDetail('billsdetails');
            }else{
                $data['srBills']=$this->SrModel->loadHistoricalSrDetail('billsdetails');
                $data['fsrBills']=$this->SrModel->loadHistoricalFsrDetail('billsdetails');
            }     
          
            $data['comp']=$this->SrModel->getdata('company');
            $data['retNames']=$this->SrModel->getdata('retailer');
            
            $this->load->view('HistoricalSrView',$data);
      }

      public function CurrentSr(){
            $data['srBills']=$this->SrModel->loadCurrentSrDetail('billsdetails');
            $data['fsrBills']=$this->SrModel->loadCurrentFsrDetail('billsdetails');
            $this->load->view('CurrentSrView',$data);
      }

      // public function AllocationWiseUSR($id){
      //       $data['srBills']=$this->SrModel->loadUSrDetail('billsdetails',$id);
      //       $data['fsrBills']=$this->SrModel->loadUfsrDetail('billsdetails',$id);
           
      //       $this->load->view('BillUsrView',$data);
      // }

  public function deliverySlipSaleReturn()
  {
    $data['bills']=$this->SrModel->getSalesReturnDS('bills');
    $this->load->view('SalesReturnDelSlipview',$data);
}

public function load($id) 
{
    $data['billsdetails']=$this->SrModel->loadBillDetails('billsdetails', $id);
    $data['msg'] = "";
    $this->load->view('SrView',$data);
}

public function loadSrBill() 
{
    $id=$this->input->post('id');
    $data['billsdetails']=$this->SrModel->loadBillDetails('billsdetails', $id);

    ?>
    <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                              
                            <h2> Sales Returns</h2><br /><br />
                            <h2 style="color: red;">

                                                <?php 
                                                    if(!empty($data['billsdetails'])){
                                                         echo $data['billsdetails'][0]['name'];
                                                    }
                                                ?>
                                                 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
                             
                                                <?php 
                                                    if(!empty($data['billsdetails'])){
                                                         echo $data['billsdetails'][0]['billNo'];
                                                    }
                                                ?>
                                                &nbsp &nbsp&nbsp &nbsp &nbsp &nbsp
                               
                                                <?php
                                                    if(!empty($data['billsdetails'])){
                                                        $dt=date_create($data['billsdetails'][0]['Date']);
                                                        $date = date_format($dt,'d-m-Y');
                                                        echo $date;
                                                    }
                                                ?>
                            </h2>
                        </div>
                            
                        <div class="body">
                            <div class="table-responsive">
                                 <?php
                                if(!empty($msg)){?>
                                    <div class="alert alert-danger">
                                <?php
                                    echo '<p class="statusMsg" >'.$msg.'</p>';
                                ?>
                                </div>
                                    <?php
                                }
                            ?> 
                        
                            <form method="post" role="form" action="<?php echo site_url('SrController/update');?>"> 
                                <table id="SrTable" class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>ProductName</th>
                                            <th>Selling Rate</th>
                                            <th>Billed Qty</th>
                                            <th>Billed Qty(In Pcs)</th>
                                            <th>Net Amount</th>
                                            <th>Old SR</th>
                                            <th>SR Qty</th>
                                            <th>SR Unit</th>
                                            <th>Return Amt</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                         <tr>
                                            <th>Sr.No</th>
                                            <th>ProductName</th>
                                            <th>Selling Rate</th>
                                            <th>Billed Qty</th>
                                            <th>Billed Qty</th>
                                            <th>Net Amount</th>
                                            <th>Old SR</th>
                                            <th>SR Qty</th>
                                            <th>SR Unit</th>
                                            <th>Return Amt</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                          $no=0;
                                          foreach ($data['billsdetails'] as $data) 
                                            {
                                                  $prdData=$this->SrModel->prodDetails('products',$data['productName']);
                                                  $pcsQty=0;
                                                  if($data['qtyUnit']=='Case'){
                                                      $pcsQty=$data['qty']*$prdData[0]['boxQty'];
                                                  }else{
                                                      $pcsQty=$data['qty'];
                                                  }
                                                 
                                             $no++; 
                                          ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $data['productName']; ?></td>
                                         <input type="hidden" name="productName[]" value="<?php echo $data['productName']; ?>" readonly>
                                        <td>
                                            <?php echo $data['sellingRate']."/".$data['sellingUnit']; ?> 
                                            <input type="hidden" name="selAmount[]" value="<?php echo $data['sellingRate']; ?>" readonly>
                                        </td>
                                         <td>
                                            <?php echo $data['qty']." ".$data['qtyUnit']; ?>
                                        </td>
                                        <td>
                                            <?php echo $pcsQty; ?>
                                              <input type="hidden" name="qty[]" value="<?php echo $pcsQty; ?>" readonly> 
                                        </td> 
                                        <td align="right">
                                            <?php echo $data['netAmount']; ?>
                                            <input type="hidden" name="netAmount" value="<?php echo $data['netAmount']; ?>" readonly>
                                            <input type="hidden" name="qtyUnit[]" value="<?php echo $data['qtyUnit']; ?>" readonly>
                                            <input type="hidden" name="sellingUnit[]" value="<?php echo $data['sellingUnit']; ?>" readonly>
                                            <input type="hidden" name="netAmount" value="<?php echo $data['netAmount']; ?>" readonly>
                                            <!--<input type="hidden" name="selAmount[]" value="<?php echo $data['sellingRate']; ?>" readonly>         -->
                                        </td>
                                         <td>
                                             <?php echo (int)$data['returnedQty']; ?>
                                              <input type="hidden" name="id[]" value="<?php echo $data['id']; ?>">
                                             <input type="hidden" name="billId" value="<?php echo $data['billId']; ?>">
                                        </td>
                                        <td>
                                        <?php if($data['qty']==$data['returnedQty']){?>
                                            <input type="text" id="returnedQty"  class="form-control" name="returnedQty[]" readonly="readonly">
                                             <?php }else{?>
                                             <input type="text" class="form-control" name="returnedQty[]">
                                              <?php }?>
                                            
                                        </td>
                                        <td>
                                            <select name="unit[]" class="form-control">
                                                <option>Select Unit</option>
                                                <option>Case</option>
                                                <option>Pcs</option>
                                            </select>
                                        </td>
                                        <td align="right">
                                            <?php echo $data['returnAmt']; ?>  
                                             <input type="hidden" name="returnAmt[]" value="<?php echo $data['returnAmt']; ?>">             
                                        </td>
                                   </tr>  
                                     <?php
                                        }
                                      ?>   
                                    </tbody>
                                </table>
                                 <div class="col-md-12">
                                        <center>
                                            <div class="row clearfix">
                                                <div class="col-md-12">

                                                   <button id="fsr" type="button" class="btn btn-primary m-t-15 waves-effect" onclick="getFSR();">
                                                        <i class="material-icons">save</i> 
                                                        <span class="icon-name">
                                                        FSR 
                                                        </span>
                                                    </button>

                                                    <button type="submit" class="btn btn-primary m-t-15 waves-effect">
                                                        <i class="material-icons">save</i> 
                                                        <span class="icon-name">
                                                        Save
                                                        </span>
                                                    </button>
                                                    <!-- <a href="<?php echo site_url('DeliverySlipController/OutstandingBill');?>"> -->
                                                        <button data-dismiss="modal" type="button" class="btn btn-primary m-t-15 waves-effect">
                                                            <i class="material-icons">cancel</i> 
                                                            <span class="icon-name"> Cancel</span>
                                                        </button>
                                                    <!-- </a>    -->
                                                </div>
                                            </div>
                                        </center>
                                    </div>  
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    <?php
}

public function update() {
        $data['msg']='';    
        $id = $this->input->post('id');
        $billId = $this->input->post('billId');
        $name=$this->input->post('productName');
        // $mrp = $this->input->post('mrp');
        $qty = $this->input->post('qty');
        $netAmount = $this->input->post('netAmount');
        $sellingRate = $this->input->post('selAmount');
        $returnedQty = $this->input->post('returnedQty');
        $returnAmt = $this->input->post('returnAmt');
        
        $selectedUnit=$this->input->post('unit');
        $sellingUnit=$this->input->post('sellingUnit');
        $qtyUnit=$this->input->post('qtyUnit');
      
        if(empty($returnedQty)) {
            // echo "<script type='text/javascript'> alert('Please enter returnedQty'); </script>";
            $this->session->set_flashdata('emptyTxt', 'Please enter Return Quantity');
            return redirect('DeliverySlipController/OutstandingBill');
        }else{
            for ($i=0; $i < count($returnedQty); $i++) {
                if(trim($returnedQty[$i])!=""){
                    if($returnAmt[$i]=="" || $returnAmt[$i] == "0.00"){
                        $returnAmt[$i]=0;
                    }
                    
                    $retQtyInPcs=0;
                    $retAmtTest=0;
                    $prd=$this->SrModel->prodDetails('products',$name[$i]);
                    $prdBoxQty=$prd[0]['boxQty'];
                    
                    //Test Calculation for Sales Return
                    if($selectedUnit[$i]=='Case'){
                        $retQtyInPcs=$returnedQty[$i]*$prdBoxQty;
                        if($qty[$i] < $retQtyInPcs){
                            $this->session->set_flashdata('emptyTxt', 'Sales Return Quantity is greater than actual billed quantity');
                            return redirect('DeliverySlipController/OutstandingBill');
                        }else{
                            if($sellingUnit[$i]=='Pcs'){
                                $prdEachQty=$sellingRate[$i];
                                $retAmtTest=$retQtyInPcs*$sellingRate[$i];
                            }else{
                                $prdEachQty=$sellingRate[$i]/$prdBoxQty;
                                $retAmtTest=$retQtyInPcs*$prdEachQty;
                            }
                        }
                    }else if($selectedUnit[$i]=='Pcs'){
                        $retQtyInPcs=$returnedQty[$i];
                        if($qty[$i]<$retQtyInPcs){
                            $this->session->set_flashdata('emptyTxt', 'Sales Return Quantity is greater than actual billed quantity');
                            return redirect('DeliverySlipController/OutstandingBill');
                        }else{
                            if($sellingUnit[$i]=='Case'){
                                $prdEachQty=$sellingRate[$i]/$prdBoxQty;
                                $retAmtTest=$retQtyInPcs*$prdEachQty;
                            }else{
                                $prdEachQty=$sellingRate[$i];
                                $retAmtTest=$retQtyInPcs*$sellingRate[$i];
                            }
                        }
                    }
                    
                    $ReturnAmount=$retAmtTest;
                    // $ReturnAmount=$returnAmt[$i]+($sellingRate[$i] * $returnedQty[$i]);
                    $data['billsdetails']=$this->SrModel->loadBillDetailsID('billsdetails', $id[$i]);
                    if(empty($data['billsdetails'][0]['returnedQty'])){
                        $oldSR=0+$retQtyInPcs;
                    }else{
                        $oldSR=$data['billsdetails'][0]['returnedQty']+ $retQtyInPcs;
                        // $oldSR=$data['billsdetails'][0]['returnedQty']+ $retQtyInPcs[$i];
                    }
                   
                    if($qty[$i] >= $oldSR){
                        
                        $data = array
                        ('returnedQty' => $oldSR,
                            'returnAmt' =>  $data['billsdetails'][0]['returnAmt']+$ReturnAmount
                        ); 
                       
                        $this->SrModel->update('billsdetails',$data,$id[$i]);
                        
                        if($this->db->affected_rows()>0){
                            if($returnedQty[$i]!='' || $returnedQty[$i]!=null){
                                // $resSRP=$this->SrModel->updateSRPamt('bills',($sellingRate[$i] * $returnedQty[$i]),$billId);
                                $resSRP=$this->SrModel->updateSRPamt('bills',$retAmtTest,$billId);
                                if($this->db->affected_rows()>0){
                                    // $resSRP=$this->SrModel->updateAvailQty('products',$returnedQty[$i],$name[$i]);
                                     $resSRP=$this->SrModel->updateAvailQty('products',$retQtyInPcs,$name[$i]);
                                    if($this->db->affected_rows()>0){
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
                        echo "<script type='text/javascript'> alert('Sale Return Quantity can not be greater than Billed Quantity'); </script>";
                        echo "<script type='text/javascript'> parent.$.fn.colorbox.close(); </script>";
                        $data['billsdetails']=$this->SrModel->loadBillDetails('billsdetails', $billId);
                    } 
                }
            }
            $data['bills']=$this->SrModel->getdataOutstandingBill('bills');
            $this->load->view('OutstandingBillView',$data);
            // echo "<script type='text/javascript'> parent.$.fn.colorbox.close();window.parent.location.reload(true);
            // </script>";
            // $this->load->view('SrView',$data);
        }
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

    public function fixDebit(){
        $id=$this->input->post('id');
        $data['bill']=$this->SrModel->load('bills',$id);
    ?>
        <div class="modal-header">
            <h4 class="modal-title">Debit Amount</h4>
          </div>
          <div class="modal-body">
            <form action="" method="post">
              <div class="col-md-12">
                  <p>Bill No. : <?php echo $data['bill'][0]['billNo']; ?></p>
                  <p>Retailer Name : <?php echo $data['bill'][0]['retailerName']; ?></p>
                  <p>Salesman : <?php echo $data['bill'][0]['salesman']; ?></p>
                  <p>Net Amount : <?php echo $data['bill'][0]['netAmount']; ?></p>
                  <p>Pending Amount : <?php echo $data['bill'][0]['pendingAmt']; ?></p>
              </div>
                
              <div class="row clearfix">
                <div class="col-md-12">
                    <br>
                   <div class="col-md-6">                                       
                        <p>
                          <b>  Amount </b>
                        </p>
                         <div class="form-line">
                                <input type="text" id="debitAmt" name="debitAmt" placeholder="Please enter Debit Amount" value="<?php echo $data['bill'][0]['pendingAmt']; ?>" class="form-control">           
                            </div>  
                            <div style="color:red;" id="sr_qty"></div>          
                    </div>
                  
                </div>
                <br><br>
                <div class="col-md-12">
                    <div class="col-md-4">
                        <button class="btn btn-primary m-t-15 waves-effect">Submit</button>
                        <button data-dismiss="modal" class="btn btn-primary m-t-15 waves-effect">Cancel</button>    
                    </div>
                </div>
             </div>
          </form>
                    
      </div>
    <?php
        
    }

    public function processResend(){
        $id=$this->input->post('id');
        $data['bill']=$this->SrModel->load('bills',$id);
        
?>
        <div class="modal-header">
            <h4 class="modal-title">Operations</h4>
        </div>
          <div class="modal-body">
              <div class="row clearfix">
                  <div class="col-md-12">
                      <p>Bill No. : <?php echo $data['bill'][0]['billNo']; ?></p>
                      <p>Retailer Name : <?php echo $data['bill'][0]['retailerName']; ?></p>
                      <p>Salesman : <?php echo $data['bill'][0]['salesman']; ?></p>
                      <p>Net Amount : <?php echo $data['bill'][0]['netAmount']; ?></p>
                      <p>Pending Amount : <?php echo $data['bill'][0]['pendingAmt']; ?></p>
                  </div>
                  
                <div class="col-md-12">
                    <div class="col-md-4">
                       
                        <button id="modalLinkCash" data-toggle="modal" data-target="#cashModal" data-cashId="<?php echo $id; ?>" class="btn btn-primary m-t-15 waves-effect">Cash</button>
                    </div>
                    <div class="col-md-4">
                        <button id="modalLinkCheque" data-toggle="modal" data-target="#chequeModal" data-chequeId="<?php echo $id; ?>" class="btn btn-primary m-t-15 waves-effect">Cheque</button>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary m-t-15 waves-effect">SR/FSR</button>
                    </div>
                    <div class="col-md-4">
                        <button id="modalLinkCD" data-toggle="modal" data-target="#cdModal" data-cdId="<?php echo $id; ?>" class="btn btn-primary m-t-15 waves-effect">CD</button>
                    </div>
                    <div class="col-md-4">
                        <button id="modalLinkDebit" data-toggle="modal" data-target="#debitModal" data-debitId="<?php echo $id; ?>" class="btn btn-primary m-t-15 waves-effect">Debit</button>
                    </div>
                    <div class="col-md-4">
                        <button data-dismiss="modal" class="btn btn-primary m-t-15 waves-effect">Cancel</button>
                    </div>
                    
                </div>
          </div>
          </div>
<?php
        
    }
    
    public function processCash(){
        // $id=$this->input->post('id');
        
?>
        
         <div class="modal-header">
            <h4 class="modal-title">Cash</h4>
          </div>
          <div class="modal-body">
            <form action="" method="post">
              <div class="row clearfix">
                <div class="col-md-12">
                   <?php echo $id; ?>
                   <div class="col-md-6">                                       
                        <p>
                          <b> Cash Amount</b>
                        </p>
                         <div class="form-line">
                                <input type="text" id="cashAmt" name="cashAmt" placeholder="Please enter Cash" class="form-control">           
                            </div>  
                            <div style="color:red;" id="sr_qty"></div>          
                    </div>
                  
                </div>
                <br><br>
                <div class="col-md-12">
                    <div class="col-md-4">
                        <button class="btn btn-primary m-t-15 waves-effect">Submit</button>
                        <button data-dismiss="modal" class="btn btn-primary m-t-15 wave-effect">Cancel</button>
                    </div>
                </div>
             </div>
          </form>
                    
      </div>
        
<?php
    }
    
    public function processCheque(){
        $id=$this->input->post('id');
        $data['bill']=$this->SrModel->load('bills',$id);
        
?>
        <div class="modal-header">
            <h4 class="modal-title">Cheque </h4>
          </div>
          <div class="modal-body">
            <form action="" method="post">
              <div class="row clearfix">
                <div class="col-md-12">
                   
                   <div class="col-md-6">                                       
                        <p>
                          <b> Cheque Amount</b>
                        </p>
                         <div class="form-line">
                                <input type="text" id="chequeAmt" name="chequeAmt" placeholder="Please enter Cheque" class="form-control">           
                            </div>  
                            <div style="color:red;" id="sr_qty"></div>          
                    </div>
                  
                </div>
                <br><br>
                <div class="col-md-12">
                    <div class="col-md-4">
                        <button class="btn btn-primary m-t-15 waves-effect">Submit</button>
                        <button data-dismiss="modal" class="btn btn-primary m-t-15 waves-effect">Cancel</button>    
                    </div>
                </div>
             </div>
          </form>
                    
      </div>
<?php
    }
    
    public function processCD(){
        $id=$this->input->post('id');
        $data['bill']=$this->SrModel->load('bills',$id);
        
    ?>
    <div class="modal-header">
            <h4 class="modal-title">CD</h4>
          </div>
          <div class="modal-body">
            <form action="" method="post">
              <div class="row clearfix">
                <div class="col-md-12">
                   
                   <div class="col-md-6">                                       
                        <p>
                          <b> CD Amount </b>
                        </p>
                         <div class="form-line">
                                <input type="text" id="cdAmt" name="cdAmt" placeholder="Please enter CD Amount" class="form-control">           
                            </div>  
                            <div style="color:red;" id="sr_qty"></div>          
                    </div>
                  
                </div>
                <br><br>
                <div class="col-md-12">
                    <div class="col-md-4">
                        <button class="btn btn-primary m-t-15 waves-effect">Submit</button>
                        <button data-dismiss="modal" class="btn btn-primary m-t-15 waves-effect">Cancel</button>    
                    </div>
                </div>
             </div>
          </form>
                    
      </div>
    
    <?php
    }
    
    public function processDebit(){
        $id=$this->input->post('id');
        $data['bill']=$this->SrModel->load('bills',$id);
        $data['emp']=$this->SrModel->getdata('bills');
        ?>
        <div class="modal-header">
            <h4 class="modal-title">Debit</h4>
          </div>
          <div class="modal-body">
            <form action="" method="post">
              <div class="row clearfix">
                <div class="col-md-12">
                   
                   <div class="col-md-6">                                       
                        <p>
                          <b> Employee </b>
                        </p>
                         <div class="form-line">
                                <select name="emp">
                                    <?php foreach($data['emp'] as $e){ ?>
                                    `   <option><?php  echo $e['name']; ?></option>        
                                    <?php   }  ?>
                                </select>       
                            </div>  
                            <div style="color:red;" id="sr_qty"></div>          
                    </div>
                    
                     <div class="col-md-6">                                       
                        <p>
                          <b> Employee </b>
                        </p>
                        <div class="form-line">
                              <input type="text" name ="debitEmpAmt" class="form-control" value="<?php echo $data['bill'][0]['penndingAmt']; ?>">   
                        </div>  
                                  
                    </div>
                  
                </div>
                <br><br><br>
                <div class="col-md-12">
                   
                        <button class="btn btn-primary m-t-15 waves-effect">Cash & Debit</button>
                        <button class="btn btn-primary m-t-15 waves-effect">SR & Debit</button>
                        <button data-dismiss="modal" class="btn btn-primary m-t-15 waves-effect">Cancel</button>    
                    
                </div>
             </div>
          </form>
                    
      </div>
        <?php
        
    }

    public function updateChequeForm(){
        $billPaymentId=trim($this->input->post('id'));
        $billId=trim($this->input->post('bill'));
        $retailer=trim($this->input->post('retailer'));
        $amount=trim($this->input->post('amount'));

        $billDetail=$this->SrModel->load('bills',$billId);
        $banks=$this->SrModel->getdata('bank');
?>

    <form action="<?php echo site_url('SrController/finalizeChequeDetail');?>" onsubmit="return chkMsg();" method="post">
              <div class="row clearfix">
                <div class="col-md-12">
                   <input type="hidden" name="billPaymentId" placeholder="Please enter Cash" class="form-control" value="<?php echo $billPaymentId;?>">
                   <div class="col-md-6">                                       
                        <p>
                          <b> Cheque Amount</b>
                        </p>
                         <div class="form-line">
                                <input type="text" autocomplete="off" readonly id="chequeAmt" name="chequeAmt" placeholder="Please enter cheque amount" value="<?php echo $amount;?>" class="form-control">           
                            </div>  
                            <div style="color:red;" id="amt_err"></div>          
                    </div>

                    <div class="col-md-6">                                       
                        <p>
                          <b> Retailer</b>
                        </p>
                         <div class="form-line">
                                <input type="text" autocomplete="off" id="rname" name="rname" placeholder="Please enter retailer name" class="form-control" value="<?php echo $retailer;?>">           
                            </div>  
                            <div style="color:red;" id="ret_err"></div>          
                    </div>

                

                    
                  
                </div>
                <br><br> <br><br>
                
                <div class="col-md-12">
                   <div class="col-md-6">                                       
                        <p>
                          <b> Cheque Number</b>
                        </p>
                         <div class="form-line">
                                <input type="text" autocomplete="off" onchange="dupCheque();" id="chequeNum" name="chequeNum" placeholder="Please enter cheque number" class="form-control">           
                            </div>  
                            <div style="color:red;" id="chkNo_err"></div>  
                            <div style="color:red;" id="chkNoErr"></div>          
                    </div>

                    <div class="col-md-6">                                       
                        <p>
                          <b> Cheque Date</b>
                        </p>
                         <div class="form-line">
                                <input type="date" onchange="dupCheque();" id="chequeDate" name="chequeDate" placeholder="Please enter retailer name" class="form-control">           
                            </div>  
                                     
                    </div>
                  
                </div>
                <br><br> <br><br>
                <div class="col-md-12">
                       <div class="col-md-6">                                       
                        <p>
                          <b> Bank</b>
                        </p>
                         <div class="form-line">
                                <input type="text" id="bankName" autocomplete="off" list="routeBi" name="bankName" placeholder="Please enter bank name" class="form-control">  
                                <datalist id="routeBi">
                                    <?php
                                        foreach($banks as $data){
                                            $name=$data['name'];
                                    ?>   
                                    <option value="<?php echo $name;?>"/>
                                    <?php    
                                        }
                                    ?>
                                </datalist>         
                            </div>  
                            <div style="color:red;" id="sr_qty"></div>          
                    </div>

                    <div class="col-md-6">                                       
                        <p>
                          <b> Company</b>
                        </p>
                         <div class="form-line">
                                <input type="text" autocomplete="off" readonly id="compName" name="compName" placeholder="Please enter company name" value="<?php echo $billDetail[0]['compName']; ?>" class="form-control">           
                            </div>  
                            <div style="color:red;" id="sr_qty"></div>          
                    </div>
                  
                </div>
                <br><br> <br><br>
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div style="color:red;display: none" id="field_err"></div>  
                        <button class="btn btn-primary m-t-15 waves-effect">Submit</button>
                        <button data-dismiss="modal" class="btn btn-primary m-t-15 wave-effect">Cancel</button>
                    </div>
                </div>
             </div>
          </form>
<?php

    }


     public function checkChequeDetails(){
          $this->load->model('CashAndChequeModel');
        $chequeNo = $this->input->post('chequeNo');
        $chequeDate = $this->input->post('chequeDate');
        $payAmount = $this->input->post('payAmount');
        // echo $chequeNo.' '.$chequeDate.' '.$payAmount;exit;

        $isChequeNoPresent=$this->CashAndChequeModel->findChequeNo('billpayments',$chequeNo,$chequeDate,$payAmount);
        // print_r($isChequeNoPresent);exit;
        if((empty($isChequeNoPresent))){
            echo "";
        }else{
            echo "Cheque already present.";
        }
    }

    public function finalizeChequeDetail(){
        $billPaymentId=trim($this->input->post('billPaymentId'));
        $chequeAmt=trim($this->input->post('chequeAmt'));
        $chequeNum=trim($this->input->post('chequeNum'));
        $chequeDate=trim($this->input->post('chequeDate'));
        $bankName=trim($this->input->post('bankName'));
        $compName=trim($this->input->post('compName'));

        $billPayDetails=$this->SrModel->load('billpayments',$billPaymentId);
        if(!empty($billPayDetails)){
            $billId=$billPayDetails[0]['billId'];
            $billInfo=$this->SrModel->load('bills',$billId);

            $upReceivedAmt=$billInfo[0]['receivedAmt']+$chequeAmt;
            $upPendingAmt=$billInfo[0]['pendingAmt']-$chequeAmt;

            $updateDetail=array('collectedAmt'=>$chequeAmt,'chequeNo'=>$chequeNum,'chequeBank'=>$bankName,'chequeDate'=>$chequeDate,'chequeStatusDate' =>date("Y-m-d H:i:sa"),'chequeReceivedDate' =>date("Y-m-d H:i:sa"),'chequeStatus'=>'New','compName'=>$compName,'isLostStatus'=>'2');
            $this->SrModel->update('billpayments',$updateDetail,$billPaymentId);

            $updateBillInfo=array(
                'receivedAmt'=>$upReceivedAmt,
                'pendingAmt'=>$upPendingAmt
            );
            $this->SrModel->update('bills',$updateBillInfo,$billId);
        }
        redirect('SrController/lostCheques');
    }

   

   public function updateNeftForm(){
        $billPaymentId=trim($this->input->post('id'));
        $billId=trim($this->input->post('bill'));
        $retailer=trim($this->input->post('retailer'));
        $amount=trim($this->input->post('amount'));

        $billDetail=$this->SrModel->load('bills',$billId);
        $banks=$this->SrModel->getdata('bank');
?>

    <form action="<?php echo site_url('SrController/finalizeNeftDetail');?>" onsubmit="return chkMsg();" method="post">
              <div class="row clearfix">
                <div class="col-md-12">
                   <input type="hidden" name="billPaymentId" placeholder="Please enter Cash" class="form-control" value="<?php echo $billPaymentId;?>">
                   <div class="col-md-6">                                       
                        <p>
                          <b> NEFT Amount</b>
                        </p>
                         <div class="form-line">
                                <input type="text" id="neftAmt" name="neftAmt" placeholder="Please enter neft amount" value="<?php echo $amount;?>" class="form-control">           
                            </div>  
                            <div style="color:red;" id="sr_qty"></div>          
                    </div>

                    <div class="col-md-6">                                       
                        <p>
                          <b> Retailer</b>
                        </p>
                         <div class="form-line">
                                <input type="text" id="rname" name="rname" placeholder="Please enter retailer name" class="form-control" value="<?php echo $retailer;?>">           
                            </div>  
                            <div style="color:red;" id="sr_qty"></div>          
                    </div>
                  
                </div>
                <br><br> <br><br>
                
                <div class="col-md-12">
                   <div class="col-md-6">                                       
                        <p>
                          <b> NEFT Number</b>
                        </p>
                         <div class="form-line">
                                <input type="text" id="neftNum" name="neftNum" placeholder="Please enter neft number" class="form-control">           
                            </div>  
                            <div style="color:red;" id="sr_qty"></div>          
                    </div>

                    <div class="col-md-6">                                       
                        <p>
                          <b> NEFT Date</b>
                        </p>
                         <div class="form-line">
                                <input type="date" id="neftDate" name="neftDate" placeholder="Please enter neft date" class="form-control">           
                            </div>  
                            <div style="color:red;" id="sr_qty"></div>          
                    </div>
                  
                </div>
                <br><br> <br><br>
                <div class="col-md-12">
                  

                    <div class="col-md-6">                                       
                        <p>
                          <b> Company</b>
                        </p>
                         <div class="form-line">
                                <input type="text" id="compName" name="compName" placeholder="Please enter company name" value="<?php echo $billDetail[0]['compName']; ?>" class="form-control">           
                            </div>  
                            <div style="color:red;" id="sr_qty"></div>          
                    </div>
                  
                </div>
                <br><br> <br><br>
                <div class="col-md-12">
                    <div class="col-md-4">
                        <button class="btn btn-primary m-t-15 waves-effect">Submit</button>
                        <button data-dismiss="modal" class="btn btn-primary m-t-15 wave-effect">Cancel</button>
                    </div>
                </div>
             </div>
          </form>
<?php

    }

     public function finalizeNeftDetail(){
        $billPaymentId=trim($this->input->post('billPaymentId'));
        $neftAmt=trim($this->input->post('neftAmt'));
        $neftNum=trim($this->input->post('neftNum'));
        $neftDate=trim($this->input->post('neftDate'));
        $compName=trim($this->input->post('compName'));

        $billPayDetails=$this->SrModel->load('billpayments',$billPaymentId);
        if(!empty($billPayDetails)){
            $billId=$billPayDetails[0]['billId'];
            $billInfo=$this->SrModel->load('bills',$billId);

            $upReceivedAmt=$billInfo[0]['receivedAmt']+$neftAmt;
            $upPendingAmt=$billInfo[0]['pendingAmt']-$neftAmt;

            $updateDetail=array('collectedAmt'=>$neftAmt,'neftNo'=>$neftNum,'neftDate'=>$neftDate,'chequeStatusDate'=>date('Y-m-d H:i:sa'),'chequeStatus'=>'New','compName'=>$compName,'isLostStatus'=>'2');
            $this->SrModel->update('billpayments',$updateDetail,$billPaymentId);

            $updateBillInfo=array(
                'receivedAmt'=>$upReceivedAmt,
                'pendingAmt'=>$upPendingAmt
            );

            $this->SrModel->update('bills',$updateBillInfo,$billId);
        }
        redirect('SrController/unclearedNeft');
    }
}
?>