<?
require "../conexao.php";
require "../classes/postagem.class.php";
require "../vendor/autoload.php";

if (isset($_POST['funcao'])) {
    if (!isset($_SESSION)) session_start();
    $dados = $_POST;
    $funcao = $dados['funcao']; unset($dados['funcao']);
    
    if ($funcao=="nova") {
        $post = new Postagem($dados);
        $result = $post->nova();
        
        echo json_encode($result);
        exit;
    } 
    else if ($funcao=="responder") {
        $post = new Postagem($dados);
        $result = $post->responder();
        
        echo json_encode($result);
        exit;
    } 
    else if ($funcao=="atualizar") {
        $post = new Postagem($dados);
        $result = $post->atualizar();
        
        echo json_encode($result);
        exit;
    } 
    else if ($funcao=="excluir") {
        $post = new Postagem($dados);
        $result = $post->excluir();
        
        echo json_encode($result);
        exit;
    }
    else if ($funcao=="atualizarResposta") {
        $post = new Postagem($dados);
        $result = $post->atualizarResposta();
        
        echo json_encode($result);
        exit;
    }
}
?>