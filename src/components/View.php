<?php
/**
 * View.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package fractalCms\components
 */
namespace fractalCms\components;

use yii\helpers\Html;
use yii\helpers\Json;
use Exception;
use Yii;

class View extends \yii\web\View
{
    public $json = [];

    protected function renderHeadHtml() : string
    {
        $line =  parent::renderHeadHtml();
        if (empty($this->json) === false) {
            $lines = [];
            if (count($this->json) > 1) {
                $lines[] = Html::tag('script', Json::encode($this->json), ['type' => 'application/ld+json']);
            } else {
                $lines[] = Html::tag('script', Json::encode($this->json[0]), ['type' => 'application/ld+json']);
            }
            $jsonLine = implode("\n", $lines);
            $line .= $jsonLine;
        }
        return $line;
    }

    public function registerJsonLd($value)
    {
        try {
            $this->json[] = $value;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
}
