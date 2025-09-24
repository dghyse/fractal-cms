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
