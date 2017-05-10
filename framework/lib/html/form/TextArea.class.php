<?php

/**
 * Cria o html de um elemento TextArea
 */
class TextArea extends Elemento {

    private $id;

    public function __construct($id, $value, $col, $row, $sqlSeg = TRUE) {
        parent::__construct('TEXTAREA');
        $this->id = $id;
        $this->setPropriedades(array(
            'id' => $id,
            'cols' => $col,
            'rows' => $row,
            'name' => $id
        ));
        if ($sqlSeg) {
            $this->setPropriedades(array(
               //'onkeyup' => "segurancaSql(this.value,'" . $id . "')"
            ));
        }
        $this->add($value);
    }

    /**
     * Reseta o valor do textarea quando é focado e o valor é igual ao passado por parâmetro.
     * @param (String) valor a ser comparado
     */
    function resetValue($conteudo) {
        $this->setPropriedades(array(
            'onfocus' => 'resetValue(\'' . $this->id . '\',\'' . $conteudo . '\')'
        ));
    }

}

?>