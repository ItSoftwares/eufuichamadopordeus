var marcado;
var idEncontro=0;
var pagina = 1;
var inscritos = 0;
 
$(document).ready(function() {
    temp = {};
    $.each(encontros, function(i, value) {
        temp[value.id] = []; 
        encontros[i].inscritos = Number(encontros[i].inscritos);
        encontros[i].presentes = Number(encontros[i].presentes);
    });
    
    inscritos = inscricoes;
    $.each(inscricoes, function(i, value) {
        temp[value.id_encontro].push(value.id_usuario);
    });
    inscricoes = temp;
    
    temp = {};
    $.each(inscritos, function(i, value) {
        temp[value.id_encontro+"."+value.id_usuario] = value;
    });
    inscritos = temp;
    
    temp = {};
    $.each(erros, function(i, value) {
        if (!(value.id_encontro in temp)) temp[value.id_encontro] = [];
        temp[value.id_encontro].push(value);
    });
    erros = temp;
    
    temp = {};
    $.each(quero, function(i, value) {
        if (!(value.id_encontro in temp)) temp[value.id_encontro] = [];
        temp[value.id_encontro].push(value);
    });
    quero = temp;
    
    atualizarUsuarios();
    
    carregarInscritos();
    carregarPresentes();
    carregarErros();
    carregarQuero();
    
    $("[data-nome=Telefone]").mask("(00) 00000-0000");
    
    if (encontros.length==0) encontros = {};
}); 

$("#botao-novo").click(function() {
    $("#novo").fadeIn().css({display: "flex"});
});

$("#novo img").click(function() {
    $("#novo").fadeOut();
});

$(document).on("click", ".ver img.editar:not(.no)", function() {
    idEncontro = $(this).parent().parent().attr("data-id");
    atualizarUsuarios();
    $("#gerenciar h3 > span:nth-child(2)").text(encontros[idEncontro].nome);
    $("body").toggleClass("hidden");
    $("#gerenciar").fadeIn().css({display: "flex"});
});

