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
                        //<link rel="canonical" href="https://portfolio.webcraftdg.fr/accueil">
                        $view->registerLinkTag([
                            'rel' => 'canonical',
                            'href' => Url::toRoute($content->getRoute(), true)
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
                            $view->registerMetaTag(['property' => 'og:description', 'content' => strip_tags($seo->description)]);
                            if (empty($seo->imgPath) === false) {
                                $imageCacheUrl = Html::getImgCache($seo->imgPath, ['width' => 1200, 'height' => 630]);
                                $imageCacheUrl = Url::to('/', true).trim($imageCacheUrl, '/');
                                $view->registerMetaTag(['property' => 'og:image', 'content' => $imageCacheUrl]);
                            }
                            $view->registerMetaTag(['property' => 'og:url', 'content' => Url::toRoute($content->getRoute(), true)]);
                            $view->registerMetaTag(['property' => 'og:type', 'content' => 'website']);
                        }
                        if((boolean)$seo->twitterMeta === true) {
                            /**
                             * <meta name="twitter:card" content="summary_large_image">
                             * <meta name="twitter:title" content="Portfolio de David Ghyse – Développeur Web Full-Stack">
                             * <meta name="twitter:description" content="Développeur web polyvalent, intéressé par des projets où la technique et l'humain se rencontrent. Découvrez mes réalisations.">
                             * <meta name="twitter:image" content="https://portfolio.webcraftdg.fr/images/hero.jpg">
                             */
                            $view->registerMetaTag(['name' => 'twitter:title', 'content' => $seo->title]);
                            $view->registerMetaTag(['name' => 'twitter:description', 'content' => strip_tags($seo->description)]);
                            if (empty($seo->imgPath) === false) {
                                $imageCacheUrl = Html::getImgCache($seo->imgPath, ['width' => 1200, 'height' => 630]);
                                $imageCacheUrl = Url::to('/', true).trim($imageCacheUrl, '/');
                                $view->registerMetaTag(['name' => 'twitter:card', 'content' => 'summary_large_image']);
                                $view->registerMetaTag(['name' => 'twitter:image', 'content' => $imageCacheUrl]);
                            }
                        }
                        if((boolean)$seo->noFollow === true) {
                            /**
                             * <meta name="robots" content="noindex, nofollow">
                             */
                            $view->registerMetaTag(['name' => 'robots', 'content' => 'noindex, nofollow']);
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
