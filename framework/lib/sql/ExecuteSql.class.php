<?php
/**
 * Executa uma query
 * @author Diogo (d.bezerra@yahoo.com.br)
 */
final class ExecuteSql {

    public $result;

    function __construct($sql, $className = '') {
        // Executa a query e retorna o resultado
        try {
            // Abre conexão
            $Tconn = new Conexao();
            $Tconn->abrir();
            $sel = true;
            // Se for um objeto TSqlSelect, TSqlInsert, TSqlUpdate ou TSqlDelete
            if (is_object($sql)) { // Se for um objeto
            	$sql = $sql->getInstrucao();
            } 
            
            # echo $sql->getInstrucao();
            // Se não é um SELECT não retorna resultado, apenas executa
            if(strpos($sql,'SELECT') === false){
            	$sel = false;
            }
            
			// echo $sql;
            $this->result = $Tconn->get('conn')->query($sql);
            
            // Se é um SELECT retorna o fetch do Obj PDO (array) ou False se não houver resultado
            if($sel){
                $rs = $this->result->fetchall(PDO::FETCH_ASSOC);
                if(count($rs) <= 0){
                    $this->result = FALSE;
                }else{
                    $this->result = $rs; 
                    //print_r($rs);
                }
            }
            // Fecha conexão
            $Tconn->fechar();
            // Exibe mensagem de erro
        } catch (PDOException $e) {
            $err = new Msg();
            $err->set('Erro ExecuteSql','<br />Em ' . $className.$e);
            $err->show();
        }
    }
}

?>