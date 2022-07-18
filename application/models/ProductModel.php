<?php
class ProductModel extends CI_Model {

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

    

    public function getPendingForBilling($tableName){
        $this->db->select('products.*,sum(deliveryslip_pending_for_billing.reduceInCompanySoftware) as totatReducePendingBilling,sum(deliveryslip_pending_for_billing.addInCompanySoftware) as totatAddPendingBilling');
        $this->db->where('deliveryslip_pending_for_billing.operatorApproval',0);
        $this->db->group_by('deliveryslip_pending_for_billing.productId');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }
    
    public function getActiveProducts($tableName)
    {
        $this->db->where('isActive', '1');
         $this->db->order_by('blockQty','asc');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getProductDetailsByName($tableName,$name,$code,$mrp)
    {
        $this->db->where('name', $name);
        $this->db->where('productCode', $code);
        $this->db->where('mrp', $mrp);
        $this->db->where('isActive !=', 2);
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

    public function load($tblName, $id) {
        $this->db->where('id', $id);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();   
    }

    public function loadProductById($tblName, $id, $type) {
        $this->db->select('sum(quantityInPcs) as qty');
        $this->db->where('productId', $id);
        $this->db->where('operationStatus', $type);
        $query = $this->db->get($tblName);
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

    public function updateQty($tblName, $qty, $id) {
       $this->db->set('availQty','availQty+'. $qty,false);
       $this->db->where('id', $id);
        return $this->db->update($tblName);   
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
    
      public function itemDetails($id){
        $this->db->select('empId,productName,sum(qty) as qty'); 
        $this->db->from('billsdetails');   
        $this->db->where('empId',$id);
        $this->db->group_by('productName');
        return $this->db->get()->result_array();
    }
    
}	
?>
