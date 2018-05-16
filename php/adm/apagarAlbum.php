<?
require "../conexao.php";

$dados = $_POST;

DBdelete("album", "where id={$dados['id']}");

$dirname = realpath("../..".DIRECTORY_SEPARATOR."servidor".DIRECTORY_SEPARATOR."fotos".DIRECTORY_SEPARATOR.$dados['id'].DIRECTORY_SEPARATOR);
//echo $dirname;
//exit;
rmdir_recursive($dirname);

echo json_encode(array('estado'=>1, 'mensagem'=>"Exclusão realizada com sucesso!"));
exit;

function rmdir_recursive($dir) {
    foreach(scandir($dir) as $file) {
        if ('.' === $file || '..' === $file) continue;
        if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
        else unlink("$dir/$file");
    }
    rmdir($dir);
}
?>