<?php
class NonAllocationBillModel extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function getdata($tableName)
    {
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
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

    public function getEmloyee($tableName)
    {
        $this->db->where('status','1');
        $this->db->where('isDeleted','0');
        $this->db->where('isUniversalId','0');
        $this->db->where('ownerApproval','1');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }
    //
    public function getBillsTB($tableName)
    {
        $this->db->where('billType !=','taggedbill');
        $this->db->where('pendingAmt >',0);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
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
    //
    public function updateBillType($tblName, $data, $billNo) {
        $this->db->where('id', $billNo);
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
        $this -> db -> where('id', $id);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();   
    }

    //
    public function loadByBillNo($tblName, $billNo) {
        $this -> db -> where('id', $billNo);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();   
    }

    //
    public function getEmployee($name){
        $this->db->from('employee'); 
        $this->db->where('name',$name);  
        return $this->db->get()->result_array();
    }
    
    public function getdataTagged($tableName)
    {
        $this->db->select("bills.*,employee.name as empname");
        $this->db->distinct('bills.billNo');
        $this->db->join("allocationsbills","allocationsbills.billId=bills.id");
        $this->db->join("allocations","allocations.id=allocationsbills.allocationId");
        $this->db->join("employee","employee.id=allocations.fieldStaffCode1");
        $this->db->where("billType","taggedbill");
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    public function loadBillsDetails($tableName, $id)
    {
        $this->db->select("bills.*,employee.name as empname");
        // $this->db->join("retailer","bills.retailerCode = retailer.code");
       
        $this->db->join("allocationsbills","allocationsbills.billId=bills.id");
        $this->db->join("allocations","allocations.id=allocationsbills.allocationId");
        $this->db->join("employee","employee.id=allocations.fieldStaffCode1");
        $this ->db ->where('bills.id', $id);
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
        
        // SELECT bills.*,retailer.name as name,employee.name as empname FROM `bills` join retailer on bills.retailerCode=retailer.code join allocationsbills on allocationsbills.billId=bills.id join allocations on allocations.id=allocationsbills.allocationId join employee on employee.id=allocations.fieldStaffCode1
    }

    public function billDetails($tableName, $id)
    {
        $this->db->select("billsdetails.*,bills.billNo as billNo,bills.date as Date,bills.retailerName as name");
        $this->db->join("bills","billsdetails.billId = bills.id");
        // $this->db->join("retailer","bills.retailerCode = retailer.code");
        $this ->db->where('billsdetails.billId', $id);
        // $this->db->order_by('billsdetails.date', 'desc');
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }
    public function loadBillDetailsID($tableName, $id)
    {
        $this->db->select("billsdetails.*,bills.billNo as billNo,bills.date as Date,bills.retailerName as name");
        $this->db->join("bills","billsdetails.billId = bills.id");
        // $this->db->join("retailer","bills.retailerCode = retailer.code");
        $this ->db->where('billsdetails.id', $id);
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    public function updateSRPamt($tableName,$amt,$id){
        $this->db->set('SRAmt','SRAmt+'.$amt,false);
        $this->db->set('receivedAmt','receivedAmt+'.$amt,false);
        $this->db->set('pendingAmt','pendingAmt-'.$amt,false);
        $this->db->where('id',$id);
        return $this->db->update($tableName);
    }

    public function updateAvailQty($tableName,$qty,$name){
        $this->db->set('availQty','availQty+'.$qty,false);
        $this->db->where('name',$name);
        return $this->db->update($tableName);
    }
}
?>