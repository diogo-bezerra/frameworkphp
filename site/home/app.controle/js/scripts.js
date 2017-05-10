/**
 * Scripts avulsos
*/
// Mascara um valor monetário (Usa maskMoney.js)
function maskValor() {
	$("#valor_cheque").maskMoney({
		symbol : "R$",
		decimal : ",",
		thousands : "."
	})
};

// Abre o opção de imprimir do navegador
function openPrint(){
	window.print();
}

// Mostra o menu "Sair"
function showLinkSair() {
	$("#link_menu_sair").show();
}