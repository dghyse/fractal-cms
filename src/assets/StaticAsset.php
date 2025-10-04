<?php
/**
 * StaticAsset.php
 *
 * PHP version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 *
 * @package app\assets
 */

namespace fractalCms\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Base application assets
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @package app\assets
 */
class StaticAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@fractalCms/assets/static';

    /**
     * @inheritdoc
     */
    public $css = [
    ];

    /**
     * @inheritdoc
     */
    public $js = [
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
    ];

    /**
     * @inheritdoc
     */
    public $jsOptions = [
        'position' => View::POS_HEAD
    ];
}
