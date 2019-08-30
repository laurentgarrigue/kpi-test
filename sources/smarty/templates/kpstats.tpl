{include file='kpnavgroup.tpl'}
<div class="container">
    <article class="padTopBottom table-responsive col-md-8 col-md-offset-2 tableClassement">
        <h4>{#Meilleurs_buteurs#}</h4>
        <table class='table' id='tableStats'>
            <thead>
                <tr class='header'>
                        <th></th>
                        <th>{#Nom#}</th>
                        <th>{#Equipe#}</th>
                        <th>{#Buts#}</th>
                </tr>
            </thead>
            <tbody>
                {section name=i loop=$arrayButeurs}
                    <tr class='{cycle values="impair,pair"}'>
                        <td class="text-center">{$smarty.section.i.iteration}</td>
                        <td>{$arrayButeurs[i].Nom} {$arrayButeurs[i].Prenom} #{$arrayButeurs[i].Numero}</td>
                        <td>{$arrayButeurs[i].Equipe}</td>
                        <td class="text-center">{$arrayButeurs[i].Buts}</td>
                    </tr>
                {/section}
            </tbody>
        </table>
    </article>
</div>
