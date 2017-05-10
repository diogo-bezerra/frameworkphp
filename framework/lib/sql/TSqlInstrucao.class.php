<?php

// Define os métodos em comum dentre todas as instruções
abstract class TSqlInstrucao {

    protected $sql; //Instrução sql
    protected $clausula; //objeto criterio

    // Define a tabela que será usada pelo sql. @param(nome da tabela)

    final public function setTabela($tabela) {
        $this->tabela = $tabela;
    }

    // Retorna a tabela que será usada pelo sql
    final public function getTabela() {
        return $this->tabela;
    }

    // Define o critério de seleção (WHERE) através de um objeto TClausula. @param(objeto TClausula)
    public function setClausula(TClausula $clausula) {
        $this->clausula = $clausula;
    }

    // Retorna a instrução montada. Por ser abstrata deve ser implementada por todas as classes filhas, cada uma de sua forma
    abstract function getInstrucao();
}

?>