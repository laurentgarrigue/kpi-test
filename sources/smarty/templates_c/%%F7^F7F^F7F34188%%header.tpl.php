<?php /* Smarty version 2.6.18, created on 2017-12-26 22:50:09
         compiled from header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'header.tpl', 12, false),)), $this); ?>

<?php if ($this->_tpl_vars['bPublic']): ?>
	<div id="banniere">
		<img src="img/FFCK1.gif" height=99 alt="FFCK" title="FFCK" />
	</div>
<?php elseif ($this->_tpl_vars['bProd']): ?>
	<div id="banniere">
		<img src="../img/FFCK2-ADMIN.gif" height=99 alt="FFCK Administration" title="FFCK Administration" />
		<div class="connexion">
			<?php echo $this->_tpl_vars['userName']; ?>
<br><?php echo $this->_tpl_vars['user']; ?>
 (<?php echo $this->_config[0]['vars']['Profil']; ?>
 <?php echo $this->_tpl_vars['profile']; ?>
)<br>
			<?php echo $this->_config[0]['vars']['Limite']; ?>
 : <?php echo ((is_array($_tmp=@$this->_tpl_vars['Limit_Clubs'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_config[0]['vars']['Aucune']) : smarty_modifier_default($_tmp, @$this->_config[0]['vars']['Aucune'])); ?>
<br>
			<a href="GestionParamUser.php"><?php echo $this->_config[0]['vars']['Mes_parametres']; ?>
</a><br>
			<a href="UnLogin.php"><?php echo $this->_config[0]['vars']['Deconnexion']; ?>
</a><br>
            <a href="" id="masquer"><?php echo $this->_config[0]['vars']['Masquer']; ?>
</a><br>
			<?php if ($this->_tpl_vars['bMirror'] == 1): ?>
				<br>
				<span class='vert'>Base Mirror</span>
			<?php endif; ?>
		</div>
	</div>
<?php else: ?>
	<div id="banniere">
		<img src="../img/FFCK2-LOCAL.gif" height=99 alt="FFCK Mode Local" title="FFCK Mode Local" />
		<div class="connexion">
			<br>
			<br>
			<?php echo $this->_tpl_vars['userName']; ?>
<br>
			<a href="UnLogin.php"><?php echo $this->_config[0]['vars']['Deconnexion']; ?>
</a><br>
		</div>
	</div>
<?php endif; ?>