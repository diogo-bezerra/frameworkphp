<?php
/**
 * Cria o html de um elemento <Script>
 */
class ScriptJs extends Elemento {
	public function __construct($src = false, $conteudo = '') {
		parent::__construct ( 'SCRIPT' );
		if ($src) {
			$this->setPropriedades ( array (
					'language' => 'javascript',
					'type' => 'text/javascript',
					'src' => $src 
			) );
		} else {
			$this->add ( $conteudo );
		}
	}
}
