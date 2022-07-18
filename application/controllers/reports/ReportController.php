<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
class ReportController extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->model('ReportModel');
        $this->load->library('session');
        date_default_timezone_set('Asia/Kolkata');
        ini_set('memory_limit', '-1');

        if(isset($this->session->userdata['codeKeyData'])) {
            $this->projectSessionName= $this->session->userdata['codeKeyData']['codeKeyValue'];
        }else{
            $this->load->view('LoginView');
        }
    }

    //Datewise bill collection
    public function datewiseReportView(){
        $data['company']=$this->ReportModel->getdata('company');
        $data['retailer']=$this->ReportModel->getdata('retailer');
        $data['salesman']=$this->ReportModel->getBillsSalesman('bills');
        $this->load->view('reports/datewiseCollectionReportView',$data);
    }

    //Datewise bill collection export
    public function datewiseReportData(){
        $fromDate=$this->input->post('fromDate');
        $toDate=$this->input->post('toDate');
        $company=$this->input->post('company');

        $billDetails=array();
        if($company=="All"){
            $billDetails=$this->ReportModel->getDatewiseCollectionsDetailsUsingDates('bills',$fromDate,$toDate);
        }else{
            $billDetails=$this->ReportModel->getDatewiseCollectionsDetailsUsingCompany('bills',$fromDate,$toDate,$company);
        }
        $this->datewiseReport($billDetails);
    }

    //Billwise Retailer collection
    public function billWiseRetailerReportView(){
        $data['company']=$this->ReportModel->getdata('company');
        $data['retailer']=$this->ReportModel->getdata('retailer');
        $data['salesman']=$this->ReportModel->getBillsSalesman('bills');

        $this->load->view('reports/collectionwiseReportView',$data);
    }

    //Billwise Retailer collection export
    public function billwiseRetailerCollectionReportData(){
        $fromDate=$this->input->post('fromDate');
        $toDate=$this->input->post('toDate');
        $company=$this->input->post('company');

        $billDetails=array();
        if($company=="All"){
            $billDetails=$this->ReportModel->getBillwiseRetailerCollectionsDetailsUsingDates('bills',$fromDate,$toDate);
        }else{
            $billDetails=$this->ReportModel->getBillwiseRetailerCollectionsDetailsUsingCompany('bills',$fromDate,$toDate,$company);
        }
        $this->billwiseRetailerCollectionReport($billDetails);
    }

    //allocation bill collection
    public function allocationWiseCollectionReportView(){
        $data['company']=$this->ReportModel->getdata('company');
        $data['retailer']=$this->ReportModel->getdata('retailer');
        $data['salesman']=$this->ReportModel->getBillsSalesman('bills');
        $this->load->view('reports/allocationwiseCollectionView',$data);
    }

    

    //Billwise bill collection
    public function billWiseCollectionReportView(){
        $data['company']=$this->ReportModel->getdata('company');
        $data['retailer']=$this->ReportModel->getdata('retailer');
        $data['salesman']=$this->ReportModel->getBillsSalesman('bills');
        $this->load->view('reports/billwiseCollectionView',$data);
    }

    //Billwise bill collection export
    public function billwiseReportData(){
        $fromDate=$this->input->post('fromDate');
        $toDate=$this->input->post('toDate');
        $company=$this->input->post('company');

        $billDetails=array();
        if($company=="All"){
            $billDetails=$this->ReportModel->getBillwiseCollectionsDetailsUsingDates('bills',$fromDate,$toDate);
        }else{
            $billDetails=$this->ReportModel->getBillwiseCollectionsDetailsUsingCompany('bills',$fromDate,$toDate,$company);
        }
        $this->billwiseReport($billDetails);
    }

    //allocation bill collection export
    public function allocationwiseReportData(){
        $fromDate=$this->input->post('fromDate');
        $toDate=$this->input->post('toDate');
        // $company=$this->input->post('company');
       
        $allocationDetails=$this->ReportModel->getAllocationwiseDetailsUsingDates('allocations',$fromDate,$toDate);
        
        // print_r($allocationDetails);exit;
        $this->allocationCollectionReport($allocationDetails);
    }

    //Allocation collection data export
    public function allocationCollectionReport($collectionDetails){
        if(empty($collectionDetails)){
            echo "empty data";
        }else{
            $file="Allocation_Collection_Report.xlsx";
            $newFileName= $file;

            $spreadsheet = new Spreadsheet();
            $spreadsheet->setActiveSheetIndex(0);

            foreach (range('A','J') as $col) {
            $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            }

            $spreadsheet->getActiveSheet()->SetCellValue('A1', 'S. No.');
            $spreadsheet->getActiveSheet()->SetCellValue('B1', 'Allocation Date');
            $spreadsheet->getActiveSheet()->SetCellValue('C1', 'Allocation Code');
            $spreadsheet->getActiveSheet()->SetCellValue('D1', 'Allocation Total');
            $spreadsheet->getActiveSheet()->SetCellValue('E1', 'Total Bills');
            $spreadsheet->getActiveSheet()->SetCellValue('F1', 'Allocation Employees');
            $spreadsheet->getActiveSheet()->SetCellValue('G1', 'Allocation Salesman');
            $spreadsheet->getActiveSheet()->SetCellValue('H1', 'Allocation Routes');
            $spreadsheet->getActiveSheet()->SetCellValue('I1', 'Company');
            $spreadsheet->getActiveSheet()->SetCellValue('J1', 'Total Cash');
            $spreadsheet->getActiveSheet()->SetCellValue('K1', 'Total Cheque');
            $spreadsheet->getActiveSheet()->SetCellValue('L1', 'Total NEFT');
            $spreadsheet->getActiveSheet()->SetCellValue('M1', 'Total Other Adj');
            $spreadsheet->getActiveSheet()->SetCellValue('N1', 'Total SR');

            $no=0;
            $num=1;
            $rowCount = 2;
            foreach ($collectionDetails as $element) {
                $cash=$this->ReportModel->loadBillPayments('billpayments',$element['id'],'Cash');
                $cheque=$this->ReportModel->loadBillPayments('billpayments',$element['id'],'Cheque');
                $neft=$this->ReportModel->loadBillPayments('billpayments',$element['id'],'NEFT');
                $other=$this->ReportModel->loadBillPayments('billpayments',$element['id'],'Other Adjustment');

                $cashTotal=0;
                $chequeTotal=0;
                $neftTotal=0;
                $otherAdjTotal=0;

                if(!empty($cash)){
                    $cashTotal=$cashTotal+$cash[0]['paidAmount'];
                }

                if(!empty($cheque)){
                    $chequeTotal=$chequeTotal+$cheque[0]['paidAmount'];
                }

                if(!empty($neft)){
                    $neftTotal=$neftTotal+$neft[0]['paidAmount'];
                }

                if(!empty($other)){
                    $otherAdjTotal=$otherAdjTotal+$other[0]['paidAmount'];
                }

                $paymentdate=date('d-M-Y',strtotime($element['date']));

                $spreadsheet->getActiveSheet()->SetCellValue('A' . $rowCount, $num);
                $spreadsheet->getActiveSheet()->SetCellValue('B' . $rowCount, $paymentdate);
                $spreadsheet->getActiveSheet()->SetCellValue('C' . $rowCount, $element['allocationCode']);
                $spreadsheet->getActiveSheet()->SetCellValue('D' . $rowCount, ($element['allocationTotalAmount']));
                $spreadsheet->getActiveSheet()->SetCellValue('E' . $rowCount, $element['allocationBillCount']);
                $spreadsheet->getActiveSheet()->SetCellValue('F' . $rowCount, trim($element['allocationEmployeeName']));
                $spreadsheet->getActiveSheet()->SetCellValue('G' . $rowCount, trim($element['allocationSalesman']));
                $spreadsheet->getActiveSheet()->SetCellValue('H' . $rowCount, trim($element['allocationRouteName']));
                $spreadsheet->getActiveSheet()->SetCellValue('I' . $rowCount, $element['company']);
                $spreadsheet->getActiveSheet()->SetCellValue('J' . $rowCount, ($cashTotal));
                $spreadsheet->getActiveSheet()->SetCellValue('K' . $rowCount, ($chequeTotal));
                $spreadsheet->getActiveSheet()->SetCellValue('L' . $rowCount, ($neftTotal));
                $spreadsheet->getActiveSheet()->SetCellValue('M' . $rowCount, ($otherAdjTotal));
                $spreadsheet->getActiveSheet()->SetCellValue('N' . $rowCount, ($element['totalSRAmt']));

                $rowCount++;
                $no++;
                $num++;
            }
        
            $writer = new Xlsx($spreadsheet);
            $fileName=$file;
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
            $writer->save('php://output');
        }
    }

    //Delivery slip data export
    public function deliveryslipSalesReportView(){
        $data['company']=$this->ReportModel->getdata('company');
        $data['retailer']=$this->ReportModel->getdata('retailer_kia');
        $data['salesman']=$this->ReportModel->getSalesman('employee');
        $this->load->view('reports/deliveryslipSalesView',$data);
    }

    //Delivery slip data export
    public function deliveryslipSalesReport(){
        $salesman=trim($this->input->post('salesman'));
        $retailer=trim($this->input->post('retailer'));

        $fromDate=trim($this->input->post('fromDate'));
        $toDate=trim($this->input->post('toDate'));
        
        $billDetails=array();
        if($salesman=="All" || $retailer="All" || $salesman=="" || $retailer=""){
            $billDetails=$this->ReportModel->getSalesReportForDeliveryslipWithoutSalesman('bills',$fromDate,$toDate);
        }else{
            $billDetails=$this->ReportModel->getSalesReportForDeliveryslip('bills',$retailer,$salesman,$fromDate,$toDate);
        }
        
        $no=0;
        $rowCount = 7;
        
        $file="Deliveryslip_Sale_Report.xlsx";
        $newFileName= $file;

        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);

        foreach (range('A','J') as $col) {
           $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }

        $spreadsheet->getActiveSheet()->SetCellValue('A1', 'S. No.');
        $spreadsheet->getActiveSheet()->SetCellValue('B1', 'Division');
        $spreadsheet->getActiveSheet()->SetCellValue('C1', 'Bill Date');
        $spreadsheet->getActiveSheet()->SetCellValue('D1', 'Bill Number');
        $spreadsheet->getActiveSheet()->SetCellValue('E1', 'Salesman Code');
        $spreadsheet->getActiveSheet()->SetCellValue('F1', 'Salesman');
        $spreadsheet->getActiveSheet()->SetCellValue('G1', 'Route Code');
        $spreadsheet->getActiveSheet()->SetCellValue('H1', 'Route Name');
        $spreadsheet->getActiveSheet()->SetCellValue('I1', 'Retailer Code');
        $spreadsheet->getActiveSheet()->SetCellValue('J1', 'Retailer Name');
        $spreadsheet->getActiveSheet()->SetCellValue('K1', 'Product Code');
        $spreadsheet->getActiveSheet()->SetCellValue('L1', 'Product Name');
        $spreadsheet->getActiveSheet()->SetCellValue('M1', 'MRP');
        $spreadsheet->getActiveSheet()->SetCellValue('N1', 'Selling Rate');
        $spreadsheet->getActiveSheet()->SetCellValue('O1', 'Billed Quantity');
        $spreadsheet->getActiveSheet()->SetCellValue('P1', 'Billed Amount');
        $spreadsheet->getActiveSheet()->SetCellValue('Q1', 'SR Quantity');
        $spreadsheet->getActiveSheet()->SetCellValue('R1', 'SR Amount');
        $spreadsheet->getActiveSheet()->SetCellValue('S1', 'Net Quantity');
        $spreadsheet->getActiveSheet()->SetCellValue('T1', 'Net Amount');

        $no=0;
        $num=1;
        $rowCount = 2;
        foreach ($billDetails as $element) {
            $sellingPrice="";
            $date=date('d-M-Y',strtotime($element['billDate']));

            if((($element['netAmount'] == 0) || ($element['qty'] ==0))){
                $sellingPrice=0;
            }else{
                $sellingPrice=round($element['netAmount']/$element['qty'],2);
            }
   
            $spreadsheet->getActiveSheet()->SetCellValue('A' . $rowCount, $num);
            $spreadsheet->getActiveSheet()->SetCellValue('B' . $rowCount, $element['billCompany']);
            $spreadsheet->getActiveSheet()->SetCellValue('C' . $rowCount, $date);
            $spreadsheet->getActiveSheet()->SetCellValue('D' . $rowCount, $element['billNo']);
            $spreadsheet->getActiveSheet()->SetCellValue('E' . $rowCount, $element['billSalesmanCode']);
            $spreadsheet->getActiveSheet()->SetCellValue('F' . $rowCount, $element['billSalesman']);
            $spreadsheet->getActiveSheet()->SetCellValue('G' . $rowCount, $element['billRouteCode']);
            $spreadsheet->getActiveSheet()->SetCellValue('H' . $rowCount, $element['billRouteName']);
            $spreadsheet->getActiveSheet()->SetCellValue('I' . $rowCount, $element['billRetailerCode']);
            $spreadsheet->getActiveSheet()->SetCellValue('J' . $rowCount, $element['billRetailerName']);
            $spreadsheet->getActiveSheet()->SetCellValue('K' . $rowCount, $element['billProductCode']);
            $spreadsheet->getActiveSheet()->SetCellValue('L' . $rowCount, $element['billProductName']);
            $spreadsheet->getActiveSheet()->SetCellValue('M' . $rowCount, $element['mrp']);
            $spreadsheet->getActiveSheet()->SetCellValue('N' . $rowCount, $sellingPrice);
            $spreadsheet->getActiveSheet()->SetCellValue('O' . $rowCount, $element['qty']);
            $spreadsheet->getActiveSheet()->SetCellValue('P' . $rowCount, $element['netAmount']);
            $spreadsheet->getActiveSheet()->SetCellValue('Q' . $rowCount, $element['gkReturnQty']);
            $spreadsheet->getActiveSheet()->SetCellValue('R' . $rowCount, $element['fsReturnAmt']);
            $spreadsheet->getActiveSheet()->SetCellValue('S' . $rowCount, ($element['qty']-$element['gkReturnQty']));
            $spreadsheet->getActiveSheet()->SetCellValue('T' . $rowCount, ($element['netAmount']-$element['fsReturnAmt']));

            $rowCount++;
            $no++;
            $num++;
        }
       
        $writer = new Xlsx($spreadsheet);
        $fileName=$file;

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }

    //Billwise Collection Report Excel
    public function billwiseReport($billDetails){
        if(empty($billDetails)){
            echo "empty data";
        }else{
            $file="Billwise_Collection_Report.xlsx";
            $newFileName= $file;

            $spreadsheet = new Spreadsheet();
            $spreadsheet->setActiveSheetIndex(0);

            foreach (range('A','J') as $col) {
            $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            }

            $spreadsheet->getActiveSheet()->SetCellValue('A1', 'S. No.');
            $spreadsheet->getActiveSheet()->SetCellValue('B1', 'Division');
            $spreadsheet->getActiveSheet()->SetCellValue('C1', 'Bill Date');
            $spreadsheet->getActiveSheet()->SetCellValue('D1', 'Bill Number');
            $spreadsheet->getActiveSheet()->SetCellValue('E1', 'Retailer Name');
            $spreadsheet->getActiveSheet()->SetCellValue('F1', 'Bill Amount');
            $spreadsheet->getActiveSheet()->SetCellValue('G1', 'Salesman Name');
            $spreadsheet->getActiveSheet()->SetCellValue('H1', 'Route Name');
            $spreadsheet->getActiveSheet()->SetCellValue('I1', 'Bill Payment Date');
            $spreadsheet->getActiveSheet()->SetCellValue('J1', 'Paid Amount');
            $spreadsheet->getActiveSheet()->SetCellValue('K1', 'Payment Mode');
            $spreadsheet->getActiveSheet()->SetCellValue('L1', 'Remarks');

            $spreadsheet->getActiveSheet()->SetCellValue('M1', 'Employee Code');
            $spreadsheet->getActiveSheet()->SetCellValue('N1', 'Employee');
            
            $spreadsheet->getActiveSheet()->SetCellValue('O1', 'Designation');
            $spreadsheet->getActiveSheet()->SetCellValue('P1', 'Allocation');

            $no=0;
            $num=1;
            $rowCount = 2;
            foreach ($billDetails as $element) {
                $remark="";
                if($element['paymentMode']=="Cheque"){
                    if($element['chequeNo'] !="" && $element['chequeBank'] !=""){
                        $remark="Cheque No. : ".$element['chequeNo'].' Cheque Bank : '.$element['chequeBank'];
                    }
                }else{
                    if($element['neftNo'] !=""){
                        $remark="NEFT No. : ".$element['neftNo'];
                    }
                }

                $billdate=date('d-M-Y',strtotime($element['bdate']));
                $paymentdate=date('d-M-Y',strtotime($element['date']));

                $spreadsheet->getActiveSheet()->SetCellValue('A' . $rowCount, $num);
                $spreadsheet->getActiveSheet()->SetCellValue('B' . $rowCount, $element['compName']);
                $spreadsheet->getActiveSheet()->SetCellValue('C' . $rowCount, $billdate);
                $spreadsheet->getActiveSheet()->SetCellValue('D' . $rowCount, $element['billNo']);
                $spreadsheet->getActiveSheet()->SetCellValue('E' . $rowCount, $element['rtname']);
                $spreadsheet->getActiveSheet()->SetCellValue('F' . $rowCount, $element['billAmount']);
                $spreadsheet->getActiveSheet()->SetCellValue('G' . $rowCount, $element['salesman']);
                $spreadsheet->getActiveSheet()->SetCellValue('H' . $rowCount, $element['routeName']);
                $spreadsheet->getActiveSheet()->SetCellValue('I' . $rowCount, $paymentdate);
                $spreadsheet->getActiveSheet()->SetCellValue('J' . $rowCount, $element['paidAmount']);
                $spreadsheet->getActiveSheet()->SetCellValue('K' . $rowCount, $element['paymentMode']);
                $spreadsheet->getActiveSheet()->SetCellValue('L' . $rowCount, $remark);

                $spreadsheet->getActiveSheet()->SetCellValue('M' . $rowCount, $element['empCode']);
                $spreadsheet->getActiveSheet()->SetCellValue('N' . $rowCount, $element['empName']);

                $spreadsheet->getActiveSheet()->SetCellValue('O' . $rowCount, $element['empDes']);
                $spreadsheet->getActiveSheet()->SetCellValue('P' . $rowCount, $element['alCode']);

                $rowCount++;
                $no++;
                $num++;
            }
        
            $writer = new Xlsx($spreadsheet);
            $fileName=$file;
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
            $writer->save('php://output');
        }
    }

    //Datewise Collection Report Excel
    public function datewiseReport($billDetails){
        if(empty($billDetails)){
            echo "empty data";
        }else{
            $file="Datewise_Collection_Report.xlsx";
            $newFileName= $file;

            $spreadsheet = new Spreadsheet();
            $spreadsheet->setActiveSheetIndex(0);

            foreach (range('A','J') as $col) {
            $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            }

            $spreadsheet->getActiveSheet()->SetCellValue('A1', 'S. No.');
            $spreadsheet->getActiveSheet()->SetCellValue('B1', 'Division');
            $spreadsheet->getActiveSheet()->SetCellValue('C1', 'Bill Payment Date');
            $spreadsheet->getActiveSheet()->SetCellValue('D1', 'Bill Number');
            $spreadsheet->getActiveSheet()->SetCellValue('E1', 'Retailer Name');
            $spreadsheet->getActiveSheet()->SetCellValue('F1', 'Bill Amount');
            $spreadsheet->getActiveSheet()->SetCellValue('G1', 'Salesman Name');
            $spreadsheet->getActiveSheet()->SetCellValue('H1', 'Route Name');
            $spreadsheet->getActiveSheet()->SetCellValue('I1', 'Paid Amount');
            $spreadsheet->getActiveSheet()->SetCellValue('J1', 'Payment Mode');
            $spreadsheet->getActiveSheet()->SetCellValue('K1', 'Remarks');

            $no=0;
            $num=1;
            $rowCount = 2;
            foreach ($billDetails as $element) {
                $remark="";
                if($element['paymentMode']=="Cheque"){
                    if($element['chequeNo'] !="" && $element['chequeBank'] !=""){
                        $remark="Cheque No. : ".$element['chequeNo'].' Cheque Bank : '.$element['chequeBank'];
                    }
                }else{
                    if($element['neftNo'] !=""){
                        $remark="NEFT No. : ".$element['neftNo'];
                    }
                }

                $date=date('d-M-Y',strtotime($element['date']));
                $spreadsheet->getActiveSheet()->SetCellValue('A' . $rowCount, $num);
                $spreadsheet->getActiveSheet()->SetCellValue('B' . $rowCount, $date);
                $spreadsheet->getActiveSheet()->SetCellValue('C' . $rowCount, $element['billNo']);
                $spreadsheet->getActiveSheet()->SetCellValue('D' . $rowCount, $element['compName']);
                $spreadsheet->getActiveSheet()->SetCellValue('E' . $rowCount, $element['rtname']);
                $spreadsheet->getActiveSheet()->SetCellValue('F' . $rowCount, $element['billAmount']);
                $spreadsheet->getActiveSheet()->SetCellValue('G' . $rowCount, $element['salesman']);
                $spreadsheet->getActiveSheet()->SetCellValue('H' . $rowCount, $element['routeName']);
                $spreadsheet->getActiveSheet()->SetCellValue('I' . $rowCount, $element['paidAmount']);
                $spreadsheet->getActiveSheet()->SetCellValue('J' . $rowCount, $element['paymentMode']);
                $spreadsheet->getActiveSheet()->SetCellValue('K' . $rowCount, $remark);

                $rowCount++;
                $no++;
                $num++;
            }
        
            $writer = new Xlsx($spreadsheet);
            $fileName=$file;
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
            $writer->save('php://output');
        }
    }

    //Billwise Retailer Collection Excel 
    public function billwiseRetailerCollectionReport($billDetails){
        if(empty($billDetails)){
            echo "empty data";
        }else{
            $file="Billwise_Retailer_Collection_Report.xlsx";
            $newFileName= $file;

            $spreadsheet = new Spreadsheet();
            $spreadsheet->setActiveSheetIndex(0);

            foreach (range('A','J') as $col) {
                $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            }

            $spreadsheet->getActiveSheet()->SetCellValue('A1', 'S. No.');
            $spreadsheet->getActiveSheet()->SetCellValue('B1', 'Bill Date');
            $spreadsheet->getActiveSheet()->SetCellValue('C1', 'Bill Number');
            $spreadsheet->getActiveSheet()->SetCellValue('D1', 'Company');
            $spreadsheet->getActiveSheet()->SetCellValue('E1', 'Retailer Name');
            $spreadsheet->getActiveSheet()->SetCellValue('F1', 'Route Name');
            $spreadsheet->getActiveSheet()->SetCellValue('G1', 'Salesman Name');
            $spreadsheet->getActiveSheet()->SetCellValue('H1', 'Bill Amount');
            $spreadsheet->getActiveSheet()->SetCellValue('I1', 'Pending Amount');
            $spreadsheet->getActiveSheet()->SetCellValue('J1', 'Cash/Cheque/NEFT');
            $spreadsheet->getActiveSheet()->SetCellValue('K1', 'SR/FSR');
            $spreadsheet->getActiveSheet()->SetCellValue('L1', 'CD');
            $spreadsheet->getActiveSheet()->SetCellValue('M1', 'Debit');
            $spreadsheet->getActiveSheet()->SetCellValue('N1', 'Office Adjustment');
            $spreadsheet->getActiveSheet()->SetCellValue('O1', 'Other Adjustment');

            $no=0;
            $num=1;
            $rowCount = 2;
            foreach ($billDetails as $element) {
                $cd=$element['cd'];
                $sr=$element['SRAmt'];
                $ofcAdj=$element['officeAdjustmentBillAmount'];
                $otherAdj=$element['otherAdjustment'];
                $debit=$element['debit'];

                $date=date('d-M-Y',strtotime($element['date']));
                $spreadsheet->getActiveSheet()->SetCellValue('A' . $rowCount, $num);
                $spreadsheet->getActiveSheet()->SetCellValue('B' . $rowCount, $date);
                $spreadsheet->getActiveSheet()->SetCellValue('C' . $rowCount, $element['billNo']);
                $spreadsheet->getActiveSheet()->SetCellValue('D' . $rowCount, $element['compName']);
                $spreadsheet->getActiveSheet()->SetCellValue('E' . $rowCount, $element['retailerName']);
                $spreadsheet->getActiveSheet()->SetCellValue('F' . $rowCount, $element['routeName']);
                $spreadsheet->getActiveSheet()->SetCellValue('G' . $rowCount, $element['salesman']);
                $spreadsheet->getActiveSheet()->SetCellValue('H' . $rowCount, $element['netAmount']);
                $spreadsheet->getActiveSheet()->SetCellValue('I' . $rowCount, $element['pendingAmt']);
                $spreadsheet->getActiveSheet()->SetCellValue('J' . $rowCount, $element['receivedAmt']);
                $spreadsheet->getActiveSheet()->SetCellValue('K' . $rowCount, $sr);
                $spreadsheet->getActiveSheet()->SetCellValue('L' . $rowCount, $cd);
                $spreadsheet->getActiveSheet()->SetCellValue('M' . $rowCount, $debit);
                $spreadsheet->getActiveSheet()->SetCellValue('N' . $rowCount, $ofcAdj);
                $spreadsheet->getActiveSheet()->SetCellValue('O' . $rowCount, $debit);

                $rowCount++;
                $no++;
                $num++;
            }
        
            $writer = new Xlsx($spreadsheet);
            $fileName=$file;
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
            $writer->save('php://output');
        }
    }

    public function stockMovementReport(){
        $data['products']=$this->ReportModel->getdata('products');  
        $this->load->view('reports/stockMovementReportView',$data);
    }

    //Stock Movement Report Excel 
    public function deliveryslipProductReport(){
        $fromDate=$this->input->post('fromDate');
        $toDate=$this->input->post('toDate');
        $productDetails=$this->ReportModel->getProductHistory('deliveryslip_pending_for_billing',$fromDate,$toDate);  

        $file="Stock_Movement_Report.xlsx";
        $newFileName= $file;

        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);

        foreach (range('A','J') as $col) {
            $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }

        $spreadsheet->getActiveSheet()->SetCellValue('A1', 'S. No.');
        $spreadsheet->getActiveSheet()->SetCellValue('B1', 'Date');
        $spreadsheet->getActiveSheet()->SetCellValue('C1', 'Operation Status');
        $spreadsheet->getActiveSheet()->SetCellValue('D1', 'Product Code');
        $spreadsheet->getActiveSheet()->SetCellValue('E1', 'Product Name');
        $spreadsheet->getActiveSheet()->SetCellValue('F1', 'MRP');
        $spreadsheet->getActiveSheet()->SetCellValue('G1', 'Bill No');
        $spreadsheet->getActiveSheet()->SetCellValue('H1', 'Retailer Code');
        $spreadsheet->getActiveSheet()->SetCellValue('I1', 'Retailer Name');
        $spreadsheet->getActiveSheet()->SetCellValue('J1', 'Invoice Quantity');
        $spreadsheet->getActiveSheet()->SetCellValue('K1', 'Invoice Unit');
        $spreadsheet->getActiveSheet()->SetCellValue('L1', 'Total Quantity In Pcs');
        $spreadsheet->getActiveSheet()->SetCellValue('M1', 'Emplyee');

        $no=0;
        $num=1;
        $rowCount = 2;


        foreach ($productDetails as $element) {
            $operationStatus="";
            $invoiceQty="";
            $qty="";
            $retailerName="";

            $prodDetails=$this->ReportModel->load('products',$element['productId']);     
            if($element['operationStatus']=="reduce"){
                if($element['billingId']==0){
                    $operationStatus="Reduced through Product Master";
                    $invoiceQty='-'.$element['quantity'];
                    $qty=($element['quantityInPcs']);
                }else{
                    $operationStatus="Billed";
                    $invoiceQty='-'.$element['quantity'];
                    $qty=($element['quantityInPcs']);
                }
            }else if($element['operationStatus']=="add"){
                if($element['billingId']==0){
                    $operationStatus="Increased through Product Master";
                    $invoiceQty=$element['quantity'];
                    $qty=$element['quantityInPcs'];
                }else{
                    $operationStatus="SR";
                    $invoiceQty=$element['quantity'];
                    $qty=$element['quantityInPcs'];
                }
            }else if($element['operationStatus']=="replace"){
                if($element['billingId']==0){
                    if($element['quantityInPcs'] > 0){
                        $operationStatus="Increased through Product Master";
                        $qty=$element['quantityInPcs'];

                        if($element['quantityUnit']=="case" || $element['quantityUnit']=="Case"){
                            $invoiceQty=round($element['quantityInPcs']/($prodDetails[0]['unitOne'])/($prodDetails[0]['unitTwo']),2);
                        }else if($element['quantityUnit']=="box" || $element['quantityUnit']=="Box"){
                            $invoiceQty=round($element['quantityInPcs']/($prodDetails[0]['unitOne'])/($prodDetails[0]['unitTwo']),2);
                        }else if($element['quantityUnit']=="pcs" || $element['quantityUnit']=="Pcs"){
                            $invoiceQty='-'.$element['quantity'];
                        }
                    }else{
                        $operationStatus="Reduced through Product Master";
                        $qty=$element['quantityInPcs'];

                        if($element['quantityUnit']=="case" || $element['quantityUnit']=="Case"){
                            $invoiceQty=round($element['quantityInPcs']/($prodDetails[0]['unitOne'])/($prodDetails[0]['unitTwo']),2);
                        }else if($element['quantityUnit']=="box" || $element['quantityUnit']=="Box"){
                            $invoiceQty=round($element['quantityInPcs']/($prodDetails[0]['unitOne'])/($prodDetails[0]['unitTwo']),2);
                        }else if($element['quantityUnit']=="pcs" || $element['quantityUnit']=="Pcs"){
                            $invoiceQty='-'.$element['quantity'];
                        }
                    }
                }
            }else if($element['operationStatus']=="operator operation"){
                $operationStatus="Operator Entry";
                $invoiceQty=$element['quantity'];
                $qty=$element['quantityInPcs'];
           
            }

            $spreadsheet->getActiveSheet()->SetCellValue('A' . $rowCount, $num);
            $spreadsheet->getActiveSheet()->SetCellValue('B' . $rowCount, date('d-M-Y',strtotime($element['createdAt'])));
            $spreadsheet->getActiveSheet()->SetCellValue('C' . $rowCount, $operationStatus);
            $spreadsheet->getActiveSheet()->SetCellValue('D' . $rowCount, $element['productCode']);
            $spreadsheet->getActiveSheet()->SetCellValue('E' . $rowCount, $element['productName']);
            $spreadsheet->getActiveSheet()->SetCellValue('F' . $rowCount, $prodDetails[0]['mrp']);
            $spreadsheet->getActiveSheet()->SetCellValue('G' . $rowCount, $element['b_billNo']);
            $spreadsheet->getActiveSheet()->SetCellValue('H' . $rowCount, $element['b_retailerCode']);
            $spreadsheet->getActiveSheet()->SetCellValue('I' . $rowCount, $element['b_retailerName']);
            $spreadsheet->getActiveSheet()->SetCellValue('J' . $rowCount, $invoiceQty);
            $spreadsheet->getActiveSheet()->SetCellValue('K' . $rowCount, $element['quantityUnit']);
            $spreadsheet->getActiveSheet()->SetCellValue('L' . $rowCount, $qty);
            $spreadsheet->getActiveSheet()->SetCellValue('M' . $rowCount, $element['ename']);

            $rowCount++;
            $no++;
            $num++;
        }
    
        $writer = new Xlsx($spreadsheet);
        $fileName=$file;
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
        
    }
    
    public function frequentsrRetailersReportView(){
        $data['retailerSR']=$this->ReportModel->frequentsrRetailers('allocation_sr_details');
        $this->load->view('reports/frequentSRRetailersReportView',$data);
    }
	
	public function showDetails(){
     $first_date = trim($this->input->post('value'));
     $last_date   = date('Y-m-d',strtotime("now")); 
     $data['retailerSRs'] = $this->ReportModel->frequentsrRetailerswithDate('allocation_sr_details',$first_date,$last_date); 
     //print_r($data['retailerSRs']); exit();
     $this->load->view('reports/frequentSRRetailerFilterView',$data);
    }

    public function frequentsrSalesmanReportView(){
        $data['salesmanSR']=$this->ReportModel->frequentsrSalesman('allocation_sr_details');
        $this->load->view('reports/frequentSRSalesmanReportView',$data);
    }
    
    public function multipleVisitorRetailerReportView(){
        $data['mutliVisitRetailer']=$this->ReportModel->multiplevisitorRetailer();
        
        print_r($data['mutliVisitRetailer']); exit();
        $this->load->view('reports/multipleVisitorRetailerReportView',$data);
    }
    
    public function OverdueBillsReports(){   
        $fromDate=$this->input->post('fromDate');
        $toDate=$this->input->post('toDate');
        $data['fetchTotalRecords']=$this->ReportModel->fetchBillRecord('bills',$fromDate,$toDate);
        $this->load->view('reports/OverdueBillsView',$data);
    }

    public function OverdueBillsReport(){
        $fromDate=$this->input->post('fromDate');
        $toDate=$this->input->post('toDate');
        $billDetails=$this->ReportModel->fetchBillRecord('bills',$fromDate,$toDate);
        //print_r($billDetails); exit();
        if(empty($billDetails)){
            echo "empty data";
        }else{
            $file="Overdue_Bills_Report.xlsx";
            $newFileName= $file;

            $spreadsheet = new Spreadsheet();
            $spreadsheet->setActiveSheetIndex(0);

            foreach (range('A','J') as $col) {
                $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            }

            $spreadsheet->getActiveSheet()->SetCellValue('A1', 'S. No.');
            $spreadsheet->getActiveSheet()->SetCellValue('B1', 'Bill Date');
            $spreadsheet->getActiveSheet()->SetCellValue('C1', 'Bill Number');
            $spreadsheet->getActiveSheet()->SetCellValue('D1', 'Company');
            $spreadsheet->getActiveSheet()->SetCellValue('E1', 'Retailer Name');
            $spreadsheet->getActiveSheet()->SetCellValue('F1', 'Route Name');
            $spreadsheet->getActiveSheet()->SetCellValue('G1', 'Salesman Name');
            $spreadsheet->getActiveSheet()->SetCellValue('H1', 'Bill Amount');
            $spreadsheet->getActiveSheet()->SetCellValue('I1', 'Pending Amount');
            $spreadsheet->getActiveSheet()->SetCellValue('J1', 'Cash/Cheque/NEFT');
            

            $no=0;
            $num=1;
            $rowCount = 2;
            foreach ($billDetails as $element) {
              // print_r($element['date']); exit();
                $date=date('d-M-Y',strtotime($element['date']));
                $spreadsheet->getActiveSheet()->SetCellValue('A' . $rowCount, $num);
                $spreadsheet->getActiveSheet()->SetCellValue('B' . $rowCount, $date);
                $spreadsheet->getActiveSheet()->SetCellValue('C' . $rowCount, $element['billNo']);
                $spreadsheet->getActiveSheet()->SetCellValue('D' . $rowCount, $element['compName']);
                $spreadsheet->getActiveSheet()->SetCellValue('E' . $rowCount, $element['retailerName']);
                $spreadsheet->getActiveSheet()->SetCellValue('F' . $rowCount, $element['routeName']);
                $spreadsheet->getActiveSheet()->SetCellValue('G' . $rowCount, $element['salesman']);
                $spreadsheet->getActiveSheet()->SetCellValue('H' . $rowCount, $element['netAmount']);
                $spreadsheet->getActiveSheet()->SetCellValue('I' . $rowCount, $element['pendingAmt']);
                $spreadsheet->getActiveSheet()->SetCellValue('J' . $rowCount, $element['receivedAmt']);
                
                $rowCount++;
                $no++;
                $num++;
            }
        
            $writer = new Xlsx($spreadsheet);
            $fileName=$file;
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
            $writer->save('php://output');
        }
    }

    public function RetailerAccountStatementReport()
    {
        $data['retailer']=$this->ReportModel->getdata('retailer_kia');
        $this->load->view('reports/retailerAccountStatementView',$data);
    }

   public function retailorWiseBillReport(){
        $retailer=trim($this->input->post('retailer'));
        $fromDate=trim($this->input->post('fromDate'));
        $toDate=trim($this->input->post('toDate'));
        $data['billInfo']=$this->ReportModel->getRetailerDetailsUsingDates('bills',$retailer,$fromDate,$toDate); 
        $this->load->view('reports/retailerAccountStatementdetails',$data);
   }

   public function showBillDetails(){
    $bid=trim($this->input->post('id')); 
    $billsdetails = $this->ReportModel->loadBillsDetails('billpayments', $bid);
   // print_r($billsdetails); exit();
    ?>

   <table class="table table-bordered js-exportable dataTable cust-tbl" data-page-length='100'>
        <thead>
            <tr>
                <th>Sr.No</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Payment Mode</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Sr.No</th>
                <th>Date</th> 
                <th>Amount</th> 
                <th>Payment Mode</th>
            </tr>
        </tfoot>
                                
        <tbody>
            <?php
            if(!empty($billsdetails)){
            $no=0;
            foreach ($billsdetails as $data){
            $no++; 
            $bill_date=date('d-M-Y',strtotime($data['date']));
            ?>
            <tr>
                <td><?php echo $no; ?></td>
                <td><?php echo $bill_date; ?></td>
                <td><?php echo $data['paidAmount']; ?></td>
                <td><?php echo $data['paymentMode'];?></td>
            </tr>  

            <?php
             }
             }else{ ?>
            <tr><td>No data available</td></tr>
            <?php  } ?>
        </tbody>
        </table>

    <?php 
   }
}