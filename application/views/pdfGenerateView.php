<?php
//  $pdf = new Pdf('P', 'mm', 'A6', true, 'UTF-8', false);
    $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->AddPage();
    $pdf->SetTitle('Order Details');
    $pdf->SetHeaderMargin(30);
    $pdf->SetTopMargin(20);
    $pdf->setFooterMargin(20);
    $pdf->SetAutoPageBreak(true);
    $pdf->SetAuthor('Author');
    $pdf->SetDisplayMode('real', 'default');


    $details = "";
        $total=0;
        for($i=0;$i<count($getBillDetail);$i++) {

            $details = $details."<tr><td>".($i+1)."</td>
                <td>".$getBillDetail[$i]['name'].'-'.$getBillDetail[$i]['strength']."</td>
                <td>".$getBillDetail[$i]['sku']."</td>
                <td>".$getBillDetail[$i]['brand']."</td>
                <td>".$getBillDetail[$i]['batchNo']."</td>
                <td>".date('d/m/Y',strtotime($getBillDetail[$i]['expiry']))."</td>
                <td>".$getBillDetail[$i]['yourPrice']."</td>
                <td>".$getBillDetail[$i]['quantity']."</td>
                <td>".$getBillDetail[$i]['gst']."</td>
                <td>".$getBillDetail[$i]['ptrDiscount']."</td>
                <td>".$getBillDetail[$i]['subTotal']."</td></tr>";
        }

$html ='<h1 align="center">Order Details</h1>
<table>
    <br />
         <tr>
            <td align="left"><b>Sold By :</b><br>'."Dawabag E-Pharmacy".'<br>'."2009 14th Street N, Suite 3,".'<br>'."Arlington, Virginia 22201".'<br><b>GST No :</b>'."22AAAAA0000A1Z5".'</td>
            <td align="center"><b>Order id: : </b>&nbsp;&nbsp;&nbsp;'.$getBillDetail[0]['id'].'<br><b>Order Date:</b>&nbsp;&nbsp;&nbsp;'. date("d/m/Y", strtotime($getBillDetail[0]['odate'])).'<br><b>Invoice Date:</b>&nbsp;&nbsp;&nbsp;'.date("d/m/Y", strtotime($getBillDetail[0]['odate'])).'<br><b>DL No :</b>'."PH8735".'</td>
            <td align="right"><b>Bill To / Ship To (Patient): </b><br>'.$getBillDetail[0]['fname'].' '.$getBillDetail[0]['lname'].'<br>
                    '.$getBillDetail[0]['a1'].','.$getBillDetail[0]['a2'].','.$getBillDetail[0]['a3'].','.'<br>'. $getBillDetail[0]['acity'].','.$getBillDetail[0]['astate'].','.$getBillDetail[0]['acountry'].'-'.$getBillDetail[0]['apincode'].'</td>
         </tr>
         <br>
         <tr>
            <td align="left"><b>Coupon Title:</b>&nbsp;&nbsp;'.$getBillDetail[0]['ctitle'].'</td>
            <td align="center"><b>Coupon Offer:</b>&nbsp;&nbsp;'.$getBillDetail[0]['cdescription'].'</td>
         </tr>
         
        
    </table>
    <br><br>

        <h3 align="center">Medicine Details</h3>
            <table border="1px" rowspan="2" colspan="2" style="text-align:center;">
        <tr>
                        <th class="text-center">Sr. No.</th>
                        <th class="text-center">Medicine</th>
                        <th class="text-center">SKU</th>
                        <th class="text-center">Brand</th>
                        <th class="text-center">Batch</th>
                        <th class="text-center">Expiry Date</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Discount</th>
                        <th class="text-center">GST</th>
                        <th class="text-center">Sub Total</th>
        </tr>
        
            '.$details.'


    </table>

       <br /> 
    <table>
    <br /><br /><br />
         <tr>
            <td align="left"><b>Name of the Pharmacist:</b><br><b>PR Number:</b></td>
            <td align="center"><br><br><br><b>Pharmacist Signature:</b></td>
            <td align="right"><b>MRP Total:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$getBillDetail[0]['ototal'].'<br><b>Discount:</b>&nbsp;&nbsp;&nbsp;- '.$getBillDetail[0]['odiscount'].'<br><b>Sub-Total:</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$getBillDetail[0]['opayble'].'</td>
         </tr><br>
         <tr>
            <td align="left"><b>Doctor Name : </b><br>'.$getBillDetail[0]['doctor'].'<br><b>Contact No:</b><br>'. $getBillDetail[0]['dcontact'].'<br><b>Email:</b><br>'.$getBillDetail[0]['demail'].'</td>

            <td align="center"><b>Licence No : </b><br>'.$getBillDetail[0]['licence'].'<br><b>Reg No:</b><br>'.$getBillDetail[0]['reg'].'</td>
            
            <td align="right"><b>Hospital: </b><br>'.$getBillDetail[0]['dhospital'].'<br>'.$getBillDetail[0]['daddress'].'</td>
         </tr>

          <tr>
            <td align="left">--------------------------------------------</td>
            <td align="center">--------------------------------------------</td>
            <td align="right">--------------------------------------------</td>
         </tr>

         <tr>
            <td align="left"><b>Note: Total savings Rs.</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$getBillDetail[0]['odiscount'].'</td>
            <td align="center"></td>
            <td align="right"><b>Total Invoice Amount Rs. </b>&nbsp;&nbsp;&nbsp;&nbsp;'.$getBillDetail[0]['opayble'].'</td>
         </tr>
         <tr>
            <td align="left">--------------------------------------------</td>
            <td align="center">--------------------------------------------</td>
            <td align="right">--------------------------------------------</td>
         </tr>
         <tr>
            <td align="left"></td>
            <td align="center"></td>
            <td align="right"><b><h4>'."Thank You!".'</h4></b><b>'."For ordering medicines with us".'<br></b></td>
         </tr>
    </table>

    <table width="100%" style="text-align:center;">
        <div style="min-height:127px;">
         <span><h7>Contact For Support : 9999999992  Email: dawabag@gmail.com<h7></span>
        </div>  
       
    </table>';
            




    
    // output the HTML content
    $pdf->writeHTML($html, true, false, true, false, '');



        $pdf->Write(5, ' ');
        $pdf->Output($html.'OrderDateil.pdf', 'I');


?>