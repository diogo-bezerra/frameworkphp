<?php
/**
 * Super classe para classes de modelo.
 * @method <li>callCtrlGet (Monta o comando JS (Global.js->callCtrlGet) para chamar um Controle via Get Javascript Ajax)
 * <li>callCtrlPost (Monta o comando JS (Global.js->callCtrlPost) para chamar um Controle via Post Javascript Ajax)
 * <li>submitForm (Monta o comando JS (Global.js->submitForm) para submeter um form via Post Javascript Ajax)
 * @author Diogo (d.bezerra@yahoo.com.br)
 */
abstract class AppModelo{
	/**
	 * 
	 */
	function __construct($id) {
		// Retorna um objeto do banco caso o id seja definido
		if (!is_null($id) and isset($id)) {
			$this->id = $id;
			if(!$this->getObj(array('id' => $id))){
				$this->id = false;
			}
		}
	}

	/**
	 *
	 * Usada para chamar funções estáticas não declaradas
	 * O repositório é chamado de nomeClasse::BD(tipo,chave,aux)
	 * tipo: string tipo da ação (select, insert, update ou delete)
	 * chave: int Indica qual query será chamada no reposit�rio
	 * aux: obj auxiliar para enviar variáveis
	 * OBS: NÃO FUNCIONA NO PHP 5.2
	 */
	public static function __callstatic($function, $arguments) {
		if($function == 'BD'){
			$nomeRepos = 'Repos'.get_called_class();
			$tabela = strtolower('tb_'.get_called_class());
			$alias = strtolower(substr(get_called_class(), 0, 4));
			$bd = new $nomeRepos($arguments, $tabela, $alias);
			$rs = $bd->get();
			return $rs;
		}
	}
	
	/**
	 * SUBSTITUI A FUNÇÃO ACIMA PARA O PHP 5.2
	 */
	public static function BD($tipo, $chave, $aux = null, $tabela = null) {
		$arguments = array($tipo, $chave, $aux);
		if(is_null($aux)) {
			$arguments = array($tipo, $chave);
		}
		// PHP 5.3
		if(function_exists('get_called_class')){
			$class = get_called_class();
			$nomeRepos = 'Repos'.$class;
			if(is_null($tabela)) {
				$tabela = strtolower('tb_'.$class);
			}
			$alias = strtolower(substr(get_called_class(), 0, 4));
			
			$bd = new $nomeRepos($arguments, $tabela, $alias);
			$rs = $bd->get();
		// PHP 5.2
		} else{ 
			$nomeRepos = 'Repos'.self::get_called_classphp52();
			if(is_null($tabela)) {
				$tabela = strtolower('tb_'.self::get_called_classphp52());
			}
			$alias = strtolower(substr(self::get_called_classphp52(), 0, 4));
			$bd = new $nomeRepos($arguments, $tabela, $alias);
			$rs = $bd->get();
		}
		return $rs; 		
	}
	
	/**
	 * SUBSTITUI get_called_class DO PHP 5.3 PARA O PHP 5.2
	 */ 
	static function get_called_classphp52($level = 1, $trace = false) {
		if (!$trace) $trace = debug_backtrace();
		if (!isset($trace[$level])) throw new Exception(
				'Cannot find called class: stack level too deep');
		if (!isset($trace[$level]['type'])) throw new Exception (
				'Cannot find called class: type not set');
	
		switch ($trace[$level]['type']) {
			case '::':
				$lines = file($trace[$level]['file']);
				$i = 0;
				$callerLine = '';
				while (stripos($callerLine, $trace[$level]['function']) === false) {
					$i++;
					$callerLine = $lines[$trace[$level]['line'] - $i] . $callerLine;
				}
	
				$pattern = '/([a-zA-Z0-9\_]+)::' . $trace[$level]['function'] . '/';
				preg_match($pattern, $callerLine, $matches);
	
				if (!isset($matches[1])) {
					throw new Exception(
							'Cannot find called class: originating method call is obscured');
				}
	
				switch ($matches[1]) {
					case 'self':
					case 'parent':
						return get_called_class($level + 1, $trace);
					default:
						return $matches[1];
				}
	
			case '->':
				switch ($trace[$level]['function']) {
					case '__get':
						if (!is_object($trace[$level]['object'])) {
							throw new Exception('Edge case fail. __get called on non object');
						}
						return get_class($trace[$level]['object']);
					default: return $trace[$level]['class'];
				}
	
			default:
				throw new Exception ("Unknown backtrace method type");
		}
	
	}
	
	/** Retorna o valor de um atributo
	 * @param string $nome_atributo
	 */
	function get($nome_atributo) {
		return $this->$nome_atributo;
	}

	/**
	 * Define um atributo da classe
	 *
	 * @param string $nome_atributo
	 * @param string $novo_valor
	 */
	function set($nome_atributo, $novo_valor) {
		$this->$nome_atributo = $novo_valor;
	}

	/** Retorna um obj do BD de acordo com o parâmetro (array).
	 * @param array com parâmetros para o sql
	 * <li> Ex: array('id' => 1, 'email' => 'email@email.com')
	 */
	function getObj($arrayParams = NULL) {
		if (!is_null($arrayParams)) {
			$rs = self::BD('select', 0, $arrayParams);
			if ($rs) {
				$this->setObj($rs[0]);
				return true;
			} else {
				return false;
			}
		} else {
			$err = new Msg();
			$err->dieProcess("Falta parametro (".  get_class().".class)");
		}
	}

	/**
	 *
	 * Define todos os atributos de um objeto com os valores do parâmetro (array).
	 * O array passado deve ter os atributos nomeados iguais aos atributs da classe.
	 * Se for obtido do BD os campos devem ter os mesmos nomes.
	 * @param(array com resultado do banco ou criado)
	 */
	function setObj($array_valor) {
		# Buscando as propriedades da classe
		$this->reflect = new ReflectionClass($this);
		$nomeProps = $this->reflect->getProperties();
		# Percorre as propriedades
		foreach ($nomeProps as $prop) {
			$nome = $prop->getName();
			
			# Se a propriedade for uma classe percorre e define os atributos dessa classe
			if (is_object($this->get($nome))) {
				$reflect = new ReflectionClass(get_class($this->get($nome)));
				$nomeProps2 = $reflect->getProperties();
				foreach ($nomeProps2 as $prop2) {
					$nome2 = $prop2->getName();
					if (isset($array_valor[$nome2])) {
						$this->get($nome)->set($nome2, $array_valor[$nome2]);
					}
				}
				# Define o valor do atributo
			} else {
				if (isset($array_valor[$nome])) {
					$this->set($nome, $array_valor[$nome]);
				}
			}
		}
	}

	function insert() {
		self::BD('insert', 0, $this);
	}

	function update() {
		self::BD('update', 0, $this);
	}

	function delete() {
		self::BD('delete', 1, $this);
	}

	/**
	 * Deleta todos os objs do BD
	 */
	static function deleteAll() {
		self::BD('delete', 0);
	}
	
	/**
	 * Update de todos os objs do BD
	 */
	static function updateAll($params) {
		if(is_array($params) and isset($params)){
			self::BD('update', 1, $params);
		}else{
			die('Erro em updateAll');
		}
	}
	
	/**
	 * Retorna todos os objs do BD
	 */
	static function getAll() {
		return self::BD('select', 0, array('1'=>1));
	}
}