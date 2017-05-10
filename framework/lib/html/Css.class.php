<?php
/**
 * Define um link CSS
 * Os CSS Globais serão inseridos de acordo com o navegador.
 * É utilizada em config/setCss
 * IE: app.visual/clobalIE.css
 * FireFox: app.visual/clobalFF.css
 * Chrome: app.visual/clobalCH.css
 * Safari: app.visual/clobalSF.css
 * Opera: app.visual/clobalOP.css
 * @author Diogo (d.bezerra@yahoo.com.br)
 * @param (String) diretório do CSS
 * @param Obj Head que receberá o CSS
 */

class CSS {
	public function __construct($arquivo) {
		// Define Links de CSS para essa classe para ser incluido na tag Head
		$navegador = Glb::detect_browser(); // Verifica qual é o naveador
		if (strstr($arquivo, 'global')) { // Define qual arquivo css global será usado de acordo com o navegador
			switch ($navegador) {
				case 'IE':
					$$arquivo = str_replace('global', 'globalIE', $arquivo);
					break;
				case 'Firefox':
					$arquivo = str_replace('global', 'globalFF', $arquivo);
					break;
				case 'Chrome':
					$arquivo = str_replace('global', 'globalCH', $arquivo);
					break;
				case 'Safari':
					$arquivo = str_replace('global', 'globalSF', $arquivo);
					break;
				case 'Opera':
					$arquivo = str_replace('global', 'globalOP', $arquivo);
					break;
			}
		}
	}
}