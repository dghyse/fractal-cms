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
 * @var \fractalCms\models\Seo $seo;
 */

use fractalCms\helpers\Html;
use yii\helpers\ArrayHelper;
?>
<div class="card">
    <div class="card-header">
        SEO
    </div>
    <div class="card-body">
        <div class="row  justify-content-center">
            <div class="col form-check ">
                <?php
                echo Html::activeCheckbox($seo, 'active', ['label' =>  null, 'class' => 'form-check-input']);
                echo Html::activeLabel($seo, 'active', ['label' => 'Actif', 'class' => 'form-check-label']);
                ?>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <div class="col form-group p-0">
                    <?php
                    echo Html::activeLabel($seo, 'title', ['label' => 'Titre', 'class' => 'form-label']);
                    echo Html::activeTextInput($seo, 'title', ['placeholder' => 'Titre Seo', 'class' => 'form-control']);
                    ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="col form-group p-0">
                    <?php
                    echo Html::activeLabel($seo, 'description', ['label' => 'Description', 'class' => 'form-label']);
                    echo Html::activeTextarea($seo, 'description', ['placeholder' => 'Description Seo', 'class' => 'form-control']);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        Sitemap
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col form-label">
                <?php
                    echo 'Fréquence de modification';
                ?>
            </div>
            <div class="col form-check ">
                <?php
                $index = 0;
                foreach (\fractalCms\models\Seo::optsFrequence() as $label => $value) {
                    $inputRadioId = Html::getInputId($seo, 'changefreq').'_'.($index + 1);
                    echo Html::beginTag('div', ['class' => 'form-check form-check-inline']);
                    echo Html::input('radio', Html::getInputName($seo, 'changefreq'), $value, [
                        'id' => $inputRadioId,
                        'class' => 'form-check-input',
                        'value' => $value,
                        'checked' => $seo->changefreq == $value,
                        'label' => null
                    ]);
                    echo Html::label($value, $inputRadioId, ['class' => 'form-check-label']);
                    echo Html::endTag('div');
                    $index +=1;
                }
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col form-group p-0">
                <?php
                echo Html::activeLabel($seo, 'priority', ['label' => 'Priorite (valeurs valides : 0 à 1)', 'class' => 'form-label']);
                echo Html::activeInput('number', $seo, 'priority', ['class' => 'form-control', 'step' => '0.1', 'min' => '0.0', 'max' => '1.0']);
                ?>
            </div>
        </div>
    </div>
</div>
