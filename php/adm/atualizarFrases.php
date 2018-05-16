 <?
date_default_timezone_set("America/Sao_Paulo");
require "../conexao.php";

$dados = $_POST;

foreach($dados as $key => $frase) {
    DBupdate("frase_diaria", array('frase'=>$frase), "where id={$key}");
}

echo json_encode(array('estado'=>1, 'mensagem'=> "Frases atualizado com sucesso!"));
exit;