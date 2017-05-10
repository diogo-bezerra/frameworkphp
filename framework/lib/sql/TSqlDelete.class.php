<?php

// Usada para criaчуo e manipulaчуo de instruчѕes DELETE
final class TSqlDelete extends TSqlInstrucao {

    // Retorna a instrunчуo montada
    public function getInstrucao() {
        $this->sql = "DELETE FROM {$this->tabela}";
        // Retorna a clсusula WHERE do objeto $this->criterio
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