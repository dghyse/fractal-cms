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
use fractalCms\models\Seo;
?>
<div class="card">
    <div class="card-header uppercase">
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
                    echo Html::activeTextarea($seo, 'description', [
                        'placeholder' => 'Description Seo',
                        'rows' => 6,
                        'cols' => 12,
                        'class' => 'form-control form-textarea']);
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?php
                echo Html::tag('cms-file-upload', '', [
                    'title' => 'Image dimensions idéale 1200×630px',
                    'name' => Html::getInputName($seo, 'imgPath'),
                    'value' => $seo->imgPath,
                    'upload-file-text' => 'Ajouter une fichier',
                    'file-type' => Seo::$accept
                ]);
                ?>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header uppercase">
                Sitemap
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4 form-label p-0">
                        <?php
                        echo 'Fréquence de modification';
                        ?>
                    </div>
                    <div class="col-sm-8 form-check ">
                        <?php
                        $index = 0;
                        foreach (Seo::optsFrequence() as $label => $value) {
                            $inputRadioId = Html::getInputId($seo, 'changefreq').'_'.($index + 1);
                            $suffixFreq = Seo::frequenceSuffix();
                            $suffix = ($suffixFreq[$value]) ?? '';
                            echo Html::beginTag('div', ['class' => 'form-check form-check-inline']);
                            echo Html::input('radio', Html::getInputName($seo, 'changefreq'), $value, [
                                'id' => $inputRadioId,
                                'class' => 'form-check-input',
                                'value' => $value,
                                'checked' => $seo->changefreq == $value,
                                'label' => null
                            ]);
                            echo Html::label($value.' ('.$suffix.')', $inputRadioId, ['class' => 'form-check-label']);
                            echo Html::endTag('div');
                            $index +=1;
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col form-group p-0">
                        <?php
                        echo Html::activeLabel($seo, 'priority', ['label' => 'Priorité (valeurs valides : 0 à 1)', 'class' => 'form-label']);
                        echo Html::activeInput('number', $seo, 'priority', ['class' => 'form-control', 'step' => '0.1', 'min' => '0.0', 'max' => '1.0']);
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header uppercase">
                Meta données
            </div>
            <div class="card-body">
                <div class="row ">
                    <div class="col form-check ">
                        <div class="form-check form-check-inline">
                            <?php
                            echo Html::activeCheckbox($seo, 'noFollow', ['label' =>  null, 'class' => 'form-check-input']);
                            echo Html::activeLabel($seo, 'noFollow', ['label' => 'Ajoute la meta "no-follow no-index"', 'class' => 'form-check-label']);
                            ?>
                        </div>
                        <div class="form-check form-check-inline">
                            <?php
                            echo Html::activeCheckbox($seo, 'ogMeta', ['label' =>  null, 'class' => 'form-check-input']);
                            echo Html::activeLabel($seo, 'ogMeta', ['label' => 'Générer les Metas OG:*', 'class' => 'form-check-label']);
                            ?>
                        </div>
                        <div class="form-check form-check-inline">
                            <?php
                            echo Html::activeCheckbox($seo, 'addJsonLd', ['label' =>  null, 'class' => 'form-check-input']);
                            echo Html::activeLabel($seo, 'addJsonLd', ['label' => 'Générer le Meta JSONLD', 'class' => 'form-check-label']);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
