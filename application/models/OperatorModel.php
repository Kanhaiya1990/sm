<?php
class OperatorModel extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function getdata($tableName)
    {
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getEmpData($tableName,$id){
        $this->db->distinct();
        $this->db->select('e1.name ename1,e2.name ename2,e3.name ename3,e4.name ename4');
        $this->db->join('allocations','allocations.id=allocation_sr_details.allocationId');
        $this->db->join('employee e1','e1.id=allocations.fieldStaffCode1');
        $this->db->join('employee e2','e2.id=allocations.fieldStaffCode2','left outer');
        $this->db->join('employee e3','e3.id=allocations.fieldStaffCode3','left outer');
        $this->db->join('employee e4','e4.id=allocations.fieldStaffCode4','left outer');
        $this->db->where('allocations.id',$id);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getBillsData($tableName)
    {
        $this->db->where('bills.deliveryStatus !=',"cancelled");
        $this->db->where('bills.SRAmt >',0);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getSrDetailData($tableName,$billId)
    {
        $this->db->where('billId',$billId);
        $this->db->order_by('id','desc');
        $this->db->limit(1);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getFsrBillsData($tableName)
    {
        // $this->db->where('bills.deliveryStatus !=',"cancelled");
        $this->db->where('bills.isFsrBill',1);
        $this->db->where('bills.isAllocated',0);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getSrInfo($tableName,$id){
        $this->db->where('allocationId',$id);
        $this->db->group_by('billId');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getSrInfoDetails($tableName,$id,$billId){
        $this->db->select('billsdetails.*,allocation_sr_details.quantity as ac_qty');
        $this->db->where('allocation_sr_details.allocationId',$id);
        $this->db->where('allocation_sr_details.billId',$billId);
        $this->db->join('billsdetails','billsdetails.id=allocation_sr_details.billItemId');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getSrBills($tableName,$id){
        $this->db->select('billsdetails.*,allocation_sr_details.quantity as ac_qty');
        $this->db->where('allocationId',$id);
        $this->db->join('billsdetails','billsdetails.id=allocation_sr_details.billItemId');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getRemark($tableName,$billId,$amount)
    {
        $this->db->where('bill_remark_history.billId',$billId);
        $this->db->where('bill_remark_history.amount',$amount);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getSrAllocationInfo(){
        $this->db->join('allocation_sr_details','allocations.id=allocation_sr_details.allocationId');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getAllocationSRAmount($tableName,$allocationId,$billId){
        $this->db->where('allocation_sr_details.allocationId',$billId);
        $this->db->where('allocation_sr_details.billId',$billId);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getCompanyPendingSrDetails($tblName,$cmp){
        $this->db->select('allocations.id as allocation_Id,allocations.company as allocation_Company,allocations.allocationCode as code,allocations.date as startDate,allocations.allocationCloseAt as endDate,employee.id as empId,employee.name as empName');
        $this->db->join('allocations','allocations.id=allocation_sr_details.allocationId');
        $this->db->join('employee','employee.id=allocations.fieldStaffCode1');
        $this->db->where('allocation_sr_details.allocationAmountStatus',0);
        $this->db->where('allocations.company',$cmp);
        $this->db->group_by('allocations.id');
        // $this->db->order_by('allocation_sr_details.id','desc');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getAllocationSalesman($tblName,$id){
        $this->db->select('bills.salesman,bills.retailerCode,bills.retailerName');
        $this->db->join('allocations','allocationsbills.allocationId=allocations.id');
        $this->db->join('bills','bills.id=allocationsbills.billId');
        $this->db->where('allocations.id', $id);
        $query = $this->db->get($tblName);
        
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

    public function getPendingSrDetails($tblName){
        $this->db->select('allocations.id as allocation_Id,allocations.company as allocation_Company,allocations.allocationCode as code,allocations.date as startDate,allocations.allocationCloseAt as endDate,employee.id as empId,employee.name as empName');
        $this->db->join('allocations','allocations.id=allocation_sr_details.allocationId');
        $this->db->join('employee','employee.id=allocations.fieldStaffCode1');
        $this->db->where('allocation_sr_details.allocationAmountStatus',0);
        $this->db->where('allocation_sr_details.quantity !=',0);
        $this->db->group_by('allocations.id');
        // $this->db->order_by('allocation_sr_details.id','desc');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getDetails($tblName){
        $this->db->select('allocations.id as allocation_Id,allocations.company as allocation_Company,allocations.allocationCode as code,allocations.routeCode as rcode,allocations.date as startDate,allocations.allocationCloseAt as endDate,e1.id as empId1,e1.name as empName1,e2.id as empId2,e2.name as empName2');
        $this->db->join('allocations','allocations.id=allocation_sr_details.allocationId');
        $this->db->join('employee e1','e1.id=allocations.fieldStaffCode1','left outer');
        $this->db->join('employee e2','e2.id=allocations.fieldStaffCode2','left outer');
        $this->db->where('allocation_sr_details.operatorStatus',0);
        $this->db->where('allocation_sr_details.quantity !=',0);
        $this->db->group_by('allocations.id');
        // $this->db->order_by('allocation_sr_details.id','desc');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getDetailsWithCompany($tblName,$company){
        $this->db->select('allocations.id as allocation_Id,allocations.company as allocation_Company,allocations.allocationCode as code,allocations.routeCode as rcode,allocations.date as startDate,allocations.allocationCloseAt as endDate,e1.id as empId1,e1.name as empName1,e2.id as empId2,e2.name as empName2');
        $this->db->join('allocations','allocations.id=allocation_sr_details.allocationId');
        $this->db->join('employee e1','e1.id=allocations.fieldStaffCode1','left outer');
        $this->db->join('employee e2','e2.id=allocations.fieldStaffCode2','left outer');
        $this->db->where('allocation_sr_details.operatorStatus',0);
        $this->db->where('allocation_sr_details.quantity !=',0);
        $this->db->where('allocations.company',$company);
        $this->db->group_by('allocation_sr_details.allocationId');
        // $this->db->order_by('allocation_sr_details.id','desc');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getOfficeDetailsWithDate($tblName){
        $this->db->distinct();
        $this->db->select('allocations_officebills.updatedAt');
         $this->db->join('bills','bills.id=allocations_officebills.billId');
        $this->db->where('allocations_officebills.operatorApproval',0);
        $this->db->where('allocations_officebills.transactionType','fsr');
        $this->db->group_by('DATE(allocations_officebills.updatedAt)');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getOfficeDetailsWithDateCompany($tblName,$company){
        $this->db->distinct();
        $this->db->select('allocations_officebills.updatedAt');
        $this->db->join('allocations_officeadjustment','allocations_officeadjustment.id=allocations_officebills.allocationId');
        $this->db->join('bills','bills.id=allocations_officebills.billId');
        $this->db->where('allocations_officebills.operatorApproval',0);
        $this->db->where('allocations_officebills.transactionType','fsr');
        $this->db->where('allocations_officeadjustment.company',$company);
        $this->db->group_by('DATE(allocations_officebills.updatedAt)');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getDetailsWithDate($tblName){
        $this->db->distinct();
        $this->db->select('allocation_sr_details.createdAt');
         $this->db->join('bills','bills.id=allocation_sr_details.billId');
        $this->db->where('allocation_sr_details.operatorStatus',0);
        $this->db->where('allocation_sr_details.quantity !=',0);
        $this->db->where('bills.isDeliverySlipBill !=',1);
        $this->db->group_by('DATE(allocation_sr_details.createdAt)');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getDetailsWithDateCompany($tblName,$company){
        $this->db->distinct();
        $this->db->select('allocation_sr_details.createdAt');
        $this->db->join('allocations','allocations.id=allocation_sr_details.allocationId','');
        $this->db->join('bills','bills.id=allocation_sr_details.billId');
        $this->db->where('allocation_sr_details.operatorStatus',0);
        $this->db->where('allocation_sr_details.quantity !=',0);
        $this->db->where('allocations.company',$company);
        $this->db->where('bills.isDeliverySlipBill !=',1);
        $this->db->group_by('DATE(allocation_sr_details.createdAt)');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getPendingCollectionDetailsWithCompany($tblName,$company){
        $this->db->select('allocations.id as allocation_Id,allocations.company as allocation_Company,allocations.allocationCode as code,allocations.date as startDate,allocations.allocationCloseAt as endDate,employee.id as empId,employee.name as empName,route.name as routeName');
        $this->db->join('allocations','allocations.id=billpayments.allocationId');
        $this->db->join('employee','employee.id=allocations.fieldStaffCode1');
        $this->db->join('route','route.id=allocations.routId');
        $this->db->where('billpayments.operatorApproval',0);
        $this->db->where('allocations.company',$company);
         $this->db->where('billpayments.isLostStatus !=',1);
        $this->db->where('billpayments.operatorApproval',0);
        $this->db->where('billpayments.ownerApproval !=',2);
        $this->db->where('billpayments.chequeStatus !=','Bounced');
        $this->db->where('billpayments.chequeStatus !=','Bounced&Returned');
        $this->db->group_by('billpayments.allocationId');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getPendingCollectionDetails($tblName){
        $this->db->select('allocations.id as allocation_Id,allocations.company as allocation_Company,allocations.allocationCode as code,allocations.date as startDate,allocations.allocationCloseAt as endDate,employee.id as empId,employee.name as empName,route.name as routeName');
        $this->db->join('allocations','allocations.id=billpayments.allocationId');
        $this->db->join('employee','employee.id=allocations.fieldStaffCode1');
        $this->db->join('route','route.id=allocations.routId');
         $this->db->where('billpayments.isLostStatus !=',1);
        $this->db->where('billpayments.operatorApproval',0);
        $this->db->where('billpayments.ownerApproval !=',2);
        $this->db->where('billpayments.chequeStatus !=','Bounced');
        $this->db->where('billpayments.chequeStatus !=','Bounced&Returned');
        $this->db->group_by('billpayments.allocationId');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    //office allocation
    public function getPendingOfficeCollectionDetails($tblName){
        $this->db->select('allocations_officeadjustment.*,employee.id as empId,employee.name as empName');
        $this->db->join('employee','employee.id=allocations_officeadjustment.createdBy');
        $this->db->join('allocations_officebills','allocations_officeadjustment.id=allocations_officebills.allocationId');
        $this->db->where('allocations_officeadjustment.isAllocationComplete',1);
        $this->db->where('allocations_officebills.operatorApproval',0);
        $this->db->where('allocations_officebills.amount !=',0);
        $this->db->where('allocations_officebills.transactionType !=','pending');
        $this->db->group_by('allocations_officeadjustment.id');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getCompanyPendingOfficeCollectionDetails($tblName,$name){
        $this->db->select('allocations_officeadjustment.*,employee.id as empId,employee.name as empName');
        $this->db->join('allocations_officebills','allocations_officeadjustment.id=allocations_officebills.allocationId');
        $this->db->join('employee','employee.id=allocations_officeadjustment.createdBy');
        $this->db->where('allocations_officeadjustment.company',$name);
        $this->db->where('allocations_officeadjustment.isAllocationComplete',1);
        $this->db->where('allocations_officebills.operatorApproval',0);
        $this->db->where('allocations_officebills.amount !=',0);
        $this->db->where('allocations_officebills.transactionType !=','pending');
        $this->db->group_by('allocations_officeadjustment.id');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }
    //end office collection

    public function getPendingCollectionWithoutAllocation($tblName){
        $this->db->select('employee.id as empId,employee.name as empName');
        $this->db->join('employee','employee.id=billpayments.empId');
        $this->db->where('billpayments.operatorApproval',0);
        $this->db->group_by('billpayments.allocationId',0);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getOfficeSr($tblName){
        $this->db->select('allocation_sr_details.id as sr_id,allocation_sr_details.quantity as sr_qty,allocation_sr_details.allocationId as sr_allocationId,bills.id as billId,bills.billNo as billNo,bills.compName as compName,bills.retailerName as retailerName,bills.retailerCode as retailerCode,billsdetails.productName as prodName,billsdetails.productCode as prodCode');
        $this->db->join('bills','bills.id=allocation_sr_details.billId');
        $this->db->join('billsdetails','billsdetails.id=allocation_sr_details.billItemId');
        $this->db->where('allocation_sr_details.allocationId',0);
         $this->db->where('allocation_sr_details.quantity >',0);
        $this->db->where('allocation_sr_details.operatorStatus',0);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getPendingOfficeCollection($tblName){
        $this->db->select('bills.id as billId,bills.billNo as billNo,bills.date as billDate,bills.compName as compName,bills.retailerName as retailerName,billpayments.id as paymentId,billpayments.paidAmount as paidAmount,billpayments.paymentMode as paymentMode,billpayments.chequeStatus as chequeStatus,billpayments.neftNo as neftNo,billpayments.chequeNo as chequeNo,billpayments.chequeStatusDate as chequeStatusDate,billpayments.neftDate as neftDate,billpayments.ownerApproval as ownerApproval,billpayments.allocationId as allocationId');
        $this->db->join('bills','bills.id=billpayments.billId');
        $this->db->where('billpayments.allocationId',0);
        $this->db->where('billpayments.isLostStatus !=',1);
        $this->db->where('billpayments.operatorApproval',0);
        $this->db->where('billpayments.paidAmount !=',0);
        $this->db->where('billpayments.ownerApproval !=',2);
        $this->db->where('billpayments.chequeStatus !=','Bounced');
        $this->db->where('billpayments.chequeStatus !=','Bounced&Returned');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getCountPendingOfficeCollection($tblName){
        $this->db->select('count(bills.id) as countBill');
        $this->db->join('bills','bills.id=billpayments.billId');
        $this->db->where('billpayments.allocationId',0);
        $this->db->where('billpayments.isLostStatus !=',1);
        $this->db->where('billpayments.operatorApproval',0);
        $this->db->where('billpayments.paidAmount !=',0);
        $this->db->where('billpayments.ownerApproval !=',2);
        $this->db->where('billpayments.chequeStatus !=','Bounced');
        $this->db->where('billpayments.chequeStatus !=','Bounced&Returned');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getPendingOfficeCollectionByAllocation($tblName,$id){
        $this->db->select('bills.id as billId,bills.billNo as billNo,bills.date as billDate,bills.compName as compName,bills.retailerName as retailerName,billpayments.id as paymentId,billpayments.paidAmount as paidAmount,billpayments.paymentMode as paymentMode,billpayments.chequeStatus as chequeStatus,billpayments.neftNo as neftNo,billpayments.chequeNo as chequeNo,billpayments.chequeStatusDate as chequeStatusDate,billpayments.neftDate as neftDate,billpayments.ownerApproval as ownerApproval,billpayments.allocationId as allocationId');
        $this->db->join('bills','bills.id=billpayments.billId');
        $this->db->where('billpayments.allocationId',$id);
        $this->db->where('billpayments.isLostStatus !=',1);
        $this->db->where('billpayments.operatorApproval',0);
        $this->db->where('billpayments.paidAmount !=',0);
        $this->db->where('billpayments.ownerApproval !=',2);
        $this->db->where('billpayments.chequeStatus !=','Bounced');
        $this->db->where('billpayments.chequeStatus !=','Bounced&Returned');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getCountPendingOfficeCollectionByAllocation($tblName,$id){
        $this->db->select('count(bills.id) as countBill');
        $this->db->join('bills','bills.id=billpayments.billId');
        $this->db->where('billpayments.allocationId',$id);
        $this->db->where('billpayments.isLostStatus !=',1);
        $this->db->where('billpayments.operatorApproval',0);
        $this->db->where('billpayments.paidAmount !=',0);
        $this->db->where('billpayments.ownerApproval !=',2);
        $this->db->where('billpayments.chequeStatus !=','Bounced');
        $this->db->where('billpayments.chequeStatus !=','Bounced&Returned');
        // $this->db->group_by('billpayments.billId');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getOfficeAdjustmentCollectionDetails($tblName,$id){
        $this->db->select('allocations_officebills.*,bills.billNo,bills.date,bills.retailerName,allocations_officeadjustment.remark');
        $this->db->join('bills','bills.id=allocations_officebills.billId');
        $this->db->join('allocations_officeadjustment','allocations_officebills.allocationId=allocations_officeadjustment.id');
        $this->db->where('allocationId',$id);
        $this->db->where('operatorApproval',0);
        $this->db->where('amount !=',0);
        $this->db->where('transactionType !=','pending');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getCountOfficeAdjustmentCollectionDetails($tblName,$id){
        $this->db->select('count(bills.id) as countBill');
        $this->db->join('bills','bills.id=allocations_officebills.billId');
        $this->db->join('allocations_officeadjustment','allocations_officebills.allocationId=allocations_officeadjustment.id');
        $this->db->where('allocationId',$id);
        $this->db->where('operatorApproval',0);
        $this->db->where('amount !=',0);
        $this->db->where('transactionType !=','pending');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getAllocationDetails($tblName,$id){
        $this->db->select('allocations.*,route.name as rname,e1.name as e1name,e2.name as e2name,e3.name as e3name,e4.name as e4name');
        $this->db->join('route','route.id=allocations.routId');
        $this->db->join('employee e1','e1.id=allocations.fieldStaffCode1');
        $this->db->join('employee e2','e2.id=allocations.fieldStaffCode2','left outer');
        $this->db->join('employee e3','e3.id=allocations.fieldStaffCode3','left outer');
        $this->db->join('employee e4','e4.id=allocations.fieldStaffCode4','left outer');

        $this->db->where('allocations.id',$id);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getCountForBills($id){
        $query="SELECT count(billId) as billcount,billId FROM `allocation_sr_details` where allocationId=$id group by billId";
        $res=$this->db->query($query);
        
        return $res->result_array();
    }

    public function getBillPaymentCountForBills($id){
        $query="SELECT count(billId) as billcount FROM `allocationsbills` where allocationId=$id";
        $res=$this->db->query($query);
        
        return $res->result_array();
    }

    public function getOfficeBillPaymentCountForBills($id){
        $query="SELECT count(billId) as billcount FROM `allocations_officebills` where allocationId=$id";
        $res=$this->db->query($query);
        
        return $res->result_array();
    }

    public function getBillPaymentCount(){
        $query="SELECT count(DISTINCT billId) as billcount FROM `billpayments` where allocationId=0";
        $res=$this->db->query($query);
        
        return $res->result_array();
    }

    public function getQtySumByBill($id){
        $query="SELECT sum(qty) as billcount FROM billsdetails where billId=$id group by billId";
        $res=$this->db->query($query);
        
        return $res->result_array();
    }
    public function srAcceptedByBill($id){
        $query="SELECT sum(quantity) as billcount FROM allocation_sr_details where billId=$id and operatorStatus=0 group by billId";
        $res=$this->db->query($query);
        
        return $res->result_array();
    }

    public function getSrQtySumByBill($id,$allocationId){
        $query="SELECT sum(quantity) as billcount FROM allocation_sr_details where billId=$id and allocationId=$allocationId and operatorStatus=0 group by billId";
        $res=$this->db->query($query);
        
        return $res->result_array();
    }

    public function getAllocationSrDetailsDate($tblName,$date){
        $this->db->distinct();
        $this->db->select('allocations.allocationCode as alCode,allocation_sr_details.id as sr_id,allocation_sr_details.quantity as sr_qty,allocation_sr_details.allocationId as sr_allocationId,bills.id as billId,bills.billNo as billNo,bills.compName as compName,bills.salesman as salesman,bills.routeName as routeName,bills.isFsrBill as isFsrBill,bills.retailerName as retailerName,bills.retailerCode as retailerCode,billsdetails.productName as prodName,billsdetails.productCode as prodCode');
        $this->db->join('allocations','allocations.id=allocation_sr_details.allocationId','left outer');
        $this->db->join('bills','bills.id=allocation_sr_details.billId');
        $this->db->join('billsdetails','billsdetails.id=allocation_sr_details.billItemId');
        $this->db->where('DATE(allocation_sr_details.createdAt)',$date);
        $this->db->where('allocation_sr_details.operatorStatus',0);
        $this->db->where('allocation_sr_details.quantity >',0);
        $this->db->where('bills.isDeliverySlipBill !=',1);
        $this->db->where('bills.isFsrBill !=',1);
        // $this->db->group_by('allocation_sr_details.allocationId');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getAllocationFsrDetailsDate($tblName,$date){
        $this->db->distinct();
        $this->db->select('allocations.allocationCode as alCode,allocation_sr_details.id as sr_id,allocation_sr_details.quantity as sr_qty,allocation_sr_details.allocationId as sr_allocationId,bills.id as billId,bills.billNo as billNo,bills.compName as compName,bills.salesman as salesman,bills.routeName as routeName,bills.isFsrBill as isFsrBill,bills.retailerName as retailerName,bills.retailerCode as retailerCode,billsdetails.productName as prodName,billsdetails.productCode as prodCode');
        $this->db->join('allocation_sr_details','bills.id=allocation_sr_details.billId','left outer');
        $this->db->join('allocations','allocations.id=allocation_sr_details.allocationId','left outer');
        $this->db->join('billsdetails','billsdetails.id=allocation_sr_details.billItemId');
        $this->db->where('bills.isDeliverySlipBill !=',1);
        $this->db->where('bills.isFsrBill',1);
        $this->db->where('DATE(allocation_sr_details.createdAt)',$date);
        $this->db->where('allocation_sr_details.operatorStatus',0);
        $this->db->where('allocation_sr_details.quantity >',0);
        $this->db->group_by('bills.id');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getOfficeAllocationSrDetailsDate($tblName,$date){
        $this->db->distinct();
        $this->db->select('allocations_officeadjustment.allocationCode as alCode,allocations_officebills.id as sr_id,allocations_officebills.allocationId as sr_allocationId,bills.id as billId,bills.billNo as billNo,bills.compName as compName,bills.salesman as salesman,bills.routeName as routeName,bills.isFsrBill as isFsrBill,bills.retailerName as retailerName,bills.retailerCode as retailerCode');
        $this->db->join('allocations_officeadjustment','allocations_officeadjustment.id=allocations_officebills.allocationId');
        $this->db->join('bills','bills.id=allocations_officebills.billId');
        $this->db->where('DATE(allocations_officebills.updatedAt)',$date);
        $this->db->where('allocations_officebills.operatorApproval',0);
        $this->db->where('allocations_officebills.transactionType','fsr');
        // $this->db->group_by('bills.id');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getAllocationSrDetails($tblName,$id){
        $this->db->select('allocation_sr_details.id as sr_id,allocation_sr_details.quantity as sr_qty,allocation_sr_details.allocationId as sr_allocationId,bills.id as billId,bills.billNo as billNo,bills.compName as compName,bills.retailerName as retailerName,bills.retailerCode as retailerCode,billsdetails.productName as prodName,billsdetails.productCode as prodCode');
        $this->db->join('bills','bills.id=allocation_sr_details.billId');
        $this->db->join('billsdetails','billsdetails.id=allocation_sr_details.billItemId');
        $this->db->where('allocation_sr_details.allocationId',$id);
        $this->db->where('allocation_sr_details.operatorStatus',0);
        $this->db->where('allocation_sr_details.quantity >',0);
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getGstBillSrDetails($tblName){
        $this->db->select('allocation_sr_details.id as sr_id,allocation_sr_details.quantity as sr_qty,allocation_sr_details.allocationId as sr_allocationId,bills.id as billId,bills.billNo as billNo,bills.compName as compName,bills.retailerName as retailerName,bills.retailerCode as retailerCode,billsdetails.productName as prodName,billsdetails.productCode as prodCode,billsdetails.qty as prodQty,billsdetails.netAmount as prodNetAmount');
        $this->db->join('bills','bills.id=allocation_sr_details.billId');
        $this->db->join('billsdetails','billsdetails.id=allocation_sr_details.billItemId');
        $this->db->join('retailer','bills.retailerCode=retailer.code');
        $this->db->where('allocation_sr_details.isGstBill',0);
        $this->db->where('allocation_sr_details.operatorStatus',1);
        $this->db->where('allocation_sr_details.quantity >',0);
        $this->db->where('retailer.gstIn !=',"NULL");
        $this->db->where('retailer.gstIn !=',"");
        $this->db->where('bills.isFsrBill !=',"1");//
        $this->db->order_by('bills.id','asc');
        $query=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function routeInfo($tableName,$rname,$rcode)
    {
        $this->db->where('code',$rcode);
        $this->db->where('name',$rname);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function retailerInfo($tableName,$rName,$rcode)
    {
        $this->db->where('name',$rName);
        $this->db->where('code',$rcode);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getAllCompanies($tableName)
    {
        $this->db->select('name');
        $this->db->distinct();
        $query=$this->db->get($tableName);
        
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

    public function updateAllocationSr($tblName, $data, $id) {
        $this->db->where('allocationId', $id);
        return $this->db->update($tblName, $data);  
    }

    public function updateBillRoute($tblName,$data, $retailerName, $retailerCode) {
        $this->db->where('retailerName', $retailerName);
        $this->db->where('retailerCode', $retailerCode);
        return $this->db->update($tblName, $data);  
    }

     public function updatePendingFSR($tblName,$data, $billId, $allocationId) {
        $this->db->where('billId', $billId);
        $this->db->where('allocationId', $allocationId);
        return $this->db->update($tblName, $data);  
    }

    public function show($tblName) {
        $query = $this->db->get($tblName);
        
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
        
        return $query->result_array();   
    }

    public function getBillId($tblName, $id) {
        $this->db->where('billNo', $id);
        $query = $this->db->get($tblName);
        
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