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
		}else{
			$this->load->view('LoginView');
		}
    }

    public function index(){
        $data['allocations']=$this->OfficeAllocationModel->getAllocations('allocations_officeadjustment');
        $this->load->view('Manager/officeAllocationsBillsView',$data);
    }

    public function addOfficeAllocationsBills(){
        $this->session->unset_userdata('officeAllocationInfo');
        $this->session->unset_userdata('officeAllocation');

        $allocationCount=$this->OfficeAllocationModel->getdata('allocations_officeadjustment');
        $officeAllocationCode="ofc-".date('dmy')." : ".(count($allocationCount)+1);
        $data['allocationCode']=$officeAllocationCode;

        $data['company']=$this->OfficeAllocationModel->getdata('company');
        $data['bills']=$this->OfficeAllocationModel->getdata('bills');

        $this->load->view('Manager/addOfficeAllocationBillsView',$data);
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
                    <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                    <td><?php echo $items['retailerName']; ?></td>
                    <td><?php echo $items['netAmount']; ?></td>
                    <td><?php echo $items['receivedAmt']; ?></td>
                    <td><?php echo $items['SRAmt']; ?></td>
                    <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                    <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                    <td></td>
                    <td></td>
                    <td class="noSpace">
                        <button id="cashM" data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                        <button id="srM" data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                        
                        <button id="pendingM" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                    <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                        <button id="fsrM" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php }else{ ?>
                        <button disabled id="fsrM" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php } ?>

                        <a>
                            <button onclick="deleteMe(this,'<?php echo $id;?>');" class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
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
                        <a>
                            <button onclick="removeMe(this,'<?php echo $id;?>');" class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
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
        $remark=$this->input->post('remark');
        $title=$this->input->post('title');


        $officeInfo=$this->session->userdata('officeAllocationInfo');

        if(empty($officeInfo)){
            $allocationCount=$this->AllocationByManagerModel->getOfficeAllocationCount('allocations_officeadjustment');
            $officeAllocationCode="ofc-".date('dmy')." : ".(count($allocationCount)+1);

            if(!empty($billId)){
                $allocationData=array
                (   'createdAt' => date("Y-m-d H:i:sa"),
                    'createdBy'=>$userId,
                    'company'=>$company,
                    'remark'=>$remark,
                    'title'=>$title,
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
                                'remark'=>$remark,
                                'isAllocated'=>1
                            );
                            $this->AllocationByManagerModel->update('bills',$billUpdate,$itm['id']);

                            $history=array(
                                'billId'=>$itm['id'],
                                'officeAllocationId' => $lastInsertedId,
                                'transactionStatus' =>'Allocated in '.$officeAllocationCode,
                                // 'remark'=>$remark,
                                'transactionDate'=>date('Y-m-d H:i:sa'),
                                'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                            );
                            $this->AllocationByManagerModel->insert('bill_transaction_history',$history);
            // 			
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

        // print_r($data['allocationId']);exit;
       
        $this->session->unset_userdata('officeAllocation');
        $data['current_allocations']=$this->OfficeAllocationModel->loadAllocatedBills('allocations_officebills',$id);

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
        $this->load->view('Manager/addOfficeAllocationBillsView',$data);
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
                            <div class="col-md-12 cust-tbl">
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
                                        <button id="clearSubmit" data-dismiss="modal" class="btn btn-xs btnStyle m-t-15 waves-effect">
                                            <i class="material-icons">save</i> 
                                            <span class="icon-name">
                                            Save
                                            </span>
                                        </button> 
                                         <button data-dismiss="modal" type="button" class="btn btn-sm btn-danger m-t-15 waves-effect">
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
                            <div class="col-md-12 cust-tbl">
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
                                        <button id="clearExistingSubmit" data-dismiss="modal" class="btn btn-xs btnStyle m-t-15 waves-effect">
                                            <i class="material-icons">save</i> 
                                            <span class="icon-name">
                                            Save
                                            </span>
                                        </button> 
                                         <button data-dismiss="modal" type="button" class="btn btn-sm btn-danger m-t-15 waves-effect">
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
                            <div class="col-md-12 cust-tbl">
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
                                        <button id="clearCashSubmit" data-dismiss="modal" class="btn btn-xs btnStyle m-t-15 waves-effect">
                                            <i class="material-icons">save</i> 
                                            <span class="icon-name">
                                            Save
                                            </span>
                                        </button> 
                                         <button data-dismiss="modal" type="button" class="btn btn-xs btn-danger m-t-15 waves-effect">
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
                            <div class="col-md-12 cust-tbl">
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
                                        <button id="cashExistingSubmit" data-dismiss="modal" class="btn btn-xs btnStyle m-t-15 waves-effect">
                                            <i class="material-icons">save</i> 
                                            <span class="icon-name">
                                            Save 
                                            </span>  
                                        </button> 
                                         <button data-dismiss="modal" type="button" class="btn btn-sm btn-danger m-t-15 waves-effect">
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
                    <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                    <td><?php echo $items['retailerName']; ?></td>
                    <td><?php echo $items['netAmount']; ?></td>
                    <td><?php echo $items['receivedAmt']; ?></td>
                    <td><?php echo $items['SRAmt']; ?></td>
                    <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                    <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                    <td><?php echo $amount; ?></td>
                    <td><?php echo 'Office Adjustment'; ?></td>
                    <td class="noSpace">
                        <button id="cashM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cash")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                        <button id="srM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                        
                        <button id="pendingM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                    <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                        <button id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php }else{ ?>
                        <button id="fsrM" disabled data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php } ?>

                    <?php if($items['a_type']==""){ ?>
                        <a>
                            <button onclick="deleteMe(this,'<?php echo $items['id'];?>');" class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
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
                    <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                    <td><?php echo $items['retailerName']; ?></td>
                    <td><?php echo $items['netAmount']; ?></td>
                    <td><?php echo $items['receivedAmt']; ?></td>
                    <td><?php echo $items['SRAmt']; ?></td>
                    <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                    <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                    <td><?php echo '0.00'; ?></td>
                    <td><?php echo $items['a_type']; ?></td>
                    <td class="noSpace">
                        <button id="cashM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cash")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                        <button id="srM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                        
                        <button id="pendingM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                    <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                        <button id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php }else{ ?>
                        <button id="fsrM" disabled data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php } ?>

                    <?php if($items['a_type']==""){ ?>
                        <a>
                            <button onclick="deleteMe(this,'<?php echo $items['id'];?>');" class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
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
                    <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                    <td><?php echo $items['retailerName']; ?></td>
                    <td><?php echo $items['netAmount']; ?></td>
                    <td><?php echo $items['receivedAmt']; ?></td>
                    <td><?php echo $items['SRAmt']; ?></td>
                    <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                    <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                    <td><?php echo $amount; ?></td>
                    <td><?php echo $items['a_type']; ?></td>
                    <td class="noSpace">
                        <button id="cashM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cash")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                        <button id="srM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                        
                        <button id="pendingM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                    <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                        <button id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php }else{ ?>
                        <button id="fsrM" disabled data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php } ?>

                    <?php if($items['a_type']==""){ ?>
                        <a>
                            <button onclick="deleteMe(this,'<?php echo $items['id'];?>');" class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
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
                    <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                    <td><?php echo $items['retailerName']; ?></td>
                    <td><?php echo $items['netAmount']; ?></td>
                    <td><?php echo $items['receivedAmt']; ?></td>
                    <td><?php echo $items['SRAmt']; ?></td>
                    <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                    <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                    <td><?php echo '0.00'; ?></td>
                    <td><?php echo $items['a_type']; ?></td>
                    <td class="noSpace">
                        <button id="cashM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cash")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                        <button id="srM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                        
                        <button id="pendingM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                    <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                        <button id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php }else{ ?>
                        <button id="fsrM" disabled data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php } ?>

                    <?php if($items['a_type']==""){ ?>
                        <a>
                            <button onclick="deleteMe(this,'<?php echo $items['id'];?>');" class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
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
            
            $allocation_data=array(
                'amount'=>$amount,
                'transactionType'=>'cleared',
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
                    <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                    <td><?php echo $items['retailerName']; ?></td>
                    <td><?php echo $items['netAmount']; ?></td>
                    <td><?php echo $items['receivedAmt']; ?></td>
                    <td><?php echo $items['SRAmt']; ?></td>
                    <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                    <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                    <td><?php echo $amount; ?></td>
                    <td><?php echo 'Office Adjustment'; ?></td>
                    <td class="noSpace">
                        <button id="cashMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cash")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                        <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                        
                        <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                    <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                        <button id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php }else{ ?>
                        <button id="fsrMupdate" disabled data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
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
                    <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                    <td><?php echo $items['retailerName']; ?></td>
                    <td><?php echo $items['netAmount']; ?></td>
                    <td><?php echo $items['receivedAmt']; ?></td>
                    <td><?php echo $items['SRAmt']; ?></td>
                    <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                    <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                    <td><?php echo '0.00'; ?></td>
                    <td><?php echo $items['a_type']; ?></td>
                    <td class="noSpace">
                        <button id="cashMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cash")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                        <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                        
                        <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                    <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                        <button id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php }else{ ?>
                        <button disabled data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
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
                    <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                    <td><?php echo $items['retailerName']; ?></td>
                    <td><?php echo $items['netAmount']; ?></td>
                    <td><?php echo $items['receivedAmt']; ?></td>
                    <td><?php echo $items['SRAmt']; ?></td>
                    <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                    <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                    <td><?php echo $amount; ?></td>
                    <td><?php echo $items['a_type']; ?></td>
                    <td class="noSpace">
                        <button id="cashMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cash")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                        <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                        
                        <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                    <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                        <button id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php }else{ ?>
                        <button id="fsrMupdate" disabled data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
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
                    <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                    <td><?php echo $items['retailerName']; ?></td>
                    <td><?php echo $items['netAmount']; ?></td>
                    <td><?php echo $items['receivedAmt']; ?></td>
                    <td><?php echo $items['SRAmt']; ?></td>
                    <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                    <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                    <td><?php echo '0.00'; ?></td>
                    <td><?php echo $items['a_type']; ?></td>
                    <td class="noSpace">
                        <button id="cashMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cash")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                        <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                        
                        <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                    <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                        <button id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php }else{ ?>
                        <button disabled data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
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

        // $newCurrentBills = $this->OfficeAllocationModel->load('bills',$billId);
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
                <td><?php echo $items['a_type']; ?></td>
                <td class="noSpace">  
                    <button id="cashM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cash")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                    <button id="srM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                    
                    <button id="pendingM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                    <button id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                <?php }else{ ?>
                    <button id="fsrM" disabled data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                <?php } ?>

                    <?php if($items['a_type']==""){ ?>
                        <a>
                            <button onclick="deleteMe(this,'<?php echo $items['id'];?>');" class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
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
                    <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                    <td><?php echo $items['retailerName']; ?></td>
                    <td><?php echo $items['netAmount']; ?></td>
                    <td><?php echo $items['receivedAmt']; ?></td>
                    <td><?php echo $items['SRAmt']; ?></td>
                    <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                    <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                    <td><?php echo $items['a_amount']; ?></td>
                    <td><?php echo $items['a_type']; ?></td>
                    <td class="noSpace">
                        <button id="cashMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cash")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                        <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                        
                        <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>
 
                    <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                        <button id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php }else{ ?>
                        <button id="fsrMupdate" disabled data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
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

            // $bills_data=array(
            //     'pendingAmt'=>$netAmount,
            //     'SRAmt'=>0,
            //     'isFsrBill'=>0
            // );
            // $this->OfficeAllocationModel->update('bills',$bills_data,$billId); 
        }else{
            $allocation_data=array(
                'amount'=>$netAmount,
                'transactionType'=>'fsr',
                'updatedAt'=>date('Y-m-d H:i:sa')
            );
            
            $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$officeAllocationID);   

            // $bills_data=array(
            //     'pendingAmt'=>0,
            //     'SRAmt'=>$netAmount,
            //     'isFsrBill'=>1
            // );
            // $this->OfficeAllocationModel->update('bills',$bills_data,$billId); 
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
                <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                <td><?php echo $items['retailerName']; ?></td>
                <td><?php echo $items['netAmount']; ?></td>
                <td><?php echo $items['receivedAmt']; ?></td>
                <td><?php echo $items['SRAmt']; ?></td>
                <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                <td><?php echo $items['a_amount']; ?></td>
                <td><?php if($items['a_type']=='fsr'){ echo 'FSR'; } ?></td>
                <td class="noSpace">
                    <button id="cashM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cash")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                    <button id="srM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                    
                    <button id="pendingM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                 <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                    <button id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                <?php }else{ ?>
                    <button id="fsrM" disabled data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                <?php } ?>

                    <?php if($items['a_type']==""){ ?>
                        <a>
                            <button onclick="deleteMe(this,'<?php echo $items['id'];?>');" class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
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

            // $bills_data=array(
            //     'pendingAmt'=>$netAmount,
            //     'SRAmt'=>0,
            //     'isFsrBill'=>0
            // );
            // $this->OfficeAllocationModel->update('bills',$bills_data,$billId); 
 
        }else{
            $allocation_data=array(
                'amount'=>$netAmount,
                'transactionType'=>'fsr',
                'updatedAt'=>date('Y-m-d H:i:sa')
            );
            $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$officeAllocationID);   

            // $bills_data=array(
            //     'pendingAmt'=>0,
            //     'SRAmt'=>$netAmount,
            //     'isFsrBill'=>1
            // );
            // $this->OfficeAllocationModel->update('bills',$bills_data,$billId); 
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
                <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                <td><?php echo $items['retailerName']; ?></td>
                <td><?php echo $items['netAmount']; ?></td>
                <td><?php echo $items['receivedAmt']; ?></td>
                <td><?php echo $items['SRAmt']; ?></td>
                <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                <td><?php echo $items['a_amount']; ?></td>
                <td><?php if($items['a_type']=='fsr'){ echo 'FSR'; } ?></td>
                <td class="noSpace">
                    <button id="cashMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cash")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                    <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                    
                    <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                 <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                    <button id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                <?php }else{ ?>
                    <button id="fsrMupdate" disabled data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
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

    public function clearAllBills(){
        $billIds=$this->input->post('billArray');
        $rowId=$this->input->post('rowArray');

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
                        <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                        <td><?php echo $items['retailerName']; ?></td>
                        <td><?php echo $items['netAmount']; ?></td>
                        <td><?php echo $items['receivedAmt']; ?></td>
                        <td><?php echo $items['SRAmt']; ?></td>
                        <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                        <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                        <td><?php echo $amount; ?></td>
                        <td>Office Adjustment</td>
                        <td class="noSpace">
                            <button id="cashM" data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                            <button id="srM" data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                            
                            <button disabled id="pendingM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                        <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                            <button disabled id="fsrM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php }else{ ?>
                            <button disabled id="fsrM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php } ?>

                            <a>
                                <button disabled class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                            </a>
                        </td>
                    </tr>
            <?php
                }
            // }
        }

    }

    public function cancelClearAllBills(){
        $billIds=$this->input->post('billArray');
        $rowId=$this->input->post('rowArray');

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
            //     'officeAdjustmentBillAmount'=>$totalOfficeAdjustment,
            //     'billType'=>'officeAdjustmentBill',
            //     'isAllocated'=>1
            // );

            // $this->OfficeAllocationModel->update('bills',$data,$billId);
            // if($this->db->affected_rows()>0){

                $allocation_data=array(
                    'amount'=>0,
                    'transactionType'=>'',
                    'updatedAt'=>date('Y-m-d H:i:sa')
                );
                // print_r($allocation_data);exit;

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
                        <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                        <td><?php echo $items['retailerName']; ?></td>
                        <td><?php echo $items['netAmount']; ?></td>
                        <td><?php echo $items['receivedAmt']; ?></td>
                        <td><?php echo $items['SRAmt']; ?></td>
                        <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                        <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                        <td></td>
                        <td></td>
                        <td class="noSpace">  
                            <button id="cashM" data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                            <button id="srM" data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                            
                            <button  id="pendingM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>
  
                        <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                            <button  id="fsrM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php }else{ ?> 
                            <button disabled id="fsrM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php } ?>

                            <a>
                                <button  class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
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
                        <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                        <td><?php echo $items['retailerName']; ?></td>
                        <td><?php echo $items['netAmount']; ?></td>
                        <td><?php echo $items['receivedAmt']; ?></td>
                        <td><?php echo $items['SRAmt']; ?></td>
                        <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                        <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                        <td><?php echo $amount; ?></td>
                        <td><?php echo 'Office Adjustment'; ?></td>
                        <td class="noSpace">
                            <button id="cashMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cash")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                            <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                        
                        <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                        <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                            <button  id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php }else{ ?>
                            <button disabled data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
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
                        <td class="noSpace">
                            <button id="cashMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cash")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                            <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                        
                        <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                        <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                            <button  id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php }else{ ?>
                            <button disabled data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
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
                        <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                        <td><?php echo $items['retailerName']; ?></td>
                        <td><?php echo $items['netAmount']; ?></td>
                        <td><?php echo $items['receivedAmt']; ?></td>
                        <td><?php echo $items['SRAmt']; ?></td>
                        <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                        <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                        <td>0.00</td>
                        <td>pending</td>
                        <td class="noSpace">
                            <button id="cashM" data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                            <button disabled id="srM" data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                            
                            <button id="pendingM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                        <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                            <button disabled id="fsrM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php }else{ ?>
                            <button disabled id="fsrM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php } ?>

                            <a>
                                <button disabled class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                            </a>
                        </td>
                    </tr>
            <?php
                }
            // }
        }
    }

    public function cancelPendingAllBills(){
        $billArray=$this->input->post('billArray');
        $rowArray=$this->input->post('rowArray');
        // echo $billId.' '.$rowNo;
        $billId="";
        $rowNo="";
        for($i=0;$i<count($billArray);$i++){
            $billId=$billArray[$i];
            $rowNo=$rowArray[$i];
            
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
                        <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                        <td><?php echo $items['retailerName']; ?></td>
                        <td><?php echo $items['netAmount']; ?></td>
                        <td><?php echo $items['receivedAmt']; ?></td>
                        <td><?php echo $items['SRAmt']; ?></td>
                        <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                        <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                        <td></td>
                        <td></td>
                        <td class="noSpace">
                            <button id="cashM" data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                            <button id="srM" data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                            
                            <button id="pendingM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                        <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                            <button id="fsrM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php }else{ ?>
                            <button disabled id="fsrM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php } ?>

                            <a>
                                <button class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                            </a>
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
                        <td class="noSpace">
                            <button id="cashMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cash")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                            <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                        
                        <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                        <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                            <button  id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php }else{ ?>
                            <button id="fsrM" disabled data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
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
    
     public function pendingExistingAllBills(){
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
                        <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                        <td><?php echo $items['retailerName']; ?></td>
                        <td><?php echo $items['netAmount']; ?></td>
                        <td><?php echo $items['receivedAmt']; ?></td>
                        <td><?php echo $items['SRAmt']; ?></td>
                        <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                        <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                        <td><?php echo $items['a_amount']; ?></td>
                        <td><?php echo $items['a_type']; ?></td>
                        <td class="noSpace">
                        <button id="cashMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cash")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                        <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                        
                        <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                        <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                            <button  id="fsrM" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php }else{ ?>
                            <button id="fsrM" disabled data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
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

    public function cashAllBills(){
        $billIds=$this->input->post('billArray');
        $rowId=$this->input->post('rowArray');

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

            $officeInfo=$this->session->userdata('officeAllocationInfo');
            if(!empty($officeInfo)){
                $officeAllocationID=$officeInfo['officeAllocationID'];
                $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$officeAllocationID);   
            }
            // }

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
                        <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                        <td><?php echo $items['retailerName']; ?></td>
                        <td><?php echo $items['netAmount']; ?></td>
                        <td><?php echo $items['receivedAmt']; ?></td>
                        <td><?php echo $items['SRAmt']; ?></td>
                        <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                        <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                        <td><?php echo $bill[0]['netAmount']; ?></td>
                        <td>Cash</td>
                        <td class="noSpace">
                            <button id="cashM" data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                            <button disabled id="srM" data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                            
                            <button disabled id="pendingM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                        <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                            <button disabled id="fsrM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php }else{ ?>
                            <button disabled id="fsrM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php } ?>

                            <a>
                                <button disabled class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                            </a>
                        </td>
                    </tr>
            <?php
                }
            
        }

    }

    public function cancelCashAllBills(){
        $billIds=$this->input->post('billArray');
        $rowId=$this->input->post('rowArray');

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

            $officeInfo=$this->session->userdata('officeAllocationInfo');
            if(!empty($officeInfo)){
                $officeAllocationID=$officeInfo['officeAllocationID'];
                $this->OfficeAllocationModel->updateAllocation('allocations_officebills',$allocation_data,$billId,$officeAllocationID);   
            }
            // }

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
                        <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                        <td><?php echo $items['retailerName']; ?></td>
                        <td><?php echo $items['netAmount']; ?></td>
                        <td><?php echo $items['receivedAmt']; ?></td>
                        <td><?php echo $items['SRAmt']; ?></td>
                        <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                        <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                        <td></td>
                        <td></td>
                        <td class="noSpace">
                            <button id="cashM" data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                            <button id="srM" data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                            
                            <button id="pendingM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                        <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                            <button id="fsrM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php }else{ ?>
                            <button disabled id="fsrM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php } ?>

                            <a>
                                <button class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                            </a>
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
                        <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                        <td><?php echo $items['retailerName']; ?></td>
                        <td><?php echo $items['netAmount']; ?></td>
                        <td><?php echo $items['receivedAmt']; ?></td>
                        <td><?php echo $items['SRAmt']; ?></td>
                        <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                        <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                    <?php if($bill[0]['netAmount']==$bill[0]['pendingAmt']){ ?>
                        <td><?php echo $bill[0]['netAmount']; ?></td>
                        <td>FSR</td>
                    <?php }else{ ?>
                        <td></td>
                        <td></td>
                    <?php } ?>
                        
                        <td class="noSpace">
                            <button disabled id="cashM" data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                            <button disabled id="srM" data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                            
                            <button disabled id="pendingM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                        <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                            <button id="fsrM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php }else{ ?>
                            <button disabled id="fsrM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php } ?>

                            <a>
                                <button disabled class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                            </a>
                        </td>
                    </tr>
            <?php
                }
            
        }

    }

    public function cancelFsrAllBills(){
        $billIds=$this->input->post('billArray');
        $rowId=$this->input->post('rowArray');

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
                        <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                        <td><?php echo $items['retailerName']; ?></td>
                        <td><?php echo $items['netAmount']; ?></td>
                        <td><?php echo $items['receivedAmt']; ?></td>
                        <td><?php echo $items['SRAmt']; ?></td>
                        <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                        <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                        <td></td>
                        <td></td>
                        <td class="noSpace">
                            <button id="cashM" data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                            <button id="srM" data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                            
                            <button id="pendingM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                        <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                            <button id="fsrM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php }else{ ?>
                            <button disabled id="fsrM" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                        <?php } ?>

                            <a>
                                <button class="btn btn-xs btn-danger waves-effect" data-type="basic"><i class="material-icons">cancel</i></button>
                            </a>
                        </td>
                    </tr>
            <?php
                }
            
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
                    <td class="noSpace">
                        <button id="cashMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cash")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                        <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                    
                    <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                    <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                        <button  id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php }else{ ?>
                        <button disabled id="fsrMupdate" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
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
                    <td class="noSpace">
                        <button id="cashMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cash")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                        <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                    
                    <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                    <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                        <button  id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php }else{ ?>
                        <button disabled id="fsrMupdate" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
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
                    <td><?php echo date('d-M-Y',strtotime($items['date'])); ?></td>
                    <td><?php echo $items['retailerName']; ?></td>
                    <td><?php echo $items['netAmount']; ?></td>
                    <td><?php echo $items['receivedAmt']; ?></td>
                    <td><?php echo $items['SRAmt']; ?></td>
                    <td><?php echo $items['officeAdjustmentBillAmount']; ?></td>
                    <td id="loop"><?php echo $items['pendingAmt']; ?></td>
                    <td><?php echo $items['a_amount']; ?></td>
                    <td><?php if($items['a_type']=='fsr'){ echo 'FSR'; } ?></td>
                    <td class="noSpace">
                        <button id="cashMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cash")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                        <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                    
                    <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                    <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                        <button  id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php }else{ ?>
                        <button disabled id="fsrMupdate" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
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
                    <td class="noSpace">
                        <button id="cashMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cash")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $no; ?>" data-id="<?php echo $items['id']; ?>" data-target="#cashModal" class="btn btn-xs btn1 waves-effect" data-type="basic"><span>Cash</span></button>
                        <button id="srMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="cleared")){ echo "disabled"; } ?> data-toggle="modal" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" data-target="#clrModal" class="btn btn-xs btn2 waves-effect" data-type="basic"><span>Cleared</span></button>
                    
                    <button id="pendingMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="pending")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn3 waves-effect" data-type="basic"><span>Pending</span></button>

                    <?php if($items['netAmount']==$items['pendingAmt']){ ?>  
                        <button  id="fsrMupdate" <?php if(($items['a_type'] !=="") && ($items['a_type'] !=="fsr")){ echo "disabled"; } ?> data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
                    <?php }else{ ?>
                        <button disabled id="fsrMupdate" data-no="<?php echo $rowNo; ?>" data-id="<?php echo $items['id']; ?>" class="btn btn-xs btn4 waves-effect" data-type="basic"><span>FSR</span></button>
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
                        $countBills=$this->OfficeAllocationModel->getCount('allocations_officebills',$id);
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
                        $countBills=$this->OfficeAllocationModel->getCount('allocations_officebills',$id);
                       

                         // if(($billData[0]['billType'] === "officeAdjustmentBill") && ($billData[0]['officeAdjustmentBillAmount'] != 0)){
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
                            'remark'=>'','isAllocated'=>'0'    
                        );
                        $this->AllocationByManagerModel->update('bills',$rtData,$id);

                        $history=array(
							'billId'=>$id,
							'officeAllocationId'=>$allocationId,
							'transactionStatus' =>'Removed from allocation',
							'transactionDate'=>date('Y-m-d H:i:sa'),
							'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
						);
						$this->AllocationByManagerModel->insert('bill_transaction_history',$history);
                    }else{
                        $rtData=array
                        (
                            'billType'=>'','remark'=>'','isAllocated'=>'0'    
                        );
                        $this->AllocationByManagerModel->update('bills',$rtData,$id);

                        $history=array(
							'billId'=>$id,
							'officeAllocationId'=>$allocationId,
							'transactionStatus' =>'Removed from allocation',
							'transactionDate'=>date('Y-m-d H:i:sa'),
							'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
						);
						$this->AllocationByManagerModel->insert('bill_transaction_history',$history);
                    }
                }else{
                    $countBills=$this->AllocationByManagerModel->getCount('allocations_officebills',$id);
                       
                     // if(($billData[0]['billType'] === "officeAdjustmentBill") && ($billData[0]['officeAdjustmentBillAmount'] != 0)){
                    if($countBills>0){
                        $rtData=array
                        (
                            'isAllocated'=>'0'    
                        );
                        $this->AllocationByManagerModel->update('bills',$rtData,$id);

                        $history=array(
							'billId'=>$id,
							'officeAllocationId'=>$allocationId,
							'transactionStatus' =>'Removed from allocation',
							'transactionDate'=>date('Y-m-d H:i:sa'),
							'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
						);
						$this->AllocationByManagerModel->insert('bill_transaction_history',$history);
                    }else{
                        $rtData=array
                        (
                            'billType'=>'allocatedbillCurrent','remark'=>'','isAllocated'=>'0'    
                        );
                        $this->AllocationByManagerModel->update('bills',$rtData,$id);

                        $history=array(
							'billId'=>$id,
							'officeAllocationId'=>$allocationId,
							'transactionStatus' =>'Removed from allocation',
							'transactionDate'=>date('Y-m-d H:i:sa'),
							'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
						);
						$this->AllocationByManagerModel->insert('bill_transaction_history',$history);
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
        // echo "he";exit;

        $userId = $this->session->userdata[$this->projectSessionName]['id'];

        $loadBills=$this->OfficeAllocationModel->loadOfficeBills('allocations_officebills',$officeAllocationID);
        
        $designation = ($this->session->userdata[$this->projectSessionName]['designation']);
        $des=explode(',',$designation);

        $managerStatus=0;
        $ownerStatus=0;
        $allocationComplete=0;
        if ((in_array('owner', $des))) {
            $managerStatus=1;
            $ownerStatus=1;
            $allocationComplete=1;
        }else{
            $managerStatus=1;
            $ownerStatus=0;
            $allocationComplete=0;
        }

        $data=array(
            "managerStatus"=>$managerStatus,'ownerStatus'=>$ownerStatus,'isAllocationComplete'=>$allocationComplete
        );
        $this->OfficeAllocationModel->update('allocations_officeadjustment',$data,$officeAllocationID);
        if($this->db->affected_rows()>0){
            if ((in_array('owner', $des))) {
                if($allocationComplete==1){
                    if(!empty($loadBills)){
                        foreach ($loadBills as $bill) {
                            $mode=$bill['transactionType'];
                            $amount=$bill['amount'];
                            $billId=$bill['billId'];
                            $billData=$this->OfficeAllocationModel->load('bills',$billId);
                            // $pendingAmt=$billData[0]['pendingAmt'];
                            $pendingAmt=$billData[0]['pendingAmt'];
                            $netBillAmt=$billData[0]['netAmount'];
                            $receivedAmt=$billData[0]['receivedAmt'];
                            $officeAdjustment=$billData[0]['officeAdjustmentBillAmount'];

                            if($mode=="cash"){
                                $receivedAmt=$billData[0]['receivedAmt'];
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
                                    'company'=>$billData[0]['compName'],
                                    'billId'=>$billData[0]['id'],
                                    'date'=>date('Y-m-d H:i:sa'),
                                    'employeeId'=>$userId,
                                    'amount'=>$amount,
                                    'nature'=>'Office Collection',
                                    'narration'=>'Bill No. '.$billData[0]['billNo'],
                                    'inoutStatus'=>'Inflow',
                                    'openCloseBalance'=>$totalClosingBalance,
                                    'updatedBy'=>$userId
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
                                $this->OfficeAllocationModel->insert('billpayments',$billPayment);//inser
                            }else if($mode=="cleared"){
                                $officeAdjustmentBillAmount=$billData[0]['officeAdjustmentBillAmount'];
                                $latestPending=$pendingAmt-$amount;
                                $latestOfficeAdjustmentBillAmount=$officeAdjustmentBillAmount+$amount;
                                $updBillData=array(
                                    'billCurrentStatus'=> 'Office Adjustment',
                                    'isAllocated'=>0,
                                    'pendingAmt'=>$latestPending,
                                    'officeAdjustmentBillAmount'=>$latestOfficeAdjustmentBillAmount
                                );
                                $this->OfficeAllocationModel->update('bills',$updBillData,$billId);

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
                                    'balanceAmount'=>$latestPending,
                                    'paymentMode'=>'Office Adjustment',
                                    'ownerApproval'=>1,
                                    'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                                );
                                $this->OfficeAllocationModel->insert('billpayments',$billPayment);//insert
                            }else if($mode=="fsr"){
                                $srAmount=$billData[0]['SRAmt'];
                                $latestPending=$pendingAmt-$amount;
                                $latestSrAmount=$srAmount+$amount;
                                $updBillData=array(
                                    'billCurrentStatus'=> 'Office Adjustment',
                                    'isAllocated'=>0,
                                    'pendingAmt'=>$latestPending,
                                    'SRAmt'=>$latestSrAmount,
                                    'isFsrBill'=>1
                                );
                                $this->OfficeAllocationModel->update('bills',$updBillData,$billId);

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
                                    'officeAllocationId'=>$officeAllocationID,
                                    'transactionAmount' =>$amount,
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
                            }else if($mode=="pending"){
                                $officeAdjustmentBillAmount=$billData[0]['officeAdjustmentBillAmount'];
                                $latestPending=$pendingAmt-$amount;
                                $latestOfficeAdjustmentBillAmount=$officeAdjustmentBillAmount+$amount;
                                $updBillData=array(
                                    'billCurrentStatus'=>'Office Adjustment',
                                    'isAllocated'=>0,
                                    'pendingAmt'=>$latestPending,
                                    'officeAdjustmentBillAmount'=>$latestOfficeAdjustmentBillAmount
                                );
                                $this->OfficeAllocationModel->update('bills',$updBillData,$billId);

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
                            }
                        }
                    }  
                }
            }
            echo "Record saved";
        }else{
            echo "Record not saved";
        }
    }

    public function closeExistCurrentAllocation(){
        $officeAllocationID=$this->input->post('alId');

        $loadBills=$this->OfficeAllocationModel->loadOfficeBills('allocations_officebills',$officeAllocationID);
        $designation = ($this->session->userdata[$this->projectSessionName]['designation']);
        $des=explode(',',$designation);

        $userId = $this->session->userdata[$this->projectSessionName]['id'];
        // print_r($loadBills);exit;


        if(!empty($loadBills)){
            $designation = ($this->session->userdata[$this->projectSessionName]['designation']);
            $des=explode(',',$designation);

            $managerStatus=0;
            $ownerStatus=0;
            $allocationComplete=0;
            if ((in_array('owner', $des))) {
                $managerStatus=1;
                $ownerStatus=1;
                $allocationComplete=1;
            }else{
                $managerStatus=1;
                $ownerStatus=0;
                $allocationComplete=0;
            }

            $data=array(
                "managerStatus"=>$managerStatus,'ownerStatus'=>$ownerStatus,'isAllocationComplete'=>$allocationComplete
            );

            $this->OfficeAllocationModel->update('allocations_officeadjustment',$data,$officeAllocationID);
            if($this->db->affected_rows()>0){
                if ((in_array('owner', $des))) {
                    if($allocationComplete==1){
                        if(!empty($loadBills)){
                            foreach ($loadBills as $bill) {
                                $mode=$bill['transactionType'];
                                $amount=$bill['amount'];
                                $billId=$bill['billId'];

                                $billData=$this->OfficeAllocationModel->load('bills',$billId);
                                $pendingAmt=$billData[0]['pendingAmt'];
                                $netBillAmt=$billData[0]['netAmount'];
                                $receivedAmt=$billData[0]['receivedAmt'];
                                $officeAdjustment=$billData[0]['officeAdjustmentBillAmount'];

                                if($mode==="cash"){
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
                                        'company'=>$billData[0]['compName'],
                                        'billId'=>$billData[0]['id'],
                                        'date'=>date('Y-m-d H:i:sa'),
                                        'employeeId'=>$userId,
                                        'amount'=>$amount,
                                        'nature'=>'Office Collection',
                                        'narration'=>'Bill No. '.$billData[0]['billNo'],
                                        'inoutStatus'=>'Inflow',
                                        'openCloseBalance'=>$totalClosingBalance,
                                        'updatedBy'=>$userId
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
                                    $this->OfficeAllocationModel->insert('billpayments',$billPayment);//inser
                        
                                }else if($mode==="cleared"){
                                    $officeAdjustmentBillAmount=$billData[0]['officeAdjustmentBillAmount'];
                                    $latestPending=$pendingAmt-$amount;
                                    $latestOfficeAdjustmentBillAmount=$officeAdjustmentBillAmount+$amount;
                                    $updBillData=array(
                                        'billCurrentStatus'=> 'Office Adjustment',
                                        'isAllocated'=>0,
                                        'pendingAmt'=>$latestPending,
                                        'officeAdjustmentBillAmount'=>$latestOfficeAdjustmentBillAmount
                                    );
                                    $this->OfficeAllocationModel->update('bills',$updBillData,$billId);

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
                                        'balanceAmount'=>$latestPending,
                                        'paymentMode'=>'Office Adjustment',
                                        'ownerApproval'=>1,
                                        'updatedBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                                    );
                                    $this->OfficeAllocationModel->insert('billpayments',$billPayment);//insert
                                }else if($mode==="fsr"){
                                    $srAmount=$billData[0]['SRAmt'];
                                    $latestPending=$pendingAmt-$amount;
                                    $latestSrAmount=$srAmount+$amount;
                                    $updBillData=array(
                                        'billCurrentStatus'=> 'Office Adjustment',
                                        'isAllocated'=>0,
                                        'pendingAmt'=>$latestPending,
                                        'SRAmt'=>$latestSrAmount,
                                        'isFsrBill'=>1
                                    );
                                    $this->OfficeAllocationModel->update('bills',$updBillData,$billId);

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
                                        'officeAllocationId'=>$officeAllocationID,
                                        'transactionAmount' =>$amount,
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
                                }else if($mode==="pending"){
                                    $officeAdjustmentBillAmount=$billData[0]['officeAdjustmentBillAmount'];
                                    $latestPending=$pendingAmt-$amount;
                                    $latestOfficeAdjustmentBillAmount=$officeAdjustmentBillAmount+$amount;
                                    $updBillData=array(
                                        'billCurrentStatus'=>'Office Adjustment',
                                        'isAllocated'=>0,
                                        'pendingAmt'=>$latestPending,
                                        'officeAdjustmentBillAmount'=>$latestOfficeAdjustmentBillAmount
                                    );
                                    $this->OfficeAllocationModel->update('bills',$updBillData,$billId);

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
                                }
                            }
                        }  
                    }
                }
                echo "Record saved";
            }else{
                echo "Record not saved";
            }
        }else{
            echo "no bills available";
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

                $this->OfficeAllocationModel->deleteAllocationsBills('allocations_officebills',$billId,$allocationId);

                if($billData[0]['netAmount']==$billData[0]['pendingAmt']){
                    $countBills=$this->OfficeAllocationModel->getCount('allocations_officebills',$billId);
                    if($countBills>0){
                        $rtData=array
                        (
                            'billType'=>'allocatedbillPass','remark'=>'','isAllocated'=>'0'    
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
                            'billType'=>'allocatedbillPass','remark'=>'','isAllocated'=>'0'    
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
                    $countBills=$this->OfficeAllocationModel->getCount('allocations_officebills',$billId);
                    if($countBills>0){

                    // if(($billData[0]['billType'] === "officeAdjustmentBill") && ($billData[0]['officeAdjustmentBillAmount'] != 0)){
                        $rtData=array
                        (
                            'billType'=>'allocatedbillPass','isAllocated'=>'0'    
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
                            'billType'=>'allocatedbillPass','remark'=>'','isAllocated'=>'0'    
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

                $this->OfficeAllocationModel->deleteAllocationsBills('allocations_officebills',$billId,$allocationId);

                // $updateBill=array('isAllocated');
                if($billData[0]['netAmount']==$billData[0]['pendingAmt']){
                    $countBills=$this->OfficeAllocationModel->getCount('allocations_officebills',$billId);
                    if($countBills>0){
                        $rtData=array
                        (
                            'billType'=>'allocatedbillPass','remark'=>'','isAllocated'=>'0'    
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
                            'billType'=>'allocatedbillPass','remark'=>'','isAllocated'=>'0'    
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
                            'billType'=>'allocatedbillPass','isAllocated'=>'0'    
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
                            'billType'=>'allocatedbillPass','remark'=>'','isAllocated'=>'0'    
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
    
}