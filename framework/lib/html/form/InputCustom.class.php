<?php

/**
 * Cria o html de um elemento Input Customizado
 * DEPRECATED: Utilizar CSS
 */
class InputCustom extends Tabela {

    public $input;
    private $id;

    public function __construct($id, $tipo, $value, $largura, $fonte = 'fonte01_6_cinza') {
        parent::__construct('tb_input' . $id, 1, 1);

        if ($tipo != 'hidden') {
            $nav = Glb::detect_browser();
            $display = 'display:inline';
            if ($nav == 'Chrome') {
                $display = 'display:inline-table';
            }
            // Definindo as propriedades da tabela objeto
            $this->setPropriedades(array(
                'border' => '0px',
                'cellspacing' => '0px',
                'cellpadding' => '0px',
                'style' => $display
            ));
            // Definindo as propriedades da td 1 da tabela objeto
            $this->td[1][1]->setPropriedades(array(
                'align' => 'left',
                'valign' => 'top',
                'style' => 'font-family:verdana;',
                'width' => $largura . 'px;'
            ));

            // Div que recebe a imagem do background
            $this->divBackground = new Elemento('div');
            $this->divBackground->setPropriedades(array(
                'class' => 'borda_arredondada ' . $fonte, // Classe que arredonda as bordas
                'style' => 'position:relative;border:2px #666666 solid;background-image:url(' . Glb::$CONFIG['URL'] . '/global/app.visual/imagens/fundo_input_custom.jpg);padding-top:4px;padding-left:4px;width:' . ($largura + 20) . 'px;z-index:3'
            ));

            // input
            $this->input = new Input($id, $tipo, $value);
            $this->input->setPropriedades(array(
                'class' => $fonte,
                'style' => 'border:0px;background-image:url(' . Glb::$CONFIG['URL'] . '/app.visual/imagens/fundo_input_custom.jpg);margin-top:3px;margin-left:6px;position:absolute;width:' . ($largura + 15) . 'px;z-index:4'
            ));
            $this->td[1][1]->add(array(
            ));
            $this->divBackground->add(array(
                '&nbsp'
            ));
        } else {
            // input
            $this->input = new Input($id, $tipo, $value);
            $this->input->setPropriedades(array(
                'style' => 'position:absolute;'
            ));
            $this->setPropriedades(array(
                'border' => '0px',
                'cellspacing' => '0px',
                'cellpadding' => '0px',
                'style' => 'position:absolute;'
            ));
            $this->divBackground = '';
        }

        // Adicionando os elementos criados � td 1
        $this->td[1][1]->add(array(
            $this->input,
            $this->divBackground
        ));
    }

    // Reseta o valor do input quando � focado e o valor � igual ao passado por par�metro. @param(valor a ser comparado)
    function resetValue($conteudo) {
        $this->input->setPropriedades(array(
            'onfocus' => 'resetValue(\'' . $this->input->get('id') . '\',\'' . $conteudo . '\')'
        ));
    }

}

?>
