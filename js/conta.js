var funcao = "";
var original = "";
var imagem = {w: 0, h: 0};
var cropper;
var srcFinal = null;

$(document).ready(function() { 
    new dgCidadesEstados({
        estado: $("[name=estado]")[0],
        cidade: $("[name=cidade]")[0],
        estadoVal: '<%=Request("estado") %>',
        cidadeVal: '<%=Request("cidade") %>'
    });

    original = $("#foto img").attr("src");

    $("select[name=area_atuacao] option").each(function(i, elem) {
        $(this).val($(this).text());
    });
    
    $.each(paises, function(i, value) {
        temp = "<option value='"+value+"'>"+value+"</option>"
        
        $("[name=pais]").append(temp);
    });
    
    restaurarInformacoes();
     
    $("[name=data_nascimento]").mask("00/00/0000");
    $("[name=celular]").mask("(00) 00000-0000");
    
    $("#conta form input, #conta form select, #salvar").attr("disabled", true);
    
    filhos = $("#arquivos li");
    
    filhos.sort(function(a, b) {
        // para ordem decrescente; use a - b para crescente
        timeB = $(b).attr("data-time");
        timeA = $(a).attr("data-time");
       // console.log(timeA+" "+timeB);
        return Number(timeB) - Number(timeA);
    });
    
    $("#arquivos ul").children().remove();
    $("#arquivos ul").append(filhos);

    usuario.visibilidade = JSON.parse(usuario.visibilidade);

    $.each(usuario.visibilidade, function(i, value) {
    	if (i=="nome" || i=="cidade" || i=="estado") return true;
    	$("#informacoes-visiveis [name="+i+"]").attr("checked", value);
    })
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

$("#editar").click(function() {
    if (funcao=="") {
        $(this).css({background: "#FF9800"}).text("Cancelar");
        $("#salvar").attr("disabled", false);
        funcao = "editar";
        $("#conta form input, #conta form select").attr("disabled", false);
        $("#conta [name=nome]").focus().select();
        $("#salvar").show();
    } 
    else {
        $(this).css({background: "#5e5882"}).text("Editar");
        $("#salvar").attr("disabled", true);
        funcao = "";
        
        restaurarInformacoes();
        $("#conta form input, #conta form select").attr("disabled", true)
        $("#salvar").hide();
    }
});

$("#salvar").click(function() {
	$("#conta form").submit();
});

$("#conta form").submit(function(e) {
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
    data.funcao = "atualizar";
    
    if (data.celular.length<10) {
        $("[name=celular]").focus();
        chamarPopupInfo("Digite um número válido!");
        return;
    }
    
    if (data.data_nascimento.length<8) {
        $("[name=data_nascimento]").focus();
        chamarPopupInfo("Digite uma data completa!");
        return;
    }
    
    if ($("[name=senha]").val()!=$("#repetir_senha").val()) {
        $("#repetir_senha").focus();
        chamarPopupInfo("Repita a senha corretamente!");
        return;
    }

    dados = new FormData();

    if (imagem.w>0) {
    	data.foto = srcFinal;
        data.larguraImagem = imagem.w;
        data.alturaImagem = imagem.h;
    }

    $.each(data, function(i, value) {
        dados.set(i, value);
    });
    console.log(dados);
    
    atualizarUsuario(dados, true);
});

$("#conta h2 span").click(function() {
    aberto = $(this).attr("data-aberto");
    
    if (aberto == 0) {
        $(this).parent().next().show();
    } else {
        $(this).parent().next().hide();
    }
    
    $(this).attr("data-aberto", (aberto==0?1:0));
});

$("#ver").click(function() {
    $("#informacoes-visiveis").fadeIn().css({display: "flex"});
});

$("#informacoes-visiveis .fechar").click(function() {
    $("#informacoes-visiveis").fadeOut();
});

$("#informacoes-visiveis form").submit(function(e) {
    e.preventDefault();

    data = new FormData();

    $(this).find('input[type=checkbox]').each(function(i, elem) {
        value = $(this).is(":checked");
        data[$(this).attr('name')] = value;
    });

    // console.log(data);
    data.append('id', usuario.id);
    data.append('visibilidade', JSON.stringify(data));
    data.append('funcao', 'atualizar');
    
    $("#informacoes-visiveis .botao").attr('disabled', true);
    atualizarUsuario(data, false, function() {
        $("#informacoes-visiveis .fechar").click();
        $("#informacoes-visiveis .botao").attr('disabled', false);
    });
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
            
            // proporcaoHeight = 200*img.height/img.width;
            
            // if (proporcaoHeight<200) {
            //     chamarPopupInfo("Proporções inválidas. A imagem deve ser quadrada");
            //     limparImagemPerfil();
            //     return;
            // }
        
            // $("#foto img").attr("src", img.src);
            
            // imagem.w = img.width;
            // imagem.h = img.height;
            $imagem = $('#imagem-cortada');
            $imagem.attr('src', img.src);
            $imagem.cropper({
				aspectRatio: 1, 
				viewMode: 2, 
				modal: true, 
				background: false, 
				autoCrop: true, 
				autoCropArea: 1,
				// minCropBoxWidth: 200,
				crop: function(event) {
				}
			});
        	cropper = $imagem.data('cropper');
            $("#cropper").fadeIn().css("display", "flex");
        }

        reader.onload = function (e) {
            img.src = e.target.result;
        }

        reader.readAsDataURL(input.files[0]);
    }
}); 

