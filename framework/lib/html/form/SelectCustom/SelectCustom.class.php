<?php

// Classe para abstração de tags HTML
class SelectCustom extends Tabela {

    public $span;
    public $select;
    public $opt = array();
    public $td;
    public $img_seta;
    public $divConteudo;
    public $spanConteudo;

    public function __construct($id, $largura, $fonte_size = '14') {
        parent::__construct('tb_' . $id, 1, 1);

        // Definindo as propriedades da tabela objeto
        $this->setPropriedades(array(
            'border' => '0px',
            'cellspacing' => '0px',
            'cellpadding' => '0px',
            'style' => 'display:inline'
        ));
        // Definindo as propriedades da td 1 da tabela objeto
        $this->td[1][1]->setPropriedades(array(
            'align' => 'left',
            'valign' => 'top',
            'style' => 'font-family:verdana;font-size:' . $fonte_size . 'px;height:' . ($fonte_size + 12) . 'px;width:' . $largura . 'px;z-index:0'
        ));

        // Definindo a margem superior da seta do select
        $img_seta_mtop = $fonte_size - 12;
        if ($img_seta_mtop <= 3) {
            $img_seta_mtop = 3;
        }
        // Seta do select
        $this->img_seta = new Imagem('', '', Glb::$CONFIG['URL'] . '/lib/html/form/SelectCustom/seta_select.png');
        $this->img_seta->setPropriedades(array(
            'align' => 'right',
            'style' => 'position:absolute;margin-left:' . ($largura - 15) . 'px;top:' . $img_seta_mtop . 'px;z-index:3',
        ));

        // Div que recebe o conteúdo do select para ser mostrado
        $this->divConteudo = new Elemento('div');
        $this->divConteudo->setPropriedades(array(
            'class' => 'borda_arredondada',
            'style' => 'background-image:url(' . Glb::$CONFIG['URL'] . '/lib/html/form/SelectCustom/fundo_select_custom.jpg);background-color:#CCCCCC;width:' . $largura . 'px;border:2px solid #666666;font-weight:bold;font-size:' . ($fonte_size - 2) . 'px;padding:3px;position:relative;z-index:3;'
        ));
        // Div que recebe o conteúdo do select para ser mostrado
        $this->spanConteudo = new Elemento('div');
        $this->spanConteudo->setPropriedades(array(
            'id' => $id . 'conteudo',
            'style' => 'border:0px solid #00FF00;font-size:' . ($fonte_size - 3) . 'px;overflow:hidden;background-image:url(' . Glb::$CONFIG['URL'] . '/lib/html/form/SelectCustom/fundo_select_custom.jpg);width:' . ($largura - 15) . 'px;'
        ));

        $this->divConteudo->add(array(
            $this->spanConteudo,
            $this->img_seta
        ));

        // Select
        $this->select = new Select($id, $largura . 'px');
        $this->select->setPropriedades(array(
            'class' => 'transparente', // Classe que deixa o select invisível
            'onchange' => 'document.getElementById(\'' . $id . 'conteudo\').innerHTML = this.options[this.selectedIndex].text;document.getElementById(\'' . $id . 'conteudo\').style.overflow=\'hidden\'', // D� ao span_conte�do o valor selecionado do select
            'style' => 'font-size:' . $fonte_size . 'px;margin-top:0px;position:absolute;width:' . ($largura + 10) . 'px;z-index:4'
        ));

        // Adicionando os elementos criados à td 1
        $this->td[1][1]->add(array(
            $this->select,
            $this->divConteudo,
        ));
    }

    // Define um option do select. @param(id,value,conteudo,selected)
    public function setOpt($idOpt, $value, $conteudo, $selected = FALSE) {
        // Dá ao divConteudo o valor do option selecionado inicialmente
        if ($selected == TRUE) {
            $this->spanConteudo->add(array(
                $conteudo
            ));
        }
        // Instancia um elemento e armazena no array opt
        $this->opt[$idOpt] = new Elemento('OPTION');
        // Define a propriedade value
        $this->opt[$idOpt]->setPropriedades(array(
            'value' => $value
        ));
        // Adiciona ao elemento <option> o conteudo
        $this->opt[$idOpt]->add($conteudo);
        // Adiciona ao elemento <select> o option
        $this->select->add($this->opt[$idOpt]);
        // Torna o option selecionado caso $selected seja TRUE
        if ($selected) {
            $this->opt[$idOpt]->setPropriedades(array(
                'selected' => 'selected'
            ));
        }
    }

}

?>
