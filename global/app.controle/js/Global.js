// #### Esse arquivo é inserido em index.php ####
var xhr = null;
var AjaxShowvs = null;
var AjaxCtrlGet = null;
var AjaxSubmitForm = null;
var AjaxCtrlPost = null;

function carregaScript(scriptjs) {
	var js = document.createElement("SCRIPT");
	js.type = "text/javascript";
	js.language = "javascript";
	js.src = scriptjs;

	// var conteudo = document.getElementById("divHeadJs");
	$(js).appendTo(document.getElementsByTagName('head')[0]);
	// Tem que usar jquery pois appendChild não funciona no chrome
}

// Adiciona à tag head as tags <scripts> carregadas via ajax
function callJs(exibeResultado) {
	// Declarando a criação de uma nova tag <script>
	var scripts = document.createElement("script");
	scripts.type = "text/javascript";
	// Pegando os valores das Tags script que estão na página carregada pelo AJAX
	var js = exibeResultado.getElementsByTagName("script");
	// Inserir o conteúdo da tag <script> que pegamos na linha acima
	for (i = 0; i < js.length; i++) {
		// Se houver um src carrega o script
		if (js[i].src) {
			carregaScript(js[i].src);
		} else {
			scripts.text = scripts.text + js[i].innerHTML;
		}
	}
	document.getElementsByTagName('head')[0].appendChild(scripts);
}

function sleep(milliseconds) {
	var start = new Date().getTime();
	for (var i = 0; i < 1e7; i++) {
		if ((new Date().getTime() - start) > milliseconds) {
			break;
		}
	}
}

/**
 * Rastreia uma página pelo google analytics
 */ 
function trackGoogleAnalytics(url) {
	// A url é relativa
	//_gaq.push([ '_trackPageview', url ]);
	// alert('trackGoogleAnalytics: ' + url);
}

/**
 * Abre o ajax criando o elemento Ajax
 */ 
