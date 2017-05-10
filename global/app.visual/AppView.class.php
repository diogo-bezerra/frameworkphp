<?php
/**
 * Superclasse para todas as classes view (Vs)
 * @param string $dirHtml (Diretório do html a ser carregado)
 * @param boolean $controle (Se o view deve instanciar sua classe controle)
 * @property $ctrl (Classe de controle da view)
 * @property $htmlFile (html da view)
 * @property $trackGoogle (Indica se a view deve ser rastreada pelo google analytics
 * @return boolean
 * @author Diogo (d.bezerra@yahoo.com.br)
 */
class AppView {
	public $ctrl;
	public $css;
	public $js;
	public $htmlFile;
	public $trackGoogle = false;
	public $scripts;
	public $beforeScripts;
	public $blockVsUrl;
	public $header;
	public $footer;
	
	function __construct($controle = true, $dir , $css = true, $js = false, $header = false, $footer = false) {
		@header ( 'Content-Type: text/html; charset=utf-8' );
		
		if ($controle) {
			// ### Controle ####
			$nomeCtrl = str_replace ( "Vs", 'Ctrl', get_class ( $this ) );
			$this->ctrl = new $nomeCtrl ();
		}
		
		$this->blockVsUrl = false;
		$this->dirHtml = false;
		$this->css = false;
		$this->js = false;
		$this->header = $header;
		$this->footer = $footer;
		
		$nomeArquivo = lcfirst(str_replace('Vs', '', get_class($this)));
		$this->dirHtml = Glb::$CONFIG['RAIZGLB'].'/'.$dir.'/app.visual/html/'.$nomeArquivo.'.html';
		if($css) {
			$this->css = $dir.'/app.visual/css/'.$nomeArquivo.'.css';
		}
		if($js) {
			$this->js = $dir.'/app.controle/js/'.$nomeArquivo.'.js';
		}
		
		// #### Carrega o html ####
		$this->htmlFile = file_get_html ( $this->dirHtml );
	}
	
	function changeUrl() {
		// Obtendo o nome do diretório sem o diretório raiz
		$url = strtolower(str_replace ('/'.Glb::$CONFIG['NOME1'].'/', '',$_SERVER['REQUEST_URI']));
		// Muda a URL
		$sc = new DScriptJs("var stateObject = {};var title = 'Title';var newUrl = '/".Glb::$CONFIG['URI'].'/'.$url."';history.pushState(stateObject,title,newUrl);");
		return $sc->toStr();
	}
	
	function getCss() {
		return $this->css;
	}
	
	function getJs() {
		return $this->js;
	}
	
	function getHeader() {
		return $this->header;
	}
	
	function getFooter() {
		return $this->footer;
	}
	
	function getHtmlTag($id) {
		// echo $id;
		return $this->htmlFile->find("[id=$id]",0);
	}
	
	function getHtmlTagByAtt($tag, $att, $value, $posicao) {
		// echo $id;
		return $this->htmlFile->find($tag."[$att=$value]", $posicao);
	}
	
	function show($utf = '') {
		// Busca o html
		$vs = $this->htmlFile->show ();
		// Busca o css
		if($this->css) {
			$css = file_get_html ( Glb::$CONFIG['RAIZGLB'] . '/' . $this->css );
			if($css) {
				$vs .= '<style>'.$css->show().'</style>';
				$css->clear();
			}
		}
		
		if($this->js) {
			$js = file_get_html ( Glb::$CONFIG['RAIZGLB'] . '/' . $this->js );
			if($js) {
				$vs .= "<script language='javascript' type='text/javascript'>setScript('".Glb::$CONFIG['URL'] . '/' . $this->js."');</script>";
				$js->clear();
			}
		}
		
		if(is_array($this->scripts)) {
			$vs .= "<script language='javascript' type='text/javascript'>".implode(';',$this->scripts)."</script>";
		}
		
		if (! $vs) {
			return false;
		}
		// Verificando se é para codificar em UTF8
		if ($utf == 'utf') {
			echo utf8_encode ( $vs );
		} else {
			echo ($vs);
		}
		
		if ($this->trackGoogle) {
			// Insere o script de rastreamento do google anaytics
			$sc = new ScriptJs ( false, 'trackGoogleAnalytics("' . get_class ( $this ) . '")' );
			$sc->show ();
		}
		
		// Libera a memória do carregamento do html
		$this->htmlFile->clear ();
		return true;
	}
	
	function showStr($utf = '') {
		// Carrega as funções js se tiverem sido chamadas na view
		$scrs = "";
		if ($this->trackGoogle) {
			// Insere o script de rastreamento do google anaytics
			$scrs = '<script language="javascript" type="text/javascript">trackGoogleAnalytics("' . get_class ( $this ) . '")'.'</script>';
		}
		if(is_array($this->beforeScripts)) {
			$scrs .= '<script language="javascript" type="text/javascript">'.implode(';',$this->beforeScripts).'</script>';
		}
		
		// Retorna o html em formato string
		$vs = $scrs.$this->htmlFile->show ();
		if (! $vs) {
			return false;
		}
		// Verificando se é para codificar em UTF8
		if ($utf == 'utf') {
			$vs = utf8_encode ( $vs );
		}
	
		// Libera a memória do carregamento do html
		$this->htmlFile->clear ();
		return $vs;
	}
	
	static function showVs($target, $view, $posts, $msg = false, $h = false) {
		if (is_null ( $posts )) {
			$posts = array ();
		}
		//$posts = array('id'=>'1', 'nome' => 'Diogo');
		$vars = "''.concat(";
		$sep = '';
		if (is_array ( $posts )) {
			foreach ( $posts as $post => $valor ) {
				if(strpos($valor, 'this.') !== false) {
					$vars .= $sep."'&" . $post . "='," . $valor;
				} elseif(is_string($valor)) {
					$vars .= $sep."'&" . $post . "=','" . $valor . "'";
				} else {
					$vars .= $sep."'&" . $post . "='," . $valor;
				}
				$sep = ',';
			}
		}
		$vars .= ')';
		$rs = "showVs('$target','$view',$vars)";
		if ($msg or is_string ( $msg )) {
			$rs = "showVs('$target','$view',$vars,'$msg');";
			if ($h) {
				$rs = "showVs('$target','$view',$vars,'$msg', '$h');";
			}
		}
		return $rs;
	}
	
	/**
	 * Executa um comando ou função js
	 * @param comando ou função $jsfunc
	 * @param number $pos: indica se executa antes ou depois do load
	 */
	function callLoadJs($jsfunc, $pos = 0){
		if ($pos == 0) {
			$this->scripts[] = $jsfunc;
		}elseif ($pos == 1) {
			$this->beforeScripts[] = $jsfunc;
		}
	}
	
	function callFuncJs(){
		if(is_array($this->scripts)){
			return $this->scripts;
		}
		return false;
	}
	
	function blockVsUrl() {
		$this->blockVsUrl = true;
		return true;
	}
	
	function getBlockVsUrl() {
		return $this->blockVsUrl;
	}
}