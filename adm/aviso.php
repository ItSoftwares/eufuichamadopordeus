<head>
    <link rel="stylesheet" href="https://<? echo $_SERVER['SERVER_NAME']; ?>/css/aviso.css" media="(min-width: 1000px)">
    <link rel="stylesheet" href="https://<? echo $_SERVER['SERVER_NAME']; ?>/cssmobile/aviso.css" media="(max-width: 999px)">
</head>
<?
include_once("../php/conexao.php");
$aviso = DBselect("aviso")[0];
$texto = ($aviso['ativo']==1)?"Desativar":"Ativar";
$class = ($aviso['ativo']==1)?"desativar":"ativar";
?>
<section id="aviso">
    <img src="../img/fechar.png" class="fechar">
    <div>
        <h3>Editar Aviso</h3>
        <button class="botao <? echo $class; ?>"><? echo $texto ?></button>
        <form>
            <div class="input">
                <label>Palavra Principal</label>
                <input type="text" placeholder="Palavra Principal" name="palavra_principal" value="<? echo $aviso['palavra_principal']; ?>" required>
            </div>
            <div class="input">
                <label>Primeira Frase</label>
                <input type="text" placeholder="Frase 1" name="frase_1" value="<? echo $aviso['frase_1']; ?>" required>
            </div>
            <div class="input">
                <label>Frase com link</label>
                <input type="text" placeholder="Frase com link" name="frase_link" value="<? echo $aviso['frase_link']; ?>" required>
            </div>
            <div class="input">
                <label>Link para frase</label>
                <input type="text" placeholder="Link para Frase" name="link" value="<? echo $aviso['link']; ?>" required>
            </div>
            <div class="input">
                <label>Segunda Frase</label>
                <input type="text" placeholder="Frase 2" name="frase_2" value="<? echo $aviso['frase_2']; ?>" required>
            </div>
            <div class="input">
                <label>Imagem 1</label>
                <div class="upload">
                    <div class="nome">Nenhum arquivo</div>
                    <label for="imagem1"><img src="../img/upload.png">Procurar</label>
                    <input type="file" id="imagem1" name="imagem1" accept="image/*">
                </div>
            </div>
            <div class="input">
                <label>Imagem 2</label>
                <div class="upload">
                    <div class="nome">Nenhum arquivo</div>
                    <label for="imagem2"><img src="../img/upload.png">Procurar</label>
                    <input type="file" id="imagem2" name="imagem2" accept="image/*">
                </div>
            </div>
            <div class="input">
                <label>Imagem 3</label>
                <div class="upload">
                    <div class="nome">Nenhum arquivo</div>
                    <label for="imagem3"><img src="../img/upload.png">Procurar</label>
                    <input type="file" id="imagem3" name="imagem3" accept="image/*">
                </div>
            </div>

            <button class="botao">Salvar</button>
            <div class="clear"></div>
        </form>
    </div>
</section>