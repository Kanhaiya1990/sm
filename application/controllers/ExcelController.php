<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ExcelController extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('ExcelModel');
        $this->load->library('PHPExcel');
        $this->load->library('excel');
        $this->load->library('session');
        date_default_timezone_set('Asia/Kolkata');
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        $this->load->helper('form');

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

    public function getMultipleImport(){
        $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $total_count = count($_FILES['file']['name']);
        for ( $i=0 ; $i < $total_count ; $i++ ) {
            $data=[];
            $tmpFilePath = $_FILES['file']['tmp_name'][$i];
            if ($tmpFilePath != "") {
                $newFilePath = $_FILES['file']['name'][$i];
              
                $arr_file = explode('.', $newFilePath); //get file
                $extension = end($arr_file); //get file extension

                if ('csv' == $extension) {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                } else if ('xlsx'){
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                }

                $reader->setReadDataOnly(true);
                $objPHPExcel = $reader->load($tmpFilePath);//Get filename
                
                $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
                $highestRow = $worksheet->getHighestRow(); // e.g. 12
                $highestColumn = $worksheet->getHighestColumn(); // e.g M'

                $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
                $cnt=0;
                
                for ($row = 1; $row <= $highestRow; ++$row) {
                    $cnt++;
                    $date = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $perticular = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $voucher = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $data[] = array(
                          'perticular' => $perticular,
                          'voucher'    => $voucher
                    );
                }
            }
        }
    }

    public function index(){
        $data['company']=$this->ExcelModel->getAllCompanies('company');
        $this->load->view('ExcelInsertView',$data);
    }

     public function billsUpload()
    {
        $data['company']=$this->ExcelModel->getAllCompanies('company');
        $this->load->view('ExcelView1',$data);
    }
    
    public function billDetailUpload()
    {
        $data['company']=$this->ExcelModel->getAllCompanies('company');
        $this->load->view('ExcelView',$data);
    }

    public function outletSummery()
    {
        $data['company']=$this->ExcelModel->getAllCompanies('company');
        $this->load->view('ExcelView2',$data);
    }

    public function insertSalesman(){
        $this->load->library('PHPExcel');
        $this->load->library("excel");

        $fileName = $_FILES['file']['name'];
        $fileName = str_replace(' ', '_', $fileName);
        echo "File Name : ".$fileName."<br>"; 
        
        $config['upload_path'] = 'assets/uploads/';                             
        $config['file_name'] = $fileName;
        $config['allowed_types'] = 'xls|xlsx|csv';

        $this->load->library('upload');
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file'))
            $this->upload->display_errors();

        $media =  $fileName;
        $path = 'assets/uploads/'. $media;

        echo "Path : ".$path."<br>";
        echo "Exist : ".file_exists($path);
        
        try {
            $inputFileType = PHPExcel_IOFactory::identify($path);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($path);
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($path, PATHINFO_BASENAME) . '": ' . $e->getMessage());            
        }
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        for ($row = 11; $row <= $highestRow; $row++) {            
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

            $sName = $rowData[0][26];
            if(trim(strlen($sName))==0)
             $sName = "";

         $data1= array(                                                    
            "name" => $sName
        );
         $insert_query1 = $this->db->insert_string('employee', $data1);
         $insert_query1 = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query1);
         $this->db->query($insert_query1); 

         if($this->db->affected_rows()>0)
         {
            echo "success"; 
        }else 
        {
            echo "Fail";
        }
        delete_files($path); 
    }
}


