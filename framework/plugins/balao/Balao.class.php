<?php

final class Balao extends Tabela {

	function __construct($id,$objLink,$t,$conteudo,$w = 0,$h = 0,$down = false) {
		parent::__construct($id,2,1);
		$this->setPropriedades(array(
				'border' => '0px',
				'class' => 'fonte01_3_azul',
				'cellpadding' => '0',
				'cellspacing' => '0',
				'width'=>$t.'px',
				'style' => 'display:none;
				position:absolute;
				z-index:2000;'
		));
		if($down){
			$this->td[1][1]->setPropriedades(array(
					'align' => 'left'
			));
			$this->td[2][1]->setPropriedades(array(
					'width'=>$t.'px',
					'style' => 'background-color:#F5F5F5;
					border-left:5px solid #2D83C3;
					border-right:3px solid #2D83C3;
					border-top:4px solid #2D83C3;
					border-bottom:4px solid #2D83C3;
					padding-left:5px;
					padding-right:5px;
					padding-top:5px;
					padding-bottom:5px;'
			));
			$this->td[2][1]->add(urldecode($conteudo));

			$seta = new Imagem('', '', Glb::$CONFIG['URL'] . '/framework/plugins/balao/bubble_setaDown.png');
			$this->td[1][1]->add($seta);

			$idObjLink = $objLink->get('id');
			//echo $idObjLink;
			$objLink->setPropriedades(array(
					'onmousemove' => "setCursor(this,'pointer');showToolTipDown(event,'" . $id . "',this.id,".$w.",".$h.")",
					'onmouseout' => 'hideToolTip(\'' . $id . '\')'
			));
		}else{
			$this->td[1][1]->setPropriedades(array(
					'style' => 'background-color:#F5F5F5;
					border-left:5px solid #2D83C3;
					border-right:3px solid #2D83C3;
					border-top:4px solid #2D83C3;
					border-bottom:4px solid #2D83C3;
					padding-left:5px;
					padding-right:5px;
					padding-top:5px;
					padding-bottom:5px;'
			));
			$this->td[2][1]->setPropriedades(array(
					'align' => 'left'
			));

			$this->td[1][1]->add(urldecode($conteudo));

			$seta = new Imagem('', '', Glb::$CONFIG['URL'] . '/framework/plugins/balao/bubble_seta.png');
			$this->td[2][1]->add($seta);

			$idObjLink = $objLink->get('id');
			$objLink->setPropriedades(array(
					'onmousemove' => "setCursor(this,'pointer');showToolTip(event,'" . $id . "',this.id,".$w.",".$h.")",
					'onmouseout' => 'hideToolTip(\'' . $id . '\')'
			));
		}
		//$this->show();
	}
}

?>
