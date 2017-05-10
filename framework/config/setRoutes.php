<?php
/* Define as rotas (url) para chamada de controles
 * A função showView é herdada de AppCtrl para todos os controles e chama a view correspondente.
 * Por exemplo: www.site.com.br/home chama VsHome se a url /home estiver apontando para CtrlHome[showView()]
 
 * Para chamar uma função passando parâmetros a função showView deve ser recriada na classe filha (Override) ou criada uma nova função. 
 * Ex1: www.site.com.br/home/10 chama funcaoEx($param) se a url /home estiver apontando para CtrlHome[funcaoEx(param)] passando 10 como parâmetro.
 * Ex2: www.site.com.br/home/10/xx chama funcaoEx($param1, $param2) se a url /home estiver apontando para CtrlHome[funcaoEx(param1, param2)] 
 * passando 10 como parâmetro para $param1 e 'xx' para $param2.
 * */

$route = array(
		// ###### Home #####
		''=>'CtrlExemploHome[showView()]',
		'home'=>'CtrlHome[showView()]',
		'fale-conosco'=>'CtrlFaleConosco[showView()]'
);