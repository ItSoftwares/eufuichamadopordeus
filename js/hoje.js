var selecao1 = "";
var dia = hoje;
var CANVAS;
var gerado = false;
var selecionado = hoje;

$(document).ready(function() {
    String.prototype.replaceAll = String.prototype.replaceAll || function(needle, replacement) {
        return this.split(needle).join(replacement);
    };
    texto = $("#conteudo #texto").text();
    texto = colocarTags(texto);
    $("#conteudo #texto").html(texto);
    
    $.each(frases, function(i, value) {
        $("#frases [name="+value.id+"]").val(value.frase);
    });
	
	$("#aviso-mostrar").css({opacity: 1, filter: "blur(0px)"}); 
});

$("input[type=file]").change(function(e) {
    nome = $(this)[0].files[0].name;
//    console.log(e);
    $(this).parent().find(".nome").text(nome);
});

$("#negrito").on("click", function() {
    inicio = $("textarea")[0].selectionStart;
    fim = $("textarea")[0].selectionEnd;
    
    selecao2 = $("textarea").val().substring(inicio, fim);
    if (selecao1=="") return;
    
    if (selecao1!=selecao2) return;
    
    texto = $("textarea").val();
    texto = texto.substr(0, inicio) + "[b]" + selecao2 + "[/b]" + texto.substr(fim, texto.length);
    console.log(texto);
    
    $("textarea").val(texto).focus()[0].setSelectionRange(inicio, fim+7);
    atualizarPrevia();
});

$("#italico").on("click", function() {
    inicio = $("textarea")[0].selectionStart;
    fim = $("textarea")[0].selectionEnd;
    
    selecao2 = $("textarea").val().substring(inicio, fim);
    if (selecao1=="") return;
    
    if (selecao1!=selecao2) return;
    
    texto = $("textarea").val();
    texto = texto.substr(0, inicio) + "[i]" + selecao2 + "[/i]" + texto.substr(fim, texto.length);
    console.log(texto);
    
    $("textarea").val(texto).focus()[0].setSelectionRange(inicio, fim+7);
    atualizarPrevia();
});

$("#sublinhado").on("click", function() {
    inicio = $("textarea")[0].selectionStart;
    fim = $("textarea")[0].selectionEnd;
    
    selecao2 = $("textarea").val().substring(inicio, fim);
    if (selecao1=="") return;
    
    if (selecao1!=selecao2) return;
    
    texto = $("textarea").val();
    texto = texto.substr(0, inicio) + "[u]" + selecao2 + "[/u]" + texto.substr(fim, texto.length);
    console.log(texto);
    
    $("textarea").val(texto).focus()[0].setSelectionRange(inicio, fim+7);
    atualizarPrevia();
});

$("textarea").select(function(e) {
//    console.log(e);
    selecao1 = document.getSelection().toString();
});

$("textarea").keyup(function() {
    atualizarPrevia();
});

$("#semana .dia").click(function() {
    dia = $(this).attr("data-id");
    $(".dia").removeClass("hoje");
    $(this).addClass("hoje");
    
    $("#topo h3").text("ATUALIZAR IMAGEM TOPO - "+dias[dia-1]);
    $("#topo").fadeIn().css({display: "flex"});
});

$("#semanal span").click(function() {
    semana = $(this).attr("data-id");
    selecionado = semana;
    $("#semanal span").removeClass();
    $(this).addClass("selecionado")
    
    $("#conteudo #titulo").text(conteudo[semana-1].titulo);
    $("#conteudo #imagem").attr("src", "../servidor/conteudo/"+semana+".jpg");
    $("#conteudo #texto").html(colocarTags(conteudo[semana-1].texto));
});

$("#editar").click(function() {
    $("#formulario [name=titulo]").val(conteudo[semana-1].titulo);
    $("#formulario textarea").val(conteudo[semana-1].texto);
    atualizarPrevia();
    $("#formulario").fadeIn().css({display: "flex"});
    $("#formulario [name=titulo]").focus().select();
});

$("#editar-frases").click(function() {
    $("#frases").fadeIn().css({display: "flex"});
    $("#frases [name=1]").focus().select();
});

$("#formulario img.fechar").click(function() {
    $("#formulario").fadeOut();
});

$("#frases img.fechar").click(function() {
    $("#frases").fadeOut();
});

