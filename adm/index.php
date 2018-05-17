<?
session_start();
require "../php/sessao_usuario.php";
verificarSessao(1);
require "../php/conexao.php";

$usuarios = DBselect("usuario", "order by id DESC");
$ultima = 0;

$selec = "participantes";
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>ADM - Participantes</title>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="itsoftwares">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <link rel="shortcut icon" type="image/png" href="img/logo.png"/>
        <link rel="stylesheet" href="../css/geral.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../css/usuarios.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../cssmobile/geral.css" media="(max-width: 999px)">
        <link rel="stylesheet" href="../cssmobile/usuarios.css" media="(max-width: 999px)">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </head>
    
    <body>
        <? include("../html/menu.html"); ?>
       
       <section id="inicio">
           <div class="fundo"></div>
           
           <div id="nome">
               <h1>Participantes</h1>
               <a href="encontros" class="botao" id="encontro-botao">Encontros</a>
               <a href="#" class="botao" id="aviso-botao">Aviso</a>
               <a href="agenda" class="botao" id="agenda-botao">Agenda</a>
           </div>
       </section>
        
        <section id="usuarios">
            <h3>Número de Participantes: <span>0</span></h3>
            <h2><span>Lista de Participantes</span></h2>
            <button id="apagar" class="botao">Limpar registros</button>
            <div>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Celular</th>
                        <th>Nascimento</th>
                        <th>Pais</th>
                        <th>UF</th>
                        <th>Cidade</th>
                        <th>Eu sou</th>
                        <th>Escolaridade</th>
                        <th>Login</th>
                        <th>Senha</th>
                    </tr>
                    <?
                    if (isset($usuarios)) {
                        $i=0;
                        $qtd=0;
                        foreach($usuarios as $u) {
                            $i++;
                            $pagina = ceil($i/20);
                            $escolaridade = "";
                            if ($u['escolaridade']==1) $escolaridade="1º Grau";
                            else if ($u['escolaridade']==2) $escolaridade="2º Grau";
                            else if ($u['escolaridade']==3) $escolaridade="Superior Incompleto";
                            else if ($u['escolaridade']==4) $escolaridade="Superior Completo";
                            else if ($u['escolaridade']==5) $escolaridade="Pós Graduação";
                            else if ($u['escolaridade']==6) $escolaridade="Mestrado";
                            else if ($u['escolaridade']==7) $escolaridade="Doutorado";
                            $estilo="";
                            if ($pagina>1) $estilo="style='display: none'";
                            if ($u['estado_conta']==1) $qtd++;
                            ?>
                            
                            <tr data-estado='<? echo $u['estado_conta']; ?>' data-pagina='<? echo $pagina; ?>' <? echo $estilo; ?>>
                                <td data-nome="ID"><? echo $u['id']!=""?$u['id']:"-"; ?></td>
                                <td data-nome="Nome"><? echo $u['nome']!=""?$u['nome']:"-"; ?></td>
                                <td data-nome="Celular" <? echo $u['celular']!=""?" data-mask='(00) 00000-0000'":""; ?>><? echo $u['celular']!=""?$u['celular']:"-"; ?></td>
                                <td data-nome="Data de Nasimento" <? echo $u['celular']!=""?" data-mask='00/00/0000'":""; ?>><? echo $u['data_nascimento']!=""?$u['data_nascimento']:"-"; ?></td>
                                <td data-nome="País"><? echo $u['pais']!=""?$u['pais']:"-"; ?></td>
                                <td data-nome="Estado"><? echo $u['estado']!=""?$u['estado']:"-"; ?></td>
                                <td data-nome="Cidade"><? echo $u['cidade']!=""?$u['cidade']:"-"; ?></td>
                                <td data-nome="Eu sou"><? echo $u['religiao']!=""?$u['religiao']:"-"; ?></td>
                                <td data-nome="Escolaridade"><? echo $u['escolaridade']==0?"-":$escolaridade; ?></td>
                                <td data-nome="Email"><? echo $u['email']!=""?$u['email']:"-"; ?></td>
                                <td data-nome="Senha"><? echo $u['senha']!=""?$u['senha']:"-"; ?></td>
                            </tr>
                            <?
                        }
                    }
                    ?>
                </table>
            </div>
            
            <div id="paginas">
                <ul>
                    <?
                    for ($i=1; $i<=ceil(count($usuarios)/20);$i++) {
                        echo "<li ".($i==1?"class='selecionado'":"")." data-pagina='{$i}' data-aberto='0'>{$i}</li>"; 
                        $ultima = $i;
                    }
                    ?>
                </ul>
            </div>
        </section>
        
        <? 
        include("../html/rodape.html");
        include("../html/popup.html");
        include("../html/confirmacao.html");
        include("aviso.php");
        ?>
    </body> 
    <script type="text/javascript">
        var usuarios = <? echo json_encode($usuarios); ?>;
        var ultima = <? echo $ultima; ?>;
        var qtd = <? echo $qtd; ?>;
    </script>
    <script src="../js/jquery.mask.js"></script>
    <script src="../js/menu.js"></script>
    <script src="../js/usuario.js?<? echo time() ?>"></script>
</html>