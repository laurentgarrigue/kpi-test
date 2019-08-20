{* page.tpl Smarty *}
{config_load file='../../commun/MyLang.conf' section=$lang}
{if $bPublic}{assign var=adm value=""}{else}{assign var=adm value="../"}{/if}
<!DOCTYPE html>
<html lang="fr" xmlns:og="http://ogp.me/ns#">
    <head>
        <meta charset="utf-8" />
        <meta name="Author" Content="LG" />
        
        <!-- FB Meta -->
        <meta property="og:image" content="https://www.kayak-polo.info/img/newKPI2.jpg" />
        <link rel="image_src" href="https://www.kayak-polo.info/img/newKPI2.jpg" />
        <meta property="og:title" content="kayak-polo.info" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://www.kayak-polo.info"/>
        <meta property="og:description" content="FFCK - Commission Nationale d'Activité Kayak-Polo" />
        <meta property="og:site_name" content="KAYAK-POLO.INFO" />

        <link rel="shortcut icon" type="image/x-icon" href="{$adm}favicon.ico" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="pingback" href="https://www.kayak-polo.info/wordpress/xmlrpc.php">
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <!-- Mobile Specific Meta -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="alternate" type="application/rss+xml" title="Kayak-polo.info &raquo; Flux" href="http://kayak-polo.info/?feed=rss2" />
        <link rel="alternate" type="application/rss+xml" title="Kayak-polo.info &raquo; Flux des commentaires" href="http://kayak-polo.info/?feed=comments-rss2" />

        <link rel='stylesheet' href='{$adm}css/fullcalendar.min.css' type='text/css' media='all' />
        <link rel='stylesheet' id='material-custom-css' href='{$adm}css/wordpress_material_stylesheets_styles.css?v={$NUM_VERSION}' type='text/css' media='all' />
        <link rel='stylesheet' id='material-main-css' href='{$adm}css/wordpress_material_style.css?v={$NUM_VERSION}' type='text/css' media='all' />
        <link rel='stylesheet' id='my_style-css' href='{$adm}css/jquery.dataTables.css?v={$NUM_VERSION}' type='text/css' media='all' />
        <link rel='stylesheet' href='{$adm}css/dataTables.fixedHeader.min.css?v={$NUM_VERSION}' type='text/css' media='all' />
        <link rel="stylesheet" href="{$adm}css/jquery-ui.css?v={$NUM_VERSION}">
        <link rel="stylesheet" href="{$adm}css/fontawesome/font-awesome.css?v={$NUM_VERSION}">
        
        {assign var=temp value="$adm./css/$contenutemplate.css"} 
        {if is_file($temp)}
            <link type="text/css" rel="stylesheet" href="{$adm}css/{$contenutemplate}.css?v={$NUM_VERSION}" />
        {/if}
        <!-- 
            Css = '' (simply, zsainto, ckca...) 
            notamment sur les pages Journee.php et Classements.php 
            intégrer en iframe : 
        -->
        {assign var=temp value="$adm./css/$css_supp.css"} 
        {if $css_supp && is_file($temp)}
            <link type="text/css" rel="stylesheet" href="{$adm}css/{$css_supp}.css?v={$NUM_VERSION}">
        {/if}
        <title>{$smarty.config.$title|default:$title}</title>
    </head>
    <body onload="testframe(); alertMsg('{$AlertMessage}'); ">
        <div id="fb-root"></div>
        
        {if !$skipheader}{include file='kpheader.tpl'}{/if}
        {include file="$contenutemplate.tpl"}
        {if !$skipheader}{include file='kpfooter.tpl'}{/if}
        
        <script>
            masquer = 0;
            lang = '{$lang}';
            version = '{$NUM_VERSION}';
        </script>

        <script type='text/javascript' src='{$adm}js/jquery-1.11.2.min.js?v={$NUM_VERSION}'></script>
        <script type='text/javascript' src='{$adm}js/jquery-ui-1.11.4.min.js?v={$NUM_VERSION}'></script>
        <script type='text/javascript' src='{$adm}js/jquery.dataTables.min.js?v={$NUM_VERSION}'></script>
        <script type='text/javascript' src='{$adm}js/dataTables.fixedHeader.min.js?v={$NUM_VERSION}'></script>
        <script type='text/javascript' src='{$adm}js/bootstrap/js/bootstrap.min.js?v={$NUM_VERSION}'></script>
        <script type="text/javascript" src="{$adm}js/wordpress_material_javascripts_main.js"></script>
        <script type="text/javascript" src="{$adm}js/formTools.js?v={$NUM_VERSION}" defer></script>
        {assign var=temp value="$adm./js/$contenutemplate.js"} 
        {if is_file($temp)}
            <script type="text/javascript" src="{$adm}js/{$contenutemplate}.js?v={$NUM_VERSION}" defer></script>
        {/if}
        {if $contenutemplate == 'kpcalendrier'}
            <script type='text/javascript' src='{$adm}js/moment.min.js?v={$NUM_VERSION}'></script>
            <script type='text/javascript' src='{$adm}js/fullcalendar.min.js?v={$NUM_VERSION}'></script>
        {/if}
        {if $contenutemplate|upper eq 'IMPORTPCE' }	
            {literal}
                <script>
                    Init();
                </script>

            {/literal}
        {/if}
            
        {literal}
            <script>
                window.fbAsyncInit = function() {
                    FB.init({
                        appId      : '693131394143366',
                        xfbml      : true,
                        version    : 'v2.3'
                    });
                };
                (function(d, s, id){
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) {return;}
                    js = d.createElement(s); js.id = id;
                    js.src = "//connect.facebook.net/en_US/sdk.js";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));
            </script>
            <!-- Piwik -->
            <script type="text/javascript">
                var _paq = _paq || [];
                /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
                _paq.push(['trackPageView']);
                _paq.push(['enableLinkTracking']);
                (function() {
                    var u="piwik/";
                    _paq.push(['setTrackerUrl', u+'piwik.php']);
                    _paq.push(['setSiteId', '1']);
                    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
                    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
                })();
            </script>
            <!-- End Piwik Code -->
        {/literal}
    
    </body>
</html>