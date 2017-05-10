/**
 * Busca endereços através do CEP no site cep.republicavirtual.com.br
 */
function getEndCep(cep, idEnd, idBairro, idCidade, idUf) {
	$("#"+idEnd).val("Carregando...");
	$("#"+idBairro).val("Carregando...");
	$("#"+idCidade).val("Carregando...");
		
	$.getScript("http://cep.republicavirtual.com.br/web_cep.php?cep="+cep+"&formato=javascript",function() {
		rua=unescape(resultadoCEP.logradouro)
		bairro=unescape(resultadoCEP.bairro)
		cidade=unescape(resultadoCEP.cidade)
		tipo=unescape(resultadoCEP.tipo_logradouro)
		uf=unescape(resultadoCEP.uf)
		
		$("#"+idEnd).val(tipo+" "+rua);
		$("#"+idBairro).val(bairro);
		$("#"+idCidade).val(cidade);
		$("#"+idUf).val(uf);
	});
}

/**
 * Máscara de data com validação
 */
function validaData(data) {
	var date = data.split("/");
    var d = parseInt(date[0], 10),
        m = parseInt(date[1], 10),
        a = parseInt(date[2], 10);
    var rt = true; 
    
    // Se ano estiver entre 1500 e 2100
    if((a > 1500) && (a <= 2100)) {
	    // Se mes está entre 1 e 12
	    if((m >= 1) || (m <= 12)) {
	    	// Meses com 31 dias
		    if((m==1) || (m==3) || (m==5) || (m==7) || (m==8) || (m==10) || (m==12)) {
		    	// Se dia não está entre 1 e 31
		    	if(d <= 0 || d > 31) {
		    		rt = false;
		    	}
		    } else {
		    	// Se fevereiro
		    	if(m==2) {
		    		// Se ano é bissexto
		    		if ((a % 4 == 0) && ((a % 100 != 0) || (a % 400 == 0))) {
	    				// Se dia não está entre 1 e 29
	    		    	if((d <= 0) || (d > 29)) {
	    		    		rt = false;
	    		    	}
		    		} else {
		    			// Se dia não está entre 1 e 28
	    		    	if((d <= 0) || (d > 28)) {
	    		    		rt = false;
	    		    	}
		    		}
		    	} else {
		    		// Se dia não está entre 1 e 30
			    	if((d <= 0) || (d > 30)) {
			    		rt = false;
			    	}
		    	}
		    }
	    } else {
	    	rt = false;
	    }
    } else {
    	rt = false;
    }
    
    if(!rt) {
    	alert('Data inválida');
    	return false;
    }
    return true;
}

/**
 * Define a quantidade de caracteres permitidos em um input text
 * 
 * @param (string)
 *            id do input de texto
 * @param (string)
 *            id do input contador
 * @param (int)
 *            qtd máxima de caracteres
 * 
 * 
 * <p>
 * Exemplo de chamada no elemento:
 * 'onkeyup'=>'contar_char(this,document.form.input_contChar,40)'
 * </p>
 */
function contar_char(obj, objContador, maxLength) {
	var x = maxLength - (obj.value.length);
	if (x <= 0) {
		obj.value = obj.value.substr(0, maxLength)
		objContador.value = 'Caracteres restantes: ' + '0';
		alert("A quantidade máxima de caracteres (" + maxLength
				+ ") foi atingida!")
	} else {
		objContador.value = 'Caracteres restantes: ' + x;
	}
}

/**
 * Formata um campo input de acordo com a máscara passada como parâmetro.
 * 
 * @param (obj)
 *            objeto
 * @param (string)
 *            máscara
 * @param (event)
 *            event
 *            Ex: onkeypress="maskForm(this,'(##)####-####');
 *            Colocar somente até 2 caracteres juntos na máscara.
 *            Ex: ) e - juntos: (##)-####-####. Espaço conta como caractere
 *            Ex: ) e espaço em branco juntos (##) ####-####
 */
function maskForm(obj, mask, evt) {
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode != 8) {
		if (obj.value.length < mask.length) {
			var i = obj.value.length;
			var saida = '#';
			var texto = mask.substring(i)// (##) ####-####
			var tx = texto.substring(0, 1);
			if (tx != saida) {// ) != #
				obj.value += tx;
				if (texto.substring(1, 2) != saida) {
					tx = texto.substring(1, 2);
					if (texto.substring(1, 2) == '') {
						tx = ' '
					}
					;
					obj.value += tx;
				}
			}
		}
	}
}

/**
 * Reseta o input quando focado e o valor é igual ao parâmetro passado.
 * 
 * @param (string)
 *            id do obj
 * @param (string)
 *            valor a ser comparado
 */
function resetValue(id, conteudo) {
	if (document.getElementById(id).value == conteudo) {
		document.getElementById(id).value = '';
	}
}

/**
 * Usada na captura de valores de formulários para evitar sql inject e código
 * 
 * @param (string)
 *            valor do input (this.value)
 * @param (string)
 *            id do input
 *            <p>
 *            Ex: onkeyup="segurancaSql(this.value,'msn');"
 *            </p>
 *            Adicionada automaticamente em todos os inputs se criados pela
 *            classe Input ou InputCustom
 */
