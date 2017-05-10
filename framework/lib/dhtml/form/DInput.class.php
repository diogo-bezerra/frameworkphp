<?php
class DInput extends DTag {
    public function __construct($name, $type = 'text') {
    	parent::__construct('input');
    	$this->id = $name;
    	$this->name = $name;
    	$this->type = $type;
    }
}

?>
