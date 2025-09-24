<?php
/**
 * Header.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package cms/widgets
 */
namespace fractalCms\widgets;

use yii\base\Widget;
use Yii;
use yii\helpers\Url;
use Exception;

class Header extends Widget
{

    public $logoutUrl = ['authentication/logout'];
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        Yii::debug('Trace: '.__METHOD__, __METHOD__);
        ob_start();
    }

    /**
     * Render widget
     *
     * @return string
     * @since 1.0
     */
    public function run()
    {
        try {
            Yii::debug('Trace: '.__METHOD__, __METHOD__);
            $content = ob_get_clean();
            return $this->render(
                'header', [
                    'identity' => Yii::$app->user->getIdentity(),
                    'logoutUrl' => Url::to($this->logoutUrl),
                ]
            );
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }

    }
}
