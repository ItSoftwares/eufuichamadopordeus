<?
session_start();
require "../php/sessao_usuario.php";
require "../php/listarArquivos.php";
// verificarSessao();
require "../php/conexao.php";

$dirname = dirname(__DIR__).DIRECTORY_SEPARATOR."servidor".DIRECTORY_SEPARATOR."fotos";
//echo $dirname;
$imagens = listar($dirname);

$albuns = DBselect("album", "order by time DESC");

$selec = 'fotos';
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Eu Fui Chamado por Deus - Fotos</title>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="itsoftwares">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <link rel="shortcut icon" type="image/png" href="img/logo.png"/>
        <link rel="stylesheet" href="../css/geral.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../css/fotos.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../cssmobile/geral.css" media="(max-width: 999px)">
        <link rel="stylesheet" href="../cssmobile/fotos.css" media="(max-width: 999px)">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </head>
    
    <body>
        <? include('../html/menu.html'); ?>
       
       <section id="inicio">
           <div class="fundo"></div>
           
           <div id="nome">
               <h1>Fotos</h1>
           </div>
       </section>
        
        <section id="fotos">
           <?
            $guia=[];
            if (count($albuns)>0) {
                foreach($albuns as $album) {
                    $guia[$album['id']] = array();
                    ?>
                    <div class="album">
                    <h2><span><? echo $album['titulo']; ?></span></h2>
                    <p><? echo $album['descricao']; ?></p>
                    <div>
                    <?
                    foreach($imagens['caminhos'][$album['id']] as $key => $img) {
                        $img = str_replace(dirname(__DIR__).DIRECTORY_SEPARATOR, "", $img);
                        $guia[$album['id']][$key] = "../".$img;
                        ?>
                        <div class="foto">
                            <img src="../img/loading.gif" data-realsrc="../<? echo $img; ?>">
                            <div class="full" data-id='<? echo $key; ?>' data-album='<? echo $album['id']; ?>'>
                                <img src="../img/full-screen.png" alt="">
                            </div>
                        </div>
                        <?
                    }
                    ?>
                    </div>
                    </div>
                    <?
                }
            }
            ?>
        </section>
        
        <section id="full">
            <img src="../img/fechar.png" class="fechar">
            
<!--            <div id="ver">-->
                <img src="../img/loading.gif" id="ver">
<!--            </div>-->
            
            <img src="../img/seta-dupla.png" id="esquerda">
            <img src="../img/seta-dupla.png" id="direita">
        </section>
        
        <? 
        include("../html/rodape.html");
        ?>
    </body> 
    <script type="text/javascript">
        var fotos = <? echo json_encode($imagens); ?>;
        var guia = <? echo json_encode($guia); ?>;
    </script>
    <script src="../js/menu.js"></script>
    <script src="../js/fotos.js?<? echo time(); ?>"></script>
</html>