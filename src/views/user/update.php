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
 * @var \fractalCms\models\User $model
 */

?>
<div class="row mt-3 align-items-center">
    <div class="col-sm-6">
        <p>Mettre à jour d'un utilisateur</p>
    </div>
</div>
<div class="row m-3">
    <?php
    echo $this->render('_form', ['model' => $model]);
    ?>
</div>
