<?php
/**
 * Cria o html de um elemento LinkCss
 */
 class LinkCss extends Elemento {
    public function __construct($href = false) {
        parent::__construct('LINK');
        if ($href) {
            $this->setPropriedades(array(
                'rel' => 'stylesheet',
                'type' => 'text/css',
                'href' => $href
            ));
        } else {
            $erro = new Erro('Erro');
            $erro->dieProcess('Falta o href do link css.');
        }
    }

}

?>
