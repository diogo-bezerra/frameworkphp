<?php
@session_start ();
include_once("../config/setGlbVars.php");
include_once '../../global/app.controle/Global.class.php';
$global = new Glb();

$_SESSION['postctrlblank'] = $_POST;

if($_POST['blank'] == '1') {
	echo '<script type="text/javascript">window.open("'.$_POST['link'].'","_blank")</script>';
} else {
	echo '<script type="text/javascript">window.open("'.$_POST['link'].'","_self")</script>';
}
?>