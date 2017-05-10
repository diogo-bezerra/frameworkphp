<?php
/**
 *
 * Cria o html de um elemento Link (a)
 * @Param id
 * @Param href
 * @Param target
 * @Param conteudo
 *
 */
class Link extends Elemento {

    public function __construct($id, $href = false, $target = false, $conteudo) {
        parent::__construct('A');
        if ($href) {
            $this->setPropriedades(array(
                'href' => $href
            ));
        }
        if ($target) {
            $this->setPropriedades(array(
                'target' => $target
            ));
        }
        $this->setPropriedades(array(
            'id' => $id,
            'name' => $id
        ));
        $this->add($conteudo);
    }
}

?>
