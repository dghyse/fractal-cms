<?php
/**
 * _items.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package app\config
 *
 * @var \yii\web\View $this
 * @var \yii\db\ActiveQuery $itemsQuery
 * @var \fractalCms\models\Content $content
 * @var array $configItems
 */

use fractalCms\helpers\Html;
use yii\helpers\ArrayHelper;

$model = Yii::createObject(\fractalCms\models\Item::class);
?>

<div class="row m-3">
    <?php if ($itemsQuery !== null):?>
        <?php
            foreach ($itemsQuery->each() as $index => $item) {
                echo $this->render('@fractalCms/views/templates/template-item',
                [
                    'model' => $item,
                    'index' => $index,
                    'configItems' => $configItems,
                    'content' => $content,
                    'total' => $itemsQuery->count()
                ]);
            }
        ?>
    <?php endif;?>
    <div class="row justify-content-end m-3">
        <div class="col-sm-3">
            <div class="input-group">
                <?php
                echo Html::activeDropDownList($model,
                    'configItemId',
                    ArrayHelper::map($configItems, 'id' , 'name'),
                [
                    'class' => 'form-select',
                ]);
                ?>
                <button type="button" class="btn btn-primary" name="addItem">
                    <svg width="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 12H15" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 9L12 15" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#fff" stroke-width="2"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
