<?php
class CompanyModel extends CI_Model {

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
    public function insert($tblName, $data) {      
        
        $this->db->insert($tblName, $data);
        return $this->db->insert_id();
    }
    public function update($tblName, $data, $id) {
        $this->db->where('id', $id);
        return $this->db->update($tblName, $data);  
    }

    public function getRecordByDistributorCode($tblName, $code) {
        $this->db->where('code', $code);
        $this->db->where('status', 1);
        $this->db->from($tblName);
        $data = $this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $data->result_array();
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

    public function getUserById($tableName,$id){
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('status',0);
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

    public function loadDataById($tblName, $id) {
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('id', $id);
        $query = $db2->get($tblName);
        $db2->close();
        $db2->initialize();
        return $query->result_array();   
    }

    public function getUserByCode($tblName, $code) {
        $db2 = $this->load->database('sia_superadmin', TRUE);
        $db2->where('code', $code);
        $query = $db2->get($tblName);
        $db2->close();
        $db2->initialize();
        return $query->result_array();   
    }
}
?>