function segurancaSql(valor, id) {
	var invalid = new Array("#", "<", ">", "href", "link(", '"', "[", "]",
			"url", "link=", "'", "delete ", "update ", "drop ", "/*", "*/")
	for (i = 0; i < invalid.length; i++) {
		valor = valor.toLowerCase()
		if (valor.indexOf(invalid[i]) != -1) {
			alert('Caractere nao permitido: ' + invalid[i]);
			document.getElementById(id).value = valor.substr(0, valor
					.indexOf(invalid[i]));
		}
	}
	var invalid2 = "-->"
	// Usado para evitar erro na validação do w3c. Significa que a tag
	// comentário não foi completada.
	if (valor.indexOf(invalid2.substr(0, 2)) != -1) {
		alert('Caractere nao permitido: ' + invalid2.substr(0, 2));
		document.getElementById(id).value = valor.substr(0, valor
				.indexOf(invalid2.substr(0, 2)));
	}
}

/**
 * Só permite a escrita de numeros
 * 
 * @param (string)
 *            valor do input (this.value)
 * @param (string)
 *            id do input
 *            <p>
 *            onkeypress="return somenteNum(event);"
 *            </p>
 */
// Só permite a escrita de numeros. @param(valor do d�gito). Ex:
// onkeypress="return somenteNum(event);"
function somenteNum(evt) {
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		return false;
	} else {
		return true;
	}
}

/**
 * 
 * Bloqueia a entrada de determinados caracteres.
 * 
 * @param (event)
 *            event
 * @param (exp
 *            reg) tipo de blqueio
 *            <p>
 *            Ex:onkeypress="return blockCharInput(event, /[^0-9]+/g);"
 *            </p>
 *            <li>(0) Somente números: /[^0-9]+/g
 *            <li>(1) Somente letras min.: /[^a-z]+/g
 *            <li>(2) Somente letras maiusc. e minusc.: /[^a-z]+/gi
 *            <li>(3) Letras e números : /[^a-z0-9]+/gi
 *            <li>(4) Custom : /[^2468]+/g
 */
function blockCharInput(evt, pattern) {
	var ch = String.fromCharCode(evt.which);
	if (evt.which != 8) {
		var new_value = '';
		switch (pattern) {
		case 0:
			new_value = ch.replace(/[^0-9]+/g, '');
			break;
		case 1:
			new_value = ch.replace(/[^a-z]+/g, '');
			break;
		case 2:
			new_value = ch.replace(/[^a-z]+/gi, '');
			break;
		case 3:
			new_value = ch.replace(/[^a-z0-9]+/gi, '');
			break;
		case 4:
			new_value = ch.replace(/[^2468]+/g, '');
			break;
		}
		if (new_value == '') {
			return false;
		}
	}
	return true;
}

/**
 * Define o action de um formulário
 * 
 * @param (string)
 *            nomeForm
 * @param (string)
 *            destino
 */
function setAction(nomeForm, destino) {
	document.forms[nomeForm].action(destino);
}

/**
 * Valida a quantidade de caracteres de um valor
 * 
 * @param (obj)
 *            obj input
 * @param (string)
 *            tamanho máximo
 */
function validaLength(obj, tam) {
	if (obj.value.length != tam && obj.value != '') {
		alert('Dado inválido no campo ' + obj.name
				+ '.\n\nPor favor informe novamente.');
		obj.value = '';
	}
}

/**
 * Valida o formato de um numero de celular com ddd e 9 dígitos (81) 986542365
 */
function validaCelular(element) {
	num = element.value;
	// alert(num.match('^[\(][1-9]{2}[\)] [9][8-9][0-9]{7}$'));
	if(num.match('^[9][8-9][0-9]{7}$') == null) {
		if(!confirm('Parece que você informou um número inválido '+num+'. Deseja manter?')) {
			element.value = '';
		}
	}
}

/**
 * Valida o formato de um numero de telefone residencial com ddd (81) 33335599
 */
function validaFone(element) {
	num = element.value;
	// alert(num.match('^[\(][1-9]{2}[\)] [9][8-9][0-9]{7}$'));
	if(num.match('^[\(][1-9]{2}[\)] [2-9]{8}$') == null) {
		if(!confirm('Parece que você informou um número inválido '+num+'. Deseja manter?')) {
			element.value = '';
		}
	}
}

/**
 * Valida o formato de um telefone (xx) 9xxxxxxxx
 */
function validaEmail(element) {
	email = element.value;
	if(email.match('^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+.[A-Za-z]{2,4}$') == null) {
		if(!confirm('Parece que você informou um email inválido ('+email+'). Deseja manter?')) {
			element.value = '';
		}
	}
}

/**
 * Verifica se o valor de um input é vazio e redefine a borda
 * 
 * @param (obj)
 *            obj input
 * @param (string)
 *            mensagem
 */
function validaVazio(obj, msg) {
	if (trim(obj.value) == '' || obj.value == '_Digite seu email aqui') {
		alert(msg);
		return false;
	} else {
		return true;
	}
}

function isEmpty(id, msg){
	var obj = document.getElementById(id);
	if (trim(obj.value) == ''){
		alert(msg);
		return false;
	} else {
		return true;
	}
}

/**
 * Valida o tipo de arquivo de um input file
 * 
 * @param (obj)
 *            obj file
 */
function valida_ext(obj) {
	tipo = obj.value.substring(obj.value.length - 4, obj.value.length);
	tipo = tipo.toLowerCase()

	if (tipo == 'jpeg' || tipo == '.jpg' || tipo == '.gif') {
		return true;
	} else {
		alert('Somente são permitidos arquivos com extensões .jpeg, .jpg e .gif.');
		return false;
	}
}