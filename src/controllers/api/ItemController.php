<?php
/**
 * ContentApiController.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package app\controllers\api
 */

namespace fractalCms\controllers\api;

use fractalCms\actions\ItemAction;
use fractalCms\components\Constant;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

class ItemController extends BaseController
{


    /**
     * @inheritDoc
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['manage-items'] = [
            'class' => ItemAction::class,
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
            'only' => ['manage-items'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['manage-items'],
                    'verbs' => ['get', 'post'],
                    'roles' => [
                        Constant::PERMISSION_MAIN_ITEM.Constant::PERMISSION_ACTION_LIST,
                        Constant::PERMISSION_MAIN_ITEM.Constant::PERMISSION_ACTION_CREATE,
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
