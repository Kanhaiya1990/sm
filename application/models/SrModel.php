<?php
class SrModel extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function checkStatusForHistory($tableName,$billId,$allocationId,$type){
        $this->db->where('billId', $billId);
        $this->db->where('allocationId', $allocationId);
        $this->db->where('transactionStatus', $type);
        $resultset=$this->db->get($tableName); 
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
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
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getdataActive($tableName)
    {
        $this->db->select("employee.*,company.name as companyName");
        $this->db->join("company","employee.companyId = company.id","left outer"); 
        // $this->db->where('status', 1);
        $this->db->where('isDeleted', 0);
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize(); 
        return $resultset->result_array();
    }

    public function updateHistory($tblName, $data, $billId,$allocationId,$type) {
        $this->db->where('billId', $billId);
        $this->db->where('allocationId', $allocationId);
        $this->db->where('transactionStatus', $type);
        $this->db->close();
        $this->db->initialize();
        return $this->db->update($tblName, $data);  
    }

    public function getSumByType($tableName,$id,$type){
        if($type=="NEFT"){
            $sql="SELECT sum(paidAmount) as amt FROM `billpayments` WHERE billId=".$id." and paymentMode='".$type."' and isLostStatus=2";    
            $query = $this->db->query($sql);
            $this->db->close();
            $this->db->initialize();
            return $query->result_array();
        }else if($type=="Cheque"){
            $sql="SELECT sum(paidAmount) as amt FROM `billpayments` WHERE billId=".$id." and paymentMode='".$type."' and isLostStatus=2";    
            $query = $this->db->query($sql);
            $this->db->close();
             $this->db->initialize();
            return $query->result_array(); 
        }else{
            $sql="SELECT sum(paidAmount) as amt FROM `billpayments` WHERE billId=".$id." and paymentMode='".$type."'";    
            $query = $this->db->query($sql);
            $this->db->close();
        $this->db->initialize();
            return $query->result_array(); 
        }
        
    }

