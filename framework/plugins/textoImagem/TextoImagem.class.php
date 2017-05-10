<?php
/**
 * Transforma um texto em imagem
 * Chamada não criptografada
 * $texto = new TextoImagem('','','texto de teste',10,'cinza','arial',FALSE); // Texto imagem do email @param(id, alt, texto, tamanho da fonte, cor, fonte, FALSE)
 * $texto->show();
 * Chamada criptografada
 * $texto = new TextoImagem('','',base64_encode('texto de teste'),10,'cinza','arial',TRUE); // Texto imagem do email @param(id, alt, texto, tamanho da fonte, cor, fonte, TRUE)
 * $texto->show();
 * As cores podem ser criadas no arquivo textoImg.php ou em um arquivo de configura��o externo
 * As fontes devem ser True Type (.ttf) e devem estar no mesmo diretório desse arquivo
*/
final class TextoImagem extends Imagem {
	function __construct($id = '', $alt = '', $texto, $tam = 12, $cor = 'FFFFFF', $fonte = 'Tahoma', $cript = FALSE) {
		if (! preg_match ( '/^[a-fA-F0-9]+$/', $cor ) or strlen ( $cor ) != 6) {
			die ( 'A cor do texto da classe TextoImagem está errada (utilize o formato FFFFFF)' );
		}
		
		parent::__construct ( $id, $alt, Glb::$CONFIG['URL'] . '/framework/plugins/textoImagem/textoImg.php?tam=' . $tam . '&texto=' . $texto . '&cor=' . $cor . '&fonte=' . $fonte . '&cript=' . $cript );
	}
}
?>
