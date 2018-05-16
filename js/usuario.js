var pagina=1;

$(document).ready(function() {
    paginar();
    
    $("#usuarios h3 span").text(qtd);
});

$("#apagar").click(function() {
    chamarConfirmacao("Deseja realmente apagar os registros de usuários que estão em branco?", function() {
        //sim
//        console.log("teste");
        data = {};
        $("#apagar").attr("disabled", true);
        $.ajax({
            url: "../php/adm/limparRegistros.php",
            type: "post",
            data: data,
            success: function(result) {
                result = JSON.parse(result);

                console.log(result);

                if (result.estado==1) {
                    chamarPopupConf(result.mensagem);
                    $("#apagar").attr("disabled", false);
                    $("tr[data-estado=0]").remove();
                    $("img#confirmacao-fechar").click();
                } else if (result.estado==2) {
                    chamarPopupInfo(result.mensagem);
                    $("#apagar").attr("disabled", false);
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
    }, function() {
        //nao
        $("img#confirmacao-fechar").click();
    });
});

$("#paginas li").click(function() {
    if ($(this).hasClass("selecionado")) return;
    
    $("#paginas li").removeClass("selecionado");
    $(this).addClass("selecionado");
    
    pagina = $(this).attr("data-pagina");
    
    $("#usuarios tr:not(:first-child)").hide();
    
    $("#usuarios tr[data-pagina="+pagina+"]").show();
    
    paginar();
});

function paginar() {
    $("#paginas li").attr("data-aberto", 0);
    
    $("#paginas li[data-pagina="+1+"]").attr("data-aberto", 1);
    $("#paginas li[data-pagina="+(Number(pagina)-2)+"]").attr("data-aberto", 1);
    $("#paginas li[data-pagina="+(Number(pagina)-1)+"]").attr("data-aberto", 1);
    $("#paginas li[data-pagina="+(Number(pagina))+"]").attr("data-aberto", 1);
    $("#paginas li[data-pagina="+(Number(pagina)+1)+"]").attr("data-aberto", 1);
    $("#paginas li[data-pagina="+(Number(pagina)+2)+"]").attr("data-aberto", 1);
    $("#paginas li[data-pagina="+ultima+"]").attr("data-aberto", 1);
}