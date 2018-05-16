var loop;

$(document).ready(function() {
    atualizarPesquisas();
    atualizarEmpresas();
    atualizarFuncionarios();
    
    $(document).scroll();
    
    loop = setTimeout(function() {
        location.href = "/";
    }, 20000);
    
    $(".ver .input input, textarea, select").attr("disabled", true)
})

$("#menu-botao").click(function() {
    $("header nav").toggleClass("aberto");
});

$("#pin input").keyup(function() {
//    console.log($(this).attr("id"))
    if ($(this).val().length == this.maxLength && $(this).attr("id")!="pin4") {
        $(this).next('input').focus();""
        console.log("a")
    } else {
        senha = "";
        
        $("#pin input").each(function(i, elem) {
            senha += $(elem).val();
        });
        
        if (senha.length!=4) {
            $("#pin input").val("");
            $("#pin1").focus();
        } else if(senha=="0517") {
            $("#pin").fadeOut();
            clearTimeout(loop);
        }
    }
});

$(document).scroll(function() {
    topTemp = $(document).scrollTop();
//    console.log(topTemp); 
    
    if (topTemp>200 && !$("header").hasClass("branco")) {
        $("body>header").addClass("branco");
    } else if (topTemp<=200 && $("header").hasClass("branco")) {
        $("body>header").removeClass("branco");
    }
});

function atualizarPesquisas() {
    $.each(pesquisa, function(i, value) {
        temp = "";
        
        temp += "<tr>";
        temp += "<td>"+value.id+"</td>";
        temp += "<td>"+value.nome+"</td>";
        temp += "<td>"+value.email+"</td>";
        temp += "<td><img src='img/menu.png' class='ver-pesquisa' data-id='"+i+"'></td>";
        temp += "</tr>";
        
        $("#pesquisa table").append(temp);
    });
}

function atualizarFuncionarios() {
    $.each(funcionarios, function(i, value) {
        temp = "";
        
        temp += "<tr>";
        temp += "<td>"+value.id+"</td>";
        temp += "<td>"+value.nome+"</td>";
        temp += "<td>"+value.email+"</td>";
        temp += "<td><img src='img/menu.png' class='ver-funcionario' data-id='"+i+"'></td>";
        temp += "</tr>";
        
        $("#funcionarios table").append(temp);
    });
}

function atualizarEmpresas() {
    $.each(empresas, function(i, value) {
        temp = "";
        
        temp += "<tr>";
        temp += "<td>"+value.id+"</td>";
        temp += "<td>"+value.denominacao_social+"</td>";
        temp += "<td>"+value.email+"</td>";
        temp += "<td><img src='img/menu.png' class='ver-empresa' data-id='"+i+"'></td>";
        temp += "</tr>";
        
        $("#empresas table").append(temp);
    });
}

$(".ver > img").click(function() {
    $(this).parent().fadeOut();
    $("body").css({overflowY: "auto"});
});

$(document).on("click", ".ver-pesquisa", function() {
    dataId = $(this).attr("data-id");
    pes = pesquisa[dataId];
    
    $.each(pes, function(i, value) {
        $("#ver-pesquisa [name="+i+"]").val(value);
        
        if ((value+"").length==1) {
            if (i=="pergunta3") {
                if (value=="1") {
                    $("#ver-pesquisa [name="+i+"]").val("Valor Recebido");
                } else {
                    $("#ver-pesquisa [name="+i+"]").val("Qualidade do Fornecedor");
                }
            } else {
                if (value=="1") {
                    $("#ver-pesquisa [name="+i+"]").val("Sim");
                } else {
                    $("#ver-pesquisa [name="+i+"]").val("Não");
                }    
            }
        }
    });
    
    $("body").css({overflowY: "hidden"});
    $("#ver-pesquisa").fadeIn().css({display: "flex"});
});

$(document).on("click", ".ver-funcionario", function() {
    dataId = $(this).attr("data-id");
    fun = funcionarios[dataId];
    
    $.each(fun, function(i, value) {
        $("#ver-funcionario [name="+i+"]").val(value);
    });
    
    $("body").css({overflowY: "hidden"});
    $("#ver-funcionario").fadeIn().css({display: "flex"});
    
    $("#ver-funcionario a").each(function(i, elem) {
        link = "/servidor/funcionario/"+fun.id+"/"+$(elem).attr("id")+".png";
        $(elem).attr("href", link)
    })
});

$(document).on("click", ".ver-empresa", function() {
    dataId = $(this).attr("data-id");
    emp = empresas[dataId];
    
    $.each(emp, function(i, value) {
        $("#ver-empresa [name="+i+"]").val(value);
    });
    
    $("body").css({overflowY: "hidden"});
    $("#ver-empresa").fadeIn().css({display: "flex"});
    
    $("#ver-empresa a").each(function(i, elem) {
        if ($(elem).attr("id")=="foto_usuarios") {
            link = "/servidor/funcionario/"+emp.id+"/"+$(elem).attr("id")+".zip";
            $(elem).attr("href", link)
        } else {
            link = "/servidor/funcionario/"+emp.id+"/"+$(elem).attr("id")+".png";
            $(elem).attr("href", link)
        }
    })
});

$("#youtube form").submit(function(e) {
    e.preventDefault();
    
    data = $(this).serializeArray();
    
    console.log(data);
    
    $.ajax({
        url: "php/atualizarLink.php",
        type: "post",
        data: data,
        success: function(result) {
            result = JSON.parse(result);
            
            console.log(result);
            
            if (result.estado==1) {
                chamarPopupConf("Link atualizado com sucesso!");
            } else chamarPopupErro("Erro, tente novamente ou atualize a página!")
        }, 
        error: function(result) {
            console.log(result);
            chamarPopupErro("Houve um erro, tente atualizar a página!");
        }
    })
})