<?
session_start();
require_once (dirname(__DIR__)."/php/sessao_usuario.php");
require_once (dirname(__DIR__)."/php/conexao.php");
require_once (dirname(__DIR__)."/php/classes/usuario.class.php");
require_once (dirname(__DIR__)."/php/classes/postagem.class.php");
// verificarSessao();
date_default_timezone_set("America/Sao_Paulo");

if (isset($_SESSION['usuario'])) $usuario = unserialize($_SESSION['usuario']);
else $usuario = false;

if (count($_GET)==0) {
    if ($usuario==false) {
    	$area1 = "Area de Atuação";
        $area2 = "";
        $area3 = "";
    } else {
        $area1 = 'Area de Atuação';
        $area2 = $usuario->area_atuacao;
        $area3 = "";
    }
} else {
    $area1 = $_GET['area1'];
    $area2 = $_GET['area2'];
    $area3 = $_GET['area3'];
}

if ($area1=="") {
    $_SESSION['info_msg'] = "Atualize suas informações profissionais para acessar a comunidade!";
    session_write_close();
    header('location: /paginas/conta');
}

// $atua_comoString = "";
// switch ($area3) {
//     case 1:
//         $area3 = "Estudante";
//         break;
//     case 2:
//         $area3 = "Profissional";
//         break;
//     case 3:
//         $area3 = "Técnico";
//         break;
//     case 4:
//         $area3 = "Superior";
//         break;
// }

// $notificacoes = DBselect('notificacao_postagem n inner join usuario u on n.id_usuario = u.id', "where n.id_postagem in (select id from postagem where id_usuario = {$usuario->id})", 'n.*, u.nome as usuario. p.area1, p.area2, p.area3');

$postagem = new Postagem();
$postagens = $postagem->carregar($area1, $area2, $area3);
$respostas = $postagens['respostas'];
$postagens = $postagens['postagens'];

$usuarios = DBselect('usuario', 'where estado_conta=1 order by RAND() limit 12', 'nome, foto_perfil, id, estado_conta');

if ($usuario!=false) {
    // MENSAGENS QUE ME ENVIARAM
    $mensagens_recebidas = DBselect('usuario_mensagem m INNER JOIN usuario u ON m.id_de = u.id', "where id_para = {$usuario->id} order by time DESC", "m.*, u.foto_perfil, u.id as id_usuario, u.nome");
    // MENSAGENS QUE EU FIZ PARA OUTROS USUÁRIOS
    $mensagens_enviadas = DBselect('usuario_mensagem m', "where id_de = {$usuario->id} and m.id in (select id_referencia from usuario_mensagem where id_para = {$usuario->id})", "m.*");
    // RESPOSTAS QUE DEI PARA MINHAS MENSAGENS
    $mensagens_respostas = DBselect('usuario_mensagem m', "where id_de = {$usuario->id} and m.id_referencia in (select id from usuario_mensagem where id_para = {$usuario->id})", "m.*");

    $notificacoes = DBselect('notificacao_postagem n inner join usuario u on n.id_usuario = u.id INNER JOIN postagem p ON n.id_postagem = p.id', "where n.id_postagem in (select id from postagem where id_usuario = {$usuario->id})", 'n.*, u.nome as usuario, p.area1, p.area2, p.area3');
} else {
    $mensagens_recebidas = [];
    // MENSAGENS QUE EU FIZ PARA OUTROS USUÁRIOS
    $mensagens_enviadas = [];
    // RESPOSTAS QUE DEI PARA MINHAS MENSAGENS
    $mensagens_respostas = [];
}

$selec = 'comunidades';

$qtd = DBselect('usuario', 'where estado_conta=1', 'count(id) as qtd')[0]['qtd'];

