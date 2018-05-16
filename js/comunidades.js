var funcao = "nova";
var index;
var participanteId;

$(document).ready(function() {
	$("select[name=area_atuacao] option").each(function(i, elem) {
        $(this).val($(this).text());
    });

	$("#inicio select[name=area_atuacao]").val(area_atuacao);
	$("#inicio select[name=atua_como]").val(atua_como);

	// if (postagens.length==0)

	temp = {};
	$.each(respostas, function(i, value) {
		if (!(value.id_postagem in temp)) temp[value.id_postagem] = [];

		temp[value.id_postagem].push(value);
	});
	respostas = temp;

	$.each(mensagens_respostas, function(i, value) {
		temp = carregarRespostas(value);

		$(".mensagem[data-id="+value.id_referencia+"] .fez").after(temp);
		$(".mensagem[data-id="+value.id_referencia+"] form").hide();
	});

	$.each(mensagens_enviadas, function(i, value) {
		temp = carregarRespostas(value, "Sua mensagem");

		$(".mensagem[data-referencia="+value.id+"] .fez").before(temp);
		$(".mensagem[data-referencia="+value.id+"] form").hide();
	});
});

$("#inicio nav select").change(function() {
	area_atuacao = $("#inicio [name=area_atuacao]").val();
	atua_como = $("#inicio select[name=atua_como]").val();

	location.href = "/paginas/comunidades/"+area_atuacao+"/"+atua_como;
});

$(document).on('click', "li.postagem h3", function(e) {
	e.preventDefault();

	pai = $(this).closest('li.postagem');

	if ($(this).closest('li.postagem').hasClass('fechada')) {
		$("li.postagem:not(.sub):not(.fechada)").addClass('fechada');

		pai.removeClass('fechada');
		pai.find('> .resumo p').text(postagens[pai.attr('data-index')].texto);

		if (!pai.hasClass('carregada')) {
			index = postagens[pai.attr('data-index')].id;

			$.each(respostas[index], function(i, value) {
				temp = adicionarReposta(value);

				pai.find('ul').prepend(temp);
			});

			pai.addClass('carregada');
		}
	} else {
		pai.addClass('fechada');

		texto = postagens[pai.attr('data-index')].texto;
		texto = texto.length>300?texto.substr(0, 300)+"...":"";

		pai.find('> .resumo p').text(texto);
	}
});

$("#nova").click(function() {
	$("#nova-postagem h3").text('Nova Postagem');
	$("#nova-postagem").fadeIn().css({display: 'flex'});
	funcao = 'nova';
});

$("#inbox").click(function() {
	$("#participante").hide();
	$("#mensagens").show();
	$("#postagens").hide();
});

$("#nova-postagem .fechar").click(function() {
	$("#nova-postagem").fadeOut();
	$("#nova-postagem form")[0].reset();
});

$("#nova-postagem form").submit(function(e) {
	e.preventDefault();

	data = $(this).serializeArray();
	temp = {};
	$.each(data, function(i, value) {
		temp[value.name] = value.value;
	});
	data = temp;

	data.funcao = funcao;
	if (funcao == 'nova') {
		data.area_atuacao = area_atuacao;
		data.atua_como = atua_como;
		data.id_usuario = usuario.id;
	} else {
		data.id = postagens[index].id;
	}

	$("#nova-postagem button").attr('disabled', true);
	$.ajax({
        url: "../php/handler/postagemHandler.php",
        type: "post",
        data: data,
        success: function(result) {
            result = JSON.parse(result);
            console.log(result);

            if (result.estado==1) {
            	chamarPopupConf(result.mensagem);

            	if (funcao=='nova') {
            		index = postagens.push(result.postagem) - 1;
	            	adicionarPostagem(result.postagem, index);
            	} else {
            		postagens[index].titulo = result.postagem.titulo;
            		postagens[index].texto = result.postagem.texto;

					texto = result.postagem.texto;
					texto = texto.length>300?texto.substr(0, 300)+"...":"";
            		$("li.postagem[data-index="+index+"] > .resumo h3").html(result.postagem.titulo+ "<span>("+postagens[index].respostas+" Respostas)</span>");
					$("li.postagem[data-index="+index+"] > .resumo p").text(texto);
            	}

	            $("#nova-postagem .fechar").click();
	            $("#nova-postagem form")[0].reset();
				$("#nova-postagem button").attr('disabled', false);
            } else {
            	chamarPopupErro("Houve um erro, tente atualizar a página!");
            }
        }, 
        error: function(result) {
            console.log(result);
            chamarPopupErro("Houve um erro, tente atualizar a página!");
        }
    });
});

