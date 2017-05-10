<?php
final class Login {
	
	// Os atributos devem ter o mesmo nome da tabela tb_associado
	private $login;
	private $senha;
	private $msg;
	private $session; // session de login.
	private $target; // Url para redirecionamento
	function __construct($login = NULL, $senha = NULL, $chave = NULL, $msg = 'Dados Incorretos.', $target = false) {
		@session_start ();
		if (is_null ( $login ) or empty ( $login )) {
			$msg = '<br />Informe seu Login.<br />';
		}
		if (is_null ( $senha ) or empty ( $senha )) {
			$msg .= 'Informe sua Senha.<br />';
		}
		
		$this->login = trim ( $login );
		$this->senha = trim ( $senha );
		$this->msg = $msg;
		$this->target = $target;
		self::getLogin ( $chave );
	}
	
	// Retorna um atributo
	function get($nome_atributo) {
		return $this->$nome_atributo;
	}
	
	// Define um atributo
	function set($nome_atributo, $valor) {
		$this->$nome_atributo = $valor;
	}
	
	/**
	 * Formata o cpf (login)
	 * @param unknown $login
	 * @return unknown
	 */
function setCpf($cpf) {
		$cpf = substr_replace($cpf, '.', 3, 0);
		$cpf = substr_replace($cpf, '.', 7, 0);
		$cpf = substr_replace($cpf, '-', 11, 0);
		return $cpf;
	}
	
	/**
	 * Criptografa a senha para comparação no banco (somente ida)
	 * Não mudar após sistema em produção.
	 */
	public static function criptSenha($senha) {
		// String qualquer. Não mudar.
		$CRIPTSTRING = 'dsgw5756hws';
		$cod = sha1 ( $senha . $CRIPTSTRING );
		// O código é composto de 15 caracteres derivados da string do sha1
		$cod = $cod [3] . $cod [15] . $cod [8] . $cod [2] . strtoupper ( $cod [21] ) . $cod [7] . strtoupper ( $cod [17] ) . $cod [32] . strtoupper ( $cod [19] ) . $cod [31];
		return $cod;
	}
	
	// Faz o login com dados do banco
	function getLogin($chave = NULL) {
		@session_destroy ();
		@session_start ();
		$_SESSION = array ();
		if (! is_null ( $chave )) {
			switch ($chave) {
				// Login para a Área do associado
				case 'ass' :
					$_SESSION = array (); // Zera todas as sessões
					$ass = new Associado ();
					$rs = $ass->getObj ( array (
						//'cpf' => $this->setCpf($this->login),
						'cpf' => $this->login,
						//'senha' => self::criptSenha ( $this->senha ) 
						'senha' => $this->senha,
						'arq_morto' => 'N'
					) );
					if ($rs) {
						$_SESSION ['chave'] = $chave;
						$_SESSION ['id'] = $ass->get ( 'id' );
						$_SESSION ['cpf'] = $ass->get ( 'cpf' );
						$_SESSION ['nome'] = $ass->get ( 'nome' );
						$_SESSION ['email1'] = $ass->get ( 'email1' );
						$this->ass = $ass;
						
						if(isset($this->target)){
							$imgLoad = new Imagem ('', 'load', Glb::$CONFIG['URL'].'/global/app.visual/imagens/loading.gif');
							$imgLoad->show();
							// Usa JS para redirecionar
							$scrpt = new ScriptJs(false, "redirect('$this->target')");
							$scrpt->show();
							//header('location:'.$this->target);
						}else{
							
						}
						return TRUE;
					} else {
						self::fechar ();
						echo $this->setCpf($this->login);
						echo $this->msg;
					}
					break;
					// Login para a Área de administração
					case 'adm' :
						$_SESSION = array (); // Zera todas as sessões
						$func = new LoginAdm();
						$rs = $func->getObj ( array (
							'login' => $this->login,
							'senha' => $this->senha
						) );
						if ($rs) {
							$_SESSION ['chave'] = $chave;
							$_SESSION ['id'] = $func->get ( 'id' );
							$_SESSION ['cpf'] = $func->get ( 'cpf' );
							$_SESSION ['nome'] = $func->get ( 'nome' );
							$_SESSION ['email'] = $func->get ( 'email' );
							$_SESSION ['permissao'] = $func->get ( 'permissao' );
							$_SESSION ['permissaoHosp'] = $func->get ( 'permissaoHosp' );
							$this->func = $func;
					
							if(isset($this->target)) {
								$imgLoad = new Imagem ('', 'load', Glb::$CONFIG['URL'].'/global/app.visual/imagens/loading.gif');
								$imgLoad->show();
								// Usa JS para redirecionar
								$scrpt = new ScriptJs(false, "redirect('$this->target')");
								$scrpt->show();
								// header('location:'.$this->target);
							} else {
									
							}
							return TRUE;
						} else {
							self::fechar ();
							echo $this->msg;
						}
						break;
			}
		} else {
			echo ( 'Erro: Falta a chave da Área para o login (Login.class)' );
		}
		return false;
	}
	
	public static function permissao($permissao) {
		if(!isset ( $_SESSION ['permissao'] ) or strpos($_SESSION ['permissao'], $permissao.'||') === false) {
			self::fechar ();
			@session_start ();
			$_SESSION ['targetLogin'] = Glb::$CONFIG['URL'].str_replace ('/'.Glb::$CONFIG['URI'], '', $_SERVER['REQUEST_URI']);
			header('location:'.Glb::$CONFIG['URL'].'/admLogin/lock/permissao');
			//$msg = new Msg($_SESSION ['targetLogin'], 'Você não tem permissão para acessar essa página.', 0);
			//$msg->show();
		}
	}
	
	/**
	 * Todos os arquivos que necessitam de login devem chamar essa função.
	 * @param (chave da área logada)
	 */
	public static function verificaSession($chave, $err = false) {
		@session_start ();
		// Direcionar para a view (url) desejada
		switch ($chave) {
			case 'ass' :
				$view = 'login';
				break;
			case 'adm' :
				$view = 'admLogin';
				break;
		}
		
		if (! isset ( $_SESSION ['chave'] ) or $_SESSION ['chave'] != $chave) {
			if($err) {
				$msg = new Msg('Sessão encerrada', 'Não foi possível acessar a página. <br /><br />O tempo de sessão expirou.
						<br /><br />Realize o login novamente.', 0);
				$msg->show();
			}
			
			// Direciona para a página de login e em seguida para a página que tentou acessar quando realiza o login
			$_SESSION ['targetLogin'] = Glb::$CONFIG['URL'].str_replace ('/'.Glb::$CONFIG['URI'], '', $_SERVER['REQUEST_URI']);
			header('location:'.Glb::$CONFIG['URL'].'/'.$view.'/lock');
			die();
		}
	}
	
	/**
	 * Encerra todas as sessões
	 */
	public static function fechar() {
		@session_start ();
		$_SESSION = array ();
		@session_destroy ();
	}
}

?>
