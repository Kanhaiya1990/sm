<?php
class CashAndChequeModel extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

     public function getAllocations($tableName)
    {
        $this->db->where('isAllocationComplete !=','1');
        $this->db->order_by('id','desc');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }
    
    public function getLinkedSalesman($tableName,$name,$code)
    {
        $this->db->where('salesmanName',$name);
        $this->db->where('salesmanCode',$code);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getDetails($tableName)
    {
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getTransactions()
    {
        $sqlQuery="SELECT count(billPaymentId) as chequeCount,sum(chequeAmount) as totalChequeSum,transactionId,filePath,createdDate,company,chequeTillDate FROM `cheque_deposit_slips` group by transactionId order by createdDate desc";
        $data=$this->db->query($sqlQuery);
        
        return $data->result_array();
    }

   

     public function findBank($tableName,$name)
    {
        $this->db->where('name',$name);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

     public function getCheckRegister($tableName)
    {
        $this->db->where('billId !=',0);
        $this->db->where('chequeStatus !=','');
        $this->db->where('isLostStatus',"2");
        $this->db->where('paymentMode !=','NEFT');
        $this->db->order_by('DATE(chequeReceivedDate)','desc');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getCheckRegisterGroupBy($tableName)
    {
        $this->db->distinct();
        $this->db->select('billpayments.*,sum(paidAmount) as sumAmount');
        $this->db->where('billId !=',0);
        $this->db->where('paymentMode','Cheque');
        $this->db->where('isLostStatus',"2");
        $this->db->group_start();
        $this->db->where('tempStatus',"0");
        $this->db->or_where('tempStatus',"1");
        $this->db->group_end();
        $this->db->group_start();
        $this->db->where('chequeStatus !=','Bounced');
        $this->db->or_where('chequeStatus !=','Bounced&Returned');
        $this->db->group_end();
        $this->db->order_by('id','desc');
        $this->db->group_by('chequeDate,bounceChequeCount,tempStatus,billNo,chequeNo,chequeBank');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    // public function getChequeTransactions($tableName,$id)
    // {
    //     $this->db->distinct();
    //     $this->db->select('billpayments.*,sum(paidAmount) as sumAmount');
    //     $this->db->join('billpayments','billpayments.id=cheque_deposit_slips.billPaymentId');
    //     $this->db->where('cheque_deposit_slips.transactionId',$id);
    //     $this->db->order_by('id','desc');
    //     $this->db->group_by('chequeDate,bounceChequeCount,tempStatus,billNo,chequeNo,chequeBank');
    //     $query=$this->db->get($tableName);
    //     return $query->result_array();
    // }

    public function getChequeTransactions($id){
        $sqlQuery="SELECT billpayments.* FROM cheque_deposit_slips join billpayments on billpayments.id=cheque_deposit_slips.billPaymentId where transactionId='$id'";
        $data=$this->db->query($sqlQuery);
        
        // print_r($data->result_array());
        return $data->result_array();
    }

     public function getBouncedCheckRegisterGroupBy($tableName)
    {
        $this->db->distinct();
        $this->db->select('billpayments.*,sum(billpayments.paidAmount) as sumAmount');
        // $this->db->where('billId !=',0);
        $this->db->where('paymentMode','Cheque');
        $this->db->where('isLostStatus',"2");
        $this->db->where('tempStatus',"2");
        // $this->db->group_start();
        // $this->db->where('chequeStatus','Bounced');
        // $this->db->or_where('chequeStatus','Bounced&Returned');
        // $this->db->group_end();
        $this->db->order_by('id','desc');
        $this->db->group_by('chequeDate,bounceChequeCount,tempStatus,billNo,chequeNo,chequeBank');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

     public function getCheckRegisterAdHoc($tableName)
    {
        $this->db->where('billId',0);
        $this->db->where('chequeStatus !=','');
        $this->db->where('paymentMode','Cheque');
        $this->db->where('isOfficeCheque','1');
        $this->db->order_by('DATE(chequeReceivedDate)','desc');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

     public function getCheckRegisterAdHocGroupBy($tableName)
    {
        $this->db->distinct();
        $this->db->select('billpayments.*,sum(paidAmount) as sumAmount');
        $this->db->where('billId',0);
        $this->db->where('chequeStatus !=','');
        $this->db->where('paymentMode','Cheque');
        $this->db->where('isOfficeCheque','1');
        $this->db->order_by('DATE(chequeReceivedDate)','desc');
        $this->db->group_by('chequeDate,bounceChequeCount,chequeNo,chequeBank');

        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getChequeData($tableName,$chequeNo,$chequeDate,$chequeBank)
    {
        // $this->db->where('chequeStatus','NEFT');
        $this->db->where('tempStatus','0');
        $this->db->where('chequeNo',$chequeNo);
        $this->db->where('DATE(chequeDate)',$chequeDate);
        $this->db->where('chequeBank',$chequeBank);
        $this->db->where('tallyStatus','');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getBouncedChequeData($tableName,$chequeNo,$chequeDate,$chequeBank)
    {
        $this->db->distinct();
        $this->db->select('billpayments.*');
        $this->db->where('chequeNo',$chequeNo);
        $this->db->where('DATE(chequeDate)',$chequeDate);
        $this->db->where('chequeBank',$chequeBank);
        $this->db->where('paymentMode','Cheque');
        $this->db->where('tempStatus','2');
        $this->db->group_start();
        $this->db->where('chequeStatus','Bounced');
        $this->db->or_where('chequeStatus','Bounced&Returned');
        $this->db->group_end();
        
        $this->db->order_by('id','desc');
        $this->db->group_by('chequeDate,bounceChequeCount,billNo,chequeNo,chequeBank');

        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

     public function getNeftRegister($tableName)
    {
        // $this->db->where('chequeStatus','NEFT');
        $this->db->where('paymentMode','NEFT');
        $this->db->where('neftNo !=','');
        $this->db->where('isLostStatus','2');
        $this->db->where('paidAmount >',0);
        $this->db->order_by('chequeReceivedDate','desc');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

     public function getNeftRegisterGroupBy($tableName)
    {
        $this->db->distinct();
        $this->db->select('billpayments.*,sum(paidAmount) as sumAmount');
        // $this->db->where('chequeStatus','NEFT');
        $this->db->where('paymentMode','NEFT');
        $this->db->where('neftNo !=','');
        $this->db->where('isLostStatus','2');
        $this->db->where('paidAmount >',0);
        $this->db->order_by('chequeReceivedDate','desc');
        $this->db->group_by('billpayments.neftNo,billpayments.neftDate');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getBillPaymentRecord($tableName,$billId,$allocationId,$mode)
    {
        $this->db->where('billId',$billId);
        $this->db->where('allocationId',$allocationId);
        $this->db->where('paymentMode',$mode);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

     public function currentCheques($tableName){
        $query="SELECT bills.*,billpayments.id as bpayId,billpayments.allocationId as bpayAllocationId,billpayments.paidAmount as paidAmt,billpayments.paymentMode as payMode,billpayments.date as bdate from bills join billpayments on bills.id=billpayments.billId where billpayments.paidAmount >0 and billpayments.chequeStatus='' and billpayments.chequeNo='' and billpayments.neftNo='' and billpayments.isLostStatus=2 and (billpayments.paymentMode = 'Cheque' or billpayments.paymentMode='NEFT')";
        $qry=$this->db->query($query);
        
        return $qry->result_array();
    }
   //  public function currentCheques($tableName){
   //      $this->db->select('bills.*,billpayments.id as bpayId,billpayments.allocationId as bpayAllocationId,billpayments.paidAmount as paidAmt,billpayments.paymentMode as payMode,billpayments.date as bdate');
   //      $this->db->join('billpayments','bills.id=billpayments.billId','left outer');
   //      $this->db->from($tableName);
   //      $this->db->where('billpayments.chequeStatus',"");
        
   //      $this->db->where('billpayments.chequeNo',"");
   //      $this->db->where('billpayments.neftNo',"");
   //        $this->db->where('billpayments.isLostStatus',"2");
   //      $this->db->like('billpayments.paymentMode','Cheque');
   //      $this->db->or_like('billpayments.paymentMode','NEFT');
        
   //      $query=$this->db->get();
   //      return $query->result_array();
   // }

    public function getClosedAllocations($tableName)
    {
        $this->db->where('isAllocationComplete =','1');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function checkHistory($tblName,$billId,$allocationId){
        $this->db->where('billId', $billId);
        $this->db->where('allocationId', $allocationId);
        $query = $this->db->get($tblName);
        
        return $query->result_array();  
    }

    public function getEmployeeNamesByID($id){
        $this->db->select('name'); 
        $this->db->from('employee');
        $this->db->where('id',$id);   
        return $this->db->get()->row()->name;
    }

    public function getRouteName($code){
        $this->db->select('name'); 
        $this->db->from('route');
        $this->db->where('code',$code);   
        return $this->db->get()->result_array();
    }
    
    public function getdata($tableName)
    {
        $this->db->order_by('id','desc');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }
    
    public function getEmail($tableName){
        $this->db->select("email");
        $this->db->where('email !=','');
        $resultset=$this->db->get($tableName); 
        
        return $resultset->result_array();
    }


    public function getdataByDates($tableName,$from,$to,$type)
    {
        if($type=='cheque'){
            $this->db->where("DATE(chequeDate) BETWEEN '". $from. "' AND '".$to."'");
            $this->db->where('chequeStatus !=','');
            $this->db->where('paymentMode !=','NEFT');
            $this->db->where('isLostStatus',"2");
            $this->db->order_by('DATE(chequeReceivedDate)','desc');
            $query=$this->db->get($tableName);
            
            return $query->result_array();
        }else if($type=='receive'){
            $this->db->where("DATE(chequeReceivedDate) BETWEEN '". $from. "' AND '".$to."'");
            $this->db->where('chequeStatus !=','');
            $this->db->where('paymentMode !=','NEFT');
            $this->db->where('isLostStatus',"2");
             $this->db->order_by('DATE(chequeReceivedDate)','desc');
            $query=$this->db->get($tableName);
            
            return $query->result_array();
        }
        
    }

    public function getdataByDatesGroupBy($tableName,$from,$to,$type)
    {
        if($type=='cheque'){
            $this->db->distinct();
            $this->db->select('billpayments.*,sum(paidAmount) as sumAmount');
            $this->db->where("DATE(chequeDate) BETWEEN '". $from. "' AND '".$to."'");
            $this->db->where('paymentMode','Cheque');
            $this->db->where('isLostStatus',"2");
            $this->db->group_start();
            $this->db->where('tempStatus',"0");
            $this->db->or_where('tempStatus',"1");
            $this->db->group_end();
            $this->db->order_by('id','desc');
            $this->db->group_by('chequeBank,bounceChequeCount,chequeDate,billNo,chequeNo');
            $query=$this->db->get($tableName);
            
            return $query->result_array();
        }else if($type=='receive'){
            $this->db->distinct();
            $this->db->select('billpayments.*,sum(paidAmount) as sumAmount');
            $this->db->where("DATE(chequeReceivedDate) BETWEEN '". $from. "' AND '".$to."'");
            $this->db->group_start();
            $this->db->where('chequeStatus !=','Bounced');
            $this->db->or_where('chequeStatus !=','Bounced&Returned');
            $this->db->group_end();
            $this->db->where('paymentMode','Cheque');
            $this->db->where('isLostStatus',"2");
            // $this->db->where('tempStatus',"0");
             $this->db->order_by('id','desc');
            $this->db->group_by('chequeBank,bounceChequeCount,chequeDate,billNo,chequeNo');

            $query=$this->db->get($tableName);
            
            return $query->result_array();
        }
        
    }

    public function getBounceddataByDatesGroupBy($tableName,$from,$to,$type)
    {
        if($type=='cheque'){
            $this->db->distinct();
            $this->db->select('billpayments.*,sum(paidAmount) as sumAmount');
            $this->db->where("DATE(chequeDate) BETWEEN '". $from. "' AND '".$to."'");
            $this->db->group_start();
            $this->db->where('chequeStatus','Bounced');
            $this->db->or_where('chequeStatus','Bounced&Returned');
            $this->db->group_end();
            $this->db->where('paymentMode','Cheque');
            $this->db->where('isLostStatus',"2");
            $this->db->where('tempStatus',"2");
            $this->db->order_by('id','desc');
            $this->db->group_by('chequeBank,bounceChequeCount,chequeDate,billNo,chequeNo');
            $query=$this->db->get($tableName);
            
            return $query->result_array();
        }else if($type=='receive'){
            $this->db->distinct();
            $this->db->select('billpayments.*,sum(paidAmount) as sumAmount');
            $this->db->where("DATE(chequeReceivedDate) BETWEEN '". $from. "' AND '".$to."'");
            $this->db->group_start();
            $this->db->where('chequeStatus','Bounced');
            $this->db->or_where('chequeStatus','Bounced&Returned');
            $this->db->group_end();
            $this->db->where('paymentMode','Cheque');
            $this->db->where('isLostStatus',"2");
            $this->db->where('tempStatus',"2");
             $this->db->order_by('id','desc');
            $this->db->group_by('chequeBank,bounceChequeCount,chequeDate,chequeNo');

            $query=$this->db->get($tableName);
            
            return $query->result_array();
        }
        
    }

     public function getdataByDatesAdHocGroupBy($tableName,$from,$to,$type)
    {
        if($type=='cheque'){
            $this->db->where("DATE(chequeDate) BETWEEN '". $from. "' AND '".$to."'");
            $this->db->where('chequeStatus !=','');
            $this->db->where('isOfficeCheque','1');
            $this->db->where('billId','0');
            $this->db->where('paymentMode','Cheque');
             $this->db->order_by('DATE(chequeReceivedDate)','desc');
            $this->db->group_by('chequeBank,bounceChequeCount,chequeDate,chequeNo');

            $query=$this->db->get($tableName);
            
            return $query->result_array();
        }else if($type=='receive'){
            $this->db->where("DATE(chequeReceivedDate) BETWEEN '". $from. "' AND '".$to."'");
            $this->db->where('chequeStatus !=','');
            $this->db->where('isOfficeCheque','1');
            $this->db->where('billId','0');
            $this->db->where('paymentMode','Cheque');
             $this->db->order_by('DATE(chequeReceivedDate)','desc');
            $this->db->group_by('chequeBank,bounceChequeCount,chequeDate,chequeNo');

            $query=$this->db->get($tableName);
            
            return $query->result_array();
        }
        
    }


    public function getdataByDatesAdHoc($tableName,$from,$to,$type)
    {
        if($type=='cheque'){
            $this->db->where("DATE(chequeDate) BETWEEN '". $from. "' AND '".$to."'");
            $this->db->where('chequeStatus !=','');
            $this->db->where('isOfficeCheque','1');
            $this->db->where('billId','0');
            $this->db->where('paymentMode','Cheque');
             $this->db->order_by('DATE(chequeReceivedDate)','desc');
            $query=$this->db->get($tableName);
            
            return $query->result_array();
        }else if($type=='receive'){
            $this->db->where("DATE(chequeReceivedDate) BETWEEN '". $from. "' AND '".$to."'");
            $this->db->where('chequeStatus !=','');
            $this->db->where('isOfficeCheque','1');
            $this->db->where('billId','0');
            $this->db->where('paymentMode','Cheque');
             $this->db->order_by('DATE(chequeReceivedDate)','desc');
            $query=$this->db->get($tableName);
            
            return $query->result_array();
        }
        
    }

    public function getNeftdataByDates($tableName,$from,$to)
    {
        $this->db->where("DATE(neftDate) BETWEEN '". $from. "' AND '".$to."'");
        // $this->db->where('chequeStatus !=','');
        $this->db->where('paymentMode','NEFT');
         $this->db->where('neftNo !=','');
          $this->db->where('isLostStatus','2');
          $this->db->where('paidAmount >',0);
          $this->db->order_by('chequeReceivedDate','desc');

        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

     public function getNeftdataByDatesGroupBy($tableName,$from,$to)
    {
        $this->db->distinct();
        $this->db->select('billpayments.*,sum(paidAmount) as sumAmount');
        $this->db->where("DATE(neftDate) BETWEEN '". $from. "' AND '".$to."'");
        // $this->db->where('chequeStatus !=','');
        $this->db->where('paymentMode','NEFT');
         $this->db->where('neftNo !=','');
          $this->db->where('isLostStatus','2');
          $this->db->where('paidAmount >',0);
          $this->db->order_by('chequeReceivedDate','desc');
          $this->db->group_by('billpayments.neftNo,billpayments.neftDate');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getNames($tableName)
    {
        $this->db->select('name');
        $this->db->distinct();
        $this->db->order_by('id','desc');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getChequeDetail($tableName,$chkNo,$chkDate,$bank){
        $this->db->where('chequeNo',$chkNo);
        $this->db->where('chequeDate',$chkDate);
        $this->db->where('chequeBank',$bank);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getdataBanked($tableName)
    {
        $this->db->select("billpayments.*,bills.retailerName as Name,bills.billNo as Bno");
        $this->db->join("bills","bills.id = billpayments.billId");      
        $this->db->where("billpayments.chequeStatus", "Banked");
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

    public function getdataBankedGroupBy($tableName)
    {
        // $this->db->distinct();
        $this->db->select("billpayments.*,sum(paidAmount) as sumAmount,bills.retailerName as Name,bills.billNo as Bno");
        $this->db->join("bills","bills.id = billpayments.billId");   
        // $this->db->where("billpayments.paidAmount >", 0);   
        $this->db->where("billpayments.chequeStatus", "Banked");
        // $this->db->where("billpayments.tallyStatus", "");
        $this->db->order_by('chequeDate','desc');
        $this->db->group_by('chequeNo,chequeDate,chequeBank');
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

     public function paginationChequereconciatioBills($tableName,$limit, $start, $st = "", $orderField, $orderDirection){
        $this->db->distinct();
        $this->db->select("billpayments.*,sum(paidAmount) as sumAmount,bills.retailerName as Name,bills.billNo as Bno");
        $this->db->join("bills","bills.id = billpayments.billId");      
        $this->db->from($tableName);
        $this->db->group_start();
        $this->db->like('billpayments.billNo', $st);
        $this->db->or_like('billpayments.date', $st);
        $this->db->or_like('billpayments.retailerName', $st);
        $this->db->or_like('bills.netAmount', $st);
        $this->db->or_like('billpayments.compName', $st);
         $this->db->or_like('billpayments.chequeNo', $st);
        $this->db->or_like('billpayments.chequeDate', $st);
        $this->db->or_like('bills.pendingAmt', $st);
        $this->db->group_end();
        $this->db->where("billpayments.paidAmount >",0);
        $this->db->where("billpayments.chequeStatus", "Banked");
        $this->db->group_by('chequeNo,chequeDate,chequeBank');
        $this->db->order_by('chequeStatusDate,paidAmount','asc');
        $this->db->limit($limit, $start);
        $query=$this->db->get();
        
        return $query->result_array();
    }

    public function countPaginationChequereconciatioBills($tableName,$limit, $start, $st = "", $orderField, $orderDirection){
        $this->db->distinct();
        $this->db->select("billpayments.*,sum(paidAmount) as sumAmount,bills.retailerName as Name,bills.billNo as Bno");
        $this->db->join("bills","bills.id = billpayments.billId"); 
        $this->db->from($tableName);
        $this->db->group_start();
        $this->db->like('billpayments.billNo', $st);
        $this->db->or_like('billpayments.date', $st);
        $this->db->or_like('billpayments.retailerName', $st);
        $this->db->or_like('bills.netAmount', $st);
        $this->db->or_like('billpayments.compName', $st);
         $this->db->or_like('billpayments.chequeNo', $st);
        $this->db->or_like('billpayments.chequeDate', $st);
        $this->db->or_like('bills.pendingAmt', $st);
        $this->db->group_end();
        $this->db->where("billpayments.chequeStatus", "Banked");
        $this->db->group_by('chequeNo,chequeDate,chequeBank');
       $this->db->order_by('chequeStatusDate,paidAmount','asc');
        // $this->db->order_by($orderField, $orderDirection);
        $query=$this->db->get();
        
        return $query->num_rows();
    }

    public function paginationAdhocChequereconciatioBills($tableName,$limit, $start, $st = "", $orderField, $orderDirection){
        $this->db->distinct();
        $this->db->select("billpayments.*,sum(paidAmount) as sumAmount");
        $this->db->from($tableName);
        $this->db->group_start();
        $this->db->like('billpayments.billNo', $st);
        $this->db->or_like('billpayments.date', $st);
        $this->db->or_like('billpayments.retailerName', $st);
        $this->db->or_like('billpayments.chequeNo', $st);
        $this->db->or_like('billpayments.chequeDate', $st);
        // $this->db->or_like('bills.netAmount', $st);
        $this->db->or_like('billpayments.compName', $st);
        // $this->db->or_like('bills.pendingAmt', $st);
        $this->db->group_end();
        $this->db->where("billpayments.chequeStatus", "Banked");
        $this->db->where("billpayments.billId", "0");
        $this->db->group_by('chequeNo,chequeDate,chequeBank');
        $this->db->order_by('chequeStatusDate,paidAmount','asc');
        // $this->db->order_by($orderField, $orderDirection);
        $this->db->limit($limit, $start);
        $query=$this->db->get();
        
        return $query->result_array();
    }

    public function countAdhocPaginationChequereconciatioBills($tableName,$limit, $start, $st = "", $orderField, $orderDirection){
        $this->db->distinct();
        $this->db->select("billpayments.*,sum(paidAmount) as sumAmount");
        $this->db->from($tableName);
        $this->db->group_start();
        $this->db->like('billpayments.billNo', $st);
        $this->db->or_like('billpayments.date', $st);
        $this->db->or_like('billpayments.retailerName', $st);
         $this->db->or_like('billpayments.chequeNo', $st);
        $this->db->or_like('billpayments.chequeDate', $st);
        // $this->db->or_like('bills.netAmount', $st);
        $this->db->or_like('billpayments.compName', $st);
        // $this->db->or_like('bills.pendingAmt', $st);
        $this->db->group_end();
        $this->db->where("billpayments.chequeStatus", "Banked");
        $this->db->where("billpayments.billId", "0");
        $this->db->group_by('chequeNo,chequeDate,chequeBank');
        $this->db->order_by('chequeStatusDate,paidAmount','asc');
        // $this->db->order_by($orderField, $orderDirection);
        $query=$this->db->get();
        
        return $query->num_rows();
    }

    // public function getdataBankedGroupBy($tableName)
    // {
    //     // $this->db->distinct();
    //     $this->db->select("billpayments.*,sum(paidAmount) as sumAmount,bills.retailerName as Name,bills.billNo as Bno");
    //     $this->db->join("bills","bills.id = billpayments.billId");      
    //     $this->db->where("billpayments.chequeStatus", "Banked");
    //     $this->db->order_by('chequeDate','desc');
    //     $this->db->group_by('chequeNo,chequeDate,chequeBank');
    //     $resultset=$this->db->get($tableName);
    //     return $resultset->result_array();
    // }

    public function getdataAdHocBanked($tableName)
    {
        $this->db->select("billpayments.*");
        $this->db->where("billpayments.chequeStatus", "Banked");
        $this->db->where("billpayments.billId", "0");
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

    public function getdataAdHocBankedGroupBy($tableName)
    {
        $this->db->select("billpayments.*,sum(paidAmount) as sumAmount");
        $this->db->where("billpayments.chequeStatus", "Banked");
        $this->db->where("billpayments.billId", "0");
        $this->db->group_by('chequeNo,chequeDate,chequeBank');
        
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

    public function getNeftBanked($tableName)
    {
        $this->db->select("billpayments.*,bills.retailerName as Name,bills.billNo as Bno");
        $this->db->join("bills","bills.id = billpayments.billId");        
        $this->db->where("billpayments.paymentMode", "NEFT");
        $this->db->where("billpayments.chequeStatus", "New");
         $this->db->where("billpayments.paidAmount >", 0);
        $this->db->where("billpayments.isLostStatus", "2");
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

     public function getNeftBankedGroupBy($tableName)
    {
        $this->db->select("billpayments.*,sum(paidAmount) as sumAmount,bills.retailerName as Name,bills.billNo as Bno");
        $this->db->join("bills","bills.id = billpayments.billId");        
        $this->db->where("billpayments.paymentMode", "NEFT");
        $this->db->where("billpayments.chequeStatus", "New");
         $this->db->where("billpayments.paidAmount >", 0);
        $this->db->where("billpayments.isLostStatus", "2");
        $this->db->group_by('neftNo,neftDate');
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

     public function getNeftAdHocBanked($tableName)
    {
        $this->db->select("billpayments.*,bills.retailerName as Name,bills.billNo as Bno");
        $this->db->join("bills","bills.id = billpayments.billId");  
         $this->db->where("billpayments.paymentMode", "NEFT");
         $this->db->where("billpayments.paidAmount >", 0);
        $this->db->where("billpayments.isLostStatus", "2");
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

   

    public function getdataAdHocReconciliationData($tableName)
    {
        $this->db->select("billpayments.*");
        $this->db->where("billpayments.billId", "0");
         $this->db->where("billpayments.chequeStatus", "Banked");
        // $this->db->or_where("billpayments.chequeStatus", "Cleared");
        // $this->db->or_where("billpayments.chequeStatus", "Bounced");
        // $this->db->or_where("billpayments.tempStatus", "1");
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

    public function getCurrentReconciliationData($tableName)
    {
       
        $this->db->select("billpayments.*,bills.retailerName as Name,bills.billNo as Bno");
        $this->db->join("bills","bills.id = billpayments.billId");
        // $this->db->join("retailer","bills.retailerCode = retailer.code"); 

        $this->db->where("billpayments.chequeStatus", "Banked");
        // $this->db->or_where("billpayments.chequeStatus", "Cleared");
        // $this->db->or_where("billpayments.chequeStatus", "Bounced");
        // $this->db->or_where("billpayments.tempStatus", "1");  
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

    public function getdataBounceNew($tableName)
    {
        $this->db->distinct('billpayments.billId');
        $this->db->select("billpayments.billId");
        $this->db->join("bills","bills.id = billpayments.billId",'left outer');
        $this->db->where('billpayments.paidAmount >',0);
        $this->db->where('bills.pendingAmt >',0);
        $this->db->group_start();
        $this->db->where('chequeStatus',"Bounced");
        $this->db->or_where('chequeStatus',"Bounced&Returned");
        $this->db->group_end();
        $this->db->group_by('billpayments.billId,chequeNo,chequeDate,chequeBank');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getdataBounceForUniqueBounceCheque($tableName,$billId)
    {
        $this->db->select("billpayments.*,sum(paidAmount) as sumAmount,sum(penalty) as penaltyAmount,bills.retailerName as Name,bills.billNo as Bno,bills.pendingAmt as latestPendingAmt");
        $this->db->join("bills","bills.id = billpayments.billId",'left outer');
        $this->db->where('billpayments.billId',$billId);
        $this->db->where('billpayments.paidAmount >',0);
        $this->db->where('bills.pendingAmt >',0);
        $this->db->group_start();
        $this->db->where('chequeStatus',"Bounced");
        $this->db->or_where('chequeStatus',"Bounced&Returned");
        $this->db->group_end();
        $this->db->group_by('bounceChequeCount,chequeNo,chequeDate,chequeBank');
        $this->db->limit(1);
        $this->db->order_by('billpayments.id','desc');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getdataBounce($tableName)
    {
        $this->db->select("billpayments.*,sum(paidAmount) as sumAmount,sum(penalty) as penaltyAmount,bills.retailerName as Name,bills.billNo as Bno");
        $this->db->join("bills","bills.id = billpayments.billId",'left outer');
        // $this->db->where('(SELECT id FROM billpayments order_by billpayments.id desc)', NULL, FALSE);
        $this->db->where('billpayments.paidAmount >',0);
        // $this->db->where('billpayments.bounceChequeCount',0);
        $this->db->group_start();
        $this->db->where('chequeStatus',"Bounced");
        $this->db->or_where('chequeStatus',"Bounced&Returned");
        $this->db->group_end();
        $this->db->group_by('bounceChequeCount,chequeNo,chequeDate,chequeBank');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function loadRetailsers($tableName, $retailerCode)
    {
        $this->db->select('name');
        $this->db->where ('code',$retailerCode);
        //$this->db->orderby('id','desc');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }  

    public function loadRetailsersId($tableName, $retailerCode)
    {
        $this->db->select('name');
        $this->db->where ('id',$retailerCode);
        $this->db->where ('id >',0);
        //$this->db->orderby('id','desc');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function loadBillNumber($tblName, $id) {
        //$this->db->select("billNo");
        $this -> db ->where('id >', 0);
        $this -> db ->where_in('id', $id);
        $query = $this->db->get($tblName);
        
        return $query->result_array();   
    }
            
    public function getdataPenalty($tableName)
    {
        $this->db->select('id, name,amount');
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getPenaltyByName($tableName,$name)
    {
        $this->db->where('name',$name);
        $query=$this->db->get($tableName);
        
        return $query->result_array();
    }

    public function getdataCheckRegister($tableName)
    {
        $this->db->select("billpayments.*,bills.retailerName as Name,bills.billNo as Bno");
        $this->db->join("billpayments","bills.id = billpayments.billId");
        // $this->db->join("retailer","bills.retailerCode = retailer.code");
        $this->db->where('billpayments.chequeStatus', "Banked");
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }
    public function getdataBills($tableName)
    {
        $this->db->select("bills.*");
        // $this->db->join("retailer","bills.retailerCode = retailer.code","left outer");
        $this->db->where("bills.pendingAmt > 0");
         $this->db->where("bills.is_delete",0);
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }
    // public function getdataBanked($tableName)
    // {
    //     // $this->db->where('chequeStatus', "banked");
    //     // $query=$this->db->get($tableName);
    //     // return $query->result_array();
    //     $this->db->select("billpayments.*,retailer.name as Name,bills.billNo as Bno");
    //     $this->db->join("billpayments","bills.id = billpayments.billId");
    //     $this->db->join("retailer","bills.retailerCode = retailer.code");
    //     $this->db->where('billpayments.chequeStatus', "banked");
    //     $resultset=$this->db->get($tableName);
    //     return $resultset->result_array();
    // }
    public function getdataBounce1($tableName,$id)
    {
        $this->db->select("bills.*,bills.retailerName as Name");
        // $this->db->join("retailer","bills.retailerCode = retailer.code");
         $this->db->where_in('bills.id', array($id));
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }
    public function getdataReceived($tableName)
    {
        // $this->db->where('chequeStatus', "received");
        // $query=$this->db->get($tableName);
        // return $query->result_array();
        $this->db->select("billpayments.*,bills.id as id,bills.billNo as Bno");
        $this->db->join("bills","billpayments.billId = bills.id");
        $this->db->where('chequeStatus', "Banked");
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }
    public function getdataRetailerDate($tableName,$date,$comp)
    {
        $this->db->select("billpayments.*,bills.retailerName as Name,bills.billNo as Bno");
        $this->db->join("billpayments","bills.id = billpayments.billId");
        // $this->db->join("retailer","bills.retailerCode = retailer.code");
        $this->db->where('billpayments.chequeStatus', "New");
         $this->db->where('billpayments.paymentMode', "Cheque");
        $this->db->where('DATE(billpayments.chequeDate) <=', $date);
        $this->db->like('billpayments.compName', $comp, 'both');
        $this->db->order_by('billpayments.paidAmount','asc');
        // $this->db->where('billpayments.compName', $comp);
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

    public function groupByChequeDepositslipDate($tableName,$date,$comp)
    {
        $this->db->select("billpayments.*,sum(billpayments.paidAmount) as chAmount,bills.retailerName as Name,bills.billNo as Bno");
        $this->db->join("billpayments","bills.id = billpayments.billId");
        $this->db->where('billpayments.chequeStatus', "New");
         $this->db->where('billpayments.paymentMode', "Cheque");
        $this->db->where('DATE(billpayments.chequeDate) <=', $date);
        $this->db->like('billpayments.compName', $comp, 'both');
        $this->db->order_by('chAmount','asc');
        $this->db->group_by('billpayments.chequeDate,billpayments.chequeNo,billpayments.chequeBank');
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }


    
    public function getdataRetailerDateById($tableName,$date,$id)
    {
        $this->db->select("billpayments.*,bills.retailerName as Name,bills.billNo as Bno");
        $this->db->join("billpayments","bills.id = billpayments.billId");
        // $this->db->join("retailer","bills.retailerCode = retailer.code");
        $this->db->where('billpayments.chequeStatus', "Banked");
        $this->db->where('billpayments.chequeDate', $date);
        $this->db->where('billpayments.id',$id);
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }
     
    public function getdataRetailer($tableName,$date)
    {
        $this->db->distinct();
        $this->db->select("billpayments.*,bills.retailerName as Name,bills.billNo as Bno");
        $this->db->join("billpayments","bills.id = billpayments.billId","left outer");
        $this->db->where('billpayments.chequeStatus', "New");
        $this->db->where('billpayments.paymentMode', "Cheque");
        $this->db->where('billpayments.billId >', 0);
        $this->db->where('DATE(billpayments.chequeDate) <=', $date);
        $this->db->order_by('billpayments.paidAmount','asc');
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

    public function groupByChequeDepositslip($tableName,$date)
    {
        $this->db->distinct();
        $this->db->select("billpayments.*,sum(billpayments.paidAmount) as chAmount,bills.retailerName as Name,bills.billNo as Bno");
        $this->db->join("billpayments","bills.id = billpayments.billId","left outer");
        $this->db->where('billpayments.chequeStatus', "New");
        $this->db->where('billpayments.paymentMode', "Cheque");
        $this->db->where('billpayments.billId >', 0);
        $this->db->where('DATE(billpayments.chequeDate) <=', $date);
        $this->db->order_by('chAmount','asc');
        $this->db->group_by('billpayments.chequeDate,billpayments.chequeNo,billpayments.chequeBank');

        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

    public function getdataRetailerByDate($tableName,$date)
    {
        $this->db->distinct();
        $this->db->select("billpayments.*,bills.retailerName as Name,bills.billNo as Bno");
        $this->db->join("billpayments","bills.id = billpayments.billId","left outer");
        $this->db->where('billpayments.chequeStatus', "New");
        $this->db->where('billpayments.paymentMode', "Cheque");
        $this->db->where('billpayments.billId >', 0);
        $this->db->where('DATE(billpayments.chequeDate) <=',$date);
        $this->db->order_by('billpayments.paidAmount','asc');
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

    public function verifyCheque($tableName,$no,$date,$bank){
        $this->db->where('billpayments.chequeNo', $no);
        $this->db->where('DATE(billpayments.chequeDate)', $date);
        $this->db->where('billpayments.chequeBank', $bank);
        $this->db->where('billpayments.paymentMode', "Cheque");
        $this->db->where('billpayments.paidAmount >', 0);
        $this->db->group_start();
        // $this->db->where('billpayments.chequeStatus !=', "");
        $this->db->where('billpayments.chequeStatus !=', "Bounced");
        $this->db->where('billpayments.chequeStatus !=', "Bounced&Returned");
        $this->db->group_end();
        $this->db->order_by('billpayments.id','desc');
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

     public function verifyNeft($tableName,$no,$date,$amount)
    {
        $this->db->where('billpayments.neftNo', $no);
        $this->db->where('DATE(billpayments.neftDate)', $date);
        $this->db->where('billpayments.paidAmount', $amount);
        // $this->db->where('billpayments.chequeBank', $bank);
        $this->db->where('billpayments.paymentMode', "NEFT");
        $this->db->order_by('billpayments.id','desc');
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

     public function verifyNeftWithoutAmount($tableName,$no,$date)
    {
        $this->db->where('billpayments.neftNo', $no);
        $this->db->where('DATE(billpayments.neftDate)', $date);
        // $this->db->where('billpayments.paidAmount', $amount);
        // $this->db->where('billpayments.chequeBank', $bank);
        $this->db->where('billpayments.paymentMode', "NEFT");
        $this->db->order_by('billpayments.id','desc');
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

    public function findChequeNo($tableName,$no,$date,$amount)
    {
        $this->db->where('billpayments.chequeNo', $no);
        $this->db->where('DATE(billpayments.chequeDate)', $date);
        $this->db->where('billpayments.paidAmount', $amount);
        
        // $this->db->where('billpayments.chequeStatus !=', 'Bounced');
        // $this->db->or_where('billpayments.chequeStatus !=', 'Bounced&Returned');
        $this->db->where('billpayments.paymentMode', "Cheque");
        $this->db->order_by('billpayments.id','desc');
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

     public function findChequeWithBank($tableName,$no,$date,$bank)
    {
        $this->db->where('billpayments.chequeNo', $no);
        $this->db->where('DATE(billpayments.chequeDate)', $date);
        $this->db->where('billpayments.chequeBank', $bank);
        $this->db->where('billpayments.paymentMode', "Cheque");
        $this->db->where('billpayments.chequeStatus !=', "Bounced");
        $this->db->order_by('billpayments.id','desc');
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

    public function findNeft($tableName,$no,$date)
    {
        $this->db->where('billpayments.neftNo', $no);
        $this->db->where('DATE(billpayments.neftDate)', $date);
        $this->db->where('billpayments.paymentMode', "NEFT");
        $this->db->order_by('billpayments.id','desc');
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

     public function findNeftNo($tableName,$no,$date,$amount)
    {
        $this->db->where('billpayments.neftNo', $no);
        $this->db->where('DATE(billpayments.neftDate)', $date);
        $this->db->where('billpayments.paidAmount', $amount);
        
        // $this->db->where('billpayments.chequeStatus !=', 'Bounced');
        // $this->db->or_where('billpayments.chequeStatus !=', 'Bounced&Returned');
        $this->db->where('billpayments.paymentMode', "NEFT");
        $this->db->order_by('billpayments.id','desc');
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }
    
     public function findChequeNoWithStatus($tableName,$no,$date,$amount)
    {
        $this->db->where('billpayments.chequeNo', $no);
        $this->db->where('DATE(billpayments.chequeDate)', $date);
        $this->db->where('billpayments.paidAmount', $amount);
        $this->db->where('billpayments.chequeStatus', 'Bounced');
        $this->db->or_where('billpayments.chequeStatus', 'Bounced&Returned');
        $this->db->where('billpayments.paymentMode', "Cheque");
        $this->db->limit(1);
        $this->db->order_by('billpayments.id','desc');
        // $this->db->where('billpayments.chequeStatus !=', "Bounced");
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

    public function getOfficeCheques($tableName,$date)
    {
        // $this->db->select("billpayments.*");
       $this->db->distinct();
        $this->db->like('billpayments.chequeStatus', "New");
        $this->db->like('billpayments.paymentMode', "Cheque");
         $this->db->where('DATE(billpayments.chequeDate) <=', $date);
          $this->db->where('billpayments.isOfficeCheque','1');
          $this->db->order_by('billpayments.paidAmount','asc');
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

    public function groupByofficeChequeDepositSlip($tableName,$date)
    {
        $this->db->distinct();
        $this->db->select("billpayments.*,sum(billpayments.paidAmount) as chAmount");
        $this->db->like('billpayments.chequeStatus', "New");
        $this->db->like('billpayments.paymentMode', "Cheque");
         $this->db->where('DATE(billpayments.chequeDate) <=', $date);
          $this->db->where('billpayments.isOfficeCheque','1');
          $this->db->order_by('chAmount','asc');
        $this->db->group_by('billpayments.chequeDate,billpayments.chequeNo,billpayments.chequeBank');

        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

    public function getOfficeChequesWithCompany($tableName,$date,$comp)
    {
        $this->db->distinct();
        $this->db->where('billpayments.chequeStatus', "New");
        $this->db->where('billpayments.paymentMode', "Cheque");
        
        $this->db->where('DATE(billpayments.chequeDate) <=', $date);
        $this->db->where('billpayments.isOfficeCheque','1');
         $this->db->like('billpayments.compName', $comp, 'both');
         $this->db->order_by('billpayments.paidAmount','asc');
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

    public function getChequeDeposit($tableName,$chequeNo){
        $this->db->distinct();
        $this->db->select("billpayments.*,sum(billpayments.paidAmount) as chAmount");
        $this->db->where('billpayments.chequeNo', $chequeNo);
        $this->db->order_by('billpayments.paidAmount','asc');
        $this->db->group_by('billpayments.chequeDate,billpayments.chequeNo,billpayments.chequeBank');
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

    public function getChequeDepositByNo($tableName,$chequeNo,$chequeBank,$chequeDate){
        $this->db->distinct();
        $this->db->select("billpayments.*,sum(billpayments.paidAmount) as chAmount");
        $this->db->where('billpayments.chequeNo', $chequeNo);
        $this->db->where('DATE(billpayments.chequeDate)', $chequeDate);
        $this->db->where('billpayments.chequeBank', $chequeBank);
        $this->db->where('billpayments.tempStatus', '0');
        $this->db->where('billpayments.paidAmount >', 0);
        $this->db->order_by('billpayments.paidAmount','asc');
        $this->db->group_by('billpayments.chequeDate,billpayments.billNo,billpayments.chequeNo,billpayments.chequeBank');
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

     public function groupByofficeChequeDepositSlipByDate($tableName,$date,$comp)
    {
        // $this->db->distinct();
        $this->db->distinct();
        $this->db->select("billpayments.*,sum(billpayments.paidAmount) as chAmount");
        $this->db->where('billpayments.chequeStatus', "New");
        $this->db->where('billpayments.paymentMode', "Cheque");
        
        $this->db->where('DATE(billpayments.chequeDate) <=', $date);
        $this->db->where('billpayments.isOfficeCheque','1');
         $this->db->like('billpayments.compName', $comp, 'both');
         $this->db->order_by('chAmount','asc');
        $this->db->group_by('billpayments.chequeDate,billpayments.chequeNo,billpayments.chequeBank');

        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }
  
    public function getdataBounceBillpaymentsIdWise($tableName,$id)
    {
        $this->db->select("billpayments.*,bills.retailerName as Name,bills.billNo as Bno");
        $this->db->join("billpayments","bills.id = billpayments.billId");
        // $this->db->join("retailer","bills.retailerCode = retailer.code");
         $this->db->where('billpayments.id', $id);
        $this->db->where('billpayments.chequeStatus', "Banked");
        $this->db->or_where ('billpayments.chequeStatus',"Bounced");
        $resultset=$this->db->get($tableName);
        
        return $resultset->result_array();
    }

    public function getBillInfo($tableName,$neftNo,$neftDate)
    {
        $this->db->where('billpayments.neftNo', $neftNo);
        $this->db->where('DATE(billpayments.neftDate)', $neftDate);
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

    public function updateNeftData($tblName, $data, $neftNo,$neftDate) {
        $this->db->where('neftNo', $neftNo);
        $this->db->where('DATE(billpayments.neftDate)', $neftDate);
        return $this->db->update($tblName, $data);  
    }

    public function updateNeftDataWithBillNo($tblName, $data, $billId, $neftNo,$neftDate) {
        $this->db->where('billId', $billId);
        $this->db->where('neftNo', $neftNo);
        $this->db->where('DATE(billpayments.neftDate)', $neftDate);
        return $this->db->update($tblName, $data);  
    }

    public function updateChequeData($tblName, $data, $chequeNo,$chequeDate,$bank) {
        $this->db->where('chequeNo', $chequeNo);
        $this->db->where('chequeBank', $bank);
        $this->db->where('tempStatus','0');
        // $this->db->or_where('tempStatus','2');
        // $this->db->where('chequeStatus !=', "Bounced");
        $this->db->where('DATE(billpayments.chequeDate)', $chequeDate);
        return $this->db->update($tblName, $data);  
    }

    public function updateBounceChequeData($tblName, $data, $chequeNo,$chequeDate,$bank) {
        $this->db->where('chequeNo', $chequeNo);
        $this->db->where('chequeBank', $bank);
        $this->db->where('tempStatus !=','1');
        // $this->db->or_where('tempStatus','2');
        // $this->db->where('chequeStatus !=', "Bounced");
        $this->db->where('DATE(billpayments.chequeDate)', $chequeDate);
        return $this->db->update($tblName, $data);  
    }



    public function updateBillDetails($tblName, $data) {
        // $this->db->where('id', $id);
        return $this->db->update($tblName, $data);  
    }
    public function updateBills($tblName, $id) {
        $this->db->where('billNo', $id);
        return $this->db->update($tblName, array('is_delete'=>'0'));  
    }

    public function updateBillPayment($tblName,$data, $billId, $allocationId,$mode) {
        $this->db->where('billId', $billId);
        $this->db->where('allocationId', $allocationId);
        $this->db->where('paymentMode', $mode);
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
    public function load($tableName,$id)
    {
        $this->db->where('id',$id);
        $result=$this->db->get($tableName);
        
        return $result->result_array();
    }

    public function name_exists($tblName,$name){
        // $this->db->where('name',$name);
        $this->db->select('*');
        $this->db->like('name', $name);
        $query = $this->db->get($tblName);
        // $resultset=$this->db->get($tblName);
        
        return $query->result_array();
    }

    public function getIDbyBills($tblName,$bill){
        $this->db->select('id');
        $this->db->where('billNo',$bill);
        $query=$this->db->get($tblName);
        return $query->row()->id;
    }
    public function getRetailerbyBills($tblName,$bill){
        $this->db->select('retailerName');
        $this->db->where('id',$bill);
        $query=$this->db->get($tblName);
        return $query->row()->retailerName;
    }

    public function getDatebyBills($tblName,$bill){
        $this->db->select('date');
        $this->db->where('id',$bill);
        $query=$this->db->get($tblName);
        return $query->row()->date;
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
        
        return $query->result_array();
    }


}
?>