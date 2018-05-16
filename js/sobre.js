var atual = 1;
var loop;
var tempo=5000;
var id;

$(document).ready(function() {
    $(document).scroll();
    
    if (typeof adm=="undefined") slide();
    else {
        $(".slide").show();
    }
});

$("#esquerda").click(function() {
    clearTimeout(loop);
    $(".slide div *").addClass("fora");
    $("#slide #barra").stop().animate({width: "0%"}, 500);
    $(".slide:nth-child("+atual+")").fadeOut(1000, function() {
        if (atual>1) {
            atual--;
        } else {
            atual=3;
        }
        console.log(atual);
        $(".slide:nth-child("+atual+")").fadeIn(1000, function() {
            $("#bolas span").removeClass("atual");
            $("#bolas span[data-id="+atual+"]").addClass("atual");
            $(".slide div *").removeClass("fora");
        })
    });
});

$("#direita").click(function() {
    clearTimeout(loop);
    $(".slide div *").addClass("fora");
    $("#slide #barra").stop().animate({width: "0%"}, 500);
    $(".slide:nth-child("+atual+")").fadeOut(1000, function() {
        if (atual<3) {
            atual++;
        } else {
            atual=1;
        }
        console.log(atual);
        $(".slide:nth-child("+atual+")").fadeIn(1000, function() {
            $("#bolas span").removeClass("atual");
            $("#bolas span[data-id="+atual+"]").addClass("atual")
            $(".slide div *").removeClass("fora");
        })
    });
});

$("#bolas span").click(function() {
    if ($(this).hasClass("atual")) return;
    else {
        id = $(this).attr("data-id");
        if (id>atual) {
            if (id==atual+1) {
                $("#direita").click();
            } else {
                $("#esquerda").click();
            }
        } else {
            if (id==atual-1) {
                $("#esquerda").click();
            } else {
                $("#direita").click();
            }
        }
    }
});

$(".editar").click(function() {
    id = $(this).attr("data-id");
    
    $("#editar [name=titulo]").val(slides[id-1].titulo);
    $("#editar [name=descricao]").val(slides[id-1].descricao);
    
    $("#editar").fadeIn().css({display: "flex"});
    $("#editar [name=descricao]").focus().select();
});

$("#editar > img").click(function() {
    $("#editar").fadeOut()
})

$(document).on("change", "[type=file]", function(e) {
    nome = $(this)[0].files[0].name;
    $(this).parent().find(".nome").text(nome);
});

$("#editar form").submit(function(e) {
    e.preventDefault();
    
    form = this
    
    data = new FormData(this);
    data.append("id", id);
    data.append("descricao", $("[name=descricao]").val());
    $("#editar button").attr("disabled", true);
    $.ajax({
        url: "../php/adm/atualizarSobre.php",
        type: "post",
        data: data,
        success: function(result) {
            result = JSON.parse(result);

            console.log(result);

            if (result.estado==1) {
                chamarPopupConf(result.mensagem);
                
                form.reset();
                $("#editar").fadeOut();
                $("#editar .nome").text("Nenhum arquivo");
                slides[id-1] = result.slide;
                atualizarImagens();
                $("#editar button").attr("disabled", false);
            } else if (result.estado==2) {
                chamarPopupInfo(result.mensagem);
                $("#editar button").attr("disabled", false);
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

function slide() {
    $("#slide #barra").animate({width: "100%"}, tempo);
    loop = setTimeout(function() {
        $(".slide div *").addClass("fora");
        $("#slide #barra").animate({width: "0%"}, tempo/10);
        $(".slide:nth-child("+atual+")").fadeOut(1000, function() {
            if (atual<3) {
                atual++;
            } else {
                atual=1;
            }
//            console.log(atual);
            $(".slide:nth-child("+atual+")").fadeIn(1000, function() {
                $(".slide div *").removeClass("fora");
                $("#bolas span").removeClass("atual");
                $("#bolas span[data-id="+atual+"]").addClass("atual")
                slide();
            })
        });
    }, tempo)
}

function atualizarImagens() {
    $(".slide > img").each(function(i, elem) {
        tempId = $(this).attr("data-id");
        $(elem).attr("src", "../servidor/slide/"+slides[tempId].img+"?"+new Date().getTime());
        $(elem).parent().find("div h3").text(slides[tempId].titulo);
        $(elem).parent().find("div p").text(slides[tempId].descricao);
    });
}