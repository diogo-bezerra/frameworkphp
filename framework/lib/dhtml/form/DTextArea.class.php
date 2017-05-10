<?php
class DTextArea extends DTag {
    public function __construct($name, $rows = 10, $cols = 10) {
    	parent::__construct('textarea');
    	//$this->id($name);
    	$this->id = $name;
    	$this->name = $name;
    	$this->rows = $rows;
    	$this->cols = $cols;
    }
}

?>