$("#topo img.fechar").click(function() {
    $("#topo").fadeOut();
});

$("#topo form").submit(function(e) {
    e.preventDefault();
    
    form = this
    
    data = new FormData(this);
    data.append("id", dias[dia-1]);
    $("#topo button").attr("disabled", true);
    $.ajax({
        url: "../php/adm/atualizarTopo.php",
        type: "post",
        data: data,
        success: function(result) {
            result = JSON.parse(result);

            console.log(result);

            if (result.estado==1) {
                chamarPopupConf(result.mensagem);
                
                form.reset();
                $("#topo").fadeOut();
                $("#topo .nome").text("Nenhum arquivo");
                $("#topo button").attr("disabled", false);
                
                $(".dia[data-id="+dia+"] img").attr("src", "../servidor/hoje/"+dias[dia-1]+".jpg?"+new Date().getTime());
            } else if (result.estado==2) {
                chamarPopupInfo(result.mensagem);
                $("#topo button").attr("disabled", false);
            } else {
                chamarPopupErro(result.mensagem);
            }
        }, 
        error: function(result) {
            console.log(result);
            chamarPopupErro("Houve um erro, tente atualizar a página!");
        },
        processData: false,
        contentType: false
    });
})

$("#formulario form").submit(function(e) {
    e.preventDefault();
    
    form = this
    
    data = new FormData(this);
    data.append("titulo", $("[name=titulo]").val());
    data.append("texto", $("[name=texto]").val());
    data.append("versao", ultima+1);
    data.append("id", selecionado);
    $("#formulario button").attr("disabled", true);
    $.ajax({
        url: "../php/adm/atualizarConteudo.php",
        type: "post",
        data: data,
        success: function(result) {
            result = JSON.parse(result);

            console.log(result);

            if (result.estado==1) {
                chamarPopupConf(result.mensagem);
                
                form.reset();
                $("#formulario").fadeOut();
                $("#formulario .nome").text("Nenhum arquivo");
                $("#formulario button").attr("disabled", false);
                conteudo[semana-1] = result.conteudo;
                atualizarImagens(dias[dia-1], dia);
            } else if (result.estado==2) {
                chamarPopupInfo(result.mensagem);
                $("#formulario button").attr("disabled", false);
            } else {
                chamarPopupErro(result.mensagem);
            }
        }, 
        error: function(result) {
            console.log(result);
            chamarPopupErro("Houve um erro, tente atualizar a página!");
        },
        processData: false,
        contentType: false
    });
});

$("#frases form").submit(function(e) {
    e.preventDefault();
    
    data = $(this).serializeArray();
    
    $.ajax({
        url: "../php/adm/atualizarFrases.php",
        type: "post",
        data: data,
        success: function(result) {
            result = JSON.parse(result);

            console.log(result);

            if (result.estado==1) {
                chamarPopupConf(result.mensagem);
                $("#formulario button").attr("disabled", false);
                $("#frases .fechar").click();
            } else if (result.estado==2) {
                chamarPopupInfo(result.mensagem);
                $("#formulario button").attr("disabled", false);
            } else {
                chamarPopupErro(result.mensagem);
            }
        }, 
        error: function(result) {
            console.log(result);
            chamarPopupErro("Houve um erro, tente atualizar a página!");
        }
    });
});

$("#baixar-pdf").click(function() {
    var win = window.open("conteudo?id="+hoje, '_blank');
    win.focus(); 
});

function colocarTags(texto) {
    texto = texto.replaceAll('[', '<');
    texto = texto.replaceAll(']', '>');
    texto = texto.replaceAll("\n","<br>")
    return texto;
}

function atualizarPrevia() {
    texto = $("textarea").val();
    texto = colocarTags(texto);
    
    $("#ver").html(texto);
}

function atualizarImagens(temp, temp2) {
    $("#conteudo #imagem").attr("src", "../servidor/conteudo/"+semana+".jpg?"+new Date().getTime());
    $("#conteudo #texto").html(colocarTags(conteudo[semana-1].texto));
    $("#conteudo h2 span").text(conteudo[semana-1].titulo);
//    $("#semana .dia[data-id="+temp2+"] img").attr("src", "../servidor/hoje/"+temp+".jpg?"+new Date().getTime());
}