$(document).on('submit', "form.resposta", function(e) {
	e.preventDefault();

	pai = $(this).closest('ul');

	data = $(this).serializeArray();
	temp = {};
	$.each(data, function(i, value) {
		temp[value.name] = value.value;
	});
	data = temp;

	data.funcao = 'responder';
	data.id_usuario = usuario.id;

	console.log(data);

	$(this).find('button').attr('disabled', true);

	$.ajax({
        url: "../php/handler/postagemHandler.php",
        type: "post",
        data: data,
        success: function(result) {
            result = JSON.parse(result);
            console.log(result);

            if (result.estado==1) {
            	chamarPopupConf(result.mensagem);
            	resposta = result.resposta;
	            if (!respostas.hasOwnProperty(resposta.id_postagem)) respostas[resposta.id_postagem] = [];
	            respostas[resposta.id_postagem].push(resposta);

	            resposta.foto_perfil = usuario.foto_perfil;
	            resposta.nome = usuario.nome;
	            resposta.estado = usuario.estado;
	            resposta.cidade = usuario.cidade;

	            temp = adicionarReposta(resposta);

	            pai.prepend(temp);

	            pai.find('form')[0].reset();
	            $(this).find('button').attr('disabled', false);
            }
        }, 
        error: function(result) {
            console.log(result);
            chamarPopupErro("Houve um erro, tente atualizar a página!");
        }
    })
});

$(document).on('click', ".menu img", function() {
	$(this).parent().find('div').fadeToggle();
});

$(document).on('click', ".menu .editar", function() {
	$("#nova").click();
	$("#nova-postagem h3").text('Editar Postagem');
	funcao = 'atualizar';
	index = $(this).closest('li.postagem').attr('data-index');
	$("#nova-postagem [name=titulo]").val(postagens[index].titulo);
	$("#nova-postagem [name=texto]").val(postagens[index].texto);

	$(this).closest('.menu').find('img').click();
});

$(document).on('click', ".menu .excluir", function() {
	index = $(this).closest('li.postagem').attr('data-index');

	data = {};
	data.funcao = 'excluir';
	data.id = postagens[index].id;

	$(this).closest('.menu').find('img').click();
	
	$.ajax({
        url: "../php/handler/postagemHandler.php",
        type: "post",
        data: data,
        success: function(result) {
            result = JSON.parse(result);
            console.log(result);

            if (result.estado==1) {
            	chamarPopupConf(result.mensagem);

            	$("li.postagem[data-index="+index+"]").remove();
            } else {
            	chamarPopupErro("Houve um erro, tente atualizar a página!");
            }
        }, 
        error: function(result) {
            console.log(result);
            chamarPopupErro("Houve um erro, tente atualizar a página!");
        }
    });
});

$(document).on('click', 'a.criador', function(e) {
	e.preventDefault();

	id = $(this).attr("data-id");

	if (participanteId==id) return;

	$("#participante *").remove();
	$("#participante").append("<p class='aviso'>Carregando...</p>");
	
	$("#participante").load("/paginas/participante/"+id).show();
	$("#mensagens").hide();
	$("#postagens").hide();
});

$(document).on('click', '.voltar', function(e) {
	$("#participante").hide();
	$("#postagens").show();
	$("#mensagens").hide();
});

$(document).on('submit', '#perfil form', function(e) {
	e.preventDefault();

	data = {};
	data.texto = $(this).find('[name=texto]').val();
	data.id_de = usuario.id;
	data.id_para = participante.id;

	if (data.id_de==data.id_para) {
		chamarPopupInfo("Não pode enviar uma mensagem para si mesmo!");
		return;
	}

	adicionarMensagem(data);
});

$(document).on('submit', '.mensagem form', function(e) {
	e.preventDefault();

	data = {};
	data.texto = $(this).find('[name=texto]').val();
	data.id_referencia = $(this).find('[name=id_referencia]').val();
	data.id_para = $(this).find('[name=id_para]').val();
	data.id_de = usuario.id;

	if (data.id_de==data.id_para) {
		chamarPopupInfo("Não pode enviar uma mensagem para si mesmo!");
		return;
	}

	adicionarMensagem(data, $(this).closest('.mensagem'));
});

