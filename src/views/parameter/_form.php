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
 * @var \fractalCms\models\Menu $model
 * @var \yii\redis\ActiveQuery $itemsQuery
 */

use fractalCms\helpers\Html;
use yii\helpers\ArrayHelper;
?>
<div class="row">
    <div class="col-sm-12">
        <?php echo Html::beginForm('', 'post', []); ?>
        <div class="row">
            <div class="col-sm-6">
                <div class="row  justify-content-center">
                    <div class="col form-group p-0">
                        <?php
                        echo Html::activeLabel($model, 'group', ['label' => 'Groupe', 'class' => 'form-label']);
                        echo Html::activeTextInput($model, 'group', ['placeholder' => 'Groupe', 'class' => 'form-control']);
                        ?>
                    </div>
                </div>
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
                        echo Html::activeLabel($model, 'value', ['label' => 'Valeur', 'class' => 'form-label']);
                        echo Html::activeTextInput($model, 'value', ['placeholder' => 'Valeur', 'class' => 'form-control']);
                        ?>
                    </div>
                </div>
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
