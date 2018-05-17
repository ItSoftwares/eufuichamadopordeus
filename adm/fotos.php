<?
session_start();
require "../php/sessao_usuario.php";
require "../php/listarArquivos.php";
verificarSessao(1);
require "../php/conexao.php";

$dirname = dirname(__DIR__).DIRECTORY_SEPARATOR."servidor".DIRECTORY_SEPARATOR."fotos";
//echo $dirname;
$imagens = listar($dirname);

$albuns = DBselect("album", "order by time DESC");

$selec = "fotos";
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>ADM - Fotos</title>
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
        <? include("../html/menu.html"); ?>
       
       <section id="inicio">
           <div class="fundo"></div>
           
           <div id="nome">
               <h1>Fotos</h1>
               <a href="encontros" class="botao" id="encontro-botao">Encontros</a>
               <a href="#" class="botao" id="aviso-botao">Aviso</a>
               <a href="agenda" class="botao" id="agenda-botao">Agenda</a>
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
                        <img src="../img/lixeira-branca.png" class="excluir" data-id="<? echo $album['id']; ?>">
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
        
        <section id="novo">
<!--            <img src="../img/fechar.png" class="fechar">-->
            <div>
                <h3>Novo álbum</h3>
                
                <form>
                    <div class="input">
                        <label>Nome</label>
                        <input type="text" placeholder="Nome" name="nome" required>
                    </div>
                    <div class="input">
                        <label>Descrição</label>
                        <input type="text" placeholder="Descrição do álbum" name="descricao" required>
                    </div> 
                    <div class="input">
                        <label>Imagens</label>
                        <div class="upload">
                            <div class="nome">Nenhum arquivo</div>
                            <label for="imagens"><img src="../img/upload.png">Procurar</label>
                            <input type="file" id="imagens" name="imagens[]" multiple accept="image/*">
                        </div>
                    </div>

                    <button class="botao">Criar</button>
                    <div class="clear"></div>
                </form>
            </div>
        </section>
        
        <div id="abrir">
            <img src="../img/add.png" alt="Novo" title="Clique para criar um novo álbum">
        </div>
        
        <? 
        include("../html/popup.html");
        include("../html/rodape.html");
        include("../html/confirmacao.html"); 
		include("aviso.php");
        ?>
    </body> 
    <script type="text/javascript">
        var adm=true;
        var fotos = <? echo json_encode($imagens); ?>;
        var guia = <? echo json_encode($guia); ?>;
    </script>
    <script src="../js/menu.js"></script>
    <script src="../js/fotos.js?<? echo time(); ?>"></script>
</html>