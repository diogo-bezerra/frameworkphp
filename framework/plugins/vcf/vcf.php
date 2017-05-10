<?php
header("Content-type: text/directory");
header("Content-Disposition: attachment; filename= contatos.vcf");
header("Pragma: public");

$txt = '';
$fh = fopen('vcffile.txt','r');
while ($line = fgets($fh)) {
	$txt .= $line;
}
fclose($fh);

echo $txt;
