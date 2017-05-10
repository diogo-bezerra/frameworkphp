<?php
include_once("../config/setGlbVars.php");
include_once '../../global/app.controle/Global.class.php';
$global = new Glb();

$erro = new Msg('Controle de view','Página não encontrada');
$params = array_merge($_POST, $_GET);
@$classe = $params['vs'] or $erro->show(true);
# Bloqueia o acesso a outras classes que não sejam de view (Vs) para evitar acessos diretos
if (strpos($classe, 'Vs') === FALSE) {
	$erro->set('Controle de view', 'Erro de requisição');
    $erro->show(true);
}

# Instancia do view
$erro->set('Controle de view', 'Classe não encontrada ('.$classe.')');
if (class_exists($classe)) {
	$vs = new $classe($params);
}else{
	$erro->show(true);
}

# Mostra o html
# Define utf8 (em setUrl.php)
if(@$params["utf8"] == "false"){
	$sh = $vs->show();
}else{
	$sh = $vs->show('utf');
}
?>


