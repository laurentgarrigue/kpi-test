/*function changeAuthSaison(){	document.forms['formCompet'].elements['Cmd'].value = 'ChangeAuthSaison';	document.forms['formCompet'].elements['ParamCmd'].value = '';	document.forms['formCompet'].submit();}function AddSaison(){	if(!confirm('Confirmez-vous l ajout d une saison ?'))	{		return;	}	else	{		document.forms['formCompet'].elements['Cmd'].value = 'AddSaison';		document.forms['formCompet'].elements['ParamCmd'].value = '';		document.forms['formCompet'].submit();	}}function activeSaison(){	if(!confirm('Confirmez-vous le changement de saison active ?\nCE CHANGEMENT AFFECTE TOUT LES UTILISATEURS (ADMIN ET PUBLIC) !'))	{		document.forms['formCompet'].reset;		return;	} else if(!confirm('Il s\'agit bien de changer de saison active !\nCE CHANGEMENT AFFECTE TOUT LES UTILISATEURS (ADMIN ET PUBLIC) !\nConfirmez ?')) {		document.forms['formCompet'].reset;		return;	} else {		document.forms['formCompet'].elements['Cmd'].value = 'ActiveSaison';		document.forms['formCompet'].elements['ParamCmd'].value = document.forms['formCompet'].elements['saisonActive'].value;		document.forms['formCompet'].submit();	}}		*/$(document).ready(function() {	// Maskedinput	//$.mask.definitions['h'] = "[A-O]";	$('.dpt').mask("?***");	$('.date').mask("99/99/9999");	//$("#inputZone").mask("9");		$("*").tooltip({		//bodyHandler: function() {		//	return $($(this).attr("href")).html();		//},		showURL: false	});	$("#logoLink").blur(function(){		lien = $("#logoLink").val();		$("#logoprovisoire").attr('src', lien);	});	$("#sponsorLink").blur(function(){		lien = $("#sponsorLink").val();		$("#sponsorprovisoire").attr('src', lien);	});	//Fusion joueurs	$("#FusionSource").autocomplete('Autocompl_joueur.php', {		width: 550,		max: 50,		mustMatch: false,	});	$("#FusionSource").result(function(event, data, formatted) {		if (data) {			$("#numFusionSource").val(data[1]);			$("#FusionSource").val(data[0]);		}	});	$("#FusionCible").autocomplete('Autocompl_joueur.php', {		width: 550,		max: 50,		mustMatch: false,	});	$("#FusionCible").result(function(event, data, formatted) {		if (data) {			$("#numFusionCible").val(data[1]);			$("#FusionCible").val(data[0]);		}	});	$("#FusionJoueurs").click(function() {		var fusSource = $("#FusionSource").val();		var fusCible = $("#FusionCible").val();		if(!confirm('Confirmez-vous la fusion : '+fusSource+' => '+fusCible+' ?'))		{			return false;		}		document.forms['formCompet'].elements['Cmd'].value = 'FusionJoueurs';		document.forms['formCompet'].submit();	});	//Renomme Equipe	$("#RenomSource").autocomplete('Autocompl_equipe.php', {		width: 550,		max: 50,		mustMatch: false,	});	$("#RenomSource").result(function(event, data, formatted) {		if (data) {			$("#numRenomSource").val(data[1]);			$("#RenomSource").val(data[2]);			$("#RenomCible").val(data[2]);		}	});	$("#RenomEquipe").click(function() {		var renSource = $("#RenomSource").val();		var renCible = $("#RenomCible").val();		if(!confirm('Confirmez-vous la modification :\n'+renSource+' => '+renCible+' ?'))		{			return false;		}		document.forms['formCompet'].elements['Cmd'].value = 'RenomEquipe';		document.forms['formCompet'].submit();	});	//TitreJournee labelCompet	$("#TitreJournee").focus(function() {		var TitreJournee = $("#labelCompet").val();		$("#TitreJournee").val(TitreJournee);	});	//Accès Feuille	$("#accesFeuilleBtn").click(function() {		var accesFeuille = $("#accesFeuille").val();		if(!confirm('Confirmez-vous l\'accès à la feuille '+accesFeuille+' ?'))		{			return false;		}		window.open('GestionMatchDetail1.php?idMatch='+accesFeuille,'Feuille');	});	//Fileupload/*	$("#fileuploader").uploadFile({		url:"YOUR_FILE_UPLOAD_URL",		fileName:"myfile"	});*/});if (top.location != self.document.location)	{		alert('Vous quittez le site parent !');		top.location = self.document.location;	}