public function routeRetailerImport(){
    $companyName=$this->input->post('company');

    $this->load->library('PHPExcel');
    $this->load->library("excel");

    $fileName = $_FILES['file']['name'];
    $fileName = str_replace(' ', '_', $fileName);

    $config['upload_path'] = 'assets/uploads/';                             
    $config['file_name'] = $fileName;
    $config['allowed_types'] = 'xls|xlsx|csv';

    $this->load->library('upload');
    $this->upload->initialize($config);

    if (!$this->upload->do_upload('file'))
        $this->upload->display_errors();

    $media =  $fileName;
    $path = 'assets/uploads/'. $media;

    try {
        $inputFileType = PHPExcel_IOFactory::identify($path);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($path);
    } catch (Exception $e) {
        die('Error loading file "' . pathinfo($path, PATHINFO_BASENAME) . '": ' . $e->getMessage());            
    }

    if($companyName == "Nestle"){

        $sheet = $objPHPExcel->getSheet(3);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        for ($row = 2; $row <= $highestRow; $row++) {            
            $rowData = $sheet->rangeToArray('B' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

            $rCode = trim($rowData[0][3]);
            if(trim(strlen($rCode))==0)
                 $rCode = "";
            $rName = trim($rowData[0][4]);
            if(trim(strlen($rName))==0)
                 $rName = "";

            $data['roDetails']=$this->ExcelModel->routeInfo('route',$rName,$rCode); 

            if(empty($data['roDetails'])){
                if(empty($data['roDetails'])){
                    $data = array(                                                     
                      "code" => $rCode,
                      "name" => $rName
                    );
                    $this->ExcelModel->insert('route',$data);
                } 
            }
                        
        }
        $reCode="";
        $count=0;
        for ($row = 2; $row <= $highestRow; $row++) {            
            $rowData = $sheet->rangeToArray('B' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

            $sCode = $rowData[0][1];
            if(trim(strlen($sCode))==0)
                $sCode = "";
            $sName = $rowData[0][2];
            if(trim(strlen($sName))==0)
                $sName = "";
           
            $rStatus = $rowData[0][5];
            if(trim(strlen($rStatus))==0){
                 $rStatus = "";
            }
            if($rStatus=="Active"){
                $rStatus = 1;
            }else{
                $rStatus = 0;
            }
            $rHirarchy = $rowData[0][7];
            if(trim(strlen($rHirarchy))==0)
               $rHirarchy = "";
            $reCode = $rowData[0][9];
            if(trim(strlen($reCode))==0)
                $reCode = "";
            $reName = $rowData[0][11];
            if(trim(strlen($reName))==0)
                $reName = "";
            $phone = $rowData[0][18];
            if(trim(strlen($phone))==0)
                $phone = "";
            $gstIn = $rowData[0][28];
            if(trim(strlen($gstIn))==0)
                $gstIn = "";
            $data['reData']=$this->ExcelModel->retailerInfo('retailer',$reName,$reCode);
            if(empty($data['reData'])){
                $data2= array(                                                     
                  "code" => $reCode,
                  "name" => $reName,
                  "hierarchy" => $rHirarchy,
                  "phone" => $phone,
                  "status" => $rStatus,
                  "gstIn" => $gstIn
                );
                $this->ExcelModel->insert('retailer',$data2);
                if($this->db->affected_rows() <=0){
                    echo "fail";
                    $count++;
                }
            }
        }

        //routes for bills
        for ($row = 2; $row <= $highestRow; $row++) {            
            $rowData = $sheet->rangeToArray('B' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

            $routeName1 = $rowData[0][4];
            if(trim(strlen($routeName1))==0)
                $routeName1 = "";

            $retailerCode1 = $rowData[0][9];
            if(trim(strlen($retailerCode1))==0)
                $retailerCode1 = "";

            $retailerName1 = $rowData[0][11];
            if(trim(strlen($retailerName1))==0)
                $retailerName1 = "";
           
            $routeBillInsert= array(                                                     
                "routeName" => $routeName1
            );

            $this->ExcelModel->updateBillRoute('bills',$routeBillInsert,$retailerName1,$retailerCode1);
        }


         delete_files($path); 
         // return redirect('DashbordController'); 
    }else if($companyName=="ITC"){

        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        for ($row = 4; $row <= $highestRow; $row++) {            
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

            $sCode = $rowData[0][1];
            if(trim(strlen($sCode))==0)
                $sCode = "";
            $sName = $rowData[0][2];
            if(trim(strlen($sName))==0)
                $sName = "";
            $rCode = $rowData[0][3];
            if(trim(strlen($rCode))==0)
                 $rCode = "";
            $rName = $rowData[0][4];
            if(trim(strlen($rName))==0)
                 $rName = "";
            $rStatus = $rowData[0][5];
            if(trim(strlen($rStatus))==0){
                 $rStatus = "";
            }
            if($rStatus=="Active"){
                $rStatus = 1;
            }else{
                $rStatus = 0;
            }
            $rHirarchy = $rowData[0][7];
            if(trim(strlen($rHirarchy))==0)
               $rHirarchy = "";
            $reCode = $rowData[0][9];
            if(trim(strlen($reCode))==0)
                $reCode = "";
            $reName = $rowData[0][11];
            if(trim(strlen($reName))==0)
                $reName = "";
            $phone = $rowData[0][18];
            if(trim(strlen($phone))==0)
                $phone = "";
            $gstIn = $rowData[0][28];
            if(trim(strlen($gstIn))==0)
                $gstIn = "";

            $data = array(                                                     
                "code" => $rCode,
                "name" => $rName
            );

             $insert_query = $this->db->insert_string('route', $data);
             $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
             $result = $this->db->query($insert_query); 
             if($result==1)
             {
                  $data1= array(                                                    
                    "code" => $sCode,
                    "name" => $sName
                  );
                //   $insert_query1 = $this->db->insert_string('employee', $data1);
                //   $insert_query1 = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query1);
                //   $result1 = $this->db->query($insert_query1); 
                //   if($result1==1)
                //   {
                      $data2= array(                                                     
                        "code" => $reCode,
                        "name" => $reName,
                        "hierarchy" => $rHirarchy,
                        "phone" => $phone,
                        "status" => $rStatus,
                        "gstIn" => $gstIn
                      );
                      $insert_query2 = $this->db->insert_string('retailer', $data2);
                      $insert_query2= str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query2);
                      $result2 = $this->db->query($insert_query2); 
                      if($result2==1)
                      {
                         echo "Success"."<br />";
                      }else{
                        echo "Fail";
                      }
                // }else{
                //     echo "Fail";
                // }       
            }else{
                echo "Fail";
            }                 
            delete_files($path); 
        }
    }
    redirect('DashbordController');
}

public function uploadFile($fileName) 
{
    $upload_path='assets/uploads/'; 
    $config = array(
    'upload_path' => $upload_path,
    'allowed_types' => "xls|xlsx|csv"
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

public function billsImport() //for bills data upload
{
    $company=$this->input->post('company');

    $this->load->library('PHPExcel');
    $this->load->library("excel");

    $fileName = $_FILES['file']['name'];
    $fileName = str_replace(' ', '_', $fileName);

    $config['upload_path'] = 'assets/uploads/';                             
    $config['file_name'] = $fileName;
    $config['allowed_types'] = 'xls|xlsx|csv';

    $this->load->library('upload');
    $this->upload->initialize($config);

    if (!$this->upload->do_upload('file'))
        $this->upload->display_errors();

    $media =  $fileName;
    $path = 'assets/uploads/'. $media;


    try {
        $inputFileType = PHPExcel_IOFactory::identify($path);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($path);
    } catch (Exception $e) {
        die('Error loading file "' . pathinfo($path, PATHINFO_BASENAME) . '": ' . $e->getMessage());
    }

        if($company=='Nestle' || $company=='HU'){
           $sheet = $objPHPExcel->getSheet(0);
           $highestRow = $sheet->getHighestRow();
           $highestColumn = $sheet->getHighestColumn();

           for ($row = 3; $row <= $highestRow; $row++) {
            $rowData = $sheet->rangeToArray('B' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
            $billCode = $rowData[0][0];
            if(trim(strlen($billCode))==0)
                $billCode = "";
            $billDate = $rowData[0][1];
            if(trim(strlen($billDate))==0)
             $billDate = "";            
           $deliveryStatus = $rowData[0][4];
           if(trim(strlen($deliveryStatus))==0)
               $deliveryStatus = "";
           $retailerName = $rowData[0][11];
           if(trim(strlen($retailerName))==0)
               $retailerName = "";
           $retailerHeirarchy = $rowData[0][8];
           if(trim(strlen($retailerHeirarchy))==0)
               $retailerHeirarchy = "";
           $retailerCode = $rowData[0][10];
           if(trim(strlen($retailerCode))==0)
               $retailerCode = "";
           $distributorDiscount = $rowData[0][16];
           if(trim(strlen($distributorDiscount))==0)
               $distributorDiscount = "";
           $cashDiscount = $rowData[0][17];
           if(trim(strlen($cashDiscount))==0)
               $cashDiscount = "";
           $creditAdjustment = $rowData[0][21];
           if(trim(strlen($creditAdjustment))==0)
               $creditAdjustment = "";  
           $netAmount = $rowData[0][22];
           if(trim(strlen($netAmount))==0)
               $netAmount = "";           
           $salesman=$rowData[0][6];
           if(trim(strlen($salesman))==0)
               $salesman = "";
             $checkBillNo=array();

            if(!empty($billCode)){
              $checkBillNo=$this->ExcelModel->getBillId('bills',$billCode);
            }
             
             if(empty($checkBillNo)){
                  $data = array(                                                     
                    "billNo" => $billCode,
                    "date" => $billDate,
                    "deliveryStatus" => $deliveryStatus,
                    "retailerName" => $retailerName,
                    "salesman" => $salesman,
                    "retailerHeirarchy" => $retailerHeirarchy,
                    "retailerCode" => $retailerCode,
                    "distributorDiscount" => $distributorDiscount,
                    "cashDiscount" => $cashDiscount,
                    "creditAdjustment" => $creditAdjustment,
                    "netAmount" => $netAmount,
                    "SRAmt" => 0,
                    "receivedAmt" => 0,
                    "pendingAmt" => $netAmount,
                    "billType" => '',
                    "compName" => $company
                );
                 
                if($deliveryStatus === "Cancelled" || $deliveryStatus === "Saved"){

                }else{
                    $this->ExcelModel->insert('bills',$data); 
                }
             }else{
              $billId=$checkBillNo[0]['id'];
                $data = array(                                                     
                    "billNo" => $billCode,
                    "date" => $billDate,
                    "deliveryStatus" => $deliveryStatus,
                    "retailerName" => $retailerName,
                    "salesman" => $salesman,
                    "retailerHeirarchy" => $retailerHeirarchy,
                    "retailerCode" => $retailerCode,
                    "distributorDiscount" => $distributorDiscount,
                    "cashDiscount" => $cashDiscount,
                    "creditAdjustment" => $creditAdjustment,
                    "netAmount" => $netAmount,
                    // "SRAmt" => 0,
                    // "receivedAmt" => 0,
                    // "pendingAmt" => $netAmount,
                    // "billType" => '',
                    "compName" => $company

                );
                 
                if($deliveryStatus === "Cancelled" || $deliveryStatus === "Saved"){

                }else{
                    $this->ExcelModel->update('bills',$data,$billId); 
                }
             }
           
          delete_files($path);
        }
    }else if($company=='ITC'){
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        for ($row = 11; $row <= $highestRow; $row++) {
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
            $billCode = $rowData[0][0];
            if(trim(strlen($billCode))==0)
                $billCode = "";
            $timestamp = strtotime($rowData[0][2]);
            $billDate = date("d-m-Y", $timestamp);

            if(trim(strlen($billDate))==0)
               $billDate = "";            
                    
                 $deliveryStatus = "";
                 $retailerName = $rowData[0][7];
                 if(trim(strlen($retailerName))==0)
                     $retailerName = "";
                          
                 $retailerHeirarchy = "";
                 $retailerCode = $rowData[0][6];
                 if(trim(strlen($retailerCode))==0)
                     $retailerCode = "";
                 $distributorDiscount = $rowData[0][16];
                 if(trim(strlen($distributorDiscount))==0)
                     $distributorDiscount = "";
                 $cashDiscount = $rowData[0][14];
                 if(trim(strlen($cashDiscount))==0)
                     $cashDiscount = "";
                 $creditAdjustment = $rowData[0][21];
                 if(trim(strlen($creditAdjustment))==0)
                     $creditAdjustment = "";  
                 $netAmount = $rowData[0][18];
                 if(trim(strlen($netAmount))==0)
                     $netAmount = "";           
                 $salesman=$rowData[0][26];
                 if(trim(strlen($salesman))==0)
                    $salesman="";

                 $data = array(                                                     
                    "billNo" => $billCode,
                    "date" => $billDate,
                    "deliveryStatus" => $deliveryStatus,
                    "retailerName" => $retailerName,
                    "salesman" => $salesman,
                    "retailerHeirarchy" => $retailerHeirarchy,
                    "retailerCode" => $retailerCode,
                    "distributorDiscount" => $distributorDiscount,
                    "cashDiscount" => $cashDiscount,
                    "creditAdjustment" => $creditAdjustment,
                    "netAmount" => $netAmount,
                    "SRAmt" => 0,
                    "receivedAmt" => 0,
                    "pendingAmt" => $netAmount,
                    "billType" => '',
                    "compName" => $company
                );

                $this->ExcelModel->insert('bills',$data); 
                delete_files($path);
            }
        }else if($company=='Parle'){
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            for ($row = 9; $row <= $highestRow; $row++) {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                $billCode = $rowData[0][2];

                if(trim(strlen($billCode))==0)
                    $billCode = "";
                $timestamp = strtotime($rowData[0][0]);
                $billDate = date("Y-m-d", $timestamp);

                if(trim(strlen($billDate))==0)
                 $billDate = "";            
                        
               $deliveryStatus = "";
               $retailerName = $rowData[0][1];
               if(trim(strlen($retailerName))==0)
                   $retailerName = "";
                          
               $retailerHeirarchy = "";
                         
               $retailerCode = "";
                        
               $distributorDiscount = "";
                         
               $cashDiscount = "";
                         
               $creditAdjustment = "";  
               $netAmount = $rowData[0][11];
               if(trim(strlen($netAmount))==0)
                   $netAmount = "";           

               $data = array(                                                     
                  "billNo" => $billCode,
                  "date" => $billDate,
                  "deliveryStatus" => $deliveryStatus,
                  "retailerName" => $retailerName,
                  "retailerHeirarchy" => $retailerHeirarchy,
                  "retailerCode" => $retailerCode,
                  "distributorDiscount" => $distributorDiscount,
                  "cashDiscount" => $cashDiscount,
                  "creditAdjustment" => $creditAdjustment,
                  "netAmount" => $netAmount,
                  "SRAmt" => 0,
                  "receivedAmt" => 0,
                  "pendingAmt" => $netAmount,
                  "billType" => '',
                  "compName" => $company
              );


              $this->ExcelModel->insert('bills',$data); 
              delete_files($path);
        }
    }
    redirect('DashbordController');
}


public function billDetailsImport() //for billdetails
{
    $company=$this->input->post('company');

    $this->load->library('PHPExcel');
    $this->load->library("excel");

    $fileName = $_FILES['file']['name'];
    echo "File Name : ".$fileName."<br>"; 

    $fileName = str_replace(' ', '_', $fileName);
    echo "File Name : ".$fileName."<br>"; 

    $config['upload_path'] = 'assets/uploads/';                             
    $config['file_name'] = $fileName;
    $config['allowed_types'] = 'xls|xlsx|csv';

    $this->load->library('upload');
    $this->upload->initialize($config);

    if (!$this->upload->do_upload('file'))
        $this->upload->display_errors();

    $media =  $fileName;
    $path = 'assets/uploads/'. $media;

    try {
        $inputFileType = PHPExcel_IOFactory::identify($path);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($path);
    } catch (Exception $e) {
        die('Error loading file "' . pathinfo($path, PATHINFO_BASENAME) . '": ' . $e->getMessage());            
    }

    if($company=='ITC'){
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        for ($row = 11; $row <= $highestRow; $row++) {
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
            $billNo = $rowData[0][0];
            if(trim(strlen($billNo))==0)
                $billNo = "";
            $billId=$this->ExcelModel->getBillId('bills',$billNo);
            $prodName = $rowData[0][35];
            if(trim(strlen($prodName))==0)
                $prodName = "";

            $salesman=$rowData[0][26];
            if(trim(strlen($salesman))==0)
                $salesman="";
            
            $mrp = $rowData[0][39];
            if(trim(strlen($mrp))==0)
                $mrp = "";
            $sellingRate = $rowData[0][39];
            if(trim(strlen($sellingRate))==0)
             $sellingRate = "";            
             $qty = $rowData[0][41];
             if(trim(strlen($qty))==0)
                 $qty = "";
             $netAmount = $rowData[0][46];
             if(trim(strlen($netAmount))==0)
                $netAmount="";

            $data = array(                                                     
                "billId" => $billId,
                "productCode" => "",
                "brandCaption" => "",
                "motherPackName" => "",
                "productName" => $prodName,
                "mrp" => $mrp,
                "sellingRate" => $sellingRate,
                "qty" => $qty,
                "netAmount" => $netAmount,
                "returnedQty" => 0,
                "status" => 0,
                "returnAmt" => 0
            );

          $this->ExcelModel->insert('billsdetails',$data); 
          delete_files($path);

    }
    }else if($company=="Parle"){
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $billId=0;
        for ($row = 9; $row <= $highestRow; $row++) {
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
            $billNo = $rowData[0][2];
            if(trim(strlen($billNo))==0)
                $billNo = "";
            if(!empty($billNo)){
                $billId=$this->ExcelModel->getBillId('bills',$billNo);
            }

            $prodName = $rowData[0][1];
            if(trim(strlen($prodName))==0)
                $prodName = "";
            $mrp = $rowData[0][9];
            if(trim(strlen($mrp))==0)
                $mrp = "";
            $sellingRate = $rowData[0][9];
            if(trim(strlen($sellingRate))==0)
                 $sellingRate = "";            
                 $qty = $rowData[0][8];
                 if(trim(strlen($qty))==0)
                     $qty = "";
                 $netAmount = $rowData[0][10];
                 if(trim(strlen($netAmount))==0)
                    $netAmount="";

                $data = array(                                                     
                    "billId" => $billId,
                    "productCode" => "",
                    "brandCaption" => "",
                    "motherPackName" => "",
                    "productName" => $prodName,
                    "mrp" => $mrp,
                    "sellingRate" => $sellingRate,
                    "qty" => $qty,
                    "netAmount" => $netAmount,
                    "returnedQty" => 0,
                    "status" => 0,
                    "returnAmt" => 0
                );
                $this->ExcelModel->insert('billsdetails',$data); 
                delete_files($path);
                        
            }
    }else if($company=="Nestle"){
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        for ($row = 3; $row <= $highestRow; $row++) {
            $rowData = $sheet->rangeToArray('B' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
            $billNo = $rowData[0][8];
            
            if(trim(strlen($billNo))==0)
                $billNo = "";
            $billDetails=$this->ExcelModel->getBillId('bills',$billNo);

            if(!empty($billDetails)){

              $billId=$billDetails[0]['id'];
                $prodName = $rowData[0][16];
                if(trim(strlen($prodName))==0)
                    $prodName = "";
                $mrp = $rowData[0][18];
                if(trim(strlen($mrp))==0)
                    $mrp = "";
                $sellingRate = $rowData[0][19];
                if(trim(strlen($sellingRate))==0)
                    $sellingRate = "";            
                $qty = $rowData[0][20];
                if(trim(strlen($qty))==0)
                    $qty = "";
                $netAmount = $rowData[0][29];
                if(trim(strlen($netAmount))==0)
                    $netAmount="";

                $data = array(                                                     
                    "billId" => $billId,
                    "productCode" => "",
                    "brandCaption" => "",
                    "motherPackName" => "",
                    "productName" => $prodName,
                    "mrp" => $mrp,
                    "sellingRate" => $sellingRate,
                    "qty" => $qty,
                    "netAmount" => $netAmount,
                    "returnedQty" => 0,
                    "status" => 0,
                    "returnAmt" => 0
                );

                $this->ExcelModel->insert('billsdetails',$data); 
            }
            
            delete_files($path);
        }
    }
    redirect('DashbordController');
    }
}
