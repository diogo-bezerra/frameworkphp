// Adiciona um link a favoritos
function addFav(urlfav, titulofav){
    if (window.sidebar) window.sidebar.addPanel(titulofav, urlfav,"");
    else if(window.opera && window.print){
        var mbm = document.createElement('a');
        mbm.setAttribute('rel','sidebar');
        mbm.setAttribute('href',urlfav);
        mbm.setAttribute('title',vtitle);
        mbm.click();
    }
    else if(document.all){
        window.external.AddFavorite(urlfav);
    }
}

// Abre um prompt para copiaro conteúdo de um elemento input
function HighlightAll(id) {
    window.prompt ("Pressione Ctrl+C para copiar", document.getElementById(id).value);
}

// Executa um scroll.
function pageScroll(h,v) {
    window.scrollBy(h,v); // horizontal e vertical scroll
}

// Define a altura de um container pai de acordo com seu cntainer filho
// É utilizado para redimencionar iframes de acordo com o conteúdo
function setHeightScreen(idParent, idChild, margem){
    try {
        if(!margem){
            margem = 0;
        }
        //alert('screen1: ' + document.getElementById(idParent).style.height+ ' => tb1: '+(document.getElementById(idChild).scrollHeight) + 'px');
        document.getElementById(idParent).style.height = (document.getElementById(idChild).scrollHeight + margem) + 'px'; // Margem Superior e Inferior, somadas
        //alert('screen2: ' + document.getElementById(idParent).style.height+ ' = tb2: '+(document.getElementById(idChild).scrollHeight) + 'px');   
    }
    catch (e) {
	
    }
}

function redirect (url){
	$(window.document.location).attr('href', url);
}

function redirectBlank (url) {
	window.open(url, '_blank');
}