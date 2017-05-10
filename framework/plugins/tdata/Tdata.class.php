<?php
class Tdata {
	/**
	 *
	 * Classe de manipulação de datas
	 * @param Array get com os valores passados via post ou get por Global.js->showVs()
	 *
	 */
	function __construct() {
		
	}
	
	/**
	 * Converte data ano-mes-dia em dia/mes/ano (usado para MySql)
	 * @param string $data
	 * @return string data formatada
	 */
	static public function TdataBR($data) {
		$data = implode('/', array_reverse(explode('-', $data)));
		return $data;
	}
	
	/**
	 * Converte data dia/mes/ano em ano-mes-dia (usado para MySql)
	 * @param string $data
	 * @return string data formatada
	 */
	static public function TdataMysql($data) {
		$data = explode('/', $data);
		$data = array_reverse($data);
		$data = implode('-', $data);
		return $data;
	}
	
	/**
	 * Converte data ano-mes-dia (do banco) em inteiro anomesdia para comparação com outras datas
	 */
	static public function TdataInteiro($data) {
		$data = str_replace('-', '', $data);
		return $data;
	}
	
	/**
	 * Retorna a data de hoje em formato MySql ano-mes-dia
	 */
	static public function TdataHojeMysql() {
		return date ( "Y-m-d" );
	}
	
	/**
	 * Retorna a data de hoje
	 */
	static public function TdataHoje() {
		return date ( "d/m/Y" );
	}
	
	/**
	 * Retorna o dia do mes atual
	 * @return (int) dia
	 */
	static public function dia() {
		return date ( "d" );
	}
	
	/**
	 * Retorna o mes atual
	 * @return (int) mes
	 */
	static public function mes() {
		return date ( "m" );
	}
	
	/**
	 * Retorna o ano atual
	 * @return (int) ano
	 */
	static public function ano() {
		return date ( "Y" );
	}
	
	/**
	 * Retorna o nome do dia de hoje
	 * @param (String) data no formato yyyy-mm-dd
	 */
	static public function diaNome($data = false) {
		if($data) {
			$nome = date('l', strtotime($data));
		}else{
			$nome = date('l', strtotime( self::TdataHojeMysql()));
		}
		
		switch ($nome) {
			case 'Sunday':
				return 'Domingo';
				break;
			case 'Monday':
				return 'Segunda';
			break;
			
			case 'Tuesday':
				return 'Terça';
			break;
			
			case 'Wednesday':
				return 'Quarta';
			break;
			
			case 'Thursday':
				return 'Quinta';
			break;
				
			case 'Friday':
				return 'Sexta';
			break;
				
			case 'Saturday':
				return 'Sábado';
			break;
		}
	}
	
	/**
	 * Retorna a hora de agora
	 */
	static public function hora() {
		return  date ( "H:i:s" );
	}
	
	/**
	 * Compara uma hora com a hora de agora
	 * @param (String) Hora a ser comparada em formato "00::00:00"
	 */
	static function compHora_agora($hora){
		$horafixa = strtotime($hora);
		$horaatual = strtotime("now");
		$rt = "igual";
		if($horaatual > $horafixa){
			return "maior";
		}
		
		if($horaatual < $horafixa){
			return "menor";
		}
		return $rt;
	}
	
	/**
	 * Compara uma data com a data de hoje
	 * @param (String) Data a ser comparada em formato "yyyy-mm-dd" ou dd/mm/yyyy
	 */
	static function compData_hoje($data){
		$data = self::TdataMysql($data);
		$data = self::TdataInteiro($data);
		$dataHoje = date("Ymd");
		$rt = "igual";
		if($dataHoje > $data){
			$rt = "maior";
		}
		if($dataHoje < $data){
			$rt = "menor";
		}
		return $rt;
	}
	
	/**
	 * Compara duas data1 com data2 
	 * @param (String) Data a ser comparada em formato "2014-02-02"
	 * @param (String) Data a ser comparada em formato "2014-02-02"
	 */
	static function compDatas($data1, $data2) {
		$data1 = self::TdataMysql($data1);
		$data2 = self::TdataMysql($data2);
		$data1 = self::TdataInteiro($data1);
		$data2 = self::TdataInteiro($data2);
		$rt = "igual";
		if($data1 > $data2){
			$rt = "maior";
		}
		if($data1 < $data2){
			$rt = "menor";
		}
		return $rt;
	}
	
