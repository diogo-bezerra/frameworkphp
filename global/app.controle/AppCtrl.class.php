<?php
/**
 * Super classe para classes de controle.
 * @method <li>callCtrlGet (Monta o comando JS (Global.js->callCtrlGet) para chamar um Controle via Get Javascript Ajax)
 * <li>callCtrlPost (Monta o comando JS (Global.js->callCtrlPost) para chamar um Controle via Post Javascript Ajax)
 * <li>submitForm (Monta o comando JS (Global.js->submitForm) para submeter um form via Post Javascript Ajax)
 * @author Diogo (d.bezerra@yahoo.com.br)
 */
class AppCtrl{
	function __construct() {
		
	}
	
	/**
	 * Função padrão para a chamada da view
	 * Para enviar com parâmetros deve ser recriada na classe filha (Override)
	 */
	function showView() {
		$nomeView = str_replace ( 'Ctrl', 'Vs', get_class ( $this ) );
		return new $nomeView();
	}

	/**
	 * !!DEPRECATED!!
	 * Monta o comando JS para chamar um Controle via Javascript Ajax
	 * (Global.js->callCtrlGet->ajax->app.controle/lib/ctrl.php)
	 * passando variáveis via get
	 * @param string $target: Elemento que receberá o conteúdo carregado
	 * @param string $controle: Controle que será chamado
	 * @param string $funcao: Função do controle que será chamada
	 * @param array $gets: Variáveis para a função que será chamada (recebe na ordem de declaração)
	 * @param string $msg: Mensagem de carregamento
	 * @param int $h: Altura do target
	 *
	 * @return string: Comando JS para chamar o Controle
	 *
	 * Obs: Os valores do array get representam variáveis JS.
	 * Para enviar uma string deve-se obedecer o formato
	 * "'string'" e se for uma variável JS somente "nome da Var"
	 * Ex: String: array('uf'=>"'PE'")
	 * Ex: Variavel: array('selectValue'=>"this.value")
	 * Ex no onchange de um select: callCtrlGet('target','ctrl','funcao',array('valString'=>"'PE'",'valueSelect'=>'this.value'))
	 */

	static function callCtrlGet($target, $controle, $funcao, $gets = null, $msg = false, $h = false){
		$vars = '';
		if(!is_null($gets) and !empty($gets)){
			$sep = '';
			foreach ($gets as $get=>$valor){
				$vars .= $sep."'&".$get."=',".$valor;
				$sep = ',';
			}
			$vars = "''.concat(".$vars.")";
		}

		$rs = "callCtrlGet('$target','$controle','$funcao',$vars);";
		if($msg or is_string($msg)){
			$rs = "callCtrlGet('$target','$controle','$funcao',$vars,'$msg');";
			if($h){
				$rs = "callCtrlGet('$target','$controle','$funcao',$vars,'$msg', '$h');";
			}
		}
		return $rs;
	}

	/**
	 * Monta o comando JS para chamar um Controle via Javascript Ajax
	 * (Global.js->callCtrlPost->ajax->app.controle/lib/ctrl.php)
	 * passando variáveis via post
	 * @param string $target: Elemento que receberá a resposta
	 * @param string $controle: Controle que será chamado
	 * @param string $funcao: Função do controle que será chamada
	 * @param array $gets: Variáveis para a função que será chamada (recebe na ordem de declaração)
	 * @param string $msg: Mensagem de carregamento
	 * @param int $h: Altura do target
	 *
	 * @return string: Comando JS para chamar o Controle
	 *
	 ** Obs: Os valores do array get representam variáveis JS.
	 * Para enviar uma string deve-se obedecer o formato
	 * "'string'" e se for uma variável JS somente "nome da Var"
	 * Ex: String: array('uf'=>"'PE'")
	 * Ex: Variavel: array('selectValue'=>"this.value")
	 * Ex no onchange de um select: callCtrlPost('target','ctrl','funcao',array('valString'=>"'PE'",'valueSelect'=>'this.value'))
	 */
	static function callCtrlPost($target, $controle, $funcao, $posts = null, $msg = false, $h = false){
		$vars = '';
		if(!is_null($posts) and !empty($posts)){
			$sep = '';
			foreach ($posts as $post=>$valor){
				
				if(strpos($valor, 'this.') !== false) {
					$vars .= $sep."'&".$post."=',".$valor;
				}elseif(is_string($valor)) {
					$vars .= $sep."'&".$post."=','".$valor."'";
				}else{
					$vars .= $sep."'&".$post."=',".$valor;
				}
				
				$sep = ',';
			}
		}
		$vars = "''.concat(".$vars.")";
		$rs = "callCtrlPost('$target','$controle','$funcao',$vars);";
		if($msg or is_string($msg)){
			$rs = "callCtrlPost('$target','$controle','$funcao',$vars, '$msg');";
			if($h){
				$rs = "callCtrlPost('$target','$controle','$funcao',$vars, '$msg', '$h');";
			}
		}
		return $rs;
	}
	
