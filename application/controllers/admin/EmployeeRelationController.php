<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmployeeRelationController extends CI_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('EmployeeRelationModel');
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
		$data['emprole']=$this->EmployeeRelationModel->getdata1('emprole');
		$this->load->view('admin/EmployeeRelationView',$data);
	}

    public function cashierExpensesLimit()
    {
        $data['cashierLimit']=$this->EmployeeRelationModel->getdata('cashier_expenses_limit');
        $this->load->view('admin/admin_cashierExpensesLimitView',$data);
    }

    public function fieldstaffTimeline()
    {
        $data['timeline']=$this->EmployeeRelationModel->getdata('fieldstaff_timeline');
        $this->load->view('admin/fieldstaffTimelineView',$data);
    }

    public function insertFieldstaffTimeline(){
        $limitId=trim($this->input->post('id'));
        $days=trim($this->input->post('days'));
        $data=array('days'=>$days);
        $this->EmployeeRelationModel->update('fieldstaff_timeline',$data,$limitId);
        // redirect('admin/EmployeeRelationController/cashierExpensesLimit');
    }

    public function insertExpenseLimit(){
        $limitId=trim($this->input->post('id'));
        $amount=trim($this->input->post('amount'));
        $data=array('expenseLimit'=>$amount);
        $this->EmployeeRelationModel->update('expenses_limit',$data,$limitId);
        // redirect('admin/EmployeeRelationController/cashierExpensesLimit');
    }

    public function loadCashierLimit(){
        $id=trim($this->input->post('id'));
        $loadLimitData=$this->EmployeeRelationModel->load('cashier_expenses_limit',$id);

        
    ?>
          <form method="post" role="form" action="<?php echo site_url('admin/EmployeeRelationController/insertCashierLimit'); ?>"> 
                        <div class="body">
                            <div class="demo-masked-input">
                                <div class="row clearfix">
                                    <input type="hidden" id='limitId' autocomplete="off" name="limitId" list="ret" value="1" class="form-control date">

                                  <div class="col-md-6">
                                        <b>Title</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" id='title' autocomplete="off" name="title" list="ret" value="<?php echo $loadLimitData[0]['title']?>" class="form-control date" placeholder="Enter title" required>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-6">
                                        <b>Expenses Limit</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" id='limit' autocomplete="off" name="limit" list="ret" value="<?php echo $loadLimitData[0]['amount']?>" class="form-control date" placeholder="Enter limit" required>
                                            </div>
                                        </div>
                                    </div>
                                  <div id="recStatus1"></div>
                                     <div class="col-md-12">
                                        <div class="row clearfix">
                                            <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                                <button id="insRet" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name">Save</span>
                                                </button>
                                               
                                                    <button data-dismiss="modal" type="button" class="btn btn-primary m-t-15 waves-effect">
                                                        <i class="material-icons">cancel</i> 
                                                        <span class="icon-name"> Cancel</span>
                                                    </button>
                                               
                                            </div>

                                        </div>
                                    </div>                            
                                </div>
                            </div>
                        </div>
                      </form>
    <?php    

    }

	public function Add()
	{
		$data['employee']=$this->EmployeeRelationModel->getdata('employee');
		$data['role']=$this->EmployeeRelationModel->getdata('role');
		$this->load->view('admin/AddEmployeeRelationView',$data);
	}

	public function insert()
    {
        $roleId=$this->input->post('roleId');
        $employeeId=trim($this->input->post('employeeId'));

        $userData=array();
        for($i=0;$i<count($roleId);$i++){
            $userData=$this->EmployeeRelationModel->checkRecord('emprole',$roleId[$i],$employeeId);
            if(count($userData)>0){
                $data = array('roleId' =>$roleId[$i],
                    'employeeId' =>$employeeId,
                     ); 
                $this->EmployeeRelationModel->updateRecord('emprole',$data,$roleId[$i],$employeeId); 
            }else{
                $data = array('roleId' =>$roleId[$i],
                    'employeeId' =>$employeeId,
                    'status' =>1            
                     ); 
                $this->EmployeeRelationModel->insert('emprole',$data); 
            }
        }
        redirect('admin/EmployeeRelationController');
    }

    public function removeRole()
    {
        $roleId=$this->input->post('roleId');
        $employeeId=trim($this->input->post('empId'));
        
        for($i=0;$i<count($roleId);$i++){
            $this->EmployeeRelationModel->deleteRole('emprole',$roleId[$i],$employeeId); 
        }
        redirect('admin/EmployeeRelationController');
    }

    public function load($id) 
    {
        $data['emprole']=$this->EmployeeRelationModel->load1('emprole', $id);
        $data['employee']=$this->EmployeeRelationModel->getdata('employee');
		$data['role']=$this->EmployeeRelationModel->getdata('role');
        // print_r($data['emprole']);exit;
		$this->load->view('admin/editEmployeeRelationView',$data);
    }

    public function update() {
        $id = $this->input->post('id');
        $roleId=$this->input->post('roleId');
        $employeeId=trim($this->input->post('employeeId'));

        for($i=0;$i<count($roleId);$i++){
            $userData=$this->EmployeeRelationModel->checkRecord('emprole',$roleId[$i],$employeeId);
            if(count($userData)>0){
                 $roleId = $roleId[$i];
                $data = array('roleId' => $roleId          
                     );
                $result = $this->EmployeeRelationModel->update('emprole',$data, $id);
            }else{
                $data = array('roleId' =>$roleId[$i],
                    'employeeId' =>$employeeId,
                    'status' =>1            
                     ); 
                $this->EmployeeRelationModel->insert('emprole',$data); 
               
            }
        }
        redirect('admin/EmployeeRelationController');
    }

    public function updateStatus($id, $status) {
        if($status==1) {
            $data = array('status' => 0);
        } else {
            $data = array('status' => 1);
        }
        $result = $this->EmployeeRelationModel->update('emprole',$data, $id);
        if($result==1){
           redirect("admin/EmployeeRelationController");
        } else {
            redirect("admin/EmployeeRelationController");
        }
    } 
}
