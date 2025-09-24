<?php

namespace fractalCms\behaviors;

use fractalCms\controllers\CmsController;
use yii\base\Behavior;
use fractalCms\models\Seo as SeoModel;
use Exception;
use Yii;
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
                        $view->registerMetaTag([
                            'name' => 'title',
                            'content' => $seo->title
                        ]);
                    }
                }
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }
}
