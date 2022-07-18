<?php
class EmployeeModel extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function getLastEntry($tableName)
    {
        $this->db->order_by('id','desc');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getSumRowCount($id){
        $query = $this->db->query('SELECT sum(isResendBill) as recBill,sum(isLostBill) as lostBill,sum(isLostCheque) as lostCheque,sum(isPendingNeft)as lostNeft FROM `allocationsbills` WHERE allocationsbills.billId='.$id);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getdata($tableName)
    {
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

     public function getdataStatus($tblName,$status)
    {
        $this->db->where('status', $status);
        $query=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array(); 
    }

    public function salesmanData($tblName)
    {
        $this->db->distinct('');
        $this->db->select('salesmanCode,salesman,compName');
        $this->db->where('salesmanCode !=', "");
        $this->db->where('isDeliverySlipBill', 0);
        $this->db->group_by('salesmanCode');
        // $this->db->order_by('id','desc');
        $query=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array(); 
    }

    public function allocationEmployees($tblName)
    {
        $this->db->where('status',1);
        $this->db->where('isDeleted',0);
        $this->db->where('ownerApproval',1);
        $this->db->where('isSalaryEmp',1);
        $this->db->where('isLoginEmp',1);
        // $this->db->like('designation','deliveryman');
        $query=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array(); 
    }

    public function checkedLinkedSalesman($tblName,$salesmanCode,$salesmanName)
    {
        // $this->db->where('employeeId !=',0);
        $this->db->where('salesmanCode',$salesmanCode);
        $this->db->where('salesmanName',$salesmanName);
        $query=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array(); 
    }

     public function getLinkedSalesman($tblName,$salesmanCode,$salesmanName)
    {
        $this->db->where('salesmanCode',$salesmanCode);
        $this->db->where('salesmanName',$salesmanName);
        $query=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array(); 
    }

    public function checkedLinkedEmployee($tblName,$employeeId)
    {
        $this->db->where('employeeId',$employeeId);
        $query=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array(); 
    }

    public function allocationSalesman($tblName)
    {
        $this->db->where('status',1);
        $this->db->where('isDeleted',0);
        $this->db->where('ownerApproval',1);
        $this->db->where('isSalaryEmp',1);
        $this->db->where('isLoginEmp',1);
        $this->db->like('designation','salesman');
        $query=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array(); 
    }
    
    public function checkEmpDetails($tblName,$code){
        $this->db->where('code', $code);
        $query=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array(); 
    }

    public function checkSalariedEmpDetails($tblName,$code){
        $this->db->where('code', $code);
        $this->db->where('status',1);
        $this->db->where('isDeleted',0);
        $this->db->where('ownerApproval',1);
        $this->db->where('isSalaryEmp',1);
        $this->db->where('isLoginEmp',1);
        $query=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array(); 
    }

     public function getRowCount($tableName,$id,$type){
        $query = $this->db->query('SELECT * FROM '.$tableName.' WHERE billId='.$id.' and '.$type.'=1');
        $this->db->close();
        $this->db->initialize();
        return $query->num_rows();   
    }

    public function checkBouncedBill($tblName,$id){
        $this->db->like('chequeStatus', 'Bounced');
        $this->db->where('billId', $id);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->num_rows();   
    }

    public function loadUserByMobile($tblName,$mobile) {
        $this->db->where('mobile', $mobile);
        $data = $this->db->get($tblName);
        if($data->num_rows()>0){
            $this->db->close();
        $this->db->initialize();
            return $data->result_array();
        }else{
            return null;
        }
    }

     public function loadUserByUserName($tblName,$name) {
        $this->db->where('email', $name);
        $data = $this->db->get($tblName);
        if($data->num_rows()>0){
            $this->db->close();
        $this->db->initialize();
            return $data->result_array();
        }else{
            return null;
        }
    }

     public function getEmployeeBills($tblName,$empId){
        $this->db->select('bills.*,e1.name as ename');
        $this->db->join('allocationsbills','allocations.id=allocationsbills.allocationId');
        $this->db->join('bills','bills.id=allocationsbills.billId');
        $this->db->join('employee e1','e1.id=allocations.fieldStaffCode1','left outer');
        $this->db->join('employee e2','e2.id=allocations.fieldStaffCode2','left outer');
        $this->db->join('employee e3','e3.id=allocations.fieldStaffCode3','left outer');
        $this->db->join('employee e4','e4.id=allocations.fieldStaffCode4','left outer');
        $this->db->group_start();
        $this->db->where('allocations.fieldStaffCode1', $empId);
        $this->db->or_where('allocations.fieldStaffCode2', $empId);
        $this->db->or_where('allocations.fieldStaffCode3', $empId);
        $this->db->or_where('allocations.fieldStaffCode4', $empId);
        $this->db->group_end();
        $this->db->where('bills.pendingAmt >', 0);
        $this->db->group_by('bills.id');
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getEmployeeProvisitionalBills($tblName,$empId){
        $this->db->select('bills.*,e1.name as ename');
        $this->db->join('billpayments','bills.id=billpayments.billId','left outer');
        $this->db->join('employee e1','e1.id=billpayments.empId','left outer');
        $this->db->or_where('billpayments.empId', $empId);
        $this->db->where('bills.pendingAmt >', 0);
        $this->db->group_by('bills.id');
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function loadEmployeeProvisitionalBills($tblName,$empId){
        $this->db->select('bills.*');
        $this->db->join('billpayments','bills.id=billpayments.billId','left outer');
        $this->db->join('employee e1','e1.id=billpayments.empId','left outer');
        $this->db->or_where('billpayments.empId', $empId);
        $this->db->where('bills.pendingAmt >', 0);
        $this->db->group_by('bills.id');
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getEmployeeDeliveryBills($tblName,$empName){
        $this->db->select('bills.*');
        $this->db->join('employee e1','e1.name=bills.deliveryEmpName');
        $this->db->where('e1.name', $empName);
        $this->db->where('bills.pendingAmt >', 0);
        $this->db->group_by('bills.id');
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

     //outstanding bills
    public function loadOutstandingBills($tableName,$userId){
        $this->db->select('bills.*');
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id');
        $this->db->join('employee e1','e1.id=allocations.fieldStaffCode1','left outer');
        $this->db->join('employee e2','e2.id=allocations.fieldStaffCode2','left outer');
        $this->db->join('employee e3','e3.id=allocations.fieldStaffCode3','left outer');
        $this->db->join('employee e4','e4.id=allocations.fieldStaffCode4','left outer');
        $this->db->from($tableName);
        $this->db->where('bills.pendingAmt >',0);
        $this->db->group_start();
        $this->db->where('bills.isAllocated',0);
        $this->db->or_where('bills.isAllocated',1);
        $this->db->group_end();
        $this->db->group_start();
        $this->db->where('e1.id',$userId);
        $this->db->or_where('e2.id',$userId);
        $this->db->or_where('e3.id',$userId);
        $this->db->or_where('e4.id',$userId);
        $this->db->group_end();
        $this->db->group_by('bills.id');
        $this->db->order_by('bills.billNo');
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    //outstanding bills
    public function loadSalesmanOutstandingBills($tableName,$userId){
        $this->db->select('bills.*');
        $this->db->join('salesman_linking','bills.salesmanCode=salesman_linking.salesmanCode');
        $this->db->join('employee e1','e1.id=salesman_linking.employeeId');
        $this->db->from($tableName);
        $this->db->where('bills.deliveryStatus !=',"cancelled");
        $this->db->where('bills.pendingAmt >',0);
        $this->db->group_start();
        $this->db->where('bills.isAllocated',0);
        $this->db->or_where('bills.isAllocated',1);
        $this->db->group_end();
        $this->db->group_start();
        $this->db->where('salesman_linking.employeeId',$userId);
        $this->db->group_end();
        $this->db->group_by('bills.id');
        $this->db->order_by('bills.billNo');
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getEmpLedger($tableName)
    {
        //
        $this->db->select("emptransactions.amount as amt,emptransactions.transactionType as type,employee.id as empId,employee.name as empName");
        $this->db->join("emptransactions","emptransactions.empId = employee.id","left outer"); 
        $this->db->where('employee.isSalaryEmp', 1);
        $this->db->group_by('employee.id');
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize(); 
        return $resultset->result_array();
    }

    public function getEmpLedgerForDetail($tableName)
    {
        //
        $this->db->select("emptransactions.amount as amt,emptransactions.transactionType as type,employee.id as empId,employee.name as empName");
        $this->db->join("emptransactions","emptransactions.empId = employee.id","left outer"); 
        // $this->db->where('status', 1);
        // $this->db->where('employee.isDeleted', 0);
        // $this->db->where('employee.isSalaryEmp', 1);
        $this->db->group_by('employee.id');
        $resultset=$this->db->get($tableName); 
        $this->db->close();
        $this->db->initialize();
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
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    public function getEmpLedgerByEmpDates($tableName,$empId,$fdate,$ldate)
    {
        $sql="SELECT emptransactions.*,employee.id as empId,employee.name as empName,bills.billNo as billNo FROM `emptransactions` join employee on emptransactions.empId = employee.id left join bills on emptransactions.billId = bills.id where emptransactions.ownerApprovalStatus!=2 and emptransactions.empId=$empId and DATE(emptransactions.createdAt) BETWEEN '$fdate' AND '$ldate'";
        $resultset=$this->db->query($sql); 
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }
    
    public function getEmpLedgerByEmpDate($tableName,$empId,$fromdate,$todate)
    {
        $sql="SELECT emptransactions.*,employee.id as empId,employee.name as empName FROM `emptransactions` join employee on emptransactions.empId = employee.id where emptransactions.ownerApprovalStatus!=2 and emptransactions.empId=$empId and DATE(emptransactions.createdAt) < '$fromdate' order by emptransactions.id desc";
        $resultset=$this->db->query($sql);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    public function getClosingLedgerById($tableName,$empId,$todate)
    {
        $sql="SELECT emptransactions.*,employee.id as empId,employee.name as empName FROM `emptransactions` join employee on emptransactions.empId = employee.id where emptransactions.empId=$empId and emptransactions.ownerApprovalStatus !=2 and DATE(emptransactions.createdAt) <= '$todate' order by emptransactions.id desc";
        $resultset=$this->db->query($sql);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    // public function getEmpLedgerByEmpDate($tableName,$empId,$fromdate,$todate)
    // {
    //     $sql="SELECT emptransactions.*,employee.id as empId,employee.name as empName FROM `emptransactions` join employee on emptransactions.empId = employee.id where emptransactions.empId=$empId and DATE(emptransactions.createdAt) BETWEEN '$fromdate' and '$todate' order by emptransactions.id limit 1";
    //     $resultset=$this->db->query($sql);
    //     return $resultset->result_array();
    // }

    public function getEmpLedgerById($tableName,$empId)
    {
        $sql="SELECT emptransactions.*,employee.id as empId,employee.name as empName FROM `emptransactions` join employee on emptransactions.empId = employee.id where emptransactions.empId=$empId and emptransactions.ownerApprovalStatus!=2 order by emptransactions.id desc";
        $resultset=$this->db->query($sql);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    public function getAllocationDetailsByBill($tblName,$id){
        $this->db->select('allocations.id, allocations.allocationCode,e1.name as ename1,e2.name as ename2,e3.name as ename3,e4.name as ename4');
        $this->db->join('allocationsbills','bills.id=allocationsbills.billId','left outer');
        $this->db->join('allocations','allocations.id=allocationsbills.allocationId','left outer');
        $this->db->join('employee e1','allocations.fieldstaffCode1=e1.id','left outer');
        $this->db->join('employee e2','allocations.fieldstaffCode2=e2.id','left outer');
        $this->db->join('employee e3','allocations.fieldstaffCode3=e3.id','left outer');
        $this->db->join('employee e4','allocations.fieldstaffCode4=e4.id','left outer');
        $this->db->where('bills.id',$id);
        $this->db->where('allocations.isAllocationComplete','0');
        $this->db->order_by('allocations.id','desc');
        $this->db->limit(1); 
        $query=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
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
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    // public function getAllocationDetailsByBill($tblName,$id){
    //     $this->db->select('allocations.id, allocations.allocationCode');
    //     $this->db->join('allocationsbills','bills.id=allocationsbills.billId');
    //     $this->db->join('allocations','allocations.id=allocationsbills.allocationId');
    //     $this->db->where('bills.id',$id);
    //     $this->db->where('allocations.isAllocationComplete','0');
    //     $this->db->order_by('allocations.id','desc');
    //     $this->db->limit(1); 
    //     $query=$this->db->get($tblName);
    //     return $query->result_array();
    // }

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
        $this->db->close();
        $this->db->initialize();
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
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

     public function getdataActive($tableName)
    {
        $this->db->select("employee.*,company.name as companyName");
        $this->db->join("company","employee.companyId = company.id","left outer"); 
        $this->db->where('status', 1);
        $this->db->where('isDeleted', 0);
        $resultset=$this->db->get($tableName); 
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    public function getdataActiveForDetail($tableName)
    {
        $this->db->select("employee.*,company.name as companyName");
        $this->db->join("company","employee.companyId = company.id","left outer"); 
        $this->db->where('isUniversalId', 0);
        $this->db->group_start();
        $this->db->where('status', 1);
        $this->db->or_where('status', 2);
         $this->db->group_end();
        $this->db->where('employee.isDeleted', 0);
        $this->db->order_by('employee.id','asc');
         // $this->db->where('employee.isSalaryEmp',1);
        $resultset=$this->db->get($tableName); 
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    public function getActiveUniversalEmployee($tableName)
    {
         $this->db->select("employee.*,company.name as companyName");
        $this->db->join("company","employee.companyId = company.id","left outer"); 
        $this->db->where('isUniversalId', 1);
        $this->db->where('status', 1);
        $this->db->or_where('status', 2);
        $this->db->where('employee.isDeleted', 0);
        $this->db->order_by('employee.id','asc');
         // $this->db->where('employee.isSalaryEmp',1);
        $resultset=$this->db->get($tableName); 
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    public function getNonSalaryEmployeeForDetail($tableName)
    {
        $this->db->select("employee.*,company.name as companyName");
        $this->db->join("company","employee.companyId = company.id","left outer"); 
        $this->db->where('status', 1);
        $this->db->where('employee.isDeleted', 0);
         $this->db->where('employee.isSalaryEmp',0);
        $resultset=$this->db->get($tableName); 
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

     public function getdataDeactive($tableName)
    {
        $this->db->select("employee.*,company.name as companyName");
        $this->db->join("company","employee.companyId = company.id","left outer"); 
        $this->db->where('status', 0);
        $this->db->where('isDeleted', 0);
        $resultset=$this->db->get($tableName); 
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }
    public function insert($tblName, $data) {      
        
        $this->db->insert($tblName, $data);
        return $this->db->insert_id();
    }
    public function update($tblName, $data, $id) {
        $this->db->where('id', $id);
        return $this->db->update($tblName, $data);  
    }

    public function updateByCodeName($tblName, $data, $code,$name) {
        $this->db->where('salesmanCode', $code);
        $this->db->where('salesmanName', $name);
        return $this->db->update($tblName, $data);  
    }

    public function show($tblName) {
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
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
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();   
    }

    public function getEmpDesignation($tblName, $role) {
        $this->db->where('name', $role);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();   
    }

    public function getEmpDetails($tableName,$id)
    {
        $this->db->select("employee.*,company.id as compId,company.name as companyName");
        $this->db->join("company","employee.companyId = company.id","left outer"); 
        $this->db->where('employee.id', $id);
        $this->db->where('isDeleted', 0);
        $resultset=$this->db->get($tableName); 
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }
}
?>