<?php /* Smarty version 2.6.18, created on 2017-12-26 21:52:35
         compiled from kpcalendrier.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'kpcalendrier.tpl', 6, false),)), $this); ?>
<div class="container titre">
    <div class="col-md-9">
        <h1 class="col-md-11 col-xs-9"><?php echo $this->_config[0]['vars']['Calendrier_des_competitions']; ?>
</h1>
    </div>
    <div class="col-md-3">
        <span class="badge pull-right"><?php echo ((is_array($_tmp=@$this->_config[0]['vars']['Saison'])) ? $this->_run_mod_handler('default', true, $_tmp, 'Saison') : smarty_modifier_default($_tmp, 'Saison')); ?>
 <?php echo $this->_tpl_vars['Saison']; ?>
</span>
    </div>
</div>

<div class="container-fluid">
    <article class="col-md-12 padTopBottom">        
		<div id='calendar_<?php echo $this->_tpl_vars['lang']; ?>
' class='fc'></div>
    </article>
</div>
		
		