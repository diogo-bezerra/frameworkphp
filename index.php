<?php
header ( 'Content-Type: text/html; charset=utf-8' );
# Includes Globais
include_once ("framework/config/setGlbVars.php"); // Variáveis Globais
include_once ("global/app.controle/Global.class.php");  // Classe Global

# Instanciando a classe Glb
$global = new Glb ();
# Definindo o local
setlocale(LC_ALL, 'Portuguese_Brazil.1252');
/**
 * Inicia o sistema. Carrega o html, insere o css, scripts e meta tags
 * @author Diogo Bezerra
 *
 */
class main {
	public $header;
	public $footer;
	
	function __construct() {
		$this->getRoute();
		
		// <HTML>#
		$this->html = new DHtml5();
		
		// <HEAD>#
		$this->head = new DHead();
		
		// <META>#
		include_once ("framework/config/setMeta.php");
		
		# Definindo a tag <TITLE>
		
		$title = new DTag( 'TITLE' );
		include ("framework/config/setTitulo.php");
		$title->setIn( $__TITULO );
		
		# tag <BODY>
		$this->body = new DTag ('BODY');
		// Div para carregamento de Global.js->submitFormToLink
		$divcalljs = new DTag('div');
		$divcalljs->id = 'divcalljs_we3746hejrg';
		//$divcalljs->hidden = 'hidden';
		$this->body->setIn($divcalljs);
		//$this->body->atr();
		
		// ====== STYLES/CSS =======
		
		include_once ("framework/config/setCss.php");
		
		$ctrl = new $this->ctrl();
		$ctrlfunction = $this->ctrlfunction;
		$rtctrl = call_user_func_array(array($ctrl, $ctrlfunction), $this->paramsCtrl);
		// Se o retorno for uma view
		if(get_parent_class($rtctrl) == "AppView") {
			$view = $rtctrl;
			// ### Inserindo o header (Cabeçalho) se houver 
			$vsheader = $view->getHeader();
			if($vsheader) {
				$vsheader = new $vsheader();
				$this->body->setIn($vsheader->showStr());
				if($vsheader->getCss()) {
					$this->head->setLinksCss($vsheader->getCss());
				}
				// ### Carrega o script js do header
				if($vsheader->getJs()){
					$scriptview = new DScriptJs(Glb::$CONFIG['URL'].'/'.$vsheader->getJs(), true);
					$l = new DLink('', 'link');
					$this->head->setIn($scriptview);
				}
			}
			
			// Verifica se a classe é bloqueada para ser chamada via url
			$blk = $view->getBlockVsUrl();
			if($blk) {
				$this->vs = 'Vs404';
				$view = new Vs404($this->paramsvs);
			}
			
			$this->body->setIn($view->showStr());
			//$this->body->setIn($view->changeUrl());
			
			if($view->getCss()) {
				$this->head->setLinksCss($view->getCss());
			}
			
			if($view->getJs()){
				$scriptview = new DScriptJs(Glb::$CONFIG['URL'].'/'.$view->getJs(), true);
				$l = new DLink('', 'link');
				$this->head->setIn($scriptview);
			}
			
			$viewJs = $view->callFuncJs();
			if($viewJs) {
				$this->head->setOnload ( implode(';',$viewJs) );
			}
			
			$vsfooter = $view->getFooter();
			if($vsfooter) {
				$vsfooter = new $vsfooter();
				$this->body->setIn($vsfooter->showStr());
				if($vsfooter->getCss()) {
					$this->head->setLinksCss($vsfooter->getCss());
				}
			}
		}
		
		// ### Insere todos os scripts e links definidos
		$this->head->loadhead (); 
		
		// ### Chamando a função setScripts para carregar os scripts js no head
		$setScripts = new DScriptJs("setScripts();");
		$this->head->setIn( $setScripts );
		
		// ### Montagem ###
		$this->head->setIn( $title); // Adiciona o Elemento titulo ao Elemento head
		foreach ($metatag as $meta) {
			$this->head->setIn( $meta ); // Adiciona as tags meta a tag head
		}
		$this->html->setIn( $this->head ); // Adiciona o Elemento head ao Elemento html
		$this->html->setIn( $this->body); // Adiciona o Elemento body ao Elemento html
	}
	
	/**
	 * Chama um arquivo de controle de acordo com a rota
	 */
	function getRoute() {
		$this->header = 'VsHeader';
		$this->footer = 'VsFooter';
		$this->vs = 'VsHome';
		$this->paramsvs = false;
		
		include_once ("framework/config/setRoutes.php");
		
		$url = str_replace (' ', '', (str_replace ('/'.Glb::$CONFIG['URI'].'/', '', $_SERVER['REQUEST_URI'])));
		// Conserto online
		if(substr($url, 0, 1) == "/") {
			$url = substr($url, 1);
		}
		
		$arr = explode('/', $url);
		$cont = count($arr);
		$this->paramsCtrl = array();
		for ($i = 0; $i < $cont; $i++) {
			$rota = implode("/", $arr);
			if(isset($route[$rota])) {
				$ctrl=$route[$rota];
			} else {
				$param = array_pop($arr);
				$this->paramsCtrl[] = $param;
			}
		}
	
		@$this->ctrl = substr($ctrl, 0, strpos($ctrl, '['));
		@$this->ctrlfunction = substr($ctrl, strpos($ctrl, '[')+1, (strpos($ctrl, '(')-strpos($ctrl, '[')-1));
		@$ctrlFunctionParamsCount = count(array_filter(explode(',', substr($ctrl, strpos($ctrl, '(')+1, -2))));
		$this->paramsCtrl = array_reverse($this->paramsCtrl);
		// Comparando quantidade de argumentos da função do controle a ser chamada com os argumentos passados na url
		if($ctrlFunctionParamsCount != count($this->paramsCtrl)) {
			$err = new Msg();
			$err->dieProcess('', 'Desculpe-nos. Não foi possível carregar a página que procura.<br /><br />
					<a onclick="javascript:window.history.go(-1);" href="#">Voltar</a>');
		}
	}
	
	/**
	 * Mostra o html do index
	 */
	function show() {
		$this->html->show();
	}
	
	/**
	 * Retorna um obj html do html carregado ($this->html)
	 * @param (String) $id
	 */
	function gethtml($id){
		return $this->htmlFile->find("[id=$id]",0);
	}
}
$main = new main ();
$main->show ();