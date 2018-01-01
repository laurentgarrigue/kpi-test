<?php /* Smarty version 2.6.18, created on 2017-12-26 21:52:42
         compiled from kpclassements.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'kpclassements.tpl', 6, false),)), $this); ?>
<div class="container titre">
    <div class="col-md-9">
        <h1 class="col-md-11 col-xs-9"><?php echo $this->_config[0]['vars']['Classement']; ?>
</h1>
    </div>
    <div class="col-md-3">
        <span class="badge pull-right"><?php echo ((is_array($_tmp=@$this->_config[0]['vars']['Saison'])) ? $this->_run_mod_handler('default', true, $_tmp, 'Saison') : smarty_modifier_default($_tmp, 'Saison')); ?>
 <?php echo $this->_tpl_vars['Saison']; ?>
</span>
    </div>
</div>


<div class="container" id="selector">
    <article class="col-md-12 padTopBottom">
			<form method="POST" action="kpclassements.php#selector" name="formClassement" enctype="multipart/form-data">
				<input type='hidden' name='Cmd' Value='' />
				<input type='hidden' name='ParamCmd' Value='' />
				<div class='col-md-2 col-sm-6 col-xs-12'>
                    <label for="saisonTravail"><?php echo $this->_config[0]['vars']['Saison']; ?>
 :</label>
                    <select name="saisonTravail" onChange="submit()">
                        <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['arraySaison']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                            <Option Value="<?php echo $this->_tpl_vars['arraySaison'][$this->_sections['i']['index']]['Code']; ?>
" <?php if ($this->_tpl_vars['arraySaison'][$this->_sections['i']['index']]['Code'] == $this->_tpl_vars['sessionSaison']): ?>selected<?php endif; ?>><?php if ($this->_tpl_vars['arraySaison'][$this->_sections['i']['index']]['Code'] == $this->_tpl_vars['sessionSaison']): ?>=> <?php endif; ?><?php echo $this->_tpl_vars['arraySaison'][$this->_sections['i']['index']]['Code']; ?>
</Option>
                        <?php endfor; endif; ?>
                    </select>
                </div>
				<div class='col-md-4 col-sm-6 col-xs-12'>
                    <label for="codeCompetGroup"><?php echo $this->_config[0]['vars']['Competition']; ?>
 :</label>
                    <select name="codeCompetGroup" onChange="submit();">
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
				<div class='col-md-6 col-sm-12 col-xs-12 text-right'>
                    <div class="row">
                        <div class="fb-like" data-href="http://www.kayak-polo.info/kpclassements.php?Saison=<?php echo $this->_tpl_vars['sessionSaison']; ?>
&Group=<?php echo $this->_tpl_vars['codeCompetGroup']; ?>
" data-layout="button" data-action="recommend" data-show-faces="false" data-share="true"></div>
                    </div>
                    <div class="row">
                        <a class="btn btn-default" href='kphistorique.php?Group=<?php echo $this->_tpl_vars['codeCompetGroup']; ?>
'><?php echo $this->_config[0]['vars']['Historique']; ?>
</a>
                        <a class="btn btn-default" title="<?php echo $this->_config[0]['vars']['Partager']; ?>
" data-link="http://www.kayak-polo.info/kpclassements.php?Saison=<?php echo $this->_tpl_vars['sessionSaison']; ?>
&Group=<?php echo $this->_tpl_vars['codeCompetGroup']; ?>
&lang=<?php echo $this->_tpl_vars['lang']; ?>
" id="share_btn"><img src="img/share.png" width="16"></a>
                    </div>
                </div>
            </form>
    </article>
</div>

<?php if ($this->_tpl_vars['recordCompetition'][0]['BandeauLink'] != '' || $this->_tpl_vars['recordCompetition'][0]['LogoLink'] != '' || $this->_tpl_vars['recordCompetition'][0]['Web'] != ''): ?>
    <div class="container logo_lien">
        <article class="padTopBottom table-responsive col-md-6 col-md-offset-3">
            <div class="text-center">
                <?php if ($this->_tpl_vars['recordCompetition'][0]['BandeauLink'] != ''): ?>
                    <img class="img2" id='logo' src='<?php echo $this->_tpl_vars['recordCompetition'][0]['BandeauLink']; ?>
' alt="logo">
                <?php else: ?>
                    <img class="img2" id='logo' src='<?php echo $this->_tpl_vars['recordCompetition'][0]['LogoLink']; ?>
' alt="logo">
                <?php endif; ?>
                <?php if ($this->_tpl_vars['recordCompetition'][0]['Web'] != ''): ?>
                    <p><a class="text-primary" href='<?php echo $this->_tpl_vars['recordCompetition'][0]['Web']; ?>
' target='_blank'><i><?php echo $this->_tpl_vars['recordCompetition'][0]['Web']; ?>
</i></a></p>
                <?php endif; ?>
            </div>
        </article>
    </div>
<?php endif; ?>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['arrayCompetition']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
    <?php if ($this->_tpl_vars['arrayCompetition'][$this->_sections['i']['index']]['Statut'] != 'ATT'): ?>
        <?php $this->assign('codetemp', $this->_tpl_vars['arrayCompetition'][$this->_sections['i']['index']]['codeCompet']); ?>
        <div class="container">
            <article class="padTopBottom<?php if ($this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][0]['Code_typeclt'] != 'CHPT'): ?> table-responsive col-md-6 col-md-offset-3<?php else: ?> col-md-12<?php endif; ?>">
                <div class="page-header">
                    <?php $this->assign('idCompet', $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][0]['CodeCompet']); ?>
                    <?php $this->assign('idTour', $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][0]['Code_tour']); ?>
                    <?php $this->assign('idSaison', $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][0]['CodeSaison']); ?>
                    <h3>
                        <?php if ($this->_tpl_vars['arrayCompetition'][$this->_sections['i']['index']]['Titre_actif'] == 'O'): ?>
                            <span class="titreCompet<?php if ($this->_sections['i']['first']): ?> first<?php endif; ?>"><?php echo $this->_tpl_vars['arrayCompetition'][$this->_sections['i']['index']]['libelleCompet']; ?>
</span>
                        <?php else: ?>
                            <span class="titreCompet<?php if ($this->_sections['i']['first']): ?> first<?php endif; ?>"><?php echo $this->_tpl_vars['arrayCompetition'][$this->_sections['i']['index']]['Soustitre']; ?>
</span>
                        <?php endif; ?>
                        <?php if ($this->_tpl_vars['arrayCompetition'][$this->_sections['i']['index']]['Soustitre'] != ''): ?>
                            <span class="soustitreCompet"><br><?php echo $this->_tpl_vars['arrayCompetition'][$this->_sections['i']['index']]['Soustitre2']; ?>
</span>
                        <?php endif; ?>
                        <div class='pull-right'>
                            <?php if ($this->_tpl_vars['arrayCompetition'][$this->_sections['i']['index']]['Statut'] != 'ON' || $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][0]['Code_typeclt'] != 'CP'): ?>
                                <a class="btn btn-default" href='kpclassement.php?Saison=<?php echo $this->_tpl_vars['idSaison']; ?>
&Group=<?php echo $this->_tpl_vars['codeCompetGroup']; ?>
&Compet=<?php echo $this->_tpl_vars['idCompet']; ?>
'><?php echo $this->_config[0]['vars']['Deroulement']; ?>
...</a>
                            <?php endif; ?>
                            <?php if ($this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][0]['existMatch'] == 1): ?>
                               <a class="btn btn-default" href='kpmatchs.php?Saison=<?php echo $this->_tpl_vars['idSaison']; ?>
&Group=<?php echo $this->_tpl_vars['codeCompetGroup']; ?>
&Compet=<?php echo $this->_tpl_vars['idCompet']; ?>
'><?php echo $this->_config[0]['vars']['Matchs']; ?>
...</a>
                            <?php endif; ?>
                            <?php if ($this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][0]['Code_typeclt'] == 'CHPT'): ?>
                                <a class="btn btn-default" href='kpdetails.php?Compet=<?php echo $this->_tpl_vars['idCompet']; ?>
&Group=<?php echo $this->_tpl_vars['codeCompetGroup']; ?>
&Saison=<?php echo $this->_tpl_vars['idSaison']; ?>
&Journee=<?php echo $this->_tpl_vars['idSelJournee']; ?>
&typ=CHPT'><?php echo $this->_config[0]['vars']['Infos']; ?>
</a>
                            <?php else: ?>
                                <a class="btn btn-default" href='kpdetails.php?Compet=<?php echo $this->_tpl_vars['idCompet']; ?>
&Group=<?php echo $this->_tpl_vars['codeCompetGroup']; ?>
&Saison=<?php echo $this->_tpl_vars['idSaison']; ?>
&typ=CP'><?php echo $this->_config[0]['vars']['Infos']; ?>
</a>
                            <?php endif; ?>
                        </div>

                    </h3>
                    <?php if ($this->_tpl_vars['arrayCompetition'][$this->_sections['i']['index']]['Statut'] != 'END'): ?>
                        <div class="label label-warning"><?php echo $this->_config[0]['vars']['Classement_provisoire']; ?>
</div>
                    <?php endif; ?>
                </div>
                <?php if ($this->_tpl_vars['arrayCompetition'][$this->_sections['i']['index']]['Statut'] == 'END' || $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][0]['Code_typeclt'] == 'CHPT'): ?>
                    <table class='table table-striped table-condensed table-hover' id='tableMatchs'>
                        <thead>
                            <?php if ($this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][0]['Code_typeclt'] == 'CHPT'): ?>
                                <tr>
                                    <th></th>
                                    <th>#</th>
                                    <th><?php echo $this->_config[0]['vars']['Equipe']; ?>
</th>
                                    <th><?php echo $this->_config[0]['vars']['Pts']; ?>
</th>
                                    <th><?php echo $this->_config[0]['vars']['J']; ?>
</th>
                                    <th><?php echo $this->_config[0]['vars']['G']; ?>
</th>
                                    <th><?php echo $this->_config[0]['vars']['N']; ?>
</th>
                                    <th><?php echo $this->_config[0]['vars']['P']; ?>
</th>
                                    <th><?php echo $this->_config[0]['vars']['F']; ?>
</th>
                                    <th>+</th>
                                    <th>-</th>
                                    <th><?php echo $this->_config[0]['vars']['Diff']; ?>
</th>
                                </tr>
                            <?php else: ?>
                                                            <?php endif; ?>
                        </thead>
                        <tbody>        
                            <?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']]) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                                <tr>
                                    <?php if ($this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['Code_typeclt'] == 'CHPT' && $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['Code_tour'] == '10' && $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['Clt'] <= 3 && $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['Clt'] > 0 && $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['Statut'] == 'END'): ?>
                                        <td class='medaille text-center'><img width="30" src="img/medal<?php echo $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['Clt']; ?>
.gif" alt="Podium" title="Podium" /></td>
                                    <?php elseif ($this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['Code_typeclt'] == 'CP' && $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['Code_tour'] == '10' && $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['CltNiveau'] <= 3 && $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['CltNiveau'] > 0 && $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['Statut'] == 'END'): ?>
                                        <td class='medaille text-center'><img width="30" src="img/medal<?php echo $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['CltNiveau']; ?>
.gif" alt="Podium" title="Podium" /></td>
                                    <?php elseif ($this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['Code_typeclt'] == 'CHPT'): ?>
                                        <?php if ($this->_sections['j']['iteration'] <= $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['Qualifies']): ?>
                                            <td class='qualifie text-center'><img width="30" src="img/up.gif" alt="Qualifié" title="Qualifié" /></td>
                                        <?php elseif ($this->_sections['j']['iteration'] > $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['Nb_equipes'] - $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['Elimines']): ?>
                                            <td class='elimine text-center'><img width="30" src="img/down.gif" alt="Eliminés" title="Eliminés" /></td>
                                        <?php else: ?>
                                            <td>&nbsp;</td>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if ($this->_sections['j']['iteration'] <= $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['Qualifies']): ?>
                                            <td class='qualifie text-center'><img width="30" src="img/up.gif" alt="Qualifié" title="Qualifié" /></td>
                                        <?php elseif ($this->_sections['j']['iteration'] > $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['Nb_equipes'] - $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['Elimines']): ?>
                                            <td class='elimine text-center'><img width="30" src="img/down.gif" alt="Eliminés" title="Eliminés" /></td>
                                        <?php else: ?>
                                            <td>&nbsp;</td>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if ($this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['Code_typeclt'] == 'CHPT'): ?>
                                        <td class="droite">
                                            <?php echo $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['Clt']; ?>

                                            <?php if ($this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['logo'] != ''): ?>
                                                <img class="img2 pull-right" width="30" src="<?php echo $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['logo']; ?>
" alt="<?php echo $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['club']; ?>
" />
                                            <?php endif; ?>
                                        </td>
                                        <td class="cliquableNomEquipe"><a class="btn btn-xs btn-default" href='kpequipes.php?Equipe=<?php echo $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['Numero']; ?>
' title='<?php echo $this->_config[0]['vars']['Palmares']; ?>
'><?php echo $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['Libelle']; ?>
</a></td>
                                        <td><?php echo $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['Pts']/100; ?>
</td>
                                        <td><?php echo $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['J']; ?>
</td>
                                        <td><?php echo $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['G']; ?>
</td>
                                        <td><?php echo $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['N']; ?>
</td>
                                        <td><?php echo $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['P']; ?>
</td>
                                        <td><?php echo $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['F']; ?>
</td>
                                        <td><?php echo $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['Plus']; ?>
</td>
                                        <td><?php echo $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['Moins']; ?>
</td>
                                        <td><?php echo $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['Diff']; ?>
</td>
                                    <?php else: ?>
                                        <td class="droite">
                                            <?php echo $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['CltNiveau']; ?>

                                        </td>
                                        <td class="cliquableNomEquipe">
                                            <?php if ($this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['logo'] != ''): ?>
                                                <img class="img2 pull-left" width="30" src="<?php echo $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['logo']; ?>
" alt="<?php echo $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['club']; ?>
" />
                                            <?php endif; ?>
                                            <a class="btn btn-xs btn-default" href='kpequipes.php?Equipe=<?php echo $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['Numero']; ?>
' title='<?php echo $this->_config[0]['vars']['Palmares']; ?>
'><?php echo $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][$this->_sections['j']['index']]['Libelle']; ?>
</a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endfor; endif; ?>
                        </tbody>
                    </table>
                <?php elseif ($this->_tpl_vars['arrayCompetition'][$this->_sections['i']['index']]['Statut'] == 'ON' && $this->_tpl_vars['arrayEquipe_publi'][$this->_tpl_vars['codetemp']][0]['Code_typeclt'] == 'CP'): ?>
                    <div class='pull-left'>
                        <a class="btn btn-default" href='kpclassement.php?Saison=<?php echo $this->_tpl_vars['idSaison']; ?>
&Group=<?php echo $this->_tpl_vars['codeCompetGroup']; ?>
&Compet=<?php echo $this->_tpl_vars['idCompet']; ?>
'><?php echo $this->_config[0]['vars']['Deroulement']; ?>
...</a>
                    </div>
                <?php endif; ?>
            </article>
        </div>
    <?php endif; ?>
<?php endfor; endif; ?>