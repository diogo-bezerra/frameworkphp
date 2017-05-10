<?php
class DTag {
	private $_tagname;
	private $_tag;
	private $_inner;
	private $_endtag;
	private $_tagNoSetIn;
	private $_tagsNoSetIn;
	private $_array_inner = array();
	private $_delete = false;
	
    public function __construct($tag) {
    	$tag = strtolower($tag);
    	$this->_tagname = $tag;
    	$this->_tag = "<$tag>";
    	$this->_inner = '';
    	$this->_endtag = "</$tag>";
    	$this->_tagNoSetIn = false;
    	$this->_attr = array();
    	$this->_tagsNoSetIn = array(
    		'input',
    		'img',
    		'meta'
    	);
    	
    	if (in_array($tag, $this->_tagsNoSetIn)) {
    		$this->_tag = "<$tag";
    		$this->_endtag = " />";
    		$this->_tagNoSetIn = true;
    	}
    }
    
    function delete() {
    	$this->_tagname = '';
    	$this->_tag = '';
    	$this->_inner = '';
    	$this->_endtag = '';
    	$this->_delete = true;
    }
    
    /**
     * Retorna a tag em formato string
     * @return string
     */
    public function getTag() {
    	if ($this->_tagNoSetIn) {
    		return $this->_tag.$this->_inner.$this->_endtag."\n";
    	}
    	if($this->_delete) {
    		return "";
    	}
    	return $this->_tag.$this->_inner.$this->_endtag;
    }
    
    public function setIn($conteudo, $reset = false) {
    	if($reset) {
    		$this->_array_inner = array();
    	}
    	$this->_array_inner[] = $conteudo;
    }
    
    /**
     * Retorna o conteúdo de uma tag
     */
    public function getInner() {
    	foreach ($this->_array_inner as $value) {
    		;
    	}
    	return $this->_inner;
    }
    
    public function setAttrs($tag) {
    	$attrs = '';
    	foreach (get_object_vars ( $tag ) as $nome => $value) {
    		if(strpos($nome, '_') === false) {
    			$this->_attr[$nome] = $value;
    			$attrs .= ' '.$nome.' = "'.$value.'"';
    		}
    	}
    	
    	$this->_tag = '<'.$this->_tagname;
    	if (in_array($this->_tagname, $this->_tagsNoSetIn)) {
    		$this->_tag = $this->_tag.$attrs;
    	} else {
    		$this->_tag = $this->_tag.$attrs.'>';
    	}
    }
    
	/**
	 * Exibe a tag na tela (echo)
	 */
	public function show() {
    	$this->dumpTree();
    	// Definindo aos atributos
    	$this->setAttrs($this);
    	if($this->_tagname == 'html') {
    		echo  '﻿<!DOCTYPE html>'."\n";
    	}
    	echo $this->getTag();
    }
    
    /**
     * Retorna em formato string
     * @return string
     */
    public function toStr() {
    	$this->dumpTree();
    	// Definindo aos atributos
    	$this->setAttrs($this);
    	return $this->getTag();
    }
    
    public function dumpTree() {
    	$this->_inner = '';
    	foreach ($this->_array_inner as $value) {
	    	if ($this->_tagNoSetIn) {
	    		$error = "A tag $this->_tagname não insere elementos";
	    		throw new Exception($error);
	    		die();
	    	}
	    	 
	    	if(is_a($value, 'DTag')) {
	    		// Definindo aos atributos
	    		$value->setAttrs($value);
	    		$value->dumpTree();
	    		$this->_inner .= "\n".$value->getTag();
	    	}
	    	 
    		if(is_string($value) or is_numeric($value)) {
	    		$this->_inner .= $value."\n";
	    	}
	    	
	    	
	    	if(is_array($value)) {
	    		foreach ($value as $str) {
	    			$this->_inner .= $str."\n";
	    		}
	    	}
    	}
    }
    
    /**
     * Insere string em uma tag
     * @param (String ou Objeto Dtag) $conteudo: conteúdo a ser inserido
     * @param boolean $Reset: Se true sobreescreve o conteúdo existente
     */
    public function insertInner($conteudo) {
    	if ($this->_tagNoSetIn) {
    		$error = "A tag $this->_tagname não insere elementos";
    		throw new Exception($error);
    		die();
    	}
    	 
    	if(is_a($conteudo, 'DTag')) {
    		// Definindo aos atributos
    		$this->setAttrs($conteudo);
    		$this->_inner .= $conteudo->getTag();
    	}
    	 
    	if(is_string($conteudo)) {
    		$this->_inner .= $conteudo;
    	}
    }
}

?>
