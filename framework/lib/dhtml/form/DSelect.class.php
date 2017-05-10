<?php
class DSelect extends DTag {
    public function __construct($name) {
    	parent::__construct('select');
    	$this->id = $name;
    	$this->name = $name;
    }
    
    public function setOption($conteudo, $value, $selected = false) {
    	$opt = new DTag('option');
    	$opt->value = $value;
    	$opt->setIn($conteudo);
    	$this->setIn($opt);
    	if($selected) {
    		$opt->selected = 'selected';
    	}
    }
}

?>
