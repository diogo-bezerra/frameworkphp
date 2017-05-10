<?php
class Relogio extends Imagem
{	
	function __construct($tam = 12, $cor = 'FFFFFF', $fonte = 'Tahoma', $target = 'divRelogio'){
		if(!preg_match('/^[a-fA-F0-9]+$/', $cor) or strlen($cor) != 6){
			die('A cor do texto da classe TextoImagem está errada (utilize o formato FFFFFF)');
		}
		$script = new ScriptJs(false, 'carregaScript("'.Glb::$CONFIG['URL'].'/framework/plugins/relogio/relogio.js")');
		$script->show();
		$script = new ScriptJs(false, "setTimeout('getHora($tam,\"$cor\",\"$fonte\",\"$target\")', 1000)");
		$script->show();
	}
}

?>