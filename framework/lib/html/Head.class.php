<?php
/**
 * Monta a tag head do html inserindo todos os links, css e scripts
 * @author Diogo (d.bezerra@yahoo.com.br)
 *
 */
class Head extends Elemento {
	public $jsOnload = array ();
	public $links = array ();
	
	public function __construct() {
		parent::__construct ( 'HEAD' );
		# Jquery
		$this->add ( '<script language="javascript" type="text/javascript" src="' . Glb::$CONFIG['URL'] . '/global/app.controle/js/jquery-1.9.1.js"></script>' );
		$this->add ( '<script language="javascript" type="text/javascript" src="http://code.jquery.com/jquery-migrate-1.2.1.js"></script>' );
		$this->add ( '<script language="javascript" type="text/javascript" src="' . Glb::$CONFIG['URL'] . '/framework/plugins/jqueryui/js/jquery-ui-1.9.2.custom.js"></script>' );
		$this->add ( '<script language="javascript" type="text/javascript">var globalUrl = "' . Glb::$CONFIG['URL'] . '";var hview = "' . Glb::$CONFIG['HVIEW'] . '";</script>' );
		
		# Onload
		$this->jsOnload [] = '<script language="javascript" type="text/javascript">$(document).ready(function(){';
	}
	
	/**
	 * Insere todos os scripts e links definidos
	 *
	 */
	public function load() {
		$this->add ( '<script language="javascript" type="text/javascript" src="' . Glb::$CONFIG['URL'] . '/global/app.controle/js/onLoad.js"></script>' );
		$this->add ( $this->links );
		$this->jsOnload [] = "});</script>";
		$this->add ( $this->jsOnload );
	}
	
	public function setOnload($js) {
		$this->jsOnload [] = $js;
	}
	
	/**
	 * Define os links css que serão adicionados a tag head
	 * Ver config/setCss.
	 * @param (String) $arquivo: diretório do arquivo
	 */
	public function setLinksCss($arquivo) {
		if($arquivo) {
			if (file_exists(Glb::$CONFIG['RAIZGLB'] . "/" . $arquivo) and $arquivo){
				$this->links [] = "<link href='" . Glb::$CONFIG['URL'] . "/" . $arquivo . "' rel='stylesheet' type='text/css' />";
			}else{
				//echo "Css ". $arquivo . " não encontrado. Verifique o parent::__construct da classe correspondente.";
			}
		}
	}
	
	/**
	 * Define os links que serão adicionados a tag head.
	 * @param (String) $arquivo: diretório do arquivo
	 */
	public function setLinks($arquivo) {
		$this->links [] = "<link href='" . $arquivo . "' />";
	}
}