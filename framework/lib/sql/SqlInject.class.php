<?php

// Faz a verifica��o de valore para evitar Sql Inject
final class SqlInject {

    public $result;

    function __construct($valor) {
        if (!is_null($valor)) {
            $err = new Msg();
            $lista = array("--", "'", "drop ", "delete", "update ", '"', "/*", "*/"); // Par�metros proibidos 
            if (is_array($valor)) {
                foreach ($valor as $key => $conteudo) {
                    foreach ($lista as $listaKey => $listaConteudo) {
                        if (stripos($conteudo, $listaConteudo) !== false) {
                        	//echo('achou');
                        	$err->set('Erro','Erro: Inser��o de par�metro n�o permitido: ' . $listaConteudo);
                            $err->show();
                        }
                    }
                }
            } else {
                if (is_object($valor)) {
                    
                } else {
                    foreach ($lista as $listaKey => $listaConteudo) {
                        if (stripos($valor, $listaConteudo) !== false) {
                            $err->set('Erro','Erro: Inserção de parâmetro não permitido 2: ' . $listaConteudo);
                            $err->show();
                        }
                    }
                }
            }
        }
    }

}

?>