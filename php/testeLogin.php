<?php
require 'conexao.php';
require 'classes/usuario.class.php';
require 'classes/adm.class.php';

session_start();

$usuario = new Adm($_POST);

$result = $usuario->login();

if ($result['estado']==4) {
    echo json_encode($result);
    exit;
} else if ($result['estado']==3) {
    echo json_encode($result);
    exit;
}

$usuario = new Usuario($_POST);

$result = $usuario->login();

echo json_encode($result);
exit;
?>