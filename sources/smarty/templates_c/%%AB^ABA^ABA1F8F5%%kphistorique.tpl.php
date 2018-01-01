<?php /* Smarty version 2.6.18, created on 2017-12-26 21:52:44
         compiled from kphistorique.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'kphistorique.tpl', 7, false),)), $this); ?>
<div class="container titre">
    <div class="col-md-9">
        <h1 class="col-md-11 col-xs-9"><?php echo $this->_config[0]['vars']['Historique']; ?>
</h1>
    </div>
<!--
    <div class="col-md-3">
        <span class="badge pull-right"><?php echo ((is_array($_tmp=@$this->_config[0]['vars']['Saison'])) ? $this->_run_mod_handler('default', true, $_tmp, 'Saison') : smarty_modifier_default($_tmp, 'Saison')); ?>
 <?php echo $this->_tpl_vars['Saison']; ?>
</span>
    </div>
-->
</div>
<div class="container" id="selector">
    <article class="col-md-12 padTopBottom">
			<form class="form-inline" method="POST" action="kphistorique.php#selector" name="formHistorique" enctype="multipart/form-data">
				<input type='hidden' name='Cmd' Value='' />
				<input type='hidden' name='ParamCmd' Value='' />
				<div class='col-md-8 col-sm-8 col-xs-12 form-group'>
                    <label for="Group"><?php echo $this->_config[0]['vars']['Competition']; ?>
 :</label>
                    <select name="Group" onChange="submit();">
                        <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['arrayCompetitionGroupe']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                            <?php $this->assign('options', $this->_tpl_vars['arrayCompetitionGroupe'][$this->_sections['i']['index']]['options']); ?>
                            <?php $this->assign('label', $this->_tpl_vars['arrayCompetitionGroupe'][$this->_sections['i']['index']]['label']); ?>
                            <optgroup label="<?php echo ((is_array($_tmp=@$this->_config[0]['vars'][$this->_tpl_vars['label']])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['label']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['label'])); ?>
">
                                <?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['options']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['j']['show'] = true;
$this->_sections['j']['max'] = $this->_sections['j']['loop'];
$this->_sections['j']['step'] = 1;
$this->_sections['j']['start'] = $this->_sections['j']['step'] > 0 ? 0 : $this->_sections['j']['loop']-1;
if ($this->_sections['j']['show']) {
    $this->_sections['j']['total'] = $this->_sections['j']['loop'];
    if ($this->_sections['j']['total'] == 0)
        $this->_sections['j']['show'] = false;
} else
    $this->_sections['j']['total'] = 0;
if ($this->_sections['j']['show']):

            for ($this->_sections['j']['index'] = $this->_sections['j']['start'], $this->_sections['j']['iteration'] = 1;
                 $this->_sections['j']['iteration'] <= $this->_sections['j']['total'];
                 $this->_sections['j']['index'] += $this->_sections['j']['step'], $this->_sections['j']['iteration']++):
$this->_sections['j']['rownum'] = $this->_sections['j']['iteration'];
$this->_sections['j']['index_prev'] = $this->_sections['j']['index'] - $this->_sections['j']['step'];
$this->_sections['j']['index_next'] = $this->_sections['j']['index'] + $this->_sections['j']['step'];
$this->_sections['j']['first']      = ($this->_sections['j']['iteration'] == 1);
$this->_sections['j']['last']       = ($this->_sections['j']['iteration'] == $this->_sections['j']['total']);
?>
                                    <?php $this->assign('optionLabel', $this->_tpl_vars['options'][$this->_sections['j']['index']]['Groupe']); ?>
                                    <Option Value="<?php echo $this->_tpl_vars['options'][$this->_sections['j']['index']]['Groupe']; ?>
" <?php echo $this->_tpl_vars['options'][$this->_sections['j']['index']]['selected']; ?>
><?php echo ((is_array($_tmp=@$this->_config[0]['vars'][$this->_tpl_vars['optionLabel']])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['options'][$this->_sections['j']['index']]['Libelle']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['options'][$this->_sections['j']['index']]['Libelle'])); ?>
</Option>
                                <?php endfor; endif; ?>
                            </optgroup>
                        <?php endfor; endif; ?>
                    </select>
                </div>
				<div class='col-md-4 col-sm-4 col-xs-12 text-right'>
                    <div class="row">
                        <div class="fb-like" data-href="http://www.kayak-polo.info/kphistorique.php?Group=<?php echo $this->_tpl_vars['codeCompetGroup']; ?>
" data-layout="button" data-action="recommend" data-show-faces="false" data-share="true"></div>
                    </div>
                    <div class="row">
                        <a class="btn btn-default" title="<?php echo $this->_config[0]['vars']['Partager']; ?>
" data-link="http://www.kayak-polo.info/kphistorique.php?Group=<?php echo $this->_tpl_vars['codeCompetGroup']; ?>
&lang=<?php echo $this->_tpl_vars['lang']; ?>
" id="share_btn"><img src="img/share.png" width="16"></a>
                    </div>
                </div>
            </form>
    </article>
</div>

<div role="tabpanel" class="container-fluid">
    <!-- Nav tabs -->
    <ul class="pagination" role="tablist">
        <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['arraySaisons']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
            <li role="presentation" <?php if ($this->_sections['i']['iteration'] == 1): ?>class="active"<?php endif; ?>><a href="#saison<?php echo $this->_tpl_vars['arraySaisons'][$this->_sections['i']['index']]['saison']; ?>
" aria-controls="saison<?php echo $this->_tpl_vars['arraySaisons'][$this->_sections['i']['index']]['saison']; ?>
" role="tab" data-toggle="tab"><?php echo $this->_tpl_vars['arraySaisons'][$this->_sections['i']['index']]['saison']; ?>
</a></li>
        <?php endfor; endif; ?>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content container-fluid">
        <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['arraySaisons']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
            <?php $this->assign('codesaison', $this->_tpl_vars['arraySaisons'][$this->_sections['i']['index']]['saison']); ?>
            <article role="tabpanel" class="padTopBottom tab-pane<?php if ($this->_sections['i']['iteration'] == 1): ?> active<?php endif; ?>" id="saison<?php echo $this->_tpl_vars['arraySaisons'][$this->_sections['i']['index']]['saison']; ?>
">
                <h3 class="row">
                    <div class="col-md-6 col-sm-6">
                        <?php echo $this->_config[0]['vars']['Saison']; ?>
 <?php echo $this->_tpl_vars['arraySaisons'][$this->_sections['i']['index']]['saison']; ?>

                        <?php if ($this->_tpl_vars['arrayCompets'][$this->_tpl_vars['codesaison']][0]['LogoLink'] != ''): ?>
                            <div class="hidden-xs">
                                <?php if ($this->_tpl_vars['arrayCompets'][$this->_tpl_vars['codesaison']][0]['Web'] != ''): ?>
                                    <a href='<?php echo $this->_tpl_vars['arrayCompets'][$this->_tpl_vars['codesaison']][0]['Web']; ?>
' target='_blank'>
                                <?php endif; ?>
                                <img class="img2" id='logo' src='<?php echo $this->_tpl_vars['arrayCompets'][$this->_tpl_vars['codesaison']][0]['LogoLink']; ?>
'>
                                <?php if ($this->_tpl_vars['arrayCompets'][$this->_tpl_vars['codesaison']][0]['Web'] != ''): ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <div class="hidden-xs"></div>
                        <?php endif; ?>
                    </div>
                </h3>

                <?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['arrayCompets'][$this->_tpl_vars['codesaison']]) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['j']['show'] = true;
$this->_sections['j']['max'] = $this->_sections['j']['loop'];
$this->_sections['j']['step'] = 1;
$this->_sections['j']['start'] = $this->_sections['j']['step'] > 0 ? 0 : $this->_sections['j']['loop']-1;
if ($this->_sections['j']['show']) {
    $this->_sections['j']['total'] = $this->_sections['j']['loop'];
    if ($this->_sections['j']['total'] == 0)
        $this->_sections['j']['show'] = false;
} else
    $this->_sections['j']['total'] = 0;
if ($this->_sections['j']['show']):

            for ($this->_sections['j']['index'] = $this->_sections['j']['start'], $this->_sections['j']['iteration'] = 1;
                 $this->_sections['j']['iteration'] <= $this->_sections['j']['total'];
                 $this->_sections['j']['index'] += $this->_sections['j']['step'], $this->_sections['j']['iteration']++):
$this->_sections['j']['rownum'] = $this->_sections['j']['iteration'];
$this->_sections['j']['index_prev'] = $this->_sections['j']['index'] - $this->_sections['j']['step'];
$this->_sections['j']['index_next'] = $this->_sections['j']['index'] + $this->_sections['j']['step'];
$this->_sections['j']['first']      = ($this->_sections['j']['iteration'] == 1);
$this->_sections['j']['last']       = ($this->_sections['j']['iteration'] == $this->_sections['j']['total']);
?>
                    <?php $this->assign('codecompet', $this->_tpl_vars['arrayCompets'][$this->_tpl_vars['codesaison']][$this->_sections['j']['index']]['code']); ?>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <table class='table table-striped table-condensed table-hover' id='tableMatchs'>
                            <caption>
                                <?php if ($this->_tpl_vars['arrayCompets'][$this->_tpl_vars['codesaison']][$this->_sections['j']['index']]['Titre_actif'] != 'O' && $this->_tpl_vars['arrayCompets'][$this->_tpl_vars['codesaison']][$this->_sections['j']['index']]['Soustitre'] != ''): ?>
                                    <?php echo $this->_tpl_vars['arrayCompets'][$this->_tpl_vars['codesaison']][$this->_sections['j']['index']]['Soustitre']; ?>

                                <?php else: ?>
                                    <?php echo $this->_tpl_vars['arrayCompets'][$this->_tpl_vars['codesaison']][$this->_sections['j']['index']]['libelle']; ?>

                                <?php endif; ?>
                                <?php if ($this->_tpl_vars['arrayCompets'][$this->_tpl_vars['codesaison']][$this->_sections['j']['index']]['Soustitre2'] != ''): ?><br><?php echo $this->_tpl_vars['arrayCompets'][$this->_tpl_vars['codesaison']][$this->_sections['j']['index']]['Soustitre2']; ?>
<?php endif; ?>
                            </caption>
                            <tbody>
                                <?php unset($this->_sections['k']);
$this->_sections['k']['name'] = 'k';
$this->_sections['k']['loop'] = is_array($_loop=$this->_tpl_vars['arrayClts'][$this->_tpl_vars['codesaison']][$this->_tpl_vars['codecompet']]) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['k']['show'] = true;
$this->_sections['k']['max'] = $this->_sections['k']['loop'];
$this->_sections['k']['step'] = 1;
$this->_sections['k']['start'] = $this->_sections['k']['step'] > 0 ? 0 : $this->_sections['k']['loop']-1;
if ($this->_sections['k']['show']) {
    $this->_sections['k']['total'] = $this->_sections['k']['loop'];
    if ($this->_sections['k']['total'] == 0)
        $this->_sections['k']['show'] = false;
} else
    $this->_sections['k']['total'] = 0;
if ($this->_sections['k']['show']):

            for ($this->_sections['k']['index'] = $this->_sections['k']['start'], $this->_sections['k']['iteration'] = 1;
                 $this->_sections['k']['iteration'] <= $this->_sections['k']['total'];
                 $this->_sections['k']['index'] += $this->_sections['k']['step'], $this->_sections['k']['iteration']++):
$this->_sections['k']['rownum'] = $this->_sections['k']['iteration'];
$this->_sections['k']['index_prev'] = $this->_sections['k']['index'] - $this->_sections['k']['step'];
$this->_sections['k']['index_next'] = $this->_sections['k']['index'] + $this->_sections['k']['step'];
$this->_sections['k']['first']      = ($this->_sections['k']['iteration'] == 1);
$this->_sections['k']['last']       = ($this->_sections['k']['iteration'] == $this->_sections['k']['total']);
?>
                                    <tr>
                                        <?php if ($this->_tpl_vars['arrayClts'][$this->_tpl_vars['codesaison']][$this->_tpl_vars['codecompet']][$this->_sections['k']['index']]['Code_typeclt'] == 'CHPT'): ?>
                                            <?php if ($this->_tpl_vars['arrayClts'][$this->_tpl_vars['codesaison']][$this->_tpl_vars['codecompet']][$this->_sections['k']['index']]['Clt'] > 0 && $this->_tpl_vars['arrayClts'][$this->_tpl_vars['codesaison']][$this->_tpl_vars['codecompet']][$this->_sections['k']['index']]['Clt'] <= 3): ?>
                                                <td class='medaille'><img width="28" src="img/medal<?php echo $this->_tpl_vars['arrayClts'][$this->_tpl_vars['codesaison']][$this->_tpl_vars['codecompet']][$this->_sections['k']['index']]['Clt']; ?>
.gif" alt="Podium" /></td>
                                            <?php else: ?>
                                                <td><?php echo $this->_tpl_vars['arrayClts'][$this->_tpl_vars['codesaison']][$this->_tpl_vars['codecompet']][$this->_sections['k']['index']]['Clt']; ?>
</td>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php if ($this->_tpl_vars['arrayClts'][$this->_tpl_vars['codesaison']][$this->_tpl_vars['codecompet']][$this->_sections['k']['index']]['CltNiveau'] > 0 && $this->_tpl_vars['arrayClts'][$this->_tpl_vars['codesaison']][$this->_tpl_vars['codecompet']][$this->_sections['k']['index']]['CltNiveau'] <= 3): ?>
                                                <td class='medaille'><img width="28" src="img/medal<?php echo $this->_tpl_vars['arrayClts'][$this->_tpl_vars['codesaison']][$this->_tpl_vars['codecompet']][$this->_sections['k']['index']]['CltNiveau']; ?>
.gif" alt="Podium" /></td>
                                            <?php else: ?>
                                                <td><?php echo $this->_tpl_vars['arrayClts'][$this->_tpl_vars['codesaison']][$this->_tpl_vars['codecompet']][$this->_sections['k']['index']]['CltNiveau']; ?>
</td>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <td class="cliquableNomEquipe">
											<?php if ($this->_tpl_vars['arrayClts'][$this->_tpl_vars['codesaison']][$this->_tpl_vars['codecompet']][$this->_sections['k']['index']]['logo'] != ''): ?>
												<img class="img2 pull-left" width="28" src="<?php echo $this->_tpl_vars['arrayClts'][$this->_tpl_vars['codesaison']][$this->_tpl_vars['codecompet']][$this->_sections['k']['index']]['logo']; ?>
" alt="<?php echo $this->_tpl_vars['arrayClts'][$this->_tpl_vars['codesaison']][$this->_tpl_vars['codecompet']][$this->_sections['k']['index']]['club']; ?>
" />
											<?php endif; ?>
                                            <a class="btn btn-xs btn-default" href='kpequipes.php?Equipe=<?php echo $this->_tpl_vars['arrayClts'][$this->_tpl_vars['codesaison']][$this->_tpl_vars['codecompet']][$this->_sections['k']['index']]['Numero']; ?>
' title='<?php echo $this->_config[0]['vars']['Palmares']; ?>
'><?php echo $this->_tpl_vars['arrayClts'][$this->_tpl_vars['codesaison']][$this->_tpl_vars['codecompet']][$this->_sections['k']['index']]['Libelle']; ?>
</a>
                                        </td>
                                    </tr>
                                <?php endfor; else: ?>
                                    <tr>
                                        <td><i class="center-block"><?php echo $this->_config[0]['vars']['Pas_de_classement']; ?>
</i></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endfor; endif; ?>
            </article>
        <?php endfor; endif; ?>
    </div>
</div>