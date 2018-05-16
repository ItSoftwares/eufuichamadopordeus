<?
session_start(); 
require "../php/sessao_usuario.php";
verificarSessao();

require "../php/classes/usuario.class.php";
require "../php/conexao.php";

$usuario = unserialize($_SESSION['usuario']);

$tem = true;
$certificado=array();

if (isset($_GET['id'])) {
    $conteudo = DBselect("hoje", "where id={$_GET['id']}")[0];
    
    if (count($certificado)==0) {
        $tem=false;
    }
} else {
    $tem=false;
}

?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Eu Fui Chamado por Deus - Conteúdo</title>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="itsoftwares">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <link rel="shortcut icon" type="image/png" href="img/logo.png"/>
        <link rel="stylesheet" href="../css/geral.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../css/conteudo.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </head>
    
    <body>
        <section id="gerar">
<!--            <button class='botao' id="imprimir">Imprimir</button>-->
<!--            <a href=""><img src="../img/salvar.png" id="salvar" title="Salvar" class="no"></a>-->
            <img src="../img/salvar.png" id="salvar" title="Salvar" class="no">
            <img src="../img/pdf.png" id="pdf" title="Baixar PDF" class="no"> 
        </section>
        
        <section id="conteudo">
            <h2><span><? echo $conteudo['titulo'] ?></span></h2>
            <img src="../servidor/conteudo/<? echo $conteudo['id']; ?>.jpg?<? echo time() ?>" alt="">
            <div id="texto"><? echo $conteudo['texto'] ?></div>
        </section>
        
        <section id="paginas">
            <div class="conteudo" data-pagina='1'>
                <h2><span><? echo $conteudo['titulo'] ?></span></h2>
                <img src="../servidor/conteudo/<? echo $conteudo['id']; ?>.jpg?<? echo time() ?>" alt="">
                <div class="texto"></div>
            </div>
        </section>
        
        <section id="loading" style="display: flex">
            <img src="../img/loading.gif">
        </section> 
        <?
        include "../html/popup.html";
        ?>
    </body> 
    
    <script src="../js/jspdf.js"></script>
<!--    <script src="http://mrrio.github.io/jsPDF/dist/jspdf.debug.js"></script>-->
    <script src="../js/canvas2image.js"></script>
    <script src="../js/html2canvas.js"></script>
    <script src="../js/conteudo.js?<? echo time() ?>"></script>
    <script type="text/javascript">
        var conteudo = <? echo json_encode($conteudo); ?>;
        var atual = <? echo $_GET['id']; ?>;
        var pdf = new jsPDF('p', 'mm', [297, 210]);
        
        function savePDF() {
            pdf.save(conteudo.titulo + ".pdf");
        }
    </script>
</html>