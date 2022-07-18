<?php
class CashBookModel extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function getdata($tableName)
    {
        $this->db->order_by('id','desc');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getDynamicNames($tblName,$type){
        $this->db->where('type', $type);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    public function getDayBookDetails($tableName,$fromDate,$toDate){
        $this->db->select('expences.*,employee.name as empName,allocations.allocationCode');
        $this->db->join('employee','expences.employeeId=employee.id','left outer');
        $this->db->join('allocations','expences.allocationId=allocations.id','left outer');
        $this->db->where('DATE(expences.date) >=', $fromDate);
        $this->db->where('DATE(expences.date) <=', $toDate);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function lastEntries($tableName)
    {
        $this->db->select('main_cashbook_expences.*,employee.name as empName');
        $this->db->join('employee','main_cashbook_expences.updatedBy=employee.id');
        $this->db->limit(10);
        $this->db->order_by('id','desc');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function allEntries($tableName)
    {
        $this->db->select('main_cashbook_expences.*,employee.name as empName');
        $this->db->join('employee','main_cashbook_expences.updatedBy=employee.id');
        $this->db->order_by('id','desc');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getDataByBillIdWithAllocation($tblName,$id,$amount,$mode,$allocationId) {
        $this->db->where('billId', $id);
        $this->db->where('allocationId', $allocationId);
        $this->db->where('paidAmount', $amount);
        $this->db->where('paymentMode', $mode);
        $resultset=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    public function getOwners($tableName){
        $this->db->where('designation','owner');
        $this->db->where('status',1);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getSuspenseDetail($tableName,$code){
        $this->db->where('code',$code);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function checkCashReconsilation($tableName,$name){
        $this->db->where('closeDayBookName',$name);
        // $this->db->where('expenseOwnerApproval',1);
        $this->db->where('bankDepositApproval',1);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getEmpLedgerByEmp($tableName,$empId)
    {
        $this->db->select("emptransactions.*,employee.id as empId,employee.name as empName,bills.billNo as billNo");
        $this->db->join("employee","emptransactions.empId = employee.id","left outer"); 
        $this->db->join("bills","emptransactions.billId = bills.id","left outer"); 
        $this->db->where('emptransactions.empId',$empId);
        $this->db->where('emptransactions.ownerApprovalStatus !=',2);
         // $this->db->where('employee.isSalaryEmp', 1);
        $resultset=$this->db->get($tableName); 
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    public function getEmpCompanydata($tableName,$id)
    {
        $this->db->select('company.name as company');
        $this->db->join('company','company.id=employee.companyId','left outer');
        $this->db->where('employee.id',$id);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getIncomeNotesDetails($tableName){
        $this->db->select('sum(note2000) as note2000,sum(note500) as note500,sum(note200) as note200,sum(note100) as note100,sum(note50) as note50,sum(note20) as note20,sum(note10) as note10,sum(coins) as coins,sum(collectedAmt) as collectedAmt');
        $this->db->join('expences','expences.notesId=notesdetails.id');
        $this->db->group_start();
        $this->db->where('transactionType','income');
        $this->db->or_where('transactionType','Market Collection');
        $this->db->or_where('transactionType','Office Collection');
        $this->db->group_end();
        $this->db->where('expences.isCloseDayBook',0);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getExpenseNotesDetails($tableName){
        $this->db->select('sum(note2000) as note2000,sum(note500) as note500,sum(note200) as note200,sum(note100) as note100,sum(note50) as note50,sum(note20) as note20,sum(note10) as note10,sum(coins) as coins,sum(collectedAmt) as collectedAmt');
        $this->db->join('expences','expences.notesId=notesdetails.id');
        $this->db->group_start();
        $this->db->where('transactionType','expense');
        // $this->db->or_where('transactionType','bank_deposit');
        $this->db->group_end();
        $this->db->where('expences.isCloseDayBook',0);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getContraEntryExpenseNotesDetails($tableName){
        $this->db->select('sum(note2000) as note2000,sum(note500) as note500,sum(note200) as note200,sum(note100) as note100,sum(note50) as note50,sum(note20) as note20,sum(note10) as note10,sum(coins) as coins,sum(collectedAmount) as collectedAmt');
        $this->db->where('transactionType','expense');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getContraEntryIncomeNotesDetails($tableName){
        $this->db->select('sum(note2000) as note2000,sum(note500) as note500,sum(note200) as note200,sum(note100) as note100,sum(note50) as note50,sum(note20) as note20,sum(note10) as note10,sum(coins) as coins,sum(collectedAmount) as collectedAmt');
        $this->db->where('transactionType','income');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getBankDepositNotesDetails($tableName){
        $this->db->select('sum(note2000) as note2000,sum(note500) as note500,sum(note200) as note200,sum(note100) as note100,sum(note50) as note50,sum(note20) as note20,sum(note10) as note10,sum(coins) as coins,sum(collectedAmt) as collectedAmt');
        $this->db->join('expences','expences.notesId=notesdetails.id');
        $this->db->group_start();
        // $this->db->where('transactionType','expense');
        $this->db->where('transactionType','bank_deposit');
        $this->db->group_end();
        $this->db->where('expences.isCloseDayBook',0);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getFinalBookNotesDetails($tableName){
        $this->db->where('transactionType','close_daybook');
        $this->db->order_by('id','desc');
        $this->db->limit(1);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getFinalEntryNotesDetails($tableName){
        // $this->db->where('transactionType','close_daybook');
        $this->db->order_by('id','desc');
        $this->db->limit(1);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getEmloyee($tableName)
    {
        $this->db->where('status','1');
        $this->db->where('isDeleted','0');
        $this->db->where('ownerApproval','1');
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }



    public function getInfo($tableName)
    {
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getDaybookDates($tblName){
        $this->db->select('date');
        $this->db->group_by('DATE(date)');
        $this->db->order_by('DATE(date)','desc');
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    public function getDaybookDatesWithDates($tblName,$fromDate,$toDate){
        $this->db->select('date');
        $this->db->where('DATE(date) >=',$fromDate);
        $this->db->where('DATE(date) <=',$toDate);
        $this->db->group_by('DATE(date)');
        $this->db->order_by('DATE(date)','desc');
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    public function getByType($tableName,$type)
    {
        $this->db->where('type',$type);
        $query=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function checkExpenseData($tblName){
        $this->db->where('expenseOwnerApproval',1);
        $query=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function get_expenseLimit(){
        $this->db->select('expenseLimit');
        $this->db->from('expenses_limit');
        $row = $this->db->get()->row();
        if (isset($row)) {
            return $row->expenseLimit;
        } else {
            return false;
        }
    }

    public function get_expenseOfficeLimit(){
        $this->db->select('expenseLimit');
        $this->db->from('expenses_limit');
        $this->db->where('id',2);
        $row = $this->db->get()->row();
        if (isset($row)) {
            return $row->expenseLimit;
        } else {
            return false;
        }
    }

    public function get_expenseDayBookLimit(){
        $this->db->select('expenseLimit');
        $this->db->from('expenses_limit');
         $this->db->where('id',3);
        $row = $this->db->get()->row();
        if (isset($row)) {
            return $row->expenseLimit;
        } else {
            return false;
        }
    }

    public function checkDayBook($tblName,$code){
        $date=date('Y-m-d');
        $this->db->where('DATE(closeDayBookDate)',$date);
        $query=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function getParking($tblName,$date){
        $this->db->select('SUM(parking) AS parking,SUM(challan) AS challan,SUM(cng) AS cng');
        $this->db->where('DATE(notesdetails.updatedAt)<=',$date);
        $resultset=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    //SELECT sum(parking+challan+cng) as expense FROM `notesdetails` WHERE allocationId=4 

    public function calculateExpense($allocationId){
        $query="SELECT parking,challan,cng,expenseOwnerApproval FROM `notesdetails` WHERE allocationId=".$allocationId;
        $data=$this->db->query($query);
        $this->db->close();
        $this->db->initialize();
        return $data->result_array();
    }


    //SELECT sum(amount) as outflowTotal FROM `expences` WHERE (inoutStatus='Outflow' and nature !='Bank Deposit') and DATE(date)='2020-07-16'

    public function calculateTotalIncome($name){
        $query="SELECT sum(amount) as inflowTotal FROM `expences` WHERE (inoutStatus='Inflow' and nature !='Contra Entry' and nature !='Bank Deposit' and nature !='Disallowed Advance Reversal' and nature !='Disallowed Expense Credit' and nature !='Disallowed Bank Deposit') and isCloseDayBook=0 and expenseOwnerApproval !=2";
        $data=$this->db->query($query);
        $this->db->close();
        $this->db->initialize();
        return $data->result_array();
    }

    public function calculateTotalIncomeByDaybook($name){
        $query="SELECT sum(amount) as inflowTotal FROM `expences` WHERE (inoutStatus='Inflow' and nature !='Contra Entry' and nature !='Bank Deposit' and nature !='Disallowed Advance Reversal' and nature !='Disallowed Expense Credit' and nature !='Disallowed Bank Deposit') and closeDayBookName='".$name."' and expenseOwnerApproval !=2";
        $data=$this->db->query($query);
        $this->db->close();
        $this->db->initialize();
        return $data->result_array();
    }

    //for accountant income expense daybook
    public function calculateTotalIncomeByDaybookDate($date){
        $query="SELECT sum(amount) as inflowTotal FROM `expences` WHERE (inoutStatus='Inflow' and nature !='Contra Entry' and nature !='Bank Deposit' and nature !='Disallowed Advance Reversal' and nature !='Disallowed Expense Credit' and nature !='Disallowed Bank Deposit') and DATE(date)='".$date."' and expenseOwnerApproval !=2";
        $data=$this->db->query($query);
        $this->db->close();
        $this->db->initialize();
        return $data->result_array();
    }

    //for accountant period income expense daybook
    public function calculateTotalPeriodIncomeByDaybookDate($fdate,$tdate){
        $query="SELECT sum(amount) as inflowTotal FROM `expences` WHERE (inoutStatus='Inflow' and nature !='Contra Entry' and nature !='Bank Deposit' and nature !='Disallowed Advance Reversal' and nature !='Disallowed Expense Credit' and nature !='Disallowed Bank Deposit') and (DATE(date)>='".$fdate."' and DATE(date)<='".$tdate."') and expenseOwnerApproval !=2";
        $data=$this->db->query($query);
        $this->db->close();
        $this->db->initialize();
        return $data->result_array();
    }


    //for accountant incomeexpense daybook
    public function calculateTotalIncomeByCompanyDate($date,$company){
        $query="SELECT sum(amount) as inflowTotal FROM `expences` WHERE (inoutStatus='Inflow' and nature !='Contra Entry' and nature !='Bank Deposit' and nature !='Disallowed Advance Reversal' and nature !='Disallowed Expense Credit' and nature !='Disallowed Bank Deposit') and DATE(date)='".$date."' and expenseOwnerApproval !=2 and company='$company'";
        $data=$this->db->query($query);
        return $data->row()->inflowTotal;
    }

    //for accountant period income expense daybook
    public function calculateTotalPeriodIncomeByCompanyDate($fdate,$tdate,$company){
        $query="SELECT sum(amount) as inflowTotal FROM `expences` WHERE (inoutStatus='Inflow' and nature !='Contra Entry' and nature !='Bank Deposit' and nature !='Disallowed Advance Reversal' and nature !='Disallowed Expense Credit' and nature !='Disallowed Bank Deposit') and (DATE(date)>='".$fdate."' and DATE(date)<='".$tdate."') and expenseOwnerApproval !=2 and company='$company'";
        $data=$this->db->query($query);
        return $data->row()->inflowTotal;
    }

     //for accountant income expense daybook
    public function calculateTotalIncomeByCompanyTypeDate($date,$company,$type){
        $query="SELECT sum(amount) as inflowTotal FROM `expences` WHERE (inoutStatus='Inflow' and nature !='Contra Entry' and nature !='Bank Deposit' and nature !='Disallowed Advance Reversal' and nature !='Disallowed Expense Credit' and nature !='Disallowed Bank Deposit') and DATE(date)='".$date."' and expenseOwnerApproval !=2 and company='$company' and nature='$type'";
        $data=$this->db->query($query);
        return $data->row()->inflowTotal;
    }

     //for accountant period income expense daybook
    public function calculateTotalPeriodIncomeByCompanyTypeDate($fdate,$tdate,$company,$type){
        $query="SELECT sum(amount) as inflowTotal FROM `expences` WHERE (inoutStatus='Inflow' and nature !='Contra Entry' and nature !='Bank Deposit' and nature !='Disallowed Advance Reversal' and nature !='Disallowed Expense Credit' and nature !='Disallowed Bank Deposit') and (DATE(date)>='".$fdate."' and DATE(date)<='".$tdate."') and expenseOwnerApproval !=2 and company='$company' and nature='$type'";
        $data=$this->db->query($query);
        return $data->row()->inflowTotal;
    }

    //for accountant income expense daybook
    public function calculateTotalOutflowByCompanyTypeDate($date,$company,$type){
        $query="SELECT sum(amount) as inflowTotal FROM `expences` WHERE (inoutStatus='Outflow' and nature !='Contra Entry' and nature !='Bank Deposit') and DATE(date)='".$date."' and expenseOwnerApproval !=2 and company='$company' and nature='$type'";
        $data=$this->db->query($query);
        return $data->row()->inflowTotal;
    }

    //for accountant period income expense daybook
    public function calculateTotalPeriodOutflowByCompanyTypeDate($fdate,$tdate,$company,$type){
        $query="SELECT sum(amount) as inflowTotal FROM `expences` WHERE (inoutStatus='Outflow' and nature !='Contra Entry' and nature !='Bank Deposit') and (DATE(date)>='".$fdate."' and DATE(date)<='".$tdate."') and expenseOwnerApproval !=2 and company='$company' and nature='$type'";
        $data=$this->db->query($query);
        return $data->row()->inflowTotal;
    }

    //for accountant income expense daybook
    public function calculateTotalOutflowByCompanyDate($date,$company){
        $query="SELECT sum(amount) as inflowTotal FROM `expences` WHERE (inoutStatus='Outflow' and nature !='Contra Entry' and nature !='Bank Deposit') and DATE(date)='".$date."' and expenseOwnerApproval !=2 and company='$company'";
        $data=$this->db->query($query);
        return $data->row()->inflowTotal;
    }

    //for accountant period income expense daybook
    public function calculateTotalPeriodOutflowByCompanyDate($fdate,$tdate,$company){
        $query="SELECT sum(amount) as inflowTotal FROM `expences` WHERE (inoutStatus='Outflow' and nature !='Contra Entry' and nature !='Bank Deposit') and (DATE(date)>='".$fdate."' and DATE(date)<='".$tdate."') and expenseOwnerApproval !=2 and company='$company'";
        $data=$this->db->query($query);
        return $data->row()->inflowTotal;
    }

    public function calculateTotalIncomeByDate($date){
        $query="SELECT sum(amount) as inflowTotal FROM `expences` WHERE (inoutStatus='Inflow' and nature !='Contra Entry' and nature !='Bank Deposit' and nature !='Disallowed Advance Reversal' and nature !='Disallowed Expense Credit' and nature !='Disallowed Bank Deposit') and (isCloseDayBook=0 and DATE(expences.date)<='".$date."') and expenseOwnerApproval !=2";
        $data=$this->db->query($query);
        $this->db->close();
        $this->db->initialize();
        return $data->result_array();
    }

    public function calculateTotalOutflow($date){
        $query="SELECT sum(amount) as outflowTotal FROM `expences` WHERE (inoutStatus='Outflow' and nature !='Contra Entry' and nature !='Bank Deposit') and isCloseDayBook=0 and expenseOwnerApproval !=2";
        $data=$this->db->query($query);
        $this->db->close();
        $this->db->initialize();
        return $data->result_array();
    }

    public function calculateTotalOutflowByDaybook($name){
        $query="SELECT sum(amount) as outflowTotal FROM `expences` WHERE (inoutStatus='Outflow' and nature !='Contra Entry' and nature !='Bank Deposit') and closeDayBookName='".$name."' and expenseOwnerApproval !=2";
        $data=$this->db->query($query);
        $this->db->close();
        $this->db->initialize();
        return $data->result_array();
    }

    public function calculateOutflowContraEntryForDayBook($date){
        $query="SELECT sum(amount) as outflowTotal FROM `expences` WHERE (inoutStatus='Outflow') and (isContraEntry=1) and (isCloseDayBook=0)";
        $data=$this->db->query($query);
        $this->db->close();
        $this->db->initialize();
        return $data->result_array();
    }

    public function calculateInflowContraEntryForDayBook($date){
        $query="SELECT sum(amount) as inflowTotal FROM `expences` WHERE (inoutStatus='Inflow') and (isContraEntry=1) and (isCloseDayBook=0)";
        $data=$this->db->query($query);
        $this->db->close();
        $this->db->initialize();
        return $data->result_array();
    }

    public function calculatePastOutflowContraEntryForDayBook($daybookName){
        $query="SELECT sum(amount) as outflowTotal FROM `expences` WHERE (inoutStatus='Outflow') and (isContraEntry=1) and closeDayBookName='".$daybookName."'";
        $data=$this->db->query($query);
        $this->db->close();
        $this->db->initialize();
        return $data->result_array();
    }

    public function calculatePastInflowContraEntryForDayBook($daybookName){
        $query="SELECT sum(amount) as inflowTotal FROM `expences` WHERE (inoutStatus='Inflow') and (isContraEntry=1) and closeDayBookName='".$daybookName."'";
        $data=$this->db->query($query);
        $this->db->close();
        $this->db->initialize();
        return $data->result_array();
    }

    public function calculateOutflowContraEntry($date){
        $query="SELECT sum(amount) as outflowTotal FROM `main_cashbook_expences` WHERE (inoutStatus='Outflow')";
        $data=$this->db->query($query);
        $this->db->close();
        $this->db->initialize();
        return $data->result_array();
    }

    public function calculateInflowContraEntry($date){
        $query="SELECT sum(amount) as inflowTotal FROM `main_cashbook_expences` WHERE (inoutStatus='Inflow') and (isBankDepositReturn=0)";
        $data=$this->db->query($query);
        $this->db->close();
        $this->db->initialize();
        return $data->result_array();
    }

    // public function calculateTotalOutflowByDaybookDate($date){
    //     $query="SELECT sum(amount) as outflowTotal FROM `expences` WHERE (inoutStatus='Outflow' and nature !='Bank Deposit') and DATE('closeBookDayDate')='".$date."' and expenseOwnerApproval=0";
    //     $data=$this->db->query($query);
    //     return $data->result_array();
    // }

    //for accountant income expense datbook
    public function calculateTotalOutflowByDaybookDate($date){
        $query="SELECT sum(amount) as outflowTotal FROM `expences` WHERE (inoutStatus='Outflow' and nature !='Contra Entry' and nature !='Bank Deposit') and DATE(date)='".$date."' and expenseOwnerApproval !=2";
        $data=$this->db->query($query);
        $this->db->close();
        $this->db->initialize();
        return $data->result_array();
    }

    //for accountant period income expense datbook 
    public function calculateTotalPeriodOutflowByDaybookDate($fdate,$tdate){
        $query="SELECT sum(amount) as outflowTotal FROM `expences` WHERE (inoutStatus='Outflow' and nature !='Contra Entry' and nature !='Bank Deposit') and (DATE(date)>='".$fdate."' and DATE(date)<='".$tdate."') and expenseOwnerApproval !=2";
        $data=$this->db->query($query);
        $this->db->close();
        $this->db->initialize();
        return $data->result_array();
    }


    public function calculateTotalOutflowByDate($date){
        $query="SELECT sum(amount) as outflowTotal FROM `expences` WHERE (inoutStatus='Outflow' and nature !='Contra Entry' and nature !='Bank Deposit') and (isCloseDayBook=0 and DATE(expences.date)<='".$date."') and expenseOwnerApproval !=2";
        $data=$this->db->query($query);
        $this->db->close();
        $this->db->initialize();
        return $data->result_array();
    }

    public function calculateTotalBankDeposit($date){
        $query="SELECT sum(amount) as bankflowTotal FROM `expences` WHERE (inoutStatus='Outflow' and nature ='Bank Deposit') and (isCloseDayBook=0 and bankDepositApproval !=2)";
        $data=$this->db->query($query);
        $this->db->close();
        $this->db->initialize();
        return $data->result_array();
    }

    public function calculateTotalBankDepositByDaybook($name){
        $query="SELECT sum(amount) as bankflowTotal FROM `expences` WHERE (inoutStatus='Outflow' and nature ='Bank Deposit') and closeDayBookName='".$name."' and bankDepositApproval !=2";
        $data=$this->db->query($query);
        $this->db->close();
        $this->db->initialize();
        return $data->result_array();
    }

    //by date 
    public function calculateTotalBankDepositByDaybookDate($date){
        $query="SELECT sum(amount) as bankflowTotal FROM `expences` WHERE (inoutStatus='Outflow' and nature ='Bank Deposit') and DATE('closeBookDayDate')='".$date."' and bankDepositApproval !=2";
        $data=$this->db->query($query);
        $this->db->close();
        $this->db->initialize();
        return $data->result_array();
    }

    public function calculateTotalBankDepositByDate($date){
        $query="SELECT sum(amount) as bankflowTotal FROM `expences` WHERE (inoutStatus='Outflow' and nature ='Bank Deposit') and (isCloseDayBook=0 and DATE(expences.date)<='".$date."') and bankDepositApproval !=2";
        $data=$this->db->query($query);
        $this->db->close();
        $this->db->initialize();
        return $data->result_array();
    }


    public function getAvailableBalance($tblName,$date){
        $this->db->limit(1);
        $this->db->select('expences.openCloseBalance');
        $this->db->where('isCloseDayBook',0);
        $this->db->order_by('id', 'DESC');
        $resultset=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    public function getAvailableBalanceByDate($tblName,$date){
        $this->db->limit(1);
        $this->db->select('expences.openCloseBalance');
        $this->db->where('isCloseDayBook',0);
        $this->db->where('DATE(date) <=',$date);
        $this->db->order_by('id', 'DESC');
        $resultset=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    public function getAvailableBalanceByDateCloseDay($tblName,$date){
        $this->db->limit(1);
        $this->db->select('expences.openCloseBalance');
        $this->db->where('isCloseDayBook',1);
        $this->db->where('DATE(date) <=',$date);
        $this->db->order_by('id', 'DESC');
        $resultset=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    public function getBankDepositBalance($tblName,$date,$nature){
        $this->db->select('sum(expences.amount) as avlBalance');
        $this->db->where('isCloseDayBook',0);
        $this->db->where('expences.nature',$nature);
        // $this->db->order_by('DATE(expences.date)', 'DESC');
        $resultset=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

     public function getBankDepositBalanceByDate($tblName,$date,$nature){
        $this->db->select('sum(expences.amount) as avlBalance');
        $this->db->where('isCloseDayBook',0);
        $this->db->where('expences.nature',$nature);
        $this->db->where('DATE(date) <=',$date);
        // $this->db->order_by('DATE(expences.date)', 'DESC');
        $resultset=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

     public function getInflowEmp($tableName)
    {
        $this->db->distinct();
        $this->db->select("expences.*,employee.name as name");
        $this->db->join("employee","expences.employeeId = employee.id","left outer");
        $this->db->where('isCloseDayBook',0);
        $this->db->where('amount >',0);
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    public function getInflowEmpByDayBook($tableName,$name)
    {
        $this->db->distinct();
        $this->db->select("expences.*,employee.name as name");
        $this->db->join("employee","expences.employeeId = employee.id","left outer");
        $this->db->where('closeDayBookName',$name);
        $this->db->order_by('expences.date');
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    //for accoutant income expense daybook
    public function getInflowEmpByDayBookDate($tableName,$date)
    {
        $this->db->distinct();
        $this->db->select("expences.*,employee.name as name");
        $this->db->join("employee","expences.employeeId = employee.id","left outer");
        $this->db->where('DATE(expences.date)',$date);
        $this->db->order_by('expences.date');
        $this->db->order_by('expences.isCloseDayBook',1);
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    //for accoutant period income expense daybook
    public function getPeriodInflowEmpByDayBookDate($tableName,$sdate,$edate)
    {
        $this->db->distinct();
        $this->db->select("expences.*,employee.name as name");
        $this->db->join("employee","expences.employeeId = employee.id","left outer");
        $this->db->where('DATE(expences.date) >=',$sdate);
        $this->db->where('DATE(expences.date) <=',$edate);
        $this->db->order_by('expences.date');
        $this->db->order_by('expences.isCloseDayBook',1);
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    public function getPendingExpenseCount($tableName)
    {
        $this->db->distinct();
        $this->db->select("expences.*,employee.name as name");
        $this->db->join("expences","expences.allocationId = notesdetails.allocationId");
        $this->db->join("employee","expences.employeeId = employee.id","left outer");
        $this->db->where('notesdetails.expenseOwnerApproval',1);
        $this->db->where('expences.isCloseDayBook',0);
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    public function getPendingExpenseCountByDate($tableName,$date)
    {
        $this->db->distinct();
        $this->db->select("expences.*,employee.name as name");
        $this->db->join("expences","expences.allocationId = notesdetails.allocationId");
        $this->db->join("employee","expences.employeeId = employee.id","left outer");
        $this->db->where('notesdetails.expenseOwnerApproval',1);
        $this->db->where('expences.isCloseDayBook',0);
        $this->db->where('DATE(expences.date)',$date);
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

     public function getPendingExpenseCountBetweenDate($tableName,$fdate,$tdate)
    {
        $this->db->distinct();
        $this->db->select("expences.*,employee.name as name");
        $this->db->join("expences","expences.allocationId = notesdetails.allocationId");
        $this->db->join("employee","expences.employeeId = employee.id","left outer");
        $this->db->where('notesdetails.expenseOwnerApproval',1);
        $this->db->where('expences.isCloseDayBook',0);
        $this->db->where('DATE(expences.date) >=',$fdate);
        $this->db->where('DATE(expences.date) <=',$tdate);
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    public function getPendingCashExpenseCount($tableName)
    {
        $this->db->distinct();
        $this->db->select("expences.*");
        $this->db->where('expences.expenseOwnerApproval',1);
        $this->db->where('expences.isCloseDayBook',0);
        $this->db->where('expences.nature !=','Bank Deposit');
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    public function getPendingCashExpenseCountByDate($tableName,$date)
    {
        $this->db->distinct();
        $this->db->select("expences.*");
        $this->db->where('expences.expenseOwnerApproval',1);
        $this->db->where('expences.isCloseDayBook',0);
        $this->db->where('expences.nature !=','Bank Deposit');
        $this->db->where('DATE(expences.date)',$date);
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    public function getPendingCashExpenseCountBetweenDate($tableName,$fdate,$tdate)
    {
        $this->db->distinct();
        $this->db->select("expences.*");
        $this->db->where('expences.expenseOwnerApproval',1);
        $this->db->where('expences.isCloseDayBook',0);
        $this->db->where('expences.nature !=','Bank Deposit');
        $this->db->where('DATE(expences.date) >=',$fdate);
        $this->db->where('DATE(expences.date) <=',$tdate);
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }


    public function getInflowEmpByDate($tableName,$date)
    {
        $this->db->distinct();
        $this->db->select("expences.*,employee.name as name");
        $this->db->join("employee","expences.employeeId = employee.id","left outer");
        $this->db->where('isCloseDayBook',0);
         $this->db->where('DATE(date) <=',$date);
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    public function getCategories($tblName,$type){
        $this->db->where('type',$type);
        $res=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $res->result_array();
    }   
    
    public function getPastDayInflowEmp($tableName,$closeDayBookName)
    {
        $this->db->distinct();
        $this->db->select("expences.*,employee.name as name");
        $this->db->join("employee","expences.employeeId = employee.id");
        $this->db->where('closeDayBookName',$closeDayBookName);
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

     public function getAllocationDetails($tableName,$id)
    {
        $this->db->distinct();
        $this->db->select("allocations.*,route.name as name");
        $this->db->join("route","route.id = allocations.routId");
        $this->db->where('allocations.id',$id);
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }
    
    public function getInflowCategory($tableName)
    {
        $date=date('Y-m-d');
        $this->db->distinct();
        $this->db->select("expences.*");
        $this->db->where('category !=',"");
        $this->db->where('isCloseDayBook',0);
        // $this->db->join("employee","expences.employeeId = employee.id");
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }
    
     public function getPastDayInflowCategory($tableName,$date)
    {
        $this->db->distinct();
        $this->db->select("expences.*");
        $this->db->where('category !=',"");
        $this->db->where('isCloseDayBook',0);
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    public function getSalesReturnDS($tableName)
    {
        $this->db->select("bills.*,bills.retailerName as name");
        // $this->db->join("retailer","bills.retailerCode = retailer.code");
        $this->db->where("billType","deliveryslip");
        $this->db->order_by("date","desc");
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }
     public function getBillCR($tableName,$id)
    {
        $this->db->select("bills.*,bills.retailerName as name");
        // $this->db->join("retailer","bills.retailerCode = retailer.code");
        $this->db->where("bills.billType","deliveryslip");
        $this->db->where("bills.id",$id);

        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    public function updateBankDeposit($tableName,$data,$id){
        $this->db->where('notesId', $id);
        return $this->db->update($tableName, $data);  
    }

    public function updateReceiveAmt($tableName,$amt,$id){
        $this->db->set('receivedAmt','receivedAmt+'.$amt,false);
        $this->db->set('pendingAmt','pendingAmt-'.$amt,false);
        $this->db->where('id',$id);
        return $this->db->update($tableName);
    }

    public function updateExpenseStatusByDate($tableName,$data){
        $this->db->where('isCloseDayBook', '0');
        return $this->db->update($tableName, $data);  
    }

    public function updateNotesDetails($tblName, $data, $id) {
        $this->db->where('allocationId', $id);
        return $this->db->update($tblName, $data);  
    }

    public function loadBillDetails($tableName, $id)
    {
        $this->db->select("billsdetails.*,bills.billNo as billNo,bills.date as Date,bills.retailerName as name");
        $this->db->join("bills","billsdetails.billId = bills.id");
        // $this->db->join("retailer","bills.retailerCode = retailer.code");
        $this ->db->where('billsdetails.billId', $id);
        // $this->db->order_by('billsdetails.date', 'desc');
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
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

    public function checkHistory($tblName,$billId,$allocationId){
        $this->db->where('billId', $billId);
        $this->db->where('allocationId', $allocationId);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }


    public function updateAllocationBillsStatus($tblName, $data, $id) {
        $this->db->where('allocationId', $id);
        return $this->db->update($tblName, $data);  
    }

    public function updateAllocationStatus($tblName, $data, $allocationId,$billId) {
        $this->db->where('billId', $billId);
        $this->db->where('allocationId', $allocationId);
        return $this->db->update($tblName, $data);  
    }

   public function chkBillsStatus($tblName, $id,$type) {
        $this->db->where('billId', $id);
        $resultset=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    public function statusUpdateByBillId($tblName, $data, $id, $amount,$mode) {
        $this->db->where('billId', $id);
        $this->db->where('paidAmount', $amount);
        $this->db->where('paymentMode', $mode);
        return $this->db->update($tblName, $data);  
    }

    


    public function statusUpdateByBillIdWithAllocation($tblName, $data, $id, $amount,$mode,$allocationId) {
        $this->db->where('billId', $id);
        $this->db->where('allocationId', $allocationId);
        $this->db->where('paidAmount', $amount);
        $this->db->where('paymentMode', $mode);
        return $this->db->update($tblName, $data);  
    }

    public function loadBillPaymentDetails($tblName, $id, $amount,$mode) {
        $this->db->where('billId', $id);
        $this->db->where('paidAmount', $amount);
        $this->db->where('paymentMode', $mode);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
    }

    public function loadChkBillPaymentDetails($tblName, $id, $alId,$mode) {
        $this->db->where('billId', $id);
        $this->db->where('allocationId', $alId);
        $this->db->where('paymentMode', $mode);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();  
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
        $this -> db -> where('id', $id);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();

        return $query->result_array();   
    }
    public function loadByAllocationId($tblName, $id) {
        $this -> db -> where('allocationId', $id);
        $query = $this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();   
    }

      public function getRouteName($code){
        $this->db->select('name'); 
        $this->db->from('route');
        $this->db->where('code',$code);   
        return $this->db->get()->result_array();
    }

    public function getEmployeeNames(){
        $this->db->select('id,name'); 
        $this->db->from('employee');
        $this->db->where('ownerApproval',1);
        return $this->db->get()->result_array();
    }

    public function loadBalAmt($tblname,$id){
        $this->db->where('allocationId',$id); 
        $this->db->from($tblname);
        return $this->db->get()->result_array();
    }


    public function getEmployeeNamesByID($id){
        $this->db->select('name'); 
        $this->db->from('employee');
        $this->db->where('id',$id);   
        return $this->db->get()->row()->name;
    }

    public function getAllocatedBills($tableName,$id){
            $this->db->select('bills.*');
            $this->db->from($tableName);
            $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
            // $this->db->join('billpayments','billpayments.billId=bills.id');
            $this->db->where('allocationsbills.allocationId',$id);
            $query=$this->db->get();
            $this->db->close();
        $this->db->initialize();
            return $query->result_array();
    }

   public function getAllocatedBillsByType($tableName,$id,$type,$status){
        $this->db->select('bills.*');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
        $this->db->where('allocationsbills.allocationId',$id);
        $this->db->where('allocationsbills.billStatus',$status);
        $this->db->where('bills.billType',$type);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
   }

    public function getAllocatedPastBillsByType($tableName,$id){
        $this->db->select('bills.*');
        $this->db->from($tableName);
        $this->db->join('allocationsbills','allocationsbills.billId=bills.id');
        $this->db->group_start();
        $this->db->where('bills.billType','officeAdjustmentBill');
        $this->db->or_where('bills.billType','allocatedbillPass');
        $this->db->or_where('bills.billType','adHocDeliveryBill');
        $this->db->group_end();
        $this->db->where('allocationsbills.allocationId',$id);
        $this->db->where('allocationsbills.billStatus','2');
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

   // public function getOpeningBalance($tblName){
   //      $date=date('Y-m-d');
   //      $this->db->limit(1);
   //      $this->db->where('DATE(date)<',$date);
   //      $qeury=$this->db->get($tblName);
   //      return $qeury->result_array();
   // }

   public function getClosingBalance($tblName){
        $this->db->limit(1);
        $this->db->where('isCloseDayBook',1);
        // $this->db->where('DATE(date)<=',$date);
        $this->db->order_by('id','desc');
        $qeury=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $qeury->result_array();
   }

   public function getlastEntryDayBook($tblName){
        $this->db->limit(1);
        $this->db->where('isCloseDayBook',0);
        $this->db->order_by('id','desc');
        $qeury=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $qeury->result_array();
   }

   public function getClosingBalanceByDayBookName($tblName,$closeDayBookName){
        $this->db->limit(1);
        $this->db->where('closeDayBookName',$closeDayBookName);
        $qeury=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $qeury->result_array();
   }

   public function getClosingBalanceByDate($tblName,$date){
        $this->db->limit(1);
        $this->db->where('DATE(date)<=',$date);
        $this->db->order_by('id','desc');
        $qeury=$this->db->get($tblName);
        $this->db->close();
        $this->db->initialize();
        return $qeury->result_array();
   }

   public function getInflowOutflow($tableName){
       $date=date('Y-m-d');
        $this->db->select('allocations.*,notesdetails.*,employee.name');
        $this->db->from($tableName);
        $this->db->join('notesdetails','notesdetails.allocationId=allocations.id');
        $this->db->join('employee','employee.id=allocations.fieldstaffCode1');
        $this->db->where('notesdetails.updatedAt',$date);
        $qeury=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $qeury->result_array();
   }

   public function getPastAllocationDetail($tableName,$id){
        $this->db->select('allocations.*,e1.name as ename1,e2.name as ename2,e3.name as ename3,e4.name as ename4');
        $this->db->from($tableName);
        $this->db->join('employee e1','e1.id=allocations.fieldstaffCode1','left outer');
        $this->db->join('employee e2','e2.id=allocations.fieldstaffCode2','left outer');
        $this->db->join('employee e3','e3.id=allocations.fieldstaffCode3','left outer');
        $this->db->join('employee e4','e4.id=allocations.fieldstaffCode4','left outer');
        $this->db->where('allocations.id',$id);
        $qeury=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $qeury->result_array();
   }
   
   public function getPastDayInflowOutflow($tableName,$date){
        $this->db->select('allocations.*,notesdetails.*,employee.name');
        $this->db->from($tableName);
        $this->db->join('notesdetails','notesdetails.allocationId=allocations.id');
        $this->db->join('employee','employee.id=allocations.fieldstaffCode1');
        $this->db->where('notesdetails.updatedAt',$date);
        $qeury=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $qeury->result_array();
   }

   public function lastRecordInOutFlow(){
        $date=date('Y-m-d');
        $query = $this->db->query("SELECT * FROM expences ORDER BY id desc LIMIT 1");
        $this->db->close();
        $this->db->initialize();
        return $query->row_array();
    }

    public function getLastRecord($tblName){
        $query = $this->db->query("SELECT * FROM ".$tblName." ORDER BY id desc LIMIT 1");
        $this->db->close();
        $this->db->initialize();
        return $query->row_array();
    }

    public function lastRecordMainCashbook(){
        $date=date('Y-m-d');
        $query = $this->db->query("SELECT * FROM main_cashbook_expences ORDER BY id desc LIMIT 1");
        $this->db->close();
        $this->db->initialize();
        return $query->row_array();
    }

    public function lastRecordDayBookValue(){
        $date=date('Y-m-d');
        $query = $this->db->query("SELECT * FROM expences ORDER BY id desc LIMIT 1");
        $this->db->close();
        $this->db->initialize();
        return $query->row_array();
    }

    public function lastRecordValue(){
        $date=date('Y-m-d');
        $query = $this->db->query("SELECT * FROM expences where isCloseDayBook=1 ORDER BY id desc LIMIT 1");
        $this->db->close();
        $this->db->initialize();
        return $query->row_array();
    }

    public function lastRecordValueByDaybook($name){
        $date=date('Y-m-d');
        $query = $this->db->query("SELECT * FROM expences where closeDayBookName='".$name."' ORDER BY id desc LIMIT 1");
        $this->db->close();
        $this->db->initialize();
        return $query->row_array();
    }
    
    public function closingBalValue(){
        $date=date('Y-m-d');
        $query = $this->db->query("SELECT * FROM expences where isCloseDayBook=0 and date ='".$date."' ORDER BY id DESC LIMIT 1");
        $this->db->close();
        $this->db->initialize();
        return $query->row_array();
    }
    
    public function openingRecordValue(){
        $date=date('Y-m-d');
        $query = $this->db->query("SELECT * FROM expences where isCloseDayBook=0 and date !='".$date."' ORDER BY id desc LIMIT 1");
        $this->db->close();
        $this->db->initialize();
        return $query->row_array();
    }
    
    public function sumBankDeposit(){
        $date=date('Y-m-d');
        $query = $this->db->query("SELECT sum(amount) as BankDepSum FROM expences where isCloseDayBook=0 and date='".$date."' and nature='Bank Deposit' and bankDepositApproval !=2 ORDER BY id DESC LIMIT 1");
        $this->db->close();
        $this->db->initialize();
        return $query->row_array();
    }

    public function sumIncome(){
        $date=date('Y-m-d');
        $query = $this->db->query("SELECT sum(amount) as income FROM expences where isCloseDayBook=0 and date='".$date."' and inoutStatus='Inflow' ORDER BY id DESC LIMIT 1");
        $this->db->close();
        $this->db->initialize();
        return $query->row_array();
    }

    public function sumExp(){
        $date=date('Y-m-d');
        $query = $this->db->query("SELECT sum(amount) as expense FROM expences where isCloseDayBook=0 and date='".$date."' and inoutStatus='Outflow' ORDER BY id DESC LIMIT 1");
        $this->db->close();
        $this->db->initialize();
        return $query->row_array();
    }

    public function lastRecordValueByDate($date){
        
        $query = $this->db->query("SELECT * FROM expences where date ='".$date."' ORDER BY id DESC LIMIT 1");
        $this->db->close();
        $this->db->initialize();
        return $query->row_array();
    }
    public function closingBalValueByDate($date){
      
        $query = $this->db->query("SELECT * FROM expences where date ='".$date."' ORDER BY id DESC LIMIT 1");
        $this->db->close();
        $this->db->initialize();
        return $query->row_array();
    }
    
    public function openingRecordValueByDate($date){
        // $date=date_create($date)->modify('-1 days')->format('Y-m-d');
        // echo "dt ".$date;
        $query = $this->db->query("SELECT * FROM expences where date ='".$date."' ORDER BY id asc LIMIT 1");
        $this->db->close();
        $this->db->initialize();
        return $query->row_array();
    }
    
    public function sumBankDepositByDate($date){
        $query = $this->db->query("SELECT sum(amount) as BankDepSum FROM expences where date='".$date."' and nature='Bank Deposit' ORDER BY id DESC LIMIT 1");
        $this->db->close();
        $this->db->initialize();
        return $query->row_array();
    }

    public function sumIncomeByDate($date){
        $query = $this->db->query("SELECT sum(amount) as income FROM expences where date='".$date."' and inoutStatus='Inflow' ORDER BY id DESC LIMIT 1");
        $this->db->close();
        $this->db->initialize();
        return $query->row_array();
    }

    public function sumExpenseByDate($date){
        $query = $this->db->query("SELECT sum(amount) as expense FROM expences where date='".$date."' and inoutStatus='Outflow' ORDER BY id DESC LIMIT 1");
        $this->db->close();
        $this->db->initialize();
        return $query->row_array();
    }
    
    public function getDates($tableName){
        $date=date('Y-m-d');
        $this->db->distinct();
        $this->db->select('date');
        $this->db->from($tableName);
        $this->db->where('date !=',$date);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

     public function getfieldStaffsById($tableName,$id)
    {
        $this->db->select("id,fieldStaffCode1,fieldStaffCode2,fieldStaffCode3,fieldStaffCode4");
        $this->db->where("id",$id);
        $resultset=$this->db->get($tableName);
        $this->db->close();
        $this->db->initialize();
        return $resultset->result_array();
    }

    public function getOnlyName($table,$id){
       return $this->db->select('name')->where('id', $id)->limit(1)->get($table)->row()->name;
    }

    public function paginationMainCashBook($tableName,$limit, $start, $st = "", $orderField, $orderDirection){
        $this->db->distinct();
        $this->db->select('main_cashbook_expences.*');
        $this->db->from($tableName);
        $this->db->group_start();
        $this->db->like('natire', $st);
        $this->db->or_like('category', $st);
        $this->db->or_like('inoutstatus', $st);
        $this->db->or_like('naration', $st);
        $this->db->group_end();
        $this->db->order_by($orderField, $orderDirection);
        $this->db->limit($limit, $start);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->result_array();
    }

    public function countPaginationMainCashBook($tableName,$limit, $start, $st = "", $orderField, $orderDirection){
        $this->db->distinct();
        $this->db->select('main_cashbook_expences.*');
        $this->db->from($tableName);
        $this->db->group_start();
        $this->db->like('natire', $st);
        $this->db->or_like('category', $st);
        $this->db->or_like('inoutstatus', $st);
        $this->db->or_like('naration', $st);
        $this->db->group_end();
        $this->db->order_by($orderField, $orderDirection);
        $query=$this->db->get();
        $this->db->close();
        $this->db->initialize();
        return $query->num_rows();
    }

}
?>