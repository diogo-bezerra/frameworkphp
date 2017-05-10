<?php

class Excel extends Elemento {

    private $nomeArq;
    private $nomePlanilha;
    private $html;
    private $nome_empresa;

    function __construct($nomeArq, $nomePlanilha, $html, $nomeLink = 'exl') {
        // Nome do arquivo que ser� exportado  
        parent::__construct('SPAN');
        $arquivo = $nomeArq . '.xls';
        $form = "<form name='$nomeArq' action='" . Glb::$CONFIG['URL'] . "/framework/plugins/excel/arquivo.php' method='post' target='_blank' style='display:inline'>";
        $form .= "<input name='arquivo' type='hidden' value='$arquivo' />";
        $form .= "<textarea name='html' style='visibility:hidden;position:absolute'>" . $html . "</textarea>";
        $form .= "<a href='javascript:void(0)' onclick='document.$nomeArq.submit()'>" . $nomeLink . "</a>";
        $form .= "</form>";
        $this->add($form);
    }

    function download() {
        
    }

    function __destruct() {
        # echo "<br /> <br /> Objeto destru�do";
    }

    // Retorna um atributo
    function get($nome_atributo) {
        return $this->$nome_atributo;
    }

    // Define um atributo
    function set($nome_atributo, $novo_valor) {
        $this->$nome_atributo = $novo_valor;
    }

}

?>
