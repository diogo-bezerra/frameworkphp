<?php
# CSSs que serão adicionados ao Head para uso em todo o site
# As tags são criadas pela função setLinksCss da clase lib/html/Head.class.php
$css = array(
	//"global/app.visual/css/bootstrap-select.min.css",
	"global/app.visual/css/bootstrap.css",
	"global/app.visual/css/reset.css",
	//"global/app.visual/css/global.css",
	//"global/app.visual/css/fonts.css",
	"global/app.visual/css/style.css",
	//"global/app.visual/css/form.css",
 	
	"framework/plugins/jqueryui1.11/themes/smoothness/jquery-ui.css",
	"framework/plugins/colorbox/example1/colorbox.css",
	
);
foreach ($css as $c){
	$css_file = new Css($c);
	$this->head->setLinksCss($c);
}