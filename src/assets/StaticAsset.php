<?php
/**
 * StaticAsset.php
 *
 * PHP version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @package application\assets
 *
 * @link http://www.ibitux.com
 * @package application\assets
 */

namespace fractalCms\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Base application assets
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @package application\assets
 * @since 1.0.0
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
