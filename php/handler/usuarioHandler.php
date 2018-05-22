<?
require "../conexao.php";
require "../classes/usuario.class.php";
require "../vendor/autoload.php";
// var_dump($_FILES); exit;
if (isset($_POST['funcao'])) {
    if (!isset($_SESSION)) session_start();
    $dados = $_POST;
    $arquivos = $_FILES;
    $funcao = $dados['funcao']; unset($dados['funcao']);
    
    if ($funcao=="cadastrar") {
        $usuario = new Usuario($dados);
        $result = $usuario->cadastrar();
        
        echo json_encode($result);
        exit;
    } 
    else if ($funcao=="login") {
        $usuario = new Usuario($dados);
        $result = $usuario->login();
        
        echo json_encode($result);
        exit;
    }
    else if ($funcao=="atualizar") {
        // var_dump($dados); exit;
        $temp = unserialize($_SESSION['usuario']);
        
        foreach($dados as $key => $value) {
            if (($value==$temp->$key and $key!="id") || ($key=='senha' and $value=='')) {
                unset($dados[$key]);
            }
        }

        $dados['id'] = $temp->id;
        if ($arquivos!=null and is_uploaded_file($arquivos['foto_perfil']['tmp_name'])) $dados['foto_perfil'] = $temp->foto_perfil;
        
        $usuario = new Usuario($dados);
        $result = $usuario->atualizar($arquivos);
        
        foreach($result['atualizado'] as $key => $value) {
            $temp->$key = $value;
        }
        
        $_SESSION['usuario'] = serialize($temp);
        
        echo json_encode($result);
        exit;
    }
    else if ($funcao=="recuperarSenha") {
        $usuario = new Usuario($dados);
        $result = $usuario->recuperarSenha();
        
        echo json_encode($result);
        exit;
    } 
    else if ($funcao=="mensagem") {
        $usuario = new Usuario($dados);
        $result = $usuario->mensagem();
        
        echo json_encode($result);
        exit;
    }
}
?>