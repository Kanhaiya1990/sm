<?php
class OfficeAllocationModel extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }
    
    public function getdata($tableName)
    {
        // $this->db->where('isDeleted', 0);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function billsById($tblName,$id) {
        $this->db->where('billId',$id);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function lastExpenseValue(){
        $date=date('Y-m-d');
        $query = $this->db->query("SELECT * FROM expences ORDER BY id DESC LIMIT 1");
        return $query->row_array();
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

    public function loadOfficeBills($tableName,$id)
    {
        $this->db->where('allocationId',$id);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getDataByBillNo($tableName,$billNo)
    {
        $this->db->where('pendingAmt >',0);
        $this->db->where('billNo', $billNo);
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

    public function findCloseDaybookDetail($tableName,$name)
    {
        $this->db->where('closeDayBookDate', $name);
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

    public function updateCloseDaybookDetail($tblName, $data, $name) {
        $this->db->where('closeDayBookDate', $name);
        return $this->db->update($tblName, $data);  
    }

    public function updateChequeData($tblName, $data, $chequeNo,$chequeDate,$bank) {
        $this->db->where('chequeNo', $chequeNo);
        $this->db->where('chequeBank', $bank);
        $this->db->where('chequeStatus !=', "Bounced");
        $this->db->where('DATE(billpayments.chequeDate)', $chequeDate);
        return $this->db->update($tblName, $data);  
    }

    public function updateNeftData($tblName, $data, $neftNo,$neftDate) {
        $this->db->where('neftNo', $neftNo);
        $this->db->where('DATE(billpayments.neftDate)', $neftDate);
        return $this->db->update($tblName, $data);  
    }

    public function getLastRecorddata()
    {
        $this->db->where('isCloseDayBook', 0);
        $this->db->limit(1);
        $this->db->order_by('id','desc');
        $query=$this->db->get('expences');
        
        return $query->result_array();
    }

    public function lastRecordDayBookValue(){
        $date=date('Y-m-d');
        $query = $this->db->query("SELECT * FROM expences WHERE isCloseDayBook=0 ORDER BY id desc LIMIT 1");
        
        return $query->row_array();
    }

    public function getEmpTransaction($tblName){
        $this->db->select("emptransactions.*,employee.name as empName");
        $this->db->join("employee","employee.id = emptransactions.empId","left outer"); 
        $this->db->where('nonCashStatus', 1);
        $this->db->where('ownerApprovalStatus', 0);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getCreditEmpTransaction($tblName){
        $this->db->select("emptransactions.*,employee.name as empName,e2.name as initiatorName");
        $this->db->join("employee","employee.id = emptransactions.empId","left outer"); 
        $this->db->join("employee e2","e2.id = emptransactions.createdBy","left outer"); 
        $this->db->where('nonCashStatus', 1);
        $this->db->where('ownerApprovalStatus', 0);
        $this->db->where('transactionType', 'cr');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getDebitEmpTransaction($tblName){
        $this->db->select("emptransactions.*,employee.name as empName,e2.name as initiatorName");
        $this->db->join("employee","employee.id = emptransactions.empId","left outer"); 
        $this->db->join("employee e2","e2.id = emptransactions.createdBy","left outer"); 
        $this->db->where('nonCashStatus', 1);
        $this->db->where('ownerApprovalStatus', 0);
        $this->db->where('transactionType', 'dr');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getDebitByProcess($tblName){
        $this->db->select("billpayments.*,bills.billNo as currentbillNo,bills.date as currentbillDate,employee.name as empName,bill_remark_history.remark as description,e2.name as initiatorName");
        $this->db->join("employee","employee.id = billpayments.empId","left outer"); 
        $this->db->join("employee e2","e2.id = billpayments.updatedBy","left outer");
        $this->db->join("bills","bills.id = billpayments.billId","left outer"); 
        $this->db->join("bill_remark_history","billpayments.billId = bill_remark_history.billId","left outer"); 
        $this->db->where('billpayments.ownerApproval', 0);
        $this->db->where('billpayments.allocationId',0);
        $this->db->where('paymentMode', 'Debit To Employee');
        $this->db->group_by('billpayments.id');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getCdData($tblName){
        $this->db->select("bills.*,billpayments.id as billPaymentId,billpayments.paidAmount as cdAmount,,billpayments.date as cdDate,e2.name as initiatorName");
        $this->db->join("bills","bills.id = billpayments.billId"); 
        $this->db->join("employee e2","e2.id = billpayments.updatedBy","left outer"); 
        $this->db->where('billpayments.paymentMode', 'CD');
        $this->db->where('billpayments.ownerApproval',0);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getOtherAdjustmentData($tblName){
        $this->db->select("bills.*,billpayments.id as billPaymentId,billpayments.paidAmount as otherAdjAmount,,billpayments.date as otherAdjDate,e2.name as initiatorName");
        $this->db->join("bills","bills.id = billpayments.billId"); 
        $this->db->join("employee e2","e2.id = billpayments.updatedBy","left outer"); 
        $this->db->where('billpayments.paymentMode', 'Other Adjustment');
        $this->db->where('billpayments.ownerApproval',0);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getOfficeAdjustmentData($tblName){
        $this->db->select("bills.*,billpayments.id as billPaymentId,billpayments.paidAmount as officeAdjAmount,,billpayments.date as officeAdjDate,e2.name as initiatorName");
        $this->db->join("bills","bills.id = billpayments.billId"); 
        $this->db->join("employee e2","e2.id = billpayments.updatedBy","left outer"); 
        $this->db->where('billpayments.paymentMode', 'Office Adjustment');
        $this->db->where('billpayments.ownerApproval',0);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getEmpApproval($tableName)
    {
        $this->db->select("employee.*,company.name as companyName");
        $this->db->join("company","employee.companyId = company.id","left outer"); 
        $this->db->where('ownerApproval', 0);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getEmpCompany($tableName,$id)
    {
        $this->db->select("employee.*,company.name as companyName");
        $this->db->join("company","employee.companyId = company.id","left outer"); 
        $this->db->where('employee.id', $id);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

     public function getDetails($tableName)
    {
        $this->db->order_by('id', 'desc');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function get_billClearanceLimit(){
        $this->db->select('expenseLimit');
        $this->db->from('expenses_limit');
        $this->db->where('id',4);
        $row = $this->db->get()->row();
        if (isset($row)) {
            return $row->expenseLimit;
        } else {
            return false;
        }
    }

    public function get_empIdLimit($name){
        $this->db->select('id');
        $this->db->from('employee');
        $this->db->where('name',$name);
        $row = $this->db->get()->row();
        if (isset($row)) {
            return $row->id;
        } else {
            return false;
        }
    }

    public function getBillInfoForClearance($tblName,$company,$limit){
        $this->db->where('pendingAmt <=',$limit);
        $this->db->where('pendingAmt >',0);
        $this->db->where('compName',$company);
        $this->db->where('isAllocated !=',1);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getGeneralBillInfoForClearance($tblName,$limit){
        $this->db->where('pendingAmt <=',$limit);
        $this->db->where('pendingAmt >',0);
        $this->db->where('isAllocated !=',1);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getCount($tableName,$id){
        $query = $this->db->query('SELECT * FROM '.$tableName.' WHERE billId='.$id);
        return $query->num_rows();   
    }

    public function getCategories($tblName,$type){
        $this->db->where('type',$type);
        $res=$this->db->get($tblName);
        
        return $res->result_array();
    }   

    public function lastRecordValue(){
        $query = $this->db->query("SELECT * FROM expences where isCloseDayBook=0 ORDER BY id desc LIMIT 1");
        
        return $query->row_array();
    }

    public function getExpensesDetails($tableName){
        $this->db->select('notesdetails.*,employee.id as emp_id,employee.name emp_name, allocations.allocationCode as code,allocations.date as allocactionDate,e2.name initiator_name');
        $this->db->join('allocations','allocations.id=notesdetails.allocationId');
        $this->db->join('employee','employee.id=allocations.fieldStaffCode1');
        $this->db->join('employee e2','e2.id=notesdetails.updatedBy','left outer');
        $this->db->where('notesdetails.expenseOwnerApproval','1');
        $this->db->order_by('notesdetails.id','desc');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getAllExpensesDetails($tableName){
        $this->db->select('expences.*,employee.id as emp_id,employee.name as emp_name,e2.name initiator_name');
        $this->db->join('employee','employee.id=expences.employeeId');
        $this->db->join('employee e2','e2.id=expences.updatedBy');
        $this->db->where('expenseOwnerApproval',1);
        $this->db->where('expences.nature !=','Bank Deposit');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

     public function getAllDayBookExpensesDetails($tableName){
        $this->db->select('close_daybook_notes.*,employee.id as emp_id,employee.name as emp_name');
        $this->db->where('expenseApproval',0);
        $this->db->join('employee','employee.id=close_daybook_notes.empId');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

     public function getOfficeAllocations($tableName)
    {
        $this->db->select('allocations_officeadjustment.*,employee.id as emp_id,employee.name as emp_name');
        $this->db->join('employee','employee.id=allocations_officeadjustment.createdBy','left outer');
        $this->db->where('isAllocationComplete !=','1');
        $this->db->order_by('id','desc');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getBankDepositDetails($tableName)
    {
        $this->db->select('expences.*,employee.id as emp_id,employee.name as emp_name,e2.name as initiator_name');
        $this->db->join('employee','employee.id=expences.employeeId');
        $this->db->join('employee e2','e2.id=expences.updatedBy');
        $this->db->where('bankDepositApproval','1');
        // $this->db->where('expenseOwnerApproval','0');
        $this->db->where('expences.amount !=','0');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getMainCashbookBankDepositDetails($tableName)
    {
        $this->db->select('main_cashbook_expences.*,employee.id as emp_id,employee.name as emp_name');
        $this->db->join('employee','employee.id=main_cashbook_expences.employeeId');
        $this->db->where('bankDepositApproval','1');
        $this->db->where('main_cashbook_expences.amount !=','0');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getClosedOfficeAllocations($tableName)
    {
        $this->db->where('isAllocationComplete','1');
         $this->db->order_by('id','desc');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getClosedOfficeBillsInfo($tableName,$id)
    {
        $this->db->select('bills.*,allocations_officebills.id as a_id,allocations_officebills.amount as a_amount,allocations_officebills.transactionType as a_type');
        $this->db->join('bills','bills.id=allocations_officebills.billId');
        $this->db->where('allocations_officebills.allocationId',$id);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getAllocations($tableName)
    {
        // $this->db->where('isAllocationComplete','3');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function loadAllocatedBills($tableName,$id)
    {
        $this->db->select('bills.*,allocations_officebills.id as a_id,allocations_officebills.amount as a_amount,allocations_officebills.transactionType as a_type,allocations_officeadjustment.remark as a_remark,allocations_officeadjustment.title as a_title');
        $this->db->join('bills','bills.id=allocations_officebills.billId');
        $this->db->join('allocations_officeadjustment','allocations_officeadjustment.id=allocations_officebills.allocationId');
        
        $this->db->where('allocations_officebills.allocationId',$id);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function loadCurrentAllocatedBills($tableName,$id,$allocationId)
    {
        $this->db->select('bills.*,allocations_officebills.id as a_id,allocations_officebills.amount as a_amount,allocations_officebills.transactionType as a_type');
        $this->db->join('allocations_officebills','bills.id=allocations_officebills.billId');
        $this->db->where('allocations_officebills.allocationId',$allocationId);
        $this->db->where('allocations_officebills.billId',$id);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getBillNosByCompany($compName){
        $this->db->select('bills.billNo,bills.retailerName'); 
         
        $this->db->where('pendingAmt >',0);
        $this->db->where('isAllocated',0);
         // $this->db->where('route',0);
        $this->db->where('deliveryStatus !=','cancelled');
        // $this->db->where('deliveryStatus !=','Saved');
        // $this->db->where('billType','');
        // $this->db->where('billType !=','allocatedbillPass');
        // $this->db->where('billType !=','allocatedbillCurrent');
        // $this->db->where('billType !=','allocatedbillDS');
        $this->db->where('compName',$compName);
        $this->db->from('bills');
        return $this->db->get()->result_array();
    }

    public function insert($tblName, $data) {      
        
        $this->db->insert($tblName, $data);
        return $this->db->insert_id();
    }

    public function update($tblName, $data, $id) {
        $this->db->where('id', $id);
        return $this->db->update($tblName, $data);  
    }

     public function updateAllocation($tblName, $data, $id,$allocationId) {
        $this->db->where('billId', $id);
        $this->db->where('allocationId', $allocationId);
        return $this->db->update($tblName, $data);  
    }

    public function updateEmpTransaction($tblName, $data, $id,$amount) {
        $this->db->where('billId', $id);
        $this->db->where('amount', $amount);
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
        $this -> db -> where('id', $id);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function getCategoryByType($tblName, $type) {
        $this->db->where('type', $type);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function checkInfo($tblName,$id,$alId){
        $this->db->where('billId',$id);
        $this->db->where('allocationId',$alId);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function checkAllocationDetails($tblName,$id){
        $this->db->where('allocationId',$id);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function get_expenseDayBookLimit(){
        $this->db->select('expenseLimit');
        $this->db->from('expenses_limit');
         $this->db->where('id',3);
        $row = $this->db->get()->row();
        if (isset($row)) {
            return $row->expenseLimit;
        } else {
            return false;
        }
    }
}
?>