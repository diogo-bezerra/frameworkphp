<?php
class DTable extends DTag {
	private $countTr;
	private $countTd;
	
	public function __construct($tr, $td) {
    	parent::__construct('table');
    	$this->countTr = $tr;
    	$this->countTd = $td;
		for($i=1;$i<=$tr;$i++){
			$this->_tr[$i] = new DTag('TR');
			$this->_count_td_da_tr[$i] = $td;
			for($j=1;$j<=$td;$j++){
				$this->_td[$i][$j] = new DTag('TD');
				$this->_tr[$i]->setIn($this->_td[$i][$j]);
			}
			$this->setIn($this->_tr[$i]);
		}
    }
    
    /**
     * Insere conteúdo em uma td 
     * @param int $tr: Posição da tr
     * @param int $td: Posição da td
     */
    function setinTd($tr, $td, $conteudo) {
    	$this->_td[$tr][$td]->setIn($conteudo);
    }
    
    function getTd($tr, $td) {
    	return $this->_td[$tr][$td];
    }
    
    function colspan($tr, $td, $valor) {
    	$this->_td[$tr][$td]->colspan = $valor;
    	$count = $this->_count_td_da_tr[$tr];
    	
    	for($i=$valor;$i>1;$i--) {
    		$this->_td[$tr][$this->_count_td_da_tr[$tr]]->delete();
    		$this->_count_td_da_tr[$tr]--;
    	}
    	/*
    	print_r($this->_td[$tr]);
    	$this->_td[$tr][$td]->colspan = $valor;
    	for($i=1;$i<=$valor;$i++) {
    		
    		array_pop($this->_td[$tr]);
    	}
    	*/
    }
}

?>
