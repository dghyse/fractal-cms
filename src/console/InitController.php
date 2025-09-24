<?php
/**
 * AdminController.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\config
 */
namespace fractalCms\console;

use Exception;
use fractalCms\components\Constant;
use fractalCms\models\Content;
use fractalCms\models\Slug;
use fractalCms\models\User;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Json;

class InitController extends Controller
{
    public function actionIndex()
    {
        try {
            $this->stdout('Create main section '."\n");
            $content = Yii::createObject(Content::class);
            $content->scenario = Content::SCENARIO_INIT;
            $content->name = 'main';
            $content->type = 'section';
            $content->pathKey = '1';
            $content->active = 1;
            if ($content->validate() === true) {
                $slug = Yii::createObject(Slug::class);
                $slug->scenario = Slug::SCENARIO_CREATE;
                $slug->path = 'accueil';
                $slug->active = 1;
                if ($slug->save() === true) {
                    $content->slugId = $slug->id;
                    $content->save();
                    $this->stdout('Save main section '.$content->name.' '.$content->type."\n");
                }  else {
                    $this->stdout('Main section is invalid : '.Json::encode($slug->errors)."\n");
                    return ExitCode::UNSPECIFIED_ERROR;
                }

            } else {
                $this->stdout('Main section is invalid : '.Json::encode($content->errors)."\n");
                return ExitCode::UNSPECIFIED_ERROR;
            }
            return ExitCode::OK;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
}
