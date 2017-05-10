<?php
class Body extends Elemento {

    public $div_getJs;
    
    // Retorna um atributo
    public function __construct() {
        parent::__construct('BODY');
        // Verifica se o JS do navegador est� ativado (todas as p�ginas fazem essa verificação)
        $this->getJavaScript();
    }

    // Verifica se o JS est� ativado. Se n�o estiver mostra uma mensagem sen�o executa normalmente
    function getJavaScript() {
        // Tabela que mostra a mensagem de carregando. Se o JS n�o estiver ativado ela permanece. 
        $tb_getJsMsg = new Tabela('tb_getJsMsg', 1, 1);
        $tb_getJsMsg->setPropriedades(array(
            'border' => '0px',
            'class' => 'fonte01_3_cinza',
            'width' => '100%'
        ));
        $tb_getJsMsg->td[1][1]->setPropriedades(array(
            'align' => 'center',
            'id' => 'noJs',
            'style' => "height:400px;vertical-align:top;width:90%;"
        ));

        $img_logo = new Imagem('img_logo', '', Glb::$CONFIG['URL'].'/global/app.visual/imagens/logo.png');
        $img_logo->setPropriedades(array(
            'height' => '50px'
        ));
        $img_load = new Imagem('$img_load', '', Glb::$CONFIG['URL'].'/global/app.visual/imagens/loading.gif');
        $br = new Elemento('BR');

        $tb_getJsMsg->td[1][1]->add(array(
            $img_logo,
            '<br />Bem vindo<br /><br />',
            $br,
            "Identificamos que a permissão para utilização de miniaplicativos (Java Script) em sua máquina está desativada.<br />Verifique se o JS em seu navegador está ativo.<br /><br />",
            '<a href="http://java.com/pt_BR/download/help/5000021600.xml" target="_BLANK">Click aqui para saber como ativar o JavaScript em sua máquina</a><br /><br />'
        ));

        // Div que armazena todo o conte�do do site. Está oculto e somente aparece se o JS do elemento $script for executado
        $div_getJs = new Elemento('DIV');
        $div_getJs->setPropriedades(array(
            'id' => 'getJavascript',
            'style' => 'display:none'
        ));

        // Monta o script que torna visível o conteúdo do site e esconde a mensagem de carregando
        $scriptGetJs = new Elemento('SCRIPT');
        $scriptGetJs->setPropriedades(array(
            'language' => 'javascript',
            'type' => 'text/javascript'
        ));
        $scriptGetJs->add(array(
            "document.getElementById('getJavascript').style.display = 'inline';",
            "document.getElementById('tb_getJsMsg').style.position ='absolute';",
            "document.getElementById('tb_getJsMsg').style.left=0;",
            "document.getElementById('noJs').innerHTML = '';",
            "document.getElementById('noJs').style.position = 'absolute';",
            "document.getElementById('noJs').style.height = 0+'px';",
        ));

        ###$this->add($tb_getJsMsg); // Adiciona a tabela de mensagem ao body
        $this->div_getJs = $div_getJs; // Define o div do conteúdo do site como uma variável do elemento body
        $this->div_getJs->add($scriptGetJs); // Adiciona ao div o elemento $scriptGetJs
        ###$this->add($this->div_getJs); // Adiciona o div ao elemento body para ser mostrado
    }
}