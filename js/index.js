var esqueceu = 0;

$("#login #linha a, #esqueceu img").click(function() {
    if (esqueceu == 0) {
        $("#esqueceu").fadeIn().css({display: "flex"});
    } else {
        $("#esqueceu").fadeOut();
    }
    
    esqueceu = esqueceu==0?1:0;
});

$("#login form").submit(function(e) {
    e.preventDefault();
    
    data = $(this).serialize();
    $("form button").attr("disabled", true);
    
   // console.log(data)
   // return;
    
    $.ajax({
        url: "php/testeLogin.php",
        type: "post",
        data: data,
        success: function(result) {
            result = JSON.parse(result);
            
            console.log(result);
           // return;
            if(result.estado==1) {
                location.href="paginas/hoje";
            } else if(result.estado==2 || result.estado==3) {
                chamarPopupInfo(result.mensagem);
                $("form button").attr("disabled", false);
            } else if (result.estado==4) {
                location.href="adm/participantes";
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
}); 

$("#esqueceu form").submit(function(e) {
    e.preventDefault();
     
    data = $(this).serialize();
    $("#esqueceu button").attr("disabled", true);
    $.ajax({
        url: "php/recuperarSenha.php",
        type: "post",
        data: data,
        success: function(result) {
            result = JSON.parse(result);
            console.log(result);
           // return;
            if(result.estado==1) {
                chamarPopupConf(result.mensagem);
                $("#esqueceu form")[0].reset();
                $("#esqueceu button").attr("disabled", false);
                $("#esqueceu").fadeOut();
            } else if (result.estado==2) {
                chamarPopupInfo(result.mensagem);
            } else {
                console.log(result)
                chamarPopupErro("Houve um erro, tente atualizar a página!");
            }
        }, 
        error: function(result) {
            console.log(result);
            chamarPopupErro("Houve um erro, tente atualizar a página!");
        }
    })
}); 