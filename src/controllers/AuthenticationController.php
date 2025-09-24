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

use Exception;
use fractalCms\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class AuthenticationController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['login', 'logout'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['login'],
                    'roles' => ['?'],
                    'denyCallback' => function ($rule, $action) {
                        return $this->redirect(['default/index']);
                    }
                ],
                [
                    'allow' => true,
                    'actions' => ['logout'],
                    'roles' => ['@'],
                    'denyCallback' => function ($rule, $action) {
                        return $this->redirect(['authentication/login']);
                    }
                ],
            ]
        ];
        return $behaviors;
    }

    public function actionLogin()
    {
        try {
            $model = Yii::createObject(User::class);
            $model->scenario = User::SCENARIO_LOGIN;
            $request = Yii::$app->request;
            $response = null;
            if ($request->isPost === true) {
                $model->load($request->bodyParams);
                if ($model->validate() === true) {
                    $user = User::findOne(['email' => $model->email]);
                    if ($user !== null && $user->validatePassword($model->tmpPassword) === true) {
                        Yii::$app->user->login($user, User::LOGIN_DURATION);
                        $response = $this->redirect(['default/index']);
                    } else {
                        $model->addError('email', 'Veuillez vérifier votre saisie');
                    }
                }
            }
            if ($response === null) {
                $response = $this->render('login', ['model' => $model]);
            }
            return $response;
        } catch (Exception $e)  {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    public function actionLogout()
    {
        try {
            if (Yii::$app->user->isGuest === false) {
                Yii::$app->user->logout();
            }
            return $this->redirect(['default/index']);
        } catch (Exception $e)  {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
}
