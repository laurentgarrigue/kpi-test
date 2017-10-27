    &nbsp;(<a href="GestionEquipe.php">Retour</a>)
		<div class="main">
				
			<form method="POST" action="GestionEquipeJoueur.php" name="formEquipeJoueur" id="formEquipeJoueur" enctype="multipart/form-data">
				<input type='hidden' name='Cmd' id='Cmd' value='' />
				<input type='hidden' name='ParamCmd' id='ParamCmd' value='' />
				<input type='hidden' name='AjaxTableName' id='AjaxTableName' value='gickp_Competitions_Equipes_Joueurs' />
				<input type='hidden' name='AjaxWhere' id='AjaxWhere' value='Where Matric = ' />
				<input type='hidden' name='AjaxAnd' id='AjaxAnd' value='And Id_equipe = ' />
				<input type='hidden' name='AjaxUser' id='AjaxUser' value='{$user}'/>
				<input type='hidden' name='idEquipe' id='idEquipe' value='{$idEquipe}' />
				<input type='hidden' name='typeCompet' id='typeCompet' value='{$typeCompet}' />
				<input type='hidden' name='saisonCompet' id='saisonCompet' value='{$Saison}' />
				<input type='hidden' name='surcl_necess' id='surcl_necess' value='{$surcl_necess}' />

                <div class='blocLeft'>
					<div class='titrePage' tabindex='1'>Feuille de présence {$infoEquipe2}</div>
					{if $typeCompet == 'CH'}
						<div class='titrePage'><i>Joueurs présents pour la prochaine journée de Championnat de France</i></div>
					{elseif $typeCompet == 'CF'}
						<div class='titrePage'><i>Joueurs présents pour le prochain tour de la Coupe de France</i></div>
					{/if}
					<br>
					<br>
					<div class='liens'>
						{if $profile <= 8 && $Verrou != 'O' && $AuthModif == 'O' && $idEquipe > 0}
							<a href="#" onclick="setCheckboxes('formEquipeJoueur', 'checkEquipeJoueur', true);return false;"><img height="22" src="../img/glyphicons-155-more-checked.png" alt="Sélectionner tous" title="Sélectionner tous" /></a>
							<a href="#" onclick="setCheckboxes('formEquipeJoueur', 'checkEquipeJoueur', false);return false;"><img height="22" src="../img/glyphicons-155-more-windows.png" title="Sélectionner aucun" /></a>
							<a href="#" onclick="RemoveCheckboxes('formEquipeJoueur', 'checkEquipeJoueur')"><img height="25" src="../img/glyphicons-17-bin.png" alt="Supprimer la sélection" title="Supprimer la sélection" /></a>
						{/if}
						<a href="FeuilleTitulaires.php?equipe={$idEquipe}" target="_blank" title="Feuille de présence PDF"><img height="25" src="../img/pdf.png" /></a>						
						<a href="FeuilleTitulairesEN.php?equipe={$idEquipe}" target="_blank" title="Feuille de présence PDF - EN"><img height="25" src="../img/pdfEN.png" /></a>						
						<select name='changeEquipe' id='changeEquipe'>
                            {if $idEquipe <= 0}
                                <Option Value="" selected>Sélectionner...</option>
                            {/if}
							{section name=i loop=$arrayEquipe} 
								<Option Value="{$arrayEquipe[i].Id}" {if $idEquipe == $arrayEquipe[i].Id}selected{/if}>{$arrayEquipe[i].Code_comite_dep} - {$arrayEquipe[i].Libelle}</Option>
							{/section}
						</select>
                        <img class="cliquable" id="actuButton" title="Recharger" height="25" src="../img/glyphicons-82-refresh.png">
					</div>
					<div class='blocTable'>
						<table class='tableau' id='tableMatchs'>
							<thead>
								<tr class='header'>
									<th>&nbsp;</th>
									<th>N°</th>
									<th>Cap.</th>
									<th>Nom</th>
									<th>Prénom</th>
									<th>Licence</th>
									<th>Club</th>
									<th>Cat.-Sexe</th>
									<th>Pagaie<br />eau calme</th>
									<th>Certificat<br /><span title="CK">Compétition</span></th>
									<th>Arb.</th>
									<th>&nbsp;</th>
								</tr>
							</thead>
							<tbody>
								{section name=i loop=$arrayJoueur} 
									{if ($arrayJoueur[i].Capitaine == 'E' or $arrayJoueur[i].Capitaine == 'A' or $arrayJoueur[i].Capitaine == 'X') && $test != 'OK'}
									{assign var='test' value='OK'}
									<tr class='{cycle values="impair,pair"}'>
										<td><br><br></td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									{/if}
									<tr class='{cycle values="impair,pair"}  colorCap{$arrayJoueur[i].Capitaine}'>
										{if $profile <= 7 && $Verrou != 'O' && $AuthModif == 'O' && $idEquipe > 0}
											<td><input type="checkbox" name="checkEquipeJoueur" value="{$arrayJoueur[i].Matric}" id="checkDelete{$smarty.section.i.iteration}" /></td>
											<td width="30" class='directInput text' tabindex='1{$smarty.section.i.iteration}0'><span href="#" Id="Numero-{$arrayJoueur[i].Matric}-{$idEquipe}">{$arrayJoueur[i].Numero}</span></td>
											<!--<td><a href="#" Id="numero{$arrayJoueur[i].Matric}" onclick="DoNumero({$arrayJoueur[i].Matric},'{$arrayJoueur[i].Numero}')">{$arrayJoueur[i].Numero}</a></td>-->
											<td class='directSelect colorCap{$arrayJoueur[i].Capitaine}'>
												<span Id="Capitaine-{$arrayJoueur[i].Matric}-{$idEquipe}" class='tooltip'
													title="{$arrayJoueur[i].Capitaine|replace:'C':'Capitaine'|replace:'A':'Arbitre'|replace:'E':'Entraineur'|replace:'X':'Inactif'}">{$arrayJoueur[i].Capitaine}</span>
												<!--<a href="#" Id="Capitaine{$arrayJoueur[i].Matric}" onclick="choixRadioCapitaine('compet', '{$idEquipe}','{$arrayJoueur[i].Matric}','{$arrayJoueur[i].Capitaine}')">{if $arrayJoueur[i].Capitaine=='N'}&nbsp;{else}{$arrayJoueur[i].Capitaine}{/if}</a>-->
											</td>
										{else}
											<td>&nbsp;</td>
											<td>{$arrayJoueur[i].Numero}</td>
											<td class='colorCap{$arrayJoueur[i].Capitaine}'>{if $arrayJoueur[i].Capitaine=='N'}&nbsp;{else}{$arrayJoueur[i].Capitaine}{/if}</td>
										{/if}
										<td>{$arrayJoueur[i].Nom}</td>
										<td>{$arrayJoueur[i].Prenom}</td>
										<td>
											{if $arrayJoueur[i].Matric > 2000000 && $arrayJoueur[i].icf != NULL}Icf-{$arrayJoueur[i].icf}{else}{$arrayJoueur[i].Matric}{/if}
                                            {if $arrayJoueur[i].Saison < $sSaison} <span class='highlight2'>({$arrayJoueur[i].Saison})</span>{/if}
											{if $profile <= 6 && $AuthModif == 'O'}
												<a href="GestionAthlete.php?Athlete={$arrayJoueur[i].Matric}"><img width="10" src="../img/b_plus.png" alt="Détails" title="Détails" /></a>
											{/if}
										</td>
										<td>{$arrayJoueur[i].Numero_club}</td>
										<td>{$arrayJoueur[i].Categ}
                                            {if $arrayJoueur[i].date_surclassement != ''}<span class="vert" title="Surclassement au {$arrayJoueur[i].date_surclassement}">#S</span>{/if}
                                             - {$arrayJoueur[i].Sexe}</td>
										<td {if $arrayJoueur[i].Pagaie_ECA == '' or $arrayJoueur[i].Pagaie_ECA == 'PAGB' or $arrayJoueur[i].Pagaie_ECA == 'PAGJ'} class='highlight2'{/if}>
											<img width="16" src="../img/EC-{$arrayJoueur[i].Pagaie_ECA}.gif" alt="Pagaie Eau Calme" title="Pagaie Eau Calme" />
										</td>
										<td><!--<span title='Loisir'>{$arrayJoueur[i].CertifAPS}</span>/-->{if $arrayJoueur[i].CertifCK != 'OUI'}<span class='highlight2' title='Compétition'>NON</span>{else}<span title='Compétition'>OUI</span>{/if}</td>
										<td>{$arrayJoueur[i].Arbitre}</td>
										{if $profile <= 7 && $Verrou != 'O' && $AuthModif == 'O'}
											<td><a href="#" onclick="RemoveCheckbox('formEquipeJoueur', '{$arrayJoueur[i].Matric}');return false;"><img height="20" src="../img/glyphicons-17-bin.png" alt="Supprimer" title="Supprimer" /></a></td>
										{else}
											<td>&nbsp;</td>
										{/if}
									</tr>
								{/section}
							</tbody>
						</table>
						<div>
							Utilisez le statut <b>Inactif (X)</b> pour rendre indisponible un joueur sans le supprimer de la liste.
							<br>
							(joueurs absents ou suspendus sur un ou plusieurs matchs, sur une ou plusieurs journées)
							<br>
							Les entraineurs (E) et arbitres (A) ne sont pas comptabilisés dans les statistiques.
							<br>
							Les joueurs inactifs (X) et les arbitres (A) ne sont pas transférés sur les feuilles de match.
								<br>
								<br>
								<br>
								<a href="FeuilleTitulaires.php?equipe={$idEquipe}" target="_blank" title="Feuille de présence PDF"><img height="25" src="../img/pdf.png" />Feuille de présence PDF</a>						
							{if $typeCompet == 'CH' or $typeCompet == 'CF'}
								<br>
								<b>Les feuilles de présence doivent impérativement être saisies et mises à jour au plus tard 
								<br>7 jours avant chaque journée de Championnat de France et de Coupe de France.</b>
							{else}
								<a href="FeuilleTitulairesEN.php?equipe={$idEquipe}" target="_blank" title="Feuille de présence PDF - EN"><img height="25" src="../img/pdfEN.png" />Feuille de présence PDF - EN</a>						
							{/if}
						</div>
					</div>
					{if $profile <= 7 && $idEquipe > 0}
						<div>
							<i>Dernier ajout ou suppression dans la liste des présents
							<br>le {$LastUpdate} par {$LastUpdater}.</i>
						</div>
					{/if}
					<div id='directSelecteur'>
						<select id='directSelecteurSelect' size=5>
							<option value='-'>Joueur</option>
							<option value='C'>C - Capitaine</option>
							<option value='E'>E - Entraineur (non joueur)</option>
							<option value='A'>A - Arbitre (non joueur)</option>
							<option value='X'>X - Inactif (non joueur)</option>
						</select>
						<img id='annulButton' height="20" src="../img/annuler.gif" alt="Annuler" title="Annuler" />
						<input type=hidden id='variables' value='' />
					</div>

		        </div>
		        

			    <div class='blocRight'>
					{if $profile <= 7 && $Verrou != 'O' && $AuthModif == 'O' && $idEquipe > 0}
						<table width=100%>
							<tr>
								<th class='titreForm' colspan=2>
									<label>Sélectionner un athlète</label>
								</th>
							</tr>
							<tr>
								<td colspan=2>
									<label class="rouge">Recherche (nom, prénom ou licence)</label>
									<input type="text" name="choixJoueur" id="choixJoueur"/>
									<hr>
								</td>
							</tr>
							<tr>
								<td width=60%>
									<label for="matricJoueur2">N° Licence :</label>
									<input type="text" name="matricJoueur2" readonly maxlength=10 id="matricJoueur2"/>
								</td>
							</tr>
							<tr>
								<td colspan=2>
									<label for="nomJoueur2">Nom :</label>
									<input type="text" name="nomJoueur2" readonly maxlength=30 id="nomJoueur2"/>
								</td>
							</tr>
							<tr>
								<td colspan=2>
									<label for="prenomJoueur2">Prénom :</label>
									<input type="text" name="prenomJoueur2" readonly maxlength=30 id="prenomJoueur2"/>
								</td>
							</tr>
							<tr>
								<td>
									<label for="naissanceJoueur2">Date Naissance :</label>
									<input type="text" name="naissanceJoueur2" readonly maxlength=10 id="naissanceJoueur2" >
                                    <input type="hidden" name="categJoueur2" id="categJoueur2" />
                                </td>
								<td>
									<label for="sexeJoueur2">Sexe :</label>
									<input type="text" name="sexeJoueur2" readonly maxlength=1 id="sexeJoueur2" >
								</td>
							</tr>
                            <tr>
                                <td colspan="2">
                                    <span id="categJoueur3"></span> <span class="surclassement3"></span>
								</td>
                            </tr>
							<tr>
								<td colspan=2><center><i>Optionnel :</i></center></td>
							</tr>
							<tr>
								<td>
									<label for="capitaineJoueur2">Capit./Entr./Arbitre:</label>
									<select name="capitaineJoueur2" id='capitaineJoueur2'>
										<Option Value="-" SELECTED>Joueur</Option>
										<Option Value="C">Capitaine</Option>
										<Option Value="E">Entraineur (non joueur)</Option>
										<Option Value="A">Arbitre (non joueur)</Option>
										<Option Value="X">Inactif (non joueur)</Option>
									</select>
								</td>
								<td>
									<label for="numeroJoueur">Numero</label>
									<input type="text" name="numeroJoueur2" maxlength=2 id="numeroJoueur2">
								</td>
							</tr>
							{if $typeCompet == 'CH' or $typeCompet == 'CF'}
								<tr>
									<td colspan=2><center><i>Contrôle :</i></center></td>
								</tr>
								<tr>
									<td>
										Licence<br />
										Certif CK (Compet.)<br />
										<!--Certif APS (Loisir)<br />-->
										Pagaie ECA<br />
										Cat.
									</td>
									<td>
										<span id="origineJoueur2"></span><br />
										<span id="CKJoueur2"></span><br />
										<!--<span id="APSJoueur2"></span><br />-->
										<span id="pagaieJoueur2"></span><br />
										<span id="catJoueur2"></span>
                                        
									</td>
								</tr>
                                <tr>
                                    <td colspan="2"><span class="surclassement3"></span></td>
                                </tr>
							{/if}
							<tr>
								<td colspan=2 align="center">
									<span id="irregularite" class='highlight2'>Ce joueur n'est pas en règle<br />pour une compétition nationale</span>
									<br />
									<span id="motif" class='highlight2'></span>
                                    <input type="button" id="addEquipeJoueurImpossible" value="Ajout impossible !">
									{if $profile < 3}
                                        <br />
										<input type="button" onclick="Add2();" name="addEquipeJoueur3" id="addEquipeJoueur3" value="<< Ajouter (profil 1/2)">
									{else}
										<br />
                                        <input type="button" onclick="Add2();" name="addEquipeJoueur2" id="addEquipeJoueur2" value="<< Ajouter">
                                    {/if}
								</td>
							</tr>
						</table>
					{/if}
					{if $profile < 3 && $Verrou != 'O' && $AuthModif == 'O'}
						<table width=100%>
							<tr>
								<th class='titreForm' colspan=2>
									<label>Recherche avancée</label>
								</th>
							</tr>
							<tr>
								<td>
									<input type="button" onclick="Find();" name="findJoueur" id="findJoueur" value="&reg; Recherche Licenciés...">
								</td>
							</tr>
						</table>
						<br>
						<br>
                    {/if}
					{if $profile <= 3 && $Verrou != 'O' && $AuthModif == 'O' && $typeCompet != 'CH' && $typeCompet != 'CF'}
						<table width=100% title="Si un licencié est introuvable dans les formulaires de recherche ci-dessus, pensez à mettre à jour la base des licenciés dans l'onglet Import">
							<tr>
								<th class='titreForm' colspan=2>
									<label>Créer & ajouter un licencié</label>
								</th>
							</tr>
							<tr>
								<td colspan=2>
									<label class="rouge">UNIQUEMENT POUR LES NOUVEAUX<br>COMPETITEURS ETRANGERS</label>
									<hr>
								</td>
							</tr>
