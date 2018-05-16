<?
session_start();
require "../php/sessao_usuario.php";
// verificarSessao();
require "../php/conexao.php";

$selec = 'encontro';
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Eu Fui Chamado por Deus - Encontros</title>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="itsoftwares">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <link rel="shortcut icon" type="image/png" href="img/logo.png"/>
        <link rel="stylesheet" href="../css/geral.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../css/encontro.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../cssmobile/geral.css" media="(max-width: 999px)">
        <link rel="stylesheet" href="../cssmobile/encontro.css" media="(max-width: 999px)">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </head>
    
    <body>
        <? include('../html/menu.html'); ?>
       
       <section id="inicio">
           <div class="fundo"></div>
           
           <div id="nome">
               <h1>Encontros</h1>
           </div>
       </section>
        
        <section id="encontro">
           <h2><span>Juntos somos mais fortes</span></h2>
            
            <div id="imagem">
                <img src="../img/encontros-1.jpg">
                <span></span>
                <div class="texto">
                    <p>Em breve teremos o Primeiro Encontro Eu Fui Chamado Por Deus! Não deixe de participar!</p>
                    <p>Por isso será muito importante seu cadastro, mandaremos novidades através do Whatsapp.</p>
                    <p>Não deixe também de acessar o site, vamos comunicar aqui as informações sobre nosso primeiro encontro.</p>
                </div>
            </div>
            
            <div id="colunas">
                <h3>Neste Encontro teremos uma palestra de grande importância que poderá mudar sua vida.</h3>

                <div>
                    <div class="imagem">
                        <img src="../img/encontros-2.jpg" class="fundo-imagem">
                        <img src="../img/encontros-2.jpg">
                    </div>
                    <div class="imagem">
                        <img src="../img/encontros-3.jpg" class="fundo-imagem">
                        <img src="../img/encontros-3.jpg">
                    </div>
                </div>
            </div>
            
            <div id="info">
                <h3>O Encontro será organizado em breve, em um sábado a tarde, e acontecerá em dois momentos:</h3>
                
                <ul>
                    <li>
                        <div><img src="../img/palestra.jpg"></div>
                        <h4>1° MOMENTO: PALESTRA - SEGREDO DE UMA CARREIRA VITORIOSA</h4>
<!--                        <p></p>-->
                    </li>
                    <li>
                        <div><img src="../img/coffee.jpg"></div>
                        <h4>Intervalo</h4>
<!--                        <p>Durante o intervalo teremos um Coffee Break.</p>-->
                    </li>
                    <li>
                        <div><img src="../img/dinamica.jpg"></div>
                        <h4>2° Momento: Dinâmica - Interagindo com novos amigos.</h4>
                        <p></p>
                    </li>
                    <li>
                        <div><img src="../img/encerramento.jpg"></div>
                        <h4>Encerramento</h4>
                    </li>
                </ul>
            </div>
            
            <div id="etapas">
                <ol>
                    <li>A Palestra</li>
                    <span>Segredos de Uma Carreira Vitoriosa é uma palestra super envolvente, desenvolvida pelo Pastor e Escritor Delso Gomes, focada nos anseios de todos que estão construindo uma carreira profissional. O escritor narra parte de sua vida profissional onde encontrou grandes obstáculos, e seguindo alguns princípios superou todos eles. Tema imperdível para todos aqueles que desejam aprender como construir uma carreira de vitória.</span>

                    <li>O Intervalo</li>
<!--                    <span>Nada melhor que fazer novos amigos, não é mesmo?! A Palavra afirma que existem amigos mais chegados que irmãos (Provérbios 18:24). <i>Interagindo Com Novos Amigos</i> é uma dinâmica que tem o objetivo de expandir sua rede de relacionamentos. Um momento especial de descontração e troca de experiências que levará você a ampliar ainda mais seus horizontes sobre o assunto. Como faremos essa dinâmica? Será surpresa! Venha e participe conosco!</span>-->

                    <li>A Dinâmica</li>
                    <span>Nada melhor que fazer novos amigos, não é mesmo?! A Palavra afirma que existem amigos mais chegados que irmãos (Provérbios 18:24). Interagindo Com Novos Amigos é uma dinâmica que tem o objetivo de expandir sua rede de relacionamentos. Um momento especial de descontração e troca de experiências que levará você a ampliar ainda mais seus horizontes sobre o assunto. Como faremos essa dinâmica? Será surpresa! Venha e participe conosco!</span>

                    <li>Encerramento</li>
<!--                    <span>Que bom que despertamos sua atenção em nos receber em sua igreja! Estamos trabalhando para organizar os demais encontros. Essa agenda de convites estará disponível a partir do Primeiro Encontro <i>Eu Fui Chamado por Deus</i>. Se você deseja receber nossa equipe converse com o seu pastor, é fundamental que o líder da igreja tenha o interesse no evento. Assim que possível estarão disponíveis aqui as informações necessárias para o agendamento de encontros.<br>Escreva para nós, queremos ouvir sua sugestão, ou se você já tem interesse em organizar um encontro em sua igreja nos envie um e-mail.<br><br>eufuichamadopordeus@gmail.com</span>-->
                </ol>
            </div>
        </section>
        
        <? 
        include("../html/rodape.html");
        ?>
    </body> 
    <script type="text/javascript">
        
    </script>
    <script src="../js/menu.js"></script>
</html>