<?php

/** Classe para criação de Iframes. 
 */
class Iframe extends Elemento {

    // Constroi o iframe. @param(id,frameborder,scroll,height,width,src)
    public function __construct($id, $borda, $scroll, $height, $width, $src) {
        parent::__construct('IFRAME');
        // Instancia automaticamente a quantidade de TRs e TDs passadas e adiciona na TAG da tabela
        $this->setPropriedades(array(
            'id' => $id,
            'name' => $id,
            'frameborder' => $borda,
            'height' => $height,
            'scrolling' => $scroll,
            'width' => $width,
            'src' => $src
        ));
        // Define o arquivo de JavaScript para essa classe para ser incluido na tag Head
        // Head::setScripts('app.controle/js/Iframe.js');
    }

    // Define o src do iframe
    public function setSrc($src) {
        switch ($src) {
            case '':

                break;
        }
        $this->setPropriedades(array(
            'src' => $src
        ));
    }

}

?>
