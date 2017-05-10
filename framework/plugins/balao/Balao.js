var posMouseDown = 0; 
var linkblUp = '';
var posMouseUp = 0; 
function showToolTipDown(e,idTooltip,idLink,w,h){
    if(document.all)e = event;	
    var balao = document.getElementById(idTooltip);
    var link = document.getElementById(idLink);
    balao.style.display = 'block';
    var dif = 0;
    if(e.clientX != posMouseDown){ // 203 != 200
        dif = e.clientX - posMouseDown; //203-200 = 3
        if(dif == e.clientX){
            dif = 0;
            posMouseDown = e.clientX; // posmouse = 200
        }
        
    }
    balao.style.left = link.offsetLeft + dif + 65 +'px'; //3
    balao.style.top = link.offsetTop + 18 + h +'px';
}

function showToolTip(e,idTooltip,idLink,w,h){
    if(document.all)e = event;	
    var balao = document.getElementById(idTooltip);
    var link = document.getElementById(idLink);
    
    balao.style.display = 'block';
    var alturaBl = balao.scrollHeight;
    var dif = 0;
    if(e.clientX != posMouseUp){ 
        dif = e.clientX - posMouseUp;
        if(dif == e.clientX){
            dif = 0;
            posMouseUp = e.clientX; 
        }
    }
    var ieleft = 0;
    if(navigator.appName == 'Microsoft Internet Explorer'){
        ieleft = 0;
    }   
    balao.style.left = link.offsetLeft + dif + w +'px'; //3
    balao.style.top = link.offsetTop - alturaBl + h +'px';
}

function hideToolTip(idTooltip){
    document.getElementById(idTooltip).style.display = 'none';
    posMouseUp = 0;
    posMouseDown = 0;
}

function getPosicaoElemento(elemID){
    var offsetTrail = document.getElementById(elemID);
    var offsetLeft = 0;
    var offsetTop = 0;
    while (offsetTrail) {
        offsetLeft += offsetTrail.offsetLeft;
        offsetTop += offsetTrail.offsetTop;
        offsetTrail = offsetTrail.offsetParent;
    }
    if (navigator.userAgent.indexOf("Mac") != -1 && 
        typeof document.body.leftMargin != "undefined") {
        offsetLeft += document.body.leftMargin;
        offsetTop += document.body.topMargin;
    }
    //alert('left: '+offsetLeft+ ' top: '+offsetTop);
    return {
        left:offsetLeft, 
        top:offsetTop
    };
}

function getPosicaoElemento2(elemID){
    var offsetTrail = document.getElementById(elemID);
    var offsetLeft = 0;
    var offsetTop = 0;
    while (offsetTrail) {
        offsetLeft += offsetTrail.offsetLeft;
        offsetTop += offsetTrail.offsetTop;
        offsetTrail = offsetTrail.offsetParent;
    }
    if (navigator.userAgent.indexOf("Mac") != -1 && 
        typeof document.body.leftMargin != "undefined") {
        offsetLeft += document.body.leftMargin;
        offsetTop += document.body.topMargin;
    }
    alert('left: '+offsetLeft+ ' top: '+offsetTop);
    //return {left:offsetLeft, top:offsetTop};
}

function nX (evento, objeto){
    return evento.clientX-(objeto.offsetLeft-pageXOffset); 
}

