<?
require "conexao.php";
require "classes/usuario.class.php";

$dados = $_POST;
$estado = 0;

if (isset($dados['id'])) {
    $estado = $dados['id'];
}

$dados['data_criacao'] = time();

$usuario = new Usuario($dados);
$result = DBselect("usuario", "where email='{$dados['email']}'", "email");

if (count($result)>0) {
    echo json_encode(array('estado'=>2, 'mensagem'=>"Já existe um usuário cadastrado com esse email!"));
    exit;
}

if ($estado!=0) {
    $usuario->estado_conta=1;
    $result = $usuario->atualizar();
} else {
    $result = $usuario->cadastrar();
}

echo json_encode($result);
exit;

?>