<?php
/**
 * BaseApiController.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\config
 */

namespace fractalCms\controllers\api;

use Exception;
use fractalCms\models\User;
use Yii;
use yii\db\Expression;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class BaseController extends Controller
{


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
                'application/xml' => Response::FORMAT_XML,
                'text/csv' => Response::FORMAT_RAW,
                'application/pdf' => Response::FORMAT_RAW,
                'text/html' => Response::FORMAT_HTML,
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => Response::FORMAT_RAW,
            ],
        ];
        unset($behaviors['authenticator']);
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }


    public function actionDelete($id) : Response
    {
        try {
            $response = Yii::$app->getResponse();
            $model = User::findOne(['id' => $id]);
            if ($model === null) {
                throw new NotFoundHttpException('user not found');
            }
            $model->delete();
            $response->statusCode = 204;
            return $response;
        } catch (Exception $e)  {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }

    public function actionActivate($id) : Response
    {
        try {
            $response = Yii::$app->getResponse();
            /** @var User $model */
            $model = User::findOne(['id' => $id]);
            if ($model === null) {
                throw new NotFoundHttpException('user not found');
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
