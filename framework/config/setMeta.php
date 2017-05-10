<?php
// <META>#
$meta1 = new DTag( 'META' );
$meta1->charset = Glb::$CONFIG['CHARSET'];
$metatag[] = $meta1;

$meta2 = new DTag ( 'META' );
$meta2->name = 'description';
$meta2->content = Glb::$CONFIG['DESCRICAO'];

$metatag[] = $meta2;

$meta3 = new DTag ( 'META' );
$meta3->name = 'keywords';
$meta3->content = Glb::$CONFIG['KEYWORDS'] ;

$metatag[] = $meta3;

$meta4 = new DTag ( 'META' );
$meta4->name = 'viewport';
$meta4->content = 'width=device-width,initial-scale=1';

$metatag[] = $meta4;