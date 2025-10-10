<?php
/**
 * index.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package app\config
 *
 * @var \yii\web\View $this
 * @var \fractalCms\models\ConfigType $model
 * @var array $routes
 */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
?>
<div class="row">
    <div class="col-sm-12">
        <?php if (empty($model->errors) === false): ?>
                <?php foreach ($model->errors as $error):?>
                <div class="row justify-items-center justify-content-center m-0 v">
                        <?php echo Html::tag('p', $error['0'], ['class' => ' col text-bg-warning text-white m-0']);?>
                </div>
                <?php endforeach;?>
        <?php endif; ?>


        <?php echo Html::beginForm(); ?>

        <div class="row  justify-content-center">
            <div class="col form-group p-0">
                <?php
                echo Html::activeLabel($model, 'name', ['label' => 'Nom', 'class' => 'form-label']);
                echo Html::activeTextInput($model, 'name', ['placeholder' => 'Nom', 'class' => 'form-control']);
                ?>
            </div>
        </div>
        <div class="row  justify-content-center">
            <div class="col form-group p-0">
                <?php
                echo Html::activeLabel($model, 'config', ['label' => 'Route', 'class' => 'form-label']);
                echo Html::activeDropDownList($model, 'config', ArrayHelper::map($routes, 'id', 'name', 'group'), [
                        'prompt' => 'Sélectionner un route', 'class' => 'form-control'
                ]);
                ?>
            </div>
        </div>
        <div class="row  justify-content-center mt-3">
            <div  class="col-sm-6 text-center form-group">
                <button type="submit" class="btn btn-primary">Valider</button>
            </div>
        </div>
        <?php  echo Html::endForm(); ?>
    </div>
</div>
