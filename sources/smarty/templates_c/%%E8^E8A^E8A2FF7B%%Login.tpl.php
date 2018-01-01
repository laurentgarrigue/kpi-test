<?php /* Smarty version 2.6.18, created on 2017-12-26 21:52:54
         compiled from Login.tpl */ ?>
<div class="container">
    <div class="col-md-6 col-md-offset-3">
        <h2 class="form-signin-heading text-center"><img src="../img/KPI.png" alt="KPI" height="60"> <?php echo $this->_config[0]['vars']['Connexion']; ?>
</h2>
    </div>
    <div class="col-md-4 col-md-offset-4">
        <form class="form-signin2" method="POST" action="Login.php" name="formLogin" id="formLogin" enctype="multipart/form-data">
            <label for="User"><?php echo $this->_config[0]['vars']['Identifiant']; ?>
</label>
            <input type="tel" name="User" id="idUser" class="form-control" placeholder="<?php echo $this->_config[0]['vars']['Identifiant']; ?>
" required autofocus>
            <div id="connect">
                <label for="password"><?php echo $this->_config[0]['vars']['Mot_de_passe']; ?>
</label>
                <input type="password" name="Pwd" id="idPwd" class="form-control" placeholder="<?php echo $this->_config[0]['vars']['Mot_de_passe']; ?>
" required>
            </div>
            <div id="renv">
                <label for="Mel">Email</label>
                <input type="email" name="Mel" id="Mel" class="form-control" placeholder="E-mail" required>
            </div>
            <br>
            <input class="btn btn-lg btn-primary btn-block" type="button" name="login" id="login" value="<?php echo $this->_config[0]['vars']['Connexion']; ?>
">
            <input class="btn btn-lg btn-primary btn-block" type="button" name="Renvoyer" id="Renvoyer" value="<?php echo $this->_config[0]['vars']['Renvoyer']; ?>
">
            <input class="btn btn-lg btn-primary btn-block" type="button" name="Annuler" id="Annuler" value="<?php echo $this->_config[0]['vars']['Annuler']; ?>
" onClick="return false">

            <input type="hidden" name="Mode" id="Mode" value="Connexion">        
            <br>
            <br>
            <p class="text-center"><a href="mailto:laurent@poloweb.org?subject=Demande d'identifiant administrateur kayak-polo.info&body=Nom:%0D%0APrénom:%0D%0AN°Licence:%0D%0AFonctions fédérales:%0D%0AUn petit mot ?"><?php echo $this->_config[0]['vars']['Demander_identifiant']; ?>
</a></p>
            <br>
            <p class="text-center" id="perdu"><a href="" onClick="return false"><?php echo $this->_config[0]['vars']['j_ai_perdu_mon_mdp']; ?>
</a></p>
            <br>
            <br>
        </form>
    </div>
    <div class="col-md-6 col-md-offset-3 text-center">
        <p><?php echo $this->_config[0]['vars']['Vous_devez_vous_identifier']; ?>
 (<a href="../"><?php echo $this->_config[0]['vars']['Retour']; ?>
</a>)</p>
    </div>
</div> <!-- /container -->