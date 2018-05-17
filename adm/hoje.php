<?
session_start();
require "../php/sessao_usuario.php";
require "../php/listarArquivos.php";
verificarSessao(1);
require "../php/conexao.php";

$dirname = dirname(__DIR__).DIRECTORY_SEPARATOR."servidor".DIRECTORY_SEPARATOR."hoje";
//echo $dirname;
//$imagens = listar($dirname);
//$imagens = $imagens['informacoes']; 
//$temp = [];
//foreach($imagens as $i) {
//    $temp[$i['filename']] = $i;
//}
//$imagens = $temp;
$conteudo = DBselect("hoje"); 
$frases = DBselect("frase_diaria"); 

$hoje = date("w")+1;
$semana = intval(date("z")/7);

while($semana>7) {
    $semana -= 7;
}

$versao = 0;

foreach($conteudo as $c) {
    if ($c['versao']>$versao) $versao = $c['versao'];
}

$dias = ["domingo", "segunda", "terça", "quarta", "quinta", "sexta", "sábado"];

$selec = "hoje";
?> 
<!DOCTYPE HTML>
<html>
    <head>
        <title>ADM - Hoje</title>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="itsoftwares">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <link rel="shortcut icon" type="image/png" href="img/logo.png"/>
        <link rel="stylesheet" href="../css/geral.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../css/hoje.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../cssmobile/geral.css" media="(max-width: 999px)">
        <link rel="stylesheet" href="../cssmobile/hoje.css" media="(max-width: 999px)">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </head>
    
    <body>
        <? include("../html/menu.html"); ?>
       
       <section id="inicio">
           <div class="fundo"></div>
           
           <div id="nome">
               <h1>Conteudo Semanal</h1>
               <h3 id="editar-frases" style="cursor: pointer; text-shadow: 1px 1px 1px rgba(0,0,0,.5)">Clique para editar as frases semanais</h3> 
               <a href="encontros" class="botao" id="encontro-botao">Encontros</a>
               <a href="#" class="botao" id="aviso-botao">Aviso</a>
               <a href="agenda" class="botao" id="agenda-botao">Agenda</a>
           </div>
       </section>
        
        <section id="hoje">
            <h2><span>Imagens topo</span></h2>
            
            <div id="semana">
                <div class="dia" data-id="1">
                    <h4>Domingo</h4>
                    <img src="../servidor/hoje/domingo.jpg">
                </div>
                <div class="dia" data-id="2">
                    <h4>Segunda</h4>
                    <img src="../servidor/hoje/segunda.jpg">
                </div>
                <div class="dia" data-id="3">
                    <h4>Terça</h4>
                    <img src="../servidor/hoje/ter%C3%A7a.jpg">
                </div>
                <div class="dia" data-id="4">
                    <h4>Quarta</h4>
                    <img src="../servidor/hoje/quarta.jpg">
                </div>
                <div class="dia" data-id="5">
                    <h4>Quinta</h4>
                    <img src="../servidor/hoje/quinta.jpg">
                </div>
                <div class="dia" data-id="6">
                    <h4>Sexta</h4>
                    <img src="../servidor/hoje/sexta.jpg">
                </div>
                <div class="dia" data-id="7">
                    <h4>Sábado</h4>
                    <img src="../servidor/hoje/s%C3%A1bado.jpg">
                </div>
            </div>
        </section>
        
        <section id="conteudo">
            <h2><span>Conteudo Semanal</span></h2> 
            
            <div id="semanal">
                <span data-id="1">Domingo</span>
                <span data-id="2">Segunda</span>
                <span data-id="3">Terça</span>
                <span data-id="4">Quarta</span>
                <span data-id="5">Quinta</span>
                <span data-id="6">Sexta</span>
                <span data-id="7">Sábado</span>
            </div>
           
            <img src="../img/editar-branco.png" id="editar" title="Editar conteudo!">
            <h2><span id="titulo"><? echo $conteudo[$hoje-1]['titulo']; ?></span></h2>
            
            <img src="../servidor/conteudo/<? echo $hoje.".jpg?".time(); ?>" id="imagem">
            
            <div id="texto"><? echo $conteudo[$hoje-1]['texto']; ?></div>
        </section>
        
        <section id="formulario" class='extra'>
            <img src="../img/fechar.png" class="fechar">
            <div>
                <h3>Editar conteudo semanal</h3>
                
                <form>
                    <div class="input">
                        <label>Título</label>
                        <input type="text" placeholder="Título" name="titulo" required>
                    </div>
                    <div class="input">
                        <label>Texto</label>
                        <div id="botoes">
                            <span id="negrito"><b>N</b></span>
                            <span id="italico"><i>I</i></span>
                            <span id="sublinhado"><u>S</u></span>
                        </div>
                        <textarea name="texto" placeholder="Escreva o texto que você deseja para esse dia"></textarea>
                        
                        <div id="ver">Prévia do texto, digite alguma coisa.</div>
                    </div>
                    <div class="input">
                        <label>Imagem</label>
                        <div class="upload">
                            <div class="nome">Nenhum arquivo</div>
                            <label for="imagem-input"><img src="../img/upload.png">Procurar</label>
                            <input type="file" id="imagem-input" name="imagem" accept="image/*">
                        </div>
                    </div>
                    <button class="botao">Atualizar</button>
                    <div class="clear"></div>
                </form>
            </div>
        </section>
        
        <section id="topo" class='extra'>
            <img src="../img/fechar.png" class="fechar">
            <div>
                <h3>Atualizar Imagem topo</h3>
                
                <form>
                    <div class="input">
                        <label>Imagem</label>
                        <div class="upload">
                            <div class="nome">Nenhum arquivo</div>
                            <label for="imagem-input-topo"><img src="../img/upload.png">Procurar</label>
                            <input type="file" id="imagem-input-topo" name="imagem" accept="image/*">
                        </div>
                    </div>
                    <button class="botao">Atualizar</button>
                    <div class="clear"></div>
                </form>
            </div>
        </section>
        
        <section id="frases" class='extra'>
            <img src="../img/fechar.png" class="fechar">
            <div>
                <h3>Atualizar Frases</h3>
                
                <form>
                    <div class="input">
                        <label>Frase do Domingo</label>
                        <input type="text" name="1" placeholder="Digite a primeira frase">
                    </div>
                    <div class="input">
                        <label>Frase da Segunda</label>
                        <input type="text" name="2" placeholder="Digite a segunda frase">
                    </div>
                    <div class="input">
                        <label>Frase da Terça</label>
                        <input type="text" name="3" placeholder="Digite a terceira frase">
                    </div>
                    <div class="input">
                        <label>Frase da Quarta</label>
                        <input type="text" name="4" placeholder="Digite a quarta frase">
                    </div>
                    <div class="input">
                        <label>Frase da Quinta</label>
                        <input type="text" name="5" placeholder="Digite a quinta frase">
                    </div>
                    <div class="input">
                        <label>Frase da Sexta</label>
                        <input type="text" name="6" placeholder="Digite a sexta frase">
                    </div>
                    <div class="input">
                        <label>Frase Sábado</label>
                        <input type="text" name="7" placeholder="Digite a sétima frase">
                    </div>
                    <button class="botao">Atualizar</button>
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
        var hoje = <? echo json_encode($hoje); ?>;
        var conteudo = <? echo json_encode($conteudo); ?>;
        var dias = <? echo json_encode($dias); ?>;
        var frases = <? echo json_encode($frases); ?>;
        var semana = <? echo json_encode($semana); ?>;
        var ultima = <? echo $versao; ?>;
        
        $(".dia[data-id="+hoje+"]").addClass("hoje");
        $("#semanal [data-id="+hoje+"]").addClass("selecionado");
    </script>
    <script src="../js/jquery.mask.js"></script>
    <script src="../js/menu.js"></script>
    <script src="../js/hoje.js?<? echo time() ?>"></script>
</html>