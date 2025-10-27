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
 * @var $this \yii\web\View
 * @var \yii\db\ActiveQuery $modelQuery
 */
use fractalCms\components\Constant;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="row mt-3 align-items-center">
    <div class="col-sm-6">
        <h2>Liste des tags</h2>
    </div>
</div>
<div class="row mt-3">
    <div class="col" >
        <?php
        if (Yii::$app->user->can(Constant::PERMISSION_MAIN_TAG.Constant::PERMISSION_ACTION_CREATE) === true):

        echo Html::beginTag('a', ['href' => Url::to(['tag/create']), 'class' => 'btn btn-outline-success']);
        ?>
            <svg width="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 12H15" stroke="#198754" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12 9L12 15" stroke="#198754" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#198754" stroke-width="2"/>
            </svg>
        <span>Ajouter</span>
        <?php
        echo Html::endTag('a');
        endif;

        ?>
    </div>

</div>
<div class="row m-3">
    <?php
    foreach ($modelQuery->each() as $model) {
        echo $this->render('_line', ['model' => $model]);
    }
    ?>
</div>
