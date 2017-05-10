<?php
// Usada para criaчуo e manipulaчуo de instruчѕes INSERT
final class TSqlInsert extends TSqlInstrucao
{
	function __construct(){
		$this->stringSqlInject = '';
	}
	
	// Atribui valores as colunas que serуo inseridas. @param(coluna da tabela, valor definido para a coluna)
	public function setColunaValor($coluna,$valor){ 
    $this->stringSqlInject = $this->stringSqlInject.$valor;
		// Monta array indexado pelo nome da coluna
		// Caso valor seja string
		if(is_string($valor)){
			// Adiciona \ em aspas
			$valor = addslashes($valor);
			// Adiciona aspas
			$this->arrayColuna[$coluna] = "'$valor'";
		// Caso valor seja boleano
		}else if(is_bool($valor)){
			// Define true ou false
			$this->arrayColuna[$coluna] = $valor ? 'TRUE' : 'FALSE';
		// Se valor tiver conteњdo
		}else if(isset($valor)){
			$this->arrayColuna[$coluna] = $valor;
		}else{
			// Se for NULL
			$this->arrayColuna[$coluna] = 'NULL';
		}
	}
	
	// Lanчa erro para a funчуo setCriterio pois insert nуo tem clсusula WHERE
	// Tem que ser implementada pois pertence a classe pai. @param(objeto criterio)
	public function setCriterio(TClausula $clausula){
		throw new Exception("Nуo pode chamar setCriterio de " . __CLASS__);
	}
	
	// Retorna a instrunчуo montada
	public function getInstrucao(){
		$this->sql = "INSERT INTO {$this->tabela} (";
		// Monta a string com as colunas
		$colunas = implode(', ',array_keys(($this->arrayColuna)));
		
		// Monta a string com os valores
		$valores = implode(', ',array_values(($this->arrayColuna)));
		$this->sql .= $colunas . ')';
		$this->sql .= " VALUES ({$valores})";
		
		return $this->sql;
	}
}
?>