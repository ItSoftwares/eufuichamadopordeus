<?
session_start();
require "../php/sessao_usuario.php";
verificarSessao(1, 1);
require "../php/conexao.php";

$pin = rand(1, 50);

if (isset($_COOKIE['pin'])) { 
    header("Location: participantes");
}

?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>ADM - PIN</title>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="itsoftwares">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <link rel="shortcut icon" type="image/png" href="img/logo.png"/>
        <link rel="stylesheet" href="../css/geral.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../css/pin.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../css/mobile/geral.css" media="(max-width: 999px)">
        <link rel="stylesheet" href="../css/mobile/pin.css" media="(max-width: 999px)">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </head>

    <body>
        <div id="pin" style="<? echo isset($_COOKIE['pin'])?'display: none':''; ?>">
            <h1>Insira o PIN para o número <? echo $pin; ?></h1>

            <div id="codigo">
                <div class="input linha" id="pin_1"><input type="number" value="" step="1" maxlength="1" max="9" min="0" size="1" autofocus="" placeholder="*"></div>
                <div class="input linha" id="pin_2"><input type="number" value="" step="1" maxlength="1" max="9" min="0" size="1" placeholder="*"></div>
                <div class="input linha" id="pin_3"><input type="number" value="" step="1" maxlength="1" max="9" min="0" size="1" placeholder="*"></div>
                <div class="input linha" id="pin_4"><input type="number" value="" step="1" maxlength="1" max="9" min="0" size="1" placeholder="*"></div>
            </div>
    </div>

    <? 
    // include("../html/rodape.html");
    include("../html/popup.html");
    // include("../html/confirmacao.html");
    ?>
    </body>

    <script>
        const pin = <? echo $pin; ?>;
        var pins = [
        2113,5486,1695,5208,4782,3219,6333,1288,9312,5439,
        6494,8054,1372,1733,5556,8012,2622,8566,0314,4452,
        5847,8332,7823,4166,1085,6486,5796,8738,5307,2444,
        4344,1935,1234,1446,2633,1089,6426,2955,4656,1214,
        1516,2495,4645,9405,7814,6348,5541,4488,0461,5462
        ]

        $(document).ready(function() {

        });

        $("#pin input").keyup(function() {
            if ($(this).parent().attr("id")=="pin_4") {
                codigo = "";

                $("#pin input").each(function(i, value) {
                    codigo += $(this).val();
                });

                if (codigo==pins[pin-1]) {
                    $("#pin").fadeOut();
                    data = new Date();
                    data.setTime(data.getTime() + 60*60*1000);
                    document.cookie = "pin=valido; expires="+data.toUTCString()+"; path=/";
                    location.href="participantes";
                    console.log("OK");
                } else {
                    resetarFormPin("PIN incorreto!");
                }
            } else {
                // console.log($(this).next())
                if ($(this).val().length==1) {
                    $(this).parent().next().find("input").focus();
                } else if ($(this).val().length>1) {
                    resetarFormPin("Digite um PIN válido!");
                }
            }
        });

        function resetarFormPin(mensagem) {
            $("#pin input").val("");
            chamarPopupInfo(mensagem);
            $("#pin_1 input").focus();
        }
    </script>
</html> 