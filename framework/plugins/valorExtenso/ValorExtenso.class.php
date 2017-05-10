<?php
/**
 * Retorna um valor monetário por extenso
 * <p>Ex: ExtensoMoney::get("12428,12"); </p>
 * @author Diogo (d.bezerra@yahoo.com.br)
 */
class ValorExtenso {
	public $ext;
	function __construct() {
		
	}
	
	/**
	 * Ex: echo extenso("12428,12"); 
	 */
	public static function get($valor = 0, $maiusculas = false) {
		global $rt;
		// verifica se tem virgula decimal
		if (strpos ( $valor, "," ) > 0) {
			// retira o ponto de milhar, se tiver
			$valor = str_replace ( ".", "", $valor );
			
			// troca a virgula decimal por ponto decimal
			$valor = str_replace ( ",", ".", $valor );
		}
		$singular = array (
				"Centavo",
				"Real",
				"Mil",
				"Milhão",
				"Bilhão",
				"Trilhão",
				"Quatrilhão" 
		);
		$plural = array (
				"Centavos",
				"Reais",
				"Mil",
				"Milhões",
				"Bilhões",
				"Trilhões",
				"Quatrilhões" 
		);
		$c = array (
				"",
				"Cem",
				"Duzentos",
				"Trezentos",
				"Quatrocentos",
				"Quinhentos",
				"Seiscentos",
				"Setecentos",
				"Oitocentos",
				"Novecentos" 
		);
		$d = array (
				"",
				"Dez",
				"Vinte",
				"Trinta",
				"Quarenta",
				"Cinquenta",
				"Sessenta",
				"Setenta",
				"Oitenta",
				"Noventa" 
		);
		$d10 = array (
				"Dez",
				"Onze",
				"Doze",
				"Treze",
				"Quatorze",
				"Quinze",
				"Dezesseis",
				"Dezesete",
				"Dezoito",
				"Dezenove" 
		);
		$u = array (
				"",
				"Um",
				"Dois",
				"Três",
				"Quatro",
				"Cinco",
				"Seis",
				"Sete",
				"Oito",
				"Nove" 
		);
		$z = 0;
		$valor = number_format ( $valor, 2, ".", "." );
		$inteiro = explode ( ".", $valor );
		for($i = 0; $i < count ( $inteiro ); $i ++)
			for($ii = strlen ( $inteiro [$i] ); $ii < 3; $ii ++)
				$inteiro [$i] = "0" . $inteiro [$i];
		$fim = count ( $inteiro ) - ($inteiro [count ( $inteiro ) - 1] > 0 ? 1 : 2);
		for($i = 0; $i < count ( $inteiro ); $i ++) {
			$valor = $inteiro [$i];
			$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c [$valor [0]];
			$rd = ($valor [1] < 2) ? "" : $d [$valor [1]];
			$ru = ($valor > 0) ? (($valor [1] == 1) ? $d10 [$valor [2]] : $u [$valor [2]]) : "";
			$r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
			$t = count ( $inteiro ) - 1 - $i;
			$r .= $r ? " " . ($valor > 1 ? $plural [$t] : $singular [$t]) : "";
			if ($valor == "000")
				$z ++;
			elseif ($z > 0)
				$z --;
			if (($t == 1) && ($z > 0) && ($inteiro [0] > 0))
				$r .= (($z > 1) ? " de " : "") . $plural [$t];
			if ($r)
				$rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro [0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
		}
		if (! $maiusculas) {
			return (strtolower ( $rt ) ? strtolower ( $rt ) : "zero");
		} else {
			return ($rt ? $rt : "Zero");
		}
	}
}
?>

