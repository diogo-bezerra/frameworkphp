function submitFormLogin(nomeForm){
    var i = 0;
    var obrg = "";
    var submitVar = true;
    for(i=0;i< document.forms[nomeForm].elements.length; i++){
        //alert(document.form_cad.elements[i].id);
        // Verifica se o campo � obrigat�rio atrav�s da classe obrg
        obrg = document.forms[nomeForm].elements[i].className.indexOf('obrg');
        // Se for obrigat�rio
        if(obrg!=-1) {
            // Verifica se est� em branco tirando tamb�m espa�os do in�cio e fim do valor (trim)
            if(trim(document.forms[nomeForm].elements[i].value) == ""){	
                submitVar = false;
                try{
                    document.forms[nomeForm].elements[i].focus();
                    document.forms[nomeForm].elements[i].style.borderColor = "#FF3C3C";
                }catch(e){}
                alert('Informe os dados para login.');
                break;
            }
        }
        
    }
    if(submitVar){
        pageScroll(0,-2000);
        return true;
    }
}