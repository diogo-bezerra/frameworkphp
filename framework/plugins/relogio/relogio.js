var horaRelogio7823gb784g = 2;
function getHora(tam, cor, fonte, target) {
	if (document.getElementById) { // Para os browsers complacentes com o DOM
		var dadosPost = 'globalUrl=' + globalUrl + "&tam=" + tam + "&cor="
				+ cor + "&fonte=" + fonte;
		exibeResultado = document.getElementById(target);

		if (horaRelogio7823gb784g == 2) {
			exibeResultado.innerHTML = '<div id="div1horaRelogio7823gb784g">zzz</div><div id="div2horaRelogio7823gb784g">xxx</div>';
			document.getElementById('div1horaRelogio7823gb784g').style.position = 'absolute';
			document.getElementById('div2horaRelogio7823gb784g').style.position = 'absolute';
			document.getElementById('div1horaRelogio7823gb784g').style.visibility = 'hidden';
			document.getElementById('div2horaRelogio7823gb784g').style.visibility = 'hidden';
			horaRelogio7823gb784g = 0;
		}
		div1 = document.getElementById('div1horaRelogio7823gb784g');
		div2 = document.getElementById('div2horaRelogio7823gb784g');
		AjaxShowvs = openAjax(); // Inicia o Ajax.
		var url = globalUrl + "/plugins/relogio/getHora.php";
		AjaxShowvs.open("POST", url, true); // Fazendo a requisição

		AjaxShowvs.setRequestHeader("Content-type",
				"application/x-www-form-urlencoded");
		AjaxShowvs.onreadystatechange = function() {
			if (AjaxShowvs.readyState == 1) {

			}
			if (AjaxShowvs.readyState == 4) { // Quando estiver tudo pronto.
				if (AjaxShowvs.status == 200) {
					var resultado = AjaxShowvs.responseText;
					// Resolve o problema dos acentos
					resultado = resultado.replace(/\+/g, " ");
					resultado = unescape(resultado);
					if (horaRelogio7823gb784g == 0) {
						div1.innerHTML = resultado;
						setTimeout(function() {
							div1.style.visibility = 'visible';
							div2.style.visibility = 'hidden';
							horaRelogio7823gb784g = 1;
							getHora(tam, cor, fonte, target);
						}, 1000);
					} else {
						div2.innerHTML = resultado;
						setTimeout(function() {

							div2.style.visibility = 'visible';
							div1.style.visibility = 'hidden';
							horaRelogio7823gb784g = 0;
							getHora(tam, cor, fonte, target);
						}, 1000);
					}

				} else {
					exibeResultado.innerHTML = AjaxShowvs.responseText;
					return false;
				}
			}
		}
		AjaxShowvs.send(dadosPost);
	}
}