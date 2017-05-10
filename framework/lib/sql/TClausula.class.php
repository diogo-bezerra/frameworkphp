<?php
// Classe de interface para definiчуo de filtros de select
class TClausula extends TExpressao
{
	private $expressoes;
	private $operadores;
	private $clausulas = array('GROUP BY'=>'','ORDER BY'=>'','LIMIT'=>'','OFFSET'=>'');
	
	// Adiciona um critщrio. @param(objeto expressao, operador(and ou or de TExpressao)
	public function add(TExpressao $expressao,$operador = self::OPERADOR_AND){
		// Nуo щ necessсrio operador lѓgico na primeira sentenчa
		if(empty($this->expressoes)){
			unset($operador);
			$operador = '';
		}
		
		// Agrega resultado da expressуo a lista de expressѕes
		$this->expressoes[] = $expressao;
		$this->operadores[] = $operador;
	}
	
	// Retorna a expressуo em forma de string
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