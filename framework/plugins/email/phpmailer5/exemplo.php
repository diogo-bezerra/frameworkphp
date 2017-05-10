<?php

// Inicia a classe PHPMailer
$mail = new PHPMailer();
 
// Define os dados do servidor e tipo de conexão
$mail->SMTPDebug = '0';
$mail->IsSMTP(); // Define que a mensagem será SMTP
$mail->Host = "smtp.amepe.com.br"; // Endereço do servidor SMTP (caso queira utilizar a autenticação, utilize o host smtp.seudomínio.com.br)
$mail->SMTPAuth = true; // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
$mail->SMTPSecure = 'tls';
$mail->Port = 587;
$mail->Username = 'maladireta@amepe.com.br'; // Usuário do servidor SMTP (endereço de email)
$mail->Password = 'malaamepe2009'; // Senha do servidor SMTP (senha do email usado)
 
// Define o remetente
$mail->From = $this->remetente; // Seu e-mail
$mail->Sender = $this->remetente; // Seu e-mail
//$mail->From = 'maladireta@amepe.com.br'; // Seu e-mail
//$mail->Sender = 'maladireta@amepe.com.br'; // Seu e-mail
$mail->FromName = $this->nome_remt; // Seu nome
 
// Define os destinatário(s)
$mail->AddAddress($this->destino);
//$mail->AddAddress('informatica2@amepe.com.br');
//$mail->AddCC('ciclano@site.net', 'Ciclano'); // Copia
//$mail->AddBCC('fulano@dominio.com.br', 'Fulano da Silva'); // Cópia Oculta
 
// Define os dados técnicos da Mensagem
$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
//$mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
 
// Define a mensagem (Texto e Assunto)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->Subject  = $this->assunto; // Assunto da mensagem
$mail->Body = $this->texto_html;
$mail->AltBody = $this->texto_html;
 
 
// Define os anexos (opcional)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
 
if(isset($this->anexo) and !empty($this->anexo)) {
	/*
	 $pos = strrpos($this->anexo, "/");
	 if ($pos === false) { // note: três sinais iguais
	 $pos = 0;
	 }
	 $nomeArq = substr($pos, $this->anexo);
	 $mail->AddAttachment($this->anexo, $nomeArq);  // Insere um anexo
	 */
	$mail->addStringAttachment(file_get_contents($this->anexo['tmp_name']), $this->anexo["name"]);
}
// Envia o e-mail
if(!empty($this->destino) and isset($this->destino)) {
	$enviado = $mail->Send();
	//echo 'enviou '.$this->destino.'<br /><br />';
	 
	// Limpa os destinatários e os anexos
	$mail->ClearAllRecipients();
	$mail->ClearAttachments();

	// Exibe uma mensagem de resultado
	if ($enviado) {
		echo "E-mail enviado com sucesso para ".$this->destino.'<br /><br />';
	} else {
		//echo "Não foi possível enviar o e-mail.";
		//echo "Informações do erro: " . $mail->ErrorInfo;
		//die($this->destino);
	}
}