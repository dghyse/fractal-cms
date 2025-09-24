<?php
/**
 * FileController.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\config
 */

namespace fractalCms\controllers\api;

use Exception;
use fractalCms\actions\ItemAction;
use fractalCms\actions\ResumableDeleteAction;
use fractalCms\actions\ResumablePreviewAction;
use fractalCms\actions\ResumableUploadAction;
use fractalCms\components\Constant;
use fractalCms\helpers\Html;
use fractalCms\models\ConfigItem;
use fractalCms\models\Content;
use fractalCms\models\ElasticModel;
use fractalCms\models\User;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class FileController extends BaseController
{


    public function actions()
    {
        $actions = parent::actions();
        $actions['upload'] = [
            'class' => ResumableUploadAction::class,
        ];
        $actions['preview'] = [
            'class' => ResumablePreviewAction::class,
        ];
        $actions['delete'] = [
            'class' => ResumableDeleteAction::class,
        ];
        return $actions;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['upload', 'preview', 'delete'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['upload', 'preview'],
                    'verbs' => ['get', 'post'],
                    'roles' => [
                        Constant::PERMISSION_MAIN_ITEM.Constant::PERMISSION_ACTION_LIST,
                        Constant::PERMISSION_MAIN_ITEM.Constant::PERMISSION_ACTION_CREATE,
                        ],
                    'denyCallback' => function ($rule, $action) {
                        throw new ForbiddenHttpException();
                    }
                ],
                [
                    'allow' => true,
                    'actions' => [ 'delete'],
                    'verbs' => ['delete'],
                    'roles' => [
                        Constant::PERMISSION_MAIN_ITEM.Constant::PERMISSION_ACTION_DELETE
                    ],
                    'denyCallback' => function ($rule, $action) {
                        throw new ForbiddenHttpException();
                    }
                ]
            ]
        ];
        return $behaviors;
    }
}
