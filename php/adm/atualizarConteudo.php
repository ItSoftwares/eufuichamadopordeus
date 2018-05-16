<?
date_default_timezone_set("America/Sao_Paulo");
require "../conexao.php";
session_start();

$result = "";
$dados = $_POST;

$dirname="";
$target_file="";
$imageFileType="";

$conteudo = array();
$conteudo['texto'] = $dados['texto'];
$conteudo['titulo'] = $dados['titulo'];
$conteudo['versao'] = $dados['versao'];
$dados["tem_anexo"] = 0;

$result = array('estado'=>1, 'mensagem'=> "Conteudo atualizado com sucesso!", 'conteudo'=>$conteudo);

//var_dump($_FILES);
//exit;

if (count($_FILES)>0 and $_FILES['imagem']["name"]!="") {
    $dirname = realpath("../..".DIRECTORY_SEPARATOR."servidor".DIRECTORY_SEPARATOR."conteudo".DIRECTORY_SEPARATOR);

    $target_file = basename($_FILES["imagem"]["name"]);
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    $dados['extensao'] = $imageFileType;
    $dados["tem_anexo"] = 1;
} 

if ($dados["tem_anexo"]==1) {
    $uploadOk = 1;
    $target_file = $dirname .DIRECTORY_SEPARATOR. $dados['id'] . ".jpg";

    // VERIFICAR TAMANHO DA IMAGEM
    if ($_FILES["imagem"]["size"] > 10000000) {
        $mensagem = "Tamanho máximo permitido é de 10Mb";
        $uploadOk = 0;
    }

    // FINALIZAR UPLOAD
    if ($uploadOk == 0) {
        $result = array('estado'=>2, 'mensagem'=> $mensagem);
        // SE ESTIVER TUDO CERTO FAZ O UPLOAD
    } else {
        if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $target_file)) {
            // TUDO OK
            $result = array('estado'=>1, 'mensagem'=> "Conteudo atualizado com sucesso!", 'conteudo'=>$conteudo);
        } else {
            $mensagem =  "Desculpe, ocorreu um erro ao enviar imagem!";
            $result = array('estado'=>2, 'mensagem'=> $mensagem);
        }
    }    
}

DBupdate("hoje", $conteudo, "where id={$dados['id']}");

echo json_encode($result);
exit;

?>