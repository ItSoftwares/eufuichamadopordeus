<?php
error_reporting(E_ALL);

include_once('config.php');
include_once('database.php');

function DBescape($dados) {
    $link = DBconnect();

    if (!is_array($dados)) {
        $dados = mysqli_real_escape_string($link, $dados);
    } else {
        $arr = $dados; 
        $dados = array();
        foreach ($arr as $key => $value) {
            
            if (!is_array($value)) {
                $key = mysqli_real_escape_string($link, $key);
                $value = mysqli_real_escape_string($link, $value);
                $dados[$key] = $value;
            }
        }
    }

    DBclose($link);

    return $dados;
}

function DBconnect(){
    $link = mysqli_connect(DB_HOSTNAME, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_connect_error());
    mysqli_set_charset($link,DB_CHARSET) or die(mysqli_error($link));
    // echo "conectou";

    return $link;
}

function DBclose($link){
    mysqli_close($link) or die(mysqli_error($link));
}


// Tirar métodos daqui e colocar na classe correta
// function verificarLogin(){
// 	$email = $_POST['emailLogin'];
// 	$senha = $_POST['senhaLogin'];

// 	$link = DBconnect();
// }

// // if (!isset($_POST['logar'])) {
// // } 

// // if (!isset($_POST['cadastrar'])) {
// // 	var_dump(DBinsere());
// // }

// var_dump(DBselect("usuario","where id = 5",'*'));
?>