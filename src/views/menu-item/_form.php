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
 * @var \fractalCms\models\MenuItem $model
 * @var \fractalCms\models\MenuItem[] $menusItems
 * @var \fractalCms\models\Content[] $contents
 * @var  array $routes
 */

use fractalCms\helpers\Html;
use yii\helpers\ArrayHelper;
?>
<div class="row">
    <div class="col-sm-12">
        <?php echo Html::beginForm('', 'post'); ?>
        <div class="row">
            <div class="col form-group p-0">
                <?php
                echo Html::activeLabel($model, 'name', ['label' => 'Nom', 'class' => 'form-label']);
                echo Html::activeTextInput($model, 'name', ['placeholder' => 'Nom', 'class' => 'form-control']);
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col form-group p-0">
                <?php
                echo Html::activeLabel($model, 'contentId', ['label' => 'Route CMS', 'class' => 'form-label']);
                echo Html::activeDropDownList($model, 'contentId', ArrayHelper::map($contents, 'id', 'name'), [
                    'prompt' => 'Sélectionner une route interne au CMS', 'class' => 'form-control',
                ]);
                ?>
            </div>
        </div>
        <?php if (empty($routes) === false):?>
        <div class="row">
            <div class="col form-group p-0">
                <?php
                echo Html::activeLabel($model, 'route', ['label' => 'Route Locale', 'class' => 'form-label']);
                echo Html::activeDropDownList($model, 'route', ArrayHelper::map($routes, 'id', 'name'), [
                    'prompt' => 'Sélectionner une route interne à l\'application', 'class' => 'form-control',
                ]);
                ?>
            </div>
        </div>
        <?php endif;?>
        <div class="row">
            <div class="col form-group p-0">
                <?php
                echo Html::activeLabel($model, 'menuItemId', ['label' => 'Parent', 'class' => 'form-label']);
                echo Html::activeDropDownList($model, 'menuItemId', ArrayHelper::map($menusItems, 'id', 'name'), [
                    'prompt' => 'Sélectionner un Parent', 'class' => 'form-control',
                ]);
                ?>
            </div>
        </div>
        <div class="row  justify-content-center mt-3">
            <div  class="col-sm-6 text-center form-group">
                <button type="submit" class="btn btn-primary">Enregister</button>
            </div>
        </div>
        <?php  echo Html::endForm(); ?>
    </div>
</div>
