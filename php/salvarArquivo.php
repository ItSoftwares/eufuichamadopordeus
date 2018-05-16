<?
require "conexao.php";
require "classes/usuario.class.php";
session_start();
$usuario = unserialize($_SESSION['usuario']);

$dados = $_POST;
$result = DBselect("usuario_conteudo", "where id_usuario={$usuario->id} and versao_conteudo={$dados['versao']}");

if (count($result)>0) {
    echo json_encode(array("estado"=>2, "mensagem"=>"Você já salvou este conteúdo em seus arquivos!"));
    exit;
}

$result = DBselect("usuario_conteudo", "where versao_conteudo={$dados['versao']}");

if (count($result)==0) {
    $img = $dados['base'];
    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $fileData = base64_decode($img);

    $dirname = realpath("..".DIRECTORY_SEPARATOR."servidor".DIRECTORY_SEPARATOR."conteudo_pdf".DIRECTORY_SEPARATOR);
    $fileName = $dados['versao'].'.pdf';
    file_put_contents($dirname.DIRECTORY_SEPARATOR.$fileName, $fileData);
}

$criar = array(
    'id_usuario'=> $usuario->id,
    'versao_conteudo'=> $dados['versao'],
    'titulo'=> $dados['titulo'],
    'time'=>time()
);

DBcreate("usuario_conteudo", $criar);

echo json_encode(array("estado"=>1, "mensagem"=>"Salvo em MINHA CONTA/ARQUIVOS RECEBIDOS!"));
exit;
//echo $dirname.$fileName;
?>