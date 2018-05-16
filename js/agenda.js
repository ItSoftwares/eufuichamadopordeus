var id_editar=0;
var id_excluir=0;
var meses = ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"];
var mesCalendario, anoCalendario;
var ladoCartao = "frente";
var esgotado = 0;

$(document).ready(function() {
    if (typeof inscricoes!="undefined") {
        temp = {};
        $.each(encontros, function(i, value) {
            temp[value.id] = []; 
        });
        
        inscritos = inscricoes;
        $.each(inscricoes, function(i, value) {
            if (!(value.id_encontro in encontros)) return true;
            temp[value.id_encontro].push(value.id_usuario);
        });
        inscricoes = temp;

        temp = {};
        $.each(encontros, function(i, value) {
            temp[value.id] = []; 
        });
        
        $.each(inscritos, function(i, value) {
            temp[value.id_encontro+"."+value.id_usuario] = value;
        });
        inscritos = temp;
    }
    
    if (typeof pagseguro!="undefined") {
        PagSeguroDirectPayment.setSessionId(id_sessao);
        $("#abrir-pagamento").attr("disabled", true);

        PagSeguroDirectPayment.getPaymentMethods({
            success: function(json){
                console.log(json);
                getInstallments();
                if (esgotado==0) $("#abrir-pagamento").attr("disabled", false);
            }, error: function(json){
                console.log(json);
                var erro = "";
                for(i in json.errors){
                    erro = erro + json.errors[i];
                }

                chamarPopupErro("Houve algum erro, por favor atualize a página!")
            }, complete: function(json){
                console.log(json);
            }
        });
        
        $.each(encontros, function(j, enc) {
            id_info = enc.id;
            temp = {};
            estados = [];
            if (Object.keys(pagamentos).length>0) {
                $.each(pagamentos, function(i, value) {
                    if (value.id_encontro==id_info) {
                        temp[i] = value;
                        estados.push(Number(value.estado));
                    }
                });

                if (estados.length>0) {
    //                console.log(estados);
                    if (estados.indexOf(3)!=-1 || estados.indexOf(4)!=-1) {
                        if (encontros[id_info].cancelado==0) {
                            if (enc.id+"."+usuario.id in inscritos && inscritos[enc.id+"."+usuario.id].presente==1) {
                                $(".encontro[data-id="+id_info+"] section .presenca").text("Presença Ok");
                            } else {
                                $(".encontro[data-id="+id_info+"] section .presenca").attr("disabled", false);
                            }
                        }
                    } 
                }
            }
        });
    }
    
    id_info = 0;
});

$(".editar, .reeditar").click(function() {
    id_editar = $(this).parent().parent().parent().attr("data-id");
//    console.log(id_editar); 
    $("body").toggleClass("hidden");
    $("#novo").fadeIn().css({display: "flex"});
    
    $("#novo [name=data]").val(getData(encontros[id_editar].data)).focus();
    $("#novo [name=hora_inicio]").val(getHora(encontros[id_editar].data));
    $("#novo [name=nome]").val(encontros[id_editar].nome);
    $("#novo [name=como]").val(encontros[id_editar].como);
    $("#novo [name=tema]").val(encontros[id_editar].tema);
    $("#novo [name=local]").val(encontros[id_editar].local);
    $("#novo [name=endereco]").val(encontros[id_editar].endereco);
    $("#novo [name=cidade]").val(encontros[id_editar].cidade);
    $("#novo [name=valor]").val(encontros[id_editar].valor);
    $("#novo [name=observacao]").val(encontros[id_editar].observacao);
});

