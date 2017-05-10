<?php

require_once $CONFIG['RAIZGLB'] . '/framework/plugins/captcha/securimage/securimage.php';

// Classe de montagem de captcha. Utiliza app.controle/util/securimage

final class Captcha extends Tabela {

    function __construct($largura = 215, $altura = 80) {
        parent::__construct('captcha', 2, 2);
        $img = new Imagem('siimage', '', Glb::$CONFIG['URL'] . '/framework/plugins/captcha/securimage/securimage_show.php?largura=' . $largura . '&altura=' . $altura . '&sid=' . md5(uniqid()));
        $reload = new Link('', '#', '', '<img src="' . Glb::$CONFIG['URL'] . '/framework/plugins/captcha/securimage/images/refresh.png" alt="Mostrar outra imagem" onclick="this.blur()" align="bottom" border="0">');
        $reload->setPropriedades(array(
            'style' => 'border-style: none;',
            'title' => 'Mostrar outra imagem',
            'onclick' => "document.getElementById('siimage').src = '" . Glb::$CONFIG['URL'] . "/framework/plugins/captcha/securimage/securimage_show.php?largura=" . $largura . "&altura=" . $altura . "&sid='.concat(Math.random()); this.blur(); return false",
            'tabindex' => '-1'
        ));
        $input = new Input('ct_captcha', 'text', '', $largura / 2);
        $input->setPropriedades(array(
            'class' => 'inputCustom',
            'maxlength' => '4'
        ));
        $this->td[1][1]->setPropriedades(array(
            'style' => 'border: 2px solid #AAAAAA'
        ));
        $this->td[1][2]->setPropriedades(array(
            'style' => 'border: 2px solid #AAAAAA'
        ));
        $this->td[2][1]->setPropriedades(array(
            'align' => 'center',
            'class' => 'fonte01_3_cinza',
            'colspan' => '2'
        ));
        $this->td[1][1]->add($img);
        $this->td[1][2]->add($reload);
        $this->td[2][1]->add('Digite aqui o código acima<br />');
        $this->td[2][1]->add($input);
    }

    static function check($captcha) {
        $securimage = new Securimage();
        if (!empty($captcha)) {
            if ($securimage->check($captcha)) {
                // Código digitado corretamente
                return TRUE;
            } else {
                // Código digitado não bate com a imagem
                echo '<br />';
                echo (utf8_encode('<script language="javascript" type="text/javascript">alert("Código verificador incorreto.\n\nPor favor tente novamente.");showVs("div_reqlogin","VsRecuperaSenha","","Carregando...",320);</script>'));
                return FALSE;
            }
        } else {
            echo '<br />';
            echo (utf8_encode('<script language="javascript" type="text/javascript">alert("Código verificador incorreto.\n\nPor favor tente novamente.");showVs("div_reqlogin","VsRecuperaSenha","","Carregando...",320);</script>'));
            return FALSE;
        }
    }
}

?>
