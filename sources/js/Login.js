function alertMsg(msg){	if(msg != '')	{		alert(msg);	}}$(document).ready(function() {	$('#renv').hide();	$('#Renvoyer').hide();	$('#Annuler').hide();        // Calcul du décalage time_zone en minutes    var timezone_offset_minutes = new Date().getTimezoneOffset();    timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;    // Timezone difference in minutes such as 330 or -360 or 0    $('#tzOffset').val(timezone_offset_minutes);	$('#perdu').click(function(){		$('#renv').show();		$('#connect').hide();		$('#login').hide();		$('#Renvoyer').show();		$('#Annuler').show();		$('#perdu').hide();		$('#Mode').val('Regeneration');	});	$('#Annuler').click(function(){		$('#idMel').val('');		$('#renv').hide();		$('#connect').show();		$('#login').show();		$('#Renvoyer').hide();		$('#Annuler').hide();		$('#perdu').show();		$('#Mode').val('Connexion');	});	$('#Renvoyer').click(function(){		var idUser = $('#idUser').val();		if(idUser == '')		{			alert('Vous devez saisir un identifiant valide.');			return false;		}		var idMel = $('#idMel').val();		if(idMel == '')		{			alert('Vous devez saisir une adresse mail valide.');			return false;		}		alert('Vous allez recevoir un nouveau mot de passe par mail dans quelques instants.');		$('#formLogin').submit();	});    $('#login').click(function(){        $('#formLogin').submit();    });        $(document).keypress(function( event ) {        if ( event.which == 13 ) {           $('#formLogin').submit();        }    });});