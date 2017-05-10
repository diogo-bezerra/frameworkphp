<?php
/**
 * Super classe para classes de repositório.
 * @author Diogo (d.bezerra@yahoo.com.br)
 */
abstract class Repos {
	protected $alias;
	protected $tabela;
	public $rs;
	function __construct($args, $tabela, $alias) {
		$this->tabela = $tabela;
		$this->alias = $alias;
		switch ($args [0]) {
			case 'select' :
				if (isset ( $args [2] )) {
					$this->rs = $this->select ( $args [1], $args [2] );
				} else {
					$this->rs = $this->select ( $args [1] );
				}
				break;
			case 'insert' :
					$this->rs = $this->insert ( $args [1], $args [2] );
				break;
			case 'update' :
				if (isset ( $args [2] )) {
					$this->rs = $this->update ( $args [1], $args [2] );
				} else {
					$this->rs = $this->update ( $args [1] );
				}
				break;
			case 'delete' :
				if (isset ( $args [2] )) {
					$this->rs = $this->delete ( $args [1], $args [2] );
				} else {
					$this->rs = $this->delete ( $args [1] );
				}
				break;
		}
	}
}