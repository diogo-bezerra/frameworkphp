<?php
$globalUrl = $_POST['globalUrl'];
$tam = $_POST['tam'];
$cor = $_POST['cor'];
$fonte = $_POST['fonte'];
$data = date("H:i:s");
echo '<img alt="" src="'.$globalUrl.'/plugins/relogio/textoImg.php?tam='.$tam.'&texto='.$data.'&cor='.$cor.'&fonte='.$fonte.'" />';
?>