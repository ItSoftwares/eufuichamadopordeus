<?
date_default_timezone_set("America/Sao_Paulo");
session_start();
require "../php/sessao_usuario.php";
verificarSessao(1);
require "../php/conexao.php"; 



$encontros = DBselect("encontros", "where finalizado=0 order by data ASC");
$pagamentos = DBselect("pagamento", "order by id DESC");
$inscritos = DBselect("encontro_inscritos", "order by id DESC");
$usuarios = DBselect("usuario", "where estado_conta=1 order by id ASC");
$quantidade = array();
$enc_inscritos = array();
 
if (count($encontros)==0) $encontros = array();
else {
    $temp = [];
    foreach($encontros as $e) {
        $quantidade[$e['id']] = 0;
        $temp[$e['id']] = $e; 
        $temp[$e['id']]['hora'] = date("H:i", $e['data']);
    }
    $encontros = $temp;
}

if (count($inscritos)==0) $inscritos = array();
else {
    $temp = [];
    foreach($inscritos as $i) {
        if (!array_key_exists($i['id_encontro'], $enc_inscritos)) $enc_inscritos[$i['id_encontro']] = [];
        if (array_key_exists($i['id_encontro'], $quantidade)) $quantidade[$i['id_encontro']]++;

        array_push($enc_inscritos[$i['id_encontro']], $i['id']);
        $temp[$i['id']] = $i; 
    }
    $inscritos = $temp;
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

$link = DBselect("youtube", "where id=1")[0]['link'];

?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>ADS - Agenda</title>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="itsoftwares">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <link rel="shortcut icon" type="image/png" href="img/logo.png"/>
        <link rel="stylesheet" href="../css/geral.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../css/agenda.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../cssmobile/geral.css" media="(max-width: 999px)">
        <link rel="stylesheet" href="../cssmobile/agenda.css" media="(max-width: 999px)">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </head>
    
    <body>
        <? include("../html/menu.html"); ?>
       
        <section id="inicio">
           <div class="fundo"></div>
           
           <div id="nome">
               <h1>Agenda</h1>
               <a href="encontros" class="botao" id="encontro-botao">Encontros</a>
               <a href="#" class="botao" id="aviso-botao">Aviso</a>
               <a href="agenda" class="botao" id="agenda-botao">Agenda</a>
           </div>
       </section>
       
        <section id="encontros">
             <button class="botao" id="exibir">Exibir Vídeo</button>
       	   <?
		   foreach($encontros as $e) {
           ?>
           <div class="encontro" data-id="<? echo $e['id']; ?>">
               <section>
                   <div class="dados">
                       <span><b>Tema:</b> <? echo $e['tema']; ?></span>
                       <span><b>Cidade:</b> <? echo $e['cidade']; ?></span>
                       <span><b>Data:</b> <? echo date("d/m/Y", $e['data']); ?></span>
                   </div>

                   <div>
                       <?
                        // MOSTRAR BOTÃO CANCELAR
                        $cancelado = false;
                       if ($e['cancelado']==0) {
                       ?>
                       <button class="botao cancelar">Cancelar</button>
                       <?
                       } else {
                           $cancelado = true;
                       ?>
                       <button class="botao" disabled>Cancelado</button>
                       <? }
               
                        // MOSTRAR BOTÃO EXIBIR
                       if ($e['presenca']==0) {
                       ?>
                       <button class="botao exibir" <? echo $cancelado?"disabled":""; ?>>Exibir Presença</button>
                       <?
                       } else {
                       ?>
                       <button class="botao ocultar" <? echo $cancelado?"disabled":""; ?>>Presença exibida</button>
                       <? }
               
                        // MOSTRAR BOTÃO ESGOTAR
                        
                       if ($e['esgotado']==0) {
                       ?>
                       <button class="botao encerrar" <? echo $cancelado?"disabled":""; ?>>Encerrar Inscrições</button>
                       <?
                       } else {
                       ?>
                       <button class="botao esgotado" <? echo $cancelado?"disabled":""; ?>>Vagas Esgotadas</button>
                       <? }
               
                        // MOSTRAR BOTÃO EDITAR
                       if ($e['agendado']==0) {
                       ?>
                       <button class="botao editar" <? echo $cancelado?"disabled":""; ?>>Editar</button>
                       <?
                       } else {
                       ?>
                       <button class="botao reeditar" <? echo $cancelado?"disabled":""; ?>>Reeditar</button>
                       <? } ?>
                       
                       <button class="botao excluir" <? echo ($quantidade[$e['id']]>0)?"disabled":""; ?>>Excluir</button>
                   </div>
               </section>
               
               <span class="inscritos"><b>Inscritos:</b> <? echo $quantidade[$e['id']]; ?></span>
               
               <table>
                   <tr>
                       <th>Participante</th>
                       <th>Onde Mora</th>
                       <th>Data da Inscrição</th>
                   </tr>
                   <?
                   if (isset($enc_inscritos[$e['id']])>0) {
                       foreach($enc_inscritos[$e['id']] as $participante) {
                           $nome = $usuarios[$inscritos[$participante]['id_usuario']]['nome'];
                           $cidade = $usuarios[$inscritos[$participante]['id_usuario']]['cidade'];
                           $estado = $usuarios[$inscritos[$participante]['id_usuario']]['estado'];
                           $data = date("d/m/Y", $inscritos[$participante]['time']);
                   ?>
                   <tr>
                       <td><? echo $nome; ?></td>
                       <td><? echo $cidade.", ".$estado; ?></td>
                       <td><? echo $data; ?></td>
                   </tr>
                   <?
                       }
                   }
                   ?>
               </table>
           </div>
           <?
           }
		   ?>
       </section>
        
        <section id="novo">
            <img src="../img/fechar.png" class="fechar">
            <div>
                <h3>Agendar Encontro</h3>
                
                <form>
                   <div class="input">
                       <label>Como será nosso encontro</label>
                       <textarea name="como" placeholder="Descreva aqui o encontro" required></textarea>
                   </div>
                   <div class="input">
                        <label>Imagem de explicação</label>
                        <div class="upload">
                            <div class="nome">Nenhum arquivo</div>
                            <label for="imagem3"><img src="../img/upload.png">Procurar</label>
                            <input type="file" id="imagem3" name="imagem3" accept="image/*">
                        </div>
                    </div>
<!--                    <div class="input metade"></div>-->
                    <hr>
                    <div class="input metade">
                        <label>Data</label>
                        <input type="text" placeholder="Data <? echo date("d/m/Y"); ?>" name="data" data-mask="00/00/0000" required>
                    </div> 
                    <div class="input metade">
                        <label>Hora Inicio</label>
                        <input type="text" placeholder="Inicio 12:00" name="hora_inicio" data-mask="00:00" pattern=".{5,}" required>
                    </div> 
                    <div class="input">
                        <label>Tema</label>
                        <input type="text" placeholder="Tema do Encontro" name="tema" required>
                    </div>
<!--
                    <div class="input metade">
                        <label>Tema</label>
                        <input type="text" placeholder="Tema do Encontro" name="tema" required>
                    </div>
-->
                    <div class="input metade">
                        <label>Local</label>
                        <input type="text" placeholder="Local do Encontro" name="local" required>
                    </div>
                    <div class="input metade">
                        <label>Endereço</label>
                        <input type="text" placeholder="Endereço do Encontro" name="endereco" required>
                    </div>
                    <div class="input metade">
                        <label>Cidade</label>
                        <input type="text" placeholder="Cidade do encontro" name="cidade" maxlength="50" required> 
                    </div> 
                    <div class="input metade">
                        <label>Valor da Inscrição (R$)</label>
                        <input type="number" placeholder="Valor da inscrição" name="valor" step="any" min="10" required>
                    </div> 
                    <div class="input metade">
                        <label>Imagem 1</label>
                        <div class="upload">
                            <div class="nome">Nenhum arquivo</div>
                            <label for="imagem1"><img src="../img/upload.png">Procurar</label>
                            <input type="file" id="imagem1" name="imagem1" accept="image/*">
                        </div>
                    </div>
                    <div class="input metade">
                        <label>Imagem 2</label>
                        <div class="upload">
                            <div class="nome">Nenhum arquivo</div>
                            <label for="imagem2"><img src="../img/upload.png">Procurar</label>
                            <input type="file" id="imagem2" name="imagem2" accept="image/*">
                        </div>
                    </div>
                    
                    <div class="input">
                        <label>Observações</label>
                        <textarea name="observacao" placeholder="Observações" required></textarea>
                    </div>
                    
                    <button class="botao">Atualizar</button>
<!--                    <div class="clear"></div>-->
                </form>
            </div>
        </section>
        
        <section id="cancelar">
            <img src="../img/fechar.png" class="fechar">
            
            <div>
                <h3>Motivo do Cancelamento</h3>
                <form>
                    <div class="input">
                        <textarea name="motivo" placeholder="Informe o motivo do cancelamento" required></textarea>
                    </div>
                    <input type="hidden" name="id" value="0">
                    <input type="hidden" name="funcao" value="atualizar">
                    <input type="hidden" name="cancelado" value="1">
                    <button class="botao">Confirmar Cancelamento</button>
                    <div class="clear"></div>
                </form>
            </div>
        </section>
        
        <section id="video-link">
            <img src="../img/fechar.png" class="fechar">
            
            <div>
                <h3>Link do Vídeo</h3>
                <form>
                    <div class="input">
                        <label>Link</label>
                        <input type="text" placeholder="Somente ID do vídeo. Ex.: nSGhys8EW90" value="<? echo $link; ?>" name="link">
                    </div>
                    <input type="hidden" name="id" value="1">
                    <input type="hidden" name="funcao" value="atualizar">
                    <button class="botao">Salvar e exibir</button>
                    <div class="clear"></div>
                </form>
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
        var pagamentos = <? echo json_encode($pagamentos); ?>;
        var inscritos = <? echo json_encode($inscritos); ?>;
        var enc_inscritos = <? echo json_encode($enc_inscritos); ?>;
    </script>
    <script src="../js/jquery.mask.js"></script>
    <script src="../js/menu.js"></script>
    <script src="../js/agenda.js?<? echo time(); ?>"></script>
</html>