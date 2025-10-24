<?php
/**
 * index.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package views
 *
 * @var \fractalCms\models\ConfigItem $model
 */

?>
<div class="row mt-3 align-items-center">
    <div class="col-sm-6">
        <h2>Mettre à jour une configuration</h2>
    </div>
</div>
<div class="row m-3">
    <?php
    echo $this->render('_form', [
        'model' => $model,
    ]);
    ?>
</div>
