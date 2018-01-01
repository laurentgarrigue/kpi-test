<?php /* Smarty version 2.6.18, created on 2017-12-26 21:52:25
         compiled from kpfooter.tpl */ ?>
<div class="container">
    <div class="footer copyright padTopBottom">
    <?php if ($this->_tpl_vars['bPublic']): ?>
        <?php echo '
            <!-- Piwik -->
                <script type="text/javascript">
                var pkBaseURL = (("https:" == document.location.protocol) ? "https://www.poloweb.org/piwik/" : "http://www.poloweb.org/piwik/");
                document.write(unescape("%3Cscript src=\'" + pkBaseURL + "piwik.js\' type=\'text/javascript\'%3E%3C/script%3E"));
                </script><script type="text/javascript">
                try {
                var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 1);
                piwikTracker.trackPageView();
                piwikTracker.enableLinkTracking();
                } catch( err ) {}
                </script><noscript><p><img src="http://www.poloweb.org/piwik/piwik.php?idsite=1" style="border:0" alt="" /></p></noscript>
            <!-- End Piwik Tracking Code -->
        '; ?>

        <div class="flex-footer">
            <div class="btn btn-default text-center">
                <a href="https://www.facebook.com/ffckkp/" target="_blank"><img class="img-rounded" src="img/ffck_kayakpolo.jpg" alt="" height="70" border="none"></a>
                <br><?php echo $this->_config[0]['vars']['La_page_officielle']; ?>
 <a href="https://www.facebook.com/ffckkp/" target="_blank"><img src="img/facebook.png" border="none" width="20"></a>
                </div>

                <div class="btn btn-default text-center">
                <a href="https://www.facebook.com/KIPsport" target="_blank"><img class="img-rounded" src="img/KIPSport.png" height="70" border="none"></a>
                <br>
                <?php echo $this->_config[0]['vars']['Notre_partenaire']; ?>
 <a href="https://www.facebook.com/KIPsport" target="_blank"><img src="img/facebook.png" border="none" width="20"></a> et 
                <a href="https://twitter.com/KipSport" target="_blank"><img src="img/twitter.png" border="none" width="20"></a>
            </div>
        </div>
    <?php else: ?>
        <?php echo '
            <!-- Piwik -->
                <script type="text/javascript">
                var pkBaseURL = (("https:" == document.location.protocol) ? "https://www.poloweb.org/piwik/" : "http://www.poloweb.org/piwik/");
                document.write(unescape("%3Cscript src=\'" + pkBaseURL + "piwik.js\' type=\'text/javascript\'%3E%3C/script%3E"));
                </script><script type="text/javascript">
                try {
                var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 2);
                piwikTracker.trackPageView();
                piwikTracker.enableLinkTracking();
                } catch( err ) {}
                </script><noscript><p><img src="http://www.poloweb.org/piwik/piwik.php?idsite=2" style="border:0" alt="" /></p></noscript>
            <!-- End Piwik Tracking Code -->
        '; ?>

        <div class="flex-footer">
            <div class="btn btn-default text-center">
                <a href="https://www.facebook.com/ffckkp/" target="_blank"><img class="img-rounded" src="../img/ffck_kayakpolo.jpg" alt="" height="60" border="none"></a>
                <br><?php echo $this->_config[0]['vars']['La_page_officielle']; ?>
 <a href="https://www.facebook.com/ffckkp/" target="_blank"><img src="../wordpress/wp-content/plugins/social-media-feather/synved-social/image/social/regular/32x32/facebook.png" border="none" width="20"></a>
                </div>

                <div class="btn btn-default text-center">
                <a href="https://www.facebook.com/KIPsport" target="_blank"><img class="img-rounded" src="../wordpress/wp-content/uploads/2015/01/KIPSport.png" height="60" border="none"></a>
                <br>
                <?php echo $this->_config[0]['vars']['Notre_partenaire']; ?>
 <a href="https://www.facebook.com/KIPsport" target="_blank"><img src="../wordpress/wp-content/plugins/social-media-feather/synved-social/image/social/regular/32x32/facebook.png" border="none" width="20"></a> & 
                <a href="https://twitter.com/KipSport" target="_blank"><img src="../wordpress/wp-content/plugins/social-media-feather/synved-social/image/social/regular/32x32/twitter.png" border="none" width="20"></a>
            </div>
        </div>
    <?php endif; ?>
    </div>
</div>
				  