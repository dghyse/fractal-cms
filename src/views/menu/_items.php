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
 * @var string $menuItemHtml
 * @var \fractalCms\models\Menu $menu
 */

use fractalCms\helpers\Html;
use yii\helpers\ArrayHelper;
?>

<div class="row m-1" cms-menu-item-list="">
            <?php
                echo $this->render('_menu_item_lines', ['menuItemHtml' => $menuItemHtml]);
            ?>
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
