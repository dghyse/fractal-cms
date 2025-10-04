<?php
/**
 * UrlRule.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package app\components
 */
namespace fractalCms\components;

use fractalCms\models\Content;
use fractalCms\models\Slug;
use yii\base\BaseObject;
use Exception;
use Yii;
use yii\web\UrlRuleInterface;

class UrlRule extends BaseObject implements UrlRuleInterface
{

    public function createUrl($manager, $route, $params) : string | null
    {
        try {
            $prettyUrl = $route;
            $matches = [];
            if ( preg_match('/(content)-(\d+)$/', $route, $matches) === 1) {
                $elementName = $matches[1];
                $elementId = $matches[2];
                $content = Content::findOne($elementId);
                if ($content instanceof Content) {
                    $slug = Slug::findOne($content->slugId);
                    if ($slug instanceof Slug) {
                        $host = (empty($slug->host) === false) ? $slug->host : '';
                        $prettyUrl = $host.'/'.$slug->path;
                    }
                }
            }
            if (empty($params) === false && ($queryParams = http_build_query($params)) !== '') {
                $prettyUrl .= '?' . $queryParams;
            }
            return $prettyUrl;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    public function parseRequest($manager, $request)
    {
        try {
            $pathInfo = $request->getPathInfo();
            $params = $request->getQueryParams();
            $result= [
                $pathInfo,
                $params
            ];
            $slug = Slug::find()->andWhere(['path' => $pathInfo, 'active' => 1])->one();
            if ($slug instanceof Slug) {
                $content = $slug->getContent()->andWhere(['active' => 1])->one();
                if ($content instanceof Content) {
                    $result= [
                        $content->configType->config,
                        $params
                    ];
                }
            }
            return $result;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
}
