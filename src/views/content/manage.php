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
 * @var \fractalCms\models\Content $model
 * @var \fractalCms\models\ConfigType $configTypes
 * @var array $sections
 * @var array $configItems
 * @var \yii\redis\ActiveQuery $itemsQuery
 * @var \fractalCms\models\Slug $slug
 * @var \fractalCms\models\Seo $seo
 */
use fractalCms\helpers\Html;
use yii\helpers\Url;

$configItems = ($configItems) ?? [];
?>
<div class="row mt-3 align-items-center">
    <div class="col-sm-8">
        <h2>Création d'un article</h2>
    </div>
    <div class="col-sm-4">
        <div class="row align-items-center">
            <div class="col-sm-4">
                <div class="col form-group p-0">
                    <?php
                    echo Html::a('Prévisualisation', Url::toRoute([$model->getRoute()]), [
                           'class' => 'btn btn-primary',
                        'target' => '_blank'
                    ])
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row m-3">
    <?php
        echo $this->render('_form', [
                'model' => $model,
                'slug' => $slug,
                'seo' => $seo,
            'configTypes' => $configTypes,
            'sections' => $sections,
            'configItems' => $configItems,
            'itemsQuery' => $itemsQuery
        ]);
    ?>
</div>
