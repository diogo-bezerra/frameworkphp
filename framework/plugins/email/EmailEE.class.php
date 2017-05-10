<?php

class EmailEE{
	
    private $texto_html; // Texto do email em html (Body)
    private $texto; // Texto do email (Body)
    private $nome_remt; // Nome do remetente
    private $remetente; // Email que envia
    private $destino; // Email que recebe
    private $anexo; //Nome do neg�cio (TP)
    private $assunto; // Assunto do Email
    private $tipo; // Tipo de envio (Normal ou Personalizado)
    
    /**
     * Classe de email (Elastic Email)
     * Há dois tipos de email que são definidos pela variável $tipo.
     Normal (normal): é enviado um texto padrão para todos os destinatários.
     Nesse tipo a variável $destino deve ser uma string com os emails dos destinatários separados por ;
    
     Personalizado (pers): é enviado um texto personalizado para cada destinatário
     utilizando um arquivo(string) em formato CSV.
     Nesse tipo a variável $destino de ser uma string em formato CSV com o primeiro campo de cada linha
     sendo obrigatoriamente para o email(ToEmail).
    
     Ex:
     # Arquivo/String CSV enviado
     "ToEmail","nome","matricula","separador"
     "email1@email.com","Jo�o","10025",|x|
     "email2@email.com","Maria","33690",|x|
     ... n linhas
    
    
     # Texto
     Caro {nome}, informamos que sua matrícula é: {matricula}
    
     Para cada mensagem enviada ser�o utlizados os dados do CSV substituindo as tags entre chaves {} no texto.
    
     Obs1: Se o tipo não for informado o padrão será normal.
     Obs2: O limite de emails por CSV é de 100.000.
     Obs3: A mensagem deve ter as tags {} compatíveis com o CSV enviado.
     Obs4: O texto_html recebido deve ser codificado como utf8_encode
    
     */
    function __construct($nome_remt, $remetente, $destino, $assunto, $texto = '', $texto_html = '', $tipo = 'normal') {
    	$this->username = CONFIG_EMAIL::$USERNAMEEE;
        $this->api_key = CONFIG_EMAIL::$APIKEYEE;
        $this->nome_remt = $nome_remt;
        $this->remetente = $remetente;
        $this->destino = $destino;
        $this->assunto = $assunto;
        $this->texto = $texto;
        $this->texto_html = $texto_html;
        $this->tipo = $tipo;
    }

    function __destruct() {
        # echo "<br /> <br /> Objeto produto destruído";
    }

    // Retorna um atributo
    function get($nome_atributo) {
        return $this->$nome_atributo;
    }

    // Define um atributo
    function set($nome_atributo, $novo_valor) {
        $this->$nome_atributo = $novo_valor;
    }

    function enviar() {
        if ($_SERVER['REMOTE_ADDR'] != "127.0.0.2" and $_SERVER['REMOTE_ADDR'] != "::2") {
            $res = "";
            $data = "username=" . urlencode($this->username);
            $data .= "&api_key=" . urlencode($this->api_key);
            $data .= "&from=" . urlencode($this->remetente);
            $data .= "&from_name=" . urlencode($this->nome_remt);
            // Definindo os dados de acordo com o tipo de envio
            if ($this->tipo == 'normal') {
                $data .= "&to=" . urlencode($this->destino);
            } else {
                $csvName = 'mailmerge.csv';
                $attachID = $this->uploadAnexo($this->destino, $csvName);
                $data .= "&data_source=" . urlencode($attachID);
            }
            $data .= "&subject=" . urlencode($this->assunto);
            if (!empty($this->texto_html)) {
                #$html = '';
                $html = '<html>';
                $html .= '<head>';
                $html .= '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />';
                $html .= '<title>Email</title>';
                $html .= '</head>';
                $html .= '<body>';
                $html .= $this->texto_html;
                $html .= '</body>';
                $html .= '</html>';

                $data .= "&body_html=" . $html;
            } else {
                $data .= "&body_text=" . $this->texto;
            }

            $header = "POST /mailer/send HTTP/1.0\r\n";
            $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $header .= "Content-Length: " . strlen($data) . "\r\n\r\n";
            @$fp = fsockopen('ssl://api.elasticemail.com', 443, $errno, $errstr, 30);

            if (!$fp)
                return 'Erro de conex�o';
            else {
                fputs($fp, $header . $data);
                while (!feof($fp)) {
                    $res .= fread($fp, 1024);
                }
                fclose($fp);
            }
            # echo $res.'<br /><br />';
            return $res;
        } else {
            $err = new Msg();
            # $err->dieProcess('Envio local rejeitado');
            echo '[EE] Envio local rejeitado (' . $this->destino . ')';
        }
    }

    // Faz o upload de um arquivo CSV para emails personalizados
    function uploadAnexo($content, $fileName) {
        $res = "";
        $config = Glb::getConfig('emailEE.config');
        $data = "username=" . urlencode($this->username);
        $data .= "&api_key=" . urlencode($this->api_key);
        $header = "PUT /attachments/upload?username=" . urlencode($this->username) . "&api_key=" . urlencode($this->api_key) . "&file=" . urlencode($fileName) . " HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($content) . "\r\n\r\n";
        $fp = @fsockopen("ssl://api.elasticemail.com", 443, $errno, $errstr, 30);
        if (!$fp) {
            return "ERROR. Could not open connection";
        } else {
            fputs($fp, $header . $content);
            while (!feof($fp)) {
                $res .= fread($fp, 1024);
            }
            fclose($fp);
        }
        $rsId = substr($res, -9);
        echo $res . '<br /><br />';
        # echo $rsId.'<br /><br />'; 
        return $rsId;
    }

}

?>
