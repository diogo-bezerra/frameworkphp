<?php
class DScriptJs extends DTag {
    public function __construct($conteudo, $issrc = false) {
    	parent::__construct('script');
    	if ($issrc) {
			$this->language = 'javascript';
			$this->type= 'text/javascript';
			$this->src = $conteudo;
		} else {
			$this->setIn( $conteudo );
		}
    }
}

?>
