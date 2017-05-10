<?php
// Classe abstrata para gerenciar expressões
abstract class TExpressao
{
	// Operadores lógicos
	const OPERADOR_AND = 'AND ';
	const OPERADOR_OR = 'OR ';
	
	// Retorna a expressão em forma de string
	abstract public function dump();
}
?>