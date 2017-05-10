<?php

// Define os m�todos em comum dentre todas as instru��es
abstract class TSqlInstrucao {

    protected $sql; //Instru��o sql
    protected $clausula; //objeto criterio

    // Define a tabela que ser� usada pelo sql. @param(nome da tabela)

    final public function setTabela($tabela) {
        $this->tabela = $tabela;
    }

    // Retorna a tabela que ser� usada pelo sql
    final public function getTabela() {
        return $this->tabela;
    }

    // Define o crit�rio de sele��o (WHERE) atrav�s de um objeto TClausula. @param(objeto TClausula)
    public function setClausula(TClausula $clausula) {
        $this->clausula = $clausula;
    }

    // Retorna a instru��o montada. Por ser abstrata deve ser implementada por todas as classes filhas, cada uma de sua forma
    abstract function getInstrucao();
}

?>