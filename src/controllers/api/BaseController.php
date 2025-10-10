<?php
/**
 * BaseApiController.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package app\controllers\api
 */

namespace fractalCms\controllers\api;

use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\Response;

class BaseController extends Controller
{


    /**
     * @inheritDoc
     */
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
}
