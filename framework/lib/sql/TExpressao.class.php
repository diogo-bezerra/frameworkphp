<?php
// Classe abstrata para gerenciar express�es
abstract class TExpressao
{
	// Operadores l�gicos
	const OPERADOR_AND = 'AND ';
	const OPERADOR_OR = 'OR ';
	
	// Retorna a express�o em forma de string
	abstract public function dump();
}
?>