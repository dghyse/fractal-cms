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
 * @var \fractalCms\models\MenuItem $model
 * @var \fractalCms\models\MenuItem[] $menusItems
 * @var array $contents
 * @var array $routes
 */
use fractalCms\helpers\Html;
use yii\helpers\Url;
?>

<div class="row mt-3 align-items-center">
    <div class="col-sm-8">
        <h2>Création d'un élément du menu</h2>
    </div>
</div>
<div class="row m-3">
    <?php
        echo $this->render('_form', [
            'model' => $model,
            'menusItems' => $menusItems,
            'contents' => $contents,
            'routes' => $routes,
        ]);
    ?>
</div>
