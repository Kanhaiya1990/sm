<?php
class DeliverySlipModel extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

     public function paginationDeliveryBills($tableName,$limit, $start, $st = "", $orderField, $orderDirection){
        $this->db->distinct();
        $this->db->select('bills.*');
        $this->db->where('isDeliverySlipBill','1');
        // $this->db->where('deliveryslipOwnerApproval','1');
        // $this->db->order_by('bills.id','desc');
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

    public function countPaginationDeliveryBills($tableName,$limit, $start, $st = "", $orderField, $orderDirection){
        $this->db->distinct();
        $this->db->select('bills.*');
        $this->db->where('isDeliverySlipBill','1');
        $this->db->where('deliveryslipOwnerApproval','1');
        $this->db->order_by('bills.id','desc');
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

    public function checkProductExist($tblName,$productId,$userId){
        $this->db->where('userId',$userId);
        $this->db->where('productId',$productId);
        $query=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function checkRetailerExist($tblName,$code){
        $this->db->where('retailerCode',$code);
        $query=$this->db->get($tblName);
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

    public function getAllocationDetailsByBill($tblName,$id){
        $this->db->select('allocations.id, allocations.allocationCode');
        $this->db->join('allocationsbills','bills.id=allocationsbills.billId');
        $this->db->join('allocations','allocations.id=allocationsbills.allocationId');
        $this->db->where('bills.id',$id);
        $this->db->where('allocations.isAllocationComplete','0');
        $this->db->order_by('allocations.id','desc');
        $this->db->limit(1); 
        $query=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getCloseAllocationDetailsByBill($tblName,$id){
        $this->db->select('allocations.id, allocations.allocationCode');
        $this->db->join('allocationsbills','bills.id=allocationsbills.billId');
        $this->db->join('allocations','allocations.id=allocationsbills.allocationId');
        $this->db->where('bills.id',$id);
        $this->db->where('allocations.isAllocationComplete','1');
        $this->db->order_by('allocations.id','desc');
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

    public function getCloseOfficeAllocationDetailsByBill($tblName,$id){
        $this->db->select('allocations_officeadjustment.id, allocations_officeadjustment.allocationCode');
        $this->db->join('allocations_officebills','bills.id=allocations_officebills.billId');
        $this->db->join('allocations_officeadjustment','allocations_officeadjustment.id=allocations_officebills.allocationId');
        $this->db->where('bills.id',$id);
        $this->db->where('allocations_officeadjustment.isAllocationComplete','1');
        $this->db->order_by('allocations_officeadjustment.id','desc');
        $this->db->limit(1); 
        $query=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function loadRetailer($id) {
        $sql="select * from retailer where code='".$id."'";
        $query = $this->db->query($sql);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();   
    }

    public function getCartDetailsByUser($tblName,$userId){
        $this->db->select('deliveryslip_add_to_cart.*,retailer_kia.area as retailerArea,retailer_kia.retailerCode as retailerCode,retailer_kia.name as retailerName,retailer_kia.id as retailerId,employee.name as empName,employee.id as empId');
        $this->db->join('employee','employee.id=deliveryslip_add_to_cart.salesmanId');
         $this->db->join('retailer_kia','retailer_kia.id=deliveryslip_add_to_cart.retailerId');
        $this->db->where('userId',$userId);
        $query=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getSumOfReduceQty($tblName,$id){
        $this->db->select('sum(deliveryslip_pending_for_billing.quantityInPcs) as totatReduceQuantity');
        $this->db->where('deliveryslip_pending_for_billing.productId',$id);
        $this->db->where('deliveryslip_pending_for_billing.operationStatus','reduce');
        $this->db->group_by('deliveryslip_pending_for_billing.productId');
        $query=$this->db->get($tblName);
        return $query->result_array();
        $this->db->close();
        $this->db->initialize();
        // return $query->row()->totatReduceQuantity;
    }

    public function getSumOfReplaceQty($tblName,$id){
        $this->db->select('sum(deliveryslip_pending_for_billing.quantityInPcs) as totatReplaceQuantity');
        $this->db->where('deliveryslip_pending_for_billing.productId',$id);
        $this->db->where('deliveryslip_pending_for_billing.operationStatus','replace');
        $this->db->group_by('deliveryslip_pending_for_billing.productId');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
        // return $query->row()->totatReduceQuantity;
    }

    public function getRetailerRateById($tableName,$retailerId,$productId){
        $this->db->where('retailerId',$retailerId);
        $this->db->where('productId',$productId);
        $this->db->order_by('id','desc');
        $this->db->limit(1);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getTotalQtySum($tblName,$id){
        $this->db->select('sum(deliveryslip_pending_for_billing.quantityInPcs) as totatQuantity');
        $this->db->where('deliveryslip_pending_for_billing.productId',$id);
        // $this->db->where('deliveryslip_pending_for_billing.operationStatus','add');
        $this->db->group_by('deliveryslip_pending_for_billing.productId');
        $query=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
         // return $query->row()->totatAddQuantity;
    }

    public function getSumOfAddQty($tblName,$id){
        $this->db->select('sum(deliveryslip_pending_for_billing.quantityInPcs) as totatAddQuantity');
        $this->db->where('deliveryslip_pending_for_billing.productId',$id);
        $this->db->where('deliveryslip_pending_for_billing.operationStatus','add');
        $this->db->group_by('deliveryslip_pending_for_billing.productId');
        $query=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
         // return $query->row()->totatAddQuantity;
    }

    public function getdata($tableName)
    {
        
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getDeliveryData($tableName)
    {
        $this->db->where('isDeliverySlipBill','1');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getOpenAllocations($tableName)
    {
        $this->db->select('allocations.*,route.name as routeName');
        $this->db->join('route','route.id =allocations.routId');
        $this->db->where('isAllocationComplete','0');
        $this->db->where('fsStatus','0');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getDeliveryBillDetail($tableName,$id)
    {
        $this->db->where('billingId',$id);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function checkProductData($tableName,$code,$name){
        $this->db->where('productCode',$code);
        $this->db->where('name',$name);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function checkProductDataForUpload($tableName,$code,$name,$mrp){
        $this->db->where('productCode',$code);
        $this->db->where('name',$name);
        $this->db->where('mrp',$mrp);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }
    
    public function getDetailBilling($tableName){
        $this->db->select('bills.*');
        $this->db->where('isDeliverySlipBill','1');
        $this->db->where('deliveryslipOwnerApproval','1');
        $this->db->order_by('bills.id','desc');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getPendingDetailBilling($tableName){
        $this->db->select('bills.*');
        $this->db->where('pendingAmt >','0');
        $this->db->where('isDeliverySlipBill','1');
        $this->db->where('deliveryslipOwnerApproval','0');
        $this->db->order_by('bills.id','desc');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getPendingForBilling($tableName){
        $this->db->select('deliveryslip_pending_for_billing.*,sum(deliveryslip_pending_for_billing.quantityInPcs) as totatReducePendingBilling,products.mrp as pmrp');
        $this->db->join('products','products.id=deliveryslip_pending_for_billing.productId');
        $this->db->where('deliveryslip_pending_for_billing.operatorApproval',0);
        $this->db->group_by('deliveryslip_pending_for_billing.productId');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getPendingForBillingWithCompany($tableName,$company){
        $this->db->select('deliveryslip_pending_for_billing.*,sum(deliveryslip_pending_for_billing.quantityInPcs) as totatReducePendingBilling,products.mrp as pmrp');
        $this->db->join('products','products.id=deliveryslip_pending_for_billing.productId');
        $this->db->where('deliveryslip_pending_for_billing.operatorApproval',0);
        $this->db->where('products.company',$company);
        $this->db->group_by('deliveryslip_pending_for_billing.productId');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getReduceQty($tableName,$productId){
        $this->db->where('deliveryslip_pending_for_billing.productId',$productId);
        // $this->db->group_by('deliveryslip_pending_for_billing.productId');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }
    
    public function getEmployee($tableName)
    {

        $this->db->order_by('name', 'asc');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }
    
    public function getRoute($tableName)
    {
        $this->db->order_by('name', 'asc');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getActiveProducts($tableName)
    {
        $this->db->where('isActive', '1');
        // $this->db->order_by('blockQty', 'asc');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }
    
    public function getActiveProductName($tableName)
    {
        $this->db->where('isActive', '1');
        $this->db->order_by('name', 'asc');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }
    
     public function getDeactiveProducts($tableName)
    {
        $this->db->where('isActive', '0');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }
    
    public function getBillData($tableName)
    {
        $this->db->where('billType','deliveryslip');
        // $this->db->where('pendingAmt >',0);
        $this->db->order_by('date', 'desc');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getRetailerBillData($tableName,$code)
    {
        $this->db->where('billType','DeliverySlip');
        $this->db->where('retailerCode',$code);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }
    
     public function getKiaRetailerBillData($tableName,$name)
    {
        $this->db->where('billType','DeliverySlip');
        $this->db->like('retailerName',$name);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }


    public function loadRetailerBills($tblName, $fromNo ,$toNo) {
        $this->db->select('retailerCode');
        $this->db->select_sum('pendingAmt' , 'pendingAmt'); 

        $this->db->order_by('pendingAmt', 'desc');
        $this->db->group_by('retailerCode');
        $this->db->close();
        $this->db->initialize();
        $query = $this->db->get($tableName);
       
        // $this->db->where('date BETWEEN "'. $fromNo. '" and "'.$toNo.'"');
        // $this->db->where()
        // $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();   
    }

    public function retailerBills($tblName) {
        $this->db->select('retailerName,salesman,retailerId,count(billNo) as billCount');
        $this->db->select_sum('pendingAmt' , 'pendingAmt'); 
        // $this->db->select_count('pendingAmt' , 'billCount'); 
        $this->db->where('pendingAmt >',0);
        // $this->db->like('billNo','SL');
         // $this->db->where('billType','deliverySlip');
        $this->db->where('isDeliverySlipBill',1);
        $this->db->order_by('pendingAmt', 'desc');
        $this->db->group_by('retailerCode,retailerId');
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();   
    }
    
    public function retailerAccountBills($tblName) {
        $this->db->select('retailer_kia.name');
        $this->db->select_sum('pendingAmt' , 'pendingAmt');
        $this->db->join('bills','retailer_kia.id=bills.retailerId','right outer');
        $this->db->where('billType','deliverySlip');
        $this->db->order_by('pendingAmt', 'desc');
        $this->db->group_by('retailerName');
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();   
    }

    public function retailerBillsByCode($tblName,$code) {
        $this->db->where('pendingAmt >',0);
        $this->db->where('isDeliverySlipBill',1);
        $this->db->where('retailerName',$code);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();   
    }


    //outstanding
    public function getdataOutstandingBill($tblName) {
        $this -> db -> where('pendingAmt >', 0);
        $this -> db -> where('billType', 'deliveryslip');
        $this->db->order_by('date', 'desc');
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();   
    }  

    public function load($tblName, $id) {
        $this ->db-> where('id', $id);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();   
    }

    public function loadByBillId($tblName, $id) {
        $this ->db-> where('billId', $id);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();   
    }

    public function loadByProductId($tblName, $id) {
        $this ->db-> where('productId', $id);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();   
    }

    public function loadBills($tblName, $id) {
        $this ->db-> where('billNo', $id);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();   
    }

    public function getNextId($tableName) {
        $this->db->select_max('id');
        $query = $this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getRetailerCode($name) {
        $this->db->select('id,code,hierarchy'); 
        $this->db->from('retailer');
        $this->db->where('name',$name);   
        
        return $this->db->get()->result_array();
    }

     public function getKiaRetailerCode($name) {
        $this->db->select('id,name'); 
        $this->db->from('retailer_kia');
        $this->db->where('name',$name);   
        return $this->db->get()->result_array();
    }

    public function getRetailerName($code) {
        $this->db->select('name,routeId'); 
        $this->db->from('retailer');
        $this->db->where('id',$code);   
        return $this->db->get()->result_array();
    }

    public function getEmpCode($name) {
        $this->db->select('id'); 
        $this->db->from('employee');
        $this->db->where('name',$name);   
        return $this->db->get()->result_array();
    }
    
   public function getIDs($table,$name) {
        $this->db->select('id,name'); 
        $this->db->from($table);
        $this->db->where('name',$name);   
        return $this->db->get()->result_array();
    }

    public function RetailerName($table) {
        $this->db->select('retailer_kia.*'); 
        $this->db->from($table);
        $this->db->where('isActive !=','0');
        $this->db->order_by('name', 'asc');
        return $this->db->get()->result_array();
    }
    
    public function ProductName() {
        $this->db->select('id,name'); 
        $this->db->from('products');
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

    public function updateByProductId($tblName, $data, $id) {
        $this->db->where('productId', $id);
        return $this->db->update($tblName, $data);  
    }

    public function updateByName($tblName,$name,$qty) {
        $this->db->set('availQty','availQty-'.$qty,false);
        $this->db->set('blockQty','blockQty-'. $qty,false);
        $this->db->where('name', $name);
        return $this->db->update($tblName);  
    }
    
     public function prodDetailsByName($tblName,$name) {
        $this->db->where('name', $name);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
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

    public function deleteCartRecordsByUser($tblName,$id)
    {
        $this->db->where('userId',$id);
        return $this->db->delete($tblName,array('userId'=>$id));
    }
    

    public function getRetailerArea($name)
    {
        $this->db->from('retailer_kia');   
        $this->db->where('retailer_kia.name',$name);
        return $this->db->get()->result_array();
    }

    public function EmpName($name){
        $this->db->select('route.name as rtname'); 
        $this->db->from('retailer_kia');   
        // $this->db->join('route','route.id=retailer_kia.routeId','left outer');
        $this->db->where('retailer_kia.name',$name);
        return $this->db->get()->result_array();
    }

    // public function EmpName($name){
    //     $this->db->select('route.name as rtname'); 
    //     $this->db->from('retailer_kia');   
    //     // $this->db->join('route','route.id=retailer_kia.routeId','left outer');
    //     $this->db->where('retailer_kia.name',$name);
    //     return $this->db->get()->result_array();
    // }
    
    public function itemDetails($id){
        $this->db->select('empId,productName,sum(qty) as qty'); 
        $this->db->from('billsdetails');   
        $this->db->where('empId',$id);
        $this->db->group_by('productName');
        return $this->db->get()->result_array();
    }
    
    
    public function getSalesmanStockSale($tableName){
        $this->db->select('bills.date as bdate,bills.salesman as ename,billsdetails.productName as pname,sum(billsdetails.qty) as prodQty');
        $this->db->join('billsdetails','bills.id=billsdetails.billId');
        $this->db->where('bills.billType','deliveryslip');
        $this->db->group_by('ename,pname');
        $this->db->order_by('bdate','desc');
        $this->db->from($tableName);
        return $this->db->get()->result_array();
    }
    
    public function getSalesmanStockSaleByDate($tableName,$fromDate,$toDate){
        $this->db->select('bills.date as bdate,bills.salesman as ename,billsdetails.productName as pname,sum(billsdetails.qty) as prodQty');
        $this->db->join('billsdetails','bills.id=billsdetails.billId');
        $this->db->where("bills.date BETWEEN '". $fromDate. "' AND '".$toDate."'");
        $this->db->group_by('ename,pname');
        $this->db->order_by('bdate','desc');
        $this->db->from($tableName);
        return $this->db->get()->result_array();
    }
}   
?>
