<?php
class GeoPlugin // Identifica de onde o usu�rio est� acessando. Em home.php.
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
        'amap�'=>'AP',
        'amazonas'=>'AM',
        'bahia'=>'BA',
        'cear�'=>'CE',
        'distrito federal'=>'DF',
        'esp�rito santo'=>'ES',
        'goi�s'=>'GO',
        'maranh�o'=>'MA',
        'mato grosso'=>'MT',
        'mato grosso do sul'=>'MS',
        'minas gerais'=>'MG',
        'par�'=>'PA',
        'para�ba'=>'PB',
        'paran�'=>'PR',
        'pernambuco'=>'PE',
        'piau�'=>'PI',
        'rio de janeiro'=>'RJ',
        'rio grande do norte'=>'RN',
        'rio grande do sul'=>'RS',
        'rond�nia'=>'RO',
        'roraima'=>'RR',
        'santa catarina'=>'SC',
        's�o paulo'=>'SP',
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
		# echo "<br /> <br /> Objeto destru�do";
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

 