?> 
<!DOCTYPE HTML>
<html>
    <head>
        <title>Eu Fui Chamado por Deus - Comunidades</title>
        <base href="https://eufuichamadopordeus.com.br/paginas/comunidades">
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="itsoftwares">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <link rel="shortcut icon" type="image/png" href="img/logo.png"/>
        <link rel="stylesheet" href="../css/geral.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../css/comunidades.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="../cssmobile/geral.css" media="(max-width: 999px)">
        <link rel="stylesheet" href="../cssmobile/comunidades.css" media="(max-width: 999px)">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </head>
     
    <body>
        <? include('../html/menu.html'); ?>
       
        <section id="inicio">
            <div class="fundo"></div>
           
            <div id="nome">
                <h1>Comunidades</h1>
            </div>

            <nav>
                <div class="input">
					<select name="area1">
						<option value="Area de Atuação">Area de Atuação</option>
						<option value="Area de Atuação">Igreja</option>
						<option value="Area de Atuação">Brasil</option>
					</select>
                    <!-- <input type="text" value='Área de Atuação' disabled=""> -->
					<? if ($area1=="Area de Atuação") { ?>
                    <select name="area2" id="area_atuacao">
                        <option value="" selected=""></option>
                        <option value="">Ensino Médio</option>
                        <optgroup label="Administração, negócios e serviços">
                            <option value=''>Administração</option>
                            <option value=''>Administração Pública</option>
                            <option value=''>Agronegócios e Agropecuária</option>
                            <option value=''>Ciências Aeronáuticas</option>
                            <option value=''>Ciências Atuariais</option>
                            <option value=''>Ciências Contábeis</option>
                            <option value=''>Ciências Econômicas</option>
                            <option value=''>Comércio Exterior</option>
                            <option value=''>Defesa e Gestão Estratégica Internacional</option>
                            <option value=''>Gastronomia</option>
                            <option value=''>Gestão Comercial</option>
                            <option value=''>Gestão de Recursos Humanos</option>
                            <option value=''>Gestão de Segurança Privada</option>
                            <option value=''>Gestão de Seguros</option>
                            <option value=''>Gestão de Turismo</option>
                            <option value=''>Gestão Financeira</option>
                            <option value=''>Gestão Pública</option>
                            <option value=''>Hotelaria</option>
                            <option value=''>Logística</option>
                            <option value=''>Marketing</option>
                            <option value=''>Negócios Imobiliários</option>
                            <option value=''>Pilotagem Profissional de Aeronaves</option>
                            <option value=''>Processos Gerenciais</option>
                            <option value=''>Segurança Pública</option>
                            <option value=''>Turismo</option>
                        </optgroup>
                        <optgroup label='Artes e Design'>
                            <option value=''>Animação</option>
                            <option value=''>Arquitetura e Urbanismo</option>
                            <option value=''>Artes Visuais</option>
                            <option value=''>Comunicação das Artes do Corpo</option>
                            <option value=''>Conservação e Restauro</option>
                            <option value=''>Dança</option>
                            <option value=''>Design</option>
                            <option value=''>Design de Games</option>
                            <option value=''>Design de Interiores</option>
                            <option value=''>Design de Moda</option>
                            <option value=''>Fotografia</option>
                            <option value=''>História da Arte</option>
                            <option value=''>Jogos Digitais</option>
                            <option value=''>Luteria</option>
                            <option value=''>Música</option>
                            <option value=''>Produção Cênica</option>
                            <option value=''>Produção Fonográfica</option>
                            <option value=''>Teatro</option>
                        </optgroup>
                        <optgroup label='Ciências Biológicas e da Terra'>
                            <option value=''>Agroecologia</option>
                            <option value=''>Agronomia</option>
                            <option value=''>Alimentos</option>
                            <option value=''>Biocombustíveis</option>
                            <option value=''>Biotecnologia</option>
                            <option value=''>Biotecnologia e Bioquímica</option>
                            <option value=''>Ciência e Tecnologia de Alimentos</option>
                            <option value=''>Ciências Agrárias</option>
                            <option value=''>Ciências Biológicas</option>
                            <option value=''>Ciências Naturais e Exatas</option>
                            <option value=''>Ecologia</option>
                            <option value=''>Geofísica</option>
                            <option value=''>Geologia</option>
                            <option value=''>Gestão Ambiental</option>
                            <option value=''>Medicina Veterinária</option>
                            <option value=''>Meteorologia</option>
                            <option value=''>Oceanografia</option>
                            <option value=''>Produção de Bebidas</option>
                            <option value=''>Rochas Ornamentais</option>
                            <option value=''>Zootecnia</option>
                        </optgroup>
                        <optgroup label='Ciências Exatas e Informática'>
                            <option value=''>Análise e Desenvolvimento de Sistemas</option>
                            <option value=''>Astronomia</option>
                            <option value=''>Banco de Dados</option>
                            <option value=''>Ciência da Computação</option>
                            <option value=''>Ciência e Tecnologia</option>
                            <option value=''>Computação</option>
                            <option value=''>Estatística</option>
                            <option value=''>Física</option>
                            <option value=''>Gestão da Tecnologia da Informação</option>
                            <option value=''>Matemática</option>
                            <option value=''>Nanotecnologia</option>
                            <option value=''>Química</option>
                            <option value=''>Redes de Computadores</option>
                            <option value=''>Segurança da Informação</option>
                            <option value=''>Sistemas de Informação</option>
                            <option value=''>Sistemas para Internet</option>
                        </optgroup>
                        <optgroup label='Ciências Sociais e Humanas'>
                            <option value=''>Arqueologia</option>
                            <option value=''>Ciências Humanas</option>
                            <option value=''>Ciências Sociais</option>
                            <option value=''>Cooperativismo</option>
                            <option value=''>Direito</option>
                            <option value=''>Escrita Criativa</option>
                            <option value=''>Filosofia</option>
                            <option value=''>Geografia</option>
                            <option value=''>Gestão de Cooperativas</option>
                            <option value=''>História</option>
                            <option value=''>Letras</option>
                            <option value=''>Libras</option>
                            <option value=''>Linguística</option>
                            <option value=''>Museologia</option>
                            <option value=''>Pedagogia</option>
                            <option value=''>Psicopedagogia</option>
                            <option value=''>Relações Internacionais</option>
                            <option value=''>Serviço Social</option>
                            <option value=''>Serviços Judiciários e Notariais</option>
                            <option value=''>Teologia</option>
                            <option value=''>Tradutor e Intérprete</option>
                        </optgroup>
                        <optgroup label='Comunicação e Informação'>
                            <option value=''>Arquivologia</option>
                            <option value=''>Biblioteconomia</option>
                            <option value=''>Cinema e Audiovisual</option>
                            <option value=''>Comunicação em Mídias Digitais</option>
                            <option value=''>Eventos</option>
                            <option value=''>Gestão da Informação</option>
                            <option value=''>Jornalismo</option>
                            <option value=''>Produção Audiovisual</option>
                            <option value=''>Produção Cultural</option>
                            <option value=''>Produção Editorial</option>
                            <option value=''>Produção Multimídia</option>
                            <option value=''>Produção Publicitária</option>
                            <option value=''>Publicidade e Propaganda</option>
                            <option value=''>Rádio, TV e Internet</option>
                            <option value=''>Relações Públicas</option>
                            <option value=''>Secretariado</option>
                            <option value=''>Secretariado Executivo</option>
                            <option value=''>Tecnologia da Informação</option>
                        </optgroup>
                        <optgroup label='Engenharia e Produção'>
                            <option value=''>Agrimensura</option>
                            <option value=''>Aquicultura</option>
                            <option value=''>Automação Industrial</option>
                            <option value=''>Construção Civil</option>
                            <option value=''>Construção Naval</option>
                            <option value=''>Eletrônica Industrial</option>
                            <option value=''>Eletrotécnica Industrial</option>
                            <option value=''>Energias Renováveis</option>
                            <option value=''>Engenharia Acústica</option>
                            <option value=''>Engenharia Aeronáutica</option>
                            <option value=''>Engenharia Agrícola</option>
                            <option value=''>Engenharia Ambiental e Sanitária</option>
                            <option value=''>Engenharia Biomédica</option>
                            <option value=''>Engenharia Bioquímica, </option>
                            <option value=''>Engenharia Civil</option>
                            <option value=''>Engenharia da Computação</option>
                            <option value=''>Engenharia de Alimentos</option>
                            <option value=''>Engenharia de Biossistemas</option>
                            <option value=''>Engenharia de Controle e Automação</option>
                            <option value=''>Engenharia de Materiais</option>
                            <option value=''>Engenharia de Minas</option>
                            <option value=''>Engenharia de Petróleo</option>
                            <option value=''>Engenharia de Produção</option>
                            <option value=''>Engenharia de Segurança no Trabalho</option>
                            <option value=''>Engenharia de Sistemas</option>
                            <option value=''>Engenharia de Software</option>
                            <option value=''>Engenharia de Telecomunicações</option>
                            <option value=''>Engenharia de Transporte e da Mobilidade</option>
                            <option value=''>Engenharia Elétrica</option>
                            <option value=''>Engenharia Eletrônica</option>
                            <option value=''>Engenharia Física</option>
                            <option value=''>Engenharia Florestal</option>
                            <option value=''>Engenharia Hídrica</option>
                            <option value=''>Engenharia Industrial Madeireira</option>
                            <option value=''>Engenharia Mecânica</option>
                            <option value=''>Engenharia Mecatrônica</option>
                            <option value=''>Engenharia Metalúrgica</option>
                            <option value=''>Engenharia Naval</option>
                            <option value=''>Engenharia Nuclear</option>
                            <option value=''>Engenharia Química</option>
                            <option value=''>Engenharia Têxtil</option>
                            <option value=''>Fabricação Mecânica</option>
                            <option value=''>Geoprocessamento</option>
                            <option value=''>Gestão da Produção Industrial</option>
                            <option value=''>Gestão da Qualidade</option>
                            <option value=''>Irrigação e Drenagem</option>
                            <option value=''>Manutenção de Aeronaves</option>
                            <option value=''>Manutenção Industrial (T/L)</option>
                            <option value=''>Materiais</option>
                            <option value=''>Mecatrônica Industrial</option>
                            <option value=''>Mineração</option>
                            <option value=''>Papel e Celulose</option>
                            <option value=''>Petróleo e Gás</option>
                            <option value=''>Processos Metalúrgicos</option>
                            <option value=''>Processos Químicos</option>
                            <option value=''>Produção Têxtil</option>
                            <option value=''>Saneamento Ambiental</option>
                            <option value=''>Segurança no Trabalho</option>
                            <option value=''>Silvicultura</option>
                            <option value=''>Sistemas Biomédicos</option>
                            <option value=''>Sistemas de Telecomunicações</option>
                            <option value=''>Sistemas Elétricos</option>
                            <option value=''>Sistemas Embarcados</option>
                            <option value=''>Transporte</option>
                        </optgroup>
                        <optgroup label='Saúde e Bem-Estar'>
                            <option value=''>Biomedicina</option>
                            <option value=''>Educação Física</option>
                            <option value=''>Enfermagem</option>
                            <option value=''>Esporte</option>
                            <option value=''>Estética e Cosmética</option>
                            <option value=''>Farmácia</option>
                            <option value=''>Fisioterapia</option>
                            <option value=''>Fonoaudiologia</option>
                            <option value=''>Gerontologia</option>
                            <option value=''>Gestão Desportiva e de Lazer</option>
                            <option value=''>Gestão em Saúde</option>
                            <option value=''>Gestão Hospitalar</option>
                            <option value=''>Medicina</option>
                            <option value=''>Musicoterapia</option>
                            <option value=''>Naturologia</option>
                            <option value=''>Nutrição</option>
                            <option value=''>Obstetrícia</option>
                            <option value=''>Odontologia</option>
                            <option value=''>Oftálmica</option>
                            <option value=''>Optometria</option>
                            <option value=''>Psicologia</option>
                            <option value=''>Quiropraxia</option>
                            <option value=''>Radiologia</option>
                            <option value=''>Saúde Coletiva</option>
                            <option value=''>Terapia Ocupacional</option>
                        </optgroup>
                        <optgroup label='Outras'>
                            <option value=''>Artes</option>
                            <option value=''>Biossistemas</option>
                            <option value=''>Ciência da Terra</option>
                            <option value=''>Ciência e Economia</option>
                            <option value=''>Ciência e Tecnologia</option>
                            <option value=''>Ciência e Tecnologia das Águas/do Mar</option>
                            <option value=''>Ciências Agrárias</option>
                            <option value=''>Ciências da Natureza e suas Tecnologias</option>
                            <option value=''>Cultura, Linguagens e Tecnologias Aplicadas</option>
                            <option value=''>Energia e Sustentabilidade</option>
                            <option value=''>Linguagens e Códigos e suas Tecnologias</option>
                        </optgroup>
                    </select>
                    <? if (false and $area2!="") { ?>
                    <select name="area3" id="atua_como">
                    	<option value="" selected=""></option>
                        <option value="Estudante">Estudante</option>
                        <option value="Profissional">Profissional</option>
                        <option value="Técnico">Técnico</option>
                        <option value="Superior">Superior</option>
                    </select>
                	<? } ?>
					<? } else if ($area1=="Igreja") { ?>
                    <select id="igreja"  name="area2">
                        <option value="" selected=""></option>
                        <option value='1'>Adventista</option>
                        <option value='2'>Assembleia de Deus</option>
                        <option value='3'>Batista</option>
                        <option value='4'>Bola de Neve</option>
                        <option value='5'>Catedral da Benção</option>
                        <option value='6'>Católica</option>
                        <option value='7'>Comunidades</option>
                        <option value='8'>Congregação Cristã</option>
                        <option value='9'>Congregacional</option>
                        <option value='10'>Episcopal Carismática</option>
                        <option value='11'>Evangelho Quadrangular</option>
                        <option value='12'>Internacional da Graça de Deus</option>
                        <option value='13'>Luterana</option>
                        <option value='14'>Igreja Maranata</option>
                        <option value='15'>Metodista</option>
                        <option value='16'>Mundial do Poder de Deus</option>
                        <option value='17'>Nova Vida</option>
                        <option value='18'>Pentecostal</option>
                        <option value='19'>Presbiteriana</option>
                        <option value='20'>Renascer em Cristo</option>
                        <option value='21'>Sara Nossa Terra</option>
                        <option value='22'>Universal do Reino de Deus</option>
                        <option value='23'>Outra</option>
                        <option value='24'>Nenhuma</option>
                    </select>
                    <? if (false and $area2!="") { ?>
                    <select id="voce_e" name="area3">
                        <option value="" selected=""></option>
                        <option value="1">Novo Convertido</option>
                        <option value="2">Membro</option>
                        <option value="3">Cantor (a)</option>
                        <option value="4">Musico (a)</option>
                        <option value="5">Ministra Louvor</option>
                        <option value="6">Ministra Estudos</option>
                        <option value="7">Pregador (a)</option>
                        <option value="8">Outro (a)</option>
                    </select>
                    <? } ?>
					<? } else { ?>
                    <select id="estado" name='area2'>
                        <option value="" selected="">Estado</option>
                    </select>
                    <? //if ($area2!="") { ?>
                    <select id="cidade" name='area3'>
                        <option value="" selected="">Cidade</option>
                    </select>
                    <? //} ?>
                	<? } ?>
                </div>
            </nav>
        </section>

        <section id="comunidades">
            <aside>
                <h2 class="titulo"><span>Participantes <? echo number_format($qtd, 0, ".", ".") ?></span></h2>

                <ul>
                    <?
                    foreach ($usuarios as $u) { 
                        $u = new Usuario($u);
                    ?>
                    <li>
                        <a href="/paginas/participante/<? echo $u->id ?>" title="<? echo $u->nome ?>" class='criador' data-id='<? echo $u->id ?>'>
                            <? if ($u->foto_perfil == null or $u->foto_perfil == "") { ?>
                            <img src="../../img/foto_perfil.png">
                            <? } else { ?>
                            <img src="../../servidor/thumbs-usuarios/<? echo $u->foto_perfil ?>">
                            <? } ?>
                        </a>
                    </li>
                    <? } ?>
                </ul>
            </aside>

            <article id="postagens">
            	<?
            	$titulo = $area1;
            	if ($area2!="") $titulo .= " - ".$area2;
            	if ($area3!="") $titulo .= " - ".$area3;
            	?>
                <h2 class="titulo"><span><? echo $titulo; ?></span></h2>

                <h4 id="qtd">Part. desta comunidade <b>1.234</b></h4>

                <ul>
                    <?
                    if (count($postagens)==0) echo "<p class='aviso'>Nenhum post nesta sessão!</p>";

                    foreach ($postagens as $key => $p) { 
                        $p = new Postagem($p);
                    ?>
                    <li class="postagem fechada" data-index='<? echo $key; ?>'>
                        <a class="criador" href="/paginas/participante/<? echo $p->id_usuario ?>" data-id='<? echo $p->id_usuario ?>'>
                            <? if ($p->foto_perfil == null or $p->foto_perfil == "") { ?>
                            <img src="../../img/foto_perfil.png">
                            <? } else { ?>
                            <img src="../../servidor/thumbs-usuarios/<? echo $p->foto_perfil ?>">
                            <? } ?>
                            <div>
                                <h4><? echo explode(' ', $p->nome)[0]; ?></h4>
                                <p><? echo $p->cidade; ?>/<? echo $p->estado; ?></p>
                            </div>
                        </a>

                        <div class="resumo">
                            <h3><? echo $p->titulo ?> <span>(<? echo $p->respostas ?> Respostas)</span></h3>
                            <p><? echo strlen($p->texto)>300?substr($p->texto, 0, 300)."...":$p->texto; ?></p>
                        </div>

                        <? if ($usuario!=false and $p->id_usuario==$usuario->id) { ?>
                        <div class="menu">
                            <img src="../../img/menu2.png">

                            <div>
                                <span class="editar">Editar</span>
                                <span class="excluir">Excluir</span>
                            </div>
                        </div>
                        <? 
                        }
                        ?>
                        <ul>
                        	<? if ($usuario!=false) { ?>
                            <form class="resposta">
                                <input type="hidden" name="id_postagem" value="<? echo $p->id; ?>">
                                <div class="input">
                                    <textarea name="texto" placeholder="Escreva algo" required></textarea>
                                </div>
                                <button class="botao">Responder</button>
                            </form>
                        	<? } ?>
                        </ul>
                    </li>
                    <? } ?>
                </ul>
            </article>

            <article id="participante">
            </article>

            <article id="mensagens">
                <h2 class="titulo"><span>Mensagens Recebidas</span></h2>
                <button class="botao voltar">Voltar</button>
                <div class="clear"></div>
                <ul>
                    <? foreach ($mensagens_recebidas as $key => $m) { ?>
                    <li class="mensagem" data-id="<? echo $m['id'] ?>" data-referencia="<? echo $m['id_referencia'] ?>">
                        <a class="fez criador" data-id="<? echo $m['id_usuario']; ?>">
                            <? if ($m['foto_perfil'] == null or $m['foto_perfil'] == null) { ?>
                            <img src="../../img/foto_perfil.png">
                            <? } else { ?>
                            <img src="../../servidor/thumbs-usuarios/<? echo $m['foto_perfil'] ?>">
                            <? } ?>
                            <div>
                                <h4><? echo $m['id_referencia']==0?"Mensagem de":"Resposta de" ?> <? echo $m['nome']; ?></h4>
                                <p><? echo $m['texto']; ?></p>
                            </div>
                        </a>
                        <!-- <div class="eu">
                            <img src="../../img/foto_perfil.png">
                            <div>
                                <h4>NOME DO PARTICIPANTE</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                consequat.</p>
                            </div>
                        </div> -->
                        <form>
                            <div class="input">
                                <input type="hidden" name="id_referencia" value="<? echo $m['id']; ?>">
                                <input type="hidden" name="id_para" value="<? echo $m['id_usuario']; ?>">
                                <input type="text" name="texto" required placeholder="Responda essa mensagem">
                            </div>
                            <button class="botao">Responder</button>
                            <div class="clear"></div>
                        </form>
                    </li>
                    <? } ?>
                </ul>
            </article>
        </section>

        <section class="fundo" id="nova-postagem">
            <img class="fechar" src="../../img/fechar.png">

            <div>
                <h3>Nova Postagem</h3>

                <form>
                    <div class="input">
                        <label>Título</label>
                        <input type="text" name="titulo" required placeholder="Informe um título">
                    </div>

                    <div class="input">
                        <label>Texto</label>
                        <textarea required name="texto" placeholder="Escreva sobre o tema"></textarea>
                    </div>

                    <button class="botao">Salvar</button>
                </form>
            </div>
        </section>

        <section id="notificacoes">
        	<ul>
        		<? 
        		if (count($notificacoes)==0) echo "<p class='aviso'>Nenhuma notificação!</p>";
        		foreach ($notificacoes as $noti) {
        		?>
				<li class="notificacao">
					<? 
					// $link = str_replace(" - ", "/", $titulo); 
					$link = $noti['area1'];
	            	if ($noti['area2']!="") $link .= "/".$noti['area2'];
	            	if ($noti['area3']!="") $link .= "/".$noti['area3'];
					?>
					<a href="/paginas/comunidades/<? echo $link; ?>">
						<p>Você tem uma nova resposta de <? echo explode(" ", $noti['usuario'])[0]; ?> em sua postagem!</p>
						<span class="time"></span>
					</a>
				</li>
        		<?
        		} ?>
        	</ul>
        </section>

        <?
        if ($usuario!=false) {
            $qtd = count($mensagens_recebidas)-count($mensagens_respostas)-count($mensagens_enviadas);
            $qtd = $qtd==0?"":"({$qtd})";

            $qtd2 = count($notificacoes);
            $qtd2 = $qtd2==0?"":"({$qtd2})";
        ?>
        <button class="botao redondo" id="nova"><img src="../../img/add.png"></button>
        <button class="botao redondo" id="inbox"><img src="../../img/email.png"> <? echo $qtd; ?></button>
        <button class="botao redondo" id="icon-notificacao"><img src="../../img/notification.png"> <? echo $qtd2; ?></button>
        <?
        }
        include("../html/rodape.html");
        include("../html/popup.html");
        include("../html/confirmacao.html");

        if ($usuario!=false) $usuario = json_encode($usuario->toArray());
        else $usuario = 0;
        ?>
    </body> 
    <script type="text/javascript">
        var postagens = <? echo json_encode($postagens); ?>;
        var respostas = <? echo json_encode($respostas); ?>;
        var mensagens_recebidas = <? echo json_encode($mensagens_recebidas); ?>;
        var mensagens_enviadas = <? echo json_encode($mensagens_enviadas); ?>;
        var mensagens_respostas = <? echo json_encode($mensagens_respostas); ?>;
        var usuario = <? echo $usuario; ?>;
        var area1 = '<? echo $area1; ?>';
        var area2 = '<? echo $area2; ?>';
        var area3 = '<? echo $area3; ?>';
    </script>
    <script src="../js/jquery.mask.js"></script>
    <script src="../js/cidade-estado.js"></script>
    <script src="../js/menu.js"></script>
    <script src="../js/comunidades.js?<? echo time() ?>"></script>
</html>