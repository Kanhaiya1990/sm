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
		}else{
			$this->load->view('LoginView');
		}
    }
   
    public function index()
    {
        
        $data['employee']=$this->EmployeeModel->getdataActive('employee');
        $this->load->view('Godownkeeper/EmployeeView',$data);
    }
     public function Deactive()
    {
        $data['employee']=$this->EmployeeModel->getdataDeactive('employee');
        $this->load->view('Godownkeeper/EmployeeView',$data);
    }
    public function Add()
    {
        $data['company']=$this->EmployeeModel->getdata('company');
        $this->load->view('Godownkeeper/AddEmployeeView',$data);
    }
    public function insert() {
        $fileName = trim($this->uploadFile('idProofImage'));   
        $fileName1 = trim($this->uploadFile('addrProofImage'));
        $fileName2 = trim($this->uploadFile('profileImage'));
        //echo "File Name : ".$fileName;
        $str= $this->input->post('FirstName');
        $str1= $this->input->post('lastName');
        $array = array($str,$str1);
        $name =implode(" ",$array);
        $cd=$this->input->post('code');
        if($this->input->post('designation')=='admin')
        $code = "AD".$cd;
        if($this->input->post('designation')=='manager')
        $code = "MG".$cd;
        if($this->input->post('designation')=='accountant')
        $code = "AC".$cd;
        if($this->input->post('designation')=='owner')
       $code = "ON".$cd;
        if($this->input->post('designation')=='deliveryman')
        $code = "DM".$cd;
        if($this->input->post('designation')=='godown_keeper')
        $code = "GK".$cd;
        if($this->input->post('designation')=='cashier')
        $code = "CS".$cd;
    if($this->input->post('designation')=='salesman')
        $code = "SM".$cd;

           if(strlen($fileName)>0 && strlen($fileName1)>0 && strlen($fileName2)>0) {
             $data = array
                    ('code' => $code,
                    'name' => $name,
                    'email' => $this->input->post('email'),
                    'mobile' => $this->input->post('mobile'),
                    'designation' => $this->input->post('designation'),
                    'password' =>date("dmY"),                          
                    'fatherName' => $this->input->post('fatherName'),                    
                    'localAddress' => $this->input->post('localAddress'),
                    'permanantAddress' => $this->input->post('permanantAddress'),
                    'idProofName' =>$this->input->post('idProofName'),
                    'idProofNo' =>$this->input->post('idProofNo'),                          
                    'idProofImage' => $fileName,                    
                    'addrProofName' => $this->input->post('addrProofName'),
                    'addrProofNo' => $this->input->post('addrProofNo'),
                    'addrProofImage' => $fileName1,
                    'profileImage' =>  $fileName2,
                    'joiningDate' => date("y-m-d"),
                    'salary' => $this->input->post('salary'),
                    'status' => $this->input->post('status'),
                    'remark' => $this->input->post('remark'),
                    'companyId' => $this->input->post('companyId')          
                     ); 
            $result=$this->EmployeeModel->insert('employee',$data); 
            // print_r($data);
            // exit();
            if(!$result==0){                
            return redirect("Godownkeeper/EmployeeController");
                
            } else {
                echo "Fail";
            }
        }
         else {
            echo "Unable to upload file";
        }   
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
            //$ext = explode('.',$file_name);
            //$fileName = time().".".$ext[1];
            return $fileName;
        }
     }
    public function load($id) 
    {
        $data['employee']=$this->EmployeeModel->load('employee', $id);
        $this->load->view('Godownkeeper/AddEmployeeView',$data);
    }
    public function update() {
        $fileName = trim($this->uploadFile('idProofImage'));   
        $fileName1 = trim($this->uploadFile('addrProofImage'));
        $fileName2 = trim($this->uploadFile('profileImage'));
        $id = $this->input->post('id');
         $str= $this->input->post('FirstName');
        $str1= $this->input->post('lastName');
        $array = array($str,$str1);
        $name =implode(" ",$array);
        $data = array
                ('code' => $this->input->post('code'),
                'name' => $name,
                'email' => $this->input->post('email'),
                'mobile' => $this->input->post('mobile'),
                'designation' => $this->input->post('designation'),
                'password' =>md5($this->input->post('password')),                          
                'fatherName' => $this->input->post('fatherName'),                    
                'localAddress' => $this->input->post('localAddress'),
                'permanantAddress' => $this->input->post('permanantAddress'),
                'idProofName' =>$this->input->post('idProofName'),
                'idProofNo' =>$this->input->post('idProofNo'),                          
                'idProofImage' => $fileName,                    
                'addrProofName' => $this->input->post('addrProofName'),
                'addrProofNo' => $this->input->post('addrProofNo'),
                'addrProofImage' => $fileName1,
                'profileImage' =>  $fileName2,
                //'joiningDate' => $this->input->post('joiningDate'),
                'salary' => $this->input->post('salary'),
                'status' => $this->input->post('status'),
                'remark' => $this->input->post('remark'),
                'companyId' => $this->input->post('companyId')          
                 );  
            $result = $this->EmployeeModel->update('employee',$data, $id);
            if($result==1){
                return redirect("Godownkeeper/EmployeeController");
            } else {
                echo "Fail";
            }
    }
    public function delete()
    {
        $id =$this->input->post('id');
        $data=$this->EmployeeModel->delete('employee',$id);  
        if ($data==1)
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
        } else {
            $data = array('status' => 1);
        }
        $result = $this->EmployeeModel->update('employee',$data, $id);
        if($result==1){
           redirect("Godownkeeper/EmployeeController");
        } else {
            echo "Fail";
        }
    } 
}
