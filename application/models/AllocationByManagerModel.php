<?php
class AllocationByManagerModel extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function allRetailerBillsByCode($tableName,$code){
        $this->db->distinct();
        $this->db->select('bills.*');
        $this->db->from($tableName);
        $this->db->where('retailerName', $code);
        $this->db->order_by('id','desc');
        $query=$this->db->get();
        
        return $query->result_array();
    }
    
    public function getRetailerBillsByCode($tableName,$code){
        // $this->db->distinct();
        $this->db->select('bills.*');
        $this->db->where('bills.retailerCode', $code);
        $this->db->order_by('bills.id','desc');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getDynamicNames($tblName,$type){
        $this->db->where('type', $type);
        $query = $this->db->get($tblName);
        
        return $query->result_array();  
    }

    public function retailerBillsByCode($tblName,$code) {
        $this->db->where('pendingAmt >',0);
        $this->db->where('deliveryStatus !=','cancelled');
        $this->db->where('retailerCode',$code);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function retailerOutstandingBills($tblName) {
        $this->db->select('retailerName,retailerCode,salesman,retailerId,count(billNo) as billCount');
        $this->db->select_sum('pendingAmt', 'pendingAmt');
        $this->db->where('deliveryStatus !=','cancelled'); 
        $this->db->where('pendingAmt >',0);
        $this->db->order_by('pendingAmt', 'desc');
        $this->db->group_by('retailerName,retailerCode');
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function paginationRetailerBills($tableName,$limit, $start, $st = "", $orderField, $orderDirection,$code,$fromDate,$toDate){
        $this->db->distinct();
        $this->db->select('bills.*');
        $this->db->from($tableName);
        $this->db->group_start();
        $this->db->like('billNo', $st);
        $this->db->or_like('date', $st);
        $this->db->or_like('retailerName', $st);
        $this->db->or_like('netAmount', $st);
        $this->db->or_like('compName', $st);
        $this->db->or_like('pendingAmt', $st);
        $this->db->group_end();
        $this->db->where('retailerCode', $code);
        $this->db->where('date >=', $fromDate);
        $this->db->where('date <=', $toDate);
        $this->db->order_by('id','desc');
        $this->db->order_by($orderField, $orderDirection);
        $this->db->limit($limit, $start);
        $query=$this->db->get();
        
        return $query->result_array();
    }

    public function countRetailerBills($tableName,$limit, $start, $st = "", $orderField, $orderDirection,$code,$fromDate,$toDate){
        $this->db->distinct();
        $this->db->select('bills.*');
        $this->db->from($tableName);
        $this->db->group_start();
        $this->db->like('billNo', $st);
        $this->db->or_like('date', $st);
        $this->db->or_like('retailerName', $st);
        $this->db->or_like('netAmount', $st);
        $this->db->or_like('compName', $st);
        $this->db->or_like('pendingAmt', $st);
        $this->db->group_end();
        $this->db->where('retailerCode', $code);
        $this->db->where('date >=', $fromDate);
        $this->db->where('date <=', $toDate);
        $this->db->order_by('id','desc');
        $this->db->order_by($orderField, $orderDirection);
        $query=$this->db->get();
        
        return $query->num_rows();
    }

    public function getJournalDetails($tableName,$code){
        $this->db->select("billpayments.*,bills.billNo as billNo,bills.retailerName as retailerName,journal_entry.journalEntryCode,journal_entry.journalEntryDate,employee.name as empName,allocations.allocationCode");
        $this->db->join("billpayments","billpayments.journalId = journal_entry.journalEntryCode"); 
        $this->db->join("allocations","allocations.id = billpayments.allocationId","left outer"); 
        $this->db->join("bills","billpayments.billId = bills.id","left outer"); 
        $this->db->join("employee","billpayments.empId = employee.id","left outer"); 
        $this->db->where('billpayments.journalId',$code);
         // $this->db->where('employee.isSalaryEmp', 1);
        $resultset=$this->db->get($tableName); 
        
        return $resultset->result_array();
    }

    public function getJournalDetailsForEmp($tableName,$code){
        $this->db->select("emptransactions.*,journal_entry.journalEntryCode,journal_entry.journalEntryDate,employee.name as empName");
        $this->db->join("emptransactions","emptransactions.journalId = journal_entry.journalEntryCode"); 
        $this->db->join("employee","emptransactions.empId = employee.id","left outer"); 
        $this->db->where('emptransactions.journalId',$code);
         // $this->db->where('employee.isSalaryEmp', 1);
        $resultset=$this->db->get($tableName); 
        
        return $resultset->result_array();
    }
    
    public function getEmpLedgerByEmp($tableName,$empId)
    {
        $this->db->select("emptransactions.*,employee.id as empId,employee.name as empName,bills.billNo as billNo");
        $this->db->join("employee","emptransactions.empId = employee.id","left outer"); 
        $this->db->join("bills","emptransactions.billId = bills.id","left outer"); 
        $this->db->where('emptransactions.empId',$empId);
        $this->db->where('emptransactions.ownerApprovalStatus !=',2);
         // $this->db->where('employee.isSalaryEmp', 1);
        $resultset=$this->db->get($tableName); 
        
        return $resultset->result_array();
    }

    public function findBills($tableName,$billNo){
        $this->db->distinct();
        $this->db->select('bills.*');
        $this->db->from($tableName);
        $this->db->like('bills.billNo',$billNo);
        $query=$this->db->get();
        
        return $query->result_array();
    }

    public function getSumRowCount($id){
        $query = $this->db->query('SELECT sum(isResendBill) as recBill,sum(isLostBill) as lostBill,sum(isLostCheque) as lostCheque,sum(isPendingNeft)as lostNeft FROM `allocationsbills` WHERE allocationsbills.billId='.$id);
        
        return $query->result_array();
    }

     public function lastRecordDayBookValue(){
        $date=date('Y-m-d');
        $query = $this->db->query("SELECT * FROM expences ORDER BY id desc LIMIT 1");
        
        return $query->row_array();
    }

    public function paginationOutstandingBills($tableName,$limit, $start, $st = "", $orderField, $orderDirection){
        $this->db->distinct();
        $this->db->select('allocations.*');
        $this->db->from($tableName);
        $this->db->group_start();
        $this->db->like('id', $st);
        $this->db->or_like('date', $st);
        $this->db->or_like('allocationCode', $st);
        $this->db->or_like('company', $st);
        $this->db->or_like('allocationEmployeeName', $st);
        $this->db->or_like('allocationSalesman', $st);
        $this->db->group_end();
        $this->db->where('allocations.isAllocationComplete',1);
        $this->db->order_by('allocations.id', 'desc');
        $this->db->limit($limit, $start);
        $query=$this->db->get();
        
        return $query->result_array();
    }

    public function paginationJournalBills($tableName,$limit, $start, $st = "", $orderField, $orderDirection){
        $this->db->distinct();
        $this->db->select('journal_entry.*');
        
        $this->db->from($tableName);
        $this->db->group_start();
        $this->db->like('journalEntryCode', $st);
        $this->db->or_like('journalEntryDate', $st);
        $this->db->group_end();
        $this->db->order_by($orderField, $orderDirection);
        $this->db->limit($limit, $start);
        $query=$this->db->get();
        
        return $query->result_array();
    }

    public function countPaginationJournalBills($tableName,$limit, $start, $st = "", $orderField, $orderDirection){
        $this->db->distinct();
        $this->db->select('journal_entry.*');
        $this->db->from($tableName);
        $this->db->group_start();
        $this->db->like('journalEntryCode', $st);
        $this->db->or_like('journalEntryDate', $st);
        $this->db->group_end();
        $this->db->order_by($orderField, $orderDirection);
        $query=$this->db->get();
        
        return $query->num_rows();
    }

    public function getDataByName($tableName,$name)
    {
        $this->db->where('name',$name);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getDataByNameCode($tableName,$name,$code)
    {
        $this->db->where('name',$name);
        $this->db->where('code',$code);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getEmptySalesmanRoutes($tblName) {
        $this->db->where('pendingAmt >',0);
        $this->db->where('isDeliverySlipBill',0);
        $this->db->group_start();
        $this->db->where('salesman','');
        $this->db->or_where('routeName','');
        $this->db->or_where('routeName','NO ROUTE');
        $this->db->group_end();
        $query = $this->db->get($tblName);
        
        return $query->result_array(); 
    }

    public function getBillByLastRecords($tblName, $id) {
        $this->db->select('id,billNo');
        $this->db->where('billNo', $id);
        $query = $this->db->get($tblName);
        
        return $query->result_array(); 
        // return $query->row()->id;   
    }
    
    public function getdata($tableName)
    {
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getAllocationBillNetAmt($tableName,$billId,$allocationId){
        $this->db->where('billId',$billId);
        $this->db->where('allocationId <=',$allocationId);
        $this->db->where('allocationId !=',0);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getProcessBillNetAmt($tableName,$billId,$date){
        $this->db->where('billId',$billId);
        $this->db->where('allocationId',0);
        $this->db->where('DATE(date) <',$date);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }
  
    public function getBillPaymentRemarks($tableName,$billId,$allocationId){
        $this->db->where('billId',$billId);
        $this->db->where('allocationId',$allocationId);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getBillsData($tableName)
    {
        $this->db->where('bills.deliveryStatus !=',"cancelled");
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getDeliveryBillsByDate($tableName,$fromDate,$toDate)
    {
        $this->db->where('bills.date >=',$fromDate);
        $this->db->where('bills.date <=',$toDate);
        $this->db->where('bills.isDeliverySlipBill',1);
        $query=$this->db->get($tableName);
        return $query->result_array();
    }

    public function findChequeWithBank($tableName,$no,$date,$bank)
    {
        $this->db->where('billpayments.chequeNo', $no);
        $this->db->where('DATE(billpayments.chequeDate)', $date);
        $this->db->where('billpayments.chequeBank', $bank);
        $this->db->where('billpayments.paymentMode', "Cheque");
        $this->db->where('billpayments.chequeStatus !=', "Bounced");
        $this->db->order_by('billpayments.id','desc');
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

    public function findNeft($tableName,$no,$date)
    {
        $this->db->where('billpayments.neftNo', $no);
        $this->db->where('DATE(billpayments.neftDate)', $date);
        $this->db->where('billpayments.paymentMode', "NEFT");
        $this->db->order_by('billpayments.id','desc');
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

    public function findChequeNo($tableName,$no,$date,$amount)
    {
        $this->db->where('billpayments.chequeNo', $no);
        $this->db->where('DATE(billpayments.chequeDate)', $date);
        $this->db->where('billpayments.paidAmount', $amount);
        
        // $this->db->where('billpayments.chequeStatus !=', 'Bounced');
        // $this->db->or_where('billpayments.chequeStatus !=', 'Bounced&Returned');
        $this->db->where('billpayments.paymentMode', "Cheque");
        $this->db->order_by('billpayments.id','desc');
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

    public function findNeftNo($tableName,$no,$date,$amount)
    {
        $this->db->where('billpayments.neftNo', $no);
        $this->db->where('DATE(billpayments.neftDate)', $date);
        $this->db->where('billpayments.paidAmount', $amount);
        
        // $this->db->where('billpayments.chequeStatus !=', 'Bounced');
        // $this->db->or_where('billpayments.chequeStatus !=', 'Bounced&Returned');
        $this->db->where('billpayments.paymentMode', "NEFT");
        $this->db->order_by('billpayments.id','desc');
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

    public function getCancelledBills($tableName)
    {
        $this->db->where('deliveryStatus', 'cancelled');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getTempCancelledBills($tableName)
    {
        $this->db->where('isTempCancelled', '1');
        $this->db->or_where('deliveryStatus', '');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function loadDeliveryDates($tblName,$id){
        $this->db->where('billId', $id);
        $this->db->where('isDirectDeliveryBill',1);
        $this->db->order_by('id','desc');
        $query = $this->db->get($tblName);
        
        return $query->result_array(); 
    }

     public function getRemarksById($tblName,$id){
        $this->db->where('billId', $id);
        $query = $this->db->get($tblName);
        
        return $query->result_array();  
    }

    public function getDetailByCode($tblName,$code){
        $this->db->where('productCode', $code);
        $query = $this->db->get($tblName);
        
        return $query->result_array();  
    }

    public function getDetailByCodeWithDetails($tblName,$name,$code,$mrp){
        $this->db->where('name', $name);
        $this->db->where('productCode', $code);
        $this->db->where('mrp', $mrp);
        $query = $this->db->get($tblName);
        
        return $query->result_array();  
    }

    public function getCdRemark($tblName,$amt,$id){
        $this->db->select('bill_remark_history.*');
        $this->db->where('amount', $amt);
        $this->db->where('billId', $id);
        $query = $this->db->get($tblName);
        
        return $query->result_array();  
    }

    public function lastRecordValue(){
        $date=date('Y-m-d');
        $query = $this->db->query("SELECT * FROM expences ORDER BY id DESC LIMIT 1");
        
        return $query->row_array();
    }

     public function loadChkBillPaymentDetails($tblName, $id, $alId,$mode) {
        $this->db->where('billId', $id);
        $this->db->where('allocationId', $alId);
        $this->db->where('paymentMode', $mode);
        $query = $this->db->get($tblName);
        
        return $query->result_array();  
    }



    public function getNotAllocatedAdHocBillsByType($tblName){
        // $this->db->where('pendingAmt >',0);
        $this->db->where('manuallyAddedBill',1);
        $this->db->where('deliveryStatus','');
        $this->db->where('routeName','');
        $query = $this->db->get($tblName);
        
       return $query->result_array();  
    }

    public function checkBouncedBill($tblName,$id){
        $this->db->like('chequeStatus', 'Bounced');
        $this->db->where('billId', $id);
        $query = $this->db->get($tblName);
        
        return $query->num_rows();   
    }

    public function getBillpaymentHistory($tblName,$id){
        $this->db->select('billpayments.*, employee.name as empName');
        $this->db->join('employee','employee.id=billpayments.empId');
        $this->db->where('billId', $id);
        $this->db->limit(1);
        $this->db->order_by('id','desc');
        $query = $this->db->get($tblName);
        
        return $query->result_array();  
    }

    public function getSrBillpaymentHistory($tblName,$id){
        $this->db->select('allocation_sr_details.*, employee.name as empName');
        $this->db->join('employee','employee.id=allocation_sr_details.empId');
        $this->db->where('billId', $id);
        $this->db->limit(1);
        $this->db->order_by('id','desc');
        $query = $this->db->get($tblName);
        
        return $query->result_array();  
    }

    

    public function updateHistory($tblName, $data, $billId,$allocationId,$type) {
        $this->db->where('billId', $billId);
        $this->db->where('allocationId', $allocationId);
        $this->db->where('transactionStatus', $type);
        
        return $this->db->update($tblName, $data);  
    }

    public function checkHistory($tblName,$billId,$allocationId){
        $this->db->where('billId', $billId);
        $this->db->where('allocationId', $allocationId);
        $query = $this->db->get($tblName);
        
        return $query->result_array();  
    }

    public function checkBillByRoute($tblName,$name){
        $this->db->where('routeName', $name);
        $query = $this->db->get($tblName);
        
        return $query->num_rows();   
    }

    public function loadByAllocationId($tblName, $id) {
        $this->db->where('allocationId', $id);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function loadRetailer($id) {
        $sql="select * from retailer where code='".$id."'";
        $query = $this->db->query($sql);
        
        return $query->result_array();   
    }

    public function loadEmpIdByAllocationId($tblName, $id) {
        $this->db->where('allocationId', $id);
        $this->db->where('empId', $id);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }
    public function getBillsByRetailerCode($tblName,$code,$company,$fromDate,$toDate){
        $this->db->where('retailerCode', $code);
        $this->db->where('date >=', $fromDate);
        $this->db->where('date <=', $toDate);
        $this->db->order_by('id','desc');
        $query = $this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getBillsByRetailer($tblName,$retailerName,$routeName,$company,$fromDate,$toDate){
        $this->db->where('retailerName', $retailerName);
        $this->db->where('routeName', $routeName);
        $this->db->where('compName', $company);
        $this->db->where('date >=', $fromDate);
        $this->db->where('date <=', $toDate);
        $query = $this->db->get($tblName);
        
        return $query->result_array();
    }

     public function getEmployeeBills($tblName,$empId){
        $this->db->select('bills.*,employee.name as ename');
        $this->db->join('bills','bills.id=billpayments.billId');
        $this->db->where('billpayments.empId', $empId);
        $query = $this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getRetailerDetails($tblName){
        $this->db->distinct();
        $this->db->select('retailerName, routeName,retailerCode,compName');
        $query = $this->db->get($tblName);
        
        return $query->result_array();  
    }

    public function getResendBill($tblName, $id) {
        $this->db->select('allocationsbills.*,bills.netAmount as netAmount,bills.pendingAmt as pendingAmount, allocations.allocationCode as alCode,DATE(allocations.date) as alDate,e1.name as ename1,e2.name as ename2,e3.name as ename3,e4.name as ename4');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id');
        $this->db->join('bills','bills.id=allocationsbills.billId');
        $this->db->join('employee e1','allocations.fieldStaffCode1=e1.id','left outer');
        $this->db->join('employee e2','allocations.fieldStaffCode2=e2.id','left outer');
        $this->db->join('employee e3','allocations.fieldStaffCode3=e3.id','left outer');
        $this->db->join('employee e4','allocations.fieldStaffCode4=e4.id','left outer');
        $this->db->where('allocationsbills.billId', $id);
        $this->db->order_by('allocationsbills.id','desc');
        // $this->db->where('isResendBill', '1');
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function getSignedBill($tblName, $id) {
        $this->db->select('allocationsbills.*, allocations.allocationCode as alCode,DATE(allocations.date) as alDate,e1.name as ename1,e2.name as ename2,e3.name as ename3,e4.name as ename4');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id');
        $this->db->join('bills','bills.id=allocationsbills.billId');
        $this->db->join('employee e1','allocations.fieldStaffCode1=e1.id','left outer');
        $this->db->join('employee e2','allocations.fieldStaffCode2=e2.id','left outer');
        $this->db->join('employee e3','allocations.fieldStaffCode3=e3.id','left outer');
        $this->db->join('employee e4','allocations.fieldStaffCode4=e4.id','left outer');
        $this->db->where('allocationsbills.billId', $id);
        $this->db->where('bills.pendingAmt >', 0);
        $this->db->order_by('allocations.id','desc');
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }


    public function getAllocationAmount($tblName,$id){
        $this->db->select('sum(bills.netAmount) as sumNetAmount,sum(bills.pendingAmt) as sumPendingAmount,sum(bills.receivedAmt) as sumReceivedAmount,sum(bills.SRAmt) as sumSRAmount,sum(bills.fsSrAmt) as sumSrAmount,sum(bills.fsCashAmt) as sumCashAmount,sum(bills.fsChequeAmt) as sumChequeAmount,sum(bills.fsNeftAmt) as sumNeftAmount');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id');
        $this->db->join('bills','bills.id=allocationsbills.billId');
        $this->db->where('allocations.id', $id);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

     public function getAllocationSalesman($tblName,$id){
        $this->db->select('bills.salesman');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id');
        $this->db->join('bills','bills.id=allocationsbills.billId');
        $this->db->where('allocations.id', $id);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }


    public function getBillCount($tableName,$id){
        $query = $this->db->query('SELECT * FROM '.$tableName.' WHERE allocationId='.$id);
        
        return $query->num_rows();   
    }

    public function getCount($tableName,$id){
        $query = $this->db->query('SELECT * FROM '.$tableName.' WHERE billId='.$id);
        
        return $query->num_rows();   
    }

    public function getRowCount($tableName,$id,$type){
        $query = $this->db->query('SELECT * FROM '.$tableName.' WHERE billId='.$id.' and '.$type.'=1');
        
        return $query->num_rows();   
    }

    public function getAdHocBillsByType($tblName,$type){
        $this->db->where('billType',$type);
        // $this->db->where('pendingAmt >',0);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getOfficeBillsByType($tblName,$type,$from,$to){
        $this->db->where('officeAdjustmentBillAmount >',0);
        $this->db->or_where('isOfficeAdjustmentBill',1);
        $this->db->where('date >=',$from);
        $this->db->where('date <=',$to);
        // $this->db->where('pendingAmt >',0);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getOfficeBillsByTypeWithComp($tblName,$type,$from,$to,$company){
        $this->db->where('officeAdjustmentBillAmount >',0);
        $this->db->where('compName',$company);
        $this->db->where('date >=',$from);
        $this->db->where('date <=',$to);
        // $this->db->where('pendingAmt >',0);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function retailerInfo($tableName,$rName,$rcode)
    {
        $this->db->where('name',$rName);
        $this->db->where('code',$rcode);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function routeInfoByName($tableName,$rname)
    {
        $this->db->where('name',$rname);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getDebitBillsByType($tblName,$from,$to){
        $this->db->where('debitNoteAmount >',0);
        $this->db->where('date >=',$from);
        $this->db->where('date <=',$to);
        // $this->db->where('pendingAmt >',0);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getDebitBillsByTypeWithComp($tblName,$from,$to,$company){
        $this->db->where('debitNoteAmount >',0);
        $this->db->where('compName',$company);
        $this->db->where('date >=',$from);
        $this->db->where('date <=',$to);
        // $this->db->where('pendingAmt >',0);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

   

    public function getOtherBillsByType($tblName,$type,$from,$to){
        $this->db->group_start();
        $this->db->where('otherAdjustment >',0);
        $this->db->or_where('isOtherAdjustmentBill',1);
        $this->db->group_end();
        $this->db->where('date >=',$from);
        $this->db->where('date <=',$to);
        $this->db->where('pendingAmt >',0);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getOtherBillsByTypeWithComp($tblName,$type,$from,$to,$company){
        $this->db->group_start();
        $this->db->where('otherAdjustment >',0);
        $this->db->or_where('isOtherAdjustmentBill',1);
        $this->db->group_end();
        $this->db->where('compName',$company);
        $this->db->where('date >=',$from);
        $this->db->where('date <=',$to);
        $this->db->where('pendingAmt >',0);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getCashDiscountBillsByType($tblName,$type,$from,$to){
        $this->db->where('cd >',0);
        $this->db->where('date >=',$from);
        $this->db->where('date <=',$to);
        $this->db->where('pendingAmt >',0);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getCashDiscountBillsByTypeWithComp($tblName,$type,$from,$to,$company){
        $this->db->where('cd >',0);
         $this->db->where('compName',$company);
        $this->db->where('date >=',$from);
        $this->db->where('date <=',$to);
        $this->db->where('pendingAmt >',0);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getOfficeAllocationCount($tblName){
        // $this->db->select('count(*) as allocationsCount');
        $this->db->where('isOfficeAllocation',1);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }


    public function getAllocatedBillsType($tableName,$id){
        $this->db->select('billsdetails.*,bills.billNo as billNo,bills.retailerName as retailerName,bills.fsbillStatus as FsBillStatus');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=billsdetails.billId');
        $this->db->join('bills','bills.id=billsdetails.billId');
        $this->db->where('allocationsbills.allocationId',$id);
        $this->db->where('billsdetails.fsReturnQty >',0);
        // $this->db->where('billsdetails.gkStatus !=',1);
        $this->db->like('FsBillStatus', 'SR');
         $this->db->not_like('FsBillStatus', 'FSR');
        $query=$this->db->get();
        
        return $query->result_array();
    }


    public function getAllocatedBillsFSR($tableName,$id){
        $this->db->distinct();
        $this->db->select('bills.id,bills.billNo as billNo,bills.retailerName as retailerName,bills.fsbillStatus as FsBillStatus');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=billsdetails.billId');
        $this->db->join('bills','bills.id=billsdetails.billId');
        $this->db->where('allocationsbills.allocationId',$id);
        $this->db->where('billsdetails.fsReturnQty >',0);
         // $this->db->where('billsdetails.gkStatus !=',1);
        $this->db->like('bills.fsbillStatus', 'FSR');
        $query=$this->db->get();
        
        return $query->result_array();
   }



     public function getAllocationDetailsByBill($tblName,$id){
        $this->db->select('allocations.id, allocations.allocationCode,e1.name as ename1,e2.name as ename2,e3.name as ename3,e4.name as ename4');
        $this->db->join('allocationsbills','bills.id=allocationsbills.billId','left outer');
        $this->db->join('allocations','allocations.id=allocationsbills.allocationId','left outer');
        $this->db->join('employee e1','allocations.fieldStaffCode1=e1.id','left outer');
        $this->db->join('employee e2','allocations.fieldStaffCode2=e2.id','left outer');
        $this->db->join('employee e3','allocations.fieldStaffCode3=e3.id','left outer');
        $this->db->join('employee e4','allocations.fieldStaffCode4=e4.id','left outer');
        $this->db->where('bills.id',$id);
        $this->db->where('allocations.isAllocationComplete','0');
        $this->db->order_by('allocations.id','desc');
        $this->db->limit(1); 
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

      public function getAllocationDetailsByBillHistory($tblName,$id){
        $this->db->select('allocations.id, allocations.allocationCode');
        $this->db->join('allocationsbills','bills.id=allocationsbills.billId');
        $this->db->join('allocations','allocations.id=allocationsbills.allocationId');
        $this->db->where('bills.id',$id);
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('allocations.isAllocationComplete','1');
        $this->db->order_by('allocations.id','desc');
        $this->db->limit(1); 
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }



    public function getOfficeAllocationDetailsByBill($tblName,$id){
        $this->db->select('allocations_officeadjustment.id, allocations_officeadjustment.allocationCode');
        $this->db->join('allocations_officebills','bills.id=allocations_officebills.billId');
        $this->db->join('allocations_officeadjustment','allocations_officeadjustment.id=allocations_officebills.allocationId');
        $this->db->where('bills.id',$id);
        $this->db->where('allocations_officeadjustment.isAllocationComplete','0');
        $this->db->order_by('allocations_officeadjustment.id','desc');
        $this->db->limit(1); 
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

     public function getOfficeAllocationDetailsByBillHistory($tblName,$id){
        $this->db->select('allocations_officeadjustment.id, allocations_officeadjustment.allocationCode');
        $this->db->join('allocations_officebills','bills.id=allocations_officebills.billId');
        $this->db->join('allocations_officeadjustment','allocations_officeadjustment.id=allocations_officebills.allocationId');
        $this->db->where('bills.id',$id);
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('allocations_officeadjustment.isAllocationComplete','1');
        $this->db->order_by('allocations_officeadjustment.id','desc');
        $this->db->limit(1); 
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getBillAllocationSrByBill($tblName,$id){
        $this->db->distinct();
        $this->db->select('billsdetails.*,allocations.id as allocationId,allocations.allocationCode,allocation_sr_details.quantity as sr_qty,allocation_sr_details.createdAt as createdDate,e1.name as ename1,e2.name as ename2,e3.name as ename3,e4.name as ename4,e5.name as ename5');
        $this->db->join('allocations','allocation_sr_details.allocationId=allocations.id','left outer');
        $this->db->join('billsdetails','billsdetails.id=allocation_sr_details.billItemId');
        $this->db->join("employee e1","e1.id=allocations.fieldStaffCode1",'left outer');
        $this->db->join("employee e2","e2.id=allocations.fieldStaffCode2",'left outer');
        $this->db->join("employee e3","e3.id=allocations.fieldStaffCode3",'left outer');
        $this->db->join("employee e4","e4.id=allocations.fieldStaffCode4",'left outer');
        $this->db->join("employee e5","e5.id=allocation_sr_details.empId",'left outer');
        $this->db->where('allocation_sr_details.billId',$id);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getBillOfficeAdjHistoryByBill($tblName,$id){
        $this->db->select('allocations_officebills.*,employee.name,allocations_officeadjustment.allocationCode,allocations_officeadjustment.remark');
        $this->db->join('allocations_officeadjustment','allocations_officebills.allocationId=allocations_officeadjustment.id','left outer');
        $this->db->join('employee','employee.id=allocations_officeadjustment.createdBy','left outer');
        

        $this->db->where('billId',$id);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getSum($tableName,$id){
        $this->db->select_sum('qty', 'qtySum');
        $this->db->where('billId',$id);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }
    

    public function loadBillsDetails($tableName, $id)
    {
        $this->db->select("bills.*,bills.retailerName as name,employee.name as empname");
        $this->db->join("allocationsbills","allocationsbills.billId=bills.id",'left outer');
        $this->db->join("allocations","allocations.id=allocationsbills.allocationId",'left outer');
        $this->db->join("employee","employee.id=allocations.fieldStaffCode1",'left outer');
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

    public function getBillAllocationHistoryByBill($tblName,$id){
        $this->db->select('billpayments.*,allocations.id as allocationId,allocations.allocationCode,employee.name');
        $this->db->join('allocations','billpayments.allocationId=allocations.id','left outer');
        $this->db->join('employee','employee.id=billpayments.empId','left outer');
        $this->db->where('billpayments.billId',$id);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getBillHistoryByBillId($tblName,$amount,$mode,$id,$allocationId,$officeAllocationId){
        $this->db->select('billpayments.*,allocations.id as allocationId,allocations.allocationCode,employee.name');
        $this->db->join('allocations','billpayments.allocationId=allocations.id','left outer');
        $this->db->join('employee','employee.id=billpayments.empId','left outer');
        $this->db->where('billpayments.billId',$id);
        $this->db->where('billpayments.paymentMode',$mode);
        $this->db->where('billpayments.paidAmount',$amount);
        $this->db->where('billpayments.allocationId',$allocationId);
        $this->db->where('billpayments.officeAllocationId',$officeAllocationId);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getAdHocDeliveryBillsByType($tblName,$type){
        $this->db->where('deliveryStatus !=','cancelled');
        $this->db->where('pendingAmt >',0);
        $this->db->where('isDirectDeliveryBill',1);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getAdHocDeliveryBillsByTypeWithCompany($tblName,$type,$company){
         $this->db->where('deliveryStatus !=','cancelled');
        $this->db->where('compName',$company);
        $this->db->where('pendingAmt >',0);
        $this->db->where('isDirectDeliveryBill',1);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getCurrentOpenAllocations($tblName){
         $this->db->select('allocations.*,route.name as rname');
        $this->db->join('route','route.id=allocations.routId');
        $this->db->where('fsStatus',0);
        $this->db->where('isAllocationComplete',0);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getAllocationDetails($tblName,$id){
        $this->db->select('allocationsbills.*');
        $this->db->join('allocationsbills','allocations.id=allocationsbills.allocationId','left outer');
        $this->db->where('allocations.id',$id);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function checkAllocationCode($tblName,$code){
        $this->db->where('allocationCode',$code);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getNonAllocatedBills($tblName){
        $this->db->where('billType','');
        $this->db->where('isResendBill',0);
        $this->db->where('deliveryStatus !=','cancelled');
        $this->db->where('pendingAmt >',0);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getNonAllocatedBillsWithCompany($tblName,$company){
        $this->db->where('billType','');
        $this->db->where('isResendBill',0);
        $this->db->where('deliveryStatus !=','cancelled');
        $this->db->where('pendingAmt >',0);
        $this->db->where('compName',$company);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function loadSrBillDetails($tblName,$compName){
        $this->db->select('bill_serial_manage.*,company.name as compName,company.id as cId');
        $this->db->join('company','company.id=bill_serial_manage.companyId','right outer');
        $this->db->where('company.name',$compName);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getAllocations($tableName)
    {
        $this->db->where('isAllocationComplete !=','1');
        // $this->db->order_by('id','desc');
        $query=$this->db->get($tableName);
        // $this->db->close();
        // $this->db->initialize();  
        
        return $query->result_array();
    }

    public function getClosedAllocations($tableName)
    {
        $this->db->where('isAllocationComplete =','1');
        $this->db->order_by('id','desc');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function checkInfo($tblName,$id,$alId){
        $this->db->where('billId',$id);
        $this->db->where('allocationId',$alId);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getdataEmployee($tableName)
    {
        $this->db->where('status',1);
        $this->db->where('isDeleted',0);
        $this->db->where('ownerApproval',1);
        $this->db->where('isSalaryEmp',1);
        $this->db->where('isLoginEmp',1);
        $this->db->where('designation','deliveryman');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    

     public function getNames($tableName)
    {
        $this->db->select('name');
        $this->db->distinct();
        $this->db->order_by('id','desc');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getDuplicatedAllocatedRecord($tblName,$billId,$allocationId){
        $this->db->where('billId',$billId);
        $this->db->where('allocationId',$allocationId);
        $data=$this->db->get($tblName);
        
        return $data->result_array();
    }

    public function checkBillInAllocatedRecord($tblName,$billId,$allocationId){
        $this->db->select('id,billId,allocationId');
        $this->db->where('billId',$billId);
        $this->db->where('allocationId',$allocationId);
        $data=$this->db->get($tblName);
        
        return $data->result_array();
    }

    public function insert($tblName, $data) {  
        $this->db->insert($tblName, $data);
        return $this->db->insert_id();
    }
    public function update($tblName, $data, $id) {
        $this->db->where('id', $id);
        return $this->db->update($tblName, $data);  
    }

    public function updateNeftData($tblName, $data, $neftNo,$neftDate) {
        $this->db->where('neftNo', $neftNo);
        $this->db->where('DATE(billpayments.neftDate)', $neftDate);
        return $this->db->update($tblName, $data);  
    }

    public function updateChequeData($tblName, $data, $chequeNo,$chequeDate,$bank) {
        $this->db->where('chequeNo', $chequeNo);
        $this->db->where('chequeBank', $bank);
        $this->db->where('chequeStatus !=', "Bounced");
        $this->db->where('DATE(billpayments.chequeDate)', $chequeDate);
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

    public function deleteAllocationsBills($tblName,$id,$allocationId){
        $this->db->where('billId',$id);
        $this->db->where('allocationId',$allocationId);
        return $this->db->delete($tblName,array('billId'=>$id,'allocationId'=>$allocationId));        

    }

    public function load($tblName, $id) {
        $this ->db-> where('id', $id);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function loadBillData($tblName, $id) {
        $this->db->select('id,billNo,billCurrentStatus,billHistoryStatus,billType');
        $this ->db-> where('id', $id);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function loadPayBills($tblName, $id) {
        $this->db->select('billId');
        $this ->db-> where('allocationId', $id);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function loadAllocated($tblName, $id) {
        $this ->db-> where('allocationCode', $id);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function loadAllocatedBills($tblName, $id) {
        $this->db->select('billId');
        $this ->db-> where('allocationId', $id);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function loadDataByChequeNo($tblName, $id) {
        $this->db->select('bills.*,billpayments.paidAmount as paidCheque,billpayments.date as chDate,billpayments.chequeNo as chNo,billpayments.penalty as chPenalty');
        $this->db->join('bills','bills.id=billpayments.billId');
        $this ->db-> where('chequeNo', $id);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function search($name){
        $this->db->like('billNo', $name, 'both');
        return $this->db->get('bills')->result();
    }

    public function loadBillByNumber($tblName,$billno) {
        $this->db->select('bills.id,bills.compName');
        $this->db->where('bills.billNo',$billno);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function loadCurrentBillsWithCompany($tblName,$fromNo,$toNo,$comp) {
        $this->db->select('bills.*');
        // $this->db->where('find_in_set("'.$fromNo.'", "'.$toNo.'"');
        // $this->db->where('bills.sortBillNo BETWEEN "'. $fromNo. '" and "'.$toNo.'"');
        $this->db->where('bills.id >=', $fromNo);
        $this->db->where('bills.id <=', $toNo);
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('bills.billType','');
        $this->db->where('bills.compName',$comp);
        // $this->db->order_by('LENGTH(bills.billNo)','asc');
        
        // $this->db->where('bills.route',0);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
        
       
    }

    public function getBillsForDebit($tblName,$comp,$fromNo,$toNo) {
        $this->db->select('bills.*');
        $this->db->where('bills.id >=', $fromNo);
        $this->db->where('bills.id <=', $toNo);
        $this->db->where('bills.compName', $comp);
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('bills.isAllocated',0);
        $this->db->where('bills.deliveryStatus !=','cancelled');
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function getAllocationsBillsForBillJournalDebit($tblName,$id,$fromNo,$toNo,$amount) {
        $this->db->select('bills.*');
        $this->db->join('allocationsbills','bills.id=allocationsbills.billId');
        $this->db->where('bills.id >=', $fromNo);
        $this->db->where('bills.id <=', $toNo);
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('bills.pendingAmt <=',$amount);
        $this->db->where('allocationsbills.allocationId', $id);
        $query = $this->db->get($tblName);
        
        return $query->result_array();  
    }

    public function getAllocationsBills($tblName,$id) {
        $this->db->select('bills.*');
        $this->db->join('allocationsbills','bills.id=allocationsbills.billId');
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('allocationsbills.allocationId', $id);
        $query = $this->db->get($tblName);
        
        return $query->result_array();  
    }

    public function getBillsForBillJournalDebit($tblName,$comp,$fromNo,$toNo,$amount) {
        $this->db->select('bills.*');
        $this->db->where('bills.id >=', $fromNo);
        $this->db->where('bills.id <=', $toNo);
        $this->db->where('bills.compName', $comp);
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('bills.pendingAmt <=',$amount);
        $this->db->where('bills.isAllocated',0);
        $this->db->where('bills.deliveryStatus !=','cancelled');
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function getBillsByCompany($tblName,$comp) {
        $this->db->select('bills.*');
        $this->db->where('bills.compName', $comp);
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('bills.isAllocated',0);
        $this->db->where('bills.deliveryStatus !=','cancelled');
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }


    public function getOpenAllocationsForBillJournal($tblName) {
        $this->db->select('allocations.*');
        $this->db->where('gkStatus',0);
        $this->db->where('managerHisaabStatus',0);
        $this->db->where('isAllocationComplete',0);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function getBillsForManualJournalDebit($tblName,$comp,$fromNo) {
        $this->db->select('bills.*');
        $this->db->where('bills.id', $fromNo);
        $this->db->where('bills.compName', $comp);
        $this->db->where('bills.isAllocated',0);
        $this->db->where('bills.deliveryStatus !=','cancelled');
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function loadCurrentBills($tblName,$fromNo,$toNo) {
        $this->db->select('bills.*');
        // $this->db->where('find_in_set("'.$fromNo.'", "'.$toNo.'"');
        // $this->db->where('bills.sortBillNo BETWEEN "'. $fromNo. '" and "'.$toNo.'"');
        $this->db->where('bills.id >=', $fromNo);
        $this->db->where('bills.id <=', $toNo);
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('bills.billType','');
        // $this->db->order_by('LENGTH(bills.billNo)','asc');
        
        // $this->db->where('bills.route',0);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
        
       
    }

    public function loadOfficeBills($tblName, $fromNo ,$toNo) {
        $this->db->select('bills.*');
        $this->db->where('bills.billNo BETWEEN "'. $fromNo. '" and "'.$toNo.'"');
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('bills.isAllocated','0');
        $this->db->where('bills.deliveryStatus !=','cancelled');
        // $this->db->where('bills.route',0);
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

    public function checkAllocationTransaction($tblName, $allocationId) {
        $this->db->select('bills.id');
        $this->db->join('allocationsbills','allocations.id=allocationsbills.allocationId');
        $this->db->join('bills','allocationsbills.billId=bills.id');
        $this->db->where('bills.fsbillStatus !=','');
        $this->db->where('allocations.id',$allocationId);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function loadBillPayments($tblName, $allocationId,$billId,$type) {
        $this->db->select('paidAmount');
        $this->db->where('allocationId',$allocationId);
        $this->db->where('billId',$billId);
        $this->db->where('paymentMode',$type);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function loadBillSRPayments($tblName,$allocationId,$billId) {
        $this->db->select('billsdetails.*,allocation_sr_details.quantity as receivedQuantity');
        $this->db->join('billsdetails','billsdetails.id=allocation_sr_details.billItemId');
        $this->db->where('allocation_sr_details.allocationId',$allocationId);
        $this->db->where('allocation_sr_details.billId',$billId);
         $query = $this->db->get($tblName);
         
        return $query->result_array();  
    }

    public function loadPastBillsByRoute($tblName, $routeName) {
        $this->db->select('bills.*');
        // $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
        // $this->db->join('allocations','allocations.id=allocationsbills.allocationId');
        $this->db->where('bills.routeName',$routeName);
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('bills.isAllocated','0');
        $this->db->where('bills.billType !=','');
        // $this->db->where('bills.isAllocated','0');
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }
    
    public function loadPastBillsByRouteCode($tblName, $code) {
        $this->db->select('bills.*');
        // $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
        // $this->db->join('allocations','allocations.id=allocationsbills.allocationId');
        $this->db->where('bills.routeCode',$code);
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('bills.isAllocated','0');
        $this->db->where('bills.billType !=','');
        // $this->db->where('bills.isAllocated','0');
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function isBillAllocated($tblName,$id){
        $this->db->where('id',$id);
        $this->db->where('isAllocated',0);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getNextId($tableName) {
        $this->db->select_max('id');
        $query = $this->db->get($tableName);
        
        return $query->result_array();
    }

    public function routesByCompany($tblName,$comp){
        $this->db->from($tblName);
        $this->db->like('company',$comp);
        return $this->db->get()->result_array();
    }
    
    public function bouncedReturnCheques($tblName,$comp){
        $this->db->select('chequeNo'); 
        $this->db->from($tblName);
        $this->db->where('chequeStatus','Bounced&Returned');  
        $this->db->or_where('chequeStatus','Bounced');  
        $this->db->like('compName',$comp);
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
        $this->db->where('status',1);
        $this->db->where('isDeleted',0);
        $this->db->where('ownerApproval',1);
        $this->db->where('isSalaryEmp',1);
        $this->db->where('isLoginEmp',1);
        return $this->db->get()->result_array();
    }

    public function getAllEmployees(){
        $this->db->select('*'); 
        $this->db->from('employee');  
        $this->db->where('status',1);
        $this->db->where('isDeleted',0);
        $this->db->where('ownerApproval',1);
        $this->db->where('isSalaryEmp',1);
        $this->db->where('isLoginEmp',1);
        return $this->db->get()->result_array();
    }

    public function getEmployeeNamesByID($id){
        $this->db->select('name'); 
        $this->db->from('employee');
        $this->db->where('id',$id);   
        return $this->db->get()->row()->name;
    }

    public function getRetailerName($code){
        $this->db->select('name'); 
        $this->db->from('retailer');
        $this->db->where('code',$code);   
        return $this->db->get()->result_array();
    }

    public function getRouteID($name){
        $this->db->select('id'); 
        $this->db->from('route');
        $this->db->where('name',$name);   
        return $this->db->get()->row()->id;
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

    public function getDebitNoteBills($tbl){
        $this->db->select('id,billNo,retailerName'); 
        $this->db->where('deliveryStatus !=','Cancelled');
        $this->db->where('pendingAmt >',0);
        $this->db->where('isAllocated',0);
        $this->db->from($tbl); 
        return $this->db->get()->result_array();
    }

    public function getAllocationBills($tbl,$id){
        $this->db->select('bills.id,bills.billNo,bills.retailerName');
        $this->db->join('allocationsbills','allocationsbills.allocationId=allocations.id');
        $this->db->join('bills','allocationsbills.billId=bills.id');
        $this->db->where('allocations.id',$id);
        $this->db->where('bills.pendingAmt >',0);
        $this->db->order_by('bills.id','asc');
        $this->db->from($tbl); 
        return $this->db->get()->result_array();
    }

    public function getDebitNoteBillsWithCompany($tbl,$comp){
        $this->db->select('id,billNo,retailerName'); 
        $this->db->where('deliveryStatus !=','Cancelled');
        $this->db->where('isAllocated',0);
        $this->db->where('pendingAmt >',0);
        $this->db->where('compName',$comp);
        $this->db->order_by('bills.id','asc');
        $this->db->from($tbl); 
        return $this->db->get()->result_array();
    }

    public function getBillNos(){
        $this->db->select('bills.billNo,bills.retailerName'); 
        $this->db->where('pendingAmt >',0);
         $this->db->where('isDeliverySlipBill',0);
        $this->db->where('deliveryStatus !=','Cancelled');
        $this->db->where('billType !=','deliveryslip');
        $this->db->from('bills'); 
        return $this->db->get()->result_array();
    }

    public function getOpenAllocationBillsByCompany($compName){
        $this->db->select('bills.id,bills.billNo,bills.retailerName'); 
        $this->db->where('pendingAmt >',0);
        $this->db->where('isAllocated',0);
        $this->db->where('isDeliverySlipBill',0);
        $this->db->where('manuallyAddedBill',0);
        $this->db->where('deliveryStatus !=','cancelled');
        // $this->db->where('deliveryStatus !=','Saved');
        $this->db->where('billType','');
        $this->db->where('compName',$compName);
        $this->db->from('bills');
        $this->db->order_by('bills.id','asc');
        return $this->db->get()->result_array();
    }

    public function getDeliverySlipBillsByCompany($compName){
        $this->db->select('bills.billNo,bills.retailerName'); 
        $this->db->where('pendingAmt >',0);
        $this->db->where('isAllocated',0);
        $this->db->where('isDeliverySlipBill',1);
        $this->db->where('deliveryStatus !=','cancelled');
        // $this->db->where('deliveryslipOwnerApproval',1);
        $this->db->like('compName',$compName);
        $this->db->from('bills');
        $this->db->order_by('bills.billNo','asc');
        return $this->db->get()->result_array();
    }

    public function getBillNosByCompany($compName){
        $this->db->select('bills.billNo,bills.retailerName'); 
        $this->db->where('pendingAmt >',0);
        $this->db->where('isAllocated',0);
         $this->db->where('isDeliverySlipBill',0);
        $this->db->where('deliveryStatus !=','Cancelled');
        // $this->db->where('deliveryStatus !=','Saved');
        // $this->db->where('billType','');
        $this->db->where('compName',$compName);
        $this->db->from('bills');
        return $this->db->get()->result_array();
    }

    public function getPastBills(){
        $this->db->select('bills.billNo,bills.retailerName'); 
        $this->db->from('bills');
        $this->db->where('pendingAmt >', 0);
         $this->db->where('isDeliverySlipBill',0);
        $this->db->where('route !=', 0);
        $this->db->where('deliveryStatus !=','cancelled');  
        $query=$this->db->get();
        
        return $query->result_array();
    }

    public function getSignedBills($tableName,$id,$billId){
        $this->db->distinct();
        $this->db->select('bills.*');
        $this->db->from($tableName);
        $this->db->join('billsdetails','bills.id=billsdetails.billId','left outer');
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
        $this->db->where('bills.pendingAmt !=',0);
        $this->db->where('bills.fsbillStatus !=','Resend');
        $this->db->where('bills.id',$billId);
        $this->db->where('allocationsbills.allocationId',$id);
        $query=$this->db->get();
        
        return $query->result_array();
    }

    public function loadAllocationBillPayments($tblName, $allocationId,$type) {
        $this->db->select('paidAmount');
        $this->db->where('allocationId',$allocationId);
        $this->db->where('paymentMode',$type);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function getAllocationSignedBills($tableName,$id){
        $this->db->distinct();
        $this->db->select('bills.*,allocationsbills.isLostBill as lostBill,allocationsbills.isResendBill as resendBill,allocationsbills.isBilled as signedBill');
        $this->db->from($tableName);
        $this->db->join('billsdetails','bills.id=billsdetails.billId','left outer');
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
        // $this->db->where('bills.pendingAmt !=',0);
        $this->db->where('allocationsbills.allocationId',$id);
        // $this->db->like('allocationsbills.isResendBill',1);
        // $this->db->where('bills.fsbillStatus !=','Resend');
        $query=$this->db->get();
        
        return $query->result_array();
    }

    public function getAllocationSrBills($tableName,$id){
        $this->db->distinct();
        $this->db->select('billsdetails.*,bills.isFsrBill as b_isFsrBill,bills.billNo as b_billNo,bills.retailerName as b_retailer,bills.salesman as b_salesman,bills.date as b_date,allocation_sr_details.quantity as srQuantity');
        $this->db->from($tableName);
        $this->db->join('billsdetails','bills.id=billsdetails.billId','left outer');
        $this->db->join('allocation_sr_details','allocation_sr_details.billItemId=billsdetails.id');
        $this->db->where('allocation_sr_details.allocationId',$id);
        $this->db->where('bills.isFsrBill',0);
        $query=$this->db->get();
        
        return $query->result_array();
    }

    public function getAllocationFsrBills($tableName,$id){
        $this->db->distinct();
        $this->db->select('bills.isFsrBill as b_isFsrBill,bills.billNo as b_billNo,bills.retailerName as b_retailer,bills.salesman as b_salesman,bills.date as b_date');
        $this->db->from($tableName);
        $this->db->join('billsdetails','bills.id=billsdetails.billId','left outer');
        $this->db->join('allocation_sr_details','allocation_sr_details.billItemId=billsdetails.id');
        $this->db->where('allocation_sr_details.allocationId',$id);
        $this->db->where('bills.isFsrBill',1);
        $this->db->group_by('bills.id');
        $query=$this->db->get();
        
        return $query->result_array();
    }

    public function getBillPaymentsDetails($tableName,$id,$billId){
        $this->db->distinct();
        $this->db->select('billpayments.*,bills.id as b_id,bills.billNo as b_billNo,bills.retailerName as b_retailer,bills.date as b_date');
        $this->db->from($tableName);
        $this->db->join('billpayments','bills.id=billpayments.billId','left outer');
        $this->db->where('billpayments.billId',$billId);
        $this->db->where('billpayments.allocationId',$id);
        $query=$this->db->get();
        
        return $query->result_array();
    }

    public function getBillPayment($tableName,$billId){
        $this->db->distinct();
        $this->db->select('billpayments.*,employee.name as empName');
        $this->db->from($tableName);
        $this->db->join('billpayments','bills.id=billpayments.billId','left outer');
        $this->db->join('employee','employee.id=billpayments.empId','left outer');
        $this->db->where('billpayments.billId',$billId);
        $this->db->where('billpayments.allocationId',0);
        $query=$this->db->get();
        
        return $query->result_array();
    }

    public function getAllocationBillPaymentsDetails($tableName,$id){
        $this->db->distinct();
        $this->db->select('billpayments.*,bills.id as b_id,bills.billNo as b_billNo,bills.retailerName as b_retailer,bills.date as b_date');
        $this->db->from($tableName);
        $this->db->join('billpayments','bills.id=billpayments.billId','left outer');
        $this->db->where('billpayments.allocationId',$id);
        $query=$this->db->get();
        
        return $query->result_array();
    }

    public function getPastBillsList($compName){
        $this->db->distinct();
        $this->db->select('bills.billNo,bills.retailerName'); 
        // $this->db->join('allocationsbills','bills.id=allocationsbills.billId');
        // $this->db->join('allocations','allocations.id=allocationsbills.allocationId');

        // $this->db->where('allocations.isAllocationComplete','1');
        $this->db->where('deliveryStatus !=','cancelled');
        // $this->db->group_start();
        // $this->db->where('bills.billType','allocatedbillCurrent');
        // $this->db->or_where('bills.billType','officeAdjustmentBill');
        // $this->db->or_where('bills.billType','allocatedbillPass');
        // $this->db->or_where('bills.billType','adHocDeliveryBill');
        // $this->db->group_end();
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('bills.isAllocated',0);
        $this->db->where('bills.compName',$compName);
        $this->db->order_by('bills.billNo','asc');
        $this->db->from('bills');
        
        return $this->db->get()->result_array();
    }

    public function getPastBillsByCompAllocation($compName)
    {
        $this->db->distinct();
        $this->db->select('bills.billNo,bills.retailerName'); 
        // $this->db->join('billpayments','bills.id=billpayments.billId','left outer');
        $this->db->from('bills'); 
        $this->db->group_start();
        $this->db->where('bills.billType','allocatedbillCurrent');
        $this->db->or_where('bills.billType','officeAdjustmentBill');
        $this->db->or_where('bills.billType','allocatedbillPass');
        $this->db->or_where('bills.billType','adHocDeliveryBill');
        $this->db->group_end();
        $this->db->where('bills.isAllocated',0);
        $this->db->where('bills.compName',$compName);
        $query=$this->db->get();
        
        return $query->result_array();
    }

    public function getPastBillsByComp($compName)
    {
        $this->db->distinct();
        $this->db->select('bills.billNo,bills.retailerName'); 
        // $this->db->join('billpayments','bills.id=billpayments.billId','left outer');
        $this->db->from('bills'); 
        $this->db->group_start();
        $this->db->where('bills.billType','allocatedbillCurrent');
        $this->db->or_where('bills.billType','officeAdjustmentBill');
        $this->db->or_where('bills.billType','allocatedbillPass');
        $this->db->or_where('bills.billType','adHocDeliveryBill');
        $this->db->group_end();
        $this->db->where('bills.pendingAmt >', 0);  
        $this->db->where('bills.isAllocated',0);
        $this->db->where('bills.compName',$compName);
        $query=$this->db->get();
        
        return $query->result_array();
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

    public function getRetailerCodeByName($tableName,$name)
    {
        $this->db->where('name',$name);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getEmpId($tableName,$name)
    {
        $this->db->where('name',$name);
        $this->db->where('status',1);
        $this->db->where('isDeleted',0);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getEmpName($tableName,$id)
    {
        $this->db->where('id',$id);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getBillDetailInfo($tableName,$id)
    {
        $this->db->where('billId',$id);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getBillHistoryInfo($tableName,$id)
    {
        $this->db->select("bill_transaction_history.*,e1.name as empName1,e2.name as empName2");
        $this->db->join("employee e1","bill_transaction_history.empId = e1.id",'left outer');
        $this->db->join("employee e2","bill_transaction_history.transactionBy = e2.id",'left outer');
        $this->db->where('billId',$id);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getBillPaymentInfo($tableName,$id)
    {
        $this->db->select("billpayments.*,employee.name as empName");
        $this->db->join("employee","billpayments.empId = employee.id");
        $this->db->where('billId',$id);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function loadBillDetails($tableName, $id)
    {
        $this->db->select("billsdetails.*,bills.billNo as billNo,bills.date as Date,bills.retailerName as name,bills.netAmount as netAmt,bills.pendingAmt as pendingAmt,bills.fsSrAmt as fsSrAmt,bills.creditNoteRenewal as creditNoteRenewal");
        $this->db->join("bills","billsdetails.billId = bills.id");
        $this ->db->where('billsdetails.billId', $id);
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }
   
    public function getPastBilldata($tableName,$date,$bill)
    {
        if(empty($bill)){
            $this->db->where('DATE(date) <=', $date);
            $this->db->where('pendingAmt >', 0);
            $query=$this->db->get($tableName);
            
            return $query->result_array();
        }else{
            $this->db->where('DATE(date) <=', $date);
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
            $this->db->where('billNo', $bill);
            $query=$this->db->get($tableName);
            
            return $query->result_array();
        }
    }

    public function getDeliverySlipBilldata($tableName,$date,$bill)
    {
        if(empty($bill)){
            $this->db->where('date <=', $date);
            $this->db->where('pendingAmt >', 0);
            $query=$this->db->get($tableName);
            
            return $query->result_array();
        }else{
            $this->db->where('date <=', $date);
            $this->db->where('pendingAmt >', 0);
            $this->db->where('billNo', $bill);
            $query=$this->db->get($tableName);
            
            return $query->result_array();
        }
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
        $this->db->distinct();
        $this->db->select('bills.*,allocationsbills.isResendBill as ResendBill,allocationsbills.isBilled as Billed');
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
        // $this->db->join('billpayments','allocationsbills.allocationId=billpayments.allocationId','left outer');
        $this->db->where('allocationsbills.allocationId',$id);
        $this->db->where('allocationsbills.billStatus',$status);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getAllocatedPastBillsByType($tableName,$id){
        $this->db->select('bills.*');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
        $this->db->group_start();
        $this->db->where('bills.billType','officeAdjustmentBill');
        $this->db->or_where('bills.billType','allocatedbillPass');
        $this->db->or_where('bills.billType','allocatedbillCurrent');
        $this->db->or_where('bills.billType','adHocDeliveryBill');
        $this->db->group_end();
        $this->db->where('allocationsbills.allocationId',$id);
        $this->db->where('allocationsbills.billStatus','2');
        $query=$this->db->get();
        
        return $query->result_array();
    }

    public function getChequeBillsByIDs($tableName,$id){
        $this->db->select('billpayments.*');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=billpayments.id');
        $this->db->where('allocationsbills.billId',$id);
        $query=$this->db->get();
        
        return $query->result_array();
    }

    public function getUnallocatedBillById($tableName,$id){
        $this->db->select('bills.*');
        $this->db->from($tableName);
        $this->db->where('bills.id',$id);
        $this->db->where('bills.isAllocated',0);
        $query=$this->db->get();
        
        return $query->result_array();
    }

    public function insertTableData($tblName, $data) {
        $this->db->insert($tblName, $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }

    }
}
?>