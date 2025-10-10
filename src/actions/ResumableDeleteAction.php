<?php
/**
 * ResumableDeleteAction.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package app\actions
 */
namespace fractalCms\actions;


use yii\base\Action;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use Yii;

class ResumableDeleteAction extends Action
{
    /**
     * Delete file
     *
     * @return mixed
     * @throws HttpException
     */
    public function run()
    {
        $name = Yii::$app->request->getQueryParam('name', null);
        $realNameAlias = $name;
        $realName = Yii::getAlias($realNameAlias);
        if (file_exists($realName) === true) {
            unlink($realName);
        }
        throw new HttpException(204);
    }

}
