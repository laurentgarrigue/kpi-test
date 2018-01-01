<?php /* Smarty version 2.6.18, created on 2017-12-26 21:52:25
         compiled from kpmain_menu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'kpmain_menu.tpl', 32, false),)), $this); ?>

<div class="col-xs-12">

    <nav class="site-navigation navbar navbar-default navbar-mv-up" role="navigation">
        <div class="menu-short-container container-fluid">
            <!--    Brand and toggle get grouped for better mobile di…    -->
            <div class="navbar-header">
                <button class="navbar-toggle collapsed navbar-color-mod" data-target="#bs-example-navbar-collapse-1" data-toggle="collapse" type="button">
        <span class="sr-only">
            Toggle navigation
        </span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
</div>
<!--    Collect the nav links, forms, and other content f…  -->
<div id="bs-example-navbar-collapse-1" class="collapse navbar-collapse">
    <div class="menu-nav1-container">
        <ul id="menu-nav1" class="site-menu">
		<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['arraymenu']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
            <?php $this->assign('temporaire', $this->_tpl_vars['arraymenu'][$this->_sections['i']['index']]['name']); ?>
            <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-958<?php if ($this->_tpl_vars['currentmenu'] == $this->_tpl_vars['arraymenu'][$this->_sections['i']['index']]['name']): ?> active<?php endif; ?>">
                <?php if ($this->_tpl_vars['temporaire'] == 'Accueil' && $this->_tpl_vars['lang'] == 'en'): ?>
                    <a href="./?lang=en">
                <?php elseif ($this->_tpl_vars['temporaire'] == 'Accueil' && $this->_tpl_vars['lang'] == 'fr'): ?>
                    <a href="./?lang=fr">
                <?php else: ?>
                    <a href="<?php echo $this->_tpl_vars['arraymenu'][$this->_sections['i']['index']]['href']; ?>
">
                <?php endif; ?>
                    <?php echo ((is_array($_tmp=@$this->_config[0]['vars'][$this->_tpl_vars['temporaire']])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['temporaire']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['temporaire'])); ?>

                </a>
            </li>
        <?php endfor; endif; ?>
		<?php if ($this->_tpl_vars['bPublic']): ?>
			<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-958"><a href="?lang=fr"><img width="22" src="img/Pays/FRA.png" alt="FR" title="FR" /></a></li>
			<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-958"><a href="?lang=en"><img width="22" src="img/Pays/GBR.png" alt="EN" title="EN" /></a></li>
		<?php else: ?>
			<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-958"><a href="?lang=fr"><img width="22" src="../img/Pays/FRA.png" alt="FR" title="FR" /></a></li>
			<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-958"><a href="?lang=en"><img width="22" src="../img/Pays/GBR.png" alt="EN" title="EN" /></a></li>
		<?php endif; ?>
        </ul>
    </div>
</div>
            
            
 