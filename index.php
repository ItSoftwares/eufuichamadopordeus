<?
session_start();

if (!(isset($_GET['set']) and $_GET['set']==1) and array_key_exists("tipo_usuario", $_SESSION)) {
    if ($_SESSION['tipo_usuario']==1) {
        header("location: /paginas/hoje");
    } else if ($_SESSION['tipo_usuario']==2) {
        header("location: /adm/participantes"); 
    }
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-109768896-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-109768896-1');
        </script>

        <title>Eu Fui Chamado por Deus - LOGIN</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/geral.css">
        <link rel="stylesheet" href="css/index.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="cssmobile/index.css" media="(max-width: 999px)">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </head>
    
    <body>
       <div id="fundo"></div>
        <div id="geral">
            <div>
<!--                <h1>Eu Fui Chamado por Deus</h1>-->
               <img src="img/logo.png" style="width: 100%">
                <p>Faça parte do maior projeto de apoio ao jovem cristão do Brasil!</p>
            </div>
            <div id="login">
                <section>
                    <h2 class="titulo-normal">LOGIN</h2>
                    <form>
                        <div class="input">
                            <label>Email</label>
                            <input type="text" placeholder="Informe seu Email" name="email" required autofocus>
                        </div>
                        
                        <div class="input">
                            <label>Senha</label>
                            <input type="password" placeholder="Informe sua Senha" name="senha" required>
                        </div>
                        
                        <div id="linha">
                            <div id="lembrar">
                                <input type="checkbox" name="lembrar" id="check">
                                <label for="check"><div><img src="img/input-mark.png" alt=""></div><span>Lembrar</span></label>
                                
                            </div>

                            <a href="#">Recuperar Senha</a> 
                        </div>
                        
                        <button class="botao">Entrar</button>
                        
                    </form>
                    <a href="cadastro">CRIE SUA CONTA, PARTICIPE</a>
                </section>
            </div>
        </div>
        
        <section id="esqueceu">
            <div>
                <h3>Recuperar Senha</h3>
                <img src="img/fechar.png">
                <form>
                    <div class="input">
<!--                        <label>Email</label>-->
                        <input type="email" placeholder="Informe seu Email" name="email" required>
                    </div>
                    
                    <button class="botao">Recuperar</button>
                </form>
            </div>
        </section>
        
        <?
        include("html/popup.html");
        ?>
        <p><a href="https://www.instagram.com/itsoftwares/" target="_blank">Copyright © Todos os direitos reservados.</a></p>
    </body>
    
    <script src="js/index.js?<? echo time(); ?>"></script>
    <? 
//    session_unset();
//    session_destroy();
    ?>
</html>