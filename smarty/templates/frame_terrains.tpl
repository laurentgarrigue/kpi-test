{section name=i loop=$arrayDates}
    {assign var='Date' value=$arrayDates[i].date}
    <div class="section terrains">
        <div class="container-fluid titre-date">
            <div class="col-md-12">
                <h2 class="col-md-11 col-xs-9">{#Matchs#} {$arrayDates[i].date}</h2>
            </div>
        </div>
                
        <div class="container-fluid" id="containor">
            <article class="table-responsive col-md-12 padTopBottom">
                <table class='tableau table table-striped table-condensed table-responsive table-hover display compact'>
                    <thead>
                        <tr class="text-center">
                            <th colspan="6" class="text-center bg-primary text-white">{#Terrain#} 1</th>
                            <th rowspan="2" class="text-center">{#Heure#}</th>
                            <th colspan="6" class="text-center bg-primary text-white">{#Terrain#} 2</th>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>{#Cat#}</th>
                            <th>{#Poules#}</th>
                            <th class="cliquableNomEquipe">{#Equipe_A#}</th>
                            <th class="cliquableScore">{#Score#}</th>
                            <th class="cliquableNomEquipe">{#Equipe_B#}</th>
                            
                            <th>#</th>
                            <th>{#Cat#}</th>
                            <th>{#Poules#}</th>
                            <th class="cliquableNomEquipe">{#Equipe_A#}</th>
                            <th class="cliquableScore">{#Score#}</th>
                            <th class="cliquableNomEquipe">{#Equipe_B#}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {section name=j loop=$arrayHeures[$Date]}
                            {assign var='Heure' value=$arrayHeures[$Date][j].heure}
                            {assign var='Match1' value=$arrayMatchs[$Date][$Heure][1]}
                            {assign var='validation1' value=$Match1.Validation}
                            {assign var='statut1' value=$Match1.Statut}
                            {assign var='periode1' value=$Match1.Periode}
                            {assign var='Match2' value=$arrayMatchs[$Date][$Heure][2]}
                            {assign var='validation2' value=$Match2.Validation}
                            {assign var='statut2' value=$Match2.Statut}
                            {assign var='periode2' value=$Match2.Periode}
                            
                            <tr class='{$Match1.past}'>
                                {if $Match1.Numero_ordre}
                                    <td>{$Match1.Numero_ordre}</td>
                                    <td class="cat" data-cat="{$Match1.Code_competition}">{$Match1.Code_competition}</td>
                                    <td>{$Match1.Phase|default:'&nbsp;'}</td>
                                    <td class="text-center" data-filter="{$Match1.EquipeA|default:'&nbsp;'}">
                                        <a class="btn btn-xs btn-default equipe">
                                            {$Match1.EquipeA|default:'&nbsp;'}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        {if $validation1 == 'O' && $Match1.ScoreA != '?' && $Match1.ScoreA != '' && $Match1.ScoreB != '?' && $Match1.ScoreB != ''}
                                            <button type="button" class="btn btn-success btn-xs" title="{#END#}">
                                                   {$Match1.ScoreA|replace:'?':'&nbsp;'|default:'&nbsp;'} - {$Match1.ScoreB|replace:'?':'&nbsp;'|default:'&nbsp;'}
                                            </button>
                                        {elseif $statut1 == 'ON' && $validation1 != 'O'}
                                            <button type="button" class="btn btn-warning btn-xs" title="{#scoreProvisoire#}">
                                                   {$Match1.ScoreA|replace:'?':'&nbsp;'|default:'&nbsp;'} - {$Match1.ScoreB|replace:'?':'&nbsp;'|default:'&nbsp;'}
                                            </button>
                                        {elseif $statut1 == 'END' && $validation1 != 'O'}
                                            <button type="button" class="btn btn-warning btn-xs" title="{#scoreProvisoire#}">
                                                   {$Match1.ScoreA|replace:'?':'&nbsp;'|default:'&nbsp;'} - {$Match1.ScoreB|replace:'?':'&nbsp;'|default:'&nbsp;'}
                                            </button>
                                        {else}
                                            <button type="button" class="btn btn-default btn-xs" title="{#ATT#}">
                                                   ATT
                                            </button>
                                        {/if}
                                    </td>
                                    <td class="text-center" data-filter="{$Match1.EquipeB|default:'&nbsp;'}">
                                        <a class="btn btn-xs btn-default equipe">
                                            {$Match1.EquipeB|default:'&nbsp;'}
                                        </a>
                                    </td>
                                {else}
                                    <td colspan="6" class="pause">{#Pause#}</td>
                                {/if}
                                
                                <td class="text-center" data-order="{$Heure}" data-filter="{$Heure}">
                                    <span class="badge">{$Heure}</span>
                                </td>
                                    
                                {if $Match2.Numero_ordre}
                                    <td>{$Match2.Numero_ordre}</td>
                                    <td class="cat" data-cat="{$Match2.Code_competition}">{$Match2.Code_competition}</td>
                                    <td>{$Match2.Phase|default:'&nbsp;'}</td>
                                    <td class="text-center" data-filter="{$Match2.EquipeA|default:'&nbsp;'}">
                                        <a class="btn btn-xs btn-default equipe">
                                            {$Match2.EquipeA|default:'&nbsp;'}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        {if $validation2 == 'O' && $Match2.ScoreA != '?' && $Match2.ScoreA != '' && $Match2.ScoreB != '?' && $Match2.ScoreB != ''}
                                            <button type="button" class="btn btn-success btn-xs" title="{#END#}">
                                                   {$Match2.ScoreA|replace:'?':'&nbsp;'|default:'&nbsp;'} - {$Match2.ScoreB|replace:'?':'&nbsp;'|default:'&nbsp;'}
                                            </button>
                                        {elseif $statut2 == 'ON' && $validation2 != 'O'}
                                            <button type="button" class="btn btn-warning btn-xs" title="{#scoreProvisoire#}">
                                                   {$Match2.ScoreA|replace:'?':'&nbsp;'|default:'&nbsp;'} - {$Match2.ScoreB|replace:'?':'&nbsp;'|default:'&nbsp;'}
                                            </button>
                                        {elseif $statut2 == 'END' && $validation2 != 'O'}
                                            <button type="button" class="btn btn-warning btn-xs" title="{#scoreProvisoire#}">
                                                   {$Match2.ScoreA|replace:'?':'&nbsp;'|default:'&nbsp;'} - {$Match2.ScoreB|replace:'?':'&nbsp;'|default:'&nbsp;'}
                                            </button>
                                        {else}
                                            <button type="button" class="btn btn-default btn-xs" title="{#ATT#}">
                                                   ATT
                                            </button>
                                        {/if}
                                    </td>
                                    <td class="text-center" data-filter="{$Match2.EquipeB|default:'&nbsp;'}">
                                        <a class="btn btn-xs btn-default equipe">
                                            {$Match2.EquipeB|default:'&nbsp;'}
                                        </a>
                                    </td>
                                {else}
                                    <td colspan="6" class="pause">{#Pause#}</td>
                                {/if}
                            </tr>
                        {sectionelse}
                            <tr>
                                <td colspan=13 align=center><i>{#Aucun_match#}</i></td>
                            </tr>
                        {/section}
                    </tbody>
                </table>
                    <p class="separator">&nbsp;</p>
                <table class='tableau table table-striped table-condensed table-responsive table-hover display compact'>
                    <thead>
                        <tr class="text-center">
                            <th colspan="6" class="text-center bg-primary text-white">{#Terrain#} 3</th>
                            <th rowspan="2" class="text-center">{#Heure#}</th>
                            <th colspan="6" class="text-center bg-primary text-white">{#Terrain#} 4</th>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>{#Cat#}</th>
                            <th>{#Poules#}</th>
                            <th class="cliquableNomEquipe">{#Equipe_A#}</th>
                            <th class="cliquableScore">{#Score#}</th>
                            <th class="cliquableNomEquipe">{#Equipe_B#}</th>
                            
                            <th>#</th>
                            <th>{#Cat#}</th>
                            <th>{#Poules#}</th>
                            <th class="cliquableNomEquipe">{#Equipe_A#}</th>
                            <th class="cliquableScore">{#Score#}</th>
                            <th class="cliquableNomEquipe">{#Equipe_B#}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {section name=j loop=$arrayHeures[$Date]}
                            {assign var='Heure' value=$arrayHeures[$Date][j].heure}
                            {assign var='Match1' value=$arrayMatchs[$Date][$Heure][3]}
                            {assign var='validation1' value=$Match1.Validation}
                            {assign var='statut1' value=$Match1.Statut}
                            {assign var='periode1' value=$Match1.Periode}
                            {assign var='Match2' value=$arrayMatchs[$Date][$Heure][4]}
                            {assign var='validation2' value=$Match2.Validation}
                            {assign var='statut2' value=$Match2.Statut}
                            {assign var='periode2' value=$Match2.Periode}
                            
                            <tr class='{$Match1.past}'>
                                {if $Match1.Numero_ordre}
                                    <td>{$Match1.Numero_ordre}</td>
                                    <td class="cat" data-cat="{$Match1.Code_competition}">{$Match1.Code_competition}</td>
                                    <td>{$Match1.Phase|default:'&nbsp;'}</td>
                                    <td class="text-center" data-filter="{$Match1.EquipeA|default:'&nbsp;'}">
                                        <a class="btn btn-xs btn-default equipe">
                                            {$Match1.EquipeA|default:'&nbsp;'}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        {if $validation1 == 'O' && $Match1.ScoreA != '?' && $Match1.ScoreA != '' && $Match1.ScoreB != '?' && $Match1.ScoreB != ''}
                                            <button type="button" class="btn btn-success btn-xs" title="{#END#}">
                                                   {$Match1.ScoreA|replace:'?':'&nbsp;'|default:'&nbsp;'} - {$Match1.ScoreB|replace:'?':'&nbsp;'|default:'&nbsp;'}
                                            </button>
                                        {elseif $statut1 == 'ON' && $validation1 != 'O'}
                                            <button type="button" class="btn btn-warning btn-xs" title="{#scoreProvisoire#}">
                                                   {$Match1.ScoreA|replace:'?':'&nbsp;'|default:'&nbsp;'} - {$Match1.ScoreB|replace:'?':'&nbsp;'|default:'&nbsp;'}
                                            </button>
                                        {elseif $statut1 == 'END' && $validation1 != 'O'}
                                            <button type="button" class="btn btn-warning btn-xs" title="{#scoreProvisoire#}">
                                                   {$Match1.ScoreA|replace:'?':'&nbsp;'|default:'&nbsp;'} - {$Match1.ScoreB|replace:'?':'&nbsp;'|default:'&nbsp;'}
                                            </button>
                                        {else}
                                            <button type="button" class="btn btn-default btn-xs" title="{#ATT#}">
                                                   ATT
                                            </button>
                                        {/if}
                                    </td>
                                    <td class="text-center" data-filter="{$Match1.EquipeB|default:'&nbsp;'}">
                                        <a class="btn btn-xs btn-default equipe">
                                            {$Match1.EquipeB|default:'&nbsp;'}
                                        </a>
                                    </td>
                                {else}
                                    <td colspan="6" class="pause">{#Pause#}</td>
                                {/if}
                                
                                <td class="text-center" data-order="{$Heure}" data-filter="{$Heure}">
                                    <span class="badge">{$Heure}</span>
                                </td>
                                    
                                {if $Match2.Numero_ordre}
                                    <td>{$Match2.Numero_ordre}</td>
                                    <td class="cat" data-cat="{$Match2.Code_competition}">{$Match2.Code_competition}</td>
                                    <td>{$Match2.Phase|default:'&nbsp;'}</td>
                                    <td class="text-center" data-filter="{$Match2.EquipeA|default:'&nbsp;'}">
                                        <a class="btn btn-xs btn-default equipe">
                                            {$Match2.EquipeA|default:'&nbsp;'}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        {if $validation2 == 'O' && $Match2.ScoreA != '?' && $Match2.ScoreA != '' && $Match2.ScoreB != '?' && $Match2.ScoreB != ''}
                                            <button type="button" class="btn btn-success btn-xs" title="{#END#}">
                                                   {$Match2.ScoreA|replace:'?':'&nbsp;'|default:'&nbsp;'} - {$Match2.ScoreB|replace:'?':'&nbsp;'|default:'&nbsp;'}
                                            </button>
                                        {elseif $statut2 == 'ON' && $validation2 != 'O'}
                                            <button type="button" class="btn btn-warning btn-xs" title="{#scoreProvisoire#}">
                                                   {$Match2.ScoreA|replace:'?':'&nbsp;'|default:'&nbsp;'} - {$Match2.ScoreB|replace:'?':'&nbsp;'|default:'&nbsp;'}
                                            </button>
                                        {elseif $statut2 == 'END' && $validation2 != 'O'}
                                            <button type="button" class="btn btn-warning btn-xs" title="{#scoreProvisoire#}">
                                                   {$Match2.ScoreA|replace:'?':'&nbsp;'|default:'&nbsp;'} - {$Match2.ScoreB|replace:'?':'&nbsp;'|default:'&nbsp;'}
                                            </button>
                                        {else}
                                            <button type="button" class="btn btn-default btn-xs" title="{#ATT#}">
                                                   ATT
                                            </button>
                                        {/if}
                                    </td>
                                    <td class="text-center" data-filter="{$Match2.EquipeB|default:'&nbsp;'}">
                                        <a class="btn btn-xs btn-default equipe">
                                            {$Match2.EquipeB|default:'&nbsp;'}
                                        </a>
                                    </td>
                                {else}
                                    <td colspan="6" class="pause">{#Pause#}</td>
                                {/if}                            </tr>
                        {sectionelse}
                            <tr>
                                <td colspan=13 align=center><i>{#Aucun_match#}</i></td>
                            </tr>
                        {/section}
                    </tbody>
                </table>

            </article>
        </div>
    </div>
{sectionelse}
    <div class="container-fluid" id="containor">
        <article class="table-responsive col-md-12 padTopBottom">
            Aucun match à afficher
        </article>
    </div>
{/section}