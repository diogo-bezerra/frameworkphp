<?php
/**
 * Cria o html de um elemento Input File
 */
final class InputFileCustom extends Elemento
{
	function __construct($id,$img){
		parent::__construct('DIV');
    
    // Buscando os atributos da imagem
    list($widthImg, $heightImg, $typeImg, $attrImg) = getimagesize($img); 
    
    $this->setPropriedades(array(
  		'style'=>'border:0px solid #0000ff;height:'.$heightImg.'px;width:'.$widthImg.'px;'
		));
    
    $this->div = new Elemento('DIV');
    $this->div->setPropriedades(array(
  		'class'=>'uploadButton',
      'onmousemove'=>'showInputFile(event,\''.$id.'\')',
      //'onmouseout'=>'hideToolTip(\''.$id.'\')',
      'style'=>'cursor:pointer;border:0px solid #FF0000;overflow:hidden;position:relative;height:'.$heightImg.'px;width:'.$widthImg.'px;background:url('.$img.');background-repeat:no-repeat'
		));  
    
    $this->input = new Elemento('INPUT');
    $this->input->setPropriedades(array(
  		'class' =>'transparente',
      'id' => $id,
  		'name' => $id,
  		'type' => 'file',
      'style'=>'cursor:pointer;position:absolute;width:300px'
		));
    
    $this->div->add(array(
      $this->input
		));
    
    $this->add(array(
      $this->div
		));
		
		// Define o arquivo de JavaScript para essa classe para ser incluido na tag Head
		Head::setScripts('app.controle/js/InputFileCustom.js');
	}
}
?>