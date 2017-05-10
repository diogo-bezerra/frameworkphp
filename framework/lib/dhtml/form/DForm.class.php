<?php
class DForm extends DTag {
    public function __construct($name, $action = '#', $method = 'post') {
    	parent::__construct('form');
    	//$this->id($name);
    	$this->id = $name;
    	$this->name = $name;
    	$this->action = $action;
    	$this->method = $method;
    }
}

?>
