<?php
header("Access-Control-Allow-Origin: *");
# Scripts que serão adicionados no index para uso em todo o site
# São carregados pela função setScripts em app.controle/js/onLoad.js
# onLoad.js é carregada no index pela função loadhead() da classe DHead (framework/lib/dhtml/DHead.class.php)
$scp = array(
		"global/app.controle/js/Global.js",
		"global/app.controle/js/Body.js",
		"global/app.controle/js/Elemento.js",
		"global/app.controle/js/html5shiv.min.js",
		"global/app.controle/js/prefixfree.min.js",
		"global/app.controle/js/Imagem.js",
		"global/app.controle/js/Form.js",
		"global/app.controle/js/Contato.js",
		//"global/app.controle/js/maskMoney.js",
		"framework/plugins/colorbox/jquery.colorbox.js",
		"global/app.controle/js/scripts.js",
		"global/app.controle/js/bootstrap-select.min.js",
		"global/app.controle/js/bootstrap.js"
		//"app.controle/api/login/login.js"
);

foreach ($scp as $s){
	echo $s;
}