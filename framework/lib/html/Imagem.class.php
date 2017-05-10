<?php
/**
 * Cria o html de um elemento Imagem
 */
class Imagem extends Elemento {

    public $id;
    public $name;
    public $alt;
    public $src;

    public function __construct($id, $alt, $src) {
        parent::__construct('IMG');
        $this->id = $id;
        $this->name = $id;
        $this->alt = $alt;
        $this->src = $src;
        $this->setPropriedades(array(
            'border' => '0px',
            'id' => $id,
            'name' => $id,
            'alt' => $alt,
            'src' => $src
        ));
    }

    /** Reduz uma imagem de acordo com o percentual passado. 
     * @param (int) percentual para redimencionar uma imagem.
     */
    function resize($percent) {
        $arquivo = $this->src;
        $percent = $percent / 100;
        # Get new sizes
        list($width, $height) = getimagesize($arquivo);
        $newwidth = $width * $percent;
        $newheight = $height * $percent;
        # Redefinindo altura e largura
        $this->setPropriedades(array(
            'height' => $newheight,
            'width' => $newwidth
        ));
    }

}
