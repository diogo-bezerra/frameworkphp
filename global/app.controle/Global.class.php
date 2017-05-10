<?php

date_default_timezone_set('America/Recife');

/**
 * Gera arqivos de log de erros do php
 * @author Diogo (d.bezerra@yahoo.com.br)
 *
 */
function error_handler($level = null, $message = null, $arq = null, $line = null, $context = null) {
	// Caso o erro esteja suprimido com @ não escreve o erro (sai da função)
	if (error_reporting() === 0) {
		return;
	}
	// Apaga o arquivo criado pelo php.ini
	$file_delete = Glb::$CONFIG['RAIZGLB'] . '/framework/errlog/phpinierrors.log';
	if (is_file($file_delete)) {
		chmod($file_delete, 0666);
		unlink($file_delete);
	}

	/* Valor de $level
	 2 E_WARNING Non-fatal run-time errors. Execution of the script is not halted
	8 E_NOTICE Run-time notices. The script found something that might be an error, but could also happen when running a script normally
	256 E_USER_ERROR Fatal user-generated error. This is like an E_ERROR set by the programmer using the PHP function trigger_error()
	512 E_USER_WARNING Non-fatal user-generated warning. This is like an E_WARNING set by the programmer using the PHP function trigger_error()
	1024 E_USER_NOTICE User-generated notice. This is like an E_NOTICE set by the programmer using the PHP function trigger_error()
	4096 E_RECOVERABLE_ERROR
	*/

	$tipoErr = '[Tipo de erro Desconhecido] ';
	switch ($level) {
		case 2:
			$tipoErr = '[E_WARNING] ';
			break;
		case 8:
			$tipoErr = '[E_NOTICE] ';
			break;
		case 256:
			$tipoErr = '[E_USER_ERROR] ';
			break;
		case 512:
			$tipoErr = '[E_USER_WARNING] ';
			break;
		case 1024:
			$tipoErr = '[E_USER_NOTICE] ';
			break;
	}

	// Definindo o arquivo de log
	$file = Glb::$CONFIG['RAIZGLB'] . '/framework/errlog/php-error.log';

	// Definindo a data e a hora do erro
	$data = new DateTime();
	$dataInicio = $data->format('d/m/Y');
	$horaInicio = $data->format('H:i:s');

	// Se o arquivo de log for maior que 10kb troca o arquivo de log
	@$fsize = filesize($file);
	if ($fsize > 10000) {
		rename($file, $file . (string) time() . '.log');
		clearstatcache();
	}

	// Criando o id do erro
	$idErro = preg_replace("/[^0-9]/", "", sha1($_SERVER['REMOTE_ADDR'] . $dataInicio . $horaInicio));
	$idErro = substr($idErro, 0, 8);

	// Definindo link de redirecionamento
	$link = new Link('', Glb::$CONFIG['URL'], 'blank', 'Ir para a home');
	$linkHome = $link->showStr();
	// Definindo as mensagens
	$msg = $tipoErr . 'Erro no arquivo: ' . $arq . ' na linha ' . $line . '<br />' . $message . '<br /><br />';
	$msgLog = 'Erro: ' . $idErro . ' em ' . $dataInicio . ' - ' . $horaInicio . "\r\n" . $tipoErr . 'Arquivo: ' . $arq . ' na linha ' . $line . "\r\n" . $message . "\r\n\r\n";

	// Verificando se o erro é fatal (level 1)
	$error = error_get_last();
	if (($error['type'] === E_ERROR) || ($error['type'] === E_USER_ERROR)) {
		$msg = '[Erro Fatal] Erro no arquivo: ' . $error['file'] . ' na linha ' . $error['line'] . '<br />' . $error['message'] . '<br /><br />';
		$msgLog = 'Erro: ' . $idErro . ' em ' . $dataInicio . ' - ' . $horaInicio . "\r\n" . '[ERRO FATAL] Arquivo: ' . $error['file'] . ' na linha ' . $error['line'] . "\r\n" . $error['message'] . "\r\n\r\n";
		$level = 1;
	}

	if (isset($level)) {
		// Inserindo o erro no arquivo de log
		error_log($msgLog, 3, $file);

		// Exibindo mensagem
		// Ambiente de desenvolvimento (localhost)
		if ($_SERVER['REMOTE_ADDR'] == "127.0.0.1" or $_SERVER['REMOTE_ADDR'] == "::1") {
			$err = new Msg();
			$err->dieProcess('', $msg);
			// Ambiente de produção
		} else {
			$err = new Msg();
			$err->dieProcess('', '<font class=\'fonte01_4_azul\'>Desculpe-nos. Ocorreu um erro inesperado.<br />Por favor entre em contato com suporte@achequemfaz.com.br informando o n�mero de protocolo: <b>' . $idErro . '</b></font><br /><br />' . $linkHome);
		}
	}
}

