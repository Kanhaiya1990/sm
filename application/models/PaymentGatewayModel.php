<?php

class PaymentGatewayModel extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function getdata($tableName)
    {
        $query=$this->db->get($tableName);
        return $query->result_array();
    }

    public function getRecordByDistributorCode($tblName, $code) {
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('code', $code);
        $db2->where('status', 1);
        $db2->from($tblName);
        $data = $db2->get();
        return $data->result_array();
    }

    public function getTableData($tableName)
    {
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $query= $db2->get($tableName);
        return $query->result_array();
    }

    public function show($tblName) {
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $query = $db2->get($tblName);
        return $query->result_array();    
    }

    public function delete($tblName,$id)
    {
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('id',$id);
        return $db2->delete($tblName,array('id'=>$id));
    }

    public function load($tblName, $id) {
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('id', $id);
        $query =  $db2->get($tblName);
        return $query->result_array();   
    }

    public function getUserById($tableName,$id){
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('status',1);
        $db2->where('isDeleted',0);
        $db2->where('id',$id);
        $data=$db2->get($tableName);
        return $data->result_array(); 
    }

    public function loadData($tblName)
    {
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('status', 0);
        $db2->where('isDeleted', 0);
        $db2->order_by('id', 'asc');
        $db2->from($tblName);
        $data = $db2->get();
        return $data->result_array();
    }

    public function getLastId($tableName,$id){
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->select_max('id');
        $db2->where('distributorId',$id);
        $data=$db2->get($tableName);
        return $data->result_array(); 
    }

    public function getLastFive($tableName,$did){
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('distributorId',$did);
        $db2->order_by('id', 'desc');
        $db2->limit(5);
        $data=$db2->get($tableName);
        return $data->result_array();
    }

    public function getLastEntry($tableName,$did){
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('distributorId',$did);
        $db2->order_by('id', 'desc');
        $db2->limit(1);
        $data=$db2->get($tableName);
        return $data->result_array();
    }

    public function loadDataById($tblName, $id) {
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('id', $id);
        $query = $db2->get($tblName);
        return $query->result_array();   
    }

    public function loadFileById($tblName, $id) {
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->select('filepath');
        $db2->where('cid', $id);
        $query = $db2->get($tblName);
        return $query->result_array();   
    }

    public function loadFileByOrderId($tblName, $id) {
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->select('filepath');
        $db2->where('orderId', $id);
        $query = $db2->get($tblName);
        return $query->result_array();   
    }

    public function getUserByCode($tblName, $code) {
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('code', $code);
        $query = $db2->get($tblName);
        return $query->result_array();   
    }

    

    
    public function insert($tblName, $data) { 
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->insert($tblName, $data);
        return $db2->insert_id();
    }
	

    public function update($tblName, $data, $id) {
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('id', $id);
        return $db2->update($tblName, $data);  
    }
    
    public function updateCid($tblName, $data, $id) {
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('cid', $id);
        return $db2->update($tblName, $data);  
    }

    public function updateOrderStatus($tblName, $data, $id) {
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('orderId', $id);
        return $db2->update($tblName, $data);  
    }

    public function getDatabyId($tblName,$id){
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('id', $id);
        $db2->from($tblName);
        $data = $db2->get();
        return $data->result_array();
    }

    public function getDatabyName($tblName,$name)
    {
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('packageName', $name);
        $db2->from($tblName);
        $data = $db2->get();
        return $data->result_array();
    }

    public function loadDataGroupBy($tblName)
    {
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->select('*');
        $db2->order_by('id', 'asc');
        $db2->group_by('packageName');
        $data = $db2->get($tblName);
        return $data->result_array();
    }

    public function check_user_login($tblName,$email,$password){
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('email',$email);
        $db2->where('password1',$password);
        $db2->where('status',1);
        $db2->where('isDeleted', 0);   
        $data=$db2->get($tblName);
        return $data->result_array();   
    }

    public function checkUserLogin($tblName,$email,$password){
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('email',$email);
        $db2->where('password',$password);
        $db2->where('status',1);
        $db2->where('isDeleted', 0);   
        $data=$db2->get($tblName);
        return $data->result_array();   
    }

    public function read_user_information($tblName,$email){
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('email',$email);
        $db2->where('status',1);
        $db2->where('isDeleted', 0);   
        $data=$db2->get($tblName);
        return $data->result_array();   
    }

    public function getCountAll($tableName){
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('status',0);
        $db2->where('isDeleted',0);
        $data=$db2->get($tableName);
        return count($data->result_array());
    }

    public function getCountDist($tableName,$pid){
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('status',0);
        $db2->where('isDeleted',0);
        $db2->where('partnerId',$pid);
        $data=$db2->get($tableName);
        return count($data->result_array());
    }
	
	public function getAllInvoice($tableName,$yearLastDigit){  
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->select('SUBSTRING(invoiceNumber, 4, 2) as code', false);
        $db2->where("(SUBSTRING(invoiceNumber, 4, 2) = '$yearLastDigit')"); 
        $db2->from($tableName);
        $query = $db2->get();
        return $query->result();
    }

    public function getLastOneInvoice($tableName,$yearLastDigit){  
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->select('id');
        $db2->where("(SUBSTRING(invoiceNumber, 4, 2) = '$yearLastDigit')"); 
        $db2->from($tableName);
        $db2->order_by('id','desc');
        $db2->limit(1);
        $query = $db2->get();
        return $query->result_array();
    }


    public function getAll($tableName,$did){
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('distributorId',$did);
        $db2->order_by('id', 'desc');
        $data=$db2->get($tableName);
        return $data->result_array();
    }
	
	public function getLastInvoice($tableName){
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->select_max('invoiceNumber');
        $db2->order_by('id', 'desc');
        $data=$db2->get($tableName);
        return $data->result_array(); 
    }

    public function getDistById($tableName,$pid){
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('status',0);
        $db2->where('isDeleted',0);
        $db2->where('partnerId',$pid);
        $data=$db2->get($tableName);
        return $data->result_array(); 
    }

    public function getDistByIdPackage($tableName,$pid){
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('status',0);
        $db2->where('isDeleted',0);
        $db2->where('package',$pid);
        $data=$db2->get($tableName);
        return $data->result_array(); 
    }

    public function getLastId1($tableName,$id){
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->select_max('id');
        $db2->where('cid',$id);
        $data=$db2->get($tableName);
        return $data->result_array(); 
    }

    public function getDatabycId($tblName,$id){
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('cid', $id);
        $db2->order_by('id','desc');
        $db2->from($tblName);
        $data = $db2->get();
        return $data->result_array();
    }

    public function getDatabyOrder($tblName,$id)
    {
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('orderId', $id);
        $db2->from($tblName);
        $data = $db2->get();
        return $data->result_array();
    }
}
?>