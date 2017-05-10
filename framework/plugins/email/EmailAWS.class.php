<?php

// Classe de email (Amazon SES)
class EmailAWS {
	/**
	 * Classe de email (AWS)
	 * @author Diogo (d.bezerra@yahoo.com.br)
	 * 
	 * @param string nome do remetente
	 * @param string email do remetente
	 * @param string email de destino
	 * @param string assunto do email
	 * @param string corpo da mensagem (txt)
	 * @param string corpo da mensagem (html)
	 * @param string auxiliar para enviar vari�veis ao corpo do email
	 * @param string tipo (ver classe emailEE.class.php)
	 * @param string servidor que ser� utilizado
	 */
    protected $chaveAcesso; // AWS Access key
    protected $chaveSecreta; // AWS Secret key
    protected $host;
    protected $verificaHost = 1;
    protected $verificaPeer = 1;
    private $verboHTTP = array(); // Verbo HTTP 
    private $parametros = array();
    public $resposta;
    public $destino;
    public $to, $cc, $bcc, $replyto;
    public $from, $returnpath;
    public $subject, $messagetext, $messagehtml;
    public $subjectCharset, $messageTextCharset, $messageHtmlCharset;
    
    public function __construct($nome_remt = null, $remetente, $destino, $assunto, $texto = '', $texto_html = '') {
    	
        $this->nome_remt = $nome_remt; // O nome do remetente s� � usado para EE quando o AWS d� erro.
        $this->chaveAcesso = CONFIG_EMAIL::$CHAVEACESSOAWS;
        $this->chaveSecreta = CONFIG_EMAIL::$CHAVESECRETAAWS;
        $this->host = CONFIG_EMAIL::$HOSTAWS;

        $this->destino = $destino;
        $this->to = explode(';', $destino);
        ;
        $this->cc = array();
        $this->bcc = array();

        $this->replyto = array();

        $this->from = $remetente;
        $this->returnpath = null;

        $this->subject = $assunto;
        $this->messagetext = null; // $texto;
        $this->messagehtml = $texto_html;

        $this->subjectCharset = null;
        $this->messageTextCharset = null;
        $this->messageHtmlCharset = null;
    }

    function __destruct() {
        # echo "<br /> <br /> Objeto produto destru�do";
    }

    // Retorna um atributo
    function get($nome_atributo) {
        return $this->$nome_atributo;
    }

    // Define um atributo
    function set($nome_atributo, $novo_valor) {
        $this->$nome_atributo = $novo_valor;
    }

    // verificaHost e verificaPeer determinam se curl verifica certificados ssl.
    // Pode ser necess�rio para desativar essas checagens em certos sistemas these checks on certain systems.
    // Somente tem efeito se SSL estiver ativo.
    function ativaVerificaHost($enable = true) {
        $this->verificaHost = $enable;
    }

    function ativaVerificaPeer($enable = true) {
        $this->verificaPeer = $enable;
    }

    public function setParametro($key, $value, $replace = true) {
        if (!$replace && isset($this->parametros[$key])) {
            $temp = (array) ($this->parametros[$key]);
            $temp[] = $value;
            $this->parametros[$key] = $temp;
        } else {
            $this->parametros[$key] = $value;
        }
    }

    // Ver: http://www.morganney.com/blog/API/AWS-Product-Advertising-API-Requires-a-Signed-Request.php
    // @param string
    private function customUrlEncode($var) {
        return str_replace('%7E', '~', rawurlencode($var));
    }

    private function getAssinatura($string) {
        return base64_encode(hash_hmac('sha256', $string, $this->chaveSecreta, true));
    }

    // Resposta de curl
    private function responseWriteCallback(&$curl, &$data) {
        $this->resposta->body .= $data;
        return strlen($data);
    }

