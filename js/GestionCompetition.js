function uploadLogo(){	document.forms['formCompet'].elements['Cmd'].value = 'UploadLogo';	document.forms['formCompet'].elements['ParamCmd'].value = '';	document.forms['formCompet'].submit();}function dropLogo(){	if(!confirm('Confirmez-vous la suppression ?'))	{		return;	}	else	{		document.forms['formCompet'].elements['Cmd'].value = 'DropLogo';		document.forms['formCompet'].elements['ParamCmd'].value = '';		document.forms['formCompet'].submit();	}}function changeAffiche(){	document.forms['formCompet'].elements['Cmd'].value = '';	document.forms['formCompet'].elements['ParamCmd'].value = '';	document.forms['formCompet'].submit();}function changeAuthSaison(){	document.forms['formCompet'].elements['Cmd'].value = 'ChangeAuthSaison';	document.forms['formCompet'].elements['ParamCmd'].value = '';	document.forms['formCompet'].submit();}function verrou(VerrouCompet, verrouEtat){	if(!confirm('Confirmez-vous le changement ?'))	{		return;	}	else	{		document.forms['formCompet'].elements['Cmd'].value = 'Verrou';		document.forms['formCompet'].elements['verrouCompet'].value = VerrouCompet;		document.forms['formCompet'].elements['Verrou'].value = verrouEtat;		document.forms['formCompet'].submit();	}}function AddSaison(){	if(!confirm('Confirmez-vous l ajout d une saison ?'))	{		return;	}	else	{		document.forms['formCompet'].elements['Cmd'].value = 'AddSaison';		document.forms['formCompet'].elements['ParamCmd'].value = '';		document.forms['formCompet'].submit();	}}function validCompet(){		var codeCompet = document.forms['formCompet'].elements['codeCompet'].value;		if (codeCompet.length == 0)		{			alert("Le code Compétition est Vide ..., Ajout Impossible !");			return false;		}				var labelCompet = document.forms['formCompet'].elements['labelCompet'].value;		if (labelCompet.length == 0)		{			alert("Le libellé de la Compétition est Vide ..., Ajout Impossible !");			return false;		}				return true;}function Add(){	if (!validCompet())		return;	document.forms['formCompet'].elements['Cmd'].value = 'Add';	document.forms['formCompet'].elements['ParamCmd'].value = '';	document.forms['formCompet'].submit();}function sessionSaison(){	if(!confirm('Confirmez-vous le changement de saison de travail ?\n(n\'affecte que votre session)'))	{		document.forms['formCompet'].reset;		return;	} else {		document.forms['formCompet'].elements['Cmd'].value = 'SessionSaison';		document.forms['formCompet'].elements['ParamCmd'].value = document.forms['formCompet'].elements['saisonTravail'].value;		document.forms['formCompet'].submit();	}}function activeSaison(){	if(!confirm('Confirmez-vous le changement de saison active ?\nCE CHANGEMENT AFFECTE TOUT LES UTILISATEURS (ADMIN ET PUBLIC) !'))	{		document.forms['formCompet'].reset;		return;	} else if(!confirm('Il s\'agit bien de changer de saison active !\nCE CHANGEMENT AFFECTE TOUT LES UTILISATEURS (ADMIN ET PUBLIC) !\nConfirmez ?')) {		document.forms['formCompet'].reset;		return;	} else {		document.forms['formCompet'].elements['Cmd'].value = 'ActiveSaison';		document.forms['formCompet'].elements['ParamCmd'].value = document.forms['formCompet'].elements['saisonActive'].value;		document.forms['formCompet'].submit();	}}		function publiCompet(idCompet, pub){	if(!confirm('Confirmez-vous le changement ?'))	{		return false;	}			document.forms['formCompet'].elements['Cmd'].value = 'PubliCompet';	document.forms['formCompet'].elements['ParamCmd'].value = idCompet;	document.forms['formCompet'].elements['Pub'].value = pub;	document.forms['formCompet'].submit();}function updateCompet(){	if (!validCompet())		return;	document.forms['formCompet'].elements['Cmd'].value = 'UpdateCompet';	document.forms['formCompet'].elements['ParamCmd'].value = '';	document.forms['formCompet'].submit();}function razCompet(){	document.forms['formCompet'].elements['Cmd'].value = 'RazCompet';	document.forms['formCompet'].elements['ParamCmd'].value = '';	document.forms['formCompet'].submit();}function paramCompet(idCompet){	document.forms['formCompet'].elements['Cmd'].value = 'ParamCompet';	document.forms['formCompet'].elements['ParamCmd'].value = idCompet;	document.forms['formCompet'].submit();}$(document).ready(function() {	// Maskedinput	//$.mask.definitions['h'] = "[A-O]";	$('.dpt').mask("?***");	$('.date').mask("99/99/9999");	//$("#inputZone").mask("9");		$("*").tooltip({		//bodyHandler: function() {		//	return $($(this).attr("href")).html();		//},		showURL: false	});		$("#choixCompet").autocomplete('Autocompl_compet.php', {		width: 350,		max: 50,		mustMatch: true,		//multiple: true,		//matchContains: true,		//formatItem: formatItem,		//formatResult: formatResult		//selectFirst: false	});	$("#choixCompet").result(function(event, data, formatted) {		if (data) {			$("#codeCompet").val(data[1]);			$("#labelCompet").val(data[2]);			$("#niveauCompet").val(data[3]);			$("#codeRef").val(data[4]);			$("#codeTypeClt").val(data[5]);			$("#etape").val(data[6]);			$("#qualifies").val(data[7]);			$("#elimines").val(data[8]);			$("#points").val(data[9]);			$("#soustitre").val(data[10]);			$("#web").val(data[11]);			$("#logoLink").val(data[12]);			$("#sponsorLink").val(data[13]);			$("#toutGroup").val(data[14]);			$("#touteSaisons").val(data[15]);			$("#groupOrder").val(data[16]);			$("#soustitre2").val(data[17]);			$("#titre_actif").val(data[18]);			$("#logo_actif").val(data[19]);			$("#sponsor_actif").val(data[20]);			$("#kpi_ffck_actif").val(data[21]);			$("#en_actif").val(data[22]);		}	});	$("#logoLink").blur(function(){		lien = $("#logoLink").val();		$("#logoprovisoire").attr('src', lien);	});	$("#sponsorLink").blur(function(){		lien = $("#sponsorLink").val();		$("#sponsorprovisoire").attr('src', lien);	});	$("#toutGroup").bind('click', function() {		alert('Vous allez affecter ces données (Sous-titre, Lien web, logo, sponsor) à toutes les compétitions du groupe ET perdre les données des autres compétitions du groupe !');	});	$("#touteSaisons").bind('click', function() {		alert('Vous allez affecter ces données (Sous-titre, Lien web, logo, sponsor) à toutes les saisons de la compétition ou du groupe ET perdre les données des autres saisons !');	});	//Fusion joueurs	$("#FusionSource").autocomplete('Autocompl_joueur.php', {		width: 550,		max: 50,		mustMatch: false,	});	$("#FusionSource").result(function(event, data, formatted) {		if (data) {			$("#numFusionSource").val(data[1]);			$("#FusionSource").val(data[0]);		}	});	$("#FusionCible").autocomplete('Autocompl_joueur.php', {		width: 550,		max: 50,		mustMatch: false,	});	$("#FusionCible").result(function(event, data, formatted) {		if (data) {			$("#numFusionCible").val(data[1]);			$("#FusionCible").val(data[0]);		}	});	$("#FusionJoueurs").click(function() {		var fusSource = $("#FusionSource").val();		var fusCible = $("#FusionCible").val();		if(!confirm('Confirmez-vous la fusion : '+fusSource+' => '+fusCible+' ?'))		{			return false;		}		document.forms['formCompet'].elements['Cmd'].value = 'FusionJoueurs';		document.forms['formCompet'].submit();	});	//Renomme Equipe	$("#RenomSource").autocomplete('Autocompl_equipe.php', {		width: 550,		max: 50,		mustMatch: false,	});	$("#RenomSource").result(function(event, data, formatted) {		if (data) {			$("#numRenomSource").val(data[1]);			$("#RenomSource").val(data[2]);			$("#RenomCible").val(data[2]);		}	});	$("#RenomEquipe").click(function() {		var renSource = $("#RenomSource").val();		var renCible = $("#RenomCible").val();		if(!confirm('Confirmez-vous la modification :\n'+renSource+' => '+renCible+' ?'))		{			return false;		}		document.forms['formCompet'].elements['Cmd'].value = 'RenomEquipe';		document.forms['formCompet'].submit();	});	//TitreJournee labelCompet	$("#TitreJournee").focus(function() {		var TitreJournee = $("#labelCompet").val();		$("#TitreJournee").val(TitreJournee);	});	//Accès Feuille	$("#accesFeuilleBtn").click(function() {		var accesFeuille = $("#accesFeuille").val();		if(!confirm('Confirmez-vous l\'accès à la feuille '+accesFeuille+' ?'))		{			return false;		}		window.open('FeuilleMarque2.php?idMatch='+accesFeuille,'Feuille');	});	$(".publiCompet").click(function(){		//if(confirm('Confirmez-vous le changement de publication ?')){			laCompet = $(this);			laCompet.attr('src', 'v2/images/indicator.gif');			laSaison = $('#saisonTravail').val();			if(laCompet.attr('data-valeur') == 'O'){				changeType = 'N';				textType = 'Non public';			}else{				changeType = 'O';				textType = 'Public';			}			$.post(				'v2/StatutCompet.php', // Le fichier cible côté serveur.				{ // variables					Id_Compet : laCompet.attr('data-id'),					Valeur : changeType,					TypeUpdate : 'Publication',					idSaison : laSaison				},				function(data){ // callback					if(data == 'OK'){						laCompet.attr('src', '../img/oeil2' + changeType + '.gif');						laCompet.attr('data-valeur', changeType);						laCompet.attr('title', textType);					}					else{						alert('Changement impossible <br />'+data);						laCompet.attr('src', '../img/oeil2' + laCompet.attr('data-valeur') + '.gif');						laCompet.attr('data-valeur', laCompet.attr('data-valeur'));					}				},				'text' // Format des données reçues.			);		//}	});	$(".verrouCompet").click(function(){		//if(confirm('Confirmez-vous le changement de publication ?')){			laCompet = $(this);			laCompet.attr('src', 'v2/images/indicator.gif');			laSaison = $('#saisonTravail').val();			if(laCompet.attr('data-valeur') == 'O'){				changeType = 'N';				textType = 'Feuilles de présence modifiables';			}else{				changeType = 'O';				textType = 'Feuilles de présence verrouillées';			}			$.post(				'v2/StatutCompet.php', // Le fichier cible côté serveur.				{ // variables					Id_Compet : laCompet.attr('data-id'),					Valeur : changeType,					TypeUpdate : 'Verrou',					idSaison : laSaison				},				function(data){ // callback					if(data == 'OK'){						laCompet.attr('src', '../img/verrou2' + changeType + '.gif');						laCompet.attr('data-valeur', changeType);						laCompet.attr('title', textType);					}					else{						alert('Changement impossible <br />'+data);						laCompet.attr('src', '../img/verrou2' + laCompet.attr('data-valeur') + '.gif');						laCompet.attr('data-valeur', laCompet.attr('data-valeur'));					}				},				'text' // Format des données reçues.			);		//}	});	$(".statutCompet").click(function(){		//if(confirm('Confirmez-vous le changement de statut ?')){			laCompet = $(this);			statut = laCompet.text();			laCompet.html('<img src="v2/images/indicator.gif" />');			laSaison = $('#saisonTravail').val();			if(statut == '0' || statut == 'ATT'){				changeType = 'ON';			}else if(statut == 'ON'){				changeType = 'END';			}else{				changeType = 'ATT';			}			$.post(				'v2/StatutCompet.php', // Le fichier cible côté serveur.				{ // variables					Id_Compet : laCompet.attr('data-id'),					Valeur : changeType,					TypeUpdate : 'Statut',					idSaison : laSaison				},				function(data){ // callback					if(data == 'OK'){						laCompet.html(changeType);						laCompet.removeClass('statutCompetATT statutCompetON statutCompetEND').addClass('statutCompet' + changeType);					}					else{						laCompet.html(statut);						alert('Changement impossible <br />'+data);					}				},				'text' // Format des données reçues.			);					//}	});		//$('#tableCompet').fixedHeaderTable('hide');	});if (top.location != self.document.location)	{		alert('Vous quittez le site parent !');		top.location = self.document.location;	}