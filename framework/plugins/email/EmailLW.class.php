<?php
/**
 * @author Diogo (d.bezerra@yahoo.com.br)
 * Classe de email (Locaweb)
 * Anexo deve um array com url do arquivo, nome e Content-Type ou um arquivo via form
 */

class EmailLW {

    private $texto; // Texto do email (Body)
    private $remetente; // Email que envia
    private $destino; // Email que recebe
    private $anexo; 
    private $assunto; // Assunto do Email
    
    function __construct($nome_remt, $remetente, $destino, $assunto, $texto = '', $anexo = false) {
        $this->nome_remt = $nome_remt;
        $this->remetente = $remetente;
        $this->destino = $destino;
        $this->assunto = $assunto;
        $this->texto_html = $texto;
        $this->anexo = $anexo;
    }

    function __destruct() {
        # echo "<br /> <br /> Objeto produto destruído";
    }

    /**
     * Retorna um atributo
     */ 
    function get($nome_atributo) {
        return $this->$nome_atributo;
    }

    /**
     * Define um atributo
     */ 
    function set($nome_atributo, $novo_valor) {
        $this->$nome_atributo = $novo_valor;
    }

    // Utiliza o phpMailer
    function enviar2() {
    	//require_once (Glb::$CONFIG['RAIZGLB']."/framework/plugins/email/phpmailer5.2/class.pop3.php");
    	//require_once (Glb::$CONFIG['RAIZGLB']."/framework/plugins/email/phpmailer5.2/class.smtp.php");
    	require_once (Glb::$CONFIG['RAIZGLB']."/framework/plugins/email/phpmailer5.2/class.phpmailer.php");
    	
    	// Inicia a classe PHPMailer
    	$mail = new PHPMailer(true);
    	
    	// Define os dados do servidor e tipo de conexão
    	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    	$mail->IsSMTP(); // Define que a mensagem será SMTP
    	
    	try {
    		$mail->Host = 'smtp.amepe.com.br'; // Endereço do servidor SMTP (Autenticação, utilize o host smtp.seudomínio.com.br)
    		$mail->SMTPAuth   = true;  // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
    		$mail->Port       = 587; //  Usar 587 porta SMTP
    		$mail->Username = 'maladireta@amepe.com.br'; // Usuário do servidor SMTP (endereço de email)
    		$mail->Password = 'malaamepe2009'; // Senha do servidor SMTP (senha do email usado)
    		$mail->CharSet = 'utf-8';
    		$mail->isSMTP();
    	
    		//Define o remetente
    		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    		$mail->SetFrom($this->remetente, utf8_encode($this->nome_remt)); //Seu e-mail
    		$mail->AddReplyTo($this->remetente, $this->nome_remt); //Seu e-mail
    		$mail->Subject = utf8_encode($this->assunto);//Assunto do e-mail
    	
    		//Define o corpo do email
    		$mail->MsgHTML(utf8_encode($this->texto_html));
    		////Caso queira colocar o conteudo de um arquivo utilize o método abaixo ao invés da mensagem no corpo do e-mail.
    		//$mail->MsgHTML(file_get_contents('arquivo.html'));
    	
    		//Define os destinatário(s)
    		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    		$cont = 1;
    		if(is_array($this->destino)) { 
    			$mail->AddAddress('maladireta@amepe.com.br');
    			foreach ($this->destino as $dest) {
    				//echo $dest.'<br />';
    				if($mail->ValidateAddress($dest)) {
    					$mail->AddBCC($dest); // Cópia Oculta
    					$cont++;
    					if($cont == 50) {
    						$cont == 0;
    						$mail->send();
    						$mail->clearAllRecipients();
    						$mail->AddAddress('maladireta@amepe.com.br');
    					}
    				} else {
    					die('Email inválido: '.$dest);
    				}
    			}
    		} elseif (is_string($this->destino)) {
    			if($mail->ValidateAddress($this->destino)) {
    				$mail->AddAddress($this->destino, '');
    				$mail->Send();
    			} else {
    				die('Email inválido: '.$this->destino);
    			}
    		}
    		
    		if($cont > 0) {
    			$mail->Send();
    		}
    		//Campos abaixo são opcionais
    		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    		//$mail->AddCC('destinarario@dominio.com.br', 'Destinatario'); // Copia
    		//$mail->AddBCC('destinatario_oculto@dominio.com.br', 'Destinatario2`'); // Cópia Oculta
    		//$mail->AddAttachment('images/phpmailer.gif');      // Adicionar um anexo
    	
    		//echo "Mensagem enviada com sucesso</p>\n";
    	
    		//caso apresente algum erro é apresentado abaixo com essa exceção.
    	}catch (phpmailerException $e) {
    		echo $e->errorMessage(); //Mensagem de erro customizada do PHPMailer
    	}
    }
    
