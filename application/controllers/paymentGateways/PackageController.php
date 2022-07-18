<?php
Class PackageController extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('email');
        $this->load->model('PaymentGatewayModel');
        date_default_timezone_set("Asia/Calcutta");
        ini_set('memory_limit', '-1');
        
        //for TEST
        // $this->appId="142580bbcfc7cf14f799f81365085241";
        // $this->secretKey="93cacc8ce549e7a0fda5d196734cf468757bca03";
        // $this->mode="TEST";
        
        //for Production
        $this->appId="191720b1a7f39c4fe857339a51027191";
        $this->secretKey="876deeb94f7aaa3f8e110af7e4823b4ad7c838ca";
        $this->mode="PROD";
        
        require_once('application/libraries/tcpdf/tcpdf.php') ;
    }

    public function testData(){
        $invoicesId = $this->PaymentGatewayModel->getLastInvoice('invoices');
        $lastInvoice = $invoicesId[0]['invoiceNumber'];
        $invoice_date = date("Y-m-d H:i:s");
        $currentYear = date('Y');  // current year
        $currentMonth = date('m'); // current month

        $getAllInvoice=0;
        if($currentMonth>4){
            $year = $currentYear+1;    //23
            $yearLastDigit= substr( $year, -2);
            $detail = $this->PaymentGatewayModel->getLastOneInvoice('invoices', $yearLastDigit);

            if(!empty($detail)){
                $getAllInvoice=$detail[0]['id'];
            }
        }else{
            $year = $currentYear;      //22
            $yearLastDigit= substr( $year, -2);
            $detail = $this->PaymentGatewayModel->getLastOneInvoice('invoices', $yearLastDigit);
            if(!empty($detail)){
                $getAllInvoice=$detail[0]['id'];
            }
        }  

        //invoice number generation
        if($lastInvoice == null || $lastInvoice == ''){
            $invoice_id = "SIA".$yearLastDigit."00001";
        }else{
            $lastPlus = $getAllInvoice + 1;
            $invoice_id1 = $yearLastDigit."00000";
            $invoice_id2 = $invoice_id1 + $lastPlus;
            $invoice_id = "SIA".$invoice_id2;
        }  

        // echo $invoice_id;
    }

    public function checkout(){
            $mode = $this->mode; //<-------- Change to TEST for test server, PROD for production
            
            $userid = ($this->session->userdata['paymentSession']['id']);
            $customerdata=$this->PaymentGatewayModel->getUserById('distributors_details',$userid);
            $gstStateCode=$customerdata[0]['gstStateCode'];

            $pk_id=trim($this->input->post('pid')); 
            $pkgName=trim($this->input->post('pkgName')); 
            $pkgDuration=trim($this->input->post('selectedDuration'));
        
            $gstStateCheck=trim($this->input->post('gstStateCheck')); 
            $gstStateCode1=trim($this->input->post('gstStateCode1'));

            $gstPercent=trim($this->input->post('ofcGstValue')); // GST %
            $sacCode=trim($this->input->post('ofcSacValue')); // SAC Code
            $convenienceFeeSacCode=trim($this->input->post('convenienceFeeSacCode')); //Convenience Fee SAC Code

            $cgstAmt=trim($this->input->post('cgst')); 
            $sgstAmt=trim($this->input->post('sgst')); 
            $igstAmt=trim($this->input->post('igst')); 
            $taxAmt=trim($this->input->post('taxAmt')); 
            $orderAmount1=trim($this->input->post('pkgAmount')); 
            $orderAmount=trim($this->input->post('totalAmount')); 
            $totalDays=trim($this->input->post('totalDays')); 

            if ($gstStateCheck == $gstStateCode1) {
                $cgst = $gstPercent/2;
                $sgst = $gstPercent/2;
                $igst = 0;
            }else{
                $cgst = 0;
                $sgst = 0;
                $igst = $gstPercent;
            }
            
            $this->session->set_userdata('pkgName', $pkgName);
            $this->session->set_userdata('pkgDuration', $pkgDuration);

            $date   = new DateTime(); //this returns the current date time
                $result = $date->format('Y-m-d');
                $krr    = explode('-', $result);
                $result = implode("", $krr);
            
            $secretKey = $this->secretKey;
            $appId=$this->appId;
            $orderId='Order'.$result.rand(0,500);
            $cId=$result.rand(0,100);

            $customerCode=$customerdata[0]['code'];
            $customerName=$customerdata[0]['name'];
            $customerEmail=$customerdata[0]['email'];
            $customerPhone=$customerdata[0]['mobile'];
            $customerValid_date=$customerdata[0]['validTill'];

            $customerPartnerId=$customerdata[0]['partnerId'];
            $gstNumber=$customerdata[0]['gstNumber'];
            $panNumber=$customerdata[0]['panNumber'];
            $gstStateCode=$customerdata[0]['gstStateCode'];
            $gstState=$customerdata[0]['gstState'];
            $address=$customerdata[0]['address'];
            $rechargeDate = date("Y-m-d H:i:s");

            $orderCurrency='INR';
           
            $returnUrl=site_url("paymentGateways/PackageController/payment_status/".$cId);
            $notifyUrl=site_url("paymentGateways/PackageController/payment_status/".$cId);

            $rechargeFromDate = date("Y-m-d");  //da
            $rechargeToDate = date("Y-m-d");  //da
            if($pkgDuration == 1){
                
                if($customerValid_date == null || $customerValid_date == 0000-00-00)
                {
                    $rechargeFromDate = date("Y-m-d");  //date("Y-m-d H:i:s");
                    $rechargeToDate = date('Y-m-d', strtotime($rechargeFromDate. '+1 month'));
                }else{
                  
                    $forOdNextMonth= date('Y-m-d', strtotime("+1 days", strtotime($customerValid_date)));
                    $forOdNextMonth= date('Y-m-d', strtotime("+1 month", strtotime($forOdNextMonth)));
                    $forOdNextMonth= date('Y-m-d', strtotime("-1 days", strtotime($forOdNextMonth)));
                    
                    $rechargeFromDate = date('Y-m-d', strtotime($customerValid_date. '+1 days'));
                    $rechargeToDate = $forOdNextMonth;
                }
            
            }else if($pkgDuration == 3){
                $rechargeFromDate = date("Y-m-d");  //da
                if($customerValid_date == null || $customerValid_date == 0000-00-00){
                    $rechargeFromDate = date("Y-m-d");  //date("Y-m-d H:i:s");
                    $rechargeToDate = date('Y-m-d', strtotime($rechargeFromDate. ' +3 month'));
                }else{
                    $forOdNextMonth= date('Y-m-d', strtotime("+1 days", strtotime($customerValid_date)));
                    $forOdNextMonth= date('Y-m-d', strtotime("+3 month", strtotime($forOdNextMonth)));
                    $forOdNextMonth= date('Y-m-d', strtotime("-1 days", strtotime($forOdNextMonth)));
                    
                    $rechargeFromDate = date('Y-m-d', strtotime($customerValid_date. '+1 days'));
                    $rechargeToDate = $forOdNextMonth;
                }
            }
              
            //insert transaction entry for new transaction
            $invoiceData1 = array( 
                "cid" => $cId,
                "orderId" => $orderId,
                "distributorId" => $userid,
                "distributorCode" => $customerCode,
                "distributorName" => $customerName,
                "partnerId" => $customerPartnerId,
                "contact" => $customerPhone,
                "email" => $customerEmail,
                "gstNumber" => $gstNumber,
                "panNumber" => $panNumber,
                "gstStateCode" => $gstStateCode,
                "gstState" => $gstState,
                "address" => $address,
                "rechargeDate" => $rechargeDate,
                "packageId" => $pk_id,
                "packageName" => $pkgName,
                "duration" => $pkgDuration,
                "packageAmount" => $orderAmount1,
                "sacCode" =>$sacCode,
                "convenienceFeeSacCode"=>$convenienceFeeSacCode,
                "cgstPercent" => $cgst,
                "sgstPercent" => $sgst,
                "igstPercent" => $igst,
                "cgstAmount" => $cgstAmt,
                "sgstAmount" => $sgstAmt,
                "igstAmount" => $igstAmt,
                "taxableAmount" => $orderAmount1,
                "taxAmount" => $taxAmt,
                "netAmount" => $orderAmount,
                "rechargeFromDate" => $rechargeFromDate,
                "rechargeToDate" => $rechargeToDate
            );
            $this->PaymentGatewayModel->insert('transaction',$invoiceData1);
           
            
            $orderNote='KIAS validity extension till '.date('d-M-Y',strtotime($rechargeToDate)).' for '.$customerName;
            $postData = array( 
                "appId" => $appId, 
                "orderId" => $orderId, 
                "orderAmount" => $orderAmount, 
                "orderCurrency" => $orderCurrency, 
                "orderNote" => $orderNote, 
                "customerName" => $customerName, 
                "customerPhone" => $customerPhone, 
                "customerEmail" => $customerEmail,
                "returnUrl" => $returnUrl, 
                "notifyUrl" => $notifyUrl
            );

            ksort($postData);
            $signatureData = "";
            foreach ($postData as $key => $value){
                $signatureData .= $key.$value;
            }

            $signature = hash_hmac('sha256', $signatureData, $secretKey,true);
            $signature = base64_encode($signature);

            if ($mode == "PROD") {
            $url = "https://www.cashfree.com/checkout/post/submit";
            } else {
            $url = "https://test.cashfree.com/billpay/checkout/post/submit";
            }
            $this->load->view('paymentGateway/payment-checkout',['postData'=>$postData,'signature'=>$signature,'url'=>$url]);
    }

    public function payment_status(){   
        $cId = $this->uri->segment(4, 0);
        
        if(isset($_POST["txMsg"])){
            $secretkey = $this->secretKey;
            $orderId = $_POST["orderId"];
            $orderAmount = $_POST["orderAmount"];
            $referenceId = $_POST["referenceId"];
            $txStatus = $_POST["txStatus"];
            $paymentMode = $_POST["paymentMode"];
            $txMsg = $_POST["txMsg"];
            $txTime = $_POST["txTime"];
            $signature = $_POST["signature"];
    
            $curl = curl_init();
            curl_setopt_array($curl, array(
                // CURLOPT_URL => "https://sandbox.cashfree.com/pg/orders/".$orderId, //test
                CURLOPT_URL => "https://api.cashfree.com/pg/orders/".$orderId,     //live
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                // CURLOPT_POSTFIELDS => $jsonData,
                CURLOPT_HTTPHEADER => array("x-client-id: ".$this->appId,"x-api-version: 2022-01-01","x-client-secret: ".$this->secretKey,"content-type: application/JSON"),
            ));
            $response = curl_exec($curl);
    
            $err = curl_error($curl);
            curl_close($curl);

            //generate expiry date for 24 hrs
            $today=date('Y-m-d H:i:s');
    		$orderDate = date('Y-m-d H:i:s',strtotime('+1 days', strtotime($today)));
    		$todayDateTime = new DateTime($orderDate);
    		$todayDateTime=$todayDateTime->format(DateTime::ATOM);
                       
            $data_response = json_decode($response, true);
            $status = $data_response['order_status'];
            $order_token = $data_response['order_token'];
            $paymentLinkForPayment= $data_response['payment_link'];
            $order_expiry_time = $todayDateTime;
            $cf_order_id = $data_response['cf_order_id'];
    
            $curl = curl_init();
            curl_setopt_array($curl, array(
                // CURLOPT_URL => "https://sandbox.cashfree.com/pg/orders/".$orderId."/payments/".$referenceId,     //test
                CURLOPT_URL => "https://api.cashfree.com/pg/orders/".$orderId."/payments/".$referenceId,     //live
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                // CURLOPT_POSTFIELDS => $jsonData,
                CURLOPT_HTTPHEADER => array("x-client-id: ".$this->appId,"x-api-version: 2022-01-01","x-client-secret: ".$this->secretKey,"content-type: application/JSON"),
            ));
            $payment_response = curl_exec($curl);
    
            $err = curl_error($curl);
            curl_close($curl);

            $latest_conv_fee=0;
            $latest_conv_fee_gst=0;
            $payment_response = json_decode($payment_response, true);

            //calculate convenience charges and gst for convenience charges
            if(isset($payment_response['payment_amount'])){
                $latest_payment_amount = $payment_response['payment_amount'];
                $latest_order_amount = $payment_response['order_amount'];

                $difference=$latest_payment_amount-$latest_order_amount;
                if($difference > 0){
                    if($latest_order_amount <=2000){
                        $latest_conv_fee=round($difference,2);
                    }else{
                        $latest_conv_fee=round($difference/1.18,2);
                        $latest_conv_fee_gst=round($difference-$latest_conv_fee,2);
                    }
                }
            }
    
            //update transaction entry
            $invoiceData1 = array( 
                "orderId" => $orderId,
                "transactionDetails" => $txMsg,
                "transactionMode" => $paymentMode,
                "transactionStatus" => $txStatus,
                "orderStatus" => $status,
                "transactionDate" => $txTime,
                "transactionId" => $referenceId,
                "order_token" => $order_token,
                "order_expiry_time" => $order_expiry_time,
                "response" => serialize($data_response),
                "paymentResponse" => serialize($payment_response),
                "cf_order_id" => $cf_order_id,
                "convenienceCharges"=>$latest_conv_fee,
                "convenienceGstCharges"=>$latest_conv_fee_gst
            );
    
            $data = $orderId.$orderAmount.$referenceId.$txStatus.$paymentMode.$txMsg.$txTime;
    
            $hash_hmac = hash_hmac('sha256', $data, $secretkey, true) ;
            $computedSignature = base64_encode($hash_hmac);
    
            $insertData = $this->PaymentGatewayModel->updateCid('transaction',$invoiceData1, $cId);
            $invoicesdata = $this->PaymentGatewayModel->getDatabycId('transaction',$cId);
            $invoicesId = $this->PaymentGatewayModel->getLastInvoice('invoices');
            $lastInvoice = $invoicesId[0]['invoiceNumber'];
            
            $distributorData = $this->PaymentGatewayModel->load('distributors_details',$invoicesdata[0]['distributorId']);
    
            $transactionStatus1 = $invoicesdata[0]['transactionStatus'];
            $orderStatus1 = $invoicesdata[0]['orderStatus'];
            if ($transactionStatus1 == "SUCCESS" || $orderStatus1 == "PAID") {
                $invoice_date = date("Y-m-d H:i:s");
                $currentYear = date('Y');  // current year
                $currentMonth = date('m'); // current month
                
                $getAllInvoice=0;
                if($currentMonth>4){
                    $year = $currentYear+1;    //23
                    $yearLastDigit= substr( $year, -2);
                    $detail = $this->PaymentGatewayModel->getLastOneInvoice('invoices', $yearLastDigit);

                    if(!empty($detail)){
                        $getAllInvoice=$detail[0]['id'];
                    }
                }else{
                    $year = $currentYear;      //22
                    $yearLastDigit= substr( $year, -2);
                    $detail = $this->PaymentGatewayModel->getLastOneInvoice('invoices', $yearLastDigit);
                    if(!empty($detail)){
                        $getAllInvoice=$detail[0]['id'];
                    }
                }  

                //invoice number generation
                if($lastInvoice == null || $lastInvoice == ''){
                    $invoice_id = "SIA".$yearLastDigit."00001";
                }else{
                    $lastPlus = $getAllInvoice + 1;
                    $invoice_id1 = $yearLastDigit."00000";
                    $invoice_id2 = $invoice_id1 + $lastPlus;
                    $invoice_id = "SIA".$invoice_id2;
                }  
    
                //insert invoice entry 
                $invoiceDataNew = array( 
                    "cid" => $cId,
                    "cf_order_id" => $cf_order_id,
                    "trans_id" => $invoicesdata[0]['id'],
                    "orderId" => $invoicesdata[0]['orderId'],
                    "invoiceNumber" => $invoice_id,
                    "invoiceDate" => $invoice_date,
                    "distributorId" => $invoicesdata[0]['distributorId'],
                    "distributorCode" => $invoicesdata[0]['distributorCode'],
                    "distributorName" => $invoicesdata[0]['distributorName'],
                    "partnerId" => $invoicesdata[0]['partnerId'],
                    "contact" => $invoicesdata[0]['contact'],
                    "email" => $invoicesdata[0]['email'],
                    "gstNumber" => $invoicesdata[0]['gstNumber'],
                    "panNumber" => $invoicesdata[0]['panNumber'],
                    "gstStateCode" => $invoicesdata[0]['gstStateCode'],
                    "gstState" => $invoicesdata[0]['gstState'],
                    "address" => $invoicesdata[0]['address'],
                    "address2" => $distributorData[0]['address2'],
                    "city" => $distributorData[0]['city'],
                    "state" => $distributorData[0]['state'],
                    "pincode" => $distributorData[0]['pincode'],
                    "rechargeDate" => $invoicesdata[0]['rechargeDate'],
                    "rechargeFromDate" => $invoicesdata[0]['rechargeFromDate'],
                    "rechargeToDate" => $invoicesdata[0]['rechargeToDate'],
                    "packageId" => $invoicesdata[0]['packageId'],
                    "packageName" => $invoicesdata[0]['packageName'],
                    "duration" => $invoicesdata[0]['duration'],
                    "packageAmount" => $invoicesdata[0]['packageAmount'],
                    "referralDiscount" => $invoicesdata[0]['referralDiscount'],
                    "introductoryDiscount" => $invoicesdata[0]['introductoryDiscount'],
                    "sacCode" => $invoicesdata[0]['sacCode'],
                    "convenienceFeeSacCode"=>$invoicesdata[0]['convenienceFeeSacCode'],
                    "cgstPercent" => $invoicesdata[0]['cgstPercent'],
                    "sgstPercent" => $invoicesdata[0]['sgstPercent'],
                    "igstPercent" => $invoicesdata[0]['igstPercent'],
                    "cgstAmount" => $invoicesdata[0]['cgstAmount'],
                    "sgstAmount" => $invoicesdata[0]['sgstAmount'],
                    "igstAmount" => $invoicesdata[0]['igstAmount'],
                    "taxableAmount" => $invoicesdata[0]['taxableAmount'],
                    "taxAmount" => $invoicesdata[0]['taxAmount'],
                    "netAmount" => $invoicesdata[0]['netAmount'],
                    "transactionId" => $invoicesdata[0]['transactionId'],
                    "transactionDetails" => $invoicesdata[0]['transactionDetails'],
                    "transactionMode" => $invoicesdata[0]['transactionMode'],
                    "transactionStatus" => $invoicesdata[0]['transactionStatus'],
                    "orderStatus" => $invoicesdata[0]['orderStatus'],
                    "transactionDate" => $invoicesdata[0]['transactionDate'],
                    "convenienceCharges" => $invoicesdata[0]['convenienceCharges'],
                    "convenienceGstCharges" => $invoicesdata[0]['convenienceGstCharges']
                ); 
    
                $insert_id=$this->PaymentGatewayModel->insert('invoices',$invoiceDataNew);

                //update distributor validity date
                $distributorId = ($this->session->userdata['paymentSession']['id']);
                $distData = array( 
                    "validTill" => $invoicesdata[0]['rechargeToDate'],
                    "package" => $invoicesdata[0]['packageId'],
                    "packageName" => $invoicesdata[0]['packageName'],
                );
                $this->PaymentGatewayModel->update('distributors_details',$distData, $distributorId);

                $this->insertInvoiceOfficeDetails($insert_id);//insert office details for invoice
                $this->downloadPDF($cId);//generate Invoice PDF
                $message = "Payment successful";
                echo "<script type='text/javascript'>alert('$message');</script>";
                redirect('paymentGateways/CashfreePaymentGatewayController/manageAccountDetails','refresh');
            }else if ($transactionStatus1 != "SUCCESS" || $orderStatus1 != "PAID") {
                $this->PaymentGatewayModel->updateCid('transaction',$invoiceData1, $cId);
                $message = "Payment Failed";
                if ($message == TRUE) {
                    $studentName="priyanka";
                    $courseName="Smart Distributor"; 
                    $email="patilkb123@gmail.com";
                    
                    $subject="KIAS Order On Hold";
                    $rechargeDate = date('d - M - Y',strtotime($invoicesdata[0]['rechargeDate']));
               
                    $msg = '<html>
                            <head>
                            <title>' . $subject . '</title>
                            </head>
                            <body>
                                <br>
                                <b>Dear Sir/Mam,</b><br/><br/>
                                Thank you for using KIAS. We have <b>not received</b> the payment against order ID '.$orderId.' generated on '.$rechargeDate.'. <br/><br/>
                                
                                Your order is on hold. Request you to please verify the status and retry the payment by logging in the KIAS app.<br/><br/>
                                
                                In case your account is already debited, the amount will be credited back to original payment mode in 2-3 business days.                    
                                      
                                You may retry the payment through this link <a href="'.$paymentLinkForPayment.'">Pay Here!</a>. The link is valid for 24 hours.          
                                <br/>
                                <br/>
                                <b>Note:</b><br/>
                                This is a system-generated e-mail. Please do not reply to this email. <br/>
                                In case of any discrepancy, please contact SIA Inc immediately.
    
                            </body>
                            </html>';
    
                    $fromEmail="donotreply@siainc.in";
                    $headers = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $headers .= 'From: <' . $fromEmail . '>' . "\r\n";
                
                   // mail($email, $subject, $msg, $headers);  
                    echo "<script type='text/javascript'>alert('$message');</script>";
                    redirect('paymentGateways/CashfreePaymentGatewayController/manageAccountDetails','refresh');
                }
            }
        }else{
            $message="Payment failed";
            echo "<script type='text/javascript'>alert('$message');</script>";
            redirect('paymentGateways/CashfreePaymentGatewayController/manageAccountDetails','refresh');
        }
    }

    public function checkNow(){
        $cId = $_GET['cid'];
        $orderAmount = $_GET['amt'];

        $this->session->set_userdata('cid', $cId);
        $mode = $this->mode; //<-------- Change to TEST for test server, PROD for production
        $userid = ($this->session->userdata['paymentSession']['id']);
        $customerdata=$this->PaymentGatewayModel->getUserById('distributors_details',$userid);
        
        $date   = new DateTime(); //this returns the current date time
        $result = $date->format('Y-m-d');
        $krr    = explode('-', $result);
        $result = implode("", $krr);
            
        $secretKey = $this->secretKey;
        $appId=$this->appId;
        $orderId='Order'.$result.rand(0,500);

        $customerCode=$customerdata[0]['code'];
        $customerName=$customerdata[0]['name'];
        $customerEmail=$customerdata[0]['email'];
        $customerPhone=$customerdata[0]['mobile'];

        $orderCurrency='INR';
        // $orderNote='checkout';
        $returnUrl=site_url("paymentGateways/PackageController/payment_status1/".$cId);
        $notifyUrl=site_url("paymentGateways/PackageController/payment_status1/".$cId );
        
         $orderNote='KIAS validity extension till  for '.$customerName;

        $postData = array( 
            "appId" => $appId, 
            "orderId" => $orderId, 
            "orderAmount" => $orderAmount, 
            "orderCurrency" => $orderCurrency, 
            "orderNote" => $orderNote, 
            "customerName" => $customerName, 
            "customerPhone" => $customerPhone, 
            "customerEmail" => $customerEmail,
            "returnUrl" => $returnUrl, 
            "notifyUrl" => $notifyUrl,
        );

        ksort($postData);
        $signatureData = "";
        foreach ($postData as $key => $value){
            $signatureData .= $key.$value;
        }

        $signature = hash_hmac('sha256', $signatureData, $secretKey,true);
        $signature = base64_encode($signature);
        if ($mode == "PROD") {
            $url = "https://www.cashfree.com/checkout/post/submit";
        } else {
            $url = "https://test.cashfree.com/billpay/checkout/post/submit";
        }
        $this->load->view('paymentGateway/payment-checkout',['postData'=>$postData,'signature'=>$signature,'url'=>$url]);
    }

    public function payment_status1(){
        $cId = $this->uri->segment(4, 0); 
        
        if(isset($_POST["txMsg"])){
            $invoicesdata=$this->PaymentGatewayModel->getDatabycId('transaction',$cId);
            $lastId1=$invoicesdata[0]['id'];
    
            $secretkey = $this->secretKey;
            $orderId = $_POST["orderId"];
            $orderAmount = $_POST["orderAmount"];
            $referenceId = $_POST["referenceId"];
            $txStatus = $_POST["txStatus"];
            $paymentMode = $_POST["paymentMode"];
            $txMsg = $_POST["txMsg"];
            $txTime = $_POST["txTime"];
            $signature = $_POST["signature"];
    
            $curl = curl_init();
            curl_setopt_array($curl, array(
                // CURLOPT_URL => "https://sandbox.cashfree.com/pg/orders/".$orderId,  //test
                CURLOPT_URL => "https://api.cashfree.com/pg/orders/".$orderId,      //live
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                // CURLOPT_POSTFIELDS => $jsonData,
                CURLOPT_HTTPHEADER => array("x-client-id: ".$this->appId,"x-api-version: 2022-01-01","x-client-secret: ".$this->secretKey,"content-type: application/JSON"),
            ));
            $response = curl_exec($curl);
    
            $err = curl_error($curl);
            curl_close($curl);
    
            $data_response = json_decode($response, true);
            
            //generate order expiry time for 24 hrs
            $today=date('Y-m-d H:i:s');
    		$orderDate = date('Y-m-d H:i:s',strtotime('+1 days', strtotime($today)));
    		$todayDateTime = new DateTime($orderDate);
    		$todayDateTime=$todayDateTime->format(DateTime::ATOM);
    
            $status = $data_response['order_status'];
            $order_token = $data_response['order_token'];
            $paymentLinkForPayment=$data_response['payment_link'];
            $order_expiry_time = $todayDateTime;
            $cf_order_id = $data_response['cf_order_id'];
    
            $invoicesId = $this->PaymentGatewayModel->getLastInvoice('invoices');
            $lastInvoice = $invoicesId[0]['invoiceNumber'];
    
            $invoice_date = date("Y-m-d H:i:s");
            $currentYear = date('Y');  // current year
            $currentMonth = date('m'); // current month
            
            $getAllInvoice=0;
            if($currentMonth>4){
                $year = $currentYear+1;    //23
                $yearLastDigit= substr( $year, -2);
                $detail = $this->PaymentGatewayModel->getLastOneInvoice('invoices', $yearLastDigit);

                if(!empty($detail)){
                    $getAllInvoice=$detail[0]['id'];
                }
            }else{
                $year = $currentYear;      //22
                $yearLastDigit= substr( $year, -2);
                $detail = $this->PaymentGatewayModel->getLastOneInvoice('invoices', $yearLastDigit);
                if(!empty($detail)){
                    $getAllInvoice=$detail[0]['id'];
                }
            }  

            //invoice number generation
            if($lastInvoice == null || $lastInvoice == ''){
                $invoice_id = "SIA".$yearLastDigit."00001";
            }else{
                $lastPlus = $getAllInvoice + 1;
                $invoice_id1 = $yearLastDigit."00000";
                $invoice_id2 = $invoice_id1 + $lastPlus;
                $invoice_id = "SIA".$invoice_id2;
            }  
    
            $curl = curl_init();
            curl_setopt_array($curl, array(
                // CURLOPT_URL => "https://sandbox.cashfree.com/pg/orders/".$orderId."/payments/".$referenceId,     //test
                CURLOPT_URL => "https://api.cashfree.com/pg/orders/".$orderId."/payments/".$referenceId,     //live
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                // CURLOPT_POSTFIELDS => $jsonData,
                CURLOPT_HTTPHEADER => array("x-client-id: ".$this->appId,"x-api-version: 2022-01-01","x-client-secret: ".$this->secretKey,"content-type: application/JSON"),
            ));
            $payment_response = curl_exec($curl);
    
            $err = curl_error($curl);
            curl_close($curl);

            //calculate convenience fee and gst for convenience fee
            $latest_conv_fee=0;
            $latest_conv_fee_gst=0;
            $payment_response = json_decode($payment_response, true);
            if(isset($payment_response['payment_amount'])){
                $latest_payment_amount = $payment_response['payment_amount'];
                $latest_order_amount = $payment_response['order_amount'];

                $difference=$latest_payment_amount-$latest_order_amount;
                if($difference > 0){
                    if($latest_order_amount <=2000){
                        $latest_conv_fee=round($difference,2);
                    }else{
                        $latest_conv_fee=round($difference/1.18,2);
                        $latest_conv_fee_gst=round($difference-$latest_conv_fee,2);
                    }
                }
            }
         
            $invoiceData1 = array( 
                "orderId" => $orderId,
                "transactionDetails" => $txMsg,
                "transactionMode" => $paymentMode,
                "transactionStatus" => $txStatus,
                "orderStatus" => $status,
                "transactionDate" => $txTime,
                "transactionId" => $referenceId,
                "order_token" => $order_token,
                "order_expiry_time" => $order_expiry_time,
                "response" => serialize($data_response),
                "paymentResponse" => serialize($payment_response),
                "convenienceCharges"=>$latest_conv_fee,
                "convenienceGstCharges"=>$latest_conv_fee_gst
            );

            $data = $orderId.$orderAmount.$referenceId.$txStatus.$paymentMode.$txMsg.$txTime;
      
            $hash_hmac = hash_hmac('sha256', $data, $secretkey, true) ;
            $computedSignature = base64_encode($hash_hmac);
        
            $insertData = $this->PaymentGatewayModel->update('transaction',$invoiceData1, $lastId1);
            $invoicesdata = $this->PaymentGatewayModel->getDatabycId('transaction',$cId);
            $transactionStatus1 = $invoicesdata[0]['transactionStatus'];
            $orderStatus1 = $invoicesdata[0]['orderStatus'];
            
            $distributorData = $this->PaymentGatewayModel->load('distributors_details',$invoicesdata[0]['distributorId']);
         
            if ($transactionStatus1 == "SUCCESS" || $orderStatus1 == "PAID") {
                //insert Invoice entry in db
                $invoiceDataNew = array( 
                    "cid" => $cId,
                    "cf_order_id" => $cf_order_id,
                    "trans_id" => $invoicesdata[0]['id'],
                    "orderId" => $invoicesdata[0]['orderId'],
                    "invoiceNumber" => $invoice_id,
                    "invoiceDate" => $invoice_date,
                    "distributorId" => $invoicesdata[0]['distributorId'],
                    "distributorCode" => $invoicesdata[0]['distributorCode'],
                    "distributorName" => $invoicesdata[0]['distributorName'],
                    "partnerId" => $invoicesdata[0]['partnerId'],
                    "contact" => $invoicesdata[0]['contact'],
                    "email" => $invoicesdata[0]['email'],
                    "gstNumber" => $invoicesdata[0]['gstNumber'],
                    "panNumber" => $invoicesdata[0]['panNumber'],
                    "gstStateCode" => $invoicesdata[0]['gstStateCode'],
                    "gstState" => $invoicesdata[0]['gstState'],
                    "address" => $invoicesdata[0]['address'],
                    "address2" => $distributorData[0]['address2'],
                    "city" => $distributorData[0]['city'],
                    "state" => $distributorData[0]['state'],
                    "pincode" => $distributorData[0]['pincode'],
                    "rechargeDate" => $invoicesdata[0]['rechargeDate'],
                    "rechargeFromDate" => $invoicesdata[0]['rechargeFromDate'],
                    "rechargeToDate" => $invoicesdata[0]['rechargeToDate'],
                    "packageId" => $invoicesdata[0]['packageId'],
                    "packageName" => $invoicesdata[0]['packageName'],
                    "duration" => $invoicesdata[0]['duration'],
                    "packageAmount" => $invoicesdata[0]['packageAmount'],
                    "referralDiscount" => $invoicesdata[0]['referralDiscount'],
                    "introductoryDiscount" => $invoicesdata[0]['introductoryDiscount'],
                    "sacCode" => $invoicesdata[0]['sacCode'],
                    "convenienceFeeSacCode"=>$invoicesdata[0]['convenienceFeeSacCode'],
                    "cgstPercent" => $invoicesdata[0]['cgstPercent'],
                    "sgstPercent" => $invoicesdata[0]['sgstPercent'],
                    "igstPercent" => $invoicesdata[0]['igstPercent'],
                    "cgstAmount" => $invoicesdata[0]['cgstAmount'],
                    "sgstAmount" => $invoicesdata[0]['sgstAmount'],
                    "igstAmount" => $invoicesdata[0]['igstAmount'],
                    "taxableAmount" => $invoicesdata[0]['taxableAmount'],
                    "taxAmount" => $invoicesdata[0]['taxAmount'],
                    "netAmount" => $invoicesdata[0]['netAmount'],
                    "transactionId" => $invoicesdata[0]['transactionId'],
                    "transactionDetails" => $invoicesdata[0]['transactionDetails'],
                    "transactionMode" => $invoicesdata[0]['transactionMode'],
                    "transactionStatus" => $invoicesdata[0]['transactionStatus'],
                    "orderStatus" => $invoicesdata[0]['orderStatus'],
                    "transactionDate" => $invoicesdata[0]['transactionDate'],
                    "convenienceCharges" => $invoicesdata[0]['convenienceCharges'],
                    "convenienceGstCharges" => $invoicesdata[0]['convenienceGstCharges']
                );
    
                $insert_id=$this->PaymentGatewayModel->insert('invoices',$invoiceDataNew);

                //update distributor validity date
                $distributorId = ($this->session->userdata['paymentSession']['id']);
                $distData = array( 
                    "validTill" => $invoicesdata[0]['rechargeToDate'],
                    "package" => $invoicesdata[0]['packageId'],
                    "packageName" => $invoicesdata[0]['packageName'],
                );
                $this->PaymentGatewayModel->update('distributors_details',$distData, $distributorId);
                // print_r($distData);

                //insert office details for invoice 
                $this->insertInvoiceOfficeDetails($insert_id);
                $this->downloadPDF($cId);//generate invoice PDF

                $message = "Payment successful";
                echo "<script type='text/javascript'>alert('$message');</script>";
                redirect('paymentGateway/CashfreePaymentGatewayController/manageAccountDetails','refresh'); 
            
            }else if ($transactionStatus1 != "SUCCESS" || $orderStatus1 != "PAID") {
                // echo 'payment status '.$transactionStatus1.' order sttaus '.$orderStatus1;exit;
                $this->PaymentGatewayModel->update('transaction',$invoiceData1, $lastId1); 
                $message = "Payment Failed";
                if ($message == TRUE) {
                    $courseName="Smart Distributor"; 
                    $email="patilkb123@gmail.com";
                    $subject="KIAS Order On Hold";
                    $rechargeDate = date('d - M - Y',strtotime($invoicesdata[0]['rechargeDate']));
    
                    $msg = '<html>
                            <head>
                            <title>' . $subject . '</title>
                            </head>
                            <body>
                                <br>
                                <b>Dear Sir/Mam,</b><br/><br/>
                                Thank you for using KIAS. We have <b>not received</b> the payment against order ID '.$orderId.' generated on '.$rechargeDate.'. <br/><br/>
                                
                                Your order is on hold. Request you to please verify the status and retry the payment by logging in the KIAS app.<br/><br/>
                                
                                In case your account is already debited, the amount will be credited back to original payment mode in 2-3 business days.  
                                
                                You may retry the payment through this link <a href="'.$paymentLinkForPayment.'">Pay Here!</a>. The link is valid for 24 hours.   
                                                
                                <br/>
                                <br/>
                                <b>Note:</b><br/>
                                This is a system-generated e-mail. Please do not reply to this email. <br/>
                                In case of any discrepancy, please contact SIA Inc immediately.
    
                            </body>
                            </html>';
                        
                    $fromEmail="donotreply@siainc.in";
                    $headers = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $headers .= 'From: <' . $fromEmail . '>' . "\r\n";
            
                   // mail($email, $subject, $msg, $headers);
                    echo "<script type='text/javascript'>alert('$message');</script>";
                    redirect('paymentGateways/CashfreePaymentGatewayController/manageAccountDetails','refresh');
                }
            
            }
        }else{
            $message="Payment failed";
            echo "<script type='text/javascript'>alert('$message');</script>";
            redirect('paymentGateways/CashfreePaymentGatewayController/manageAccountDetails','refresh');
        }
        
    }


    public function paymentStatus(){
        $orderId = $this->uri->segment(4, 0); 
        
        // echo $orderId;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            // CURLOPT_URL => "http://test.cashfree.com/api/v2/orders/".$orderId."/status",     //test
            CURLOPT_URL => "https://api.cashfree.com/api/v2/orders/".$orderId."/status",     //live
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array("x-client-id: ".$this->appId,"x-api-version: 2022-01-01","x-client-secret: ".$this->secretKey,"content-type: application/JSON"),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response, true);

        // print_r($data);exit;
        if(isset($data['orderStatus'])){
            $status = $data['orderStatus'];
            $txStatus="";
            if(isset($data['txStatus'])){
                $txStatus = $data['txStatus'];
            }

            $invoiceUpdate1 = array( 
                "transactionStatus" => $txStatus,
                "orderStatus" => $status,
                "orderResponse" => serialize($data),
            );

            $invoiceUpdate = array( 
                "transactionStatus" => $txStatus,
                "orderStatus" => $status,
            );

            $invoiceTest =  $this->PaymentGatewayModel->updateOrderStatus('transaction',$invoiceUpdate1, $orderId);
            if ($txStatus == "SUCCESS" && $status == "PAID") {
                $invoicesdata = $this->PaymentGatewayModel->getDatabyOrder('transaction',$orderId);
                
                // $CHECK=(unserialize($invoicesdata[0]['paymentResponse']));
                // $CHECK =(json_decode($CHECK,true));
                // echo $CHECK['bank_reference'];
                // exit;

                $invoicesId = $this->PaymentGatewayModel->getLastInvoice('invoices');
                $lastInvoice = $invoicesId[0]['invoiceNumber'];
                    
                $invoice_date = date("Y-m-d H:i:s");
                $currentYear = date('Y');  // current year
                $currentMonth = date('m'); // current month
                
                $getAllInvoice=0;
                if($currentMonth>4){
                    $year = $currentYear+1;    //23
                    $yearLastDigit= substr( $year, -2);
                    $detail = $this->PaymentGatewayModel->getLastOneInvoice('invoices', $yearLastDigit);

                    if(!empty($detail)){
                        $getAllInvoice=$detail[0]['id'];
                    }
                }else{
                    $year = $currentYear;      //22
                    $yearLastDigit= substr( $year, -2);
                    $detail = $this->PaymentGatewayModel->getLastOneInvoice('invoices', $yearLastDigit);
                    if(!empty($detail)){
                        $getAllInvoice=$detail[0]['id'];
                    }
                }  

                //invoice number generation
                if($lastInvoice == null || $lastInvoice == ''){
                    $invoice_id = "SIA".$yearLastDigit."00001";
                }else{
                    $lastPlus = $getAllInvoice + 1;
                    $invoice_id1 = $yearLastDigit."00000";
                    $invoice_id2 = $invoice_id1 + $lastPlus;
                    $invoice_id = "SIA".$invoice_id2;
                }  
                
                $distributorData = $this->PaymentGatewayModel->load('distributors_details',$invoicesdata[0]['distributorId']);
                    
                $invoiceDataNew = array( 
                    "cid" => $invoicesdata[0]['cid'],
                    "cf_order_id" => $invoicesdata[0]['cf_order_id'],
                    "trans_id" => $invoicesdata[0]['id'],
                    "orderId" => $invoicesdata[0]['orderId'],
                    "invoiceNumber" => $invoice_id,
                    "invoiceDate" => $invoice_date,
                    "distributorId" => $invoicesdata[0]['distributorId'],
                    "distributorCode" => $invoicesdata[0]['distributorCode'],
                    "distributorName" => $invoicesdata[0]['distributorName'],
                    "partnerId" => $invoicesdata[0]['partnerId'],
                    "contact" => $invoicesdata[0]['contact'],
                    "email" => $invoicesdata[0]['email'],
                    "gstNumber" => $invoicesdata[0]['gstNumber'],
                    "panNumber" => $invoicesdata[0]['panNumber'],
                    "gstStateCode" => $invoicesdata[0]['gstStateCode'],
                    "gstState" => $invoicesdata[0]['gstState'],
                    "address" => $invoicesdata[0]['address'],
                    "address2" => $distributorData[0]['address2'],
                    "city" => $distributorData[0]['city'],
                    "state" => $distributorData[0]['state'],
                    "pincode" => $distributorData[0]['pincode'],
                    "rechargeDate" => $invoicesdata[0]['rechargeDate'],
                    "rechargeFromDate" => $invoicesdata[0]['rechargeFromDate'],
                    "rechargeToDate" => $invoicesdata[0]['rechargeToDate'],
                    "packageId" => $invoicesdata[0]['packageId'],
                    "packageName" => $invoicesdata[0]['packageName'],
                    "duration" => $invoicesdata[0]['duration'],
                    "packageAmount" => $invoicesdata[0]['packageAmount'],
                    "referralDiscount" => $invoicesdata[0]['referralDiscount'],
                    "introductoryDiscount" => $invoicesdata[0]['introductoryDiscount'],
                    "sacCode" => $invoicesdata[0]['sacCode'],
                    "convenienceFeeSacCode"=>$invoicesdata[0]['convenienceFeeSacCode'],
                    "cgstPercent" => $invoicesdata[0]['cgstPercent'],
                    "sgstPercent" => $invoicesdata[0]['sgstPercent'],
                    "igstPercent" => $invoicesdata[0]['igstPercent'],
                    "cgstAmount" => $invoicesdata[0]['cgstAmount'],
                    "sgstAmount" => $invoicesdata[0]['sgstAmount'],
                    "igstAmount" => $invoicesdata[0]['igstAmount'],
                    "taxableAmount" => $invoicesdata[0]['taxableAmount'],
                    "taxAmount" => $invoicesdata[0]['taxAmount'],
                    "netAmount" => $invoicesdata[0]['netAmount'],
                    "transactionId" => $invoicesdata[0]['transactionId'],
                    "transactionDetails" => $invoicesdata[0]['transactionDetails'],
                    "transactionMode" => $invoicesdata[0]['transactionMode'],
                    "transactionStatus" => $invoicesdata[0]['transactionStatus'],
                    "orderStatus" => $invoicesdata[0]['orderStatus'],
                    "transactionDate" => $invoicesdata[0]['transactionDate'],
                    "convenienceCharges" => $invoicesdata[0]['convenienceCharges'],
                    "convenienceGstCharges" => $invoicesdata[0]['convenienceGstCharges']
                ); 
                $insert_id=$this->PaymentGatewayModel->insert('invoices',$invoiceDataNew);

                //update distributor validity date
                $distributorId = ($this->session->userdata['paymentSession']['id']);
                $distData = array( 
                    "validTill" => $invoicesdata[0]['rechargeToDate'],
                    "package" => $invoicesdata[0]['packageId'],
                    "packageName" => $invoicesdata[0]['packageName'],
                );
                $this->PaymentGatewayModel->update('distributors_details',$distData, $distributorId);

                
                $this->insertInvoiceOfficeDetails($insert_id);//insert office details for invoice
                $cId = $invoicesdata[0]['cid'];
                $this->downloadPDF($cId);
            }

            $message = "Status Updated";
            // echo "<script type='text/javascript'>alert('$message');</script>";
            
            redirect('paymentGateways/CashfreePaymentGatewayController/manageAccountDetails','refresh');
        }else{
            if(isset($data['message'])){
                 $message = $data['message'];
                echo "<script type='text/javascript'>alert('$message');</script>";
                redirect('paymentGateways/CashfreePaymentGatewayController/manageAccountDetails','refresh');
            }else{
                redirect('paymentGateways/CashfreePaymentGatewayController/manageAccountDetails','refresh');
            }
           
        }
        
    }

