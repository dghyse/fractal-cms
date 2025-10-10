<?php
/**
 * ParameterController.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package app\controllers
 */

namespace fractalCms\controllers;

use Exception;
use fractalCms\components\Constant;
use fractalCms\models\Parameter;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ParameterController extends Controller
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
                    'roles' => [Constant::PERMISSION_MAIN_PARAMETER.Constant::PERMISSION_ACTION_LIST],
                    'denyCallback' => function ($rule, $action) {
                        return $this->redirect(['default/index']);
                    }
                ],
                [
                    'allow' => true,
                    'actions' => ['create'],
                    'roles' => [Constant::PERMISSION_MAIN_PARAMETER.Constant::PERMISSION_ACTION_CREATE],
                    'denyCallback' => function ($rule, $action) {
                        return $this->redirect(['default/index']);
                    }
                ],
                [
                    'allow' => true,
                    'actions' => ['update'],
                    'roles' => [Constant::PERMISSION_MAIN_PARAMETER.Constant::PERMISSION_ACTION_UPDATE],
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
            $modelsQuery = Parameter::find()->orderBy(['group' => SORT_ASC, 'name' => SORT_ASC]);
            return $this->render('index', [
                'modelsQuery' => $modelsQuery
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
            $response = null;
            $model = Yii::createObject(Parameter::class);
            $model->scenario = Parameter::SCENARIO_CREATE;

            $request = Yii::$app->request;

            if ($request->isPost === true) {
                $body = $request->getBodyParams();
                $model->load($body);
                if ($model->validate() === true) {
                    $model->save();
                    $model->refresh();
                    $response = $this->redirect(['parameter/update', 'id' => $model->id]);
                }
            }
            if ($response === null) {
                $response =  $this->render('manage', [
                    'model' => $model,
                ]);
            }
            return  $response;
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
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionUpdate($id = null) : string | Response
    {
        try {
            //find menu
            $model = Parameter::findOne(['id' => $id]);
            if ($model === null) {
                throw new NotFoundHttpException('Parameter not found');
            }
            $model->scenario = Parameter::SCENARIO_UPDATE;
            $request = Yii::$app->request;
            if ($request->isPost === true) {
                $body = $request->getBodyParams();
                $model->load($body);
                if ($model->validate() === true) {
                    $model->save();
                    $model->refresh();
                }
            }

            return $this->render('manage', [
                'model' => $model,
            ]);
        } catch (Exception $e)  {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }

}
