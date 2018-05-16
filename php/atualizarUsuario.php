<?
require "conexao.php";
require "classes/usuario.class.php";
session_start();

$dados = $_POST;

$temp = unserialize($_SESSION['usuario']);

foreach($temp->toArray() as $key => $value) {
    if (isset($dados[$key]) && $dados[$key]==$value) {
        unset($dados[$key]);
    }
}

$dados['id'] = $temp->id;

if (count($dados)>0) {
    $usuario = new Usuario($dados);
    
    $usuario->atualizar();
    
    foreach($dados as $key => $value) {
        $temp->$key = $value;
    }
    
    $_SESSION['usuario'] = serialize($temp);
}

echo json_encode(array('estado'=>1, 'mensagem'=> "Dados atualizados com sucesso!", 'usuario'=>$dados));
exit;
?>