<?php

// Classe de integração com o Moip
class Moip extends AppModelo {
    public $id;
    public $id_transacao_amepe;
    public $id_transacao_moip;
    public $cod_pagamento_moip;
    public $prod_serv;
    public $data_inicio;
    public $data_pagamento;
    public $data_vencimento;
    public $valor;
    public $tipo_pagamento;
    public $percent_moip;
    public $taxa_moip;
    public $link_pagamento;
    public $situacao;
    /**
     * Classe herdada de AppModelo.
     * Os atributos devem ser iguais ao da tabela no BD e sempre deve ter o atributo id.
     * Para chamar o repositório dessa classe: NomeClasse::BD(tipo,chave,aux). Ver em AppModelo.class.
     * @param int id do obj no BD para carregar o objeto na instância (facultativo)
     * @method get: Herdado de AppModelo - Retorna um atributo
     * @method set: Herdado de AppModelo - Define um atributo
     * @method getObj: Herdado de AppModelo - Retorna um obj do BD de acordo com o parâmetro (array).
     * @method setObj: Herdado de AppModelo -  Define todos os atributos de um objeto com os valores do parâmetro (array).
     * @method insert: Herdado de AppModelo - Insere um obj no BD
     * @method update: Herdado de AppModelo - Atualiza um obj no BD
     * @method delete: Herdado de AppModelo - Deleta um obj do BD
     * @method deleteAll: Herdado de AppModelo (static) - Deleta todos os objs do BD
     * @method BD (tipo, chave, aux): Herdado de AppModelo (static) - Acessa o repositório do Obj (app.modelo->Repos[nome da classe].class.php)
     * Tipo: string select, update, insert ou delete
     * Chave: int número da query a ser usada no repositório
     * Aux: Variáveis passadas para utilização na query (No caso de insert = obj)
     *
     */
    function __construct($ambiente = 'teste') {
    	parent::__construct(null);
    
        $err = new Msg();
        include Glb::$CONFIG['RAIZGLB'] . '/framework/plugins/moip/setMoip.php';
        
        $this->token = $tokenMoip;
        $this->keyMoip = $keyMoip;
        $this->base = $tokenMoip . ':' . $keyMoip;
        $this->auth = base64_encode($this->base);
        $this->headerMoip[] = 'Authorization: Basic ' . $this->auth;
        $this->urlRetorno = $urlRetornoMoip;
        $this->urlNotidicacao = $urlNotificacaoMoip;
        $this->urlEnvia = $urlEnviaMoip;
        $this->link_pagamento = $linkPagMoip;
        $this->taxa_moip = $taxa_moip;
        $this->percent_moip_boleto = $percent_moip_boleto;
        $this->percent_moip_cartao = $percent_moip_cartao;
        $this->diasVencMoip = $diasVencMoip;
        $this->cod_seg = $cod_seg;
    }

