<?php
/**
 * Classe de controle
 * Possui todas as funções de controle de dados da view correspondente.
 * Herda de AppCrl
 */
class CtrlHome extends AppCtrl {
	function __construct() {
		parent::__construct();
	}
	
	function showVsAssAniv($mes) {
		return new VsAssociadosAniversariantes($mes);
	}
	
	function showVsColAniv($mes) {
		return new VsColaboradoresAniversariantes($mes);
	}
	
	function showVsConvenios($id) {
		return new VsConvenios($id);
	}
	
	function showVsInformativo($id, $pdf) {
		return new VsInformativo($id, $pdf);
	}
	
	function showVsArmasMunicao() {
		return new VsArmasMunicao();
	}
	
	function showVsArtigosJudiciario($id) {
		return new VsArtigosJudiciario($id);
	}
	
	function showVsAtosPresidencia() {
		return new VsAtosPresidencia();
	}
	
	function showVsCoordenadorias() {
		return new VsCoordenadorias();
	}
	
	function showVsDiretoria($ano) {
		return new VsDiretoria($ano);
	}
	
	function showVsListaAntiguidade() {
		return new VsListaAntiguidade();
	}
	
	function showVsOficiosPresidencia($ano) {
		return new VsOficiosPresidencia($ano);
	}
	
	function showVsPortarias() {
		return new VsPortarias();
	}
	
	function showVsProcedimentosAmepeCnj() {
		return new VsProcedimentosAmepeCnj();
	}
	
	function showVsRelacaoJuizes($tipo) {
		return new VsRelacaoJuizes($tipo);
	}
	
	function showVsResolucoesAmepe() {
		return new VsResolucoesAmepe();
	}
	
	function showVsResolucoesCnj() {
		return new VsResolucoesCnj();
	}
	
	function showVsTabelaSubstituicaoAutomatica() {
		return new VsTabelaSubstituicaoAutomatica();
	}
	
	function showVsAudio($idmidia) {
		return new VsAudio($idmidia);
	}
	
	function showVsVideo() {
		return new VsVideo();
	}
	
	function showVsNoticia($idnoticia) {
		return new VsNoticia($idnoticia);
	}
	
	function showVsNoticiaPdf($idnoticia) {
		return new VsNoticiaPdf($idnoticia);
	}
	
	function showVsAmepeMidia($ano) {
		return new VsAmepeMidia($ano);
	}
	
	function showVsAmepeMidiaLoad($idmidia) {
		return new VsAmepeMidiaLoad($idmidia);
	}
	
	function showVsCadernosAmepe() {
		return new VsCadernosAmepe();
	}
	
	function showVsJudicatura($ano) {
		return new VsJudicatura($ano);
	}
	
	function showVsLiteraturaJuridica() {
		return new VsLiteraturaJuridica();
	}
	
	function showVsLiteraturaUniversal() {
		return new VsLiteraturaUniversal();
	}
	
	function showVsCampestre() {
		return new VsCampestre();
	}
	
	function showVsPaulaBaptista() {
		return new VsPaulaBaptista();
	}
	
	function showVsRodolfoAureliano() {
		return new VsRodolfoAureliano();
	}
	
	function showVsSocial() {
		return new VsSocial();
	}
	
	function showVsSedesOutrasAssociacoes() {
		return new VsSedesOutrasAssociacoes();
	}
	
	function showVsClassificados() {
		return new VsClassificados();
	}
	
	function showVsFeriados() {
		return new VsFeriados();
	}
	
	function showVsPeritosCategorias() {
		return new VsPeritosCategorias();
	}
	
	function showVsPrestacaoContas($ano) {
		return new VsPrestacaoContas($ano);
	}
}
?>
