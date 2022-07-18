<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
class CashAndChequeController extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model('CashAndChequeModel');
        date_default_timezone_set('Asia/Kolkata');
        $this->load->library('session');
        ini_set('memory_limit', '-1');

        if(isset($this->session->userdata['codeKeyData'])) {
			$this->projectSessionName= $this->session->userdata['codeKeyData']['codeKeyValue'];
			$this->baseUrl=$this->session->userdata['codeKeyData']['yourBaseUrl'];

            if($this->baseUrl=="http://localhost/smartdistributor/" || $this->baseUrl=="https://siainc.in/kiasales/" || $this->baseUrl=="https://siainc.in/staging_kiasales/"){

            }else{
                $this->load->helper('url');
                $url_parts = parse_url(current_url());
                $siteUrl=explode('/',$url_parts['path']);//current url path
        
                $baseUrl=explode('/',$this->baseUrl);//base url path
                
                $siteDistributorName=trim($siteUrl[2]);
                $baseDistributorName=trim($baseUrl[4]);
                
                if($siteDistributorName !="" && $baseDistributorName !=""){
                    if($siteDistributorName==$baseDistributorName){
                    //   
                    }else{
                    redirect($this->baseUrl.'index.php/UserAuthentication/randomlogout');
                    }
                }else{
                redirect($this->baseUrl.'index.php/UserAuthentication/randomlogout');
                }
            }
		}else{
			$this->load->view('LoginView');
		}
    }

    public function index()
    {
        $this->session->set_flashdata('message', '');
        $data['message']=$this->session->flashdata('message');
        $data['bills']=$this->CashAndChequeModel->currentCheques('bills');
        $data['banks']=$this->CashAndChequeModel->getNames('bank');
        $data['company']=$this->CashAndChequeModel->getNames('company');

        $highlightValue=3;
        $days=$this->CashAndChequeModel->getDetails('highlighting_days');
        if(!empty($days)){
            if($days[1]['id']==6){
                $highlightValue=$days[1]['days'];
            }
        }
        $data['highlightValue']=$highlightValue;

        $this->load->view('NewEntryView',$data);
    }

    public function chequeDepositSlipTransactions(){
        $data['transactions']=$this->CashAndChequeModel->getTransactions();
        $this->load->view('Manager/cheque_deposit_transaction_view',$data);
    }

    public function getChequesFromTransations($transactionId){
        $transactionId= urldecode($transactionId);
        $data['title']=$transactionId;
        $data['chequeDetails']=$this->CashAndChequeModel->getChequeTransactions($transactionId);
        $this->load->view('Manager/cheque_deposit_details_view',$data);
    }
    
    public function DesktopBill()
    {
        $date =$this->input->post('dt');
        // echo $date;exit;
        $comp=$this->input->post('name');

        $data['emailIDs']=$this->CashAndChequeModel->getEmail('employee');
        $data['company']=$this->CashAndChequeModel->getdata('company');

        $response=array();
        $DesktopBillResponse=array();
        if($comp==""){
            $data['bills']=array();
            $data['officeBills']=array();
            $this->session->unset_userdata('DepositDate');
            $this->load->view('DesktopBillView',$data);
        }else if(($comp=="General") && (!$date=="")){
            $session_data = array('depositDate' => $date);
            $this->session->set_userdata('DepositDate', $session_data);

            $session_data = array('depositCompany' => 'General');
            $this->session->set_userdata('DepositCompany', $session_data);

            // $data['bills']=$this->CashAndChequeModel->getdataRetailer('bills',$date);
            // $data['officeBills']=$this->CashAndChequeModel->getOfficeCheques('billpayments',$date);
            $data['bills']=$this->CashAndChequeModel->groupByChequeDepositslip('bills',$date);
            $data['officeBills']=$this->CashAndChequeModel->groupByofficeChequeDepositSlip('billpayments',$date);
            
            foreach($data['bills'] as $row){
                $response[] = $row;
            }

            foreach($data['officeBills'] as $row){
                $response[] = $row;
            }
            // print_r($data);exit;

            $this->session->set_userdata("DesktopBill",$response);
            $this->load->view('DesktopBillView',$data);
        }else if($comp !="" && $date!="") {
            $session_data = array('depositDate' => $date);
            $this->session->set_userdata('DepositDate', $session_data);

            $session_data = array('depositCompany' => $comp);
            $this->session->set_userdata('DepositCompany', $session_data);
            
            // $data['bills']=$this->CashAndChequeModel->getdataRetailerDate('bills',$date,$comp);
            // $data['officeBills']=$this->CashAndChequeModel->getOfficeChequesWithCompany('billpayments',$date,$comp);

            $data['bills']=$this->CashAndChequeModel->groupByChequeDepositslipDate('bills',$date,$comp);
            $data['officeBills']=$this->CashAndChequeModel->groupByofficeChequeDepositSlipByDate('billpayments',$date,$comp);
            foreach($data['bills'] as $row){
                $response[] = $row;
            }

            foreach($data['officeBills'] as $row){
                $response[] = $row;
            }
            
            $this->session->set_userdata("DesktopBill",$response);
            $this->load->view('DesktopBillView',$data);
        }
    }

    public function searchBills(){
         $response=array();
        $bills=array();
        $officeBills=array();
        $date=trim($this->input->post('date'));
        $name=trim($this->input->post('name'));
        
        // echo $date.' '.$name;
       if(($name=="--Select All Divisions--") && ($date !=="")){
            $bills=$this->CashAndChequeModel->getdataRetailerByDate('bills',$date,$name);
            $officeBills=$this->CashAndChequeModel->getOfficeCheques('billpayments',$date,$name);


            // print_r($bills);echo "<br><br>";print_r($officeBills);exit;
            foreach($bills as $row){
                $response[] = $row;
            }

            foreach($officeBills as $row){
                $response[] = $row;
            }

            $this->session->set_userdata("DesktopBill",$response);
        }else if(($name !="") && ($date !=="")){
            $bills=$this->CashAndChequeModel->getdataRetailerDate('bills',$date,$name);
            $officeBills=$this->CashAndChequeModel->getOfficeChequesWithCompany('billpayments',$date,$name);

            foreach($bills as $row){
                $response[] = $row;
            }

            foreach($officeBills as $row){
                $response[] = $row;
            }

            $this->session->set_userdata("DesktopBill",$response);
        }


        // $data['bills']=$bills;
        // $data['officeBills']=$officeBills;
        // $this->load->view('DesktopBillView',$data);
        
         $no=0;
        foreach ($bills as $data) 
        {
           $no++; 
           ?>
           <tr>
            <td>
                <input class="checkhour" type="checkbox" name="selValue" value="<?php echo $data['id']; ?>" id="basic_checkbox_<?php echo $data['id']; ?>" checked/>
                <label for="basic_checkbox_<?php echo $data['id']; ?>"></label>
            </td>
            <td><?php echo $no; ?></td>
            <?php
                $rname="";
                $billsID= $data['billId'];
                // echo $billsID."<br>";
                $billsID=explode(',',$billsID);
                // print_r($billsID);
                foreach($billsID as $b){
                    // echo $b;
                    if($b>0){
                        $retailer=$this->CashAndChequeModel->getRetailerbyBills('bills',$b);
                        $rname=$rname.$retailer.',';
                    }
                    
                }
                $rname= trim($rname,',');
            ?>
            <td><?php echo $rname; ?></td>
             <td><?php echo $data['chequeNo']; ?></td>
             <td><?php
            $dt=date_create($data['chequeDate']);
            $data['chequeDate'] = date_format($dt,'d-M-Y');
            echo $data['chequeDate']; ?></td>
            <td><?php echo $data['paidAmount']; ?></td>
            <td><?php echo $data['chequeBank']; ?></td>
            <td>
            <?php
                $cmp=$data['compName']; 
                echo trim($cmp,',');
            ?>
            </td>
        </tr>
        <?php
    }
        foreach ($officeBills as $data) 
                {
                   $no++; 
                   ?>
                   <tr>
                    <td>
                        <input class="checkhour" type="checkbox" name="selValue" value="<?php echo $data['id']; ?>" id="basic_checkbox_<?php echo $data['id']; ?>" checked/>
                        <label for="basic_checkbox_<?php echo $data['id']; ?>"></label>
                    </td>
                    <td><?php echo $no; ?></td>
                    
                    <td><?php echo $data['retailerName']; ?></td>
                     <td><?php echo $data['chequeNo']; ?></td>
                     <td><?php
                    $dt=date_create($data['chequeDate']);
                    $data['chequeDate'] = date_format($dt,'d-M-Y');
                    echo $data['chequeDate']; ?></td>
                    <td><?php echo $data['paidAmount']; ?></td>
                    <td><?php echo $data['chequeBank']; ?></td>
                    <td>
                    <?php
                        $cmp=$data['compName']; 
                        echo trim($cmp,',');
                    ?>
                    </td>
                </tr>
                <?php
            }
                                                 

        
    }

    public function BounceCheques()
    {       

        $highlightValue=3;
        $days=$this->CashAndChequeModel->getDetails('highlighting_days');
        if(!empty($days)){
            if($days[1]['id']==8){
                $highlightValue=$days[1]['days'];
            }
        }
        $data['highlightValue']=$highlightValue;

        $data['billpayments']=$this->CashAndChequeModel->getdataBounceNew('billpayments');
        
        // echo count($data['billpayments']);
        // print_r($data['billpayments']);exit;
        // $data['billpayments']=$this->CashAndChequeModel->getdataBounce('billpayments');

        // print_r($data['billpayments']);exit;
        // if(!empty($data['billpayments'])){
        //     for($j=0;$j<count($data['billpayments']);$j++) {
        //         if($data['billpayments'][$j]['billId']>0){
        //             $billNo = "";
        //             $retailer="";
        //             $compName = "";
        //             $billId= $data['billpayments'][$j]['billId']; 
        //             // echo $billId;// get id in billpayment
        //             $id = explode(',', $billId);

        //             for($i=0;$i<count($id);$i++) {
        //                 // echo $data['billpayments'][$i]['billId'];
        //                 if($data['billpayments'][$i]['billId']>0){
        //                     $data['bills']=$this->CashAndChequeModel->loadBillNumber('bills',$id[$i]);
        //                     $billNo = $billNo.' '.$data['bills'][0]['billNo'].", ";
        //                 }
        //             }   
        //             for($i=0;$i<count($id);$i++) {
        //                 if($data['billpayments'][$i]['billId']>0){
        //                     $data['bills']=$this->CashAndChequeModel->loadBillNumber('bills',$id[$i]);
        //                     $compName = $compName.' '.$data['bills'][0]['compName'].", ";
        //                 }
        //             } 
        //             for($i=0;$i<count($id);$i++){
        //                 if($data['billpayments'][$i]['billId']>0){
        //                     $data['bills']=$this->CashAndChequeModel->loadBillNumber('bills',$id);
        //                 }
        //             }         
        //             $compName = implode(',', array_unique(array_map('trim', explode(',', $compName))));
        //             $billNo = implode(',', array_unique(array_map('trim', explode(',', $billNo))));
        //             $compName = implode(',', array_unique(array_map('trim', explode(',', $compName))));
        //             $data['billpayments'][$j]['billNo'] = trim($billNo,',');
        //             $data['billpayments'][$j]['retailer']=$retailer;
        //             $data['billpayments'][$j]['compName']=trim($compName,',');
        //             $billNo="";
        //             $retailer="";
        //             $compName="";
        //         }
                 
        //     }
            
        // }
        // print_r($data);exit;

        // print_r($data['billpayments'][0]);exit;
        $this->load->view('BouncedCheques',$data);
    }
    
    public function ChequeRegister(){
        $from=$this->input->post('from_date');
        $to=$this->input->post('to_date');

        $type=$this->input->post('selectedDateType');

        if ($from !="" && $to !="") {
            $retailer="";
            $data['billpayments']=$this->CashAndChequeModel->getdataByDatesGroupBy('billpayments',$from,$to,$type);
            $data['bouncedBillPayments']=$this->CashAndChequeModel->getBounceddataByDatesGroupBy('billpayments',$from,$to,$type);
            
            $data['billpaymentsAdHoc']=$this->CashAndChequeModel->getdataByDatesAdHocGroupBy('billpayments',$from,$to,$type);
            $this->load->view('CheckRegisterView',$data);
        }else{

            $data['billpayments']=$this->CashAndChequeModel->getCheckRegisterGroupBy('billpayments');
            $data['bouncedBillPayments']=$this->CashAndChequeModel->getBouncedCheckRegisterGroupBy('billpayments');
            $data['billpaymentsAdHoc']=$this->CashAndChequeModel->getCheckRegisterAdHocGroupBy('billpayments');
            $retailer="";
            // print_r($data);exit;
            $this->load->view('CheckRegisterView',$data);
        }
    }

    public function NeftRegister(){
        $from=$this->input->post('from_date');
        $to=$this->input->post('to_date');

        if ($from !="" && $to !="") {
            $retailer="";
            $data['billpayments']=$this->CashAndChequeModel->getNeftdataByDatesGroupBy('billpayments',$from,$to);
            // $data['billpayments']=$this->CashAndChequeModel->getNeftdataByDates('billpayments',$from,$to);
            
            // for($j=0;$j<count($data['billpayments']);$j++) {
            //     $billNo = "";
            //      $compName = "";
            //     $billId= $data['billpayments'][$j]['billId']; // get id in billpayment
            //     $id = explode(',', $billId);
            //     for($i=0;$i<count($id);$i++) {
            //         $data['bills']=$this->CashAndChequeModel->loadBillNumber('bills',$id[$i]);
            //         $billNo = $billNo.' '.$data['bills'][0]['billNo'].", ";
            //     }   
            //      for($i=0;$i<count($id);$i++) {
            //         $data['bills']=$this->CashAndChequeModel->loadBillNumber('bills',$id[$i]);
            //         $compName = $compName.' '.$data['bills'][0]['compName'].", ";
            //     } 
            //     for($i=0;$i<count($id);$i++){
            //         $data['bills']=$this->CashAndChequeModel->loadBillNumber('bills',$id);
            //         $rname['retailer']=$this->CashAndChequeModel->loadRetailsers('retailer',$data['bills'][$i]['retailerCode']);
            //     }   
            //     $billNo = implode(',', array_unique(array_map('trim', explode(',', $billNo))));
            //     $compName = implode(',', array_unique(array_map('trim', explode(',', $compName))));
            //     $retailer = implode(',', array_unique(array_map('trim', explode(',', $retailer))));   
            //     $data['billpayments'][$j]['billNo'] = $billNo;
            //     $data['billpayments'][$j]['retailer']=$retailer;
            //     $data['billpayments'][$j]['compName']=$compName;
            //     $billNo="";
            //     $retailer="";
            //     $compName="";
            // }
            $this->load->view('NeftRegisterView',$data);
        }else{
            $data['billpayments']=$this->CashAndChequeModel->getNeftRegisterGroupBy('billpayments');
            // $data['billpayments']=$this->CashAndChequeModel->getNeftRegister('billpayments');
            $retailer="";
            // for($j=0;$j<count($data['billpayments']);$j++) {
            //     $billNo = "";
            //     $compName = "";
            //     $billId= $data['billpayments'][$j]['billId']; // get id in billpayment
            //     $id = explode(',', $billId);
            //     for($i=0;$i<count($id);$i++) {
            //         $data['bills']=$this->CashAndChequeModel->loadBillNumber('bills',$id[$i]);
            //         $billNo = $billNo.' '.$data['bills'][0]['billNo'].", ";
            //     }   

            //     for($i=0;$i<count($id);$i++) {
            //         $data['bills']=$this->CashAndChequeModel->loadBillNumber('bills',$id[$i]);
            //         $compName = $compName.' '.$data['bills'][0]['compName'].", ";
            //     } 

            //     for($i=0;$i<count($id);$i++){
            //         $data['bills']=$this->CashAndChequeModel->loadBillNumber('bills',$id);
            //         if(!empty($data['bills'][$i]['retailerCode'])){
            //             $rname['retailer']=$this->CashAndChequeModel->loadRetailsers('retailer',$data['bills'][$i]['retailerCode']);
            //         }else if(!empty($data['bills'][$i]['retailerId'])){
            //             $rname['retailer']=$this->CashAndChequeModel->loadRetailsersId('retailer',$data['bills'][$i]['retailerId']);
            //         }
                   
            //     }     
            //     $billNo = implode(',', array_unique(array_map('trim', explode(',', $billNo))));
            //     $compName = implode(',', array_unique(array_map('trim', explode(',', $compName))));
            //     $retailer = implode(',', array_unique(array_map('trim', explode(',', $retailer))));      
            //     $data['billpayments'][$j]['billNo'] = $billNo;
            //     $data['billpayments'][$j]['retailer']=$retailer;
            //     $data['billpayments'][$j]['compName']=$compName;
            //     $billNo="";
            //     $retailer="";
            //     $compName="";
            // }
            $this->load->view('NeftRegisterView',$data);
        }
    }
    
    public function oldChequeReconcilation()
    {   
        $data['penalty']=$this->CashAndChequeModel->getdata('penalty');
        $data['billpayments']=$this->CashAndChequeModel->getdataBankedGroupBy('billpayments');
        $data['billpaymentsAdHoc']=$this->CashAndChequeModel->getdataAdHocBankedGroupBy('billpayments');
       
        $this->load->view('ChequeReconcilationView',$data);
    }
   

    public function ChequeReconcilation()
    {
        $data['penalty']=$this->CashAndChequeModel->getdata('penalty');

        $this->load->library('pagination');

        $billpayments=$this->CashAndChequeModel->getdataBankedGroupBy('billpayments');
        $billpaymentsAdHoc=$this->CashAndChequeModel->getdataAdHocBankedGroupBy('billpayments');
        
        $total=0;
        $totalCheques=0;
        if(!empty($billpayments)){
            $totalCheques=$totalCheques+(count($billpayments));
            foreach($billpayments as $item){
                $total=$total+$item['sumAmount'];
            }
        }

        if(!empty($billpaymentsAdHoc)){
            $totalCheques=$totalCheques+(count($billpaymentsAdHoc));
            foreach($billpaymentsAdHoc as $item){
                $total=$total+$item['sumAmount'];
            }
        }

        $data['totalChequesAmount']=$total;
        $data['totalCheques']=$totalCheques;


        $config['base_url'] = base_url('index.php/CashAndChequeController/ChequeReconcilation');
        
        $config['per_page'] = ($this->input->get('limitRows')) ? $this->input->get('limitRows') : 50;
        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;

         // integrate bootstrap pagination
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
       
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = 'Prev';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="'.$config['base_url'].'?per_page=0">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $data['page'] = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;
        $data['searchFor'] = ($this->input->get('query')) ? $this->input->get('query') : NULL;
        $data['orderField'] = ($this->input->get('orderField')) ? $this->input->get('orderField') : '';
        $data['orderDirection'] = ($this->input->get('orderDirection')) ? $this->input->get('orderDirection') : '';
        $outstanding="";
        $rowConunts="";
        $outstanding = $this->CashAndChequeModel->paginationChequereconciatioBills('billpayments',$config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);
        $rowCounts=$this->CashAndChequeModel->countPaginationChequereconciatioBills('billpayments',$config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);

        $adhocOutstanding = $this->CashAndChequeModel->paginationAdhocChequereconciatioBills('billpayments',$config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);
        $adhocRowCounts=$this->CashAndChequeModel->countAdhocPaginationChequereconciatioBills('billpayments',$config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);

        $outstandingBills=array_merge($outstanding,$adhocOutstanding);


        // echo $rowCounts.' '.$adhocRowCounts;exit;
        $data['billpayments'] = $outstandingBills;
        $config['total_rows'] = ($rowCounts+$adhocRowCounts);
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('paginationChequeReconcilliationView',$data);
    }

     public function neftReconcilation()
    {   
        $data['penalty']=$this->CashAndChequeModel->getdata('penalty');
        // $data['billpayments']=$this->CashAndChequeModel->getNeftBanked('billpayments');
        $data['billpayments']=$this->CashAndChequeModel->getNeftBankedGroupBy('billpayments');


        // $data['billpaymentsAdHoc']=$this->CashAndChequeModel->getNeftAdHocBanked('billpayments');
        // print_r($data['billpaymentsAdHoc']);exit;
        // for($j=0;$j<count($data['billpayments']);$j++) {
        //      $billNo = "";
        //     $billId= $data['billpayments'][$j]['billId']; // get id in billpayment
        //     $id = explode(',', $billId);
        //     $data['bills']=$this->CashAndChequeModel->loadBillNumber('bills',$id);
        //     for($i=0;$i<count($data['bills']);$i++) {

        //        $billNo = $billNo.$data['bills'][$i]['billNo'].",";
        //        if($i==0) {
        //         $retailer = $this->CashAndChequeModel->loadRetailsers('retailer',$data['bills'][$i]['retailerCode']);
        //        }
        //     }            
        //     $data['billpayments'][$j]['billNo'] = $billNo;
        // }
        $this->load->view('neftReconciliationView',$data);
    } 


    public function saveChequeReconcilation()
    {   
        // print_r();
        $updateData=array('tempStatus'=>0);
        $this->CashAndChequeModel->updateBillDetails('billpayments',$updateData);

        $data['penalty']=$this->CashAndChequeModel->getdata('penalty');
        $data['billpayments']=$this->CashAndChequeModel->getdataBanked('billpayments');
        $data['billpaymentsAdHoc']=$this->CashAndChequeModel->getdataAdHocBanked('billpayments');

        //  $data['billpayments']=$this->CashAndChequeModel->getdataBankedGroupBy('billpayments');
        // $data['billpaymentsAdHoc']=$this->CashAndChequeModel->getdataAdHocBankedGroupBy('billpayments');
        // print_r($data['billpaymentsAdHoc']);exit;
        for($j=0;$j<count($data['billpayments']);$j++) {
             $billNo = "";
            $billId= $data['billpayments'][$j]['billId']; // get id in billpayment
            $id = explode(',', $billId);
            $data['bills']=$this->CashAndChequeModel->loadBillNumber('bills',$id);
            for($i=0;$i<count($data['bills']);$i++) {

               $billNo = $billNo.$data['bills'][$i]['billNo'].",";
               if($i==0) {
                $retailer = $this->CashAndChequeModel->loadRetailsers('retailer',$data['bills'][$i]['retailerCode']);
               }
            }            
            $data['billpayments'][$j]['billNo'] = $billNo;
        }
        $this->load->view('ChequeReconcilationView',$data);
    }



    public function updateStatus() {

        $id = $this->input->post('id');
        $data = array
        ('chequeStatus' => $this->input->post('chequeStatus'),
            'chequeStatusDate' => date("y-m-d")
        );  
        $result = $this->CashAndChequeModel->update('billpayments',$data, $id);
        if($result==1){    ?>
                
                <a id="changeStatus" >
                    <button onclick="changeStatus('<?php echo $id;?>')" class="btn btn-primary waves-effect" data-type="basic"><?php echo $data['chequeStatus']; ?>
                </button>
            </a>
            <p id="result_data"></p>
            

        <?php } else {
            echo "Fail";
        }
    }
    public function updateStatusBounceCheques() {
        $id = $this->input->post('id');
        $data = array
        ('chequeStatus' => $this->input->post('chequeStatus'),
            'chequeStatusDate' => date("y-m-d")
        );  
        $result = $this->CashAndChequeModel->update('billpayments',$data, $id);
        if($result==1){   ?>
                
                <a id="changeStatus" >
                    <button onclick="changeStatus('<?php echo $id;?>')" class="btn btn-primary waves-effect" data-type="basic"><?php echo $data['chequeStatus']; ?>
                </button>
            </a>
            <p id="result_data"></p>
            

        <?php } else {
            echo "Fail";
        }
    }
    public function updateStatusDesktopBill() {
        $id = $this->input->post('id');
        $data = array
        ('chequeStatus' => $this->input->post('chequeStatus'),
            'chequeStatusDate' => date("y-m-d")
        );  
        $result = $this->CashAndChequeModel->update('billpayments',$data, $id);
        if($result==1){
            ?>           
            <a id="changeStatus" >
                <button onclick="changeStatus('<?php echo $id;?>')" class="btn btn-primary waves-effect" data-type="basic"><?php echo $data['chequeStatus']; ?>
                </button>
            </a>
            <p id="result_data"></p>
<?php   } else {
            echo "Fail";
        }
    }

    public function ChequeDepositSlip(){
       $data['billpayments']=$this->CashAndChequeModel->getdataStatus('billpayments');
       $this->load->view('ChequeDepositSlipView',$data);
    }

    public function updateNewEntry(){
        $chequeBank=$this->input->post('chequeBank');

        $billPaymentId=$this->input->post('billPaymentId');
        
        $bankDetail=$this->CashAndChequeModel->findBank('bank',$chequeBank);
        if(empty($bankDetail) && $chequeBank !=''){//if bank not present in database then insert bank name
            $bankInsert=array('name'=>$chequeBank);
            $this->CashAndChequeModel->insert('bank',$bankInsert);
        }


        $billno=$this->input->post('billno');
        $allocatedIds=$this->input->post('allIDs');
        $billModes=$this->input->post('billModes');


        $newCheque=$this->input->post('newCheque');

        $neftNo = $this->input->post('neftNo');

        $chequeNo = $this->input->post('chequeNo');
        $chequeDate= $this->input->post('chequeDate');
        
        $comp=$this->input->post('company');

        $payAmount = $this->input->post('billAmount');
        $payAmount = str_replace( ',', '', $payAmount);
        $retailerName = $this->input->post('retailer');
        
        // echo $chequeNo.' '.$chequeDate.' '.$payAmount;exit;

        $isChequeNoPresent=$this->CashAndChequeModel->findChequeNo('billpayments',$chequeNo,$chequeDate,$payAmount);
        $isChequeNoPresentWithStatus=$this->CashAndChequeModel->findChequeNoWithStatus('billpayments',$chequeNo,$chequeDate,$payAmount);
        // echo $chequeNo.' '.$chequeDate.' '.$payAmount.' ';
        // print_r($isChequeNoPresent);exit;
        if((empty($isChequeNoPresent))){
            if($newCheque==="yes"){
                // $billDetail=$this->CashAndChequeModel->getIDbyBills('bills',$billno[0]);

                 $data = array(
                    // 'billId' => $billDetail,
                    'billNo'=>$billno[0],
                    'chequeNo' => $chequeNo,
                    'chequeDate' => $chequeDate,
                    'billAmount' => "",
                    'retailerName'=>$retailerName,
                    'paidAmount' => $payAmount,
                    'balanceAmount' => "",
                    'chequeBank' => $chequeBank,
                    'paymentMode' => "Cheque",
                    'date' =>date("Y-m-d H:i:sa"),
                    'chequeStatus'=> "New",
                    'chequeStatusDate' =>date("Y-m-d H:i:sa"),
                    'chequeReceivedDate' =>date("Y-m-d H:i:sa"),
                    'allocationId'=>"0",
                    'isOfficeCheque'=>"1",
                    'compName'=>$comp
                ); 
                $this->CashAndChequeModel->insert('billpayments',$data);
                $this->session->set_flashdata('msg', array('message' => 'Record saved successfully'));

            }else{
                $billno=implode(',',array_unique(explode(',',$billno[0])));
                $billno=trim($billno,',');
                $billno=explode(',',$billno);

                $billPaymentId=implode(',',array_unique(explode(',',$billPaymentId[0])));
                $billPaymentId=trim($billPaymentId,',');
                $billPaymentId=explode(',',$billPaymentId);

              
                $billId="";
                foreach($billno as $bno){
                    $billN=$this->CashAndChequeModel->getIDbyBills('bills',$bno);
                    $billId=$billId.''.$billN.',';
                    
                }
                $billId=trim($billId,',');

                $billId=implode(',',(explode(',',$billId)));
                $billId=trim($billId,',');
                $billId=explode(',',$billId);

                $allocatedIds=implode(',',(explode(',',$allocatedIds)));
                $allocatedIds=trim($allocatedIds,',');
                $allocatedIds=explode(',',$allocatedIds);

                $rc=0;
                $receivedDate=date("Y-m-d H:i:sa");

                foreach($billno as $rec){
                    $updData=array('neftNo'=>$neftNo,'chequeNo'=>$chequeNo,'chequeBank'=>$chequeBank,'chequeDate'=>$chequeDate,'chequeReceivedDate'=>$receivedDate,'neftDate'=>$chequeDate,'chequeStatusDate'=>$receivedDate,'chequeStatus'=>'New','compName'=>trim($comp,','));
                    // $this->CashAndChequeModel->updateBillPayment('billpayments',$updData,$billId[$rc],$allocatedIds[$rc],$billModes);
                    $this->CashAndChequeModel->update('billpayments',$updData,$billPaymentId[$rc]);
                    $rc++;
                }

                $this->session->set_flashdata('msg', array('message' => 'Record saved successfully'));
            }
            
            return redirect('CashAndChequeController','refresh');
        }else{
            $this->session->set_flashdata('msgerr', array('message' => 'Cheque Number Already present'));
           
            return redirect('CashAndChequeController','refresh');
        }
    }

    public function checkChequeDetails(){
        $chequeNo = $this->input->post('chequeNo');
        $chequeDate = $this->input->post('chequeDate');
        $payAmount = $this->input->post('payAmount');

        $isChequeNoPresent=$this->CashAndChequeModel->findChequeNo('billpayments',$chequeNo,$chequeDate,$payAmount);
        if((empty($isChequeNoPresent))){
            echo "";
        }else{
            echo "Cheque already present.";
        }
    }


    public function chequeDetailsVerify(){
        $chequeNo = $this->input->post('chequeNo');
        $chequeDate = $this->input->post('chequeDate');
        $chequeBank = $this->input->post('chequeBank');

        $isChequeNoPresent=$this->CashAndChequeModel->verifyCheque('billpayments',$chequeNo,$chequeDate,$chequeBank);
        if((empty($isChequeNoPresent))){
            echo "";
        }else{
            echo "Cheque already present.";
        }
    }

    public function neftDetailsVerify(){
        $neftNo = $this->input->post('neftNo');
        $neftDate = $this->input->post('neftDate');
        $neftAmount = $this->input->post('billAmount');

        $isNeftNoPresent=$this->CashAndChequeModel->verifyNeft('billpayments',$neftNo,$neftDate,$neftAmount);
        if((empty($isNeftNoPresent))){
            echo "";
        }else{
            echo "NEFT already present.";
        }
    }

     public function neftDetailsVerifyWithoutAmount(){
        $neftNo = $this->input->post('neftNo');
        $neftDate = $this->input->post('neftDate');
        // $neftAmount = $this->input->post('billAmount');

        $isNeftNoPresent=$this->CashAndChequeModel->verifyNeftWithoutAmount('billpayments',$neftNo,$neftDate);
        if((empty($isNeftNoPresent))){
            echo "";
        }else{
            echo "NEFT already present.";
        }
    }

    public function checkNeftDetails(){
        $neftNo = $this->input->post('neftNo');
        $neftDate = $this->input->post('neftDate');
        $payAmount = $this->input->post('payAmount');

        $isNeftNoPresent=$this->CashAndChequeModel->findNeftNo('billpayments',$neftNo,$neftDate,$payAmount);
        if((empty($isNeftNoPresent))){
            echo "";
        }else{
            echo "NEFT already present.";
        }
    }


    public function updateAddHocNewEntry(){
        $chequeBank=$this->input->post('chequeBanks');
        
        $bankDetail=$this->CashAndChequeModel->findBank('bank',$chequeBank);
        if(empty($bankDetail) && $chequeBank !=''){//if bank not present in database then insert bank name
            $bankInsert=array('name'=>$chequeBank);
            $this->CashAndChequeModel->insert('bank',$bankInsert);
        }


        $billno=$this->input->post('billnos');

        $allocatedIds=$this->input->post('allIDss');
        $billModes=$this->input->post('billModess');

        // $newCheque=$this->input->post('newCheque');

        // $neftNo = $this->input->post('neftNo');

        $chequeNo = $this->input->post('chequeNos');
        $chequeDate= $this->input->post('theDates');
        
        $comp=$this->input->post('companys');

        $payAmount = $this->input->post('billAmounts');
        // $payAmount = str_replace( ',', '', $payAmount);
        $retailerName = $this->input->post('retailers');
        
        $isChequeNoPresent=$this->CashAndChequeModel->findChequeNo('billpayments',$chequeNo,$chequeDate,$payAmount);
        $isChequeNoPresentWithStatus=$this->CashAndChequeModel->findChequeNoWithStatus('billpayments',$chequeNo,$chequeDate,$payAmount);
       
        if((empty($isChequeNoPresent))){
            $data = array(
                'billNo'=>$billno,
                'chequeNo' => $chequeNo,
                'chequeDate' => $chequeDate,
                'billAmount' => "",
                'retailerName'=>$retailerName,
                'paidAmount' => $payAmount,
                'balanceAmount' => "",
                'chequeBank' => $chequeBank,
                'paymentMode' => "Cheque",
                'date' =>date("Y-m-d H:i:sa"),
                'chequeStatus'=> "New",
                'chequeStatusDate' =>date("Y-m-d H:i:sa"),
                'chequeReceivedDate' =>date("Y-m-d H:i:sa"),
                'allocationId'=>"0",
                'isOfficeCheque'=>"1",
                'compName'=>$comp
            ); 
            $this->CashAndChequeModel->insert('billpayments',$data);
            echo "Record Inserted";
            // $this->session->set_flashdata('msg', array('message' => 'Record saved successfully'));
            // redirect('CashAndChequeController','refresh');
        }else{
             echo "Cheque Already present";
            // $this->session->set_flashdata('msgerr', array('message' => 'Cheque Number Already present'));
            
            // redirect('CashAndChequeController','refresh');
        }
    }

    public function updateBouncedAndClearCheque(){
        $id=$this->input->post('chequeId');
        $billPayment=$this->CashAndChequeModel->load('billpayments',$id);
        $paidAmount=$billPayment[0]['paidAmount'];
        $data=array('chequeStatus'=>'Bounced&Cleared','chequeStatusDate'=>date('Y-m-d H:i:sa'),'collectedAmt'=>$paidAmount);

        $this->CashAndChequeModel->update('billpayments',$data,$id);
        redirect('CashAndChequeController/BounceCheques');
    }

    public function insert()
    {   
        $billno = array();
        $billno =$this->input->post('billno');
        $billno=implode(',',array_unique(explode(',',$billno[0])));
        $billno=trim($billno,',');
        $billno=explode(',',$billno);

        $billNOs="";
        foreach($billno as $bno){
            $billN=$this->CashAndChequeModel->getIDbyBills('bills',$bno);
            $billNOs=$billNOs.$billN.',';
            
        }
        $billNOs=trim($billNOs,',');
       
        //bill amount total
        $totalBillsAmt = $this->input->post('billAmt');
        $totalBillsAmt=trim($totalBillsAmt[0],',');
        $totalBillsAmt=explode(',',$totalBillsAmt);
        $billAmt=0;
        foreach($totalBillsAmt as $amt){
            $billAmt=$billAmt+$amt;
        }
        $billAmount = $billAmt;
        $billAmount = floatval($billAmount);
        $billAmount= number_format($billAmount, 2, '.', '');

        //amount on cheque
        $payAmount = $this->input->post('billAmount');
        $payAmount = str_replace(',', '', $payAmount);
        $payAmount = floatval($payAmount);

        //amount remaining
        $balanceAmount=$billAmount-$payAmount;
        $balanceAmount = floatval($balanceAmount);
        $balanceAmount= number_format($balanceAmount, 2, '.', '');

        $chequeNo = $this->input->post('chequeNo');
        $chequeDate= $this->input->post('chequeDate');
        $chequeBank=$this->input->post('chequeBank');
        $comp=$this->input->post('company');
        $comp=trim($comp,',');
        $comp=explode(',',$comp);
        $compName="";
        foreach($comp as $i){
            $compName=$compName.$i.',';
        }
      
        $chkDetail=$this->CashAndChequeModel->getChequeDetail('billpayments',$chequeNo,$chequeDate,$chequeBank);
        if(!empty($chkDetail)){
            echo '<script>alert("cheque detail present in database");</script>';
            // return redirect('CashAndChequeController','refresh');
        }else{
            $data = array(
            'billId' => $billNOs,
            'chequeNo' => $chequeNo,
            'chequeDate' => $chequeDate,
            'billAmount' => $billAmount,
            'paidAmount' => $payAmount,
            'balanceAmount' => $balanceAmount,
            'chequeBank' => $chequeBank,
            'paymentMode' => "cheque",
            'date' =>date("Y-m-d"),
            'chequeStatus'=> "Received",
            'chequeStatusDate' =>date("Y-m-d"),
            'allocationId'=>"0",
            'compName'=>trim($compName,',')
            );     
            $result=$this->CashAndChequeModel->insert('billpayments',$data); 
            if($result){
                //change status is_delete 1 for bills 
                $tempBillNos=explode(',',$billNOs);
                foreach($tempBillNos as $bnos){
                    $updateBills = array(
                            'is_delete' => '1'
                    );     

                    $rs=$this->CashAndChequeModel->update('bills',$updateBills,$bnos); 
                    if(!$rs==0){  
                                    // echo "inserted";
                    }else{
                        echo "error ";
                    }
                }
           }
           
           if(!$result==0){    
            // For Company. 
                foreach($comp as $itm){
                    $cmp=$this->CashAndChequeModel->name_exists('company',$itm);
                   if(empty($cmp)){
                        $data1 = array(
                            'name' => $itm
                        );     

                        $result1=$this->CashAndChequeModel->insert('company',$data1); 
                        if(!$result1==0){  
                                        // echo "inserted";
                        }else{
                            echo "error ";
                        }   
                    } 
                     
                }
                // For Bank.
                $bnk=$this->CashAndChequeModel->name_exists('bank',$this->input->post('chequeBank'));
                if(empty($bnk)){
                    $data2 = array(
                        'name' => $this->input->post('chequeBank')
                    ); 

                    $result2=$this->CashAndChequeModel->insert('bank',$data2); 
                    if(!$result2==0){  
                                    // echo "inserted";
                    }else{
                        echo "error ";
                    }    
                }
                echo '<script>alert("cheque detail inserted");</script>';
                return redirect('CashAndChequeController','refresh');
            }
        }  
    }

    public function updateStatusCleared() {
        $id=trim($this->input->post('id'));
        $getBillData=$this->CashAndChequeModel->load('billpayments', $id);
        $chequeNo=$getBillData[0]['chequeNo'];
        $chequeDate=$getBillData[0]['chequeDate'];
        $chequeBank=$getBillData[0]['chequeBank'];
        // echo $chequeNo.' '.$chequeDate;
        // print_r($getBillData);exit;

        $status=trim($this->input->post('status'));
        $data = array('tempStatus'=>1,'chequeStatus' => "Cleared",
            'chequeStatusDate' => date("y-m-d"));
        $result = $this->CashAndChequeModel->updateChequeData('billpayments',$data, $chequeNo,$chequeDate,$chequeBank);

        // $conciliationDetail=$this->CashAndChequeModel->getCurrentReconciliationData('billpayments');
        // $adHocconciliationDetail=$this->CashAndChequeModel->getdataAdHocReconciliationData('billpayments');
    } 

    public function updateStatusClearedWithCheckBox() {
        $selValue=($this->input->post('selValue'));
        // print_r($id);exit;

        if(!empty($selValue)){
            foreach($selValue as $sel){
                $id=$sel;
                $getBillData=$this->CashAndChequeModel->load('billpayments', $id);
                $chequeNo=$getBillData[0]['chequeNo'];
                $chequeDate=$getBillData[0]['chequeDate'];
                $chequeBank=$getBillData[0]['chequeBank'];

                $status=trim($this->input->post('status'));
                $data = array('tempStatus'=>1,'chequeStatus' => "Cleared",
                    'chequeStatusDate' => date("y-m-d"));
                $result = $this->CashAndChequeModel->updateChequeData('billpayments',$data, $chequeNo,$chequeDate,$chequeBank);
   
            }
        }
    } 

    // public function updateNeftStatusCleared() {
    //     $id=trim($this->input->post('id'));
    //     $status=trim($this->input->post('status'));
    //     $billPayment=$this->CashAndChequeModel->load('billpayments',$id);
    //     $amt=0;
    //     if($status=='Received'){
    //          $data = array('tempStatus'=>1,'chequeStatus' => "Received",
    //         'chequeStatusDate' => date("Y-m-d H:i:sa"));
    //         // $this->CashAndChequeModel->update('billpayments',$data, $id);
    //          $this->CashAndChequeModel->updateNeftData('billpayments',$data, $billPayment[0]['neftDate'],$billPayment[0]['neftNo']);
    //     }else{
    //         $billId= $billPayment[0]['billId'];
    //         $amt= $billPayment[0]['paidAmount'];
    //         $bills=$this->CashAndChequeModel->load('bills',$billId);
    //         $pendingAmt=0;
    //         $receivedAmt=0;
    //         if(!empty($bills)){
    //             $pendingAmt=$bills[0]['pendingAmt']+$amt;
    //             $receivedAmt=$bills[0]['receivedAmt']-$amt;
    //         }
            
    //         $data = array('tempStatus'=>1,'chequeStatus' => "Not Received",
    //         'chequeStatusDate' => date("Y-m-d H:i:sa"),'isLostStatus'=>1);
    //         // $this->CashAndChequeModel->update('billpayments',$data, $id);
    //         $this->CashAndChequeModel->updateNeftData('billpayments',$data, $billPayment[0]['neftDate'],$billPayment[0]['neftNo']);

    //         $dataBill=array('pendingAmt'=>$pendingAmt,'receivedAmt'=>$receivedAmt);
    //         $this->CashAndChequeModel->update('bills',$dataBill, $billId);
    //     }
    
    // } 

    public function updateNeftStatusCleared() {
        $id=trim($this->input->post('id'));
        $status=trim($this->input->post('status'));
        $billPayment=$this->CashAndChequeModel->load('billpayments',$id);
        $userId = trim($this->session->userdata[$this->projectSessionName]['id']);
        
        $tallyNeftNo=$billPayment[0]['neftNo'];
        $tallyNeftDate=$billPayment[0]['neftDate'];

        
        $amt=0;
        if($status=='Received'){
             $data = array('tempStatus'=>1,'chequeStatus' => "Received",
            'chequeStatusDate' => date("Y-m-d H:i:sa"));
            // $this->CashAndChequeModel->update('billpayments',$data, $id);
             $this->CashAndChequeModel->updateNeftData('billpayments',$data,$billPayment[0]['neftNo'], $billPayment[0]['neftDate']);
        }else{
            $getBillInfo=$this->CashAndChequeModel->getBillInfo('billpayments',$billPayment[0]['neftNo'],$billPayment[0]['neftDate']);
            foreach($getBillInfo as $item){
                $billId= $item['billId'];
                $amt= $item['paidAmount'];
                $bills=$this->CashAndChequeModel->load('bills',$billId);
                $pendingAmt=0;
                $receivedAmt=0;
                if(!empty($bills)){
                    $pendingAmt=$bills[0]['pendingAmt']+$amt;
                    $receivedAmt=$bills[0]['receivedAmt']-$amt;
                }
                $data = array(
                    'tempStatus'=>1,
                    'chequeStatus' => "Not Received",
                    'chequeStatusDate' => date("Y-m-d H:i:sa"),
                    'isLostStatus'=>1,
                    'neftNo'=>''
                );

                $dataBill=array('pendingAmt'=>$pendingAmt,'receivedAmt'=>$receivedAmt);

                $this->CashAndChequeModel->updateNeftDataWithBillNo('billpayments',$data,$item['billId'],$item['neftNo'],$item['neftDate']);
                $this->CashAndChequeModel->update('bills',$dataBill, $billId);

                //add new entry to billpayments for neft not received 
                $returnAmount=(-$amt);
                $insertBillPayments=array(
                    'billId'=>$billId,
                    'empId'=>$userId,
                    'paymentMode'=>'NEFT',
                    'neftNo'=>'',
                    'neftDate'=>'',
                    'tallyStatus'=>'NEFT_Cancel',
                    'paidAmount'=>$returnAmount,
                    'balanceAmount'=>$pendingAmt,
                    'date'=>date('Y-m-d H:i:sa')
                );
                $this->CashAndChequeModel->insert('billpayments',$insertBillPayments);

                $history=array(
                    'billId'=>$billId,
                    'transactionStatus' =>'NEFT not Received',
                    'transactionAmount' =>$amt,
                    'transactionMode'=>'cr',
                    'transactionDate'=>date('Y-m-d H:i:sa'),
                    'empId'=>trim($this->session->userdata[$this->projectSessionName]['id']),
                    'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                );
                $this->CashAndChequeModel->insert('bill_transaction_history',$history);
            }
        }
    } 

    public function updateNeftStatusClearedWithCheckbox() {
        $selValue=($this->input->post('selValue'));

        if(!empty($selValue)){
            foreach($selValue as $sel){
                $id=$sel;
                $billPayment=$this->CashAndChequeModel->load('billpayments',$id);
                $amt=0;
                 $data = array('tempStatus'=>1,'chequeStatus' => "Received",
                'chequeStatusDate' => date("Y-m-d H:i:sa"));
                // $this->CashAndChequeModel->update('billpayments',$data, $id);
                 $this->CashAndChequeModel->updateNeftData('billpayments',$data,$billPayment[0]['neftNo'], $billPayment[0]['neftDate']);
            }
        }
    } 

    public function updateStatusReturned() {
        $id=$this->input->post('billID');
        // echo $id;exit;

        $userId = trim($this->session->userdata[$this->projectSessionName]['id']);
        
        $reason=$this->input->post('reason');
        $penalty=$this->input->post('penalty');

        $getInfo=$this->CashAndChequeModel->load('billpayments',$id);
        // print_r($getInfo);exit;

        if(!empty($getInfo)){
            $chequeNo=$getInfo[0]['chequeNo'];
            $chequeDate=$getInfo[0]['chequeDate'];
            $chequeBank=$getInfo[0]['chequeBank'];

            // penalty equal for all cheques
            $getPaymentInfo=$this->CashAndChequeModel->getChequeData('billpayments',$chequeNo,$chequeDate,$chequeBank);
            $countRecord=count($getPaymentInfo);
            $newPenaltyForBill=round($penalty/$countRecord);
            
            // check cheque is bounced earlier or not if yes then increase count
            $getBouncedPaymentInfo=$this->CashAndChequeModel->getBouncedChequeData('billpayments',$chequeNo,$chequeDate,$chequeBank);
            $countForBounceCheque=count($getBouncedPaymentInfo);
            
            // print_r($getBouncedPaymentInfo);
            // print_r($countForBounceCheque);
            // exit;

            $data = array(
                'tempStatus'=>2,
                'chequeStatus' => "Bounced",
                'chequeStatusDate' => date("Y-m-d"),
                'statusBouncedReason' => $reason,
                'penalty' => $newPenaltyForBill,
                'bounceChequeCount'=>$countForBounceCheque
            );

            $penalty=$penalty/$countRecord;
            foreach($getPaymentInfo as $itm){
                $transactionEmpId= $itm['empId'];
                $billPaymentId= $itm['id'];
                $billId= $itm['billId'];
                $billInfo=$this->CashAndChequeModel->load('bills',$billId);
                
                $paidAmountForMsg=0;
                if(!empty($billInfo)){
                    $paidAmountForMsg=$itm['paidAmount'];
                    $paidAmount=$itm['paidAmount']+$newPenaltyForBill;
                    $chequePenalty=$billInfo[0]['chequePenalty']+$newPenaltyForBill;
                    $billPendingAmt=$billInfo[0]['pendingAmt']+$paidAmount;
                    $billReceivedAmt=$billInfo[0]['receivedAmt']-$itm['paidAmount'];

                    //add new entry to billpayments for bounce cheque
                    $returnAmount=(-$paidAmount);
                    $insertBillPayments=array(
                        'billId'=>$billId,
                        'empId'=>$userId,
                        'paymentMode'=>'Cheque',
                        'tallyStatus'=>'Cheque_Bounce',
                        'chequeNo'=>$chequeNo,
                        'chequeDate'=>$chequeDate,
                        'chequeBank'=>$chequeBank,
                        'paidAmount'=>(-$itm['paidAmount']),
                        'balanceAmount'=>$billPendingAmt,
                        'statusBouncedReason'=>$reason,
                        'date'=>date('Y-m-d H:i:sa')
                    );
                    $this->CashAndChequeModel->insert('billpayments',$insertBillPayments);

                    $insertBillPayments=array(
                        'billId'=>$billId,
                        'empId'=>$userId,
                        'paymentMode'=>'Cheque',
                        'tallyStatus'=>'Cheque_Bounce_penalty',
                        'chequeNo'=>$chequeNo,
                        'chequeDate'=>$chequeDate,
                        'chequeBank'=>$chequeBank,
                        'paidAmount'=>(-$newPenaltyForBill),
                        'balanceAmount'=>$billPendingAmt,
                        'statusBouncedReason'=>$reason,
                        'date'=>date('Y-m-d H:i:sa')
                    );
                    $this->CashAndChequeModel->insert('billpayments',$insertBillPayments);


                    $updateBillInfo=array(
                        'billCurrentStatus'=>'Cheque Bounce',
                        'pendingAmt'=>$billPendingAmt,
                        'receivedAmt'=>$billReceivedAmt,
                        'chequePenalty'=>$chequePenalty
                    );
                    $this->CashAndChequeModel->update('bills',$updateBillInfo,$billId);

                    $remDesc="Cheque No: ".$chequeNo.", Bank : ".$chequeBank.", Cheque Date : ".date('d-M-Y',strtotime($chequeDate)).'. Reason : '.$reason;
                    $remBounceDesc=" for Cheque No: ".$chequeNo.'. Reason : '.$reason;;
                    $history=array(
                        'billId'=>$billId,
                        'transactionStatus' =>'Cheque Bounce',
                        'transactionAmount'=>$itm['paidAmount'],
                        'transactionMode'=>'cr',
                        'remark'=>$remDesc,
                        'transactionDate'=>date('Y-m-d H:i:sa'),
                        'empId'=>$transactionEmpId,
                        'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                    );
                    $this->CashAndChequeModel->insert('bill_transaction_history',$history);

                    $history=array(
                        'billId'=>$billId,
                        'transactionStatus' =>'Cheque Bounce Penalty',
                        'transactionAmount'=>$newPenaltyForBill,
                        'transactionMode'=>'cr',
                        'remark'=>"Cheque bounce penalty of Rs. ".$newPenaltyForBill.' '.$remBounceDesc,
                        'transactionDate'=>date('Y-m-d H:i:sa'),
                        'empId'=>$transactionEmpId,
                        'transactionBy'=>trim($this->session->userdata[$this->projectSessionName]['id'])
                    );
                    $this->CashAndChequeModel->insert('bill_transaction_history',$history);
                    
                    $billNo=$billInfo[0]['billNo'];
                    $retailerName=$billInfo[0]['retailerName'];
                    $salesman=$billInfo[0]['salesman'];
                    $salesmanCode=$billInfo[0]['salesmanCode'];
                    
                    $salesmanInfo=$this->CashAndChequeModel->getLinkedSalesman('salesman_linking',$salesman,$salesmanCode);
                    if(!empty($salesmanInfo)){
                        $empId=$salesmanInfo[0]['employeeId'];
                        
                        $companyDetails=$this->CashAndChequeModel->getdata('office_details');
                        $officeName=$companyDetails[0]['distributorName'];
                        $distributorCode=$companyDetails[0]['distributorCode'];
                
                        $employeeDetails=$this->CashAndChequeModel->load('employee',$empId);
                        $employeeMobile=$employeeDetails[0]['mobile'];
                        $employeeName=$employeeDetails[0]['name'];
                        $employeeDesignation=$employeeDetails[0]['designation'];
                        $transactionDate=date('M d, Y H:i a');
                
                        $office=$this->CashAndChequeModel->load('office_details','1');
                        $jsonData=array(
                            "flow_id"=>"618d06911d8269225d5988aa",
                            "sender"=>"SIAInc",
                            "mobiles"=>'91'.$employeeMobile,
                            "chequenumber"=>$chequeNo,
                            "chequedate"=>date("d M Y", strtotime($chequeDate)),
                            "retailername"=>$retailerName,
                            "billnumber"=>$billNo,
                            "amount"=>number_format($paidAmountForMsg),
                            "bouncereason"=>substr($reason,0,25)
                        );
                        
                        $jsonData=json_encode($jsonData);
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => "https://api.msg91.com/api/v5/flow/",
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => $jsonData,
                            CURLOPT_HTTPHEADER => array("authkey: 291106Atbm2KHoWhat5d99ec46","content-type: application/JSON"),
                        ));
                        $response = curl_exec($curl);
                        $err = curl_error($curl);
                        curl_close($curl);
                    }
                }
                $result = $this->CashAndChequeModel->update('billpayments',$data, $billPaymentId);
            }
        }
        $data['billpayments']=$this->CashAndChequeModel->getCurrentReconciliationData('billpayments');
        redirect('CashAndChequeController/ChequeReconcilation');
    } 

    public function updateStatusBounced($id) {
        // echo $id;exit;
        $billPayInfo=$this->CashAndChequeModel->load('billpayments',$id);
        // print_r($billPayInfo);exit;
        $chequeNo=$billPayInfo[0]['chequeNo'];
        $chequeBank=$billPayInfo[0]['chequeBank'];
        $chequeDate=$billPayInfo[0]['chequeDate'];

        // print_r($billPayInfo);exit;

        // echo $chequeNo.' '.$chequeBank.' '.$chequeDate;exit;

        $data = array('tempStatus'=>2,'chequeStatus' => "Bounced&Returned",
            'chequeStatusDate' => date("y-m-d"));
        $this->CashAndChequeModel->updateBounceChequeData('billpayments',$data, $chequeNo,$chequeDate,$chequeBank);
        redirect("CashAndChequeController/BounceCheques");
        // if()
        // {
        //      redirect("CashAndChequeController/BounceCheques");
        // } else {
        //     echo "Fail";
        // }
    } 

    public function DesktopBillUpdate() {
        $email=$this->input->post('email');
        $oldSession =  $this->session->userdata('DesktopBill');
        $depositSlipId='Cheque Deposit Slip '.date('d-M-Y H:i');
        $depositSlipDate=date('Y-m-d H:i:sa');

        $excelData=array();
        $transactionDetail=array();

        foreach($oldSession as $items){
            $data=array
            ('id'=> $items['id'],
            'date'=> $items['date'],
            'billId'=> $items['billId'],
            'paidAmount'=> $items['paidAmount'],
            'billAmount'=> $items['billAmount'],
            'balanceAmount'=> $items['balanceAmount'],
            'paymentMode'=> $items['paymentMode'],
            'chequeNo'=> $items['chequeNo'],
            'chequeBank'=> $items['chequeBank'],
            'chequeDate'=> $items['chequeDate'],
            'paymentMode'=> $items['paymentMode'],
            'chequeStatus'=> $items['chequeStatus'],
            'allocationId'=> $items['allocationId'],
            'chequeStatus'=> "Banked",
            'penalty'=> $items['penalty'],
            'chequeStatusDate' => date("Y-m-d H:i:sa"));

            $transactionDetail=array(
                'transactionId'=>$depositSlipId,
                'billPaymentId'=>$items['id'],
                'chequeAmount'=>$items['paidAmount'],
                'createdDate'=>$depositSlipDate
            );    



            $exData=array(
                'billId'=>$items['billId'],
                'chequeNo'=>$items['chequeNo'],
                'chequeDate'=>$items['chequeDate'],
                'chequeBank'=>$items['chequeBank'],
                'chequeAmount'=>$items['paidAmount'],
                'chequeCompany'=>$items['compName']
            );

            $excelData[]=$exData;

            $this->CashAndChequeModel->insert('cheque_deposit_slips',$transactionDetail);     

            $this->CashAndChequeModel->update('billpayments',$data,$items['id']); 
            if($this->db->affected_rows()>0){
                // redirect("CashAndChequeController/DesktopBill");
            } else {
                echo "fails";
            }
        }
        
        $this->createXLS($excelData);//generate excel for latest deposit slip
        $this->EmailSend();
    } 

    public function depositSlipUpdate() {
        $bankDetails=$this->CashAndChequeModel->getDetails('office_details');
        $chequeDepNumber=$bankDetails[0]['distributorName'].' - Cheque Deposit Slip - '.date('d-M-Y-H-i-s');
        $fileName=$bankDetails[0]['distributorName'].' - Cheque Deposit Slip - '.date('d-M-Y-H-i-s').'.xlsx';
        $dateformat=date('d-M-Y');
        $companyName=trim($this->input->post('company'));
        $chequeTillDate=trim($this->input->post('date'));
        $billpaymentsId=$this->input->post('selValue');

        $depositSlipId=$chequeDepNumber;
        $depositSlipDate=date('Y-m-d H:i:sa');
      
        $oldSession=array();
        $excelData=array();
        $transactionDetail=array();

        $totalChequeAmount=0;
        $totalBillPaidId=0;
        if(!empty($billpaymentsId)){
            foreach($billpaymentsId as $billId){
                $no=0;
                $oldSession=$this->CashAndChequeModel->load('billpayments',$billId); 
                if(!empty($oldSession)){
                    $chkData=$this->CashAndChequeModel->getChequeDepositByNo('billpayments',$oldSession[0]['chequeNo'],$oldSession[0]['chequeBank'],$oldSession[0]['chequeDate']); 
                    $totalChequeAmount=$totalChequeAmount+$chkData[0]['chAmount'];
                    $totalBillPaidId=$chkData[0]['id'];
                    
                    foreach($oldSession as $items){
                        $data=array(
                            'chequeStatus'=> "Banked",
                            'chequeStatusDate' => date("Y-m-d H:i:sa")
                        );
                        $this->CashAndChequeModel->updateChequeData('billpayments',$data,$items['chequeNo'],$items['chequeDate'],$items['chequeBank']); 
                    }
                    
                    $transactionDetail=array(
                        'transactionId'=>$depositSlipId,
                        'billPaymentId'=>$chkData[0]['id'],
                        'chequeAmount'=>$chkData[0]['chAmount'],
                        'createdDate'=>$depositSlipDate,
                        'filePath'=>$fileName,
                        'company'=>$companyName,
                        'chequeTillDate'=>$chequeTillDate
                    );   

                    $this->CashAndChequeModel->insert('cheque_deposit_slips',$transactionDetail); 
                    $excelData[]=$chkData;
                }
                $no++;
            }
            $finalCheckDepArray=array();
            if(!empty($excelData)){
                foreach($excelData as $dt){
                    $finalCheckDepArray[]=$dt;
                }
            }
            $this->exportDataToExcel($finalCheckDepArray,$fileName,$chequeDepNumber,$dateformat);
        }else{
            echo "No data selected";
        }
    } 

    public function FeatchPanalty()
    {
        $data=$this->CashAndChequeModel->getdataPenalty('penalty');
        echo json_encode($data);
    }

    public function getPenaltyAmtByText($name){
        $name=urldecode($name);
        $data['penAmt']=$this->CashAndChequeModel->getPenaltyByName('penalty',$name);
        echo json_encode($data);
    }

    public function EmailSend()
    {
        $senderId = $this->session->userdata[$this->projectSessionName]['id'];
        $data['employee'] = $this->CashAndChequeModel->load('employee', $senderId);
        $senderEmail='karanmittal@gmail.com';
        $receiverEmail='karanmittal@gmail.com';
        $name="KIA SALES";

        $subject = "Cheque Deposit Slip For ".$name;

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: <'.$senderEmail.'>' . "\r\n";
  
        $oldSession =  $this->session->userdata('DesktopBill');
        $output="";

        $data['billpayments']=$this->session->userdata('DesktopBill');
        for($j=0;$j<count($data['billpayments']);$j++) {
             $billNo = "";
            $billId= $data['billpayments'][$j]['billId']; // get id in billpayment
            $id = explode(',', $billId);
            $data['bills']=$this->CashAndChequeModel->loadBillNumber('bills',$id);
             $no=0;
            for($i=0;$i<count($data['bills']);$i++) {
                 $no=1+$i;
               $billNo = $data['bills'][$i]['billNo'].", ".$billNo; 
               if($i==0) 
               {
                $retailer = $this->CashAndChequeModel->loadRetailsers('retailer',$data['bills'][$i]['retailerCode']);
                $data['billpayments'][$j]['Name'] = $retailer[0]['name'];
                 $id =$data['billpayments'][$j]['id'];
                $name =$data['billpayments'][$j]['Name'];
                $chequeNo = $data['billpayments'][$j]['chequeNo'];
                $chequeBank = $data['billpayments'][$j]['chequeBank'];
                $chequeDate = $data['billpayments'][$j]['chequeDate'];
                $no=0;        

                $output .='
                <tr>
                    <td>'.$id.'</td>
                    <td>'.$name.'</td>
                    <td>'.$chequeNo.'</td>
                    <td>'.$chequeBank.'</td>
                    <td>'.$chequeDate.'</td>                    
                </tr>';
               }
            }            
            $data['billpayments'][$j]['billNo'] = $billNo;
        }
           
        $message = "<div>
        <h2 style='align:center;'>Smart Distributor</h2>
        <h3>Cheque Deposit Slip</h3>
        <html>
        <head></head>
        <body>
        <table style='align:center;' border='1px'>
            <thead  style='align:center;background-color: #44BDA9;'>
                <tr>
                    <th>Sr.No</th>
                    <th>Retailer Name</th>
                    <th>Cheque No</th>
                    <th>Cheque Bank</th>
                    <th>Cheque Date</th>
                </tr>
            </thead>
            <tbody  style='align:center;background-color: whitesmoke;'>
                ".$output."
            </tbody>
        <table>
        </body>
        </html>
        </div>";
        mail($receiverEmail, $subject, $message, $headers); 
         redirect("CashAndChequeController/DesktopBill");
    }

     public function exportDataToExcel($data,$file,$chequeDepNumber,$dateformat){
        $newFileName= $file;

        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);

        foreach (range('A','J') as $col) {
           $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }

        $chequeTotal=0;
        foreach ($data as $element) {
            // print_r($element);exit;
            $chequeTotal=$chequeTotal+$element[0]['chAmount'];
        }

        $bankDetails=$this->CashAndChequeModel->getDetails('office_details');

        $spreadsheet->getActiveSheet()->SetCellValue('B1', $bankDetails[0]['bankName'])->getStyle('B1:C1')->getFont()->setSize(13);
        $spreadsheet->getActiveSheet()->mergeCells("B1:C1");
        $spreadsheet->getActiveSheet()->getStyle("B1:C1")->getFont()->setBold( true );

        $spreadsheet->getActiveSheet()->SetCellValue('D1', 'Account Number')->getStyle('D1:E1')->getFont()->setSize(13);
        $spreadsheet->getActiveSheet()->mergeCells("D1:E1");
        $spreadsheet->getActiveSheet()->getStyle("D1:E1")->getFont()->setBold( true );

        $spreadsheet->getActiveSheet()->SetCellValue('F1', 'PAN Number')->getStyle('F1:G1')->getFont()->setSize(13);
        $spreadsheet->getActiveSheet()->mergeCells("F1:G1");
        $spreadsheet->getActiveSheet()->getStyle("F1:G1")->getFont()->setBold( true );

        // $spreadsheet->getActiveSheet()->SetCellValue('H1', 'Cheque Deposit Slip Number')->getStyle('H1:I1')->getFont()->setSize(13);
        // $spreadsheet->getActiveSheet()->mergeCells("H1:I1");
        // $spreadsheet->getActiveSheet()->getStyle("H1:I1")->getFont()->setBold( true );

        $spreadsheet->getActiveSheet()->SetCellValue('B2', $bankDetails[0]['address'])->getStyle('B2:C2')->getFont()->setSize(13);
        $spreadsheet->getActiveSheet()->mergeCells("B2:C2");
        $spreadsheet->getActiveSheet()->getStyle("B2:C2")->getFont()->setBold( true );

        $spreadsheet->getActiveSheet()->SetCellValue('D2', $bankDetails[0]['accountNumber'])->getStyle('D2:E2')->getFont()->setSize(13);
        $spreadsheet->getActiveSheet()->mergeCells("D2:E2");
        $spreadsheet->getActiveSheet()->getStyle("D2:E2")->getFont()->setBold( true );

        $spreadsheet->getActiveSheet()->SetCellValue('F2', $bankDetails[0]['panNumber'])->getStyle('F2:G2')->getFont()->setSize(13);
        $spreadsheet->getActiveSheet()->mergeCells("F2:G2");
        $spreadsheet->getActiveSheet()->getStyle("F2:G2")->getFont()->setBold( true );

        // $spreadsheet->getActiveSheet()->SetCellValue('H2', $chequeDepNumber)->getStyle('H2:I2')->getFont()->setSize(13);
        // $spreadsheet->getActiveSheet()->mergeCells("H2:I2");
        // $spreadsheet->getActiveSheet()->getStyle("H2:I2")->getFont()->setBold( true );

        //Define outline border to the cells
        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $spreadsheet->getActiveSheet()->SetCellValue('B4',$bankDetails[0]['distributorName'].' - Cheque Deposit Slip - '.$dateformat)->getStyle('B4:G4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('d1caca');
        $spreadsheet->getActiveSheet()->SetCellValue('B4',$bankDetails[0]['distributorName'].' - Cheque Deposit Slip - '.$dateformat)->getStyle('B4:G4')->getFont()->setSize(16);
        $spreadsheet->getActiveSheet()->mergeCells("B4:G4");
        $spreadsheet->getActiveSheet()->getStyle("B4:G4")->getFont()->setBold( true );
        $spreadsheet->getActiveSheet()->getStyle("B4:G4")->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle("B4:G4")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("B4:G4")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        // $spreadsheet->getActiveSheet()->getColumnDimension("B4:G4")->setAutoSize(true);

        $spreadsheet->getActiveSheet()->SetCellValue('C4', '');
        $spreadsheet->getActiveSheet()->getStyle("C4")->applyFromArray($styleArray);

        $spreadsheet->getActiveSheet()->SetCellValue('D4', '');
        $spreadsheet->getActiveSheet()->getStyle("D4")->applyFromArray($styleArray);

        $spreadsheet->getActiveSheet()->SetCellValue('E4', '');
        $spreadsheet->getActiveSheet()->getStyle("E4")->applyFromArray($styleArray);

        $spreadsheet->getActiveSheet()->SetCellValue('F4', '');
        $spreadsheet->getActiveSheet()->getStyle("F4")->applyFromArray($styleArray);

        $spreadsheet->getActiveSheet()->SetCellValue('G4', '');
        $spreadsheet->getActiveSheet()->getStyle("G4")->applyFromArray($styleArray);

        $spreadsheet->getActiveSheet()->SetCellValue('B5', 'No. of Cheques')->getStyle('B5:C5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('d1caca');
        $spreadsheet->getActiveSheet()->SetCellValue('B5', 'No. of Cheques')->getStyle('B5:C5')->getFont()->setSize(13);
        $spreadsheet->getActiveSheet()->mergeCells("B5:C5");
        $spreadsheet->getActiveSheet()->getStyle("B5:C5")->getFont()->setBold( true );
        $spreadsheet->getActiveSheet()->getStyle("B5:C5")->applyFromArray($styleArray);


        $spreadsheet->getActiveSheet()->SetCellValue('D5', count($data))->getStyle('D5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('d1caca');
        $spreadsheet->getActiveSheet()->SetCellValue('D5', count($data))->getStyle('D5')->getFont()->setSize(13);
        $spreadsheet->getActiveSheet()->getStyle("D5")->getFont()->setBold( true );
        $spreadsheet->getActiveSheet()->getStyle("D5")->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('D5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $spreadsheet->getActiveSheet()->getStyle('D5')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
       

        $spreadsheet->getActiveSheet()->SetCellValue('E5', 'Total Amount')->getStyle('E5:F5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('d1caca');
        $spreadsheet->getActiveSheet()->SetCellValue('E5', 'Total Amount')->getStyle('E5:F5')->getFont()->setSize(13);
        $spreadsheet->getActiveSheet()->mergeCells("E5:F5");
        $spreadsheet->getActiveSheet()->getStyle("E5:F5")->getFont()->setBold( true );
        $spreadsheet->getActiveSheet()->getStyle("E5:F5")->applyFromArray($styleArray);


        $spreadsheet->getActiveSheet()->SetCellValue('G5', 'Rs. '.number_format($chequeTotal))->getStyle('G5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('d1caca');
        $spreadsheet->getActiveSheet()->SetCellValue('G5', 'Rs. '.number_format($chequeTotal))->getStyle('G5')->getFont()->setSize(13);
        $spreadsheet->getActiveSheet()->getStyle("G5")->getFont()->setBold( true );
        $spreadsheet->getActiveSheet()->getStyle("G5")->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('G5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $spreadsheet->getActiveSheet()->getStyle('G5')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $spreadsheet->getActiveSheet()->SetCellValue('B6', 'S. No.')->getStyle('B6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('d1caca');
        $spreadsheet->getActiveSheet()->getStyle("B6")->getFont()->setBold( true );
        $spreadsheet->getActiveSheet()->getStyle("B6")->applyFromArray($styleArray);


        $spreadsheet->getActiveSheet()->SetCellValue('C6', 'Party')->getStyle('C6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('d1caca');
        $spreadsheet->getActiveSheet()->getStyle("C6")->getFont()->setBold( true );
        $spreadsheet->getActiveSheet()->getStyle("C6")->applyFromArray($styleArray);


        $spreadsheet->getActiveSheet()->SetCellValue('D6', 'Cheque No')->getStyle('D6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('d1caca');
        $spreadsheet->getActiveSheet()->getStyle("D6")->getFont()->setBold( true );
        $spreadsheet->getActiveSheet()->getStyle("D6")->applyFromArray($styleArray);


        $spreadsheet->getActiveSheet()->SetCellValue('E6', 'Cheque Date')->getStyle('E6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('d1caca');
        $spreadsheet->getActiveSheet()->getStyle("E6")->getFont()->setBold( true );
        $spreadsheet->getActiveSheet()->getStyle("E6")->applyFromArray($styleArray);


        $spreadsheet->getActiveSheet()->SetCellValue('F6', 'Amount')->getStyle('F6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('d1caca');
        $spreadsheet->getActiveSheet()->getStyle("F6")->getFont()->setBold( true );
        $spreadsheet->getActiveSheet()->getStyle("F6")->applyFromArray($styleArray);


        $spreadsheet->getActiveSheet()->SetCellValue('G6', 'Bank')->getStyle('G6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('d1caca');
        $spreadsheet->getActiveSheet()->getStyle("G6")->getFont()->setBold( true );
        $spreadsheet->getActiveSheet()->getStyle("G6")->applyFromArray($styleArray);


        $no=0;
        $rowCount = 7;
        foreach ($data as $element) {
            $rname="";
            if($element[0]['billId']>0){
                $retailer=$this->CashAndChequeModel->getRetailerbyBills('bills',$element[0]['billId']);
                $rname=$rname.$retailer.',';
            }
            $rname=trim($rname,',');
            $newDate = date("d-M-Y", strtotime($element[0]['chequeDate']));

            $no++;
            $spreadsheet->getActiveSheet()->SetCellValue('B' . $rowCount, $no);
            $spreadsheet->getActiveSheet()->getStyle('B' . $rowCount)->applyFromArray($styleArray);

            $spreadsheet->getActiveSheet()->SetCellValue('C' . $rowCount, $rname);
            $spreadsheet->getActiveSheet()->getStyle('C' . $rowCount)->applyFromArray($styleArray);

            $spreadsheet->getActiveSheet()->SetCellValue('D' . $rowCount, $element[0]['chequeNo']);
            $spreadsheet->getActiveSheet()->getStyle('D' . $rowCount)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('D' . $rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $spreadsheet->getActiveSheet()->getStyle('D' . $rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
       
            $spreadsheet->getActiveSheet()->SetCellValue('E' . $rowCount, $newDate);
            $spreadsheet->getActiveSheet()->getStyle('E' . $rowCount)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('E' . $rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $spreadsheet->getActiveSheet()->getStyle('E' . $rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
       
            $spreadsheet->getActiveSheet()->SetCellValue('F' . $rowCount, number_format($element[0]['chAmount']));
            $spreadsheet->getActiveSheet()->getStyle('F' . $rowCount)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('F' . $rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $spreadsheet->getActiveSheet()->getStyle('F' . $rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
       
            $spreadsheet->getActiveSheet()->SetCellValue('G' . $rowCount, $element[0]['chequeBank']);
            $spreadsheet->getActiveSheet()->getStyle('G' . $rowCount)->applyFromArray($styleArray);

            $rowCount++;
        }
        $writer = new Xlsx($spreadsheet);
        $fileName=$file;
        
        $upload_path='./assets/deliveryslips/'.$fileName;
        $writer->save($upload_path);
        echo $newFileName;
    }

    public function splitCheques(){
        $billpaymentId=trim($this->input->post('id'));
        // echo $billpaymentId;
       
        $billpaymentData=$this->CashAndChequeModel->load('billpayments',$billpaymentId);
        // $billId=$billpaymentData[0]['id'];
        $bank=$this->CashAndChequeModel->getDetails('bank');
        $company=$this->CashAndChequeModel->getDetails('company');

        $rname="";
        $billNo="";
        $paymentMode=$billpaymentData[0]['paymentMode'];
        // echo $paymentMode;exit;

        if(!empty($billpaymentData)){
            $billComp=$this->CashAndChequeModel->load('bills',$billpaymentData[0]['billId']);
            if($billpaymentData[0]['billId']>0){
                $retailer=$this->CashAndChequeModel->getRetailerbyBills('bills',$billpaymentData[0]['billId']);
                $rname=$rname.$retailer.',';
            }

            if($billpaymentData[0]['billId']>0){
                $billDetail=$this->CashAndChequeModel->load('bills',$billpaymentData[0]['billId']);
                $billNo=$billDetail[0]['billNo'];
            }
            $rname= trim($rname,',');

            $dt=date_create($billpaymentData[0]['chequeDate']);
            $chequeDate = date_format($dt,'d-m-Y');

            if($paymentMode==="Cheque"){
    ?>
    <div class="body">
        <div class="row">                                 
            <div class="row m-t-20">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped table-hover dataTable" data-page-length='100'>
                        <thead>
                        <tr class="gray">
                            <th>Party</th>
                            <th>Bill no</th>
                            <th> Amount  </th>
                            <th> Company  </th>
                        </tr>
                      </thead>

                      <tbody>
                        <tr>
                            <td><?php if(!empty($rname)){ echo $rname; }else{ echo $billpaymentData[0]['retailerName'];}?></td>
                            <td><?php echo $billNo; ?></td>
                            <td><?php echo $billpaymentData[0]['paidAmount']?></td>
                            <td><?php echo $billComp[0]['compName']?></td>
                        </tr>
                      </tbody>
                  </table>
                </div>
        <form method="post" role="form" onsubmit="return checkAmount();" action="<?php echo site_url('CashAndChequeController/insertSplitCheques'); ?>">         
                <div class="col-md-12">
                    <table id="myTable" class="table table-bordered table-striped table-hover dataTable" data-page-length='100'>
                      <tbody>

                        <input type="hidden" id="billpaymentId" name="billpaymentId" value="<?php echo $billpaymentId; ?>">
                        <input type="hidden" id="billComp" name="billComp" value="<?php echo $billComp[0]['compName']; ?>">
                        <input type="hidden" id="paymentMode" name="paymentMode" value="<?php echo $paymentMode; ?>">
                        <input type="hidden" id="billpaymentAmount" name="billpaymentAmount" value="<?php echo $billpaymentData[0]['paidAmount']; ?>">

                        <datalist id='bnkList'>
                            <?php foreach ($bank as $bnk) { ?>
                                   <option><?php echo $bnk['name']; ?></option> 
                            <?php } ?>
                        </datalist>

                        <datalist id='cmpList'>
                            <?php foreach ($company as $cmp) { ?>
                                   <option><?php echo $cmp['name']; ?></option> 
                            <?php } ?>
                        </datalist>

                        <tr>
                            <td><input type="text" onkeypress="return numbersonly(this, event);" id="chAmt1" name="amount[]" autocomplete="off" placeholder="amount" class="form-control" required></td>
                            <td><input type="text" onkeypress="return numbersonly(this, event);" id="chNo1" name="chequeNo[]" autocomplete="off" placeholder="cheque no." class="form-control" required></td>
                            <td><input type="date" id="chDate1" name="date[]" onblur="dupAdHocChequeEntry1();" autocomplete="off" placeholder="cheque date" class="form-control" required><div style="color:red;" id="chkNoErr1"></div></td>
                            <td><input type="text" id="chBank1" name="bank[]" autocomplete="off" list="bnkList" placeholder="bank name" class="form-control" required></td>
                            <td>
                                <button type="button" onclick="addNewRow();">+</button>
                                <button type="button" onclick="deleterow();">-</button>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="text" onkeypress="return numbersonly(this, event);" id="chAmt2" name="amount[]" autocomplete="off" placeholder="amount" class="form-control" required></td>
                            <td><input type="text" onkeypress="return numbersonly(this, event);" id="chNo2" name="chequeNo[]" autocomplete="off" placeholder="cheque no." class="form-control" required></td>
                            <td><input type="date" id="chDate2" name="date[]" onblur="dupAdHocChequeEntry2();" autocomplete="off" placeholder="cheque date" class="form-control" required><div style="color:red;" id="chkNoErr2"></div></td>
                            <td><input type="text" id="chBank2" name="bank[]" autocomplete="off" list="bnkList" placeholder="bank name" class="form-control" required></td>
                        </tr>
                       
                      </tbody>
                  </table>

                </div>

                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary m-t-15 waves-effect">
                        <i class="material-icons">save</i><span class="icon-name">Save</span>
                    </button>
                
                    <button data-dismiss="modal" type="button" class="btn btn-danger m-t-15 waves-effect">
                            <i class="material-icons">cancel</i><span class="icon-name">cancel</span>
                    </button>
                </div>
            </form>
                </div>
            </div>
        </div>
    </div>
        
    <?php
            } else {
    ?>
       <div class="body">
        <div class="row">                                 
            <div class="row m-t-20">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped table-hover dataTable" data-page-length='100'>
                        <thead>
                        <tr class="gray">
                            <th>Party</th>
                            <th>Bill no</th>
                            <th> Amount  </th>
                            <th> Company  </th>
                        </tr>
                      </thead>

                      <tbody>
                        <tr>
                            <td><?php if(!empty($rname)){ echo $rname; }else{ echo $billpaymentData[0]['retailerName'];}?></td>
                            <td><?php echo $billNo; ?></td>
                            <td><?php echo $billpaymentData[0]['paidAmount']?></td>
                            <td><?php echo $billComp[0]['compName']?></td>
                        </tr>
                      </tbody>
                  </table>
                </div>
        <form method="post" role="form" onsubmit="return checkNeftAmount();" action="<?php echo site_url('CashAndChequeController/insertSplitNeft'); ?>">         
                <div class="col-md-12">
                    <table id="myTable" class="table table-bordered table-striped table-hover dataTable" data-page-length='100'>
                      <tbody>

                        <input type="hidden" id="billpaymentId" name="billpaymentId" value="<?php echo $billpaymentId; ?>">
                        <input type="hidden" id="billComp" name="billComp" value="<?php echo $billComp[0]['compName']; ?>">
                        <input type="hidden" id="paymentMode" name="paymentMode" value="<?php echo $paymentMode; ?>">
                        <input type="hidden" id="billpaymentAmount" name="billpaymentAmount" value="<?php echo $billpaymentData[0]['paidAmount']; ?>">

                        <datalist id='bnkList'>
                            <?php foreach ($bank as $bnk) { ?>
                                   <option><?php echo $bnk['name']; ?></option> 
                            <?php } ?>
                        </datalist>

                        <datalist id='cmpList'>
                            <?php foreach ($company as $cmp) { ?>
                                   <option><?php echo $cmp['name']; ?></option> 
                            <?php } ?>
                        </datalist>

                        <tr>
                            <td><input type="text" onkeypress="return numbersonly(this, event);" id="neftAmt1" name="amount[]" autocomplete="off" placeholder="amount" class="form-control" required></td>
                            <td><input type="text" id="neftNo1" name="chequeNo[]" autocomplete="off" placeholder="neft no" class="form-control" required></td>
                            <td><input type="date" id="neftDate1" name="date[]" onblur="dupNeftEntry1();" autocomplete="off" placeholder="cheque date" class="form-control" required><div style="color:red;" id="neftNoErr1"></div></td>
                            <td>
                                <button type="button" onclick="addNewNeftRow();">+</button>
                                <button type="button" onclick="deleterow();">-</button>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="text" onkeypress="return numbersonly(this, event);" id="neftAmt2" name="amount[]" autocomplete="off" placeholder="amount" class="form-control" required></td>
                            <td><input type="text" id="neftNo2" name="chequeNo[]" autocomplete="off" placeholder="neft no" class="form-control" required></td>
                            <td><input type="date" id="neftDate2" name="date[]" onblur="dupNeftEntry2();" autocomplete="off" placeholder="cheque date" class="form-control" required><div style="color:red;" id="neftNoErr2"></div></td>
                        </tr>
                       
                      </tbody>
                  </table>

                </div>

                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary m-t-15 waves-effect">
                        <i class="material-icons">save</i><span class="icon-name">Save</span>
                    </button>
                
                    <button data-dismiss="modal" type="button" class="btn btn-danger m-t-15 waves-effect">
                            <i class="material-icons">cancel</i><span class="icon-name">cancel</span>
                    </button>
                </div>
            </form>
                </div>
            </div>
        </div>
    </div>
    <?php
            }

        }
    }


    public function insertSplitCheques(){
        $billpaymentId=$this->input->post('billpaymentId');
        $billComp=$this->input->post('billComp');
      
        $billDetails=$this->CashAndChequeModel->load('billpayments',$billpaymentId);
        // print_r($billDetails);exit;

        $billId=$billDetails[0]['billId'];
        $billItemDetail=$this->CashAndChequeModel->load('bills',$billId);

        $billNo=$billItemDetail[0]['billNo'];
        $retailerName=$billItemDetail[0]['retailerName'];

        $empId=$billDetails[0]['empId'];
        $allocationId=$billDetails[0]['allocationId'];
        $date=$billDetails[0]['date'];
        $billAmount=$billDetails[0]['billAmount'];
        $balanceAmount=$billDetails[0]['balanceAmount'];
        $paymentMode=$billDetails[0]['paymentMode'];
        // $billNo=$billDetails[0]['billNo'];
        $compName=$billComp;


        $isAllocationClosed=$billDetails[0]['isAllocationClosed'];
        $allocationCloseDate=$billDetails[0]['allocationCloseDate'];

        // $retailerName=$billDetails[0]['retailerName'];
        $isLostStatus=$billDetails[0]['isLostStatus'];
        $isOfficeCheque=$billDetails[0]['isOfficeCheque'];
        // $chequeDate=date('Y-m-d H:i:sa');
        $chequeReceivedDate=date('Y-m-d H:i:sa');
        $chequeStatusDate=date('Y-m-d H:i:sa');

        $amount=$this->input->post('amount');
        $chequeNo=$this->input->post('chequeNo');
        $chequeDate=$this->input->post('date');
        $bank=$this->input->post('bank');
        
        $this->CashAndChequeModel->delete('billpayments',$billpaymentId);
        if($this->db->affected_rows()>0){
            for($i=0;$i<count($amount);$i++){
                $insertData=array(
                    'billId'=>$billId,
                    'empId'=>$empId,
                    'allocationId'=>$allocationId,
                    'date'=>$date,
                    'paidAmount'=>$amount[$i],
                    'billAmount'=>$billAmount,
                    'balanceAmount'=>$balanceAmount,
                    'paymentMode'=>$paymentMode,
                    'chequeBank'=>$bank[$i],
                    'chequeDate'=>$chequeDate[$i],
                    'chequeReceivedDate'=>$chequeReceivedDate,
                    'chequeStatusDate'=>$chequeStatusDate,
                    'billNo'=>$billNo,
                    'compName'=>$compName,
                    'retailerName'=>$retailerName,
                    'isLostStatus'=>$isLostStatus,
                    'isOfficeCheque'=>$isOfficeCheque,
                    'chequeNo'=>$chequeNo[$i],
                    'chequeStatus'=>'New',
                    'isAllocationClosed'=>$isAllocationClosed,
                    'allocationCloseDate'=>$allocationCloseDate
                );

                // print_r($insertData);exit;

                $this->CashAndChequeModel->insert('billpayments',$insertData);
             }
             echo "<script>alert('record inserted');</script>";
             redirect('CashAndChequeController');
        }else{
             echo "<script>alert('record not inserted');</script>";
             redirect('CashAndChequeController');
        }
    }

    public function insertSplitNeft(){
        $billpaymentId=$this->input->post('billpaymentId');
        $billComp=$this->input->post('billComp');
      
        $billDetails=$this->CashAndChequeModel->load('billpayments',$billpaymentId);
        // print_r($billDetails);exit;

        $billId=$billDetails[0]['billId'];
        $empId=$billDetails[0]['empId'];
        $allocationId=$billDetails[0]['allocationId'];
        $date=$billDetails[0]['date'];
        $billAmount=$billDetails[0]['billAmount'];
        $balanceAmount=$billDetails[0]['balanceAmount'];
        $paymentMode=$billDetails[0]['paymentMode'];
        $billNo=$billDetails[0]['billNo'];
        $compName=$billComp;

        $isAllocationClosed=$billDetails[0]['isAllocationClosed'];
        $allocationCloseDate=$billDetails[0]['allocationCloseDate'];

        $retailerName=$billDetails[0]['retailerName'];
        $isLostStatus=$billDetails[0]['isLostStatus'];
        $isOfficeCheque=$billDetails[0]['isOfficeCheque'];
        // $chequeDate=date('Y-m-d H:i:sa');
        $chequeReceivedDate=date('Y-m-d H:i:sa');
        $chequeStatusDate=date('Y-m-d H:i:sa');

        $amount=$this->input->post('amount');
        $chequeNo=$this->input->post('chequeNo');
        $chequeDate=$this->input->post('date');
        
        $this->CashAndChequeModel->delete('billpayments',$billpaymentId);
        if($this->db->affected_rows()>0){
            for($i=0;$i<count($amount);$i++){
                $insertData=array(
                    'billId'=>$billId,
                    'empId'=>$empId,
                    'allocationId'=>$allocationId,
                    'date'=>$date,
                    'paidAmount'=>$amount[$i],
                    'billAmount'=>$billAmount,
                    'balanceAmount'=>$balanceAmount,
                    'paymentMode'=>$paymentMode,
                    'neftDate'=>$chequeDate[$i],
                    'chequeReceivedDate'=>$chequeReceivedDate,
                    'chequeStatusDate'=>$chequeStatusDate,
                    'billNo'=>$billNo,
                    'compName'=>$compName,
                    'retailerName'=>$retailerName,
                    'isLostStatus'=>$isLostStatus,
                    'isOfficeCheque'=>$isOfficeCheque,
                    'neftNo'=>$chequeNo[$i],
                    'chequeStatus'=>'New',
                    'isAllocationClosed'=>$isAllocationClosed,
                    'allocationCloseDate'=>$allocationCloseDate
                );

                // print_r($insertData);exit;

                $this->CashAndChequeModel->insert('billpayments',$insertData);
             }
             echo "<script>alert('record inserted');</script>";
             redirect('CashAndChequeController');
        }else{
             echo "<script>alert('record not inserted');</script>";
             redirect('CashAndChequeController');
        }
    }

    public function addChequeInDepositSlip(){
        $billId=$this->input->post('billId');
        $billNo=$this->input->post('billNo');
        $billAmount=$this->input->post('billAmount');
        $chequeNo=$this->input->post('chequeNo');
        $neftNo = $this->input->post('neftNo');
        $chequeDate = $this->input->post('chequeDate');
        $chequeBank= $this->input->post('chequeBank');
        $company=$this->input->post('company');
        $mode = $this->input->post('mode');
        $retailer = $this->input->post('retailer');

        if(empty($billId)){
            echo "Please select Cheque";exit;
        }

        if(empty($billNo)){
            echo "Please select Cheques";exit;
        }

        if(($billAmount == "") || ($chequeNo == "") || ($chequeDate == "") || ($chequeBank == "") || ($company == "")){
            echo "Please enter all details";
        }else{
            $bankDetail=$this->CashAndChequeModel->findBank('bank',$chequeBank);
            //if bank not present in database then insert bank name
            if(empty($bankDetail) && $chequeBank !=''){
                $bankInsert=array('name'=>$chequeBank);
                $this->CashAndChequeModel->insert('bank',$bankInsert);
            }
            
            $billNo=implode(',',array_unique(explode(',',$billNo[0])));
            $billNo=trim($billNo,',');
            $billNo=explode(',',$billNo);
          
            $newBillNo="";
            foreach($billNo as $bill){
                $newBillNo=$newBillNo.', '.$bill;
            }
            $newBillNo=trim($newBillNo,',');

            $clubBillNo="";
            $clubRetailer="";
            $isNeftNoPresent=$this->CashAndChequeModel->findChequeWithBank('billpayments',$chequeNo,$chequeDate,$chequeBank);
            if((empty($isNeftNoPresent))){
                echo "";
            }else{
                foreach($isNeftNoPresent as $itm){
                    $clubBillNo=$clubBillNo.",".$itm['billNo'];
                    $clubRetailer=$clubRetailer.",".$itm['retailerName'];
                }
            }
            $clubBillNo=$clubBillNo.','.$newBillNo;
            $clubRetailer=$clubRetailer.','.$retailer;

            $clubBillNo=trim($clubBillNo,',');
            $clubRetailer=trim($clubRetailer,',');
            
            $clubBillNo=array_unique(explode(',',$clubBillNo));
            $clubBillNo=implode(',',$clubBillNo);
            $clubBillNo=trim($clubBillNo,',');

            $clubRetailer=array_unique(explode(',',$clubRetailer));
            $clubRetailer=implode(',',$clubRetailer);
            $clubRetailer=trim($clubRetailer,',');

            // print_r($clubRetailer);exit;

            $billId=implode(',',array_unique(explode(',',$billId[0])));
            $billId=trim($billId,',');
            $billId=explode(',',$billId);

            $rc=0;
            $receivedDate=date("Y-m-d H:i:sa");

            foreach($billId as $rec){
                $updData=array(
                    'neftNo'=>$neftNo,
                    'chequeNo'=>$chequeNo,
                    'chequeBank'=>$chequeBank,
                    'chequeDate'=>$chequeDate,
                    'chequeReceivedDate'=>$receivedDate,
                    'neftDate'=>$chequeDate,
                    'chequeStatusDate'=>$receivedDate,
                    'chequeStatus'=>'New',
                    'compName'=>trim($company,','),
                    'billNo'=>$clubBillNo,
                    'retailerName'=>$clubRetailer
                );
                // print_r($updData);exit;
                $this->CashAndChequeModel->update('billpayments',$updData,$billId[$rc]);
                $rc++;
            }

            if($chequeNo !="" && $chequeDate !=="" && $chequeBank !==""){
                $updChequeDetail=array(
                    'billNo'=>$clubBillNo,
                    'retailerName'=>$clubRetailer
                );
                $this->CashAndChequeModel->updateChequeData('billpayments',$updChequeDetail,$chequeNo,$chequeDate,$chequeBank);//update billpayment data
            }
            echo "Record updated";
        }
        
    }

    public function addNeftInNeftRegister(){
        $billId=$this->input->post('billId');
        $billNo=$this->input->post('billNo');
        $billAmount=$this->input->post('billAmount');
        $chequeNo=$this->input->post('chequeNo');
        $neftNo = $this->input->post('neftNo');
        $chequeDate = $this->input->post('chequeDate');
        $chequeBank= $this->input->post('chequeBank');
        $company=$this->input->post('company');
        $mode = $this->input->post('mode');
        $retailer = $this->input->post('retailer');
       
        if(empty($billId)){
            echo "Please select Cheque";exit;
        }

        if(empty($billNo)){
            echo "Please select Cheques";exit;
        }

        // echo $billAmount.' '.$neftNo.' '.$chequeDate.' '.$company;exit;

        if(($billAmount == "") || ($neftNo == "") || ($chequeDate == "") || ($company == "")){
            echo "Please enter all details";exit;
        }else{
            $bankDetail=$this->CashAndChequeModel->findBank('bank',$chequeBank);
            //if bank not present in database then insert bank name
            if(empty($bankDetail) && $chequeBank !=''){
                $bankInsert=array('name'=>$chequeBank);
                $this->CashAndChequeModel->insert('bank',$bankInsert);
            }
            
            $newBillNo="";
            // if(!empty($billNo)){
                foreach($billNo as $bill){
                    $newBillNo=$newBillNo.', '.$bill;
                }
            // }
           
            $newBillNo=trim($newBillNo,',');

            $newRetailer=$retailer;
            // if(!empty($retailer)){
            //     foreach($retailer as $ret){
            //         $newRetailer=$newRetailer.', '.$ret;
            //     }
            // }
            $newRetailer=trim($newRetailer,',');


            $clubBillNo="";
            $clubRetailer="";
            $isNeftNoPresent=$this->CashAndChequeModel->findNeft('billpayments',$neftNo,$chequeDate);
            if((empty($isNeftNoPresent))){
                echo "";
            }else{
                foreach($isNeftNoPresent as $itm){
                    $clubBillNo=$clubBillNo.",".$itm['billNo'];
                    $clubRetailer=$clubRetailer.",".$itm['retailerName'];
                }
            }
            $clubBillNo=$clubBillNo.','.$newBillNo;
            $clubRetailer=$clubRetailer.','.$newRetailer;

            $clubBillNo=trim($clubBillNo,',');
            $clubRetailer=trim($clubRetailer,',');

            $clubRetailer=array_unique(explode(',',$clubRetailer));
            $clubRetailer=implode(',',$clubRetailer);
            $clubRetailer=trim($clubRetailer,',');

            $billId=implode(',',array_unique(explode(',',$billId[0])));
            $billId=trim($billId,',');
            $billId=explode(',',$billId);

            $rc=0;
            $receivedDate=date("Y-m-d H:i:sa");

            foreach($billId as $rec){
                $updData=array(
                    'neftNo'=>$neftNo,
                    'chequeNo'=>$chequeNo,
                    'chequeBank'=>$chequeBank,
                    'chequeDate'=>$chequeDate,
                    'chequeReceivedDate'=>$receivedDate,
                    'neftDate'=>$chequeDate,
                    'chequeStatusDate'=>$receivedDate,
                    'chequeStatus'=>'New',
                    'compName'=>trim($company,','),
                    'billNo'=>$clubBillNo,
                    'retailerName'=>$clubRetailer
                );
                $this->CashAndChequeModel->update('billpayments',$updData,$billId[$rc]);
                $rc++;
            }

            if($neftNo !="" && $chequeDate !=""){
                $updChequeDetail=array(
                    'billNo'=>$clubBillNo,
                    'retailerName'=>$clubRetailer
                );
                $this->CashAndChequeModel->updateNeftData('billpayments',$updChequeDetail,$neftNo,$chequeDate);//update billpayment data
                
            }
            echo "Record updated";
        }
    }

}