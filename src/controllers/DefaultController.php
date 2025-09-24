<?php
/**
 * main.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\config
 *
 */

namespace fractalCms\controllers;

use fractalCms\models\Content;
use fractalCms\models\User;
use yii\filters\AccessControl;
use yii\web\Controller;
use Exception;
use Yii;

class DefaultController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['index'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index'],
                    'roles' => ['@'],
                    'denyCallback' => function ($rule, $action) {
                        return $this->redirect(['authentication/login']);
                    }
                ],
            ]
        ];
        return $behaviors;
    }


    public function actionIndex()
    {
        try {
            /** @var User $user */
            $user = Yii::$app->user->getIdentity();
            $nbSections = Content::find()->andWhere(['type' => Content::TYPE_SECTION])->count();
            $nbActicles = Content::find()->andWhere(['type' => Content::TYPE_ARTICLE])->count();
            $lastDate = Content::find()->max('dateCreate');
            return $this->render('index', [
                'model' => $user,
                'nbSections' => $nbSections,
                'nbArticles' => $nbActicles,
                'lastDate' => $lastDate,
            ]);
        } catch (Exception $e)  {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }
}
