var gerado = false;
var CANVAS;

$(document).ready(function() {
    html2canvas($("#certificado")[0], {
        useCORS: true,
        allowTaint: true,
        letterRendering: true,
//        scale: 2,
        logging: false
    }).then(function(canvas) {
        gerado = true;
        theCanvas = canvas;
        document.body.appendChild(canvas);
        CANVAS = canvas;

        // Convert and download as image 
        Canvas2Image.saveAsPNG(canvas); 
        $("body").append(canvas);
        $("#certificado").hide();

        $("#gerar img.no").removeClass("no");
    });
    
//    html2canvas($("#certificado"), {
//        onrendered: function(canvas) {
//            gerado = true;
//            theCanvas = canvas;
//            document.body.appendChild(canvas);
//            CANVAS = canvas;
//
//            // Convert and download as image 
//            Canvas2Image.saveAsPNG(canvas); 
//            $("body").append(canvas);
//            $("#certificado").hide();
//            
//            $("#gerar img.no").removeClass("no");
////            window.print();
//        }
//    });
});

$("#imprimir").click(function() {
    if ($(this).hasClass("no")) return;
    if (gerado) {
        window.print();
        return;
    }
    
    html2canvas($("#certificado"), {
        onrendered: function(canvas) {
            gerado = true;;
            theCanvas = canvas;
            document.body.appendChild(canvas);

            // Convert and download as image 
            Canvas2Image.saveAsPNG(canvas); 
            $("body").append(canvas);
            window.print();
            // Clean up 
            //document.body.removeChild(canvas);
        }
    });
});

$("#pdf").click(function() {
    if ($(this).hasClass("no")) return;
    savePDF();
});