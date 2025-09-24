<?php
/**
 * CmsController.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\config
 */

namespace fractalCms\controllers;

use fractalCms\interfaces\ControllerInterface;
use fractalCms\models\Content;
use fractalCms\models\Slug;
use yii\filters\AccessControl;
use yii\web\Controller;
use Exception;
use Yii;
use yii\web\Request;

class CmsController extends Controller implements ControllerInterface
{

    public function getContent() : Content | null
    {
        try {
            /** @var Request $request */
            $content = null;
            $request = Yii::$app->request;
            $pathInfo = $request->getPathInfo();
            //Get slug with path info
            $slug = Slug::find()->andWhere(['path' => $pathInfo, 'active' => 1])->one();
            if ($slug instanceof Slug) {
                //Get content with slug
                $content = Content::find()->andWhere(['slugId' => $slug->id])->one();
            }
            return $content;
        }catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
}
