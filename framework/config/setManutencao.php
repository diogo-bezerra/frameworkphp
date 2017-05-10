<?php
# Esse arquivo é inserido na classe Glb em Global.class.php
# Chama a view indicada em $VIEWMANUTENCAO
$BLOCK = "";// "ok" para manutenção; "ip" para acessar somente pelo ip informado abaixo; "" para normal;
$BLOCKIP = '::1';

# Senha de acesso via input (input_acesso em manutencao.html) quando em manutencao
$SENHABLOCK = 'fr2102';

# Get para link de acesso direto quando em manutenção
# Link de acesso: http://dominio.com.br/?$LINKBLOCKGET=$LINKBLOCKGETVALUE
$LINKBLOCKGET = 'H57DaF5hfyDjr3';
$LINKBLOCKGETVALUE = base64_encode('fr2102');
$VIEWMANUTENCAO = 'VsManutencao';
// echo $LINKBLOCKGETVALUE;