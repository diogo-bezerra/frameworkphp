<?php

// Classe de interface para definiчуo de filtros de select
class TFiltro extends TExpressao {

    private $variavel;
    private $operador;
    private $valor;

    public function __construct($variavel, $operador = '', $valor = '') {
        $this->variavel = $variavel;
        $this->operador = $operador;

        // Transforma o valor atravщs de regras antes de atribuir para poder ficar compatэvel com o banco de dados
        $this->valor = $this->transform($valor);
    }

    // Transforma o valor atravщs de regras antes de atribuir para poder ficar compatэvel com o banco de dados
    private function transform($valor) {
        // Se o valor for um array
        if (is_array($valor)) {
            // Percorre o array
            foreach ($valor as $x) {
                // Se for inteiro
                if (is_integer($x)) {
                    $temp[] = $x;
                    // Se for string
                } else if (is_string($x)) {
                    // Adiciona aspas
                    $temp[] = "'$x'";
                }
            }
            // Converte o array em string separada por vэrgula
            $result = '(' . implode(',', $temp) . ')';
            // Se $valor nуo for passado utiliza apenas $variavel como parтmetro retornado, mantendo $valor e $operador vazios.
            // Usado quando funчѕes MySql (ex: ...WHERE isnull(campo)...) sуo passadas como condiчуo.
        } else if (is_integer($valor)) {
            $result = $valor;
        } else if (empty($valor)) {
            //$this->operador = '';
            $result = "'$valor'";
            // Se for string 
        } else if (is_string($valor)) {
            // Adiciona aspas
            $result = "'$valor'";
            // Se o operador for IN adiciona parъnteses e retira as aspas (inteiro)
            if (strtoupper($this->operador) == 'IN') {
                $result = "($valor)";
            }
            // Se for nulo
        } else if (is_null($valor)) {
            $result = 'NULL';
            // Se for booleano
        } else if (is_bool($valor)) {
            $result = $valor ? 'TRUE' : 'FALSE';
        } else {
            $result = $valor;
        }
        // Retorna o valor
        return $result;
    }

    // Retorna a expressуo em forma de string
    public function dump() {
        return "{$this->variavel} {$this->operador} {$this->valor}";
    }

}

?>