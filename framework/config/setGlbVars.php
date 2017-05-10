<?php
/**
 Define um array de variáveis para configurações globais
 É incluido no index.php 
 *
 */

class CONFIGGLOBAL {
	public static $auxGlobal = array(
			# Nome aux do sistema
			'NOME1' => 'frameworkGithub',
				
			# Nome aux do sistema
			'NOME2' => 'frameworkGithub',
	
			# Url do site
			'URL' => 'http://localhost/frameworkGithub',
			
			# Redireciona para https se for http
			'SSL' => FALSE,
			
			# Uri do site
			'URI' => 'frameworkGithub',

			# Diretório físico dos arquivos no servidor
			'RAIZGLB' => 'Z:\Desenvolvimento\web\frameworkGithub',

			# Domínio do site
			'DOMINIO' => 'frameworkGithub.com',
				
			# Id do container onde será carregado o conteúdo inicial (Definir em index.html)
			# A altura do container pode ser definida no index.html ou dinamicamente
			# pela função showVs de uma view
			'IDCONTAINER' => 'screen',
				
			# Alinhamento do body
			'alignBody' => 'center', // center, left ou right
				
			# Mensagem de loading da página inicial
			'msgLoad' => 'Carregando...',
				
			# Altura padrão (mínima) de uma view
			'HVIEW' => '300px',
				
			# Descrição do site (meta tag)
			'DESCRICAO' => 'frameworkGithub',

			# Keywords do site (meta tag)
			'KEYWORDS' => 'frameworkGithub',

			# Charset do site (meta tag)
			'CHARSET' => 'UTF-8',
			
			# Definir cada diretório de sistema interno que possua a estrutura MVC
			'SYS2' => 'site'
			
	);
}