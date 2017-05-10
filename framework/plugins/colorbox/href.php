<?php
$vs=$_GET['vs'];

echo $vs;
/*
include_once("../../../app.controle/Global.class.php");
setlocale(LC_ALL, 'pt_BR.utf8');

class hrefColorBox {

	function __construct() {
		@$vs = $_GET['href'];
		#<HTML>#
		$this->html = new Elemento('HTML');
		$this->html->setPropriedades(array(
				'xmlns' => 'http://www.w3.org/1999/xhtml'
		));

		#<HEAD>#
		$this->head = new Head();

		#<META>#
		$meta = new Elemento('META');
		$meta->setPropriedades(array(
				'content' => 'text/html; charset=utf-8',
				'http-equiv' => 'Content-Type'
		));

		#<TITLE>#
		$title = new Elemento('TITLE');
		$title->add('');

		#<BODY>#
		$this->body = new Elemento('BODY');
		$this->body->setPropriedades(array(
				'style' => 'margin:0px;padding:0px'
		));

		#====== STYLES/CSS =======
		include_once '../../../app.controle/config/setCss.php';

	
		#### Montagem ####
		$this->html->add($this->head); // Adiciona a Elemento head ao Elemento html
		$this->html->add($this->body);
		$this->head->add($meta); // Adiciona a Elemento meta ao Elemento head
		$this->head->add($title); // Adiciona o Elemento titulo ao Elemento head
		

		$this->head->load(); // Insere todos os scripts e links definidos

		$this->body->add('yyyyyyyyyyy'); // Todo conte�do do site deve ser armazenado no elemento div_getJs do body par verificar se o JS est� ativo. Ver a constru��o em Body.class.php

		
	}

	/**
	 * Mostra o html do index
	 *
	function show() {
		# Mostra conteudo do HTML
		$this->html->show();
	}
}

$hrefColorBox = new hrefColorBox();
$hrefColorBox->show();
*/
?>
