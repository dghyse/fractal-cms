<?php
/**
 * main.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\config
 *
 * @var \yii\web\View $this
 * @var \fractalCms\models\User $model
 */

use yii\helpers\Html;

?>

<div class="row">
    <div class="col-sm-12">
        <h1 class="text-center">Veuillez vous identifier</h1>
    </div>
    <?php if (empty($model->errors) === false): ?>
    <div class="col-sm-12">
        <div class="row justify-content-center">
            <?php echo Html::tag('p', 'Veuillez vérifier vos informations', ['class' => 'col-sm-6  text-bg-danger']);?>
        </div>
    </div>
    <?php endif; ?>
    <div class="col-sm-12">
        <?php echo Html::beginForm(); ?>
        <div class="row  justify-content-center">
            <div class="col-sm-6 form-group">
                <?php
                echo Html::activeLabel($model, 'email', ['label' => 'Identifiant (Email)', 'class' => 'form-label']);
                echo Html::activeTextInput($model, 'email', ['placeholder' => 'votre login / email', 'class' => 'form-control']);
                ?>
            </div>
        </div>
        <div class="row  justify-content-center">
            <div class="col-sm-6  form-group">
                <?php
                echo Html::activeLabel($model, 'email', ['label' => 'Mot de passe', 'class' => 'form-label']);
                echo Html::activePasswordInput($model, 'tmpPassword', ['placeholder' => 'Votre mot de passe', 'class' => 'form-control']);
                ?>
            </div>
        </div>
        <div class="row  justify-content-center mt-3">
            <div  class="col-sm-6 text-center form-group">
                <button type="submit" class="btn btn-primary">S'identifier</button>
            </div>
        </div>
        <?php  echo Html::endForm(); ?>
    </div>
</div>