    // Faz a requisição de envio
    public function getResposta() {
        $params = array();
        foreach ($this->parametros as $var => $value) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    $params[] = $var . '=' . $this->customUrlEncode($v);
                }
            } else {
                $params[] = $var . '=' . $this->customUrlEncode($value);
            }
        }

        sort($params, SORT_STRING);

        // Deve estar no formato 'Sun, 06 Jul 2012 08:49:37 GMT'
        $data = gmdate('D, d M Y H:i:s e');

        $query = implode('&', $params);

        $headers = array();
        $headers[] = 'Date: ' . $data;
        $headers[] = 'Host: ' . $this->host;

        $auth = 'AWS3-HTTPS AWSAccessKeyId=' . $this->chaveAcesso;
        $auth .= ',Algorithm=HmacSHA256,Signature=' . $this->getAssinatura($data);
        $headers[] = 'X-Amzn-Authorization: ' . $auth;

        $url = 'https://' . $this->host . '/';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_USERAGENT, 'SimpleEmailService/php');

        #curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, ($this->verificaHost ? 1 : 0));
        #curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, ($this->verificaPeer ? 1 : 0));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        // Tipos de request
        switch ($this->verboHTTP) {
            case 'GET':
                $url .= '?' . $query;
                break;
            case 'POST':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->verboHTTP);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
                $headers[] = 'Content-Type: application/x-www-form-urlencoded';
                break;
            case 'DELETE':
                $url .= '?' . $query;
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            default: break;
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_HEADER, false);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($curl, CURLOPT_WRITEFUNCTION, array(&$this, 'responseWriteCallback'));
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        if (curl_exec($curl)) {
            $this->resposta->code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        } else {
            $this->resposta->error = array(
                'curl' => true,
                'code' => curl_errno($curl),
                'message' => curl_error($curl),
                'resource' => 'resource'#$this->resource
            );
        }

        @curl_close($curl);

        if ($this->resposta->error === false && isset($this->resposta->body)) {
            $this->resposta->body = simplexml_load_string($this->resposta->body);

            // Verificando Erros
            if (!in_array($this->resposta->code, array(200, 201, 202, 204))
                    && isset($this->resposta->body->Error)) {
                $error = $this->resposta->body->Error;
                $output = array();
                $output['curl'] = false;
                $output['Error'] = array();
                $output['Error']['Type'] = (string) $error->Type;
                $output['Error']['Code'] = (string) $error->Code;
                $output['Error']['Message'] = (string) $error->Message;
                $output['RequestId'] = (string) $this->resposta->body->RequestId;

                $this->resposta->error = $output;
                unset($this->resposta->body);
            }
        }
        return $this->resposta;
    }

    // Lista os emails que podem ser usados como remetente (emails verificados)
    function listaEmailsVerificados() {
        $this->verboHTTP = 'GET';
        $this->resposta = new STDClass;
        $this->resposta->error = false;
        $this->setParametro('Action', 'ListVerifiedEmailAddresses');
        $rs = $this->getResposta();
        if ($rs->error === false && $rs->code !== 200) {
            $rs->error = array('code' => $rs->code, 'message' => 'Unexpected HTTP status');
        }
        if ($rs->error !== false) {
            $error = $rs->error;
            $err = new Msg();
            $err->dieProcess('Erro: (' . $error['code'] . ') ' . $error['message']);
            return false;
        }
        $resposta = array();
        if (!isset($rs->body)) {
            return $resposta;
        }
        $emails = array();
        foreach ($rs->body->ListVerifiedEmailAddressesResult->VerifiedEmailAddresses->member as $email) {
            $emails[] = (string) $email;
        }

        $response['Emails'] = $emails;
        $response['RequestId'] = (string) $rs->body->ResponseMetadata->RequestId;
        return $response;
    }

    // Valida um email para ser usado como remetente 
    public function validaEmailRemet($email) {
        $this->verboHTTP = 'POST';
        $this->resposta = new STDClass;
        $this->resposta->error = false;

        $this->setParametro('Action', 'VerifyEmailAddress');
        $this->setParametro('EmailAddress', $email);

        $rs = $this->getResposta();
        if ($rs->error === false && $rs->code !== 200) {
            $rs->error = array('code' => $rs->code, 'message' => 'Unexpected HTTP status');
        }
        if ($rs->error !== false) {
            $error = $rs->error;
            $err = new Msg();
            $err->dieProcess('Erro: (' . $error['code'] . ') ' . $error['message']);
            return false;
        }

        $response['RequestId'] = (string) $rs->body->ResponseMetadata->RequestId;
        return $response;
    }

    // Desativa um email para ser usado como remetente
    public function deleteEmailRemet($email) {
        $this->verboHTTP = 'DELETE';
        $this->resposta = new STDClass;
        $this->resposta->error = false;

        $this->setParameter('Action', 'DeleteVerifiedEmailAddress');
        $this->setParameter('EmailAddress', $email);

        $rs = $this->getResposta();
        if ($rs->error === false && $rs->code !== 200) {
            $rs->error = array('code' => $rs->code, 'message' => 'Unexpected HTTP status');
        }
        if ($rs->error !== false) {
            $error = $rs->error;
            $err = new Msg();
            $err->dieProcess('Erro: (' . $error['code'] . ') ' . $error['message']);
            return false;
        }

        $response['RequestId'] = (string) $rs->body->ResponseMetadata->RequestId;
        return $response;
    }

    // Retorna a quantidade de emails que podem ser enviados por dia e por segundo 
    public function getSendQuota() {
        $this->verboHTTP = 'GET';
        $this->resposta = new STDClass;
        $this->resposta->error = false;

        $this->setParametro('Action', 'GetSendQuota');

        $rs = $this->getResposta();
        if ($rs->error === false && $rs->code !== 200) {
            $rs->error = array('code' => $rs->code, 'message' => 'Unexpected HTTP status');
        }
        if ($rs->error !== false) {
            $error = $rs->error;
            $err = new Msg();
            $err->dieProcess('Erro: (' . $error['code'] . ') ' . $error['message']);
            return false;
        }

        $response = array();
        if (!isset($rs->body)) {
            return $response;
        }

        $response['Max24HourSend'] = (string) $rs->body->GetSendQuotaResult->Max24HourSend;
        $response['MaxSendRate'] = (string) $rs->body->GetSendQuotaResult->MaxSendRate;
        $response['SentLast24Hours'] = (string) $rs->body->GetSendQuotaResult->SentLast24Hours;
        $response['RequestId'] = (string) $rs->body->ResponseMetadata->RequestId;

        return $response;
    }

    // Retorna um array com os dados estat�sticos de envio de email
    public function getSendStatistics() {
        # $rest = new SimpleEmailServiceRequest($this, 'GET');
        $this->verboHTTP = 'GET';
        $this->resposta = new STDClass;
        $this->resposta->error = false;

        $this->setParametro('Action', 'GetSendStatistics');

        $rs = $this->getResposta();
        if ($rs->error === false && $rs->code !== 200) {
            $rs->error = array('code' => $rs->code, 'message' => 'Unexpected HTTP status');
        }
        if ($rs->error !== false) {
            #$this->__triggerError('getSendStatistics', $rest->error);
            $error = $rs->error;
            $err = new Msg();
            $err->dieProcess('Erro: (' . $error['code'] . ') ' . $error['message']);
            return false;
        }

        $response = array();
        if (!isset($rs->body)) {
            return $response;
        }

        $datapoints = array();
        foreach ($rs->body->GetSendStatisticsResult->SendDataPoints->member as $datapoint) {
            $p = array();
            $p['Bounces'] = (string) $datapoint->Bounces;
            $p['Complaints'] = (string) $datapoint->Complaints;
            $p['DeliveryAttempts'] = (string) $datapoint->DeliveryAttempts;
            $p['Rejects'] = (string) $datapoint->Rejects;
            $p['Timestamp'] = (string) $datapoint->Timestamp;

            $datapoints[] = $p;
        }

        $response['SendDataPoints'] = $datapoints;
        $response['RequestId'] = (string) $rs->body->ResponseMetadata->RequestId;

        return $response;
    }

    // Envia email
    public function enviar() {
        #if(!$this->validacao()) {
        #$err = new Msg();
        #$err->dieProcess(htmlentities('Par�metros para a classe incompletos.'));
        #}

        if ($_SERVER['REMOTE_ADDR'] != "127.0.0.2" and $_SERVER['REMOTE_ADDR'] != "::2") {
            #$rest = new SimpleEmailServiceRequest($this, 'POST');
            $this->verboHTTP = 'POST';
            $this->resposta = new STDClass;
            $this->resposta->error = false;
            $this->setParametro('Action', 'SendEmail');

            // Remetente
            $this->setParametro('Source', $this->from);

            // Assunto
            if ($this->subject != null && strlen($this->subject) > 0) {
                $this->setParametro('Message.Subject.Data', $this->subject);
                if ($this->subjectCharset != null && strlen($this->subjectCharset) > 0) {
                    $this->setParametro('Message.Subject.Charset', $this->subjectCharset);
                }
            }

            // Mensagem de texto
            if ($this->messagetext != null && strlen($this->messagetext) > 0) {
                $this->setParametro('Message.Body.Text.Data', $this->messagetext);
                if ($this->messageTextCharset != null && strlen($this->messageTextCharset) > 0) {
                    $this->setParametro('Message.Body.Text.Charset', $this->messageTextCharset);
                }
            }

            // Mensagem HTML
            // $html = '';
            $html = '<html>';
            $html .= '<head>';
            $html .= '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />';
            $html .= '<title>Email</title>';
            $html .= '</head>';
            $html .= '<body>';
            $html .= $this->messagehtml;
            $html .= '</body>';
            $html .= '</html>';
            if ($this->messagehtml != null && strlen($this->messagehtml) > 0) {
                $this->setParametro('Message.Body.Html.Data', $html);
                if ($this->messageHtmlCharset != null && strlen($this->messageHtmlCharset) > 0) {
                    $this->setParametro('Message.Body.Html.Charset', $this->messageHtmlCharset);
                }
            }

            /* // Reply
              if(is_array($this->replyto)) {
              $i = 1;
              foreach($this->replyto as $replyto) {
              $this->setParametro('ReplyToAddresses.member.'.$i, $replyto);
              $i++;
              }
              }
             */

            /* // Retorno
              if($this->returnpath != null) {
              $this->setParametro('ReturnPath', $this->returnpath);
              }
             */

            /* // C�pia
              if(is_array($this->cc)) {
              $i = 1;
              foreach($this->cc as $cc) {
              $this->setParametro('Destination.CcAddresses.member.'.$i, $cc);
              $i++;
              }
              }
             */

            /* // C�pia oculta
              if(is_array($this->bcc)) {
              $i = 1;
              foreach($this->bcc as $bcc) {
              $this->setParametro('Destination.BccAddresses.member.'.$i, $bcc);
              $i++;
              }
              }
             */
            // Destino
            foreach ($this->to as $to) {
                $this->setParametro('Destination.ToAddresses.member.1', $to);
                $rs = $this->getResposta();

                if ($rs->error !== false) {
                    # $this->__triggerError('sendEmail', $rs->error); 
                    $error = $rs->error['Error'];
                    // Se houver algum erro no envio utiliza o Elastic Email
                    $email = new EmailEE($this->nome_remt, $this->from, $this->destino, $this->subject, '', $this->messagehtml);
                    $email->enviar();
                    $response = FALSE;
                    #$err = new Msg();
                    #$err->dieProcess('Erro: ('.$error['Code'].') '.$error['Message']);
                } else {
                    $response['MessageId'] = (string) $rs->body->SendEmailResult->MessageId;
                    $response['RequestId'] = (string) $rs->body->ResponseMetadata->RequestId;
                }
            }
            return $response;
        } else {
            echo '[AWS] Envio local rejeitado (' . $this->destino . ')';
            return FALSE;
        }
    }

    // Define a mensagem HTML a partir de um arquivo
    function setHTMLMensagemFile($file = null) {
        if (file_exists($file) && is_file($file) && is_readable($file)) {
            $this->messagehtml = file_get_contents($file);
        } else {
            $this->messagehtml = null;
        }
    }

    // Define a mensagem Texto a partir de um arquivo
    function setTextoMensagemFile($file = null) {
        if (file_exists($file) && is_file($file) && is_readable($file)) {
            $this->messagetext = file_get_contents($file);
        } else {
            $this->messagetext = null;
        }
    }

    // Define a mensagem a partir de uma URL
    function setMensagemURL($texturl, $htmlurl = null) {
        if ($texturl !== null) {
            $this->messagetext = file_get_contents($texturl);
        } else {
            $this->messagetext = null;
        }
        if ($htmlurl !== null) {
            $this->messagehtml = file_get_contents($htmlurl);
        } else {
            $this->messagehtml = null;
        }
    }

    function setMessageCharset($textCharset, $htmlCharset = null) {
        $this->messageTextCharset = $textCharset;
        $this->messageHtmlCharset = $htmlCharset;
    }

    // Valida os campos do email
    public function validacao() {
        if ($this->from == null || strlen($this->from) == 0) {
            return false;
        }

        // A mensagem requer pelo menos: subject, messagetext, messagehtml.
        if (($this->subject == null || strlen($this->subject) == 0)
                && ($this->messagetext == null || strlen($this->messagetext) == 0)
                && ($this->messagehtml == null || strlen($this->messagehtml) == 0)) {
            return false;
        }
        return true;
    }

}

?>
