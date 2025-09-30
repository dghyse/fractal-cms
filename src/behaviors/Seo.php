<?php

namespace fractalCms\behaviors;

use fractalCms\controllers\CmsController;
use fractalCms\helpers\Html;
use yii\base\Behavior;
use fractalCms\models\Seo as SeoModel;
use Exception;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;

class Seo extends Behavior
{
    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction',
        ];
    }

    public function beforeAction($event)
    {
        try {
            $controller = $this->owner;
            if ($controller instanceof CmsController) {
                $content = $controller->getContent();
                if ($content !== null) {
                    $seo = $content->getSeo()->one();
                    $view = $controller->getView();
                    if ($seo instanceof SeoModel && (boolean)$seo->active === true) {
                        $view->registerMetaTag([
                            'name' => 'description',
                            'content' => $seo->description
                        ]);
                        if ((boolean)$seo->ogMeta === true) {
                            /**
                             * <meta property="og:title" content="Portfolio de David Ghyse – Développeur Web">
                             * <meta property="og:description" content="Développeur Full-Stack spécialisé Yii2, Aurelia et Tailwind. Découvrez mes projets et réalisations.">
                             * <meta property="og:image" content="https://portfolio.webcraftdg.fr/images/hero.jpg">
                             * <meta property="og:image:width" content="1200">
                             * <meta property="og:image:height" content="630">
                             * <meta property="og:url" content="https://portfolio.webcraftdg.fr/accueil">
                             * <meta property="og:type" content="website">
                             */
                            $view->registerMetaTag(['property' => 'og:title', 'content' => $seo->title]);
                            $view->registerMetaTag(['property' => 'og:description', 'content' => $seo->description]);
                            if (empty($seo->imgPath) === false) {
                                $imageCacheUrl = Html::getImgCache($seo->imgPath, ['width' => 1200, 'height' => 630]);
                                $imageCacheUrl = Url::to('/', true).trim($imageCacheUrl, '/');
                                $view->registerMetaTag(['property' => 'og:image', 'content' => $imageCacheUrl]);
                            }
                            $view->registerMetaTag(['property' => 'og:url', 'content' => Url::toRoute($content->getRoute(), true)]);
                            $view->registerMetaTag(['property' => 'og:type', 'content' => 'website']);

                        }

                    }
                }
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }
}
