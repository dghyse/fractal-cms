<?php
/**
 * TagController.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms\controllers\api
 */

namespace fractalCms\controllers\api;

use Exception;
use fractalCms\actions\ItemAction;
use fractalCms\components\Constant;
use fractalCms\models\Item;
use fractalCms\models\Seo;
use fractalCms\models\Slug;
use fractalCms\models\Tag;
use fractalCms\models\TagItem;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class TagController extends BaseController
{

    /**
     * @inheritDoc
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['manage-items'] = [
            'class' => ItemAction::class,
            'targetClass' => Tag::class,
            'targetRelationClass' => TagItem::class
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
            'only' => ['index'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['delete'],
                    'verbs' => ['delete'],
                    'roles' => [Constant::PERMISSION_MAIN_TAG.Constant::PERMISSION_ACTION_DELETE],
                    'denyCallback' => function ($rule, $action) {
                        throw new ForbiddenHttpException();
                    }
                ],
                [
                    'allow' => true,
                    'actions' => ['activate'],
                    'verbs' => ['get'],
                    'roles' => [Constant::PERMISSION_MAIN_TAG.Constant::PERMISSION_ACTION_ACTIVATION],
                    'denyCallback' => function ($rule, $action) {
                        throw new ForbiddenHttpException();
                    }
                ],
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
            ]
        ];
        return $behaviors;
    }


    /**
     * Delete content
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
            /** @var Tag $model */
            $model = Tag::findOne(['id' => $id]);
            if ($model === null) {
                throw new NotFoundHttpException('tag not found');
            }
            $seo = $model->getSeo()->one();
            $slug = $model->getSlug()->one();
            $model->seoId = null;
            $model->slugId = null;
            $model->save(false, ['seoId', 'slugId']);

            $itemQuery = $model->getItems();
            /** @var Item $item */
            foreach ($itemQuery->each() as $item) {
                $item->delete();
            }
            if( $seo instanceof Seo) {
                $seo->delete();
            }
            if ($slug instanceof Slug) {
                $slug->delete();
            }
            $model->delete();
            $response->statusCode = 204;
            return $response;
        } catch (Exception $e)  {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }

    /**
     * Activate Content
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
            /** @var Tag $model */
            $model = Tag::findOne(['id' => $id]);
            if ($model === null) {
                throw new NotFoundHttpException('tag not found');
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