    // Função original
    function enviar() {
        if ($_SERVER['REMOTE_ADDR'] != "127.0.0.2" and $_SERVER['REMOTE_ADDR'] != "::2") {
        	if(PHP_OS == "Linux") {
        		$quebra_linha = "\n"; // Se for Linux
        	}
        	elseif(PHP_OS == "WINNT") {
        		$quebra_linha = "\r\n"; // Se for Windows
        	}
        	else {
        		die("Este script não esta preparado para funcionar com o sistema operacional de seu servidor");
        	}
        	
        	// Definições do servidor
        	//ini_set("SMTP","localhost");
        	//ini_set("smtp_port","25");
        	//ini_set('sendmail_from', $this->remetente); 
        	$html = '<html>';
        	$html .= '<body>';
        	$html .= $this->texto_html;
        	$html .= '</body>';
        	$html .= '</html>';
        	$headers = 'MIME-Version: 1.0' . $quebra_linha;
        	$headers .= "Content-type: text/html; charset=iso-8859-1".$quebra_linha;
        	$headers .= 'From: ' . $this->remetente;
        	//print_r($this->anexo);
        	if(isset($this->anexo) and !empty($this->anexo)) {
        		$nomeArq = '';
        		@$arquivo = file_get_contents($this->anexo["tmp_name"]);
        		if($arquivo) {
        			$anexo = base64_encode($arquivo);
        		} else {
        			$fp = fopen($this->anexo["tmp_name"],"rb");
        			$anexo = fread($fp,filesize($this->anexo["tmp_name"]));
        			$anexo = base64_encode($arquivo);
        			fclose($fp);
        			$anexo = chunk_split($anexo);
        		}
        		
        		$mensagem_cabecalho = '';
        			
        		$boundary = 'XYZ-' . date("dmYis") . '-ZYX';
        		$html = '--' . $boundary . $quebra_linha;
        		$html .= 'Content-Transfer-Encoding: 8bits' . $quebra_linha;
        		$html .= 'Content-Type: text/html; charset="ISO-8859-1"' . $quebra_linha . $quebra_linha;
        		$html .= $mensagem_cabecalho . $quebra_linha;
        		$html .= $this->texto_html . $quebra_linha;
        		$html .= '--' . $boundary . $quebra_linha;
        		$html .= 'Content-Type: ' . $this->anexo["type"] . $quebra_linha;
        		$html .= 'Content-Disposition: attachment; filename="' . $this->anexo["name"] . '"' . $quebra_linha;
        		$html .= 'Content-Transfer-Encoding: base64' . $quebra_linha . $quebra_linha;
        		$html .= $anexo . $quebra_linha;
        		$html .= '--' . $boundary . '--' . $quebra_linha;
        				
        		$headers = 'MIME-Version: 1.0' . $quebra_linha;
        		$headers .= 'From: ' . $this->remetente . $quebra_linha;
        		$headers .= 'Return-Path: ' . $this->remetente . $quebra_linha;
        		$headers .= 'Content-type: multipart/mixed; boundary="' . $boundary . '"' . $quebra_linha;
        		$headers .= $boundary . $quebra_linha;
        	}
        	
            # Envia o email
           mail (urldecode($this->destino), $this->assunto, $html, $headers,"-f".'informatica2@amepe.com.br');
            //echo urldecode($this->destino);
        	// mail ( 'informatica2@amepe.com.br', $this->assunto, $html, $headers,"-r".'contato_site@amepe.com.br');
        } else {
            $err = new Msg();
            # $err->dieProcess('Envio local rejeitado');
            echo '[LW] Envio local rejeitado (' . $this->destino . ')'; 
        }
    }
}

?>
