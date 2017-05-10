<?php

# Usa Global.class.php
include Glb::$CONFIG['RAIZGLB'].'/framework/plugins/email/setEmail.php';
/**
 * Envia emails através de configurações do servidor Amazon SES, Elastic Email ou Locaweb
 * @author Diogo (d.bezerra@yahoo.com.br)
 *
 * @param string nome do remetente
 * @param string email do remetente
 * @param string email de destino
 * @param string assunto do email
 * @param string corpo da mensagem (html)
 * @param string auxiliar para enviar variáveis ao corpo do email
 * @param string caminho para o arquivo que será anexado ao email
 * @param string tipo (ver classe emailEE.class.php)
 * @param string servidor que será utilizado
 * (AWS ou Elastic Email - Definido em setApiEmail o na chamada da instância)
 *
 * Ex: new Email('remetente', 'email@remetente.com', 'email1@destino.com;email2@destino.com;email3@destino.com', 
 * 'assunto', 'NOME DO ARQUIVO DE EMAIL A SER ENVIADO (NA PASTA EMAILS)', aux)
 * 
 * Ver as Classes EmailAWS, EmailEE e EmailLW para saber como utilizar cada tipo de servidor.
 * Ver o arquivo setEmail para definir as configurações de envio.
 * As classes Email, EmailAWS, EmailEE e EmailLW devem ser inseridas em config/setPlugins.php
 */
class Email {
	function __construct($nome_remt = null, $remetente, $destino, $assunto, $texto, $auxEmail = '', $anexo = false, $tipo = 'normal', $server = '') {
		$this->nome_remt = $nome_remt;
		$this->remetente = $remetente;
		$this->destino = $destino;
		$this->assunto = $assunto;
		$this->texto = '';
		$this->texto_html = CONFIG_EMAIL::getCorpo($auxEmail, $texto);
		$this->anexo = $anexo;
		$this->tipo = $tipo;
		$this->server = $server; # Se for definido na instância força o uso de um servidor, independente do arquivo de configuração
	}

	function __destruct() {
		# echo "<br /> <br /> Objeto produto destruído";
	}
	
	public function __call($function, $arguments) {
		if($function == 'enviar') {
			$nome_remt = $this->nome_remt;
			$remetente = $this->remetente;
			$destino = $this->destino;
			$assunto = $this->assunto;
			$texto = $this->texto;
			$texto_html = $this->texto_html;
			$anexo = $this->anexo;
			$tipo = $this->tipo;
			$server = $this->server;
			$email = '';
			if (empty($server)) {
				$server = CONFIG_EMAIL::$SERVERAPIEMAIL;
			}

			if ($server == 'AWS') {
				$email = new EmailAWS($nome_remt, $remetente, $destino, $assunto, $texto, utf8_encode($texto_html));
			} elseif ($server == 'EE') {
				$email = new EmailEE($nome_remt, $remetente, $destino, $assunto, $texto, utf8_encode($texto_html), $tipo);
			} elseif ($server == 'LW') {
				$email = new EmailLW($nome_remt, $remetente, $destino, $assunto, ($texto_html), $anexo);
			} else {
				die('Configuração do servidor inválida em setEmail.');
			}
			if (($_SERVER['REMOTE_ADDR'] != "127.0.0.1" and $_SERVER['REMOTE_ADDR'] != "::1") or CONFIG_EMAIL::$LOCALHOSTAPIEMAIL == true) {
				return $email->$function();
			} else {
				echo '[' . $server . '] Envio local rejeitado (' . $this->destino . ')';
				return FALSE;
			}
		}
	}

	// Retorna um atributo
	function get($nome_atributo) {
		return $this->$nome_atributo;
	}

	// Define um atributo
	function set($nome_atributo, $novo_valor) {
		$this->$nome_atributo = $novo_valor;
	}

	

}

?>
