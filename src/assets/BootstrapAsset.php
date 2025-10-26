<?php
/**
 * BootstrapAsset.php
 *
 * PHP version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @package fractalCms\assets
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
class BootstrapAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/bootstrap/dist';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/bootstrap.css'
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/bootstrap.js'
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
