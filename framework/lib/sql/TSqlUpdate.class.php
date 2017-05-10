<?php

// Usada para criação e manipulação de instruções UPDATE
final class TSqlUpdate extends TSqlInstrucao {

    // Define o valor para cada couna a ser alterada. @param(coluna da tabela, valor definido para a coluna)
    public function setColunaValor($coluna, $valor) {
        // Monta array indexado pelo nome da coluna
        // Caso valor seja string
        if (is_string($valor)) {
            // Adiciona \ em aspas
            $valor = addslashes($valor);
            // Adiciona aspas
            $this->arrayColuna[$coluna] = "'$valor'";
            // Caso valor seja boleano
        } else if (is_bool($valor)) {
            // Define true ou false
            $this->arrayColuna[$coluna] = $value ? 'TRUE' : 'FALSE';
            // Se valor tiver conte�do
        } else if (isset($valor)) {
            $this->arrayColuna[$coluna] = $valor;
        } else {
            // Se for NULL
            $this->arrayColuna[$coluna] = 'NULL';
        }
    }
    
    public function setColunaIncrement($coluna, $incremento) {
    	$this->arrayColuna[$coluna] = $coluna." + ".$incremento;
    }

    // Retorna a instrução montada
    public function getInstrucao() {
        $this->sql = "UPDATE {$this->tabela}";
        // Monta os pares coluna = valor, ...
        if ($this->arrayColuna) {
            foreach ($this->arrayColuna as $coluna => $valor) {
                $set[] = "{$coluna} = {$valor}";
            }
        }
        $this->sql .= ' SET ' . implode(', ', $set);

        // Retorna a cláusula WHERE do objeto $this->criterio
        if ($this->clausula) {
            $this->sql .= ' WHERE ' . $this->clausula->dump();
        }
        return $this->sql;
    }

}

?>