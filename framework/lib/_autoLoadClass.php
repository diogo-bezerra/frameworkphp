<?php
# Insere classes automaticamente quando instanciadas
function __autoload($nomeClasse) {
	include Glb::$CONFIG['RAIZGLB'].'/framework/config/setPlugins.php';
	$dir = array(
		#### Modelo ####
		'AppModelo' => 'framework/global/app.modelo/',

		#### Visual ####
		'AppView' => 'framework/global/app.visual/',
		
		#### Controle ####
		'AppCtrl' => 'framework/global/app.controle/',

		#### Classes de Plugins ####
	
		#### Lib DomHTML ####
		'DHead' => 'framework/lib/dhtml/',
		'DHtml5' => 'framework/lib/dhtml/',
		'DData' => 'framework/lib/dhtml/form/',
		'DFile' => 'framework/lib/dhtml/form/',
		'DForm' => 'framework/lib/dhtml/form/',
		'DImg' => 'framework/lib/dhtml/',
		'DInput' => 'framework/lib/dhtml/form/',
		'DLink' => 'framework/lib/dhtml/',
		'DScriptJs' => 'framework/lib/dhtml/',
		'DSelect' => 'framework/lib/dhtml/form/',
		'DTable' => 'framework/lib/dhtml/',
		'DTag' => 'framework/lib/dhtml/',
		'DTextArea' => 'framework/lib/dhtml/form/',
		'DWysiwyg' => 'framework/lib/dhtml/form/',

		#### Lib HTML ####
		'Body' => 'framework/lib/html/',
		'Css' => 'framework/lib/html/',
		'Elemento' => 'framework/lib/html/',
		'Head' => 'framework/lib/html/',
		'Iframe' => 'framework/lib/html/',
		'Imagem' => 'framework/lib/html/',
		'Link' => 'framework/lib/html/',
		'LinkCss' => 'framework/lib/html/',
		'Tabela' => 'framework/lib/html/',
		'TextoImagem' => 'framework/lib/html/',
		'Form' => 'framework/lib/html/form/',
		'Input' => 'framework/lib/html/form/',
		'InputCustom' => 'framework/lib/html/form/',
		'InputFileCustom' => 'framework/lib/html/form/',
		'Msg' => 'framework/lib/',
		'ScriptJs' => 'framework/lib/html/',
		'Select' => 'framework/lib/html/form/',
		'SelectCustom' => 'framework/lib/html/form/SelectCustom/',
		'SelectCustom2' => 'framework/lib/html/form/',
		'TextArea' => 'framework/lib/html/form/',
		'Wysiwyg' => 'framework/lib/html/form/',

		#### Banco de Dados ####
		'ExecuteSql' => 'framework/lib/sql/',
		'SqlInject' => 'framework/lib/sql/',
		'TClausula' => 'framework/lib/sql/',
		'TExpressao' => 'framework/lib/sql/',
		'TFiltro' => 'framework/lib/sql/',
		'TSqlDelete' => 'framework/lib/sql/',
		'TSqlInsert' => 'framework/lib/sql/',
		'TSqlInstrucao' => 'framework/lib/sql/',
		'TSqlSelect' => 'framework/lib/sql/',
		'TSqlUpdate' => 'framework/lib/sql/'
	);
	
	$dir = array_merge($dir, $PLUGINS);
	$dir = array_merge($dir, Glb::$CLASSES);
	if(isset($dir[$nomeClasse])) {
		include_once(Glb::$CONFIG['RAIZGLB'] . '/' . $dir[$nomeClasse] . $nomeClasse . ".class.php");
		//echo $nomeClasse . ".class.php <br />";
	} else {
		$dirfile = Glb::$CONFIG['RAIZGLB'] . '/global/app.modelo/' . $nomeClasse . ".class.php";
		if(file_exists($dirfile)) {
			include_once($dirfile);
			//echo $dirfile . "<br />";
		} else {
			Glb::$CONFIG['RAIZGLB'] . '/global/app.visual/Vs404.class.php';
		}
	}
}
