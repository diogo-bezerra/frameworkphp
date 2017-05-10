<?php
// Configura o tipo de imagem para jpeg
header("Content-type: image/gif");
//Recebe o tamanho da fonte
$tamanhofonte = $_GET["tam"];
//A fonte deve ser True Type e deve estar no mesmo diretório do script
$fonte = $_GET["fonte"].'.ttf';
// Recebe o texto que ser� usado para criar a imagem
$texto = $_GET["texto"];


// Criando a imagem
$tamanho = imagettfbbox($tamanhofonte, 0, $fonte, $texto);
$largura = $tamanho[2] + $tamanho[0] + 8;
$altura = abs($tamanho[1]) + abs($tamanho[7]);

$imagem = imagecreate($largura, $altura);

$corPreta = imagecolorallocate($imagem, 255, 255, 255);
imagecolortransparent($imagem, $corPreta);

// Recebe a cor da fonte
@$cor = $_GET["cor"];
$r = '0x'.substr($cor, 0, 2);
$g = '0x'.substr($cor, 2, 2);
$b = '0x'.substr($cor, 4, 2);

// Criando as cores (RGB)
$corFonte = imagecolorallocate($imagem, $r, $g, $b); // Cinza

//Adicionando o Texto na imagem
imagefttext($imagem, $tamanhofonte, 0, 0, abs($tamanho[5]), $corFonte, $fonte, $texto);

// Gera a imagem
imagegif($imagem); // Destrói os recursos alocados pela imagem
imagedestroy($imagem);
 ?>