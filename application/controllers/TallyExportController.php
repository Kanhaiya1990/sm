<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
class TallyExportController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('ReportModel');
        $this->load->library('session');
        $this->load->library('pagination');
        date_default_timezone_set('Asia/Kolkata');
        ini_set('memory_limit', '-1');

        if(isset($this->session->userdata['codeKeyData'])) {
          $this->projectSessionName= $this->session->userdata['codeKeyData']['codeKeyValue'];
        }else{
          $this->load->view('LoginView');
        }
    }

    public function index(){
        $data['company']=$this->ReportModel->getdata('company');
        $this->load->view('tallyExportView',$data);
    }

    //for download master files
    public function downloadMaster(){
          $companyData=$this->ReportModel->getdata('company');
          $retailers=$this->ReportModel->getUniqueData('bills');
          $deliveryslipRetailers=$this->ReportModel->getUniqueDataForDeliveryslip('bills');

          $allcompany=$this->input->post('allcompName');
          $company=$this->input->post('compName');
          if($allcompany !=""){
            $ar=array('id'=>count($companyData)+1,'name'=>'Deliveryslip','reportSW'=>'');
            array_push($companyData,$ar);
            
            if(!empty($companyData)){
                $this->masterExportWithDeliverySlip($companyData,$retailers,$deliveryslipRetailers);
            }
          }else{
            
            if(!empty($company)){
                $this->masterExport($company,$retailers,$deliveryslipRetailers);
            }
          }
      }

    //for download invoice files
    public function downloadInvoice(){
        $companyData=$this->ReportModel->getdata('company');
        $distributorCode="";
        $officeDetails=$this->ReportModel->getdata('office_details');
        if(!empty($officeDetails)){
          $distributorCode=$officeDetails[0]['distributorCode'];
        }
        
        $allcompany=$this->input->post('invallcompName');
        $company=$this->input->post('compName');

        $fromDate=$this->input->post('invfromDate');
        $toDate=$this->input->post('invtoDate');

        $dynamicmatter="";
        if($allcompany !=""){
          $ar=array('id'=>count($companyData)+1,'name'=>'Deliveryslip','reportSW'=>'');
          array_push($companyData,$ar);
          
          if(!empty($companyData)){
              //for Invoice - In Tally Export
              if(!empty($companyData)){
                  foreach($companyData as $item){
                    if($item['name']=="Deliveryslip"){
                      $getData=$this->ReportModel->getCompanyDataForInvoiceWithDeliveryslip('bills',$item['name'],$fromDate,$toDate);
                      if(!empty($getData)){
                          $dynamicmatter .=$this->invoiceExport($getData,$distributorCode);
                      }
                    }else{
                      $getData=$this->ReportModel->getCompanyDataForInvoice('bills',$item['name'],$fromDate,$toDate);
                      if(!empty($getData)){
                          $dynamicmatter .=$this->invoiceExport($getData,$distributorCode);
                      }
                    }
                  }
              }
          }
        }else{
          if(!empty($company)){
              foreach($company as $item){
                if($item=="Deliveryslip"){
                  $getData=$this->ReportModel->getCompanyDataForInvoiceWithDeliveryslip('bills',$item,$fromDate,$toDate);
                  if(!empty($getData)){
                      $dynamicmatter .=$this->invoiceExport($getData,$distributorCode);
                  }
                }else{
                  $getData=$this->ReportModel->getCompanyDataForInvoice('bills',$item,$fromDate,$toDate);
                  if(!empty($getData)){
                      $dynamicmatter .=$this->invoiceExport($getData,$distributorCode);
                  }
                }
              }
          }
        }

        $matter='<?xml version="1.0"?>
        <ENVELOPE>
          <HEADER>
            <TALLYREQUEST>Import Data</TALLYREQUEST>
          </HEADER>
          <BODY>
            <IMPORTDATA>
              <REQUESTDESC>
                <REPORTNAME>All Masters</REPORTNAME>
                <STATICVARIABLES />
                <SVCURRENTCOMPANY></SVCURRENTCOMPANY>
              </REQUESTDESC>
              <REQUESTDATA>'.$dynamicmatter.'</REQUESTDATA>
              </IMPORTDATA>
            </BODY>
          </ENVELOPE>';
        
        $name=$distributorCode."-Invoices-".date('d-M-Y').'.xml';

        header('Content-disposition: attachment; filename='.$name);
        header ("Content-Type:text/xml"); 
        echo  $matter;
    }

    public function submitTally(){
        $company=$this->input->post('compName');
        $fromDate=$this->input->post('fromDate');
        $toDate=$this->input->post('toDate');

        $all=$this->input->post('all');
        
        $cash_receipt=$this->input->post('cash_receipt');

        $cash_discount=$this->input->post('cash_discount');
        $neft_receipt=$this->input->post('neft_receipt');
        $sales_return=$this->input->post('sales_return');
        
        $cheque_receipt=$this->input->post('cheque_receipt');
        $cheque_discount=$this->input->post('cheque_discount');
        $cheque_bounce_penalty=$this->input->post('cheque_bounce_penalty');

        $office_adjustment=$this->input->post('office_adjustment');
        $other_adjustment=$this->input->post('other_adjustment');
        $debit_to_employee=$this->input->post('debit_to_employee');

        $retailers=$this->ReportModel->getdata('retailer');
        $deliveryslipRetailers=$this->ReportModel->getdata('retailer_kia');

        // $officeDetails=$this->ReportModel->getdata('office_details');
        // $distributorCode=$officeDetails[0]['distributorCode'];

        $distributorCode="";
        $officeDetails=$this->ReportModel->getdata('office_details');
        if(!empty($officeDetails)){
          $distributorCode=$officeDetails[0]['distributorCode'];
        }

        $checkData=$this->ReportModel->checkDataWithDates('billpayments',$fromDate,$toDate);
        if(!empty($checkData)){
          echo "Please close open allocations first";
          exit;
        }

        $allcompany=$this->input->post('allcompData');
        $dynamicmatter='';
        if($allcompany !=""){
          $companyData=$this->ReportModel->getdata('company');
          $ar=array('id'=>count($companyData)+1,'name'=>'Deliveryslip','reportSW'=>'');
          array_push($companyData,$ar);
            
         

          //for Cash Receipt and Cash transaction edit - In Tally Export
          if($cash_receipt !="" || $all !=""){
              if(!empty($companyData)){
                  foreach($companyData as $item){
                    if($item['name']=="Deliveryslip"){
                      $getData=$this->ReportModel->getCompanyDataByTypeForDeliveryslip('bills',$item['name'],$fromDate,$toDate,'Cash');
                      if(!empty($getData)){
                          $dynamicmatter .=$this->cashExport($getData,$distributorCode);
                      }
                    }else{
                      $getData=$this->ReportModel->getCompanyDataByType('bills',$item['name'],$fromDate,$toDate,'Cash');
                      if(!empty($getData)){
                          $dynamicmatter .=$this->cashExport($getData,$distributorCode);
                      }
                    }
                  }
              }
          }

          //for Cash Discount and CD transaction edit - In Tally Export
          if($cash_discount !="" || $all !=""){
              if(!empty($companyData)){
                  foreach($companyData as $item){
                    if($item['name']=="Deliveryslip"){
                      $getData=$this->ReportModel->getCompanyDataByTypeForDeliveryslip('bills',$item['name'],$fromDate,$toDate,'CD');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->cashDiscountExport($getData,$distributorCode);
                      }
                    }else{
                      $getData=$this->ReportModel->getCompanyDataByType('bills',$item['name'],$fromDate,$toDate,'CD');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->cashDiscountExport($getData,$distributorCode);
                      }
                    }
                  }
              }
          }
          
          //for NEFT and NEFT transaction edit - In Tally Export
          if($neft_receipt !="" || $all !=""){
              if(!empty($companyData)){
                  foreach($companyData as $item){
                    if($item['name']=="Deliveryslip"){
                      $getData=$this->ReportModel->getCompanyDataByTypeNEFTForDeliveryslip('bills',$item['name'],$fromDate,$toDate,'NEFT');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->neftReceiptExport($getData,$distributorCode);
                      }
                    }else{
                      $getData=$this->ReportModel->getCompanyDataByTypeNEFT('bills',$item['name'],$fromDate,$toDate,'NEFT');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->neftReceiptExport($getData,$distributorCode);
                      }
                    }
                  }
              }
          }

          //for SR and SR transaction edit - In Tally Export
          if($sales_return !="" || $all !=""){
              if(!empty($companyData)){
                  foreach($companyData as $item){
                    if($item['name']=="Deliveryslip"){
                      $getData=$this->ReportModel->getCompanyDataByTypeForDeliveryslip('bills',$item['name'],$fromDate,$toDate,'SR');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->saleReturnExport($getData,$distributorCode);
                      }
                    }else{
                      $getData=$this->ReportModel->getCompanyDataByType('bills',$item['name'],$fromDate,$toDate,'SR');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->saleReturnExport($getData,$distributorCode);
                      }
                    }
                  }
              }
          }

          //for FSR and FSR transaction edit - In Tally Export
          if($sales_return !="" || $all !=""){
              if(!empty($companyData)){
                  foreach($companyData as $item){
                    if($item['name']=="Deliveryslip"){
                      $getData=$this->ReportModel->getCompanyDataByTypeForFSRForDeliveryslip('bills',$item['name'],$fromDate,$toDate,'FSR');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->fullSaleReturnExport($getData,$distributorCode);
                      }
                    }else{
                      $getData=$this->ReportModel->getCompanyDataByTypeForFSR('bills',$item['name'],$fromDate,$toDate,'FSR');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->fullSaleReturnExport($getData,$distributorCode);
                      }
                    }
                  }
              }
          }

          //for Cheque Receipt - In Tally Export
          if($cheque_receipt !="" || $all !=""){
              if(!empty($companyData)){
                  foreach($companyData as $item){
                    if($item['name']=="Deliveryslip"){
                      $getData=$this->ReportModel->getCompanyDataByTypeChequeReceivedForDeliveryslip('bills',$item['name'],$fromDate,$toDate,'Cheque');
                      if(!empty($getData)){
                          $dynamicmatter .=$this->chequeReceiptExport($getData,$distributorCode);
                      }
                    }else{
                      $getData=$this->ReportModel->getCompanyDataByTypeChequeReceived('bills',$item['name'],$fromDate,$toDate,'Cheque');
                      if(!empty($getData)){
                          $dynamicmatter .=$this->chequeReceiptExport($getData,$distributorCode);
                      }
                    }
                  }
              }
          }

          //for Cheque Receipt transaction edit - In Tally Export
          if($cheque_receipt !="" || $all !=""){
            if(!empty($companyData)){
                foreach($companyData as $item){
                  if($item['name']=="Deliveryslip"){
                    $getData=$this->ReportModel->getCompanyDataByTypeChequeTransactionChangeForDeliveryslip('bills',$item['name'],$fromDate,$toDate,'Cheque');
                    if(!empty($getData)){
                        $dynamicmatter .=$this->chequeTransactionExport($getData,$distributorCode);
                    }
                  }else{
                    $getData=$this->ReportModel->getCompanyDataByTypeChequeTransactionChange('bills',$item['name'],$fromDate,$toDate,'Cheque');
                    if(!empty($getData)){
                        $dynamicmatter .=$this->chequeTransactionExport($getData,$distributorCode);
                    }
                  }
                }
            }
          }

          //for Cheque Bounce - In Tally Export
          if($cheque_discount !="" || $all !=""){
              if(!empty($companyData)){
                  foreach($companyData as $item){
                    if($item['name']=="Deliveryslip"){
                      $getData=$this->ReportModel->getCompanyDataByTypeChequeBouncedForDeliveryslip('bills',$item['name'],$fromDate,$toDate,'Cheque');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->chequeBounceExport($getData,$distributorCode);
                      }
                    }else{
                      $getData=$this->ReportModel->getCompanyDataByTypeChequeBounced('bills',$item['name'],$fromDate,$toDate,'Cheque');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->chequeBounceExport($getData,$distributorCode);
                      }
                    }
                  }
              }
          }

          //for Cheque Bounce Penalty - In Tally Export
          if($cheque_bounce_penalty !="" || $all !=""){
              if(!empty($companyData)){
                  foreach($companyData as $item){
                    if($item['name']=="Deliveryslip"){
                      $getData=$this->ReportModel->getCompanyDataByTypeBouncePenaltyForDeliveryslip('bills',$item['name'],$fromDate,$toDate,'Cheque');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->chequeBouncePenalty($getData,$distributorCode);
                      }
                    }else{
                      $getData=$this->ReportModel->getCompanyDataByTypeBouncePenalty('bills',$item['name'],$fromDate,$toDate,'Cheque');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->chequeBouncePenalty($getData,$distributorCode);
                      }
                    }
                  }
              }
          }

          //for Office Adjustment and Office Adjustment transaction edit - In Tally Export
          if($office_adjustment !="" || $all !=""){
              if(!empty($companyData)){
                  foreach($companyData as $item){
                    if($item['name']=="Deliveryslip"){
                      $getData=$this->ReportModel->getCompanyDataByTypeForDeliveryslip('bills',$item['name'],$fromDate,$toDate,'Office Adjustment');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->officeAdjustmentExport($getData,$distributorCode);
                      }
                    }else{
                      $getData=$this->ReportModel->getCompanyDataByType('bills',$item['name'],$fromDate,$toDate,'Office Adjustment');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->officeAdjustmentExport($getData,$distributorCode);
                      }
                    }
                  }
              }
          }

          //for Other Adjustment and Other Adjustment transaction edit - In Tally Export
          if($other_adjustment !="" || $all !=""){
              if(!empty($companyData)){
                  foreach($companyData as $item){
                    if($item['name']=="Deliveryslip"){
                      $getData=$this->ReportModel->getCompanyDataByTypeForDeliveryslip('bills',$item['name'],$fromDate,$toDate,'Other Adjustment');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->otherAdjustmentExport($getData,$distributorCode);
                      }
                    }else{
                      $getData=$this->ReportModel->getCompanyDataByType('bills',$item['name'],$fromDate,$toDate,'Other Adjustment');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->otherAdjustmentExport($getData,$distributorCode);
                      }
                    }
                  }
              }
          }

          //for Employee Debit and Employee Debit transaction edit - In Tally Export
          if($debit_to_employee !="" || $all !=""){
              if(!empty($companyData)){
                  foreach($companyData as $item){
                    if($item['name']=="Deliveryslip"){
                      $getData=$this->ReportModel->getCompanyDataByTypeForDeliveryslip('bills',$item['name'],$fromDate,$toDate,'Debit To Employee');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->debitToEmployeeExport($getData,$distributorCode);
                      }
                    }else{
                      $getData=$this->ReportModel->getCompanyDataByType('bills',$item['name'],$fromDate,$toDate,'Debit To Employee');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->debitToEmployeeExport($getData,$distributorCode);
                      }
                    }
                  }
              }
          }

        }else{ 

          //for Cash Receipt and Cash transaction edit - In Tally Export
          if($cash_receipt !="" || $all !=""){
              if(!empty($company)){
                  foreach($company as $item){
                    if($item=="deliveryslip"){
                      $getData=$this->ReportModel->getCompanyDataByTypeForDeliveryslip('bills',$item,$fromDate,$toDate,'Cash');
                      if(!empty($getData)){
                          $dynamicmatter .=$this->cashExport($getData,$distributorCode);
                      }
                    }else{
                      $getData=$this->ReportModel->getCompanyDataByType('bills',$item,$fromDate,$toDate,'Cash');
                      if(!empty($getData)){
                          $dynamicmatter .=$this->cashExport($getData,$distributorCode);
                      }
                    }
                  }
              }
          }

          //for Cash Discount and CD transaction edit - In Tally Export
          if($cash_discount !="" || $all !=""){
              if(!empty($company)){
                  foreach($company as $item){
                    if($item=="deliveryslip"){
                      $getData=$this->ReportModel->getCompanyDataByTypeForDeliveryslip('bills',$item,$fromDate,$toDate,'CD');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->cashDiscountExport($getData,$distributorCode);
                      }
                    }else{
                      $getData=$this->ReportModel->getCompanyDataByType('bills',$item,$fromDate,$toDate,'CD');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->cashDiscountExport($getData,$distributorCode);
                      }
                    }
                  }
              }
          }
          
          //for NEFT and NEFT transaction edit - In Tally Export
          if($neft_receipt !="" || $all !=""){
              if(!empty($company)){
                  foreach($company as $item){
                    if($item=="deliveryslip"){
                      $getData=$this->ReportModel->getCompanyDataByTypeNEFTForDeliveryslip('bills',$item,$fromDate,$toDate,'NEFT');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->neftReceiptExport($getData,$distributorCode);
                      }
                    }else{
                      $getData=$this->ReportModel->getCompanyDataByTypeNEFT('bills',$item,$fromDate,$toDate,'NEFT');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->neftReceiptExport($getData,$distributorCode);
                      }
                    }
                  }
              }
          }

          //for SR and SR transaction edit - In Tally Export
          if($sales_return !="" || $all !=""){
              if(!empty($company)){
                  foreach($company as $item){
                    if($item=="deliveryslip"){
                      $getData=$this->ReportModel->getCompanyDataByTypeForDeliveryslip('bills',$item,$fromDate,$toDate,'SR');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->saleReturnExport($getData,$distributorCode);
                      }
                    }else{
                      $getData=$this->ReportModel->getCompanyDataByType('bills',$item,$fromDate,$toDate,'SR');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->saleReturnExport($getData,$distributorCode);
                      }
                    }
                  }
              }
          }

          //for FSR and FSR transaction edit - In Tally Export
          if($sales_return !="" || $all !=""){
              if(!empty($company)){
                  foreach($company as $item){
                    if($item=="deliveryslip"){
                      $getData=$this->ReportModel->getCompanyDataByTypeForFSRForDeliveryslip('bills',$item,$fromDate,$toDate,'FSR');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->fullSaleReturnExport($getData,$distributorCode);
                      }
                    }else{
                      $getData=$this->ReportModel->getCompanyDataByTypeForFSR('bills',$item,$fromDate,$toDate,'FSR');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->fullSaleReturnExport($getData,$distributorCode);
                      }
                    }
                  }
              }
          }

          //for Cheque Receipt - In Tally Export
          if($cheque_receipt !="" || $all !=""){
              if(!empty($company)){
                  foreach($company as $item){
                    if($item=="deliveryslip"){
                      $getData=$this->ReportModel->getCompanyDataByTypeChequeReceivedForDeliveryslip('bills',$item,$fromDate,$toDate,'Cheque');
                      if(!empty($getData)){
                          $dynamicmatter .=$this->chequeReceiptExport($getData,$distributorCode);
                      }
                    }else{
                      $getData=$this->ReportModel->getCompanyDataByTypeChequeReceived('bills',$item,$fromDate,$toDate,'Cheque');
                      if(!empty($getData)){
                          $dynamicmatter .=$this->chequeReceiptExport($getData,$distributorCode);
                      }
                    }
                  }
              }
          }

          //for Cheque Receipt transaction edit - In Tally Export
          if($cheque_receipt !="" || $all !=""){
            if(!empty($company)){
                foreach($company as $item){
                  if($item=="deliveryslip"){
                    $getData=$this->ReportModel->getCompanyDataByTypeChequeTransactionChangeForDeliveryslip('bills',$item,$fromDate,$toDate,'Cheque');
                    if(!empty($getData)){
                        $dynamicmatter .=$this->chequeTransactionExport($getData,$distributorCode);
                    }
                  }else{
                    $getData=$this->ReportModel->getCompanyDataByTypeChequeTransactionChange('bills',$item,$fromDate,$toDate,'Cheque');
                    if(!empty($getData)){
                        $dynamicmatter .=$this->chequeTransactionExport($getData,$distributorCode);
                    }
                  }
                }
            }
          }

          //for Cheque Bounce - In Tally Export
          if($cheque_discount !="" || $all !=""){
              if(!empty($company)){
                  foreach($company as $item){
                    if($item=="deliveryslip"){
                      $getData=$this->ReportModel->getCompanyDataByTypeChequeBouncedForDeliveryslip('bills',$item,$fromDate,$toDate,'Cheque');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->chequeBounceExport($getData,$distributorCode);
                      }
                    }else{
                      $getData=$this->ReportModel->getCompanyDataByTypeChequeBounced('bills',$item,$fromDate,$toDate,'Cheque');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->chequeBounceExport($getData,$distributorCode);
                      }
                    }
                  }
              }
          }

          //for Cheque Bounce Penalty - In Tally Export
          if($cheque_bounce_penalty !="" || $all !=""){
              if(!empty($company)){
                  foreach($company as $item){
                    if($item=="deliveryslip"){
                      $getData=$this->ReportModel->getCompanyDataByTypeBouncePenaltyForDeliveryslip('bills',$item,$fromDate,$toDate,'Cheque');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->chequeBouncePenalty($getData,$distributorCode);
                      }
                    }else{
                      $getData=$this->ReportModel->getCompanyDataByTypeBouncePenalty('bills',$item,$fromDate,$toDate,'Cheque');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->chequeBouncePenalty($getData,$distributorCode);
                      }
                    }
                  }
              }
          }

          //for Office Adjustment and Office Adjustment transaction edit - In Tally Export
          if($office_adjustment !="" || $all !=""){
              if(!empty($company)){
                  foreach($company as $item){
                    if($item=="deliveryslip"){
                      $getData=$this->ReportModel->getCompanyDataByTypeForDeliveryslip('bills',$item,$fromDate,$toDate,'Office Adjustment');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->officeAdjustmentExport($getData,$distributorCode);
                      }
                    }else{
                      $getData=$this->ReportModel->getCompanyDataByType('bills',$item,$fromDate,$toDate,'Office Adjustment');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->officeAdjustmentExport($getData,$distributorCode);
                      }
                    }
                  }
              }
          }

          //for Other Adjustment and Other Adjustment transaction edit - In Tally Export
          if($other_adjustment !="" || $all !=""){
              if(!empty($company)){
                  foreach($company as $item){
                    if($item=="deliveryslip"){
                      $getData=$this->ReportModel->getCompanyDataByTypeForDeliveryslip('bills',$item,$fromDate,$toDate,'Other Adjustment');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->otherAdjustmentExport($getData,$distributorCode);
                      }
                    }else{
                      $getData=$this->ReportModel->getCompanyDataByType('bills',$item,$fromDate,$toDate,'Other Adjustment');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->otherAdjustmentExport($getData,$distributorCode);
                      }
                    }
                  }
              }
          }

          //for Employee Debit and Employee Debit transaction edit - In Tally Export
          if($debit_to_employee !="" || $all !=""){
              if(!empty($company)){
                  foreach($company as $item){
                    if($item=="deliveryslip"){
                      $getData=$this->ReportModel->getCompanyDataByTypeForDeliveryslip('bills',$item,$fromDate,$toDate,'Debit To Employee');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->debitToEmployeeExport($getData,$distributorCode);
                      }
                    }else{
                      $getData=$this->ReportModel->getCompanyDataByType('bills',$item,$fromDate,$toDate,'Debit To Employee');
                      if(!empty($getData)){
                          $dynamicmatter .= $this->debitToEmployeeExport($getData,$distributorCode);
                      }
                    }
                  }
              }
          }
        }

        $matter='<?xml version="1.0"?>
        <ENVELOPE>
          <HEADER>
            <TALLYREQUEST>Import Data</TALLYREQUEST>
          </HEADER>
          <BODY>
            <IMPORTDATA>
              <REQUESTDESC>
                <REPORTNAME>All Masters</REPORTNAME>
                <STATICVARIABLES />
                <SVCURRENTCOMPANY></SVCURRENTCOMPANY>
              </REQUESTDESC>
              <REQUESTDATA>'.$dynamicmatter.'</REQUESTDATA>
              </IMPORTDATA>
            </BODY>
          </ENVELOPE>';

        $name=$distributorCode.'-Bill-Transactions-'.$fromDate.'-to-'.$toDate.'.xml';
        header('Content-disposition: attachment; filename='.$name);
        header ("Content-Type:text/xml"); 
        echo  $matter;
        // echo $matter;
    }

   

    public function masterExport($company,$retailers,$deliveryslipRetailers){
        $distributorCode="";
        $officeDetails=$this->ReportModel->getdata('office_details');
        if(!empty($officeDetails)){
          $distributorCode=$officeDetails[0]['distributorCode'];
        }

      // echo $company;exit;
        $matter='<?xml version="1.0"?>
        <ENVELOPE>
          <HEADER>
            <TALLYREQUEST>Import Data</TALLYREQUEST>
          </HEADER>
          <BODY>
            <IMPORTDATA>
              <REQUESTDESC>
                <REPORTNAME>All Masters</REPORTNAME>
                <STATICVARIABLES/>
                <SVCURRENTCOMPANY></SVCURRENTCOMPANY>
              </REQUESTDESC>
              <REQUESTDATA>
                  <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <VOUCHERTYPE NAME="Credit Note" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>Credit Note</NAME>
                    </NAME.LIST>
                    <PARENT>Credit Note</PARENT>
                    <ADDITIONALNAME>C/Note</ADDITIONALNAME>
                    <NUMBERINGMETHOD>Manual</NUMBERINGMETHOD>
                    <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                    <AFFECTSTOCK>No</AFFECTSTOCK>
                    <PREVENTDUPLICATES>Yes</PREVENTDUPLICATES>
                    <PREFILLZERO>No</PREFILLZERO>
                    <PRINTAFTERSAVE>No</PRINTAFTERSAVE>
                    <FORMALRECEIPT>No</FORMALRECEIPT>
                    <ISOPTIONAL>No</ISOPTIONAL>
                    <ASMFGJRNL>No</ASMFGJRNL>
                    <EFFECTIVEDATE>No</EFFECTIVEDATE>
                    <COMMONNARRATION>Yes</COMMONNARRATION>
                    <MULTINARRATION>No</MULTINARRATION>
                    <ISTAXINVOICE>No</ISTAXINVOICE>
                    <USEFORPOSINVOICE>No</USEFORPOSINVOICE>
                    <USEFOREXCISETRADINGINVOICE>No</USEFOREXCISETRADINGINVOICE>
                    <SORTPOSITION>50</SORTPOSITION>
                    <BEGINNINGNUMBER>1</BEGINNINGNUMBER>
                  </VOUCHERTYPE>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <VOUCHERTYPE NAME="Debit Note" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>Debit Note</NAME>
                    </NAME.LIST>
                    <PARENT>Debit Note</PARENT>
                    <ADDITIONALNAME>D/Note</ADDITIONALNAME>
                    <NUMBERINGMETHOD>Manual</NUMBERINGMETHOD>
                    <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                    <AFFECTSTOCK>No</AFFECTSTOCK>
                    <PREVENTDUPLICATES>Yes</PREVENTDUPLICATES>
                    <PREFILLZERO>No</PREFILLZERO>
                    <PRINTAFTERSAVE>No</PRINTAFTERSAVE>
                    <FORMALRECEIPT>No</FORMALRECEIPT>
                    <ISOPTIONAL>No</ISOPTIONAL>
                    <ASMFGJRNL>No</ASMFGJRNL>
                    <EFFECTIVEDATE>No</EFFECTIVEDATE>
                    <COMMONNARRATION>Yes</COMMONNARRATION>
                    <MULTINARRATION>No</MULTINARRATION>
                    <ISTAXINVOICE>No</ISTAXINVOICE>
                    <USEFORPOSINVOICE>No</USEFORPOSINVOICE>
                    <USEFOREXCISETRADINGINVOICE>No</USEFOREXCISETRADINGINVOICE>
                    <SORTPOSITION>50</SORTPOSITION>
                    <BEGINNINGNUMBER>1</BEGINNINGNUMBER>
                  </VOUCHERTYPE>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <VOUCHERTYPE NAME="KIAS Credit Note" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Credit Note</NAME>
                    </NAME.LIST>
                    <PARENT>Credit Note</PARENT>
                    <ADDITIONALNAME>C/Note</ADDITIONALNAME>
                    <NUMBERINGMETHOD>Manual</NUMBERINGMETHOD>
                    <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                    <AFFECTSTOCK>No</AFFECTSTOCK>
                    <PREVENTDUPLICATES>Yes</PREVENTDUPLICATES>
                    <PREFILLZERO>No</PREFILLZERO>
                    <PRINTAFTERSAVE>No</PRINTAFTERSAVE>
                    <FORMALRECEIPT>No</FORMALRECEIPT>
                    <ISOPTIONAL>No</ISOPTIONAL>
                    <ASMFGJRNL>No</ASMFGJRNL>
                    <EFFECTIVEDATE>No</EFFECTIVEDATE>
                    <COMMONNARRATION>Yes</COMMONNARRATION>
                    <MULTINARRATION>No</MULTINARRATION>
                    <ISTAXINVOICE>No</ISTAXINVOICE>
                    <USEFORPOSINVOICE>No</USEFORPOSINVOICE>
                    <USEFOREXCISETRADINGINVOICE>No</USEFOREXCISETRADINGINVOICE>
                    <SORTPOSITION>50</SORTPOSITION>
                    <BEGINNINGNUMBER>1</BEGINNINGNUMBER>
                  </VOUCHERTYPE>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <VOUCHERTYPE NAME="KIAS Debit Note" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Debit Note</NAME>
                    </NAME.LIST>
                    <PARENT>Debit Note</PARENT>
                    <ADDITIONALNAME>D/Note</ADDITIONALNAME>
                    <NUMBERINGMETHOD>Manual</NUMBERINGMETHOD>
                    <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                    <AFFECTSTOCK>No</AFFECTSTOCK>
                    <PREVENTDUPLICATES>Yes</PREVENTDUPLICATES>
                    <PREFILLZERO>No</PREFILLZERO>
                    <PRINTAFTERSAVE>No</PRINTAFTERSAVE>
                    <FORMALRECEIPT>No</FORMALRECEIPT>
                    <ISOPTIONAL>No</ISOPTIONAL>
                    <ASMFGJRNL>No</ASMFGJRNL>
                    <EFFECTIVEDATE>No</EFFECTIVEDATE>
                    <COMMONNARRATION>Yes</COMMONNARRATION>
                    <MULTINARRATION>No</MULTINARRATION>
                    <ISTAXINVOICE>No</ISTAXINVOICE>
                    <USEFORPOSINVOICE>No</USEFORPOSINVOICE>
                    <USEFOREXCISETRADINGINVOICE>No</USEFOREXCISETRADINGINVOICE>
                    <SORTPOSITION>50</SORTPOSITION>
                    <BEGINNINGNUMBER>1</BEGINNINGNUMBER>
                  </VOUCHERTYPE>
                </TALLYMESSAGE>
                 <TALLYMESSAGE xmlns:UDF="TallyUDF">       
                  <VOUCHERTYPE NAME="KIAS Receipt" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Receipt</NAME>
                    </NAME.LIST>
                    <PARENT>Receipt</PARENT>
                    <ADDITIONALNAME>Rcpt</ADDITIONALNAME>
                    <NUMBERINGMETHOD>Manual</NUMBERINGMETHOD>
                    <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                    <AFFECTSTOCK>No</AFFECTSTOCK>
                    <PREVENTDUPLICATES>Yes</PREVENTDUPLICATES>
                    <PREFILLZERO>No</PREFILLZERO>
                    <PRINTAFTERSAVE>No</PRINTAFTERSAVE>
                    <FORMALRECEIPT>No</FORMALRECEIPT>
                    <ISOPTIONAL>No</ISOPTIONAL>
                    <ASMFGJRNL>No</ASMFGJRNL>
                    <EFFECTIVEDATE>No</EFFECTIVEDATE>
                    <COMMONNARRATION>Yes</COMMONNARRATION>
                    <MULTINARRATION>No</MULTINARRATION>
                    <ISTAXINVOICE>No</ISTAXINVOICE>
                    <USEFORPOSINVOICE>No</USEFORPOSINVOICE>
                    <USEFOREXCISETRADINGINVOICE>No</USEFOREXCISETRADINGINVOICE>
                    <SORTPOSITION>50</SORTPOSITION>
                    <BEGINNINGNUMBER>1</BEGINNINGNUMBER>
                  </VOUCHERTYPE>
                </TALLYMESSAGE>
                 <TALLYMESSAGE xmlns:UDF="TallyUDF">       
                  <VOUCHERTYPE NAME="KIAS Payment" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Payment</NAME>
                    </NAME.LIST>
                    <PARENT>Payment</PARENT>
                    <ADDITIONALNAME>Pymt</ADDITIONALNAME>
                    <NUMBERINGMETHOD>Manual</NUMBERINGMETHOD>
                    <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                    <AFFECTSTOCK>No</AFFECTSTOCK>
                    <PREVENTDUPLICATES>Yes</PREVENTDUPLICATES>
                    <PREFILLZERO>No</PREFILLZERO>
                    <PRINTAFTERSAVE>No</PRINTAFTERSAVE>
                    <FORMALRECEIPT>No</FORMALRECEIPT>
                    <ISOPTIONAL>No</ISOPTIONAL>
                    <ASMFGJRNL>No</ASMFGJRNL>
                    <EFFECTIVEDATE>No</EFFECTIVEDATE>
                    <COMMONNARRATION>Yes</COMMONNARRATION>
                    <MULTINARRATION>No</MULTINARRATION>
                    <ISTAXINVOICE>No</ISTAXINVOICE>
                    <USEFORPOSINVOICE>No</USEFORPOSINVOICE>
                    <USEFOREXCISETRADINGINVOICE>No</USEFOREXCISETRADINGINVOICE>
                    <SORTPOSITION>50</SORTPOSITION>
                    <BEGINNINGNUMBER>1</BEGINNINGNUMBER>
                  </VOUCHERTYPE>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <VOUCHERTYPE NAME="KIAS Invoices" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Invoices</NAME>
                    </NAME.LIST>
                    <PARENT>Sales</PARENT>
                    <ADDITIONALNAME>Sale</ADDITIONALNAME>
                    <NUMBERINGMETHOD>Manual</NUMBERINGMETHOD>
                    <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                    <AFFECTSTOCK>No</AFFECTSTOCK>
                    <PREVENTDUPLICATES>Yes</PREVENTDUPLICATES>
                    <PREFILLZERO>No</PREFILLZERO>
                    <PRINTAFTERSAVE>No</PRINTAFTERSAVE>
                    <FORMALRECEIPT>No</FORMALRECEIPT>
                    <ISOPTIONAL>No</ISOPTIONAL>
                    <ASMFGJRNL>No</ASMFGJRNL>
                    <EFFECTIVEDATE>No</EFFECTIVEDATE>
                    <COMMONNARRATION>Yes</COMMONNARRATION>
                    <MULTINARRATION>No</MULTINARRATION>
                    <ISTAXINVOICE>No</ISTAXINVOICE>
                    <USEFORPOSINVOICE>No</USEFORPOSINVOICE>
                    <USEFOREXCISETRADINGINVOICE>No</USEFOREXCISETRADINGINVOICE>
                    <SORTPOSITION>50</SORTPOSITION>
                    <BEGINNINGNUMBER>1</BEGINNINGNUMBER>
                  </VOUCHERTYPE>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <GROUP NAME="Sundry Debtors" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>Sundry Debtors</NAME>
                    </NAME.LIST>
                    <PARENT>Current Assets</PARENT>
                    <ISBILLWISEON>No</ISBILLWISEON>
                    <ISADDABLE>No</ISADDABLE>
                    <ISSUBLEDGER>No</ISSUBLEDGER>
                    <ISREVENUE>No</ISREVENUE>
                    <AFFECTGROSSPROFIT>No</AFFECTGROSSPROFIT>
                    <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                    <TRACKNEGATIVEBALANCES>No</TRACKNEGATIVEBALANCES>
                    <ISCONDENSED>No</ISCONDENSED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </GROUP>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">';

            if(!empty($company)){
                foreach($company as $cmp){
                  $matter .='<GROUP NAME="KIAS '.$cmp.' Debtors" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS '.$cmp.' Debtors</NAME>
                    </NAME.LIST>
                    <PARENT>Sundry Debtors</PARENT>
                    <ISBILLWISEON>No</ISBILLWISEON>
                    <ISADDABLE>No</ISADDABLE>
                    <ISSUBLEDGER>No</ISSUBLEDGER>
                    <ISREVENUE>No</ISREVENUE>
                    <AFFECTGROSSPROFIT>No</AFFECTGROSSPROFIT>
                    <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                    <TRACKNEGATIVEBALANCES>No</TRACKNEGATIVEBALANCES>
                    <ISCONDENSED>No</ISCONDENSED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </GROUP>';
                }
            }
               
    $matter .='</TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <LEDGER NAME="KIAS Sales" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Sales</NAME>
                    </NAME.LIST>
                    <ADDRESS.LIST />
                    <ADDITIONALNAME>KIAS Sales</ADDITIONALNAME>
                    <PARENT>Sales Accounts</PARENT>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TAXTYPE>
                    </TAXTYPE>
                    <GSTAPPLICABLE>&#4; Applicable</GSTAPPLICABLE>
                    <GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TDSRATENAME>&#4; Not Applicable</TDSRATENAME>
                    <ISBILLWISEON>Yes</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>No</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>Yes</AFFECTSSTOCK>
                    <FORPAYROLL>No</FORPAYROLL>
                    <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <TCSAPPLICABLE>&#4; Not Applicable</TCSAPPLICABLE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AUDITED>No</AUDITED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </LEDGER>
                </TALLYMESSAGE>
        
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <LEDGER NAME="KIAS GST" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS GST</NAME>
                    </NAME.LIST>
                    <ADDRESS.LIST />
                    <ADDITIONALNAME>KIAS GST</ADDITIONALNAME>
                    <CURRENCYNAME>
                    </CURRENCYNAME>
                    <STATENAME>Delhi</STATENAME>
                    <PINCODE>
                    </PINCODE>
                    <INCOMETAXNUMBER>
                    </INCOMETAXNUMBER>
                    <SALESTAXNUMBER>
                    </SALESTAXNUMBER>
                    <VATTINNUMBER>
                    </VATTINNUMBER>
                    <PARENT>Duties &amp; Taxes</PARENT>
                    <TCSAPPLICABLE>&#4; Not Applicable</TCSAPPLICABLE>
                    <GSTDUTYHEAD>Integrated Tax</GSTDUTYHEAD>
                    <TAXTYPE>GST</TAXTYPE>
                    <RATEOFTAXCALCULATION>0</RATEOFTAXCALCULATION>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TDSRATENAME>&#4; Not Applicable</TDSRATENAME>
                    <ISBILLWISEON>Yes</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>No</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>Yes</AFFECTSSTOCK>
                    <FORPAYROLL>No</FORPAYROLL>
                    <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AUDITED>No</AUDITED>
                    <SORTPOSITION>1000</SORTPOSITION>
                    <GSTTYPEOFSUPPLY>
                    </GSTTYPEOFSUPPLY>
                  </LEDGER>
                </TALLYMESSAGE>
        
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <LEDGER NAME="KIAS Cash Receipt" RESERVEDNAME="">
                    <NAME.LIST>
                    <NAME>KIAS Cash Receipt</NAME>
                    </NAME.LIST>
                    <ADDRESS.LIST />
                    <ADDITIONALNAME>KIAS Cash Receipt</ADDITIONALNAME>
                    <PARENT>Cash-in-Hand</PARENT>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <GSTAPPLICABLE>&amp;#4; Not Applicable</GSTAPPLICABLE>
                    <GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TDSRATENAME>&#4; Not Applicable</TDSRATENAME>
                    <ISBILLWISEON>Yes</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>No</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>Yes</AFFECTSSTOCK>
                    <FORPAYROLL>No</FORPAYROLL>
                    <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <TCSAPPLICABLE>&#4; Not Applicable</TCSAPPLICABLE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AUDITED>No</AUDITED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </LEDGER>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <LEDGER NAME="KIAS Cheque Receipt" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Cheque Receipt</NAME>
                    </NAME.LIST>
                    <ADDRESS.LIST />
                    <ADDITIONALNAME>KIAS Cheque Receipt</ADDITIONALNAME>
                    <PARENT>Bank Accounts</PARENT>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <GSTAPPLICABLE>&amp;#4; Not Applicable</GSTAPPLICABLE>
                    <GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TDSRATENAME>&#4; Not Applicable</TDSRATENAME>
                    <ISBILLWISEON>Yes</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>No</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>Yes</AFFECTSSTOCK>
                    <FORPAYROLL>No</FORPAYROLL>
                    <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <TCSAPPLICABLE>&#4; Not Applicable</TCSAPPLICABLE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AUDITED>No</AUDITED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </LEDGER>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <LEDGER NAME="KIAS NEFT Receipt" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS NEFT Receipt</NAME>
                    </NAME.LIST>
                    <ADDRESS.LIST />
                    <ADDITIONALNAME>KIAS NEFT Receipt</ADDITIONALNAME>
                    <PARENT>Bank Accounts</PARENT>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <GSTAPPLICABLE>&amp;#4; Not Applicable</GSTAPPLICABLE>
                    <GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TDSRATENAME>&#4; Not Applicable</TDSRATENAME>
                    <ISBILLWISEON>Yes</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>No</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>Yes</AFFECTSSTOCK>
                    <FORPAYROLL>No</FORPAYROLL>
                    <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <TCSAPPLICABLE>&#4; Not Applicable</TCSAPPLICABLE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AUDITED>No</AUDITED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </LEDGER>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <LEDGER NAME="KIAS Sales Return" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Sales Return</NAME>
                    </NAME.LIST>
                    <ADDRESS.LIST />
                    <ADDITIONALNAME>KIAS Sales Return</ADDITIONALNAME>
                    <PARENT>Sales Accounts</PARENT>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <GSTAPPLICABLE>&#4; Applicable</GSTAPPLICABLE>
                    <GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TDSRATENAME>&#4; Not Applicable</TDSRATENAME>
                    <ISBILLWISEON>Yes</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>No</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>Yes</AFFECTSSTOCK>
                    <FORPAYROLL>No</FORPAYROLL>
                    <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <TCSAPPLICABLE>&#4; Not Applicable</TCSAPPLICABLE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AUDITED>No</AUDITED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </LEDGER>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <LEDGER NAME="KIAS Cash Discount" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Cash Discount</NAME>
                    </NAME.LIST>
                    <ADDRESS.LIST />
                    <ADDITIONALNAME>KIAS Cash Discount</ADDITIONALNAME>
                    <PARENT>Direct Expenses</PARENT>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <GSTAPPLICABLE>&amp;#4; Not Applicable</GSTAPPLICABLE>
                    <GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TDSRATENAME>&#4; Not Applicable</TDSRATENAME>
                    <ISBILLWISEON>Yes</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>No</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>Yes</AFFECTSSTOCK>
                    <FORPAYROLL>No</FORPAYROLL>
                    <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <TCSAPPLICABLE>&#4; Not Applicable</TCSAPPLICABLE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AUDITED>No</AUDITED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </LEDGER>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <LEDGER NAME="KIAS Office Adjustment" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Office Adjustment</NAME>
                    </NAME.LIST>
                    <ADDRESS.LIST />
                    <ADDITIONALNAME>KIAS Office Adjustment</ADDITIONALNAME>
                    <PARENT>Indirect Expenses</PARENT>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <GSTAPPLICABLE>&#4; Applicable</GSTAPPLICABLE>
                    <GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TDSRATENAME>&#4; Not Applicable</TDSRATENAME>
                    <ISBILLWISEON>Yes</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>No</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>Yes</AFFECTSSTOCK>
                    <FORPAYROLL>No</FORPAYROLL>
                    <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <TCSAPPLICABLE>&#4; Not Applicable</TCSAPPLICABLE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AUDITED>No</AUDITED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </LEDGER>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <LEDGER NAME="KIAS Round Off" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Round Off</NAME>
                    </NAME.LIST>
                    <ADDRESS.LIST />
                    <ADDITIONALNAME>KIAS Round Off</ADDITIONALNAME>
                    <PARENT>Indirect Expenses</PARENT>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <GSTAPPLICABLE>&#4; Applicable</GSTAPPLICABLE>
                    <GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TDSRATENAME>&#4; Not Applicable</TDSRATENAME>
                    <ISBILLWISEON>Yes</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>No</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>Yes</AFFECTSSTOCK>
                    <FORPAYROLL>No</FORPAYROLL>
                    <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <TCSAPPLICABLE>&#4; Not Applicable</TCSAPPLICABLE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AUDITED>No</AUDITED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </LEDGER>
                </TALLYMESSAGE>
        
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <LEDGER NAME="KIAS Other Adjustment" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Other Adjustment</NAME>
                    </NAME.LIST>
                    <ADDRESS.LIST />
                    <ADDITIONALNAME>KIAS Other Adjustment</ADDITIONALNAME>
                    <PARENT>Indirect Expenses</PARENT>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <GSTAPPLICABLE>&#4; Applicable</GSTAPPLICABLE>
                    <GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TDSRATENAME>&#4; Not Applicable</TDSRATENAME>
                    <ISBILLWISEON>Yes</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>No</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>Yes</AFFECTSSTOCK>
                    <FORPAYROLL>No</FORPAYROLL>
                    <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <TCSAPPLICABLE>&#4; Not Applicable</TCSAPPLICABLE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AUDITED>No</AUDITED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </LEDGER>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <LEDGER NAME="KIAS Debit to Employee" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Debit to Employee</NAME>
                    </NAME.LIST>
                    <ADDRESS.LIST />
                    <ADDITIONALNAME>KIAS Debit to Employee</ADDITIONALNAME>
                    <PARENT>Indirect Expenses</PARENT>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <GSTAPPLICABLE>&#4; Applicable</GSTAPPLICABLE>
                    <GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TDSRATENAME>&#4; Not Applicable</TDSRATENAME>
                    <ISBILLWISEON>Yes</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>No</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>Yes</AFFECTSSTOCK>
                    <FORPAYROLL>No</FORPAYROLL>
                    <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <TCSAPPLICABLE>&#4; Not Applicable</TCSAPPLICABLE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AUDITED>No</AUDITED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </LEDGER>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <LEDGER NAME="KIAS Cheque Bounce Penalty" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Cheque Bounce Penalty</NAME>
                    </NAME.LIST>
                    <ADDRESS.LIST />
                    <ADDITIONALNAME>KIAS Cheque Bounce Penalty</ADDITIONALNAME>
                    <PARENT>Indirect Incomes</PARENT>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <GSTAPPLICABLE>&amp;#4; Not Applicable</GSTAPPLICABLE>
                    <GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TDSRATENAME>&#4; Not Applicable</TDSRATENAME>
                    <ISBILLWISEON>Yes</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>No</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>Yes</AFFECTSSTOCK>
                    <FORPAYROLL>No</FORPAYROLL>
                    <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <TCSAPPLICABLE>&#4; Not Applicable</TCSAPPLICABLE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AUDITED>No</AUDITED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </LEDGER>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <LEDGER NAME="KIAS Credit Adjustment" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Credit Adjustment</NAME>
                    </NAME.LIST>
                    <ADDRESS.LIST>
                    </ADDRESS.LIST>
                    <PARENT>Sundry Creditors</PARENT>
                    <TAXTYPE>Others</TAXTYPE>
                    <GSTAPPLICABLE>No</GSTAPPLICABLE>
                    <ISBILLWISEON>No</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>No</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>No</AFFECTSSTOCK>
                    <FORPAYROLL>No</FORPAYROLL>
                    <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <TCSAPPLICABLE>&#4; Applicable</TCSAPPLICABLE>
                    <TDSDEDUCTEETYPE>Company - Resident</TDSDEDUCTEETYPE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AUDITED>No</AUDITED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </LEDGER>
                </TALLYMESSAGE>';
       
        if(!empty($retailers)){
            foreach($retailers as $detail){
                $gstNo="";
                $flag="";
                // $retailerId=$detail['id'];
                $retailerCode=$detail['retailerCode'];
                $retailerName=$detail['retailerName'];
                $name=$retailerName.' : '.$retailerCode;

                $gst=$detail['gstIn'];
                if($gst !=""){
                  $gstNo=$gst;
                  $flag="Regular";
                }else{
                  $gstNo="";
                  $flag="";
                }

                $name=str_replace('&','And',$name);
                // $name=str_replace('&','And',$name);
                // $name=str_replace('&','And',$name);

                $matter .='<TALLYMESSAGE xmlns:UDF="TallyUDF">
              <LEDGER NAME="'.$name.'" RESERVEDNAME="">
                <NAME.LIST>
                  <NAME>'.$name.'</NAME>
                </NAME.LIST>
                <ADDRESS.LIST>
                </ADDRESS.LIST>
                <ADDITIONALNAME></ADDITIONALNAME>
                <PARENT>KIAS Nestle Debtors</PARENT>
                <TAXTYPE>Others</TAXTYPE>
                <GSTAPPLICABLE>No</GSTAPPLICABLE>
                <ISBILLWISEON>No</ISBILLWISEON>
                <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                <ISINTERESTON>No</ISINTERESTON>
                <ALLOWINMOBILE>No</ALLOWINMOBILE>
                <ISCONDENSED>No</ISCONDENSED>
                <AFFECTSSTOCK>No</AFFECTSSTOCK>
                <FORPAYROLL>No</FORPAYROLL>
                <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                <USEFORVAT>No</USEFORVAT>
                <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                <TCSAPPLICABLE>&#4; Applicable</TCSAPPLICABLE>
                <TDSDEDUCTEETYPE>Company - Resident</TDSDEDUCTEETYPE>
                <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                <USEFORGRATUITY>No</USEFORGRATUITY>
                <FORSERVICETAX>No</FORSERVICETAX>
                <ISINPUTCREDIT>No</ISINPUTCREDIT>
                <ISEXEMPTED>No</ISEXEMPTED>
                <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                <AUDITED>No</AUDITED>
                <SORTPOSITION>0</SORTPOSITION>
                <COUNTRYNAME>India</COUNTRYNAME>
                <GSTREGISTRATIONTYPE>'.$flag.'</GSTREGISTRATIONTYPE>
                <VATDEALERTYPE>'.$flag.'</VATDEALERTYPE>
                <PARTYGSTIN>'.$gstNo.'</PARTYGSTIN>
                <COUNTRYOFRESIDENCE>India</COUNTRYOFRESIDENCE>
                <LEDSTATENAME>Delhi</LEDSTATENAME>
                <GSTTYPEOFSUPPLY></GSTTYPEOFSUPPLY>
              </LEDGER>
            </TALLYMESSAGE>';
          }
      }

      if(!empty($deliveryslipRetailers)){
        foreach($deliveryslipRetailers as $detail){
            $gstNo="";
            $flag="";
            // $retailerId=$detail['id'];
            $retailerCode=$detail['retailerCode'];
            $retailerName=$detail['retailerName'];
            $name='Deliveryslip : '.$retailerName.' : '.$retailerCode;

            $gst="";
            if($gst !=""){
              $gstNo=$gst;
              $flag="Regular";
            }else{
              $gstNo="";
              $flag="";
            }
            $name=str_replace('&','And',$name);
            // $name=str_replace('& ','And',$name);
            // $name=str_replace(' &','And',$name);

            // $name=str_replace(' ','',$name);
            // echo $name;exit;

            $matter .='<TALLYMESSAGE xmlns:UDF="TallyUDF">
                <LEDGER NAME="'.$name.'" RESERVEDNAME="">
                  <NAME.LIST>
                    <NAME>'.$name.'</NAME>
                  </NAME.LIST>
                  <ADDRESS.LIST>
                  </ADDRESS.LIST>
                  <ADDITIONALNAME></ADDITIONALNAME>
                  <PARENT>KIAS Nestle Debtors</PARENT>
                  <TAXTYPE>Others</TAXTYPE>
                  <GSTAPPLICABLE>No</GSTAPPLICABLE>
                  <ISBILLWISEON>No</ISBILLWISEON>
                  <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                  <ISINTERESTON>No</ISINTERESTON>
                  <ALLOWINMOBILE>No</ALLOWINMOBILE>
                  <ISCONDENSED>No</ISCONDENSED>
                  <AFFECTSSTOCK>No</AFFECTSSTOCK>
                  <FORPAYROLL>No</FORPAYROLL>
                  <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                  <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                  <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                  <USEFORVAT>No</USEFORVAT>
                  <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                  <TCSAPPLICABLE>&#4; Applicable</TCSAPPLICABLE>
                  <TDSDEDUCTEETYPE>Company - Resident</TDSDEDUCTEETYPE>
                  <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                  <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                  <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                  <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                  <USEFORGRATUITY>No</USEFORGRATUITY>
                  <FORSERVICETAX>No</FORSERVICETAX>
                  <ISINPUTCREDIT>No</ISINPUTCREDIT>
                  <ISEXEMPTED>No</ISEXEMPTED>
                  <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                  <AUDITED>No</AUDITED>
                  <SORTPOSITION>0</SORTPOSITION>
                  <COUNTRYNAME>India</COUNTRYNAME>
                  <GSTREGISTRATIONTYPE>'.$flag.'</GSTREGISTRATIONTYPE>
                  <VATDEALERTYPE>'.$flag.'</VATDEALERTYPE>
                  <PARTYGSTIN>'.$gstNo.'</PARTYGSTIN>
                  <COUNTRYOFRESIDENCE>India</COUNTRYOFRESIDENCE>
                  <LEDSTATENAME>Delhi</LEDSTATENAME>
                  <GSTTYPEOFSUPPLY></GSTTYPEOFSUPPLY>
                </LEDGER>
              </TALLYMESSAGE>';
            }
        }

        $name=$distributorCode.'-Masters-'.date('d-M-Y').'.xml';
        $matter .='</REQUESTDATA>
                </IMPORTDATA>
              </BODY>
              </ENVELOPE>';

        header('Content-disposition: attachment; filename='.$name);
        header ("Content-Type:text/xml"); 
        echo  $matter;
    }

    public function masterExportWithDeliverySlip($company,$retailers,$deliveryslipRetailers){
        $distributorCode="";
        $officeDetails=$this->ReportModel->getdata('office_details');
        if(!empty($officeDetails)){
          $distributorCode=$officeDetails[0]['distributorCode'];
        }
      // echo $company;exit;
        $matter='<?xml version="1.0"?>
        <ENVELOPE>
          <HEADER>
            <TALLYREQUEST>Import Data</TALLYREQUEST>
          </HEADER>
          <BODY>
            <IMPORTDATA>
              <REQUESTDESC>
                <REPORTNAME>All Masters</REPORTNAME>
                <STATICVARIABLES/>
                <SVCURRENTCOMPANY></SVCURRENTCOMPANY>
              </REQUESTDESC>
              <REQUESTDATA>
                  <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <VOUCHERTYPE NAME="Credit Note" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>Credit Note</NAME>
                    </NAME.LIST>
                    <PARENT>Credit Note</PARENT>
                    <ADDITIONALNAME>C/Note</ADDITIONALNAME>
                    <NUMBERINGMETHOD>Manual</NUMBERINGMETHOD>
                    <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                    <AFFECTSTOCK>No</AFFECTSTOCK>
                    <PREVENTDUPLICATES>Yes</PREVENTDUPLICATES>
                    <PREFILLZERO>No</PREFILLZERO>
                    <PRINTAFTERSAVE>No</PRINTAFTERSAVE>
                    <FORMALRECEIPT>No</FORMALRECEIPT>
                    <ISOPTIONAL>No</ISOPTIONAL>
                    <ASMFGJRNL>No</ASMFGJRNL>
                    <EFFECTIVEDATE>No</EFFECTIVEDATE>
                    <COMMONNARRATION>Yes</COMMONNARRATION>
                    <MULTINARRATION>No</MULTINARRATION>
                    <ISTAXINVOICE>No</ISTAXINVOICE>
                    <USEFORPOSINVOICE>No</USEFORPOSINVOICE>
                    <USEFOREXCISETRADINGINVOICE>No</USEFOREXCISETRADINGINVOICE>
                    <SORTPOSITION>50</SORTPOSITION>
                    <BEGINNINGNUMBER>1</BEGINNINGNUMBER>
                  </VOUCHERTYPE>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <VOUCHERTYPE NAME="Debit Note" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>Debit Note</NAME>
                    </NAME.LIST>
                    <PARENT>Debit Note</PARENT>
                    <ADDITIONALNAME>D/Note</ADDITIONALNAME>
                    <NUMBERINGMETHOD>Manual</NUMBERINGMETHOD>
                    <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                    <AFFECTSTOCK>No</AFFECTSTOCK>
                    <PREVENTDUPLICATES>Yes</PREVENTDUPLICATES>
                    <PREFILLZERO>No</PREFILLZERO>
                    <PRINTAFTERSAVE>No</PRINTAFTERSAVE>
                    <FORMALRECEIPT>No</FORMALRECEIPT>
                    <ISOPTIONAL>No</ISOPTIONAL>
                    <ASMFGJRNL>No</ASMFGJRNL>
                    <EFFECTIVEDATE>No</EFFECTIVEDATE>
                    <COMMONNARRATION>Yes</COMMONNARRATION>
                    <MULTINARRATION>No</MULTINARRATION>
                    <ISTAXINVOICE>No</ISTAXINVOICE>
                    <USEFORPOSINVOICE>No</USEFORPOSINVOICE>
                    <USEFOREXCISETRADINGINVOICE>No</USEFOREXCISETRADINGINVOICE>
                    <SORTPOSITION>50</SORTPOSITION>
                    <BEGINNINGNUMBER>1</BEGINNINGNUMBER>
                  </VOUCHERTYPE>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <VOUCHERTYPE NAME="KIAS Credit Note" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Credit Note</NAME>
                    </NAME.LIST>
                    <PARENT>Credit Note</PARENT>
                    <ADDITIONALNAME>C/Note</ADDITIONALNAME>
                    <NUMBERINGMETHOD>Manual</NUMBERINGMETHOD>
                    <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                    <AFFECTSTOCK>No</AFFECTSTOCK>
                    <PREVENTDUPLICATES>Yes</PREVENTDUPLICATES>
                    <PREFILLZERO>No</PREFILLZERO>
                    <PRINTAFTERSAVE>No</PRINTAFTERSAVE>
                    <FORMALRECEIPT>No</FORMALRECEIPT>
                    <ISOPTIONAL>No</ISOPTIONAL>
                    <ASMFGJRNL>No</ASMFGJRNL>
                    <EFFECTIVEDATE>No</EFFECTIVEDATE>
                    <COMMONNARRATION>Yes</COMMONNARRATION>
                    <MULTINARRATION>No</MULTINARRATION>
                    <ISTAXINVOICE>No</ISTAXINVOICE>
                    <USEFORPOSINVOICE>No</USEFORPOSINVOICE>
                    <USEFOREXCISETRADINGINVOICE>No</USEFOREXCISETRADINGINVOICE>
                    <SORTPOSITION>50</SORTPOSITION>
                    <BEGINNINGNUMBER>1</BEGINNINGNUMBER>
                  </VOUCHERTYPE>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <VOUCHERTYPE NAME="KIAS Debit Note" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Debit Note</NAME>
                    </NAME.LIST>
                    <PARENT>Debit Note</PARENT>
                    <ADDITIONALNAME>D/Note</ADDITIONALNAME>
                    <NUMBERINGMETHOD>Manual</NUMBERINGMETHOD>
                    <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                    <AFFECTSTOCK>No</AFFECTSTOCK>
                    <PREVENTDUPLICATES>Yes</PREVENTDUPLICATES>
                    <PREFILLZERO>No</PREFILLZERO>
                    <PRINTAFTERSAVE>No</PRINTAFTERSAVE>
                    <FORMALRECEIPT>No</FORMALRECEIPT>
                    <ISOPTIONAL>No</ISOPTIONAL>
                    <ASMFGJRNL>No</ASMFGJRNL>
                    <EFFECTIVEDATE>No</EFFECTIVEDATE>
                    <COMMONNARRATION>Yes</COMMONNARRATION>
                    <MULTINARRATION>No</MULTINARRATION>
                    <ISTAXINVOICE>No</ISTAXINVOICE>
                    <USEFORPOSINVOICE>No</USEFORPOSINVOICE>
                    <USEFOREXCISETRADINGINVOICE>No</USEFOREXCISETRADINGINVOICE>
                    <SORTPOSITION>50</SORTPOSITION>
                    <BEGINNINGNUMBER>1</BEGINNINGNUMBER>
                  </VOUCHERTYPE>
                </TALLYMESSAGE>
                 <TALLYMESSAGE xmlns:UDF="TallyUDF">       
                  <VOUCHERTYPE NAME="KIAS Receipt" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Receipt</NAME>
                    </NAME.LIST>
                    <PARENT>Receipt</PARENT>
                    <ADDITIONALNAME>Rcpt</ADDITIONALNAME>
                    <NUMBERINGMETHOD>Manual</NUMBERINGMETHOD>
                    <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                    <AFFECTSTOCK>No</AFFECTSTOCK>
                    <PREVENTDUPLICATES>Yes</PREVENTDUPLICATES>
                    <PREFILLZERO>No</PREFILLZERO>
                    <PRINTAFTERSAVE>No</PRINTAFTERSAVE>
                    <FORMALRECEIPT>No</FORMALRECEIPT>
                    <ISOPTIONAL>No</ISOPTIONAL>
                    <ASMFGJRNL>No</ASMFGJRNL>
                    <EFFECTIVEDATE>No</EFFECTIVEDATE>
                    <COMMONNARRATION>Yes</COMMONNARRATION>
                    <MULTINARRATION>No</MULTINARRATION>
                    <ISTAXINVOICE>No</ISTAXINVOICE>
                    <USEFORPOSINVOICE>No</USEFORPOSINVOICE>
                    <USEFOREXCISETRADINGINVOICE>No</USEFOREXCISETRADINGINVOICE>
                    <SORTPOSITION>50</SORTPOSITION>
                    <BEGINNINGNUMBER>1</BEGINNINGNUMBER>
                  </VOUCHERTYPE>
                </TALLYMESSAGE>
                 <TALLYMESSAGE xmlns:UDF="TallyUDF">       
                  <VOUCHERTYPE NAME="KIAS Payment" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Payment</NAME>
                    </NAME.LIST>
                    <PARENT>Payment</PARENT>
                    <ADDITIONALNAME>Pymt</ADDITIONALNAME>
                    <NUMBERINGMETHOD>Manual</NUMBERINGMETHOD>
                    <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                    <AFFECTSTOCK>No</AFFECTSTOCK>
                    <PREVENTDUPLICATES>Yes</PREVENTDUPLICATES>
                    <PREFILLZERO>No</PREFILLZERO>
                    <PRINTAFTERSAVE>No</PRINTAFTERSAVE>
                    <FORMALRECEIPT>No</FORMALRECEIPT>
                    <ISOPTIONAL>No</ISOPTIONAL>
                    <ASMFGJRNL>No</ASMFGJRNL>
                    <EFFECTIVEDATE>No</EFFECTIVEDATE>
                    <COMMONNARRATION>Yes</COMMONNARRATION>
                    <MULTINARRATION>No</MULTINARRATION>
                    <ISTAXINVOICE>No</ISTAXINVOICE>
                    <USEFORPOSINVOICE>No</USEFORPOSINVOICE>
                    <USEFOREXCISETRADINGINVOICE>No</USEFOREXCISETRADINGINVOICE>
                    <SORTPOSITION>50</SORTPOSITION>
                    <BEGINNINGNUMBER>1</BEGINNINGNUMBER>
                  </VOUCHERTYPE>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <VOUCHERTYPE NAME="KIAS Invoices" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Invoices</NAME>
                    </NAME.LIST>
                    <PARENT>Sales</PARENT>
                    <ADDITIONALNAME>Sale</ADDITIONALNAME>
                    <NUMBERINGMETHOD>Manual</NUMBERINGMETHOD>
                    <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                    <AFFECTSTOCK>No</AFFECTSTOCK>
                    <PREVENTDUPLICATES>Yes</PREVENTDUPLICATES>
                    <PREFILLZERO>No</PREFILLZERO>
                    <PRINTAFTERSAVE>No</PRINTAFTERSAVE>
                    <FORMALRECEIPT>No</FORMALRECEIPT>
                    <ISOPTIONAL>No</ISOPTIONAL>
                    <ASMFGJRNL>No</ASMFGJRNL>
                    <EFFECTIVEDATE>No</EFFECTIVEDATE>
                    <COMMONNARRATION>Yes</COMMONNARRATION>
                    <MULTINARRATION>No</MULTINARRATION>
                    <ISTAXINVOICE>No</ISTAXINVOICE>
                    <USEFORPOSINVOICE>No</USEFORPOSINVOICE>
                    <USEFOREXCISETRADINGINVOICE>No</USEFOREXCISETRADINGINVOICE>
                    <SORTPOSITION>50</SORTPOSITION>
                    <BEGINNINGNUMBER>1</BEGINNINGNUMBER>
                  </VOUCHERTYPE>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <GROUP NAME="Sundry Debtors" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>Sundry Debtors</NAME>
                    </NAME.LIST>
                    <PARENT>Current Assets</PARENT>
                    <ISBILLWISEON>No</ISBILLWISEON>
                    <ISADDABLE>No</ISADDABLE>
                    <ISSUBLEDGER>No</ISSUBLEDGER>
                    <ISREVENUE>No</ISREVENUE>
                    <AFFECTGROSSPROFIT>No</AFFECTGROSSPROFIT>
                    <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                    <TRACKNEGATIVEBALANCES>No</TRACKNEGATIVEBALANCES>
                    <ISCONDENSED>No</ISCONDENSED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </GROUP>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">';

            if(!empty($company)){
                foreach($company as $cmp){
                  $matter .='<GROUP NAME="KIAS '.$cmp['name'].' Debtors" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS '.$cmp['name'].' Debtors</NAME>
                    </NAME.LIST>
                    <PARENT>Sundry Debtors</PARENT>
                    <ISBILLWISEON>No</ISBILLWISEON>
                    <ISADDABLE>No</ISADDABLE>
                    <ISSUBLEDGER>No</ISSUBLEDGER>
                    <ISREVENUE>No</ISREVENUE>
                    <AFFECTGROSSPROFIT>No</AFFECTGROSSPROFIT>
                    <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                    <TRACKNEGATIVEBALANCES>No</TRACKNEGATIVEBALANCES>
                    <ISCONDENSED>No</ISCONDENSED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </GROUP>';
                }
            }
               
    $matter .='</TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <LEDGER NAME="KIAS Sales" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Sales</NAME>
                    </NAME.LIST>
                    <ADDRESS.LIST />
                    <ADDITIONALNAME>KIAS Sales</ADDITIONALNAME>
                    <PARENT>Sales Accounts</PARENT>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TAXTYPE>
                    </TAXTYPE>
                    <GSTAPPLICABLE>&#4; Applicable</GSTAPPLICABLE>
                    <GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TDSRATENAME>&#4; Not Applicable</TDSRATENAME>
                    <ISBILLWISEON>Yes</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>No</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>Yes</AFFECTSSTOCK>
                    <FORPAYROLL>No</FORPAYROLL>
                    <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <TCSAPPLICABLE>&#4; Not Applicable</TCSAPPLICABLE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AUDITED>No</AUDITED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </LEDGER>
                </TALLYMESSAGE>
        
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <LEDGER NAME="KIAS GST" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS GST</NAME>
                    </NAME.LIST>
                    <ADDRESS.LIST />
                    <ADDITIONALNAME>KIAS GST</ADDITIONALNAME>
                    <CURRENCYNAME>
                    </CURRENCYNAME>
                    <STATENAME>Delhi</STATENAME>
                    <PINCODE>
                    </PINCODE>
                    <INCOMETAXNUMBER>
                    </INCOMETAXNUMBER>
                    <SALESTAXNUMBER>
                    </SALESTAXNUMBER>
                    <VATTINNUMBER>
                    </VATTINNUMBER>
                    <PARENT>Duties &amp; Taxes</PARENT>
                    <TCSAPPLICABLE>&#4; Not Applicable</TCSAPPLICABLE>
                    <GSTDUTYHEAD>Integrated Tax</GSTDUTYHEAD>
                    <TAXTYPE>GST</TAXTYPE>
                    <RATEOFTAXCALCULATION>0</RATEOFTAXCALCULATION>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TDSRATENAME>&#4; Not Applicable</TDSRATENAME>
                    <ISBILLWISEON>Yes</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>No</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>Yes</AFFECTSSTOCK>
                    <FORPAYROLL>No</FORPAYROLL>
                    <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AUDITED>No</AUDITED>
                    <SORTPOSITION>1000</SORTPOSITION>
                    <GSTTYPEOFSUPPLY>
                    </GSTTYPEOFSUPPLY>
                  </LEDGER>
                </TALLYMESSAGE>
        
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <LEDGER NAME="KIAS Cash Receipt" RESERVEDNAME="">
                    <NAME.LIST>
                    <NAME>KIAS Cash Receipt</NAME>
                    </NAME.LIST>
                    <ADDRESS.LIST />
                    <ADDITIONALNAME>KIAS Cash Receipt</ADDITIONALNAME>
                    <PARENT>Cash-in-Hand</PARENT>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <GSTAPPLICABLE>&amp;#4; Not Applicable</GSTAPPLICABLE>
                    <GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TDSRATENAME>&#4; Not Applicable</TDSRATENAME>
                    <ISBILLWISEON>Yes</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>No</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>Yes</AFFECTSSTOCK>
                    <FORPAYROLL>No</FORPAYROLL>
                    <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <TCSAPPLICABLE>&#4; Not Applicable</TCSAPPLICABLE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AUDITED>No</AUDITED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </LEDGER>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <LEDGER NAME="KIAS Cheque Receipt" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Cheque Receipt</NAME>
                    </NAME.LIST>
                    <ADDRESS.LIST />
                    <ADDITIONALNAME>KIAS Cheque Receipt</ADDITIONALNAME>
                    <PARENT>Bank Accounts</PARENT>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <GSTAPPLICABLE>&amp;#4; Not Applicable</GSTAPPLICABLE>
                    <GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TDSRATENAME>&#4; Not Applicable</TDSRATENAME>
                    <ISBILLWISEON>Yes</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>No</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>Yes</AFFECTSSTOCK>
                    <FORPAYROLL>No</FORPAYROLL>
                    <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <TCSAPPLICABLE>&#4; Not Applicable</TCSAPPLICABLE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AUDITED>No</AUDITED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </LEDGER>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <LEDGER NAME="KIAS NEFT Receipt" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS NEFT Receipt</NAME>
                    </NAME.LIST>
                    <ADDRESS.LIST />
                    <ADDITIONALNAME>KIAS NEFT Receipt</ADDITIONALNAME>
                    <PARENT>Bank Accounts</PARENT>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <GSTAPPLICABLE>&amp;#4; Not Applicable</GSTAPPLICABLE>
                    <GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TDSRATENAME>&#4; Not Applicable</TDSRATENAME>
                    <ISBILLWISEON>Yes</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>No</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>Yes</AFFECTSSTOCK>
                    <FORPAYROLL>No</FORPAYROLL>
                    <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <TCSAPPLICABLE>&#4; Not Applicable</TCSAPPLICABLE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AUDITED>No</AUDITED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </LEDGER>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <LEDGER NAME="KIAS Sales Return" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Sales Return</NAME>
                    </NAME.LIST>
                    <ADDRESS.LIST />
                    <ADDITIONALNAME>KIAS Sales Return</ADDITIONALNAME>
                    <PARENT>Sales Accounts</PARENT>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <GSTAPPLICABLE>&#4; Applicable</GSTAPPLICABLE>
                    <GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TDSRATENAME>&#4; Not Applicable</TDSRATENAME>
                    <ISBILLWISEON>Yes</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>No</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>Yes</AFFECTSSTOCK>
                    <FORPAYROLL>No</FORPAYROLL>
                    <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <TCSAPPLICABLE>&#4; Not Applicable</TCSAPPLICABLE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AUDITED>No</AUDITED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </LEDGER>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <LEDGER NAME="KIAS Cash Discount" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Cash Discount</NAME>
                    </NAME.LIST>
                    <ADDRESS.LIST />
                    <ADDITIONALNAME>KIAS Cash Discount</ADDITIONALNAME>
                    <PARENT>Direct Expenses</PARENT>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <GSTAPPLICABLE>&amp;#4; Not Applicable</GSTAPPLICABLE>
                    <GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TDSRATENAME>&#4; Not Applicable</TDSRATENAME>
                    <ISBILLWISEON>Yes</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>No</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>Yes</AFFECTSSTOCK>
                    <FORPAYROLL>No</FORPAYROLL>
                    <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <TCSAPPLICABLE>&#4; Not Applicable</TCSAPPLICABLE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AUDITED>No</AUDITED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </LEDGER>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <LEDGER NAME="KIAS Office Adjustment" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Office Adjustment</NAME>
                    </NAME.LIST>
                    <ADDRESS.LIST />
                    <ADDITIONALNAME>KIAS Office Adjustment</ADDITIONALNAME>
                    <PARENT>Indirect Expenses</PARENT>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <GSTAPPLICABLE>&#4; Applicable</GSTAPPLICABLE>
                    <GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TDSRATENAME>&#4; Not Applicable</TDSRATENAME>
                    <ISBILLWISEON>Yes</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>No</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>Yes</AFFECTSSTOCK>
                    <FORPAYROLL>No</FORPAYROLL>
                    <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <TCSAPPLICABLE>&#4; Not Applicable</TCSAPPLICABLE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AUDITED>No</AUDITED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </LEDGER>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <LEDGER NAME="KIAS Round Off" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Round Off</NAME>
                    </NAME.LIST>
                    <ADDRESS.LIST />
                    <ADDITIONALNAME>KIAS Round Off</ADDITIONALNAME>
                    <PARENT>Indirect Expenses</PARENT>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <GSTAPPLICABLE>&#4; Applicable</GSTAPPLICABLE>
                    <GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TDSRATENAME>&#4; Not Applicable</TDSRATENAME>
                    <ISBILLWISEON>Yes</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>No</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>Yes</AFFECTSSTOCK>
                    <FORPAYROLL>No</FORPAYROLL>
                    <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <TCSAPPLICABLE>&#4; Not Applicable</TCSAPPLICABLE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AUDITED>No</AUDITED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </LEDGER>
                </TALLYMESSAGE>
        
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <LEDGER NAME="KIAS Other Adjustment" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Other Adjustment</NAME>
                    </NAME.LIST>
                    <ADDRESS.LIST />
                    <ADDITIONALNAME>KIAS Other Adjustment</ADDITIONALNAME>
                    <PARENT>Indirect Expenses</PARENT>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <GSTAPPLICABLE>&#4; Applicable</GSTAPPLICABLE>
                    <GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TDSRATENAME>&#4; Not Applicable</TDSRATENAME>
                    <ISBILLWISEON>Yes</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>No</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>Yes</AFFECTSSTOCK>
                    <FORPAYROLL>No</FORPAYROLL>
                    <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <TCSAPPLICABLE>&#4; Not Applicable</TCSAPPLICABLE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AUDITED>No</AUDITED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </LEDGER>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <LEDGER NAME="KIAS Debit to Employee" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Debit to Employee</NAME>
                    </NAME.LIST>
                    <ADDRESS.LIST />
                    <ADDITIONALNAME>KIAS Debit to Employee</ADDITIONALNAME>
                    <PARENT>Indirect Expenses</PARENT>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <GSTAPPLICABLE>&#4; Applicable</GSTAPPLICABLE>
                    <GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TDSRATENAME>&#4; Not Applicable</TDSRATENAME>
                    <ISBILLWISEON>Yes</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>No</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>Yes</AFFECTSSTOCK>
                    <FORPAYROLL>No</FORPAYROLL>
                    <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <TCSAPPLICABLE>&#4; Not Applicable</TCSAPPLICABLE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AUDITED>No</AUDITED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </LEDGER>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <LEDGER NAME="KIAS Cheque Bounce Penalty" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Cheque Bounce Penalty</NAME>
                    </NAME.LIST>
                    <ADDRESS.LIST />
                    <ADDITIONALNAME>KIAS Cheque Bounce Penalty</ADDITIONALNAME>
                    <PARENT>Indirect Incomes</PARENT>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <GSTAPPLICABLE>&amp;#4; Not Applicable</GSTAPPLICABLE>
                    <GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>
                    <SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>
                    <TDSRATENAME>&#4; Not Applicable</TDSRATENAME>
                    <ISBILLWISEON>Yes</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>No</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>Yes</AFFECTSSTOCK>
                    <FORPAYROLL>No</FORPAYROLL>
                    <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <TCSAPPLICABLE>&#4; Not Applicable</TCSAPPLICABLE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AUDITED>No</AUDITED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </LEDGER>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <LEDGER NAME="KIAS Credit Adjustment" RESERVEDNAME="">
                    <NAME.LIST>
                      <NAME>KIAS Credit Adjustment</NAME>
                    </NAME.LIST>
                    <ADDRESS.LIST>
                    </ADDRESS.LIST>
                    <PARENT>Sundry Creditors</PARENT>
                    <TAXTYPE>Others</TAXTYPE>
                    <GSTAPPLICABLE>No</GSTAPPLICABLE>
                    <ISBILLWISEON>No</ISBILLWISEON>
                    <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                    <ISINTERESTON>No</ISINTERESTON>
                    <ALLOWINMOBILE>No</ALLOWINMOBILE>
                    <ISCONDENSED>No</ISCONDENSED>
                    <AFFECTSSTOCK>No</AFFECTSSTOCK>
                    <FORPAYROLL>No</FORPAYROLL>
                    <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                    <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                    <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                    <USEFORVAT>No</USEFORVAT>
                    <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                    <TCSAPPLICABLE>&#4; Applicable</TCSAPPLICABLE>
                    <TDSDEDUCTEETYPE>Company - Resident</TDSDEDUCTEETYPE>
                    <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                    <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                    <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                    <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                    <USEFORGRATUITY>No</USEFORGRATUITY>
                    <FORSERVICETAX>No</FORSERVICETAX>
                    <ISINPUTCREDIT>No</ISINPUTCREDIT>
                    <ISEXEMPTED>No</ISEXEMPTED>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AUDITED>No</AUDITED>
                    <SORTPOSITION>0</SORTPOSITION>
                  </LEDGER>
                </TALLYMESSAGE>';
       
        if(!empty($retailers)){
            foreach($retailers as $detail){
                $gstNo="";
                $flag="";
                // $retailerId=$detail['id'];
                $retailerCode=$detail['retailerCode'];
                $retailerName=$detail['retailerName'];
                $name=$retailerName.' : '.$retailerCode;

                $gst=$detail['gstIn'];
                if($gst !=""){
                  $gstNo=$gst;
                  $flag="Regular";
                }else{
                  $gstNo="";
                  $flag="";
                }

                $name=str_replace('&','And',$name);
                // $name=str_replace('&','And',$name);
                // $name=str_replace('&','And',$name);

                $matter .='<TALLYMESSAGE xmlns:UDF="TallyUDF">
              <LEDGER NAME="'.$name.'" RESERVEDNAME="">
                <NAME.LIST>
                  <NAME>'.$name.'</NAME>
                </NAME.LIST>
                <ADDRESS.LIST>
                </ADDRESS.LIST>
                <ADDITIONALNAME></ADDITIONALNAME>
                <PARENT>KIAS Nestle Debtors</PARENT>
                <TAXTYPE>Others</TAXTYPE>
                <GSTAPPLICABLE>No</GSTAPPLICABLE>
                <ISBILLWISEON>No</ISBILLWISEON>
                <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                <ISINTERESTON>No</ISINTERESTON>
                <ALLOWINMOBILE>No</ALLOWINMOBILE>
                <ISCONDENSED>No</ISCONDENSED>
                <AFFECTSSTOCK>No</AFFECTSSTOCK>
                <FORPAYROLL>No</FORPAYROLL>
                <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                <USEFORVAT>No</USEFORVAT>
                <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                <TCSAPPLICABLE>&#4; Applicable</TCSAPPLICABLE>
                <TDSDEDUCTEETYPE>Company - Resident</TDSDEDUCTEETYPE>
                <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                <USEFORGRATUITY>No</USEFORGRATUITY>
                <FORSERVICETAX>No</FORSERVICETAX>
                <ISINPUTCREDIT>No</ISINPUTCREDIT>
                <ISEXEMPTED>No</ISEXEMPTED>
                <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                <AUDITED>No</AUDITED>
                <SORTPOSITION>0</SORTPOSITION>
                <COUNTRYNAME>India</COUNTRYNAME>
                <GSTREGISTRATIONTYPE>'.$flag.'</GSTREGISTRATIONTYPE>
                <VATDEALERTYPE>'.$flag.'</VATDEALERTYPE>
                <PARTYGSTIN>'.$gstNo.'</PARTYGSTIN>
                <COUNTRYOFRESIDENCE>India</COUNTRYOFRESIDENCE>
                <LEDSTATENAME>Delhi</LEDSTATENAME>
                <GSTTYPEOFSUPPLY></GSTTYPEOFSUPPLY>
              </LEDGER>
            </TALLYMESSAGE>';
          }
      }

      if(!empty($deliveryslipRetailers)){
        foreach($deliveryslipRetailers as $detail){
            $gstNo="";
            $flag="";
            // $retailerId=$detail['id'];
            $retailerCode=$detail['retailerCode'];
            $retailerName=$detail['retailerName'];
            $name='Deliveryslip : '.$retailerName.' : '.$retailerCode;

            $gst="";
            if($gst !=""){
              $gstNo=$gst;
              $flag="Regular";
            }else{
              $gstNo="";
              $flag="";
            }
            $name=str_replace('&','And',$name);
            // $name=str_replace('& ','And',$name);
            // $name=str_replace(' &','And',$name);

            // $name=str_replace(' ','',$name);
            // echo $name;exit;

            $matter .='<TALLYMESSAGE xmlns:UDF="TallyUDF">
                <LEDGER NAME="'.$name.'" RESERVEDNAME="">
                  <NAME.LIST>
                    <NAME>'.$name.'</NAME>
                  </NAME.LIST>
                  <ADDRESS.LIST>
                  </ADDRESS.LIST>
                  <ADDITIONALNAME></ADDITIONALNAME>
                  <PARENT>KIAS Nestle Debtors</PARENT>
                  <TAXTYPE>Others</TAXTYPE>
                  <GSTAPPLICABLE>No</GSTAPPLICABLE>
                  <ISBILLWISEON>No</ISBILLWISEON>
                  <ISCOSTCENTRESON>No</ISCOSTCENTRESON>
                  <ISINTERESTON>No</ISINTERESTON>
                  <ALLOWINMOBILE>No</ALLOWINMOBILE>
                  <ISCONDENSED>No</ISCONDENSED>
                  <AFFECTSSTOCK>No</AFFECTSSTOCK>
                  <FORPAYROLL>No</FORPAYROLL>
                  <INTERESTONBILLWISE>No</INTERESTONBILLWISE>
                  <OVERRIDEINTEREST>No</OVERRIDEINTEREST>
                  <OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>
                  <USEFORVAT>No</USEFORVAT>
                  <ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>
                  <TCSAPPLICABLE>&#4; Applicable</TCSAPPLICABLE>
                  <TDSDEDUCTEETYPE>Company - Resident</TDSDEDUCTEETYPE>
                  <ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>
                  <ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>
                  <ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>
                  <SHOWINPAYSLIP>No</SHOWINPAYSLIP>
                  <USEFORGRATUITY>No</USEFORGRATUITY>
                  <FORSERVICETAX>No</FORSERVICETAX>
                  <ISINPUTCREDIT>No</ISINPUTCREDIT>
                  <ISEXEMPTED>No</ISEXEMPTED>
                  <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                  <AUDITED>No</AUDITED>
                  <SORTPOSITION>0</SORTPOSITION>
                  <COUNTRYNAME>India</COUNTRYNAME>
                  <GSTREGISTRATIONTYPE>'.$flag.'</GSTREGISTRATIONTYPE>
                  <VATDEALERTYPE>'.$flag.'</VATDEALERTYPE>
                  <PARTYGSTIN>'.$gstNo.'</PARTYGSTIN>
                  <COUNTRYOFRESIDENCE>India</COUNTRYOFRESIDENCE>
                  <LEDSTATENAME>Delhi</LEDSTATENAME>
                  <GSTTYPEOFSUPPLY></GSTTYPEOFSUPPLY>
                </LEDGER>
              </TALLYMESSAGE>';
            }
        }

        $name=$distributorCode.'-Masters-'.date('d-M-Y').'.xml';
        $matter .='</REQUESTDATA>
                </IMPORTDATA>
              </BODY>
              </ENVELOPE>';

        header('Content-disposition: attachment; filename='.$name);
        header ("Content-Type:text/xml"); 
        echo  $matter;
    }

    public function invoiceExport($compData,$distributorCode){
        $matter='';
        foreach($compData as $bill){
          $billUniqueNo='Bill ID '.$bill['id'].'-'.$bill['billNo'];
          $creditUniqueNoteNo='CreditNote ID '.$bill['id'].'-'.$bill['billNo'];
          $billNo=$bill['billNo'];
          $billDate=date('Ymd',strtotime($bill['date']));
          $invoiceMatter="Invoice number ".$bill['billNo']." imported from KIAS";
         
          $retailer="";
          if($bill['isDeliverySlipBill']==1){
            $retailer='Deliveryslip : '.$bill['retailerName'].' : '.$bill['retailerCode'];
          }else{
            $retailer=$bill['retailerName'].' : '.$bill['retailerCode'];
          }
          // $retailer=$bill['retailerName'].' : '.$bill['retailerCode'];

          $retailer=str_replace('&','And',$retailer);

          $billStatus="";
          $isCancelled="";
          if($bill['deliveryStatus']=="Cancelled" || $bill['deliveryStatus'] == "cancelled"){
            $billStatus="Cancel";
            $isCancelled="Yes";
          }else{
            $billStatus="Create";
            $isCancelled="No";
          }

          $billNetAmount=$bill['billNetAmount'];
          $creditAdjustment=$bill['creditAdjustment'];
          $billCreditAdjustment=$bill['creditAdjustment'];
          $grossAmount=$bill['grossAmount'];
          $taxAmount=$bill['taxAmount'];
          $netAmount=$bill['netAmount'];

          $taxableAmount=$bill['billNetAmount']-$bill['taxAmount'];
          $roundAmountForBill=round(($billNetAmount-$taxAmount-$taxableAmount),2);

          $roundAmount=round(($netAmount+$creditAdjustment)-($billNetAmount),2);

          $creditNote1=round($billNetAmount-$netAmount,2);

          if($creditAdjustment==0){
            $creditAdjustment='';
          }else{
            $creditAdjustment='-'.$creditAdjustment;
          }

          if($roundAmount==0){
            $roundAmount='';
          }else{
            $roundAmount=''.$roundAmount;
          }

          if($roundAmountForBill==0){
            $roundAmountForBill='';
          }else{
            $roundAmountForBill=''.$roundAmountForBill;
          }

          // $matter .='<TALLYMESSAGE xmlns:UDF="TallyUDF">
          //         <VOUCHER REMOTEID="'.$billUniqueNo.'" VCHTYPE="Sales" ACTION="Create" OBJVIEW="Accounting Voucher View">
          //           <DATE>'.$billDate.'</DATE>
          //           <GUID>'.$billUniqueNo.'</GUID>
          //           <GSTREGISTRATIONTYPE></GSTREGISTRATIONTYPE>
          //           <VATDEALERTYPE></VATDEALERTYPE>
          //           <STATENAME></STATENAME>
          //           <NARRATION>'.$invoiceMatter.'</NARRATION>
          //           <COUNTRYOFRESIDENCE></COUNTRYOFRESIDENCE>
          //           <PARTYGSTIN></PARTYGSTIN>
          //           <PLACEOFSUPPLY></PLACEOFSUPPLY>
          //           <PARTYNAME>'.$retailer.'</PARTYNAME>
          //           <PARTYLEDGERNAME>'.$retailer.'</PARTYLEDGERNAME>
          //           <VOUCHERTYPENAME>KIAS Invoices</VOUCHERTYPENAME>
          //           <VOUCHERNUMBER>'.$billNo.'</VOUCHERNUMBER>
          //           <BASICBASEPARTYNAME>'.$retailer.'</BASICBASEPARTYNAME>
          //           <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
          //           <PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>
          //           <VCHGSTCLASS/>
          //           <VCHENTRYMODE>Accounting Invoice</VCHENTRYMODE>
          //           <VOUCHERTYPEORIGNAME>KIAS Invoices</VOUCHERTYPEORIGNAME>
          //           <ISOPTIONAL>No</ISOPTIONAL>
          //           <EFFECTIVEDATE>'.$billDate.'</EFFECTIVEDATE>
          //           <ISCANCELLED>No</ISCANCELLED>
          //           <ISINVOICE>Yes</ISINVOICE>
          //           <ALTERID>'.$billNo.'</ALTERID>
          //           <ALLLEDGERENTRIES.LIST>
          //             <LEDGERNAME>'.$retailer.'</LEDGERNAME>
          //             <GSTCLASS/>
          //             <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
          //             <LEDGERFROMITEM>No</LEDGERFROMITEM>
          //             <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
          //             <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
          //             <AMOUNT>-'.$netAmount.'</AMOUNT>
          //             <VATEXPAMOUNT>-'.$netAmount.'</VATEXPAMOUNT>
          //           </ALLLEDGERENTRIES.LIST>
          //           <ALLLEDGERENTRIES.LIST>
          //             <LEDGERNAME>KIAS Sales</LEDGERNAME>
          //             <GSTCLASS/>
          //             <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
          //             <LEDGERFROMITEM>No</LEDGERFROMITEM>
          //             <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
          //             <ISPARTYLEDGER>No</ISPARTYLEDGER>
          //             <AMOUNT>'.$amount.'</AMOUNT>
          //             <VATEXPAMOUNT>'.$amount.'</VATEXPAMOUNT>
          //           </ALLLEDGERENTRIES.LIST>
          //           <ALLLEDGERENTRIES.LIST>
          //             <LEDGERNAME>KIAS Credit Note</LEDGERNAME>
          //             <GSTCLASS/>
          //             <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
          //             <LEDGERFROMITEM>No</LEDGERFROMITEM>
          //             <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
          //             <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
          //             <AMOUNT>'.$creditAdjustment.'</AMOUNT>
          //             <VATEXPAMOUNT>'.$creditAdjustment.'</VATEXPAMOUNT>
          //           </ALLLEDGERENTRIES.LIST>
          //           <ALLLEDGERENTRIES.LIST>
          //             <BASICRATEOFINVOICETAX.LIST />
          //             <BASICRATEOFINVOICETAX>0</BASICRATEOFINVOICETAX>
          //             <LEDGERNAME>KIAS GST</LEDGERNAME>
          //             <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
          //             <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
          //             <LEDGERFROMITEM>No</LEDGERFROMITEM>
          //             <AMOUNT>'.$taxAmount.'</AMOUNT>
          //             <VATEXPAMOUNT>'.$taxAmount.'</VATEXPAMOUNT>
          //           </ALLLEDGERENTRIES.LIST>
          //           <ALLLEDGERENTRIES.LIST>
          //             <BASICRATEOFINVOICETAX.LIST>
          //               <BASICRATEOFINVOICETAX>
          //               </BASICRATEOFINVOICETAX>
          //             </BASICRATEOFINVOICETAX.LIST>
          //             <LEDGERNAME>KIAS Round Off</LEDGERNAME>
          //             <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
          //             <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
          //             <LEDGERFROMITEM>No</LEDGERFROMITEM>
          //             <AMOUNT>'.$roundAmount.'</AMOUNT>
          //           </ALLLEDGERENTRIES.LIST>
          //         </VOUCHER>
          //         </TALLYMESSAGE>';
          if($billNetAmount >0){
                $matter .='<TALLYMESSAGE xmlns:UDF="TallyUDF">
                <VOUCHER REMOTEID="'.$billUniqueNo.'" VCHTYPE="Sales" ACTION="'.$billStatus.'" OBJVIEW="Accounting Voucher View">
                  <DATE>'.$billDate.'</DATE>
                  <GUID>'.$billUniqueNo.'</GUID>
                  <GSTREGISTRATIONTYPE></GSTREGISTRATIONTYPE>
                  <VATDEALERTYPE></VATDEALERTYPE>
                  <STATENAME></STATENAME>
                  <NARRATION>Invoice number '.$billNo.' imported from KIAS</NARRATION>
                  <COUNTRYOFRESIDENCE></COUNTRYOFRESIDENCE>
                  <PARTYGSTIN></PARTYGSTIN>
                  <PLACEOFSUPPLY></PLACEOFSUPPLY>
                  <PARTYNAME>'.$retailer.'</PARTYNAME>
                  <PARTYLEDGERNAME>'.$retailer.'</PARTYLEDGERNAME>
                  <VOUCHERTYPENAME>KIAS Invoices</VOUCHERTYPENAME>
                  <VOUCHERNUMBER>'.$billNo.'</VOUCHERNUMBER>
                  <BASICBASEPARTYNAME>'.$retailer.'</BASICBASEPARTYNAME>
                  <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
                  <PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>
                  <VCHGSTCLASS/>
                  <VCHENTRYMODE>Accounting Invoice</VCHENTRYMODE>
                  <VOUCHERTYPEORIGNAME>KIAS Invoices</VOUCHERTYPEORIGNAME>
                  <ISOPTIONAL>No</ISOPTIONAL>
                  <EFFECTIVEDATE>'.$billDate.'</EFFECTIVEDATE>
                  <ISCANCELLED>'.$isCancelled.'</ISCANCELLED>
                  <ISINVOICE>Yes</ISINVOICE>
                  <ALTERID>'.$billNo.'</ALTERID>
                  <ALLLEDGERENTRIES.LIST>
                    <LEDGERNAME>'.$retailer.'</LEDGERNAME>
                    <GSTCLASS/>
                    <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                    <LEDGERFROMITEM>No</LEDGERFROMITEM>
                    <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                    <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                    <AMOUNT>-'.$billNetAmount.'</AMOUNT>
                    <VATEXPAMOUNT>-'.$billNetAmount.'</VATEXPAMOUNT>
                  </ALLLEDGERENTRIES.LIST>
                  <ALLLEDGERENTRIES.LIST>
                    <LEDGERNAME>KIAS Sales</LEDGERNAME>
                    <GSTCLASS/>
                    <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                    <LEDGERFROMITEM>No</LEDGERFROMITEM>
                    <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                    <ISPARTYLEDGER>No</ISPARTYLEDGER>
                    <AMOUNT>'.$taxableAmount.'</AMOUNT>
                    <VATEXPAMOUNT>'.$taxableAmount.'</VATEXPAMOUNT>
                  </ALLLEDGERENTRIES.LIST>
                  <ALLLEDGERENTRIES.LIST>
                    <BASICRATEOFINVOICETAX.LIST />
                    <BASICRATEOFINVOICETAX>0</BASICRATEOFINVOICETAX>
                    <LEDGERNAME>KIAS GST</LEDGERNAME>
                    <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                    <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                    <LEDGERFROMITEM>No</LEDGERFROMITEM>
                    <AMOUNT>'.$taxAmount.'</AMOUNT>
                    <VATEXPAMOUNT>'.$taxAmount.'</VATEXPAMOUNT>
                  </ALLLEDGERENTRIES.LIST>
                  <ALLLEDGERENTRIES.LIST>
                    <BASICRATEOFINVOICETAX.LIST>
                      <BASICRATEOFINVOICETAX>
                      </BASICRATEOFINVOICETAX>
                    </BASICRATEOFINVOICETAX.LIST>
                    <LEDGERNAME>KIAS Round Off</LEDGERNAME>
                    <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                    <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                    <LEDGERFROMITEM>No</LEDGERFROMITEM>
                    <AMOUNT>'.$roundAmountForBill.'</AMOUNT>
                  </ALLLEDGERENTRIES.LIST>
                </VOUCHER>';
              if($billCreditAdjustment > 0){
                $matter .= '<VOUCHER REMOTEID="'.$creditUniqueNoteNo.'" VCHTYPE="Credit Note" ACTION="'.$billStatus.'" OBJVIEW="Accounting Voucher View">
                    <DATE>'.$billDate.'</DATE>
                    <GUID>'.$creditUniqueNoteNo.'</GUID>
                    <GSTREGISTRATIONTYPE></GSTREGISTRATIONTYPE>
                    <VATDEALERTYPE></VATDEALERTYPE>
                    <STATENAME></STATENAME>
                    <NARRATION>Credit Note deducted in Invoice Number '.$billNo.' imported from KIAS</NARRATION>
                    <COUNTRYOFRESIDENCE></COUNTRYOFRESIDENCE>
                    <PARTYGSTIN></PARTYGSTIN>
                    <PLACEOFSUPPLY></PLACEOFSUPPLY>
                    <PARTYNAME>'.$retailer.'</PARTYNAME>
                    <PARTYLEDGERNAME>'.$retailer.'</PARTYLEDGERNAME>
                    <VOUCHERTYPENAME>KIAS Credit Note</VOUCHERTYPENAME>
                    <VOUCHERNUMBER>CN-Bill'.$bill['id'].'</VOUCHERNUMBER>
                    <BASICBASEPARTYNAME>'.$retailer.'</BASICBASEPARTYNAME>
                    <CSTFORMISSUETYPE/>
                    <CSTFORMRECVTYPE/>
                    <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
                    <PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>
                    <VCHGSTCLASS/>
                    <CONSIGNEESTATENAME></CONSIGNEESTATENAME>
                    <VCHENTRYMODE>As Voucher</VCHENTRYMODE>
                    <VOUCHERTYPEORIGNAME>KIAS Credit Note</VOUCHERTYPEORIGNAME>
                    <EFFECTIVEDATE>'.$billDate.'</EFFECTIVEDATE>
                    <ISDELETED>No</ISDELETED>
                    <ISINVOICE>No</ISINVOICE>
                    <ISVATDUTYPAID>Yes</ISVATDUTYPAID>
                    <ALTERID>CN-Bill'.$bill['id'].'</ALTERID>
                    <ALLLEDGERENTRIES.LIST>
                    <LEDGERNAME>'.$retailer.'</LEDGERNAME>
                    <GSTCLASS/>
                    <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                    <LEDGERFROMITEM>No</LEDGERFROMITEM>
                    <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                    <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                    <AMOUNT>'.$creditNote1.'</AMOUNT>
                    <VATEXPAMOUNT>'.$creditNote1.'</VATEXPAMOUNT>
                    </ALLLEDGERENTRIES.LIST>
                    <ALLLEDGERENTRIES.LIST>
                    <LEDGERNAME>KIAS Credit Adjustment</LEDGERNAME>
                    <GSTCLASS/>
                    <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                    <LEDGERFROMITEM>No</LEDGERFROMITEM>
                    <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                    <ISPARTYLEDGER>No</ISPARTYLEDGER>
                    <AMOUNT>-'.$billCreditAdjustment.'</AMOUNT>
                    <VATEXPAMOUNT>-'.$billCreditAdjustment.'</VATEXPAMOUNT>
                    </ALLLEDGERENTRIES.LIST>
                    <ALLLEDGERENTRIES.LIST>
                      <BASICRATEOFINVOICETAX.LIST>
                        <BASICRATEOFINVOICETAX>
                        </BASICRATEOFINVOICETAX>
                      </BASICRATEOFINVOICETAX.LIST>
                      <LEDGERNAME>KIAS Round Off</LEDGERNAME>
                      <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                      <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                      <LEDGERFROMITEM>No</LEDGERFROMITEM>
                      <AMOUNT>'.$roundAmount.'</AMOUNT>
                    </ALLLEDGERENTRIES.LIST> 
                </VOUCHER>
                ';
              }
              $matter .='</TALLYMESSAGE>';
          }
        }
        return $matter;
    }


    public function cashExport($data,$distributorCode){
        $matter='';
        foreach($data as $bill){
            $billUniqueNo='Payment ID '.$bill['id'].'-'.$bill['billNo'];
            $billNo=$bill['billNo'];
            $billDate=date('Ymd',strtotime($bill['date']));
            // $retailer=$bill['rtname'].' : '.$bill['rtCode'];

            $retailer="";
            if($bill['isDeliverySlipBill']==1){
              $retailer='Deliveryslip : '.$bill['rtname'].' : '.$bill['rtCode'];
            }else{
              $retailer=$bill['rtname'].' : '.$bill['rtCode'];
            }
            $retailer=str_replace('&','And',$retailer);

            $paidAmount=$bill['paidAmount'];

            //for dynamic transactions
            $employeeName="";
            if($bill['empId']>0){
                $emp=$this->ReportModel->load('employee',$bill['empId']);
                if(!empty($emp)){
                  $employeeName=$emp[0]['name'];
                }
            }

            $newPaidAmount=0;
            $narration="";
            if($paidAmount >0){
              $newPaidAmount='-'.$paidAmount;
              $narration='Cash receipt by '.$employeeName.' imported from KIAS';
            }else{
              $newPaidAmount=abs($paidAmount);
              $narration="Cash transaction edited by Owner ".$employeeName." imported from KIAS";
            }

            $narration=str_replace('&','And',$narration);
            
            $matter .='<TALLYMESSAGE xmlns:UDF="TallyUDF">
            <VOUCHER REMOTEID="'.$billUniqueNo.'" VCHTYPE="Receipt" ACTION="Create" OBJVIEW="Accounting Voucher View">
                <DATE>'.$billDate.'</DATE>
                <GUID>'.$billUniqueNo.'</GUID>
                <NARRATION>'.$narration.'</NARRATION>
                <PARTYLEDGERNAME>'.$retailer.'</PARTYLEDGERNAME>
                <VOUCHERTYPENAME>KIAS Receipt</VOUCHERTYPENAME>
                <VOUCHERNUMBER>RCP'.$bill['id'].'</VOUCHERNUMBER>
                <CSTFORMISSUETYPE/>
                <CSTFORMRECVTYPE/>
                <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
                <PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>
                <VCHGSTCLASS/>
                <VOUCHERTYPEORIGNAME>KIAS Receipt</VOUCHERTYPEORIGNAME>
                <EFFECTIVEDATE>'.$billDate.'</EFFECTIVEDATE>
                <ALTERID>RCP'.$bill['id'].'</ALTERID>
                <ALLLEDGERENTRIES.LIST>
                    <LEDGERNAME>'.$retailer.'</LEDGERNAME>
                    <GSTCLASS/>
                    <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                    <LEDGERFROMITEM>No</LEDGERFROMITEM>
                    <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                    <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                    <AMOUNT>'.$paidAmount.'</AMOUNT>
                </ALLLEDGERENTRIES.LIST>
                <ALLLEDGERENTRIES.LIST>
                    <LEDGERNAME>KIAS Cash Receipt</LEDGERNAME>
                    <GSTCLASS/>
                    <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                    <LEDGERFROMITEM>No</LEDGERFROMITEM>
                    <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                    <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                    <AMOUNT>'.$newPaidAmount.'</AMOUNT>
                </ALLLEDGERENTRIES.LIST>
                </VOUCHER>
                </TALLYMESSAGE>';
        }
        return $matter;
    }

    public function chequeReceiptExport($data,$distributorCode){
        $matter='';

        foreach($data as $bill){
          $billUniqueNo='Payment ID '.$bill['id'].'-'.$bill['billNo'];
          $billNo=$bill['billNo'];
          $billDate=date('Ymd',strtotime($bill['date']));

          $retailer="";
          if($bill['isDeliverySlipBill']==1){
            $retailer='Deliveryslip : '.$bill['rtname'].' : '.$bill['rtCode'];
          }else{
            $retailer=$bill['rtname'].' : '.$bill['rtCode'];
          }

          // $retailer=$bill['rtname'].' : '.$bill['rtCode'];
          $retailer=str_replace('&','And',$retailer);

          $paidAmount=$bill['paidAmount'];

          $chequeNo=$bill['chequeNo'];
          $chequeBank=$bill['chequeBank'];
          $chequeDate=date('Ymd',strtotime($bill['chequeDate']));
          $chequeDateFor=date('d M Y',strtotime($bill['chequeDate']));
          $reason=$bill['statusBouncedReason'];

          $chequeBank=str_replace('&','And',$chequeBank);
          
          $matter .='<TALLYMESSAGE xmlns:UDF="TallyUDF">
          <VOUCHER REMOTEID="'.$billUniqueNo.'" VCHTYPE="Receipt" ACTION="Create" OBJVIEW="Accounting Voucher View">
            <DATE>'.$billDate.'</DATE>
            <GUID>'.$billUniqueNo.'</GUID>
            <NARRATION>Cheque receipt imported from KIAS. Cheque No. '.$chequeNo.' dated '.$chequeDateFor.' drawn on '.$chequeBank.'</NARRATION>
            <PARTYLEDGERNAME>'.$retailer.'</PARTYLEDGERNAME>
            <VOUCHERTYPENAME>KIAS Receipt</VOUCHERTYPENAME>
            <VOUCHERNUMBER>RCP'.$bill['id'].'</VOUCHERNUMBER>
            <CSTFORMISSUETYPE/>
            <CSTFORMRECVTYPE/>
            <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
            <PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>
            <VCHGSTCLASS/>
            <VOUCHERTYPEORIGNAME>KIAS Receipt</VOUCHERTYPEORIGNAME>
            <EFFECTIVEDATE>'.$billDate.'</EFFECTIVEDATE>
            <ALTERID>RCP'.$bill['id'].'</ALTERID>
            <ALLLEDGERENTRIES.LIST>
                <LEDGERNAME>'.$retailer.'</LEDGERNAME>
                <GSTCLASS/>
                <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                <LEDGERFROMITEM>No</LEDGERFROMITEM>
                <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                <AMOUNT>'.$paidAmount.'</AMOUNT>
            </ALLLEDGERENTRIES.LIST>
            <ALLLEDGERENTRIES.LIST>
                <LEDGERNAME>KIAS Cheque Receipt</LEDGERNAME>
                <GSTCLASS/>
                <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                <LEDGERFROMITEM>No</LEDGERFROMITEM>
                <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                <AMOUNT>-'.$paidAmount.'</AMOUNT>
                <BANKALLOCATIONS.LIST>
                    <DATE>'.$billDate.'</DATE>
                    <INSTRUMENTDATE>'.$chequeDate.'</INSTRUMENTDATE>
                    <TRANSACTIONTYPE>Cheque/DD</TRANSACTIONTYPE>
                    <PAYMENTFAVOURING>'.$retailer.'</PAYMENTFAVOURING>
                    <INSTRUMENTNUMBER>'.$chequeNo.'</INSTRUMENTNUMBER>
                    <BANKPARTYNAME>'.$retailer.'</BANKPARTYNAME>
                    <AMOUNT>-'.$paidAmount.'</AMOUNT>
                </BANKALLOCATIONS.LIST>
            </ALLLEDGERENTRIES.LIST>
            </VOUCHER>
            </TALLYMESSAGE>';
        }
        return $matter;
    }

    public function chequeTransactionExport($data,$distributorCode){
      $matter='';

      foreach($data as $bill){
          $billUniqueNo='Payment ID '.$bill['id'].'-'.$bill['billNo'];
          $billNo=$bill['billNo'];
          $billDate=date('Ymd',strtotime($bill['date']));

          $retailer="";
          if($bill['isDeliverySlipBill']==1){
            $retailer='Deliveryslip : '.$bill['rtname'].' : '.$bill['rtCode'];
          }else{
            $retailer=$bill['rtname'].' : '.$bill['rtCode'];
          }

          // $retailer=$bill['rtname'].' : '.$bill['rtCode'];
          $retailer=str_replace('&','And',$retailer);

          $paidAmount=$bill['paidAmount'];

          $chequeNo=$bill['chequeNo'];
          $chequeBank=$bill['chequeBank'];
          $chequeDate=date('Ymd',strtotime($bill['chequeDate']));
          $chequeDateFor=date('d M Y',strtotime($bill['chequeDate']));
          $reason=$bill['statusBouncedReason'];

          if($paidAmount >0){
            $paidAmount='-'.$paidAmount;
          }else{
            $paidAmount=abs($paidAmount);
          }

          //for dynamic transactions
          $employeeName="";
          if($bill['empId']>0){
              $emp=$this->ReportModel->load('employee',$bill['empId']);
              if(!empty($emp)){
                $employeeName=$emp[0]['name'];
              }
          }
          
          $matter .='<TALLYMESSAGE xmlns:UDF="TallyUDF">
          <VOUCHER REMOTEID="'.$billUniqueNo.'" VCHTYPE="Receipt" ACTION="Create" OBJVIEW="Accounting Voucher View">
            <DATE>'.$billDate.'</DATE>
            <GUID>'.$billUniqueNo.'</GUID>
            <NARRATION>Cheque transaction changed by '.$employeeName.' imported from KIAS</NARRATION>
            <PARTYLEDGERNAME>'.$retailer.'</PARTYLEDGERNAME>
            <VOUCHERTYPENAME>KIAS Receipt</VOUCHERTYPENAME>
            <VOUCHERNUMBER>RCP'.$bill['id'].'</VOUCHERNUMBER>
            <CSTFORMISSUETYPE/>
            <CSTFORMRECVTYPE/>
            <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
            <PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>
            <VCHGSTCLASS/>
            <VOUCHERTYPEORIGNAME>KIAS Receipt</VOUCHERTYPEORIGNAME>
            <EFFECTIVEDATE>'.$billDate.'</EFFECTIVEDATE>
            <ALTERID>RCP'.$bill['id'].'</ALTERID>
            <ALLLEDGERENTRIES.LIST>
                <LEDGERNAME>'.$retailer.'</LEDGERNAME>
                <GSTCLASS/>
                <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                <LEDGERFROMITEM>No</LEDGERFROMITEM>
                <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                <AMOUNT>'.$paidAmount.'</AMOUNT>
            </ALLLEDGERENTRIES.LIST>
            <ALLLEDGERENTRIES.LIST>
                <LEDGERNAME>KIAS Cheque Receipt</LEDGERNAME>
                <GSTCLASS/>
                <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                <LEDGERFROMITEM>No</LEDGERFROMITEM>
                <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                <AMOUNT>-'.$paidAmount.'</AMOUNT>
                <BANKALLOCATIONS.LIST>
                    <DATE>'.$billDate.'</DATE>
                    <INSTRUMENTDATE>'.$chequeDate.'</INSTRUMENTDATE>
                    <TRANSACTIONTYPE>Cheque/DD</TRANSACTIONTYPE>
                    <PAYMENTFAVOURING>'.$retailer.'</PAYMENTFAVOURING>
                    <INSTRUMENTNUMBER>'.$chequeNo.'</INSTRUMENTNUMBER>
                    <BANKPARTYNAME>'.$retailer.'</BANKPARTYNAME>
                    <AMOUNT>-'.$paidAmount.'</AMOUNT>
                </BANKALLOCATIONS.LIST>
            </ALLLEDGERENTRIES.LIST>
            </VOUCHER>
            </TALLYMESSAGE>';
        }
        return $matter;
    }

    public function neftReceiptExport($data,$distributorCode){
        $matter ='';
     
        foreach($data as $bill){
          if($bill['chequeStatus'] =="" && $bill['isLostStatus'] ==1){

          }else{
            $billUniqueNo='Payment ID '.$bill['id'].'-'.$bill['billNo'];
            $billNo=$bill['billNo'];
            $billDate="";
            if($bill['date']=="" || $bill['date']=="0000-00-00 00:00:00"){
              $billDate="";
            }else{
              $billDate=date('Ymd',strtotime($bill['date']));
            }
            // $retailer=$bill['rtname'].' : '.$bill['rtCode'];

            $retailer="";
            if($bill['isDeliverySlipBill']==1){
              $retailer='Deliveryslip : '.$bill['rtname'].' : '.$bill['rtCode'];
            }else{
              $retailer=$bill['rtname'].' : '.$bill['rtCode'];
            }

            $retailer=str_replace('&','And',$retailer);

            $paidAmount=$bill['paidAmount'];

            $neftNo=$bill['neftNo'];
            $neftDate=date('d M Y',strtotime($bill['neftDate']));
            $neftDateFor=date('Ymd',strtotime($bill['neftDate']));
            $reason=$bill['statusBouncedReason'];

            //for dynamic transactions
            $employeeName="";
            if($bill['empId']>0){
                $emp=$this->ReportModel->load('employee',$bill['empId']);
                if(!empty($emp)){
                  $employeeName=$emp[0]['name'];
                }
            }

            $newPaidAmount=0;
            $narration="";
            if($paidAmount >0){
              $newPaidAmount='-'.$paidAmount;
              $narration='NEFT receipt by '.$employeeName.' imported from KIAS. Transaction No. '.$neftNo.' dated '.$neftDate;
            }else{
              $newPaidAmount=abs($paidAmount);
              $narration="NEFT transaction edited by Owner ".$employeeName." imported from KIAS";
            }

            if($bill['tallyStatus']=='NEFT_Cancel'){
                $narration="NEFT transaction cancelled by employee ".$employeeName." imported from KIAS";
            }

            $narration=str_replace('&','And',$narration);
            
            $matter .='<TALLYMESSAGE xmlns:UDF="TallyUDF">
            <VOUCHER REMOTEID="'.$billUniqueNo.'" VCHTYPE="Receipt" ACTION="Create" OBJVIEW="Accounting Voucher View">
                    <DATE>'.$billDate.'</DATE>
                    <GUID>'.$billUniqueNo.'</GUID>
                    <NARRATION>'.$narration.'</NARRATION>
                    <PARTYLEDGERNAME>'.$retailer.'</PARTYLEDGERNAME>
                    <VOUCHERTYPENAME>KIAS Receipt</VOUCHERTYPENAME>
                    <VOUCHERNUMBER>RCP'.$bill['id'].'</VOUCHERNUMBER>
                    <CSTFORMISSUETYPE/>
                    <CSTFORMRECVTYPE/>
                    <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
                    <PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>
                    <VCHGSTCLASS/>
                    <VOUCHERTYPEORIGNAME>KIAS Receipt</VOUCHERTYPEORIGNAME>
                    <EFFECTIVEDATE>'.$billDate.'</EFFECTIVEDATE>
                    <ALTERID>RCP'.$bill['id'].'</ALTERID>
                    <ALLLEDGERENTRIES.LIST>
                        <LEDGERNAME>'.$retailer.'</LEDGERNAME>
                        <GSTCLASS/>
                        <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                        <LEDGERFROMITEM>No</LEDGERFROMITEM>
                        <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                        <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                        <AMOUNT>'.$paidAmount.'</AMOUNT>
                    </ALLLEDGERENTRIES.LIST>
                    <ALLLEDGERENTRIES.LIST>
                        <LEDGERNAME>KIAS NEFT Receipt</LEDGERNAME>
                        <GSTCLASS/>
                        <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                        <LEDGERFROMITEM>No</LEDGERFROMITEM>
                        <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                        <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                        <AMOUNT>'.$newPaidAmount.'</AMOUNT>
                        <BANKALLOCATIONS.LIST>
                            <DATE>'.$billDate.'</DATE>
                            <INSTRUMENTDATE>'.$neftDateFor.'</INSTRUMENTDATE>
                            <TRANSACTIONTYPE>Same Bank Transfer</TRANSACTIONTYPE>
                            <PAYMENTFAVOURING>'.$retailer.'</PAYMENTFAVOURING>
                            <INSTRUMENTNUMBER>'.$neftNo.'</INSTRUMENTNUMBER>
                            <BANKPARTYNAME>'.$retailer.'</BANKPARTYNAME>
                            <AMOUNT>'.$newPaidAmount.'</AMOUNT>
                        </BANKALLOCATIONS.LIST>
                    </ALLLEDGERENTRIES.LIST>
                    </VOUCHER>
                   </TALLYMESSAGE>';
          }
        }
        return $matter;
    }

    public function saleReturnExport($data,$distributorCode){
        $matter ='';

        foreach($data as $bill){
          $billUniqueNo='Payment ID '.$bill['id'].'-'.$bill['billNo'];
          $billNo=$bill['billNo'];
          $billDate=date('Ymd',strtotime($bill['date']));

          $retailer="";
            if($bill['isDeliverySlipBill']==1){
              $retailer='Deliveryslip : '.$bill['rtname'].' : '.$bill['rtCode'];
            }else{
              $retailer=$bill['rtname'].' : '.$bill['rtCode'];
            }
          // $retailer=$bill['rtname'].' : '.$bill['rtCode'];
          $retailer=str_replace('&','And',$retailer);

          $paidAmount=$bill['paidAmount'];
          $taxableValue=$bill['taxableValue'];

          $taxAmount=$bill['taxAmount'];

          $roundAmount=0;
          if($taxableValue==0){
              $taxableValue=$paidAmount;
              if($paidAmount < 0){
                $tx=$paidAmount;
                $roundAmount=round($tx-$paidAmount,2);
              }else{
                $roundAmount=round($taxableValue+$taxAmount-$paidAmount,2);
              }
          }else{
              if($paidAmount < 0){
                $tx=$paidAmount;
                $roundAmount=round($tx-$paidAmount,2);
              }else{
                $roundAmount=round($taxableValue+$taxAmount-$paidAmount,2);
              }
          }
          
          if($taxableValue==0){
            if($paidAmount<0){
              $taxableValue=abs($paidAmount);
            }else{
              $taxableValue='-'.$paidAmount;
            }
          }else if($taxableValue < 0){
              $taxableValue=abs($taxableValue);
          }else{
              $taxableValue='-'.$taxableValue;
          }

          if($taxAmount >0 ){
            $taxAmount='-'.$taxAmount;
          }else if($taxAmount==0){
            $taxAmount='';
          }

          $isDeemed="Yes";
          if($roundAmount==0){
            $roundAmount='';
          }else{
            if($roundAmount >0){
              $isDeemed="No";
              // $roundAmount='-'.$roundAmount;
            }
            $roundAmount=''.$roundAmount;
          }


          $matter .='<TALLYMESSAGE xmlns:UDF="TallyUDF">
          <VOUCHER REMOTEID="'.$billUniqueNo.'" VCHTYPE="Credit Note" ACTION="Create" OBJVIEW="Accounting Voucher View">
                   <DATE>'.$billDate.'</DATE>
                   <GUID>'.$billUniqueNo.'</GUID>
                   <GSTREGISTRATIONTYPE></GSTREGISTRATIONTYPE>
                   <VATDEALERTYPE></VATDEALERTYPE>
                   <STATENAME></STATENAME>
                   <NARRATION>Sale return imported from KIAS</NARRATION>
                   <COUNTRYOFRESIDENCE></COUNTRYOFRESIDENCE>
                   <PARTYGSTIN></PARTYGSTIN>
                   <PLACEOFSUPPLY></PLACEOFSUPPLY>
                   <PARTYNAME>'.$retailer.'</PARTYNAME>
                   <PARTYLEDGERNAME>'.$retailer.'</PARTYLEDGERNAME>
                   <VOUCHERTYPENAME>KIAS Credit Note</VOUCHERTYPENAME>
                   <VOUCHERNUMBER>SR-'.$bill['id'].'</VOUCHERNUMBER>
                   <BASICBASEPARTYNAME>'.$retailer.'</BASICBASEPARTYNAME>
                   <CSTFORMISSUETYPE/>
                   <CSTFORMRECVTYPE/>
                   <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
                   <PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>
                   <VCHGSTCLASS/>
                   <CONSIGNEESTATENAME></CONSIGNEESTATENAME>
                   <VCHENTRYMODE>As Voucher</VCHENTRYMODE>
                   <VOUCHERTYPEORIGNAME>KIAS Credit Note</VOUCHERTYPEORIGNAME>
                   <EFFECTIVEDATE>'.$billDate.'</EFFECTIVEDATE>
                   <ISDELETED>No</ISDELETED>
                   <ISINVOICE>No</ISINVOICE>
                   <ISVATDUTYPAID>Yes</ISVATDUTYPAID>
                   <ALTERID>SR-'.$bill['id'].'</ALTERID>
                   <ALLLEDGERENTRIES.LIST>
                   <LEDGERNAME>'.$retailer.'</LEDGERNAME>
                   <GSTCLASS/>
                   <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                   <LEDGERFROMITEM>No</LEDGERFROMITEM>
                   <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                   <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                   <AMOUNT>'.$paidAmount.'</AMOUNT>
                   <VATEXPAMOUNT>'.$paidAmount.'</VATEXPAMOUNT>
                   </ALLLEDGERENTRIES.LIST>
                   <ALLLEDGERENTRIES.LIST>
                   <LEDGERNAME>KIAS Sales Return</LEDGERNAME>
                   <GSTCLASS/>
                   <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                   <LEDGERFROMITEM>No</LEDGERFROMITEM>
                   <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                   <ISPARTYLEDGER>No</ISPARTYLEDGER>
                   <AMOUNT>'.$taxableValue.'</AMOUNT>
                   <VATEXPAMOUNT>'.$taxableValue.'</VATEXPAMOUNT>
                   </ALLLEDGERENTRIES.LIST>
                   <ALLLEDGERENTRIES.LIST>
                     <BASICRATEOFINVOICETAX.LIST />
                     <BASICRATEOFINVOICETAX>0</BASICRATEOFINVOICETAX>
                     <LEDGERNAME>KIAS GST</LEDGERNAME>
                     <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                     <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                     <LEDGERFROMITEM>No</LEDGERFROMITEM>
                     <AMOUNT>'.$taxAmount.'</AMOUNT>
                     <VATEXPAMOUNT>'.$taxAmount.'</VATEXPAMOUNT>
                   </ALLLEDGERENTRIES.LIST>
                   <ALLLEDGERENTRIES.LIST>
                     <BASICRATEOFINVOICETAX.LIST>
                       <BASICRATEOFINVOICETAX>
                       </BASICRATEOFINVOICETAX>
                     </BASICRATEOFINVOICETAX.LIST>
                     <LEDGERNAME>KIAS Round Off</LEDGERNAME>
                     <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                     <ISDEEMEDPOSITIVE>'.$isDeemed.'</ISDEEMEDPOSITIVE>
                     <LEDGERFROMITEM>No</LEDGERFROMITEM>
                     <AMOUNT>'.$roundAmount.'</AMOUNT>
                   </ALLLEDGERENTRIES.LIST>
                </VOUCHER>
                </TALLYMESSAGE>';
        }
        return $matter;
    }

    public function fullSaleReturnExport($data,$distributorCode){
      $matter ='';

      foreach($data as $bill){
          $billUniqueNo='FSR ID '.$bill['id'].'-'.$bill['billNo'];
          $billNo=$bill['billNo'];
          $billDate=date('Ymd',strtotime($bill['date']));
          $invoiceMatter="Invoice number ".$bill['billNo']." imported from KIAS";
          // $retailer=$bill['rtname'].' : '.$bill['rtCode'];

          $retailer="";
            if($bill['isDeliverySlipBill']==1){
              $retailer='Deliveryslip : '.$bill['rtname'].' : '.$bill['rtCode'];
            }else{
              $retailer=$bill['rtname'].' : '.$bill['rtCode'];
            }

          $retailer=str_replace('&','And',$retailer);


          $billData=$this->ReportModel->load('bills',$bill['bid']);

          $billNetAmount=$billData[0]['billNetAmount'];
          $billCreditAdjustment=$billData[0]['creditAdjustment'];
          $creditAdjustment=$billData[0]['creditAdjustment'];
          $grossAmount=$billData[0]['grossAmount'];
          $taxAmount=$billData[0]['taxAmount'];
          $netAmount=$billData[0]['netAmount'];
          
          $taxableAmount=$billData[0]['billNetAmount']-$billData[0]['taxAmount'];
          $roundAmountForBill=round(($billNetAmount-$taxAmount-$taxableAmount),2); 

          $roundAmount=round(($netAmount+$creditAdjustment)-($billNetAmount),2);

          $creditNote1=$billNetAmount-($netAmount);

          if($creditAdjustment==0){
            $creditAdjustment='';
          }else{
            $creditAdjustment=''.$creditAdjustment;
          }

          if($roundAmount==0){
            $roundAmount='';
          }else if($roundAmount < 0){
            $roundAmount=abs($roundAmount);
          }else{
            $roundAmount='-'.$roundAmount;
          }

          // $finalRoundAmt='';
          // if($roundAmount < 0){
          //   $finalRoundAmt = abs($roundAmount);
          // }else{
          //   $finalRoundAmt = $roundAmount;
          // }

          if($roundAmountForBill==0){
            $roundAmountForBill='';
          }else{
            $roundAmountForBill=''.$roundAmountForBill;
          }


        // $matter .='<TALLYMESSAGE xmlns:UDF="TallyUDF">
        //       <VOUCHER REMOTEID="'.$billUniqueNo.'" VCHTYPE="Credit Note" ACTION="Create" OBJVIEW="Accounting Voucher View">
        //         <DATE>'.$billDate.'</DATE>
        //         <GUID>'.$billUniqueNo.'</GUID>
        //         <GSTREGISTRATIONTYPE></GSTREGISTRATIONTYPE>
        //         <VATDEALERTYPE></VATDEALERTYPE>
        //         <STATENAME></STATENAME>
        //         <NARRATION>Full Sale return imported from KIAS</NARRATION>
        //         <COUNTRYOFRESIDENCE></COUNTRYOFRESIDENCE>
        //         <PARTYGSTIN></PARTYGSTIN>
        //         <PLACEOFSUPPLY></PLACEOFSUPPLY>
        //         <PARTYNAME>'.$retailer.'</PARTYNAME>
        //         <PARTYLEDGERNAME>'.$retailer.'</PARTYLEDGERNAME>
        //         <VOUCHERTYPENAME>KIAS Credit Note</VOUCHERTYPENAME>
        //         <VOUCHERNUMBER>FSR-'.$bill['id'].'</VOUCHERNUMBER>
        //         <BASICBASEPARTYNAME>'.$retailer.'</BASICBASEPARTYNAME>
        //         <CSTFORMISSUETYPE/>
        //         <CSTFORMRECVTYPE/>
        //         <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
        //         <PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>
        //         <VCHGSTCLASS/>
        //         <CONSIGNEESTATENAME></CONSIGNEESTATENAME>
        //         <VCHENTRYMODE>As Voucher</VCHENTRYMODE>
        //         <VOUCHERTYPEORIGNAME>KIAS Credit Note</VOUCHERTYPEORIGNAME>
        //         <EFFECTIVEDATE>'.$billDate.'</EFFECTIVEDATE>
        //         <ISDELETED>No</ISDELETED>
        //         <ISINVOICE>No</ISINVOICE>
        //         <ISVATDUTYPAID>Yes</ISVATDUTYPAID>
        //         <ALTERID>FSR-'.$bill['id'].'</ALTERID>
        //         <ALLLEDGERENTRIES.LIST>
        //         <LEDGERNAME>'.$retailer.'</LEDGERNAME>
        //         <GSTCLASS/>
        //         <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
        //         <LEDGERFROMITEM>No</LEDGERFROMITEM>
        //         <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
        //         <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
        //         <AMOUNT>'.$netAmount.'</AMOUNT>
        //         <VATEXPAMOUNT>'.$netAmount.'</VATEXPAMOUNT>
        //         </ALLLEDGERENTRIES.LIST>
        //         <ALLLEDGERENTRIES.LIST>
        //         <LEDGERNAME>KIAS Sales Return</LEDGERNAME>
        //         <GSTCLASS/>
        //         <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
        //         <LEDGERFROMITEM>No</LEDGERFROMITEM>
        //         <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
        //         <ISPARTYLEDGER>No</ISPARTYLEDGER>
        //         <AMOUNT>-'.$amount.'</AMOUNT>
        //         <VATEXPAMOUNT>-'.$amount.'</VATEXPAMOUNT>
        //         </ALLLEDGERENTRIES.LIST>
        //         <ALLLEDGERENTRIES.LIST>
        //           <LEDGERNAME>KIAS Credit Note</LEDGERNAME>
        //           <GSTCLASS/>
        //           <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
        //           <LEDGERFROMITEM>No</LEDGERFROMITEM>
        //           <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
        //           <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
        //           <AMOUNT>'.$creditAdjustment.'</AMOUNT>
        //           <VATEXPAMOUNT>'.$creditAdjustment.'</VATEXPAMOUNT>
        //         </ALLLEDGERENTRIES.LIST>
        //         <ALLLEDGERENTRIES.LIST>
        //           <BASICRATEOFINVOICETAX.LIST />
        //           <BASICRATEOFINVOICETAX>0</BASICRATEOFINVOICETAX>
        //           <LEDGERNAME>KIAS GST</LEDGERNAME>
        //           <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
        //           <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
        //           <LEDGERFROMITEM>No</LEDGERFROMITEM>
        //           <AMOUNT>-'.$taxAmount.'</AMOUNT>
        //           <VATEXPAMOUNT>-'.$taxAmount.'</VATEXPAMOUNT>
        //         </ALLLEDGERENTRIES.LIST>
        //         <ALLLEDGERENTRIES.LIST>
        //           <BASICRATEOFINVOICETAX.LIST>
        //             <BASICRATEOFINVOICETAX>
        //             </BASICRATEOFINVOICETAX>
        //           </BASICRATEOFINVOICETAX.LIST>
        //           <LEDGERNAME>KIAS Round Off</LEDGERNAME>
        //           <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
        //           <ISDEEMEDPOSITIVE>'.$isDeemed.'</ISDEEMEDPOSITIVE>
        //           <LEDGERFROMITEM>No</LEDGERFROMITEM>
        //           <AMOUNT>'.$roundAmount.'</AMOUNT>
        //         </ALLLEDGERENTRIES.LIST>
        //     </VOUCHER>
        //     </TALLYMESSAGE>';

       

        $matter .='<TALLYMESSAGE xmlns:UDF="TallyUDF">
            <VOUCHER REMOTEID="FullSaleReturn-'.$billUniqueNo.'" VCHTYPE="Credit Note" ACTION="Create" OBJVIEW="Accounting Voucher View">
              <DATE>'.$billDate.'</DATE>
              <GUID>FullSaleReturn-'.$billUniqueNo.'</GUID>
              <GSTREGISTRATIONTYPE></GSTREGISTRATIONTYPE>
              <VATDEALERTYPE></VATDEALERTYPE>
              <STATENAME></STATENAME>
              <NARRATION>Full Sale return imported from KIAS</NARRATION>
              <COUNTRYOFRESIDENCE></COUNTRYOFRESIDENCE>
              <PARTYGSTIN></PARTYGSTIN>
              <PLACEOFSUPPLY></PLACEOFSUPPLY>
              <PARTYNAME>'.$retailer.'</PARTYNAME>
              <PARTYLEDGERNAME>'.$retailer.'</PARTYLEDGERNAME>
              <VOUCHERTYPENAME>KIAS Credit Note</VOUCHERTYPENAME>
              <VOUCHERNUMBER>FSR-'.$bill['id'].'</VOUCHERNUMBER>
              <BASICBASEPARTYNAME>'.$retailer.'</BASICBASEPARTYNAME>
              <CSTFORMISSUETYPE/>
              <CSTFORMRECVTYPE/>
              <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
              <PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>
              <VCHGSTCLASS/>
              <CONSIGNEESTATENAME></CONSIGNEESTATENAME>
              <VCHENTRYMODE>As Voucher</VCHENTRYMODE>
              <VOUCHERTYPEORIGNAME>KIAS Credit Note</VOUCHERTYPEORIGNAME>
              <EFFECTIVEDATE>'.$billDate.'</EFFECTIVEDATE>
              <ISDELETED>No</ISDELETED>
              <ISINVOICE>No</ISINVOICE>
              <ISVATDUTYPAID>Yes</ISVATDUTYPAID>
              <ALTERID>FSR-'.$bill['id'].'</ALTERID>
              <ALLLEDGERENTRIES.LIST>
              <LEDGERNAME>'.$retailer.'</LEDGERNAME>
              <GSTCLASS/>
              <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
              <LEDGERFROMITEM>No</LEDGERFROMITEM>
              <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
              <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
              <AMOUNT>'.$billNetAmount.'</AMOUNT>
              <VATEXPAMOUNT>'.$billNetAmount.'</VATEXPAMOUNT>
              </ALLLEDGERENTRIES.LIST>
              <ALLLEDGERENTRIES.LIST>
              <LEDGERNAME>KIAS Sales Return</LEDGERNAME>
              <GSTCLASS/>
              <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
              <LEDGERFROMITEM>No</LEDGERFROMITEM>
              <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
              <ISPARTYLEDGER>No</ISPARTYLEDGER>
              <AMOUNT>-'.$taxableAmount.'</AMOUNT>
              <VATEXPAMOUNT>-'.$taxableAmount.'</VATEXPAMOUNT>
              </ALLLEDGERENTRIES.LIST>
              <ALLLEDGERENTRIES.LIST>
                <BASICRATEOFINVOICETAX.LIST />
                <BASICRATEOFINVOICETAX>0</BASICRATEOFINVOICETAX>
                <LEDGERNAME>KIAS GST</LEDGERNAME>
                <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                <LEDGERFROMITEM>No</LEDGERFROMITEM>
                <AMOUNT>-'.$taxAmount.'</AMOUNT>
                <VATEXPAMOUNT>-'.$taxAmount.'</VATEXPAMOUNT>
              </ALLLEDGERENTRIES.LIST>
              <ALLLEDGERENTRIES.LIST>
                <BASICRATEOFINVOICETAX.LIST>
                  <BASICRATEOFINVOICETAX>
                  </BASICRATEOFINVOICETAX>
                </BASICRATEOFINVOICETAX.LIST>
                <LEDGERNAME>KIAS Round Off</LEDGERNAME>
                <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                <LEDGERFROMITEM>No</LEDGERFROMITEM>
                <AMOUNT>'.$roundAmountForBill.'</AMOUNT>
              </ALLLEDGERENTRIES.LIST>
            </VOUCHER>';

            if($billCreditAdjustment >0){
              $matter .='<VOUCHER REMOTEID="DebitNote-'.$bill['id'].'" VCHTYPE="Debit Note" ACTION="Create" OBJVIEW="Accounting Voucher View">
                    <DATE>'.$billDate.'</DATE>
                    <GUID>DebitNote-'.$bill['id'].'</GUID>
                    <GSTREGISTRATIONTYPE></GSTREGISTRATIONTYPE>
                    <VATDEALERTYPE></VATDEALERTYPE>
                    <STATENAME></STATENAME>
                    <NARRATION>Debit Note due to FSR of Invoice Number '.$billNo.' imported from KIAS</NARRATION>
                    <COUNTRYOFRESIDENCE></COUNTRYOFRESIDENCE>
                    <PARTYGSTIN></PARTYGSTIN>
                    <PLACEOFSUPPLY></PLACEOFSUPPLY>
                    <PARTYNAME>'.$retailer.'</PARTYNAME>
                    <PARTYLEDGERNAME>'.$retailer.'</PARTYLEDGERNAME>
                    <VOUCHERTYPENAME>KIAS Debit Note</VOUCHERTYPENAME>
                    <VOUCHERNUMBER>DN-'.$bill['id'].'</VOUCHERNUMBER>
                    <BASICBASEPARTYNAME>'.$retailer.'</BASICBASEPARTYNAME>
                    <CSTFORMISSUETYPE/>
                    <CSTFORMRECVTYPE/>
                    <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
                    <PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>
                    <VCHGSTCLASS/>
                    <CONSIGNEESTATENAME></CONSIGNEESTATENAME>
                    <VCHENTRYMODE>As Voucher</VCHENTRYMODE>
                    <VOUCHERTYPEORIGNAME>KIAS Debit Note</VOUCHERTYPEORIGNAME>
                    <EFFECTIVEDATE>'.$billDate.'</EFFECTIVEDATE>
                    <ISDELETED>No</ISDELETED>
                    <ISINVOICE>No</ISINVOICE>
                    <ISVATDUTYPAID>Yes</ISVATDUTYPAID>
                    <ALTERID>DN-'.$bill['id'].'</ALTERID>
                    <ALLLEDGERENTRIES.LIST>
                    <LEDGERNAME>'.$retailer.'</LEDGERNAME>
                    <GSTCLASS/>
                    <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                    <LEDGERFROMITEM>No</LEDGERFROMITEM>
                    <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                    <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                    <AMOUNT>-'.$creditNote1.'</AMOUNT>
                    <VATEXPAMOUNT>-'.$creditNote1.'</VATEXPAMOUNT>
                    </ALLLEDGERENTRIES.LIST>
                    <ALLLEDGERENTRIES.LIST>
                    <LEDGERNAME>KIAS Credit Adjustment</LEDGERNAME>
                    <GSTCLASS/>
                    <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                    <LEDGERFROMITEM>No</LEDGERFROMITEM>
                    <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                    <ISPARTYLEDGER>No</ISPARTYLEDGER>
                    <AMOUNT>'.$creditAdjustment.'</AMOUNT>
                    <VATEXPAMOUNT>'.$creditAdjustment.'</VATEXPAMOUNT>
                    </ALLLEDGERENTRIES.LIST>
                    <ALLLEDGERENTRIES.LIST>
                      <BASICRATEOFINVOICETAX.LIST>
                        <BASICRATEOFINVOICETAX>
                        </BASICRATEOFINVOICETAX>
                      </BASICRATEOFINVOICETAX.LIST>
                      <LEDGERNAME>KIAS Round Off</LEDGERNAME>
                      <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                      <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                      <LEDGERFROMITEM>No</LEDGERFROMITEM>
                      <AMOUNT>'.$roundAmount.'</AMOUNT>
                    </ALLLEDGERENTRIES.LIST>
                </VOUCHER>';
            }

          $matter .='</TALLYMESSAGE>';
      }
      return $matter;
  }

    

    public function chequeBounceExport($data,$distributorCode){
        $matter ='';

        foreach($data as $bill){
          $billUniqueNo='Payment ID '.$bill['id'].'-'.$bill['billNo'];
          $billNo=$bill['billNo'];

          $billDate="";
          if($bill['date']=="" || $bill['date']=="0000-00-00 00:00:00"){
            $billDate="";
          }else{
            $billDate=date('Ymd',strtotime($bill['date']));
          }

          // $retailer=$bill['rtname'].' : '.$bill['rtCode'];

          $retailer="";
            if($bill['isDeliverySlipBill']==1){
              $retailer='Deliveryslip : '.$bill['rtname'].' : '.$bill['rtCode'];
            }else{
              $retailer=$bill['rtname'].' : '.$bill['rtCode'];
            }

          $retailer=str_replace('&','And',$retailer);

          $paidAmount=$bill['paidAmount'];

          $chequeNo=$bill['chequeNo'];
          $chequeDate=date('Ymd',strtotime($bill['chequeDate']));
          $reason=$bill['statusBouncedReason'];

          $newPaidAmount=$paidAmount;

          $positivePaidAmt=0;
          if($paidAmount<0){
            $positivePaidAmt=abs($paidAmount);
          }

          $reason=str_replace('&','And',$reason);

          $matter .='<TALLYMESSAGE xmlns:UDF="TallyUDF">
          <VOUCHER REMOTEID="'.$billUniqueNo.'" VCHTYPE="Payment" ACTION="Create" OBJVIEW="Accounting Voucher View">
                   <DATE>'.$billDate.'</DATE>
                   <GUID>'.$billUniqueNo.'</GUID>
                   <GSTREGISTRATIONTYPE></GSTREGISTRATIONTYPE>
                   <VATDEALERTYPE></VATDEALERTYPE>
                   <STATENAME></STATENAME>
                   <NARRATION>Cheque bounce imported from KIAS. Cheque No '.$chequeNo.' bounced. Reason: '.$reason.'</NARRATION>
                   <COUNTRYOFRESIDENCE></COUNTRYOFRESIDENCE>
                   <PARTYGSTIN></PARTYGSTIN>
                   <PLACEOFSUPPLY></PLACEOFSUPPLY>
                   <PARTYNAME>'.$retailer.'</PARTYNAME>
                   <PARTYLEDGERNAME>'.$retailer.'</PARTYLEDGERNAME>
                   <VOUCHERTYPENAME>KIAS Payment</VOUCHERTYPENAME>
                   <VOUCHERNUMBER>CB-'.$bill['id'].'</VOUCHERNUMBER>
                   <BASICBASEPARTYNAME>'.$retailer.'</BASICBASEPARTYNAME>
                   <CSTFORMISSUETYPE/>
                   <CSTFORMRECVTYPE/>
                   <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
                   <PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>
                   <VCHGSTCLASS/>
                   <CONSIGNEESTATENAME></CONSIGNEESTATENAME>
                   <VCHENTRYMODE>As Voucher</VCHENTRYMODE>
                   <VOUCHERTYPEORIGNAME>KIAS Payment</VOUCHERTYPEORIGNAME>
                   <EFFECTIVEDATE>'.$billDate.'</EFFECTIVEDATE>
                   <ISDELETED>No</ISDELETED>
                   <ISINVOICE>No</ISINVOICE>
                   <ISVATDUTYPAID>Yes</ISVATDUTYPAID>
                   <ALTERID>CB-'.$bill['id'].'</ALTERID>
                   <ALLLEDGERENTRIES.LIST>
                   <LEDGERNAME>'.$retailer.'</LEDGERNAME>
                   <GSTCLASS/>
                   <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                   <LEDGERFROMITEM>No</LEDGERFROMITEM>
                   <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                   <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                   <AMOUNT>'.$paidAmount.'</AMOUNT>
                   <VATEXPAMOUNT>'.$paidAmount.'</VATEXPAMOUNT>
                   </ALLLEDGERENTRIES.LIST>
                   <ALLLEDGERENTRIES.LIST>
                   <LEDGERNAME>KIAS Cheque Receipt</LEDGERNAME>
                   <GSTCLASS/>
                   <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                   <LEDGERFROMITEM>No</LEDGERFROMITEM>
                   <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                   <ISPARTYLEDGER>No</ISPARTYLEDGER>
                   <AMOUNT>'.$positivePaidAmt.'</AMOUNT>
                   <VATEXPAMOUNT>'.$positivePaidAmt.'</VATEXPAMOUNT>
                         <BANKALLOCATIONS.LIST>
                           <DATE>'.$billDate.'</DATE>
                           <INSTRUMENTDATE>'.$chequeDate.'</INSTRUMENTDATE>
                           <TRANSACTIONTYPE>Cheque/DD</TRANSACTIONTYPE>
                           <PAYMENTFAVOURING>'.$retailer.'</PAYMENTFAVOURING>
                           <INSTRUMENTNUMBER>'.$chequeNo.'</INSTRUMENTNUMBER>
                           <BANKPARTYNAME>'.$retailer.'</BANKPARTYNAME>
                           <AMOUNT>'.$newPaidAmount.'</AMOUNT>
                       </BANKALLOCATIONS.LIST>
                   </ALLLEDGERENTRIES.LIST>
                </VOUCHER>
                </TALLYMESSAGE>';
        }
        return $matter;
    }

    public function chequeBouncePenalty($data,$distributorCode){
        $matter ='';

        foreach($data as $bill){
          $billUniqueNo='Payment ID '.$bill['id'].'-'.$bill['billNo'];
          $billNo=$bill['billNo'];

          $billDate="";
          if($bill['date']=="" || $bill['date']=="0000-00-00 00:00:00"){
            $billDate="";
          }else{
            $billDate=date('Ymd',strtotime($bill['date']));
          }

          // $retailer=$bill['rtname'].' : '.$bill['rtCode'];

          $retailer="";
            if($bill['isDeliverySlipBill']==1){
              $retailer='Deliveryslip : '.$bill['rtname'].' : '.$bill['rtCode'];
            }else{
              $retailer=$bill['rtname'].' : '.$bill['rtCode'];
            }

          $retailer=str_replace('&','And',$retailer);

          $paidAmount=$bill['paidAmount'];

          $chequeNo=$bill['chequeNo'];
          $chequeDate=date('Ymd',strtotime($bill['chequeDate']));
          $reason=$bill['statusBouncedReason'];

          $newPaidAmount=$paidAmount;

          $positivePaidAmt=0;
          if($paidAmount<0){
            $positivePaidAmt=abs($paidAmount);
          }

          $reason=str_replace('&','And',$reason);

          if($paidAmount !=0){
            $matter .='<TALLYMESSAGE xmlns:UDF="TallyUDF">
            <VOUCHER REMOTEID="'.$billUniqueNo.'" VCHTYPE="Debit Note" ACTION="Create" OBJVIEW="Accounting Voucher View">
                      <DATE>'.$billDate.'</DATE>
                      <GUID>'.$billUniqueNo.'</GUID>
                      <GSTREGISTRATIONTYPE></GSTREGISTRATIONTYPE>
                      <VATDEALERTYPE></VATDEALERTYPE>
                      <STATENAME></STATENAME>
                      <NARRATION>Cheque bounce penalty imported from KIAS. Cheque Number '.$chequeNo.' bounced. Reason: '.$reason.'</NARRATION>
                      <COUNTRYOFRESIDENCE></COUNTRYOFRESIDENCE>
                      <PARTYGSTIN></PARTYGSTIN>
                      <PLACEOFSUPPLY></PLACEOFSUPPLY>
                      <PARTYNAME>'.$retailer.'</PARTYNAME>
                      <PARTYLEDGERNAME>'.$retailer.'</PARTYLEDGERNAME>
                      <VOUCHERTYPENAME>KIAS Debit Note</VOUCHERTYPENAME>
                      <VOUCHERNUMBER>CBP-'.$bill['id'].'</VOUCHERNUMBER>
                      <BASICBASEPARTYNAME>'.$retailer.'</BASICBASEPARTYNAME>
                      <CSTFORMISSUETYPE/>
                      <CSTFORMRECVTYPE/>
                      <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
                      <PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>
                      <VCHGSTCLASS/>
                      <CONSIGNEESTATENAME></CONSIGNEESTATENAME>
                      <VCHENTRYMODE>As Voucher</VCHENTRYMODE>
                      <VOUCHERTYPEORIGNAME>KIAS Debit Note</VOUCHERTYPEORIGNAME>
                      <EFFECTIVEDATE>'.$billDate.'</EFFECTIVEDATE>
                      <ISDELETED>No</ISDELETED>
                      <ISINVOICE>No</ISINVOICE>
                      <ISVATDUTYPAID>Yes</ISVATDUTYPAID>
                      <ALTERID>CBP-'.$bill['id'].'</ALTERID>
                      <ALLLEDGERENTRIES.LIST>
                      <LEDGERNAME>'.$retailer.'</LEDGERNAME>
                      <GSTCLASS/>
                      <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                      <LEDGERFROMITEM>No</LEDGERFROMITEM>
                      <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                      <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                      <AMOUNT>'.$paidAmount.'</AMOUNT>
                      <VATEXPAMOUNT>'.$paidAmount.'</VATEXPAMOUNT>
                      </ALLLEDGERENTRIES.LIST>
                      <ALLLEDGERENTRIES.LIST>
                      <LEDGERNAME>KIAS Cheque Bounce Penalty</LEDGERNAME>
                      <GSTCLASS/>
                      <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                      <LEDGERFROMITEM>No</LEDGERFROMITEM>
                      <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                      <ISPARTYLEDGER>No</ISPARTYLEDGER>
                      <AMOUNT>'.$positivePaidAmt.'</AMOUNT>
                      <VATEXPAMOUNT>'.$positivePaidAmt.'</VATEXPAMOUNT>
                      </ALLLEDGERENTRIES.LIST>
                   </VOUCHER>
                   </TALLYMESSAGE>';
          }

          
        }
        return $matter;
    }

    public function officeAdjustmentExport($data,$distributorCode){
        $matter='';
        foreach($data as $bill){
            $billUniqueNo='Payment ID '.$bill['id'].'-'.$bill['billNo'];
            $billNo=$bill['billNo'];
            $billDate=date('Ymd',strtotime($bill['date']));
            // $retailer=$bill['rtname'].' : '.$bill['rtCode'];

            $retailer="";
            if($bill['isDeliverySlipBill']==1){
              $retailer='Deliveryslip : '.$bill['rtname'].' : '.$bill['rtCode'];
            }else{
              $retailer=$bill['rtname'].' : '.$bill['rtCode'];
            }

            $retailer=str_replace('&','And',$retailer);

            $paidAmount=$bill['paidAmount'];

            //for dynamic transactions
            $employeeName="";
            if($bill['empId']>0){
                $emp=$this->ReportModel->load('employee',$bill['empId']);
                if(!empty($emp)){
                  $employeeName=$emp[0]['name'];
                }
            }

            $newPaidAmount=0;
            $narration="";
            if($paidAmount >0){
              $newPaidAmount='-'.$paidAmount;
              $narration="Office Adjustment imported from KIAS";
            }else{
              $newPaidAmount=abs($paidAmount);
              $narration="Office Adjustment transaction edited by Owner ".$employeeName." imported from KIAS";
            }

            $narration=str_replace('&','And',$narration);

            // $matter .='<TALLYMESSAGE xmlns:UDF="TallyUDF">
            // <VOUCHER REMOTEID="'.$billUniqueNo.'" VCHTYPE="Receipt" ACTION="Create" OBJVIEW="Accounting Voucher View">
            //     <DATE>'.$billDate.'</DATE>
            //     <GUID>'.$billUniqueNo.'</GUID>
            //     <NARRATION>'.$narration.'</NARRATION>
            //     <PARTYLEDGERNAME>'.$retailer.'</PARTYLEDGERNAME>
            //     <VOUCHERTYPENAME>KIAS Receipt</VOUCHERTYPENAME>
            //     <VOUCHERNUMBER>RCP'.$bill['id'].'</VOUCHERNUMBER>
            //     <CSTFORMISSUETYPE/>
            //     <CSTFORMRECVTYPE/>
            //     <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
            //     <PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>
            //     <VCHGSTCLASS/>
            //     <VOUCHERTYPEORIGNAME>KIAS Receipt</VOUCHERTYPEORIGNAME>
            //     <EFFECTIVEDATE>'.$billDate.'</EFFECTIVEDATE>
            //     <ALTERID>RCP'.$bill['id'].'</ALTERID>
            //     <ALLLEDGERENTRIES.LIST>
            //         <LEDGERNAME>'.$retailer.'</LEDGERNAME>
            //         <GSTCLASS/>
            //         <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
            //         <LEDGERFROMITEM>No</LEDGERFROMITEM>
            //         <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
            //         <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
            //         <AMOUNT>'.$paidAmount.'</AMOUNT>
            //     </ALLLEDGERENTRIES.LIST>
            //     <ALLLEDGERENTRIES.LIST>
            //         <LEDGERNAME>KIAS Office Adjustment</LEDGERNAME>
            //         <GSTCLASS/>
            //         <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
            //         <LEDGERFROMITEM>No</LEDGERFROMITEM>
            //         <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
            //         <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
            //         <AMOUNT>'.$newPaidAmount.'</AMOUNT>
            //     </ALLLEDGERENTRIES.LIST>
            //     </VOUCHER>
            //     </TALLYMESSAGE>';

                $matter .='<TALLYMESSAGE xmlns:UDF="TallyUDF">
          <VOUCHER REMOTEID="'.$billUniqueNo.'" VCHTYPE="Credit Note" ACTION="Create" OBJVIEW="Accounting Voucher View">
                   <DATE>'.$billDate.'</DATE>
                   <GUID>'.$billUniqueNo.'</GUID>
                   <GSTREGISTRATIONTYPE></GSTREGISTRATIONTYPE>
                   <VATDEALERTYPE></VATDEALERTYPE>
                   <STATENAME></STATENAME>
                   <NARRATION>'.$narration.'</NARRATION>
                   <COUNTRYOFRESIDENCE></COUNTRYOFRESIDENCE>
                   <PARTYGSTIN></PARTYGSTIN>
                   <PLACEOFSUPPLY></PLACEOFSUPPLY>
                   <PARTYNAME>'.$retailer.'</PARTYNAME>
                   <PARTYLEDGERNAME>'.$retailer.'</PARTYLEDGERNAME>
                   <VOUCHERTYPENAME>KIAS Credit Note</VOUCHERTYPENAME>
                   <VOUCHERNUMBER>OFC-ADJ-'.$bill['id'].'</VOUCHERNUMBER>
                   <BASICBASEPARTYNAME>'.$retailer.'</BASICBASEPARTYNAME>
                   <CSTFORMISSUETYPE/>
                   <CSTFORMRECVTYPE/>
                   <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
                   <PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>
                   <VCHGSTCLASS/>
                   <CONSIGNEESTATENAME></CONSIGNEESTATENAME>
                   <VCHENTRYMODE>As Voucher</VCHENTRYMODE>
                   <VOUCHERTYPEORIGNAME>KIAS Credit Note</VOUCHERTYPEORIGNAME>
                   <EFFECTIVEDATE>'.$billDate.'</EFFECTIVEDATE>
                   <ISDELETED>No</ISDELETED>
                   <ISINVOICE>No</ISINVOICE>
                   <ISVATDUTYPAID>Yes</ISVATDUTYPAID>
                   <ALTERID>OFC-ADJ-'.$bill['id'].'</ALTERID>
                   <ALLLEDGERENTRIES.LIST>
                   <LEDGERNAME>'.$retailer.'</LEDGERNAME>
                   <GSTCLASS/>
                   <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                   <LEDGERFROMITEM>No</LEDGERFROMITEM>
                   <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                   <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                   <AMOUNT>'.$paidAmount.'</AMOUNT>
                   <VATEXPAMOUNT>'.$paidAmount.'</VATEXPAMOUNT>
                   </ALLLEDGERENTRIES.LIST>
                   <ALLLEDGERENTRIES.LIST>
                   <LEDGERNAME>KIAS Office Adjustment</LEDGERNAME>
                   <GSTCLASS/>
                   <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                   <LEDGERFROMITEM>No</LEDGERFROMITEM>
                   <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                   <ISPARTYLEDGER>No</ISPARTYLEDGER>
                   <AMOUNT>'.$newPaidAmount.'</AMOUNT>
                   <VATEXPAMOUNT>'.$newPaidAmount.'</VATEXPAMOUNT>
                   </ALLLEDGERENTRIES.LIST>
                </VOUCHER>
                </TALLYMESSAGE>';
        }
        return $matter;
    }

    public function otherAdjustmentExport($data,$distributorCode){
        $matter='';
        foreach($data as $bill){
            $billUniqueNo='Payment ID '.$bill['id'].'-'.$bill['billNo'];
            $billNo=$bill['billNo'];
            $billDate=date('Ymd',strtotime($bill['date']));
            // $retailer=$bill['rtname'].' : '.$bill['rtCode'];

            $retailer="";
            if($bill['isDeliverySlipBill']==1){
              $retailer='Deliveryslip : '.$bill['rtname'].' : '.$bill['rtCode'];
            }else{
              $retailer=$bill['rtname'].' : '.$bill['rtCode'];
            }

            $retailer=str_replace('&','And',$retailer);

            $paidAmount=$bill['paidAmount'];

            //for dynamic transactions
            $employeeName="";
            if($bill['empId']>0){
                $emp=$this->ReportModel->load('employee',$bill['empId']);
                if(!empty($emp)){
                  $employeeName=$emp[0]['name'];
                }
            }

            $newPaidAmount=0;
            $narration="";
            if($paidAmount >0){
                $newPaidAmount='-'.$paidAmount;
                $narration="Other Adjustment imported from KIAS";
            }else{
                $newPaidAmount=abs($paidAmount);
                $narration="Other Adjustment transaction edited by Owner ".$employeeName." imported from KIAS";
            }

            $narration=str_replace('&','And',$narration);
            
            // $matter .='<TALLYMESSAGE xmlns:UDF="TallyUDF">
            // <VOUCHER REMOTEID="'.$billUniqueNo.'" VCHTYPE="Receipt" ACTION="Create" OBJVIEW="Accounting Voucher View">
            //     <DATE>'.$billDate.'</DATE>
            //     <GUID>'.$billUniqueNo.'</GUID>
            //     <NARRATION>'.$narration.'</NARRATION>
            //     <PARTYLEDGERNAME>'.$retailer.'</PARTYLEDGERNAME>
            //     <VOUCHERTYPENAME>KIAS Receipt</VOUCHERTYPENAME>
            //     <VOUCHERNUMBER>RCP'.$bill['id'].'</VOUCHERNUMBER>
            //     <CSTFORMISSUETYPE/>
            //     <CSTFORMRECVTYPE/>
            //     <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
            //     <PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>
            //     <VCHGSTCLASS/>
            //     <VOUCHERTYPEORIGNAME>KIAS Receipt</VOUCHERTYPEORIGNAME>
            //     <EFFECTIVEDATE>'.$billDate.'</EFFECTIVEDATE>
            //     <ALTERID>RCP'.$bill['id'].'</ALTERID>
            //     <ALLLEDGERENTRIES.LIST>
            //         <LEDGERNAME>'.$retailer.'</LEDGERNAME>
            //         <GSTCLASS/>
            //         <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
            //         <LEDGERFROMITEM>No</LEDGERFROMITEM>
            //         <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
            //         <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
            //         <AMOUNT>'.$paidAmount.'</AMOUNT>
            //     </ALLLEDGERENTRIES.LIST>
            //     <ALLLEDGERENTRIES.LIST>
            //         <LEDGERNAME>KIAS Other Adjustment</LEDGERNAME>
            //         <GSTCLASS/>
            //         <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
            //         <LEDGERFROMITEM>No</LEDGERFROMITEM>
            //         <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
            //         <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
            //         <AMOUNT>'.$newPaidAmount.'</AMOUNT>
            //     </ALLLEDGERENTRIES.LIST>
            //     </VOUCHER>
            //     </TALLYMESSAGE>';

                $matter .='<TALLYMESSAGE xmlns:UDF="TallyUDF">
                <VOUCHER REMOTEID="'.$billUniqueNo.'" VCHTYPE="Credit Note" ACTION="Create" OBJVIEW="Accounting Voucher View">
                         <DATE>'.$billDate.'</DATE>
                         <GUID>'.$billUniqueNo.'</GUID>
                         <GSTREGISTRATIONTYPE></GSTREGISTRATIONTYPE>
                         <VATDEALERTYPE></VATDEALERTYPE>
                         <STATENAME></STATENAME>
                         <NARRATION>'.$narration.'</NARRATION>
                         <COUNTRYOFRESIDENCE></COUNTRYOFRESIDENCE>
                         <PARTYGSTIN></PARTYGSTIN>
                         <PLACEOFSUPPLY></PLACEOFSUPPLY>
                         <PARTYNAME>'.$retailer.'</PARTYNAME>
                         <PARTYLEDGERNAME>'.$retailer.'</PARTYLEDGERNAME>
                         <VOUCHERTYPENAME>KIAS Credit Note</VOUCHERTYPENAME>
                         <VOUCHERNUMBER>OTHER-ADJ-'.$bill['id'].'</VOUCHERNUMBER>
                         <BASICBASEPARTYNAME>'.$retailer.'</BASICBASEPARTYNAME>
                         <CSTFORMISSUETYPE/>
                         <CSTFORMRECVTYPE/>
                         <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
                         <PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>
                         <VCHGSTCLASS/>
                         <CONSIGNEESTATENAME></CONSIGNEESTATENAME>
                         <VCHENTRYMODE>As Voucher</VCHENTRYMODE>
                         <VOUCHERTYPEORIGNAME>KIAS Credit Note</VOUCHERTYPEORIGNAME>
                         <EFFECTIVEDATE>'.$billDate.'</EFFECTIVEDATE>
                         <ISDELETED>No</ISDELETED>
                         <ISINVOICE>No</ISINVOICE>
                         <ISVATDUTYPAID>Yes</ISVATDUTYPAID>
                         <ALTERID>OTHER-ADJ-'.$bill['id'].'</ALTERID>
                         <ALLLEDGERENTRIES.LIST>
                         <LEDGERNAME>'.$retailer.'</LEDGERNAME>
                         <GSTCLASS/>
                         <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                         <LEDGERFROMITEM>No</LEDGERFROMITEM>
                         <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                         <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                         <AMOUNT>'.$paidAmount.'</AMOUNT>
                         <VATEXPAMOUNT>'.$paidAmount.'</VATEXPAMOUNT>
                         </ALLLEDGERENTRIES.LIST>
                         <ALLLEDGERENTRIES.LIST>
                         <LEDGERNAME>KIAS Other Adjustment</LEDGERNAME>
                         <GSTCLASS/>
                         <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                         <LEDGERFROMITEM>No</LEDGERFROMITEM>
                         <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                         <ISPARTYLEDGER>No</ISPARTYLEDGER>
                         <AMOUNT>'.$newPaidAmount.'</AMOUNT>
                         <VATEXPAMOUNT>'.$newPaidAmount.'</VATEXPAMOUNT>
                         </ALLLEDGERENTRIES.LIST>
                      </VOUCHER>
                      </TALLYMESSAGE>';
        }
        return $matter;
    }

    public function debitToEmployeeExport($data,$distributorCode){
        $matter='';
        foreach($data as $bill){
            $billUniqueNo='Payment ID '.$bill['id'].'-'.$bill['billNo'];
            $billNo=$bill['billNo'];
            $billDate=date('Ymd',strtotime($bill['date']));
            // $retailer=$bill['rtname'].' : '.$bill['rtCode'];

            $retailer="";
            if($bill['isDeliverySlipBill']==1){
              $retailer='Deliveryslip : '.$bill['rtname'].' : '.$bill['rtCode'];
            }else{
              $retailer=$bill['rtname'].' : '.$bill['rtCode'];
            }

            $retailer=str_replace('&','And',$retailer);

            $paidAmount=$bill['paidAmount'];

            //for dynamic transactions
            $employeeName="";
            if($bill['empId']>0){
                $emp=$this->ReportModel->load('employee',$bill['empId']);
                if(!empty($emp)){
                  $employeeName=$emp[0]['name'];
                }
            }

            $newPaidAmount=0;
            $narration="";
            if($paidAmount >0){
                $newPaidAmount='-'.$paidAmount;
                $narration="Debited to employee ".$employeeName." imported from KIAS";
            }else{
                $newPaidAmount=abs($paidAmount);
                $narration="Employee Debit transaction edited by Owner ".$employeeName." imported from KIAS";
            }

            $narration=str_replace('&','And',$narration);
            
            // $matter .='<TALLYMESSAGE xmlns:UDF="TallyUDF">
            // <VOUCHER REMOTEID="'.$billUniqueNo.'" VCHTYPE="Receipt" ACTION="Create" OBJVIEW="Accounting Voucher View">
            //     <DATE>'.$billDate.'</DATE>
            //     <GUID>'.$billUniqueNo.'</GUID>
            //     <NARRATION>'.$narration.'</NARRATION>
            //     <PARTYLEDGERNAME>'.$retailer.'</PARTYLEDGERNAME>
            //     <VOUCHERTYPENAME>KIAS Receipt</VOUCHERTYPENAME>
            //     <VOUCHERNUMBER>RCP'.$bill['id'].'</VOUCHERNUMBER>
            //     <CSTFORMISSUETYPE/>
            //     <CSTFORMRECVTYPE/>
            //     <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
            //     <PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>
            //     <VCHGSTCLASS/>
            //     <VOUCHERTYPEORIGNAME>KIAS Receipt</VOUCHERTYPEORIGNAME>
            //     <EFFECTIVEDATE>'.$billDate.'</EFFECTIVEDATE>
            //     <ALTERID>RCP'.$bill['id'].'</ALTERID>
            //     <ALLLEDGERENTRIES.LIST>
            //         <LEDGERNAME>'.$retailer.'</LEDGERNAME>
            //         <GSTCLASS/>
            //         <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
            //         <LEDGERFROMITEM>No</LEDGERFROMITEM>
            //         <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
            //         <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
            //         <AMOUNT>'.$paidAmount.'</AMOUNT>
            //     </ALLLEDGERENTRIES.LIST>
            //     <ALLLEDGERENTRIES.LIST>
            //         <LEDGERNAME>KIAS Debit to Employee</LEDGERNAME>
            //         <GSTCLASS/>
            //         <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
            //         <LEDGERFROMITEM>No</LEDGERFROMITEM>
            //         <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
            //         <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
            //         <AMOUNT>'.$newPaidAmount.'</AMOUNT>
            //     </ALLLEDGERENTRIES.LIST>
            //     </VOUCHER>
            //     </TALLYMESSAGE>';

                $matter .='<TALLYMESSAGE xmlns:UDF="TallyUDF">
                <VOUCHER REMOTEID="'.$billUniqueNo.'" VCHTYPE="Credit Note" ACTION="Create" OBJVIEW="Accounting Voucher View">
                         <DATE>'.$billDate.'</DATE>
                         <GUID>'.$billUniqueNo.'</GUID>
                         <GSTREGISTRATIONTYPE></GSTREGISTRATIONTYPE>
                         <VATDEALERTYPE></VATDEALERTYPE>
                         <STATENAME></STATENAME>
                         <NARRATION>'.$narration.'</NARRATION>
                         <COUNTRYOFRESIDENCE></COUNTRYOFRESIDENCE>
                         <PARTYGSTIN></PARTYGSTIN>
                         <PLACEOFSUPPLY></PLACEOFSUPPLY>
                         <PARTYNAME>'.$retailer.'</PARTYNAME>
                         <PARTYLEDGERNAME>'.$retailer.'</PARTYLEDGERNAME>
                         <VOUCHERTYPENAME>KIAS Credit Note</VOUCHERTYPENAME>
                         <VOUCHERNUMBER>EMP-DEBIT-'.$bill['id'].'</VOUCHERNUMBER>
                         <BASICBASEPARTYNAME>'.$retailer.'</BASICBASEPARTYNAME>
                         <CSTFORMISSUETYPE/>
                         <CSTFORMRECVTYPE/>
                         <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
                         <PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>
                         <VCHGSTCLASS/>
                         <CONSIGNEESTATENAME></CONSIGNEESTATENAME>
                         <VCHENTRYMODE>As Voucher</VCHENTRYMODE>
                         <VOUCHERTYPEORIGNAME>KIAS Credit Note</VOUCHERTYPEORIGNAME>
                         <EFFECTIVEDATE>'.$billDate.'</EFFECTIVEDATE>
                         <ISDELETED>No</ISDELETED>
                         <ISINVOICE>No</ISINVOICE>
                         <ISVATDUTYPAID>Yes</ISVATDUTYPAID>
                         <ALTERID>EMP-DEBIT-'.$bill['id'].'</ALTERID>
                         <ALLLEDGERENTRIES.LIST>
                         <LEDGERNAME>'.$retailer.'</LEDGERNAME>
                         <GSTCLASS/>
                         <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                         <LEDGERFROMITEM>No</LEDGERFROMITEM>
                         <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                         <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                         <AMOUNT>'.$paidAmount.'</AMOUNT>
                         <VATEXPAMOUNT>'.$paidAmount.'</VATEXPAMOUNT>
                         </ALLLEDGERENTRIES.LIST>
                         <ALLLEDGERENTRIES.LIST>
                         <LEDGERNAME>KIAS Debit to Employee</LEDGERNAME>
                         <GSTCLASS/>
                         <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                         <LEDGERFROMITEM>No</LEDGERFROMITEM>
                         <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                         <ISPARTYLEDGER>No</ISPARTYLEDGER>
                         <AMOUNT>'.$newPaidAmount.'</AMOUNT>
                         <VATEXPAMOUNT>'.$newPaidAmount.'</VATEXPAMOUNT>
                         </ALLLEDGERENTRIES.LIST>
                      </VOUCHER>
                      </TALLYMESSAGE>';
        }
        return $matter;
    }

    public function cashDiscountExport($data,$distributorCode){
      $matter ='';

      foreach($data as $bill){
        $billUniqueNo='Payment ID '.$bill['id'].'-'.$bill['billNo'];
        $billNo=$bill['billNo'];
        $billDate=date('Ymd',strtotime($bill['date']));
        // $retailer= $bill['rtname'].' : '.$bill['rtCode'];

        $retailer="";
            if($bill['isDeliverySlipBill']==1){
              $retailer='Deliveryslip : '.$bill['rtname'].' : '.$bill['rtCode'];
            }else{
              $retailer=$bill['rtname'].' : '.$bill['rtCode'];
            }

        $retailer=str_replace('&','And',$retailer);

        $paidAmount=$bill['paidAmount'];

        //for dynamic transactions
        $employeeName="";
        if($bill['empId']>0){
            $emp=$this->ReportModel->load('employee',$bill['empId']);
            if(!empty($emp)){
              $employeeName=$emp[0]['name'];
            }
        }

        $newPaidAmount=0;
        $narration="";
        if($paidAmount >0){
          $newPaidAmount='-'.$paidAmount;
          $narration="Cash discount imported from KIAS";
        }else{
          $newPaidAmount=abs($paidAmount);
          $narration="Cash discount transaction edited by Owner ".$employeeName." imported from KIAS";
        }

        $narration=str_replace('&','And',$narration);

        $matter .='<TALLYMESSAGE xmlns:UDF="TallyUDF">
        <VOUCHER REMOTEID="'.$billUniqueNo.'" VCHTYPE="Credit Note" ACTION="Create" OBJVIEW="Accounting Voucher View">
                 <DATE>'.$billDate.'</DATE>
                 <GUID>'.$billUniqueNo.'</GUID>
                 <GSTREGISTRATIONTYPE></GSTREGISTRATIONTYPE>
                 <VATDEALERTYPE></VATDEALERTYPE>
                 <STATENAME></STATENAME>
                 <NARRATION>'.$narration.'</NARRATION>
                 <COUNTRYOFRESIDENCE></COUNTRYOFRESIDENCE>
                 <PARTYGSTIN></PARTYGSTIN>
                 <PLACEOFSUPPLY></PLACEOFSUPPLY>
                 <PARTYNAME>'.$retailer.'</PARTYNAME>
                 <PARTYLEDGERNAME>'.$retailer.'</PARTYLEDGERNAME>
                 <VOUCHERTYPENAME>KIAS Credit Note</VOUCHERTYPENAME>
                 <VOUCHERNUMBER>CD-'.$bill['id'].'</VOUCHERNUMBER>
                 <BASICBASEPARTYNAME>'.$retailer.'</BASICBASEPARTYNAME>
                 <CSTFORMISSUETYPE/>
                 <CSTFORMRECVTYPE/>
                 <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
                 <PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>
                 <VCHGSTCLASS/>
                 <CONSIGNEESTATENAME></CONSIGNEESTATENAME>
                 <VCHENTRYMODE>As Voucher</VCHENTRYMODE>
                 <VOUCHERTYPEORIGNAME>KIAS Credit Note</VOUCHERTYPEORIGNAME>
                 <EFFECTIVEDATE>'.$billDate.'</EFFECTIVEDATE>
                 <ISDELETED>No</ISDELETED>
                 <ISINVOICE>No</ISINVOICE>
                 <ISVATDUTYPAID>Yes</ISVATDUTYPAID>
                 <ALTERID>CD-'.$bill['id'].'</ALTERID>
                 <ALLLEDGERENTRIES.LIST>
                 <LEDGERNAME>'.$retailer.'</LEDGERNAME>
                 <GSTCLASS/>
                 <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                 <LEDGERFROMITEM>No</LEDGERFROMITEM>
                 <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                 <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                 <AMOUNT>'.$paidAmount.'</AMOUNT>
                 <VATEXPAMOUNT>'.$paidAmount.'</VATEXPAMOUNT>
                 </ALLLEDGERENTRIES.LIST>
                 <ALLLEDGERENTRIES.LIST>
                 <LEDGERNAME>KIAS Cash Discount</LEDGERNAME>
                 <GSTCLASS/>
                 <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                 <LEDGERFROMITEM>No</LEDGERFROMITEM>
                 <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                 <ISPARTYLEDGER>No</ISPARTYLEDGER>
                 <AMOUNT>'.$newPaidAmount.'</AMOUNT>
                 <VATEXPAMOUNT>'.$newPaidAmount.'</VATEXPAMOUNT>
                 </ALLLEDGERENTRIES.LIST>
              </VOUCHER>
              </TALLYMESSAGE>';
      }
      return $matter;
  }

}