<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
class CompanyDataUploadingController extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('ExcelModel');
        $this->load->library('session');
        date_default_timezone_set('Asia/Kolkata');
        ini_set('memory_limit', '-1');

        if(isset($this->session->userdata['codeKeyData'])) {
			$this->projectSessionName= $this->session->userdata['codeKeyData']['codeKeyValue'];
		}else{
			$this->load->view('LoginView');
		}
    }

    //check dates for uploading data
    public function checkDatesForCompany(){
        $company=$this->input->post('company');
        if($company==="Nestle"){
            $data=$this->ExcelModel->getPendingDatesFromBills('bills',$company);
            // print_r($data);
            if(!empty($data)){
                // $excelDate=$data[0]['date'];
                $next_date= date('Y-m-d', strtotime($excelDate. ' -15 days'));
                $next_date= date('Y-m-d', strtotime($excelDate));
                $date= date('d M, Y', strtotime($next_date));
                $message ="Please upload data from date: ".$date;
                $dataRes= ['date'=>$next_date,'message'=>$message];
                echo json_encode($dataRes);
            }else{
                $data=$this->ExcelModel->getDeliveredDatesFromBills('bills',$company); 
                if(!empty($data)){
                    // $excelDate=$data[0]['date'];
                    $next_date= date('Y-m-d', strtotime($excelDate. ' -15 days'));
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

    //loading data
    public function index(){
        $data['company']=$this->ExcelModel->getAllCompanies('company');
        $this->load->view('dataUploadingView',$data);
    }

    public function uploadFilesForImport(){
        $userId = $this->session->userdata[$this->projectSessionName]['id'];
        $compName=trim($this->input->post('company'));
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

        $cronTab=$this->ExcelModel->getCronDetails('cron_settings',$compName);
        
        if(!empty($cronTab)){
            if($cronTab[0]['status']==1){
                echo "Cron Job running already...Please wait.";
                exit;
            }else{
                $currentDate=date('Y-m-d H:i:sa');
                $cronStatus=array('status'=>0,'createdAt'=>$currentDate);
                $this->ExcelModel->updateByCompany('cron_settings',$cronStatus,$compName);
            }
        }
        
        if($compName == "Nestle"){
            $nestleErr= $this->nestleExcelUploading($bill,$billType,$billTempName,$dateForUploadBills);
            if(trim($nestleErr) !==""){
                echo $nestleErr;
            }else{
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

                    if($billFileName=="" || $billDetailFileName==""){
                        echo "Files are not uploaded properly...Please upload files again...!";
                    }else{
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
                            echo "Files uploaded successfully";
                        }
                    }
                }else{
                    echo "Please select file";
                }
            } 
        }else if($compName == "ITC"){
            $itcErr= $this->checkItcExcelUploadingWithSifi($bill,$billType,$billTempName,$dateForUploadBills);
            //for tally : kia sales
            // $itcErr= $this->checkItcExcelUploading($bill,$billType,$billTempName,$dateForUploadBills);
            if(trim($itcErr) !==""){
                echo $itcErr;
            }else{
                if($bill !==""){
                    $billFileName="";
                    $billDetailFileName="";
                    $retailerFileName="";
                    if($bill !==""){
                        $billFileName = trim($this->uploadFile('billFile'));
                    }

                    if($billFileName==""){
                        echo "Files are not uploaded properly...Please upload files again...!";
                    }else{
                        $insertData=array(
                            'billFile'=>$billFileName,
                            'company'=>$compName,
                            'uploadedDate'=>$dateForUploadBills,
                            'uploadedBy'=>$userId,
                            'uploadedAt'=>date('Y-m-d H:i:sa')
                        );
                        $this->ExcelModel->insert('uploaded_files_details',$insertData);
                        if($this->db->affected_rows() > 0){
                            echo "Files uploaded successfully";
                        }
                    }
                }else{
                    echo "Please select file";
                }
            }
        }else if($compName == "Parle"){
            $parleErr= $this->checkParleExcelUploading($bill,$billType,$billTempName,$dateForUploadBills);
            if(trim($parleErr) !==""){
                echo $parleErr;
            }else{
                if($bill !==""){
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

                    if($billFileName=="" || $billDetailFileName==""){
                        echo "Files are not uploaded properly...Please upload files again...!";
                    }else{
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
                            echo "Files uploaded successfully";
                        }
                    }
                }else{
                    echo "Please select file";
                }
            }
        }else if($compName == "Jockey"){
            $parleErr= $this->checkJockeyExcelUploading($bill,$billType,$billTempName,$dateForUploadBills);
            if(trim($parleErr) !==""){
                // echo $parleErr;
            }else{
                if($bill !==""){
                    $billFileName="";
                    $retailerFileName="";
                    if($bill !==""){
                        $billFileName = trim($this->uploadFile('billFile'));
                    }
                    if($retailerDetail !==""){
                        $retailerFileName = trim($this->uploadFile('retailerDetailFile'));
                    }

                    if($billFileName==""){
                        echo "Files are not uploaded properly...Please upload files again...!";
                    }else{
                        $insertData=array(
                            'billFile'=>$billFileName,
                            'retailerFile'=>$retailerFileName,
                            'company'=>$compName,
                            'uploadedDate'=>$dateForUploadBills,
                            'uploadedBy'=>$userId,
                            'uploadedAt'=>date('Y-m-d H:i:sa')
                        );
                        $this->ExcelModel->insert('uploaded_files_details',$insertData);
                        if($this->db->affected_rows() > 0){
                            echo "Files uploaded successfully";
                        }
                    }
                }else{
                    echo "Please select file";
                }
            }
        }else if($compName == "Havells"){
            // $parleErr= $this->checkHavellsExcelUploading($bill,$billType,$billTempName,$dateForUploadBills);
            $havellsErr= $this->checkHavellsElectricsBillsExcelUploading($bill,$billType,$billTempName,$dateForUploadBills);
            if(trim($havellsErr) !==""){
                // echo $parleErr;
            }else{
                if($bill !==""){
                    $billFileName="";
                    $retailerFileName="";
                    if($bill !==""){
                        $billFileName = trim($this->uploadFile('billFile'));
                    }
                    if($retailerDetail !==""){
                        $retailerFileName = trim($this->uploadFile('retailerDetailFile'));
                    }

                    if($billFileName==""){
                        echo "Files are not uploaded properly...Please upload files again...!";
                    }else{
                        $insertData=array(
                            'billFile'=>$billFileName,
                            'retailerFile'=>$retailerFileName,
                            'company'=>$compName,
                            'uploadedDate'=>$dateForUploadBills,
                            'uploadedBy'=>$userId,
                            'uploadedAt'=>date('Y-m-d H:i:sa')
                        );
                        $this->ExcelModel->insert('uploaded_files_details',$insertData);
                        if($this->db->affected_rows() > 0){
                            echo "Files uploaded successfully";
                        }
                    }
                }else{
                    echo "Please select file";
                }
            }
        }else if($compName == "McCain"){
            $mcCainErr= $this->checkMcCainExcelUploadingWithSifi($bill,$billType,$billTempName,$dateForUploadBills);
            if(trim($mcCainErr) !==""){
                // echo $parleErr;
            }else{
                if($bill !==""){
                    $billFileName="";
                    $retailerFileName="";
                    if($bill !==""){
                        $billFileName = trim($this->uploadFile('billFile'));
                    }
                    if($retailerDetail !==""){
                        $retailerFileName = trim($this->uploadFile('retailerDetailFile'));
                    }

                    if($billFileName==""){
                        echo "Files are not uploaded properly...Please upload files again...!";
                    }else{
                        $insertData=array(
                            'billFile'=>$billFileName,
                            'retailerFile'=>$retailerFileName,
                            'company'=>$compName,
                            'uploadedDate'=>$dateForUploadBills,
                            'uploadedBy'=>$userId,
                            'uploadedAt'=>date('Y-m-d H:i:sa')
                        );
                        $this->ExcelModel->insert('uploaded_files_details',$insertData);
                        if($this->db->affected_rows() > 0){
                            echo "Files uploaded successfully";
                        }
                    }
                }else{
                    echo "Please select file";
                }
            }
            
        }else if($compName == "Amul"){
            $amulErr= $this->checkAmulExcelUploadingWithSifi($bill,$billType,$billTempName,$dateForUploadBills);
            
            if(trim($amulErr) !==""){
                // echo $parleErr;
            }else{
                if($bill !==""){
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

                    if($billFileName=="" || $billDetailFileName==""){
                        echo "Files are not uploaded properly...Please upload files again...!";
                    }else{
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
                            echo "Files uploaded successfully";
                        }
                    }
                }else{
                    echo "Please select file";
                }
            }
        }else if($compName == "Marico"){
            $maricoErr= $this->checkMaricoBillsExcelUploading($bill,$billType,$billTempName,$dateForUploadBills);
            
            if(trim($maricoErr) !==""){
                // echo $parleErr;
            }else{
                if($bill !==""){
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

                    if($billFileName=="" || $billDetailFileName==""){
                        echo "Files are not uploaded properly...Please upload files again...!";
                    }else{
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
                            echo "Files uploaded successfully";
                        }
                    }
                }else{
                    echo "Please select file";
                }
            }
        }
    }

    //data uploading
    public function uploadFile($fileName) 
    {
        $upload_path='./assets/uploads/excels'; 
        $config = array(
            'upload_path' => $upload_path,
            'overwrite' => false,
            'allowed_types' => '*',
            // 'allowed_types' => 'xlsx|xls|csv',
            'max_size' => 51200,
            'file_ext_tolower' => true,
            'remove_spaces' => true
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
    
    public function importUploadedFiles(){
        $company=$this->ExcelModel->getdata('company');
        if(!empty($company)){
            foreach($company as $comp){
                $uploadedData=$this->ExcelModel->getLatesttUploadedFileData('uploaded_files_details',$comp['name']);
                $compName="";
                
                if(!empty($uploadedData)){
                    $compName=$uploadedData[0]['company'];
                    $cronTab=$this->ExcelModel->getCronDetails('cron_settings',$compName);
                    if(!empty($cronTab)){
                        if($cronTab[0]['status'] !=0){
                            // exit;
                        }else{
                            $this->startUploading($comp['name']);
                        }
                    }
                }
            }
        }
    }

    //starting uploading data for company if cron status is 0
    public function startUploading($comp){
        $uploadedData=$this->ExcelModel->getLatesttUploadedFileData('uploaded_files_details',$comp);
        $compName="";
       
        if(!empty($uploadedData)){
            $compName=$uploadedData[0]['company'];
                
            $updateStatus=array('status'=>1);
            $this->ExcelModel->updateByCompany('cron_settings',$updateStatus,$compName);

            if($compName=="Nestle"){
                $billFile=trim($uploadedData[0]['billFile']);
                $billDetailFile=trim($uploadedData[0]['billDetailFile']);
                $retailerFile=trim($uploadedData[0]['retailerFile']);
                $company=trim($uploadedData[0]['company']);
                $uploadedDate=trim($uploadedData[0]['uploadedDate']);
    
                if(($billFile !=="") && ($billDetailFile!=="")){
                    $billFilePath='./assets/uploads/excels/'.$billFile;
                    $billDetailFilePath='./assets/uploads/excels/'.$billDetailFile;
                    $retailerBillDetailFilePath="";
                    if($retailerFile !=""){
                        $retailerBillDetailFilePath='./assets/uploads/excels/'.$retailerFile;
                    }
                    
                    if($company==="Nestle"){
                        $this->uploadNestleBillData($billFilePath);
                        $this->uploadBillDetailsData($billDetailFilePath);
                        if($retailerBillDetailFilePath !==""){
                            $this->uploadNestleRetailerBillData($retailerBillDetailFilePath);
                        }
                    }
                }
            }
    
            //import Parle Data
            if($compName=="Parle"){
                $billFile=trim($uploadedData[0]['billFile']);
                $billDetailFile=trim($uploadedData[0]['billDetailFile']);
                $retailerFile=trim($uploadedData[0]['retailerFile']);
                $company=trim($uploadedData[0]['company']);
                $uploadedDate=trim($uploadedData[0]['uploadedDate']);
    
                if(($billFile !=="") && ($billDetailFile !=="")){
                    $billFilePath='./assets/uploads/excels/'.$billFile;
                    $billDetailFilePath='./assets/uploads/excels/'.$billDetailFile;

                    $retailerBillDetailFilePath="";
                    if($retailerFile !=""){
                        $retailerBillDetailFilePath='./assets/uploads/excels/'.$retailerFile;
                    }

                    if($company==="Parle"){
                        $this->uploadParleExcelUploading($billFilePath);
                        $this->uploadParleBillDetailsExcelUploading($billDetailFilePath);
                        if($retailerBillDetailFilePath !==""){
                            $this->uploadParleRetailerExcelUploading($retailerBillDetailFilePath);
                        }
                    }
                }
            }
    
            //import ITC Data
            if($compName=="ITC"){
                $billFile=trim($uploadedData[0]['billFile']);
                $billDetailFile=trim($uploadedData[0]['billDetailFile']);
                $company=trim($uploadedData[0]['company']);
                $uploadedDate=trim($uploadedData[0]['uploadedDate']); 

                if(($billFile !=="")){
                    $billFilePath='./assets/uploads/excels/'.$billFile;
                    // $billDetailFilePath='./assets/uploads/excels/'.$billDetailFile;
                    if($company==="ITC"){
                        //for tally : kiasales
                        // $this->itcExcelUploading($billFilePath);

                        $this->itcExcelUploadingWithSifi($billFilePath);
                    }
                }
            }

            //import Jockey Data
            if($compName=="Jockey"){
                $billFile=trim($uploadedData[0]['billFile']);
                $retailerFile=trim($uploadedData[0]['retailerFile']);
                $company=trim($uploadedData[0]['company']);
                $uploadedDate=trim($uploadedData[0]['uploadedDate']);

                if(($billFile !=="")){
                    $billFilePath='./assets/uploads/excels/'.$billFile;
                    $retailerBillDetailFilePath="";
                    if($retailerFile !=""){
                        $retailerBillDetailFilePath='./assets/uploads/excels/'.$retailerFile;
                    }
                    if($company==="Jockey"){
                        $this->uploadJockeyExcelUploading($billFilePath);
                        if($retailerBillDetailFilePath !==""){
                            $this->uploadJockeyRetailerExcelUploading($retailerBillDetailFilePath);
                        }
                    }
                }
            }

            //import Havells Data
            if($compName=="Havells"){
                $billFile=trim($uploadedData[0]['billFile']);
                $retailerFile=trim($uploadedData[0]['retailerFile']);
                $company=trim($uploadedData[0]['company']);
                $uploadedDate=trim($uploadedData[0]['uploadedDate']);
                // echo "hey";exit;
                if(($billFile !=="")){
                    $billFilePath='./assets/uploads/excels/'.$billFile;
                    $retailerBillDetailFilePath="";
                    if($retailerFile !=""){
                        $retailerBillDetailFilePath='./assets/uploads/excels/'.$retailerFile;
                    }
                    if($company==="Havells"){
                        
                        $this->havellsElectricsExcelUploading($billFilePath);
                        // $this->uploadHavellsExcelUploading($billFilePath);
                        
                        // if($retailerBillDetailFilePath !==""){
                        //     $this->uploadHavellsRetailersExcelUploading($retailerBillDetailFilePath);
                        // }
                    }
                }
            }

            //import McCain Data
            if($compName=="McCain"){
                $billFile=trim($uploadedData[0]['billFile']);
                $retailerFile=trim($uploadedData[0]['retailerFile']);
                $company=trim($uploadedData[0]['company']);
                $uploadedDate=trim($uploadedData[0]['uploadedDate']); 

                if(($billFile !=="")){
                    $billFilePath='./assets/uploads/excels/'.$billFile;
                    $retailerBillDetailFilePath="";
                    if($retailerFile !=""){
                        $retailerBillDetailFilePath='./assets/uploads/excels/'.$retailerFile;
                    }
                    if($company==="McCain"){
                        $this->mcCainExcelUploading($billFilePath);
                        
                        if($retailerBillDetailFilePath !==""){
                            // $this->uploadHavellsRetailersExcelUploading($retailerBillDetailFilePath);
                        }
                    }
                }
            }

            //import Amul Data
            if($compName=="Amul"){
                $billFile=trim($uploadedData[0]['billFile']);
                $billDetailFile=trim($uploadedData[0]['billDetailFile']);
                $retailerFile=trim($uploadedData[0]['retailerFile']);
                $company=trim($uploadedData[0]['company']);
                $uploadedDate=trim($uploadedData[0]['uploadedDate']); 

                if(($billFile !=="")){
                    $billFilePath='./assets/uploads/excels/'.$billFile;
                    $billDetailsFilePath='./assets/uploads/excels/'.$billDetailFile;
                    $retailerBillDetailFilePath="";
                    if($retailerFile !=""){
                        $retailerBillDetailFilePath='./assets/uploads/excels/'.$retailerFile;
                    }
                    if($company==="Amul"){
                        $this->amulExcelUploading($billFilePath);
                        $this->amulBillDetailsExcelUploading($billDetailsFilePath);
                        if($retailerBillDetailFilePath !==""){
                            $this->amulRetailerDetailsExcelUploading($retailerBillDetailFilePath);
                        }
                    }
                }
            }

            //import Amul Data
            if($compName=="Marico"){
                $billFile=trim($uploadedData[0]['billFile']);
                $billDetailFile=trim($uploadedData[0]['billDetailFile']);
                $retailerFile=trim($uploadedData[0]['retailerFile']);
                $company=trim($uploadedData[0]['company']);
                $uploadedDate=trim($uploadedData[0]['uploadedDate']); 

                if(($billFile !=="")){
                    $billFilePath='./assets/uploads/excels/'.$billFile;
                    $billDetailsFilePath='./assets/uploads/excels/'.$billDetailFile;
                    $retailerBillDetailFilePath="";
                    if($retailerFile !=""){
                        $retailerBillDetailFilePath='./assets/uploads/excels/'.$retailerFile;
                    }
                    if($company==="Marico"){
                        $this->maricoBillDetailsExcelUploading($billFilePath);
                        $this->maricoBillItemDetailsExcelUploading($billDetailsFilePath);
                        if($retailerBillDetailFilePath !==""){
                            $this->maricoRetailerDetailsExcelUploading($retailerBillDetailFilePath);
                        }
                    }
                }
            }
            
        }
            
        // $uploadedNestleData=$this->ExcelModel->getUploadedFileData('uploaded_files_details','Nestle');
    }

     //import function for data uploading using CronJob 
    public function importUploadedBillsFiles(){
        $uploadedNestleData=$this->ExcelModel->getUploadedFileData('uploaded_files_details','Nestle');
        $uploadedParleData=$this->ExcelModel->getUploadedFileData('uploaded_files_details','Parle');
        $uploadedItcData=$this->ExcelModel->getUploadedFileData('uploaded_files_details','ITC');

        //import Nestle Data
        if(!empty($uploadedNestleData)){
            $billFile=trim($uploadedNestleData[0]['billFile']);
            $company=trim($uploadedNestleData[0]['company']);
            $uploadedDate=trim($uploadedNestleData[0]['uploadedDate']);

            if(($billFile !=="")){
                $billFilePath='./assets/uploads/excels/'.$billFile;
                
                if($company==="Nestle"){
                    $this->uploadNestleBillData($billFilePath);
                }
            }
        }

        //import Parle Data
        if(!empty($uploadedParleData)){
            $billFile=trim($uploadedParleData[0]['billFile']);
            $company=trim($uploadedParleData[0]['company']);
            $uploadedDate=trim($uploadedParleData[0]['uploadedDate']);

            if(($billFile !=="")){
                $billFilePath='./assets/uploads/excels/'.$billFile;
                if($company==="Parle"){
                    $this->uploadParleBillData();
                }
            }
        }

        //import ITC Data
        if(!empty($uploadedItcData)){
            $billFile=trim($uploadedItcData[0]['billFile']);
            $company=trim($uploadedItcData[0]['company']);
            $uploadedDate=trim($uploadedItcData[0]['uploadedDate']);

            if(($billFile !=="")){
                $billFilePath='./assets/uploads/excels/'.$billFile;
                if($company==="ITC"){
                    $this->uploadItcBillData();
                }
            }
        }
    }

    //Import Nestle Bills
    public function uploadNestleBillData($billFilePath){
        //for get last 5 days records 
        // $dateForUploading = date('Y-m-d',strtotime('-5 day'));
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($billFilePath);
        $reader->setReadDataOnly(TRUE);
        $objPHPExcel = $reader->load($billFilePath);

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
        $grossAmountHeader="";
        $taxAmountHeader="";
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

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Gross Amount"){
                        $grossAmountHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Tax Amount"){
                        $taxAmountHeader= $i;
                    }
                }
            }

            if(($row==1) && (empty($billNumberHeader) || empty($taxAmountHeader) || empty($grossAmountHeader) || empty($billDateHeader) || empty($retailerCodeHeader) || empty($retailerNameHeader) || empty($billNetAmountHeader) || empty($netAmountHeader) || empty($creditAdjustmentHeader))){
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

            $grossAmount=$worksheet->getCellByColumnAndRow($grossAmountHeader, $row)->getValue();
            $taxAmount=$worksheet->getCellByColumnAndRow($taxAmountHeader, $row)->getValue();


            $excelDate="";
            // echo $billDate;
            $extension="";
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
            $billExist=$this->ExcelModel->getBillByLastRecords('bills',$billNumber);
            if(empty($billExist)){
                if((!empty($excelDate)) && ($excelDate != "1970-01-01") && ($billDate !=='Bill Date')){
                    $data = array(
                      'date'=>$excelDate,
                      'billNo'=>$billNumber,
                      'retailerName'=>$retailerName,
                      'retailerCode'=>$retailerCode,
                      'grossAmount'=>$grossAmount,
                      'taxAmount'=>$taxAmount,
                      'creditAdjustment'=>$creditAdjustment,
                      'billNetAmount'=>round($amount),
                      'netAmount'=>round($netAmount),
                      'pendingAmt'=>round($netAmount),
                      'compName'=>'Nestle',
                      'insertedAt'=>date('Y-m-d H:i:sa')
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
                  
                if(($billDeliveryStatus==="pending" || $billDeliveryStatus==="" || $billDeliveryStatus==="Vehicle Allocated")){
                    $fsDataAmount=$billExist[0]['fsCashAmt']+$billExist[0]['fsSrAmt']+$billExist[0]['fsNeftAmt']+$billExist[0]['fsChequeAmt']+$billExist[0]['fsOtherAdjAmt'];
                    $totalRecAmt=($fsDataAmount)+$billSrAmt+$billReceivedAmt+$billCd+$billDebit+$billOfficeAdjustment+$billOtherAdjustment+$billExist[0]['creditNoteJournalAmt']-($billExist[0]['debitNoteAmount']+$billExist[0]['debitNoteJournalAmt']);
                    $newPendingAmt=$netAmount-$totalRecAmt;
                    
                    if((!empty($excelDate)) && ($excelDate != "1970-01-01")){
                        $data = array(
                          'date'=>$excelDate,
                          'billNo'=>$billNumber,
                          'retailerName'=>$retailerName,
                          'retailerCode'=>$retailerCode,
                          'grossAmount'=>$grossAmount,
                          'taxAmount'=>$taxAmount,
                          'creditAdjustment'=>$creditAdjustment,
                          'billNetAmount'=>round($amount),
                          'netAmount'=>round($netAmount),
                          'pendingAmt'=>round($newPendingAmt),
                          'compName'=>'Nestle',
                          'insertedAt'=>date('Y-m-d H:i:sa')
                        );
                        $this->ExcelModel->update('bills',$data,$billId);
                    }
                }
            }
        }

        $lastBill=$this->ExcelModel->getlastBills('bills','Nestle');
        if(!empty($lastBill)){
            echo " \n\nFound ".$cnt." records. Total records uploaded : ".$cnt;
            echo " \n\nLast bill No : ".$lastBill[0]['billNo'];
        }else{
            echo " \n\nFound ".$cnt." records. Total records uploaded : ".$cnt;
        }
    }



    //Import Nestle Bill Details
    public function uploadBillDetailsData($billFilePath){
        $tempBillNumber="";
        $tempRetailerName="";
        $tempRouteName="";
        $billNumber="";
        $tempBillId="";

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($billFilePath);
        $reader->setReadDataOnly(TRUE);
        $objPHPExcel = $reader->load($billFilePath);

        $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
        $highestRow = $worksheet->getHighestRow(); // e.g. 12
        $highestColumn = $worksheet->getHighestColumn(); // e.g M'

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
            $freeQuantity=$worksheet->getCellByColumnAndRow(27, $row)->getValue();
            $netAmount=$worksheet->getCellByColumnAndRow(41, $row)->getValue();

            //new added columns
            $itemChannel=$worksheet->getCellByColumnAndRow(11, $row)->getValue();
            $subChannel=$worksheet->getCellByColumnAndRow(12, $row)->getValue();
            $business=$worksheet->getCellByColumnAndRow(14, $row)->getValue();
            $brandName=$worksheet->getCellByColumnAndRow(16, $row)->getValue();
            $motherPackName=$worksheet->getCellByColumnAndRow(18, $row)->getValue();
            $purchaseRateWithoutTax=$worksheet->getCellByColumnAndRow(22, $row)->getValue();
            $purchaseRateWithTax=$worksheet->getCellByColumnAndRow(23, $row)->getValue();
            $freeValue=$worksheet->getCellByColumnAndRow(28, $row)->getValue();
            $grossRate=$worksheet->getCellByColumnAndRow(30, $row)->getValue();
            $schemaDisc=$worksheet->getCellByColumnAndRow(31, $row)->getValue();
            $keyDisc=$worksheet->getCellByColumnAndRow(32, $row)->getValue();
            $rdDisc=$worksheet->getCellByColumnAndRow(33, $row)->getValue();
            $cddbDisc=$worksheet->getCellByColumnAndRow(34, $row)->getValue();
            $splDisc=$worksheet->getCellByColumnAndRow(35, $row)->getValue();
            $taxableValue=$worksheet->getCellByColumnAndRow(36, $row)->getValue();
            $taxPercent=$worksheet->getCellByColumnAndRow(37, $row)->getValue();
            $cessPercent=$worksheet->getCellByColumnAndRow(38, $row)->getValue();
            $taxAmount=$worksheet->getCellByColumnAndRow(39, $row)->getValue();
            $cessAmount=$worksheet->getCellByColumnAndRow(40, $row)->getValue();
            $grossNetVolume=$worksheet->getCellByColumnAndRow(42, $row)->getValue();
           
            if($tempBillNumber != $billNumber){
                // check bill exist or not
                $billExist=$this->ExcelModel->getBillByLastRecords('bills',$billNumber);
                //  echo count();exit;
                if(!empty($billExist)){
                    $billId=$billExist[0]['id'];
                    $getBillDetail=$this->ExcelModel->getAllBillDetailsInLastEntries('billsdetails',$billId,$productCode,$productName,$mrp,$quantity,$netAmount);
                    $statusForNestleBills=strtolower($deliveryStatus);
                    if(empty($getBillDetail)){
                        if($statusForNestleBills==='pending'){
                            if(trim($billNumber) !== trim($tempBillNumber)){
                                $data = array(
                                  'salesmanCode'=>$salesmanCode,
                                  'salesman'=>$salesmanName,
                                  'routeCode'=>$routeCode,
                                  'routeName'=>str_replace(",","-",$routeName),
                                  'deliveryStatus'=>$statusForNestleBills,
                                  'isTempCancelled'=>1,
                                  'retailerCode'=>$retailerCode,
                                  'retailerName'=>$retailerName,
                                  'insertedAt'=>date('Y-m-d H:i:sa')
                                );
                                $this->ExcelModel->update('bills',$data,$billId);
                            }
                        }else{
                            //inserting bill details for specific bill
                            if($quantity > 0){
                                $billDetailData=array(
                                    'billId'=>$billId,
                                    'productCode'=>$productCode,
                                    'productName'=>$productName,
                                    'mrp'=>$mrp,
                                    'qty'=>$quantity,
                                    'netAmount'=>$netAmount,
                                    'itemChannel'=>$itemChannel,
                                    'subChannel'=>$subChannel,
                                    'business'=>$business,
                                    'brandName'=>$brandName,
                                    'motherPackName'=>$motherPackName,
                                    'purchaseRateWithoutTax'=>$purchaseRateWithoutTax,
                                    'purchaseRateWithTax'=>$purchaseRateWithTax,
                                    'freeValue'=>$freeValue,
                                    'grossRate'=>$grossRate,
                                    'schemaDisc'=>$schemaDisc,
                                    'keyDisc'=>$keyDisc,
                                    'rdDisc'=>$rdDisc,
                                    'cddbDisc'=>$cddbDisc,
                                    'splDisc'=>$splDisc,
                                    'taxableValue'=>$taxableValue,
                                    'taxPercent'=>$taxPercent,
                                    'cessPercent'=>$cessPercent,
                                    'taxAmount'=>$taxAmount,
                                    'cessAmount'=>$cessAmount,
                                    'grossNetVolume'=>$grossNetVolume
                                );
                                $this->db->insert('billsdetails', $billDetailData); 
                                // array_push($batchInsert, $billDetailData);
                            }else{
                                $billDetailData=array(
                                    'billId'=>$billId,
                                    'billNo'=>$billNumber,
                                    'productCode'=>$productCode,
                                    'productName'=>$productName,
                                    'freeQuantity'=>$freeQuantity,
                                    'quantity'=>$quantity,
                                );
                                $this->db->insert('billsdetails_freeitems', $billDetailData); 
                            }
                           
    
                            if(trim($billNumber) !== trim($tempBillNumber)){
                                $data = array(
                                  'salesmanCode'=>$salesmanCode,
                                  'salesman'=>$salesmanName,
                                  'routeCode'=>$routeCode,
                                  'routeName'=>str_replace(",","-",$routeName),
                                  'deliveryStatus'=>$statusForNestleBills,
                                  'isTempCancelled'=>0,
                                  'retailerCode'=>$retailerCode,
                                  'retailerName'=>$retailerName,
                                  'insertedAt'=>date('Y-m-d H:i:sa')
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
                                'company'=>'Nestle'
                            );
                            $this->ExcelModel->insert('retailer',$retailerData);
                        }else{
                            $retailerData=array(
                                'name'=>$retailerName,
                                'company'=>'Nestle'
                            );
                            $this->ExcelModel->update('retailer',$retailerData,$retailerExist[0]['id']);
                        }
                    }
                    
                    if(trim($routeName) !== trim($tempRouteName)){
                        // check route exist or not
                        $routeExist=$this->ExcelModel->getInfoByCode('route',$routeCode);
                        if(empty($routeExist)){
                            $routeData=array(
                                'name'=>str_replace(",","-",$routeName),
                                'code'=>$routeCode,
                                'company'=>'Nestle'
                            );
                            $this->ExcelModel->insert('route',$routeData);
                        }else{
                            $routeData=array(
                                'name'=>str_replace(",","-",$routeName),
                                'company'=>'Nestle'
                            );
                            $this->ExcelModel->update('route',$routeData,$routeExist[0]['id']);
                        }
                    }
                }
            }else{
                $billId=$tempBillId;
                $getBillDetail=$this->ExcelModel->getAllBillDetailsInLastEntries('billsdetails',$billId,$productCode,$productName,$mrp,$quantity,$netAmount);
                $statusForNestleBills=strtolower($deliveryStatus);
                if(empty($getBillDetail)){
                    if($statusForNestleBills==='pending'){
                        if(trim($billNumber) !== trim($tempBillNumber)){
                            $data = array(
                              'salesmanCode'=>$salesmanCode,
                              'salesman'=>$salesmanName,
                              'routeCode'=>$routeCode,
                              'routeName'=>str_replace(",","-",$routeName),
                              'deliveryStatus'=>$statusForNestleBills,
                              'isTempCancelled'=>1,
                              'retailerCode'=>$retailerCode,
                              'retailerName'=>$retailerName,
                              'insertedAt'=>date('Y-m-d H:i:sa')
                            );
                            $this->ExcelModel->update('bills',$data,$billId);
                        }
                    }else{
                        //inserting bill details for specific bill
                        if($quantity > 0){
                            $billDetailData=array(
                                'billId'=>$billId,
                                'productCode'=>$productCode,
                                'productName'=>$productName,
                                'mrp'=>$mrp,
                                'qty'=>$quantity,
                                'netAmount'=>$netAmount,
                                'itemChannel'=>$itemChannel,
                                'subChannel'=>$subChannel,
                                'business'=>$business,
                                'brandName'=>$brandName,
                                'motherPackName'=>$motherPackName,
                                'purchaseRateWithoutTax'=>$purchaseRateWithoutTax,
                                'purchaseRateWithTax'=>$purchaseRateWithTax,
                                'freeValue'=>$freeValue,
                                'grossRate'=>$grossRate,
                                'schemaDisc'=>$schemaDisc,
                                'keyDisc'=>$keyDisc,
                                'rdDisc'=>$rdDisc,
                                'cddbDisc'=>$cddbDisc,
                                'splDisc'=>$splDisc,
                                'taxableValue'=>$taxableValue,
                                'taxPercent'=>$taxPercent,
                                'cessPercent'=>$cessPercent,
                                'taxAmount'=>$taxAmount,
                                'cessAmount'=>$cessAmount,
                                'grossNetVolume'=>$grossNetVolume
                            );
                            $this->db->insert('billsdetails', $billDetailData); 
                            // array_push($batchInsert, $billDetailData);
                        }else{
                            $billDetailData=array(
                                'billId'=>$billId,
                                'billNo'=>$billNumber,
                                'productCode'=>$productCode,
                                'productName'=>$productName,
                                'freeQuantity'=>$freeQuantity,
                                'quantity'=>$quantity,
                            );
                            $this->db->insert('billsdetails_freeitems', $billDetailData); 
                        }
                       

                        if(trim($billNumber) !== trim($tempBillNumber)){
                            $data = array(
                              'salesmanCode'=>$salesmanCode,
                              'salesman'=>$salesmanName,
                              'routeCode'=>$routeCode,
                              'routeName'=>str_replace(",","-",$routeName),
                              'deliveryStatus'=>$statusForNestleBills,
                              'isTempCancelled'=>0,
                              'retailerCode'=>$retailerCode,
                              'retailerName'=>$retailerName,
                              'insertedAt'=>date('Y-m-d H:i:sa')
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
                            'company'=>'Nestle'
                        );
                        $this->ExcelModel->insert('retailer',$retailerData);
                    }else{
                        $retailerData=array(
                            'name'=>$retailerName,
                            'company'=>'Nestle'
                        );
                        $this->ExcelModel->update('retailer',$retailerData,$retailerExist[0]['id']);
                    }
                }
                
                if(trim($routeName) !== trim($tempRouteName)){
                    // check route exist or not
                    $routeExist=$this->ExcelModel->getInfoByCode('route',$routeCode);
                    if(empty($routeExist)){
                        $routeData=array(
                            'name'=>str_replace(",","-",$routeName),
                            'code'=>$routeCode,
                            'company'=>'Nestle'
                        );
                        $this->ExcelModel->insert('route',$routeData);
                    }else{
                        $routeData=array(
                            'name'=>str_replace(",","-",$routeName),
                            'company'=>'Nestle'
                        );
                        $this->ExcelModel->update('route',$routeData,$routeExist[0]['id']);
                    }
                }
            }
            
            //store data in temp variables to skip duplicate entries
            $tempBillNumber=$billNumber;
            $tempRetailerName=$retailerName;
            $tempRouteName=$routeName;
            $tempBillId=$billId;
        }

        $updateData=array('status'=>2);
        $this->ExcelModel->updateByCompany('cron_settings',$updateData,'Nestle');

    }

    //Import Nestle Retailer data
    public function uploadNestleRetailerBillData($billFilePath){
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($billFilePath);
        $reader->setReadDataOnly(TRUE);
        $objPHPExcel = $reader->load($billFilePath);

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
                    'gstIn'=>$retailerGstNo,
                    'company'=>'Nestle'
                );
                $this->ExcelModel->update('retailer',$retailerData,$retailerId);
            }
        }
    }

    //Import ITC Bills
    public function uploadItcBillData($billFilePath){
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($billFilePath);
        $reader->setReadDataOnly(TRUE);
        $objPHPExcel = $reader->load($billFilePath);

        $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
        $highestRow = $worksheet->getHighestRow(); // e.g. 12
        $highestColumn = $worksheet->getHighestColumn(); // e.g M'

        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
        $lastBillNumber="";
        $cnt=0;
        for ($row = 1; $row <= $highestRow; ++$row) {
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
                $billExist=$this->ExcelModel->getBillByLastRecords('bills',$billNumber);
                if(empty($billExist)){
                    if((!empty($excelDate)) && ($excelDate!="1970-01-01")){
                        $data = array(
                          'date'=>$excelDate,
                          'billNo'=>$billNumber,
                          'retailerName'=>$retailerName,
                          'retailerCode'=>$retailerCode,
                          'routeName'=>str_replace(",","-",$route),
                          'salesman'=>$salesman,
                          'deliveryStatus'=>'delivered',
                          'billNetAmount'=>$netAmount,
                          'netAmount'=>$netAmount,
                          'pendingAmt'=>$netAmount,
                          'compName'=>'ITC',
                          'insertedAt'=>date('Y-m-d H:i:sa')
                        );
                        $this->ExcelModel->insert('bills',$data);

                        // check retailer exist or not
                        $retailerExist=$this->ExcelModel->retailerInfo('retailer',$retailerName,$retailerCode);
                        if(empty($retailerExist)){
                            $retailerData=array(
                                'name'=>$retailerName,
                                'code'=>$retailerCode,
                                'gstIn'=>$retailerGstNo,
                                'company'=>'ITC'
                            );
                            $this->ExcelModel->insert('retailer',$retailerData);
                        }

                        // check retailer exist or not
                        $routeExist=$this->ExcelModel->getRouteInfo('route',$route,"");
                        if(empty($routeExist)){
                            $routeData=array(
                                'name'=>$route,
                                'company'=>'ITC'
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
                              'routeName'=>str_replace(",","-",$route),
                              'salesman'=>$salesman,
                              'deliveryStatus'=>'delivered',
                              'billNetAmount'=>$netAmount,
                              'netAmount'=>$netAmount,
                              'pendingAmt'=>$newPendingAmt,
                              'compName'=>'ITC',
                              'insertedAt'=>date('Y-m-d H:i:sa')
                            );
                            $this->ExcelModel->update('bills',$data,$billId);

                            // check retailer exist or not
                            $retailerExist=$this->ExcelModel->retailerInfo('retailer',$retailerName,$retailerCode);
                            if(empty($retailerExist)){
                                $retailerData=array(
                                    'name'=>$retailerName,
                                    'code'=>$retailerCode,
                                    'gstIn'=>$retailerGstNo,
                                    'company'=>'ITC'
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

     //Import ITC bill details
    public function uploadItcBillDetailData($billFilePath){
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($billFilePath);
        $reader->setReadDataOnly(TRUE);
        $objPHPExcel = $reader->load($billFilePath);

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
                $billExist=$this->ExcelModel->getBillByLastRecords('bills',$billNumber);
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

        $updateData=array('status'=>2);
        $this->ExcelModel->updateByCompany('cron_settings',$updateData,'ITC');
    }

     //Import parle bills
    public function uploadParleBillData($billFilePath){
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($billFilePath);
        $reader->setReadDataOnly(TRUE);
        $objPHPExcel = $reader->load($billFilePath);

        $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
        $highestRow = $worksheet->getHighestRow(); // e.g. 12
        $highestColumn = $worksheet->getHighestColumn(); // e.g M'

        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7

        $lastBillNumber="";
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
                $billExist=$this->ExcelModel->getBillByLastRecords('bills',$billNumber);
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
                              'compName'=>'Parle',
                              'insertedAt'=>date('Y-m-d H:i:sa')
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
                                  'compName'=>'Parle',
                                  'insertedAt'=>date('Y-m-d H:i:sa')
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

    //Import parle bill details 
    public function uploadParleBillDetailData($billFilePath){
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($billFilePath);
        $reader->setReadDataOnly(TRUE);
        $objPHPExcel = $reader->load($billFilePath);

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
                    $billExist=$this->ExcelModel->getBillByLastRecords('bills',$sesBillNumber);
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

        $updateData=array('status'=>2);
        $this->ExcelModel->updateByCompany('cron_settings',$updateData,'Parle');
    }

    //check date for nestle bills data uploading
    public function nestleExcelUploading($fileName,$fileType,$fileTempName,$dateForUploadBills){
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
            $billNumberHeader="";
            $billDateHeader="";
            $retailerNameHeader = "";
            $retailerCodeHeader = "";
            $billNetAmountHeader = "";
            $netAmountHeader = "";
            $creditAdjustmentHeader="";
            $billNumber="";
            for ($row = 1; $row <= $highestRow; ++$row) {
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
                
                $billNumber = $worksheet->getCellByColumnAndRow($billNumberHeader, $row)->getValue();
                $billDate = $worksheet->getCellByColumnAndRow($billDateHeader, $row)->getValue();
                $retailerName = $worksheet->getCellByColumnAndRow($retailerNameHeader, $row)->getValue();
                $retailerCode = $worksheet->getCellByColumnAndRow($retailerCodeHeader, $row)->getValue();
                $amount = $worksheet->getCellByColumnAndRow($billNetAmountHeader, $row)->getValue();
                $netAmount = $worksheet->getCellByColumnAndRow($netAmountHeader, $row)->getValue();
                $creditAdjustment=$worksheet->getCellByColumnAndRow($creditAdjustmentHeader, $row)->getValue();

                $excelDate="";
                if($extension==='csv'){
                    if(!empty($billDate)){
                        $excelDate=date('Y-m-d', strtotime($billDate));
                    }
                }else{
                    if(!empty($billDate) && ($billDate !=='Bill Date')){
                        $billDate =str_replace("/","-",$billDate);
                        $excelDate=date('Y-m-d', strtotime($billDate));
                    }
                }
                if($dateForUploadBills !==""){
                    if($billDate !=="Bill Date"){
                        $excelDate = date("Y-m-d", strtotime('-15 days', strtotime($excelDate)));
                        if(($excelDate > $dateForUploadBills) && ($billDate !=='Bill Date')){
                            return "Please uploads bills from date: ".$dateForUploadBills;
                        }else{
                            return "";
                        }
                    }
                }
            }
        }
    }

    //Import ITC Bills for Tally bills
    public function itcExcelUploading($billFilePath){
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($billFilePath);
        $reader->setReadDataOnly(TRUE);
        $objPHPExcel = $reader->load($billFilePath);

        $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
        $highestRow = $worksheet->getHighestRow(); // e.g. 12
        $highestColumn = $worksheet->getHighestColumn(); // e.g M'

        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7

        $cnt=0;
        $total="";

        $billDateHeader="";
        $billNumberHeader="";
        $retailerCodeHeader="";
        $voucherTypeHeader="";
        $getPercentHeader="";
        $gstNumberHeader="";
        $panNumberHeader="";
        $quantityHeader="";
        $valueHeader="";
        $grossTotalHeader="";
        $cgstHeader="";
        $sgstHeader="";

        for ($row = 1; $row <= $highestRow; ++$row) {
            $cnt++;
            for($i=1;$i<=$highestColumnIndex;$i++){
                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Date"){
                    $billDateHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Particulars"){
                    $retailerCodeHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Voucher Type"){
                    $voucherTypeHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Voucher No."){
                    $billNumberHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="GST %"){
                    $getPercentHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="GSTIN/UIN"){
                    $gstNumberHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="PAN No."){
                    $panNumberHeader= $i;
                }
                

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Quantity"){
                    $quantityHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Value"){
                    $valueHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Gross Total"){
                    $grossTotalHeader= $i;
                    $total=$cnt;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="CGST"){
                    $cgstHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="SGST"){
                    $sgstHeader= $i;
                }
            }
        }
        if((empty($billDateHeader) || empty($cgstHeader) || empty($sgstHeader) || empty($billNumberHeader) || empty($retailerCodeHeader) || empty($voucherTypeHeader) || empty($getPercentHeader) || empty($gstNumberHeader) || empty($panNumberHeader) || empty($quantityHeader) || empty($valueHeader) || empty($grossTotalHeader))){
            echo "Source file not in correct order. Please select correct files for uploading.";
            exit;
        }

        for ($row = ($total+1); $row <= $highestRow; ++$row) {
            $billDate = trim($worksheet->getCellByColumnAndRow($billDateHeader, $row)->getValue());
            $retailerName = trim($worksheet->getCellByColumnAndRow($retailerCodeHeader, $row)->getValue());
            $voucherType = trim($worksheet->getCellByColumnAndRow($voucherTypeHeader, $row)->getValue());
            $billNo = trim($worksheet->getCellByColumnAndRow($billNumberHeader, $row)->getValue());
            $gstPercent = trim($worksheet->getCellByColumnAndRow($getPercentHeader, $row)->getValue());
            $gstNo = trim($worksheet->getCellByColumnAndRow($gstNumberHeader, $row)->getValue());
            $panNo = trim($worksheet->getCellByColumnAndRow($panNumberHeader, $row)->getValue());
            $quantity = trim($worksheet->getCellByColumnAndRow($quantityHeader, $row)->getValue());
            $amoutWithoutTax = trim($worksheet->getCellByColumnAndRow($valueHeader, $row)->getValue());
            $netAmount = trim($worksheet->getCellByColumnAndRow($grossTotalHeader, $row)->getValue());

            $cgst = trim($worksheet->getCellByColumnAndRow($cgstHeader, $row)->getValue());
            $sgst = trim($worksheet->getCellByColumnAndRow($sgstHeader, $row)->getValue());
                
            $excelDate="";
            if(($billDate !=="") && ($retailerName !== "(cancelled)")){
                if(!empty($billDate) && $billDate !=='Bill Date'){
                    $billDate =str_replace("/","-",$billDate);
                    $date = ($billDate - 25569) * 86400;
                    $excelDate=date('Y-m-d', $date);//convert date from excel data

                    $billExist=$this->ExcelModel->getBillByLastRecords('bills',$billNo);

                    // $pan="";
                    if($panNo==""){
                        $retailerCount=$this->ExcelModel->getdata('retailer');

                        $retailerDataExist=$this->ExcelModel->getDataByName('retailer',$retailerName);
                        if(empty($retailerDataExist)){
                            $panNo="RETNO1000".count($retailerCount);
                        }else{
                            $panNo=$retailerDataExist[0]['code'];
                        }
                        
                    }

                    if(empty($billExist)){
                        $arrayRes=array(
                            'date'=>$excelDate,
                            'billNo'=>$billNo,
                            'deliveryStatus'=>'delivered',
                            'retailerCode'=>$panNo,
                            'retailerName'=>$retailerName,
                            'grossAmount'=>$netAmount,
                            'taxAmount'=>($cgst+$sgst),
                            'billNetAmount'=>round($netAmount),
                            'netAmount'=>round($netAmount),
                            'pendingAmt'=>round($netAmount),
                            'compName'=>'ITC',
                            'invoiceType'=>$voucherType,
                            'routeCode'=>'NOROUTE',
                            'routeName'=>'NO ROUTE',
                            'insertedAt'=>date('Y-m-d H:i:sa')
                        );
                        $this->ExcelModel->insert('bills',$arrayRes);
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
                        if(($billNetAmount != $billPendingAmt)){
                            $totalRecAmt=$billSrAmt+$billReceivedAmt+$billCd+$billDebit+$billOfficeAdjustment+$billOtherAdjustment;
                            $newPendingAmt=$netAmount-$totalRecAmt;
                            if((!empty($excelDate)) && ($excelDate != "1970-01-01")){
                                $data = array(
                                    'date'=>$excelDate,
                                    'billNo'=>$billNo,
                                    'retailerCode'=>$panNo,
                                    'retailerName'=>$retailerName,
                                    'grossAmount'=>$grossAmount,
                                    'taxAmount'=>($cgst+$sgst),
                                    'billNetAmount'=>round($netAmount),
                                    'netAmount'=>round($netAmount),
                                    'pendingAmt'=>round($newPendingAmt),
                                    'compName'=>'ITC',
                                    'invoiceType'=>$voucherType,
                                    'routeCode'=>'NOROUTE',
                                    'routeName'=>'NO ROUTE',
                                    'insertedAt'=>date('Y-m-d H:i:sa')
                                );
                                $this->ExcelModel->update('bills',$data,$billId);
                            }
                        }
                    }

                    $retailerDetails=$this->ExcelModel->retailerInfo('retailer',$retailerName,$panNo);
                    if(empty($retailerDetails)){
                        $retailerData=array(
                            'code'=>$panNo,
                            'name'=>$retailerName,
                            'gstIn'=>$gstNo,
                            'company'=>'ITC'
                        );
                        $this->ExcelModel->insert('retailer',$retailerData);
                    }

                    $routeDetails=$this->ExcelModel->routeInfo('route','NO ROUTE','NOROUTE');
                    if(empty($routeDetails)){
                        $routeData=array(
                            'code'=>'NOROUTE',
                            'name'=>'NO ROUTE',
                            'company'=>'ITC'
                        );
                        $this->ExcelModel->insert('route',$routeData);
                    }
                }
            }else if(($billDate !=="") && ($retailerName == "(cancelled)")){
                $arrayRes=array(
                    'date'=>$excelDate,
                    'billNo'=>$billNo,
                    'deliveryStatus'=>'cancelled',
                    'compName'=>'ITC',
                    'invoiceType'=>$voucherType,
                    'insertedAt'=>date('Y-m-d H:i:sa')
                );
                $this->ExcelModel->insert('bills',$arrayRes);
            }
        }

        $billId=0;
        //upload bill details value
        for ($row = ($total+1); $row <= $highestRow; ++$row) {
            $billDate = $worksheet->getCellByColumnAndRow($billDateHeader, $row)->getValue();
            $billNo = $worksheet->getCellByColumnAndRow($billNumberHeader, $row)->getValue();
            $excelDate="";

            if($billDate !==""){
                $billExist=$this->ExcelModel->getBillByLastRecords('bills',$billNo);
                // print_r($billExist);exit;
                if(!empty($billExist)){
                    $billId=$billExist[0]['id'];
                }
            }

            if($billDate ==""){
                $itemName = trim($worksheet->getCellByColumnAndRow($retailerCodeHeader, $row)->getValue());
                $gstPercent = trim($worksheet->getCellByColumnAndRow($getPercentHeader, $row)->getValue());
                $quantity = trim($worksheet->getCellByColumnAndRow($quantityHeader, $row)->getValue());
                $amoutWithoutTax = trim($worksheet->getCellByColumnAndRow($valueHeader, $row)->getValue());
                if($itemName != "Grand Total"){
                    $checkItemDetails=$this->ExcelModel->checkBillDetailsData('billsdetails',$billId,$itemName,$gstPercent,$quantity);
                    if(empty($checkItemDetails)){
                        if($gstPercent==""){
                            $rateWithGst=(($amoutWithoutTax/100)*0);
                            $productArray=array(
                                'billId'=>$billId,
                                'productName'=>$itemName,
                                'gstPercent'=>$gstPercent,
                                'qty'=>$quantity,
                                'netAmount'=>($amoutWithoutTax+$rateWithGst)
                            );
                            $this->ExcelModel->insert('billsdetails',$productArray);
                        }else{
                            $rateWithGst=(($amoutWithoutTax/100)*$gstPercent);
                            $productArray=array(
                                'billId'=>$billId,
                                'productName'=>$itemName,
                                'gstPercent'=>$gstPercent,
                                'qty'=>$quantity,
                                'netAmount'=>($amoutWithoutTax+$rateWithGst)
                            );
                            $this->ExcelModel->insert('billsdetails',$productArray);
                        }
                    }
                }
            }
            
        }

        $updateData=array('status'=>2);
        $this->ExcelModel->updateByCompany('cron_settings',$updateData,'ITC');
    }

    //check date for ITC bills data uploading
    public function checkItcExcelUploading($fileName,$fileType,$fileTempName,$dateForUploadBills){
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
            $total="";

            $billDateHeader="";
            $billNumberHeader="";
            $retailerCodeHeader="";
            $voucherTypeHeader="";
            $getPercentHeader="";
            $gstNumberHeader="";
            $quantityHeader="";
            $valueHeader="";
            $grossTotalHeader="";
            $cgstHeader="";
            $sgstHeader="";
            
            for ($row = 1; $row <= $highestRow; ++$row) {
                $cnt++;
                for($i=1;$i<=$highestColumnIndex;$i++){
                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Date"){
                        $billDateHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Particulars"){
                        $retailerCodeHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Voucher Type"){
                        $voucherTypeHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Voucher No."){
                        $billNumberHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="GST %"){
                        $getPercentHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="GSTIN/UIN"){
                        $gstNumberHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Quantity"){
                        $quantityHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Value"){
                        $valueHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Gross Total"){
                        $grossTotalHeader= $i;
                        $total=$cnt;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="CGST"){
                        $cgstHeader= $i;
                    }
    
                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="SGST"){
                        $sgstHeader= $i;
                    }
                }
            }
            if((empty($billDateHeader) || empty($cgstHeader) || empty($sgstHeader) || empty($billNumberHeader) || empty($retailerCodeHeader) || empty($voucherTypeHeader) || empty($getPercentHeader) || empty($gstNumberHeader) || empty($quantityHeader) || empty($valueHeader) || empty($grossTotalHeader))){
                    echo "Source file not in correct order. Please select correct files for uploading.";
                    exit;
            }

            for ($row = ($total+1); $row <= $highestRow; ++$row) {

                $billDate = $worksheet->getCellByColumnAndRow($billDateHeader, $row)->getValue();
                $retailerName = $worksheet->getCellByColumnAndRow($retailerCodeHeader, $row)->getValue();
                $voucherType = $worksheet->getCellByColumnAndRow($voucherTypeHeader, $row)->getValue();
                $billNo = $worksheet->getCellByColumnAndRow($billNumberHeader, $row)->getValue();
                $gstPercent = $worksheet->getCellByColumnAndRow($getPercentHeader, $row)->getValue();
                $gstNo = $worksheet->getCellByColumnAndRow($gstNumberHeader, $row)->getValue();
                $quantity = $worksheet->getCellByColumnAndRow($quantityHeader, $row)->getValue();
                $amoutWithoutTax = $worksheet->getCellByColumnAndRow($valueHeader, $row)->getValue();
                $netAmount = $worksheet->getCellByColumnAndRow($grossTotalHeader, $row)->getValue();
                $excelDate="";
                if($billDate !==""){
                    if(!empty($billDate) && $billDate !=='Bill Date'){
                        $billDate =str_replace("/","-",$billDate);
                        $date = ($billDate - 25569) * 86400;
                        $excelDate=date('Y-m-d', $date);//convert date from excel data
                    }
                }

                // $excelDate="";
                if($extension==='csv'){
                    if(!empty($billDate)){
                        $excelDate=date('Y-m-d', strtotime($billDate));
                    }
                }else{
                    if(!empty($billDate) && ($billDate !=='Bill Date')){
                        $billDate =str_replace("/","-",$billDate);
                        $excelDate=date('Y-m-d', strtotime($billDate));
                    }
                }
                if($dateForUploadBills !==""){
                    if($billDate !=="Bill Date"){
                        $excelDate = date("Y-m-d", strtotime('-15 days', strtotime($excelDate)));
                        if(($excelDate > $dateForUploadBills) && ($billDate !=='Bill Date')){
                            return "Please uploads bills from date: ".$dateForUploadBills;
                        }else{
                            return "";
                        }
                    }
                }

            }
        }
    }

    //check date for Havells bills data uploading
    public function checkHavellsExcelUploading($fileName,$fileType,$fileTempName,$dateForUploadBills){
        $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        if(isset($fileName) && in_array($fileType, $file_mimes)) {
            $arr_file = explode('.', $fileName); //get file
            $extension = end($arr_file); //get file extension
            // select spreadsheet reader depends on file extension
            if($extension == 'csv') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else if ($extension =='xlsx'){
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
            $total="";

            $billNumberHeader="";
            $billDateHeader="";
            $grossAmountHeader="";
            $netAmountHeader="";
            $salesmanNameHeader="";
            $routeNameHeader="";
            $retailerCodeHeader="";
            $retailerNameHeader="";
            $productCodeHeader="";
            $productNameHeader="";
            $sellingRateHeader="";
            $qtyHeader="";
            $freeQtyHeader="";
            $itemTaxableAmtHeader="";
            $itemTaxAmtHeader="";
            $sdHeader="";
            $cdHeader="";
            $itemNetAmountHeader="";
            $deliveryStatusHeader="";
            
            for ($row = 1; $row <= $highestRow; ++$row) {
                $cnt++;
                for($i=1;$i<=$highestColumnIndex;$i++){
                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Sales Invoice No"){
                        $billNumberHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Sales Invoice Date"){
                        $billDateHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Gross Amount"){
                        $grossAmountHeader= $i;
                    }
                
                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Net Amount"){
                        $netAmountHeader= $i;
                        $total=$cnt;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Salesman Name"){
                        $salesmanNameHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Route Name"){
                        $routeNameHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Retailer Code"){
                        $retailerCodeHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Retailer Name"){
                        $retailerNameHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Product Code"){
                        $productCodeHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Product Name"){
                        $productNameHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Selling Rate After Tax"){
                        $sellingRateHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Sales Qty"){
                        $qtyHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Free Quantity"){
                        $freeQtyHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Product Taxable Amt"){
                        $itemTaxableAmtHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Product Tax Amount"){
                        $itemTaxAmtHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Product Scheme Discount"){
                        $sdHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Product Cash Discount"){
                        $cdHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Product Net Amount"){
                        $itemNetAmountHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Invoice Status"){
                        $deliveryStatusHeader= $i;
                    }
                }
            }
            
            if((empty($billNumberHeader) || empty($billDateHeader) || empty($grossAmountHeader) || empty($netAmountHeader) || empty($salesmanNameHeader) || empty($routeNameHeader) || empty($retailerCodeHeader) || empty($retailerNameHeader) || empty($productCodeHeader) || empty($productNameHeader) || empty($sellingRateHeader) || empty($qtyHeader) || empty($freeQtyHeader) || empty($itemTaxableAmtHeader) || empty($itemTaxAmtHeader) || empty($sdHeader) || empty($cdHeader) || empty($itemNetAmountHeader) || empty($deliveryStatusHeader))){
                    echo "Source file not in correct order. Please select correct files for uploading.";
                    exit;
            }

            for ($row = ($total+1); $row <= $highestRow; ++$row) {
                $billDate = $worksheet->getCellByColumnAndRow($billDateHeader, $row)->getValue();
                if($billDate !=""){
                    $billDate =str_replace("/","-",$billDate);
                    $date = ($billDate - 25569) * 86400;
                    $billDate=date('Y-m-d', $date);//convert date from excel data
                
                    if($dateForUploadBills !==""){
                        if($billDate !=="Bill Date"){
                            $excelDate = date("Y-m-d", strtotime('-15 days', strtotime($billDate)));
                            if(($excelDate > $dateForUploadBills) && ($billDate !=='Bill Date')){
                                return "Please uploads bills from date: ".$dateForUploadBills;
                            }else{
                                return "";
                            }
                        }
                    }
                }
            }
        }
    }

    //Import Havells Bills
    public function uploadHavellsExcelUploading($billFilePath){
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($billFilePath);
        $reader->setReadDataOnly(TRUE);
        $objPHPExcel = $reader->load($billFilePath);

        $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
        $highestRow = $worksheet->getHighestRow(); // e.g. 12
        $highestColumn = $worksheet->getHighestColumn(); // e.g M'

        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
        
        $cnt=0;
        $total="";

        $billNumberHeader="";
        $billDateHeader="";
        $grossAmountHeader="";
        $netAmountHeader="";
        $salesmanNameHeader="";
        $routeNameHeader="";
        $retailerCodeHeader="";
        $retailerNameHeader="";
        $productCodeHeader="";
        $productNameHeader="";
        $sellingRateHeader="";
        $qtyHeader="";
        $freeQtyHeader="";
        $itemTaxableAmtHeader="";
        $itemTaxAmtHeader="";
        $sdHeader="";
        $cdHeader="";
        $itemNetAmountHeader="";
        $deliveryStatusHeader="";
        $taxAmtHeader="";

        $tempRetailerName="";
        $tempRouteName="";
        
        for ($row = 1; $row <= $highestRow; ++$row) {
            $cnt++;
            for($i=1;$i<=$highestColumnIndex;$i++){
                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Sales Invoice No"){
                    $billNumberHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Sales Invoice Date"){
                    $billDateHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Gross Amount"){
                    $grossAmountHeader= $i;
                }
            
                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Net Amount"){
                    $netAmountHeader= $i;
                    $total=$cnt;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Salesman Name"){
                    $salesmanNameHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Route Name"){
                    $routeNameHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Retailer Code"){
                    $retailerCodeHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Retailer Name"){
                    $retailerNameHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Product Code"){
                    $productCodeHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Product Name"){
                    $productNameHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Selling Rate After Tax"){
                    $sellingRateHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Sales Qty"){
                    $qtyHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Free Quantity"){
                    $freeQtyHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Product Taxable Amt"){
                    $itemTaxableAmtHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Product Tax Amount"){
                    $itemTaxAmtHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Product Scheme Discount"){
                    $sdHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Product Cash Discount"){
                    $cdHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Product Net Amount"){
                    $itemNetAmountHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Invoice Status"){
                    $deliveryStatusHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Total Deduction(-)"){
                    $taxAmtHeader= $i;
                }
            }
        }
        
        if((empty($billNumberHeader) || empty($taxAmtHeader) || empty($billDateHeader) || empty($grossAmountHeader) || empty($netAmountHeader) || empty($salesmanNameHeader) || empty($routeNameHeader) || empty($retailerCodeHeader) || empty($retailerNameHeader) || empty($productCodeHeader) || empty($productNameHeader) || empty($sellingRateHeader) || empty($qtyHeader) || empty($freeQtyHeader) || empty($itemTaxableAmtHeader) || empty($itemTaxAmtHeader) || empty($sdHeader) || empty($cdHeader) || empty($itemNetAmountHeader) || empty($deliveryStatusHeader))){
                echo "Source file not in correct order. Please select correct files for uploading.";
                exit;
        }
        
        for ($row = ($total+1); $row <= $highestRow; ++$row) {
            $billNumber=trim($worksheet->getCellByColumnAndRow($billNumberHeader, $row)->getValue());
            $billDate=trim($worksheet->getCellByColumnAndRow($billDateHeader, $row)->getValue());
            $grossAmount=trim($worksheet->getCellByColumnAndRow($grossAmountHeader, $row)->getValue());
            $netAmount=trim($worksheet->getCellByColumnAndRow($netAmountHeader, $row)->getValue());
            $salesmanName=trim($worksheet->getCellByColumnAndRow($salesmanNameHeader, $row)->getValue());
            $routeName=trim($worksheet->getCellByColumnAndRow($routeNameHeader, $row)->getValue());
            $retailerCode=trim($worksheet->getCellByColumnAndRow($retailerCodeHeader, $row)->getValue());
            $retailerName=trim($worksheet->getCellByColumnAndRow($retailerNameHeader, $row)->getValue());
            $productCode=trim($worksheet->getCellByColumnAndRow($productCodeHeader, $row)->getValue());
            $productName=trim($worksheet->getCellByColumnAndRow($productNameHeader, $row)->getValue());
            $sellingRate=trim($worksheet->getCellByColumnAndRow($sellingRateHeader, $row)->getValue());
            $qty=trim($worksheet->getCellByColumnAndRow($qtyHeader, $row)->getValue());
            $freeQty=trim($worksheet->getCellByColumnAndRow($freeQtyHeader, $row)->getValue());
            $itemTaxableAmt=trim($worksheet->getCellByColumnAndRow($itemTaxableAmtHeader, $row)->getValue());
            $itemTaxAmt=trim($worksheet->getCellByColumnAndRow($itemTaxAmtHeader, $row)->getValue());
            $sd=trim($worksheet->getCellByColumnAndRow($sdHeader, $row)->getValue());
            $cd=trim($worksheet->getCellByColumnAndRow($cdHeader, $row)->getValue());
            $itemNetAmount=trim($worksheet->getCellByColumnAndRow($itemNetAmountHeader, $row)->getValue());
            $deliveryStatus=trim($worksheet->getCellByColumnAndRow($deliveryStatusHeader, $row)->getValue());

            $taxAmt=trim($worksheet->getCellByColumnAndRow($taxAmtHeader, $row)->getValue());
            
            $billExist=$this->ExcelModel->getBillByLastRecords('bills',$billNumber);
            if(empty($billExist)){
                if($productName!="Total"){
                    $billDate =str_replace("/","-",$billDate);
                    $date = ($billDate - 25569) * 86400;
                    $billDate=date('Y-m-d', $date);
    
                    $readArray=array(
                        'billNo'=>$billNumber,
                        'date'=>$billDate,
                        'deliveryStatus'=>$deliveryStatus,
                        'retailerCode'=>$retailerCode,
                        'retailerName'=>$retailerName,
                        'salesman'=>$salesmanName,
                        'routeName'=>$routeName,
                        'grossAmount'=>$grossAmount,
                        'billNetAmount'=>$netAmount,
                        'taxAmount'=>$taxAmt,
                        'netAmount'=>$netAmount,
                        'pendingAmt'=>$netAmount,
                        'compName'=>'Havells',
                        'insertedAt'=>date('Y-m-d H:i:sa')
                    );
                    $this->ExcelModel->insert('bills',$readArray);
                }
            }

            if(trim($retailerName) !== trim($tempRetailerName)){
                // check retailer exist or not
                $retailerExist=$this->ExcelModel->getInfoByCode('retailer',$retailerCode);
                if(empty($retailerExist)){
                    $retailerData=array(
                        'name'=>$retailerName,
                        'code'=>$retailerCode,
                        'company'=>'Havells'
                    );
                    $this->ExcelModel->insert('retailer',$retailerData);
                }else{
                    $retailerData=array(
                        'name'=>$retailerName,
                        'company'=>'Havells'
                    );
                    $this->ExcelModel->update('retailer',$retailerData,$retailerExist[0]['id']);
                }
            }
            
            if(trim($routeName) !== trim($tempRouteName)){
                $routeData=$this->ExcelModel->getdata('route');
                
                $routeCode="ROUTE1";
                if(!empty($routeData)){
                    $routeCode='ROUTE1'.count($routeData);
                }

                $routeExist=$this->ExcelModel->routeInfoByName('route',$routeName);
                if(empty($routeExist)){
                    $routeData=array(
                        'name'=>str_replace(",","-",$routeName),
                        'code'=>$routeCode,
                        'company'=>'Havells'
                    );
                    $this->ExcelModel->insert('route',$routeData);
                }
            }

            $tempRetailerName=$retailerName;
            $tempRouteName=$routeName;
        }

        for ($row = ($total+1); $row <= $highestRow; ++$row) {
            //bill details
            $billNumber=trim($worksheet->getCellByColumnAndRow($billNumberHeader, $row)->getValue());
            $billDate=trim($worksheet->getCellByColumnAndRow($billDateHeader, $row)->getValue());
               
            //product details
            $productCode=trim($worksheet->getCellByColumnAndRow($productCodeHeader, $row)->getValue());
            $productName=trim($worksheet->getCellByColumnAndRow($productNameHeader, $row)->getValue());
            $sellingRate=trim($worksheet->getCellByColumnAndRow($sellingRateHeader, $row)->getValue());
            $qty=trim($worksheet->getCellByColumnAndRow($qtyHeader, $row)->getValue());
            $freeQty=trim($worksheet->getCellByColumnAndRow($freeQtyHeader, $row)->getValue());
            $itemTaxableAmt=trim($worksheet->getCellByColumnAndRow($itemTaxableAmtHeader, $row)->getValue());
            $itemTaxAmt=trim($worksheet->getCellByColumnAndRow($itemTaxAmtHeader, $row)->getValue());
            $sd=trim($worksheet->getCellByColumnAndRow($sdHeader, $row)->getValue());
            $cd=trim($worksheet->getCellByColumnAndRow($cdHeader, $row)->getValue());
            $itemNetAmount=trim($worksheet->getCellByColumnAndRow($itemNetAmountHeader, $row)->getValue());
           
           
            $billExist=$this->ExcelModel->getBillByLastRecords('bills',$billNumber);
            if(!empty($billExist)){
                if($productName!="Total"){
                    $billDate =str_replace("/","-",$billDate);
                    $date = ($billDate - 25569) * 86400;
                    $billDate=date('Y-m-d', $date);

                    $billId=$billExist[0]['id'];
                    $checkItemDetails=$this->ExcelModel->getAllItcBillDetails('billsdetails',$billId,$productCode,$productName,$sellingRate,$qty,$itemNetAmount);
                    if(empty($checkItemDetails)){
                        $readArray=array(
                            'billId'=>$billId,
                            'productCode'=>$productCode,
                            'motherPackName'=>$productName,
                            'productName'=>$productName,
                            'mrp'=>$sellingRate,
                            'sellingRate'=>$sellingRate,
                            'qty'=>$qty,
                            'netAmount'=>$itemNetAmount,
                            'grossRate'=>$itemTaxableAmt,
                            'schemaDisc'=>$sd,
                            'cddbDisc'=>$cd,
                            'taxableValue'=>$itemTaxableAmt,
                            'taxAmount'=>$itemTaxAmt
                        );
                        $this->ExcelModel->insert('billsdetails',$readArray);
                    }
                }
                
            }
        }

        $updateData=array('status'=>2);
        $this->ExcelModel->updateByCompany('cron_settings',$updateData,'Havells');
    }

    //Import Havells Retailers Bills
    public function uploadHavellsRetailersExcelUploading($billFilePath){
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($billFilePath);
        $reader->setReadDataOnly(TRUE);
        $objPHPExcel = $reader->load($billFilePath);

        $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
        $highestRow = $worksheet->getHighestRow(); // e.g. 12
        $highestColumn = $worksheet->getHighestColumn(); // e.g M'

        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
        
        $cnt=0;
        $total="";

        $retailerCodeHeader="";
        $retailerNameHeader="";
        $gstHeader="";
       
        // echo "hey ";
        for ($row = 1; $row <= $highestRow; ++$row) {
            $cnt++;
            for($i=1;$i<=$highestColumnIndex;$i++){
                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Power Plus No"){
                    $retailerCodeHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Retailer Name"){
                    $retailerNameHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="GSTIN Number"){
                    $gstHeader= $i;
                    $total=$cnt;
                }
            }
        }
        
        if((empty($retailerCodeHeader) || empty($retailerNameHeader) || empty($gstHeader))){
                echo "Source file not in correct order. Please select correct files for uploading.";
                exit;
        }
        
        for ($row = ($total+1); $row <= $highestRow; ++$row) {
            $retailerCode=trim($worksheet->getCellByColumnAndRow($retailerCodeHeader, $row)->getValue());
            $retailerName=trim($worksheet->getCellByColumnAndRow($retailerNameHeader, $row)->getValue());
            $gst=trim($worksheet->getCellByColumnAndRow($gstHeader, $row)->getValue());
           
            // check retailer exist or not
            $retailerExist=$this->ExcelModel->getInfoByCode('retailer',$retailerCode);
            // print_r($retailerExist);exit;
            if(empty($retailerExist)){
                $retailerData=array(
                    'name'=>$retailerName,
                    'code'=>$retailerCode,
                    'gstIn'=>$gst,
                    'company'=>'Havells'
                );
                // print_r($retailerData);exit;
                $this->ExcelModel->insert('retailer',$retailerData);
            }else{
                $retailerData=array(
                    'name'=>$retailerName,
                    'gstIn'=>$gst,
                    'company'=>'Havells'
                );
                $this->ExcelModel->update('retailer',$retailerData,$retailerExist[0]['id']);
            }
        }

        $updateData=array('status'=>2);
        $this->ExcelModel->updateByCompany('cron_settings',$updateData,'Havells');
    }

    //check date for Jockey bills data uploading
    public function checkHavellsRetailerExcelUploading($fileName,$fileType,$fileTempName,$dateForUploadBills){
        $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        if(isset($fileName) && in_array($fileType, $file_mimes)) {
            $arr_file = explode('.', $fileName); //get file
            $extension = end($arr_file); //get file extension
            // select spreadsheet reader depends on file extension
            if($extension == 'csv') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else if ($extension =='xlsx'){
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
            $total="";

            
            $retailerCodeHeader="";
            $retailerNameHeader="";
            $gstNumberHeader="";
        
            
            for ($row = 1; $row <= $highestRow; ++$row) {
                $cnt++;
                for($i=1;$i<=$highestColumnIndex;$i++){
                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Power Plus No"){
                        $retailerCodeHeader= $i;
                    }
                    // echo $billDateHeader;
                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Retailer Name"){
                        $retailerNameHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="GSTIN Number"){
                        $gstNumberHeader= $i;
                        $total=$cnt;
                    }
                }
            }
            
            if((empty($retailerCodeHeader) || empty($retailerNameHeader) || empty($gstNumberHeader))){
                    echo "Source file not in correct order. Please select correct files for uploading.";
                    exit;
            }
        }
    }
    
    //check date for Jockey bills data uploading
    public function checkJockeyExcelUploading($fileName,$fileType,$fileTempName,$dateForUploadBills){
        $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        if(isset($fileName) && in_array($fileType, $file_mimes)) {
            $arr_file = explode('.', $fileName); //get file
            $extension = end($arr_file); //get file extension
            // select spreadsheet reader depends on file extension
            if($extension == 'csv') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else if ($extension =='xlsx'){
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
            $total="";

            
            $billDateHeader="";
            $billNumberHeader1="";
            $billNumberHeader2="";
            $deliveryStatusHeader="";
            $retailerCodeHeader="";
            $retailerNameHeader="";
            $salesmanNameHeader="";
            $productCodeHeader="";
            $productNameHeader1="";
            $productNameHeader2="";
            $mrpHeader="";
            $qtyHeader="";
            $netAmountHeader="";
            $taxAmountHeader="";
           
            $docRateHeader="";
            $taxTercentHeader="";
            $itemDiscountHeader="";
            
            for ($row = 1; $row <= $highestRow; ++$row) {
                $cnt++;
                for($i=1;$i<=$highestColumnIndex;$i++){
                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Bill Date"){
                        $billDateHeader= $i;
                    }
                    // echo $billDateHeader;
                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Tran Type"){
                        $deliveryStatusHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Bill Prefix"){
                        $billNumberHeader1= $i;
                    }
                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Bill No"){
                        $billNumberHeader2= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Customer Code"){
                        $retailerCodeHeader= $i;
                    }
                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()===" Customer Name"){
                        $retailerNameHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Sales Man Name"){
                        $salesmanNameHeader= $i;
                    }
                   
                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="StockNo"){
                        $productCodeHeader= $i;
                    }
                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Item Description"){
                        $productNameHeader1= $i;
                    }
                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Size"){
                        $productNameHeader2= $i;
                    }
                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Batch No."){
                        $mrpHeader= $i;
                        $total=$cnt;
                    }
                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Qty"){
                        $qtyHeader= $i;
                    }
                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Value"){
                        $netAmountHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Tax"){
                        $taxAmountHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Doc Rate"){
                        $docRateHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Tax Perc."){
                        $taxTercentHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Item - Discount"){
                        $itemDiscountHeader= $i;
                    }
                }
            }
            
            if((empty($deliveryStatusHeader) || empty($taxTercentHeader) || empty($itemDiscountHeader) || empty($docRateHeader) || empty($taxAmountHeader) || empty($salesmanNameHeader) || empty($billNumberHeader1) || empty($billNumberHeader2) || empty($billDateHeader) || empty($retailerCodeHeader) || empty($retailerNameHeader) || empty($productCodeHeader) || empty($productNameHeader1) || empty($productNameHeader2) || empty($mrpHeader) || empty($qtyHeader) || empty($netAmountHeader))){
                    echo "Source file not in correct order. Please select correct files for uploading.";
                    exit;
            }

            for ($row = ($total+1); $row <= $highestRow; ++$row) {
                $billDate = $worksheet->getCellByColumnAndRow($billDateHeader, $row)->getValue();
               
                if (strpos($billDate, '*Grand Total*') !== false) {

                } else {
                    if (strpos($billDate, '*Sub Total*') !== false) {
                    
                    }else{
                        // echo $billDate;
                        $billDate =str_replace("/","-",$billDate);
                        $date = ($billDate - 25569) * 86400;
                        $billDate=date('Y-m-d', $date);//convert date from excel data
                    
                        if($dateForUploadBills !==""){
                            if($billDate !=="Bill Date"){
                                $excelDate = date("Y-m-d", strtotime('-15 days', strtotime($billDate)));
                                if(($excelDate > $dateForUploadBills) && ($billDate !=='Bill Date')){
                                    return "Please uploads bills from date: ".$dateForUploadBills;
                                }else{
                                    return "";
                                }
                            }
                        }
                    }
                }
               
            }
        }
    }

     //Import Jockey Bills
    public function uploadJockeyExcelUploading($billFilePath){
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($billFilePath);
        $reader->setReadDataOnly(TRUE);
        $objPHPExcel = $reader->load($billFilePath);

        $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
        $highestRow = $worksheet->getHighestRow(); // e.g. 12
        $highestColumn = $worksheet->getHighestColumn(); // e.g M'

        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
        
        $cnt=0;
        $total="";
        
        $billDateHeader="";
        $billNumberHeader1="";
        $billNumberHeader2="";
        $deliveryStatusHeader="";
        $retailerCodeHeader="";
        $retailerNameHeader="";
        $salesmanNameHeader="";
        $productCodeHeader="";
        $productNameHeader1="";
        $productNameHeader2="";
        $mrpHeader="";
        $qtyHeader="";
        $netAmountHeader="";
        $taxAmountHeader="";

        $docRateHeader="";
        $taxPercentHeader="";
        $itemDiscountHeader="";
        
        for ($row = 1; $row <= $highestRow; ++$row) {
            $cnt++;
            for($i=1;$i<=$highestColumnIndex;$i++){
                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Bill Date"){
                    $billDateHeader= $i;
                }
                // echo $billDateHeader;
                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Tran Type"){
                    $deliveryStatusHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Bill Prefix"){
                    $billNumberHeader1= $i;
                }
                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Bill No"){
                    $billNumberHeader2= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Customer Code"){
                    $retailerCodeHeader= $i;
                }
                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()===" Customer Name"){
                    $retailerNameHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Sales Man Name"){
                    $salesmanNameHeader= $i;
                }
                
                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="StockNo"){
                    $productCodeHeader= $i;
                }
                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Item Description"){
                    $productNameHeader1= $i;
                }
                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Size"){
                    $productNameHeader2= $i;
                }
                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Batch No."){
                    $mrpHeader= $i;
                    $total=$cnt;
                }
                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Qty"){
                    $qtyHeader= $i;
                }
                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Value"){
                    $netAmountHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Tax"){
                    $taxAmountHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Doc Rate"){
                    $docRateHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Tax Perc."){
                    $taxPercentHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Item - Discount"){
                    $itemDiscountHeader= $i;
                }
            }
        }
        
        if((empty($deliveryStatusHeader) || empty($taxPercentHeader) || empty($itemDiscountHeader) || empty($docRateHeader) || empty($taxAmountHeader) || empty($salesmanNameHeader) || empty($billNumberHeader1) || empty($billNumberHeader2) || empty($billDateHeader) || empty($retailerCodeHeader) || empty($retailerNameHeader) || empty($productCodeHeader) || empty($productNameHeader1) || empty($productNameHeader2) || empty($mrpHeader) || empty($qtyHeader) || empty($netAmountHeader))){
            echo "Source file not in correct order. Please select correct files for uploading.";
            exit;
        }
            
        for ($row = ($total+1); $row <= $highestRow; ++$row) {
            $deliveryStatus= trim($worksheet->getCellByColumnAndRow($deliveryStatusHeader, $row)->getValue());
            $taxAmount = trim($worksheet->getCellByColumnAndRow($taxAmountHeader, $row)->getValue());
            $salesman = trim($worksheet->getCellByColumnAndRow($salesmanNameHeader, $row)->getValue());
            $billNumber1 = trim($worksheet->getCellByColumnAndRow($billNumberHeader1, $row)->getValue());
            $billNumber2 = trim($worksheet->getCellByColumnAndRow($billNumberHeader2, $row)->getValue());
            $billDate = trim($worksheet->getCellByColumnAndRow($billDateHeader, $row)->getValue());
            $retailerCode = trim($worksheet->getCellByColumnAndRow($retailerCodeHeader, $row)->getValue());
            $retailerName = trim($worksheet->getCellByColumnAndRow($retailerNameHeader, $row)->getValue());
            $productCode = trim($worksheet->getCellByColumnAndRow($productCodeHeader, $row)->getValue());
            $productName1 = trim($worksheet->getCellByColumnAndRow($productNameHeader1, $row)->getValue());
            $productName2 = trim($worksheet->getCellByColumnAndRow($productNameHeader2, $row)->getValue());
            $mrp = trim($worksheet->getCellByColumnAndRow($mrpHeader, $row)->getValue());
            $qty = trim($worksheet->getCellByColumnAndRow($qtyHeader, $row)->getValue());
            $netAmount = trim($worksheet->getCellByColumnAndRow($netAmountHeader, $row)->getValue());

            $billNumber=$billNumber1.'-'.$billNumber2;
            $productName=$productName1.'-'.$productName2;

            if (strpos($billDate, '*Grand Total*') !== false) {

            } else {
                if (strpos($billDate, '*Sub Total*') !== false) {
                
                }else{
                    $billDate =str_replace("/","-",$billDate);
                    $date = ($billDate - 25569) * 86400;
                    $billDate=date('Y-m-d', $date);
                    if(trim($deliveryStatus)=="Sales"){
                        $billExist=$this->ExcelModel->getBillByLastRecords('bills',$billNumber);
                        if(empty($billExist)){
                           
                            $readArray=array(
                                'billNo'=>$billNumber,
                                'date'=>$billDate,
                                'deliveryStatus'=>'delivered',
                                'retailerCode'=>$retailerCode,
                                'retailerName'=>$retailerName,
                                'salesman'=>$salesman,
                                'compName'=>'Jockey',
                                'insertedAt'=>date('Y-m-d H:i:sa')
                            );
                            $this->ExcelModel->insert('bills',$readArray);
                        }

                        // check retailer exist or not
                        $retailerExist=$this->ExcelModel->getInfoByCode('retailer',$retailerCode);
                        if(empty($retailerExist)){
                            $retailerData=array(
                                'name'=>$retailerName,
                                'code'=>$retailerCode,
                                'company'=>'Jockey'
                            );
                            $this->ExcelModel->insert('retailer',$retailerData);
                        }
                    }
                }
            }
        }

        $billWithCount=array();
        $cnt=1;
        $tempNumber="";
        for ($row = ($total+1); $row <= $highestRow; ++$row) {
            $billDate = trim($worksheet->getCellByColumnAndRow($billDateHeader, $row)->getValue());
            $deliveryStatus= trim($worksheet->getCellByColumnAndRow($deliveryStatusHeader, $row)->getValue());
            $billNumber1 = trim($worksheet->getCellByColumnAndRow($billNumberHeader1, $row)->getValue());
            $billNumber2 = trim($worksheet->getCellByColumnAndRow($billNumberHeader2, $row)->getValue());
            if (strpos($billDate, '*Grand Total*') !== false) {
            } else {
                if (strpos($billDate, '*Sub Total*') !== false) {
                }else{
                    if(trim($deliveryStatus)=="Sales"){
                        $billNumber=$billNumber1.'-'.$billNumber2;
                        if($billNumber != $tempNumber){
                            if($tempNumber !=""){
                                $billCount=array('billNo'=>$tempNumber,'Count'=>$cnt);
                                array_push($billWithCount,$billCount);
                                $cnt=1;
                            }
                        }else{
                            $cnt++;
                        }
                        $tempNumber=$billNumber;
                    }
                }
            }
        }
        $billCount=array('billNo'=>$tempNumber,'Count'=>$cnt);
        array_push($billWithCount,$billCount);

        $tempBillNumber="";
        $tempBillId=0;
        for ($row = ($total+1); $row <= $highestRow; ++$row) {
            $deliveryStatus= trim($worksheet->getCellByColumnAndRow($deliveryStatusHeader, $row)->getValue());
            $taxAmount = trim($worksheet->getCellByColumnAndRow($taxAmountHeader, $row)->getValue());
            $salesman = trim($worksheet->getCellByColumnAndRow($salesmanNameHeader, $row)->getValue());
            $billNumber1 = trim($worksheet->getCellByColumnAndRow($billNumberHeader1, $row)->getValue());
            $billNumber2 = trim($worksheet->getCellByColumnAndRow($billNumberHeader2, $row)->getValue());
            $billDate = trim($worksheet->getCellByColumnAndRow($billDateHeader, $row)->getValue());
            $retailerCode = trim($worksheet->getCellByColumnAndRow($retailerCodeHeader, $row)->getValue());
            $retailerName = trim($worksheet->getCellByColumnAndRow($retailerNameHeader, $row)->getValue());
            $productCode = trim($worksheet->getCellByColumnAndRow($productCodeHeader, $row)->getValue());
            $productName1 = trim($worksheet->getCellByColumnAndRow($productNameHeader1, $row)->getValue());
            $productName2 = trim($worksheet->getCellByColumnAndRow($productNameHeader2, $row)->getValue());
            $mrp = trim($worksheet->getCellByColumnAndRow($mrpHeader, $row)->getValue());
            $qty = trim($worksheet->getCellByColumnAndRow($qtyHeader, $row)->getValue());
            $netAmount = trim($worksheet->getCellByColumnAndRow($netAmountHeader, $row)->getValue());

            $docRate=trim($worksheet->getCellByColumnAndRow($docRateHeader, $row)->getValue());
            $taxPercent=trim($worksheet->getCellByColumnAndRow($taxPercentHeader, $row)->getValue());
            $itemDiscount=trim($worksheet->getCellByColumnAndRow($itemDiscountHeader, $row)->getValue());

            $billNumber=$billNumber1.'-'.$billNumber2;
            $productName=$productName1.'-'.$productName2;
           
            if (strpos($billDate, '*Grand Total*') !== false) {

            } else {
                if (strpos($billDate, '*Sub Total*') !== false) {
                }else{
                    $billDate =str_replace("/","-",$billDate);
                    $date = ($billDate - 25569) * 86400;
                    $billDate=date('Y-m-d', $date);
                   
                    $billCount=0;
                    if(trim($deliveryStatus)=="Sales"){
                        if($tempBillNumber != $billNumber){
                            if($tempBillNumber !=""){
                                $billData=$this->ExcelModel->getBillRecords('bills',$tempBillId);
                                if(!empty($billData)){
                                    $tempTotalnetAmount=round($billData[0]['netAmount']);
                                    $tempTotalpendingAmt=round($billData[0]['pendingAmt']);
                                    $updateTempArray=array(
                                        'netAmount'=>$tempTotalnetAmount,
                                        'pendingAmt'=>$tempTotalpendingAmt
                                    );
                                    $this->ExcelModel->update('bills',$updateTempArray,$tempBillId);
                                }
                            }
                        }

                        $billExist=$this->ExcelModel->getBillByLastRecords('bills',$billNumber);
                        if(!empty($billExist)){
                            foreach($billWithCount as $bl){
                                if($bl['billNo']==$billNumber){
                                    $billCount=($bl['Count']);
                                }
                            }

                            $billId=$billExist[0]['id'];
                            $billCountData=$this->ExcelModel->countBills('billsdetails',$billId);
                            if($billCountData != $billCount){
                                $readArray=array(
                                    'billId'=>$billId,
                                    'productCode'=>$productCode,
                                    'motherPackName'=>$productName,
                                    'productName'=>$productName,
                                    'mrp'=>$mrp,
                                    'sellingRate'=>round($netAmount/$qty,2),
                                    'qty'=>$qty,
                                    'netAmount'=>$netAmount,
                                    'grossRate'=>round($docRate*$qty,2),
                                    'taxPercent'=>$taxPercent,
                                    'taxAmount'=>$taxAmount,
                                    'cddbDisc'=>$itemDiscount,
                                    'taxableValue'=>($netAmount-$taxAmount)
                                );

                                $this->ExcelModel->insert('billsdetails',$readArray);
                                
                                $billData=$this->ExcelModel->getBillRecords('bills',$billId);
                                if(!empty($billData)){
                                    $totalgrossAmount=$billData[0]['grossAmount']+($netAmount-$taxAmount);
                                    $totaltaxAmount=$billData[0]['taxAmount']+$taxAmount;
                                    $totalbillNetAmount=$billData[0]['billNetAmount']+$netAmount;
                                    $totalnetAmount=$billData[0]['netAmount']+$netAmount;
                                    $totalpendingAmt=$billData[0]['pendingAmt']+$netAmount;

                                    $updateArray=array(
                                        'billNetAmount'=>$totalbillNetAmount,
                                        'grossAmount'=>$totalgrossAmount,
                                        'taxAmount'=>$totaltaxAmount,
                                        'netAmount'=>$totalnetAmount,
                                        'pendingAmt'=>$totalpendingAmt,
                                        'insertedAt'=>date('Y-m-d H:i:sa')
                                    );
                                    $this->ExcelModel->update('bills',$updateArray,$billId);
                                }
                            }else if($billCountData==0){
                                $readArray=array(
                                    'billId'=>$billId,
                                    'productCode'=>$productCode,
                                    'motherPackName'=>$productName,
                                    'productName'=>$productName,
                                    'mrp'=>$mrp,
                                    'sellingRate'=>round($netAmount/$qty,2),
                                    'qty'=>$qty,
                                    'netAmount'=>$netAmount,
                                    'grossRate'=>round($docRate*$qty,2),
                                    'taxPercent'=>$taxPercent,
                                    'taxAmount'=>$taxAmount,
                                    'cddbDisc'=>$itemDiscount,
                                    'taxableValue'=>($netAmount-$taxAmount)
                                );
                                $this->ExcelModel->insert('billsdetails',$readArray);
                                
                                $billData=$this->ExcelModel->getBillRecords('bills',$billId);
                                if(!empty($billData)){
                                    $totalgrossAmount=$billData[0]['grossAmount']+($netAmount-$taxAmount);
                                    $totaltaxAmount=$billData[0]['taxAmount']+$taxAmount;
                                    $totalbillNetAmount=$billData[0]['billNetAmount']+$netAmount;
                                    $totalnetAmount=$billData[0]['netAmount']+$netAmount;
                                    $totalpendingAmt=$billData[0]['pendingAmt']+$netAmount;

                                    $updateArray=array(
                                        'billNetAmount'=>$totalbillNetAmount,
                                        'grossAmount'=>$totalgrossAmount,
                                        'taxAmount'=>$totaltaxAmount,
                                        'netAmount'=>$totalnetAmount,
                                        'pendingAmt'=>$totalpendingAmt,
                                        'insertedAt'=>date('Y-m-d H:i:sa')
                                    );
                                    $this->ExcelModel->update('bills',$updateArray,$billId);
                                }
                            }
                            $tempBillNumber=$billNumber;
                            $tempBillId=$billId;
                        }
                    }
                }
            }
        }

        $billData=$this->ExcelModel->getBillRecords('bills',$tempBillId);
        if(!empty($billData)){
            $tempTotalnetAmount=round($billData[0]['netAmount']);
            $tempTotalpendingAmt=round($billData[0]['pendingAmt']);
            $updateTempArray=array(
                'netAmount'=>$tempTotalnetAmount,
                'pendingAmt'=>$tempTotalpendingAmt
            );
            $this->ExcelModel->update('bills',$updateTempArray,$tempBillId);
        }

        // for ($row = ($total+1); $row <= $highestRow; ++$row) {
        //     $deliveryStatus= trim($worksheet->getCellByColumnAndRow($deliveryStatusHeader, $row)->getValue());
        //     $taxAmount = trim($worksheet->getCellByColumnAndRow($taxAmountHeader, $row)->getValue());
        //     $salesman = trim($worksheet->getCellByColumnAndRow($salesmanNameHeader, $row)->getValue());
        //     $billNumber1 = trim($worksheet->getCellByColumnAndRow($billNumberHeader1, $row)->getValue());
        //     $billNumber2 = trim($worksheet->getCellByColumnAndRow($billNumberHeader2, $row)->getValue());
        //     $billDate = trim($worksheet->getCellByColumnAndRow($billDateHeader, $row)->getValue());
        //     $retailerCode = trim($worksheet->getCellByColumnAndRow($retailerCodeHeader, $row)->getValue());
        //     $retailerName = trim($worksheet->getCellByColumnAndRow($retailerNameHeader, $row)->getValue());
        //     $productCode = trim($worksheet->getCellByColumnAndRow($productCodeHeader, $row)->getValue());
        //     $productName1 = trim($worksheet->getCellByColumnAndRow($productNameHeader1, $row)->getValue());
        //     $productName2 = trim($worksheet->getCellByColumnAndRow($productNameHeader2, $row)->getValue());
        //     $mrp = trim($worksheet->getCellByColumnAndRow($mrpHeader, $row)->getValue());
        //     $qty = trim($worksheet->getCellByColumnAndRow($qtyHeader, $row)->getValue());
        //     $netAmount = trim($worksheet->getCellByColumnAndRow($netAmountHeader, $row)->getValue());

        //     $docRate=trim($worksheet->getCellByColumnAndRow($docRateHeader, $row)->getValue());
        //     $taxPercent=trim($worksheet->getCellByColumnAndRow($taxPercentHeader, $row)->getValue());
        //     $itemDiscount=trim($worksheet->getCellByColumnAndRow($itemDiscountHeader, $row)->getValue());

        //     $billNumber=$billNumber1.'-'.$billNumber2;
        //     $productName=$productName1.'-'.$productName2;

        //     if (strpos($billDate, '*Grand Total*') !== false) {

        //     } else {
        //         if (strpos($billDate, '*Sub Total*') !== false) {
                
        //         }else{
        //             $billDate =str_replace("/","-",$billDate);
        //             $date = ($billDate - 25569) * 86400;
        //             $billDate=date('Y-m-d', $date);
        //             if(trim($deliveryStatus)=="Sales"){
        //                 $billExist=$this->ExcelModel->getBillByLastRecords('bills',$billNumber);
        //                 if(!empty($billExist)){
        //                     $billId=$billExist[0]['id'];
        //                     $selRate=round($netAmount/$qty,2);
        //                     $netAmount=round($netAmount,2);
        //                     $checkItemDetails=$this->ExcelModel->getAllItcBillDetails('billsdetails',$billId,$productCode,$productName,$selRate,$qty,$netAmount);
        //                     if(empty($checkItemDetails)){
        //                         $readArray=array(
        //                             'billId'=>$billId,
        //                             'productCode'=>$productCode,
        //                             'motherPackName'=>$productName,
        //                             'productName'=>$productName,
        //                             'mrp'=>$mrp,
        //                             'sellingRate'=>round($netAmount/$qty,2),
        //                             'qty'=>$qty,
        //                             'netAmount'=>$netAmount,
        //                             'grossRate'=>round($docRate*$qty,2),
        //                             'taxPercent'=>$taxPercent,
        //                             'taxAmount'=>$taxAmount,
        //                             'cddbDisc'=>$itemDiscount,
        //                             'taxableValue'=>($netAmount-$taxAmount)
        //                         );
        //                         $this->ExcelModel->insert('billsdetails',$readArray);
                                
        //                         $billData=$this->ExcelModel->getBillRecords('bills',$billId);
        //                         if(!empty($billData)){
        //                             $totalgrossAmount=$billData[0]['grossAmount']+($netAmount-$taxAmount);
        //                             $totaltaxAmount=$billData[0]['taxAmount']+$taxAmount;
        //                             $totalbillNetAmount=$billData[0]['billNetAmount']+$netAmount;
        //                             $totalnetAmount=$billData[0]['netAmount']+$netAmount;
        //                             $totalpendingAmt=$billData[0]['pendingAmt']+$netAmount;
        //                             $updateArray=array(
        //                                 'billNetAmount'=>$totalbillNetAmount,
        //                                 'grossAmount'=>$totalgrossAmount,
        //                                 'taxAmount'=>$totaltaxAmount,
        //                                 'netAmount'=>round($totalnetAmount),
        //                                 'pendingAmt'=>round($totalpendingAmt)
        //                             );
        //                             $this->ExcelModel->update('bills',$updateArray,$billId);
        //                         }
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }

        $updateData=array('status'=>2);
        $this->ExcelModel->updateByCompany('cron_settings',$updateData,'Jockey');
    }

    //Import Jockey Bills
    public function uploadJockeyRetailerExcelUploading($billFilePath){
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($billFilePath);
        $reader->setReadDataOnly(TRUE);
        $objPHPExcel = $reader->load($billFilePath);

        $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
        $highestRow = $worksheet->getHighestRow(); // e.g. 12
        $highestColumn = $worksheet->getHighestColumn(); // e.g M'

        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
        
        $cnt=0;
        $total="";
        
      
        $retailerCodeHeader="";
        $retailerNameHeader="";
        $salesmanNameHeader="";
        $routeHeader="";
        
        for ($row = 1; $row <= $highestRow; ++$row) {
            $cnt++;
            for($i=1;$i<=$highestColumnIndex;$i++){
               
                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="UID"){
                    $retailerCodeHeader= $i;
                }
                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="PARTY NAME"){
                    $retailerNameHeader= $i;
                    $total=$cnt;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="DSO"){
                    $salesmanNameHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="BEAT"){
                    $routeHeader= $i;
                }
                
            }
        }
        
        if((empty($retailerCodeHeader) || empty($retailerNameHeader) || empty($salesmanNameHeader) || empty($routeHeader))){
                echo "Source file not in correct order. Please select correct files for uploading.";
                exit;
        }
            
        for ($row = ($total+1); $row <= $highestRow; ++$row) {
            $retailerCode = trim($worksheet->getCellByColumnAndRow($retailerCodeHeader, $row)->getValue());
            $retailerName = trim($worksheet->getCellByColumnAndRow($retailerNameHeader, $row)->getValue());
            $salesmanName = trim($worksheet->getCellByColumnAndRow($salesmanNameHeader, $row)->getValue());
            $route = trim($worksheet->getCellByColumnAndRow($routeHeader, $row)->getValue());
            
            // check retailer exist or not
            $retailerExist=$this->ExcelModel->getInfoByCode('retailer',$retailerCode);
            if(empty($retailerExist)){
                $retailerData=array(
                    'name'=>$retailerName,
                    'code'=>$retailerCode,
                    'company'=>'Jockey'
                );
                $this->ExcelModel->insert('retailer',$retailerData);
            }
            // check route exist or not
            $routeExist=$this->ExcelModel->getInfoByName('route',$route,'Jockey');
            if(empty($routeExist)){
                $routeData=array(
                    'name'=>str_replace(",","-",$route),
                    'company'=>'Jockey'
                );
                $this->ExcelModel->insert('route',$routeData);
            }
          
            $billsData=array(
                'salesman'=>$salesmanName,
                'routeName'=>$route
            );
            $this->ExcelModel->updateBillData('bills',$billsData,$retailerName,$retailerCode);
           
        }

        $updateData=array('status'=>2);
        $this->ExcelModel->updateByCompany('cron_settings',$updateData,'Jockey');
    }


    //check date for Parle bills data uploading
    public function checkParleExcelUploading($fileName,$fileType,$fileTempName,$dateForUploadBills){
        $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        if(isset($fileName) && in_array($fileType, $file_mimes)) {
            $arr_file = explode('.', $fileName); //get file
            $extension = end($arr_file); //get file extension
            // select spreadsheet reader depends on file extension
            if($extension == 'csv') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else if ($extension =='xlsx'){
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
            $total="";

            $billNumberHeader="";
            $billDateHeader="";
            $deliveryStatusHeader="";
            $salesmanHeader="";
            $retailerCodeHeader="";
            $retailerNameHeader="";
            $cashDiscountHeader="";
            $creditAdjustmentHeader="";
            $netAmountHeader="";
            $grossAmountHeader="";
            $taxAmountHeader="";
            
            for ($row = 1; $row <= $highestRow; ++$row) {
                $cnt++;
                for($i=1;$i<=$highestColumnIndex;$i++){
                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Bill Number"){
                        $billNumberHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Bill Date"){
                        $billDateHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Delivery Status"){
                        $deliveryStatusHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Salesman"){
                        $salesmanHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Retailer Code"){
                        $retailerCodeHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Retailer Name"){
                        $retailerNameHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Cash Discount"){
                        $cashDiscountHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Credit Adjustment"){
                        $creditAdjustmentHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Net Amount"){
                        $netAmountHeader= $i;
                        $total=$cnt;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Gross Amount"){
                        $grossAmountHeader= $i;
                    }
    
                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Tax Amount"){
                        $taxAmountHeader= $i;
                    }
                }
            }
            if((empty($billNumberHeader) || empty($taxAmountHeader) || empty($grossAmountHeader) || empty($billDateHeader) || empty($deliveryStatusHeader) || empty($salesmanHeader) || empty($retailerCodeHeader) || empty($retailerNameHeader) || empty($cashDiscountHeader) || empty($creditAdjustmentHeader) || empty($netAmountHeader))){
                    echo "Source file not in correct order. Please select correct files for uploading.";
                    exit;
            }

            for ($row = ($total+2); $row <= $highestRow; ++$row) {

                $billNumber = $worksheet->getCellByColumnAndRow($billNumberHeader, $row)->getValue();
                $billDate = $worksheet->getCellByColumnAndRow($billDateHeader, $row)->getValue();
                $deliveryStatus = $worksheet->getCellByColumnAndRow($deliveryStatusHeader, $row)->getValue();
                $salesman = $worksheet->getCellByColumnAndRow($salesmanHeader, $row)->getValue();
                $retailerCode = $worksheet->getCellByColumnAndRow($retailerCodeHeader, $row)->getValue();
                $retailerName = $worksheet->getCellByColumnAndRow($retailerNameHeader, $row)->getValue();
                $cashDiscount = $worksheet->getCellByColumnAndRow($cashDiscountHeader, $row)->getValue();
                $creditAdjustment = $worksheet->getCellByColumnAndRow($creditAdjustmentHeader, $row)->getValue();
                $netAmount = $worksheet->getCellByColumnAndRow($netAmountHeader, $row)->getValue();
               
                if($dateForUploadBills !==""){
                    if($billDate !=="Bill Date"){
                        $excelDate = date("Y-m-d", strtotime('-15 days', strtotime($billDate)));
                        if(($excelDate > $dateForUploadBills) && ($billDate !=='Bill Date')){
                            return "Please uploads bills from date: ".$dateForUploadBills;
                        }else{
                            return "";
                        }
                    }
                }

            }
        }
    }

    //Import Parle Bills
    public function uploadParleExcelUploading($billFilePath){
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($billFilePath);
        $reader->setReadDataOnly(TRUE);
        $objPHPExcel = $reader->load($billFilePath);

        $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
        $highestRow = $worksheet->getHighestRow(); // e.g. 12
        $highestColumn = $worksheet->getHighestColumn(); // e.g M'

        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
        
        $cnt=0;
        $total="";

        $billNumberHeader="";
        $billDateHeader="";
        $deliveryStatusHeader="";
        $salesmanHeader="";
        $retailerCodeHeader="";
        $retailerNameHeader="";
        $cashDiscountHeader="";
        $creditAdjustmentHeader="";
        $netAmountHeader="";
        $grossAmountHeader="";
        $taxAmountHeader="";
        
        for ($row = 1; $row <= $highestRow; ++$row) {
            $cnt++;
            for($i=1;$i<=$highestColumnIndex;$i++){
                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Bill Number"){
                    $billNumberHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Bill Date"){
                    $billDateHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Delivery Status"){
                    $deliveryStatusHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Salesman"){
                    $salesmanHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Retailer Code"){
                    $retailerCodeHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Retailer Name"){
                    $retailerNameHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Cash Discount"){
                    $cashDiscountHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Credit Adjustment"){
                    $creditAdjustmentHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Net Amount"){
                    $netAmountHeader= $i;
                    $total=$cnt;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Gross Amount"){
                    $grossAmountHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Tax Amount"){
                    $taxAmountHeader= $i;
                }
            }
        }
        if((empty($billNumberHeader) || empty($taxAmountHeader) || empty($grossAmountHeader) || empty($billDateHeader) || empty($deliveryStatusHeader) || empty($salesmanHeader) || empty($retailerCodeHeader) || empty($retailerNameHeader) || empty($cashDiscountHeader) || empty($creditAdjustmentHeader) || empty($netAmountHeader))){
                echo "Source file not in correct order. Please select correct files for uploading.";
                exit;
        }

        for ($row = ($total+2); $row <= $highestRow; ++$row) {

            $billNumber = trim($worksheet->getCellByColumnAndRow($billNumberHeader, $row)->getValue());
            $billDate = trim($worksheet->getCellByColumnAndRow($billDateHeader, $row)->getValue());
            $deliveryStatus = trim($worksheet->getCellByColumnAndRow($deliveryStatusHeader, $row)->getValue());
            $salesman = trim($worksheet->getCellByColumnAndRow($salesmanHeader, $row)->getValue());
            $retailerCode = trim($worksheet->getCellByColumnAndRow($retailerCodeHeader, $row)->getValue());
            $retailerName = trim($worksheet->getCellByColumnAndRow($retailerNameHeader, $row)->getValue());
            $cashDiscount = trim($worksheet->getCellByColumnAndRow($cashDiscountHeader, $row)->getValue());
            $creditAdjustment = trim($worksheet->getCellByColumnAndRow($creditAdjustmentHeader, $row)->getValue());
            $netAmount = trim($worksheet->getCellByColumnAndRow($netAmountHeader, $row)->getValue());
            $grossAmount = trim($worksheet->getCellByColumnAndRow($grossAmountHeader, $row)->getValue());
            $taxAmount = trim($worksheet->getCellByColumnAndRow($taxAmountHeader, $row)->getValue());
            

            if(trim($deliveryStatus)=="Delivered"){
                $billExist=$this->ExcelModel->getBillByLastRecords('bills',$billNumber);
                if(empty($billExist)){
                    $readArray=array(
                        'billNo'=>$billNumber,
                        'date'=>$billDate,
                        'deliveryStatus'=>$deliveryStatus,
                        'retailerCode'=>$retailerCode,
                        'retailerName'=>$retailerName,
                        'salesman'=>$salesman,
                        'grossAmount'=>$grossAmount,
                        'taxAmount'=>$taxAmount,
                        'billNetAmount'=>round($netAmount),
                        'cashDiscount'=>$cashDiscount,
                        'creditAdjustment'=>round($creditAdjustment),
                        'netAmount'=>round($netAmount),
                        'pendingAmt'=>round($netAmount),
                        'compName'=>'Parle',
                        'insertedAt'=>date('Y-m-d H:i:sa')
                    );
                    $this->ExcelModel->insert('bills',$readArray);
                }
            }

        }

        $updateData=array('status'=>2);
        $this->ExcelModel->updateByCompany('cron_settings',$updateData,'Parle');
    }

    //Import Parle Bills details
    public function uploadParleBillDetailsExcelUploading($billFilePath){
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($billFilePath);
        $reader->setReadDataOnly(TRUE);
        $objPHPExcel = $reader->load($billFilePath);

        $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
        $highestRow = $worksheet->getHighestRow(); // e.g. 12
        $highestColumn = $worksheet->getHighestColumn(); // e.g M'

        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
        
       
        for ($row = 3; $row <= $highestRow; ++$row) {
            $billNo = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
            $productCode = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
            $productName = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
            $mrp = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
            $sellingRate = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
            $qty = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
            $grossAmount = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
            $schemeDiscount = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
            $distributorDiscount = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
            $cashDiscount = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
            $taxAmount = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());
            $netAmount = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
            
            $billExist=$this->ExcelModel->getBillByLastRecords('bills',$billNo);
            if(!empty($billExist)){
                $readArray=array(
                    'billId'=>$billExist[0]['id'],
                    'productCode'=>$productCode,
                    'motherPackName'=>$productName,
                    'productName'=>$productName,
                    'mrp'=>$mrp,
                    'sellingRate'=>$sellingRate,
                    'qty'=>$qty,
                    'netAmount'=>$netAmount,
                    'purchaseRateWithoutTax'=>$grossAmount,
                    'purchaseRateWithTax'=>$netAmount,
                    'grossRate'=>$grossAmount,
                    'schemaDisc'=>$schemeDiscount,
                    'cddbDisc'=>($cashDiscount+$distributorDiscount),
                    'taxableValue'=>$taxAmount
                );
                $this->ExcelModel->insert('billsdetails',$readArray);
            }
        }

        $updateData=array('status'=>2);
        $this->ExcelModel->updateByCompany('cron_settings',$updateData,'Parle');
    }

    //Import Parle Bills details
    public function uploadParleRetailerExcelUploading($billFilePath){
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($billFilePath);
        $reader->setReadDataOnly(TRUE);
        $objPHPExcel = $reader->load($billFilePath);

        $worksheet = $objPHPExcel->getSheet(3);//Get sheet 
        $highestRow = $worksheet->getHighestRow(); // e.g. 12
        $highestColumn = $worksheet->getHighestColumn(); // e.g M'

        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
        for ($row = 2; $row <= $highestRow; ++$row) {
            $salesmanCode = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
            $salesmanName = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
            $routeCode = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
            $routeName = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
            $retailerCode = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
            $retailerName = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());

            // echo "hy ".$salesmanCode.' '.$salesmanName.' '.$routeCode.' '.$routeName.' '.$retailerCode.' '.$retailerName;
            // exit;
            if(trim($retailerName)){
                // check retailer exist or not
                $retailerExist=$this->ExcelModel->getInfoByCode('retailer',$retailerCode);
                if(empty($retailerExist)){
                    $retailerData=array(
                        'name'=>$retailerName,
                        'code'=>$retailerCode,
                        'company'=>'Parle'
                    );
                    $this->ExcelModel->insert('retailer',$retailerData);
                }else{
                    $retailerData=array(
                        'name'=>$retailerName,
                        'company'=>'Parle'
                    );
                    $this->ExcelModel->update('retailer',$retailerData,$retailerExist[0]['id']);
                }
            }
            
            if(trim($routeName)){
                // check route exist or not
                $routeExist=$this->ExcelModel->getInfoByCode('route',$routeCode);
                if(empty($routeExist)){
                    $routeData=array(
                        'name'=>str_replace(",","-",$routeName),
                        'code'=>$routeCode,
                        'company'=>'Parle'
                    );
                    $this->ExcelModel->insert('route',$routeData);
                }else{
                    $routeData=array(
                        'name'=>str_replace(",","-",$routeName),
                        'company'=>'Parle'
                    );
                    $this->ExcelModel->update('route',$routeData,$routeExist[0]['id']);
                }
            }
        }
    }

    //Import ITC Bills for SIFI bills
    public function itcExcelUploadingWithSifi($billFilePath){
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($billFilePath);
        $reader->setReadDataOnly(TRUE);
        $objPHPExcel = $reader->load($billFilePath);

        $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
        $highestRow = $worksheet->getHighestRow(); // e.g. 12
        $highestColumn = $worksheet->getHighestColumn(); // e.g M'

        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7

        $cnt=0;
        $total="";
        
        $billNumberHeader="";
        $billDateHeader="";
        $retailerCodeHeader="";
        $retailerNameHeader="";
        $netValueHeader="";
        $beatHeader="";
        $salesmanHeader="";
        $salesTaxHeader="";
        $gstHeader="";
        $itemCodeHeader="";
        $itemNameHeader="";
        $quantityHeader="";
        $salesPriceHeader="";
        $invoiceUomHeader="";
        $itemSaleTaxHeader="";
        $discountPercentHeader="";
        $itemTotalHeader="";
        $itemSalesTaxValueHeader="";
        
        $creditValueHeader="";

        for ($row = 1; $row <= $highestRow; ++$row) {
            $cnt++;
            for($i=1;$i<=$highestColumnIndex;$i++){
                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="InvoiceID"){
                    $billNumberHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Date"){
                    $billDateHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="CustomerID"){
                    $retailerCodeHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Customer"){
                    $retailerNameHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Net Value"){
                    $netValueHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Beat"){
                    $beatHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Salesman"){
                    $salesmanHeader= $i;
                }
                

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Total SalesTax Value"){
                    $salesTaxHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="GSTIN OF Outlet"){
                    $gstHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Item Code"){
                    $itemCodeHeader= $i;
                    $total=$cnt;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Item Name"){
                    $itemNameHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Invoice Qty"){
                    $quantityHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Sales Price"){
                    $salesPriceHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Invoice UOM"){
                    $invoiceUomHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Sale Tax"){
                    $itemSaleTaxHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Discount"){
                    $discountPercentHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Total"){
                    $itemTotalHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Sales Tax Value"){
                    $itemSalesTaxValueHeader= $i;
                }
                
                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Adjusted Amount"){
                    $creditValueHeader= $i;
                }
            }
        }

        // echo $billNumberHeader;exit;
        if((empty($billNumberHeader) || empty($creditValueHeader) || empty($billDateHeader) || empty($retailerCodeHeader) || empty($retailerNameHeader) || empty($netValueHeader) || empty($beatHeader) || empty($salesmanHeader) || empty($salesTaxHeader) || empty($gstHeader) || empty($itemCodeHeader) || empty($itemNameHeader) || empty($quantityHeader) || empty($salesPriceHeader) || empty($invoiceUomHeader) || empty($itemSaleTaxHeader) || empty($discountPercentHeader) || empty($itemTotalHeader) || empty($itemSalesTaxValueHeader))){
            echo "Source file not in correct order. Please select correct files for uploading.";
            exit;
        }

        for ($row = ($total+1); $row <= $highestRow; ++$row) {
            //bill no
            $billNumber = trim($worksheet->getCellByColumnAndRow($billNumberHeader, $row)->getValue());

            // $billNumber=explode('/',$billNumber);
            // if(!empty($billNumber)){
            //     $billNo=$billNumber[count($billNumber)-1];
            //     if(strlen($billNo)==1){
            //         $billNumber[count($billNumber)-1]='0000'.$billNo;
            //     }else if(strlen($billNo)==2){
            //         $billNumber[count($billNumber)-1]='000'.$billNo;
            //     }else if(strlen($billNo)==3){
            //         $billNumber[count($billNumber)-1]='00'.$billNo;
            //     }else if(strlen($billNo)==4){
            //         $billNumber[count($billNumber)-1]='0'.$billNo;
            //     }else{
            //         $billNumber[count($billNumber)-1]=$billNo;
            //     }
            //     $billNumber=implode('/',$billNumber);
            // }

            // $sortbillNumber=explode('-',$billNumber);
            // $sortBillNo="";
            // if(!empty($sortbillNumber)){
            //     $sortBillNo=$sortbillNumber[count($sortbillNumber)-1];
            //     $sortBillNo=str_replace("/","",$sortBillNo);
            // }else{
            //     $sortBillNo=$billNumber;
            // }

            $billDate = trim($worksheet->getCellByColumnAndRow($billDateHeader, $row)->getValue());
            // echo $billDate;exit;
            $retailerCode = trim($worksheet->getCellByColumnAndRow($retailerCodeHeader, $row)->getValue());
            $retailerName = trim($worksheet->getCellByColumnAndRow($retailerNameHeader, $row)->getValue());
            $netAmount = trim($worksheet->getCellByColumnAndRow($netValueHeader, $row)->getValue());
            $route=trim($worksheet->getCellByColumnAndRow($beatHeader, $row)->getValue());
            $salesman = trim($worksheet->getCellByColumnAndRow($salesmanHeader, $row)->getValue());
            $tax=trim($worksheet->getCellByColumnAndRow($salesTaxHeader, $row)->getValue());
            $retailerGstNo=trim($worksheet->getCellByColumnAndRow($gstHeader, $row)->getValue());
             
            $creditAdjValue=trim($worksheet->getCellByColumnAndRow($creditValueHeader, $row)->getValue());
             
            //calculations
            $billgrossAmount=$netAmount-round($tax,2);
            
            $excelDate="";
            
            if($billNumber !==''){
                if($billNumber !=='GrandTotal:'){
                    if(!empty($billDate) && $billDate !=='Bill Date'){
                        $billExist=$this->ExcelModel->getBillByLastRecords('bills',$billNumber);
                        if(empty($billExist)){
                            $billDate =str_replace("/","-",$billDate);
                            $date = ($billDate - 25569) * 86400;
                            $billDate=date('Y-m-d', $date);//convert date from excel data

                            $arrayRes=array(
                                'date'=>$billDate,
                                'billNo'=>$billNumber,
                                'deliveryStatus'=>'delivered',
                                'retailerCode'=>$retailerCode,
                                'retailerName'=>$retailerName,
                                'grossAmount'=>$billgrossAmount,
                                'taxAmount'=>($tax),
                                'billNetAmount'=>round($netAmount,2),
                                'creditAdjustment'=>round($creditAdjValue,2),
                                'netAmount'=>round($netAmount-$creditAdjValue),
                                'pendingAmt'=>round($netAmount-$creditAdjValue),
                                'compName'=>'ITC',
                                'routeName'=>$route,
                                'salesman'=>$salesman,
                                'insertedAt'=>date('Y-m-d H:i:sa')
                            );
                            $this->ExcelModel->insert('bills',$arrayRes);

                            //latest inserted id
                            $insert_id = $this->db->insert_id();
                            $salesmanCode="";
                            $salesmanExist=$this->ExcelModel->getSalesmanCount('bills',$salesman);
                            if(!empty($salesmanExist)){
                                $salesmanCode=$salesmanExist[0]['salesmanCode'];
                            }else{
                                $salesmanCode='ITC'.$insert_id;
                            }
                            $updateSalesmanCode=array('salesmanCode'=>$salesmanCode);
                            $this->ExcelModel->update('bills',$updateSalesmanCode,$insert_id);
                        }
        
                        $retailerDetails=$this->ExcelModel->retailerInfo('retailer',$retailerName,$retailerCode);
                        if(empty($retailerDetails)){
                            $retailerData=array(
                                'code'=>$retailerCode,
                                'name'=>$retailerName,
                                'gstIn'=>$retailerGstNo,
                                'company'=>'ITC'
                            );
                            $this->ExcelModel->insert('retailer',$retailerData);
                        }
        
                        $routeDetails=$this->ExcelModel->routeInfoByName('route',$route);
                        $routeDetailsInfo=$this->ExcelModel->getdata('route');
                        if(empty($routeDetails)){
                            $routeData=array(
                                'code'=>'NOROUTE'.count($routeDetailsInfo),
                                'name'=>$route,
                                'company'=>'ITC'
                            );
                            $this->ExcelModel->insert('route',$routeData);
                        }
                    }
                }
            }
        }

        $billId=0;
        //upload bill details value
        for ($row = ($total+1); $row <= $highestRow; ++$row) {
            $billDate = $worksheet->getCellByColumnAndRow($billDateHeader, $row)->getValue();
            $billNo = $worksheet->getCellByColumnAndRow($billNumberHeader, $row)->getValue();

            if($billNo !=="GrandTotal:"){
                if($billNo !==""){
                    // bill item 
                    $productCode=trim($worksheet->getCellByColumnAndRow($itemCodeHeader, $row)->getValue());
                    if($productCode !=="GrandTotal:"){
                        $productName=trim($worksheet->getCellByColumnAndRow($itemNameHeader, $row)->getValue());
                        $quantity=trim($worksheet->getCellByColumnAndRow($quantityHeader, $row)->getValue());
                        $mrp=trim($worksheet->getCellByColumnAndRow($salesPriceHeader, $row)->getValue());
                        $sellingPrice=trim($worksheet->getCellByColumnAndRow($salesPriceHeader, $row)->getValue());
                        $sellingUnit=trim($worksheet->getCellByColumnAndRow($invoiceUomHeader, $row)->getValue());
                        $salesTaxPercent=trim($worksheet->getCellByColumnAndRow($itemSaleTaxHeader, $row)->getValue());
                        $discountPercent=trim($worksheet->getCellByColumnAndRow($discountPercentHeader, $row)->getValue());
                        $netitemNetAmount=trim($worksheet->getCellByColumnAndRow($itemTotalHeader, $row)->getValue());
                        $itemNetAmount=trim($worksheet->getCellByColumnAndRow($itemTotalHeader, $row)->getValue());
                        $itemNetAmount=($itemNetAmount);
                        $itemTax=trim($worksheet->getCellByColumnAndRow($itemSalesTaxValueHeader, $row)->getValue());
                        
                        //calculations
                        $grossAmount=(round($itemNetAmount,2)-round($itemTax,2));
                        $schemeDiscount=($quantity*round($sellingPrice,2)*round($discountPercent,2));
                        $distributorDiscount=($quantity*round($sellingPrice,2)*(1-round($discountPercent,2))-round($grossAmount,2));
                        
                        if($distributorDiscount<1){
                            $distributorDiscount=0;
                        }
                        $billExist=$this->ExcelModel->getBillByLastRecords('bills',$billNo);
                        if(!empty($billExist)){
                            $billId=$billExist[0]['id'];
                            // echo ''.$billId.' '.$productCode.'  '.$sellingPrice.' '.$quantity.' '.$netitemNetAmount.' <br>';
                            $checkItemDetails=$this->ExcelModel->getAllItcBillDetails('billsdetails',$billId,$productCode,$productName,$sellingPrice,$quantity,$itemNetAmount);
                            // echo count($checkItemDetails).' <br>';
                            // print_r($checkItemDetails);exit;
                            if(empty($checkItemDetails)){
                                $billDetailData=array(
                                    'billId'=>$billId,
                                    'productCode'=>$productCode,
                                    'productName'=>$productName,
                                    'qty'=>$quantity,
                                    'sellingRate'=>$sellingPrice,
                                    'sellingUnit'=>$sellingUnit,
                                    'netAmount'=>$itemNetAmount,
                                    'grossRate'=>$grossAmount,
                                    'schemaDisc'=>$schemeDiscount,
                                    'cddbDisc'=>$distributorDiscount,
                                    'taxAmount'=>$itemTax,
                                    'taxPercent'=>($salesTaxPercent*100)
                                );
                                $this->ExcelModel->insert('billsdetails',$billDetailData);
                            }
                        }
                    }
                }
            }
        }
        $updateData=array('status'=>2);
        $this->ExcelModel->updateByCompany('cron_settings',$updateData,'ITC');

    }
    
    //check date for ITC bills data uploading
    public function checkItcExcelUploadingWithSifi($fileName,$fileType,$fileTempName,$dateForUploadBills){
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
            $total="";
            
            $billNumberHeader="";
            $billDateHeader="";
            $retailerCodeHeader="";
            $retailerNameHeader="";
            $netValueHeader="";
            $beatHeader="";
            $salesmanHeader="";
            $salesTaxHeader="";
            $gstHeader="";
            $itemCodeHeader="";
            $itemNameHeader="";
            $quantityHeader="";
            $salesPriceHeader="";
            $invoiceUomHeader="";
            $itemSaleTaxHeader="";
            $discountPercentHeader="";
            $itemTotalHeader="";
            $itemSalesTaxValueHeader="";

            $itcFileUOMType="";
            $itcFileUOMValue="";
            
            $creditValueHeader="";
            $uom="";

            $uomTotal="";
            $uomRow="";

            for ($row = 1; $row <= $highestRow; ++$row) {
                $cnt++;
                for($i=1;$i<=$highestColumnIndex;$i++){
                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="UOM:"){
                        $itcFileUOMType=$i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="UOM2"){
                        $itcFileUOMValue=$i;
                        $uomTotal=$i;
                        $uomRow=$cnt;
                    }
                }
            }

            if((empty($itcFileUOMType) || empty($itcFileUOMValue))){
                echo "Source file not having UOM type as UOM2. Please select correct files for uploading.";
                exit;
            }

            $itcFileUOM="";
            for ($row = ($uomRow); $row <= $uomRow; ++$row) {
                if(!empty($itcFileUOMValue)){
                    $itcFileUOM = trim($worksheet->getCellByColumnAndRow($itcFileUOMValue, $row)->getValue());
                    if($itcFileUOM != "UOM2"){
                        echo "UOM type is wrong. Please upload UOM2 type file";
                        exit;
                    }
                }  
            }
           
            //for checking file columns are correct or not
            for ($row = 1; $row <= $highestRow; ++$row) {
                $cnt++;
                for($i=1;$i<=$highestColumnIndex;$i++){

                    if(trim($worksheet->getCellByColumnAndRow($i, $row)->getValue())==="InvoiceID"){
                        $billNumberHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Date"){
                        $billDateHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="CustomerID"){
                        $retailerCodeHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Customer"){
                        $retailerNameHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Net Value"){
                        $netValueHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Beat"){
                        $beatHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Salesman"){
                        $salesmanHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Total SalesTax Value"){
                        $salesTaxHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="GSTIN OF Outlet"){
                        $gstHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Item Code"){
                        $itemCodeHeader= $i;
                        $total=$cnt;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Item Name"){
                        $itemNameHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Invoice Qty"){
                        $quantityHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Sales Price"){
                        $salesPriceHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Invoice UOM"){
                        $invoiceUomHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Sale Tax"){
                        $itemSaleTaxHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Discount"){
                        $discountPercentHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Total"){
                        $itemTotalHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Sales Tax Value"){
                        $itemSalesTaxValueHeader= $i;
                    }
                    
                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Adjusted Amount"){
                        $creditValueHeader= $i;
                    }
                }
            }

            if((empty($billNumberHeader) || empty($creditValueHeader) || empty($billDateHeader) || empty($retailerCodeHeader) || empty($retailerNameHeader) || empty($netValueHeader) || empty($beatHeader) || empty($salesmanHeader) || empty($salesTaxHeader) || empty($gstHeader) || empty($itemCodeHeader) || empty($itemNameHeader) || empty($quantityHeader) || empty($salesPriceHeader) || empty($invoiceUomHeader) || empty($itemSaleTaxHeader) || empty($discountPercentHeader) || empty($itemTotalHeader) || empty($itemSalesTaxValueHeader))){
                echo "Source file not in correct order. Please select correct files for uploading.";
                exit;
            }
        }
    }

    //check date for McCain bills data uploading
    public function checkMcCainExcelUploadingWithSifi($fileName,$fileType,$fileTempName,$dateForUploadBills){
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
            $total="";
            
            $routeCodeHeader="";
            $routeNameHeader="";
            $salesmanCodeHeader="";
            $salesmanNameHeader="";
            $retailerCodeHeader="";
            $retailerNameHeader="";

            $transactionTypeHeader="";
            $billDateHeader="";
            $billNumberHeader="";
            $productCodeHeader="";
            $productNameHeader="";
            $qtyHeader="";

            $mrpHeader="";
            $sellingPriceHeader="";
            $grossAmountHeader="";
            $schemeDescountHeader="";
            $dbDescountHeader="";
            $taxableValueHeader="";

            $cgstHeader="";
            $sgstHeader="";
            $igstHeader="";
            $netAmountHeader="";
            $billNetAmountHeader="";
            $creditAdjustmentHeader="";

            for ($row = 1; $row <= $highestRow; ++$row) {
                $cnt++;
                for($i=1;$i<=$highestColumnIndex;$i++){
                    if(trim($worksheet->getCellByColumnAndRow($i, $row)->getValue())==="Route Code"){
                        $routeCodeHeader= $i;
                        $total=$cnt;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Route Name"){
                        $routeNameHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Seller Code"){
                        $salesmanCodeHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Seller Name"){
                        $salesmanNameHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Customer Code"){
                        $retailerCodeHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Customer Name"){
                        $retailerNameHeader= $i;
                    }
                    //////////////

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Transaction Type"){
                        $transactionTypeHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Invoice Date"){
                        $billDateHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Invoice Id"){
                        $billNumberHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="SKU Code"){
                        $productCodeHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="SKU Name"){
                        $productNameHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Qty Pieces"){
                        $qtyHeader= $i;
                    }
                    //////////

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="MRP"){
                        $mrpHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Price Pieces"){
                        $sellingPriceHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Gross Value"){
                        $grossAmountHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Scheme Discount"){
                        $schemeDescountHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="DB Discount Bill Level"){
                        $dbDescountHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Taxable Value"){
                        $taxableValueHeader= $i;
                    }
                    /////////
                    
                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="CGST Amount"){
                        $cgstHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="SGST Amount"){
                        $sgstHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="IGST Amount"){
                        $igstHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Net Amount"){
                        $netAmountHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Invoice Net Amount"){
                        $billNetAmountHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Credit Note"){
                        $creditAdjustmentHeader= $i;
                    }
                }
            }

            if((empty($routeCodeHeader) || empty($routeNameHeader) || empty($salesmanCodeHeader) || empty($salesmanNameHeader) || empty($retailerCodeHeader) || empty($retailerNameHeader))){
                echo "Source file not in correct order. Please select correct files for uploading.";
                exit;
            }

            if((empty($transactionTypeHeader) || empty($billDateHeader) || empty($billNumberHeader) || empty($productCodeHeader) || empty($productNameHeader) || empty($qtyHeader))){
                echo "Source file not in correct order. Please select correct files for uploading.";
                exit;
            }

            if((empty($mrpHeader) || empty($sellingPriceHeader) || empty($grossAmountHeader) || empty($schemeDescountHeader) || empty($dbDescountHeader) || empty($taxableValueHeader))){
                echo "Source file not in correct order. Please select correct files for uploading.";
                exit;
            }

            if((empty($cgstHeader) || empty($sgstHeader) || empty($igstHeader) || empty($netAmountHeader) || empty($billNetAmountHeader) || empty($creditAdjustmentHeader))){
                echo "Source file not in correct order. Please select correct files for uploading.";
                exit;
            }

            $billWithCount=array();
            $cnt=1;
            $tempNumber="";
            for ($row = ($total+1); $row <= $highestRow; ++$row) {
                $billDate = trim($worksheet->getCellByColumnAndRow($billDateHeader, $row)->getValue());
                $billNumber = trim($worksheet->getCellByColumnAndRow($billNumberHeader, $row)->getValue());

                if(trim($billNumber) != trim($tempNumber)){
                    if($tempNumber !=""){
                        $billCount=array('billNo'=>$tempNumber,'Count'=>$cnt);
                        array_push($billWithCount,$billCount);
                        $cnt=1;
                    }
                }else{
                    $cnt++;
                }
                $tempNumber=$billNumber;
            }
            $billCount=array('billNo'=>$tempNumber,'Count'=>$cnt);
            array_push($billWithCount,$billCount);

            $cm = array_column($billWithCount, 'billNo');
            if($cm != array_unique($cm)){
                echo 'Please sort files with Bill no .';
                exit;
            }
        }
    }

    //Import McCain Bills for SIFI bills
    public function mcCainExcelUploading($billFilePath){
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($billFilePath);
        $reader->setReadDataOnly(TRUE);
        $objPHPExcel = $reader->load($billFilePath);

        $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
        $highestRow = $worksheet->getHighestRow(); // e.g. 12
        $highestColumn = $worksheet->getHighestColumn(); // e.g M'

        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7

        $cnt=0;
        $total="";
        
        $routeCodeHeader="";
        $routeNameHeader="";
        $salesmanCodeHeader="";
        $salesmanNameHeader="";
        $retailerCodeHeader="";
        $retailerNameHeader="";

        $transactionTypeHeader="";
        $billDateHeader="";
        $billNumberHeader="";
        $productCodeHeader="";
        $productNameHeader="";
        $qtyHeader="";

        $mrpHeader="";
        $sellingPriceHeader="";
        $grossAmountHeader="";
        $schemeDescountHeader="";
        $dbDescountHeader="";
        $taxableValueHeader="";

        $cgstHeader="";
        $sgstHeader="";
        $igstHeader="";
        $netAmountHeader="";
        $billNetAmountHeader="";
        $creditAdjustmentHeader="";
        $gstHeader="";

        for ($row = 1; $row <= $highestRow; ++$row) {
            $cnt++;
            for($i=1;$i<=$highestColumnIndex;$i++){
                if(trim($worksheet->getCellByColumnAndRow($i, $row)->getValue())==="Route Code"){
                    $routeCodeHeader= $i;
                    $total=$cnt;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Route Name"){
                    $routeNameHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Seller Code"){
                    $salesmanCodeHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Seller Name"){
                    $salesmanNameHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Customer Code"){
                    $retailerCodeHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Customer Name"){
                    $retailerNameHeader= $i;
                }
                //////////////

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Transaction Type"){
                    $transactionTypeHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Invoice Date"){
                    $billDateHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Invoice Id"){
                    $billNumberHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="SKU Code"){
                    $productCodeHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="SKU Name"){
                    $productNameHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Qty Pieces"){
                    $qtyHeader= $i;
                }
                //////////

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="MRP"){
                    $mrpHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Price Pieces"){
                    $sellingPriceHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Gross Value"){
                    $grossAmountHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Scheme Discount"){
                    $schemeDescountHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="DB Discount Bill Level"){
                    $dbDescountHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Taxable Value"){
                    $taxableValueHeader= $i;
                }
                /////////
                
                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="CGST Amount"){
                    $cgstHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="SGST Amount"){
                    $sgstHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="IGST Amount"){
                    $igstHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Net Amount"){
                    $netAmountHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Invoice Net Amount"){
                    $billNetAmountHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Credit Note"){
                    $creditAdjustmentHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Customer GST Number"){
                    $gstHeader= $i;
                }
            }
        }

        if((empty($routeCodeHeader) || empty($routeNameHeader) || empty($salesmanCodeHeader) || empty($salesmanNameHeader) || empty($retailerCodeHeader) || empty($retailerNameHeader))){
            echo "Source file not in correct order. Please select correct files for uploading.";
            exit;
        }

        if((empty($transactionTypeHeader) || empty($billDateHeader) || empty($billNumberHeader) || empty($productCodeHeader) || empty($productNameHeader) || empty($qtyHeader))){
            echo "Source file not in correct order. Please select correct files for uploading.";
            exit;
        }

        if((empty($mrpHeader) || empty($sellingPriceHeader) || empty($grossAmountHeader) || empty($schemeDescountHeader) || empty($dbDescountHeader) || empty($taxableValueHeader))){
            echo "Source file not in correct order. Please select correct files for uploading.";
            exit;
        }

        if((empty($gstHeader) || empty($cgstHeader) || empty($sgstHeader) || empty($igstHeader) || empty($netAmountHeader) || empty($billNetAmountHeader) || empty($creditAdjustmentHeader))){
            echo "Source file not in correct order. Please select correct files for uploading.";
            exit;
        }

        for ($row = ($total+1); $row <= $highestRow; ++$row) {
            //bills
            $billDate=trim($worksheet->getCellByColumnAndRow($billDateHeader, $row)->getValue());
            $billNumber=trim($worksheet->getCellByColumnAndRow($billNumberHeader, $row)->getValue());
            $routeCode = trim($worksheet->getCellByColumnAndRow($routeCodeHeader, $row)->getValue());
            $routeName = trim($worksheet->getCellByColumnAndRow($routeNameHeader, $row)->getValue());
            $salesmanCode = trim($worksheet->getCellByColumnAndRow($salesmanCodeHeader, $row)->getValue());
            $salesmanName = trim($worksheet->getCellByColumnAndRow($salesmanNameHeader, $row)->getValue());
            $retailerName = trim($worksheet->getCellByColumnAndRow($retailerNameHeader, $row)->getValue());
            $retailerCode=trim($worksheet->getCellByColumnAndRow($retailerCodeHeader, $row)->getValue());
            $transactionType = trim($worksheet->getCellByColumnAndRow($transactionTypeHeader, $row)->getValue());
            $billNetAmount =trim($worksheet->getCellByColumnAndRow($billNetAmountHeader, $row)->getValue());
            $creditAdjustment =trim($worksheet->getCellByColumnAndRow($creditAdjustmentHeader, $row)->getValue());
            $gstNumber =trim($worksheet->getCellByColumnAndRow($gstHeader, $row)->getValue());

            //bill details
            $productCode = trim($worksheet->getCellByColumnAndRow($productCodeHeader, $row)->getValue());
            $productName=trim($worksheet->getCellByColumnAndRow($productNameHeader, $row)->getValue());
            $qty=trim($worksheet->getCellByColumnAndRow($qtyHeader, $row)->getValue());
            $netAmount = trim($worksheet->getCellByColumnAndRow($netAmountHeader, $row)->getValue());
            $mrp = trim($worksheet->getCellByColumnAndRow($mrpHeader, $row)->getValue());
            $sellingPrice=trim($worksheet->getCellByColumnAndRow($sellingPriceHeader, $row)->getValue());
            $grossAmount=trim($worksheet->getCellByColumnAndRow($grossAmountHeader, $row)->getValue());
            $schemeDescount = trim($worksheet->getCellByColumnAndRow($schemeDescountHeader, $row)->getValue());
            $dbDescount=trim($worksheet->getCellByColumnAndRow($dbDescountHeader, $row)->getValue());
            $taxableValue=trim($worksheet->getCellByColumnAndRow($taxableValueHeader, $row)->getValue());
            $cgst = trim($worksheet->getCellByColumnAndRow($cgstHeader, $row)->getValue());
            $sgst =trim($worksheet->getCellByColumnAndRow($sgstHeader, $row)->getValue());
            $igst =trim($worksheet->getCellByColumnAndRow($igstHeader, $row)->getValue());
                      

            $billDate=date('Y-m-d', strtotime($billDate));
            $excelDate="";

            if($transactionType=='Invoice'){
                if(!empty($billDate) && $billDate !=='Bill Date'){
                    $billExist=$this->ExcelModel->getBillByLastRecords('bills',$billNumber);
                    if(empty($billExist)){
                        $arrayRes=array(
                            'date'=>$billDate,
                            'billNo'=>$billNumber,
                            'deliveryStatus'=>'delivered',
                            'retailerCode'=>$retailerCode,
                            'retailerName'=>$retailerName,
                            'routeName'=>$routeName,
                            'routeCode'=>$routeCode,
                            'salesmanCode'=>$salesmanCode,
                            'salesman'=>$salesmanName,
                            'billNetAmount'=>round($billNetAmount),
                            'creditAdjustment'=>round($creditAdjustment),
                            'netAmount'=>round($billNetAmount),
                            'pendingAmt'=>round($billNetAmount),
                            'compName'=>'McCain',
                            'insertedAt'=>date('Y-m-d H:i:sa')
                        );
                        $this->ExcelModel->insert('bills',$arrayRes);
                    }

                    $retailerDetails=$this->ExcelModel->retailerInfo('retailer',$retailerName,$retailerCode);
                    if(empty($retailerDetails)){
                        $retailerData=array(
                            'code'=>$retailerCode,
                            'name'=>$retailerName,
                            'gstIn'=>$gstNumber,
                            'company'=>'McCain'
                        );
                        $this->ExcelModel->insert('retailer',$retailerData);
                    }

                    $routeDetails=$this->ExcelModel->retailerInfo('route',$routeName,$routeCode);
                    if(empty($routeDetails)){
                        $routeData=array(
                            'code'=>$routeCode,
                            'name'=>$routeName,
                            'company'=>'McCain'
                        );
                        $this->ExcelModel->insert('route',$routeData);
                    }
                }
            }
        }

        $billWithCount=array();
        $cnt=1;
        $tempNumber="";
        for ($row = ($total+1); $row <= $highestRow; ++$row) {
            $billDate = trim($worksheet->getCellByColumnAndRow($billDateHeader, $row)->getValue());
            $billNumber = trim($worksheet->getCellByColumnAndRow($billNumberHeader, $row)->getValue());

            if(trim($billNumber) != trim($tempNumber)){
                if($tempNumber !=""){
                    $billCount=array('billNo'=>$tempNumber,'Count'=>$cnt);
                    array_push($billWithCount,$billCount);
                    $cnt=1;
                }
            }else{
                $cnt++;
            }
            $tempNumber=$billNumber;
        }
        $billCount=array('billNo'=>$tempNumber,'Count'=>$cnt);
        array_push($billWithCount,$billCount);

        $cm = array_column($billWithCount, 'billNo');
        if($cm != array_unique($cm)){
            echo 'There are duplicates in billNo';
            exit;
        }

        // $tempAr=array();
        // foreach($billWithCount as $bill){
        //     if(!empty($bill)){
        //         if(in_array($bill,$billWithCount))
        //         {
        //             $cnt1=$cnt+$bill['Count'];
        //             $billCount=array('billNo'=>$bill['billNo'],'Count'=>$cnt1);
        //             array_push($tempAr,$billCount);
        //         }
        //     }
        // }

        // print_r($billWithCount);exit;

        $tempBillNumber="";
        $tempBillId=0;

        $totalGrossAmount=0;
        $totalTaxAmount=0;
        for ($row = ($total+1); $row <= $highestRow; ++$row) {
            $billDate = trim($worksheet->getCellByColumnAndRow($billDateHeader, $row)->getValue());
            $billNumber = trim($worksheet->getCellByColumnAndRow($billNumberHeader, $row)->getValue());

            $productCode = trim($worksheet->getCellByColumnAndRow($productCodeHeader, $row)->getValue());
            $productName=trim($worksheet->getCellByColumnAndRow($productNameHeader, $row)->getValue());
            $qty=trim($worksheet->getCellByColumnAndRow($qtyHeader, $row)->getValue());
            $netAmount = trim($worksheet->getCellByColumnAndRow($netAmountHeader, $row)->getValue());
            $mrp = trim($worksheet->getCellByColumnAndRow($mrpHeader, $row)->getValue());
            $sellingPrice=trim($worksheet->getCellByColumnAndRow($sellingPriceHeader, $row)->getValue());
            $grossAmount=trim($worksheet->getCellByColumnAndRow($grossAmountHeader, $row)->getValue());
            $schemeDescount = trim($worksheet->getCellByColumnAndRow($schemeDescountHeader, $row)->getValue());
            $dbDescount=trim($worksheet->getCellByColumnAndRow($dbDescountHeader, $row)->getValue());
            $taxableValue=trim($worksheet->getCellByColumnAndRow($taxableValueHeader, $row)->getValue());

            //tax 
            $cgst = trim($worksheet->getCellByColumnAndRow($cgstHeader, $row)->getValue());
            $sgst =trim($worksheet->getCellByColumnAndRow($sgstHeader, $row)->getValue());
            $igst =trim($worksheet->getCellByColumnAndRow($igstHeader, $row)->getValue());

            $taxValue=0;
            if($igst !=0){
                $taxValue=$igst;
            }else{
                $taxValue=$cgst+$sgst;
            }
            
            $billCount=0;
            $billExist=$this->ExcelModel->getBillByLastRecords('bills',$billNumber);
            if(!empty($billExist)){
                foreach($billWithCount as $bl){
                    if(trim($bl['billNo'])==trim($billNumber)){
                        $billCount=($bl['Count']);
                    }
                }

                $billId=$billExist[0]['id'];
                $billCountData=$this->ExcelModel->countBills('billsdetails',$billId);
                
                if($billCountData != $billCount){
                    $totalGrossAmount=$totalGrossAmount+$taxableValue;
                    $totalTaxAmount=$totalTaxAmount+$taxValue;

                    $readArray=array(
                        'billId'=>$billId,
                        'productCode'=>$productCode,
                        'motherPackName'=>$productName,
                        'productName'=>$productName,
                        'mrp'=>$mrp,
                        'sellingRate'=>$sellingPrice,
                        'qty'=>$qty,
                        'netAmount'=>$netAmount,
                        'grossRate'=>$grossAmount,
                        'taxAmount'=>$taxValue,
                        'schemaDisc'=>$schemeDescount,
                        'cddbDisc'=>$dbDescount,
                        'taxableValue'=>$taxableValue
                    );
                    
                    $this->ExcelModel->insert('billsdetails',$readArray);
                }else if($billCountData==0){
                    $totalGrossAmount=$totalGrossAmount+$taxableValue;
                    $totalTaxAmount=$totalTaxAmount+$taxValue;

                    $readArray=array(
                        'billId'=>$billId,
                        'productCode'=>$productCode,
                        'motherPackName'=>$productName,
                        'productName'=>$productName,
                        'mrp'=>$mrp,
                        'sellingRate'=>$sellingPrice,
                        'qty'=>$qty,
                        'netAmount'=>$netAmount,
                        'grossRate'=>$grossAmount,
                        'taxAmount'=>$taxValue,
                        'schemaDisc'=>$schemeDescount,
                        'cddbDisc'=>$dbDescount,
                        'taxableValue'=>$taxableValue
                    );
                    
                    $this->ExcelModel->insert('billsdetails',$readArray);
                }

                if($tempBillId != $billId){
                    $updateTempArray=array(
                        'grossAmount'=>$totalGrossAmount,
                        'taxAmount'=>$totalTaxAmount
                    );
                    $this->ExcelModel->update('bills',$updateTempArray,$tempBillId);

                    $totalGrossAmount=0;
                    $totalTaxAmount=0;
                }

                $tempBillNumber=$billNumber;
                $tempBillId=$billId;
            }
        }

        // $billData=$this->ExcelModel->getBillRecords('bills',$tempBillId);
        // if(!empty($billData)){
        //     $tempTotalnetAmount=round($billData[0]['netAmount']);
        //     $tempTotalpendingAmt=round($billData[0]['pendingAmt']);
        //     $updateTempArray=array(
        //         'netAmount'=>$tempTotalnetAmount,
        //         'pendingAmt'=>$tempTotalpendingAmt
        //     );
        //     $this->ExcelModel->update('bills',$updateTempArray,$tempBillId);
        // }

        $updateData=array('status'=>2);
        $this->ExcelModel->updateByCompany('cron_settings',$updateData,'McCain');
    }

    //check date for Amul bills data uploading / old nestle
    public function checkAmulExcelUploadingWithSifi($fileName,$fileType,$fileTempName,$dateForUploadBills){
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
            $total="";
            
            $billNumberHeader="";
            $billDateHeader="";
            $deliveryStatusHeader="";
            $salesmanNameHeader="";
            $routeNameHeader="";
            $retailerCodeHeader="";

            $retailerNameHeader="";
            $grossAmountHeader="";
            $taxAmountHeader="";
            $creditAdjustmentHeader="";
            $netAmountHeader="";

            for ($row = 1; $row <= $highestRow; ++$row) {
                $cnt++;
                for($i=1;$i<=$highestColumnIndex;$i++){
                    if(trim($worksheet->getCellByColumnAndRow($i, $row)->getValue())==="Bill Number"){
                        $billNumberHeader= $i;
                        $total=$cnt;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Bill Date"){
                        $billDateHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Delivery Status"){
                        $deliveryStatusHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Salesman"){
                        $salesmanNameHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Route"){
                        $routeNameHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Retailer Code"){
                        $retailerCodeHeader= $i;
                    }
                    //////////////

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Retailer Name"){
                        $retailerNameHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Gross Amount"){
                        $grossAmountHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Tax Amount"){
                        $taxAmountHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Credit Adjustment"){
                        $creditAdjustmentHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Net Amount"){
                        $netAmountHeader= $i;
                    }
                    //////////
                }
            }

            if((empty($billNumberHeader) || empty($billDateHeader) || empty($deliveryStatusHeader) || empty($salesmanNameHeader) || empty($routeNameHeader) || empty($retailerCodeHeader))){
                echo "Source file not in correct order. Please select correct files for uploading.";
                exit;
            }

            if((empty($retailerNameHeader) || empty($grossAmountHeader) || empty($taxAmountHeader) || empty($creditAdjustmentHeader) || empty($netAmountHeader))){
                echo "Source file not in correct order. Please select correct files for uploading.";
                exit;
            }
        }
    }

        //Import Amul Bills for SIFI bills
        public function amulExcelUploading($billFilePath){
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($billFilePath);
            $reader->setReadDataOnly(TRUE);
            $objPHPExcel = $reader->load($billFilePath);
    
            $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
            $highestRow = $worksheet->getHighestRow(); // e.g. 12
            $highestColumn = $worksheet->getHighestColumn(); // e.g M'
    
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
    
            $cnt=0;
            $total="";

            
            $billNumberHeader="";
            $billDateHeader="";
            $deliveryStatusHeader="";
            $salesmanNameHeader="";
            $routeNameHeader="";
            $retailerCodeHeader="";

            $retailerNameHeader="";
            $grossAmountHeader="";
            $taxAmountHeader="";
            $creditAdjustmentHeader="";
            $netAmountHeader="";

            for ($row = 1; $row <= $highestRow; ++$row) {
                $cnt++;
                for($i=1;$i<=$highestColumnIndex;$i++){
                   
                    if(trim($worksheet->getCellByColumnAndRow($i, $row)->getValue())==="Bill Number"){
                        $billNumberHeader= $i;
                        $total=$cnt;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Bill Date"){
                        $billDateHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Delivery Status"){
                        $deliveryStatusHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Salesman"){
                        $salesmanNameHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Route"){
                        $routeNameHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Retailer Code"){
                        $retailerCodeHeader= $i;
                    }
                    //////////////

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Retailer Name"){
                        $retailerNameHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Gross Amount"){
                        $grossAmountHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Tax Amount"){
                        $taxAmountHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Credit Adjustment"){
                        $creditAdjustmentHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Net Amount"){
                        $netAmountHeader= $i;
                    }
                    //////////
                }
            }

            if((empty($billNumberHeader) || empty($billDateHeader) || empty($deliveryStatusHeader) || empty($salesmanNameHeader) || empty($routeNameHeader) || empty($retailerCodeHeader))){
                echo "Source file not in correct order. Please select correct files for uploading.";
                exit;
            }

            if((empty($retailerNameHeader) || empty($grossAmountHeader) || empty($taxAmountHeader) || empty($creditAdjustmentHeader) || empty($netAmountHeader))){
                echo "Source file not in correct order. Please select correct files for uploading.";
                exit;
            }
    
            for ($row = ($total+1); $row <= $highestRow; ++$row) {
                //bills
                $billNumber=trim($worksheet->getCellByColumnAndRow($billNumberHeader, $row)->getValue());
                $billDate=trim($worksheet->getCellByColumnAndRow($billDateHeader, $row)->getValue());
                $deliveryStatus = trim($worksheet->getCellByColumnAndRow($deliveryStatusHeader, $row)->getValue());
                $salesmanName = trim($worksheet->getCellByColumnAndRow($salesmanNameHeader, $row)->getValue());
                $routeName = trim($worksheet->getCellByColumnAndRow($routeNameHeader, $row)->getValue());
                $retailerCode = trim($worksheet->getCellByColumnAndRow($retailerCodeHeader, $row)->getValue());
                $retailerName = trim($worksheet->getCellByColumnAndRow($retailerNameHeader, $row)->getValue());
                $grossAmount=trim($worksheet->getCellByColumnAndRow($grossAmountHeader, $row)->getValue());
                $taxAmount = trim($worksheet->getCellByColumnAndRow($taxAmountHeader, $row)->getValue());
                $creditAdjustment =trim($worksheet->getCellByColumnAndRow($creditAdjustmentHeader, $row)->getValue());
                $netAmount =trim($worksheet->getCellByColumnAndRow($netAmountHeader, $row)->getValue());
    
                $billDate=date('Y-m-d', strtotime($billDate));
                $excelDate="";
            
                if($billNumber !="Totals"){
                    if($billNumber !=""){
                        if(!empty($billDate) && $billDate !=='Bill Date'){
                            if(strtolower($deliveryStatus)=='Delivered' || strtolower($deliveryStatus)=='delivered'){
                                $billExist=$this->ExcelModel->getBillByLastRecords('bills',$billNumber);
                                if(empty($billExist)){
                                    $arrayRes=array(
                                        'date'=>$billDate,
                                        'billNo'=>$billNumber,
                                        'deliveryStatus'=>strtolower($deliveryStatus),
                                        'retailerCode'=>$retailerCode,
                                        'retailerName'=>$retailerName,
                                        'routeName'=>$routeName,
                                        'salesman'=>$salesmanName,
                                        'billNetAmount'=>round($netAmount),
                                        'creditAdjustment'=>round($creditAdjustment),
                                        'netAmount'=>round($netAmount),
                                        'pendingAmt'=>round($netAmount),
                                        'grossAmount'=>$grossAmount,
                                        'taxAmount'=>$taxAmount,
                                        'compName'=>'Amul',
                                        'insertedAt'=>date('Y-m-d H:i:sa')
                                    );
                                    $this->ExcelModel->insert('bills',$arrayRes);
                                }
                            }
                        }

                        $retailerDetails=$this->ExcelModel->retailerInfo('retailer',$retailerName,$retailerCode);
                        if(empty($retailerDetails)){
                            $retailerData=array(
                                'code'=>$retailerCode,
                                'name'=>$retailerName,
                                'company'=>'Amul'
                            );
                            $this->ExcelModel->insert('retailer',$retailerData);
                        }
                    }
                }
                
            }
    
            // $updateData=array('status'=>2);
            // $this->ExcelModel->updateByCompany('cron_settings',$updateData,'Amul');
        }

    //check date for Amul bill details data uploading / old nestle
    public function checkAmulBillDetailsExcelUploadingWithSifi($fileName,$fileType,$fileTempName,$dateForUploadBills){
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
            $total="";
            
            $billNumberHeader="";
            $productCodeHeader="";
            $productNameHeader="";
            $mrpHeader="";
            $sellingRateHeader="";
            $qtyHeader="";

            $grossAmountHeader="";
            $schemeDiscountHeader="";
            $distributorDiscountHeader="";
            $cashDiscountHeader="";
            $taxAmountHeader="";
            $netAmountHeader="";

            for ($row = 1; $row <= $highestRow; ++$row) {
                $cnt++;
                for($i=1;$i<=$highestColumnIndex;$i++){
                    if(trim($worksheet->getCellByColumnAndRow($i, $row)->getValue())==="Sales Invoice Number"){
                        $billNumberHeader= $i;
                        $total=$cnt;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Company Product Code"){
                        $productCodeHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Product Name"){
                        $productNameHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="MRP"){
                        $mrpHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Selling Rate"){
                        $sellingRateHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Quantity Billed"){
                        $qtyHeader= $i;
                    }
                    //////////////

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Gross Amount"){
                        $grossAmountHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Product Scheme Discount"){
                        $schemeDiscountHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Distributor Discount"){
                        $distributorDiscountHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Cash Discount"){
                        $cashDiscountHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Tax Amount"){
                        $taxAmountHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Net Amount"){
                        $netAmountHeader= $i;
                    }
                    //////////
                }

            }

            if((empty($billNumberHeader) || empty($productCodeHeader) || empty($productNameHeader) || empty($mrpHeader) || empty($sellingRateHeader) || empty($qtyHeader))){
                echo "Source file not in correct order. Please select correct files for uploading.";
                exit;
            }

            if((empty($grossAmountHeader) || empty($schemeDiscountHeader) || empty($distributorDiscountHeader) || empty($cashDiscountHeader) || empty($taxAmountHeader) || empty($netAmountHeader))){
                echo "Source file not in correct order. Please select correct files for uploading.";
                exit;
            }
            
        }
    }

    //Import Amul Bill Details for SIFI bills
    public function amulBillDetailsExcelUploading($billFilePath){
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($billFilePath);
        $reader->setReadDataOnly(TRUE);
        $objPHPExcel = $reader->load($billFilePath);

        $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
        $highestRow = $worksheet->getHighestRow(); // e.g. 12
        $highestColumn = $worksheet->getHighestColumn(); // e.g M'

        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7

        $cnt=0;
        $total="";
        $totalHeader="";
        
        $billNumberHeader="";
        $productCodeHeader="";
        $productNameHeader="";
        $mrpHeader="";
        $sellingRateHeader="";
        $qtyHeader="";

        $grossAmountHeader="";
        $schemeDiscountHeader="";
        $distributorDiscountHeader="";
        $cashDiscountHeader="";
        $taxAmountHeader="";
        $netAmountHeader="";

        for ($row = 1; $row <= $highestRow; ++$row) {
            $cnt++;
            for($i=1;$i<=$highestColumnIndex;$i++){

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Salesman"){
                    $totalHeader= $i;
                }

                if(trim($worksheet->getCellByColumnAndRow($i, $row)->getValue())==="Sales Invoice Number"){
                    $billNumberHeader= $i;
                    $total=$cnt;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Company Product Code"){
                    $productCodeHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Product Name"){
                    $productNameHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="MRP"){
                    $mrpHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Selling Rate"){
                    $sellingRateHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Quantity Billed"){
                    $qtyHeader= $i;
                }
                //////////////

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Gross Amount"){
                    $grossAmountHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Product Scheme Discount"){
                    $schemeDiscountHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Distributor Discount"){
                    $distributorDiscountHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Cash Discount"){
                    $cashDiscountHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Tax Amount"){
                    $taxAmountHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Net Amount"){
                    $netAmountHeader= $i;
                }
                //////////
            }

        }

        if((empty($billNumberHeader) || empty($totalHeader) || empty($productCodeHeader) || empty($productNameHeader) || empty($mrpHeader) || empty($sellingRateHeader) || empty($qtyHeader))){
            echo "Source file not in correct order. Please select correct files for uploading.";
            exit;
        }

        if((empty($grossAmountHeader) || empty($schemeDiscountHeader) || empty($distributorDiscountHeader) || empty($cashDiscountHeader) || empty($taxAmountHeader) || empty($netAmountHeader))){
            echo "Source file not in correct order. Please select correct files for uploading.";
            exit;
        }

        
        $billWithCount=array();
        $cnt=1;
        $tempNumber="";
        for ($row = ($total+1); $row <= $highestRow; ++$row) {
            $billNumber = trim($worksheet->getCellByColumnAndRow($billNumberHeader, $row)->getValue());
            if(trim($billNumber) != trim($tempNumber)){
                if($tempNumber !=""){
                    $billCount=array('billNo'=>$tempNumber,'Count'=>$cnt);
                    array_push($billWithCount,$billCount);
                    $cnt=1;
                }
            }else{
                $cnt++;
            }
            $tempNumber=$billNumber;
        }
        $billCount=array('billNo'=>$tempNumber,'Count'=>$cnt);
        array_push($billWithCount,$billCount);

        $tempBillNumber="";
        $tempBillId=0;
        for ($row = ($total+1); $row <= $highestRow; ++$row) {
            //bills products

            $totalHeaderData=trim($worksheet->getCellByColumnAndRow($totalHeader, $row)->getValue());

            $billNumber=trim($worksheet->getCellByColumnAndRow($billNumberHeader, $row)->getValue());
            $productCode=trim($worksheet->getCellByColumnAndRow($productCodeHeader, $row)->getValue());
            $productName = trim($worksheet->getCellByColumnAndRow($productNameHeader, $row)->getValue());
            $mrp = trim($worksheet->getCellByColumnAndRow($mrpHeader, $row)->getValue());
            $sellingRate = trim($worksheet->getCellByColumnAndRow($sellingRateHeader, $row)->getValue());
            $qty = trim($worksheet->getCellByColumnAndRow($qtyHeader, $row)->getValue());
            
            $grossAmount = trim($worksheet->getCellByColumnAndRow($grossAmountHeader, $row)->getValue());
            $schemeDiscount=trim($worksheet->getCellByColumnAndRow($schemeDiscountHeader, $row)->getValue());
            $distributorDiscount = trim($worksheet->getCellByColumnAndRow($distributorDiscountHeader, $row)->getValue());
            $cashDiscount =trim($worksheet->getCellByColumnAndRow($cashDiscountHeader, $row)->getValue());
            $taxAmount =trim($worksheet->getCellByColumnAndRow($taxAmountHeader, $row)->getValue());
            $netAmount =trim($worksheet->getCellByColumnAndRow($netAmountHeader, $row)->getValue());
            
            if($totalHeaderData != "Totals"){
                if($totalHeaderData != ""){
                    $billCount=0;
                    $billExist=$this->ExcelModel->getBillByLastRecords('bills',$billNumber);
                    if(!empty($billExist)){
                        foreach($billWithCount as $bl){
                            if(trim($bl['billNo'])==trim($billNumber)){
                                $billCount=($bl['Count']);
                            }
                        }

                        $billId=$billExist[0]['id'];
                        $billCountData=$this->ExcelModel->countBills('billsdetails',$billId);
                        
                        if($billCountData != $billCount){
                            $readArray=array(
                                'billId'=>$billId,
                                'productCode'=>$productCode,
                                'motherPackName'=>$productName,
                                'productName'=>$productName,
                                'mrp'=>$mrp,
                                'sellingRate'=>$sellingRate,
                                'qty'=>$qty,
                                'netAmount'=>$netAmount,
                                'grossRate'=>$grossAmount,
                                'taxAmount'=>$taxAmount,
                                'schemaDisc'=>$schemeDiscount,
                                'cddbDisc'=>($distributorDiscount+$cashDiscount),
                                'taxableValue'=>$grossAmount
                            );
                            
                            $this->ExcelModel->insert('billsdetails',$readArray);
                        }else if($billCountData==0){
                            $readArray=array(
                                'billId'=>$billId,
                                'productCode'=>$productCode,
                                'motherPackName'=>$productName,
                                'productName'=>$productName,
                                'mrp'=>$mrp,
                                'sellingRate'=>$sellingRate,
                                'qty'=>$qty,
                                'netAmount'=>$netAmount,
                                'grossRate'=>$grossAmount,
                                'taxAmount'=>$taxAmount,
                                'schemaDisc'=>$schemeDiscount,
                                'cddbDisc'=>($distributorDiscount+$cashDiscount),
                                'taxableValue'=>$grossAmount
                            );
                            $this->ExcelModel->insert('billsdetails',$readArray);
                        }

                        $tempBillNumber=$billNumber;
                        $tempBillId=$billId;
                    }
                }
                
            }
            
        }

        $updateData=array('status'=>2);
        $this->ExcelModel->updateByCompany('cron_settings',$updateData,'Amul');
    }

    //Import Amul Retailers Details for SIFI bills
    public function amulRetailerDetailsExcelUploading($billFilePath){
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($billFilePath);
        $reader->setReadDataOnly(TRUE);
        $objPHPExcel = $reader->load($billFilePath);

        $worksheet = $objPHPExcel->getSheet(3);//Get sheet 
        $highestRow = $worksheet->getHighestRow(); // e.g. 12
        $highestColumn = $worksheet->getHighestColumn(); // e.g M'

        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7

        $cnt=0;
        $total="";
        
        $routeCodeHeader="";
        $routeNameHeader="";
        $retailerCodeHeader="";
        $retailerNameHeader="";
        $gstHeader="";

        for ($row = 1; $row <= $highestRow; ++$row) {
            $cnt++;
            for($i=1;$i<=$highestColumnIndex;$i++){

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Route Code"){
                    $routeCodeHeader= $i;
                }

                if(trim($worksheet->getCellByColumnAndRow($i, $row)->getValue())==="Route Name"){
                    $routeNameHeader= $i;
                    $total=$cnt;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Retailer Code"){
                    $retailerCodeHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Retailer Name"){
                    $retailerNameHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="GSTIN Number"){
                    $gstHeader= $i;
                }
            }
        }

        if((empty($routeCodeHeader) || empty($routeNameHeader) || empty($retailerCodeHeader) || empty($retailerNameHeader) || empty($gstHeader))){
            echo "Source file not in correct order. Please select correct files for uploading.";
            exit;
        }

        $tempBillNumber="";
        $tempBillId=0;
        for ($row = ($total+1); $row <= $highestRow; ++$row) {
            //bills products
            $routeCode=trim($worksheet->getCellByColumnAndRow($routeCodeHeader, $row)->getValue());
            $routeName=trim($worksheet->getCellByColumnAndRow($routeNameHeader, $row)->getValue());
            $retailerCode=trim($worksheet->getCellByColumnAndRow($retailerCodeHeader, $row)->getValue());
            $retailerName=trim($worksheet->getCellByColumnAndRow($retailerNameHeader, $row)->getValue());
            $gstNumber=trim($worksheet->getCellByColumnAndRow($gstHeader, $row)->getValue());
            
            // check retailer exist or not
            $retailerExist=$this->ExcelModel->retailerInfo('retailer',$retailerName,$retailerCode);
            if(!empty($retailerExist)){
                $retailerId=$retailerExist[0]['id'];
                $retailerData=array(
                    // 'code'=>$retailerCode,
                    'gstIn'=>$gstNumber,
                    'company'=>'Amul'
                );
                $this->ExcelModel->update('retailer',$retailerData,$retailerId);
            }else{
                $retailerData=array(
                    'name'=>$retailerName,
                    'code'=>$retailerCode,
                    'gstIn'=>$gstNumber,
                    'company'=>'Amul'
                );
                $this->ExcelModel->insert('retailer',$retailerData);
            }

            // check route exist or not
            $routeExist=$this->ExcelModel->retailerInfo('route',$routeName,$routeCode);
            if(empty($routeExist)){
                $routeData=array(
                    'name'=>$routeName,
                    'code'=>$routeCode,
                    'company'=>'Amul'
                );
                $this->ExcelModel->insert('route',$routeData);
            }
        }

        $updateData=array('status'=>2);
        $this->ExcelModel->updateByCompany('cron_settings',$updateData,'Amul');
    }

    //check date for Marico bills data uploading / old nestle
    public function checkMaricoBillsExcelUploading($fileName,$fileType,$fileTempName,$dateForUploadBills){
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
            $total="";
            
            $billNumberHeader="";
            $billDateHeader="";
            $deliveryStatusHeader="";
            $salesmanHeader="";
            $routeHeader="";
            $retailerCodeHeader="";

            $retailerNameHeader="";
            $grossAmountHeader="";
            $schemeDiscountHeader="";
            $secondaryHeader="";
            $primaryHeader="";
            $discountHeader="";

            $dbDiscountHeader="";
            $splDiscountHeader="";
            $taxAmountHeader="";
            $creditAdjusmentHeader="";
            $netAmountHeader="";

            for ($row = 1; $row <= $highestRow; ++$row) {
                $cnt++;
                for($i=1;$i<=$highestColumnIndex;$i++){

                    if(trim($worksheet->getCellByColumnAndRow($i, $row)->getValue())==="Bill Number"){
                        $billNumberHeader= $i;
                        $total=$cnt;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Bill Date"){
                        $billDateHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Delivery Status"){
                        $deliveryStatusHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Salesman"){
                        $salesmanHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Route"){
                        $routeHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Retailer Code"){
                        $retailerCodeHeader= $i;
                    }
                    //////////////

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Retailer Name"){
                        $retailerNameHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Gross Amount"){
                        $grossAmountHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Scheme Disc"){
                        $schemeDiscountHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Secondary Disc"){
                        $secondaryHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Primary Disc"){
                        $primaryHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Discount"){
                        $discountHeader= $i;
                    }
                    //////////

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="DB Disc."){
                        $dbDiscountHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Spl Disc."){
                        $splDiscountHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Tax Amount"){
                        $taxAmountHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Credit Adjustment"){
                        $creditAdjusmentHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Net Amount"){
                        $netAmountHeader= $i;
                    }
                }

            }

            if((empty($billNumberHeader) || empty($billDateHeader) || empty($deliveryStatusHeader) || empty($salesmanHeader) || empty($routeHeader) || empty($retailerCodeHeader))){
                echo "Source file not in correct order. Please select correct files for uploading.";
                exit;
            }

            if((empty($retailerNameHeader) || empty($grossAmountHeader) || empty($schemeDiscountHeader) || empty($secondaryHeader) || empty($primaryHeader) || empty($discountHeader))){
                echo "Source file not in correct order. Please select correct files for uploading.";
                exit;
            }

            if((empty($dbDiscountHeader) || empty($splDiscountHeader) || empty($taxAmountHeader) || empty($creditAdjusmentHeader) || empty($netAmountHeader))){
                echo "Source file not in correct order. Please select correct files for uploading.";
                exit;
            }
            
        }
    }

    //Import Marico Bill Details for SIFI bills
    public function maricoBillDetailsExcelUploading($billFilePath){
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($billFilePath);
        $reader->setReadDataOnly(TRUE);
        $objPHPExcel = $reader->load($billFilePath);

        $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
        $highestRow = $worksheet->getHighestRow(); // e.g. 12
        $highestColumn = $worksheet->getHighestColumn(); // e.g M'

        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7

        $cnt=0;
        $total="";
        $totalHeader="";
        
        $billNumberHeader="";
        $billDateHeader="";
        $deliveryStatusHeader="";
        $salesmanHeader="";
        $routeHeader="";
        $retailerCodeHeader="";

        $retailerNameHeader="";
        $grossAmountHeader="";
        $schemeDiscountHeader="";
        $secondaryHeader="";
        $primaryHeader="";
        $discountHeader="";

        $dbDiscountHeader="";
        $splDiscountHeader="";
        $taxAmountHeader="";
        $creditAdjusmentHeader="";
        $netAmountHeader="";

        for ($row = 1; $row <= $highestRow; ++$row) {
            $cnt++;
            for($i=1;$i<=$highestColumnIndex;$i++){

                if(trim($worksheet->getCellByColumnAndRow($i, $row)->getValue())==="Bill Number"){
                    $billNumberHeader= $i;
                    $total=$cnt;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Bill Date"){
                    $billDateHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Delivery Status"){
                    $deliveryStatusHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Salesman"){
                    $salesmanHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Route"){
                    $routeHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Retailer Code"){
                    $retailerCodeHeader= $i;
                }
                //////////////

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Retailer Name"){
                    $retailerNameHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Gross Amount"){
                    $grossAmountHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Scheme Disc"){
                    $schemeDiscountHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Secondary Disc"){
                    $secondaryHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Primary Disc"){
                    $primaryHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Discount"){
                    $discountHeader= $i;
                }
                //////////

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="DB Disc."){
                    $dbDiscountHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Spl Disc."){
                    $splDiscountHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Tax Amount"){
                    $taxAmountHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Credit Adjustment"){
                    $creditAdjusmentHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Net Amount"){
                    $netAmountHeader= $i;
                }
            }

        }

        if((empty($billNumberHeader) || empty($billDateHeader) || empty($deliveryStatusHeader) || empty($salesmanHeader) || empty($routeHeader) || empty($retailerCodeHeader))){
            echo "Source file not in correct order. Please select correct files for uploading.";
            exit;
        }

        if((empty($retailerNameHeader) || empty($grossAmountHeader) || empty($schemeDiscountHeader) || empty($secondaryHeader) || empty($primaryHeader) || empty($discountHeader))){
            echo "Source file not in correct order. Please select correct files for uploading.";
            exit;
        }

        if((empty($dbDiscountHeader) || empty($splDiscountHeader) || empty($taxAmountHeader) || empty($creditAdjusmentHeader) || empty($netAmountHeader))){
            echo "Source file not in correct order. Please select correct files for uploading.";
            exit;
        }

        for ($row = ($total+1); $row <= $highestRow; ++$row) {
            //bills products

            $billNumber=trim($worksheet->getCellByColumnAndRow($billNumberHeader, $row)->getValue());
            $billDate=trim($worksheet->getCellByColumnAndRow($billDateHeader, $row)->getValue());
            if(!empty($billDate)){
                $billDate = ($billDate - 25569) * 86400;
                $billDate=date('Y-m-d', $billDate);//convert date from excel data
            }
            // echo $billDate;exit;
            $deliveryStatus=trim($worksheet->getCellByColumnAndRow($deliveryStatusHeader, $row)->getValue());
            $salesmanName = trim($worksheet->getCellByColumnAndRow($salesmanHeader, $row)->getValue());
            $routeName = trim($worksheet->getCellByColumnAndRow($routeHeader, $row)->getValue());
            $retailerCode = trim($worksheet->getCellByColumnAndRow($retailerCodeHeader, $row)->getValue());

            $retailerName = trim($worksheet->getCellByColumnAndRow($retailerNameHeader, $row)->getValue());
            $grossAmount = trim($worksheet->getCellByColumnAndRow($grossAmountHeader, $row)->getValue());

            //not required
            $schemeDiscount=trim($worksheet->getCellByColumnAndRow($schemeDiscountHeader, $row)->getValue());
            $secondaryDiscount = trim($worksheet->getCellByColumnAndRow($secondaryHeader, $row)->getValue());
            $primaryDiscount =trim($worksheet->getCellByColumnAndRow($primaryHeader, $row)->getValue());
            $discount =trim($worksheet->getCellByColumnAndRow($discountHeader, $row)->getValue());
            $dbDiscount =trim($worksheet->getCellByColumnAndRow($dbDiscountHeader, $row)->getValue());
            $splDiscount =trim($worksheet->getCellByColumnAndRow($splDiscountHeader, $row)->getValue());
            //

            $taxAmount =trim($worksheet->getCellByColumnAndRow($taxAmountHeader, $row)->getValue());
            $creditAdjustment =trim($worksheet->getCellByColumnAndRow($creditAdjusmentHeader, $row)->getValue());
            $netAmount =trim($worksheet->getCellByColumnAndRow($netAmountHeader, $row)->getValue());

            
            
            if($billNumber != ""){
                if($billNumber != "TOTAL"){
                    $billCount=0;
                    $billExist=$this->ExcelModel->getBillByLastRecords('bills',$billNumber);
                    if(empty($billExist)){
                        $arrayRes=array(
                            'date'=>$billDate,
                            'billNo'=>$billNumber,
                            'deliveryStatus'=>strtolower($deliveryStatus),
                            'retailerCode'=>$retailerCode,
                            'retailerName'=>$retailerName,
                            'routeName'=>$routeName,
                            'salesman'=>$salesmanName,
                            'billNetAmount'=>round($netAmount),
                            'creditAdjustment'=>round($creditAdjustment,2),
                            'netAmount'=>round($netAmount),
                            'pendingAmt'=>round($netAmount),
                            'grossAmount'=>round($grossAmount,2),
                            'taxAmount'=>round($taxAmount,2),
                            'compName'=>'Marico',
                            'insertedAt'=>date('Y-m-d H:i:sa')
                        );
                        $this->ExcelModel->insert('bills',$arrayRes);

                        //latest inserted id
                        $insert_id = $this->db->insert_id();
                        $salesmanCode="";
                        $salesmanExist=$this->ExcelModel->getSalesmanCount('bills',$salesmanName);
                        if(!empty($salesmanExist)){
                            $salesmanCode=$salesmanExist[0]['salesmanCode'];
                        }else{
                            $salesmanCode='MARICO'.$insert_id;
                        }
                        $updateSalesmanCode=array('salesmanCode'=>$salesmanCode);
                        $this->ExcelModel->update('bills',$updateSalesmanCode,$insert_id);
                    }
                }
            }
        }

        $updateData=array('status'=>2);
        $this->ExcelModel->updateByCompany('cron_settings',$updateData,'Marico');
    }

    //Import Marico Bill Item Details for SIFI bills
    public function maricoBillItemDetailsExcelUploading($billFilePath){
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($billFilePath);
        $reader->setReadDataOnly(TRUE);
        $objPHPExcel = $reader->load($billFilePath);

        $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
        $highestRow = $worksheet->getHighestRow(); // e.g. 12
        $highestColumn = $worksheet->getHighestColumn(); // e.g M'

        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7

        $cnt=0;
        $total="";
        
        $billNumberHeader="";
        $statusHeader="";
        $productCodeHeader="";
        $productNameHeader="";
        $mrpHeader="";

        $sellingRateHeader="";
        $qtyHeader="";
        $schemeDiscountHeader="";
        $distributorDiscountHeader="";
        $taxableAmountHeader="";

        $grossAmountHeader="";
        $netAmountHeader="";
        $taxAmountHeader="";

        for ($row = 1; $row <= $highestRow; ++$row) {
            $cnt++;
            for($i=1;$i<=$highestColumnIndex;$i++){

                if(trim($worksheet->getCellByColumnAndRow($i, $row)->getValue())==="Bill Number"){
                    $billNumberHeader= $i;
                    $total=$cnt;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Status"){
                    $statusHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Product Code"){
                    $productCodeHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Product Name"){
                    $productNameHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="MRP"){
                    $mrpHeader= $i;
                }
                //////////////

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Selling Rate"){
                    $sellingRateHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Total Qty"){
                    $qtyHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Total SecondaryScheme Amt"){
                    $schemeDiscountHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Cash Discount"){
                    $distributorDiscountHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Sales Ex Tax"){
                    $taxableAmountHeader= $i;
                }
                //////////

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Gross Amt"){
                    $grossAmountHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="NetAmount"){
                    $netAmountHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Total Tax Amount"){
                    $taxAmountHeader= $i;
                }
            }
        }
        
       

        if((empty($billNumberHeader) || empty($statusHeader) || empty($productCodeHeader) || empty($productNameHeader) || empty($mrpHeader))){
            echo "Source file not in correct order. Please select correct files for uploading.";
            exit;
        }
        
        if((empty($sellingRateHeader) || empty($qtyHeader) || empty($schemeDiscountHeader) || empty($distributorDiscountHeader) || empty($taxableAmountHeader))){
            echo "Source file not in correct order. Please select correct files for uploading.";
            exit;
        }

        if((empty($grossAmountHeader) || empty($netAmountHeader) || empty($taxAmountHeader))){
            echo "Source file not in correct order. Please select correct files for uploading.";
            exit;
        }

        $billWithCount=array();
        $cnt=1;
        $tempNumber="";
        for ($row = ($total+1); $row <= $highestRow; ++$row) {
            $billNumber = trim($worksheet->getCellByColumnAndRow($billNumberHeader, $row)->getValue());
            if(trim($billNumber) != trim($tempNumber)){
                if($tempNumber !=""){
                    $billCount=array('billNo'=>$tempNumber,'Count'=>$cnt);
                    array_push($billWithCount,$billCount);
                    $cnt=1;
                }
            }else{
                $cnt++;
            }
            $tempNumber=$billNumber;
        }
        $billCount=array('billNo'=>$tempNumber,'Count'=>$cnt);
        array_push($billWithCount,$billCount);

        $tempBillNumber="";
        $tempBillId=0;
        for ($row = ($total+1); $row <= $highestRow; ++$row) {

            //bills products
            $billNumber=trim($worksheet->getCellByColumnAndRow($billNumberHeader, $row)->getValue());
            $status=trim($worksheet->getCellByColumnAndRow($statusHeader, $row)->getValue());
            $productCode = trim($worksheet->getCellByColumnAndRow($productCodeHeader, $row)->getValue());
            $productName = trim($worksheet->getCellByColumnAndRow($productNameHeader, $row)->getValue());
            $mrp = trim($worksheet->getCellByColumnAndRow($mrpHeader, $row)->getValue());

            $sellingRate = trim($worksheet->getCellByColumnAndRow($sellingRateHeader, $row)->getValue());
            $qty = trim($worksheet->getCellByColumnAndRow($qtyHeader, $row)->getValue());
            $schemeDiscount=trim($worksheet->getCellByColumnAndRow($schemeDiscountHeader, $row)->getValue());
            $distributorDiscount = trim($worksheet->getCellByColumnAndRow($distributorDiscountHeader, $row)->getValue());
            $taxableAmount =trim($worksheet->getCellByColumnAndRow($taxableAmountHeader, $row)->getValue());

            $grossAmount =trim($worksheet->getCellByColumnAndRow($grossAmountHeader, $row)->getValue());
            $netAmount =trim($worksheet->getCellByColumnAndRow($netAmountHeader, $row)->getValue());
            $taxAmount =trim($worksheet->getCellByColumnAndRow($taxAmountHeader, $row)->getValue());
            
            $billCount=0;
            $billExist=$this->ExcelModel->getBillByLastRecords('bills',$billNumber);
            if(!empty($billExist)){
                foreach($billWithCount as $bl){
                    if(trim($bl['billNo'])==trim($billNumber)){
                        $billCount=($bl['Count']);
                    }
                }

                if($status !="Sales Return"){
                    $billId=$billExist[0]['id'];
                    $billCountData=$this->ExcelModel->countBills('billsdetails',$billId);
                    if($billCountData != $billCount){
                        $readArray=array(
                            'billId'=>$billId,
                            'productCode'=>$productCode,
                            'motherPackName'=>$productName,
                            'productName'=>$productName,
                            'mrp'=>$mrp,
                            'sellingRate'=>round($sellingRate,2),
                            'qty'=>$qty,
                            'netAmount'=>round($netAmount,2),
                            'grossRate'=>round($grossAmount,2),
                            'taxAmount'=>round($taxAmount,2),
                            'schemaDisc'=>round($schemeDiscount,2),
                            'cddbDisc'=>round($distributorDiscount,2),
                            'taxableValue'=>round($taxableAmount,2)
                        );
                        
                        $this->ExcelModel->insert('billsdetails',$readArray);
                    }else if($billCountData==0){
                        $readArray=array(
                            'billId'=>$billId,
                            'productCode'=>$productCode,
                            'motherPackName'=>$productName,
                            'productName'=>$productName,
                            'mrp'=>$mrp,
                            'sellingRate'=>round($sellingRate,2),
                            'qty'=>$qty,
                            'netAmount'=>round($netAmount,2),
                            'grossRate'=>round($grossAmount,2),
                            'taxAmount'=>round($taxAmount,2),
                            'schemaDisc'=>round($schemeDiscount,2),
                            'cddbDisc'=>round($distributorDiscount,2),
                            'taxableValue'=>round($taxableAmount,2)
                        );
                        $this->ExcelModel->insert('billsdetails',$readArray);
                    }

                    $tempBillNumber=$billNumber;
                    $tempBillId=$billId;
                }
            }
               
        }

        $updateData=array('status'=>2);
        $this->ExcelModel->updateByCompany('cron_settings',$updateData,'Marico');
    }

    //Import Marico Retailers Details for SIFI bills
    public function maricoRetailerDetailsExcelUploading($billFilePath){
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($billFilePath);
        $reader->setReadDataOnly(TRUE);
        $objPHPExcel = $reader->load($billFilePath);

        $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
        $highestRow = $worksheet->getHighestRow(); // e.g. 12
        $highestColumn = $worksheet->getHighestColumn(); // e.g M'

        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7

        $cnt=0;
        $total="";
        
        $routeCodeHeader="";
        $routeNameHeader="";
        $retailerCodeHeader="";
        $retailerNameHeader="";
        $gstHeader="";

        for ($row = 1; $row <= $highestRow; ++$row) {
            $cnt++;
            for($i=1;$i<=$highestColumnIndex;$i++){

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Delivery Route Code"){
                    $routeCodeHeader= $i;
                }

                if(trim($worksheet->getCellByColumnAndRow($i, $row)->getValue())==="Delivery Route Name"){
                    $routeNameHeader= $i;
                    $total=$cnt;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Retailer Code"){
                    $retailerCodeHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Retailer Name"){
                    $retailerNameHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="GSTIN"){
                    $gstHeader= $i;
                }
            }
        }

        if((empty($routeCodeHeader) || empty($routeNameHeader) || empty($retailerCodeHeader) || empty($retailerNameHeader) || empty($gstHeader))){
            echo "Source file not in correct order. Please select correct files for uploading.";
            exit;
        }

        $tempBillNumber="";
        $tempBillId=0;
        for ($row = ($total+1); $row <= $highestRow; ++$row) {
            //bills products
            $routeCode=trim($worksheet->getCellByColumnAndRow($routeCodeHeader, $row)->getValue());
            $routeName=trim($worksheet->getCellByColumnAndRow($routeNameHeader, $row)->getValue());
            $retailerCode=trim($worksheet->getCellByColumnAndRow($retailerCodeHeader, $row)->getValue());
            $retailerName=trim($worksheet->getCellByColumnAndRow($retailerNameHeader, $row)->getValue());
            $gstNumber=trim($worksheet->getCellByColumnAndRow($gstHeader, $row)->getValue());
            
            // check retailer exist or not
            $retailerExist=$this->ExcelModel->retailerInfo('retailer',$retailerName,$retailerCode);
            if(!empty($retailerExist)){
                $retailerId=$retailerExist[0]['id'];
                $retailerData=array(
                    'gstIn'=>$gstNumber,
                    'company'=>'Marico'
                );
                $this->ExcelModel->update('retailer',$retailerData,$retailerId);
            }else{
                $retailerData=array(
                    'name'=>$retailerName,
                    'code'=>$retailerCode,
                    'gstIn'=>$gstNumber,
                    'company'=>'Marico'
                );
                $this->ExcelModel->insert('retailer',$retailerData);
            }

            // check route exist or not
            $routeExist=$this->ExcelModel->retailerInfo('route',$routeName,$routeCode);
            if(empty($routeExist)){
                $routeData=array(
                    'name'=>$routeName,
                    'code'=>$routeCode,
                    'company'=>'Marico'
                );
                $this->ExcelModel->insert('route',$routeData);
            }
        }

        $updateData=array('status'=>2);
        $this->ExcelModel->updateByCompany('cron_settings',$updateData,'Marico');
    }

    //check date for Havells bills data uploading / old nestle
    public function checkHavellsElectricsBillsExcelUploading($fileName,$fileType,$fileTempName,$dateForUploadBills){
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
            $total="";

            $billDateHeader="";
            $billNumberHeader="";
            $retailerCodeHeader="";
            $voucherTypeHeader="";
            $getPercentHeader="";
            $gstNumberHeader="";
            // $panNumberHeader="";
            $quantityHeader="";
            $valueHeader="";
            $grossTotalHeader="";
            $cgstHeader="";
            $sgstHeader="";

            for ($row = 1; $row <= $highestRow; ++$row) {
                $cnt++;
                for($i=1;$i<=$highestColumnIndex;$i++){
                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Date"){
                        $billDateHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Particulars"){
                        $retailerCodeHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Voucher Type"){
                        $voucherTypeHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Voucher No."){
                        $billNumberHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="GST %"){
                        $getPercentHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="GSTIN/UIN"){
                        $gstNumberHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Quantity"){
                        $quantityHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Value"){
                        $valueHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Gross Total"){
                        $grossTotalHeader= $i;
                        $total=$cnt;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="CGST"){
                        $cgstHeader= $i;
                    }

                    if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="SGST"){
                        $sgstHeader= $i;
                    }
                }
            }
            if((empty($billDateHeader) || empty($cgstHeader) || empty($sgstHeader) || empty($billNumberHeader) || empty($retailerCodeHeader))){
                echo "Source file not in correct order. Please select correct files for uploading.a";
                exit;
            }
            if((empty($voucherTypeHeader) || empty($getPercentHeader) || empty($gstNumberHeader) || empty($quantityHeader) || empty($valueHeader) || empty($grossTotalHeader))){
                echo "Source file not in correct order. Please select correct files for uploading.a";
                exit;
            }
        }
    }

    //Import Havells Bills for Tally bills
    public function havellsElectricsExcelUploading($billFilePath){
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($billFilePath);
        $reader->setReadDataOnly(TRUE);
        $objPHPExcel = $reader->load($billFilePath);

        $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
        $highestRow = $worksheet->getHighestRow(); // e.g. 12
        $highestColumn = $worksheet->getHighestColumn(); // e.g M'

        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7

        $cnt=0;
        $total="";

        $billDateHeader="";
        $billNumberHeader="";
        $retailerCodeHeader="";
        $voucherTypeHeader="";
        $getPercentHeader="";
        $gstNumberHeader="";
        // $panNumberHeader="";
        $quantityHeader="";
        $valueHeader="";
        $grossTotalHeader="";
        $cgstHeader="";
        $sgstHeader="";

        for ($row = 1; $row <= $highestRow; ++$row) {
            $cnt++;
            for($i=1;$i<=$highestColumnIndex;$i++){
                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Date"){
                    $billDateHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Particulars"){
                    $retailerCodeHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Voucher Type"){
                    $voucherTypeHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Voucher No."){
                    $billNumberHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="GST %"){
                    $getPercentHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="GSTIN/UIN"){
                    $gstNumberHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Quantity"){
                    $quantityHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Value"){
                    $valueHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="Gross Total"){
                    $grossTotalHeader= $i;
                    $total=$cnt;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="CGST"){
                    $cgstHeader= $i;
                }

                if($worksheet->getCellByColumnAndRow($i, $row)->getValue()==="SGST"){
                    $sgstHeader= $i;
                }
            }
        }

        if((empty($billDateHeader) || empty($cgstHeader) || empty($sgstHeader) || empty($billNumberHeader) || empty($retailerCodeHeader))){
            echo "Source file not in correct order. Please select correct files for uploading.a";
            exit;
        }
        if((empty($voucherTypeHeader) || empty($getPercentHeader) || empty($gstNumberHeader) || empty($quantityHeader) || empty($valueHeader) || empty($grossTotalHeader))){
            echo "Source file not in correct order. Please select correct files for uploading.a";
            exit;
        }

        for ($row = ($total+1); $row <= $highestRow; ++$row) {
            $billDate = trim($worksheet->getCellByColumnAndRow($billDateHeader, $row)->getValue());
            $retailerName = trim($worksheet->getCellByColumnAndRow($retailerCodeHeader, $row)->getValue());
            $voucherType = trim($worksheet->getCellByColumnAndRow($voucherTypeHeader, $row)->getValue());
            $billNo = trim($worksheet->getCellByColumnAndRow($billNumberHeader, $row)->getValue());
            $gstPercent = trim($worksheet->getCellByColumnAndRow($getPercentHeader, $row)->getValue());
            $gstNo = trim($worksheet->getCellByColumnAndRow($gstNumberHeader, $row)->getValue());
            // $panNo = trim($worksheet->getCellByColumnAndRow($panNumberHeader, $row)->getValue());
            $quantity = trim($worksheet->getCellByColumnAndRow($quantityHeader, $row)->getValue());
            $amoutWithoutTax = trim($worksheet->getCellByColumnAndRow($valueHeader, $row)->getValue());
            $netAmount = trim($worksheet->getCellByColumnAndRow($grossTotalHeader, $row)->getValue());

            
            $cgst = trim($worksheet->getCellByColumnAndRow($cgstHeader, $row)->getValue());
            $sgst = trim($worksheet->getCellByColumnAndRow($sgstHeader, $row)->getValue());

            if($cgst==""){
                $cgst=0;
            }

            if($sgst==""){
                $sgst=0;
            }
                
            $panNo="";
            $excelDate="";
            
            if($retailerName !== "Grand Total"){
                if(($billDate !=="") && ($retailerName !== "(cancelled)")){
                    if(!empty($billDate) && $billDate !=='Bill Date'){
                        $billDate =str_replace("/","-",$billDate);
                        $date = ($billDate - 25569) * 86400;
                        $excelDate=date('Y-m-d', $date);//convert date from excel data

                        $billExist=$this->ExcelModel->getBillByLastRecords('bills',$billNo);

                        // $pan="";
                        $salesmanName="";
                        $routeName="";
                        $routeCode="";
                        if($panNo==""){
                            $retailerCount=$this->ExcelModel->getdata('retailer');

                            $retailerDataExist=$this->ExcelModel->getDataByName('retailer',$retailerName);
                            if(empty($retailerDataExist)){
                                $panNo="RETNO1000".count($retailerCount);
                                $routeCode="NOROUTE";
                                $routeName="NO ROUTE";
                            }else{
                                $panNo=$retailerDataExist[0]['code'];
                                $salesmanName=$retailerDataExist[0]['salesmanName'];
                                $routeName=$retailerDataExist[0]['routeName'];
                            }
                        }

                        if(empty($billExist)){
                            $arrayRes=array(
                                'date'=>$excelDate,
                                'billNo'=>$billNo,
                                'deliveryStatus'=>'delivered',
                                'retailerCode'=>$panNo,
                                'retailerName'=>$retailerName,
                                'salesman'=>$salesmanName,
                                'routeName'=>$routeName,
                                'routeCode'=>$routeCode,
                                'grossAmount'=>$amoutWithoutTax,
                                'taxAmount'=>($cgst+$sgst),
                                'billNetAmount'=>round($netAmount),
                                'netAmount'=>round($netAmount),
                                'pendingAmt'=>round($netAmount),
                                'compName'=>'Havells',
                                'invoiceType'=>$voucherType,
                                'insertedAt'=>date('Y-m-d H:i:sa')
                            );
                            $this->ExcelModel->insert('bills',$arrayRes);
                        }else{
                            $billId=$billExist[0]['id'];
                            $billExist=$this->ExcelModel->load('bills',$billId);
                        
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
                            if(($billNetAmount != $billPendingAmt)){
                                $totalRecAmt=$billSrAmt+$billReceivedAmt+$billCd+$billDebit+$billOfficeAdjustment+$billOtherAdjustment;
                                $newPendingAmt=$netAmount-$totalRecAmt;
                                if((!empty($excelDate)) && ($excelDate != "1970-01-01")){
                                    $data = array(
                                        'date'=>$excelDate,
                                        'billNo'=>$billNo,
                                        'retailerCode'=>$panNo,
                                        'retailerName'=>$retailerName,
                                        'salesman'=>$salesmanName,
                                        'routeName'=>$routeName,
                                        'routeCode'=>$routeCode,
                                        'grossAmount'=>$grossAmount,
                                        'taxAmount'=>($cgst+$sgst),
                                        'billNetAmount'=>round($netAmount),
                                        'netAmount'=>round($netAmount),
                                        'pendingAmt'=>round($newPendingAmt),
                                        'compName'=>'Havells',
                                        'invoiceType'=>$voucherType,
                                        'insertedAt'=>date('Y-m-d H:i:sa')
                                    );
                                    $this->ExcelModel->update('bills',$data,$billId);
                                }
                            }
                        }

                        $retailerDetails=$this->ExcelModel->retailerInfo('retailer',$retailerName,$panNo);
                        if(empty($retailerDetails)){
                            $retailerData=array(
                                'code'=>$panNo,
                                'name'=>$retailerName,
                                'gstIn'=>$gstNo,
                                'company'=>'Havells'
                            );
                            $this->ExcelModel->insert('retailer',$retailerData);
                        }

                        $routeDetails=$this->ExcelModel->routeInfo('route','NO ROUTE','NOROUTE');
                        if(empty($routeDetails)){
                            $routeData=array(
                                'code'=>'NOROUTE',
                                'name'=>'NO ROUTE',
                                'company'=>'Havells'
                            );
                            $this->ExcelModel->insert('route',$routeData);
                        }
                    }
                }else if(($billDate !=="") && ($retailerName == "(cancelled)")){
                    $arrayRes=array(
                        'date'=>$excelDate,
                        'billNo'=>$billNo,
                        'deliveryStatus'=>'cancelled',
                        'compName'=>'Havells',
                        'invoiceType'=>$voucherType,
                        'insertedAt'=>date('Y-m-d H:i:sa')
                    );
                    $this->ExcelModel->insert('bills',$arrayRes);
                }
            }
        }

        $billId=0;
        //upload bill details value
        for ($row = ($total+1); $row <= $highestRow; ++$row) {
            $billDate = $worksheet->getCellByColumnAndRow($billDateHeader, $row)->getValue();
            $billNo = $worksheet->getCellByColumnAndRow($billNumberHeader, $row)->getValue();
            $excelDate="";

            if($billDate !==""){
                $billExist=$this->ExcelModel->getBillByLastRecords('bills',$billNo);
                // print_r($billExist);exit;
                if(!empty($billExist)){
                    $billId=$billExist[0]['id'];
                }
            }

            if($billDate ==""){
                $itemName = trim($worksheet->getCellByColumnAndRow($retailerCodeHeader, $row)->getValue());
                $gstPercent = trim($worksheet->getCellByColumnAndRow($getPercentHeader, $row)->getValue());
                $quantity = trim($worksheet->getCellByColumnAndRow($quantityHeader, $row)->getValue());
                $amoutWithoutTax = trim($worksheet->getCellByColumnAndRow($valueHeader, $row)->getValue());
                if($itemName != "Grand Total"){
                    $checkItemDetails=$this->ExcelModel->checkBillDetailsData('billsdetails',$billId,$itemName,$gstPercent,$quantity);
                    if(empty($checkItemDetails)){
                        if($gstPercent==""){
                            $rateWithGst=(($amoutWithoutTax/100)*0);
                            $productArray=array(
                                'billId'=>$billId,
                                'productName'=>$itemName,
                                'gstPercent'=>$gstPercent,
                                'qty'=>$quantity,
                                'netAmount'=>($amoutWithoutTax+$rateWithGst),
                                'taxableValue'=>$amoutWithoutTax,
                                'taxAmount'=>$rateWithGst
                            );
                            $this->ExcelModel->insert('billsdetails',$productArray);
                        }else{
                            $rateWithGst=(($amoutWithoutTax/100)*$gstPercent);
                            $productArray=array(
                                'billId'=>$billId,
                                'productName'=>$itemName,
                                'gstPercent'=>$gstPercent,
                                'qty'=>$quantity,
                                'netAmount'=>($amoutWithoutTax+$rateWithGst),
                                'taxableValue'=>$amoutWithoutTax,
                                'taxAmount'=>$rateWithGst,
                                'taxPercent'=>$gstPercent
                            );
                            $this->ExcelModel->insert('billsdetails',$productArray);
                        }
                    }
                }
            }
            
        }

        $updateData=array('status'=>2);
        $this->ExcelModel->updateByCompany('cron_settings',$updateData,'Havells');
    }

}