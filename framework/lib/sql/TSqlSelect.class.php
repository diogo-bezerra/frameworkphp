<?php
/**
 * Usada para criação e manipulação de instruções SELECT
 */
final class TSqlSelect extends TSqlInstrucao
{
	private $colunas; // Colunas a serem selecionadas
	private $joins; // Joins a serem adicionados a instrução
	
	/**
	 * Adiciona uma coluna a ser retornada no resultado. 
	 * @param (String) coluna da tabela
	 */
	// 
	public function addColuna($coluna){
		$this->colunas[] = $coluna;
	}
	
	/**
	 * Adiciona uma coluna a ser retornada no resultado (formato string ou array). 
	 * @param (array ou string) Colunas a serem selecionadas na tabela
	 * <li> addColunas('col1, col2, col3')
	 * <li> addColunas(array('col1', 'col2', 'col3'))
	 */ 
	public function addColunas($ListaColunas){
		if(is_array($ListaColunas)){
			$this->colunas = $ListaColunas;
		}else{
			$this->colunas .= $ListaColunas;
		}
	}
	
	// Adiciona joins. @param(tipo de join(inner,full,left,right...),tabela a ser unida, id do join da tabela atual, id do join da tabela a ser unida)
	public function addJoin($tipo,$tabela,$id1,$id2){
		$this->joins[] = ' ' . $tipo . ' JOIN ' .  $tabela . ' ON(' . $id1 . ' = ' . $id2 . ') ' ;
	}
	
	// Retorna a instruncao SELECT montada
	public function getInstrucao(){
		$this->sql = 'SELECT ';
		// Monta colunas
		if(is_array($this->colunas)){
			$sep = "";
			foreach ( $this->colunas as $col ) {
				$this->sql .= $sep.$col;
				$sep = ", ";
			}
		}else{
			$this->sql .= $this->colunas;
		}
		
		
		// Adiciona na cl�usula FROM o nome da tabela
		$this->sql .= ' FROM ' . $this->tabela;
		
		// Monta joins
		if($this->joins){
			$this->sql .= implode('',array_values(($this->joins)));
		}
		
		// Monta WHERE do objeto criterio
		if($this->clausula){
			$expressao = $this->clausula->dump();
			if($expressao){
				$this->sql .= ' WHERE ' . $expressao;
			}
			
			// Monta propriedades do criterio
			$group = $this->clausula->getClausula('GROUP BY');
			$order = $this->clausula->getClausula('ORDER BY');
			$limit = $this->clausula->getClausula('LIMIT');
			$offset = $this->clausula->getClausula('OFFSET');
			
			// Monta ordena��o, limite...
			if($group){
				$this->sql .= ' GROUP BY ' . $group;
			}
			if($order){
				$this->sql .= ' ORDER BY ' . $order;
			}
			if($limit){
				$this->sql .= ' LIMIT ' . $limit;
			}
			if($offset){
				$this->sql .= ' OFFSET ' . $offset;
			}
		}
		return $this->sql;
	}
}
?>