    public function paginationOutstandingBills($tableName,$limit, $start, $st = "", $orderField, $orderDirection){
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
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('bills.deliveryStatus !=',"cancelled");
        $this->db->group_by('bills.id');
        $this->db->order_by($orderField, $orderDirection);
        $this->db->limit($limit, $start);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function countPaginationOutstandingBills($tableName,$limit, $start, $st = "", $orderField, $orderDirection){
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
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('bills.deliveryStatus !=',"cancelled");
        $this->db->group_by('bills.id');
        $this->db->order_by($orderField, $orderDirection);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->num_rows();
    }

    public function salesmanProvisionalOutstandingBills($tableName,$userId,$limit, $start, $st = "", $orderField, $orderDirection){
        $this->db->distinct();
        $this->db->select('bills.*');
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id');
        $this->db->join('employee e1','e1.id=allocations.fieldStaffCode1','left outer');
        $this->db->join('employee e2','e2.id=allocations.fieldStaffCode2','left outer');
        $this->db->join('employee e3','e3.id=allocations.fieldStaffCode3','left outer');
        $this->db->join('employee e4','e4.id=allocations.fieldStaffCode4','left outer');
        $this->db->from($tableName);
        $this->db->group_start();
        $this->db->like('billNo', $st);
        $this->db->or_like('bills.date', $st);
        $this->db->or_like('retailerName', $st);
        $this->db->or_like('netAmount', $st);
        $this->db->or_like('compName', $st);
        $this->db->or_like('pendingAmt', $st);
        $this->db->group_end();
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('bills.deliveryStatus !=',"cancelled");
        $this->db->where('e1.id',$userId);
        $this->db->or_where('e2.id',$userId);
        $this->db->or_where('e3.id',$userId);
        $this->db->or_where('e4.id',$userId);
        $this->db->group_by('bills.id');
        $this->db->order_by($orderField, $orderDirection);
        $this->db->limit($limit, $start);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function countSalesmanProvisionalOutstandingBills($tableName,$userId,$limit, $start, $st = "", $orderField, $orderDirection){
        $this->db->distinct();
        $this->db->select('bills.*');
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id');
        $this->db->join('employee e1','e1.id=allocations.fieldStaffCode1','left outer');
        $this->db->join('employee e2','e2.id=allocations.fieldStaffCode2','left outer');
        $this->db->join('employee e3','e3.id=allocations.fieldStaffCode3','left outer');
        $this->db->join('employee e4','e4.id=allocations.fieldStaffCode4','left outer');
        $this->db->from($tableName);
        $this->db->group_start();
        $this->db->like('billNo', $st);
        $this->db->or_like('bills.date', $st);
        $this->db->or_like('retailerName', $st);
        $this->db->or_like('netAmount', $st);
        $this->db->or_like('compName', $st);
        $this->db->or_like('pendingAmt', $st);
        $this->db->group_end();
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('bills.deliveryStatus !=',"cancelled");
        $this->db->where('e1.id',$userId);
        $this->db->or_where('e2.id',$userId);
        $this->db->or_where('e3.id',$userId);
        $this->db->or_where('e4.id',$userId);
        $this->db->group_by('bills.id');
        $this->db->order_by($orderField, $orderDirection);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->num_rows();
    }

    public function salesmanPaginationOutstandingBills($tableName,$userId,$limit, $start, $st = "", $orderField, $orderDirection){
        $this->db->distinct();
        $this->db->select('bills.*');
        $this->db->join('salesman_linking','bills.salesmanCode=salesman_linking.salesmanCode');
        $this->db->join('employee e1','e1.id=salesman_linking.employeeId');
        $this->db->from($tableName);
        $this->db->group_start();
        $this->db->like('billNo', $st);
        $this->db->or_like('date', $st);
        $this->db->or_like('retailerName', $st);
        $this->db->or_like('netAmount', $st);
        $this->db->or_like('compName', $st);
        $this->db->or_like('pendingAmt', $st);
        $this->db->group_end();
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('bills.deliveryStatus !=',"cancelled");
        $this->db->where('e1.id',$userId);
        $this->db->group_by('bills.id');
        $this->db->order_by($orderField, $orderDirection);
        $this->db->limit($limit, $start);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function countSalesmanPaginationOutstandingBills($tableName,$userId,$limit, $start, $st = "", $orderField, $orderDirection){
        $this->db->distinct();
        $this->db->select('bills.*');
        $this->db->join('salesman_linking','bills.salesmanCode=salesman_linking.salesmanCode');
        $this->db->join('employee e1','e1.id=salesman_linking.employeeId');
        $this->db->from($tableName);
        $this->db->group_start();
        $this->db->like('billNo', $st);
        $this->db->or_like('date', $st);
        $this->db->or_like('retailerName', $st);
        $this->db->or_like('netAmount', $st);
        $this->db->or_like('compName', $st);
        $this->db->or_like('pendingAmt', $st);
        $this->db->group_end();
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('bills.deliveryStatus !=',"cancelled");
        $this->db->where('e1.id',$userId);
        $this->db->group_by('bills.id');
        $this->db->order_by($orderField, $orderDirection);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->num_rows();
    }

    public function paginationAllBills($tableName,$limit, $start, $st = "", $orderField, $orderDirection){
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
        $this->db->where('bills.deliveryStatus !=',"cancelled");
        $this->db->group_by('bills.id');
        $this->db->order_by($orderField, $orderDirection);
        $this->db->limit($limit, $start);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function countPaginationAllBills($tableName,$limit, $start, $st = "", $orderField, $orderDirection){
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
        // $this->db->where('bills.pendingAmt >',0);
        $this->db->where('bills.deliveryStatus !=',"cancelled");
        $this->db->group_by('bills.id');
        $this->db->order_by($orderField, $orderDirection);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->num_rows();
    }

    public function getdata($tableName)
    {
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getSalesman($tblName){
        $this->db->distinct();
        $this->db->select('salesman');
        $data=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $data->result_array();
    }

    public function loadRetailer($id) {
        $sql="select * from retailer where code='".$id."'";
        $query = $this->db->query($sql);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();   
    } 
    
    public function getCurrentOpenAllocations($tblName){
         $this->db->select('allocations.*,route.name as rname');
        $this->db->join('route','route.id=allocations.routId');
        $this->db->where('fsStatus',0);
        $this->db->where('isAllocationComplete',0);
        $query=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getDetails($tblName){
        $this->db->select('allocations.id as allocation_Id,allocations.company as compName,allocations.allocationCode as code,allocations.date as startDate,allocations.allocationCloseAt as endDate,employee.id as empId,employee.name as empName,route.name as routeName');
        $this->db->join('allocations','allocations.id=allocation_sr_details.allocationId');
        $this->db->join('employee','employee.id=allocations.fieldStaffCode1');
         $this->db->join('route','route.id=allocations.routId');
        $this->db->group_by('allocation_sr_details.allocationId');
        $query=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getOfficeSr($tblName){
        $this->db->select('allocation_sr_details.id as sr_id,allocation_sr_details.quantity as sr_qty,bills.id as billId,bills.billNo as billNo,billsdetails.productName as prodName');
        $this->db->join('bills','bills.id=allocation_sr_details.billId');
        $this->db->join('billsdetails','billsdetails.id=allocation_sr_details.billItemId');
        $this->db->where('allocation_sr_details.allocationId',0);
        $query=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getAllocationSrDetails($tblName,$id){
        $this->db->select('allocation_sr_details.id as sr_id,allocation_sr_details.quantity as sr_qty,bills.id as billId,bills.compName as compName,bills.billNo as billNo,bills.retailerName as retailerName,bills.retailerCode as retailerCode,billsdetails.productName as prodName');
        $this->db->join('bills','bills.id=allocation_sr_details.billId');
        $this->db->join('billsdetails','billsdetails.id=allocation_sr_details.billItemId');
        $this->db->where('allocation_sr_details.allocationId',$id);
        $query=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getAllocationDetailsOutstanding($tblName,$id){
        $this->db->select('allocations.id, allocations.allocationCode');
        $this->db->join('allocationsbills','bills.id=allocationsbills.billId','left outer');
        $this->db->join('allocations','allocations.id=allocationsbills.allocationId','left outer');
        $this->db->where('bills.id',$id);
        $this->db->where('allocations.isAllocationComplete','0');
        $this->db->order_by('allocations.id','desc');
        $this->db->limit(1); 
        $query=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
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
        $this->db->select('allocationsbills.*,allocations.id, allocations.allocationCode');
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

    public function getAllocatedBillInfo($tableName,$id){
        $this->db->select('allocations.*,employee.name as ename,route.name as rname');
        $this->db->join('employee','allocations.fieldstaffCode1=employee.id');
        $this->db->join('route','route.id=allocations.routId');
        $this->db->where('allocations.id',$id);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getAllAllocations($tableName)
    {
        $this->db->distinct();
        $this->db->select('allocations.*,route.name as routeName');
      
        $this->db->join('allocationsbills','allocationsbills.allocationId=allocations.id');
        $this->db->join('billsdetails','allocationsbills.billId=billsdetails.billId');
        
        $this->db->join('route','route.id=allocations.routId');
        $this->db->join('bills','bills.id=billsdetails.billId');
        $this->db->where('billsdetails.managerSrStatus',2);
        $this->db->from($tableName);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    } 

    public function getAllUsr($tableName)
    {
        $this->db->distinct();
        $this->db->select('allocations.*,route.name as routeName');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.allocationId=allocations.id');
        $this->db->join('billsdetails','allocationsbills.billId=billsdetails.billId');
        
        $this->db->join('route','route.id=allocations.routId');
        $this->db->join('bills','bills.id=billsdetails.billId');
        $this->db->where('billsdetails.managerSrStatus',3);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    } 

    public function getRouteName($code){
        $this->db->select('name'); 
        $this->db->from('route');
        $this->db->where('code',$code);   
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getEmployeeNamesByID($id){
        $this->db->select('name'); 
        $this->db->from('employee');
        $this->db->where('id',$id);   
        return $this->db->get()->row()->name;
    }
    public function getEmployeeIdByName($name){
        $this->db->select('id'); 
        $this->db->from('employee');
        $this->db->where('name',$name);   
        return $this->db->get()->row()->id;
    }

    public function insert($tblName, $data) {      
        
        $this->db->insert($tblName, $data);
        return $this->db->insert_id();
    }
    public function update($tblName, $data, $id) {
        $this->db->where('id', $id);
        return $this->db->update($tblName, $data);  
    }
    

    public function updateLostChequeStatus($tblName,$data,$id,$allocationId,$mode) {
        $this->db->where('billId', $id);
        $this->db->where('allocationId', $allocationId);
        $this->db->where('paymentMode', $mode);
        return $this->db->update($tblName, $data);  
    }

    public function updateLostbillStatus($tblName,$data,$id,$allocationId) {
        $this->db->where('billId', $id);
        $this->db->where('allocationId', $allocationId);
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

    public function getGstNo($tblName, $name) {
        $this->db->where('name', $name);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();   
    }

    public function loadByBillId($tblName, $id) {
        $this->db->where('billId', $id);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();   
    }

    public function loadBill($tableName,$id){
        $this->db->select("bills.*");
        $this->db->join('billsdetails','bills.id=billsdetails.billId');
        $this->db->where('billsdetails.id',$id);
        $result=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $result->result_array();
    }
     
    public function prodDetails($tableName,$name){
        $this->db->where('name',$name);
        $result=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $result->result_array();
    } 
     
     public function getdata1($tableName)
    {
        $this->db->select("bills.*,bills.retailerName as name");
        // $this->db->join("retailer","bills.retailerCode = retailer.code");
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }
     public function getSalesReturnDS($tableName)
    {
        $this->db->select("bills.*,bills.retailerName as name");
        // $this->db->join("retailer","bills.retailerCode = retailer.code");
        $this->db->where("billType","deliveryslip");
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

      public function getdataBillsDetails($tableName)
    {
        $this->db->select("billsdetails.*,bills.retailerName as name,bills.billNo as billNo,bills.date as date");
        $this->db->join("bills","billsdetails.billId = bills.id");
        // $this->db->join("retailer","bills.retailerCode = retailer.code");
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }
    public function loadBillDetails($tableName, $id)
    {
        $this->db->select("billsdetails.*,bills.billNo as billNo,bills.date as Date,bills.retailerName as name");
        $this->db->join("bills","billsdetails.billId = bills.id");
        // $this->db->join("retailer","bills.retailerId = retailer.id");
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
        // $this->db->set('receivedAmt','receivedAmt+'.$amt,false);
        $this->db->set('pendingAmt','pendingAmt-'.$amt,false);
        $this->db->where('id',$id);
        return $this->db->update($tableName);
    }

    public function updateAvailQty($tableName,$qty,$name){
        $this->db->set('availQty','availQty+'.$qty,false);
         $this->db->set('blockQty','blockQty+'.$qty,false);
        $this->db->where('name',$name);
        return $this->db->update($tableName);
    }

    public function EmployeeName($code) {
        $this->db->select('name'); 
        $this->db->from('employee');
        return $this->db->get()->result_array();
    }

    //SR Check Methods
    public function getAllocatedBillsType($tableName,$id){
        $this->db->select('billsdetails.*,bills.billNo as billNo,bills.retailerName as retailerName,bills.salesman as salesman,bills.fsbillStatus as FsBillStatus,bills.creditAdjustment as creditAdjustment');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=billsdetails.billId');
        $this->db->join('bills','bills.id=billsdetails.billId');
        $this->db->where('allocationsbills.allocationId',$id);
        $this->db->where('billsdetails.fsReturnQty >',0);
        $this->db->where('billsdetails.gkStatus',1);
        $this->db->where('billsdetails.managerSrStatus !=',2);
        $this->db->like('FsBillStatus', 'SR');
         $this->db->not_like('FsBillStatus', 'FSR');
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
   }

    public function getSignedBills($tableName,$id){
        $this->db->distinct();
        $this->db->select('bills.*');
        $this->db->from($tableName);
        $this->db->join('billsdetails','bills.id=billsdetails.billId','left outer');
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
        $this->db->where('bills.pendingAmt !=',0);
        $this->db->where('bills.fsbillStatus !=','Resend');
        // $this->db->where('bills.fsbillStatus !=','FSR');
        
        $this->db->where('allocationsbills.allocationId',$id);
        // $this->db->not_like('bills.fsbillStatus','SR');
        
        // $this->db->where('bills.isLostBill',0);
        // $this->db->where('billsdetails.signBillAcceptStatus !=',2);
        //  $this->db->where('billsdetails.signBillAcceptStatus !=',1);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getSignedBillsWithCheck($tableName,$id){
        $this->db->distinct();
        $this->db->select('bills.*');
        $this->db->from($tableName);
        $this->db->join('billsdetails','bills.id=billsdetails.billId','left outer');
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
        $this->db->where('bills.pendingAmt !=',0);
        $this->db->where('bills.fsbillStatus !=','Resend');
        $this->db->where('allocationsbills.allocationId',$id);
        $this->db->where('bills.isLostBill',0);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }


     public function checkSignedBills($tableName,$id){
        $this->db->distinct();
        $this->db->select('bills.*');
        $this->db->from($tableName);
        $this->db->join('billsdetails','bills.id=billsdetails.billId');
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
        $this->db->where('bills.pendingAmt >','0.00');
        $this->db->not_like('bills.fsbillStatus','Resend');
        $this->db->not_like('bills.fsbillStatus','FSR');
        $this->db->where('allocationsbills.allocationId',$id);
        $this->db->where('bills.isLostBill',0);
        // $this->db->where('billsdetails.signBillAcceptStatus !=',2);
        //  $this->db->where('billsdetails.signBillAcceptStatus !=',1);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

   public function getResendBills($tableName,$id){
        $this->db->distinct();
        $this->db->select('bills.*');
        $this->db->from($tableName);
         $this->db->join('billsdetails','bills.id=billsdetails.billId');
        $this->db->join('allocationsbills','allocationsbills.billId=billsdetails.billId');
        $this->db->where('allocationsbills.allocationId',$id);
        $this->db->like('FsBillStatus', 'Resend');
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
   }

   public function getAllocatedBillsFSR($tableName,$id){
        $this->db->distinct();
        $this->db->select('bills.id as id,bills.billNo as billNo,bills.retailerName as retailerName,bills.fsbillStatus as FsBillStatus,bills.creditAdjustment as creditAdjustment');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=billsdetails.billId');
        $this->db->join('bills','bills.id=billsdetails.billId');
        $this->db->where('allocationsbills.allocationId',$id);
        $this->db->where('billsdetails.fsReturnQty >',0);
        $this->db->where('billsdetails.gkStatus',1);
        $this->db->where('billsdetails.managerSrStatus !=',2);
        $this->db->where('billsdetails.managerSrStatus !=',3);
        $this->db->like('bills.fsbillStatus', 'FSR');
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
   }

   public function loadSrBills($tableName,$id){
        $this->db->select('billsdetails.*,bills.billNo as billNo,bills.retailerName as retailerName');
        $this->db->join('bills','bills.id=billsdetails.billId');
        $this->db->where('billsdetails.id',$id);
        $this->db->from($tableName);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
   }

   public function loadFsrBills($tableName,$id){
        $this->db->select('billsdetails.*,bills.billNo as billNo,bills.retailerName as retailerName');
        $this->db->from($tableName);
        $this->db->join('bills','bills.id=billsdetails.billId');
        $this->db->where('billsdetails.billId',$id);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
   }

   public function debitData($tableName,$id){
        $this->db->select("allocations.*");
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=billsdetails.billId');
        $this->db->join('allocations','allocations.id=allocationsbills.allocationId');
        $this->db->where('billsdetails.id',$id);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
   }

   public function loadAllBillData($tableName,$id){
        $this->db->select('billsdetails.*,route.name as routeName,bills.billNo as billNo,bills.retailerName as retailerName');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=billsdetails.billId');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id');
        $this->db->join('route','route.id=allocations.routId');
        $this->db->join('bills','bills.id=billsdetails.billId');
        $this->db->where('billsdetails.id',$id);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
   }

   public function loadAllBillDetails($tableName,$id){
        $this->db->select('billsdetails.*,route.name as routeName,bills.billNo as billNo,bills.retailerName as retailerName');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=billsdetails.billId');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id');
        $this->db->join('route','route.id=allocations.routId');
        $this->db->join('bills','bills.id=billsdetails.billId');
        $this->db->where('billsdetails.billId',$id);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
   }

    public function loadSrDetail($tableName,$id){
        $this->db->select('billsdetails.*,allocations.allocationCode as AllocationCode,route.name as routeName,bills.billNo as billNo,bills.retailerName as retailerName,bills.pendingAmt as pendingAmt');
        
        $this->db->join('allocationsbills','allocationsbills.billId=billsdetails.billId');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id');
        $this->db->join('route','route.id=allocations.routId');
        $this->db->join('bills','bills.id=billsdetails.billId');
        $this->db->where('billsdetails.managerSrStatus',2);
        $this->db->where('allocations.id',$id);
         $this->db->where('billsdetails.fsReturnAmt >',0);
        // $this->db->like('FsBillStatus', 'SR');
        // $this->db->not_like('FsBillStatus', 'FSR');
        $this->db->from($tableName);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function loadFsrDetail($tableName,$id){
        $this->db->distinct();
        $this->db->select('allocations.allocationCode as AllocationCode,bills.compName as compName,bills.id,bills.billNo as billNo,bills.retailerName as retailerName,route.name as routeName');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=billsdetails.billId');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id');
        $this->db->join('route','route.id=allocations.routId');
        $this->db->join('bills','bills.id=billsdetails.billId');
        $this->db->where('billsdetails.managerSrStatus',2);
        //  $this->db->where('billsdetails.fsReturnAmt >',0);
        $this->db->where('allocations.id',$id);
        $this->db->like('FsBillStatus', 'FSR');
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
   }

    public function loadHistoricalSrDetail($tableName){
        $this->db->select('billsdetails.*,allocations.allocationCode as AllocationCode,bills.compName as compName,route.name as routeName,bills.billType as billType,bills.billNo as billNo,bills.retailerName as retailerName,bills.pendingAmt as pendingAmt');
        
        $this->db->join('allocationsbills','allocationsbills.billId=billsdetails.billId');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id');
        $this->db->join('route','route.id=allocations.routId');
        $this->db->join('bills','bills.id=billsdetails.billId');
        $this->db->where('billsdetails.managerSrStatus',2);
        $this->db->where('billsdetails.fsReturnAmt >',0);
        // $this->db->where('allocations.id',$id);
        // $this->db->like('FsBillStatus', 'SR');
        // $this->db->not_like('FsBillStatus', 'FSR');
        $this->db->from($tableName);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }
    
    public function loadHistoricalSrDetailWithCRB($tableName,$cmpName,$retName,$billType){
        $this->db->select('billsdetails.*,allocations.allocationCode as AllocationCode,bills.compName as compName,route.name as routeName,bills.billType as billType,bills.billNo as billNo,bills.retailerName as retailerName,bills.pendingAmt as pendingAmt');
        
        $this->db->join('allocationsbills','allocationsbills.billId=billsdetails.billId');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id');
        $this->db->join('route','route.id=allocations.routId');
        $this->db->join('bills','bills.id=billsdetails.billId');
        $this->db->where('billsdetails.managerSrStatus',2);
        $this->db->where('billsdetails.fsReturnAmt >',0);
        $this->db->where('bills.compName',$cmpName);
        $this->db->or_where('bills.retailerName',$retName);
        $this->db->or_where('bills.billType',$billType);
        // $this->db->like('FsBillStatus', 'SR');
        // $this->db->not_like('FsBillStatus', 'FSR');
        $this->db->from($tableName);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }
    
    public function loadHistoricalFsrDetail($tableName){
        $this->db->distinct();
        $this->db->select('allocations.allocationCode as AllocationCode,bills.id,bills.compName as compName,bills.billType as billType,bills.billNo as billNo,bills.retailerName as retailerName,route.name as routeName');
       
        $this->db->join('allocationsbills','allocationsbills.billId=billsdetails.billId');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id');
        $this->db->join('route','route.id=allocations.routId');
        $this->db->join('bills','bills.id=billsdetails.billId');
         // $this->db->join('provisionaldebit','provisionaldebit.billDetailId=billsdetails.id');
        // $this->db->join('employee','provisionaldebit.salesmanId=employee.id');
        $this->db->where('billsdetails.managerSrStatus',2);
        // $this->db->where('allocations.id',$id);
        $this->db->like('FsBillStatus', 'FSR');
         $this->db->from($tableName);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function loadCurrentSrDetail($tableName){
        $this->db->select('billsdetails.*,allocations.allocationCode as AllocationCode,bills.compName as compName,route.name as routeName,bills.billNo as billNo,bills.retailerName as retailerName,bills.pendingAmt as pendingAmt');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=billsdetails.billId');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id');
        $this->db->join('route','route.id=allocations.routId');
        $this->db->join('bills','bills.id=billsdetails.billId');
        $this->db->where('billsdetails.managerSrStatus',2);
         $this->db->where('billsdetails.fsReturnAmt >',0);
        // $this->db->where('allocations.id',$id);
        // $this->db->like('FsBillStatus', 'SR');
        // $this->db->not_like('FsBillStatus', 'FSR');
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function loadCurrentFsrDetail($tableName){
        $this->db->distinct();
        $this->db->select('allocations.allocationCode as AllocationCode,bills.id,bills.billNo as billNo,bills.retailerName as retailerName,route.name as routeName');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=billsdetails.billId');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id');
        $this->db->join('route','route.id=allocations.routId');
        $this->db->join('bills','bills.id=billsdetails.billId');
         // $this->db->join('provisionaldebit','provisionaldebit.billDetailId=billsdetails.id');
        // $this->db->join('employee','provisionaldebit.salesmanId=employee.id');
        $this->db->where('billsdetails.managerSrStatus',2);
        // $this->db->where('allocations.id',$id);
        $this->db->like('FsBillStatus', 'FSR');
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
   }

  // public function lostCheque($tableName){
  //       $this->db->distinct();
  //       $this->db->select('bills.*,route.name as rname,employee.name as ename,billpayments.id as bpayId,billpayments.paidAmount as paidAmt,billpayments.paymentMode as payMode,DATE(billpayments.date) as bdate');
  //       $this->db->from($tableName);
  //       $this->db->join('billpayments','bills.id=billpayments.billId');
  //       $this->db->join('allocations','allocations.id=billpayments.allocationId');
  //       $this->db->join('allocationsbills','allocations.id=allocationsbills.allocationId');
  //        $this->db->join('route','route.id=allocations.routId');
  //        $this->db->join('employee','employee.id=allocations.fieldstaffCode1');
  //       $this->db->where('billpayments.isLostStatus',1);
  //       $this->db->where('billpayments.paymentMode','Cheque');
  //       $this->db->where('bills.pendingAmt >',0);
  //       $this->db->order_by('billpayments.id','desc');
  //       $this->db->group_by('bills.id');
  //       $query=$this->db->get();
  //       return $query->result_array();
  // }

    public function lostCheque($tblName){
        $qry="SELECT distinct bills.*,route.name as rname,employee.name as ename,billpayments.id as bpayId,billpayments.paidAmount as paidAmt,billpayments.paymentMode as payMode,DATE(billpayments.date) as bdate from billpayments join bills on bills.id=billpayments.billId join allocations on allocations.id=billpayments.allocationId join allocationsbills on allocations.id=allocationsbills.allocationId join route on route.id=allocations.routId join employee on employee.id=allocations.fieldstaffCode1 where (billpayments.id in (SELECT MAX(billpayments.id) from billpayments where billpayments.paymentMode='Cheque' and billpayments.isLostStatus=1 and billpayments.paidAmount >0 GROUP by billpayments.billId)) and bills.pendingAmt >0 GROUP by bills.id ORDER by billpayments.id desc";
        $query=$this->db->query($qry);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

     public function lostChequeWithComp($tblName,$comp){
        $qry="SELECT distinct bills.*,route.name as rname,employee.name as ename,billpayments.id as bpayId,billpayments.paidAmount as paidAmt,billpayments.paymentMode as payMode,DATE(billpayments.date) as bdate from billpayments join bills on bills.id=billpayments.billId join allocations on allocations.id=billpayments.allocationId join allocationsbills on allocations.id=allocationsbills.allocationId join route on route.id=allocations.routId join employee on employee.id=allocations.fieldstaffCode1 where (billpayments.id in (SELECT MAX(billpayments.id) from billpayments where billpayments.paymentMode='Cheque' and billpayments.isLostStatus=1 and billpayments.paidAmount >0 GROUP by billpayments.billId)) and bills.pendingAmt >0 and bills.compName='$comp' GROUP by bills.id ORDER by billpayments.id desc";
        $query=$this->db->query($qry);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }
    
    // public function lostNeft($tblName){
    //     $qry="SELECT distinct bills.*,route.name as rname,employee.name as ename,billpayments.id as bpayId,billpayments.paidAmount as paidAmt,billpayments.paymentMode as payMode,DATE(billpayments.date) as bdate from billpayments join bills on bills.id=billpayments.billId join allocations on allocations.id=billpayments.allocationId join allocationsbills on allocations.id=allocationsbills.allocationId join route on route.id=allocations.routId join employee on employee.id=allocations.fieldstaffCode1 where (billpayments.id in (SELECT MAX(billpayments.id) from billpayments where billpayments.paymentMode='NEFT' and billpayments.isLostStatus=1 and billpayments.paidAmount >0 GROUP by billpayments.billId)) and bills.pendingAmt >0 GROUP by bills.id ORDER by billpayments.id desc";
    //     $query=$this->db->query($qry);
    //     return $query->result_array();
    // }
    
    // public function lostNeft($tblName){
    //     $qry="SELECT distinct bills.*,route.name as rname,employee.name as ename,billpayments.id as bpayId,billpayments.paidAmount as paidAmt,billpayments.paymentMode as payMode,DATE(billpayments.date) as bdate from billpayments join bills on bills.id=billpayments.billId join allocations on allocations.id=billpayments.allocationId join allocationsbills on allocations.id=allocationsbills.allocationId join route on route.id=allocations.routId join employee on employee.id=allocations.fieldstaffCode1 where (billpayments.paymentMode='NEFT') and (billpayments.isLostStatus=1 and bills.pendingAmt >0) GROUP by bills.id ORDER by billpayments.id desc";
    //     $query=$this->db->query($qry);
    //     return $query->result_array();
    // }

    public function checkHistory($tblName,$billId,$allocationId){
        $this->db->where('billId', $billId);
        $this->db->where('allocationId', $allocationId);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    public function lostNeft($tblName){
        $qry="SELECT distinct bills.*,employee.name as ename,billpayments.id as bpayId,billpayments.paidAmount as paidAmt,billpayments.paymentMode as payMode,DATE(billpayments.date) as bdate from bills join billpayments on bills.id=billpayments.billId join allocations on allocations.id=billpayments.allocationId join allocationsbills on allocations.id=allocationsbills.allocationId join employee on employee.id=allocations.fieldstaffCode1 where (billpayments.paymentMode='NEFT') and (billpayments.isLostStatus=1 and bills.pendingAmt >0) GROUP by bills.id ORDER by billpayments.id desc";
        $query=$this->db->query($qry);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }
    

    //  public function lostNeft($tblName){
    //     $qry="SELECT distinct bills.*,route.name as rname,employee.name as ename,billpayments.id as bpayId,billpayments.paidAmount as paidAmt,billpayments.paymentMode as payMode,DATE(billpayments.date) as bdate from billpayments join bills on bills.id=billpayments.billId join allocations on allocations.id=billpayments.allocationId join allocationsbills on allocations.id=allocationsbills.allocationId join route on route.id=allocations.routId join employee on employee.id=allocations.fieldstaffCode1 where (billpayments.id in (SELECT MAX(billpayments.id) from billpayments where billpayments.paymentMode='NEFT' and billpayments.isLostStatus=1 and billpayments.paidAmount >0 GROUP by billpayments.billId)) and bills.pendingAmt >0 GROUP by bills.id ORDER by billpayments.id desc";
    //     $query=$this->db->query($qry);
    //     return $query->result_array();
    // }

    public function lostNeftWithComp($tblName,$comp){
        $qry="SELECT distinct bills.*,route.name as rname,employee.name as ename,billpayments.id as bpayId,billpayments.paidAmount as paidAmt,billpayments.paymentMode as payMode,DATE(billpayments.date) as bdate from billpayments join bills on bills.id=billpayments.billId join allocations on allocations.id=billpayments.allocationId join allocationsbills on allocations.id=allocationsbills.allocationId join route on route.id=allocations.routId join employee on employee.id=allocations.fieldstaffCode1 where (billpayments.id in (SELECT MAX(billpayments.id) from billpayments where billpayments.paymentMode='NEFT' and billpayments.isLostStatus=1 and billpayments.paidAmount >0 GROUP by billpayments.billId)) and bills.pendingAmt >0 and bills.compName='$comp' GROUP by bills.id ORDER by billpayments.id desc";
        $query=$this->db->query($qry);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

//   public function lostNeft($tableName){
//         $this->db->distinct();
//         $this->db->select('bills.*,route.name as rname,employee.name as ename,billpayments.id as bpayId,billpayments.paidAmount as paidAmt,billpayments.paymentMode as payMode,DATE(billpayments.date) as bdate');
//         $this->db->from($tableName);
//         $this->db->join('billpayments','bills.id=billpayments.billId');
//          $this->db->join('allocations','allocations.id=billpayments.allocationId');
//           $this->db->join('allocationsbills','allocations.id=allocationsbills.allocationId');
//          $this->db->join('route','route.id=allocations.routId');
//          $this->db->join('employee','employee.id=allocations.fieldstaffCode1');
//         $this->db->where('billpayments.isLostStatus',1);
//         $this->db->where('billpayments.paymentMode','NEFT');
//         $this->db->where('bills.pendingAmt >',0);
//         $this->db->order_by('billpayments.id','desc');
//         $this->db->group_by('bills.id');
//         $query=$this->db->get();
//         return $query->result_array();
//   }

    public function loadOutstandingBills($tableName){
        $this->db->distinct();
        $this->db->select('bills.*');
        $this->db->from($tableName);
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('bills.deliveryStatus !=',"cancelled");
        $this->db->group_by('bills.id');
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

      public function loadAllBills($tableName){
        $this->db->distinct();
        $this->db->select('bills.*');
        $this->db->from($tableName);
        // $this->db->where('bills.pendingAmt >',0);
        $this->db->where('bills.deliveryStatus !=',"cancelled");
        $this->db->group_by('bills.id');
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function loadOutstandingBillsByCompany($tableName,$company){
        $this->db->distinct();
        $this->db->select('bills.*');
        $this->db->from($tableName);
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('bills.deliveryStatus !=',"cancelled");
        $this->db->where('bills.compName',$company);
        $this->db->group_by('bills.id');
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

   public function loadLostBills($tableName){
        $this->db->distinct();
        $this->db->select('bills.*,employee.name as ename,route.name as rname,DATE(allocations.date) as entryDate');
        $this->db->from($tableName);
        // $this->db->join('billsdetails','bills.id=billsdetails.billId','left outer');
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id');
         $this->db->join('employee','employee.id=allocations.fieldStaffCode1','left outer');
        $this->db->join('route','route.id=allocations.routId','left outer');
        $this->db->where('allocationsbills.isLostBill',1);
        $this->db->where('bills.pendingAmt >',0);
        $this->db->order_by('allocations.id','asc');
        $this->db->group_by('bills.id');
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
   }

   public function loadLostBillsWithComp($tableName,$company){
        $this->db->distinct();
        $this->db->select('bills.*,employee.name as ename,route.name as rname,DATE(allocations.date) as entryDate');
        $this->db->from($tableName);
        // $this->db->join('billsdetails','bills.id=billsdetails.billId','left outer');
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id');
         $this->db->join('employee','employee.id=allocations.fieldStaffCode1','left outer');
        $this->db->join('route','route.id=allocations.routId','left outer');
        $this->db->where('allocationsbills.isLostBill',1);
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('bills.compName',$company);
        $this->db->order_by('allocations.id','asc');
        $this->db->group_by('bills.id');
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
   }

   public function salesmanloadLostBills($tableName,$userId){
        $this->db->distinct();
        $this->db->select('bills.*,e1.name as ename,route.name as rname,DATE(allocations.date) as entryDate');
        $this->db->from($tableName);
        // $this->db->join('billsdetails','bills.id=billsdetails.billId','left outer');
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id');
        $this->db->join('employee e1','e1.id=allocations.fieldStaffCode1','left outer');
        $this->db->join('employee e2','e2.id=allocations.fieldStaffCode2','left outer');
        $this->db->join('employee e3','e3.id=allocations.fieldStaffCode3','left outer');
        $this->db->join('employee e4','e4.id=allocations.fieldStaffCode4','left outer');
        $this->db->join('route','route.id=allocations.routId','left outer');
        $this->db->where('allocationsbills.isLostBill',1);
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('e1.id',$userId);
        $this->db->or_where('e2.id',$userId);
        $this->db->or_where('e3.id',$userId);
        $this->db->or_where('e4.id',$userId);
        $this->db->order_by('allocations.id','desc');
        $this->db->group_by('bills.id');
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
   }

   public function salesmanloadLostBillsWithComp($tableName,$company,$userId){
        $this->db->distinct();
        $this->db->select('bills.*,e1.name as ename,route.name as rname,DATE(allocations.date) as entryDate');
        $this->db->from($tableName);
        // $this->db->join('billsdetails','bills.id=billsdetails.billId','left outer');
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id');
        $this->db->join('employee e1','e1.id=allocations.fieldStaffCode1','left outer');
        $this->db->join('employee e2','e2.id=allocations.fieldStaffCode2','left outer');
        $this->db->join('employee e3','e3.id=allocations.fieldStaffCode3','left outer');
        $this->db->join('employee e4','e4.id=allocations.fieldStaffCode4','left outer');
        $this->db->join('route','route.id=allocations.routId','left outer');
        $this->db->where('allocationsbills.isLostBill',1);
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('bills.compName',$company);
        $this->db->where('e1.id',$userId);
        $this->db->or_where('e2.id',$userId);
        $this->db->or_where('e3.id',$userId);
        $this->db->or_where('e4.id',$userId);
        $this->db->order_by('allocations.id','desc');
        $this->db->group_by('bills.id');
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
   }

    

   public function loadResendBills($tableName){
        $this->db->distinct();
        $this->db->select('bills.*,employee.name as ename,route.name as rname,DATE(allocations.date) as entryDate');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id','left outer');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id','left outer');
        $this->db->join('employee','employee.id=allocations.fieldStaffCode1','left outer');
        $this->db->join('route','route.id=allocations.routId','left outer');
        $this->db->where('bills.isResendBill','1');
         $this->db->where('bills.isBilled','0');
        $this->db->where('bills.pendingAmt >',0);
         $this->db->order_by('allocations.id','desc');
        $this->db->group_by('bills.id');
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
   }

   public function loadResendBillsWithUser($tableName,$userId){
        $this->db->distinct();
        $this->db->select('bills.*,e1.name as ename,route.name as rname,DATE(allocations.date) as entryDate');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id','left outer');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id','left outer');
        $this->db->join('employee e1','e1.id=allocations.fieldStaffCode1','left outer');
        $this->db->join('employee e2','e2.id=allocations.fieldStaffCode2','left outer');
        $this->db->join('employee e3','e3.id=allocations.fieldStaffCode3','left outer');
        $this->db->join('employee e4','e4.id=allocations.fieldStaffCode4','left outer');
        $this->db->join('route','route.id=allocations.routId','left outer');
        $this->db->where('bills.isResendBill','1');
         $this->db->where('bills.isBilled','0');
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('e1.id',$userId);
        $this->db->or_where('e2.id',$userId);
        $this->db->or_where('e3.id',$userId);
        $this->db->or_where('e4.id',$userId);
         $this->db->order_by('allocations.id','desc');
        $this->db->group_by('bills.id');
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
   }

   public function loadResendBillsWithComp($tableName,$company){
        $this->db->distinct();
        $this->db->select('bills.*,employee.name as ename,route.name as rname,DATE(allocations.date) as entryDate');
        $this->db->from($tableName);
        // $this->db->join('billsdetails','bills.id=billsdetails.billId');
        // $this->db->join('allocationsbills','allocationsbills.billId=billsdetails.billId');
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id','left outer');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id','left outer');
        $this->db->join('employee','employee.id=allocations.fieldStaffCode1','left outer');
        $this->db->join('route','route.id=allocations.routId','left outer');
        // $this->db->where('bills.fsbillStatus','Resend');
        $this->db->where('bills.isResendBill','1');
         $this->db->where('bills.isBilled','0');
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('bills.compName',$company);

         $this->db->order_by('allocations.id','desc');
        $this->db->group_by('bills.id');
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
   }

   public function loadResendBillsWithCompWithUser($tableName,$company,$userId){
        $this->db->distinct();
        $this->db->select('bills.*,e1.name as ename,route.name as rname,DATE(allocations.date) as entryDate');
        $this->db->from($tableName);
        // $this->db->join('billsdetails','bills.id=billsdetails.billId');
        // $this->db->join('allocationsbills','allocationsbills.billId=billsdetails.billId');
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id','left outer');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id','left outer');
        $this->db->join('employee','employee.id=allocations.fieldStaffCode1','left outer');
        $this->db->join('route','route.id=allocations.routId','left outer');
        // $this->db->where('bills.fsbillStatus','Resend');
        $this->db->where('bills.isResendBill','1');
         $this->db->where('bills.isBilled','0');
        $this->db->where('bills.pendingAmt >',0);
        $this->db->where('bills.compName',$company);
        $this->db->where('e1.id',$userId);
        $this->db->or_where('e2.id',$userId);
        $this->db->or_where('e3.id',$userId);
        $this->db->or_where('e4.id',$userId);
         $this->db->order_by('allocations.id','desc');
        $this->db->group_by('bills.id');
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
   }
   
   public function loadResendBillsById($tableName,$id){
        $this->db->distinct();
        $this->db->select('bills.*');
        $this->db->from($tableName);
        // $this->db->join('billsdetails','bills.id=billsdetails.billId');
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id','left outer');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id','left outer');
        $this->db->where('bills.fsbillStatus','Resend');
        $this->db->where('bills.id',$id);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
   }
   
   

   public function loadUSrDetail($tableName){
        $this->db->select('billsdetails.*,allocations.allocationCode as AllocationCode,route.name as routeName,bills.billNo as billNo,bills.retailerName as retailerName,bills.pendingAmt as pendingAmt,provisionaldebit.usrAmt as usr,employee.name as empName,provisionaldebit.date as pdDate');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=billsdetails.billId');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id');
        $this->db->join('route','route.id=allocations.routId');
        $this->db->join('bills','bills.id=billsdetails.billId');
         $this->db->join('provisionaldebit','provisionaldebit.billDetailId=billsdetails.id');
         $this->db->join('employee','provisionaldebit.salesmanId=employee.id');
        $this->db->where('billsdetails.managerSrStatus',3);
        // $this->db->where('allocations.id',$id);
        $this->db->like('FsBillStatus', 'SR');
         $this->db->not_like('FsBillStatus', 'FSR');
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
   }

   public function loadUfsrDetail($tableName){
        $this->db->distinct();
        $this->db->select('allocations.allocationCode as AllocationCode,bills.id,bills.billNo as billNo,bills.retailerName as retailerName,route.name as routeName,bills.usr as usr');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=billsdetails.billId');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id');
        $this->db->join('route','route.id=allocations.routId');
        $this->db->join('bills','bills.id=billsdetails.billId');
        $this->db->where('billsdetails.managerSrStatus',3);
        // $this->db->where('allocations.id',$id);
        $this->db->like('FsBillStatus', 'FSR');
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
   }

   public function loadBillUSrDetail($tableName){
        $this->db->select('billsdetails.*,allocations.allocationCode as AllocationCode,route.name as routeName,bills.billNo as billNo,bills.retailerName as retailerName,bills.pendingAmt as pendingAmt,provisionaldebit.usrAmt as usr,employee.name as empName,provisionaldebit.date as pdDate');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=billsdetails.billId');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id');
        $this->db->join('route','route.id=allocations.routId');
        $this->db->join('bills','bills.id=billsdetails.billId');
         $this->db->join('provisionaldebit','provisionaldebit.billDetailId=billsdetails.id');
         $this->db->join('employee','provisionaldebit.salesmanId=employee.id');
        $this->db->where('billsdetails.managerSrStatus',3);
        $this->db->where('provisionaldebit.date BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW()');
        // $this->db->where('allocations.id',$id);
        $this->db->like('FsBillStatus', 'SR');
         $this->db->not_like('FsBillStatus', 'FSR');
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
   }

   public function loadBillUfsrDetail($tableName){
        $this->db->distinct();
        $this->db->select('allocations.allocationCode as AllocationCode,bills.id,bills.billNo as billNo,bills.retailerName as retailerName,route.name as routeName,bills.usr as usr');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=billsdetails.billId');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id');
        $this->db->join('route','route.id=allocations.routId');
        $this->db->join('bills','bills.id=billsdetails.billId');
        $this->db->where('billsdetails.managerSrStatus',3);
        // $this->db->where('provisionaldebit.date >=','NOW() - INTERVAL 1 DAY');
        // $this->db->where('allocations.id',$id);
        $this->db->like('FsBillStatus', 'FSR');
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
   }
   
    //outstanding
    public function getdataOutstandingBill($tblName) {
        $this->db->where('pendingAmt >', 0);
        $this->db->where('billType', 'deliveryslip');
        $this->db->order_by('date', 'desc');
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();   
    } 
}
?>