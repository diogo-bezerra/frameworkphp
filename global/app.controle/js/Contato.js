// Exemplo de função para validação de um form
function verificaFormContato(nomeForm){
    var i = 0;
    var obrg = "";
    var submitVar = true;
    for(i=0;i< document.forms[nomeForm].elements.length; i++){
        // Verifica se o campo é obrigatório através da classe obrg
        obrg = document.forms[nomeForm].elements[i].className.indexOf('obrg');
        // Se for obrigatório
        if(obrg!=-1) {
            // Verifica se está em branco tirando também espaços do início e fim do valor (trim)
            if(trim(document.forms[nomeForm].elements[i].value) == ""){		
                submitVar = false;
                try{
                    document.forms[nomeForm].elements[i].focus();
                    document.forms[nomeForm].elements[i].style.borderColor = "#FF3C3C";
                }catch(e){}
                alert('Verifique os campos de preenchimento obrigatorio.');
                break;
            }
        } 
    }
    if(submitVar){
        if(confirm('Verifique se digitou seu email corretamente, pois o mesmo sera utilizado para resposta.\n\n'+document.getElementById('input_email').value+'\n\n Seu email esta correto?\n\n')){
        	//submitForm('screen','CtrlContato','enviar','form_contato','Enviando...',600);
        	return true;
        }
    }
    return false;
}