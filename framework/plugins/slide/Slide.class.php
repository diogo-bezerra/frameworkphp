<?php
// ATEN��O:
// Para iniciar o slide o script startSlide.js deve ser chamado quando carrega a p�gina do slide
class Slide extends Tabela {

	private $container = array(''); // Containers de cada tela do slide
	private $slideTr = array(''); // Linhas de cada container
	public $slideTd = array(''); // Colunas das linhas de cada container
	private $conteudo = array('');

	function __construct($id, $linhas, $colunas, $largura, $altura, $qtd_telas, $showLinks = true) {
		parent::__construct($id, 2, 1);
		// Define propriedades do slide (tabela container)
		$this->setPropriedades(array(
				'border' => '0px',
				'cellpadding' => '0',
				'cellspacing' => '0',
				'height' => $altura . 'px',
				'style' => 'position:relative;',
				'width' => $largura . 'px'
		));
		$this->qtd_telas = $qtd_telas;
		$this->id = $id;
		$this->showLinks = 'false';
		if($showLinks){
			$this->showLinks = 'true';
		}

		// Monta a estrutura do slide
		$this->montaSlide($id, $linhas, $colunas, $largura, $altura, $qtd_telas, $showLinks);
	}

	// Monta a estrutura do slide
	function montaSlide($id, $linhas, $colunas, $largura, $altura, $qtd_telas, $showLinks) {
		// Cria o container dos links do slide
		$tb_links = new Tabela('tb_links', 1, $qtd_telas);
		$tb_links->setPropriedades(array(
				'border' => '0px',
				'cellspacing' => '8px',
				'height' => '5px',
				'style' => 'position:absolute;left:0px;top:160px;z-index:300',
				'width' => ''
		));

		// Percorre e cria a quantidade de telas do slide
		for ($i = 1; $i <= $qtd_telas; $i++) {
			// Instancia um um objeto Elemento tabela (slide)
			$this->container[$i] = new Elemento('TABLE');
			// Define as propriedades do objeto (slide)
			$this->container[$i]->setPropriedades(array(
					'id' => $id . $i,
					'border' => '0px',
					'cellpadding' => '2',
					'cellspacing' => '0',
					'height' => $altura . 'px',
					'style' => 'position:absolute;visibility:hidden;left:0px;top:0px',
					'width' => $largura . 'px'
			));
			// Define o slide 1 como sendo vis�vel e os demais n�o vis�veis
			if ($i == 1) {
				$this->container[$i]->setPropriedades(array(
						'style' => 'position:absolute;visibility:visible;left:0px;top:0px'
				));
			}

			// Gera as TRs do container do slide e armazena em um array
			for ($w = 1; $w <= $linhas; $w++) {
				$this->slideTr[$i][$w] = new Elemento('TR');
				// Gera as TDs de cada TR do container do slide e armazena em um array
				for ($t = 1; $t <= $colunas; $t++) {
					$this->slideTd[$i][$w][$t] = new Elemento('TD');
					// Define as propriedades de cada td e a largura dividida pela qtd de TDs em cada TR para ficarem iguais em tamanho
					$this->slideTd[$i][$w][$t]->setPropriedades(array(
							'width' => ($largura / $colunas) . 'px'
					));
					# $this->slideTd[$i][$w][$t]->add('TD'.$i.$w.$t);
					$this->slideTr[$i][$w]->add($this->slideTd[$i][$w][$t]); // Adiciona a TR as tags TD criadas
				}
				$this->container[$i]->add($this->slideTr[$i][$w]); // Adiciona ao slide as tags TR criadas
			}
			$this->td[1][1]->add($this->container[$i]); // Adiciona ao container geral os slides criados
			// Definindo conte�do e propriedades de cada link
			$tb_links->td[1][$i]->add('&nbsp');
			$tb_links->td[1][$i]->setPropriedades(array(
					'id' => 'link_' . $id . $i,
					'align' => 'center',
					'onmouseover' => 'setCursorSlide(this,\'pointer\')',
					'onclick' => "stopSlide('{$i}','link_slide1')",
					'width' => "12px"
							));
		}
		// Adicionando o container de links dos slides na TR 2 do container principal
		$this->td[2][1]->setPropriedades(array(
				'align' => 'left',
				'style' => 'padding-left:150px'
		));
		//if($showLinks){
		$this->td[2][1]->add(array(
				$tb_links
		));
		//}
	}

	// Define o html de um slide
	function setSlideHtml($slide,$linha,$arquivoHtml){
		try {
			$htmlFile = new simple_html_dom();
			$htmlFile->load_file($arquivoHtml);
			$this->slideTd[$slide][$linha][1]->add(array(
					$htmlFile->show()
			));
			$htmlFile->clear();
		} catch (Exception $e) {
			die('Quantidade de telas incompatível com quantidade de htmls carregados.');
			$htmlFile->clear();
		}
	}

	function ini($intervalo){
		$script = new ScriptJs(false, "iniciaSlide('$this->id',$this->qtd_telas,'$intervalo','$this->showLinks');");
		$script->show();
	}
}

?>