<!--							<tr>
								<td width=60%>
									
									<label for="matricJoueur">N° Licence (si connu) :</label>
									<input type="text" name="matricJoueur" maxlength=10 id="matricJoueur"/>
								</td>
							</tr>
-->							<tr>
								<td colspan=2>
									<label for="nomJoueur">Nom :</label>
									<input type="text" name="nomJoueur" maxlength=30 id="nomJoueur"/>
								</td>
							</tr>
							<tr>
								<td colspan=2>
									<label for="prenomJoueur">Prénom :</label>
									<input type="text" name="prenomJoueur" maxlength=30 id="prenomJoueur"/>
								</td>
							</tr>
							<tr>
								<td>
									<label for="naissanceJoueur">Date Naissance :</label>
									<input type="text" name="naissanceJoueur" maxlength=10 id="naissanceJoueur" onfocus="displayCalendar(document.forms[0].naissanceJoueur,'dd/mm/yyyy',this)" >
								</td>
								<td>
									<label for="sexeJoueur">Sexe :</label>
									<select name="sexeJoueur" id="sexeJoueur" onChange="">
										<Option Value="M" SELECTED>Masculin</Option>
										<Option Value="F">Féminin</Option>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan=2><center><i>Optionnel :</i></center></td>
							</tr>
							<tr>
								<td>
									<label for="capitaineJoueur">Capit./Entr./Arbitre:</label>
									<select name="capitaineJoueur" id="capitaineJoueur">
										<Option Value="-" SELECTED>Joueur</Option>
										<Option Value="C">Capitaine</Option>
										<Option Value="E">Entraineur (non joueur)</Option>
										<Option Value="A">Arbitre (non joueur)</Option>
										<Option Value="X">Inactif (non joueur)</Option>
									</select>
								</td>
								<td>
									<label for="numeroJoueur">Numero</label>
									<input type="tel" name="numeroJoueur" maxlength=2 size="3" id="numeroJoueur">
								</td>
							</tr>
							<tr>
								<td>
									<label for="arbitreJoueur">Arbitrage :</label>
									<select name="arbitreJoueur" id="arbitreJoueur">
										<Option Value="" SELECTED>--- Aucun ---</Option>
										<Option Value="REG">Régional</Option>
										<Option Value="NAT">National</Option>
										<Option Value="INT">International</Option>
										<Option Value="OTM">Officiel table de marque</Option>
										<Option Value="JO">Jeune officiel</Option>
									</select>
								</td>
                                <td>
									<label for="niveauJoueur">Niveau :</label>
									<select name="niveauJoueur" id="niveauJoueur">
										<Option Value="" SELECTED>-</Option>
										<Option Value="A">A</Option>
										<Option Value="B">B</Option>
										<Option Value="C">C</Option>
										<Option Value="S">S</Option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<label for="numicfJoueur">Licence ICF :</label>
                                </td>
                                <td>
									<input type="tel" name="numicfJoueur" id="numicfJoueur" size="8">
								</td>
							</tr>
							<tr>
								<td colspan=2>
									<br>
									<input type="button" onclick="Add();" name="addEquipeJoueur" id="addEquipeJoueur" value="<< Ajouter">
								</td>
							</tr>
						</table>
					
							
					{/if}
					{if $Verrou == 'O'}
						<b>Vous ne pouvez pas modifier les titulaires de cette équipe :</b>
						<br>
						- La compétition est verrouillée par le responsable ou le coordinateur,
						<br>
						- ou vous n'avez pas les droits sur ce club.
					{/if}
			    </div>
						
			</form>			
		</div>	  	   

		
