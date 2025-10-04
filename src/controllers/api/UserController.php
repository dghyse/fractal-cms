<?php
/**
 * main.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package app\controllers\api
 */

namespace fractalCms\controllers\api;

use Exception;
use fractalCms\components\Constant;
use fractalCms\models\User;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class UserController extends BaseController
{


    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['delete', 'activate'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['delete'],
                    'verbs' => ['delete'],
                    'roles' => [Constant::PERMISSION_MAIN_USER.Constant::PERMISSION_ACTION_DELETE],
                ],
                [
                    'allow' => true,
                    'actions' => ['activate'],
                    'verbs' => ['get'],
                    'roles' => [Constant::PERMISSION_MAIN_USER.Constant::PERMISSION_ACTION_ACTIVATION],
                ],
            ],
            'denyCallback' => function ($rule, $action) {
                throw new ForbiddenHttpException();
            }
        ];
        return $behaviors;
    }


    /**
     * Delete user
     *
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id) : Response
    {
        try {
            $response = Yii::$app->getResponse();
            $model = User::findOne(['id' => $id]);
            if ($model === null) {
                throw new NotFoundHttpException('user not found');
            }
            $model->delete();
            $response->statusCode = 204;
            return $response;
        } catch (Exception $e)  {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }

    /**
     * Active user
     *
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionActivate($id) : Response
    {
        try {
            $response = Yii::$app->getResponse();
            /** @var User $model */
            $model = User::findOne(['id' => $id]);
            if ($model === null) {
                throw new NotFoundHttpException('user not found');
            }
            $model->active = true;
            $model->dateUpdate = new Expression('NEW()');
            $model->save();
            $response->statusCode = 204;
            return $response;
        } catch (Exception $e)  {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }
}
