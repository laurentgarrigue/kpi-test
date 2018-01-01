<?php /* Smarty version 2.6.18, created on 2017-12-26 21:52:50
         compiled from kpclubs.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'kpclubs.tpl', 6, false),)), $this); ?>
<!--<div class="container">
    <div class="col-md-9">
        <h1 class="col-md-11 col-xs-9"><?php echo $this->_config[0]['vars']['Clubs']; ?>
Clubs!</h1>
    </div>
    <div class="col-md-3">
        <span class="badge pull-right"><?php echo ((is_array($_tmp=@$this->_config[0]['vars']['Saison'])) ? $this->_run_mod_handler('default', true, $_tmp, 'Saison') : smarty_modifier_default($_tmp, 'Saison')); ?>
 <?php echo $this->_tpl_vars['Saison']; ?>
</span>
    </div>
</div>
-->
<div class="container">
    <article class="col-md-6 padTopBottom">        
        <div class="form-horizontal">
            <label class="col-sm-2"><?php echo $this->_config[0]['vars']['Chercher']; ?>
:</label>
            <input class="col-sm-6" type="text" id="rechercheClub" placeholder="<?php echo $this->_config[0]['vars']['Nom_ou_numero_de_club']; ?>
">
            <input type="hidden" id="clubId" value="<?php echo $this->_tpl_vars['clubId']; ?>
">
            <a class="btn btn-primary pull-right" href="kplogos.php"><?php echo $this->_config[0]['vars']['Tous_les_clubs']; ?>
...</a>
            <div class="row">
                <h3 class="col-xs-12" id="clubLibelle">Club:</h3>
            </div>
            <div class="row">
                <span class="col-xs-1"><img id="coord" class="img-responsive" style="cursor: pointer;" src="img/mapmarker.png" title="<?php echo $this->_config[0]['vars']['Localiser']; ?>
"></span>
                <h3 class="col-xs-4 col-xs-offset-3" id="clubLogo"></h3>
            </div>
            <div class="form-group">
                <label class="col-sm-4">CD:</label>
                <div class="col-sm-8" id="comitedep"></div>
            </div>
            <div class="form-group">
                <label class="col-sm-4">CR:</label>
                <div class="col-sm-8" id="comitereg"></div>
            </div>
            <div class="form-group">
                <label class="col-sm-4">Web:</label>
                <div class="col-sm-8" id="www"></div>
            </div>
            <div class="form-group">
                <label class="col-sm-4">email:</label>
                <div class="col-sm-8" id="email"></div>
            </div>
            <div class="form-group">
                <label class="col-sm-4"><?php echo $this->_config[0]['vars']['Adresse']; ?>
:</label>
                <div class="col-sm-8" id="postal"></div>
            </div>
            <div class="form-group">
                <label class="col-sm-4"><?php echo $this->_config[0]['vars']['Equipes']; ?>
:</label>
                <div class="col-sm-8" id="listEquipes">
                </div>
            </div>
        </div>
    </article>
    
    <article class="col-md-6 padTopBottom">        
        <div id="carte" class="col-md-12 col-sm-12 col-xs-12" style="height: 400px"></div>
        <form onsubmit="codeAddress(); event.preventDefault();">
            <input type="text" size="50" name="address" id="address" placeholder="<?php echo $this->_config[0]['vars']['Adresse_Ville_Pays']; ?>
" />
            <input type="button" value="<?php echo $this->_config[0]['vars']['Localiser']; ?>
" onclick="codeAddress();" />
        </form>
    </article>
</div>


<?php echo '
    <script type="text/javascript">
        function initialiser() {
            geocoder = new google.maps.Geocoder();
            var latlng = new google.maps.LatLng(46.85, 1.73);
            var options = {
                center: latlng,
                zoom: 5,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                panControl: true,
                zoomControl: true,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.LARGE
                },
                scrollwheel: true,
                draggable: true
            };
            carte = new google.maps.Map(document.getElementById("carte"), options);
            var infoWindow = new google.maps.InfoWindow;

            //création des marqueurs
            '; ?>
<?php echo $this->_tpl_vars['mapParam']; ?>
<?php echo '

        }
    </script>
'; ?>
