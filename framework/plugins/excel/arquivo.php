<?php
// Configurações header para forçar o download  
    $arquivo = $_POST['arquivo'];
    $html = $_POST['html'];  
    //header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
    header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");  
    header ("Cache-Control: no-cache, must-revalidate");  
    header ("Pragma: no-cache");  
    header ("Content-type: application/x-msexcel");  
    header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );  
    header ("Content-Description: PHP Generated Data" );     
   echo $html;
   exit; 
        

?>