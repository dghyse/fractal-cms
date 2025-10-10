<?php
/**
 * ResumablePreviewAction.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package app\actions
 */
namespace fractalCms\actions;


use yii\base\Action;
use yii\web\NotFoundHttpException;
use Yii;

class ResumablePreviewAction extends Action
{
    /**
     * @var string
     */
    public $filetypeIconAlias = '@fractalCms/assets/static/icons/';

    /**
     * Preview files
     *
     * @return \yii\console\Response|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\web\RangeNotSatisfiableHttpException
     */
    public function run()
    {
        $name = Yii::$app->request->getQueryParam('name', null);
        $name = str_replace('@web/', '@webroot/', $name);
        $realName = Yii::getAlias($name);
        if (file_exists($realName) === false) {
            throw new NotFoundHttpException();
        }
        $mimeType = mime_content_type($realName);
        $fileName = pathinfo($realName, PATHINFO_BASENAME);
        if (strncmp('image/', $mimeType, 6) !== 0) {
            $realName = $this->prepareImage($fileName);
            $mimeType = mime_content_type($realName);
        }
        $handle = fopen($realName, 'r');
        return Yii::$app->response->sendStreamAsFile($handle, $fileName, ['inline' => true, 'mimeType' => $mimeType]);
    }

    /**
     * Prepare preview path file
     * @param string $filename
     * @return bool|string
     */
    protected function prepareImage($filename) {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $iconAlias = $this->filetypeIconAlias . $extension . '.png';
        $iconPath = Yii::getAlias($iconAlias);
        if (file_exists($iconPath) === true) {
            return $iconPath;
        } else {
            $iconAlias = $this->filetypeIconAlias . 'doc.png';
            return Yii::getAlias($iconAlias);
        }
    }
    
}
