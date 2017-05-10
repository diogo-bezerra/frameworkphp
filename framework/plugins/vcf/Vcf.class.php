<?php
/**
 * Gera um arquivo vcf de contatos para celular
 * Deve receber um array bidimensional dos contatos
 */
class Vcf {
	// An array of this vcard's contact data.
    protected $data;
    // Filename for download file naming.
    protected $filename;
    // vCard class: PUBLIC, PRIVATE, CONFIDENTIAL.
    protected $class;
    // vCard revision date.
    protected $revision_date;
    // The vCard gnerated.
    protected $card;
	
/**
	 * The constructor.
	 */
	public function __construct($dados) {
		/*
		$this->data = array(
				'display_name' => "Diogo",
				'first_name' => "Diogo",
				'last_name' => NULL,
				'additional_name' => NULL,
				'name_prefix' => NULL,
				'name_suffix' => NULL,
				'nickname' => NULL,
				'title' => NULL,
				'role' => NULL,
				'department' => NULL,
				'company' => NULL,
				'work_po_box' => NULL,
				'work_extended_address' => NULL,
				'work_address' => NULL,
				'work_city' => NULL,
				'work_state' => NULL,
				'work_postal_code' => NULL,
				'work_country' => NULL,
				'home_po_box' => NULL,
				'home_extended_address' => NULL,
				'home_address' => NULL,
				'home_city' => NULL,
				'home_state' => NULL,
				'home_postal_code' => NULL,
				'home_country' => NULL,
				'office_tel' => NULL,
				'home_tel' => NULL,
				'cell_tel' => "81991667090",
				'fax_tel' => NULL,
				'pager_tel' => NULL,
				'email1' => NULL,
				'email2' => NULL,
				'url' => NULL,
				'photo' => NULL,
				'birthday' => NULL,
				'timezone' => NULL,
				'sort_string' => NULL,
				'note' => NULL,
		);
		*/
		$this->data = $dados;
		$this->setCard();
	}
	
	/**
	 * Define o texto que será carregado
	 */
	function setCard() {
		$txt = "";
		foreach ($this->data as $contato) {
			$aux = array(
				'display_name' => NULL,
				'first_name' => NULL,
				'last_name' => NULL,
				'additional_name' => NULL,
				'name_prefix' => NULL,
				'name_suffix' => NULL,
				'nickname' => NULL,
				'title' => NULL,
				'role' => NULL,
				'department' => NULL,
				'company' => NULL,
				'work_po_box' => NULL,
				'work_extended_address' => NULL,
				'work_address' => NULL,
				'work_city' => NULL,
				'work_state' => NULL,
				'work_postal_code' => NULL,
				'work_country' => NULL,
				'home_po_box' => NULL,
				'home_extended_address' => NULL,
				'home_address' => NULL,
				'home_city' => NULL,
				'home_state' => NULL,
				'home_postal_code' => NULL,
				'home_country' => NULL,
				'office_tel' => NULL,
				'home_tel' => NULL,
				'cell_tel' => NULL,
				'fax_tel' => NULL,
				'pager_tel' => NULL,
				'email1' => NULL,
				'email2' => NULL,
				'url' => NULL,
				'photo' => NULL,
				'birthday' => NULL,
				'timezone' => NULL,
				'sort_string' => NULL,
				'note' => NULL,
			);
			$contato = array_merge($aux, $contato);
			$txt .= $this->build($contato);
		}
		
		$myfile = fopen(Glb::$CONFIG['RAIZGLB']."/framework/plugins/vcf/vcffile.txt", "w");
		fwrite($myfile, $txt);
		fclose($myfile);
		header('location:'.Glb::$CONFIG['URL'].'/framework/plugins/vcf/vcf.php');
	}
	
