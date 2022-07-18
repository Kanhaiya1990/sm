<?php
class BillTransactionModel extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }
    public function getdata($tableName)
    {
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function lostChequeWithComp($tblName,$date){
        $qry="SELECT distinct bills.* from billpayments join bills on bills.id=billpayments.billId where (billpayments.id in (SELECT MAX(billpayments.id) from billpayments where billpayments.paymentMode='Cheque' and billpayments.isLostStatus=1 and billpayments.paidAmount >0 GROUP by billpayments.billId)) and bills.pendingAmt >0 and bills.date <='$date' GROUP by bills.id ORDER by billpayments.id desc";
        $query=$this->db->query($qry);
        
        return $query->result_array();
    }

    public function getdataActive($tableName)
    {
        $this->db->select("employee.*,company.name as companyName");
        $this->db->join("company","employee.companyId = company.id","left outer"); 
        // $this->db->where('status', 1);
        $this->db->where('isDeleted', 0);
        $resultset=$this->db->get($tableName);
         
        return $resultset->result_array();
    }

    public function lostNeftWithComp($tblName,$date){
        $qry="SELECT distinct bills.* from billpayments join bills on bills.id=billpayments.billId where (billpayments.id in (SELECT MAX(billpayments.id) from billpayments where billpayments.paymentMode='NEFT' and billpayments.isLostStatus=1 and billpayments.paidAmount >0 GROUP by billpayments.billId)) and bills.pendingAmt >0 and bills.date <='$date' GROUP by bills.id ORDER by billpayments.id desc";
        $query=$this->db->query($qry);
        
        return $query->result_array();
    }

    public function loadResendBillsWithComp($tableName,$date){
        $this->db->distinct();
        $this->db->select('bills.*');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
        $this->db->where('allocationsbills.isResendBill','1');
         $this->db->where('bills.isBilled','0');
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('bills.date <=',$date);
        $this->db->group_by('bills.id');
        $query=$this->db->get();
        
        return $query->result_array();
   }

    public function loadLostBillsWithComp($tableName,$date){
        $this->db->distinct();
        $this->db->select('bills.*');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
        $this->db->where('allocationsbills.isLostBill',1);
        $this->db->where('bills.pendingAmt >',0);
        // $this->db->where('bills.compName',$company);
        $this->db->where('bills.date <=',$date);
        $this->db->group_by('bills.id');
        $query=$this->db->get();
        
        return $query->result_array();
   }

    public function getPendingAllocations($tblName,$customeDate) {
        $this->db->where('isAllocationComplete',0);
        $this->db->where('DATE(date) <=',$customeDate);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function getPendingOfficeAllocations($tblName,$customeDate) {
        $this->db->where('isAllocationComplete',0);
        $this->db->where('DATE(createdAt) <=',$customeDate);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function getPendingAllocationsAmount($tblName,$customeDate) {
        $this->db->select('pendingAmt');
        $this->db->join('allocations_officebills','allocations_officebills.allocationId=allocations_officeadjustment.id');
        $this->db->join('bills','allocations_officebills.billId=bills.id');
        $this->db->where('isAllocationComplete',0);
        $this->db->where('DATE(allocations_officeadjustment.createdAt) <=',$customeDate);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }
    
   
    public function getAllBills($tblName,$company,$customDate) {
        $this->db->where('pendingAmt >=',0);
        $this->db->where('deliveryStatus !=','cancelled');
        $this->db->where('compName',$company);
        $this->db->where('date <=',$customDate);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }
    
    public function getUnAllBills($tblName,$company,$customDate) {
        $this->db->where('pendingAmt >',0);
        $this->db->where('deliveryStatus !=','cancelled');
        $this->db->where('compName',$company);
        $this->db->where('date >=',$customDate);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function getAllPendingBills($tblName,$company,$customDate) {
        $this->db->where('pendingAmt >',0);
        $this->db->where('deliveryStatus !=','cancelled');
        $this->db->where('compName',$company);
        $this->db->where('date >=',$customDate);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function getAllBillsByDate($tblName,$company,$from,$to) {
        // $this->db->where('pendingAmt >',0);
        // $this->db->where('isDeliverySlipBill',0);
        $this->db->where('deliveryStatus !=','cancelled');
        $this->db->where('compName',$company);
        $this->db->where('date >=',$from);
        $this->db->where('date <=',$to);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function getSrAllBillsByDate($tblName,$company,$from,$to) {
        $this->db->select('allocation_sr_details.*,billsdetails.netAmount as billItemNetAmount,billsdetails.qty as BillItemQuantity,bills.isDeliverySlipBill');
        $this->db->join('billsdetails','billsdetails.id=allocation_sr_details.billItemId');
        $this->db->join('bills','bills.id=billsdetails.billId');
        $this->db->where('bills.compName',$company);
        // $this->db->where('bills.isDeliverySlipBill',0);
        $this->db->where('DATE(createdAt) >=',$from);
        $this->db->where('DATE(createdAt) <=',$to);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function getAllBillDetails($tblName,$company,$status) {
        // $this->db->where('paidAmount >',0);
        // $this->db->where('chequeStatus',$status);
        // $this->db->where('compName',$company);
        // $query = $this->db->get($tblName);
        // return $query->result_array(); 

        $this->db->select("billpayments.*,sum(billpayments.paidAmount) as chAmount,bills.retailerName as Name,bills.billNo as Bno");
        $this->db->join("bills","bills.id = billpayments.billId");
        $this->db->where('billpayments.chequeStatus', $status);
        $this->db->where('billpayments.paymentMode', "Cheque");
        // $this->db->where('DATE(billpayments.date) >=', $date);
        $this->db->like('billpayments.compName', $company, 'both');
        $this->db->order_by('chAmount','asc');
        // $this->db->group_by('billpayments.chequeDate,billpayments.chequeNo,billpayments.chequeBank');
        $resultset=$this->db->get($tblName);
        
        return $resultset->result_array();  
    }
    
    public function getStatusAllBillDetails($tblName,$company) {
        $this->db->select("billpayments.*,sum(billpayments.paidAmount) as chAmount,bills.retailerName as Name,bills.billNo as Bno");
        $this->db->join("bills","bills.id = billpayments.billId");
        $this->db->group_start();
        $this->db->where('billpayments.chequeStatus', '');
        $this->db->or_where('billpayments.chequeStatus', 'New');
        $this->db->group_end();
        $this->db->where('billpayments.paymentMode', "Cheque");
        $this->db->where('billpayments.isLostStatus', "2");
        $this->db->where('bills.compName', $company);
        $this->db->where('bills.isAllocated', 0);
        // $this->db->where('DATE(billpayments.date) >=', $date);
        $this->db->order_by('chAmount','asc');
        // $this->db->group_by('billpayments.chequeDate,billpayments.chequeNo,billpayments.chequeBank');
        $resultset=$this->db->get($tblName);
        
        return $resultset->result_array();  
    }

    public function loadRetailer($id) {
        $sql="select * from retailer where code='".$id."'";
        $query = $this->db->query($sql);
        
        return $query->result_array();   
    } 

    public function findBills($tableName,$billNo){
        $this->db->distinct();
        $this->db->select('bills.*');
        $this->db->from($tableName);
        $this->db->like('bills.billNo',$billNo);
        $query=$this->db->get();
        
        return $query->result_array();
    }

    public function getSumByType($tableName,$id,$type){
        $sql="SELECT sum(paidAmount) as amt FROM `billpayments` WHERE billId=".$id." and paymentMode='".$type."' and isLostStatus !=1";    
        $query = $this->db->query($sql);
        
        return $query->result_array();
    }

    public function getSumByOfficeType($tableName,$id,$type){
        $sql="SELECT sum(amount) as amt FROM `allocations_officebills` WHERE billId=".$id." and transactionType='".$type."'";    
        $query = $this->db->query($sql);
        
        return $query->result_array();
    }

    public function getBills($tableName)
    {
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function checkNeftStatus($tableName,$billNo)
    {
        // $this->db->group_start();
        // $this->db->where('chequeStatus','New');
        // $this->db->or_where('chequeStatus','');
        // $this->db->group_end();
        $this->db->where('paidAmount >',0);
        $this->db->where('billId',$billNo);
        $this->db->where('paymentMode','NEFT');
        $this->db->order_by('id,date','desc');
        $this->db->limit(1);  
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }


    public function checkChequeStatus($tableName,$billNo)
    {
        // $this->db->group_start();
        // $this->db->where('chequeStatus','Banked');
        // $this->db->or_where('chequeStatus','New');
        // $this->db->or_where('chequeStatus','');
        // $this->db->group_end();
        $this->db->where('paidAmount >',0);
        $this->db->where('billId',$billNo);
        $this->db->where('paymentMode','Cheque');
        $this->db->group_start();
        $this->db->where('chequeStatus !=','Bounced');
        $this->db->where('chequeStatus !=','Bounced&Returned');
        $this->db->group_end();
        $this->db->order_by('id','desc');
        $this->db->limit(1);  
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }
    

    public function getCompanySerialNo($tblName){
        $this->db->select('bill_serial_manage.*,company.name as compName,company.id as cId');
        $this->db->join('company','company.id=bill_serial_manage.companyId','right outer');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }


    public function getBillDetails($tableName,$billNo)
    {
        $this->db->where('billNo',$billNo);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getBillTransaction($tableName,$billId)
    {
        $this->db->where('billId',$billId);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }
     
    public function insert($tblName, $data) {      
        
        $this->db->insert($tblName, $data);
        return $this->db->insert_id();
    }
    public function update($tblName, $data, $id) {
        $this->db->where('id', $id);
        return $this->db->update($tblName, $data);  
    }

    public function show($tblName) {
        $query = $this->db->get($tblName);
        
        return $query->result_array();    
    }
    
    public function delete($tblName,$id)
    {
        $this->db->where('id',$id);
        return $this->db->delete($tblName,array('id'=>$id));
    }
    public function load($tblName, $id) {
        $this->db->where('id', $id);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

}
?>