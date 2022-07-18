<?php 
class GodownKeeperModel extends CI_Model{

	public function __construct(){
		$this->load->database();
	}

	public function getdata($tableName)
    {
        $this->db->order_by('id','desc');
        $query=$this->db->get($tableName);
        
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
    
    public function getAllocationDetails($tableName)
    {
        $this->db->order_by('id','desc');
        $this->db->where('gkStatus','0');
        $query=$this->db->get($tableName);
        
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
        $this->db->where('allocationsbills.allocationId',$id);
        $query=$this->db->get();
        
        return $query->result_array();
    }

    public function getAllocatedBillInfo($tableName,$id){
        $this->db->distinct();
        $this->db->select('allocations.*,e1.name as ename,e2.name as ename2,e3.name as ename3,e4.name as ename4,route.name as rname');
        $this->db->join('employee e1','allocations.fieldstaffCode1=e1.id','left outer');
        $this->db->join('employee e2','allocations.fieldstaffCode2=e2.id','left outer');
        $this->db->join('employee e3','allocations.fieldstaffCode3=e3.id','left outer');
        $this->db->join('employee e4','allocations.fieldstaffCode4=e4.id','left outer');
        $this->db->join('route','route.id=allocations.routId');
        $this->db->where('allocations.id',$id);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getAllAllocations($tableName)
    {
        $this->db->distinct();
        $this->db->select('allocations.*,route.name as routeName');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.allocationId=allocations.id');
        $this->db->join('billsdetails','allocationsbills.billId=billsdetails.billId');
        
        $this->db->join('route','route.id=allocations.routId');
        $this->db->join('bills','bills.id=billsdetails.billId');
        $this->db->where('billsdetails.managerSrStatus',2);
        $query=$this->db->get();
        
        return $query->result_array();
    } 

    public function getRouteName($code){
        $this->db->select('name'); 
        $this->db->from('route');
        $this->db->where('code',$code);   
        return $this->db->get()->result_array();
    }

    public function getEmployeeNamesByID($id){
        $this->db->select('name'); 
        $this->db->from('employee');
        $this->db->where('id',$id);   
        return $this->db->get()->row()->name;
    }

     public function loadSrDetails($tblName, $billId,$allocationId,$billItemId){
        $this->db->where('billId', $billId);
        $this->db->where('allocationId', $allocationId);
        $this->db->where('billItemId', $billItemId);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function deleteSrDetails($tblName,$billId,$allocationId)
    {
        $this->db->where('billId',$billId);
        $this->db->where('allocationId',$allocationId);
        return $this->db->delete($tblName);
    }

    public function updateSrDetail($tblName,$data,$allocationId,$billId,$billItemId){
        $this->db->where('allocationId', $allocationId);
         $this->db->where('billId', $billId);
          $this->db->where('billItemId', $billItemId);
        return $this->db->update($tblName, $data);  
    }

    public function insert($tblName, $data) {      
        
        $this->db->insert($tblName, $data);
        return $this->db->insert_id();
    }

    public function update($tblName, $data, $id) {
        $this->db->where('id', $id);
        return $this->db->update($tblName, $data);  
    }

    public function updateByBillId($tblName, $data, $id) {
        $this->db->where('billId', $id);
        return $this->db->update($tblName, $data);  
    }

    public function getTotalSrQtyById($id){
        $this->db->select('sum(fsReturnQty) as srQty'); 
        $this->db->from('billsdetails');   
        // $this->db->join('billsdetails','billsdetails.billId=bills.id');
        $this->db->where('billId',$id);
        return $this->db->get()->row()->srQty;
    }

    

    public function getEmpName($tableName,$id)
    {
        $this->db->where('id',$id);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getBillInfo($tableName,$billNo)
    {
        $this->db->where('billNo',$billNo);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

     public function getRouteNameById($id){
        $this->db->select('name'); 
        $this->db->from('route');
        $this->db->where('id',$id);   
        return $this->db->get()->result_array();
    }

    public function load($tblName, $id) {
        $this ->db-> where('id', $id);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

  
    public function getdataEmployee($tableName)
    {
        $this->db->where('ownerApproval',0);
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

    public function retailerBillsByCode($tblName,$id) {
        $this->db->where('billId',$id);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function detailByGkBill($tblName,$id) {
        $this->db->where('billId',$id);
        $this->db->where('gkStatus !=',1);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }

    public function billsByCode($tblName,$id) {
        $this->db->select('billsdetails.*,bills.billNo as BillNo,bills.retailerName as RetailerName'); 
        $this->db->from($tblName);  
        $this->db->join('bills','billsdetails.billId=bills.id');
        $this->db->where('billsdetails.billId',$id);
        $this->db->where('billsdetails.fsReturnQty >',0);
        // $this->db->where('billsdetails.gkStatus !=',1);
        // $query = $this->db->get($tblName);
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

    public function getBillNosByCompany($compName){
        $this->db->select('bills.billNo,bills.retailerName'); 
        $this->db->from('bills');   
        // $this->db->join('retailer','bills.retailerCode=retailer.code','left outer');
        $this->db->where('pendingAmt >',0);
        $this->db->where('deliveryStatus !=','Cancelled');
        $this->db->where('billType','');
        // $this->db->where('billType !=','allocatedbillPass');
        // $this->db->where('billType !=','allocatedbillCurrent');
        // $this->db->where('billType !=','allocatedbillDS');
        $this->db->where('compName',$compName);
       
        // $this->db->or_where('billType !=','deliveryslip');  
        // $this->db->where('bills.deliveryStatus !=','Cancelled');
        return $this->db->get()->result_array();
    }

      public function getPastBillsByComp($compName)
    {
        $this->db->select('bills.billNo,bills.retailerName'); 
        $this->db->from('bills');   
        // $this->db->join('retailer','bills.retailerCode=retailer.code');
        $this->db->where('pendingAmt >', 0);
        // $this->db->where('billType !=','allocatedbill');  
        $this->db->where('billType !=','deliveryslip'); 
        $this->db->where('deliveryStatus !=','Cancelled');  
        $this->db->where('billType !=','allocatedbillPass');
        $this->db->where('billType !=','allocatedbillCurrent');
        $this->db->where('billType !=','allocatedbillDS'); 
        $this->db->where('compName',$compName);     
        $query=$this->db->get();
        
        return $query->result_array();
    }

     public function bouncedReturnCheques($tblName,$comp){
        $this->db->select('chequeNo'); 
        $this->db->from($tblName);
        $this->db->where('chequeStatus','Bounced&Returned');  
        $this->db->where('compName',$comp);  
        return $this->db->get()->result_array();
    }

    public function deliverySlipBillNo($comp){
        $this->db->select('bills.billNo,bills.retailerName'); 
        $this->db->from('bills');   
        // $this->db->join('retailer','bills.retailerId=retailer.id');
        $this->db->where('billType','deliveryslip');
        $this->db->where('compName',$comp);
        return $this->db->get()->result_array();
    }

     public function getAllocatedBills($tableName,$id){
            $this->db->select('bills.*');
            $this->db->from($tableName);
            $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
            $this->db->where('allocationsbills.allocationId',$id);
            $query=$this->db->get();
            
            return $query->result_array();
       }

    public function getAllocatedBillsByType($tableName,$id,$type,$status){
        $this->db->select('bills.*');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
        $this->db->where('allocationsbills.allocationId',$id);
        $this->db->where('allocationsbills.billStatus',$status);
        $this->db->where('bills.billType',$type);
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

   public function getAllocatedBillsType($tableName,$id){
        // $this->db->distinct();
   		$this->db->select('billsdetails.*,bills.billNo as billNo,bills.salesman as salesmanName,bills.retailerName as retailerName,bills.fsbillStatus as FsBillStatus');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=billsdetails.billId');
        $this->db->join('bills','bills.id=billsdetails.billId');
        $this->db->where('allocationsbills.allocationId',$id);
        $this->db->where('billsdetails.fsReturnQty >',0);
        // $this->db->where('billsdetails.gkStatus !=',1);
        $this->db->like('bills.fsBillStatus', 'SR');
         $this->db->not_like('bills.fsBillStatus', 'FSR');
         // $this->db->group_by('bills.id');
        $query=$this->db->get();
        
        return $query->result_array();
   }


   public function chkAllocatedBillsType($tableName,$id){
        $this->db->select('billsdetails.*,bills.billNo as billNo,bills.retailerName as retailerName,bills.fsbillStatus as FsBillStatus');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=billsdetails.billId');
        $this->db->join('bills','bills.id=billsdetails.billId');
        $this->db->where('allocationsbills.allocationId',$id);
        $this->db->where('billsdetails.fsReturnQty >',0);
        $this->db->where('billsdetails.gkStatus !=',1);
        $this->db->like('FsBillStatus', 'SR');
         $this->db->not_like('FsBillStatus', 'FSR');
        $query=$this->db->get();
        
        return $query->result_array();
   }

    public function getAllocatedBillsFSR($tableName,$id){
        $this->db->distinct();
        $this->db->select('bills.id,bills.creditAdjustment as creditAdjustment,bills.billNo as billNo,bills.netAmount as netAmount,bills.retailerName as retailerName,bills.salesman as salesmanName,bills.fsbillStatus as FsBillStatus');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=billsdetails.billId');
        $this->db->join('bills','bills.id=billsdetails.billId');
        $this->db->where('allocationsbills.allocationId',$id);
        $this->db->where('billsdetails.fsReturnQty >',0);
         // $this->db->where('billsdetails.gkStatus !=',1);
        $this->db->like('bills.fsbillStatus', 'FSR');
        $this->db->group_by('bills.id');
        $query=$this->db->get();
        
        return $query->result_array();
   }

    public function getNewBillAllocatedBillsFSR($tableName,$id){
        $this->db->distinct();
        $this->db->select('bills.pendingAmt as pendingAmt,bills.id,bills.creditAdjustment as creditAdjustment,bills.billNo as billNo,bills.netAmount as netAmount,bills.retailerName as retailerName,bills.salesman as salesmanName,bills.fsbillStatus as FsBillStatus');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
        $this->db->where('allocationsbills.allocationId',$id);
        $this->db->where('bills.pendingAmt !=',0);
        $this->db->like('bills.fsbillStatus', 'FSR');
        $this->db->group_by('bills.id');
        $query=$this->db->get();
        
        return $query->result_array();
   }

    public function updateHistory($tblName, $data, $billId,$allocationId,$type) {
        $this->db->where('billId', $billId);
        $this->db->where('allocationId', $allocationId);
        $this->db->where('transactionStatus', $type);
        return $this->db->update($tblName, $data);  
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


    public function chkAllocatedBillsFSR($tableName,$id){
        $this->db->distinct();
        $this->db->select('bills.id,bills.billNo as billNo,bills.retailerName as retailerName,bills.fsbillStatus as FsBillStatus');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=billsdetails.billId');
        $this->db->join('bills','bills.id=billsdetails.billId');
        $this->db->where('allocationsbills.allocationId',$id);
        $this->db->where('billsdetails.fsReturnQty >',0);
        $this->db->where('billsdetails.gkStatus !=',1);
        $this->db->like('bills.fsbillStatus', 'FSR');
        $query=$this->db->get();
        
        return $query->result_array();
   }

    public function loadPayBills($tblName, $id) {
        $this->db->select('billId');
        $this ->db-> where('allocationId', $id);
        $query = $this->db->get($tblName);
        
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

}
?>