<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
class EmployeeController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('EmployeeModel');
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

    public function employeeData(){
        $this->load->view('employeeUploadingView');
    }
   
    public function checkValuesByMobile(){
        $mobile=trim($this->input->post('mobile'));
        $exists = $this->EmployeeModel->loadUserByMobile('employee',$mobile);
        if(empty($exists)) {
            echo "";
        } else {
            echo "Mobile Number already present.";
        }
    }

    public function addDistributors(){
         
    }

    

    public function checkValuesByUserName(){
        $logId=trim($this->input->post('logId'));
        
        $exists = $this->EmployeeModel->loadUserByUserName('employee',$logId);
        if(empty($exists)) {
            echo "";
        } else {
            echo "Username already present.";
        }
    }

    public function index()
    {
        $data['cpage']='dtPage';
        $data['employee']=$this->EmployeeModel->getdataActiveForDetail('employee');
        // $data['empLedger']=$this->EmployeeModel->getEmpLedgerForDetail('employee');
        $balance=0;
        $no=0;
        $balanceArr=array();
        $empBalArr=array();
       
        foreach($data['employee'] as $itm){
          
            $ledger=$this->EmployeeModel->getEmpLedgerByEmp('emptransactions',$itm['id']);
            if(!empty($ledger)){
                foreach($ledger as $leg){
                    if($leg['transactionType']=='cr'){
                        $balance=$balance+$leg['amount'];
                    }else if($leg['transactionType']=='dr'){
                         $balance=$balance-$leg['amount'];
                    }else{
                        $balance=0;
                    }
                }
                $balanceArr[$no]=$balance;
                // $empBalArr[$no]=$itm['empId'];
            }else{
                // $empBalArr[$no]=$itm['empId'];
                $balanceArr[$no]=0;
            }
            $balance=0;
             $no++;
        }

        $data['empBalId']=$empBalArr;
        $data['balance']=$balanceArr;

        // print_r($data['balance']);exit;
        $data['employee']=$this->EmployeeModel->getdataActiveForDetail('employee');
        $this->load->view('Manager/employeeDetailsView',$data);
    }


    public function getNonSalaryEmployee()
    {
        $data['employee']=$this->EmployeeModel->getNonSalaryEmployeeForDetail('employee');
        // $data['empLedger']=$this->EmployeeModel->getEmpLedgerForDetail('employee');
        $balance=0;
        $no=0;
        $balanceArr=array();
       
        foreach($data['employee'] as $itm){
          
            $ledger=$this->EmployeeModel->getEmpLedgerByEmp('emptransactions',$itm['id']);
            if(!empty($ledger)){
                foreach($ledger as $leg){
                    if($leg['transactionType']=='cr'){
                        $balance=$balance+$leg['amount'];
                    }else if($leg['transactionType']=='dr'){
                         $balance=$balance-$leg['amount'];
                    }else{
                        $balance=0;
                    }
                }
                  $balanceArr[$no]=$balance;
            }else{
                 
                $balanceArr[$no]=0;
            }
            $balance=0;
             $no++;
        }
       
        $data['balance']=$balanceArr;

        // print_r($data['balance']);exit;
        $data['employee']=$this->EmployeeModel->getNonSalaryEmployeeForDetail('employee');
        $this->load->view('Manager/nonSalaryEmployeeView',$data);
    }

    public function inactiveEmployee()
    {
        $data['employee']=$this->EmployeeModel->getdataDeactive('employee');
        $this->load->view('Manager/inactiveEmployeeDetailsView',$data);
    }

    public function employeeClearance()
    {
        $empName=trim($this->input->post('employee'));
        $empId=trim($this->input->post('eid'));
        $billsData=array();
        if($empName ==="" && $empId ===""){
            $data['bills']=array();
            $data['billProvisional']=array();
        }else{
            // $data['bills']=$this->EmployeeModel->getEmployeeBills('allocations',$empId);
            // $data['billProvisional']=$this->EmployeeModel->getEmployeeProvisitionalBills('bills',$empId);

            $data['bills']=$this->EmployeeModel->loadOutstandingBills('bills',$empId);
            $data['billsall']=$this->EmployeeModel->loadSalesmanOutstandingBills('bills',$empId);

            $bills=(array_merge($data['bills'],$data['billsall']));
            $bills = array_values(array_map("unserialize", array_unique(array_map("serialize", $bills))));

            $data['billProvisional']=$this->EmployeeModel->loadEmployeeProvisitionalBills('bills',$empId);
            $bills=(array_merge($data['billProvisional'],$bills));
            $bills = array_values(array_map("unserialize", array_unique(array_map("serialize", $bills))));

            $data['billDelivery']=$this->EmployeeModel->getEmployeeDeliveryBills('bills',$empName);
            $bills=(array_merge($data['billDelivery'],$bills));
            $bills = array_values(array_map("unserialize", array_unique(array_map("serialize", $bills))));
            // echo count($bills);exit;
            // $bills=array_unique($bills);
            $no=0;
            if(!empty($bills)){
                foreach($bills as $billItem){
                    $billsData[$no]=$billItem;
                    $no++;
                }
            }

            // echo count($billsData);exit;

            // if(!empty($data['billsall'])){
            //     foreach($data['billsall'] as $billItem){
            //         $billsData[$no]=$billItem;
            //         $no++;
            //     }
            // }

            // if(!empty($data['billProvisional'])){
            //     foreach($data['billProvisional'] as $billItem){
            //         $billsData[$no]=$billItem;
            //         $no++;
            //     }
            // }

            // if(!empty($data['billDelivery'])){
            //     foreach($data['billDelivery'] as $billItem){
            //         $billsData[$no]=$billItem;
            //         $no++;
            //     }
            // }
        }
        
        $data['bills']=$billsData;
       
        // $this->employeeHistoryInformation($bills,$billProvisional);

        $data['employee']=$this->EmployeeModel->getdataActive('employee');
        $data['emp']=$this->EmployeeModel->getdataActive('employee');
        $this->load->view('employeeClearanceView',$data);
    }

    public function add()
    {
        $emp=$this->EmployeeModel->getLastEntry('employee');
        $data['role']=$this->EmployeeModel->getdata('role');     

        $data['proof']=$this->EmployeeModel->getdata('proof_details');
        $data['empCount']="";
        $username="";
        $empCount="";
        if(!empty($emp)){
            $data['empCount']=date('y').($emp[0]['id']+1);
            $empCount=($emp[0]['id']+1);
            $username="fname.lname".$empCount;
            $data['userEmail']=$username;
        }else{
            $data['empCount']=date('y').(count($emp)+1);
            $empCount=count($emp);
            $username="fname.lname".$empCount;
            $data['userEmail']=$username;
        }
        
        $data['company']=$this->EmployeeModel->getdata('company');
        $this->load->view('Manager/addEmployeeForManagerView',$data);
    }

    public function employeeLedger()
    {
        // $data['empLedger']=$this->EmployeeModel->getEmpLedger('employee');
        $data['cpage']='legPage';
         $data['empLedger']=$this->EmployeeModel->getdataActiveForDetail('employee');
        $balance=0;
        $no=0;
        $balanceArr=array();
       
        foreach($data['empLedger'] as $itm){
          
            $ledger=$this->EmployeeModel->getEmpLedgerByEmp('emptransactions',$itm['id']);
            if(!empty($ledger)){
                foreach($ledger as $leg){
                    if($leg['transactionType']=='cr'){
                        $balance=$balance+$leg['amount'];
                    }else if($leg['transactionType']=='dr'){
                         $balance=$balance-$leg['amount'];
                    }
                }
                  $balanceArr[$no]=$balance;
            }else{
                $balanceArr[$no]=0;
            }
            $balance=0;
             $no++;
        }

        $data['balance']=$balanceArr;
        $this->load->view('EmployeeLedgerView',$data);
    } 

    public function employeeLedgerByEmp($id)
    {

        $from_date=$this->input->post('from_date');
        $to_date=$this->input->post('to_date');
        
        
        if($from_date=='' && $to_date==''){
            $time = strtotime("-1 year", time());
            $todate = date("Y-m-d", $time);
            $fromdate=date('Y-03-01');
            // $todate=date('Y-04-01');
            $data['dtCheck']="no";
            $data['fromdate']=$todate;
            $data['todate']=date('Y-m-d');
            $data['empId']=$id;
            $data['emp']=$this->EmployeeModel->load('employee',$id);
            $data['opening']=$this->EmployeeModel->getEmpLedgerByEmpDate('emptransactions',$id,$todate,$todate);
            $data['closing']=$this->EmployeeModel->getEmpLedgerById('emptransactions',$id);
            // $data['closing']=$this->EmployeeModel->getClosingLedgerById('emptransactions',$id,$todate);
            $data['empLedger']=$this->EmployeeModel->getEmpLedgerByEmp('emptransactions',$id);

            $this->load->view('EmployeeLedgerDetailsView',$data);
        }else{
            $from_date=$this->input->post('from_date');
            $to_date=$this->input->post('to_date');
            $fromdate=$from_date;
            $todate=$to_date;
            $data['dtCheck']="yes";
            $data['fromdate']=$fromdate;
            $data['todate']=$todate;
            $data['empId']=$id;
            $data['emp']=$this->EmployeeModel->load('employee',$id);
            $data['opening']=$this->EmployeeModel->getEmpLedgerByEmpDate('emptransactions',$id,$fromdate,$todate);
            // print_r($data['opening']);exit;
            
            $data['closing']=$this->EmployeeModel->getClosingLedgerById('emptransactions',$id,$todate);
            // $data['closing']=$this->EmployeeModel->getEmpLedgerById('emptransactions',$id);

            $data['empLedger']=$this->EmployeeModel->getEmpLedgerByEmpDates('emptransactions',$id,$fromdate,$todate);

            $this->load->view('EmployeeLedgerDetailsView',$data);
        }
        
    } 

    public function Deactive(){
        $data['employee']=$this->EmployeeModel->getdataDeactive('employee');
        $this->load->view('Manager/EmployeeView',$data);
    }

    public function generatePassword($_len){
        $_numerics   = '1234567890';                           
        $_container = $_numerics;   
        $password = '';  
        for($i = 0; $i < $_len; $i++) {          
            $_rand = rand(0, strlen($_container) - 1);                 
            $password .= substr($_container, $_rand, 1);              
        }
        return $password;       
    }

    public function sendApprovalSms($id,$designation,$username,$empMobile){
        $password=$this->generatePassword(4);

        $pass=md5($password);
        $upData=array('password'=>$pass);

        $this->EmployeeModel->update('employee',$upData,$id);

        $companyDetails=$this->EmployeeModel->getdata('office_details');
        $officeName=$companyDetails[0]['distributorName'];
        $distributorCode=$companyDetails[0]['distributorCode'];

        $employeeDetails=$this->EmployeeModel->load('employee',$id);
        $employeeMobile=$employeeDetails[0]['mobile'];
        $employeeName=$employeeDetails[0]['name'];
        $employeeDesignation=$employeeDetails[0]['designation'];
        $transactionDate=date('M d, Y H:i a');

        $office=$this->EmployeeModel->load('office_details','1');
        $jsonData=array(
            "flow_id"=>"618d05c15ce85c71050c8967",
            "sender"=>"SIAInc",
            "mobiles"=>'91'.$employeeMobile,
            "Distributorname"=>$office[0]['distributorName'],
            "distributorcode"=>$office[0]['distributorCode'],
            "username"=>$username,
            "password"=>$password,
            "designation"=>ucfirst($employeeDesignation),
            "link"=>"https://rb.gy/rad9y6"
        );

            // "link"=>"https://play.google.com/store/apps/details?id=com.smartdistributor"

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
    
    public function insert() {
        $loginId = $this->session->userdata[$this->projectSessionName]['id'];
        $checkLogin=$this->EmployeeModel->load('employee',$loginId);
       
        $empApproval=0;
        if(!empty($checkLogin)){
            if($checkLogin[0]['designation']=='owner'){
                $empApproval=1;
                // echo "yetete ";
            }
        }

        $pre_role_code=$this->EmployeeModel->getdata('emp_code');
        $designationData=$this->input->post('designation');
        $role_data="";
        foreach($designationData as $role){
            $role_data=$role_data.','.$role;
        }

        $parts=explode(",",$role_data);
        $parts=array_filter($parts);
        $role_data=(implode(",",$parts));

        $salStatus=1;
        $logStatus=1;

        $fileName = trim($this->uploadFile('idProofImage'));   
        $fileName1 = trim($this->uploadFile('addrProofImage'));
        $fileName2 = trim($this->uploadFile('profileImage'));
        $str= $this->input->post('FirstName');
        $str1= $this->input->post('lastName');
        $array = array($str,$str1);
        $name =implode(" ",$array);
        $cd=$this->input->post('code');
       
        $username=$this->input->post('logId');
        $userMobile=$this->input->post('mobile');

        $code=$pre_role_code[0]['name'].''.$cd;
        // echo $code;exit;
        $data = array(
            'code' => $code,
            'name' => $name,
            'email'=>$this->input->post('logId'),
            'mobile' => $this->input->post('mobile'),
            'designation' => $role_data,
            'idProofName' =>$this->input->post('idProofName'),
            'idProofImage' => $fileName,                    
            'addrProofName' => $this->input->post('addrProofName'),
            'addrProofImage' => $fileName1,
            'profileImage' =>  $fileName2,
            'joiningDate' => date("y-m-d"),
            'salary' => $this->input->post('salary'),
            'status' => 1,
            'isSalaryEmp' => $salStatus,
            'isLoginEmp' => $logStatus,
            'ownerApproval' => $empApproval,
            'remark' => $this->input->post('remark'),
            'companyId' => $this->input->post('companyId')          
        ); 
        $result=$this->EmployeeModel->insert('employee',$data); 
       
        if($this->db->affected_rows() >0){ 
            if($empApproval==1){
                $insert_id = $this->db->insert_id();  
                $this->sendApprovalSms($insert_id,$role_data,$username,$userMobile);   
            }
                    
            return redirect("manager/EmployeeController");
        } else {
            return redirect("manager/EmployeeController");
        }
    }

    public function addNonSalaryEmployee(){
        $fname=trim($this->input->post('firstName'));
        $lname=trim($this->input->post('lastName'));
        $mobile=trim($this->input->post('mobile'));
        $name=$fname.' '.$lname;

        $loginId = $this->session->userdata[$this->projectSessionName]['id'];
        $checkLogin=$this->EmployeeModel->load('employee',$loginId);
        // $empApproval=0;
        // if(!empty($checkLogin)){
        //     if($checkLogin[0]['designation']=='owner'){
        //         $empApproval=1;
        //     }
        // }

        $emp=$this->EmployeeModel->getLastEntry('employee');
        $pre_role_code=$this->EmployeeModel->getdata('emp_code');
        $code="";
        if(!empty($emp)){
            // $data['empCount']=$emp[0]['id']+1;
            $empCodeData=($emp[0]['id']+1);
            $code=$pre_role_code[0]['name'].date('y').$empCodeData;
        }else{
            $code=$pre_role_code[0]['name'].date('y').(count($emp)+1);
        }
        // echo $code;exit;
        
        $data=array('name'=>$name,'mobile'=>$mobile,'code'=>$code,'ownerApproval'=>1,'status'=>1,'joiningDate'=>date('Y-m-d'));
        $this->EmployeeModel->insert('employee',$data);
        redirect("manager/EmployeeController");
    }

    public function updateNonSalaryEmployee(){
        $id=trim($this->input->post('nonLoginId'));
        $fname=trim($this->input->post('firstName'));
        $lname=trim($this->input->post('lastName'));
        $mobile=trim($this->input->post('mobile'));
        $name=$fname.' '.$lname;

        $loginId = $this->session->userdata[$this->projectSessionName]['id'];
        
        // $emp=$this->EmployeeModel->getdata('employee');
        $emp=$this->EmployeeModel->getLastEntry('employee');
        $pre_role_code=$this->EmployeeModel->getdata('emp_code');

        $empCount="";
        if(!empty($emp)){
            $empCount=date('y').($emp[0]['id']+1);
        }else{
            $empCount=date('y').(count($emp)+1);
        }
        $code=$pre_role_code[0]['name'].''.$empCount;
        
        $data=array('name'=>$name,'mobile'=>$mobile,'ownerApproval'=>1,'status'=>1,'joiningDate'=>date('Y-m-d'));
        $this->EmployeeModel->update('employee',$data,$id);
        redirect("manager/EmployeeController");
    }

     public function uploadFile($fileName) {
        $upload_path='./assets/uploads/'; 
        $config = array(
        'upload_path' => $upload_path,
        'allowed_types' => "gif|jpg|png|jpeg"
        //'overwrite' => TRUE
        /*'max_size' => "2048000", 
        'max_height' => "768",
        'max_width' => "1024"*/
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

      public function load($id) 
    {
        $emp=$this->EmployeeModel->getdata('employee');
        $data['empCount']=count($emp);
         $data['proof']=$this->EmployeeModel->getdata('proof_details');
        $data['company']=$this->EmployeeModel->getdata('company');
        $data['role']=$this->EmployeeModel->getdata('role');
        $data['employee']=$this->EmployeeModel->getEmpDetails('employee', $id);
        $this->load->view('Manager/addEmployeeForManagerView',$data);
    }

  
    public function update() {
        $id = $this->input->post('id');
        $details=$this->EmployeeModel->load('employee',$id);
        $profileImg=$details[0]['profileImage'];
        $idImg=$details[0]['idProofImage'];
        $addressImg=$details[0]['addrProofImage'];

        $role_data="";
        $designationData=$this->input->post('designation');
        if(empty($designationData)){
            $role_data=$details[0]['designation'];
        }else{
            foreach($designationData as $role){
                $role_data=$role_data.','.$role;
            }
            $parts=explode(",",$role_data);
            $parts=array_filter($parts);
            $role_data=(implode(",",$parts));
        }
        
        $img_profile="";
        $img_id="";
        $img_address="";

        $fileName = trim($this->uploadFile('idProofImage'));   
        $fileName1 = trim($this->uploadFile('addrProofImage'));
        $fileName2 = trim($this->uploadFile('profileImage'));

        if($fileName==""){
            $img_id=$details[0]['idProofImage'];
        }else{
            $img_id=$fileName;
        }

        if($fileName1==""){
            $img_address=$details[0]['addrProofImage'];
        }else{
            $img_address=$fileName1;
        }

        if($fileName2==""){
            $img_profile=$details[0]['profileImage'];
        }else{
            $img_profile=$fileName2;
        }
        
        $str= $this->input->post('FirstName');
        $str1= $this->input->post('lastName');
        $array = array($str,$str1);
        $name =implode(" ",$array);
        $data = array
                (
                'idProofName' =>$this->input->post('idProofName'),
                'addrProofName' => $this->input->post('addrProofName'),
                'designation' => $role_data,
                'salary' => $this->input->post('salary'),
                'remark' => $this->input->post('remark'),
                'companyId' => $this->input->post('companyId'), 
                'idProofImage' => $img_id,                    
                'addrProofImage' => $img_address,
                'profileImage' =>  $img_profile        
                 );  
            $this->EmployeeModel->update('employee',$data, $id);
            if($this->db->affected_rows()>0){
                redirect("manager/EmployeeController");
            } else {
                redirect("manager/EmployeeController");
            }
    }


    public function updateEmployeeMobile(){
        $empId=trim($this->input->post('updateMobileEmpId'));
        $mobile=trim($this->input->post('updatemobile'));
        $exists = $this->EmployeeModel->loadUserByMobile('employee',$mobile);
        if(empty($exists)) {
            $data=array('mobile'=>$mobile);
            $this->EmployeeModel->update('employee',$data,$empId); 
            redirect("manager/EmployeeController"); 
        } else {
            $this->session->set_flashdata('item', array('message' => 'Mobile Number already present.','class' => 'success'));
            redirect("manager/EmployeeController");
        }
    }

    public function updateEmployeeUsername(){
        $empId=trim($this->input->post('updateEmpId'));
        $username=trim($this->input->post('updatelogId'));
        $updateFirstName=trim($this->input->post('updateFirstName'));
        $updatelastName=trim($this->input->post('updatelastName'));
        
        $name=$updateFirstName.' '.$updatelastName;
        
        $data=array('email'=>$username,'name'=>$name);

        $this->EmployeeModel->update('employee',$data,$empId);  
        redirect("manager/EmployeeController");
    }

    public function delete()
    {
        $id =$this->input->post('id');
        $this->EmployeeModel->delete('employee',$id);  
        if($this->db->affected_rows()>0){
            echo "Your record has been deleted!";                
        }else{
            echo "Deleted Fail..";
        }
    } 
     public function updateStatus($id, $status) {
        if($status==1) {
            $data = array('status' => 0);
        } else if($status==0){
            $data = array('status' => 1);
        } else if($status==2){
            $data = array('status' => 2);
        }
        $this->EmployeeModel->update('employee',$data, $id);
        if($this->db->affected_rows()>0){
           redirect("manager/EmployeeController");
        } else {
           redirect("manager/EmployeeController");
        }
    }

     public function updateDeleteStatus($id) {
        $data = array('isDeleted' => 1);
        
        $this->EmployeeModel->update('employee',$data, $id);
        if($this->db->affected_rows()>0){
           redirect("manager/EmployeeController");
        } else {
           redirect("manager/EmployeeController");
        }
    }

    public function retailerHistoryInfo(){
        $empName=trim($this->input->post('empName'));
        $empId=trim($this->input->post('empId'));
        $bills=$this->EmployeeModel->getEmployeeBills('allocations',$empId);
        $billProvisional=$this->EmployeeModel->getEmployeeProvisitionalBills('bills',$empId);
        $this->employeeHistoryInformation($bills,$billProvisional);

    }

    public function employeeHistoryInformation($bills,$billProvisional){
        if(!empty($bills)){
            foreach ($bills as $data) 
            {
                $resendBills=$this->EmployeeModel->getRowCount('allocationsbills',$data['id'],'isResendBill');
                $lostBills=$this->EmployeeModel->getRowCount('allocationsbills',$data['id'],'isLostBill');
                $lostChequesBills=$this->EmployeeModel->getRowCount('allocationsbills',$data['id'],'isLostCheque');
                $pendingNeftBills=$this->EmployeeModel->getRowCount('allocationsbills',$data['id'],'isPendingNeft');

                $bouncedBill=$this->EmployeeModel->checkBouncedBill('billpayments',$data['id']);

                $dt=date_create($data['date']);
                $createdDate = date_format($dt,'d-M-Y');
                $style="";
                if($data['isAllocated']==1){ 
                    $style="background-color: #dcd6d5";
                }

            ?>
            <tr style="<?php echo $style; ?>">
            
                <td>
            <?php 
                if($data['creditNoteRenewal']>0){ echo '<span class="logo_prov">CN</span>'; }
                if($resendBills>0){ echo '<span class="logo_prov">RB</span>'; }
                if($lostBills>0){ echo '<span class="logo_prov">LB</span>'; }
                if($lostChequesBills>0){ echo '<span class="logo_prov">LC</span>'; }
                if($pendingNeftBills>0){ echo '<span class="logo_prov">PN</span>'; }
        
            ?>
                </td>
                <td><?php echo $data['billNo']; ?></td>
                <td><?php echo $createdDate; ?></td>
                <td><?php echo $data['salesman']; ?></td>
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
                    <?php if($data['isAllocated']!=1){ ?>
                      <a id="prDetails" href="javascript:void()" data-id="<?php echo $data['id']; ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>" data-gst="<?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?>" data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-toggle="modal" data-target="#processModal"><button class="btn btn-xs btn-primary waves-effect"><i class="material-icons">touch_app</i></button></a>
                      <a target="_blank" href="<?php echo site_url('AdHocController/billHistoryInfo/'.$data['id']); ?>" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="View History"><i class="material-icons">info</i></a>
                    <a target="_blank" href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs  btn-primary" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                                       
                  <?php }else{
                    $allocations=$this->EmployeeModel->getAllocationDetailsByBill('bills',$data['id']);

                    $officeAllocations=$this->EmployeeModel->getOfficeAllocationDetailsByBill('bills',$data['id']);
                                              
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
       
        if(!empty($billProvisional)){
            foreach ($billProvisional as $data) 
            {
                $resendBills=$this->EmployeeModel->getRowCount('allocationsbills',$data['id'],'isResendBill');
                $lostBills=$this->EmployeeModel->getRowCount('allocationsbills',$data['id'],'isLostBill');
                $lostChequesBills=$this->EmployeeModel->getRowCount('allocationsbills',$data['id'],'isLostCheque');
                $pendingNeftBills=$this->EmployeeModel->getRowCount('allocationsbills',$data['id'],'isPendingNeft');
                $bouncedBill=$this->EmployeeModel->checkBouncedBill('billpayments',$data['id']);
                $dt=date_create($data['date']);
                $createdDate = date_format($dt,'d-M-Y');
                
                $style="";
                if($data['isAllocated']==1){ 
                    $style="background-color: #dcd6d5";
                }

            ?>
            <tr style="<?php echo $style; ?>">
                <td>
                    <?php 
                        if($data['creditNoteRenewal']>0){ echo '<span class="logo_prov">CN</span>'; }
                        if($resendBills>0){ echo '<span class="logo_prov">RB</span>'; }
                        if($lostBills>0){ echo '<span class="logo_prov">LB</span>'; }
                        if($lostChequesBills>0){ echo '<span class="logo_prov">LC</span>'; }
                        if($pendingNeftBills>0){ echo '<span class="logo_prov">PN</span>'; }
                    ?>
                </td>
                <td><?php echo $data['billNo']; ?></td>
                <td><?php echo $createdDate; ?></td>
                <td><?php echo $data['salesman']; ?></td>
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
                if($data['isAllocated']!=1){ ?>
                    <a id="prDetails" href="javascript:void()" data-id="<?php echo $data['id']; ?>" data-billNo="<?php echo $data['billNo']; ?>" data-retailerName="<?php echo $data['retailerName']; ?>" data-gst="<?php if(!empty($retailerCode)){ echo $retailerCode[0]['gstIn']; } ?>" data-pendingAmt="<?php echo $data['pendingAmt']; ?>" data-toggle="modal" data-target="#processModal"><button class="btn btn-xs btn-primary waves-effect"><i class="material-icons">touch_app</i></button></a>
                    <a target="_blank" href="<?php echo site_url('AdHocController/billHistoryInfo/'.$data['id']); ?>" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="View History"><i class="material-icons">info</i></a>
                    <a target="_blank" href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs  btn-primary" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                                    
            <?php 
                }else{
                    $allocations=$this->EmployeeModel->getAllocationDetailsByBill('bills',$data['id']);

                    $officeAllocations=$this->EmployeeModel->getOfficeAllocationDetailsByBill('bills',$data['id']);
                                              
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

    public function uploadEmpSalaryAdvanceDetail(){

        $this->load->library('PHPExcel');
        $this->load->library("excel");

        $fileName = $_FILES['file']['name'];
        $fileName = str_replace(' ', '_', $fileName);
        // echo $fileName.'<br>';

        $config['upload_path'] = 'assets/uploads/';                             
        $config['file_name'] = $fileName;
        $config['overwrite'] = true;
        $config['allowed_types'] = 'xls|xlsx|csv';

        $this->load->library('upload');
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file')){
            // echo "hey";
            $this->upload->display_errors();
        }

        $media =  $fileName;
        $path = 'assets/uploads/'. $media;
        // echo $path.'<br>';

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

        for ($row = 6; $row <= $highestRow; $row++) {
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

            $employeeCode = $rowData[0][0];
            if(trim(strlen($employeeCode))==0){
                $employeeCode = "";
            }
            
            $employeeNewSalary = $rowData[0][6];
            if(trim(strlen($employeeNewSalary))==0){
                $employeeNewSalary = "";            
            }
            
            $employeeNewAdvanceDeduct = $rowData[0][8];
            if(trim(strlen($employeeNewAdvanceDeduct))==0){
                $employeeNewAdvanceDeduct = "";
            }

            $remark = $rowData[0][9];
            if(trim(strlen($remark))==0){
                $remark = "";
            }

            // echo $
            
            $updatedAt=date('Y-m-d H:i:sa');
            $userId = $this->session->userdata[$this->projectSessionName]['id'];
            if(!empty($employeeCode)){
                $empCheck=$this->EmployeeModel->checkEmpDetails('employee',$employeeCode);
                // print_r($empCheck);exit;
                if(!empty($empCheck)){
                    $empId=$empCheck[0]['id'];
                    if(!empty($employeeNewAdvanceDeduct)){
                        if(!empty($remark)){
                            $empDebit=array(
                                'empId'=>$empId,'transactionType'=>'cr','description'=>$remark,'amount'=>$employeeNewAdvanceDeduct,'createdAt'=>$updatedAt,'createdBy'=>$userId
                            );
                            $this->EmployeeModel->insert('emptransactions',$empDebit);//insert remark data
                        }else{
                            $remark="Entry as per File Uploaded on ".date('d-M-Y H:i:sa');
                            $empDebit=array(
                                'empId'=>$empId,'transactionType'=>'cr','description'=>$remark,'amount'=>$employeeNewAdvanceDeduct,'createdAt'=>$updatedAt,'createdBy'=>$userId
                            );
                            $this->EmployeeModel->insert('emptransactions',$empDebit);//insert remark data
                        }
                    }
                    
                    if(!empty($employeeNewSalary)){
                        $updData=array('salary'=>$employeeNewSalary);
                        $this->EmployeeModel->update('employee',$updData,$empId);
                    }
                   
                }
            }
            
            delete_files($path);
        }

        $this->session->set_flashdata('true', 'Advance Deductions and New Salary updated');
        $this->session->set_flashdata('err', "Advance Deductions and New Salary updated");
        
        redirect('manager/EmployeeController/employeeLedger');
    }
    
    // create xlsx
    public function createEmpDetails() {
        $empLedger=$this->EmployeeModel->getdataActiveForDetail('employee');
        $balance=0;
        $no=0;
        $balanceArr=array();

        foreach($empLedger as $itm){
          
            $ledger=$this->EmployeeModel->getEmpLedgerByEmp('emptransactions',$itm['id']);
            if(!empty($ledger)){
                foreach($ledger as $leg){
                    if($leg['transactionType']=='cr'){
                        $balance=$balance+$leg['amount'];
                    }else if($leg['transactionType']=='dr'){
                         $balance=$balance-$leg['amount'];
                    }
                }
                  $balanceArr[$no]=$balance;
            }else{
                $balanceArr[$no]=0;
            }
            $balance=0;
             $no++;
        }

        // load excel library
        $this->load->library('excel');
        // $listInfo = $this->export->exportList();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

        $date=date('d-M-Y');
        $bankDetails=$this->EmployeeModel->getdata('office_details');

        $objPHPExcel->getActiveSheet()->SetCellValue('A1', $bankDetails[0]['bankName']);
        $objPHPExcel->getActiveSheet()->SetCellValue('A2', $date);
        $objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Employee Salary and Advance Master File');
        
        $objPHPExcel->getActiveSheet()->SetCellValue('A5', 'Employee ID');
        $objPHPExcel->getActiveSheet()->SetCellValue('B5', 'First Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('C5', 'Last Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('D5', 'Company');
        $objPHPExcel->getActiveSheet()->SetCellValue('E5', 'Role');
        $objPHPExcel->getActiveSheet()->SetCellValue('F5', 'Current Salary');
        $objPHPExcel->getActiveSheet()->SetCellValue('G5', 'New Salary');
        $objPHPExcel->getActiveSheet()->SetCellValue('H5', 'Current Debit');
        $objPHPExcel->getActiveSheet()->SetCellValue('I5', 'Deduction');
        $objPHPExcel->getActiveSheet()->SetCellValue('J5', 'Remark');

        // set Row
        $no=0;
        $rowCount = 6;
        foreach ($empLedger as $element) {
            if(count($empLedger)>$no){
            
            $cmpname=$this->EmployeeModel->load('company',$element['companyId']);
            $companyName="";
            if(!empty($cmpname)){
                $companyName=$cmpname[0]['name'];
            } 
            $str=$element['name']; 
            $exploded=explode(" ",$str);
            $fName="";
            $lName="";
            if(!empty($exploded[0])){
                 $fName = $exploded[0];
            }
            
            if(!empty($exploded[1])){
                 $lName = $exploded[1];
            }       
            $bal="";
            
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $element['code']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $fName);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $lName);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $companyName);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $element['designation']);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $element['salary']);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, "");
            $debit=0;
            if($balanceArr[$no]<0){
                $debit=intval($balanceArr[$no]);
                $debit = str_replace('-', '', $debit);
                $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $debit);
            }else if($balanceArr[$no]>0){
                $debit=intval($balanceArr[$no]);
                $debit = '-'.$debit;
                $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $debit);
            }else{
                $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, "0");
            }
           
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, "");
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, "");

            $rowCount++;
            $no++;
            }
        }
       
        $filename = "EmployeeClearance". date("Y-m-d-H-i-s").".xls";
        header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0'); 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        // $objWriter->save('php://output'); 
    }

    public function exportDataToExcel(){
        $empLedger=$this->EmployeeModel->getdataActiveForDetail('employee');
        $balance=0;
        $no=0;
        $balanceArr=array();

        foreach($empLedger as $itm){
          
            $ledger=$this->EmployeeModel->getEmpLedgerByEmp('emptransactions',$itm['id']);
            if(!empty($ledger)){
                foreach($ledger as $leg){
                    if($leg['transactionType']=='cr'){
                        $balance=$balance+$leg['amount'];
                    }else if($leg['transactionType']=='dr'){
                         $balance=$balance-$leg['amount'];
                    }
                }
                  $balanceArr[$no]=$balance;
            }else{
                $balanceArr[$no]=0;
            }
            $balance=0;
             $no++;
        }

        $file="Employee_Details.xlsx";
        $newFileName= $file;

        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);

        foreach (range('A','J') as $col) {
           $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }

        $bankDetails=$this->EmployeeModel->getdata('office_details');


        $date=date('d-M-Y');
        $bankDetails=$this->EmployeeModel->getdata('office_details');

        $spreadsheet->getActiveSheet()->SetCellValue('A1', $bankDetails[0]['bankName']);
        $spreadsheet->getActiveSheet()->SetCellValue('A2', $date);
        $spreadsheet->getActiveSheet()->SetCellValue('A3', 'Employee Salary and Advance Master File');
        
        $spreadsheet->getActiveSheet()->SetCellValue('A5', 'Employee ID');
        $spreadsheet->getActiveSheet()->SetCellValue('B5', 'First Name');
        $spreadsheet->getActiveSheet()->SetCellValue('C5', 'Last Name');
        $spreadsheet->getActiveSheet()->SetCellValue('D5', 'Company');
        $spreadsheet->getActiveSheet()->SetCellValue('E5', 'Role');
        $spreadsheet->getActiveSheet()->SetCellValue('F5', 'Current Salary');
        $spreadsheet->getActiveSheet()->SetCellValue('G5', 'New Salary');
        $spreadsheet->getActiveSheet()->SetCellValue('H5', 'Current Debit');
        $spreadsheet->getActiveSheet()->SetCellValue('I5', 'Deduction');
        $spreadsheet->getActiveSheet()->SetCellValue('J5', 'Remark');

         // set Row
        $no=0;
        $rowCount = 6;
        foreach ($empLedger as $element) {
            if(count($empLedger)>$no){
            
            $cmpname=$this->EmployeeModel->load('company',$element['companyId']);
            $companyName="";
            if(!empty($cmpname)){
                $companyName=$cmpname[0]['name'];
            } 
            $str=$element['name']; 
            $exploded=explode(" ",$str);
            $fName="";
            $lName="";
            if(!empty($exploded[0])){
                 $fName = $exploded[0];
            }
            
            if(!empty($exploded[1])){
                 $lName = $exploded[1];
            }       
            $bal="";
            
            $spreadsheet->getActiveSheet()->SetCellValue('A' . $rowCount, $element['code']);
            $spreadsheet->getActiveSheet()->SetCellValue('B' . $rowCount, $fName);
            $spreadsheet->getActiveSheet()->SetCellValue('C' . $rowCount, $lName);
            $spreadsheet->getActiveSheet()->SetCellValue('D' . $rowCount, $companyName);
            $spreadsheet->getActiveSheet()->SetCellValue('E' . $rowCount, $element['designation']);
            $spreadsheet->getActiveSheet()->SetCellValue('F' . $rowCount, $element['salary']);
            $spreadsheet->getActiveSheet()->SetCellValue('G' . $rowCount, "");
            $debit=0;
            if($balanceArr[$no]<0){
                $debit=intval($balanceArr[$no]);
                $debit = str_replace('-', '', $debit);
                $spreadsheet->getActiveSheet()->SetCellValue('H' . $rowCount, $debit);
            }else if($balanceArr[$no]>0){
                $debit=intval($balanceArr[$no]);
                $debit = '-'.$debit;
                $spreadsheet->getActiveSheet()->SetCellValue('H' . $rowCount, $debit);
            }else{
                $spreadsheet->getActiveSheet()->SetCellValue('H' . $rowCount, "0");
            }
           
            $spreadsheet->getActiveSheet()->SetCellValue('I' . $rowCount, "");
            $spreadsheet->getActiveSheet()->SetCellValue('J' . $rowCount, "");

            $rowCount++;
            $no++;
            }
        }
       
        $writer = new Xlsx($spreadsheet);
        $fileName=$file;
        
        // $upload_path='./assets/deliveryslips/'.$fileName;
        // $writer->save($upload_path);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }


     //for nestle bill-details data uploading
    public function employeeDataUploading(){
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
                
                $empCode = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                $empFname = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                $empLname = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                $mobile = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                $company = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                $role=trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                $loginAllowed=trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                $userCode=trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());

                $companyId=0;
                if(trim($company)=="ITC"){
                    $companyId=1;
                }

                $ownerApproval=0;
                $isSalaryEmp=0;
                $isLoginEmp=0;

                if($loginAllowed=="Login Required"){
                    $ownerApproval=1;
                    $isSalaryEmp=1;
                    $isLoginEmp=1;
                }else {
                    $ownerApproval=1;
                    $isSalaryEmp=0;
                    $isLoginEmp=0;
                }

                // $emp=$this->EmployeeModel->getLastEntry('employee');
                // $pre_role_code=$this->EmployeeModel->getdata('emp_code');
                // $employeeCode="";
                // if(!empty($emp)){
                //     $employeeCode=$pre_role_code[0]['name'].''.$emp[0]['id'];
                // }else{
                //     $employeeCode=$pre_role_code[0]['name'].''.$emp[0]['id'];
                // }

                
                $empExist=$this->EmployeeModel->checkEmpDetails('employee',$empCode);
                if(!empty($empExist)){
                    $empId=$empExist[0]['id'];
                    $codeData=$empExist[0]['code'];
                    if(!empty($codeData)){
                        $data = array(
                          'name'=>$empFname.' '.$empLname,
                          'mobile'=>$mobile,
                          'email'=>$userCode,
                          'companyId'=>$companyId,
                          'designation'=>$role,
                          'ownerApproval'=>$ownerApproval,
                          'isSalaryEmp'=>$isSalaryEmp,
                          'isLoginEmp'=>$isLoginEmp,
                          'status'=>1
                        );
                        $this->EmployeeModel->update('employee',$data,$empId);
                    }
                }else{
                    $data = array(
                      'code'=>$empCode,
                      'name'=>$empFname.' '.$empLname,
                      'mobile'=>$mobile,
                      'email'=>$userCode,
                      'companyId'=>$companyId,
                      'designation'=>$role,
                      'ownerApproval'=>$ownerApproval,
                      'isSalaryEmp'=>$isSalaryEmp,
                      'isLoginEmp'=>$isLoginEmp,
                      'status'=>1
                    );
                    $this->EmployeeModel->insert('employee',$data);
                }
            }
                
        }
    }


    //for Employee Advance data uploading
    public function employeeAdvanceDataUploading(){
        $advanceAmount=$this->input->post('advanceAmount');

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

            for ($row = 6; $row <= $highestRow; ++$row) {
                $employeeCode = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                $employeeNewSalary = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                $employeeNewAdvanceDeduct = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                $remark = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());

                // Calculate total for Advance
                $excelTotalAmt=(int)$excelTotalAmt+(int)$employeeNewAdvanceDeduct;
            }

            
            if(((int)$advanceAmount)==((int)$excelTotalAmt)){
                for ($row = 6; $row <= $highestRow; ++$row) {
                    $cnt++;
                    
                    $employeeCode = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $employeeNewSalary = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $employeeNewAdvanceDeduct = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $remark = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());

                    $updatedAt=date('Y-m-d H:i:sa');
                    $userId = $this->session->userdata[$this->projectSessionName]['id'];
                    if(!empty($employeeCode)){
                        $empCheck=$this->EmployeeModel->checkSalariedEmpDetails('employee',$employeeCode);
                        if(!empty($empCheck)){
                            $empId=$empCheck[0]['id'];
                            if(!empty($employeeNewAdvanceDeduct)){
                                if(!empty($remark)){
                                    $empDebit=array(
                                        'empId'=>$empId,'transactionType'=>'cr','description'=>$remark,'amount'=>$employeeNewAdvanceDeduct,'createdAt'=>$updatedAt,'createdBy'=>$userId
                                    );
                                    $this->EmployeeModel->insert('emptransactions',$empDebit);//insert remark data
                                }else{
                                    $remark="Entry as per File Uploaded on ".date('d-M-Y H:i:sa');
                                    $empDebit=array(
                                        'empId'=>$empId,'transactionType'=>'cr','description'=>$remark,'amount'=>$employeeNewAdvanceDeduct,'createdAt'=>$updatedAt,'createdBy'=>$userId
                                    );
                                    $this->EmployeeModel->insert('emptransactions',$empDebit);//insert remark data
                                }
                            }
                            
                            if(!empty($employeeNewSalary)){
                                $updData=array('salary'=>$employeeNewSalary);
                                $this->EmployeeModel->update('employee',$updData,$empId);
                            }
                        }
                    }
                }
                $this->session->set_flashdata('true', 'Advance Deductions and New Salary updated');
                redirect('manager/EmployeeController/employeeLedger');
            }else{
                $this->session->set_flashdata('err', "Advance deduction amount mismatch. File not processsed");
                redirect('manager/EmployeeController/employeeLedger');
            }
        }
    }
}