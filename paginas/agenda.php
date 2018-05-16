<?
session_start();
require "../php/sessao_usuario.php";
verificarSessao();

require_once('../php/pagseguro/config_pag.php');
require_once('../php/pagseguro/utils.php');
require "../php/conexao.php"; 
require "../php/classes/usuario.class.php"; 
date_default_timezone_set("America/Sao_Paulo");

$usuario = unserialize($_SESSION['usuario']);

if ($usuario->rua=="" or $usuario->bairro=="" or $usuario->numero=="" or $usuario->cep=="") {
    header("location: conta?atualizar=1");
}

$encontros = DBselect("encontros", "where finalizado=0 and agendado=1 order by data ASC");
$pagamentos = DBselect("pagamento", "where id_usuario={$usuario->id} order by id DESC");

if (count($encontros)==0) $encontros = array();
else {
    $temp = [];
    foreach($encontros as $e) {
        $quantidade[$e['id']] = 0;
        $temp[$e['id']] = $e;
    }
    $encontros = $temp;
}


if (count($pagamentos)==0) $pagamentos = array();
else {
    $temp = [];
    foreach($pagamentos as $p) {
        $temp[$p['id']] = $p;
    }
    $pagamentos = $temp;
}

$params = array(
    'email' => $PAGSEGURO_EMAIL,
    'token' => $PAGSEGURO_TOKEN
);
//$header = array("Content-Type"=> "application/xml;charset=ISO-8859-1");
$header = array();

$response = curlExec($PAGSEGURO_API_URL."/sessions", $params, $header);
$json = json_decode(json_encode(simplexml_load_string($response)));
//echo $response; 
//exit;
$sessionCode = $json->id;

$link = DBselect("youtube", "where id=1")[0]['link']; 

