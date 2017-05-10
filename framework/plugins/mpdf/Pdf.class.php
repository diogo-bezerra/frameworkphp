<?php
/**
 * 
 * @author Diogo Bezerra
 * Classe com funções básicas que utiliza o mpdf para criar PDFs
 *
 */
class Pdf {
	public $pdf;
	
	function __construct($html = false) {
		if($html) {
			$this->setHtml($html);
		}
	}
	
	/**
	 * Define o html que será carregado no pdf
	 * @param unknown $html
	 */
	function setHtml($html) {
		$myfile = fopen(Glb::$CONFIG['RAIZGLB']."/framework/plugins/mpdf/pdffile.txt", "w");
		$txt = $html;
		fwrite($myfile, $txt);
		fclose($myfile);
	}
	
	/**
	 * Abre o pdf na tela
	 * @param string $nome: Nome do arquivo
	 */
	function show($nome = 'arquivo') {		
		header('location:'.Glb::$CONFIG['URL'].'/framework/plugins/mpdf/file.php');
	}
	
	/**
	 * Salva o pdf no diretório
	 * @param string $diretorio: Diretório
	 * @param string $nome: Nome do Arquivo
	 */
	function save($diretorio = '', $nome = 'arquivo', $html) {
		require_once 'mpdf.php';
		$this->pdf = new mPDF();
		$this->pdf->WriteHTML($html);
		$this->pdf->Output($diretorio.$nome.'.pdf', 'F');
		//exit();
	}
}