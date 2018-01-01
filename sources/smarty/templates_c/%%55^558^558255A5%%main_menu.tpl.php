<?php /* Smarty version 2.6.18, created on 2017-12-26 22:50:09
         compiled from main_menu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'main_menu.tpl', 7, false),array('modifier', 'replace', 'main_menu.tpl', 27, false),)), $this); ?>
	 

	<ul id="nav">
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
			<?php if ($this->_tpl_vars['currentmenu'] == $this->_tpl_vars['arraymenu'][$this->_sections['i']['index']]['name']): ?>
				<li class="current"><a href="<?php echo $this->_tpl_vars['arraymenu'][$this->_sections['i']['index']]['href']; ?>
"><?php echo ((is_array($_tmp=@$this->_config[0]['vars'][$this->_tpl_vars['temporaire']])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['temporaire']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['temporaire'])); ?>
</a></li>
			<?php else: ?>
				<li <?php if ($this->_tpl_vars['arraymenu'][$this->_sections['i']['index']]['name'] == 'Forum' || $this->_tpl_vars['arraymenu'][$this->_sections['i']['index']]['name'] == 'Accueil_Public'): ?>class="forum"<?php endif; ?>>
					<a href="<?php echo $this->_tpl_vars['arraymenu'][$this->_sections['i']['index']]['href']; ?>
"><?php echo ((is_array($_tmp=@$this->_config[0]['vars'][$this->_tpl_vars['temporaire']])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['temporaire']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['temporaire'])); ?>
</a>
				</li>
			<?php endif; ?>
			
		<?php endfor; endif; ?>
		<?php if ($this->_tpl_vars['bPublic']): ?>
			<li <?php if ($this->_tpl_vars['lang'] == 'en'): ?> class="current"<?php endif; ?>><a href="?lang=en"><img width="22" src="img/Pays/GBR.png" alt="en" title="en" /></a></li>
			<li <?php if ($this->_tpl_vars['lang'] == 'fr'): ?> class="current"<?php endif; ?>><a href="?lang=fr"><img width="22" src="img/Pays/FRA.png" alt="fr" title="fr" /></a></li>
		<?php else: ?>
			<li <?php if ($this->_tpl_vars['lang'] == 'en'): ?> class="current"<?php endif; ?>><a href="?lang=en"><img width="22" src="../img/Pays/GBR.png" alt="en" title="en" /></a></li>
			<li <?php if ($this->_tpl_vars['lang'] == 'fr'): ?> class="current"<?php endif; ?>><a href="?lang=fr"><img width="22" src="../img/Pays/FRA.png" alt="fr" title="fr" /></a></li>
            <?php if ($this->_tpl_vars['currentmenu'] == 'Matchs'): ?>
                <li class="hideall"><a href=""><img src="../img/hideall2.png" height="14" alt="Masquer tout" title="Masquer tout" ></a></li>
            <?php endif; ?>
		<?php endif; ?>
	</ul>
	<?php if ($this->_tpl_vars['currentmenu'] != 'Accueil'): ?>
        <?php $this->assign('headerTitle0', ((is_array($_tmp=$this->_tpl_vars['headerTitle'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '_') : smarty_modifier_replace($_tmp, ' ', '_'))); ?>
		<span class='saison'><?php echo ((is_array($_tmp=@$this->_config[0]['vars']['Saison'])) ? $this->_run_mod_handler('default', true, $_tmp, 'Saison') : smarty_modifier_default($_tmp, 'Saison')); ?>
 <?php echo $this->_tpl_vars['Saison']; ?>
</span>
		<span class='repere'><?php echo ((is_array($_tmp=@$this->_config[0]['vars'][$this->_tpl_vars['headerTitle0']])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['headerTitle']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['headerTitle'])); ?>
</span>
		<?php if ($this->_tpl_vars['headerSubTitle']): ?>
            <?php $this->assign('headerSubTitle0', ((is_array($_tmp=$this->_tpl_vars['headerSubTitle'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '_') : smarty_modifier_replace($_tmp, ' ', '_'))); ?>
            <span class='repere'>></span>
            <span class='repere'><?php echo ((is_array($_tmp=@$this->_config[0]['vars'][$this->_tpl_vars['headerSubTitle0']])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['headerSubTitle']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['headerSubTitle'])); ?>
</span>
        <?php endif; ?>
	<?php endif; ?>