public function downloadPDF($lastId){ 
    $officeDetails=$this->PaymentGatewayModel->getTableData('office_details');
    
    $lastInsertedId = $lastId;
    $filename = time().rand().".pdf";
    $userid = $this->session->userdata['paymentSession']['id'];
    $invoicesdata=$this->PaymentGatewayModel->getDatabycId('invoices',$lastInsertedId);
// print_r($invoicesdata);exit;
    // print_r($invoicesdata[0]['convenienceCharges'];);
    
    $convenienceCharges=0;
    $convHtml="";
    $convChargeHtml="";
    $convenienceGstCharges=0;

    $convenienceFeeSacCode=0;
    $convCodeHtml="";

    if(!empty($invoicesdata)){
        if($invoicesdata[0]['convenienceCharges'] !=""){
            $convenienceCharges=$invoicesdata[0]['convenienceCharges'];
            if($convenienceCharges >0){
                $convHtml="Convenience Charges";
                $convChargeHtml=number_format($invoicesdata[0]['convenienceCharges'],2);
                $convCodeHtml=$invoicesdata[0]['convenienceFeeSacCode'];
            }
        }

        if($invoicesdata[0]['convenienceGstCharges'] !=""){
            $convenienceGstCharges=$invoicesdata[0]['convenienceGstCharges'];
        }

        if($invoicesdata[0]['convenienceFeeSacCode'] !=""){
            $convenienceFeeSacCode=$invoicesdata[0]['convenienceFeeSacCode'];

        }
    }

    $durationDate=date('d-M-Y',strtotime($invoicesdata[0]['rechargeFromDate'])).' To '.date('d-M-Y',strtotime($invoicesdata[0]['rechargeToDate']));

    $isIGST='';

    $officeGstStateCode=trim($officeDetails[0]['gstStateCode']);
    $distributorGstStateCode=trim($invoicesdata[0]['gstStateCode']);

    if($officeGstStateCode != $distributorGstStateCode){
        $isIGST='false';
    }

    $invoicesId=$invoicesdata[0]['id']; 
    $inDate = date('d-F-Y',strtotime($invoicesdata[0]['invoiceDate']));

        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A6', true, 'UTF-8', false);
        $pdf->SetTitle('Invoice');
        $pdf->SetHeaderMargin(30);
        $pdf->SetTopMargin(4);
        $pdf->setFooterMargin(25);
        $pdf->SetAutoPageBreak(false);
        $pdf->SetAuthor('Author');
        $pdf->SetDisplayMode('real', 'default');
        $pdf->setPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->AddPage();

        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Times','B',12);
        $pdf-> Cell(0,10,' TAX INVOICE',0,1,'C',1);

        $pdf->SetFont('Times','B',12);
        $pdf->SetTextColor(0,0,0);
        $pdf-> Cell(0,10,$officeDetails[0]['name'],0,10,'L');
        
        $pdf->SetFont('Times','',10);
        $pdf->Cell(0,5,$officeDetails[0]['address'],0,10,'L');
        $pdf->Cell(0,5,$officeDetails[0]['address2'].', '.$officeDetails[0]['city'].' '.$officeDetails[0]['pincode'],0,10,'L');
        $pdf->Cell(0,5,'GSTIN/UIN: '.$officeDetails[0]['gstNumber'],0,10,'L'); 
        $pdf->Cell(0,5,'PAN: '.$officeDetails[0]['panNumber'],0,10,'L');  //cell 1.left,2.top,and 3.bottom,4.right
        $pdf->Cell(0,5,'State Name: '.$officeDetails[0]['state'].', Code: '.$officeDetails[0]['gstStateCode'],0,10,'L');  
        $pdf->Cell(0,5,'E-Mail: '.$officeDetails[0]['email'],0,10,'L');

        $pdf->image('images/logo.jpeg', 165, 18, 35, '', 'JPEG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        $pdf->ln(40);
        $pdf->SetFont('Times','B',12);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(0,6,'Bill To',0,10,'L');
        $pdf->Cell(0,6,$invoicesdata[0]['distributorName'],0,10,'L');
        $pdf->SetFont('Times','',10);
        $pdf->Cell(0,5,$invoicesdata[0]['address'].', ',0,10,'L');
        $pdf->Cell(0,5,$invoicesdata[0]['address2'].', ',0,10,'L');
        $pdf->Cell(0,5,$invoicesdata[0]['city'].', '.$invoicesdata[0]['state'].' - '.$invoicesdata[0]['pincode'],0,10,'L');
        
        $pdf->Cell(0,5,'GSTIN/UIN: ' .$invoicesdata[0]['gstNumber'],0,10,'L'); 
        $pdf->Cell(0,5,'PAN/IT No: ' .$invoicesdata[0]['panNumber'],0,10,'L'); 
        $pdf->Cell(0,5,'Place of Supply: '.$invoicesdata[0]['state'],0,10,'L'); 
        $pdf->ln(-32);

        $pdf->SetFont('Times','',10);

        $pdf->Cell(135 ,5,'',0,0);
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(27 ,5,'Invoice Date:',0,0);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(34 ,5,$inDate,0,1);

        $pdf->Cell(135 ,5,'',0,0);
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(27 ,5,'Invoice No:',0,0);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(34 ,5,$invoicesdata[0]['invoiceNumber'],0,1);

        $pdf->Cell(135 ,5,'',0,0);
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(35 ,5,'Distributor Code:',0,0);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(34 ,5,$invoicesdata[0]['distributorCode'],0,1);/*end of line*/

        $pdf->Cell(135 ,5,'',0,0);
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(27 ,5,'Payment Id:',0,0);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(34 ,5,$invoicesdata[0]['transactionId'],0,1);

        $pdf->Cell(135 ,5,'',0,0);
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(32 ,5,'Payment Status:',0,0);
        $pdf->SetFont('Times','B',10);
        $pdf->SetTextColor(0, 176, 80);
        $pdf->Cell(34 ,5,$invoicesdata[0]['transactionStatus'],0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->ln(20);

        $html='';

        if($isIGST==''){
            $html = '
                <table border="1px" style="padding:5px;text-align:center;font-size:11px">
                <tr style="background-color:#ffffff;">
                <th align="left" style="width:180px; color:black;"><b>Description</b></th>
                <th align="left" style="width:100px; color:black;"><b>HSN/SAC</b></th>
                <th align="left" style="width:100px; color:black;"><b>Duration</b></th>
                <th align="right" style="width:80px; color:black;"><b>Rate</b></th>
                <th align="right" style="width:80px; color:black;"><b>Amount</b></th>
                </tr>

                <tr>
                <td align="left"><br /><br />'.$invoicesdata[0]['packageName'].'<br /><span style="font-size:9px">('.$durationDate.')</span><br /><br />
                CGST<br />SGST <br /><br /><br /> <br /><br /><br /></td>
                <td align="left"><br /><br />'.$invoicesdata[0]['sacCode'].'<br /><br /><br /></td>
                <td align="left"><br /><br />'.$invoicesdata[0]['duration'].' Month</td>
                <td align="right"><br /><br />'.number_format($invoicesdata[0]['packageAmount'], 2).'<br /><br /><br />'.$invoicesdata[0]['cgstPercent'].'% <br / > '.$invoicesdata[0]['sgstPercent'].'%<br /> <br /><br /><br /><br /><br /></td>
                <td align="right"><br /><br />'.number_format($invoicesdata[0]['packageAmount'], 2).'<br /><br /><br />'.number_format(($invoicesdata[0]['cgstAmount']), 2).'<br /> '.number_format(($invoicesdata[0]['sgstAmount']), 2).'<br /> <br /><br /><br /><br /><br /></td>

                </tr>

                <tr style="background-color:#ffffff;">
                <td align="Left" style="color:black;"><b>Total</b></td>
                <td></td>
                <td></td>
                <td></td>
                <td align="right" style="color:black;"><b>Rs. '.number_format(($invoicesdata[0]['netAmount']), 2).'</b></td>
                </tr>
            </table>';
        }else{
            $html = '
                <table border="1px" style="padding:5px;text-align:center;font-size:10px">
                <tr style="background-color:#ffffff;">
                <th align="left" style="width:180px; color:black;"><b>Description</b></th>
                <th align="left" style="width:100px; color:black;"><b>HSN/SAC</b></th>
                <th align="left" style="width:100px; color:black;"><b>Duration</b></th>
                <th align="right" style="width:80px; color:black;"><b>Rate</b></th>
                <th align="right" style="width:80px; color:black;"><b>Amount</b></th>
                </tr>

                <tr>
                <td align="left"><br /><br />'.$invoicesdata[0]['packageName'].'<br /><small><b>('.$durationDate.')</b></small><br /><br />
                IGST<br /><br /><br /> <br /><br /><br /></td>
                <td align="left"><br /><br />'.$invoicesdata[0]['sacCode'].'<br /><br /><br /></td>
                <td align="left"><br /><br />'.$invoicesdata[0]['duration'].' Month</td>
                <td align="right"><br /><br />'.number_format($invoicesdata[0]['packageAmount'], 2).'<br /><br /><br /> '.$invoicesdata[0]['igstPercent'].'%<br /><br /><br /><br /><br /><br /></td>
                <td align="right"><br /><br />'.number_format($invoicesdata[0]['packageAmount'], 2).'<br /><br /><br />'.number_format(($invoicesdata[0]['igstAmount']), 2).'<br /><br /><br /><br /><br /><br /></td>

                </tr>

                <tr style="background-color:#ffffff;">
                <td align="Left" style="color:black;"><b>Total</b></td>
                <td></td>
                <td></td>
                <td></td>
                <td align="right" style="color:black;"><b>Rs. '.number_format(($invoicesdata[0]['netAmount']), 2).'</b></td>
                </tr>
            </table>';
        }




    $pdf->writeHTML($html, true, false, true, false, '');

    $html = '
        <table style="padding-top:-15px;text-align:center;font-size:10px">
        <tr><td colspan="3" align="left">Amount Chargeable (in words)
            <br /><b>INR '.$this->amount_word = $this->getIndianCurrency(($invoicesdata[0]['netAmount'])).' Only </b> </td></tr>

    </table>';

    $pdf->writeHTML($html, true, false, true, false, '');

    $html = '
        <table style="padding:-3px;text-align:center;font-size:10px">
        <tr>
        <td>
        </td>
        </tr>
    </table>';

    $pdf->writeHTML($html, true, false, true, false, '');

    $html = '
    <table border="1px" style="padding-top: 5px; border-collapse: collapse;text-align:center;font-size:10px" >
    <tr align="center" >
            <th style="padding:2.5px;" rowspan="2"><b>HSN/SAC</b></th>
            <th style="padding:2.5px;" rowspan="2"><b>Taxable Value</b></th>
            <th style="padding:2.5px;" colspan="2"><b>CGST</b></th>
            <th style="padding:2.5px;" colspan="2"><b>SGST</b></th>
            <th style="padding:2.5px;" colspan="2"><b>IGST</b></th>
            <th style="padding:2.5px;" rowspan="2"><b>Total Tax Amount</b></th>
        </tr>
        <tr>
            <th>Rate</th>
            <th>Amount</th>
            <th>Rate</th>
            <th>Amount</th>
            <th>Rate</th>
            <th>Amount</th>

        </tr>

        <tr>
        <td><b>'.$invoicesdata[0]['sacCode'].'</b></td>
        <td><b>'.number_format($invoicesdata[0]['packageAmount'], 2).'</b></td>
        <td><b>'.$invoicesdata[0]['cgstPercent'].' %</b></td>
        <td><b>'.number_format($invoicesdata[0]['cgstAmount'], 2).'</b></td>
        <td><b>'.$invoicesdata[0]['sgstPercent'].' %</b></td>
        <td><b>'.number_format($invoicesdata[0]['sgstAmount'], 2).'</b></td>
        <td><b>'.$invoicesdata[0]['igstPercent'].' %</b></td>
        <td><b>'.number_format($invoicesdata[0]['igstAmount'], 2).'</b></td>
        <td><b>'.number_format(($invoicesdata[0]['taxAmount']), 2).'</b></td>
        </tr>
    </table>';
    $pdf->writeHTML($html, true, false, true, false, '');

    $html = '
        <table style="padding:-15px;text-align:center;font-size:10px">
        <tr>
        <td colspan="9" align="left">&nbsp; &nbsp; &nbsp;Tax Amount (in words) :  <b> INR '.$this->amount_word = $this->getIndianCurrency($invoicesdata[0]['taxAmount']).' Only </b>
        </td>
        </tr>
    </table>';

    $pdf->writeHTML($html, true, false, true, false, '');

    $html = '
        <table style="padding:-3px;text-align:center;font-size:10px">
        <tr>
        <td>
        </td>
        </tr>
    </table>';

    $pdf->writeHTML($html, true, false, true, false, '');

    $html = '
        <table style="padding:3px;text-align:center;font-size:10px;">
        <tr><td colspan="5" align="left"><br /><b>Declaration</b>
        <br />We declare that this invoice shows the actual price of the services described and that all particulars are true and correct. </td>

        <td colspan="5" align="right"><b>for SIA Inc</b>
            <br />
            <img src="images/sign.jpeg" alt="" width="100px" height="20px"/>
            <br />Authorised Signatory</td>
        </tr>

    </table>';

    $pdf->writeHTML($html, true, false, true, false, '');
    $footertext = '
        <table style="padding:3px;text-align:center;font-size:10px">
        <tr>
        <td colspan="5" align="center">
        SUBJECT TO DELHI JURISDICTION <br /> This is a computer generated invoice</td>
        </tr>
        </table>';
    $pdf->writeHTML($footertext, true, false, true, false, '');
  
    $filename= "KIAS Invoice No. ".$invoicesdata[0]['invoiceNumber']." dated ".date('d M Y',strtotime($invoicesdata[0]['invoiceDate'])).".pdf";

    $distributorCode=$invoicesdata[0]['distributorCode'];
    
    $distributorInfo=$this->PaymentGatewayModel->getUserByCode('distributors_details',$distributorCode);
    $userId=$distributorInfo[0]['id'];
    $username=$distributorInfo[0]['email'];
    $password=$distributorInfo[0]['password'];
    $baseUrl=$distributorInfo[0]['adminBaseUrl'];
    
    // Get absolute path for server live environment
    $path = getcwd();
    $path = substr($path, 0, strpos($path, "public_html"));
    $root = $path . "public_html/project_code/superadmin/";
    $path = $root.'assets/uploads/pdf/'.$filename;


    //for localhost server
    // $path = FCPATH.'assets/uploads/pdf/'.$filename;
             
        
    //Close and output PDF document
    $data = array('filepath' => $filename);
    $this->PaymentGatewayModel->updateCid('invoices',$data, $lastInsertedId);

    $data = array('filepath' => $filename);
    $this->PaymentGatewayModel->updateCid('transaction',$data, $lastInsertedId);

    ob_start(); 
    $pdf->Output($path, 'F');
    ob_end_clean();
    
    // Recipient 
    $to = 'patilkb123@gmail.com'; 
    
    // Sender   
    $from = 'donotreply@siainc.in'; 
    $fromName = 'SIA Inc';  
 
    // Email subject 
    $subject = "KIAS Invoice for ".$invoicesdata[0]['distributorName'];
    $invoiceDate = date('d - M - Y' ,strtotime($invoicesdata[0]['invoiceDate']));
    $transactionDate = date('d - M - Y H:i:s',strtotime($invoicesdata[0]['transactionDate']));
    $rechargeDate = date('d - M - Y',strtotime($invoicesdata[0]['rechargeToDate']));
                        
    // Attachment file 
    $file = $path; 
 
    // Email body content 
    $htmlContent = '<html>
                <head>
                  <title>' . $subject . '</title>
                </head>
                <body>
                    <br>
                    <b>Dear Sir/Mam,</b><br/><br/>
                     Thank you for using KIAS. We have received a payment of Rs '.$invoicesdata[0]['netAmount'].' via '.$invoicesdata[0]['transactionMode'].' on '.$transactionDate.'. Please find attached GST Invoice for your reference.<br>
                     <br>                    
                     Here are some details for your order:
					 <br>
                    <table>
                    <tr>
                    <td colspan="6">Order ID: </td>
                    <td colspan="6">'.$invoicesdata[0]['orderId'].'</td>
                    </tr>
                    
                    <tr>
                    <td colspan="6">Transaction ID: </td>
                    <td colspan="6">'.$invoicesdata[0]['transactionId'].'</td>
                    </tr>
                    
                    <tr>
                    <td colspan="6">Invoice Number: </td>
                    <td colspan="6">'.$invoicesdata[0]['invoiceNumber'].'</td>
                    </tr>
                     
                    <tr>
                    <td colspan="6">Invoice Date: </td>
                    <td colspan="6">'.$invoiceDate.'</td>
                    </tr> 
                    
                    <tr>
                    <td colspan="6">Package: </td>
                    <td colspan="6">'.$invoicesdata[0]['packageName'].'</td>
                    </tr>
                    
                    <tr>
                    <td colspan="4">Duration: </td>
                    <td colspan="4">'.$invoicesdata[0]['duration'].' Month</td>
                    </tr>
                    
                    <tr>
                    <td colspan="6">Validity: </td>
                    <td colspan="6">'.$rechargeDate.'</td>
                    </tr>  
                    
                    <tr>
                    <td colspan="6">Amount Paid: </td>
                    <td colspan="6">Rs. '.$invoicesdata[0]['netAmount'].'</td>
                    </tr>
                    
                    </table>                   
                    
                    <br/>
                     <br/>
                    <b>Note:</b><br>
                    This is a system-generated e-mail. Please do not reply to this email. <br/>
                    In case of any discrepancy, please contact SIA Inc immediately.

                </body>
                </html>';
 
    // Header for sender info 
    $headers = "From: $fromName"." <".$from.">"; 
    
    // Boundary  
    $semi_rand = md5(time());  
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
    
    // Headers for attachment  
    $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
 
    // Multipart boundary  
    $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" . 
    "Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n";  
    
    // Preparing attachment 
    if(!empty($file) > 0){ 
        if(is_file($file)){ 
            $message .= "--{$mime_boundary}\n"; 
            $fp =    @fopen($file,"rb"); 
            $data =  @fread($fp,filesize($file)); 
    
            @fclose($fp); 
            $data = chunk_split(base64_encode($data)); 
            $message .= "Content-Type: application/octet-stream; name=\"".basename($file)."\"\n" .  
            "Content-Description: ".basename($file)."\n" . 
            "Content-Disposition: attachment;\n" . " filename=\"".basename($file)."\"; size=".filesize($file).";\n" .  
            "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n"; 
        } 
    } 
    $message .= "--{$mime_boundary}--"; 
    $returnpath = "-f" . $from; 
    
    // Send email 
    $mail = @mail($to, $subject, $message, $headers, $returnpath);  
} 

public function downloadPDFOld($lastId){ 
    $officeDetails=$this->PaymentGatewayModel->getTableData('office_details');
    
    $lastInsertedId = $lastId;
    $filename = time().rand().".pdf";
    $userid = $this->session->userdata['paymentSession']['id'];
    $invoicesdata=$this->PaymentGatewayModel->getDatabycId('invoices',$lastInsertedId);
// print_r($invoicesdata);exit;
    // print_r($invoicesdata[0]['convenienceCharges'];);
    
    $convenienceCharges=0;
    $convHtml="";
    $convChargeHtml="";
    $convenienceGstCharges=0;

    $convenienceFeeSacCode=0;
    $convCodeHtml="";

    if(!empty($invoicesdata)){
        if($invoicesdata[0]['convenienceCharges'] !=""){
            $convenienceCharges=$invoicesdata[0]['convenienceCharges'];
            if($convenienceCharges >0){
                $convHtml="Convenience Charges";
                $convChargeHtml=number_format($invoicesdata[0]['convenienceCharges'],2);
                $convCodeHtml=$invoicesdata[0]['convenienceFeeSacCode'];
            }
        }

        if($invoicesdata[0]['convenienceGstCharges'] !=""){
            $convenienceGstCharges=$invoicesdata[0]['convenienceGstCharges'];
        }

        if($invoicesdata[0]['convenienceFeeSacCode'] !=""){
            $convenienceFeeSacCode=$invoicesdata[0]['convenienceFeeSacCode'];

        }
    }

    $durationDate=date('d-M-Y',strtotime($invoicesdata[0]['rechargeFromDate'])).' To '.date('d-M-Y',strtotime($invoicesdata[0]['rechargeToDate']));

    $isIGST='';

    $officeGstStateCode=trim($officeDetails[0]['gstStateCode']);
    $distributorGstStateCode=trim($invoicesdata[0]['gstStateCode']);

    if($officeGstStateCode != $distributorGstStateCode){
        $isIGST='false';
    }

    $invoicesId=$invoicesdata[0]['id']; 
    $inDate = date('d-F-Y',strtotime($invoicesdata[0]['invoiceDate']));

        $this->load->library('Pdf');
        // $pageLayout = array(105, 148);
        $pdf = new Pdf('P', 'mm', 'A6', true, 'UTF-8', false);
        $pdf->SetTitle('Invoice');
        $pdf->SetHeaderMargin(30);
        $pdf->SetTopMargin(4);
        $pdf->setFooterMargin(25);
        $pdf->SetAutoPageBreak(false);
        $pdf->SetAuthor('Author');
        $pdf->SetDisplayMode('real', 'default');
        $pdf->setPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->AddPage();

        // $pdf->SetFillColor(0, 0, 95);
        // $pdf->SetTextColor(255, 165, 0);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Times','B',12);
        $pdf-> Cell(0,10,' TAX INVOICE',0,1,'C',1);

       // $this->Image('./../../../images/bdGovt-Logo.gif', 5, 8, 25, '', 'GIF', '', 'T', false, 300, '', false, false, 0, false, false, false);
       // $this->Image('./../../../images/SEQAEP-Logo.gif', 275, 8, 15, '', 'GIF', '', 'T', false, 300, '', false, false, 0, false, false, false);

        $pdf->SetFont('Times','B',12);
        $pdf->SetTextColor(0,0,0);
        $pdf-> Cell(0,10,$officeDetails[0]['name'],0,10,'L');
        
        $pdf->SetFont('Times','',10);
        $pdf->Cell(0,5,$officeDetails[0]['address'],0,10,'L');
        $pdf->Cell(0,5,$officeDetails[0]['address2'].', '.$officeDetails[0]['city'].' '.$officeDetails[0]['pincode'],0,10,'L');
        $pdf->Cell(0,5,'GSTIN/UIN: '.$officeDetails[0]['gstNumber'],0,10,'L'); 
        $pdf->Cell(0,5,'PAN: '.$officeDetails[0]['panNumber'],0,10,'L');  //cell 1.left,2.top,and 3.bottom,4.right
        $pdf->Cell(0,5,'State Name: '.$officeDetails[0]['state'].', Code: '.$officeDetails[0]['gstStateCode'],0,10,'L');  
        $pdf->Cell(0,5,'E-Mail: '.$officeDetails[0]['email'],0,10,'L');

        $pdf->image('images/logo.jpeg', 165, 18, 35, '', 'JPEG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        $pdf->ln(40);
        $pdf->SetFont('Times','B',12);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(0,6,'Bill To',0,10,'L');
        $pdf->Cell(0,6,$invoicesdata[0]['distributorName'],0,10,'L');
        $pdf->SetFont('Times','',10);
        $pdf->Cell(0,5,$invoicesdata[0]['address'].', ',0,10,'L');
        $pdf->Cell(0,5,$invoicesdata[0]['address2'].', ',0,10,'L');
        $pdf->Cell(0,5,$invoicesdata[0]['city'].', '.$invoicesdata[0]['state'].' - '.$invoicesdata[0]['pincode'],0,10,'L');
        
       // $pdf->Cell(0,5,'Civil Lines, Delhi 110054',0,10,'L');
        $pdf->Cell(0,5,'GSTIN/UIN: ' .$invoicesdata[0]['gstNumber'],0,10,'L'); 
        $pdf->Cell(0,5,'PAN/IT No: ' .$invoicesdata[0]['panNumber'],0,10,'L'); 
       // $pdf->Cell(0,5,'State Name : Delhi, Code : 07',0,10,'L'); 
        $pdf->Cell(0,5,'Place of Supply: '.$invoicesdata[0]['state'],0,10,'L'); 

        $pdf->ln(-32);

        // $pdf->SetFont('Times','B',10);
        // $pdf->Cell(0,5,'Invoice Number:',0,150,'R');
        // $pdf->Cell(0,5,'Invoice Date:',0,150,'R');
        // $pdf->Cell(0,5,'Status:',0,150,'R'); 
        // $pdf->Cell(0,5,'Payment Id:',0,150,'R');  //cell 1.left,2.top,and 3.bottom,4.right

        $pdf->SetFont('Times','',10);

        $pdf->Cell(135 ,5,'',0,0);
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(27 ,5,'Invoice Date:',0,0);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(34 ,5,$inDate,0,1);

        $pdf->Cell(135 ,5,'',0,0);
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(27 ,5,'Invoice No:',0,0);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(34 ,5,$invoicesdata[0]['invoiceNumber'],0,1);

        $pdf->Cell(135 ,5,'',0,0);
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(35 ,5,'Distributor Code:',0,0);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(34 ,5,$invoicesdata[0]['distributorCode'],0,1);/*end of line*/

        $pdf->Cell(135 ,5,'',0,0);
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(27 ,5,'Payment Id:',0,0);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(34 ,5,$invoicesdata[0]['transactionId'],0,1);

        $pdf->Cell(135 ,5,'',0,0);
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(32 ,5,'Payment Status:',0,0);
        $pdf->SetFont('Times','B',10);
        $pdf->SetTextColor(0, 176, 80);
       // $pdf->Cell(34 ,5,'Paid',0,1);
        $pdf->Cell(34 ,5,$invoicesdata[0]['transactionStatus'],0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->ln(20);

        $html='';

        if($isIGST==''){
            $html = '
                <table border="1px" style="padding:5px;text-align:center;font-size:11px">
                <tr style="background-color:#ffffff;">
                <th align="left" style="width:180px; color:black;"><b>Description</b></th>
                <th align="left" style="width:100px; color:black;"><b>HSN/SAC</b></th>
                <th align="left" style="width:100px; color:black;"><b>Duration</b></th>
                <th align="right" style="width:80px; color:black;"><b>Rate</b></th>
                <th align="right" style="width:80px; color:black;"><b>Amount</b></th>
                </tr>

                <tr>
                <td align="left"><br /><br />'.$invoicesdata[0]['packageName'].'<br /><span style="font-size:11px"><b>('.$durationDate.')</b></span><br /><br />
                '.$convHtml.' <br /><br />CGST<br />SGST <br /><br /><br /> <br /><br /><br /></td>
                <td align="left"><br /><br />'.$invoicesdata[0]['sacCode'].'<br /><br /><br />'.$convCodeHtml.'</td>
                <td align="left"><br /><br />'.$invoicesdata[0]['duration'].' Month</td>
                <td align="right"><br /><br />'.number_format($invoicesdata[0]['packageAmount'], 2).'<br /><br /><br />'.$convChargeHtml.'<br /><br />'.$invoicesdata[0]['cgstPercent'].'% <br / > '.$invoicesdata[0]['sgstPercent'].'%<br /> <br /><br /><br /><br /><br /></td>
                <td align="right"><br /><br />'.number_format($invoicesdata[0]['packageAmount'], 2).'<br /><br /><br />'.$convChargeHtml.' <br /><br />'.number_format(($invoicesdata[0]['cgstAmount']+($convenienceGstCharges/2)), 2).'<br /> '.number_format(($invoicesdata[0]['sgstAmount']+($convenienceGstCharges/2)), 2).'<br /> <br /><br /><br /><br /><br /></td>

                </tr>

                <tr style="background-color:#ffffff;">
                <td align="Left" style="color:black;"><b>Total</b></td>
                <td></td>
                <td></td>
                <td></td>
                <td align="right" style="color:black;"><b>Rs. '.number_format(($invoicesdata[0]['netAmount']+$invoicesdata[0]['convenienceCharges']+$invoicesdata[0]['convenienceGstCharges']), 2).'</b></td>
                </tr>
            </table>';
        }else{
            $html = '
                <table border="1px" style="padding:5px;text-align:center;font-size:10px">
                <tr style="background-color:#ffffff;">
                <th align="left" style="width:180px; color:black;"><b>Description</b></th>
                <th align="left" style="width:100px; color:black;"><b>HSN/SAC</b></th>
                <th align="left" style="width:100px; color:black;"><b>Duration</b></th>
                <th align="right" style="width:80px; color:black;"><b>Rate</b></th>
                <th align="right" style="width:80px; color:black;"><b>Amount</b></th>
                </tr>

                <tr>
                <td align="left"><br /><br />'.$invoicesdata[0]['packageName'].'<br /><small><b>('.$durationDate.')</b></small><br /><br />
                '.$convHtml.' <br /><br />IGST<br /><br /><br /> <br /><br /><br /></td>
                <td align="left"><br /><br />'.$invoicesdata[0]['sacCode'].'<br /><br /><br />'.$convCodeHtml.'</td>
                <td align="left"><br /><br />'.$invoicesdata[0]['duration'].' Month</td>
                <td align="right"><br /><br />'.number_format($invoicesdata[0]['packageAmount'], 2).'<br /><br /><br />'.$convChargeHtml.' <br /><br />'.$invoicesdata[0]['igstPercent'].'%<br /><br /><br /><br /><br /><br /></td>
                <td align="right"><br /><br />'.number_format($invoicesdata[0]['packageAmount'], 2).'<br /><br /><br />'.$convChargeHtml.' <br /><br />'.number_format(($invoicesdata[0]['igstAmount']+$convenienceGstCharges), 2).'<br /><br /><br /><br /><br /><br /></td>

                </tr>

                <tr style="background-color:#ffffff;">
                <td align="Left" style="color:black;"><b>Total</b></td>
                <td></td>
                <td></td>
                <td></td>
                <td align="right" style="color:black;"><b>Rs. '.number_format(($invoicesdata[0]['netAmount']+$invoicesdata[0]['convenienceCharges']+$invoicesdata[0]['convenienceGstCharges']), 2).'</b></td>
                </tr>
            </table>';
        }




    $pdf->writeHTML($html, true, false, true, false, '');

    $html = '
        <table style="padding-top:-15px;text-align:center;font-size:10px">
        <tr><td colspan="3" align="left">Amount Chargeable (in words)
            <br /><b>INR '.$this->amount_word = $this->getIndianCurrency(($invoicesdata[0]['netAmount']+$invoicesdata[0]['convenienceCharges']+$invoicesdata[0]['convenienceGstCharges'])).' Only </b> </td></tr>

    </table>';

    $pdf->writeHTML($html, true, false, true, false, '');

    $html = '
        <table style="padding:-3px;text-align:center;font-size:10px">
        <tr>
        <td>
        </td>
        </tr>
    </table>';

    $pdf->writeHTML($html, true, false, true, false, '');

    $html = '
    <table border="1px" style="padding-top: 5px; border-collapse: collapse;text-align:center;font-size:10px" >
    <tr align="center" >
            <th style="padding:2.5px;" rowspan="2"><b>HSN/SAC</b></th>

            <th style="padding:2.5px;" rowspan="2"><b>CF SAC</b></th>
            <th style="padding:2.5px;" rowspan="2"><b>CF GST</b></th>
            <th style="padding:2.5px;" rowspan="2"><b>Taxable Value</b></th>
            <th style="padding:2.5px;" colspan="2"><b>CGST</b></th>
            <th style="padding:2.5px;" colspan="2"><b>SGST</b></th>
            <th style="padding:2.5px;" colspan="2"><b>IGST</b></th>
            <th style="padding:2.5px;" rowspan="2"><b>Total Tax Amount</b></th>
        </tr>
        <tr>
            <th>Rate</th>
            <th>Amount</th>
            <th>Rate</th>
            <th>Amount</th>
            <th>Rate</th>
            <th>Amount</th>

        </tr>

        <tr>
        <td><b>'.$invoicesdata[0]['sacCode'].'</b></td>
        <td><b>'.$invoicesdata[0]['convenienceFeeSacCode'].'</b></td>
        <td><b>'.number_format($invoicesdata[0]['convenienceGstCharges'], 2).'</b></td>
        <td><b>'.number_format($invoicesdata[0]['packageAmount'], 2).'</b></td>
        <td><b>'.$invoicesdata[0]['cgstPercent'].' %</b></td>
        <td><b>'.number_format($invoicesdata[0]['cgstAmount'], 2).'</b></td>
        <td><b>'.$invoicesdata[0]['sgstPercent'].' %</b></td>
        <td><b>'.number_format($invoicesdata[0]['sgstAmount'], 2).'</b></td>
        <td><b>'.$invoicesdata[0]['igstPercent'].' %</b></td>
        <td><b>'.number_format($invoicesdata[0]['igstAmount'], 2).'</b></td>
        <td><b>'.number_format(($invoicesdata[0]['taxAmount']+$invoicesdata[0]['convenienceGstCharges']), 2).'</b></td>
        </tr>
    </table>';
    $pdf->writeHTML($html, true, false, true, false, '');

    $html = '
        <table style="padding:-15px;text-align:center;font-size:10px">
        <tr>
        <td colspan="9" align="left">&nbsp; &nbsp; &nbsp;Tax Amount (in words) :  <b> INR '.$this->amount_word = $this->getIndianCurrency($invoicesdata[0]['taxAmount']+$invoicesdata[0]['convenienceGstCharges'] ).' Only </b>
        </td>
        </tr>
    </table>';

    $pdf->writeHTML($html, true, false, true, false, '');

    $html = '
        <table style="padding:-3px;text-align:center;font-size:10px">
        <tr>
        <td>
        </td>
        </tr>
    </table>';

    $pdf->writeHTML($html, true, false, true, false, '');

    $html = '
        <table style="padding:3px;text-align:center;font-size:10px;">
        <tr><td colspan="5" align="left"><br /><b>Declaration</b>
        <br />We declare that this invoice shows the actual price of the services described and that all particulars are true and correct. </td>

        <td colspan="5" align="right"><b>for SIA Inc</b>
            <br />
            <img src="images/sign.jpeg" alt="" width="150px" height="30px"/>
            <br />Authorised Signatory</td>
        </tr>

    </table>';

    $pdf->writeHTML($html, true, false, true, false, '');
    $footertext = '
        <table style="padding:3px;text-align:center;font-size:10px">
        <tr>
        <td colspan="5" align="center">
        SUBJECT TO DELHI JURISDICTION <br /> This is a computer generated invoice</td>
        </tr>
        </table>';
    $pdf->writeHTML($footertext, true, false, true, false, '');
  
    $filename= "KIAS Invoice No. ".$invoicesdata[0]['invoiceNumber']." dated ".date('d M Y',strtotime($invoicesdata[0]['invoiceDate'])).".pdf";

    $distributorCode=$invoicesdata[0]['distributorCode'];
    
    $distributorInfo=$this->PaymentGatewayModel->getUserByCode('distributors_details',$distributorCode);
    $userId=$distributorInfo[0]['id'];
    $username=$distributorInfo[0]['email'];
    $password=$distributorInfo[0]['password'];
    $baseUrl=$distributorInfo[0]['adminBaseUrl'];
    
    // Get absolute path for server
    $path = getcwd();
    $path = substr($path, 0, strpos($path, "public_html"));
    $root = $path . "public_html/project_code/superadmin/";
    $path = $root.'assets/uploads/pdf/'.$filename;


    //for local server
    // $path = FCPATH.'assets/uploads/pdf/'.$filename;
             
    //  $pdf->Output(__DIR__."/../invoices/invoice_".date('d-M-Y').".pdf", 'F');
        
    //Close and output PDF document
    $data = array('filepath' => $filename);
    $this->PaymentGatewayModel->updateCid('invoices',$data, $lastInsertedId);

    $data = array('filepath' => $filename);
    $this->PaymentGatewayModel->updateCid('transaction',$data, $lastInsertedId);

    // echo $aa=$this->db->last_query(); exit();
    ob_start(); 
    //$pdf->Output($path, 'F');
    $pdf->Output($path, 'F');
    ob_end_clean();
    
    // Recipient 
    $to = 'patilkb123@gmail.com'; 
    
    // Sender   
    $from = 'donotreply@siainc.in'; 
    $fromName = 'SIA Inc';  
 
    // Email subject 
    $subject = "KIAS Invoice for ".$invoicesdata[0]['distributorName'];
    $invoiceDate = date('d - M - Y' ,strtotime($invoicesdata[0]['invoiceDate']));
    $transactionDate = date('d - M - Y H:i:s',strtotime($invoicesdata[0]['transactionDate']));
    $rechargeDate = date('d - M - Y',strtotime($invoicesdata[0]['rechargeToDate']));
                        
    // Attachment file 
    $file = $path; 
 
    // Email body content 
    $htmlContent = '<html>
                <head>
                  <title>' . $subject . '</title>
                </head>
                <body>
                    <br>
                    <b>Dear Sir/Mam,</b><br/><br/>
                     Thank you for using KIAS. We have received a payment of Rs '.$invoicesdata[0]['netAmount'].' via '.$invoicesdata[0]['transactionMode'].' on '.$transactionDate.'. Please find attached GST Invoice for your reference.<br>
                     <br>                    
                     Here are some details for your order:
					 <br>
                    <table>
                    <tr>
                    <td colspan="6">Order ID: </td>
                    <td colspan="6">'.$invoicesdata[0]['orderId'].'</td>
                    </tr>
                    
                    <tr>
                    <td colspan="6">Transaction ID: </td>
                    <td colspan="6">'.$invoicesdata[0]['transactionId'].'</td>
                    </tr>
                    
                    <tr>
                    <td colspan="6">Invoice Number: </td>
                    <td colspan="6">'.$invoicesdata[0]['invoiceNumber'].'</td>
                    </tr>
                     
                    <tr>
                    <td colspan="6">Invoice Date: </td>
                    <td colspan="6">'.$invoiceDate.'</td>
                    </tr> 
                    
                    <tr>
                    <td colspan="6">Package: </td>
                    <td colspan="6">'.$invoicesdata[0]['packageName'].'</td>
                    </tr>
                    
                    <tr>
                    <td colspan="4">Duration: </td>
                    <td colspan="4">'.$invoicesdata[0]['duration'].' Month</td>
                    </tr>
                    
                    <tr>
                    <td colspan="6">Validity: </td>
                    <td colspan="6">'.$rechargeDate.'</td>
                    </tr>  
                    
                    <tr>
                    <td colspan="6">Amount Paid: </td>
                    <td colspan="6">Rs. '.$invoicesdata[0]['netAmount'].'</td>
                    </tr>
                    
                    </table>                   
                    
                    <br/>
                     <br/>
                    <b>Note:</b><br>
                    This is a system-generated e-mail. Please do not reply to this email. <br/>
                    In case of any discrepancy, please contact SIA Inc immediately.

                </body>
                </html>';
 
    // Header for sender info 
    $headers = "From: $fromName"." <".$from.">"; 
    
    // Boundary  
    $semi_rand = md5(time());  
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
    
    // Headers for attachment  
    $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
 
    // Multipart boundary  
    $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" . 
    "Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n";  
    
    // Preparing attachment 
    if(!empty($file) > 0){ 
        if(is_file($file)){ 
            $message .= "--{$mime_boundary}\n"; 
            $fp =    @fopen($file,"rb"); 
            $data =  @fread($fp,filesize($file)); 
    
            @fclose($fp); 
            $data = chunk_split(base64_encode($data)); 
            $message .= "Content-Type: application/octet-stream; name=\"".basename($file)."\"\n" .  
            "Content-Description: ".basename($file)."\n" . 
            "Content-Disposition: attachment;\n" . " filename=\"".basename($file)."\"; size=".filesize($file).";\n" .  
            "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n"; 
        } 
    } 
    $message .= "--{$mime_boundary}--"; 
    $returnpath = "-f" . $from; 
    
    // Send email 
    $mail = @mail($to, $subject, $message, $headers, $returnpath);  
    
    // Email sending status 
    //echo $mail?"<h1>Email Sent Successfully!</h1>":"<h1>Email sending failed.</h1>"; 
} 

    public function getIndianCurrency(float $number) {
         $no = (int)floor($number);
        $point = (int)round(($number - $no) * 100);
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array('0' => '', '1' => 'One', '2' => 'Two',
         '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
         '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
         '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
         '13' => 'Thirteen', '14' => 'Fourteen',
         '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
         '18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
         '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
         '60' => 'Sixty', '70' => 'Seventy',
         '80' => 'Eighty', '90' => 'Ninety');
        $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        while ($i < $digits_1) {
          $divider = ($i == 2) ? 10 : 100;
          $number = floor($no % $divider);
          $no = floor($no / $divider);
          $i += ($divider == 10) ? 1 : 2;
     
     
          if ($number) {
             $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
             $hundred = ($counter == 1 && $str[0]) ? ' And ' : null;
             $str [] = ($number < 21) ? $words[$number] .
                 " " . $digits[$counter] . $plural . " " . $hundred
                 :
                 $words[floor($number / 10) * 10]
                 . " " . $words[$number % 10] . " "
                 . $digits[$counter] . $plural . " " . $hundred;
          } else $str[] = null;
       }
       $str = array_reverse($str);
       $result = implode('', $str);
     
     
       if ($point > 20) {
         $points = ($point) ?
           "" . $words[floor($point / 10) * 10] . " " . 
               $words[$point = $point % 10] : ''; 
       } else {
           $points = $words[$point];
       }
       if($points != ''){        
           return $result . "Rupees  And " . $points . " Paise";
       } else {
     
           return $result . "Rupees";
       }
    }

    public function convert_number_to_words($number){

        $hyphen = ' ';
        $conjunction = ' and ';
        $separator = ' ';
        $negative = 'negative ';
        $decimal = ' and Cents ';
        $dictionary = array(
            0 => 'Zero',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine',
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen',
            20 => 'Twenty',
            30 => 'Thirty',
            40 => 'Fourty',
            50 => 'Fifty',
            60 => 'Sixty',
            70 => 'Seventy',
            80 => 'Eighty',
            90 => 'Ninety',
            100 => 'Hundred',
            1000 => 'Thousand',
            1000000 => 'Million',
        );

        if (!is_numeric($number)) {
            return false;
        }

        if ($number < 0) {
            return $negative . $this->getIndianCurrency(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int)($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->getIndianCurrency($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int)($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->getIndianCurrency($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->getIndianCurrency($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string)$fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }
        return $string;
    } 

    public function package_Distributor(){
        $id=$_GET['id']; 
        $data['getDistributors']=$this->PaymentGatewayModel->getDistByIdPackage('distributors_details',$id);
        $this->load->view('package/distributorList',$data);
        //print_r($data['getDistributors']); exit();
    }

    public function uploadDistributorDetails(){
        $fileName=$_FILES['billFile4']['name'];
        $fileType=$_FILES['billFile4']['type'];
        $fileTempName=$_FILES['billFile4']['tmp_name'];

        //upload file
        $config['upload_path'] = 'assets/uploads/';                             
        $config['file_name'] = $fileName;
        $config['overwrite'] = true;
        $config['allowed_types'] = 'xls|xlsx|csv';

        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('file')){
            $this->upload->display_errors();
        }
        $media =  $fileName;
        $path = 'assets/uploads/'. $media;

        $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        if(isset($fileName) && in_array($fileType, $file_mimes)) {
            $arr_file = explode('.', $fileName); //get file
            $extension = end($arr_file); //get file extension

            // select spreadsheet reader depends on file extension
            if('csv' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else if ('xlsx'){
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            }

            $reader->setReadDataOnly(true);
            $objPHPExcel = $reader->load($fileTempName);//Get filename
            
            $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
            $highestRow = $worksheet->getHighestRow(); // e.g. 12
            $highestColumn = $worksheet->getHighestColumn(); // e.g M'

            $billNumber="";
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
            
            $cnt=0;
            $excelTotalAmt=0;
            
            for ($row = 2; $row <= $highestRow; ++$row) {
                $cnt++;
                $code = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                $name = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                $mobile= trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                $telephone= trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                $email = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                $password = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                $password1 = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                $address = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                $address2 = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                $city = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                $state = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                $country = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                $pincode = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                $gstNumber = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                $panNumber = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                $gstStateCode = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                $gstState = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                $baseUrl = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                $adminBaseUrl = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                $databaseName = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                $status = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                $createdAt = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                $updatedOn = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());
                $createdBy = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                $updatedBy = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                $partnerId = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                $package = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());
                $packageName = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
                $validTill = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());

                // $billDate =str_replace("/","-",$billDate);
                $createdAt = ($createdAt - 25569) * 86400;
                $createdAt=date('Y-m-d', $createdAt);//convert date from excel data

                $updatedOn = ($updatedOn - 25569) * 86400;
                $updatedOn=date('Y-m-d', $updatedOn);//convert date from excel data

                $validTill = ($validTill - 25569) * 86400;
                $validTill=date('Y-m-d', $validTill);//convert date from excel data

                // echo $createdAt.' '.$updatedOn.' '.$validTill;
                
                $chechCode=$this->PaymentGatewayModel->getUserByCode('distributors_details',$code);//insert remark data
                if(empty($chechCode)){
                    $distData=array(
                        'code'=>$code,
                        'name'=>$name,
                        'mobile'=>$mobile,
                        'telephone'=>$telephone,
                        'email'=>$email,
                        'password'=>$password,
                        'password1'=>$password1,
                        'address'=>$address,
                        'address2'=>$address2,
                        'city'=>$city,
                        'state'=>$state,
                        'country'=>$country,
                        'pincode'=>$pincode,
                        'gstNumber'=>$gstNumber,
                        'panNumber'=>$panNumber,
                        'gstStateCode'=>$gstStateCode,
                        'gstState'=>$gstState,
                        'baseUrl'=>$baseUrl,
                        'adminBaseUrl'=>$adminBaseUrl,
                        'databaseName'=>$databaseName,
                        'status'=>$status,
                        'createdAt'=>$createdAt,
                        'updatedOn'=>$updatedOn,
                        'createdBy'=>$createdBy,
                        'updatedBy'=>$updatedBy,
                        'partnerId'=>$partnerId,
                        'package'=>$package,
                        'packageName'=>$packageName,
                        'validTill'=>$validTill
                    );
                    $this->PaymentGatewayModel->insert('distributors_details',$distData);//insert remark data
                }
            }
        }
        // redirect('DeliverySlipController/Products');
    }

    public function uploadInvoiceDetails(){
        $fileName=$_FILES['billFile5']['name'];
        $fileType=$_FILES['billFile5']['type'];
        $fileTempName=$_FILES['billFile5']['tmp_name'];

        //upload file
        $config['upload_path'] = 'assets/uploads/';                             
        $config['file_name'] = $fileName;
        $config['overwrite'] = true;
        $config['allowed_types'] = 'xls|xlsx|csv';

        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('file')){
            $this->upload->display_errors();
        }
        $media =  $fileName;
        $path = 'assets/uploads/'. $media;

        $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        if(isset($fileName) && in_array($fileType, $file_mimes)) {
            $arr_file = explode('.', $fileName); //get file
            $extension = end($arr_file); //get file extension

            // select spreadsheet reader depends on file extension
            if('csv' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else if ('xlsx'){
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            }

            $reader->setReadDataOnly(true);
            $objPHPExcel = $reader->load($fileTempName);//Get filename
            
            $worksheet = $objPHPExcel->getSheet(0);//Get sheet 
            $highestRow = $worksheet->getHighestRow(); // e.g. 12
            $highestColumn = $worksheet->getHighestColumn(); // e.g M'

            $billNumber="";
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 7
            
            $cnt=0;
            $excelTotalAmt=0;
            
            for ($row = 2; $row <= $highestRow; ++$row) {
                $cnt++;

                $cid = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                $orderId = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                $invoiceNumber= trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                $invoiceDate= trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                $distributorId= trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                $distributorCode = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                $distributorName = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                $partnerId = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                $contact = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                $email = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                $gstNumber = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());

                $panNumber = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                $gstStateCode = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                $gstState = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                $address = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                $address2 = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                $city = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                $state = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                $pincode = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                $rechargeDate = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                $rechargeFromDate = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());

                $rechargeToDate = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                $packageId = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());
                $packageName = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                $duration = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                $packageAmount = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                $referralDiscount = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());
                $introductoryDiscount = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
                $sacCode = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                $cgstPercent = trim($worksheet->getCellByColumnAndRow(31, $row)->getValue());
                $cgstAmount = trim($worksheet->getCellByColumnAndRow(32, $row)->getValue());

                $sgstPercent = trim($worksheet->getCellByColumnAndRow(33, $row)->getValue());
                $sgstAmount = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());
                $igstPercent = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());
                $igstAmount = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());
                $taxableAmount = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());
                $taxAmount = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());
                $netAmount = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());
                $transactionId = trim($worksheet->getCellByColumnAndRow(40, $row)->getValue());
                $transactionDetails = trim($worksheet->getCellByColumnAndRow(41, $row)->getValue());
                $transactionMode = trim($worksheet->getCellByColumnAndRow(42, $row)->getValue());

                $transactionStatus = trim($worksheet->getCellByColumnAndRow(43, $row)->getValue());
                $transactionDate = trim($worksheet->getCellByColumnAndRow(44, $row)->getValue());
                $convenienceCharges = trim($worksheet->getCellByColumnAndRow(45, $row)->getValue());
                $filepath = trim($worksheet->getCellByColumnAndRow(46, $row)->getValue());
                $orderStatus = trim($worksheet->getCellByColumnAndRow(47, $row)->getValue());
                $cf_order_id = trim($worksheet->getCellByColumnAndRow(48, $row)->getValue());
                $trans_id = trim($worksheet->getCellByColumnAndRow(49, $row)->getValue());
                $invoiceStatus = trim($worksheet->getCellByColumnAndRow(50, $row)->getValue());
                // echo $transactionDate;exit;

                if($invoiceDate !=""){
                    $invoiceDate = ($invoiceDate - 25569) * 86400;
                    $invoiceDate=date('Y-m-d', $invoiceDate);//convert date from excel data
                }
                
                if($rechargeDate !=""){
                    $rechargeDate = ($rechargeDate - 25569) * 86400;
                    $rechargeDate=date('Y-m-d', $rechargeDate);//convert date from excel data
                }

                if($rechargeFromDate !=""){
                    $rechargeFromDate = ($rechargeFromDate - 25569) * 86400;
                    $rechargeFromDate=date('Y-m-d', $rechargeFromDate);//convert date from excel data
                }

                if($rechargeToDate !=""){
                    $rechargeToDate = ($rechargeToDate - 25569) * 86400;
                    $rechargeToDate=date('Y-m-d', $rechargeToDate);//convert date from excel data
                }

                if($transactionDate !=""){
                    $transactionDate = ($transactionDate - 25569) * 86400;
                    $transactionDate=date('Y-m-d', $transactionDate);//convert date from excel data
                }

                // echo $invoiceDate.' '.$rechargeDate.' '.$rechargeFromDate.' '.$rechargeToDate.' '.$transactionDate;exit;
               
                $udata=$this->PaymentGatewayModel->loadFileByOrderId('invoices',$orderId);
                if(empty($udata)){
                    $invoiceData=array(
                        'cid'=>$cid,
                        'orderId'=>$orderId,
                        'invoiceNumber'=>$invoiceNumber,
                        'invoiceDate'=>$invoiceDate,
                        'distributorId'=>$distributorId,
                        'distributorCode'=>$distributorCode,
                        'distributorName'=>$distributorName,
                        'partnerId'=>$partnerId,
                        'contact'=>$contact,
                        'email'=>$email,
                        'gstNumber'=>$gstNumber,
                        'panNumber'=>$panNumber,
                        'gstStateCode'=>$gstStateCode,
                        'gstState'=>$gstState,
                        'address'=>$address,
                        'address2'=>$address2,
                        'city'=>$city,
                        'state'=>$state,
                        'pincode'=>$pincode,
                        'rechargeDate'=>$rechargeDate,
                        'rechargeFromDate'=>$rechargeFromDate,
                        'rechargeToDate'=>$rechargeToDate,
                        'packageId'=>$packageId,
                        'packageName'=>$packageName,
                        'duration'=>$duration,
                        'packageAmount'=>$packageAmount,
                        'referralDiscount'=>$referralDiscount,
                        'introductoryDiscount'=>$introductoryDiscount,
                        'sacCode'=>$sacCode,
                        'cgstPercent'=>$cgstPercent,
                        'cgstAmount'=>$cgstAmount,
                        'sgstPercent'=>$sgstPercent,
                        'sgstAmount'=>$sgstAmount,
                        'igstPercent'=>$igstPercent,
                        'igstAmount'=>$igstAmount,
                        'taxableAmount'=>$taxableAmount,
                        'taxAmount'=>$taxAmount,
                        'netAmount'=>$netAmount,
                        'transactionId'=>$transactionId,
                        'transactionDetails'=>$transactionDetails,
                        'transactionMode'=>$transactionMode,
                        'transactionStatus'=>$transactionStatus,
                        'transactionDate'=>$transactionDate,
                        'convenienceCharges'=>$convenienceCharges,
                        'filepath'=>$filepath,
                        'orderStatus'=>$orderStatus,
                        'cf_order_id'=>$cf_order_id,
                        'trans_id'=>$trans_id,
                        'invoiceStatus'=>$invoiceStatus
                    );
                    $this->PaymentGatewayModel->insert('invoices',$invoiceData);//insert remark data
                }
            }
        }
        // redirect('DeliverySlipController/Products');
    }

    public function insertInvoiceOfficeDetails($invoiceId){
        $officeDetails=$this->PaymentGatewayModel->getTableData('office_details');

        $insertData=array(
            'invoiceId'=>$invoiceId,
            'name'=>$officeDetails[0]['name'],
            'address'=>$officeDetails[0]['address'],
            'address2'=>$officeDetails[0]['address2'],
            'city'=>$officeDetails[0]['city'],
            'state'=>$officeDetails[0]['state'],
            'pincode'=>$officeDetails[0]['pincode'],
            'gstState'=>$officeDetails[0]['gstState'],
            'gstNumber'=>$officeDetails[0]['gstNumber'],
            'gstStateCode'=>$officeDetails[0]['gstStateCode'],
            'panNumber'=>$officeDetails[0]['panNumber'],
            'email'=>$officeDetails[0]['email'],
            'mobile'=>$officeDetails[0]['mobile'],
            'gstPercent'=>$officeDetails[0]['gstPercent'],
            'sacCode'=>$officeDetails[0]['sacCode'],
            'convenienceFeeSacCode'=>$officeDetails[0]['convenienceFeeSacCode']
        );

        $this->PaymentGatewayModel->insert('invoice_office_details',$insertData);//insert remark data
    }
}

?>