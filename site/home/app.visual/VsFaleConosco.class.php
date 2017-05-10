<?php
/**
 * 
 * Classe de view
 * Herda de AppView
 * @param Array get com os valores passados via post ou get por Global.js->showVs()
 *
 */
class VsFaleConosco extends AppView{
	
	function __construct($post = false) {
		# O parent::__construct recebe um boolean para indicar se deve criar o controle,
		# caminho do arquivo,
		# se possui css,
		# se possui js,
		# qual header deve ser carregada ou false,
		# qual footer deve ser carregado ou false
		# parent::__construct();
		parent::__construct (true, 'site/home', true, true, 'VsHeader', 'VsFooter');
		
		# Se é rastreado pelo google analytics (Global.js->trackGoogleAnalytics(url))
		# O script é criado na função AppView->show();
		$this->trackGoogle = false;
		
		#### Variáveis do post ####
		$section_main = $this->getHtmlTag('main');
		$bt_enviar = $this->getHtmlTag('bt_enviar');
		$bt_enviar->onclick = "if(verificaForm('form_contato')){".AppCtrl::submitForm('main', 'CtrlFaleConosco', 'enviaContato', 'form_contato', 'Enviando. Aguarde...').'}';
	}
}
?>


