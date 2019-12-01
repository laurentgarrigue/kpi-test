$(function() {
    // Messages et alertes
    alert_messages(messages);

    // Sélection langue
    $('.nav-lang').click(function(e) {
        e.preventDefault();
        var lang = $(this).data('lang');
        $.post( 
            base_url + 'home/lang', 
            { lang: lang },
            function( data ) {
                location.reload();
            }, 
            "json"
        );
    });


});


/**
 * Affichage des messages et alertes
 * 
 * @param {object} messages 
 */
function alert_messages(messages = null) {
    if (typeof messages == 'object' && messages.length > 0) {
        var delay = 5000;
        var step = 2500;
        messages.forEach(function(item) 
        { 
            var texte = '<div class="alert  alert-' + item["context"] + ' alert-dismissible fade show" role="alert">';
            texte += item['content'];
            texte += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
            texte += '<span aria-hidden="true">&times;</span>';
            texte += '</button></div>';
            
            $(texte).appendTo('#infoMessage')
            .delay(delay)
            .fadeOut(step);

            delay += step;
        });
        return;
    } else {
        return;
    }
}