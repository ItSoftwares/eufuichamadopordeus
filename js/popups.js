var idPopup = 1;
var popups = {};

$(document).on("click", ".popup", function() {
   // console.log("a")
    $(this).clearQueue().fadeOut(function() {
        $(this).remove();
        organizarPopups();
    });
});

function chamarPopupInfo(mensagem, tempo) {
    tempo = tempo || 10000;
    popupInfo = $("<div class='popup popup-info'>").appendTo("body");
    popupInfo.html(mensagem).attr("data-id", idPopup);
    organizarPopups();
    
    popupInfo.delay(tempo).fadeOut(function() {
        $(this).remove();
        // console.log($('.popup-info').length);
        organizarPopups();
    });
    
    idPopup++;
}

function chamarPopupErro(mensagem, tempo) {
    tempo = tempo || 10000;
    popupInfo = $("<div class='popup popup-erro'>").appendTo("body");
    popupInfo.html(mensagem).attr("data-id", idPopup);
    organizarPopups();
    
    popupInfo.delay(tempo).fadeOut(function() {
        $(this).remove();
        // console.log($('.popup-erro').length);
        organizarPopups();
    });
    
    idPopup++;
}

function chamarPopupConf(mensagem, tempo) {
    tempo = tempo || 10000;
    popupInfo = $("<div class='popup popup-conf'>").appendTo("body");
    popupInfo.html(mensagem).attr("data-id", idPopup);
    organizarPopups();
    
    popupInfo.delay(tempo).fadeOut(function() {
        $(this).remove();
        // console.log($('.popup-conf').length);
        organizarPopups();
    });
    
    idPopup++;
}

function organizarPopups() {
    qtdPopups = $(".popup").length;
   // console.log(qtdPopups);
    altura = 10;
    
    $(".popup").each(function(i, elem) {
        $(elem).css({bottom: altura});
        altura+= $(elem).outerHeight()+10;
    });
}