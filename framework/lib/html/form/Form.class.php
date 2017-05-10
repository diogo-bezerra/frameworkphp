<?php

/**
 * Cria o html de um elemento Form
 */
class Form extends Elemento {
    public $id;
    public $action;
    public $method;
    public $enctype;
    public $target;

    public function __construct($id, $action, $method, $target = NULL, $enctype = NULL) {
        parent::__construct('FORM');
        $this->setPropriedades(array(
            'id' => $id,
            'name' => $id,
            'action' => $action,
            'method' => $method,
            'target' => $target,
            'enctype' => $enctype
        ));
    }
}

?>
