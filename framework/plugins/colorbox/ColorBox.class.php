<?php
/**
 * 
 * @author Diogo
 * @param $href: view que será carregada, $remove: indica se o colorbox 
 * deve ser aberto na mesma janela ou em uma janela diferente
 * Para retornar o erro em caso de falha carregue o link retornado pela classe.
 * Ex: http://localhost/framework/lib/view.php?vs=VsContato&colorbox=colorboxvrtdgdfgs
 */
class ColorBox{
	function __construct(){

	}

	static function set($href, $utf8 = false, $remove = true){
		if($utf8){
			$utf8 = 'true';
		}else{
			$utf8 = 'false';
		}
		if($remove){
			return "$.colorbox.remove();$.colorbox({href:'".Glb::$CONFIG['URL']."/framework/lib/view.php?vs=$href&utf8=$utf8'});return false;";
		}else{
			return "$.colorbox({href:'".Glb::$CONFIG['URL']."/framework/lib/view.php?vs=$href'});return false;";
		}
	}

	static function close(){
		return "$.colorbox.close();";
	}
}
?>
