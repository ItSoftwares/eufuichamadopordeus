<?
session_start();
require "../php/sessao_usuario.php";
// verificarSessao();
require "../php/conexao.php";
require "../php/classes/usuario.class.php";
date_default_timezone_set("America/Sao_Paulo");

$dirname = dirname(__DIR__).DIRECTORY_SEPARATOR."servidor".DIRECTORY_SEPARATOR."hoje";

$conteudo = DBselect("hoje"); 
$frases = DBselect("frase_diaria"); 

$hoje = date("w")+1;
$semana = intval(date("z")/7);
$versao = 0;

foreach($conteudo as $c) {
    if ($c['versao']>$versao) $versao = $c['versao'];
}

while($semana>7) {
    $semana -= 7;
}

$dias = ["domingo", "segunda", "terça", "quarta", "quinta", "sexta", "sábado"];

if (isset($_SESSION['usuario'])) $usuario = unserialize($_SESSION['usuario']);
else $usuario = false;

$cumprimeto = "Bom dia";
if (date("G")>11) $cumprimeto = "Boa tarde";
if (date("G")>17) $cumprimeto = "Boa noite";
//echo date("G").$cumprimeto;

$aviso = DBselect("aviso")[0];

$selec = 'hoje';

?> 
<!DOCTYPE HTML>
<html>
    <head>
        <title>Eu Fui Chamado por Deus - Hoje</title>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="itsoftwares">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <link rel="shortcut icon" type="image/png" href="img/logo.png"/>
        <link rel="stylesheet" href="../css/geral.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../css/hoje.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../cssmobile/geral.css" media="(max-width: 999px)">
        <link rel="stylesheet" href="../cssmobile/hoje.css" media="(max-width: 999px)">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <style>
            #inicio, footer {
	            background-image: url("../servidor/hoje/<? echo $dias[$hoje-1]; ?>.jpg?<? echo time(); ?>") !important;
            }
            
            #conteudo {
                margin-top: 30px;
            }
        </style>
    </head>
     
    <body class="<? echo ($aviso['ativo']==1 and (!isset($_SESSION['aviso'])))?"hidden":""; ?>">
        <? include('../html/menu.html'); ?>
       
        <section id="inicio">
           <div class="fundo"></div>
           
           <div id="nome">
                <? if ($usuario) {?>
                <h3><? echo $cumprimeto." <span style='color: #ff0; text-transform: capitalize;'>".(explode(" ",$usuario->nome)[0])."!</span>"; ?></h3>
                <? } ?>
                <h3>Hoje é <span style="text-transform: Capitalize"><? echo $dias[$hoje-1]; ?></span>, dia <? echo date("d/m"); ?></h3>

                <h3><? echo $frases[$hoje-1]['frase']; ?></h3>
           </div>
        </section>
        
        <section id="conteudo">
            <h2><span><? echo $conteudo[$hoje-1]['titulo']; ?></span></h2>
            
            <img src="../servidor/conteudo/<? echo $hoje.".jpg?".time() ?>" id="imagem">
            
            <div id="texto"><? echo $conteudo[$hoje-1]['texto']; ?></div>
        </section>
        <?
		if ($aviso['ativo']==1 and (!isset($_SESSION['aviso']))) {
            $_SESSION['aviso'] = true;
		?>
        <section id="aviso-mostrar">
            <img src="../img/fechar.png" class="fechar">
            <img src="../img/logo.png" id="logo-superior">
        	<div id="aviso-container">
        	
                <h3><? echo $aviso['palavra_principal']; ?></h3>

                <p><? echo $aviso['frase_1']; ?></p>

<!--                <a href="<? echo $aviso['link']; ?>" class="botao"><? echo $aviso['frase_link']; ?></a>-->
                <a href="/paginas/agenda" class="botao"><? echo $aviso['frase_link']; ?></a>

                <p><? echo $aviso['frase_2']; ?></p>

                <div>
                    <img src="../servidor/aviso/aviso1.jpg?<? echo time() ?>">
                    <img src="../servidor/aviso/aviso2.jpg?<? echo time() ?>">
                    <img src="../servidor/aviso/aviso3.jpg?<? echo time() ?>">
                </div>
        	</div>
        </section>
        <?
		}
		?>
        <? if ($usuario) {?>
        <img src="../img/pdf.png" id="baixar-pdf">
        <? } ?>
        
        <?
        include("../html/rodape.html");
        include("../html/popup.html");
        include("../html/confirmacao.html");
        ?>
    </body> 
    <script type="text/javascript">
        var hoje = <? echo json_encode($hoje); ?>;
        var conteudo = <? echo json_encode($conteudo); ?>;
        var dias = <? echo json_encode($dias); ?>;
        var frases = <? echo json_encode($frases); ?>;
        var ultima = <? echo $versao; ?>;
        var atual = <? echo $semana; ?>;
    </script>
    <script src="../js/jquery.mask.js"></script>
    <script src="../js/menu.js"></script>
    <script src="../js/hoje.js?<? echo time() ?>"></script>
</html>