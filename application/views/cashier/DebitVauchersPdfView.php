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
            <h3 style="text-align: center;">Firms Name</h3>
            <h3 style="text-align: center;">Debit Voucher</h3>
             <div class="box-title" style="paddign:0px;">
                <pre><strong>      Voucher No:                              Date:'. $date.'                  Credit Account :                                                          Amount :   </strong> </pre>  
            <div>
            <h2 style="text-align:center;paddign:0px;"> Debit Account</h2>
            <p style="text-align:center;">
                <table class="box-title" cellspacing="2" cellpadding="2" border="1 groove">
                    <tr style="text-align: center;">
                        <td>Sr.No</td>
                        <td>Account Name</td>
                        <td>Amount (Rs)</td>
                        <td>Narration (i.e. Explanation)</td>
                    </tr>
                    <tr style="min-height:427px;">
                            <td>
                                <p></p>
                                <p></p>

                            </td>
                            <td><p></p></td>
                            <td><p></p></td>
                            <td><p></p></td>
                    </tr>
            </table>
            </p>
            <div class="box-title" style="">
                <pre>
                    <strong>      Authorised By:                              Prepared By:                 
                    </strong>
                </pre>  
            <div>
            ';


// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');



	$pdf->Write(5, ' ');
	$pdf->Output('My-File-Name.pdf', 'I');


?>