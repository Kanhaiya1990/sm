<?php
Class LoginDatabase extends CI_Model {
// Read data using username and password
public function login($data) {
		// $condition = "email =" . "'" . $data['email'] . "' AND " . "password =" . "'" . $data['password'] . "' AND " . "status =" . "'" . $status. "'";
		$this->db->select('*');
		$this->db->from('employee');
		$this->db->where('email',$data['email']);
		$this->db->where('password',$data['password']);
		$this->db->where('status',1);
		$this->db->where('ownerApproval',1);
		$this->db->limit(1);
		$query = $this->db->get();

		if ($query->num_rows() == 1) {
		return true;
		} else {
		return false;
		}
}

public function adminLogin($data) {
		$condition = "email =" . "'" . $data['email'] . "' AND " . "password =" . "'" . $data['password'];
		$this->db->select('*');
		$this->db->from('admin_login');
		// $this->db->where($condition);
		$this->db->where('adminUserName',$data['email']);
		$this->db->where('adminPassword',$data['password']);
		$this->db->limit(1);
		$query = $this->db->get();

		if ($query->num_rows() == 1) {
		return true;
		} else {
		return false;
		}
}


public function read_admin_information($email) {
			$condition = "adminUserName =" . "'" . $email . "'";
			$this->db->select('*');
			$this->db->from('admin_login');
			$this->db->where($condition);
			$this->db->limit(1);
			$query = $this->db->get();

			if ($query->num_rows() == 1) {
			return $query->result();
			} else {
			return false;
			}
	}
// Read data from database to show data in admin page
public function read_user_information($email) {
			$condition = "email =" . "'" . $email . "'";
			$this->db->select('*');
			$this->db->from('employee');
			$this->db->where($condition);
			$this->db->limit(1);
			$query = $this->db->get();

			if ($query->num_rows() == 1) {
			return $query->result();
			} else {
			return false;
			}
	}
	
	public function getdata($tableName)
    {
        $query=$this->db->get($tableName);
		
        return $query->result_array();
    }
    
	public function update($tblName, $data, $id) {
        $this->db->where('id', $id);
        return $this->db->update($tblName, $data);  
    }

    public function updatePassword($tblName, $data, $mobile) {
        $this->db->where('mobile', $mobile);
        $this->db->where('ownerApproval', '1');
        $this->db->where('isSalaryEmp', '1');
        $this->db->where('isLoginEmp', '1');
        return $this->db->update($tblName, $data);  
    }

     public function updateAdminPassword($tblName, $data, $mobile) {
        $this->db->where('adminMobile', $mobile);
        return $this->db->update($tblName, $data);  
    }

    public function show($tblName) {
        $query = $this->db->get($tblName);
		
        return $query->result_array();    
    }
      public function load($tblName, $id) {
        $this->db->where('id', $id);
        $query = $this->db->get($tblName);
		
        return $query->result_array();   
    }

    public function getUserByMobile($tblName, $mobile) {
        $this->db ->where('mobile', $mobile);
        $query = $this->db->get($tblName);
		
        return $query->result_array();   
    }

    public function getAdminByMobile($tblName, $mobile) {
        $this->db ->where('adminMobile', $mobile);
        $query = $this->db->get($tblName);
		
        return $query->result_array();   
    }
      
    public function checkTime($tblName, $time) {
        $this->db->where('fromTime >=', $time);
        $this->db->where('toTime <=', $time);
        $query = $this->db->get($tblName);
		
        return $query->result_array();   
    }
 
}
?>