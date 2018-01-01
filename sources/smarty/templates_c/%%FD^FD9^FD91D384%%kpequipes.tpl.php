<?php /* Smarty version 2.6.18, created on 2017-12-26 21:52:47
         compiled from kpequipes.tpl */ ?>
<div class="container">
    <article class="col-md-6 padTopBottom">        
        <div class="form-horizontal">
            <label class="col-sm-2"><?php echo $this->_config[0]['vars']['Chercher']; ?>
:</label>
            <input class="col-sm-6" type="text" id="rechercheEquipe" placeholder="<?php echo $this->_config[0]['vars']['Nom_de_l_equipe']; ?>
">
            <input class="col-sm-2" type="hidden" id="equipeId" value="<?php echo $this->_tpl_vars['equipeId']; ?>
">
            <h2 class="col-sm-12 text-center" id="nomEquipe"><?php echo $this->_tpl_vars['nomEquipe']; ?>
</h2>
            <div class="form-group">
                <div class="col-sm-12 text-center" id="nomClub">
                    <a class="btn btn-xs btn-default" href='kpclubs.php?clubId=<?php echo $this->_tpl_vars['Code_club']; ?>
' title='<?php echo $this->_config[0]['vars']['Club']; ?>
'>
                        <?php echo $this->_tpl_vars['Club']; ?>

                    </a>
                </div>
            </div>
        </div>
        <?php if ($this->_tpl_vars['eColors']): ?>
            <div class="col-xs-10 col-xs-offset-1" id="equipeColors">
                <a href="<?php echo $this->_tpl_vars['eColors']; ?>
" target="_blank"><img class="img-responsive img-thumbnail" src="<?php echo $this->_tpl_vars['eColors']; ?>
" alt="<?php echo $this->_tpl_vars['nomEquipe']; ?>
"></a>
            </div>
        <?php elseif ($this->_tpl_vars['eLogo']): ?>
            <div class="col-sm-6 col-sm-offset-3 col-xs-8 col-xs-offset-2" id="equipeColors">
                <a href="kpclubs.php?clubId=<?php echo $this->_tpl_vars['Code_club']; ?>
" title='<?php echo $this->_config[0]['vars']['Club']; ?>
'><img class="img-responsive img-thumbnail" src="<?php echo $this->_tpl_vars['eLogo']; ?>
" alt="<?php echo $this->_tpl_vars['nomEquipe']; ?>
"></a>
            </div>
        <?php endif; ?>
    </article>
    
    <article class="col-md-6 padTopBottom" id="equipePalmares">        
        <?php if ($this->_tpl_vars['eTeam']): ?>
            <div class="col-sm-12" id="equipeTeam">
                <a href="<?php echo $this->_tpl_vars['eTeam']; ?>
" target="_blank"><img class="img-responsive img-thumbnail" src="<?php echo $this->_tpl_vars['eTeam']; ?>
" alt="<?php echo $this->_tpl_vars['nomEquipe']; ?>
"></a>
            </div>
        <?php endif; ?>
            <h3 class="col-sm-12"><?php echo $this->_config[0]['vars']['Palmares']; ?>
:</h3>
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
                <?php $this->assign('Saison', $this->_tpl_vars['arraySaisons'][$this->_sections['i']['index']]['Saison']); ?>
                <table class='table table-striped table-hover table-condensed' id='tableMatchs'>
                    <caption><h3><?php echo $this->_tpl_vars['Saison']; ?>
</h3></caption>
                    <tbody>        
                        <?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['arrayPalmares'][$this->_tpl_vars['Saison']]) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                            <?php if ($this->_tpl_vars['arrayPalmares'][$this->_tpl_vars['Saison']][$this->_sections['j']['index']]['Code_tour'] == 10): ?>
                                <tr>
                                    <td>
                                        <a class="btn btn-xs btn-default" href='kpclassements.php?Compet=<?php echo $this->_tpl_vars['arrayPalmares'][$this->_tpl_vars['Saison']][$this->_sections['j']['index']]['Code']; ?>
&Group=<?php echo $this->_tpl_vars['arrayPalmares'][$this->_tpl_vars['Saison']][$this->_sections['j']['index']]['Code_ref']; ?>
&Saison=<?php echo $this->_tpl_vars['arrayPalmares'][$this->_tpl_vars['Saison']][$this->_sections['j']['index']]['Saison']; ?>
' title='<?php echo $this->_config[0]['vars']['Classement']; ?>
'>
                                            <?php echo $this->_tpl_vars['arrayPalmares'][$this->_tpl_vars['Saison']][$this->_sections['j']['index']]['Competitions']; ?>

                                        </a>
                                    </td>
                                    <td>
                                        <?php echo $this->_tpl_vars['arrayPalmares'][$this->_tpl_vars['Saison']][$this->_sections['j']['index']]['Classt']; ?>

                                        <?php if ($this->_tpl_vars['arrayPalmares'][$this->_tpl_vars['Saison']][$this->_sections['j']['index']]['Classt'] > 0 && $this->_tpl_vars['arrayPalmares'][$this->_tpl_vars['Saison']][$this->_sections['j']['index']]['Classt'] <= 3): ?>
                                            <img class="pull-right" width="20" src="img/medal<?php echo $this->_tpl_vars['arrayPalmares'][$this->_tpl_vars['Saison']][$this->_sections['j']['index']]['Classt']; ?>
.gif" alt="<?php echo $this->_tpl_vars['arrayPalmares'][$this->_tpl_vars['Saison']][$this->_sections['j']['index']]['Classt']; ?>
" title="<?php echo $this->_tpl_vars['arrayPalmares'][$this->_tpl_vars['Saison']][$this->_sections['j']['index']]['Classt']; ?>
" />
                                        <?php endif; ?>

                                    </td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td class="text-right">
                                        <a class="btn btn-xs btn-default" href='kpclassements.php?Compet=<?php echo $this->_tpl_vars['arrayPalmares'][$this->_tpl_vars['Saison']][$this->_sections['j']['index']]['Code']; ?>
&Group=<?php echo $this->_tpl_vars['arrayPalmares'][$this->_tpl_vars['Saison']][$this->_sections['j']['index']]['Code_ref']; ?>
&Saison=<?php echo $this->_tpl_vars['arrayPalmares'][$this->_tpl_vars['Saison']][$this->_sections['j']['index']]['Saison']; ?>
' title='<?php echo $this->_config[0]['vars']['Classement']; ?>
'>
                                            <i><?php echo $this->_tpl_vars['arrayPalmares'][$this->_tpl_vars['Saison']][$this->_sections['j']['index']]['Competitions']; ?>
</i>
                                        </a>
                                        <i><?php echo $this->_tpl_vars['arrayPalmares'][$this->_tpl_vars['Saison']][$this->_sections['j']['index']]['Classt']; ?>
</i>
                                    </td>
                                    <td>
                                        
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endfor; endif; ?>
                    </tbody>
                </table>
            <?php endfor; else: ?>
                <em class="text-right"><?php echo $this->_config[0]['vars']['Pas_de_classement_equipe']; ?>
.</em>
            <?php endif; ?>
    </article>
</div>