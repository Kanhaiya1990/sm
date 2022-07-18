<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OfficeAllocationController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('OfficeAllocationModel');
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
        
        $designation = ($this->session->userdata[$this->projectSessionName]['designation']);
        $des=explode(',',$designation);
        if (in_array('owner', $des) || in_array('senior_manager', $des)) { 
            
        }else{
            redirect("DashbordController");
        }
    }

    public function empApproval(){
         $data['employee']=$this->OfficeAllocationModel->getEmpApproval('employee');
         $this->load->view('owner/employeeApprovalView',$data);
    }

     public function generatePassword($_len){
        // $_alphaSmall = 'abcdefghijklmnopqrstuvwxyz';            
        // $_alphaCaps  = strtoupper($_alphaSmall);             
        $_numerics   = '1234567890';                           
        // $_specialChars = '`~!@#^&*';
        // $_container = $_alphaSmall.$_alphaCaps.$_numerics;   
        $_container = $_numerics;   
        $password = '';  
        for($i = 0; $i < $_len; $i++) {          
            $_rand = rand(0, strlen($_container) - 1);                 
            $password .= substr($_container, $_rand, 1);              
        }
        return $password;       
    }

    public function empOwnerApproval(){

        $id=$this->input->post('id');
        $emp=$this->OfficeAllocationModel->load('employee',$id);
 ?>
         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h4 style="color: #099dba;">
                    <span style="color: black;">Employee :</span>
                    <?php 
                        if(!empty($emp)){
                             echo $emp[0]['name'];
                        }
                    ?>
                     &nbsp; &nbsp;
                   <span style="color: black;">Mobile :</span> 
                    <?php 
                        if(!empty($emp)){
                             echo $emp[0]['mobile'];
                        }
                    ?>
                    &nbsp; &nbsp;
                  <span style="color: black;">Role :</span>
                    <?php
                        if(!empty($emp)){
                            echo $emp[0]['designation'];
                        }
                    ?>
                    </h4>
                </div>
                    
                <div class="body">
                <div class="table-responsive">
                <div class="body">
                    <div class="demo-masked-input">
                        <form method="post" role="form" action="<?php echo site_url('owner/OfficeAllocationController/empAccept');?>"> 
                        <div class="row clearfix">
                            <div class="col-md-12">
                             <input type="hidden" name="id" value="<?php
                            if(isset($emp))
                              {
                                if(!empty($emp[0]['id'])){
                                    echo $emp[0]['id'];
                                }
                              }
                            ?>">
                           
                            <div class="col-md-6">                                       
                                <p>
                                  <b>Salary </b>
                                </p>
                                <div class="form-line">
                                    <input onkeypress="return numbersonly(this, event);" onfocus="this.select();" autofocus="autofocus" type="text" id="salary" name="salary" value="<?php if(isset($emp)){ echo $emp[0]['salary']; }?>" class="form-control" required>           
                                </div>        
                            </div>
                             <div class="col-md-6">                                       
                                 <button type="submit" class="btn btn-primary m-t-25 waves-effect">
                                            <i class="material-icons">save</i> 
                                            <span class="icon-name">
                                            Save
                                            </span>
                                        </button> 
                                       <button data-dismiss="modal" type="button" class="btn m-t-25 btn-primary  waves-effect">
                                                <i class="material-icons">cancel</i> 
                                                <span class="icon-name">
                                                cancel
                                                </span>
                                        </button>        
                            </div>
                        </div>
                        </div>
                        </div>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        </div>
 <?php
    }

    public function empAccept(){
        $id=$this->input->post('id');
        $salary=$this->input->post('salary');
        // echo $id.' '.$salary;exit;

        $emp=$this->OfficeAllocationModel->load('employee',$id);
        $empEmail=$emp[0]['email'];
        $empMobile=$emp[0]['mobile'];
        // $mobile="8446107727";
        // $mobile="9081400400";

        $data=array('ownerApproval'=>1,'salary'=>$salary);
        $this->OfficeAllocationModel->update('employee',$data,$id);
        if($this->db->affected_rows()>0){
            $username=$empEmail;
            $password=$this->generatePassword(4);

            $pass=md5($password);
            $upData=array('password'=>$pass);

            $this->OfficeAllocationModel->update('employee',$upData,$id);
            
            $matter="For ".$emp[0]['designation']." panel : Username is ".$username.' and Password is '.$password;
            $url="https://api.msg91.com/api/sendhttp.php?authkey=291106AG8eCyzDe5d626f5c&mobiles=".$empMobile."&country=91&message=".$matter."&sender=TESTIN&route=4";

            $ch = curl_init();
            curl_setopt ($ch, CURLOPT_URL, $url);
            //curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
            $contents = curl_exec($ch);
            curl_close($ch);
        }
        redirect("owner/ExpensesController/allApprovals");
    }

    public function empReject(){
        $id=$this->input->post('id');
        // $data=array('ownerApproval'=>2);
        // $this->OfficeAllocationModel->update('employee',$data,$id);
        $this->OfficeAllocationModel->delete('employee',$id);
        if($this->db->affected_rows()>0){
            echo "Record rejected";
        }else{
            echo "Record not rejected";
        }
    }

    public function billClearance(){

        $response=array();
        $company=trim($this->input->post('compName'));
        $amount=trim($this->input->post('amount'));
        $remark=trim($this->input->post('remark'));
        $option=$this->input->post('selOption');

        $finalLimit=0;
        $limit=$this->OfficeAllocationModel->get_billClearanceLimit();

        if($amount>$limit){
            $finalLimit=$limit;
        }else{
            $finalLimit=$amount;
        }
        $details=array();
        
        if($company==="General"){
            $details=$this->OfficeAllocationModel->getGeneralBillInfoForClearance('bills',$finalLimit);
        }else{
            $details=$this->OfficeAllocationModel->getBillInfoForClearance('bills',$company,$finalLimit);
        }

        // print_r($details);exit;
        foreach($details as $data){
            $response[] = $data;
        }
        $this->session->set_userdata("DesktopBill",$response);

        $data['bills']=$details;
        $data['limit']=$limit;

        $data['compName']=$company;
        $data['amt']=$amount;
        $data['rem']=$remark;
        $data['opti']=$option;

        $data['company']=$this->OfficeAllocationModel->getdata('company');
        $this->load->view('owner/billClearanceView',$data);
    }

    public function insertBillClearanceData(){
        $billId=$this->input->post('billId');
        $compName=$this->input->post('compName');
        $amount=$this->input->post('amount');
        $remark=$this->input->post('remark');
        $selOption=$this->input->post('selOption');

        $empId=trim($this->input->post('empId'));
        $empId=$this->session->userdata[$this->projectSessionName]['id'];
        
        $updatedAt=date('Y-m-d H:i:sa');
        $updatedBy=$this->session->userdata[$this->projectSessionName]['id'];

        $checkLogin=$this->OfficeAllocationModel->load('employee',$empId);
        $empApproval=0;
        if(!empty($checkLogin)){
            if($checkLogin[0]['designation']=='owner'){
                $empApproval=1;
            }
        }

        foreach($billId as $currentBillId){
            if($currentBillId != 'on'){
                if($selOption=="cd"){
                    $collectedAmt=0;
                    $billInfo=$this->OfficeAllocationModel->load('bills',$currentBillId);//get bill detail
                    $netAmount=$billInfo[0]['netAmount'];
                    $pendingAmt=$billInfo[0]['pendingAmt'];
                    $collectedAmt=$pendingAmt;//pending amount
                    $cd=$billInfo[0]['cd'];

                    $finalPending=$pendingAmt-$collectedAmt;//final pending for current bill
                    $finalCd=$cd+$collectedAmt;//final received for current bill
                    
                    $billUpdate=array();
                    if($netAmount==$pendingAmt){
                        $billUpdate=array(
                            'billType'=>'allocatedbillCurrent','cd'=>$finalCd,'pendingAmt'=>$finalPending
                        );
                    }else{
                        $billUpdate=array(
                            'cd'=>$finalCd,'pendingAmt'=>$finalPending
                        );
                    }

                    $this->OfficeAllocationModel->update('bills',$billUpdate,$currentBillId);//update bill amount
                    if($this->db->affected_rows()>0){
                        $history=array(
                            'billId'=>$currentBillId,
                            'transactionAmount' =>$collectedAmt,
                            'transactionStatus' =>'CD',
                            'transactionMode' =>'dr',
                            'transactionDate'=>date('Y-m-d H:i:sa'),
                            'empId'=>$empId,
                            'transactionBy'=>$empId
                        );
                        $this->OfficeAllocationModel->insert('bill_transaction_history',$history);

                        $billRemark=array(
                            'billId'=>$currentBillId,'empId'=>$empId,'remark'=>$remark,'amount'=>$collectedAmt,'updatedAt'=>$updatedAt,'updatedBy'=>$updatedBy
                        );
                        $this->OfficeAllocationModel->insert('bill_remark_history',$billRemark);//insert remark data

                        $billPayment=array(
                            'ownerApproval'=>$empApproval,'empId'=>$empId,'billId'=>$currentBillId,'date'=>$updatedAt,'paidAmount'=>$collectedAmt,'billAmount'=>$netAmount,'balanceAmount'=>$finalPending,'paymentMode'=>'CD','isLostStatus'=>2,'updatedBy'=>$updatedBy
                        );
                        $this->OfficeAllocationModel->insert('billpayments',$billPayment);//insert billpayment
                    }
                }else if($selOption =="other_adjustment"){
                    $collectedAmt=0;
                    $billInfo=$this->OfficeAllocationModel->load('bills',$currentBillId);//get bill detail
                    $netAmount=$billInfo[0]['netAmount'];
                    $pendingAmt=$billInfo[0]['pendingAmt'];
                    $collectedAmt=$pendingAmt;//pending amount
                    $otherAdjustment=$billInfo[0]['otherAdjustment'];

                    $finalPending=$pendingAmt-$collectedAmt;//final pending for current bill
                    $finalOA=$otherAdjustment+$collectedAmt;//final received for current bill
                    
                    $billUpdate=array();
                    if($netAmount==$pendingAmt){
                        $billUpdate=array(
                            'billType'=>'allocatedbillCurrent','otherAdjustment'=>$finalOA,'pendingAmt'=>$finalPending
                        );
                    }else{
                        $billUpdate=array(
                            'otherAdjustment'=>$finalOA,'pendingAmt'=>$finalPending
                        );
                    }

                    $this->OfficeAllocationModel->update('bills',$billUpdate,$currentBillId);//update bill amount
                    if($this->db->affected_rows()>0){
                        $history=array(
                            'billId'=>$currentBillId,
                            'transactionAmount' =>$collectedAmt,
                            'transactionStatus' =>'Other Adjustment',
                            'transactionMode' =>'dr',
                            'transactionDate'=>date('Y-m-d H:i:sa'),
                            'empId'=>$empId,
                            'transactionBy'=>$empId
                        );
                        $this->OfficeAllocationModel->insert('bill_transaction_history',$history);


                        $billRemark=array(
                            'billId'=>$currentBillId,'empId'=>$empId,'remark'=>$remark,'amount'=>$collectedAmt,'updatedAt'=>$updatedAt,'updatedBy'=>$updatedBy
                        );
                        $this->OfficeAllocationModel->insert('bill_remark_history',$billRemark);//insert remark data

                        $billPayment=array(
                            'ownerApproval'=>$empApproval,'empId'=>$empId,'billId'=>$currentBillId,'date'=>$updatedAt,'paidAmount'=>$collectedAmt,'billAmount'=>$netAmount,'balanceAmount'=>$finalPending,'paymentMode'=>'Other Adjustment','isLostStatus'=>2,'updatedBy'=>$updatedBy
                        );
                        $this->OfficeAllocationModel->insert('billpayments',$billPayment);//insert billpayment
                    }
                }
            }
        }
    }

    public function searchBillsForClearance(){
        $response=array();
        $company=trim($this->input->post('compName'));
        $amount=trim($this->input->post('amount'));
        $remark=trim($this->input->post('remark'));
        $option=trim($this->input->post('option'));


        $finalLimit=0;
        $limit=$this->OfficeAllocationModel->get_billClearanceLimit();

        if($amount>$limit){
            $finalLimit=$limit;
        }else{
            $finalLimit=$amount;
        }

        $details=$this->OfficeAllocationModel->getBillInfoForClearance('bills',$company,$finalLimit);
        
        foreach($details as $data){
            $response[] = $data;
        }
        $this->session->set_userdata("DesktopBill",$response);

        $no=0;
        foreach ($details as $data) 
        {
            $no++; 
        ?>
            <tr>
                <td>
                    <input class="checkhour" type="checkbox" name="selValue" value="<?php echo $data['id']; ?>" id="basic_checkbox_<?php echo $data['id']; ?>" checked/>
                    <label for="basic_checkbox_<?php echo $data['id']; ?>"></label>
                </td>
                <td><?php echo $no; ?></td>
               
                <td><?php echo $data['billNo']; ?></td>
                <td><?php
                $dt=date_create($data['date']);
                $date = date_format($dt,'d-M-Y');
                echo $date; ?></td>
                <td><?php echo $data['retailerName']; ?></td>
                <td><?php echo $data['netAmount']; ?></td>
                <td><?php echo $data['pendingAmt']; ?></td>
            </tr>
<?php
        }
    }

    public function openAllocations(){
        $this->session->unset_userdata('officeAllocationInfo');
        $this->session->unset_userdata('bouncedBills');
        $this->session->unset_userdata('deliveryBills');
        $this->session->unset_userdata('currentBillIDs');
        $this->session->unset_userdata('routeBills');
        $this->session->unset_userdata('Emp');
       
        $data['officeAllocations']=$this->OfficeAllocationModel->getOfficeAllocations('allocations_officeadjustment');
        $this->load->view('owner/openAdhocAllocationsView',$data);
    }

    public function closedAllocations(){
        $this->session->unset_userdata('officeAllocationInfo');
        $this->session->unset_userdata('bouncedBills');
        $this->session->unset_userdata('deliveryBills');
        $this->session->unset_userdata('currentBillIDs');
        $this->session->unset_userdata('routeBills');
        $this->session->unset_userdata('Emp');
       
        $data['officeAllocations']=$this->OfficeAllocationModel->getClosedOfficeAllocations('allocations_officeadjustment');

        $this->load->view('owner/officeAllocationsBillsView',$data);
    }

    public function closedAllocationInfo($id){
        $data['officeAllocations']=$this->OfficeAllocationModel->getClosedOfficeBillsInfo('allocations_officebills',$id);

        $allocationCode=$this->OfficeAllocationModel->load('allocations_officeadjustment',$id);
        $data['allocationCode']=$allocationCode[0]['allocationCode'];

        $this->load->view('owner/closedAllocationInfoView',$data);
    }

    

    public function index(){
        $data['allocations']=$this->OfficeAllocationModel->getAllocations('allocations_officeadjustment');
        $this->load->view('owner/officeAllocationsBillsView',$data);
    }

    public function addOfficeAllocationsBills(){
        $this->session->unset_userdata('officeAllocationInfo');
        $this->session->unset_userdata('officeAllocation');

        $allocationCount=$this->OfficeAllocationModel->getdata('allocations_officeadjustment');
        $officeAllocationCode="ofc-".date('dmy')." : ".(count($allocationCount)+1);
        $data['allocationCode']=$officeAllocationCode;

        $data['company']=$this->OfficeAllocationModel->getdata('company');
        $data['bills']=$this->OfficeAllocationModel->getdata('bills');


        $this->load->view('owner/addOfficeAllocationBillsView',$data);
    }

    public function printInsertedData(){
        $newCurrentBills = $this->session->userdata('officeAllocation');
        if(!empty($newCurrentBills)){
            $no=0;
            foreach($newCurrentBills as $items){
                $id=$items['id'];
                $pastBillIDs[$no]=$id;
                $no++;

        ?>  
                <tr id="status-id<?php echo $no; ?>">
                    <td><?php echo $no; ?></td>
                    <td style="display: none"><?php echo $items['id']; ?></td>
                    <td><?php echo $items['billNo']; ?></td>
                    <td><?php echo $items['date']; ?></td>
                    <td><?php echo $items['retailerName']; ?></td>
                    <td><?php echo $items['netAmount']; ?></td>
                    <td><?php echo $items['receivedAmt']; ?></td>
                    <td><?php echo $items['SRAmt']; ?></td>
                    <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                    <td><?php echo $items['pendingAmt']; ?></td>
                    <td></td>
                    <td></td>
                    <td>
                        <button id="srM" data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-success waves-effect" data-type="basic"><span>Cleared</span></button>
                        
                        <button id="pendingM" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>Pending</span></button>

                    <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                        <button id="fsrM" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php }else{ ?>
                        <button disabled id="fsrM" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php } ?>

                        <a>
                            <button onclick="deleteMe(this,'<?php echo $id;?>');" class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    </td>
                </tr>
        <?php
            }
        }
    }

    public function printTableData(){
        $newCurrentBills = $this->session->userdata('officeAllocation');
        if(!empty($newCurrentBills)){
            $no=0;
            foreach($newCurrentBills as $items){
                $id=$items['id'];
                $pastBillIDs[$no]=$id;
                $no++;

    ?>  
                <tr id="status-id<?php echo $no; ?>">
                    <td><?php echo $no; ?></td>
                    <td style="display: none"><?php echo $items['id']; ?></td>
                    <td><?php echo $items['billNo']; ?></td>
                    <td><?php echo $items['date']; ?></td>
                    <td><?php echo $items['retailerName']; ?></td>
                    <td><?php echo $items['netAmount']; ?></td>
                    <td><?php echo $items['receivedAmt']; ?></td>
                    <td><?php echo $items['SRAmt']; ?></td>
                    <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                    <td><?php echo $items['pendingAmt']; ?></td>
                    <td></td>
                    <td></td>
                    <td>
                        <a>
                            <button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    </td>
                </tr>
    <?php
            }
        }
    }

    public function insertAllocationData(){
        $this->load->model('AllocationByManagerModel');

        $userId = $this->session->userdata[$this->projectSessionName]['id'];
        $billId=$this->session->userdata('officeAllocation');
        $company=$this->input->post('cmpName');

        $officeInfo=$this->session->userdata('officeAllocationInfo');

        if(empty($officeInfo)){
            $allocationCount=$this->AllocationByManagerModel->getOfficeAllocationCount('allocations_officeadjustment');
            $officeAllocationCode="ofc-".date('dmy')." : ".(count($allocationCount)+1);

            if(!empty($billId)){
                $allocationData=array
                (   'createdAt' => date("Y-m-d H:i:sa"),
                    'createdBy'=>$userId,
                    'company'=>$company,
                    'allocationCode' => $officeAllocationCode,
                    'isOfficeAllocation'=>1
                );

                $this->AllocationByManagerModel->insert('allocations_officeadjustment',$allocationData);
                if($this->db->affected_rows()>0){
                    $lastInsertedId=$this->db->insert_id();
                    $sess_data=array(
                        "officeAllocationID"=>$lastInsertedId,
                        "officeAllocationCode"=>$officeAllocationCode
                    );
                    $this->session->set_userdata('officeAllocationInfo', $sess_data);
                    foreach($billId as $itm){
                        $details=array(
                            'allocationId'=>$lastInsertedId,
                            'billId'=>$itm['id'],
                            'updatedAt' => date("Y-m-d H:i:sa")
                        );
                        $this->AllocationByManagerModel->insert('allocations_officebills',$details);
                        if($this->db->affected_rows()>0){
                            $billUpdate=array(
                                'billCurrentStatus'=>'Allocated in '.$officeAllocationCode,
                                'billType'=>'officeAdjustmentBill',
                                'isAllocated'=>1
                            );
                            $this->AllocationByManagerModel->update('bills',$billUpdate,$itm['id']);

                            $history=array(
                                'billId'=>$itm['id'],
                                'officeAllocationId' => $lastInsertedId,
                                'transactionStatus' =>'Allocated in '.$officeAllocationCode,
                                'transactionDate'=>date('Y-m-d H:i:sa'),
                                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                            );
                            $this->AllocationByManagerModel->insert('bill_transaction_history',$history);
                        }
                    }
                }
            }else{
                echo "Bills not present";
            }
        }else{
            $officeAllocationID=$officeInfo['officeAllocationID'];
            $officeAllocationCode=$officeInfo['officeAllocationCode'];

            foreach($billId as $itm){
                $checkBills=$this->AllocationByManagerModel->checkInfo('allocations_officebills',$itm['id'],$officeAllocationID);
                if(empty($checkBills)){
                    $details=array(
                        'allocationId'=>$officeAllocationID,
                        'billId'=>$itm['id']
                    );
                    $this->AllocationByManagerModel->insert('allocations_officebills',$details);
                    if($this->db->affected_rows()>0){
                        $billUpdate=array(
                            'billCurrentStatus'=>'Allocated in '.$officeAllocationCode,
                            'billType'=>'officeAdjustmentBill',
                            'isAllocated'=>1
                        );
                        $this->AllocationByManagerModel->update('bills',$billUpdate,$itm['id']);

                        $history=array(
                            'billId'=>$itm['id'],
                            'officeAllocationId' => $officeAllocationID,
                            'transactionStatus' =>'Allocated in '.$officeAllocationCode,
                            'transactionDate'=>date('Y-m-d H:i:sa'),
                            'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                        );
                        $this->AllocationByManagerModel->insert('bill_transaction_history',$history);
                    }
                }
            }
        }
        $this->printInsertedData();
    }

    public function loadOfficeAllocationsBills($id){
        $data['allocationId']=$id;
        $this->session->unset_userdata('officeAllocation');
        $data['current_allocations']=$this->OfficeAllocationModel->loadAllocatedBills('allocations_officebills',$id);

        // print_r($data['current_allocations']);exit;
        $routeBills="";
        foreach ($data['current_allocations'] as $items) {
            $routeBills=$items;
            $oldSession =  $this->session->userdata('officeAllocation');
            if(empty($oldSession)){
                $routes[]=$routeBills;
                $this->session->set_userdata('officeAllocation', $routes);
            }else{
                if(!in_array($items,$oldSession)){
                    array_push($oldSession, $routeBills);
                    $this->session->set_userdata('officeAllocation', $oldSession);
                }
            }
        }
        // print_r($data['allocations']);exit;
        $data['company']=$this->OfficeAllocationModel->getdata('company');
        $data['bills']=$this->OfficeAllocationModel->getdata('bills');

        $allocationCode=$this->OfficeAllocationModel->load('allocations_officeadjustment',$id);
        $data['allocationCode']=$allocationCode[0]['allocationCode'];
        $data['remark']=$allocationCode[0]['remark'];
        $data['title']=$allocationCode[0]['title'];

        // print_r($data['current_allocations']);exit;
        $this->load->view('owner/addOfficeAllocationBillsView',$data);
    }

    public function CompCurrentBills(){
        $compName=$this->input->post("cmpName");
        $data['billNos']=$this->OfficeAllocationModel->getBillNosByCompany($compName);
        foreach($data['billNos'] as $item){
            $billNo=$item['billNo']." : ".$item['retailerName'];
        ?>   
          <option value="<?php echo $billNo;?>"/>
        <?php    
        }
    }


    public function getCurrentBills() {
        $this->load->model('AllocationByManagerModel');
        $billId=$this->session->userdata('officeAllocation');

        $fromNo= $this->input->post('from');
        $toNo= $this->input->post('to');

        $fromNo=explode(" : ",$fromNo);
        $toNo=explode(" : ",$toNo);

        $fromNo=$fromNo[0];
        $toNo=$toNo[0];

        if(!empty($billId)){
            for($i=0;$i<count($billId);$i++) {
                if(trim($billId[$i]['billNo'])==trim($fromNo) || trim($billId[$i]['billNo'])==trim($toNo)) {
                    $fromNo = "";
                    $toNo="";           
                }
            }
        }
               
        $response=array();
        $currentBillIDs=array();
        $newCurrentBills=array();

        if((!empty($fromNo)) || (!empty($toNo))){
            $currentBills=$this->AllocationByManagerModel->loadOfficeBills('bills', $fromNo, $toNo);   
            foreach($currentBills as $row){
                $response[] = $row;
            }

            $no=0;

            $routeBills="";
            foreach ($response as $items) {
                $routeBills=$items;
                $oldSession = $this->session->userdata('officeAllocation');
                if(empty($oldSession)){
                    $routes[]=$routeBills;
                    $this->session->set_userdata('officeAllocation', $routes);
                }else{
                    if(!in_array($items,$oldSession)){
                        array_push($oldSession, $routeBills);
                        $this->session->set_userdata('officeAllocation', $oldSession);
                    }
                }
            }
            $this->printTableData();
        }else{
            $this->printTableData();
        }
    }

    public function getCurrentBillsWithAdditions() {
        $this->load->model('AllocationByManagerModel');
        $addBill=$this->input->post('addBill');


        $addBill=explode(" : ",$addBill);
        $addBill=$addBill[0];


        $response=array();
        $currentBillIDs=array();
        
        $bills=$this->session->userdata('officeAllocation');


        if(!empty($bills)){
            for($i=0;$i<count($bills);$i++) {
                if(trim($bills[$i]['billNo'])==trim($addBill)) {
                    $addBill = "";
                }
            }
        }
        if((!empty($addBill))){
            $data['currentBills']=$this->AllocationByManagerModel->loadCurrentBillsByNo('bills', $addBill);
            foreach($data['currentBills'] as $row){ 
                $response[] = $row;
            }

        ?>

        <?php
            $no=0;

            $routeBills="";
            foreach ($response as $items) {
                $routeBills=$items;
                $oldSession =$this->session->userdata('officeAllocation');
                if(empty($oldSession)){
                    $routes[]=$routeBills;
                    $this->session->set_userdata('officeAllocation', $routes);
                }else{
                    if(!in_array($items,$oldSession)){
                        array_push($oldSession, $routeBills);
                        $this->session->set_userdata('officeAllocation', $oldSession);
                    }
                }
            }

            $this->printTableData();         
        }else{
            $this->printTableData(); 
        }
    }


    public function removeBillIdFromSession(){
        $id=$this->input->post('rmId');
        $newCurrentArray=array();
        $currentBills = $this->session->userdata('officeAllocation');
        foreach ($currentBills AS $key => $value) {
            if ($id == $value['id']){
                unset($currentBills[$key]);
            }
        }

        foreach($currentBills as $newItem){
            $newCurrentArray[]=$newItem;
        }
        $this->session->set_userdata('officeAllocation', $newCurrentArray);
    }

    public function insertOfficeAllocationData(){
        $this->load->model('AllocationByManagerModel');

        $userId = $this->session->userdata[$this->projectSessionName]['id'];

        $billId=$this->session->userdata('officeAllocation');
        $company=$this->input->post('company');

        $allocationCount=$this->AllocationByManagerModel->getOfficeAllocationCount('allocations_officeadjustment');
        $officeAllocationCode="ofc-".date('dmy')." : ".(count($allocationCount)+1);

        if(!empty($billId)){
            $allocationData=array
            (   'createdAt' => date("Y-m-d H:i:sa"),
                'createdBy'=>$userId,
                'company'=>$company,
                'allocationCode' => $officeAllocationCode,
                'isOfficeAllocation'=>1
            );

            $this->AllocationByManagerModel->insert('allocations_officeadjustment',$allocationData);
            if($this->db->affected_rows()>0){
                $lastInsertedId=$this->db->insert_id();
                foreach($billId as $itm){
                    $details=array(
                        'allocationId'=>$lastInsertedId,
                        'billId'=>$itm['id']
                    );
                    $this->AllocationByManagerModel->insert('allocations_officebills',$details);
                }
                echo "Record inserted";
            }
        }else{
            echo "Bills not present";
        }
    }

    public function clearedOfficeAllocationBill(){
        $id=$this->input->post('billId');
        $rowNo=$this->input->post('rowNo');
        // $bill=$this->OfficeAllocationModel->load('bills',$id); 
        $officeInfo=$this->session->userdata('officeAllocationInfo');
        // print_r($officeInfo);exit;
        $officeAllocationID="";
        if(!empty($officeInfo)){
            $officeAllocationID=$officeInfo['officeAllocationID'];
        }else{
            $officeAllocationID="";
        }  

         $bill=$this->OfficeAllocationModel->loadCurrentAllocatedBills('bills',$id,$officeAllocationID);
?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                   <center> <h4>
                     Cleared Bill For Bill No : <?php echo $bill[0]['billNo'];?>
                    </h4></center>
                </div>
                    
                <div class="body">
                <div class="table-responsive">
                <div class="body">
                    <div class="demo-masked-input">
                            <div class="col-md-12">
                                <input type="hidden" id="penAmt" name="penAmt" value="<?php echo $bill[0]['pendingAmt']; ?>">
                                <input type="hidden" id="rowNo" name="rowNo" value="<?php echo $rowNo; ?>">
                                <input type="hidden" id="billId" name="billId" value="<?php echo $id; ?>">
                                <div class="col-md-3"></div>
                                <div class="col-md-6">                                       
                                    <p>
                                      <b> Amount </b>
                                    </p>
                                    <div class="form-line">
                                        <input onkeypress="return numbersonly(this, event);" onfocus="this.select();" autofocus="autofocus" type="text" id="amt" value="<?php if($bill[0]['a_amount'] > 0){ echo $bill[0]['a_amount']; }else{ echo $bill[0]['pendingAmt']; } ?>" name="cashAmt" class="form-control">           
                                    </div>  <br>
                                    <div style="color:red;" id="cashRes"></div>         
                                </div>
                                <div class="col-md-3"></div>
                            </div>

                        <div class="col-md-12">
                                <center>                                               
                                        <button id="clearSubmit" data-dismiss="modal" class="btn btn-primary m-t-15 waves-effect">
                                            <i class="material-icons">save</i> 
                                            <span class="icon-name">
                                            Save
                                            </span>
                                        </button> 
                                         <button data-dismiss="modal" type="button" class="btn btn-danger m-t-15 waves-effect">
                                            <i class="material-icons">cancel</i><span class="icon-name">cancel</span>
                                        </button>
                                </center>

                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    }

    public function cashOfficeAllocationBill(){
        $id=$this->input->post('billId');
        $rowNo=$this->input->post('rowNo');
        // $bill=$this->OfficeAllocationModel->load('bills',$id); 
        $officeInfo=$this->session->userdata('officeAllocationInfo');
        // print_r($officeInfo);exit;
        $officeAllocationID="";
        if(!empty($officeInfo)){
            $officeAllocationID=$officeInfo['officeAllocationID'];
        }else{
            $officeAllocationID="";
        }  

         $bill=$this->OfficeAllocationModel->loadCurrentAllocatedBills('bills',$id,$officeAllocationID);
?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                   <center> <h4>
                     Cash Bill For Bill No : <?php echo $bill[0]['billNo'];?>
                    </h4></center>
                </div>
                    
                <div class="body">
                <div class="table-responsive">
                <div class="body">
                    <div class="demo-masked-input">
                            <div class="col-md-12">
                                <input type="hidden" id="penAmt" name="penAmt" value="<?php echo $bill[0]['pendingAmt']; ?>">
                                <input type="hidden" id="rowNo" name="rowNo" value="<?php echo $rowNo; ?>">
                                <input type="hidden" id="billId" name="billId" value="<?php echo $id; ?>">
                                <div class="col-md-3"></div>
                                <div class="col-md-6">                                       
                                    <p>
                                      <b> Amount </b>
                                    </p>
                                    <div class="form-line">
                                        <input onkeypress="return numbersonly(this, event);" onfocus="this.select();" autofocus="autofocus" type="text" id="amt" value="<?php if($bill[0]['a_amount'] > 0){ echo $bill[0]['a_amount']; }else{ echo $bill[0]['pendingAmt']; } ?>" name="cashAmt" class="form-control">           
                                    </div>  <br>
                                    <div style="color:red;" id="cashRes"></div>         
                                </div>
                                <div class="col-md-3"></div>
                            </div>

                        <div class="col-md-12">
                                <center>                                               
                                        <button id="cashSubmit" data-dismiss="modal" class="btn btn-primary m-t-15 waves-effect">
                                            <i class="material-icons">save</i> 
                                            <span class="icon-name">
                                            Save
                                            </span>
                                        </button> 
                                         <button data-dismiss="modal" type="button" class="btn btn-danger m-t-15 waves-effect">
                                            <i class="material-icons">cancel</i><span class="icon-name">cancel</span>
                                        </button>
                                </center>

                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    }

    public function existingClearedOfficeAllocationBill(){
        $id=$this->input->post('billId');
        $rowNo=$this->input->post('rowNo');
        $officeAllocationID=$this->input->post('alId');
        $bill=$this->OfficeAllocationModel->loadCurrentAllocatedBills('bills',$id,$officeAllocationID);
        // $bill=$this->OfficeAllocationModel->load('bills',$id); 

?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                   <center> <h4>
                     Cleared Bill For Bill No : <?php echo $bill[0]['billNo'];?>
                    </h4></center>
                </div>
                    
                <div class="body">
                <div class="table-responsive">
                <div class="body">
                    <div class="demo-masked-input">
                            <div class="col-md-12">
                                <input type="hidden" id="penAmt" name="penAmt" value="<?php echo $bill[0]['pendingAmt']; ?>">
                                <input type="hidden" id="letAlId" name="letAlId" value="<?php echo $officeAllocationID; ?>">
                                <input type="hidden" id="rowNo" name="rowNo" value="<?php echo $rowNo; ?>">
                                <input type="hidden" id="billId" name="billId" value="<?php echo $id; ?>">
                                <div class="col-md-3"></div>
                                <div class="col-md-6">                                       
                                    <p>
                                      <b> Amount </b>
                                    </p>
                                    <div class="form-line">
                                        <input onkeypress="return numbersonly(this, event);" onfocus="this.select();" autofocus="autofocus" type="text" id="amt" value="<?php if($bill[0]['a_amount'] > 0){ echo $bill[0]['a_amount']; }else{ echo $bill[0]['pendingAmt']; } ?>" name="cashAmt" class="form-control">           
                                    </div>  <br>
                                    <div style="color:red;" id="cashRes"></div>         
                                </div>
                                <div class="col-md-3"></div>
                            </div>

                        <div class="col-md-12">
                                <center>                                               
                                        <button id="clearExistingSubmit" data-dismiss="modal" class="btn btn-primary m-t-15 waves-effect">
                                            <i class="material-icons">save</i> 
                                            <span class="icon-name">
                                            Save
                                            </span>
                                        </button> 
                                         <button data-dismiss="modal" type="button" class="btn btn-danger m-t-15 waves-effect">
                                            <i class="material-icons">cancel</i><span class="icon-name">cancel</span>
                                        </button>
                                </center>

                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    }

    public function existingCashOfficeAllocationBill(){
        $id=$this->input->post('billId');
        $rowNo=$this->input->post('rowNo');
        $officeAllocationID=$this->input->post('alId');
        $bill=$this->OfficeAllocationModel->loadCurrentAllocatedBills('bills',$id,$officeAllocationID);
        // $bill=$this->OfficeAllocationModel->load('bills',$id); 

?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                   <center> <h4>
                     Cash Bill For Bill No : <?php echo $bill[0]['billNo'];?>
                    </h4></center>
                </div>
                    
                <div class="body">
                <div class="table-responsive">
                <div class="body">
                    <div class="demo-masked-input">
                            <div class="col-md-12">
                                <input type="hidden" id="penAmt" name="penAmt" value="<?php echo $bill[0]['pendingAmt']; ?>">
                                <input type="hidden" id="letAlId" name="letAlId" value="<?php echo $officeAllocationID; ?>">
                                <input type="hidden" id="rowNo" name="rowNo" value="<?php echo $rowNo; ?>">
                                <input type="hidden" id="billId" name="billId" value="<?php echo $id; ?>">
                                <div class="col-md-3"></div>
                                <div class="col-md-6">                                       
                                    <p>
                                      <b> Amount </b>
                                    </p>
                                    <div class="form-line">
                                        <input onkeypress="return numbersonly(this, event);" onfocus="this.select();" autofocus="autofocus" type="text" id="amt" value="<?php if($bill[0]['a_amount'] > 0){ echo $bill[0]['a_amount']; }else{ echo $bill[0]['pendingAmt']; } ?>" name="cashAmt" class="form-control">           
                                    </div>  <br>
                                    <div style="color:red;" id="cashRes"></div>         
                                </div>
                                <div class="col-md-3"></div>
                            </div>

                        <div class="col-md-12">
                                <center>                                               
                                        <button id="cashExistingSubmit" data-dismiss="modal" class="btn btn-primary m-t-15 waves-effect">
                                            <i class="material-icons">save</i> 
                                            <span class="icon-name">
                                            Save
                                            </span>
                                        </button> 
                                         <button data-dismiss="modal" type="button" class="btn btn-danger m-t-15 waves-effect">
                                            <i class="material-icons">cancel</i><span class="icon-name">cancel</span>
                                        </button>
                                </center>

                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    }

    public function updatedClearedAmount(){
        $billId=$this->input->post('billId');
        $amount=$this->input->post('amount');
        $rowNo=$this->input->post('rowNo');

        $officeInfo=$this->session->userdata('officeAllocationInfo');
        // print_r($officeInfo);exit;
        $officeAllocationID="";
        if(!empty($officeInfo)){
            $officeAllocationID=$officeInfo['officeAllocationID'];
        }else{
            $officeAllocationID="";
        }    

        $existData=$this->OfficeAllocationModel->checkInfo('allocations_officebills',$billId,$officeAllocationID); 
        if($amount>0){
            $prevAmt=$existData[0]['amount'];
            $bill=$this->OfficeAllocationModel->load('bills',$billId); 
            $pendingAmt=$bill[0]['pendingAmt'];
            $officeAdjustment=$bill[0]['officeAdjustmentBillAmount'];

            $totalPending=($pendingAmt+$prevAmt)-$amount;
            $totalOfficeAdjustment=($officeAdjustment-$prevAmt)+$amount;
            // $data=array(
            //     'pendingAmt'=>$totalPending,
            //     'officeAdjustmentBillAmount'=>$totalOfficeAdjustment
            // );

            // $this->OfficeAllocationModel->update('bills',$data,$billId);
            // if($this->db->affected_rows()>0){
                $allocation_data=array(
                    'amount'=>$amount,
                    'transactionType'=>'cleared',
                    'updatedAt'=>date('Y-m-d H:i:sa')
                );

                $officeInfo=$this->session->userdata('officeAllocationInfo');
                if(!empty($officeInfo)){
                    $officeAllocationID=$officeInfo['officeAllocationID'];
                    $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$officeAllocationID);   
                }
                
                
            // }

            $newCurrentBills=$this->OfficeAllocationModel->loadCurrentAllocatedBills('bills',$billId,$officeAllocationID);
                $no=0;
                foreach($newCurrentBills as $items){
                    $id=$items['id'];
                    $pastBillIDs[$no]=$id;
                    $no++;

            ?>  
                    <tr id="status-id<?php echo $rowNo; ?>">
                        <td><?php echo $rowNo; ?></td>
                        <td style="display: none"><?php echo $items['id']; ?></td>
                        <td><?php echo $items['billNo']; ?></td>
                        <td><?php echo $items['date']; ?></td>
                        <td><?php echo $items['retailerName']; ?></td>
                        <td><?php echo $items['netAmount']; ?></td>
                        <td><?php echo $items['receivedAmt']; ?></td>
                        <td><?php echo $items['SRAmt']; ?></td>
                        <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                        <td><?php echo $items['pendingAmt']; ?></td>
                        <td><?php echo $amount; ?></td>
                        <td><?php echo 'Office Adjustment'; ?></td>
                        <td>
                            <button id="srM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-success waves-effect" data-type="basic"><span>Cleared</span></button>
                            
                            <button id="pendingM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>Pending</span></button>

                        <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                            <button id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php }else{ ?>
                            <button id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php } ?>

                    <?php if($items['a_type']==""){ ?>
                        <a>
                            <button onclick="deleteFromTable(this,'<?php echo $items['id'];?>');" class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php }else{ ?>
                        <a>
                            <button disabled class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php } ?> 
                        </td>
                    </tr>
            <?php
                }
        }else{
            $prevAmt=$existData[0]['amount'];
            $bill=$this->OfficeAllocationModel->load('bills',$billId); 
            $pendingAmt=$bill[0]['pendingAmt'];
            $officeAdjustment=$bill[0]['officeAdjustmentBillAmount'];

            $totalPending=$pendingAmt+$prevAmt;
            $totalOfficeAdjustment=$officeAdjustment-$prevAmt;
            // $data=array(
            //     'pendingAmt'=>$totalPending,
            //     'officeAdjustmentBillAmount'=>$totalOfficeAdjustment
            // );

            // $this->OfficeAllocationModel->update('bills',$data,$billId);
            // if($this->db->affected_rows()>0){
                $allocation_data=array(
                    'amount'=>0,
                    'transactionType'=>'',
                    'updatedAt'=>date('Y-m-d H:i:sa')
                );

                $officeInfo=$this->session->userdata('officeAllocationInfo');
                if(!empty($officeInfo)){
                    $officeAllocationID=$officeInfo['officeAllocationID'];
                    $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$officeAllocationID);   
                }
               
            // }

            $newCurrentBills=$this->OfficeAllocationModel->loadCurrentAllocatedBills('bills',$billId,$officeAllocationID);
                $no=0;
                foreach($newCurrentBills as $items){
                    $id=$items['id'];
                    $pastBillIDs[$no]=$id;
                    $no++;

            ?>  
                    <tr id="status-id<?php echo $rowNo; ?>">
                        <td><?php echo $rowNo; ?></td>
                        <td style="display: none"><?php echo $items['id']; ?></td>
                        <td><?php echo $items['billNo']; ?></td>
                        <td><?php echo $items['date']; ?></td>
                        <td><?php echo $items['retailerName']; ?></td>
                        <td><?php echo $items['netAmount']; ?></td>
                        <td><?php echo $items['receivedAmt']; ?></td>
                        <td><?php echo $items['SRAmt']; ?></td>
                        <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                        <td><?php echo $items['pendingAmt']; ?></td>
                        <td><?php echo '0.00'; ?></td>
                        <td><?php echo $items['a_type']; ?></td>
                        <td>
                            <button id="srM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-success waves-effect" data-type="basic"><span>Cleared</span></button>
                            
                            <button id="pendingM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>Pending</span></button>

                        <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                            <button id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php }else{ ?>
                            <button id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php } ?>

                    <?php if($items['a_type']==""){ ?>
                        <a>
                            <button onclick="deleteFromTable(this,'<?php echo $items['id'];?>');" class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php }else{ ?>
                        <a>
                            <button disabled class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php } ?> 
                        </td>
                    </tr>
            <?php
                }

        }
    }

    public function updatedCashAmount(){
        $billId=$this->input->post('billId');
        $amount=$this->input->post('amount');
        $rowNo=$this->input->post('rowNo');

        $officeInfo=$this->session->userdata('officeAllocationInfo');
        // print_r($officeInfo);exit;
        $officeAllocationID="";
        if(!empty($officeInfo)){
            $officeAllocationID=$officeInfo['officeAllocationID'];
        }else{
            $officeAllocationID="";
        }    

        $existData=$this->OfficeAllocationModel->checkInfo('allocations_officebills',$billId,$officeAllocationID); 
        if($amount>0){
            $prevAmt=$existData[0]['amount'];
            $bill=$this->OfficeAllocationModel->load('bills',$billId); 
            $pendingAmt=$bill[0]['pendingAmt'];
            $officeAdjustment=$bill[0]['officeAdjustmentBillAmount'];

            $totalPending=($pendingAmt+$prevAmt)-$amount;
            $totalOfficeAdjustment=($officeAdjustment-$prevAmt)+$amount;
            
            $allocation_data=array(
                'amount'=>$amount,
                'transactionType'=>'cash',
                'updatedAt'=>date('Y-m-d H:i:sa')
            );

            $officeInfo=$this->session->userdata('officeAllocationInfo');
            if(!empty($officeInfo)){
                $officeAllocationID=$officeInfo['officeAllocationID'];
                $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$officeAllocationID);   
            }

            $newCurrentBills=$this->OfficeAllocationModel->loadCurrentAllocatedBills('bills',$billId,$officeAllocationID);
                $no=0;
                foreach($newCurrentBills as $items){
                    $id=$items['id'];
                    $pastBillIDs[$no]=$id;
                    $no++;

            ?>  
                    <tr id="status-id<?php echo $rowNo; ?>">
                        <td><?php echo $rowNo; ?></td>
                        <td style="display: none"><?php echo $items['id']; ?></td>
                        <td><?php echo $items['billNo']; ?></td>
                        <td><?php echo $items['date']; ?></td>
                        <td><?php echo $items['retailerName']; ?></td>
                        <td><?php echo $items['netAmount']; ?></td>
                        <td><?php echo $items['receivedAmt']; ?></td>
                        <td><?php echo $items['SRAmt']; ?></td>
                        <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                        <td><?php echo $items['pendingAmt']; ?></td>
                        <td><?php echo $amount; ?></td>
                        <td><?php echo $items['a_type']; ?></td>
                        <td>
                            <button id="srM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-success waves-effect" data-type="basic"><span>Cleared</span></button>
                            
                            <button id="pendingM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>Pending</span></button>

                        <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                            <button id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php }else{ ?>
                            <button id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php } ?>

                    <?php if($items['a_type']==""){ ?>
                        <a>
                            <button onclick="deleteFromTable(this,'<?php echo $items['id'];?>');" class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php }else{ ?>
                        <a>
                            <button disabled class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php } ?> 
                        </td>
                    </tr>
            <?php
                }
        }else{
            $prevAmt=$existData[0]['amount'];
            $bill=$this->OfficeAllocationModel->load('bills',$billId); 
            $pendingAmt=$bill[0]['pendingAmt'];
            $officeAdjustment=$bill[0]['officeAdjustmentBillAmount'];

            $totalPending=$pendingAmt+$prevAmt;
            $totalOfficeAdjustment=$officeAdjustment-$prevAmt;
            
            $allocation_data=array(
                'amount'=>0,
                'transactionType'=>'',
                'updatedAt'=>date('Y-m-d H:i:sa')
            );

            $officeInfo=$this->session->userdata('officeAllocationInfo');
            if(!empty($officeInfo)){
                $officeAllocationID=$officeInfo['officeAllocationID'];
                $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$officeAllocationID);   
            }
               
            
            $newCurrentBills=$this->OfficeAllocationModel->loadCurrentAllocatedBills('bills',$billId,$officeAllocationID);
                $no=0;
                foreach($newCurrentBills as $items){
                    $id=$items['id'];
                    $pastBillIDs[$no]=$id;
                    $no++;

            ?>  
                    <tr id="status-id<?php echo $rowNo; ?>">
                        <td><?php echo $rowNo; ?></td>
                        <td style="display: none"><?php echo $items['id']; ?></td>
                        <td><?php echo $items['billNo']; ?></td>
                        <td><?php echo $items['date']; ?></td>
                        <td><?php echo $items['retailerName']; ?></td>
                        <td><?php echo $items['netAmount']; ?></td>
                        <td><?php echo $items['receivedAmt']; ?></td>
                        <td><?php echo $items['SRAmt']; ?></td>
                        <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                        <td><?php echo $items['pendingAmt']; ?></td>
                        <td><?php echo '0.00'; ?></td>
                        <td><?php echo $items['a_type']; ?></td>
                        <td>
                            <button id="srM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-success waves-effect" data-type="basic"><span>Cleared</span></button>
                            
                            <button id="pendingM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>Pending</span></button>

                        <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                            <button id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php }else{ ?>
                            <button id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php } ?>

                    <?php if($items['a_type']==""){ ?>
                        <a>
                            <button onclick="deleteFromTable(this,'<?php echo $items['id'];?>');" class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php }else{ ?>
                        <a>
                            <button disabled class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php } ?> 
                        </td>
                    </tr>
            <?php
                }

        }
    }


     public function updatedExistingClearedAmount(){
        $billId=$this->input->post('billId');
        $amount=$this->input->post('amount');
        $rowNo=$this->input->post('rowNo');
        $officeAllocationID=$this->input->post('alId');

        $existData=$this->OfficeAllocationModel->checkInfo('allocations_officebills',$billId,$officeAllocationID); 

        if($amount >0){
            $prevAmt=$existData[0]['amount'];
            $bill=$this->OfficeAllocationModel->load('bills',$billId); 
            $pendingAmt=$bill[0]['pendingAmt'];
            $officeAdjustment=$bill[0]['officeAdjustmentBillAmount'];

            $totalPending=($pendingAmt+$prevAmt)-$amount;
            $totalOfficeAdjustment=($officeAdjustment-$prevAmt)+$amount;
            // $data=array(
            //     'pendingAmt'=>$totalPending,
            //     'officeAdjustmentBillAmount'=>$totalOfficeAdjustment,
            //     'billType'=>'officeAdjustmentBill'
            // );

            // $this->OfficeAllocationModel->update('bills',$data,$billId);
            // if($this->db->affected_rows()>0){
                $allocation_data=array(
                    'amount'=>$amount,
                    'transactionType'=>'cleared',
                    'updatedAt'=>date('Y-m-d H:i:sa')
                );

               
            $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$officeAllocationID);   
                
            // }

            $newCurrentBills=$this->OfficeAllocationModel->loadCurrentAllocatedBills('bills',$billId,$officeAllocationID);
                $no=0;
                foreach($newCurrentBills as $items){
                    $id=$items['id'];
                    $pastBillIDs[$no]=$id;
                    $no++;

            ?>  
                    <tr id="status-id<?php echo $rowNo; ?>">
                        <td><?php echo $rowNo; ?></td>
                        <td style="display: none"><?php echo $items['id']; ?></td>
                        <td><?php echo $items['billNo']; ?></td>
                        <td><?php echo $items['date']; ?></td>
                        <td><?php echo $items['retailerName']; ?></td>
                        <td><?php echo $items['netAmount']; ?></td>
                        <td><?php echo $items['receivedAmt']; ?></td>
                        <td><?php echo $items['SRAmt']; ?></td>
                        <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                        <td><?php echo $items['pendingAmt']; ?></td>
                        <td><?php echo $amount; ?></td>
                        <td><?php echo 'Office Adjustment'; ?></td>
                        <td>
                            <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-success waves-effect" data-type="basic"><span>Cleared</span></button>
                            
                            <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>Pending</span></button>

                        <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                            <button id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php }else{ ?>
                            <button id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php } ?>

                    <?php if($items['a_type']==""){ ?>
                        <a>
                            <button onclick="deleteFromTable(this,'<?php echo $items['id'];?>');" class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php }else{ ?>
                        <a>
                            <button disabled class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php } ?> 
                        </td>
                    </tr>
            <?php
                }
        }else{
            $prevAmt=$existData[0]['amount'];
           
            $bill=$this->OfficeAllocationModel->load('bills',$billId); 
            $pendingAmt=$bill[0]['pendingAmt'];
            $officeAdjustment=$bill[0]['officeAdjustmentBillAmount'];

            $totalPending=$pendingAmt+$prevAmt;
            $totalOfficeAdjustment=$officeAdjustment-$prevAmt;
            // $data=array(
            //     'pendingAmt'=>$totalPending,
            //     'officeAdjustmentBillAmount'=>$totalOfficeAdjustment,
            //     'billType'=>'officeAdjustmentBill'
            // );

            // $this->OfficeAllocationModel->update('bills',$data,$billId);
            // if($this->db->affected_rows()>0){
                $allocation_data=array(
                    'amount'=>0,
                    'transactionType'=>'',
                    'updatedAt'=>date('Y-m-d H:i:sa')
                );
               
                $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$officeAllocationID);   
            // }

            $newCurrentBills=$this->OfficeAllocationModel->loadCurrentAllocatedBills('bills',$billId,$officeAllocationID);
                
            $no=0;
            foreach($newCurrentBills as $items){
                $id=$items['id'];
                $pastBillIDs[$no]=$id;
                $no++;

        ?>  
                <tr id="status-id<?php echo $rowNo; ?>">
                    <td><?php echo $rowNo; ?></td>
                    <td style="display: none"><?php echo $items['id']; ?></td>
                    <td><?php echo $items['billNo']; ?></td>
                    <td><?php echo $items['date']; ?></td>
                    <td><?php echo $items['retailerName']; ?></td>
                    <td><?php echo $items['netAmount']; ?></td>
                    <td><?php echo $items['receivedAmt']; ?></td>
                    <td><?php echo $items['SRAmt']; ?></td>
                    <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                    <td><?php echo $items['pendingAmt']; ?></td>
                   <td><?php echo '0.00'; ?></td>
                    <td><?php echo $items['a_type']; ?></td>
                    <td>
                        <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-success waves-effect" data-type="basic"><span>Cleared</span></button>
                        
                        <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>Pending</span></button>

                    <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                        <button id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php }else{ ?>
                        <button id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php } ?>

                    <?php if($items['a_type']==""){ ?>
                        <a>
                            <button onclick="deleteFromTable(this,'<?php echo $items['id'];?>');" class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php }else{ ?>
                        <a>
                            <button disabled class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php } ?> 
                    </td>
                </tr>
        <?php
            }
        }

    }

     public function updatedExistingCashAmount(){
        $billId=$this->input->post('billId');
        $amount=$this->input->post('amount');
        $rowNo=$this->input->post('rowNo');
        $officeAllocationID=$this->input->post('alId');

        $existData=$this->OfficeAllocationModel->checkInfo('allocations_officebills',$billId,$officeAllocationID); 

        if($amount >0){
            $prevAmt=$existData[0]['amount'];
            $bill=$this->OfficeAllocationModel->load('bills',$billId); 
            $pendingAmt=$bill[0]['pendingAmt'];
            $officeAdjustment=$bill[0]['officeAdjustmentBillAmount'];

            $totalPending=($pendingAmt+$prevAmt)-$amount;
            $totalOfficeAdjustment=($officeAdjustment-$prevAmt)+$amount;
            
            $allocation_data=array(
                'amount'=>$amount,
                'transactionType'=>'cash',
                'updatedAt'=>date('Y-m-d H:i:sa')
            );
               
            $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$officeAllocationID);   

            $newCurrentBills=$this->OfficeAllocationModel->loadCurrentAllocatedBills('bills',$billId,$officeAllocationID);
                $no=0;
                foreach($newCurrentBills as $items){
                    $id=$items['id'];
                    $pastBillIDs[$no]=$id;
                    $no++;

            ?>  
                    <tr id="status-id<?php echo $rowNo; ?>">
                        <td><?php echo $rowNo; ?></td>
                        <td style="display: none"><?php echo $items['id']; ?></td>
                        <td><?php echo $items['billNo']; ?></td>
                        <td><?php echo $items['date']; ?></td>
                        <td><?php echo $items['retailerName']; ?></td>
                        <td><?php echo $items['netAmount']; ?></td>
                        <td><?php echo $items['receivedAmt']; ?></td>
                        <td><?php echo $items['SRAmt']; ?></td>
                        <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                        <td><?php echo $items['pendingAmt']; ?></td>
                        <td><?php echo $amount; ?></td>
                        <td><?php echo $items['a_type']; ?></td>
                        <td>
                            <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-success waves-effect" data-type="basic"><span>Cleared</span></button>
                            
                            <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>Pending</span></button>

                        <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                            <button id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php }else{ ?>
                            <button id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php } ?>

                    <?php if($items['a_type']==""){ ?>
                        <a>
                            <button onclick="deleteFromTable(this,'<?php echo $items['id'];?>');" class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php }else{ ?>
                        <a>
                            <button disabled class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php } ?> 
                        </td>
                    </tr>
            <?php
                }
        }else{
            $prevAmt=$existData[0]['amount'];
           
            $bill=$this->OfficeAllocationModel->load('bills',$billId); 
            $pendingAmt=$bill[0]['pendingAmt'];
            $officeAdjustment=$bill[0]['officeAdjustmentBillAmount'];

            $totalPending=$pendingAmt+$prevAmt;
            $totalOfficeAdjustment=$officeAdjustment-$prevAmt;
            
            $allocation_data=array(
                'amount'=>0,
                'transactionType'=>'',
                'updatedAt'=>date('Y-m-d H:i:sa')
            );
           
            $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$officeAllocationID);   
           

            $newCurrentBills=$this->OfficeAllocationModel->loadCurrentAllocatedBills('bills',$billId,$officeAllocationID);
                
            $no=0;
            foreach($newCurrentBills as $items){
                $id=$items['id'];
                $pastBillIDs[$no]=$id;
                $no++;

        ?>  
                <tr id="status-id<?php echo $rowNo; ?>">
                    <td><?php echo $rowNo; ?></td>
                    <td style="display: none"><?php echo $items['id']; ?></td>
                    <td><?php echo $items['billNo']; ?></td>
                    <td><?php echo $items['date']; ?></td>
                    <td><?php echo $items['retailerName']; ?></td>
                    <td><?php echo $items['netAmount']; ?></td>
                    <td><?php echo $items['receivedAmt']; ?></td>
                    <td><?php echo $items['SRAmt']; ?></td>
                    <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                    <td><?php echo $items['pendingAmt']; ?></td>
                   <td><?php echo '0.00'; ?></td>
                    <td><?php echo $items['a_type']; ?></td>
                    <td>
                        <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-success waves-effect" data-type="basic"><span>Cleared</span></button>
                        
                        <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>Pending</span></button>

                    <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                        <button id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php }else{ ?>
                        <button id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php } ?>

                    <?php if($items['a_type']==""){ ?>
                        <a>
                            <button onclick="deleteFromTable(this,'<?php echo $items['id'];?>');" class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php }else{ ?>
                        <a>
                            <button disabled class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php } ?> 
                    </td>
                </tr>
        <?php
            }
        }

    }


    public function updatedPendingAmount(){
        $billId=$this->input->post('billId');
        $rowNo=$this->input->post('rowNo');

        $officeInfo=$this->session->userdata('officeAllocationInfo');
        // print_r($officeInfo);exit;
        $officeAllocationID="";
        if(!empty($officeInfo)){
            $officeAllocationID=$officeInfo['officeAllocationID'];
        }else{
            $officeAllocationID="";
        }    
        
        $existData=$this->OfficeAllocationModel->checkInfo('allocations_officebills',$billId,$officeAllocationID); 

        if($existData[0]['transactionType']==="pending"){
            $allocation_data=array(
                'amount'=>0,
                'transactionType'=>'',
                'updatedAt'=>date('Y-m-d H:i:sa')
            );
            
            $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$officeAllocationID);   
        }else{
            $allocation_data=array(
                'amount'=>0,
                'transactionType'=>'pending',
                'updatedAt'=>date('Y-m-d H:i:sa')
            );
            
            $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$officeAllocationID);   
        }  

        $newCurrentBills=$this->OfficeAllocationModel->loadCurrentAllocatedBills('bills',$billId,$officeAllocationID);

        $no=0;
        foreach($newCurrentBills as $items){
            $id=$items['id'];
            $pastBillIDs[$no]=$id;
            $no++;

    ?>  
            <tr id="status-id<?php echo $rowNo; ?>">
                <td><?php echo $rowNo; ?></td>
                <td style="display: none"><?php echo $items['id']; ?></td>
                <td><?php echo $items['billNo']; ?></td>
                <td><?php echo $items['date']; ?></td>
                <td><?php echo $items['retailerName']; ?></td>
                <td><?php echo $items['netAmount']; ?></td>
                <td><?php echo $items['receivedAmt']; ?></td>
                <td><?php echo $items['SRAmt']; ?></td>
                <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                <td><?php echo $items['pendingAmt']; ?></td>
                <td><?php echo $items['a_amount']; ?></td>
                <td><?php echo $items['a_type']; ?></td>
                <td>
                    <button id="srM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-success waves-effect" data-type="basic"><span>Cleared</span></button>
                    
                    <button id="pendingM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>Pending</span></button>

                <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                    <button id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                <?php }else{ ?>
                    <button id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                <?php } ?>

                    <?php if($items['a_type']==""){ ?>
                        <a>
                            <button onclick="deleteFromTable(this,'<?php echo $items['id'];?>');" class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php }else{ ?>
                        <a>
                            <button disabled class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php } ?> 
                </td>
            </tr>
    <?php
        }
    }

    public function updatedExistPendingAmount(){
        $billId=$this->input->post('billId');
        $rowNo=$this->input->post('rowNo');
        $officeAllocationID=$this->input->post('alId');


        $existData=$this->OfficeAllocationModel->checkInfo('allocations_officebills',$billId,$officeAllocationID); 

        if($existData[0]['transactionType']==="pending"){
            $allocation_data=array(
                'amount'=>0,
                'transactionType'=>'',
                'updatedAt'=>date('Y-m-d H:i:sa')
            );
            
            $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$officeAllocationID);   
        }else{
            $allocation_data=array(
                'amount'=>0,
                'transactionType'=>'pending',
                'updatedAt'=>date('Y-m-d H:i:sa')
            );
            
            $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$officeAllocationID);   
        }  
                
            $newCurrentBills=$this->OfficeAllocationModel->loadCurrentAllocatedBills('bills',$billId,$officeAllocationID);
            
            $no=0;
            foreach($newCurrentBills as $items){
                $id=$items['id'];
                $pastBillIDs[$no]=$id;
                $no++;

        ?>  
                <tr id="status-id<?php echo $rowNo; ?>">
                    <td><?php echo $rowNo; ?></td>
                    <td style="display: none"><?php echo $items['id']; ?></td>
                    <td><?php echo $items['billNo']; ?></td>
                    <td><?php echo $items['date']; ?></td>
                    <td><?php echo $items['retailerName']; ?></td>
                    <td><?php echo $items['netAmount']; ?></td>
                    <td><?php echo $items['receivedAmt']; ?></td>
                    <td><?php echo $items['SRAmt']; ?></td>
                    <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                    <td><?php echo $items['pendingAmt']; ?></td>
                    <td><?php echo $items['a_amount']; ?></td>
                    <td><?php echo $items['a_type']; ?></td>
                    <td>
                        <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-success waves-effect" data-type="basic"><span>Cleared</span></button>
                        
                        <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>Pending</span></button>

                    <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                        <button id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php }else{ ?>
                        <button id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php } ?>

                    <?php if($items['a_type']==""){ ?>
                        <a>
                            <button onclick="deleteFromTable(this,'<?php echo $items['id'];?>');" class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php }else{ ?>
                        <a>
                            <button disabled class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php } ?> 
                    </td>
                </tr>
        <?php
            }
    }

    public function updatedFsrAmount(){
        $billId=$this->input->post('billId');
        $rowNo=$this->input->post('rowNo');

        $bill=$this->OfficeAllocationModel->load('bills',$billId); 
        $netAmount=$bill[0]['netAmount'];

        $officeInfo=$this->session->userdata('officeAllocationInfo');
        $officeAllocationID="";
        if(!empty($officeInfo)){
            $officeAllocationID=$officeInfo['officeAllocationID'];
        }else{
            $officeAllocationID="";
        }    
        
        $existData=$this->OfficeAllocationModel->checkInfo('allocations_officebills',$billId,$officeAllocationID); 

        if($existData[0]['transactionType']==="fsr"){
            $allocation_data=array(
                'amount'=>0,
                'transactionType'=>'',
                'updatedAt'=>date('Y-m-d H:i:sa')
            );
            
            $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$officeAllocationID);   
        }else{
            $allocation_data=array(
                'amount'=>$netAmount,
                'transactionType'=>'fsr',
                'updatedAt'=>date('Y-m-d H:i:sa')
            );
            
            $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$officeAllocationID);   
        }  

        $newCurrentBills=$this->OfficeAllocationModel->loadCurrentAllocatedBills('bills',$billId,$officeAllocationID);
        
        $no=0;
        foreach($newCurrentBills as $items){
            $id=$items['id'];
            $pastBillIDs[$no]=$id;
            $no++;

    ?>  
            <tr id="status-id<?php echo $rowNo; ?>">
                <td><?php echo $rowNo; ?></td>
                <td style="display: none"><?php echo $items['id']; ?></td>
                <td><?php echo $items['billNo']; ?></td>
                <td><?php echo $items['date']; ?></td>
                <td><?php echo $items['retailerName']; ?></td>
                <td><?php echo $items['netAmount']; ?></td>
                <td><?php echo $items['receivedAmt']; ?></td>
                <td><?php echo $items['SRAmt']; ?></td>
                <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                <td><?php echo $items['pendingAmt']; ?></td>
                <td><?php echo $items['a_amount']; ?></td>
               <td><?php if($items['a_type']=='fsr'){ echo 'FSR'; } ?></td>
                <td>
                    <button id="srM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-success waves-effect" data-type="basic"><span>Cleared</span></button>
                    
                    <button id="pendingM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>Pending</span></button>

                 <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                    <button id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                <?php }else{ ?>
                    <button id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                <?php } ?>

                    <?php if($items['a_type']==""){ ?>
                        <a>
                            <button onclick="deleteFromTable(this,'<?php echo $items['id'];?>');" class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php }else{ ?>
                        <a>
                            <button disabled class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php } ?>
                </td>
            </tr>
    <?php
        }
    }


     public function updatedExistFsrAmount(){
        $billId=$this->input->post('billId');
        $rowNo=$this->input->post('rowNo');
        $officeAllocationID=$this->input->post('alId');

        $bill=$this->OfficeAllocationModel->load('bills',$billId); 
        $netAmount=$bill[0]['netAmount'];
        
       
        $existData=$this->OfficeAllocationModel->checkInfo('allocations_officebills',$billId,$officeAllocationID); 

        if($existData[0]['transactionType']==="fsr"){
            $allocation_data=array(
                'amount'=>0,
                'transactionType'=>'',
                'updatedAt'=>date('Y-m-d H:i:sa')
            );
            
            $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$officeAllocationID);   
        }else{
            $allocation_data=array(
                'amount'=>$netAmount,
                'transactionType'=>'fsr',
                'updatedAt'=>date('Y-m-d H:i:sa')
            );
            
            $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$officeAllocationID);   
        }  

        $newCurrentBills=$this->OfficeAllocationModel->loadCurrentAllocatedBills('bills',$billId,$officeAllocationID);
        
        $no=0;
        foreach($newCurrentBills as $items){
            $id=$items['id'];
            $pastBillIDs[$no]=$id;
            $no++;

    ?>  
            <tr id="status-id<?php echo $rowNo; ?>">
                <td><?php echo $rowNo; ?></td>
                <td style="display: none"><?php echo $items['id']; ?></td>
                <td><?php echo $items['billNo']; ?></td>
                <td><?php echo $items['date']; ?></td>
                <td><?php echo $items['retailerName']; ?></td>
                <td><?php echo $items['netAmount']; ?></td>
                <td><?php echo $items['receivedAmt']; ?></td>
                <td><?php echo $items['SRAmt']; ?></td>
                <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                <td><?php echo $items['pendingAmt']; ?></td>
                <td><?php echo $items['a_amount']; ?></td>
                <td><?php if($items['a_type']=='fsr'){ echo 'FSR'; } ?></td>
                <td>
                    <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-success waves-effect" data-type="basic"><span>Cleared</span></button>
                    
                    <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>Pending</span></button>

                 <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                    <button id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                <?php }else{ ?>
                    <button id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                <?php } ?>

                    <?php if($items['a_type']==""){ ?>
                        <a>
                            <button onclick="deleteFromTable(this,'<?php echo $items['id'];?>');" class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php }else{ ?>
                        <a>
                            <button disabled class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php } ?>
                </td>
            </tr>
    <?php
        }
    }

    public function clearAllBills(){
        $billIds=$this->input->post('billArray');
        $rowId=$this->input->post('rowArray');

        for($i=0;$i<count($billIds);$i++){
            $billId=$billIds[$i];
            $rowNo=$rowId[$i];

            $bill=$this->OfficeAllocationModel->load('bills',$billId); 
            $amount=$bill[0]['pendingAmt'];
            $pendingAmt=$bill[0]['pendingAmt'];
            $officeAdjustment=$bill[0]['officeAdjustmentBillAmount'];

            $totalPending=$pendingAmt-$amount;
            $totalOfficeAdjustment=$officeAdjustment+$amount;
            // $data=array(
            //     'pendingAmt'=>$totalPending,
            //     'officeAdjustmentBillAmount'=>$totalOfficeAdjustment,
            //     'billType'=>'officeAdjustmentBill',
            //     'isAllocated'=>1
            // );

            // $this->OfficeAllocationModel->update('bills',$data,$billId);
            // if($this->db->affected_rows()>0){

                $allocation_data=array(
                    'amount'=>$amount,
                    'transactionType'=>'cleared',
                    'updatedAt'=>date('Y-m-d H:i:sa')
                );

                $officeInfo=$this->session->userdata('officeAllocationInfo');
                if(!empty($officeInfo)){
                    $officeAllocationID=$officeInfo['officeAllocationID'];
                    $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$officeAllocationID);   
                }
                
                $newCurrentBills = $this->OfficeAllocationModel->load('bills',$billId);
                $no=0;
                foreach($newCurrentBills as $items){
                    $id=$items['id'];
                    $pastBillIDs[$no]=$id;
                    $no++;

            ?>  
                    <tr id="status-id<?php echo $rowNo; ?>">
                        <td><?php echo $rowNo; ?></td>
                        <td style="display: none"><?php echo $items['id']; ?></td>
                        <td><?php echo $items['billNo']; ?></td>
                        <td><?php echo $items['date']; ?></td>
                        <td><?php echo $items['retailerName']; ?></td>
                        <td><?php echo $items['netAmount']; ?></td>
                        <td><?php echo $items['receivedAmt']; ?></td>
                        <td><?php echo $items['SRAmt']; ?></td>
                        <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                        <td><?php echo $items['pendingAmt']; ?></td>
                        <td><?php echo $amount; ?></td>
                        <td>Office Adjustment</td>
                        <td>
                            <button id="srM" data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-success waves-effect" data-type="basic"><span>Cleared</span></button>
                            
                            <button disabled id="pendingM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>Pending</span></button>

                        <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                            <button disabled id="fsrM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php }else{ ?>
                            <button disabled id="fsrM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php } ?>

                            <a>
                                <button disabled class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                            </a>
                        </td>
                    </tr>
            <?php
                }
            // }
        }

    }


     public function clearExistAllBills(){
        $billIds=$this->input->post('billArray');
        $rowId=$this->input->post('rowArray');
        $allocationId=$this->input->post('alId');

        for($i=0;$i<count($billIds);$i++){
            $billId=$billIds[$i];
            $rowNo=$rowId[$i];

            $bill=$this->OfficeAllocationModel->load('bills',$billId); 
            $amount=$bill[0]['pendingAmt'];
            $pendingAmt=$bill[0]['pendingAmt'];
            $officeAdjustment=$bill[0]['officeAdjustmentBillAmount'];

            $totalPending=$pendingAmt-$amount;
            $totalOfficeAdjustment=$officeAdjustment+$amount;
            // $data=array(
            //     'pendingAmt'=>$totalPending,
            //     'officeAdjustmentBillAmount'=>$totalOfficeAdjustment
            // );
            // $this->OfficeAllocationModel->update('bills',$data,$billId);
            // if($this->db->affected_rows()>0){
                $allocation_data=array(
                    'amount'=>$amount,
                    'transactionType'=>'cleared',
                    'updatedAt'=>date('Y-m-d H:i:sa')
                );
                
                $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$allocationId);   

                $newCurrentBills=$this->OfficeAllocationModel->loadCurrentAllocatedBills('bills',$billId,$allocationId);
                
                $no=0;
                foreach($newCurrentBills as $items){
                    $id=$items['id'];
                    $pastBillIDs[$no]=$id;
                    $no++;

            ?>  
                    <tr id="status-id<?php echo $rowNo; ?>">
                        <td><?php echo $rowNo; ?></td>
                        <td style="display: none"><?php echo $items['id']; ?></td>
                        <td><?php echo $items['billNo']; ?></td>
                        <td><?php echo $items['date']; ?></td>
                        <td><?php echo $items['retailerName']; ?></td>
                        <td><?php echo $items['netAmount']; ?></td>
                        <td><?php echo $items['receivedAmt']; ?></td>
                        <td><?php echo $items['SRAmt']; ?></td>
                        <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                        <td><?php echo $items['pendingAmt']; ?></td>
                        <td><?php echo $items['a_amount']; ?></td>
                        <td><?php echo 'Office Adjustment'; ?></td>
                        <td>
                            <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-success waves-effect" data-type="basic"><span>Cleared</span></button>
                        
                        <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>Pending</span></button>

                        <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                            <button  id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php }else{ ?>
                            <button id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php } ?>
                         
                    <?php if($items['a_type']==""){ ?>
                        <a>
                            <button onclick="deleteFromTable(this,'<?php echo $items['id'];?>');" class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php }else{ ?>
                        <a>
                            <button disabled class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php } ?> 
                        </td>
                    </tr>
            <?php
                }
            // }
        }

    }

    public function pendingAllBills(){
        $billArray=$this->input->post('billArray');
        $rowArray=$this->input->post('rowArray');
        // echo $billId.' '.$rowNo;
        $billId="";
        $rowNo="";
        for($i=0;$i<count($billArray);$i++){
            $billId=$billArray[$i];
            $rowNo=$rowArray[$i];

            // $data=array(
            //     'billType'=>'officeAdjustmentBill',
            //     'isAllocated'=>1
            // );

            // $this->OfficeAllocationModel->update('bills',$data,$billId);
            // if($this->db->affected_rows()>0){

                $allocation_data=array(
                    'amount'=>0,
                    'transactionType'=>'pending',
                    'updatedAt'=>date('Y-m-d H:i:sa')
                );

                $officeInfo=$this->session->userdata('officeAllocationInfo');
                if(!empty($officeInfo)){
                    $officeAllocationID=$officeInfo['officeAllocationID'];
                    $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$officeAllocationID);   
                }

                $newCurrentBills = $this->OfficeAllocationModel->load('bills',$billId);
                $no=0;
                foreach($newCurrentBills as $items){
                    $id=$items['id'];
                    $pastBillIDs[$no]=$id;
                    $no++;

            ?>  
                    <tr id="status-id<?php echo $rowNo; ?>">
                        <td><?php echo $rowNo; ?></td>
                        <td style="display: none"><?php echo $items['id']; ?></td>
                        <td><?php echo $items['billNo']; ?></td>
                        <td><?php echo $items['date']; ?></td>
                        <td><?php echo $items['retailerName']; ?></td>
                        <td><?php echo $items['netAmount']; ?></td>
                        <td><?php echo $items['receivedAmt']; ?></td>
                        <td><?php echo $items['SRAmt']; ?></td>
                        <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                        <td><?php echo $items['pendingAmt']; ?></td>
                        <td></td>
                        <td>pending</td>
                        <td>
                            <button disabled id="srM" data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-success waves-effect" data-type="basic"><span>Cleared</span></button>
                            
                            <button id="pendingM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>Pending</span></button>

                        <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                            <button disabled id="fsrM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php }else{ ?>
                            <button disabled id="fsrM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php } ?>

                            <a>
                                <button disabled class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                            </a>
                        </td>
                    </tr>
            <?php
                }
            // }
        }
    }

     public function pendingExistingAllBills(){
        $billArray=$this->input->post('billArray');
        $rowArray=$this->input->post('rowArray');
        $allocationId=$this->input->post('alId');

        $billId="";
        $rowNo="";
        for($i=0;$i<count($billArray);$i++){
            $billId=$billArray[$i];
            $rowNo=$rowArray[$i];


            $existData=$this->OfficeAllocationModel->checkInfo('allocations_officebills',$billId,$allocationId); 

            if($existData[0]['transactionType']==="pending"){
                $allocation_data=array(
                    'amount'=>0,
                    'transactionType'=>'',
                    'updatedAt'=>date('Y-m-d H:i:sa')
                );
                
                $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$allocationId);   
            }else{
                $allocation_data=array(
                    'amount'=>0,
                    'transactionType'=>'pending',
                    'updatedAt'=>date('Y-m-d H:i:sa')
                );
                
                $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$allocationId);   
            }  

              
                $newCurrentBills=$this->OfficeAllocationModel->loadCurrentAllocatedBills('bills',$billId,$allocationId);

                $no=0;
                foreach($newCurrentBills as $items){
                    $id=$items['id'];
                    $pastBillIDs[$no]=$id;
                    $no++;

            ?>  
                    <tr id="status-id<?php echo $rowNo; ?>">
                        <td><?php echo $rowNo; ?></td>
                        <td style="display: none"><?php echo $items['id']; ?></td>
                        <td><?php echo $items['billNo']; ?></td>
                        <td><?php echo $items['date']; ?></td>
                        <td><?php echo $items['retailerName']; ?></td>
                        <td><?php echo $items['netAmount']; ?></td>
                        <td><?php echo $items['receivedAmt']; ?></td>
                        <td><?php echo $items['SRAmt']; ?></td>
                        <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                        <td><?php echo $items['pendingAmt']; ?></td>
                        <td><?php echo $items['a_amount']; ?></td>
                        <td><?php echo $items['a_type']; ?></td>
                        <td>
                            <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-success waves-effect" data-type="basic"><span>Cleared</span></button>
                        
                        <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>Pending</span></button>

                        <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                            <button  id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php }else{ ?>
                            <button id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php } ?>
                         
                    <?php if($items['a_type']==""){ ?>
                        <a>
                            <button onclick="deleteFromTable(this,'<?php echo $items['id'];?>');" class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php }else{ ?>
                        <a>
                            <button disabled class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php } ?> 
                        </td>
                    </tr>
            <?php
                }
            
        }
    }

    public function fsrAllBills(){
        $billIds=$this->input->post('billArray');
        $rowId=$this->input->post('rowArray');

        for($i=0;$i<count($billIds);$i++){
            $billId=$billIds[$i];
            $rowNo=$rowId[$i];

            $bill=$this->OfficeAllocationModel->load('bills',$billId); 

            if($bill[0]['netAmount']==$bill[0]['pendingAmt']){
                $allocation_data=array(
                    'amount'=>$bill[0]['netAmount'],
                    'transactionType'=>'fsr',
                    'updatedAt'=>date('Y-m-d H:i:sa')
                );

                $officeInfo=$this->session->userdata('officeAllocationInfo');
                if(!empty($officeInfo)){
                    $officeAllocationID=$officeInfo['officeAllocationID'];
                    $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$officeAllocationID);   
                }
            }

            $newCurrentBills = $this->OfficeAllocationModel->load('bills',$billId);
                $no=0;
                foreach($newCurrentBills as $items){
                    $id=$items['id'];
                    $pastBillIDs[$no]=$id;
                    $no++;

            ?>  
                    <tr id="status-id<?php echo $rowNo; ?>">
                        <td><?php echo $rowNo; ?></td>
                        <td style="display: none"><?php echo $items['id']; ?></td>
                        <td><?php echo $items['billNo']; ?></td>
                        <td><?php echo $items['date']; ?></td>
                        <td><?php echo $items['retailerName']; ?></td>
                        <td><?php echo $items['netAmount']; ?></td>
                        <td><?php echo $items['receivedAmt']; ?></td>
                        <td><?php echo $items['SRAmt']; ?></td>
                        <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                        <td><?php echo $items['pendingAmt']; ?></td>
                        <td><?php echo $bill[0]['netAmount']; ?></td>
                        <td>FSR</td>
                        <td>
                            <button disabled id="srM" data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-success waves-effect" data-type="basic"><span>Cleared</span></button>
                            
                            <button disabled id="pendingM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>Pending</span></button>

                        <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                            <button id="fsrM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php }else{ ?>
                            <button disabled id="fsrM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php } ?>

                            <a>
                                <button disabled class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                            </a>
                        </td>
                    </tr>
            <?php
                }
            
        }

    }

    public function fsrExistAllBills(){
        $billIds=$this->input->post('billArray');
        $rowId=$this->input->post('rowArray');
        $allocationId=$this->input->post('alId');

        for($i=0;$i<count($billIds);$i++){
            $billId=$billIds[$i];
            $rowNo=$rowId[$i];

            $bill=$this->OfficeAllocationModel->load('bills',$billId); 


            if($bill[0]['netAmount']==$bill[0]['pendingAmt']){
                $allocation_data=array(
                    'amount'=>$bill[0]['netAmount'],
                    'transactionType'=>'fsr',
                    'updatedAt'=>date('Y-m-d H:i:sa')
                );

                $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$allocationId);
            }

            $newCurrentBills=$this->OfficeAllocationModel->loadCurrentAllocatedBills('bills',$billId,$allocationId);
            
            $no=0;
            foreach($newCurrentBills as $items){
                $id=$items['id'];
                $pastBillIDs[$no]=$id;
                $no++;

        ?>  
                <tr id="status-id<?php echo $rowNo; ?>">
                    <td><?php echo $rowNo; ?></td>
                    <td style="display: none"><?php echo $items['id']; ?></td>
                    <td><?php echo $items['billNo']; ?></td>
                    <td><?php echo $items['date']; ?></td>
                    <td><?php echo $items['retailerName']; ?></td>
                    <td><?php echo $items['netAmount']; ?></td>
                    <td><?php echo $items['receivedAmt']; ?></td>
                    <td><?php echo $items['SRAmt']; ?></td>
                    <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                    <td><?php echo $items['pendingAmt']; ?></td>
                    <td><?php echo $items['a_amount']; ?></td>
                    <td><?php if($items['a_type']=='fsr'){ echo 'FSR'; } ?></td>
                    <td>
                        <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-success waves-effect" data-type="basic"><span>Cleared</span></button>
                    
                    <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>Pending</span></button>

                    <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                        <button  id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php }else{ ?>
                        <button disabled id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php } ?>
                     
                <?php if($items['a_type']==""){ ?>
                    <a>
                        <button onclick="deleteFromTable(this,'<?php echo $items['id'];?>');" class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                    </a>
                <?php }else{ ?>
                    <a>
                        <button disabled class="btn btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                    </a>
                <?php } ?>
                    </td>
                </tr>
        <?php
            }
        }

    }

    public function deleteBillIdFromSession(){
        $this->load->model('AllocationByManagerModel');
        $id=$this->input->post('rmId');
        $officeInfo=$this->session->userdata('officeAllocationInfo');
        if(!empty($officeInfo)){
            $allocationId=$officeInfo['officeAllocationID'];

            $checkData=$this->AllocationByManagerModel->checkInfo('allocations_officebills',$id,$allocationId);
            $billData=$this->AllocationByManagerModel->load('bills',$id);
            if(!empty($checkData)){
                $this->AllocationByManagerModel->deleteAllocationsBills('allocations_officebills',$id,$allocationId);
                if($this->db->affected_rows()>0){

                    if($billData[0]['netAmount']==$billData[0]['pendingAmt']){
                        $countBills=$this->AllocationByManagerModel->getCount('allocations_officebills',$id);
                        if($countBills>0){
                            $rtData=array
                            (
                                'remark'=>'','isAllocated'=>'0'    
                            );
                            $this->AllocationByManagerModel->update('bills',$rtData,$id);
                        }else{
                            $rtData=array
                            (
                                'billType'=>'','remark'=>'','isAllocated'=>'0'    
                            );
                            $this->AllocationByManagerModel->update('bills',$rtData,$id);
                        }
                        
                    }else{

                        // if(($billData[0]['billType'] === "officeAdjustmentBill") && ($billData[0]['officeAdjustmentBillAmount'] != 0)){
                        $countBills=$this->AllocationByManagerModel->getCount('allocations_officebills',$id);
                        if($countBills>0){
                            $rtData=array
                            (
                                'isAllocated'=>'0'    
                            );
                            $this->AllocationByManagerModel->update('bills',$rtData,$id);
                        }else{
                            $rtData=array
                            (
                                'billType'=>'allocatedbillCurrent','remark'=>'','isAllocated'=>'0'    
                            );
                            $this->AllocationByManagerModel->update('bills',$rtData,$id);
                        }
                        
                    }
                    
                }
            }

            $newCurrentArray=array();
            $currentBills = $this->session->userdata('officeAllocation');
            foreach ($currentBills AS $key => $value) {
                if ($id == $value['id']){
                    unset($currentBills[$key]);
                }
            }

            foreach($currentBills as $newItem){
                $newCurrentArray[]=$newItem;
            }
            $this->session->set_userdata('officeAllocation', $newCurrentArray);
        }
       
    }



    public function deleteBillIdFromTable(){
        $this->load->model('AllocationByManagerModel');
        $id=$this->input->post('rmId');
        $allocationId=$this->input->post('allocationId');

        $checkData=$this->AllocationByManagerModel->checkInfo('allocations_officebills',$id,$allocationId);
        $billData=$this->AllocationByManagerModel->load('bills',$id);
        if(!empty($checkData)){
            $this->AllocationByManagerModel->deleteAllocationsBills('allocations_officebills',$id,$allocationId);
            if($this->db->affected_rows()>0){
                if($billData[0]['netAmount']==$billData[0]['pendingAmt']){
                    $countBills=$this->AllocationByManagerModel->getCount('allocations_officebills',$id);
                    if($countBills>0){
                        $rtData=array
                        (
                            'isAllocated'=>'0'    
                        );
                        $this->AllocationByManagerModel->update('bills',$rtData,$id);
                    }else{
                        $rtData=array
                        (
                            'billType'=>'','isAllocated'=>'0'    
                        );
                        $this->AllocationByManagerModel->update('bills',$rtData,$id);
                    }
                    
                }else{
                    // if(($billData[0]['billType'] === "officeAdjustmentBill") && ($billData[0]['officeAdjustmentBillAmount'] != 0)){
                    $countBills=$this->AllocationByManagerModel->getCount('allocations_officebills',$id);
                    if($countBills>0){
                        $rtData=array
                        (
                            'isAllocated'=>'0'    
                        );
                        $this->AllocationByManagerModel->update('bills',$rtData,$id);
                    }else{
                        $rtData=array
                        (
                            'billType'=>'allocatedbillCurrent','isAllocated'=>'0'    
                        );
                        $this->AllocationByManagerModel->update('bills',$rtData,$id);
                    }
                }
            }
        }

        $newCurrentArray=array();
        $currentBills = $this->session->userdata('officeAllocation');
        foreach ($currentBills AS $key => $value) {
            if ($id == $value['id']){
                unset($currentBills[$key]);
            }
        }

        foreach($currentBills as $newItem){
            $newCurrentArray[]=$newItem;
        }
        $this->session->set_userdata('officeAllocation', $newCurrentArray);
    }

    public function closeCurrentAllocation(){
        $currentBills = $this->session->userdata('officeAllocationInfo');
        $officeAllocationID=$currentBills['officeAllocationID'];

        $data=array(
            "managerStatus"=>1
        );

        $this->OfficeAllocationModel->update('allocations_officeadjustment',$data,$officeAllocationID);
        if($this->db->affected_rows()>0){
            echo "Record saved";
        }else{
            echo "Record not saved";
        }
    }

    public function closeExistCurrentAllocation(){
        $officeAllocationID=$this->input->post('alId');
        $data=array(
            "managerStatus"=>1
        );

        $this->OfficeAllocationModel->update('allocations_officeadjustment',$data,$officeAllocationID);
        if($this->db->affected_rows()>0){
            echo "Record saved";
        }else{
            echo "Record not saved";
        }
    }

    public function closeOwnerCurrentAllocation(){
        $officeAllocationID=$this->input->post('alId');
        $allocationsDetails=$this->OfficeAllocationModel->checkAllocationDetails('allocations_officebills',$officeAllocationID);
        $billId=0;
        $amount=0;

        if(!empty($allocationsDetails)){
            foreach($allocationsDetails as $item){
                $amount=$item['amount'];
                $billId=$item['billId'];

                $bill=$this->OfficeAllocationModel->load('bills',$billId); 
                $isAllocated=$bill[0]['isAllocated'];
                $pendingAmt=$bill[0]['pendingAmt'];
                $netBillAmt=$bill[0]['netAmount'];
                $receivedAmt=$bill[0]['receivedAmt'];
                $officeAdjustment=$bill[0]['officeAdjustmentBillAmount'];

                if($item['transactionType']=='fsr'){
                    $updateRecord=array(
                        'billCurrentStatus'=> 'Office Adjustment',
                        'pendingAmt'=>0,
                        'SRAmt'=>$amount,
                        'isAllocated'=>0
                    );
                    $this->OfficeAllocationModel->update('bills',$updateRecord,$billId);

                    $totalTaxableValue=0;
                    $totalTaxAmount=0;
                    $totalCessAmount=0;
                    $billDetails=$this->OfficeAllocationModel->billsById('billsdetails',$billId);
                    if(!empty($billDetails)){
                        foreach($billDetails as $detail){
                            $id=$detail['id'];
                            $prodCode=$detail['productCode'];
                            $srQty=$detail['qty'];
                            $data['billDetail']=$this->OfficeAllocationModel->load('billsdetails',$id);
                            $eachQtyRate=$data['billDetail'][0]['netAmount']/$data['billDetail'][0]['qty'];

                            //cess amount
                            $cessAmount=$data['billDetail'][0]['cessAmount']/$data['billDetail'][0]['qty'];
                            $cessAmount=round($srQty*$cessAmount,2);

                            //tax amount
                            $taxAmount=$data['billDetail'][0]['taxAmount']/$data['billDetail'][0]['qty'];
                            $taxAmount=round($srQty*$taxAmount,2);

                            //taxable amount
                            $taxableValue=$data['billDetail'][0]['taxableValue']/$data['billDetail'][0]['qty'];
                            $taxableValue=round($srQty*$taxableValue,2);

                            $totalTaxableValue=$totalTaxableValue+$taxableValue;
                            $totalTaxAmount=$totalTaxAmount+$taxAmount;
                            $totalCessAmount=$totalCessAmount+$cessAmount;
                        }
                    }

                    $history=array(
                        'billId'=>$billId,
                        'transactionAmount' =>$amount,
                        'officeAllocationId'=>$officeAllocationID,
                        'transactionStatus' =>'FSR',
                        'transactionMode' =>'dr',
                        'transactionDate'=>date('Y-m-d H:i:sa'),
                        'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                        'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                    );
                    $this->OfficeAllocationModel->insert('bill_transaction_history',$history);
    
                    $billPayment=array(
                        'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                        'billId'=>$billId,
                        'officeAllocationId'=>$officeAllocationID,
                        'date'=>date('Y-m-d H:i:sa'),
                        'paidAmount'=>$amount,
                        'billAmount'=>$netBillAmt,
                        'balanceAmount'=>0,
                        'paymentMode'=>'FSR',
                        'taxableValue'=>$totalTaxableValue,
                        'taxAmount'=>$totalTaxAmount,
                        'cessAmount'=>$totalCessAmount,
                        'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                    );
                    $this->OfficeAllocationModel->insert('billpayments',$billPayment);//insert billpayment dat
                }else if($item['transactionType']=='pending'){
                    $totalPending=($pendingAmt)-$amount;
                    $totalOfficeAdjustment=($officeAdjustment)+$amount;

                    $updateRecord=array(
                       'billCurrentStatus'=> 'Office Adjustment',
                        'isAllocated'=>0
                    );
                    $this->OfficeAllocationModel->update('bills',$updateRecord,$billId);

                    $history=array(
                        'billId'=>$billId,
                        'officeAllocationId'=>$officeAllocationID,
                        'transactionAmount' =>0,
                        'transactionStatus' =>'Office Adjustment',
                        'transactionMode' =>'dr',
                        'transactionDate'=>date('Y-m-d H:i:sa'),
                        'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                        'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                    );
                    $this->OfficeAllocationModel->insert('bill_transaction_history',$history);
                }else if($item['transactionType']=='cleared'){
                    $totalPending=($pendingAmt)-$amount;
                    $totalOfficeAdjustment=($officeAdjustment)+$amount;

                    $updateRecord=array(
                        'billCurrentStatus'=> 'Office Adjustment',
                        'pendingAmt'=>$totalPending,
                        'officeAdjustmentBillAmount'=>$totalOfficeAdjustment,
                        'isAllocated'=>0
                    );
                    $this->OfficeAllocationModel->update('bills',$updateRecord,$billId);

                    $history=array(
                        'billId'=>$billId,
                        'officeAllocationId'=>$officeAllocationID,
                        'transactionAmount' =>$amount,
                        'transactionStatus' =>'Office Adjustment',
                        'transactionMode' =>'dr',
                        'transactionDate'=>date('Y-m-d H:i:sa'),
                        'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                        'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                    );
                    $this->OfficeAllocationModel->insert('bill_transaction_history',$history);
    
                    $billPayment=array(
                        'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                        'billId'=>$billId,
                        'officeAllocationId'=>$officeAllocationID,
                        'date'=>date('Y-m-d H:i:sa'),
                        'paidAmount'=>$amount,
                        'billAmount'=>$netBillAmt,
                        'balanceAmount'=>$totalPending,
                        'paymentMode'=>'Office Adjustment',
                        'ownerApproval'=>1,
                        'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                    );
                    $this->OfficeAllocationModel->insert('billpayments',$billPayment);//insert
                }else if($item['transactionType']=="cash"){
                            
                    $latestPending=$pendingAmt-$amount;
                    $latestReceivedAmt=$receivedAmt+$amount;
                    $updBillData=array(
                        'billCurrentStatus'=> 'Office Adjustment',
                        'isAllocated'=>0,
                        'pendingAmt'=>$latestPending,
                        'receivedAmt'=>$latestReceivedAmt
                    );
                    $this->OfficeAllocationModel->update('bills',$updBillData,$billId);

                    $lastBal=$this->OfficeAllocationModel->lastExpenseValue();//get closing balance
                    $openCloseBal=$lastBal['openCloseBalance'];
                    if($openCloseBal=='' || $openCloseBal==Null){
                        $openCloseBal=0.0;
                    }

                    $totalClosingBalance=$openCloseBal+$amount;//final closing balance

                    $marketCollection=array(
                        'company'=>$bill[0]['compName'],
                        'billId'=>$bill[0]['id'],
                        'date'=>date('Y-m-d H:i:sa'),
                        'employeeId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                        'amount'=>$amount,
                        'nature'=>'Office Collection',
                        'narration'=>'Bill No. '.$bill[0]['billNo'],
                        'inoutStatus'=>'Inflow',
                        'openCloseBalance'=>$totalClosingBalance,
                        'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                    );
                    $this->OfficeAllocationModel->insert('expences',$marketCollection);//insert expenses
                    
                    $history=array(
                        'billId'=>$billId,
                        'officeAllocationId'=>$officeAllocationID,
                        'transactionAmount' =>$amount,
                        'transactionStatus' =>'Cash',
                        'transactionMode' =>'dr',
                        'transactionDate'=>date('Y-m-d H:i:sa'),
                        'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                        'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                    );
                    $this->OfficeAllocationModel->insert('bill_transaction_history',$history);
        
                    $billPayment=array(
                        'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                        'billId'=>$billId,
                        'officeAllocationId'=>$officeAllocationID,
                        'date'=>date('Y-m-d H:i:sa'),
                        'paidAmount'=>$amount,
                        'billAmount'=>$netBillAmt,
                        'balanceAmount'=>$latestPending,
                        'paymentMode'=>'Cash',
                        'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                    );
                    $this->OfficeAllocationModel->insert('billpayments',$billPayment);//insert
                }
            }
        }

        $allocationData=array(
            "ownerStatus"=>1,
            "isAllocationComplete"=>1
        );

        $this->OfficeAllocationModel->update('allocations_officeadjustment',$allocationData,$officeAllocationID);
        if($this->db->affected_rows()>0){
            echo "Record saved";
        }else{
            echo "Record not saved";
        }
    }
    

    public function cancelCurrentFullAllocation(){
        $currentBills = $this->session->userdata('officeAllocationInfo');
        $officeAllocationID=$currentBills['officeAllocationID'];

        $allocations=$this->OfficeAllocationModel->load('allocations_officeadjustment',$officeAllocationID);

        $detailOfAllocation=$this->OfficeAllocationModel->checkAllocationDetails('allocations_officebills',$officeAllocationID);
         $billId=0;
        $allocationId=0;
        if(!empty($detailOfAllocation)){
            foreach($detailOfAllocation as $itm){
                $billId=$itm['billId'];
                $allocationId=$itm['allocationId'];
                
                $billData=$this->OfficeAllocationModel->load('bills',$billId);
                $billStatus=$billData[0]['billHistoryStatus'];	
                $currentBillStatus=$billData[0]['billCurrentStatus'];	

                if($billData[0]['netAmount']==$billData[0]['pendingAmt']){
                    $currentBillStatus="";
                }

                $this->OfficeAllocationModel->deleteAllocationsBills('allocations_officebills',$billId,$allocationId);

                if($billData[0]['netAmount']==$billData[0]['pendingAmt']){
                    $countBills=$this->OfficeAllocationModel->getCount('allocations_officebills',$billId);
                    if($countBills>0){
                        $rtData=array
                        (
                            'billType'=>$billStatus,'remark'=>'','isAllocated'=>'0'    
                        );
                        $this->OfficeAllocationModel->update('bills',$rtData,$billId);

                        $history=array(
							'billId'=>$billId,
							'officeAllocationId'=>$allocationId,
							'transactionStatus' =>'Cancel allocation',
							'transactionDate'=>date('Y-m-d H:i:sa'),
							'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
						);
						$this->OfficeAllocationModel->insert('bill_transaction_history',$history);
                    }else{
                        $rtData=array
                        (
                            'billType'=>$billStatus,'remark'=>'','isAllocated'=>'0'    
                        );
                        $this->OfficeAllocationModel->update('bills',$rtData,$billId);

                        $history=array(
							'billId'=>$billId,
							'officeAllocationId'=>$allocationId,
							'transactionStatus' =>'Cancel allocation',
							'transactionDate'=>date('Y-m-d H:i:sa'),
							'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
						);
						$this->OfficeAllocationModel->insert('bill_transaction_history',$history);
                    }
                   
                }else{
                    // if(($billData[0]['billType'] === "officeAdjustmentBill") && ($billData[0]['officeAdjustmentBillAmount'] != 0)){
                    $countBills=$this->OfficeAllocationModel->getCount('allocations_officebills',$billId);
                    if($countBills>0){
                        $rtData=array
                        (
                            'billType'=>$billStatus,'isAllocated'=>'0'    
                        );
                        $this->OfficeAllocationModel->update('bills',$rtData,$billId);

                        $history=array(
							'billId'=>$billId,
							'officeAllocationId'=>$allocationId,
							'transactionStatus' =>'Cancel allocation',
							'transactionDate'=>date('Y-m-d H:i:sa'),
							'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
						);
						$this->OfficeAllocationModel->insert('bill_transaction_history',$history);
                    }else{
                        $rtData=array
                        (
                            'billType'=>$billStatus,'remark'=>'','isAllocated'=>'0'    
                        );
                        $this->OfficeAllocationModel->update('bills',$rtData,$billId);

                        $history=array(
							'billId'=>$billId,
							'officeAllocationId'=>$allocationId,
							'transactionStatus' =>'Cancel allocation',
							'transactionDate'=>date('Y-m-d H:i:sa'),
							'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
						);
						$this->OfficeAllocationModel->insert('bill_transaction_history',$history);
                    }
                }

              
            }
        }
        

        $this->OfficeAllocationModel->delete('allocations_officeadjustment',$allocationId);
        if($this->db->affected_rows()>0){
            echo 'Allocation Deleted.';
        }else{
            echo 'Unable to delete Allocation.';
        }
    }

    public function cancelExistFullAllocation(){
        $officeAllocationID=$this->input->post('alId');
        $allocations=$this->OfficeAllocationModel->load('allocations_officeadjustment',$officeAllocationID);

        $detailOfAllocation=$this->OfficeAllocationModel->checkAllocationDetails('allocations_officebills',$officeAllocationID);

        $billId=0;
        $allocationId=0;
        if(!empty($detailOfAllocation)){
            foreach($detailOfAllocation as $itm){
                $billId=$itm['billId'];
                $allocationId=$itm['allocationId'];

                $billData=$this->OfficeAllocationModel->load('bills',$billId);
                $billStatus=$billData[0]['billHistoryStatus'];
                $currentBillStatus=$billData[0]['billCurrentStatus'];	

                if($billData[0]['netAmount']==$billData[0]['pendingAmt']){
                    $currentBillStatus="";
                }
	

                $this->OfficeAllocationModel->deleteAllocationsBills('allocations_officebills',$billId,$allocationId);
                
                // $updateBill=array('isAllocated');
                if($billData[0]['netAmount']==$billData[0]['pendingAmt']){
                    $countBills=$this->OfficeAllocationModel->getCount('allocations_officebills',$billId);
                    if($countBills>0){
                        $rtData=array
                        (
                            'billType'=>$billStatus,'remark'=>'','isAllocated'=>'0'    
                        );
                        $this->OfficeAllocationModel->update('bills',$rtData,$billId);

                        $history=array(
							'billId'=>$billId,
							'officeAllocationId'=>$allocationId,
							'transactionStatus' =>'Cancel allocation',
							'transactionDate'=>date('Y-m-d H:i:sa'),
							'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
						);
						$this->OfficeAllocationModel->insert('bill_transaction_history',$history);
                    }else{
                        $rtData=array
                        (
                            'billType'=>$billStatus,'remark'=>'','isAllocated'=>'0'    
                        );
                        $this->OfficeAllocationModel->update('bills',$rtData,$billId);

                        $history=array(
							'billId'=>$billId,
							'officeAllocationId'=>$allocationId,
							'transactionStatus' =>'Cancel allocation',
							'transactionDate'=>date('Y-m-d H:i:sa'),
							'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
						);
						$this->OfficeAllocationModel->insert('bill_transaction_history',$history);
                    }
                    
                }else{
                    // if(($billData[0]['billType'] === "officeAdjustmentBill") && ($billData[0]['officeAdjustmentBillAmount'] != 0)){
                    $countBills=$this->OfficeAllocationModel->getCount('allocations_officebills',$billId);
                    if($countBills>0){
                        $rtData=array
                        (
                            'billType'=>$billStatus,'isAllocated'=>'0'    
                        );
                        $this->OfficeAllocationModel->update('bills',$rtData,$billId);

                        $history=array(
							'billId'=>$billId,
							'officeAllocationId'=>$allocationId,
							'transactionStatus' =>'Cancel allocation',
							'transactionDate'=>date('Y-m-d H:i:sa'),
							'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
						);
						$this->OfficeAllocationModel->insert('bill_transaction_history',$history);
                    }else{
                        $rtData=array
                        (
                            'billType'=>$billStatus,'remark'=>'','isAllocated'=>'0'    
                        );
                        $this->OfficeAllocationModel->update('bills',$rtData,$billId);

                        $history=array(
							'billId'=>$billId,
							'officeAllocationId'=>$allocationId,
							'transactionStatus' =>'Cancel allocation',
							'transactionDate'=>date('Y-m-d H:i:sa'),
							'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
						);
						$this->OfficeAllocationModel->insert('bill_transaction_history',$history);
                    }
                }

                
            }
        }
        

        $this->OfficeAllocationModel->delete('allocations_officeadjustment',$allocationId);
        if($this->db->affected_rows()>0){
            echo 'Allocation Deleted.';
        }else{
            echo 'Unable to delete Allocation.';
        }
        
    }

    public function billsTransactionData(){
        $this->load->view('billsTransactionUploadingView');
    }

    //for  bill transaction details data uploading
    public function billsTransactionDataUploading(){
        $fileName=$_FILES['billFile']['name'];
        $fileType=$_FILES['billFile']['type'];
        $fileTempName=$_FILES['billFile']['tmp_name'];

        $empId=$this->session->userdata[$this->projectSessionName]['id'];

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

            $billNumber="";
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
            $cnt=0;
            for ($row = 2; $row <= $highestRow; ++$row) {
                //A row selected
                $cnt++;
                
                $billNo = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                $paymentMode = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                $paymentAmount = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                $chequeNeftDate = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                $chequeNeftNumber = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                $bankName=trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                $receivedDate=$worksheet->getCellByColumnAndRow(10, $row)->getValue();
               
                if(trim($chequeNeftDate) !=""){
                    $chequeNeftDate =str_replace("/","-",$chequeNeftDate);
                    $chequeNeftDate = ($chequeNeftDate - 25569) * 86400;
                    $chequeNeftDate=date('Y-m-d', $chequeNeftDate);//convert date from excel data
                }
                
                if(trim($receivedDate) !=""){
                    $receivedDate =str_replace("/","-",$receivedDate);
                    $receivedDate = ($receivedDate - 25569) * 86400;
                    $receivedDate=date('Y-m-d', $receivedDate);//convert date from excel data

                }
                
                // echo ' chequeNeftDate is :'.$chequeNeftDate.' ';
                // echo ' Date is :'.$receivedDate.' <br>';
                // exit;
                $checkBill=$this->OfficeAllocationModel->getDataByBillNo('bills',$billNo);
                if(!empty($checkBill)){
                    $billId=$checkBill[0]['id'];

                    if(trim($paymentMode)=="Cash"){
                        $this->cashDataUploading($empId,$billId,$paymentAmount,$receivedDate);
                    }else if(trim($paymentMode)=="Cheque"){
                        $this->chequeDataUploading($empId,$billId,$chequeNeftNumber,$chequeNeftDate,$bankName,$paymentAmount,$receivedDate);
                    }else if(trim($paymentMode)=="NEFT"){
                        $this->neftDataUploading($empId,$billId,$chequeNeftNumber,$chequeNeftDate,$paymentAmount,$receivedDate);
                    }else if(trim($paymentMode)=="SR"){
                        $this->srDataUploading($empId,$billId,$paymentAmount,$receivedDate);
                    }else if(trim($paymentMode)=="FSR"){
                        $this->fsrDataUploading($empId,$billId,$paymentAmount,$receivedDate);
                    }else if(trim($paymentMode)=="CD"){
                        $this->cdDataUploading($empId,$billId,$paymentAmount,$receivedDate);
                    }else if(trim($paymentMode)=="Debit"){
                        $this->debitDataUploading($empId,$billId,$paymentAmount,$receivedDate);
                    }else if(trim($paymentMode)=="Office Adjustment"){
                        $this->officeAdjDataUploading($empId,$billId,$paymentAmount,$receivedDate);
                    }else if(trim($paymentMode)=="Other Adjustment"){
                        $this->otherAdjDataUploading($empId,$billId,$paymentAmount,$receivedDate);
                    }
                }
            }
        }
    }

    public function cashDataUploading($empId,$currentBillId,$collectedAmt,$receivedDate){
        $updatedAt=$receivedDate;
        $recDate=date('Y-m-d H:i:sa');
        $updatedBy=$empId;

        $billInfo=$this->OfficeAllocationModel->load('bills',$currentBillId);//get bill detail
        $netAmount=$billInfo[0]['netAmount'];
        $pendingAmt=$billInfo[0]['pendingAmt'];
        $receivedAmt=$billInfo[0]['receivedAmt'];
        $compName=$billInfo[0]['compName'];
        $billNo=$billInfo[0]['billNo'];
        $bill_no="Bill No. ".$billNo;

        $lastBal=$this->OfficeAllocationModel->lastRecordValue();//get closing balance
        $openCloseBal=$lastBal['openCloseBalance'];
        if($openCloseBal=='' || $openCloseBal==Null){
            $openCloseBal=0.0;
        }

        $totalClosingBalance=$openCloseBal+$collectedAmt;//final closing balance

        $finalPending=$pendingAmt-$collectedAmt;//final pending for current bill
        $finalReceived=$receivedAmt+$collectedAmt;//final received for current bill
        
        $billUpdate=array();
        if($netAmount==$pendingAmt){
            $billUpdate=array(
                'billType'=>'allocatedbillCurrent','deliveryslipOwnerApproval'=>'1','receivedAmt'=>$finalReceived,'pendingAmt'=>$finalPending
            );
        }else{
            $billUpdate=array(
                'receivedAmt'=>$finalReceived,'pendingAmt'=>$finalPending
            );
        }

        $this->OfficeAllocationModel->update('bills',$billUpdate,$currentBillId);//update bill amount
        if($this->db->affected_rows()>0){
            $billPayment=array(
                'empId'=>$empId,'billId'=>$currentBillId,'date'=>$updatedAt,'paidAmount'=>$collectedAmt,'billAmount'=>$netAmount,'balanceAmount'=>$finalPending,'paymentMode'=>'Cash','updatedBy'=>$empId
            );
            $this->OfficeAllocationModel->insert('billpayments',$billPayment);//insert billpayment data

            $notesDetails=array('transactionType'=>"Office Collection",
                'billId'=>$currentBillId,'empId'=>$empId,'coins'=>$collectedAmt,'collectedAmt'=>$collectedAmt,'updatedAt'=>$updatedAt,'updatedBy'=>$updatedBy
            );
            $this->OfficeAllocationModel->insert('notesdetails',$notesDetails);//insert notes details
            if($this->db->affected_rows()>0){
                $notesId=$this->db->insert_id();
                $marketCollection=array(
                    'company'=>$compName,'billId'=>$currentBillId,'date'=>$updatedAt,'employeeId'=>$empId,'notesId'=>$notesId,'amount'=>$collectedAmt,'nature'=>'Office Collection','narration'=>$billNo,'inoutStatus'=>'Inflow','openCloseBalance'=>$totalClosingBalance,'updatedBy'=>$empId
                );

                $this->OfficeAllocationModel->insert('expences',$marketCollection);//insert expenses
                if($this->db->affected_rows()>0){
                    $history=array(
                        'billId'=>$currentBillId,
                        'transactionAmount'=>$collectedAmt,
                        'transactionStatus' =>'Cash',
                        'transactionMode'=>'dr',
                        'transactionDate'=>$updatedAt,
                        'remark'=>'Transaction done by Admin',
                        'empId'=>$empId,
                        'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                    );
                    $this->OfficeAllocationModel->insert('bill_transaction_history',$history);

                    echo "Record updated";
                }
            }
        }
    }

    public function chequeDataUploading($empId,$currentBillId,$chequeNumber,$chequeDate,$chequeBank,$collectedAmt,$receivedDate){
        $updatedAt=$receivedDate;
        $updatedBy=$empId;

        $billInfo=$this->OfficeAllocationModel->load('bills',$currentBillId);//get bill detail
        $newBillNo=$billInfo[0]['billNo'];
        $netAmount=$billInfo[0]['netAmount'];
        $pendingAmt=$billInfo[0]['pendingAmt'];
        $receivedAmt=$billInfo[0]['receivedAmt'];

        $billCompName=$billInfo[0]['compName'];
        $billRetailerName=$billInfo[0]['retailerName'];

        $finalPending=$pendingAmt-$collectedAmt;//final pending for current bill
        $finalReceived=$receivedAmt+$collectedAmt;//final received for current bill

        $clubBillNo="";
        $clubRetailer="";
        $isNeftNoPresent=$this->OfficeAllocationModel->findChequeWithBank('billpayments',$chequeNumber,$chequeDate,$chequeBank);
        if((empty($isNeftNoPresent))){
            echo "";
        }else{
            foreach($isNeftNoPresent as $itm){
                $clubBillNo=$clubBillNo.",".$itm['billNo'];
                $clubRetailer=$clubRetailer.",".$itm['retailerName'];
            }
        }
        $clubBillNo=$clubBillNo.','.$newBillNo;
        $clubRetailer=$clubRetailer.','.$billRetailerName;
        $clubBillNo=trim($clubBillNo,',');
        $clubRetailer=trim($clubRetailer,',');

        $clubBillNo=array_unique(explode(',',$clubBillNo));
        $clubBillNo=implode(',',$clubBillNo);
        $clubBillNo=trim($clubBillNo,',');

        $clubRetailer=array_unique(explode(',',$clubRetailer));
        $clubRetailer=implode(',',$clubRetailer);
        $clubRetailer=trim($clubRetailer,',');

        
        $billUpdate=array();
        if($netAmount==$pendingAmt){
            $billUpdate=array(
                'billType'=>'allocatedbillCurrent','deliveryslipOwnerApproval'=>'1','receivedAmt'=>$finalReceived,'pendingAmt'=>$finalPending
            );
        }else{
            $billUpdate=array(
                'receivedAmt'=>$finalReceived,'pendingAmt'=>$finalPending
            );
        }

        if($empId !=="" && $collectedAmt !=="" && $chequeNumber ==="" && $chequeDate === "" && $chequeBank ===""){
            $this->OfficeAllocationModel->update('bills',$billUpdate,$currentBillId);//update bill amount
            if($this->db->affected_rows()>0){
                $billPayment=array(
                    'empId'=>$empId,
                    'billId'=>$currentBillId,
                    'chequeNo'=>$chequeNumber,
                    'chequeReceivedDate'=>$updatedAt,
                    'date'=>$updatedAt,
                    'paidAmount'=>$collectedAmt,
                    'billAmount'=>$netAmount,
                    'balanceAmount'=>$finalPending,
                    'paymentMode'=>'Cheque',
                    'isLostStatus'=>2,
                    'compName'=>$billCompName,
                    'billNo'=>$clubBillNo,
                    'retailerName'=>$clubRetailer,
                    'updatedBy'=>$updatedBy
                );
                $this->OfficeAllocationModel->insert('billpayments',$billPayment);//insert billpayment data

                $updChequeDetail=array(
                    'billNo'=>$clubBillNo,
                    'retailerName'=>$clubRetailer
                );
                $this->OfficeAllocationModel->updateChequeData('billpayments',$updChequeDetail,$chequeNumber,$chequeDate,$chequeBank);//update billpayment data

                $history=array(
                    'billId'=>$currentBillId,
                    'transactionAmount'=>$collectedAmt,
                    'transactionStatus' =>'Cheque',
                    'transactionMode'=>'dr',
                    'transactionDate'=>$updatedAt,
                    'remark'=>'Transaction done by Admin',
                    'empId'=>$empId,
                    'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                );
                $this->OfficeAllocationModel->insert('bill_transaction_history',$history);
            }
        }else if($empId !=="" && $collectedAmt !=="" && $chequeNumber !=="" && $chequeDate !== "" && $chequeBank !==""){
            $this->OfficeAllocationModel->update('bills',$billUpdate,$currentBillId);//update bill amount
            if($this->db->affected_rows()>0){
                $billPayment=array(
                    'empId'=>$empId,
                    'billId'=>$currentBillId,
                    'chequeNo'=>$chequeNumber,
                    'chequeDate'=>$chequeDate,
                    'chequeReceivedDate'=>$updatedAt,
                    'chequeStatus'=>'New',
                    'chequeStatusDate'=>$updatedAt,
                    'date'=>$updatedAt,
                    'paidAmount'=>$collectedAmt,
                    'billAmount'=>$netAmount,
                    'balanceAmount'=>$finalPending,
                    'paymentMode'=>'Cheque',
                    'isLostStatus'=>2,
                    'compName'=>$billCompName,
                    'billNo'=>$clubBillNo,
                    'retailerName'=>$clubRetailer,
                    'chequeBank'=>$chequeBank,
                    'updatedBy'=>$updatedBy
                );
                $this->OfficeAllocationModel->insert('billpayments',$billPayment);//insert billpayment data

                $updChequeDetail=array(
                    'billNo'=>$clubBillNo,
                    'retailerName'=>$clubRetailer
                );
                $this->OfficeAllocationModel->updateChequeData('billpayments',$updChequeDetail,$chequeNumber,$chequeDate,$chequeBank);//update billpayment data

                $history=array(
                    'billId'=>$currentBillId,
                    'transactionAmount'=>$collectedAmt,
                    'transactionStatus' =>'Cheque',
                    'transactionMode'=>'dr',
                    'transactionDate'=>$updatedAt,
                    'remark'=>'Transaction done by Admin',
                    'empId'=>$empId,
                    'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                );
                $this->OfficeAllocationModel->insert('bill_transaction_history',$history);
            }
        }
    }


    public function neftDataUploading($empId,$currentBillId,$neftNumber,$neftDate,$collectedAmt,$receivedDate){
        $updatedAt=$receivedDate;
        $updatedBy=$empId;

        $billInfo=$this->OfficeAllocationModel->load('bills',$currentBillId);//get bill detail
        $newBillNo=$billInfo[0]['billNo'];
        $netAmount=$billInfo[0]['netAmount'];
        $pendingAmt=$billInfo[0]['pendingAmt'];
        $receivedAmt=$billInfo[0]['receivedAmt'];
        $billCompName=$billInfo[0]['compName'];
        $billRetailerName=$billInfo[0]['retailerName'];

        $finalPending=$pendingAmt-$collectedAmt;//final pending for current bill
        $finalReceived=$receivedAmt+$collectedAmt;//final received for current bill

        $clubBillNo="";
        $clubRetailer="";
        $isNeftNoPresent=$this->OfficeAllocationModel->findNeft('billpayments',$neftNumber,$neftDate);
        if((empty($isNeftNoPresent))){
            echo "";
        }else{
            foreach($isNeftNoPresent as $itm){
                $clubBillNo=$clubBillNo.",".$itm['billNo'];
                $clubRetailer=$clubRetailer.",".$itm['retailerName'];
            }
        }
        $clubBillNo=$clubBillNo.','.$newBillNo;
        $clubRetailer=$clubRetailer.','.$billRetailerName;
        $clubBillNo=trim($clubBillNo,',');
        $clubRetailer=trim($clubRetailer,',');
        
        if($empId !=="" && $collectedAmt !=="" && $neftNumber ==="" && $neftDate ===""){
            $billUpdate=array();
            if($netAmount==$pendingAmt){
                $billUpdate=array(
                    'billType'=>'allocatedbillCurrent','deliveryslipOwnerApproval'=>'1','receivedAmt'=>$finalReceived,'pendingAmt'=>$finalPending
                );
            }else{
                $billUpdate=array(
                    'receivedAmt'=>$finalReceived,'pendingAmt'=>$finalPending
                );
            }

            $this->OfficeAllocationModel->update('bills',$billUpdate,$currentBillId);//update bill amount
            if($this->db->affected_rows()>0){
                $billPayment=array(
                    'empId'=>$empId,'billNo'=>$clubBillNo,'compName'=>$billCompName,'retailerName'=>$clubRetailer,'billId'=>$currentBillId,'date'=>$updatedAt,'paidAmount'=>$collectedAmt,'billAmount'=>$netAmount,'balanceAmount'=>$finalPending,'paymentMode'=>'NEFT','isLostStatus'=>2,'updatedBy'=>$updatedBy
                );
                $this->OfficeAllocationModel->insert('billpayments',$billPayment);//insert billpayment data

                $updChequeDetail=array(
                    'billNo'=>$clubBillNo,
                    'retailerName'=>$clubRetailer
                );
                $this->OfficeAllocationModel->updateNeftData('billpayments',$updChequeDetail,$neftNumber,$neftDate);//update billpayment data

                $history=array(
                    'billId'=>$currentBillId,
                    'transactionAmount'=>$collectedAmt,
                    'transactionStatus' =>'NEFT',
                    'transactionMode'=>'dr',
                    'transactionDate'=>$updatedAt,
                    'remark'=>'Transaction done by Admin',
                    'empId'=>$empId,
                    'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                );
                $this->OfficeAllocationModel->insert('bill_transaction_history',$history);
            }
        }else if($empId !=="" && $collectedAmt !=="" && $neftNumber !=="" && $neftDate !==""){
            $billUpdate=array();
            if($netAmount==$pendingAmt){
                $billUpdate=array(
                    'billType'=>'allocatedbillCurrent','deliveryslipOwnerApproval'=>'1','receivedAmt'=>$finalReceived,'pendingAmt'=>$finalPending
                );
            }else{
                $billUpdate=array(
                    'receivedAmt'=>$finalReceived,'pendingAmt'=>$finalPending
                );
            }

            $this->OfficeAllocationModel->update('bills',$billUpdate,$currentBillId);//update bill amount
            if($this->db->affected_rows()>0){
                $billPayment=array(
                    'empId'=>$empId,'billNo'=>$clubBillNo,'compName'=>$billCompName,'retailerName'=>$clubRetailer,'billId'=>$currentBillId,'chequeStatus'=>'Received','chequeReceivedDate'=>$updatedAt,'date'=>$updatedAt,'paidAmount'=>$collectedAmt,'billAmount'=>$netAmount,'neftNo'=>$neftNumber,'neftDate'=>$neftDate,'balanceAmount'=>$finalPending,'paymentMode'=>'NEFT','isLostStatus'=>2,'updatedBy'=>$updatedBy
                );
                $this->OfficeAllocationModel->insert('billpayments',$billPayment);//insert billpayment data

                $updChequeDetail=array(
                    'billNo'=>$clubBillNo,
                    'retailerName'=>$clubRetailer
                );
                $this->OfficeAllocationModel->updateNeftData('billpayments',$updChequeDetail,$neftNumber,$neftDate);//update billpayment data

                $history=array(
                    'billId'=>$currentBillId,
                    'transactionAmount'=>$collectedAmt,
                    'transactionStatus' =>'NEFT',
                    'transactionMode'=>'dr',
                    'transactionDate'=>$updatedAt,
                    'remark'=>'Transaction done by Admin',
                    'empId'=>$empId,
                    'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                );
                $this->OfficeAllocationModel->insert('bill_transaction_history',$history);
            }
        }
    }

    public function cdDataUploading($empId,$currentBillId,$collectedAmt,$receivedDate){
        $remark="";
        $updatedAt=$receivedDate;
        $updatedBy=$empId;
        $status=1;
        
        $billInfo=$this->OfficeAllocationModel->load('bills',$currentBillId);//get bill detail
        $netAmount=$billInfo[0]['netAmount'];
        $pendingAmt=$billInfo[0]['pendingAmt'];
        $cd=$billInfo[0]['cd'];
        $billNo=$billInfo[0]['billNo'];

        $finalPending=$pendingAmt-$collectedAmt;//final pending for current bill
        $finalCd=$cd+$collectedAmt;//final received for current bill
        
        $remark="Transaction uploaded by excel for : ".$billNo;


        $billUpdate=array();
        if($netAmount==$pendingAmt){
            $billUpdate=array(
                'billType'=>'allocatedbillCurrent','deliveryslipOwnerApproval'=>'1','cd'=>$finalCd,'pendingAmt'=>$finalPending
            );
        }else{
            $billUpdate=array(
                'cd'=>$finalCd,'pendingAmt'=>$finalPending
            );
        }

        $this->OfficeAllocationModel->update('bills',$billUpdate,$currentBillId);//update bill amount
        if($this->db->affected_rows()>0){
            $billRemark=array(
                'billId'=>$currentBillId,'empId'=>$empId,'remark'=>$remark,'amount'=>$collectedAmt,'updatedAt'=>$updatedAt,'updatedBy'=>$updatedBy
            );
            $this->OfficeAllocationModel->insert('bill_remark_history',$billRemark);//insert remark data

            $billPayment=array(
                'empId'=>$empId,'billId'=>$currentBillId,'date'=>$updatedAt,'paidAmount'=>$collectedAmt,'billAmount'=>$netAmount,'balanceAmount'=>$finalPending,'paymentMode'=>'CD','isLostStatus'=>2,'updatedBy'=>$updatedBy,'ownerApproval'=>$status
            );
            $this->OfficeAllocationModel->insert('billpayments',$billPayment);

            $history=array(
                'billId'=>$currentBillId,
                'transactionAmount'=>$collectedAmt,
                'transactionStatus' =>'CD',
                'transactionMode'=>'dr',
                'transactionDate'=>$updatedAt,
                'remark'=>'Transaction done by Admin',
                'empId'=>$empId,
                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
            );
            $this->OfficeAllocationModel->insert('bill_transaction_history',$history);
        }
    }

    public function debitDataUploading($empId,$currentBillId,$collectedAmt,$receivedDate){
        
        $updatedAt=$receivedDate;
        $updatedBy=$empId;
        $status=1;

        $billInfo=$this->OfficeAllocationModel->load('bills',$currentBillId);//get bill detail
        $netAmount=$billInfo[0]['netAmount'];
        $pendingAmt=$billInfo[0]['pendingAmt'];
        $debit=$billInfo[0]['debit'];
        $billCompName=$billInfo[0]['compName'];
        $billNo=$billInfo[0]['billNo'];

        $remark="Transaction uploaded by excel for : ".$billNo;

        $finalPending=$pendingAmt-$collectedAmt;//final pending for current bill
        $finalDebit=$debit+$collectedAmt;//final received for current bill
        
        $billUpdate=array();
        if($netAmount==$pendingAmt){
            $billUpdate=array(
                'billType'=>'allocatedbillCurrent','deliveryslipOwnerApproval'=>'1','debit'=>$finalDebit,'pendingAmt'=>$finalPending
            );
        }else{
            $billUpdate=array(
                'debit'=>$finalDebit,'pendingAmt'=>$finalPending
            );
        }

        $this->OfficeAllocationModel->update('bills',$billUpdate,$currentBillId);//update bill amount
        if($this->db->affected_rows()>0){
            $billRemark=array(
                'billId'=>$currentBillId,'empId'=>$empId,'remark'=>$remark,'amount'=>$collectedAmt,'updatedAt'=>$updatedAt,'updatedBy'=>$updatedBy
            );
            $this->OfficeAllocationModel->insert('bill_remark_history',$billRemark);//insert remark data

            $empDebit=array(
                'billId'=>$currentBillId,'empId'=>$empId,'transactionType'=>'dr','description'=>$remark,'amount'=>$collectedAmt,'createdAt'=>$updatedAt,'createdBy'=>$updatedBy
            );
             $this->OfficeAllocationModel->insert('emptransactions',$empDebit);//insert remark data

            $billPayment=array(
                'empId'=>$empId,'billId'=>$currentBillId,'date'=>$updatedAt,'paidAmount'=>$collectedAmt,'billAmount'=>$netAmount,'balanceAmount'=>$finalPending,'paymentMode'=>'Debit To Employee','isLostStatus'=>2,'updatedBy'=>$updatedBy,'ownerApproval'=>$status
            );
            $this->OfficeAllocationModel->insert('billpayments',$billPayment);

            $history=array(
                'billId'=>$currentBillId,
                'transactionAmount'=>$collectedAmt,
                'transactionStatus' =>'Debit to Employee',
                'transactionMode'=>'dr',
                'transactionDate'=>$updatedAt,
                'remark'=>'Transaction done by Admin',
                'empId'=>$empId,
                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
            );
            $this->OfficeAllocationModel->insert('bill_transaction_history',$history);
        }
    }

    public function officeAdjDataUploading($empId,$currentBillId,$collectedAmt,$receivedDate){
        $updatedAt=$receivedDate;
        $updatedBy=$empId;
        $status=1;

        $billInfo=$this->OfficeAllocationModel->load('bills',$currentBillId);//get bill detail
        $netAmount=$billInfo[0]['netAmount'];
        $pendingAmt=$billInfo[0]['pendingAmt'];
        $officeAdjustment=$billInfo[0]['officeAdjustmentBillAmount'];
        $billNo=$billInfo[0]['billNo'];

        $finalPending=$pendingAmt-$collectedAmt;//final pending for current bill
        $finalOA=$officeAdjustment+$collectedAmt;//final received for current bill
        
        $remark="Transaction uploaded by excel for : ".$billNo;

        $billUpdate=array();
        if($netAmount==$pendingAmt){
            $billUpdate=array(
                'billType'=>'allocatedbillCurrent','deliveryslipOwnerApproval'=>'1','isOfficeAdjustmentBill'=>1,'officeAdjustmentBillAmount'=>$finalOA,'pendingAmt'=>$finalPending
            );
        }else{
            $billUpdate=array(
                'officeAdjustmentBillAmount'=>$finalOA,'isOfficeAdjustmentBill'=>1,'pendingAmt'=>$finalPending
            );
        }

        $this->OfficeAllocationModel->update('bills',$billUpdate,$currentBillId);//update bill amount
        if($this->db->affected_rows()>0){
            $billRemark=array(
                'billId'=>$currentBillId,'empId'=>$empId,'remark'=>$remark,'amount'=>$collectedAmt,'updatedAt'=>$updatedAt,'updatedBy'=>$updatedBy
            );
            $this->OfficeAllocationModel->insert('bill_remark_history',$billRemark);//insert remark data

            $billPayment=array(
                'empId'=>$empId,'billId'=>$currentBillId,'date'=>$updatedAt,'paidAmount'=>$collectedAmt,'billAmount'=>$netAmount,'balanceAmount'=>$finalPending,'paymentMode'=>'Office Adjustment','isLostStatus'=>2,'updatedBy'=>$updatedBy,'ownerApproval'=>$status
            );
            $this->OfficeAllocationModel->insert('billpayments',$billPayment);

            $history=array(
                'billId'=>$currentBillId,
                'transactionAmount'=>$collectedAmt,
                'transactionStatus' =>'Office Adjustment',
                'transactionMode'=>'dr',
                'transactionDate'=>$updatedAt,
                'remark'=>'Transaction done by Admin',
                'empId'=>$empId,
                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
            );
            $this->OfficeAllocationModel->insert('bill_transaction_history',$history);
        }
    }

    public function otherAdjDataUploading($empId,$currentBillId,$collectedAmt,$receivedDate){
        $status=1;
        $updatedAt=$receivedDate;
        $updatedBy=$empId;

        $billInfo=$this->OfficeAllocationModel->load('bills',$currentBillId);//get bill detail
        $netAmount=$billInfo[0]['netAmount'];
        $pendingAmt=$billInfo[0]['pendingAmt'];
        $otherAdjustment=$billInfo[0]['otherAdjustment'];
        $billNo=$billInfo[0]['billNo'];

        $finalPending=$pendingAmt-$collectedAmt;//final pending for current bill
        $finalOA=$otherAdjustment+$collectedAmt;//final received for current bill
        
        $remark="Transaction uploaded by excel for : ".$billNo;

        $billUpdate=array();
        if($netAmount==$pendingAmt){
            $billUpdate=array(
                'billType'=>'allocatedbillCurrent','deliveryslipOwnerApproval'=>'1','isOtherAdjustmentBill'=>1,'otherAdjustment'=>$finalOA,'pendingAmt'=>$finalPending
            );
        }else{
            $billUpdate=array(
                'otherAdjustment'=>$finalOA,'isOtherAdjustmentBill'=>1,'pendingAmt'=>$finalPending
            );
        }

        $this->OfficeAllocationModel->update('bills',$billUpdate,$currentBillId);//update bill amount
        if($this->db->affected_rows()>0){
            $billRemark=array(
                'billId'=>$currentBillId,'empId'=>$empId,'remark'=>$remark,'amount'=>$collectedAmt,'updatedAt'=>$updatedAt,'updatedBy'=>$updatedBy
            );
            $this->OfficeAllocationModel->insert('bill_remark_history',$billRemark);//insert remark data

            $billPayment=array(
                'empId'=>$empId,'billId'=>$currentBillId,'date'=>$updatedAt,'paidAmount'=>$collectedAmt,'billAmount'=>$netAmount,'balanceAmount'=>$finalPending,'paymentMode'=>'Other Adjustment','isLostStatus'=>2,'updatedBy'=>$updatedBy,'ownerApproval'=>$status
            );
            $this->OfficeAllocationModel->insert('billpayments',$billPayment);

            $history=array(
                'billId'=>$currentBillId,
                'transactionAmount'=>$collectedAmt,
                'transactionStatus' =>'Other Adjustment',
                'transactionMode'=>'dr',
                'transactionDate'=>$updatedAt,
                'remark'=>'Transaction done by Admin',
                'empId'=>$empId,
                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
            );
            $this->OfficeAllocationModel->insert('bill_transaction_history',$history);
        }
    }

    public function srDataUploading($empId,$currentBillId,$collectedAmt,$receivedDate){
        $updatedAt=$receivedDate;
        $updatedBy=$empId;

        $billInfo=$this->OfficeAllocationModel->load('bills',$currentBillId);//get bill detail
        $netAmount=$billInfo[0]['netAmount'];
        $srAmt=$billInfo[0]['SRAmt'];
        $pendingAmt=$billInfo[0]['pendingAmt'];
        $billNo=$billInfo[0]['billNo'];
        
        $finalPending=$pendingAmt-$collectedAmt;//final pending for current bill
        $finalSR=$srAmt+$collectedAmt;//final received for current bill
        
        $billUpdate=array();
        if($netAmount==$pendingAmt){
            $billUpdate=array(
                'billType'=>'allocatedbillCurrent','deliveryslipOwnerApproval'=>'1','SRAmt'=>$finalSR,'pendingAmt'=>$finalPending
            );
        }else{
            $billUpdate=array(
                'SRAmt'=>$finalSR,'pendingAmt'=>$finalPending
            );
        }

        $this->OfficeAllocationModel->update('bills',$billUpdate,$currentBillId);//update bill amount
        if($this->db->affected_rows()>0){
            $billPayment=array(
                'empId'=>$empId,'billId'=>$currentBillId,'date'=>$updatedAt,'paidAmount'=>$collectedAmt,'billAmount'=>$netAmount,'balanceAmount'=>$finalPending,'paymentMode'=>'SR','updatedBy'=>$empId
            );
            $this->OfficeAllocationModel->insert('billpayments',$billPayment);//insert billpayment data

            $history=array(
                'billId'=>$currentBillId,
                'transactionAmount'=>$collectedAmt,
                'transactionStatus' =>'SR',
                'transactionMode'=>'dr',
                'transactionDate'=>$updatedAt,
                'remark'=>'Transaction done by Admin',
                'empId'=>$empId,
                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
            );
            $this->OfficeAllocationModel->insert('bill_transaction_history',$history);

        }
    }

    public function fsrDataUploading($empId,$currentBillId,$collectedAmt,$receivedDate){
        $updatedAt=$receivedDate;
        $updatedBy=$empId;

        $billInfo=$this->OfficeAllocationModel->load('bills',$currentBillId);//get bill detail
        $netAmount=$billInfo[0]['netAmount'];
        $srAmt=$billInfo[0]['SRAmt'];
        $pendingAmt=$billInfo[0]['pendingAmt'];
        $billNo=$billInfo[0]['billNo'];
        
        $finalPending=$pendingAmt-$collectedAmt;//final pending for current bill
        $finalSR=$srAmt+$collectedAmt;//final received for current bill
        
        $billUpdate=array();
        if($netAmount==$pendingAmt){
            $billUpdate=array(
                'billType'=>'allocatedbillCurrent','isFsrBill'=>1,'deliveryslipOwnerApproval'=>'1','SRAmt'=>$finalSR,'pendingAmt'=>$finalPending
            );
        }else{
            $billUpdate=array(
                'SRAmt'=>$finalSR,'isFsrBill'=>1,'pendingAmt'=>$finalPending
            );
        }

        $this->OfficeAllocationModel->update('bills',$billUpdate,$currentBillId);//update bill amount
        if($this->db->affected_rows()>0){
            $billPayment=array(
                'empId'=>$empId,'billId'=>$currentBillId,'date'=>$updatedAt,'paidAmount'=>$collectedAmt,'billAmount'=>$netAmount,'balanceAmount'=>$finalPending,'paymentMode'=>'FSR','updatedBy'=>$empId
            );
            $this->OfficeAllocationModel->insert('billpayments',$billPayment);//insert billpayment data

            $history=array(
                'billId'=>$currentBillId,
                'transactionAmount'=>$collectedAmt,
                'transactionStatus' =>'FSR',
                'transactionMode'=>'dr',
                'transactionDate'=>$updatedAt,
                'remark'=>'Transaction done by Admin',
                'empId'=>$empId,
                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
            );
            $this->OfficeAllocationModel->insert('bill_transaction_history',$history);
        }
    }

    public function cashExistAllBills(){
        $billIds=$this->input->post('billArray');
        $rowId=$this->input->post('rowArray');
        $allocationId=$this->input->post('alId');

        for($i=0;$i<count($billIds);$i++){
            $billId=$billIds[$i];
            $rowNo=$rowId[$i];

            $bill=$this->OfficeAllocationModel->load('bills',$billId); 

            // if($bill[0]['netAmount']==$bill[0]['pendingAmt']){
                $allocation_data=array(
                    'amount'=>$bill[0]['pendingAmt'],
                    'transactionType'=>'cash',
                    'updatedAt'=>date('Y-m-d H:i:sa')
                );

                $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$allocationId);
            // }

            $newCurrentBills=$this->OfficeAllocationModel->loadCurrentAllocatedBills('bills',$billId,$allocationId);
            
            $no=0;
            foreach($newCurrentBills as $items){
                $id=$items['id'];
                $pastBillIDs[$no]=$id;
                $no++;

        ?>  
                <tr id="status-id<?php echo $rowNo; ?>">
                    <td><?php echo $rowNo; ?></td>
                    <td style="display: none"><?php echo $items['id']; ?></td>
                    <td><?php echo $items['billNo']; ?></td>
                    <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                    <td><?php echo $items['retailerName']; ?></td>
                    <td><?php echo $items['netAmount']; ?></td>
                    <td><?php echo $items['receivedAmt']; ?></td>
                    <td><?php echo $items['SRAmt']; ?></td>
                    <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                    <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                    <td><?php echo $items['a_amount']; ?></td>
                    <td><?php if($items['a_type']=='cash'){ echo 'Cash'; } ?></td>
                    <td>
                        <button id="cashMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cash")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn-success waves-effect" data-type="basic"><span>Cash</span></button>
                        <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn-success waves-effect" data-type="basic"><span>Cleared</span></button>
                    
                    <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn-success waves-effect" data-type="basic"><span>Pending</span></button>

                    <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                        <button  id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php }else{ ?>
                        <button disabled id="fsrMupdate" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php } ?>
                     
                <?php if($items['a_type']==""){ ?>
                    <a>
                        <button onclick="deleteFromTable(this,'<?php echo $items['id'];?>');" class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                    </a>
                <?php }else{ ?>
                    <a>
                        <button disabled class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                    </a>
                <?php } ?>
                    </td>
                </tr>
        <?php
            }
        }

    }

    public function cancelCashExistAllBills(){
        $billIds=$this->input->post('billArray');
        $rowId=$this->input->post('rowArray');
        $allocationId=$this->input->post('alId');

        for($i=0;$i<count($billIds);$i++){
            $billId=$billIds[$i];
            $rowNo=$rowId[$i];

            $bill=$this->OfficeAllocationModel->load('bills',$billId); 

            // if($bill[0]['netAmount']==$bill[0]['pendingAmt']){
            $allocation_data=array(
                'amount'=>0,
                'transactionType'=>'',
                'updatedAt'=>date('Y-m-d H:i:sa')
            );

            $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$allocationId);
            // }

            $newCurrentBills=$this->OfficeAllocationModel->loadCurrentAllocatedBills('bills',$billId,$allocationId);
            
            $no=0;
            foreach($newCurrentBills as $items){
                $id=$items['id'];
                $pastBillIDs[$no]=$id;
                $no++;

        ?>  
                <tr id="status-id<?php echo $rowNo; ?>">
                    <td><?php echo $rowNo; ?></td>
                    <td style="display: none"><?php echo $items['id']; ?></td>
                    <td><?php echo $items['billNo']; ?></td>
                    <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                    <td><?php echo $items['retailerName']; ?></td>
                    <td><?php echo $items['netAmount']; ?></td>
                    <td><?php echo $items['receivedAmt']; ?></td>
                    <td><?php echo $items['SRAmt']; ?></td>
                    <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                    <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                    <td></td>
                    <td></td>
                    <td>
                        <button id="cashMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cash")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn-success waves-effect" data-type="basic"><span>Cash</span></button>
                        <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn-success waves-effect" data-type="basic"><span>Cleared</span></button>
                    
                    <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn-success waves-effect" data-type="basic"><span>Pending</span></button>

                    <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                        <button  id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php }else{ ?>
                        <button disabled id="fsrMupdate" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php } ?>
                     
                <?php if($items['a_type']==""){ ?>
                    <a>
                        <button onclick="deleteFromTable(this,'<?php echo $items['id'];?>');" class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                    </a>
                <?php }else{ ?>
                    <a>
                        <button disabled class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                    </a>
                <?php } ?>
                    </td>
                </tr>
        <?php
            }
        }

    }

    public function cancelClearExistAllBills(){
        $billIds=$this->input->post('billArray');
        $rowId=$this->input->post('rowArray');
        $allocationId=$this->input->post('alId');

        for($i=0;$i<count($billIds);$i++){
            $billId=$billIds[$i];
            // $amount=$this->input->post('amount');
            $rowNo=$rowId[$i];

            $bill=$this->OfficeAllocationModel->load('bills',$billId); 
            $amount=$bill[0]['pendingAmt'];
            $pendingAmt=$bill[0]['pendingAmt'];
            $officeAdjustment=$bill[0]['officeAdjustmentBillAmount'];

            $totalPending=$pendingAmt-$amount;
            $totalOfficeAdjustment=$officeAdjustment+$amount;
            // $data=array(
            //     'pendingAmt'=>$totalPending,
            //     'officeAdjustmentBillAmount'=>$totalOfficeAdjustment
            // );
            // $this->OfficeAllocationModel->update('bills',$data,$billId);
            // if($this->db->affected_rows()>0){
                $allocation_data=array(
                    'amount'=>0,
                    'transactionType'=>'',
                    'updatedAt'=>date('Y-m-d H:i:sa')
                );
                

                $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$allocationId);   

                $newCurrentBills=$this->OfficeAllocationModel->loadCurrentAllocatedBills('bills',$billId,$allocationId);
                
                $no=0;
                foreach($newCurrentBills as $items){
                    $id=$items['id'];
                    $pastBillIDs[$no]=$id;
                    $no++;

            ?>  
                    <tr id="status-id<?php echo $rowNo; ?>">
                        <td><?php echo $rowNo; ?></td>
                        <td style="display: none"><?php echo $items['id']; ?></td>
                        <td><?php echo $items['billNo']; ?></td>
                        <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                        <td><?php echo $items['retailerName']; ?></td>
                        <td><?php echo $items['netAmount']; ?></td>
                        <td><?php echo $items['receivedAmt']; ?></td>
                        <td><?php echo $items['SRAmt']; ?></td>
                        <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                        <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                        <td></td>
                        <td></td>
                        <td>
                            <button id="cashMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cash")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn-success waves-effect" data-type="basic"><span>Cash</span></button>
                            <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn-success waves-effect" data-type="basic"><span>Cleared</span></button>
                        
                        <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn-success waves-effect" data-type="basic"><span>Pending</span></button>

                        <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                            <button  id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php }else{ ?>
                            <button disabled data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php } ?>
                         
                    <?php if($items['a_type']==""){ ?>
                        <a>
                            <button onclick="deleteFromTable(this,'<?php echo $items['id'];?>');" class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php }else{ ?>
                        <a>
                            <button disabled class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php } ?>
                        </td>
                    </tr>
            <?php
                }
            // }
        }

    }

    public function cancelPendingExistingAllBills(){
        $billArray=$this->input->post('billArray');
        $rowArray=$this->input->post('rowArray');
        $allocationId=$this->input->post('alId');
        // echo $billId.' '.$rowNo;exit;
        $billId="";
        $rowNo="";
        for($i=0;$i<count($billArray);$i++){
            $billId=$billArray[$i];
            $rowNo=$rowArray[$i];

            $existData=$this->OfficeAllocationModel->checkInfo('allocations_officebills',$billId,$allocationId); 

            
            $allocation_data=array(
                'amount'=>0,
                'transactionType'=>'',
                'updatedAt'=>date('Y-m-d H:i:sa')
            );
            
            $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$allocationId);   

            $newCurrentBills=$this->OfficeAllocationModel->loadCurrentAllocatedBills('bills',$billId,$allocationId);

            $no=0;
            foreach($newCurrentBills as $items){
                $id=$items['id'];
                $pastBillIDs[$no]=$id;
                $no++;

            ?>  
                    <tr id="status-id<?php echo $rowNo; ?>">
                        <td><?php echo $rowNo; ?></td>
                        <td style="display: none"><?php echo $items['id']; ?></td>
                        <td><?php echo $items['billNo']; ?></td>
                        <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                        <td><?php echo $items['retailerName']; ?></td>
                        <td><?php echo $items['netAmount']; ?></td>
                        <td><?php echo $items['receivedAmt']; ?></td>
                        <td><?php echo $items['SRAmt']; ?></td>
                        <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                        <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                        <td></td>
                        <td></td>
                        <td>
                            <button id="cashMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cash")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn-success waves-effect" data-type="basic"><span>Cash</span></button>
                            <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn-success waves-effect" data-type="basic"><span>Cleared</span></button>
                        
                        <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn-success waves-effect" data-type="basic"><span>Pending</span></button>

                        <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                            <button  id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php }else{ ?>
                            <button id="fsrM" disabled data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php } ?>
                         
                    <?php if($items['a_type']==""){ ?>
                        <a>
                            <button onclick="deleteFromTable(this,'<?php echo $items['id'];?>');" class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php }else{ ?>
                        <a>
                            <button disabled class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                        </a>
                    <?php } ?>
                        </td>
                    </tr>
            <?php
                }
            
        }
    }

    public function cancelFsrExistAllBills(){
        $billIds=$this->input->post('billArray');
        $rowId=$this->input->post('rowArray');
        $allocationId=$this->input->post('alId');

        for($i=0;$i<count($billIds);$i++){
            $billId=$billIds[$i];
            $rowNo=$rowId[$i];

            $bill=$this->OfficeAllocationModel->load('bills',$billId); 


            if($bill[0]['netAmount']==$bill[0]['pendingAmt']){
                $allocation_data=array(
                    'amount'=>0,
                    'transactionType'=>'',
                    'updatedAt'=>date('Y-m-d H:i:sa')
                );

                $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$allocationId);
            }

            $newCurrentBills=$this->OfficeAllocationModel->loadCurrentAllocatedBills('bills',$billId,$allocationId);
            
            $no=0;
            foreach($newCurrentBills as $items){
                $id=$items['id'];
                $pastBillIDs[$no]=$id;
                $no++;

        ?>  
                <tr id="status-id<?php echo $rowNo; ?>">
                    <td><?php echo $rowNo; ?></td>
                    <td style="display: none"><?php echo $items['id']; ?></td>
                    <td><?php echo $items['billNo']; ?></td>
                    <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                    <td><?php echo $items['retailerName']; ?></td>
                    <td><?php echo $items['netAmount']; ?></td>
                    <td><?php echo $items['receivedAmt']; ?></td>
                    <td><?php echo $items['SRAmt']; ?></td>
                    <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                    <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                    <td></td>
                    <td></td>
                    <td>
                        <button id="cashMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cash")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn-success waves-effect" data-type="basic"><span>Cash</span></button>
                        <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn-success waves-effect" data-type="basic"><span>Cleared</span></button>
                    
                    <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn-success waves-effect" data-type="basic"><span>Pending</span></button>

                    <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                        <button  id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php }else{ ?>
                        <button disabled id="fsrMupdate" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn-success waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php } ?>
                     
                <?php if($items['a_type']==""){ ?>
                    <a>
                        <button onclick="deleteFromTable(this,'<?php echo $items['id'];?>');" class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                    </a>
                <?php }else{ ?>
                    <a>
                        <button disabled class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                    </a>
                <?php } ?>
                    </td>
                </tr>
        <?php
            }
        }

    }
    


    
}
