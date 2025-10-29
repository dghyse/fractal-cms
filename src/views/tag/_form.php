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
 * @var \fractalCms\models\Content $model
 * @var array $routes
 * @var \fractalCms\models\ConfigType[] $configTypes
 * @var array $configItems
 * @var \yii\redis\ActiveQuery $itemsQuery
 * @var \fractalCms\models\Slug $slug;
 * @var \fractalCms\models\Seo $seo;
 */

use fractalCms\helpers\Html;
use yii\helpers\ArrayHelper;
?>
<div class="row">
    <div class="col-sm-12">
        <?php echo Html::beginForm('', 'post', ['enctype' => 'multipart/form-data']); ?>
        <div class="row  justify-content-center">
            <div class="col form-check p-0">
                <?php
                echo Html::activeCheckbox($model, 'active', ['label' =>  null, 'class' => 'form-check-input']);
                echo Html::activeLabel($model, 'active', ['label' => 'Actif', 'class' => 'form-check-label']);
                ?>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-6 form-group">
                <?php
                echo Html::activeLabel($model, 'name', ['label' => 'Nom', 'class' => 'form-label']);
                echo Html::activeTextInput($model, 'name', ['placeholder' => 'Nom', 'class' => 'form-control']);
                ?>
            </div>
            <div class="col-sm-6 form-group">
                <?php
                echo Html::activeLabel($model, 'configTypeId', ['label' => 'Configuration de l\'article', 'class' => 'form-label']);
                echo Html::activeDropDownList($model, 'configTypeId', ArrayHelper::map($configTypes, 'id', 'name'), [
                    'prompt' => 'Sélectionner un type', 'class' => 'form-control',
                ]);
                ?>
            </div>
        </div>
        <?php if ($model->isNewRecord === false):?>
            <div class="row mt-3">
                <?php
                    echo $this->render('_formSlug', ['slug' => $slug]);
                ?>
            </div>
            <div class="row mt-3">
                <?php
                echo $this->render('_formSeo', ['seo' => $seo]);
                ?>
            </div>
        <div class="row mt-3">
            <div class="card">
                <div class="card-header">
                    <h2>
                    Gestion des éléments
                    </h2>
                </div>
                <?php
                    echo Html::beginTag(
                            'div',
                        [
                            'class' => 'cad-body',
                        ]);
                    echo Html::tag('fractalcms-content-manage-items', '',
                        [
                            'id.bind' => $model->id,
                            'item-api-url' => '/tags/{targetId}/manage-items',
                            'view' => $this->render('_items', [
                                'itemsQuery' => $itemsQuery,
                                'configItems' => $configItems,
                                'target' => $model
                            ])
                        ]
                    );
                ?>
                <?php
                    echo Html::endTag('div');
                ?>
            </div>
        </div>
        <?php endif;?>
        <div class="row  justify-content-center mt-3">
            <div  class="col-sm-6 text-center form-group">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </div>
        <?php  echo Html::endForm(); ?>
    </div>
</div>
