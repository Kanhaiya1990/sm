<?php
    $this->load->library('Pdf');
	$pdf = new Pdf('P', 'mm', 'A6', true, 'UTF-8', false);
	$pdf->SetTitle('Report');
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

            $details = $details."<tr><td>".($i+1)."</td>
                <td>".$orderDetails[$i]['item']."</td>
                <td>".$orderDetails[$i]['mrp']."</td>
                <td>".$orderDetails[$i]['qty']."</td>
                <td>".$orderDetails[$i]['rate']."</td>
                <td>".$orderDetails[$i]['amt']."</td></tr>";
                $total=$total+$orderDetails[$i]['amt'];
        }    
        

        $html = '
       
    <table>
    <br /><br /><br />
         <tr>
            <td align="left"><b>Retailer Name :</b>'.$orderDetails[0]['retailer'].'</td>
            <td align="center"><b>Route : </b>'.$orderDetails[0]['route'].'</td>
            <td align="right"><b>Date : </b>'.date('d-m-Y').'</td>
         </tr>
         <tr>
            <td align="left"><b>Bill No : </b>'.$orderDetails[0]['billNo'].'</td>
            <td></td>
            <td></td>
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
    <table width="100%" style="text-align:right;">
        <div style="min-height:427px;">
         <span><b>Grand Total :</b> '.$total.'</span>
                
                
        </div>  
       
    </table>';

    // output the HTML content
    $pdf->writeHTML($html, true, false, true, false, '');



        $pdf->Write(5, ' ');
        $pdf->Output('My-File-Name.pdf', 'I');


?>