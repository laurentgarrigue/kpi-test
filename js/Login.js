function alertMsg(msg){	if(msg != '')	{		alert(msg);	}}$(document).ready(function() {	$('#renv').hide();	$('#Renvoyer').hide();	$('#Annuler').hide();	$('#perdu').click(function(){		$('#renv').show();		$('#connect').hide();		$('#login').hide();		$('#Renvoyer').show();		$('#Annuler').show();		$('#perdu').hide();		$('#Mode').val('Regeneration');	});	$('#Annuler').click(function(){		$('#idMel').val('');		$('#renv').hide();		$('#connect').show();		$('#login').show();		$('#Renvoyer').hide();		$('#Annuler').hide();		$('#perdu').show();		$('#Mode').val('Connexion');	});	$('#Renvoyer').click(function(){		var idUser = $('#idUser').val();		if(idUser == '')		{			alert('Vous devez saisir un identifiant valide.');			return false;		}		var idMel = $('#idMel').val();		if(idMel == '')		{			alert('Vous devez saisir une adresse mail valide.');			return false;		}		alert('Vous allez recevoir un nouveau mot de passe par mail dans quelques instants.');		$('#formLogin').submit();	});    $('#login').click(function(){        $('#formLogin').submit();    });});