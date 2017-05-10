<?php
@session_start ();
if(strpos($_SERVER['REQUEST_URI'], 'ctrl=Ctrl') !== false){
	die('Carregamento de controle via url não permitido');
}
# ----> Global.js->callCtrlGet ou callCtrlPost
# Executa uma função qualquer classe controle
include_once("../config/setGlbVars.php");
include_once '../../global/app.controle/Global.class.php';
$global = new Glb();

$erro = new Msg('','Erro de acesso ctrl');
foreach ($_POST as $k=>$v) {
	//echo $_POST['caller'];
	// Verifica qual função chamou ctrl.php para funções adicionais 
	if(is_string($v)) {
		switch ($_POST['caller']) {
			case 'callCtrlPost':
				$post[$k] = ($v);
			break;
			case 'callCtrlPostConcat':
				$post[$k] = $v;
			break;
			case 'formUpload':
				$post[$k] = urldecode($v);
			break;
			case 'submitForm':
				$post[$k] = urlencode($v);
			break;
		}
	} else {
		$post[$k] = $v;
	}
}

$params = array_merge($post, $_GET, $_FILES);

$erro->set('','Controle não encontrado');
@$classe = $params['ctrl'] or $erro->show();

# Bloqueia o acesso a outras classes que não sejam de controle (Ctrl) para evitar acessos diretos
if (strpos($classe, 'Ctrl') === FALSE) {
	$erro->set('','Acesso bloqueado');
	$erro->show();
}
$erro->set('','Função de controle não encontrada');
@$funcao = $params['f'] or $erro->show();
# Verificando se a classe existe
if(class_exists($classe)){
	# Se os dados vierem de um form passa somente o post senão passa os dados como parâmetros da função
	if (isset($params['formdw436rtd4'])) {
		if (!empty($params['f'])) {
			# Instância do Controle
			$ctrl = new $classe();
			# Chama uma função do controle passando o post
			$ctrl->$funcao($params, 'ajaxhgwrth56fdwe');
		} else {
			# Instância do Controle
			@$ctrl = new $classe($params) or $erro->dieProcess('Classe não encontrada ('.$classe.')');
		}
	} else {
		foreach ($params as $atb) {
			$param[] = $atb;
		}
		if (!empty($funcao)) {
			# Instância do Controle
			$ctrl = new $classe();
			//print_r($param);
			# Chama uma função do controle podendo passar até 10 parâmetros
			# Determinadas funções não podem ser acessadas via ajax. 
			# Para evitar a chamada dessas funções utilize __ no início do nome da função
			if(strpos($funcao, '__') === FALSE){
				@$ctrl->$funcao(@$param[4], @$param[5], @$param[6], @$param[7], @$param[8], @$param[9], @$param[10], @$param[11], @$param[12]);
			}
		} else { // Se não houver função passa os parâmetros para o construtor da classe
			// Instância do Controle
			@$ctrl = new $classe(@$param[4], @$param[5], @$param[6], @$param[7], @$param[8], @$param[9], @$param[10], @$param[11], @$param[12]) or $erro->dieProcess('Classe não encontrada');
		}
	}
} else {
	$erro->set('','Classe não encontrada ('.$classe.')');
	$erro->show();
}

?>