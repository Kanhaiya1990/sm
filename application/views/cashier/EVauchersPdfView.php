<?php
	$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
	$pdf->SetTitle('E-Vaucher');
	$pdf->SetHeaderMargin(30);
	$pdf->SetTopMargin(20);
	$pdf->setFooterMargin(20);
	$pdf->SetAutoPageBreak(true);
	$pdf->SetAuthor('Author');
	$pdf->SetDisplayMode('real', 'default');

	$pdf->AddPage();
    $date=date("d-m-Y");
	$html = '<br />
            <h3 style="text-align: center;">Debit Voucher</h3>
            <h3 class="box-title" style="">
                <h5 style="text-align: center;">M/S New Central Book Agency(P) Ltd</h5>
                <h5 style="text-align: center;"><b> 8/1, Chintamoni Das Lane,Kolkota 700 009</b></h5>
                <h5 style="text-align: center;">Date :'.$date.'</h5>
             </h3>
                <div width="100%" style="text-align:left;top-paddign:0px;">
                    <b>Voucher No: P-103</b>          
                </div>

                <div width="100%">
                    <b>Debit,</b>
                    <table>

                       <tr>
                        <td>
                            <p style="text-align: justify;"> <br />
                                Office Equipment A/C <br />
                                -Being Office Equipment <br />
                                Purchase,Cash Memo No-90
                            </p>
                             <p></p> 
                            <p><b>S/d</b></p>
                             <p>Authorised Signtory</p>
                        </td>
                         <td>
                            <p></p>
                            <p></p>    
                            <p></p>   
                            <p></p>  
                            <p></p>  
                             <p></p> 
                            <p style="text-align:center;"><b>Total</b></p>
                        </td>
                        <td style="text-align:right;">
                        <p style="text-align: center;">Amount</p>
                        <table rowspan="2" colspan="2" cellspacing="2" cellpadding="2" border="1">
                            <tr style="text-align: center;">
                                <td>Rs <br />
                                    5,000.00
                                </td>
                            </tr>
                            <tr style="text-align: center;">
                                <td>
                                    5,000.00
                                </td>
                            </tr>
                        </table>
                            <p style="text-align: center;"><b>S/d</b></p>
                            <p>Authorised Signtory</p>
                        </td>
                        </tr>  
                    </table>
                </div>
                <hr />
                <p>Received...................................................................................................................................................................................................................................................................................................................</p>
                </p>
                <p style="text-align:right;">
                <b>
                    <center>Affix Stamp</center>
                </b>
            </p>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');



	$pdf->Write(5, ' ');
	$pdf->Output('My-File-Name.pdf', 'I');


?>