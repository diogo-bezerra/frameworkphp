<?php

// Necessita do arquivo global.php
// Classe que calcula datas de vencimento incluindo dias úteis e feriados para boletos e pagamentos
// @param inteiro dias de vencimento
// @return data em formato Y-m-d 
class DataVenc {

    function getData($diasVenc) {
        // Busca os dias de feriados
        $arq = Glb::$CONFIG['RAIZGLB'] . '/framework/plugins/dataVenc/feriados.config';
        if (file_exists($arq)) {
            // Lê um arquivo .config e retorna o valor das variáveis em um array.
            $feriados = parse_ini_file($arq);
        } else {
            // Erro se o arquivo não existir
            die('Arquivo de configuração ' . $arq . ' não encontrado.');
        }
        $data_hoje = new DateTime(); // Instancia o tipo DateTime (nativo do PHP). Data de hoje.
        // Formata a data
        // $data_hoje = $data_hoje->format('d/m/Y');
        // Somando os dias para a data de vencimento
        $data_vencimento = new DateTime(); // Instancia o tipo DateTime (nativo do PHP). Data de hoje.
        $data_vencimento->add(new DateInterval('P' . $diasVenc . 'D')); // Adiciona dias para a data de vencimento
        // Verificando qual é o dia
        $contDias = 0;
        while ($data_hoje <= $data_vencimento) {
            // Verificando qual é o dia
            $diaNome_vencimento = date("w", self::dataToTimestamp($data_hoje->format('d/m/Y')));
            
            // Se o dia for Sábado ou Domingo soma 1
            if ($diaNome_vencimento == 0 || $diaNome_vencimento == 6) {
                $data_vencimento = $data_vencimento->add(new DateInterval('P1D')); //dia + 1
                $contDias++;
            } else {
                // Senão vemos se este dia é FERIADO
                foreach ($feriados as $feriado) {
                    if ($feriado == $data_hoje->format('d/m/Y')) {
                        $data_vencimento = $data_vencimento->add(new DateInterval('P1D')); //dia + 1
                        //$data_hoje = $data_hoje->add(new DateInterval('P1D')); //dia + 1
                        $contDias++;
                    }
                }
            }
            $data_hoje = $data_hoje->add(new DateInterval('P1D')); //dia + 1
            $contDias++;
        }

        $data_vencimento = $data_vencimento->format('Y-m-d');
        $retorno = array('data'=>$data_vencimento,'dias'=>$contDias);
        return $retorno;
    }

    function dataToTimestamp($data) {
        $ano = substr($data, 6, 4);
        $mes = substr($data, 3, 2);
        $dia = substr($data, 0, 2);
        return mktime(0, 0, 0, $mes, $dia, $ano);
    }

    function CalculaDias($xDataInicial, $xDataFinal) {
        $time1 = dataToTimestamp($xDataInicial);
        $time2 = dataToTimestamp($xDataFinal);

        $tMaior = $time1 > $time2 ? $time1 : $time2;
        $tMenor = $time1 < $time2 ? $time1 : $time2;

        $diff = $tMaior - $tMenor;
        $numDias = $diff / 86400; //86400 é o número de segundos que 1 dia possui  
        return $numDias;
    }

}
?>

