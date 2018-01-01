<?php /* Smarty version 2.6.18, created on 2017-12-26 21:52:38
         compiled from kpmatchs.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'kpmatchs.tpl', 3, false),array('modifier', 'count', 'kpmatchs.tpl', 72, false),array('modifier', 'replace', 'kpmatchs.tpl', 158, false),array('modifier', 'substr', 'kpmatchs.tpl', 192, false),array('modifier', 'truncate', 'kpmatchs.tpl', 192, false),)), $this); ?>
<div class="container titre"> 
    <h1 class="col-xs-12"><?php echo $this->_config[0]['vars']['Matchs']; ?>

        <span class="badge pull-right"><?php echo ((is_array($_tmp=@$this->_config[0]['vars']['Saison'])) ? $this->_run_mod_handler('default', true, $_tmp, 'Saison') : smarty_modifier_default($_tmp, 'Saison')); ?>
 <?php echo $this->_tpl_vars['Saison']; ?>
</span>
    </h1>
</div>

<div class="container" id="selector">
    <article class="col-md-12 padTopBottom">
        <form method="POST" action="kpmatchs.php#containor" name="formJournee" id="formJournee" enctype="multipart/form-data">
            <input type='hidden' name='Cmd' Value=''/>
            <input type='hidden' name='ParamCmd' Value=''/>
            <input type='hidden' name='idEquipeA' Value=''/>
            <input type='hidden' name='idEquipeB' Value=''/>
            <input type='hidden' name='Pub' Value=''/>
            <input type='hidden' name='Verrou' Value=''/>
            
            <div class='col-md-1 col-sm-4 col-xs-3 selects'>
                <label for="Saison"><?php echo $this->_config[0]['vars']['Saison']; ?>
</label>
                <select name="Saison" onChange="submit()">
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
" <?php if ($this->_tpl_vars['arraySaison'][$this->_sections['i']['index']]['Code'] == $this->_tpl_vars['Saison']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['arraySaison'][$this->_sections['i']['index']]['Code']; ?>
</Option>
                    <?php endfor; endif; ?>
                </select>
            </div>
            <div class='col-md-4 col-sm-8 col-xs-8 selects'>
                <label for="Group"><?php echo $this->_config[0]['vars']['Competition']; ?>
</label>
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
            <a class="visible-xs-block pull-right" href="" id="selects_toggle">
                <img class="img-responsive" src="img/glyphicon-triangle-bottom.png" width="16">
            </a>
            <?php if ($this->_tpl_vars['arrayCompetition'][0]['Code_typeclt'] == 'CHPT'): ?>
                <div class='col-md-3 col-sm-6 col-xs-12 selects'>
                    <label for="J"><?php echo $this->_config[0]['vars']['Journee']; ?>
</label>
                    <select name="J" onChange="submit();">
                        <Option Value="*" Selected><?php echo $this->_config[0]['vars']['Toutes']; ?>
</Option>
                        <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['arrayListJournees']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                                <Option Value="<?php echo $this->_tpl_vars['arrayListJournees'][$this->_sections['i']['index']]['Id']; ?>
" <?php if ($this->_tpl_vars['idSelJournee'] == $this->_tpl_vars['arrayListJournees'][$this->_sections['i']['index']]['Id']): ?>Selected<?php endif; ?>><?php if ($this->_tpl_vars['lang'] == 'en'): ?><?php echo $this->_tpl_vars['arrayListJournees'][$this->_sections['i']['index']]['Date_debut_en']; ?>
<?php else: ?><?php echo $this->_tpl_vars['arrayListJournees'][$this->_sections['i']['index']]['Date_debut']; ?>
<?php endif; ?> - <?php echo $this->_tpl_vars['arrayListJournees'][$this->_sections['i']['index']]['Lieu']; ?>
</Option>
                        <?php endfor; endif; ?>
                    </select>
                </div>
            <?php elseif ($this->_tpl_vars['nbCompet'] > 1): ?>
                <div class='col-md-3 col-sm-6 col-xs-12 selects'>
                    <label for="Compet"><?php echo $this->_config[0]['vars']['Categorie']; ?>
Cat.</label>
                    <select name="Compet" onChange="submit();">
                        <Option Value="*" Selected><?php echo $this->_config[0]['vars']['Toutes']; ?>
</Option>
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
                                <Option Value="<?php echo $this->_tpl_vars['arrayCompetition'][$this->_sections['i']['index']]['Code']; ?>
" <?php if ($this->_tpl_vars['idSelCompet'] == $this->_tpl_vars['arrayCompetition'][$this->_sections['i']['index']]['Code']): ?>Selected<?php endif; ?>><?php echo ((is_array($_tmp=@$this->_tpl_vars['arrayCompetition'][$this->_sections['i']['index']]['Soustitre2'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['arrayCompetition'][$this->_sections['i']['index']]['Libelle']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['arrayCompetition'][$this->_sections['i']['index']]['Libelle'])); ?>
</Option>
                        <?php endfor; endif; ?>
                    </select>
                </div>
            <?php else: ?>
                <div class='col-md-3 col-sm-6 col-xs-12 selects'></div>
            <?php endif; ?>
            <div class='col-md-4 col-sm-6 col-xs-12 text-right selects'>
                <div class="row">
                    <div id="fb-root"></div>
                    <div class="fb-like" data-href="http://www.kayak-polo.info/kpmatchs.php?Group=<?php echo $this->_tpl_vars['codeCompetGroup']; ?>
&Saison=<?php echo $this->_tpl_vars['sessionSaison']; ?>
" data-layout="button" data-action="recommend" data-show-faces="false" data-share="true"></div>
                </div>
                <div class="row">
                    <?php if ($this->_tpl_vars['arrayCompetition'][0]['Code_typeclt'] == 'CHPT' && ((is_array($_tmp=$this->_tpl_vars['arrayListJournees'])) ? $this->_run_mod_handler('count', true, $_tmp) : count($_tmp)) > 0): ?>
                        <?php if ($this->_tpl_vars['idSelJournee'] == '*'): ?><?php $this->assign('selJournee', $this->_tpl_vars['arrayListJournees'][0]['Id']); ?><?php else: ?><?php $this->assign('selJournee', $this->_tpl_vars['idSelJournee']); ?><?php endif; ?>
                        <a class="btn btn-default" href='kpdetails.php?Compet=<?php echo $this->_tpl_vars['codeCompetGroup']; ?>
&Group=<?php echo $this->_tpl_vars['codeCompetGroup']; ?>
&Saison=<?php echo $this->_tpl_vars['Saison']; ?>
&Journee=<?php echo $this->_tpl_vars['selJournee']; ?>
&typ=CHPT'><?php echo $this->_config[0]['vars']['Infos']; ?>
</a>
                    <?php elseif ($this->_tpl_vars['nbCompet'] > 1): ?>
                        <?php if ($this->_tpl_vars['idSelCompet'] == '*'): ?><?php $this->assign('selCompet', $this->_tpl_vars['arrayCompetition'][0]['Code']); ?><?php else: ?><?php $this->assign('selCompet', $this->_tpl_vars['idSelCompet']); ?><?php endif; ?>
                        <a class="btn btn-default" href='kpdetails.php?Compet=<?php echo $this->_tpl_vars['selCompet']; ?>
&Group=<?php echo $this->_tpl_vars['codeCompetGroup']; ?>
&Saison=<?php echo $this->_tpl_vars['Saison']; ?>
&typ=CP'><?php echo $this->_config[0]['vars']['Infos']; ?>
</a>
                    <?php endif; ?>
                    <a class="pdfLink btn btn-default" href="PdfListeMatchs<?php if ($this->_tpl_vars['lang'] == 'en'): ?>EN<?php endif; ?>.php?S=<?php echo $this->_tpl_vars['Saison']; ?>
&Group=<?php echo $this->_tpl_vars['codeCompetGroup']; ?>
&Compet=<?php echo $this->_tpl_vars['idSelCompet']; ?>
&Journee=<?php echo $this->_tpl_vars['idSelJournee']; ?>
" Target="_blank"><img width="20" src="img/pdf.gif" alt="<?php echo $this->_config[0]['vars']['Matchs']; ?>
 (pdf)" title="<?php echo $this->_config[0]['vars']['Matchs']; ?>
 (pdf)" /></a>
                    <a class="btn btn-default" href='kpclassements.php?Compet=<?php echo $this->_tpl_vars['idSelCompet']; ?>
&Group=<?php echo $this->_tpl_vars['codeCompetGroup']; ?>
&Saison=<?php echo $this->_tpl_vars['Saison']; ?>
&Journee=<?php echo $this->_tpl_vars['idSelJournee']; ?>
'><?php echo $this->_config[0]['vars']['Classements']; ?>
...</a>
                    <a class="btn btn-default" title="<?php echo $this->_config[0]['vars']['Partager']; ?>
" data-link="http://www.kayak-polo.info/kpmatchs.php?Group=<?php echo $this->_tpl_vars['codeCompetGroup']; ?>
&Compet=<?php echo $this->_tpl_vars['idSelCompet']; ?>
&Saison=<?php echo $this->_tpl_vars['Saison']; ?>
&Journee=<?php echo $this->_tpl_vars['idSelJournee']; ?>
&lang=<?php echo $this->_tpl_vars['lang']; ?>
" id="share_btn"><img src="img/share.png" width="16"></a>
                </div>
            </div>
        </form>
    </article>
</div>
<div class="container-fluid" id="containor">
    <article class="table-responsive col-md-12 padTopBottom">
        <table class='tableau table table-striped table-condensed table-hover display compact' <?php if (is_array ( $this->_tpl_vars['arrayMatchs'][0] )): ?>id='tableMatchs_<?php echo $this->_tpl_vars['lang']; ?>
'<?php endif; ?>>
            <thead>
                <tr>
                    <th class="hidden-xs">#</th>
                    <th class="hidden-xs"><?php echo $this->_config[0]['vars']['Date']; ?>
</th>
                    <th class="hidden-xs"><?php echo $this->_config[0]['vars']['Cat']; ?>
</th>
                    <?php if ($this->_tpl_vars['arrayCompetition'][0]['Code_typeclt'] == 'CP'): ?>
                        <th class="hidden-xs"><?php echo $this->_config[0]['vars']['Poules']; ?>
</th>
                    <?php else: ?>
                        <th class="hidden-xs"><?php echo $this->_config[0]['vars']['Lieu']; ?>
</th>
                    <?php endif; ?>
                    <th class="hidden-xs"><?php echo $this->_config[0]['vars']['Terr']; ?>
</th>
                    <th class="cliquableNomEquipe hidden-xs"><?php echo $this->_config[0]['vars']['Equipe_A']; ?>
</th>
                    <th class="cliquableScore hidden-xs"><?php echo $this->_config[0]['vars']['Score']; ?>
</th>
                    <th class="cliquableNomEquipe hidden-xs"><?php echo $this->_config[0]['vars']['Equipe_B']; ?>
</th>
                    <th class="arb1 hidden-xs"><?php echo $this->_config[0]['vars']['Arbitre_1']; ?>
</th>	
                    <th class="arb2 hidden-xs"><?php echo $this->_config[0]['vars']['Arbitre_2']; ?>
</th>
                    <th class="visible-xs-block"><?php echo $this->_config[0]['vars']['Matchs']; ?>
</th>
                </tr>
            </thead>
            <tfoot class="hidden-xs">
                <tr>
                    <th class="hidden-xs">#</th>
                    <th class="hidden-xs"><?php echo $this->_config[0]['vars']['Date']; ?>
</th>
                    <th class="hidden-xs"><?php echo $this->_config[0]['vars']['Cat']; ?>
</th>
                    <?php if ($this->_tpl_vars['PhaseLibelle'] == 1): ?>
                        <th class="hidden-xs"><?php echo $this->_config[0]['vars']['Poules']; ?>
</th>
                    <?php else: ?>
                        <th class="hidden-xs"><?php echo $this->_config[0]['vars']['Lieu']; ?>
</th>
                    <?php endif; ?>
                    <th class="hidden-xs"><?php echo $this->_config[0]['vars']['Terr']; ?>
</th>
                    <th class="cliquableNomEquipe hidden-xs"><?php echo $this->_config[0]['vars']['Equipe_A']; ?>
</th>
                    <th class="cliquableScore hidden-xs"><?php echo $this->_config[0]['vars']['Score']; ?>
</th>
                    <th class="cliquableNomEquipe hidden-xs"><?php echo $this->_config[0]['vars']['Equipe_B']; ?>
</th>
                    <th class="arb1 hidden-xs"><?php echo $this->_config[0]['vars']['Arbitre_1']; ?>
</th>	
                    <th class="arb2 hidden-xs"><?php echo $this->_config[0]['vars']['Arbitre_2']; ?>
</th>	
                </tr>
            </tfoot>
            <tbody>
                <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['arrayMatchs']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                    <?php $this->assign('validation', $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Validation']); ?>
                    <?php $this->assign('statut', $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Statut']); ?>
                    <?php $this->assign('periode', $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Periode']); ?>
                    <tr class='<?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['StdOrSelected']; ?>
 <?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['past']; ?>
'>
                            <td class="hidden-xs"><?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Numero_ordre']; ?>
</td>
                            <td class="hidden-xs" data-order="<?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Date_EN']; ?>
 <?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Heure_match']; ?>
" data-filter="<?php if ($this->_tpl_vars['lang'] == 'en'): ?><?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Date_EN']; ?>
<?php else: ?><?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Date_match']; ?>
<?php endif; ?>">
                                <?php if ($this->_tpl_vars['lang'] == 'en'): ?><?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Date_EN']; ?>
<?php else: ?><?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Date_match']; ?>
<?php endif; ?><br /><span class="pull-right badge"><?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Heure_match']; ?>
</span>
                            </td>
                            <td class="hidden-xs"><?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Code_competition']; ?>
</td>
                            <?php if ($this->_tpl_vars['arrayCompetition'][0]['Code_typeclt'] == 'CP'): ?>
                                <td class="hidden-xs"><?php echo ((is_array($_tmp=@$this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Phase'])) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>
</td>
                            <?php else: ?>
                                <td class="hidden-xs"><?php echo ((is_array($_tmp=@$this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Lieu'])) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>
</td>
                            <?php endif; ?>
                            <td class="hidden-xs"><?php echo ((is_array($_tmp=@$this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Terrain'])) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>
</td>
                            <td class="text-center hidden-xs" data-filter="<?php echo ((is_array($_tmp=@$this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['EquipeA'])) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>
">
                                <a class="btn btn-xs btn-default" href="kpequipes.php?Equipe=<?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['NumA']; ?>
" title="<?php echo $this->_config[0]['vars']['Palmares']; ?>
">
                                    <?php echo ((is_array($_tmp=@$this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['EquipeA'])) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>

                                </a>
                            </td>
                            <td class="text-center hidden-xs">
                                <?php if ($this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['logoA'] != ''): ?>
                                    <img class="img2 pull-left hidden-sm hidden-xs" width="30" src="<?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['logoA']; ?>
" alt="<?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['clubA']; ?>
" />
                                <?php endif; ?>
                                <?php if ($this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['logoB'] != ''): ?>
                                    <img class="img2 pull-right hidden-sm hidden-xs" width="30" src="<?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['logoB']; ?>
" alt="<?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['clubB']; ?>
" />
                                <?php endif; ?>
                                <?php if ($this->_tpl_vars['validation'] == 'O' && $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['ScoreA'] != '?' && $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['ScoreA'] != '' && $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['ScoreB'] != '?' && $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['ScoreB'] != ''): ?>
                                    <a class="btn btn-xs btn-default" href="PdfMatchMulti.php?listMatch=<?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Id']; ?>
" Target="_blank" title="<?php echo $this->_config[0]['vars']['Feuille_marque']; ?>
">
                                    <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['ScoreA'])) ? $this->_run_mod_handler('replace', true, $_tmp, '?', '&nbsp;') : smarty_modifier_replace($_tmp, '?', '&nbsp;')))) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>
 - <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['ScoreB'])) ? $this->_run_mod_handler('replace', true, $_tmp, '?', '&nbsp;') : smarty_modifier_replace($_tmp, '?', '&nbsp;')))) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>

                                    </a>
                                    <br />
                                    <span class="statutMatch label label-success" title="<?php echo $this->_config[0]['vars']['END']; ?>
"><?php echo $this->_config[0]['vars']['END']; ?>
</span>
                                <?php elseif ($this->_tpl_vars['statut'] == 'ON' && $this->_tpl_vars['validation'] != 'O'): ?>
                                    <span class="scoreProvisoire btn btn-xs btn-warning" title="<?php echo $this->_config[0]['vars']['scoreProvisoire']; ?>
"><?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['ScoreDetailA']; ?>
 - <?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['ScoreDetailB']; ?>
</span>
                                    <br />
                                    <span class="statutMatchOn label label-info" title="<?php echo $this->_config[0]['vars'][$this->_tpl_vars['periode']]; ?>
"><?php echo $this->_config[0]['vars'][$this->_tpl_vars['periode']]; ?>
</span>
                                <?php elseif ($this->_tpl_vars['statut'] == 'END' && $this->_tpl_vars['validation'] != 'O'): ?>
                                    <span class="scoreProvisoire btn btn-xs btn-warning" role="presentation" title="<?php echo $this->_config[0]['vars']['scoreProvisoire']; ?>
"><?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['ScoreDetailA']; ?>
 - <?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['ScoreDetailB']; ?>
</span>
                                    <br />
                                    <span class="statutMatchOn label label-info" title="<?php echo $this->_config[0]['vars']['scoreProvisoire']; ?>
"><?php echo $this->_config[0]['vars']['scoreProvisoire']; ?>
</span>
                                <?php else: ?>
                                    <br />
                                    <span class="statutMatchATT label label-default" title="<?php echo $this->_config[0]['vars']['ATT']; ?>
"><?php echo $this->_config[0]['vars']['ATT']; ?>
</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center hidden-xs" data-filter="<?php echo ((is_array($_tmp=@$this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['EquipeB'])) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>
">
                                <a class="btn btn-xs btn-default" href="kpequipes.php?Equipe=<?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['NumB']; ?>
" title="<?php echo $this->_config[0]['vars']['Palmares']; ?>
">
                                    <?php echo ((is_array($_tmp=@$this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['EquipeB'])) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>

                                </a>
                            </td>
                            <td class="arb1 hidden-xs"><small><?php if ($this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Arbitre_principal'] != '-1'): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Arbitre_principal'])) ? $this->_run_mod_handler('replace', true, $_tmp, '(', '<br>(') : smarty_modifier_replace($_tmp, '(', '<br>(')); ?>
<?php else: ?>&nbsp;<?php endif; ?></small></td>
                            <td class="arb2 hidden-xs"><small><?php if ($this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Arbitre_secondaire'] != '-1'): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Arbitre_secondaire'])) ? $this->_run_mod_handler('replace', true, $_tmp, '(', '<br>(') : smarty_modifier_replace($_tmp, '(', '<br>(')); ?>
<?php else: ?>&nbsp;<?php endif; ?></small></td>
                            
                            
                            <td class="text-center visible-xs-block" 
                                data-order="<?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Date_EN']; ?>
 <?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Heure_match']; ?>
 <?php echo ((is_array($_tmp=@$this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Terrain'])) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>
"
                                data-filter="<?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['EquipeA']; ?>
 
                                            <?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['EquipeB']; ?>
 
                                            <?php if ($this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Arbitre_principal'] != '-1'): ?><?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Arbitre_principal']; ?>
<?php endif; ?> 
                                            <?php if ($this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Arbitre_secondaire'] != '-1'): ?><?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Arbitre_secondaire']; ?>
<?php endif; ?>">
                                <div class="col-xs-6">
                                    <span class="pull-left badge" title="<?php if ($this->_tpl_vars['lang'] == 'en'): ?><?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Date_EN']; ?>
<?php else: ?><?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Date_match']; ?>
<?php endif; ?>">
                                        <?php if ($this->_tpl_vars['lang'] == 'en'): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Date_EN'])) ? $this->_run_mod_handler('substr', true, $_tmp, -5) : substr($_tmp, -5)); ?>
<?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Date_match'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 5, '') : smarty_modifier_truncate($_tmp, 5, '')); ?>
<?php endif; ?>
                                        <?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Heure_match']; ?>
 - <?php echo $this->_config[0]['vars']['Terr']; ?>
 <?php echo ((is_array($_tmp=@$this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Terrain'])) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>

                                    </span>
                                </div>
                                <div class="col-xs-6">
                                    <?php if ($this->_tpl_vars['arrayCompetition'][0]['Code_typeclt'] == 'CP'): ?>
                                        <small><em><span class="pull-right"><?php echo ((is_array($_tmp=@$this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Phase'])) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>
</em></span></small>
                                    <?php else: ?>
                                        <small><em><span class="pull-right"><?php echo ((is_array($_tmp=@$this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Lieu'])) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>
</em></span></small>
                                    <?php endif; ?>
                                </div>
                                <div class="col-xs-12">
                                    <div class="btn-group btn-block" role="group">
                                        <span type="button" class="col-xs-5 text-right" href="kpequipes.php?Equipe=<?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['NumA']; ?>
" title="<?php echo $this->_config[0]['vars']['Palmares']; ?>
">
                                            <b class=""><?php echo ((is_array($_tmp=@$this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['EquipeA'])) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>
</b>
                                        </span>
                                        
                                        <?php if ($this->_tpl_vars['validation'] == 'O' && $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['ScoreA'] != '?' && $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['ScoreA'] != '' && $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['ScoreB'] != '?' && $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['ScoreB'] != ''): ?>
                                            <span type="button" class="col-xs-2 label label-success" href="PdfMatchMulti.php?listMatch=<?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Id']; ?>
" Target="_blank" title="<?php echo $this->_config[0]['vars']['Feuille_marque']; ?>
">
                                                <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['ScoreA'])) ? $this->_run_mod_handler('replace', true, $_tmp, '?', '&nbsp;') : smarty_modifier_replace($_tmp, '?', '&nbsp;')))) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>
 - <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['ScoreB'])) ? $this->_run_mod_handler('replace', true, $_tmp, '?', '&nbsp;') : smarty_modifier_replace($_tmp, '?', '&nbsp;')))) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>

                                            </span>
                                        <?php elseif ($this->_tpl_vars['statut'] == 'ON' && $this->_tpl_vars['validation'] != 'O'): ?>
                                            <span type="button" class="col-xs-2 scoreProvisoire label label-warning" title="<?php echo $this->_config[0]['vars']['scoreProvisoire']; ?>
"><?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['ScoreDetailA']; ?>
 - <?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['ScoreDetailB']; ?>
</span>
                                        <?php elseif ($this->_tpl_vars['statut'] == 'END' && $this->_tpl_vars['validation'] != 'O'): ?>
                                            <span type="button" class="col-xs-2 scoreProvisoire label label-info" role="presentation" title="<?php echo $this->_config[0]['vars']['scoreProvisoire']; ?>
"><?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['ScoreDetailA']; ?>
 - <?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['ScoreDetailB']; ?>
</span>
                                        <?php else: ?>
                                            <span type="button" class="col-xs-2 statutMatchATT label label-default" title="<?php echo $this->_config[0]['vars']['ATT']; ?>
"><?php echo $this->_config[0]['vars']['ATT']; ?>
</span>
                                        <?php endif; ?>
                                        
                                        <span type="button" class="col-xs-5 text-left" href="kpequipes.php?Equipe=<?php echo $this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['NumB']; ?>
" title="<?php echo $this->_config[0]['vars']['Palmares']; ?>
">
                                            <b class=""><?php echo ((is_array($_tmp=@$this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['EquipeB'])) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>
</b>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xs-6 text-left">
                                    <small><em><?php if ($this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Arbitre_principal'] != '-1'): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Arbitre_principal'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' (', '<br>(') : smarty_modifier_replace($_tmp, ' (', '<br>(')); ?>
<?php else: ?>&nbsp;<?php endif; ?></em></small>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <small><em><?php if ($this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Arbitre_secondaire'] != '-1'): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['arrayMatchs'][$this->_sections['i']['index']]['Arbitre_secondaire'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' (', '<br>(') : smarty_modifier_replace($_tmp, ' (', '<br>(')); ?>
<?php else: ?>&nbsp;<?php endif; ?></em></small>
                                </div>
                            </td>
                    </tr>
                <?php endfor; else: ?>
                    <tr>
                        <td colspan=13 class="text-center hidden-xs"><i><?php echo $this->_config[0]['vars']['Aucun_match']; ?>
</i></td>
                        <td align="center" class="visible-xs-block"><i><?php echo $this->_config[0]['vars']['Aucun_match']; ?>
</i></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
	</article>
</div>
<script>
    <?php if ($this->_tpl_vars['arrayCompetition'][0]['Code_typeclt'] == 'CP'): ?>
        table_ordre = [[ 1, 'asc' ], [ 4, 'asc' ]];
    <?php else: ?>
        table_ordre = [[ 0, 'asc' ]];
    <?php endif; ?>
</script>