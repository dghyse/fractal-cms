<?php
/**
 * main.php
 *
 * PHP Version 8.2+
 *
 * @version XXX
 * @package webapp\views\layouts
 *
 * @var $this yii\web\View
 * @var $content string
 */

use fractalCms\assets\BootstrapAsset;
use fractalCms\assets\StaticAsset;
use fractalCms\assets\WebpackAsset;
use fractalCms\Module;
use yii\helpers\Html;

$moduleInstance = Module::getInstance();
WebpackAsset::register($this);
BootstrapAsset::register($this);
$baseUrl = StaticAsset::register($this)->baseUrl;
Yii::$app->response->headers->set('X-Content-Type-Options', 'nosniff');
$this->beginPage();
$url = Yii::$app->request->url;
$class = 'container-lg';
$this->title = $moduleInstance->name;
?>
<!DOCTYPE html>
    <?php echo Html::beginTag('html', ['lang' => Yii::$app->language]); ?>
        <head>
            <meta charset="utf-8">
            <meta http-equiv="x-ua-compatible" content="ie=edge">
            <title>
                <?php echo $this->title; ?>
            </title>
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <?php echo Html::tag('link', '', ['rel' => 'icon', 'href' => $baseUrl.'/img/favicon.ico', 'sizes' => 'any']);?>
            <?php echo Html::tag('meta', '', ['name' => 'X-Version', 'content' => Yii::$app->version]); ?>
            <?php $this->head(); ?>
        </head>
        <?php echo Html::beginTag('body'); ?>
            <?php $this->beginBody(); ?>
                <?php echo Html::beginTag('div', ['id' => 'main', 'class' => $class]); ?>
<?php
if (Yii::$app->user->isGuest === false) {
    echo \fractalCms\widgets\Header::widget();
}
?>
                    <?php echo $content; ?>
                <?php echo Html::endTag('div'); ?>
            <?php $this->endBody(); ?>
        <?php echo Html::endTag('body'); ?>
    <?php echo Html::endTag('html'); ?>
<?php $this->endPage();
