<?php
# Antes de utilizar a API o DNS do servidor deve estar devidamente configurado como informado abaixo

# Setup Amazon SES: http://docs.aws.amazon.com/ses/latest/DeveloperGuide/authentication.html
# Tipo de Registro              Nome                                      |    Valor
# TXT                           dominio.com.br.                           |	   spf2.0/pra include:amazonses.com ?all
# TXT							_amazonses.dominio.com.br.                |    DKIM gerado pela Amazon com o easy DKIM
# CNAME                         DKIM Record Set 1 gerado pela Amazon      |	   Valor referente gerado
# CNAME                         DKIM Record Set 2 gerado pela Amazon      |	   Valor referente gerado
# CNAME                         DKIM Record Set 3 gerado pela Amazon      |	   Valor referente gerado

# Setup Elastic Email: http://elasticemail.com/api-documentation/domain-authorization
# Tipo de Registro              Nome                                      |    Valor
# TXT                           dominio.com.br.                           |    v=spf1 a mx include:_spf.elasticemail.com ~all ou se já existir adicionar include:_spf.elasticemail.com
# CNAME                         email.dominio.com.br.                     |	   api.elasticemail.com
# CNAME							tracking.dominio.com.br.                  |    api.elasticemail.com
# TXT                           api._domainkey.midiacoletiva.com.br.      |	   Chave DKIM informada pelo Elastic email (ver link)

class CONFIG_EMAIL{
	# "AWS" para usar o SES da Amazon, "EE" para usar o Elastic Email ou LW para usar a Locaweb
	public static $SERVERAPIEMAIL = 'LW';
	# Caso o AWS retorne algum erro o Elastic Email é utilizado automaticamente.

	# Indica se o email pode ser enviado de um servidor local (localhost)
	public static $LOCALHOSTAPIEMAIL = true;

	# Configurações para AWS
	public static $CHAVEACESSOAWS = ''; // chave de segurança do AWS
	public static $CHAVESECRETAAWS = ''; // chave secreta do AWS
	public static $HOSTAWS = 'email.us-east-1.amazonaws.com'; // Host do AWS

	# Configurações Elastic Email
	public static $USERNAMEEE = ''; // Nome de usuário do Elastic Email
	public static $APIKEYEE = ''; // Chave de segurança do Elastic Email

	####################################################
	############### CORPO DE EMAILS ####################
	####################################################
	// Monta os textos de emails. @param(auxiliar de variáveis, tag da case)

	/**
	 * Busca no diretório 'emails' o corpo do email que será enviado.
	 * Permite substituir os conteúdos entre chaves {} no corpo do email pelo valor das variáveis do array $auxEmail.
	 * O array $auxEmail é definido na instância da classe Email.
	 * @param obj $aux
	 * @param string chave para o corpo do email na pasta emails
	 * @return string email formatado
	 */
	static function getCorpo($auxEmail, $corpo) {
		$err = new Msg();
		if (file_exists(Glb::$CONFIG['RAIZGLB'] . '/framework/plugins/email/emails/' . $corpo . '.email')) {
			// Lê um arquivo .email e retorna o valor das variáveis em um array.
			$texto = "<pre><font face='Verdana' color='#333333'>";
			$texto .= file_get_contents(Glb::$CONFIG['RAIZGLB'] . '/framework/plugins/email/emails/' . $corpo . '.email');
			$texto .= '</font></pre>';
			
			foreach ($auxEmail as $key=>$valor) {
				if(strpos($texto, "{".$key."}") !== false) {
					$texto = str_replace("{".$key."}", $valor, $texto);
				}
			}
			$texto = str_replace('{logo}', "<img src='" . Glb::$CONFIG['URL'] . "/global/app.visual/imagens/logo_email.jpg' />", $texto);
		} else {
			$texto = $corpo;
		}

		// echo $texto;
		return utf8_decode($texto);
	}
}



?>