$(".info").click(function() {
    id_info = $(this).closest(".encontro").attr("data-id");
    console.log(id_info);
    if (encontros[id_info].esgotado==0) {
        $("#agendar h3").text("FAZER INSCRIÇÃO").css({background: "#03A9F4"});
        $("#agendar #container > div p").css({opacity: 1});
        $("#agendar #container > div button").attr("disabled", false);
    } else {
        $("#agendar h3").text("VAGAS ESGOTADAS").css({background: "#FF5722"});
        $("#agendar #container > div p").css({opacity: ".5"});
        $("#agendar #container > div button").attr("disabled", true);
        esgotado=1;
    }
    
    temp = {};
    estados = [];
    if (Object.keys(pagamentos).length>0) {
        $.each(pagamentos, function(i, value) {
            if (value.id_encontro==id_info) {
                temp[i] = value;
                estados.push(Number(value.estado));
            }
        });
        
        if (estados.length>0) {
            console.log(estados);
            if (estados.indexOf(3)!=-1 || estados.indexOf(4)!=-1) {
                $("#abrir-pagamento").attr("disabled", true);
                $("#container > div > p").text("Você já está inscrito nesse encontro!");
            } else if (estados.indexOf(1)!=-1 || estados.indexOf(2)!=-1) {
                $("#container > div > p").html("Você já tem um pagamento em avaliação, aguarde, <br>ou se quiser fazer uma nova inscrição clique aqui:").css({textAlign: "center"});
            } else {
                $("#container > div > p").html("Você teve algum pagamento não aprovado, <br>faça uma nova inscrição clicando aqui:").css({textAlign: "center"});
            }
        }
    }
//    console.log(id_editar); 
    $("body").toggleClass("hidden");
    $("#agendar").fadeIn().css({display: "flex"});
    
    $("#agendar #como p").html("<b>Como Será Nosso Encontro</b><br><br>"+encontros[id_info].como);
    $("#agendar #data span").text(getData(encontros[id_info].data));
    $("#agendar #horario span").text(getHora(encontros[id_info].data));
    $("#agendar #nome span").text(encontros[id_info].nome);
    $("#agendar #tema span").text(encontros[id_info].tema);
    $("#agendar #local span").text(encontros[id_info].local);
    $("#agendar #endereco span").text(encontros[id_info].endereco);
    $("#agendar #cidade span").text(encontros[id_info].cidade);
    $("#agendar #valor span").text("R$ "+encontros[id_info].valor);
    $("#agendar #observacao span").text(encontros[id_info].observacao);
    
    $("#agendar #imagem1").attr("src", "../servidor/encontro/"+encontros[id_info].id+"-imagem1.jpg");
    $("#agendar #imagem2").attr("src", "../servidor/encontro/"+encontros[id_info].id+"-imagem2.jpg");
    $("#agendar #imagem3").attr("src", "../servidor/encontro/"+encontros[id_info].id+"-imagem3.jpg");
    
    $("#fundo-pagar [name=valor]").val(encontros[id_info].valor);
    $("#fundo-pagar [name=descricao]").val("Inscrição do usuário "+usuario.nome+" de ID "+usuario.id+", no encontro "+encontros[id_info].nome);
    $("#fundo-pagar [name=encontro]").val(encontros[id_info].id);
    $("#calendario .hoje").removeClass("hoje");
    dataGeral = new Date(encontros[id_info].data*1000);
    mesCalendario = dataGeral.getMonth();
    anoCalendario = dataGeral.getFullYear();
    
    $("#calendario").css({opacity: 1}); 
    
    atualizarCalendario(mesCalendario, anoCalendario);
});

