<div class="container titre"> 
    <div class="col-md-9">
        <h1 class="col-md-11 col-xs-9">{#Matchs#}</h1>
    </div>
    <div class="col-md-3">
        <span class="badge pull-right">{$smarty.config.Saison|default:'Saison'} {$Saison}</span>
    </div>
</div>

<div class="container" id="selector">
    <article class="col-md-12 padTopBottom">
        <form method="POST" action="kpmatchs.php#containor" name="formJournee" id="formJournee" enctype="multipart/form-data">
            <input type='hidden' name='Cmd' Value=''/>
            <input type='hidden' name='ParamCmd' Value=''/>
            <input type='hidden' name='idEquipeA' Value=''/>
            <input type='hidden' name='idEquipeB' Value=''/>
            <input type='hidden' name='Pub' Value=''/>
            <input type='hidden' name='Verrou' Value=''/>

            <div class='col-md-1 col-sm-4 col-xs-4'>
                <label for="Saison">{#Saison#}</label>
                <select name="Saison" onChange="submit()">
                    {section name=i loop=$arraySaison} 
                        <Option Value="{$arraySaison[i].Code}" {if $arraySaison[i].Code eq $Saison}selected{/if}>{$arraySaison[i].Code}</Option>
                    {/section}
                </select>
            </div>
            <div class='col-md-4 col-sm-8 col-xs-12'>
                <label for="Group">{#Competition#}</label>
                <select name="Group" onChange="submit();">
                    {section name=i loop=$arrayCompetitionGroupe}
                        {assign var='options' value=$arrayCompetitionGroupe[i].options}
                        {assign var='label' value=$arrayCompetitionGroupe[i].label}
                        <optgroup label="{$smarty.config.$label|default:$label}">
                            {section name=j loop=$options}
                                {assign var='optionLabel' value=$options[j].Groupe}
                                <Option Value="{$options[j].Groupe}" {$options[j].selected}>{$smarty.config.$optionLabel|default:$options[j].Libelle}</Option>
                            {/section}
                        </optgroup>
                    {/section}
                </select>
            </div>
            {if $arrayCompetition[0].Code_typeclt == 'CHPT'}
                <div class='col-md-3 col-sm-6 col-xs-12'>
                    <label for="J">{#Journee#}</label>
                    <select name="J" onChange="submit();">
                        <Option Value="*" Selected>{#Toutes#}</Option>
                        {section name=i loop=$arrayListJournees}
                                <Option Value="{$arrayListJournees[i].Id}" {if $idSelJournee == $arrayListJournees[i].Id}Selected{/if}>{$arrayListJournees[i].Date_debut} - {$arrayListJournees[i].Lieu}</Option>
                        {/section}
                    </select>
                </div>
            {elseif $nbCompet > 1}
                <div class='col-md-3 col-sm-6 col-xs-12'>
                    <label for="Compet">{#Categorie#}Cat.</label>
                    <select name="Compet" onChange="submit();">
                        <Option Value="*" Selected>{#Toutes#}</Option>
                        {section name=i loop=$arrayCompetition}
                                <Option Value="{$arrayCompetition[i].Code}" {if $idSelCompet == $arrayCompetition[i].Code}Selected{/if}>{$arrayCompetition[i].Soustitre2|default:$arrayCompetition[i].Libelle}</Option>
                        {/section}
                    </select>
                </div>
            {else}
                <div class='col-md-3 col-sm-6 col-xs-12'></div>
            {/if}
            <div class='col-md-4 col-sm-6 col-xs-12 text-right'>
                <div class="row">
                    <div id="fb-root"></div>
                    <div class="fb-like" data-href="http://www.kayak-polo.info/kpmatchs.php?Group={$codeCompetGroup}&Saison={$sessionSaison}" data-layout="button" data-action="recommend" data-show-faces="false" data-share="true"></div>
                </div>
                <div class="row">
                    {if $arrayCompetition[0].Code_typeclt == 'CHPT' && $idSelCompet != '*'}
                        <a class="btn btn-default" href='kpdetails.php?Compet={$idSelCompet}&Group={$codeCompetGroup}&Saison={$Saison}&Journee={$idSelJournee}&typ=CHPT'>{#Infos#}</a>
                    {elseif $nbCompet > 1 && $idSelCompet != '*'}
                        <a class="btn btn-default" href='kpdetails.php?Compet={$idSelCompet}&Group={$codeCompetGroup}&Saison={$Saison}&typ=CP'>{#Infos#}</a>
                    {/if}
                    <a class="pdfLink btn btn-default" href="PdfListeMatchs{if $lang=='en'}EN{/if}.php?S={$Saison}&Compet={$codeCompetGroup}&Journee={$idSelJournee}" Target="_blank"><img width="20" src="img/pdf.gif" alt="{#Matchs#} (pdf)" title="{#Matchs#} (pdf)" /></a>
                    <a class="btn btn-default" href='kpclassements.php?Compet={$idSelCompet}&Group={$codeCompetGroup}&Saison={$Saison}&Journee={$idSelJournee}'>{#Classements#}...</a>
                    <a class="btn btn-default" title="{#Partager#}" data-link="http://www.kayak-polo.info/kpmatchs.php?Group={$codeCompetGroup}&Compet={$idSelCompet}&Saison={$Saison}&Journee={$idSelJournee}&lang={$lang}" id="share_btn"><img src="img/share.png" width="16"></a>
                </div>
            </div>
        </form>
    </article>
</div>
<div class="container-fluid" id="containor">
    <article class="table-responsive col-md-12 padTopBottom">
        <table class='tableau table table-striped table-condensed table-responsive table-hover display compact' {if is_array($arrayMatchs[0])}id='tableMatchs_{$lang}'{/if}>
            <thead>
                <tr>
                    <th>#</th>
                    <th>{#Date#}</th>
                    <th>{#Cat#}</th>
                    {if $arrayCompetition[0].Code_typeclt == 'CP'}
                        <th>{#Poules#}</th>
                    {else}
                        <th>{#Lieu#}</th>
                    {/if}
                    <th>{#Terr#}</th>
                    <th class="cliquableNomEquipe">{#Equipe_A#}</th>
                    <th class="cliquableScore">{#Score#}</th>
                    <th class="cliquableNomEquipe">{#Equipe_B#}</th>
                    <th class="arb1">{#Arbitre_1#}</th>	
                    <th class="arb2">{#Arbitre_2#}</th>	
                </tr>
            </thead>
            <tbody>
                {section name=i loop=$arrayMatchs}
                    {assign var='validation' value=$arrayMatchs[i].Validation}
                    {assign var='statut' value=$arrayMatchs[i].Statut}
                    {assign var='periode' value=$arrayMatchs[i].Periode}
                    <tr class='{$arrayMatchs[i].StdOrSelected} {$arrayMatchs[i].past}'>
                            <td>{$arrayMatchs[i].Numero_ordre}</td>
                            <td data-order="{$arrayMatchs[i].Date_EN} {$arrayMatchs[i].Heure_match}" data-filter="{$arrayMatchs[i].Date_match}">{$arrayMatchs[i].Date_match}<br /><span class="pull-right badge">{$arrayMatchs[i].Heure_match}</span></td>
                            <td>{$arrayMatchs[i].Code_competition}</td>
                            {if $arrayCompetition[0].Code_typeclt == 'CP'}
                                <td>{$arrayMatchs[i].Phase|default:'&nbsp;'}</td>
                            {else}
                                <td>{$arrayMatchs[i].Lieu|default:'&nbsp;'}</td>
                            {/if}
                            <td>{$arrayMatchs[i].Terrain|default:'&nbsp;'}</td>
                            <td class="text-center" data-filter="{$arrayMatchs[i].EquipeA|default:'&nbsp;'}">
                                <a class="btn btn-xs btn-default" href="kpequipes.php?Equipe={$arrayMatchs[i].NumA}" title="{#Palmares#}">
                                    {$arrayMatchs[i].EquipeA|default:'&nbsp;'}
                                </a>
                            </td>
                            <td class="text-center">
                                {if $arrayMatchs[i].logoA != ''}
                                    <img class="img2 pull-left hidden-sm hidden-xs" width="30" src="{$arrayMatchs[i].logoA}" alt="{$arrayMatchs[i].clubA}" />
                                {/if}
                                {if $arrayMatchs[i].logoB != ''}
                                    <img class="img2 pull-right hidden-sm hidden-xs" width="30" src="{$arrayMatchs[i].logoB}" alt="{$arrayMatchs[i].clubB}" />
                                {/if}
                                {if $validation == 'O' && $arrayMatchs[i].ScoreA != '?' && $arrayMatchs[i].ScoreA != '' && $arrayMatchs[i].ScoreB != '?' && $arrayMatchs[i].ScoreB != ''}
                                    <a class="btn btn-xs btn-default" href="PdfMatchMulti.php?listMatch={$arrayMatchs[i].Id}" Target="_blank" title="{#Feuille_marque#}">
                                    {$arrayMatchs[i].ScoreA|replace:'?':'&nbsp;'|default:'&nbsp;'} - {$arrayMatchs[i].ScoreB|replace:'?':'&nbsp;'|default:'&nbsp;'}
                                    </a>
                                    <br />
                                    <span class="statutMatch label label-success" title="{#END#}">{#END#}</span>
                                {elseif $statut == 'ON' && $validation != 'O'}
                                    <span class="scoreProvisoire btn btn-xs btn-warning" title="{#scoreProvisoire#}">{$arrayMatchs[i].ScoreDetailA} - {$arrayMatchs[i].ScoreDetailB}</span>
                                    <br />
                                    <span class="statutMatchOn label label-info" title="{$smarty.config.$periode}">{$smarty.config.$periode}</span>
                                {elseif $statut == 'END' && $validation != 'O'}
                                    <span class="scoreProvisoire btn btn-xs btn-warning" role="presentation" title="{#scoreProvisoire#}">{$arrayMatchs[i].ScoreDetailA} - {$arrayMatchs[i].ScoreDetailB}</span>
                                    <br />
                                    <span class="statutMatchOn label label-info" title="{#scoreProvisoire#}">{#scoreProvisoire#}</span>
                                {else}
                                    <br />
                                    <span class="statutMatchATT label label-default" title="{#ATT#}">{#ATT#}</span>
                                {/if}
                            </td>
                            <td class="text-center" data-filter="{$arrayMatchs[i].EquipeB|default:'&nbsp;'}">
                                <a class="btn btn-xs btn-default" href="kpequipes.php?Equipe={$arrayMatchs[i].NumB}" title="{#Palmares#}">
                                    {$arrayMatchs[i].EquipeB|default:'&nbsp;'}
                                </a>
                            </td>
                            <td class="arb1">{if $arrayMatchs[i].Arbitre_principal != '-1'}{$arrayMatchs[i].Arbitre_principal|replace:'(':'<br>('}{else}&nbsp;{/if}</td>
                            <td class="arb2">{if $arrayMatchs[i].Arbitre_secondaire != '-1'}{$arrayMatchs[i].Arbitre_secondaire|replace:'(':'<br>('}{else}&nbsp;{/if}</td>
                    </tr>
                {sectionelse}
                    <tr>
                        <td colspan=13 align=center><i>{#Aucun_match#}</i></td>
                    </tr>
                {/section}
            </tbody>
            <tfoot class="hidden-xs hidden-sm">
                <tr>
                    <th>#</th>
                    <th>{#Date#}</th>
                    <th>{#Cat#}</th>
                    {if $PhaseLibelle == 1}
                        <th>{#Poules#}</th>
                    {else}
                        <th>{#Lieu#}</th>
                    {/if}
                    <th>{#Terr#}</th>
                    <th class="cliquableNomEquipe">{#Equipe_A#}</th>
                    <th class="cliquableScore">{#Score#}</th>
                    <th class="cliquableNomEquipe">{#Equipe_B#}</th>
                    <th class="arb1">{#Arbitre_1#}</th>	
                    <th class="arb2">{#Arbitre_2#}</th>	
                </tr>
            </tfoot>
        </table>
        
	</article>
</div>
<script>
    {if $arrayCompetition[0].Code_typeclt == 'CP'}
        table_ordre = [[ 1, 'asc' ], [ 4, 'asc' ]];
    {else}
        table_ordre = [[ 0, 'asc' ]];
    {/if}
</script>