<?php 
/** 
 *
 * Classe de view
 * Herda de AppView
 * @param Array com os valores passados via post ou get por Global.js->showVs()
 * O parent::__construct (boolean para indicar se deve criar o controle
 * , diretorio do html, diretorio do css ou false, diretorio do js ou false).
 * Ex: parent::__construct (true, 'site/app.visual/html', 'site/app.visual/css', 'site/app.controle/js');
 */
class VsHeader extends AppView {
	
	function __construct($get = false) {
		# O parent::__construct recebe um boolean para indicar se deve criar o controle,
		# caminho do arquivo, 
		# se possui css, 
		# se possui js, 
		# qual header deve ser carregada ou false, 
		# qual footer deve ser carregado ou false
		# parent::__construct();
		parent::__construct (true, 'global', false, false);
	 	
		# Se é rastreado pelo google analytics (Global.js->trackGoogleAnalytics(url))
		# O script é criado na função AppView->show();
		$this->trackGoogle = false;
	}
}