<?php /* Smarty version 2.6.18, created on 2017-12-26 21:52:54
         compiled from pagelogin.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'pagelogin.tpl', 1, false),array('modifier', 'default', 'pagelogin.tpl', 21, false),)), $this); ?>
<?php echo smarty_function_config_load(array('file' => '../../commun/MyLang.conf','section' => $this->_tpl_vars['lang']), $this);?>

<!DOCTYPE html>
<html lang="fr" xmlns:og="http://ogp.me/ns#">
	<head>
		<meta charset="utf-8" />
		<meta name="Author" Content="LG" />
		<meta property="og:image" content="http://kayak-polo.info/img/KPI.png" />
		<link rel="image_src" href="http://kayak-polo.info/img/KPI.png" />
		<meta property="og:type" content="article" />
		<meta property="og:site_name" content="KAYAK-POLO.INFO" />

        <link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" />
        <link href="../js/bootstrap/css/bootstrap.min.css?v=<?php echo $this->_tpl_vars['NUM_VERSION']; ?>
" rel="stylesheet" type="text/css"/>
        <?php $this->assign('temp', "../css/".($this->_tpl_vars['contenutemplate']).".css"); ?> 
        <?php if (is_file ( $this->_tpl_vars['temp'] )): ?>
            <link type="text/css" rel="stylesheet" href="../css/<?php echo $this->_tpl_vars['contenutemplate']; ?>
.css?v=<?php echo $this->_tpl_vars['NUM_VERSION']; ?>
" />
        <?php endif; ?>
			
            
            
		<title><?php echo ((is_array($_tmp=@$this->_config[0]['vars'][$this->_tpl_vars['title']])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['title']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['title'])); ?>
</title>
	</head>
	<body>

        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['contenutemplate']).".tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        
        <script src="../js/jquery-1.11.2.min.js?v=<?php echo $this->_tpl_vars['NUM_VERSION']; ?>
" type="text/javascript"></script>
        <script src="../js/bootstrap/js/bootstrap.min.js?v=<?php echo $this->_tpl_vars['NUM_VERSION']; ?>
" type="text/javascript"></script>
        <?php $this->assign('temp', "../js/".($this->_tpl_vars['contenutemplate']).".js"); ?> 
        <?php if (is_file ( $this->_tpl_vars['temp'] )): ?>
            <script src="../js/<?php echo $this->_tpl_vars['contenutemplate']; ?>
.js?v=<?php echo $this->_tpl_vars['NUM_VERSION']; ?>
" type="text/javascript"></script>
        <?php endif; ?>
	</body>
</html>