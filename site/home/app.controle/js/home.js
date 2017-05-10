$.getScript("framework/plugins/fancybox/lib/jquery.mousewheel.pack.js", function(){
	//alert("1");
	});
$.getScript("framework/plugins/fancybox/source/jquery.fancybox.pack.js", function(){
	//alert("2");
	});
$.getScript("framework/plugins/fancybox/source/jquery.fancybox.js", function(){
	//alert("3");
	});

function startPopup(w,h) {
	$("#link_popup").fancybox({
		'width'				: w,
		'height'			: h,
        'autoScale'     	: false,
        'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'				: 'iframe'
	});
	setTimeout(function(){ $("#link_popup").click(); }, 2000);
}
/**
 * Inicia o slide da home
 * @param idsNoticia
 * @param imgNoticias
 * @param tituloNoticias
 * @param dataNoticias
 */
function startSlide(idsNoticia, imgNoticias, tituloNoticias, dataNoticias, linkNot) {
	var time = 5000;
	var ids = idsNoticia.split('|');
	var imgs = imgNoticias.split('|');
	var titulos = tituloNoticias.split('|');
	var datas = dataNoticias.split('|');
	var links = linkNot.split('|');
	
	var noticia1 = [ids[0], imgs[0], titulos[0], datas[0], links[0]];
	var noticia2 = [ids[1], imgs[1], titulos[1], datas[1], links[1]];
	var noticia3 = [ids[2], imgs[2], titulos[2], datas[2], links[2]];
	var noticia4 = [ids[3], imgs[3], titulos[3], datas[3], links[3]];
	
	var id = noticia1[0];
	var src = noticia1[1];
	var titulo = noticia1[2];
	var tagcolor = 1;
	setInterval(function() {
		switch (id) {
		    case noticia1[0]:
		    	src = noticia2[1];
		    	titulo = noticia2[2];
		    	id = noticia2[0];
		    	tagcolor = 2;
		    	link = noticia2[4];
		    break;
		    case noticia2[0]:
		    	src = noticia3[1];
		    	titulo = noticia3[2];
		    	id = noticia3[0];
		    	tagcolor = 3;
		    	link = noticia3[4];
		    break;
		    case noticia3[0]:
		    	src = noticia4[1];
		    	titulo = noticia4[2];
		    	id = noticia4[0];
		    	tagcolor = 4;
		    	link = noticia4[4];
		    break;
		    case noticia4[0]:
		    	src = noticia1[1];
		    	titulo = noticia1[2];
		    	id = noticia1[0];
		    	tagcolor = 1;
		    	link = noticia1[4];
		    break;
		} 
		//$('#figure_noticia').fadeOut('slow',function(){$('#img_noticia').attr("src", 'http://www.amepe.com.br/imagensnoticias/'+src)});
		//$('#figure_noticia').fadeIn('slow',function(){$('#img_noticia').attr("src", 'http://www.amepe.com.br/imagensnoticias/'+src)});
		$('#figure_noticia').fadeOut('slow',function(){$('#img_noticia').attr("src", 'http://192.168.1.95/amepe/docs/noticia/imagens/'+src)});
		$('#figure_noticia').fadeIn('slow',function(){$('#img_noticia').attr("src", 'http://192.168.1.95/amepe/docs/noticia/imagens/'+src)});
		
		$('#link_click_noticia').attr("href", link);
		setTimeout(function() {$('#span_titulo_img').text(titulo)}, 500);
		
		$('#div_noticia1').css("background-color", "#FFFFFF");
		$('#div_noticia2').css("background-color", "#FFFFFF");
		$('#div_noticia3').css("background-color", "#FFFFFF");
		$('#div_noticia4').css("background-color", "#FFFFFF");
		
		$('#div_noticia'+tagcolor).css("background-color", "#DDDDDD");
		
		
	},time);
};