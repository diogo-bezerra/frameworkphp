<?php

// Usada para cria��o e manipula��o de instru��es DELETE
final class TSqlDelete extends TSqlInstrucao {

    // Retorna a instrun��o montada
    public function getInstrucao() {
        $this->sql = "DELETE FROM {$this->tabela}";
        // Retorna a cl�usula WHERE do objeto $this->criterio
        if ($this->clausula) {
            $expressao = $this->clausula->dump();
            if ($expressao) {
                $this->sql .= ' WHERE ' . $expressao;
            }
        }
        return $this->sql;
    }

}

?>