	static function callCtrlPostConcat($target, $controle, $funcao, $posts = null, $msg = false, $h = false){
		$vars = '';
		if(!is_null($posts) and !empty($posts)){
			$sep = '';
			foreach ($posts as $post=>$valor){
	
				if(strpos($valor, 'this.') !== false) {
					$vars .= $sep."'&".$post."=',".$valor;
				}elseif(is_string($valor)) {
					$vars .= $sep."'&".$post."=','".$valor."'";
				}else{
					$vars .= $sep."'&".$post."=',".$valor;
				}
	
				$sep = ',';
			}
		}
		$vars = "''.concat(".$vars.")";
		$rs = "callCtrlPostConcat('$target','$controle','$funcao',$vars);";
		if($msg or is_string($msg)){
			$rs = "callCtrlPostConcat('$target','$controle','$funcao',$vars, '$msg');";
			if($h){
				$rs = "callCtrlPostConcat('$target','$controle','$funcao',$vars, '$msg', '$h');";
			}
		}
		return $rs;
	}

	/**
	 * Monta o comando JS para submeter um form via Javascript Ajax
	 * (Global.js->submitForm->ajax->app.controle/lib/ctrl.php)
	 * passando variáveis via post
	 * @param string $target: Elemento que receberá o conteúdo carregado
	 * @param string $controle: Controle que será chamado
	 * @param string $funcao: Função do controle que será chamada
	 * @param string $nomeForm: Nome do form que será submetido
	 * @param string $msg: Mensagem de carregamento
	 * @param int $h: Altura do target
	 *
	 * @return string: Comando JS para chamar o Controle
	 *
	 */
	static function submitForm($target, $controle, $funcao, $nomeForm, $msg = false, $h = false) {
		$rs = "submitForm('$target', '$controle', '$funcao', '$nomeForm');";
		if($msg or is_string($msg)){
			$rs = "submitForm('$target', '$controle', '$funcao', '$nomeForm', '$msg');";
			if($h){
				$rs = "submitForm('$target', '$controle', '$funcao', '$nomeForm', '$msg', '$h');";
				if(!$target){
					$rs = "submitForm(false, '$controle', '$funcao', '$nomeForm', '$msg', '$h');";
				}
			}
		}
		return $rs;
	}
	
	static function submitFormToLink($link, $nomeForm, $blankPage = false) {
		$bl = 0;
		if($blankPage) {
			$bl = 1;
		}
		$rs = "submitFormToLink('$link', '$nomeForm', '$bl');";
		return $rs;
	}
	
	static function submitForm2($target, $controle, $funcao, $nomeForm, $msg = false, $h = false) {
		$rs = "submitForm2('$target', '$controle', '$funcao', '$nomeForm');";
		if($msg or is_string($msg)){
			$rs = "submitForm2('$target', '$controle', '$funcao', '$nomeForm', '$msg');";
			if($h){
				$rs = "submitForm2('$target', '$controle', '$funcao', '$nomeForm', '$msg', '$h');";
				if(!$target){
					$rs = "submitForm2(false, '$controle', '$funcao', '$nomeForm', '$msg', '$h');";
				}
			}
		}
		return $rs;
	}
	
	static function formUpload($target, $controle, $funcao, $nomeForm, $msg = false, $h = false) {
		$rs = "formUpload('$target', '$controle', '$funcao', '$nomeForm');";
		if($msg or is_string($msg)){
			$rs = "formUpload('$target', '$controle', '$funcao', '$nomeForm', '$msg');";
			if($h){
				$rs = "formUpload('$target', '$controle', '$funcao', '$nomeForm', '$msg', '$h');";
				if(!$target){
					$rs = "formUpload(false, '$controle', '$funcao', '$nomeForm', '$msg', '$h');";
				}
			}
		}
		return $rs;
	}
}
?>