<?php
/**
 * 
 * Cria tabelas dinamicamente
 * Params: (id, qtd de Trs, qtd de Tds)
 *
 */
class Tabela extends Elemento
{
	public $td;
	public $tr = array('');
	
	// Retorna um atributo
	public function __construct($id,$tr,$td){
		parent::__construct('TABLE');
		$this->setPropriedades(array(
			'id'=>$id,
			'name'=>$id
		));
		// Instancia automaticamente a quantidade de TRs e TDs passadas e adiciona na TAG da tabela
		for($i=1;$i<=$tr;$i++){
			$this->tr[$i] = new Elemento('TR');
			$this->add($this->tr[$i]);
			for($j=1;$j<=$td;$j++){
				$this->td[$i][$j] = new Elemento('TD');
				$this->tr[$i]->add($this->td[$i][$j]);
			}
		}
	}
	
	// Define propriedades para todas as TDs da tabela
	public function setAllTdProp($propriedades){
		// Instancia automaticamente a quantidade de TRs e TDs passadas e adiciona na TAG da tabela
		for($i=1;$i<=count($this->tr)-1;$i++){
			for($j=1;$j<=count($this->td[$i]);$j++){
				$this->td[$i][$j]->setPropriedades($propriedades);
			}
		}
	}
	
	// Define propriedades para todas as TDs de uma TR da tabela
	public function setAllTdTrProp($tr,$propriedades){
		// Instancia automaticamente a quantidade de TRs e TDs passadas e adiciona na TAG da tabela
		for($j=1;$j<=count($this->td[$tr]);$j++){
			$this->td[$tr][$j]->setPropriedades($propriedades);
		}
	}
}