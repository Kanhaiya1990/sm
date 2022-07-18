<?php
class ReportModel extends CI_Model {

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

    public function getSumByType($tableName,$id,$type){
        $sql="SELECT sum(paidAmount) as amt FROM `billpayments` WHERE billId=".$id." and paymentMode='".$type."' and isLostStatus !=1";    
        $query = $this->db->query($sql);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getUniqueData($tableName)
    {
        $this->db->distinct();
        $this->db->select('retailerCode,retailerName,retailer.gstIn');
        $this->db->join('retailer','bills.retailerCode=retailer.code','left outer');
        $this->db->where('isDeliverySlipBill',0);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getUniqueDataForDeliveryslip($tableName)
    {
        $this->db->distinct();
        $this->db->select('retailerCode,retailerName');
        $this->db->where('isDeliverySlipBill',1);
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

    public function show($tblName) {
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();    
    }

    public function getSalesman($tblName) {
        $this->db->where('designation','salesman');
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();    
    }

    public function getBillsSalesman($tblName) {
        $this->db->distinct();
        $this->db->select('salesman');
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

    public function checkDataWithDates($tblName, $fromDate,$toDate) {
        $this->db->where('billpayments.allocationId >', 0);
        $this->db->where('DATE(billpayments.date) >=', $fromDate);
        $this->db->where('DATE(billpayments.date) <=', $toDate);
        $this->db->where('billpayments.isAllocationClosed',0);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();   
    }

    public function getBillsDetailsUsingDates($tblName,$fromDate,$toDate){
        $this->db->select('billpayments.*,bills.id as bid,bills.date as bdate,bills.billNo,bills.retailerName as rtname,bills.retailerCode as rtCode,bills.routeName,bills.salesman');
        $this->db->join('billpayments','billpayments.billId=bills.id');
        $this->db->where('bills.date >=', $fromDate);
        $this->db->where('bills.date <=', $toDate);
        $this->db->where('billpayments.paidAmount >',0);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    public function getCompanyData($tblName,$company,$fromDate,$toDate){
        // $this->db->select('bills.*');
        $this->db->where('bills.compName', $company);
        $this->db->where('bills.isDeliverySlipBill', 0);
        $this->db->where('bills.date >=', $fromDate);
        $this->db->where('bills.date <=', $toDate);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    public function getCompanyDataWithDeliveryslip($tblName,$company,$fromDate,$toDate){
        // $this->db->select('bills.*');
        $this->db->where('bills.compName', $company);
        $this->db->where('bills.isDeliverySlipBill', 1);
        $this->db->where('bills.date >=', $fromDate);
        $this->db->where('bills.date <=', $toDate);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    public function getCompanyDataForInvoice($tblName,$company,$fromDate,$toDate){
        $this->db->where('bills.compName', $company);
        $this->db->where('bills.isDeliverySlipBill', 0);
        $this->db->where('bills.date >=', $fromDate);
        $this->db->where('bills.date <=', $toDate);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    public function getCompanyDataForInvoiceWithDeliveryslip($tblName,$company,$fromDate,$toDate){
        $this->db->where('bills.compName', $company);
        $this->db->where('bills.isDeliverySlipBill', 1);
        $this->db->where('bills.date >=', $fromDate);
        $this->db->where('bills.date <=', $toDate);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    public function getCompanyDataByTypeChequeReceived($tblName,$company,$fromDate,$toDate,$type){
        $this->db->select('billpayments.*,bills.id as bid,bills.date as bdate,bills.billNo,bills.retailerName as rtname,bills.retailerCode as rtCode,bills.routeName,bills.salesman,bills.isDeliverySlipBill');
        $this->db->join('billpayments','billpayments.billId=bills.id');
        $this->db->where('bills.compName', $company);
        $this->db->where('bills.isDeliverySlipBill', 0);
        $this->db->where('DATE(billpayments.date) >=', $fromDate);
        $this->db->where('DATE(billpayments.date) <=', $toDate);
        $this->db->where('billpayments.paymentMode',$type);
        $this->db->where('billpayments.isLostStatus',2);
        $this->db->where('billpayments.paidAmount !=',0);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    public function getCompanyDataByTypeChequeReceivedForDeliveryslip($tblName,$company,$fromDate,$toDate,$type){
        $this->db->select('billpayments.*,bills.id as bid,bills.date as bdate,bills.billNo,bills.retailerName as rtname,bills.retailerCode as rtCode,bills.routeName,bills.salesman,bills.isDeliverySlipBill');
        $this->db->join('billpayments','billpayments.billId=bills.id');
        $this->db->where('bills.isDeliverySlipBill', 1);
        $this->db->where('DATE(billpayments.date) >=', $fromDate);
        $this->db->where('DATE(billpayments.date) <=', $toDate);
        $this->db->where('billpayments.paymentMode',$type);
        $this->db->where('billpayments.isLostStatus',2);
        $this->db->where('billpayments.paidAmount !=',0);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    public function getCompanyDataByTypeChequeTransactionChange($tblName,$company,$fromDate,$toDate,$type){
        $this->db->select('billpayments.*,bills.id as bid,bills.date as bdate,bills.billNo,bills.retailerName as rtname,bills.retailerCode as rtCode,bills.routeName,bills.salesman,bills.isDeliverySlipBill');
        $this->db->join('billpayments','billpayments.billId=bills.id');
        $this->db->where('bills.compName', $company);
        $this->db->where('bills.isDeliverySlipBill', 0);
        $this->db->where('DATE(billpayments.date) >=', $fromDate);
        $this->db->where('DATE(billpayments.date) <=', $toDate);
        $this->db->where('billpayments.paymentMode',$type);
        $this->db->where('billpayments.paidAmount !=',0);
        $this->db->where('billpayments.tallystatus','Cheque_Change');
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    public function getCompanyDataByTypeChequeTransactionChangeForDeliveryslip($tblName,$company,$fromDate,$toDate,$type){
        $this->db->select('billpayments.*,bills.id as bid,bills.date as bdate,bills.billNo,bills.retailerName as rtname,bills.retailerCode as rtCode,bills.routeName,bills.salesman,bills.isDeliverySlipBill');
        $this->db->join('billpayments','billpayments.billId=bills.id');
        $this->db->where('bills.isDeliverySlipBill', 1);
        $this->db->where('DATE(billpayments.date) >=', $fromDate);
        $this->db->where('DATE(billpayments.date) <=', $toDate);
        $this->db->where('billpayments.paymentMode',$type);
        $this->db->where('billpayments.paidAmount !=',0);
        $this->db->where('billpayments.tallystatus','Cheque_Change');
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    public function getCompanyDataByTypeChequeBounced($tblName,$company,$fromDate,$toDate,$type){
        $this->db->select('billpayments.*,bills.id as bid,bills.date as bdate,bills.billNo,bills.retailerName as rtname,bills.retailerCode as rtCode,bills.routeName,bills.salesman,bills.isDeliverySlipBill');
        $this->db->join('billpayments','billpayments.billId=bills.id');
        $this->db->where('bills.compName', $company);
        $this->db->where('bills.isDeliverySlipBill', 0);
        $this->db->where('DATE(billpayments.date) >=', $fromDate);
        $this->db->where('DATE(billpayments.date) <=', $toDate);
        $this->db->where('billpayments.paymentMode',$type);
        $this->db->where('billpayments.paidAmount !=',0);
        // $this->db->where('billpayments.isLostStatus',2);
        $this->db->where('billpayments.tallyStatus','Cheque_Bounce');
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    public function getCompanyDataByTypeChequeBouncedForDeliveryslip($tblName,$company,$fromDate,$toDate,$type){
        $this->db->select('billpayments.*,bills.id as bid,bills.date as bdate,bills.billNo,bills.retailerName as rtname,bills.retailerCode as rtCode,bills.routeName,bills.salesman,bills.isDeliverySlipBill');
        $this->db->join('billpayments','billpayments.billId=bills.id');
        $this->db->where('bills.isDeliverySlipBill', 1);
        $this->db->where('DATE(billpayments.date) >=', $fromDate);
        $this->db->where('DATE(billpayments.date) <=', $toDate);
        $this->db->where('billpayments.paymentMode',$type);
        $this->db->where('billpayments.paidAmount !=',0);
        // $this->db->where('billpayments.isLostStatus',2);
        $this->db->where('billpayments.tallyStatus','Cheque_Bounce');
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    public function getCompanyDataByTypeBouncePenalty($tblName,$company,$fromDate,$toDate,$type){
        $this->db->select('billpayments.*,bills.id as bid,bills.date as bdate,bills.billNo,bills.retailerName as rtname,bills.retailerCode as rtCode,bills.routeName,bills.salesman,bills.isDeliverySlipBill');
        $this->db->join('billpayments','billpayments.billId=bills.id');
        $this->db->where('bills.compName', $company);
        $this->db->where('bills.isDeliverySlipBill', 0);
        $this->db->where('DATE(billpayments.date) >=', $fromDate);
        $this->db->where('DATE(billpayments.date) <=', $toDate);
        $this->db->where('billpayments.paymentMode',$type);
        $this->db->where('billpayments.paidAmount !=',0);
        // $this->db->where('billpayments.penalty >',0);
        $this->db->where('billpayments.tallyStatus','Cheque_Bounce_penalty');
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    public function getCompanyDataByTypeBouncePenaltyForDeliveryslip($tblName,$company,$fromDate,$toDate,$type){
        $this->db->select('billpayments.*,bills.id as bid,bills.date as bdate,bills.billNo,bills.retailerName as rtname,bills.retailerCode as rtCode,bills.routeName,bills.salesman,bills.isDeliverySlipBill');
        $this->db->join('billpayments','billpayments.billId=bills.id');
        $this->db->where('bills.compName', $company);
        $this->db->where('bills.isDeliverySlipBill', 1);
        $this->db->where('DATE(billpayments.date) >=', $fromDate);
        $this->db->where('DATE(billpayments.date) <=', $toDate);
        $this->db->where('billpayments.paymentMode',$type);
        $this->db->where('billpayments.paidAmount !=',0);
        // $this->db->where('billpayments.penalty >',0);
        $this->db->where('billpayments.tallyStatus','Cheque_Bounce_penalty');
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    public function getCompanyDataByType($tblName,$company,$fromDate,$toDate,$type){
        $this->db->select('billpayments.*,bills.id as bid,bills.date as bdate,bills.billNo,bills.retailerName as rtname,bills.retailerCode as rtCode,bills.routeName,bills.salesman,bills.isDeliverySlipBill');
        $this->db->join('billpayments','billpayments.billId=bills.id');
        $this->db->where('bills.compName', $company);
        $this->db->where('bills.isDeliverySlipBill', 0);
        $this->db->where('DATE(billpayments.date) >=', $fromDate);
        $this->db->where('DATE(billpayments.date) <=', $toDate);
        $this->db->where('billpayments.paymentMode',$type);
        $this->db->where('billpayments.paidAmount !=',0);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
    //    print_r($this->db->last_query());
        return $query->result_array();  
    }

    public function getCompanyDataByTypeForDeliveryslip($tblName,$company,$fromDate,$toDate,$type){
        $this->db->select('billpayments.*,bills.id as bid,bills.date as bdate,bills.billNo,bills.retailerName as rtname,bills.retailerCode as rtCode,bills.routeName,bills.salesman,bills.isDeliverySlipBill');
        $this->db->join('billpayments','billpayments.billId=bills.id');
        // $this->db->where('bills.compName', $company);
        $this->db->where('bills.isDeliverySlipBill', 1);
        $this->db->where('DATE(billpayments.date) >=', $fromDate);
        $this->db->where('DATE(billpayments.date) <=', $toDate);
        $this->db->where('billpayments.paymentMode',$type);
        $this->db->where('billpayments.paidAmount !=',0);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
    //    print_r($this->db->last_query());
        return $query->result_array();  
    }

    public function getCompanyDataByTypeForFSR($tblName,$company,$fromDate,$toDate,$type){
        $this->db->select('billpayments.*,bills.id as bid,bills.date as bdate,bills.billNo,bills.retailerName as rtname,bills.retailerCode as rtCode,bills.routeName,bills.salesman,bills.isDeliverySlipBill');
        $this->db->join('billpayments','billpayments.billId=bills.id');
        $this->db->where('bills.compName', $company);
        $this->db->where('bills.isDeliverySlipBill', 0);
        $this->db->where('bills.isFsrBill', 1);
        $this->db->where('DATE(billpayments.date) >=', $fromDate);
        $this->db->where('DATE(billpayments.date) <=', $toDate);
        $this->db->where('billpayments.paymentMode',$type);
        $this->db->where('billpayments.paidAmount !=',0);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    public function getCompanyDataByTypeForFSRForDeliveryslip($tblName,$company,$fromDate,$toDate,$type){
        $this->db->select('billpayments.*,bills.id as bid,bills.date as bdate,bills.billNo,bills.retailerName as rtname,bills.retailerCode as rtCode,bills.routeName,bills.salesman,bills.isDeliverySlipBill');
        $this->db->join('billpayments','billpayments.billId=bills.id');
        $this->db->where('bills.isDeliverySlipBill', 1);
        $this->db->where('bills.isFsrBill', 1);
        $this->db->where('DATE(billpayments.date) >=', $fromDate);
        $this->db->where('DATE(billpayments.date) <=', $toDate);
        $this->db->where('billpayments.paymentMode',$type);
        $this->db->where('billpayments.paidAmount !=',0);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    public function getCompanyDataByTypeNEFT($tblName,$company,$fromDate,$toDate,$type){
        $this->db->select('billpayments.*,bills.id as bid,bills.date as bdate,bills.billNo,bills.retailerName as rtname,bills.retailerCode as rtCode,bills.routeName,bills.salesman,bills.isDeliverySlipBill');
        $this->db->join('billpayments','billpayments.billId=bills.id');
        $this->db->where('bills.compName', $company);
        $this->db->where('bills.isDeliverySlipBill', 0);
        $this->db->where('DATE(billpayments.date) >=', $fromDate);
        $this->db->where('DATE(billpayments.date) <=', $toDate);
        $this->db->where('billpayments.paymentMode',$type);
        $this->db->where('billpayments.paidAmount !=',0);
        // $this->db->where('billpayments.paymentMode',$type);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        // echo $this->db->last_query();
        return $query->result_array();  
    }

    public function getCompanyDataByTypeNEFTForDeliveryslip($tblName,$company,$fromDate,$toDate,$type){
        $this->db->select('billpayments.*,bills.id as bid,bills.date as bdate,bills.billNo,bills.retailerName as rtname,bills.retailerCode as rtCode,bills.routeName,bills.salesman,bills.isDeliverySlipBill');
        $this->db->join('billpayments','billpayments.billId=bills.id');
        $this->db->where('bills.isDeliverySlipBill', 1);
        $this->db->where('DATE(billpayments.date) >=', $fromDate);
        $this->db->where('DATE(billpayments.date) <=', $toDate);
        $this->db->where('billpayments.paymentMode',$type);
        $this->db->where('billpayments.paidAmount !=',0);
        // $this->db->where('billpayments.paymentMode',$type);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        // echo $this->db->last_query();
        return $query->result_array();  
    }

    public function getBillsDetailsUsingDatesWithCompany($tblName,$fromDate,$toDate,$company){
        $this->db->select('billpayments.*,bills.id as bid,bills.date as bdate,bills.billNo,bills.retailerName as rtname,bills.retailerCode as rtCode,bills.routeName,bills.salesman,bills.isDeliverySlipBill');
        $this->db->join('billpayments','billpayments.billId=bills.id');
        $this->db->where('bills.date >=', $fromDate);
        $this->db->where('bills.date <=', $toDate);
        $this->db->where('bills.compName', $company);
        $this->db->where('billpayments.paidAmount >',0);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    //for Datewise Collection report
    public function getDatewiseCollectionsDetailsUsingDates($tblName,$fromDate,$toDate){
        $this->db->select('billpayments.*,bills.id as bid,bills.date as bdate,bills.billNo,bills.compName,bills.retailerName as rtname,bills.salesman,bills.retailerCode as rtCode,bills.routeName,bills.salesman,bills.isDeliverySlipBill');
        $this->db->join('billpayments','billpayments.billId=bills.id');
        $this->db->where('DATE(billpayments.date) >=', $fromDate);
        $this->db->where('DATE(billpayments.date) <=', $toDate);
        $this->db->where('billpayments.paidAmount >',0);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    //for Datewise Collection report using company
    public function getDatewiseCollectionsDetailsUsingCompany($tblName,$fromDate,$toDate,$compName){
        $this->db->select('billpayments.*,bills.id as bid,bills.date as bdate,bills.billNo,bills.compName,bills.retailerName as rtname,bills.salesman,bills.retailerCode as rtCode,bills.routeName,bills.salesman,bills.isDeliverySlipBill');
        $this->db->join('billpayments','billpayments.billId=bills.id');
        $this->db->where('DATE(billpayments.date) >=', $fromDate);
        $this->db->where('DATE(billpayments.date) <=', $toDate);
        $this->db->where('billpayments.paidAmount >',0);
        $this->db->where('bills.compName',$compName);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    /////

     //for allocation wise Collection report
    public function getAllocationwiseCollectionsDetailsUsingDates($tblName,$fromDate,$toDate){
        $this->db->select('billpayments.*,bills.id as bid,bills.date as bdate,bills.billNo,bills.compName,bills.retailerName as rtname,bills.salesman,bills.retailerCode as rtCode,bills.routeName,bills.salesman,bills.isDeliverySlipBill');
        $this->db->join('billpayments','allocations.id=billpayments.allocationId');
        $this->db->join('bills','billpayments.billId=bills.id');
        $this->db->where('DATE(allocations.date) >=', $fromDate);
        $this->db->where('DATE(allocations.date) <=', $toDate);
        $this->db->where('billpayments.paidAmount !=',0);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    public function getProductName($tblName,$name){
        $this->db->where('name',$name);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    public function getProductHistory($tblName,$fromDate,$toDate){
        $this->db->select('deliveryslip_pending_for_billing.*,employee.name as ename,bills.billNo as b_billNo,bills.retailerName as b_retailerName,bills.retailerCode as b_retailerCode');
        // $this->db->join('retailer','retailer.id=deliveryslip_pending_for_billing.retailerId','left outer');
        $this->db->join('employee','employee.id=deliveryslip_pending_for_billing.createdBy','left outer');
        $this->db->join('bills','bills.id=deliveryslip_pending_for_billing.billingId','left outer');
        $this->db->where('DATE(deliveryslip_pending_for_billing.createdAt) >=', $fromDate);
        $this->db->where('DATE(deliveryslip_pending_for_billing.createdAt) <=', $toDate);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    public function getProductHistoryWithName($tblName,$date,$name){
        $this->db->select('deliveryslip_pending_for_billing.*,employee.name as ename,bills.billNo as b_billNo,bills.retailerName as b_retailerName,bills.retailerCode as b_retailerCode');
        // $this->db->join('retailer','retailer.id=deliveryslip_pending_for_billing.retailerId','left outer');
        $this->db->join('employee','employee.id=deliveryslip_pending_for_billing.createdBy','left outer');
        $this->db->join('bills','bills.id=deliveryslip_pending_for_billing.billingId','left outer');
        $this->db->where('DATE(deliveryslip_pending_for_billing.createdAt)', $date);
        $this->db->where('deliveryslip_pending_for_billing.productName',$name);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }


    public function getAllocationwiseDetailsUsingDates($tblName,$fromDate,$toDate){
        $this->db->select('allocations.*');
        $this->db->where('DATE(allocations.date) >=', $fromDate);
        $this->db->where('DATE(allocations.date) <=', $toDate);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    /////

    //for allocation collection
    public function loadBillPayments($tblName, $allocationId,$type) {
        $this->db->select('paidAmount');
        $this->db->where('allocationId',$allocationId);
        $this->db->where('paymentMode',$type);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();   
    }

    //for allocation collection
    public function loadBillSRPayments($tblName,$allocationId) {
        $this->db->select('billsdetails.*,allocation_sr_details.quantity as receivedQuantity');
        $this->db->join('billsdetails','billsdetails.id=allocation_sr_details.billItemId');
        $this->db->where('allocation_sr_details.allocationId',$allocationId);
         $query = $this->db->get($tblName);
         $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }
            
    
    //for Billwise Collection report
    public function getBillwiseCollectionsDetailsUsingDates($tblName,$fromDate,$toDate){
        $this->db->select('billpayments.*,bills.id as bid,bills.date as bdate,bills.billNo,bills.compName,bills.retailerName as rtname,bills.salesman,bills.retailerCode as rtCode,bills.routeName,bills.salesman,bills.isDeliverySlipBill,employee.name as empName,employee.designation as empDes,employee.code as empCode,allocations.allocationCode as alCode');
        $this->db->join('billpayments','billpayments.billId=bills.id');
        $this->db->join('allocations','billpayments.allocationId=allocations.id','left outer');
        $this->db->join('employee','billpayments.empId=employee.id');
        $this->db->where('DATE(bills.date) >=', $fromDate);
        $this->db->where('DATE(bills.date) <=', $toDate);
        $this->db->where('billpayments.paidAmount !=',0);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    //for Billwise Collection report using company
    public function getBillwiseCollectionsDetailsUsingCompany($tblName,$fromDate,$toDate,$compName){
        $this->db->select('billpayments.*,bills.id as bid,bills.date as bdate,bills.billNo,bills.compName,bills.retailerName as rtname,bills.salesman,bills.retailerCode as rtCode,bills.routeName,bills.salesman,bills.isDeliverySlipBill,employee.name as empName,employee.designation as empDes,employee.code as empCode,allocations.allocationCode as alCode');
        $this->db->join('billpayments','billpayments.billId=bills.id');
        $this->db->join('allocations','billpayments.allocationId=allocations.id','left outer');
        $this->db->join('employee','billpayments.empId=employee.id');
        $this->db->where('DATE(bills.date) >=', $fromDate);
        $this->db->where('DATE(bills.date) <=', $toDate);
        $this->db->where('billpayments.paidAmount !=',0);
        $this->db->where('bills.compName',$compName);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }
    /////
    
    //for Billwise Retailer Collection report
    public function getBillwiseRetailerCollectionsDetailsUsingDates($tblName,$fromDate,$toDate){
        $this->db->where('DATE(bills.date) >=', $fromDate);
        $this->db->where('DATE(bills.date) <=', $toDate);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    //for Billwise Retailer Collection report using company
    public function getBillwiseRetailerCollectionsDetailsUsingCompany($tblName,$fromDate,$toDate,$compName){
        $this->db->where('DATE(bills.date) >=', $fromDate);
        $this->db->where('DATE(bills.date) <=', $toDate);
        $this->db->where('bills.compName',$compName);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }
    /////

    public function getCollectionsDetailsUsingDates($tblName,$fromDate,$toDate){
        $this->db->select('billpayments.*,bills.id as bid,bills.date as bdate,bills.billNo,bills.compName,bills.retailerName as rtname,bills.salesman,bills.retailerCode as rtCode,bills.routeName,bills.salesman,bills.isDeliverySlipBill');
        $this->db->join('billpayments','billpayments.billId=bills.id');
        $this->db->where('DATE(billpayments.date) >=', $fromDate);
        $this->db->where('DATE(billpayments.date) <=', $toDate);
        $this->db->where('billpayments.paidAmount >',0);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    public function getCollectionsDetailsUsingDatesWithCompany($tblName,$fromDate,$toDate,$compName){
        $this->db->select('billpayments.*,bills.id as bid,bills.date as bdate,bills.billNo,bills.compName,bills.retailerName as rtname,bills.salesman,bills.retailerCode as rtCode,bills.routeName,bills.salesman,bills.isDeliverySlipBill');
        $this->db->join('billpayments','billpayments.billId=bills.id');
        $this->db->where('DATE(billpayments.date) >=', $fromDate);
        $this->db->where('DATE(billpayments.date) <=', $toDate);
        $this->db->where('billpayments.paidAmount >',0);
        $this->db->where('bills.compName',$compName);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    public function getSalesReportForDeliveryslip($tblName,$retailer,$salesman,$fromDate,$toDate){
        $this->db->select('bills.date as billDate,bills.billNo as billNo, bills.compName as billCompany,bills.salesman as billSalesman,bills.salesmanCode as billSalesmanCode,bills.routeName as billRouteName,bills.routeCode as billRouteCode,bills.retailerName as billRetailerName,bills.retailerCode as billRetailerCode,billsdetails.productCode as billProductCode, billsdetails.productName as billProductName, billsdetails.mrp as mrp,billsdetails.sellingRate as sellingRate,billsdetails.qty as qty,billsdetails.netAmount as netAmount,billsdetails.gkReturnQty as gkReturnQty,billsdetails.fsReturnAmt as fsReturnAmt');
        $this->db->join('billsdetails','billsdetails.billId=bills.id','left outer');
        $this->db->where('DATE(bills.date) >=',$fromDate);
        $this->db->where('DATE(bills.date) <=',$toDate);
        $this->db->where('bills.isDeliverySlipBill',1);
        $this->db->where('bills.retailerName',$retailer);
        $this->db->where('bills.salesman',$salesman);
        $query=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getSalesReportForDeliveryslipWithoutSalesman($tblName,$fromDate,$toDate){
        $this->db->select('bills.date as billDate,bills.billNo as billNo, bills.compName as billCompany,bills.salesman as billSalesman,bills.salesmanCode as billSalesmanCode,bills.routeName as billRouteName,bills.routeCode as billRouteCode,bills.retailerName as billRetailerName,bills.retailerCode as billRetailerCode,billsdetails.productCode as billProductCode, billsdetails.productName as billProductName, billsdetails.mrp as mrp,billsdetails.sellingRate as sellingRate,billsdetails.qty as qty,billsdetails.netAmount as netAmount,billsdetails.gkReturnQty as gkReturnQty,billsdetails.fsReturnAmt as fsReturnAmt');
        $this->db->join('billsdetails','billsdetails.billId=bills.id','left outer');
        $this->db->where('DATE(bills.date) >=',$fromDate);
        $this->db->where('DATE(bills.date) <=',$toDate);
        $this->db->where('bills.isDeliverySlipBill',1);
        $query=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }
	 
	 public function frequentsrRetailers($tableName)
    {
       // $this->db->select('DISTINCT(allocation_sr_details.billId) as biId, bills.id as bid, bills.retailerName');
        $this->db->select('DISTINCT(allocation_sr_details.billId) as biId, bills.*');
        $this->db->join('bills','bills.id=allocation_sr_details.billId','left outer');
        $this->db->order_by('bills.id','desc');
        $this->db->limit(200);
        $query=$this->db->get($tableName);
        //$aa = $this->db->last_query(); print_r($aa); exit();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function frequentsrRetailerswithDate($tableName,$fromDate,$toDate)
            {
            //$first_date = date('Y-m-d', strtotime('first day of last month'));
            //$last_date = date('Y-m-d', strtotime('last day of last month'));
            //$first_date = date('Y-m-d',strtotime("-60 days"));
    
           $this->db->select('DISTINCT(allocation_sr_details.billId) as biId, bills.*');
            $this->db->join('bills','bills.id=allocation_sr_details.billId','left outer');
            $this->db->where('bills.date BETWEEN "'.$fromDate.'" AND "'.$toDate.'" ');
            $this->db->order_by('bills.id','desc');
            $query=$this->db->get($tableName);
            //$aa = $this->db->last_query(); print_r($aa); exit();
            $this->db->close();
            $this->db->initialize();
            return $query->result_array();
            }


    public function frequentsrSalesman($tableName)
    {   $this->db->distinct('bills.salesman');
        $this->db->select('allocation_sr_details.billId as biId, bills.id as bid, bills.salesman');
        $this->db->join('bills','bills.id=allocation_sr_details.billId','left outer');
        $this->db->group_by('bills.salesman');
        $query=$this->db->get($tableName);
     
       // $aa = $this->db->last_query(); print_r($aa); exit();
        $this->db->close();  
        $this->db->initialize();
        return $query->result_array();
    }
	
	 public function multiplevisitorRetailer()
    {
        $this->db->select('DISTINCT(billpayments.billId) as biId, bills.id as bid, bills.retailerName as rname');
        $this->db->join('bills','bills.id=billpayments.billId','left outer');
        $query=$this->db->get('billpayments');
       // $aa = $this->db->last_query(); print_r($aa); exit(); 
       // $this->db->close(); 
       // $this->db->initialize(); 
        return $query->result_array(); 
		
		/*$this->db->select('DISTINCT(billpayments.billId) as biId, bills.id as bid, bills.retailerName as rname');
        $this->db->join('billpayments','billpayments.billId=bills.id', 'left outer');
		//$this->db->join('bills','bills.id=billpayments.billId','left outer');
        $query = $this->db->get('bills');
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();*/
    }
	
	//Overdue Bill Fetch Record
    public function fetchBillRecord($tblName,$fromDate,$toDate)
            {
            //$first_date = date('Y-m-d', strtotime('first day of last month'));
            //$last_date = date('Y-m-d', strtotime('last day of last month'));
            $first_date = date('Y-m-d',strtotime("-60 days"));
            $last_date   = date('Y-m-d',strtotime("now"));
            $this->db->select('*');
            $this->db->from($tblName);
            $this->db->where('date BETWEEN "'.$first_date.'" AND "'.$last_date.'" ');
			$this->db->where('pendingAmt !=',0); 
            $query = $this->db->get();
            $this->db->close();
            $this->db->initialize();
            return $query->result_array();
            }
	
	//Retailer Account Statement Report
	public function getRetailerDetailsUsingDates($tableName,$rname,$fromDate,$toDate){
        $this->db->distinct();
        $this->db->select('bills.*');
        $this->db->from($tableName);
        $this->db->where('retailerName', $rname);
        $this->db->where('DATE(bills.date) >=', $fromDate);
        $this->db->where('DATE(bills.date) <=', $toDate);
        $this->db->order_by('id','desc');
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function loadBillsDetails($tblName, $id) {
        $this->db->where('billId', $id);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();   
    }
}
?>