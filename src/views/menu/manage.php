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
 * @var \yii\web\View $this
 * @var \fractalCms\models\Menu $model
 * @var \yii\redis\ActiveQuery $itemsQuery
 */

?>

<div class="row mt-3 align-items-center">
    <div class="col-sm-8">
        <h2>Création d'un article</h2>
    </div>
</div>
<div class="row m-3">
    <?php
        echo $this->render('_form', [
                'model' => $model,
            'itemsQuery' => $itemsQuery
        ]);
    ?>
</div>
