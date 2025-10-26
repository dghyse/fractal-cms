<?php
/**
 * ConfigTypeController.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms\controllers
 */

namespace fractalCms\controllers;

use fractalCms\components\Constant;
use fractalCms\models\ConfigType;
use fractalCms\models\User;
use fractalCms\helpers\ConfigType as ConfigTypeHelpers;
use yii\filters\AccessControl;
use yii\web\Controller;
use Exception;
use Yii;
use yii\web\Response;

class ConfigTypeController extends Controller
{


    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['index', 'update', 'create'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index'],
                    'roles' => [Constant::PERMISSION_MAIN_CONFIG_TYPE.Constant::PERMISSION_ACTION_LIST],
                    'denyCallback' => function ($rule, $action) {
                        return $this->redirect(['default/index']);
                    }
                ],
                [
                    'allow' => true,
                    'actions' => ['create'],
                    'roles' => [Constant::PERMISSION_MAIN_CONFIG_TYPE.Constant::PERMISSION_ACTION_CREATE],
                    'denyCallback' => function ($rule, $action) {
                        return $this->redirect(['default/index']);
                    }
                ],
                [
                    'allow' => true,
                    'actions' => ['update'],
                    'roles' => [Constant::PERMISSION_MAIN_CONFIG_TYPE.Constant::PERMISSION_ACTION_UPDATE],
                    'denyCallback' => function ($rule, $action) {
                        return $this->redirect(['default/index']);
                    }
                ]
            ]
        ];
        return $behaviors;
    }


    public function actionIndex() : string
    {
        try {
            $models = ConfigType::find()->all();
            return $this->render('index', [
                'models' => $models
            ]);
        } catch (Exception $e)  {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }


    public function actionCreate(ConfigTypeHelpers $cfHelper) : string | Response
    {
        try {
            $model = Yii::createObject(ConfigType::class);
            $model->scenario = ConfigType::SCENARIO_CREATE;
            $request = Yii::$app->request;
            $response = null;
            $routes = $cfHelper->getCmsRoutes(CmsController::class);
            if ($request->isPost === true) {
                $body = $request->getBodyParams();
                $model->load($body);
                if ($model->validate() === true) {
                    if ($model->save() === true) {
                        $response = $this->redirect(['config-type/index']);
                    } else {
                        $model->addError('name', 'Une erreur c\est produite');
                    }
                }
            }
            if ($response === null) {
                $response = $this->render('create', [
                    'model' => $model,
                    'routes' => $routes
                ]);
            }
            return $response;
        } catch (Exception $e)  {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }

    public function actionUpdate($id, ConfigTypeHelpers $cfHelper) : string | Response
    {
        try {
            $model = ConfigType::findOne(['id' => $id]);
            $model->scenario = ConfigType::SCENARIO_UPDATE;
            $request = Yii::$app->request;
            $routes = $cfHelper->getCmsRoutes(CmsController::class);
            $response = null;
            if ($request->isPost === true) {
                $body = $request->getBodyParams();
                $model->load($body);
                if ($model->validate() === true) {
                    if ($model->save() === true) {
                        $response = $this->redirect(['config-type/index']);
                    } else {
                        $model->addError('name', 'Une erreur c\est produite');
                    }
                }
            }
            if ($response === null) {
                $response = $this->render('update', [
                    'model' => $model,
                    'routes' => $routes
                ]);
            }
            return $response;
        } catch (Exception $e)  {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }
}
