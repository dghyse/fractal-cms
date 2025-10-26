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
 * @var \yii\web\View $this
 * @var \fractalCms\models\Menu $model
 * @var string $menuItemHtml
 */

?>

<div class="row mt-3 align-items-center">
    <div class="col-sm-8">
        <h2>Création d'un menu</h2>
    </div>
</div>
<div class="row m-3">
    <?php
        echo $this->render('_form', [
            'model' => $model,
            'menuItemHtml' => $menuItemHtml
        ]);
    ?>
</div>
