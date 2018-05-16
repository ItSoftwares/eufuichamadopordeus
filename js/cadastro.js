var original = "";
var imagem = {w: 0, h: 0};

$(document).ready(function() {
    $("[name=data_nascimento]").mask("00/00/0000");
    $("[name=celular]").mask("(00) 00000-0000");
    
    original = $("#foto img").attr("src");

    new dgCidadesEstados({
        estado: $("[name=estado]")[0],
        cidade: $("[name=cidade]")[0],
        estadoVal: '<%=Request("estado") %>',
        cidadeVal: '<%=Request("cidade") %>'
    });
    
    $.each(paises, function(i, value) {
        temp = "<option value='"+value+"'>"+value+"</option>"
        
        $("[name=pais]").append(temp);
    });

    $("select[name=area_atuacao] option").each(function(i, elem) {
        $(this).val($(this).text());
    });
    
    $("[name=pais]").val("Brasil")
});

$("[name=pais]").change(function() {
    valor = $(this).val();
    
    if (valor=="Brasil") {
        $("select[name=estado]").show().attr("disabled", false);
        $("select[name=cidade]").show().attr("disabled", false);
        $("input[name=estado]").hide().attr("disabled", true);
        $("input[name=cidade]").hide().attr("disabled", true);
    } else {
        $("input[name=estado]").show().attr("disabled", false);
        $("input[name=cidade]").show().attr("disabled", false);
        $("select[name=estado]").hide().attr("disabled", true);
        $("select[name=cidade]").hide().attr("disabled", true);
    }
});

$("form").submit(function(e) {
    e.preventDefault();
    
    data = $(this).serializeArray();
    temp = {};

    $.each(data, function(i, value) {
        temp[value.name] = value.value;
    });
    data = temp;

    parar = false;

    $.each(data, function(i, value) {
        if (value != "" && value == 0) {
            parar = i;
        }
    });

    if (parar!=false) {
        chamarPopupInfo("Informe algo!");
        $("[name="+parar+"]").focus().select();
        return;
    }

    data.data_nascimento = $("[name=data_nascimento]").cleanVal();
    data.celular = $("[name=celular]").cleanVal();
    // data.cep = $("[name=cep]").cleanVal();
    data.funcao = 'cadastrar';

    if (estado!=0) {
        data.funcao = 'atualizar';
        data.id = estado;
    }
    
    if ($("[name=senha]").val()!=$("#repetir_senha").val()) {
        $("#repetir_senha").focus();
        chamarPopupInfo("Repita a senha corretamente!");
        return;
    }
    
    if (!$("#concordo").is(":checked")) {
        chamarPopupInfo("Você deve concordar com os termos para concluir o cadastro!");
        return;
    }

    dados = new FormData(this);

    $.each(data, function(i, value) {
        dados.set(i, value);
    });

    if (imagem.w>0) {
        dados.append("larguraImagem", imagem.w);
        dados.append("alturaImagem", imagem.h);
    }
    
    $("form button").attr("disabled", true);
    $.ajax({
        url: "php/handler/usuarioHandler.php",
        type: "post",
        data: dados,
        success: function(result) {
            console.log(result);
            result = JSON.parse(result);
            
            if (result.estado==1) {
                chamarPopupConf(result.mensagem);
                $("form")[0].reset();
               // location.href="/paginas/sobre";
                $.ajax({
                    url: "php/testeLogin.php",
                    type: "post",
                    data: data,
                    success: function(result) {
                        result = JSON.parse(result);

                        console.log(result);
            //            return;
                        if(result.estado==1) {
                            location.href="paginas/hoje";
                        } else if(result.estado==2 || result.estado==3) {
                            chamarPopupInfo(result.mensagem);
                            $("form button").attr("disabled", false);
                        } else if (result.estado==4) {
                            location.href="adm/usuarios";
                        } else if (result.estado==10) {
                            location.href="cadastro?estado=completar&id="+result.id;
                        } else {
                            console.log(result);
                            chamarPopupErro("Houve um erro, tente atualizar a página!");
                        }
                    }, 
                    error: function(result) {
                        console.log(result);
                        chamarPopupErro("Houve um erro, tente atualizar a página!");
                    }
                })
            } else if (result.estado==2) {
                chamarPopupInfo(result.mensagem);
            } else {
                chamarPopupErro("erro");
            }
        }, 
        error: function(result) {
            console.log(result);
            chamarPopupErro("Houve um erro, tente atualizar a página!");
        },
        cache: false,
        contentType: false,
        processData: false
    })
});

$("#foto input[type=file]").change(function() {
    input = this;
    
    if (input.files && input.files[0]) {
        if (input.files[0].size>2*1024*1024) {
            chamarPopupInfo("A imagem deve ter até 2Mb");
            limparImagemPerfil();
            return;
        }
        
        var reader = new FileReader();
        var img = new Image();
        
        img.onload = function() {
            if (img.width<200 || img.height<200) {
                chamarPopupInfo("A imagem deve ter pelo menos 200 Pixels");
                limparImagemPerfil();
                return;
            }
            
            proporcaoHeight = 200*img.height/img.width;
            
            if (proporcaoHeight<200) {
                chamarPopupInfo("Proporções inválidas. A imagem deve ser quadrada");
                limparImagemPerfil();
                return;
            }
        
            $("#foto img").attr("src", img.src);
            
            imagem.w = img.width;
            imagem.h = img.height;
        }

        reader.onload = function (e) {
            img.src = e.target.result;
        }

        reader.readAsDataURL(input.files[0]);
    }
}); 

$(".input.normal label").click(function() {
    $("#termos").fadeIn().css({display: "flex"});
});

$("#termos img").click(function() {
    $("#termos").fadeOut();
});

function limparImagemPerfil() {
    $("#foto img").attr("src", original);
    $("#foto input[type=file]").val("");
    
    imagem.w = 0;
    imagem.h = 0;
}