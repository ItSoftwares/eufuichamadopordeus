<?
require_once (dirname(__DIR__)."/php/classes/usuario.class.php");
require_once (dirname(__DIR__)."/php/conexao.php");
session_start();
$teste = isset($_SESSION['usuario']);
session_write_close();

$usuario = new usuario();
$usuario->fromArray($usuario->carregar($_GET['id'])[0]);

$visibilidade = $usuario->visibilidade==null?true:json_decode($usuario->visibilidade, true);
// var_dump($visibilidade);
// echo "<pre>"; var_dump($visibilidade); exit;
?>
<button class="botao voltar">Voltar</button>
<section id="perfil">
    <div class="card">
        <div class="entalhe"></div>
        <? if ($usuario->foto_perfil == null or $usuario->foto_perfil == "") { ?>
        <img src="../../img/foto_perfil.png">
        <? } else { ?>
        <img src="../../servidor/thumbs-usuarios/<? echo $usuario->foto_perfil ?>">
        <? } ?>
        <div class="detalhes">
            <h2><? echo $usuario->nome ?></h2>
            <p><? echo $usuario->cidade."/".$usuario->estado ?></p>
        </div>
    </div>

    <div id="informacoes" class="card">
        <div class="input metade" data-esconder="<? echo $visibilidade===true?1:($visibilidade['celular']?0:1); ?>">
            <label>Celular</label>
            <input type="text" name="celular" disabled>
        </div>
        <div class="input metade" data-esconder="<? echo $visibilidade===true?1:($visibilidade['data_nascimento']?0:1); ?>">
            <label>Data de Nascimento</label>
            <input type="text" name="data_nascimento" disabled>
        </div>
        
        <div class="input metade" data-esconder="<? echo $visibilidade===true?1:($visibilidade['pais']?0:1); ?>">
            <label>Pais</label>
            <input name="pais" disabled>
        </div>
        <div class="input metade" data-esconder="<? echo $visibilidade===true?1:($visibilidade['estado_civil']?0:1); ?>">
            <label>Estado Civil</label>
            <select name="estado_civil" disabled>
                <option value="0"></option>
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
    
        <div class="input" data-esconder="<? echo $visibilidade===true?0:($visibilidade['igrejatemp']?0:1); ?>">
            <label>Igreja</label>
            <select name="igreja" disabled>
                <option value='0'></option>
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
        </div>
        <div class="input" data-esconder="<? echo $visibilidade===true?1:($visibilidade['voce_e']?0:1); ?>">
            <label>Você é</label>
            <select name="voce_e" disabled>
                <option value="0"></option>
                <option value="1">Novo Convertido</option>
                <option value="2">Membro</option>
                <option value="3">Cantor (a)</option>
                <option value="4">Musico (a)</option>
                <option value="5">Ministra Louvor</option>
                <option value="6">Ministra Estudos</option>
                <option value="7">Pregador (a)</option>
                <option value="8">Outro (a)</option>
            </select>
        </div>
        
        <div class="input" data-esconder="<? echo $visibilidade===true?0:($visibilidade['area_atuacao']?0:1); ?>">
            <label>Área de Atuação</label>
            <input type="text" name="area_atuacao" disabled>
        </div>
        <div class="input" data-esconder="<? echo $visibilidade===true?0:($visibilidade['atua_como']?0:1); ?>">
            <label>Atua como</label>
            <select name="atua_como" disabled>
                <option value="0"></option>
                <option value="1">Estudante</option>
                <option value="2">Profissional</option>
                <option value="3">Técnico</option>
                <option value="4">Superior</option>
            </select>
        </div>

        <div class="input" data-esconder="<? echo $visibilidade===true?1:($visibilidade['site_facebook']?0:1); ?>">
            <label>Site ou página do facebook</label>
            <input type="text" name="site_facebook" disabled>
        </div>
    </div>
    <? if ($teste) { ?>
    <form>
        <div class="input">
            <label>Enviar Mensagem</label>
            <input type="text" name="texto" required placeholder="Escreva algo para <? echo explode(" ", $usuario->nome)[0]; ?>">
        </div>
        <button class="botao">Enviar</button>
    </form>
    <? } ?>
</section>

<script type="text/javascript">
    var participante = <? echo json_encode($usuario->toArray()); ?>;

    atualizarParticipante();
</script>