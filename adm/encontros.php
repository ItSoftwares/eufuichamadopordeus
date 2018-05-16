<?
session_start();
require "../php/sessao_usuario.php";
verificarSessao(1);
require "../php/conexao.php"; 
//$data = strtotime("26-11-2017 12:00");
//echo $data;
//echo "<br>".(date("d-m-Y, H:i", $data));
$encontros = DBselect("encontros e", "order by data ASC, id DESC", "*, (select COUNT(id_usuario) from encontro_inscritos where id_encontro=e.id) inscritos, (select COUNT(id_usuario) from encontro_inscritos where presente=1 and id_encontro=e.id) presentes, (select COUNT(id_usuario) from erro_pagamento where id_encontro=e.id) erro, (select COUNT(id_usuario) from quero_ir where id_encontro=e.id) quero");
$usuarios = DBselect("usuario", "where estado_conta=1 order by id ASC", "id, nome, email, cidade, estado, celular");
$erros = DBselect("erro_pagamento", "order by time DESC");
$quero = DBselect("quero_ir", "order by time DESC");
$pagamentos = DBselect("pagamento");
$enc_inscritos = [];

if (count($encontros)==0) $encontros = array();
else {
    $temp = [];
    foreach($encontros as $e) {
        $e['quantidade']=0;
        $temp[$e['id']] = $e;
    }
    $encontros = $temp;
}

if (count($usuarios)==0) $usuarios = array();
else {
    $temp = [];
    foreach($usuarios as $u) {
        $temp[$u['id']] = $u;
    }
    $usuarios = $temp;
}

if (count($pagamentos)==0) $pagamentos = array();
else {
    $temp = [];
    foreach($pagamentos as $p) {
        $temp[$p['id']] = $p;
    }
    $pagamentos = $temp;
}

//$quantidades = DBselect("encontro_inscritos", "where presente=1 group by id_encontro", "COUNT(id_usuario) as quantidade, id_encontro");
//if (count($quantidades)==0) $quantidades = array();
//else {
//    $temp = []; 
//    foreach($quantidades as $q) {
//        $temp[$q['id_encontro']] = $q;
//        $encontros[$q['id_encontro']]['quantidade'] = $q['quantidade'];
//    }
//    $quantidades = $temp;
//}

$inscricoes = DBselect("encontro_inscritos", "order by id_encontro ASC, id ASC");

