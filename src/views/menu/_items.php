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
 * @var \fractalCms\models\Menu $menu
 */

use fractalCms\helpers\Html;
use yii\helpers\ArrayHelper;
?>

<div class="row m-1">
    <?php if ($itemsQuery !== null):?>
        <ul class="list-none" cms-menu-item-list="">
            <?php
            $parentItem = null;
            $lastItem = null;
            $lastLine = null;
            $open = false;
            /**
             * @var  int $index
             * @var \fractalCms\models\MenuItem $item
             */
            foreach ($itemsQuery->each() as $index => $item) {
                $className = [];
                if($lastItem !== null) {
                    $deep = $lastItem->getDeep();
                    if ($deep !== null && $deep !== 1) {
                        $classMargin = 'ps-'.$deep;
                    } else {
                        $classMargin = 'p-0';
                    }
                    if ($open === true) {
                        $className[] = $classMargin;
                    }
                }

                $line = $this->render('_line', [
                    'model' => $item,
                    'menu' => $menu
                ]);
                if ($parentItem !== null && $item->menuItemId === $parentItem->id && $open === false) {
                    echo Html::beginTag('ul', ['class' => 'list-none p-0']);
                    echo Html::tag('li', $lastLine, ['class'=> 'p-0']);
                    $open = true;
                } elseif ($parentItem !== null && $item->menuItemId !== $parentItem->id && $open === true) {
                    echo Html::tag('li', $lastLine, ['class' => implode(' ', $className),]);
                    echo Html::endTag('ul');
                    $open = false;
                } elseif ($lastLine !== null) {
                    echo Html::tag('li', $lastLine, ['class' => implode(' ', $className),]);
                }

                if ($open === false) {
                    $parentItem = $item;
                }
                $lastLine = $line;
                $lastItem = $item;
            }
            if ($lastLine !== null) {
                echo Html::tag('li', $lastLine);
            }
            ?>
        </ul>

    <?php endif;?>
</div>
<div class="row">
    <div class="col-sm-3  justify-content-end">
        <div class="input-group">
            <?php
            echo Html::beginTag('a', ['href' => \yii\helpers\Url::to(['menu-item/create', 'menuId' => $menu->id]), 'class' => 'btn btn-success'])
            ?>
            <svg width="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 12H15" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12 9L12 15" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#fff" stroke-width="2"/>
            </svg>
            <span>Ajouter un élément</span>
            <?php
            echo Html::endTag('a');
            ?>
        </div>
    </div>
</div>