$("#cropper button").click(function() {
	canvas = cropper.getCroppedCanvas().toDataURL();
	srcFinal = canvas;
	cropper.getCroppedCanvas().toBlob(function(blob) {
		// srcFinal = blob;
	});

	$("#foto img").attr("src", canvas);
	imagem.w = cropper.getCropBoxData().width;
	imagem.h = cropper.getCropBoxData().width;
	cropper.destroy();
	$("#cropper").fadeOut();
});

$("#cropper .fechar").click(function() {
	cropper.destroy();
	$("#cropper").fadeOut();
});

function limparImagemPerfil() {
    $("#foto img").attr("src", original);
    $("#foto input[type=file]").val("");
    
    imagem.w = 0;
    imagem.h = 0;
}

function restaurarInformacoes() {
    $.each(usuario, function(i, value) {
        if (i=="foto_perfil") {
            if (value!=null && value!="") $("#foto img").attr('src', "../../servidor/thumbs-usuarios/"+value);
            return true;
        }

        if (i=='senha') return true;

        $("[name="+i+"]").val(value);
        if (i=="estado") {
            $("[name="+i+"]").change();
            $("[name=cidade]").val(usuario.cidade);
        }
    });

    $("[name=data_nascimento]").mask("00/00/0000");
    $("[name=celular]").mask("(00) 00000-0000");
}

function atualizarUsuario(dados, editar, sucesso) {
    editar = editar || false;
    sucesso = sucesso || false;

    $("#salvar").attr("disabled", true);

    $.ajax({
        url: "../php/handler/usuarioHandler.php",
        type: "post",
        data: dados,
        success: function(result) {
            console.log(result);
            result = JSON.parse(result);
            
            if (result.estado==1) {
                chamarPopupConf(result.mensagem);

                $.each(result.atualizado, function(i, value) {
                    usuario[i] = value;
                });
                
                if (editar!=false) $("#editar").click();
                if (sucesso!=false) sucesso();
            } else if (result.estado==2) {
                chamarPopupConf(result.mensagem);
            } else {
                chamarPopupErro("erro");
            }

            $("#salvar").attr("disabled", false);
        }, 
        error: function(result) {
            console.log(result);
            chamarPopupErro("Houve um erro, tente atualizar a página!");
        },
        cache: false,
        contentType: false,
        processData: false
    });
}