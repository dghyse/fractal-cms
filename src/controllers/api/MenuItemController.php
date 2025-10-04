<?php
/**
 * ContentApiController.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\config
 */

namespace fractalCms\controllers\api;

use Exception;
use fractalCms\components\Constant;
use fractalCms\models\Content;
use fractalCms\models\Menu;
use fractalCms\models\MenuItem;
use fractalCms\models\User;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class MenuItemController extends BaseController
{


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['delete'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['delete'],
                    'verbs' => ['delete'],
                    'roles' => [Constant::PERMISSION_MAIN_MENU.Constant::PERMISSION_ACTION_DELETE],
                ]
            ],
            'denyCallback' => function ($rule, $action) {
                throw new ForbiddenHttpException();
            }
        ];
        return $behaviors;
    }


    public function actionDelete($id) : Response
    {
        try {
            $response = Yii::$app->getResponse();
            $model = MenuItem::findOne(['id' => $id]);
            if ($model === null) {
                throw new NotFoundHttpException('MenuItem not found');
            }
            $model->delete();
            $model->detach();
            $response->statusCode = 204;
            return $response;
        } catch (Exception $e)  {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }
}