    // Define o xml a ser enviado
    function setXml($chave, $aux = false) {
        switch ($chave) {
            case 'hosp': // Define o xml para registro de hospedagem
                // Recebendo variável auxiliar
                // $hosp = $aux['hospedagem'];
            	$idSede = $aux['idSede'];
            	if($aux['idAss'] == 0) {
            		$fone = $aux['fone'];
            		$nome = $aux['nome'];
            		$email = $aux['email'];
            		$endereco = $aux['endereco'];
            		$numero = $aux['fone'];
            		$bairro = $aux['bairro'];
            		$cidade = $aux['cidade'];
            		$uf = $aux['uf'];
            	} else {
            		$ass = new Associado($aux['idAss']);
            		// Formatando o fone do associado para o moip
            		$fone = str_replace('(', '', $ass->get('celular1'));
            		$fone = str_replace(')', '', $fone);
            		$fone = str_replace(' ', '', $fone);
            		$fone = str_replace('-', '', $fone);
            		$fone = str_replace('.', '', $fone);
            		if(strlen($fone) < 10) {
            			$fone = '81'.$fone;
            		}
            		$nome = $ass->get('nome');
            		$email = $ass->get('email1');
            		$endereco = $ass->get('endereco_res') . ' ' . $ass->get('endereco_res_compl');
            		$numero = $ass->get('numero_res');
            		$bairro = $ass->get('bairro_res');
            		$cidade = $ass->get('cidade_res');
            		$uf = $ass->get('uf_res');
            	}
            	
				
                // Buscando a sequencia do id de transação
                $seq = new Sequence('id_transacao_moip_amepe');
                $seq_id_transacao_amepe = $seq->get('cont');
                
                // Incrementando em +1 a sequencia do id de transação
                $seq->increment(1);
                $this->id = $seq_id_transacao_amepe;
                $this->id_transacao_amepe = $seq_id_transacao_amepe . '||' . $aux['idAss'] . '||' . $idSede; 
                $digito_verificador = '01'; // Código identificador para a reserva de hospedagem
                
                
                // Definindo a data de vencimento do pagamento
                $dataVenc = new DataVenc();
                $dataDados = $dataVenc->getData($this->diasVencMoip);
                $this->data_vencimento = $dataDados['data']; // Classe api de cálculo de data incluindo feriados e fds
                
                // Calculando e formatando o preço pago pelo anunciante
                $valor = $aux['valor'];
                $this->valor = number_format($valor, 2, '.', ''); // 1234.56
                
                
                $this->prod_serv = ('AMEPE - Hospedagem');
                $this->id_transacao_amepe = $this->id_transacao_amepe .  $this->cod_seg . $digito_verificador;

                $this->xml = '<EnviarInstrucao>';
                $this->xml = $this->xml . '<InstrucaoUnica>';
                $this->xml = $this->xml . '<Razao>AMEPE - Hospedagem</Razao>';
                $this->xml = $this->xml . '<IdProprio>' . $this->id_transacao_amepe . '</IdProprio>';
                $this->xml = $this->xml . '<FormasPagamento>';
                if($aux['boleto'] == 1) {
                	$this->xml = $this->xml . '<FormaPagamento>BoletoBancario</FormaPagamento>';
                }
                $this->xml = $this->xml . '<FormaPagamento>CartaoCredito</FormaPagamento>';
                $this->xml = $this->xml . '<FormaPagamento>CarteiraMoIP</FormaPagamento>';
                $this->xml = $this->xml . '</FormasPagamento>';
                $this->xml = $this->xml . '<DataVencimento>' . $this->data_vencimento . 'T12:00:00.703-03:00</DataVencimento>'; // <DataVencimento>2011-05-02T12:00:00.703-03:00</DataVencimento>
                $this->xml = $this->xml . '<Valores>';
                $this->xml = $this->xml . '<Valor moeda=\'BRL\'>' . $this->valor . '</Valor>';
                $this->xml = $this->xml . '</Valores>';
                $this->xml = $this->xml . '<Boleto>';
                $this->xml = $this->xml . '<DiasExpiracao Tipo=\'Corridos\'>'.$dataDados['dias'].'</DiasExpiracao>';
                $this->xml = $this->xml . '<Instrucao1>Nao receber apos o vencimento</Instrucao1>';
                #$this->xml = $this->xml . '<URLLogo>http://www.----.com/imagens/logo.png</URLLogo>';
                $this->xml = $this->xml . '</Boleto>';
                $this->xml = $this->xml . '<Mensagens>';
                $this->xml = $this->xml . '<Mensagem></Mensagem>';
                $this->xml = $this->xml . '</Mensagens>';
                $this->xml = $this->xml . '<Pagador>';
                $this->xml = $this->xml . '<Nome>' . $nome . '</Nome>';
                $this->xml = $this->xml . '<Email>' . $email . '</Email>';
                $this->xml = $this->xml . '<EnderecoCobranca>';
                $this->xml = $this->xml . '<Logradouro>' . $endereco . '</Logradouro>';
                $this->xml = $this->xml . '<Numero>' . $numero . '</Numero>';
                $this->xml = $this->xml . '<Bairro>' . $bairro . '</Bairro>';
                $this->xml = $this->xml . '<Cidade>' . $cidade . '</Cidade>';
                $this->xml = $this->xml . '<Estado>' . $uf . '</Estado>';
                $this->xml = $this->xml . '<Pais>BRA</Pais>';
                $this->xml = $this->xml . '<CEP>00000-000</CEP>';
                $this->xml = $this->xml . '<TelefoneFixo>' . $fone . '</TelefoneFixo>';
                $this->xml = $this->xml . '</EnderecoCobranca>';
                $this->xml = $this->xml . '</Pagador>';
                $this->xml = $this->xml . '<URLRetorno>' . $this->urlRetorno . '</URLRetorno>';
                $this->xml = $this->xml . '<URLNotificacao>' . $this->urlNotidicacao . '</URLNotificacao>';
                $this->xml = $this->xml . '</InstrucaoUnica>';
                $this->xml = $this->xml . '</EnviarInstrucao>';

                break;
        }
    }

    // Define um atributo
    function enviar() {
    	//phpinfo();
        $xml = ($this->xml);
        $user = '';
        $passwd = '';
        $curl = curl_init();
        curl_setopt_array( $curl, array(
        	CURLOPT_URL => $this->urlEnvia,
        	CURLOPT_HTTPHEADER => $this->headerMoip,
        	CURLOPT_USERPWD => $user . ':' . $passwd,
        	CURLOPT_SSL_VERIFYPEER => false,
        	//CURLOPT_SSL_VERIFYHOST => 2,
        	CURLOPT_SSLVERSION => 1,
        	CURLOPT_SSL_CIPHER_LIST => 'TLSv1',
        	CURLOPT_USERAGENT => 'Mozilla/4.0',
        	CURLOPT_POST => true,
        	CURLOPT_POSTFIELDS => $xml,
        	CURLOPT_RETURNTRANSFER => true,
        	CURLINFO_HEADER_OUT => true
        ));
        $xmlRetorno = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        echo $xmlRetorno . ' - ' . $err; 
        $objXml = simplexml_load_string($xmlRetorno);
        $this->status = strtoupper($objXml->Resposta->Status);
        $this->id_transacao_moip = $objXml->Resposta->ID;
        $this->tokenPag = $objXml->Resposta->Token;

        if ($this->status == 'SUCESSO') {
            $this->link_pagamento = $this->link_pagamento . $this->tokenPag;
        } else {
            $err = new Msg();
            $err->dieProcess('Problemas na requisição de pagamento. Por favor contate informatica@'.Glb::$CONFIG['DOMINIO']);
        }
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

?>
