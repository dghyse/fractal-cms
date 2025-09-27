<?php
/**
 * bootstrap.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\config
 */

namespace fractalCms\assets;

use fractalCms\Module;
use yii\caching\Cache;
use yii\caching\FileDependency;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\AssetBundle;
use yii\web\View;
use Yii;
use Exception;

/**
 * Base webpack assets
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\config
 * @since XXX
 */
class WebpackAsset extends AssetBundle
{

    /**
     * @var string name of webpack asset catalog, should be in synch with webpack.config.js
     */
    public $webpackAssetCatalog = 'assets-catalog.json';

    /**
     * string base cache key
     */
    const CACHE_KEY = 'webpack:bundles:cms:';

    /**
     * @var \yii\caching\Cache cache
     */
    public $cache = 'cache';

    /**
     * @inheritdoc
     */
    public $cacheEnabled = false;

    /**
     * @inheritdoc
     */
    public $webpackPath = '@fractalCms/assets/webpack';

    /**
     * @inheritdoc
     */
    public $webpackDistDirectory = 'build';

    /**
     * @inheritdoc
     */
    public $webpackBundles = [
        'manifest',
        'vendors',
        'main',
        'app'
    ];

    /**
     * @var array list of bundles which are css only
     */
    public $cssOnly = [
        'main',
    ];

    /**
     * @var array list of bundles which are js only
     */
    public $jsOnly = [
        'manifest',
        'vendors',
        'app',
    ];

    public $js = [
    ];
    /**
     * @inheritdoc
     */
    public $css = [
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
        'position' => View::POS_HEAD,
        'defer' => 'defer',
    ];

    /**
     * @inheritdoc
     */
    public static function register($view)
    {
        /* @var $view View */
        $bundle = parent::register($view);
        $view->registerJsVar('webpackBaseUrl', $bundle->baseUrl.'/');
        $view->registerJsVar('apiBaseUrl', Url::to(['/']).Module::getInstance()->id);

        return $bundle;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->mergeWebpackBundles();
        parent::init();
    }

    /**
     * Merge webpack bundles with classic bundles and cache it if needed
     * @return void
     * @throws Exception
     * @since XXX
     */
    protected function mergeWebpackBundles()
    {
        try {
            if ((isset($this->webpackPath) === true) && (is_array($this->webpackBundles) === true)) {
                $cacheKey = self::CACHE_KEY . get_called_class();
                $this->sourcePath = $this->webpackPath . '/' . $this->webpackDistDirectory;
                $cache = $this->getCache();
                if (($cache === null) || ($cache->get($cacheKey) === false)) {
                    $assetsFileAlias = $this->webpackPath . '/' . $this->webpackAssetCatalog;
                    $bundles = file_get_contents(Yii::getAlias($assetsFileAlias));
                    $bundles = Json::decode($bundles);
                    if ($cache !== null) {
                        $cacheDependency = Yii::createObject([
                            'class' => FileDependency::class,
                            'fileName' => $assetsFileAlias,
                        ]);
                        $cache->set($cacheKey, $bundles, 0, $cacheDependency);
                    }
                } else {
                    $bundles = $cache->get($cacheKey);
                }
                foreach($this->webpackBundles as $bundle) {
                    if (isset($bundles[$bundle]['js']) === true && in_array($bundle, $this->cssOnly) === false) {
                        $this->js[] = $bundles[$bundle]['js'];
                    }
                    if (isset($bundles[$bundle]['css']) === true && in_array($bundle, $this->jsOnly) === false) {
                        $this->css[] = $bundles[$bundle]['css'];
                    }
                }
            }
        } catch(Exception $e) {
            Yii::error($e->getMessage());
            throw $e;
        }
    }

    /**
     * @return null|Cache
     * @throws \yii\base\InvalidConfigException
     * @since XXX
     */
    private function getCache()
    {
        return $this->cacheEnabled ? $this->get('cache') : null;
    }
}
