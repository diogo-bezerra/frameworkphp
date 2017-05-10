//## Scripts para tags e elementos html 

/**
* Abre a uma nova janela. Parâmetros(largura, altura, margem esquerda, margem superior, url)
* @param largura
* @param altura
* @param margem esquerda
* @param margem superior
* @param url
*/
function abrir_janela(wth, hght, lft, top, url, scrollBar) {
	// wth: "diminui janela para o lado";
	// hght: "diminui janela para cima";
	// lft: "centraliza janela para a esquerda";
	// top: "centraliza janela para a direira";

	window
			.open(
					url,
					'Senha',
					'width='
							+ wth
							+ ', height='
							+ hght
							+ ', top='
							+ top
							+ ', left='
							+ lft
							+ ', scrollbars='
							+ scrollBar
							+ ', status=no, toolbar=no, location=no, directories=no, menubar=no, resizable=no, fullscreen=no,titlebar = no');
	// window.resizeTo(400,400)
}

/**
* Desabilita um elemento
* @param id do elemento
*/
function disable_elemento(id) {
	var bt = document.getElementById(id);
	bt.disabled = true;
}

/**
* Define uma conecção ajax
*/
function openAjax() {
	var Ajax;
	try {
		Ajax = new XMLHttpRequest(); // XMLHttpRequest para browsers mais
										// populares, como: Firefox, Safari,
										// dentre outros.
	} catch (ee) {
		try {
			Ajax = new ActiveXObject("Msxml2.XMLHTTP"); // Para o IE da MS
		} catch (e) {
			try {
				Ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Para o IE da
																// MS
			} catch (e) {
				Ajax = false;
			}
		}
	}
	return Ajax;
}


/**
* Abre e fecha um elemento alternando a visibilidade e a posição do elemento.
* @param id do elemento
*/
function OnOff_elemento (id) {
	
	var s = document.getElementById(id);
	var el = s.style;
	if (el.display == 'block') {
		el.display = 'none';
	} else {
		el.display = 'block';
	}
}

/**
* Abre um elemento alternando a visibilidade e a posição do elemento.
* @param id do elemento
*/

function On_elemento(id) {
	var s = document.getElementById(id);
	var div = s.style;
	div.visibility = 'visible';
	div.position = 'static';
	div.left = '0px';
	div.top = '0px';
}

/**
* Fecha um elemento alternando a visibilidade e a posição do elemento.
* @param id do elemento
*/
function Off_elemento(id) {
	var s = document.getElementById(id);
	var div = s.style;
	div.visibility = 'hidden';
	div.position = 'absolute';
	div.left = '0px';
	div.top = '-1000px';
}

/**
* Redefine o tipo de cursor de um elemento
* @param id do elemento
*/
function setCursor(obj, tipo) {
	obj.style.cursor = tipo;
}

/**
* Elimina espaços iniciais e finais de uma string
* @param string
*/
function trim(str) {
	return str.replace(/^\s+|\s+$/g, "");
}