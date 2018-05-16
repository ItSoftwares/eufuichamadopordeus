<?
require "../conexao.php";

$dados = $_POST;

if ($dados['funcao']=="atualizar") {
    $link = "";
    $mensagem = "Sem vídeo em exibição!";
    if (isset($dados['link']) and strlen($dados['link'])>0) {
        $link = $dados['link'];
        $mensagem = "O vídeo está sendo exibido!";
    }
    
    DBupdate("youtube", array('link'=>$link), "where id=1");
    
    echo json_encode(array('estado'=>1, 'mensagem'=>$mensagem));
    exit;
}

?>