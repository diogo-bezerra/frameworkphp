<?php
class GeoPlugin // Identifica de onde o usuário está acessando. Em home.php.
{
private $ip;
private $status;
private $cidade;
private $uf;
private $ufAbrev;
private $pais;
private $paisAbrev;

	function __construct($ip){
  #$ip = '177.98.22.136';
  @$data = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip));
  if($data){
      @$this->ip = $data['geoplugin_request']; 
      @$this->status = $data['geoplugin_status'];
      @$this->cidade = $data['geoplugin_city'];
      @$this->uf = strtolower($data['geoplugin_region']);
      $arrayUfAbrev = array(
        'acre'=>'AC',
        'alagoas'=>'AL',
        'amapá'=>'AP',
        'amazonas'=>'AM',
        'bahia'=>'BA',
        'ceará'=>'CE',
        'distrito federal'=>'DF',
        'espírito santo'=>'ES',
        'goiás'=>'GO',
        'maranhão'=>'MA',
        'mato grosso'=>'MT',
        'mato grosso do sul'=>'MS',
        'minas gerais'=>'MG',
        'pará'=>'PA',
        'paraíba'=>'PB',
        'paraná'=>'PR',
        'pernambuco'=>'PE',
        'piauí'=>'PI',
        'rio de janeiro'=>'RJ',
        'rio grande do norte'=>'RN',
        'rio grande do sul'=>'RS',
        'rondônia'=>'RO',
        'roraima'=>'RR',
        'santa catarina'=>'SC',
        'são paulo'=>'SP',
        'sergipe'=>'SE',
        'tocantins'=>'TO'
      );
      foreach($arrayUfAbrev as $chave=>$valor){
        if(nl2br(htmlentities($chave)) == $this->uf){
          $this->ufAbrev = $valor;  
        }
      } 
      $this->paisAbrev = $data['geoplugin_countryCode'];
      $this->pais = $data['geoplugin_countryName'];
    //}catch(Exception $e){
      //echo 'Fudeu';
   // };
    }
	}
	
	function __destruct(){
		# echo "<br /> <br /> Objeto destruído";
	}
	
	// Retorna um atributo
	function get($nome_atributo){
		return $this->$nome_atributo;
	}
	
	// Define um atributo
	function set($nome_atributo,$novo_valor){
		$this->$nome_atributo = $novo_valor;
	}
}
?>

 

