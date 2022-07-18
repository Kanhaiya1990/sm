<?php
class ExcelModel extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function countBills($tableName,$billId){
        $this->db->select('billsdetails.billId');
        $this->db->where('billsdetails.billId',$billId);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->num_rows();
    }

    public function getAllSalesmans($tableName){
        $this->db->distinct();
        $this->db->select('salesmanCode,salesman');
        $this->db->where('salesmanCode !=','');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getDataUsingSalesman($tableName,$code,$name){
        $this->db->select('netAmount,pendingAmt,isAllocated');
        $this->db->where('salesmanCode',$code);
        $this->db->where('salesman',$name);
        $this->db->where('pendingAmt >',0);
        $this->db->where('deliveryStatus !=','cancelled');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getSalesmanCount($tableName,$name){
        $this->db->select('id,salesmanCode');
        $this->db->where('salesman',$name);
        $this->db->where('salesmanCode !=','');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }


    public function getAllNestleBills($tableName){
        $this->db->where('compName','Nestle');
        // $this->db->where('deliveryStatus','pending');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getLatesttUploadedFileData($tableName,$company){
        $this->db->where('company',$company);
        $this->db->order_by('id','desc');
        $this->db->limit(1);  
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getUploadedFileData($tableName,$company){
        $this->db->where('company',$company);
        $this->db->order_by('id','desc');
        $this->db->limit(1);  
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getlastBills($tableName,$company){
        $this->db->where('compName',$company);
        $this->db->order_by('id','desc');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

     public function getPendingDatesFromBills($tableName,$company){
        $this->db->where('manuallyAddedBill',0);
        $this->db->where('compName',$company);
        $this->db->group_start();
        $this->db->where('deliveryStatus','pending');
        $this->db->or_where('deliveryStatus','');
        $this->db->group_end();
        $this->db->order_by('id','asc');
        $this->db->limit(1);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getDeliveredDatesFromBills($tableName,$company){
        $this->db->where('manuallyAddedBill',0);
        $this->db->where('compName',$company);
        $this->db->order_by('id','desc');
        $this->db->limit(1);
        $query=$this->db->get($tableName);
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

    public function getCronDetails($tableName,$company)
    {
        $this->db->where('company',$company);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }


    public function getDataByName($tableName,$name)
    {
        $this->db->where('name',$name);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function batchInsert($tableName,$data)
    {
        $this->db->insert_batch($tableName, $data);
    }

    public function getRouteInfo($tableName,$rname,$rcode)
    {
        $this->db->where('code',$rcode);
        $this->db->where('name',$rname);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function routeInfoByName($tableName,$rname)
    {
        $this->db->where('name',$rname);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function routeInfo($tableName,$rname,$rcode)
    {
        $this->db->where('code',$rcode);
        $this->db->where('name',$rname);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function retailerInfo($tableName,$rName,$rcode)
    {
        $this->db->where('name',$rName);
        $this->db->where('code',$rcode);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getInfoByCode($tableName,$rcode)
    {
        $this->db->where('code',$rcode);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getInfoByName($tableName,$name,$comp)
    {
        $this->db->where('name',$name);
        $this->db->where('company',$comp);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getAllCompanies($tableName)
    {
        $this->db->select('name');
        $this->db->distinct();
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getEmpIdByName($tableName,$name){
        $this->db->select('id'); 
        $this->db->from($tableName);
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

    public function updateByCompany($tblName, $data, $company) {
        $this->db->where('company', $company);
        return $this->db->update($tblName, $data);  
    }

    public function updateBillRoute($tblName,$data, $retailerName, $retailerCode) {
        $this->db->where('retailerName', $retailerName);
        $this->db->where('retailerCode', $retailerCode);
        return $this->db->update($tblName, $data);  
    }

    public function updateBillData($tblName,$data, $retailerName, $retailerCode) {
        $this->db->where('salesman', '');
        $this->db->where('routeName', '');
        $this->db->where('retailerName', $retailerName);
        $this->db->where('retailerCode', $retailerCode);
        return $this->db->update($tblName, $data);  
    }

    public function show($tblName) {
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();    
    }
    
   public function delete($tblName, $id) {
        $this->db-> where('id', $id);
        return $this->db->delete($tblName);
    }

    public function removeEmpty($tblName) {
        $this->db-> where('productName','');
        return $this->db->delete($tblName);
    }

    public function removeEntryParle($tblName) {
        $this->db-> where('mrp','0.0');
        $this->db-> where('sellingRate','0.0');
        return $this->db->delete($tblName);
    }
    public function load($tblName, $id) {
        $this->db->where('id', $id);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();   
    }

    public function getBillDetails($tblName, $id) {
        $this->db->where('billId', $id);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();   
    }

    public function getAllItcBillDetails($tblName,$billId,$productCode,$productName,$sellingRate,$quantity,$netAmount){
        // public function getAllItcBillDetails($tblName,$billId,$productCode,$sellingRate,$quantity,$netAmount){
            $this->db->where('billId',trim($billId));
            $this->db->where('productCode',trim($productCode));
            $this->db->where('productName', trim($productName));
            $this->db->like('sellingRate',trim($sellingRate));
            $this->db->where('qty',trim($quantity));
            $this->db->like('netAmount',trim($netAmount));
            $query = $this->db->get($tblName);
            $this->db->close();
        $this->db->initialize();
            return $query->result_array(); 
        }

    public function getAllBillDetails($tblName,$billId,$productCode,$productName,$mrp,$quantity,$netAmount){
        $this->db->where('billId', $billId);
        $this->db->where('productCode', $productCode);
        $this->db->where('productName', $productName);
        $this->db->where('mrp', $mrp);
        $this->db->where('qty', $quantity);
        $this->db->where('netAmount', $netAmount);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array(); 
    }

    public function checkBillDetailsData($tblName,$billId,$productName,$gstPercent,$quantity){
        $this->db->where('billId', $billId);
        $this->db->where('productName', $productName);
        $this->db->where('gstPercent', $gstPercent);
        $this->db->where('qty', $quantity);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array(); 
    }

     public function getAllBillDetailsInLastEntries($tblName,$billId,$productCode,$productName,$mrp,$quantity,$netAmount){
        $this->db->where('billId', $billId);
        $this->db->where('productCode', $productCode);
        $this->db->where('productName', $productName);
        $this->db->where('mrp', $mrp);
        $this->db->where('qty', $quantity);
        $this->db->where('netAmount', $netAmount);
        $this->db->limit(10000);
        $this->db->order_by('id','desc');
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array(); 
    }

    // public function getBillDetails($tblName, $id) {
    //     $this->db->where('billId', $id);
    //     $query = $this->db->get($tblName);
    //     return $query->result_array();   
    // }

    public function getBillId($tblName, $id) {
        $this->db->where('billNo', $id);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array(); 
        // return $query->row()->id;   
    }

    public function getBillByLastRecords($tblName, $id) {
        $this->db->select('id,billNo');
        $this->db->where('billNo', $id);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array(); 
        // return $query->row()->id;   
    }

    public function getBillRecords($tblName, $id) {
        $this->db->select('grossAmount,taxAmount,billNetAmount,netAmount,pendingAmt');
        $this->db->where('id', $id);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array(); 
        // return $query->row()->id;   
    }

    public function getPendingBillId($tblName, $id) {
        $this->db->where('deliveryStatus', 'delivered');
        $this->db->where('billNo', $id);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array(); 
        // return $query->row()->id;   
    }

    function insert1($data)
    {
        $this->db->insert_batch('products', $data);
    }
    public function Add_User($data_user)
    {
        $this->db->insert('products', $data_user);
    }
    
}
?>