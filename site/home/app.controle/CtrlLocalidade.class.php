<?php
/**
 * Classe de controle
 * Possui todas as funções de controle de dados da view correspondente.
 * Herda de AppCrl
 */
class CtrlLocalidade extends AppCtrl {
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * Gera um select de UFs que carrega outro select de cidades quando selecionado
	 * @param (String) $id: id do select.
	 * @param (int) $largura.
	 * @param (int) $fonte.
	 * @param (String) $targetCid: Container onde será carregado o select de cidades. Se falso não carrega cidades.
	 * @param (String) $targetBairro: Container onde será carregado o select de bairros. Se falso não carrega bairros.
	 * @param string $obrg: Para indicar que são campos obrigatórios inserindo a classe obrg na tag.
	 * @return Html do Select.
	 */
	public function getSelectUfLoad($sufixo, $largura, $fonte = 14, $targetCid, $targetBairro, $obrg = false, $ufSelected = false) {
		$localidade = new Localidade ();
		$ufs = $localidade->getUfs ();
		// $selectUfsCustom = new SelectCustom($id, $largura, $fonte);
		// $selectUfsCustom->setOpt(0, '', 'Selecione o Estado', TRUE);
		$selectUfs = new DSelect('select_uf'.$sufixo, $largura );
		$selectUfs->setOption('Selecione o Estado', 0, TRUE );
		foreach ( $ufs as $uf ) {
			$sel = false;
			if($ufSelected == $uf ['uf']) {
				$sel = true;
			}
			$selectUfs->setOption ($uf ['uf'], $uf ['uf'], $sel);
		}
		
		if ($targetCid) {
			if (! $targetBairro) {
				$targetBairro = 'false';
			}
			$gets = array (
				'sufixo' => $sufixo,
				'largura' => $largura,
				'fonte' => $fonte,
				'targetBairro' => $targetBairro,
				'uf' => 'this.value',
				'obrg' => 'true',
				'idCidade' => 'false' 
			);
			
			$ctrlget = AppCtrl::callCtrlPost ( $targetCid, 'CtrlLocalidade', 'getSelectCidadeLoad', $gets, 'Buscando Cidades...');
			$selectUfs->onchange = $ctrlget . "try{document.getElementById('" . $targetBairro . "').innerHTML = '';}catch(e){}";
		}
		if ($obrg) {
			$selectUfs->class = 'obrg';
		}
		return $selectUfs;
	}
	
	/**
	 * Gera um select de Cidades que carrega outro select de bairros quando selecionado.
	 * @param (String) $id: id do select.
	 * @param (int) $largura.
	 * @param (int) $fonte
	 * @param (String) $targetBairro: Container onde será carregado o select de bairros. Se falso não carrega bairros.
	 * @param (String) $uf: Nome da uf.
	 * @param string $obrg: Para indicar que são campos obrigatórios inserindo a classe obrg na tag.
	 * @param string $idCidade: Id da cidade para carregar os bairros
	 * @return Html do Select.
	 */
	public function getSelectCidadeLoad($sufixo, $largura, $fonte = 14, $targetBairro, $uf, $obrg = false, $idCidade = 'false') {
		$localidade = new Localidade ();
		$cids = $localidade->getCidadesUfs ( $uf );
		// $selectCidades = new SelectCustom ( $id, $largura, $fonte );
		$selectCidades = new DSelect('select_cidade'.$sufixo);
		if ($idCidade != 'false') {
			$selectCidades->setOption('Selecione a Cidade', '' );
			foreach ( $cids as $cid ) {
				$sel = FALSE;
				if ($idCidade == $cid ['nome']) {
					$sel = TRUE;
				}
				$selectCidades->setOption($cid ['nome'], $cid ['id'], $sel);
			}
		} else {
			$selectCidades->setOption('Selecione a Cidade', '', TRUE);
			foreach ( $cids as $cid ) {
				$selectCidades->setOption($cid ['nome'], $cid ['id']);
			}
		}
		if ($targetBairro != 'false') {
				$selectCidades->onchange = AppCtrl::callCtrlPost ( $targetBairro, 'CtrlLocalidade', 'getSelectBairroLoad', array (
					'sufixo' => $sufixo,
					'largura' => $largura,
					'fonte' => $fonte,
					'cidade' => 'this.value',
					'obrg' => 'true',
					'idBairro' => 'false' 
				), 'Buscando Bairros...' );
		}
		if ($obrg) {
			$selectCidades->class = 'obrg';
		}
		$selectCidades->show ();
	}
	
	/**
	 * Gera um select de bairros.
	 * @param (String) $id: id do select.
	 * @param (int) $largura.
	 * @param (int) $fonte
	 * @param (String) $cidade: Id da cidade.
	 * @param string $obrg: Para indicar que são campos obrigatórios inserindo a classe obrg na tag.
	 * @param string $idBairro: Id do bairro para selecionar o option no início se necessário
	 * @return Html do Select.
	 */
	public function getSelectBairroLoad($sufixo, $largura, $fonte = 14, $cidade, $obrg = false, $idBairro = 'false') {
		$localidade = new Localidade ();
		$bairros = $localidade->getBairrosCidades ( $cidade );
		$selectBairros = new DSelect ('select_bairro'.$sufixo);
		if ($idBairro != 'false') {
			$selectBairros->setOption('Selecione o Bairro', '', TRUE);
			foreach ( $bairros as $bairro ) {
				$sel = FALSE;
				if ($idBairro == $bairro ['nome']) {
					$sel = TRUE;
				}
				$selectBairros->setOption($bairro ['nome'], $bairro ['nome'], $sel );
			}
		} else {
			$selectBairros->setOption('Selecione o Bairro', '', TRUE );
			foreach ( $bairros as $bairro ) {
				$selectBairros->setOption($bairro ['nome'], $bairro ['nome'], '');
			}
		}
		if ($obrg) {
			$selectBairros->class = 'obrg';
		}
		$selectBairros->show ();
	}
}

?>