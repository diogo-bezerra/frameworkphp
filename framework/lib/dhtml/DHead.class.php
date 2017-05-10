<?php
class DHead extends DTag {
	
	public $_jsOnload = array ();
	public $_links = array ();
	
    public function __construct() {
    	parent::__construct('head');
    	### Inserindo scripts definitivos ###
    	$conteudo = '';
    	$conteudo = '<script language="javascript" type="text/javascript" src="' . Glb::$CONFIG['URL'] . '/global/app.controle/js/jquery-1.11.1.js"></script>';
    	//$conteudo .= '<script language="javascript" type="text/javascript" src="http://code.jquery.com/jquery-migrate-1.2.1.js"></script>' );
    	$conteudo .= '<script language="javascript" type="text/javascript" src="' . Glb::$CONFIG['URL'] . '/framework/plugins/jqueryui/js/jquery-ui-1.9.2.custom.js"></script>';
    	$conteudo .= "\n".'<script language="javascript" type="text/javascript">var globalUrl = "' . Glb::$CONFIG['URL'] . '";var hview = "' . Glb::$CONFIG['HVIEW'] . '";</script>';
    	$this->setIn($conteudo);
    	### // ###
        
        # Onload
        $this->_jsOnload [] = '<script language="javascript" type="text/javascript">$(document).ready(function(){';
    }
    
    /**
     * Insere todos os scripts e links definidos em onload.js
     * para serem executados
     *
     */
    public function loadhead () {
    	$this->setIn ( '<script language="javascript" type="text/javascript" src="' . Glb::$CONFIG['URL'] . '/global/app.controle/js/onLoad.js"></script>');
    	$this->setIn ( $this->_links );
    	$this->_jsOnload [] = "});</script>";
    	$this->setIn ( $this->_jsOnload );
    }
    
    public function setOnload($js) {
    	$this->_jsOnload [] = $js;
    }
    
    /**
     * Define os links css que serão adicionados a tag head
     * Ver config/setCss.
     * @param (String) $arquivo: diretório do arquivo
     */
    public function setLinksCss($arquivo) {
    	if($arquivo) {
    		if (file_exists(Glb::$CONFIG['RAIZGLB'] . "/" . $arquivo) and $arquivo){
    			$this->_links [] = "\n<link href='" . Glb::$CONFIG['URL'] . "/" . $arquivo . "' rel='stylesheet' type='text/css' />";
    		}else{
    			echo "Css ". $arquivo . " não encontrado. Verifique o parent::__construct da classe correspondente.";
    		}
    	}
    }
    
    /**
     * Define os links que serão adicionados a tag head.
     * @param (String) $arquivo: diretório do arquivo
     */
    public function setLinks($arquivo) {
    	$this->_links [] = "<link href='" . $arquivo . "' />";
    }
}

?>