?>
<!DOCTYPE HTML>
<html>
    <head> 
        <title>ADS - Encontros Inscritos</title>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="itsoftwares">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <link rel="shortcut icon" type="image/png" href="img/logo.png"/>
        <link rel="stylesheet" href="../css/geral.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../css/encontros.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../cssmobile/geral.css" media="(max-width: 999px)">
        <link rel="stylesheet" href="../cssmobile/encontros.css" media="(max-width: 999px)">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </head>
    
    <body>
       <header class="">
           <div>
               <a href="/"><img src="../img/logo.png" alt=""></a>

               <img src="../img/menu.png" id="menu-botao">

               <nav>
                   <ul>
                       <li><a href="hoje">Hoje</a></li>
                       <li><a href="participantes">Participantes</a></li>
                       <li><a href="gerar" target="_blank">Gerar</a></li>
                       <li><a href="sobre">Sobre ADM</a></li>
                       <li><a href="fotos">Fotos ADM</a></li>
                       <li><a href="../php/sair.php">Sair</a></li>
                   </ul>
               </nav>
           </div>
       </header>
       
       <section id="inicio">
           <div class="fundo"></div>
           
           <div id="nome">
               <h1>Encontros Inscritos</h1>
               <a href="encontros" class="botao" id="encontro-botao">Encontros</a>
               <a href="#" class="botao" id="aviso-botao">Aviso</a>
               <a href="agenda" class="botao" id="agenda-botao">Agenda</a>
           </div>
       </section>
        
        <section id="encontros">
            <h2><span>Lista de Encontros</span></h2>
            <button id="botao-novo" class="botao">Novo Encontro</button>
            <div>
                <table id="principal">
                    <tr>
                        <th>ID</th>
                        <th>Cidade</th>
                        <th>Data do Encontro</th>
                        <th>Inscritos</th>
                        <th>Presentes</th>
                        <th>Pagarão no Local</th>
                        <th>Erro no Pagamento</th>
                        <th>Ações</th>
                    </tr>
                    <?
                    if (isset($encontros)) {
                        $i=0;
                        foreach($encontros as $e) {
                            $i++;
                            $qtd = 0;
                            $pagina = ceil($i/10);
                            $estilo="";
                            if ($pagina>1) $estilo="style='display: none'";
                            ?>
                            
                            <tr data-id='<? echo $e['id']; ?>' data-pagina='<? echo $pagina; ?>' data-time="<? echo $e['data']; ?>" <? echo $estilo; ?>>
                                <td data-nome="ID"><? echo $e['id']; ?></td>
                                <td data-nome="Nome"><? echo $e['cidade']; ?></td>
                                <td data-nome="Data"><? echo date("d/m/Y, H:i", $e['data']) ?></td>
                                <td data-nome="Inscritos"><? echo $e['inscritos']; ?></td>
                                <td data-nome="Presentes"><? echo $e['presentes']; ?></td>
                                <td data-nome="Pagarão no local"><? echo $e['quero']; ?></td>
                                <td data-nome="Erro no Pagamento"><? echo $e['erro']; ?></td>
                                <td data-nome="Ações" class='ver'>
                                    <img src="../img/menu.png" title="Gerenciar encontro" class="editar <? echo $e['finalizado']==1?"no":""; ?>">
                                    <img src="../img/certificado.png" title="<? echo $e['finalizado']==0?"Gerar Certificados":"Esse encontro ja foi finalizado e os certificados foram gerados."; ?>" class="gerar <? echo ($e['presentes']>0 and $e['finalizado']==0)?"":"no"; ?>">
                                    <img src="../img/usuarios.png" title="Participantes Inscritos" class="inscritos">
                                    <img src="../img/input-mark.png" title="Participantes Presentes" class="presentes">
                                    <img src="../img/exclamacao.png" title="Pagarão no local" class="quero">
                                    <img src="../img/fechar.png" title="Erros no pagamento" class="erros">
                                </td>
                            </tr>
                            <tr data-refer='<? echo $e['id']; ?>' class="lista-inscritos" data-time="<? echo $e['data']+1; ?>">
                                <td colspan="8">
                                    <table>
                                        <tr>
                                            <th>Participante Inscrito</th>
                                            <th>Onde Mora</th>
                                            <th>Data da Inscrição</th>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr data-refer='<? echo $e['id']; ?>' class="lista-presentes" data-time="<? echo $e['data']+2; ?>">
                                <td colspan="8">
                                    <table>
                                        <tr>
                                            <th>Participante Presente</th>
                                            <th>Onde Mora</th>
                                            <th>Data da Inscrição</th>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr data-refer='<? echo $e['id']; ?>' class="lista-erros" data-time="<? echo $e['data']+3; ?>">
                                <td colspan="8">
                                    <table>
                                        <tr>
                                            <th>Já fiz contato</th>
                                            <th>Participante</th>
                                            <th>Email</th>
                                            <th>Telefone</th>
                                            <th>Onde Mora</th>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr data-refer='<? echo $e['id']; ?>' class="lista-quero" data-time="<? echo $e['data']+3; ?>">
                                <td colspan="8">
                                    <table>
                                        <tr>
                                            <th>Já fiz contato</th>
                                            <th>Participante</th>
                                            <th>Email</th>
                                            <th>Telefone</th>
                                            <th>Onde Mora</th>
                                        </tr>
                                    </table>
                                </td>
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
                    $ultima = 0;
                    for ($i=1; $i<=ceil(count($encontros)/10);$i++) {
                        echo "<li ".($i==1?"class='selecionado'":"")." data-pagina='{$i}'>{$i}</li>"; 
                        $ultima = $i;
                    }
                    ?>
                </ul>
            </div>
        </section>
        
        <section id="novo">
            <img src="../img/fechar.png" class="fechar">
            <div>
                <h3>Novo Encontro</h3>
                
                <form>
                    <div class="input">
                        <label>Tema</label>
                        <input type="text" placeholder="Tema do Encontro" name="nome" required>
                    </div>
                    <div class="input">
                        <label>Data</label>
                        <input type="text" placeholder="Data <? echo date("d/m/Y"); ?>" name="data" data-mask="00/00/0000" required>
                    </div> 
                    <div class="input">
                        <label>Cidade</label>
                        <input type="text" placeholder="Cidade do encontro" name="cidade" maxlength="50" required> 
                    </div> 
                    <div class="input">
                        <label>Local</label>
                        <input type="text" placeholder="Local do encontro" name="local" maxlength="100" required> 
                    </div> 
                    <div class="input metade">
                        <label>Hora Inicio</label>
                        <input type="text" placeholder="Inicio 12:00" name="hora_inicio" data-mask="00:00" pattern=".{5,}" required>
                    </div> 
                    <div class="input metade">
                        <label>Carga Horária</label>
                        <input type="number" placeholder="Carga Horária" name="carga_horaria" required>
                    </div> 

                    <button class="botao">Criar</button>
                    <div class="clear"></div>
                </form>
            </div>
        </section>
        
        <section id="gerenciar">
            <img src="../img/fechar.png" class="fechar">
            
            <div>
                <h3>
                Confirme aqui sua presença!
                <br>
                <span>Nome do Encontro</span>
                <span id="travar">
                    <input type="checkbox" name="lembrar" id="check">
                    <label for="check"><div><img src="../img/input-mark.png" alt=""></div><span>Travar</span></label>
                </span>
                </h3>
                
                <form>
                    <div class="input metade">
                        <label>ID</label>
                        <input type="text" name="id" placeholder="ID">
                    </div>
                    
                    <div class="input metade">
                        <label>Nome</label>
                        <input type="text" name="nome" placeholder="Nome do participante">
                    </div>
                    
                    <button class="botao">Procurar</button>
                </form>
                
                <div>
                    <table>
                        <tr>
                            <th data-nome="ID">ID</th>
                            <th data-nome="Nome">Nome</th>
                            <th data-nome="Email">Email</th>
                            <th data-nome="Inscrição">Inscrição</th>
                            <th data-nome="Presente">Presente</th>
                        </tr>
                    </table>
                </div>
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
        var encontros = <? echo json_encode($encontros); ?>;
        var usuarios = <? echo json_encode($usuarios); ?>;
        var inscricoes = <? echo json_encode($inscricoes); ?>;
        var ultima = <? echo ($ultima); ?>;
        var erros = <? echo json_encode($erros); ?>;
        var quero = <? echo json_encode($quero); ?>;
    </script>
    <script src="../js/jquery.mask.js"></script>
    <script src="../js/menu.js"></script>
    <script src="../js/encontroInscritos.js?<? echo time(); ?>"></script>
</html>