$inscricoes = DBselect("encontro_inscritos", "order by id_encontro ASC, id ASC");
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
        <link rel="shortcut icon" type="image/png" href="img/logo.png" />
        <link rel="stylesheet" href="../css/geral.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../css/agenda.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../cssmobile/geral.css" media="(max-width: 999px)">
        <link rel="stylesheet" href="../cssmobile/agenda.css" media="(max-width: 999px)">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </head>

    <body>
        <header class="">
            <div>
                <a href="/"><img src="../img/logo.png" alt=""></a> <img src="../img/menu.png" id="menu-botao">
                <nav>
                    <ul>
                        <li><a href="hoje">Hoje</a></li>
                        <li><a href="sobre">Sobre</a></li>
                        <li><a href="encontro">Encontros</a></li>
                        <li><a href="agenda" class="selecionado">Agenda</a></li>
                        <li><a href="fotos">Fotos</a></li>
                        <li><a href="contato">Contato</a></li>
                        <li><a href="conta">Conta</a></li>
                        <li><a href="../php/sair.php">Sair</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        
        <section id="inicio">
            <div class="fundo"></div>
            <div id="nome">
                <h1>Agenda</h1> </div>
            <? if ($link!="") { ?>
                <div id="video">
                    <!--               <h1>SUPERTIL</h1>-->
                    <iframe src="https://www.youtube.com/embed/<? echo $link; ?>" frameborder="0" constrols="0"></iframe> <img src="img/fechar.png" alt=""> </div>
                <? } ?>
        </section>
        
        <section id="encontros">
            <h2><span>Agenda de Encontros</span></h2>
            <?
		   foreach($encontros as $e) {
           ?>
                <div class="encontro participante" data-id="<? echo $e['id']; ?>">
                    <section>
                        <h3><b>Cidade:</b> <? echo $e['cidade']; ?></h3>
                        <h3><b>Data:</b> <? echo date("d/m/Y, H:i", $e['data']); ?></h3>
                        <div>
                            <?
                            if ($e['presenca']==1) {
                            ?>
                                <button class="botao presenca" disabled>Confirmar Presenca</button>
                            <?
                            } 

                            if ($e['cancelado']==0) {
                            ?>
                                <button class="botao info">Informações</button>
                            <?
                            } else {
                            ?>
                                <button class="botao cancelado">Cancelado</button>
                            <? } ?>
                        </div>
                    </section>
                </div>
                <?
           }
		   ?>
        </section>
        
        <section id="agendar"> <img src="../img/fechar.png" class="fechar">
            <div>
                <h3>Fazer inscrição</h3>
                <div id="como">
                    <p></p> <img src="#" id="imagem3"> </div>
                <div id="container">
                    <ul>
                        <li id="data"><b>Data:</b><span>15/07/2009</span></li>
                        <!--                        <li id="encontro"><b>Encontro:</b><span>teste</span></li>-->
                        <li id="tema"><b>Tema:</b><span>teste</span></li>
                        <li id="local"><b>Local:</b><span>teste</span></li>
                        <li id="endereco"><b>Endereço:</b><span>teste</span></li>
                        <li id="cidade"><b>Cidade:</b><span>teste</span></li>
                        <li id="horario"><b>Horário:</b><span>teste</span></li>
                        <li id="valor"><b>Inscrição:</b><span>teste</span></li>
                        <li id="observacao"><b>Obsevação:</b><span>teste</span></li>
                    </ul>
                    <div>
                        <div id="calendario">
                            <header>
                                <div id="mes">Agosto /</div>
                                <div id="ano">2017</div>
                            </header>
                            <div id="nome-semana"> <span>Dom</span> <span>Seg</span> <span>Ter</span> <span>Qua</span> <span>Qui</span> <span>Sex</span> <span>Sab</span> </div>
                            <article>
                                <!-- gerar dias -->
                                <? for ($i=0; $i<6; $i++) { ?>
                                    <div class="semana"> <span>0</span> <span>0</span> <span>0</span> <span>0</span> <span>0</span> <span>0</span> <span>0</span> </div>
                                    <? } ?>
                            </article>
                        </div>
                        <p>Para fazer sua inscrição online clique aqui:</p>
                        <button class="botao" id="abrir-pagamento">PAGAR COM <img src="../img/pagseguro.png"></button>
                    </div>
                </div>
                
                <div id="erro-pag"> 
                    <p>Caso você não consiga efetivar o pagamento click no botão ao lado, Seremos informados e entraremos em contato por e-mail ou por whatsapp.</p>
                     
                    <button class="botao vermelho">NÃO CONSEGUI PAGAR</button>
                </div>
                
                <div id="quero-ir">
                    <p>Caso prefira pagar a inscrição no local nos comunique  aqui, precisamos saber o numero de pessoas.</p>
                    
                    <button class="botao amarelo">PREFIRO PAGAR NO LOCAL</button>
                </div>
                
                <div id="mensagem">
                    <p>Este é o local onde será o encontro. Chegue pelo menos 30 minutos antes do inicio para confirmar sua presença.</p> <img src="" id="imagem1"> <img src="" id="imagem2"> </div>
            </div>
        </section>
        
        <section id="fundo-pagar"> <img src="../img/fechar.png" class="fechar">
            <div>
                <div id="forma-pagamento">
                    <h3>Forma de Pagamento</h3>
                    <input type="radio" id="cartao" name="pagamento">
                    <label for="cartao">Cartão</label>
                    <input type="radio" id="boleto" name="pagamento">
                    <label for="boleto">Boleto</label>
                    <div id="tipo"><img src="../img/cartao-credito.png"></div> <img src="../img/pagseguro.png" id="logo-pagseguro"> </div>
                <section id="pagar-cartao">
                    <h4>Pagar com Cartão</h4>
                    <div class="container">
                        <form>
                            <input type="hidden" name="brand">
                            <input type="hidden" name="token">
                            <input type="hidden" name="senderHash">
                            <input type="hidden" name="valor" value='0'>
                            <input type="hidden" name="descricao" value='SEM DESCRIÇÃO'>
                            <input type="hidden" name="encontro" value='0'>
                            <div class="input">
                                <label for="titular">Nome do Titular</label>
                                <input type="text" id="titular" placeholder="Titular do Cartão" data-id="nome" name="titular"> </div>
                            <div class="input">
                                <label for="numero">Número do Cartão</label>
                                <input type="text" id="numero" placeholder="Número do Cartão" data-id="numero-cartao" name="numero" data-mask="0000-0000-0000-0000"> </div>
                            <div class="input metade">
                                <label for="validade">Validade</label>
                                <input type="text" id="validade" placeholder="xx/xxxx" data-id="validade" name="validade" data-mask="00/0000"> </div>
                            <div class="input metade">
                                <label for="cvc">Código CVC</label>
                                <input type="text" id="cvc" placeholder="Código CVC" data-id="cvc" name="cvc" data-mask="000"> </div>
                            <button type="button">Realizar Pagamento</button>
                            <div id="confirmar"> <img src='../img/fechar.png'>
                                <div>
                                    <h3>Titular do Cartão</h3>
                                    <div class="input">
                                        <label for="cpf">CPF</label>
                                        <input type="text" id="cpf" placeholder="CPF" name="cpf" data-mask="000.000.000-00" required> </div>
                                    <div class="input metade">
                                        <label for="aniversario">Data de Nascimento</label>
                                        <input type="text" id="aniversario" placeholder="00/00/0000" data-mask="00/00/0000" name='aniversario' required> </div>
                                    <div class="input metade">
                                        <label for="telefone">Telefone</label>
                                        <input type="text" id="telefone" placeholder="Telefone" name="telefone" data-mask="(00) 00009-0000" value="<? echo $usuario->celular; ?>" required> </div>
                                    <button>Finalizar</button>
                                </div>
                            </div>
                        </form>
                        <div id="cartao-container">
                            <div id="cartao-credito">
                                <div class="cartao frente">
                                    <div id="chip"><span></span></div>
                                    <h3 id="brand"></h3>
                                    <!--                                        <div id="bandeira"><img src="../img/pagamento/bandeira-visa.png"></div>-->
                                    <p class="numero-cartao">0000 0000 0000 0000</p>
                                    <p class="nome">NOME DO TITULAR</p>
                                    <p class="validade">07/2019</p>
                                </div>
                                <div class="cartao verso">
                                    <div class="fita"></div>
                                    <div class="faixa"></div>
                                    <p class="cvc">373</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="pagar-boleto">
                    <h4>Pagar com Boleto</h4>
                    <div class="container">
                        <form>
                            <input type="hidden" name="senderHash">
                            <input type="hidden" name="valor" value='0'>
                            <input type="hidden" name="descricao" value='SEM DESCRICAO'>
                            <input type="hidden" name="encontro" value='0'>
                            <div class="input">
                                <label for="cpf">CPF</label>
                                <input type="text" id="cpf" placeholder="CPF" name="cpf" data-mask="000.000.000-00" required> </div>
                            <button>Emitir Boleto</button>
                        </form>
                    </div>
                </section>
            </div>
        </section>
        
        <section id="comprovante"> <img src="../img/fechar.png" class="fechar"> <img src="../img/loading.gif" class='loading'>
            <div>
                <h2>Pagamento de R$ <span class='valor'>0,00</span> realizado com sucesso!</h2>
                <h5>Detalhes da transação:</h5>
                <p class="descricao">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                <h3>
                    <div>Valor no Cartão:</div>
                    <div>R$ <span class="valor">0,00</span></div>
                </h3>
                <p class="codigo">761FE12B-AE8D-4BD9-B431-6CF7AC7A8EDB</p>
            </div>
        </section>
        
        <section id="cancelar"> <img src="../img/fechar.png" class="fechar">
            <div>
                <h3>Motivo do Cancelamento</h3>
                <p>--MENSAGEM--</p>
            </div>
        </section>
        <? 
        include("../html/rodape.html");
        include("../html/popup.html");
        include("../html/confirmacao.html");
        ?>
    </body>
    <script type="text/javascript" src="https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
    <!--    <script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>-->
    <script type="text/javascript">
        var encontros = <? echo json_encode($encontros); ?>;
        var pagamentos = <? echo json_encode($pagamentos); ?>;
        var inscricoes = <? echo json_encode($inscricoes); ?>;
        var usuario = <? echo json_encode($usuario->toArray()); ?>;
        var id_sessao = '<? echo $sessionCode;?>';
        var pagseguro = true;
    </script>
    <script src="../js/jquery.mask.js"></script>
    <script src="../js/menu.js"></script>
    <script src="../js/agenda.js?<? echo time(); ?>"></script>

    </html>