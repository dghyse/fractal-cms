<?php
/**
 * ConfigItemController.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms\controllers
 */

namespace fractalCms\controllers;

use fractalCms\components\Constant;
use fractalCms\models\ConfigItem;
use yii\filters\AccessControl;
use yii\web\Controller;
use Exception;
use Yii;
use yii\web\Response;

class ConfigItemController extends Controller
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
                    'roles' => [Constant::PERMISSION_MAIN_CONFIG_ITEM.Constant::PERMISSION_ACTION_LIST],
                    'denyCallback' => function ($rule, $action) {
                        return $this->redirect(['default/index']);
                    }
                ],
                [
                    'allow' => true,
                    'actions' => ['create'],
                    'roles' => [Constant::PERMISSION_MAIN_CONFIG_ITEM.Constant::PERMISSION_ACTION_CREATE],
                    'denyCallback' => function ($rule, $action) {
                        return $this->redirect(['default/index']);
                    }
                ],
                [
                    'allow' => true,
                    'actions' => ['update'],
                    'roles' => [Constant::PERMISSION_MAIN_CONFIG_ITEM.Constant::PERMISSION_ACTION_UPDATE],
                    'denyCallback' => function ($rule, $action) {
                        return $this->redirect(['default/index']);
                    }
                ]
            ]
        ];
        return $behaviors;
    }


    /**
     * Liste
     *
     * @return string
     * @throws Exception
     */
    public function actionIndex() : string
    {
        try {
            $models = ConfigItem::find()->all();
            return $this->render('index', [
                'models' => $models
            ]);
        } catch (Exception $e)  {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }


    /**
     * Create
     *
     * @return string|Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionCreate() : string | Response
    {
        try {
            $model = Yii::createObject(ConfigItem::class);
            $model->scenario = ConfigItem::SCENARIO_CREATE;
            $request = Yii::$app->request;
            $response = null;
            if ($request->isPost === true) {
                $body = $request->getBodyParams();
                $model->load($body);
                if ($model->validate() === true) {
                    if ($model->save() === true) {
                        $response = $this->redirect(['config-item/index']);
                    } else {
                        $model->addError('name', 'Une erreur c\est produite');
                    }
                }
            }
            if ($response === null) {
                $response = $this->render('create', [
                    'model' => $model,
                ]);
            }
            return $response;
        } catch (Exception $e)  {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }

    /**
     * Update
     *
     * @param $id
     * @return string|Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionUpdate($id) : string | Response
    {
        try {
            $model = ConfigItem::findOne(['id' => $id]);
            $model->scenario = ConfigItem::SCENARIO_UPDATE;
            $request = Yii::$app->request;
            $response = null;
            if ($request->isPost === true) {
                $body = $request->getBodyParams();
                $model->load($body);
                if ($model->validate() === true) {
                    if ($model->save() === true) {
                        $response = $this->redirect(['config-item/index']);
                    } else {
                        $model->addError('name', 'Une erreur c\est produite');
                    }
                }
            }
            if ($response === null) {
                $response = $this->render('update', [
                    'model' => $model
                ]);
            }
            return $response;
        } catch (Exception $e)  {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }
}
