<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SrCheckController extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->model('SrModel');
		$this->load->library('session');
		date_default_timezone_set('Asia/Kolkata');
		ini_set('memory_limit', '-1');

		if(isset($this->session->userdata['codeKeyData'])) {
			$this->projectSessionName= $this->session->userdata['codeKeyData']['codeKeyValue'];
		}else{
			$this->load->view('LoginView');
		}
	}

	 public function saveSrCheck(){
        $allocationId=trim($this->input->post('allocationId'));
        $data=array('sr_bill_Status'=>1);
        $this->SrModel->update('allocations',$data,$allocationId);

        
    }

    

    public function pendingSr(){
        $data['pendingSr']=$this->SrModel->getDetails('allocation_sr_details');
        $officeSr=$this->SrModel->getOfficeSr('allocation_sr_details');
        $data['countOffice']=count($officeSr);
        $this->load->view('Manager/allocationSr',$data);
    }

    public function pendingAllocationSr($id){
        $data['allocationId']=$id;
        $data['allocationDetails']=$this->SrModel->getAllocatedBillInfo('allocations',$id);
        $data['title']="Allocation SR Details";
        $data['srDetails']=$this->SrModel->getAllocationSrDetails('allocation_sr_details',$id);
        $this->load->view('Manager/allocationSrDetails',$data);
    }

	public function LoadSrCheckDetails($id){
		$data['signed']=$this->SrModel->getSignedBills('bills',$id);
		$data['idAllocated']=$id;
        $data['allocations']=$this->SrModel->load('allocations',$id);
		$data['BillInfo']=$this->SrModel->getAllocatedBillInfo('allocations',$id);

		$otherCheck=$this->SrModel->load('tbl_settings',3);
			
		$data['otherAdj']=array();
		if(!empty($otherCheck)){
			if($otherCheck[0]['propertyValue']=="yes"){
				$data['otherAdj']=$this->SrModel->getOtherAdjAllocatedBills('allocations',$id);
			}
		}

		// print_r($data['signed']);
		// print_r($data['otherAdj']);exit;

		if(!empty($data['signed']) || !empty($data['otherAdj'])){
			// print_r($data);exit;
			// $data['signed']=$this->SrModel->getSignedBills('bills',$id);
			$this->load->view('Manager/SrCheckView',$data);
		}else{
			// $allocationId=trim($this->input->post('allocationId'));
			$data=array('sr_bill_Status'=>1);
			$this->SrModel->update('allocations',$data,$id);
			redirect('AllocationByManagerController/openAllocations');
		}
	}

	public function finalSrBillStatus($allocationId){
		$data['signed']=$this->SrModel->getSignedBills('bills',$allocationId);
		$data['signedCheck']=$this->SrModel->checkSignedBills('bills',$allocationId);
		$data['BillInfo']=$this->SrModel->getAllocatedBillInfo('allocations',$allocationId);
		$data['idAllocated']=$allocationId;

		$otherCheck=$this->SrModel->load('tbl_settings',3);
			
		$data['otherAdj']=array();
		if(!empty($otherCheck)){
			if($otherCheck[0]['propertyValue']=="yes"){
				$data['otherAdj']=$this->SrModel->getOtherAdjAllocatedBills('allocations',$allocationId);
			}
		}

		if(!empty($data['signedCheck']) || !empty($data['otherAdj'])){
			redirect('manager/SrCheckController/LoadSrCheckDetails/'.$allocationId);
		}else{
			$data=array('sr_bill_Status'=>1);
			$this->SrModel->update('allocations',$data,$allocationId);
			redirect('AllocationByManagerController/openAllocations');
		}
	}

	public function updateSrBillStatus(){
		$allocationId=trim($this->input->post('allocationId'));
		$data=array('sr_bill_Status'=>1);
		$this->SrModel->update('allocations',$data,$allocationId);
		// redirect('AllocationByManagerController/openAllocations');
	}

	public function SrCheckEdit(){
		$id=trim($this->input->post('id'));
		$allocationId=trim($this->input->post('allocationId'));

		$billDetail=$this->SrModel->loadSrBills('billsdetails',$id);

		?>
			<div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h4 style="color: #099dba;">
                            
                           <span style="color: black;">Bill No :</span> 
                            <?php 
                                if(!empty($billDetail)){
                                     echo $billDetail[0]['billNo'];
                                }
                            ?>
                            &nbsp; &nbsp;
                             <span style="color: black;">Retailer Name :</span> 
                            <?php 
                                if(!empty($billDetail)){
                                     echo $billDetail[0]['retailerName'];
                                }
                            ?>
                            &nbsp; &nbsp;
                            </h4>
                        </div>
                            
                        <div class="body">
                        <div class="table-responsive">
                        <div class="body">
                            <div class="demo-masked-input">
                                    <div class="row clearfix">
                                         <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100' id='tbl'>
                                                <thead>
                                                    <tr>
                                                        <th>Sr.No</th>
                                                        <th>Item</th>
                                                        <th>Net Amt</th>
                                                        <th>MRP</th>
                                                        
                                                        <th>Billed Qty</th>
                                                        <th>GK SR</th>
                                                        <th>Additional</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th>Sr.No</th>
                                                        <th>Item</th>
                                                         <th>Net Amt</th>
                                                        <th>MRP</th>
                                                       
                                                        <th>Billed Qty</th>
                                                        <th>GK SR</th>
                                                        <th>Additional</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    <?php
                                                    $no=0;
                                       
                                        foreach ($billDetail as $data) 
                                          { 
                                             $no++; 
                                        ?>
                                            <tr>
                                                <td><?php echo $no;?></td>
                                                <td><?php echo $data['productName'];?></td>
                                                 <td><?php echo $data['netAmount'];?></td>
                                                <td><?php echo $data['mrp'];?></td>

                                                <td><?php echo number_format($data['qty']);?></td>
                                                <td><?php echo number_format($data['gkReturnQty']);?></td>
                                                <td><input id='sr'style="width: 70px" type="text" name="additional">
                                                    <div id="srError" style="color: red"></div>
                                                </td>
                                                
                                                <td>
                                                <button style="font-size: 12px" onclick="updateSRqty(this,'<?php echo $data['id'];?>','<?php echo $data['billId'];?>');" class=" btn-primary waves-effect">
                                                    <span class="icon-name">
                                                    Received
                                                    </span>
                                                </button>
                                           
                                                <button style="font-size: 12px" onclick="debitSRqty(this,'<?php echo $data['id'];?>','<?php echo $allocationId; ?>');" class="btn-primary waves-effect">
                                                    <span class="icon-name">
                                                    Debit
                                                    </span>
                                                </button>
                                                </td>
                                           </tr>
                                     <?php
                                            }
                                        ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}
	
	public function FsrCheckEdit($id){
		$data['billDetail']=$this->SrModel->loadFsrBills('billsdetails',$id);
		$this->load->view('Manager/FsrCheckEditView',$data);
	}

	public function ChangeManagerStatus(){
		$id=$this->input->post('id');
		$allocatedId=$this->input->post('allocatedId');
		$updateData=array('managerSrStatus' => 1);

		$this->SrModel->update('billsdetails',$updateData,$id);
		if($this->db->affected_rows()>0){
			echo "";
		}else{
			echo "fail";
		}
	}

	public function ChangeManagerStatusForSigned(){
		$id=$this->input->post('billIdForRemark');
		$allocatedId=$this->input->post('allocationIdForRemark');
		$billRemark=$this->input->post('billRemark');
		// $data=$this->SrModel->loadByBillId('billsdetails',$id);

		// $getInfo
		
		// foreach($data as $itm){
			// $updateData=array('managerSrStatus' => 4);
			$updateData=array('billCurrentStatus'=>'Lost Bill','isLostBill' => 2,'remark'=>$billRemark);
			$this->SrModel->update('bills',$updateData,$id);
			$billData=$this->SrModel->load('bills',$id);
			$history=array(
				'billId'=>$id,
				'allocationId' => $allocatedId,
				'transactionStatus' =>'Lost Bill',
				'remark' =>'Lost Bill for pending amount '.number_format($billData[0]['pendingAmt']).'. '.$billRemark,
				'transactionDate'=>date('Y-m-d H:i:sa'),
				'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
			);
			$this->SrModel->insert('bill_transaction_history',$history);
			

			if($this->db->affected_rows()>0){
				$lost=array('isLostBill'=>1);
				$this->SrModel->updateLostbillStatus('allocationsbills',$lost,$id,$allocatedId);
			}else{
				echo "fail";
			}
			redirect('manager/SrCheckController/LoadSrCheckDetails/'.$allocatedId);
		// }
	}

	public function ChangeManagerStatusForSignedOk(){
		$id=$this->input->post('id');
		$allocatedId=$this->input->post('allocatedId');
		// $data=$this->SrModel->loadByBillId('billsdetails',$id);
		
		// foreach($data as $itm){
			$updateData=array('isLostBill' => 1);
			$this->SrModel->update('bills',$updateData,$id);
			if($this->db->affected_rows()>0){
				$checkSigned=$this->SrModel->checkStatusForHistory('bill_transaction_history',$id,$allocatedId,'Signed');
				$billData=$this->SrModel->load('bills',$id);
				if(empty($checkSigned)){
					$history=array(
						'billId'=>$id,
						'allocationId' => $allocatedId,
						'transactionStatus' =>'Signed',
						'remark' =>'Signed for pending amount '.number_format($billData[0]['pendingAmt']),
						'transactionDate'=>date('Y-m-d H:i:sa'),
						'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
					);
					$this->SrModel->insert('bill_transaction_history',$history);
					
					$status=array('billCurrentStatus'=>'Signed');
					$this->SrModel->update('bills',$status,$id);
				}else{
					$history=array(
						'billId'=>$id,
						'allocationId' => $allocatedId,
						'transactionStatus' =>'Signed',
						'remark' =>'Signed for pending amount '.number_format($billData[0]['pendingAmt']),
						'transactionDate'=>date('Y-m-d H:i:sa'),
						'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
					);
					$this->SrModel->updateHistory('bill_transaction_history',$history,$id,$allocatedId,'Signed');
					
					$status=array('billCurrentStatus'=>'Signed');
					$this->SrModel->update('bills',$status,$id);
				}
				
				echo "";
			}else{
				echo "fail";
			}
		// }
	}

	public function ChangeManagerStatusForSignedOkWithCheckBox(){
		$value=$this->input->post('selValue');
		$notselValue=$this->input->post('notselValue');
		$textValue=$this->input->post('textValue');
// print_r($textValue);exit;
		
		if(!empty($value)){
            foreach($value as $sel){
				if($sel !="on"){
					$exp=explode(':',$sel);
					$allocatedId=$exp[0];
					$id=$exp[1];

					$updateData=array('isLostBill' => 1);
					$this->SrModel->update('bills',$updateData,$id);
					if($this->db->affected_rows()>0){
						$checkSigned=$this->SrModel->checkStatusForHistory('bill_transaction_history',$id,$allocatedId,'Signed');
						$billData=$this->SrModel->load('bills',$id);
						if(empty($checkSigned)){
							$history=array(
								'billId'=>$id,
								'allocationId' => $allocatedId,
								'transactionStatus' =>'Signed',
								'remark' =>'Signed for pending amount '.number_format($billData[0]['pendingAmt']),
								'transactionDate'=>date('Y-m-d H:i:sa'),
								'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
							);
							$this->SrModel->insert('bill_transaction_history',$history);
							
							$status=array('billCurrentStatus'=>'Signed');
							$this->SrModel->update('bills',$status,$id);
						}else{
							$history=array(
								'billId'=>$id,
								'allocationId' => $allocatedId,
								'transactionStatus' =>'Signed',
								'remark' =>'Signed for pending amount '.number_format($billData[0]['pendingAmt']),
								'transactionDate'=>date('Y-m-d H:i:sa'),
								'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
							);
							$this->SrModel->updateHistory('bill_transaction_history',$history,$id,$allocatedId,'Signed');
							
							$status=array('billCurrentStatus'=>'Signed');
							$this->SrModel->update('bills',$status,$id);
						}
						
						echo "";
					}else{
						echo "fail";
					}
				}
			}
		}
		
		if(!empty($notselValue)){
			$n=0;
			foreach($notselValue as $sel){
				if($sel !="on"){
					$exp=explode(':',$sel);
					$allocatedId=$exp[0];
					$id=$exp[1];
					$billRemark=$textValue[$n];
					$n++;

					$updateData=array('billCurrentStatus'=>'Lost Bill','isLostBill' => 2,'remark'=>$billRemark);
					$this->SrModel->update('bills',$updateData,$id);
					$billData=$this->SrModel->load('bills',$id);
					$history=array(
						'billId'=>$id,
						'allocationId' => $allocatedId,
						'transactionStatus' =>'Lost Bill',
						'remark' =>'Lost Bill for pending amount '.number_format($billData[0]['pendingAmt']).'. '.$billRemark,
						'transactionDate'=>date('Y-m-d H:i:sa'),
						'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
					);
					$this->SrModel->insert('bill_transaction_history',$history);
					

					if($this->db->affected_rows()>0){
						$lost=array('isLostBill'=>1);
						$this->SrModel->updateLostbillStatus('allocationsbills',$lost,$id,$allocatedId);
					}else{
						echo "fail";
					}
				}
			}
		}
	}

	public function ReceivedSR(){
		$srQty=$this->input->post('srQty');
		$id=$this->input->post('id');
		$billId=$this->input->post('billId');

		$data['bills']=$this->SrModel->load('billsdetails',$id);
		$fsReturnQty=$data['bills'][0]['fsReturnQty'];
		$gkReturnQty=$data['bills'][0]['gkReturnQty'];
		$billedQty=$data['bills'][0]['qty'];
		$netAmount=$data['bills'][0]['netAmount'];
		$fsGkShortQty=$data['bills'][0]['fsGkShortQty'];
		
		$finalReturnQty=round(($gkReturnQty+$srQty),2);
		$fsGkShortQty=round($fsGkShortQty-($fsReturnQty-$gkReturnQty),2);
		if($billedQty>=$finalReturnQty){
			$eachItemAmt=$netAmount/$billedQty;
			$returnAmt=round($finalReturnQty*$eachItemAmt,2);
			$updateBills=array('gkReturnQty' => $finalReturnQty,'fsReturnAmt' => $returnAmt,'fsGkShortQty' => $fsGkShortQty);
			$this->SrModel->update('billsdetails',$updateBills,$id);
			if($this->db->affected_rows()>0){
				$getBillDetails=$this->SrModel->loadByBillId('billsdetails',$billId);
				$total=0;
				foreach($getBillDetails as $tot){
					$total=$total+$tot['fsReturnAmt'];
				}
				$updatefsRetAmt=array('fsSrAmt' => $total);
				$this->SrModel->update('bills',$updatefsRetAmt,$billId);
				if($this->db->affected_rows()>0){
					echo "SR qty updated";
				}else{
					echo "fail";
				}
			}else{
				echo "fail";
			}
		}else{
			echo "Your SR Qty (".$srQty."+".round($gkReturnQty).") will be greater than Billed Qty(".round($billedQty).").";
		}
	}

	public function DebitSR(){
		$srQty=$this->input->post('srQty');
		$id=$this->input->post('id');
		$allocatedId=trim($this->input->post('allocatedId'));

		$data['bills']=$this->SrModel->debitData('billsdetails',$id);

		
		$empId=array();
		if($data['bills'][0]['fieldStaffCode1'] !=0){
			array_push($empId,$data['bills'][0]['fieldStaffCode1']);		
		}
		if($data['bills'][0]['fieldStaffCode2'] !=0){
			array_push($empId,$data['bills'][0]['fieldStaffCode2']);	
		}
		if($data['bills'][0]['fieldStaffCode3'] !=0){
			array_push($empId,$data['bills'][0]['fieldStaffCode3']);				
		}
		if($data['bills'][0]['fieldStaffCode4'] !=0){
			array_push($empId,$data['bills'][0]['fieldStaffCode4']);				
		}

		//Get Billsdetails by Id
		$data['bills']=$this->SrModel->load('billsdetails',$id);

		$fsReturnQty=$data['bills'][0]['fsReturnQty'];
		$gkReturnQty=$data['bills'][0]['gkReturnQty'];
		$billedQty=$data['bills'][0]['qty'];
		$netAmount=$data['bills'][0]['netAmount'];
		$fsGkShortQty=$data['bills'][0]['fsGkShortQty'];

		$finalReturnQty=round($srQty,2);
		$fsGkShortQty=round($fsGkShortQty-($fsReturnQty-$gkReturnQty),2);
		if($billedQty>=$finalReturnQty){
			//price for each item
			$eachItemAmt=$netAmount/$billedQty;
			$debitAmt=$eachItemAmt*$srQty;
			$description=$debitAmt." will debited from Employee ID ".$empId[0].", Allocation ID is ".$allocatedId." for short quantity ".$srQty;

			$insertData=array('employeeId' => $empId[0],'allocationId'=>$allocatedId,'debitAmt' => $debitAmt,'description'=>$description,'createdAt'=>date('Y-m-d H:i:sa'));
			print_r($insertData);
			exit;
		}else{
			echo "Your SR Qty (".$srQty."+".round($gkReturnQty).") will be greater than Billed Qty(".round($billedQty).").";
		}
	}

	public function ApprovedSR($id,$billId,$allocatedId){
		$data['billDetails']=$this->SrModel->load('billsdetails',$id);
		$data['bills']=$this->SrModel->loadBill('bills',$id);
		$pendingAmt=$data['bills'][0]['pendingAmt'];		
		$total=0;
		foreach($data['billDetails'] as $item){
			$total=$total+$item['fsReturnAmt'];
		}
		$pendingAmt=$data['bills'][0]['pendingAmt']-$data['billDetails'][0]['fsReturnAmt'];
		
		$updateData=array('managerSrStatus'=>2);
		$this->SrModel->update('billsdetails',$updateData,$id);
		if($this->db->affected_rows() > 0){
			$updateData1=array('pendingAmt'=>$pendingAmt);
			$this->SrModel->update('bills',$updateData1,$billId);
			if($this->db->affected_rows() > 0){
				// $this->LoadSrCheckDetails($allocatedId);
				redirect('manager/SrCheckController/LoadSrCheckDetails/'.$allocatedId);
				// redirect('AllocationByManagerController/openAllocations');
			}else{
				redirect('manager/SrCheckController/LoadSrCheckDetails/'.$allocatedId);
				// redirect('AllocationByManagerController/openAllocations');
			}
		}else{
			redirect('manager/SrCheckController/LoadSrCheckDetails/'.$allocatedId);
			// redirect('AllocationByManagerController/openAllocations');
		}
	}

	public function ApprovedFSR($id,$allocatedId){
		$data['billDetails']=$this->SrModel->loadByBillId('billsdetails',$id);
		$data['bills']=$this->SrModel->load('bills',$id);
		
		$pendingAmt=$data['bills'][0]['pendingAmt'];	
		foreach($data['billDetails'] as $d){
			$pendingAmt=$pendingAmt-$d['fsReturnAmt'];
			$updateData=array('managerSrStatus'=>2);
			$this->SrModel->update('billsdetails',$updateData,$d['id']);
			if($this->db->affected_rows() > 0){
				$updateData1=array('pendingAmt'=>$pendingAmt);
				$this->SrModel->update('bills',$updateData1,$id);
				if($this->db->affected_rows() > 0){
				// redirect('manager/SrCheckController/LoadSrCheckDetails/'.$allocatedId);
				}else{
					echo "fail";
				}
			}else{
				echo "fail";
			}
		}

		redirect('manager/SrCheckController/LoadSrCheckDetails/'.$allocatedId);
	}

	public function Disapproved(){
		$id=trim($this->input->post('id'));
		$allocationId=trim($this->input->post('allocatedId'));

		$billID=$id;
		$allocationId=$allocationId;
		$bills=$this->SrModel->loadBill('bills',$id);
		$salesman=$this->SrModel->getSalesman('bills');
	?>
	
	<div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                          <h2>Disapproved SR </h2>
                      </div>

                      <div class="body">
                        <div class="table-responsive">
                            <div class="body">
                                <div class="demo-masked-input">
                                    <div class="row clearfix">
                                        <div class="col-md-12">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-4">
                                                <p>
                                                  <b>Salesman </b>
                                              </p>
                                              <div class="form-line">
                                                <input class="form-control" type="hidden" id="bId" name="bId" value="<?php
                                                if(isset($billID))
                                                {
                                                    echo $billID;
                                                }
                                                ?>">
                                                <input class="form-control" type="hidden" id="allocatedID" name="allocatedID" value="<?php
                                                if(isset($allocationId))
                                                {
                                                    echo $allocationId;
                                                }
                                                ?>">

                                                <input autocomplete="off" type="text" id="addeName" list="empList" name="eName" class="form-control" value="<?php if(isset($bills[0]['salesman'])){
                                                    echo $bills[0]['salesman'];
                                                } ?>">
                                                <datalist id="empList">
                                                    <?php
                                                    foreach($salesman as $data){
                                                        $name=$data['salesman'];
                                                        ?>   
                                                        <option value="<?php echo $name;?>"/>
                                                            <?php    
                                                        }
                                                        ?>
                                                    </datalist>
                                                </div> 
                                                <button id="empAdd" onclick="allsrempList();" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">add</i> 
                                                    <span class="icon-name">
                                                        Add to List
                                                    </span>
                                                </button> 

                                            </div>  
                                            <div class="col-md-4">
                                                <label>Selected Emp</label>
                                                <ul class="list-group" id="emp_list" multiple="multiple"></ul>
                                            </div>
                                            <div class="col-md-2"></div>
                                        </div>
                                        <center>
                                            <button id="insert_usr" class="btn btn-primary m-t-15 waves-effect">
                                                <i class="material-icons">save</i> 
                                                <span class="icon-name">Save</span>
                                            </button> 
                                        </center>
                                    </div>
                                    <div id="res"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	<?php
	
	}

	public function Disapproved_FSR(){
		$id=trim($this->input->post('id'));
		$allocationId=trim($this->input->post('allocatedId'));

		$billID=$id;
		$allocationId=$allocationId;
		$bills=$this->SrModel->loadBill('bills',$id);
		$salesman=$this->SrModel->getSalesman('bills');
	?>
	
	<div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                          <h2>Disapproved SR </h2>
                      </div>

                      <div class="body">
                        <div class="table-responsive">
                            <div class="body">
                                <div class="demo-masked-input">
                                    <div class="row clearfix">
                                        <div class="col-md-12">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-4">
                                                <p>
                                                  <b>Salesman </b>
                                              </p>
                                              <div class="form-line">
                                                <input class="form-control" type="hidden" id="bId" name="bId" value="<?php
                                                if(isset($billID))
                                                {
                                                    echo $billID;
                                                }
                                                ?>">
                                                <input class="form-control" type="hidden" id="allocatedID" name="allocatedID" value="<?php
                                                if(isset($allocationId))
                                                {
                                                    echo $allocationId;
                                                }
                                                ?>">

                                                <input autocomplete="off" type="text" id="fsreName" list="empList" name="eName" class="form-control" value="<?php if(isset($bills[0]['salesman'])){
                                                    echo $bills[0]['salesman'];
                                                } ?>">
                                                <datalist id="empList">
                                                    <?php
                                                    foreach($salesman as $data){
                                                        $name=$data['salesman'];
                                                        ?>   
                                                        <option value="<?php echo $name;?>"/>
                                                            <?php    
                                                        }
                                                        ?>
                                                    </datalist>
                                                </div> 
                                                <button id="fsrempAdd" onclick="allfsrempList();" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">add</i> 
                                                    <span class="icon-name">
                                                        Add to List
                                                    </span>
                                                </button> 

                                            </div>  
                                            <div class="col-md-4">
                                                <label>Selected Emp</label>
                                                <ul class="list-group" id="fsrlist" multiple="multiple"></ul>
                                            </div>
                                            <div class="col-md-2"></div>
                                        </div>
                                        <center>
                                            <button id="insert_usr" class="btn btn-primary m-t-15 waves-effect">
                                                <i class="material-icons">save</i> 
                                                <span class="icon-name">Save</span>
                                            </button> 
                                        </center>
                                    </div>
                                    <div id="res"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	<?php
	
	}

	public function DisapprovedFsr($id,$allocatedId){
		$data['billID']=$id;
		$data['allocationId']=$allocatedId;
		$data['bills']=$this->SrModel->loadBill('bills',$id);
		$data['emp']=$this->SrModel->getdata('employee');
		$this->load->view('Manager/DisapprovedFsrView',$data);
	}

	public function insertUSR(){
		$id=$this->input->post('id');
		$allocationId=$this->input->post('allocationId');
		$empName=$this->input->post('empName');

		$empId="";
		foreach($empName as $e){
			$eId=$this->SrModel->getEmployeeIdByName($e);
			$empId=$empId.''.$eId.',';
		}
		$empId=trim($empId,',');
		
		$data['billdetails']=$this->SrModel->loadAllBillData('billsdetails',$id);

		$route=$data['billdetails'][0]['routeName'];
		$billNO=$data['billdetails'][0]['billNo'];
		$retailerName=$data['billdetails'][0]['retailerName'];

		$billId=$data['billdetails'][0]['billId'];

		$prodName=$data['billdetails'][0]['productName'];
		$netAmt=$data['billdetails'][0]['netAmount'];
		$srQty=$data['billdetails'][0]['gkReturnQty'];
		$usrAmt=$data['billdetails'][0]['fsReturnAmt'];

		$data['bills']=$this->SrModel->load('bills',$billId);
		$billUsrAmt=$usrAmt+$data['bills'][0]['usr'];
		$billFsSrAmt=$data['bills'][0]['fsSrAmt']-$usrAmt;

		$provisionalData=array('billId'=>$billId,'date'=>date('Y-m-d H:i:sa'),'salesmanId' => $empId,'billDetailId' => $id,'usrAmt' => $usrAmt,'allocationId' => $allocationId);
		$this->SrModel->insert('provisionaldebit',$provisionalData);
		if($this->db->affected_rows()>0){
			$usrData=array('usr'=> $billUsrAmt,'fsSrAmt' => $billFsSrAmt);
			$this->SrModel->update('bills',$usrData,$billId);
			if($this->db->affected_rows()>0){
				$managerStatus=array('managerSrStatus'=> 3);
				$this->SrModel->update('billsdetails',$managerStatus,$id);
				if($this->db->affected_rows()>0){
					echo "success";
				}
			}
		}else{
			echo "fail";
		}
	}

	public function insertUfsr(){
		$id=$this->input->post('id');
		$allocationId=$this->input->post('allocationId');
		$empName=$this->input->post('empName');
		$empId="";
		foreach($empName as $e){
			$eId=$this->SrModel->getEmployeeIdByName($e);
			$empId=$empId.''.$eId.',';
		}
		$empId=trim($empId,',');

		$data['billdetails']=$this->SrModel->loadAllBillDetails('billsdetails',$id);

		foreach($data['billdetails'] as $d){
			$billUsrAmt=0;
			$billFsSrAmt=0;
			$managerStatus=0;

			$route=$d['routeName'];
			$billNO=$d['billNo'];
			$retailerName=$d['retailerName'];

			$billId=$d['billId'];
			$billDetailsId=$d['id'];
			
			$prodName=$d['productName'];
			$netAmt=$d['netAmount'];
			$srQty=$d['gkReturnQty'];
			$usrAmt=$d['fsReturnAmt'];

			$data['bills']=$this->SrModel->load('bills',$billId);

			$billUsrAmt=$usrAmt+$data['bills'][0]['usr'];
			$billFsSrAmt=$data['bills'][0]['fsSrAmt']-$usrAmt;

			$provisionalData=array('billId'=>$billId,'date'=>date('Y-m-d H:i:sa'),'salesmanId' => $empId,'billDetailId' => $billDetailsId,'usrAmt' => $usrAmt,'allocationId' => $allocationId);
			$this->SrModel->insert('provisionaldebit',$provisionalData);
			if($this->db->affected_rows()>0){
				$usrData=array('usr'=> $billUsrAmt,'fsSrAmt' => $billFsSrAmt);
				$this->SrModel->update('bills',$usrData,$billId);
				if($this->db->affected_rows()>0){
					$managerStatus=array('managerSrStatus'=> 3);
					$this->SrModel->update('billsdetails',$managerStatus,$billDetailsId);
					if($this->db->affected_rows()>0){
						echo "success";
					}else{
						echo "fail while updating status";
					}
				}else{
					echo "fail while updating bills";
				}
			}else{
				echo "fail  while inserting data";
			} 
		}
	}

	public function DebitConfirm($id){
		$data['allocationID']=$id;
		$data['allocation']=$this->SrModel->load('allocations',$id);
		$data['emp']=$this->SrModel->getdata('employee');
		
		$empId=array();
		if($data['allocation'][0]['fieldStaffCode1'] !=0){
			array_push($empId,$data['allocation'][0]['fieldStaffCode1']);		
		}
		if($data['allocation'][0]['fieldStaffCode2'] !=0){
			array_push($empId,$data['allocation'][0]['fieldStaffCode2']);	
		}
		if($data['allocation'][0]['fieldStaffCode3'] !=0){
			array_push($empId,$data['allocation'][0]['fieldStaffCode3']);				
		}
		if($data['allocation'][0]['fieldStaffCode4'] !=0){
			array_push($empId,$data['allocation'][0]['fieldStaffCode4']);				
		}

		$emp=array();
		for($i=0;$i<count($empId);$i++){
			$empName=$this->SrModel->load('employee',$empId[$i]);
			$name=$empName[0]['name'];
			array_push($emp,$name);
		}
		$cnt=count($empId);
		$data['grandTotal']=1000;
		$data['total']=(1000/$cnt);
		$data['allocatedEmp']=$emp;
		$this->load->view('Manager/DebitConfirmView',$data);
	}

}
?>