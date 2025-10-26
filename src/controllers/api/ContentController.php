<?php
/**
 * ContentController.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms\controllers\api
 */

namespace fractalCms\controllers\api;

use Exception;
use fractalCms\components\Constant;
use fractalCms\models\Content;
use fractalCms\models\Item;
use fractalCms\models\Seo;
use fractalCms\models\Slug;
use fractalCms\models\User;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ContentController extends BaseController
{


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
                    'roles' => [Constant::PERMISSION_MAIN_CONTENT.Constant::PERMISSION_ACTION_DELETE],
                    'denyCallback' => function ($rule, $action) {
                        throw new ForbiddenHttpException();
                    }
                ],
                [
                    'allow' => true,
                    'actions' => ['activate'],
                    'verbs' => ['get'],
                    'roles' => [Constant::PERMISSION_MAIN_CONTENT.Constant::PERMISSION_ACTION_ACTIVATION],
                    'denyCallback' => function ($rule, $action) {
                        throw new ForbiddenHttpException();
                    }
                ],
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
            $model = Content::findOne(['id' => $id]);
            if ($model === null) {
                throw new NotFoundHttpException('content not found');
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
            /** @var User $model */
            $model = Content::findOne(['id' => $id]);
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
