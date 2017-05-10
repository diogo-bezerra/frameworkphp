<?php
include_once ("../../config/setGlbVars.php");
include_once '../../../global/app.controle/Global.class.php';
$global = new Glb ();
setlocale(LC_ALL, 'pt_BR.utf8');

##############################################
########### VARIÁVEIS & POSTS ################
##############################################
$err = new Msg();
// Pega os valores retornados pelo MoIP    18||17||1yrb75rhbdy01
@$id_transacao = $_POST['id_transacao'];#.'706||238||1rt548idnf001';
//@$id_transacao = $_POST['id_transacao'] = '706||238||1rt548idnf001';
@$valor = $_POST['valor'];##.'15000';
@$status_pagamento = $_POST['status_pagamento'];#.'1';
@$cod_pagamento_moip = $_POST['cod_moip'];#.'201507311216196770000008492284';
@$forma_pagamento = $_POST['forma_pagamento'];
@$tipo_pagamento = $_POST['tipo_pagamento'];#.'BoletoBancario';
@$email_consumidor = $_POST['email_consumidor'];#.'informatica2@amepe.com.br';

$resposta = FALSE;
###############################################
############## OBJETOS & FUNÇÕES ##############
###############################################
$moip = new Moip();

$taxa_moip = $moip->get('taxa_moip'); // Define o valor da taxa de transação do Moip
// Verifica o tipo de pagamento e define a percentagem cobrada pelo Moip
$percent_moip = 0;
switch ($tipo_pagamento) {
    case "BoletoBancario":
        $percent_moip = $moip->get('percent_moip_boleto');
        break;
    case "CartaDeCredito":
        $percent_moip = $moip->get('percent_moip_cartao');
        break;
}

$digi_verif = substr($id_transacao, -2);
$cod_seg = substr(substr($id_transacao, -12), 0, 10);
$id_transacao_array = explode('||', str_replace(substr($id_transacao, -12), "", $id_transacao));
$id_transacao_mc = $id_transacao;
$idLance = $id_transacao_array[0];
$idAnc = $id_transacao_array[1];
$idMidia = $id_transacao_array[2];
$valor_pago = substr($valor, 0, -2) . "." . substr($valor, -2);

$msg_retorno = '';
$status_pagamento_tipos = array(
    '1' => 'autorizado',
    '2' => 'iniciado',
    '3' => 'boleto_impresso',
    '4' => 'concluido',
    '5' => 'cancelado',
    '6' => 'em análise',
    '7' => 'estornado'
);

if ($cod_seg == 'rt548idnf0') {
    switch ($digi_verif) {
        case '01': // Retorno de pagamento de Hospedagem
        	switch ($status_pagamento) {
        		case 1:
        			// Atualiza tb_moip
        			$auxTbMoip = array(
        				'cod_pagamento_moip' => $cod_pagamento_moip,
        				'data_pagamento' => Tdata::TdataHojeMysql(),
        				'tipo_pagamento' => $tipo_pagamento,
        				'percent_moip' => $percent_moip,
        				'situacao' => 'PAG',
        				'status_moip' => $status_pagamento_tipos[$status_pagamento],
        				'id_transacao_amepe' => $id_transacao,
        				'msg_retorno' => $msg_retorno
        			);
        			Moip::BD('update', 1, $auxTbMoip);
        			
        			// Atualiza tb_hospedagem
        			Hospedagem::BD('update', 3, $auxTbMoip);
        			$resposta = TRUE;
        		break;
        		
        		case 4:
        			// Atualiza tb_moip
        			$auxTbMoip = array(
        			'cod_pagamento_moip' => $cod_pagamento_moip,
        			'data_pagamento' => Tdata::TdataHojeMysql(),
        			'tipo_pagamento' => $tipo_pagamento,
        			'percent_moip' => $percent_moip,
        			'situacao' => 'PAG',
        			'status_moip' => $status_pagamento_tipos[$status_pagamento],
        			'id_transacao_amepe' => $id_transacao,
        			'msg_retorno' => $msg_retorno
        			);
        			Moip::BD('update', 1, $auxTbMoip);
        			 
        			// Atualiza tb_hospedagem
        			Hospedagem::BD('update', 3, $auxTbMoip);
        			$resposta = TRUE;
        			break;
        		
        		default:
        			$auxTbMoip = array(
        				'status_moip' => $status_pagamento_tipos[$status_pagamento],
        				'id_transacao_amepe' => $id_transacao
        			);
        			Moip::BD('update', 2, $auxTbMoip);
        			$resposta = TRUE;
        		break;
        	}
		break;
    }
} else { // Se o código de segurança estiver errado
    $resposta = FALSE; // Define a resposta no fim do script
    $msg_retorno = '<br /><br /><br />Erro de retorno: Código de segurança não confere.';
    // Atualizando dados de tb_moip
    $auxTbMoip = array('id_transacao_mc' => $id_transacao, 'msg_retorno' => $msg_retorno);
    //Moip::BD('update', 3, $auxTbMoip);
    //$email = new Email('Mc - Moip', 'suporte@' . $RAIZGLB, 'd.bezerra@yahoo.com.br', 'Problema de retorno do moip.', '', utf8_encode($msg_retorno)); // Instancia tipo email
    //$email->enviar(); // Envia o email
}

if ($resposta) {
    header("HTTP/1.0 200 OK");
} else {
    header("HTTP/1.0 404 Not Found");
}
echo $msg_retorno;
?>
