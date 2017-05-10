<?php
// Classe de interface para defini��o de filtros de select
class TClausula extends TExpressao
{
	private $expressoes;
	private $operadores;
	private $clausulas = array('GROUP BY'=>'','ORDER BY'=>'','LIMIT'=>'','OFFSET'=>'');
	
	// Adiciona um crit�rio. @param(objeto expressao, operador(and ou or de TExpressao)
	public function add(TExpressao $expressao,$operador = self::OPERADOR_AND){
		// N�o � necess�rio operador l�gico na primeira senten�a
		if(empty($this->expressoes)){
			unset($operador);
			$operador = '';
		}
		
		// Agrega resultado da express�o a lista de express�es
		$this->expressoes[] = $expressao;
		$this->operadores[] = $operador;
	}
	
	// Retorna a express�o em forma de string
	public function dump(){
		// Concatena a lista
		$result = '';
		if(is_array($this->expressoes)){
			foreach($this->expressoes as $i=>$expressao){
				$operador = $this->operadores[$i];
				$result .= $operador . $expressao->dump() . ' ';
			}
			$result = trim($result);
			return "({$result})";
		}
	}
	
	// Define o valor de uma clausula. @param(clausula(order,limit,group...), valor da propriedadee)
	public function setClausula($nome_clausula,$valor){
		$this->clausulas[$nome_clausula] = $valor;
	}
	
	// Retorna o valor de uma clausula
	public function getClausula($nome_clausula){
		return $this->clausulas[$nome_clausula];
	}
	
}
?>