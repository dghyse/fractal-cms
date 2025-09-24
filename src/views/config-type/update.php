<?php
/**
 * index.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\config
 *
 * @var \fractalCms\models\ConfigType $model
 * @var array $routes
 */

?>
<div class="row mt-3 align-items-center">
    <div class="col-sm-6">
        <p>Mettre à jour d'une config</p>
    </div>
</div>
<div class="row m-3">
    <?php
    echo $this->render('_form', [
        'model' => $model,
        'routes' => $routes]);
    ?>
</div>
