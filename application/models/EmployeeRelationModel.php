<?php
class EmployeeRelationModel extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }
    public function getdata($tableName)
    {
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function checkRecord($tblName,$roleId,$empId){
        $this->db->where('roleId',$roleId);
        $this->db->where('employeeId',$empId);
        $resultset=$this->db->get($tblName); 
        
        return $resultset->result_array();
    }
  

    public function getdata1($tableName)
    {   
        $this->db->distinct();
        $this->db->select("emprole.id,emprole.employeeId,emprole.status,employee.name as eName,employee.email,role.name as rName,role.id as rId");
        $this->db->join("employee","emprole.employeeId = employee.id");
        $this->db->join("role","emprole.roleId = role.id"); 
        $resultset=$this->db->get($tableName); 
        
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

    public function updateRecord($tblName, $data, $roleId,$employeeId) {
        $this->db->where('roleId', $roleId);
        $this->db->where('employeeId', $employeeId);
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

    public function deleteRole($tblName,$roleId,$empId)
    {
        $this->db->where('roleId',$roleId);
        $this->db->where('employeeId',$empId);
        return $this->db->delete($tblName,array('roleId'=>$roleId,'employeeId'=>$empId));
    }

    public function load($tblName, $id) {
        $this->db->where('id', $id);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }
     public function load1($tableName, $id)
    {
        $this->db->select("emprole.id,emprole.roleId,emprole.employeeId,emprole.status,employee.name as eName,employee.email,role.name as rName");
        $this->db->join("employee","emprole.employeeId = employee.id");
        $this->db->join("role","emprole.roleId = role.id"); 
        $this->db->where('emprole.employeeId', $id);
        $resultset=$this->db->get($tableName); 
        
        return $resultset->result_array();
    }
}
?>