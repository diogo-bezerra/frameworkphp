<?php
class GoogleMaps extends DTag {
	public $init = "";
	public $scrp = "";
	
    public function __construct($id, $apikey, $height, $width, $endereco, $zoom) {
    	parent::__construct('div');
    	$this->id = $id;
		$divgmap = new DTag('div');
		$divgmap->id = $id.'gmap';
		$divgmap->style = "height: $height;width: $width";
		$this->setIn($divgmap);
		
		$this->setMarker($id, $apikey, $height, $width, $endereco, $zoom);
    }
    
    public function setMarker($id, $apikey, $height, $width, $endereco, $zoom) {
    	$arr = $this->geocode($endereco);
    	if($arr) {
	    	$init = '<script language="javascript" type= "text/javascript">
				function myMap() {
					var mapCanvas = document.getElementById("'.$id.'gmap");
					var mapOptions = {
				  		center: new google.maps.LatLng('.$arr[0].', '.$arr[1].'),
				  		zoom: '.$zoom.'
					}
					var map = new google.maps.Map(mapCanvas, mapOptions);
	  				marker = new google.maps.Marker({
	                	map: map,
	                	position: new google.maps.LatLng('.$arr[0].', '.$arr[1].')
                	
	            	});
                	infowindow = new google.maps.InfoWindow({
                		content: "'.$endereco.'"
            		});
                	infowindow.open(map, marker);
				}
			</script>';
	    	$scrp = '<script src="https://maps.googleapis.com/maps/api/js?key='.$apikey.'&callback=myMap"></script>';
	    	$this->setIn($init);
	    	$this->setIn($scrp);
    	} else {
    		$this->setIn('Não foi possível encontrar o mapa');
    	}
    }
    
    function geocode($address){
    
    	// url encode the address
    	$address = urlencode($address);
    	 
    	// google map geocode api url
    	$url = "http://maps.google.com/maps/api/geocode/json?address={$address}";
    
    	// get the json response
    	$resp_json = file_get_contents($url);
    	 
    	// decode the json
    	$resp = json_decode($resp_json, true);
    
    	// response status will be 'OK', if able to geocode given address
    	if($resp['status']=='OK'){
    
    		// get the important data
    		$lati = $resp['results'][0]['geometry']['location']['lat'];
    		$longi = $resp['results'][0]['geometry']['location']['lng'];
    		$formatted_address = $resp['results'][0]['formatted_address'];
    		 
    		// verify if data is complete
    		if($lati && $longi && $formatted_address){
    			 
    			// put the data in the array
    			$data_arr = array();
    			 
    			array_push(
    					$data_arr,
    					str_replace(',', '.', $lati),
    					str_replace(',', '.', $longi),
    					$formatted_address
    			);
    			 
    			return $data_arr;
    			 
    		}else{
    			return false;
    		}
    		 
    	}else{
    		return false;
    	}
    }
    
    public function showMap() {
    	$this->setIn($init);
    	$this->setIn($scrp);
    }
}