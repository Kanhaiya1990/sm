<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RouteController extends CI_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('RouteModel');
        $this->load->library('session');
        date_default_timezone_set('Asia/Kolkata');
        ini_set('memory_limit', '-1');
        
        if(isset($this->session->userdata['codeKeyData'])) {
			$this->projectSessionName= ($this->session->userdata['codeKeyData']['codeKeyValue']);
		}else{
			redirect('DashbordController');
		}
    }
	public function index()
	{
		$data['route']=$this->RouteModel->getdata('route');
		$this->load->view('admin/RouteView',$data);
	}
	public function Add()
	{
		$this->load->view('admin/AddRouteView');
	}
	public function insert()
    {
        $data = array('name' => $this->input->post('name'),
            'code' => $this->input->post('code')              
             ); 
        $result=$this->RouteModel->insert('route',$data); 
       	if(!$result==0){                
             redirect("admin/RouteController");
        }   
        else{
             redirect("admin/RouteController");
        }
    }
    public function load($id) 
    {
        $data['route']=$this->RouteModel->load('route', $id);
        $this->load->view('admin/AddRouteView',$data);
    }
    public function update() {
        $id = $this->input->post('id');
            $data = array('name' => $this->input->post('name'),
                     'code' => $this->input->post('code')         
                    ); 
            $result = $this->RouteModel->update('route',$data, $id);
            if($result==1){
                 redirect("admin/RouteController");
            } else {
                 redirect("admin/RouteController");
            }
    }
    public function delete()
    {
        $id =$this->input->post('id');
        $data=$this->RouteModel->delete('route',$id);  
        if ($data==1)
        {
            echo "Your record has been deleted!";                
        }
        else
        {
            echo "Deleted Fail..";
        }
    }  
    public function search(){
    
        $result = $this->RouteModel->search($this->input->post('name'));
        if(count($result)>0){
            foreach($result as $object)
                $arr_result[] = array( 'label' => $object->name, 'value' => $object->id);

            echo json_encode($arr_result);
        }

}
}
