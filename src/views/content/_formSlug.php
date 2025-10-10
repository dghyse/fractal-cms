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
 * @var \fractalCms\models\Slug $slug;
 */

use fractalCms\helpers\Html;
use yii\helpers\ArrayHelper;
?>
<div class="card">
    <div class="card-header">
        Url
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="col form-group p-0">
                    <?php
                    echo Html::activeLabel($slug, 'path', ['label' => 'Url de cet article', 'class' => 'form-label']);
                    echo Html::activeTextInput($slug, 'path', ['placeholder' => 'Path', 'class' => 'form-control']);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
