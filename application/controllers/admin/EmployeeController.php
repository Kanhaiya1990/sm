<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmployeeController extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('EmployeeModel');
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
  
    public function index()
    {
        $data['employee']=$this->EmployeeModel->getdataActiveForDetail('employee');
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

        $this->load->view('admin/EmployeeView',$data);
    }


    public function universalEmployees()
    {
        $data['cpage']='dtPage';
        $data['employee']=$this->EmployeeModel->getActiveUniversalEmployee('employee');
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

        $this->load->view('admin/EmployeeUniversalView',$data);
    }

    public function employeeeException(){
        
        $data['employee'] = $this->EmployeeModel->allocationEmployees('employee');
        $this->load->view('admin/employeeExemptionView',$data);
    }

    public function salesmanLinking(){
        $data['salesman'] = $this->EmployeeModel->allocationSalesman('employee');
        $data['employee'] = $this->EmployeeModel->salesmanData('bills');
        // echo count($data['employee']);
        $this->load->view('admin/salesmanLinkingView',$data);
    }

     public function insertSalesmanLinking(){
        $loginId = $this->session->userdata[$this->projectSessionName]['id'];
        $salesmanCode=trim($this->input->post('salesmancode'));
        $salesmanName=trim($this->input->post('salesmanname'));

        $empId=trim($this->input->post('empSelectedId'));
        $checkExist=$this->EmployeeModel->checkedLinkedEmployee('salesman_linking',$empId);
        $salesmanExist=$this->EmployeeModel->checkedLinkedSalesman('salesman_linking',$salesmanCode,$salesmanName);  
        if(!empty($checkExist)){
            $this->session->set_flashdata('Successfully', 'Salesman already linked');
        }else{
            if(empty($salesmanExist)){
                $data=array(
                    'salesmanCode'=>$salesmanCode,
                    'salesmanName'=>$salesmanName,
                    'employeeId'=>$empId,
                    'createdBy'=>$loginId
                );
                $this->EmployeeModel->insert('salesman_linking',$data); 
                $this->session->set_flashdata('Successfully', 'Salesman assigned');
            }else{
                $data=array(
                    'salesmanCode'=>$salesmanCode,
                    'salesmanName'=>$salesmanName,
                    'employeeId'=>$empId,
                    'createdBy'=>$loginId
                );
                $this->EmployeeModel->updateByCodeName('salesman_linking',$data,$salesmanCode,$salesmanName);
                $this->session->set_flashdata('Successfully', 'Salesman assigned');
            }
        }
        redirect("admin/EmployeeController/salesmanLinking");
    }

    public function checkSalesmanLinking(){
        $empId=trim($this->input->post('empId'));
        $checkExist=$this->EmployeeModel->checkedLinkedEmployee('salesman_linking',$empId);
        if(!empty($checkExist)){
            echo 'Salesman already linked';
        }else{
            echo '';
        }
    }


    public function cancelSalesmanLinking($salesmancode){
        $salesmanCode=trim($salesmancode);
        // $salesmanName=urldecode(trim($salesmanname));
        // echo $salesmanCode.' '.$salesmanName;exit;
        $data=array(
            'employeeId'=>0,
        );

        $this->EmployeeModel->updateWithCode('salesman_linking',$data,$salesmanCode); 
        // $this->EmployeeModel->updateByCodeName('salesman_linking',$data,$salesmanCode,$salesmanName); 
        $this->session->set_flashdata('Successfully', 'Salesman linking removed');
        redirect("admin/EmployeeController/salesmanLinking");
    }

    public function updateEmployeeMobile(){
        $empId=trim($this->input->post('updateMobileEmpId'));
        $mobile=trim($this->input->post('updatemobile'));

        $exists = $this->EmployeeModel->loadUserByMobile('employee',$mobile);
        if(empty($exists)) {
            $data=array('mobile'=>$mobile);
            $this->EmployeeModel->update('employee',$data,$empId);  
            redirect("admin/EmployeeController");
        } else {
            $this->session->set_flashdata('item', array('message' => 'Mobile Number already present.','class' => 'success'));
            redirect("admin/EmployeeController");
        }
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

    public function checkValuesByUserName(){
        $logId=trim($this->input->post('logId'));
        
        $exists = $this->EmployeeModel->loadUserByUserName('employee',$logId);
        if(empty($exists)) {
            echo "";
        } else {
            echo "Username already present.";
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
        redirect("admin/EmployeeController");
    }

    public function addNonSalaryEmployee(){
        $fname=trim($this->input->post('firstName'));
        $lname=trim($this->input->post('lastName'));
        $mobile=trim($this->input->post('mobile'));
        $name=$fname.' '.$lname;

        $loginId = $this->session->userdata[$this->projectSessionName]['id'];
        // $checkLogin=$this->EmployeeModel->load('employee',$loginId);
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
            $code=$pre_role_code[0]['name'].''.$emp[0]['id'];
        }else{
            $code=$pre_role_code[0]['name'].''.count($emp);
        }
        $data=array('name'=>$name,'mobile'=>$mobile,'code'=>$code,'ownerApproval'=>1,'status'=>2,'joiningDate'=>date('Y-m-d'));
        $this->EmployeeModel->insert('employee',$data);
        redirect("admin/EmployeeController");
    }

    public function updateNonSalaryEmployee(){
        $id=trim($this->input->post('nonLoginId'));
        $fname=trim($this->input->post('firstName'));
        $lname=trim($this->input->post('lastName'));
        $mobile=trim($this->input->post('mobile'));
        $name=$fname.' '.$lname;

        $loginId = $this->session->userdata[$this->projectSessionName]['id'];
        
        $emp=$this->EmployeeModel->getdata('employee');
        $pre_role_code=$this->EmployeeModel->getdata('emp_code');
        $code=$pre_role_code[0]['name'].''.count($emp);
        
        $data=array('name'=>$name,'mobile'=>$mobile,'ownerApproval'=>1,'status'=>2,'joiningDate'=>date('Y-m-d'));
        $this->EmployeeModel->update('employee',$data,$id);
        redirect("admin/EmployeeController");
    }

    public function inactiveEmployee()
    {
        $data['employee']=$this->EmployeeModel->getdataDeactive('employee');
        $this->load->view('admin/inactiveEmployeeDetailsView',$data);
    }

    public function empCodeSetting()
    {
        $data['empCode']=$this->EmployeeModel->getdata('emp_code');
        $this->load->view('admin/empCodeView',$data);
    }

    public function updateEmpCode(){
        $limitId=trim($this->input->post('id'));
        $name=trim($this->input->post('name'));
        $data=array('name'=>$name);
        // print_r($data);
        $this->EmployeeModel->update('emp_code',$data,$limitId);
        // redirect('admin/EmployeeRelationController/cashierExpensesLimit');
    }

     public function Deactive()
    {
        $data['employee']=$this->EmployeeModel->getdataDeactive('employee');
        $this->load->view('admin/EmployeeView',$data);
    }
    public function Add()
    {
        $emp=$this->EmployeeModel->getLastEntry('employee');
        $data['role']=$this->EmployeeModel->getdata('role');     

        $data['proof']=$this->EmployeeModel->getdata('proof_details');

        $data['empCount']="";
        $username="";
        $empCount="";
        if(!empty($emp)){
            $data['empCount']=$emp[0]['id'];
            $empCount=$emp[0]['id'];
            $username="fname.lname".$empCount;
            $data['userEmail']=$username;
        }else{
            $data['empCount']=count($emp);
            $empCount=count($emp);
            $username="fname.lname".$empCount;
            $data['userEmail']=$username;
        }

        $data['company']=$this->EmployeeModel->getdata('company');
        $this->load->view('admin/AddEmployeeView',$data);
    }

    public function EmployeeDetail(){
        $data['emp']=$this->EmployeeModel->getdata('employee');
        $this->load->view('admin/EmployeeDetailView',$data);
    }

    public function EmployeeLedger(){
        $this->load->view('admin/EmployeeLedgerView');
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

        $employeeDetails=$this->EmployeeModel->load('employee',$id);
        $employeeMobile=$employeeDetails[0]['mobile'];
        $employeeName=$employeeDetails[0]['name'];
        $employeeDesignation=$employeeDetails[0]['designation'];
        $transactionDate=date('M d, Y H:i a');

        $office=$this->EmployeeModel->load('office_details','1');
        $jsonData=array(
            "flow_id"=>"6149c44ea311164889682a36",
            "sender"=>"SIAInc",
            "mobiles"=>'91'.$employeeMobile,
            "name"=>$employeeName,
            "designation"=>ucfirst($employeeDesignation),
            "kias"=>'KIAS',
            "distributorName"=>$office[0]['distributorName'],
            "username"=>$username,
            "password"=>$password,
            "link"=>"https://rb.gy/rad9y6"
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

    public function insert() {
        $empApproval=0;

        $pre_role_code=$this->EmployeeModel->getdata('emp_code');
        $designationData=$this->input->post('designation');
        // print_r($designationData);
        $role_data="";
        foreach($designationData as $role){
            $role_data=$role_data.','.$role;
        }
        // echo $role_data.'<br>';exit;

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
       
        if($this->db->affected_rows()>0){
            if($empApproval==0){
                $insert_id = $this->db->insert_id();  
                $this->sendApprovalSms($insert_id,$role_data,$username,$userMobile);   
            }
            return redirect("admin/EmployeeController");
        } else {
            return redirect("admin/EmployeeController");
        }
        
    }

    public function uploadFile($fileName) {
        $upload_path='./assets/uploads/'; 
        $config = array(
        'upload_path' => $upload_path,
        'allowed_types' => "gif|jpg|png|jpeg"
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
        $data['proof']=$this->EmployeeModel->getdata('proof_details');
        $data['empCount']=count($emp);
        $data['company']=$this->EmployeeModel->getdata('company');
        $data['role']=$this->EmployeeModel->getdata('role');
        $data['employee']=$this->EmployeeModel->getEmpDetails('employee', $id);
        $this->load->view('admin/AddEmployeeView',$data);
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
        $data = array(
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
                 redirect("admin/EmployeeController");
            } else {
                 redirect("admin/EmployeeController");
            }
    }
    public function delete()
    {
        $id =$this->input->post('id');
        $mobile="";
        $updateData=array('mobile'=>$mobile,'isDeleted'=>1);
        $this->EmployeeModel->update('employee',$updateData,$id);  
        if ($this->db->affected_rows()>0)
        {
            echo "Your record has been deleted!";                
        }
        else
        {
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
           redirect("admin/EmployeeController");
        } else {
           redirect("admin/EmployeeController");
        }
    }

     public function updateEmployeeExcemptionStatus($id, $status) {
        if($status==1) {
            $data = array('empExemption' => 0);
        } else if($status==0){
            $data = array('empExemption' => 1);
        } 
        $this->EmployeeModel->update('employee',$data, $id);
        if($this->db->affected_rows()>0){
           redirect("admin/EmployeeController/employeeeException");
        } else {
           redirect("admin/EmployeeController/employeeeException");
        }
    }

    public function updateSelectedEmployeeExcemptionStatus(){
        $selValue=($this->input->post('selValue'));

        if(!empty($selValue)){
            foreach($selValue as $sel){
                $id=$sel;
                $data = array('empExemption'=>1);
                $this->EmployeeModel->update('employee',$data, $id);
            }
        }
    } 

    public function cancelSelectedEmployeeExcemptionStatus(){
        $selValue=($this->input->post('selValue'));

        if(!empty($selValue)){
            foreach($selValue as $sel){
                $id=$sel;
                $data = array('empExemption'=>0);
                $this->EmployeeModel->update('employee',$data, $id);
            }
        }
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
            $data['empLedger']=$this->EmployeeModel->getEmpLedgerByEmp('emptransactions',$id);

            $this->load->view('admin/EmployeeLedgerDetailsView',$data);
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
            
            $data['closing']=$this->EmployeeModel->getEmpLedgerById('emptransactions',$id);

            $data['empLedger']=$this->EmployeeModel->getEmpLedgerByEmpDates('emptransactions',$id,$fromdate,$todate);


            $this->load->view('admin/EmployeeLedgerDetailsView',$data);
        }
        
    } 


    public function empDetails(){
        $id=trim($this->input->post('id'));
        $emp=$this->EmployeeModel->getEmpDetails('employee', $id);

        if(!empty($emp)){
            $ledger=$this->EmployeeModel->getEmpLedgerByEmp('emptransactions',$id);
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
            ?>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table  table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                            <thead>
                                 <tr>
                                    <td colspan="2" align="center"><img src="<?php if(isset($emp)){ echo base_url().'/assets/uploads/'.$emp[0]['profileImage'];  } ?>" id='emp-img-upload' class="border" style="height:90px; max-height: 90px; max-width:90px; width: 90px;" /></td>
                                </tr>
                                <tr>
                                    <td>Employee Code</td><td><?php echo $emp[0]['code'] ?></td>
                                </tr>
                                 <tr>
                                    <td>Employee Name</td><td><?php echo $emp[0]['name']; ?></td>
                                </tr>
                                
                                 <tr>
                                    <td>Email</td><td><?php echo $emp[0]['email'] ?></td>
                                </tr>
                                 <tr>
                                    <td>Mobile No.</td><td><?php echo $emp[0]['mobile'] ?></td>
                                </tr>
                                
                                
                                 <tr>
                                    <td>ID Proof Name</td><td><img src="<?php if(isset($emp)){ echo base_url().'/assets/uploads/'.$emp[0]['idProofName'];  } ?>" id='emp-img-upload' class="border" style="height:90px; max-height: 90px; max-width:90px; width: 90px;" /></td>
                                </tr>
                               
                                 <tr>
                                    <td>Address Proof Name</td><td><img src="<?php if(isset($emp)){ echo base_url().'/assets/uploads/'.$emp[0]['addrProofName'];  } ?>" id='emp-img-upload' class="border" style="height:90px; max-height: 90px; max-width:90px; width: 90px;" /></td>
                                </tr>
                                <tr>
                                    <td>Joining Date</td><td><?php echo $emp[0]['joiningDate'] ?></td>
                                </tr>
                                <tr>
                                    <td>Role</td><td><?php echo $emp[0]['designation'] ?></td>
                                </tr>
                                 <tr>
                                    <td>Company</td><td><?php echo $emp[0]['companyName'] ?></td>
                                </tr>
                                <tr>
                                    <td>Salary</td><td><?php echo $emp[0]['salary'] ?></td>
                                </tr>
                                <tr>
                                    <td>Current Balance</td>
                                    <td>
                                              
                                                 <?php 
                                                    if($balance<0){ ?>
                                                      <a class="btn btn-xs btn-dark waves-effect" href="<?php echo site_url('manager/EmployeeController/employeeLedgerByEmp/'.$emp[0]['id']); ?>">
                                                      <?php  echo '<span style="color:red">'.str_replace('-','',intval($balance)).' dr</span></a>'; 
                                                    }else if($balance>0){ ?>
                                                       <a class="btn btn-xs btn-dark waves-effect" href="<?php echo site_url('manager/EmployeeController/employeeLedgerByEmp/'.$emp[0]['id']); ?>">
                                                    <?php    echo '<span style="color:blue">'.intval($balance).' cr</span></a>'; 
                                                    }else{
                                                      if($emp[0]['isSalaryEmp']==1){ ?>
                                                        <a class="btn btn-xs btn-dark waves-effect" href="<?php echo site_url('manager/EmployeeController/employeeLedgerByEmp/'.$emp[0]['id']); ?>">
                                                    <?php       echo '<span style="color:blue">0</span></a>'; 
                                                      }
                                                    }
                                                ?>
                                                </td>
                                </tr>
    
                            </thead>
                            </table>
                    </div>
                </div>
            <?php
        }
    } 
}
