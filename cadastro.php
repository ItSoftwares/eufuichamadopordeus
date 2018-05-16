<?
require "php/conexao.php";
session_start();
$tem = false;
if (isset($_GET['estado']) and $_GET['estado']=="completar") {
    $result = DBselect("usuario", "where id={$_GET['id']}");
    $result = $result[0];
    $tem = true;
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Eu Fui Chamado por Deus - Cadastro</title>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="itsoftwares">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <link rel="shortcut icon" type="image/png" href="img/logo.png"/>
        <link rel="stylesheet" href="css/geral.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="css/cadastro.css" media="(min-width: 1000px)">
        <link rel="stylesheet" href="cssmobile/geral.css" media="(max-width: 999px)">
        <link rel="stylesheet" href="cssmobile/cadastro.css" media="(max-width: 999px)">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </head>
    
    <body>
        <? include('html/menu.html'); ?>
       
       <section id="inicio">
           <div class="fundo"></div>
           
           <div id="nome">
               <h1>Bem Vindo</h1>
           </div>
       </section>
        
        <section id="formulario">
            <h2><span>Crie sua conta para participar do maior projeto de apoio ao jovem cristão do Brasil</span></h2>

            <form id="usuario">
                <h3>Foto para Perfil</h3>
                <div class="sessao" id="foto">
                    <div>
                        <img src="img/foto_perfil.png">
                        <input type="file" name="foto_perfil" id="foto_perfil">
                        <label for="foto_perfil">Mudar Foto</label>
                    </div>
                </div>

                <h3>Informações Pessoais</h3>
                <div class="sessao" id="pessoal">
                    <div class="input">
                        <label>Nome</label>
                        <input type="text" name="nome" placeholder="Nome completo" autofocus required>
                    </div>
                    <div class="input metade">
                        <label>Celular</label>
                        <input type="text" name="celular" placeholder="(00) 00000-0000" required>
                    </div>
                    <div class="input metade">
                        <label>Data de Nascimento</label>
                        <input type="text" name="data_nascimento" placeholder="00/00/0000" required>
                    </div>
                    
                    <div class="input metade">
                        <label>Pais</label>
                        <select name="pais" required>
                        </select>
                    </div>
                    <div class="input metade">
                        <label>Estado</label>
                        <select name="estado" required>
                            <option value="0">Escolha</option>
                        </select>
                        <input type="text" name="estado" placeholder="Nome do estado" disabled required style="display: none">
                    </div>
                    <div class="input metade">
                        <label>Cidade</label>
                        <select name="cidade" required>
                            <option value="0">Escolha</option>
                        </select>
                        <input type="text" name="cidade" placeholder="Nome da cidade" disabled required style="display: none">
                    </div>   
                    <div class="input metade">
                        <label>Estado Civil</label>
                        <select name="estado_civil" required>
                            <option value="0">Escolha</option>
                            <option value="1">Solterio (a)</option>
                            <option value="2">Em um relacionamento sério</option>
                            <option value="3">Noivo (a)</option>
                            <option value="4">Casado (a)</option>
                            <option value="5">Em uma união estável</option>
                            <option value="6">Separado (a)</option>
                            <option value="7">Divorciado (a)</option>
                            <option value="8">Viúvo (a)</option>
                        </select>
                    </div> 
                </div>
    
                <h3>Informações Ministeriais</h3>
                <div class="sessao" id="igreja">
                    <div class="input">
                        <label>Igreja</label>
                        <select name="igreja" required>
                            <option value='0'>Escolha</option>
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
                            <option value='14'>Maranata</option>
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
                    </div>
                    <div class="input">
                        <label>Você é</label>
                        <select name="voce_e">
                            <option value="0">Escolha</option>
                            <option value="1">Novo Converso</option>
                            <option value="2">Membro</option>
                            <option value="3">Cantor (a)</option>
                            <option value="4">Musico (a)</option>
                            <option value="5">Ministra Louvor</option>
                            <option value="6">Ministra Estudos</option>
                            <option value="7">Pregador (a)</option>
                        </select>
                    </div>
                </div>

                <h3>Informações Profissionais</h3>
                <div class="sessao" id="profissional">
                    <div class="input">
                        <label>Área de Atuação</label>
                        <select name="area_atuacao">
                            <option value="0" selected="">Escolha</option>
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
                    </div>

                    <div class="input">
                        <label>Atua como</label>
                        <select name="atua_como">
                            <option value="0">Escolha</option>
                            <option value="1">Estudante</option>
                            <option value="2">Profissional</option>
                            <option value="3">Técnico</option>
                            <option value="4">Superior</option>
                        </select>
                    </div>

                    <div class="input">
                        <label>Site ou página do facebook</label>
                        <input type="text" name="site_facebook" placeholder="Cole aqui o link">
                    </div>
                </div>

                <h3>Informações de Acesso</h3>
                <div class="sessao" id="acesso">
                    <div class="input">
                        <label>Login <span>Use seu Email</span></label>
                        <input type="email" name="email" placeholder="Digite seu email" required value="<? echo isset($result)?$result['email']:""; ?>">
                    </div>
                    <div class="input metade">
                        <label>Senha</label>
                        <input type="password" name="senha" placeholder="Digite uma Senha" maxlength="20" minlength="8" required>
                    </div>
                    <div class="input metade">
                        <label>Repetir Senha</label>
                        <input type="password" id="repetir_senha" placeholder="Repita a Senha" maxlength="20" minlength="8" required>
                    </div>
                    
                    <!-- <div class="input">
                        <label>Escolaridade</label>
                        <select name="escolaridade" >
                            <option value="0">Escolha</option>
                            <option value="1">1º Grau</option>
                            <option value="2">2º Grau</option>
                            <option value="3">Superior Incompleto</option>
                            <option value="4">Superior Completo</option>
                            <option value="5">Pós Graduação</option>
                            <option value="6">Mestrado</option>
                            <option value="7">Doutorado</option>
                        </select>
                    </div> -->
                    
                    <div class="input normal">
                        <label for="concordo">Eu concordo com os termos de participação</label>
                        <input type="checkbox" id="concordo">
                    </div>
                    <button class="botao">Salvar</button>
                </div>
<!--                <div class="clear"></div>-->
            </form>
        </section>
        
        <section id="termos" class="fundo">
            <img src="img/fechar.png" class="fechar">
            
            <div>
                <h3>Termos de Participação no Projeto Eu Fui Chamado por Deus</h3>
                
                <p>Todos os encontros serão fotografados ou filmados, essas fotos e filmes serão usados para postagens no site, bem como nas redes sociais Facebook e Instagram. Os participantes dos Encontros frequentemente poderão sair nas fotos e nos filmes, e ao concordarem com os termos cederão o direito de imagem para que essas fotos e filmes sejam usados nos meios eletrônicos citados acima, não podendo com isso requerer a retirada de sua imagem, bem como valor pelo uso de sua imagem.</p>
                <p>O Eu Fui Chamado por Deus se compromete a não postar fotos e filmes em que hajam participantes dos Encontros em situações desagradáveis ou que causem constrangimento a pessoa fotografada ou filmada.</p><br>

                <p>Eu, agora participante do Projeto Eu Fui Chamado por Deus, concordo que li os termos de participação e autorizo o uso da minha imagem para fins de divulgação e publicidade do Projeto nos meios citados acima.</p><br>

                <p>O Direito aplicável</p>
                
                <p>Os direitos autorais são regidos, no Brasil, pela Lei federal n. 9.610, de 19 de fevereiro de 1998. Às cessões de uso de imagem, também se aplica o Código Civil (Lei federal n. 10.406, de 10 de janeiro de 2002), especialmente seus dispositivos sobre os Direitos de Personalidade.</p>
            </div>
        </section>
        
        <footer>
            <img src="img/sitelook.png">
            Você está em um site seguro.
        </footer>
        
        <? 
//        include("html/rodape.html"); 
        include("html/popup.html"); 
        ?>
    </body> 
    <script type="text/javascript">
        var estado = <? echo $tem?$result['id']:0; ?>;
    </script>
    <script src="js/jquery.mask.js"></script>
    <script src="js/cidade-estado.js?<? echo time(); ?>"></script>
    <script src="js/pais.js?<? echo time(); ?>"></script>
    <script src="js/menu.js?<? echo time(); ?>"></script>
    <script src="js/cadastro.js?<? echo time(); ?>"></script>
</html>