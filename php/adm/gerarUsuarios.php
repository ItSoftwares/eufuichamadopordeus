<?
require "../conexao.php";

$qtd = 65;

$login = array();

for($i=0;$i<$qtd;$i++) {
    $login[$i] = array();
    $login[$i]['email'] = gerarLogin(6);
    $login[$i]['senha'] = gerarSenha(6);
    $login[$i]['estado_conta'] = 0;
}

$ids = DBcreateVarios("usuario", $login);

echo json_encode(array(
'estado'=>1,
'mensagem'=>"Logins gerados com sucesso!",
'ids'=>$ids,
'logins'=>$login
));
exit;

function gerarLogin($length=8) {
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $password = substr( str_shuffle( $chars ), 0, $length );
    return $password;
}

function gerarSenha($length=8) {
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $password = substr( str_shuffle( $chars ), 0, $length );
    return $password;
}

?>