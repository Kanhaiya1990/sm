<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RetailerController extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('RetailerModel');
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
        $this->load->library('pagination');

        $config['base_url'] = base_url('index.php/RetailerController');
        
        $getRetailerInfo=$this->RetailerModel->getdata('retailer_kia');
       
        $getCode=$this->RetailerModel->getdata('emp_code');
        $codeForRetailer="";
        $data['retailerCode']="";
        if(!empty($getCode)){
            $codeForRetailer=$getCode[0]['name'];
        }
        
        if(!empty($getRetailerInfo)){
            $data['retailerCode']=$codeForRetailer."100".count($getRetailerInfo);
        }else{
            $data['retailerCode']=$codeForRetailer."1001";
        }
        
        $config['per_page'] = ($this->input->get('limitRows')) ? $this->input->get('limitRows') : 50;
        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;

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
        $retailer = $this->RetailerModel->paginationRetailers('retailer_kia',$config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);
        $rowCounts=$this->RetailerModel->countPaginationRetailers('retailer_kia',$config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);
        // $rowCounts=count($rowCounts);

        $data['retailer'] = $retailer;
        $config['total_rows'] = $rowCounts;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('RetailerView',$data);
    }

    public function indexOld(){
      $getRetailerInfo=$this->RetailerModel->getdata('retailer_kia');
      $data['retailerCode']="GTI100".count($getRetailerInfo);
        
    	$data['retailer']=$this->RetailerModel->getAllRetailers('retailer_kia');
        // $data['blockRetailer']=$this->RetailerModel->getBlockRetailers('retailer_kia');
    	$this->load->view('RetailerView',$data);
    }

    public function blockedRetailers(){
    	$data['retailer']=$this->RetailerModel->getRetailers('retailer_kia');
        $data['blockRetailer']=$this->RetailerModel->getBlockRetailers('retailer_kia');
    	$this->load->view('blockedRetailerView',$data);
    }

    public function Add(){
        $data['retNames']=$this->RetailerModel->getName('retailer');
        $data['emp']=$this->RetailerModel->getdata('employee');
         $data['route']=$this->RetailerModel->getData('route');
    	$this->load->view('AddRetailerView',$data);
    }

    public function load($id) 
    {
        $data['retailer']=$this->RetailerModel->load('retailer', $id);
        $this->load->view('AddRetailerView',$data);
    }

    public function retailersDataUploading(){
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
            
            for ($row = 2; $row <= $highestRow; ++$row) {
                $cnt++;
                $retailerName = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                $retailerCode = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                $area = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                $billingAddress = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
               
               
                if($retailerCode != "GrandTotal:"){
                    $checkRetailer=$this->RetailerModel->checkRetailerData('retailer_kia',$retailerCode,$retailerName);//insert remark data
                    if(empty($checkRetailer)){
                            $retailerData=array(
                                'name'=>$retailerName,
                                'retailerCode'=>$retailerCode,
                                'area'=>$area,
                                'billingAddress'=>$billingAddress,
                                'isActive'=>1
                            );
                            $this->RetailerModel->insert('retailer_kia',$retailerData);//insert remark data
                        
                    }else{
                        $id=$checkRetailer[0]['id'];
                        $retailerData=array(
                            'name'=>$retailerName,
                            'retailerCode'=>$retailerCode,
                            'area'=>$area,
                            'billingAddress'=>$billingAddress,
                            'isActive'=>1
                        );
                        $this->RetailerModel->update('retailer_kia',$retailerData,$id);//insert remark data
                    }
                }
            }
        }
        redirect('RetailerController');
    }

    public function insert()
    {
        $retailerName=$this->input->post('retailerName');
        $area=$this->input->post('area');
        $retailerCode=$this->input->post('retailerCode');
        $address=trim($this->input->post('address'));

        // $getRetailerInfo=$this->RetailerModel->getdata('retailer_kia');
        // $retailerCode="KIA100".count($getRetailerInfo);
        
        $retailerExist=$this->RetailerModel->getDetails('retailer_kia',$retailerName);
        if(empty($retailerExist)){//check route present or not
            $retailerData = array('name' => $retailerName,'retailerCode'=>$retailerCode,'area'=> $area,'billingAddress'=>$address,'isActive'=>'1');
            $this->RetailerModel->insert('retailer_kia',$retailerData); 
            if($this->db->affected_rows()>0){
                echo "Record Added";
            }else{
                echo "Unable to Insert Record";
            }
        }else{
            echo "Retailer Name already Present";
        }
        
    }
   
    public function update() {
        $id = $this->input->post('id');
             $data = array
            ('name' => $this->input->post('prodName')
             );  
             $this->RetailerModel->update('retailer',$data, $id);
            if($this->db->affected_rows()>0){
                return redirect("RetailerController");
            } else {
                echo "Fail";
            }
    }
    
     public function updateRetailerDetail() {
        $rtId = trim($this->input->post('retailerId'));
        $name=trim($this->input->post('retailerName'));
        $area=trim($this->input->post('area'));
        $retailerCode=trim($this->input->post('retailerCode'));
        $address=$this->input->post('address');

        $retailerExist=$this->RetailerModel->getDetails('retailer_kia',$name);
        $data = array('name' => $name,'area'=>$area,'billingAddress'=>$address);  

        $this->RetailerModel->update('retailer_kia',$data, $rtId);
        if($this->db->affected_rows()>0){
            $data = array('retailerName' => $name);  
            $this->RetailerModel->updateByRetailerId('bills',$data, $rtId);
            echo "Record updated";
        } else {
            echo "Record not updated";
        }
    }
    
    
    public function deactivateRetailer($id)
    {
        $up=array('isActive'=>0);
        $this->RetailerModel->update('retailer_kia',$up,$id);  
        if ($this->db->affected_rows()>0)
        {
          return redirect("RetailerController");                   
        }
        else
        {
            echo "Deleted Fail..";
        }
    }
    
    public function activateRetailer($id)
    {
        $up=array('isActive'=>1);
        $this->RetailerModel->update('retailer_kia',$up,$id);  
        if ($this->db->affected_rows()>0)
        {
          return redirect("RetailerController");                   
        }
        else
        {
            echo "Deleted Fail..";
        }
    }
    
    public function delete()
    {
        $id =$this->input->post('id');
        $up=array('isActive'=>2);
        $this->RetailerModel->update('retailer_kia',$up,$id);  
        if ($this->db->affected_rows()>0)
        {
            echo "Your record has been deleted!";                
        }
        else
        {
            echo "Deleted Fail..";
        }
    }
    
    public function editRetailer(){
        $id=trim($this->input->post('id'));
        $retailer=$this->RetailerModel->getRetailersById('retailer_kia',$id);
        $name=$retailer[0]['name'];
        $area=$retailer[0]['area'];
        $code=$retailer[0]['retailerCode'];
        $address=$retailer[0]['billingAddress'];

        
        ?>
          <div class="modal-header">
           <h4 class="modal-title">Update Retailer</h4>
          </div>
          
          <input type="hidden" id='retailerInfoIdU' autocomplete="off" value="<?php echo $id; ?>" name="retailerInfoId">
          <div class="modal-body">
                        <div class="body">
                            <div class="demo-masked-input">
                                <div class="row clearfix">
                                  <div class="col-md-4">
                                        <b>Retailer Code</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" id='retailerCodeU' readonly value="<?php echo $code; ?>" name="retailerCode" class="form-control date" placeholder="Enter retailer code" required>
                                            </div>
                                        </div>
                                    </div> 
                                  <div class="col-md-4">
                                        <b>Retailer Name</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" id='rtNameU'  name="rtName" class="form-control date" value="<?php echo $name; ?>" placeholder="Enter retailer name" required>
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="col-md-4">
                                        <b>Area</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input id="areaU" type="text" name="area" class="form-control date" value="<?php echo $area; ?>" placeholder="Enter area" required>
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="col-md-12">
                                        <b>Address</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                            <input id="addressU" type="text" name="address" class="form-control" value="<?php echo $address; ?>">
                                            </div>
                                        </div>
                                    </div> 

                                  <div id="recStatus1"></div>
                                     <div class="col-md-12">
                                        <div class="row clearfix">
                                            <div class="col-md-4">
                                                <button id="updRetInfo" class="btn btn-primary m-t-15 waves-effect">
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
          </div>
        <?php
    }
}

?>