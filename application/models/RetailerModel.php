<?php
class RetailerModel extends CI_Model {

	public function __construct()
    {
        $this->load->database();
    }
    public function getdata($tableName)
    {
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    
    public function paginationRetailers($tableName,$limit, $start, $st = "", $orderField, $orderDirection){
        $this->db->distinct();
        $this->db->select('retailer_kia.*');
        
        $this->db->from($tableName);
        $this->db->group_start();
        $this->db->like('name', $st);
        $this->db->or_like('retailerCode', $st);
        $this->db->or_like('area', $st);
        $this->db->or_like('billingAddress', $st);
        $this->db->group_end();
        $this->db->where('retailer_kia.isActive',1);
        $this->db->order_by($orderField, $orderDirection);
        $this->db->limit($limit, $start);
        $query=$this->db->get();
        
        return $query->result_array();
    }

    public function countPaginationRetailers($tableName,$limit, $start, $st = "", $orderField, $orderDirection){
        $this->db->distinct();
        $this->db->select('retailer_kia.*');
        $this->db->from($tableName);
        $this->db->group_start();
        $this->db->like('name', $st);
        $this->db->or_like('retailerCode', $st);
        $this->db->or_like('area', $st);
        $this->db->or_like('billingAddress', $st);
        $this->db->group_end();
        $this->db->where('retailer_kia.isActive',1);
        $this->db->order_by($orderField, $orderDirection);
        $query=$this->db->get();
        
        return $query->num_rows();
    }


    

    public function getAllRetailers($tableName)
    {
        $this->db->where('isActive', 1);
        $data=$this->db->get($tableName);
        
        return $data->result_array();
    }

    public function checkRetailerData($tblName,$retailerCode,$name){
        $this->db->where('retailerCode',$retailerCode);
        $this->db->where('name',$name);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }
    
     public function getRetailers($tableName)
    {
        
        $this->db->where('isActive', 1);
        $data=$this->db->get($tableName);
        if($data->num_rows()>0){
            $this->db->close();
            $this->db->initialize();
            return $data->result_array();
        }else{
            return null;
        }
    }
    
     public function getRetailersById($tableName,$id)
    {
       
        $this->db->where('retailer_kia.id', $id);
        $data=$this->db->get($tableName);
        if($data->num_rows()>0){
            $this->db->close();
            $this->db->initialize();
            return $data->result_array();
        }else{
            return null;
        }
    }
    
    public function getBlockRetailers($tableName)
    {
        $this -> db -> where('isActive', '0');
        $data=$this->db->get($tableName);
        if($data->num_rows()>0){
            $this->db->close();
            $this->db->initialize();
            return $data->result_array();
        }else{
            return null;
        }
    }

    public function load($tblName, $id) {
        $this->db->where('id', $id);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }
    
     public function getId($tblName,$name) {
        $this->db->select('id'); 
        $this->db->from($tblName);
        $this->db->where('name',$name); 
          
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

    public function updateByRetailerId($tblName, $data, $id) {
        $this->db->where('retailerId', $id);
        $this->db->where('isDeliverySlipBill', '1');
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

    public function getEmpId($name){
        $this->db->select('id'); 
        $this->db->from('employee');
        $this->db->where('name',$name);   
        return $this->db->get()->result_array();
    }

    public function getName($table){
        $this->db->select('name'); 
        $this->db->from($table);
        $this->db->where('routeId !=',null);
        return $this->db->get()->result_array();
    }

     public function getDetails($table,$name){
       $this->db->select('id'); 
        $this->db->from($table);
        $this->db->where('name',$name);   
        return $this->db->get()->result_array();
    }
    
}	
?>