function openAjax() {
	try {
		// XMLHttpRequest para browsers mais populares, como: Firefox, Safari, dentre outros.
		var Ajax = new XMLHttpRequest(); 
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
 * Carrega uma página em um container via ajax utilizando o método Post Envia
 * para lib/view.php qual classe deve ser insanciada e retornar o html gerado
 * 
 * @param (String)
 *            container
 * @param (String)
 *            nome do arquivo Vs
 * @param (String)
 *            variaveis em formato get
 * @param (String)
 *            mensagem de carregamento
 * @param (int)
 *            altura do container)
 */
function showVs(target, pg, posts, loadMsg, h, utf8) {
	if(!target){
		target = 'divCallJs';
	}
	// Mensagem de load
	var exibeResultado = document.getElementById(target);
	exibeResultado.style.height = '1vw';
	if (h) {
		exibeResultado.style.height = h + '';
	}
	if (loadMsg) {
		$('#'+target).html("<div align='center' style='border:0px solid #0044FF;height:"
				+ h
				+ "px;position:relative'><div align='center' style='position:absolute;top:10%;width:100%'><img src='"
				+ globalUrl
				+ "/global/app.visual/imagens/loading.gif' /> <font face='Tahoma' size='-1' color='#CCCCCC'><strong>"
				+ loadMsg + " </strong></font></div></div>");
	}
	
	// Criando o FormData
	var formData = new FormData();
	  
	formData.append( 'vs', pg );
	formData.append( 'nocache', Math.random() );
	formData.append( 'utf8', 'false' );
	
	var post = posts.split('&');
	post.shift();
	
	for (i = 0; i < post.length; i++) {
		var dados = post[i].split('=');
		//alert(dados[0]+'='+dados[1]);
		formData.append( dados[0], dados[1] );
	} 
	
	var url = globalUrl + "/framework/lib/view.php";
	$.ajax({
		url: url,
		type: 'POST',
		data: formData,
		async: false,
		success: function (html) {
			$('#'+target).html(html);
		    },
	    cache: false,
	    contentType: false,
	    processData: false,
	    error: function (request, status, error) {
	        alert(request.responseText);
	    }
	});
}


function showVsDeprecated(target, pg, gets, loadMsg, h, utf8) {
	if (document.getElementById) { // Para os browsers complacentes com o DOM
		// alert(pg);
		// Variáveis que serão enviadas
		var dadosPost = "vs=" + pg + "&nocache=" + Math.random() + "&"+gets;
		// Container que exibirá o resultado.
		if(!target){
			target = 'divCallJs';
		}
		var exibeResultado = document.getElementById(target);
		// Definindo a altura do container
		//exibeResultado.style.height = '1vw';
		if (h) {
			exibeResultado.style.height = h+'';
		}
		
		if (utf8) {
			dadosPost = dadosPost + "&utf8=true";
		} else {
			dadosPost = dadosPost + "&utf8=false";
		}
		// Aborta o ajax anterior
		if (AjaxShowvs != null) {
			AjaxShowvs.abort();
		}
		// Inicia o Ajax.
		AjaxShowvs = openAjax();
		var url = globalUrl + "/framework/lib/view.php";
		// Fazendo a requisição
		AjaxShowvs.open("POST", url, true);
		// Quando estiver carregando exibe a mensagem
		if (loadMsg) {
			exibeResultado.innerHTML = "<div align='center' style='border:0px solid #0044FF;height:"
					+ h
					+ "px;position:relative'><div align='center' style='position:absolute;top:10%;width:100%'><br /><img src='"
					+ globalUrl
					+ "/global/app.visual/imagens/loading.gif' /> <font face='Tahoma' size='-1' color='#CCCCCC'><strong>"
					+ loadMsg + " </strong></font></div></div>";
		}
		// Header
		AjaxShowvs.setRequestHeader("Content-type",
				"application/x-www-form-urlencoded;");
		AjaxShowvs.onreadystatechange = function() {
			// Caso o ajax seja nulo recarrega a função. Mais um bug do IE.
			if (AjaxShowvs == null) {
				showVs(target, pg, gets, loadMsg, h);
				return false;
			}
			if (AjaxShowvs.readyState == 4) { // Quando estiver tudo pronto.
				if (AjaxShowvs.status == 200) {
					// Coloca o retornado pelo Ajax nessa variável
					var resultado = encodeURIComponent(AjaxShowvs.responseText);
					//alert(resultado);
					//alert(encodeURIComponent(resultado));
					// Resolve o problema dos acentos
					resultado = resultado.replace(/\+/g, " ");
					// Resolve o problema dos acentos
					//resultado = unescape(resultado);
					var iddiv = Math.random();
					exibeResultado.innerHTML = "<div id='"+iddiv+"'>"+resultado+"</div>";
					$("#"+target).css({
					    height:$("#"+iddiv).height()
					});
					//exibeResultado.innerHTML = resultado;
					//alert(resultado);
					callJs(exibeResultado);
					if (resultado.replace(/^\s+|\s+$/g, "") == '') {
						// alert(resultado);
						// showVs(target,pg,gets,loadMsg,h);
						return false;
					}
				} else {
					// Erro no carregamento da página
					// showVs(target, pg, gets, loadMsg, h);
					alert(AjaxShowvs.responseText);
					exibeResultado.innerHTML = AjaxShowvs.responseText;
					return false;
				}
			}
		}
		AjaxShowvs.send(dadosPost);
	}
}

/**
 * Chama o arquivo ctrl.php que instancia um controle (ctrl) e chama uma função 
 * desse controle (f) passando parametros via POST
 * 
 */ 
function callCtrlPost(target, ctrl, f, posts, loadMsg, h) {
	if(!target){
		target = 'divCallJs';
	}
	// Mensagem de load
	var exibeResultado = document.getElementById(target);
	exibeResultado.style.height = '1vw';
	if (h) {
		exibeResultado.style.height = h + '';
	}
	if (loadMsg) {
		$('#'+target).html("<div align='center' style='border:0px solid #0044FF;height:"
				+ h
				+ "px;position:relative'><div align='center' style='position:absolute;top:10%;width:100%'><img src='"
				+ globalUrl
				+ "/global/app.visual/imagens/loading.gif' /> <font face='Tahoma' size='-1' color='#CCCCCC'><strong>"
				+ loadMsg + " </strong></font></div></div>");
	}
	
	// Criando o FormData
	var formData = new FormData();
	  
	formData.append( 'ctrl', ctrl );
	formData.append( 'nocache', Math.random() );
	formData.append( 'f', f );
	formData.append( 'caller', 'callCtrlPost' );
	var post = posts.split('&');
	post.shift();
	
	for (i = 0; i < post.length; i++) {
		var dados = post[i].split('=');
		formData.append( dados[0], dados[1] );
	} 
	
	var url = globalUrl + "/framework/lib/ctrl.php";
	$.ajax({
		url: url,
		type: 'POST',
		data: formData,
		async: false,
		success: function (html) {
			$('#'+target).html(html);
		    },
	    cache: false,
	    contentType: false,
	    processData: false,
	    error: function (request, status, error) {
	        alert(request.responseText);
	    }
	});
}

function callCtrlPostDeprecated(target, ctrl, f, posts, loadMsg, h) {
	// Para os browsers complacentes com o DOM W3C.
	if (document.getElementById) { 
		var dadosPost = "ctrl=" + ctrl + "&f=" + f + "&nocache="
				+ Math.random() + posts;
		// container que exibirá o resultado.
		// Se não houver target exibe no div padrão divCallJs (em index.php)
		// Usado para carregar js sem precisar mostrar algum conteúdo
		if(!target){
			target = 'divCallJs';
		}
		var exibeResultado = document.getElementById(target); 
		exibeResultado.style.height = '1vw';
		if (h) {
			exibeResultado.style.height = h + '';
		}
		// Aborta o ajax anterior
		if (AjaxCtrlPost != null) {
			AjaxCtrlPost.abort();
		}
		// Inicia o Ajax.
		var AjaxCtrlPost = openAjax(); 
		var url = globalUrl + "/framework/lib/ctrl.php";
		// Fazendo a requisição
		AjaxCtrlPost.open("POST", url, true); 
		// Quando estiver carregando exibe a mensagem
		if (loadMsg) {
			exibeResultado.innerHTML = "<div align='center' style='border:0px solid #0044FF;height:"
					+ h
					+ "px;position:relative'><div align='center' style='position:absolute;top:10%;width:100%'><img src='"
					+ globalUrl
					+ "/global/app.visual/imagens/loading.gif' /> <font face='Tahoma' size='-1' color='#CCCCCC'><strong>"
					+ loadMsg + " </strong></font></div></div>";
		}
		AjaxCtrlPost.setRequestHeader("Content-type",
				"application/x-www-form-urlencoded; charset=UTF-8"); 
		// Setando Content-type
		AjaxCtrlPost.onreadystatechange = function() {
			// Caso o ajax seja nulo recarrega a função. Mais um bug do IE.
			if (AjaxCtrlPost == null) {
				//callCtrlPost(target, ctrl, f, posts, loadMsg, h);
				return false;
			}
			// Quando estiver tudo pronto.
			if (AjaxCtrlPost.readyState == 4) { 
				if (AjaxCtrlPost.status == 200) {
					var resultado = AjaxCtrlPost.responseText; 
					// Coloca o retornado pelo Ajax nessa variável
					resultado = resultado.replace(/\+/g, " "); 
					// Resolve o problema dos acentos (saiba mais aqui: http://www.plugsites.net/leandro/?p=4)
					resultado = unescape(resultado); 
					// Resolve o problema dos acentos
					if(resultado != ""){
						//exibeResultado.innerHTML = resultado;
						exibeResultado.innerHTML = "<div class='div_glb_ctrlpost'>"+resultado+"</div>";
						$("#"+target).css({
						    height:$(".div_glb_ctrlpost").height()
						});
						callJs(exibeResultado);
					}
					// alert(resultado);
				} else {
					// exibeResultado.innerHTML = AjaxCtrlPost.responseText;
					callCtrlPost(target, ctrl, f, posts, loadMsg, h)
				}
			}
		}
		AjaxCtrlPost.send(dadosPost);
	}
}

/**
 * Chama o arquivo ctrl.php que instancia um controle (ctrl) e chama uma função 
 * desse controle (f) passando parametros via POST
 * Concatena o resultado com o conteúdo já existente no target
 */ 
function callCtrlPostConcat(target, ctrl, f, posts, loadMsg, h) {
	// Para os browsers complacentes com o DOM W3C.
	if (document.getElementById) { 
		var dadosPost = "ctrl=" + ctrl + "&f=" + f + "&nocache="
				+ Math.random() + "&caller=callCtrlPostConcat" + posts;
		// Container que exibirá o resultado.
		// Se não houver target exibe no div padrão divCallJs (em index.php)
		// Usado para carregar js sem precisar mostrar algum conteúdo
		if(!target){
			target = 'divCallJs';
		}
		var exibeResultado = document.getElementById(target); 
		//exibeResultado.style.height = '1vw';
		if (h) {
			exibeResultado.style.height = h + '';
		}
		// Aborta o ajax anterior
		if (AjaxCtrlPost != null) {
			AjaxCtrlPost.abort();
		}
		// Inicia o Ajax.
		var AjaxCtrlPost = openAjax(); 
		var url = globalUrl + "/framework/lib/ctrl.php";
		// Fazendo a requisição
		AjaxCtrlPost.open("POST", url, true); 
		// Quando estiver carregando exibe a mensagem
		if (loadMsg) {
			
		}
		AjaxCtrlPost.setRequestHeader("Content-type",
				"application/x-www-form-urlencoded"); 
		// Setando Content-type
		AjaxCtrlPost.onreadystatechange = function() {
			// Caso o ajax seja nulo recarrega a função. Mais um bug do IE.
			if (AjaxCtrlPost == null) {
				callCtrlPost(target, ctrl, f, posts, loadMsg, h);
				return false;
			}
			// Quando estiver tudo pronto.
			if (AjaxCtrlPost.readyState == 4) { 
				if (AjaxCtrlPost.status == 200) {
					var resultado = AjaxCtrlPost.responseText; 
					// Coloca o retornado pelo Ajax nessa variável
					resultado = resultado.replace(/\+/g, " "); 
					// Resolve o problema dos acentos (saiba mais aqui: http://www.plugsites.net/leandro/?p=4)
					resultado = unescape(resultado); 
					// Resolve o problema dos acentos
					if(resultado != ""){
						exibeResultado.innerHTML = exibeResultado.innerHTML+resultado;
						callJs(exibeResultado);
					}
					// alert(resultado);
				} else {
					// exibeResultado.innerHTML = AjaxCtrlPost.responseText;
					callCtrlPost(target, ctrl, f, posts, loadMsg, h)
				}
			}
		}
		AjaxCtrlPost.send(dadosPost);
	}
}

function formUpload (target, ctrl, f, nomeForm, loadMsg, h) {
	var form = document.getElementById(nomeForm);
	
	// Mensagem de load
	var exibeResultado = document.getElementById(target);
	exibeResultado.style.height = '1vw';
	if (h) {
		exibeResultado.style.height = h + '';
	}
	if (loadMsg) {
		$('#'+target).html("<div align='center' style='border:0px solid #0044FF;height:"
				+ h
				+ "px;position:relative'><div align='center' style='position:absolute;top:10%;width:100%'><img src='"
				+ globalUrl
				+ "/global/app.visual/imagens/loading.gif' /> <font face='Tahoma' size='-1' color='#CCCCCC'><strong>"
				+ loadMsg + " </strong></font></div></div>");
	}

	
	// Criando o FormData
	var formData = new FormData();
	  
	formData.append( 'ctrl', ctrl );
	formData.append( 'f', f );
	formData.append( 'formdw436rtd4', nomeForm );
	formData.append( 'caller', 'formUpload' );
	for (i = 0; i < form.elements.length; i++) {
		elm = form.elements[i];
		if (elm.type != 'radio' || (elm.type == 'radio' && elm.checked == true)) {
			formData.append( elm.name, encodeURIComponent(elm.value) );
		}
		if (elm.type == 'file') {
			// Input de arquivos
			var files = elm.files;
			// Loop para cada arquivo
			for (var j = 0; j < files.length; j++) {
				var file = files[j];
				
				// Checando o tipo.
				//if (!file.type.match('image.*')) {
				//  continue;
				//}
				
				// Adicionando o arquivo
				formData.append(elm.name, file, file.name);
			}
		}
	}

	var url = globalUrl + "/framework/lib/ctrl.php";
	
	$.ajax({
		url: url,
		type: 'POST',
		data: formData,
		async: false,
		success: function (html) {
			$('#'+target).html(html);
		    },
	    cache: false,
	    contentType: false,
	    processData: false,
	    error: function (request, status, error) {
	        alert(request.responseText);
	    }
	});
}

/**
 * Submete um form.
 * @param () target
 * @param classe de controle que será chamada
 * @param função do controle a ser chamada
 * @param nome do form
 * @param mensagem
 * @param altura do container
 * 
 */ 
function submitForm2(target, ctrl, f, nomeForm, loadMsg, h) {
	$('#'+nomeForm).submit(function(event) {
		alert('foi1');
		e.preventDefault();
		//e.stopImmediatePropagation();
		
		var exibeResultado = document.getElementById(target);
		exibeResultado.style.height = '1vw';
		if (h) {
			exibeResultado.style.height = h + '';
		}
		if (loadMsg) {
			$('#'+target).html("<div align='center' style='border:0px solid #0044FF;height:"
					+ h
					+ "px;position:relative'><div align='center' style='position:absolute;top:10%;width:100%'><img src='"
					+ globalUrl
					+ "/global/app.visual/imagens/loading.gif' /> <font face='Tahoma' size='-1' color='#CCCCCC'><strong>"
					+ loadMsg + " </strong></font></div></div>");
		}
		
		var formData = new FormData($(this)[0]);
		var url = globalUrl + "/framework/lib/ctrl.php";
		formData.append( 'ctrl', ctrl );
		formData.append( 'f', f );
		formData.append( 'formdw436rtd4', nomeForm );
		
	    $.ajax({
	        url: url,
	        type: 'POST',
	        data: formData,
	        async: false,
	        success: function (html) {
	        	$('#'+target).html(html);
	        },
	        cache: false,
	        contentType: false,
	        processData: false
	    });
	  //  return false;
	});
	//$("#"+nomeForm).submit();
}

/**
 * 
 */
function submitForm(target, ctrl, f, nomeForm, loadMsg, h) {
	// Para os browsers complacentes com o DOM
	if (document.getElementById) { 
		// W3C.
		var dadosPost = "ctrl=" + ctrl + "&f=" + f + "&formdw436rtd4="
				+ nomeForm + "&caller=submitForm";
		var elm;
		for (i = 0; i < document.forms[nomeForm].elements.length; i++) {
			elm = document.forms[nomeForm].elements[i];
			if (elm.type != 'radio' || (elm.type == 'radio' && elm.checked == true)) {
				if(elm.type == 'checkbox') {
					if(elm.checked == true) {
						dadosPost = dadosPost + '&'
						+ elm.name + '='
						+ elm.value;
					}
				} else {
					dadosPost = dadosPost + '&'
					+ elm.name + '='
					+ encodeURIComponent(elm.value);
				}
			}
		}
		if(!target){
			target = 'divCallJs';
		}
		var exibeResultado = document.getElementById(target);
		// Container que exibirá o resultado.
		// exibeResultado.style.height = '1vw';
		if (h) {
			exibeResultado.style.height = h + '';
		}
		// Aborta o ajax anterior
		if (AjaxSubmitForm != null) {
			AjaxSubmitForm.abort();
		}
		// Inicia o Ajax.
		var AjaxSubmitForm = openAjax(); 
		var url = globalUrl + "/framework/lib/ctrl.php";
		// Fazendo a requisição
		AjaxSubmitForm.open("POST", url, true); 
		// Quando estiver carregando exibe a mensagem
		if (loadMsg) {
			exibeResultado.innerHTML = "<div align='center' style='border:0px solid #0044FF;height:"
					+ h
					+ "px;position:relative'><div align='center' style='position:absolute;top:10%;width:100%'><img src='"
					+ globalUrl
					+ "/global/app.visual/imagens/loading.gif' /> <font face='Tahoma' size='-1' color='#CCCCCC'><strong>"
					+ loadMsg + " </strong></font></div></div>";
		}
		// Setando Content-type
		AjaxSubmitForm.setRequestHeader("Content-type",
				"application/x-www-form-urlencoded"); 
		AjaxSubmitForm.onreadystatechange = function() {
			// Caso o ajax seja nulo recarrega a função. Mais uma bug do IE.
			if (AjaxSubmitForm == null) {
				submitForm(target, ctrl, f, nomeForm, loadMsg, h);
				return false;
			}
			// Quando estiver tudo pronto.
			if (AjaxSubmitForm.readyState == 4) { 
				if (AjaxSubmitForm.status == 200) {
					// Coloca o retornado pelo Ajax nessa variável
					var resultado = AjaxSubmitForm.responseText; 
					// Resolve o problema dos acentos
					resultado = resultado.replace(/\+/g, " "); 
					resultado = unescape(resultado); 
					// Exibe o resultado
					exibeResultado.innerHTML = "<div class='div_glb_submit'>"+resultado+"</div>";
					$("#"+target).css({
					    height:$(".div_glb_submit").height()
					});
					callJs(exibeResultado);
				} else {
					// exibeResultado.innerHTML = AjaxSubmitForm.responseText;
					submitForm(target, ctrl, f, nomeForm, loadMsg, h);
				}
			}
		}
		//alert(dadosPost);
		AjaxSubmitForm.send(dadosPost);
	}
}

function submitFormToLink(link, nomeForm, blank) {
	document.forms[nomeForm].action = link;
	alert(document.forms[nomeForm].action);
	if(blank == true) {
		document.forms[nomeForm].target = '_blank';
	} 
	document.forms[nomeForm].submit();
}

function submitFormToLink2(link, nomeForm, blank) {
	// Para os browsers complacentes com o DOM
	if (document.getElementById) { 
		// W3C.
		var dadosPost = "link=" + link + "&blank=" + blank;
		var elm;
		for (i = 0; i < document.forms[nomeForm].elements.length; i++) {
			elm = document.forms[nomeForm].elements[i];
			if (elm.type != 'radio' || (elm.type == 'radio' && elm.checked == true)) {
				if(elm.type == 'checkbox') {
					if(elm.checked == true) {
						dadosPost = dadosPost + '&'
						+ elm.name + '='
						+ elm.value;
					}
				} else {
					dadosPost = dadosPost + '&'
					+ elm.name + '='
					+ encodeURIComponent(elm.value);
				}
			}
		}
		target = 'divcalljs_we3746hejrg';
		var exibeResultado = document.getElementById('divcalljs_we3746hejrg');
		
		// Aborta o ajax anterior
		if (AjaxSubmitForm != null) {
			AjaxSubmitForm.abort();
		}
		// Inicia o Ajax.
		var AjaxSubmitForm = openAjax(); 
		var url = globalUrl + "/framework/lib/formtolink.php";
		// Fazendo a requisição
		AjaxSubmitForm.open("POST", url, true); 
		// Quando estiver carregando exibe a mensagem
		
		// Setando Content-type
		AjaxSubmitForm.setRequestHeader("Content-type",
				"application/x-www-form-urlencoded"); 
		AjaxSubmitForm.onreadystatechange = function() {
			// Caso o ajax seja nulo recarrega a função. Mais uma bug do IE.
			if (AjaxSubmitForm == null) {
				submitForm(target, ctrl, f, nomeForm, loadMsg, h);
				return false;
			}
			// Quando estiver tudo pronto.
			if (AjaxSubmitForm.readyState == 4) { 
				if (AjaxSubmitForm.status == 200) {
					// Coloca o retornado pelo Ajax nessa variável
					var resultado = AjaxSubmitForm.responseText; 
					// Resolve o problema dos acentos
					resultado = resultado.replace(/\+/g, " "); 
					resultado = unescape(resultado); 
					// Exibe o resultado
					exibeResultado.innerHTML = "<div class='div_glb_submit'>"+resultado+"</div>";
					$("#"+target).css({
					    height:$(".div_glb_submit").height()
					});
					callJs(exibeResultado);
				} else {
					// exibeResultado.innerHTML = AjaxSubmitForm.responseText;
					submitForm(target, ctrl, f, nomeForm, loadMsg, h);
				}
			}
		}
		AjaxSubmitForm.send(dadosPost);
	}
}