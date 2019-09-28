<h2><?php echo $title; ?></h2>

<?php foreach ($matchs as $matchs_item): ?>

        <h3><?= $matchs_item['Numero_ordre']; ?></h3>
        <div class="main">
            <?= $matchs_item['Date_match']; ?> <?= $matchs_item['Heure_match']; ?>
        </div>
        <p><a href="<?= site_url('matchs/'.$matchs_item['Id']); ?>">Voir le match</a></p>

<?php endforeach; ?>
