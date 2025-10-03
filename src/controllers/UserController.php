<?php
/**
 * main.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\config
 */

namespace fractalCms\controllers;

use fractalCms\components\Constant;
use fractalCms\models\User;
use yii\filters\AccessControl;
use yii\web\Controller;
use Exception;
use Yii;
use yii\web\Response;

class UserController extends Controller
{


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
                    'roles' => [Constant::PERMISSION_MAIN_USER.Constant::PERMISSION_ACTION_LIST],
                    'denyCallback' => function ($rule, $action) {
                        return $this->redirect(['default/index']);
                    }
                ],
                [
                    'allow' => true,
                    'actions' => ['create'],
                    'roles' => [Constant::PERMISSION_MAIN_USER.Constant::PERMISSION_ACTION_CREATE],
                    'denyCallback' => function ($rule, $action) {
                        return $this->redirect(['default/index']);
                    }
                ],
                [
                    'allow' => true,
                    'actions' => ['update'],
                    'roles' => [Constant::PERMISSION_MAIN_USER.Constant::PERMISSION_ACTION_UPDATE],
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
            $users = User::find()->all();
            return $this->render('index', [
                'models' => $users
            ]);
        } catch (Exception $e)  {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }


    public function actionCreate() : string | Response
    {
        try {
            $model = Yii::createObject(User::class);
            $model->scenario = User::SCENARIO_CREATE;
            $request = Yii::$app->request;
            $response = null;
            $model->buildAuthRules();
            if ($request->isPost === true) {
                $body = $request->getBodyParams();
                $model->load($body);
                if ($model->validate() === true) {
                    $model->hashPassword();
                    if ($model->save() === true) {
                        $model->manageAuthRules();
                        $response = $this->redirect(['user/index']);
                    } else {
                        $model->addError('email', 'Une erreur c\est produite');
                    }
                }
            }
            if ($response === null) {
                $response = $this->render('create', [
                    'model' => $model
                ]);
            }
            return $response;
        } catch (Exception $e)  {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }

    public function actionUpdate($id) : string | Response
    {
        try {
            $model = User::findOne(['id' => $id]);
            $model->scenario = User::SCENARIO_UPDATE;
            $model->buildAuthRules();
            $request = Yii::$app->request;
            $response = null;
            if ($request->isPost === true) {
                $body = $request->getBodyParams();
                $model->load($body);
                $hasNewPassword = (empty($model->tmpPassword) === false);
                if ($model->validate() === true) {
                    $validatePassword = true;
                    if ($hasNewPassword === true) {
                        $model->scenario = User::SCENARIO_MOT_PASSE;
                        $validatePassword = $model->validate();
                        if ($validatePassword === true) {
                            $model->hashPassword();
                        }
                    }
                    if ($validatePassword === true) {
                        if ($model->save() === true) {
                            $model->manageAuthRules();
                            $response = $this->redirect(['user/index']);
                        } else {
                            $model->addError('email', 'Une erreur c\est produite');
                        }
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
