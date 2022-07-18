<?php
// 	$pdf = new Pdf('P', 'mm', 'A6', true, 'UTF-8', false);
	$pdf = new Pdf('P', 'mm', array('41','89'), true, 'UTF-8', false);
// 	$pdf->AddPage('L', '', false, false);
	$pdf->SetTitle('Billwise Details');
	$pdf->SetHeaderMargin(30);
	$pdf->SetTopMargin(20);
	$pdf->setFooterMargin(20);
	$pdf->SetAutoPageBreak(true);
	$pdf->SetAuthor('Author');
	$pdf->SetDisplayMode('real', 'default');

	$pdf->AddPage();

        $details = "";

        $total=0;

        for($i=0;$i<count($orderDetails);$i++) {

            $details = $details.'<tr><td>'.($i+1).'</td>
                <td align="left">'.$orderDetails[$i]['productName'].'</td>
                <td align="right">'.$orderDetails[$i]['mrp'].'</td>
                 <td align="center">'.number_format($orderDetails[$i]['qty']).' '.$orderDetails[$i]['qtyUnit'].'</td>
                <td align="center">'.number_format($orderDetails[$i]['sellingRate']).'/'.$orderDetails[$i]['sellingUnit'].'</td>
                <td align="right">'.$orderDetails[$i]['netAmount'].'</td></tr>';
                $total=$total+$orderDetails[$i]['netAmount'];
        }    
        

      $html = '
       <div classs="col-md-12"></div>
    <table>
         <tr>
            <td><b>Bill No : </b>'.$billNo.'</td>
            <td></td>
            <td><b>Bill Date : </b>'.$date.'</td>
            
         </tr>
         <tr>
            <td><b>Retailer : </b>'.$retailerName.'</td>
            <td><b>Route : </b>'.$routeName.'</td>
            <td><b>Salesman : </b>'.$salesman.'</td>
         </tr>
        
    </table>
    <br><br>
    <table border="1px" rowspan="2" colspan="2" style="text-align:center;">
        <tr>
            <td><b>Sr.No</b></td>
            <td><b>Name</b></td>
            <td><b>MRP</b></td>    
            <td><b>Quantity</b></td>
            <td><b>Rate</b></td>
            <td><b>Amount</b></td>
        </tr>
        
            '.$details.'
        
    </table>
    <br /> 
    <table width="100%" style="text-align:center;">
        <div style="min-height:427px;">
             <span>Grand Total : '.number_format($total).'</span>
        </div>  
       
    </table>';
    // output the HTML content
    $pdf->writeHTML($html, true, false, true, false, '');



        $pdf->Write(5, ' ');
        $pdf->Output($billNo.'-BillDetail.pdf', 'I');


?>