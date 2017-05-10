<?php
/**
 * Classe de controle
 * Possui todas as funções de controle de dados da view correspondente.
 * Herda de AppCrl
 */
class CtrlFaleConosco extends AppCtrl {
	function __construct() {
		parent::__construct ();
	}
	
	function enviaContato($post = null) {
		$msgerro = null;
		$erro = new Msg ();
		@$input_nome = $post ['input_nome'] or $msgerro = 'Não foi possível enviar seu contato. Você deve informar seu nome.';
		@$input_email = $post ['input_email'] or $msgerro = 'Não foi possível enviar seu contato. Você deve informar seu email.';
		@$input_assunto = $post ['input_assunto'] or $msgerro = 'Não foi possível enviar seu contato. Você deve informar o assunto.';
		@$textArea_mensagem = $post ['textArea_mensagem'] or $msgerro = 'Não foi possível enviar seu contato. Você não digitou o texto da mensagem.';
		
		# Se houver erro retorna para a Vs reenviando os dados já digitados
		if (! is_null ( $msgerro )) {
			$post["msgerro"] = $msgerro;
			$vscontato = new VsFaleConosco( $post );
			$vscontato->show ();
		}
		$remetente = $post['input_email'];
		
		$destino = $post['select_destino'];
		
		# Gera número único para ser usado como protocolo de atendimento
		$protocolo = sha1 ( $destino . rand ( 10, 100 ) );
		$protocolo = substr ( preg_replace ( "/[^0-9]/i", "", $protocolo ), 0, 8 );
		
		$texto = 'Enviado através do site (www.amepe.com.br/site/fale-conosco)<br /><br />'.$textArea_mensagem;
		# Plugin de email
		$email = new Email ( $input_nome, $remetente, $destino, ($input_assunto . ' (' . $protocolo . ')'), $texto, '', 'normal' );
		$email->enviar();
		
		$contato = new Contato();
		$contato->set('nome', $input_nome);
		$contato->set('email', $input_email);
		$contato->set('assunto', $input_assunto);
		$contato->set('msg', $texto);
		$contato->set('ip', $_SERVER['REMOTE_ADDR']);
		$contato->set('data', Tdata::TdataHojeMysql());
		$contato->set('hora', Tdata::hora());
		$contato->set('protocolo', $protocolo);
		
		$contato->insert();
		
		$msg = new Msg ("Mensagem enviada com sucesso!!", "Obrigado por entrar em contato. <br />Em breve lhe responderemos.<br /><br />", 1);
		$msg->show();
	}
}
?>
