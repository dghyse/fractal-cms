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

use Exception;
use fractalCms\components\Constant;
use fractalCms\helpers\MenuItemBuilder;
use fractalCms\models\Menu;
use fractalCms\models\User;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class MenuController extends BaseController
{

    protected MenuItemBuilder $menuItemBuilder;

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['delete', 'activate', 'manage-menu-items'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['delete'],
                    'verbs' => ['delete'],
                    'roles' => [Constant::PERMISSION_MAIN_MENU.Constant::PERMISSION_ACTION_DELETE],
                ],
                [
                    'allow' => true,
                    'actions' => ['activate'],
                    'verbs' => ['get'],
                    'roles' => [Constant::PERMISSION_MAIN_MENU.Constant::PERMISSION_ACTION_ACTIVATION],
                ],
                [
                    'allow' => true,
                    'actions' => ['manage-menu-items'],
                    'verbs' => ['post'],
                    'roles' => [Constant::PERMISSION_MAIN_MENU.Constant::PERMISSION_ACTION_UPDATE],
                ],
            ],
            'denyCallback' => function ($rule, $action) {
                throw new ForbiddenHttpException();
            }
        ];
        return $behaviors;
    }


    public function __construct($id, $module, MenuItemBuilder $menuItemBuilder, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->menuItemBuilder = $menuItemBuilder;
    }

    /**
     * Delete menu
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
            $model = Menu::findOne(['id' => $id]);
            if ($model === null) {
                throw new NotFoundHttpException('content not found');
            }
            $model->delete();
            $response->statusCode = 204;
            return $response;
        } catch (Exception $e)  {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }



    public function actionManageMenuItems() : Response
    {
        try {
            $response = Yii::$app->getResponse();
            $request = Yii::$app->request;
            $id = $request->getQueryParam('id');
            $body = $request->getBodyParams();
            $model = Menu::findOne(['id' => $id]);
            if ($model === null) {
                throw new NotFoundHttpException('content not found');
            }
            $model->scenario = Menu::SCENARIO_MOVE_MENU_ITEM;
            $model->load($body, '');
            if($model->validate() === true) {
                $success = $model->moveMenuItem();
                if ($success === true) {
                    $menuItemHtml = null;
                    if ($this->menuItemBuilder !== null) {
                        $menuItemHtml = $this->menuItemBuilder->build($model);
                    }
                    $html = $this->renderPartial('@fractalCms/views/menu/_menu_item_lines', ['menuItemHtml' => $menuItemHtml]);
                    $response->statusCode = 201;
                    $response->data = $html;
                } else {
                    $response->statusCode = 400;
                }
            } else {
                $response->statusCode = 400;
            }

            return $response;
        } catch (Exception $e)  {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }

    /**
     * Active menu
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
            $model = Menu::findOne(['id' => $id]);
            if ($model === null) {
                throw new NotFoundHttpException('content not found');
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
