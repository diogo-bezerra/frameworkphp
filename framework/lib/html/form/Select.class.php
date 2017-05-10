<?php

/**
 * Cria o html de um elemento Select
 */
class Select extends Elemento {
    public function __construct($id, $largura = false) {
        parent::__construct('SELECT');
        $this->id = $id;
        $this->name = $id;
        $this->largura = $largura;
        $this->setPropriedades(array(
            'id' => $id,
            'name' => $id
        ));
        if($largura){
        	$this->setPropriedades(array(
        		'style' => 'width:' . $largura
        	));
        }
    }

    /**
     * Define um elemento option para o select
     * @param unknown $idOpt
     * @param unknown $value
     * @param unknown $conteudo
     * @param string $selected: Se o option deve ser selecionado inicialmente
     */
    public function setOpt($idOpt, $value, $conteudo, $selected = FALSE) {
        # Instancia um elemento e armazena no array opt
        $this->opt[$idOpt] = new Elemento('OPTION');
        # Define a propriedade value
        $this->opt[$idOpt]->setPropriedades(array(
            'value' => $value
        ));
        # Adiciona ao elemento <option> o conteudo
        $this->opt[$idOpt]->add($conteudo);
        # Adiciona ao elemento <select> o option
        $this->add($this->opt[$idOpt]);
        # Torna o option selecionado caso $selected seja TRUE
        if ($selected) {
            $this->opt[$idOpt]->setPropriedades(array(
                'selected' => 'selected'
            ));
        }
    }

}

?>
