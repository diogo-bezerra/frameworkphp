<?php
/**
 * Classe de criação de query e acesso ao BD
 * Para cada modelo deve haver um repositório correspondente.
 * Ver as classes de construção em no diretório classes/buildSql
 * <li>Ex: Contato::BD("select", 0, array('id'=>0))</li>
 * @author Diogo (d.bezerra@yahoo.com.br)
 * @param (array) Argumentos da chamada de Contato::BD
 * @param (String) tabela: nome da tabela no BD
 * @param (String) alias: apelido para a tabela
 * @property 
 * <li>(String) tabela: nome da tabela no BD
 * <li>(String) alias: apelido para a tabela
 * <li>(PDO) rs: retorno do resultado da query
 */
class ReposExemploModelo extends Repos {
	function __construct($args, $tabela, $alias) {
		parent::__construct($args, $tabela, $alias);
	}
	
	/**
	 * Retorna o resultado da query
	 * @return boolean
	 */
	function get() {
		if (isset ( $this->rs )) {
			return $this->rs;
		} else {
			return false;
		}
	}
	
	/** Executa selects pré configurados. 
	 * Os selects podem ser criados com String ou pela classe TSqlSelect
	 * @param (int) chave de escolha do select
	 * @param (Objeto, array de objeto, string, inteiro...) variável auxiliar
	 * @return Resultado da consulta
	 */
	function select($key, $aux = array('id'=>0)) {
		# Segurança para SqlInject
		$verfSqlInj = new SqlInject ( $aux ); 
		$sql = new TSqlSelect ();
		$sql->setTabela ( $this->tabela . ' ' . $this->alias );
		switch ($key) {
			case 0 : // Retorna todos os atributos de um registro de acordo com parametro passado
				$sql->addColunas ( '*' ); // Colunas a serem selecionadas
				$clausula = new TClausula (); // Construção da condição WHERE
				foreach ( $aux as $chave => $param ) {
					$clausula->add ( new TFiltro ( $chave, '=', $param ) ); // Adiciona um filtro
				}
				$sql->setClausula ( $clausula ); // Define a cláusula do sql
			break;
			case 1 : 
				$sql->addColunas ( '*' ); // Colunas a serem selecionadas
				$clausula = new TClausula (); // Construção da condição WHERE
				$clausula->add ( new TFiltro ( 'nome', '=', $aux['nome'] ) ); // Adiciona um filtro
				$clausula->setClausula('ORDER BY', $this->alias.'.nome'); // Define a cláusula ORDER BY
				$sql->setClausula ( $clausula ); // Define a cláusula do sql
			break;
			case 2 : 
				$sql="SELECT * FROM ".$this->tabela." ORDER BY ass.nome";
			break;
		}
		// echo $sql->getInstrucao();
		// echo $sql;
		$exec = new ExecuteSql ( $sql, get_class () ); // Executa o sql. @param(sql,nome da classe para identificação de erro)
		return $exec->result;
	}
	
	/** Insere um objeto no BD. @param(obj)
	 * 
	 * @param (int) $key
	 * @param (obj modelo) $obj
	 */
	function insert($key = 0, $obj) {
		$sql = new TSqlInsert ();
		$sql->setTabela ( $this->tabela );
		switch ($key) {
			case 0 :
				// Definindo os valores para inserção
				$obj->reflect = new ReflectionClass ( $obj );
				$nomeProps = $obj->reflect->getProperties ();
				foreach ( $nomeProps as $prop ) {
					$nome = $prop->getName ();
					//if ($nome != 'id') {
						$sql->setColunaValor ( $nome, ($obj->get ( $nome )) );
					//}
				}
				break;
			case 1 :
				$sql->setTabela ($this->tabela);
				$sql->setColunaValor ('nome', $obj['nome']);
				$sql->setColunaValor ('fone', $obj['fone']);
				$sql->setColunaValor ('email', $obj['email']);
				break;
		}
		
		// Segurança para SqlInject
		$verfSqlInj = new SqlInject ( $sql->stringSqlInject ); // stringSqlInject soma todos os valores a serem inseridos. Usado somente para insert(TsqlInsert)
        // echo $sql->getInstrucao();
		$exec = new ExecuteSql ( $sql->getInstrucao (), get_class () ); // Executa o sql. @param(sql,nome da classe para identifica��o de erro)
	}
	
	/** Atualiza um objeto no BD. @param(obj)
	 *
	 * @param (int) $key
	 * @param (obj modelo, String) $aux
	 */
	function update($key, $aux = NULL) {
		// $verfSqlInj = new SqlInject ( $aux ); // Segurança para SqlInject
		$sql = new TSqlUpdate ();
		$sql->setTabela ( $this->tabela );
		switch ($key) {
			// Atualiza os dados que estão definidos do objeto passado por parametro ($aux)
			case 0 :
				$reflect = new ReflectionClass ( $aux );
				$props = $reflect->getProperties ();
				foreach ( $props as $prop ) {
					$nomeProp = $prop->getName ();
					$valorProp = $aux->get ( $nomeProp );
					if (isset ( $valorProp ) and $nomeProp != 'id' and $nomeProp != 'BD') {
						$sql->setColunaValor ( $nomeProp, ($valorProp) );
					}
				}
				$clausula = new TClausula (); // Construçãoo da condição WHERE
				$clausula->add ( new TFiltro ( 'id', '=', $aux->get ( 'id' ) ) ); // Adiciona um filtro
				$sql->setClausula ( $clausula ); // Define a cláusula do sql
				break;
			case 1 :
				$sql->setColunaValor ( 'nome', $aux['nome'] );
				$clausula = new TClausula (); // Construção da condição WHERE
				$clausula->add ( new TFiltro ( 'id', '=', $aux['id'] ) ); // Adiciona um filtro
				$sql->setClausula ( $clausula ); // Define a cláusula do sql
			break;
		}
		// echo $sql->getInstrucao();
		$exec = new ExecuteSql ( $sql, get_class () ); // Executa o sql. @param(sql,nome da classe para identifica��o de erro)
	}
	
	/** Deleta um objeto no BD. @param(obj)
	 *
	 * @param (int) $key
	 * @param (obj modelo, String) $aux
	 */
	function delete($key, $aux = NULL) {
		$verfSqlInj = new SqlInject ( $aux ); // Segurança para SqlInject
		$sql = new TSqlDelete ();
		$sql->setTabela ( $this->tabela );
		switch ($key) {
			// Deleta todos os objs
			case 0 : // Boa prática: onde é chamada ---> Classe.class.php->funçãoquechama()
				break;
			
			// Deleta um obj de acordo com o id
			case 1 :
				$clausula = new TClausula (); // Construção da condição WHERE
				foreach ( $aux as $chave => $param ) {
					$clausula->add ( new TFiltro ( $chave, '=', $param ) ); // Adiciona um filtro
				}
				$sql->setClausula ( $clausula ); // Define a cláusula do sql
				break;
			
			// Deleta um obj de acordo com o id na cláusula IN
			case 2 : // Boa prática: onde é chamada ---> Classe.class.php->funãoquechama()
				$sql->setColunaValor ( 'atributo', $aux ['atributo'] );
				$clausula = new TClausula (); // Construão da condião WHERE
				$clausula->add ( new TFiltro ( 'id', 'IN', $aux ['ids'] ) ); // Adiciona um filtro
				$sql->setClausula ( $clausula ); // Define a cláusula do sql
				break;
		}
		// echo $sql->getInstrucao();
		$exec = new ExecuteSql ( $sql, get_class () );
	}
}

?>