function adicionarPostagem(post, index) {
	temp = "";

	temp += "<li class='postagem fechada' data-index='"+index+"'>";
    temp += "<div class='resumo'>";
    temp += "<h3>"+post.titulo+" <span>(0 Respostas)</span></h3>";

    texto = post.texto;
    texto = texto.length>300?texto.substr(0, 300)+"...":"";

    temp += "<p>"+texto+"</p>";
    temp += "</div>";
    temp += "<a class='criador' href='/paginas/participante/"+usuario.id+"' data-id="+resposta.id_usuario+">";
    if (usuario.foto_perfil==null || usuario.foto_perfil=="")
    	temp += "<img src='../../img/foto_perfil.png'>";
    else
    	temp += "<img src='../../servidor/thumbs-usuarios/"+usuario.foto_perfil+"'>";
    temp += "<div>";
    temp += "<h4>"+usuario.nome.split(" ")[0]+"</h4>";
    temp += "<p>"+usuario.cidade+"/"+usuario.estado+"</p>";
    temp += "</div>";
    temp += "</a>";

    temp += "<div class='menu'>";
    temp += "<img src='../../img/menu2.png'>";
    temp += "<div>";
    temp += "<span class='editar'>Editar</span>";
    temp += "<span class='excluir'>Excluir</span>";
    temp += "</div>";
    temp += "</div>";

	temp += "<ul>";

	temp += "<form class='resposta'>";
    temp += "<input type='hidden' name='id_postagem' value='"+index+"'>";
    temp += "<div class='input'>";
    temp += "<textarea name='texto' placeholder='Escreva algo' required></textarea>";
    temp += "</div>";
    temp += "<button class='botao'>Responder</button>";
    temp += "</form>";

	temp += "</ul>";
	temp += "</li>";

	$("article > ul").prepend(temp);
}

function adicionarReposta(resposta) {
	temp = "";

	temp += "<li class='postagem sub'>";
    temp += "<div class='resumo'>";
    temp += "<h4>"+resposta.nome+".<span>"+resposta.cidade+"/"+resposta.estado+"</span></h4>";
    temp += "<p class='comentario'>"+resposta.texto+"</p>";
    temp += "</div>";
    temp += "<a class='criador' href='/paginas/participante/"+resposta.id_usuario+"' data-id="+resposta.id_usuario+">";
    if (resposta.foto_perfil==null || resposta.foto_perfil=="")
    	temp += "<img src='../../img/foto_perfil.png'>";
    else
    	temp += "<img src='../../servidor/thumbs-usuarios/"+resposta.foto_perfil+"'>";
    temp += "</a>";
    temp += "</li>";

    return temp;
}

function atualizarParticipante() {
	$.each(participante, function(i, value) {
		$("#participante [name="+i+"]").val(value);
	});

	$("[name=data_nascimento]").mask("00/00/0000");
    $("[name=celular]").mask("(00) 00000-0000");
}

function adicionarMensagem(dados, elem) {
	elem = elem || 0;
	dados.funcao = "mensagem";

	$("#perfil form button, #mensagens .mensagem form button").attr('disabled', true);
	$.ajax({
        url: "../php/handler/usuarioHandler.php",
        type: "post",
        data: dados,
        success: function(result) {
            result = JSON.parse(result);
            console.log(result);

            if (result.estado==1) {
            	chamarPopupConf(result.mensagem);

            	if (elem!=0) {
            		temp = "";
            		msg = result.msg;

            		temp += "<a class='eu criador' data-id="+usuario.id+">";
            		if (usuario.foto_perfil==null || usuario.foto_perfil=="")
				    	temp += "<img src='../../img/foto_perfil.png'>";
				    else
				    	temp += "<img src='../../servidor/thumbs-usuarios/"+usuario.foto_perfil+"'>";
            		temp += "</a>";
            		temp += "<h4>Sua resposta</h4>";
            		temp += "<p>"+msg.texto+"</p>";
            		temp += "</div>";
            		temp += "</div>";

            		elem.find('.fez').after(temp);
            		elem.find('form').hide();
            	}
            	
				$("#perfil form button, #mensagens .mensagem form button").attr('disabled', false);
				$("#perfil form, #mensagens .mensagem form").each(function(i, elem) {
					$(this)[0].reset();
				});
            } else {
            	chamarPopupErro("Houve um erro, tente atualizar a página!");
            }
        }, 
        error: function(result) {
            console.log(result);
            chamarPopupErro("Houve um erro, tente atualizar a página!");
        }
    });
}

function carregarRespostas(msg, nome) {
	nome = nome || "Sua Reposta";
	temp = "";
	temp += "<a class='eu criador' data-id="+usuario.id+">";
	if (usuario.foto_perfil==null || usuario.foto_perfil=="")
    	temp += "<img src='../../img/foto_perfil.png'>";
    else
    	temp += "<img src='../../servidor/thumbs-usuarios/"+usuario.foto_perfil+"'>";
	temp += "</a>";
	temp += "<h4>"+nome+"</h4>";
	temp += "<p>"+msg.texto+"</p>";
	temp += "</div>";
	temp += "</div>";
	
	return temp;
}