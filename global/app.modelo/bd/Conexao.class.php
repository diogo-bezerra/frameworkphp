<?php
/**
 * Classe de gerenciamento de conexões com banco dados
 * @property (PDO obj) conn
 * @property (string) tipo de banco (mysql, oracle, sqlite)
 * @author Diogo (d.bezerra@yahoo.com.br)
 *
 */
final class Conexao {

	private $conn;
	private $tipo;

	// Retorna um atributo
	function get($nome_atributo) {
		return $this->$nome_atributo;
	}

	// Define um atributo
	function set($nome_atributo, $valor) {
		$this->$nome_atributo = $valor;
	}

	// Recebe o nome do banco de dados e instancia o objeto PDO. @param(nome do banco)
	function abrir($banco = 'mysql_0') {
		// Só abre se $conn estiver vazio para evitar duas conexões abertas
		if (empty($this->conn) or !isset($this->conn)) {
			$cont = 0;
			# Configurações do BD
			include Glb::$CONFIG['RAIZGLB'].'/framework/config/setBD.php';
			// Verifica qual banco será usado de acordo com o arquivo de configuração.
			switch ($tipo) {
				case 'mysql':
					$err = new Msg();
					try {
						$opcoes = array(
							PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'
						);

						$this->conn = new PDO("mysql:host={$servidor};port=3306;dbname={$banco};charset=utf8", $usuario, $senha, $opcoes);
					} catch (Exception $e) {
						# Cuidado ao mostrar a exceção $e em produção pois pode informar dados do banco
						//$err->set('Erro de conexão',$e);
						$err->set('Erro de conexão','');
						$err->show();
					}
					break;

				case 'oracle':

					break;

				case 'sqlite':
					$this->conn = new PDO("sqlite:" . Glb::$CONFIG['RAIZGLB'] . "app.modelo/bd/bd.db");
					$this->tipo = $tipo; /* @Bug Sqlite: Usado para identificar quando usa sqlite para
					ExecuteSql.class.php. Sqlite não permite a função PDO::rowCount() */
					break;
			}
			// Definindo o PDO para lançar exceções quando houver erros
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//echo 'abriu ';
		} else {
			// Conexão já aberta
		}
	}

	// Recebe o nome do banco de dados e instancia o objeto PDO. @param(nome do banco)
	function fechar() {
		//echo 'fechou ';
		$this->conn = NULL;
		# print '<br /><br />Conexão fechada<br /><br />';
		// return self::$conn;
	}

}