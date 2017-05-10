<?php
/**
 * Classe de controle
 * Possui todas as funções de controle de dados da view correspondente.
 * Herda de AppCrl
 */
class CtrlExemploHome extends AppCtrl {
	function __construct() {
		parent::__construct();
	}
	
	# Supondo acesso à www.site.com.br/home
	# o fluxo do framework acessará setRoutes.php e 
	# verificará se home está definido como rota ('home'=>'CtrlExemploHome[showView()]')
	# showView() instancia e retorna VsExemploHome().
	
	# Qualquer view pode ser chamada de um controle.
	# Uma função de controle pode também receber parâmetros pela url informada.
	# Supondo acesso à www.site.com.br/home/10/x
	# showView() instancia e retorna VsExemploHome($param1, $param2)
	# onde $param1 = 10 e $param2 = 'x'
	
	# Uma função de controle pode ser requisitada através de AppCtrl::callCtrlPost()
	# Os parâmetros devem seguir o array passado por callCtrlPost 
	# (ver global/app.controle/AppCtrl.class.php)
	
	# Ex: Imprimindo uma view em um container chamado por 
	# AppCtrl::callCtrlPost('div', 'CtrlExemploHome', 'showVsExemplo2', array('value'=>'xx'), 'loading');
	function showVsExemplo2($value) {
		// $value recebe 'xx'
		$view = new VsVsExemplo2($value);
		$view->show();
	}
}
?>
