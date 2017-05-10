<?php 
/**
 *
 * Classe de view
 * Herda de AppView
 * @param Array get com os valores passados via post ou get por Global.js->showVs()
 *
 */
class Vs404 extends AppView {
	
	function __construct($get = false) {
		# O parent::__construct recebe um boolean para indicar se deve criar o controle.
		# parent::__construct();
		# Se a view não possuir classe de controle, adicionar o parâmetro false
		// Ex: parent::__construct(false);
		parent::__construct (false, 'global');
	 	
	 	
		# Se é rastreado pelo google analytics (Global.js->trackGoogleAnalytics(url))
		# O script é criado na função AppView->show();
		$this->trackGoogle = false;
		
	}
}