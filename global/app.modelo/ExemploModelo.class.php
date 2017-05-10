<?php
/**
 * Classe herdada de AppModelo.
 * Os atributos devem ser iguais ao da tabela no BD e sempre deve ter o atributo id.
 * Para chamar o repositório dessa classe: NomeClasse::BD(tipo,chave,aux). Ver em AppModelo.class.
 * Tipo: string select, update, insert ou delete
 * Chave: int número da query a ser usada no repositório
 * Aux: Variáveis passadas para utiliza��o na query (No caso de insert = obj)
 * @param int id do obj no BD para carregar o objeto na instância (facultativo)
 * @method get: Herdado de AppModelo - Retorna um atributo
 * @method set: Herdado de AppModelo - Define um atributo
 * @method getObj: Herdado de AppModelo - Retorna um obj do BD de acordo com o par�metro (array).
 * @method setObj: Herdado de AppModelo -  Define todos os atributos de um objeto com os valores do par�metro (array).
 * @method insert: Herdado de AppModelo - Insere um obj no BD
 * @method update: Herdado de AppModelo - Atualiza um obj no BD
 * @method delete: Herdado de AppModelo - Deleta um obj do BD
 * @method deleteAll: Herdado de AppModelo (static) - Deleta todos os objs do BD
 * @method BD (tipo, chave, aux): Herdado de AppModelo (static) - Acessa o repositório do Obj (app.modelo->Repos[nome da classe].class.php)
 * @author Diogo (d.bezerra@yahoo.com.br)
 *
 */
class ExemploModelo extends AppModelo {
	public $id;
	public $nome;
	public $fone;
	public $email;
	
	
	function __construct($id = NULL) {
		parent::__construct ( $id );

	}
	
	# Exemplo
	function enviaEmail($id, $email) {
		# Cria um objeto com os atributos obtidos do banco de dados
		$exMod = new ExemploModelo($id);
		
		#Cria um objeto "vazio"
		$exMod = new ExemploModelo();
		
		# Definindo atributo
		$exMod->set('nome', '[string nome]');
		$exMod->set('email', $email);
		
		# Obtendo valores dos atributos
		$destino = $exMod->get('email');
		
		# Instanciando o plugin Email (framework/plugins/email)
		$e = new Email($remetente, $destino, $assunto, $texto);
		$e->enviar(); // envia o email
		
		# Atualizando objeto no banco
		$exMod->update(); // Somente os atributos definidos serão atualizados
		
		# Inserindo um objeto no banco
		$exMod->set('nome', $novo_valor);
		$exMod->set('fone', $novo_valor);
		$exMod->set('email', $novo_valor);
		$exMod->insert();
		
		# O repositório pode ser acessado diretamente para executar qualquer query
		$rs = ExemploModelo::BD('select', 0, array('nome'=>$nome)); // Retorna objetos cujo nome = $nome
		// Retona falso se não houver resultados ou o fetch da consulta
		if($rs) {
			foreach ($rs as $obj) {
				$nome = $obj['nome'];
				$fone = $obj['fone'];
				$email = $obj['email'];
				
				# Os atributos do objeto modelo podem ser definidos 
				# diretamente através do array retornado
				$exMod->setObj($obj);
			}
		}
	}
}

?>
