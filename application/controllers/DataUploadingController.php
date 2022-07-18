<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
class DataUploadingController extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('ExcelModel');
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

    public function someBillsUpload(){
        $this->load->view('someBillsUploadView');
    }

    //for nestle bill-details data uploading
    public function billsDataUploading(){
        // echo "hey";exit;
        $fileName=$_FILES['billFile']['name'];
        $fileType=$_FILES['billFile']['type'];
        $fileTempName=$_FILES['billFile']['tmp_name'];

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
            for ($row = 2; $row <= $highestRow; ++$row) {
                //A row selected
                $cnt++;
                
                $billNo = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                $date = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                $status = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                $retailerCode = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                $retailerName = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                $salesman=trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                $salesmanCode=trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                $routeCode=trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                $routeName=trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                $grossAmount=trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                $taxAmount=trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                $billNetAmount=trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                $cashDiscount=trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                $creditAdj=trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                $netAmount=trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                $receivedAmt=trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                $pendingAmt=trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                $billType=trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                $company=trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());
                // echo $date;exit;
                // echo 'actual '.$date.' after convert '.date('Y-m-d',strtotime($date)).' ';exit;

                $billExist=$this->ExcelModel->getBillId('bills',$billNo);
                if(empty($billExist)){
                    $data = array(
                        'billNo'=>$billNo,
                        'date'=>$date,
                        'deliveryStatus'=>$status,
                        'retailerCode'=>$retailerCode,
                        'retailerName'=>$retailerName,
                        'salesman'=>$salesman,
                        'salesmanCode'=>$salesmanCode,
                        'routeCode'=>$routeCode,
                        'routeName'=>$routeName,
                        'grossAmount'=>$grossAmount,
                        'taxAmount'=>$taxAmount,
                        'billNetAmount'=>$billNetAmount,
                        'cashDiscount'=>$cashDiscount,
                        'creditAdjustment'=>$creditAdj,
                        'netAmount'=>$netAmount,
                        'receivedAmt'=>$receivedAmt,
                        'pendingAmt'=>$pendingAmt,
                        'billType'=>$billType,
                        'compName'=>$company
                    );
                    $this->ExcelModel->insert('bills',$data);
                }
            }
        }
    }

    //loading data
    public function index(){
        $data['company']=$this->ExcelModel->getAllCompanies('company');
        $this->load->view('dataUploadingView',$data);
    }

    //check dates for uploading data
    public function checkDatesForCompany(){
        $company=$this->input->post('company');
        if($company==="Nestle"){
            $data=$this->ExcelModel->getPendingDatesFromBills('bills',$company);
            // print_r($data);
            if(!empty($data)){
                $excelDate=$data[0]['date'];
                // $next_date= date('Y-m-d', strtotime($excelDate. ' -15 days'));
                $next_date= date('Y-m-d', strtotime($excelDate));
                $date= date('d M, Y', strtotime($next_date));
                $message ="Please upload data from date: ".$date;
                $dataRes= ['date'=>$next_date,'message'=>$message];
                echo json_encode($dataRes);
            }else{
                $data=$this->ExcelModel->getDeliveredDatesFromBills('bills',$company); 
                if(!empty($data)){
                    $excelDate=$data[0]['date'];
                    // $next_date= date('Y-m-d', strtotime($excelDate. ' -15 days'));
                    $next_date= date('Y-m-d', strtotime($excelDate));
                    $date= date('d M, Y', strtotime($next_date));
                    $message ="Please upload data from date: ".$date;
                    $dataRes= ['date'=>$next_date,'message'=>$message];
                    echo json_encode($dataRes);
                }
            }
        }else if($company==="Parle"){
            $data=$this->ExcelModel->getDeliveredDatesFromBills('bills',$company); 
            if(!empty($data)){
                $excelDate=$data[0]['date'];
                // $next_date= date('Y-m-d', strtotime($excelDate. ' -15 days'));
                $next_date= date('Y-m-d', strtotime($excelDate));
                $date= date('d M, Y', strtotime($next_date));
                $message ="Please upload data from date: ".$date;
                $dataRes= ['date'=>$next_date,'message'=>$message];
                echo json_encode($dataRes);
            }
        }else if($company==="ITC"){
            $data=$this->ExcelModel->getDeliveredDatesFromBills('bills',$company); 
            if(!empty($data)){
                $excelDate=$data[0]['date'];
                // $next_date= date('Y-m-d', strtotime($excelDate. ' -15 days'));
                $next_date= date('Y-m-d', strtotime($excelDate));
                $date= date('d M, Y', strtotime($next_date));
                $message ="Please upload data from date: ".$date;
                $dataRes= ['date'=>$next_date,'message'=>$message];
                echo json_encode($dataRes);
            }
        }
    }

    //Manually cancel bills from owner
    public function cancelBillsImport(){
        $compName=$this->input->post('company');
        //file name
        $fileName=$_FILES['cancelBillFile']['name'];
        $fileType=$_FILES['cancelBillFile']['type'];
        $fileTempName=$_FILES['cancelBillFile']['tmp_name'];

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

            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
            $cnt=0;

            if($compName==="Parle"){
                for ($row = 9; $row <= $highestRow; ++$row) {
                    //A row selected
                    $cnt++;
                    
                    $billNumber = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $deliveryStatus=$worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    
                    // check bill exist or not
                    $billExist=$this->ExcelModel->getBillId('bills',$billNumber);
                    if(!empty($billExist)){
                        $billId=$billExist[0]['id'];
                        $data = array(
                          'deliveryStatus'=>'cancelled'
                        );
                        $this->ExcelModel->update('bills',$data,$billId);
                    }
                }
            }else if($compName==="ITC"){
                for ($row = 9; $row <= $highestRow; ++$row) {
                    //A row selected
                    $cnt++;
                    
                    $billNumber = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $deliveryStatus=$worksheet->getCellByColumnAndRow(14, $row)->getValue();
                    
                    // check bill exist or not
                    $billExist=$this->ExcelModel->getBillId('bills',$billNumber);
                    if(!empty($billExist)){
                        $billId=$billExist[0]['id'];
                        $data = array(
                          'deliveryStatus'=>'cancelled'
                        );
                        $this->ExcelModel->update('bills',$data,$billId);
                    }
                }
            }else if($compName==="Nestle"){
                for ($row = 9; $row <= $highestRow; ++$row) {
                    //A row selected
                    $cnt++;
                    
                    $billNumber = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $deliveryStatus=$worksheet->getCellByColumnAndRow(14, $row)->getValue();
                    
                    // check bill exist or not
                    $billExist=$this->ExcelModel->getBillId('bills',$billNumber);
                    if(!empty($billExist)){
                        $billId=$billExist[0]['id'];
                        $data = array(
                          'deliveryStatus'=>'cancelled'
                        );
                        $this->ExcelModel->update('bills',$data,$billId);
                    }
                }
            }
        }
    }

    public function uploadFile($fileName) 
    {
        $upload_path='./assets/uploads/excels'; 
        $config = array(
        'upload_path' => $upload_path,
        'overwrite'=>true,
        'allowed_types' => 'xlsx|csv|xls|ods'
        );
        $this->load->library('upload', $config);
        if(!$this->upload->do_upload($fileName)) {
            return "";
        } else {
            $uploadData = $this->upload->data();
            $fileName =  $uploadData['file_name'];
            return $fileName;
        }
    }

    public function uploadFilesForImport(){
        $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        $userId = $this->session->userdata[$this->projectSessionName]['id'];
        $compName=$this->input->post('company');
        $dateForUploadBills=$this->input->post('dateForUpload');
        
        //file name
        $bill=$_FILES['billFile']['name'];
        $billDetail=$_FILES['billDetailFile']['name'];
        $retailerDetail=$_FILES['retailerDetailFile']['name'];
        
        //file type
        $billType=$_FILES['billFile']['type'];
        $billDetailType=$_FILES['billDetailFile']['type'];
        $retailerDetailType=$_FILES['retailerDetailFile']['type'];

        //file temp_name
        $billTempName=$_FILES['billFile']['tmp_name'];
        $billDetailTempName=$_FILES['billDetailFile']['tmp_name'];
        $retailerDetailTempName=$_FILES['retailerDetailFile']['tmp_name'];

        if($bill !=="" && $billDetail !==""){
            $billFileName="";
            $billDetailFileName="";
            $retailerFileName="";
            if($bill !==""){
                $billFileName = trim($this->uploadFile('billFile'));
            }

            if($billDetail !==""){
                $billDetailFileName = trim($this->uploadFile('billDetailFile'));
            }

            if($retailerDetail !==""){
                $retailerFileName = trim($this->uploadFile('retailerDetailFile'));
            }

            $insertData=array(
                'billFile'=>$billFileName,
                'billDetailFile'=>$billDetailFileName,
                'retailerFile'=>$retailerFileName,
                'company'=>$compName,
                'uploadedDate'=>$dateForUploadBills,
                'uploadedBy'=>$userId,
                'uploadedAt'=>date('Y-m-d H:i:sa')
            );

            $this->ExcelModel->insert('uploaded_files_details',$insertData);
            if($this->db->affected_rows() > 0){
                $this->importUploadedFiles();
                echo "Files uploaded successfully";

            }
        }else{
            echo "Please select file";
        }
    }

    public function importUploadedFiles(){
        $uploadedData=$this->ExcelModel->getUploadedFileData('uploaded_files_details');
        if(!empty($uploadedData)){
            $billFile=$uploadedData[0]['billFile'];
            $billDetailFile=$uploadedData[0]['billDetailFile'];
            $retailerFile=$uploadedData[0]['retailerFile'];

            $company=$uploadedData[0]['company'];
            $uploadedDate=$uploadedData[0]['uploadedDate'];

            $billFilePath='./assets/uploads/excels/'.$billFile;
            $billDetailFilePath='./assets/uploads/excels/'.$billDetailFile;

            echo $billFilePath;

            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($billFilePath);
            $reader->setReadDataOnly(TRUE);
            $objPHPExcel = $reader->load($billFilePath);

            $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
            $highestRow = $worksheet->getHighestRow(); // e.g. 12
            echo $highestRow;
            $highestColumn = $worksheet->getHighestColumn(); // e.g M'

            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
            // $cnt=0;
            $billNumberHeader="";
            $billDateHeader="";
            $retailerNameHeader = "";
            $retailerCodeHeader = "";
            $billNetAmountHeader = "";
            $netAmountHeader = "";
            $creditAdjustmentHeader="";
            $billNumber="";
            for ($row = 2; $row <= $highestRow; ++$row) {
                $cnt++;
                for($i=1;$i<=$highestColumnIndex;$i++){
                    if($row==1){
                        if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Bill Number"){
                            $billNumberHeader= $i;
                        }

                        if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Bill Date"){
                            $billDateHeader= $i;
                        }

                        if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Retailer Code"){
                            $retailerCodeHeader= $i;
                        }

                        if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Customer Name"){
                            $retailerNameHeader= $i;
                        }

                        if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Net Amount"){
                            $billNetAmountHeader= $i;
                        }

                        if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Bill receivable amount"){
                            $netAmountHeader= $i;
                        }

                        if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Credit Adjustment"){
                            $creditAdjustmentHeader= $i;
                        }
                    }
                }

                if(($row==1) && (empty($billNumberHeader) || empty($billDateHeader) || empty($retailerCodeHeader) || empty($retailerNameHeader) || empty($billNetAmountHeader) || empty($netAmountHeader) || empty($creditAdjustmentHeader))){
                    echo "Please select correct files for uploading";
                    exit;
                } 
                 
                $billNumber = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $billDate = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $retailerName = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                $retailerCode = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                $amount = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                $netAmount = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                $creditAdjustment=$worksheet->getCellByColumnAndRow(7, $row)->getValue();
                echo $billNumber;
                exit;
            }

        }
    }

    public function importAllFiles(){
        $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        $compName=$this->input->post('company');
        $dateForUploadBills=$this->input->post('dateForUpload');
        
        //file name
        $bill=$_FILES['billFile']['name'];
        $billDetail=$_FILES['billDetailFile']['name'];
        $retailerDetail=$_FILES['retailerDetailFile']['name'];
        
        //file type
        $billType=$_FILES['billFile']['type'];
        $billDetailType=$_FILES['billDetailFile']['type'];
        $retailerDetailType=$_FILES['retailerDetailFile']['type'];

        //file temp_name
        $billTempName=$_FILES['billFile']['tmp_name'];
        $billDetailTempName=$_FILES['billDetailFile']['tmp_name'];
        $retailerDetailTempName=$_FILES['retailerDetailFile']['tmp_name'];

        if($compName==="Nestle"){
            $this->nestleExcelUploading($bill,$billType,$billTempName,$dateForUploadBills);
            $this->testnestleBillDetailsExcelUploading($billDetail,$billDetailType,$billDetailTempName);
            // $this->nestleBillDetailsExcelUploading($billDetail,$billDetailType,$billDetailTempName);
            if($retailerDetail !==""){
                $this->nestleRetailerGstExcelUploading($retailerDetail,$retailerDetailType,$retailerDetailTempName);
            }
        }else if($compName==="Parle"){
            $this->parleExcelUploading($bill,$billType,$billTempName,$dateForUploadBills);
            $this->parleBillDetailsExcelUploading($billDetail,$billDetailType,$billDetailTempName);
        }else if($compName==="ITC"){
            $this->itcExcelUploading($bill,$billType,$billTempName,$dateForUploadBills);
            $this->itcBillDetailsExcelUploading($billDetail,$billDetailType,$billDetailTempName);
        }

        // redirect('DataUploadingController');
    }

    //for nestle bills data uploading
    public function nestleExcelUploading($fileName,$fileType,$fileTempName,$dateForUploadBills){
        $batchInsert=array();
        $batchUpdate=array();
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

            //for get last 5 days records 
            // $dateForUploading = date('Y-m-d',strtotime('-5 day'));

            $reader->setReadDataOnly(true);
            $objPHPExcel = $reader->load($fileTempName);//Get filename
            
            $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
            $highestRow = $worksheet->getHighestRow(); // e.g. 12
            $highestColumn = $worksheet->getHighestColumn(); // e.g M'

            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
            $cnt=0;
            $billNumberHeader="";
            $billDateHeader="";
            $retailerNameHeader = "";
            $retailerCodeHeader = "";
            $billNetAmountHeader = "";
            $netAmountHeader = "";
            $creditAdjustmentHeader="";
            $billNumber="";
            for ($row = 1; $row <= $highestRow; ++$row) {
                //A row selected
                $cnt++;
                for($i=1;$i<=$highestColumnIndex;$i++){
                    // echo $worksheet->getCellByColumnAndRow($i, $row)->getValue().'<br>';
                    if($row==1){
                        if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Bill Number"){
                            $billNumberHeader= $i;
                        }

                        if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Bill Date"){
                            $billDateHeader= $i;
                        }

                        if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Retailer Code"){
                            $retailerCodeHeader= $i;
                        }

                        if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Customer Name"){
                            $retailerNameHeader= $i;
                        }

                        if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Net Amount"){
                            $billNetAmountHeader= $i;
                        }

                        if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Bill receivable amount"){
                            $netAmountHeader= $i;
                        }

                        if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Credit Adjustment"){
                            $creditAdjustmentHeader= $i;
                        }
                    }
                }

                if(($row==1) && (empty($billNumberHeader) || empty($billDateHeader) || empty($retailerCodeHeader) || empty($retailerNameHeader) || empty($billNetAmountHeader) || empty($netAmountHeader) || empty($creditAdjustmentHeader))){
                    echo "Please select correct files for uploading";
                    exit;
                }               
                
                $billNumber = $worksheet->getCellByColumnAndRow($billNumberHeader, $row)->getValue();
                $billDate = $worksheet->getCellByColumnAndRow($billDateHeader, $row)->getValue();
                

                $retailerName = $worksheet->getCellByColumnAndRow($retailerNameHeader, $row)->getValue();
                $retailerCode = $worksheet->getCellByColumnAndRow($retailerCodeHeader, $row)->getValue();
                $amount = $worksheet->getCellByColumnAndRow($billNetAmountHeader, $row)->getValue();
                $netAmount = $worksheet->getCellByColumnAndRow($netAmountHeader, $row)->getValue();
                $creditAdjustment=$worksheet->getCellByColumnAndRow($creditAdjustmentHeader, $row)->getValue();

                $excelDate="";
                // echo $billDate;
                if($extension==='csv'){
                    if(!empty($billDate)){
                        $excelDate=date('Y-m-d', strtotime($billDate));
                    }
                }else{
                    if(!empty($billDate) && ($billDate !=='Bill Date')){
                        $billDate =str_replace("/","-",$billDate);
                        $excelDate=date('Y-m-d', strtotime($billDate));
                    }
                    // if(!empty($billDate) && $billDate !=='Bill Date'){
                    //     $billDate =str_replace("/","-",$billDate);
                    //     $date = ($billDate - 25569) * 86400;
                    //     $excelDate=date('Y-m-d', $date);//convert date from excel data
                    // }
                }

                // if($dateForUploadBills !==""){
                //     $excelDate= date('Y-m-d', strtotime($excelDate. ' -15 days'));
                //     if($excelDate > $dateForUploadBills && $billDate !=='Bill Date'){
                //         echo "Please upload bills from date: ".$dateForUploadBills;
                //         exit;
                //     }
                // }
                // get 1st day
                $timestamp = strtotime($excelDate);
                $day= date("d", $timestamp);
                // echo $excelDate.' '.$day;exit;

                // if(($day != "01" || $day != "1") && $cnt == 1){
                //     echo "date not starting from 1st";
                //     exit;
                // }
                // echo $excelDate.'    '.$billDate;
                // check bill exist or not
                $billExist=$this->ExcelModel->getBillId('bills',$billNumber);
                if(empty($billExist)){
                    if((!empty($excelDate)) && ($excelDate != "1970-01-01") && ($billDate !=='Bill Date')){
                        $data = array(
                          'date'=>$excelDate,
                          'billNo'=>$billNumber,
                          'retailerName'=>$retailerName,
                          'retailerCode'=>$retailerCode,
                          'creditAdjustment'=>$creditAdjustment,
                          'billNetAmount'=>$amount,
                          'netAmount'=>$netAmount,
                          'pendingAmt'=>$netAmount,
                          'compName'=>'Nestle'
                        );
                        $this->ExcelModel->insert('bills',$data);
                        // array_push($batchInsert, $data);
                    }
                }else{
                    $billId=$billExist[0]['id'];
                    
                    $billDeliveryStatus=$billExist[0]['deliveryStatus'];
                    $billNetAmount=$billExist[0]['billNetAmount'];
                    $billPendingAmt=$billExist[0]['pendingAmt'];

                    $billSrAmt=$billExist[0]['SRAmt'];
                    $billReceivedAmt=$billExist[0]['receivedAmt'];
                    $billCd=$billExist[0]['cd'];
                    $billDebit=$billExist[0]['debit'];
                    $billOfficeAdjustment=$billExist[0]['officeAdjustmentBillAmount'];
                    $billOtherAdjustment=$billExist[0]['otherAdjustment'];
                    //  
                    if(($billNetAmount != $amount) && ($billDeliveryStatus==="pending" || $billDeliveryStatus==="")){
                        $totalRecAmt=$billSrAmt+$billReceivedAmt+$billCd+$billDebit+$billOfficeAdjustment+$billOtherAdjustment;
                        $newPendingAmt=$netAmount-$totalRecAmt;
                        
                        if((!empty($excelDate)) && ($excelDate != "1970-01-01")){
                            $data = array(
                              'date'=>$excelDate,
                              'billNo'=>$billNumber,
                              'retailerName'=>$retailerName,
                              'retailerCode'=>$retailerCode,
                              'creditAdjustment'=>$creditAdjustment,
                              'billNetAmount'=>$amount,
                              'netAmount'=>$netAmount,
                              'pendingAmt'=>$newPendingAmt,
                              'compName'=>'Nestle'
                            );
                            $this->ExcelModel->update('bills',$data,$billId);
                        }
                    }
                }
            }

            //batch insert in bills table
            // $this->db->insert_batch('bills', $batchInsert); 

            $lastBill=$this->ExcelModel->getlastBills('bills','Nestle');
            if(!empty($lastBill)){
                echo " \n\nFound ".$cnt." records. Total records uploaded : ".$cnt;
                echo " \n\nLast bill No : ".$lastBill[0]['billNo'];
            }else{
                echo " \n\nFound ".$cnt." records. Total records uploaded : ".$cnt;
            }
        }
    }

     //for nestle bill-details data uploading
    public function nestleBillDetailsExcelUploading($fileName,$fileType,$fileTempName){
        $batchInsert=array();
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
            //for get last 5 days records 
            // $dateForUploading = date('Y-m-d',strtotime('-5 day'));

            $reader->setReadDataOnly(true);
            $objPHPExcel = $reader->load($fileTempName);//Get filename
            
            $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
            $highestRow = $worksheet->getHighestRow(); // e.g. 12
            $highestColumn = $worksheet->getHighestColumn(); // e.g M'

            $billNumber="";
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
            $cnt=0;
            for ($row = 2; $row <= $highestRow; ++$row) {
                //A row selected
                $cnt++;
                
                $salesmanCode = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $salesmanName = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $routeCode = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                $routeName = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                $billNumber = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                $billDate=$worksheet->getCellByColumnAndRow(6, $row)->getValue();
                $deliveryStatus=$worksheet->getCellByColumnAndRow(7, $row)->getValue();
                $retailerCode=$worksheet->getCellByColumnAndRow(8, $row)->getValue();
                $retailerName=$worksheet->getCellByColumnAndRow(10, $row)->getValue();
                $productCode=$worksheet->getCellByColumnAndRow(19, $row)->getValue();
                $productName=$worksheet->getCellByColumnAndRow(20, $row)->getValue();
                $mrp=$worksheet->getCellByColumnAndRow(21, $row)->getValue();
                $quantity=$worksheet->getCellByColumnAndRow(24, $row)->getValue();
                $netAmount=$worksheet->getCellByColumnAndRow(41, $row)->getValue();

                // check bill exist or not
                $billExist=$this->ExcelModel->getBillId('bills',$billNumber);
                if(!empty($billExist)){
                    $billId=$billExist[0]['id'];
                    $getBillDetail=$this->ExcelModel->getAllBillDetails('billsdetails',$billId,$productCode,$productName,$mrp,$quantity,$netAmount);
                    // print_r($getBillDetail);exit;
                    $statusForNestleBills=strtolower($deliveryStatus);
                    if(empty($getBillDetail)){
                        if($statusForNestleBills==='pending'){
                            $data = array(
                              'salesmanCode'=>$salesmanCode,
                              'salesman'=>$salesmanName,
                              'routeCode'=>$routeCode,
                              'routeName'=>$routeName,
                              'deliveryStatus'=>$statusForNestleBills,
                              'isTempCancelled'=>1,
                              'retailerCode'=>$retailerCode,
                              'retailerName'=>$retailerName
                            );
                            // print_r($data);exit;
                            $this->ExcelModel->update('bills',$data,$billId);
                        }else{
                            $data = array(
                              'salesmanCode'=>$salesmanCode,
                              'salesman'=>$salesmanName,
                              'routeCode'=>$routeCode,
                              'routeName'=>$routeName,
                              'deliveryStatus'=>$statusForNestleBills,
                              'isTempCancelled'=>0,
                              'retailerCode'=>$retailerCode,
                              'retailerName'=>$retailerName
                            );
                            $this->ExcelModel->update('bills',$data,$billId);

                            //inserting bill details for specific bill
                            $billDetailData=array(
                                'billId'=>$billId,
                                'productCode'=>$productCode,
                                'productName'=>$productName,
                                'mrp'=>$mrp,
                                'qty'=>$quantity,
                                'netAmount'=>$netAmount,
                            );
                            array_push($batchInsert, $billDetailData);
                            // $this->ExcelModel->insert('billsdetails',$billDetailData);
                        }
                    }else{
                            $data = array(
                              'salesmanCode'=>$salesmanCode,
                              'salesman'=>$salesmanName,
                              'routeCode'=>$routeCode,
                              'routeName'=>$routeName,
                              'deliveryStatus'=>$statusForNestleBills,
                              'isTempCancelled'=>0,
                              'retailerCode'=>$retailerCode,
                              'retailerName'=>$retailerName
                            );
                            $this->ExcelModel->update('bills',$data,$billId);
                    }
                    
                    // check retailer exist or not
                    $retailerExist=$this->ExcelModel->getInfoByCode('retailer',$retailerCode);
                    if(empty($retailerExist)){
                        $retailerData=array(
                            'name'=>$retailerName,
                            'code'=>$retailerCode,
                        );
                        $this->ExcelModel->insert('retailer',$retailerData);
                    }else{
                        $retailerData=array(
                            'name'=>$retailerName
                        );
                        $this->ExcelModel->update('retailer',$retailerData,$retailerExist[0]['id']);
                    }

                    // check route exist or not
                    $routeExist=$this->ExcelModel->getInfoByCode('route',$routeCode);
                    if(empty($routeExist)){
                        $routeData=array(
                            'name'=>$routeName,
                            'code'=>$routeCode
                        );
                        $this->ExcelModel->insert('route',$routeData);
                    }else{
                        $routeData=array(
                            'name'=>$routeName
                        );
                        $this->ExcelModel->update('route',$routeData,$routeExist[0]['id']);
                    }
                }
            }

            //insertrecords in bills details
            $this->db->insert_batch('billsdetails', $batchInsert); 
            
            // echo " \n\nFound ".$cnt." records. Total records uploaded : ".$cnt;
            // echo " \n\nLast bill No : ".$billNumber;
        }
        
    }


    public function testnestleBillDetailsExcelUploading($fileName,$fileType,$fileTempName){
        $batchInsert=array();
        $tempBillNumber="";
        $tempRetailerName="";
        $tempRouteName="";
        $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        if(isset($fileName) && in_array($fileType, $file_mimes)) {
            $arr_file = explode('.', $fileName); //get file
            $extension = end($arr_file); //get file extension

            if('csv' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else if ('xlsx'){
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            }
            //for get last 5 days records 
            // $dateForUploading = date('Y-m-d',strtotime('-5 day'));

            $reader->setReadDataOnly(true);
            $objPHPExcel = $reader->load($fileTempName);//Get filename
            
            $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
            $highestRow = $worksheet->getHighestRow(); // e.g. 12
            $highestColumn = $worksheet->getHighestColumn(); // e.g M'

            $billNumber="";
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
            $cnt=0;
            for ($row = 2; $row <= $highestRow; ++$row) {
                $cnt++;
                $salesmanCode = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $salesmanName = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $routeCode = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                $routeName = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                $billNumber = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                $billDate=$worksheet->getCellByColumnAndRow(6, $row)->getValue();
                $deliveryStatus=$worksheet->getCellByColumnAndRow(7, $row)->getValue();
                $retailerCode=$worksheet->getCellByColumnAndRow(8, $row)->getValue();
                $retailerName=$worksheet->getCellByColumnAndRow(10, $row)->getValue();
                $productCode=$worksheet->getCellByColumnAndRow(19, $row)->getValue();
                $productName=$worksheet->getCellByColumnAndRow(20, $row)->getValue();
                $mrp=$worksheet->getCellByColumnAndRow(21, $row)->getValue();
                $quantity=$worksheet->getCellByColumnAndRow(24, $row)->getValue();
                $netAmount=$worksheet->getCellByColumnAndRow(41, $row)->getValue();

                // check bill exist or not
                $billExist=$this->ExcelModel->getBillId('bills',$billNumber);
                if(!empty($billExist)){
                    $billId=$billExist[0]['id'];
                    $getBillDetail=$this->ExcelModel->getAllBillDetails('billsdetails',$billId,$productCode,$productName,$mrp,$quantity,$netAmount);
                    $statusForNestleBills=strtolower($deliveryStatus);
                    if(empty($getBillDetail)){
                        if($statusForNestleBills==='pending'){
                            if(trim($billNumber) !== trim($tempBillNumber)){
                                $data = array(
                                  'salesmanCode'=>$salesmanCode,
                                  'salesman'=>$salesmanName,
                                  'routeCode'=>$routeCode,
                                  'routeName'=>$routeName,
                                  'deliveryStatus'=>$statusForNestleBills,
                                  'isTempCancelled'=>1,
                                  'retailerCode'=>$retailerCode,
                                  'retailerName'=>$retailerName
                                );
                                $this->ExcelModel->update('bills',$data,$billId);
                            }
                        }else{
                            //inserting bill details for specific bill
                            $billDetailData=array(
                                'billId'=>$billId,
                                'productCode'=>$productCode,
                                'productName'=>$productName,
                                'mrp'=>$mrp,
                                'qty'=>$quantity,
                                'netAmount'=>$netAmount,
                            );
                            $this->db->insert('billsdetails', $billDetailData); 
                            // array_push($batchInsert, $billDetailData);

                            if(trim($billNumber) !== trim($tempBillNumber)){
                                $data = array(
                                  'salesmanCode'=>$salesmanCode,
                                  'salesman'=>$salesmanName,
                                  'routeCode'=>$routeCode,
                                  'routeName'=>$routeName,
                                  'deliveryStatus'=>$statusForNestleBills,
                                  'isTempCancelled'=>0,
                                  'retailerCode'=>$retailerCode,
                                  'retailerName'=>$retailerName
                                );
                                $this->ExcelModel->update('bills',$data,$billId);
                            }
                        }
                    }
                    
                    if(trim($retailerName) !== trim($tempRetailerName)){
                        // check retailer exist or not
                        $retailerExist=$this->ExcelModel->getInfoByCode('retailer',$retailerCode);
                        if(empty($retailerExist)){
                            $retailerData=array(
                                'name'=>$retailerName,
                                'code'=>$retailerCode,
                            );
                            $this->ExcelModel->insert('retailer',$retailerData);
                        }else{
                            $retailerData=array(
                                'name'=>$retailerName
                            );
                            $this->ExcelModel->update('retailer',$retailerData,$retailerExist[0]['id']);
                        }
                    }
                    
                    if(trim($routeName) !== trim($tempRouteName)){
                        // check route exist or not
                        $routeExist=$this->ExcelModel->getInfoByCode('route',$routeCode);
                        if(empty($routeExist)){
                            $routeData=array(
                                'name'=>$routeName,
                                'code'=>$routeCode
                            );
                            $this->ExcelModel->insert('route',$routeData);
                        }else{
                            $routeData=array(
                                'name'=>$routeName
                            );
                            $this->ExcelModel->update('route',$routeData,$routeExist[0]['id']);
                        }
                    }
                }
                
                //store data in temp variables to skip duplicate entries
                $tempBillNumber=$billNumber;
                $tempRetailerName=$retailerName;
                $tempRouteName=$routeName;
            }

            //insertrecords in bills details
            // $this->db->insert_batch('billsdetails', $batchInsert); 
        }
    }

    //for nestle bill-details data uploading
    public function dummynestleBillDetailsExcelUploading($fileName,$fileType,$fileTempName){
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
            //for get last 5 days records 
            // $dateForUploading = date('Y-m-d',strtotime('-5 day'));

            $reader->setReadDataOnly(true);
            $objPHPExcel = $reader->load($fileTempName);//Get filename
            
            $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
            $highestRow = $worksheet->getHighestRow(); // e.g. 12
            $highestColumn = $worksheet->getHighestColumn(); // e.g M'

            $billNumber="";
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
            $cnt=0;
            for ($row = 2; $row <= $highestRow; ++$row) {
                //A row selected
                $cnt++;
                
                $salesmanCode = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $salesmanName = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $routeCode = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                $routeName = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                $billNumber = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                $billDate=$worksheet->getCellByColumnAndRow(6, $row)->getValue();
                $deliveryStatus=$worksheet->getCellByColumnAndRow(7, $row)->getValue();
                $retailerCode=$worksheet->getCellByColumnAndRow(8, $row)->getValue();
                $retailerName=$worksheet->getCellByColumnAndRow(10, $row)->getValue();
                $productCode=$worksheet->getCellByColumnAndRow(19, $row)->getValue();
                $productName=$worksheet->getCellByColumnAndRow(20, $row)->getValue();
                $mrp=$worksheet->getCellByColumnAndRow(21, $row)->getValue();
                $quantity=$worksheet->getCellByColumnAndRow(24, $row)->getValue();
                $netAmount=$worksheet->getCellByColumnAndRow(41, $row)->getValue();

                // check bill exist or not
                $billExist=$this->ExcelModel->getBillId('bills',$billNumber);
                if(!empty($billExist)){
                    $billId=$billExist[0]['id'];
                    $statusForNestleBills=strtolower($deliveryStatus);
                    $data = array(
                      'salesmanCode'=>$salesmanCode,
                      'salesman'=>$salesmanName,
                      'routeCode'=>$routeCode,
                      'routeName'=>$routeName,
                      'deliveryStatus'=>$statusForNestleBills,
                      'isTempCancelled'=>0,
                      'retailerCode'=>$retailerCode,
                      'retailerName'=>$retailerName
                    );
                    $this->ExcelModel->update('bills',$data,$billId);

                    //inserting bill details for specific bill
                    // $billDetailData=array(
                    //     'billId'=>$billId,
                    //     'productCode'=>$productCode,
                    //     'productName'=>$productName,
                    //     'mrp'=>$mrp,
                    //     'qty'=>$quantity,
                    //     'netAmount'=>$netAmount,
                    // );
                    // $this->ExcelModel->insert('dummy_billsdetails',$billDetailData);

                    // check retailer exist or not
                    $retailerExist=$this->ExcelModel->getInfoByCode('retailer',$retailerCode);
                    if(empty($retailerExist)){
                        $retailerData=array(
                            'name'=>$retailerName,
                            'code'=>$retailerCode,
                        );
                        $this->ExcelModel->insert('retailer',$retailerData);
                    }else{
                        $retailerData=array(
                            'name'=>$retailerName
                        );
                        $this->ExcelModel->update('retailer',$retailerData,$retailerExist[0]['id']);
                    }

                    // check route exist or not
                    $routeExist=$this->ExcelModel->getInfoByCode('route',$routeCode);
                    if(empty($routeExist)){
                        $routeData=array(
                            'name'=>$routeName,
                            'code'=>$routeCode
                        );
                        $this->ExcelModel->insert('route',$routeData);
                    }else{
                        $routeData=array(
                            'name'=>$routeName
                        );
                        $this->ExcelModel->update('route',$routeData,$routeExist[0]['id']);
                    }
                }
            }
        }
    }

    //for cancel and suggested cancel bills for Nestle
    public function cancelAndSuggestedCancelNestleBills($fileName,$fileType,$fileTempName){

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

            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
            
            $collectAllNestleBills=$this->ExcelModel->getAllNestleBills('bills');
            if(!empty($collectAllNestleBills)){
                $arrData=array();
                foreach($collectAllNestleBills as $data){
                    $arrData[]=$data['billNo'];
                }
            
                for ($row = 2; $row <= $highestRow; ++$row) {
                    $billNumber = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    foreach($collectAllNestleBills as $data){
                        $billId=$data['id'];
                        // $checkBillDetails=$this->ExcelModel->getBillDetails('billsdetails',$billId);
                        if (($data['billNo'] !== $billNumber) && ($data['deliveryStatus'] === 'pending')){
                            $updateData = array(
                              'deliveryStatus'=>'cancelled',
                            );
                            $this->ExcelModel->update('bills',$updateData,$billId);
                        }
                    }
                }
            }
        }
    }

        //for nestle retailer gst data uploading
    public function nestleRetailerGstExcelUploading($fileName,$fileType,$fileTempName){

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

            //for get last 5 days records 
            // $dateForUploading = date('Y-m-d',strtotime('-5 day'));

            $reader->setReadDataOnly(true);
            $objPHPExcel = $reader->load($fileTempName);//Get filename
            
            $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
            $highestRow = $worksheet->getHighestRow(); // e.g. 12
            $highestColumn = $worksheet->getHighestColumn(); // e.g M'

            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
            $cnt=0;
            for ($row = 6; $row <= $highestRow; ++$row) {
                //A row selected
                $cnt++;
                
                $retailerName = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                $retailerCode = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                $retailerGstNo = $worksheet->getCellByColumnAndRow(27, $row)->getValue();

                // check retailer exist or not
                $retailerExist=$this->ExcelModel->retailerInfo('retailer',$retailerName,$retailerCode);
                if(!empty($retailerExist)){
                    $retailerId=$retailerExist[0]['id'];
                    $retailerData=array(
                        'code'=>$retailerCode,
                        'gstIn'=>$retailerGstNo
                    );
                    $this->ExcelModel->update('retailer',$retailerData,$retailerId);
                }
            }
        }
    }
    
    //for itc data uploading
    public function itcExcelUploading($fileName,$fileType,$fileTempName,$dateForUploadBills){

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

            //for get last 5 days records 
            // $dateForUploading = date('Y-m-d',strtotime('-5 day'));

            $reader->setReadDataOnly(true);
            $objPHPExcel = $reader->load($fileTempName);//Get filename
            
            $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
            $highestRow = $worksheet->getHighestRow(); // e.g. 12
            $highestColumn = $worksheet->getHighestColumn(); // e.g M'
            $lastBillNumber="";

            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
            $cnt=0;
            for ($row = 12; $row <= $highestRow; ++$row) {
                //A row selected
                $cnt++;
                if($worksheet->getCellByColumnAndRow(1, $row)->getValue() != "GrandTotal:"){
                    $lastBillNumber = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                }
                $billNumber = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $billDate = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                $salesman = $worksheet->getCellByColumnAndRow(27, $row)->getValue();
                $retailerName = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                $retailerCode = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                $netAmount = $worksheet->getCellByColumnAndRow(23, $row)->getValue();
                $route=$worksheet->getCellByColumnAndRow(26, $row)->getValue();
                $retailerGstNo=$worksheet->getCellByColumnAndRow(33, $row)->getValue();

                if($billNumber != "GrandTotal:"){
                    $excelDate="";
                    if($extension==='csv'){
                        if(!empty($billDate)){
                            $excelDate=date('Y-m-d', strtotime($billDate));
                        }
                    }else{
                        if(!empty($billDate)){
                            $date = ($billDate - 25569) * 86400;
                            $excelDate=date('Y-m-d', $date);//convert date from excel data
                        }
                    }

                    if($dateForUploadBills !==""){
                        if($excelDate > $dateForUploadBills){
                            echo "Please upload bills from date: ".$dateForUploadBills;
                            exit;
                        }
                    }
                    
                    // get 1st day
                    $string = $excelDate;
                    $timestamp = strtotime($string);
                    $day= date("d", $timestamp);

                    if(($day != "01" || $day != "1") && $cnt == 1){
                        echo "date not starting from 1st";
                        exit;
                    }
                    // check bill exist or not
                    $billExist=$this->ExcelModel->getBillId('bills',$billNumber);
                    if(empty($billExist)){
                        if((!empty($excelDate)) && ($excelDate!="1970-01-01")){
                            $data = array(
                              'date'=>$excelDate,
                              'billNo'=>$billNumber,
                              'retailerName'=>$retailerName,
                              'retailerCode'=>$retailerCode,
                              'routeName'=>$route,
                              'salesman'=>$salesman,
                              'deliveryStatus'=>'delivered',
                              'billNetAmount'=>$netAmount,
                              'netAmount'=>$netAmount,
                              'pendingAmt'=>$netAmount,
                              'compName'=>'ITC'
                            );
                            $this->ExcelModel->insert('bills',$data);

                            // check retailer exist or not
                            $retailerExist=$this->ExcelModel->retailerInfo('retailer',$retailerName,$retailerCode);
                            if(empty($retailerExist)){
                                $retailerData=array(
                                    'name'=>$retailerName,
                                    'code'=>$retailerCode,
                                    'gstIn'=>$retailerGstNo
                                );
                                $this->ExcelModel->insert('retailer',$retailerData);
                            }

                            // check retailer exist or not
                            $routeExist=$this->ExcelModel->getRouteInfo('route',$route,"");
                            if(empty($routeExist)){
                                $routeData=array(
                                    'name'=>$route
                                );
                                $this->ExcelModel->insert('route',$routeData);
                            }
                        }
                    }else{
                        $billId=$billExist[0]['id'];
                    
                        $billDeliveryStatus=$billExist[0]['deliveryStatus'];
                        $billNetAmount=$billExist[0]['billNetAmount'];
                        $billPendingAmt=$billExist[0]['pendingAmt'];

                        $billSrAmt=$billExist[0]['SRAmt'];
                        $billReceivedAmt=$billExist[0]['receivedAmt'];
                        $billCd=$billExist[0]['cd'];
                        $billDebit=$billExist[0]['debit'];
                        $billOfficeAdjustment=$billExist[0]['officeAdjustmentBillAmount'];
                        $billOtherAdjustment=$billExist[0]['otherAdjustment'];
                        if(($billNetAmount != $netAmount)){
                            $totalRecAmt=$billSrAmt+$billReceivedAmt+$billCd+$billDebit+$billOfficeAdjustment+$billOtherAdjustment;
                            $newPendingAmt=$netAmount-$totalRecAmt;
                        
                            if((!empty($excelDate)) && ($excelDate!="1970-01-01")){
                                $data = array(
                                  'date'=>$excelDate,
                                  'billNo'=>$billNumber,
                                  'retailerName'=>$retailerName,
                                  'retailerCode'=>$retailerCode,
                                  'routeName'=>$route,
                                  'salesman'=>$salesman,
                                  'deliveryStatus'=>'delivered',
                                  'billNetAmount'=>$netAmount,
                                  'netAmount'=>$netAmount,
                                  'pendingAmt'=>$newPendingAmt,
                                  'compName'=>'ITC'
                                );
                                $this->ExcelModel->update('bills',$data,$billId);

                                // check retailer exist or not
                                $retailerExist=$this->ExcelModel->retailerInfo('retailer',$retailerName,$retailerCode);
                                if(empty($retailerExist)){
                                    $retailerData=array(
                                        'name'=>$retailerName,
                                        'code'=>$retailerCode,
                                        'gstIn'=>$retailerGstNo
                                    );
                                    $this->ExcelModel->insert('retailer',$retailerData);
                                }
                            }
                        }
                    }
                }
            }
            echo " \n\nFound ".$cnt." records. Total records uploaded : ".$cnt;
            echo " \n\nLast bill No : ".$lastBillNumber;
        }
    }

     //for itc bill details data uploading
    public function itcBillDetailsExcelUploading($fileName,$fileType,$fileTempName){

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

            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
            $cnt=0;
            for ($row = 12; $row <= $highestRow; ++$row) {
                //A row selected
                $cnt++;
                
                $billNumber = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $billDate=$worksheet->getCellByColumnAndRow(3, $row)->getValue();

                $productCode=$worksheet->getCellByColumnAndRow(37, $row)->getValue();
                $productName=$worksheet->getCellByColumnAndRow(38, $row)->getValue();
                $mrp=$worksheet->getCellByColumnAndRow(42, $row)->getValue();
                $quantity=$worksheet->getCellByColumnAndRow(40, $row)->getValue();
                $sellingPrice=$worksheet->getCellByColumnAndRow(42, $row)->getValue();
                $netAmount=$worksheet->getCellByColumnAndRow(49, $row)->getValue();
                
                if($productCode != "GrandTotal:"){
                    // check bill exist or not
                    $billExist=$this->ExcelModel->getBillId('bills',$billNumber);
                    if(!empty($billExist)){
                        $billId=$billExist[0]['id'];
                        $billDetailData=array(
                            'billId'=>$billId,
                            'productCode'=>$productCode,
                            'productName'=>$productName,
                            'mrp'=>$mrp,
                            'qty'=>$quantity,
                            'sellingRate'=>$sellingPrice,
                            'netAmount'=>$netAmount
                        );
                        $this->ExcelModel->insert('billsdetails',$billDetailData);
                    }
                }
               
            }
        }
    }

    //for parle data uploading
    public function parleExcelUploading($fileName,$fileType,$fileTempName,$dateForUploadBills){

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

            //for get last 5 days records 
            // $dateForUploading = date('Y-m-d',strtotime('-5 day'));

            $reader->setReadDataOnly(true);
            $objPHPExcel = $reader->load($fileTempName);//Get filename
            
            $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
            $highestRow = $worksheet->getHighestRow(); // e.g. 12
            $highestColumn = $worksheet->getHighestColumn(); // e.g M'

            $lastBillNumber="";

            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
            $cnt=0;
            for ($row = 10; $row <= $highestRow; ++$row) {
                //A row selected
                $cnt++;

                if($worksheet->getCellByColumnAndRow(1, $row)->getValue() != "Total:"){
                    $lastBillNumber = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                }
                $billNumber = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                $billDate = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
               
                $retailerName = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $netAmount = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                if($billDate != "Total:"){
                    $excelDate="";
                    if($extension==='csv'){
                        if(!empty($billDate)){
                            $excelDate=date('Y-m-d', strtotime($billDate));
                        }
                    }else{
                        if(!empty($billDate)){
                            $date = ($billDate - 25569) * 86400;
                            $excelDate=date('Y-m-d', $date);//convert date from excel data
                        }
                    }

                    if($dateForUploadBills !==""){
                        if($excelDate > $dateForUploadBills){
                            echo "Please upload bills from date: ".$dateForUploadBills;
                            exit;
                        }
                    }

                    // get 1st day
                    $string = $excelDate;
                    $timestamp = strtotime($string);
                    $day= date("d", $timestamp);
                    if(($day != "01" || $day != "1") && $cnt == 1){
                        echo "date not starting from 1st";
                        exit;
                    }

                    // check bill exist or not
                    $billExist=$this->ExcelModel->getBillId('bills',$billNumber);
                    if(empty($billExist)){
                        if((!empty($excelDate)) && ($excelDate!="1970-01-01")){
                            if($retailerName !="(cancelled)"){
                                $data = array(
                                  'date'=>$excelDate,
                                  'billNo'=>$billNumber,
                                  'retailerName'=>$retailerName,
                                  'deliveryStatus'=>'delivered',
                                  'billNetAmount'=>$netAmount,
                                  'netAmount'=>$netAmount,
                                  'pendingAmt'=>$netAmount,
                                  'compName'=>'Parle'
                                );
                                $this->ExcelModel->insert('bills',$data);
                            }
                        }
                    }else{
                        $billId=$billExist[0]['id'];
                    
                        $billDeliveryStatus=$billExist[0]['deliveryStatus'];
                        $billNetAmount=$billExist[0]['billNetAmount'];
                        $billPendingAmt=$billExist[0]['pendingAmt'];

                        $billSrAmt=$billExist[0]['SRAmt'];
                        $billReceivedAmt=$billExist[0]['receivedAmt'];
                        $billCd=$billExist[0]['cd'];
                        $billDebit=$billExist[0]['debit'];
                        $billOfficeAdjustment=$billExist[0]['officeAdjustmentBillAmount'];
                        $billOtherAdjustment=$billExist[0]['otherAdjustment'];
                        if(($billNetAmount != $netAmount)){
                            $totalRecAmt=$billSrAmt+$billReceivedAmt+$billCd+$billDebit+$billOfficeAdjustment+$billOtherAdjustment;
                            $newPendingAmt=$netAmount-$totalRecAmt;

                            if((!empty($excelDate)) && ($excelDate!="1970-01-01")){
                                if($retailerName !="(cancelled)"){
                                    $data = array(
                                      'date'=>$excelDate,
                                      'billNo'=>$billNumber,
                                      'retailerName'=>$retailerName,
                                      'deliveryStatus'=>'delivered',
                                      'billNetAmount'=>$netAmount,
                                      'netAmount'=>$netAmount,
                                      'pendingAmt'=>$newPendingAmt,
                                      'compName'=>'Parle'
                                    );
                                    $this->ExcelModel->update('bills',$data,$billId);
                                }
                            }
                        }
                    }
                }
            }
            echo " \n\nFound ".$cnt." records. Total records uploaded : ".$cnt;
            echo " \n\nLast bill No : ".$lastBillNumber;
        }
    }

    //for parle bill details data uploading
    public function parleBillDetailsExcelUploading($fileName,$fileType,$fileTempName){

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

            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
            $cnt=0;
            $sesBillNumber="";
            for ($row = 9; $row <= $highestRow; ++$row) {
                //A row selected
                $cnt++;
                
                $billNumber = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                $billDate=$worksheet->getCellByColumnAndRow(1, $row)->getValue();

                $productName=$worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $mrp=$worksheet->getCellByColumnAndRow(10, $row)->getValue();
                $quantity=$worksheet->getCellByColumnAndRow(8, $row)->getValue();
                $sellingPrice=$worksheet->getCellByColumnAndRow(10, $row)->getValue();
                $netAmount=$worksheet->getCellByColumnAndRow(11, $row)->getValue();
                
                if($productName != "Grand Total"){
                    $billId="";
                    if($billNumber !="" && $billDate !=""){
                        $sesBillNumber=$billNumber;
                    }

                    if($sesBillNumber != ""){
                        // check bill exist or not
                        $billExist=$this->ExcelModel->getBillId('bills',$sesBillNumber);
                        if(!empty($billExist)){
                            if($billNumber=="" && $billDate==""){
                                $billId=$billExist[0]['id'];
                                $billDetailData=array(
                                    'billId'=>$billId,
                                    'productName'=>$productName,
                                    'mrp'=>$mrp,
                                    'qty'=>$quantity,
                                    'sellingRate'=>$sellingPrice,
                                    'netAmount'=>$netAmount
                                );
                                $this->ExcelModel->insert('billsdetails',$billDetailData);
                            }
                        }
                    }
                }
            }
        }
    }

    public function employeeAndBankUploading(){
        $employeeFileName=$_FILES['employeeFile']['name'];
        $employeeFileType=$_FILES['employeeFile']['type'];
        $employeeFileTempName=$_FILES['employeeFile']['tmp_name'];

        $bankFileName=$_FILES['bankFile']['name'];
        $bankFileType=$_FILES['bankFile']['type'];
        $bankFileTempName=$_FILES['bankFile']['tmp_name'];

        if((!empty($employeeFileName)) && (!empty($employeeFileType)) && (!empty($employeeFileTempName))){
            $this->employeeUploadExcel($employeeFileName,$employeeFileType,$employeeFileTempName);
        }

        if((!empty($bankFileName)) && (!empty($bankFileType)) && (!empty($bankFileTempName))){
            $this->bankUploadExcel($bankFileName,$bankFileType,$bankFileTempName);
        }
        return redirect('DashbordController');
    }

    //for bank data uploading
    public function bankUploadExcel($fileName,$fileType,$fileTempName){
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

            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
            $cnt=0;
            $bankName="";
            for ($row = 5; $row <= $highestRow; ++$row) {
                //A row selected
                $cnt++;
                $bankName = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                if(trim(strlen($bankName))==0){
                    $bankName = "";
                }

                if(!empty($bankName)){
                    // check bank exist or not
                    $bankExist=$this->ExcelModel->bankInfo('bank',$bankName);
                    if(empty($bankExist)){
                        $bankData=array(
                            'name'=>$bankName
                        );
                        $this->ExcelModel->insert('bank',$bankData);
                    }
                }
            }
        }
    }

    //for bank data uploading
    public function employeeUploadExcel($fileName,$fileType,$fileTempName){
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

            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
            $cnt=0;
            $empName="";
            for ($row = 2; $row <= $highestRow; ++$row) {
                //A row selected
                $cnt++;
                $firstName = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                if(trim(strlen($firstName))==0){
                    $firstName = "";
                }

                $lastName = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                if(trim(strlen($lastName))==0){
                    $lastName = "";
                }

                $mobile = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                if(trim(strlen($mobile))==0){
                    $mobile = "";
                }

                $joiningDate = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                if(trim(strlen($joiningDate))==0){
                    $joiningDate = "";
                }

                $designation = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                if(trim(strlen($designation))==0){
                    $designation = "";
                }

                $company = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                if(trim(strlen($company))==0){
                    $company = "";
                }

                $isSalaryEmployee = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                if(trim(strlen($isSalaryEmployee))==0){
                    $isSalaryEmployee = "";
                }

                $isLoginEmployee = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                if(trim(strlen($isLoginEmployee))==0){
                    $isLoginEmployee = "";
                }

                $userName = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                if(trim(strlen($userName))==0){
                    $userName = "";
                }

                $salaryEmp=0;
                if($isSalaryEmployee==="Yes"){
                    $salaryEmp=1;
                }

                $loginEmp=0;
                if($isLoginEmployee==="Login Required"){
                    $loginEmp=1;
                }

                $companyId="";
                if(!empty($company)){
                    $companyDetail=$this->ExcelModel->getInfo('company',$company);
                    $companyId=$companyDetail[0]['id'];
                }

                $employeeDesignation="";
                if(!empty($designation)){
                    //for salesman
                    if($designation==="salesman"){
                        $employeeDesignation="salesman";
                    }

                    //for manager
                    if($designation==="Manager"){
                        $employeeDesignation="manager";
                    }

                    //for accountant
                    if($designation==="Accountant"){
                        $employeeDesignation="accountant";
                    }

                    //for deliveryman
                    if($designation==="Delivery Man"){
                        $employeeDesignation="deliveryman";
                    }

                    //for cashier
                    if($designation==="Cashier"){
                        $employeeDesignation="cashier";
                    }

                    //for godownkeeper
                    if($designation==="Godown Keeper"){
                        $employeeDesignation="godownkeeper";
                    }

                    //for owner
                    if($designation==="Owner"){
                        $employeeDesignation="owner";
                    }

                    //for senior_manager
                    if($designation==="senior_manager"){
                        $employeeDesignation="senior_manager";
                    }

                    //for operator
                    if($designation==="Operator"){
                        $employeeDesignation="operator";
                    }
                }

                $code=$this->ExcelModel->getdata('emp_code');
                $employee=$this->ExcelModel->getdata('employee');
                $empCode=$code[0]['name'].(1000+count($employee));

                $excelDate="";
                if(!empty($joiningDate)){
                    // $billDate =str_replace("/","-",$billDate);
                    $date = ($joiningDate - 25569) * 86400;
                    $excelDate=date('Y-m-d', $date);//convert date from excel data
                }



                $password="";
                if($employeeDesignation==="deliveryman"){
                    $password=md5('kiasales');
                }else{
                    $password=md5('kiasales2021');
                }
               
                $insertEmployeeData=array(
                    'code'=>trim($empCode),
                    'name'=>trim($firstName).' '.trim($lastName),
                    'email'=>trim($userName),
                    'mobile'=>trim($mobile),
                    'password'=>$password,
                    'joiningDate'=>trim($excelDate),
                    'designation'=>trim($employeeDesignation),
                    'companyId'=>trim($companyId),
                    'ownerApproval'=>1,
                    'status'=>1,
                    'isSalaryEmp'=>trim($salaryEmp),
                    'isLoginEmp'=>trim($loginEmp),
                );

                $employeeInfo=$this->ExcelModel->getUserInfo('employee',$userName);
                if(empty($employeeInfo)){
                    $this->ExcelModel->insert('employee',$insertEmployeeData);
                }   
            }
        }
    }

     //for bank data uploading
    public function billPendDataUploadExcel(){
        $fileName=$_FILES['pendfile']['name'];
        $fileType=$_FILES['pendfile']['type'];
        $fileTempName=$_FILES['pendfile']['tmp_name'];

        // echo $fileName;exit;

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

            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
            $cnt=0;
            $empName="";
            for ($row = 2; $row <= $highestRow; ++$row) {
                //A row selected
                $cnt++;
                $billNo = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                if(trim(strlen($billNo))==0){
                    $billNo = "";
                }

                $pendingAmt = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                if(trim(strlen($pendingAmt))==0){
                    $pendingAmt = "";
                }

                $billInfo=$this->ExcelModel->getBillId('bills',$billNo);
                if(!empty($billInfo)){
                    $updateData=array(
                        'pendingAmt'=>trim($pendingAmt),
                    );

                    $billId=$billInfo[0]['id'];
                    
                    $this->ExcelModel->update('bills',$updateData,$billId);
                }   
            }
        }
    }
}