<?
session_start();
require "../php/sessao_usuario.php";
// verificarSessao();
require "../php/conexao.php";

$selec = 'contato';
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Eu Fui Chamado por Deus - Convite</title>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="itsoftwares">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <link rel="shortcut icon" type="image/png" href="img/logo.png"/>
        <link rel="stylesheet" href="../css/geral.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../css/convite.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../cssmobile/geral.css" media="(max-width: 999px)">
        <link rel="stylesheet" href="../cssmobile/convite.css" media="(max-width: 999px)">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </head>
    
    <body>
       <? include('../html/menu.html'); ?>
       
       <section id="inicio">
           <div class="fundo"></div>
           
           <div id="nome">
               <h1>Contato</h1>
           </div>
       </section>
        <section>
            <h2><span>ESCREVA PARA NÓS</span></h2>
        </section>
        <section id="convite">
            <div>
                <div>
                    <h1>Queremos lhe Ouvir</h1>
                    <p>Que bom que despertamos sua atenção em nos receber em sua igreja! Estamos trabalhando para organizar os demais encontros. Essa agenda de convites estará disponível a partir do Primeiro Encontro Eu Fui Chamado por Deus. Se você deseja receber nossa equipe converse com o seu pastor, é fundamental que o líder da igreja tenha o interesse no evento. Assim que possível estarão disponíveis aqui as informações necessárias para o agendamento de encontros. <br><br>
                    Escreva para nós, queremos ouvir sua sugestão, ou se você já tem interesse em organizar um encontro em sua igreja nos envie um e-mail. <br><br>
                    eufuichamadopordeus@gmail.com</p>
                </div>
                
                <form id="contato">
                    <div class="input">
                        <label>Nome</label>
                        <input type="text" name="nome" placeholder="Nome" required>
                    </div>
                    <div class="input">
                        <label>Email</label>
                        <input type="mail" name="email" placeholder="Email" required>
                    </div>
                    <div class="input">
                        <label>Mensagem</label>
                        <textarea name="mensagem" placeholder="Digite aqui sua mensagem" maxlength="500" required minlength="50"></textarea>
                    </div>
                    <button class="botao">Enviar</button>
                    <div class="clear"></div>
                </form>
            </div>
        </section>
        
        <? 
        include("../html/popup.html");
        include("../html/rodape.html");
        ?>
    </body> 
    <script type="text/javascript">
        
    </script>
    <script src="../js/menu.js"></script>
    <script src="../js/contato.js"></script>
</html>