<?php
header("access-control-allow-origin: https://sandbox.pagseguro.uol.com.br");
include_once ("../../config/setGlbVars.php");
include_once '../../../global/app.controle/Global.class.php';
$global = new Glb ();
setlocale(LC_ALL, 'pt_BR.utf8');

##############################################
########### VARIÁVEIS & POSTS ################
##############################################
$err = new Msg();
// Pega os valores retornados pelo MoIP    18||17||1yrb75rhbdy01
@$id_notificacao = $_POST['notificationCode'];

$resposta = FALSE;
###############################################
############## OBJETOS & FUNÇÕES ##############
###############################################
$pagseg = new PagSeguro();
$ins = '||';
foreach ($_POST as $key => $value) {
	$ins .= $key.'=>'.$value.'||';
}

try {
	$credentials = PagSeguroConfig::getAccountCredentials(); // getApplicationCredentials()
	$response = PagSeguroNotificationService::checkTransaction(
		$credentials,
		$id_notificacao
	);
	$id_transacao_amepe = $response->getReference();
	$id_transacao_pagseg = $response->getCode();
	$tipo_pagamento = $response->getPaymentMethod();
	$data_pagamento = $response->getDate();
	$status = $response->getStatus();
	$idAss = explode('||', str_replace(substr($id_transacao_amepe, -12), "", $id_transacao_amepe));
	$idAss = $idAss[1];
	$rs = Hospedagem::BD('select', 0, array('id_transacao_moip_amepe'=>$id_transacao_amepe)); 
	if($rs) {
		$idHosp = $rs[0]['id'];
	}
	$ass = new Associado($idAss);
	$tipoPg = array(
		1 => 'Cartão de Crédito',
		2 => 'Boleto',
		3 => 'Transferência Online',
		7 => 'Depósto Direto'
	);
	switch ($status->getValue()) {
		case 2: // Em análise
			PagSeguro::BD('update', 1, array(
				'id_transacao_amepe'=>$id_transacao_amepe,
				'id_transacao_pagseg'=>$id_transacao_pagseg,
				'tipo_pagamento'=>$tipoPg[$tipo_pagamento->getType()->getValue()],
				'data_pagamento'=>$data_pagamento,
				'situacao'=>'AP',
				'status_pagseg'=>'Em análise'
			));
		break;
		case 3: // Pago
			$aux = array(
				'id_transacao_amepe'=>$id_transacao_amepe,
				'id_transacao_pagseg'=>$id_transacao_pagseg,
				'tipo_pagamento'=>$tipoPg[$tipo_pagamento->getType()->getValue()],
				'data_pagamento'=>$data_pagamento,
				'situacao'=>'PG',
				'status_pagseg'=>'Pago'
			);
			PagSeguro::BD('update', 1, $aux);
			Hospedagem::BD('update', 3, $aux);
			// Enviando autorização
			$auxEmail = array(
				'idHosp' => $idHosp,
				'linkAut' => Glb::$CONFIG['URL']."/autorizacao-hospedagem/".$idHosp
			);
			$em = new Email('AMEPE', 'hospedagem@amepe.com.br', $ass->get('email1'), 'Confirmação de Pagamento de Hospedagem', 'autorizacaoHospPg',$auxEmail);
			$em->enviar();
		break;
		case 4: // Disponível
			PagSeguro::BD('update', 1, array(
				'id_transacao_amepe'=>$id_transacao_amepe,
				'id_transacao_pagseg'=>$id_transacao_pagseg,
				'tipo_pagamento'=>$tipoPg[$tipo_pagamento->getType()->getValue()],
				'data_pagamento'=>$data_pagamento,
				'situacao'=>'PG',
				'status_pagseg'=>'Disponível'
			));
		break;
		case 5: // Disputa
			PagSeguro::BD('update', 1, array(
				'id_transacao_amepe'=>$id_transacao_amepe,
				'id_transacao_pagseg'=>$id_transacao_pagseg,
				'tipo_pagamento'=>$tipoPg[$tipo_pagamento->getType()->getValue()],
				'data_pagamento'=>$data_pagamento,
				'situacao'=>'AP',
				'status_pagseg'=>'Disputa aberta'
			));
		break;
		case 6: // Reembolsado
			PagSeguro::BD('update', 1, array(
				'id_transacao_amepe'=>$id_transacao_amepe,
				'id_transacao_pagseg'=>$id_transacao_pagseg,
				'tipo_pagamento'=>$tipoPg[$tipo_pagamento->getType()->getValue()],
				'data_pagamento'=>$data_pagamento,
				'situacao'=>'CA',
				'status_pagseg'=>'Reembolsado'
			));
		break;
		case 7: // Cancelado
			$aux = array(
				'id_transacao_amepe'=>$id_transacao_amepe,
				'id_transacao_pagseg'=>$id_transacao_pagseg,
				'tipo_pagamento'=>$tipoPg[$tipo_pagamento->getType()->getValue()],
				'data_pagamento'=>$data_pagamento,
				'situacao'=>'CA',
				'status_pagseg'=>'Cancelado PagSeg'
			);
		
			PagSeguro::BD('update', 1, $aux);
			Hospedagem::BD('update', 3, $aux);
		break;
	}
} catch (PagSeguroServiceException $e) {
	die($e->getMessage());
}
?>
