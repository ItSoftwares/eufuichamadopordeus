<?
require "../conexao.php";

DBdelete("usuario", "where estado_conta=0 and nome=''");

$usuarios = DBselect("usuario", "order by id ASC");

echo json_encode(array('estado'=>1, 'mensagem'=>"Registros apagados com sucesso!", 'usuarios'=>$usuarios));
exit
?>