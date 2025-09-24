<?php
/**
 * MenuController.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\config
 */

namespace fractalCms\controllers;

use Exception;
use fractalCms\components\Constant;
use fractalCms\controllers\api\ItemController;
use fractalCms\helpers\Cms;
use fractalCms\models\ConfigType;
use fractalCms\models\Content;
use fractalCms\models\Item;
use fractalCms\models\Menu;
use fractalCms\models\MenuItem;
use fractalCms\models\Slug;
use Yii;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class MenuItemController extends Controller
{


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['create', 'update'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['create'],
                    'roles' => [Constant::PERMISSION_MAIN_MENU.Constant::PERMISSION_ACTION_CREATE],
                    'denyCallback' => function ($rule, $action) {
                        return $this->redirect(['default/index']);
                    }
                ],
                [
                    'allow' => true,
                    'actions' => ['update'],
                    'roles' => [Constant::PERMISSION_MAIN_MENU.Constant::PERMISSION_ACTION_UPDATE],
                    'denyCallback' => function ($rule, $action) {
                        return $this->redirect(['default/index']);
                    }
                ]
            ]
        ];
        return $behaviors;
    }

    public function actionCreate($menuId) : string | Response
    {
        try {
            $response = null;
            $menu = Menu::findOne($menuId);
            if ($menu === null) {
                throw new NotFoundHttpException('Menu not found');
            }
            $model = Yii::createObject(MenuItem::class);
            $model->scenario = MenuItem::SCENARIO_CREATE;
            $request = Yii::$app->request;
            $contents = Cms::getStructure();
            $menusItems = Cms::getMenuItemStructure($menuId);

            if ($request->isPost === true) {
                $body = $request->getBodyParams();
                $model->load($body);
                $model->menuId = $menuId;
                $model->attach();
                if ($model->validate() === true) {
                    $model->save();
                    $model->refresh();
                    $response = $this->redirect(['menu/update', 'id' => $menuId]);
                }
            }
            if ($response === null) {
                $response =  $this->render('manage', [
                    'model' => $model,
                    'menusItems' => $menusItems,
                    'contents' => $contents
                ]);
            }
            return  $response;
        } catch (Exception $e)  {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }



    public function actionUpdate($menuId, $id) : string | Response
    {
        try {
            $response = null;
            $menu = Menu::findOne($menuId);
            if ($menu === null) {
                throw new NotFoundHttpException('Menu not found');
            }
            $menusItems = Cms::getMenuItemStructure($menuId, $id);

            //find menu
            $model = MenuItem::findOne(['id' => $id]);
            if ($model === null) {
                throw new NotFoundHttpException('Menu item not found');
            }
            $model->scenario = MenuItem::SCENARIO_UPDATE;
            $contents = Cms::getStructure();
            $request = Yii::$app->request;
            if ($request->isPost === true) {
                $body = $request->getBodyParams();
                $oldParentId = $model->menuItemId;
                $model->load($body);
                if ($model->validate() === true) {
                    if ($oldParentId !== (int)$model->menuItemId) {
                        $model->attach();
                    }
                    $model->save();
                    $model->refresh();
                    $response = $this->redirect(['menu/update', 'id' => $menuId]);
                }
            }
            if ($response === null) {
                $response = $this->render('manage', [
                    'model' => $model,
                    'menusItems' => $menusItems,
                    'contents' => $contents
                ]);
            }
            return $response;
        } catch (Exception $e)  {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }

}
