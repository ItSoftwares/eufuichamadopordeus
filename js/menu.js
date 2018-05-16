$(document).ready(function() {
    $(document).scroll();
    
    $("header > a").attr("href", "");
});

$(document).scroll(function() {
    return;
    topTemp = $(document).scrollTop();
   // console.log(topTemp); 
    
    if (topTemp>50 && !$("header").hasClass("branco")) {
        $("body>header").addClass("branco");
    } else if (topTemp<=50 && $("header").hasClass("branco")) {
        $("body>header").removeClass("branco");
    }
});

$("#menu-botao").click(function() {
    $("header nav").toggleClass("aberto");
});

$("#aviso-botao").click(function(e) {
    e.preventDefault();
    $("body").toggleClass("hidden")
    $("#aviso").fadeIn().css({display: "flex"});
});

$("#aviso .fechar, #aviso-mostrar .fechar").click(function() {
    $("body").toggleClass("hidden")
    $(this).parent().fadeOut();
});

$("#aviso > div > button").click(function() {
	ativo = 0;
	
	
    if ($(this).hasClass("ativar")) {
		ativo = 1;
    } else {
		ativo = 0;
    }
	
	data = {ativo: ativo, funcao: "change"};
	console.log(data);
//	return;
	$("#aviso > div > button").attr("disabled", true);
	este = $(this);
    $.ajax({
        url: "../php/adm/atualizarAviso.php",
        type: "post",
        data: data,
        success: function(result) {
            result = JSON.parse(result);

            console.log(result);

            if (result.estado==1) {
                chamarPopupConf(result.mensagem);
				
				if (ativo==1) {
					$(este).removeClass("ativar").addClass("desativar");
					$(este).text("Desativar");
				} else {
					$(este).removeClass("desativar").addClass("ativar");
					$(este).text("Ativar");
				}
                console.log(ativo)
				$("#aviso > div > button").attr("disabled", false);
            } else if (result.estado==2) {
                chamarPopupInfo(result.mensagem);
                $("#aviso > div > button").attr("disabled", false);
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

$("#aviso form").submit(function(e) {
	e.preventDefault();
	
	form = this
    
    data = new FormData(this);
    data.append("funcao", "atualizar");
	
    $("#aviso button").attr("disabled", true);
    $.ajax({
        url: "../php/adm/atualizarAviso.php",
        type: "post",
        data: data,
        success: function(result) {
            result = JSON.parse(result);

            console.log(result);

            if (result.estado==1) {
                chamarPopupConf(result.mensagem);
				
				form.reset();
                $("#aviso").fadeOut();
                $("#aviso .nome").text("Nenhum arquivo");
                $("#aviso button").attr("disabled", false);
            } else if (result.estado==2) {
                chamarPopupInfo(result.mensagem);
                $("#aviso button").attr("disabled", false);
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