var time_slide;
var lista_slide = new Array();
var intervaloSlide = 50000;// intervalo de tempo entre um slide e outro
var interfade = 20;// velocidade do fade
var dirImgSlide;
var x = 1000;
var showLinksSlide = 'true';

function fadeOut(id, time) {
	fade(id, time, 100, 0);
}
function fadeIn(id, time) {
	fade(id, time, 0, 100);
}
function fade(id, time, ini, fin) {
	var target = document.getElementById(id);
	setAlpha(target, ini);
	var alpha = ini;
	var inc;
	if (fin >= ini) {
		inc = 2;
	} else {
		inc = -2;
	}
	timer = (time * 1000) / 50;
	var i = setInterval(function() {
		if ((inc > 0 && alpha >= fin) || (inc < 0 && alpha <= fin)) {
			clearInterval(i);
		}
		setAlpha(target, alpha);
		alpha += inc;
	}, timer);
}
function setAlpha(target, alpha) {
	target.style.filter = "alpha(opacity=" + alpha + ")";
	target.style.opacity = alpha / 100;
}

// Inicia fadeOut enviando recursivamente o próximo slide a ser exibido.
// Parâmetros:(index do array de ids dos slides)
function mudaSlide(aux, id) {
	// Se der erro volta para o início
	try {
		// Muda o fundo do link para off
		if (showLinksSlide == 'true') {
			document.getElementById('link_' + lista_slide[aux]).style.background = "url('"
					+ globalUrl
					+ "/app.controle/api/slide/imagens/fundo_link_slide.png') no-repeat";
		}
		// Esconde o slide atual
		target = document.getElementById(lista_slide[aux]);
		fadeOut(lista_slide[aux], 0.6);
		// target.style.position = "absolute";
		// target.style.top=-1000+'px';
		// Mostra o pr�ximo slide
		if (showLinksSlide == 'true') {
			document.getElementById('link_' + lista_slide[aux + 1]).style.background = "url('"
					+ globalUrl
					+ "/app.controle/api/slide/imagens/fundo_link_slide_on.png') no-repeat";
		}
		proximo = document.getElementById(lista_slide[aux + 1]);
		// proximo.style.position = "relative";
		// proximo.style.top=0+'px';
		fadeIn(lista_slide[aux + 1], 0.6);
		proximo.style.visibility = "visible";
		setZindex(aux + 1); // Define o zindex
		time_slide = setTimeout('mudaSlide(' + (aux + 1) + ')', intervaloSlide);
	} catch (e) {
		try {
			if (showLinksSlide == 'true') {
				document.getElementById('link_' + lista_slide[1]).style.background = "url('"
						+ globalUrl
						+ "/app.controle/api/slide/imagens/fundo_link_slide_on.png') no-repeat";
			}
			proximo = document.getElementById(lista_slide[1]);
			// proximo.style.position = "relative";
			// proximo.style.top=0+'px';
			fadeIn(lista_slide[1], 0.6);
			proximo.style.visibility = "visible";
			setZindex(1); // Define o zindex
			time_slide = setTimeout('mudaSlide(1)', intervaloSlide);
		} catch (e) {
		}
	}
}

// Inicia a apresenta��o dos slides. @param(id do slide,qtd de slides,nivel de
// diret�rio para as imagens (../))
function iniciaSlide(id, qtdSlide, interv, showLinks) {
	try {
		clearTimeout(time_slide);
	} catch (e) {
	}
	intervaloSlide = interv;
	showLinksSlide = showLinks;

	// Monta a lista de ids dos slides
	for (i = 1; i <= qtdSlide; i++) {
		lista_slide[i] = id + i;
		// alert(showLinks);
		document.getElementById(lista_slide[i]).style.top = 0 + 'px';
		if (showLinksSlide == 'true') {
			document.getElementById('link_' + lista_slide[i]).style.background = "url('"
					+ globalUrl
					+ "/app.controle/api/slide/imagens/fundo_link_slide.png') no-repeat";
		}
	}
	if (showLinksSlide == 'true') {
		document.getElementById('link_' + lista_slide[1]).style.background = "url('"
				+ globalUrl
				+ "/app.controle/api/slide/imagens/fundo_link_slide_on.png') no-repeat";
	}
	setZindex(1);
	if (qtdSlide > 1) {
		time_slide = setTimeout('mudaSlide(1,\'' + id + '\')', intervaloSlide);
	}
}

// Redefine o tipo de cursor de um elemento
function setCursorSlide(obj, tipo) {
	obj.style.cursor = tipo;
}

// Define o Zindex do slides. Parâmetros:(id do slide que ser� exibido)
function setZindex(proximo) {
	for (i = 1; i <= lista_slide.length - 1; i++) {
		document.getElementById(lista_slide[i]).style.zIndex = 10;
	}
	var linkshow = document.getElementById(lista_slide[proximo]);
	linkshow.style.zIndex = 20;
}

// Para a apresenta��o dos slides e exibe o slide referente ao link clicado.
// Par�metros(id do slide que ser� exibido,id do slide escondido(1),id do slide
// escondido(2),id do link clicado)
function stopSlide(idshow, idlink) {
	for (i = 1; i <= lista_slide.length - 1; i++) {
		document.getElementById(lista_slide[i]).style.visibility = "hidden";
		// document.getElementById(lista_slide[i]).style.position = "absolute";
		// document.getElementById(lista_slide[i]).style.top=0+'px';
		document.getElementById(lista_slide[i]).style.zIndex = 10;
		setAlpha(document.getElementById(lista_slide[i]), 100);
		if (showLinksSlide == 'true') {
			document.getElementById('link_' + lista_slide[i]).style.background = "url('"
					+ globalUrl
					+ "/app.controle/api/slide/imagens/fundo_link_slide.png') no-repeat";
		}
	}
	var slshow = document.getElementById(lista_slide[idshow]);
	// setAlpha(slshow, 100);
	// slshow.style.position = "relative";
	slshow.style.zIndex = 20;
	slshow.style.visibility = "visible";
	if (showLinksSlide == 'true') {
		document.getElementById('link_' + lista_slide[idshow]).style.background = "url('"
				+ globalUrl
				+ "/app.controle/api/slide/imagens/fundo_link_slide_on.png') no-repeat";
	}

	clearTimeout(time_slide);
}

// Anula a fun��o iniciaSlide()
function cancelaSlide() {
	clearTimeout(time_slide);
}