/**
 * Classe com funções e variáveis globais
 * @author Diogo (d.bezerra@yahoo.com.br)
 *
 */
class Glb {
	static public $NOME1;
	static public $NOME2;
	static public $URLGLB;
	static public $URI;
	static public $RAIZGLB;
	static public $DOMINIOGLB;
	static public $IDCONTAINER;
	static public $ALIGNBODY;
	static public $MSGLOAD;
	static public $HVIEW;
	static public $DESCSITE;
	static public $KEYWORDS;
	static public $CHARSET;
	static public $CONFIG = array();
	static public $DIRHML = array();
	static public $CLASSES = array();
	
	
	function __construct($id = NULL) {
		# Em config setGlbVars
		foreach (CONFIGGLOBAL::$auxGlobal as $key => $valor) {
			self::$CONFIG[$key] = $valor;
		}
		
		$this->buscaClasses(Glb::$CONFIG['RAIZGLB']);
		//print_r(Glb::$CLASSES);
  		include_once(Glb::$CONFIG['RAIZGLB'] . "/framework/lib/_autoLoadClass.php");
		include_once(Glb::$CONFIG['RAIZGLB'] . "/global/app.modelo/bd/Conexao.class.php");
		include_once(Glb::$CONFIG['RAIZGLB'] . "/framework/lib/html/simple_html_dom.php");
		
		// Usa a função error_handler como manipulador de erro
		//set_error_handler('error_handler');
		//register_shutdown_function('error_handler');
		include_once Glb::$CONFIG['RAIZGLB'] . '/framework/config/setManutencao.php';
		
		//session_write_close();
		@session_start();
		
		$x = '';
		
		if (@$_GET[$LINKBLOCKGET]) {
			$x = base64_decode($_GET[$LINKBLOCKGET]);
		}
		# Usa um input para digitar uma senha de acesso quando o site estiver em manutenção.
		$flag = @$_POST['input_acesso'] . $x;
		if (isset($flag) and !empty($flag)) {
			@$_SESSION['acesso'] = $flag;
		}
		if (isset($_SESSION['acesso'])) {
			if ($_SESSION['acesso'] != $SENHABLOCK) {
				$this->getBlock(); // Verifica o arquivo de manutenção para bloquear o site de acordo com a necessidade (ver config/setManutencao.php)
			}
		} else {
			$this->getBlock(); // Verifica o arquivo de manutenção para bloquear o site de acordo com a necessidade (ver config/setManutencao.php)
		}
	}
	
	/**
	 * Busca os diretórios das classes para serem utilizados em autoload
	 * @param unknown $dir
	 * @return boolean
	 */
	function buscaClasses($dir) {
		try {
			$d = new DirectoryIterator($dir);
			$diretorios = null;
			$rs = false;
			foreach ($d as $item) {
				if ($item->isDot()) {
					continue;
				}
				if($item->isFile()) {
					if(strpos($item->getFilename(), '.class.php') !== false) {
						$nomeClasse = str_replace('.class.php', '', $item);
						Glb::$CLASSES[$nomeClasse] = str_replace(Glb::$CONFIG['RAIZGLB'], '', $item->getPath()).'/';
						//echo $item->getPath().'/'.$item.'<br />';
					}
				} else {
					if($item != 'html' and 
					$item != 'css' and 
					$item != 'js' and 
					$item != 'docs' and 
					$item != 'framework' and 
					$item != 'imagens') {
						$diretorios[] = $item->getPath().'/'.$item;
						//echo 'entrou em '.$item->getPath().'/'.$item.'<br />';
					}
				}
			}
		
			if(!is_null($diretorios)) {
				foreach ($diretorios as $dir) {
					$this->buscaClasses($dir);
				}
			}else{
				return $rs;
			}
		} catch (UnexpectedValueException $e) {
			echo 'Erro: '.$e->getMessage().'<br />';
		}
	}

