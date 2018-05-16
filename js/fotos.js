var album=0, id=0;
var full=0;
var tempId;

$(document).ready(function() {
    $(document).scroll();
    
    $(".album .foto").each(function(i, elem) {
        rot = Math.random()* (5 - -5) + -5;
        
        $(this).css({transform: "rotate("+rot.toFixed(2)+"deg)"})
    });
    
    $(".album .foto").css({opacity: 1});
});

$(document).scroll(function() {
    topTemp = $(document).scrollTop();
//    console.log(topTemp); 
    
    if (topTemp>200 && !$("header").hasClass("branco")) {
        $("body>header").addClass("branco");
    } else if (topTemp<=200 && $("header").hasClass("branco")) {
        $("body>header").removeClass("branco");
    }
    
    $('.foto > img[data-realsrc]').each(function(i){
        var t = $(this);
        
        if(t.position().top < $(document).scrollTop()+$(window).innerHeight()){
            t.attr('src', t.attr('data-realsrc'));
            t.removeAttr('data-realsrc').css({width: "100%"});
        }
    });
}); 

$("#abrir").click(function() {
    if ($(this).hasClass("fechar")) {
        $(this).removeClass();
        $("#novo").hide();
    } else {
        $(this).addClass("fechar");
        $("#novo form")[0].reset();
        $("#novo").show().css({display: "flex"});
        $("#novo [name=nome]").focus();
    }
});

$(".full").click(function() {
    album = Number($(this).attr("data-album"));
    id = Number($(this).attr("data-id"));
    $("#full #ver").attr("src", guia[album][id]);
    
    $("#full").fadeIn().css({display: "flex"});
    full=1;
});

$("#full .fechar").click(function() {
    $("#full").fadeOut();
    full=0;
});

$(document).keyup(function(e) {
    codigo = e.keyCode;
    
    if (codigo==37 && full==1) $("#esquerda").click();
    else if (codigo==39 && full==1) $("#direita").click();
});

$("#esquerda").click(function() {
    if (id-1>=0) {
        id--;
        
        $("#full #ver").attr("src", guia[album][id]);
        console.log(id);
    }
});

$("#direita").click(function() {
    if (id+1<guia[album].length) {
        id++;
        
        $("#full #ver").attr("src", guia[album][id]);
        console.log(id);
    }
});

$(document).on("change", "[type=file]", function(e) {
//    nome = $(this)[0].files[0].name;
    nome = $(this)[0].files.length+" Imagens";
    $(this).parent().find(".nome").text(nome);
});

$("#novo form").submit(function(e) {
    e.preventDefault();
    
    form = this
    
    data = new FormData(this);
    data.append("titulo", $("[name=nome]").val());
    data.append("descricao", $("[name=descricao]").val());
    
    if ($("#novo [type=file]")[0].files.length==0) {
        chamarPopupInfo("Envie pelo menos uma imagem para o novo album!");
        return;
    }
    
//    return;
    $("#novo button").attr("disabled", true);
    $.ajax({
        url: "../php/adm/novoAlbum.php",
        type: "post",
        data: data,
        success: function(result) {
            result = JSON.parse(result);

            console.log(result);

            if (result.estado==1) {
                chamarPopupConf(result.mensagem);
                
                form.reset();
                $("#novo").fadeOut();
                $("#novo .nome").text("Nenhum arquivo");
                $("#novo button").attr("disabled", false);
                
                setTimeout(function() {
                    location.reload();
                }, 5000);
            } else if (result.estado==2) {
                chamarPopupInfo(result.mensagem);
                $("#novo button").attr("disabled", false);
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

$(".excluir").click(function() {
    tempId = $(this).attr("data-id");
    este = $(this);
    chamarConfirmacao("Deseja realmente apagar esse album?", function() {
        //sim
        data = {};
        data.id = tempId;
        
        $.ajax({
            url: "../php/adm/apagarAlbum.php",
            type: "post",
            data: data,
            success: function(result) {
                result = JSON.parse(result);

                console.log(result);

                if (result.estado==1) {
                    chamarPopupConf(result.mensagem);
                    este.parent().remove();
                    $("img#confirmacao-fechar").click();
                } else if (result.estado==2) {
                    chamarPopupInfo(result.mensagem);
                } else {
                    chamarPopupErro(result.mensagem);
                }
            }, 
            error: function(result) {
                console.log(result);
                chamarPopupErro("Houve um erro, tente atualizar a página!");
            }
        });
    }, function() {
        //nao
        $("img#confirmacao-fechar").click();
    });
});