<?php
/**
 * SitemapBuilder.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\config
 */
namespace fractalCms\helpers;

use fractalCms\models\Content;
use fractalCms\models\Menu;
use fractalCms\models\Seo;
use yii\base\Component;
use DOMDocument;
use DOMElement;
use Yii;
use Exception;
use yii\db\ActiveQuery;
use yii\helpers\Url;

class SitemapBuilder extends Component
{

    public $xml = null;

    public function get() : DOMDocument
    {
        try {
            if ($this->xml === null) {
                $dom = null;
                $seoQuery = Seo::find()->orderBy(['priority' => SORT_DESC]);
                $dom = $this->initXml($seoQuery);
                $this->xml = $dom;
            }
            return $this->xml;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }

    }

    protected function initXml(ActiveQuery $seoQuery) : DOMDocument
    {
        try {

            $domXml = new DOMDocument('1.0', 'UTF-8');
            $urlSet = $domXml->createElement('urlset');
            // xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
            $urlSet->setAttribute('xmlns','http://www.sitemaps.org/schemas/sitemap/0.9');
            $urlSet = $this->addElement($seoQuery, $domXml, $urlSet);
            $domXml->appendChild($urlSet);
            return $domXml;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }


    protected function addElement(ActiveQuery $seoQuery, DOMDocument $domXml, DOMElement $parent) : DOMElement
    {
        try {
            /** @var Seo $seo */
            foreach ($seoQuery->each() as $seo) {
                $contentTarget = $seo->getContent()->one();
                if ($contentTarget instanceof Content && (boolean)$contentTarget->active === true) {
                    $route = $contentTarget->getRoute();
                    $url = Url::toRoute($route, true);
                    $domUrl = $domXml->createElement('url');
                    $domLoc = $domXml->createElement('loc', $url);
                    $domLastdate = $domXml->createElement('lastmod', Yii::$app->formatter->asDate($contentTarget->dateUpdate, 'php:Y-m-d'));
                    $domFrq = $domXml->createElement('changefreq', $seo->changefreq);
                    $domPrio = $domXml->createElement('priority', $seo->priority);

                    $domUrl->appendChild($domLoc);
                    $domUrl->appendChild($domLastdate);
                    $domUrl->appendChild($domFrq);
                    $domUrl->appendChild($domPrio);
                    $parent->appendChild($domUrl);
                }
            }
            return $parent;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
}
