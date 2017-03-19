{* main_menu.tpl Smarty *}	 

<!--
	<div id="boutonsH">
	<div id="nav2Left"></div>
	<ul id="nav2">
-->
	<ul id="nav">
		{section name=i loop=$arraymenu} 
			{assign var='temporaire' value=$arraymenu[i].name}
			{if $currentmenu eq $arraymenu[i].name}
				<li class="current"><a href="{$arraymenu[i].href}">{$smarty.config.$temporaire|default:$temporaire}</a></li>
			{else}
				<li {if $arraymenu[i].name == 'Forum' || $arraymenu[i].name == 'Accueil Public'}class="forum"{/if}>
					<a href="{$arraymenu[i].href}">{$smarty.config.$temporaire|default:$temporaire}</a>
				</li>
			{/if}
			
		{/section}
		{if $bPublic}
			<li {if $lang == 'en'} class="current"{/if}><a href="?lang=en"><img width="22" src="img/Pays/GBR.png" alt="en" title="en" /></a></li>
			<li {if $lang == 'fr'} class="current"{/if}><a href="?lang=fr"><img width="22" src="img/Pays/FRA.png" alt="fr" title="fr" /></a></li>
		{else}
			<li {if $lang == 'en'} class="current"{/if}><a href="?lang=en"><img width="22" src="../img/Pays/GBR.png" alt="en" title="en" /></a></li>
			<li {if $lang == 'fr'} class="current"{/if}><a href="?lang=fr"><img width="22" src="../img/Pays/FRA.png" alt="fr" title="fr" /></a></li>
		{/if}
	</ul>
<!--	
	<div id="nav2Right"></div>
	</div>
	<br />
-->
	{if $currentmenu != 'Accueil'}
		<span class='saison'>{$smarty.config.Saison|default:'Saison'} {$Saison}</span>
		<span class='repere'>{$smarty.config.$headerTitle|default:$headerTitle}</span>
		<span class='repere'>></span>
		<span class='repere'>{$smarty.config.$headerSubTitle|default:$headerSubTitle}</span>
	{/if}
