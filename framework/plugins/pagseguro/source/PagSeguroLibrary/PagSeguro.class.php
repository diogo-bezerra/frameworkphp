<?php
class PagSeguro extends AppModelo {
	public $id;
	public $id_transacao_amepe;
	public $id_transacao_pagseg;
	public $cod_pagamento_pagseg;
	public $prod_serv;
	public $data_inicio;
	public $data_pagamento;
	public $data_vencimento;
	public $valor;
	public $tipo_pagamento;
	public $percent_pagseg;
	public $taxa_pagseg;
	public $link_pagamento;
	public $situacao;
	public $status_pagseg;
	
    function __construct() {
    	include_once 'PagSeguroLibrary.php';
    	$this->request = new PagSeguroPaymentRequest();
    }
    
    function getRequest() {
    	return $this->request;
    }
    
    /**
     * Criptografa o id da compra para ser usado no link de cancelamento de compra e no link de pagamento.
     *
     * @param string $id
     * @return string|boolean
     */
    public function __criptId($id = null) {
    	if (!is_null($id)) {
    		$id1 = substr($id, 0, 10);
    		$id2 = substr($id, 10, 30);
    		$criptId1 = sha1($id1);
    		$criptId2 = sha1($id2 . substr($id, 0, 10));
    		return $criptId1 . $criptId2;
    	} else {
    		return FALSE;
    	}
    }
}


