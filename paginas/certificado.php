<?
session_start();
require "../php/sessao_usuario.php";
verificarSessao();

require "../php/classes/usuario.class.php";
require "../php/conexao.php";

date_default_timezone_set('America/Sao_Paulo');
header('Content-type: text/html; charset=utf-8');
setlocale(LC_ALL, 'pt_BR.utf-8');

$usuario = unserialize($_SESSION['usuario']);

$tem = true;
$certificado=array();

if (isset($_GET['id'])) {
    $certificado = DBselect("encontro_inscritos", "where id={$_GET['id']} and id_usuario={$usuario->id}");
    
    if (count($certificado)==0) {
        $tem=false;
    }
} else {
    $tem=false;
}

if ($tem==false) {
    $_SESSION['erro_msg'] = "Certificado inválido!";
    header("Location: conta");
}

$certificado = $certificado[0];
$encontro = DBselect("encontros", "where id={$certificado['id_encontro']}")[0];
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Eu Fui Chamado por Deus - Certificado</title>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="itsoftwares">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <link rel="shortcut icon" type="image/png" href="img/logo.png"/>
        <link rel="stylesheet" href="../css/geral.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../css/certificado.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </head>
    
    <body>
        <section id="gerar">
<!--            <button class='botao' id="imprimir">Imprimir</button>-->
            <img src="../img/imprimir.png" id="imprimir" title="Imprimir" class="no">
            <img src="../img/pdf.png" id="pdf" title="Baixar PDF" class="no"> 
        </section>
        
        <section id="certificado">
            <img src="../img/certificado-em-branco.jpg" alt="">
            <div id="nome"><? echo $usuario->nome; ?></div>
            <div id="encontro"><? echo $encontro['nome']; ?></div>
            <div id="data"><? echo strftime("%d de <span>%B</span> de %Y", $encontro['data']); ?></div>
            <div id="local"><? echo $encontro['local']; ?></div>
            <div id="carga"><? echo $encontro['carga_horaria']; ?> Horas</div>
            <div id="final"><? echo $encontro['cidade'].", ".strftime("%d de <span>%B</span> de %Y", $encontro['data']); ?></div>
        </section>
        <?
        include "../html/popup.html";
        ?>
    </body> 
    
    <script src="../js/jspdf.js"></script>
<!--    <script src="http://mrrio.github.io/jsPDF/dist/jspdf.debug.js"></script>-->
    <script src="../js/canvas2image.js"></script>
    <script src="../js/html2canvas.js"></script>
    <script src="../js/certificado.js"></script>
    <script type="text/javascript">
        var encontro = <? echo json_encode($encontro); ?>;
        function savePDF(){
            try {
                CANVAS.getContext('2d');
                var imgData = CANVAS.toDataURL("image/jpeg", 1.0);
                var pdf = new jsPDF('l', 'mm', [297, 210]);
                pdf.addImage(imgData, 'JPEG', 0, 0);
                pdf.save(encontro.nome + ".pdf");
            } catch(e) {
                alert("Error description: " + e.message);
            }
        }
    </script>
</html>