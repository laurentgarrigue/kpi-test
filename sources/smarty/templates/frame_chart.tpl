{include file='frame_navgroup.tpl'}
<div class="container-fluid flex">
    {if $recordCompetition.Code_typeclt == 'CP'}
        {assign var='idJournee' value=$arrayListJournees[0]}
        {assign var='Etape' value=$arrayJournees[$idJournee].Etape}
        <article class="col-md-{$largeur}">
        {section name=i loop=$arrayListJournees}
            {assign var='idJournee' value=$arrayListJournees[i]}
            {if $Etape != $arrayJournees[$idJournee].Etape}
                </article><article class="col-md-{$largeur}">
            {/if}
            {assign var='Etape' value=$arrayJournees[$idJournee].Etape}
            {if $arrayJournees[$idJournee].Type == 'C'}
                <div class="padBottom table-responsive col-md-12 tablePhase">
                    <h4 class="row text-center">{$arrayJournees[$idJournee].Phase}</h4>
                    <table class='table table-striped table-condensed table-hover'>
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>{#Equipes#}</th>
                                <th class="text-center">{#Pts#}</th>
                                <th class="text-center">{#J#}</th>
                                <th class="text-center">{#Diff#}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {if $arrayJournees[$idJournee].Actif == 1}
                                {section name=j loop=$arrayEquipe_journee_publi[$idJournee]}
                                    <tr>
                                        <td class="text-center">
                                            {$arrayEquipe_journee_publi[$idJournee][j].Clt}
                                            {if $arrayEquipe_journee_publi[$idJournee][j].logo != ''}
                                                <img class="img2 pull-right" width="30" src="{$arrayEquipe_journee_publi[$idJournee][j].logo}" alt="{$arrayEquipe_journee_publi[$idJournee][j].club}" />
                                            {/if}

                                        </td>
                                        <td>
                                            <a class="btn btn-xs btn-default equipe"{if $arrayEquipe_journee_publi[$idJournee][j].Id > 0} href="frame_team.php?Team={$arrayEquipe_journee_publi[$idJournee][j].Id}&Compet={$codeCompet}&Css={$Css}&navGroup={$navGroup}" title="{#Palmares#}"{/if}>{$arrayEquipe_journee_publi[$idJournee][j].Libelle}</a>
                                        </td>
                                        <td class="text-center">{$arrayEquipe_journee_publi[$idJournee][j].Pts/100}</td>
                                        <td class="text-center">{$arrayEquipe_journee_publi[$idJournee][j].J}</td>
                                        <td class="text-center">{$arrayEquipe_journee_publi[$idJournee][j].Diff}</td>
                                    </tr>
                                {/section}
                            {else}
                                {if $arrayEquipes[$idJournee]}
                                    {foreach from=$arrayEquipes[$idJournee] key=myId item=j}
                                        <tr>
                                            <td></td>
                                            <td>
                                                <a class="btn btn-xs btn-default equipe"{if $j.Id > 0} href="frame_team.php?Team={$j.Id}&Compet={$codeCompet}&Css={$Css}&navGroup={$navGroup}" title="{#Palmares#}"{/if}>{$j.Libelle}</a>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    {/foreach}
                                {else}
                                    {section name=k loop=$arrayJournees[$idJournee].Nbequipes}
                                        <tr>
                                            <td class="text-center">{$smarty.section.k.iteration}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    {/section}
                                {/if}
                            {/if}
                        </tbody>
                    </table>
                </div>
            {elseif $arrayMatchs[$idJournee]|@count > 0}
                <div class="padBottom table-responsive col-md-12 tableMatch">
                    <h4 class="row text-center">{$arrayJournees[$idJournee].Phase}</h4>
                    {section name=j loop=$arrayMatchs[$idJournee]}
                        <div class="row cliquableNomEquipe {if !$smarty.section.j.last}padBottom{/if}">
                            {if $arrayMatchs[$idJournee][j].ScoreA > $arrayMatchs[$idJournee][j].ScoreB}
                                <div class="col-md-12 text-right chart_match">
                                    <table class="pull-right">
                                        <tr>
                                            <td rowspan="2">
                                                <span class="chart_num_match">#{$arrayMatchs[$idJournee][j].Numero_ordre}</span>
                                            </td>
                                            <td>
                                                <a class="btn btn-xs btn-primary equipe"{if $arrayMatchs[$idJournee][j].Id_equipeA > 0} href="frame_team.php?Team={$arrayMatchs[$idJournee][j].Id_equipeA}&Compet={$codeCompet}&Css={$Css}&navGroup={$navGroup}" title="{#Palmares#}"{/if}>{$arrayMatchs[$idJournee][j].EquipeA}</a>
                                                <span class="btn btn-xs btn-primary">{$arrayMatchs[$idJournee][j].ScoreA}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a class="btn btn-xs btn-default equipe"{if $arrayMatchs[$idJournee][j].Id_equipeB > 0} href="frame_team.php?Team={$arrayMatchs[$idJournee][j].Id_equipeB}&Compet={$codeCompet}&Css={$Css}&navGroup={$navGroup}" title="{#Palmares#}"{/if}>{$arrayMatchs[$idJournee][j].EquipeB}</a>
                                                <span class="btn btn-xs btn-default">{$arrayMatchs[$idJournee][j].ScoreB}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            {elseif $arrayMatchs[$idJournee][j].ScoreA < $arrayMatchs[$idJournee][j].ScoreB}
                                <div class="col-md-12 text-right chart_match">
                                    <table class="pull-right">
                                        <tr>
                                            <td rowspan="2">
                                                <span class="chart_num_match">#{$arrayMatchs[$idJournee][j].Numero_ordre}</span>
                                            </td>
                                            <td>
                                                <a class="btn btn-xs btn-primary equipe"{if $arrayMatchs[$idJournee][j].Id_equipeB > 0} href="frame_team.php?Team={$arrayMatchs[$idJournee][j].Id_equipeB}&Compet={$codeCompet}&Css={$Css}&navGroup={$navGroup}" title="{#Palmares#}"{/if}>{$arrayMatchs[$idJournee][j].EquipeB}</a>
                                                <a class="btn btn-xs btn-primary">{$arrayMatchs[$idJournee][j].ScoreB}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a class="btn btn-xs btn-default equipe"{if $arrayMatchs[$idJournee][j].Id_equipeA > 0} href="frame_team.php?Team={$arrayMatchs[$idJournee][j].Id_equipeA}&Compet={$codeCompet}&Css={$Css}&navGroup={$navGroup}" title="{#Palmares#}"{/if}>{$arrayMatchs[$idJournee][j].EquipeA}</a>
                                                <a class="btn btn-xs btn-default">{$arrayMatchs[$idJournee][j].ScoreA}</a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            {else}
                                <div class="col-md-12 text-right chart_match">
                                    <table class="pull-right">
                                        <tr>
                                            <td rowspan="2">
                                                <span class="chart_num_match">#{$arrayMatchs[$idJournee][j].Numero_ordre}</span>
                                            </td>
                                            <td>
                                                <a class="btn btn-xs btn-default equipe"{if $arrayMatchs[$idJournee][j].Id_equipeA > 0} href="frame_team.php?Team={$arrayMatchs[$idJournee][j].Id_equipeA}&Compet={$codeCompet}&Css={$Css}&navGroup={$navGroup}" title="{#Palmares#}"{/if}>{$arrayMatchs[$idJournee][j].EquipeA}</a>
                                                <a class="btn btn-xs btn-default">{$arrayMatchs[$idJournee][j].ScoreA}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a class="btn btn-xs btn-default equipe"{if $arrayMatchs[$idJournee][j].Id_equipeB > 0} href="frame_team.php?Team={$arrayMatchs[$idJournee][j].Id_equipeB}&Compet={$codeCompet}&Css={$Css}&navGroup={$navGroup}" title="{#Palmares#}"{/if}>{$arrayMatchs[$idJournee][j].EquipeB}</a>
                                                <a class="btn btn-xs btn-default">{$arrayMatchs[$idJournee][j].ScoreB}</a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            {/if}
                        </div>
                    {/section}
                </div>
            {/if}
        {/section}
        </article>
    {else}
        {section name=i loop=$arrayJournee}
            {assign var='idJournee' value=$arrayJournee[i].Id_journee}
                <article class="padBottom table-responsive col-md-12 tableJournee">
                    <div class="page-header">
                        <h4>
                            {$arrayJournee[i].Lieu} ({$arrayJournee[i].Departement}) {$arrayJournee[i].Date_debut|date_format:'%d/%m/%Y'} - {$arrayJournee[i].Date_fin|date_format:'%d/%m/%Y'}
                        </h4>
                    </div>
                    {section name=j loop=$arrayMatchs[$idJournee]}
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            {if $arrayMatchs[$idJournee][j].ScoreA > $arrayMatchs[$idJournee][j].ScoreB}
                                <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                                    <a class="btn btn-xs btn-primary equipe">{$arrayMatchs[$idJournee][j].EquipeA}</a>
                                    <a class="btn btn-xs btn-primary">{$arrayMatchs[$idJournee][j].ScoreA}</a>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                                    <a class="btn btn-xs btn-default">{$arrayMatchs[$idJournee][j].ScoreB}</a>
                                    <a class="btn btn-xs btn-default equipe">{$arrayMatchs[$idJournee][j].EquipeB}</a>
                                </div>
                            {elseif $arrayMatchs[$idJournee][j].ScoreA < $arrayMatchs[$idJournee][j].ScoreB}
                                <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                                    <a class="btn btn-xs btn-default equipe">{$arrayMatchs[$idJournee][j].EquipeA}</a>
                                    <a class="btn btn-xs btn-default">{$arrayMatchs[$idJournee][j].ScoreA}</a>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                                    <a class="btn btn-xs btn-primary">{$arrayMatchs[$idJournee][j].ScoreB}</a>
                                    <a class="btn btn-xs btn-primary equipe">{$arrayMatchs[$idJournee][j].EquipeB}</a>
                                </div>
                            {else}
                                <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                                    <a class="btn btn-xs btn-default equipe">{$arrayMatchs[$idJournee][j].EquipeA}</a>
                                    <a class="btn btn-xs btn-default">{$arrayMatchs[$idJournee][j].ScoreA}</a>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                                    <a class="btn btn-xs btn-default">{$arrayMatchs[$idJournee][j].ScoreB}</a>
                                    <a class="btn btn-xs btn-default equipe">{$arrayMatchs[$idJournee][j].EquipeB}</a>
                                </div>
                            {/if}
                        </div>
                    {/section}
                </article>
        {/section}
        
    {/if}
</div>
