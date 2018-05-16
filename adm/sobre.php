<?
session_start();
require "../php/sessao_usuario.php";
verificarSessao(1);
require "../php/conexao.php";

$slide = DBselect("slide"); 
?>
<!DOCTYPE HTML> 
<html>
    <head>
        <title>ADM - Sobre</title>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="itsoftwares">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <link rel="shortcut icon" type="image/png" href="img/logo.png"/>
        <link rel="stylesheet" href="../css/geral.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../css/sobre.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../cssmobile/geral.css" media="(max-width: 999px)">
        <link rel="stylesheet" href="../cssmobile/sobre.css" media="(max-width: 999px)">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </head>
    
    <body>
       <header class="">
           <div>
<!--               <a href="/"><h3>Eu Fui Chamado por Deus</h3></a>-->
               <a href="/"><img src="../img/logo.png" alt=""></a>

               <img src="../img/menu.png" id="menu-botao">

               <nav>
                   <ul>
                      <li><a href="hoje">Hoje</a></li>
                       <li><a href="participantes">Participantes</a></li>
                       <li><a href="gerar" target="_blank">Gerar</a></li>
                       <li><a href="sobre" class="selecionado">Sobre ADM</a></li>
                       <li><a href="fotos">Fotos ADM</a></li>
                       <li><a href="../php/sair.php">Sair</a></li>
                   </ul>
               </nav>
           </div>
       </header>
       
       <section id="inicio">
           <div class="fundo"></div>
           
           <div id="nome">
               <h1>Sobre</h1>
               <a href="encontros" class="botao" id="encontro-botao">Encontros</a>
               <a href="#" class="botao" id="aviso-botao">Aviso</a>
               <a href="agenda" class="botao" id="agenda-botao">Agenda</a>
           </div>
       </section>
        
        <section id="sobre">
                <h2><span>Slide 1</span></h2>
                <div class="slide adm">
                    <img src="../servidor/slide/<? echo $slide[0]['img']."?".time(); ?>" data-id='0'>
                    
                    <div>
                        <h3><? echo $slide[0]['titulo']; ?></h3>
                        <p><? echo $slide[0]['descricao']; ?></p>
                    </div>
                    
                    <span class="editar" data-id="1">
                        <img src="../img/editar-branco.png">
                    </span>
                </div>
                <h2><span>Slide 2</span></h2>
                <div class="slide adm">
                    <img src="../servidor/slide/<? echo $slide[1]['img']."?".time(); ?>" data-id='1'>
                    
                    <div>
                        <h3><? echo $slide[1]['titulo']; ?></h3>
                        <p><? echo $slide[1]['descricao']; ?></p>
                    </div>
                    
                    <span class="editar" data-id="2">
                        <img src="../img/editar-branco.png">
                    </span>
                </div>
                <h2><span>Slide 3</span></h2>
                <div class="slide adm">
                    <img src="../servidor/slide/<? echo $slide[2]['img']."?".time(); ?>" data-id='2'>
                    
                    <div>
                        <h3><? echo $slide[2]['titulo']; ?></h3>
                        <p><? echo $slide[2]['descricao']; ?></p>
                    </div>
                    
                    <span class="editar" data-id="3">
                        <img src="../img/editar-branco.png">
                    </span>
                </div>
        </section>
        
        <section id="editar">
            <img src="../img/fechar.png" class="fechar">
            <div>
                <h3>Editar Slide</h3>
                
                <form>
                    <div class="input" style="display: none">
<!--
                        <label>Título</label>
                        <input type="text" placeholder="Título" name="titulo">
-->
                    </div>
                    <div class="input">
                        <label>Descrição</label>
                        <input type="text" placeholder="Descrição do slide" name="descricao">
                    </div> 
                    <div class="input">
                        <label>Imagem</label>
                        <div class="upload">
                            <div class="nome">Nenhum arquivo</div>
                            <label for="imagem"><img src="../img/upload.png">Procurar</label>
                            <input type="file" id="imagem" name="imagem" accept="image/*">
                        </div>
                    </div>

                    <button class="botao">Atualizar</button>
                    <div class="clear"></div>
                </form>
            </div>
        </section>
        
        <? 
        include("../html/rodape.html");
        include("../html/popup.html");
		include("aviso.php");
        ?>
    </body> 
    <script type="text/javascript">
        var adm = true;
        var slides = <? echo json_encode($slide); ?>
    </script>
    <script src="../js/jquery.mask.js"></script>
    <script src="../js/menu.js"></script>
    <script src="../js/sobre.js?<? echo time(); ?>"></script>
</html>