<?php
class DFile extends DTag {
    public function __construct($name, $img, $rotulo = 'Selecionar Arquivo', $font = '1.8em') {
    	parent::__construct('div');
    	$this->class = 'dfiledivUpload'.$name;
    	$this->setIn("
    			<style type='text/css'>
    			.dfiledivUpload$name {
				border: 1px #cccccc solid;
				border-radius: 2px;
				display: table-cell;
				font-size: $font;
				height: 2em;
			    position: relative;
			    overflow: hidden;
			    text-align: center;
			    vertical-align: middle;
			    width: 10em;
				}
				.dfiledivUpload$name input.dfile {
				    position: absolute;
				    top: 0;
				    right: 0;
				    margin: 0;
				    padding: 0;
				    font-size: 20px;
				    cursor: pointer;
				    opacity: 0;
				    filter: alpha(opacity=0);
				}
    			</style>
    			<span><img alt='img' src='$img' /></span> 
    			<span id='dfiletxtbt$name'>$rotulo</span>
    			<input id='dfileuploadBtn$name' name='$name' type='file' class='dfile' />
    			<script type='text/javascript'>
    				$('#dfileuploadBtn$name').change(function () {
    					$('#dfiletxtbt$name').html(this.value.substring(0, 20)+'...');
					});
    			</script>");
    }
}

?>
