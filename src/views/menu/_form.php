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

use fractalCms\helpers\Html;
use yii\helpers\ArrayHelper;
?>
<div class="row">
    <div class="col-sm-12">
        <?php echo Html::beginForm('', 'post', []); ?>
        <div class="row  justify-content-center">
            <div class="col form-check p-0">
                <?php
                echo Html::activeCheckbox($model, 'active', ['label' =>  null, 'class' => 'form-check-input']);
                echo Html::activeLabel($model, 'active', ['label' => 'Actif', 'class' => 'form-check-label']);
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="row  justify-content-center">
                    <div class="col form-group p-0">
                        <?php
                        echo Html::activeLabel($model, 'name', ['label' => 'Nom', 'class' => 'form-label']);
                        echo Html::activeTextInput($model, 'name', ['placeholder' => 'Nom', 'class' => 'form-control']);
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($model->isNewRecord === false):?>
        <div class="row mt-3">
            <div class="card">
                <div class="card-header">
                    Gestion des éléments
                </div>
                <?php
                    echo Html::beginTag(
                            'div',
                        [
                            'class' => 'cad-body',
                        ]);
                    echo $this->render('_items',
                        [
                            'itemsQuery' => $itemsQuery,
                            'menu' => $model
                        ]);
                ?>
                <?php
                    echo Html::endTag('div');
                ?>
            </div>
        </div>
        <?php endif;?>
        <div class="row  justify-content-center mt-3">
            <div  class="col-sm-6 text-center form-group">
                <button type="submit" class="btn btn-primary">Enregister</button>
            </div>
        </div>
        <?php  echo Html::endForm(); ?>
    </div>
</div>
