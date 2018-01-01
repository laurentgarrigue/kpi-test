<?php /* Smarty version 2.6.18, created on 2017-12-26 21:52:24
         compiled from kppage.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'kppage.tpl', 2, false),array('modifier', 'default', 'kppage.tpl', 48, false),array('modifier', 'upper', 'kppage.tpl', 76, false),)), $this); ?>
<?php echo smarty_function_config_load(array('file' => '../../commun/MyLang.conf','section' => $this->_tpl_vars['lang']), $this);?>

<!DOCTYPE html>
<html lang="fr" xmlns:og="http://ogp.me/ns#">
    <head>
        <meta charset="utf-8" />
        <meta name="Author" Content="LG" />
        
        <!-- FB Meta -->
        <meta property="og:image" content="http://kayak-polo.info/img/newKPI2.jpg" />
        <link rel="image_src" href="http://kayak-polo.info/img/newKPI2.jpg" />
        <meta property="og:title" content="kayak-polo.info" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="http://www.kayak-polo.info"/>
        <meta property="og:description" content="FFCK - Commission Nationale d'Activité Kayak-Polo" />
        <meta property="og:site_name" content="KAYAK-POLO.INFO" />

        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="pingback" href="http://kayak-polo.info/wordpress/xmlrpc.php">
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <!-- Mobile Specific Meta -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="alternate" type="application/rss+xml" title="Kayak-polo.info &raquo; Flux" href="http://kayak-polo.info/?feed=rss2" />
        <link rel="alternate" type="application/rss+xml" title="Kayak-polo.info &raquo; Flux des commentaires" href="http://kayak-polo.info/?feed=comments-rss2" />

        <link rel='stylesheet' href='css/fullcalendar.min.css' type='text/css' media='all' />
        <link rel='stylesheet' id='material-custom-css' href='css/wordpress_material_stylesheets_styles.css?v=<?php echo $this->_tpl_vars['NUM_VERSION']; ?>
' type='text/css' media='all' />
        <link rel='stylesheet' id='material-main-css' href='css/wordpress_material_style.css?v=<?php echo $this->_tpl_vars['NUM_VERSION']; ?>
' type='text/css' media='all' />
        <link rel='stylesheet' id='my_style-css' href='css/jquery.dataTables.css?v=<?php echo $this->_tpl_vars['NUM_VERSION']; ?>
' type='text/css' media='all' />
        <link rel='stylesheet' href='css/dataTables.fixedHeader.min.css?v=<?php echo $this->_tpl_vars['NUM_VERSION']; ?>
' type='text/css' media='all' />
        <link rel="stylesheet" href="css/jquery-ui.css?v=<?php echo $this->_tpl_vars['NUM_VERSION']; ?>
">
        <link rel="stylesheet" href="css/fontawesome/font-awesome.css?v=<?php echo $this->_tpl_vars['NUM_VERSION']; ?>
">
        
        <?php $this->assign('temp', "css/".($this->_tpl_vars['contenutemplate']).".css"); ?> 
        <?php if (is_file ( $this->_tpl_vars['temp'] )): ?>
            <link type="text/css" rel="stylesheet" href="css/<?php echo $this->_tpl_vars['contenutemplate']; ?>
.css?v=<?php echo $this->_tpl_vars['NUM_VERSION']; ?>
" />
        <?php endif; ?>
        <!-- 
            Css = '' (simply, zsainto, ckca...) 
            notamment sur les pages Journee.php et Classements.php 
            intégrer en iframe : 
        -->
        <?php $this->assign('temp', "css/".($this->_tpl_vars['css_supp']).".css"); ?> 
        <?php if ($this->_tpl_vars['css_supp'] && is_file ( $this->_tpl_vars['temp'] )): ?>
            <link type="text/css" rel="stylesheet" href="css/<?php echo $this->_tpl_vars['css_supp']; ?>
.css?v=<?php echo $this->_tpl_vars['NUM_VERSION']; ?>
">
        <?php endif; ?>
        <title><?php echo ((is_array($_tmp=@$this->_config[0]['vars'][$this->_tpl_vars['title']])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['title']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['title'])); ?>
</title>
    </head>
    <body onload="testframe(); alertMsg('<?php echo $this->_tpl_vars['AlertMessage']; ?>
'); ">
        <div id="fb-root"></div>
        
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'kpheader.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['contenutemplate']).".tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'kpfooter.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        
        <script>
            masquer = 0;
        </script>

        <script type='text/javascript' src='js/jquery-1.11.2.min.js?v=<?php echo $this->_tpl_vars['NUM_VERSION']; ?>
'></script>
        <script type='text/javascript' src='js/jquery-ui-1.11.4.min.js?v=<?php echo $this->_tpl_vars['NUM_VERSION']; ?>
'></script>
        <script type='text/javascript' src='js/jquery.dataTables.min.js?v=<?php echo $this->_tpl_vars['NUM_VERSION']; ?>
'></script>
        <script type='text/javascript' src='js/dataTables.fixedHeader.min.js?v=<?php echo $this->_tpl_vars['NUM_VERSION']; ?>
'></script>
        <script type='text/javascript' src='js/bootstrap/js/bootstrap.min.js?v=<?php echo $this->_tpl_vars['NUM_VERSION']; ?>
'></script>
        <script type="text/javascript" src="js/wordpress_material_javascripts_main.js"></script>
        <script type="text/javascript" src="js/formTools.js?v=<?php echo $this->_tpl_vars['NUM_VERSION']; ?>
" defer></script>
        <?php $this->assign('temp', "js/".($this->_tpl_vars['contenutemplate']).".js"); ?> 
        <?php if (is_file ( $this->_tpl_vars['temp'] )): ?>
            <script type="text/javascript" src="js/<?php echo $this->_tpl_vars['contenutemplate']; ?>
.js?v=<?php echo $this->_tpl_vars['NUM_VERSION']; ?>
" defer></script>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['contenutemplate'] == 'kpcalendrier'): ?>
            <script type='text/javascript' src='js/moment.min.js?v=<?php echo $this->_tpl_vars['NUM_VERSION']; ?>
'></script>
            <script type='text/javascript' src='js/fullcalendar.min.js?v=<?php echo $this->_tpl_vars['NUM_VERSION']; ?>
'></script>
        <?php endif; ?>
        <?php if (((is_array($_tmp=$this->_tpl_vars['contenutemplate'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)) == 'IMPORTPCE'): ?>	
            <?php echo '
                <script>
                    Init();
                </script>

            '; ?>

        <?php endif; ?>
            
        <?php echo '
            <script>
                window.fbAsyncInit = function() {
                    FB.init({
                        appId      : \'693131394143366\',
                        xfbml      : true,
                        version    : \'v2.3\'
                    });
                };
                (function(d, s, id){
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) {return;}
                    js = d.createElement(s); js.id = id;
                    js.src = "//connect.facebook.net/en_US/sdk.js";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, \'script\', \'facebook-jssdk\'));
            </script>
            <!-- Piwik -->
            <script type="text/javascript">
                var _paq = _paq || [];
                /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
                _paq.push([\'trackPageView\']);
                _paq.push([\'enableLinkTracking\']);
                (function() {
                    var u="//poloweb.org/piwik/";
                    _paq.push([\'setTrackerUrl\', u+\'piwik.php\']);
                    _paq.push([\'setSiteId\', \'1\']);
                    var d=document, g=d.createElement(\'script\'), s=d.getElementsByTagName(\'script\')[0];
                    g.type=\'text/javascript\'; g.async=true; g.defer=true; g.src=u+\'piwik.js\'; s.parentNode.insertBefore(g,s);
                })();
            </script>
            <!-- End Piwik Code -->
        '; ?>

    
    </body>
</html>