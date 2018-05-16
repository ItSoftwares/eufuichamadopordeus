var base;
var imagemPrincipal;
var CANVAS = [];
var contador;
var index = 0;
var paginas = 0;
var temp = [];

$(document).ready(function() {
    String.prototype.replaceAll = String.prototype.replaceAll || function(needle, replacement) {
        return this.split(needle).join(replacement);
    };
    
    $("#texto").html(colocarTags($("#texto").text()));
    $("#loading").show().css({display: "flex"});
//    console.log($(".conteudo img").height());
//    gerarPaginas();
    $('.conteudo[data-pagina=1] img').each(function() {
        if( this.complete ) {
            gerarPaginas.call( this );
        } else {
            $(this).one('load', gerarPaginas);
        }
    });
});

$("#pdf").click(function() {
    savePDF();
});

$("#salvar").click(function() {
    $("#loading").fadeIn().css({display: "flex"});
    
    data = {versao: conteudo.versao, titulo: conteudo.titulo, base: base};
    
    $.ajax({
        url: "../php/salvarArquivo.php",
        type: "post",
        data: data,
        success: function(result) {
//            console.log(result);
            result = JSON.parse(result);
            
            if (result.estado==1) {
                chamarPopupConf(result.mensagem);
            } else if (result.estado==2) {
                chamarPopupInfo(result.mensagem);
            } else {
                chamarPopupErro("erro");
            }
            $("#loading").fadeOut();
        }, 
        error: function(result) {
            console.log(result);
            chamarPopupErro("Houve um erro, tente atualizar a página!");
            $("#loading").fadeOut();
        }
    })
});

function colocarTags(texto) {
    texto = texto.replaceAll('[', '<');
    texto = texto.replaceAll(']', '>');
    texto = texto.replaceAll("\n","<br>");
    return texto;
} 

function gerarCanvas() {
    contador=2;
    index=1;
    for (var i=1; i<=$(".conteudo").length; i++) {
        este = $(".conteudo[data-pagina="+i+"]")[0];
        html2canvas(este, {
            useCORS: true,
            allowTaint: true,
            letterRendering: true,
            scale: 2,
            logging: false
        }).then(function(canvas) {
            theCanvas = canvas;
            var ctx = theCanvas.getContext('2d');
            ctx.webkitImageSmoothingEnabled = false;
            ctx.mozImageSmoothingEnabled = false;
            ctx.imageSmoothingEnabled = false;
            gerado = true;
            CANVAS.push(theCanvas);
        });
    }
    
    setTimeout(function() {
        console.log(CANVAS);
        $.each(CANVAS, function(i, elem) {
            if (i+1==CANVAS.length) {
                $(elem).attr("data-index", 1);
                $("body #paginas").prepend(elem);
            } else {
                $(elem).attr("data-index", contador);
                $("body #paginas").append(elem);
                contador++;
            }
        });
        $("#pdf.no").removeClass("no");
        $(".conteudo").hide();
        $("#loading").fadeOut();
        
        try {
            for (var i=1; i<=CANVAS.length; i++) {
    //                $.each(CANVAS, function(i, elem) {
                if (i>1) pdf.addPage();
                var imgData = $("canvas[data-index="+i+"]")[0].toDataURL("image/jpeg", 1.0);
                pdf.addImage(imgData, 'JPEG', 0, 0);
            }

            base = btoa(pdf.output());
            $("#salvar.no").removeClass("no");
        } catch(e) {
            alert("Error description: " + e.message);
        }
    }, 2000);
}

function loop() {
    index++;
    
    if (index<=paginas) {
        este = $(".conteudo[data-pagina="+index+"]")[0];
        html2canvas(este, {
            useCORS: true,
            allowTaint: true,
            letterRendering: true,
//            scale: 2,
            logging: false
        }).then(function(canvas) {
            theCanvas = canvas;
            theCanvas.setAttribute("data-teste", index);
            var ctx = theCanvas.getContext('2d');
            ctx.webkitImageSmoothingEnabled = false;
            ctx.mozImageSmoothingEnabled = false;
            ctx.imageSmoothingEnabled = false;
            gerado = true;
            CANVAS.push(theCanvas);
            loop();
        });
    } else {
        contador = 1;
//        setTimeout(function() {
            console.log(CANVAS);
            $.each(CANVAS, function(i, elem) {
                $(elem).attr("data-index", contador);
                $("body #paginas").append(elem);
                contador++;
            });
            $("#pdf.no").removeClass("no");
            $(".conteudo").hide();
            $("#loading").fadeOut();

            try {
                for (var i=1; i<=CANVAS.length; i++) {
        //                $.each(CANVAS, function(i, elem) {
                    if (i>1) pdf.addPage();
                    var imgData = $("canvas[data-index="+i+"]")[0].toDataURL("image/jpeg", 1.0);
                    pdf.addImage(imgData, 'JPEG', 0, 0);
                }

                base = btoa(pdf.output());
                $("#salvar.no").removeClass("no");
            } catch(e) {
                alert("Error description: " + e.message);
            } 
//        }, 2000);
    }
}

function gerarPaginas() {
    console.log($("#conteudo img").height())
    var tamanhoMaximo = 1123-40;
    var numero = 1;
    paragrafos = conteudo.texto.split("\n");
    
    $.each(paragrafos, function(i, value) {
        paragrafos[i] = colocarTags(value);
        if (value.length==1) {
            paragrafos[i] = "<br>";
        }
    });
    
    pagina = $(".conteudo[data-pagina=1]");
    
    $.each(paragrafos, function(i, value) {
        elemento = $("<span>"+value+"</span>").appendTo(pagina.find(".texto"));
//        console.log(elemento)
//        console.log(numero+": "+pagina.height());
        
        if (pagina.height()>tamanhoMaximo) {
            elemento.remove();
            
            numero++;
            pagina = $("<div class='conteudo'><div class='texto'></div></div>").appendTo("#paginas");
            pagina.attr("data-pagina", numero);
            elemento = $("<span>"+value+"</span>").appendTo(pagina.find(".texto"));
        }
    });
    
    $(".conteudo").css({minHeight: "29.7cm"})
    $("#conteudo").hide();
    
    paginas = numero;
    
//    gerarCanvas();
    loop();
}

function sleep(ms) {
    console.log("Espere "+(ms/1000)+" segundos");
    return new Promise(resolve => setTimeout(resolve, ms));
}