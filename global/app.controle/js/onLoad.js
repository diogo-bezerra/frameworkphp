/**
 * Abre o ajax criando o elemento Ajax
 */
function openAj() {
	try {
		var Ajax = new XMLHttpRequest(); // XMLHttpRequest para browsers mais
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
 * Utilizada pelo index.php para carregar todos os scripts via ajax. Ver
 * config/setScripts.php e deve ser criado em index.html
 */

function setScripts() {
	//alert(globalUrl + "/framework/config/setScripts.php");
	$.ajax({
		url : globalUrl + "/framework/config/setScripts.php",
		data : "a=a",
		type : "POST",
		async : false,
		success : function(data) {
			var arrayJs = data.split('.js');
			// Gera as tags js com os resultados de setScripts.php e adiciona
			// via append a tag head
			for (i = 0; i < arrayJs.length - 1; i++) {
				var sc = document.createElement("script");
				sc.type = "text/javascript";
				sc.src = globalUrl + '/' + arrayJs[i] + '.js';
				$(sc).appendTo(document.getElementsByTagName('head')[0]);
				// alert(globalUrl + '/' +arrayJs[i]+'.js');
			}
		}
	});
}

/**
 * Carrega scripts de uma view quando chamada via ajax.
 * Chamada em App.View->show()
 */
function setScript(dir) {
	var sc = document.createElement("script");
	sc.type = "text/javascript";
	sc.src = dir;
	$(sc).appendTo(document.getElementsByTagName('head')[0]);
}
