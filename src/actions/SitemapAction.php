<?php
/**
 * SitemapAction.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms\actions
 */
namespace fractalCms\actions;

use fractalCms\helpers\SitemapBuilder;
use yii\base\Action;
use Exception;
use Yii;

class SitemapAction extends Action
{

    /**
     * Generate sitemap.xml
     *
     * @param SitemapBuilder $sitemapBuilder
     * @return false|string
     * @throws Exception
     */
    public function run(SitemapBuilder $sitemapBuilder)
    {
        try {
            $domXml = $sitemapBuilder->get();
            Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
            Yii::$app->response->headers->set('Content-Type', 'application/xml; charset=UTF-8');
            return $domXml->saveXML();
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
}
