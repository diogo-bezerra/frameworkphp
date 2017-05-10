<?php
/**
 * Cria o html de um elemento Wysiwyg
 */
class Wysiwyg extends Elemento {	
	// Retorna um atributo
	public function __construct($idTextArea,$largura,$altura){
		parent::__construct('SCRIPT');
		
		$this->setPropriedades(array(
			'language'=>'javascript',
			'type'=>'text/javascript'
		));
		
		$this->add(array(
			'wysiwygWidth='.$largura,
			'wysiwygHeight='.$altura,
			'generate_wysiwyg(\''.$idTextArea.'\')'
		));
   
		// Define o arquivo de JavaScript para essa classe para ser incluido na tag Head
		//Head::setScripts('app.controle/js/editor_texto/wysiwyg.js');
	}
}
?>
