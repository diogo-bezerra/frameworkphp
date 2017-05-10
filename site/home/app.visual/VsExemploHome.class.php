<?php 
/**
 *
 * Classe de view
 * Herda de AppView
 * @param Array $params com os valores passados via post ou get por Global.js->showVs()
 *
 */
class VsExemploHome extends AppView {
	
	function __construct($params = false) {
		# O parent::__construct recebe um boolean para indicar se deve criar o controle,
		# Diretório do arquivo, 
		# se possui css,
		# se possui js, 
		# qual header deve ser carregada ou false, 
		# qual footer deve ser carregado ou false
		
		# O controle, o html, o css e o js devem seguir as nomeclaturas CtrlExemploHome, exemploHome.html, exemploHome.css e exemploHome.js respectivamente
		
		# parent::__construct();
		parent::__construct (true, 'site/home', true, true, 'VsHeader', 'VsFooter');
	 	
		# Se é rastreado pelo google analytics (Global.js->trackGoogleAnalytics(url))
		# O script é criado na função AppView->show();
		$this->trackGoogle = true;
		
		# Obtendo um elemento div de exemploHome.html pela id
		$div1 = $this->getHtmlTag('div1'); 
		
		#Inserindo conteúdo em um elemento
		$div1->setIn('Home'); 
		$div1->setIn('Home -  site/home/app.visual/VsExemploHome.class.php<br />', true); // True indica que todo o conteúdo já existente deve ser substituido
		
		#Criando um elemento html com a biblioteca DHtml (ver outras classes em framework/lib/dhtml)
		$img = new DImg('alt', Glb::$CONFIG['URL'].'/global/app.visual/imagens/logo.png');
		$img->id = 'img_logo'; // Ver site/home/app.visual/css/exemploHome.css'
		
		#Inserindo o objeto DImg na div criada
		$div1->setIn($img);
		$div1->setIn('<br />');
		
		#Inserindo o objeto DImg na div criada em formato string
		$div1->setIn($img->toStr().'<br />');
		
		# Uma elemento html também pode ser obtido pelo valor de um atributo
		// Retorna a primeira div cujo valor do atributo name é igual a 'div2'
		$div2 = $this->getHtmlTagByAtt('div', 'name', 'div2', 1); 
		
		#Executa um comando ou função js
		//@param comando ou função $jsfunc
		//@param number $pos: indica se executa antes (0) ou depois (1) do load (default = 0)
		$this->callLoadJs('alert("msg")', 0);
		
		#Definindo atributos de um elemento
		$button = $this->getHtmlTag('buton');  
		$button->onclick = 'alert("click")';
		
		$img->alt = 'logo';
		$img->src = Glb::$CONFIG['URL'].'/global/app.visual/imagens/logo.png';
		// Glb::$CONFIG['URL']: url global definida em framework/config/setGlobalVars.php
		
		#Chamando funções de controles
		$button->onclick = AppCtrl::callCtrlPost(
			'[id do container]', 
			'[nome da classe controle]', 
			'[nome da função do controle]',
			array(),//$array_com_os_parametros_da_funcao,
			'mensagem do loading'
		);
		
		#Submetendo um form para um container
		// A função deve receber um parâmetro $post
		$button->onclick = AppCtrl::submitForm(
			'[id do container]',
			'[nome da classe controle]',
			'[nome da função do controle]',
			'[nome do form]',
			'mensagem do loading'
		);
		
		#Submetendo um form com input file para um container
		// A função deve receber um parâmetro $post
		$button->onclick = AppCtrl::formUpload(
			'[id do container]',
			'[nome da classe controle]',
			'[nome da função do controle]',
			'[nome do form]',
			'mensagem do loading'
		);
	}
}