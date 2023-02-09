var alerta = {
    ok: function(div, mensaje, tiempo) {
        if (tiempo === undefined) { tiempo = 5000; }
        $("#" + div).html(mensaje);
        $("#" + div).addClass("alert-success");
        $("#" + div).removeClass("hidden");
        setTimeout(function() {
            $("#" + div).addClass("hidden");
            $("#" + div).removeClass("alert-success");
        }, tiempo);
    },
    aviso: function(div, mensaje, tiempo) {
        if (tiempo === undefined) { tiempo = 5000; }
        $("#" + div).html(mensaje);
        $("#" + div).addClass("alert-primary");
        $("#" + div).removeClass("hidden");
        setTimeout(function() {
            $("#" + div).addClass("hidden");
            $("#" + div).removeClass("alert-primary");
        }, tiempo);
    },
    error: function(div, mensaje, tiempo) {
        if (tiempo === undefined) { tiempo = 5000; }
        $("#" + div).html(mensaje);
        $("#" + div).addClass("alert-danger");
        $("#" + div).removeClass("hidden");
        setTimeout(function() {
            $("#" + div).addClass("hidden");
            $("#" + div).removeClass("alert-danger");
        }, tiempo);
    }
}