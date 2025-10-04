<?php
/**
 * FileController.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package app\controllers\api
 */

namespace fractalCms\controllers\api;

use fractalCms\actions\ResumableDeleteAction;
use fractalCms\actions\ResumablePreviewAction;
use fractalCms\actions\ResumableUploadAction;
use fractalCms\components\Constant;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

class FileController extends BaseController
{


    /**
     * @inheritDoc
     */
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

    /**
     * @inheritDoc
     */
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
                ],
                [
                    'allow' => true,
                    'actions' => [ 'delete'],
                    'verbs' => ['delete'],
                    'roles' => [
                        Constant::PERMISSION_MAIN_ITEM.Constant::PERMISSION_ACTION_DELETE
                    ],
                ]
            ],
            'denyCallback' => function ($rule, $action) {
                throw new ForbiddenHttpException();
            }
        ];
        return $behaviors;
    }
}
