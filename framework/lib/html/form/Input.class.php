<?php

/**
 * Cria o html de um elemento Input
 */
class Input extends Elemento {
    private $id;
    public function __construct($id, $tipo, $value = '', $width = 100) {
        parent::__construct('INPUT');
        $this->id = $id;
        $this->setPropriedades(array(
            'id' => $id,
            'name' => $id,
            'type' => $tipo,
            'value' => $value,
            'onkeyup' => "segurancaSql(this.value,'" . $id . "')",
            'style' => 'width:' . $width
        ));
    }

    /**
     * Reseta o valor do input quando � focado e o valor � igual ao passado por par�metro. 
     * @param (String) valor a ser comparado
     */ 
    function resetValue($conteudo) {
        $this->setPropriedades(array(
            'onfocus' => 'resetValue(\'' . $this->id . '\',\'' . $conteudo . '\')'
        ));
    }
}

?>
