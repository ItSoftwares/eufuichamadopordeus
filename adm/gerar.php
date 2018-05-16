<?
session_start();
require "../php/sessao_usuario.php";
verificarSessao(1);
require "../php/conexao.php";

$tem = false;
if (isset($_GET['id'])) {
    $result = DBselect("usuario", "where id>={$_GET['id']} limit 65", "email, senha");
    if (count($result)>0) $tem = true;
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>ADS - Usuários</title>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="itsoftwares">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <link rel="shortcut icon" type="image/png" href="img/logo.png"/>
        <link rel="stylesheet" href="../css/geral.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../css/gerar.css">
<!--
        <link rel="stylesheet" href="cssmobile/geral.css" media="(max-width: 999px)">
        <link rel="stylesheet" href="cssmobile/cadastro_especifico.css" media="(max-width: 999px)">
-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </head>
    
    <body>
        <section id="gerar">
            <button class='botao'>Gerar 65 novos usuários</button>
            <button class='botao' id="imprimir" style="display: none;">Imprimir</button>
        </section>
        
<!--        <section id="folha">-->
            <?
//            for($i=0;$i<65;$i++) {
            ?>
<!--
            <span class="etiqueta">
                <p>Login: asdf</p>
                <p>Senha: asd1234</p>
            </span>
-->
            <?
//            }
            ?>
<!--        </section>-->
        <?
        include "../html/popup.html";
        ?>
    </body> 
    <script src="../js/html2canvas.js"></script>
    <script src="../js/gerar.js"></script>
    <script type="text/javascript">
        var logins = <? echo $tem?json_encode($result):json_encode(array()) ?>;
    </script>
</html>