<?php
/**
 * Super Classe para abstração para classes de criação de tags HTML
 */
// 
class Elemento {
	private $tag;
	public $propDefault = array();
	protected $propriedades = array();
	private $children = array('');
	# Elementos que não possuem tags de fechamento. O Fechamento é na própria tag de abertura
	private $noCloseTags = array('br', 'img', 'input', 'meta', 'hr', 'link'); 
	# Usado para especificar a tabulção das tags
	private $tab; 
	
	public function __construct($tag) {
		$this->tag = strtolower($tag);
		$ar = get_object_vars($this);
		foreach ($ar as $chave => $array) {
			$this->propDefault[$chave] = $chave;
		}
	}

	/** 
	 * Define as propriedades do objeto providos de um array. @param(array das propriedades)
	 * @param string $propriedades
	 * @param string $replace
	 */
	public function setPropriedades($propriedades, $replace = false) {
		foreach ($propriedades as $nome => $valor) {
			if (isset($this->propriedades[$nome])) {
				if ($replace) {
					$this->propriedades[$nome] = $valor;
				}else{
					$separador = ';';
					// Se a propriedade for class n�o usa o ; para separar os valores
					if ($nome == 'class') {
						$separador = ' ';
					}
					$this->propriedades[$nome] = $this->propriedades[$nome] . $separador . $valor;
				}
			} else {
				$this->propriedades[$nome] = $valor;
			}
		}
	}

	/** 
	 * Retorna uma propriedade do objeto. 
	 * @param(nome da propriedade)
	 */
	public function get($propriedade) {
		return $this->propriedades[$propriedade];
	}

	/** 
	 * Adiciona um elemento filho
	 */
	public function add($child) {
		if (is_array($child)) {
			foreach ($child as $valor) {
				$this->children[] = $valor;
			}
		} else {
			$this->children[] = $child;
		}
	}

	/** 
	 * Reseta os valores do elemento
	 * 
	 */
	public function reset() {
		$this->children[] = "";
	}

	/** 
	 * Abre a tag HTML
	 * 
	 */
	private function abrir() {
		# Verifica se a tag é <html>. Se for adiciona o cabeçalho xhtml antes
		if ($this->tag == 'html') {
			# HTML 4
			// echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
			# HTML 5
			echo '<!DOCTYPE html>'. "\r\n";
		}
		
		echo "<{$this->tag}";
		$ar = get_object_vars($this);
		foreach ($ar as $chave => $valor) {
			if (!in_array($chave, $this->propDefault)) {
				$this->setPropriedades(array($chave => $valor),true);
			}
		}
		if ($this->propriedades) {
			ksort($this->propriedades); // Ordena por �ndice
			foreach ($this->propriedades as $nome => $valor) {
				echo " {$nome}=\"{$valor}\"";
			}
		}
		# Verifica o tipo da tag no array tag para definir como será fechada
		if (in_array(strtolower($this->tag), $this->noCloseTags)) {
			echo ' />';
		} else {
			echo '>';
		}
	}

	/** 
	 * Fecha a tag HTML
	 * 
	 * @param unknown $tab
	 */
	private function fechar($tab) {
		if (!in_array(strtolower($this->tag), $this->noCloseTags)) {
			//echo $tab . "</{$this->tag}>\r\n";
			echo "</{$this->tag}>\r\n";
		}
	}

	/** Exibe a tag HTML na tela com seu conteúdo
	 * @param string $parentTab (tabulação das tags html)
	 * @param string $showTd (variável que define se uma TD será mostrada ou não dependendo da propriedade colspan)
	 * @param string $urlEncode (Indica se o conteudo deve ser codificado (usado para carregar ajax))
	 */
	public function show($encode = '', $parentTab = '', $showTd = TRUE) {
		$this->tab = $parentTab . "\t"; # Adiciona mais uma tabulação a tabulação do objeto parental
		//echo $this->tab; # Escreve a tabulação
		$this->abrir($this->tab); # Abre a tag passando a tabulação para os filhos
		# Se possui conteúdo
		if ($this->children) {
			# Percorre todos os objetos filhos
			$colspan = 0; # Contador decrescente de colspan para TDs
			foreach ($this->children as $child) {
				# Se for objeto
				if (is_object($child)) {
					# Tratando a propriedade colspan se o elemento for TD.
					# Se o elemento filho for TD verifica se tem a propriedade colspan. Se tiver as próximas TDs (de acordo com o valor de colspan) n�o s�o mostradas.
					if ($child->tag == 'td') { # Se for TD
						if ($showTd == TRUE) { # Se $showTd for verdadeiro mostra a TD
							$child->show($encode, $this->tab); # mostra a TD
							if (isset($child->propriedades['colspan'])) { # Se a propriedade colspan da TD existe
								$showTd = FALSE; # Define $showTd como falso para não mostrar a próxima TD
								$colspan = $child->propriedades['colspan'] - 1; # Define a variável $colspan para a contagem de quantas TDs NÃO serão mostradas
							}
						} else { # Se $showTd for falso
							if ($colspan <= 0) { # Verifica se $colspan atingiu 0
								$showTd = TRUE; # Se $colspan for 0 então define $showTd como verdadeiro para mostrar a próxima TD
							}
						}
						$colspan = $colspan - 1; # Decrementa o contador $colspan.
						# Qualquer outro elemento que não seja TD
					} else {
						$child->show($encode, $this->tab, TRUE);
					}
				} else {
					if ((is_string($child)) or (is_numeric($child))) {
						if ($this->tag == 'textarea') { # Se for textarea não escreve a tabulação para evitar espaços dentro da textarea
							if ($encode == 'utf') {
								echo utf8_encode($child); # Escreve o conteúdo
							} else {
								echo $child; # Escreve o conteúdo
							}
							$this->tab = '';
						} else {
							if ($encode == 'utf') {
								//echo (utf8_encode($this->tab . "\t" . $child . "\r\n")); # Escreve o conteúdo
								echo (utf8_encode($child. "\r\n")); # Escreve o conteúdo
							} else {
								//echo $this->tab . "\t" . $child . "\r\n"; # Escreve o conteúdo
								echo ($child. "\r\n");
							}
						}
					}
				}
			}
		}
		# Fecha a tag. @param(tabulação da tag)
		$this->fechar($this->tab);
	}

	/** 
	 * Retorna o elemento em formato string.
	 * 
	 * @return string
	 */
	public function showStr() {
		$elemento = "<{$this->tag}";
		if ($this->propriedades) {
			ksort($this->propriedades); # Ordena por índice
			foreach ($this->propriedades as $nome => $valor) {
				$elemento = $elemento . " {$nome}=\"{$valor}\"";
			}
		}
		# Verifica o tipo da tag no array tag para definir como será fechada
		if (in_array(strtolower($this->tag), $this->noCloseTags)) {
			$elemento = $elemento . ' />';
		} else {
			$elemento = $elemento . '>';
			if ($this->children) {
				# Percorre todos os objetos filhos
				foreach ($this->children as $child) {
					# Se for objeto
					if (is_object($child)) {
						$elemento = $elemento . $child->showStr();
					} else {
						if ((is_string($child)) or (is_numeric($child))) {
							$elemento = $elemento . $child; # Escreve o conteúdo
						}
					}
				}
			}
			$elemento = $elemento . "</{$this->tag}>";
		}
		return ($elemento);
	}

}