$(document).on("click", "#gerenciar .inscrever", function() {
    data = {};
    
    data.funcao = "inscrever";
    data.id_usuario = $(this).parent().parent().attr("data-id");
    data.id = idEncontro;
    este = this;
//    if ()
    
    buscaId = $("#gerenciar [name=id]").val();
    buscaNome = $("#gerenciar [name=nome]").val();
    
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
                chamarPopupConf(result.mensagem, 2000);
                
                inscritos[data.id+"."+data.id_usuario].presente=1
                
                encontros[idEncontro].presentes++;
                $("#encontros tr[data-id="+idEncontro+"] td:nth-child(5)").text(encontros[idEncontro].presentes);
                $("#encontros tr[data-id="+idEncontro+"] td.ver .gerar").removeClass("no"); 
                atualizarUsuarios();

                if (buscaId.length==0) buscaId = false;
                if (buscaNome.length==0) buscaNome = false;

                pesquisarUsuario(buscaId, buscaNome);
                carregarPresentes();
//                $(este).attr("disabled", false);
            } else if (result.estado==2) {
                chamarPopupInfo(result.mensagem);
//                $(".botao.inscrever").attr("disabled", false);
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

$("#gerenciar > img").click(function() {
    if (marcado) return;
    
    $("#gerenciar").fadeOut();
    $("body").toggleClass("hidden");
});

$(document).on("click", "#gerenciar .pagar", function() {
    data = {};
    
    data.funcao = "pagar";
    data.id_usuario = $(this).parent().parent().attr("data-id");
    data.id = idEncontro;
    
    console.log(data);
    $(".botao.inscrever").attr("disabled", true);
    $.ajax({ 
        url: "../php/adm/manterEncontro.php",
        type: "post",
        data: data, 
        success: function(result) {
            console.log(result);
            result = JSON.parse(result);

            if (result.estado==1) {
                chamarPopupConf(result.mensagem, 2000);
                
                inscricoes[idEncontro].push(data.id_usuario);
                inscritos[data.id+"."+data.id_usuario] = result.inscricao;
//                pagamentos[result.pagamento.id] = result.pagamento;
                encontros[idEncontro].inscritos++;
                $("#encontros tr[data-id="+idEncontro+"] td:nth-child(4)").text(encontros[idEncontro].inscritos)
                atualizarUsuarios();
                
                buscaId = $("#gerenciar [name=id]").val();
                buscaNome = $("#gerenciar [name=nome]").val();

                if (buscaId.length==0) buscaId = false;
                if (buscaNome.length==0) buscaNome = false;

                pesquisarUsuario(buscaId, buscaNome);
                carregarInscritos();
                $("#novo button").attr("disabled", false);
                
                $("#gerenciar form")[0].reset();
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
        }
    });
});
 
$(document).on("click", ".gerar:not(.no)", function() { 
    tempId = $(this).parent().parent().attr("data-id");
    botao = $(this);
    data = {};
    
    data.funcao = "finalizar";
    data.id = tempId;
    
    chamarConfirmacao("Ao gerar os certificados você não poderá inscrever nenhum outro usuário.", function() {
        $.ajax({
            url: "../php/adm/manterEncontro.php",
            type: "post",
            data: data, 
            success: function(result) {
                console.log(result);
                result = JSON.parse(result);

                if (result.estado==1) {
                    chamarPopupConf(result.mensagem);
                    encontros[tempId].finalizado = 1;

                    botao.addClass("no").attr("title", "Esse encontro ja foi finalizado e os certificados foram gerados.");
                    botao.parent().find(".editar").addClass("no");
                } else if (result.estado==2) {
                    chamarPopupInfo(result.mensagem);
                } else {
                    chamarPopupErro(result.mensagem);
                }
                $("img#confirmacao-fechar").click();
            }, 
            error: function(result) {
                console.log(result);
                chamarPopupErro("Houve um erro, tente atualizar a página!");
            }
        });
    }, function() {
        $("img#confirmacao-fechar").click();
    });
});

$("#travar input").change(function() {
    marcado = $(this).is(":checked");
});

$("#novo form").submit(function(e) {
    e.preventDefault();
    
    if (validarHora($("[name=hora_inicio]").val())) {
        chamarPopupInfo("Digite uma hora válida!");
        $("[name=hora_inicio]").focus();
        return;
    }
    
    form = this;
    data = $(this).serializeArray();
    
    temp={};
    $.each(data, function(i, value) {
        temp[value.name]=value.value;
    });
    data = temp;
    
    data.funcao = "novo";
    
    $("#novo button").attr("disabled", true);
    
    $.ajax({
        url: "../php/adm/manterEncontro.php",
        type: "post",
        data: data, 
        success: function(result) {
            console.log(result);
            result = JSON.parse(result);

            if (result.estado==1) {
                agora = (new Date().getTime()/1000).toFixed(0)
                chamarPopupConf(result.mensagem);
                encontro = result.encontro;
                encontro.inscritos=0;
                encontro.presentes=0;
                inscricoes[encontro.id] = [];
                encontros[encontro.id] = encontro;
                temp = "";
                
                temp+="<tr data-id='"+encontro.id+"' data-time='"+encontro.data+"'>";
                temp+="<td data-nome='ID'>"+encontro.id+"</td>";
                temp+="<td data-nome='Encontro'>"+encontro.nome+"</td>";
                temp+="<td data-nome='Data'>"+toDate(encontro.data)+"</td>";
                temp+="<td data-nome='Inscritos'>0</td>";
                temp+="<td data-nome='Presentes'>0</td>";
                temp+="<td data-nome='Açoes' class='ver'>";
                temp+="<img src='../img/menu.png' class='editar'>"
                temp+="<img src='../img/certificado.png' class='gerar no'>"
                temp+="<img src='../img/usuarios.png' class='inscritos'>"
                temp+="<img src='../img/input-mark.png' class='presentes'>"
                temp+="</td></tr>"
                
                temp+="<tr data-refer='"+encontro.id+"' class='lista-incritos' data-time='"+(Number(encontro.data)+1)+"'><td colspan='6'><table>";
                temp+="<tr><th>Participante Inscrito</th><th>Onde Mora</th><th>Data da Inscrição</th></tr>"
                temp+="</table></td></tr>";
                
                temp+="<tr data-refer='"+encontro.id+"' class='lista-presentes' data-time='"+(Number(encontro.data)+2)+"'><td colspan='6'><table>";
                temp+="<tr><th>Participante Inscrito</th><th>Onde Mora</th><th>Data da Inscrição</th></tr>"
                temp+="</table></td></tr>";
                
                $("#encontros table#principal").append(temp);
                
                filhos = $("#encontros table#principal > tbody > tr:not(:first-child)");
    
                filhos.sort(function(a, b) {
                    // para ordem decrescente; use a - b para crescente
                    timeB = $(b).attr("data-time");
                    timeA = $(a).attr("data-time");
            //        console.log(timeA+" "+timeB);
                    return Number(timeA) - Number(timeB);
                });

//                console.log(filhos);

                $("#encontros table#principal > tbody > tr:not(:first-child)").remove();
                $("#encontros table").append(filhos);
                
                cont = 1;
                last = cont;
                $("#encontros table#principal > tbody > tr:not(:first-child)").each(function(i, elem) {
                    if ($(this).hasClass("lista-inscritos") || $(this).hasClass("lista-presentes")) {
                        $(elem).attr("data-pagina", last);
                        return true;
                    }
                    pag = Math.ceil(cont/10);
                    console.log(pag);
                    $(elem).attr("data-pagina", pag);
                    cont++;
                    last = cont;
                });
                
                $("#paginas li[data-pagina="+pagina+"]").click();
                
                form.reset();
                $("#novo").fadeOut();
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
        }
    });
})

$("#gerenciar form").submit(function(e) {
    e.preventDefault();
    
    buscaId = $("#gerenciar [name=id]").val();
    buscaNome = $("#gerenciar [name=nome]").val();
    
    if (buscaId.length==0) buscaId = false;
    if (buscaNome.length==0) buscaNome = false;
    
    qtd = pesquisarUsuario(buscaId, buscaNome);
    
    if (qtd==0) chamarPopupInfo("Participante Inexistente!", 3000)
});

$(document).on("change", "input[name=contatado]", function() {
    checado = $(this).is(":checked")?1:0;
    id_temp = $(this).attr("data-id");
    banco = $(this).attr("data-banco");
    
    data = {funcao: "extra_atualizar", id: id_temp, contatado: checado, banco: banco};
    $(this).attr("disabled", true);
    input = $(this);
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
                
                input.attr("disabled", false);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                chamarPopupErro("Desculpe, houve um erro, por favor atualize a pÃ¡gina ou nos contate.");
                console.log(XMLHttpRequest);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
});

$(document).on("click", "#paginas li", function() {
    if ($(this).hasClass("selecionado")) return;
    $(".ver .marcado").removeClass("marcado")
    $("#paginas li").removeClass("selecionado");
    $(this).addClass("selecionado");
    
    pagina = $(this).attr("data-pagina");
    
    $("#encontros table#principal > tbody > tr:not(:first-child)").hide();
    
    $("#encontros tr[data-pagina="+pagina+"]:not([data-refer])").show();
    
    paginar();
     
    console.log(pagina)
});

$(document).on("click", "table tr td.ver img.inscritos", function() {
    $(this).parent().find("img:not(.inscritos)").removeClass("marcado");
    
    $(this).toggleClass("marcado");
    id_encontro = $(this).closest("tr").attr("data-id");
    
    $("tr[data-refer="+id_encontro+"].lista-presentes").hide();
    $("tr[data-refer="+id_encontro+"].lista-erros").hide();
    $("tr[data-refer="+id_encontro+"].lista-quero").hide();
    $("tr[data-refer="+id_encontro+"].lista-inscritos").toggle();
});

$(document).on("click", "table tr td.ver img.presentes", function() {
    $(this).parent().find("img:not(.presentes)").removeClass("marcado");
    
    $(this).toggleClass("marcado");
    id_encontro = $(this).closest("tr").attr("data-id");
    
    $("tr[data-refer="+id_encontro+"].lista-inscritos").hide();
    $("tr[data-refer="+id_encontro+"].lista-erros").hide();
    $("tr[data-refer="+id_encontro+"].lista-quero").hide();
    $("tr[data-refer="+id_encontro+"].lista-presentes").toggle();
});

$(document).on("click", "table tr td.ver img.erros", function() {
    $(this).parent().find("img:not(.erros)").removeClass("marcado");
    
    $(this).toggleClass("marcado");
    id_encontro = $(this).closest("tr").attr("data-id");
    
    $("tr[data-refer="+id_encontro+"].lista-inscritos").hide();
    $("tr[data-refer="+id_encontro+"].lista-presentes").hide();
    $("tr[data-refer="+id_encontro+"].lista-quero").hide();
    $("tr[data-refer="+id_encontro+"].lista-erros").toggle();
});

$(document).on("click", "table tr td.ver img.quero", function() {
    $(this).parent().find("img:not(.quero)").removeClass("marcado");
    
    $(this).toggleClass("marcado");
    id_encontro = $(this).closest("tr").attr("data-id");
    
    $("tr[data-refer="+id_encontro+"].lista-inscritos").hide();
    $("tr[data-refer="+id_encontro+"].lista-presentes").hide();
    $("tr[data-refer="+id_encontro+"].lista-erros").hide();
    $("tr[data-refer="+id_encontro+"].lista-quero").toggle();
});

function toDate(time) {
    data = new Date(time*1000);
    return colocarZero(data.getDate())+"/"+colocarZero(data.getMonth()+1)+"/"+data.getFullYear()+", "+colocarZero(data.getHours())+":"+colocarZero(data.getMinutes());;
}

function colocarZero(numero) {
    if (numero<10) return "0"+numero;
    
    return numero;
}

function atualizarUsuarios() {
    $("#gerenciar table tr:not(:first-child)").remove();
    
    $.each(usuarios, function(i, value) {
        temp = "<tr data-id="+value.id+">";
        value.inscrito = false;
        paga = false;
        temp += "<td data-nome='ID'>"+value.id+"</td>";
        temp += "<td data-nome='Nome'>"+value.nome+"</td>";
        temp += "<td data-nome='Email'>"+value.email+"</td>";
        if (idEncontro!=0 && inscricoes[idEncontro].indexOf(value.id)!=-1) {
            temp += "<td data-nome='Inscrição'><button class='botao paga'>Paga</button></td>";
            paga = true;
        } else {
            temp += "<td data-nome='Inscrição'><button class='botao pagar'>Pendente</button></td>";
        }
        if (idEncontro!=0 && inscricoes[idEncontro].indexOf(value.id)!=-1 && idEncontro+"."+value.id in inscritos && inscritos[idEncontro+"."+value.id].presente==1) {
            temp += "<td data-nome='Presente'><button class='botao' disabled>Confirmado</button></td>";
        } else {
            if (paga)
                temp += "<td data-nome='Presente'><button class='botao inscrever'>Confirmar</button></td>";
            else
                temp += "<td data-nome='Presente'><button class='botao inscrever' disabled>Confirmar</button></td>";
        }
        
        temp += "</tr>";
        
        $("#gerenciar table").append(temp);
    });
    
    pesquisarUsuario(-1, -1);
}

function validarHora(hora) {
    hora = hora.split(":");
    
    if (hora[0]>23 || hora[1]>59) {
        return true;
    }
    
    return false;
}

function pesquisarUsuario(buscaId, buscaNome) {
    this.buscaId = this.buscaId || false;
    this.buscaNome = this.buscaNome || false;
    
    if ((this.buscaId==-1 && this.buscaNome==-1)) {
        $("#gerecniar tr:not(:first-child)").hide();
        return;
    }
    
    idHide=[];
    qtd = 0;
    if (buscaId!=false || buscaNome!=false) {
        $.each(usuarios, function(i, value) {
            if (buscaId!=false) {
                if (value.id.indexOf(buscaId)!=-1) {
                    $("#gerenciar tr[data-id="+value.id+"]").show();
                    qtd++;
                } else {
                    $("#gerenciar tr[data-id="+value.id+"]").hide();
                    idHide.push(value.id);
                }
            }
            
            if (buscaNome!=false && idHide.indexOf(value.id)==-1) {
                if (value.nome.toLowerCase().indexOf(buscaNome.toLowerCase())!=-1) {
                    $("#gerenciar tr[data-id="+value.id+"]").show();
                    qtd++;
                } else {
                    $("#gerenciar tr[data-id="+value.id+"]").hide();
                }
            } 
        });
    } else {
        $("#gerenciar tr:not(:first-child)").hide();
    }
    
    return qtd;
}

function carregarInscritos() {
    $("tr[data-refer].lista-inscritos").each(function(i , elem) {
        $(this).find("table tr:not(:first-child)").remove();
        id_refer = $(this).attr("data-refer");
        temp = "";
        $.each(inscricoes[id_refer], function(j, value) {
            insc = inscritos[id_refer+"."+value];
            
            temp +="<tr>";
            temp +="<td data-nome='Inscrito'>"+usuarios[insc.id_usuario].nome+"</td>";
            temp +="<td data-nome='Onde Mora'>"+usuarios[insc.id_usuario].cidade+", "+usuarios[insc.id_usuario].estado+"</td>";
            temp +="<td data-nome='Data Inscrição'>"+toDate(insc.time)+"</td>";
            temp +="</tr>";
        })
        $(this).find("table").append(temp);
    })
}

function carregarPresentes() {
    $("tr[data-refer].lista-presentes").each(function(i , elem) {
        $(this).find("table tr:not(:first-child)").remove();
        id_refer = $(this).attr("data-refer");
        temp = "";
        $.each(inscricoes[id_refer], function(j, value) {
            insc = inscritos[id_refer+"."+value];
            if (insc.presente==0) return true;
            
            temp +="<tr>";
            temp +="<td data-nome='Presente'>"+usuarios[insc.id_usuario].nome+"</td>";
            temp +="<td data-nome='Onde Mora'>"+usuarios[insc.id_usuario].cidade+", "+usuarios[insc.id_usuario].estado+"</td>";
            temp +="<td data-nome='Data Inscrição'>"+toDate(insc.time)+"</td>";
            temp +="</tr>";
        })
        $(this).find("table").append(temp);
    })
}

function carregarErros() {
    $("tr[data-refer].lista-erros").each(function(i , elem) {
        id_refer = $(this).attr("data-refer");
        temp = "";
        $.each(erros[id_refer], function(j, value) {
            insc = value.id_usuario;
            checado = value.contatado==1?"checked":"";
            temp +="<tr>";
            temp +="<td data-nome='Já fiz contato'><input type='checkbox' name='contatado' data-banco='erro_pagamento' data-id='"+value.id+"' "+checado+"></td>";
            temp +="<td data-nome='Participante'>"+usuarios[insc].nome+"</td>";
            temp +="<td data-nome='Emal'>"+usuarios[insc].email+"</td>";
            temp +="<td data-nome='Telefone'>"+usuarios[insc].celular+"</td>";
            temp +="<td data-nome='Onde Mora'>"+usuarios[insc].cidade+", "+usuarios[insc].estado+"</td>";
            temp +="</tr>";
        })
        $(this).find("table").append(temp);
    })
}

function carregarQuero() {
    $("tr[data-refer].lista-quero").each(function(i , elem) {
        id_refer = $(this).attr("data-refer");
        temp = "";
        $.each(quero[id_refer], function(j, value) {
            insc = value.id_usuario;
            checado = value.contatado==1?"checked":"";
            temp +="<tr>";
            temp +="<td data-nome='Já fiz contato'><input type='checkbox' name='contatado' data-banco='quero_ir' data-id='"+value.id+"' "+checado+"></td>";
            temp +="<td data-nome='Participante'>"+usuarios[insc].nome+"</td>";
            temp +="<td data-nome='Emal'>"+usuarios[insc].email+"</td>";
            temp +="<td data-nome='Telefone'>"+usuarios[insc].celular+"</td>";
            temp +="<td data-nome='Onde Mora'>"+usuarios[insc].cidade+", "+usuarios[insc].estado+"</td>";
            temp +="</tr>";
        })
        $(this).find("table").append(temp);
    });
}

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