	/**
	 * Devolve o mês de acordo com o nome
	 */
	static function getMesNum($mesExtenso) {
		$arr = array(
			'Janeiro' => '01',	
			'Fevereiro' => '02',
			'Março' => '03',
			'Abril' => '04',
			'Maio' => '05',
			'Junho' => '06',
			'Julho' => '07',
			'Agosto' => '08',
			'Setembro' => '09',
			'Outubro' => '10',
			'Novembro' => '11',
			'Dezembro' => '12',
		);
		return $arr[$mesExtenso];
	}
	
	/**
	 * Devolve o mês por extenso de acordo com o número do mês
	 */
	static function getMesExt($mesnum) {
		$arr = array(
			'Janeiro' => '01',	
			'Fevereiro' => '02',
			'Março' => '03',
			'Abril' => '04',
			'Maio' => '05',
			'Junho' => '06',
			'Julho' => '07',
			'Agosto' => '08',
			'Setembro' => '09',
			'Outubro' => '10',
			'Novembro' => '11',
			'Dezembro' => '12',
		);
		return array_search ($mesnum, $arr);
	}
	
	/**
	 * Subtrai duas datas em formato yyyy-mm-dd
	 */
	static function diffDate($d1, $d2, $type='', $sep='-') {
		$d1 = explode($sep, $d1);
		$d2 = explode($sep, $d2);
		switch ($type){
			case 'A':
				$X = 31536000;
			break;
			case 'M':
				$X = 2592000;
			break;
			case 'D':
				$X = 86400;
			break;
			case 'H':
				$X = 3600;
			break;
			case 'MI':
				$X = 60;
			break;
			default:
				$X = 1;
		}
		
		$z = mktime (0, 0, 0, $d2[1], $d2[2], $d2[0]);
		$y = mktime (0, 0, 0, $d1[1], $d1[2], $d1[0]);
		return floor( ($z-$y) / $X );
	}
	
	/**
	 * Soma dias a uma data em formato yyyy-mm-dd
	 * @param unknown $data
	 * @param unknown $dias
	 */
	static function addDias($data, $dias) {
		return date('Y-m-d', strtotime("+$dias days",strtotime($data)));
	}
	
	/**
	 * Subtrai dias de uma data em formato yyyy-mm-dd
	 * @param unknown $data
	 * @param unknown $dias
	 */
	static function subDias($data, $dias) {
		return date('Y-m-d', strtotime("-$dias days",strtotime($data)));
	}
	
	/**
	 * Retorna o dia de uma data em formato yyyy-mm-dd
	 * @param Data em formato yyyy-mm-dd
	 */
	static function getDiaData($data) {
		$data = explode('-', $data);
		return $data[2];
	}
	
	/**
	 * Retorna o mes de uma data em formato yyyy-mm-dd
	 * @param Data em formato yyyy-mm-dd
	 */
	static function getMesData($data) {
		$data = explode('-', $data);
		return $data[1];
	}
	
	/**
	 * Retorna o ano de uma data em formato yyyy-mm-dd
	 * @param Data em formato yyyy-mm-dd
	 */
	static function getAnoData($data) {
		$data = explode('-', $data);
		return $data[0];
	}
	
	/**
	 * Obtem as datas entre dois intervalos de datas
	 * @param (String) $dtin em formato yyyy-mm-dd
	 * @param (String) $dtout em formato yyyy-mm-dd
	 * @return Array de String
	 */
	static function getIntervaloDatas ($dtin, $dtout) {
		if($dtin == $dtout) {
			return array($dtin, $dtout);
		}
		$dias = false;
		// Data de entrada
		$dtin = gmdate("Y-m-d", strtotime($dtin));
		// data de saída
		$dtout = gmdate("Y-m-d", strtotime($dtout));
		// Insere a data de entrada para retorno
		$dias[] = $dtin;
		// Data auxiliar
		$dataAux = $dtin;
		while($dataAux < $dtout) {
			$dataAux = gmdate("Y-m-d", strtotime("+1 day", strtotime($dataAux)));
			$dias[] = $dataAux;
		}
		return $dias;
	}
}

?>