	function getBlock() {
		include self::$CONFIG['RAIZGLB'] . '/framework/config/setManutencao.php';
		switch ($BLOCK) {
			case 'ok':
				$mant = new $VIEWMANUTENCAO();
				$mant->show();
				die();
				break;
			case 'ip':
				if ($_SERVER['REMOTE_ADDR'] != $BLOCKIP) {
					$mant = new $VIEWMANUTENCAO();
					$mant->show();
					die();
				}
				break;
		}
	}

	public static function cript($string) {
		include self::$CONFIG['RAIZGLB'] . '/framework/config/setCript.php';
		$str = base64_encode($CRIPTSTRING1.$string);
		$str = str_pad($str, strlen($str) + strlen($CRIPTSTRING2), $CRIPTSTRING2);

		for ($i = 0; $i <= 3; $i++) {
			$str = str_pad($str, strlen($str) + 3, $str[1] . $str[3] . $str[5]);
			$str = strrev(base64_encode($str));
		}
		return $str;
	}

	public static function decript($str) {
		include self::$CONFIG['RAIZGLB'] . '/framework/config/setCript.php';
		for ($i = 0; $i <= 3; $i++) {
			$str = base64_decode(strrev($str));
			$str = substr($str, 0, -3);
		}
		$str = substr(base64_decode(substr($str, 0, -strlen($CRIPTSTRING2))), strlen($CRIPTSTRING1));
		return $str;
	}

	// Insere espaços em branco
	static public function espaco($qtd) {
		$esp = '';
		for ($i = 1; $i <= $qtd; $i++) {
			$esp = $esp . '&nbsp;';
		}
		return $esp;
	}

	public static function detect_browser() {
		$useragent = $_SERVER['HTTP_USER_AGENT'];

		if (preg_match('|MSIE ([0-9].[0-9]{1,2})|', $useragent, $matched)) {
			$browser_version = $matched[1];
			$browser = 'IE';
		} elseif (preg_match('|Opera/([0-9].[0-9]{1,2})|', $useragent, $matched)) {
			$browser_version = $matched[1];
			$browser = 'Opera';
		} elseif (preg_match('|Firefox/([0-9\.]+)|', $useragent, $matched)) {
			$browser_version = $matched[1];
			$browser = 'Firefox';
		} elseif (preg_match('|Chrome/([0-9\.]+)|', $useragent, $matched)) {
			$browser_version = $matched[1];
			$browser = 'Chrome';
		} elseif (preg_match('|Safari/([0-9\.]+)|', $useragent, $matched)) {
			$browser_version = $matched[1];
			$browser = 'Safari';
		} else {
			// browser not recognized!
			$browser_version = 0;
			$browser = 'outro';
		}
		return $browser;
	}

	/** 
	 * Acessa e retorna as variáveis de um arquivo
	 * @param string diretório do arquivo
	 * @return array com as variaveis do arquivo
	 */
	static public function getArquivo($arquivo) {
		if (file_exists($arquivo)) {
			// Lê um arquivo e retorna o valor das variáveis em um array.
			$arr = parse_ini_file($arquivo);
		} else {
			// Erro se o arquivo não existir
			$err = new Msg();
			$err->dieProcess('Arquivo de configuração ' . $arquivo . ' não encontrado.');
		}
		return $arr;
	}
	
	/**
	 * Retira acentos de uma string
	 * @param unknown $str
	 */
	static function retiraAcentos($string) {
		//$string = 'ÁÍÓÚÉÄÏÖÜËÀÌÒÙÈÃÕÂÎÔÛÊáíóúéäïöüëàìòùèãõâîôûêÇç';
		//return preg_replace( '/[`^~\'"]/', null, iconv( 'UTF-8', 'ASCII//TRANSLIT', $str ) );
		$tr = strtr(
			$string,
			array (
				'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A',
				'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E',
				'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ð' => 'D', 'Ñ' => 'N',
				'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O',
				'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Ŕ' => 'R',
				'Þ' => 's', 'ß' => 'B', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a',
				'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
				'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
				'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
				'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y',
				'þ' => 'b', 'ÿ' => 'y', 'ŕ' => 'r'
			)
		);
		return $tr;
	}
}