	/**
	 * @param string $nome: Nome do arquivo
	 */
	function output($nome = 'arquivo') {
		header('location:'.Glb::$CONFIG['URL'].'/framework/plugins/vcf/vcf.php');
	}
	
	
	/**
	 * Global setter.
	 *
	 * @param string $key
	 *   Name of the property.
	 * @param mixed $value
	 *   Value to set.
	 *
	 * @return vCard
	 *   Return itself.
	 */
	public function set($key, $value) {
		// Check if the specified property is defined.
		if (property_exists($this, $key) && $key != 'data') {
			$this->{$key} = trim($value);
			return $this;
		} elseif (property_exists($this, $key) && $key == 'data') {
			foreach ($value as $v_key => $v_value) {
				$this->{$key}[$v_key] = trim($v_value);
			}
			return $this;
		} else {
			return FALSE;
		}
	}
	/**
	 * Checks all the values, builds appropriate defaults for
	 * missing values and generates the vcard data string.
	 */
	function build($contato) {
		if (!$this->class) {
			$this->class = 'PUBLIC';
		}
		if (!$contato['display_name']) {
			$contato['display_name'] = $contato['first_name'] . ' ' . $contato['last_name'];
		}
		if (!$contato['sort_string']) {
			$contato['sort_string'] = $contato['last_name'];
		}
		if (!$contato['sort_string']) {
			$contato['sort_string'] = $contato['company'];
		}
		if (!$contato['timezone']) {
			$contato['timezone'] = date("O");
		}
		if (!$this->revision_date) {
			$this->revision_date = date('Y-m-d H:i:s');
		}
		$card = "BEGIN:VCARD\r\n";
		$card .= "VERSION:3.0\r\n";
		$card .= "CLASS:" . $this->class . "\r\n";
		$card .= "PRODID:-//class_vCard from WhatsAPI//NONSGML Version 1//EN\r\n";
		$card .= "REV:" . $this->revision_date . "\r\n";
		$card .= "FN:" . $contato['display_name'] . "\r\n";
		$card .= "N:"
			. $contato['last_name'] . ";"
			. $contato['first_name'] . ";"
			. $contato['additional_name'] . ";"
			. $contato['name_prefix'] . ";"
			. $contato['name_suffix'] . "\r\n";
		
		if ($contato['nickname']) {
			$card .= "NICKNAME:" . $contato['nickname'] . "\r\n";
		}
		if ($contato['title']) {
			$card .= "TITLE:" . $contato['title'] . "\r\n";
		}
		if ($contato['company']) {
			$card .= "ORG:" . $contato['company'];
		}
		if ($contato['department']) {
			$card .= ";".$contato['department'];
		}
		$card .= "\r\n";
		if ($contato['work_po_box'] || $contato['work_extended_address']
				|| $contato['work_address'] || $contato['work_city']
				|| $contato['work_state'] || $contato['work_postal_code']
				|| $contato['work_country']) {
			$card .= "ADR;type=WORK:"
				. $contato['work_po_box'] . ";"
				. $contato['work_extended_address'] . ";"
				. $contato['work_address'] . ";"
				. $contato['work_city'] . ";"
				. $contato['work_state'] . ";"
				. $contato['work_postal_code'] . ";"
				. $contato['work_country'] . "\r\n";
		}
		if ($contato['home_po_box'] || $contato['home_extended_address']
				|| $contato['home_address'] || $contato['home_city']
				|| $contato['home_state'] || $contato['home_postal_code']
				|| $contato['home_country']) {
					$card .= "ADR;type=HOME:"
						. $contato['home_po_box'] . ";"
						. $contato['home_extended_address'] . ";"
						. $contato['home_address'] . ";"
						. $contato['home_city'] . ";"
						. $contato['home_state'] . ";"
						. $contato['home_postal_code'] . ";"
						. $contato['home_country'] . "\r\n";
		}
		if ($contato['email1']) {
			$card .= "EMAIL;type=INTERNET,pref:" . $contato['email1'] . "\r\n";
		}
		if ($contato['email2']) {
			$card .= "EMAIL;type=INTERNET:" . $contato['email2'] . "\r\n";
		}
		if ($contato['office_tel']) {
			$card .= "TEL;type=WORK,voice:" . $contato['office_tel'] . "\r\n";
		}
		if ($contato['home_tel']) {
			$card .= "TEL;type=HOME,voice:" . $contato['home_tel'] . "\r\n";
		}
		if ($contato['cell_tel']) {
			$card .= "TEL;type=CELL,voice:" . $contato['cell_tel'] . "\r\n";
		}
		if ($contato['fax_tel']) {
			$card .= "TEL;type=WORK,fax:" . $contato['fax_tel'] . "\r\n";
		}
		if ($contato['pager_tel']) {
			$card .= "TEL;type=WORK,pager:" . $contato['pager_tel'] . "\r\n";
		}
		if ($contato['url']) {
			$card .= "URL;type=WORK:" . $contato['url'] . "\r\n";
		}
		if ($contato['birthday']) {
			$card .= "BDAY:" . $contato['birthday'] . "\r\n";
		}
		if ($contato['role']) {
			$card .= "ROLE:" . $contato['role'] . "\r\n";
		}
		if ($contato['note']) {
			$card .= "NOTE:" . $contato['note'] . "\r\n";
		}
		$card .= "TZ:" . $contato['timezone'] . "\r\n";
		$card .= "END:VCARD\r\n";
		
		return $card;
	}
	
	/**
	 * Streams the vcard to the browser client.
	 */
	function download() {
		if (!$this->card) {
			$this->build();
		}
		if (!$this->filename) {
			$this->filename = $this->data['display_name'];
		}
		$this->filename = str_replace(' ', '_', $this->filename);
		header("Content-type: text/directory");
		header("Content-Disposition: attachment; filename=" . $this->filename . ".vcf");
		header("Pragma: public");
		echo $this->card;
		return TRUE;
	}
	/**
	 * Show the vcard.
	 */
	function show() {
		if (!$this->card) {
			$this->build();
		}
		return $this->card;
	}
}