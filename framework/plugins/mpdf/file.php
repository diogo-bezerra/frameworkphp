<?php
require_once 'mpdf.php';
$txt = '';
$fh = fopen('pdffile.txt','r');
while ($line = fgets($fh)) {
	$txt .= $line;
}
fclose($fh);
//echo $txt;
$pdf = new mPDF('', 'A4',9,'dejavusans');
$pdf->WriteHTML($txt);
$pdf->Output('arq'.'.pdf', 'I');
exit();