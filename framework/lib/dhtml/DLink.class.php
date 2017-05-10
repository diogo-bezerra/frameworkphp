<?php
class DLink extends DTag {
    public function __construct($conteudo, $href = false, $target = false) {
    	parent::__construct('a');
    	$this->setIn($conteudo);
    	
    	if($href) {
    		$this->href = $href;
    	}
    	if($target) {
    		$this->target = $target;
    	}
    }
}

?>
