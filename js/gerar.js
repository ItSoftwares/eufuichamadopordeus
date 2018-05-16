var gerado = false;

$(document).ready(function() {
    if (logins.length>0) {
        $("#gerar .botao:first-child").hide().attr("disabled", true);
        $("#imprimir").show();
        gerarFolha(logins);
    } 
});

$("#gerar .botao:first-child").click(function() {
    $(this).attr("disabled", true);
    data = {}
    botao = $(this);
    $.ajax({
        url: "../php/adm/gerarUsuarios.php",
        type: "post",
        data: data,
        success: function(result) {
            result = JSON.parse(result);
            console.log(result);
            
            if (result.estado==1) {
                chamarPopupConf(result.mensagem);
                gerarFolha(result.logins);
                botao.hide();
                $("#imprimir").show();
                history.pushState(null, null, "?id="+result.ids);
            } else if (result.estado==2) {
                chamarPopupConf(result.mensagem);
            } else {
                chamarPopupErro("erro");
            }
        }, 
        error: function(result) {
            console.log(result);
            chamarPopupErro("Houve um erro, tente atualizar a página!");
        }
    })
});

$("#imprimir").click(function() {
    if (gerado) {
        window.print();
        return;
    }
    html2canvas($("#folha")[0], {
        onrendered: function(canvas) {
            document.body.appendChild(canvas);
            $(canvas).addClass("imprimir");
            $("#folha").hide();
            gerado = true;
            window.print();
        }
    });
});

function gerarFolha(logins) {
    temp = "<section id='folha'>";
    
    $.each(logins, function(i, value) {
        temp += "<span class='etiqueta'>";
        temp += "<p><b>Login</b>: "+value.email.substr(0, 6)+"</p>";
        temp += "<p><b>Senha</b>: "+value.senha.substr(0, 6)+"</p>";
        temp += "</span>";
    });
    
    temp += "</section>";
    
    $("body").append(temp);
}