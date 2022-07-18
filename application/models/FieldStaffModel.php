<?php
class FieldStaffModel extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }
    
    public function getdata($tableName)
    {
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function updateHistory($tblName, $data, $billId,$allocationId,$type) {
        $this->db->where('billId', $billId);
        $this->db->where('allocationId', $allocationId);
        $this->db->where('transactionStatus', $type);
        return $this->db->update($tblName, $data);  
    }

    public function getDynamicNames($tblName,$type){
        $this->db->where('type', $type);
        $query = $this->db->get($tblName);
        
        return $query->result_array();  
    }

    public function getOtherAdjAllocatedBills($tableName,$id){
        $this->db->distinct();
        $this->db->select('billpayments.*,employee.name as ename,bills.pendingAmt as pendingAmt,bills.id as bid,bills.creditAdjustment as creditAdjustment,bills.billNo as billNo,bills.netAmount as netAmount,bills.retailerName as retailerName,bills.salesman as salesmanName,bills.fsbillStatus as FsBillStatus');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.allocationId=allocations.id');
        $this->db->join('bills','allocationsbills.billId=bills.id');
        $this->db->join('billpayments','billpayments.billId=bills.id');
        $this->db->join('employee','billpayments.empId=employee.id');
        $this->db->where('billpayments.allocationId',$id);
        $this->db->where('billpayments.paidAmount >',0);
        $this->db->where('billpayments.paymentMode','Other Adjustment');
        $this->db->where('billpayments.otherAdjustmentApproval',0);
        $this->db->group_by('bills.id');
        $query=$this->db->get();
        
        return $query->result_array();
    }

    public function checkHistory($tblName,$billId,$allocationId,$type){
        $this->db->where('billId', $billId);
        $this->db->where('allocationId', $allocationId);
        $this->db->where('transactionStatus', $type);
        $query = $this->db->get($tblName);
        
        return $query->result_array();  
    }

    public function getSumRowCount($id){
        $query = $this->db->query('SELECT sum(isResendBill) as recBill,sum(isLostBill) as lostBill,sum(isLostCheque) as lostCheque,sum(isPendingNeft)as lostNeft FROM `allocationsbills` WHERE allocationsbills.billId='.$id);
        
        return $query->result_array();
    }

    public function get_expenseLimit(){
        $this->db->select('expenseLimit');
        $this->db->from('expenses_limit');
        $row = $this->db->get()->row();
        if (isset($row)) {
            return $row->expenseLimit;
        } else {
            return false;
        }
    }

    public function getSignedBills($tableName,$id){
        $this->db->distinct();
        $this->db->select('bills.*');
        $this->db->from($tableName);
        $this->db->join('billsdetails','bills.id=billsdetails.billId','left outer');
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
        $this->db->where('bills.pendingAmt !=',0);
        $this->db->where('bills.fsbillStatus !=','Resend');
        $this->db->where('allocationsbills.allocationId',$id);
        $query=$this->db->get();
        
        return $query->result_array();
    }

    public function getRowCount($tableName,$id,$type){
        $query = $this->db->query('SELECT * FROM '.$tableName.' WHERE billId='.$id.' and '.$type.'=1');
        return $query->num_rows();   
    }

    public function checkBouncedBill($tblName,$id){
        $this->db->like('chequeStatus', 'Bounced');
        $this->db->where('billId', $id);
        $query = $this->db->get($tblName);
        return $query->num_rows();   
    }

    public function getAllocationBills($tblName,$allocationId){
        $this->db->select("bills.*");
        $this->db->join("bills","allocationsbills.billId = bills.id");
        $this->db->where('allocationsbills.allocationId',$allocationId);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }
   
    public function getAllocationDetails($tblName){
        $this->db->where('isAllocationComplete !=','1');
        $this->db->order_by('id','desc');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getSum($tableName,$id){
        $this->db->select_sum('qty', 'qtySum');
        $this->db->where('billId',$id);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }
    
    public function getdataEmployee($tableName)
    {
        $this->db->where('ownerApproval',0);
        $this->db->where('designation','deliveryman');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getEmployeeNamesByID($id){
        $this->db->select('name'); 
        $this->db->from('employee');
        $this->db->where('id',$id);   
        return $this->db->get()->row()->name;
    }



    public function loadBillDetails($tableName, $id)
    {
        $this->db->select("billsdetails.*,bills.billNo as billNo,bills.date as Date,bills.retailerName as name,bills.netAmount as netAmt,bills.pendingAmt as pendingAmt,bills.fsSrAmt as fsSrAmt,bills.creditNoteRenewal as creditNoteRenewal");
        $this->db->join("bills","billsdetails.billId = bills.id");
        $this ->db->where('billsdetails.billId', $id);
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

    public function loadBillsDetails($tableName, $id)
    {
        $this->db->select("bills.*,bills.retailerName as name,employee.name as empname");
        $this->db->join("allocationsbills","allocationsbills.billId=bills.id");
        $this->db->join("allocations","allocations.id=allocationsbills.allocationId");
        $this->db->join("employee","employee.id=allocations.fieldStaffCode1");
        $this ->db ->where('bills.id', $id);
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

     public function loadBillDetailsID($tableName, $id)
    {
        $this->db->select("billsdetails.*,bills.billNo as billNo,bills.date as Date,bills.retailerName as name");
        $this->db->join("bills","billsdetails.billId = bills.id");
        $this ->db->where('billsdetails.id', $id);
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

    public function updateSRPamt($tableName,$amt,$id){
        $this->db->set('SRAmt','SRAmt+'.$amt,false);
        $this->db->set('pendingAmt','pendingAmt-'.$amt,false);
        $this->db->where('id',$id);
        return $this->db->update($tableName);
    }

    public function updateAllocationStatus($tblName, $data, $allocationId,$billId) {
        $this->db->where('billId', $billId);
        $this->db->where('allocationId', $allocationId);
        return $this->db->update($tblName, $data);  
    }

    
    public function updateAvailQty($tableName,$qty,$name){
        $this->db->set('availQty','availQty+'.$qty,false);
        $this->db->where('name',$name);
        return $this->db->update($tableName);
    }

    public function insert($tblName, $data) {      
        
        $this->db->insert($tblName, $data);
        return $this->db->insert_id();
    }
    public function update($tblName, $data, $id) {
        $this->db->where('id', $id);
        return $this->db->update($tblName, $data);  
    }

    public function updateFinelizeRecordByManager($tblName,$data,$id){
        $this->db->where('allocationId', $id);
        return $this->db->update($tblName, $data);  
    }

    public function updateNotesDetails($tblName,$data,$id){
        $this->db->where('allocationId', $id);
        return $this->db->update($tblName, $data);  
    }

    public function updateSrDetail($tblName,$data,$allocationId,$billId,$billItemId){
        $this->db->where('allocationId', $allocationId);
        $this->db->where('billId', $billId);
        $this->db->where('billItemId', $billItemId);
        return $this->db->update($tblName, $data);  
    }

    public function show($tblName) {
        $query = $this->db->get($tblName);
        
        return $query->result_array();    
    }

    public function noteDetailsByAllocationId($tblName,$id) {
        $this->db->where('allocationId',$id);
        $query = $this->db->get($tblName);
        
        return $query->result_array();    
    }

     public function delete($tblName,$id)
     {
        $this->db->where('id',$id);
        return $this->db->delete($tblName,array('id'=>$id));
    }

    public function deleteTransactionRecord($tblName,$billId,$allocationId){
        $this->db->where('billId',$billId);
        $this->db->where('allocationId',$allocationId);
        return $this->db->delete($tblName);
    }
    public function load($tblName, $id) {
        $this -> db -> where('id', $id);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function loadSrDetails($tblName, $billId,$allocationId,$billItemId){
        $this->db->where('billId', $billId);
        $this->db->where('allocationId', $allocationId);
        $this->db->where('billItemId', $billItemId);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function loadAllocatedBills($tblName, $id) {
        $this -> db -> where('allocationId', $id);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function loadDataByChequeNo($tblName, $id) {
        $this ->db-> where('chequeNo', $id);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function search($name){
        $this->db->like('billNo', $name, 'both');
        return $this->db->get('bills')->result();
    }
     
    public function loadCurrentBills($tblName, $fromNo ,$toNo) {
        $this->db->where('billNo BETWEEN "'. $fromNo. '" and "'.$toNo.'"');
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function loadCurrentBillsByNo($tblName, $billNo) {
        $this->db->where('billNo',$billNo);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function loadPastBills($tblName, $billNo) {
        $this->db->where('billNo',$billNo);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function loadPastBillsByRoute($tblName, $routeName) {
        $this->db->where('route',$routeName);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

     public function getNextId($tableName) {
        $this->db->select_max('id');
        $query = $this->db->get($tableName);
        
        return $query->result_array();
    }
    
    public function bouncedReturnCheques($tblName){
        $this->db->select('chequeNo'); 
        $this->db->from($tblName);
        $this->db->where('chequeStatus','Bounced&Returned'); 
          
        return $this->db->get()->result_array();
    }

    public function deliverySlipBillNo(){
        $this->db->select('bills.billNo,bills.retailerName'); 
        $this->db->from('bills');  
        $this->db->where('billType','deliveryslip');
        return $this->db->get()->result_array();
    }

    public function getRouteNames() {
        $this->db->select('name'); 
        $this->db->from('route');   
        return $this->db->get()->result_array();
    }

    public function getEmployeeNames(){
        $this->db->select('name'); 
        $this->db->from('employee');  
        $this->db->where('ownerApproval',0); 
        return $this->db->get()->result_array();
    }

    public function getRetailerName($code){
        $this->db->select('name'); 
        $this->db->from('retailer');
        $this->db->where('code',$code);   
        return $this->db->get()->result_array();
    }
    public function getRetailerNameById($code){
        $this->db->select('name'); 
        $this->db->from('retailer');
        $this->db->where('id',$code);   
        return $this->db->get()->result_array();
    }

    public function getRouteName($code){
        $this->db->select('name'); 
        $this->db->from('route');
        $this->db->where('code',$code);   
        return $this->db->get()->result_array();
    }

     public function getRouteNameById($id){
        $this->db->select('name'); 
        $this->db->from('route');
        $this->db->where('id',$id);   
        return $this->db->get()->result_array();
    }

    public function getBillNos(){
        $this->db->select('bills.billNo,bills.retailerName'); 
        $this->db->from('bills');  
        $this->db->where('billType !=','allocatedbill');
        $this->db->or_where('billType !=','deliveryslip');  
        $this->db->where('deliveryStatus !=','Cancelled');  
        return $this->db->get()->result_array();
    }

    public function GetRowBill($tblName,$keyword) {        
        $this->db->order_by('id', 'DESC');
        $this->db->like("billNo", $keyword);
        return $this->db->get($tblName)->result_array();
    }
    public function GetCheckNo($tblName,$keyword) {        
        $this->db->order_by('id', 'DESC');
        $this->db->like("chequeNo", $keyword);
        return $this->db->get($tblName)->result_array();
    }
    public function getIdByBill($tableName,$billNo)
    {
        $this->db->where('billNo',$billNo);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getRouteCodeByName($tableName,$name)
    {
        $this->db->where('name',$name);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getEmpId($tableName,$name)
    {
        $this->db->where('name',$name);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getEmpName($tableName,$id)
    {
        $this->db->where('id',$id);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }
   
    public function getPastBilldata($tableName,$date,$bill)
    {
        if(empty($bill)){
            $this->db->where('date <', $date);
            $this->db->where('pendingAmt >', 0);
            $query=$this->db->get($tableName);
            
            return $query->result_array();
        }else{
            $this->db->where('date <', $date);
            $this->db->where('pendingAmt >', 0);
            $this->db->where('billNo', $bill);
            $query=$this->db->get($tableName);
            
            return $query->result_array();
        }
    }

    public function getBouncedBilldata($tableName,$date,$bill)
    {
        if(empty($bill)){
            $this->db->where('date <', $date);
            $this->db->where('balanceAmount >', 0);
            $query=$this->db->get($tableName);
            
            return $query->result_array();
        }else{
            $this->db->where('date <', $date);
            $this->db->where('balanceAmount >', 0);
            $this->db->where('BillNo', $bill);
            $query=$this->db->get($tableName);
            
            return $query->result_array();
        }
    }

     public function getPastBills()
    {
        $this->db->select('bills.billNo,bills.retailerName'); 
        $this->db->from('bills');
        $this->db->where('pendingAmt >', 0);      
        $query=$this->db->get();
        
        return $query->result_array();
    }

       
    public function getAllocatedBills($tableName,$id){
        $this->db->select('bills.*');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
        $this->db->where('allocationsbills.allocationId',$id);
        $query=$this->db->get();
        
        return $query->result_array();
    }

       public function getAllocatedBillsByType($tableName,$id,$status){
            $this->db->select('bills.*');
            $this->db->from($tableName);
            $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
            $this->db->where('allocationsbills.allocationId',$id);
             $this->db->where('allocationsbills.billStatus',$status);
            $query=$this->db->get();
            
            return $query->result_array();
       }

       public function getAllocatedPastBillsByType($tableName,$id){
        $this->db->select('bills.*');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
        $this->db->group_start();
        $this->db->where('bills.billType','officeAdjustmentBill');
        $this->db->or_where('bills.billType','allocatedbillPass');
        $this->db->or_where('bills.billType','adHocDeliveryBill');
        $this->db->group_end();
        $this->db->where('allocationsbills.allocationId',$id);
        $this->db->where('allocationsbills.billStatus','2');
        $query=$this->db->get();
        
        return $query->result_array();
    }

       public function getBillPaymentDetailsById($tableName,$billId,$allocationId,$mode){
            $this->db->where('allocationId',$allocationId);
            $this->db->where('billId',$billId);
            $this->db->where('paymentMode',$mode);
            $query=$this->db->get($tableName);
            
            return $query->result_array();        
       }

}
?>