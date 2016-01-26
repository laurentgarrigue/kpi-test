function changeCompetition()
{
	document.forms['formClassement'].elements['Cmd'].value = '';
	document.forms['formClassement'].elements['ParamCmd'].value = 'changeCompetition';
	document.forms['formClassement'].submit();
}

function changeSaisonTransfert()
{
	document.forms['formClassement'].elements['Cmd'].value = '';
	document.forms['formClassement'].elements['ParamCmd'].value = 'changeSaisonTransfert';
	document.forms['formClassement'].submit();
}

function changeOrderCompetition()
{
	document.forms['formClassement'].elements['Cmd'].value = '';
	document.forms['formClassement'].elements['ParamCmd'].value = 'changeOrderCompetition';
	document.forms['formClassement'].submit();
}

function computeClt()
{
	document.forms['formClassement'].elements['Cmd'].value = 'DoClassement';
	document.forms['formClassement'].elements['ParamCmd'].value = '';
	document.forms['formClassement'].submit();
}

function initClt()
{
	document.forms['formClassement'].elements['Cmd'].value = 'InitClassement';
	document.forms['formClassement'].elements['ParamCmd'].value = '';
	document.forms['formClassement'].submit();
}

function publicationClt()
{
	if (!confirm('Confirmation de la Publication du Classement ? '))
		return false;

	document.forms['formClassement'].elements['Cmd'].value = 'PublicationClassement';
	document.forms['formClassement'].elements['ParamCmd'].value = '';
	document.forms['formClassement'].submit();
}

function depublicationClt()
{
	if (!confirm('Confirmation de la DE-Publication du Classement ? (Suppression du classement public)'))
		return false;

	if (!confirm('ATTENTION : Suppression du classement public ?'))
		return false;

	document.forms['formClassement'].elements['Cmd'].value = 'DePublicationClassement';
	document.forms['formClassement'].elements['ParamCmd'].value = '';
	document.forms['formClassement'].submit();
}

// Transfert des Equipes ...
function transfert()
{
	// Verification qu'il y a bien des Equipes &agrave;  Transf&eacute;rer ...
	var elts = document.forms['formClassement'].elements['checkClassement'];
	var elts_count = (typeof(elts.length) != 'undefined') ? elts.length : 0;

	var str = '';
	if (elts_count) 
	{
		for (var i = 0; i < elts_count; i++) 
		{
			if (elts[i].checked)
			{
				if (str.length > 0)
					str += ',';
			
				str += elts[i].value;
			}
		} 
	}
	else
	{
		str = elts.value;
	}
	  
	if (str.length == 0)
	{
		alert("Rien &agrave;  transf&eacute;rer !, aucune ligne s&eacute;lectionn&eacute;e !!!! ...");
		return false;
	}
	
	// Verification qu'une comp&eacute;tition est choisie ainsi qu'une saison ...
	var codeCompetTransfert = $('#codeCompetTransfert option:selected').val();
	if (codeCompetTransfert.length == 0)
	{
		alert("Aucune comp&eacute;tition de transfert s&eacute;lectionn&eacute;e !!! ...");
		return false;
	}
	var codeSaisonTransfert = $('#codeSaisonTransfert option:selected').val();
	if (codeSaisonTransfert.length == 0)
	{
		alert("Aucune saison de transfert s&eacute;lectionn&eacute;e !!! ...");
		return false;
	}

	if (!confirm('Confirmation du Transfert ? '))
		return false;

	document.forms['formClassement'].elements['Cmd'].value = 'Transfert';
	document.forms['formClassement'].elements['ParamCmd'].value = str;
	document.forms['formClassement'].submit();

	return true;
}
		
function sessionSaison()
{
	if(!confirm('Confirmez-vous le changement de saison de travail ?\n(n\'affecte que votre session)'))
	{
		document.forms['formClassement'].reset;
		return;
	} else {
		document.forms['formClassement'].elements['Cmd'].value = 'SessionSaison';
		document.forms['formClassement'].elements['ParamCmd'].value = document.forms['formClassement'].elements['saisonTravail'].value;
		document.forms['formClassement'].submit();
	}
}

$(document).ready(function() { //Jquery

	// Actualiser
	$('#actuButton').click(function(){
		$('#formClassement').submit();
	});
	//Ajout title
	$('.directInput').attr('title','Cliquez pour modifier, puis tabulation pour passer &agrave;  la valeur suivante');
	// contr&ocirc;le touche entr&eacute;e (valide les donn&eacute;es en cours mais pas le formulaire)
	$('.tableauJQ').bind('keydown',function(e){
		if(e.which == 13)
		{
			validationDonnee();
			return false;
		}
	}); 
	// blur d'une input => validation de la donn&eacute;e
	$('#inputZone').live('blur', function(){
		validationDonnee();
	});
	// focus sur un lien du tableau => remplace le lien par un input
	$('.directInput').focus(function(event){
		event.preventDefault();
		var valeur = $(this).text();
		var tabindexVal = $(this).attr('tabindex');
		$(this).attr('tabindex',tabindexVal+1000);
		$(this).before('<input type="text" id="inputZone" class="champsPoints" tabindex="'+tabindexVal+'" size="1" value="'+valeur+'">');
		$(this).hide();
		setTimeout( function() { $('#inputZone').select() }, 0 );
	});
	
	function validationDonnee(){
		var nouvelleValeur = $('#inputZone').val();
		var tabindexVal = $('#inputZone').attr('tabindex');
		$('#inputZone + span').attr('tabindex',tabindexVal);
		$('#inputZone + span').show();
		var valeur = $('#inputZone + span').text();
		var identifiant = $('#inputZone + span').attr('id');
		var identifiant2 = identifiant.split('-');
		var typeValeur = identifiant2[0];
		var numEquipe = identifiant2[1];
		if(typeof identifiant2[2] != 'undefined')
		{
			var numJournee = identifiant2[2];
		}else{
			var numJournee = '';
		}

		var diviseur = 1;
		if(typeValeur == 'Pts')
		{
			nouvelleValeur = nouvelleValeur * 100;
			valeur = valeur * 100;
			diviseur = 100;
		}
		if(valeur != nouvelleValeur){
			var AjaxWhere = $('#AjaxWhere').val();
			var AjaxUser = $('#AjaxUser').val();
			if(numJournee != '')
			{
				var AjaxTableName = $('#AjaxTableName2').val();
				var AjaxAnd = '';
			}
			else
			{
				var AjaxTableName = $('#AjaxTableName').val();
				var AjaxAnd = $('#AjaxAnd').val();
			}
			$.get("UpdateCellJQ.php",
				{
					AjTableName: AjaxTableName,
					AjWhere: AjaxWhere,
					AjTypeValeur: typeValeur,
					AjValeur: nouvelleValeur,
					AjAnd: AjaxAnd,
					AjId: numEquipe,
					AjId2: numJournee,
					AjUser: AjaxUser,
					AjOk: 'OK'
				},
				function(data){
					if(data != 'OK!'){
						alert('mise &agrave;  jour impossible : '+data);
					}else{
						$('#'+identifiant).text(nouvelleValeur/diviseur);
					}
				}
			);
		};
		$('#inputZone').remove();
	}


	
});