$(".excluir").click(function() {
    id_excluir = $(this).parent().parent().parent().attr("data-id");
    data = {id: id_excluir, funcao: "excluir"};
    botao = $(this);
    
    botao.attr("disabled", true);
    
    $.ajax({
        url: "../php/adm/manterEncontro.php",
        type: "post",
        data: data, 
        success: function(result) {
            console.log(result);
            result = JSON.parse(result);

            if (result.estado==1) {
                chamarPopupConf(result.mensagem);
                
                delete encontros[id_excluir];
                $(".encontro[data-id="+id_excluir+"]").remove();
                
                botao.attr("disabled", false);
            } else if (result.estado==2) {
                chamarPopupInfo(result.mensagem);
                botao.attr("disabled", false);
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

$(".cancelar").click(function() {
    id_cancelar = $(this).parent().parent().parent().attr("data-id");
    $("#cancelar [name=id]").val(id_cancelar);
    $("#cancelar").fadeIn().css({display: "flex"});
});

$("#cancelar .fechar").click(function() {
    $(this).parent().fadeOut();
});

$("#cancelar form").submit(function(e) {
    e.preventDefault();
    
    form = this;
    data = $(this).serialize();
    
    $("#cancelar button").attr("disabled", true);
    
    $.ajax({
        url: "../php/adm/manterEncontro.php",
        type: "post",
        data: data, 
        success: function(result) {
            console.log(result);
            result = JSON.parse(result);

            if (result.estado==1) {
                chamarPopupConf(result.mensagem);
                
                form.reset();
                
                encontros[id_cancelar].cancelado = result.encontro.cancelado;
                encontros[id_cancelar].motivo = result.encontro.motivo;
                $(".encontro[data-id="+id_cancelar+"] button.cancelar").removeClass("cancelar").addClass("cancelado").text("Cancelado").attr("disabled", true);
                $("#cancelar").fadeOut();
                $("#cancelar button").attr("disabled", false);
            } else if (result.estado==2) {
                chamarPopupInfo(result.mensagem);
                $("#cancelar button").attr("disabled", false);
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

$(".cancelado").click(function() {
    id_info = $(this).closest(".encontro").attr("data-id");
    console.log(id_info);
    $("#cancelar p").text(encontros[id_info].motivo);
    
    $("#cancelar").fadeIn().css({display: "flex"});
});

$("#exibir").click(function() {
    $("#video-link").fadeIn().css({display: "flex"});
});

$(".encerrar, .esgotado").click(function(e) {
    id_editar = $(this).parent().parent().parent().attr("data-id");
    
    valor = encontros[id_editar].esgotado==0?1:0;
    
    data = {id: id_editar, funcao: "atualizar", esgotado: valor}
    este = this;
    $(este).attr("disabled", true);
    
    $.ajax({
        url: "../php/adm/manterEncontro.php",
        type: "post",
        data: data, 
        success: function(result) {
            console.log(result);
            result = JSON.parse(result);

            if (result.estado==1) {
                chamarPopupConf(result.mensagem);
                
                encontros[id_editar].esgotado = valor;
                
                if (valor==0) {
                    $(este).removeClass("esgotado").addClass("encerrar").text("Encerrar Inscrições").attr("disabled", false);
                } else {
                    $(este).removeClass("encerrar").addClass("esgotado").text("Esgotado").attr("disabled", false);
                }
                
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
});

$("#video-link form").submit(function(e) {
    e.preventDefault();
    
    link = $(this).find("[name=link]").val();
    data = $(this).serializeArray();
    temp = {};
    $.each(data, function(i, value) {
        temp[value.name] = value.value;
    })
    data = temp;
    
    if (link!=null || link.length>0) {
        if (link.indexOf("watch?v=")!=-1) {
            newlink = link.split("watch?v=")[1];
            
            if (newlink.indexOf("&list=")!=-1) newlink = newlink.split("&list=")[0];
            
            data.link = newlink;
        }
    }
    este = this;
    $(este).find("button").attr("disabled", true);
    $.ajax({
        url: "../php/adm/atualizarLink.php",
        type: "post",
        data: data, 
        success: function(result) {
            console.log(result);
            result = JSON.parse(result);
            
            if (result.estado==1) {
                chamarPopupConf(result.mensagem);
                $(este).find("button").attr("disabled", false);
                $("#video-link").fadeOut();
            } else if (result.estado==2) {
                chamarPopupInfo(result.mensagem);
            } else {
                chamarPopupErro(result);
            }
        }, 
        error: function(result) {
            console.log(result);
            chamarPopupErro("Houve um erro, tente atualizar a página!");
        }
    });
});

$(".exibir, .ocultar").click(function() {
    id_editar = $(this).parent().parent().parent().attr("data-id");
    
    valor = encontros[id_editar].presenca==0?1:0;
    
    data = {id: id_editar, funcao: "atualizar", presenca: valor}
    este = this;
    $(este).attr("disabled", true);
    
    $.ajax({
        url: "../php/adm/manterEncontro.php",
        type: "post",
        data: data, 
        success: function(result) {
            console.log(result);
            result = JSON.parse(result);

            if (result.estado==1) {
                chamarPopupConf(result.mensagem);
                
                encontros[id_editar].presenca = valor;
                
                if (valor==0) {
                    $(este).removeClass("ocultar").addClass("exibir").text("Exibir Presença").attr("disabled", false);
                } else {
                    $(este).removeClass("exibir").addClass("ocultar").text("Presença Exibida").attr("disabled", false);
                }
                
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
});

$(".presenca").click(function() {
    data = {};
    
    data.funcao = "inscrever";
    data.id_usuario = usuario.id;
    data.id = $(this).closest(".encontro").attr("data-id");
    este = this;
    
    console.log(data);
    $(este).attr("disabled", true);
    $.ajax({
        url: "../php/adm/manterEncontro.php",
        type: "post",
        data: data, 
        success: function(result) {
            console.log(result);
            result = JSON.parse(result);

            if (result.estado==1) {
                chamarPopupConf(result.mensagem);
                
                inscritos[data.id+"."+data.id_usuario].presente=1;
                
                $(este).text("Presença OK");
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
});

function getData(time) {
    time = new Date(time*1000);
    return colocarZero(time.getDate())+"/"+colocarZero(time.getMonth()+1)+"/"+time.getFullYear();
}

function getHora(time) {
    time = new Date(time*1000);
    return colocarZero(time.getHours())+":"+colocarZero(time.getMinutes());
}

function colocarZero(n) {
    if (n<10) return "0"+n;
    
    return n;
}

function validarHora(hora) {
    hora = hora.split(":");
    
    if (hora[0]>23 || hora[1]>59) {
        return true;
    }
    
    return false;
}

function getDiaMes(mes, ano) {
//        mes--;
    var dias = [];
    var data = new Date(ano, mes, 1);

    while (data.getMonth() === mes) {
        dia = data.getDate();
        dias.push(dia<10?"0"+dia:dia);
        data.setDate(data.getDate()+1);
    }

    return dias;
}

function atualizarCalendario(mes, ano) {
    semana = 0;
    dias = getDiaMes(mes, ano);

    diaSemana = new Date(ano, mes, 1).getDay();

    if ($(".semana").length<6) {
        while ($(".semana").length<6) {
            $(".semana:last-child").clone().appendTo("#calendario article");
        }
    }

    while (dias.length>0) {
        temp = 1;
        semana++;
        while (temp<8) {
            if (temp<=diaSemana) {
                $(".semana:nth-child("+semana+") span:nth-child("+temp+")").text("").attr("data-nada", 1).attr("data-dia",0);
            } else if (dias.length>0) {
                if (dataGeral.getDate()==dias[0] && mes===dataGeral.getMonth() && ano==dataGeral.getFullYear()) {
                    $(".semana:nth-child("+semana+") span:nth-child("+temp+")").addClass("hoje");
                    $("#dia").text(dias[0]);
                }
                $(".semana:nth-child("+semana+") span:nth-child("+temp+")").text(dias[0]).attr("data-nada",0).attr("data-dia",dias[0]);
                dias.splice(0,1);
            } else {
                $(".semana:nth-child("+semana+") span:nth-child("+temp+")").text("").attr("data-nada",1).attr("data-dia",0);
            }

            temp++;
        }
        diaSemana=0;
    }

    while (semana<7) {
        semana++;
        $(".semana:nth-child("+semana+")").remove();
    }
    
    $("#mes").text(meses[mesCalendario]+" -");
    $("#ano").text(anoCalendario);
}

function ValidarData() {
    var aAr = typeof (arguments[0]) == "string" ? arguments[0].split("/") : arguments,
        lDay = parseInt(aAr[0]), lMon = parseInt(aAr[1]), lYear = parseInt(aAr[2]),
        BiY = (lYear % 4 == 0 && lYear % 100 != 0) || lYear % 400 == 0,
        MT = [1, BiY ? -1 : -2, 1, 0, 1, 0, 1, 1, 0, 1, 0, 1];
    return lMon <= 12 && lMon > 0 && lDay <= MT[lMon - 1] + 30 && lDay > 0;
}

$("#novo img.fechar, #agendar img.fechar").click(function() {
    $("body").toggleClass("hidden");
    $(this).parent().fadeOut();
});

$(".inscritos").click(function() {
    $(this).parent().find("table").fadeToggle().css({display: "table"});
});

$(document).on("change", "[type=file]", function(e) {
    nome = $(this)[0].files[0].name;
//    nome = $(this)[0].files.length+" Imagens";
    $(this).parent().find(".nome").text(nome);
});

$("#novo form").submit(function(e) {
    e.preventDefault();
    
    if (validarHora($("[name=hora_inicio]").val())) {
        chamarPopupInfo("Digite uma hora válida!");
        $("[name=hora_inicio]").focus();
        return;
    }
    
    form = this;
    data = new FormData(form);
    
    data.append("funcao", "atualizar");
    data.append("id", id_editar);
    
    $("#novo button").attr("disabled", true);
    
    $.ajax({
        url: "../php/adm/manterEncontro.php",
        type: "post",
        data: data, 
        success: function(result) {
            console.log(result);
            result = JSON.parse(result);

            if (result.estado==1) {
                chamarPopupConf(result.mensagem);
                
                form.reset();
                
                encontros[id_editar] = result.encontro;
                $(".encontro[data-id="+id_editar+"] button.editar").removeClass("editar").addClass("reeditar").text("Reeditar");
                $("#novo").fadeOut();
                $("body").toggleClass("hidden");
                $("#novo button").attr("disabled", false);
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

$("#erro-pag button").click(function() {
    chamarConfirmacao("<h4>Eu tentei fazer o pagamento mas não consegui.<br><br> <b>Enviar Comunicado?</b></h4>", function() {
//        console.log("sim"); 
        data = {funcao: "extra", banco: "erro_pagamento", id_usuario: usuario.id, "id_encontro": id_info};
        $("#erro-pag button").attr("disabled", true);
        $("#confirmacao-tela div img#confirmacao-fechar").click();
        $.ajax({
            type: "post",
            url: "../php/adm/manterEncontro.php",
            data: data,
            success: function(result) {
                console.log(result);
                result = JSON.parse(result);

                if (result.estado==1) {
                    chamarPopupConf(result.mensagem);
                } else {
                    chamarPopupInfo(result.mensagem);
                    $("#cadastro").find("button").attr("disabled", false);
                }
                
                $("#erro-pag button").attr("disabled", false);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                chamarPopupErro("Desculpe, houve um erro, por favor atualize a pÃ¡gina ou nos contate.");
                console.log(XMLHttpRequest);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    }, function() {
        $("#confirmacao-tela div img#confirmacao-fechar").click();
    });
});

$("#quero-ir button").click(function() {
    chamarConfirmacao("<h4>Ao pagar a inscrição online vc ganha desconto!<br><br> <b>Prefere pagar no local?</b></h4>", function() {
//        console.log("sim"); 
        data = {funcao: "extra", banco: "quero_ir", id_usuario: usuario.id, "id_encontro": id_info};
        $("#quero-ir button").attr("disabled", true);
        $("#confirmacao-tela div img#confirmacao-fechar").click();
        $.ajax({
            type: "post",
            url: "../php/adm/manterEncontro.php",
            data: data,
            success: function(result) {
                console.log(result);
                result = JSON.parse(result);

                if (result.estado==1) {
                    chamarPopupConf(result.mensagem);
                } else {
                    chamarPopupInfo(result.mensagem);
                }
                
                $("#quero-ir button").attr("disabled", false);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                chamarPopupErro("Desculpe, houve um erro, por favor atualize a pÃ¡gina ou nos contate.");
                console.log(XMLHttpRequest);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    }, function() {
        $("#confirmacao-tela div img#confirmacao-fechar").click();
    });
});

// PAGAMENTO

$("#abrir-pagamento").click(function() {
    teste = false;
    if (Object.keys(pagamentos).length>0) {
        $.each(pagamentos, function(i, value) {
            if (value.id_encontro==id_info) {
                temp[i] = value;
                estados.push(Number(value.estado));
            }
        });
        
        if (estados.length>0) {
            console.log(estados);
            if (estados.indexOf(3)!=-1 || estados.indexOf(4)!=-1) {
                $("#abrir-pagamento").attr("disabled", true);
                chamarPopupInfo("Você já está inscrito nesse encontro!");
                teste = true;
            }
        }
    }
    if (teste) return;
    $("#agendar").fadeOut(function() {
        $("#fundo-pagar").fadeIn().css({display: "flex"});
    });
});

$("input[name='pagamento']").change(function() {
    tipoPagamento=$(this).attr("id");
    
    if (tipoPagamento=="cartao") {
        $("#tipo").css({background: "#f4b35a"}).find("img").attr("src", "../img/cartao-credito.png");
        $("#pagar-cartao").show();
        $("#pagar-boleto").hide();
        $("#pagar-deposito").hide();
        $("#titular").focus();
    } else if (tipoPagamento=="boleto") {
        $("#tipo").css({background: "#65c288"}).find("img").attr("src", "../img/boleto.png");
        $("#pagar-cartao").hide();
        $("#pagar-deposito").hide();
        $("#pagar-boleto").show();
        $("#pagar-boleto button").focus();
    }
});

$("#pagar-cartao input").focusin(function() {
    $(".marcado").removeClass("marcado");
    
    id = "."+$(this).attr("data-id");
    
    $(id).addClass("marcado");
    
    if (id==".cvc" && ladoCartao=="frente") {
        $("#cartao-credito").css({transform: "rotateY(180deg)"});
        ladoCartao = "verso";
    } else if (ladoCartao=="verso") {
        $("#cartao-credito").css({transform: "rotateY(00deg)"});
        ladoCartao = "frente";
    }
});
 
$("#pagar-cartao input").focusout(function() {
    $(".marcado").removeClass("marcado");
    if (ladoCartao=="verso") {
        $("#cartao-credito").css({transform: "rotateY(00deg)"});
        ladoCartao = "frente";
    }
});

$("#confirmar > img").click(function() {
    $("#confirmar").fadeOut();
});

$("#fundo-pagar > img").click(function() {
    $("#fundo-pagar").fadeOut();
});

$("#numero").keyup(function(){
    getInstallments();
});

$("#pagar-cartao form button").click(function() {
    if ($("#titular").val().length==0) {
        chamarPopupInfo("Digite o Nome do titular!");
        $("#titular").focus().select();
        return;
    }
    
    if ($("#numero").cleanVal().length!=16) {
        chamarPopupInfo("Digite um número de cartão válido!");
        $("#numero").focus().select();
        return;
    }
    
    if ($("#validade").cleanVal().length!=6) {
        chamarPopupInfo("Digite um validade válida!");
        $("#validade").focus().select();
        return;
    }
    
    if ($("#cvc").cleanVal().length!=3) {
        chamarPopupInfo("Digite um código cvc de 3 dígitos!");
        $("#cvc").focus().select();
        return;
    }
    
    $("#confirmar").fadeIn().css({display: "flex"});
});

$("#comprovante .fechar").click(function() {
    chamarPopupConf("Iremos atualizar a página aguarde...");
    setTimeout(function() {
        location.reload();
    },5000);
});

$("#pagar-cartao form").submit(function(e) {
    e.preventDefault();
    $("#pagar button").attr("disabled", true);
    data = $(this).serializeArray();
    
    temp = {};
    $.each(data, function(i, value) {
        temp[value.name] = value.value;
    });
    data = temp;
    
    data['telefone'] = $("#telefone").cleanVal();
    data['cpf'] = $("#pagar-cartao #cpf").cleanVal();
    
    if ($("[name=brand]").val()=="") {
        chamarPopupInfo("Informe um número de cartão válido!");
        $("#confirmar").fadeOut();
        $("#numero").focus().select();
    }
    
    var param = {
        cardNumber: $("#numero").cleanVal(),
        brand: $("#brand").text(),
        cvv: $("#cvc").val(),
        expirationMonth: $("#validade").val().split('/')[0],
        expirationYear: $("#validade").val().split('/')[1],
        success: function(json){
            var token = json.card.token;
            data['token'] = token;

            var senderHash = PagSeguroDirectPayment.getSenderHash();
            data['senderHash'] = senderHash;
            
            // AJAX
            $("#pagar button").attr("disabled", true);
            $("#confirmar").hide();
            $("#comprovante").show().css({display: "flex"});
            $.ajax({
                url: "../php/pagseguro/pagarComCartao.php",
                type: "post",
                data: data,
                success: function(result) {
                    padrao = result;
                    result = JSON.parse(result);
                    result = result;
                    console.log(result);
                    
                    $("#pagar button").attr("disabled", false);
                    
                    if (result.status==1) {
                        chamarPopupConf("Pagamento realizado com sucesso!");
                        
                        $("#comprovante .valor").text(result.grossAmount.replace(".", ","));
                        $("#comprovante .descricao").text($("[name=descricao]").val());
                        $("#comprovante .codigo").text(result.code);
                        
                        $("#comprovante .loading").hide();
                        $("#comprovante .fechar").show();
                        $("#comprovante > div").fadeIn();
                        
                    } else {
                        console.log(result);
                        chamarPopupErro("Houve algum problema, tente novamente! Atualizando a página...");
                        setTimeout(function() {
                            location.reload();
                        },5000);
                    }
                }, 
                error: function(result) {
                    console.log(result);
                    chamarPopupErro("Houve um erro, tente atualizar a página!");
                    $("#pagar button").attr("disabled", false);
                }
            });
            
        }, error: function(json){
            console.log(json);
            $("#pagar button").attr("disabled", false);
        }, complete:function(json){
        }
    }

    console.log(data);
    
    PagSeguroDirectPayment.createCardToken(param);
})

$("#pagar-boleto form").submit(function(e) {
    e.preventDefault();
    
    $("#pagar button").attr("disabled", true);
    data = $(this).serializeArray();
    
    temp = {};
    $.each(data, function(i, value) {
        temp[value.name] = value.value;
    });
    data = temp;
    
    data['cpf'] = $("#pagar-boleto #cpf").cleanVal();
    
    var senderHash = PagSeguroDirectPayment.getSenderHash();
    data['senderHash'] = senderHash;
    
    console.log(data);
//    return;
    
    // AJAX
    $("#confirmar").hide();
    $("#comprovante").show().css({display: "flex"});
    $("#pagar button").attr("disabled", true);
    $.ajax({
        url: "../php/pagseguro/pagarComBoleto.php",
        type: "post",
        data: data,
        success: function(result) {
            result = JSON.parse(result);
            console.log(result);

            $("#pagar button").attr("disabled", false);
            
            var win = window.open(result.paymentLink, "popUpWindow");
            
            if (result.status==1) {
                chamarPopupConf("Pagamento realizado com sucesso, aguarde!");
                
                $("#comprovante h2").html("Boleto de R$ <span class='valor'>0,00</span> gerado com sucesso!")
                $("#comprovante .valor").text(result.grossAmount.replace(".", ","));
                $("#comprovante .descricao").html($("[name=descricao]").val()+"<br><a href='"+result.paymentLink+"' target='_blank'>Link do Boleto</a>");
                $("#comprovante .codigo").text(result.code);

                $("#comprovante .loading").hide();
                $("#comprovante .fechar").show();
                $("#comprovante > div").fadeIn();
            }
        }, 
        error: function(result) {
            console.log(result);
            chamarPopupErro("Houve um erro, tente atualizar a página!");
            $("#pagar button").attr("disabled", false);
        }
    });
});

function getInstallments(){
//    console.log("teste");
    var cardNumber = $("#numero").cleanVal();

    //if creditcard number is finished, get installments
    if(cardNumber.length != 16){
        return;
    } 

    PagSeguroDirectPayment.getBrand({
        cardBin: cardNumber,
        success: function(json){
            console.log(json);
            
            var brand = json.brand.name;
            $("#brand").text(brand);
            $("[name=brand]").val("brand");
        }, error: function(json){
            console.log(json);
            $("#brand").text("");
        }, complete: function(json){
            console.log(json);
            $("#brand").text("");
        }
    });
}